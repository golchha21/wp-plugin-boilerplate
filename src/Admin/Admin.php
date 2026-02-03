<?php

namespace WPPluginBoilerplate\Admin;

use WPPluginBoilerplate\Admin\Actions\ResetSettings;
use WPPluginBoilerplate\Loader;
use WPPluginBoilerplate\Support\Settings;
use WPPluginBoilerplate\Support\Settings\Tabs;

class Admin
{
    public function register(Loader $loader): void
    {
        $loader->action('admin_menu', $this, 'register_menu');

        $loader->action(
            'admin_post_wp_plugin_boilerplate_reset',
            new ResetSettings(),
            'handle'
        );

        new Settings()->register($loader);
    }

    public function register_menu(): void
    {
        add_menu_page(
            'WP Plugin Boilerplate',
            'WP Boilerplate',
            'manage_options',
            'wp-plugin-boilerplate',
            [$this, 'render_page'],
            'dashicons-admin-generic'
        );
    }

    public function render_page(): void
    {
        $tabs   = Tabs::all();
        $active = Tabs::active();

        echo '<div class="wrap">';
        echo '<h1>WP Plugin Boilerplate</h1>';
        echo '<nav class="nav-tab-wrapper">';

        foreach ($tabs as $tab) {
            $activeClass = $tab->id() === $active->id() ? 'nav-tab-active' : '';
            $url = admin_url('admin.php?page=wp-plugin-boilerplate&tab=' . $tab->id());
            echo "<a class='nav-tab {$activeClass}' href='{$url}'>{$tab->label()}</a>";
        }

        echo '</nav>';

        if ($active->hasForm()) {
            echo '<form method="post" action="options.php">';
            $active->render();

            if ($active->hasActions()) {
                if ($active->hasActions()) {
                    submit_button();

                    echo '<hr />';

                    $resetUrl = wp_nonce_url(
                        admin_url(
                            'admin-post.php?action=wp_plugin_boilerplate_reset&tab=' . $active->id()
                        ),
                        'wp_plugin_boilerplate_reset'
                    );

                    echo "<a href='{$resetUrl}' class='button button-secondary'>";
                    echo 'Reset to Defaults';
                    echo '</a>';
                }
            }

            echo '</form>';
        } else {
            $active->render();
        }

        echo '</div>';
    }
}
