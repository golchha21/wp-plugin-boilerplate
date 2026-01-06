<?php

namespace PluginBoilerplate;

use PluginBoilerplate\Admin\CorePageRegistry;
use PluginBoilerplate\Admin\Fields\Checkbox;
use PluginBoilerplate\Admin\Fields\Date;
use PluginBoilerplate\Admin\Fields\DateTime;
use PluginBoilerplate\Admin\Fields\Email;
use PluginBoilerplate\Admin\Fields\Media;
use PluginBoilerplate\Admin\Fields\MultiCheckbox;
use PluginBoilerplate\Admin\Fields\MultiSelect;
use PluginBoilerplate\Admin\Fields\Number;
use PluginBoilerplate\Admin\Fields\Radio;
use PluginBoilerplate\Admin\Fields\RawHtml;
use PluginBoilerplate\Admin\Fields\RichText;
use PluginBoilerplate\Admin\Fields\Select;
use PluginBoilerplate\Admin\Fields\Text;
use PluginBoilerplate\Admin\Fields\Textarea;
use PluginBoilerplate\Admin\Fields\Time;
use PluginBoilerplate\Admin\Helpers\AboutPage;
use PluginBoilerplate\Admin\Helpers\Choices;
use PluginBoilerplate\Admin\Helpers\ToolsPage;
use PluginBoilerplate\Admin\Services\ToolsService;
use PluginBoilerplate\Admin\SettingsPage;
use PluginBoilerplate\Admin\Target;

class Bootstrap
{
    public function init_settings_page(): void
    {
        $page = new SettingsPage([
            'option_prefix' => OPTION_PREFIX,       // ← required
            'menu_slug' => 'plugin-boilerplate',
            'menu_title' => 'Plugin Boilerplate',
            'page_title' => 'Plugin Boilerplate Settings',
            'capability' => 'manage_options',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Tabs
        |--------------------------------------------------------------------------
        */

        $page->add_tab('content', 'Content');
        $page->add_tab('choices', 'Choices');
        $page->add_tab('datetime', 'Date & Time');
        $page->add_tab('media', 'Media');
        $page->add_tab('tools', 'Tools', false);
        $page->add_tab('about', 'About', false);

        /*
        |--------------------------------------------------------------------------
        | Sections
        |--------------------------------------------------------------------------
        */

        $page->add_section('content', 'text', 'Text & Content Fields');
        $page->add_section('choices', 'single', 'Single Choice');
        $page->add_section('choices', 'multiple', 'Multiple Choice');
        $page->add_section('datetime', 'time', 'Date & Time');
        $page->add_section('media', 'files', 'Media');
        $page->add_section('tools', 'tools');
        $page->add_section('about', 'about');

        /*
        |--------------------------------------------------------------------------
        | Text & Content Fields
        |--------------------------------------------------------------------------
        */

        $page->add_field((new Text(
            'sample_text',
            'Text',
            'content',
            'text',
            [
                'default' => 'Sample text',
                'description' => 'Single-line text input.'
            ]
        )));

        $page->add_field(new Textarea(
            'sample_textarea',
            'Textarea',
            'content',
            'text',
            [
                'rows' => 4,
                'description' => 'Multi-line plain text.'
            ]
        ));

        $page->add_field(new Email(
            'sample_email',
            'Email',
            'content',
            'text',
            [
                'description' => 'Email address input.'
            ]
        ));

        $page->add_field(new Number(
            'sample_number',
            'Number',
            'content',
            'text',
            [
                'min' => 1,
                'max' => 100,
                'default' => 10,
                'description' => 'Numeric input with constraints.'
            ]
        ));

        $page->add_field(new RichText(
            'sample_richtext',
            'Rich Text',
            'content',
            'text',
            [
                'rows' => 6,
                'media_buttons' => false,
                'description' => 'WordPress WYSIWYG editor.'
            ]
        ));

        /*
        |--------------------------------------------------------------------------
        | Single Choice Fields
        |--------------------------------------------------------------------------
        */

        $page->add_field(new Checkbox(
            'sample_checkbox',
            'Checkbox',
            'choices',
            'single',
            [
                'default' => '1',
                'description' => 'Boolean on/off toggle.'
            ]
        ));

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
                'default' => 'one',
                'description' => 'Mutually exclusive choices.'
            ]
        ));

        $page->add_field((new Select(
            'sample_select',
            'Select',
            'choices',
            'single',
            [
                'choices' => [
                    'a' => 'Choice A',
                    'b' => 'Choice B',
                ],
                'default' => 'a',
                'description' => 'Dropdown selection.'
            ]
        )));

        /*
        |--------------------------------------------------------------------------
        | Multiple Choice Fields
        |--------------------------------------------------------------------------
        */

        $page->add_field((new MultiCheckbox(
            'sample_multicheckbox',
            'MultiCheckbox',
            'choices',
            'multiple',
            [
                'choices' => Choices::post_types(),
                'default' => ['post', 'page'],
                'description' => 'Multiple checkbox selection.'
            ]
        )));

