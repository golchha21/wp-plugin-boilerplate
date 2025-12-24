<?php

namespace PluginBoilerplate\Admin\Fields;

class Text extends Field
{
    public function render(): void
    {
        $value = $this->get_value();
        ?>
        <input
            type="text"
            name="<?php echo esc_attr($this->get_option_name()); ?>"
            value="<?php echo esc_attr((string) $value); ?>"
            class="regular-text"
        >
        <?php
        $this->render_description();
    }

    public function sanitize($value): ?string
    {
        if ($value === null) {
            return null;
        }
        return sanitize_text_field((string) $value);
    }

}
