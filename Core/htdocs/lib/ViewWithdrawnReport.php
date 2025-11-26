<?php
class ViewWithdrawnReport extends View
{

    public static function getInstance()
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            if($_SESSION['user']->isAdmin())
            {
                $where = '';
            }
            elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
            {
                $emp = $_SESSION['user']->employer_id;
                $username = $_SESSION['user']->username;
                $where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp')" ;
            }
            else
            {
                $where = ' where tr.employer_id = ' . $_SESSION['user']->employer_id;
            }
            $sql = <<<HEREDOC
SELECT
	withdrawn_report2.*
FROM
	withdrawn_report2
    LEFT JOIN tr ON withdrawn_report2.`tr_id` = tr.id
$where
ORDER BY
	withdrawn_report2.status, withdrawn_report2.l03
;
HEREDOC;

            $view = $_SESSION[$key] = new ViewWithdrawnReport();
            $view->setSQL($sql);

            $f = new TextboxViewFilter('filter_surname', "WHERE withdrawn_report2.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_firstname', "WHERE withdrawn_report2.firstname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Firstname: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_uln', "WHERE withdrawn_report2.uln LIKE '%s%%'", null);
            $f->setDescriptionFormat("ULN: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_l03', "WHERE withdrawn_report2.l03 LIKE '%s%%'", null);
            $f->setDescriptionFormat("L03: %s");
            $view->addFilter($f);

            $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE withdrawn_report2.l03 IN (SELECT l03 FROM tr WHERE tr.assessor=',char(39),id,char(39), ')') FROM users where type=3 order by users.firstnames";
            $f = new DropDownViewFilter('filter_tr_assessor', $options, null, true);
            $f->setDescriptionFormat("Training Record Assessor: %s");
            $view->addFilter($f);

            $options = <<<SQL
SELECT users.id, CONCAT(users.firstnames, ' ', users.surname), NULL,
CONCAT('WHERE withdrawn_report2.l03 IN (SELECT tr.l03 FROM group_members INNER JOIN groups ON group_members.`groups_id` = groups.`id`
INNER JOIN tr ON group_members.`tr_id` = tr.id WHERE groups.`assessor` =',users.id, ')') FROM users WHERE users.`type` = 3 ORDER BY users.`firstnames`;
SQL;
            $f = new DropDownViewFilter('filter_group_assessor', $options, null, true);
            $f->setDescriptionFormat("Group Assessor: %s");
            $view->addFilter($f);

            $options = <<<SQL
SELECT lookup_programme_type.code, description, NULL,
CONCAT('WHERE withdrawn_report2.l03 IN (SELECT tr.l03 FROM tr INNER JOIN courses_tr ON tr.id = courses_tr.`tr_id` INNER JOIN courses ON courses.id = courses_tr.`course_id`
WHERE courses.`programme_type` =',lookup_programme_type.code, ')') FROM lookup_programme_type ORDER BY lookup_programme_type.`description`;
SQL;

            $f = new DropDownViewFilter('filter_programme_type', $options, null, true);
            $f->setDescriptionFormat("Programme Type: %s");
            $view->addFilter($f);

        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            $arrayForBothHandSides = array();

            echo '<div align="center"><table id="dataMatrix" class="resultset" border="0" cellspacing="0" cellpadding="2">';
            echo '<thead><tr><th class="topRow" colspan="7">Learner Information</th><th class="topRow"></th><th class="topRow" colspan="6">Withdrawn</th><th class="topRow"></th><th class="topRow" bgcolor="black"></th><th class="topRow" colspan="8">Restart</th></tr>';
            echo '<tr><th class="bottomRow">L03</th><th class="bottomRow">ULN</th><th class="bottomRow">Surname</th><th class="bottomRow">Forenames</th><th class="bottomRow">Framework Title</th><th class="bottomRow">Assessor</th><th class="bottomRow">Tutor</th>';
            echo '<th class="bottomRow" bgcolor="black"></th>';
            echo '<th class="bottomRow">Contract</th><th class="bottomRow">Start Date</th><th class="bottomRow">Planned End Date</th><th class="bottomRow">Actual End Date</th><th class="bottomRow">Completion Status</th><th class="bottomRow">Outcome</th>';
            echo '<th class="bottomRow" bgcolor="black"></th>';
            echo '<th class="bottomRow">Restart</th><th class="bottomRow">Contract</th><th class="bottomRow">Original Start Date</th><th class="bottomRow">Start Date</th><th class="bottomRow">Planned End Date</th><th class="bottomRow">Actual End Date</th><th class="bottomRow">Proportion of Funding Remaining</th><th class="bottomRow">Completion Status</th><th class="bottomRow">Outcome</th></tr></thead>';

            echo '<tbody>';
            while($row = $st->fetch())
            {
                if($row['status'] == 'B' && $row['restart'] == 0)
                    $arrayForBothHandSides[$row['l03']]['left'] = $row;
                elseif($row['status'] == 'B' && $row['restart'] == 1)
                    $arrayForBothHandSides[$row['l03']]['right'] = $row;
                echo '<tr>';

                if($row['status'] == 'L')
                {
                    echo '<td align="left">' . HTML::cell($row['l03']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['uln']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['surname']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['firstname']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['framework_title']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['assessor']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['tutor']) . '</td>';
                    echo '<td align="left" bgcolor="black"></td>';
                    echo '<td align="center">' . HTML::cell($row['contract']) . '</td>';
                    echo '<td align="center">' . HTML::cell(Date::toShort($row['start_date'])) . '</td>';
                    echo '<td align="center">' . HTML::cell(Date::toShort($row['planned_end_date'])) . '</td>';
                    echo '<td align="center">' . HTML::cell(Date::toShort($row['actual_end_date'])) . '</td>';
                    echo '<td align="center">' . HTML::cell($row['comp_status']) . '</td>';
                    echo '<td align="center">' . HTML::cell($row['outcome']) . '</td>';
                    echo '<td align="left" bgcolor="black"></td>';
                    echo '<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                }
                elseif($row['status'] == 'R')
                {
                    echo '<td align="left">' . HTML::cell($row['l03']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['uln']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['surname']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['firstname']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['framework_title']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['assessor']) . '</td>';
                    echo '<td align="left">' . HTML::cell($row['tutor']) . '</td>';
                    echo '<td align="left" bgcolor="black"></td>';
                    echo '<td></td><td></td><td></td><td></td><td></td><td></td>';
                    echo '<td align="left" bgcolor="black"></td>';
                    echo $row['restart'] == 1 ? '<td align="center">' . HTML::cell('Yes') . '</td>': '<td align="center">' . HTML::cell('No') . '</td>';
                    echo '<td align="center">' . HTML::cell($row['contract']) . '</td>';
                    echo '<td align="center">' . HTML::cell(Date::toShort($row['original_start_date'])) . '</td>';
                    echo '<td align="center">' . HTML::cell(Date::toShort($row['start_date'])) . '</td>';
                    echo '<td align="center">' . HTML::cell(Date::toShort($row['planned_end_date'])) . '</td>';
                    echo '<td align="center">' . HTML::cell(Date::toShort($row['actual_end_date'])) . '</td>';
                    echo '<td align="center">' . HTML::cell($row['fund_remain']) . '</td>';
                    echo '<td align="center">' . HTML::cell($row['comp_status']) . '</td>';
                    echo '<td align="center">' . HTML::cell($row['outcome']) . '</td>';
                }
                echo '</tr>';
            }

            if(isset($arrayForBothHandSides) && count($arrayForBothHandSides) > 0)
            {
                foreach($arrayForBothHandSides AS $row)
                {
                    $left_side = $row['left'];
                    $right_side = $row['right'];
                    echo '<tr>';
                    echo '<td align="left">' . HTML::cell($left_side['l03']) . '</td>';
                    echo '<td align="left">' . HTML::cell($left_side['uln']) . '</td>';
                    echo '<td align="left">' . HTML::cell($left_side['surname']) . '</td>';
                    echo '<td align="left">' . HTML::cell($left_side['firstname']) . '</td>';
                    echo '<td align="left">' . HTML::cell($left_side['framework_title']) . '</td>';
                    echo '<td align="left">' . HTML::cell($left_side['assessor']) . '</td>';
                    echo '<td align="left">' . HTML::cell($left_side['tutor']) . '</td>';
                    echo '<td align="left" bgcolor="black"></td>';
                    echo '<td align="center">' . HTML::cell($left_side['contract']) . '</td>';
                    echo '<td align="center">' . HTML::cell(Date::toShort($left_side['start_date'])) . '</td>';
                    echo '<td align="center">' . HTML::cell(Date::toShort($left_side['planned_end_date'])) . '</td>';
                    echo '<td align="center">' . HTML::cell(Date::toShort($left_side['actual_end_date'])) . '</td>';
                    echo '<td align="center">' . HTML::cell($left_side['comp_status']) . '</td>';
                    echo '<td align="center">' . HTML::cell($left_side['outcome']) . '</td>';

                    echo '<td align="left" bgcolor="black"></td>';

                    echo $right_side['restart'] == 1 ? '<td align="center">' . HTML::cell('Yes') . '</td>': '<td align="center">' . HTML::cell('No') . '</td>';
                    echo '<td align="left">' . HTML::cell($right_side['contract']) . '</td>';
                    echo '<td align="left">' . HTML::cell(Date::toShort($right_side['original_start_date'])) . '</td>';
                    echo '<td align="left">' . HTML::cell(Date::toShort($right_side['start_date'])) . '</td>';
                    echo '<td align="left">' . HTML::cell(Date::toShort($right_side['planned_end_date'])) . '</td>';
                    echo '<td align="left">' . HTML::cell(Date::toShort($right_side['actual_end_date'])) . '</td>';
                    echo '<td align="center">' . HTML::cell($right_side['fund_remain']) . '</td>';
                    echo '<td align="center">' . HTML::cell($right_side['comp_status']) . '</td>';
                    echo '<td align="center">' . HTML::cell($right_side['outcome']) . '</td>';

                    echo '</tr>';
                }
            }
            echo '</tbody></table></div>';


        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }
}
?>