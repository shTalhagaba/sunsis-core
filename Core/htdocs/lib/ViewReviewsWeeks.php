<?php
class ViewReviewsWeeks extends View
{
    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__.'11';

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
SELECT
CONCAT(tr.firstnames, ' ', tr.surname) AS learner
,CONCAT(users.firstnames, ' ', users.surname) AS learning_mentor
,(SELECT ROUND(DATEDIFF(meeting_date,tr.start_date)/7) FROM assessor_review WHERE meeting_date!="0000-00-00" AND template_review = 1 AND assessor_review.id = (SELECT id FROM assessor_review WHERE assessor_review.tr_id = tr.id ORDER BY id LIMIT 1)) AS introduction_review
,(SELECT ROUND(DATEDIFF(RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",2),10),RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",1),10))/7) FROM assessor_review WHERE meeting_date!="0000-00-00" AND assessor_review.tr_id = tr.id ORDER BY id) AS review_1
,(SELECT ROUND(DATEDIFF(RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",3),10),RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",2),10))/7) FROM assessor_review WHERE meeting_date!="0000-00-00" AND assessor_review.tr_id = tr.id ORDER BY id) AS review_2
,(SELECT ROUND(DATEDIFF(RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",4),10),RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",3),10))/7) FROM assessor_review WHERE meeting_date!="0000-00-00" AND assessor_review.tr_id = tr.id ORDER BY id) AS review_3
,(SELECT ROUND(DATEDIFF(RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",5),10),RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",4),10))/7) FROM assessor_review WHERE meeting_date!="0000-00-00" AND assessor_review.tr_id = tr.id ORDER BY id) AS review_4
,(SELECT ROUND(DATEDIFF(RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",6),10),RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",5),10))/7) FROM assessor_review WHERE meeting_date!="0000-00-00" AND assessor_review.tr_id = tr.id ORDER BY id) AS review_4
,(SELECT ROUND(DATEDIFF(RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",7),10),RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",6),10))/7) FROM assessor_review WHERE meeting_date!="0000-00-00" AND assessor_review.tr_id = tr.id ORDER BY id) AS review_5
,(SELECT ROUND(DATEDIFF(RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",8),10),RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",7),10))/7) FROM assessor_review WHERE meeting_date!="0000-00-00" AND assessor_review.tr_id = tr.id ORDER BY id) AS review_6
,(SELECT ROUND(DATEDIFF(RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",9),10),RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",8),10))/7) FROM assessor_review WHERE meeting_date!="0000-00-00" AND assessor_review.tr_id = tr.id ORDER BY id) AS review_7
,(SELECT ROUND(DATEDIFF(RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",10),10),RIGHT(SUBSTRING_INDEX(GROUP_CONCAT(meeting_date),",",9),10))/7) FROM assessor_review WHERE meeting_date!="0000-00-00" AND assessor_review.tr_id = tr.id ORDER BY id) AS review_8
FROM tr
LEFT JOIN users ON users.id = tr.assessor
LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
WHERE status_code = 1;
HEREDOC;
            $view = $_SESSION[$key] = new ViewReviewsWeeks();
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


             /*$options = "SELECT username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE additional_support.tr_id in (select id from tr where assessor in (select id from users where supervisor = \'',username, '\'))') FROM users where users.username IN (SELECT supervisor FROM users WHERE supervisor IS NOT NULL) ORDER BY firstnames";
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
		<th>Learner</th>
		<th>Learning Mentor</th>
		<th>Introduction Review</th>
		<th>Review 1</th>
		<th>Review 2</th>
		<th>Review 3</th>
		<th>Review 4</th>
		<th>Review 5</th>
		<th>Review 6</th>
		<th>Review 7</th>
		<th>Review 8</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $tr_id = 0;
            while($row = $st->fetch())
            {
                $style = '';
                echo '<tr ' . $style . '>';
                echo '<td>' . HTML::cell($row['learner']) . '</td>';
                echo '<td>' . HTML::cell($row['learning_mentor']) . '</td>';
                echo '<td>' . HTML::cell($row['introduction_review']) . '</td>';
                echo '<td>' . HTML::cell($row['review_1']) . '</td>';
                echo '<td>' . HTML::cell($row['review_2']) . '</td>';
                echo '<td>' . HTML::cell($row['review_3']) . '</td>';
                echo '<td>' . HTML::cell($row['review_4']) . '</td>';
                echo '<td>' . HTML::cell($row['review_5']) . '</td>';
                echo '<td>' . HTML::cell($row['review_6']) . '</td>';
                echo '<td>' . HTML::cell($row['review_7']) . '</td>';
                echo '<td>' . HTML::cell($row['review_8']) . '</td>';
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