<?php
class Emailer
{
	public static function notification_email($to, $from, $reply_to, $subject, $plain_text, $html, array $files = array(), array $extra_headers = array())
	{
		$from = "no-reply@perspective-uk.com";
		// Clean recipients
		if(is_array($to)){
			$to = implode(', ', $to);
		}

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";
		//if($reply_to != '')
		//	$headers .= "Reply-To: {$reply_to}\r\n";

		$message = $plain_text == '' ? $html : $plain_text;

		$params = "-f no-reply@perspective-uk.com";

		if(mail($to, $subject, $message, $headers) )
			return true;
		else
			return false;
	}

	public static function html_mail($to, $from, $subject, $plain_text, $html, array $files = array(), array $extra_headers = array())
	{
		// Clean recipients
		if(is_array($to)){
			$to = implode(', ', $to);
		}

		if(preg_match('/<(.*@.*)>/', $from, $matches))
		{
			$envelope_from = $matches[1];
		}
		else
		{
			$envelope_from = $from;
		}

		//$reply_to = "inaam.azmat@perspective-uk.com";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";
		//if($from != '')
		//	$headers .= "Reply-To: {$from}\r\n";
		//$headers .= "Cc: <".$reply_to.">\r\n";

		$message = $plain_text == '' ? $html : $plain_text;

		$params = "-f no-reply@perspective-uk.com";

		if(mail($to, $subject, $message, $headers, $params ) )
			return true;
		else
			return false;
	}

}
