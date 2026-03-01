<?php

namespace WPPluginBoilerplate\MetaBox\Boxes;

use WPPluginBoilerplate\MetaBox\Abstracts\AbstractMetaBox;
use WPPluginBoilerplate\MetaBox\Tabs\CoreFieldsTab;
use WPPluginBoilerplate\MetaBox\Tabs\EnhancedFieldsTab;
use WPPluginBoilerplate\MetaBox\Tabs\FeatureFieldsTab;

class TabsMetaBox extends AbstractMetaBox
{
	public function id(): string
	{
		return 'tabs_meta';
	}

	public function title(): string
	{
		return 'Tabs Example';
	}

	public function tabs(): array
	{
		return [
			new CoreFieldsTab(),
			new EnhancedFieldsTab(),
			new FeatureFieldsTab(),
		];
	}

}
