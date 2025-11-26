<?php
class logic_melon implements IAction
{
	public function execute(PDO $link)
	{
		require_once './LogicMelon/LogicMelonAutoload.php';

		$vacancy_id = isset($_REQUEST['vac_id'])?$_REQUEST['vac_id']:'';
		$on_duplicate = isset($_REQUEST['on_duplicate'])?$_REQUEST['on_duplicate']:'';
		$selected_advert_id = isset($_REQUEST['advert_id'])?$_REQUEST['advert_id']:'';
		if($vacancy_id == '')
			throw new Exception('Vacancy ID Missing.');

		$vacancy = Vacancy::loadFromDatabase($link, $vacancy_id);

		$culture_id = 'en';
		$api_key = SystemConfig::getEntityValue($link, 'logic_melon_api_key');
		$username = SystemConfig::getEntityValue($link, 'logic_melon_username');
		$password = SystemConfig::getEntityValue($link, 'logic_melon_password');

//		$api_key = '50fcad16-ca44-4140-ab85-652d8c1206fa';
//		$username = 'support@perspective-uk.com';
//		$password = 'perspective';

		// prepare an advertisement
		$advert_identifier = $vacancy->code;
		$advert_ref = $vacancy->code;
		$search_days = NULL;
		if($vacancy->expiry_date != '' || is_null($vacancy->expiry_date))
		{
			$expiry_date = new Date($vacancy->expiry_date);
			$search_days = Date::dateDiffInfo($expiry_date, date('Y-m-d'));
			if(isset($search_days['days']))
				$search_days = $search_days['days'];
			else
				$search_days = NULL;
		}
		$job_title = $vacancy->job_title;
		$job_type = $vacancy->job_type;
		$hours = $vacancy->job_hours;
		$primary_location = DAO::getSingleValue($link, "SELECT address_line_3 FROM locations WHERE id = " . $vacancy->location);
		$industry = NULL;
		$salaryFrom = str_replace('�', '', $vacancy->salary);
		$salaryTo = str_replace('�', '', $vacancy->salary);
		$salaryCurrency = 'GBP';
		$salaryPer = 'W';
		$salaryBenefits = NULL;
		$contact_name = '';
		$contact_email = '';
		//$vacancy->description = 'VACANCY DESCRIPTION' . "\r\n" . PHP_EOL . $vacancy->description . "\r\n" . PHP_EOL  . "\r\n" . 'SKILLS REQUIRED' . "\r\n" . PHP_EOL . $vacancy->skills_req . "\r\n" . PHP_EOL . "\r\n" . 'TRAINING TO BE PROVIDED' . "\r\n" . PHP_EOL . $vacancy->training_provided . "\r\n" . PHP_EOL . "\r\n" . 'QUALIFICATIONS REQUIRED' . "\r\n" . PHP_EOL . $vacancy->required_quals;
		$vacancy->description = 'VACANCY DESCRIPTION'  . $vacancy->description   . 'SKILLS REQUIRED' . $vacancy->skills_req . 'TRAINING TO BE PROVIDED' . $vacancy->training_provided . 'QUALIFICATIONS REQUIRED' . $vacancy->required_quals;
		$vacancy->description = mb_convert_encoding($vacancy->description, "UTF-8");
		//$job_desc = htmlspecialchars((string)$vacancy->description);
		$job_desc = $link->quote($vacancy->description);
		$job_desc = str_replace('\r\n', '', $job_desc);
		$application_url = 'https://' . substr(DB_NAME, 3) . '.sunesis.uk.net/do.php?_action=vacancy_detail&id='.$vacancy->id;
//		$application_url = 'https://demo.sunesis.uk.net/do.php?_action=vacancy_detail&id='.$vacancy->id;

		$advertisement = new LogicMelonStructAddAdvert($culture_id, $api_key, $username, $password, $advert_identifier, $advert_ref, $search_days, $on_duplicate, $job_title, $job_type, $hours, $primary_location, $industry, $salaryFrom, $salaryTo, $salaryCurrency, $salaryPer, $salaryBenefits, $contact_name, $contact_email, $job_desc, $application_url);

		// if there are already adverts created then check and give the user options
		if($on_duplicate == '' AND $selected_advert_id == '')
		{
			$advertExistsInSunesis = DAO::getSingleValue($link, "SELECT COUNT(*) FROM adverts WHERE vac_id = " . $vacancy->id . " AND username = '" . $_SESSION['user']->username . "'");
			if($advertExistsInSunesis)
			{
				$result = $this->checkExistingAdvertsInLogicMelon($link, $advertisement, $vacancy_id);
				if($result != false)
				{
					echo $result;
					exit;
				}
			}
		}
//		pre($advertisement);

		// create the advertisement
		$logicMelonServiceAdd = new LogicMelonServiceAdd();
		if($logicMelonServiceAdd->AddAdvert($advertisement))
		{
			$response = $logicMelonServiceAdd->getResult();
			$response = $response->getAddAdvertResult();
			$logicMelonStructAddAdvertResult = new LogicMelonStructAddAdvertResult($response->AdvertID, $response->UserID, $response->OrganisationID, $response->RedirectUrl);

			$advert_id = $logicMelonStructAddAdvertResult->getAdvertID();
			$user_id = $logicMelonStructAddAdvertResult->getUserID();
			$organisation_id = $logicMelonStructAddAdvertResult->getOrganisationID();
			$r_url = addslashes((string)$logicMelonStructAddAdvertResult->getRedirectUrl());
			$sunesis_username = $_SESSION['user']->username;

			if(isset($selected_advert_id) && $selected_advert_id == $response->AdvertID) // if user has selected the duplicate option then this means that we have that record in sunesis adverts table so update the time
			{
				$sql = <<<HEREDOC
				UPDATE
					adverts
				SET
					`datetime` = NULL
				WHERE
					vac_id = $vacancy->id AND advert_id = $selected_advert_id
HEREDOC;
				//pre($sql);
			}
			else
			{
				$sql = <<<HEREDOC
					INSERT INTO
						adverts (vac_id, advert_id, user_id, organisation_id, redirect_url, username)
					VALUES
					    ($vacancy->id, $advert_id, $user_id, $organisation_id, '$r_url', '$sunesis_username');

HEREDOC;
			}
			DAO::execute($link, $sql);

			$outputHTML = "";
			$outputHTML .= '<h3>Upload Result</h3>';
			$outputHTML .= '<table style="width: 100%" class="resultset" cellspacing="0" cellpadding="4">';
			$outputHTML .= '<caption><strong>Operation successfully completed on Logic Melon. Following are the details received from Logic Melon.<br>Please click on the Redirect URL to complete the posting process. </strong></caption>';
			$outputHTML .= '<tr><th>Field</th><th>Value</th></tr>';
			$outputHTML .= '<tr class="Data"><td><strong>Advert ID</strong></td><td>' . $advert_id . '</td></tr>';
			$outputHTML .= '<tr class="Data"><td><strong>User ID</strong></td><td>' . $user_id . '</td></tr>';
			$outputHTML .= '<tr class="Data"><td><strong>Organisation ID</strong></td><td>' . $organisation_id . '</td></tr>';
			$outputHTML .= '<tr class="Data"><td><strong>Redirect URL</strong></td><td><a href= "' . $r_url . '" target="_blank" onclick="removeDialog();">' . $r_url . '</a></td></tr>';
			$outputHTML .= '<tr class="Data"><td colspan = "2"><i>Please click on the URL returned from Logic Melon to complete the posting process.</i></td></tr>';
			$outputHTML .= '</table>';

			/*			$outputHTML = <<<HEREDOC

   <IFRAME style="border: 0px;" SRC="$url" width="100%" height = "100%" >
   HEREDOC;*/
			echo $outputHTML;
		}
		else
		{
			if(isset($vacancy))
				$details = 'Logic Melon Error:: Vacancy ID = ' . $vacancy->id . ' :: Vacancy Code = ' . $vacancy->code;
			else
				$details = '';
			pre('Error in communication with Logic Melon, please click <a href="do.php?_action=support_form&header=1&type=Incident&priority=high&details=' . $details . '">here</a> to raise the support request.');
		}

	}

