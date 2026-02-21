<?php

namespace WPPluginBoilerplate\Core\Fields\Types;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;

class ColorField extends AbstractField
{
	public function render(?string $optionKey): void
	{
		$this->openFieldWrapper();
		printf(
			'<input type="text" class="wppb-color-field" id="%s" name="%s" value="%s" />',
			esc_attr($this->id($optionKey)),
			esc_attr($this->name($optionKey)),
			esc_attr($this->value)
		);

		$this->description();
		$this->closeFieldWrapper();
	}
}
