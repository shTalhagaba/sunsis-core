<?php
class create_progression_from_recruitment implements IAction
{
	public function execute(PDO $link)
	{
		$application_id = isset($_REQUEST['application_id'])?$_REQUEST['application_id']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subaction == 'save')
		{
			$this->saveProgression($link);
			exit;
		}


		if($application_id == '')
			throw new Exception('Missing querystring argument: application_id');

		$rec_application = RecCandidateApplication::loadFromDatabaseByID($link, $application_id);
		if(is_null($rec_application))
			throw new Exception('Candidate/Participant application record not found');

		$_SESSION['bc']->add($link, "do.php?_action=create_progression_from_recruitment&application_id=".$application_id, "Create Participant Progression");

		$vacancy = RecVacancy::loadFromDatabase($link, $rec_application->vacancy_id);

		if($vacancy->getProgressions($link, false, true) >= $vacancy->total_positions)
			throw new Exception('This vacancy is filled');

		$yes_no_existing_provider = 0;

		$progression = new Progression();
		$progression->participant_id = $rec_application->candidate_id;
		$progression->progression_type = 1;
		if(!is_null($vacancy->provider_id) || $vacancy->provider_id != '')
			$progression->progression_type = 2;
		$progression->application_id = $rec_application->id;
		$progression->vacancy_id = $vacancy->id;
		$progression->employer_id = $vacancy->employer_id;
		$progression->employer_location = $vacancy->location;
		if(!is_null($vacancy->provider_id) || $vacancy->provider_id != '')
		{
			$progression->provider_id = $vacancy->provider_id;
			$progression->provider_location = $vacancy->provider_location;
			$yes_no_existing_provider = 1;
		}

		$progression_type_ddl2 = array(
			array('1', 'Full Time Paid Employment'),
			array('2', 'Part Time Paid Employment')
		);

		if(!is_null($vacancy->provider_id) || $vacancy->provider_id != '')
		{
			$progression_type_ddl2 = array(
				array('4', 'Apprenticeship')
			);
		}

		$source_type_ddl = array(
			array('3', 'RM (Recruitment Manager)')
		);

		$progression_status_ddl = Progression::getProgressionStatus();
		unset($progression_status_ddl[2]);
		unset($progression_status_ddl[3]);

		$progression_end_status_ddl = Progression::getProgressionEndStatuses();

		$training_end_date = DAO::getSingleValue($link, "SELECT tr.closure_date FROM tr INNER JOIN users ON tr.username = users.username WHERE users.id = '{$rec_application->candidate_id}'");
		if($training_end_date == '')
			throw new Exception('Participant training end date not found, operation aborted.');

		include('tpl_create_progression_from_recruitment.php');

	}

	private function saveProgression(PDO $link)
	{
		$progression = new Progression();
		$progression->populate($_REQUEST);

		DAO::transaction_start($link);
		try
		{
			$progression->save($link);
			DAO::execute($link, "UPDATE candidate_applications SET application_status = '" . RecCandidateApplication::PROGRESSION_CREATED . "' WHERE id = '" . $_REQUEST['application_id'] . "'");
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		http_redirect($_SESSION['bc']->getPrevious());
	}
}