<?php
class change_password implements IAction
{
	public function execute(PDO $link)
	{
		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
		$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
		$password1 = isset($_REQUEST['password1']) ? $_REQUEST['password1'] : '';
		$password2 = isset($_REQUEST['password2']) ? $_REQUEST['password2'] : '';
		$firstnames = isset($_REQUEST['firstnames']) ? $_REQUEST['firstnames'] : '';
		$surname = isset($_REQUEST['surname']) ? $_REQUEST['surname'] : '';

		$user = $_SESSION['user']; /* @var $user User */

		// If the user has supplied data, process the request, otherwise
		// provide the user with a form to complete
		if($username != '')
		{
			if($password1 != '')
			{
				if($password2 == '')
				{
					throw new Exception("Both the new password and its confirmation must be supplied");
				}

				// Check the password fields match
				if($password1 != $password2)
				{
					throw new Exception("The contents of the password field and the password confirmation field do not match");
				}

				// Check password length
				$len = strlen($password1);
				if($len < 8 || $len > 50)
				{
					throw new Exception("Passwords must be between 8 and 50 characters long");
				}

				// Check user's current password
				if( sha1($password) != $user->pwd_sha1)
				{
					throw new Exception("The current password you supplied is incorrect.");
				}

				$user->password = $password1;
			}
			else
			{
				$user->password = NULL;
			}

			$user->save($link);

			if(IS_AJAX)
			{
				header('Content-Type: text/plain; charset=ISO-8859-1');
				echo '1'; // return something, anything
			}
			else
			{
				http_redirect('do.php?_action=home_page.php');
			}
		}
		else
		{
			// Present user with the "change password" form
			include('tpl_change_password.php');
		}
	}
}
?>
