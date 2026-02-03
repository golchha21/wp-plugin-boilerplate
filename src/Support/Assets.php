<?php

namespace WPPluginBoilerplate\Support;

use WPPluginBoilerplate\Loader;

class Assets
{
    public function register(Loader $loader): void
    {
        $loader->action('admin_enqueue_scripts', $this, 'admin_assets');
        $loader->action('wp_enqueue_scripts', $this, 'public_assets');
    }

    public function admin_assets(): void
    {
        wp_enqueue_style(
            'wp-plugin-boilerplate-admin',
            plugin_dir_url(dirname(__DIR__, 2)) . 'assets/admin/admin.css',
            [],
            '1.0.0'
        );
    }

    public function public_assets(): void
    {
        wp_enqueue_script(
            'wp-plugin-boilerplate-public',
            plugin_dir_url(dirname(__DIR__, 2)) . 'assets/public/public.js',
            [],
            '1.0.0',
            true
        );
    }
}
