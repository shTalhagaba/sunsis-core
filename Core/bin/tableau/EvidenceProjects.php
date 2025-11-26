<?php
function EvidenceProjects(PDO $source_link, PDO $target_link)
{
    $start = microtime(true);
    $sql = <<<HEREDOC
SELECT
    tr_projects.id AS EvidenceProjectID
    #,tr.l03 AS learner_reference
	#,CONCAT(tr.firstnames, ' ', tr.surname) AS learner_name
	#,courses.title AS course
	#,'' AS total_plan
	#,'' AS expected_progress
	#,(SELECT organisations.legal_name FROM organisations WHERE organisations.id = tr.employer_id) AS employer
    ,evidence_project.`project`
	#,'' AS `status`
    #,IF(assessorsng.firstnames IS NOT NULL, CONCAT(assessorsng.firstnames, ' ' ,assessorsng.surname),CONCAT(assessors.firstnames, ' ' ,assessors.surname)) AS assessor
    #,(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE sub.assessor = users.id) AS assessor_2
    #,'' AS projects_completed
    #,'' AS project_status
    #,'' AS project_progress
    #,'' AS weeks_on_project
    #,'' AS project_2
    ,DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date
    ,IF(courses.`title` LIKE "%L3%" OR courses.`title` LIKE "%Level 3%" , DATE_FORMAT(DATE_ADD(start_date, INTERVAL 10 MONTH), '%d/%m/%Y'), DATE_FORMAT(DATE_ADD(start_date, INTERVAL 15 MONTH), '%d/%m/%Y')) AS assessment_plan_due_date
    ,DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS planned_end_date
    #,(SELECT IF(due_date2 IS NOT NULL AND due_date2!='0000-00-00', DATE_FORMAT(due_date2, '%d/%m/%Y'), IF(due_date1 IS NOT NULL AND due_date1!='0000-00-00', DATE_FORMAT(due_date1, '%d/%m/%Y'),DATE_FORMAT(due_date, '%d/%m/%Y'))) FROM assessor_review WHERE tr_id = tr.id AND IF(due_date2 IS NOT NULL AND due_date2!='0000-00-00', due_date2, IF(due_date1 IS NOT NULL AND due_date1!='0000-00-00', due_date1, due_date)) > NOW() ORDER BY due_date DESC LIMIT 0,1) AS next_review_date
	#,(SELECT DATE_FORMAT(due_date,'%d/%m/%Y') FROM additional_support WHERE due_date>=CURDATE() AND tr_id = tr.id ORDER BY due_date LIMIT 0,1) AS next_additional_support
	,DATE_FORMAT(sub.due_date, '%d/%m/%Y') AS due_date
	,DATE_FORMAT(sub.submission_date, '%d/%m/%Y') AS submission_date
	,DATE_FORMAT(sub.marked_date, '%d/%m/%Y') AS marked_date_1
	,DATE_FORMAT(sub.completion_date, '%d/%m/%Y') AS completion_date
	,(SELECT COUNT(*) FROM project_submissions WHERE project_submissions.project_id = tr_projects.`id`) AS submission_number
	,tr.contract_id
	,tr_projects.tr_id
    ,sub.due_date < CURDATE() AS expired
    ,DATE_FORMAT(sub.sent_iqa_date, '%d/%m/%Y') AS sent_iqa_date
    ,DATE_FORMAT(sub.assessor_signed_off, '%d/%m/%Y') AS assessor_signed_off
    ,DATE_FORMAT(sub.set_date, '%d/%m/%Y') AS set_date
    ,DATE_FORMAT(sub.acc_rej_date, '%d/%m/%Y') AS acc_rej_date
    ,IF(sub.iqa_status=1,"Accepted",IF(sub.iqa_status=2,"Rejected","")) AS iq_status
    ,CONCAT(users.`firstnames`, ' ',users.`surname`) AS Assessor2
    ,sub.iqa_recheck_date
    #,sub.comments
	#,CASE extractvalue(tr_operations.lar_details, '/Notes/Note[last()]/Type')
	#  WHEN 'O' THEN 'Yes'
	#  WHEN 'N' THEN 'No'
	#  WHEN 'S' THEN 'Yes'
	# END AS LAR
	#,(IF(tr_operations.`on_furlough` = 'Y', 'Yes', 'No')) AS on_furlough
    #,(SELECT manager_comments.`comment` FROM manager_comments WHERE manager_comments.`tr_id` = tr.id AND manager_comments.`comment_type` = 'ER' ORDER BY manager_comments.`id` DESC LIMIT 1) AS employer_ref_comments
	#,(SELECT CASE manager_comments.rag WHEN 'R' THEN 'Red' WHEN 'A' THEN 'Amber' WHEN 'G' THEN 'Green' END FROM manager_comments WHERE manager_comments.`tr_id` = tr.id AND manager_comments.`comment_type` = 'ER' ORDER BY manager_comments.`id` DESC LIMIT 1) AS employer_ref_comments_rag
    #,(SELECT manager_comments.`comment` FROM manager_comments WHERE manager_comments.`tr_id` = tr.id AND manager_comments.`comment_type` = 'LP' ORDER BY manager_comments.`id` DESC LIMIT 1) AS learner_progress_comments
	#,(SELECT CASE manager_comments.rag WHEN 'R' THEN 'Red' WHEN 'A' THEN 'Amber' WHEN 'G' THEN 'Green' END FROM manager_comments WHERE manager_comments.`tr_id` = tr.id AND manager_comments.`comment_type` = 'LP' ORDER BY manager_comments.`id` DESC LIMIT 1) AS learner_progress_comments_rag
    #,CASE WHEN iqa_reason = 1 THEN "Lack of evidence" WHEN iqa_reason = 2 THEN "Wrong dates" WHEN iqa_reason = 3 THEN "Outcomes not met" WHEN iqa_reason = 4 THEN "Error with context/layout/Functional Skills" ELSE "" END AS iqa_reason
    #,CASE WHEN assessor_reason = 1 THEN "1st rework" WHEN assessor_reason = 2 THEN "Outcomes not met" WHEN assessor_reason = 3 THEN "Push back for higher grade" WHEN assessor_reason = 4 THEN "Lack of evidence" WHEN assessor_reason = 5 THEN "Error with context/layout/Functional Skills" ELSE "" END AS assessor_reason
    #,CASE WHEN sub.system = 1 THEN "Skilsure" WHEN sub.system = 2 THEN "Smart Assessor"  ELSE "" END AS system
FROM
	tr_projects
	LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
		sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
	LEFT JOIN users ON users.id = sub.assessor
	LEFT JOIN tr ON tr.id = tr_projects.tr_id
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN courses_tr ON tr.id = courses_tr.tr_id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
    LEFT JOIN evidence_project ON evidence_project.`id` = tr_projects.`project` AND courses.id = evidence_project.`course_id`
	LEFT JOIN group_members ON group_members.tr_id = tr_projects.tr_id
	LEFT JOIN groups ON groups.id = group_members.groups_id
	LEFT JOIN users AS assessors ON assessors.id = groups.assessor
    LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
    LEFT JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.id = tr_projects.project AND student_frameworks.id = lookup_assessment_plan_log_mode.framework_id
	LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id
    WHERE tr_projects.tr_id IN (SELECT id FROM tr WHERE start_date > DATE_ADD(CURDATE(), INTERVAL -5 YEAR));
HEREDOC;


    $st = $source_link->query($sql);
    if(!$st)
    {
        throw new DatabaseException($source_link, $sql);
    }

    $csv_fields = array();
    $index = -1;
    while($row = $st->fetch())
    {

        if($row['completion_date']!='')
            $status = "Complete";
        elseif($row['iq_status']=='Rejected')
            $status = "IQA Rejected";
        elseif($row['iqa_recheck_date']!='')
            $status = "IQA Recheck";
        elseif($row['sent_iqa_date']!='' and ($row['iq_status']!='Rejected' or $row['iq_status']!='3'))
            $status = "IQA";
        elseif($row['submission_date']!='')
            $status = "Awaiting marking";
        elseif($row['expired']=='1' and $row['submission_date']=='')
            $status = "Overdue";
        elseif($row['set_date']!='' and $row['expired']=='0' and $row['submission_number']=='1')
            $status = "In progress";
        else
            $status = "Rework Required";



        $index++;
        $csv_fields[$index]['EvidenceProjectID'] = $row['EvidenceProjectID'];
        $csv_fields[$index]['TrainingRecordID'] = $row['tr_id'];
        $csv_fields[$index]['ProjectStatus'] = $status;
        $csv_fields[$index]['SubmissionDate'] = $row['submission_date'];
        $csv_fields[$index]['MarkedDate'] = $row['marked_date_1'];
        $csv_fields[$index]['CompletionDate'] = $row['completion_date'];
        $csv_fields[$index]['SentToIQADate'] = $row['sent_iqa_date'];
        $csv_fields[$index]['ProjectTitle'] = $row['project'];
        $csv_fields[$index]['SubmissionCount'] = $row['submission_number'];
        $csv_fields[$index]['Assessor2'] = $row['Assessor2'];
        $csv_fields[$index]['DueDate'] = $row['due_date'];
    }

    DAO::execute($target_link, "truncate EvidenceProjects");
    DAO::multipleRowInsert($target_link, "EvidenceProjects", $csv_fields);

    $time_elapsed_secs = microtime(true) - $start;

    echo "\nEvidence Projects populated in {$time_elapsed_secs} seconds\n";
    unset($csv_fields);
}