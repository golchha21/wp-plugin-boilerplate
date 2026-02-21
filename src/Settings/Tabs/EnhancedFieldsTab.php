<?php

namespace WPPluginBoilerplate\Settings\Tabs;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Contracts\SettingsContract;
use WPPluginBoilerplate\Settings\Contracts\TabContract;

class EnhancedFieldsTab implements TabContract, SettingsContract
{
	public function id(): string
	{
		return 'enhanced-fields';
	}

	public function label(): string
	{
		return 'Enhanced Fields';
	}


	public function capability(): string
	{
		return 'manage_options';
	}

	public static function optionKey(): string
	{
		return Plugin::option_key() . 'ef';
	}

	public static function fields(): array
	{
		return array(
			'media' => array(
				'type' => 'integer',
				'field' => 'media',
				'multiple' => true,
			),

			'file' => array(
				'type' => 'integer',
				'field' => 'file',
			),

			'image' => array(
				'type' => 'integer',
				'field' => 'image',
			),

			'document' => array(
				'type' => 'integer',
				'field' => 'document',
			),

			'audio' => array(
				'type' => 'integer',
				'field' => 'audio',
			),

			'video' => array(
				'type' => 'integer',
				'field' => 'video',
			),

			'archive' => array(
				'type' => 'integer',
				'field' => 'archive',
			),

			'color' => array(
				'type' => 'string',
				'field' => 'color',
				// hex color value.
			),

			'editor' => array(
				'type' => 'string',
				'field' => 'editor',
				'rows' => 8,
				'media_buttons' => true,
				'class' => 'width-8',
			),
		);
	}

	public function render(): void
	{
		settings_fields(static::optionKey());
		do_settings_sections(static::optionKey());
	}

}
