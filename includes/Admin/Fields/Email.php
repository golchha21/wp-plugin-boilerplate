<?php

namespace PluginBoilerplate\Admin\Fields;

class Email extends Field
{
    public function render(): void
    {
        $value = $this->get_value();
        ?>
        <input
            type="email"
            name="<?php echo esc_attr($this->get_option_name()); ?>"
            value="<?php echo esc_attr((string) $value); ?>"
            class="regular-text"
        >
        <?php
    }

    public function sanitize($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = (string) $value;
        return is_email($value) ? $value : null;
    }

}
