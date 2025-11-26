<?php
class ViewToleranceReport extends View
{
    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__.'11';

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
SELECT DISTINCT 
tr.l03 as learner_reference
,concat(tr.firstnames, ' ', tr.surname) as learner_name
,id as tr_id
,start_date as start_date
,contract_id
,courses_tr.course_id
FROM
    tr
LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
where status_code = 1;
HEREDOC;
            $view = $_SESSION[$key] = new ViewToleranceReport();
            $view->setSQL($sql);

            $f = new TextboxViewFilter('filter_tr_ids', "WHERE tr.id in (%s)", null);
            $f->setDescriptionFormat("TR IDs: %s");
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

            $options = "SELECT username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE additional_support.tr_id in (select id from tr where assessor in (select id from users where supervisor = \'',username, '\'))') FROM users where users.username IN (SELECT supervisor FROM users WHERE supervisor IS NOT NULL) ORDER BY firstnames";
            $f = new DropDownViewFilter('filter_manager', $options, null, true);
            $f->setDescriptionFormat("Manager: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. 0 to 7 days', null,null),
                2=>array(2, '2. 8 to 28 days', null, null),
                3=>array(3, '3. 29 to 59 days', null,null),
                4=>array(4, '4. 60+ days', null,null));
            $f = new DropDownViewFilter('filter_band', $options, 0, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        $band = $this->getFilterValue('filter_band');
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
		<th>Days behind</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $tr_id = 0;
            while($row = $st->fetch())
            {
                $tr_id = $row['tr_id'];
                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $tr_id);
                $course_id = $row['course_id'];
                $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '$course_id' AND '$current_training_month' BETWEEN min_month AND max_month");
                $start_date = $row['start_date'];
                if($month_row_id!='')
                {
                    $days_behind = DAO::getSingleValue($link, "SELECT DATEDIFF(CURDATE(),DATE_ADD('$start_date',INTERVAL max_month WEEK)) FROM ap_percentage WHERE course_id = '$course_id' AND id < $month_row_id ORDER BY id desc LIMIT 1");
                }
                else
                {
                    $days_behind = DAO::getSingleValue($link, "SELECT DATEDIFF(CURDATE(),DATE_ADD('$start_date',INTERVAL max_month WEEK)) FROM ap_percentage WHERE course_id = '$course_id' ORDER BY id desc LIMIT 1");
                }

                $style = '';
                echo '<tr ' . $style . '>';
                echo '<td>' . HTML::cell($row['learner_reference']) . '</td>';
                echo '<td><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . '&amp;contract=' . $row['contract_id']. ' ><span style="color: black"> ' . HTML::cell($row['learner_name']) . '</span></a></td>';
                echo '<td>' . HTML::cell($days_behind) . '</td>';
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