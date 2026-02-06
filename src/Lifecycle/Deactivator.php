<?php

namespace WPPluginBoilerplate\Lifecycle;

use WPPluginBoilerplate\Plugin;

class Deactivator {

	public static function deactivate(): void {

		/**
		 * Intentionally do NOT delete options here.
		 * Only runtime cleanup should live here.
		 */

		do_action(  Plugin::prefix() . 'deactivated' );
	}
}
