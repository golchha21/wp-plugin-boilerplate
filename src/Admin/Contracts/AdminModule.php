<?php

namespace WPPluginBoilerplate\Admin\Contracts;

use WPPluginBoilerplate\Loader;

interface AdminModule
{
	public function register(Loader $loader): void;
}
