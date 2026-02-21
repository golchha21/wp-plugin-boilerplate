<?php

namespace WPPluginBoilerplate\Settings\Tabs;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Contracts\TabContract;

class ToolsTab implements TabContract
{
	public function id(): string
	{
		return 'tools';
	}

	public function label(): string
	{
		return 'Tools';
	}

	public function capability(): string
	{
		return 'manage_options';
	}

	public function render(): void
	{
		echo '<h2>' . esc_html__('Tools', Plugin::text_domain()) . '</h2>';

		$this->render_notices();

		$this->render_export();
		$this->render_import();
	}

	/* -----------------------------------------------------------------
	 * Export
	 * ----------------------------------------------------------------- */

	protected function render_export(): void
	{
		$export_url = wp_nonce_url(
			admin_url('admin-post.php?action='. Plugin::prefix() .'export'),
			Plugin::prefix() .'export_all'
		);

		echo '<h3>' . esc_html__('Export Settings', Plugin::text_domain()) . '</h3>';
		echo '<p class="description">';
		echo esc_html__(
			'Download a JSON file containing all plugin settings.',
			Plugin::text_domain()
		);
		echo '</p>';

		echo '<a href="' . esc_url($export_url) . '" class="button button-primary">';
		echo esc_html__('Export all settings', Plugin::text_domain());
		echo '</a>';
	}

	/* -----------------------------------------------------------------
	 * Import
	 * ----------------------------------------------------------------- */
	protected function render_import(): void
	{
		echo '<h3 style="margin-top:2em;">' . esc_html__('Import Settings', Plugin::text_domain()) . '</h3>';

		echo '<p class="description">';
		echo esc_html__(
			'Upload a previously exported settings file to restore all plugin settings.',
			Plugin::text_domain()
		);
		echo '</p>';

		echo '<div class="notice notice-info inline"><p>';
		echo esc_html__(
			'Importing settings will overwrite all existing plugin settings.',
			Plugin::text_domain()
		);
		echo '</p></div>';

		$input_id  = Plugin::prefix() .'import_file';
		$label_id  = Plugin::prefix() .'import_label';
		$button_id = Plugin::prefix() .'import_submit';

		echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" enctype="multipart/form-data" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">';

		wp_nonce_field(''. Plugin::prefix() .'import_all');

		echo '<input type="hidden" name="action" value="'. Plugin::prefix() .'import" />';

		// Hidden file input
		echo '<input
		type="file"
		name="import_file"
		id="' . esc_attr($input_id) . '"
		accept="application/json"
		required
		style="display:none;"
		onchange="HandleFileSelect(this, \'' . esc_js($button_id) . '\', \'' . esc_js($label_id) . '\')"
	/>';

		// Choose file button
		echo '<label for="' . esc_attr($input_id) . '" class="button button-secondary">';
		echo esc_html__('Choose file', Plugin::text_domain());
		echo '</label>';

		// Filename preview
		echo '<span id="' . esc_attr($label_id) . '" style="font-style:italic; color:#666;">';
		echo esc_html__('No file selected', Plugin::text_domain());
		echo '</span>';

		// Submit button (disabled until file chosen)
		submit_button(
			esc_html__('Import settings', Plugin::text_domain()),
			'primary',
			'submit',
			false,
			[
				'id'       => $button_id,
				'disabled' => true,
			]
		);

		echo '</form>';
	}

	/* -----------------------------------------------------------------
	 * Helpers
	 * ----------------------------------------------------------------- */

	protected function render_notices(): void
	{
		$notice = $_GET[''. Plugin::prefix() .'notice'] ?? null;

		if (! $notice) {
			return;
		}

		switch ($notice) {
			case 'exported':
				$class   = 'notice-success';
				$message = __('Settings exported successfully.', Plugin::text_domain());
				break;

			case 'imported':
				$class   = 'notice-success';
				$message = __('Settings imported successfully.', Plugin::text_domain());
				break;

			case 'error':
				$class   = 'notice-error';
				$message = __('An error occurred while processing the request.', Plugin::text_domain());
				break;

			default:
				return;
		}

		echo '<div class="notice ' . esc_attr($class) . ' inline">';
		echo '<p>' . esc_html($message) . '</p>';
		echo '</div>';
	}
}
