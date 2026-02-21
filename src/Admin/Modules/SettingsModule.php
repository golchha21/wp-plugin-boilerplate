<?php

namespace WPPluginBoilerplate\Admin\Modules;

use WPPluginBoilerplate\Admin\Actions\ExportSettings;
use WPPluginBoilerplate\Admin\Actions\ImportSettings;
use WPPluginBoilerplate\Admin\Actions\ResetSettings;
use WPPluginBoilerplate\Admin\Contracts\AdminModule;
use WPPluginBoilerplate\Loader;
use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Contracts\SettingsContract;
use WPPluginBoilerplate\Settings\Contracts\TabContract;
use WPPluginBoilerplate\Settings\Tabs;
use WPPluginBoilerplate\Settings\SettingsRepository;
use WPPluginBoilerplate\Core\Fields\FieldDefinition;
use WPPluginBoilerplate\Core\Fields\FieldRenderer;

class SettingsModule implements AdminModule
{
	public function register(Loader $loader): void
	{
		$loader->action('admin_init', $this, 'boot');
		$loader->action('admin_post_'. Plugin::prefix() .'reset', new ResetSettings(), 'handle');
		$loader->action('admin_post_'. Plugin::prefix() .'export', new ExportSettings(), 'handle');
		$loader->action('admin_post_'. Plugin::prefix() .'import', new ImportSettings(), 'handle');
		$loader->action('admin_menu', $this, 'register_menus');

		$loader->filter('plugin_action_links_' . plugin_basename(Plugin::file()), $this, 'add_settings_link');
	}

	/* -------------------------------------------------
	 * SETTINGS REGISTRATION
	 * ------------------------------------------------- */

	public function boot(): void
	{
		foreach ($this->getTabs() as $tab) {

			if (!$tab instanceof SettingsContract) {
				continue;
			}

			$this->registerTab($tab);
		}
	}

	protected function registerTab(SettingsContract $tab): void
	{
		$option_key = $tab->optionKey();

		\register_setting($option_key, $option_key);
		\add_settings_section('default', '', '__return_null', $option_key);

		foreach ($tab->fields() as $field_key => $definition) {

			$field = FieldDefinition::fromSchema($field_key, $definition);

			\add_settings_field(
				$field->key,
				\esc_html($field->label),
				function () use ($tab, $field) {

					$values = SettingsRepository::get($tab->optionKey());
					$raw = $values[$field->key] ?? null;

					$value = ($raw === null || $raw === 'null')
						? $field->resolvedDefault()
						: $raw;

					echo '<div class="wppb-fields-row">';
					FieldRenderer::render(
						$tab->optionKey(),
						$field,
						$value
					);
					echo '</div>';
				},
				$option_key,
				'default'
			);
		}
	}

	/* -------------------------------------------------
	 * MENU REGISTRATION
	 * ------------------------------------------------- */

	public function register_menus(): void
	{
		if (Plugin::menu_parent()) {
			$this->register_as_submenu();
		} else {
			$this->register_as_top_level();
		}

		$this->register_tab_submenus();
	}

	private function register_as_top_level(): void
	{
		\add_menu_page(
			'WP Plugin Boilerplate',
			'WP Plugin Boilerplate',
			$this->resolve_menu_capability(),
			Plugin::slug(),
			[ $this, 'render_page' ],
			'dashicons-admin-generic'
		);
	}

	private function register_as_submenu(): void
	{
		\add_submenu_page(
			Plugin::menu_parent(),
			'WP Plugin Boilerplate',
			'WP Plugin Boilerplate',
			$this->resolve_menu_capability(),
			Plugin::slug(),
			[ $this, 'render_page' ]
		);
	}

	private function register_tab_submenus(): void
	{
		if (!Plugin::tabs_as_submenu()) {
			return;
		}

		foreach ($this->getVisibleTabs() as $tab) {
			\add_submenu_page(
				Plugin::slug(),
				$tab->label(),
				$tab->label(),
				$tab->capability(),
				Plugin::slug() . '&tab=' . $tab->id(),
				[ $this, 'render_page' ]
			);
		}
	}

