<?php

namespace PluginBoilerplate\Admin;

use PluginBoilerplate\Admin\Fields\Field;

class SettingsPage
{
    protected string $menu_slug;
    protected string $page_title;
    protected string $menu_title;
    protected string $capability;
    protected string $option_prefix;

    /** @var array<string, Tab> */
    protected array $tabs = [];

    protected array $sections = [];
    protected array $fields = [];

    public function __construct(array $args)
    {
        $this->menu_slug     = $args['menu_slug'];
        $this->page_title    = $args['page_title'];
        $this->menu_title    = $args['menu_title'];
        $this->capability    = $args['capability'] ?? 'manage_options';
        $this->option_prefix = $args['option_prefix'];

        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    /* ---------------------------------
     * Configuration API
     * --------------------------------- */

    public function add_tab(string $id, string $label, bool $is_form_tab = true): void
    {
        $this->tabs[$id] = new Tab($id, $label, $is_form_tab);
    }

    public function add_section(string $tab_id, string $section_id, string $title = ''): void
    {
        $this->sections[$tab_id][$section_id] = $title;
    }

    public function add_field(Field $field): void
    {
        $field->set_option_prefix($this->option_prefix);
        $this->fields[] = $field;
    }

    /* ---------------------------------
     * Admin Menu
     * --------------------------------- */

    public function add_menu(): void
    {
        if (defined('IS_OPTIONS_PAGE') && IS_OPTIONS_PAGE) {
            add_options_page(
                    $this->page_title,
                    $this->menu_title,
                    $this->capability,
                    $this->menu_slug,
                    [$this, 'render']
            );
        } else {
            add_menu_page(
                    $this->page_title,
                    $this->menu_title,
                    $this->capability,
                    $this->menu_slug,
                    [$this, 'render'],
                    'dashicons-admin-generic'
            );
        }
    }

    /* ---------------------------------
     * Settings Registration
     * --------------------------------- */

    public function register_settings(): void
    {
        $active_tab = $_POST['_active_tab']
                ?? $_GET['tab']
                ?? array_key_first($this->tabs);

        if (! isset($this->tabs[$active_tab])) {
            return;
        }

        // Do NOT register settings for non-form tabs
        if (! $this->tabs[$active_tab]->is_form_tab()) {
            return;
        }

        $group = $this->menu_slug . '_' . $active_tab;

        // Register settings-api aware fields ONLY
        foreach ($this->fields as $field) {
            if ($field->tab !== $active_tab) {
                continue;
            }

            if (! $field->uses_settings_api()) {
                continue;
            }

            register_setting(
                    $group,
                    $field->get_option_name(),
                    [$field, 'sanitize']
            );
        }

        // Sections
        if (! empty($this->sections[$active_tab])) {
            foreach ($this->sections[$active_tab] as $section_id => $title) {
                add_settings_section(
                        $section_id,
                        $title,
                        null,
                        $group
                );
            }
        }

        // Fields (table-based only)
        foreach ($this->fields as $field) {
            if ($field->tab !== $active_tab) {
                continue;
            }

            if ($field->render_outside_table()) {
                continue;
            }

            add_settings_field(
                    $field->id,
                    $field->label,
                    [$field, 'render'],
                    $group,
                    $field->section
            );
        }
    }

    /* ---------------------------------
     * Render Page
     * --------------------------------- */

    public function render(): void
    {
        $active_tab = $_GET['tab'] ?? array_key_first($this->tabs);

        if (! isset($this->tabs[$active_tab])) {
            return;
        }

        $tab   = $this->tabs[$active_tab];
        $group = $this->menu_slug . '_' . $active_tab;
        ?>
        <div class="wrap">
            <h1><?php echo esc_html($this->page_title); ?></h1>

            <h2 class="nav-tab-wrapper">
                <?php foreach ($this->tabs as $t): ?>
                    <a href="?page=<?php echo esc_attr($this->menu_slug); ?>&tab=<?php echo esc_attr($t->id); ?>"
                       class="nav-tab <?php echo $t->id === $active_tab ? 'nav-tab-active' : ''; ?>">
                        <?php echo esc_html($t->label); ?>
                    </a>
                <?php endforeach; ?>
            </h2>

            <?php
            /**
             * 1. Render standalone (single-column) fields
             */
            foreach ($this->fields as $field) {
                if (
                        $field->tab === $active_tab &&
                        $field->render_outside_table()
                ) {
                    echo '<div class="plugin-boilerplate-standalone">';
                    $field->render();
                    echo '</div>';
                }
            }

            /**
             * 2. Non-form tabs (Tools, About, etc.)
             */
            if (! $tab->is_form_tab()) {
                do_settings_sections($group);
                return;
            }
            ?>

            <form method="post" action="options.php">
                <input type="hidden" name="_active_tab" value="<?php echo esc_attr($active_tab); ?>">

                <?php
                settings_fields($group);
                do_settings_sections($group);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
