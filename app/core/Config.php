<?php

namespace App\Core;

class Config
{
    private static $config = [];

    public function __construct()
    {
        $this->loadEnv();
    }

    private function loadEnv()
    {
        $envFile = ROOT_PATH . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                    list($key, $value) = explode('=', $line, 2);
                    self::$config[trim($key)] = trim($value, '"\'');
                }
            }
        }
    }

    public static function get($key, $default = null)
    {
        return getenv($key) ?: (self::$config[$key] ?? $default);
    }
}