<?php

namespace App\Middleware;

use App\Core\Response;
use App\Helpers\Validator;

class ValidationMiddleware extends Middleware
{
    private $rules;

    public function __construct($rules = [])
    {
        $this->rules = $rules;
    }

    public function handle($request, $next)
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? [];
        
        $validator = new Validator();
        $errors = $validator->validate($data, $this->rules);
        
        if (!empty($errors)) {
            return Response::json(['errors' => $errors], 422);
        }
        
        return $next($request);
    }
}