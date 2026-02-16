<?php

namespace WPPluginBoilerplate\Settings\Fields\Types;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Fields\Abstracts\AbstractField;
use WPPluginBoilerplate\Settings\Fields\FieldDefinition;
use WPPluginBoilerplate\Settings\Fields\FieldRenderer;

class RepeaterField extends AbstractField
{
	public function render(string $optionKey): void
	{
		$this->openFieldWrapper();
		$rows = is_array($this->value) ? $this->value : [];
		$min = $this->meta['min'] ?? 0;
		$max = $this->meta['max'] ?? null;

		// Open wrapper
		echo '<div class="wppb-repeater-wrapper"
        data-min="' . esc_attr($this->meta['min'] ?? 0) . '"
        data-max="' . esc_attr($this->meta['max'] ?? '') . '">';

		echo '<div class="wppb-repeater">';

		/* ---------------------------------
		   Existing Rows
		--------------------------------- */

		$value = is_array($this->value) ? array_values($this->value) : [];

		foreach ($value as $index => $row) {
			$this->renderRow($optionKey, $index, $row);
		}

		echo '</div>'; // .wppb-repeater


		/* ---------------------------------
		   TEMPLATE ROW (hidden automatically)
		--------------------------------- */

		echo '<template class="wppb-repeater-template">';
		$this->renderRow($optionKey, '__index__', []);
		echo '</template>';

		/* ---------------------------------
		   Add Button
		--------------------------------- */

		echo '<button type="button" class="button wppb-repeater-add">
        <span class="dashicons dashicons-plus"></span>
        ' . esc_html__('Add', Plugin::text_domain()) . '
      </button>';

		echo '</div>'; // wrapper

		$this->description();
		$this->closeFieldWrapper();
	}

	protected function renderRow(string $optionKey, string|int $index, array $row): void
	{
		echo '<div class="wppb-repeater-item width is-collapsed" data-index="' . esc_attr($index) . '">';

		/* ---------------------------------
		   HEADER
		--------------------------------- */

		echo '<div class="wppb-repeater-header">';

		// LEFT SIDE (Drag + Title)
		echo '<div class="wppb-repeater-left">';

		echo '<span class="wppb-repeater-drag dashicons dashicons-move" draggable="true"></span>';

		echo '<span class="wppb-repeater-title">';
		echo 'Item ' . (is_numeric($index) ? ((int)$index + 1) : '');
		echo '</span>';

		echo '</div>';

		// CONTROLS
		echo '<div class="wppb-repeater-controls">';

		// Toggle (Collapsed by default → show PLUS)
		echo '<button type="button"
			class="wppb-repeater-toggle"
			aria-expanded="false"
			title="' . esc_attr__('Expand', Plugin::text_domain()) . '">
			<span class="dashicons dashicons-plus"></span>
		  </button>';

		// Duplicate
		echo '<button type="button"
			class="wppb-repeater-duplicate"
			title="' . esc_attr__('Duplicate', Plugin::text_domain()) . '">
			<span class="dashicons dashicons-admin-page"></span>
		  </button>';

		// Remove
		echo '<button type="button"
			class="wppb-repeater-remove"
			title="' . esc_attr__('Remove', Plugin::text_domain()) . '">
			<span class="dashicons dashicons-no-alt"></span>
		  </button>';

		echo '</div>'; // controls
		echo '</div>'; // header


		/* ---------------------------------
		   BODY
		--------------------------------- */

		echo '<div class="wppb-repeater-body">';
		echo '<div class="wppb-repeater-row">';

		foreach ($this->meta['fields'] as $subKey => $subSchema) {

			$definition = FieldDefinition::fromSchema($subKey, $subSchema);

			$value = $row[$subKey] ?? $definition->resolvedDefault();

			$nestedOptionKey = $optionKey . '[' . $this->key . '][' . $index . ']';

			FieldRenderer::render($nestedOptionKey, $definition, $value);
		}

		echo '</div>'; // repeater-row
		echo '</div>'; // repeater-body

		echo '</div>'; // repeater-item
	}

	public function sanitize(mixed $value): array
	{
		if (!is_array($value)) {
			return [];
		}

		$sanitized = [];

		foreach ($value as $key => $row) {

			// Skip template placeholder
			if ($key === '__index__') {
				continue;
			}

			if (!is_array($row)) {
				continue;
			}

			$cleanRow = [];

			foreach ($this->meta['fields'] as $subKey => $subSchema) {

				$definition = FieldDefinition::fromSchema($subKey, $subSchema);

				$raw = $row[$subKey] ?? null;

				if (is_callable($definition->sanitize)) {
					$cleanRow[$subKey] = call_user_func($definition->sanitize, $raw);
				} else {
					$cleanRow[$subKey] = $raw;
				}
			}

			// Skip completely empty rows
			if ($this->isEmptyRow($cleanRow)) {
				continue;
			}

			$sanitized[$key] = $cleanRow;
		}

		// Ensure clean numeric indexing
		return array_values($sanitized);
	}

	protected function fieldClass(): string
	{
		return $this->meta['class'] ?? 'width';
	}

	private function isEmptyRow(array $row): bool
	{
		foreach ($row as $value) {

			if (is_array($value) && !empty($value)) {
				return false;
			}

			if (!is_array($value) && $value !== null && $value !== '' && $value !== 0) {
				return false;
			}
		}

		return true;
	}
}
