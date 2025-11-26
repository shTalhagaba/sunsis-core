<?php
class epa_dashboard implements IAction
{
	public function execute(PDO $link)
	{
		$start_month = isset($_REQUEST['start_month']) ? $_REQUEST['start_month'] : 1;
		$end_month = isset($_REQUEST['end_month']) ? $_REQUEST['end_month'] : (int)date('m');
		$start_year = isset($_REQUEST['start_year']) ? $_REQUEST['start_year'] : date('Y');
		$end_year = isset($_REQUEST['end_year']) ? $_REQUEST['end_year'] : date('Y');

		$start_date = $start_year . '-' . str_pad($start_month, 2, '0', STR_PAD_LEFT) . '-01';
		$end_date = $end_year . '-' . str_pad($end_month, 2, '0', STR_PAD_LEFT) . '-01';
		$end_date = date("Y-m-t", strtotime($end_date));

		$months = [
			[1, "January"],
			[2, "February"],
			[3, "March"],
			[4, "April"],
			[5, "May"],
			[6, "June"],
			[7, "July"],
			[8, "August"],
			[9, "September"],
			[10, "October"],
			[11, "November"],
			[12, "December"]
		];
		$years = [];
		for($i = 2015; $i <= (int)date('Y'); $i++)
		{
			$years[] = [$i, $i];
		}

		$gradesTotals = [
			"distinction" => [],
			"merit" => [],
			"pass" => [],
			"fail" => [],
			"resit_pass" => [],
			"resit_fail" => []
		];
		$grades = ["distinction", "merit", "pass", "fail", "resit_pass", "resit_fail"];

		$by_programme = $this->byProgramme($link, $start_date, $end_date);
		$programmes = $by_programme->programmes;
		$grand_total = $by_programme->grand_total;
		$gradesTotals = $by_programme->gradesTotals;
		$by_programme = null;

		$by_assessor = $this->byAssessor($link, $start_date, $end_date);
		$assessors = $by_assessor->assessors;
		$grand_total_a = $by_assessor->grand_total;
		$gradesTotals_a = $by_assessor->gradesTotals;
		$by_assessor = null;

		$by_supervisor = $this->bySupervisor($link, $start_date, $end_date);
		$supervisors = $by_supervisor->supervisors;
		$grand_total_p = $by_supervisor->grand_total;
		$gradesTotals_p = $by_supervisor->gradesTotals;
		$by_supervisor = null;

		//$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=epa_dashboard", "EPA Results Dashboard");

		include_once('tpl_epa_dashboard.php');
	}

	public function m_name($m)
	{
		$months = [
			1 => "January",
			2 => "February",
			3 => "March",
			4 => "April",
			5 => "May",
			6 => "June",
			7 => "July",
			8 => "August",
			9 => "September",
			10 => "October",
			11 => "November",
			12 => "December"
		];
		return $months[$m];
	}

