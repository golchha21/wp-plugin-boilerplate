<?php

namespace PluginBoilerplate\Admin\Services;

final class ToolsService
{
    public const EXPORT_VERSION = 1;

    public static function register(): void
    {
        add_action('admin_post_plugin_boilerplate_export', [self::class, 'export']);
        add_action('admin_post_plugin_boilerplate_import', [self::class, 'import']);
    }

    /* ---------------------------------
     * Actions
     * --------------------------------- */

    public static function export(): void
    {
        self::verify_request('export');

        $payload = self::build_export_payload();

        nocache_headers();
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=plugin-settings.json');

        echo wp_json_encode($payload, JSON_PRETTY_PRINT);
        exit;
    }

    public static function import(): void
    {
        self::verify_request('import');

        if (empty($_POST['import_payload'])) {
            wp_die('No import data provided.');
        }

        $payload = json_decode(wp_unslash($_POST['import_payload']), true);

        if (! is_array($payload)) {
            wp_die('Invalid JSON payload.');
        }

        $errors = self::validate_import_payload($payload);

        if (! empty($errors)) {
            wp_die(
                '<h2>Import Failed</h2><ul><li>' .
                implode('</li><li>', array_map('esc_html', $errors)) .
                '</li></ul>',
                'Import Error',
                ['response' => 400]
            );
        }

        // Validation passed — safe to import
        self::restore_from_payload($payload);

        wp_safe_redirect(
            add_query_arg('import', 'success', wp_get_referer())
        );
        exit;
    }

    /* ---------------------------------
     * Export / Import internals
     * --------------------------------- */

    protected static function build_export_payload(): array
    {
        return [
            'meta' => [
                'framework'      => 'plugin-boilerplate-settings',
                'export_version' => self::EXPORT_VERSION,
                'plugin_version' => defined('MY_PLUGIN_VERSION') ? MY_PLUGIN_VERSION : null,
                'exported_at'    => current_time('mysql'),
            ],
            'options' => self::collect_options(),
        ];
    }

    public static function get_export_payload(): array
    {
        return self::build_export_payload();
    }

    protected static function collect_options(): array
    {
        global $wpdb;

        if (! defined('OPTION_PREFIX') || OPTION_PREFIX === '') {
            return [];
        }

        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT option_name, option_value
                 FROM {$wpdb->options}
                 WHERE option_name LIKE %s",
                $wpdb->esc_like(OPTION_PREFIX) . '%'
            ),
            ARRAY_A
        );

        $options = [];

        foreach ($rows as $row) {
            $options[$row['option_name']] = maybe_unserialize($row['option_value']);
        }

        return $options;
    }

    protected static function restore_from_payload(array $payload): void
    {
        if (
            empty($payload['meta']['export_version']) ||
            empty($payload['options']) ||
            ! is_array($payload['options'])
        ) {
            wp_die('Invalid export format.');
        }

        // Version gate (future-proof)
        $version = (int) $payload['meta']['export_version'];

        if ($version > self::EXPORT_VERSION) {
            wp_die('Export version is newer than this framework supports.');
        }

        foreach ($payload['options'] as $option_name => $value) {
            if (strpos($option_name, OPTION_PREFIX) !== 0) {
                continue;
            }

            update_option($option_name, $value);
        }
    }

    protected static function validate_import_payload(array $payload): array
    {
        $errors = [];

        // 1. Meta section
        if (empty($payload['meta']) || ! is_array($payload['meta'])) {
            $errors[] = 'Missing or invalid meta section.';
            return $errors;
        }

        // 2. Export version
        if (! isset($payload['meta']['export_version'])) {
            $errors[] = 'Missing export_version.';
        } elseif ((int) $payload['meta']['export_version'] > self::EXPORT_VERSION) {
            $errors[] = 'Export version is newer than this framework supports.';
        }

        // 3. Options section
        if (empty($payload['options']) || ! is_array($payload['options'])) {
            $errors[] = 'Missing or invalid options section.';
            return $errors;
        }

        // 4. Prefix safety
        if (! defined('OPTION_PREFIX') || OPTION_PREFIX === '') {
            $errors[] = 'OPTION_PREFIX is not defined.';
            return $errors;
        }

        foreach ($payload['options'] as $option_name => $value) {
            if (! is_string($option_name)) {
                $errors[] = 'Invalid option name detected.';
                continue;
            }

            if (strpos($option_name, OPTION_PREFIX) !== 0) {
                $errors[] = sprintf(
                    'Option "%s" does not match the required prefix.',
                    esc_html($option_name)
                );
            }

            // Allow only scalar or array values
            if (! is_scalar($value) && ! is_array($value) && ! is_null($value)) {
                $errors[] = sprintf(
                    'Invalid value type for option "%s".',
                    esc_html($option_name)
                );
            }
        }

        return $errors;
    }

    /* ---------------------------------
     * Security
     * --------------------------------- */

    protected static function verify_request(string $action): void
    {
        if (! current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('plugin_boilerplate_tools_' . $action);
    }

}
