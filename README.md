# Plugin Boilerplate – Settings Framework

A reusable, OOP-based WordPress admin settings framework designed for real plugins:

- Independent options per field (no serialized arrays)
- Tab-based settings UI
- Per-tab Settings API registration (no data loss)
- Safe lifecycle cleanup (activate / deactivate / uninstall)
- Prefix-based export / import
- Tools tab rendered outside the Settings API

This is not a boilerplate generator.  
It’s a **foundation** you copy, rename, and adapt to build real plugins.

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
       │   └── RawHtml.php
       └── Helpers/
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

Update the plugin header inside the file accordingly.

---

### 4. Update namespaces and class names

Search and replace:

```text
PluginBoilerplate
plugin_boilerplate
```

with your plugin’s PHP namespace, for example:

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

This framework stores **one option per field**, all prefixed.

You **must** change the option prefix to match your plugin.

In the main plugin file:

```php
    define('MY_AWESOME_PLUGIN_VERSION', '1.0.0');
    const OPTION_PREFIX = 'my_awesome_plugin_';
    const IS_OPTIONS_PAGE = false;
```

In your settings bootstrap:

```php
    $page = new SettingsPage([
        'option_prefix' => OPTION_PREFIX,       // ← required
        'menu_slug'     => 'my-awesome-plugin',
        'menu_title'    => 'My Awesome Plugin',
        'page_title'    => 'My Awesome Plugin Settings',
        'capability'    => 'manage_options'
    ]);
```

And in `Lifecycle.php`:

```php
    const OPTION_PREFIX = 'my_awesome_plugin_';
```

> ⚠️ The prefix **must match everywhere**:
> - SettingsPage
> - Field option names
> - Lifecycle cleanup
> - Export / Import

---

### 6. Activate the plugin

After renaming and adjusting:

- Activate the plugin from WordPress Admin
- Settings will appear under the configured menu
- Options are created lazily when users save fields

---

## Fields

Each field:
- Saves to its own option
- Uses the shared prefix
- Owns its own sanitization
- Is registered only when its tab is active

---

## Tools Tab

The Tools tab:
- Is rendered **outside `<form>`**
- Does **not** use the Settings API
- Is intended for utilities only (export, import, reset, diagnostics)

---

## Lifecycle Management

### Activation
- Runtime setup only (cron, rewrites if needed)

### Deactivation
- Runtime cleanup only
- **Never deletes user settings**

### Uninstall
- Deletes **all options matching the prefix**
- Clears plugin transients
- Removes cron jobs
- Leaves no database residue

---

## License

GPL v3 or later.
