<?php

namespace PluginBoilerplate\Admin\Fields;

use PluginBoilerplate\Admin\Target;

abstract class Field
{
    public string $id;
    public string $label;
    public string $tab;
    public string $section;
    protected array $args = [];
    protected string $option_name;
    protected array $targets = [];

    public function __construct(
        string $id,
        string $label,
        string $tab,
        string $section,
        array  $args = []
    )
    {
        $this->id = $id;
        $this->label = $label;
        $this->tab = $tab;
        $this->section = $section;
        $this->args = $args;
    }

    public function set_option_prefix(string $prefix): void
    {
        $this->option_name = $prefix . $this->id;
    }

    public function get_value()
    {
        // 🔹 Runtime override (used for user profile rendering)
        if (array_key_exists('_override_value', $this->args)) {
            return $this->args['_override_value'];
        }

        $option = get_option($this->get_option_name(), null);

        // If option exists, always use it
        if ($option !== null) {
            return $option;
        }

        // Option missing → use default if provided
        return $this->has_default()
            ? $this->get_default()
            : '';
    }

    public function get_option_name(): string
    {
        return $this->option_name;
    }

    protected function has_default(): bool
    {
        return array_key_exists('default', $this->args);
    }

    protected function get_default()
    {
        return $this->args['default'] ?? null;
    }

    public function get_value_for_user(\WP_User $user)
    {
        return get_user_meta(
            $user->ID,
            $this->get_option_name(),
            true
        );
    }

    abstract public function sanitize($value);

    public function uses_settings_api(): bool
    {
        return true;
    }

    public function render_outside_table(): bool
    {
        return false;
    }

    public function attach_to(Target $target): self
    {
        if (!empty($this->targets)) {
            throw new \LogicException(
                sprintf(
                    'Field "%s" already has a target. A field can only have one target.',
                    $this->id
                )
            );
        }

        $this->targets[] = $target;
        return $this;
    }

    public function get_targets(): array
    {
        return $this->targets;
    }

    public function render_for_user(\WP_User $user): void
    {
        $this->args['_override_value'] = get_user_meta(
            $user->ID,
            $this->get_option_name(),
            true
        );

        $this->render();

        unset($this->args['_override_value']);
    }

    abstract public function render(): void;

    public function has_user_profile_target(): bool
    {
        foreach ($this->targets as $target) {
            if ($target->is_user_profile()) {
                return true;
            }
        }

        return false;
    }

    public function is_user_profile_field(): bool
    {
        foreach ($this->targets as $target) {
            if ($target->is_user_profile()) {
                return true;
            }
        }

        return false;
    }

    protected function render_description(): void
    {
        if (!empty($this->args['description'])) {
            printf(
                '<p class="description">%s</p>',
                esc_html($this->args['description'])
            );
        }
    }

    protected function wp_datetime(): \DateTimeZone
    {
        return wp_timezone();
    }

    protected function render_with_value($value): void
    {
        // Default behavior: temporarily override value
        $this->args['_override_value'] = $value;
        $this->render();
        unset($this->args['_override_value']);
    }

}
