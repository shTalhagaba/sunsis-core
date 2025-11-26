<?php
function OperationsLARReport(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
SELECT DISTINCT
  tr_operations.tr_id AS SystemID,
  CASE extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/RAG')
  	WHEN '1' THEN 'LAR - Terminate'
  	WHEN '2' THEN 'LAR - Tolerate'
  	WHEN '3' THEN 'LAR - Treat'
  	WHEN '4' THEN 'BIL LAR - Terminate'
  	WHEN '5' THEN 'BIL LAR - Tolerate'
  	WHEN '6' THEN 'BIL LAR - Treat'
	WHEN '7' THEN 'High Risk LAR - Terminate'
	WHEN '8' THEN 'High Risk LAR - Tolerate'
	WHEN '9' THEN 'High Risk LAR - Treat'
	WHEN '10' THEN 'High Risk BIL LAR - Terminate'
	WHEN '11' THEN 'High Risk BIL LAR - Tolerate'
	WHEN '12' THEN 'High Risk BIL LAR - Treat'
	WHEN 'R' THEN 'Red'
	WHEN 'A' THEN 'Amber'
	WHEN 'G' THEN 'Green'
  END AS LARStatus,
  tr.`firstnames` as Firstnames,
  tr.`surname` as Surname,
  organisations.legal_name AS Employer,
  op_trackers.title AS Programme,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS StartDate,
  DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS PlannedEndDate,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[1]/Date') AS added_to_lar_date,
  '' AS AddedToLARDate,
  tr_operations.lar_details,
  extractvalue(lar_details, '/Notes/Note[last()]/LastActionDate') AS DateOfLastAction,
  extractvalue(lar_details, '/Notes/Note[last()]/NextActionDate') AS DateOfNextAction,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Reason') AS LARReason,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Retention') AS RetentionCategory,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Owner') AS LAROwner,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS Coordinator,
  CASE TRUE
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) > 24 THEN '24+'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS AgeBand,
  (SELECT DISTINCT induction.`brm` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS BDM,
  (SELECT DISTINCT induction.`resourcer` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS Recruiter,
  (SELECT DISTINCT induction.`lead_gen` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS LeadGenerator,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS Assessor,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/RetentionOther') AS RetentionCategoryOther,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/SecondReason') AS SecondaryReason,
  IF(tr_operations.arm_involved = 'Y', 'Yes', IF(tr_operations.arm_involved = 'N', 'No', '') ) AS ArmInvolved,
  tr_operations.arm_revisit AS ArmRevisit,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Destination') AS LarDestination,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/OpenDate') AS LarOpenDate,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ClosedDate') AS LarClosedDate,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Summary') AS LarSummary,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/NextActionHistory') AS LarNextActionHistory
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
  LEFT JOIN courses_tr ON tr.id = courses_tr.`tr_id`
