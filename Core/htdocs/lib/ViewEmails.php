<?php
class ViewEmails extends View
{
    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__.'11';

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
SELECT
tr.id as tr_id
,organisations.legal_name as employer
,tr.`firstnames` AS firstnames
,tr.`surname` AS surname
,description AS email_trigger
,`date` AS email_date
,form_type AS email_type
,meeting_date AS review_date
,assessment_plan_log_submissions.set_date AS assessment_plan_set_date
FROM forms_audit
LEFT JOIN assessor_review ON assessor_review.id = forms_audit.form_id AND forms_audit.form_type = "Review"
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = forms_audit.form_id AND forms_audit.form_type = "Plan"
LEFT JOIN assessment_plan_log_submissions ON assessment_plan_log_submissions.`assessment_plan_id` = assessment_plan_log.id
INNER JOIN tr ON tr.id = IF(forms_audit.form_type = "Review", assessor_review.`tr_id`, assessment_plan_log.`tr_id`) AND tr.`status_code` = 1
inner join organisations on organisations.id = tr.employer_id
order by date desc;

HEREDOC;
            $view = $_SESSION[$key] = new ViewEmails();
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


            $format = "WHERE forms_audit.date >= '%s'";
            $f = new DateViewFilter('actual_start_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE forms_audit.date <= '%s'";
            $f = new DateViewFilter('actual_end_date', $format, '');
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
		<th>Employer</th>
		<th>Firstnames</th>
		<th>Surname</th>
		<th>Email Trigger</th>
		<th>Email Date</th>
		<th>Email Type</th>
		<th>Review Date</th>
		<th>Assessment Plan Set Date</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $tr_id = 0;
            while($row = $st->fetch())
            {
                $style = '';
                echo '<tr ' . $style . '>';
                echo '<td>' . HTML::cell($row['employer']) . '</td>';
                echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
                echo '<td><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . ' ><span style="color: black">' . $row['surname'] . '</span></a></td>';
                //echo '<td>' . HTML::cell($row['surname']) . '</td>';
                echo '<td>' . HTML::cell($row['email_trigger']) . '</td>';
                echo '<td>' . HTML::cell($row['email_date']) . '</td>';
                echo '<td>' . HTML::cell($row['email_type']) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['review_date'])) . '</td>';
                echo '<td>' . HTML::cell(Date::toShort($row['assessment_plan_set_date'])) . '</td>';
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