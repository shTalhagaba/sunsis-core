<?php

namespace Helpers;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $host = DB_HOST;
        $dbname = DB_NAME;
        $username = DB_USER;
        $password = DB_PASSWORD;

        $options = [];

        // Enable SSL if configured
        $sslCa = getenv('PERSPECTIVE_DB_SSL_CA');
        if ($sslCa && file_exists($sslCa)) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }

        try {
            $this->connection = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password, $options);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
