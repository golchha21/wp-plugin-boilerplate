<?php

namespace PluginBoilerplate\Admin\Fields;

class Select extends Field
{
    public function render(): void
    {
        $value   = $this->get_value();
        $options = $this->args['options'] ?? [];

        if (empty($options)) {
            return;
        }
        ?>
        <select name="<?php echo esc_attr($this->get_option_name()); ?>">
            <?php foreach ($options as $key => $label): ?>
                <option value="<?php echo esc_attr($key); ?>"
                        <?php selected((string) $value, (string) $key); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    public function sanitize($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $options = $this->args['options'] ?? [];
        return array_key_exists((string) $value, $options)
                ? (string) $value
                : null;
    }

}
