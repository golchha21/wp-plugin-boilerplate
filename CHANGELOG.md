# Changelog

All notable changes to this project will be documented in this file.

This project follows a **foundation-first** release model:

- early versions establish architecture and contracts
- backward compatibility is taken seriously
- breaking changes are documented explicitly

---

## v1.6.3 – Documentation Improvements

### Documentation

Improves and expands the documentation to better explain the architecture and usage of the boilerplate.

### Added

- Complete **How-To-Use guide** describing the full plugin development workflow
- Comprehensive **Fields reference** documenting the full field definition structure
- **Contributing guide** explaining architectural rules and contribution constraints
- **Advanced topics documentation** covering internal architectural guarantees and system boundaries

### Improved

- README navigation and documentation structure
- Cross-referencing between documentation files
- Architectural explanations for settings, MetaBoxes, and the field engine

### Notes

- No runtime behavior changes
- No API changes
- No storage or compatibility changes

---

## v1.6.2 — Declarative Hooks & Module Wiring

### Added

- Declarative hook registration via `hooks()` method.
- Loader now supports automatic hook discovery from service and module classes.
- Admin modules can define hooks without manual `$loader->action()` calls.

### Improved

- Admin modules now register both:
    - `hooks()` (declarative hooks)
    - `register()` (manual wiring)
- Cleaner architecture for plugins with multiple modules.
- Loader safety improvements to prevent invalid callbacks.

### Architecture

Services and modules can now register hooks in two ways.

Declarative hooks example:

```php
    public function hooks(): array
    {
        return [
            'action' => [
                ['admin_menu', 'register_menu'],
            ],
        ];
    }
```

Manual wiring example:

```php
    public function register(Loader $loader): void
    {
        $loader->action('admin_post_reset', [new ResetSettings(), 'handle']);
    }
```

Both approaches can be used together.

---

## v1.6.1 – Documentation & Repository Improvements

### Added
- Built-in examples section in README with pointers to reference modules
- GitHub issue templates for bug reports and feature requests
- Pull request template for contributor guidance
- Security policy for responsible vulnerability disclosure

### Improved
- README structure and clarity
- Media field documentation updated to include duplicate prevention behavior
- Repeater documentation wording improved
- Folder responsibilities table formatting

### Internal
- Minor documentation alignment with conditional field engine behavior
- Repository structure improvements for open-source collaboration

---

## v1.6.0 – Conditional Field Engine & Repeater Stability

### Added

- Structured conditional field system
- Multiple condition support per field
- AND / OR relation logic
- Expanded operators:
    - `==`
    - `!=`
    - `>`
    - `<`
    - `>=`
    - `<=`
    - `in`
    - `not_in`
    - `empty`
    - `not_empty`
- Deterministic condition normalization in the field schema layer
- Scoped conditional evaluation inside repeater rows

### Improvements

- Conditional visibility now works reliably inside repeater fields
- Repeater row cloning correctly resets conditional initialization
- Repeater index remapping now updates conditional targets safely
- Field condition names are resolved deterministically across Settings and MetaBoxes

### Fixed

- Conditional fields not updating correctly in duplicated repeater rows
- Incorrect field targeting caused by repeater index changes
- Conditional initialization not resetting after cloning
- Conditional evaluation scope leaking across repeater rows

### Internal

- Improved conditional field evaluation engine
- Safer repeater reindex logic
- Safer condition field name remapping
- Improved conditional initialization handling

---

## v1.5.1 -- 2026-03-03

### Fixed
- Prevent duplicate media selection within media field (multiple mode)

### Improved
- Replaced alert() with WordPress core-styled admin notices
- Added `notice-warning` for duplicate selections
- Added `notice-error` for invalid file types
- Support stacked dismissible notices per field
- Improved overall media field UX consistency

### Notes
- No breaking changes
- No API changes
- Backwards compatible

---

## v1.5.0 -- 2026-02-28

### Added
- MetaBox ID validation (format and uniqueness enforcement)
- Settings Tab ID validation
- Template-based MetaBox filtering (classic and block theme support)
- Field-type CSS class injection for layout targeting
- Responsive admin grid improvements
- Vertical tab layout for MetaBoxes

### Changed
- Meta keys are now namespaced as: _{PREFIX}{BOX_ID}_{FIELD_KEY}
- Repository methods accept box ID and raw field key — never full meta keys.
- Prefix generation centralized inside MetaBoxRepository
- MetaBox registration skips unmatched templates before add_meta_box()

### Improved
- Stronger namespace isolation between MetaBoxes
- Cleaner repository abstraction
- Deterministic meta key ownership
- Responsive admin styling consistency

### Internal
- Removed direct meta key construction outside repository
- Registry-level validation enforcement
- Refined template resolution logic

---

