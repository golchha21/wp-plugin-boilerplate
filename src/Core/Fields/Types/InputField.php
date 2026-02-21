<?php

namespace WPPluginBoilerplate\Core\Fields\Types;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;

class InputField extends AbstractField
{
	protected function inputType(): string
	{
		$type = $this->meta['field'] ?? $this->meta['type'] ?? 'text';

		$allowed = [
			'text',
			'email',
			'url',
			'password',
			'hidden',
			'date',
			'time',
			'datetime-local',
			'number', // optional safety
		];

		return in_array($type, $allowed, true) ? $type : 'text';
	}

	public function render(?string $optionKey): void
	{
		$this->openFieldWrapper();
		$placeholder = $this->meta['placeholder'] ?? '';

		printf(
			'<input type="%s"
			id="%s"
			name="%s"
			value="%s"
			placeholder="%s"
			class="regular-text" />',
			esc_attr($this->inputType()),
			esc_attr($this->id($optionKey)),
			esc_attr($this->name($optionKey)),
			esc_attr($this->value),
			esc_attr($placeholder)
		);

		$this->description();
		$this->closeFieldWrapper();
	}

}
