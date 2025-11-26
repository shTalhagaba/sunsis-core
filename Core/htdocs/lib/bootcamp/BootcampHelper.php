<?php

class BootcampHelper
{
    public static function isValidBootcampRegistrationUrl(PDO $link, $key)
    {
        return DAO::getSingleValue($link, "SELECT id FROM registrations WHERE MD5( CONCAT(registrations.id, '_sunesis_bootcamp_registration_url') ) = '{$key}'");
    }

    public static function getBootcampRegistrationUrl($id)
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $path = '/do.php';
        $key = md5($id . '_sunesis_bootcamp_registration_url');

        return "{$protocol}://{$host}{$path}?_action=bc_registration&key={$key}";
    }
}
