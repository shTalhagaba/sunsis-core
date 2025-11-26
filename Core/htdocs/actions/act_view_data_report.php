<?php
class view_data_report implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_data_report", "View Data Report");
		
		$view = ViewDataReport::getInstance($link);
		$view->refresh($link, $_REQUEST);
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        if($subaction == 'export_csv')
        {
            $this->exportToCSV($link, $view);
            exit;
        }

		require_once('tpl_view_data_report.php');
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
                if($column=='status_code' or $column=='bil_withdrawal' or $column=='outcome' or $column=='expired' or $column=='iqa_status')
                    continue;
                else
                    echo ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . ',';
            }
            echo "\r\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {

                $tr_id = $row['TRID'];
                $tnp1 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount" . '"';
                $tnp2 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount" . '"';
                $tnp3 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=3]/TBFinAmount" . '"';
                $tnp4 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=4]/TBFinAmount" . '"';
                $aln = '"' . "/Learner/LearningDelivery[AimType=1]/LearningDeliveryFAM[LearnDelFAMType='LSF']/LearnDelFAMCode[last()]" . '"';
                $outgrade = '"' . "/Learner/LearningDelivery[AimType=1]/OutGrade" . '"';
                $res = DAO::getResultset($link, "SELECT extractvalue(ilr, $tnp1),extractvalue(ilr,$tnp2),extractvalue(ilr,$tnp3),extractvalue(ilr,$tnp4),extractvalue(ilr,$aln),extractvalue(ilr,$outgrade) FROM ilr WHERE ilr.tr_id = $tr_id  ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1");
                $row['TNP1'] = @$res[0][0];
                $row['TNP2'] = @$res[0][1];
                $row['TNP3'] = @$res[0][2];
                $row['TNP4'] = @$res[0][3];
                $row['ALN'] = (@$res[0][4]==1)?'Yes':'No';
                $row['EPA_result'] = @$res[0][5];

                $class = "";
                foreach($columns AS $column)
                {
                    if($column=='status_code' or $column=='bil_withdrawal' or $column=='outcome' or $column=='expired' or $column=='iqa_status')
                    {
                        continue;
                    }
                    elseif($column=='learning_status')
                    {
                        if($row['status_code']=='1')
                            echo $this->csvSafe('In-learning') . ',';
                        elseif($row['status_code']=='6')
                            echo $this->csvSafe('Break-in-learning') . ',';
                        elseif($row['status_code']=='3')
                            echo $this->csvSafe('Withdrawn') . ',';
                        elseif($row['status_code']=='2')
                            echo $this->csvSafe('Achieved') . ',';
                        elseif($row['bil_withdrawal']=='1')
                            echo $this->csvSafe('Under consideration for BIL') . ',';
                        elseif($row['bil_withdrawal']=='2')
                            echo $this->csvSafe('Under consideration for withdrawal') . ',';
                        elseif($row['outcome']=='8')
                            echo $this->csvSafe('At EPA') . ',';
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