	public function byProgramme(PDO $link, $start_date, $end_date)
	{
		$programmes = [];
		$gradesTotals = [
			"distinction" => [],
			"merit" => [],
			"pass" => [],
			"fail" => [],
			"resit_pass" => [],
			"resit_fail" => []
		];
		$grades = ["distinction", "merit", "pass", "fail", "resit_pass", "resit_fail"];
		$grand_total = 0;
		$sql = <<<SQL
SELECT frameworks.`short_name` AS programme, op_epa.* FROM am_baltic.op_epa INNER JOIN am_baltic.student_frameworks ON op_epa.tr_id = student_frameworks.`tr_id`
INNER JOIN am_baltic.frameworks ON student_frameworks.`id` = frameworks.`id`
WHERE op_epa.task = 8 AND IF(task_actual_date IS NULL, op_epa.task_date BETWEEN '{$start_date}' AND '{$end_date}', op_epa.task_actual_date BETWEEN '{$start_date}' AND '{$end_date}');
SQL;
		//pre($sql);
		$results = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($results AS $row)
		{
			if(!isset($programmes[$row['programme']]))
			{
				$programme = new stdClass();
				$programme->title = $row['programme'];
				$programme->distinction = [];
				$programme->merit = [];
				$programme->pass = [];
				$programme->fail = [];
				$programme->resit_pass = [];
				$programme->resit_fail = [];
				$programme->total = [];
				$programme->distinction_merit_pass = [];
				$programme->resit_pass_resit_fail = [];

				$programmes[$row['programme']] = $programme;
			}

			switch($row['task_status'])
			{
				case '16'://Passed
					if($row['task_type'] == '2')
					{
						$programmes[$row['programme']]->resit_pass[] = $row['id'];
						$gradesTotals["resit_pass"][] = $row['id'];
					}
					else
					{
						$programmes[$row['programme']]->pass[] = $row['id'];
						$gradesTotals["pass"][] = $row['id'];
					}
					break;
				case 19://Fail
					if($row['task_type'] == '2')
					{
						$programmes[$row['programme']]->resit_fail[] = $row['id'];
						$gradesTotals["resit_fail"][] = $row['id'];
					}
					else
					{
						$programmes[$row['programme']]->fail[] = $row['id'];
						$gradesTotals["fail"][] = $row['id'];
					}
					break;
				case 18://Distinction
					$programmes[$row['programme']]->distinction[] = $row['id'];
					$gradesTotals["distinction"][] = $row['id'];
					break;
				case 17://Merit
					$programmes[$row['programme']]->merit[] = $row['id'];
					$gradesTotals["merit"][] = $row['id'];
					break;
			}

			$programmes[$row['programme']]->total[] = $row['id'];
			$programmes[$row['programme']]->distinction_merit_pass[] = count($programmes[$row['programme']]->distinction) + count($programmes[$row['programme']]->merit) + count($programmes[$row['programme']]->pass);
			$programmes[$row['programme']]->resit_pass_resit_fail[] = count($programmes[$row['programme']]->resit_pass) + count($programmes[$row['programme']]->resit_fail);

			$grand_total++;
		}

		$fnResult = new stdClass();
		$fnResult->programmes = $programmes;
		$fnResult->grand_total = $grand_total;
		$fnResult->gradesTotals = $gradesTotals;

		return $fnResult;
	}

	public function byAssessor(PDO $link, $start_date, $end_date)
	{
		$assessors = [];
		$gradesTotals = [
			"distinction" => [],
			"merit" => [],
			"pass" => [],
			"fail" => [],
			"resit_pass" => [],
			"resit_fail" => []
		];
		$grades = ["distinction", "merit", "pass", "fail", "resit_pass", "resit_fail"];
		$grand_total = 0;
		$sql = <<<SQL
SELECT (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.assessor) AS assessor, op_epa.*
FROM am_baltic.op_epa INNER JOIN am_baltic.tr ON op_epa.tr_id = tr.`id`
WHERE op_epa.task = 8 AND IF(task_actual_date IS NULL, op_epa.task_date BETWEEN '{$start_date}' AND '{$end_date}', op_epa.task_actual_date BETWEEN '{$start_date}' AND '{$end_date}');
SQL;
		//pre($sql);
		$results = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($results AS $row)
		{
			$row['assessor'] = $row['assessor'] == '' ? 'NotAllocated' : $row['assessor'];
			if(!isset($assessors[$row['assessor']]))
			{
				$assessor = new stdClass();
				$assessor->title = $row['assessor'];
				$assessor->distinction = [];
				$assessor->merit = [];
				$assessor->pass = [];
				$assessor->fail = [];
				$assessor->resit_pass = [];
				$assessor->resit_fail = [];
				$assessor->total = [];
				$assessor->distinction_merit_pass = [];
				$assessor->resit_pass_resit_fail = [];

				$assessors[$row['assessor']] = $assessor;
			}

			switch($row['task_status'])
			{
				case '16'://Passed
					if($row['task_type'] == '2')
					{
						$assessors[$row['assessor']]->resit_pass[] = $row['id'];
						$gradesTotals["resit_pass"][] = $row['id'];
					}
					else
					{
						$assessors[$row['assessor']]->pass[] = $row['id'];
						$gradesTotals["pass"][] = $row['id'];
					}
					break;
				case 19://Fail
					if($row['task_type'] == '2')
					{
						$assessors[$row['assessor']]->resit_fail[] = $row['id'];
						$gradesTotals["resit_fail"][] = $row['id'];
					}
					else
					{
						$assessors[$row['assessor']]->fail[] = $row['id'];
						$gradesTotals["fail"][] = $row['id'];
					}
					break;
				case 18://Distinction
					$assessors[$row['assessor']]->distinction[] = $row['id'];
					$gradesTotals["distinction"][] = $row['id'];
					break;
				case 17://Merit
					$assessors[$row['assessor']]->merit[] = $row['id'];
					$gradesTotals["merit"][] = $row['id'];
					break;
			}

			$assessors[$row['assessor']]->total[] = $row['id'];
			$assessors[$row['assessor']]->distinction_merit_pass[] = count($assessors[$row['assessor']]->distinction) + count($assessors[$row['assessor']]->merit) + count($assessors[$row['assessor']]->pass);
			$assessors[$row['assessor']]->resit_pass_resit_fail[] = count($assessors[$row['assessor']]->resit_pass) + count($assessors[$row['assessor']]->resit_fail);

			$grand_total++;
		}

		$fnResult = new stdClass();
		$fnResult->assessors = $assessors;
		$fnResult->grand_total = $grand_total;
		$fnResult->gradesTotals = $gradesTotals;

		return $fnResult;
	}

