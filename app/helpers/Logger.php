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

    public static function log($message, $level = 'INFO', array $context = [])
    {
        if (!self::$logFile) {
            self::init();
        }

        $timestamp = date('Y-m-d H:i:s');
        $logEntry = [
            'timestamp' => $timestamp,
            'level' => $level,
            'message' => $message,
            'context' => $context
        ];

        file_put_contents(self::$logFile, json_encode($logEntry) . PHP_EOL, FILE_APPEND);
    }

    public static function info($message, array $context = [])
    {
        self::log($message, 'INFO', $context);
    }

    public static function warning($message, array $context = [])
    {
        self::log($message, 'WARNING', $context);
    }

    public static function error($message, array $context = [])
    {
        self::log($message, 'ERROR', $context);
    }

    public static function debug($message, array $context = [])
    {
        if (Env::get('APP_DEBUG', false)) {
            self::log($message, 'DEBUG', $context);
        }
    }
}