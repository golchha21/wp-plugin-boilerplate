<?php

namespace WPPluginBoilerplate\Core\Fields\Types;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;

class SelectField extends AbstractField
{
	public function render(?string $optionKey): void
	{
		$this->openFieldWrapper();
		printf(
			'<select id="%s" name="%s">',
			esc_attr($this->id($optionKey)),
			esc_attr($this->name($optionKey))
		);

		foreach ($this->options as $value => $label) {
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr($value),
				selected($this->value, $value, false),
				esc_html($label)
			);
		}

		echo '</select>';

		$this->description();
		$this->closeFieldWrapper();
	}
}
