<?php

namespace WPPluginBoilerplate\Support\Settings\Contracts;

interface SchemaContract
{
    public static function optionKey(): string;
    public static function definition(): array;
    public static function defaults(): array;
}