	public function bySupervisor(PDO $link, $start_date, $end_date)
	{
		$supervisors = [];
		$gradesTotals = [
			"distinction" => [],
			"merit" => [],
			"pass" => [],
			"fail" => [],
			"resit_pass" => [],
			"resit_fail" => []
		];
		$grades = ["distinction", "merit", "pass", "fail", "resit_pass", "resit_fail"];
		$grand_total = 0;
		$sql = <<<SQL
SELECT
  (SELECT
    CONCAT(supervisors.firstnames, ' ', supervisors.surname)
  FROM
    users AS supervisors
    LEFT JOIN users AS assessors ON supervisors.username = assessors.supervisor
  WHERE assessors.id = tr.assessor) AS supervisor,
  op_epa.*
FROM
  am_baltic.op_epa
  INNER JOIN am_baltic.tr
    ON op_epa.tr_id = tr.`id`
WHERE op_epa.task = 8
  AND IF(
    task_actual_date IS NULL,
    op_epa.task_date BETWEEN '{$start_date}' AND '{$end_date}',
    op_epa.task_actual_date BETWEEN '{$start_date}' AND '{$end_date}'
  ) ;
SQL;
		//pre($sql);
		$results = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		foreach($results AS $row)
		{
			$row['supervisor'] = $row['supervisor'] == '' ? 'NotAllocated' : $row['supervisor'];
			if(!isset($supervisors[$row['supervisor']]))
			{
				$supervisor = new stdClass();
				$supervisor->title = $row['supervisor'];
				$supervisor->distinction = [];
				$supervisor->merit = [];
				$supervisor->pass = [];
				$supervisor->fail = [];
				$supervisor->resit_pass = [];
				$supervisor->resit_fail = [];
				$supervisor->total = [];
				$supervisor->distinction_merit_pass = [];
				$supervisor->resit_pass_resit_fail = [];

				$supervisors[$row['supervisor']] = $supervisor;
			}

			switch($row['task_status'])
			{
				case '16'://Passed
					if($row['task_type'] == '2')
					{
						$supervisors[$row['supervisor']]->resit_pass[] = $row['id'];
						$gradesTotals["resit_pass"][] = $row['id'];
					}
					else
					{
						$supervisors[$row['supervisor']]->pass[] = $row['id'];
						$gradesTotals["pass"][] = $row['id'];
					}
					break;
				case 19://Fail
					if($row['task_type'] == '2')
					{
						$supervisors[$row['supervisor']]->resit_fail[] = $row['id'];
						$gradesTotals["resit_fail"][] = $row['id'];
					}
					else
					{
						$supervisors[$row['supervisor']]->fail[] = $row['id'];
						$gradesTotals["fail"][] = $row['id'];
					}
					break;
				case 18://Distinction
					$supervisors[$row['supervisor']]->distinction[] = $row['id'];
					$gradesTotals["distinction"][] = $row['id'];
					break;
				case 17://Merit
					$supervisors[$row['supervisor']]->merit[] = $row['id'];
					$gradesTotals["merit"][] = $row['id'];
					break;
			}

			$supervisors[$row['supervisor']]->total[] = $row['id'];
			$supervisors[$row['supervisor']]->distinction_merit_pass[] = count($supervisors[$row['supervisor']]->distinction) + count($supervisors[$row['supervisor']]->merit) + count($supervisors[$row['supervisor']]->pass);
			$supervisors[$row['supervisor']]->resit_pass_resit_fail[] = count($supervisors[$row['supervisor']]->resit_pass) + count($supervisors[$row['supervisor']]->resit_fail);

			$grand_total++;
		}

		$fnResult = new stdClass();
		$fnResult->supervisors = $supervisors;
		$fnResult->grand_total = $grand_total;
		$fnResult->gradesTotals = $gradesTotals;

		return $fnResult;
	}
}
