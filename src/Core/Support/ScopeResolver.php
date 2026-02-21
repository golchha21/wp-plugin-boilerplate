<?php
// src/Settings/Support/ScopeResolver.php
namespace WPPluginBoilerplate\Core\Support;

use WPPluginBoilerplate\Settings\Contracts\ScopedContract;

final class ScopeResolver
{
	public static function resolve(object $tab): string
	{
		return $tab instanceof ScopedContract
			? $tab::scope()
			: 'site';
	}
}
