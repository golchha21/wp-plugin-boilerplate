<?php

namespace WPPluginBoilerplate\MetaBox\Boxes;

use WPPluginBoilerplate\MetaBox\Abstracts\AbstractMetaBox;

class FieldsMetaBox extends AbstractMetaBox
{
	public function id(): string
	{
		return 'field_meta';
	}

	public function title(): string
	{
		return 'Fields Example';
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
				'conditions' => [
					'relation' => 'OR',
					[
						'field' => 'checkbox',
						'operator' => '==',
						'value' => '1'
					],
					[
						'field' => 'url',
						'operator' => '!=',
						'value' => ''
					]
				],
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
				'class' => 'column',
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

}
