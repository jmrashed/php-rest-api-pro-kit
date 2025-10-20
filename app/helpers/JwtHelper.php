<?php

namespace App\Helpers;

use App\Config\Env;

class JwtHelper
{
    private static $secretKey;
    private static $algorithm = 'HS256'; // HMAC-SHA256

    public static function init()
    {
        self::$secretKey = Env::get('JWT_SECRET_KEY', 'your_super_secret_key');
    }

    public static function generateToken(array $payload)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => self::$algorithm]);
        $payload = json_encode($payload);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function validateToken($jwt)
    {
        list($header, $payload, $signature) = explode('.', $jwt);

        $expectedSignature = hash_hmac('sha256', $header . "." . $payload, self::$secretKey, true);
        $base64UrlExpectedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));

        if ($base64UrlExpectedSignature !== $signature) {
            return false; // Invalid signature
        }

        $decodedPayload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $payload)), true);

        // Check expiration time if 'exp' is present
        if (isset($decodedPayload['exp']) && $decodedPayload['exp'] < time()) {
            return false; // Token expired
        }

        return $decodedPayload;
    }
}