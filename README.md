# WP Plugin Boilerplate

![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/github/v/tag/golchha21/wp-plugin-boilerplate)
![Downloads](https://img.shields.io/github/downloads/golchha21/wp-plugin-boilerplate/total)
![License](https://img.shields.io/github/license/golchha21/wp-plugin-boilerplate)


An opinionated, OOP-first WordPress plugin boilerplate for building long-lived, maintainable plugins.

This repository is a **foundation**, not a demo plugin.

---

## Core Principles

### 1. One entry point

- `wp-plugin-boilerplate.php` is the only file WordPress directly interacts with
- No logic lives there beyond wiring

### 2. Centralized hooks only

- `add_action` and `add_filter` are allowed **only** inside `Loader`
- All hooks must be registered via `$loader->action()` or `$loader->filter()`

### 3. No WordPress globals in business logic

- No `$_POST`, `$_GET`, or `$_REQUEST` outside controlled entry points
- No scattered `global $wpdb`

### 4. Extend by adding classes, not editing core

- New behavior = new class
- Core orchestration classes should rarely change

### 5. PSR-4 is non-negotiable

- File name = class name
- Namespace = folder structure
- Case-sensitive, always

### 6. Tabs are first-class concepts

- Admin screens are composed of tabs
- Tabs may be:
    - **settings tabs** (persist data)
    - **presentation-only tabs** (About, Help, Docs)
- Not all tabs save data, by design

### 7. Settings are schema-driven and isolated

- Each settings tab owns its own schema
- Each settings tab persists data under its own option key
- Defaults are defined centrally in schemas
- Raw `get_option()` usage outside the settings layer is forbidden

### 8. Multisite is explicit, never implicit

- Multisite is supported, but never assumed
- Each settings schema explicitly declares its storage scope:
    - `site` → per-site options
    - `network` → network-wide options
- Network-scoped settings are visible and editable only in Network Admin
- There is no automatic switching between `option` and `site_option`

---

## Folder Responsibilities

| Folder        | Responsibility                    |
|---------------|-----------------------------------|
| `src/`        | All PHP source code               |
| `src/Admin`   | Admin-only behavior               |
| `src/Public`  | Frontend behavior                 |
| `src/Support` | Shared helpers and infrastructure |
| `assets/`     | JS / CSS                          |
| `languages/`  | Translations                      |

---

## Documentation

This boilerplate is intentionally opinionated and schema-driven.

- **[HOW-TO-USE.md](HOW-TO-USE.md)**  
  Practical guidance on creating a real plugin using this boilerplate.

- **[FIELDS.md](FIELDS.md)**  
  Complete reference of all supported field types, their syntax, and guarantees.

- **[ADVANCED-TOPICS.md](ADVANCED-TOPICS.md)**
  Advanced internal topics such as multisite behavior, schema migrations, and import/export are documented separately to keep this README focused.

---

## Settings Architecture

- `TabContract` defines navigation and rendering
- `SchemaContract` defines persistence, defaults, and sanitization
- `SettingsTabContract` explicitly marks tabs that own settings

Rules:

- Tabs render **content only**
- Tabs never render `<form>` tags or action buttons
- UI chrome (forms, buttons, notices) is owned by the renderer
- Presentation-only tabs must never touch settings storage

---

## What NOT to do

- ❌ Put logic in the entry file
- ❌ Call `add_action` or `add_filter` directly in feature classes
- ❌ Access `get_option()` / `get_site_option()` outside the settings repository
- ❌ Run migrations on plugin activation
- ❌ Introduce silent side effects in constructors

---

## Mental Model

- Entry file = handshake
- Plugin = orchestration
- Loader = wiring
- Classes = behavior

If you break these rules, this stops being a boilerplate.

---

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on recent changes.

## Security

If you discover any security-related issues, please email **vardhans@ulhas.net** instead of using the issue tracker.

## Credits

- [Ulhas Vardhan Golchha](https://github.com/golchha21) — *Initial work*

See also the list of [contributors](https://github.com/golchha21/wp-plugin-boilerplate/graphs/contributors).

---

## License

This project is licensed under the **GNU General Public License v2.0 or later (GPL-2.0-or-later)**.

WordPress is licensed under the GPL, and any plugin that runs within WordPress
and uses its APIs is required to be GPL-compatible.

You are free to use, modify, and distribute this software under the terms
of the GPL. See the [LICENSE](LICENSE) file for details.


---

If this boilerplate has been useful to you, you can support its development here:  
[Buy me a coffee](https://www.buymeacoffee.com/golchha21)
