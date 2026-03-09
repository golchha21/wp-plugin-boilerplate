# Fields Reference

This document defines the complete field definition structure supported
by the WP Plugin Boilerplate as of v1.3.

Fields are owner-scoped (tab or MetaBox), explicit, and deterministic.
There is no implicit behavior and no hidden schema magic.

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

## Conditional Fields (v1.6+)

Fields may define a `conditions` key to control admin visibility.

Conditional logic affects rendering only.\
Storage format remains unchanged.

### Default Behavior (AND)

Multiple conditions default to AND logic.

``` php
'conditions' => [
    [
        'field' => 'enable_feature',
        'operator' => '==',
        'value' => '1',
    ],
    [
        'field' => 'mode',
        'operator' => '!=',
        'value' => 'basic',
    ],
],
```

### OR Logic

Explicit OR relations are supported.

``` php
'conditions' => [
    'relation' => 'OR',
    'conditions' => [
        [
            'field' => 'enable_feature',
            'operator' => '==',
            'value' => '1',
        ],
        [
            'field' => 'mode',
            'operator' => 'in',
            'value' => ['advanced', 'pro'],
        ],
    ],
],
```

### Supported Operators

-   `==`
-   `!=`
-   `>`
-   `<`
-   `>=`
-   `<=`
-   `in`
-   `not_in`
-   `empty`
-   `not_empty`

### Scope Support

Conditional evaluation works consistently in:

-   Settings
-   MetaBoxes
-   Repeater rows

Nested grouped conditions are intentionally not supported.

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

Notes:

-   Powered by `wp_editor()`
-   Editor IDs are sanitized internally
-   Not supported inside `repeater` fields (due to wp_editor lifecycle
    constraints)

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

### Option Normalization (v1.3+)

If options are defined using a numeric array:

``` php
'options' => ['Red', 'Green']
```

They are automatically normalized internally to:

``` php
[
  'Red' => 'Red',
  'Green' => 'Green',
]
```

This ensures semantic values are stored instead of numeric indexes.

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
-   Drag sorting with order persistence
-   Duplicate support
-   Min / max enforcement
-   Independent row sanitization
-   Template-based rendering

Repeaters always store ordered arrays.

### Repeater Storage Guarantees (v1.3+)

-   Template placeholder rows (`__index__`) are never saved.
-   Completely empty rows are removed automatically.
-   Rows are reindexed numerically before persistence.
-   Nested data structure is deterministic and stable.

------------------------------------------------------------------------

## Hook Integration

Fields are typically registered during the `admin_init` lifecycle.

Modules may declare this using the declarative hook system.

Example:

```php
    public function hooks(): array
    {
        return [
            'action' => [
                ['admin_init', 'boot'],
            ],
        ];
    }
```

The Loader ensures these hooks are registered consistently across modules.

------------------------------------------------------------------------

## Guarantees

-   `type` controls data safety, not UI
-   `field` controls rendering, not storage
-   Conditions affect admin visibility only
-   Missing values always fall back to defaults
-   Unknown keys are ignored safely
-   Storage format is stable and deterministic

------------------------------------------------------------------------

## MetaBox Field Behavior (v1.5+)

MetaBox fields are automatically namespaced using:

*{PREFIX}{BOX_ID}*{FIELD_KEY}

Field definitions remain identical to Settings fields.

Rendering injects the field type as a CSS class on the wrapper:

.wppb-meta-field.{field_type}

This allows layout targeting without affecting storage.

------------------------------------------------------------------------

## Final Rule

If a field definition is unclear, make it explicit. Explicit
configuration always wins over convenience.
