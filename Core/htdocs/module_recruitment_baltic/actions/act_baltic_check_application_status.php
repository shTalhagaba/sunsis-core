<?php
class baltic_check_application_status implements IAction
{
	public function execute(PDO $link)
	{
		$form_submitted = isset($_REQUEST['form_submitted'])?$_REQUEST['form_submitted']:'';
		$first_name = isset($_REQUEST['first_name'])?addslashes((string)$_REQUEST['first_name']):'';
		$surname = isset($_REQUEST['surname'])?addslashes((string)$_REQUEST['surname']):'';
		$dob = isset($_REQUEST['dob'])?htmlspecialchars((string)$_REQUEST['dob']):'';
		$email = isset($_REQUEST['email'])?addslashes((string)$_REQUEST['email']):'';

		$dob = Date::toMySQL($dob);
		if($form_submitted)
		{
			$result = "";
			$sql = <<<HEREDOC
			SELECT (SELECT description FROM lookup_candidate_status WHERE id = candidate.`status_code`) AS STATUS,
			 vacancies.`job_title`, vacancies.`id`, candidate.`created` AS ApplicationMade
			FROM
			candidate
			INNER JOIN candidate_applications ON candidate.id = candidate_applications.`candidate_id`
			INNER JOIN vacancies ON vacancies.id = candidate_applications.`vacancy_id`
			WHERE candidate.firstnames = '$first_name' AND surname = '$surname' AND dob = '$dob' AND email = '$email';

HEREDOC;

			$resultSet = DAO::getResultset($link, $sql);

			$result = 'No Records Found.';

			if($resultSet)
			{
				$result = "<h3>Candidate Details</h3>";
				$result .= "First Name:" . $first_name . "<br>";
				$result .= "Surname:" . $surname . "<br>";
				$result .= "Email:" . $email . "<br>";
				$result .= "DOB:" . Date::toLong($dob) . "<br>";

				$result .= "<h3>Applications Details</h3>";
				foreach($resultSet AS $r )
				{
					$result .= "<h4>Job Title: " . $r[1] . "</h4><br>";
					$result .= "Application Made On: " . Date::toLong($r[3]) . "<br>";
					$result .= "Status: " . $r[0] . "<br>";
				}
				//pre($result);
			}
		}

		include('tpl_baltic_check_application_status.php');
	}
}