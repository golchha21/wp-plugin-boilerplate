<?php

namespace WPPluginBoilerplate;

use WPPluginBoilerplate\Admin\Admin;
use WPPluginBoilerplate\I18n\I18n;
use WPPluginBoilerplate\Public\PublicPlugin;

class Plugin
{
	protected Loader $loader;

	public function __construct()
	{
		$this->loader = new Loader();
		$this->register_services();
	}

	protected function register_services(): void
	{
		(new I18n())->register($this->loader);

		if (is_admin()) {
			(new Admin())->register($this->loader);
		}

		(new PublicPlugin())->register($this->loader);
	}

	public static function prefix(): string
	{
		return WPPB_PREFIX;
	}

	public static function text_domain(): string
	{
		return WPPB_TEXT_DOMAIN;
	}

	public static function slug(): string
	{
		return WPPB_SLUG;
	}

	public static function version(): string
	{
		return WPPB_VERSION;
	}

	public static function file(): string
	{
		return WPPB_FILE;
	}

	public static function url(): string
	{
		return WPPB_URL;
	}

	public static function menu_parent(): ?string
	{
		return WPPB_MENU_PARENT;
	}

	public static function tabs_as_submenu(): bool
	{
		return WPPB_TABS_AS_SUBMENU;
	}

	public static function option_key(): string
	{
		return  self::prefix() . 'settings';
	}

	public static function cron_hook(string $name): string
	{
		return self::prefix() . $name;
	}

	public function run(): void
	{
		$this->loader->run();
	}
}
