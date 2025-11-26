<?php
class view_tr_destinations implements IAction
{
	public function execute(PDO $link)
	{
		$panel = isset($_REQUEST['panel'])?$_REQUEST['panel']:'';

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_tr_destinations", "View Destinations");

		$view = ViewTrDestinations::getInstance($link); /* @var $view View */
		$view->refresh($link, $_REQUEST);

		if($panel == 'DestinationsByOutcomeType')
		{
			$ss = array();
			foreach($this->DestinationsByOutcomeType($link, $view->getSQLStatement()) AS $key => $value)
			{
				$ss[] = array($key , $value);
			}
			echo(json_encode($ss));

			exit;
		}
		if($panel == 'DestinationsByOutcomeCode')
		{
			echo $this->DestinationsByOutcomeCode($link, $view->getSQLStatement());

			exit;
		}

		require_once('tpl_view_tr_destinations.php');
	}


	private function DestinationsByOutcomeType(PDO $link, $sqlStatement) /* @var $sqlStatement SQLStatement */
	{
		$sqlStatement->removeClause('LIMIT');
		$sqlStatement->removeClause('ORDER BY');
		$view_sql = $sqlStatement->__toString();

		$return = array();
		$sql = <<<SQL
SELECT destinations.outcome_type AS `code`, COUNT(*) AS cnt FROM destinations INNER JOIN ($view_sql) AS view_dests ON destinations.id = view_dests.dest_id GROUP BY destinations.outcome_type;
SQL;

		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($result AS $rs)
		{
			if($rs['code'] == 'EDU')
				$return['EDU-Education'] = (int)$rs['cnt'];
			elseif($rs['code'] == 'EMP')
				$return['EMP-In Paid Employment'] = (int)$rs['cnt'];
			elseif($rs['code'] == 'GAP')
				$return['GAP-Gap Year'] = (int)$rs['cnt'];
			elseif($rs['code'] == 'NPE')
				$return['NPE-Not in Paid Employment'] = (int)$rs['cnt'];
			elseif($rs['code'] == 'OTH')
				$return['OTH-Other'] = (int)$rs['cnt'];
			elseif($rs['code'] == 'SDE')
				$return['SDE-Social Destination (High needs students only)'] = (int)$rs['cnt'];
			elseif($rs['code'] == 'VOL')
				$return['VOL-Voluntary Work'] = (int)$rs['cnt'];
			else
				$return['Unknown'] = (int)$rs['cnt'];
		}
		return $return;
	}

	private function DestinationsByOutcomeCode(PDO $link, $sqlStatement) /* @var $sqlStatement SQLStatement */
	{
		$sqlStatement->removeClause('LIMIT');
		$sqlStatement->removeClause('ORDER BY');
		$view_sql = $sqlStatement->__toString();

		$sql = <<<SQL
SELECT
  COUNT(*) AS cnt,
  (SELECT CONCAT(type_code, '-',description) FROM central.`lookup_destination_outcome_code` WHERE lookup_destination_outcome_code.type_code = destinations.type_code) AS `status`
FROM
  destinations INNER JOIN ($view_sql) AS view_dests ON destinations.id = view_dests.dest_id
GROUP BY destinations.type_code
ORDER BY `status`;


SQL;

		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$category = array();
		$category['name'] = 'Destination Code';
		$series1 = array();
		$series1['name'] = 'Learners';

		foreach ($result AS $rs) {
			$category['data'][] = $rs['status'];
			$series1['data'][] = $rs['cnt'];
		}
		$result = array();
		array_push($result, $category);
		array_push($result, $series1);
		return (json_encode($result, JSON_NUMERIC_CHECK));
	}
}
?>