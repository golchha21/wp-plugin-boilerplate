<?php

namespace WPPluginBoilerplate\Support\Settings\Tabs;

use WPPluginBoilerplate\Support\Settings\Contracts\SettingsTabContract;
use WPPluginBoilerplate\Support\Settings\Schemas\AdvancedSchema;

class AdvancedTab implements SettingsTabContract
{
    public function id(): string
    {
        return 'advanced';
    }

    public function label(): string
    {
        return 'Advanced';
    }

    public function hasForm(): bool
    {
        return true;
    }

    public function hasActions(): bool
    {
        return true;
    }

    public static function optionKey(): string
    {
        return 'wp_plugin_boilerplate_advanced';
    }

    public static function definition(): array
    {
        return AdvancedSchema::definition();
    }

    public static function defaults(): array
    {
        return AdvancedSchema::defaults();
    }

    public function render(): void
    {
        settings_fields(self::optionKey());
        do_settings_sections(self::optionKey());
    }
}
