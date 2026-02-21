<?php

namespace WPPluginBoilerplate\Settings;

use WPPluginBoilerplate\Settings\Tabs\AboutTab;
use WPPluginBoilerplate\Settings\Tabs\CoreFieldsTab;
use WPPluginBoilerplate\Settings\Tabs\EnhancedFieldsTab;
use WPPluginBoilerplate\Settings\Tabs\FeatureFieldsTab;
use WPPluginBoilerplate\Settings\Tabs\HelpTab;
use WPPluginBoilerplate\Settings\Tabs\ToolsTab;

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
		return [
			new CoreFieldsTab(),
			new EnhancedFieldsTab(),
			new FeatureFieldsTab(),
			new ToolsTab(),
			new AboutTab(),
			new HelpTab(),
		];
	}
}
