<?php
class application implements IUnauthenticatedAction

{
	public function execute( PDO $link )
	{
		$firstnames = isset($_POST['firstname']) ? $_POST['firstname'] : '';
		$surname = isset($_POST['surname']) ? $_POST['surname'] : '';
		$dob = isset($_POST['dob']) ? $_POST['dob'] : '';
		$postcode = isset($_POST['postcode']) ? $_POST['postcode'] : '';
		$new_candidate = isset($_POST['new_candidate']) ? $_POST['new_candidate'] : '';

		$returning_candidate_message = "";

		if($new_candidate == '0' && $firstnames != '' && $surname != '' && $dob != '' && $postcode != '')
		{
			$basic_details['firstnames'] = $link->quote($firstnames);
			$basic_details['surname'] = $link->quote($surname);
			$basic_details['dob'] = Date::toMySQL($dob);
			$basic_details['postcode'] = $link->quote($postcode);

			$candidate = RecCandidate::getCandidateIDFromBasicDetails($link, $basic_details);
			if(!$candidate)
			{
				$returning_candidate_message = 'We have not found your details, please continue applying as <u><strong>New Candidate</strong></u>';
				$candidate = new RecCandidate();
			}
			else
			{
				$returning_candidate_message = 'Welcome back, <u><strong>' . $candidate->firstnames . ' ' . $candidate->surname . '</strong></u>';
			}
		}
		else
		{
			// New record
			$candidate = new RecCandidate();
		}

		//$candidate = RecCandidate::loadFromDatabase($link, 71472);

		$qualifications = $this->getCandidateQualifications($link, $candidate);
		$employments = $this->getCandidateEmployments($link, $candidate);

		$type_ddl = DAO::getResultset($link, "SELECT id, description, NULL FROM lookup_sector_types WHERE id IN (1, 8) ORDER BY description ASC;");
		$sql_regions = <<<SQL
SELECT DISTINCT
  locations.`address_line_4`,
  locations.`address_line_4`,
  NULL
FROM
  locations
  INNER JOIN vacancies
    ON locations.id = vacancies.`location_id`
WHERE locations.`address_line_4` IS NOT NULL
ORDER BY locations.`address_line_4` ;
SQL;
		$region_ddl = DAO::getResultset($link, $sql_regions);

		$region = isset($_REQUEST['region'])?$_REQUEST['region']:'';
		$sector = isset($_REQUEST['sector'])?$_REQUEST['sector']:'';
		$keywords = isset($_REQUEST['keywords'])?$_REQUEST['keywords']:'';

		$genderDDL = DAO::getResultset($link, "SELECT id, description, null FROM lookup_gender;");
		$ethnicityDDL = DAO::getResultset($link,"SELECT Ethnicity, Ethnicity_Desc, null FROM lis201213.ilr_ethnicity ORDER BY Ethnicity;");
		$countiesDDL = DAO::getResultSet($link, "SELECT description, description, NULL FROM central.lookup_counties GROUP BY description ORDER BY description ASC;");
		//$PriorAttain_dropdown = DAO::getResultset($link,"SELECT distinct PriorAttain, CONCAT(PriorAttain, ' ', PriorAttainDesc), NULL FROM lis201415.ilr_priorattain ORDER BY PriorAttain;");
		$PriorAttain_dropdown = DAO::getResultset($link, "SELECT DISTINCT id, description, null FROM lookup_candidate_qualification ORDER BY id;");
		//array_unshift($PriorAttain_dropdown,array('0','Please select one',''));
		$yes_no_options = array(
			array('No', 'No', ''),
			array('Yes', 'Yes', '')
		);

		if(true || SOURCE_LOCAL || DB_NAME == "am_sd_demo" || DB_NAME == "am_demo")
			require_once('tpl_application1.php');
		else
			require_once('tpl_application.php');
	}

	private function getCandidateQualifications(PDO $link, RecCandidate $candidate)
	{
		if(is_null($candidate))
			throw new UnauthorizedException();

		$qualification = array();
		$i = 1;
		foreach($candidate->qualifications AS $q)
		{
			if($q['qualification_level'] == 'GCSE')
				continue;
			$qualification['level'.$i] = $q['qualification_level'];
			$qualification['subject'.$i] = $q['qualification_subject'];
			$qualification['grade'.$i] = $q['qualification_grade'];
			$qualification['date'.$i] = $q['qualification_date'];
			$qualification['institution'.$i] = $q['institution'];
			$i++;
		}
		return $qualification;
	}

	private function getCandidateEmployments(PDO $link, RecCandidate $candidate)
	{
		if(is_null($candidate))
			throw new UnauthorizedException();

		$employments = array();
		$i = 1;
		foreach($candidate->employments AS $e)
		{
			$employments['company_name'.$i] = $e['company_name'];
			$employments['job_title'.$i] = $e['job_title'];
			$employments['start_date'.$i] = $e['start_date'];
			$employments['end_date'.$i] = $e['end_date'];
			$employments['skills'.$i] = $e['skills'];
			$i++;
		}
		return $employments;
	}

}
?>
