<?php

function format_money($string)
{

    // no period, add one please
    if (strpos($string, '.') === false) {
        $string .= '.';
    }
    $bits = explode('.', $string);
    $ret = $bits[0] . '.' . str_pad($bits[1], 2, '0', STR_PAD_RIGHT);

    $ret = sprintf("%.2f", $ret);
    if (strpos($ret, '.') === false) {
        $ret .= '.00';
    }


    $bits2 = explode('.', $ret);
    return number_format($bits2[0]) . '.' . str_pad($bits2[1], 2, '0', STR_PAD_RIGHT);
}

abstract class FundingCore
{
    private $db = null;
    protected $totalFunding = array();
    protected $ttotal = 0;
    protected $pl = null;
    protected $contractInfo;

    // output date
    protected $data = array();
    protected $dataShadow = array();
    protected $dataClassroom = array();
    protected $dataTraineeship1924NP = array();

    protected array $dataTraineeship1924PMay17 =  array();
    protected array $dataAEBOtherLearningNP = array();
    protected array $dataAEBOtherLearningPNov17 = array();
    protected array $data1618Apps = array();
    protected array $data1923Apps = array();
    protected array $data24Apps = array();
    protected array $dataApps1618LevyMay17 = array();
    protected array $dataApps1618NLNPMay17 = array();
    protected array $dataApps1618NLPMay17 = array();
    protected array $dataApps19LevyMay17 = array();
    protected array $dataApps19NLNPMay17 = array();
    protected array $dataApps19NLPMay17 = array();


    // periods
    protected $years = array();

    // constants
    const LAD_DB = 'lad200910';
    const T2GSLN = '2775';
    const ASLN = '2860';

    function __construct($link, $contractInfo)
    {
        $this->db = $link;
        $this->contractInfo = $contractInfo;
    }

    private function delete_col(&$array, $offset)
    {
        return array_walk($array, function (&$v) use ($offset) {
            array_splice($v, $offset, 1);
        });
    }

