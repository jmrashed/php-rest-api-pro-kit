<?php

namespace App\Core;

class Autoloader
{
    public function register()
    {
        spl_autoload_register([$this, 'autoload']);
    }

    public function autoload($className)
    {
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        $file = __DIR__ . '/../' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
}