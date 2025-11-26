<?php
class view_single_graph implements IAction
{
	public function execute(PDO $link)
	{
		$first = isset($_REQUEST['first']) ? $_REQUEST['first'] : 'age_range';

		$this->createTempTable($link);

		$view = ViewDoubleGraph::getInstance($first, $first, $link);
		$view->refresh($link, $_REQUEST);

		$view->save_graph_data($link);

		if ($first == 'monthly_work_experience')
			$sql = "select DATE_FORMAT($first,'%b-%y') as description, count(COALESCE($first,1)) as total from multi_bar_graph group by $first";
		else
			$sql = "select $first as description, count(COALESCE($first,1)) as total from multi_bar_graph group by $first";
		//pre($sql);
		$st = $link->query($sql);
		$xml = "";
		if ($st) {
			// for pie

			$xml = "<graph>";
			// for single bar
			$data = array();
			$labels = array();
			while ($row = $st->fetch()) {
				// for pie
				$xml .= "<record>";
				$xml .= "<description>" . str_replace(",", "", $row['description'] ?: '') . "</description>";
				$xml .= "<value>" . $row['total'] . "</value>";
				$xml .= "</record>";
				// for single bar
				$data[] = $row['total'];
				$labels[] = str_replace(",", "", $row['description'] ?: '');
			}
			$xml .= "</graph>";
		}

		$xml = str_replace("&", "&amp;", $xml ?: '');

		// Drop down list arrays
		$first_dropdown = "SELECT id, CONCAT(description), null FROM lookup_stacked_graph ORDER BY description;";
		$first_dropdown = DAO::getResultset($link, $first_dropdown);

		require_once('tpl_view_single_graph.php');
	}


	private function createTempTable(PDO $link)
	{
		$sql = <<<HEREDOC
CREATE TEMPORARY TABLE `multi_bar_graph` (
  `tr_id` int(10) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `ethnicity` varchar(100) DEFAULT NULL,
  `disability` varchar(100) DEFAULT NULL,
  `progress` varchar(25) DEFAULT NULL,
  `age_range` varchar(25) DEFAULT NULL,
  `learning_difficulty` varchar(100) DEFAULT NULL,
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
  apprentice_coordinator varchar(100) DEFAULT NULL
  ,`percentage_completed` varchar(100) DEFAULT NULL,
  `target` varchar(100) DEFAULT NULL,
  `area_code` varchar(100) DEFAULT NULL,
  `employer` varchar(250) DEFAULT NULL
)
HEREDOC;
		DAO::execute($link, $sql);
	}
}
