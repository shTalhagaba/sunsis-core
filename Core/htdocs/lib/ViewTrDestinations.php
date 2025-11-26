<?php
class ViewTrDestinations extends View
{

	public static function createAndPopulateLLDDs(PDO $link)
	{
		ini_set('memory_limit','1024M');
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS learner_lldds_info");
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `learner_lldds_info` (
  `tr_id` INT(11) DEFAULT NULL,
  `contract_id` INT(11) DEFAULT NULL,
  `LLDDHealthProb` varchar(10) DEFAULT NULL,
  `PrimaryLLDD` varchar(10) DEFAULT NULL,
  `EHC` varchar(10) DEFAULT NULL,
	KEY `i_tr_id` (`tr_id`)
) ENGINE 'MEMORY'
HEREDOC;
		$link->query($sql);
		$sql = <<<SQL
INSERT INTO learner_lldds_info
SELECT
	ilr.`tr_id`,
	ilr.`contract_id`,
	extractvalue(ilr, '/Learner/LLDDHealthProb') AS LLDDHealthProb,
	extractvalue(ilr, '/Learner/LLDDandHealthProblem[PrimaryLLDD="1"]/LLDDCat') AS PrimaryLLDD,
	extractvalue(ilr, '/Learner/LearnerFAM[LearnFAMType="EHC"]/LearnFAMCode') AS EHC
FROM
	ilr
GROUP BY
	tr_id

ORDER BY
	contract_id DESC, submission DESC
;
SQL;
		DAO::execute($link, $sql);
	}

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		self::createAndPopulateLLDDs($link);

		if(!isset($_SESSION[$key]))
		{
			// Create new view object

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type== User::TYPE_SYSTEM_VIEWER)
			{
				$where = '';
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==User::TYPE_MANAGER || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp')" ;
			}
			else
			{
				throw new Exception('You are not authorised to view this report.');
			}


