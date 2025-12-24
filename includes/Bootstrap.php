<?php

namespace PluginBoilerplate;

use PluginBoilerplate\Admin\Fields\Email;
use PluginBoilerplate\Admin\Fields\Media;
use PluginBoilerplate\Admin\Fields\MultiCheckbox;
use PluginBoilerplate\Admin\Fields\MultiSelect;
use PluginBoilerplate\Admin\Fields\Number;
use PluginBoilerplate\Admin\Fields\RawHtml;
use PluginBoilerplate\Admin\Fields\Text;
use PluginBoilerplate\Admin\Helpers\Choices;
use PluginBoilerplate\Admin\Helpers\ExportImport;
use PluginBoilerplate\Admin\SettingsPage;
use PluginBoilerplate\Admin\Fields\Checkbox;
use PluginBoilerplate\Admin\Fields\Select;
use PluginBoilerplate\Admin\Fields\Textarea;

class Bootstrap
{
    public function register(): void
    {
        // Settings page setup
        add_action('plugins_loaded', [$this, 'init_settings_page']);

        // Settings link on plugin page
        add_filter(
            'plugin_action_links_' . plugin_basename(PLUGIN_FILE),
            [$this, 'add_settings_link']
        );

        // Admin assets
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    public function init_settings_page(): void
    {
        $page = new SettingsPage([
            'option_prefix' => OPTION_PREFIX,       // ← required
            'menu_slug' => 'plugin-boilerplate',
            'menu_title' => 'Plugin Boilerplate',
            'page_title' => 'Plugin Boilerplate Settings',
            'capability' => 'manage_options',
        ]);

        // Tabs
        $page->add_tab('general', 'General');
        $page->add_tab('content', 'Content Rules');
        $page->add_tab('tools', 'Tools');

        // Sections
        $page->add_section('general', 'basic', '');
        $page->add_section('content', 'filters', '');
        $page->add_section('tools', 'export_import', '');

        $page->add_field(new Text(
            'name',
            'Name',
            'general',
            'basic'
        ));

        $page->add_field(new Email(
            'email',
            'Email',
            'general',
            'basic'
        ));

        $page->add_field(new Number(
            'number',
            'Number',
            'general',
            'basic',
            [
                'min'         => 1,
                'max'         => 5000,
                'step'        => 1,
                'description' => 'Controls how many items are processed per run.'
            ]
        ));

        $page->add_field(new Textarea(
            'address',
            'Address',
            'general',
            'basic',
            [
                'rows' => 6,
                'description' => 'Complete address'
            ]
        ));

        $page->add_field(new Checkbox(
            'subscribe_promo',
            'Subscription',
            'general',
            'basic',
            [
                'label' => 'Subscribe to promotional emails.'
            ]
        ));


        $page->add_field(new Select(
            'roles',
            'Roles',
            'general',
            'basic',
            [
                'options' => Choices::roles(),
                'description' => 'List of all the roles.'
            ]
        ));

        $page->add_field(
            new MultiSelect(
                'enabled_post_types',
                'Select Post Types',
                'content',
                'filters',
                [
                    'choices' => Choices::post_types(),
                    'description' => 'Select one or more post types'
                ]
            )
        );

        $page->add_field(
            new MultiCheckbox(
                'enabled_taxonomy',
                'Select Taxonomies',
                'content',
                'filters',
                [
                    'choices' => Choices::taxonomies(),
                    'description' => 'Select one or more taxonomies'
                ]
            )
        );

        $page->add_field(new Media(
            'media',
            'Media',
            'content',
            'filters',
            [
                'button'      => 'Choose Image',
                'description' => 'Used as the primary image.'
            ]
        ));

        $page->add_field(new Media(
            'medias',
            'Medias',
            'content',
            'filters',
            [
                'multiple'      => true,
                'button'      => 'Choose Images',
                'description' => 'Used as the gallery images.'
            ]
        ));

        $page->add_field(new Media(
            'pdf',
            'Documents (PDF)',
            'content',
            'filters',
            [
                'type'        => 'application/pdf',
                'button'      => 'Select PDF'
            ]
        ));

        $page->add_field(
            new RawHtml(
                'export_settings',
                'Export Settings',
                'tools',
                'export_import',
                [ExportImport::class, 'render_export']
            )
        );

        $page->add_field(
            new RawHtml(
                'import_settings',
                'Import Settings',
                'tools',
                'export_import',
                [ExportImport::class, 'render_import']
            )
        );
    }

    public function add_settings_link(array $links): array
    {
        $base = IS_OPTIONS_PAGE ? 'options-general.php' : 'admin.php';
        $settings_url = admin_url($base . '?page=plugin-boilerplate');

        $settings_link = '<a href="' . esc_url($settings_url) . '">Settings</a>';

        array_unshift($links, $settings_link);

        return $links;
    }

    public function enqueue_admin_assets(string $hook): void
    {
        if (
            ! isset($_GET['page']) ||
            $_GET['page'] !== 'plugin-boilerplate'
        ) {
            return;
        }

        wp_enqueue_script('jquery-ui-sortable');

        // Detect Select2 / SelectWoo
        if (wp_script_is('select2', 'registered')) {
            wp_enqueue_script('select2');
            wp_enqueue_style('select2');
            $handle = 'select2';
        } elseif (wp_script_is('selectWoo', 'registered')) {
            wp_enqueue_script('selectWoo');
            wp_enqueue_style('selectWoo');
            $handle = 'selectWoo';
        } else {
            // Absolute fallback (safe, local copy)
            wp_enqueue_script(
                'plugin-boilerplate-select2',
                plugin_dir_url(PLUGIN_FILE) . 'assets/vendor/select2/select2.min.js',
                ['jquery'],
                '4.0.13',
                true
            );

            wp_enqueue_style(
                'plugin-boilerplate-select2',
                plugin_dir_url(PLUGIN_FILE) . 'assets/vendor/select2/select2.min.css',
                [],
                '4.0.13'
            );

            $handle = 'plugin-boilerplate-select2';
        }

        wp_add_inline_script(
            $handle,
            "jQuery(function($){
                if ($.fn.select2) {
                    $('.plugin-boilerplate-multiselect').select2({
                        width: '100%',
                        placeholder: 'Select options'
                    });
                }
            });"
        );
    }


}
