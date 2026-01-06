<?php

namespace PluginBoilerplate\Admin\Fields;

class Select extends Field
{
    public function render(): void
    {
        $value = $this->get_value();
        $choices = $this->args['choices'] ?? [];

        if (empty($choices)) {
            echo '<em>No choices defined.</em>';
            return;
        }
        ?>
        <select name="<?php echo esc_attr($this->get_option_name()); ?>">
            <?php foreach ($choices as $key => $label): ?>
                <option value="<?php echo esc_attr($key); ?>"
                        <?php selected($value, (string)$key); ?>>
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php
        $this->render_description();
    }

    public function sanitize($value)
    {
        $choices = $this->args['choices'] ?? [];

        if (!array_key_exists($value, $choices)) {
            return $this->default();
        }

        return $value;
    }
}
