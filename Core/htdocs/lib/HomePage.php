<?php
class HomePage extends View
{
	public static function getStatsLearnersByProgression($contract_year, $progression_type)
	{
		$key = __CLASS__.'_stats_learners_by_progression_'.$progression_type;
		if(!isset($_SESSION[$key]))
		{
			$sql = new SQLStatement("
				SELECT
					IF(MONTH(second.start_date) >= 8, YEAR(second.start_date), YEAR(second.start_date)-1) AS second_start_year, COUNT(second.id) AS progressions
				FROM
					tr AS `first`
					INNER JOIN courses_tr AS first_courses_tr ON first_courses_tr.`tr_id` = first.`id`
					INNER JOIN courses AS first_courses ON first_courses.id = first_courses_tr.`course_id`
					INNER JOIN frameworks AS first_frameworks ON first_frameworks.id = first_courses.`framework_id`
					INNER JOIN tr AS `second` ON first.l03 = second.l03
					INNER JOIN courses_tr AS second_courses_tr ON second_courses_tr.`tr_id` = second.`id`
					INNER JOIN courses AS second_courses ON second_courses.id = second_courses_tr.`course_id`
					INNER JOIN frameworks AS second_frameworks ON second_frameworks.id = second_courses.`framework_id`
			");
			if($progression_type == 'L2L3')
			{
				$sql->setClause("WHERE first.status_code = '2'");
				$sql->setClause("WHERE first_frameworks.`framework_type` = '3'");
				$sql->setClause("WHERE first_frameworks.`framework_type` IS NOT NULL");
				$sql->setClause("WHERE second.`start_date` > first.`start_date`");
				$sql->setClause("WHERE second_frameworks.`framework_type` = '2'");
				$sql->setClause("WHERE second_frameworks.framework_type IS NOT NULL");
				$sql->setClause("GROUP BY second_start_year");
				$sql->setClause("HAVING second_start_year = '{$contract_year}'");
			}
			elseif($progression_type == 'L3L4')
			{
				$sql->setClause("WHERE first.status_code = '2'");
				$sql->setClause("WHERE first_frameworks.`framework_type` = '2'");
				$sql->setClause("WHERE first_frameworks.`framework_type` IS NOT NULL");
				$sql->setClause("WHERE second.`start_date` > first.`start_date`");
				$sql->setClause("WHERE second_frameworks.`framework_type` NOT IN (3,2)");
				$sql->setClause("WHERE second_frameworks.framework_type IS NOT NULL");
				$sql->setClause("GROUP BY second_start_year");
				$sql->setClause("HAVING second_start_year = '{$contract_year}'");
			}
			elseif($progression_type == 'TtoA')
			{
				$sql->setClause("WHERE first.status_code = '2'");
				$sql->setClause("WHERE first_frameworks.`framework_type` = '24'");
				$sql->setClause("WHERE first_frameworks.`framework_type` IS NOT NULL");
				$sql->setClause("WHERE second.`start_date` > first.`start_date`");
				$sql->setClause("WHERE second_frameworks.`framework_type` != '24' ");
				$sql->setClause("WHERE second_frameworks.framework_type IS NOT NULL");
				$sql->setClause("GROUP BY second_start_year");
				$sql->setClause("HAVING second_start_year = '{$contract_year}'");
			}
			elseif($progression_type == 'SP')
			{
				$sql->setClause("WHERE first.status_code != '1'");
				$sql->setClause("WHERE first_frameworks.`framework_type` IS NULL");
				$sql->setClause("WHERE first_frameworks.`framework_code` IS NULL");
				$sql->setClause("WHERE first.id IN (SELECT tr_id FROM ilr WHERE LOCATE('<FundModel>25</FundModel>',ilr) > 0 AND LOCATE('<LearnAimRef>ZPROG001</LearnAimRef>', ilr) = 0)");
				$sql->setClause("WHERE second.`start_date` > first.`start_date`");
				$sql->setClause("WHERE second_frameworks.`framework_type` = '24' ");
				$sql->setClause("GROUP BY second_start_year");
				$sql->setClause("HAVING second_start_year = '{$contract_year}'");
			}


			$view = $_SESSION[$key] = new HomePage();
			$view->setSQL($sql->__toString());
		}
		return $_SESSION[$key];
	}

	public static function getStatsLearners($stats_name, $contract_year)
	{
		$key = __CLASS__.'_'.$stats_name;
		if(!isset($_SESSION[$stats_name]))
		{
			if($stats_name == 'stats_learners_by_progress')
			{
				$sql = new SQLStatement("
					SELECT DISTINCT
						tr.id, IF(tr.l36 IS NULL, 0, tr.l36) AS percentage_completed,
						IF(tr.target_date < CURDATE(), 100, tr.target) AS target, tr.`status_code`
					FROM
					  tr INNER JOIN contracts ON tr.contract_id = contracts.id
					  LEFT JOIN group_members ON group_members.tr_id = tr.id
					  LEFT JOIN groups ON group_members.groups_id = groups.id
					  LEFT JOIN organisations AS employers ON tr.employer_id = employers.id
					  LEFT JOIN users AS assessors ON groups.assessor = assessors.id
					  LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
					  LEFT JOIN courses ON courses.id = courses_tr.course_id
					  LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
					  LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
				");
			}
			elseif($stats_name == 'stats_learners_by_status')
			{
				$sql = new SQLStatement("
					SELECT DISTINCT
						tr.id, tr.status_code, tr.target_date
					FROM
					  tr INNER JOIN contracts ON tr.contract_id = contracts.id
					  LEFT JOIN group_members ON group_members.tr_id = tr.id
					  LEFT JOIN groups ON group_members.groups_id = groups.id
					  LEFT JOIN organisations AS employers ON tr.employer_id = employers.id
					  LEFT JOIN users AS assessors ON groups.assessor = assessors.id
					  LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
					  LEFT JOIN courses ON courses.id = courses_tr.course_id
					  LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
					  LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
				");
			}
			elseif($stats_name == 'stats_learners_by_assessor')
			{
				$sql = new SQLStatement("
					SELECT DISTINCT
						tr.id, tr.status_code,
						IF(assessorsng.id IS NOT NULL, assessorsng.id, IF(assessors.id IS NOT NULL, assessors.id, '')) AS assessor_id
					FROM
					  tr INNER JOIN contracts ON tr.contract_id = contracts.id
					  LEFT JOIN group_members ON group_members.tr_id = tr.id
					  LEFT JOIN groups ON group_members.groups_id = groups.id
					  LEFT JOIN organisations AS employers ON tr.employer_id = employers.id
					  LEFT JOIN users AS assessors ON groups.assessor = assessors.id
					  LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
					  LEFT JOIN courses ON courses.id = courses_tr.course_id
					  LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
					  LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
				");
				$sql->setClause("HAVING assessor_id != '0' AND assessor_id != '' ");
			}
			elseif($stats_name == 'stats_learners_by_ethnicity')
			{
				$sql = new SQLStatement("
					SELECT DISTINCT
						tr.id, tr.ethnicity
					FROM
					  tr INNER JOIN contracts ON tr.contract_id = contracts.id
					  LEFT JOIN group_members ON group_members.tr_id = tr.id
					  LEFT JOIN groups ON group_members.groups_id = groups.id
					  LEFT JOIN organisations AS employers ON tr.employer_id = employers.id
					  LEFT JOIN users AS assessors ON groups.assessor = assessors.id
					  LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
					  LEFT JOIN courses ON courses.id = courses_tr.course_id
					  LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
					  LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
				");
			}
			elseif($stats_name == 'stats_learners_by_contract')
			{
				$sql = new SQLStatement("
					SELECT DISTINCT
						tr.id, tr.status_code, tr.contract_id, contracts.title
					FROM
					  tr INNER JOIN contracts ON tr.contract_id = contracts.id
					  LEFT JOIN group_members ON group_members.tr_id = tr.id
					  LEFT JOIN groups ON group_members.groups_id = groups.id
					  LEFT JOIN organisations AS employers ON tr.employer_id = employers.id
					  LEFT JOIN users AS assessors ON groups.assessor = assessors.id
					  LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
					  LEFT JOIN courses ON courses.id = courses_tr.course_id
					  LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
					  LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
				");
			}
			elseif($stats_name == 'stats_achievers_forecast')
			{
				$sql = new SQLStatement("
					SELECT COUNT(*) AS cnt,
  DATE_FORMAT(tr.`target_date`, '%M %Y') AS target_month,
  IF(
    tr.`closure_date` IS NULL,
    'C',
    (
      IF(
        ((tr.`closure_date` <= tr.`target_date`) OR (tr.`closure_date` BETWEEN tr.`target_date` AND DATE_ADD(tr.`target_date`, INTERVAL 90 DAY))),
        'T',
        (
          IF(
            tr.`closure_date` > DATE_ADD(tr.`target_date`, INTERVAL 90 DAY),
            'A',
            0
          )
        )
      )
    )
  ) AS ach_type
					FROM
					  tr INNER JOIN contracts ON tr.contract_id = contracts.id
					  LEFT JOIN group_members ON group_members.tr_id = tr.id
					  LEFT JOIN groups ON group_members.groups_id = groups.id
					  LEFT JOIN organisations AS employers ON tr.employer_id = employers.id
					  LEFT JOIN users AS assessors ON groups.assessor = assessors.id
					  LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
					  LEFT JOIN courses ON courses.id = courses_tr.course_id
					  LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
					  LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
				");
				$start_date = date('Y').'-08-01';
				$next_year = (int)date('Y')+1;
				$end_date = $next_year.'-07-31';
				$sql->setClause("WHERE tr.`status_code` IN (1, 2)");
				$sql->setClause("GROUP BY ach_type, target_month");
				$sql->setClause("ORDER BY tr.`target_date`");
				$sql->setClause("WHERE tr.`target_date` BETWEEN '{$start_date}' AND '{$end_date}'");
			}
			$sql->setClause("WHERE contracts.contract_year = '{$contract_year}'");

			if($_SESSION['user']->isAdmin())
			{
				$sql->setClause("WHERE 1 = 1");
			}
			elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER || $_SESSION['user']->type == User::TYPE_SCHOOL_VIEWER)
			{
				$sql->setClause("WHERE tr.provider_id = '{$_SESSION['user']->employer_id}' OR tr.employer_id = '{$_SESSION['user']->employer_id}')");
			}
			elseif($_SESSION['user']->type == User::TYPE_TUTOR)
			{
				$sql->setClause("WHERE (groups.tutor = '{$_SESSION['user']->id}' .  ' OR tr.tutor = '{$_SESSION['user']->id}') OR course_qualifications_dates.tutor_username = '{$_SESSION['user']->id}'");
			}
			elseif($_SESSION['user']->type == User::TYPE_ASSESSOR)
			{
				$sql->setClause("WHERE groups.assessor = '{$_SESSION['user']->id}' OR tr.assessor = '{$_SESSION['user']->id}'");
			}
			elseif($_SESSION['user']->type == User::TYPE_VERIFIER)
			{
				$sql->setClause("WHERE groups.verifier = '{$_SESSION['user']->id}'");
			}
			elseif($_SESSION['user']->type == User::TYPE_OTHER_LEARNER)
			{
				$sql->setClause("WHERE groups.wbcoordinator = '{$_SESSION['user']->id}'");
			}
			elseif($_SESSION['user']->type == User::TYPE_LEARNER)
			{
				$sql->setClause("WHERE tr.username = '{$_SESSION['user']->username}'");
			}
			elseif($_SESSION['user']->type == User::TYPE_SALESPERSON || $_SESSION['user']->type == User::TYPE_MANAGER)
			{
				$sql->setClause("WHERE tr.provider_id = '{$_SESSION['user']->employer_id}'");
			}
			elseif($_SESSION['user']->type == User::TYPE_SUPERVISOR)
			{
				if(DB_NAME=='am_ela')
					$sql->setClause("WHERE 1 = 1");
				else
					$sql->setClause("WHERE assessors.supervisor = '{$_SESSION['user']->username}' OR assessorsng.supervisor = '{$_SESSION['user']->username}'");
			}
			elseif($_SESSION['user']->type == User::TYPE_CONTRACT_MANAGER_2)
			{
				$sql->setClause("WHERE contracts.contract_holder = '{$_SESSION['user']->employer_id}'");
			}
			elseif($_SESSION['user']->type == User::TYPE_BRAND_MANAGER)
			{
				$sql->setClause("WHERE employers.manufacturer = '{$_SESSION['user']->department}'");
			}
			elseif($_SESSION['user']->type == User::TYPE_APPRENTICE_COORDINATOR)
			{
				$sql->setClause("WHERE tr.programme = '{$_SESSION['user']->id}'");
			}
			else
			{
				$sql->setClause("WHERE 1 = 2 ");
			}

			$view = $_SESSION[$key] = new HomePage();
			$view->setSQL($sql->__toString());

			$options = array(
				0 => array(1, 'All Contracts', null, null),
				1 => array(2, 'Funded Contracts', null, 'WHERE  contracts.funded = 1'),
				2 => array(3, 'Unfunded Contracts', null, 'WHERE contracts.funded <> 1'));
			$f = new DropDownViewFilter('filter_funded_contract', $options, 1, false);
			$f->setDescriptionFormat("Funded: %s");
			$view->addFilter($f);
		}
		return $_SESSION[$key];
	}

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==15)
			{
				$where = ' where tr.status_code=1';
			}
			elseif($_SESSION['user']->isOrgAdmin() || (int)$_SESSION['user']->type==13 || (int)$_SESSION['user']->type==14)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = ' where (tr.provider_id= '. $emp . ' or tr.employer_id = ' . $emp . ') and tr.status_code=1';
			}
			elseif((int)$_SESSION['user']->type==2)
			{
                $id = $_SESSION['user']->id;
				$where = ' where tr.status_code=1 and (groups.tutor = '. '"' . $id . '"' .  ' or tr.tutor ="' . $id . '") or course_qualifications_dates.tutor_username = ' . '"' . $id . '"';
			}
			elseif((int)$_SESSION['user']->type==3)
			{
                $id = $_SESSION['user']->id;
				$where = ' where tr.status_code=1 and (groups.assessor = '. '"' . $id . '" or tr.assessor="' . $id . '")';
			}
			elseif((int)$_SESSION['user']->type==4)
			{
				$id = $_SESSION['user']->id;
				$where = ' where tr.status_code=1 and groups.verifier = '. '"' . $id . '"';
			}
			elseif((int)$_SESSION['user']->type==6)
			{
				$id = $_SESSION['user']->id;
				$where = ' where tr.status_code=1 and groups.wbcoordinator = '. '"' . $id . '"';
			}
			elseif((int)$_SESSION['user']->type==5)
			{
				$username = $_SESSION['user']->username;
				$where = ' where tr.status_code=1 and tr.username = ' . '"' . $username . '"';
			}
			elseif((int)$_SESSION['user']->type==7)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = ' where tr.provider_id= '. $emp . ' and tr.status_code=1';
			}
			elseif((int)$_SESSION['user']->type==8)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = ' where tr.provider_id= '. $emp . ' and tr.status_code=1';
			}
			elseif($_SESSION['user']->type==9)
			{
				if(DB_NAME=='am_ela')
				{
					$where = ' where tr.status_code=1';
				}
				else
				{
					$username = $_SESSION['user']->username;
					$where = ' where tr.status_code =1 and (assessors.supervisor = "'. $username . '" or assessorsng.supervisor="' . $username . '")';
				}
			}
			elseif($_SESSION['user']->type==16)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = ' where contracts.contract_holder= '. $emp;
			}
			elseif($_SESSION['user']->type==18)
			{
				$supervisors = preg_replace('/([^,]+)/', '\'$1\'', $_SESSION['user']->supervisor);
				$assessors = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(\"\'\",id,\"\'\") FROM users WHERE supervisor in ($supervisors);");
				$where = ' where (groups.assessor in (' . $assessors . ') or tr.assessor in (' . $assessors . '))';
			}
			elseif($_SESSION['user']->type==19)
			{
				$brand = $_SESSION['user']->department;
				$where = " where employers.manufacturer = '$brand' and tr.status_code = 1";
			}
			elseif((int)$_SESSION['user']->type==20)
			{
				$id = $_SESSION['user']->id;
				$where = ' where tr.status_code=1 and tr.programme="' . $id . '"';
			}
            elseif((int)$_SESSION['user']->type==21)
            {
                $username = $_SESSION['user']->username;
                //$where = ' where (courses.director="' . $username . '")';
	            $where = ' where find_in_set("' . $username . '", courses.director) ';
            }
			else
			{
				$where = ' where tr.status_code = 1 and tr.employer_id = ' . $_SESSION['user']->employer_id;
			}

			$sql = <<<HEREDOC
#EXPLAIN
SELECT DISTINCT
  tr.id,
  IF(tr.l36 IS NULL, 0, tr.l36) AS percentage_completed,
  IF(
    tr.target_date < CURDATE(),
    100,
    tr.target
  ) AS target,
  tr.`status_code`
FROM
  tr
  LEFT JOIN organisations AS employers ON tr.employer_id = employers.id
  LEFT JOIN group_members ON group_members.tr_id = tr.id
  LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
  LEFT JOIN courses ON courses.id = courses_tr.course_id
  LEFT JOIN groups ON group_members.groups_id = groups.id
  LEFT JOIN users AS assessors ON groups.assessor = assessors.id
  LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
  LEFT JOIN contracts ON contracts.id = tr.contract_id
  LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
    $where;
HEREDOC;
			// Create new view object

			$view = $_SESSION[$key] = new HomePage();
			$view->setSQL($sql);

			//pre($sql);
			// Add progress filter
			$options = array(
				0=>array(0, 'Show all', null, null),
				//1=>array(1, 'On track', null, 'WHERE framework_percentage >= target_status'),
				//2=>array(2, 'Behind', null, 'WHERE `framework_percentage` < `target_status`'));
				1=>array(1, 'On track', null, 'having target is null or percentage_completed >= target'),
				2=>array(2, 'Behind', null, 'having percentage_completed < target'));
			$f = new DropDownViewFilter('filter_progress', $options, 0, false);
			$f->setDescriptionFormat("Progress: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), NULL, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts ORDER BY contract_year DESC";
			$f = new DropDownViewFilter('filter_contract_year', $options, null, true);
			$f->setDescriptionFormat("Contract Year: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Learner (asc), Start date (asc)', null,
					'ORDER BY tr.surname ASC, tr.firstnames ASC, tr.start_date ASC'),
				1=>array(2, 'Leaner (desc), Start date (desc), Course (desc)', null,
					'ORDER BY tr.surname DESC, tr.firstnames DESC, tr.start_date DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);


			// Add preferences
			$view->setPreference('showAttendanceStats', '0');
			$view->setPreference('showProgressStats', '1');
		}
//pre($view->getSQL());
		return $_SESSION[$key];
	}


	public function save_graph_data(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());

		//pre($this->getSQL());
		if($st)
		{
			// Delete graph data
			$sql = "delete from graph_data";
			DAO::execute($link, $sql);


			while($row = $st->fetch())
			{

				if($row['target']>=0 || $row['percentage_completed']>=0)
					if($row['percentage_completed']<$row['target'])
						$value = "Behind";
					else
						$value = "On Track";
				else
					$value = "Behind";

				//$value = $row['description'];
				$sql2 = "insert into graph_data (description, value) values('$value','1')";
				DAO::execute($link, $sql2);
			}
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}

	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st)
		{

			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Progress Status</th><th>Learners</th></tr></thead>';
			echo '<tbody>';

			// display data table
			$sqlnew = "select description, count(value) as total from graph_data group by description";
			$st = $link->query($sqlnew);
			if($st)
			{
				$sum = 0;
				while($rownew = $st->fetch())
				{
					echo '<td>&nbsp</td>';
					echo '<td align="left">' . HTML::cell($rownew['description']) . "</td>";
					echo '<td align="center">' . HTML::cell($rownew['total']) . "</td>";
					echo '</tr>';
					$sum += (int)$rownew['total'];
				}

				echo '<td>&nbsp</td>';
				echo '<td align="left">' . HTML::cell("Total") . "</td>";
				echo '<td align="center">' . HTML::cell($sum) . "</td>";


				echo '</tbody></table></div>';
			}

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}

