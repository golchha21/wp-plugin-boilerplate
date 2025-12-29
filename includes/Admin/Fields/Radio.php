<?php

namespace PluginBoilerplate\Admin\Fields;

class Radio extends Field
{
    public function render(): void
    {
        $value   = $this->get_value();
        $choices = $this->args['choices'] ?? [];

        foreach ($choices as $key => $label) {
            printf(
                    '<label style="display:block;">
                    <input type="radio"
                           name="%s"
                           value="%s"
                           %s>
                    %s
                </label>',
                    esc_attr($this->get_option_name()),
                    esc_attr($key),
                    checked((string) $value, (string) $key, false),
                    esc_html($label)
            );
        }

        $this->render_description();
    }

    public function sanitize($value)
    {
        $choices = $this->args['choices'] ?? [];
        return array_key_exists($value, $choices) ? $value : '';
    }
}
