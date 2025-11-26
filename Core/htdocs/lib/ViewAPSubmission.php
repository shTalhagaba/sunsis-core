<?php
class ViewAPSubmission extends View
{
    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__.'11';

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
SELECT
     distinct tr.l03 AS learner_reference
	,CONCAT(tr.firstnames, ' ', tr.surname) AS learner_name
	,courses.title AS course
	,(SELECT organisations.legal_name FROM organisations WHERE organisations.id = tr.employer_id) AS employer
	,lookup_assessment_plan_log_mode.description AS plan
	,'' AS `status`
    ,IF(assessorsng.firstnames IS NOT NULL, CONCAT(assessorsng.firstnames, ' ' ,assessorsng.surname),CONCAT(assessors.firstnames, ' ' ,assessors.surname)) AS assessor
    ,(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE assessment_plan_log_submissions.assessor = users.id) AS assessor_2
    ,'' AS assessment_status
    ,'' AS assessment_progress
    ,'' AS months_on_plan
    ,'' AS assessment_plan_2
    ,tr.start_date
    ,IF(courses.`title` LIKE "%L3%" OR courses.`title` LIKE "%Level 3%" , DATE_ADD(start_date, INTERVAL 10 MONTH), DATE_ADD(start_date, INTERVAL 15 MONTH)) AS gateway_due_date
    ,tr.target_date AS planned_end_date
    ,(SELECT MIN(assessor_review.due_date) FROM assessor_review WHERE assessor_review.due_date > NOW() AND tr_id = tr.id) AS next_review_date
	,assessment_plan_log_submissions.due_date
	,assessment_plan_log_submissions.submission_date
	,assessment_plan_log_submissions.marked_date AS marked_date_1
	,assessment_plan_log_submissions.completion_date
	,(SELECT COUNT(*) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.`assessment_plan_id` = assessment_plan_log.`id`) AS total_submissions
	,(SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id` AND  s2.id <= assessment_plan_log_submissions.id) AS submission
	,tr.contract_id
	,assessment_plan_log.tr_id
    ,assessment_plan_log_submissions.due_date < CURDATE() AS expired
    ,assessment_plan_log_submissions.iqa_status
    ,assessment_plan_log_submissions.sent_iqa_date
    ,assessment_plan_log_submissions.assessor_signed_off
    ,assessment_plan_log_submissions.acc_rej_date
    ,IF(assessment_plan_log_submissions.iqa_status=1,"Accepted",IF(assessment_plan_log_submissions.iqa_status=2,"Rejected","")) AS iq_status
    ,assessment_plan_log_submissions.set_date
    ,case when iqa_reason = 1 then "Lack of evidence" when iqa_reason = 2 then "Wrong dates" when iqa_reason = 3 then "Outcomes not met" when iqa_reason = 4 then "Error with context/layout/Functional Skills" ELSE "" END as iqa_reason
    ,case when assessor_reason = 1 then "1st rework" when assessor_reason = 2 then "Outcomes not met" when assessor_reason = 3 then "Push back for higher grade" when assessor_reason = 4 then "Lack of evidence" when assessor_reason = 5 then "Error with context/layout/Functional Skills" ELSE "" END as assessor_reason
FROM
    assessment_plan_log_submissions
    LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
	LEFT JOIN tr ON tr.id = assessment_plan_log.tr_id
	LEFT JOIN student_frameworks on student_frameworks.tr_id = tr.id
	LEFT JOIN courses_tr ON tr.id = courses_tr.tr_id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN group_members ON group_members.tr_id = assessment_plan_log.tr_id
	LEFT JOIN groups ON groups.id = group_members.groups_id
	LEFT JOIN users AS assessors ON assessors.id = groups.assessor
    LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
    LEFT JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.id = assessment_plan_log.mode and student_frameworks.id = lookup_assessment_plan_log_mode.framework_id
HEREDOC;
            $view = $_SESSION[$key] = new ViewAPSubmission();
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
                1=>array(1, '1. In progress', null, 'WHERE paperwork=1'),
                2=>array(2, '2. Awaiting marking', null, 'WHERE paperwork=2'),
                3=>array(3, '3. Complete', null, 'WHERE paperwork=3'),
                4=>array(4, '4. Rework required', null, 'WHERE paperwork=4'),
                5=>array(5, '5. IQA', null, 'WHERE paperwork=5'),
                6=>array(6, '6. Overdue', null, 'WHERE paperwork=6'));
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

            $f = new TextboxViewFilter('firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
            $f->setDescriptionFormat("Firstnames: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_trs', "WHERE tr.id in (%s)", null);
            $view->addFilter($f);


            $format = "WHERE assessment_plan_log.actual_date >= '%s'";
            $f = new DateViewFilter('last_start_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE assessment_plan_log.actual_date <= '%s'";
            $f = new DateViewFilter('last_end_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

            $format = "WHERE due_date >= '%s'";
            $f = new DateViewFilter('due_start_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE due_date <= '%s'";
            $f = new DateViewFilter('due_end_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

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

            $options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE assessment_plan_log_submissions.assessor=',id) FROM users WHERE type=3 ORDER BY firstnames";
            $f = new DropDownViewFilter('filter_person_reviewed', $options, null, true);
            $f->setDescriptionFormat("Person Reviewed: %s");
            $view->addFilter($f);

            $options = "SELECT username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE assessment_plan_log_submissions.assessor=',username) FROM users WHERE type=3 ORDER BY firstnames";
            $f = new DropDownViewFilter('filter_manager', $options, null, true);
            $f->setDescriptionFormat("Person Reviewed: %s");
            $view->addFilter($f);

            $options = "SELECT username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE assessment_plan_log.tr_id in (select id from tr where assessor in (select id from users where supervisor = \'',username, '\'))') FROM users where users.username IN (SELECT supervisor FROM users WHERE supervisor IS NOT NULL) ORDER BY firstnames";
            $f = new DropDownViewFilter('filter_manager', $options, null, true);
            $f->setDescriptionFormat("Manager: %s");
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

            $format = "WHERE tr.start_date >= '%s'";
            $f = new DateViewFilter('start_date_from', $format, '');
            $f->setDescriptionFormat("Start Date From: %s");
            $view->addFilter($f);

            $format = "WHERE tr.start_date <= '%s'";
            $f = new DateViewFilter('start_date_to', $format, '');
            $f->setDescriptionFormat("Start Date To: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT id, description, null, CONCAT('WHERE courses.routway=',lookup_routways.id) FROM lookup_routways";
            $f = new DropDownViewFilter('filter_routways', $options, null, true);
            $f->setDescriptionFormat("Routways: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. Assessor signed off', null, 'WHERE assessor_signed_off is not null'),
                2=>array(2, '2. Assessor not signed off', null, 'WHERE assessor_signed_off is null'));
            $f = new DropDownViewFilter('filter_signed_off', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            /*$options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. First or second submissio#n', null, 'WHERE ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id` AND  s2.id <= assessment_plan_log_submissions.id) < 3) '),
                2=>array(2, '2. Third or more submission', null, 'WHERE ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id` AND  s2.id <= assessment_plan_log_submissions.id) > 2 )'));
            $f = new DropDownViewFilter('filter_submission_count', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);*/

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. First submission', null, 'WHERE ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id`) = 1) '),
                2=>array(2, '2. Second submission', null, 'WHERE ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id`) = 2 )'));
            $f = new DropDownViewFilter('filter_submission_count', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);


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
		<th>Learner Reference</th>
		<th>Learner Name</th>
        <th>Course</th>
		<th>Employer</th>
		<th>Plan</th>
		<th>Status</th>
		<th>Assessor</th>
		<th>Assessor 2</th>
        <th>Assessment Status</th>
        <th>Assessment Progress</th>
        <th>Months On Plan</th>
        <th>Assessment Plan 2</th>
        <th>Start Date</th>
        <th>Gateway Due Date</EPA>
		<th>Planned End Date</th>
		<th>Next Review Date</th>
		<th>Set Date</th>
		<th>Due Date</th>
		<th>Actual Date</th>
		<th>Sent IQA Date</th>
		<th>Assessor signed off</th>
		<th>Acc Rej Date</th>
		<th>IQA Status</th>
		<th>Completion Date</th>
		<th>Total Submissions</th>
		<th>Submission</th>
		<th>IQA Reason</th>
		<th>Assessor Reason</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $tr_id = 0;
            while($row = $st->fetch())
            {
                /*if($row['timely']=='1')
                    $style = 'bgcolor="#20b2aa"';
                else*/
                $style = '';
                echo '<tr ' . $style . '>';
                echo '<td>' . HTML::cell($row['learner_reference']) . '</td>';
                echo '<td><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . '&amp;contract=' . $row['contract_id']. ' ><span style="color: black"> ' . HTML::cell($row['learner_name']) . '</span></a></td>';
                echo '<td>' . HTML::cell($row['course']) . '</td>';
                echo '<td>' . HTML::cell($row['employer']) . '</td>';
                echo '<td>' . HTML::cell($row['plan']) . '</td>';

                if($row['completion_date']!='')
                    $status = "Complete";
                elseif($row['iqa_status']=='2')
                    $status = "Rework Required";
                elseif($row['sent_iqa_date']!='' and ($row['iqa_status']!='2' or $row['iqa_status']!='3'))
                    $status = "IQA";
                elseif($row['submission_date']!='')
                    $status = "Awaiting marking";
                elseif($row['expired']=='1' and $row['submission_date']=='')
                    $status = "Overdue";
                elseif($row['set_date']!='' and $row['expired']=='0' and $row['total_submissions']=='1')
                    $status = "In progress";
                else
                    $status = "Rework Required";

                echo '<td>' . HTML::cell($status) . '</td>';
                echo '<td>' . HTML::cell($row['assessor']) . '</td>';
                echo '<td>' . HTML::cell($row['assessor_2']) . '</td>';
                // AP PRogress
                $class = 'bg-green';
                $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		        sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");
                $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		        sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and COALESCE(sub.iqa_status,0)!=2))");
                $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                $sd = Date::toMySQL($row['start_date']);
                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
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
                if($class=='bg-green')
                    echo '<td>On track</td>';
                else
                    echo '<td>Behind</td>';
                echo count($total_units) != 0 ? '<td style="cursor:pointer;" class="text-center '.$class.'" onclick="showApProgressLookup(\'' . $row['tr_id'] . '\');">' . $passed_units . '/' . $total_units . ' = ' . round(($passed_units/$total_units) * 100)  . '%</td>': '<td class="text-center '.$class.'">0%</td>';
                echo '<td>' . HTML::cell($current_training_month) . '</td>';
                echo count($total_units) != 0 ? '<td style="cursor:pointer;" class="text-center '.$class.'" onclick="showApProgressLookup(\'' . $row['tr_id'] . '\');">' . $passed_units2 . '/' . $total_units . ' = ' . round(($passed_units2/$total_units) * 100)  . '%</td>': '<td class="text-center '.$class.'">0%</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['start_date'])) . '</td>';
                echo '<td>' . Date::toShort($row['gateway_due_date']) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['planned_end_date'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['next_review_date'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['set_date'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['due_date'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['submission_date'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['sent_iqa_date'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['assessor_signed_off'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['acc_rej_date'])) . '</td>';
                echo '<td>' . HTML::cell($row['iq_status']) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['completion_date'])) . '</td>';
                echo '<td>' . HTML::cell($row['total_submissions']) . '</td>';
                echo '<td>' . HTML::cell($row['submission']) . '</td>';
                $tr_id = $row['tr_id'];
                echo '<td>' . HTML::cell($row['iqa_reason']) . '</td>';
                echo '<td>' . HTML::cell($row['assessor_reason']) . '</td>';
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