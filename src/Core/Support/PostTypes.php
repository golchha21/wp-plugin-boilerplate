<?php

namespace WPPluginBoilerplate\Core\Support;

final class PostTypes
{
	private const EXCLUDED = [
		'attachment',
		'revision',
		'nav_menu_item',
	];

	public static function all(bool $exclude = false): array
	{
		return self::filter(
			\get_post_types([], 'names'),
			$exclude
		);
	}

	public static function allPublic(bool $exclude = true): array
	{
		return self::filter(
			\get_post_types(['public' => true], 'names'),
			$exclude
		);
	}

	public static function allDefault(bool $exclude = false): array
	{
		return self::filter(
			\get_post_types(
				[
					'public'   => true,
					'_builtin' => true,
				],
				'names'
			),
			$exclude
		);
	}

	public static function allCustom(): array
	{
		return \get_post_types( [ 'public' => true, '_builtin' => false, ], 'names' );
	}

	private static function filter(array $types, bool $exclude): array
	{
		if ($exclude) {
			$types = array_diff($types, self::EXCLUDED);
		}

		return array_values($types);
	}
}
