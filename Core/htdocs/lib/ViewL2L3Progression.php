<?php
class ViewL2L3Progression extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))

		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT 
	first.l03,
	first.surname,
	first.firstnames,
	first.uln,
	(SELECT contracts.title FROM contracts WHERE contracts.id = first.contract_id) AS first_contract,
	(SELECT contracts.contract_year FROM contracts WHERE contracts.id = first.contract_id) AS first_contract_year,
	((DATE_FORMAT(first.start_date,'%Y') - DATE_FORMAT(first.dob,'%Y')) - (DATE_FORMAT(first.start_date,'00-%m-%d') < DATE_FORMAT(first.dob,'00-%m-%d'))) AS first_age_at_start,
	first_frameworks.title as first_framework,
	first.start_date as first_start_date,
	first.closure_date as first_end_date,
	((DATE_FORMAT(second.start_date,'%Y') - DATE_FORMAT(second.dob,'%Y')) - (DATE_FORMAT(second.start_date,'00-%m-%d') < DATE_FORMAT(second.dob,'00-%m-%d'))) AS second_age_at_start,
	second_frameworks.title as second_framework,
	second.start_date as second_start_date,
	second.closure_date as second_end_date,
	(SELECT contracts.title FROM contracts WHERE contracts.id = second.contract_id) AS second_contract,
	(SELECT contracts.contract_year FROM contracts WHERE second.`start_date` BETWEEN contracts.`start_date` AND contracts.`end_date` LIMIT 0,1) AS second_contract_year,
    (SELECT DISTINCT tr_id FROM ilr WHERE ilr.tr_id = first.id AND LOCATE('<FundModel>25</FundModel>',ilr)>0 AND LOCATE('<LearnAimRef>ZPROG001</LearnAimRef>',ilr)=0) AS study_programme_id
FROM
	tr AS `first`
