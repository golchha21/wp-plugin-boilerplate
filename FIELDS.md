# Fields Reference

This document defines the complete field definition structure supported
by the WP Plugin Boilerplate as of v1.6.3.

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

            'type'  => 'string', // string | integer | boolean | array
            'field' => 'text',

            'label'       => 'Example Field',
            'description' => 'Displayed below the field.',
            'default'     => '',

            'options' => [
                'option_1' => 'Option One',
                'option_2' => 'Option Two',
            ],

            'conditions' => [
                [
                    'field'    => 'another_field',
                    'operator' => '==',
                    'value'    => 'yes',
                ],
            ],

            'class' => 'width-6', // width-1 → width-12; default: width-4

            'sanitize' => function ($value) {
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

-   `width-1` → `width-12`
-   `width` — full width (default for editor, media, repeater)
-   Default for all other fields: `width-4`

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
'type'    => 'boolean',
'field'   => 'checkbox',
'default' => false,
```

#### number

``` php
'type'    => 'integer',
'field'   => 'number',
'min'     => 0,   // optional
'max'     => 100, // optional
'step'    => 1,   // optional
'default' => 0,
```

#### range

``` php
'type'    => 'integer',
'field'   => 'range',
'min'     => 0,
'max'     => 100,
'step'    => 10,
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

#### multiselect

``` php
'type'    => 'array',
'field'   => 'multiselect',
'default' => [],
'options' => [
    'key' => 'Label',
],
```

Stores an array of selected values.

#### radio

``` php
'type'    => 'string',
'field'   => 'radio',
'options' => [
    'key' => 'Label',
],
'class' => 'column', // optional: renders options vertically
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

### Media Fields

All media fields store attachment IDs only. Never store URLs or file paths.

#### media — any file type

``` php
'type'  => 'integer',
'field' => 'media',
'default' => 0,
```

#### image — images only

``` php
'type'  => 'integer',
'field' => 'image',
'default' => 0,
```

Preview renders as thumbnail. Library filtered to images.

#### file — any file type (alias for media)

``` php
'type'  => 'integer',
'field' => 'file',
'default' => 0,
```

#### document — pdf, doc, xls, etc.

``` php
'type'  => 'integer',
'field' => 'document',
'default' => 0,
```

#### audio

``` php
'type'  => 'integer',
'field' => 'audio',
'default' => 0,
```

#### video

``` php
'type'  => 'integer',
'field' => 'video',
'default' => 0,
```

#### archive — zip, tar, gz

``` php
'type'  => 'integer',
'field' => 'archive',
'default' => 0,
```

#### Multiple Mode (ordered gallery)

Any media field type supports `multiple: true`:

``` php
'type'     => 'array',
'field'    => 'media',   // or image, file, etc.
'default'  => [],
'multiple' => true,
```

Multiple mode supports:

-   Drag sorting with order persistence
-   Per-item removal
-   Duplicate prevention (triggers `notice-warning`)
-   Invalid MIME type rejection (triggers `notice-error`)

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
