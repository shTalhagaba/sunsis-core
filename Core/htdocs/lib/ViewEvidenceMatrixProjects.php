<?php
class ViewEvidenceMatrixProjects extends View
{
    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__.'12';

        if(!isset($_SESSION[$key]))
        {

            /*DAO::execute($link, "INSERT INTO assessment_plan_log SELECT NULL, NULL, NULL,NULL, NULL,NULL, tr.id, NULL, NULL, NULL, NULL, NULL
FROM tr
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
LEFT JOIN courses ON courses.id = courses_tr.`course_id`
WHERE status_code = 1
AND tr.id NOT IN (SELECT tr_id FROM assessment_plan_log)
AND courses.programme_type=2;");*/


            $sql = <<<HEREDOC
SELECT
     tr.l03 AS learner_reference
	,CONCAT(tr.firstnames, ' ', tr.surname) AS learner_name
	,courses.title AS course
	,'' AS total_plan
	,'' AS expected_progress
	,(SELECT organisations.legal_name FROM organisations WHERE organisations.id = tr.employer_id) AS employer
    ,evidence_project.`project`
	,'' AS `status`
    ,IF(assessorsng.firstnames IS NOT NULL, CONCAT(assessorsng.firstnames, ' ' ,assessorsng.surname),CONCAT(assessors.firstnames, ' ' ,assessors.surname)) AS assessor
    ,(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE sub.assessor = users.id) AS assessor_2
    ,'' AS projects_completed
    ,'' AS project_status
    ,'' AS project_progress
    ,CONCAT(IF(sub.completion_date IS NULL OR matrix="", 0, (1 + LENGTH(matrix) - LENGTH(REPLACE(matrix,",","")))),"(",(SELECT COUNT(*) FROM evidence_criteria WHERE course_id = courses.id),")") AS evidence_progress
    ,IF(sub.completion_date IS NULL OR matrix="", 0, (1 + LENGTH(matrix) - LENGTH(REPLACE(matrix,",","")))) AS evidence_progress2
    ,'' AS weeks_on_project
    ,'' AS project_2
    ,DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date
    ,IF(courses.`title` LIKE "%L3%" OR courses.`title` LIKE "%Level 3%" , DATE_FORMAT(DATE_ADD(start_date, INTERVAL 10 MONTH), '%d/%m/%Y'), DATE_FORMAT(DATE_ADD(start_date, INTERVAL 15 MONTH), '%d/%m/%Y')) AS assessment_plan_due_date
    ,DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS planned_end_date
    ,(SELECT IF(due_date2 IS NOT NULL AND due_date2!='0000-00-00', DATE_FORMAT(due_date2, '%d/%m/%Y'), IF(due_date1 IS NOT NULL AND due_date1!='0000-00-00', DATE_FORMAT(due_date1, '%d/%m/%Y'),DATE_FORMAT(due_date, '%d/%m/%Y'))) FROM assessor_review WHERE tr_id = tr.id AND IF(due_date2 IS NOT NULL AND due_date2!='0000-00-00', due_date2, IF(due_date1 IS NOT NULL AND due_date1!='0000-00-00', due_date1, due_date)) > NOW() ORDER BY due_date DESC LIMIT 0,1) AS next_review_date
	,(SELECT DATE_FORMAT(due_date,'%d/%m/%Y') FROM additional_support WHERE due_date>=CURDATE() AND tr_id = tr.id ORDER BY due_date LIMIT 0,1) AS next_additional_support
	,DATE_FORMAT(GREATEST(sub.due_date,COALESCE(sub.extension_date,'1900-01-01')), '%d/%m/%Y') AS due_date
	,DATE_FORMAT(sub.submission_date, '%d/%m/%Y') AS submission_date
	,DATE_FORMAT(sub.marked_date, '%d/%m/%Y') AS marked_date_1
	,DATE_FORMAT(sub.completion_date, '%d/%m/%Y') AS completion_date
	,(SELECT COUNT(*) FROM project_submissions WHERE project_submissions.project_id = tr_projects.`id`) AS submission_number
	,tr.contract_id
	,tr_projects.tr_id
    ,GREATEST(sub.due_date,COALESCE(sub.extension_date,'1900-01-01')) < CURDATE() AS expired
    ,DATE_FORMAT(sub.sent_iqa_date, '%d/%m/%Y') AS sent_iqa_date
    ,DATE_FORMAT(sub.assessor_signed_off, '%d/%m/%Y') AS assessor_signed_off
    ,DATE_FORMAT(sub.set_date, '%d/%m/%Y') AS set_date
    ,DATE_FORMAT(sub.acc_rej_date, '%d/%m/%Y') AS acc_rej_date
    ,sub.iqa_status
    ,sub.iqa_recheck_date
    ,IF(sub.iqa_status=1,"Accepted",IF(sub.iqa_status=2,"Rejected","")) AS iq_status
    ,sub.comments
	,CASE extractvalue(tr_operations.lar_details, '/Notes/Note[last()]/Type')
	  WHEN 'O' THEN 'Yes'
	  WHEN 'N' THEN 'No'
	  WHEN 'S' THEN 'Yes'
	 END AS LAR
	,(IF(tr_operations.`on_furlough` = 'Y', 'Yes', 'No')) AS on_furlough
    ,(SELECT manager_comments.`comment` FROM manager_comments WHERE manager_comments.`tr_id` = tr.id AND manager_comments.`comment_type` = 'ER' ORDER BY manager_comments.`id` DESC LIMIT 1) AS employer_ref_comments
	,(SELECT CASE manager_comments.rag WHEN 'R' THEN 'Red' WHEN 'A' THEN 'Amber' WHEN 'G' THEN 'Green' END FROM manager_comments WHERE manager_comments.`tr_id` = tr.id AND manager_comments.`comment_type` = 'ER' ORDER BY manager_comments.`id` DESC LIMIT 1) AS employer_ref_comments_rag
    ,(SELECT manager_comments.`comment` FROM manager_comments WHERE manager_comments.`tr_id` = tr.id AND manager_comments.`comment_type` = 'LP' ORDER BY manager_comments.`id` DESC LIMIT 1) AS learner_progress_comments
	,(SELECT CASE manager_comments.rag WHEN 'R' THEN 'Red' WHEN 'A' THEN 'Amber' WHEN 'G' THEN 'Green' END FROM manager_comments WHERE manager_comments.`tr_id` = tr.id AND manager_comments.`comment_type` = 'LP' ORDER BY manager_comments.`id` DESC LIMIT 1) AS learner_progress_comments_rag
    ,CASE WHEN iqa_reason = 1 THEN "Lack of evidence" WHEN iqa_reason = 2 THEN "Wrong dates" WHEN iqa_reason = 3 THEN "Outcomes not met" WHEN iqa_reason = 4 THEN "Error with context/layout/Functional Skills" ELSE "" END AS iqa_reason
    ,CASE WHEN assessor_reason = 1 THEN "1st rework" WHEN assessor_reason = 2 THEN "Outcomes not met" WHEN assessor_reason = 3 THEN "Push back for higher grade" WHEN assessor_reason = 4 THEN "Lack of evidence" WHEN assessor_reason = 5 THEN "Error with context/layout/Functional Skills" ELSE "" END AS assessor_reason
    ,CASE WHEN sub.system = 1 THEN "Skilsure" WHEN sub.system = 2 THEN "Smart Assessor"  ELSE "" END AS system
FROM
	tr_projects
	LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
		sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
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
HEREDOC;
            $view = $_SESSION[$key] = new ViewEvidenceMatrixProjects();
            $view->setSQL($sql);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
                2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
                3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
                4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
                5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
                6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7=>array(7, '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
            $f = new DropDownViewFilter('filter_record_status', $options, 1, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. Green', null, 'WHERE traffic=1'),
                2=>array(2, '2. Yellow', null, 'WHERE traffic=2'),
                3=>array(3, '3. Red', null, 'WHERE traffic=3'));
            $f = new DropDownViewFilter('filter_review_status', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. In progress', null, 'WHERE (SELECT COUNT(*) FROM project_submissions WHERE project_id = tr_projects.id)=1 and sub.`completion_date` IS NULL AND sent_iqa_date IS NULL AND submission_date IS NULL AND set_date IS NOT NULL and COALESCE(iqa_status, 0)!=2 AND GREATEST(sub.due_date,COALESCE(sub.extension_date,"1900-01-01")) >= CURDATE()'),
                2=>array(2, '2. Awaiting marking', null, 'WHERE sub.`completion_date` IS NULL AND COALESCE(iqa_status, 0)!=2 AND sent_iqa_date IS NULL AND submission_date IS NOT NULL'),
                3=>array(3, '3. Complete', null, 'WHERE  sub.`completion_date` IS NOT NULL'),
                //4=>array(4, '4. Rework required', null, 'WHERE ((COALESCE(iqa_status, 0)=2 and completion_date is null) or ((SELECT COUNT(*) FROM project_submissions WHERE project_id = tr_projects.id)>1 AND sub.`completion_date` IS NULL AND iqa_status IS NULL AND sent_iqa_date IS NULL AND submission_date IS NULL AND set_date IS NOT NULL and sub.due_date >= CURDATE() ))'),
                4=>array(4, '4. Rework required', null, 'WHERE (((SELECT COUNT(*) FROM project_submissions WHERE project_id = tr_projects.id)>1 AND sub.`completion_date` IS NULL AND iqa_status IS NULL AND sent_iqa_date IS NULL AND submission_date IS NULL AND set_date IS NOT NULL and GREATEST(sub.due_date,COALESCE(sub.extension_date,"1900-01-01")) >= CURDATE() ))'),
                5=>array(5, '5. IQA', null, 'WHERE sub.`sent_iqa_date` IS not NULL AND COALESCE(iqa_status, 0)!=2 and sub.completion_date is null'),
                6=>array(6, '6. Overdue', null, 'WHERE sub.`completion_date` IS NULL AND sent_iqa_date IS NULL AND submission_date IS NULL AND set_date IS NOT NULL and COALESCE(iqa_status, 0)!=2 AND GREATEST(sub.due_date,COALESCE(sub.extension_date,"1900-01-01")) < CURDATE()'),
                7=>array(7, '5. Due', null, 'WHERE sub.`completion_date` IS NULL AND sent_iqa_date IS NULL AND submission_date IS NULL'),
                8=>array(8, '8. IQA Rejected', null, 'WHERE sub.iqa_status = 2 and completion_date is null'),
                9=>array(9, '9. IQA Recheck', null, 'WHERE iqa_recheck_date is not null and completion_date is null'),
                10=>array(10, '10. IQA Rework Awaiting Marking', null, 'WHERE iqa_rework_awaiting_marking is not null and completion_date is null'));
            $f = new DropDownViewFilter('filter_paperwork', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);



            $options = array(
                0=>array(1, 'Learner, Due Date ASC', null, 'ORDER BY learner_name, due_date ASC'),
                1=>array(2, 'L03', null, 'ORDER BY l03'),
                2=>array(3, 'Leaner', null, 'ORDER BY learner_name'),
                3=>array(4, 'Status, Due Date Desc, Actual End Date Desc', null, 'ORDER BY learner_name'));

            $f = new DropDownViewFilter('order_by', $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_trs', "WHERE tr.id in (%s)", null);
            $view->addFilter($f);

            $format = "WHERE project_submissions.actual_date >= '%s'";
            $f = new DateViewFilter('last_start_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE project_submissions.actual_date <= '%s'";
            $f = new DateViewFilter('last_end_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

            $format = "WHERE GREATEST(sub.due_date,COALESCE(sub.extension_date,'1900-01-01')) >= '%s'";
            $f = new DateViewFilter('due_start_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE GREATEST(sub.due_date,COALESCE(sub.extension_date,'1900-01-01')) <= '%s'";
            $f = new DateViewFilter('due_end_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

            $format = "WHERE sub.marked_date >= '%s'";
            $f = new DateViewFilter('filter_from_marked_date', $format, '');
            $f->setDescriptionFormat("From Marked Date: %s");
            $view->addFilter($f);

            $format = "WHERE sub.marked_date <= '%s'";
            $f = new DateViewFilter('filter_to_marked_date', $format, '');
            $f->setDescriptionFormat("To Marked Date: %s");
            $view->addFilter($f);

            $format = "WHERE sub.completion_date >= '%s'";
            $f = new DateViewFilter('filter_from_signed_off_date', $format, '');
            $f->setDescriptionFormat("From Signed off Date: %s");
            $view->addFilter($f);

            $format = "WHERE sub.completion_date <= '%s'";
            $f = new DateViewFilter('filter_to_signed_off_date', $format, '');
            $f->setDescriptionFormat("To Signed off Date: %s");
            $view->addFilter($f);

            $format = "WHERE sub.assessor_signed_off >= '%s'";
            $f = new DateViewFilter('filter_from_assessor_signed_off', $format, '');
            $f->setDescriptionFormat("From Assessor Signed off: %s");
            $view->addFilter($f);

            $format = "WHERE sub.assessor_signed_off <= '%s'";
            $f = new DateViewFilter('filter_to_assessor_signed_off', $format, '');
            $f->setDescriptionFormat("To Assessor Signed off Date: %s");
            $view->addFilter($f);


            $options = "SELECT DISTINCT users.id, CONCAT(firstnames,' ',surname, ' - ' , lookup_user_types.`description`), LEFT(firstnames, 1), CONCAT('WHERE (groups.assessor=',users.id,' and tr.assessor is null) OR tr.assessor=' , users.id)
FROM users
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
WHERE
users.id IN (SELECT assessor FROM tr WHERE status_code = 1 AND assessor IS NOT NULL)
OR
(users.id IN (SELECT assessor FROM groups WHERE assessor IN (SELECT assessor FROM groups WHERE id IN (SELECT groups_id FROM group_members WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))))
AND users.id NOT IN ((SELECT assessor FROM tr WHERE status_code = 1 AND assessor IS NOT NULL)))
ORDER BY firstnames, surname;";
            $f = new DropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            $options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE project_submissions.assessor=',id) FROM users WHERE type=3 ORDER BY firstnames";
            $f = new DropDownViewFilter('filter_person_reviewed', $options, null, true);
            $f->setDescriptionFormat("Person Reviewed: %s");
            $view->addFilter($f);

            $options = "SELECT username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE tr_projects.tr_id in (select id from tr where assessor in (select id from users where supervisor = \'',username, '\'))') FROM users where users.username IN (SELECT supervisor FROM users WHERE supervisor IS NOT NULL) ORDER BY firstnames";
            $f = new DropDownViewFilter('filter_manager', $options, null, true);
            $f->setDescriptionFormat("Manager: %s");
            $view->addFilter($f);


            $options = <<<SQL
SELECT DISTINCT
	organisations.id, organisations.legal_name, LEFT(organisations.legal_name, 1), CONCAT('WHERE tr.employer_id=', organisations.id)
FROM
	organisations
	INNER JOIN tr ON organisations.id = tr.`employer_id`
	INNER JOIN assessment_plan_log ON tr.id = assessment_plan_log.`tr_id`
WHERE
	organisation_type=2 ORDER BY legal_name
;
SQL;
            $f = new DropDownViewFilter('filter_employer', $options, null, true);
            $f->setDescriptionFormat("Person Reviewed: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
            $f->setDescriptionFormat("Learner ULN: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
            $f->setDescriptionFormat("LearnRefNumber: %s");
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
            echo '<div><table id="tblLogs" class="table table-bordered">';

            echo '<thead class="bg-gray"><tr><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
            foreach($columns as $column)
            {
                $style = '';
                if(in_array($column, ['employer_ref_comments', 'comments', 'learner_progress_comments']))
                {
                    $_nbsp = '&nbsp;';
                    for($i = 0; $i <= 20; $i++)
                        $_nbsp .= '&nbsp;';
                    echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . $_nbsp . '</th>';
                }
                else
                {
                    echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
                }
            }
            echo '</tr></thead><tbody>';
            while($row = $st->fetch())
            {
                $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                $total_months = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM ap_percentage WHERE course_id = '{$course_id}';");

                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                if($total_units>0)
                    $expected_progress = $current_training_month/$total_months*100;
                else
                    $expected_progress = 0;
                $expected_progress = ($expected_progress>100)?100:$expected_progress;

                $class = 'bg-green';
                $class2 = 'bg-green';
                if($course_id==438)
                {
                    $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project in (28,29,30,99) and project_submissions.project_id = tr_projects.id)
                    WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");

                    $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project in (28,29,30,99) and project_submissions.project_id = tr_projects.id)
                    WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
                }
                else
                {
                    $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project not in (168,227,570,571,572,573,574,575,576,592,585) and project_submissions.project_id = tr_projects.id)
                    WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");

                    $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project not in (168,227,570,571,572,573,574,575,576,592,585) and project_submissions.project_id = tr_projects.id)
                    WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
                }

                $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                if(isset($max_month_row->id))
                {
                    $class = 'bg-red';
                    $class2 = 'bg-red';
                    if($current_training_month == 0)
                    {
                        $class = 'bg-green';
                    }
                    elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                    {
                        $class = 'bg-green';
                    }
                    elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                    {
                        $class = 'bg-red';
                    }
                    else
                    {
                        $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                        $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                        if($aps_to_check == '' || $passed_units >= $aps_to_check)
                            $class = 'bg-green';
                    }

                    if($current_training_month == 0)
                        $class2 = 'bg-green';
                    elseif($current_training_month > $max_month_row->max_month && $row['evidence_progress2'] >= $max_month_row->comp)
                    {
                        $class2 = 'bg-green';
                    }
                    elseif($current_training_month > $max_month_row->max_month && $row['evidence_progress2'] < $max_month_row->comp)
                    {
                        $class2 = 'bg-red';
                    }
                    else
                    {
                        $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                        $aps_to_check = DAO::getSingleValue($link, "SELECT comp FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                        if($aps_to_check == '' || $row['evidence_progress2'] >= $aps_to_check)
                            $class2 = 'bg-green';
                    }
                }

                //echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['tr_id'], "small");
                echo '<tr class="small">';
                echo '<td title="Click to open the training record"><a href="do.php?_action=read_training_record&id=' . $row['tr_id'] . '"><i class="fa fa-folder"></i>&nbsp;View</a> </td>';
                foreach($columns as $column)
                {
                    if($column == 'total_plans')
                    {
                        echo '<td>' . HTML::cell($total_units) . '</td>';
                    }
                    elseif($column == 'expected_progress')
                    {
                        echo '<td>' . HTML::cell(($expected_progress>0)?round($expected_progress):"0") . '%</td>';
                    }
                    elseif($column == 'status')
                    {
                        if($row['completion_date']!='')
                            $status = "Complete";
                        elseif($row['iqa_status']=='2')
                            $status = "Rework Required";
                        elseif($row['iqa_recheck_date']!='')
                            $status = "IQA Recheck";
                        elseif($row['sent_iqa_date']!='' and ($row['iqa_status']!='2' or $row['iqa_status']!='3'))
                            $status = "IQA";
                        elseif($row['submission_date']!='')
                            $status = "Awaiting marking";
                        elseif($row['expired']=='1' and $row['submission_date']=='')
                            $status = "Overdue";
                        elseif($row['set_date']!='' and $row['expired']=='0' and $row['submission_number']=='1')
                            $status = "In progress";
                        else
                            $status = "Rework Required";
                        echo '<td>' . HTML::cell($status) . '</td>';
                    }
                    elseif($column == 'projects_completed')
                    {
                        echo '<td>' . HTML::cell($passed_units) . '</td>';
                    }
                    elseif($column == 'project_status')
                    {
                        if($class == 'bg-green')
                            echo '<td>On track</td>';
                        else
                            echo '<td>Behind</td>';
                    }
                    elseif($column == 'project_progress')
                    {
                        echo $total_units != 0 ? '<td style="cursor:pointer;" class="text-center '.$class.'" onclick="showApProgressLookup(\'' . $row['tr_id'] . '\');">' . $passed_units . '/' . $total_units . ' = ' . round(($passed_units/$total_units) * 100)  . '%</td>': '<td class="text-center '.$class.'">0%</td>';
                    }
                    elseif($column == 'evidence_progress')
                    {
                        echo '<td style="cursor:pointer;" class="text-center '.$class2.'" >' . $row['evidence_progress'] . '</td>';
                    }
                    elseif($column == 'weeks_on_plan')
                    {
                        echo '<td>' . HTML::cell($current_training_month) . '</td>';
                    }
                    elseif($column == 'project_2')
                    {
                        echo $total_units != 0 ? '<td style="cursor:pointer;" class="text-center '.$class.'" onclick="showApProgressLookup(\'' . $row['tr_id'] . '\');">' . $passed_units2 . '/' . $total_units . ' = ' . round(($passed_units2/$total_units) * 100)  . '%</td>': '<td class="text-center '.$class.'">0%</td>';
                    }
                    elseif($column == 'lar')
                    {
                        if(isset($row['lar']))
                            echo $row['lar'] == '' ? '<td>No</td>' : '<td>' . HTML::cell($row['lar']) . '</td>';
                        else
                            echo '<td></td>';
                    }
                    else
                    {
                        echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                    }
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