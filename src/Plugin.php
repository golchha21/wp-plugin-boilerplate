<?php

namespace WPPluginBoilerplate;

use WPPluginBoilerplate\Admin\Admin;
use WPPluginBoilerplate\Public\PublicPlugin;
use WPPluginBoilerplate\Support\Assets;
use WPPluginBoilerplate\Support\I18n;

class Plugin
{
    protected Loader $loader;

    public function __construct()
    {
        $this->loader = new Loader();

        $this->register_i18n();
        $this->register_assets();
        $this->register_admin();
        $this->register_public();
    }

    protected function register_i18n(): void
    {
        new I18n()->register($this->loader);
    }

    protected function register_admin(): void
    {
        if (is_admin()) {
            new Admin()->register($this->loader);
        }
    }

    protected function register_public(): void
    {
        new PublicPlugin()->register($this->loader);
    }

    public function run(): void
    {
        $this->loader->run();
    }

    protected function register_assets(): void
    {
        new Assets()->register($this->loader);
    }

}
