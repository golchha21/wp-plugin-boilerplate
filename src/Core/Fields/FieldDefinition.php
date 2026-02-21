<?php

namespace WPPluginBoilerplate\Core\Fields;

use WPPluginBoilerplate\Core\Support\WPMimeGroups;

class FieldDefinition
{
	public string $key;
	public string $type;
	public string $field;
	public string $label;
	public string $description;
	public mixed $default;
	public mixed $sanitize;
	public array $options;
	public array $meta;

	public function __construct(string $key, array $schema)
	{
		$this->key         = $key;
		$this->meta        = $schema;

		$type              = $schema['type'] ?? 'string';

		$this->type        = $type;
		$this->field       = $schema['field'] ?? $this->inferField($type);
		$this->label       = $schema['label'] ?? $this->humanize($key);
		$this->description = $schema['description'] ?? '';
		$this->default     = $schema['default'] ?? $this->defaultValue($this->field);
		$this->options     = $schema['options'] ?? [];
		$this->sanitize    = $schema['sanitize'] ?? $this->defaultSanitizer($type);
	}

	public static function fromSchema(string $key, array $schema): self
	{
		return new self($key, $schema);
	}

	protected function inferField(string $type): string
	{
		return match ($type) {
			'boolean' => 'checkbox',
			'integer', 'number' => 'number',
			'array' => 'textarea',
			default => 'text',
		};
	}

	protected function defaultSanitizer(string $type): callable|string
	{
		return match ($type) {
			'boolean' => 'rest_sanitize_boolean',
			'integer', 'number' => 'absint',
			default => 'sanitize_text_field',
		};
	}

	protected function defaultValue(string $field): mixed
	{
		return match ($field) {
			'checkbox' => false,
			'number', 'range' => 0,
			'multiselect' => [],
			'media', 'image', 'file', 'document', 'audio', 'video', 'archive' => 0,
			default => '',
		};
	}

	public function resolvedDefault(): mixed
	{
		return $this->default;
	}

	protected function humanize(string $key): string
	{
		return ucwords(str_replace('_', ' ', $key));
	}

	public function allowedMimes(): ?array
	{
		return match ($this->field) {
			'image' => WPMimeGroups::group('image'),
			'document' => WPMimeGroups::group('document'),
			'audio' => WPMimeGroups::group('audio'),
			'video' => WPMimeGroups::group('video'),
			'archive' => WPMimeGroups::group('archive'),
			default => null, // media & file = unrestricted.
		};
	}

	public function previewMode(): string
	{
		return match ($this->field) {
			'image' => 'image',
			'media' => 'auto',
			default => 'file',
		};
	}
}
