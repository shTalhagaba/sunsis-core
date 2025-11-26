<?php
class view_internal_validation_report implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_internal_validation_report", "View Internal Validation Report");

		$view = ViewInternalValidationReport::getInstance($link);
		$view->refresh($link, $_REQUEST);

		if($subaction == 'export_to_csv')
			$this->exportInternalValidationReportToCSV($link, $view);

		require_once('tpl_view_internal_validation_report.php');
	}

	public function exportInternalValidationReportToCSV(PDO $link, ViewInternalValidationReport $view)
	{
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=InternalValidationReport.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			$line = '';
			$line .= 'Learner Name,IV Name,IV Date,IV Type,IV Action Date,QAN,Qualification Title,Units,Comments';
			echo $line . "\r\n";
			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				$line = str_replace(',','; ', $row['learner_name'] ?: '') . ', ';
				$line .= str_replace(',','; ', $row['iv_name'] ?: '') . ', ';
				$line .= Date::toShort($row['iv_date']) . ', ';
				$line .= $row['iv_type'] . ', ';
				if($row['iv_action_date'] == '')
					$line .= ', ';
				else
					$line .= Date::toShort($row['iv_action_date']) . ', ';
				$line .= $row['iv_qualification_id'] . ', ';
				$line .= str_replace(',','; ', $row['qualification_title'] ?:'') . ', ';
				$attached_units = DAO::getSingleColumn($link, "SELECT unit_reference FROM internal_validation_unit_details WHERE internal_validation_id = " . $row['iv_id']);
				$qual_id = $row['iv_qualification_id'];
				$tr_id = $row['tr_id'];
				$units = "";
				foreach($attached_units AS $unit)
				{
					$query = <<<QUERY
SELECT extractvalue(evidences, '//unit[@reference="$unit"]/@title') AS title FROM student_qualifications WHERE REPLACE(id,'/','') = '$qual_id' AND tr_id = $tr_id
QUERY;
					$unit_title = DAO::getSingleValue($link, $query);
					$units .= $unit . ' - ' . $unit_title . '; ';
				}

				$line .= $units . ', ';
				$row['comments'] = str_replace(array("\r\n", "\n\r", "\n", "\r"), ',', $row['comments'] ?:'');
				$line .= str_replace(',','; ', $row['comments'] ?: '') . ', ';
				echo $line . "\r\n";
			}
		}

		exit;
	}
}
?>