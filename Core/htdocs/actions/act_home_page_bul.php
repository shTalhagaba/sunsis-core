<?php
class home_page_bul implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=home_page", "Home Page");

        $_SESSION['current_submission_year'] = (!isset($_SESSION['current_submission_year']) || $_SESSION['current_submission_year'] == '' ) ?
            DAO::getSingleValue($link, "SELECT contract_year FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date LIMIT 1;") :
            $_SESSION['current_submission_year'];

        $_SESSION['current_submission_year_disp'] = (!isset($_SESSION['current_submission_year_disp']) || $_SESSION['current_submission_year_disp'] == '' ) ?
            DAO::getSingleValue($link, "SELECT CONCAT(YEAR(start_submission_date), '-', RIGHT(YEAR(last_submission_date), 2)) AS current_submission_year FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date LIMIT 1;") :
            $_SESSION['current_submission_year_disp'];

        $_SESSION['current_submission'] = (!isset($_SESSION['current_submission']) || $_SESSION['current_submission'] == '' ) ?
            DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date LIMIT 1;") :
            $_SESSION['current_submission'];

        $current_submission_year = $_SESSION['current_submission_year'];
        $current_submission_year_disp = $_SESSION['current_submission_year_disp'];
        $current_submission = $_SESSION['current_submission'];

        $valid_ilrs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ilr INNER JOIN contracts ON ilr.contract_id = contracts.id WHERE contract_year = '{$current_submission_year}' AND is_valid = 1 AND submission = 'W{$current_submission}';");
        $invalid_ilrs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ilr INNER JOIN contracts ON ilr.contract_id = contracts.id WHERE contract_year = '{$current_submission_year}' AND is_valid = 0 AND submission = 'W{$current_submission}';");
        $total_ilrs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ilr INNER JOIN contracts ON ilr.contract_id = contracts.id WHERE contract_year = '{$current_submission_year}' AND submission = 'W{$current_submission}';");

        if($_SESSION['user']->isAdmin())
        {
            $file_repo_graph = HomePageV2::getFileRepositoryGraph($link);
            $panelFileRepoUsage = isset($_SESSION['panelFileRepoUsage']) ?
                $_SESSION['panelFileRepoUsage'] :
                $_SESSION['panelFileRepoUsage'] = $this->repo_usage($link);

            $learners_by_assessors = HomePageV2::getLearnersByAssessorsGraph($link);

        }
        //$learners_in_training = HomePageV2::getLearnersInTraining($link);
        //$learners_peed = HomePageV2::getLearnersPastPlannedEndDate($link);
        $learners_temp_withdrawn = HomePageV2::getLearnersTemporarilyWithdrawn($link);
        //$learners_withdrawn = HomePageV2::getLearnersWithdrawn($link);

        $learners_completed = HomePageV2::getLearnersCompleted($link);
        //$l2_to_l3_progression = HomePageV2::getL2L3Progressions($link);
        //$l3_to_l4_progression = HomePageV2::getL3L4Progressions($link);
        //$traineeship_to_app = HomePageV2::getTtoAProgressions($link);

        $learners_by_progress = HomePageV2::getLearnersByProgressGraph($link);
        $learners_by_otj_progress = HomePageV2::getLearnersByOtjProgressGraph($link);

        $gateway_stats = HomePageV2::gatewayLearnersStats($link);
        //$peed_in_2_months = HomePageV2::learnersDueToFinishIn2Months($link);

        $start_stats_previous_3_months = HomePageV2::getStartsGraphs($link);
        $on_programme_stats = HomePageV2::getOnProgrammeStats($link);
        $overstayers_by_expected_month = HomePageV2::getOverstayersByExpectedMonthGraph($link);
        $withdrawals_in_current_submission_year = HomePageV2::getWithdrawalsGraph($link);
        $completions_due_by_expected_month = HomePageV2::getUpcomingCompletionsGraph($link);


        $toastr_message = '';
        if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '_action=login'))
        {
            $toastr_message = 'Welcome back, <b>' . $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname . '</b>';
            $toastr_message .= '<br>Your last login: ' . DAO::getSingleValue($link, "SELECT DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') FROM logins WHERE username = '" . $_SESSION['user']->username . "' ORDER BY id DESC LIMIT 1,1");
        }



        require_once('tpl_home_page_bul.php');
    }

    public function fn($repository)
    {
        $files = Repository::readDirectory($repository);

        foreach($files AS $f)
        {
            if(($f->isDir()))
            {
                $this->fn($f->getAbsolutePath());
            }
            else
            {
                $data = new stdClass();
                $data->day = date('Y-m', $f->getModifiedTime());
                $data->name = $f->getName();
                $data->size = $f->getSize();
                $this->main[] = $data;
            }
        }
    }
    public function repo_usage(PDO $link)
    {
        $months = [
            '1' => 'January'
            ,'2' => 'February'
            ,'3' => 'March'
            ,'4' => 'April'
            ,'5' => 'May'
            ,'6' => 'June'
            ,'7' => 'July'
            ,'8' => 'August'
            ,'9' => 'September'
            ,'10' => 'October'
            ,'11' => 'November'
            ,'12' => 'December'
        ];

        $this->fn(Repository::getRoot());

        $main = [];

        foreach($this->main AS $data)
        {
            if(!isset($main[$data->day]))
            {
                $main[$data->day] = 0;
            }
            $main[$data->day] += $data->size;
        }


        ksort($main);

        $yearInfo = [];
        foreach($main AS $key => $value)
        {
            $year = substr($key, 0, 4);
            if(!isset($yearInfo[$year]))
            {
                $yearInfo[$year] = 0;
            }
            $yearInfo[$year] += $value;
        }

        $options = new stdClass();
        $options->chart = (object)['type' => 'column'];
        $options->title = (object)['text' => 'File Repository Usage'];

        $options->xAxis = (object)['type' => 'category'];
        $options->yAxis = (object)['title' => (object)['text' => 'File Space (Bytes)']];
        $options->legend = (object)['enabled' => false];
        $options->plotOptions = (object)['series' => (object)[
            'borderWidth' => 0,
            'dataLabels' => (object)[
                'enabled' => true
                , 'formatter' => 'function(){ var i = Math.floor( Math.log(this.y) / Math.log(1024) );return ( this.y / Math.pow(1024, i) ).toFixed(2) * 1 + \' \' + [\'B\', \'KB\', \'MB\', \'GB\', \'TB\'][i];}'
            ]
        ]
        ];
        $options->tooltip = (object)['formatter' => 'function(){ var i = Math.floor( Math.log(this.point.y) / Math.log(1024) );return ( this.point.y / Math.pow(1024, i) ).toFixed(2) * 1 + \' \' + [\'B\', \'KB\', \'MB\', \'GB\', \'TB\'][i];}'];

        $series = new stdClass();
        $series->name = 'File Space';
        $series->colorByPoint = true;
        $series->data = [];
        foreach($yearInfo AS $key => $value)
        {
            $obj = new stdClass();
            $obj->name = $key;
            $obj->y = $value;
            $obj->drilldown = $key;
            $series->data[] = $obj;
        }
        $options->series[] = $series;

        $drilldown_series = [];
        foreach($main AS $key => $value)
        {
            $key_parts = explode('-', $key);
            $y = $key_parts[0];
            $m = $key_parts[1];
            $obj = new stdClass();
            $obj->name = $y;
            $obj->id = $y;

        }
        foreach($yearInfo AS $key => $value)
        {
            $obj = new stdClass();
            $obj->name = $key;
            $obj->id = $key;
            $obj->data = [];
            for($i = 1; $i <= 12; $i++)
            {
                $ii = str_pad($i, 2, "0", STR_PAD_LEFT);
                if(isset($main[$key.'-'.$ii]))
                {
                    $m = $months[$i];// . $key;
                    $obj->data[] = [$m, $main[$key.'-'.$ii]];
                }
            }
            $drilldown_series[] = $obj;
        }
        $options->drilldown = (object)['series' => $drilldown_series];

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public $main = [];
}
?>