<?php

namespace WPPluginBoilerplate\PostMeta\Tabs;

use WPPluginBoilerplate\PostMeta\Contracts\MetaBoxTabContract;

class EnhancedFieldsTab implements MetaBoxTabContract
{
	public function id(): string
	{
		return 'enhanced-fields';
	}

	public function label(): string
	{
		return 'Enhanced Fields';
	}

	public function fields(): array
	{
		return array(
			'media' => array(
				'type' => 'integer',
				'field' => 'media',
				'multiple' => true,
				'class' => 'width-10',
			),

			'file' => array(
				'type' => 'integer',
				'field' => 'file',
				'class' => 'width-10',
			),

			'image' => array(
				'type' => 'integer',
				'field' => 'image',
				'class' => 'width-10',
			),

			'document' => array(
				'type' => 'integer',
				'field' => 'document',
				'class' => 'width-10',
			),

			'audio' => array(
				'type' => 'integer',
				'field' => 'audio',
				'class' => 'width-10',
			),

			'video' => array(
				'type' => 'integer',
				'field' => 'video',
				'class' => 'width-10',
			),

			'archive' => array(
				'type' => 'integer',
				'field' => 'archive',
				'class' => 'width-10',
			),

			'color' => array(
				'type' => 'string',
				'field' => 'color',
				// hex color value.
				'class' => 'width-10',
			),

			'editor' => array(
				'type' => 'string',
				'field' => 'editor',
				'rows' => 8,
				'media_buttons' => true,
				'class' => 'width-10',
			),
		);
	}
}
