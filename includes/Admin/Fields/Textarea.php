<?php

namespace PluginBoilerplate\Admin\Fields;

class Textarea extends Field
{
    public function render(): void
    {
        $value = $this->get_value();
        $rows = $this->args['rows'] ?? 5;
        ?>
        <textarea
                name="<?php echo esc_attr($this->get_option_name()); ?>"
                rows="<?php echo esc_attr($rows); ?>"
                class="large-text"
        ><?php echo esc_textarea((string)$value); ?></textarea>
        <?php
        $this->render_description();
    }

    public function sanitize($value): ?string
    {
        if ($value === null) {
            return null;
        }
        return sanitize_textarea_field((string)$value);
    }

}
