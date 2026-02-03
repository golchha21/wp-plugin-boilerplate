<?php

namespace WPPluginBoilerplate\Support\Settings;

use WPPluginBoilerplate\Support\Settings\Contracts\TabContract;

class Tabs
{
    public static function all(): array
    {
        return [
            new Tabs\GeneralTab(),
            new Tabs\AboutTab(),
        ];
    }

    public static function active(): TabContract
    {
        $tabs = self::all();
        $active = $_GET['tab'] ?? $tabs[0]->id();

        foreach ($tabs as $tab) {
            if ($tab->id() === $active) {
                return $tab;
            }
        }

        return $tabs[0];
    }
}