			$sql = <<<HEREDOC
SELECT
	tr.gender,
	destinations.tr_id,
	CONCAT(tr.`firstnames` , ' ', tr.`surname`) AS learner_name,
	(SELECT title FROM contracts WHERE id = tr.contract_id) AS contract_title,
	(SELECT legal_name FROM organisations WHERE organisation_type = 3 AND id = tr.provider_id) AS provider,
	(SELECT legal_name FROM organisations WHERE organisation_type = 2 AND id = tr.employer_id) AS employer,
	DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS planned_end_date,
	DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS actual_end_date,
	(SELECT CONCAT(TYPE, ' - ', description) FROM central.lookup_destination_outcome_type WHERE TYPE = destinations.`outcome_type`) AS outcome_type,
	(SELECT CONCAT(TYPE, ' - ', CODE, ' - ', description) FROM central.lookup_destination_outcome_code WHERE type_code = destinations.`type_code`) AS outcome_code,
	DATE_FORMAT(destinations.`outcome_start_date`, '%d/%m/%Y') AS outcome_start_date,
	DATE_FORMAT(destinations.`outcome_end_date`, '%d/%m/%Y') AS outcome_end_date,
	DATE_FORMAT(destinations.`outcome_collection_date`, '%d/%m/%Y') AS outcome_collection_date,
	tr.status_code,
	contracts.title AS contract,
	courses.title AS course,
	tr.home_telephone,
	tr.home_email,
	(SELECT description FROM lookup_programme_type WHERE code = courses.programme_type) AS programme_type,
	destinations.id AS dest_id,
	CASE learner_lldds_info.LLDDHealthProb
		WHEN '1' THEN '1 Learner considers himself or herself to have a learning difficulty and/or disability and/or health problem'
		WHEN '2' THEN '2 Learner does not consider himself or herself to have a learning difficulty and/or disability and/or health problem'
		WHEN '9' THEN '9 No information provided by the learner'
	END AS lldd_health_prob,
	CASE learner_lldds_info.PrimaryLLDD
	 	WHEN '1' THEN '1 Emotional/behavioural difficulties'
		WHEN '2' THEN '2 Multiple disabilities'
		WHEN '3' THEN '3 Multiple learning difficulties'
		WHEN '4' THEN '4 Visual impairment'
		WHEN '5' THEN '5 Hearing impairment'
		WHEN '6' THEN '6 Disability affecting mobility'
		WHEN '7' THEN '7 Profound complex disabilities'
		WHEN '8' THEN '8 Social and emotional difficulties'
		WHEN '9' THEN '9 Mental health difficulty'
		WHEN '10' THEN '10 Moderate learning difficulty'
		WHEN '11' THEN '11 Severe learning difficulty'
		WHEN '12' THEN '12 Dyslexia'
		WHEN '13' THEN '13 Dyscalculia'
		WHEN '14' THEN '14 Autism spectrum disorder'
		WHEN '15' THEN '15 Aspergers syndrome'
		WHEN '16' THEN '16 Temporary disability after illness (for example post-viral) or accident'
		WHEN '17' THEN '17 Speech, Language and Communication Needs'
		WHEN '93' THEN '93 Other physical disability'
		WHEN '94' THEN '94 Other specific learning difficulty (e.g. Dyspraxia)'
		WHEN '95' THEN '95 Other medical condition (for example epilepsy, asthma, diabetes)'
		WHEN '96' THEN '96 Other learning difficulty'
		WHEN '97' THEN '97 Other disability'
		WHEN '98' THEN '98 Prefer not to say'
		WHEN '99' THEN '99 Not provided'
	END AS primary_lldd,
	CASE learner_lldds_info.EHC
		WHEN '1' THEN 'Yes'
	END AS ehc
FROM
	tr LEFT JOIN destinations ON tr.id = destinations.`tr_id`
	LEFT JOIN contracts ON tr.contract_id = contracts.id
	LEFT JOIN courses_tr ON tr.id = courses_tr.tr_id
	LEFT JOIN courses ON courses_tr.course_id = courses.id
	LEFT JOIN learner_lldds_info ON (learner_lldds_info.tr_id = tr.id AND learner_lldds_info.contract_id = tr.contract_id)
$where

HEREDOC;

