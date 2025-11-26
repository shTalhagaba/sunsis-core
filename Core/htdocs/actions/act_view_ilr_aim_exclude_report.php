<?php
class view_ilr_aim_exclude_report implements IAction
{
    public function execute(PDO $link)
    {
        $view = ViewILRAimExcludeReport::getInstance($link);
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view->refresh($link, $_REQUEST);

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_ilr_aim_exclude_report", "View ILR Aim Exclude Report");

        if($subaction == 'export_csv')
        {
            $this->exportToCSV($link, $view);
            exit;
        }

        require_once('tpl_view_ilr_aim_exclude_report.php');
    }

    private function exportToCSV(PDO $link, $view)
    {
        $rp = new ReflectionProperty('View', 'sql');
        $rp->setAccessible(true);
        $st = $link->query($rp->getValue($view));
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
                if($column=='timely' or $column=='tr_id' or $column=='contract_id' or $column=='comments' or $column=='expired' or $column=='iqa_status')
                    continue;
                else
                    echo ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . ',';
            }
            echo "\r\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $aim = $row['aim_reference'];
                $tr_id = $row['tr_id'];
                $row['exclude'] = DAO::getSingleValue($link, "SELECT EXTRACTVALUE(ilr, \"/Learner/LearningDelivery[LearnAimRef='$aim']/Exclude\") FROM ilr WHERE tr_id = '$tr_id' ORDER BY contract_id DESC, submission DESC LIMIT 1");
                if($row['exclude']==1)
                {
                    $class = "";
                    foreach($columns AS $column)
                    {
                        if($column=='timely' or $column=='tr_id' or $column=='contract_id' or $column=='comments' or $column=='expired' or $column=='iqa_status')
                        {
                            continue;
                        }
                        else
                            echo ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
                    }
                    echo "\r\n";
    
                }    
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