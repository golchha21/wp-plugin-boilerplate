<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

namespace {

    use PluginBoilerplate\Autoloader;
    use PluginBoilerplate\Lifecycle;

    require_once __DIR__ . '/includes/Autoloader.php';
    Autoloader::register();

    Lifecycle::uninstall();
}
