<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;

abstract class Middleware
{
    abstract public function handle(Request $request, callable $next);
}