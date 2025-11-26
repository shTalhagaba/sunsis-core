<?php
class monthly_report_v2 implements IAction
{
    private function getDates(PDO $link, $contract_year, $start_month = '1', $end_month = '1')
    {
        $month = 8;
        $start_date = '';
        $end_date = '';
        for($i = 1; $i <= 12; $i++)
        {
            $start_date_of_month = new Date($contract_year . '-'.$month.'-01');
            $last_date_of_month = DAO::getSingleValue($link, "SELECT LAST_DAY('{$start_date_of_month->formatMySQL()}')");
            $last_date_of_month = new Date($last_date_of_month);
            if($month == 12)
            {
                $month = 0;
                $contract_year++;
            }
            $month++;
            $start_date = $i == $start_month ? $start_date_of_month->formatMySQL() : $start_date;
            $end_date = $i == $end_month ? $last_date_of_month->formatMySQL() : $end_date;
        }
        return (object)['start_date' => $start_date, 'end_date' => $end_date];
    }

    public function execute(PDO $link)
    {
        $_m = (int)date('m');
        $_m = (($_m >= 1) && ($_m <= 7)) ? $_m += 5 : $_m -= 7;

        $filterYear = isset($_REQUEST['filterYear']) ? $_REQUEST['filterYear'] : DAO::getSingleValue($link, "SELECT contract_year FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");
        $startMonth = isset($_REQUEST['startMonth']) ? $_REQUEST['startMonth'] : $_m;
        $endMonth = isset($_REQUEST['endMonth']) ? $_REQUEST['endMonth'] : $_m;
        $filterReportType = isset($_REQUEST['filterReportType']) ? $_REQUEST['filterReportType'] : 'starts';
        $filterGender = isset($_REQUEST['filterGender']) ? $_REQUEST['filterGender'] : 'S';
        $filterAgeBand = isset($_REQUEST['filterAgeBand']) ? $_REQUEST['filterAgeBand'] : 'S';
        $filterLLDD = isset($_REQUEST['filterLLDD']) ? $_REQUEST['filterLLDD'] : 'S';
        $filterFundingProvision = isset($_REQUEST['filterFundingProvision']) ? $_REQUEST['filterFundingProvision'] : array(0);
        $filterEthnicity = isset($_REQUEST['filterEthnicity']) ? $_REQUEST['filterEthnicity'] : array(0);

        $table = self::MASTER_TABLE;
        $sql = $this->applyCommonFilters($filterGender, $filterAgeBand, $filterLLDD, $filterFundingProvision, $filterEthnicity);

        $dates = $this->getDates($link, $filterYear, $startMonth, $endMonth);
        if($filterReportType == 'starts')
        {
            $sql->setClause("WHERE {$table}.start_date BETWEEN '{$dates->start_date}' AND '{$dates->end_date}'");
        }
        elseif($filterReportType == 'restarts')
        {
            $sql->setClause("WHERE {$table}.start_date BETWEEN '{$dates->start_date}' AND '{$dates->end_date}'");
            $sql->setClause("WHERE {$table}.restart = '1'");
        }
        elseif($filterReportType == 'continuing')
        {
            $sql->setClause("WHERE {$table}.start_date <= '{$dates->end_date}'");
            $sql->setClause("WHERE ({$table}.actual_end_date IS NULL) OR ({$table}.actual_end_date > '{$dates->start_date}')");
        }
        elseif($filterReportType == 'overdue')
        {
            $sql->setClause("WHERE {$table}.planned_end_date < '{$dates->end_date}'");
            $sql->setClause("WHERE {$table}.actual_end_date IS NULL  OR {$table}.actual_end_date > '{$dates->start_date}'");
        }
        elseif($filterReportType == 'ended' || $filterReportType == 'withdrawn' || $filterReportType == 'break_in_learning' || $filterReportType == 'achievers')
        {
            $sql->setClause("WHERE {$table}.actual_end_date BETWEEN '{$dates->start_date}' AND '{$dates->end_date}'");
            switch($filterReportType)
            {
                case 'ended':
                    $sql->setClause("WHERE {$table}.completion_status != '1'");
                    break;
                case 'withdrawn':
                    $sql->setClause("WHERE {$table}.completion_status = '3'");
                    break;
                case 'break_in_learning':
                    $sql->setClause("WHERE {$table}.completion_status = '6'");
                    break;
                case 'achievers':
                    $sql->setClause("WHERE {$table}.completion_status = '2'");
                    break;
            }
        }

        $view = new VoltView('Demographics', $sql);
        $view->refresh(array(), $link);

        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
        if($subaction == 'export_to_csv')
        {
            $view->exportToCSV($link);
            exit;
        }

        //pre($view);

        $panelLearnersByRegionAndSSA1 = $this->learners_by_region_and_ssa1($link, $view);
        $panelLearnersByLevel = $this->learners_by_level($link, $view);
        $panelLearnersByEthnicity = $this->learners_by_ethnicity($link, $view);//pre(json_decode($panelLearnersByEthnicity));
        $panelLearnersByGender = $this->learners_by_gender($link, $view);
        $panelLearnersByAgeBand = $this->learners_by_age_band($link, $view);
        $panelLearnersByFundingProvision = $this->learners_by_funding_provision($link, $view);
        $panelLearnersByAssessors = $this->learners_by_assessor($link, $view);
        $panelLearnersByPrimaryLLDD = $this->learners_by_primary_lldd($link, $view);
        $panelLearnersByRegion = $this->learners_by_employer_region($link, $view);
        $panelLearnersByProgType = $this->learners_by_prog_type($link, $view);
        $panelLearnersByStartMonth = $this->learners_by_starts($link, $view);
//		$panelLearnersBySSA1 = $this->learners_by_SSA($link, $view, 'ssa1');
//		$panelLearnersBySSA2 = $this->learners_by_SSA($link, $view, 'ssa2');
//		$panelLearnersByFwk = $this->learners_by_fwk($link, $view);

        include_once('tpl_monthly_report_v2.php');
    }

