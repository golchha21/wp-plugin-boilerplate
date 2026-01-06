# Plugin Boilerplate – WordPress Settings Framework

A lightweight, OOP-based WordPress admin settings framework built for **real plugins**.

Designed to be **copied, renamed, and adapted**, this framework removes the repetitive and error-prone parts of building
WordPress settings while staying close to core APIs.

> This is not a generator and not a dependency.  
> It’s a foundation you own inside your plugin.

---

## What This Solves

- No more serialized option arrays
- No data loss when switching tabs
- Clean separation between settings and tools
- WordPress-aware date, time, and media handling
- Safe lifecycle cleanup on uninstall
- Ability to place fields on **core WordPress pages** and **user profiles**

---

## Key Features

- One option per field (prefix-based storage)
- Tab-based settings UI
- Default value support (applied only when an option does not exist)
- WordPress-native Date, Time, and DateTime fields
- Media fields with previews, ordering, and MIME control
- Versioned JSON export / import
- Nonce-protected Tools tab
- Safe activate / deactivate / uninstall handling
- Optional field placement on:
    - Core WordPress settings pages
    - User profile screens

---

## Fields

All fields:

- Save to their own prefixed option
- Support optional `default` values
- Own their sanitization logic
- Support an optional `description`
- Can belong to **exactly one context** (plugin page, core page, or profile)

### Available field types

#### Text-based

- **Text**
- **Textarea**
- **Email**
- **RichText** (WordPress WYSIWYG editor)

#### Choice-based

- **Select**
- **Radio**
- **Checkbox** (inline label + description)
- **MultiCheckbox**
- **MultiSelect** (searchable via Select2)

#### Date & Time (WordPress-aware)

- **Date** (stored as `YYYY-MM-DD`)
- **Time** (stored as `HH:MM`)
- **DateTime** (stored as Unix timestamp, rendered using WordPress date/time formats and timezone)

#### Media

- **Media**
    - Stores attachment IDs only
    - Single or multiple selection
    - Drag-to-reorder (multiple only)
    - Per-item remove buttons
    - Image thumbnails / file name previews
    - Media type restriction (image by default)
      ``

#### Utility

- **Number** (with min/max hints)
- **RawHtml** (custom UI blocks, tools, diagnostics)

**See [README-FIELDS.md](README-FIELDS.md) for full field examples.**

---

## Core Settings Page Support (v1.3)

This framework allows fields to be attached to **selected WordPress core settings pages** using the Settings API.

### Supported core pages
- **General**
- **Writing**
- **Reading**
- **Discussion**
- **Media**
- **User Profile (`profile.php`)**

Fields attached to these pages:
- Render natively using the Settings API
- Save automatically using WordPress core handling
- Respect existing sections and page structure

### Not supported in v1.3
- **Permalink**
- **Privacy**

These pages are **not fully compatible with the WordPress Settings API** and require custom rendering and save logic.

Attempting to attach fields to these pages will throw a logic exception and will not render.

> Permalink support is intentionally deferred and planned for a future release.

**See [README-FIELDS.md](README-FIELDS.md) for full field examples.**

---

## Getting Started

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

---

### 6. Activate the plugin

After renaming and adjusting:

- Activate the plugin from WordPress Admin
- Settings appear under the configured menu
- Options are created lazily when users save fields

---

## Choices Helper

The `Choices` helper provides WordPress-native datasets in a consistent format:

- Post types (attachments excluded by default)
- Taxonomies
- User roles
- Users

This keeps WordPress queries **out of field classes** and makes fields fully reusable.

---

## Select2 Assets (Required)

The framework uses **Select2** for the searchable `MultiSelect` field.

WordPress does not reliably expose Select2 on all admin pages, so this framework ships with its **own local copy**.

You must keep the following files in place:

```text
assets/vendor/select2/select2.min.js
assets/vendor/select2/select2.min.css
```

These are enqueued automatically by `Bootstrap.php` **only on the plugin settings page**.

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
