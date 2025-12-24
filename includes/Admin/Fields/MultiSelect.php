<?php

namespace PluginBoilerplate\Admin\Fields;

class MultiSelect extends Field
{
    public function render(): void
    {
        $selected = $this->get_value();

        if (!is_array($selected)) {
            $selected = [];
        }

        $choices = $this->args['choices'] ?? [];

        printf(
            '<select name="%s[]" multiple="multiple" class="plugin-boilerplate-multiselect" style="width: 100%%;">',
            esc_attr($this->get_option_name())
        );

        foreach ($choices as $value => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($value),
                selected(in_array((string) $value, $selected, true), true, false),
                esc_html($label)
            );
        }

        echo '</select>';

        $this->render_description();
    }

    public function sanitize($value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $allowed = array_keys($this->args['choices'] ?? []);

        return array_values(
            array_intersect(
                array_map('strval', $value),
                array_map('strval', $allowed)
            )
        );
    }
}
