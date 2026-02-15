# Fields Reference

This document defines the complete field definition structure supported
by the WP Plugin Boilerplate as of v1.1.

Fields are tab-owned, explicit, and deterministic. There is no implicit
behavior and no hidden schema magic.

------------------------------------------------------------------------

## General Rules

-   Fields are defined inside a settings tab
-   Each field must have a unique key
-   Defaults are mandatory
-   Storage format is predictable
-   Validation is enforced on save
-   Frontend reads must tolerate missing or empty values

------------------------------------------------------------------------

## Canonical Field Definition

This example shows all supported options. Most fields only require a
subset.

``` php
public static function fields(): array
{
    return [
        'example_field' => [

            'type' => 'string', // string | int | bool | array
            'field' => 'text',

            'label' => 'Example Field',
            'description' => 'Displayed below the field.',
            'default' => '',

            'placeholder' => 'Enter a value',
            'required' => false,

            'options' => [
                'option_1' => 'Option One',
                'option_2' => 'Option Two',
            ],

            'conditions' => [
                [
                    'field' => 'another_field',
                    'operator' => '==',
                    'value' => 'yes',
                ],
            ],

            'capability' => 'manage_options',
            'class' => 'width-6', // width-1 → width-12
            'readonly' => false,
            'disabled' => false,

            'sanitize_callback' => function ($value) {
                return sanitize_text_field($value);
            },
        ],
    ];
}
```

------------------------------------------------------------------------

## Layout Width System

Fields support a 12-column CSS Grid layout.

``` php
'class' => 'width-6',
```

Available:

-   width-1 → width-12
-   width (default full width)

------------------------------------------------------------------------

## Supported Field Types

### Text-Based Fields

#### text

``` php
'type'  => 'string',
'field' => 'text',
```

#### textarea

``` php
'type'  => 'string',
'field' => 'textarea',
'rows'  => 5, // optional
```

#### editor

``` php
'type'  => 'string',
'field' => 'editor',
'rows'  => 8,              // optional
'media_buttons' => false,  // optional
```

Notes: - Powered by wp_editor() - Editor IDs are sanitized internally -
Safe for use inside repeaters

------------------------------------------------------------------------

### Boolean & Numeric Fields

#### checkbox

``` php
'type'    => 'bool',
'field'   => 'checkbox',
'default' => false,
```

#### number

``` php
'type'    => 'int',
'field'   => 'number',
'default' => 0,
```

------------------------------------------------------------------------

### Choice Fields

#### select

``` php
'type'    => 'string',
'field'   => 'select',
'options' => [
    'key' => 'Label',
],
```

#### radio

``` php
'type'    => 'string',
'field'   => 'radio',
'options' => [
    'key' => 'Label',
],
```

------------------------------------------------------------------------

### Media Field

All media fields store attachment IDs only.

#### Single Mode

``` php
'type'  => 'int',
'field' => 'media',
'default' => 0,
```

#### Multiple Mode

``` php
'type'     => 'array',
'field'    => 'media',
'default'  => [],
'multiple' => true,
```

Multiple mode supports:

-   Drag sorting
-   Per-item removal
-   Order persistence

Single mode disables drag UI automatically.

------------------------------------------------------------------------

### Repeater Field

Repeaters store structured nested arrays.

``` php
'type'   => 'array',
'field'  => 'repeater',
'default' => [],
'min'    => 0,
'max'    => 5,
'fields' => [
    'title' => [
        'type'  => 'string',
        'field' => 'text',
        'default' => '',
    ],
    'image' => [
        'type'  => 'int',
        'field' => 'media',
        'default' => 0,
    ],
],
```

Features:

-   Collapsible rows (collapsed by default)
-   Drag sorting
-   Duplicate support
-   Min / max enforcement
-   Independent row sanitization
-   Template-based rendering

Repeaters always store ordered arrays.

------------------------------------------------------------------------

## Guarantees

-   `type` controls data safety, not UI
-   `field` controls rendering, not storage
-   Conditions affect admin visibility only
-   Missing values always fall back to defaults
-   Unknown keys are ignored safely
-   Storage format is stable and deterministic

------------------------------------------------------------------------

## Final Rule

If a field definition is unclear, make it explicit. Explicit
configuration always wins over convenience.
