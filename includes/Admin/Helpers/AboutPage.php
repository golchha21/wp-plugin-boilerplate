<?php

namespace PluginBoilerplate\Admin\Helpers;

final class AboutPage
{
    public static function render(string $version): void
    {
        ?>
        <h1>Plugin Boilerplate – WordPress Settings Framework</h1>
        <p>A lightweight, OOP-based WordPress admin settings framework built for real plugins.</p>

        <p>Designed to be copied, renamed, and adapted, this framework removes the repetitive and error-prone parts of
            building WordPress settings while staying close to core APIs.</p>

        <h2>What This Solves</h2>
        <ul>
            <li>No more serialized option arrays</li>
            <li>No data loss when switching tabs</li>
            <li>Clean separation between settings and tools</li>
            <li>WordPress-aware date, time, and media handling</li>
            <li>Safe lifecycle cleanup on uninstall</li>
            <li>Ability to place fields on <b>core WordPress pages</b> and <b>user profiles</b></li>
        </ul>

        <h2>Key Features</h2>

        <ul>
            <li>One option per field (prefix-based storage)</li>
            <li>Tab-based settings UI</li>
            <li>Default value support (applied only when an option does not exist)</li>
            <li>Default value support (applied only when no value exists)</li>
            <li>WordPress-native Date, Time, and DateTime fields</li>
            <li>Media fields with previews, ordering, and MIME control</li>
            <li>Versioned JSON export / import</li>
            <li>Nonce-protected Tools tab</li>
            <li>Safe activate / deactivate / uninstall handling</li>
            <li>Optional field placement on:
                <ul>
                    <li>Core WordPress settings pages</li>
                    <li>User profile screens</li>
                </ul>
            </li>
        </ul>

        <hr>

        <p>
            <strong>Version:</strong> <?php echo esc_html(MY_PLUGIN_VERSION ?? 'dev'); ?><br>
            <strong>License:</strong> GPL v3 or later
        </p>

        <?php
    }
}
