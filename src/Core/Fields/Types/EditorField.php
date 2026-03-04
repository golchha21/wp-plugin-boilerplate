<?php

namespace WPPluginBoilerplate\Core\Fields\Types;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;

class EditorField extends AbstractField
{
	public function render(?string $optionKey, string $context = 'settings'): void
	{
		$this->setContext($context, $optionKey);
		$this->openFieldWrapper();

		$name  = $this->name();
		$id    = $this->id();
		$value = $this->value;

		$rows         = $this->meta['rows'] ?? 8;
		$mediaButtons = $this->meta['media_buttons'] ?? false;

		wp_editor(
			$value,
			$id,
			[
				'textarea_name' => $name,
				'textarea_rows' => $rows,
				'media_buttons' => $mediaButtons,
			]
		);

		$this->description();
		$this->closeFieldWrapper();
	}

	protected function fieldClass(): string
	{
		return $this->meta['class'] ?? 'width';
	}
}
