<?php
namespace Middlewares;

use Helpers\Response;
use Helpers\TokenHelper;

class AuthMiddleware
{
    public static function authenticate($config)
    {
        $token = TokenHelper::getBearerToken();

        if (!$token) 
        {
            self::unauthorizedResponse("Authorization header not found or token is missing");
        }

        if (TokenHelper::validateToken($token, $config['secret_key'])) 
        {
            return true;
        } 
        else 
        {
            self::unauthorizedResponse("Invalid token");
        }
    }

    private static function unauthorizedResponse($message)
    {
        http_response_code(401);
        Response::error($message, 401);
        exit();
    }


}