    public function to($type)
    {
        if (!SOURCE_LOCAL && DB_NAME != "am_ligauk")
            $this->delete_col($this->data, 3);
        if (isset($this->data[0]))
            $matrix = new DataMatrix(array_keys($this->data[0]), $this->data, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', '1618_prov_inc', '1618_emp_inc', 'FM36_Disadv', 'framework_uplift_opp', 'framework_uplift_bal', 'framework_uplift_comp', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('1618_prov_inc', 'format_money2', 1, 1);
            $matrix->transform('1618_emp_inc', 'format_money2', 1, 1);
            $matrix->transform('FM36_Disadv', 'format_money2', 1, 1);
            $matrix->transform('framework_uplift_opp', 'format_money2', 1, 1);
            $matrix->transform('framework_uplift_bal', 'format_money2', 1, 1);
            $matrix->transform('framework_uplift_comp', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function toShadow($type)
    {
        if (isset($this->dataShadow[0]))
            $matrix = new DataMatrix(array_keys($this->dataShadow[0]), $this->dataShadow, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function to1924TraineeshipNP($type)
    {
        if (isset($this->dataTraineeship1924NP[0]))
            $matrix = new DataMatrix(array_keys($this->dataTraineeship1924NP[0]), $this->dataTraineeship1924NP, false);
        else
            throw new Exception("No Data");

        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function to1924TraineeshipPNov17($type)
    {
        if (isset($this->dataTraineeship1924PMay17[0]))
            $matrix = new DataMatrix(array_keys($this->dataTraineeship1924PMay17[0]), $this->dataTraineeship1924PMay17, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function toAEBOtherNP($type)
    {
        if (isset($this->dataAEBOtherLearningNP[0]))
            $matrix = new DataMatrix(array_keys($this->dataAEBOtherLearningNP[0]), $this->dataAEBOtherLearningNP, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function toAEBOtherPNov17($type)
    {
        if (isset($this->dataAEBOtherLearningPNov17[0]))
            $matrix = new DataMatrix(array_keys($this->dataAEBOtherLearningPNov17[0]), $this->dataAEBOtherLearningPNov17, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function to1618Apps($type)
    {
        if (isset($this->data1618Apps[0]))
            $matrix = new DataMatrix(array_keys($this->data1618Apps[0]), $this->data1618Apps, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function to1923Apps($type)
    {
        if (isset($this->data1618Apps[0]))
            $matrix = new DataMatrix(array_keys($this->data1923Apps[0]), $this->data1923Apps, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function to24Apps($type)
    {
        if (isset($this->data24Apps[0]))
            $matrix = new DataMatrix(array_keys($this->data24Apps[0]), $this->data24Apps, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function to1618AppsLevyMay17($type)
    {
        if (isset($this->dataApps1618LevyMay17[0]))
            $matrix = new DataMatrix(array_keys($this->dataApps1618LevyMay17[0]), $this->dataApps1618LevyMay17, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function to1618AppsNLNPMay17($type)
    {
        if (isset($this->dataApps1618NLNPMay17[0]))
            $matrix = new DataMatrix(array_keys($this->dataApps1618NLNPMay17[0]), $this->dataApps1618NLNPMay17, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function to1618AppsNLPMay17($type)
    {
        if (isset($this->dataApps1618NLPMay17[0]))
            $matrix = new DataMatrix(array_keys($this->dataApps1618NLPMay17[0]), $this->dataApps1618NLPMay17, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function to19AppsLevyMay17($type)
    {
        if (isset($this->dataApps19LevyMay17[0]))
            $matrix = new DataMatrix(array_keys($this->dataApps19LevyMay17[0]), $this->dataApps19LevyMay17, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function to19AppsNLNPMay17($type)
    {
        if (isset($this->dataApps19NLNPMay17[0]))
            $matrix = new DataMatrix(array_keys($this->dataApps19NLNPMay17[0]), $this->dataApps19NLNPMay17, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    public function to19AppsNLPMay17($type)
    {
        if (isset($this->dataApps19NLPMay17[0]))
            $matrix = new DataMatrix(array_keys($this->dataApps19NLPMay17[0]), $this->dataApps19NLPMay17, false);
        else
            throw new Exception("No Data");
        $matrix->addTotalColumns(array('adjusted', 'on_program', 'balance', 'achievement', 'ach_profiled', 'ALS', 'profile', 'PFR', 'total', 'at_risk'));
        if ($type == 'HTML') {
            $matrix->transform('adjusted', 'format_money2', 1, 1);
            $matrix->transform('on_program', 'format_money2', 1, 1);
            $matrix->transform('balance', 'format_money2', 1, 1);
            $matrix->transform('achievement', 'format_money2', 1, 1);
            $matrix->transform('ach_profiled', 'format_money2', 1, 1);
            $matrix->transform('ALS', 'format_money2', 1, 1);
            $matrix->transform('profile', 'format_money2', 1, 1);
            $matrix->transform('PFR', 'format_money2', 1, 1);
            $matrix->transform('total', 'format_money2', 1, 1);
            $matrix->transform('at_risk', 'format_money2', 1, 1);
        }
        return $matrix->to($type);
    }

    protected function getYears($link)
    {
        $this->years[] = DAO::getSingleValue($link, "select distinct contract_year from contracts where id in ( " . $this->contractInfo . ")");
        return array_unique($this->years);
    }

    protected function getPeriods($link)
    {
        $this->pl = new PeriodLookup($this->db);
        $years = $this->getYears($link);
        foreach ($years as $key => $year) {
            $this->pl->add($year);
        }
    }

    public static function getWeighting($string)
    {
        preg_match('/[\d.]+/i', $string, $matches);
        if (sizeof($matches) > 0) {
            return $matches[0];
        }
        return 1;
    }

    protected function calculate_funding($data)
    {
        return  $data['sln'] * $data['funding_remaining_weight'] * $data['proportion'] / 100;
    }

    function chart_data($values, $labels, $link, $profiled = false)
    {

        // First, find the maximum value from the values given
        $maxValue = max($values);

        $maxValue = ($maxValue == 0) ? 1 : $maxValue;

        // A list of encoding characters to help later, as per Google's example
        $simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        $chartData = 's1:';
        for ($i = 0; $i < count($values); $i++) {
            $currentValue = $values[$i];

            if ($currentValue > -1) {
                $chartData .= substr($simpleEncoding, 61 * ($currentValue / $maxValue), 1);
            } else {
                $chartData .= '_';
            }
        }
        // 2) Calculate the y axis labels (we want 3 intervals)
        $interval = sprintf("%.2f", ($maxValue / 4));
        $ylabels = 0 . '|' . $interval . '|' . ($interval * 2) . '|' . ($interval * 3) . '|' . $maxValue;

        //$ylabels = sprintf("%.2f",$ylabels);

        if ($profiled) {
            $chartData2 = ',';
            $chartData3 = ',';

            $contract_id = $this->contracts;

            // fetch profile values from tables
            $st = $link->query("select sum(profile) as profile from lookup_profile_values where contract_id in('$contract_id') group by submission order by submission");
            $cc = -1;
            $profile = array();
            while ($row = $st->fetch()) {
                $cc++;
                $profile[$cc] = sprintf("%.2f", ($row['profile'] / 1000));
            }

            $maxValue = max($values);

            $temp = array();
            for ($i = 0; $i < count($profile); $i++) {
                $currentValue = $profile[$i];

                if ($currentValue > -1) {
                    $chartData2 .= substr($simpleEncoding, 61 * ($currentValue / $maxValue), 1);
                } else {
                    $chartData2 .= '_';
                }
            }

            // fetch pfr values from tables
            $st = $link->query("select sum(profile) as profile from lookup_pfr_values where contract_id in('$contract_id') group by submission order by submission");
            $cc = -1;
            $profile = array();
            while ($row = $st->fetch()) {
                $cc++;
                $profile[$cc] = sprintf("%.2f", ($row['profile'] / 1000));
            }

            $maxValue = max($values);

            for ($i = 0; $i < count($profile); $i++) {
                $currentValue = $profile[$i];

                if ($currentValue > -1) {
                    $chartData3 .= substr($simpleEncoding, 61 * ($currentValue / $maxValue), 1);
                } else {
                    $chartData3 .= '_';
                }
            }

            return  $chartData . $chartData2 . $chartData3 . '&amp;chm=D,0033FF,1,0,3,1|D,00FF33,2,0,3,1' . '&amp;chdl=%C2%A3+k|PFR|Profile&amp;chco=FFCC33,00ff00,0000ff&amp;chxt=y,x&amp;chxl=0:|' . $ylabels . '|1:|' . implode('|', $labels);
        } else {
            return  $chartData . '&amp;chm=D,0033FF,1,0,3,1' . '&amp;chdl=%C2%A3+k&amp;chxt=y,x&amp;chxl=0:|' . $ylabels . '|1:|' . implode('|', $labels);
        }


        // Return the chart data - and let the Y axis to show the maximum value
        //	throw new Exception($chartData . $chartData2 . '&chm=D,0033FF,1,0,5,1;' . '&amp;chdl=%C2%A3+k&amp;chxt=y,x&amp;chxl=0:|' . $ylabels . '|1:|' . implode('|', $labels));



    }
}