    private function applyCommonFilters($filterGender, $filterAgeBand, $filterLLDD, $filterFundingProvision, $filterEthnicity)
    {
        $table = self::MASTER_TABLE;
        $sql = <<<SQL
SELECT
	DISTINCT l03, tr_id, firstnames, surname, ethnicity_code, gender, funding_provision, age_band, assessor, lldd,
	 start_date, planned_end_date, actual_end_date, completion_status, primary_lldd, employer_region, framework_type
	, SSA1, MONTH(start_date) AS start_month, learner_level
FROM
	$table
SQL;
        $sql = new SQLStatement($sql);
        if($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type == User::TYPE_MANAGER || $_SESSION['user']->type == User::TYPE_ORGANISATION_VIEWER)
        {
            $sql->setClause("WHERE {$table}.provider_id = '{$_SESSION['user']->employer_id}' OR {$table}.employer_id = '{$_SESSION['user']->employer_id}'");
        }

        $view = new VoltView('TempView', $sql);

        $options = array(
            0 => array('S', 'Show All', null, null),
            1 => array('M', 'Male', null, 'WHERE '.$table.'.gender = "M"'),
            2 => array('F', 'Female', null, 'WHERE '.$table.'.gender = "F"')
        );
        $f = new VoltDropDownViewFilter('filterGender', $options, $filterGender, false);
        $view->addFilter($f);

        $options = array(
            0 => array('S', 'Show All', null, null),
            1 => array('1618', '16-18', null, 'WHERE '.$table.'.age_band = "16-18"'),
            2 => array('1923', '19-23', null, 'WHERE '.$table.'.age_band = "19-23"'),
            3 => array('24', '24+', null, 'WHERE '.$table.'.age_band = "24+"')
        );
        $f = new VoltDropDownViewFilter('filterAgeBand', $options, $filterAgeBand, false);
        $view->addFilter($f);

        $options = array(
            0 => array('S', 'Show All', null, null),
            1 => array('1', 'With LLDD', null, 'WHERE '.$table.'.lldd = "1"'),
            2 => array('2', 'Without LLDD', null, 'WHERE '.$table.'.lldd = "2"'),
            3 => array('9', 'No Info.', null, 'WHERE '.$table.'.lldd = "9"')
        );
        $f = new VoltDropDownViewFilter('filterLLDD', $options, $filterLLDD, false);
        $view->addFilter($f);

        $options = array(
            0 => array('0', 'Show all', null, 'WHERE '.$table.'.ethnicity_code IN (31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,98,99)'),
            1 => array('31', 'British', null, 'WHERE '.$table.'.ethnicity_code = "31"'),
            2 => array('32', 'Irish', null, 'WHERE '.$table.'.ethnicity_code = "32"'),
            3 => array('33', 'Gypsy or Irish Traveller', null, 'WHERE '.$table.'.ethnicity_code = "33"'),
            4 => array('34', 'Any other White background', null, 'WHERE '.$table.'.ethnicity_code = "34"'),
            5 => array('35', 'White and Black Caribbean', null, 'WHERE '.$table.'.ethnicity_code = "35"'),
            6 => array('36', 'White and Black African', null, 'WHERE '.$table.'.ethnicity_code = "36"'),
            7 => array('37', 'White and Asian', null, 'WHERE '.$table.'.ethnicity_code = "37"'),
            8 => array('38', 'Any other Mixed', null, 'WHERE '.$table.'.ethnicity_code = "38"'),
            9 => array('39', 'Indian', null, 'WHERE '.$table.'.ethnicity_code = "39"'),
            10 => array('40', 'Pakistani', null, 'WHERE '.$table.'.ethnicity_code = "40"'),
            11 => array('41', 'Bangladeshi', null, 'WHERE '.$table.'.ethnicity_code = "41"'),
            12 => array('42', 'Chinese', null, 'WHERE '.$table.'.ethnicity_code = "42"'),
            13 => array('43', 'Any other Asian', null, 'WHERE '.$table.'.ethnicity_code = "43"'),
            14 => array('44', 'African', null, 'WHERE '.$table.'.ethnicity_code = "44"'),
            15 => array('45', 'Caribbean', null, 'WHERE '.$table.'.ethnicity_code = "45"'),
            16 => array('46', 'Any other Black', null, 'WHERE '.$table.'.ethnicity_code = "46"'),
            17 => array('47', 'Arab', null, 'WHERE '.$table.'.ethnicity_code = "47"'),
            18 => array('98', 'Any other ethnic group', null, 'WHERE '.$table.'.ethnicity_code = "98"'),
            19 => array('99', 'Not known/not provided', null, 'WHERE '.$table.'.ethnicity_code = "99"')
        );
        $f = new VoltCheckboxViewFilter('filterEthnicity', $options, $filterEthnicity);
        $view->addFilter($f);

        $options = array(
            0 => array('0', 'Show all', null, 'WHERE '.$table.'.funding_provision IN (1,2,3,4,5,6,7,8,9)'),
            1 => array('1', '16-18 Apprenticeship', null, 'WHERE '.$table.'.funding_provision = "1"'),
            2 => array('2', '19-23 Apprenticeship', null, 'WHERE '.$table.'.funding_provision = "2"'),
            3 => array('3', '16-18 Levy Apprenticeship', null, 'WHERE '.$table.'.funding_provision = "3"'),
            4 => array('4', '19+ Levy Apprenticeship', null, 'WHERE '.$table.'.funding_provision = "4"'),
            5 => array('5', 'All Ages - Levy Apprenticeship', null, 'WHERE '.$table.'.funding_provision = "5"'),
            6 => array('6', 'Study Programme', null, 'WHERE '.$table.'.funding_provision = "6"'),
            7 => array('7', 'Traineeship', null, 'WHERE '.$table.'.funding_provision = "7"'),
            8 => array('8', 'Learner Loans', null, 'WHERE '.$table.'.funding_provision = "8"'),
            9 => array('9', 'Other', null, 'WHERE '.$table.'.funding_provision = "9"')
        );
        $f = new VoltCheckboxViewFilter('filterFundingProvision', $options, $filterFundingProvision);
        $view->addFilter($f);

        return $view->getSQLStatement();
    }

