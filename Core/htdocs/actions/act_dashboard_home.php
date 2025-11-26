<?php
class dashboard_home implements IAction
{

	public $current_contract_year = null;

	public function execute(PDO $link)
	{
		$this->current_contract_year = isset($_REQUEST['contract_year'])?$_REQUEST['contract_year']:date('Y');
		$panel = isset($_REQUEST['panel'])?$_REQUEST['panel']:'';
		if($panel == 'getLearnersInLearning')
		{
			echo $this->getLearnersInLearning($link);
			exit;
		}
		if($panel == 'getEarlyLeavers')
		{
			echo $this->getEarlyLeavers($link);
			exit;
		}
		if($panel == 'getAllLeavers')
		{
			echo $this->getAllLeavers($link);
			exit;
		}
		if($panel == 'getAllLearners')
		{
			echo $this->getAllLearners($link);
			exit;
		}
		if($panel == 'getTimelyAchievers')
		{
			echo $this->getTimelyAchievers($link);
			exit;
		}
		if($panel == 'getAchievers')
		{
			echo $this->getAchievers($link);
			exit;
		}
		if($panel == 'getBehindLearners')
		{
			echo $this->getBehindLearners($link);
			exit;
		}
		if($panel == 'getOnTrackLearners')
		{
			echo $this->getOnTrackLearners($link);
			exit;
		}
		if($panel == 'PieChartLearnersProgress')
		{
			$ss = array();
			foreach($this->generatePieChartLearnersProgress($link) AS $key => $value)
			{
				$ss[] = array($key , $value);
			}
			echo(json_encode($ss));
			exit;
		}
		if($panel == 'PieChartLearnersCompletionStatus')
		{
			$ss = array();
			foreach($this->generatePieChartLearnersCompletionStatus($link) AS $key => $value)
			{
				$ss[] = array($key , $value);
			}
			echo(json_encode($ss));

			exit;
		}
		if($panel == 'PieChartFundedLearners')
		{
			$ss = array();
			foreach($this->generatePieChartFundedLearners($link) AS $key => $value)
			{
				$ss[] = array($key , $value);
			}
			echo(json_encode($ss));

			exit;
		}
		if($panel == 'PieChartLearnersByGender')
		{
			$ss = array();
			foreach($this->generatePieChartLearnersByGender($link) AS $key => $value)
			{
				$ss[] = array($key , $value);
			}
			echo(json_encode($ss));

			exit;
		}
		if($panel == 'ColumnChartLearnersByAssessor')
		{
			if(DB_NAME=="am_reed")
			{
				$sql = <<<SQL
SELECT
  CONCAT(
    advisers.firstnames,
    ' ',
    advisers.surname
  ) AS `adviser`,
  COUNT(*) AS cnt
FROM
  users AS participants
  INNER JOIN users AS advisers
    ON participants.adviser = advisers.`id`
GROUP BY advisers.id
;
SQL;
			}
			else
			{
				$sql = <<<SQL
SELECT CONCAT(firstnames, ' ', surname) AS adviser,
                (SELECT  COUNT(*) FROM tr
                                LEFT JOIN group_members ON group_members.`tr_id` = tr.id
                                LEFT JOIN groups ON groups.id = group_members.`groups_id`
                                WHERE status_code = 1 AND (tr.assessor = users.id OR groups.`assessor` = users.id)) AS cnt
FROM users
WHERE TYPE = 3 AND web_access = 1;
SQL;

			}
			$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			$category = array();
			$category['name'] = 'Adviser';
			$series1 = array();
			$series1['name'] = 'Learners';

			foreach($result AS $rs)
			{
				$category['data'][] = $rs['adviser'];
				$series1['data'][] = $rs['cnt'];
			}
			$result = array();
			array_push($result,$category);
			array_push($result,$series1);
			echo(json_encode($result, JSON_NUMERIC_CHECK));

			exit;
		}
		if($panel == 'ColumnChartLearnersProgressByAssessor')
		{
			if(DB_NAME=="am_reed")
			{
				$sql = <<<SQL
SELECT CONCAT(firstnames, ' ', surname) AS adviser,
                (SELECT  SUM(IF(COALESCE(tr.l36,0)>=COALESCE(target,0),1,0)) FROM tr
                                LEFT JOIN users AS participants ON (tr.`username` = participants.`username`  AND tr.`contract_id` = participants.`contract`)
                                WHERE status_code = 1 AND (participants.adviser = users.id)) AS on_track,
                (SELECT SUM(IF(COALESCE(tr.l36,0)<COALESCE(target,0),1,0)) FROM tr
                                LEFT JOIN users AS participants ON (tr.`username` = participants.`username`  AND tr.`contract_id` = participants.`contract`)
                                WHERE status_code = 1 AND (participants.adviser = users.id)) AS behind
FROM users
WHERE TYPE = 8 AND web_access = 1
HAVING on_track != '' OR behind != ''
;
SQL;
			}
			else
			{
				$sql = <<<SQL
SELECT CONCAT(firstnames, ' ', surname) AS adviser,
                (SELECT  SUM(IF(COALESCE(tr.l36,0)>=COALESCE(target,0),1,0)) FROM tr
                                LEFT JOIN group_members ON group_members.`tr_id` = tr.id
                                LEFT JOIN groups ON groups.id = group_members.`groups_id`
                                WHERE status_code = 1 AND (tr.assessor = users.id OR groups.`assessor` = users.id)) AS on_track,
                (SELECT SUM(IF(COALESCE(tr.l36,0)<COALESCE(target,0),1,0)) FROM tr
                                LEFT JOIN group_members ON group_members.`tr_id` = tr.id
                                LEFT JOIN groups ON groups.id = group_members.`groups_id`
                                WHERE status_code = 1 AND (tr.assessor = users.id OR groups.`assessor` = users.id)) AS behind
FROM users
WHERE TYPE = 3 AND web_access = 1
HAVING on_track != '' OR behind != ''
;

SQL;

			}
			$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			$category = array();
			$category['name'] = 'Adviser';
			$series1 = array();
			$series1['name'] = 'OnTrack';
			$series2 = array();
			$series2['name'] = 'Behind';

			foreach($result AS $rs)
			{
				$category['data'][] = $rs['adviser'];
				$series1['data'][] = $rs['on_track'];
				$series2['data'][] = $rs['behind'];
			}
			$result = array();
			array_push($result,$category);
			array_push($result,$series1);
			array_push($result,$series2);
			echo(json_encode($result, JSON_NUMERIC_CHECK));

			exit;
		}
		if($panel == 'ColumnChartLearnersByAppFramework')
		{
			$sql = <<<SQL
SELECT (SELECT CONCAT(lars.FworkCode,' ',lars.IssuingAuthorityTitle) FROM lars201617.`Core_LARS_Framework` AS lars WHERE lars.FworkCode = frameworks.`framework_code` AND lars.ProgType = frameworks.`framework_type` LIMIT 0,1) AS Framework
,COUNT(student_frameworks.tr_id) AS cnt FROM frameworks
INNER JOIN student_frameworks ON student_frameworks.id = frameworks.`id`
INNER JOIN tr ON tr.id = student_frameworks.`tr_id` AND tr.`status_code` = 1
WHERE frameworks.`framework_type` IN (2,3,20,21,22,23,25) AND framework_code IS NOT NULL
GROUP BY framework_code,framework_type
;

SQL;

			$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			$category = array();
			$category['name'] = 'Framework';
			$series1 = array();
			$series1['name'] = 'Learners';

			foreach($result AS $rs)
			{
				$category['data'][] = $rs['Framework'];
				$series1['data'][] = $rs['cnt'];
			}
			$result = array();
			array_push($result,$category);
			array_push($result,$series1);
			echo(json_encode($result, JSON_NUMERIC_CHECK));

			exit;
		}
		if($panel == 'ColumnChartLearnersByAppLevel')
		{
			$sql = <<<SQL
SELECT (SELECT CONCAT(lars.ProgType,' ',lars.ProgTypeDesc) FROM lars201617.CoreReference_LARS_ProgType_Lookup AS lars WHERE lars.ProgType = frameworks.`framework_type`) AS `Level`
,COUNT(student_frameworks.tr_id) AS cnt FROM frameworks
INNER JOIN student_frameworks ON student_frameworks.id = frameworks.`id`
INNER JOIN tr ON tr.id = student_frameworks.`tr_id` AND tr.`status_code` = 1
WHERE frameworks.`framework_type` IN (2,3,20,21,22,23,25) AND framework_code IS NOT NULL
GROUP BY framework_type
;

SQL;

			$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			$category = array();
			$category['name'] = 'Level';
			$series1 = array();
			$series1['name'] = 'Learners';

			foreach($result AS $rs)
			{
				$category['data'][] = $rs['Level'];
				$series1['data'][] = $rs['cnt'];
			}
			$result = array();
			array_push($result,$category);
			array_push($result,$series1);
			echo(json_encode($result, JSON_NUMERIC_CHECK));

			exit;
		}
		if($panel == 'SolidLearnerProgressionL2L3')
		{
			$sql = <<<SQL
SELECT 'Achievers' AS `code`, COUNT(*) AS cnt FROM tr WHERE status_code = 2 AND id IN (SELECT tr_id FROM student_frameworks WHERE id IN (SELECT id FROM frameworks WHERE framework_type = 3))
UNION
SELECT 'Progressed' AS `code`, COUNT(*) AS cnt FROM tr WHERE status_code = 2 AND id IN (SELECT tr_id FROM student_frameworks WHERE id IN (SELECT id FROM frameworks WHERE framework_type = 3))
AND l03 IN (SELECT l03 FROM tr WHERE id IN (SELECT tr_id FROM student_frameworks WHERE id IN (SELECT id FROM frameworks WHERE framework_type = 2)));

SQL;
			$level_2_to_3 = array();
			$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			foreach($result AS $rs)
			{
				$level_2_to_3[$rs['code']] = (int)$rs['cnt'];
			}
			echo json_encode($level_2_to_3, JSON_NUMERIC_CHECK);

			exit;
		}
		if($panel == 'SolidLearnerProgressionL3L4')
		{
			$sql = <<<SQL
SELECT 'Achievers' AS `code`, COUNT(*) AS cnt FROM tr WHERE status_code = 2 AND id IN (SELECT tr_id FROM student_frameworks WHERE id IN (SELECT id FROM frameworks WHERE framework_type = 2))
UNION
SELECT 'Progressed' AS `code`, COUNT(*) AS cnt FROM tr WHERE status_code = 2 AND id IN (SELECT tr_id FROM student_frameworks WHERE id IN (SELECT id FROM frameworks WHERE framework_type = 2))
AND l03 IN (SELECT l03 FROM tr WHERE id IN (SELECT tr_id FROM student_frameworks WHERE id IN (SELECT id FROM frameworks WHERE framework_type = 20)));

SQL;
			$level_3_to_4 = array();
			$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			foreach($result AS $rs)
			{
				$level_3_to_4[$rs['code']] = (int)$rs['cnt'];
			}
			echo json_encode($level_3_to_4, JSON_NUMERIC_CHECK);

			exit;
		}
		if($panel == 'SolidLearnerProgressionTP')
		{

			$sql = <<<SQL
SELECT 'Achievers' AS `code`, COUNT(*) AS cnt FROM tr WHERE id IN (SELECT tr_id FROM student_frameworks WHERE id IN (SELECT id FROM frameworks WHERE framework_type = 24))
UNION
SELECT 'Progressed' AS `code`, COUNT(*) AS cnt FROM tr WHERE id IN (SELECT tr_id FROM student_frameworks WHERE id IN (SELECT id FROM frameworks WHERE framework_type = 24))
AND l03 IN (SELECT l03 FROM tr WHERE id IN (SELECT tr_id FROM student_frameworks WHERE id IN (SELECT id FROM frameworks WHERE framework_type IS NOT NULL AND framework_type != 24)));

SQL;
			$level_t_to_p = array();
			$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
			foreach($result AS $rs)
			{
				$level_t_to_p[$rs['code']] = (int)$rs['cnt'];
			}
			echo (json_encode($level_t_to_p, JSON_NUMERIC_CHECK));

			exit;
		}

		include_once('tpl_dashboard_home.php');

	}

