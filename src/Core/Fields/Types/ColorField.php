<?php

namespace WPPluginBoilerplate\Core\Fields\Types;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;

class ColorField extends AbstractField
{
	public function render(?string $optionKey, string $context = 'settings'): void
	{
		$this->setContext($context, $optionKey);
		$this->openFieldWrapper();
		printf(
			'<input type="text" class="wppb-color-field" id="%s" name="%s" value="%s" />',
			esc_attr($this->id()),
			esc_attr($this->name()),
			esc_attr($this->value)
		);

		$this->description();
		$this->closeFieldWrapper();
	}
}
