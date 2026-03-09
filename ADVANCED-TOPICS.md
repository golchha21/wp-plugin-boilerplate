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

## Declarative Hook Architecture (v1.6.3+)

Hooks may be declared using a `hooks()` method.

Example:

```php
    public function hooks(): array
    {
        return [
            'action' => [
                ['admin_menu', 'register_menus'],
            ],
        ];
    }
```

The Loader reads this structure and performs the final `add_action()` / `add_filter()` registration.

Guarantees:

- Hook registration remains centralized.
- Feature classes never interact with WordPress hook APIs directly.
- Wiring logic stays deterministic and predictable.
- Hook declarations remain static and easy to audit.

Dynamic hooks or external handler classes may still be registered using `register()`.

Declarative hooks are preferred for static plugin behavior.

------------------------------------------------------------------------

## Settings as a Domain Boundary

Settings are domain data shared between admin and runtime.

-   Admin writes settings
-   Public reads settings
-   Settings do not depend on Admin or Public

Settings are owned directly by tabs. There is no schema or persistence
abstraction layer.

FieldDefinition controls structure. FieldRenderer controls rendering.
Sanitization happens per-field.

The settings layer must remain explicit and deterministic.

------------------------------------------------------------------------

## MetaBox Domain Boundary (v1.5+)

MetaBoxes are a structured post meta domain.

-   Each MetaBox owns its own namespace.
-   Field keys are automatically namespaced as:
    *{PREFIX}{BOX_ID}*{FIELD_KEY}
-   ID format and uniqueness validation is available at registry level (opt-in — see `MetaBoxes::validate()`).
-   Persistence must go through MetaBoxRepository.

MetaBoxes do not share keys across boxes.

MetaBoxes may restrict rendering by post type or template, but storage
structure remains deterministic regardless of context.

MetaBox storage guarantees must remain isolated from Settings storage.

------------------------------------------------------------------------

## Repeater Architecture

Repeaters are structured nested arrays.

Guarantees:

-   Rows are ordered
-   Sorting persists order
-   Each row is sanitized independently
-   Template rows are rendered using `<template>`
-   Template markup never leaks into runtime DOM
-   Reindexing is explicit after drag operations
-   Fields that rely on server-rendered lifecycle (e.g., wp_editor) are
    not supported inside repeaters

Repeaters do not alter storage format dynamically.

------------------------------------------------------------------------

## Field Engine Guarantees (v1.3+)

The field engine now operates under strict structural guarantees.

### Save Pipeline

-   Fields are saved using field-type-aware logic.
-   Scalar fields persist empty strings.
-   Checkboxes explicitly store `1` or `0`.
-   Array-based fields are never deleted due to empty state.
-   Repeater fields are sanitized before persistence.

### Option Normalization

Choice fields (`select`, `radio`, `multiselect`) automatically normalize
numeric option arrays into semantic key/value pairs.

Example:

    ['Red', 'Green']

Internally becomes:

    [
      'Red' => 'Red',
      'Green' => 'Green',
    ]

This prevents index-based storage corruption.

### Repeater Sanitization

Repeaters guarantee:

-   Template rows (`__index__`) are never saved
-   Empty rows are removed
-   Each row is sanitized independently
-   Rows are reindexed numerically before persistence
-   Storage format remains deterministic

Rendering and storage structure are strictly aligned.

------------------------------------------------------------------------

## Conditional Engine Guarantees (v1.6+)

Conditional logic is a rendering-layer concern.

Guarantees:

-   Conditions are normalized deterministically before output.
-   Structured JSON is emitted via `data-conditions`.
-   Evaluation is scoped (form-level or repeater-item level).
-   Flat condition arrays default to AND logic.
-   Explicit OR relations are supported.
-   Conditional logic never mutates storage.
-   Conditional evaluation is repeater-index safe.
-   Backward compatibility with pre-v1.6 flat condition arrays is
    preserved.

Nested grouped logic trees are intentionally not supported.

Conditional behavior must never alter persistence structure.

------------------------------------------------------------------------

## Media Field Guarantees

Media fields store attachment IDs only.

Never store:

-   URLs
-   File paths
-   Attachment objects

Single mode stores an integer. Multiple mode stores an ordered array of
integers.

Multiple mode guarantees:

-   Drag sorting persistence
-   Per-item removal
-   Square preview rendering
-   MIME enforcement before save
-   Duplicate selection prevention in multiple mode
-   WordPress core-styled notice rendering
-   `notice-warning` for duplicates
-   `notice-error` for invalid file types
-   Multiple stacked notices per field instance

UI behavior must never mutate storage structure.

Media UI safeguards affect admin experience only. Storage format remains
unchanged.

------------------------------------------------------------------------

## Admin UI System (v1.1+)

The admin interface is:

-   Fully scoped under `.wppb-admin`
-   Built on a 12-column CSS Grid layout
-   Powered by semantic design tokens
-   Safe from wp-admin style conflicts
-   Field type is injected as a CSS class on field wrappers for layout
    targeting
-   MetaBox and Settings Tab ID validation is available at registry level (opt-in — uncomment `self::validate()` in `MetaBoxes::all()` and `Tabs::all()` for production use)

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

-   Import / Export → global operations
-   Reset → tab-scoped operation

Capability scope must match data scope.

------------------------------------------------------------------------

## Lifecycle Boundaries

-   Activation must not mutate runtime behavior outside explicit
    synchronization
-   Deactivation stops behavior without deleting data
-   Uninstall runs in isolation and must remain procedural
-   Uninstall deletes ownership, not individual options

Lifecycle logic must remain predictable and side-effect free.

------------------------------------------------------------------------

## What Not To Do

-   Call `get_option()` directly
-   Call `get_post_meta()` / `update_post_meta()` directly for MetaBox
    fields
-   Manually construct prefixed meta keys
-   Gate runtime wiring during bootstrap
-   Share option keys across tabs
-   Store computed data in settings
-   Treat Admin as a catch-all layer
-   Couple UI layout with storage structure

------------------------------------------------------------------------

## Stability Principle

Explicit behavior over convenience.

The boilerplate is intentionally strict. The constraints are what make
the system stable over time.
