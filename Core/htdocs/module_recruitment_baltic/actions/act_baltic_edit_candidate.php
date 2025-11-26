<?php
class baltic_edit_candidate implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$candidate_id = isset($_GET['candidate_id']) ? $_GET['candidate_id'] : '';
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		if(!$candidate_id)
		{
			if(!$id)
				throw new Exception("Missing or empty querystring argument 'candidate id'");
			else
				$candidate_id = $id;
		}

		// Create Value Object
		if ($candidate_id)
		{
			$vo = Candidate::loadFromDatabase($link, $candidate_id);
			if (is_null($vo))
			{
				throw new Exception("No user with id '$candidate_id'");
			}
			$latest_email_date_time = DAO::getSingleValue($link, "SELECT CONCAT(date_sent, '*', time_sent) AS dateTime FROM candidate_email_notes WHERE candidate_id = $candidate_id AND sent_from_sunesis = 0 ORDER BY date_sent DESC, time_sent DESC LIMIT 1");
			$latest_email_date = '';
			$latest_email_time = '';
			if(isset($latest_email_date_time) AND $latest_email_date_time != '')
			{
				$latest_email_date_time = explode('*', $latest_email_date_time);
				$latest_email_date = $latest_email_date_time[0];
				$latest_email_time = $latest_email_date_time[1];
			}
			$candidate_extra_info = CandidateExtraInfo::loadFromDatabase($link, $candidate_id);
			if(is_null($candidate_extra_info))
				$candidate_extra_info = new CandidateExtraInfo($candidate_id);
		}

		$gender_select = DAO::getResultset($link, "SELECT id, description FROM lookup_gender ORDER BY id;");
		$source_dropdown = DAO::getResultset($link, "SELECT id, description FROM lookup_source ORDER BY description;");
		$L12_dropdown = DAO::getResultset($link,"SELECT Ethnicity, CONCAT(Ethnicity, ' ', Ethnicity_Desc), null from lis201213.ilr_ethnicity order by Ethnicity;");
		$status_code_dropdown = DAO::getResultset($link, "SELECT id, description FROM lookup_candidate_status ORDER BY description;");
		$country_list = DAO::getResultset($link, 'SELECT id, country_name, NULL FROM lookup_countries ORDER BY country_name ;');
		$county_list = DAO::getResultset($link, 'SELECT description, description, NULL FROM central.lookup_counties GROUP BY description ORDER BY description ASC');
		$consultants = DAO::getResultset($link, "SELECT username, CONCAT(firstnames, ' ', surname) AS consultant FROM users WHERE type != 5");
		$delivery_locations = DAO::getResultset($link, "SELECT id, description FROM lookup_delivery_locations ORDER BY id");

		$driver_options = array(
			array('1', 'Yes', ''),
			array('2', 'No', ''));

		$jobatar_options = array(
			array('1', 'Yes', ''),
			array('2', 'No', ''));

		$yesno_options = array(
		array('1', 'Yes', ''),
		array('2', 'No', ''));
	
		if($vo->id == '')
		{
			$js_cancel = "window.history.go(-1);";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=baltic_read_candidate&candidate_id={$vo->id}')";
		}

		$pre_assessment_dropdown = DAO::getResultset($link,"SELECT id, description, null from lookup_pre_assessment;");
		$region_dropdown = DAO::getResultset($link, 'select description, description, null from lookup_vacancy_regions order by description;');

		// Presentation
		include('tpl_baltic_edit_candidate.php');
	}


}
?>