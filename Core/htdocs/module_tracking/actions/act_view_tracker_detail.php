<?php
class view_tracker_detail implements IAction
{
	public function execute(PDO $link)
	{

		$tracker_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$key = 'view_ViewTrackerDetails'.$tracker_id;
		$view = !isset($_SESSION[$key]) ? ViewTrackerDetails::getInstance($tracker_id) : $_SESSION[$key];
		$filter_tracker = $view->getFilter('filter_tracker');
		$filter_tracker->setValue($tracker_id);
		$view->refresh($link, $_REQUEST);
		
		if($subaction == 'export')
		{
			$this->exportToCSV($link, $view);
			exit;
		}

		$_SESSION['bc']->add($link, "do.php?_action=view_tracker_detail&id=".$tracker_id, "Programme Detail");

		require_once('tpl_view_tracker_detail.php');
	}

	private function exportToCSV(PDO $link, View $view)
	{
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if($st)
		{
			$columns = DAO::getSingleColumn($link, "SELECT DISTINCT colum FROM view_columns WHERE view = 'ViewTrackerDetails' ORDER BY sequence");
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename='.$view->getViewName().'.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			foreach($columns AS $column)
			{
				echo ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . ',';
			}
			echo 'Main Contact,Contact Name,Tel,Mobile,Email,';
			echo "\r\n";
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				foreach($columns AS $column)
				{
					echo ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
				}
				$sql_c = <<<SQL
SELECT
  contact_name,
  contact_telephone AS tel,
  contact_mobile AS mobile,
  contact_email AS email
FROM
  organisation_contact INNER JOIN tr_operations ON organisation_contact.`contact_id` = tr_operations.`main_contact_id`
WHERE tr_operations.`tr_id` = '{$row['tr_id']}' ;
SQL;
				$main_contact = DAO::getObject($link, $sql_c);
				if(isset($main_contact->contact_name) && $main_contact->contact_name != '')
				{
					echo 'YES,' . $this->csvSafe($main_contact->contact_name) . ',' . $this->csvSafe($main_contact->tel) . ',' . $this->csvSafe($main_contact->mobile) . ',' . $this->csvSafe($main_contact->email);
				}
				else
				{
					echo 'NO,,,,';
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