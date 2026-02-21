<?php

namespace WPPluginBoilerplate\PostMeta\Contracts;

interface MetaBoxContract
{
	public function id(): string;

	public function title(): string;

	public function postTypes(): array;

	public function context(): string;

	public function priority(): string;

	public function capability(): string;

	public function fields(): array;

	public function tabs(): array; // return array<MetaBoxTabContract>
}
