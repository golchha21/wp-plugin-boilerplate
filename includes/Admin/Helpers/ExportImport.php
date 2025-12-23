<?php

namespace PluginBoilerplate\Admin\Helpers;

class ExportImport
{
    private const OPTION_PREFIX = 'yps-';

    public static function render_export(): void
    {
        ?>
        <textarea readonly class="large-text code" rows="10"><?php
            echo esc_textarea(self::export());
            ?></textarea>
        <p class="description">
            Copy this JSON to back up plugin boilerplate settings.
        </p>
        <?php
    }

    public static function render_import(): void
    {
        ?>
        <form method="post">
            <?php wp_nonce_field('plugin_boilerplate_import', 'plugin_boilerplate_import_nonce'); ?>

            <textarea name="plugin_boilerplate_import_json"
                      class="large-text code"
                      rows="10"
                      required></textarea>

            <?php submit_button('Import Settings', 'primary', 'submit', false); ?>
        </form>
        <?php
    }

    private static function export(): string
    {
        global $wpdb;

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT option_name, option_value
                 FROM {$wpdb->options}
                 WHERE option_name LIKE %s",
                self::OPTION_PREFIX . '%'
            ),
            ARRAY_A
        );

        $data = [];
        foreach ($rows as $row) {
            $data[$row['option_name']] = maybe_unserialize($row['option_value']);
        }

        return wp_json_encode($data, JSON_PRETTY_PRINT);
    }
}
