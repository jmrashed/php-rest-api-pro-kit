<?php

namespace App\Config;

class Env
{
    protected static $variables = [];

    public static function load($path)
    {
        if (!file_exists($path)) {
            throw new \Exception("Environment file not found at {$path}");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            self::$variables[trim($name)] = trim($value);
        }
    }

    public static function get($key, $default = null)
    {
        return self::$variables[$key] ?? $default;
    }

    public static function getLogLevel()
    {
        return self::get('LOG_LEVEL', 'INFO');
    }

    public static function isDebugBarEnabled(): bool
    {
        return self::get('DEBUGBAR_ENABLED', 'false') === 'true';
    }

    public static function getDebugBarAllowedIps(): array
    {
        $ips = self::get('DEBUGBAR_ALLOWED_IPS', '');
        return $ips ? explode(',', $ips) : [];
    }
}