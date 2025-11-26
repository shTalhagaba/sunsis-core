<?php

function get_db_link($argv)
{
    $host = '127.0.0.1';
    $db = null;
    $db_pwd = null;
    $db_user = null;
    if (count($argv) < 3) {
        $handle = fopen("php://stdin", "r");

        echo "\nDatabase: ";
        $db = trim(fgets($handle));

        echo "\Database User: ";
        $db_user = trim(fgets($handle));

        echo "\nDatabase Password: ";
        $db_pwd = trim(fgets($handle));

        fclose($handle);
    } else {
        $db = $argv[1];
        $db_user = $argv[2];
        $db_pwd = $argv[3];
    }

    echo "\n connection establishing";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];

    // If RDS SSL CA is provided in env, add SSL options
    $sslCa = getenv('PERSPECTIVE_DB_SSL_CA');
    if ($sslCa && file_exists($sslCa)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }

    try {
        $link = new PDO("mysql:host=" . $host . ";dbname=" . $db . ";port=3306", $db_user, $db_pwd, $options);
        $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $link->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
    } catch (Exception $e) {
        $link = null;
        die('ERROR: ' . $e->getMessage());
    }
    echo "\n connection established";

    return $link;
}


function get_x_token_id(PDO $link, $X_CustomerToken, $X_TokenIDAndTS, $remoteHost, $X_TokenID)
{
    $refresh_token = false;
    if ($X_TokenID != '') {
        $token_generation_timestamp = substr($X_TokenIDAndTS, 0, 19);
        if ($token_generation_timestamp != '') {
            $current_timestamp = date('Y-m-d H:i:s');
            $diff_in_hours = differenceInHours($token_generation_timestamp, $current_timestamp);
            if ($diff_in_hours > 23) {
                $refresh_token = true;
            }
        }
    } else {
        $refresh_token = true;
    }

    if ($refresh_token) {
        echo "\n refreshing token";

        $response = api_authentication($remoteHost, $X_CustomerToken);
        if ($response->getHttpCode() == 200) {
            $value = date('Y-m-d H:i:s') . $response->getBody();
            DAO::execute($link, "UPDATE configuration SET configuration.value = '{$value}' WHERE configuration.entity = 'onefile.X-TokenID'");
            $X_TokenID = $response->getBody();
        } else {
            $link = null;
            die("ERROR: " . $response->getBody());
        }

        echo "\n refreshed token";
    }

    return $X_TokenID;
}

function api_authentication($remoteHost, $X_CustomerToken)
{
    $restClient = new RestClient();
    $restClient->setRemoteHost($remoteHost)
        ->setUriBase('/api/v2.1/')
        ->setUseSsl(true)
        ->setUseSslTestMode(false)
        ->setHeaders([
            'X-CustomerToken' => $X_CustomerToken,
            'Content-Type' => 'application/json'
        ]);

    $response = null;
    try {
        $response = $restClient->post('Authentication', json_encode([]));
    } catch (Exception $e) {
        die("ERROR: authentication endpoint error");
    }

    return $response;
}

function differenceInHours($startdate, $enddate)
{
    $starttimestamp = strtotime($startdate);
    $endtimestamp = strtotime($enddate);
    $difference = abs($endtimestamp - $starttimestamp) / 3600;
    return $difference;
}

function formatOneFileDate($apiDate, $withTime = false)
{
    $dateTime = new DateTime($apiDate, new DateTimeZone('UTC'));

    $dateTime->setTimezone(new DateTimeZone('Europe/London'));

    return !$withTime ? $dateTime->format('Y-m-d') : $dateTime->format('Y-m-d H:i:s');
}
