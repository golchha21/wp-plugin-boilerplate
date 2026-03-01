<?php

namespace WPPluginBoilerplate\MetaBox;

final class MetaBoxRepository
{
	public static function get(int $postId, string $boxId, string $key, mixed $default = null): mixed {
		$metaKey = self::buildKey($boxId, $key);

		$value = \get_post_meta($postId, $metaKey, true);

		return $value !== '' ? $value : $default;
	}

	public static function update(int $postId, string $boxId, string $key, mixed $value): void {
		\update_post_meta(
			$postId,
			self::buildKey($boxId, $key),
			$value
		);
	}

	public static function delete(int $postId, string $boxId, string $key): void {
		\delete_post_meta(
			$postId,
			self::buildKey($boxId, $key)
		);
	}

	private static function buildKey(string $boxId, string $key): string {
		return '_' . WPPB_PREFIX . $boxId . '_' . $key;
	}
}
