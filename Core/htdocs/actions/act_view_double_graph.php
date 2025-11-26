<?php
class view_double_graph implements IAction
{
	public function execute(PDO $link)
	{
		$first = isset($_REQUEST['first'])?$_REQUEST['first']:'progress';
		$second = isset($_REQUEST['second'])?$_REQUEST['second']:'disability';
		//var_dump($first . "  " . $second);
		$this->createTempTable($link);
		$view = ViewDoubleGraph::getInstance($first,$second, $link);
		$view->refresh($link, $_REQUEST);
		$view->save_graph_data($link);	

		// Get unique labels for first dimension
		$sql = "select distinct `$first` from multi_bar_graph order by `$first`";
		$first_labels = DAO::getSingleColumn($link, $sql);

		// Get unique labels for second dimension
		$sql = "select distinct `$second` from multi_bar_graph order by `$second`";
		$second_labels = DAO::getSingleColumn($link, $sql);
		
		// Generating xml for stacked graph
		$xml = "<graph>";
		$titles = implode(",", $second_labels);
		$table_titles = implode("</th><th>", $second_labels);
		foreach($first_labels as $first_label)
		{
			$xml .= "<row><data>";
			foreach($second_labels as $second_label)
			{
				$sql = "SELECT COUNT(*) from multi_bar_graph where `$first` = '" . addslashes((string)$first_label)
					. "' and `$second` = '" . addslashes((string)$second_label) . "' group by concat(`$first`, `$second`)";
				$value = DAO::getSingleValue($link, $sql);
				$xml .= ($value=='')?"0,":$value . ",";
			}
			$xml = trim($xml, ','); // remove final comma
			$xml .= "</data>";
			$xml .= "<label>" . htmlspecialchars((string)$first_label) . "</label>";
			$xml .= "</row>";
		}
		$xml .= "</graph>";
		
		// Drop down list arrays
		$first_dropdown = "SELECT id, CONCAT(description), null FROM lookup_stacked_graph ORDER BY description;";
		$first_dropdown = DAO::getResultset($link, $first_dropdown);
		
		$second_dropdown = "SELECT id, CONCAT(description), null FROM lookup_stacked_graph ORDER BY description;";
		$second_dropdown = DAO::getResultset($link, $second_dropdown);

		$sql = "select count(*) from multi_bar_graph";
		$anyresults = (int)DAO::getSingleValue($link, $sql);
		
		require_once('tpl_view_double_graph.php');
	}

	
	private function createTempTable(PDO $link)
	{
		$sql = <<<SQL
DROP TEMPORARY TABLE IF EXISTS `multi_bar_graph`; 
CREATE TEMPORARY TABLE `multi_bar_graph` (
  `tr_id` int(10) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `ethnicity` varchar(200) DEFAULT NULL,
  `disability` varchar(200) DEFAULT NULL,
  `progress` varchar(25) DEFAULT NULL,
  `age_range` varchar(25) DEFAULT NULL,
  `learning_difficulty` varchar(200) DEFAULT NULL,
  `course` varchar(200) DEFAULT NULL,
  `assessor` varchar(50) DEFAULT NULL,
  `verifier` varchar(50) DEFAULT NULL,
  `tutor` varchar(50) DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `actual_work_experience` int(11) DEFAULT NULL,
  `monthly_work_experience` int(11) DEFAULT NULL,
  `work_experience_coordinator` varchar(50) DEFAULT NULL,
  `work_experience_band_10` varchar(10) DEFAULT NULL,
  `mainarea` varchar(200) DEFAULT NULL,
  `subarea` varchar(200) DEFAULT NULL,
  `level` varchar(10) DEFAULT NULL,
  `job_role` varchar(150) DEFAULT NULL,
  `record_status` varchar(50) DEFAULT NULL,
  `apprentice_coordinator` varchar(100) DEFAULT NULL
  ,`percentage_completed` varchar(100) DEFAULT NULL,
  `target` varchar(100) DEFAULT NULL,
  `area_code` varchar(100) DEFAULT NULL,
  `employer` varchar(250) DEFAULT NULL
)
SQL;
		DAO::execute($link, $sql);
	}
}