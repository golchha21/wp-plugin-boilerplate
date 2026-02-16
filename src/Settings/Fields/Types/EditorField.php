<?php

namespace WPPluginBoilerplate\Settings\Fields\Types;

use WPPluginBoilerplate\Settings\Fields\Abstracts\AbstractField;

class EditorField extends AbstractField
{
	public function render(string $optionKey): void
	{
		$this->openFieldWrapper();

		$name  = $this->name($optionKey);
		$id    = $this->id($optionKey);
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
