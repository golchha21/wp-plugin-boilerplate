<?php

namespace WPPluginBoilerplate\Admin\Actions;

use WPPluginBoilerplate\Core\Support\ScopeResolver;
use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Contracts\SettingsContract;
use WPPluginBoilerplate\Settings\SettingsRepository;
use WPPluginBoilerplate\Settings\Tabs;

class ResetSettings
{
	public function handle(): void
	{
		if (!\current_user_can('manage_options')) {
			\wp_die(
				__('Sorry, you are not allowed to reset settings.', Plugin::text_domain())
			);
		}

		\check_admin_referer(Plugin::prefix() . 'reset');

		$tab_id = $_GET['tab'] ?? null;

		if (!$tab_id) {
			\wp_die(__('No tab specified.', Plugin::text_domain()));
		}

		$matched_tab = null;

		foreach (Tabs::all() as $tab) {
			if ($tab->id() === $tab_id) {
				$matched_tab = $tab;
				break;
			}
		}

		if (!$matched_tab) {
			\wp_die(__('Invalid tab.', Plugin::text_domain()));
		}

		if (!$matched_tab instanceof SettingsContract) {
			\wp_die(__('This tab does not support settings.', Plugin::text_domain()));
		}

		if (!\current_user_can($matched_tab->capability())) {
			\wp_die(__('You do not have permission to reset this tab.', Plugin::text_domain()));
		}

		// 🔥 Resolve scope properly
		$scope = ScopeResolver::resolve($matched_tab);

		// Build defaults from schema
		$defaults = [];

		foreach ($matched_tab->fields() as $key => $definition) {
			$defaults[$key] = $definition['default'] ?? null;
		}

		SettingsRepository::update(
			$matched_tab->optionKey(),
			$defaults,
			$scope
		);

		\wp_safe_redirect(
			\admin_url(
				'admin.php?page=' . Plugin::slug() . '&tab=' . $matched_tab->id()
			)
		);

		exit;
	}
}
