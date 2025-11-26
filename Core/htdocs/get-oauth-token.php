<?php

//namespace PHPMailer\PHPMailer;

require 'vendor/autoload.php';

session_start();

/**
 * PHPMailer - PHP email creation and transport class.
 * PHP Version 5.5
 * @package PHPMailer
 * @see https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 * @author Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 * @author Brent R. Matzelle (original founder)
 * @copyright 2012 - 2020 Marcus Bointon
 * @copyright 2010 - 2012 Jim Jagielski
 * @copyright 2004 - 2009 Andy Prevost
 * @license https://www.gnu.org/licenses/old-licenses/lgpl-2.1.html GNU Lesser General Public License
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * Get an OAuth2 token from an OAuth2 provider.
 * * Install this script on your server so that it's accessible
 * as [https/http]://<yourdomain>/<folder>/get_oauth_token.php
 * e.g.: http://localhost/phpmailer/get_oauth_token.php
 * * Ensure dependencies are installed with 'composer install'
 * * Set up an app in your Google/Microsoft account
 * * Set the script address as the app's redirect URL
 * If no refresh token is obtained when running this file,
 * revoke access to your app and run the script again.
 */

/**
 * Aliases for League Provider Classes
 * Make sure you have added these to your composer.json and run `composer install`
 * Plenty to choose from here:
 * @see https://oauth2-client.thephpleague.com/providers/thirdparty/
 */

//@see https://github.com/thephpleague/oauth2-google
use League\OAuth2\Client\Provider\Google;

//@see https://github.com/stevenmaguire/oauth2-microsoft
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;

//@see https://github.com/greew/oauth2-azure-provider
use Greew\OAuth2\Client\Provider\Azure;

$authenticated = $_SESSION['authenticated'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    $password = $_POST['password'] ?? '';
    // Validate password
    if ($password === date('dmy') || $password === date('jny') || $password === date('dmY') || $password === date('jnY')) {
        $authenticated = true;
        $_SESSION['authenticated'] = $authenticated;
    } else {
        $error = 'Incorrect code!';
    }
}
?>

<?php if (!$authenticated) { ?>
    <html>
    <body>
    <h2>Enter access code to continue</h2>
    <form method="post">
        <div>
            <input type="text" name="password" id="password" placeholder="Enter code" required>
            <button type="submit">Submit</button>
        </div>
        <?php if (!empty($error)) {
            echo "<div style='color:red;'>$error</div>";
        } ?>
    </form>
    <script>
        document.getElementById('password').addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
    </body>
    </html>
    <?php exit;
} ?>

<?php

$email = getenv('AZURE_OFFICE365_EMAIL');
$clientId = getenv('AZURE_CLIENT_ID');
$tenantId = getenv('AZURE_TENANT_ID');
$clientSecret = getenv('AZURE_CLIENT_SECRET');

//If this automatic URL doesn't work, set it yourself manually to the URL of this script
$redirectUri = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

$params = [
        'clientId' => $clientId,
        'clientSecret' => $clientSecret,
        'redirectUri' => $redirectUri,
        'tenantId' => $tenantId,
        'accessType' => 'offline'
];

$provider = new Azure($params);
$options = [
        'scope' => [
                'https://outlook.office.com/SMTP.Send',
                'offline_access'
        ]
];

if (!isset($_GET['code'])) {
    //If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl($options);
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);
    exit;
    //Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
} else {
    $path = __DIR__ . '/auth.json';
    if (!file_exists($path)) {
        file_put_contents($path, json_encode(["azure" => ['refresh_token' => '']]));
    }

    $authFileContent = json_decode(@file_get_contents($path), true);

    //Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);

    $authFileContent['azure'] = ['refresh_token' => $token->getRefreshToken()];

    file_put_contents($path, json_encode($authFileContent));

    //Use this to interact with an API on the users behalf
    //Use this to get a new access token if the old one expires
    echo 'Token generated successfully.';
}
