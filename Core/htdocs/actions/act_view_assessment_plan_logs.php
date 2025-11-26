<?php
class view_assessment_plan_logs implements IAction
{
    public function execute(PDO $link)
    {
        $view = ViewAssessmentPlanLogs::getInstance($link);
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view->refresh($link, $_REQUEST);

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_assessment_plan_logs", "View Assessment Plan Logs Report");

        if($subaction == 'export_csv')
        {
            $this->exportToCSV($link, $view);
            exit;
        }

        require_once('tpl_view_assessment_plan_logs.php');
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
                if($column=='timely' or $column=='tr_id' or $column=='contract_id' or $column=='comments')
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
                    if($column=='timely' or $column=='tr_id' or $column=='contract_id' or $column=='comments')
                    {
                        continue;
                    }
                    elseif($column=='assessment_progress')
                    {
                        $class = 'bg-green';
                        $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                        $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                        $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$row['tr_id']}' AND paperwork = '3';");
                        $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$row['tr_id']}' AND paperwork in ('3','2','5');");
                        $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                        $sd = Date::toMySQL($row['start_date']);
                        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
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
                        if(count($total_units) != 0)
                            echo round(($passed_units/$total_units) * 100).',';
                        else
                            echo '0,';
                    }
                    elseif($column=='assessment_status')
                    {
                        $class = 'bg-green';
                        $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                        $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                        $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$row['tr_id']}' AND paperwork = '3';");
                        $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$row['tr_id']}' AND paperwork in ('3','2','5');");
                        $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                        $sd = Date::toMySQL($row['start_date']);
                        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
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

                        if($class == 'bg-green')
                            echo 'On track,';
                        else
                            echo 'Behind,';
                    }
                    elseif($column=='months_on_plan')
                    {
                            echo isset($current_training_month) ? $current_training_month.',' : ',';
                    }
                    elseif($column=='assessment_plan_2')
                    {
                        if(count($total_units) != 0)
                            echo round(($passed_units2/$total_units) * 100).',';
                        else
                            echo '0,';
                    }
                    else
                        echo ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
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