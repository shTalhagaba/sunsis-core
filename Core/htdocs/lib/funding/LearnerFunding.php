<?php

class LearnerFunding
{
    private $periods = array();
    private $actualTotal = 0;
    private $learnerInfo = array();
    private $bonusTotal = 0;
    private $periodLookup = null;
    public $contractDuration = 0;
    public $actualDuration = 0;

    private $startTimestamp;
    private $endTimestamp;
    private $startPeriod;
    private $endPeriod;
    private $bonusPeriod;
    public float $total = 0.0;

    function __construct($link, $total, $learnerInfo, $periodLookup, $FundingLine)
    {

        $this->learnerInfo = $learnerInfo;
        $this->periodLookup = $periodLookup;
        // important to set this last as it uses the other variables defined before
        $this->setTotal($link, $total);
    }

    function setTotal($link, $total)
    {
        //pre($this->learnerInfo);
        // firstly calculate bonus
        $bonus = 0;
        // Dean		if(($this->learnerInfo['programme_type'] == 1 AND $this->learnerInfo['main_aim'] == 1) OR $this->learnerInfo['programme_type'] == 2)

        if ($this->learnerInfo['contract_year'] < 2013) {
            if ($this->learnerInfo['main_aim']) //Khushnood
            {
                $bonus = 0.25;
            }
        } else
            $bonus = 0.20;

        $this->actualTotal = $total;
        $this->total = ($total * (1 - $bonus));
        $this->bonusTotal = ($total * $bonus);


        //		pr('Total: ' . $this->actualTotal);
        //		pr('Real total: ' . $this->total);
        //		pr('Bonus: ' . $this->bonusTotal);

        // populate empty values
        //		$allPeriods = array_merge(explode(',', $this->learnerInfo['achiever_periods']), explode(',', $this->learnerInfo['target_periods']));
        //		$learningYears = array();
        //		foreach($allPeriods AS $period)
        //		{
        //			if(!empty($period))
        //			{
        //				$yearbits = explode('-', $period);
        //				if(!in_array($yearbits[0], $learningYears))
        //				{
        //					$learningYears[] = intval($yearbits[0]);
        //				}
        //			}
        //		}

        for ($i = 1; $i <= 24; $i++) {
            $this->add('on_program', $i, 0);
            $this->add('balance', $i, 0);
            $this->add('achievement', $i, 0);
            $this->add('adjusted', $i, 0);
            $this->add('achievement_predicted', $i, 0);
            $this->add('1618_prov_inc', $i, 0);
            $this->add('1618_emp_inc', $i, 0);
            $this->add('FM36_Disadv', $i, 0);
            $this->add('framework_uplift_opp', $i, 0);
            $this->add('framework_uplift_bal', $i, 0);
            $this->add('framework_uplift_comp', $i, 0);
            $this->add('als', $i, 0);
        }

        // take into account the student's ACTUAL start date now
        $c = 0;



        // figure out the periods on this contract the learner is learning in :)
        $this->calculatePeriods();

        // calculate OPP
        $opp = $this->total / ($this->actualDuration + 1); // Dean



        //		pr('Contract duration: ' . $this->contractDuration);
        //		pr('Actual duration over all contracts: ' . $this->actualDuration);
        //		pr('Distribution = ' . ($this->contractDuration / $this->actualDuration));
        //		pr('OPP = ' . $opp);
        //pr($this->learnerInfo);	
        //echo $opp;
        //pre($this);
        //echo 'Learner starts in period: ' . $this->startPeriod . ' and ends in ' . $this->endPeriod . '<br />';die;
        //		/pre($c);


        $cyear = $this->learnerInfo['contract_year'];

        $firstPeriod = $this->periodLookup->get($cyear)->getCensusStart(1);
        $lstart = strtotime($this->learnerInfo['learner_start_date']);


        // RTTG Workskills Tweak 
        //		if($this->learnerInfo['target_periods']=='')
        //		{
        //			$auto_id = $this->learnerInfo['auto_id'];
        //			$this->learnerInfo['target_periods'] = DAO::getSingleValue($link, "SELECT  GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM  student_qualifications AS sq LEFT JOIN central.lookup_submission_dates AS l  ON (l.census_end_date >= sq.start_date AND l.census_start_date <= sq.end_date AND l.submission <> 'W13') WHERE sq.auto_id = $auto_id;");
        //		}

        // Short programmes starting and finishing in the same month tweak
        if ($this->learnerInfo['onprogram_periods'] == '') {
            $this->learnerInfo['onprogram_periods'] = $this->learnerInfo['target_periods'];
        }


        $tperiods = explode(',', $this->learnerInfo['target_periods']);
        $startBits = explode('-', $tperiods[0]);
        $startYear = $startBits[0];

        $startPeriod = @intval(str_replace('W', '', $startBits[1]));

        //	pre($this->learnerInfo);
        //if(sizeof($this->learnerInfo['contract_periods']) > 0)  //dean
        if (
            !empty($this->learnerInfo['onprogram_periods']) ||
            !empty($this->learnerInfo['contract_periods'])
        ) {


            //			if(sizeof($this->learnerInfo['onprogram_periods']) > 0) // khushnood
            //				$cperiods = explode(',', $this->learnerInfo['onprogram_periods']); // Khushnood
            //			else //kushnood
            $cperiods = explode(',', $this->learnerInfo['contract_periods'] ?? '');
            $operiods = explode(',', $this->learnerInfo['onprogram_periods'] ?? '');

            // at this point we must figure out if the learner starts in this contract
            //			pre($this->learnerInfo);

            foreach ($operiods as $period) {
                if ($period != '') {
                    $bits = explode('-', $period);
                    $number = intval(str_replace('W', '', $bits[1]));
                    $year = $bits[0];

                    if ($year == $this->learnerInfo['contract_year']) {
                        $actual = $opp;
                        if (($startYear == $year and $number == $startPeriod) || (sizeof($operiods) == 1)) {
                            //pr('Giving a double OPP for period ' . $number . ' in year ' . $year . ' when they start in ' . $startYear);
                            $actual = $opp * 2;
                        }

                        $this->add('on_program', $number, $actual);
                        $this->add('adjusted', $number, $actual);
                    }
                }
            }
        }

        if ($this->learnerInfo['achieved'] || $this->learnerInfo['aim_achieved']) {
            if ($this->learnerInfo['achieved']) {
                $aps = $this->learnerInfo['achiever_periods'];
                $aps_periods = explode(",", $aps);
                $ach_period = explode("-", $aps_periods[sizeof($aps_periods) - 1]);
                $this->endPeriod = @(intval(str_replace("W", "", $ach_period[1])));
                if ($this->learnerInfo['contract_year'] == $ach_period[0]) {
                    $this->add('achievement', $this->endPeriod, $this->bonusTotal);
                    $this->add('achievement_predicted', $this->endPeriod, $this->bonusTotal);
                }
                $tps = explode(',', $this->learnerInfo['target_periods']);
                $ops = explode(',', $this->learnerInfo['onprogram_periods']);
                $ops_period = explode("-", $ops[sizeof($ops) - 1]);
            }
            if ($this->learnerInfo['aim_achieved']) {
                $tps = explode(',', $this->learnerInfo['target_periods']);
                $ops = explode(',', $this->learnerInfo['onprogram_periods']);
                $ops_period = explode("-", $ops[sizeof($ops) - 1]);
                $aps = $this->learnerInfo['aim_achievers'];
                $aps_periods = explode(",", $aps);
                $ach_period = explode("-", $aps_periods[sizeof($aps_periods) - 1]);
                $this->endPeriod = @(intval(str_replace("W", "", $ach_period[1])));
                if ($this->learnerInfo['contract_year'] == $ach_period[0])
                    $this->add('balance', $this->endPeriod, ((sizeof($tps) - sizeof($ops)) * $opp)); // add 1 to consider the n+1 opp calculation
            }
            for ($i = 1; $i <= 24; $i++) {
                $this->reset($i, 'adjusted');
            }
        } elseif ($this->learnerInfo['continuing']) {
            // Khushnood projected achievement payment calculation for non achievers
            $tps = explode(',', $this->learnerInfo['achiever_periods']);
            $targetPeriods = sizeof($tps);
            $endPeriod = $tps[$targetPeriods - 1];
            $pbits = explode('-', $endPeriod);
            $actualPeriod = @intval(substr($pbits[1], 1));
            $year = $pbits[0];
            if ($this->learnerInfo['contract_year'] == $year) {
                if ($this->learnerInfo['framework_achivement_date'] != '')
                    $this->add('achievement', $actualPeriod, $this->bonusTotal);
                $this->add('achievement_predicted', $actualPeriod, $this->bonusTotal);
            }
            for ($i = 1; $i <= 24; $i++) {
                $this->reset($i, 'adjusted');
            }
        } else // withdrawn
        {

            if ($this->learnerInfo['qualify'] == 0) {
                for ($i = 1; $i <= 24; $i++) {
                    $this->reset($i, 'on_program');
                }
            } else {
                $early = 0;
                $tps = explode(',', $this->learnerInfo['target_periods']);
                $aps = explode(',', $this->learnerInfo['achiever_periods']);

                $targetPeriods = sizeof($tps);
                $achieverPeriods = sizeof($aps);

                $this->endPeriod = intval(preg_replace('/^\d+-W/i', '', $aps[$achieverPeriods - 1]));

                $endPeriod = $aps[$achieverPeriods - 1];
                $pbits = explode('-', $endPeriod);
                $actualPeriod = @intval(substr($pbits[1], 1));
                $year = $pbits[0];
                if ($achieverPeriods <= $targetPeriods && $this->learnerInfo['contract_year'] >= $year) {
                    foreach ($tps as $period) {
                        $pbits = explode('-', $period);
                        $actualPeriod = @intval(substr($pbits[1], 1));

                        if (!in_array($period, $aps)) {

                            if ($pbits[0] == $this->learnerInfo['contract_year'] or $actualPeriod == $this->endPeriod) // only reset periods of this contract year
                            {
                                //pr('1Resetting period ' . $actualPeriod . ' (' . $period . ') back to 0 so we can backdate the payments');
                                $this->reset($actualPeriod, 'on_program');
                            }
                            $early++;
                        } else if ($actualPeriod == $this->endPeriod && $achieverPeriods > 1) {
                            //pr('2Resetting period ' . $actualPeriod . ' (' . $period . ') back to 0 so we can backdate the payments');
                            $this->reset($actualPeriod, 'on_program');
                            $early++;
                        } else {
                            $this->reset($actualPeriod, 'adjusted');
                        }
                    }
                } else if ($achieverPeriods > $targetPeriods) {
                    $this->endPeriod = intval(preg_replace('/^\d+-W/i', '', $aps[$achieverPeriods - 1]));
                }


                // Clawed Back
                if ($this->learnerInfo['marked_periods'] == '') {
                    for ($i = 1; $i <= 24; $i++) {
                        $this->reset($i, 'adjusted');
                    }
                } else {
                    $early = 0;
                    $tps = explode(',', $this->learnerInfo['target_periods']);
                    $mps = explode(',', $this->learnerInfo['marked_periods']);
                    $aps = explode(',', $this->learnerInfo['achiever_periods']);
                    //				$mps = array_diff($mps,$aps);
                    //				$mps = array_merge($mps);

                    $targetPeriods = sizeof($tps);
                    $markedPeriods = sizeof($mps);
                    $achieverPeriods = sizeof($aps);

                    $this->endPeriod = intval(preg_replace('/^\d+-W/i', '', $mps[$markedPeriods - 1]));

                    $endPeriod = $mps[$markedPeriods - 1];
                    $pbits = explode('-', $endPeriod);
                    $actualPeriod = @intval(substr($pbits[1], 1));
                    $year = $pbits[0];
                    if ($markedPeriods <= $targetPeriods && $this->learnerInfo['contract_year'] >= $year) {
                        foreach ($tps as $period) {
                            $pbits = explode('-', $period);
                            $actualPeriod = @intval(substr($pbits[1], 1));

                            if (!in_array($period, $mps)) {
                                if ($pbits[0] == $this->learnerInfo['contract_year'] or $actualPeriod == $this->endPeriod) // only reset periods of this contract year
                                {
                                    //pr('1Resetting period ' . $actualPeriod . ' (' . $period . ') back to 0 so we can backdate the payments');
                                    $this->reset($actualPeriod, 'adjusted');
                                }
                                $early++;
                            } else if ($actualPeriod == $this->endPeriod) # AND $achieverPeriods > 1)
                            {
                                //pr('2Resetting period ' . $actualPeriod . ' (' . $period . ') back to 0 so we can backdate the payments');
                                $this->reset($actualPeriod, 'adjusted');
                                $early++;
                            }
                        }
                    } else if ($markedPeriods > $targetPeriods) {
                        $this->endPeriod = intval(preg_replace('/^\d+-W/i', '', $mps[$markedPeriods - 1]));
                    }
                }
            }
        }



        // give any bonus (assuming the learner has achieved, or has not yet passed their target date) =)
        //		$target = mktime(0, 0, 0, intval(substr($this->learnerInfo['learner_target_end_date'], 5, 2)), intval(substr($this->learnerInfo['learner_target_end_date'], 8, 2)), intval(substr($this->learnerInfo['learner_target_end_date'], 0, 4)));
        //		$actualPeriod = @intval(substr($pbits[1],1));

        /*
              if(SOURCE_BLYTHE_VALLEY)
                  if($this->learnerInfo['L03']=='117901207595' && $this->learnerInfo['qualid']=='60076379')
                      pre($this);
*/
        //  	            if($this->learnerInfo['L03']=='108001767259')
        //  				    pre($this);


        //		if($this->learnerInfo['achieved']==0)
        //		{
        //			if($this->learnerInfo['L03']=='93724')
        //				pre($this);
        //		}
        //pr($this);

        if ($this->learnerInfo['L03'] == "000000005507" && $this->learnerInfo['qualid'] == '50079244') {

            //            pre($this);
            //$actualPeriod = @intval(substr($pbits[1],1));

            //	pre($target);
            //pre($achieverPeriods);
            //pre($targetPeriods);
            //pre($actualPeriod);
            //pre($this);
        }
    }

