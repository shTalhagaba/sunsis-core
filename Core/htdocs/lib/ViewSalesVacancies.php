<?php
class ViewSalesVacancies extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$year = date('Y');
			$previous_year = $year - 1;
			$next_year = $year + 1;

			$where = ' WHERE vacancies.status = 4';

			$sql = <<<SQL
SELECT
	(SELECT CONCAT(s.firstnames, ' ', s.surname) FROM users s WHERE s.username = vacancies.brm) AS BRM,
	organisations.`legal_name` AS employer,
	(IF(vacancies.active = 1, 'Yes', 'No')) AS active_vacancy,
	(SELECT description FROM lookup_vacancy_status WHERE id = vacancies.`status`) AS vacancy_status,
	IF(date_status_changed IS NOT NULL, DATEDIFF(CURDATE(),date_status_changed),'') AS days_in_current_stage,
	DATE_FORMAT(vacancies.`created`, '%d/%m/%Y	') AS vacancy_creation_date,
	DATEDIFF(CURDATE(), vacancies.created) AS days_since_vacancy_created,
	DATE_FORMAT(vacancies.`expiry_date`, '%d/%m/%Y	') AS closing_date,
	vacancies.`postcode` AS delivery_location,
	(select description from lookup_vacancy_type where id = vacancies.type) as sector,
	(select description from lookup_vacancy_app_type where id = vacancies.apprenticeship_type) as apprenticeship_type,
	CONCAT(IFNULL(locations.`address_line_1`,''), ' ', IFNULL(locations.`address_line_2`,''), ' ', IFNULL(locations.`address_line_3`,''), ' ', IFNULL(locations.`address_line_4`,'')) AS employer_location,
	locations.`postcode` AS employer_postcode,
	vacancies.`inductor`,
	vacancies.employer_id,
	IF(expiry_date IS NOT NULL, DATEDIFF(expiry_date, CURDATE()),'') AS days_left,
	#IF(date_status_changed IS NOT NULL, DATEDIFF(CURDATE(), vacancies.created),'') AS days_open,
	(SELECT description FROM lookup_vacancy_regions WHERE id = vacancies.region) AS region,
	(SELECT COUNT(DISTINCT candidate_id) FROM candidate_applications  INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.id WHERE candidate_applications.vacancy_id = vacancies.id AND candidate.`username` IS NOT NULL) AS candidates_converted_to_learners,
	(SELECT GROUP_CONCAT(DISTINCT candidate.username SEPARATOR ';') FROM candidate_applications  INNER JOIN candidate ON candidate_applications.`candidate_id` = candidate.id WHERE vacancy_id = vacancies.id AND candidate.`username` IS NOT NULL ) AS candidates,
	MONTHNAME(date_expected_to_fill) AS month_expected_to_fill,
	MONTH(date_expected_to_fill) AS fill_month,
	YEAR(date_expected_to_fill) AS fill_year,
	IF(date_status_changed IS NOT NULL, DATEDIFF(date_status_changed, vacancies.`created`),'') AS days_creation_to_filled

FROM
	vacancies
	LEFT JOIN organisations ON vacancies.`employer_id` = organisations.`id`
	LEFT JOIN locations ON locations.`organisations_id` = organisations.`id`



$where

SQL;

