<?php

namespace Helpers;

use HttpRequest;

class TokenHelper 
{
    public static function generateToken($data, $secretKey, $expiration)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(array_merge($data, [
            'iat' => time(),
            'exp' => time() + $expiration
        ]));

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function validateToken($token, $secretKey)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) 
        {
            return false;
        }

        $header = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[0])), true);
        $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);
        $signatureProvided = $parts[2];

        $expiration = $payload['exp'];
        if ($expiration < time()) 
        {
            return false;
        }

        $base64UrlHeader = $parts[0];
        $base64UrlPayload = $parts[1];
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlSignature === $signatureProvided;
    }

    public static function getBearerToken() 
    {
        $request = new HttpRequest();
        $authorization = $request->getHeader('Authorization');

        if (!empty($authorization)) 
        {
            if (preg_match('/Bearer\s(\S+)/', $authorization, $matches)) 
            {
                return $matches[1];
            }
        }
        return null;
    }

    public static function pre($array)
    {
        echo '<div style="background: #FFBABA; padding: 20px 20px; border: 1px solid #ff0000;">';
        echo '<pre style="padding:0;margin:0;">';
        print_r($array);
        echo '</pre>';
        echo '</div>';
        die;
    }

    public static function pr($array)
    {
        echo '<div style="background: #BDE5F8; padding: 20px 20px; border: 1px solid #00529B;">';
        echo '<pre style="padding:0;margin:0;">';
        print_r($array);
        echo '</pre>';
        echo '</div>';
    }
}