<?php

namespace WPPluginBoilerplate\Support\Settings\Tabs;

use WPPluginBoilerplate\Support\Settings\Contracts\TabContract;

class AboutTab implements TabContract
{
    public function id(): string
    {
        return 'about';
    }

    public function label(): string
    {
        return 'About';
    }

    public function hasForm(): bool
    {
        return false;
    }

    public function hasActions(): bool
    {
        return false;
    }

    public function render(): void
    {
        echo '<h2>About</h2>';
        echo '<p>This plugin is built on an opinionated boilerplate.</p>';
    }
}
