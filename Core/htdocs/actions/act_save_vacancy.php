<?php
class save_vacancy implements IAction
{
	public function execute(PDO $link)
	{
		$vacancy = new Vacancy();
		$vacancy->populate($_POST);
		$vacancy->max_submissions = $vacancy->no_of_vacancies;
		$vacancy_extra_progression = isset($_REQUEST['other_levels']) ? $_REQUEST['other_levels'] : array();

		$location = Location::loadFromDatabase($link, $vacancy->location);
		
		$vacancy->postcode = $location->postcode;

		if($_POST['id'] == "" && (DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic" || DB_NAME=="am_ray_recruit"))
		{
			$vacancy->created = "";
		}
				
		// add in the validation here for geocode location
		if ( $location->longitude == NULL ) {
			$loc = new GeoLocation();
			$loc->setPostcode($vacancy->postcode, $link);
			$vacancy->longitude = $loc->getLongitude();
			$vacancy->latitude = $loc->getLatitude();
			$vacancy->easting = $loc->getEasting();
			$vacancy->northing = $loc->getNorthing();	
		}
		else {
			$vacancy->longitude = $location->longitude;
			$vacancy->latitude = $location->latitude;
			$vacancy->easting = $location->easting;
			$vacancy->northing = $location->northing;
		}

		if($_POST['id'] != '')
		{
			$existing_record = Vacancy::loadFromDatabase($link, $_POST['id']);
			$log_string = $existing_record->buildAuditLogString($link, $vacancy);
			if($log_string!='')
			{
				$note = new Note();
				$note->subject = "Vacancy Edited";
				$note->note = $log_string;
			}
		}
		else
		{
			$note = new Note();
			$note->subject = "Vacancy created";
		}
		DAO::transaction_start($link);
		try
		{
			$vacancy->save($link);
			if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic")
			{
				$this->saveExtraProgression($link, $vacancy, $vacancy_extra_progression);
			}
			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'vacancy';
				$note->parent_id = $vacancy->id;
				$note->save($link);
			}
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}






		http_redirect('do.php?_action=read_vacancy&id=' . $vacancy->id);
	}

	private function saveExtraProgression(PDO $link, Vacancy $vacancy, array $members)
	{
		if($vacancy->id == '')
			return;
		$sql = "DELETE FROM vacancies_extra_progress WHERE vacancy_id = ".$vacancy->id;
		DAO::execute($link, $sql);

		$data = array();
		foreach($members as $member)
		{
			$data[] = array('vacancy_id' => $vacancy->id, 'vacancy_app_id' => $member);
		}

		DAO::multipleRowInsert($link, 'vacancies_extra_progress', $data);
	}
}
?>