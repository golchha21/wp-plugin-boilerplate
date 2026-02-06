<?php

namespace WPPluginBoilerplate\Settings\Tabs;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Contracts\TabContract;

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
		return 'read';
	}

	public function render(): void
	{
		echo '<h2>About This Boilerplate</h2>';

		echo '<p>';
		echo esc_html__(
			'WP Plugin Boilerplate is an opinionated, OOP-first foundation for building long-lived, maintainable WordPress plugins.',
			'wp-plugin-boilerplate'
		);
		echo '</p>';

		echo '<p>';
		echo esc_html__(
			'It is not a demo plugin or a collection of snippets. It encodes architectural rules and constraints that are meant to scale across versions, teams, and installations.',
			'wp-plugin-boilerplate'
		);
		echo '</p>';

		echo '<hr />';

		echo '<h3>Core Principles</h3>';
		echo '<ul style="list-style: disc; margin-left: 20px;">';
		echo '<li>Single entry point with explicit orchestration</li>';
		echo '<li>Centralized hook registration via a Loader</li>';
		echo '<li>Strict PSR-4 namespaces and file structure</li>';
		echo '<li>Extend by adding classes, not editing core</li>';
		echo '<li>No WordPress globals in business logic</li>';
		echo '</ul>';

		echo '<hr />';

		echo '<h3>Settings Architecture</h3>';
		echo '<ul style="list-style: disc; margin-left: 20px;">';
		echo '<li>Tabs are first-class units of composition</li>';
		echo '<li>Each settings tab owns its option key and schema</li>';
		echo '<li>Presentation-only tabs never persist data</li>';
		echo '<li>Defaults and validation are schema-driven</li>';
		echo '<li>Multisite scope is explicit, never implicit</li>';
		echo '</ul>';

		echo '<hr />';

		echo '<h3>Who This Is For</h3>';
		echo '<p>';
		echo esc_html__(
			'Use this boilerplate when long-term maintainability, clarity, and predictable behavior matter. If you are building a quick one-off plugin, this may be more structure than you need.',
			'wp-plugin-boilerplate'
		);
		echo '</p>';

		echo '<p style="font-style: italic; color: #555;">';
		echo esc_html__(
			'The architecture is intentionally strict. The constraints are part of the value.',
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
		echo esc_html__('Buy me a coffee', Plugin::text_domain());
		echo '</a>';
		echo '</p>';
	}
}
