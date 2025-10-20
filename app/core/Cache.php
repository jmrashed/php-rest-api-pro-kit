<?php

namespace App\Core;

class Cache
{
    private static $cachePath = CACHE_PATH;

    public static function get($key)
    {
        $file = self::$cachePath . '/' . md5($key) . '.cache';
        if (!file_exists($file)) {
            return null;
        }

        $data = unserialize(file_get_contents($file));
        if ($data['expires'] < time()) {
            unlink($file);
            return null;
        }

        return $data['value'];
    }

    public static function set($key, $value, $ttl = 3600)
    {
        if (!is_dir(self::$cachePath)) {
            mkdir(self::$cachePath, 0755, true);
        }

        $file = self::$cachePath . '/' . md5($key) . '.cache';
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];

        file_put_contents($file, serialize($data));
    }

    public static function delete($key)
    {
        $file = self::$cachePath . '/' . md5($key) . '.cache';
        if (file_exists($file)) {
            unlink($file);
        }
    }
}