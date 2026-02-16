<?php

namespace WPPluginBoilerplate\Settings\Fields\Abstracts;

use WPPluginBoilerplate\Settings\Fields\Contracts\FieldInterface;

abstract class AbstractField implements FieldInterface
{
	protected string $key;
	protected string $type;
	protected string $field;
	protected string $label;
	protected string $description;
	protected mixed $default;
	protected mixed $sanitize;
	protected array $options;
	protected array $meta;
	protected mixed $value;

	public function __construct(string $key, array $schema, mixed $value = null)
	{
		$this->key         = $key;
		$this->type        = $schema['type'] ?? 'string';
		$this->default     = $schema['default'] ?? null;
		$this->sanitize    = $schema['sanitize'] ?? $this->defaultSanitizer($this->type);
		$this->label       = $schema['label'] ?? $this->humanize($key);
		$this->description = $schema['description'] ?? '';
		$this->options     = $schema['options'] ?? [];
		$this->meta        = $schema;
		$this->field       = $schema['field'] ?? $this->inferFieldType($this->type);
		$this->value       = $value ?? $this->resolvedDefault();
	}

	public function key(): string
	{
		return $this->key;
	}

	protected function name(string $optionKey): string
	{
		return $optionKey . '[' . $this->key . ']';
	}

	protected function id(string $optionKey): string
	{
		return sanitize_key(
			preg_replace('/[\[\]]+/', '_', $optionKey . '_' . $this->key)
		);
	}

	public function sanitize(mixed $value): mixed
	{
		return is_callable($this->sanitize)
			? call_user_func($this->sanitize, $value)
			: $value;
	}

	protected function defaultSanitizer(string $type): callable|string
	{
		return match ($type) {
			'boolean' => 'rest_sanitize_boolean',
			'integer', 'number' => 'absint',
			default => 'sanitize_text_field',
		};
	}

	protected function humanize(string $key): string
	{
		return ucwords(str_replace('_', ' ', $key));
	}

	protected function inferFieldType(string $type): string
	{
		return match ($type) {
			'boolean' => 'checkbox',
			'integer', 'number' => 'number',
			'array' => 'textarea',
			default => 'text',
		};
	}

	protected function resolvedDefault(): mixed
	{
		if ($this->default !== null) {
			return $this->default;
		}

		return match ($this->field) {
			'checkbox' => false,
			'number', 'range' => 0,
			'multiselect' => [],
			'media', 'image', 'file' => 0,
			default => '',
		};
	}

	protected function description(): void
	{
		if (!empty($this->description)) {
			echo '<p class="description">' . esc_html($this->description) . '</p>';
		}
	}

	protected function fieldClass(): string
	{
		return $this->meta['class'] ?? 'width-4';
	}

	protected function openFieldWrapper(): void
	{
		printf(
			'<div class="wppb-field %s">',
			esc_attr($this->fieldClass())
		);
	}

	protected function closeFieldWrapper(): void
	{
		echo '</div>';
	}

}
