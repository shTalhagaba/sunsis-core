<?php
class view_iqa_report implements IAction
{
    public function execute(PDO $link)
    {
        $view = ViewIQAReport::getInstance($link);
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $view->refresh($link, $_REQUEST);

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_iqa_report", "View IQA Report");

        if($subaction == 'export_csv')
        {
            $this->exportToCSV($link, $view);
            exit;
        }

        require_once('tpl_view_iqa_report.php');
    }

    private function exportToCSV(PDO $link, View $view)
    {
		$rows = array();
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
        set_time_limit(0);
        ini_set('memory_limit','8192M');
		$result = DAO::getResultset($link, $statement, DAO::FETCH_ASSOC);
		foreach($result AS $rs)
			$rows[] = $rs;
		unset($result);


        header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename=IQAReport.csv');
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
		{
			header('Pragma: public');
			header('Cache-Control: max-age=0');
		}
		$line = '';
		$line .= 'Firstname,Surname,Programme,ProjectName,IQALead,CountSubmissions,AcceptOrRejectDate,Criteria,Included,Accept,Reject,RejectReason,FailReason1,FailReason2,FailReason3,RecommendationType,CoachActionStatus,IQAStatus,IQARejectReason,FirstSample,Assessor';

		echo $line . "\r\n";
		foreach($rows AS $row)
		{
			$line = '';
			$line .= $this->csvSafe($row['Firstname']) .',';
			$line .= $this->csvSafe($row['Surname']) .',';
			$line .= $this->csvSafe($row['Programme']) .',';
			$line .= $this->csvSafe($row['ProjectName']) .',';
			$line .= $this->csvSafe($row['IQALead']) .',';
			$line .= $this->csvSafe($row['CountSubmissions']) .',';
			$line .= $this->csvSafe($row['AcceptOrRejectDate']) .',';
			$line .= $this->csvSafe($row['Criteria']) .',';
			$line .= $this->csvSafe($row['Included']) .',';
			$line .= $this->csvSafe($row['Accept']) .',';
			$line .= $this->csvSafe($row['Reject']) .',';
			$line .= $this->csvSafe($row['RejectReason']) .',';
			$line .= $this->csvSafe($row['FailReason1']) .',';
			$line .= $this->csvSafe($row['FailReason2']) .',';
			$line .= $this->csvSafe($row['FailReason3']) .',';
			$line .= $this->csvSafe($row['RecommendationType']) .',';
			$line .= $this->csvSafe($row['CoachActionStatus']) .',';
			$line .= $this->csvSafe($row['IQAStatus']) .',';
			$line .= $this->csvSafe($row['IQARejectReason']) .',';
			$line .= $this->csvSafe($row['FirstSample']) .',';
			$line .= $this->csvSafe($row['Assessor']) .',';
			echo $line . "\r\n";
            unset($line);
		}
		exit;
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