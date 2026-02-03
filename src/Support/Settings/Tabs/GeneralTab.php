<?php

namespace WPPluginBoilerplate\Support\Settings\Tabs;

use WPPluginBoilerplate\Support\Settings\Contracts\TabContract;
use WPPluginBoilerplate\Support\Settings\Contracts\SchemaContract;
use WPPluginBoilerplate\Support\Settings\Schemas\GeneralSchema;

class GeneralTab implements TabContract, SchemaContract
{
    public function id(): string
    {
        return 'general';
    }

    public function label(): string
    {
        return 'General';
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
        return 'wp_plugin_boilerplate_general';
    }

    public static function definition(): array
    {
        return GeneralSchema::definition();
    }

    public static function defaults(): array
    {
        return GeneralSchema::defaults();
    }

    public function render(): void
    {
        settings_fields(self::optionKey());
        do_settings_sections(self::optionKey());
    }
}
