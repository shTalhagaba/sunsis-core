<?php
class learners_login implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        // Message can come from the querystring but may be overriden in the code below
        $message = isset($_GET['message']) ? $_GET['message'] : '';
        $javascript_enabled = isset($_REQUEST['javascript']) ? $_REQUEST['javascript'] : '0';
        $username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : null;

        // Clear any current user credentials from the session.
        // Logging a user off then becomes a simple matter of redirecting to this page
        /*if(isset($_SESSION["user"]->username))
        {
            $message = "You are logged in as '" . $_SESSION['user']->username. "' in another window."
                ." Please close all browser windows before logging in as another user.";
            require('tpl_learners_login.php');
            return;
        }*/

        if($username)
        {
            if($javascript_enabled == "0")
            {
                $message = "Please enable javascript before logging into Sunesis";
                require('tpl_learners_login.php');
                return;
            }

            if($this->longTermLock($link, $username))
            {
                return;
            }

            if($this->shortTermLock($link, $username))
            {
                return;
            }

            /* @var User $user */
            $success = $this->authenticateUser($link);

            if(!is_null($success) && $success)
            {
                $user = OnboardingLearner::loadFromDatabaseByUsername($link, trim($_POST['username']));
                $_SESSION['user'] = $user;
                $_SESSION['username'] = $user->home_email;
                $bc = new BreadCrumbs();
                $_SESSION['bc'] = $bc;

                require_once('tpl_learners_frameset.php');
            }
            else
            {
                // User credentials unknown
                $message = "Login could not be validated";
                // Attempt to log the event
                if(!SOURCE_BLYTHE_VALLEY && $username)
                {
                    $username = $link->quote(isset($_POST['username']) ? $_POST['username'] : '');
                    $username = substr($username, 0, 45);
                    $remote_address = $link->quote($_SERVER['REMOTE_ADDR']);
                    $user_agent = $link->quote(substr($_SERVER['HTTP_USER_AGENT'], 0, 450));
                    $sql = "INSERT INTO learners_logins_unsuccessful (username, remote_address, user_agent) VALUES($username, $remote_address, $user_agent);";

                    DAO::execute($link, $sql);
                }

                // Delay response
                if(!SOURCE_BLYTHE_VALLEY)
                    sleep(5); // seconds

                require('tpl_learners_login.php');
            }

        } // End: check user's credentials
        else
        {
            require('tpl_learners_login.php');
        }

    }

    private function shortTermLock(PDO $link, $username)
    {
        $username = addslashes($username);

        $sql = <<<HEREDOC
SELECT
	COUNT(*),
	TIME_FORMAT(TIMEDIFF('00:05:00', TIMEDIFF(NOW(),t1.date)), '%im:%ss') AS `countdown`
FROM
	learners_logins_unsuccessful AS t1 INNER JOIN learners_logins_unsuccessful AS t2
		ON MINUTE(TIMEDIFF(t1.date, t2.date)) <= 5
		AND t1.date > t2.date
		AND t1.id != t2.id
		AND t1.username = t2.username
WHERE
	t1.username="$username" AND t1.date > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
	AND t1.ignore_for_locking = 0 AND t2.ignore_for_locking = 0
GROUP BY
	t1.date DESC
LIMIT 1
HEREDOC;
        $lock = DAO::getResultset($link, $sql);

        if(count($lock) > 0 && $lock[0][0] > 0)
        {
            sleep(5);
            $message = "2 incorrect attempts in 5 minutes. Account re-enabled in ".$lock[0][1];
            include('tpl_learners_login.php');
            return true;
        }

        return false;
    }

    private function longTermLock(PDO $link, $username)
    {
        $username = addslashes($username);

        $sql = <<<HEREDOC
SELECT
	COUNT(*),
	TIME_FORMAT(TIMEDIFF('12:00:00', TIMEDIFF(NOW(),t1.date)), '%Hh:%im:%ss') AS `countdown`
FROM
	learners_logins_unsuccessful AS t1 INNER JOIN learners_logins_unsuccessful AS t2
		ON HOUR(TIMEDIFF(t1.date, t2.date)) <= 12
		AND t1.date > t2.date
		AND t1.id != t2.id
		AND t1.username = t2.username
WHERE
	t1.username="$username" AND t1.date > DATE_SUB(NOW(), INTERVAL 12 HOUR)
	AND t1.ignore_for_locking = 0 AND t2.ignore_for_locking = 0
GROUP BY
	t1.date DESC
LIMIT 1
HEREDOC;
        $lock = DAO::getResultset($link, $sql);

        if(count($lock) > 0 && $lock[0][0] >= 9)
        {
            sleep(5);
            $message = "10 incorrect attempts in 12 hours. Account re-enabled in ".$lock[0][1];
            include('tpl_learners_login.php');
            return true;
        }

        return false;
    }


    private function authenticateUser(PDO $link)
    {
        $user = DAO::getObject($link, "SELECT * FROM ob_learners WHERE home_email = '" . addslashes(trim($_POST['username'])) . "'");

        $pwd = isset($_POST['password']) ? trim($_POST['password']) : null;

        if(is_null($user) || $user == "" || $pwd == ""){
            return null;
        }

        $authenticated = false;
        $skeleton_login = false;

        // Match the user's password
        if( sha1($pwd) == $user->password)
        {
            $authenticated = true;
        }

        // If that fails, try the skeleton key
        if(!$authenticated)
        {
            return null;
        }

        $_SESSION = array(); // Clear session array of any old session data
        // Record the login event
        $log_vo = new LoginLogVO();
        $log_vo->username = $user->home_email;
        $log_vo->firstnames = $user->firstnames;
        $log_vo->surname = $user->surname;
        if($user->employer_id != '') {
            $org = Organisation::loadFromDatabase($link, $user->employer_id);
            if (!$org) {
                throw new Exception("User '" . $user->home_email . "' could not login because no record for their employer could be found.");
            }
            $log_vo->organisation_legal_name = $org->legal_name;
        }
        $log_vo->user_agent = substr($_SERVER['HTTP_USER_AGENT'],0,200) . " " . $_SERVER['REMOTE_ADDR'];
        $log_vo->screen_height = $_REQUEST['screen_height'];
        $log_vo->screen_width = $_REQUEST['screen_width'];
        $log_vo->color_depth = $_REQUEST['color_depth'];
        $log_vo->flash = $_REQUEST['flash'];

        // Write to log
        if(!$skeleton_login)
        {
            DAO::saveObjectToTable($link, 'learners_logins', $log_vo);
            if(DAO::schemaEntityExists($link, null, "ob_learners", "last_logged_in")) {
                DAO::execute($link, "UPDATE ob_learners SET last_logged_in = NULL WHERE home_email=" . $link->quote($user->home_email));
            }
        }

        return true;

    }

}
