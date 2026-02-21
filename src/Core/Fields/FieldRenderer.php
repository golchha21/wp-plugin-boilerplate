<?php

namespace WPPluginBoilerplate\Core\Fields;

use InvalidArgumentException;
use WPPluginBoilerplate\Core\Fields\Types\{SelectField};
use WPPluginBoilerplate\Core\Fields\Types\CheckboxField;
use WPPluginBoilerplate\Core\Fields\Types\ColorField;
use WPPluginBoilerplate\Core\Fields\Types\EditorField;
use WPPluginBoilerplate\Core\Fields\Types\InputField;
use WPPluginBoilerplate\Core\Fields\Types\MediaField;
use WPPluginBoilerplate\Core\Fields\Types\MultiSelectField;
use WPPluginBoilerplate\Core\Fields\Types\NumberField;
use WPPluginBoilerplate\Core\Fields\Types\RadioField;
use WPPluginBoilerplate\Core\Fields\Types\RangeField;
use WPPluginBoilerplate\Core\Fields\Types\RepeaterField;
use WPPluginBoilerplate\Core\Fields\Types\TextareaField;

class FieldRenderer
{
	public static function render(?string $optionKey, FieldDefinition $field, mixed $value): void {

		$instance = self::resolve($field, $value);

		$instance->render($optionKey);
	}

	protected static function resolve(FieldDefinition $field, mixed $value) {

		$type = $field->field ?? $field->type ?? null;

		if (!$type) {
			throw new InvalidArgumentException(
				sprintf('Field "%s" has no type defined.', $field->key)
			);
		}

		return match ($type) {

			// Media
			'media',
			'image',
			'file',
			'document',
			'audio',
			'video',
			'archive'
			=> new MediaField($field->key, $field->meta, $value),

			// Input-based
			'text',
			'email',
			'url',
			'password',
			'hidden',
			'date',
			'time',
			'datetime-local'
			=> new InputField($field->key, $field->meta, $value),

			// Distinct types
			'textarea'    => new TextareaField($field->key, $field->meta, $value),
			'checkbox'    => new CheckboxField($field->key, $field->meta, $value),
			'select'      => new SelectField($field->key, $field->meta, $value),
			'multiselect' => new MultiSelectField($field->key, $field->meta, $value),
			'radio'       => new RadioField($field->key, $field->meta, $value),
			'color'       => new ColorField($field->key, $field->meta, $value),
			'editor'      => new EditorField($field->key, $field->meta, $value),
			'range'       => new RangeField($field->key, $field->meta, $value),
			'number'      => new NumberField($field->key, $field->meta, $value),

			'repeater' => new RepeaterField($field->key, $field->meta, $value),

			default => throw new InvalidArgumentException(
				sprintf('Unknown field type "%s" for field "%s".', $type, $field->key)
			),
		};
	}
}