	private function generatePieChartFundedLearners(PDO $link)
	{
		$return = array();
		$sql = <<<SQL
SELECT 'Unfunded' AS `code`, COUNT(*) AS cnt FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE status_code = 1 AND target_date < NOW() AND contracts.`contract_year` = '{$this->current_contract_year}'
UNION
SELECT 'Funded' AS `code`, COUNT(*) AS cnt FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE status_code = 1 AND target_date >= NOW() AND contracts.`contract_year` = '{$this->current_contract_year}';
SQL;
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($result AS $rs)
		{
			$return[$rs['code']] = (int)$rs['cnt'];
		}
		return $return;
	}

	private function generatePieChartLearnersByGender(PDO $link)
	{
		$return = array();
		$sql = <<<SQL
SELECT 'Male' AS `Gender`, SUM(IF(gender='M', 1, 0)) AS cnt FROM tr WHERE status_code = 1
UNION
SELECT 'Female' AS `Gender`, SUM(IF(gender='F', 1, 0)) AS cnt FROM tr WHERE status_code = 1;
SQL;
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($result AS $rs)
		{
			$return[$rs['Gender']] = (int)$rs['cnt'];
		}
		return $return;
	}

	private function generatePieChartLearnersCompletionStatus(PDO $link)
	{
		$a = array(
			'1' => 'Continuing'
			,'2' => 'Completed'
			,'3' => 'Withdrawn'
			,'4' => 'Transferred'
			,'6' => 'Temporarily Withdrawn'
		);
		$return = array();
		$sql = <<<SQL
SELECT
  status_code,
  COUNT(status_code) AS cnt
FROM
  tr
  INNER JOIN contracts
    ON tr.`contract_id` = contracts.`id`
WHERE contracts.`contract_year` = '{$this->current_contract_year}'
GROUP BY status_code ;
SQL;
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($result AS $rs)
		{
			$return[$a[$rs['status_code']]] = (int)$rs['cnt'];
		}
		return $return;
	}

