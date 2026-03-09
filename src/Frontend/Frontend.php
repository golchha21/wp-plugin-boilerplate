<?php

namespace WPPluginBoilerplate\Frontend;

use WPPluginBoilerplate\Loader;
use WPPluginBoilerplate\Plugin;

class Frontend
{
	public function register(Loader $loader): void
	{
		// Public hooks go here.

		$loader->action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
	}

	public function enqueue_assets(): void
	{
		wp_enqueue_script(Plugin::prefix() . 'public', Plugin::url() . 'assets/frontend/public.js', [], Plugin::version(), true);
	}
}
