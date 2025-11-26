<?php
class ViewAssessmentPlanLog extends View
{

    public static function getInstance(PDO $link, $id)
    {
        $where = '';

        // Create new view object
        $sql = <<<HEREDOC
select * From
assessment_plan_log
WHERE tr_id='$id' $where
;
HEREDOC;

        $view = new ViewAssessmentPlanLog();
        $view->setSQL($sql);


        return $view;
    }


    public function render(PDO $link)
    {
        $mode_ddl = array(
            array('1', 'Analysis'),
            array('2', 'Business Operation'),
            array('3', 'Communication'),
            array('4', 'Customer Service'),
            array('5', 'Data'),
            array('6', 'Digital Analytics'),
            array('7', 'Digital Tools'),
            array('8', 'Implementation'),
            array('9', 'Industry Developments & Practices'),
            array('10', 'Problem Solving'),
            array('11', 'Research'),
            array('12', 'Specialist Areas'),
            array('13', 'Technologies'),
            array('14', 'H&S'),
            array('15', 'Remote Infrastructure'),
            array('16', 'Workflow Management'),
            array('17', 'IT Security'),
            array('18', 'WEEE'),
            array('19', 'Performance'),
            array('20', 'Business'),
            array('21', 'Development Lifecycle'),
            array('22', 'Logic'),
            array('23', 'Quality'),
            array('24', 'Security'),
            array('25', 'Test'),
            array('26', 'User Interface'),
            array('27', 'Assess & Qualify Sales Leads'),
            array('28', 'Context & CPD'),
            array('29', 'Customer Experience'),
            array('30', 'Data Security'),
            array('31', 'Database & Campaign Management'),
            array('32', 'Sales Process'),
            array('33', 'Data manipulating & Linking'),
            array('34', 'Performance Queries'),
            array('35', 'Data Quality'),
            array('36', 'Presenting Data'),
            array('37', 'Investigation Techniques'),
            array('38', 'Data Modelling'),
            array('39', 'Stakeholder Analysis & Management'),
            array('40', 'Diagnostic Tools & Techniques'),
            array('41', 'Integrating Network Software'),
            array('42', 'Monitor Test & Adjust Networks'),
            array('43', 'Service Level Agreements'),
            array('44', 'Business Environment'),
            array('45', 'Operational Requirements'),
            array('46', 'Advise and Support Others'),
            array('47', 'Developing & Collecting Data'),
            array('48', 'Presenting Test Results'),
            array('49', 'Test Cases'),
            array('50', 'Legislation'),
            array('51', 'Technical'),
            array('52', 'Data Analysis Security & Policies'),
            array('53', 'Statistical Analysis'),
            array('54', 'Applications'),
            array('55', 'Data Architecture'),
            array('56', 'Business Process Modelling'),
            array('57', 'Gap Analysis'),
            array('58', 'Business Impact Assessment'),
            array('59', 'Documenting'),
            array('60', 'Interpret Written Requirements and Tech Specs'),
            array('61', 'Network Installation'),
            array('62', 'Troubleshooting & Repair'),
            array('63', 'Deployment'),
            array('64', 'Testing'),
            array('65', 'Conduct Software Testing'),
            array('66', 'Implementing Software Testing'),
            array('67', 'Results vs Expectations'),
            array('68', 'Test Outcomes'),
            array('69', 'Project Management'),
            array('70', 'Data Migration'),
            array('71', 'Collect & Compile Data'),
            array('72', 'Analytical Techniques'),
            array('73', 'Reporting Data'),
            array('74', 'Business Analysis'),
            array('75', 'Requirements Engineering & Management'),
            array('76', 'Acceptance Testing'),
            array('77', 'Design Networks from a Specification'),
            array('78', 'Effective Business Operation'),
            array('79', 'Logging & Responding to Calls'),
            array('80', 'Network Performance'),
            array('81', 'Upgrading Network Systems'),
            array('82', 'Design'),
            array('83', 'User Interface'),
            array('84', 'Design Test Strategies'),
            array('85', 'Legislation & Standards'),
            array('86', 'Software Requirements'),
            array('87', 'Service Level Agreements '),
            array('88', 'Test Plans'),
            array('89', 'Test Outcomes'),
			array('90', 'Employer Reference')
        );

        $mode_ddl = DAO::getResultSet($link, "SELECT id, description, null FROM lookup_assessment_plan_log_mode ORDER BY description");

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
            echo '<th class="topRow">&nbsp;</th><th  class="topRow">Due Date</th><th  class="topRow">Actual Date</th><th  class="topRow">Gap</th><th  class="topRow">Mode</th><th  class="topRow">Assessor</th><th  class="topRow">GYR</th><th  class="topRow">Paperwork</th><th  class="topRow">Comments</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                if($_SESSION['user']->type == User::TYPE_LEARNER)
                    echo '<tr>';
                else
                    echo HTML::viewrow_opening_tag('/do.php?_action=edit_assessment_plan_log&apl_id=' . $row['id'] . '&tr_id=' . $row['tr_id']);
                echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/exam.png\" border=\"0\" alt=\"\" /></td>";
                echo '<td align="left">' . HTML::cell(Date::toShort($row['due_date'])) . '</td>';
                echo '<td align="left">' . HTML::cell(Date::toShort($row['actual_date'])) . '</td>';

                $actual_date = $row['actual_date'];
                if(!isset($prevActualDate))
                    $prevActualDate = $row['actual_date'];
                $diff = strtotime($actual_date) - strtotime($prevActualDate);
                if(isset($actual_date) AND $actual_date != "" AND $actual_date != "0000-00-00")
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
                echo '<td>'. HTML::select('mode', $mode_ddl, $row['mode'], true,false,false) .'</td>';

                $assessor_ddl = DAO::getResultset($link, "SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE web_access = 1 AND TYPE IN (3,7, 25)
UNION
SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE id = '{$row['assessor']}'
ORDER BY n;");

                echo '<td>'. HTML::select('assessor', $assessor_ddl, $row['assessor'], true,false,false) .'</td>';
                echo '<td>'. HTML::select('status', $status_ddl, $row['traffic'], true,false,false) .'</td>';
                echo '<td>'. HTML::select('paperwork', $paperwork_ddl, $row['paperwork'], true,false,false) .'</td>';
                echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['comments']) . '</td>';

                echo '</tr>';

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