	public static function format_size($size)
	{
		if ($size == 0) {
			return 0;
		}
		else {
			return round( ($size / 1024) / 1024, 1);
		}
	}

	public static function renderOtjProgressPanel(PDO $link)
    {
        $sql = <<<SQL
SELECT 
    tr.id AS tr_id,
    IF
	(
		tr.`otj_hours` = 0, '', 
		IF
		(
			(SELECT SUM(duration_hours)*60 + SUM(duration_minutes) FROM otj WHERE tr_id = tr.id) >=
			( tr.`otj_hours`/(timestampdiff(MONTH, tr.`start_date`, tr.`target_date`)) * timestampdiff(MONTH, tr.start_date, CURDATE()))
			,
			'On Track','Behind'
		)
	) AS otj_progress
FROM
    tr INNER JOIN contracts ON tr.contract_id = contracts.id
WHERE
    contracts.contract_year = '2022'
;    
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $on_track = [];
        $behind = [];
        foreach($records AS $row)
        {
            if($row['otj_progress'] == 'On Track')
                $on_track[] = $row['tr_id'];
            else
                $behind[] = $row['tr_id'];
        }

        $total = count($on_track) + count($behind);
        $number_on_track = count($on_track);
        $number_behind = count($behind);
        $percentage_on_track = round( ($number_on_track/$total)*100, 2 );
        $percentage_behind = round( ($number_behind/$total)*100, 2 );

        $html = <<<HTML
<div class="col-lg-8">
    <div class="box box-primary" style="max-height: 350px;">
        <div class="box-header with-border">
            <h3 class="box-title"><span class="fa fa-pie-chart"></span> Learners by OTJ Progress (2022 - 2023)</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="chart-responsive">
                        <canvas style="display: block;" id="pieChartLearnerOtjProgress" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_otj_progress=1">On Track<span class="pull-right text-red">{$percentage_on_track}%</span></a></li>
                <li><a href="do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_otj_progress=2">Behind<span class="pull-right text-red">{$percentage_behind}%</span></a></li>
            </ul>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-body">
            <div id="pieBootcampOutcome"></canvas>
        </div>
        
    </div>
</div>
HTML;

        return $html;
    }

