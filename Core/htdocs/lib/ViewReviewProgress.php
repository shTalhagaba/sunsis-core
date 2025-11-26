<?php
class ViewReviewProgress extends View
{
    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__.'1';

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
select
distinct
 	CONCAT(tr.firstnames, ' ', tr.surname) AS learner_name
 	,arf_introduction.learner_employer as employer
 	,tr.l03 as learner_reference
 	,tr.id as tr_id
 	,tr.contract_id
 	,tr.start_date
 	,frameworks.title as framework
 	,assessor_review.due_date as review_forecast_date
 	,IF(due_date3 IS NOT NULL AND due_date3!='0000-00-00', due_date3, IF(due_date2 IS NOT NULL AND due_date2!='0000-00-00', due_date2, IF(due_date1 IS NOT NULL AND due_date1!='0000-00-00', due_date1, ''))) AS revised_review_date
 	,IF(reason3=1,'Learner', IF(reason3=2, 'Assessor', IF(reason3=3, 'Employer', if(reason2=1,'Learner', IF(reason2=2,'Assessor',IF(reason2=3,'Employer',IF(reason1=1,'Learner',IF(reason1=2,'Assessor', IF(reason1=3,'Employer',''))))))))) AS reason
    ,IF(manager_auth3=1,'Yes',IF(manager_auth2=1,'Yes',IF(manager_auth1=1,'Yes',''))) as auth_by_manager
 	,assessor_review.meeting_date as actual_date
    ,IF(assessorsng.firstnames is not null, CONCAT(assessorsng.firstnames, ' ' ,assessorsng.surname),CONCAT(assessors.firstnames, ' ' ,assessors.surname)) AS assessor
    ,arf_introduction.learner_assessor AS assessor_2
    ,assessor_review.assessor_comments
    ,if(assessor_review.manager_attendance=1,"Yes","No") as manager_attendance
 	,assessor_review.id
    ,(SELECT `date` FROM forms_audit WHERE description = 'Review Form Emailed to Learner' AND forms_audit.`form_id` = assessor_review.id ORDER BY `date` DESC LIMIT 1) emailed_to_learner
    ,(SELECT `date` FROM forms_audit WHERE description = 'Review Form Emailed to Employer' AND forms_audit.`form_id` = assessor_review.id ORDER BY `date` DESC LIMIT 1) emailed_to_employer
    ,(select description from forms_audit where description in ('Review Form Emailed to Learner','Review Form Emailed to Employer','Review Form 24HR Emailed to Learner','Review Form 48HR Emailed to Learner','Review Form 72HR Emailed to Learner','Review Form 72HR Emailed to Employer','Review Form 120HR Emailed to Employer','Review Form 168HR Emailed to Employer','Welcome Review Form Emailed to Learner','Welcome Review Form Emailed to Employer') and forms_audit.form_id = assessor_review.id order by date desc limit 0,1) last_email
    ,(select date from forms_audit where description in ('Review Form Emailed to Learner','Review Form Emailed to Employer','Review Form 24HR Emailed to Learner','Review Form 48HR Emailed to Learner','Review Form 72HR Emailed to Learner','Review Form 72HR Emailed to Employer','Review Form 120HR Emailed to Employer','Review Form 168HR Emailed to Employer','Welcome Review Form Emailed to Learner','Welcome Review Form Emailed to Employer') and forms_audit.form_id = assessor_review.id order by date desc limit 0,1) last_email_date
    ,IF(arf_introduction.`signature_employer_font` IS NOT NULL AND arf_introduction.`signature_learner_font` IS NOT NULL AND arf_introduction.`signature_assessor_font` IS NOT NULL, 'Complete',
        IF(arf_introduction.`signature_employer_font` IS NULL AND arf_introduction.`signature_learner_font` IS NOT NULL AND arf_introduction.`signature_assessor_font` IS NOT NULL,'Awaiting Employer',
        IF(arf_introduction.`signature_employer_font` IS NULL AND arf_introduction.`signature_learner_font` IS NULL AND arf_introduction.`signature_assessor_font` IS NOT NULL,'Awaiting Learner','New'
        ))) AS `status`
    ,(SELECT CASE arf_introduction.attendance WHEN 1 THEN 'Poor' WHEN 2 THEN 'Satisfactory' WHEN 3 THEN 'Good' WHEN 4 THEN 'Excellent' WHEN NULL THEN "" END) AS attendance
    ,(SELECT CASE arf_introduction.punctuality WHEN 1 THEN 'Poor' WHEN 2 THEN 'Satisfactory' WHEN 3 THEN 'Good' WHEN 4 THEN 'Excellent' WHEN NULL THEN "" END) AS punctuality
    ,(SELECT CASE arf_introduction.attitude WHEN 1 THEN 'Poor' WHEN 2 THEN 'Satisfactory' WHEN 3 THEN 'Good' WHEN 4 THEN 'Excellent' WHEN NULL THEN "" END) AS attitude
    ,(SELECT CASE arf_introduction.communication WHEN 1 THEN 'Poor' WHEN 2 THEN 'Satisfactory' WHEN 3 THEN 'Good' WHEN 4 THEN 'Excellent' WHEN NULL THEN "" END) AS communication
    ,(SELECT CASE arf_introduction.enthusiasm WHEN 1 THEN 'Poor' WHEN 2 THEN 'Satisfactory' WHEN 3 THEN 'Good' WHEN 4 THEN 'Excellent' WHEN NULL THEN "" END) AS enthusiasm
    ,(SELECT CASE arf_introduction.commitment WHEN 1 THEN 'Poor' WHEN 2 THEN 'Satisfactory' WHEN 3 THEN 'Good' WHEN 4 THEN 'Excellent' WHEN NULL THEN "" END) AS commitment
  ,(SELECT
	CASE frameworks.duration_in_months
		WHEN 12 THEN CASE school_id
				WHEN 38 THEN 360
				WHEN 40 THEN 372
				WHEN 43 THEN 395
				WHEN 45 THEN 418
			     END
		WHEN 15 THEN CASE school_id
				WHEN 38 THEN 445
				WHEN 40 THEN 456
				WHEN 43 THEN 485
				WHEN 45 THEN 513
			     END
		WHEN 18 THEN CASE school_id
				WHEN 38 THEN 525
				WHEN 40 THEN 557
				WHEN 43 THEN 592
				WHEN 45 THEN 627
			     END
	END) AS expected_reflective_hours
  ,(SELECT max(current_hours) FROM arf_introduction WHERE current_hours IS NOT NULL AND review_id IN (SELECT id FROM assessor_review WHERE tr_id = tr.id)) as reflective_hours
  ,(  (SELECT CASE frameworks.duration_in_months WHEN 12 THEN CASE school_id WHEN 38 THEN 360 WHEN 40 THEN 372 WHEN 43 THEN 395 WHEN 45 THEN 418 END WHEN 15 THEN CASE school_id WHEN 38 THEN 445 WHEN 40 THEN 465 WHEN 43 THEN 493 WHEN 45 THEN 522 END WHEN 18 THEN CASE school_id WHEN 38 THEN 525 WHEN 40 THEN 557 WHEN 43 THEN 592 WHEN 45 THEN 627 END END)-(SELECT max(current_hours) FROM arf_introduction WHERE current_hours IS NOT NULL AND review_id IN (SELECT id FROM assessor_review WHERE tr_id = tr.id))) as remaining_reflective_hours
  ,(SELECT CASE school_id WHEN 38 THEN 'Up to 37.5 hours' WHEN 40 THEN '38 to 40 hours' WHEN 43 THEN '40.05 to 42.5 hours' WHEN 45 THEN '43 to 45 hours' END) AS contracted_hours
  ,IF(template_review=1, "1-Introduction", CONCAT((SELECT (COUNT(*)-1) FROM assessor_review AS s2  WHERE s2.tr_id = assessor_review.tr_id AND  s2.id <= assessor_review.id),"-On Programme")) AS review_template
from
assessor_review
left join tr on tr.id = assessor_review.tr_id
left join forms_audit on forms_audit.form_id = assessor_review.id
LEFT JOIN assessor_review_forms_assessor4 ON assessor_review_forms_assessor4.`review_id` = assessor_review.id
LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id
LEFT JOIN assessor_review_forms_employer on assessor_review_forms_employer.review_id = assessor_review.id
LEFT JOIN arf_introduction on arf_introduction.review_id = assessor_review.id
LEFT JOIN group_members on group_members.tr_id = tr.id
LEFT JOIN groups on groups.id = group_members.groups_id
LEFT JOIN users as assessors on assessors.id = groups.assessor
LEFT JOIN users as assessorsng on assessorsng.id = tr.assessor
LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
LEFT JOIN frameworks on frameworks.id = courses_tr.framework_id
HEREDOC;
            $view = $_SESSION[$key] = new ViewReviewProgress();
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
                1=>array(1, '1. Review Form Emailed to Learner', null, 'WHERE forms_audit.description=\'Review Form Emailed to Learner\''),
                2=>array(2, '2. Review Form Emailed to Employer', null, 'WHERE forms_audit.description=\'Review Form Emailed to Employer\''),
                3=>array(3, '3. Review Form Emailed to Learner or Employer', null, 'WHERE ((arf_introduction.review_id IS NOT NULL AND arf_introduction.signature_assessor_font IS NOT NULL AND arf_introduction.signature_learner_font IS NULL) OR (arf_introduction.review_id IS NOT NULL AND arf_introduction.signature_learner_font IS NOT NULL AND arf_introduction.signature_employer_font IS NULL))'),
                4=>array(4, '4. Learner Not Signed', null, 'where (assessor_review_forms_assessor4.review_id is not null and assessor_review_forms_learner.signature_learner_font IS NULL and assessor_review_forms_assessor4.signature_assessor_font is not null) or (arf_introduction.review_id is not null and arf_introduction.signature_learner_font IS NULL and arf_introduction.signature_assessor_font is not null)'));
            $f = new DropDownViewFilter('filter_audit', $options, 0, false);
            $f->setDescriptionFormat("Audit: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. Awaiting learner signature', null, 'WHERE (assessor_review_forms_learner.signature_learner_font is null or arf_introduction.signature_learner_font is null)'),
                2=>array(2, '2. Awaiting employer signature', null, 'WHERE arf_introduction.signature_learner_font is not null and arf_introduction.signature_employer_font is null'),
                3=>array(3, '3. Awaiting learner/employer signature', null, 'WHERE (signature_employer_font is null or signature_learner_font is null)'),
                4=>array(4, '4. Awaiting learner signature 24 hours', null, 'WHERE forms_audit.description = \'Review Form Emailed to Learner\' AND DATE_ADD(`date`, INTERVAL 1 DAY) < NOW() AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_learner WHERE signature_learner_font IS NOT NULL) AND form_id NOT IN (SELECT review_id FROM arf_introduction WHERE signature_learner_font IS NOT NULL)'),
                5=>array(5, '5. Awaiting employer signature 7 days', null, 'WHERE forms_audit.description = \'Review Form Emailed to Employer\' AND DATE_ADD(`date`, INTERVAL 7 DAY) < NOW() AND DATE_ADD(`date`, INTERVAL 5 WEEK) >= NOW() AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_employer WHERE signature_employer_font IS NOT NULL) AND form_id NOT IN (SELECT review_id FROM arf_introduction WHERE signature_employer_font IS NOT NULL)'),
                6=>array(6, '6. Awaiting employer signature 5 weeks', null, 'WHERE tr.status_code = 1 and forms_audit.description = \'Review Form Emailed to Employer\' AND DATE_ADD(`date`, INTERVAL 5 WEEK) < NOW() AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_employer WHERE signature_employer_font IS NOT NULL) AND form_id NOT IN (SELECT review_id FROM arf_introduction WHERE signature_employer_font IS NOT NULL)'),
                7=>array(7, '7. Total due this month', null, 'WHERE tr.status_code = 1'),
                11=>array(11, '11. Reviews have not been sent within 24 hours', null, 'WHERE arf_introduction.signature_assessor_font is null AND arf_introduction.review_date < NOW()'),
                8=>array(8, '8. Review complete', null, 'WHERE (assessor_review_forms_learner.signature_learner_font is not null and assessor_review_forms_employer.signature_employer_font is not null) or (arf_introduction.signature_learner_font is not null and arf_introduction.signature_employer_font is not null)'),
                9=>array(9, '9. No Contact 12 weeks', null, 'WHERE assessor_review.tr_id IN (SELECT tr.id FROM tr LEFT JOIN student_frameworks ON student_frameworks.`tr_id` = tr.id LEFT JOIN frameworks ON frameworks.id = student_frameworks.`id` WHERE target_date >= now() and status_code = 1 AND ((DATE_ADD(start_date, INTERVAL 12 MONTH) > NOW() AND frameworks.framework_type IN (2,3,25)) OR (DATE_ADD(start_date, INTERVAL 18 MONTH) > NOW() AND frameworks.framework_type IN (20)))) AND DATE_ADD((DATE_ADD(`meeting_date`, INTERVAL 12 WEEK)), INTERVAL (9-DAYOFWEEK((DATE_ADD(`meeting_date`, INTERVAL 12 WEEK)))) DAY) <= NOW() AND meeting_date=(SELECT MAX(meeting_date) FROM assessor_review AS ar2 WHERE ar2.tr_id = assessor_review.tr_id)'),
                10=>array(10, '10. Assessor signed', null, 'WHERE assessor_review.tr_id IN (SELECT id FROM tr WHERE status_code = 1) and assessor_review.id in (SELECT review_id FROM assessor_review_forms_assessor4 WHERE signature_assessor_font IS NOT NULL UNION SELECT review_id FROM arf_introduction WHERE signature_assessor_font IS NOT NULL)'));
            $f = new DropDownViewFilter('filter_signature', $options, 0, false);
            $f->setDescriptionFormat("Signature: %s");
            $view->addFilter($f);

            /*$options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. In progress', null, 'WHERE paperwork=1'),
                2=>array(2, '2. Awaiting marking', null, 'WHERE paperwork=2'),
                3=>array(3, '3. Complete', null, 'WHERE paperwork=3'),
                4=>array(4, '4. Rework required', null, 'WHERE paperwork=4'),
                5=>array(5, '5. IQA', null, 'WHERE paperwork=5'),
                6=>array(6, '6. Overdue', null, 'WHERE paperwork=6'));
            $f = new DropDownViewFilter('filter_paperwork', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);*/

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
                0=>array(1, 'Learner, Due Date ASC', null, 'ORDER BY meeting_date'),
                1=>array(2, 'L03', null, 'ORDER BY l03'),
                2=>array(3, 'Leaner', null, 'ORDER BY learner_name'));

            $f = new DropDownViewFilter('order_by', $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            $format = "WHERE assessor_review.meeting_date >= '%s'";
            $f = new DateViewFilter('last_start_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE assessor_review.meeting_date <= '%s'";
            $f = new DateViewFilter('last_end_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

            $format = "WHERE if(assessor_review.due_date2 is not null and assessor_review.due_date2!='0000-00-00', assessor_review.due_date2, IF(assessor_review.due_date1 is not null and assessor_review.due_date1!='0000-00-00',assessor_review.due_date1, assessor_review.due_date)) >= '%s'";
            $f = new DateViewFilter('filter_from_marked_date', $format, '');
            $f->setDescriptionFormat("From Due Date: %s");
            $view->addFilter($f);

            $format = "WHERE if(assessor_review.due_date2 is not null and assessor_review.due_date2!='0000-00-00', assessor_review.due_date2, IF(assessor_review.due_date1 is not null and assessor_review.due_date1!='0000-00-00',assessor_review.due_date1, assessor_review.due_date)) <= '%s'";
            $f = new DateViewFilter('filter_to_marked_date', $format, '');
            $f->setDescriptionFormat("To Due Date: %s");
            $view->addFilter($f);

            /*$format = "WHERE assessment_plan_log.signed_off_date >= '%s'";
            $f = new DateViewFilter('filter_from_signed_off_date', $format, '');
            $f->setDescriptionFormat("From Signed off Date: %s");
            $view->addFilter($f);

            $format = "WHERE assessment_plan_log.signed_off_date <= '%s'";
            $f = new DateViewFilter('filter_to_signed_off_date', $format, '');
            $f->setDescriptionFormat("To Signed off Date: %s");
            $view->addFilter($f); */

            $options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE groups.assessor=',char(39),id,char(39),' OR tr.assessor=' , char(39),id, char(39)) FROM users WHERE id in (select assessor from tr) or id in (select assessor from groups) ORDER BY firstnames";
            $f = new DropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            $options = "SELECT username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE assessor_review.assessor=',id) FROM users WHERE type=3 ORDER BY firstnames";
            $f = new DropDownViewFilter('filter_person_reviewed', $options, null, true);
            $f->setDescriptionFormat("Person Reviewed: %s");
            $view->addFilter($f);

            $options = "SELECT username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE assessor_review.tr_id in (select id from tr where assessor in (select id from users where supervisor = \'',username, '\'))') FROM users where users.username IN (SELECT supervisor FROM users WHERE supervisor IS NOT NULL) ORDER BY firstnames";
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


    public function render(PDO $link)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center"><table id="tblLogs" class="table table-bordered" border="0" cellspacing="0" cellpadding="6">';
            echo <<<HEREDOC
	<thead>
	<tr>
		<th>Learner Reference</th>
		<th>Learner Name</th>
		<th>Employer</th>
		<th>Start Date</th>
		<th>Framework</th>
		<th>Review Forecast Date</th>
        <th>Revised Review Date</th>
        <th>Reason</th>
        <th>Auth By Manager</th>
		<th>Actual Date</th>
		<th>Assessor</th>
		<th>Assessor 2</th>
		<th>Assessor Comments</th>
        <th>Manager Attendance</th>
		<th>Emailed to learner</th>
		<th>Emailed to employer</th>
        <th>Last Email</th>
        <th>Last Email Date</th>
        <th>Status</th>
        <th>Attendance</th>
        <th>Punctuality</th>
        <th>Attitude</th>
        <th>Communication</th>
        <th>Enthusiasm</th>
        <th>Commitment</th>
        <th>Expected Reflective Hours</th>
        <th>Reflective Hours</th>
        <th>Remaining Reflective Hours</th>
        <th>Contracted Hours</th>
        <th>Review Template</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo '<tr>';
                echo '<td>' . HTML::cell($row['learner_reference']) . '</td>';
                //echo '<td>' . HTML::cell($row['uln']) . '</td>';
                echo '<td><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . '&amp;contract=' . $row['contract_id']. ' ><span style="color: black"> ' . HTML::cell($row['learner_name']) . '</span></a></td>';
                echo '<td>' . HTML::cell($row['employer']) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['start_date'])) . '</td>';
                echo '<td>' . HTML::cell($row['framework']) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['review_forecast_date'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['revised_review_date'])) . '</td>';
                echo '<td>' . HTML::cell($row['reason']) . '</td>';
                echo '<td>' . HTML::cell($row['auth_by_manager']) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['actual_date'])) . '</td>';
                echo '<td>' . HTML::cell($row['assessor']) . '</td>';
                echo '<td>' . HTML::cell($row['assessor_2']) . '</td>';
                echo '<td>' . HTML::cell($row['assessor_comments']) . '</td>';
                echo '<td>' . HTML::cell($row['manager_attendance']) . '</td>';
                echo '<td>' . HTML::cell($row['emailed_to_learner']) . '</td>';
                echo '<td>' . HTML::cell($row['emailed_to_employer']) . '</td>';
                echo '<td>' . HTML::cell($row['last_email']) . '</td>';
                echo '<td>' . HTML::cell($row['last_email_date']) . '</td>';
                echo '<td>' . HTML::cell($row['status']) . '</td>';
                echo '<td>' . HTML::cell($row['attendance']) . '</td>';
                echo '<td>' . HTML::cell($row['punctuality']) . '</td>';
                echo '<td>' . HTML::cell($row['attitude']) . '</td>';
                echo '<td>' . HTML::cell($row['communication']) . '</td>';
                echo '<td>' . HTML::cell($row['enthusiasm']) . '</td>';
                echo '<td>' . HTML::cell($row['commitment']) . '</td>';
                echo '<td>' . HTML::cell($row['expected_reflective_hours']) . '</td>';
                echo '<td>' . HTML::cell($row['reflective_hours']) . '</td>';
                echo '<td>' . HTML::cell($row['remaining_reflective_hours']) . '</td>';
                echo '<td>' . HTML::cell($row['contracted_hours']) . '</td>';
                echo '<td>' . HTML::cell($row['review_template']) . '</td>';
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