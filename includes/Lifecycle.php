<?php

namespace PluginBoilerplate;

use PluginBoilerplate\Admin\Helpers\Cache;

class Lifecycle
{
    /**
     * Prefix for ALL plugin-owned options.
     * Must match SettingsPage option_prefix.
     */
    const OPTION_PREFIX = 'yps-';

    /**
     * On plugin activation.
     * Runtime setup only.
     */
    public static function activate(): void
    {
        // Schedule cron if needed
        if (! wp_next_scheduled('plugin_boilerplate_cron_event')) {
            wp_schedule_event(time(), 'daily', 'plugin_boilerplate_cron_event');
        }

        // If you add rewrite rules later:
        // flush_rewrite_rules(false);
    }

    /**
     * On plugin deactivation.
     * Runtime cleanup ONLY.
     */
    public static function deactivate(): void
    {
        self::cleanupRuntime();
    }

    /**
     * On plugin uninstall.
     * Full permanent cleanup.
     * Called from uninstall.php.
     */
    public static function uninstall(): void
    {
        self::cleanupRuntime();
        self::cleanupPermanent();
    }

    /**
     * Cleanup shared by deactivate + uninstall.
     * Never delete settings here.
     */
    protected static function cleanupRuntime(): void
    {
        // Remove cron events
        $timestamp = wp_next_scheduled('plugin_boilerplate_cron_event');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'plugin_boilerplate_cron_event');
        }

        // Clear plugin cache (if any)
        if (class_exists(Cache::class)) {
            Cache::clear(self::OPTION_PREFIX);
        }

        // Clear plugin transients
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options}
                 WHERE option_name LIKE %s
                    OR option_name LIKE %s",
                '_transient_plugin_boilerplate_%',
                '_transient_timeout_plugin_boilerplate_%'
            )
        );
    }

    /**
     * Permanent cleanup.
     * Delete ALL plugin-owned options.
     */
    protected static function cleanupPermanent(): void
    {
        global $wpdb;

        // Delete all options created by this plugin
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options}
                 WHERE option_name LIKE %s",
                self::OPTION_PREFIX . '%'
            )
        );

        // Final cache cleanup
        if (class_exists(Cache::class)) {
            Cache::clear(self::OPTION_PREFIX);
        }
    }
}
