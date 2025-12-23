<?php

namespace PluginBoilerplate;

use PluginBoilerplate\Admin\Fields\Email;
use PluginBoilerplate\Admin\Fields\Media;
use PluginBoilerplate\Admin\Fields\Number;
use PluginBoilerplate\Admin\Fields\RawHtml;
use PluginBoilerplate\Admin\Fields\Text;
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
    }

    public function init_settings_page(): void
    {
        $page = new SettingsPage([
            'option_prefix' => OPTION_PREFIX,       // ← required
            'menu_slug'   => 'plugin-boilerplate',
            'menu_title'  => 'Plugin Boilerplate',
            'page_title'  => 'Plugin Boilerplate Settings',
            'capability'  => 'manage_options',
        ]);

        // Tabs
        $page->add_tab('general', 'General');
        $page->add_tab('content', 'Content Rules');
        $page->add_tab('tools', 'Tools');

        // Sections
        $page->add_section('general', 'basic', '');
        $page->add_section('content', 'filters', '');
        $page->add_section('tools', 'export_import', '');

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
            'basic'
        ));

        $page->add_field(new Text(
            'text',
            'Text',
            'general',
            'basic'
        ));

        $page->add_field(new Checkbox(
            'include_posts',
            'Include Posts',
            'content',
            'filters'
        ));

        $page->add_field(new Checkbox(
            'include_pages',
            'Include Pages',
            'content',
            'filters'
        ));

        $page->add_field(new Select(
            'mode',
            'Generation Mode',
            'content',
            'filters',
            [
                'options' => [
                    'simple' => 'Simple',
                    'full'   => 'Full (detailed)'
                ]
            ]
        ));

        $page->add_field(new Textarea(
            'exclude_urls',
            'Exclude URLs (one per line)',
            'content',
            'filters',
            [
                'rows' => 6
            ]
        ));

        $page->add_field(new Media(
            'media',
            'Media',
            'content',
            'filters'
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
}
