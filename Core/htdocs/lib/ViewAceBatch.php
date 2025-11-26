<?php
class ViewAceBatch extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{

			$sql = <<<HEREDOC
SELECT
  tr.id AS tr_id,
  tr.username,
  tr.gender,
  tr.ethnicity,
  tr.firstnames,
  tr.surname,
  tr.dob,
  tr.ni,
  tr.uln,
  tr.home_address_line_1,
  tr.home_address_line_2,
  tr.home_address_line_3,
  tr.home_address_line_4,
  tr.home_postcode,
  tr.home_email,
  tr.home_telephone,
  tr.start_date,
  tr.closure_date,
  organisations.legal_name,
  locations.contact_name,
  locations.address_line_1,
  locations.address_line_2,
  locations.address_line_3,
  locations.address_line_4,
  locations.postcode,
  locations.contact_email,
  locations.telephone,
  organisations.code as size,
  (SELECT description from lookup_sector_types where id = organisations.sector) as sector,
  '' AS apprentice_funding
FROM
  tr
  LEFT JOIN organisations
    ON organisations.id = tr.employer_id
  LEFT JOIN locations
    ON locations.id = tr.employer_location_id
  LEFT JOIN courses_tr
    ON courses_tr.tr_id = tr.id
  LEFT JOIN courses
    ON courses.id = courses_tr.course_id
WHERE tr.status_code != 2
  AND tr.status_code != 3
  AND tr.id IN
  (SELECT DISTINCT
    tr_id
  FROM
    student_qualifications
  WHERE certificate_applied = ''
    OR certificate_applied IS NULL)
HEREDOC;


			$view = $_SESSION[$key] = new ViewAceBatch();
			$view->setSQL($sql);

			$parent_org = $_SESSION['user']->employer_id;

			$options = "SELECT id, title, null,CONCAT('WHERE tr.contract_id=',id) FROM contracts where active = 1 order by contract_year desc, title";
			$f = new DropDownViewFilter('filter_contract', $options, null, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts order by contract_year desc";
			$f = new DropDownViewFilter('filter_contract_year', $options, null, true);
			$f->setDescriptionFormat("Contract Year: %s");
			$view->addFilter($f);

			$options = <<<OPTIONS
SELECT larsfwk.FworkCode, CONCAT(larsfwk.FworkCode, ' - ', lookup.`SectorSubjectAreaTier1Desc`), NULL , CONCAT('WHERE courses_tr.framework_id IN(',GROUP_CONCAT(DISTINCT frameworks.id), ')')
FROM lars201415.`CoreReference_LARS_SectorSubjectAreaTier1_Lookup` AS lookup
INNER JOIN lars201415.`Core_LARS_Framework` AS larsfwk ON lookup.SectorSubjectAreaTier1 = larsfwk.SectorSubjectAreaTier1
INNER JOIN frameworks ON larsfwk.`FworkCode` = frameworks.`framework_code`
GROUP BY larsfwk.FworkCode;
OPTIONS;
			$f = new DropDownViewFilter('filter_ssa1', $options, null, true);
			$f->setDescriptionFormat("SSA1: %s");
			$view->addFilter($f);

			$options = <<<OPTIONS
SELECT larsfwk.FworkCode, CONCAT(larsfwk.FworkCode, ' - ', lookup.`SectorSubjectAreaTier2Desc`), NULL , CONCAT('WHERE courses_tr.framework_id IN(',GROUP_CONCAT(DISTINCT frameworks.id), ')')
FROM lars201415.`CoreReference_LARS_SectorSubjectAreaTier2_Lookup` AS lookup
INNER JOIN lars201415.`Core_LARS_Framework` AS larsfwk ON lookup.SectorSubjectAreaTier2 = larsfwk.SectorSubjectAreaTier2
INNER JOIN frameworks ON larsfwk.`FworkCode` = frameworks.`framework_code`
GROUP BY larsfwk.FworkCode;
OPTIONS;
			$f = new DropDownViewFilter('filter_ssa2', $options, null, true);
			$f->setDescriptionFormat("SSA2: %s");
			$view->addFilter($f);

			$options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type order by description asc ";
			$f = new DropDownViewFilter('filter_programme_type', $options, NULL, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type==8)
				$options = "SELECT DISTINCT courses.id, CONCAT(organisations.legal_name,'->',courses.title), null, CONCAT('WHERE courses.id=',courses.id) FROM courses LEFT JOIN organisations on organisations.id = courses.organisations_id where courses.organisations_id = $parent_org and courses.active = 1 order by courses.title";
			else
				$options = "SELECT DISTINCT courses.id, CONCAT(organisations.legal_name,'->',courses.title), null, CONCAT('WHERE courses.id=',courses.id) FROM courses LEFT JOIN organisations on organisations.id = courses.organisations_id where courses.active = 1 order by courses.title";
			$f = new DropDownViewFilter('filter_course', $options, null, true);
			$f->setDescriptionFormat("Course: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type==8)
				$options = "SELECT DISTINCT frameworks.id, title, null, CONCAT('WHERE courses_tr.framework_id=',frameworks.id) FROM frameworks where frameworks.parent_org = $parent_org and frameworks.active = 1 order by id desc, title asc";
			else
				$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE courses_tr.framework_id=',id) FROM frameworks where frameworks.active = 1 order by id desc, title desc";
			$f = new DropDownViewFilter('filter_framework', $options, null, true);
			$f->setDescriptionFormat("Framework: %s");
			$view->addFilter($f);

			// Creation Date Filter
			$format = "WHERE tr.created >= '%s'";
			$f = new DateViewFilter('filter_from_creation_date', $format, '');
			$f->setDescriptionFormat("From Creation Date: %s");
			$view->addFilter($f);

			$today_date = new Date("now");
			$today_date->format('d/m/Y');
			$format = "WHERE tr.created <= '%s'";
			$f = new DateViewFilter('filter_to_creation_date', $format, $today_date);
			$f->setDescriptionFormat("To Creation Date: %s");
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
				0=>array(1, 'Learner (asc), Start date (asc)', null, 'ORDER BY tr.surname ASC, tr.firstnames ASC, tr.start_date ASC'),
				1=>array(2, 'Leaner (desc), Start date (desc), Course (desc)', null, 'ORDER BY tr.surname DESC, tr.firstnames DESC, tr.start_date DESC'),
				2=>array(3, 'End Date (asc)', null, 'ORDER BY tr.target_date'),
				3=>array(4, 'End Date (desc)', null, 'ORDER BY tr.target_date desc'));

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}


