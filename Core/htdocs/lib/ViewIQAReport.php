<?php
class ViewIQAReport extends View
{
    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__.'11';

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
SELECT 
    tr.firstnames AS Firstname
    ,tr.surname AS Surname
    ,courses.title AS Programme
    ,evidence_project.project as ProjectName
    ,CONCAT(users.firstnames,' ',users.surname) AS IQALead
    ,(SELECT COUNT(*) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id) AS CountSubmissions
    ,project_submissions.acc_rej_date as AcceptOrRejectDate
    ,evidence_criteria.criteria AS Criteria
    ,IF(FIND_IN_SET(evidence_criteria.id,REPLACE(matrix," ",""))>0,"Yes","No") AS Included
    ,IF(submissions_iqa.iqa_accept=1,"Yes","") AS Accept
    ,IF(submissions_iqa.iqa_reject=1,"Yes","") AS Reject
    ,CASE submissions_iqa.reject_reason WHEN 1 THEN "VARCS not satisfied" WHEN 2 THEN "Evidence mapping error" WHEN 3 THEN "Lack of knowledge" WHEN 4 THEN "Attention to detail (Functional Skills/ Structure/ GDPR)" WHEN 5 THEN "Standards update" ELSE "" END AS RejectReason
    #,CASE submissions_iqa.first_sample WHEN 1 THEN "Yes" WHEN 0 THEN "No" ELSE "" END AS Sample1
    ,CASE submissions_iqa.fail_reason1 WHEN 1 THEN "Valid" WHEN 2 THEN "Authentic" WHEN 3 THEN "Reliable" WHEN 4 THEN "Current" WHEN 5 THEN "Sufficient" ELSE "" END AS FailReason1
    ,CASE submissions_iqa.fail_reason2 WHEN 1 THEN "Valid" WHEN 2 THEN "Authentic" WHEN 3 THEN "Reliable" WHEN 4 THEN "Current" WHEN 5 THEN "Sufficient" ELSE "" END AS FailReason2
    ,CASE submissions_iqa.fail_reason3 WHEN 1 THEN "Valid" WHEN 2 THEN "Authentic" WHEN 3 THEN "Reliable" WHEN 4 THEN "Current" WHEN 5 THEN "Sufficient" ELSE "" END AS FailReason3
    ,CASE submissions_iqa.recommendations_type WHEN 1 THEN "Higher Grades" WHEN 2 THEN "Strengthen evidence / knowledge" WHEN 3 THEN "Missed opportunity" WHEN 4 THEN "Deselect Evidence" ELSE "" END AS RecommendationType 
    ,CASE submissions_iqa.coach_actioned_status WHEN 1 THEN "Yes" WHEN 2 THEN "Set as interview prep & manager approval" WHEN 3 THEN "N/A will be picked up in next submission" ELSE "" END AS CoachActionStatus
    ,CASE project_submissions.iqa_status WHEN 1 THEN "Accepted" WHEN 2 THEN "Rejected" ELSE "" END AS IQAStatus
    ,CASE project_submissions.iqa_reason WHEN 1 THEN "Lack of evidence" WHEN 2 THEN "Wrong dates" WHEN 3 THEN "Outcomes not met" WHEN 4 THEN "Error with context/layout/Functional Skills" ELSE "" END AS IQARejectReason
    ,CASE project_submissions.attempt WHEN 1 THEN "Yes" WHEN 0 THEN "No" ELSE "" END AS FirstSample
    ,CONCAT(assessors.firstnames,' ',assessors.surname) AS Assessor
FROM tr
INNER JOIN tr_projects ON tr_projects.tr_id = tr.id
LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
INNER JOIN project_submissions ON tr_projects.id = project_submissions.project_id
INNER JOIN courses_tr ON courses_tr.tr_id = tr.id
INNER JOIN courses ON courses.id = courses_tr.course_id
INNER JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.framework_id = courses.framework_id
INNER JOIN evidence_criteria ON evidence_criteria.course_id = courses_tr.course_id AND evidence_criteria.competency = lookup_assessment_plan_log_mode.id
LEFT JOIN submissions_iqa ON submissions_iqa.competency_id = evidence_criteria.id AND submissions_iqa.tr_id = tr.id AND submissions_iqa.submission_id = project_submissions.id
LEFT JOIN users ON users.id = project_submissions.iqa
LEFT JOIN users AS assessors ON assessors.id = project_submissions.assessor
WHERE status_code = 1
ORDER BY courses.id, evidence_project.id, project_submissions.set_date,evidence_criteria.sequence;
HEREDOC;
            $view = $_SESSION[$key] = new ViewIQAReport();
            $view->setSQL($sql);

