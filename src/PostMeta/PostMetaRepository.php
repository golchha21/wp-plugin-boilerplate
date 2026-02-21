<?php

namespace WPPluginBoilerplate\PostMeta;

final class PostMetaRepository
{
	public static function get(int $postId, string $metaKey, mixed $default = null): mixed
	{
		$value = \get_post_meta($postId, $metaKey, true);

		return $value !== '' ? $value : $default;
	}

	public static function update(int $postId, string $metaKey, mixed $value): void
	{
		\update_post_meta($postId, $metaKey, $value);
	}

	public static function delete(int $postId, string $metaKey): void
	{
		\delete_post_meta($postId, $metaKey);
	}
}
