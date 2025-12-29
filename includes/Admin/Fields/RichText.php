<?php

namespace PluginBoilerplate\Admin\Fields;

class RichText extends Field
{
    public function render(): void
    {
        $value = $this->get_value();

        wp_editor(
            $value,
            esc_attr($this->id),
            [
                'textarea_name' => $this->get_option_name(),
                'textarea_rows' => $this->args['rows'] ?? 8,
                'media_buttons' => $this->args['media_buttons'] ?? false,
                'teeny'         => $this->args['teeny'] ?? true,
            ]
        );

        $this->render_description();
    }

    public function sanitize($value)
    {
        return wp_kses_post($value);
    }
}
