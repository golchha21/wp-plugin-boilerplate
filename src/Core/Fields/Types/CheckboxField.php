<?php

namespace WPPluginBoilerplate\Core\Fields\Types;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;
use WPPluginBoilerplate\Plugin;

class CheckboxField extends AbstractField
{
	public function render(?string $optionKey, string $context = 'settings'): void
	{
		$this->setContext($context, $optionKey);
		$this->openFieldWrapper();
		$boxLabel = $this->meta['box_label'] ?? __('Enable', Plugin::text_domain());
		printf(
			'<label>
				<input type="checkbox" id="%s" name="%s" value="1" %s />
				%s
			</label>',
			esc_attr($this->id()),
			esc_attr($this->name()),
			checked($this->value, true, false),
			esc_html__($boxLabel)
		);

		$this->description();
		$this->closeFieldWrapper();
	}
}
