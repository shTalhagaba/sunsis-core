<?php
class ViewCoInvestment extends View
{
    public static function getInstance($link)
    {
        $key = 'view_' . __CLASS__ . '11';

        if (!isset($_SESSION[$key])) {
            $sql = <<<HEREDOC
SELECT
organisations.`legal_name` AS Employer
,tr.`firstnames` AS Firstname
,tr.`surname` AS Surname
,SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount"),' ',-1) AS TNP1
,SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount"),' ',-1) AS TNP2
,SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount"),' ',-1)+SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount"),' ',-1) AS TotalPrice
,ROUND((SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount"),' ',-1)+SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount"),' ',-1))*.95) AS ESFAContribution
,ROUND((SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount"),' ',-1)+SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount"),' ',-1))*.05) AS EmployerContribution
,ROUND((SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='PMR']/TBFinAmount"),' ',1)+SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='PMR']/TBFinAmount"),' ',-1))) AS PMRs
,ROUND(((SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount"),' ',-1)+SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount"),' ',-1))*.05)-(SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='PMR']/TBFinAmount"),' ',1)+SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='PMR']/TBFinAmount"),' ',-1))) AS Balance

#,EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord/TBFinType") AS FinType
#,extractvalue(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord/TBFinCode") as FinCode
#,EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount") AS FinAmount
#,EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord/TBFinDate") AS FinDate
#,substring_index(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount"),' ',-1) AS FinAmount
#,SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount"),' ',-1) AS FinAmount
#,SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount"),' ',-1)+SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount"),' ',-1) as Total
#,(SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount"),' ',-1)+SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount"),' ',-1))*.05 AS Employer
#,(SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='PMR']/TBFinAmount"),' ',1)+SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='PMR']/TBFinAmount"),' ',-1)) AS PMRs
#ilr.*
FROM tr
LEFT JOIN organisations ON organisations.id = tr.employer_id
INNER JOIN ilr ON ilr.tr_id = tr.id AND CONCAT(ilr.tr_id,ilr.submission,ilr.contract_id) = (SELECT CONCAT(tr_id,submission,contract_id) FROM ilr WHERE tr.id = ilr.tr_id ORDER BY contract_id DESC, submission DESC LIMIT 1)
WHERE tr.status_code = 1 and SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount"),' ',-1)+SUBSTRING_INDEX(EXTRACTVALUE(ilr,"/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount"),' ',-1)>0;
HEREDOC;
            $view = $_SESSION[$key] = new ViewCoInvestment();
            $view->setSQL($sql);

            $options = array(
                0 => array(0, 'Show all', null, null),
                1 => array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
                2 => array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
                3 => array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
                4 => array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
                5 => array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
                6 => array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7 => array(7, '7. Delete from ILR', null, 'WHERE tr.status_code = 7')
            );
            $f = new DropDownViewFilter('filter_record_status', $options, 1, false);
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
                0 => array(20, 20, null, null),
                1 => array(50, 50, null, null),
                2 => array(100, 100, null, null),
                3 => array(200, 200, null, null),
                4 => array(0, 'No limit', null, null)
            );
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


            if (!empty($_REQUEST['actual_start_date'])) {
                $format = "WHERE contact_date >= '%s'";
                $f = new DateViewFilter('actual_start_date', $format, $_REQUEST['actual_start_date']);
                $f->setDescriptionFormat("From: %s");
                $view->addFilter($f);
            }

            if (!empty($_REQUEST['actual_end_date'])) {
                $format = "WHERE contact_date <= '%s'";
                $f = new DateViewFilter('actual_end_date', $format, $_REQUEST['actual_end_date']);
                $f->setDescriptionFormat("To: %s");
                $view->addFilter($f);
            }
            echo "yes";

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

        if ($st) {
            echo $this->getViewNavigator();
            echo '<div align="center"><table id="tblLogs" class="table table-bordered" border="0" cellspacing="0" cellpadding="6">';
            echo <<<HEREDOC
	<thead class="bg-gray">
	<tr>
		<th>Employer</th>
		<th>Learner Name</th>
		<th>TNP 1</th>
		<th>TNP 2</th>
		<th>Total Price</th>
		<th>ESFA Contribution</th>
		<th>Employer Co-Investment</th>
		<th>Total PMRs</th>
		<th>Balance</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $tr_id = 0;
            while ($row = $st->fetch()) {
                $style = '';
                echo '<tr ' . $style . '>';
                echo '<td>' . HTML::cell($row['Employer']) . '</td>';
                echo '<td>' . HTML::cell($row['Firstname'] . ' ' . $row['Surname']) . '</td>';
                echo '<td>' . htmlentities('£', ENT_QUOTES, 'UTF-8') . HTML::cell($row['TNP1']) . '</td>';
                echo '<td>' . htmlentities('£', ENT_QUOTES, 'UTF-8') . HTML::cell($row['TNP2']) . '</td>';
                echo '<td>' . htmlentities('£', ENT_QUOTES, 'UTF-8') . HTML::cell($row['TotalPrice']) . '</td>';
                echo '<td>' . htmlentities('£', ENT_QUOTES, 'UTF-8') . HTML::cell($row['ESFAContribution']) . '</td>';
                echo '<td>' . htmlentities('£', ENT_QUOTES, 'UTF-8') . HTML::cell($row['EmployerContribution']) . '</td>';
                echo '<td>' . htmlentities('£', ENT_QUOTES, 'UTF-8') . HTML::cell($row['PMRs']) . '</td>';
                echo '<td>' . htmlentities('£', ENT_QUOTES, 'UTF-8') . HTML::cell($row['Balance']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>';
            echo $this->getViewNavigator();
        } else {
            throw new DatabaseException($link, $this->getSQL());
        }
    }
}
