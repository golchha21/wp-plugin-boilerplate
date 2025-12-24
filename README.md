# Plugin Boilerplate – Settings Framework

A reusable, OOP-based WordPress admin settings framework designed for **real-world plugins**, not demos.

This framework gives you a solid foundation for building complex, maintainable plugin settings without fighting the WordPress Settings API.

**This is not a generator.**  
It’s a **copy–rename–adapt foundation** you use to build your own plugins.

---

## Key Features

- Independent options per field (no serialized option arrays)
- Prefix-based option naming for clean storage and easy cleanup
- Tab-based settings UI with safe per-tab saving
- Fully namespaced, PSR-12–compliant architecture
- Expandable field system with first-class sanitization
- WordPress-native data helpers (post types, taxonomies, roles, users)
- Production-grade Media field (IDs only, reorderable, removable)
- Safe lifecycle handling (activate / deactivate / uninstall)
- Tools tab rendered outside the Settings API

---

## Folder Structure

```text
wp-plugin-boilerplate/
├── PluginBoilerplate.php
├── uninstall.php
└── includes/
   ├── Autoloader.php
   ├── Bootstrap.php
   ├── Lifecycle.php
   └── Admin/
       ├── SettingsPage.php
       ├── Fields/
       │   ├── Field.php
       │   ├── Checkbox.php
       │   ├── Text.php
       │   ├── Textarea.php
       │   ├── Select.php
       │   ├── Number.php
       │   ├── Email.php
       │   ├── Media.php
       │   ├── MultiCheckbox.php
       │   ├── MultiSelect.php
       │   └── RawHtml.php
       └── Helpers/
           ├── Choices.php
           └── ExportImport.php
```

---

## Using This Framework for a New Plugin

This framework is intended to be **copied and renamed**, not installed directly.

### 1. Copy the directory

Copy the entire directory into your WordPress plugins folder:

```text
wp-content/plugins/wp-plugin-boilerplate
```

---

### 2. Rename the plugin directory

Rename:

```text
wp-plugin-boilerplate
```

to your actual plugin slug, for example:

```text
my-awesome-plugin
```

---

### 3. Rename the main plugin file

Rename:

```text
PluginBoilerplate.php
```

to:

```text
MyAwesomePlugin.php
```

Update the plugin header (name, description, author, version).

---

### 4. Update namespaces and class names

Search and replace:

```text
PluginBoilerplate
plugin_boilerplate
```

with your plugin’s namespace and prefix, for example:

```text
MyAwesomePlugin
my_awesome_plugin
```

Ensure consistency across:
- Namespaces
- Class names
- Autoloader mappings

---

### 5. Set your OPTION_PREFIX (critical)

This framework stores **one option per field**, all sharing a common prefix.

You **must** change the option prefix to match your plugin.

In the main plugin file:

```php
define('MY_AWESOME_PLUGIN_VERSION', '1.0.0');
const OPTION_PREFIX   = 'my_awesome_plugin_';
const IS_OPTIONS_PAGE = false;
```

In your settings bootstrap:

```php
$page = new SettingsPage([
    'option_prefix' => OPTION_PREFIX,
    'menu_slug'     => 'my-awesome-plugin',
    'menu_title'    => 'My Awesome Plugin',
    'page_title'    => 'My Awesome Plugin Settings',
    'capability'    => 'manage_options'
]);
```

In `Lifecycle.php`:

```php
const OPTION_PREFIX = 'my_awesome_plugin_';
```

---

### 6. Activate the plugin

After renaming and adjusting:

- Activate the plugin from WordPress Admin
- Settings appear under the configured menu
- Options are created lazily when users save fields

---

## Fields

Each field:
- Saves to its **own option**
- Uses the shared option prefix
- Owns its own sanitization logic
- Supports an optional `description`
- Is registered and rendered per tab

### Available field types

- Text
- Textarea
- Select
- Number (with min/max hints)
- Email
- Checkbox (with inline label + description)
- MultiCheckbox
- MultiSelect (searchable via Select2)
- Media (IDs only, sortable, removable)
- RawHtml (for custom UI blocks)

---

## Choices Helper

The `Choices` helper provides WordPress-native datasets in a consistent format:

- Post types (attachments excluded by default)
- Taxonomies
- User roles
- Users

This keeps WordPress queries **out of field classes** and makes fields fully reusable.

---

## Media Field

The Media field is production-ready:

- Stores **attachment IDs only**
- Supports single or multiple selection
- Drag-to-reorder (only when multiple is enabled)
- Per-item remove buttons
- Image thumbnails for images
- Filename previews for non-image media
- Media type restriction (image by default)

---

## Tools Tab

The Tools tab:
- Is rendered **outside `<form>`**
- Does **not** use the Settings API
- Does **not** show a Save button
- Is intended for utilities only:
    - export / import

---

## Lifecycle Management

### Activation
- Runtime setup only (cron jobs, rewrites if needed)
- Does **not** create or modify user settings

### Deactivation
- Runtime cleanup only
- Clears cron jobs and transients
- **Never deletes user options**

### Uninstall
- Deletes **all options matching the prefix**
- Clears plugin transients
- Removes cron jobs
- Leaves no database residue

---

## License

GPL v3 or later.
