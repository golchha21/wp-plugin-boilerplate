<?php

namespace WPPluginBoilerplate\Core\Fields\Types;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;

class RadioField extends AbstractField
{
	public function render(?string $optionKey, string $context = 'settings'): void
	{
		$this->setContext($context, $optionKey);
		$this->openFieldWrapper();
		foreach ($this->options as $value => $label) {
			printf(
				'<label>
					<input type="radio" name="%s" value="%s" %s />
					%s
				</label>',
				esc_attr($this->name()),
				esc_attr($value),
				checked($this->value, $value, false),
				esc_html($label)
			);
		}

		$this->description();
		$this->closeFieldWrapper();
	}
}