	private function generatePieChartLearnersProgress(PDO $link)
	{
		$status = array();
		$status['Behind'] = 0;
		$status['On Track'] = 0;

		$view = HomePage::getInstance($link);
		$view->getFilter("filter_contract_year")->setValue($this->current_contract_year);
		$view->refresh($link, $_REQUEST);
		$sql = $view->getSQLStatement()->__toString();

		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($rows as $row)
		{
			if($row['status_code'] !=2 and $row['status_code'] !=3)
			{
				if(floatval($row['target']) >= 0 || floatval($row['percentage_completed']) >= 0)
				{
					if(floatval($row['percentage_completed']) < floatval($row['target']))
						$status['Behind']++;
					else
						$status['On Track']++;
				}
			}
		}
		return $status;
	}

	private function getLearnersInLearning(PDO $link)
	{

		$sql = <<<SQL
SELECT
	COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id
WHERE
	tr.status_code = '1' AND contracts.contract_year = '{$this->current_contract_year}';
SQL;
		return DAO::getSingleValue($link, $sql);
	}

	private function getEarlyLeavers(PDO $link)
	{

		$sql = <<<SQL
SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id
WHERE
	(tr.status_code = 3 OR tr.status_code=4) AND (TO_DAYS(tr.target_date) - TO_DAYS(tr.closure_date)) > 0 AND tr.closure_date >= contracts.start_date
	AND contracts.contract_year = '{$this->current_contract_year}'
SQL;
		return DAO::getSingleValue($link, $sql);
	}

