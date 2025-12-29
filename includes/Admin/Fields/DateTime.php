<?php

namespace PluginBoilerplate\Admin\Fields;

class DateTime extends Field
{
    public function render(): void
    {
        $timestamp = $this->get_value();
        $value = '';

        if (is_numeric($timestamp)) {
            $dt = new \DateTime('@' . $timestamp);
            $dt->setTimezone(wp_timezone());
            $value = $dt->format('Y-m-d\TH:i');
        }
        ?>
        <input
            type="datetime-local"
            name="<?php echo esc_attr($this->get_option_name()); ?>"
            value="<?php echo esc_attr($value); ?>"
        >
        <?php

        $this->render_description();
    }

    public function sanitize($value)
    {
        if (empty($value)) {
            return '';
        }

        /**
         * Expected format: YYYY-MM-DDTHH:MM
         * Example: 2025-01-04T09:30
         */
        try {
            $dt = new \DateTime($value, wp_timezone());
            return $dt->getTimestamp();
        } catch (\Exception $e) {
            return '';
        }
    }
}
