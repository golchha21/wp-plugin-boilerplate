<?php

namespace PluginBoilerplate\Admin\Fields;

class Time extends Field
{
    public function render(): void
    {
        $value = $this->get_value();

        printf(
            '<input type="time"
                   name="%s"
                   value="%s">',
            esc_attr($this->get_option_name()),
            esc_attr((string)$value)
        );

        $this->render_description();
    }

    public function sanitize($value)
    {
        return preg_match('/^\d{2}:\d{2}$/', $value)
            ? $value
            : '';
    }
}
