<?php

namespace WPPluginBoilerplate\MetaBox\Boxes;

use WPPluginBoilerplate\Core\Support\PostTypes;
use WPPluginBoilerplate\MetaBox\Abstracts\AbstractMetaBox;

class SideMetaBox extends AbstractMetaBox
{
	public function id(): string
	{
		return 'side_meta';
	}

	public function title(): string
	{
		return 'Side Example';
	}

	public function postTypes(): array
	{
		return PostTypes::allPublic();
	}

	public function context(): string
	{
		return 'side';
	}

	public function priority(): string
	{
		return 'high';
	}

	public function templates(): array
	{
		return [];
	}

	public function fields(): array
	{
		return array(

			'checkbox' => [
				'type' => 'boolean',
				'field' => 'checkbox',
				'class' => 'width',
				'label' => '',
				'box_label' => 'Exclude this post from manifest output.',
			],

			'text' => [
				'type' => 'string',
				'field' => 'text',
				'label' => 'Text',
				'conditions' => [
					[
						'field' => 'checkbox',
						'operator' => '==',
						'value' => '1'
					]
				],
			],

			'textarea' => [
				'type' => 'string',
				'field' => 'textarea',
				'rows' => 8,
				'class' => 'width-10',
				'label' => 'Textarea',
			],

		);
	}

}
