<?php

namespace WPPluginBoilerplate\Admin;

use WPPluginBoilerplate\Admin\Contracts\AdminModule;
use WPPluginBoilerplate\Admin\Modules\SettingsModule;
use WPPluginBoilerplate\Admin\Modules\MetaBoxModule;
use WPPluginBoilerplate\Loader;
use WPPluginBoilerplate\Plugin;

class Admin
{
	protected array $modules = [];

	public function __construct()
	{
		$this->modules = [
			new SettingsModule(),
			new MetaBoxModule(),
		];
	}

	public function register(Loader $loader): void
	{
		$loader->action('admin_enqueue_scripts', $this, 'enqueueAssets');

		foreach ($this->modules as $module) {
			if ($module instanceof AdminModule) {
				$module->register($loader);
			}
		}
	}

	public function enqueueAssets(): void
	{
		\wp_enqueue_media();
		\wp_enqueue_editor();

		\wp_enqueue_style('wp-color-picker');
		\wp_enqueue_script('wp-color-picker');

		\wp_add_inline_script('wp-color-picker', "jQuery('.wppb-color-field').wpColorPicker();");

		\wp_enqueue_script(
			Plugin::prefix() . 'media',
			Plugin::url() . 'assets/admin/js/media.js',
			['jquery'],
			Plugin::version(),
			true
		);

		\wp_enqueue_script(
			Plugin::prefix() . 'repeater',
			Plugin::url() . 'assets/admin/js/repeater.js',
			['jquery'],
			Plugin::version(),
			true
		);

		\wp_enqueue_script(
			Plugin::prefix() . 'tools',
			Plugin::url() . 'assets/admin/js/tools.js',
			['jquery'],
			Plugin::version(),
			true
		);


		\wp_enqueue_script(
			Plugin::prefix() . 'metatab',
			Plugin::url() . 'assets/admin/js/metatab.js',
			['jquery'],
			Plugin::version(),
			true
		);

		\wp_enqueue_style(
			Plugin::prefix() . 'admin',
			Plugin::url() . 'assets/admin/css/admin.css',
			[],
			Plugin::version()
		);
	}
}
