<?php
class view_evidence_matrix_projects implements IAction
{
    public function execute(PDO $link)
    {
        $view = ViewEvidenceMatrixProjects::getInstance($link);
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view->refresh($link, $_REQUEST);

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_evidence_matrix_projects", "View Evidence Matrix Projects");

        if($subaction == 'export_csv')
        {
            $this->exportToCSV($link, $view);
            exit;
        }

        require_once('tpl_view_evidence_matrix_projects.php');
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
                    if($column=='timely' or $column=='tr_id' or $column=='contract_id' or $column=='expired' or $column=='iqa_status')
                    {
                        continue;
                    }
                    elseif($column=='total_plan')
                    {
                        $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                        $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '$course_id';");
                        echo isset($total_units) ? $total_units.',' : ',';
                    }
                    elseif($column=='expected_progress')
                    {
                        $total_units = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM ap_percentage WHERE course_id = '{$course_id}';");
                        $sd = Date::toMySQL($row['start_date']);
                        $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                        echo isset($total_units) ? round($current_training_month/$total_units*100).',' : ',';
                    }
                    elseif($column=='status')
                    {

                        if($row['completion_date']!='')
                            $status = "Complete";
                        elseif($row['iqa_status']=='2')
                            $status = "Rework Required";
                        elseif($row['sent_iqa_date']!='' and $row['iqa_status']!='2')
                            $status = "IQA";
                        elseif($row['submission_date']!='')
                            $status = "Awaiting marking";
                        elseif($row['expired']=='1' and $row['submission_date']=='')
                            $status = "Overdue";
                        elseif($row['set_date']!='' and $row['expired']=='0' and $row['submission_number']=='1')
                            $status = "In progress";
                        else
                            $status = "Rework Required";

                        echo $status . ",";
                    }
                    elseif($column=='project_progress')
                    {
                        $class = 'bg-green';
                        $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                        $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                        $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                        sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                        WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");
                        $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                        sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                        WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
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
                        if($total_units != 0)
                            echo round(($passed_units/$total_units) * 100).',';
                        else
                            echo '0,';
                    }
                    elseif($column=='projects_completed')
                    {
                        $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                        sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                        WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");
                        echo $passed_units . ",";
                    }
                    elseif($column=='project_status')
                    {
                        $class = 'bg-green';
                        $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                        $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                        $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                        sub.id = (SELECT MAX(id) FROM project_submissions WHERE project not in (168,227,570,571,572,573,574,575,576,592,585) and project_submissions.project_id = tr_projects.id)
                        WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");
                        $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                        sub.id = (SELECT MAX(id) FROM project_submissions WHERE project not in (168,227,570,571,572,573,574,575,576,592,585) and project_submissions.project_id = tr_projects.id)
                        WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
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
                    elseif($column=='weeks_on_plan')
                    {
                        echo isset($current_training_month) ? $current_training_month.',' : ',';
                    }
                    elseif($column=='project_2')
                    {
                        if($total_units != 0)
                            echo round(($passed_units2/$total_units) * 100).',';
                        else
                            echo '0,';
                    }
                    elseif($column == 'LAR')
                    {
                        echo $row['LAR'] == '' ? 'No,' : $row['LAR'] . ',';
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