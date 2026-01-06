# Available Field Types – Examples

This document shows **real, copy‑paste‑ready examples** for every field type supported by the Plugin Boilerplate
Settings Framework (v1.3+).

All examples are taken directly from the sample `Bootstrap.php` configuration and reflect **current best practices**.

---

## Text & Content Fields

### Text

```php
$page->add_field(new Text(
    'sample_text',
    'Text',
    'content',
    'text',
    [
        'default'     => 'Sample text',
        'description' => 'Single-line text input.'
    ]
));
```

### Textarea

```php
$page->add_field(new Textarea(
    'sample_textarea',
    'Textarea',
    'content',
    'text',
    [
        'rows'        => 4,
        'description' => 'Multi-line plain text.'
    ]
));
```

### Email

```php
$page->add_field(new Email(
    'sample_email',
    'Email',
    'content',
    'text',
    [
        'description' => 'Email address input.'
    ]
));
```

### Number

```php
$page->add_field(new Number(
    'sample_number',
    'Number',
    'content',
    'text',
    [
        'min'         => 1,
        'max'         => 100,
        'default'     => 10,
        'description' => 'Numeric input with min/max hints.'
    ]
));
```

### RichText (WordPress WYSIWYG)

```php
$page->add_field(new RichText(
    'sample_richtext',
    'Rich Text',
    'content',
    'text',
    [
        'rows'          => 6,
        'media_buttons' => false,
        'description'   => 'WordPress WYSIWYG editor.'
    ]
));
```

---

## Single Choice Fields

### Checkbox

```php
$page->add_field(new Checkbox(
    'sample_checkbox',
    'Checkbox',
    'choices',
    'single',
    [
        'default'     => '1',
        'description' => 'Boolean on/off toggle.'
    ]
));
```

### Radio

```php
$page->add_field(new Radio(
    'sample_radio',
    'Radio',
    'choices',
    'single',
    [
        'choices' => [
            'one' => 'Option One',
            'two' => 'Option Two',
        ],
        'default'     => 'one',
        'description' => 'Mutually exclusive choices.'
    ]
));
```

### Select

```php
$page->add_field(new Select(
    'sample_select',
    'Select',
    'choices',
    'single',
    [
        'choices' => [
            'a' => 'Choice A',
            'b' => 'Choice B',
        ],
        'default'     => 'a',
        'description' => 'Dropdown selection.'
    ]
));
```

---

## Multiple Choice Fields

### MultiCheckbox

```php
$page->add_field(new MultiCheckbox(
    'sample_multicheckbox',
    'MultiCheckbox',
    'choices',
    'multiple',
    [
        'choices'     => Choices::post_types(),
        'default'     => ['post', 'page'],
        'description' => 'Multiple checkbox selection.'
    ]
));
```

### MultiSelect (Searchable, Select2)

```php
$page->add_field(new MultiSelect(
    'sample_multiselect',
    'MultiSelect',
    'choices',
    'multiple',
    [
        'choices'     => Choices::roles(),
        'default'     => ['administrator'],
        'description' => 'Searchable multi-select dropdown.'
    ]
));
```

---

## Date & Time Fields (WordPress‑aware)

### Date

```php
$page->add_field(new Date(
    'sample_date',
    'Date',
    'datetime',
    'time',
    [
        'default'     => wp_date('Y-m-d'),
        'description' => 'Stored as YYYY-MM-DD.'
    ]
));
```

### Time

```php
$page->add_field(new Time(
    'sample_time',
    'Time',
    'datetime',
    'time',
    [
        'default'     => '09:00',
        'description' => 'Stored as HH:MM.'
    ]
));
```

### DateTime

```php
$page->add_field(new DateTime(
    'sample_datetime',
    'DateTime',
    'datetime',
    'time',
    [
        'default'     => strtotime('tomorrow 09:00', current_time('timestamp')),
        'description' => 'Stored as Unix timestamp, rendered using WordPress date/time formats.'
    ]
));
```

---

## Media Fields

### Media (Images, Multiple)

```php
$page->add_field(new Media(
    'sample_media',
    'Media',
    'media',
    'files',
    [
        'multiple'    => true,
        'mime_types'  => ['image'],
        'description' => 'Attachment IDs only. Drag to reorder.'
    ]
));
```

### Media (Documents / PDF)

```php
$page->add_field(new Media(
    'pdf',
    'Documents (PDF)',
    'media',
    'files',
    [
        'type'   => 'application/pdf',
        'button' => 'Select PDF'
    ]
));
```

---

## Render‑Only Fields (No Saving)

### RawHtml (Tools, About, Diagnostics)

```php
$page->add_field(new RawHtml(
    'tools_page',
    '',
    'tools',
    'tools',
    fn () => ToolsPage::render(),
    ['single_column' => true]
));
```

```php
$page->add_field(new RawHtml(
    'about_page',
    '',
    'about',
    'about',
    fn () => AboutPage::render(MY_PLUGIN_VERSION),
    ['single_column' => true]
));
```

---

# Core WordPress Pages & Profile – Field Examples (v1.3)

Fields can be rendered outside the plugin settings page by attaching them to a **target** using the fluent API:

```php
->attach_to(Target::...)
```

A field can belong to **exactly one target**.

---

## Core Settings Pages

### General Settings

```php
$page->add_field(
    (new Text(
        'options_company_name',
        'Company Name',
        '',
        '',
        [
            'description' => 'Appears on the General settings page.'
        ]
    ))
    ->attach_to(Target::options_page('general'))
);
```

### Reading Settings

```php
$page->add_field(
    (new Number(
        'options_posts_limit',
        'Custom Posts Limit',
        '',
        '',
        [
            'min'         => 1,
            'max'         => 50,
            'default'     => 10,
            'description' => 'Appears on the Reading settings page.'
        ]
    ))
    ->attach_to(Target::options_page('reading'))
);
```

### Writing Settings

```php
$page->add_field(
    (new Checkbox(
        'options_disable_formatting',
        'Disable Auto Formatting',
        '',
        '',
        [
            'description' => 'Appears on the Writing settings page.'
        ]
    ))
    ->attach_to(Target::options_page('writing'))
);
```

### Discussion Settings

```php
$page->add_field(
    (new Select(
        'options_comment_policy',
        'Comment Policy',
        '',
        '',
        [
            'choices' => [
                'open'   => 'Allow comments',
                'closed' => 'Disable comments'
            ],
            'default'     => 'open',
            'description' => 'Appears on the Discussion settings page.'
        ]
    ))
    ->attach_to(Target::options_page('discussion'))
);
```

### Media Settings

```php
$page->add_field(
    (new Checkbox(
        'options_lazyload_images',
        'Enable Image Lazy Loading',
        '',
        '',
        [
            'default'     => '1',
            'description' => 'Appears on the Media settings page.'
        ]
    ))
    ->attach_to(Target::options_page('media'))
);
```

---

## User Profile Fields

```php
$page->add_field(
    (new Text(
        'profile_job_title',
        'Job Title',
        '',
        '',
        [
            'description' => 'Displayed on author pages.'
        ]
    ))
    ->attach_to(Target::user_profile())
);
```

---

## Notes & Guarantees

- Each field saves to **one independent option**, prefixed via `OPTION_PREFIX`
- Defaults apply **only when an option does not already exist**
- Defaults never overwrite user-saved values
- MultiSelect and MultiCheckbox defaults must be arrays
- DateTime values are stored as Unix timestamps
- RawHtml fields:
    - Are never registered with the Settings API
    - Are excluded from export/import
    - Do not save data

