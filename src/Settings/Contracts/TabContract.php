<?php

namespace WPPluginBoilerplate\Settings\Contracts;

interface TabContract
{
	public function id(): string;
	public function label(): string;
	public function render(): void;
	public function capability(): string;
}
