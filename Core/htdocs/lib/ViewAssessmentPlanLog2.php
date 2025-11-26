<?php
class ViewAssessmentPlanLog2 extends View
{

    public static function getInstance(PDO $link, $id)
    {
        $where = '';

            $sql = <<<HEREDOC
SELECT assessment_plan_log.id AS apl_id, assessment_plan_log.*, s.*, s.due_date < CURDATE() AS expired,  (SELECT COUNT(*) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.`assessment_plan_id` = assessment_plan_log.id) AS submissions
,CASE s.portfolio_enhancement WHEN 1 THEN "Above & beyond" WHEN 2 THEN "Role overview" END AS portfolio_enhacement
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS s ON s.`assessment_plan_id` = assessment_plan_log.`id` AND s.id = (SELECT MAX(id) FROM assessment_plan_log_submissions AS s2 WHERE s2.`assessment_plan_id` = assessment_plan_log.id)
WHERE assessment_plan_log.mode IS NOT NULL AND assessment_plan_log.tr_id='$id'
$where
;
HEREDOC;
        // Create new view object

        $view = new ViewAssessmentPlanLog2();
        $view->setSQL($sql);


        return $view;
    }


    public function render(PDO $link, $tr_id)
    {

            $mode_ddl = DAO::getResultSet($link, "SELECT id, description, NULL FROM lookup_assessment_plan_log_mode WHERE framework_id IN (SELECT id FROM student_frameworks WHERE tr_id = '$tr_id') ORDER BY description");


        $status_ddl = array(
            array('1', 'Green'),
            array('2', 'Yellow'),
            array('3', 'Red')
        );

        $paperwork_ddl = array(
            array('1', 'In progress'),
            array('2', 'Awaiting marking'),
            array('3', 'Complete'),
            array('4', 'Rework required'),
            array('5', 'IQA'),
            array('6', 'Overdue')
        );

        $st = $link->query($this->getSQL());
        if($st)
        {
            echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';

            echo '<thead><tr>';
            echo '<th class="topRow">&nbsp;</th><th  class="topRow">Set Date</th><th  class="topRow">Due Date</th><th  class="topRow">Submission Date</th><th  class="topRow">Plan</th><th  class="topRow">Number of <br>Submissions</th><th  class="topRow">Assessor</th><th  class="topRow">Status</th><th  class="topRow">Comments</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                if($_SESSION['user']->type == User::TYPE_LEARNER)
                    echo '<tr>';
                else
                    echo HTML::viewrow_opening_tag('/do.php?_action=edit_assessment_plan_log2&apl_id=' . $row['apl_id'] . '&tr_id=' . $row['tr_id']);
                echo "<td valign='top' align='center' style='border-right-style: solid;'> <img src=\"/images/exam.png\" border=\"0\" alt=\"\" /></td>";
                echo '<td valign="top" align="left">' . HTML::cell(Date::toShort($row['set_date'])) . '</td>';
                echo '<td valign="top" align="left">' . HTML::cell(Date::toShort($row['due_date'])) . '</td>';
                echo '<td valign="top" align="left">' . HTML::cell(Date::toShort($row['submission_date'])) . '</td>';

                $actual_date = $row['submission_date'];
                if(!isset($prevActualDate))
                    $prevActualDate = $row['submission_date'];
                $diff = strtotime($actual_date) - strtotime($prevActualDate);
/*                if(isset($actual_date) AND $actual_date != "" AND $actual_date != "0000-00-00")
                {
                    $weeks = floor(floor($diff/(60*60*24)) / 7);
                    $days = floor($diff/(60*60*24)) % 7;
                    echo ($days != 0)? "<td>" . HTML::textbox("diff_1", $weeks . "w " . $days . "d ", "disabled  size='5'") . "</td>": "<td>" . HTML::textbox("diff_1", $weeks . "w", "disabled  size='5'") . "</td>";
                    $prevActualDate = $actual_date;
                }
                else
                {
                    $add_extra = false;
                    echo "<td>" . HTML::textbox("diff_1", "", "disabled  size='5'") . "</td>";
                }
*/
                echo '<td valign="top">'. HTML::select('mode', $mode_ddl, $row['mode'], true,false,false) .'</td>';

                $assessor_ddl = DAO::getResultset($link, "SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE web_access = 1 AND TYPE IN (3,7, 25)
UNION
SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE id = '{$row['assessor']}'
ORDER BY n;");
                echo "<td valign='top'>" . HTML::textbox("diff_1", $row['submissions'], "size='5'") . "</td>";
                echo '<td valign="top">'. HTML::select('assessor', $assessor_ddl, $row['assessor'], true,false,false) .'</td>';

                if($row['completion_date']!='')
                    $status = "3";
                elseif($row['iqa_status']=='2')
                    $status = "4";
                elseif($row['sent_iqa_date']!='' and $row['iqa_status']!='2')
                    $status = "5";
                elseif($row['submission_date']!='')
                    $status = "2";
                elseif($row['expired']=='1' and $row['submission_date']=='')
                    $status = "6";
                elseif($row['set_date']!='' and $row['expired']=='0' and $row['submissions']=='1')
                    $status = "1";
                else
                    $status = "4";


                echo '<td valign="top">'. HTML::select('status', $paperwork_ddl, $status, true,false,false) .'</td>';

                //echo '<td valign="top" align="left" style="font-size: 11px;">' . HTML::cell($row['portfolio_enhacement']) . '</td>';

                echo '<td valign="top" align="left" style="font-size: 11px;">' . HTML::cell($row['comments']) . '</td>';

                echo '</tr>';

            }

            echo '</tbody></table></div><p style="font-size: 11px;"><br>Total Rows: ' . $st->rowCount() . '</p>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

}
?>