	/* -------------------------------------------------
	 * RENDERING
	 * ------------------------------------------------- */

	public function render_page(): void
	{
		$tabs = $this->getVisibleTabs();
		$active = $this->resolveActiveTab($tabs);

		if (!$active) {
			return;
		}

		echo '<div class="wrap wppb-admin">';
		echo '<h1>WP Plugin Boilerplate</h1>';
		echo '<nav class="nav-tab-wrapper">';

		foreach ($tabs as $tab) {
			$active_class = $tab->id() === $active->id() ? 'nav-tab-active' : '';
			$url = \admin_url('admin.php?page=' . Plugin::slug() . '&tab=' . $tab->id());
			echo "<a class='nav-tab {$active_class}' href='{$url}'>{$tab->label()}</a>";
		}

		echo '</nav>';

		// If it is a settings tab, wrap in form
		if ($active instanceof SettingsContract) {

			if (\current_user_can($active->capability())) {

				echo '<form method="post" action="options.php">';
				$active->render();
				\submit_button(__('Save Settings', Plugin::text_domain()));
				echo '</form>';

				// 🔥 Reset button
				$reset_url = \wp_nonce_url(
					\admin_url(
						'admin-post.php?action=' . Plugin::prefix() . 'reset&tab=' . $active->id()
					),
					Plugin::prefix() . 'reset'
				);

				echo '<hr />';
				echo '<a href="' . \esc_url($reset_url) . '" class="button button-secondary">';
				echo \esc_html__('Reset to Defaults', Plugin::text_domain());
				echo '</a>';
			}
		} else {
			// View-only tab
			$active->render();
		}

		echo '</div>';
	}

	private function resolveActiveTab(array $tabs): ?TabContract
	{
		if (empty($tabs)) {
			return null;
		}

		$current = $_GET['tab'] ?? $tabs[0]->id();

		foreach ($tabs as $tab) {
			if ($tab->id() === $current) {
				return $tab;
			}
		}

		return $tabs[0];
	}

	private function getVisibleTabs(): array
	{
		return array_values(
			array_filter(
				$this->getTabs(),
				function ($tab) {

					if (!\current_user_can($tab->capability())) {
						return false;
					}

					if (method_exists($tab, 'scope')
						&& $tab::scope() === 'network'
						&& !\is_network_admin()) {
						return false;
					}

					return true;
				}
			)
		);
	}

	/* -------------------------------------------------
	 * CAPABILITY RESOLUTION
	 * ------------------------------------------------- */

	private function resolve_menu_capability(): string
	{
		$selected_capability = 'manage_options';
		$max_role_count = 0;

		foreach ($this->getTabs() as $tab) {

			$capability = $tab->capability();
			$role_count = $this->count_roles_with_capability($capability);

			if ($role_count > $max_role_count) {
				$max_role_count = $role_count;
				$selected_capability = $capability;
			}
		}

		return $selected_capability;
	}

	private function count_roles_with_capability(string $capability): int
	{
		global $wp_roles;

		if (!$wp_roles) {
			return 0;
		}

		$count = 0;

		foreach ($wp_roles->roles as $role) {
			if (!empty($role['capabilities'][$capability])) {
				$count++;
			}
		}

		return $count;
	}

	/* -------------------------------------------------
	 * HELPERS
	 * ------------------------------------------------- */

	private function getTabs(): array
	{
		return Tabs::all();
	}

	public function add_settings_link(array $links): array
	{
		$url = \admin_url('admin.php?page=' . Plugin::slug());

		array_unshift(
			$links,
			sprintf(
				'<a href="%s">%s</a>',
				\esc_url($url),
				\esc_html__('Settings', Plugin::text_domain())
			)
		);

		return $links;
	}
}
