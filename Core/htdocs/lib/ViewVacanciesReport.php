<?php
class ViewVacanciesReport extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$where = '';

			$sql = <<<SQL

SELECT
	IF(vacancies.active = 1, 'Yes', IF(vacancies.active = 0, 'No', '')) AS active,
	DATE_FORMAT(vacancies.`created`, '%d/%m/%Y	') AS vacancy_creation_date,
	IF(date_status_changed IS NOT NULL, DATEDIFF(CURDATE(), created),'') AS days_since_vacancy_created,
	(SELECT description FROM lookup_vacancy_status WHERE id = vacancies.`status`) AS vacancy_status,
	IF(date_status_changed IS NOT NULL, DATEDIFF(CURDATE(),date_status_changed),'') AS days_in_current_stage,
	MONTHNAME(date_expected_to_fill) AS forecast_fill_month,
	(SELECT COUNT(*) FROM candidate_applications WHERE vacancy_id = vacancies.id) AS total_applications,
	(SELECT COUNT(*) FROM candidate_applications  INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.id WHERE vacancy_id = vacancies.id AND application_screening = 100 AND candidate.`username` IS NULL) AS highly_suitable,
	(SELECT COUNT(*) FROM candidate_applications  INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.id WHERE vacancy_id = vacancies.id AND application_screening = 65 AND candidate.`username` IS NULL) AS suitable,
	(SELECT COUNT(*) FROM candidate_applications  INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.id WHERE vacancy_id = vacancies.id AND application_screening = 0 AND candidate.`username` IS NULL) AS unsuitable,
	(SELECT COUNT(*) FROM candidate_applications  INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.id WHERE vacancy_id = vacancies.id AND candidate.`username` IS NOT NULL) AS converted_to_learners,
	(SELECT COUNT(*) FROM candidate_applications  INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.id WHERE vacancy_id = vacancies.id AND candidate.`username` IS NULL AND candidate.`status_code` = 18) AS no_of_cv_to_client,
	organisations.`legal_name` AS employer,
	vacancies.`source` AS source,
	vacancies.`created` AS date_submitted,
	DATE_FORMAT(vacancies.`expiry_date`, '%d/%m/%Y	') AS closing_date,
	vacancies.`brm` AS brm,
	vacancies.`postcode` AS delivery_loc,
	CONCAT(locations.`address_line_1`, ' ', locations.`address_line_2`, ' ', locations.`address_line_3`, ' ', locations.`address_line_4`) AS company_location,
	locations.`postcode` AS company_postcode,
	IF(vacancies.dd = 1, 'Yes', IF(vacancies.dd = 0, 'No', ''))  AS due_diligence,
	IF(vacancies.`age` = 1, 'Yes', IF(vacancies.age = 0, 'No', '')) AS age_required,
	IF(vacancies.`at_risk` = 1, 'Yes', IF(vacancies.at_risk = 0, 'No', '')) AS at_risk,
	IF(vacancies.`induction_confirmed` = 1, 'Yes', IF(vacancies.induction_confirmed = 0, 'No', '')) AS induction_confirmed,
	DATE_FORMAT(vacancies.`induction_date`, '%d/%m/%Y	') AS induction_date,
	vacancies.`inductor`,
	vacancies.`comments`,
	(SELECT description FROM lookup_vacancy_app_type WHERE id = vacancies.apprenticeship_type) AS apprenticeship_type,
	IF(expiry_date IS NOT NULL, DATEDIFF(expiry_date, CURDATE()),'') AS days_left,
	(SELECT description FROM lookup_vacancy_regions WHERE id = vacancies.region) AS region



FROM
	vacancies
	LEFT JOIN organisations ON vacancies.`employer_id` = organisations.`id`
	LEFT JOIN locations ON locations.`organisations_id` = organisations.`id`;

SQL;

			// Create new view object
			$view = $_SESSION[$key] = new ViewVacanciesReport();
			$view->setSQL($sql);

			// new / unassigned filter
			$options = array(
				0=>array(1, 'Is Active', null, 'WHERE vacancies.active = 1'),
				1=>array(2, 'Is Inactive', null, 'WHERE vacancies.active = 0')
			);
			$f = new DropDownViewFilter('filter_isactive', $options, 1, true);
			$f->setDescriptionFormat("Vacancy Active/Inactive: %s");
			$view->addFilter($f);

			//Recruitment Stage
			$options = "SELECT id, description, null, CONCAT('WHERE vacancies.status=',char(39),id,char(39)) FROM lookup_vacancy_status";
			$f = new DropDownViewFilter('filter_rec_stage', $options, null, true);
			$f->setDescriptionFormat("Recruitment Stage: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(300,300,null,null),
				5=>array(400,400,null,null),
				6=>array(500,500,null,null),
				7=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}

	public function render(PDO $link, $columns)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';

			foreach($columns as $column)
			{
				echo '<th class="bottomRow" style="font-size:80%; color:#555555">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '</tr></thead>';

			echo '<tbody>';


			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				echo '<tr>';
				foreach($columns as $column)
				{
					echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
				echo '</tr>';
			}//end while
			echo '</tbody></table>';
			echo $this->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}


}
?>