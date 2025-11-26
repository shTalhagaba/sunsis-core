<?php
class rec_edit_vacancy implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id']:'';
		$employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id']:'';
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction']:'';
		$selected_tab = isset($_REQUEST['selected_tab']) ? $_REQUEST['selected_tab']:'';

		if($subaction == 'load_organisation_locations')
		{
			$this->update_organisation_locations($link);
			return;
		}
		if($subaction == 'getVacancyTemplateFromID')
		{
			echo $this->getVacancyTemplateFromID($link);
			return;
		}
		if($subaction == 'getSectorQuestions')
		{
			echo $this->getSectorQuestions($link);
			return;
		}

		$_SESSION['bc']->add($link, "do.php?_action=rec_edit_vacancy&id=" . $id . "&employer_id=" . $employer_id, "Add/Edit Vacancy");

		$objEmployer = Organisation::loadFromDatabase($link, $employer_id);

		if($id == '')
		{
			// New record
			$vo = new RecVacancy();
			$vo->employer_id = $objEmployer->id;
		}
		else
		{
			$vo = RecVacancy::loadFromDatabase($link, $id);
		}

		$sql = <<<HEREDOC
SELECT
  locations.id,
  CONCAT(
    COALESCE(full_name),
    ' (',
    COALESCE(`address_line_1`, ''),
    ' ',
    COALESCE(`address_line_2`, ''),
    ' ,',
    COALESCE(`postcode`, ''),
    ')'
  ) AS location,
  NULL
FROM
  locations
WHERE locations.`organisations_id` = '$objEmployer->id'
ORDER BY locations.full_name
;
HEREDOC;

		$employer_locations_ddl = DAO::getResultset($link, $sql);

		$providers_ddl = DAO::getResultset($link, "SELECT organisations.id, organisations.legal_name, LEFT(legal_name, 1) FROM organisations WHERE organisations.organisation_type = '3' ORDER BY organisations.legal_name");

		// set up a vacancy code
		if( $id == '' )
		{
			$placeholder_sql = "SELECT MAX(id)+1 FROM vacancies";
			$placeholder_id = DAO::getSingleValue($link, $placeholder_sql);
			$vo->vacancy_reference = strtoupper(substr($employer_locations_ddl[0][1], 0, 3)).date("dms").str_pad($placeholder_id, 6,'0',STR_PAD_LEFT);
			$pre_selected_live_date = date('d/m/Y');
		}

		$yes_no = array(
			array('0', 'No', ''),
			array('1', 'Yes', '')
		);

		$app_framework = DAO::getResultset($link, "SELECT id, title, LEFT(title, 1) FROM frameworks WHERE active = 1 ORDER BY title");
		$supplementary_questions_ddl = DAO::getResultset($link, "SELECT id, description FROM lookup_vacancies_supp_questions ORDER BY description");
		$sectors_ddl = DAO::getResultset($link, "SELECT id, description FROM lookup_sector_types ORDER BY description");
		$general_questions_ddl = DAO::getResultset($link, "SELECT id, description FROM rec_questions WHERE type = '0' ORDER BY description");
		$sector_questions_ddl = DAO::getResultset($link, "SELECT id, description FROM rec_questions WHERE sector_id = '{$vo->sector}' AND type = '1' ORDER BY description");
		$selected_general_questions = DAO::getSingleColumn($link, "SELECT question_id FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE rec_questions.type = '0' AND vacancy_id = '{$vo->id}'");
		$selected_sector_questions = DAO::getSingleColumn($link, "SELECT question_id FROM rec_vacancy_questions INNER JOIN rec_questions ON rec_vacancy_questions.question_id = rec_questions.id WHERE rec_questions.type = '1' AND vacancy_id = '{$vo->id}'");
		$templates_ddl = DAO::getResultset($link, "SELECT vacancies.id, CONCAT(vacancy_title, ' - ', legal_name), NULL FROM vacancies INNER JOIN organisations ON vacancies.`employer_id` = organisations.`id` ORDER BY vacancy_title, legal_name ;");

		// Presentation
		include('tpl_rec_edit_vacancy.php');
	}

	private function getSectorQuestions(PDO $link)
	{
		$sector_id = isset($_REQUEST['sector_id'])?$_REQUEST['sector_id']:'';
		if($sector_id == '')
			return '';
		return json_encode(DAO::getResultset($link, "SELECT id, description FROM rec_questions WHERE sector_id = '{$sector_id}' AND type = '1' ORDER BY description", DAO::FETCH_ASSOC));
	}

	private function getVacancyTemplateFromID(PDO $link)
	{
		$vacancy_id = isset($_REQUEST['vacancy_id'])?$_REQUEST['vacancy_id']:'';
		if($vacancy_id == '')
			return '';
		$vacancy = RecVacancy::loadFromDatabase($link, $vacancy_id);
		if(is_null($vacancy))
			return '';
		return json_encode($vacancy);
	}

	private function update_organisation_locations(PDO $link)
	{
		header('Content-Type: text/xml');

		$organisation = array_key_exists('organisation', $_REQUEST)?$_REQUEST['organisation']:'';

		if($organisation == '')
		{
			throw new Exception("Missing querystring argument 'employer_id/provider_id'");
		}

		$sql = <<<HEREDOC
SELECT
  locations.id,
  CONCAT(
    COALESCE(full_name),
    ' (',
    COALESCE(`address_line_1`, ''),
    ' ',
    COALESCE(`address_line_2`, ''),
    ' ,',
    COALESCE(`postcode`, ''),
    ')'
  ),
  NULL
FROM
  locations
WHERE locations.`organisations_id` = '$organisation'
ORDER BY locations.full_name
;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";

			// First entry is empty
			echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}
}
?>