WHERE
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') = "O"
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "");
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $csv_fields = array();
    $index = -1;
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows AS $row)
    {
        $index++;

        $the_date = '';
        if($row['lar_details'] != '' && !is_null($row['lar_details']))
        {
            $lar_details = XML::loadSimpleXML($row['lar_details']);
            if(count($lar_details->Note) > 0 && $lar_details->Note[count($lar_details->Note)-1]->Type->__toString() != 'N')
            {
                $is_Note_n_present = false;
                foreach($lar_details->Note AS $note)
                {
                    if($note->Type->__toString() == 'N')
                    {
                        $is_Note_n_present = true;
                        break;
                    }
                }
                if(!$is_Note_n_present)
                    $the_date = $lar_details->Note[0]->Date->__toString();
                else
                {
                    for($i = count($lar_details->Note) - 1; $i >= 0; $i--)
                    {
                        if($lar_details->Note[$i]->Type->__toString() == 'N')
                        {
                            $the_date = $lar_details->Note[$i+1]->Date->__toString();
                            break;
                        }
                    }
                }
            }
        }

        $lar_reason_list = InductionHelper::getListLARReason();
        $LARReason = isset($lar_reason_list[$row['LARReason']]) ? $lar_reason_list[$row['LARReason']] : "";
        $retention_category_list = InductionHelper::getListRetentionCategories();
        $RetentionCategory = isset($retention_category_list[$row['RetentionCategory']]) ? $retention_category_list[$row['RetentionCategory']] : "";
        $LAROwner = DAO::getSingleValue($source_link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['LAROwner']}'");

        $csv_fields[$index]['SystemID'] = $row['SystemID'];
        $csv_fields[$index]['LARStatus'] = $row['LARStatus'];
        $csv_fields[$index]['Firstnames'] = $row['Firstnames'];
        $csv_fields[$index]['Surname'] = $row['Surname'];
        $csv_fields[$index]['Employer'] = $row['Employer'];
        $csv_fields[$index]['Programme'] = $row['Programme'];
        $csv_fields[$index]['StartDate'] = $row['StartDate'];
        $csv_fields[$index]['PlannedEndDate'] = $row['PlannedEndDate'];
        $csv_fields[$index]['AddedToLARDate'] = $the_date;
        $csv_fields[$index]['DateOfLastAction'] = $row['DateOfLastAction'];
        $csv_fields[$index]['DateOfNextAction'] = $row['DateOfNextAction'];
        $primary_reasons_description = [];
        if($row['LARReason'] != '')
        {
            $primary_reasons = explode(",", $row['LARReason']);
            foreach($primary_reasons AS $p_r)
            {
                if(isset($lar_reason_list[$p_r]))
                {
                    $primary_reasons_description[] = $lar_reason_list[$p_r];
                }
            }
        }
        $csv_fields[$index]['LARReason'] = count($primary_reasons_description) > 0 ? implode("; ", $primary_reasons_description) : '';
        $csv_fields[$index]['RetentionCategory'] = $RetentionCategory;
        $csv_fields[$index]['LAROwner'] = $LAROwner;
        $csv_fields[$index]['Coordinator'] = $row['Coordinator'];
        $csv_fields[$index]['AgeBand'] = $row['AgeBand'];
        $csv_fields[$index]['BDM'] = $row['BDM'];
        $csv_fields[$index]['Recruiter'] = $row['Recruiter'];
        $csv_fields[$index]['LeadGenerator'] = $row['LeadGenerator'];
        $csv_fields[$index]['Assessor'] = $row['Assessor'];
        $csv_fields[$index]['RetentionCategoryOther'] = substr($row['RetentionCategoryOther'], 0, 199);
	$secondary_reasons_description = [];
        if($row['SecondaryReason'] != '')
        {
            $second_reasons = explode(",", $row['SecondaryReason']);
            foreach($second_reasons AS $s_r)
            {
                if(isset($lar_reason_list[$s_r]))
                {
                    $secondary_reasons_description[] = $lar_reason_list[$s_r];
                }
            }
        }
        $csv_fields[$index]['SecondaryReason'] = count($secondary_reasons_description) > 0 ? implode("; ", $secondary_reasons_description) : '';
        $csv_fields[$index]['ArmInvolved'] = $row['ArmInvolved'];
        $csv_fields[$index]['ArmRevisit'] = $row['ArmRevisit'];
        $csv_fields[$index]['LarDestination'] = $row['LarDestination'];
        $csv_fields[$index]['LarOpenDate'] = $row['LarOpenDate'];
        $csv_fields[$index]['LarClosedDate'] = $row['LarClosedDate'];
        $csv_fields[$index]['LarSummary'] = $row['LarSummary'];
        $csv_fields[$index]['LarNextActionHistory'] = $row['LarNextActionHistory'];
    }

    DAO::execute($target_link, "truncate OperationsLARReport");
    DAO::multipleRowInsert($target_link, "OperationsLARReport", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsLARReport populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}