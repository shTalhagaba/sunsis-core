<?php
class rec_save_vacancy implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);
		$vacancy = new RecVacancy();
		$vacancy->populate($_REQUEST);

		$location = Location::loadFromDatabase($link, $vacancy->location_id);

		if ( is_null($vacancy->longitude) || $vacancy->longitude == NULL )
		{
			$loc = new GeoLocation();
			$loc->setPostcode($location->postcode, $link);
			$vacancy->longitude = $loc->getLongitude();
			$vacancy->latitude = $loc->getLatitude();
			$vacancy->easting = $loc->getEasting();
			$vacancy->northing = $loc->getNorthing();
			$vacancy->postcode = $location->postcode;
		}

		$vacancy->short_description = $this->safeHTML($vacancy->short_description, true);

		DAO::transaction_start($link);
		try
		{
			$vacancy->save($link);
			$this->saveVacancyQuestions($link, $vacancy->id);

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		if($_REQUEST['selected_tab'])
			http_redirect('do.php?_action=rec_read_vacancy&id=' . $vacancy->id . '&selected_tab=' . $_REQUEST['selected_tab']);
		else
			http_redirect('do.php?_action=rec_read_vacancy&id=' . $vacancy->id);
	}

	private function safeHTML(&$content, $removeAllHTMLTags = false)
	{
		if($removeAllHTMLTags)
			$content = preg_replace('/(<([^>]+)>)/', '', $content);
		else
		{
			$content = preg_replace('/<p[^>]*>/', '', $content); // Remove the start <p> or <p attr="">
			$content = preg_replace('/<\/p>/', '', $content); // Replace the end
		}
		return $content;
	}

	private function saveVacancyQuestions(PDO $link, $vacancy_id)
	{
		$g_questions = isset($_REQUEST['general_questions'])?$_REQUEST['general_questions']:array();
		$s_questions = isset($_REQUEST['sector_questions'])?$_REQUEST['sector_questions']:array();
		$questions = array_merge($g_questions, $s_questions);
		$killer_questions = DAO::getSingleColumn($link, "SELECT id FROM rec_questions WHERE type IN ('2', '3')");
		$questions = array_merge($questions, $killer_questions);

		if(count($questions) == 0)
			return;

		$r = array();
		foreach($questions AS $q)
		{
			$objQuestion = new stdClass();
			$objQuestion->vacancy_id = $vacancy_id;
			$objQuestion->question_id = $q;
			$r[] = $objQuestion;
		}

		DAO::execute($link, "DELETE FROM rec_vacancy_questions WHERE vacancy_id = '{$vacancy_id}'");
		DAO::multipleRowInsert($link, 'rec_vacancy_questions', $r);
	}

}
?>