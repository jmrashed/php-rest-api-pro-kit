<?php

namespace App\Helpers;

class DateHelper
{
    public static function now($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    public static function format($date, $format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($date));
    }

    public static function diffForHumans($date)
    {
        $diff = time() - strtotime($date);
        
        if ($diff < 60) return 'just now';
        if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
        if ($diff < 2592000) return floor($diff / 86400) . ' days ago';
        
        return self::format($date, 'M j, Y');
    }

    public static function addDays($date, $days)
    {
        return date('Y-m-d H:i:s', strtotime($date . " +{$days} days"));
    }
}