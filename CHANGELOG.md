# Changelog

All notable changes to this project will be documented in this file.

The format follows a pragmatic, developer-focused style inspired by
Keep a Changelog, without unnecessary ceremony.

---

## [1.3] – Core Targets & Profile Field Support

### Added
- Support for attaching fields to **WordPress core settings pages**:
    - General
    - Writing
    - Reading
    - Discussion
    - Media
- Support for attaching fields to **user profile pages**
- Explicit `Target` value object to declare rendering location
- `CorePageRegistry` to safely integrate with WordPress core pages
- Context-aware field rendering for user profile fields
- Strict enforcement that a field can belong to only one target
- Strict separation between Settings API pages and render-only pages

### Changed

- Field rendering pipeline made context-aware (options vs user meta)
- Internal save logic adjusted to support core settings pages
- Bootstrap updated to register external targets explicitly
- README updated

### Not Supported (by design)
- Permalink settings page
- Privacy settings page

### Notes

- No breaking changes to existing plugins using v1.2
- Existing options remain untouched
- Profile fields use user meta, not options
- Core settings fields are saved independently from plugin settings

---

## [1.2] – New Field Types, Default Support & Framework Stabilization

### Added

- New field types:
    - Radio
    - RichText (WordPress WYSIWYG)
    - Date
    - Time
    - DateTime
- Optional `default` value support across all field types
- WordPress-aware date and time handling using site timezone and formats
- `Tab` value object with `is_form_tab()` to distinguish form vs render-only tabs
- Versioned JSON export/import format
- Unified Tools service with nonce-protected actions
- About and Tools tabs implemented as render-only helpers

### Changed

- RawHtml fields now render outside the Settings API table
- Tools and About tabs no longer rely on `do_settings_sections()`
- Export and Import logic merged into a single service
- Date, Time, and DateTime fields normalized to WordPress timezone handling
- Select field standardized to use `choices` consistently
- MultiSelect and MultiCheckbox now support array-based defaults
- README updated to reflect the complete field set and default behavior

### Fixed

- DateTime values not being saved due to incorrect sanitization
- Select fields not rendering due to mismatched argument keys
- Tools tab appearing blank because of improper Settings API usage
- Inactive tab data being removed on save
- Layout issues caused by forcing single-column UI inside Settings API tables

### Notes

- Defaults are applied only when an option does not exist
- Defaults never overwrite user-saved values
- No breaking changes to stored data
- Export format is forward-compatible via `export_version`
- Clear separation between data-entry UI and utility UI
- No migration required for existing installations

---

## [1.1.1] – Updated README.md

---

## [1.1.0] – Field System Expansion & Media UX Improvements

### Added

- **New field types**
    - `MultiSelect`
        - Supports multiple selections
        - Searchable dropdown via Select2
        - Works with dynamic choice arrays
    - `MultiCheckbox`
        - Renders grouped checkbox lists
        - Supports WordPress-native datasets
- **`Choices` helper class**
    - Centralized resolvers for WordPress data sources:
        - Public post types (auto-excludes `attachment`)
        - Taxonomies
        - User roles
        - Users
- **Media field enhancements**
    - Per-item remove buttons
    - Drag-to-reorder support when `multiple = true`
    - Thumbnail previews for image attachments
    - Filename previews for non-image media
    - Media type restriction (`image` by default, configurable)
    - Optional multiple selection with order preservation
- **Field descriptions**
    - All field types now support a `description` argument
    - Descriptions are rendered consistently across the UI
- **Checkbox label support**
    - Checkbox fields now support a dedicated inline label option
    - Label is separate from description text

### Changed

- Media fields now store **attachment IDs only** (no URLs)
- Drag handles for Media fields are rendered **only when multiple selection is enabled**
- Hidden inputs for dynamic Media fields are rebuilt **on form submit** to guarantee correct saving
- Example configuration in `Bootstrap.php` updated to demonstrate:
    - New field types
    - Usage of the `Choices` helper
    - Improved field definitions and descriptions

---

## [1.0.0] – Initial Stable Framework Release

### Added

- OOP-based admin settings framework
- Tab-based settings UI with clean navigation
- Per-tab Settings API registration (prevents data loss across tabs)
- Independent option per field (no serialized option arrays)
- Global, consistent option prefix for all settings
- Dependency-based field visibility (UI-only, non-destructive)
- Dedicated Tools tab rendered outside the Settings API
- Prefix-based JSON export and import
- Secure import handling with nonce validation
- Lifecycle management with clear separation:
    - Activation (runtime setup)
    - Deactivation (runtime cleanup only)
    - Uninstall (full permanent cleanup)
- Safe uninstall that removes all prefixed options and transients
- Media field with thumbnail preview
- RawHtml field for utility / tools rendering

---

## Versioning

This project follows semantic versioning:

- MAJOR: Breaking architectural changes
- MINOR: New features, backward compatible
- PATCH: Bug fixes and internal improvements
