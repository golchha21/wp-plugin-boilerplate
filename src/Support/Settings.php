<?php

namespace WPPluginBoilerplate\Support;

use WPPluginBoilerplate\Loader;

class Settings
{
    protected string $optionGroup = 'wp_plugin_boilerplate';
    protected string $optionName  = 'wp_plugin_boilerplate_options';

    public function register(Loader $loader): void
    {
        $loader->action('admin_init', $this, 'register_settings');
    }

    public function register_settings(): void
    {
        register_setting(
            $this->optionGroup,
            $this->optionName
        );

        add_settings_section(
            'main_section',
            'Plugin Settings',
            '__return_false',
            $this->optionGroup
        );

        add_settings_field(
            'example_text',
            'Example Text',
            [$this, 'render_text_field'],
            $this->optionGroup,
            'main_section'
        );
    }

    public function render_text_field(): void
    {
        $options = get_option($this->optionName, []);
        $value = esc_attr($options['example_text'] ?? '');

        echo "<input type='text' name='{$this->optionName}[example_text]' value='{$value}' />";
    }
}
