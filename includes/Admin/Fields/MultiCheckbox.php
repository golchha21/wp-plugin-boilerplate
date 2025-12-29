<?php

namespace PluginBoilerplate\Admin\Fields;

class MultiCheckbox extends Field
{
    public function render(): void
    {
        $value   = $this->get_value();
        $choices = $this->args['choices'] ?? [];

        if (! is_array($value)) {
            $value = [];
        }

        foreach ($choices as $key => $label) {
            printf(
                '<label style="display:block;">
                    <input type="checkbox"
                           name="%s[]"
                           value="%s"
                           %s>
                    %s
                </label>',
                esc_attr($this->get_option_name()),
                esc_attr($key),
                checked(in_array($key, $value, true), true, false),
                esc_html($label)
            );
        }

        $this->render_description();
    }

    public function sanitize($value): array
    {
        $choices = $this->args['choices'] ?? [];

        if (! is_array($value)) {
            return [];
        }

        return array_values(
            array_intersect($value, array_keys($choices))
        );
    }
}
