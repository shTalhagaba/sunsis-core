<?php
class manager_intervention_report2 implements IAction
{
    public function execute(PDO $link)
    {
        $view = ManagerInterventionReport2::getInstance($link);
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view->refresh($link, $_REQUEST);

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=manager_intervention_report", "View Manager Intervention Report");

        if($subaction == 'export_csv')
        {
            $this->exportToCSV($link, $view);
            exit;
        }

        require_once('tpl_manager_intervention_report2.php');
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