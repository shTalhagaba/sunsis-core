<?php
class save_ob_learners implements IAction
{
	public function execute(PDO $link)
	{
		if(isset($_REQUEST['version']) && $_REQUEST['version'] == '2')
		{
			$this->executeVersion2($link);
			exit;
		}

		//pre($_REQUEST);
		$learner_info = array(
			'firstnames'
			,'surname'
			,'dob'
			,'ni'
			,'gender'
			,'home_postcode'
			,'home_email'
			,'employer_id'
			,'job_title'
			,'college_id'
			,'start_date'
			,'planned_end_date'
			,'target_date_practical_period'
			,'planned_otj_hours'
			,'framework_id'
			,'course_id'
			,'tech_cert'
			,'l2_found_competence'
			,'main_aim'
			,'fs_maths'
			,'fs_eng'
			,'fs_ict'
			,'other_qual'
			,'ERR'
			,'PLTS'
		);

		DAO::transaction_start($link);
		try
		{
			for($i = 1; $i <= 5; $i++)
			{
				if(isset($_REQUEST['firstnames'.$i]) && trim($_REQUEST['firstnames'.$i]) != '')
				{
					$entry = new stdClass();
					$entry->id = null;
					foreach($learner_info AS $key)
					{
						$entry->$key = isset($_REQUEST[$key.$i])?$_REQUEST[$key.$i]:'';
					}
					$entry->created_by = $_SESSION['user']->id;
					if(isset($entry->ERR) && $entry->ERR == 'on')
						$entry->ERR = '1';
					else
						$entry->ERR = '0';
					if(isset($entry->PLTS) && $entry->PLTS == 'on')
						$entry->PLTS = '1';
					else
						$entry->PLTS = '0';

					// clean some fields
					$entry->firstnames = ucfirst($entry->firstnames);
					$entry->surname = ucfirst($entry->surname);
					$entry->home_postcode = strtoupper($entry->home_postcode);
					$entry->home_email = strtolower($entry->home_email);

					//create Sunesis user first
					$sunesis_learner = new User();
					$sunesis_learner->populate($entry);
					$sunesis_learner->type = User::TYPE_LEARNER;
					$sunesis_learner->web_access = 0;
					$sunesis_learner->password = 'pa55W0rd';
					$sunesis_learner->pwd_sha1 = sha1('pa55W0rd');
					$employer_main_location_id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.is_legal_address = '1' AND locations.organisations_id = '{$sunesis_learner->employer_id}'");
					if($employer_main_location_id == '')
						continue;
					$employer_location = Location::loadFromDatabase($link, $employer_main_location_id);
					if(is_null($employer_location))
						continue;
					$sunesis_learner->employer_location_id = $employer_location->id;
					$sunesis_learner->work_address_line_1 = $employer_location->address_line_1;
					$sunesis_learner->work_address_line_2 = $employer_location->address_line_2;
					$sunesis_learner->work_address_line_3 = $employer_location->address_line_3;
					$sunesis_learner->work_address_line_4 = $employer_location->address_line_4;
					$sunesis_learner->work_postcode = $employer_location->postcode;
					$sunesis_learner->work_telephone = $employer_location->telephone;
					$sunesis_learner->username = $this->getUniqueUsername($link, 'users', 'username', $sunesis_learner->firstnames, $sunesis_learner->surname);
					if(is_null($sunesis_learner->gender) || $sunesis_learner->gender == '')
						$sunesis_learner->gender = 'U';
					$sunesis_learner->save($link);

					//get the new sunesis learner id and attach to entry which is ob_learner
					$entry->user_id = $sunesis_learner->id;
					DAO::saveObjectToTable($link, 'ob_learners', $entry);

					$log = new OnboardingLogger();
					$log->subject = 'RECORD CREATION';
					$log->note = 'Learner record created';
					$log->ob_learner_id = $entry->id;
					$log->by_whom = $_SESSION['user']->id;
					$log->save($link);
					unset($log);
				}
			}
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		http_redirect('do.php?_action=enrol_ob_learners');
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

	private function executeVersion2(PDO $link)
	{
		$learner_info = [
			'firstnames',
			'surname',
			'dob',
			'gender',
			'home_postcode',
			'home_email',
			'employer_id',
			'employer_location_id',
			'ks_assessment',
			'coach',
			'contract_id',
		];

		$entry = new stdClass();
		$entry->id = null;
		foreach($learner_info AS $key)
		{
			$entry->$key = isset($_REQUEST[$key])?$_REQUEST[$key]:'';
		}
		$entry->created_by = $_SESSION['user']->id;
		$entry->status = "CREATED";

		// clean some fields
		$entry->firstnames = ucfirst($entry->firstnames);
		$entry->surname = ucfirst($entry->surname);
		$entry->home_postcode = strtoupper($entry->home_postcode);
		$entry->home_email = strtolower($entry->home_email);

		$entry->ob_username = $this->getUniqueUsername($link, 'ob_learners', 'ob_username', $entry->firstnames, $entry->surname);

		DAO::saveObjectToTable($link, 'ob_learners', $entry);

		$log = new OnboardingLogger();
		$log->subject = 'RECORD CREATION';
		$log->note = 'Learner record created';
		$log->ob_learner_id = $entry->id;
		$log->by_whom = $_SESSION['user']->id;
		$log->save($link);
		unset($log);

		if($_REQUEST['stay'] == 1)
			http_redirect($_SESSION['bc']->getCurrent());
		else
			http_redirect($_SESSION['bc']->getPrevious());
	}
}