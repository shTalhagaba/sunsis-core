<?php
class induction_dashboard implements IAction
{
    public function execute(PDO $link)
    {
        $current_month = date('F Y');

        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        if($subaction == 'showInductionTable')
        {
            echo $this->showInductionTable1($link);
            exit;
        }
        if($subaction == 'showInductionDashPanels')
        {
            echo $this->showInductionDashPanels($link);
            exit;
        }
        if($subaction == 'showInductionByEmployer')
        {
            echo $this->showInductionByEmployer($link);
            exit;
        }
        if($subaction == 'getStatsLearnersByAssessors')
        {
            $this->getStatsLearnersByAssessors($link);
            exit;
        }
        if($subaction == 'navToTRSummary')
        {
            $this->navToTRSummary($link);
            exit;
        }
        if($subaction == 'navToInduction')
        {
            $this->navToInduction($link);
            exit;
        }

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=induction_dashboard", "Induction Dashboard");

        $first_date = date('Y-m-d',strtotime("first day of this month"));
        $last_date = date('Y-m-d',strtotime("last day of this month"));
        /*
                $sql = <<<SQL
        SELECT
          induction.`assigned_assessor`,
          COUNT(*) AS newly_signed
        FROM
          induction
          INNER JOIN inductees ON induction.`inductee_id` = inductees.id
          LEFT JOIN tr ON inductees.`sunesis_username` = tr.`username`
        WHERE
          tr.`username` IS NULL
          #AND induction.induction_date >= '$first_date'
          #AND induction.induction_date <= '$last_date'
        GROUP BY induction.`assigned_assessor`
        ;
        SQL;
                $assAssessors = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                foreach($assAssessors AS &$row)
                {
                    $a_id = $row['assigned_assessor'];
                    $sql = <<<SQL
        SELECT
          COUNT(*) AS cnt
        FROM
          induction
          INNER JOIN inductees ON induction.`inductee_id` = inductees.id
          LEFT JOIN tr ON inductees.`sunesis_username` = tr.`username`
        WHERE tr.`username` IS NOT NULL #AND induction.induction_date >= '$first_date' AND induction.induction_date <= '$last_date' 
        AND induction.assigned_assessor = '$a_id'
        ;
        SQL;
                    $row['on_prog'] = (int)DAO::getSingleValue($link, $sql);
                    $sql = <<<SQL
        SELECT
          COUNT(*) AS cnt
        FROM
          induction
          INNER JOIN inductees ON induction.`inductee_id` = inductees.id
          LEFT JOIN tr ON inductees.`sunesis_username` = tr.`username`
        WHERE tr.`username` IS NOT NULL #AND induction.induction_date >= '$first_date' AND induction.induction_date <= '$last_date' 
        AND induction.assigned_assessor = '$a_id' AND induction.`planned_end_date` BETWEEN CURDATE() AND NOW() + INTERVAL 30 DAY
        ;
        SQL;
                    $row['comp_due'] = (int)DAO::getSingleValue($link, $sql);
                }
        
                foreach($assAssessors AS &$arr)
                    foreach($arr AS $key => &$value)
                        if($key == 'assigned_assessor')
                            $value = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$value}'");
        
                $aAssessorNames = array();
                $a_newly_signed = array();
                $a_on_prog = array();
                $a_comp_due = array();
        
                for($i = 0; $i < count($assAssessors); $i++)
                {
                    $aAssessorNames[] = $assAssessors[$i]['assigned_assessor'];
                    $a_newly_signed[] = $assAssessors[$i]['newly_signed'];
                    $a_on_prog[] = $assAssessors[$i]['on_prog'];
                    $a_comp_due[] = $assAssessors[$i]['comp_due'];
                }
        */
        $withdrawn_restart_sql = <<<SQL
SELECT 
	tr.id
FROM 
	tr 
	INNER JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	INNER JOIN frameworks ON frameworks.id = student_frameworks.id
	LEFT JOIN tr AS trp ON trp.l03 = tr.l03 AND tr.start_date > trp.closure_date
	LEFT JOIN student_frameworks AS sfp ON sfp.tr_id = trp.id
	LEFT JOIN frameworks AS fp ON fp.id = sfp.id
WHERE 
	tr.status_code = 1
	AND trp.status_code = 3
	AND (
		(frameworks.StandardCode IS NOT NULL AND frameworks.StandardCode = fp.StandardCode) OR 
		(frameworks.framework_code IS NOT NULL AND frameworks.framework_code = fp.`framework_code`)
	)
;
SQL;
        $withdrawn_restarts = DAO::getSingleColumn($link, $withdrawn_restart_sql);

        $learner_transfers = DAO::getSingleValue($link, "SELECT COUNT(*) FROM inductees WHERE inductees.inductee_type = 'LT'");

        require_once('tpl_induction_dashboard.php');
    }


