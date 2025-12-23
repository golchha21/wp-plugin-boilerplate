<?php

namespace PluginBoilerplate\Admin\Fields;

class Number extends Field
{
    public function render(): void
    {
        $value = $this->get_value();
        $min   = $this->args['min']  ?? '';
        $max   = $this->args['max']  ?? '';
        $step  = $this->args['step'] ?? '1';
        ?>
        <input
            type="number"
            name="<?php echo esc_attr($this->get_option_name()); ?>"
            value="<?php echo esc_attr($value !== null ? (string) $value : ''); ?>"
            class="small-text"
            <?php if ($min !== ''): ?>min="<?php echo esc_attr($min); ?>"<?php endif; ?>
            <?php if ($max !== ''): ?>max="<?php echo esc_attr($max); ?>"<?php endif; ?>
            step="<?php echo esc_attr($step); ?>"
        >
        <?php
    }

    public function sanitize($value): ?string
    {
        if ($value === null) {
            return null;
        }
        return is_numeric($value) ? (string) $value : null;
    }

}
