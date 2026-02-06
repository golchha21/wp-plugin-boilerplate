<?php

namespace WPPluginBoilerplate\Admin;

use WPPluginBoilerplate\Admin\Actions\ExportSettings;
use WPPluginBoilerplate\Admin\Actions\ImportSettings;
use WPPluginBoilerplate\Admin\Actions\ResetSettings;
use WPPluginBoilerplate\Loader;
use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Registry\SettingsRegistrar;
use WPPluginBoilerplate\Settings\Tabs;

class Admin
{
	public function register(Loader $loader): void
	{
		$loader->action('admin_menu', $this, 'register_menus');

		$loader->action('admin_init', new SettingsRegistrar(), 'register');

		$loader->action('admin_post_'. Plugin::prefix() .'reset', new ResetSettings(), 'handle');

		$loader->action('admin_post_'. Plugin::prefix() .'export', new ExportSettings(), 'handle');

		$loader->action('admin_post_'. Plugin::prefix() .'import', new ImportSettings(), 'handle');

		$loader->action('admin_enqueue_scripts', $this, 'enqueue_assets');

		$loader->filter('plugin_action_links_' . plugin_basename(Plugin::file()), $this, 'add_settings_link');

	}

	public function register_menus(): void
	{
		if (Plugin::menu_parent()) {
			self::register_as_submenu();
		} else {
			self::register_as_top_level();
		}

		self::register_tab_submenus();
	}

	public function render_page(): void
	{
		$tabs = Tabs::all();
		$active = Tabs::active();

		echo '<div class="wrap">';
		echo '<h1>WP Plugin Boilerplate</h1>';
		echo '<nav class="nav-tab-wrapper">';

		foreach ($tabs as $tab) {
			$active_class = $tab->id() === $active->id() ? 'nav-tab-active' : '';
			$url = admin_url('admin.php?page=wp-plugin-boilerplate&tab=' . $tab->id());
			echo "<a class='nav-tab {$active_class}' href='{$url}'>{$tab->label()}</a>";
		}

		echo '</nav>';

		if ($active->hasForm() && current_user_can($active->manageCapability())) {

			echo '<form method="post" action="options.php">';

			$active->render();

			submit_button(__('Save Settings', Plugin::text_domain()));

			if ($active->hasActions()) {
				echo '<hr />';

				$reset_url = wp_nonce_url(
					admin_url(
						'admin-post.php?action='. Plugin::prefix() .'reset&tab=' . $active->id()
					),
					Plugin::prefix() .'reset'
				);

				echo '<a href="' . esc_url($reset_url) . '" class="button button-secondary">';
				echo esc_html__('Reset to Defaults', Plugin::text_domain());
				echo '</a>';
			}

			echo '</form>';

		} else {
			$active->render();
		}

		echo '</div>';
	}

	public function enqueue_assets(): void
	{
		// Media
		wp_enqueue_media();

		wp_enqueue_script(Plugin::prefix() . 'media', Plugin::url() . 'assets/admin/js/media.js', ['jquery'], Plugin::version(), true);
		wp_enqueue_script(Plugin::prefix() . 'tools', Plugin::url() . 'assets/admin/js/tools.js', ['jquery'], Plugin::version(), true);

		// Color picker
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');

		wp_add_inline_script('wp-color-picker', "jQuery('.wppb-color-field').wpColorPicker();");

		wp_enqueue_style(Plugin::prefix() . 'admin', Plugin::url() . 'assets/admin/css/admin.css', [], Plugin::version());
	}

	public function add_settings_link(array $links): array
	{
		$url = admin_url('admin.php?page=' . Plugin::slug());

		array_unshift(
			$links,
			sprintf(
				'<a href="%s">%s</a>',
				esc_url($url),
				esc_html__('Settings', Plugin::text_domain())
			)
		);

		return $links;
	}

	/**
	 * Register as a top-level admin menu.
	 */
	private function register_as_top_level(): void
	{
		add_menu_page(
			'WP Plugin Boilerplate',
			'WP Boilerplate',
			'manage_options',
			Plugin::slug(),
			[ $this, 'render_page' ],
			'dashicons-admin-generic'
		);
	}

	/**
	 * Register under a core WordPress menu.
	 */
	private function register_as_submenu(): void
	{
		add_submenu_page(
			Plugin::menu_parent(),
			'WP Plugin Boilerplate',
			'WP Boilerplate',
			'manage_options',
			Plugin::slug(),
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Optionally expose tabs as submenu items.
	 */
	private function register_tab_submenus(): void
	{
		if (!Plugin::tabs_as_submenu()) {
			return;
		}

		foreach (Tabs::all() as $tab) {
			add_submenu_page(
				Plugin::slug(),
				$tab->label(),
				$tab->label(),
				$tab->manageCapability(),
				Plugin::slug() . '&tab=' . $tab->id(),
				[ $this, 'render_page' ]
			);
		}
	}
}
