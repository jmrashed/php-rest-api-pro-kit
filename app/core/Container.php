<?php

namespace App\Core;

class Container
{
    private static $bindings = [];
    private static $instances = [];

    public static function bind($key, $resolver)
    {
        self::$bindings[$key] = $resolver;
    }

    public static function singleton($key, $resolver)
    {
        self::bind($key, $resolver);
        self::$instances[$key] = null;
    }

    public static function resolve($key)
    {
        if (isset(self::$instances[$key]) && self::$instances[$key] !== null) {
            return self::$instances[$key];
        }

        if (isset(self::$bindings[$key])) {
            $resolver = self::$bindings[$key];
            $instance = $resolver();
            
            if (array_key_exists($key, self::$instances)) {
                self::$instances[$key] = $instance;
            }
            
            return $instance;
        }

        throw new \Exception("No binding found for {$key}");
    }
}