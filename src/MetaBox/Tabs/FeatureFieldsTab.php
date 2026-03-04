<?php

namespace WPPluginBoilerplate\MetaBox\Tabs;

use WPPluginBoilerplate\MetaBox\Contracts\MetaBoxTabContract;

class FeatureFieldsTab implements MetaBoxTabContract
{
	public function id(): string
	{
		return 'feature_fields';
	}

	public function label(): string
	{
		return 'Feature Fields';
	}

	public function fields(): array
	{
		return array(
			'features' => [
				'label' => 'Features',
				'field' => 'repeater',
				'max'   => 5,
				'class' => 'width-10',
				'fields' => [
					'enable_subtitle' => array(
						'type' => 'boolean',
						'field' => 'checkbox',
					),
					'title' => [
						'field' => 'text',
						'title' => 'Title',
						'class' => 'width-10',
					],
					'subtitle' => [
						'field' => 'text',
						'label' => 'Sub-Title',
						'class' => 'width-6',
						'conditions' => [
							[
								'field' => 'enable_subtitle',
								'operator' => '==',
								'value' => '1'
							]
						],
					],
				],
			],
			'textareaR' => [
				'field' => 'repeater',
				'label' => 'Textarea',
				'fields' => [
					'image' => array(
						'type' => 'integer',
						'field' => 'image',
						'multiple' => false,
						'class' => 'width-10',
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
}
