<?php

namespace PluginBoilerplate\Admin\Fields;

abstract class Field
{
    public string $id;
    public string $label;
    public string $tab;
    public string $section;
    protected array $args = [];
    protected string $option_name;
    public function __construct(
        string $id,
        string $label,
        string $tab,
        string $section,
        array $args = []
    ) {
        $this->id      = $id;
        $this->label   = $label;
        $this->tab     = $tab;
        $this->section = $section;
        $this->args    = $args;
    }
    public function set_option_prefix(string $prefix): void
    {
        $this->option_name = $prefix . $this->id;
    }
    public function get_option_name(): string
    {
        return $this->option_name;
    }
    public function get_value()
    {
        $option = get_option($this->get_option_name(), null);

        // If option exists, always use it
        if ($option !== null) {
            return $option;
        }

        // Option missing → use default if provided
        return $this->has_default()
            ? $this->get_default()
            : '';
    }
    abstract public function render(): void;
    abstract public function sanitize($value);
    protected function render_description(): void
    {
        if (! empty($this->args['description'])) {
            printf(
                '<p class="description">%s</p>',
                esc_html($this->args['description'])
            );
        }
    }
    protected function wp_datetime(): \DateTimeZone
    {
        return wp_timezone();
    }
    protected function has_default(): bool
    {
        return array_key_exists('default', $this->args);
    }
    protected function get_default()
    {
        return $this->args['default'] ?? null;
    }
    public function uses_settings_api(): bool
    {
        return true;
    }
    public function render_outside_table(): bool
    {
        return false;
    }

}
