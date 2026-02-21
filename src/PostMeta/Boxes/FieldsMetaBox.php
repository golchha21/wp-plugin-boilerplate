<?php

namespace WPPluginBoilerplate\PostMeta\Boxes;

use WPPluginBoilerplate\PostMeta\Contracts\MetaBoxContract;
use WPPluginBoilerplate\PostMeta\Tabs\CoreFieldsTab;
use WPPluginBoilerplate\PostMeta\Tabs\EnhancedFieldsTab;
use WPPluginBoilerplate\PostMeta\Tabs\FeatureFieldsTab;

class FieldsMetaBox implements MetaBoxContract
{
	public function id(): string
	{
		return 'field_meta';
	}

	public function title(): string
	{
		return 'Fields Example';
	}

	public function postTypes(): array
	{
		return ['post'];
	}

	public function context(): string
	{
		return 'normal';
	}

	public function priority(): string
	{
		return 'default';
	}

	public function capability(): string
	{
		return 'edit_posts';
	}

	public function fields(): array
	{
		return array(
			'text' => array(
				'type' => 'string',
				'field' => 'text',
				'label' => 'Text',
			),

			'textarea' => array(
				'type' => 'string',
				'field' => 'textarea',
				'rows' => 8,
				'class' => 'width-10',
				'label' => 'Textarea',
			),

			'email' => array(
				'type' => 'string',
				'field' => 'email',
				'label' => 'Email',
			),

			'url' => array(
				'type' => 'string',
				'field' => 'url',
				'label' => 'URL',
			),

			'password' => array(
				'type' => 'string',
				'field' => 'password',
				'label' => 'Password',
			),

			'hidden' => array(
				'type' => 'string',
				'field' => 'hidden',
				'label' => 'Hidden',
			),

			'checkbox' => array(
				'type' => 'boolean',
				'field' => 'checkbox',
				'label' => 'Checkbox',
			),

			'number' => array(
				'type' => 'integer',
				'field' => 'number',
				'min' => 0,
				'max' => 100,
				'step' => 10,
				'label' => 'Number',
			),

			'range' => array(
				'type' => 'integer',
				'field' => 'range',
				'min' => 0,
				'max' => 100,
				'step' => 10,
				'label' => 'Range',
			),

			'select' => array(
				'type' => 'string',
				'field' => 'select',
				'options' => array('Red', 'Green', 'Blue', 'Yellow'),
				'label' => 'Select',
			),

			'multiselect' => array(
				'type' => 'array',
				'field' => 'multiselect',
				'options' => array('Red', 'Green', 'Blue', 'Yellow'),
				'label' => 'Multi-Select',
			),

			'radio' => array(
				'type' => 'string',
				'field' => 'radio',
				'options' => array('Red', 'Green', 'Blue', 'Yellow'),
				'label' => 'Radio',
			),

			'date' => array(
				'type' => 'string',
				'field' => 'date',
				'label' => 'Date',
			),

			'time' => array(
				'type' => 'string',
				'field' => 'time',
				'label' => 'Time',
			),

			'datetime-local' => array(
				'type' => 'string',
				'field' => 'datetime-local',
				'label' => 'Date-Time',
			),
		);
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
