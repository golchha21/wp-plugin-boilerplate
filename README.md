# WP Plugin Boilerplate

An opinionated, OOP-first WordPress plugin boilerplate.

This repository is a **foundation**, not a demo plugin.

---

## Core Principles

### 1. One entry point
- `wp-plugin-boilerplate.php` is the only file WordPress knows about
- No logic lives there mentioned beyond wiring

### 2. Centralized hooks only
- `add_action` and `add_filter` are allowed **only** inside `Loader`
- Every hook must be registered via `$loader->action()` or `$loader->filter()`

### 3. No WordPress globals in business logic
- No `$_POST`, `$_GET`, `$_REQUEST` outside controlled entry points
- No `global $wpdb` scattered across classes

### 4. Extend by adding classes, not editing core
- New features = new class
- Core classes should rarely change

### 5. PSR-4 is non-negotiable
- File name = class name
- Namespace = folder structure
- Case-sensitive always

---

## Folder Responsibilities

| Folder | Responsibility |
|------|---------------|
| `src/` | All PHP source code |
| `src/Admin` | Admin-only behavior |
| `src/Public` | Frontend behavior |
| `src/Support` | Shared helpers |
| `assets/` | JS / CSS |
| `languages/` | Translations |

---

## What NOT to do

- ❌ No logic in the entry file
- ❌ No direct `add_action` in feature classes
- ❌ No dumping helpers into `functions.php`-style files
- ❌ No silent side effects in constructors

---

## Mental Model

- Entry file = handshake
- Plugin = orchestration
- Loader = wiring
- Classes = behavior

If you break these rules, this stops being a boilerplate.
