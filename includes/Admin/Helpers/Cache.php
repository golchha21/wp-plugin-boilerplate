<?php

namespace PluginBoilerplate\Admin\Helpers;

class Cache
{
    public static function write($key, $value)
    {
        set_transient($key . '_cache', $value, DAY_IN_SECONDS);
    }

    public static function read($key)
    {
        return get_transient($key . '_cache');
    }

    public static function clear($key)
    {
        delete_transient($key . '_cache');
    }
}
