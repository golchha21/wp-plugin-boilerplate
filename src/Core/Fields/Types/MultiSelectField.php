<?php

namespace WPPluginBoilerplate\Core\Fields\Types;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;

class MultiSelectField extends AbstractField
{
	public function render(?string $optionKey): void
	{
		$this->openFieldWrapper();
		printf(
			'<select id="%s" name="%s[]" multiple>',
			esc_attr($this->id($optionKey)),
			esc_attr($this->name($optionKey))
		);

		$values = array_map('strval', (array) $this->value);

		foreach ($this->options as $value => $label) {
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr($value),
				in_array((string) $value, $values, true) ? 'selected' : '',
				esc_html($label)
			);
		}

		echo '</select>';

		$this->description();
		$this->closeFieldWrapper();
	}

	public function sanitize(mixed $value): array
	{
		return array_map('sanitize_text_field', (array) $value);
	}
}
