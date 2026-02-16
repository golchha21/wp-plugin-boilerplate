<?php

namespace WPPluginBoilerplate\Settings\Tabs;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Contracts\SettingsContract;
use WPPluginBoilerplate\Settings\Contracts\TabContract;

class FeatureFieldsTab implements TabContract, SettingsContract
{
	public function id(): string
	{
		return 'feature-fields';
	}

	public function label(): string
	{
		return 'Feature Fields';
	}

	public function hasForm(): bool
	{
		return true;
	}

	public function hasActions(): bool
	{
		return true; // if you want Reset
	}

	public function viewCapability(): string
	{
		return 'manage_options';
	}

	public function manageCapability(): string
	{
		return 'manage_options';
	}

	public static function optionKey(): string
	{
		return Plugin::option_key() . 'ff';
	}

	public static function fields(): array
	{
		return array(
			'features' => [
				'field' => 'repeater',
				'max'   => 5,
				'fields' => [
					'title' => [
						'field' => 'text',
						'label' => 'Title',
						'class' => 'width',
					],
					'subtitle' => [
						'field' => 'text',
						'label' => 'Sub-Title',
						'class' => 'width-6',
					],
				],
			],
			'textarea' => [
				'field' => 'repeater',
				'title_field' => 'label',
				'fields' => [
					'image' => array(
						'type' => 'integer',
						'field' => 'image',
						'multiple' => false,
						'class' => 'width-2',
					),
					'description' => array(
						'type' => 'string',
						'field' => 'textarea',
						'rows' => 5,
						'media_buttons' => false,
						'class' => 'width-10',
					),
				],
			]
		);
	}

	public function render(): void
	{
		settings_fields(static::optionKey());
		do_settings_sections(static::optionKey());
	}

}
