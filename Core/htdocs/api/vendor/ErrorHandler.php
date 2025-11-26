<?php

class ErrorHandler
{
    /**
     * @param \Exception|\Error $exception
     */
    public static function handleException($exception)
    {
        
        if(SOURCE_LOCAL && $exception instanceof Error)
        {
            self::handleError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
        }

        http_response_code(500);

        if(SOURCE_LOCAL)
        {
            echo json_encode([
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }
        else
        {
            echo json_encode([
                'code' => $exception->getCode(),
                'message' => 'Internal Server Error',
            ]);
    
            self::main_error_routine($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
        }
    }

    public static function handleError(
        $errno,
        $errstr,
        $errfile,
        $errline
    )
    {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    private static function main_error_routine($code = '', $message = '', $file = '', $line = '', $trace = '', $extra_info = '')
    {
	    // Don't use custom code to report any errors from code in here
        restore_error_handler();

        // Log the error in the Apache error log
        if(strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') === false)
        {
            error_log("$file($line): $message");
        }

        // Email the error
        self::email_support($code, $message, $file, $line, $trace, $extra_info);
    }

    private static function email_support($code, $message, $file, $line, $trace, $extra_info = '')
    {
        self::logError($code, $message, $file, $line, $trace);
        
        if( SOURCE_LOCAL || PHP_OS == "WINNT") 
        {
            return;
        }

        // Make filepaths relative to the application root -- no need to include the full path
        $file = preg_replace('#/srv/www/.*?/htdocs/#', '', $file);
        $trace = preg_replace('#/srv/www/.*?/htdocs/#', '', $trace);

	    $subject = "Error: ".basename($file)." (".$line.")";
	    $from_parts = explode('.',$_SERVER['SERVER_NAME']);
	    $from = strtoupper($from_parts[0])." <log@sunesis.uk.net>";
	    $to = "khushnood.khan@perspective-uk.com,inaam.azmat@perspective-uk.com";

	    $dbname = DB_NAME;
	    $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";
	    $method = isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "";
	    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? Text::formatUserAgent($_SERVER['HTTP_USER_AGENT']) : "";
	    $remote_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
	    $date = new DateTime("now");
	    $date = $date->format("d/m/Y H:i:s");

	$text = <<<HEREDOC
Error: {$file}({$line})
URL: {$url}
Method: {$method}
Date: {$date}
DB: {$dbname}
User Agent: {$user_agent}
IP: {$remote_addr}


$message

{$file}({$line})
$trace
HEREDOC;

        self::html_mail($to, $from, $subject, $text, array("Importance: high"));
    }

    private static function html_mail($to, $from, $subject, $plain_text, array $extra_headers = array())
    {
        // Clean recipients
        if(is_array($to))
        {
            $to = implode(', ', $to);
        }

        // Create the "MAIL From:" address for the SMTP envelope
        if(preg_match('/<(.*@.*)>/', $from, $matches))
        {
            $envelope_from = $matches[1];
        }
        else
        {
            $envelope_from = $from;
        }

        // Add custom headers
        $boundary = md5(uniqid(time()));
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "From: ".$from."\r\n";
        $headers .= "Subject: $subject\r\n";
        foreach($extra_headers as $header)
        {
            $headers .= $header."\r\n"; // custom header
        }
        $headers .= "Content-Type: multipart/alternative;\r\n boundary=" . $boundary . "\r\n";

        $message = "This is a MIME encoded message.\r\n";

        $message .= "\r\n--" . $boundary . "\r\n";
        // $message .= "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n";
        $message .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $message .= chunk_split(base64_encode($plain_text));

        mail($to, $subject, $message, $headers, '-f'.$envelope_from.' -ODeliveryMode=b');
    }

    private static function logError($code, $message, $file, $line, $trace)
    {
        try
		{
			$error_link = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_PORT, DB_USER, DB_PASSWORD);

			$usr = "'API Call'";
			$cde = $code !== '' ? "'".addslashes($code)."'" : "NULL";
			$msg = $message !== '' ? "'".addslashes($message)."'" : "NULL";
			$fl = $file !== '' ? "'".addslashes(basename($file))."'" : "NULL";
			$ln = $line !== '' ? "'".addslashes($line)."'" : "NULL";
			$trc = $trace !== '' ? "'".addslashes($trace)."'" : "NULL";
			$sql = <<<HEREDOC
INSERT INTO error_log (`username`, `code`, `message`, `file1`, `line`, `trace`)
	VALUES ($usr, $cde, $msg, $fl, $ln, $trc);
HEREDOC;

			@$error_link->exec($sql);
			$error_link = null;
		}
		catch(Exception $e)
		{
			// Do nothing
		}
    }
}