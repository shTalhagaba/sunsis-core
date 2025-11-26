<?php
function PreviousOnLarReport(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);

    $sql = <<<HEREDOC
    SELECT DISTINCT
    tr.firstnames,
      tr.surname,
      organisations.legal_name AS employer,
      op_trackers.title AS programme,
      induction_fields.induction_date,
      #(SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = induction_fields.assigned_coord) AS coordinator,
      (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
      (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = induction_fields.assigned_assessor) AS learning_mentor,
      EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/Type') AS lar_type,
      EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/Date') AS lar_date,
      CASE EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[last()-1]/RAG')
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
      END AS lar_rag,
      EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[last()]/ClosedDate') AS lar_closed_date,
      EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/Reason') AS lar_primary_reason,
      EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/SecondReason') AS lar_secondary_reason,
      EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/Retention') AS retention_category,
      EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/ActivelyInvolved') AS actively_involved,
      CASE tr.status_code
        WHEN '1' THEN 'Continuing'
        WHEN '2' THEN 'Completed'
        WHEN '3' THEN 'Withdrawn'
        WHEN '4' THEN 'Transferred'
        WHEN '5' THEN 'Changes in Learning'
        WHEN '6' THEN 'Temp. Withdrawn'
        ELSE ''
      END AS training_record_status,
      tr_operations.lar_details,
      tr.id AS TrainingId
    FROM
      tr_operations INNER JOIN tr ON tr_operations.tr_id = tr.id
      LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.`id`
      LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
      LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
      LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
      LEFT JOIN organisations ON tr.employer_id = organisations.id
      LEFT JOIN (
      SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, induction.assigned_coord, induction.assigned_assessor,
      induction.`induction_date`
      FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
      ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
    WHERE
      tr_operations.lar_details IS NOT NULL
      AND extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') = "N"
      #AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
HEREDOC;
    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $types = InductionHelper::getListLAR();
    $reasonDDL = InductionHelper::getListLARReason();
    $retnetions = InductionHelper::getListRetentionCategories();

    $csv_fields = array();
    $index = -1;
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows AS $row)
    {
        $index++;
        $csv_fields[$index]['TrainingId'] = $row['TrainingId'];
        $csv_fields[$index]['Firstnames'] = $row['firstnames'];
        $csv_fields[$index]['Surname'] = $row['surname'];
        $csv_fields[$index]['Employer'] = $row['employer'];
        $csv_fields[$index]['Programme'] = $row['programme'];
        $csv_fields[$index]['InductionDate'] = $row['induction_date'];
        $csv_fields[$index]['Coordinator'] = $row['coordinator'];
        $csv_fields[$index]['LearningMentor'] = $row['learning_mentor'];
        $csv_fields[$index]['LarType'] = isset($types[$row['lar_type']]) ? $types[$row['lar_type']] : '';
        $csv_fields[$index]['LarDate'] = Date::toMySQL($row['lar_date']);
        $csv_fields[$index]['LarRag'] = $row['lar_rag'];
        $csv_fields[$index]['LarClosedDate'] = Date::toMySQL($row['lar_closed_date']);
        
        $primary_reasons_description = [];
        if($row['lar_primary_reason'] != '')
        {
            $primary_reasons = explode(",", $row['lar_primary_reason']);
            foreach($primary_reasons AS $p_r)
            {
                if(isset($reasonDDL[$p_r]))
                {
                    $primary_reasons_description[] = $reasonDDL[$p_r];
                }
            }
        }
        $csv_fields[$index]['PrimaryReason'] = count($primary_reasons_description) > 0 ? implode("; ", $primary_reasons_description) : '';

        $secondary_reasons_description = [];
        if($row['lar_secondary_reason'] != '')
        {
            $secondary_reasons = explode(",", $row['lar_secondary_reason']);
            foreach($secondary_reasons AS $s_r)
            {
                if(isset($reasonDDL[$s_r]))
                {
                    $secondary_reasons_description[] = $reasonDDL[$s_r];
                }
            }
        }
        $csv_fields[$index]['SecondaryReason'] = count($secondary_reasons_description) > 0 ? implode("; ", $secondary_reasons_description) : '';

        $users_names = [];
        if($row['actively_involved'] != '')
        {
            $user_ids = explode(",", $row['actively_involved']);
            foreach($user_ids AS $user_id)
            {
                $name_of_user = DAO::getSingleValue($source_link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$user_id}'");
                $users_names[] = $name_of_user != '' ? $name_of_user : $user_id;
            }
        }
        $csv_fields[$index]['ActivelyInvolved'] = count($users_names) > 0 ? implode("; ", $users_names) : '';
        $csv_fields[$index]['RetentionCategory'] = isset($retnetions[$row['retention_category']]) ? $retnetions[$row['retention_category']] : '';
        $csv_fields[$index]['TrainingStatus'] = $row['training_record_status'];

    }

    DAO::execute($target_link, "truncate PreviousOnLarReport");
    DAO::multipleRowInsert($target_link, "PreviousOnLarReport", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nPreviousOnLarReport populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}