<?php

namespace WPPluginBoilerplate\Settings\Tabs;

use WPPluginBoilerplate\Plugin;
use WPPluginBoilerplate\Settings\Contracts\SettingsContract;
use WPPluginBoilerplate\Settings\Contracts\TabContract;

class CoreFieldsTab implements TabContract, SettingsContract
{
	public function id(): string
	{
		return 'core-fields';
	}

	public function label(): string
	{
		return 'Core Fields';
	}

	public function capability(): string
	{
		return 'manage_options';
	}

	public static function optionKey(): string
	{
		return Plugin::option_key() . 'cf';
	}

	public static function fields(): array
	{
		return array(
			'text' => array(
				'type' => 'string',
				'field' => 'text',
			),

			'textarea' => array(
				'type' => 'string',
				'field' => 'textarea',
				'rows' => 8,
				'class' => 'width-8',
			),

			'email' => array(
				'type' => 'string',
				'field' => 'email',
			),

			'url' => array(
				'type' => 'string',
				'field' => 'url',
			),

			'password' => array(
				'type' => 'string',
				'field' => 'password',
			),

			'hidden' => array(
				'type' => 'string',
				'field' => 'hidden',
			),

			'checkbox' => array(
				'type' => 'boolean',
				'field' => 'checkbox',
			),

			'number' => array(
				'type' => 'integer',
				'field' => 'number',
				'min' => 0,
				'max' => 100,
				'step' => 10,
			),

			'range' => array(
				'type' => 'integer',
				'field' => 'range',
				'min' => 0,
				'max' => 100,
				'step' => 10,
			),

			'select' => array(
				'type' => 'string',
				'field' => 'select',
				'options' => array('Red', 'Green', 'Blue', 'Yellow'),
			),

			'multiselect' => array(
				'type' => 'array',
				'field' => 'multiselect',
				'options' => array('Red', 'Green', 'Blue', 'Yellow'),
			),

			'radio' => array(
				'type' => 'string',
				'field' => 'radio',
				'options' => array('Red', 'Green', 'Blue', 'Yellow'),
			),

			'date' => array(
				'type' => 'string',
				'field' => 'date',
			),

			'time' => array(
				'type' => 'string',
				'field' => 'time',
			),

			'datetime-local' => array(
				'type' => 'string',
				'field' => 'datetime-local',
			),
		);
	}

	public function render(): void
	{
		settings_fields(static::optionKey());
		do_settings_sections(static::optionKey());
	}

}
