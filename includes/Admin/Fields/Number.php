<?php

namespace PluginBoilerplate\Admin\Fields;

class Number extends Field
{
    public function render(): void
    {
        $value = $this->get_value();

        $min = $this->args['min'] ?? null;
        $max = $this->args['max'] ?? null;
        $step = $this->args['step'] ?? '1';

        printf(
            '<input type="number"
                name="%s"
                value="%s"
                class="small-text"
                %s %s
                step="%s"
            >',
            esc_attr($this->get_option_name()),
            esc_attr((string)$value),
            $min !== null ? 'min="' . esc_attr($min) . '"' : '',
            $max !== null ? 'max="' . esc_attr($max) . '"' : '',
            esc_attr($step)
        );

        // Range hint
        if ($min !== null || $max !== null) {
            echo '<p class="description">';

            if ($min !== null && $max !== null) {
                printf(
                    'Allowed range: %s – %s',
                    esc_html($min),
                    esc_html($max)
                );
            } elseif ($min !== null) {
                printf(
                    'Minimum value: %s',
                    esc_html($min)
                );
            } else {
                printf(
                    'Maximum value: %s',
                    esc_html($max)
                );
            }

            echo '</p>';
        }

        // Optional custom description
        $this->render_description();
    }

    public function sanitize($value)
    {
        if ($value === '' || $value === null) {
            return '';
        }

        if (!is_numeric($value)) {
            return '';
        }

        $number = $value + 0;

        if (isset($this->args['min']) && $number < $this->args['min']) {
            $number = $this->args['min'];
        }

        if (isset($this->args['max']) && $number > $this->args['max']) {
            $number = $this->args['max'];
        }

        return $number;
    }
}
