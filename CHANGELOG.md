# Changelog

All notable changes to this project will be documented in this file.

The format follows a pragmatic, developer-focused style inspired by
Keep a Changelog, without unnecessary ceremony.

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