			$view = $_SESSION[$key] = new ViewTrDestinations();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);


			$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%7%" order by legal_name';
			$f = new DropDownViewFilter('organisation', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			$options = 'SELECT type_code, CONCAT(type, " - ", code, " - ", description) AS description, type, CONCAT("WHERE destinations.type_code=",char(39),type_code,char(39)) FROM central.lookup_destination_outcome_code order by description';
			$f = new DropDownViewFilter('filter_outcome_code', $options, null, true);
			$f->setDescriptionFormat("Outcome Code: %s");
			$view->addFilter($f);

			$options = 'SELECT type, CONCAT(type, " - ", description) AS description, null, CONCAT("WHERE destinations.outcome_type=",char(39),type,char(39)) FROM central.lookup_destination_outcome_type order by type';
			$f = new DropDownViewFilter('filter_outcome_type', $options, null, true);
			$f->setDescriptionFormat("Outcome Type: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Learner (asc)', null, 'ORDER BY learner_name'),
				1=>array(2, 'Learner (desc)', null, 'ORDER BY learner_name DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Surname contains: %s");
			$view->addFilter($f);

			// Firstname Filter
			$f = new TextboxViewFilter('filter_firstname', "WHERE tr.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);

			$options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type ORDER BY description ";
			$f = new DropDownViewFilter('filter_programme_type', $options, null, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);

			// ULN Filter
			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("ULN: %s");
			$view->addFilter($f);

			// Add view filters
			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
				2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
				3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
				4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
				5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
				6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
				7=>array(7, '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
			$f = new DropDownViewFilter('filter_record_status', $options, 0, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Yes', null, 'WHERE outcome_type IS NOT NULL '),
				2=>array(2, 'No', null, 'WHERE outcome_type IS NULL '));
			$f = new DropDownViewFilter('filter_destination_flag', $options, 1, false);
			$f->setDescriptionFormat("Destination Flag: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1 Learner considers himself or herself to have a learning difficulty and/or disability and/or health problem', null, 'WHERE learner_lldds_info.LLDDHealthProb="1" '),
				2=>array(2, '2 Learner does not consider himself or herself to have a learning difficulty and/or disability and/or health problem', null, 'WHERE learner_lldds_info.LLDDHealthProb="2" '),
				3=>array(3, '9 No information provided by the learner', null, 'WHERE learner_lldds_info.LLDDHealthProb="9" '));
			$f = new DropDownViewFilter('filter_LLDDHealthProb', $options, 0, false);
			$f->setDescriptionFormat("LLDDHealthProb: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, '1 Emotional/behavioural difficulties', null, 'WHERE learner_lldds_info.PrimaryLLDD="1" '),
				2=>array(2, '2 Multiple disabilities', null, 'WHERE learner_lldds_info.PrimaryLLDD="2" '),
				3=>array(3, '3 Multiple learning difficulties', null, 'WHERE learner_lldds_info.PrimaryLLDD="3" '),
				4=>array(4, '4 Visual impairment', null, 'WHERE learner_lldds_info.PrimaryLLDD="4" '),
				5=>array(5, '5 Hearing impairment', null, 'WHERE learner_lldds_info.PrimaryLLDD="5" '),
				6=>array(6, '6 Disability affecting mobility', null, 'WHERE learner_lldds_info.PrimaryLLDD="6" '),
				7=>array(7, '7 Profound complex disabilities', null, 'WHERE learner_lldds_info.PrimaryLLDD="7" '),
				8=>array(8, '8 Social and emotional difficulties', null, 'WHERE learner_lldds_info.PrimaryLLDD="8" '),
				9=>array(9, '9 Mental health difficulty', null, 'WHERE learner_lldds_info.PrimaryLLDD="9" '),
				10=>array(10, '10 Moderate learning difficulty', null, 'WHERE learner_lldds_info.PrimaryLLDD="10" '),
				11=>array(11, '11 Severe learning difficulty', null, 'WHERE learner_lldds_info.PrimaryLLDD="11" '),
				12=>array(12, '12 Dyslexia', null, 'WHERE learner_lldds_info.PrimaryLLDD="12" '),
				13=>array(13, '13 Dyscalculia', null, 'WHERE learner_lldds_info.PrimaryLLDD="13" '),
				14=>array(14, '14 Autism spectrum disorder', null, 'WHERE learner_lldds_info.PrimaryLLDD="14" '),
				15=>array(15, '15 Asperger\'s syndrome', null, 'WHERE learner_lldds_info.PrimaryLLDD="15" '),
				16=>array(16, '16 Temporary disability after illness (for example post-viral) or accident', null, 'WHERE learner_lldds_info.PrimaryLLDD="16" '),
				17=>array(17, '17 Speech, Language and Communication Needs', null, 'WHERE learner_lldds_info.PrimaryLLDD="17" '),
				18=>array(93, '93 Other physical disability', null, 'WHERE learner_lldds_info.PrimaryLLDD="93" '),
				19=>array(94, '94 Other specific learning difficulty (e.g. Dyspraxia)', null, 'WHERE learner_lldds_info.PrimaryLLDD="94" '),
				20=>array(95, '95 Other medical condition (for example epilepsy, asthma, diabetes)', null, 'WHERE learner_lldds_info.PrimaryLLDD="95" '),
				21=>array(96, '96 Other learning difficulty', null, 'WHERE learner_lldds_info.PrimaryLLDD="96" '),
				22=>array(97, '97 Other disability', null, 'WHERE learner_lldds_info.PrimaryLLDD="97" '),
				23=>array(98, '98 Prefer not to say', null, 'WHERE learner_lldds_info.PrimaryLLDD="98" '),
				24=>array(99, '99 Not provided', null, 'WHERE learner_lldds_info.PrimaryLLDD="99" '),
			);
			$f = new DropDownViewFilter('filter_PrimaryLLDD', $options, 0, false);
			$f->setDescriptionFormat("PrimaryLLDD: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Yes', null, 'WHERE learner_lldds_info.EHC="1" '));
			$f = new DropDownViewFilter('filter_EHC', $options, 0, false);
			$f->setDescriptionFormat("EHC: %s");
			$view->addFilter($f);

			//$options = " SELECT * FROM (SELECT 'SHOW_ALL', 'Show All', NULL, CONCAT('WHERE tr.contract_id IN (', GROUP_CONCAT(contracts.id), ')') FROM contracts order by contract_year desc ) AS a ";
			//$options .= " UNION ALL ";
			$options = " SELECT id, title, NULL,CONCAT('WHERE tr.contract_id=',id) FROM contracts ORDER BY contract_year desc";
			$f = new CheckboxViewFilter('filter_contract', $options, array());
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts where active = 1 order by contract_year desc";
			$f = new DropDownViewFilter('filter_contract_year', $options, null, true);
			$f->setDescriptionFormat("Contract Year: %s");
			$view->addFilter($f);

			// Start Date Filter
			$format = "WHERE tr.start_date >= '%s'";
			$f = new DateViewFilter('filter_from_start_date', $format, '');
			$f->setDescriptionFormat("From TR start date: %s");
			$view->addFilter($f);

			$format = "WHERE tr.start_date <= '%s'";
			$f = new DateViewFilter('filter_to_start_date', $format, '');
			$f->setDescriptionFormat("To TR start date: %s");
			$view->addFilter($f);

			// Target date filter
			$format = "WHERE tr.target_date >= '%s'";
			$f = new DateViewFilter('filter_from_planned_date', $format, '');
			$f->setDescriptionFormat("From TR Planned End date: %s");
			$view->addFilter($f);

			$format = "WHERE tr.target_date <= '%s'";
			$f = new DateViewFilter('filter_to_planned_date', $format, '');
			$f->setDescriptionFormat("To target date: %s");
			$view->addFilter($f);

			// Closure date filter
			$format = "WHERE tr.closure_date >= '%s'";
			$f = new DateViewFilter('filter_from_close_date', $format, '');
			$f->setDescriptionFormat("From closure date: %s");
			$view->addFilter($f);

			$format = "WHERE tr.closure_date <= '%s'";
			$f = new DateViewFilter('filter_to_close_date', $format, '');
			$f->setDescriptionFormat("To closure date: %s");
			$view->addFilter($f);

			if(DB_NAME=="am_lead")
			{
				$format = "WHERE tr.marked_date >= '%s'";
				$f = new DateViewFilter('filter_from_marked_date', $format, '');
				$f->setDescriptionFormat("From marked date: %s");
				$view->addFilter($f);

				$format = "WHERE tr.marked_date <= '%s'";
				$f = new DateViewFilter('filter_to_marked_date', $format, '');
				$f->setDescriptionFormat("To marked date: %s");
				$view->addFilter($f);

			}

		}

		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{//pre($this->getSQL());
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th>';
			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column))) . '</th>';
			}
			echo '</tr></thead><tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['tr_id']);
				$folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
				switch($row['status_code'])
				{
					case 1:
						echo "<td><img src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" /></td>";
						break;

					case 2:
						echo "<td><img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" /></td>";
						break;

					case 3:
					case 6:
						echo "<td><img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" /></td>";
						break;

					case 4:
					case 5:
						echo "<td><img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" /></td>";
						break;

					default:
						echo '<td>?</td>';
						break;
				}
				foreach($columns as $column)
				{
					echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
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