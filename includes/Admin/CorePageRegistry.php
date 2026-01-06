<?php

namespace PluginBoilerplate\Admin;

use PluginBoilerplate\Admin\Fields\Field;
use WP_User;
use function add_action;
use function add_settings_field;
use function current_user_can;
use function register_setting;
use function update_user_meta;

final class CorePageRegistry
{
    /**
     * Bootstrap registry (safe to call early).
     */
    public static function register(array $fields): void
    {
        add_action('admin_init', function () use ($fields) {
            self::register_admin($fields);
        });

        add_action('show_user_profile', function (WP_User $user) use ($fields) {
            self::render_user_fields($fields, $user);
        });

        add_action('edit_user_profile', function (WP_User $user) use ($fields) {
            self::render_user_fields($fields, $user);
        });

        add_action('personal_options_update', function (int $user_id) use ($fields) {
            self::save_user_fields($fields, $user_id);
        });

        add_action('edit_user_profile_update', function (int $user_id) use ($fields) {
            self::save_user_fields($fields, $user_id);
        });
    }

    /**
     * Admin settings pages
     */
    protected static function register_admin(array $fields): void
    {
        foreach ($fields as $field) {
            if (!$field instanceof Field) {
                continue;
            }

            foreach ($field->get_targets() as $target) {
                if (!$target->is_options_page()) {
                    continue;
                }

                $page = $target->get_page();
                $section = $target->get_section() ?? 'default';

                // 🔒 Supported core pages ONLY
                if (!in_array($page, [
                        'general',
                        'writing',
                        'reading',
                        'discussion',
                        'media',
                ], true)) {
                    continue;
                }

                add_settings_field(
                        $field->id,
                        $field->label,
                        [$field, 'render'],
                        $page,
                        $section
                );

                register_setting(
                        $page,
                        $field->get_option_name(),
                        [$field, 'sanitize']
                );
            }
        }
    }

    /**
     * Render profile fields
     */
    protected static function render_user_fields(array $fields, WP_User $user): void
    {
        foreach ($fields as $field) {
            if (!$field instanceof Field) {
                continue;
            }

            if (!$field->has_user_profile_target()) {
                continue;
            }

            ?>
            <table class="form-table">
                <tr>
                    <th><?php echo esc_html($field->label); ?></th>
                    <td><?php $field->render_for_user($user); ?></td>
                </tr>
            </table>
            <?php
        }
    }

    /**
     * Save profile fields
     */
    protected static function save_user_fields(array $fields, int $user_id): void
    {
        if (!current_user_can('edit_user', $user_id)) {
            return;
        }

        foreach ($fields as $field) {
            if (!$field->has_user_profile_target()) {
                continue;
            }

            $key = $field->get_option_name();

            if (!isset($_POST[$key])) {
                continue;
            }

            update_user_meta(
                    $user_id,
                    $key,
                    $field->sanitize(wp_unslash($_POST[$key]))
            );
        }
    }

}
