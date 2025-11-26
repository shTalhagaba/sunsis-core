<?php
class view_epa_status_report implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->add($link, "do.php?_action=view_epa_status_report", "Course Status Report V2");
        $view = VoltView::getViewFromSession('view_epa_status_report', 'view_epa_status_report'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['view_epa_status_report'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        if(isset($_REQUEST['subaction']) && $_REQUEST['subaction'] = 'export')
        {
            $this->exportToCSV($link, $view);
            exit;
        }

        include 'tpl_view_epa_status_report.php';
    }

    private function buildView(PDO $link)
    {
        $sql = new SQLStatement("
    SELECT DISTINCT
        tr.id AS tr_id,
        op_trackers.`title` AS programme,
        tr.`firstnames`,
        tr.`surname`,
        DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS planned_end_date,
        (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
        (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator
    FROM
        tr 
        INNER JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
        INNER JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
        INNER JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
        INNER JOIN tr_operations ON tr_operations.tr_id = tr.id
    
;		
		");

        $view = new VoltView('view_epa_status_report', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("First Name: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show All', null, null),
            1=>array(1, 'Continuing', null, 'WHERE tr.status_code="1"'),
            2=>array(2, 'Completed', null, 'WHERE tr.status_code="2"'),
            3=>array(3, 'Withdrawn', null, 'WHERE tr.status_code="3"'),
            4=>array(4, 'Temp. Withdrawn', null, 'WHERE tr.status_code=="6"'),
        );
        $f = new VoltDropDownViewFilter('filter_tr_status', $options, null, false);
        $f->setDescriptionFormat("Training Status: %s");
        $view->addFilter($f);

        $options = "SELECT id, title, NULL, CONCAT('WHERE op_trackers.id=',CHAR(39),id,CHAR(39)) FROM op_trackers ORDER BY title";
        $f = new VoltDropDownViewFilter('filter_tracker', $options, null, true);
        $f->setDescriptionFormat("Unit Ref: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(20,20,null,null),
            1=>array(50,50,null,null),
            2=>array(100,100,null,null),
            3=>array(200,200,null,null),
            4=>array(300,300,null,null),
            5=>array(400,400,null,null),
            6=>array(500,500,null,null),
            7=>array(0, 'No limit', null, null));
        $f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(1, 'Programme, Learner Firstname', null, 'ORDER BY op_trackers.`title`, tr.firstnames'),
            1=>array(2, 'Programme, Learner Surname', null, 'ORDER BY op_trackers.`title`, tr.surname'),
            2=>array(3, 'Programme, Planned End Date (Desc)', null, 'ORDER BY op_trackers.`title`, tr.`target_date` DESC'));
        $f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
        $f->setDescriptionFormat("Sort by: %s");
        $view->addFilter($f);

        return $view;
    }

    private function renderView(PDO $link, VoltView $view)
    {
        //pr($view->getSQLStatement()->__toString());
        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            $epa_task_status = InductionHelper::getListOpTaskStatus();
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<div align="center" ><table id="tblLearners" class="table table-striped table-bordered text-center" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr>';
            echo '<th>Programme</th><th>Firstnames</th><th>Surname</th><th>Planned End Date</th><th>Assessor</th><th>Coordinator</th>';
            echo '<th>EPA ready</th><th>Status 1</th><th>Employer reference</th><th>Status 2</th><th>Summative portfolio</th><th>Status 3</th>';
            echo '<th>IQA complete</th><th>Status 4</th><th>Passed to awarding body</th><th>Status 5</th><th>Synoptic project</th><th>Status 6</th>';
            echo '<th>Interview</th><th>Status 7</th><th>EPA result</th><th>Status 8</th><th>EPA result Actual Date</th><th>EPA Result Type</th><th>Project</th><th>Status 9</th>';
            echo '<th>Gateway Forecast</th><th>Summative Portfolio</th><th>EOL</th><th>EOL Actual Date</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $task_details = [];
                $tr_id = $row['tr_id'];
                echo '<tr>';
                echo '<td>' . $row['programme'] . '</td>';
                echo '<td>' . $row['firstnames'] . '</td>';
                echo '<td>' . $row['surname'] . '</td>';
                echo '<td>' . $row['planned_end_date'] . '</td>';
                echo '<td>' . $row['assessor'] . '</td>';
                echo '<td>' . $row['coordinator'] . '</td>';

                $epa_details_sql = <<<EPA_SQL
SELECT 
    op_epa.*
FROM 
    tr
    LEFT JOIN (SELECT m1.*
        FROM op_epa m1 LEFT JOIN op_epa m2
        ON (m1.tr_id = m2.tr_id AND m1.task = m2.task AND m1.id < m2.id )
        WHERE m2.id IS NULL ) AS op_epa ON tr.`id` = op_epa.tr_id 
WHERE tr.id = '$tr_id'
;               
EPA_SQL;
                $op_details = DAO::getResultset($link, $epa_details_sql, DAO::FETCH_ASSOC);
                foreach($op_details AS $op_detail)
                {
                    $task_details[$op_detail['task']] = (object)$op_detail;
                }

                for($i = 1; $i <= 8; $i++)
                {
                    if(isset($task_details[$i]))
                    {
                        echo $task_details[$i]->task_applicable == 'Y' ? '<td>Yes</td>' : ($task_details[$i]->task_applicable == 'N' ? '<td>No</td>' : '<td></td>');
                    }
                    else
                    {
                        echo '<td></td>';
                    }    

                    if(isset($task_details[$i]))
                    {
                        echo '<td>';
                        echo isset($epa_task_status[$task_details[$i]->task_status]) ? $epa_task_status[$task_details[$i]->task_status] : $task_details[$i]->task_status;
                        echo '</td>';
                    }
                    else
                    {
                        echo '<td></td>';
                    }
                }
                
                // task_actual_date
                if(isset($task_details[8]))
                {
                    echo '<td>';
                    echo isset($task_details[8]->task_actual_date) ? Date::toShort($task_details[8]->task_actual_date) : '';
                    echo '</td>';
                }
                else
                {
                    echo '<td></td>';
                }

		// epa_result_type
                if(isset($task_details[8]))
                {
                    echo '<td>';
                    echo isset($task_details[8]->task_type) ? ($task_details[8]->task_type == 1 ? 'On Programme' : ($task_details[8]->task_type == 2 ? 'Re-Sit' : '') ) : '';
                    echo '</td>';
                }
                else
                {
                    echo '<td></td>';
                }
                
                //Project
                if(isset($task_details[9]))
                {
                    echo $task_details[9]->task_applicable == 'Y' ? '<td>Yes</td>' : ($task_details[9]->task_applicable == 'N' ? '<td>No</td>' : '<td></td>');
                }
                else
                {
                    echo '<td></td>';
                }    

                //Status 9
                if(isset($task_details[9]))
                {
                    echo '<td>';
                    echo isset($epa_task_status[$task_details[9]->task_status]) ? $epa_task_status[$task_details[9]->task_status] : $task_details[9]->task_status;
                    echo '</td>';
                }
                else
                {
                    echo '<td></td>';
                }

                // gateway_forecast
                if(isset($task_details[12]))
                {
                    echo '<td>';
                    echo isset($task_details[12]->task_actual_date) ? Date::toShort($task_details[12]->task_actual_date) : '';
                    echo '</td>';
                }
                else
                {
                    echo '<td></td>';
                }

                //summative_portfolio
                if(isset($task_details[3]))
                {
                    echo '<td>';
                    echo isset($task_details[3]->task_actual_date) ? Date::toShort($task_details[3]->task_actual_date) : '';
                    echo '</td>';
                }
                else
                {
                    echo '<td></td>';
                }
                
                //eol
                if(isset($task_details[15]))
                {
                    echo $task_details[15]->task_applicable == 'Y' ? '<td>Yes</td>' : ($task_details[15]->task_applicable == 'N' ? '<td>No</td>' : '<td></td>');
                }
                else
                {
                    echo '<td></td>';
                }    

                // eol_actual_date
                if(isset($task_details[15]))
                {
                    echo '<td>';
                    echo isset($task_details[15]->task_actual_date) ? Date::toShort($task_details[15]->task_actual_date) : '';
                    echo '</td>';
                }
                else
                {
                    echo '<td></td>';
                }
                    
                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $view->getViewNavigatorExtra('', $view->getViewName());
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    public function exportToCSV(PDO $link, VoltView $view)
    {
        set_time_limit(0);

        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if($st)
        {
            $epa_task_status = InductionHelper::getListOpTaskStatus();

            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=EpaStatusReport.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }

            echo 'Programme,Firstnames,Surname,Planned End Date,Assessor,Coordinator,';
            echo 'EPA ready,Status 1,Employer reference,Status 2,Summative portfolio,Status 3,';
            echo 'IQA complete,Status 4,Passed to awarding body,Status 5,Synoptic project,Status 6,';
            echo 'Interview,Status 7,EPA result,Status 8,EPA result Actual Date,EPA result Type,Project,Status 9,';
            echo 'Gateway Forecast,Summative Portfolio,EOL,EOL Actual Date';


            echo "\r\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $task_details = [];
                $tr_id = $row['tr_id'];

                echo HTML::csvSafe($row['programme']) . ',';
                echo HTML::csvSafe($row['firstnames']) . ',';
                echo HTML::csvSafe($row['surname']) . ',';
                echo HTML::csvSafe($row['planned_end_date']) . ',';
                echo HTML::csvSafe($row['assessor']) . ',';
                echo HTML::csvSafe($row['coordinator']) . ',';

                $epa_details_sql = <<<EPA_SQL
SELECT 
    op_epa.*
FROM 
    tr
    LEFT JOIN (SELECT m1.*
        FROM op_epa m1 LEFT JOIN op_epa m2
        ON (m1.tr_id = m2.tr_id AND m1.task = m2.task AND m1.id < m2.id )
        WHERE m2.id IS NULL ) AS op_epa ON tr.`id` = op_epa.tr_id 
WHERE tr.id = '$tr_id'
;               
EPA_SQL;
                $op_details = DAO::getResultset($link, $epa_details_sql, DAO::FETCH_ASSOC);
                foreach($op_details AS $op_detail)
                {
                    $task_details[$op_detail['task']] = (object)$op_detail;
                }

                for($i = 1; $i <= 8; $i++)
                {
                    if(isset($task_details[$i]))
                    {
                        echo $task_details[$i]->task_applicable == 'Y' ? 'Yes,' : ($task_details[$i]->task_applicable == 'N' ? 'No,' : ',');
                    }
                    else
                    {
                        echo ',';
                    }    

                    if(isset($task_details[$i]))
                    {
                        echo isset($epa_task_status[$task_details[$i]->task_status]) ? $epa_task_status[$task_details[$i]->task_status] : $task_details[$i]->task_status;
                        echo ',';
                    }
                    else
                    {
                        echo ',';
                    }
                }
                
                // task_actual_date
                if(isset($task_details[8]))
                {
                    echo isset($task_details[8]->task_actual_date) ? Date::toShort($task_details[8]->task_actual_date) : '';
                    echo ',';
                }
                else
                {
                    echo ',';
                }

		// epa_result_type
                if(isset($task_details[8]))
                {
                    echo isset($task_details[8]->task_type) ? ($task_details[8]->task_type == 1 ? 'On Programme' : ($task_details[8]->task_type == 2 ? 'Re-Sit' : '') ) : '';
                    echo ',';
                }
                else
                {
                    echo ',';
                }
                
                //Project
                if(isset($task_details[9]))
                {
                    echo $task_details[9]->task_applicable == 'Y' ? 'Yes,' : ($task_details[9]->task_applicable == 'N' ? 'No,' : ',');
                }
                else
                {
                    echo ',';
                }    

                //Status 9
                if(isset($task_details[9]))
                {
                    echo isset($epa_task_status[$task_details[9]->task_status]) ? $epa_task_status[$task_details[9]->task_status] : $task_details[9]->task_status;
                    echo ',';
                }
                else
                {
                    echo ',';
                }

                // gateway_forecast
                if(isset($task_details[12]))
                {
                    echo isset($task_details[12]->task_actual_date) ? Date::toShort($task_details[12]->task_actual_date) : '';
                    echo ',';
                }
                else
                {
                    echo ',';
                }

                //summative_portfolio
                if(isset($task_details[3]))
                {
                    echo isset($task_details[3]->task_actual_date) ? Date::toShort($task_details[3]->task_actual_date) : '';
                    echo ',';
                }
                else
                {
                    echo ',';
                }
                
                //eol
                if(isset($task_details[15]))
                {
                    echo $task_details[15]->task_applicable == 'Y' ? 'Yes,' : ($task_details[15]->task_applicable == 'N' ? 'No,' : ',');
                }
                else
                {
                    echo ',';
                }    

                // eol_actual_date
                if(isset($task_details[15]))
                {
                    echo isset($task_details[15]->task_actual_date) ? Date::toShort($task_details[15]->task_actual_date) : '';
                    echo ',';
                }
                else
                {
                    echo ',';
                }


                echo "\r\n";
            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

}