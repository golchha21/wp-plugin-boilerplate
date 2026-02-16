<?php

namespace WPPluginBoilerplate\Settings\Fields\Types;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Fields\Abstracts\AbstractField;
use WPPluginBoilerplate\Settings\Fields\FieldDefinition;

class MediaField extends AbstractField
{
	protected FieldDefinition $definition;

	public function __construct(string $key, array $schema, mixed $value = null)
	{
		parent::__construct($key, $schema, $value);

		// Rebuild a FieldDefinition instance so we can reuse its logic
		$this->definition = FieldDefinition::fromSchema($key, $schema);
	}

	public function render(string $optionKey): void
	{
		$this->openFieldWrapper();

		$name     = $this->name($optionKey);
		$value    = $this->value;
		$multiple = !empty($this->definition->meta['multiple']);

		$preview = '';

		// Normalize value
		if ($multiple) {
			$value = is_array($value) ? $value : [];
		} else {
			$value = (int) $value;
		}

		/* ---------------------------------
		   Build Preview + Hidden Inputs
		--------------------------------- */

		if ($multiple) {

			foreach ($value as $id) {

				$preview .= '<div class="wppb-media-item" data-id="' . esc_attr($id) . '">';

				$preview .= '<div class="wppb-media-item-header">';
				$preview .= '<span class="wppb-media-drag dashicons dashicons-move" draggable="true"></span>';
				$preview .= '<button type="button" class="wppb-media-item-remove dashicons dashicons-no-alt"></button>';
				$preview .= '</div>';

				$preview .= '<div class="wppb-media-item-body">';

				if (
					$this->definition->previewMode() === 'image' ||
					($this->definition->previewMode() === 'auto' && wp_attachment_is_image($id))
				) {
					$preview .= wp_get_attachment_image($id, 'thumbnail');
				} else {
					$file = get_attached_file($id);
					if ($file) {
						$preview .= '<code>' . esc_html(basename($file)) . '</code>';
					}
				}
				$preview .= '<input type="hidden" name="' . esc_attr($name) . '[]" value="' . esc_attr($id) . '" />';

				$preview .= '</div>';
				$preview .= '</div>';
			}

		} else {

			if ($value) {

				$preview .= '<div class="wppb-media-single">';

				if (
					$this->definition->previewMode() === 'image' ||
					($this->definition->previewMode() === 'auto' && wp_attachment_is_image($value))
				) {
					$preview .= wp_get_attachment_image($value, 'thumbnail');
				} else {
					$file = get_attached_file($value);
					if ($file) {
						$preview .= '<code>' . esc_html(basename($file)) . '</code>';
					}
				}

				$preview .= '</div>';
			}
		}

		/* ---------------------------------
		   Output
		--------------------------------- */

		printf(
			'<div class="wppb-media-field"
			data-name="%s"
			data-multiple="%s">

			%s

			<div class="wppb-media-preview">%s</div>

			<div class="wppb-media-actions">

				<button type="button"
					class="wppb-media-select"
					data-field="%s"
					data-mimes="%s"
					data-multiple="%s"
					data-tooltip="%s"
					aria-label="%s">
					<span class="dashicons dashicons-upload"></span>
				</button>

				<button type="button"
					class="wppb-media-remove"
					%s
					data-tooltip="%s"
					aria-label="%s">
					<span class="dashicons dashicons-no-alt"></span>
				</button>

			</div>

		</div>',
			esc_attr($name),
			esc_attr($multiple ? 'true' : 'false'),

			// single hidden input only
			$multiple
				? ''
				: sprintf(
				'<input type="hidden" name="%s" value="%s" />',
				esc_attr($name),
				esc_attr($value)
			),

			$preview,
			esc_attr($this->definition->field),
			esc_attr(wp_json_encode($this->definition->allowedMimes())),
			esc_attr($multiple ? 'true' : 'false'),
			esc_attr__('Select media', Plugin::text_domain()),
			esc_attr__('Select media', Plugin::text_domain()),
			$multiple
				? (empty($value) ? 'disabled' : '')
				: ($value ? '' : 'disabled'),
			esc_attr__('Remove media', Plugin::text_domain()),
			esc_attr__('Remove media', Plugin::text_domain())
		);

		$this->description();
		$this->closeFieldWrapper();
	}

	protected function fieldClass(): string
	{
		return $this->meta['class'] ?? 'width';
	}
}
