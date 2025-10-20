<?php

namespace App\Helpers;

class StringHelper
{
    public static function slug($string)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }

    public static function random($length = 10)
    {
        return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $length)), 0, $length);
    }

    public static function truncate($string, $length = 100, $suffix = '...')
    {
        return strlen($string) > $length ? substr($string, 0, $length) . $suffix : $string;
    }

    public static function sanitize($string)
    {
        return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
    }
}