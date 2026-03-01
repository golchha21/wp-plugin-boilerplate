<?php

namespace WPPluginBoilerplate\MetaBox\Abstracts;

use WPPluginBoilerplate\Core\Support\PostTypes;
use WPPluginBoilerplate\MetaBox\Contracts\MetaBoxContract;

abstract class AbstractMetaBox implements MetaBoxContract
{
	public function postTypes(): array
	{
		return PostTypes::allPublic();
	}

	public function templates(): array
	{
		return [];
	}

	public function context(): string
	{
		return 'normal';
	}

	public function priority(): string
	{
		return 'default';
	}

	public function capability(): string
	{
		return 'edit_posts';
	}

	public function fields(): array
	{
		return [];
	}

	public function tabs(): array
	{
		return [];
	}
}