    public function showInductionByEmployer(PDO $link)
    {
        $date = $_REQUEST['statsMonth'];
        $date = new Date($date);

        $start_date = $date->getYear() . '-' . $date->getMonth() . '-01';
        $last_day_of_month = cal_days_in_month(CAL_GREGORIAN, $date->getMonth(), $date->getYear());
        $end_date = $date->getYear() . '-' . $date->getMonth() . '-' . $last_day_of_month;

        echo '<table class="table table-bordered table-striped text-center">
						<tr><th>Employer</th><th>Completed</th><th>To Be Arranged</th><th>Scheduled</th><th>Holding Induction</th><th>Withdrawn</th><th>Leaver</th><th>Total</th></tr>';

        $sql = <<<SQL
SELECT
	organisations.`legal_name`,
	SUM(IF(induction_status = 'C', 1, 0)) AS Completed,
	SUM(IF(induction_status = 'TBA', 1, 0)) AS ToBeArranged,
	SUM(IF(induction_status = 'S', 1, 0)) AS Scheduled,
	SUM(IF(induction_status = 'H', 1, 0)) AS HoldingInduction,
	SUM(IF(induction_status = 'W', 1, 0)) AS Withdrawn,
	SUM(IF(induction_status = 'L', 1, 0)) AS Leaver,
	SUM(1) AS total
FROM inductees
	INNER JOIN induction ON inductees.id = induction.`inductee_id`
	INNER JOIN organisations ON inductees.`employer_id` = organisations.id
	INNER JOIN induction_programme ON inductees.id = induction_programme.`inductee_id`
WHERE
	inductees.`sunesis_username` IS NULL
	AND induction_programme.`programme_id` NOT IN (432, 446) 
	AND induction.induction_date >= '$start_date'
	AND induction.induction_date <= '$end_date'
GROUP BY
	organisations.legal_name
;
SQL;

        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $totals = array();
        $totals['Completed'] = 0;
        $totals['ToBeArranged'] = 0;
        $totals['Scheduled'] = 0;
        $totals['HoldingInduction'] = 0;
        $totals['Withdrawn'] = 0;
        $totals['Leaver'] = 0;

        foreach($result AS $row)
        {
            echo '<tr>';
            echo '<td>' . $row['legal_name'] . '</td>';
            echo '<td>' . $row['Completed'] . '</td>';
            echo '<td>' . $row['ToBeArranged'] . '</td>';
            echo '<td>' . $row['Scheduled'] . '</td>';
            echo '<td>' . $row['HoldingInduction'] . '</td>';
            echo '<td>' . $row['Withdrawn'] . '</td>';
            echo '<td>' . $row['Leaver'] . '</td>';
            echo '<td>' . $row['total'] . '</td>';
            echo '</tr>';
            foreach($totals AS $key => &$value)
            {
                $value = $value + $row[$key];
            }
        }
        echo '<tr><td bgcolor="black"></td>';
        foreach($totals AS $v)
        {
            echo '<td>' . $v . '</td>';
        }
        echo '<td class="text-bold">' . array_sum($totals) . '</td>';
        echo '</tr></table>';

        //echo json_encode($sql);
    }

    public function showInductionTable(PDO $link)
    {
        $date = $_REQUEST['statsMonth'];
        $date = new Date($date);

        $start_date = $date->getYear() . '-' . $date->getMonth() . '-01';
        $last_day_of_month = cal_days_in_month(CAL_GREGORIAN, $date->getMonth(), $date->getYear());
        $end_date = $date->getYear() . '-' . $date->getMonth() . '-' . $last_day_of_month;

        echo '<table class="table table-bordered table-striped text-center">
						<tr><th>Age Band</th><th>Programme</th><th>Completed</th><th>To Be Arranged</th><th>Scheduled</th><th>Holding Induction</th><th>Total</th></tr>';

        $sql = <<<SQL
SELECT
    CASE TRUE
		WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
		WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
		WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) > 24 THEN '24+'
		WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) < 16 THEN 'Under 16'
	END AS age_band,
	#courses.`title`,
	courses.course_group AS title,
	SUM(IF(induction_status = 'C', 1, 0)) AS Completed,
	SUM(IF(induction_status = 'TBA', 1, 0)) AS ToBeArranged,
	SUM(IF(induction_status = 'S', 1, 0)) AS Scheduled,
	SUM(IF(induction_status = 'H', 1, 0)) AS HoldingInduction,