    public static function renderOtjProgressPanelJs(PDO $link)
    {
        $sql = <<<SQL
SELECT 
    tr.id AS tr_id,
    IF
	(
		tr.`otj_hours` = 0, '', 
		IF
		(
			(SELECT SUM(duration_hours)*60 + SUM(duration_minutes) FROM otj WHERE tr_id = tr.id) >=
			( tr.`otj_hours`/(timestampdiff(MONTH, tr.`start_date`, tr.`target_date`)) * timestampdiff(MONTH, tr.start_date, CURDATE()))
			,
			'On Track','Behind'
		)
	) AS otj_progress
FROM
    tr INNER JOIN contracts ON tr.contract_id = contracts.id
WHERE
    contracts.contract_year = '2020'
;    
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $on_track = [];
        $behind = [];
        foreach($records AS $row)
        {
            if($row['otj_progress'] == 'On Track')
                $on_track[] = $row['tr_id'];
            else
                $behind[] = $row['tr_id'];
        }

        $total = count($on_track) + count($behind);
        $number_on_track = count($on_track);
        $number_behind = count($behind);
        $percentage_on_track = round( ($number_on_track/$total)*100, 2 );
        $percentage_behind = round( ($number_behind/$total)*100, 2 );

        $js = <<<JS
var _pcc = $("#pieChartLearnerOtjProgress").get(0).getContext("2d");
var _pclp = new Chart(_pcc);
var _pd = [{value: {$percentage_on_track},color: "lightgreen",label: "On Track"},{value: {$percentage_behind},color: "red",label: "Behind"}];
var _po = {
    percentageInnerCutout: 0, // This is 0 for Pie charts
    animationSteps: 100,
    animationEasing: "easeOutBounce",
    animateRotate: true,
    animateScale: false,
    responsive: true,
    maintainAspectRatio: false,
    tooltipEvents: [],
    showTooltips: true,
    onAnimationComplete: function() {
        this.showTooltip(this.segments, true);
    },
    tooltipTemplate: "<%= label %> - <%= value %>"
};
_pclp.Doughnut(_pd, _po);

JS;

        return $js;
    }

