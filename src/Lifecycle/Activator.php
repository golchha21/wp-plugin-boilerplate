<?php

namespace WPPluginBoilerplate\Lifecycle;

use WPPluginBoilerplate\Plugin;

class Activator {

	public static function activate(): void {

		// Core plugin meta
		add_option( Plugin::prefix() . 'version', Plugin::version() );

		// Feature flags
		add_option( Plugin::prefix() . 'show_tabs_as_submenu', false );

		// Settings container (future-safe)
		if ( ! get_option( Plugin::prefix() . '_settings' ) ) {
			add_option( Plugin::prefix() . '_settings', [] );
		}
	}
}
