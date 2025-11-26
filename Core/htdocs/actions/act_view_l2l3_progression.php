<?php
class view_l2l3_progression implements IAction
{
    public function execute(PDO $link)
    {
	    $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_l2l3_progression", "View Progressions");

        $view = ViewL2L3Progression::getInstance($link);
        $view->refresh($link, $_REQUEST);

        DAO::execute($link, "SET SESSION group_concat_max_len = 10000000;");

    $l2_achievers = DAO::getResultset($link, "SELECT IF(MONTH(first.closure_date)>=8,YEAR(first.closure_date),YEAR(first.closure_date)-1) AS first_end_year, COUNT(first.id) AS l2_achievres
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
LEFT JOIN lars201718.Core_LARS_Standard AS first_lars ON first_lars.StandardCode = first_frameworks.StandardCode
WHERE first.status_code = 2 AND ((first_frameworks.`framework_type` = 3 AND first_frameworks.`framework_type` IS NOT NULL) OR (first_lars.NotionalEndLevel IS NOT NULL AND first_lars.NotionalEndLevel=2))
AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
GROUP BY first_end_year;
");

    $l3_achievers = DAO::getResultset($link, "SELECT IF(MONTH(first.closure_date)>=8,YEAR(first.closure_date),YEAR(first.closure_date)-1) AS first_end_year, COUNT(first.id) AS l2_achievres, GROUP_CONCAT(first.id) as trs
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
LEFT JOIN lars201718.Core_LARS_Standard AS first_lars ON first_lars.StandardCode = first_frameworks.StandardCode
WHERE first.status_code = 2 AND ((first_frameworks.`framework_type` = 2 AND first_frameworks.`framework_type` IS NOT NULL) OR (first_lars.NotionalEndLevel IS NOT NULL AND first_lars.NotionalEndLevel=3))
AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
GROUP BY first_end_year;
");


    $traineeship_leavers = DAO::getResultset($link, "SELECT IF(MONTH(first.closure_date)>=8,YEAR(first.closure_date),YEAR(first.closure_date)-1) AS first_end_year, COUNT(first.id) AS l2_achievres
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
WHERE first.status_code != 1 AND first_frameworks.`framework_type` = 24 AND first_frameworks.`framework_type` IS NOT NULL
AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
GROUP BY first_end_year;
");

    $l3_progression = DAO::getResultset($link, "SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id)
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
LEFT JOIN lars201718.Core_LARS_Standard AS first_lars ON first_lars.StandardCode = first_frameworks.StandardCode
LEFT JOIN lars201718.Core_LARS_Standard AS second_lars ON second_lars.StandardCode = second_frameworks.StandardCode
WHERE first.status_code = 2 AND ((first_frameworks.`framework_type` = 3 AND first_frameworks.`framework_type` IS NOT NULL) or (first_lars.NotionalEndLevel is not null and first_lars.NotionalEndLevel=2))
AND second.`start_date` > first.`start_date` AND ((second_frameworks.`framework_type` in (2,17) and second_frameworks.framework_type is not null) or (second_lars.NotionalEndLevel is not null and second_lars.NotionalEndLevel = 3))
AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
AND second.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
GROUP BY second_start_year;
");

    $l4_progression = DAO::getResultset($link, "SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id)
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
LEFT JOIN lars201718.Core_LARS_Standard AS first_lars ON first_lars.StandardCode = first_frameworks.StandardCode
LEFT JOIN lars201718.Core_LARS_Standard AS second_lars ON second_lars.StandardCode = second_frameworks.StandardCode
WHERE first.status_code = 2
AND ((first_frameworks.`framework_type` = 2 AND first_frameworks.`framework_type` IS NOT NULL) or (first_lars.NotionalEndLevel is not null and first_lars.NotionalEndLevel=3))
AND second.`start_date` > first.`start_date`
AND ((second_frameworks.`framework_type` = 20 and second_frameworks.framework_type is not null) or (second_lars.NotionalEndLevel is not null and second_lars.NotionalEndLevel = 4))
AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
AND second.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
GROUP BY second_start_year;
");

    $app_progression = DAO::getResultset($link, "SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id)
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
WHERE first.status_code = 2 AND first_frameworks.`framework_type` = 24 AND first_frameworks.`framework_type` IS NOT NULL
AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` NOT IN (24) and second_frameworks.framework_type is not null
AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
AND second.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
GROUP BY second_start_year;
");

    $study_programme_leavers = DAO::getResultset($link, "SELECT IF(MONTH(first.closure_date)>=8,YEAR(first.closure_date),YEAR(first.closure_date)-1) AS first_end_year, COUNT(first.id) AS l2_achievres
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
WHERE first.status_code != 1 AND first_frameworks.`framework_type` IS NULL  AND first_frameworks.`framework_code` IS NULL
AND tr_id IN (SELECT tr_id FROM ilr WHERE LOCATE('<FundModel>25</FundModel>',ilr)>0 AND LOCATE('<LearnAimRef>ZPROG001</LearnAimRef>',ilr)=0)
AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
GROUP BY first_end_year;");

    $traineeship_progression = DAO::getResultset($link, "SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id)
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
WHERE first.status_code != 1 AND first_frameworks.`framework_type` IS NULL  AND first_frameworks.`framework_code` IS NULL
AND first.id IN (SELECT tr_id FROM ilr WHERE LOCATE('<FundModel>25</FundModel>',ilr)>0 AND LOCATE('<LearnAimRef>ZPROG001</LearnAimRef>',ilr)=0)
AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` = 24
AND first.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
AND second.contract_id IN (SELECT id FROM contracts WHERE contract_location != 2)
GROUP BY second_start_year;
");

	    if($subaction == 'export_csv')
	    {
		    $this->exportToCSV($link, $view);
	    }

        require_once('tpl_view_l2l3_progression.php');
    }

	private function exportToCSV(PDO $link, View $view)
	{
		$rows = array();
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$result = DAO::getResultset($link, $statement, DAO::FETCH_ASSOC);
		foreach($result AS $rs)
			$rows[] = $rs;
		unset($result);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename=ProgressionReport.csv');
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
		{
			header('Pragma: public');
			header('Cache-Control: max-age=0');
		}
		$line = '';
		$line .= 'L03,Surname,Firstnames,ULN,First Contract,First Age at start,First Framework,First Start Date,First End Date,Second Age at Start,Second Framework,Second Start Date,Second End Date,Second Contract';
		echo $line . "\r\n";
		foreach($rows AS $row)
		{
			$line = '';
			$line .= $this->csvSafe($row['l03']) .',';
			$line .= $this->csvSafe($row['surname']) .',';
			$line .= $this->csvSafe($row['firstnames']) .',';
			$line .= $this->csvSafe($row['uln']) .',';
			$line .= $this->csvSafe($row['first_contract']) .',';
			$line .= $this->csvSafe($row['first_age_at_start']) .',';
			$line .= $this->csvSafe($row['first_framework']) .',';
			$line .= Date::toShort($row['first_start_date']) .',';
			$line .= Date::toShort($row['first_end_date']) .',';
			$line .= $this->csvSafe($row['second_age_at_start']) .',';
			$line .= $this->csvSafe($row['second_framework']) .',';
			$line .= Date::toShort($row['second_start_date']) .',';
			$line .= Date::toShort($row['second_end_date']) .',';
			$line .= $this->csvSafe($row['second_contract']) .',';
			echo $line . "\r\n";
			unset($p);
		}
		exit;
	}

	private function csvSafe($value)
	{
		$value = str_replace(',', ';', $value);
		$value = str_replace(array("\n", "\r"), '', $value);
		$value = str_replace("\t", '', $value);
		$value = '"' . str_replace('"', '""', $value) . '"';
		return $value;
	}
}
?>