        $page->add_field(new MultiSelect(
            'sample_multiselect',
            'MultiSelect',
            'choices',
            'multiple',
            [
                'choices' => Choices::roles(),
                'default' => ['administrator'],
                'description' => 'Searchable multi-select (Select2).'
            ]
        ));

        /*
        |--------------------------------------------------------------------------
        | Date & Time Fields (WordPress-aware)
        |--------------------------------------------------------------------------
        */

        $page->add_field(new Date(
            'sample_date',
            'Date',
            'datetime',
            'time',
            [
                'default' => wp_date('Y-m-d'),
                'description' => 'Stored as YYYY-MM-DD.'
            ]
        ));

        $page->add_field(new Time(
            'sample_time',
            'Time',
            'datetime',
            'time',
            [
                'default' => '09:00',
                'description' => 'Stored as HH:MM.'
            ]
        ));

        $page->add_field(new DateTime(
            'sample_datetime',
            'DateTime',
            'datetime',
            'time',
            [
                'default' => strtotime('tomorrow 09:00', current_time('timestamp')),
                'description' => 'Stored as Unix timestamp, rendered using WP settings.'
            ]
        ));

        /*
        |--------------------------------------------------------------------------
        | Media Fields
        |--------------------------------------------------------------------------
        */

        $page->add_field(new Media(
            'sample_media',
            'Media',
            'media',
            'files',
            [
                'multiple' => true,
                'mime_types' => ['image'],
                'description' => 'Attachment IDs only. Drag to reorder.'
            ]
        ));

        $page->add_field(new Media(
            'pdf',
            'Documents (PDF)',
            'media',
            'files',
            [
                'type' => 'application/pdf',
                'button' => 'Select PDF'
            ]
        ));

        /*
        |--------------------------------------------------------------------------
        | Tools Fields
        |--------------------------------------------------------------------------
        */

        $page->add_field(new RawHtml(
            'tools_page',
            '',
            'tools',
            'tools',
            fn() => ToolsPage::render(),
            ['single_column' => true]
        ));

        $page->add_field(new RawHtml(
            'about_page',
            '',
            'about',
            'about',
            fn() => AboutPage::render(MY_PLUGIN_VERSION),
            ['single_column' => true]
        ));

        /*
        |--------------------------------------------------------------------------
        | User Profile Fields
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Options Fields
        |--------------------------------------------------------------------------
        */

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

        $page->add_field(
            (new Checkbox(
                'options_disable_pve',
                'Disable Post via email',
                '',
                '',
                [
                    'description' => 'Appears on the Writing settings page under post via email section.'
                ]
            ))
                ->attach_to(Target::options_page('writing', 'post_via_email'))
        );

        $page->add_field(
            (new Number(
                'options_posts_limit',
                'Custom Posts Limit',
                '',
                '',
                [
                    'min' => 1,
                    'max' => 50,
                    'default' => 10,
                    'description' => 'Appears on the Reading settings page.'
                ]
            ))
                ->attach_to(Target::options_page('reading'))
        );

        $page->add_field(
            (new Select(
                'options_comment_policy',
                'Comment Policy',
                '',
                '',
                [
                    'choices' => [
                        'open' => 'Allow comments',
                        'closed' => 'Disable comments'
                    ],
                    'default' => 'open',
                    'description' => 'Appears on the Discussion settings page.'
                ]
            ))
                ->attach_to(Target::options_page('discussion'))
        );

        $page->add_field(
            (new Select(
                'options_avatar_policy',
                'Avatar Policy',
                '',
                '',
                [
                    'choices' => [
                        'open' => 'Allow avatars',
                        'closed' => 'Disable avatars'
                    ],
                    'default' => 'open',
                    'description' => 'Appears on the Discussion settings page after avatars section.'
                ]
            ))
                ->attach_to(Target::options_page('discussion', 'avatars'))
        );

        $page->add_field(
            (new Checkbox(
                'options_lazyload_images',
                'Enable Image Lazy Loading',
                '',
                '',
                [
                    'default' => '1',
                    'description' => 'Appears on the Media settings page.'
                ]
            ))
                ->attach_to(Target::options_page('media'))
        );

        /* ---------------- Core Page Integration ---------------- */

        CorePageRegistry::register($page->get_fields());

    }

    public function register(): void
    {
        // Settings page setup
        add_action('plugins_loaded', [$this, 'init_settings_page']);

        ToolsService::register();

        // Settings link on plugin page
        add_filter(
            'plugin_action_links_' . plugin_basename(PLUGIN_FILE),
            [$this, 'add_settings_link']
        );

        // Admin assets
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
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
            !isset($_GET['page']) ||
            $_GET['page'] !== 'plugin-boilerplate'
        ) {
            return;
        }


        wp_enqueue_style(
            'plugin-boilerplate-admin',
            plugin_dir_url(PLUGIN_FILE) . 'assets/admin.css',
            [],
            MY_PLUGIN_VERSION
        );
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
