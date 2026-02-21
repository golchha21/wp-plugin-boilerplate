# Advanced Topics

This document explains architectural constraints that protect long-term
stability. These rules exist to prevent accidental complexity and
implicit coupling.

------------------------------------------------------------------------

## Runtime Wiring

Public behavior must always be registered unconditionally.

Do not gate hook registration behind execution context checks. Let
WordPress control when hooks fire.

Context checks belong inside callbacks, not around registration.

Runtime wiring must remain deterministic and predictable.

------------------------------------------------------------------------

## Settings as a Domain Boundary

Settings are domain data shared between admin and runtime.

- Admin writes settings
- Public reads settings
- Settings do not depend on Admin or Public

Settings are owned directly by tabs. There is no schema or persistence
abstraction layer.

FieldDefinition controls structure. FieldRenderer controls rendering.
Sanitization happens per-field.

The settings layer must remain explicit and deterministic.

------------------------------------------------------------------------

## Repeater Architecture

Repeaters are structured nested arrays.

Guarantees:

- Rows are ordered
- Sorting persists order
- Each row is sanitized independently
- Template rows are rendered using `<template>`
- Template markup never leaks into runtime DOM
- Reindexing is explicit after drag operations 
- Fields that rely on server-rendered lifecycle (e.g., wp_editor) are not supported inside repeaters

Repeaters do not alter storage format dynamically.

------------------------------------------------------------------------

## Field Engine Guarantees (v1.3+)

The field engine now operates under strict structural guarantees.

### Save Pipeline

- Fields are saved using field-type-aware logic.
- Scalar fields persist empty strings.
- Checkboxes explicitly store `1` or `0`.
- Array-based fields are never deleted due to empty state.
- Repeater fields are sanitized before persistence.

### Option Normalization

Choice fields (`select`, `radio`, `multiselect`) automatically normalize
numeric option arrays into semantic key/value pairs.

Example:

``` php
['Red', 'Green']
```

Internally becomes:

``` php
[
  'Red' => 'Red',
  'Green' => 'Green',
]
```

This prevents index-based storage corruption.

### Repeater Sanitization

Repeaters guarantee:

- Template rows (`__index__`) are never saved
- Empty rows are removed
- Each row is sanitized independently
- Rows are reindexed numerically before persistence
- Storage format remains deterministic

Rendering and storage structure are strictly aligned.

------------------------------------------------------------------------

## Media Field Guarantees

Media fields store attachment IDs only.

Never store:

- URLs
- File paths
- Attachment objects

Single mode stores an integer. Multiple mode stores an ordered array of
integers.

Multiple mode guarantees:

- Drag sorting persistence
- Per-item removal
- Square preview rendering
- MIME enforcement before save

UI behavior must never mutate storage structure.

------------------------------------------------------------------------

## Admin UI System (v1.1+)

The admin interface is:

- Fully scoped under `.wppb-admin`
- Built on a 12-column CSS Grid layout
- Powered by semantic design tokens
- Safe from wp-admin style conflicts

Layout affects presentation only. Layout never affects data structure.

------------------------------------------------------------------------

## Capability Semantics

Capabilities are not hierarchical.

Menu visibility is determined dynamically: A menu is visible if the
current user can access at least one tab.

Tabs enforce their own capabilities at runtime.

Capability scope must match data scope.

------------------------------------------------------------------------

## Import, Export, and Reset Scope

- Import / Export → global operations
- Reset → tab-scoped operation

Capability scope must match data scope.

------------------------------------------------------------------------

## Lifecycle Boundaries

- Activation must not mutate runtime behavior outside explicit
    synchronization
- Deactivation stops behavior without deleting data
- Uninstall runs in isolation and must remain procedural
- Uninstall deletes ownership, not individual options

Lifecycle logic must remain predictable and side-effect free.

------------------------------------------------------------------------

## What Not To Do

- Call `get_option()` directly
- Gate runtime wiring during bootstrap
- Share option keys across tabs
- Store computed data in settings
- Treat Admin as a catch-all layer
- Couple UI layout with storage structure

------------------------------------------------------------------------

## Stability Principle

Explicit behavior over convenience.

The boilerplate is intentionally strict. The constraints are what make
the system stable over time.
