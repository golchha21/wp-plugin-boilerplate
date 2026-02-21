<?php

namespace WPPluginBoilerplate\PostMeta;

use WPPluginBoilerplate\PostMeta\Boxes\FieldsMetaBox;

final class MetaBoxes
{
	public static function all(): array
	{
		return [
			new FieldsMetaBox(),
		];
	}
}
