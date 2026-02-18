<?php
/**
 * Plugin Name: WP Plugin Boilerplate
 * Description: An opinionated, OOP-first WordPress plugin boilerplate for building long-lived, maintainable plugins.
 * Plugin URI: https://www.ulhas.net/labs/wp-plugin-boilerplate/
 * Version: 1.2.0
 * Author: Ulhas Vardhan Golchha
 * Author URI: https://www.ulhas.net/
 * Text Domain: wp-plugin-boilerplate
 */

defined('ABSPATH') || exit;

if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) return; // Bundled dependencies are required

/**
 * -------------------------------------------------------------------------
 * Identity / Prefix
 * -------------------------------------------------------------------------
 *
 * Change this in forks or generated plugins.
 * Must remain constant for the lifetime of the plugin.
 */
if (!defined('WPPB_PREFIX')) {
	define('WPPB_PREFIX', 'wppb_');
}

if (!defined('WPPB_VERSION')) {
	define('WPPB_VERSION', '1.2.0');
}

if (!defined('WPPB_SLUG')) {
	define('WPPB_SLUG', 'wp-plugin-boilerplate');
}

if (!defined('WPPB_TEXT_DOMAIN')) {
	define('WPPB_TEXT_DOMAIN', 'wp-plugin-boilerplate');
}

if (!defined('WPPB_FILE')) {
	define('WPPB_FILE', __FILE__);
}

if (!defined('WPPB_URL')) {
	define('WPPB_URL', plugin_dir_url(__FILE__));
}

if (!defined('WPPB_MENU_PARENT')) {
	define('WPPB_MENU_PARENT', null); // e.g. 'options-general.php'
}


if (!defined('WPPB_TABS_AS_SUBMENU')) {
	define('WPPB_TABS_AS_SUBMENU', true);
}


/**
 * -------------------------------------------------------------------------
 * Autoload
 * -------------------------------------------------------------------------
 */
require_once __DIR__ . '/vendor/autoload.php';

use WPPluginBoilerplate\Lifecycle\Activator;
use WPPluginBoilerplate\Lifecycle\Deactivator;
use WPPluginBoilerplate\Plugin;

/**
 * -------------------------------------------------------------------------
 * Lifecycle hooks
 * -------------------------------------------------------------------------
 */
register_activation_hook(WPPB_FILE, [Activator::class, 'activate']);

register_deactivation_hook(WPPB_FILE, [Deactivator::class, 'deactivate']);

/**
 * -------------------------------------------------------------------------
 * Bootstrap
 * -------------------------------------------------------------------------
 */
$plugin = new Plugin();
$plugin->run();
