<?php

namespace WPPluginBoilerplate\Support\Settings\Schemas;

class GeneralSchema
{
    public static function definition(): array
    {
        return [
            'example_text' => [
                'type'     => 'string',
                'default'  => '',
                'sanitize' => 'sanitize_text_field',
                'label'    => 'Example Text',
            ],
            'enable_feature' => [
                'type'     => 'boolean',
                'default'  => false,
                'sanitize' => 'rest_sanitize_boolean',
                'label'    => 'Enable Feature',
            ],
        ];
    }

    public static function defaults(): array
    {
        return array_map(
            fn ($field) => $field['default'],
            self::definition()
        );
    }
}
