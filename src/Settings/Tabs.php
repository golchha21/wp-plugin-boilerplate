<?php

namespace WPPluginBoilerplate\Settings;

use WPPluginBoilerplate\Settings\Contracts\SettingsTabContract;
use WPPluginBoilerplate\Settings\Tabs\AboutSettingsTab;
use WPPluginBoilerplate\Settings\Tabs\CoreFieldsSettingsTab;
use WPPluginBoilerplate\Settings\Tabs\EnhancedFieldsSettingsTab;
use WPPluginBoilerplate\Settings\Tabs\FeatureFieldsSettingsTab;
use WPPluginBoilerplate\Settings\Tabs\HelpSettingsTab;
use WPPluginBoilerplate\Settings\Tabs\ToolsSettingsTab;

final class Tabs
{
	/**
	 * Return ALL tab definitions.
	 * No capability checks.
	 * No scope filtering.
	 * No request logic.
	 */
	public static function all(): array
	{
		$tabs = [
			new CoreFieldsSettingsTab(),
			new EnhancedFieldsSettingsTab(),
			new FeatureFieldsSettingsTab(),
			new ToolsSettingsTab(),
			new AboutSettingsTab(),
			new HelpSettingsTab(),
		];

//		self::validate($tabs);

		return $tabs;
	}

	private static function validate(array $tabs): void
	{
		$ids = [];

		foreach ($tabs as $tab) {

			if (!$tab instanceof SettingsTabContract) {
				throw new \RuntimeException(
					sprintf(
						'Invalid Settings Tab instance: %s',
						get_class($tab)
					)
				);
			}

			$id = $tab->id();

			if (!preg_match('/^[a-z0-9_]+$/', $id)) {
				throw new \InvalidArgumentException(
					sprintf(
						'Invalid Settings Tab id "%s". Use lowercase alphanumeric characters and underscores only.',
						$id
					)
				);
			}

			if (isset($ids[$id])) {
				throw new \RuntimeException(
					sprintf(
						'Duplicate Settings Tab id detected: "%s"',
						$id
					)
				);
			}

			$ids[$id] = true;
		}
	}
}
