<?php
class ViewForms extends View
{
    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==15 || $_SESSION['user']->type==7)
            {
                $where = '';
            }
            elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==13 || $_SESSION['user']->type==14)
            {
                $emp = $_SESSION['user']->employer_id;
                $username = $_SESSION['user']->username;
                $where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp' or users.who_created = '$username' or users.who_created in (select username from users where type = 8 and employer_id = '$emp'))" ;
            }
            elseif($_SESSION['user']->type==3)
            {
                $id = $_SESSION['user']->id;
                $where = ' where (groups.assessor = '. '"' . $id . '" or tr.assessor="' . $id . '")';
            }

            $sql = <<<SQL
SELECT
tr.id AS tr_id
, tr.contract_id
, tr.l03 AS learner_ref
, tr.surname AS surname
, tr.firstnames AS forenames
,IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor
,organisations.legal_name AS employer
,courses.review_programme_title AS programme_title
,assessor_review.due_date AS form_due
,assessor_review.meeting_date AS actual_date
,assessor_review_forms_assessor4.next_contact AS next_review_date
,IF(due_date>CURDATE(),"Future", IF(signature_assessor_font IS NULL, "Overdue",IF(signature_learner_font IS NULL,"Assessor Signed",IF(signature_employer_font IS NULL, "Learner Signed","Completed")))) AS `status`
,(SELECT `date` FROM forms_audit WHERE forms_audit.form_id = assessor_review_forms_assessor4.review_id AND description = "Review Form Emailed to Learner" ORDER BY `date` DESC LIMIT 0,1) AS email_learner_on
,(SELECT `date` FROM forms_audit WHERE forms_audit.form_id = assessor_review_forms_assessor4.review_id AND description = "Review Form Emailed to Employer" ORDER BY `date` DESC LIMIT 0,1) AS email_employer_on
,assessor_review.assessor_comments as assessor_comments
FROM assessor_review
LEFT JOIN assessor_review_forms_assessor4 ON assessor_review.id = assessor_review_forms_assessor4.review_id
LEFT JOIN assessor_review_forms_learner ON assessor_review.id = assessor_review_forms_learner.review_id
LEFT JOIN assessor_review_forms_employer ON assessor_review.id = assessor_review_forms_employer.review_id
LEFT JOIN tr ON tr.id = assessor_review.tr_id
LEFT JOIN users AS assessors ON assessors.id = tr.assessor
LEFT JOIN group_members ON group_members.tr_id = tr.id
LEFT JOIN groups ON groups.id = group_members.groups_id
LEFT JOIN users AS assessorsng ON assessorsng.id = groups.assessor
LEFT JOIN organisations ON organisations.id = tr.employer_id
LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
LEFT JOIN courses ON courses.id = courses_tr.course_id
	$where
SQL;

            // Create new view object

            $view = $_SESSION[$key] = new ViewForms();
            $view->setSQL($sql);
            $parent_org = $_SESSION['user']->employer_id;

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
                2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
                3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
                4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
                5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
                6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7=>array(7, '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
            $f = new DropDownViewFilter('filter_comp_status', $options, 1, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            // Employer Filter
            if($_SESSION['user']->type==8)
                $options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE (organisation_type like '%2%' or organisation_type like '%6%') and organisations.parent_org=$parent_org order by legal_name";
            else
                $options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE organisation_type like '%2%' or organisation_type like '%6%' order by legal_name";
            $f = new DropDownViewFilter('filter_employer', $options, null, true);
            $f->setDescriptionFormat("Employer/ School: %s");
            $view->addFilter($f);

            // Assessor Filter
            $options = "SELECT distinct learner_assessor, learner_assessor, null, CONCAT('WHERE learner_assessor=',char(39),learner_assessor,char(39)) FROM assessor_review_forms order by learner_assessor";
            $f = new DropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            // Programme review title
            $options = "SELECT distinct learner_programme, learner_programme, null, CONCAT('WHERE learner_programme=',char(39),learner_programme,char(39)) FROM assessor_review_forms order by learner_programme asc ";
            $f = new DropDownViewFilter('filter_programme_type', $options, NULL, true);
            $f->setDescriptionFormat("Programme: %s");
            $view->addFilter($f);

            $dateInfo = getdate();
            $weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
            $timestamp = time()  - ((60*60*24) * $weekday);

            // Start Date Filter
            $format = "WHERE due_date >= '%s'";
            $f = new DateViewFilter('next_contact_start', $format, '');
            $f->setDescriptionFormat("From next contact: %s");
            $view->addFilter($f);

            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));

            $format = "WHERE due_date <= '%s'";
            $f = new DateViewFilter('next_contact_end', $format, '');
            $f->setDescriptionFormat("To next contact: %s");
            $view->addFilter($f);

            // Add view filters
            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. Overdue reviews', null, 'WHERE signature_assessor_font is null'),
                2=>array(2, '2. Reviews awaiting sign off by learner', null, 'WHERE signature_assessor_font is not null and signature_learner_font is null'),
                3=>array(3, '3. Reviews awaiting sign off by employer', null, 'WHERE signature_learner_font is not null and signature_employer_font is null'),
                4=>array(4, '4. Completed', null, 'WHERE signature_employer_font is not null'));
            $f = new DropDownViewFilter('filter_record_status', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
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
            echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th class="topRow">&nbsp;</th>';
            echo '<th class="topRow">Learner Ref</th>';
            echo '<th class="topRow">Surname</th>';
            echo '<th class="topRow">Forenames</th>';
            echo '<th class="topRow">Assessor</th>';
            echo '<th class="topRow">Employer</th>';
            echo '<th class="topRow">Programme Title</th>';
            echo '<th class="topRow">Form Due</th>';
            echo '<th class="topRow">Actual Date</th>';
            echo '<th class="topRow">Next Review Date</th>';
            echo '<th class="topRow">Email Learner On</th>';
            echo '<th class="topRow">Email Employer On</th>';
            echo '<th class="topRow">Status</th>';
            echo '<th class="topRow">Assessor Comments</th>';
            echo '</tr></thead>';
            echo '<tbody>';

            while($row = $st->fetch(PDO::FETCH_ASSOC))
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id']);
                echo "<td>&nbsp;</td>";
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell($row['learner_ref']) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell($row['surname']) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell($row['forenames']) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell($row['assessor']) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell($row['employer']) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell($row['programme_title']) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell(Date::toShort($row['form_due'])) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell(Date::toShort($row['actual_date'])) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell(Date::toShort($row['next_review_date'])) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell($row['email_learner_on']) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell($row['email_employer_on']) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell($row['status']) . '</td>';
                echo "<td align=\"left\" style=\"font-size:100%;\">" . HTML::cell($row['assessor_comments']) . '</td>';
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