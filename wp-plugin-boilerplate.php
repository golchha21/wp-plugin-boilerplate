<?php
/**
 * Plugin Name: WP Plugin Boilerplate
 * Description: An opinionated, OOP-first WordPress plugin boilerplate.
 */

defined('ABSPATH') || exit;

require_once __DIR__ . '/vendor/autoload.php';

use WPPluginBoilerplate\Activator;
use WPPluginBoilerplate\Deactivator;
use WPPluginBoilerplate\Plugin;

register_activation_hook(__FILE__, [Activator::class, 'activate']);
register_deactivation_hook(__FILE__, [Deactivator::class, 'deactivate']);

function run_wp_plugin_boilerplate(): void
{
    new Plugin()->run();
}

run_wp_plugin_boilerplate();
