<?php
class ajax_onboarding extends ActionController
{
	public function indexAction( PDO $link )
	{

	}

	public function getEmailAction(PDO $link)
	{
		$email_id = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : '';
		if($email_id == '')
		{
			echo 'missing querystring argument: email_id';
			return;
		}

		echo DAO::getSingleValue($link, "SELECT emails.email_body FROM emails WHERE emails.id = '{$email_id}'");
	}

	private function getUniqueUsername(PDO $link, $table, $column, $firstnames, $surname)
	{
		$number_of_attempts = 0;
		$i = 1;
		do
		{
			$number_of_attempts++;
			if($number_of_attempts > 20)
				return null;
			$username = strtolower(substr(substr($firstnames, 0, 1).$surname, 0, 20));
			$username = str_replace(' ', '', $username);
			$username = str_replace("'", '', $username);
			$username = str_replace('"', '', $username);
			$username = $username . $i;
			$i++;
		}while((int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM {$table} WHERE {$column} = '{$username}'") > 0);
		if($username == '' || is_null($username))
			$username = strtolower(substr(substr($firstnames, 0, 1).$surname, 0, 15)) . date('is');
		return strtolower($username);
	}

	public function saveObLearnerEligibilityAction(PDO $link)
	{
		$ob_id = isset($_REQUEST['ob_id']) ? $_REQUEST['ob_id'] : '';
		if($ob_id == '')
			throw new Exception("Missing querystring argument: ob_id");

		$ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE id = '{$ob_id}'");
		if(!isset($ob_learner->id))
			throw new Exception("Invalid Id");

		$ob_eligibility = isset($_REQUEST['ob_eligibility']) ? $_REQUEST['ob_eligibility'] : '';
		if($ob_eligibility == 'N')
		{
			$ob_learner->is_eligible = 'N';
			DAO::saveObjectToTable($link, 'ob_learners', $ob_learner);

			http_redirect("http://sunesis/do.php?_action=read_ob_learner&id={$ob_id}");
		}

		$vo = new User();
		do
		{
			$pwd = PasswordUtilities::generateDatePassword();
			$pwd = PasswordUtilities::randomCapitalisation($pwd, 1);
			$pwd = PasswordUtilities::replaceSpacesWithNumbers($pwd);
			$validationResults = PasswordUtilities::checkPasswordStrength($pwd, PasswordUtilities::getIllegalWords());
		} while($validationResults['code'] == 0);
		$vo->password = $pwd;
		$vo->pwd_sha1 = sha1($pwd);
		$vo->web_access = 0;
		$vo->username = $this->getUniqueUsername($link, 'users', 'username', $ob_learner->firstnames, $ob_learner->surname);
		$vo->type = User::TYPE_LEARNER;
		$vo->created = date('Y-m-d H:i:s');
		$vo->firstnames = $ob_learner->firstnames;
		$vo->surname = $ob_learner->surname;
		$vo->dob = $ob_learner->dob;
		$vo->gender = $ob_learner->gender;
		$vo->home_postcode = $ob_learner->home_postcode;
		$vo->home_email = $ob_learner->home_email;
		$vo->employer_id = $ob_learner->employer_id;
		$vo->employer_location_id = $ob_learner->employer_location_id;
		$vo->ni = $ob_learner->ni;
		$vo->ethnicity = $ob_learner->ethnicity;
		$vo->home_address_line_1 = $ob_learner->home_address_line_1;
		$vo->home_address_line_2 = $ob_learner->home_address_line_2;
		$vo->home_address_line_3 = $ob_learner->home_address_line_3;
		$vo->home_address_line_4 = $ob_learner->home_address_line_4;
		$vo->home_telephone = $ob_learner->home_telephone;
		$vo->home_mobile = $ob_learner->home_mobile;
		$vo->created_by = $_SESSION['user']->id;

		DAO::transaction_start($link);
		try
		{

			$vo->save($link, true);

			$ob_learner->user_id = $vo->id;
			$ob_learner->is_eligible = 'Y';
			DAO::saveObjectToTable($link, 'ob_learners', $ob_learner);

			$log = new OnboardingLogger();
			$log->subject = 'CONVERTED TO SUNESIS LEARNER';
			$log->note = "Learner has been converted to Sunesis Learner.";
			$log->ob_learner_id = $ob_learner->id;
			$log->by_whom = $_SESSION['user']->id;
			$log->save($link);

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		http_redirect("do.php?_action=edit_learner&username={$vo->username}&organisations_id={$vo->employer_id}&location_id={$vo->employer_location_id}");
	}

	public function showQualificationsTableOnEnrolmentAction(PDO $link)
	{
		$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '';
		if($course_id == '')
			return;

		$sql = <<<SQL
SELECT REPLACE(framework_qualifications.id, '/', '') AS id, framework_qualifications.title, null
FROM framework_qualifications INNER JOIN courses ON framework_qualifications.framework_id = courses.framework_id
WHERE courses.id = '{$course_id}' ORDER BY main_aim DESC
SQL;
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$html = '';
		foreach($result AS $row)
		{
			$html .= '<tr>';
			$html .= '<td>';
			$html .= '<input type="checkbox" name="selected_quals[]" value="'.$row['id'].'" checked />';
			$html .= '</td>';
			$html .= '<td>' . $row['id'] . ' ' . $row['title'] . '</td>';
			$html .= '<td>';
			$html .= '<input onblur="validateDate(this);" onfocus="copydate(\'start\', this);" class="datepicker " type="text" id="input_sd_'.$row['id'].'" name="sd_'.$row['id'].'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" />';
			$html .= '</td>';
			$html .= '<td>';
            $html .= '<input onblur="validateDate(this);" onfocus="copydate(\'end\', this);" class="datepicker " type="text" id="input_ped_'.$row['id'].'" name="ped_'.$row['id'].'" value="" size="10" maxlength="10" placeholder="dd/mm/yyyy" />';
			$html .= '</td>';
			$html .= '<td>';
			$html .= '<input type="text" name="glh_'.$row['id'].'" size="4" maxlength="4" onkeypress="return numbersonly(this);" />';
			$html .= '</td>';
			$html .= '</tr>';
		}
		if($html != '')
		{
			$html = '<table class="table table-bordered"><tr><th>Select</th><th>Qualification</th><th>Start Date</th><th>Planned End Date</th><th>GLH</th></tr>' . $html . '</table>';
		}
		echo $html;
	}

	function getTechCertsAction(PDO $link)
	{
		$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '';
		if($course_id == '')
			return;

		$sql = <<<SQL
SELECT DISTINCT
  REPLACE(framework_qualifications.id, '/', '') AS id,
  CONCAT(framework_qualifications.id, ' - ', internaltitle) AS internaltitle
FROM
  framework_qualifications INNER JOIN courses ON framework_qualifications.framework_id = courses.framework_id
WHERE
  courses.id = '{$course_id}'
  AND qualification_type != 'FS' AND main_aim != 1
  AND LOWER(framework_qualifications.internaltitle) NOT LIKE '%plts%'
  AND LOWER(framework_qualifications.internaltitle) NOT LIKE '%err%'
  AND LOWER(framework_qualifications.internaltitle) NOT LIKE '%employment rights and responsibilities%'
ORDER BY
  internaltitle
;
SQL;

		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo '<option value="" >Select an option</option>';
		if(count($records) > 0)
		{
			foreach($records AS $row)
			{
				echo '<option value="'.$row['id'].'">'.$row['internaltitle'].'</option>';
			}
		}
		else
			echo '<option value="">No qualifications/certificates found</option>';

	}

	function getMainAimsAction(PDO $link)
	{
		$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '';
		if($course_id == '')
			return;

		$sql = <<<SQL
SELECT DISTINCT
  REPLACE(framework_qualifications.id, '/', '') AS id,
  CONCAT(framework_qualifications.id, ' - ', internaltitle) AS internaltitle
FROM
  framework_qualifications INNER JOIN courses ON framework_qualifications.framework_id = courses.framework_id
WHERE
  courses.id = '{$course_id}'
  AND qualification_type != 'FS' AND main_aim = 1
ORDER BY
  internaltitle
;
SQL;

		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo '<option value="" >Select an option</option>';
		if(count($records) > 0)
		{
			foreach($records AS $row)
			{
				echo '<option value="'.$row['id'].'">'.$row['internaltitle'].'</option>';
			}
		}
		else
			echo '<option value="">No qualifications/certificates found</option>';

	}

	function getFsAction(PDO $link)
	{
		$fs_type = isset($_REQUEST['fs_type'])?$_REQUEST['fs_type']:'';
		$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '';
		if($course_id == '')
			return;

		$where = ' AND LOWER(internaltitle) LIKE "%math%"';
		if($fs_type == 'eng')
			$where = ' AND LOWER(internaltitle) LIKE "%english%"';
		if($fs_type == 'ict')
			$where = ' AND LOWER(internaltitle) LIKE "%ict%"';

		$sql = <<<SQL
SELECT DISTINCT
  REPLACE(framework_qualifications.id, '/', '') AS id,
  CONCAT(framework_qualifications.id, ' - ', internaltitle) AS internaltitle
FROM
  framework_qualifications INNER JOIN courses ON framework_qualifications.framework_id = courses.framework_id
WHERE
  courses.id = '{$course_id}'
  AND qualification_type = 'FS'
  $where
ORDER BY
  internaltitle
;
SQL;

		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo '<option value="" >Select an option</option>';
		if(count($records) > 0)
		{
			foreach($records AS $row)
			{
				echo '<option value="'.$row['id'].'">'.$row['internaltitle'].'</option>';
			}
		}
		else
			echo '<option value="">No qualifications/certificates found</option>';

	}

	function getPLTSAction(PDO $link)
	{
		$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '';
		if($course_id == '')
			return;

		$sql = <<<SQL
SELECT DISTINCT
  REPLACE(framework_qualifications.id, '/', '') AS id,
  CONCAT(framework_qualifications.id, ' - ', internaltitle) AS internaltitle
FROM
  framework_qualifications INNER JOIN courses ON framework_qualifications.framework_id = courses.framework_id
WHERE
  courses.id = '{$course_id}'
  AND LOWER(framework_qualifications.internaltitle) LIKE '%plts%'
ORDER BY
  internaltitle
;
SQL;

		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo '<option value="" >Select an option</option>';
		if(count($records) > 0)
		{
			foreach($records AS $row)
			{
				echo '<option value="'.$row['id'].'">'.$row['internaltitle'].'</option>';
			}
		}
		else
			echo '<option value="">No qualifications/certificates found</option>';

	}

	function getERRAction(PDO $link)
	{
		$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '';
		if($course_id == '')
			return;

		$sql = <<<SQL
SELECT DISTINCT
  REPLACE(framework_qualifications.id, '/', '') AS id,
  CONCAT(framework_qualifications.id, ' - ', internaltitle) AS internaltitle
FROM
  framework_qualifications INNER JOIN courses ON framework_qualifications.framework_id = courses.framework_id
WHERE
  courses.id = '{$course_id}'
  AND (LOWER(framework_qualifications.internaltitle) LIKE '%err%' OR LOWER(framework_qualifications.internaltitle) LIKE '%employment rights and responsibilities%')
ORDER BY
  internaltitle
;
SQL;

		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		echo '<option value="" >Select an option</option>';
		if(count($records) > 0)
		{
			foreach($records AS $row)
			{
				echo '<option value="'.$row['id'].'">'.$row['internaltitle'].'</option>';
			}
		}
		else
			echo '<option value="">No qualifications/certificates found</option>';

	}
}