<?php

namespace WPPluginBoilerplate\Support\Settings\Tabs;

use WPPluginBoilerplate\Support\Settings\Contracts\TabContract;

class AboutTab implements TabContract
{
    public function id(): string
    {
        return 'about';
    }

    public function label(): string
    {
        return 'About';
    }

    public function hasForm(): bool
    {
        return false;
    }

    public function hasActions(): bool
    {
        return false;
    }

    public function viewCapability(): string
    {
        return 'read';
    }

    public function manageCapability(): string
    {
        return 'do_not_allow';
    }

    public function render(): void
    {
        echo '<h2>About This Boilerplate</h2>';

        echo '<p>';
        echo esc_html__(
            'This is an opinionated, OOP-first WordPress plugin boilerplate designed for long-lived, maintainable plugins.',
            'wp-plugin-boilerplate'
        );
        echo '</p>';

        echo '<p>';
        echo esc_html__(
            'It focuses on architectural discipline, explicit contracts, and predictable extension points rather than quick setup or demo features.',
            'wp-plugin-boilerplate'
        );
        echo '</p>';

        echo '<hr />';

        echo '<h3>What This Boilerplate Provides</h3>';
        echo '<ul style="list-style: disc; margin-left: 20px;">';
        echo '<li>Single entry point with clear orchestration</li>';
        echo '<li>Centralized hook registration via a loader</li>';
        echo '<li>Tab-based, schema-driven settings system</li>';
        echo '<li>Per-tab capability enforcement</li>';
        echo '<li>Multisite-aware settings storage</li>';
        echo '<li>Safe, versioned settings migrations</li>';
        echo '<li>Per-tab import and export tooling</li>';
        echo '</ul>';

        echo '<hr />';

        echo '<h3>Recent Architectural Updates</h3>';
        echo '<ul style="list-style: disc; margin-left: 20px;">';
        echo '<li>Introduced multisite-aware settings scopes (site vs network)</li>';
        echo '<li>Added lazy, schema-driven settings migrations</li>';
        echo '<li>Hardened per-tab capability checks</li>';
        echo '<li>Added Tools tab for per-tab import and export</li>';
        echo '<li>Refined Tools UI to match WordPress admin conventions</li>';
        echo '</ul>';

        echo '<hr />';

        echo '<p style="font-style: italic; color: #555;">';
        echo esc_html__(
            'If you are building a plugin that needs to scale over time, this boilerplate gives you a solid, explicit foundation. If you need a quick one-off plugin, this may be more structure than necessary.',
            'wp-plugin-boilerplate'
        );
        echo '</p>';

        echo '<hr />';

        echo '<p style="margin-top: 12px;">';
        echo esc_html__(
            'If this boilerplate has been useful in your work, you can support its continued development here:',
            'wp-plugin-boilerplate'
        );
        echo '</p>';

        echo '<p>';
        echo '<a href="https://www.buymeacoffee.com/golchha21" target="_blank" rel="noopener noreferrer" class="button button-secondary">';
        echo esc_html__('Buy me a coffee', 'wp-plugin-boilerplate');
        echo '</a>';
        echo '</p>';

    }
}
