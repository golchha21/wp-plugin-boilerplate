<?php

namespace WPPluginBoilerplate\MetaBox;

use WPPluginBoilerplate\MetaBox\Boxes\FieldsMetaBox;
use WPPluginBoilerplate\MetaBox\Boxes\RepeatersMetaBox;
use WPPluginBoilerplate\MetaBox\Boxes\SideMetaBox;
use WPPluginBoilerplate\MetaBox\Boxes\TabsMetaBox;
use WPPluginBoilerplate\MetaBox\Contracts\MetaBoxContract;
use WPPluginBoilerplate\MetaBox\Contracts\MetaBoxTabContract;

final class MetaBoxes
{
	public static function all(): array
	{
		$boxes = [
			new FieldsMetaBox(),
			new TabsMetaBox(),
			new RepeatersMetaBox(),
			new SideMetaBox(),
		];

//		self::validate($boxes);

		return $boxes;
	}

	private static function validate(array $boxes): void
	{
		$ids = [];

		foreach ($boxes as $box) {

			if (!$box instanceof MetaBoxContract) {
				throw new \RuntimeException(
					sprintf(
						'Invalid MetaBox instance: %s',
						get_class($box)
					)
				);
			}

			$id = $box->id();

			// Validate format
			if (!preg_match('/^[a-z0-9_]+$/', $id)) {
				throw new \InvalidArgumentException(
					sprintf(
						'Invalid MetaBox id "%s". Use lowercase alphanumeric characters and underscores only.',
						$id
					)
				);
			}

			// Validate uniqueness
			if (isset($ids[$id])) {
				throw new \RuntimeException(
					sprintf(
						'Duplicate MetaBox id detected: "%s"',
						$id
					)
				);
			}

			$ids[$id] = true;

			// Validate tabs within this box
			self::validateTabs($box);
		}
	}

	private static function validateTabs(MetaBoxContract $box): void
	{
		$tabs = $box->tabs();

		if (empty($tabs)) {
			return;
		}

		$ids = [];

		foreach ($tabs as $tab) {

			if (!$tab instanceof MetaBoxTabContract) {
				throw new \RuntimeException(
					sprintf(
						'Invalid MetaBoxTab instance in box "%s": %s',
						$box->id(),
						get_class($tab)
					)
				);
			}

			$id = $tab->id();

			if (!preg_match('/^[a-z0-9_]+$/', $id)) {
				throw new \InvalidArgumentException(
					sprintf(
						'Invalid MetaBoxTab id "%s" in box "%s".',
						$id,
						$box->id()
					)
				);
			}

			if (isset($ids[$id])) {
				throw new \RuntimeException(
					sprintf(
						'Duplicate MetaBoxTab id "%s" in box "%s".',
						$id,
						$box->id()
					)
				);
			}

			$ids[$id] = true;
		}
	}
}
