# Changelog

All notable changes to this project will be documented in this file.

The format follows a pragmatic, developer-focused style inspired by
Keep a Changelog, without unnecessary ceremony.

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
