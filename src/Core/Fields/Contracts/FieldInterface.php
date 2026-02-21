<?php

namespace WPPluginBoilerplate\Core\Fields\Contracts;

interface FieldInterface
{
	public function render(?string $optionKey): void;

	public function sanitize(mixed $value): mixed;

	public function key(): string;
}
