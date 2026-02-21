<?php

namespace WPPluginBoilerplate\Core\Support;


final class WPMimeGroups
{
	public static function group(string $group): array
	{
		$mimes = wp_get_mime_types();

		return match ($group) {
			'image' => self::filterByPrefix($mimes, 'image/'),
			'document' => self::filterByExtensions(
				$mimes,
				array(
					'pdf',
					'doc',
					'docx',
					'xls',
					'xlsx',
					'ppt',
					'pptx',
					'txt',
					'json',
				)
			),
			'audio' => self::filterByPrefix($mimes, 'audio/'),
			'video' => self::filterByPrefix($mimes, 'video/'),
			'archive' => self::filterByExtensions(
				$mimes,
				array(
					'zip',
					'rar',
					'7z',
					'tar',
					'gz',
				)
			),
			default => array(),
		};
	}


	private static function filterByPrefix(array $mimes, string $prefix): array
	{
		return array_values(
			array_filter($mimes, fn($mime) => str_starts_with($mime, $prefix))
		);
	}


	private static function filterByExtensions(array $mimes, array $extensions): array
	{
		$allowed = array();

		foreach ($mimes as $ext => $mime) {
			foreach ($extensions as $needle) {
				if (str_contains($ext, $needle)) {
					$allowed[] = $mime;
					break;
				}
			}
		}

		return array_unique($allowed);
	}
}
