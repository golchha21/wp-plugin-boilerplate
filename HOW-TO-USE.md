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

Choice fields automatically normalize numeric option arrays into
semantic key/value pairs.

``` php
'options' => ['Red', 'Green']
```

The stored value will be 'Red', not 0.

------------------------------------------------------------------------

### Using Conditional Fields (v1.6+)

Fields may define a `conditions` key to control admin visibility.

``` php
'subtitle' => [
    'type'    => 'string',
    'field'   => 'text',
    'default' => '',
    'conditions' => [
        [
            'field'    => 'enable_subtitle',
            'operator' => '==',
            'value'    => '1',
        ],
    ],
],
```

Multiple conditions default to AND logic.

To use OR:

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

Conditions affect rendering only.\
They do not alter storage structure.

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

### Repeater Save Behavior (v1.3+)

Repeater fields are sanitized before persistence.

This guarantees:

-   Template placeholder rows are never saved
-   Completely empty rows are removed
-   Rows are stored as clean ordered arrays

No additional save handling is required.

Note: The `editor` field type is not supported inside repeaters.

------------------------------------------------------------------------

## Step 6: Using MetaBoxes (v1.3+)

MetaBoxes use the same field engine as Settings.

Each MetaBox:

-   Must have a unique lowercase ID
-   Can define post types
-   Can optionally restrict rendering to specific templates
-   May contain tabs

Meta keys are automatically namespaced as:

*{PREFIX}{BOX_ID}*{FIELD_KEY}

Do not manually construct meta keys.

------------------------------------------------------------------------

## Step 7: Using Media Fields

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

### Duplicate Protection (v1.5.1+)

In multiple mode, the same attachment cannot be selected more than once.

Duplicate attempts trigger a WordPress `notice-warning`.

### File Type Validation

If MIME restrictions are configured:

-   Invalid selections trigger a `notice-error`
-   Notices use WordPress core admin styling
-   Multiple notices may stack per field

These safeguards affect admin UX only.\
Storage format remains unchanged.

------------------------------------------------------------------------

## Step 8: Accessing Stored Settings

Settings are stored as arrays using the `SettingsRepository`.

You can retrieve or modify either the full option array or a single key,
depending on your use case.

### Full Option Access

``` php
use WPPluginBoilerplate\Settings\SettingsRepository;

$settings = SettingsRepository::get('my_plugin_settings');
```

``` php
SettingsRepository::update('my_plugin_settings', [
    'enable_feature' => true,
    'api_key'        => '123456',
]);
```

``` php
SettingsRepository::delete('my_plugin_settings');
```

Network scope example:

``` php
SettingsRepository::get('my_plugin_settings', 'network');
```

### Granular Key Access

``` php
$enabled = SettingsRepository::getValue(
    'my_plugin_settings',
    'enable_feature',
    false
);
```

``` php
SettingsRepository::setValue(
    'my_plugin_settings',
    'enable_feature',
    true
);
```

``` php
SettingsRepository::deleteValue(
    'my_plugin_settings',
    'enable_feature'
);
```

Both full and granular access methods are multisite-aware and respect
the `site` and `network` scope parameter.

------------------------------------------------------------------------

## Step 9: Accessing MetaBox Data (v1.5+)

MetaBox fields must be accessed using `MetaBoxRepository`.

Never use `get_post_meta()` directly for MetaBox fields. Never manually
build prefixed keys.

### Get Value

``` php
use WPPluginBoilerplate\MetaBox\MetaBoxRepository;

$value = MetaBoxRepository::get(
    $postId,
    'customer_stories',
    'title',
    ''
);
```

### Update Value

``` php
MetaBoxRepository::update(
    $postId,
    'customer_stories',
    'title',
    'New Title'
);
```

### Delete Value

``` php
MetaBoxRepository::delete(
    $postId,
    'customer_stories',
    'title'
);
```

Repository guarantees deterministic namespacing and storage integrity.

------------------------------------------------------------------------

## Step 10: Add Runtime Behavior

Runtime behavior lives in `Frontend` (or equivalent).

Public behavior must always be registered unconditionally by the Plugin
orchestrator.

Do **not** gate runtime wiring behind `is_admin()`. Resolve context
inside callbacks instead.

------------------------------------------------------------------------

## Step 10.5: Registering Hooks

Hooks are registered through the Loader.

Feature classes should never call `add_action()` or `add_filter()` directly.

Hooks can be declared using a `hooks()` method.

Example:

```php
    public function hooks(): array
    {
        return [
            'action' => [
                ['admin_init', 'boot'],
                ['admin_menu', 'register_menus'],
            ],
        ];
    }
```

The Loader reads this structure and registers hooks automatically.

For dynamic hooks or external handlers, use explicit registration:

```php
    public function register(Loader $loader): void
    {
        $loader->action("admin_post_export", [new ExportSettings(), 'handle']);
    }
```

Declarative hooks keep feature classes easier to read as plugins grow.

------------------------------------------------------------------------

## Step 11: Admin Configuration Rules

Admin is responsible only for:

-   Rendering UI
-   Validating input
-   Triggering admin-only actions

Admin must never contain runtime logic.

------------------------------------------------------------------------

## Step 12: Import, Export, and Reset

-   Import and Export are global operations
-   Reset is tab-scoped

Capability enforcement must match scope.

------------------------------------------------------------------------

## Step 13: Lifecycle

-   Activation must be side-effect free
-   Deactivation pauses behavior but keeps data
-   Uninstall deletes all plugin-owned data

------------------------------------------------------------------------

## Final Rule

If something feels convenient but implicit, it probably does not belong.
Choose explicit behavior over shortcuts.
