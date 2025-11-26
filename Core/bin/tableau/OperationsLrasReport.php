<?php
function OperationsLrasReport(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $lras_reasons = Safeguarding::getListTriggers($source_link);	
    $lras_categories = Safeguarding::getListCategories($source_link);
    $lras_sps = Safeguarding::getListSupportProvider();


    $sql = <<<HEREDOC
SELECT
 DISTINCT
  tr.id AS TrainingId,
  tr.`firstnames` AS Firstnames,
  tr.`surname` AS Surname,
  tr.`target_date` AS PlannedEndDate,
  organisations.legal_name AS Employer,
  student_frameworks.title AS Programme,
  CASE EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/Status')
  	WHEN "Y" THEN "Yes"
  	WHEN "N" THEN "No"
  	ELSE ""
  END AS `Status`,
  EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/Summary') AS Summary,
  EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/Reason') AS Reason,
  EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/Category') AS Category,
  EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/LrasDate') AS `Date`,
  EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/RecommendedEndDate') AS RecommendedEndDate,
  EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/ProReact') AS ProactiveReactive,
  EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/SupportProvider') AS SupportProvider,
  EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/ActionPlanAgreed') AS ActionPlanAgreed,
  EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/ResourcesProvided') AS ResourcesProvided,
  EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/CreatedBy') AS CreatedBy,
  EXTRACTVALUE(tr_operations.`lras_details`, '/Notes/Note[last()]/DateTime') AS CreatedAt,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.assessor) AS Assessor
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
WHERE
  tr_operations.`lras_details` IS NOT NULL

;
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }


    $csv_fields = array();
    $index = -1;
    $rows = $st->fetchAll(DAO::FETCH_ASSOC);
    foreach($rows AS $row)
    {
        $index++;
        $csv_fields[$index]['TrainingId'] = $row['TrainingId'];
        $csv_fields[$index]['Firstnames'] = $row['Firstnames'];
        $csv_fields[$index]['Surname'] = $row['Surname'];
        $csv_fields[$index]['PlannedEndDate'] = $row['PlannedEndDate'];
        $csv_fields[$index]['Employer'] = $row['Employer'];
        $csv_fields[$index]['Programme'] = $row['Programme'];
        $csv_fields[$index]['CreatedAt'] = $row['CreatedAt'];
        $csv_fields[$index]['Status'] = $row['Status'];
        $csv_fields[$index]['ProactiveReactive'] = $row['ProactiveReactive'];
        $csv_fields[$index]['Summary'] = substr($row['Summary'], 0, 799);
        $csv_fields[$index]['ActionPlanAgreed'] = substr($row['ActionPlanAgreed'], 0, 799);
        $csv_fields[$index]['ResourcesProvided'] = substr($row['ResourcesProvided'], 0, 799);
        $csv_fields[$index]['CreatedBy'] = DAO::getSingleValue($source_link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['CreatedBy']}'");
        $_reasons = '';
        foreach( explode(',', $row['Reason']) AS $lras_reason )
        {
            $_reasons .= isset($lras_reasons[$lras_reason]) ? $lras_reasons[$lras_reason] . ' | ' : '';
        }
        $csv_fields[$index]['Reason'] = $_reasons;
        $_categories = '';
        foreach( explode(',', $row['Category']) AS $lras_category )
        {
            $_categories .= isset($lras_categories[$lras_category]) ? $lras_categories[$lras_category] . ' | ' : '';
        }
        $csv_fields[$index]['Category'] = $_categories;
        $_sps = '';
        foreach( explode(',', $row['SupportProvider']) AS $lras_sp )
        {
            $_sps .= isset($lras_sps[$lras_sp]) ? $lras_sps[$lras_sp] . ' | ' : '';
        }
        $csv_fields[$index]['SupportProvider'] = $_sps;
        $csv_fields[$index]['RecommendedEndDate'] = Date::toMySQL($row['RecommendedEndDate']);
        $csv_fields[$index]['Assessor'] = $row['Assessor'];
                            

    }

    DAO::execute($target_link, "truncate OperationsLrasReport");
    DAO::multipleRowInsert($target_link, "OperationsLrasReport", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsLrasReport populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}