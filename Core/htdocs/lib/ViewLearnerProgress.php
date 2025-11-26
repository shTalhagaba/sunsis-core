<?php
class ViewLearnerProgress extends View
{
    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__.'11';

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
SELECT
tr.id AS tr_id
,courses.id as course_id
,  CASE tr_operations.learner_status
    WHEN 'A' THEN 'Achieved'
    WHEN 'BIL' THEN 'BIL'
    WHEN 'LAR' THEN 'LAR'
    WHEN 'OP' THEN 'On Programme'
    WHEN 'PA' THEN 'PEED - Assessment'
    WHEN 'PC' THEN 'PEED - Coordinator'
    WHEN 'PLM' THEN 'PEED - Learning Mentor'
    WHEN 'GR' THEN 'Gateway Ready'
    WHEN 'F' THEN 'Fail'
  END as learner_status
,DATE_FORMAT(start_date, '%d/%m/%Y') AS start_date
,DATE_FORMAT(target_date, '%d/%m/%Y') AS planned_end_date
,DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y') AS gateway_forecast_date
,courses.title AS programme
,CONCAT(users.firstnames,' ',users.surname) AS assessor
,(SELECT contact_name FROM organisation_contact WHERE contact_id = tr.crm_contact_id) AS line_manager
,tr.firstnames
,tr.surname
,'' AS assessment_progress
,'' AS assessment_progress_2
,'' AS assessment_plan_status
,(SELECT IF(due_date2 IS NOT NULL AND due_date2!='0000-00-00', DATE_FORMAT(due_date2, '%d/%m/%Y'), IF(due_date1 IS NOT NULL AND due_date1!='0000-00-00', DATE_FORMAT(due_date1, '%d/%m/%Y'),DATE_FORMAT(due_date, '%d/%m/%Y'))) FROM assessor_review WHERE tr_id = tr.id AND IF(due_date2 IS NOT NULL AND due_date2!='0000-00-00', due_date2, IF(due_date1 IS NOT NULL AND due_date1!='0000-00-00', due_date1, due_date)) > NOW() ORDER BY due_date DESC LIMIT 0,1) AS next_review
,(SELECT DATE_FORMAT(due_date,'%d/%m/%Y') FROM additional_support WHERE due_date>=CURDATE() AND tr_id = tr.id ORDER BY due_date LIMIT 0,1) AS next_additional_support
FROM tr
LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id
LEFT JOIN (SELECT m1.* FROM op_epa m1 LEFT JOIN op_epa m2 ON (m1.tr_id = m2.tr_id AND m1.task = m2.task AND m1.id < m2.id) WHERE m1.task = 12 AND m2.id IS NULL ) AS op_epa ON tr.`id` = op_epa.tr_id
LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
LEFT JOIN courses ON courses.id = courses_tr.course_id
LEFT JOIN users ON users.id = tr.assessor
HEREDOC;
            $view = $_SESSION[$key] = new ViewLearnerProgress();
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
                1=>array(1, 'Achived', null, "WHERE tr_operations.learner_status='A'"),
                2=>array(2, 'BIL', null, "WHERE tr_operations.learner_status='BIL'"),
                3=>array(3, 'LAR', null, "WHERE tr_operations.learner_status='LAR'"),
                4=>array(4, 'On Proramme', null, "WHERE tr_operations.learner_status='OP'"),
                5=>array(5, 'PEED - Assessment', null, "WHERE tr_operations.learner_status='PA'"),
                6=>array(6, 'PEED - Coordinator', null, "WHERE tr_operations.learner_status='PC'"),
                7=>array(7, 'PEED - Learning Mentor', null, "WHERE tr_operations.learner_status='PLM'"),
                8=>array(8, 'Gateway Ready', null, "WHERE tr_operations.learner_status='GR'"),
                9=>array(9, 'Fail', null, "WHERE tr_operations.learner_status='F'"));
            $f = new DropDownViewFilter('filter_record_status_operations', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            /*$options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. No contact 12 weeks', null, 'WHERE DATE_ADD((DATE_ADD(`actual_date`, INTERVAL 12 WEEK)), INTERVAL (9-DAYOFWEEK((DATE_ADD(`actual_date`, INTERVAL 12 WEEK)))) DAY) <= NOW() AND tr.`status_code` = 1 AND additional_support.id = (SELECT MAX(id) FROM additional_support adds WHERE adds.tr_id = additional_support.`tr_id`)'));
            $f = new DropDownViewFilter('filter_no_contact', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f); */

            /*$options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. Green', null, 'WHERE traffic=1'),
                2=>array(2, '2. Yellow', null, 'WHERE traffic=2'),
                3=>array(3, '3. Red', null, 'WHERE traffic=3'));
            $f = new DropDownViewFilter('filter_review_status', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. In progress', null, 'WHERE paperwork=1'),
                2=>array(2, '2. Awaiting marking', null, 'WHERE paperwork=2'),
                3=>array(3, '3. Complete', null, 'WHERE paperwork=3'),
                4=>array(4, '4. Rework required', null, 'WHERE paperwork=4'),
                5=>array(5, '5. IQA', null, 'WHERE paperwork=5'),
                6=>array(6, '6. Overdue', null, 'WHERE paperwork=6'));
            $f = new DropDownViewFilter('filter_paperwork', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f); */

            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);


            /*
            $options = array(
                0=>array(1, 'Learner, Due Date ASC', null, 'ORDER BY learner_name, due_date ASC'),
                1=>array(2, 'L03', null, 'ORDER BY l03'),
                2=>array(3, 'Leaner', null, 'ORDER BY learner_name'),
                3=>array(4, 'Status, Due Date Desc, Actual End Date Desc', null, 'ORDER BY learner_name'));

            $f = new DropDownViewFilter('order_by', $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);*/

            $f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            /*$f = new TextboxViewFilter('filter_trs', "WHERE tr.id in (%s)", null);
            $view->addFilter($f); */


            /*$format = "WHERE forms_audit.date >= '%s'";
            $f = new DateViewFilter('actual_start_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE forms_audit.date <= '%s'";
            $f = new DateViewFilter('actual_end_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);*/


            $format = "WHERE op_epa.task_actual_date >= '%s'";
            $f = new DateViewFilter('gateway_forecast_date_from', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE op_epa.task_actual_date <= '%s'";
            $f = new DateViewFilter('gateway_forecast_date_to', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

            /*
            $format = "WHERE assessment_plan_log_submissions.marked_date >= '%s'";
            $f = new DateViewFilter('filter_from_marked_date', $format, '');
            $f->setDescriptionFormat("From Marked Date: %s");
            $view->addFilter($f);

            $format = "WHERE assessment_plan_log_submissions.marked_date <= '%s'";
            $f = new DateViewFilter('filter_to_marked_date', $format, '');
            $f->setDescriptionFormat("To Marked Date: %s");
            $view->addFilter($f);

            $format = "WHERE completion_date >= '%s'";
            $f = new DateViewFilter('filter_from_signed_off_date', $format, '');
            $f->setDescriptionFormat("From Signed off Date: %s");
            $view->addFilter($f);

            $format = "WHERE completion_date <= '%s'";
            $f = new DateViewFilter('filter_to_signed_off_date', $format, '');
            $f->setDescriptionFormat("To Signed off Date: %s");
            $view->addFilter($f);

            $format = "WHERE assessment_plan_log_submissions.assessor_signed_off >= '%s'";
            $f = new DateViewFilter('filter_from_assessor_signed_off', $format, '');
            $f->setDescriptionFormat("From Assessor Signed off: %s");
            $view->addFilter($f);

            $format = "WHERE assessment_plan_log_submissions.assessor_signed_off <= '%s'";
            $f = new DateViewFilter('filter_to_assessor_signed_off', $format, '');
            $f->setDescriptionFormat("To Assessor Signed off Date: %s");
            $view->addFilter($f);*/



            $options = "SELECT DISTINCT users.id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE tr.assessor=' , users.id)
 FROM users
 LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
 WHERE
 users.id IN (SELECT assessor FROM tr WHERE status_code = 1 AND assessor IS NOT NULL)
 ORDER BY firstnames, surname;";
            $f = new DropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Learning mentor: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT courses.id, title, LEFT(title, 1), CONCAT('WHERE courses_tr.course_id=' , courses.id)
 FROM courses
 WHERE
courses.active = 1
";
            $f = new DropDownViewFilter('filter_programme', $options, null, true);
            $f->setDescriptionFormat("Programme: %s");
            $view->addFilter($f);


            $options = "SELECT username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE tr.id in (select id from tr where assessor in (select id from users where supervisor = \'',username, '\'))') FROM users where users.username IN (SELECT supervisor FROM users WHERE supervisor IS NOT NULL) ORDER BY firstnames";
            $f = new DropDownViewFilter('filter_manager', $options, null, true);
            $f->setDescriptionFormat("Manager: %s");
            $view->addFilter($f);

            /*
            $options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE assessment_plan_log_submissions.assessor=',id) FROM users WHERE type=3 ORDER BY firstnames";
            $f = new DropDownViewFilter('filter_person_reviewed', $options, null, true);
            $f->setDescriptionFormat("Person Reviewed: %s");
            $view->addFilter($f);

            $f = new DropDownViewFilter('filter_employer', $options, null, true);
            $f->setDescriptionFormat("Person Reviewed: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
            $f->setDescriptionFormat("Learner ULN: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
            $f->setDescriptionFormat("LearnRefNumber: %s");
            $view->addFilter($f);

            */
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        //if(SOURCE_BLYTHE_VALLEY)
        //pr($this->getSQL());
        $st = $link->query($this->getSQL());

        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center"><table id="tblLogs" class="table table-bordered" border="0" cellspacing="0" cellpadding="6">';
            echo <<<HEREDOC
	<thead class="bg-gray">
	<tr>
		<th>Learner Status</th>
		<th>Start Date</th>
		<th>Planned End Date</th>
		<th>Gateway Forecast Date</th>
		<th>Programme</th>
		<th>Assessor</th>
		<th>Line Manager</th>
		<th>Firstname</th>
		<th>Surname</th>
		<th>Assessment Progress</th>
		<th>Assessment Progress 2</th>
		<th>Assessment Plan Status</th>
		<th>Next Review</th>
		<th>Next Support Session</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $tr_id = 0;
            while($row = $st->fetch())
            {
                $style = '';
                echo '<tr ' . $style . '>';
                echo '<td>' . HTML::cell($row['learner_status']) . '</td>';
                echo '<td>' . HTML::cell($row['start_date']) . '</td>';
                echo '<td>' . HTML::cell($row['planned_end_date']) . '</td>';
                echo '<td>' . HTML::cell($row['gateway_forecast_date']) . '</td>';
                echo '<td>' . HTML::cell($row['programme']) . '</td>';
                echo '<td>' . HTML::cell($row['assessor']) . '</td>';
                echo '<td>' . HTML::cell($row['line_manager']) . '</td>';
                echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
                echo '<td>' . HTML::cell($row['surname']) . '</td>';

                // Assessment Plan Status
                $class = '';
                $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                $assessment_evidence = DAO::getSingleValue($link, "SELECT assessment_evidence FROM courses WHERE id = '{$row['course_id']}'");
                $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$row['course_id']}';");
                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                if($assessment_evidence==2)
                {
                    $class = 'bg-green';
                    $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");
                    $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
                    $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$row['course_id']}' ORDER BY id DESC LIMIT 1");
                    if(isset($max_month_row->id))
                    {
                        $class = 'bg-red';
                        if($current_training_month == 0)
                            $class = 'bg-green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                            $class = 'bg-green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                            $class = 'bg-red';
                        else
                        {
                            $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$row['course_id']}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                            $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$row['course_id']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                            if($aps_to_check == '' || $passed_units >= $aps_to_check)
                                $class = 'bg-green';
                        }
                    }
                    echo count($total_units) != 0 ? '<td style="cursor:pointer;" class="text-center '.$class.'" onclick="showApProgressLookup(\'' . $row['tr_id'] . '\');">' . $passed_units . '/' . $total_units . ' = ' . round(($passed_units/$total_units) * 100)  . '%</td>': '<td class="text-center '.$class.'">0%</td>';
                    echo count($total_units) != 0 ? '<td style="cursor:pointer;" class="text-center '.$class.'" onclick="showApProgressLookup(\'' . $row['tr_id'] . '\');">' . $passed_units2 . '/' . $total_units . ' = ' . round(($passed_units2/$total_units) * 100)  . '%</td>': '<td class="text-center '.$class.'">0%</td>';
                }
                else
                {
                    $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
                                    sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
                    $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
                        sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                        WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
                    $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$row['course_id']}' ORDER BY id DESC LIMIT 1");
                    $sd = Date::toMySQL($row['start_date']);
                    if(isset($max_month_row->id))
                    {
                        $class = 'bg-red';
                        if($current_training_month == 0)
                            $class = 'bg-green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                            $class = 'bg-green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                            $class = 'bg-red';
                        else
                        {
                            $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                            $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                            if($aps_to_check == '' || $passed_units >= $aps_to_check)
                                $class = 'bg-green';
                        }
                    }
                    echo ($total_units != 0)
                        ? '<td class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . round(($passed_units/$total_units) * 100)  . '%</td>'
                        : '<td class="text-center '.$class.'">0%</td>';

                    echo ($total_units != 0)
                        ? '<td class="text-center '.$class.'">' . $passed_units2 . '/' . $total_units . ' = ' . round(($passed_units2/$total_units) * 100)  . '%</td>'
                        : '<td class="text-center '.$class.'">0%</td>';
                }

                // Assessment Plans
                if($assessment_evidence==2)
                {
                    $ap = DAO::getSingleValue($link, "SELECT
GROUP_CONCAT(CONCAT('\r\n',evidence_project.project, ' (' ,(SELECT COUNT(*) FROM project_submissions WHERE project_submissions.`project_id` = tr_projects.id) ,') '
,IF(sub.completion_date IS NOT NULL,\"Complete\",IF(sub.iqa_status=2,\"Rework required\",IF(sub.sent_iqa_date IS NOT NULL AND (sub.iqa_status IS NULL OR sub.iqa_status!=2),\"IQA\",IF(sub.submission_date IS NOT NULL,\"Awaiting marking\",IF(sub.due_date<CURDATE() AND submission_date IS NULL,\"Overdue\",\"In progress\"))))))) AS `status`
FROM tr_projects
LEFT JOIN project_submissions AS sub ON sub.`project_id` = tr_projects.`id` AND sub.id = (SELECT MAX(id) FROM project_submissions AS s2 WHERE s2.`project_id` = tr_projects.id)
LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
WHERE tr_projects.project IS NOT NULL AND tr_projects.tr_id= {$row['tr_id']}");
                    echo '<td>' . HTML::cell($ap) . '</td>';
                }
                else
                {
                    $ap = DAO::getSingleValue($link, "SELECT
GROUP_CONCAT(CONCAT('\r\n',lookup_assessment_plan_log_mode.description, ' ('
,(SELECT COUNT(*) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.`assessment_plan_id` = assessment_plan_log.`id`)
,') ',(IF(sub.completion_date IS NOT NULL,\"Complete\",IF(sub.iqa_status=2,\"Rework required\",IF(sub.sent_iqa_date IS NOT NULL AND (sub.iqa_status IS NULL OR sub.iqa_status!=2),\"IQA\",IF(sub.submission_date IS NOT NULL,\"Awaiting marking\",IF(sub.due_date<CURDATE() AND submission_date IS NULL,\"Overdue\",\"In progress\"))))))))
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
LEFT JOIN tr ON tr.id = assessment_plan_log.tr_id
LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
LEFT JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.id = assessment_plan_log.mode AND student_frameworks.id = lookup_assessment_plan_log_mode.framework_id
WHERE assessment_plan_log.tr_id = {$row['tr_id']}");
                    echo '<td>' . HTML::cell($ap) . '</td>';
                }
                echo '<td>' . HTML::cell($row['next_review']) . '</td>';
                echo '<td>' . HTML::cell($row['next_additional_support']) . '</td>';
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