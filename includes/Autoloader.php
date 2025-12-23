<?php

namespace PluginBoilerplate;

class Autoloader {
    public static function register(): void
    {
        spl_autoload_register(function($class){
            $prefix = 'PluginBoilerplate\\';
            $base_dir = __DIR__ . '/';

            if (strpos($class, $prefix) !== 0) {
                return;
            }

            $relative_class = substr($class, strlen($prefix));
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        });
    }
}
