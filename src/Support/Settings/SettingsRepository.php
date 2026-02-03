<?php

namespace WPPluginBoilerplate\Support\Settings;

use WPPluginBoilerplate\Support\Settings\Contracts\SchemaContract;

class SettingsRepository
{
    /**
     * Get normalized settings for a schema-backed tab
     */
    public static function get(SchemaContract|string $schema): array
    {
        if (is_string($schema)) {
            $schema = new $schema();
        }

        $saved = get_option($schema::optionKey(), []);
        $defaults = $schema::defaults();

        return wp_parse_args($saved, $defaults);
    }

    /**
     * Reset settings for a tab back to defaults
     */
    public static function reset(SchemaContract|string $schema): void
    {
        if (is_string($schema)) {
            $schema = new $schema();
        }

        update_option(
            $schema::optionKey(),
            $schema::defaults()
        );
    }
}