    private function calculatePeriods()
    {
        // need to define four variables
        /*
           1) $this->startPeriod - the number of the period within the current contract that the learner starts. If they start
           in a previous contract, always default this value to 1
           2) $this->endPeriod - the number of the period within the current contract where the learner ends. if they end in
           a different contract, default the value to 12
           3) $this->contractDuration - the duration within the current contract that the learner is active (endPeriod - startPeriod)
           4) $this->actualDuration - the actual number of periods the learner will be active, across all contracts
           */

        // get the learners periods
        $periods = explode(',', $this->learnerInfo['target_periods']);
        $aperiods = explode(',', $this->learnerInfo['achiever_periods']);
        if (empty($this->learnerInfo['contract_periods'])) {
            $cperiods = array();
        } else {
            $cperiods = explode(',', $this->learnerInfo['contract_periods']);
        }

        // bonus period is *always* last achieving month :)
        if (sizeof($aperiods) > 0 and !empty($aperiods[0])) {
            $this->bonusPeriod =  intval(preg_replace('/^\d+-W/i', '', $aperiods[sizeof($aperiods) - 1]));
        } else // fall back to last target period
        {
            $this->bonusPeriod = intval(preg_replace('/^\d+-W/i', '', $periods[sizeof($periods) - 1]));
        }

        // 1) Start period
        $this->startPeriod = 1;
        //		foreach($periods AS $key => $periodName)
        //		{
        //			if(strpos($periodName, $this->learnerInfo['contract_year']) !== false)
        //			{
        //				$this->startPeriod = intval(preg_replace('/^[\d]+-W/i', '', $periodName));
        //				break;
        //			}
        //		}

        // 2) end period
        $this->endPeriod = 24;
        foreach ($periods as $key => $periodName) {
            if (strpos($periodName, $this->learnerInfo['contract_year']) !== false) {
                // keep over-riding it (maybe consider flipping the array and then doing the same for finding start period)
                $this->endPeriod = intval(preg_replace('/^[\d]+-W/i', '', $periodName));
            }
        }

        $this->contractDuration = sizeof($cperiods);
        $this->actualDuration = sizeof($periods);

        //pre($periods);

    }

