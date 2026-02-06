<?php

namespace WPPluginBoilerplate\Settings\Tabs;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Contracts\TabContract;

class HelpTab implements TabContract
{
	public function id(): string
	{
		return 'help';
	}

	public function label(): string
	{
		return 'Help';
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
		echo '<h2>Help</h2>';

		echo '<p>';
		echo esc_html__(
			'This boilerplate is designed to be predictable and explicit. Most questions are answered by understanding the architecture rather than memorizing APIs.',
			'wp-plugin-boilerplate'
		);
		echo '</p>';

		echo '<p>';
		echo esc_html__(
			'If you are new to the structure, start with the high-level documentation before diving into individual classes.',
			'wp-plugin-boilerplate'
		);
		echo '</p>';

		echo '<hr />';

		echo '<h3>Getting Started</h3>';
		echo '<ul style="list-style: disc; margin-left: 20px;">';

		echo '<li>';
		echo '<a href="https://github.com/golchha21/wp-plugin-boilerplate/blob/master/README.md" target="_blank" rel="noopener noreferrer">';
		echo esc_html__('README', Plugin::text_domain());
		echo '</a> – ';
		echo esc_html__('architecture rules, principles, and mental model', Plugin::text_domain());
		echo '</li>';

		echo '<li>';
		echo '<a href="https://github.com/golchha21/wp-plugin-boilerplate/blob/master/HOW-TO-USE.md" target="_blank" rel="noopener noreferrer">';
		echo esc_html__('How to Use', Plugin::text_domain());
		echo '</a> – ';
		echo esc_html__('step-by-step guide to building a real plugin', Plugin::text_domain());
		echo '</li>';

		echo '<li>';
		echo '<a href="https://github.com/golchha21/wp-plugin-boilerplate/blob/master/FIELDS.md" target="_blank" rel="noopener noreferrer">';
		echo esc_html__('Fields Reference', Plugin::text_domain());
		echo '</a> – ';
		echo esc_html__('all supported field types and guarantees', Plugin::text_domain());
		echo '</li>';

		echo '<li>';
		echo '<a href="https://github.com/golchha21/wp-plugin-boilerplate/blob/master/ADVANCED-TOPICS.md" target="_blank" rel="noopener noreferrer">';
		echo esc_html__('Advanced Topics', Plugin::text_domain());
		echo '</a> – ';
		echo esc_html__('multisite behavior, migrations, capabilities, and tools', Plugin::text_domain());
		echo '</li>';

		echo '</ul>';

		echo '<hr />';

		echo '<h3>Making Changes Safely</h3>';
		echo '<ul style="list-style: disc; margin-left: 20px;">';
		echo '<li>' . esc_html__('Add new behavior by creating new classes', Plugin::text_domain()) . '</li>';
		echo '<li>' . esc_html__('Register hooks only via the Loader', Plugin::text_domain()) . '</li>';
		echo '<li>' . esc_html__('Keep settings tab-owned and schema-driven', Plugin::text_domain()) . '</li>';
		echo '<li>' . esc_html__('Avoid modifying core orchestration unless unavoidable', Plugin::text_domain()) . '</li>';
		echo '</ul>';

		echo '<hr />';

		echo '<h3>Contributing and Feedback</h3>';
		echo '<p>';
		echo esc_html__(
			'Contributions are welcome if they strengthen the architecture without diluting its constraints.',
			'wp-plugin-boilerplate'
		);
		echo '</p>';

		echo '<p>';
		echo '<a href="https://github.com/golchha21/wp-plugin-boilerplate/blob/master/CONTRIBUTING.md" target="_blank" rel="noopener noreferrer">';
		echo esc_html__('Read CONTRIBUTING.md', Plugin::text_domain());
		echo '</a>';
		echo '</p>';

		echo '<p style="font-style: italic; color: #555;">';
		echo esc_html__(
			'When in doubt, choose the more explicit and restrictive solution. Clarity scales better than flexibility.',
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