    public function learners_by_ethnicity(PDO $link, VoltView $view)
    {
        $ethnicities = [
            '31' => 'British',
            '32' => 'Irish',
            '33' => 'Gypsy or Irish Traveller',
            '34' => 'Any other White background',
            '35' => 'White and Black Caribbean',
            '36' => 'White and Black African',
            '37' => 'White and Asian',
            '38' => 'Any other Mixed',
            '39' => 'Indian',
            '40' => 'Pakistani',
            '41' => 'Bangladeshi',
            '42' => 'Chinese',
            '43' => 'Any other Asian',
            '44' => 'African',
            '45' => 'Caribbean',
            '46' => 'Any other Black',
            '47' => 'Arab',
            '98' => 'Any other ethnic group',
            '99' => 'Not known/not provided'
        ];

        $sql = $view->getSQLStatement();

        $data = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            if(!isset($data[$row['ethnicity_code']]))
            {
                $data[$row['ethnicity_code']] = 0;
            }
            $data[$row['ethnicity_code']] += 1;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => 'Learners by Ethnicity'];
        $options->plotOptions = (object)[
            'pie' => (object )[
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => (object)[
                    'enabled' => true,
                    'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
                ],
                'showInLegend' => true
            ],
            'series' => (object)[
                'point' => (object)[
                    'events' => (object)[
                        'click' => 'function (){updateURL(\"filterEthnicity[]\", this.options.key);}'
                    ]
                ]
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
            $d->name = $ethnicities[$key];
            $d->y = $value;
            $d->key = $key;
            $series->data[] = $d;
        }
        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function learners_by_prog_type(PDO $link, VoltView $view)
    {
        $levels = ['2' => '2 Advanced Level Apprenticeship',
            '3' => '3 Intermediate Level Apprenticeship',
            '20' => '20 Higher Level Apprenticeship (Level 4)',
            '21' => '21 Higher Level Apprenticeship (Level 5)',
            '22' => '22 Higher Level Apprenticeship (Level 6)',
            '23' => '23 Higher Level Apprenticeship (Level 7+)',
            '24' => '24 Traineeship',
            '25' => '25 Apprenticeship Standard',
            '' => 'NA'
        ];

        $sql = $view->getSQLStatement();

        $data = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            if(!isset($data[$row['framework_type']]))
            {
                $data[$row['framework_type']] = 0;
            }
            $data[$row['framework_type']] += 1;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => 'Learners by Programme Type'];
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
            $d->name = $levels[$key];
            $d->y = $value;
            $d->key = $key;
            $series->data[] = $d;
        }
        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function learners_by_employer_region(PDO $link, VoltView $view)
    {
        $sql = $view->getSQLStatement();

        $data = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            if(!isset($data[$row['employer_region']]))
            {
                $data[$row['employer_region']] = 0;
            }
            $data[$row['employer_region']] += 1;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => 'Learners by Employer Region'];
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

