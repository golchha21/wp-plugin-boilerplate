# How to Use

This guide walks through building a real plugin using the boilerplate.
It assumes familiarity with WordPress and PHP.

------------------------------------------------------------------------

## Step 1: Rename the Boilerplate

Start by making the boilerplate yours.

-   Rename the plugin directory
-   Rename the main plugin file
-   Update the namespace, prefix, and text domain by replacing all
    boilerplate identifiers: (`wp-plugin-boilerplate`,
    `WPPluginBoilerplate`, `WP Plugin Boilerplate`, `WPPB_`, `wppb_`,
    `WPPB-`, `wppb-`)
-   Regenerate the autoloader:

``` bash
composer dump-autoload
```

After this step, the plugin should activate cleanly under its new
identity.

------------------------------------------------------------------------

## Step 2: Define Settings Tabs

Settings are defined directly by tabs.

A settings tab owns:

-   Its option key
-   Default values
-   Sanitization rules
-   Storage scope (site or network)
-   Capability enforcement

There is no hidden schema abstraction layer.

Each tab returns a `fields()` array.

------------------------------------------------------------------------

## Step 3: Define Fields

Fields are defined declaratively.

Example:

``` php
public static function fields(): array
{
    return [
        'title' => [
            'type'    => 'string',
            'field'   => 'text',
            'default' => '',
            'class'   => 'width-6',
        ],
    ];
}
```

The `type` defines storage safety. The `field` defines rendering
behavior.

------------------------------------------------------------------------

## Step 4: Using the Grid Layout

The admin UI uses a 12-column CSS Grid layout.

``` php
'class' => 'width-4',
```

Available:

-   width-1 → width-12
-   width (default full width)

Layout is purely visual and does not affect storage.

------------------------------------------------------------------------

## Step 5: Using a Repeater

Repeaters allow structured, sortable nested fields.

``` php
'features' => [
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
],
```

Behavior:

-   Rows are collapsed by default
-   Rows are sortable
-   Duplicate is supported
-   Min and max are enforced
-   Each row is sanitized independently

Repeaters always store ordered arrays.

Note: The `editor` field type is not supported inside repeaters.

------------------------------------------------------------------------

## Step 6: Using Media Fields

### Single Media

``` php
'logo' => [
    'type'  => 'int',
    'field' => 'media',
    'default' => 0,
],
```

### Multiple Media

``` php
'gallery' => [
    'type'     => 'array',
    'field'    => 'media',
    'default'  => [],
    'multiple' => true,
],
```

Multiple mode:

-   Supports drag sorting
-   Stores ordered attachment IDs
-   Allows per-item removal
-   Uses square preview layout

Single mode disables drag behavior automatically.

------------------------------------------------------------------------

## Step 7: Add Runtime Behavior

Runtime behavior lives in `PublicPlugin` (or equivalent).

Public behavior must always be registered unconditionally by the Plugin
orchestrator.

Do **not** gate runtime wiring behind `is_admin()`. Resolve context
inside callbacks instead.

------------------------------------------------------------------------

## Step 8: Admin Configuration Rules

Admin is responsible only for:

-   Rendering UI
-   Validating input
-   Triggering admin-only actions

Admin must never contain runtime logic.

------------------------------------------------------------------------

## Step 9: Import, Export, and Reset

Not all actions have the same scope.

-   Import and Export are global operations
-   Reset is tab-scoped

Capability enforcement must match scope.

------------------------------------------------------------------------

## Step 10: Lifecycle

-   Activation must be side-effect free
-   Deactivation pauses behavior but keeps data
-   Uninstall deletes all plugin-owned data

------------------------------------------------------------------------

## Final Rule

If something feels convenient but implicit, it probably does not belong.
Choose explicit behavior over shortcuts.
