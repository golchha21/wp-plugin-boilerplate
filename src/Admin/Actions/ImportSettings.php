<?php

namespace WPPluginBoilerplate\Admin\Actions;

use WPPluginBoilerplate\Core\Support\ScopeResolver;
use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Contracts\SettingsContract;
use WPPluginBoilerplate\Settings\SettingsRepository;
use WPPluginBoilerplate\Settings\Tabs;

class ImportSettings
{
	public function handle(): void
	{
		// Global capability check
		if (!\current_user_can('manage_options')) {
			\wp_die(
				__('Sorry, you are not allowed to import settings.', Plugin::text_domain())
			);
		}

		// Global nonce check
		\check_admin_referer(Plugin::prefix() . 'import_all');

		$file = $_FILES['import_file'] ?? null;

		if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
			\wp_die(__('Invalid upload.', Plugin::text_domain()));
		}

		$payload = \json_decode(
			\file_get_contents($file['tmp_name']),
			true
		);

		if (!\is_array($payload) || !isset($payload['tabs']) || !\is_array($payload['tabs'])) {
			\wp_die(__('Invalid settings file.', Plugin::text_domain()));
		}

		if (($payload['plugin'] ?? null) !== Plugin::slug()) {
			\wp_die(__('This settings file does not belong to this plugin.', Plugin::text_domain()));
		}

		$tabs = Tabs::all();

		foreach ($payload['tabs'] as $tab_id => $tab_data) {

			// Find matching tab in current install
			$matched_tab = null;

			foreach ($tabs as $tab) {
				if ($tab->id() === $tab_id) {
					$matched_tab = $tab;
					break;
				}
			}

			if (!$matched_tab) {
				continue; // tab not present in this install
			}

			if (!$matched_tab instanceof SettingsContract) {
				continue;
			}

			if (!\current_user_can($matched_tab->capability())) {
				continue;
			}

			if (!isset($tab_data['data']) || !\is_array($tab_data['data'])) {
				continue;
			}

			$scope = ScopeResolver::resolve($matched_tab);

			SettingsRepository::update(
				$matched_tab->optionKey(),
				$tab_data['data'],
				$scope
			);
		}

		\wp_safe_redirect(
			\add_query_arg(
				Plugin::prefix() . 'notice',
				'imported',
				\admin_url('admin.php?page=' . Plugin::slug() . '&tab=tools')
			)
		);

		exit;
	}
}