	public function render(PDO $link)
	{//pre($this->getSQL());
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="2">';
			echo "<thead><tr><th class='topRow'>* Gender</th><th  class='topRow'>* Forename</th><th  class='topRow'>* Surname</th><th  class='topRow'> * Date Of Birth (DD/MM/YYYY)</th><th  class='topRow'>* Ethnic Group</th><th  class='topRow'> * NI Number</th><th  class='topRow'> Unique Number</th><th  class='topRow'> * Apprentice Street</th><th  class='topRow'> * Apprentice Postcode</th><th  class='topRow'> * Apprentice Town</th><th  class='topRow'> * Apprentice Country (UK = 232)</th><th  class='topRow'> Apprentice Email</th><th  class='topRow'> Apprentice Phone</th><th  class='topRow'> Apprentice Start Date (DD/MM/YYYY)</th><th  class='topRow'> * Employer Name</th><th  class='topRow'> Contact</th><th  class='topRow'>* Employer Size</th><th  class='topRow'> Contact Position</th><th  class='topRow'> Employer Street</th><th  class='topRow'> Employer Postcode</th><th  class='topRow'> Employer Town</th><th  class='topRow'> Employer Email</th><th  class='topRow'>* Employer Phone</th><th  class='topRow'>* Employer Sector</th><th  class='topRow'>* Apprentice Funding</th></tr></thead>";

			echo '<tbody>';
			while($rowxl = $st->fetch())
			{
				$tr_id = $rowxl['tr_id'];
				$sof = '"' . "/Learner/LearningDelivery[LearnAimRef='ZPROG001']/LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode" . '"';
				$res = DAO::getResultset($link, "SELECT extractvalue(ilr,$sof) FROM ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = $tr_id  ORDER BY contract_year DESC, submission DESC LIMIT 1");
				$rowxl['apprentice_funding'] = (isset($res[0][0]) AND ($res[0][0] != 'undefined'))? $res[0][0]: '&nbsp;';

				echo HTML::viewrow_opening_tag('/do.php?_action=read_training_record&id=' . $rowxl['tr_id']);
				echo '<td align="left">' . $rowxl['gender'] . '</td>';
				echo '<td align="left">' . $rowxl['firstnames'] . '</td>';
				echo '<td align="left">' . $rowxl['surname'] . '</td>';
				echo '<td align="left">' . Date::toShort($rowxl['dob']) . '</td>';
				echo '<td align="left">' . $rowxl['ethnicity'] . '</td>';
				echo '<td align="left">' . $rowxl['ni'] . '</td>';
				echo '<td align="left">' . $rowxl['uln'] . '</td>';
				echo '<td align="left">' . $rowxl['home_address_line_1'] . '</td>';
				echo '<td align="left">' . $rowxl['home_postcode'] . '</td>';
				echo '<td align="left">' . $rowxl['home_address_line_3'] . '</td>';
				echo '<td align="left">' . '232' . '</td>';
				echo '<td align="left">' . $rowxl['home_email'] . '</td>';
				echo '<td align="left">' . $rowxl['home_telephone'] . '</td>';
				echo '<td align="left">' . Date::toShort($rowxl['start_date']) . '</td>';
				echo '<td align="left">' . $rowxl['legal_name'] . '</td>';
				echo '<td align="left">' . $rowxl['contact_name'] . '</td>';
				echo '<td align="left">' . $rowxl['size'] . '</td>';
				echo '<td align="left"></td>';
				echo '<td align="left">' . $rowxl['address_line_1'] . '</td>';
				echo '<td align="left">' . $rowxl['postcode'] . '</td>';
				echo '<td align="left">' . $rowxl['address_line_3'] . '</td>';
				echo '<td align="left">' . $rowxl['contact_email'] . '</td>';
				echo '<td align="left">' . $rowxl['telephone'] . '</td>';
				echo '<td align="left">' . $rowxl['sector'] . '</td>';
				echo '<td align="left">' . $rowxl['apprentice_funding'] . '</td>';
				echo '</tr>';
			}

			echo '</tbody></table></div>';
			echo $this->getViewNavigator();

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}

}
?>