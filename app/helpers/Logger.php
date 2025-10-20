<?php

namespace App\Helpers;

use App\Config\Env;

class Logger
{
    private static $logFile;

    public static function init()
    {
        $logPath = Env::get('LOG_PATH', 'storage/logs');
        if (!is_dir($logPath)) {
            mkdir($logPath, 0777, true);
        }
        self::$logFile = $logPath . '/app.log';
    }

    public static function log($message, $level = 'INFO')
    {
        if (!self::$logFile) {
            self::init();
        }

        $timestamp = date('Y-m-d H:i:s');
        $logEntry = sprintf("[%s] [%s] %s%s", $timestamp, $level, $message, PHP_EOL);

        file_put_contents(self::$logFile, $logEntry, FILE_APPEND);
    }

    public static function info($message)
    {
        self::log($message, 'INFO');
    }

    public static function warning($message)
    {
        self::log($message, 'WARNING');
    }

    public static function error($message)
    {
        self::log($message, 'ERROR');
    }

    public static function debug($message)
    {
        if (Env::get('APP_DEBUG', false)) {
            self::log($message, 'DEBUG');
        }
    }
}