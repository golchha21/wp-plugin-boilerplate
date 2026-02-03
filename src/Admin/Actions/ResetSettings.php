<?php

namespace WPPluginBoilerplate\Admin\Actions;

use WPPluginBoilerplate\Support\Settings\Contracts\SettingsTabContract;
use WPPluginBoilerplate\Support\Settings\Tabs;
use WPPluginBoilerplate\Support\Settings\SettingsRepository;

class ResetSettings
{
    public function handle(): void
    {
        check_admin_referer('wp_plugin_boilerplate_reset');

        $tab = Tabs::active();

        if (! $tab instanceof SettingsTabContract) {
            wp_die('This tab does not support settings.');
        }

        SettingsRepository::reset($tab);

        wp_safe_redirect(
            admin_url('admin.php?page=wp-plugin-boilerplate&tab=' . $tab->id())
        );
        exit;
    }
}
