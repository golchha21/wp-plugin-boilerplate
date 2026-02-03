# Changelog

All notable changes to this project will be documented in this file.

This project follows a **foundation-first** release model:
- early versions establish architecture and contracts
- backward compatibility is taken seriously
- breaking changes are documented explicitly

---

## [v0.9.0] — Foundation Release

First public release of **WP Plugin Boilerplate**.

This release establishes the **core architecture, contracts, and guarantees** for building long-lived, maintainable WordPress plugins.

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
