<?php

namespace PluginBoilerplate\Admin\Fields;

class MultiSelect extends Field
{
    public function render(): void
    {
        $value = $this->get_value();
        $choices = $this->args['choices'] ?? [];

        // Normalize value
        if (!is_array($value)) {
            $value = [];
        }

        printf(
            '<select name="%s[]" multiple class="plugin-boilerplate-multiselect">',
            esc_attr($this->get_option_name())
        );

        foreach ($choices as $key => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($key),
                selected(in_array($key, $value, true), true, false),
                esc_html($label)
            );
        }

        echo '</select>';

        $this->render_description();
    }

    public function sanitize($value): array
    {
        $choices = $this->args['choices'] ?? [];

        if (!is_array($value)) {
            return [];
        }

        // Keep only allowed values
        return array_values(
            array_intersect($value, array_keys($choices))
        );
    }
}