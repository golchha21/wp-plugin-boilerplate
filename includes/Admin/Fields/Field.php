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
        return get_option($this->option_name);
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

}
