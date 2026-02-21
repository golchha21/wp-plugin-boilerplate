# WP Plugin Boilerplate

![PHP](https://img.shields.io/badge/PHP-%3E%3D7.4-777BB4?logo=php&logoColor=white)
![Version](https://img.shields.io/github/v/tag/golchha21/wp-plugin-boilerplate)
![Downloads](https://img.shields.io/github/downloads/golchha21/wp-plugin-boilerplate/total)

An opinionated WordPress plugin boilerplate for building long-lived
plugins with explicit structure and predictable lifecycle behavior.

This project does not provide user-facing features and does not try to
replace WordPress conventions, Git workflows, or existing development
practices.

It exists to provide a constrained starting point for plugins that are
expected to grow over time --- where admin configuration, settings,
frontend behavior, and lifecycle concerns tend to blur and accumulate
accidental complexity.

------------------------------------------------------------------------

## Core Principles

- Clear separation between admin, settings, and public runtime
- Settings treated as a domain boundary
- Predictable lifecycle behavior
- Minimal magic and no hidden side effects
- Constraints designed for long-term maintainability

------------------------------------------------------------------------

## Settings System (v1.3+)

The settings layer is now a structured, extensible system.

### Field Architecture

- Schema-driven `FieldDefinition`
- Centralized `FieldRenderer`
- Deterministic sanitization per field
- Nested option handling
- Extensible field pattern

### MetaBox Support (v1.3+)

The field engine now powers both Settings and MetaBox modules.

- Shared rendering layer
- Shared sanitization pipeline
- Field-type-aware save logic
- Stable nested meta structure
- Repeater fully supported in MetaBox context

Meta and Settings use the same core field abstraction.

### Supported Fields

- text
- textarea
- email
- url
- password
- hidden
- date
- time
- datetime-local
- number
- checkbox
- radio
- select
- color
- editor (wp_editor powered)
- media (single & multiple)
- repeater (nested structured fields)

------------------------------------------------------------------------

## Repeater Field

The repeater allows structured, sortable nested data.

### Features

- Collapsible rows (collapsed by default)
- Drag & drop sorting with order persistence
- Duplicate row support
- Min / max limits
- Independent row sanitization
- Template-based rendering
- Dashicon controls

Repeaters always store ordered arrays and never leak template markup into the runtime DOM.

### Storage Guarantees (v1.3+)

- Template placeholder rows (`__index__`) are never persisted
- Completely empty rows are removed automatically
- Rows are reindexed numerically before persistence
- Nested data structure remains deterministic and stable

------------------------------------------------------------------------

## Media Field

Stores attachment IDs only.

Supports:

- Single selection (integer)
- Multiple selection (ordered array)
- Drag sorting (multiple mode)
- Per-item removal (multiple mode)
- MIME restriction support
- Square preview layout

Behavior adapts automatically based on `multiple: true`.

------------------------------------------------------------------------

## Admin UI System

The admin interface is:

- Fully scoped under `.wppb-admin`
- Built on a 12-column CSS Grid layout
- Powered by semantic design tokens
- Safe from wp-admin style conflicts

Example:

``` php
'class' => 'width-6',
```

Available widths:

- width-1 → width-12
- Default: width (full width)

------------------------------------------------------------------------

## Folder Responsibilities

Directory       Responsibility
  --------------- -----------------------------
src/Admin       Admin UI & menus
src/Settings    Settings tabs & persistence
src/Public      Frontend/runtime behavior
src/Lifecycle   Activation & deactivation
src/Support     Shared infrastructure
assets          CSS, JS, static assets
vendor          Bundled dependencies

Each directory represents a deliberate boundary.

------------------------------------------------------------------------

## Stability Guarantees

Starting with v1.0:

- Public behavior is registered unconditionally
- Admin configuration flows cleanly into runtime
- Lifecycle behavior is predictable
- Uninstall cleans up plugin-owned data
- Plugin renaming does not break behavior
- Distributed as a self-contained package

Breaking these guarantees requires a major version bump.

------------------------------------------------------------------------

## Versioning

Semantic Versioning is followed:

- Patch → internal fixes
- Minor → new field types or UI features
- Major → structural or storage changes

------------------------------------------------------------------------

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on recent changes.

## Security

If you discover any security-related issues, please email **vardhans@ulhas.net** instead of using the issue tracker.

## Credits

- [Ulhas Vardhan Golchha](https://github.com/golchha21) — *Initial work*

See also the list of [contributors](https://github.com/golchha21/wp-plugin-boilerplate/graphs/contributors).

------------------------------------------------------------------------

## License

This project is licensed under the **GNU General Public License v2.0 or later (GPL-2.0-or-later)**.

WordPress is licensed under the GPL, and any plugin that runs within WordPress
and uses its APIs is required to be GPL-compatible.

You are free to use, modify, and distribute this software under the terms
of the GPL. See the [LICENSE](LICENSE) file for details.

------------------------------------------------------------------------

If this boilerplate has been useful to you, you can support its development here:  
[Buy me a coffee](https://www.buymeacoffee.com/golchha21)
