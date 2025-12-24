<?php

namespace PluginBoilerplate\Admin\Fields;

class MultiCheckbox extends Field
{
    public function render(): void
    {
        $selected = $this->get_value();

        if (!is_array($selected)) {
            $selected = [];
        }

        $choices = $this->args['choices'] ?? [];

        echo '<fieldset>';

        foreach ($choices as $value => $label) {
            printf(
                    '<label style="display:block; margin-bottom:6px;">
                    <input type="checkbox"
                           name="%s[]"
                           value="%s"
                           %s>
                    %s
                </label>',
                    esc_attr($this->get_option_name()),
                    esc_attr($value),
                    checked(in_array((string) $value, $selected, true), true, false),
                    esc_html($label)
            );
        }

        $this->render_description();

        echo '</fieldset>';
    }

    public function sanitize($value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $allowed = array_keys($this->args['choices'] ?? []);

        return array_values(
                array_intersect(array_map('strval', $value), array_map('strval', $allowed))
        );
    }
}
