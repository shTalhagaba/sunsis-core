<?php

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'] ?? '';
    if ($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<a href='" . $_SERVER['PHP_SELF'] . "' >Go Back</a><br><br>";
            try {
                $result = sendMail($email, 'Test Mail', 'Test mail body');
                if ($result) {
                    echo 'mail sent';
                } else {
                    echo 'mail not sent';
                }
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
            die;
        } else {
            $error = 'Invalid email';
        }
    } else {
        $error = 'Email is required';
    }
}

function sendMail($to, $subject, $content)
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
?>
<html lang="en">
<head>
    <title>Test Mailer</title>
</head>
<body>
<form method="post" style="display: block">
    <div>
        <input type="email" placeholder="Enter email" name="email">
        <button type="submit">Send</button>
    </div>
    <?php if (!empty($error)) {
        echo "<div style='color:red;'>$error</div>";
    } ?>
</form>
</body>
</html>
