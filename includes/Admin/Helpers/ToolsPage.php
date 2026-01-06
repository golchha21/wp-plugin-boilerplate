<?php

namespace PluginBoilerplate\Admin\Helpers;

use PluginBoilerplate\Admin\Services\ToolsService;

final class ToolsPage
{
    public static function render(): void
    {
        $payload = ToolsService::get_export_payload();
        $json = wp_json_encode($payload, JSON_PRETTY_PRINT);
        ?>
        <h2>Export Settings</h2>
        <textarea class="large-text code" rows="8" readonly><?php echo esc_textarea($json); ?></textarea>
        <p class="description">Copy this JSON to back up plugin boilerplate settings.</p>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('plugin_boilerplate_tools_export'); ?>
            <input type="hidden" name="action" value="plugin_boilerplate_export">

            <button type="submit" class="button button-primary">Download Settings (JSON)</button>
        </form>

        <h2>Import Settings</h2>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('plugin_boilerplate_tools_import'); ?>
            <input type="hidden" name="action" value="plugin_boilerplate_import">

            <textarea name="import_payload" rows="8" class="large-text code"
                      placeholder="Paste exported JSON here"></textarea>
            <p class="description">Paste the exported JSON to restore the plugin boilerplate settings.</p>

            <button type="submit" class="button button-secondary">Import Settings</button>
        </form>
        <?php
    }
}
