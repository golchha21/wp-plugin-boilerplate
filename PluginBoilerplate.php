<?php
/**
 * Plugin Name: WP Plugin Boilerplate
 * Plugin URI: http://www.ulhas.net/labs/wp-plugin-boilerplate/
 * Description: A reusable, OOP-based WordPress admin settings framework designed for real plugins.
 * Version: 1.1.1
 * Author: Ulhas Vardhan Golchha
 * Author URI: https://www.ulhas.net/
 * Text Domain: plugin-boilerplate
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace {

    if (!defined('ABSPATH')) {
        exit;
    }

    define('MY_PLUGIN_VERSION', '1.1.1');

    // Constants in the root namespace
    const PLUGIN_FILE = __FILE__;
    const OPTION_PREFIX = 'yps-';
    const IS_OPTIONS_PAGE = false;

    // Autoloader
    require_once __DIR__ . '/includes/Autoloader.php';
    PluginBoilerplate\Autoloader::register();

    // Load Lifecycle BEFORE registering hooks
    require_once __DIR__ . '/includes/Lifecycle.php';

    // Hooks MUST be registered ONLY after Lifecycle class is loaded
    register_activation_hook(PLUGIN_FILE, ['PluginBoilerplate\\Lifecycle', 'activate']);
    register_deactivation_hook(PLUGIN_FILE, ['PluginBoilerplate\\Lifecycle', 'deactivate']);


    // Bootstrap plugin
    require_once __DIR__ . '/includes/Bootstrap.php';
    (new PluginBoilerplate\Bootstrap())->register();
}
