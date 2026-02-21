<?php

namespace WPPluginBoilerplate\Core\Fields\Types;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;

class RadioField extends AbstractField
{
	public function render(?string $optionKey): void
	{
		$this->openFieldWrapper();
		foreach ($this->options as $value => $label) {
			printf(
				'<label>
					<input type="radio" name="%s" value="%s" %s />
					%s
				</label><br>',
				esc_attr($this->name($optionKey)),
				esc_attr($value),
				checked($this->value, $value, false),
				esc_html($label)
			);
		}

		$this->description();
		$this->closeFieldWrapper();
	}
}