#	SUM(IF(induction_status = 'W', 1, 0)) AS Withdrawn,
#	SUM(IF(induction_status = 'L', 1, 0)) AS Leaver,
	SUM(1) AS total
FROM inductees
	INNER JOIN induction ON inductees.id = induction.`inductee_id`
	LEFT JOIN induction_programme ON inductees.id = induction_programme.`inductee_id`
	LEFT JOIN courses ON induction_programme.`programme_id` = courses.id
WHERE
	#inductees.`sunesis_username` IS NULL AND
	induction.induction_date >= '$start_date'
	AND induction.induction_date <= '$end_date'
	AND induction.`induction_status` NOT IN ('W', 'L')
	AND induction_programme.`programme_id` NOT IN (432, 446) 
GROUP BY
	#induction_programme.`programme_id`,age_band
	courses.course_group, age_band
;
SQL;
        $sql = <<<SQL
SELECT DISTINCT
	CASE TRUE
		WHEN ((DATE_FORMAT(induction_date,'%Y') - DATE_FORMAT(dob,'%Y')) - (DATE_FORMAT(induction_date,'00-%m-%d') < DATE_FORMAT(dob,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
		WHEN ((DATE_FORMAT(induction_date,'%Y') - DATE_FORMAT(dob,'%Y')) - (DATE_FORMAT(induction_date,'00-%m-%d') < DATE_FORMAT(dob,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
		WHEN ((DATE_FORMAT(induction_date,'%Y') - DATE_FORMAT(dob,'%Y')) - (DATE_FORMAT(induction_date,'00-%m-%d') < DATE_FORMAT(dob,'00-%m-%d'))) > 24 THEN '24+'
		WHEN ((DATE_FORMAT(induction_date,'%Y') - DATE_FORMAT(dob,'%Y')) - (DATE_FORMAT(induction_date,'00-%m-%d') < DATE_FORMAT(dob,'00-%m-%d'))) < 16 THEN 'Under 16'
	END AS age_band,

	title,
	SUM(IF(induction_status = 'C', 1, 0)) AS Completed,
	SUM(IF(induction_status = 'TBA', 1, 0)) AS ToBeArranged,
	SUM(IF(induction_status = 'S', 1, 0)) AS Scheduled,
	SUM(IF(induction_status = 'H', 1, 0)) AS HoldingInduction,
	SUM(1) AS total
FROM
	(
		SELECT DISTINCT
			inductees.id,
			induction.`induction_date`,
			inductees.dob,
			courses.course_group AS title,
			induction.`induction_status`
		FROM
			inductees
			INNER JOIN induction ON inductees.id = induction.`inductee_id`
			LEFT JOIN induction_programme ON inductees.id = induction_programme.`inductee_id`
			LEFT JOIN courses ON induction_programme.`programme_id` = courses.id
		WHERE
			induction.induction_date >= '$start_date'
			AND induction.induction_date <= '$end_date'
			AND induction.`induction_status` NOT IN ('W', 'L')
			AND induction_programme.`programme_id` NOT IN (432, 446) 
	) AS tbl
GROUP BY
	title, age_band
;
SQL;

        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $totals = array();
        $totals['Completed'] = 0;
        $totals['ToBeArranged'] = 0;
        $totals['Scheduled'] = 0;
        $totals['HoldingInduction'] = 0;
        //$totals['Withdrawn'] = 0;
        //$totals['Leaver'] = 0;

        $url_part1 = "do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_from_induction_date={$start_date}&view_ViewInduction_Allfilter_to_induction_date={$end_date}";
        $url_show_all = '&view_ViewInduction_Allfilter_induction_status[]=0&view_ViewInduction_Allfilter_induction_status[]=1&view_ViewInduction_Allfilter_induction_status[]=2&view_ViewInduction_Allfilter_induction_status[]=3'; //not exactly show all

        foreach($result AS $row)
        {
            $url_part2 = "&view_ViewInduction_Allfilter_cg={$row['title']}&view_ViewInduction_Allfilter_age_group={$row['age_band']}";
            $url_part2 = str_replace('+', '%2B', $url_part2);
            echo '<tr>';
            echo '<td>' . $row['age_band'] . '</td>';
            echo '<td>' . $row['title'] . '</td>';
            echo '<td><a href="'.$url_part1.$url_part2.'&view_ViewInduction_Allfilter_induction_status[]=2">' . $row['Completed'] . '</a></td>';
            echo '<td><a href="'.$url_part1.$url_part2.'&view_ViewInduction_Allfilter_induction_status[]=0">' . $row['ToBeArranged'] . '</a></td>';
            echo '<td><a href="'.$url_part1.$url_part2.'&view_ViewInduction_Allfilter_induction_status[]=1">' . $row['Scheduled'] . '</a></td>';
            echo '<td><a href="'.$url_part1.$url_part2.'&view_ViewInduction_Allfilter_induction_status[]=3">' . $row['HoldingInduction'] . '</a></td>';
            //echo '<td>' . $row['Withdrawn'] . '</td>';
            //echo '<td>' . $row['Leaver'] . '</td>';
            echo '<td><a href="'.$url_part1.$url_part2.$url_show_all.'">' . $row['total'] . '</a></td>';
            echo '</tr>';
            foreach($totals AS $key => &$value)
            {
                $value = $value + $row[$key];
            }
        }
        echo '<tr><td bgcolor="black"></td><td bgcolor="black"></td>';
        $_totals = $totals;

        foreach($totals AS $key => $v)
        {
            $url_part2 = "&view_ViewInduction_Allfilter_cg=&view_ViewInduction_Allfilter_age_group=";
            if($key == 'Completed')
                $url_part2 .= '&view_ViewInduction_Allfilter_induction_status[]=2';
            elseif($key == 'ToBeArranged')
                $url_part2 .= '&view_ViewInduction_Allfilter_induction_status[]=0';
            elseif($key == 'Scheduled')
                $url_part2 .= '&view_ViewInduction_Allfilter_induction_status[]=1';
            elseif($key == 'HoldingInduction')
                $url_part2 .= '&view_ViewInduction_Allfilter_induction_status[]=3';
            echo '<td><a href="'.$url_part1.$url_part2.'">' . $v . '</a></td>';
        }
        $url_part2 = "&view_ViewInduction_Allfilter_cg=&view_ViewInduction_Allfilter_age_group=".$url_show_all;
        echo '<td class="text-bold"><a href="'.$url_part1.$url_part2.'">' . array_sum($_totals) . '</td>';
        echo '</tr></table>';


    }

    public function showInductionTable1(PDO $link)
    {
        $date = $_REQUEST['statsMonth'];
        $date = new Date($date);

        $start_date = $date->getYear() . '-' . $date->getMonth() . '-01';
        $last_day_of_month = cal_days_in_month(CAL_GREGORIAN, $date->getMonth(), $date->getYear());
        $end_date = $date->getYear() . '-' . $date->getMonth() . '-' . $last_day_of_month;

        echo '<table class="table table-bordered table-striped text-center">
						<tr><th>Programme</th><th>Completed</th><th>To Be Arranged</th><th>Scheduled</th><th>Holding Induction</th><th>Total</th><th>Capacity</th></tr>';

        $sql = <<<SQL
SELECT DISTINCT 
	apprenticeship_title,
	capacity,
	SUM(IF(induction_status = 'C', 1, 0)) AS Completed,
	SUM(IF(induction_status = 'TBA', 1, 0)) AS ToBeArranged,
	SUM(IF(induction_status = 'S', 1, 0)) AS Scheduled,
	SUM(IF(induction_status = 'H', 1, 0)) AS HoldingInduction,
	SUM(1) AS total,
        ict_capacity
	FROM
(
SELECT DISTINCT
	inductees.id,
	induction.`induction_date`,
	inductees.dob,
	courses.`apprenticeship_title`,
	(SELECT capacity FROM program_capacity_matrix INNER JOIN lookup_apprenticeship_titles ON program_capacity_matrix.`ap_title_id` = lookup_apprenticeship_titles.`id` WHERE lookup_apprenticeship_titles.`description` = courses.`apprenticeship_title`
	AND program_capacity_matrix.`month_name` = DATE_FORMAT('$start_date', '%b%Y')) AS capacity,
	induction.`induction_status`,
	IF(
		courses.`apprenticeship_title` = 'Level 3 ICT Network Technician' OR courses.`apprenticeship_title` = 'Level 3 ICT Support Technician', 
		(SELECT capacity FROM program_capacity_matrix WHERE program_capacity_matrix.`ap_title_id` = '3' 
		AND program_capacity_matrix.`month_name` = DATE_FORMAT('$start_date', '%b%Y')),
		''
	) AS ict_capacity
FROM
	inductees
	INNER JOIN induction ON inductees.id = induction.`inductee_id`
	INNER JOIN induction_programme ON inductees.id = induction_programme.`inductee_id`
	INNER JOIN courses ON induction_programme.`programme_id` = courses.id
WHERE
	induction.induction_date >= '$start_date'
	AND induction.induction_date <= '$end_date'
	AND induction.`induction_status` NOT IN ('W', 'L')
	AND induction_programme.`programme_id` NOT IN (432, 446)
) AS tbl
GROUP BY apprenticeship_title
;
SQL;
        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $totals = array();
        $totals['Completed'] = 0;
        $totals['ToBeArranged'] = 0;
        $totals['Scheduled'] = 0;
        $totals['HoldingInduction'] = 0;
        $capacity_total = 0;

        foreach($result AS $row)
        {
	    if(in_array($row['apprenticeship_title'], ['Level 3 ICT Support Technician', 'Level 3 ICT Support Technician']))
            {
                $row['capacity'] = $row['ict_capacity'];
            }
	    $capacity_total += $row['capacity'];
            $total_class = (int)$row['total'] < (int)$row['capacity'] ? 'text-red' : 'text-green';	
            echo '<tr>';
            echo '<td>' . $row['apprenticeship_title'] . '</td>';
            echo '<td>' . $row['Completed'] . '</td>';
            echo '<td>' . $row['ToBeArranged'] . '</td>';
            echo '<td>' . $row['Scheduled'] . '</td>';
            echo '<td>' . $row['HoldingInduction'] . '</td>';
            echo '<td class="' . $total_class . '">' . $row['total'] . '</td>';
            echo '<td>' . $row['capacity'] . '</td>';
            echo '</tr>';
            foreach($totals AS $key => &$value)
            {
                $value = $value + $row[$key];
            }
        }
        echo '<tr><td bgcolor="black"></td>';
        $_totals = $totals;

        foreach($totals AS $key => $v)
        {
            echo '<td>' . $v . '</td>';
        }
        echo '<td class="text-bold">' . array_sum($_totals) . '</td>';
        echo '<td class="text-bold">' . $capacity_total . '</td>';
        echo '</tr></table>';


    }

    private function showQuarterlyCompletion(PDO $link)
    {
        $current_quarter = InductionHelper::getCurrentQuarter();
        $last_quarter = InductionHelper::getLastQuarter();

        // previous quarter
        $sd = $last_quarter->start_date->formatMySQL();
        $ed = $last_quarter->end_date->formatMySQL();

	$p_start_date = new DateTime($sd);
        $p_end_date = new DateTime($ed);
        $interval = DateInterval::createFromDateString('1 month');
        $p_period = new DatePeriod($p_start_date, $interval, $p_end_date);
        $p_months = [];
        foreach ($p_period as $dt) 
        {
            $p_months[] = $dt->format("M_Y");
        }
        $p_capacity = DAO::getSingleValue($link, "SELECT SUM(capacity) FROM lookup_induction_capacity WHERE month IN ( " . "'" . implode ( "', '", $p_months ) . "'" . ") ");
        $p_capacity = $p_capacity == '' ? 0 : $p_capacity;

        $previous_q_url = '<a href="do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_induction_status[]=2&view_ViewInduction_Allfilter_induction_status[]=4&view_ViewInduction_Allfilter_from_induction_date='.$sd.'&view_ViewInduction_Allfilter_to_induction_date='.$ed.'" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>';
        $sql = <<<SQL
SELECT
	COUNT(*) AS cnt
FROM inductees
	INNER JOIN induction ON inductees.id = induction.`inductee_id`
	INNER JOIN induction_programme ON inductees.`id` = induction_programme.`inductee_id`
WHERE
	induction.induction_status IN ('C', 'L')
	AND induction.induction_date >= '$sd'
	AND induction.induction_date <= '$ed'
	AND induction_programme.`programme_id` NOT IN (432, 446) 
;
SQL;
        $p_quarter = (int)DAO::getSingleValue($link, $sql);
        $p_sd = $last_quarter->start_date->formatShort();
        $p_ed = $last_quarter->end_date->formatShort();

        // current quarter
        $sd = $current_quarter->start_date->formatMySQL();
        $ed = $current_quarter->end_date->formatMySQL();

	$c_start_date = new DateTime($sd);
        $c_end_date = new DateTime($ed);
        $interval = DateInterval::createFromDateString('1 month');
        $c_period = new DatePeriod($c_start_date, $interval, $c_end_date);
        $c_months = [];
        foreach ($c_period as $dt) 
        {
            $c_months[] = $dt->format("M_Y");
        }
        $c_capacity = DAO::getSingleValue($link, "SELECT SUM(capacity) FROM lookup_induction_capacity WHERE month IN ( " . "'" . implode ( "', '", $c_months ) . "'" . ") ");
        $c_capacity = $c_capacity == '' ? 0 : $c_capacity;

        $current_q_url = '<a href="do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_induction_status[]=2&view_ViewInduction_Allfilter_induction_status[]=4&view_ViewInduction_Allfilter_from_induction_date='.$sd.'&view_ViewInduction_Allfilter_to_induction_date='.$ed.'" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>';
        $sql = <<<SQL
SELECT
	COUNT(*) AS cnt
FROM inductees
	INNER JOIN induction ON inductees.id = induction.`inductee_id`
	INNER JOIN induction_programme ON inductees.`id` = induction_programme.`inductee_id`
WHERE
	induction.induction_status IN ('C', 'L')
	AND induction.induction_date >= '$sd'
	AND induction.induction_date <= '$ed'
	AND induction_programme.`programme_id` NOT IN (432, 446) 
;
SQL;
        $c_quarter = (int)DAO::getSingleValue($link, $sql);
        $html = '';
        $c_sd = $current_quarter->start_date->formatShort();
        $c_ed = $current_quarter->end_date->formatShort();
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$c_quarter / $c_capacity</h1>
			<p>Total starts within current quarter <br>$c_sd - $c_ed</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		$current_q_url
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h1>$p_quarter / $p_capacity</h1>
			<p>Total starts within previous quarter <br>$p_sd - $p_ed</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		$previous_q_url
	</div>
</div>
HTML;

        return $html;
    }

    private function showInductionDashPanels(PDO $link)
    {
        $date = $_REQUEST['statsMonth'];
        $date = new Date($date);

        $start_date = $date->getYear() . '-' . $date->getMonth() . '-01';
        $last_day_of_month = cal_days_in_month(CAL_GREGORIAN, $date->getMonth(), $date->getYear());
        $end_date = $date->getYear() . '-' . $date->getMonth() . '-' . $last_day_of_month;

        $sql = <<<SQL
SELECT
	SUM(IF(induction_status = 'C', 1, 0)) AS Completed,
	SUM(IF(induction_status = 'TBA', 1, 0)) AS ToBeArranged,
	SUM(IF(induction_status = 'S', 1, 0)) AS Scheduled,
	SUM(IF(induction_status = 'H', 1, 0)) AS HoldingInduction,
	SUM(IF(induction_status = 'W', 1, 0)) AS Withdrawn,
	SUM(IF(induction_status = 'L', 1, 0)) AS Leaver,
	SUM(1) AS total
FROM inductees
	INNER JOIN induction ON inductees.id = induction.`inductee_id`
	INNER JOIN induction_programme ON inductees.`id` = induction_programme.`inductee_id`
WHERE
	#(inductees.`sunesis_username` IS NULL OR inductees.`sunesis_username` = 'N')
	#AND
	induction.induction_date >= '$start_date'
	AND induction.induction_date <= '$end_date'
	AND induction_programme.`programme_id` NOT IN (432, 446) 
	AND inductees.inductee_type != 'LT'
;
SQL;

        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $result = $result[0];
        $completed = isset($result['Completed'])?$result['Completed']:'0';
        $tba = isset($result['ToBeArranged'])?$result['ToBeArranged']:'0';
        $scheduled = isset($result['Scheduled'])?$result['Scheduled']:'0';
        $hc = isset($result['HoldingInduction'])?$result['HoldingInduction']:'0';
        $withdrawn = isset($result['Withdrawn'])?$result['Withdrawn']:'0';
        $leaver = isset($result['Leaver'])?$result['Leaver']:'0';
        $total = isset($result['total'])?$result['total']:'0';
	$induction_capacity = DAO::getSingleValue($link, "SELECT capacity FROM lookup_induction_capacity WHERE month = '" . $date->format('M_Y') . "'");
        $induction_capacity = $induction_capacity == '' ? 0 : $induction_capacity;

        $total_read_to_be_inducted = (int)$completed + (int)$scheduled + (int)$tba;
        $total_overall_to_be_arranged = (int)$completed + (int)$scheduled + (int)$tba + (int)$hc;

        $url_part1 = "do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_from_induction_date={$start_date}&view_ViewInduction_Allfilter_to_induction_date={$end_date}";
        $total_read_to_be_inducted_link = $url_part1.'&view_ViewInduction_Allfilter_induction_status[]=0&view_ViewInduction_Allfilter_induction_status[]=1&view_ViewInduction_Allfilter_induction_status[]=2';
        $total_overall_to_be_arranged_link = $url_part1.'&view_ViewInduction_Allfilter_induction_status[]=0&view_ViewInduction_Allfilter_induction_status[]=1&view_ViewInduction_Allfilter_induction_status[]=2&view_ViewInduction_Allfilter_induction_status[]=3';

	$total_without_withdrawn = $total - $withdrawn;
        $html = '';
	        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h1>$total_without_withdrawn / $induction_capacity</h1>
			<p>Actual / Target</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$completed</h1>
			<p>Completed</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_induction_status=2&view_ViewInduction_Allfilter_from_induction_date=$start_date&view_ViewInduction_Allfilter_to_induction_date=$end_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$tba</h1>
			<p>To be arranged</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_induction_status=0&view_ViewInduction_Allfilter_from_induction_date=$start_date&view_ViewInduction_Allfilter_to_induction_date=$end_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$scheduled</h1>
			<p>Scheduled</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_induction_status=1&view_ViewInduction_Allfilter_from_induction_date=$start_date&view_ViewInduction_Allfilter_to_induction_date=$end_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-blue">
		<div class="inner">
			<h1>$hc</h1>
			<p>Holding Induction</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_induction_status=3&view_ViewInduction_Allfilter_from_induction_date=$start_date&view_ViewInduction_Allfilter_to_induction_date=$end_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$withdrawn</h1>
			<p>Withdrawn</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_induction_status=5&view_ViewInduction_Allfilter_from_induction_date=$start_date&view_ViewInduction_Allfilter_to_induction_date=$end_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$leaver</h1>
			<p>Leaver</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_induction_status=4&view_ViewInduction_Allfilter_from_induction_date=$start_date&view_ViewInduction_Allfilter_to_induction_date=$end_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h1>$total_read_to_be_inducted</h1>
			<p>Potential to be inducted</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="$total_read_to_be_inducted_link" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h1>$total_overall_to_be_arranged</h1>
			<p>Total overall to be arranged (includes Holding Inductions)</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="$total_overall_to_be_arranged_link" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        /*$html .= <<<HTML
<div class="col-lg-12 col-xs-12">
    <div class="small-box bg-aqua">
        <div class="inner">
            <h1>$total</h1>
            <p>Total</p>
        </div>
        <div class="icon"><i class="fa fa-users"></i> </div>
        <a href="do.php?_action=induction_home&_reset=1&selected_tab=tab6&view=view_ViewInduction_All&view_ViewInduction_Allfilter_induction_status=&view_ViewInduction_Allfilter_from_induction_date=$start_date&view_ViewInduction_Allfilter_to_induction_date=$end_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
HTML;*/


        return $html;
    }

    private function getStatsLearnersByAssessors(PDO $link)
    {
        $sql = <<<SQL
SELECT
  tr.id AS training_id,
  tr.`assessor` AS assessor_id,
  IF(tr.`target_date` BETWEEN CURDATE() AND NOW() + INTERVAL 30 DAY, 1, 0) AS completion_due,
  CONCAT(users.`firstnames`, ' ', users.`surname`) AS assessor
FROM
  tr LEFT JOIN users ON tr.`assessor` = users.`id`
WHERE tr.`assessor` IS NOT NULL
  AND tr.`assessor` != 0
  AND tr.`status_code` = 1
;
SQL;
        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

        $trs = [];
        $labels = [];
        $dataset = [];
        $data = [];
        foreach($result AS $row)
        {
            if(!in_array($row['training_id'], $trs))
                $trs[] = $row['training_id'];
            if(!isset($data[$row['assessor']]))
            {
                $obj = new stdClass();
                $obj->completion_due = 0;
                $obj->on_programme = 0;
                $obj->newly_assigned = DAO::getSingleValue($link, "SELECT COUNT(*) FROM induction INNER JOIN inductees ON induction.inductee_id = inductees.id WHERE induction.assigned_assessor = '{$row['assessor_id']}' AND inductees.sunesis_username IS NULL AND induction.induction_status IN ('TBA', 'S', 'H')");
                $data[$row['assessor']] = $obj;
            }
            $data[$row['assessor']]->on_programme++;
            if($row['completion_due'] == '1')
                $data[$row['assessor']]->completion_due++;
        }
        $sql = <<<SQL
SELECT
  tr.id AS training_id,
  groups.`assessor` AS assessor_id,
  IF(tr.`target_date` BETWEEN CURDATE() AND NOW() + INTERVAL 30 DAY, 1, 0) AS completion_due,
  (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = groups.assessor) AS assessor
FROM
  tr INNER JOIN group_members ON tr.id = group_members.`tr_id`
  	 INNER JOIN groups ON group_members.`groups_id` = groups.id
WHERE groups.`assessor` IS NOT NULL
  AND groups.`assessor` != 0
  AND tr.`status_code` = 1
;
SQL;
        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            if(in_array($row['training_id'], $trs))
                continue;
            if(!isset($data[$row['assessor']]))
            {
                $obj = new stdClass();
                $obj->completion_due = 0;
                $obj->on_programme = 0;
                $obj->newly_assigned = DAO::getSingleValue($link, "SELECT COUNT(*) FROM induction INNER JOIN inductees ON induction.inductee_id = inductees.id WHERE induction.assigned_assessor = '{$row['assessor_id']}' AND inductees.sunesis_username IS NULL AND induction.induction_status IN ('TBA', 'S', 'H')");
                $data[$row['assessor']] = $obj;
            }
            $data[$row['assessor']]->on_programme++;
            if($row['completion_due'] == '1')
                $data[$row['assessor']]->completion_due++;
        }

        $newly_assigned = [];
        $on_programme = [];
        $completions_due = [];
        $graphMax = 0;
        foreach($data AS $key => $val)
        {
            if(!in_array($key, $labels))
                $labels[] = $key;
            $newly_assigned[] = $val->newly_assigned;
            $on_programme[] = $val->on_programme;
            $completions_due[] = $val->completion_due;
            if(($val->newly_assigned + $val->on_programme + $val->completion_due) > $graphMax)
                $graphMax = $val->newly_assigned + $val->on_programme + $val->completion_due + 1;
        }

        $dataset[] = array(
            "fillColor" => "yellow"
        ,"strokeColor" => "rgba(220,220,220,1)"
        ,"pointColor" =>"rgba(220,220,220,1)"
        ,"data" => $newly_assigned
        ,"title" => "Newly Assigned"
        );
        $dataset[] = array(
            "fillColor" => "#FF6666"
        ,"strokeColor" => "rgba(220,220,220,1)"
        ,"pointColor" =>"rgba(220,220,220,1)"
        ,"data" => $completions_due
        ,"title" => "Completions Due"
        );
        $dataset[] = array(
            "fillColor" => "lightgreen"
        ,"strokeColor" => "rgba(220,220,220,1)"
        ,"pointColor" =>"rgba(220,220,220,1)"
        ,"data" => $on_programme
        ,"title" => "On Programme"
        );

        $options = array(
            "animationStartWithDataset" => 1,
            "animationStartWithData" => 1,
            "animationLeftToRight" => true,
            "animationSteps" => 50,
            "animationEasing" => "linear",
            "legend" => true,
            "inGraphDataShow" => true,
            "annotateDisplay" => true,
            "yAxisMinimumInterval" => 1,
            "maintainAspectRatio" => true,
            "responsive" => true,
            "savePng" => true,
            "savePngOutput" => "Save",
            "savePngName" => "Learners by assessors ",
            "canvasBorders" => true,
            "canvasBordersWidth" => 2,
            "canvasBordersColor" => "purple",
            "graphTitle" => "Learners by assessors ",
            "graphTitleFontSize" => 16,
            "graphMax" => $graphMax

        );

        $graph = new stdClass();
        $graph->data = array('labels' => $labels, 'datasets' => $dataset);
        $graph->options = $options;

        echo json_encode($graph);
    }

    private function navToTRSummary(PDO $link)
    {
        $assessor_name = isset($_REQUEST['assessor']) ? $_REQUEST['assessor'] : '';
        $comp_due = isset($_REQUEST['comp_due']) ? $_REQUEST['comp_due'] : '';
        if($assessor_name == '')
            return;

        $assessor_id = DAO::getSingleValue($link, "SELECT users.id FROM users WHERE CONCAT(users.firstnames, ' ', users.surname) = '{$assessor_name}' AND users.type != 5 AND users.web_access = '1'");
        if($assessor_id == '')
            return;

        $url = '';
        if($comp_due == '0')
            $url = 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_assessor='.$assessor_id;
        if($comp_due == '1')
        {
            $today = new Date(date('d/m/Y'));
            $url = 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_assessor='.$assessor_id.'&ViewTrainingRecords_target_start_date='.$today->formatShort();
            $today->addDays(30);
            $url .= '&ViewTrainingRecords_target_end_date='.$today->formatShort();
        }
        if($url != '')
            http_redirect($url);
    }
    private function navToInduction(PDO $link)
    {
        $assessor_name = isset($_REQUEST['assessor']) ? $_REQUEST['assessor'] : '';
        if($assessor_name == '')
            return;

        $assessor_id = DAO::getSingleValue($link, "SELECT users.id FROM users WHERE CONCAT(users.firstnames, ' ', users.surname) = '{$assessor_name}' AND users.type != 5 AND users.web_access = '1'");
        if($assessor_id == '')
            return;

        $url = 'do.php?_action=induction_home&view=view_ViewInduction_All&selected_tab=tab6&_reset=1&view_ViewInduction_Allfilter_a_assessor='.$assessor_id;
        $url .= '&view_ViewInduction_Allfilter_induction_status%5B%5D=0&view_ViewInduction_Allfilter_induction_status%5B%5D=1&view_ViewInduction_Allfilter_induction_status%5B%5D=3';
        http_redirect($url);
    }
}