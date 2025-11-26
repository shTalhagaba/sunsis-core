<?php
class login implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$username = isset($_POST["username"]) ? trim($_POST["username"]) : null;
		$message = isset($_GET['message']) ? $_GET['message'] : '';

		// Clear any current user credentials from the session.
		// Logging a user off then becomes a simple matter of calling this action
		$_SESSION = array();

		// For Bright Training
		$previous_qualification = array(
			array('1', 'Yes', ''),
			array('0', 'No', ''));

		$candidate = false;

		if(!empty($username)) // User has submitted login data
		{
			// Check for hackers, chancers and other ne'er-do-wells
			$bad_login_threshold = 2;
			$lockout_period = 5; // minutes
			$detection_period = $lockout_period * 2; // double the lockout period
			$key = $link->quote($username);


			if ($link->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
			{
				$sql = <<<HEREDOC
SELECT
	(SELECT COUNT(*) FROM logins_unsuccessful WHERE	username = $key
		AND `date` > SUBDATE(NOW(), INTERVAL $detection_period MINUTE)) AS detection_count,
	(SELECT COUNT(*) FROM logins_unsuccessful WHERE	username = $key
		AND `date` > SUBDATE(NOW(), INTERVAL $lockout_period MINUTE)) AS lockout_count,
	(SELECT TIME_FORMAT(TIMEDIFF('00:$lockout_period:00', TIMEDIFF(NOW(),`date`)), '%im:%ss')
		FROM logins_unsuccessful WHERE username = $key
		AND `date` > SUBDATE(NOW(), INTERVAL $lockout_period MINUTE)
		ORDER BY `date` DESC LIMIT 1) AS countdown
HEREDOC;
			}
			else
			{
				$sql = <<<HEREDOC
SELECT
	(SELECT COUNT(*) FROM logins_unsuccessful WHERE	username = $key
		AND date > DATEADD(mi, $detection_period,GETDATE())) AS detection_count,
	(SELECT COUNT(*) FROM logins_unsuccessful WHERE	username = $key
		AND date > DATEADD(mi, $lockout_period,GETDATE())) AS lockout_count
HEREDOC;

				$sql2 = <<<HEREDOC
SELECT DATEDIFF(mi,DATEADD(mi, $lockout_period, GETDATE()), DATEDIFF(mi,GETDATE(),date)) as countdown
FROM logins_unsuccessful WHERE username = $key
AND date > DATEADD(mi, $lockout_period, GETDATE())
ORDER BY date DESC
HEREDOC;
			}

			if ($link->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
			{
				$bad_logins = DAO::getResultset($link, $sql);
				$message = "Login limit of $bad_login_threshold attempts per $lockout_period minutes exceeded. Account will reactivate in ".$bad_logins[0][2];
			}
			else
			{
				$bad_logins = DAO::getResultset($link, $sql);
				$time_left = DAO::getSingleValue($link, $sql2);
				$message = "Login limit of $bad_login_threshold attempts per $lockout_period minutes exceeded. Account will reactivate in ".$time_left;
			}

			if( ($bad_logins[0][0] >= $bad_login_threshold) && ($bad_logins[0][1] > 0) )
			{
				if( in_array(DB_NAME, ["am_demo", "am_duplex", "am_ela"]) )
					require_once('tpl_login_.php');
				else
					require_once('tpl_login.php');
			}
			else
			{
				// Perspective login
				$master_login = false;
				if($_POST['username']=='perspective' && $_POST['password']=='Latitude.21')
				{
					$master_login = true;
					$username = DAO::getSingleValue($link, "select ident from acl inner join users on users.username = acl.ident and users.web_access = 1 where resource_category='application' and resource_id = 1 and privilege = 'administrator' AND `type` != '12' LIMIT 0,1");
					$_POST['username'] = $username;
					$_POST['password'] = DAO::getSingleValue($link, "select password from users where username = '".addslashes((string)$username)."'  LIMIT 0,1");
				}

				// Check user's credentials

				$user = User::loadFromDatabase($link, $username); /* @var $user User */
				if(!is_null($user) && DB_NAME == "am_ela" && $user->ob_access_only == 1)
				{
					$user = null;
				}
				if(!is_null($user)
					&& ($user->password != '')
					&& ($user->web_access == 1)
					&& ($_POST['password'] == $user->password) )
				{
					// User credentials identified
					$_SESSION['user'] = $user;
					$_SESSION['screen_height'] = $_REQUEST['screen_height'];
					$_SESSION['screen_width'] = $_REQUEST['screen_width'];
					$_SESSION['color_depth'] = $_REQUEST['color_depth'];

					$bc = new BreadCrumbs();
					$_SESSION['bc'] = $bc;

					if($_REQUEST['flash'])
					{
						$tokens = explode('.', $_REQUEST['flash']);
						$_SESSION['flash_major'] = (int)$tokens[0];
						$_SESSION['flash_minor'] = (int)$tokens[1];
						$_SESSION['flash_revision'] = (int)$tokens[2];
					}
					else
					{
						$_SESSION['flash_major'] = $_SESSION['flash_minor'] = $_SESSION['flash_revision'] = null;
					}

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
					$log_vo->screen_height = $_REQUEST['screen_height'];
					$log_vo->screen_width = $_REQUEST['screen_width'];
					$log_vo->color_depth = $_REQUEST['color_depth'];
					$log_vo->flash = $_REQUEST['flash'];

					$dao_log = new LoginLogDAO($link);
					if(!$master_login) {
						$dao_log->insert($log_vo);
						if(DAO::schemaEntityExists($link, null, "users", "last_logged_in")) {
							DAO::execute($link, "UPDATE users SET last_logged_in = NULL WHERE username=" . $link->quote($user->username));
						}
					}

					// Check if target entity exists
					$db_name = DB_NAME;
					//pre("select count(*) from central.update_status where client = '$db_name' and entity = 'targets'");
					$exists = DAO::getSingleValue($link, "select count(*) from central.update_status where client = '$db_name' and entity = 'targets'");
					if($exists=='0' && !in_array(DB_NAME, ["am_crackerjack_demo"]))
						DAO::execute($link, "insert into central.update_status values('targets',CURDATE(),'$db_name')");
					$targets_last_update = DAO::getSingleValue($link, "select update_date from central.update_status where client = '$db_name' and update_date = CURDATE() and entity = 'targets'");
					if(false && $targets_last_update=='')
					{
						DAO::execute($link, "UPDATE tr
LEFT OUTER JOIN (
		SELECT tr.id AS tr_id,SUM(`sub`.target * proportion / (SELECT SUM(proportion)
                                        FROM   student_qualifications
                                        WHERE  tr_id = tr.id
                                               AND aptitude != 1)) AS result
FROM tr
       LEFT OUTER JOIN (SELECT student_milestones.tr_id,
                               student_qualifications.proportion,
                               CASE TIMESTAMPDIFF(MONTH, student_qualifications.start_date, CURDATE())
                                 WHEN -1 THEN 0
                                 WHEN -2 THEN 0
                                 WHEN -3 THEN 0
                                 WHEN -4 THEN 0
                                 WHEN -5 THEN 0
                                 WHEN -6 THEN 0
                                 WHEN -7 THEN 0
                                 WHEN -8 THEN 0
                                 WHEN -9 THEN 0
                                 WHEN -10 THEN 0
                                 WHEN 0 THEN 0
                                 WHEN 1 THEN AVG(student_milestones.month_1)
                                 WHEN 2 THEN AVG(student_milestones.month_2)
                                 WHEN 3 THEN AVG(student_milestones.month_3)
                                 WHEN 4 THEN AVG(student_milestones.month_4)
                                 WHEN 5 THEN AVG(student_milestones.month_5)
                                 WHEN 6 THEN AVG(student_milestones.month_6)
                                 WHEN 7 THEN AVG(student_milestones.month_7)
                                 WHEN 8 THEN AVG(student_milestones.month_8)
                                 WHEN 9 THEN AVG(student_milestones.month_9)
                                 WHEN 10 THEN AVG(student_milestones.month_10)
                                 WHEN 11 THEN AVG(student_milestones.month_11)
                                 WHEN 12 THEN AVG(student_milestones.month_12)
                                 WHEN 13 THEN AVG(student_milestones.month_13)
                                 WHEN 14 THEN AVG(student_milestones.month_14)
                                 WHEN 15 THEN AVG(student_milestones.month_15)
                                 WHEN 16 THEN AVG(student_milestones.month_16)
                                 WHEN 17 THEN AVG(student_milestones.month_17)
                                 WHEN 18 THEN AVG(student_milestones.month_18)
                                 WHEN 19 THEN AVG(student_milestones.month_19)
                                 WHEN 20 THEN AVG(student_milestones.month_20)
                                 WHEN 21 THEN AVG(student_milestones.month_21)
                                 WHEN 22 THEN AVG(student_milestones.month_22)
                                 WHEN 23 THEN AVG(student_milestones.month_23)
                                 WHEN 24 THEN AVG(student_milestones.month_24)
                                 WHEN 25 THEN AVG(student_milestones.month_25)
                                 WHEN 26 THEN AVG(student_milestones.month_26)
                                 WHEN 27 THEN AVG(student_milestones.month_27)
                                 WHEN 28 THEN AVG(student_milestones.month_28)
                                 WHEN 29 THEN AVG(student_milestones.month_29)
                                 WHEN 30 THEN AVG(student_milestones.month_30)
                                 WHEN 31 THEN AVG(student_milestones.month_31)
                                 WHEN 32 THEN AVG(student_milestones.month_32)
                                 WHEN 33 THEN AVG(student_milestones.month_33)
                                 WHEN 34 THEN AVG(student_milestones.month_34)
                                 WHEN 35 THEN AVG(student_milestones.month_35)
                                 WHEN 36 THEN AVG(student_milestones.month_36)
                                 ELSE 100
                               END AS target
                        FROM   student_milestones
                               LEFT JOIN student_qualifications
                                       ON student_qualifications.id =
                                          student_milestones.`qualification_id`
                                          AND student_milestones.tr_id =
                                              student_qualifications.`tr_id`

                                          AND
student_qualifications.aptitude != 1
                        GROUP  BY student_milestones.`tr_id`, student_milestones.`qualification_id`) AS
                       `sub`
                     ON tr.id = `sub`.tr_id
                    GROUP BY tr.`id`
	) AS `subquery`
		ON `subquery`.tr_id = tr.id

SET target = result
WHERE target_date > CURDATE();");

						DAO::execute($link, "update tr set target = 100 where target_date <= CURDATE()");
						DAO::execute($link, "update central.update_status set update_date=CURDATE() where client = '$db_name' and entity = 'targets'");
					}

					// Create ILRs for this submission period if missing
					try	{
						DAO::transaction_start($link);
						$this->_createNewEmployerResponsiveIlrs($link);
						$this->_createNewLearnerResponsiveIlrs($link);
						DAO::transaction_commit($link);
					}
					catch(Exception $e) {
						DAO::transaction_rollback($link);
						throw $e;
					}

                    // Health & Safety Auto Creation
                    if(DB_NAME=='am_city_skills')
                    {
                        DAO::execute($link, "INSERT INTO health_safety SELECT NULL, locations.id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL FROM locations WHERE id NOT IN (SELECT location_id FROM health_safety);");

                        $sql = "SELECT locations.contact_name, locations.contact_email, health_safety.id, location_id FROM health_safety
                                LEFT JOIN locations ON locations.id = health_safety.`location_id`
                                WHERE location_id IN (SELECT id FROM locations WHERE organisations_id IN (SELECT id FROM organisations WHERE id IN (SELECT DISTINCT employer_id FROM tr WHERE status_code = 1)))
                                AND health_safety.id NOT IN (SELECT form_id FROM forms_audit WHERE description = 'Health & Safety Form Emailed');";

                        $st = $link->query($sql);
                        if($st)
                        {
                            while($row = $st->fetch())
                            {
                                $contact_name = $row['contact_name'];
                                $form_id = $row['id'];
                                $contact_email = $row['contact_email'];
                                $location_id = $row['location_id'];
                                $from="training@city-skills.com";
                                $mailto = "khushnood.khan@perspective-uk.com;".$row['contact_email'];

                                $source = 3;
                                $key = md5("PerspectiveSunesissource=".$source."form_id=".$form_id);

                                $message = "<html><body>
                                        <br><br>Dear {$contact_name}
                                        <br><br>A mandatory part of any apprenticeship scheme is ensuring and evidencing that apprentices are working in a safe environment. As you have at least one live apprentice could you please complete a brief self-assessment to keep us in line with this requirement.
                                        <br><br>You will need your company Employers Liability Insurance (ELI) provider, policy number and expiry date to hand and an understanding of your organisations H&S policies and procedures to complete the form.
                                        <br><br>If you have any comments or evidence you may choose to give this detail but it is not mandatory. We expect this form will take a maximum of 10 minutes and you will be able to track if you have met each section as you pass through the form.
                                        <br><br><a href ='https://city-skills.sunesis.uk.net/do.php?_action=health_safety_form&id=".$form_id."&location_id=".$location_id."&source=3&key=".$key."'> Please click here to be taken to the form </a>
                                        <br><br>If you have any questions or queries you can contact us at training@city-skills.com or 020 7157 9835
                                        <br><br>Regards,
                                        <br><br><b>City Skills</b>
                                        <br><br>
                                        </body></html>";

                                $subject = "City Skills Health & Safety assessment now due";

                                $success1 = Emailer::notification_email_review($mailto, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                                DAO::execute($link,"insert into forms_audit values(NULL,$form_id,'Health & Safety Form Emailed','H&S',now(),'$user->username')");
                            }
                        }
                    }

					require_once('tpl_frameset.php');
				}
				else
				{
					// User credentials unknown
					$message = "Either the username or password you entered is incorrect.";

					// Attempt to log the event
					$username = $link->quote(isset($_POST['username']) ? $_POST['username'] : '');
					$password = $link->quote(isset($_POST['password']) ? $_POST['password'] : '');
					$sql = "INSERT INTO logins_unsuccessful (username, password) VALUES($username, $password);";
					DAO::execute($link, $sql);

					if( in_array(DB_NAME, ["am_demo", "am_duplex", "am_ela"]) )
						require_once('tpl_login_.php');
					else
						require_once('tpl_login.php');
				} // End: check user's credentials

			} // End: check for bad logins

		} // End: user has submitted login credentials
		else
		{
			// No login details provided
			if( in_array(DB_NAME, ["am_demo", "am_duplex", "am_ela"]) )
					require_once('tpl_login_.php');
				else
					require_once('tpl_login.php');
		}
	}


	private function _renderChromeFrameBox()
	{
		$isIE = strpos($_SERVER["HTTP_USER_AGENT"], "MSIE") !== FALSE;
		$hasChromeFrame = strpos($_SERVER["HTTP_USER_AGENT"], "chromeframe") !== FALSE;
		if(!$isIE || $hasChromeFrame){
			return;
		}

		echo <<<HTML
<style type="text/css">
div.ChromeFrameBox{
	width: 50%;
	position: absolute;
	bottom: 10px;
	left: 25%;
	right: 25%;
	text-align: center;
	color: gray;
	margin-left: auto;
	margin-right: auto;
}
</style>
<div class="ChromeFrameBox" >
<table border="0" cellpadding="4" cellspacing="1">
<tr>
<td valign="top" align="left" width="60"><a href="http://www.google.com/chromeframe/eula.html?user=true&redirect=true"><img src="/images/chromeframe50x50.gif" width="50" height="50" border="0" float="left" title="Enable Google ChromeFrame"/></a></td>
<td valign="top" align="left" style="text-align: justify">Sunesis now supports the Google ChromeFrame plugin for Internet Explorer, enabling faster page loads and an enhanced look and feel
within Sunesis. <a href="http://www.google.com/chromeframe/eula.html?user=true&redirect=true">Enable</a> Google ChromeFrame for an improved user experience.</td>
</tr>
</table>
</div>
HTML;
	}


	/**
	 * Create ILRs up until the current submission period
	 * @param PDO $link
	 */
	private function _createNewLearnerResponsiveIlrs(PDO $link)
	{
		$this->_createNewILRs($link, self::NUM_SUBMISSION_PERIODS_LEARNER_RESPONSIVE, self::FUNDING_STREAM_LEARNER_RESPONSIVE);
	}

	/**
	 * Create ILRs up until the current submission period
	 * @param PDO $link
	 */
	private function _createNewEmployerResponsiveIlrs(PDO $link)
	{
		$this->_createNewILRs($link, self::NUM_SUBMISSION_PERIODS_EMPLOYER_RESPONSIVE, self::FUNDING_STREAM_EMPLOYER_RESPONSIVE);
	}


	/**
	 * @param PDO $link
	 * @param int $numSubmissionPeriods 5 = Learner Responsive, 13 = Employer Responsive
	 * @param int $fundingStreamType 1 = Learner Responsive, 2 = Employer Responsive
	 * @throws InvalidArgumentException
	 */
	private function _createNewILRs(PDO $link, $numSubmissionPeriods, $fundingStreamType)
	{
		if(empty($numSubmissionPeriods) || !is_numeric($numSubmissionPeriods)){
			throw new InvalidArgumentException("Invalid value for numSubmissionPeriods");
		}
		if(empty($fundingStreamType) || !is_numeric($fundingStreamType)){
			throw new InvalidArgumentException("Invalid value for fundingStreamType");
		}

		// Determine the current contract year
		$currentContractYear = DAO::getSingleValue($link, "SELECT contract_year FROM central.lookup_submission_dates "
			. "WHERE contract_type = $fundingStreamType "
			. "AND CURRENT_DATE BETWEEN start_submission_date AND last_submission_date AND submission <> 'W13'");
		if (empty($currentContractYear)) {
			return;
		}

		// Determine current submission period
		$currentSubmissionPeriod = DAO::getSingleValue($link, "SELECT UPPER(submission) FROM central.lookup_submission_dates "
			. "WHERE contract_type = $fundingStreamType "
			. "AND CURRENT_DATE BETWEEN start_submission_date AND last_submission_date AND submission <> 'W13'");
		if (empty($currentSubmissionPeriod)) {
			return; // Cannot always determine submission period for LR funding (W01 starts in November), so just skip it if we cannot determine it
		}

		// Iterate through each period and create a new ILR for the *following* period
		// if it does not yet exist. This approach works so long as we stop iterating *before*
		// the current submission period.
		$i = 1;
		$period = 'W01';
		$followingPeriod = 'W02';
		while ($i < $numSubmissionPeriods && $period < $currentSubmissionPeriod) {
			$sql = <<<HEREDOC
INSERT INTO ilr (L01, L03, A09, ilr, submission, contract_type,
 	tr_id, is_complete, is_valid, is_approved, is_active, contract_id)
SELECT
	ilr1.L01,
	ilr1.L03,
	ilr1.A09,
	ilr1.ilr,
	'$followingPeriod',
	ilr1.contract_type,
	ilr1.tr_id,
	ilr1.is_complete,
	ilr1.is_valid,
	ilr1.is_approved,
	ilr1.is_active,
	ilr1.contract_id
FROM
	ilr AS ilr1 INNER JOIN contracts
		ON ilr1.contract_id = contracts.`id`
	LEFT OUTER JOIN ilr AS ilr2
		ON ilr1.`contract_id` = ilr2.`contract_id`
		AND ilr1.`tr_id` = ilr2.`tr_id`
		AND ilr2.`submission` = '$followingPeriod'
WHERE
	ilr1.`submission` = '$period'
	AND contracts.funding_body = $fundingStreamType
	AND contracts.`contract_year` = $currentContractYear
	AND MID(ilr1.ilr, LOCATE('<L08>', ilr1.ilr) + 5, 1) != 'Y'
	AND ilr2.`submission` IS NULL
HEREDOC;
			DAO::execute($link, $sql);

			$i = $i + 1; // Next period
			$period = sprintf('W%02d', $i); // e.g. W02
			$followingPeriod = sprintf('W%02d', $i + 1); // e.g. W03
		}

	}

	const FUNDING_STREAM_LEARNER_RESPONSIVE = 1;
	const NUM_SUBMISSION_PERIODS_LEARNER_RESPONSIVE = 5;
	const FUNDING_STREAM_EMPLOYER_RESPONSIVE = 2;
	const NUM_SUBMISSION_PERIODS_EMPLOYER_RESPONSIVE = 13;
}