## v1.4.0 -- 2026-02-28

### Changed

- Renamed `Public/` runtime layer to `Frontend/`
- Updated namespaces to reflect architectural intent
- Improved structural clarity between Admin and Frontend layers

### Notes

- No runtime behavior changes
- No storage format changes
- No public API changes
- This is a structural refactor for long-term maintainability

---

## v1.3.0 -- 2026-02-21

### Added

- MetaBox system with tab support
- Shared field engine between Settings and MetaBox modules
- Modular Admin architecture (Settings + MetaBox modules)

### Fixed

- Scalar fields incorrectly deleted when empty
- Checkbox false state not persisting
- MultiSelect rendering mismatch
- Radio/Select storing numeric index instead of semantic value
- Broken repeater input name structure
- Template repeater row being saved
- Empty repeater rows being persisted
- Incorrect delete logic for array-based fields

### Improved

- Schema option normalization (numeric → semantic keys)
- Field-type-aware save pipeline
- Proper repeater sanitization integration
- Stable nested meta structure
- Clean numeric reindexing for repeater rows
- Consistent meta key prefix handling
- Predictable POST → sanitize → persist lifecycle

This release stabilizes the field engine and introduces a unified MetaBox system.

---

## v1.2.0 - 2026-02-18

### Added
- Granular settings access methods: `getValue()`, `setValue()`, `deleteValue()`
- Documentation for full option vs granular key access in HOW-TO-USE.md

---

## v1.1.2 – 2026-02-16

- Fix: Media field (multiple) removing all items when deleting one
- Fix: Repeater index synchronization inconsistencies
- Fix: Media field data-name handling inside repeater
- Improve: Repeater row title initialization on load

---

## v1.1.1 – 2026-02-15

### Documentation

- Updated README to reflect v1.1 features
- Updated FIELDS reference with Repeater, Media (multiple), Editor,
    and Grid layout
- Updated HOW-TO-USE guide with new examples
- Updated ADVANCED-TOPICS with architectural guarantees for v1.1
- Updated CHANGELOG

This release contains documentation updates only. No runtime or storage
changes.

---

## v1.1.0 – 2026-02-15

### Added
- Repeater field with:
    - Collapsible rows (collapsed by default)
    - Drag & drop sorting
    - Duplicate row support
    - Min / max enforcement
    - Template-based rendering
- Multiple media selection support (`multiple: true`)
- Square media preview system
- Per-item media removal (multiple mode)
- 12-column CSS Grid admin layout
- Scoped admin design system (`.wppb-admin`)
- Semantic design tokens (surface, accent, danger)
- Editor field (`wp_editor`) support inside repeaters

### Improved
- Centralized field rendering architecture
- Schema-driven `FieldDefinition`
- Deterministic nested sanitization
- Media sorting persistence
- Checkbox and radio layout handling
- Admin CSS conflict hardening
- Safer TinyMCE ID handling (no bracket warnings)

### Stability
- Storage format remains deterministic
- No breaking changes to existing simple fields
- Layout system migration does not affect stored data

---

## [1.0.2] – 2026-02-09

### Changed
- README clarified to better define scope and intended audience
- Documentation polished and aligned with final v1.x architecture
- Field reference updated to list all supported options
- `.gitignore` finalized

### Fixed
- Minor documentation inconsistencies

---

## [1.0.1] – 2026-02-09

### Added
- Explicit documentation for bundled dependencies
- `vendor/` directory listed as a first-class plugin component
- Release checklist enforcing dependency inclusion

### Changed
- Plugin bootstrap now guards against missing bundled dependencies

### Notes
This release does not change the public API or plugin behavior.
It reinforces distribution guarantees and architectural contracts.

---

## [1.0.0] — 2026-02-06 – Stable Foundation Release

This is the first **stable** release of WP Plugin Boilerplate.

v1.0.0 marks the point where the architecture has been validated by building and
stress-testing real plugins across admin, frontend, lifecycle, and rename scenarios.

### Added
- Clear separation between Admin, Settings, and Public runtime layers
- Unconditional runtime wiring for public behavior
- Tab-based settings ownership (no schema layer)
- Deterministic uninstall with prefix-based cleanup
- Dynamic menu capability resolution based on tab visibility
- Fully documented field definition structure
- Explicit lifecycle guarantees (activation, deactivation, uninstall)

### Changed
- Settings are now fully owned by tabs
- Import / Export scoped as global operations
- Reset scoped as tab-specific operation
- Documentation rewritten to reflect v1.0 architecture
- Advanced Topics rewritten as architectural reference

### Fixed
- Capability mismatch between menu visibility and tab access
- Uninstall not cleaning up all plugin-owned options
- Runtime behavior incorrectly gated during bootstrap
- Documentation drift from actual architecture