	private function checkExistingAdvertsInLogicMelon(PDO $link, LogicMelonStructAddAdvert $advertisement, $vacancy_id)
	{
		//$api_key = '50fcad16-ca44-4140-ab85-652d8c1206fa';
		//$username = 'support@perspective-uk.com';
		//$password = 'perspective';

		$api_key = SystemConfig::getEntityValue($link, 'logic_melon_api_key');
		$username = SystemConfig::getEntityValue($link, 'logic_melon_username');
		$password = SystemConfig::getEntityValue($link, 'logic_melon_password');

		$sunesis_advert_records = DAO::getResultset($link, "SELECT advert_id, user_id, organisation_id FROM adverts WHERE vac_id = " . $vacancy_id . " AND username = '" . $_SESSION['user']->username . "'", PDO::FETCH_ASSOC);
		$advert_id = $sunesis_advert_records[0]['advert_id'];
		$user_id = $sunesis_advert_records[0]['user_id'];
		$organisation_id = $sunesis_advert_records[0]['organisation_id'];

		$advert_to_search = new LogicMelonStructGetAdvert($advertisement->getSCultureID(), $api_key, $username, $user_id,$organisation_id, $advertisement->getSAdvertReference(),$advertisement->getSAdvertReference(),$advert_id,NULL,NULL,NULL);
		//$advert_to_search = new LogicMelonStructGetAdvert('en', $api_key, $username, NULL,NULL, 'WAR3107000729', 'WAR3107000729','1442960',NULL,NULL,NULL);

		$logicMelonServiceGet = new LogicMelonServiceGet();
		if($logicMelonServiceGet->GetAdvert($advert_to_search))
		{
			$response = $logicMelonServiceGet->getResult();
			$logicMelonStructGetAdvertResponse = new LogicMelonStructGetAdvertResponse();
			$logicMelonStructGetAdvertResponse = $response;

			$logicMelonStructArrayOfAPIAdvert = new LogicMelonStructArrayOfAPIAdvert();
			$logicMelonStructArrayOfAPIAdvert = $logicMelonStructGetAdvertResponse->getGetAdvertResult();

			if(count($logicMelonStructArrayOfAPIAdvert->getAPIAdvert()) == 0)
				return false;

			$outputHTML = "";
			$outputHTML .= '<h3>Upload Result</h3>';
			$outputHTML .= '<form><table style="width: 100%" class="resultset" cellspacing="0" cellpadding="4">';
			$outputHTML .= '<caption><strong>Following adverts for this vacancy have been found in Logic Melon.</strong></caption>';
			$outputHTML .= '<tr><th>&nbsp;</th><th>Advert ID</th><th>Last Post Date</th><th>Advert Identifier</th><th>Advert Reference</th><th>Advert Title</th><th>Advert Type</th><th>Description</th></tr>';

			foreach($logicMelonStructArrayOfAPIAdvert->getAPIAdvert() AS $r)
			{
				$outputHTML .= '<tr class="Data">';
				$outputHTML .= '<td>';
				$outputHTML .= '<button type="button" onclick=\'upload_vacancy_to_logic_melon(' . $vacancy_id . ', ' . $r->AdvertID . ', "duplicate")\'>Duplicate</button>';
				$outputHTML .= '<button type="button" onclick=\'upload_vacancy_to_logic_melon(' . $vacancy_id . ', ' . $r->AdvertID . ', "update")\'>Update</button>';
				$outputHTML .= '<button type="button" onclick=\'upload_vacancy_to_logic_melon(' . $vacancy_id . ', ' . $r->AdvertID . ', "Ignore")\'>Ignore</button>';
				$outputHTML .= '<td>' . $r->AdvertID . '</td>';
				$outputHTML .= '<td>' . $r->LastPostDate . '</td>';
				$outputHTML .= '<td>' . $r->AdvertIdentifier . '</td>';
				$outputHTML .= '<td>' . $r->AdvertReference . '</td>';
				$outputHTML .= '<td>' . $r->AdvertTitle . '</td>';
				switch($r->AdvertType)
				{
					case 'P':
						$outputHTML .=  '<td>Permanent</td>';
						break;
					case 'C':
						$outputHTML .=  '<td>Contract</td>';
						break;
					case 'T':
						$outputHTML .=  '<td>Temporary</td>';
						break;
					default:
						$outputHTML .= '<td>' . $r->AdvertType . '</td>';
						break;
				}
				$outputHTML .= '<td>' . $r->JobDescription . '</td></tr>';
			}
			$outputHTML .= '</table></form>';
			return $outputHTML;
		}
		else
		{
			pre('Error in communication with Logic Melon, please raise the support request quoting vacancy code.');
		}

	}

}
?>