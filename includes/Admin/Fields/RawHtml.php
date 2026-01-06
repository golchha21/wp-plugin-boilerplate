<?php

namespace PluginBoilerplate\Admin\Fields;

class RawHtml extends Field
{
    protected $callback;
    protected bool $single_column = false;

    public function __construct(
        string   $id,
        string   $label,
        string   $tab,
        string   $section,
        callable $callback,
        array    $args = []
    )
    {
        parent::__construct($id, $label, $tab, $section, []);

        $this->callback = $callback;
        $this->single_column = (bool)($args['single_column'] ?? false);
    }

    public function render(): void
    {
        if ($this->single_column) {
            echo '<div class="plugin-boilerplate-single-column">';
        }

        call_user_func($this->callback);

        if ($this->single_column) {
            echo '</div>';
        }
    }

    public function sanitize($value)
    {
        // RawHtml does not save anything
        return null;
    }

    public function uses_settings_api(): bool
    {
        return false;
    }

    public function render_outside_table(): bool
    {
        return $this->single_column;
    }
}
