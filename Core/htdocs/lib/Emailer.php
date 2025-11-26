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
		$headers .= in_array(DB_NAME, ["am_duplex"]) ? "From: EV Training <no-reply@perspective-uk.com>\r\n" : "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";
		//if($reply_to != '')
		//	$headers .= "Reply-To: {$reply_to}\r\n";

		$message = $plain_text == '' ? $html : $plain_text;

		$params = "-f no-reply@perspective-uk.com";

		if(mail($to, $subject, $message, $headers, $params ) )
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
		$headers .= in_array(DB_NAME, ["am_duplex"]) ? "From: EV Training <no-reply@perspective-uk.com>\r\n" : "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";
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

	public static function notification_email_review($to, $from, $reply_to, $subject, $plain_text, $html, array $files = array(), array $extra_headers = array())
	{
		// Clean recipients
		if(is_array($to)){
			$to = implode(', ', $to);
		}

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";
		//if($reply_to != '')
		//	$headers .= "Reply-To: {$reply_to}\r\n";
        if(DB_NAME!='am_sd_demo')
		    $headers .= "Cc: <".$reply_to.">\r\n";

		$message = $plain_text == '' ? $html : $plain_text;

		$params = "-f no-reply@perspective-uk.com";

		if(mail($to, $subject, $message, $headers, $params ) )
			return true;
		else
			return false;
	}
    public static function notification_email_review_auto($to, $from, $reply_to, $subject, $plain_text, $html, array $files = array(), array $extra_headers = array())
    {
        // Clean recipients
        if(is_array($to)){
            $to = implode(', ', $to);
        }

        //$bcc = "khushnood.khan@perspective-uk.com;Lauren.Fearon@baltictraining.com";

        $message = $plain_text == '' ? $html : $plain_text;

        $params = "-f no-reply@perspective-uk.com";

        $bcc = "Sunesisautoemails@balticapprenticeships.com;khushnood.khan@perspective-uk.com";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";
        $headers .= "Cc: {$reply_to}\r\n";
        $headers .= "Bcc: {$bcc}\r\n";

        if(mail($to, $subject, $message, $headers, $params ) )
            return true;
        else
            return false;
    }

    public static function notification_email_review_auto_test($to, $from, $reply_to, $subject, $plain_text, $html, array $files = array(), array $extra_headers = array())
    {

        // Clean recipients
        if(is_array($to)){
            $to = implode(', ', $to);
        }

        //$bcc = "khushnood.khan@perspective-uk.com;Lauren.Fearon@baltictraining.com";

        $message = $plain_text == '' ? $html : $plain_text;

        $params = "-f no-reply@perspective-uk.com";

        $bcc = "k-k78@hotmail.com";
        $reply_to = "khushnood.khan@perspective-uk.com";
        $to = "khushnood.khan@perspective-uk.com";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";
        $headers .= "Cc: {$reply_to}\r\n";
        $headers .= "Bcc: {$bcc}\r\n";

        if(mail($to, $subject, $message, $headers, $params ) )
        {
            return true;
        }
        else
            return false;
    }

    public static function notification_email_employer_reference_intro($to, $from, $reply_to, $subject, $plain_text, $html, array $files = array(), array $extra_headers = array())
    {
        // Clean recipients
        if(is_array($to)){
            $to = implode(', ', $to);
        }

        //$bcc = "khushnood.khan@perspective-uk.com;Lauren.Fearon@baltictraining.com";
        //$bcc = "Lauren.Fearon@baltictraining.com";
        $bcc = "khushnood.khan@perspective-uk.com";
        $reply_to = "khushnood.khan@perspective-uk.com";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";
        $headers .= "Cc: {$reply_to}\r\n";
        $headers .= "Bcc: {$bcc}\r\n";

        $message = $plain_text == '' ? $html : $plain_text;

        $params = "-f no-reply@perspective-uk.com";

        $to = "khushnood.khan@perspective-uk.com";

        $files = array(
            DATA_ROOT.'/uploads/'.DB_NAME.'/employer_reference/1.2 Employer Intro Template.docx',
            DATA_ROOT.'/uploads/'.DB_NAME.'/employer_reference/1.3 Employer Reference Guidance Q&A.docx'
        );
        Emailer::multi_attach_mail($to, $subject, $message, "no-reply@perspective-uk.com", "Baltic Training", $files);
    }

    public static function multi_attach_mail($to, $subject, $message, $senderEmail, $senderName, $files = array()){

        $from = $senderName." <".$senderEmail.">";
        $headers = "From: $from";

        // Boundary
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

        // Headers for attachment
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

        // Multipart boundary
        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
            "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";

        // Preparing attachment
        if(!empty($files)){
            for($i=0;$i<count($files);$i++){
                if(is_file($files[$i])){
                    $file_name = basename($files[$i]);
                    $file_size = filesize($files[$i]);

                    $message .= "--{$mime_boundary}\n";
                    $fp =    @fopen($files[$i], "rb");
                    $data =  @fread($fp, $file_size);
                    @fclose($fp);
                    $data = chunk_split(base64_encode($data));
                    $message .= "Content-Type: application/octet-stream; name=\"".$file_name."\"\n" .
                        "Content-Description: ".$file_name."\n" .
                        "Content-Disposition: attachment;\n" . " filename=\"".$file_name."\"; size=".$file_size.";\n" .
                        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
                }
            }
        }

        $message .= "--{$mime_boundary}--";
        $returnpath = "-f" . $senderEmail;

        // Send email
        $mail = @mail($to, $subject, $message, $headers, $returnpath);

        // Return true, if email sent, otherwise return false
        if($mail){
            return true;
        }else{
            return false;
        }
    }

    public static function weekly_emails_city_skills($to, $from, $reply_to, $subject, $plain_text, $html, array $files = array(), array $extra_headers = array())
    {
        // Clean recipients
        if(is_array($to)){
            $to = implode(', ', $to);
        }

        //$bcc = "khushnood.khan@perspective-uk.com;Lauren.Fearon@baltictraining.com";

        $message = $plain_text == '' ? $html : $plain_text;

        $params = "-f no-reply@perspective-uk.com";

        $bcc = "khushnood.khan@perspective-uk.com";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Apprenticeships <no-reply@perspective-uk.com>\r\n";
        //$headers .= "Cc: {$reply_to}\r\n";
        //$headers .= "Bcc: {$bcc}\r\n";

        if(mail($to, $subject, $message, $headers, $params ) )
            return true;
        else
            return false;
    }

    // https://learn.microsoft.com/en-us/graph/api/user-sendmail?view=graph-rest-1.0&tabs=php
    public static function sendMail($to, $subject, $content)
    {

        $email = getenv('AZURE_FROM_EMAIL');
        $clientId = getenv('AZURE_CLIENT_ID');
        $tenantId = getenv('AZURE_TENANT_ID');
        $clientSecret = getenv('AZURE_CLIENT_SECRET');

        $tokenRequestContext = new \Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext(
            $tenantId,
            $clientId,
            $clientSecret
        );

        $scopes = ['https://graph.microsoft.com/.default'];

        $graphServiceClient = new \Microsoft\Graph\GraphServiceClient($tokenRequestContext, $scopes);
        try {
            if (is_array($to)){
                $toEmail = $to['email'] ?? '';
                $toName = $to['name'] ?? '';
            } else{
                $toEmail = $to;
                $toName = '';
            }

            $sender = new \Microsoft\Graph\Generated\Models\EmailAddress();
            $sender->setAddress($email);
            $sender->setName('Sunesis');
            $fromRecipient = new \Microsoft\Graph\Generated\Models\Recipient();
            $fromRecipient->setEmailAddress($sender);

            $recipients = [];

            $recipientEmail = new \Microsoft\Graph\Generated\Models\EmailAddress();
            $recipientEmail->setAddress($toEmail);
            $recipientEmail->setName($toName);
            $toRecipient = new \Microsoft\Graph\Generated\Models\Recipient();
            $toRecipient->setEmailAddress($recipientEmail);
            $recipients[] = $toRecipient;

            $emailBody = new \Microsoft\Graph\Generated\Models\ItemBody();
            $emailBody->setContent($content);
            $emailBody->setContentType(new \Microsoft\Graph\Generated\Models\BodyType('html'));

            $message = new \Microsoft\Graph\Generated\Models\Message();
            $message->setSubject($subject);
            $message->setFrom($fromRecipient);
            $message->setToRecipients($recipients);
            $message->setBody($emailBody);

            $requestBody = new \Microsoft\Graph\Generated\Users\Item\SendMail\SendMailPostRequestBody();
            $requestBody->setMessage($message);

            $graphServiceClient
                ->users()
                ->byUserId($email)
                ->sendMail()
                ->post($requestBody)
                ->wait();

            return  true;

        } catch (\Microsoft\Kiota\Abstractions\ApiException $e) {
            return false;
        }
    }
}
