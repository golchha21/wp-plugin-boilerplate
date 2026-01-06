<?php

namespace PluginBoilerplate\Admin\Fields;

class Checkbox extends Field
{
    public function render(): void
    {
        $value = $this->get_value();
        ?>
        <label>
            <!-- Hidden field ensures unchecked state is submitted -->
            <input
                    type="hidden"
                    name="<?php echo esc_attr($this->get_option_name()); ?>"
                    value="0"
            >

            <input
                    type="checkbox"
                    name="<?php echo esc_attr($this->get_option_name()); ?>"
                    value="1"
                    <?php checked($value, '1'); ?>
            >

            <?php

            if (!empty($this->args['label'])) {
                echo esc_html($this->args['label']);
            } else {
                echo esc_html($this->label);
            }
            ?>
        </label>
        <?php
        $this->render_description();
    }

    public function sanitize($value): ?string
    {
        // Value is ALWAYS present because of hidden input
        return $value === '1' ? '1' : '0';
    }
}
