<?php
class ajax_home_page implements IAction
{
	public $contract_year = null;
	public function execute( PDO $link )
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$this->contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC  LIMIT 1;");

		if($subaction != '' && $subaction == 'load_completion_progression')
		{
			$this->load_completion_progression($link);
			exit;
		}
	}

	private function load_completion_progression(PDO $link)
	{
		$_where = "";
		if(!$_SESSION['user']->isAdmin())
			$_where = " AND tr.`provider_id` IN ({$_SESSION['user']->employer_id})";

		$contract_year = $this->contract_year;
		$sql = <<<SQL
SELECT
	COUNT(*)
FROM
	tr INNER JOIN contracts ON tr.contract_id = contracts.id
WHERE
	contract_year = '$contract_year' AND tr.status_code = '2' $_where
SQL;
		$learners_completed = DAO::getSingleValue($link, $sql);
		$l2l3_progressions = $this->getL2L3Progressions($link);
		$l3l4_progressions = $this->getL3L4Progressions($link);

		$submission = DAO::getSingleValue($link, "SELECT submission AS submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1;");
		$trs = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr_id) FROM ilr WHERE submission = '$submission' AND contract_id IN (SELECT id FROM contracts WHERE contract_year = (SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 1)) AND EXTRACTVALUE(ilr, '/Learner/LearningDelivery[AimType=1]/Outcome')=8");
		//$ttoa_progressions = $this->getTtoAProgressions($link);
		$epa = sizeof(explode(",",$trs?:''));
		$study_progressions = $this->getStudyProgressions($link);

		$panel3 = <<<PANEL3
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="small-box bg-aqua">
			<div class="inner">
				<h3>$l3l4_progressions</h3>
				<p>Progressions Level 3 to Level 4</p>
			</div>
			<div class="icon"><i class="fa fa-line-chart"></i></div>
			<a href="do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=1&ViewL2L3Progression_filter_second_contract_year=$contract_year" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
</div>
PANEL3;
		if(DB_NAME == "am_crackerjack")
		{
			$panel3 = <<<PANEL3
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="small-box bg-aqua">
			<div class="inner">
				<h3>$study_progressions</h3>
				<p>Progressions Study Prog: to Traineeship</p>
			</div>
			<div class="icon"><i class="fa fa-line-chart"></i></div>
			<a href="do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=1&ViewL2L3Progression_filter_second_contract_year=$contract_year" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
</div>
PANEL3;
		}


		echo <<<HTML
<div class="row">
	<div class="col-lg-6 col-xs-6">
		<div class="small-box bg-green">
			<div class="inner">
				<h3>$learners_completed</h3>
				<p>Learners completed</p>
			</div>
			<div class="icon"><i class="fa fa-graduation-cap"></i></div>
			<a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=2&ViewTrainingRecords_filter_contract_year=$contract_year" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>

	<div class="col-lg-6 col-xs-6">
		<div class="small-box bg-green">
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3>$l2l3_progressions</h3>
					<p>Progressions Level 2 to Level 3</p>
				</div>
				<div class="icon"><i class="fa fa-line-chart"></i></div>
				<a href="do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=0&ViewL2L3Progression_filter_second_contract_year=$contract_year" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
	</div>

	$panel3

	<div class="col-lg-6 col-xs-6">
		<div class="small-box bg-green">
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3>$epa</h3>
					<p>Learners at EPA</p>
				</div>
				<div class="icon"><i class="fa fa-line-chart"></i></div>
				<a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_tr_ids=$trs" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
	</div>

</div>
HTML;


	}

	private function getL2L3Progressions(PDO $link)
	{
		$contract_year = $this->contract_year;
		$sql = <<<SQL

SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id) AS cnt
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
WHERE first.status_code = 2 AND first_frameworks.`framework_type` = 3 AND first_frameworks.`framework_type` IS NOT NULL
AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` = 2 AND second_frameworks.framework_type IS NOT NULL
GROUP BY second_start_year
HAVING second_start_year = '$contract_year';

SQL;
		$res = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(isset($res[0]['cnt']))
			return $res[0]['cnt'] == ''?0:$res[0]['cnt'];
		else
			return 0;
	}

	private function getL3L4Progressions(PDO $link)
	{
		$contract_year = $this->contract_year;
		$sql = <<<SQL

SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id) AS cnt
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
WHERE first.status_code = 2 AND first_frameworks.`framework_type` = 2 AND first_frameworks.`framework_type` IS NOT NULL
AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` NOT IN (3,2) AND second_frameworks.framework_type IS NOT NULL
GROUP BY second_start_year
HAVING second_start_year = '$contract_year';

SQL;
		$res = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(isset($res[0]['cnt']))
			return $res[0]['cnt'] == ''?0:$res[0]['cnt'];
		else
			return 0;
	}

	private function getTtoAProgressions(PDO $link)
	{
		$contract_year = $this->contract_year;
		$sql = <<<SQL

SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id) AS cnt
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
WHERE first.status_code = 2 AND first_frameworks.`framework_type` = 24 AND first_frameworks.`framework_type` IS NOT NULL
AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` NOT IN (24) AND second_frameworks.framework_type IS NOT NULL
GROUP BY second_start_year
HAVING second_start_year = '$contract_year';

SQL;
		$res = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(isset($res[0]['cnt']))
			return $res[0]['cnt'] == ''?0:$res[0]['cnt'];
		else
			return 0;
	}

	private function getStudyProgressions(PDO $link)
	{
		$contract_year = $this->contract_year;
		$sql = <<<SQL
SELECT IF(MONTH(second.start_date)>=8,YEAR(second.start_date),YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id) as cnt
FROM tr AS `first`
INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
INNER JOIN tr AS `second` ON first.l03 = second.l03
INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
WHERE first.status_code != 1 AND first_frameworks.`framework_type` IS NULL  AND first_frameworks.`framework_code` IS NULL
AND first.id IN (SELECT tr_id FROM ilr WHERE LOCATE('<FundModel>25</FundModel>',ilr)>0 AND LOCATE('<LearnAimRef>ZPROG001</LearnAimRef>',ilr)=0)
AND second.`start_date` > first.`start_date` AND second_frameworks.`framework_type` = 24
GROUP BY second_start_year
HAVING second_start_year = '$contract_year'
SQL;

		$res = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(isset($res[0]['cnt']))
			return $res[0]['cnt'] == ''?0:$res[0]['cnt'];
		else
			return 0;
	}
}