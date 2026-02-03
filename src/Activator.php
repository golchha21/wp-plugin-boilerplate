<?php

namespace WPPluginBoilerplate;

class Activator
{
    public static function activate(): void
    {
        // Place setup logic here (DB tables, options, etc.)
        flush_rewrite_rules();
    }
}
