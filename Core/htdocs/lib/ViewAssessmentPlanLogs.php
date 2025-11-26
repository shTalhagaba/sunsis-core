<?php
class ViewAssessmentPlanLogs extends View
{
	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__.'11';

		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
SELECT
	tr.l03 AS learner_reference
	,CONCAT(tr.firstnames, ' ', tr.surname) AS learner_name
    ,courses.title as course
	,(SELECT organisations.legal_name FROM organisations WHERE organisations.id = tr.employer_id) AS employer
	,lookup_assessment_plan_log_mode.description as mode
	,
	CASE assessment_plan_log.paperwork
		WHEN '1' THEN 'In progress'
		WHEN '2' THEN 'Awaiting marking'
		WHEN '3' THEN 'Complete'
		WHEN '4' THEN 'Rework required'
		WHEN '5' THEN 'IQA'
		WHEN '6' THEN 'Overdue'
		WHEN '' THEN ''
	END AS paperwork
    ,IF(assessorsng.firstnames is not null, CONCAT(assessorsng.firstnames, ' ' ,assessorsng.surname),CONCAT(assessors.firstnames, ' ' ,assessors.surname)) AS assessor
    ,(select concat(firstnames, ' ', surname) from users where assessment_plan_log.assessor = users.id) as assessor_2
    ,'' as assessment_status
    ,'' as assessment_progress
    ,'' as months_on_plan
    ,'' as assessment_plan_2
    ,tr.start_date
    ,IF(courses.`title` LIKE "%L3%" OR courses.`title` LIKE "%Level 3%" , DATE_ADD(start_date, INTERVAL 10 MONTH), DATE_ADD(start_date, INTERVAL 15 MONTH)) as gateway_due_date
    ,tr.target_date as planned_end_date
    ,(SELECT MIN(due_date) FROM assessor_review WHERE due_date > NOW() AND tr_id = tr.id) AS next_review_date
	,assessment_plan_log.due_date
	,assessment_plan_log.actual_date
	,assessment_plan_log.traffic AS status
	,assessment_plan_log.marked_date as marked_date_1
	,assessment_plan_log.marked_date2 as marked_date_2
	,assessment_plan_log.marked_date3 as marked_date_3
	,assessment_plan_log.signed_off_date
	,assessment_plan_log.comments
	,tr.contract_id
	,assessment_plan_log.tr_id
	,if(signed_off_date<=due_date,1,0) as timely
FROM
	assessment_plan_log
	INNER JOIN tr ON tr.id = assessment_plan_log.tr_id
	LEFT JOIN courses_tr on tr.id = courses_tr.tr_id
	LEFT JOIN courses on courses.id = courses_tr.course_id
	LEFT JOIN group_members ON group_members.tr_id = assessment_plan_log.tr_id
	LEFT JOIN groups ON groups.id = group_members.groups_id
	LEFT JOIN users as assessors on assessors.id = groups.assessor
    LEFT JOIN users as assessorsng on assessorsng.id = tr.assessor
    LEFT JOIN lookup_assessment_plan_log_mode on lookup_assessment_plan_log_mode.id = assessment_plan_log.mode
    #where courses.id not in (349)
HEREDOC;
			$view = $_SESSION[$key] = new ViewAssessmentPlanLogs();
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

            $format = "WHERE assessment_plan_log.due_date >= '%s'";
            $f = new DateViewFilter('due_start_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE assessment_plan_log.due_date <= '%s'";
            $f = new DateViewFilter('due_end_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

			$format = "WHERE (assessment_plan_log.marked_date >= '?' or assessment_plan_log.marked_date2 >= '?' or assessment_plan_log.marked_date3 >= '?')";
			$f = new DateViewFilter('filter_from_marked_date', $format, '');
			$f->setDescriptionFormat("From Marked Date: %s");
			$view->addFilter($f);

			$format = "WHERE (assessment_plan_log.marked_date <= '?' or assessment_plan_log.marked_date2 <= '?' or assessment_plan_log.marked_date3 <= '?')";
			$f = new DateViewFilter('filter_to_marked_date', $format, '');
			$f->setDescriptionFormat("To Marked Date: %s");
			$view->addFilter($f);

			$format = "WHERE assessment_plan_log.signed_off_date >= '%s'";
			$f = new DateViewFilter('filter_from_signed_off_date', $format, '');
			$f->setDescriptionFormat("From Signed off Date: %s");
			$view->addFilter($f);

			$format = "WHERE assessment_plan_log.signed_off_date <= '%s'";
			$f = new DateViewFilter('filter_to_signed_off_date', $format, '');
			$f->setDescriptionFormat("To Signed off Date: %s");
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

			$options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE assessment_plan_log.assessor=',id) FROM users WHERE type=3 ORDER BY firstnames";
			$f = new DropDownViewFilter('filter_person_reviewed', $options, null, true);
			$f->setDescriptionFormat("Person Reviewed: %s");
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
		<th>Mode</th>
		<th>Paperwork</th>
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
		<th>Due Date</th>
		<th>Actual Date</th>
		<th>Marked Date 1</th>
		<th>Marked Date 2</th>
		<th>Marked Date 3</th>
		<th>Signed Off Date</th>
	</tr>
	</thead>
HEREDOC;

			echo '<tbody>';
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
                echo '<td>' . HTML::cell($row['mode']) . '</td>';
                echo '<td>' . HTML::cell($row['paperwork']) . '</td>';
                echo '<td>' . HTML::cell($row['assessor']) . '</td>';
                echo '<td>' . HTML::cell($row['assessor_2']) . '</td>';
                // AP PRogress
                $class = 'bg-green';
                $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$row['tr_id']}' AND paperwork = '3';");
                $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$row['tr_id']}' AND paperwork in ('3','2','5');");
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
				echo '<td>' . HTML::cell(Date::toShort($row['due_date'])) . '</td>';
				echo '<td>' . HTML::cell(Date::toShort($row['actual_date'])) . '</td>';
				echo '<td>' . HTML::cell(Date::toShort($row['marked_date_1'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['marked_date_2'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['marked_date_3'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['signed_off_date'])) . '</td>';
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