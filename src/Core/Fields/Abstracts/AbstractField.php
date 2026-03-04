<?php

namespace WPPluginBoilerplate\Core\Fields\Abstracts;

use WPPluginBoilerplate\Core\Fields\Contracts\FieldInterface;

abstract class AbstractField implements FieldInterface
{
	protected string $key;
	protected string $type;
	protected string $field;
	protected string $label;
	protected string $description;
	public array $conditions;
	protected ?string $optionKey = null;
	protected mixed $default;
	protected mixed $sanitize;
	protected array $options;
	protected array $meta;
	protected mixed $value;
	protected string $context = 'settings'; // or 'meta'
	private const ALLOWED_OPERATORS = ['==', '!=', 'empty', 'not_empty', 'in', 'not_in',];

	public function __construct(?string $key, array $schema, mixed $value = null)
	{
		$this->key         = $key;
		$this->type        = $schema['type'] ?? 'string';
		$this->default     = $schema['default'] ?? null;
		$this->sanitize    = $schema['sanitize'] ?? $this->defaultSanitizer($this->type);
		$this->label       = $schema['label'] ?? $this->humanize($key);
		$this->description = $schema['description'] ?? '';
		$this->conditions  = $this->normalizeConditions($schema['conditions'] ?? []);
		$this->options	   = $this->normalizeOptions($schema['options'] ?? []);
		$this->meta        = $schema;
		$this->field       = $schema['field'] ?? $this->inferFieldType($this->type);
		$this->value       = $value ?? $this->resolvedDefault();
	}

	public function key(): string
	{
		return $this->key;
	}

	public function setContext(string $context, ?string $optionKey = null): void
	{
		$this->context = $context;
		$this->optionKey = $optionKey;
	}

	protected function name(): string
	{
		if ($this->context === 'meta') {
			return "{$this->optionKey}_{$this->key}";
		}

		// settings / repeater
		if ($this->optionKey !== null) {
			return "{$this->optionKey}[{$this->key}]";
		}

		return $this->key;
	}

	protected function id(): string
	{
		if ($this->context === 'meta') {
			return "{$this->optionKey}_{$this->key}";
		}

		return \sanitize_key(
			preg_replace('/[\[\]]+/', '_', $this->optionKey . '_' . $this->key)
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

	protected function humanize(?string $key): string
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
			echo '<p class="description">' . \esc_html($this->description) . '</p>';
		}
	}

	protected function fieldClass(): string
	{
		return $this->meta['class'] ?? 'width-4';
	}

	protected function openFieldWrapper(): void
	{
		$attributes = '';

		if ($this->hasConditions()) {

			$relation = 'AND';
			$conditions = [];

			// 🔥 Handle new structured format
			if (isset($this->conditions['conditions'])) {
				$relation   = strtoupper($this->conditions['relation'] ?? 'AND');
				$conditions = $this->conditions['conditions'];
			} else {
				// Backward compatibility (flat array)
				$conditions = $this->conditions;
			}

			$resolved = [];

			foreach ($conditions as $condition) {

				$resolved[] = [
					'field'    => $this->resolveConditionFieldName(
						$condition['field']
					),
					'operator' => $condition['operator'],
					'value'    => $condition['value'],
				];
			}

			// 🔥 Output structured JSON
			$output = [
				'relation'   => $relation,
				'conditions' => $resolved,
			];

			$attributes .= sprintf(
				' data-conditions="%s"',
				\esc_attr(\wp_json_encode($output))
			);

			$attributes .= ' data-has-conditions="1"';
		}

		printf(
			'<div class="wppb-field %s"%s>',
			\esc_attr($this->fieldClass()),
			$attributes
		);
	}

	protected function closeFieldWrapper(): void
	{
		echo '</div>';
	}

	protected function normalizeOptions(array $options): array
	{
		$normalized = [];

		foreach ($options as $key => $value) {
			// If numeric key, convert to key=value
			if (is_int($key)) {
				$normalized[$value] = $value;
			} else {
				$normalized[$key] = $value;
			}
		}

		return $normalized;
	}

	protected function resolveConditionFieldName(string $key): string
	{
		if ($this->context === 'meta') {
			return "{$this->optionKey}_{$key}";
		}

		if ($this->optionKey !== null) {
			return "{$this->optionKey}[{$key}]";
		}

		return $key;
	}

	protected function hasConditions(): bool
	{
		return !empty($this->conditions);
	}

	protected function normalizeConditions(array $conditions): array
	{
		$relation = 'AND';

		// Extract relation if present
		if (isset($conditions['relation'])) {
			$relation = strtoupper($conditions['relation']);
			unset($conditions['relation']);
		}

		$normalized = [];

		foreach ($conditions as $condition) {

			if (!isset($condition['field'], $condition['operator'])) {
				continue;
			}

			if (!in_array($condition['operator'], self::ALLOWED_OPERATORS, true)) {
				continue;
			}

			$normalized[] = [
				'field'    => \sanitize_key($condition['field']),
				'operator' => $condition['operator'],
				'value'    => $condition['value'] ?? null,
			];
		}

		if (empty($normalized)) {
			return [];
		}

		return [
			'relation'   => $relation,
			'conditions' => $normalized,
		];
	}
}
