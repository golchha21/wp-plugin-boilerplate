<?php

namespace WPPluginBoilerplate\Core\Fields;

use WPPluginBoilerplate\Core\Fields\Abstracts\AbstractField;

final class FieldFactory
{
	public static function make(string $key, array $definition): ?AbstractField {

		$fieldType = $definition['field'] ?? null;

		if (!$fieldType) {
			return null;
		}

		$class = 'WPPluginBoilerplate\\Core\\Fields\\Types\\' . ucfirst($fieldType) . 'Field';

		if (!class_exists($class) || !is_subclass_of($class, AbstractField::class)) {
			return null;
		}

		return new $class($key, $definition);
	}
}
