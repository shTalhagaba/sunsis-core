<?php
function OperationsEPA(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
SELECT
id AS OperationsEPAID
,op_epa.tr_id AS TrainingRecordID
,task AS Task
,task_status AS TaskStatus
,task_date AS TaskDate
,task_applicable AS TaskApplicable
,task_actual_date AS TaskActualDate
,case when task_type = 1 then "On Programme" when task_type = 2 then "Re-Sit" END AS TaskType
,potential_achievement_month AS PotentialAchievementMonth
,task_epa_risk AS TaskEPARisk
,task_peed_forecast_date AS PeedForecastDate
,CASE op_epa.task_epao
    WHEN 'EPA' THEN '1st for EPA'
    ELSE op_epa.task_epao
END as TaskEpao
,CASE op_epa.task_lsl
    WHEN 'Y' THEN 'Yes'
    WHEN 'N' THEN 'No'
    ELSE ''
END as LslInvolvement
,op_epa.task_peed_cause AS PeedCause
,op_epa.task_comments AS Comments
,op_epa.task_assessment_method1 AS AsmtMethod1
,op_epa.task_assessment_method2 AS AsmtMethod2
,op_epa.task_end_date AS TaskEndDate
,op_epa.task_end_time AS TaskEndTime
,tr_operations.`pdp_month9_date`
,tr_operations.`pdp_month9_completed`
,tr_operations.`pdp_month12_date`
,tr_operations.`pdp_month12_completed`
,(SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = tr_operations.`pdp_coach_sign`) AS pdp_coach_signature
,tr_operations.`mock_interview_planned_date`
,tr_operations.`mock_interview_actual_date`
,tr_operations.`mock_interview_completed`
,STR_TO_DATE(EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/DateWeek1'), '%d/%m/%Y') AS CheckInDateWeek1
,STR_TO_DATE(EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/DateWeek2'), '%d/%m/%Y') AS CheckInDateWeek2
,STR_TO_DATE(EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/DateWeek3'), '%d/%m/%Y') AS CheckInDateWeek3
,STR_TO_DATE(EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/DateWeek4'), '%d/%m/%Y') AS CheckInDateWeek4
,EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/CheckInDoneWeek1') AS CheckInDoneWeek1
,EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/CheckInDoneWeek2') AS CheckInDoneWeek2
,EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/CheckInDoneWeek3') AS CheckInDoneWeek3
,EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/CheckInDoneWeek4') AS CheckInDoneWeek4
,EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/Comments') AS CheckInComments
,tr_operations.`project_plan` AS ProjectPlan
,tr_operations.`epa_mock_interview`
,tr_operations.`project_prep_session`
,EXTRACTVALUE(epa_mock_interview, 'Mock/Set[Iteration="1"]/ActualDate') AS MockInterviewDate1
,EXTRACTVALUE(epa_mock_interview, 'Mock/Set[Iteration="2"]/ActualDate') AS MockInterviewDate2 
,EXTRACTVALUE(epa_mock_interview, 'Mock/Set[Iteration="3"]/ActualDate') AS MockInterviewDate3
FROM op_epa 
INNER JOIN tr_operations ON op_epa.`tr_id` = tr_operations.`tr_id`
WHERE op_epa.tr_id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $csv_fields = array();
    $Task = InductionHelper::getListOpTask();
    $TaskStatus = InductionHelper::getListOpTaskStatus();
    $EPARisk = InductionHelper::getListYesNo();
    $TaskType = InductionHelper::getListOpTaskType();
    $index = -1;
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows AS $row)
    {
        $index++;
        $csv_fields[$index]['Task'] = isset($Task[$row['Task']])?$Task[$row['Task']]:$row['Task'];
        $csv_fields[$index]['TaskStatus'] = isset($TaskStatus[$row['TaskStatus']])?$TaskStatus[$row['TaskStatus']]:$row['TaskStatus'];
        $csv_fields[$index]['TaskType'] = isset($TaskType[$row['TaskType']])?$TaskType[$row['TaskType']]:$row['TaskType'];
        $csv_fields[$index]['TaskEPARisk'] = isset($EPARisk[$row['TaskEPARisk']])?$EPARisk[$row['TaskEPARisk']]:$row['TaskEPARisk'];
        $csv_fields[$index]['Timestamp'] = date('Y-m-d H:i:s');
        $csv_fields[$index]['PeedCause'] = substr($row['PeedCause'], 0, 249);
        $csv_fields[$index]['Comments'] = substr($row['Comments'], 0, 2499);

	if($row['epa_mock_interview'] != '')
        {
            $epa_mock_interview = new SimpleXMLElement($row['epa_mock_interview']);
            foreach($epa_mock_interview->Set AS $Set)
            {
                if($Set->Iteration->__toString() == '1')
                {
                    $csv_fields[$index]['mock_interview_planned_date'] = Date::toMySQL( $Set->PlannedDate->__toString() );
                    $csv_fields[$index]['mock_interview_actual_date'] = Date::toMySQL( $Set->ActualDate->__toString() );
                    $csv_fields[$index]['mock_interview_completed'] = $Set->Completed->__toString();
                }
                elseif($Set->Iteration->__toString() == '2')
                {
                    $csv_fields[$index]['mock_interview_planned_date2'] = Date::toMySQL( $Set->PlannedDate->__toString() );
                    $csv_fields[$index]['mock_interview_actual_date2'] = Date::toMySQL( $Set->ActualDate->__toString() );
                    $csv_fields[$index]['mock_interview_completed2'] = $Set->Completed->__toString();
                }
            }
        }
	if($row['project_prep_session'] != '')
        {
            $project_prep_session = new SimpleXMLElement($row['project_prep_session']);
            foreach($project_prep_session->Set AS $Set)
            {
                if($Set->Iteration->__toString() == '1')
                {
                    $csv_fields[$index]['project_prep_session_planned_date_1'] = Date::toMySQL( $Set->PlannedDate->__toString() );
                    $csv_fields[$index]['project_prep_session_interview_actual_date_1'] = Date::toMySQL( $Set->ActualDate->__toString() );
                    $csv_fields[$index]['project_prep_session_completed_1'] = $Set->Completed->__toString();
                }
                elseif($Set->Iteration->__toString() == '2')
                {
                    $csv_fields[$index]['project_prep_session_planned_date_2'] = Date::toMySQL( $Set->PlannedDate->__toString() );
                    $csv_fields[$index]['project_prep_session_interview_actual_date_2'] = Date::toMySQL( $Set->ActualDate->__toString() );
                    $csv_fields[$index]['project_prep_session_completed_2'] = $Set->Completed->__toString();
                }
            }
        }

        foreach([
            'OperationsEPAID',
            'TrainingRecordID',
            'TaskDate',
            'TaskApplicable',
            'TaskActualDate',
            'PotentialAchievementMonth',
            'PeedForecastDate',
            'TaskEpao',
            'LslInvolvement',
            'AsmtMethod1',
            'AsmtMethod2',
            'TaskEndDate',
            'TaskEndTime',
            'pdp_month9_date',
            'pdp_month9_completed',
            'pdp_month12_date',
            'pdp_month12_completed',
            'pdp_coach_signature',
            //'mock_interview_planned_date',
            //'mock_interview_actual_date',
            //'mock_interview_completed',
            'CheckInDateWeek1',
            'CheckInDateWeek2',
            'CheckInDateWeek3',
            'CheckInDateWeek4',
            'CheckInDoneWeek1',
            'CheckInDoneWeek2',
            'CheckInDoneWeek3',
            'CheckInDoneWeek4',
            'CheckInComments',
            'ProjectPlan',
            'MockInterviewDate1',
            'MockInterviewDate2',
            'MockInterviewDate3',
        ] AS $column_name)
        {
		if(in_array($column_name, ["MockInterviewDate1", "MockInterviewDate2", "MockInterviewDate3"]))
            {
                if($row[$column_name] != '')
                {
                    $row[$column_name] = Date::toMySQL($row[$column_name]);
                }
            }

            $csv_fields[$index][$column_name] = $row[$column_name];
        }
    }

    DAO::execute($target_link, "truncate OperationsEPA");
    DAO::multipleRowInsert($target_link, "OperationsEPA", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nOperationsEPA populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}