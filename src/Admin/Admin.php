<?php

namespace WPPluginBoilerplate\Admin;

use WPPluginBoilerplate\Loader;
use WPPluginBoilerplate\Support\Settings;
use WPPluginBoilerplate\Support\Settings\Tabs;

class Admin
{
    public function register(Loader $loader): void
    {
        $loader->action('admin_menu', $this, 'register_menu');

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
                submit_button();
            }

            echo '</form>';
        } else {
            $active->render();
        }

        echo '</div>';
    }
}
