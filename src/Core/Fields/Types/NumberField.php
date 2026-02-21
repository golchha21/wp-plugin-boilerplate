<?php

namespace WPPluginBoilerplate\Core\Fields\Types;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;

class NumberField extends AbstractField
{
	public function render(?string $optionKey): void
	{
		$this->openFieldWrapper();
		$min  = $this->meta['min']  ?? '';
		$max  = $this->meta['max']  ?? '';
		$step = $this->meta['step'] ?? '';

		printf(
			'<input type="number"
				id="%s"
				name="%s"
				value="%s"
				min="%s"
				max="%s"
				step="%s" />',
			esc_attr($this->id($optionKey)),
			esc_attr($this->name($optionKey)),
			esc_attr($this->value),
			esc_attr($min),
			esc_attr($max),
			esc_attr($step)
		);

		$this->description();
		$this->closeFieldWrapper();
	}
}
