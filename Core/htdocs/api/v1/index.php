<?php
require __DIR__ . '/autoload.php';
require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/config/config.php';
$routes = require __DIR__ . '/routes.php';

use Helpers\TokenHelper;
use Middlewares\AuthMiddleware;
use Helpers\Database;
use Helpers\Response;

header("Content-Type: application/json");


// IP Whitelisting
$allowedIPs = ['123.456.789.0', '987.654.321.0', '18.171.199.23'];
if (!isIPAllowed($allowedIPs)) 
{
    Response::error('Access denied', 403);
}

if(getRoute() == '/authentication')
{
    authenticate();
    exit();
}

// Protected endpoints
AuthMiddleware::authenticate($config);
handleRequest($routes);

function authenticate()
{
    $headers = request()->getHeaders();
    $config = config();

    if (!isset($headers['Sunesis-Api-Key'])) 
    {
        Response::error('Missing API key', 401);
    }

    if (!isset($headers['Sunesis-Api-Key']) || $headers['Sunesis-Api-Key'] !== $config['sunesis_api_key']) 
    {
        Response::error('Invalid API key', 401);
    }

    $token = TokenHelper::generateToken(
        //['api_key' => $headers['Sunesis-Api-Key']], 
        ['client' => DB_NAME], 
        $config['secret_key'], 
        $config['token_expiration']
    );

    Response::success(['token' => $token], 'Authentication successful');
}

// Helper functions for routing
function getRoute() 
{
    $uri = parse_url(request()->getUri(), PHP_URL_PATH);
    $uri = str_replace('/api/v1', '', $uri);
    return $uri;
}

// Function to match the request with the routes and extract parameters
function matchRoute($routes, $method, $route) 
{
    foreach ($routes[$method] as $pattern => $handler) 
    {
        $pattern = '#^' . $pattern . '/?$#'; // Allow optional trailing slash
        if (preg_match($pattern, $route, $matches)) 
        {
            array_shift($matches); // Remove the full match
            return [$handler, $matches];
        }
    }
    return null;
}

// Handle the request
function handleRequest($routes) 
{
    $route = getRoute();
    $method = request()->getMethod();

    if (!$routeMatch = matchRoute($routes, $method, $route)) 
    {
        Response::error('Request method or URL not allowed', 404);
    }

    $handler = $routeMatch[0];
    $params = $routeMatch[1];
    
    if (is_array($handler)) 
    {
        // Instantiate the controller and call the method with parameters
        // $controller = new $handler[0]();
        // $action = $handler[1];
        // $controller->$action(request(), ...$params);
        $controller = new $handler[0]();
        $action = $handler[1];
        $params = array_merge([request()], $params);
        call_user_func_array([$controller, $action], $params);
    }
    else 
    {
        // Call the function
        $handler();
    }
}

function request()
{
    return HttpRequest::getInstance();
}

function config()
{
    $link = Database::getInstance()->getConnection();
    $config = require __DIR__ . '/config/config.php';
    $config['sunesis_api_key'] = DAO::getSingleValue($link, "SELECT `configuration`.`value` FROM configuration WHERE `configuration`.`entity` = 'SUNESIS-API-KEY'");

    return $config;
}

function isIPAllowed($allowedIPs) 
{
    return true;
    $clientIP = $_SERVER['REMOTE_ADDR'];
    return in_array($clientIP, $allowedIPs);
}

