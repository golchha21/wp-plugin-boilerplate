<?php

namespace WPPluginBoilerplate\Support;

use WPPluginBoilerplate\Loader;

class I18n
{
    public function register(Loader $loader): void
    {
        $loader->action('plugins_loaded', $this, 'load_textdomain');
    }

    public function load_textdomain(): void
    {
        load_plugin_textdomain(
            'wp-plugin-boilerplate',
            false,
            dirname(plugin_basename(__FILE__), 2) . '/languages'
        );
    }
}