            /*$options = array(
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
            $view->addFilter($f);

            $f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_trs', "WHERE tr.id in (%s)", null);
            $view->addFilter($f); */


            $format = "WHERE acc_rej_date >= '%s'";
            $f = new DateViewFilter('acc_rej_date_from', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE acc_rej_date <= '%s'";
            $f = new DateViewFilter('acc_rej_date_to', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

            /*
            $format = "WHERE due_date >= '%s'";
            $f = new DateViewFilter('due_start_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE due_date <= '%s'";
            $f = new DateViewFilter('due_end_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f); */

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



            /* $options = "SELECT DISTINCT users.id, CONCAT(firstnames,' ',surname, ' - ' , lookup_user_types.`description`), LEFT(firstnames, 1), CONCAT('WHERE (groups.assessor=',users.id,' and tr.assessor is null) OR tr.assessor=' , users.id)
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

             $options = "SELECT username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE additional_support.tr_id in (select id from tr where assessor in (select id from users where supervisor = \'',username, '\'))') FROM users where users.username IN (SELECT supervisor FROM users WHERE supervisor IS NOT NULL) ORDER BY firstnames";
             $f = new DropDownViewFilter('filter_manager', $options, null, true);
             $f->setDescriptionFormat("Manager: %s");
             $view->addFilter($f); */

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
        $st = $link->query($this->getSQL());

        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center"><table id="tblLogs" class="table table-bordered" border="0" cellspacing="0" cellpadding="6">';
            echo <<<HEREDOC
	<thead class="bg-gray">
	<tr>
		<th>Firstname</th>
		<th>Surname</th>
		<th>Programme</th>
		<th>ProjectName</th>
		<th>IQALead</th>
		<th>CountSubmissions</th>
		<th>AcceptOrRejectDate</th>
		<th>Criteria</th>
		<th>Included</th>
		<th>Accept</th>
		<th>Reject</th>
		<th>RejectReason</th>
		<th>FailReason1</th>
		<th>FailReason2</th>
		<th>FailReason3</th>
		<th>RecommendationType</th>
		<th>CoachActionStatus</th>
		<th>IQAStatus</th>
		<th>IQARejectReason</th>
		<th>FirstSample</th>
		<th>Assessor</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $tr_id = 0;
            while($row = $st->fetch())
            {
                $style = '';
                echo '<tr ' . $style . '>';
                echo '<td>' . HTML::cell($row['Firstname']) . '</td>';
                echo '<td>' . HTML::cell($row['Surname']) . '</td>';
                echo '<td>' . HTML::cell($row['Programme']) . '</td>';
                echo '<td>' . HTML::cell($row['ProjectName']) . '</td>';
                echo '<td>' . HTML::cell($row['IQALead']) . '</td>';
                echo '<td>' . HTML::cell($row['CountSubmissions']) . '</td>';
                echo '<td>' . HTML::cell(Date::toMedium($row['AcceptOrRejectDate'])) . '</td>';
                echo '<td>' . HTML::cell($row['Criteria']) . '</td>';
                echo '<td>' . HTML::cell($row['Included']) . '</td>';
                echo '<td>' . HTML::cell($row['Accept']) . '</td>';
                echo '<td>' . HTML::cell($row['Reject']) . '</td>';
                echo '<td>' . HTML::cell($row['RejectReason']) . '</td>';
                echo '<td>' . HTML::cell($row['FailReason1']) . '</td>';
                echo '<td>' . HTML::cell($row['FailReason2']) . '</td>';
                echo '<td>' . HTML::cell($row['FailReason3']) . '</td>';
                echo '<td>' . HTML::cell($row['RecommendationType']) . '</td>';
                echo '<td>' . HTML::cell($row['CoachActionStatus']) . '</td>';
                echo '<td>' . HTML::cell($row['IQAStatus']) . '</td>';
                echo '<td>' . HTML::cell($row['IQARejectReason']) . '</td>';
                echo '<td>' . HTML::cell($row['FirstSample']) . '</td>';
                echo '<td>' . HTML::cell($row['Assessor']) . '</td>';
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