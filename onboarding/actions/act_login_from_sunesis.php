<?php
class login_from_sunesis implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$key = isset($_REQUEST['key']) ? trim($_REQUEST['key']) : null;

        $_SESSION = array();

        if($key)
		{
			/* @var User $user */
			$success = $this->authenticateUser($link);
			if(!is_null($success) && $success)
			{
                $id = DAO::getSingleValue($link, "SELECT id FROM users WHERE md5(pwd_sha1) = '" . (trim($key)) . "'");
                $user = User::loadFromDatabaseById($link, $id);
				$_SESSION['user'] = $user;
				$bc = new BreadCrumbs();
				$_SESSION['bc'] = $bc;

				require_once('tpl_frameset.php');
			}
			else
			{
				http_redirect("http://onboarding/do.php?_action=login");
			}

		} // End: check user's credentials
		else
		{
			http_redirect("http://onboarding/do.php?_action=login");
		}

	}

	
	private function authenticateUser(PDO $link)
	{
		$user = DAO::getObject($link, "SELECT * FROM users WHERE md5(pwd_sha1) = '" . (trim($_REQUEST['key'])) . "'");

		if(is_null($user) || $user == "" ){
			return null;
		}

		$_SESSION = array(); // Clear session array of any old session data

		// Record the login event
		$log_vo = new LoginLogVO();
		$log_vo->username = $user->username;
		$log_vo->firstnames = $user->firstnames;
		$log_vo->surname = $user->surname;
		if($user->employer_id != '') {
			$org = Organisation::loadFromDatabase($link, $user->employer_id);
			if (!$org) {
				throw new Exception("User '" . $user->username . "' could not login because no record for their employer could be found.");
			}
			$log_vo->organisation_legal_name = $org->legal_name;
		}
		$log_vo->user_agent = substr($_SERVER['HTTP_USER_AGENT'],0,200) . " " . $_SERVER['REMOTE_ADDR'];

		// Write to log
        $dao_log = new LoginLogDAO($link);
        $dao_log->insert($log_vo);
        if(DAO::schemaEntityExists($link, null, "users", "last_logged_in")) {
            DAO::execute($link, "UPDATE users SET last_logged_in = NULL WHERE username=" . $link->quote($user->username));
        }

		return true;

	}

}
