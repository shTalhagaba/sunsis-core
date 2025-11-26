<?php

class HomePageV2
{
    public static function createView(PDO $link, $view_name, $apply_caseload = true)
    {
        if(!$_SESSION['user']->isAdmin() && !in_array(DB_NAME, ["am_lead", "am_lead_demo"]))
            $apply_caseload = true;

        $sql = new SQLStatement("
SELECT DISTINCT 
	tr.id AS _tr_id,
	DATE_FORMAT(tr.start_date, '%M %Y') AS _start_month_year,
	DATE_FORMAT(tr.target_date, '%M %Y') AS _planned_end_month_year,
	IF(tr.`target_date` < CURRENT_DATE, 1, 0) AS _overstayer,
	CASE
		WHEN DATEDIFF(tr.`target_date`, CURRENT_DATE) > 90 THEN '> 90 days left'
		WHEN DATEDIFF(tr.`target_date`, CURRENT_DATE) BETWEEN 0 AND 90 THEN '0-90 days left'
	END AS _diff_plan_end_date,
	DATEDIFF(tr.closure_date, tr.start_date) AS _days_left_after_start,
	tr.firstnames AS _firstnames,
	tr.surname AS _surname,
	contracts.title AS _contract_title,
	IF(tr.l36 IS NULL, 0, tr.l36) AS percentage_completed,
  	IF( tr.target_date < CURDATE(), 100, tr.target ) AS target,
  	tr.`assessor`,
  	tr.`status_code`,
  	tr.otj_hours AS otj_hours_due,
	(SELECT SUM(duration_hours)*60 + SUM(duration_minutes) FROM otj WHERE tr_id = tr.id) AS otj_hours_actual,
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
	) AS otj_progress,
	timestampdiff(MONTH, CURDATE(), epa_details.`epa_prop_date1`)  AS month_left
FROM
	tr 
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN courses_tr ON tr.`id` = courses_tr.`tr_id`
	LEFT JOIN courses ON courses.`id` = courses_tr.`course_id`
	LEFT JOIN contracts ON tr.contract_id = contracts.id
	LEFT JOIN tr_epa epa_details ON tr.`id` = epa_details.`tr_id`
;
		");

        if($apply_caseload)
        {
            if($_SESSION['user']->isAdmin() ||
                in_array($_SESSION['user']->type, [User::TYPE_SYSTEM_VIEWER, User::TYPE_GLOBAL_VERIFIER, User::TYPE_SALESPERSON, User::TYPE_REVIEWER]))
            {
                // do nothing
            }
            elseif($_SESSION['user']->isOrgAdmin() ||
                in_array($_SESSION['user']->type, [User::TYPE_MANAGER, User::TYPE_ORGANISATION_VIEWER, User::TYPE_SCHOOL_VIEWER]))
            {
                $manager_type = User::TYPE_MANAGER;
                $where = <<<SQL
WHERE
(
    tr.provider_id = '{$_SESSION['user']->employer_id}' OR
    tr.employer_id = '{$_SESSION['user']->employer_id}' OR
    users.who_created = '{$_SESSION['user']->username}' OR
    users.who_created IN
    (
        SELECT username FROM users WHERE users.type = '{$manager_type}' AND users.employer_id = '{$_SESSION['user']->employer_id}'
    )
)
SQL;
                $sql->setClause($where);
            }
            elseif($_SESSION['user']->type == User::TYPE_ASSESSOR)
            {
                $sql->setClause("WHERE (tr.assessor = '{$_SESSION['user']->id}')");
            }
            elseif($_SESSION['user']->type == User::TYPE_TUTOR)
            {
                $sql->setClause("WHERE (tr.tutor = '{$_SESSION['user']->id}')");
            }
            elseif($_SESSION['user']->type == User::TYPE_VERIFIER)
            {
                $sql->setClause("WHERE (tr.verifier = '{$_SESSION['user']->id}')");
            }
            elseif($_SESSION['user']->type == User::TYPE_LEARNER)
            {
                $sql->setClause("WHERE tr.username = '{$_SESSION['user']->username}'");
            }
            elseif($_SESSION['user']->type == User::TYPE_APPRENTICE_COORDINATOR)
            {
                $sql->setClause("WHERE tr.programme = '{$_SESSION['user']->id}'");
            }
            elseif(DB_NAME=='am_ela' and $_SESSION['user']->type == User::TYPE_SUPERVISOR)
            {
                // do nothing
            }
            else
            {
                $sql->setClause("WHERE tr.employer_id = '{$_SESSION['user']->employer_id}'");
            }
        }

        $view = new VoltView($view_name, $sql->__toString());

        $options = array(
            0=>array('SHOW_ALL', 'Show all', null, 'WHERE status_code IN (1,2,3,4,5,6,7)'),
            1=>array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
            2=>array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
            3=>array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
            4=>array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
            5=>array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
            6=>array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
            7=>array('7', '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
        $f = new VoltCheckboxViewFilter( 'filter_status_code', $options, ['SHOW_ALL'] );
        $f->setDescriptionFormat("Show: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'Yes', null, 'WHERE tr.id IN (SELECT DISTINCT ilr.tr_id FROM ilr WHERE extractvalue(ilr.ilr, "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearningDeliveryFAM[LearnDelFAMType=\'RES\']/LearnDelFAMCode") = 1 ORDER BY submission DESC)'),
            2=>array(2, 'No', null, 'WHERE tr.id NOT IN (SELECT DISTINCT ilr.tr_id FROM ilr WHERE extractvalue(ilr.ilr, "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearningDeliveryFAM[LearnDelFAMType=\'RES\']/LearnDelFAMCode") = 1 ORDER BY submission DESC)'));
        $f = new VoltDropDownViewFilter('filter_restart', $options, 0, false);
        $f->setDescriptionFormat("Restart: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_tr_ids', "WHERE tr.id in (%s)", null);
        $f->setDescriptionFormat("TR IDs: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts";
        $f = new VoltDropDownViewFilter('filter_contract_year', $options, null, true);
        $f->setDescriptionFormat("Contract Year: %s");
        $view->addFilter($f);

        $format = "WHERE tr.start_date >= '%s'";
        $f = new VoltDateViewFilter('filter_from_start_date', $format, '');
        $f->setDescriptionFormat("From start date: %s");
        $view->addFilter($f);
        $format = "WHERE tr.start_date <= '%s'";
        $f = new VoltDateViewFilter('filter_to_start_date', $format, '');
        $f->setDescriptionFormat("To start date: %s");
        $view->addFilter($f);

        $format = "WHERE tr.target_date >= '%s'";
        $f = new VoltDateViewFilter('filter_from_target_date', $format, '');
        $f->setDescriptionFormat("From target date: %s");
        $view->addFilter($f);
        $format = "WHERE tr.target_date <= '%s'";
        $f = new VoltDateViewFilter('filter_to_target_date', $format, '');
        $f->setDescriptionFormat("To target date: %s");
        $view->addFilter($f);

        $format = "WHERE tr.closure_date >= '%s'";
        $f = new VoltDateViewFilter('filter_from_closure_date', $format, '');
        $f->setDescriptionFormat("From end date: %s");
        $view->addFilter($f);
        $format = "WHERE tr.closure_date <= '%s'";
        $f = new VoltDateViewFilter('filter_to_closure_date', $format, '');
        $f->setDescriptionFormat("To end date: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'Yes', null, 'WHERE tr.coach = "' . $_SESSION['user']-> id . '" '));
        $f = new VoltDropDownViewFilter('filter_coach', $options, 0, false);
        $f->setDescriptionFormat("Coach: %s");
        $view->addFilter($f);

        return $view;
    }

    public static function getStartsGraphs(PDO $link)
    {
        $previous_3_months = [];
        for($i = 5; $i >= 0; $i--)
        {
            $month_name = date("F Y",strtotime("-{$i} Months"));

            $data = new stdClass();
            $data->month = $month_name;
            $data->month_start_date = date('Y-m-01', strtotime($month_name));
            $data->month_last_date = date('Y-m-t', strtotime($month_name));
            $data->tr_ids = [];

            $previous_3_months[$month_name] = $data;
        }

        $start_date = date("Y",strtotime("-5 Months")) . "-" . date("m",strtotime("-5 Months")) . "-01";

        $view = VoltView::getViewFromSession('getStartsGraph', 'getStartsGraph'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getStartsGraph'] = self::createView($link, 'getStartsGraph', false);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [1],
            'filter_from_start_date' => $start_date,
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement()->__toString();	
        $st = DAO::query($link, $sql);
        if($st)
        {
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                if( isset($previous_3_months[$row['_start_month_year']]) )
                    $previous_3_months[$row['_start_month_year']]->tr_ids[] = $row['_tr_id'];
            }
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

        return $previous_3_months;
    }

    public static function getOnProgrammeStats(PDO $link)
    {
        $result = [
            'on_programme' => [],
            'overstayer' => [],
            'on_programme_by_duration_left_graph' => '',
        ];

        $graph_categories = [
            '> 90 days left' => 0,
            '0-90 days left' => 0,
            'Overstayer' => 0,
        ];

        $view = VoltView::getViewFromSession('getOnProgrammeStats', 'getOnProgrammeStats'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getOnProgrammeStats'] = self::createView($link, 'getOnProgrammeStats', false);
        }
        //pr($view->getSQLStatement()->__toString());
        $filters = [
            '_reset' => 1,
            'filter_status_code' => [1],
	    'filter_contract_year' => $_SESSION['current_submission_year'],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $st = DAO::query($link, $sql->__toString());
        if($st)
        {
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $result['on_programme'][] = $row['_tr_id'];
                if($row['_overstayer'] == '1')
                {
                    $result['overstayer'][] = $row['_tr_id'];
                    $graph_categories['Overstayer']++;
                }
                else
                {
                    if(isset($graph_categories[$row['_diff_plan_end_date']]))
                        $graph_categories[$row['_diff_plan_end_date']]++;
                }
            }
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

        $result['on_programme_by_duration_left_graph'] = self::getOnProgrammeGraphByDurationLeftGraph($graph_categories);

        return $result;
    }

    public static function getUpcomingCompletionsGraph(PDO $link)
    {
        $view = VoltView::getViewFromSession('getUpcomingCompletionsGraph', 'getUpcomingCompletionsGraph'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getUpcomingCompletionsGraph'] = self::createView($link, 'getUpcomingCompletionsGraph', false);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [1],
            'filter_from_target_date' => date('Y-m-d'),
            'filter_to_target_date' => date('Y-m-d', strtotime('+6 months')),
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',	
        ];

        $view->refresh($filters, $link);

        $data = [];

        $sql = $view->getSQLStatement();
        $sql->setClause("ORDER BY tr.target_date");
        $st = DAO::query($link, $sql->__toString());
        if($st)
        {
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $data[$row['_planned_end_month_year']][] = $row['_tr_id'];
            }
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column'];
        $options->title = (object)['text' => 'Completions Due in the next 6 months'];
        $options->subtitle = (object)['text' => 'by planned end month'];
        $options->xAxis = (object)[
            'type' => 'category',
            'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '8px', 'fontFamily' => 'Verdana, sans-serif']]
        ];
        $options->yAxis = (object)['min' => 0, 'title' => (object)['text' => 'Learners']];
        $options->legend = (object)['enabled' => false];
        $options->tooltip = (object)['pointFormat' => 'Learners: <b>{point.y}</b>'];
        $options->plotOptions = (object)[
            'series' => (object)[
                'shadow' => true,
                'borderWidth' => 0,
                'color' => 'green'
            ]
        ];

        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->data = [];
        foreach($data AS $key => $value)
        {
            $series->data[] = [$key, count($value)];
        }
        $series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public static function getWithdrawalsGraph(PDO $link)
    {
        $data = [
            'within_qualifying' => [],
            'after_qualifying' => [],
        ];

        $view = VoltView::getViewFromSession('getWithdrawalsGraph', 'getWithdrawalsGraph'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getWithdrawalsGraph'] = self::createView($link, 'getWithdrawalsGraph', false);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [3],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE tr.outcome = '3'");
        $current_submission_year = $_SESSION['current_submission_year'];
        $sql->setClause("WHERE contracts.contract_year = '{$current_submission_year}'");

        $st = DAO::query($link, $sql->__toString());
        if($st)
        {
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                if($row['_days_left_after_start'] <= 42 )
                {
                    $data['within_qualifying'][] = $row['_tr_id'];
                }
                else
                {
                    $data['after_qualifying'][] = $row['_tr_id'];
                }
            }
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'bar'];
        $options->title = (object)['text' => 'Withdrawals in ' . $current_submission_year . '/' . ++$current_submission_year];
        $options->subtitle = (object)['text' => 'number of withdrawals since 1st Aug ' . --$current_submission_year];
        $options->plotOptions = (object)[
            'series' => (object)[
                'shadow' => true,
                'borderWidth' => 0,
                'stacking' => true
            ]
        ];
        $options->xAxis = (object)[
            'categories' => ['Learners'],
            'lineWidth' => 1
        ];
        $options->tooltip = (object)['pointFormat' => '{series.name}: <b>{point.y}</b>'];

        $options->series = [
            (object)[
                'name' => 'after qualifying period',
                'legendIndex' => 1,
                'stack' => 'A',
                'color' => 'red',
                'data' => [count($data['after_qualifying'])],
                'dataLabels' => (object)['enabled' => true, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
            ],
            (object)[
                'name' => 'Within qualifying period',
                'legendIndex' => 1,
                'stack' => 'A',
                'color' => 'green',
                'data' => [count($data['within_qualifying'])],
                'dataLabels' => (object)['enabled' => true, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
            ]
        ];

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public static function getOverstayersByExpectedMonthGraph(PDO $link)
    {
        $view = VoltView::getViewFromSession('getOverstayersByExpectedMonthGraph', 'getOverstayersByExpectedMonthGraph'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getOverstayersByExpectedMonthGraph'] = self::createView($link, 'getOverstayersByExpectedMonthGraph', false);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [1],
            'filter_to_target_date' => date('Y-m-d'),
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $data = [];

        $sql = $view->getSQLStatement();
        $sql->setClause("ORDER BY tr.target_date");
        $st = DAO::query($link, $sql->__toString());
        if($st)
        {
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                if($row['_overstayer'] == '1')
                {
                    $data[$row['_planned_end_month_year']][] = $row['_tr_id'];
                }
            }
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column'];
        $options->title = (object)['text' => 'Overstayers'];
        $options->subtitle = (object)['text' => 'by expected month of completion'];
        $options->xAxis = (object)[
            'type' => 'category',
            'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '8px', 'fontFamily' => 'Verdana, sans-serif']]
        ];
        $options->yAxis = (object)['min' => 0, 'title' => (object)['text' => 'Learners']];
        $options->legend = (object)['enabled' => false];
        $options->tooltip = (object)['pointFormat' => 'Learners: <b>{point.y}</b>'];
        $options->plotOptions = (object)[
            'series' => (object)[
                'shadow' => true,
                'borderWidth' => 0,
                'color' => 'red'
            ]
        ];

        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->data = [];
        foreach($data AS $key => $value)
        {
            $series->data[] = [$key, count($value)];
        }
        $series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public static function getOnProgrammeGraphByDurationLeftGraph($data)
    {
        $options = new stdClass();
        $options->chart = (object)['type' => 'bar'];
        $options->title = (object)['text' => 'On-Programme learners'];
        $options->subtitle = (object)['text' => 'by duration of course left'];
        $options->plotOptions = (object)[
            'series' => (object)[
                'shadow' => true,
                'borderWidth' => 0,
                'stacking' => true,
                'point' => (object)[
                    'events' => (object)[
                        'click' => 'function (){updateURL(\"filterEthnicity[]\", this.options.key);}'
                    ]
                ]
            ]
        ];
        $options->xAxis = (object)[
            'categories' => ['Learners'],
            'lineWidth' => 1
        ];
        $options->tooltip = (object)['pointFormat' => '{series.name}: <b>{point.y}</b>'];

        $options->series = [
            (object)[
                'name' => 'Overstayer',
                'legendIndex' => 1,
                'stack' => 'A',
                'color' => 'red',
                'data' => [$data['Overstayer']],
                'dataLabels' => (object)['enabled' => true, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
            ],
            (object)[
                'name' => '0-90 days left',
                'legendIndex' => 1,
                'stack' => 'A',
                'color' => 'orange',
                'data' => [$data['0-90 days left']],
                'dataLabels' => (object)['enabled' => true, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
            ],
            (object)[
                'name' => '> 90 days left',
                'legendIndex' => 1,
                'stack' => 'A',
                'color' => 'green',
                'data' => [$data['> 90 days left']],
                'dataLabels' => (object)['enabled' => true, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
            ]
        ];

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public static function getLearnersInTraining(PDO $link)
    {
        $result = [
            'row_count' => 0,
            'url' => '',
        ];

        $view = VoltView::getViewFromSession('getLearnersInTraining', 'getLearnersInTraining'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getLearnersInTraining'] = self::createView($link, 'getLearnersInTraining', true);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [1],
            'filter_contract_year' => $_SESSION['current_submission_year'],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        //pr($sql->__toString());
        $st = DAO::query($link, $sql->__toString());
        if($st)
        {
            $result['row_count'] = $st->rowCount();
            $result['url'] = "do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_contract_year={$_SESSION['current_submission_year']}";
            return $result;
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public static function getLearnersPastPlannedEndDate(PDO $link)
    {
        $result = [
            'row_count' => 0,
            'url' => '',
        ];

        $view = VoltView::getViewFromSession('getLearnersPastPlannedEndDate', 'getLearnersPastPlannedEndDate'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getLearnersPastPlannedEndDate'] = self::createView($link, 'getLearnersPastPlannedEndDate', true);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [1],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");
        $sql->setClause("WHERE target_date < CURDATE()");
        $st = DAO::query($link, $sql->__toString());
        if($st)
        {
            $result['row_count'] = $st->rowCount();
            $result['url'] = "do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_filter_contract_year={$_SESSION['current_submission_year']}&ViewTrainingRecords_target_end_date=".date('d/m/Y');
            return $result;
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public static function getLearnersTemporarilyWithdrawn(PDO $link)
    {
        $result = [
            'row_count' => 0,
            'url' => '',
        ];

        $view = VoltView::getViewFromSession('getLearnersTemporarilyWithdrawn', 'getLearnersTemporarilyWithdrawn'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getLearnersTemporarilyWithdrawn'] = self::createView($link, 'getLearnersTemporarilyWithdrawn', true);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [6],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");
        $sql->setClause("WHERE tr.l03 NOT IN (SELECT tr2.l03 FROM tr AS tr2 WHERE tr2.`start_date` > tr.`start_date` AND tr2.status_code != '6')");
        $st = DAO::query($link, $sql->__toString());
        if($st)
        {
            $result['row_count'] = $st->rowCount();
            $result['url'] = "do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=6&ViewTrainingRecords_filter_contract_year={$_SESSION['current_submission_year']}";
            return $result;
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public static function getLearnersWithdrawn(PDO $link)
    {
        $result = [
            'row_count' => 0,
            'url' => '',
        ];

        $view = VoltView::getViewFromSession('getLearnersWithdrawn', 'getLearnersWithdrawn'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getLearnersWithdrawn'] = self::createView($link, 'getLearnersWithdrawn', true);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [3],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");
        $st = DAO::query($link, $sql->__toString());
        if($st)
        {
            $result['row_count'] = $st->rowCount();
            $result['url'] = "do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=3&ViewTrainingRecords_filter_contract_year={$_SESSION['current_submission_year']}";
            return $result;
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public static function getLearnersCompleted(PDO $link)
    {
        $result = [
            'row_count' => 0,
            'url' => '',
        ];

        $view = VoltView::getViewFromSession('getLearnersCompleted', 'getLearnersCompleted'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getLearnersCompleted'] = self::createView($link, 'getLearnersCompleted', true);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [2],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");
        $st = DAO::query($link, $sql->__toString());
        if($st)
        {
            $result['row_count'] = $st->rowCount();
            $result['url'] = "do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=2&ViewTrainingRecords_filter_contract_year={$_SESSION['current_submission_year']}";
            return $result;
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public static function getL2L3Progressions(PDO $link)
    {
        $result = [
            'row_count' => 0,
            'url' => '',
        ];

        $contract_year = $_SESSION['current_submission_year'];
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
            $result['row_count'] = $res[0]['cnt'] == '' ? 0 : $res[0]['cnt'];

        $result['url'] = "do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=0&ViewL2L3Progression_filter_second_contract_year={$contract_year}";

        return $result;
    }

    public static function getL3L4Progressions(PDO $link)
    {
        $result = [
            'row_count' => 0,
            'url' => '',
        ];

        $contract_year = $_SESSION['current_submission_year'];
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
            $result['row_count'] = $res[0]['cnt'] == '' ? 0 : $res[0]['cnt'];

        $result['url'] = "do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=1&ViewL2L3Progression_filter_second_contract_year={$contract_year}";

        return $result;
    }

    public static function getTtoAProgressions(PDO $link)
    {
        $result = [
            'row_count' => 0,
            'url' => '',
        ];

        $contract_year = $_SESSION['current_submission_year'];
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
            $result['row_count'] = $res[0]['cnt'] == '' ? 0 : $res[0]['cnt'];

        $result['url'] = "do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=2&ViewL2L3Progression_filter_second_contract_year={$contract_year}";

        return $result;
    }

    public static function getStudyProgressions(PDO $link)
    {
        $result = [
            'row_count' => 0,
            'url' => '',
        ];

        $contract_year = $_SESSION['current_submission_year'];
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
            $result['row_count'] = $res[0]['cnt'] == '' ? 0 : $res[0]['cnt'];

        $result['url'] = "do.php?_action=view_l2l3_progression&_reset=1&ViewL2L3Progression_filter_report_type=1&ViewL2L3Progression_filter_second_contract_year={$contract_year}";

        return $result;
    }

    public static function getLearnersByProgressGraph(PDO $link)
    {
        $result = [
            'graph' => '',
            'Behind' => [
                'row_count' => 0,
                'url' => 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_progress=2',
            ],
            'On Track' => [
                'row_count' => 0,
                'url' => 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_progress=1',
            ]
        ];

        $view = VoltView::getViewFromSession('getLearnersByProgressGraph', 'getLearnersByProgressGraph'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getLearnersByProgressGraph'] = self::createView($link, 'getLearnersByProgressGraph', true);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [1],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");
        $st = DAO::query($link, $sql->__toString());

        $status = [
            'Behind' => 0,
            'On Track' => 0,
        ];
        $drilldown_stats = [
            '0-10' => 0,
            '11-25' => 0,
            '26-50' => 0,
            '50Plus' => 0,
        ];
        if($st)
        {
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                if(floatval($row['target']) >= 0 || floatval($row['percentage_completed']) >= 0)
                {
                    if(floatval($row['percentage_completed']) < floatval($row['target']))
                        $result['Behind']['row_count']++;
                    else
                        $result['On Track']['row_count']++;

                    $progress = $row['target'] - $row['percentage_completed'];
                    $progress = round($progress, 0);
                    if($progress >= 0 && $progress <= 10 )
                        $drilldown_stats['0-10'] += 1;
                    elseif($progress > 10 && $progress <= 25 )
                        $drilldown_stats['11-25'] += 1;
                    elseif($progress > 25 && $progress <= 50 )
                        $drilldown_stats['26-50'] += 1;
                    elseif($progress > 50 )
                        $drilldown_stats['50Plus'] += 1;
                }
            }
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

        $status['Behind'] = $result['Behind']['row_count'];
        $status['On Track'] = $result['On Track']['row_count'];

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie'];
        $options->title = (object)['text' => ''];
        $options->plotOptions = (object)[
            'pie' => (object )[
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => (object)[
                    'enabled' => true,
                    'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
                ]
            ]
        ];
        $options->series = [];
        $series = new stdClass();
        $series->data = [];
        foreach($status AS $key => $value)
        {
            $d = new stdClass();
            $d->name = $key;
            $d->y = $value;
            $d->key = $key;
            $d->color = $key == 'Behind' ? 'red' : 'green';
            $d->drilldown = $key == 'Behind' ? 'behindByMonth' : '';
            $series->data[] = $d;
        }
        $options->series[] = $series;

        $drilldown = new stdClass();
        $drilldown->series = [];
        $behindByMonth = new stdClass();
        $behindByMonth->id = 'behindByMonth';
        $behindByMonth->data = [];
        foreach($drilldown_stats AS $key => $value)
        {
            $behindByMonth->data[] = (object)[
                'name' => $key,
                'y' => $value,
            ];
        }
        $drilldown->series[] = $behindByMonth;
        $options->drilldown = $drilldown;

        $result['graph'] = json_encode($options, JSON_NUMERIC_CHECK);

        return $result;
    }

    public static function getLearnersByOtjProgressGraph(PDO $link)
    {
        $result = [
            'graph' => '',
            'Behind' => [
                'row_count' => 0,
                'url' => 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_otj_progress=2',
            ],
            'On Track' => [
                'row_count' => 0,
                'url' => 'do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_otj_progress=1',
            ]
        ];

        $view = VoltView::getViewFromSession('getLearnersByOtjProgressGraph', 'getLearnersByOtjProgressGraph'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getLearnersByOtjProgressGraph'] = self::createView($link, 'getLearnersByOtjProgressGraph', true);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [1],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");

        $st = DAO::query($link, $sql->__toString());

        $status = [
            'Behind' => 0,
            'On Track' => 0,
        ];

        if($st)
        {
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                if($row['otj_progress'] == 'On Track')
                {
                    $result['On Track']['row_count']++;
                }
                else
                {
                    $result['Behind']['row_count']++;
                }
            }
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

        $total_status = $result['Behind']['row_count']+$result['On Track']['row_count'];
        if($total_status > 0)
        {
            $status['Behind'] = round(($result['Behind']['row_count']/$total_status)*100, 2);
            $status['On Track'] = round(($result['On Track']['row_count']/$total_status)*100, 2);
        }

        $result['Behind']['row_count'] = $status['Behind'];
        $result['On Track']['row_count'] = $status['On Track'];

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie'];
        $options->title = (object)['text' => ''];
        $options->plotOptions = (object)[
            'pie' => (object )[
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => (object)[
                    'enabled' => true,
                    'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
                ]
            ]
        ];
        $options->series = [];
        $series = new stdClass();
        $series->data = [];
        foreach($status AS $key => $value)
        {
            $d = new stdClass();
            $d->name = $key;
            $d->y = $value;
            $d->key = $key;
            $d->color = $key == 'Behind' ? 'red' : 'green';
            $series->data[] = $d;
        }
        $options->series[] = $series;

        $result['graph'] = json_encode($options, JSON_NUMERIC_CHECK);

        return $result;
    }

    public static function getFileRepositoryGraph(PDO $link)
    {
        $result = [
            'graph' => '',
            'Used' => [
                'value' => 0,
            ],
            'Remaining' => [
                'value' => 0,
            ]
        ];

        $result['Used']['value'] = self::format_size(Repository::getUsedSpace());
        $result['Remaining']['value'] = self::format_size(Repository::getRemainingSpace());

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => ''];
        $options->plotOptions = (object)[
            'pie' => (object )[
                'allowPointSelect' => true,
                'cursor' => 'pointer'
            ]
        ];
        $options->series = [];
        $series = new stdClass();
        $series->name = 'Space';
        $series->colorByPoint = true;
        $series->data = [];
        $d = new stdClass();
        $d->name = 'Used';
        $d->y = $result['Used']['value'];
        $d->key = 'Used';
        $d->color = 'red';
        $series->data[] = $d;
        $d = new stdClass();
        $d->name = 'Remaining';
        $d->y = $result['Remaining']['value'];
        $d->key = 'Remaining';
        $d->color = 'green';
        $series->data[] = $d;

        $options->series[] = $series;

        $result['graph'] = json_encode($options, JSON_NUMERIC_CHECK);

        return $result;
    }

    public static function getLearnersByAssessorsGraph(PDO $link)
    {
        $result = [];

        $view = VoltView::getViewFromSession('getLearnersByAssessorsGraph', 'getLearnersByAssessorsGraph'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['getLearnersByAssessorsGraph'] = self::createView($link, 'getLearnersByAssessorsGraph', true);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => ['SHOW_ALL'],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");
        $sql->setClause("HAVING assessor != ''");//pr($sql->__toString());
        $st = DAO::query($link, $sql->__toString());
        if($st)
        {
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $row['assessor'] = $row['assessor'] == '' ? 'NotAssigned' : DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$row['assessor']}'");
                if(!isset($result[$row['assessor']]))
                {
                    $detail = new stdClass();
                    $detail->Continuing = 0;
                    $detail->Completed = 0;
                    $detail->EarlyLeavers = 0;
                    $detail->BIL = 0;
                    $result[$row['assessor']] = $detail;
                }

                if($row['status_code'] == 1)
                    $result[$row['assessor']]->Continuing++;
                if($row['status_code'] == 2)
                    $result[$row['assessor']]->Completed++;
                if($row['status_code'] == 3)
                    $result[$row['assessor']]->EarlyLeavers++;
                if($row['status_code'] == 6)
                    $result[$row['assessor']]->BIL++;
            }
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

        //$categories = "'" . implode("','", array_keys($result)) . "'";
        $categories = array_keys($result);

        $options = new stdClass();
        $options->chart = (object)['type' => 'column'];
        $options->title = (object)['text' => 'Learners by Assessors'];
        $options->xAxis = (object)['categories' => $categories, 'title' => (object)['text' => null]];
        $options->yAxis = (object)[
            'min' => 0,
            'title' => (object)['text' => 'Learners', 'align' => 'high'],
            'labels' => (object)['overflow' => 'justify']
        ];
        $options->legend = (object)[
            'layout' => 'vertical',
            'align' => 'right',
            'verticalAlign' => 'top',
            'x' => '-40',
            'y' => '80',
            'floating' => true,
            'borderWidth' => '1',
            'backgroundColor' => '#FFFFFF',
            'shadow' => true,
        ];
        $options->credits = (object)['enabled' => false];
        $options->plotOptions = (object)['column' => (object)['stacking' => true,'dataLabels' => (object)['enabled' => true]]];

        $continuing = [];
        $completed = [];
        $early_leavers = [];
        $bil = [];


        foreach($result AS $assessor => $detail)
        {
            $continuing[] = $detail->Continuing;
            $completed[] = $detail->Completed;
            $early_leavers[] = $detail->EarlyLeavers;
            $bil[] = $detail->BIL;
        }

        $series = new stdClass();
        $series->name = 'Continuing';
        $series->data = $continuing;

        $options->series[] = (object)[
            'name' => 'Continuing',
            'data' => $continuing
        ];

        $options->series[] = (object)[
            'name' => 'Completed',
            'data' => $completed
        ];

        $options->series[] = (object)[
            'name' => 'EarlyLeavers',
            'data' => $early_leavers
        ];

        $options->series[] = (object)[
            'name' => 'BIL',
            'data' => $bil
        ];
//pre($options);
        return json_encode($options, JSON_NUMERIC_CHECK);
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

    public static function GatewayLearnersBarChart(PDO $link)
    {
        $view = VoltView::getViewFromSession('GatewayLearnersBarChart', 'GatewayLearnersBarChart'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['GatewayLearnersBarChart'] = self::createView($link, 'GatewayLearnersBarChart', true);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [1],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");

        $records = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
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
        $options->chart = (object)['type' => 'column', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => 'Gateway by remaining month'];
        $options->plotOptions = (object)[
            'column' => (object )[
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

    public static function gatewayLearnersStats(PDO $link)
    {
        $stats = [
            'gateway1' => 0,
            'gateway2' => 0,
            'gateway3' => 0,
        ];

        $view = VoltView::getViewFromSession('gatewayLearnersStats', 'gatewayLearnersStats'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['gatewayLearnersStats'] = self::createView($link, 'gatewayLearnersStats', true);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [1],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");
        $sql->setClause(" WHERE tr.id IN (SELECT tr_epa.tr_id  FROM tr_epa WHERE CURDATE() = tr_epa.`epa_prop_date1`)");

        $st = DAO::query($link, $sql->__toString());
        $stats['gateway1'] = $st->rowCount();

        $sql->removeClause("WHERE");

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");
        $sql->setClause(" WHERE tr.id IN (SELECT tr_epa.tr_id  FROM tr_epa WHERE CURDATE() = tr_epa.`epa_prop_date2`)");

        $st = DAO::query($link, $sql->__toString());
        $stats['gateway2'] = $st->rowCount();

        $sql->removeClause("WHERE");

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");
        $sql->setClause(" WHERE tr.id IN (SELECT tr_epa.tr_id  FROM tr_epa WHERE CURDATE() = tr_epa.`epa_prop_date3`)");

        $st = DAO::query($link, $sql->__toString());
        $stats['gateway3'] = $st->rowCount();


        return $stats;
    }

    public static function learnersDueToFinishIn2Months(PDO $link)
    {
        $result = ['row_count' => 0, 'url' => ''];
        $view = VoltView::getViewFromSession('learnersDueToFinishIn2Months', 'learnersDueToFinishIn2Months'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['learnersDueToFinishIn2Months'] = self::createView($link, 'learnersDueToFinishIn2Months', true);
        }

        $filters = [
            '_reset' => 1,
            'filter_status_code' => [1],
            'filter_coach' => (in_array(DB_NAME, ["am_lead_demo", "am_lead"]) && !$_SESSION['user']->isAdmin()) ? '1' : '0',
        ];

        $view->refresh($filters, $link);

        $sql = $view->getSQLStatement();
        $sql->setClause("WHERE contracts.contract_year = '{$_SESSION['current_submission_year']}'");
        $sql->setClause("WHERE tr.`target_date` BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 60 DAY)");

        $st = DAO::query($link, $sql->__toString());

        $result['row_count'] = $st->rowCount();
        $day_60 = DAO::getSingleValue($link, "SELECT DATE_ADD(CURDATE(), INTERVAL 60 DAY)");
        $result['url'] = "do.php?_action=view_training_records&_reset=1&ViewTrainingRecords_filter_record_status[]=1&ViewTrainingRecords_target_start_date=". date('d/m/Y') . "&ViewTrainingRecords_target_end_date=" . Date::toShort($day_60);

        return $result;
    }

    public static function renderGauage(PDO $link, $title)
    {
        $min = 0;
        $max = rand(100, 250);
        $tickInterval = $max/1000;
        $value = rand(1, 95);

        $options = new stdClass();
        $options->chart = (object)['type' => 'solidgauge'];
        $options->title = null;
        $options->pane = (object)[
            'center' => ['50%', '85%'],
            'size' => '140%',
			'startAngle' => -90,
            'endAngle' => 90,
			'background' => (object)[
                'backgroundColor' => "green",
                'innerRadius' => '60%',
                'outerRadius' => '100%',
                'shape' => 'arc'
            ]
        ];
        $options->tooltip = (object)['enabled' => false ];
        $options->credits = (object)['enabled' => false ];
        $options->yAxis = (object)[
            'stops' => [
                [0.1, '#55BF3B'], // green
                [0.5, '#DDDF0D'], // yellow
                [0.9, '#DF5353'] // red
            ],
            'lineWidth' => 0,
            'tickWidth' => 0,
            'minorTickInterval' => null,
            'tickAmount' => 2,
            'title' => (object)[
                'y' => -70,
                'text' => $title . ' Progression'
            ],
            'labels' => (object)[
                'y' => 16,
                'formatter' => ''
            ],
            'min' => $min,
            'max' => $max,
            'tickInterval' => $tickInterval,

        ];
        $options->plotOptions = (object)[
            'solidgauge' => (object)[
                'dataLabels' => (object)[
                    'y' => 5,
                    'borderWidth' => 0,
                    'useHTML' => true
                ]
            ]
        ];


        $options->series = [];
        $series = new stdClass();
        $series->name = 'L2 to L3';
        $series->data = [$value];
        $series->dataLabels = (object)[
            'format' => '<div style="text-align:center"><span style="font-size:25px;' .
                'color:\'#90EE90\'">{y}</span><br/>' .
                '<span style="font-size:12px;color:silver">L2 to L3</span></div>'
        ];

        $series->tooltip = (object)[
            'valueSuffix' => ' L2 to L3'
        ];



        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }
}