    public function learners_by_gender(PDO $link, VoltView $view)
    {
        $genders = ['M' => 'Male', 'F' => 'Female'];
        $sql = $view->getSQLStatement();

        $data = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            if($row['gender'] == 'M' || $row['gender'] == 'F')
            {
                if(!isset($data[$row['gender']]))
                {
                    $data[$row['gender']] = 0;
                }
                $data[$row['gender']] += 1;
            }
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => 'Learners by Gender'];
        $options->plotOptions = (object)[
            'pie' => (object )[
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => (object)[
                    'enabled' => true,
                    'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
                ],
                'showInLegend' => true
            ],
            'series' => (object)[
                'point' => (object)[
                    'events' => (object)[
                        'click' => 'function (){updateURL(\"filterGender\", this.options.key);}'
                    ]
                ]
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
            $d->name = $genders[$key];
            $d->y = $value;
            $d->key = $key;
            $series->data[] = $d;
        }
        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function learners_by_funding_provision(PDO $link, VoltView $view)
    {
        $funding_provisions = [
            '1' => '16-18 Apprenticeship'
            ,'2' => '19+ Apprenticeship'
            ,'3' => '16-18 Levy Apprenticeship'
            ,'4' => '19+ Levy Apprenticeship'
            ,'5' => 'All Ages - Levy Apprenticeship'
            ,'6' => 'Study Programme'
            ,'7' => 'Traineeship'
            ,'8' => 'Learner Loans'
            ,'9' => 'Other'
        ];
        $sql = $view->getSQLStatement();

        $data = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            if(!isset($data[$row['funding_provision']]))
            {
                $data[$row['funding_provision']] = 0;
            }
            $data[$row['funding_provision']] += 1;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column'];
        $options->title = (object)['text' => 'Learners by Funding Provision'];
        $options->plotOptions = (object)[
            'pie' => (object )[
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => (object)[
                    'enabled' => true,
                    'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
                ],
                'showInLegend' => true
            ],
            'series' => (object)[
                'point' => (object)[
                    'events' => (object)[
                        'click' => 'function (){updateURL(\"filterFundingProvision[]\", this.options.key);}'
                    ]
                ]
            ]
        ];
        $options->xAxis = (object)[
            'type' => 'category',
            'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
        ];

        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->colorByPoint = true;
        $series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']];
        $series->data = [];
        foreach($data AS $key => $value)
        {
            $d = new stdClass();
            $d->name = $funding_provisions[$key];
            $d->y = $value;
            $d->key = $key;
            $series->data[] = $d;
        }
        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function learners_by_primary_lldd(PDO $link, VoltView $view)
    {
        $LLDDCats = [
            "1" => "1 Emotional/behavioural difficulties",
            "2" => "2 Multiple disabilities",
            "3" => "3 Multiple learning difficulties",
            "4" => "4 Visual impairment",
            "5" => "5 Hearing impairment",
            "6" => "6 Disability affecting mobility",
            "7" => "7 Profound complex disabilities",
            "8" => "8 Social and emotional difficulties",
            "9" => "9 Mental health difficulty",
            "10" => "10 Moderate learning difficulty",
            "11" => "11 Severe learning difficulty",
            "12" => "12 Dyslexia",
            "13" => "13 Dyscalculia",
            "14" => "14 Autism spectrum disorder",
            "15" => "15 Asperger's syndrome",
            "16" => "16 Temporary disability after illness (for example post-viral) or accident",
            "17" => "17 Speech, Language and Communication Needs",
            "93" => "93 Other physical disability",
            "94" => "94 Other specific learning difficulty (e.g. Dyspraxia)",
            "95" => "95 Other medical condition (for example epilepsy, asthma, diabetes)",
            "96" => "96 Other learning difficulty",
            "97" => "97 Other disability",
            "98" => "98 Prefer not to say",
            "99" => "99 Not provided"
        ];
        $sql = $view->getSQLStatement();

        $data = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            if($row['primary_lldd'] != '')
            {
                if(!isset($data[$row['primary_lldd']]))
                {
                    $data[$row['primary_lldd']] = 0;
                }
                $data[$row['primary_lldd']] += 1;
            }
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column'];
        $options->title = (object)['text' => 'Learners by Primary LLDD'];
        $options->subtitle = (object)['text' => 'For 2017-18 onwards only'];
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
        $options->xAxis = (object)[
            'type' => 'category',
            'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
        ];

        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->colorByPoint = true;
        $series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']];
        $series->data = [];
        foreach($data AS $key => $value)
        {
            $d = new stdClass();
            $d->name = $LLDDCats[$key];
            $d->y = $value;
            $series->data[] = $d;
        }
        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function learners_by_level(PDO $link, VoltView $view)
    {
        $levels_list = DAO::getLookupTable($link, "SELECT DISTINCT learner_level, learner_level FROM charts_master WHERE learner_level IS NOT NULL");

        $sql = $view->getSQLStatement();

        $data = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            if($row['learner_level'] != '')
            {
                if(!isset($data[$row['learner_level']]))
                {
                    $data[$row['learner_level']] = 0;
                }
                $data[$row['learner_level']] += 1;
            }
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column'];
        $options->title = (object)['text' => 'Learners by Level'];
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
        $options->xAxis = (object)[
            'type' => 'category',
            'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
        ];

        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->colorByPoint = true;
        $series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']];
        $series->data = [];
        foreach($data AS $key => $value)
        {
            $d = new stdClass();
            $d->name = $levels_list[$key];
            $d->y = $value;
            $series->data[] = $d;
        }
        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function learners_by_age_band(PDO $link, VoltView $view)
    {
        $sql = $view->getSQLStatement();

        $data = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            if(!isset($data[$row['age_band']]))
            {
                $data[$row['age_band']] = 0;
            }
            $data[$row['age_band']] += 1;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => 'Learners by Age Band'];
        $options->plotOptions = (object)[
            'pie' => (object )[
                'allowPointSelect' => true,
                'cursor' => 'pointer',
                'dataLabels' => (object)[
                    'enabled' => true,
                    'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'
                ],
                'showInLegend' => true
            ],
            'series' => (object)[
                'point' => (object)[
                    'events' => (object)[
                        'click' => 'function (){updateURL(\"filterAgeBand\", this.options.key);}'
                    ]
                ]
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
            if($key == '16-18')
                $d->key = '1618';
            elseif($key == '19-23')
                $d->key = '1923';
            elseif($key == '24+')
                $d->key = '24';
            $series->data[] = $d;
        }
        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function learners_by_assessor(PDO $link, VoltView $view)
    {
        $sql = $view->getSQLStatement();

        $data = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            $row['assessor'] = is_null($row['assessor']) ? 'Not Assigned' : $row['assessor'];
            if(!isset($data[$row['assessor']]))
            {
                $data[$row['assessor']] = 0;
            }
            $data[$row['assessor']] += 1;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column'];
        $options->title = (object)['text' => 'Learners by Assessors'];
        $options->xAxis = (object)[
            'type' => 'category',
            'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']]
        ];
        $options->yAxis = (object)['min' => 0, 'title' => (object)['text' => 'Learners']];
        $options->legend = (object)['enabled' => false];
        $options->tooltip = (object)['pointFormat' => 'Learners: <b>{point.y}</b>'];

        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->data = [];
        foreach($data AS $key => $value)
        {
            $series->data[] = [$key, $value];
        }
        $series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

        $options->series[] = $series;


        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function learners_by_starts(PDO $link, VoltView $view)
    {
        $sql = $view->getSQLStatement();

        $data = [
            'August' => 0,
            'September' => 0,
            'October' => 0,
            'November' => 0,
            'December' => 0,
            'January' => 0,
            'February' => 0,
            'March' => 0,
            'April' => 0,
            'May' => 0,
            'June' => 0,
            'July' => 0
        ];

        $months = [
            'August',
            'September',
            'October',
            'November',
            'December',
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July'
        ];


        $funding_provisions = [
            '1' => '16-18 Apprenticeship'
            ,'2' => '19+ Apprenticeship'
            ,'3' => '16-18 Levy Apprenticeship'
            ,'4' => '19+ Levy Apprenticeship'
            ,'5' => 'All Ages - Levy Apprenticeship'
            ,'6' => 'Study Programme'
            ,'7' => 'Traineeship'
            ,'8' => 'Learner Loans'
            ,'9' => 'Other'
        ];



        $master = [];
        foreach($funding_provisions AS $key => $value)
        {
            foreach($data AS $_k => $_v)
            {
                $master[$value][$_k] = 0;
            }
        }
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            $row['start_month'] = is_null($row['start_month']) ? 'Blank' : $row['start_month'];

            $startMonth = $row['start_month'];
            $dateObj   = DateTime::createFromFormat('!m', $startMonth);
            $startMonth = $dateObj->format('F');

            $_fp = $funding_provisions[$row['funding_provision']];

            if(!isset($master[$_fp][$startMonth]))
            {
                $master[$_fp][$startMonth] = 0;
            }
            $master[$_fp][$startMonth] += 1;
        }

        $html = '<table class="table table-bordered table-striped">';
        $html .= '<thead><tr><th></th>';
        foreach($funding_provisions AS $fp)
        {
            $html .= '<th>' . $fp . '</th>';
        }
        $html .= '<th>Total</th></tr></thead>';
        $html .= '<tbody>';
        foreach($months AS $m)
        {
            $html .= '<tr>';
            $html .= '<td>' . $m . '</td>';
            $rowTotal = 0;
            foreach($funding_provisions AS $fp)
            {
                if($master[$fp][$m] > 0)
                    $html .= '<td align="center" class="text-green"><strong>' . $master[$fp][$m] . '</strong></td>';
                else
                    $html .= '<td align="center">' . $master[$fp][$m] . '</td>';
                $rowTotal += $master[$fp][$m];
            }
            $html .= '<td class="text-bold" align="center">' . $rowTotal . '</td></tr>';
        }
        $html .= '<tr><td></td>';
        foreach($funding_provisions AS $fp)
        {
            $html .= '<td class="text-bold" align="center">' . array_sum($master[$fp]) . '</td>';
        }

        $html .= '<td></td> </tr>';

        $html .= '</tbody>';
        $html .= '</table>';

        /*
                $options = new stdClass();
                $options->chart = (object)['type' => 'column'];
                $options->title = (object)['text' => 'Learners by Start Month'];
                $options->xAxis = (object)[
                    'categories' => ['16-18 Apprenticeship', '19+ Apprenticeship', '16-18 Levy Apprenticeship', '19+ Levy Apprenticeship', 'All Ages - Levy Apprenticeship', 'Study Programme', 'Traineeship', 'Learner Loans', 'Other']
                ];
                $options->yAxis = (object)[
                    'min' => 0,
                    'title' => (object)['text' => 'Learners'],
                    'stackLabels' => (object)[
                        'enabled' => true,
                        'style' => (object)[
                            'fontWeight' => 'bold',
                            'color' => "(Highcharts.theme && Highcharts.theme.textColor) || 'gray'"
                        ]
                    ]
                ];
                $options->legend = (object)[
                    'align' => 'right',
                    'x' => '-30',
                    'verticalAlign' => 'top',
                    'y' => 25,
                    'floating' => true,
                    'backgroundColor' => "lightgray",
                    'borderColor' => '#CCC',
                    'borderWidth' => 1,
                    'shadow' => false
                ];
                $options->tooltip = (object)[
                    'headerFormat' => '<b>{point.x}</b><br/>',
                    'pointFormat' => '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                ];
                $options->plotOptions = (object)[
                    'column' => (object)[
                        'stacking' => 'normal',
                        'dataLabels' => (object)[
                            'enabled' => true,
                            'color' => "(Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'"
                        ]
                    ]
                ];
        
                $options->series = [];
        
        
                foreach($months AS $m)
                {
                    $series_item = new stdClass();
                    $series_item->name = $m;
                    $temp = [];
                    foreach($funding_provisions AS $fp)
                    {
                        $temp[] = $master[$fp][$m];
                    }
                    $series_item->data = $temp;
                    $options->series[] = $series_item;
                }
        
        
                return json_encode($options, JSON_NUMERIC_CHECK);
        */
        return $html;
    }

    public function learners_by_region_and_ssa1(PDO $link, VoltView $view)
    {
        $sql = $view->getSQLStatement();
        $regions = DAO::getSingleColumn($link, "SELECT DISTINCT organisations.region FROM organisations WHERE organisations.`organisation_type` = 2 AND region != '' AND organisations.id IN (SELECT DISTINCT tr.`employer_id` FROM tr)");
        $data = [];
        $ssa1 = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        //pre($sql->__toString());
        foreach($result AS $row)
        {
            $row['SSA1'] = is_null($row['SSA1']) ? 'Blank' : $row['SSA1'];
            if(!isset($ssa1[$row['SSA1']]))
            {
                $ssa1[$row['SSA1']] = [];
            }
        }
        $temp = $ssa1;
        foreach($temp AS $key => $value)
        {
            $_sql = clone($sql);
            $_sql->setClause("WHERE SSA1 = '" . addslashes((string)$key) . "'");
            foreach($regions AS $region)
            {
                $__sql = clone($_sql);
                $__sql->setClause("WHERE employer_region = '" . addslashes((string)$region) . "'");
                $ssa1[$key][] = count(DAO::getSingleColumn($link, $__sql->__toString()));
            }
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column'];
        $options->title = (object)['text' => 'Learners by Region & SSA1'];
        $options->xAxis = (object)[
            'categories' => $regions,
        ];
        $options->yAxis = (object)[
            'min' => 0,
            'title' => (object)['text' => 'Learners'],
            'stackLabels' => (object)[
                'enabled' => true,
                'style' => (object)[
                    'fontWeight' => 'bold',
                    'color' => "(Highcharts.theme && Highcharts.theme.textColor) || 'gray'"
                ]
            ]
        ];

        $options->tooltip = (object)[
            'headerFormat' => '<b>{point.x}</b><br/>',
            'pointFormat' => '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        ];

        $options->plotOptions = (object)[
            'column' => (object)[
                'stacking' => 'normal',
                'dataLabels' => (object)[
                    'enabled' => true,
                    'color' => "(Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'",
                ]
            ]
        ];

        $options->series = [];
        foreach($ssa1 AS $key => $value)
        {
            $o = new stdClass();
            $o->name = $key;
            $o->data = $value;
            $options->series[] = $o;
        }

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function learners_by_SSA(PDO $link, VoltView $view, $ssa)
    {
        $sql = $view->getSQLStatement();

        $data = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            $row[$ssa] = is_null($row[$ssa]) ? 'Blank' : $row[$ssa];
            if(!isset($data[$row[$ssa]]))
            {
                $data[$row[$ssa]] = 0;
            }
            $data[$row[$ssa]] += 1;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column', 'options3d' => (object)['enabled' => true, 'alpha' => 15, 'beta' => 8, 'depth' => 50, 'viewDistance' => 25]];
        $options->title = (object)['text' => 'Learners by ' . strtoupper($ssa)];
        $options->xAxis = (object)[
            'type' => 'category',
            'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '12px', 'fontFamily' => 'Verdana, sans-serif']]
        ];
        $options->yAxis = (object)['min' => 0, 'title' => (object)['text' => 'Learners']];
        $options->legend = (object)['enabled' => false];
        $options->tooltip = (object)['pointFormat' => 'Learners: <b>{point.y}</b>'];

        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->data = [];
        foreach($data AS $key => $value)
        {
            $series->data[] = [$key, $value];
        }
        $series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '12px', 'fontFamily' => 'Verdana, sans-serif']];

        $options->series[] = $series;


        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function learners_by_fwk(PDO $link, VoltView $view)
    {
        $sql = $view->getSQLStatement();

        $data = [];
        $result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            $row['sfc'] = is_null($row['sfc']) ? 'Blank' : $row['sfc'];
            if(!isset($data[$row['sfc']]))
            {
                $data[$row['sfc']] = 0;
            }
            $data[$row['sfc']] += 1;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column', 'options3d' => (object)['enabled' => true, 'alpha' => 15, 'beta' => 8, 'depth' => 50, 'viewDistance' => 25]];
        $options->title = (object)['text' => 'Learners by Framework'];
        $options->xAxis = (object)[
            'type' => 'category',
            'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '12px', 'fontFamily' => 'Verdana, sans-serif']]
        ];
        $options->yAxis = (object)['min' => 0, 'title' => (object)['text' => 'Learners']];
        $options->legend = (object)['enabled' => false];
        $options->tooltip = (object)['pointFormat' => 'Learners: <b>{point.y}</b>'];

        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->data = [];
        foreach($data AS $key => $value)
        {
            $series->data[] = [$key, $value];
        }
        $series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '12px', 'fontFamily' => 'Verdana, sans-serif']];

        $options->series[] = $series;


        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    const MASTER_TABLE = 'charts_master';
}