INNER JOIN student_frameworks AS first_student_framewoks ON first_student_framewoks.`tr_id` = first.`id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.`id` = first_student_framewoks.`id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN student_frameworks AS second_student_framewoks ON second_student_framewoks.`tr_id` = second.`id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.`id` = second_student_framewoks.`id`
LEFT JOIN lars201718.Core_LARS_Standard as first_lars on first_lars.StandardCode = first_frameworks.StandardCode
LEFT JOIN lars201718.Core_LARS_Standard as second_lars on second_lars.StandardCode = second_frameworks.StandardCode
WHERE first.`contract_id` IN (SELECT id FROM contracts WHERE funding_type = 1) AND second.`contract_id` IN (SELECT id FROM contracts WHERE funding_type = 1)
HEREDOC;
			$view = $_SESSION[$key] = new ViewL2L3Progression();
			$view->setSQL($sql);


			// Add view filters
			$options = array(
				0=>array(0, '1. Level 2 to Level 3 Progression', null, 'WHERE first.status_code = 2 AND ((first_frameworks.`framework_type` = 3 AND first_frameworks.`framework_type` IS NOT NULL) or (first_lars.NotionalEndLevel is not null and first_lars.NotionalEndLevel=2)) AND second.`start_date` > first.`start_date` AND ((second_frameworks.`framework_type` in (2,17) and second_frameworks.framework_type is not null) or (second_lars.NotionalEndLevel is not null and second_lars.NotionalEndLevel = 3)) AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2) AND second.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)'),
				1=>array(1, '2. Level 3 to Level 4 Progression', null, 'WHERE first.status_code = 2 AND ((first_frameworks.`framework_type` = 2 AND first_frameworks.`framework_type` IS NOT NULL) or (first_lars.NotionalEndLevel is not null and first_lars.NotionalEndLevel=3)) AND second.`start_date` > first.`start_date` AND ((second_frameworks.`framework_type` = 20 and second_frameworks.framework_type is not null) or (second_lars.NotionalEndLevel is not null and second_lars.NotionalEndLevel = 4)) AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2) AND second.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)'),
				2=>array(2, '3. Traineeship to Apprenticeship Progression', null, 'WHERE first.status_code = 2 AND first_frameworks.`framework_type` = 24 AND first_frameworks.`framework_type` IS NOT NULL AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` NOT IN (24) and second_frameworks.framework_type is not null AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2) AND second.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)'),
				3=>array(3, '4. Study Programme to Traineeship Progression', null, 'WHERE first.status_code != 1 AND first_frameworks.`framework_type` IS NULL  AND first_frameworks.`framework_code` IS NULL AND first.id IN (SELECT tr_id FROM ilr WHERE LOCATE(\'<FundModel>25</FundModel>\',ilr)>0 AND LOCATE(\'<LearnAimRef>ZPROG001</LearnAimRef>\',ilr)=0) AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` = 24 AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2) AND second.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)'),
				4=>array(4, '5. Level 2 to Level 3 Loan Progression', null, 'WHERE first_frameworks.`framework_type` = 3 AND second_frameworks.`framework_type` = 17 AND first_frameworks.`framework_code` IS NOT NULL AND second_frameworks.`framework_code` IS NULL AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2) AND second.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2);'));
			$f = new DropDownViewFilter('filter_report_type', $options, 0, false);
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('where second.start_date >= ', char(39),contract_year,'08-01',char(39)) FROM contracts ORDER BY contract_year DESC";
			$f = new DropDownViewFilter('filter_first_contract_year', $options, null, true);
			$f->setDescriptionFormat("First Contract Year: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('where second.start_date >= ', char(39),contract_year,'-08-01',char(39),' and second.start_date<=',char(39),contract_year+1,'-07-31',char(39)) FROM contracts ORDER BY contract_year DESC";
			$f = new DropDownViewFilter('filter_second_contract_year', $options, null, true);
			$f->setDescriptionFormat("Progression Year: %s");
			$view->addFilter($f);


		}

		return $_SESSION[$key];
	}


	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
        //pre($this->getSQL());
		if($st)
		{
            $type = $this->getFilterValue('filter_report_type');
            if($type==0)
            {
                $first = "Level 2";
                $second = "Level 3";
            }
            elseif($type==1)
            {
                $first = "Level 3";
                $second = "Level 4";
            }
            else
            {
                $first = "Traineeship";
                $second = "Apprenticeship";
            }
			//echo $this->getViewNavigator();
			echo '<div align="center"><table id="dataMatrix" style="" class="resultset sortData" border="0" cellspacing="0" cellpadding="6">';
			echo <<<HEREDOC
	<thead>
		<th>&nbsp;</th>
		<th>L03</th>
		<th>Surname</th>
		<th>Firstnames</th>
		<th>ULN</th>
		<th>$first Contract</th>
		<th>$first Age at start</th>
		<th>$first Framework</th>
		<th>$first Start Date</th>
		<th>$first End Date</th>
		<th>$second Age at start</th>
		<th>$second Framework</th>
		<th>$second Start Date</th>
		<th>$second End Date</th>
		<th>$second Contract</th>
	</thead>
HEREDOC;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="center">' . HTML::cell($row['l03']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['surname']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['uln']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['first_contract']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['first_age_at_start']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['first_framework']) . "</td>";
				echo '<td align="center">' . HTML::cell(Date::to($row['first_start_date'], Date::SHORT)) . "</td>";
				echo '<td align="center">' . HTML::cell(Date::to($row['first_end_date'], Date::SHORT)) . "</td>";
				echo '<td align="center">' . HTML::cell($row['second_age_at_start']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['second_framework']) . "</td>";
				echo '<td align="center">' . HTML::cell(Date::to($row['second_start_date'], Date::SHORT)) . "</td>";
				echo '<td align="center">' . HTML::cell(Date::to($row['second_end_date'], Date::SHORT)) . "</td>";
				echo '<td align="center">' . HTML::cell($row['second_contract']) . "</td>";
				echo '</tr>';
			}
		}
		echo '</tbody></table></div>';
	}
}
?>