// Create new view object

			$view = $_SESSION[$key] = new ViewSalesVacancies();
			$view->setSQL($sql);

			$parent_org = $_SESSION['user']->employer_id;

			// Employer Filter
			if($_SESSION['user']->type==8)
				$options = "SELECT id, legal_name, null, CONCAT('WHERE vacancies.employer_id=',id) FROM organisations WHERE (organisation_type like '%2%' or organisation_type like '%6%') and organisations.parent_org=$parent_org order by legal_name";
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE vacancies.employer_id=',id) FROM organisations WHERE organisation_type like '%2%' or organisation_type like '%6%' order by legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer/ School: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_source', "WHERE vacancies.source LIKE '%s%%'", null);
			$f->setDescriptionFormat("Source: %s");
			$view->addFilter($f);

			$forecast_fill_month_dropdown = array(array(1,'January'),array(2,'February'),array(3,'March'),array(4,'April'),array(5,'May'),array(6,'June'),array(7,'July'),array(8,'August'),array(9,'September'),array(10,'October'),array(11,'November'),array(12,'December'));

			// Month Expected Filter
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'January', null, ' HAVING fill_month = 1 '),
				2=>array(2, 'February', null, ' HAVING fill_month = 2 '),
				3=>array(3, 'March', null, ' HAVING fill_month = 3 '),
				4=>array(4, 'April', null, ' HAVING fill_month = 4 '),
				5=>array(5, 'May', null, ' HAVING fill_month = 5 '),
				6=>array(6, 'June', null, ' HAVING fill_month = 6 '),
				7=>array(7, 'July', null, ' HAVING fill_month = 7 '),
				8=>array(8, 'August', null, ' HAVING fill_month = 8 '),
				9=>array(9, 'September', null, ' HAVING fill_month = 9 '),
				10=>array(10, 'October', null, ' HAVING fill_month = 10 '),
				11=>array(11, 'November', null, ' HAVING fill_month = 11 '),
				12=>array(12, 'December', null, ' HAVING fill_month = 12 '));
			$f = new DropDownViewFilter('filter_month_expected', $options, 0, false);
			$f->setDescriptionFormat("Month Expected to Fill: %s");
			$view->addFilter($f);

			// Month Expected Filter
			$options = array(
				0=>array(0, $previous_year, null, ' HAVING fill_year = ' . $previous_year),
				1=>array(1, $year, null, ' HAVING fill_year = ' . $year),
				2=>array(2, $year + 1, null, ' HAVING fill_year = ' . $next_year));
			$f = new DropDownViewFilter('filter_year_expected', $options, 1, false);
			$f->setDescriptionFormat("Year Expected to Fill: %s");
			$view->addFilter($f);

			// Date filters
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);

			// Start Date Filter
			$format = "WHERE vacancies.created >= '%s'";
			$f = new DateViewFilter('filter_from_create_date', $format, '');
			$f->setDescriptionFormat("From Date Created: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE vacancies.created <= '%s'";
			$f = new DateViewFilter('filter_to_create_date', $format, '');
			$f->setDescriptionFormat("To Date Created: %s");
			$view->addFilter($f);

			// induction date filter
			$format = "WHERE vacancies.induction_date >= '%s'";
			$f = new DateViewFilter('filter_from_induction_date', $format, '');
			$f->setDescriptionFormat("From induction date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "WHERE vacancies.induction_date <= '%s'";
			$f = new DateViewFilter('filter_to_induction_date', $format, '');
			$f->setDescriptionFormat("To induction date: %s");
			$view->addFilter($f);

			// BRM
			if($_SESSION['user']->type==8)
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE vacancies.brm=',char(39),id,char(39),' OR vacancies.brm=',CHAR(39),username,CHAR(39)) FROM users where type=23 and employer_id = $parent_org order by firstnames";
			else
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE vacancies.brm=',char(39),id,char(39),' OR vacancies.brm=',CHAR(39),username,CHAR(39)) FROM users where type=23 order by firstnames";
			$f = new DropDownViewFilter('filter_brm', $options, null, true);
			$f->setDescriptionFormat("Business Resource Manager: %s");
			$view->addFilter($f);

			//Apprenticeship type
			$options = "SELECT id, description, null, CONCAT('WHERE vacancies.apprenticeship_type=',char(39),id,char(39)) FROM lookup_vacancy_app_type ORDER BY description";
			$f = new DropDownViewFilter('filter_app_type', $options, null, true);
			$f->setDescriptionFormat("Apprenticeship Type: %s");
			$view->addFilter($f);

			//Vacancies Sector(type)
			$options = "SELECT id, description, null, CONCAT('WHERE vacancies.type=',char(39),id,char(39)) FROM lookup_vacancy_type  WHERE id != 14 ORDER BY description asc;";
			$f = new DropDownViewFilter('filter_sector', $options, null, true);
			$f->setDescriptionFormat("Sector: %s");
			$view->addFilter($f);

			//Recruitment Stage
			$options = "SELECT id, description, null, CONCAT('WHERE vacancies.status=',char(39),id,char(39)) FROM lookup_vacancy_status ORDER BY description";
			$f = new DropDownViewFilter('filter_rec_stage', $options, null, true);
			$f->setDescriptionFormat("Recruitment Stage: %s");
			$view->addFilter($f);

			//Region  Filter
			$options = "SELECT id, description, null, CONCAT('WHERE vacancies.region=',char(39),id,char(39)) FROM lookup_vacancy_regions ORDER BY description";
			$f = new DropDownViewFilter('filter_region', $options, null, true);
			$f->setDescriptionFormat("Region: %s");
			$view->addFilter($f);

			// Add due diligence
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Yes', null, 'where vacancies.dd = 1'),
				2=>array(2, 'No', null, 'where vacancies.dd = 0 OR vacancies.dd is null'));
			$f = new DropDownViewFilter('filter_dd', $options, 0, false);
			$f->setDescriptionFormat("Progress: %s");
			$view->addFilter($f);

			// Add age required
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Yes', null, 'where vacancies.age = 1'),
				2=>array(2, 'No', null, 'where vacancies.age = 0 OR vacancies.age is null'));
			$f = new DropDownViewFilter('filter_age_required', $options, 0, false);
			$f->setDescriptionFormat("Age Required: %s");
			$view->addFilter($f);

			// Add active vacancy filter
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Yes', null, 'where vacancies.active = 1'),
				2=>array(2, 'No', null, 'where vacancies.active = 0 OR vacancies.active is null'));
			$f = new DropDownViewFilter('filter_active_vacancy', $options, 0, false);
			$f->setDescriptionFormat("Age Required: %s");
			$view->addFilter($f);

			// Add induction confirmed
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Yes', null, 'where vacancies.induction_confirmed = 1'),
				2=>array(2, 'No', null, 'where vacancies.induction_confirmed = 0 OR vacancies.induction_confirmed is null'));
			$f = new DropDownViewFilter('filter_induction_confirmed', $options, 0, false);
			$f->setDescriptionFormat("Induction Confirmed: %s");
			$view->addFilter($f);

			// At Risk
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Yes', null, 'where vacancies.at_risk = 1'),
				2=>array(2, 'No', null, 'where vacancies.at_risk = 0 OR vacancies.at_risk is null'));
			$f = new DropDownViewFilter('filter_at_risk', $options, 0, false);
			$f->setDescriptionFormat("At Risk: %s");
			$view->addFilter($f);

			// delivery location
			$f = new TextboxViewFilter('filter_postcode', "where vacancies.postcode LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Delivery Location: %s");
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

			$options = array(
				0=>array(1, 'Apprenticeship Type', null, 'ORDER BY apprenticeship_type '),
				1=>array(2, 'BRM', null, 'ORDER BY vacancies.brm '),
				2=>array(3, 'Region', null, 'ORDER BY region'),
				3=>array(4, 'Employer', null, 'ORDER BY employer'));

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_name', "WHERE organisations.legal_name LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Name: %s");
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
			echo '<div align="center"><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead>';
			foreach($columns as $column)
			{
				echo '<th class="topRow" style="font-size:80%; color:#555555">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '</tr></thead>';

			echo '<tbody>';


			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				echo '<tr>';
				foreach($columns as $column)
				{
					if($column == 'date_submitted')
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':Date::toShort($row[$column])):'&nbsp') . '</td>';
					elseif($column == 'employer')
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':'<a href="do.php?_action=read_employer&id='.$row['employer_id'].'">'.$row[$column]).'</a>':'&nbsp') . '</td>';
					elseif($column == 'candidates')
					{
						$candidates = explode(';', $row['candidates']);
						$candidate_with_links = '';
						foreach($candidates AS $c)
						{
							$candidate_with_links .= '<a href="do.php?_action=read_user&username=' . $c . '">' . $c . '</a>, ';
						}
						echo '<td align="left">' . rtrim($candidate_with_links,", ") . '</td>';
					}
					else
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
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