	private function getAllLeavers(PDO $link)
	{

		$sql = <<<SQL
SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id
WHERE
	(tr.status_code = 3 OR tr.status_code=4) AND tr.closure_date >= contracts.start_date AND tr.closure_date <= contracts.end_date
	AND contracts.contract_year = '{$this->current_contract_year}'
SQL;
		return DAO::getSingleValue($link, $sql);
	}

	private function getAllLearners(PDO $link)
	{

		$sql = <<<SQL
SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id
WHERE
	contracts.contract_year = '{$this->current_contract_year}'
SQL;
		return DAO::getSingleValue($link, $sql);
	}

	private function getTimelyAchievers(PDO $link)
	{

		$sql = <<<SQL
SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id
WHERE
	status_code = 2	AND closure_date <= DATE_ADD(target_date, INTERVAL 90 DAY)
	AND tr.closure_date >= contracts.start_date AND tr.closure_date <= contracts.end_date
	AND contracts.contract_year = '{$this->current_contract_year}'
SQL;
		return DAO::getSingleValue($link, $sql);
	}

	private function getAchievers(PDO $link)
	{

		$sql = <<<SQL
SELECT COUNT(*) FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id
WHERE
	status_code = 2
	AND contracts.contract_year = '{$this->current_contract_year}'
SQL;
		return DAO::getSingleValue($link, $sql);
	}

	private function getBehindLearners(PDO $link)
	{

		$sql = <<<SQL
SELECT
  IF(tr.l36 IS NULL, 0, tr.l36) AS percentage_completed,
  IF(
    tr.target_date < CURDATE(),
    100,
    tr.target
  ) AS target
FROM
  tr
  INNER JOIN contracts
    ON tr.contract_id = contracts.id
WHERE contracts.contract_year = '{$this->current_contract_year}' AND tr.`status_code` = 1
HAVING percentage_completed < target ;
SQL;
		$r = DAO::getResultset($link, $sql);
		return count($r);
	}

	private function getOnTrackLearners(PDO $link)
	{

		$sql = <<<SQL
SELECT
  IF(tr.l36 IS NULL, 0, tr.l36) AS percentage_completed,
  IF(
    tr.target_date < CURDATE(),
    100,
    tr.target
  ) AS target
FROM
  tr
  INNER JOIN contracts
    ON tr.contract_id = contracts.id
WHERE contracts.contract_year = '{$this->current_contract_year}' AND tr.`status_code` = 1
HAVING percentage_completed >= target ;
SQL;
		$r = DAO::getResultset($link, $sql);
		return count($r);
	}
}