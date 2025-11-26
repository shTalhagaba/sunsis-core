<?php

class HttpRequest
{
    private $headers;
    private $method;
    private $uri;
    private $queryParams;
    private $bodyParams;
    private $cookies;
    private $files;
    private $serverParams;
    private static $instance = null;


    public function __construct()
    {
        $this->headers = $this->getAllHeaders();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->queryParams = $_GET;
        $this->bodyParams = $this->getBodyParameters();
        $this->cookies = $_COOKIE;
        $this->files = $_FILES;
        $this->serverParams = $_SERVER;
    }

    public static function getInstance()
    {
        if (!self::$instance) 
        {
            self::$instance = new HttpRequest();
        }
        return self::$instance;
    }

    private function getAllHeaders()
    {
        if (!function_exists('getallheaders')) {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[ str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))) ] = $value;
                }
            }
            return $headers;
        } else {
            $headers = getallheaders();
            $formattedHeaders = [];
            foreach($headers AS $key => $value) {
                $key = str_replace('_', ' ', $key);
                $key = str_replace('-', ' ', $key);
                $key = strtolower($key);
                $key = ucwords($key);
                $key = str_replace(' ', '-', $key);
                $formattedHeaders[$key] = $value;
            }
            return $formattedHeaders;
        }
    }

    private function getBodyParameters()
    {
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        $bodyParams = [];

        if ($contentType === "application/json") {
            $bodyParams = json_decode(file_get_contents("php://input"), true);
        } else {
            $bodyParams = $_POST;
        }

        return $bodyParams;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    public function getBodyParams()
    {
        return $this->bodyParams;
    }

    public function getCookies()
    {
        return $this->cookies;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getServerParams()
    {
        return $this->serverParams;
    }

    public function getHeader($name)
    {
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }

    public function getQueryParam($name)
    {
        return isset($this->queryParams[$name]) ? $this->queryParams[$name] : null;
    }

    public function getBodyParam($name)
    {
        return isset($this->bodyParams[$name]) ? $this->bodyParams[$name] : null;
    }

    public function getCookie($name)
    {
        return isset($this->cookies[$name]) ? $this->cookies[$name] : null;
    }

    public function getFile($name)
    {
        return isset($this->files[$name]) ? $this->files[$name] : null;
    }
}

