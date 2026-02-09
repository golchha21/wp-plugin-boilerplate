# WP Plugin Boilerplate

![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/github/v/tag/golchha21/wp-plugin-boilerplate)
![Downloads](https://img.shields.io/github/downloads/golchha21/wp-plugin-boilerplate/total)
![License](https://img.shields.io/github/license/golchha21/wp-plugin-boilerplate)

An opinionated, OOP-first WordPress plugin boilerplate focused on **long-term stability**, **explicit architecture**, and **predictable behavior**.

This boilerplate is designed for plugins that are expected to live for years, evolve safely, and be maintained by more than one developer.

---

## Core Principles

- Single entry point for orchestration
- Centralized hook registration via a Loader
- Clear separation between Admin, Settings, and Public runtime
- Explicit capability and scope rules
- Deterministic lifecycle behavior

---

## Folder Responsibilities

| Directory        | Responsibility |
|------------------|----------------|
| `src/Admin`      | Admin UI, menus, admin actions |
| `src/Settings`   | Settings tabs, defaults, scope, persistence |
| `src/Public`     | Frontend and runtime behavior |
| `src/Support`    | Shared infrastructure |
| `src/Lifecycle`  | Activation and deactivation |
| `assets`         | CSS, JS, static assets |
| `vendor`         | Bundled dependencies (shipped with the plugin) |

The `vendor/` directory is part of the distributed plugin and must not be removed.
It exists to support internal architecture and requires no action from plugin authors.

---

## Dependencies & Setup

This boilerplate is distributed as a **fully self-contained WordPress plugin**.

All required dependencies are already included in the repository.
You do **not** need to run Composer, install build tools, or perform any setup beyond installing and activating the plugin.

If you are extending the boilerplate to build a plugin, you can start writing code immediately inside the `src/` directory.

If the plugin is not working after cloning the repository, ensure the `vendor/` directory is present. If it is missing, reinstall the plugin from a clean copy.

---

## Settings Architecture

Settings are owned directly by individual tabs.

Each settings tab is responsible for:
- its option key
- default values
- sanitization
- storage scope (site or network)
- capability enforcement

There is no separate schema layer.

Settings must be accessed exclusively through the `SettingsRepository`.
Direct calls to `get_option()` or `get_site_option()` are considered architectural violations.

---

## Mental Model

- Classes define behavior
- Tabs define configuration boundaries
- Admin configures behavior
- Public executes behavior
- Settings connect admin and runtime
- Uninstall runs outside the plugin context and must remain procedural

---

## Who This Is For

This boilerplate is a good fit if:

- your plugin has frontend behavior
- your plugin must survive renames and refactors
- settings are business-critical
- multisite support matters
- long-term maintainability matters

If you are building a quick, disposable plugin, this may be more structure than you need.

---

## v1.0 Stability Guarantees

Starting with v1.0.0, this boilerplate guarantees:

- Public behavior is always registered unconditionally
- Admin configuration flows cleanly into runtime behavior
- Plugin lifecycle behavior is predictable and safe
- Uninstall cleans up all plugin-owned data
- Renaming the plugin does not break behavior
- The plugin is distributed as a self-contained package with all required dependencies bundled

Breaking these guarantees requires a major version bump.

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
