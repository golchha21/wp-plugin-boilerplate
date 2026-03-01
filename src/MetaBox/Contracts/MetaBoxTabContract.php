<?php

namespace WPPluginBoilerplate\MetaBox\Contracts;

interface MetaBoxTabContract
{
	public function id(): string;

	public function label(): string;

	public function fields(): array;
}
