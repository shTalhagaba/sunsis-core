<?php
class view_learner_progress implements IAction
{
    public function execute(PDO $link)
    {
        $view = ViewLearnerProgress::getInstance($link);
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view->refresh($link, $_REQUEST);

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_learner_progress", "View Learner Progress");

        if($subaction == 'export_csv')
        {
            $this->exportToCSV($link, $view);
            exit;
        }

        require_once('tpl_view_learner_progress.php');
    }

    private function exportToCSV(PDO $link, $view)
    {
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if($st)
        {
            $columns = array();
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }
            //$columns = $this->removeNotRequiredColumns($view->getViewName(), $columns);
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$view->getViewName().'.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }

            foreach($columns AS $column)
            {
                if($column=='timely' or $column=='tr_id' or $column=='contract_id' or $column=='expired' or $column=='iqa_status')
                    continue;
                else
                    echo ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . ',';
            }
            echo "\r\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $class = "";
                foreach($columns AS $column)
                {
                    if($column=='timely' or $column=='tr_id' or $column=='contract_id' or $column=='expired' or $column=='iqa_status' or $column=='assessment_progress_2')
                    {
                        continue;
                    }
                    else
                        echo ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';



                    if($column=='assessment_progress')
                    {
                        $class = '';
                        $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                        $assessment_evidence = DAO::getSingleValue($link, "SELECT assessment_evidence FROM courses WHERE id = '{$row['course_id']}'");
                        $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$row['course_id']}';");
                        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                        if($assessment_evidence==2)
                        {
                            $class = 'bg-green';
                            $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");
                            $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
                            $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$row['course_id']}' ORDER BY id DESC LIMIT 1");
                            if(isset($max_month_row->id))
                            {
                                $class = 'bg-red';
                                if($current_training_month == 0)
                                    $class = 'bg-green';
                                elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                                    $class = 'bg-green';
                                elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                                    $class = 'bg-red';
                                else
                                {
                                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$row['course_id']}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                                    $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$row['course_id']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                                    if($aps_to_check == '' || $passed_units >= $aps_to_check)
                                        $class = 'bg-green';
                                }
                            }
                            echo count($total_units) != 0 ?  $passed_units . '/' . $total_units . ' = ' . round(($passed_units/$total_units) * 100) . '%,' : ',';
                            echo count($total_units) != 0 ?  $passed_units2 . '/' . $total_units . ' = ' . round(($passed_units2/$total_units) * 100) . '%,' : ',';
                        }
                        else
                        {
                            $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
                                    sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
                            $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
                        sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                        WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
                            $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$row['course_id']}' ORDER BY id DESC LIMIT 1");
                            $sd = Date::toMySQL($row['start_date']);
                            if(isset($max_month_row->id))
                            {
                                $class = 'bg-red';
                                if($current_training_month == 0)
                                    $class = 'bg-green';
                                elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                                    $class = 'bg-green';
                                elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                                    $class = 'bg-red';
                                else
                                {
                                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                                    $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                                    if($aps_to_check == '' || $passed_units >= $aps_to_check)
                                        $class = 'bg-green';
                                }
                            }
                            echo ($total_units != 0) 
                                ? $passed_units . '/' . $total_units . ' = ' . round(($passed_units/$total_units) * 100) . '%,' 
                                : ',';

                            echo ($total_units != 0) 
                                ? $passed_units2 . '/' . $total_units . ' = ' . round(($passed_units2/$total_units) * 100) . '%,' 
                                : ',';
                        }
                    }

                    if($column=='assessment_plan_status')
                    {
                        if($assessment_evidence==2)
                        {
                            $ap = DAO::getSingleValue($link, "SELECT
                            GROUP_CONCAT(CONCAT(evidence_project.project, ' (' ,(SELECT COUNT(*) FROM project_submissions WHERE project_submissions.`project_id` = tr_projects.id) ,') '
                            ,IF(sub.completion_date IS NOT NULL,\"Complete\",IF(sub.iqa_status=2,\"Rework required\",IF(sub.sent_iqa_date IS NOT NULL AND (sub.iqa_status IS NULL OR sub.iqa_status!=2),\"IQA\",IF(sub.submission_date IS NOT NULL,\"Awaiting marking\",IF(sub.due_date<CURDATE() AND submission_date IS NULL,\"Overdue\",\"In progress\"))))))) AS `status`
                            FROM tr_projects
                            LEFT JOIN project_submissions AS sub ON sub.`project_id` = tr_projects.`id` AND sub.id = (SELECT MAX(id) FROM project_submissions AS s2 WHERE s2.`project_id` = tr_projects.id)
                            LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
                            WHERE tr_projects.project IS NOT NULL AND tr_projects.tr_id= {$row['tr_id']}");
                            echo $ap . ',';
                        }
                        else
                        {
                            $ap = DAO::getSingleValue($link, "SELECT
                            GROUP_CONCAT(CONCAT(lookup_assessment_plan_log_mode.description, ' ('
                            ,(SELECT COUNT(*) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.`assessment_plan_id` = assessment_plan_log.`id`)
                            ,') ',(IF(sub.completion_date IS NOT NULL,\"Complete\",IF(sub.iqa_status=2,\"Rework required\",IF(sub.sent_iqa_date IS NOT NULL AND (sub.iqa_status IS NULL OR sub.iqa_status!=2),\"IQA\",IF(sub.submission_date IS NOT NULL,\"Awaiting marking\",IF(sub.due_date<CURDATE() AND submission_date IS NULL,\"Overdue\",\"In progress\"))))))))
                            FROM assessment_plan_log
                            LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
                                sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                            LEFT JOIN tr ON tr.id = assessment_plan_log.tr_id
                            LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
                            LEFT JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.id = assessment_plan_log.mode AND student_frameworks.id = lookup_assessment_plan_log_mode.framework_id
                            WHERE assessment_plan_log.tr_id = {$row['tr_id']}");
                            echo $ap . ',';
                        }
                    }


                }
                echo "\r\n";
            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function csvSafe($value)
    {
        $value = str_replace(',', '; ', $value);
        $value = str_replace(array("\n", "\r"), '', $value);
        $value = str_replace("\t", '', $value);
        $value = '"' . str_replace('"', '""', $value) . '"';
        return $value;
    }
}
?>