    function add($type, $period, $periodTotal)
    {
        if (!isset($this->periods["$period"]["$type"])) {
            $this->periods["$period"]["$type"] = $periodTotal;
        } else {
            $this->periods["$period"]["$type"] += $periodTotal;
        }
    }

    function set($type, $period, $periodTotal)
    {
        $this->periods["$period"]["$type"] = $periodTotal;
    }


    function remove($type, $period, $periodTotal)
    {
        $this->periods["$period"]["$type"] -= $periodTotal;
    }

    function reset($period, $type)
    {
        $this->periods["$period"]["$type"] = 0;
    }

    function get($period, $type)
    {
        return $this->periods["$period"]["$type"];
    }

    function getPeriodTotal($period)
    {
        $total = 0;
        foreach ($this->periods["$period"] as $type => $t) {
            $total += $t;
        }
        return $total;
    }

    static function addLearners($period, $learner1, $learner2)
    {
        if ($learner1 != null and $learner2 != null) {
            $values1 = $learner1->getClean($period);
            $values2 = $learner2->getClean($period);
            return array(
                'submission_period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT),
                'on_program' => ($values1['on_program'] + $values2['on_program']),
                'balance' => ($values1['balance'] + $values2['balance']),
                'achievment' => ($values1['achievement'] + $values2['achievement']),
                'total' => ($values1['total'] + $values2['total'])
            );
        } else if ($learner1 == null) {
            return $learner2->getClean($period);
        } else if ($learner2 == null) {
            return $learner1->getClean($period);
        }
    }

    function getClean($period)
    {
        return array(
            'submission_period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT),
            'on_program' => $this->get($period, 'on_program'),
            'balance' => $this->get($period, 'balance'),
            'achievement' => $this->get($period, 'achievement'),
            'total' => $this->getPeriodTotal($period)
        );
    }

    function getBonus()
    {
        return (0.25 * $this->total);
    }
}