        public static function renderBehindProgressBarPanel(PDO $link)
    {
        $sql = <<<SQL
SELECT 
    tr.id AS tr_id
    ,ROUND(IF(tr.l36 IS NULL, 0, tr.l36)) AS percentage_completed
    ,ROUND(IF(tr.target_date < CURDATE(),100,tr.target)) AS target
FROM
    tr INNER JOIN contracts ON tr.contract_id = contracts.id
WHERE
    contracts.contract_year = '2020'
;    
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $data = [
            '0-10' => 0,
            '11-25' => 0,
            '26-50' => 0,
            '50Plus' => 0,
        ];
        foreach($records AS $row)
        {
            if($row['percentage_completed'] >= $row['target'])
                continue;

            $progress = $row['target'] - $row['percentage_completed'];
            $progress = round($progress, 0);
            if($progress >= 0 && $progress <= 10 )
                $data['0-10'] += 1;
            elseif($progress > 10 && $progress <= 25 )
                $data['11-25'] += 1;
            elseif($progress > 25 && $progress <= 50 )
                $data['26-50'] += 1;
            elseif($progress > 50 )
                $data['50Plus'] += 1;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => ''];
        $options->plotOptions = (object)[
            'pie' => (object )[
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => (object)[
                    'enabled' => true,
                    'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
                ],
                'showInLegend' => true
            ]
        ];
        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->colorByPoint = true;
        $series->data = [];
        foreach($data AS $key => $value)
        {
            $d = new stdClass();
            $d->name = $key;
            $d->y = $value;
            $d->key = $key;
            $series->data[] = $d;
        }
        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public static function GatewayLearnersBarChart(PDO $link)
    {
        $sql = <<<SQL
SELECT 
    tr.id AS tr_id
    ,timestampdiff(MONTH, CURDATE(), tr_epa.`epa_prop_date1`)  AS month_left
FROM
    tr INNER JOIN contracts ON tr.contract_id = contracts.id INNER JOIN tr_epa ON tr.`id` = tr_epa.`tr_id`
WHERE
    contracts.contract_year = '2020' 
;    
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $data = [
            '0' => 0,
            '1' => 0,
            '2' => 0,
            '3-5' => 0,
            '6-10' => 0,
            '10Plus' => 0,
        ];
        foreach($records AS $row)
        {
            if($row['month_left'] == 0 )
                $data['0'] += 1;
            elseif($row['month_left'] == 1 )
                $data['1'] += 1;
            elseif($row['month_left'] == 2 )
                $data['2'] += 1;
            elseif($row['month_left'] >= 3 && $row['month_left'] <= 5 )
                $data['3-5'] += 1;
            elseif($row['month_left'] >= 6 && $row['month_left'] <= 10 )
                $data['6-10'] += 1;
            elseif($row['month_left'] > 10 )
                $data['10Plus'] += 1;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => ''];
        $options->plotOptions = (object)[
            'pie' => (object )[
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => (object)[
                    'enabled' => true,
                    'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
                ],
                'showInLegend' => true
            ]
        ];
        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->colorByPoint = true;
        $series->data = [];
        foreach($data AS $key => $value)
        {
            $d = new stdClass();
            $d->name = $key;
            $d->y = $value;
            $d->key = $key;
            $series->data[] = $d;
        }
        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public static function ReviewsGraph(PDO $link)
    {
        $sql = <<<SQL
SELECT comments,COUNT(*) AS cnt FROM assessor_review WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1) GROUP BY comments  
;    
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $data = [
            'green' => 0,
            'red' => 0,
            'yellow' => 0,
            'no' => 0,

        ];
        foreach($records AS $row)
        {
            if($row['comments'] == 'green' )
                $data['green'] = $row['cnt'];
            elseif($row['comments'] == 'red' )
                $data['red'] = $row['cnt'];
            elseif($row['comments'] == 'yellow' )
                $data['yellow'] = $row['cnt'];
            elseif($row['comments'] == 'no' )
                $data['no'] = $row['cnt'];
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => ''];
        $options->plotOptions = (object)[
            'column' => (object )[
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => (object)[
                    'enabled' => true,
                    'format' => '<b>{point.name}</b>: {point.y} '
                ],
                'showInLegend' => true
            ]
        ];
        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->colorByPoint = true;
        $series->data = [];
        foreach($data AS $key => $value)
        {
            $d = new stdClass();
            $d->name = $key;
            $d->y = $value;
            $d->key = $key;
            $series->data[] = $d;
        }
        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

}
?>