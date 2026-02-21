<?php

namespace WPPluginBoilerplate\Core\Fields\Types;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;

class RangeField extends AbstractField
{
	public function render(?string $optionKey): void
	{
		$this->openFieldWrapper();
		$min = $this->meta['min'] ?? 0;
		$max = $this->meta['max'] ?? 100;
		$step = $this->meta['step'] ?? 1;

		printf(
			'<input type="range" id="%s" name="%s" value="%s" min="%d" max="%d" step="%d" />',
			esc_attr($this->id($optionKey)),
			esc_attr($this->name($optionKey)),
			esc_attr($this->value),
			$min,
			$max,
			$step
		);

		$this->description();
		$this->closeFieldWrapper();
	}
}