### Stability Guarantees
Starting with v1.0.0:
- Public behavior is always registered at runtime
- Admin configuration flows cleanly into frontend behavior
- Plugin lifecycle behavior is predictable and safe
- Plugin can be renamed and reused without breaking behavior

Breaking these guarantees requires a major version bump.

---

## [v0.9.6] — 2026-02-06

### Fixed
- Prevented fatal errors during plugin uninstall by removing class dependencies from `uninstall.php`
- Clarified and enforced global scope for Import and Export operations

### Changed
- Documented the distinction between global (Import/Export) and tab-scoped (Reset) settings actions
- Hardened lifecycle edge cases to align with WordPress execution model

### Philosophy
- Capability scope must match data scope
- Uninstall runs outside the plugin context and must remain procedural

---

## [v0.9.5] — 2026-02-06

### Changed
- Consolidated admin menu registration into `Admin` class
- Removed redundant MenuRegistrar abstraction
- Menu placement is now fully configuration-driven via entry file
- Tabs can optionally be exposed as submenu items without UI duplication
- Plugin admin UX refined (About & Help tabs aligned with documentation)

### Fixed
- Plugin action links now correctly point to settings screen
- Removed lifecycle duplication around uninstall handling

### Philosophy
- Menu structure is code, not state
- Admin behavior is deterministic and explicit
- Boilerplate favors clarity over configurability

---

## [v0.9.4] — 2026-02-05

### Changed
- Formalized project licensing as **GPL-2.0-or-later** to align with WordPress ecosystem requirements.
- Added canonical LICENSE file and aligned README and composer.json.

---

## [0.9.3] — 2026-02-05 – Architecture Consolidation

### Structural
- Restructured directories to reflect architectural boundaries:
    - Settings logic consolidated under `Settings/`
    - Admin workflows isolated under `Admin/Actions`
    - Tabs elevated as first-class units of composition
    - Field definitions and rendering decoupled from persistence
- Removed schema-era directory layout
- Enforced PSR-4 structure aligned with domain responsibility

### Changed
- Settings ownership moved from schemas to **tabs**
- Tabs are now the single source of truth for:
    - option keys
    - field definitions
    - settings ownership
- Import and export redesigned as **global operations**
    - single export file for all settings tabs
    - import restores matching tabs only
- Multisite handling clarified:
    - default scope is `site`
    - `network` scope is opt-in via `ScopedContract`

### Removed
- Schema-based settings layer
- `SchemaContract`
- `SettingsTabContract`
- Per-tab import/export actions
- Schema-driven directory structure

### Philosophy
- Structure enforces intent
- Tabs define behavior, not schemas
- Persistence is explicit and isolated
- Multisite behavior is deliberate, never implicit
- Import/export favors safety over convenience

---

## [v0.9.2] — 2026-02-04

- Updated composer.json

---

## [v0.9.1] — 2026-02-03

- Added CHANGELOD.md

---

## [v0.9.0] — Foundation Release

First public release of **WP Plugin Boilerplate**.

This release establishes the **core architecture, contracts, and guarantees** for building long-lived, maintainable
WordPress plugins.

### Added

- Opinionated, OOP-first plugin architecture
- Single entry-point plugin bootstrap
- Centralized hook registration via Loader
- Strict PSR-4 namespace and file structure enforcement
- Class-based extension model

#### Settings System

- Schema-driven settings architecture
- One schema per settings tab
- One option key per tab
- Automatic default resolution
- Settings repository as the single access point
- Support for presentation-only tabs (About, Help, Docs)

#### Field System

- Schema-defined, intent-based fields
- Core input fields (text, textarea, checkbox, select, radio, etc.)
- Media fields storing attachment IDs
- File size limits and MIME validation
- Clear separation between intent, rendering, and validation

#### Media Handling

- Native popup filtering for `image`, `audio`, and `video`
- Safe enforcement for `document`, `archive`, and generic media types
- Filename or thumbnail previews based on attachment type
- Backend validation for all media fields

#### Multisite Support

- Explicit multisite handling
- Per-schema scope declaration (`site` or `network`)
- Network-scoped settings restricted to Network Admin
- No implicit switching between option APIs

#### Tools

- Per-tab import and export
- Schema-validated and sanitized imports
- Per-tab reset to defaults
- Capability-protected actions

#### Documentation

- README.md (architecture and principles)
- HOW-TO-USE.md (practical usage guide)
- FIELDS.md (complete field reference)
- ADVANCED-TOPICS.md (multisite, migrations, internals)
- CONTRIBUTING.md
- MIT License

### Stability

- Public APIs and contracts introduced in v0.9.0 are considered **stable**
- Internal refactors may continue until v1.0.0
- No breaking changes will be introduced without documentation and migration guidance

---

## Unreleased

- Reserved for upcoming changes
