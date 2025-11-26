<?php

class FundingPrediction extends FundingCore
{
    private $learner = 0;
    public $contracts = null;

    public array $totalFundingTraineeship1924NP = [];
    public array $totalFundingTraineeship1924PMay17 = [];
    public array $totalFundingAEBOtherLearningNP = [];
    public array $totalFundingAEBOtherLearningPNov17 = [];
    public array $totalFunding1618Apps = [];
    public array $totalFunding1923Apps = [];
    public array $totalFundingApps19NLPMay17 = [];
    public array $totalFunding24Apps = [];
    public array $totalFundingApps1618LevyMay17 = [];
    public array $totalFundingApps1618NLNPMay17 = [];
    public array $totalFundingApps1618NLPMay17 = [];
    public array $totalFundingApps19LevyMay17 = [];
    public array $totalFundingApps19NLNPMay17 = [];
    private array $dataTraineeship1924PMay17 = [];
    private array $dataAEBOtherLearningNP = [];
    private array $dataAEBOtherLearningPNov17 = [];
    private array $data1618Apps = [];
    private array $data1923Apps = [];
    private array $data24Apps = [];
    private array $dataApps1618LevyMay17 = [];
    private array $dataApps1618NLNPMay17 = [];
    private array $dataApps1618NLPMay17 = [];
    private array $dataApps19LevyMay17 = [];
    private array $dataApps19NLNPMay17 = [];
    private array $dataApps19NLPMay17 = [];

    function __construct($link, $contracts, $sqid = 0, $course = 0, $assessor = '', $employer = 0, $submissionp = '', $shadow = false, $tutor = '', $emp_b_code = '')
    {
        ini_set('memory_limit', '1024M');
        $this->contracts = $contracts;

        // find out the submission periods for the year(s) we're looking at
        parent::__construct($link, $contracts);

        $contract_year = DAO::getSingleValue($link, "select distinct contract_year from contracts where id in ($contracts)");
        $class = 'FundingCalculator_' . $contract_year;
        require_once('years/' . $class . '.php');
        $gfunding = new $class($link, $contracts);
        $addition = (!empty($sqid) ? "AND sq.auto_id = '" . intval($sqid) . "'" : "");
        $addition .= (!empty($course) ? " AND courses.id = '" . intval($course) . "'" : "");
        $addition .= (!empty($assessor) ? " AND assessors.id = '" . $assessor . "'" : "");
        $addition .= (!empty($tutor) ? " AND tutors.id = '" . $tutor . "'" : "");
        $addition .= (!empty($employer) ? " AND employers.id = '" . $employer . "'" : "");
        $addition .= " and submission = '" . $submissionp . "'";
        if ($emp_b_code != '' && (DB_NAME == "am_siemens" || DB_NAME == "am_siemens_demo")) {
            $addition .= (!empty($emp_b_code) ? " AND learners.employer_business_code= '" . $emp_b_code . "'" : "");
        }
        $addition .= " and submission = '" . $submissionp . "'";
        $funding = $gfunding->getData($link, null, null, $addition);
        $otherData = $gfunding->getOtherData($link, $funding);

        $achievement_period = "";
        $aim_achievement_period = '';
        $achievement_profiled_period = '';

        // find out the submission periods for the year(s) we're looking at
        $this->getPeriods($link);

        $edrsstring = $otherData['edrsarray'];
        $wgts = $otherData['wgts'];
        $pws = $otherData['pws'];
        $ksarray = $otherData['ksarray'];
        $postcodes = $otherData['postcodes'];
        $home_postcodes = $otherData['home_postcodes'];
        $large_employer = $otherData['large_employer'];

        if (DB_NAME == 'am_crackerjack' || DB_NAME == 'am_siemens')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;

        foreach ($funding as $key => $data) {

            // distribute the funding across the months dependent on all sorts of things
            $learner = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "All");

            if ($data['new_aim_type'] == "19-24 Traineeship (non-procured)")
                $Traineeship1924NP_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "19-24 Traineeship (non-procured)");
            else
                $Traineeship1924NP_funding = new LearnerFunding($link, 0, $data, $this->pl, "19-24 Traineeship (non-procured)");

            if ($data['new_aim_type'] == "19-24 Traineeship (procured from Nov 2017)")
                $Traineeship1924PMay17_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "19-24 Traineeship (procured from Nov 2017)");
            else
                $Traineeship1924PMay17_funding = new LearnerFunding($link, 0, $data, $this->pl, "19-24 Traineeship (procured from Nov 2017)");

            if ($data['new_aim_type'] == "AEB - Other Learning (non-procured)")
                $AEBOtherLearningNP_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "AEB - Other Learning (non-procured)");
            else
                $AEBOtherLearningNP_funding = new LearnerFunding($link, 0, $data, $this->pl, "AEB - Other Learning (non-procured)");

            if ($data['new_aim_type'] == "AEB - Other Learning (procured from Nov 2017)")
                $AEBOtherLearningPNov17_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "AEB - Other Learning (procured from Nov 2017)");
            else
                $AEBOtherLearningPNov17_funding = new LearnerFunding($link, 0, $data, $this->pl, "AEB - Other Learning (procured from Nov 2017)");

            if ($data['new_aim_type'] == "16-18 Apprenticeship")
                $apps1618_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "16-18 Apprenticeship");
            else
                $apps1618_funding = new LearnerFunding($link, 0, $data, $this->pl, "16-18 Apprenticeship");

            if ($data['new_aim_type'] == "19-23 Apprenticeship")
                $apps1923_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "19-23 Apprenticeship");
            else
                $apps1923_funding = new LearnerFunding($link, 0, $data, $this->pl, "19-23 Apprenticeship");

            if ($data['new_aim_type'] == "24+ Apprenticeship")
                $apps24_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "24+ Apprenticeship");
            else
                $apps24_funding = new LearnerFunding($link, 0, $data, $this->pl, "24+ Apprenticeship");

            if ($data['new_aim_type'] == "16-18 Apprenticeship (From May 2017) Levy Contract")
                $Apps1618LevyMay17_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "16-18 Apprenticeship (From May 2017) Levy Contract");
            else
                $Apps1618LevyMay17_funding = new LearnerFunding($link, 0, $data, $this->pl, "16-18 Apprenticeship (From May 2017) Levy Contract");

            if ($data['new_aim_type'] == "16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured)")
                $Apps1618NLNPMay17_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured)");
            else
                $Apps1618NLNPMay17_funding = new LearnerFunding($link, 0, $data, $this->pl, "16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured)");

            if ($data['new_aim_type'] == "16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured)")
                $Apps1618NLPMay17_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured)");
            else
                $Apps1618NLPMay17_funding = new LearnerFunding($link, 0, $data, $this->pl, "16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured)");

            if ($data['new_aim_type'] == "19+ Apprenticeship (From May 2017) Levy Contract")
                $Apps19LevyMay17_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "19+ Apprenticeship (From May 2017) Levy Contract");
            else
                $Apps19LevyMay17_funding = new LearnerFunding($link, 0, $data, $this->pl, "19+ Apprenticeship (From May 2017) Levy Contract");

            if ($data['new_aim_type'] == "19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured)")
                $Apps19NLNPMay17_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured)");
            else
                $Apps19NLNPMay17_funding = new LearnerFunding($link, 0, $data, $this->pl, "19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured)");

            if ($data['new_aim_type'] == "19+ Apprenticeship Non-Levy Contract (procured)")
                $Apps19NLPMay17_funding = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "19+ Apprenticeship Non-Levy Contract (procured)");
            else
                $Apps19NLPMay17_funding = new LearnerFunding($link, 0, $data, $this->pl, "19+ Apprenticeship Non-Levy Contract (procured)");

            $data['contract_year'] = $data['contract_year'] + 1;
            $shadow_learner = new LearnerFunding($link, $data['total_funding'], $data, $this->pl, "All");


            $lastLearner = null;

            if (array_key_exists($data['home_postcode'], $home_postcodes))
                if ($data['FundModel'] == 35)
                    $disup = (float)$home_postcodes[$data['home_postcode']]['SFA'];
                else
                    $disup = (float)$home_postcodes[$data['home_postcode']]['EFA'];
            else
                $disup = 1;

            if (array_key_exists($data['postcode'], $postcodes))
                if ($data['FundModel'] == 35)
                    $area = (float)$postcodes[$data['postcode']]['SFA'];
                else
                    $area = (float)$postcodes[$data['postcode']]['EFA'];
            else
                $area = 1;

            if ($data['age'] > 18)
                if (array_key_exists($data['edrs'], $large_employer))
                    $discount = (float)$large_employer[$data['edrs']];
                else
                    $discount = 1;
            else
                $discount = 1;

            if ($data['fully_funded'] == '2') // || $data['age']>18)
                if (array_key_exists($data['qualid'], $ksarray))
                    if ($ksarray[$data['qualid']] == "NVQ/GNVQ Key Skills Unit" || $ksarray[$data['qualid']] == "Functional Skills")
                        $fee_proportion = 0.825;
                    else
                        $fee_proportion = 0.5;
                else
                    $fee_proportion = 1;
            else
                $fee_proportion = 1;


            // get the Achivemenet payment back to 100%
            if ($data['funding_remaining_weight'] == '0' || $data['funding_remaining_weight'] == '')
                $prior = 1;
            else
                $prior = $data['funding_remaining_weight'];
            //
            //pre($data);
            //pre($data);
            $LearnStartDate = $data['learner_start_date'];
            $LearnPlanEndDate = $data['learner_target_end_date'];
            $LearnActEndDate = $data['learner_end_date'];
            $LearnAimRef = $data['qualid'];
            $no_of_planned_instalments = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$LearnPlanEndDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");

            $last_day = DAO::getSingleValue($link, "SELECT LAST_DAY('$LearnPlanEndDate')='$LearnPlanEndDate'");
            if ($last_day)
                $no_of_planned_instalments++;
            if ($data['FundModel'] == '81' or $data['FundModel'] == '36')
                $no_of_planned_instalments--;

            // No of Pre-Transational instalments
            $first_day_of_current_funding_period = new Date($contract_year . '-08-01');
            $achieved = false;

            $PED = new Date(DATE::toMedium($LearnPlanEndDate));
            $LSD = new Date(DATE::toMedium($LearnStartDate));

            if (Date::isDate($LearnActEndDate))
                if ($data['FundModel'] == 25)
                    if ($LSD->before($contract_year . '-08-01'))
                        $the_learning_delivery_actual_number_of_days_in_learning = DAO::getSingleValue($link, "SELECT DATEDIFF('$LearnActEndDate',('$contract_year-08-01'))+1;");
                    else
                        $the_learning_delivery_actual_number_of_days_in_learning = DAO::getSingleValue($link, "SELECT DATEDIFF('$LearnActEndDate','$LearnStartDate')+1;");
                else
                    $the_learning_delivery_actual_number_of_days_in_learning = DAO::getSingleValue($link, "SELECT DATEDIFF('$LearnActEndDate','$LearnStartDate')+1;");
            else
                $the_learning_delivery_actual_number_of_days_in_learning = 43;
            $the_learning_delivery_planned_number_of_days_in_learning = DAO::getSingleValue($link, "SELECT DATEDIFF('$LearnPlanEndDate','$LearnStartDate')+1;");

            $threshold_days = 0;
            if ($the_learning_delivery_planned_number_of_days_in_learning >= 1 && $the_learning_delivery_planned_number_of_days_in_learning < 14)
                $threshold_days = 1;
            elseif ($the_learning_delivery_planned_number_of_days_in_learning >= 14 && $the_learning_delivery_planned_number_of_days_in_learning < 168)
                $threshold_days = 14;
            elseif ($the_learning_delivery_planned_number_of_days_in_learning >= 168)
                $threshold_days = 42;
            if ($the_learning_delivery_actual_number_of_days_in_learning >= $threshold_days)
                $threshold_eligible = true;
            else
                $threshold_eligible = false;

            $the_learning_delivery_aim_type = $data['aim_type'];
            $PwayCode = $data['PwayCode'];
            $FworkCode = $data['FworkCode'];

            if (($the_learning_delivery_aim_type == '16-18 Apprenticeships' || $the_learning_delivery_aim_type == '19-23 Apprenticeships' || $the_learning_delivery_aim_type == '24+ Apprenticeships') && $LearnAimRef != 'ZPROG001')
                $the_learning_delivery_is_an_apprenticeship_component_aim = true;
            else
                $the_learning_delivery_is_an_apprenticeship_component_aim = false;

            $the_learning_delivery_framework_component_type_code = DAO::getSingleValue($link, "SELECT FrameworkComponentType FROM lars201718.Core_LARS_FrameworkAims WHERE LearnAimRef = '$LearnAimRef' AND FworkCode = '$FworkCode' AND PwayCode = '$PwayCode'");
            if ($the_learning_delivery_is_an_apprenticeship_component_aim && ($the_learning_delivery_framework_component_type_code == '001' || $the_learning_delivery_framework_component_type_code == '003'))
                $the_learning_delivery_is_an_apprenticeship_competency_aim = true;
            else
                $the_learning_delivery_is_an_apprenticeship_competency_aim = false;
            if ($the_learning_delivery_is_an_apprenticeship_component_aim && $the_learning_delivery_framework_component_type_code == '002')
                $the_learning_delivery_is_an_apprenticeship_knowledge_aim = true;
            else
                $the_learning_delivery_is_an_apprenticeship_knowledge_aim = false;

            //$no_of_actual_instalments_this_year = 12;
            if ($data['achieved'] == 1)
                $achieved = true;

            if ($first_day_of_current_funding_period->after($LearnStartDate)) // Transitional
            {

                // Check if Actual end date is found
                if (Date::isDate($LearnActEndDate)) {
                    $aim_achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$LearnActEndDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contract_year-08-01','%Y%m'))");
                    // check if actual end date is in previous year
                    if ($first_day_of_current_funding_period->after($LearnActEndDate) || $first_day_of_current_funding_period->after($LearnPlanEndDate)) {
                        $pre_transitional_instalments = $no_of_planned_instalments;
                        $no_of_actual_instalments_this_year = 0;
                    } else {
                        $pre_transitional_instalments = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$contract_year-07-31'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");
                        if ($PED->before($LearnActEndDate)) {
                            $no_of_actual_instalments_this_year = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(LAST_DAY('$LearnPlanEndDate'),'%Y%m'),DATE_FORMAT('$contract_year-08-01','%Y%m'))");
                            $last_day = DAO::getSingleValue($link, "SELECT '$LearnPlanEndDate' = LAST_DAY('$LearnPlanEndDate')");
                            if ($last_day)
                                $no_of_actual_instalments_this_year++;
                        } else
                            $no_of_actual_instalments_this_year = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(LAST_DAY('$LearnActEndDate'),'%Y%m'),DATE_FORMAT('$contract_year-08-01','%Y%m'))");
                    }
                    $FrameworkAchievementDate = $data['framework_achivement_date'];
                    if (Date::isDate($FrameworkAchievementDate) && ($the_learning_delivery_is_an_apprenticeship_competency_aim || $the_learning_delivery_is_an_apprenticeship_knowledge_aim)) {
                        $FrameworkAchievementDate = Date::toMySQL($FrameworkAchievementDate);
                        $achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$FrameworkAchievementDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contract_year-08-01','%Y%m'))");
                    } else {
                        $achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$LearnActEndDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contract_year-08-01','%Y%m'))");
                    }
                } else {
                    $pre_transitional_instalments = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$contract_year-07-31'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");
                    $no_of_actual_instalments_this_year = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(LAST_DAY('$LearnPlanEndDate'),'%Y%m'),DATE_FORMAT('$contract_year-08-01','%Y%m'))");
                    $achievement_profiled_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY(IF('$LearnPlanEndDate'>CURDATE(),'$LearnPlanEndDate',CURDATE())), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contract_year-08-01','%Y%m'))");

                    if ($pre_transitional_instalments > $no_of_planned_instalments)
                        $pre_transitional_instalments = $no_of_planned_instalments;
                }
                if ($data['FundModel'] != '36' && $pre_transitional_instalments > 0 && $pre_transitional_instalments < $no_of_planned_instalments)
                    $pre_transitional_instalments++;
            } else {
                $pre_transitional_instalments = 0;
                $no_of_actual_instalments_this_year = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(LAST_DAY('$LearnPlanEndDate'),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");
                if (Date::isDate($LearnActEndDate)) {
                    $aim_achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$LearnActEndDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");
                    if ($PED->after($LearnActEndDate))
                        $no_of_actual_instalments_this_year = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(LAST_DAY('$LearnActEndDate'),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");

                    $FrameworkAchievementDate = $data['framework_achivement_date'];
                    if (Date::isDate($FrameworkAchievementDate) && ($the_learning_delivery_is_an_apprenticeship_competency_aim || $the_learning_delivery_is_an_apprenticeship_knowledge_aim)) {
                        $FrameworkAchievementDate = Date::toMySQL($FrameworkAchievementDate);
                        $achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$FrameworkAchievementDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contract_year-08-01','%Y%m'))");
                        //$achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$FrameworkAchievementDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");
                    } else {
                        $aim_achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$LearnActEndDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contract_year-08-01','%Y%m'))");
                        $achievement_period = $aim_achievement_period;
                    }
                } else {
                    $achievement_profiled_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY(IF('$LearnPlanEndDate'>CURDATE(),'$LearnPlanEndDate',CURDATE())), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contract_year-08-01','%Y%m'))");
                }
                if ($data['FundModel'] != '36')
                    $no_of_actual_instalments_this_year++;
            }


            // Check if actual end date was the last day of month then increment actual by 1
            if (Date::isDate($LearnActEndDate)) {
                if ($PED->before($LearnActEndDate)) {
                    if ($last_day) {
                        $no_of_actual_instalments_this_year++;
                    }
                } else {
                    $last_day = DAO::getSingleValue($link, "SELECT LAST_DAY('$LearnActEndDate')='$LearnActEndDate'");
                    if ($last_day)
                        $no_of_actual_instalments_this_year++;
                }
            } else {
                if ($last_day)
                    $no_of_actual_instalments_this_year++;
            }
            //
            // Actuals cannot be less than 2 for non-transitional learners
            if (!$first_day_of_current_funding_period->after($LearnStartDate)) // Transitional
                if ($no_of_actual_instalments_this_year == 1 && $data['FundModel'] != '36')
                    $no_of_actual_instalments_this_year = 2;


            if ($data['aim_type'] == 'Classroom' || $data['aim_type'] == 'Workplace') {
                $Rate201314 = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201718.`Core_LARS_Funding` WHERE FundingCategory = 'Matrix' AND LearnAimRef = '$LearnAimRef' ORDER By EffectiveFrom Desc LIMIT 0,1;");
            } else {
                //if(SOURCE_BLYTHE_VALLEY)
                $Rate201314 = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201718.Core_LARS_Funding WHERE FundingCategory = 'APP_ACT_COST' AND LearnAimRef = '$LearnAimRef' AND '$LearnStartDate' >= EffectiveFrom AND ('$LearnStartDate' <= EffectiveTo OR EffectiveTo IS NULL) LIMIT 0,1;");
                if ($Rate201314 == '')
                    $Rate201314 = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201718.Core_LARS_Funding WHERE FundingCategory = 'APP_ACT_COST' AND LearnAimRef = '$LearnAimRef' ORDER By EffectiveFrom Desc  LIMIT 0,1;");
            }
            $UnCappedRate201314 = DAO::getSingleValue($link, "SELECT RateUnWeighted FROM lars201718.`Core_LARS_Funding` WHERE FundingCategory = 'Matrix' AND LearnAimRef = '$LearnAimRef' ORDER By EffectiveFrom Desc LIMIT 0,1;");

            if ($data['FundModel'] == '36') {
                if ($data['qualid'] == 'ZPROG001')
                    if ($data['LevyCap'] == "" or $data['TotalNegotiatedPrice'] < $data['LevyCap'])
                        $Rate201314 = $data['TotalNegotiatedPrice'];
                    else
                        $Rate201314 = $data['LevyCap'];
                else
                    $Rate201314 = 0;

                // Calculate Provider Incentive and Employer Incentive

            }

            if ($data['L03'] == '000000001701' and $data['qualid'] = 'ZPROG001') {
                //pre($data['LevyCap']);
            }


            if ($data['programme_type'] == 2)
                $the_learning_delivery_is_an_apprenticeship = true;
            else
                $the_learning_delivery_is_an_apprenticeship = false;

            $the_learning_delivery_framework_common_component_code = DAO::getSingleValue($link, "SELECT FrameworkCommonComponent FROM lars201718.Core_LARS_LearningDelivery WHERE LearnAimRef = '$LearnAimRef'");
            if (($the_learning_delivery_framework_common_component_code == '10' || $the_learning_delivery_framework_common_component_code == '11' || $the_learning_delivery_framework_common_component_code == '12') && $the_learning_delivery_is_an_apprenticeship)
                $the_learning_delivery_is_an_apprenticeship_functional_skills_aim = true;
            else
                $the_learning_delivery_is_an_apprenticeship_functional_skills_aim = false;

            if ($data['aim_type'] == '16-18 Apprenticeships')
                $App_age_factor = 1.0723;
            else
                $App_age_factor = 1;

            if ($data['FundModel'] == '36') {
                $Total_Funding = $Rate201314;
                $FWTotalFunding = $data['1618FrameworkUplift'];
            } else {
                $Total_Funding = $Rate201314 * $App_age_factor * $disup * $discount * $area;
                $FWTotalFunding = 0;
            }


            if ($the_learning_delivery_is_an_apprenticeship_functional_skills_aim && $the_learning_delivery_aim_type == '16-18 Apprenticeships')
                $Total_Funding = $Total_Funding * 0.606061;

            // Check if Achievement payment was held back
            if ($data['fully_funded'] == 2)
                if ($the_learning_delivery_aim_type == '19-23 Apprenticeships' || $the_learning_delivery_aim_type == '24+ Apprenticeships' || $the_learning_delivery_aim_type == '16-18 Apprenticeships')
                    $Total_Funding = $Total_Funding / 2;
                else
                    $Total_Funding = $Total_Funding - ($UnCappedRate201314 / 2);

            if (($data['FundModel'] == '81' or $data['FundModel'] == '36') && $the_learning_delivery_is_an_apprenticeship_functional_skills_aim)
                $Total_Funding = 471;


            $Total_Funding = $Total_Funding * $data['proportion'] / 100;

            if ($the_learning_delivery_aim_type == '24+ Apprenticeships' and $data['FundModel'] != '36')
                $Total_Funding = $Total_Funding * 0.80;


            $balance = 0;
            $held_back = 0;
            if ($LSD->before('01/08/2013'))
                $held_back_rate = "0.25";
            else
                $held_back_rate = "0.20";
            if ($LSD->before('01/08/2013')) // If learner started in before 2013
            {
                if ($the_learning_delivery_is_an_apprenticeship_competency_aim || $data['aim_type'] == 'Classroom' || $data['aim_type'] == 'Workplace') {
                    if ($achievement_period > 0 || !$achieved)
                        $held_back = $Total_Funding * $held_back_rate;
                    else
                        $held_back = 0;

                    $remaining_amount = $Total_Funding * (1 - $held_back_rate);
                } else {
                    $held_back = 0;
                    $remaining_amount = $Total_Funding * 0.80;
                }
            } else {
                $remaining_amount = $Total_Funding * 0.80;
            }

            $fw_remaining_amount = $FWTotalFunding * 0.80;

            $prior = trim($data['prior_learning']);
            if ($prior != "")
                $remaining_amount = $remaining_amount / 100 * (int)$prior;

            if ($data['FundModel'] == '36' and $the_learning_delivery_is_an_apprenticeship_functional_skills_aim)
                $remaining_amount = 471 / 100 * (int)$prior;

            if ($no_of_planned_instalments == 0)
                $no_of_planned_instalments = 1;

            $monthly_instalment = $remaining_amount / $no_of_planned_instalments;
            $fw_monthly_instalment = $fw_remaining_amount / $no_of_planned_instalments;


            // If transitional learner
            if ($LSD->before('01/08/2013') && $contract_year >= 2014) {
                $amount_paid_pre_transition = $monthly_instalment * ($pre_transitional_instalments - 12);
                $amount_remained_post_2013 = $remaining_amount - $amount_paid_pre_transition + ($held_back * 0.20);
                $monthly_instalment_in_2013 = $amount_remained_post_2013 / ($no_of_planned_instalments - ($pre_transitional_instalments - 12));
                $paid_in_2013 = $monthly_instalment_in_2013 * 12;
                $amount_for_this_year = $amount_remained_post_2013 - $paid_in_2013;
                $fw_amount_for_this_year = 0;
            } else {
                $amount_paid_pre_transition = $monthly_instalment * $pre_transitional_instalments;
                $amount_for_this_year = $remaining_amount - $amount_paid_pre_transition + $held_back;
                $fw_amount_paid_pre_transition = $fw_monthly_instalment * $pre_transitional_instalments;
                $fw_amount_for_this_year = $fw_remaining_amount - $fw_amount_paid_pre_transition;
            }

            if ($data['FundModel'] == 25)
                if ($threshold_eligible)
                    $amount_for_this_year = $data['EFA_Amount'];
                else
                    $amount_for_this_year = 0;

            $achievement_amount = $Total_Funding * 0.2;
            $fw_achievement_amount = $FWTotalFunding * 0.2;


            if ($data['restart'] != 1)
                if ($prior != '')
                    $achievement_amount = $achievement_amount / 100 * (int)$prior;


            $main_aiim = DAO::getSingleValue($link, "SELECT FrameworkComponentType FROM lars201718.`Core_LARS_FrameworkAims` WHERE LearnAimRef = '$LearnAimRef' ORDER BY EffectiveFrom DESC LIMIT 0,1;");
            if (($data['aim_type'] == '16-18 Apprenticeships' || $data['aim_type'] == '19-23 Apprenticeships' || $data['aim_type'] == '24+ Apprenticeships') && $LSD->before('01/08/2013')) {
                // If it is transitional then nullify achievement payment for non-main aims
                if ($main_aiim != '1' && $main_aiim != '2')
                    $achievement_amount = 0;
            }

            if (($data['aim_type'] == '16-18 Apprenticeships' || $data['aim_type'] == '19-23 Apprenticeships' || $data['aim_type'] == '24+ Apprenticeships') && $LSD->after('30/04/2017') && $the_learning_delivery_is_an_apprenticeship_functional_skills_aim) {
                $achievement_amount = 0;
            }


            $amount_remaining_for_opps = $amount_for_this_year;
            $fw_amount_remaining_for_opps = $fw_amount_for_this_year;

            $no_of_instalments_remaining = $no_of_planned_instalments - $pre_transitional_instalments;

            if ($first_day_of_current_funding_period->after($LearnStartDate)) {
                if ($no_of_instalments_remaining > 0) {
                    $opp_this_year = $amount_remaining_for_opps / $no_of_instalments_remaining;
                    $fw_opp_this_year = $fw_amount_remaining_for_opps / $no_of_instalments_remaining;
                } else {
                    $opp_this_year = 0;
                    $fw_opp_this_year = 0;
                }
            } else {
                $opp_this_year = $monthly_instalment;
                $fw_opp_this_year = $fw_monthly_instalment;
            }


            $start_month = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT('$LearnStartDate','%Y%m'),DATE_FORMAT('$contract_year-07-31','%Y%m'));");

            $no_of_instalments_this_year = $no_of_planned_instalments - $pre_transitional_instalments;
            if ($no_of_actual_instalments_this_year < $no_of_instalments_remaining)
                $no_of_instalments_this_year = $no_of_actual_instalments_this_year;

            // Balancing Payment
            if ($no_of_actual_instalments_this_year == 0 && $amount_remaining_for_opps > 0) {
                $balance = $amount_remaining_for_opps;
                $fw_balance = $fw_amount_remaining_for_opps;
            } elseif ($no_of_actual_instalments_this_year < $no_of_instalments_remaining) {
                $balance = $opp_this_year * ($no_of_instalments_remaining - $no_of_actual_instalments_this_year);
                $fw_balance = $fw_opp_this_year * ($no_of_instalments_remaining - $no_of_actual_instalments_this_year);
            }

            $index_paid = 1;

            $no_of_instalments_this_year = $no_of_planned_instalments - $pre_transitional_instalments;
            if ($no_of_actual_instalments_this_year < $no_of_instalments_remaining)
                $no_of_instalments_this_year = $no_of_actual_instalments_this_year;

            // Balancing Payment
            if ($aim_achievement_period > 0)
                $balance_period = $aim_achievement_period;
            else
                $balance_period = 1;

            // Achivement Period Planned
            if ($achievement_period < $submissionp)
                $achievement_period_planned = $submissionp;
            else
                $achievement_period_planned = $achievement_period;



            // EFA Installments
            if ($data['FundModel'] == 25) {
                // Calculate EFA Start Date
                if ($LSD->before($contract_year . '-08-01'))
                    $EFA_Start_Date = ($contract_year . '-08-01');
                else
                    $EFA_Start_Date = $LSD->formatMySQL();

                if (Date::isDate($LearnActEndDate)) {
                    if ($PED->after($LearnActEndDate))
                        $EFA_End_Date = $LearnActEndDate;
                    else
                        $EFA_End_Date = $PED->formatMySQL();
                } else {
                    $EFA_End_Date = $PED->formatMySQL();
                }

                $EFA_Instalments = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT('$EFA_End_Date','%Y%m'),DATE_FORMAT('$EFA_Start_Date','%Y%m'))");
                $EFA_Instalments++;
            }


            for ($i = 1; $i <= $predictor_duration; $i++) {
                $learner->set('on_program', $i, 0);
                $learner->set('balance', $i, 0);
                $learner->set('achievement', $i, 0);
                $learner->set('achievement_predicted', $i, 0);
                $learner->set('1618_prov_inc', $i, 0);
                $learner->set('1618_emp_inc', $i, 0);
                $learner->set('FM36_disadv', $i, 0);
                $learner->set('framework_uplift_opp', $i, 0);
                $learner->set('framework_uplift_bal', $i, 0);
                $learner->set('framework_uplift_comp', $i, 0);
                $learner->set('at_risk', $i, 0);

                $Traineeship1924NP_funding->set('on_program', $i, 0);
                $Traineeship1924NP_funding->set('balance', $i, 0);
                $Traineeship1924NP_funding->set('achievement', $i, 0);
                $Traineeship1924NP_funding->set('achievement_predicted', $i, 0);
                $Traineeship1924NP_funding->set('1618_prov_inc', $i, 0);
                $Traineeship1924NP_funding->set('1618_emp_inc', $i, 0);
                $Traineeship1924NP_funding->set('FM36_disadv', $i, 0);
                $Traineeship1924NP_funding->set('framework_uplift_opp', $i, 0);
                $Traineeship1924NP_funding->set('framework_uplift_bal', $i, 0);
                $Traineeship1924NP_funding->set('framework_uplift_comp', $i, 0);
                $Traineeship1924NP_funding->set('at_risk', $i, 0);

                $Traineeship1924PMay17_funding->set('on_program', $i, 0);
                $Traineeship1924PMay17_funding->set('balance', $i, 0);
                $Traineeship1924PMay17_funding->set('achievement', $i, 0);
                $Traineeship1924PMay17_funding->set('achievement_predicted', $i, 0);
                $Traineeship1924PMay17_funding->set('1618_prov_inc', $i, 0);
                $Traineeship1924PMay17_funding->set('1618_emp_inc', $i, 0);
                $Traineeship1924PMay17_funding->set('FM36_disadv', $i, 0);
                $Traineeship1924PMay17_funding->set('framework_uplift_opp', $i, 0);
                $Traineeship1924PMay17_funding->set('framework_uplift_bal', $i, 0);
                $Traineeship1924PMay17_funding->set('framework_uplift_comp', $i, 0);
                $Traineeship1924PMay17_funding->set('at_risk', $i, 0);

                $AEBOtherLearningNP_funding->set('on_program', $i, 0);
                $AEBOtherLearningNP_funding->set('balance', $i, 0);
                $AEBOtherLearningNP_funding->set('achievement', $i, 0);
                $AEBOtherLearningNP_funding->set('achievement_predicted', $i, 0);
                $AEBOtherLearningNP_funding->set('1618_prov_inc', $i, 0);
                $AEBOtherLearningNP_funding->set('1618_emp_inc', $i, 0);
                $AEBOtherLearningNP_funding->set('FM36_disadv', $i, 0);
                $AEBOtherLearningNP_funding->set('framework_uplift_opp', $i, 0);
                $AEBOtherLearningNP_funding->set('framework_uplift_bal', $i, 0);
                $AEBOtherLearningNP_funding->set('framework_uplift_comp', $i, 0);
                $AEBOtherLearningNP_funding->set('at_risk', $i, 0);

                $AEBOtherLearningPNov17_funding->set('on_program', $i, 0);
                $AEBOtherLearningPNov17_funding->set('balance', $i, 0);
                $AEBOtherLearningPNov17_funding->set('achievement', $i, 0);
                $AEBOtherLearningPNov17_funding->set('achievement_predicted', $i, 0);
                $AEBOtherLearningPNov17_funding->set('1618_prov_inc', $i, 0);
                $AEBOtherLearningPNov17_funding->set('1618_emp_inc', $i, 0);
                $AEBOtherLearningPNov17_funding->set('FM36_disadv', $i, 0);
                $AEBOtherLearningPNov17_funding->set('framework_uplift_opp', $i, 0);
                $AEBOtherLearningPNov17_funding->set('framework_uplift_bal', $i, 0);
                $AEBOtherLearningPNov17_funding->set('framework_uplift_comp', $i, 0);
                $AEBOtherLearningPNov17_funding->set('at_risk', $i, 0);

                $apps1618_funding->set('on_program', $i, 0);
                $apps1618_funding->set('balance', $i, 0);
                $apps1618_funding->set('achievement', $i, 0);
                $apps1618_funding->set('achievement_predicted', $i, 0);
                $apps1618_funding->set('1618_prov_inc', $i, 0);
                $apps1618_funding->set('1618_emp_inc', $i, 0);
                $apps1618_funding->set('FM36_disadv', $i, 0);
                $apps1618_funding->set('framework_uplift_opp', $i, 0);
                $apps1618_funding->set('framework_uplift_bal', $i, 0);
                $apps1618_funding->set('framework_uplift_comp', $i, 0);
                $apps1618_funding->set('at_risk', $i, 0);

                $apps1923_funding->set('on_program', $i, 0);
                $apps1923_funding->set('balance', $i, 0);
                $apps1923_funding->set('achievement', $i, 0);
                $apps1923_funding->set('achievement_predicted', $i, 0);
                $apps1923_funding->set('1618_prov_inc', $i, 0);
                $apps1923_funding->set('1618_emp_inc', $i, 0);
                $apps1923_funding->set('FM36_disadv', $i, 0);
                $apps1923_funding->set('framework_uplift_opp', $i, 0);
                $apps1923_funding->set('framework_uplift_bal', $i, 0);
                $apps1923_funding->set('framework_uplift_comp', $i, 0);
                $apps1923_funding->set('at_risk', $i, 0);

                $apps24_funding->set('on_program', $i, 0);
                $apps24_funding->set('balance', $i, 0);
                $apps24_funding->set('achievement', $i, 0);
                $apps24_funding->set('achievement_predicted', $i, 0);
                $apps24_funding->set('1618_prov_inc', $i, 0);
                $apps24_funding->set('1618_emp_inc', $i, 0);
                $apps24_funding->set('FM36_disadv', $i, 0);
                $apps24_funding->set('framework_uplift_opp', $i, 0);
                $apps24_funding->set('framework_uplift_bal', $i, 0);
                $apps24_funding->set('framework_uplift_comp', $i, 0);
                $apps24_funding->set('at_risk', $i, 0);

                $Apps1618LevyMay17_funding->set('on_program', $i, 0);
                $Apps1618LevyMay17_funding->set('balance', $i, 0);
                $Apps1618LevyMay17_funding->set('achievement', $i, 0);
                $Apps1618LevyMay17_funding->set('achievement_predicted', $i, 0);
                $Apps1618LevyMay17_funding->set('1618_prov_inc', $i, 0);
                $Apps1618LevyMay17_funding->set('1618_emp_inc', $i, 0);
                $Apps1618LevyMay17_funding->set('FM36_disadv', $i, 0);
                $Apps1618LevyMay17_funding->set('framework_uplift_opp', $i, 0);
                $Apps1618LevyMay17_funding->set('framework_uplift_bal', $i, 0);
                $Apps1618LevyMay17_funding->set('framework_uplift_comp', $i, 0);
                $Apps1618LevyMay17_funding->set('at_risk', $i, 0);

                $Apps1618NLNPMay17_funding->set('on_program', $i, 0);
                $Apps1618NLNPMay17_funding->set('balance', $i, 0);
                $Apps1618NLNPMay17_funding->set('achievement', $i, 0);
                $Apps1618NLNPMay17_funding->set('achievement_predicted', $i, 0);
                $Apps1618NLNPMay17_funding->set('1618_prov_inc', $i, 0);
                $Apps1618NLNPMay17_funding->set('1618_emp_inc', $i, 0);
                $Apps1618NLNPMay17_funding->set('FM36_disadv', $i, 0);
                $Apps1618NLNPMay17_funding->set('framework_uplift_opp', $i, 0);
                $Apps1618NLNPMay17_funding->set('framework_uplift_bal', $i, 0);
                $Apps1618NLNPMay17_funding->set('framework_uplift_comp', $i, 0);
                $Apps1618NLNPMay17_funding->set('at_risk', $i, 0);

                $Apps1618NLPMay17_funding->set('on_program', $i, 0);
                $Apps1618NLPMay17_funding->set('balance', $i, 0);
                $Apps1618NLPMay17_funding->set('achievement', $i, 0);
                $Apps1618NLPMay17_funding->set('achievement_predicted', $i, 0);
                $Apps1618NLPMay17_funding->set('1618_prov_inc', $i, 0);
                $Apps1618NLPMay17_funding->set('1618_emp_inc', $i, 0);
                $Apps1618NLPMay17_funding->set('FM36_disadv', $i, 0);
                $Apps1618NLPMay17_funding->set('framework_uplift_opp', $i, 0);
                $Apps1618NLPMay17_funding->set('framework_uplift_bal', $i, 0);
                $Apps1618NLPMay17_funding->set('framework_uplift_comp', $i, 0);
                $Apps1618NLPMay17_funding->set('at_risk', $i, 0);

                $Apps19LevyMay17_funding->set('on_program', $i, 0);
                $Apps19LevyMay17_funding->set('balance', $i, 0);
                $Apps19LevyMay17_funding->set('achievement', $i, 0);
                $Apps19LevyMay17_funding->set('achievement_predicted', $i, 0);
                $Apps19LevyMay17_funding->set('1618_prov_inc', $i, 0);
                $Apps19LevyMay17_funding->set('1618_emp_inc', $i, 0);
                $Apps19LevyMay17_funding->set('FM36_disadv', $i, 0);
                $Apps19LevyMay17_funding->set('framework_uplift_opp', $i, 0);
                $Apps19LevyMay17_funding->set('framework_uplift_bal', $i, 0);
                $Apps19LevyMay17_funding->set('framework_uplift_comp', $i, 0);
                $Apps19LevyMay17_funding->set('at_risk', $i, 0);

                $Apps19NLNPMay17_funding->set('on_program', $i, 0);
                $Apps19NLNPMay17_funding->set('balance', $i, 0);
                $Apps19NLNPMay17_funding->set('achievement', $i, 0);
                $Apps19NLNPMay17_funding->set('achievement_predicted', $i, 0);
                $Apps19NLNPMay17_funding->set('1618_prov_inc', $i, 0);
                $Apps19NLNPMay17_funding->set('1618_emp_inc', $i, 0);
                $Apps19NLNPMay17_funding->set('FM36_disadv', $i, 0);
                $Apps19NLNPMay17_funding->set('framework_uplift_opp', $i, 0);
                $Apps19NLNPMay17_funding->set('framework_uplift_bal', $i, 0);
                $Apps19NLNPMay17_funding->set('framework_uplift_comp', $i, 0);
                $Apps19NLNPMay17_funding->set('at_risk', $i, 0);

                $Apps19NLPMay17_funding->set('on_program', $i, 0);
                $Apps19NLPMay17_funding->set('balance', $i, 0);
                $Apps19NLPMay17_funding->set('achievement', $i, 0);
                $Apps19NLPMay17_funding->set('achievement_predicted', $i, 0);
                $Apps19NLPMay17_funding->set('1618_prov_inc', $i, 0);
                $Apps19NLPMay17_funding->set('1618_emp_inc', $i, 0);
                $Apps19NLPMay17_funding->set('FM36_disadv', $i, 0);
                $Apps19NLPMay17_funding->set('framework_uplift_opp', $i, 0);
                $Apps19NLPMay17_funding->set('framework_uplift_bal', $i, 0);
                $Apps19NLPMay17_funding->set('framework_uplift_comp', $i, 0);
                $Apps19NLPMay17_funding->set('at_risk', $i, 0);
            }

            if ($data['FundModel'] == '25') {
                for ($i = 1; $i <= $predictor_duration; $i++) {
                    $learner->set('on_program', $i, 0);
                    $learner->set('balance', $i, 0);
                    $learner->set('achievement', $i, 0);
                    $learner->set('achievement_predicted', $i, 0);
                    $learner->set('at_risk', $i, 0);
                    $learner->set('1618_prov_inc', $i, 0);
                    $learner->set('1618_emp_inc', $i, 0);
                    $learner->set('FM36_disadv', $i, 0);
                    $learner->set('framework_uplift_opp', $i, 0);
                    $learner->set('framework_uplift_bal', $i, 0);
                    $learner->set('framework_uplift_comp', $i, 0);
                }

                $EFA_Start_Date = new Date($EFA_Start_Date);
                $EFA_End_Date = new Date($EFA_End_Date);
                if ($contract_year == 2014) {
                    if ($EFA_Start_Date->before('2014-09-01') && $EFA_End_Date->after('2014-07-31'))
                        $learner->set('on_program', 1, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2014-10-01') && $EFA_End_Date->after('2014-08-31'))
                        $learner->set('on_program', 2, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2014-11-01') && $EFA_End_Date->after('2014-09-30'))
                        $learner->set('on_program', 3, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2014-12-01') && $EFA_End_Date->after('2014-10-31'))
                        $learner->set('on_program', 4, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2015-01-01') && $EFA_End_Date->after('2014-11-30'))
                        $learner->set('on_program', 5, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2015-02-01') && $EFA_End_Date->after('2014-12-31'))
                        $learner->set('on_program', 6, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2015-03-01') && $EFA_End_Date->after('2015-01-31'))
                        $learner->set('on_program', 7, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2015-04-01') && $EFA_End_Date->after('2015-02-28'))
                        $learner->set('on_program', 8, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2015-05-01') && $EFA_End_Date->after('2015-03-31'))
                        $learner->set('on_program', 9, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2015-06-01') && $EFA_End_Date->after('2015-04-30'))
                        $learner->set('on_program', 10, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2015-07-01') && $EFA_End_Date->after('2015-05-31'))
                        $learner->set('on_program', 11, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2015-08-01') && $EFA_End_Date->after('2015-06-30'))
                        $learner->set('on_program', 12, $amount_for_this_year / $EFA_Instalments);
                } elseif ($contract_year == 2015) {
                    if ($EFA_Start_Date->before('2015-09-01') && $EFA_End_Date->after('2015-07-31'))
                        $learner->set('on_program', 1, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2015-10-01') && $EFA_End_Date->after('2015-08-31'))
                        $learner->set('on_program', 2, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2015-11-01') && $EFA_End_Date->after('2015-09-30'))
                        $learner->set('on_program', 3, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2015-12-01') && $EFA_End_Date->after('2015-10-31'))
                        $learner->set('on_program', 4, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2016-01-01') && $EFA_End_Date->after('2015-11-30'))
                        $learner->set('on_program', 5, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2016-02-01') && $EFA_End_Date->after('2015-12-31'))
                        $learner->set('on_program', 6, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2016-03-01') && $EFA_End_Date->after('2016-01-31'))
                        $learner->set('on_program', 7, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2016-04-01') && $EFA_End_Date->after('2016-02-28'))
                        $learner->set('on_program', 8, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2016-05-01') && $EFA_End_Date->after('2016-03-31'))
                        $learner->set('on_program', 9, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2016-06-01') && $EFA_End_Date->after('2016-04-30'))
                        $learner->set('on_program', 10, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2016-07-01') && $EFA_End_Date->after('2016-05-31'))
                        $learner->set('on_program', 11, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2016-08-01') && $EFA_End_Date->after('2016-06-30'))
                        $learner->set('on_program', 12, $amount_for_this_year / $EFA_Instalments);
                } elseif ($contract_year == 2016) {
                    if ($EFA_Start_Date->before('2016-09-01') && $EFA_End_Date->after('2016-07-31'))
                        $learner->set('on_program', 1, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2016-10-01') && $EFA_End_Date->after('2016-08-31'))
                        $learner->set('on_program', 2, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2016-11-01') && $EFA_End_Date->after('2016-09-30'))
                        $learner->set('on_program', 3, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2016-12-01') && $EFA_End_Date->after('2016-10-31'))
                        $learner->set('on_program', 4, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2017-01-01') && $EFA_End_Date->after('2016-11-30'))
                        $learner->set('on_program', 5, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2017-02-01') && $EFA_End_Date->after('2016-12-31'))
                        $learner->set('on_program', 6, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2017-03-01') && $EFA_End_Date->after('2017-01-31'))
                        $learner->set('on_program', 7, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2017-04-01') && $EFA_End_Date->after('2017-02-28'))
                        $learner->set('on_program', 8, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2017-05-01') && $EFA_End_Date->after('2017-03-31'))
                        $learner->set('on_program', 9, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2017-06-01') && $EFA_End_Date->after('2017-04-30'))
                        $learner->set('on_program', 10, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2017-07-01') && $EFA_End_Date->after('2017-05-31'))
                        $learner->set('on_program', 11, $amount_for_this_year / $EFA_Instalments);
                    if ($EFA_Start_Date->before('2017-08-01') && $EFA_End_Date->after('2017-06-30'))
                        $learner->set('on_program', 12, $amount_for_this_year / $EFA_Instalments);
                }
                if ($data['at_risk'] == '1') {
                    $learner->set('at_risk', 1, $learner->get(1, 'on_program'));
                    $learner->set('at_risk', 2, $learner->get(2, 'on_program'));
                    $learner->set('at_risk', 3, $learner->get(3, 'on_program'));
                    $learner->set('at_risk', 4, $learner->get(4, 'on_program'));
                    $learner->set('at_risk', 5, $learner->get(5, 'on_program'));
                    $learner->set('at_risk', 6, $learner->get(6, 'on_program'));
                    $learner->set('at_risk', 7, $learner->get(7, 'on_program'));
                    $learner->set('at_risk', 8, $learner->get(8, 'on_program'));
                    $learner->set('at_risk', 9, $learner->get(9, 'on_program'));
                    $learner->set('at_risk', 10, $learner->get(10, 'on_program'));
                    $learner->set('at_risk', 11, $learner->get(11, 'on_program'));
                    $learner->set('at_risk', 12, $learner->get(12, 'on_program'));
                }
            }
            if ($data['FundModel'] == '35' || $data['FundModel'] == '81' || $data['FundModel'] == '36')
                for ($i = 1; $i <= $predictor_duration; $i++) {
                    if ($data['new_aim_type'] == '16-18 Apprenticeships' || true) {
                        if ($threshold_eligible || $data['aim_achieved']) {
                            // Check if it should pay this month
                            if ($i >= $start_month) {
                                // Only when enough instalments have not been paid
                                if ($no_of_planned_instalments == 1 && $no_of_instalments_this_year <= 1 && $i == $start_month) {
                                    if ($data['new_aim_type'] == '19-24 Traineeship (non-procured)') {
                                        $Traineeship1924NP_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == '19-24 Traineeship (procured from Nov 2017)') {
                                        $Traineeship1924PMay17_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == 'AEB - Other Learning (non-procured)') {
                                        $AEBOtherLearningNP_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == 'AEB - Other Learning (procured from Nov 2017)') {
                                        $AEBOtherLearningPNov17_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == '16-18 Apprenticeship') {
                                        $apps1618_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == '19-23 Apprenticeship') {
                                        $apps1923_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == '24+ Apprenticeship') {
                                        $apps24_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Levy Contract') {
                                        $Apps1618LevyMay17_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                        $Apps1618NLNPMay17_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured)') {
                                        $Apps1618NLPMay17_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Levy Contract') {
                                        $Apps19LevyMay17_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                        $Apps19NLNPMay17_funding->set('on_program', $i, $opp_this_year);
                                    }
                                    if ($data['new_aim_type'] == '19+ Apprenticeship Non-Levy Contract (procured)') {
                                        $Apps19NLPMay17_funding->set('on_program', $i, $opp_this_year);
                                    }

                                    $learner->set('on_program', $i, $opp_this_year);
                                    $learner->set('framework_uplift_opp', $i, $fw_opp_this_year);
                                    $index_paid++;
                                } elseif ($index_paid <= $no_of_instalments_this_year) {
                                    if ($data['FundModel'] == '36')
                                        $double = 1;
                                    else
                                        $double = 2;
                                    if ($i == $start_month  && $no_of_instalments_remaining > 1) {
                                        if ($data['new_aim_type'] == '19-24 Traineeship (non-procured)') {
                                            $Traineeship1924NP_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == '19-24 Traineeship (procured from Nov 2017)') {
                                            $Traineeship1924PMay17_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == 'AEB - Other Learning (non-procured)') {
                                            $AEBOtherLearningNP_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == 'AEB - Other Learning (procured from Nov 2017)') {
                                            $AEBOtherLearningPNov17_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == '16-18 Apprenticeship') {
                                            $apps1618_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == '19-23 Apprenticeship') {
                                            $apps1923_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == '24+ Apprenticeship') {
                                            $apps24_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Levy Contract') {
                                            $Apps1618LevyMay17_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                            $Apps1618NLNPMay17_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured)') {
                                            $Apps1618NLPMay17_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Levy Contract') {
                                            $Apps19LevyMay17_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                            $Apps19NLNPMay17_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        if ($data['new_aim_type'] == '19+ Apprenticeship Non-Levy Contract (procured)') {
                                            $Apps19NLPMay17_funding->set('on_program', $i, $opp_this_year * $double);
                                        }
                                        $learner->set('on_program', $i, ($opp_this_year * $double));
                                        $learner->set('framework_uplift_opp', $i, $fw_opp_this_year);
                                        if ($data['FundModel'] == '36')
                                            $index_paid++;
                                        else
                                            $index_paid += 2;
                                    } else {
                                        if ($data['new_aim_type'] == '19-24 Traineeship (non-procured)') {
                                            $Traineeship1924NP_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == '19-24 Traineeship (procured from Nov 2017)') {
                                            $Traineeship1924PMay17_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == 'AEB - Other Learning (non-procured)') {
                                            $AEBOtherLearningNP_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == 'AEB - Other Learning (procured from Nov 2017)') {
                                            $AEBOtherLearningPNov17_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == '16-18 Apprenticeship') {
                                            $apps1618_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == '19-23 Apprenticeship') {
                                            $apps1923_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == '24+ Apprenticeship') {
                                            $apps24_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Levy Contract') {
                                            $Apps1618LevyMay17_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                            $Apps1618NLNPMay17_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured)') {
                                            $Apps1618NLPMay17_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Levy Contract') {
                                            $Apps19LevyMay17_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                            $Apps19NLNPMay17_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        if ($data['new_aim_type'] == '19+ Apprenticeship Non-Levy Contract (procured)') {
                                            $Apps19NLPMay17_funding->set('on_program', $i, $opp_this_year);
                                        }
                                        $learner->set('on_program', $i, $opp_this_year);
                                        $learner->set('framework_uplift_opp', $i, $fw_opp_this_year);
                                        $index_paid++;
                                    }
                                }
                            }
                        }

                        if ($i == $balance_period && $balance > 0 && ($achieved || $data['aim_achieved'])) {

                            if ($data['new_aim_type'] == '19-24 Traineeship (non-procured)') {
                                $Traineeship1924NP_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == '19-24 Traineeship (procured from Nov 2017)') {
                                $Traineeship1924PMay17_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == 'AEB - Other Learning (non-procured)') {
                                $AEBOtherLearningNP_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == 'AEB - Other Learning (procured from Nov 2017)') {
                                $AEBOtherLearningPNov17_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == '16-18 Apprenticeship') {
                                $apps1618_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == '19-23 Apprenticeship') {
                                $apps1923_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == '24+ Apprenticeship') {
                                $apps24_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Levy Contract') {
                                $Apps1618LevyMay17_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                $Apps1618NLNPMay17_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured)') {
                                $Apps1618NLPMay17_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Levy Contract') {
                                $Apps19LevyMay17_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                $Apps19NLNPMay17_funding->set('balance', $i, $balance);
                            }
                            if ($data['new_aim_type'] == '19+ Apprenticeship Non-Levy Contract (procured)') {
                                $Apps19NLPMay17_funding->set('balance', $i, $balance);
                            }
                            $learner->set('balance', $i, $balance);
                            $learner->set('framework_uplift_bal', $i, $fw_balance);
                        }

                        if (
                            (
                                ($main_aiim == '1' || $main_aiim == '2' || $main_aiim == '3')
                                && $data['framework_achieved'] && $LSD->after('2013/08/01')
                            )
                            ||
                            (
                                ($data['aim_type'] != '16-18 Apprenticeships' && $data['aim_type'] != '19-23 Apprenticeships' && $data['aim_type'] != '24+ Apprenticeships')
                                && $data['aim_achieved']
                            )
                            ||
                            (true) && ($data['aim_achieved'] && $LSD->after('2015/08/01')) || ($data['framework_achieved'] && $LSD->after('2013/08/01'))
                        ) {

                            if ($i == $achievement_period) {
                                if ($data['new_aim_type'] == '19-24 Traineeship (non-procured)') {
                                    $Traineeship1924NP_funding->set('achievement', $i, $achievement_amount);
                                    $Traineeship1924NP_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '19-24 Traineeship (procured from Nov 2017)') {
                                    $Traineeship1924PMay17_funding->set('achievement', $i, $achievement_amount);
                                    $Traineeship1924PMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == 'AEB - Other Learning (non-procured)') {
                                    $AEBOtherLearningNP_funding->set('achievement', $i, $achievement_amount);
                                    $AEBOtherLearningNP_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == 'AEB - Other Learning (procured from Nov 2017)') {
                                    $AEBOtherLearningPNov17_funding->set('achievement', $i, $achievement_amount);
                                    $AEBOtherLearningPNov17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '16-18 Apprenticeship') {
                                    $apps1618_funding->set('achievement', $i, $achievement_amount);
                                    $apps1618_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '19-23 Apprenticeship') {
                                    $apps1923_funding->set('achievement', $i, $achievement_amount);
                                    $apps1923_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '24+ Apprenticeship') {
                                    $apps24_funding->set('achievement', $i, $achievement_amount);
                                    $apps24_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Levy Contract') {
                                    $Apps1618LevyMay17_funding->set('achievement', $i, $achievement_amount);
                                    $Apps1618LevyMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                    $Apps1618NLNPMay17_funding->set('achievement', $i, $achievement_amount);
                                    $Apps1618NLNPMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured)') {
                                    $Apps1618NLPMay17_funding->set('achievement', $i, $achievement_amount);
                                    $Apps1618NLPMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Levy Contract') {
                                    $Apps19LevyMay17_funding->set('achievement', $i, $achievement_amount);
                                    $Apps19LevyMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                    $Apps19NLNPMay17_funding->set('achievement', $i, $achievement_amount);
                                    $Apps19NLNPMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '19+ Apprenticeship Non-Levy Contract (procured)') {
                                    $Apps19NLPMay17_funding->set('achievement', $i, $achievement_amount);
                                    $Apps19NLPMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                $learner->set('achievement', $i, $achievement_amount);
                                $learner->set('achievement_predicted', $i, $achievement_amount);
                                $learner->set('framework_uplift_comp', $i, $fw_achievement_amount);
                            }
                        }
                        //else
                        {
                            if ($i == $achievement_profiled_period && $data['continuing']) {
                                if ($data['new_aim_type'] == '19-24 Traineeship (non-procured)') {
                                    $Traineeship1924NP_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '19-24 Traineeship (procured from Nov 2017)') {
                                    $Traineeship1924PMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == 'AEB - Other Learning (non-procured)') {
                                    $AEBOtherLearningNP_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == 'AEB - Other Learning (procured from Nov 2017)') {
                                    $AEBOtherLearningPNov17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '16-18 Apprenticeship') {
                                    $apps1618_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '19-23 Apprenticeship') {
                                    $apps1923_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '24+ Apprenticeship') {
                                    $apps24_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Levy Contract') {
                                    $Apps1618LevyMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                    $Apps1618NLNPMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured)') {
                                    $Apps1618NLPMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Levy Contract') {
                                    $Apps19LevyMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured)') {
                                    $Apps19NLNPMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                if ($data['new_aim_type'] == '19+ Apprenticeship Non-Levy Contract (procured)') {
                                    $Apps19NLPMay17_funding->set('achievement_predicted', $i, $achievement_amount);
                                }
                                $learner->set('achievement_predicted', $i, $achievement_amount);
                            }
                        }
                    }

                    if ($data['at_risk'] == '1') {
                        for ($month_counter = 1; $month_counter <= 12; $month_counter++) {
                            $learner->set('at_risk', $month_counter, ($learner->get($month_counter, 'on_program') + $learner->get($month_counter, 'balance') + $learner->get($month_counter, 'achievement') + $learner->get($month_counter, 'als')));
                            $Traineeship1924NP_funding->set('at_risk', $month_counter, ($Traineeship1924NP_funding->get($month_counter, 'on_program') + $Traineeship1924NP_funding->get($month_counter, 'balance') + $Traineeship1924NP_funding->get($month_counter, 'achievement') + $Traineeship1924NP_funding->get($month_counter, 'als')));
                            $Traineeship1924PMay17_funding->set('at_risk', $month_counter, ($Traineeship1924PMay17_funding->get($month_counter, 'on_program') + $Traineeship1924PMay17_funding->get($month_counter, 'balance') + $Traineeship1924PMay17_funding->get($month_counter, 'achievement') + $Traineeship1924PMay17_funding->get($month_counter, 'als')));
                            $AEBOtherLearningNP_funding->set('at_risk', $month_counter, ($AEBOtherLearningNP_funding->get($month_counter, 'on_program') + $AEBOtherLearningNP_funding->get($month_counter, 'balance') + $AEBOtherLearningNP_funding->get($month_counter, 'achievement') + $AEBOtherLearningNP_funding->get($month_counter, 'als')));
                            $AEBOtherLearningPNov17_funding->set('at_risk', $month_counter, ($AEBOtherLearningPNov17_funding->get($month_counter, 'on_program') + $AEBOtherLearningPNov17_funding->get($month_counter, 'balance') + $AEBOtherLearningPNov17_funding->get($month_counter, 'achievement') + $AEBOtherLearningPNov17_funding->get($month_counter, 'als')));
                            $apps1618_funding->set('at_risk', $month_counter, ($apps1618_funding->get($month_counter, 'on_program') + $apps1618_funding->get($month_counter, 'balance') + $apps1618_funding->get($month_counter, 'achievement') + $apps1618_funding->get($month_counter, 'als')));
                            $apps1923_funding->set('at_risk', $month_counter, ($apps1923_funding->get($month_counter, 'on_program') + $apps1923_funding->get($month_counter, 'balance') + $apps1923_funding->get($month_counter, 'achievement') + $apps1923_funding->get($month_counter, 'als')));
                            $apps24_funding->set('at_risk', $month_counter, ($apps24_funding->get($month_counter, 'on_program') + $apps24_funding->get($month_counter, 'balance') + $apps24_funding->get($month_counter, 'achievement') + $apps24_funding->get($month_counter, 'als')));
                            $Apps1618LevyMay17_funding->set('at_risk', $month_counter, ($Apps1618LevyMay17_funding->get($month_counter, 'on_program') + $Apps1618LevyMay17_funding->get($month_counter, 'balance') + $Apps1618LevyMay17_funding->get($month_counter, 'achievement') + $Apps1618LevyMay17_funding->get($month_counter, 'als')));
                            $Apps1618NLNPMay17_funding->set('at_risk', $month_counter, ($Apps1618NLNPMay17_funding->get($month_counter, 'on_program') + $Apps1618NLNPMay17_funding->get($month_counter, 'balance') + $Apps1618NLNPMay17_funding->get($month_counter, 'achievement') + $Apps1618NLNPMay17_funding->get($month_counter, 'als')));
                            $Apps1618NLPMay17_funding->set('at_risk', $month_counter, ($Apps1618NLPMay17_funding->get($month_counter, 'on_program') + $Apps1618NLPMay17_funding->get($month_counter, 'balance') + $Apps1618NLPMay17_funding->get($month_counter, 'achievement') + $Apps1618NLPMay17_funding->get($month_counter, 'als')));
                            $Apps19LevyMay17_funding->set('at_risk', $month_counter, ($Apps19LevyMay17_funding->get($month_counter, 'on_program') + $Apps19LevyMay17_funding->get($month_counter, 'balance') + $Apps19LevyMay17_funding->get($month_counter, 'achievement') + $Apps19LevyMay17_funding->get($month_counter, 'als')));
                            $Apps19NLNPMay17_funding->set('at_risk', $month_counter, ($Apps19NLNPMay17_funding->get($month_counter, 'on_program') + $Apps19NLNPMay17_funding->get($month_counter, 'balance') + $Apps19NLNPMay17_funding->get($month_counter, 'achievement') + $Apps19NLNPMay17_funding->get($month_counter, 'als')));
                            $Apps19NLPMay17_funding->set('at_risk', $month_counter, ($Apps19NLPMay17_funding->get($month_counter, 'on_program') + $Apps19NLPMay17_funding->get($month_counter, 'balance') + $Apps19NLPMay17_funding->get($month_counter, 'achievement') + $Apps19NLPMay17_funding->get($month_counter, 'als')));
                        }
                    }
                }

            // 1618 Provider and Employer incentive
            for ($i = 1; $i <= $predictor_duration; $i++) {
                if ($data['FundModel'] == '36' and $data['aim_type'] == '16-18 Apprenticeships') {
                    if (isset($data['TrailblazerFunding']['1618ProvIncentive'][$i]) and $data['TrailblazerFunding']['1618ProvIncentive'][$i] == 500)
                        $learner->set('1618_prov_inc', $i, 500);
                    if (isset($data['TrailblazerFunding']['1618EmpIncentive'][$i]) and $data['TrailblazerFunding']['1618EmpIncentive'][$i] == 500)
                        $learner->set('1618_emp_inc', $i, 500);
                }
            }

            // Disadvantage Payment FM36
            for ($i = 1; $i <= $predictor_duration; $i++) {
                if ($data['FundModel'] == '36') {
                    if (isset($data['DisadvantagePayment']['DisadvantagePayment'][$i]) and $data['DisadvantagePayment']['DisadvantagePayment'][$i] > 0)
                        $learner->set('FM36_Disadv', $i, $data['DisadvantagePayment']['DisadvantagePayment'][$i]);
                }
            }


            // rebuild this into a nicer data structure that we can work with
            //			$contract_id = $this->contractInfo->id;
            //			$this->totalFunding["1"]['achivement_predicted'] = 1;
            for ($i = 1; $i <= $predictor_duration; $i++) {
                if (!isset($this->totalFunding["$i"])) {
                    $this->totalFunding["$i"] = array('on_program' => '', 'balance' => '', 'achievement' => '', 'ach_profiled' => '', 'framework_uplift_opp' => '', 'framework_uplift_bal' => '', 'framework_uplift_comp' => '', '1618_prov_inc' => '', '1618_emp_inc' => '', 'FM36_Disadv' => '', 'total' => '', 'als' => '', 'at_risk' => '');
                }
                //				pre($learner->get($i, 'on_program'));
                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select sum(profile) from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select sum(profile) from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                //$this->totalFunding["$i"]['adjusted'] += $learner->get($i,'adjusted');
                $this->totalFunding[$i]['on_program'] = (float) ($this->totalFunding[$i]['on_program'] ?? 0) + (float) $learner->get($i, 'on_program');
                $this->totalFunding[$i]['balance'] =
                    (float) ($this->totalFunding[$i]['balance'] ?? 0) + (float) $learner->get($i, 'balance');

                $this->totalFunding[$i]['achievement'] =
                    (float) ($this->totalFunding[$i]['achievement'] ?? 0) + (float) $learner->get($i, 'achievement');

                $this->totalFunding[$i]['ach_profiled'] =
                    (float) ($this->totalFunding[$i]['ach_profiled'] ?? 0) + (float) $learner->get($i, 'achievement_predicted');

                $this->totalFunding[$i]['framework_uplift_opp'] =
                    (float) ($this->totalFunding[$i]['framework_uplift_opp'] ?? 0) + (float) $learner->get($i, 'framework_uplift_opp');

                $this->totalFunding[$i]['framework_uplift_bal'] =
                    (float) ($this->totalFunding[$i]['framework_uplift_bal'] ?? 0) + (float) $learner->get($i, 'framework_uplift_bal');

                $this->totalFunding[$i]['framework_uplift_comp'] =
                    (float) ($this->totalFunding[$i]['framework_uplift_comp'] ?? 0) + (float) $learner->get($i, 'framework_uplift_comp');

                $this->totalFunding[$i]['1618_prov_inc'] =
                    (float) ($this->totalFunding[$i]['1618_prov_inc'] ?? 0) + (float) $learner->get($i, '1618_prov_inc');

                $this->totalFunding[$i]['1618_emp_inc'] =
                    (float) ($this->totalFunding[$i]['1618_emp_inc'] ?? 0) + (float) $learner->get($i, '1618_emp_inc');

                $this->totalFunding[$i]['FM36_Disadv'] =
                    (float) ($this->totalFunding[$i]['FM36_Disadv'] ?? 0) + (float) $learner->get($i, 'FM36_Disadv');

                $this->totalFunding[$i]['als'] =
                    (float) ($this->totalFunding[$i]['als'] ?? 0) + (float) $learner->get($i, 'als');
                $fields = [
                    'on_program',
                    'balance',
                    'achievement',
                    'als',
                    '1618_prov_inc',
                    'FM36_Disadv',
                    '1618_emp_inc',
                    'framework_uplift_opp',
                    'framework_uplift_bal',
                    'framework_uplift_comp'
                ];

                $sum = 0;
                foreach ($fields as $field) {
                    $sum += (float) $learner->get($i, $field);
                }

                $this->totalFunding["$i"]['total'] = (float)$this->totalFunding["$i"]['total'] + (float)$sum;
                $this->totalFunding["$i"]['at_risk'] = (float)$this->totalFunding["$i"]['at_risk'] + (float)$learner->get($i, 'at_risk');

                if (empty($sqid)) {
                    $this->totalFunding["$i"]['profile'] = $profileamount;
                    $this->totalFunding["$i"]['PFR'] = $pframount;
                }

                // Traineeship1924NP Starts
                if (!isset($this->totalFundingTraineeship1924NP["$i"])) {
                    $this->totalFundingTraineeship1924NP[$i] = [
                        'on_program'   => '',
                        'balance'      => '',
                        'achievement'  => '',
                        'ach_profiled' => '',
                        'total'        => '',
                        'at_risk'      => '',
                    ];
                }
                //				pre($learner->get($i, 'on_program'));
                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                $this->totalFundingTraineeship1924NP["$i"]['on_program'] =
                    (float)$this->totalFundingTraineeship1924NP["$i"]['on_program'] + (float)$Traineeship1924NP_funding->get($i, 'on_program');

                $this->totalFundingTraineeship1924NP["$i"]['balance'] =
                    (float)$this->totalFundingTraineeship1924NP["$i"]['balance'] + (float)$Traineeship1924NP_funding->get($i, 'balance');
                $this->totalFundingTraineeship1924NP["$i"]['achievement'] =
                    (float)$this->totalFundingTraineeship1924NP["$i"]['achievement'] + (float)$Traineeship1924NP_funding->get($i, 'achievement');
                $this->totalFundingTraineeship1924NP["$i"]['ach_profiled'] =
                    (float)$this->totalFundingTraineeship1924NP["$i"]['ach_profiled'] + (float)$Traineeship1924NP_funding->get($i, 'achievement_predicted');
                $this->totalFundingTraineeship1924NP["$i"]['total'] =
                    (float)$this->totalFundingTraineeship1924NP["$i"]['total']
                    + (float)$Traineeship1924NP_funding->get($i, 'on_program')
                    + (float)$Traineeship1924NP_funding->get($i, 'balance')
                    + (float)$Traineeship1924NP_funding->get($i, 'achievement')
                    + (float)$Traineeship1924NP_funding->get($i, '1618_prov_inc');
                $this->totalFundingTraineeship1924NP["$i"]['at_risk'] =
                    (float)$this->totalFundingTraineeship1924NP["$i"]['at_risk'] + (float)$Traineeship1924NP_funding->get($i, 'at_risk');
                if (empty($sqid)) {
                    $this->totalFundingTraineeship1924NP["$i"]['profile'] = $profileamount;
                    $this->totalFundingTraineeship1924NP["$i"]['PFR'] = $pframount;
                }

                // Traineeship1924PMay17 Starts
                if (!isset($this->totalFundingTraineeship1924PMay17["$i"])) {
                    $this->totalFundingTraineeship1924PMay17[$i] = [
                        'on_program'   => '',
                        'balance'      => '',
                        'achievement'  => '',
                        'ach_profiled' => '',
                        'total'        => '',
                        'at_risk'      => '',
                    ];
                }
                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                $this->totalFundingTraineeship1924PMay17["$i"]['on_program'] =
                    (float)$this->totalFundingTraineeship1924PMay17["$i"]['on_program']
                    + (float)$Traineeship1924PMay17_funding->get($i, 'on_program');
                $this->totalFundingTraineeship1924PMay17["$i"]['balance'] =
                    (float)$this->totalFundingTraineeship1924PMay17["$i"]['balance']
                    + (float)$Traineeship1924PMay17_funding->get($i, 'balance');

                $this->totalFundingTraineeship1924PMay17["$i"]['achievement'] =
                    (float)$this->totalFundingTraineeship1924PMay17["$i"]['achievement']
                    + (float)$Traineeship1924PMay17_funding->get($i, 'achievement');
                $this->totalFundingTraineeship1924PMay17["$i"]['ach_profiled'] =
                    (float)$this->totalFundingTraineeship1924PMay17["$i"]['ach_profiled']
                    + (float)$Traineeship1924PMay17_funding->get($i, 'achievement_predicted');
                $this->totalFundingTraineeship1924PMay17["$i"]['total'] =
                    (float)$this->totalFundingTraineeship1924PMay17["$i"]['total']
                    + (float)$Traineeship1924PMay17_funding->get($i, 'on_program')
                    + (float)$Traineeship1924PMay17_funding->get($i, 'balance')
                    + (float)$Traineeship1924PMay17_funding->get($i, 'achievement');
                $this->totalFundingTraineeship1924PMay17["$i"]['at_risk'] =
                    (float)$this->totalFundingTraineeship1924PMay17["$i"]['at_risk']
                    + (float)$Traineeship1924PMay17_funding->get($i, 'at_risk');
                if (empty($sqid)) {
                    $this->totalFundingTraineeship1924PMay17["$i"]['profile'] = $profileamount;
                    $this->totalFundingTraineeship1924PMay17["$i"]['PFR'] = $pframount;
                }

                // AEBOtherLearningNP Starts
                if (!isset($this->totalFundingAEBOtherLearningNP[$i])) {
                    $this->totalFundingAEBOtherLearningNP[$i] = [
                        'on_program'   => 0,
                        'balance'      => 0,
                        'achievement'  => 0,
                        'ach_profiled' => 0,
                        'total'        => 0,
                        'at_risk'      => 0,
                    ];
                }

                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                $this->totalFundingAEBOtherLearningNP["$i"]['on_program'] =
                    (float)$this->totalFundingAEBOtherLearningNP["$i"]['on_program']
                    + (float)$AEBOtherLearningNP_funding->get($i, 'on_program');

                $this->totalFundingAEBOtherLearningNP["$i"]['balance'] =
                    (float)$this->totalFundingAEBOtherLearningNP["$i"]['balance']
                    + (float)$AEBOtherLearningNP_funding->get($i, 'balance');

                $this->totalFundingAEBOtherLearningNP["$i"]['achievement'] =
                    (float)$this->totalFundingAEBOtherLearningNP["$i"]['achievement']
                    + (float)$AEBOtherLearningNP_funding->get($i, 'achievement');

                $this->totalFundingAEBOtherLearningNP["$i"]['ach_profiled'] =
                    (float)$this->totalFundingAEBOtherLearningNP["$i"]['ach_profiled']
                    + (float)$AEBOtherLearningNP_funding->get($i, 'achievement_predicted');

                $this->totalFundingAEBOtherLearningNP["$i"]['total'] =
                    (float)$this->totalFundingAEBOtherLearningNP["$i"]['total']
                    + (float)$AEBOtherLearningNP_funding->get($i, 'on_program')
                    + (float)$AEBOtherLearningNP_funding->get($i, 'balance')
                    + (float)$AEBOtherLearningNP_funding->get($i, 'achievement');

                $this->totalFundingAEBOtherLearningNP["$i"]['at_risk'] =
                    (float)$this->totalFundingAEBOtherLearningNP["$i"]['at_risk']
                    + (float)$AEBOtherLearningNP_funding->get($i, 'at_risk');

                if (empty($sqid)) {
                    $this->totalFundingAEBOtherLearningNP["$i"]['profile'] = $profileamount;
                    $this->totalFundingAEBOtherLearningNP["$i"]['PFR'] = $pframount;
                }

                // AEBOtherLearningPNov17 Starts
                if (!isset($this->totalFundingAEBOtherLearningPNov17[$i])) {
                    $this->totalFundingAEBOtherLearningPNov17[$i] = [
                        'on_program'   => 0,
                        'balance'      => 0,
                        'achievement'  => 0,
                        'ach_profiled' => 0,
                        'total'        => 0,
                        'at_risk'      => 0,
                    ];
                }
                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                $this->totalFundingAEBOtherLearningPNov17["$i"]['on_program'] =
                    (float)$this->totalFundingAEBOtherLearningPNov17["$i"]['on_program']
                    + (float)$AEBOtherLearningPNov17_funding->get($i, 'on_program');

                $this->totalFundingAEBOtherLearningPNov17["$i"]['balance'] =
                    (float)$this->totalFundingAEBOtherLearningPNov17["$i"]['balance']
                    + (float)$AEBOtherLearningPNov17_funding->get($i, 'balance');

                $this->totalFundingAEBOtherLearningPNov17["$i"]['achievement'] =
                    (float)$this->totalFundingAEBOtherLearningPNov17["$i"]['achievement']
                    + (float)$AEBOtherLearningPNov17_funding->get($i, 'achievement');

                $this->totalFundingAEBOtherLearningPNov17["$i"]['ach_profiled'] =
                    (float)$this->totalFundingAEBOtherLearningPNov17["$i"]['ach_profiled']
                    + (float)$AEBOtherLearningPNov17_funding->get($i, 'achievement_predicted');

                $this->totalFundingAEBOtherLearningPNov17["$i"]['total'] =
                    (float)$this->totalFundingAEBOtherLearningPNov17["$i"]['total']
                    + (float)$AEBOtherLearningPNov17_funding->get($i, 'on_program')
                    + (float)$AEBOtherLearningPNov17_funding->get($i, 'balance')
                    + (float)$AEBOtherLearningPNov17_funding->get($i, 'achievement');

                $this->totalFundingAEBOtherLearningPNov17["$i"]['at_risk'] =
                    (float)$this->totalFundingAEBOtherLearningPNov17["$i"]['at_risk']
                    + (float)$AEBOtherLearningPNov17_funding->get($i, 'at_risk');

                if (empty($sqid)) {
                    $this->totalFundingAEBOtherLearningPNov17["$i"]['profile'] = $profileamount;
                    $this->totalFundingAEBOtherLearningPNov17["$i"]['PFR'] = $pframount;
                }

                // 16-18 Starts
                if (!isset($this->totalFunding1618Apps[$i])) {
                    $this->totalFunding1618Apps[$i] = [
                        'on_program'   => 0,
                        'balance'      => 0,
                        'achievement'  => 0,
                        'ach_profiled' => 0,
                        'total'        => 0,
                        'at_risk'      => 0,
                    ];
                }
                //				pre($learner->get($i, 'on_program'));
                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                //$this->totalFunding1618Apps["$i"]['adjusted'] += $apps1618_funding->get($i,'adjusted');
                $this->totalFunding1618Apps["$i"]['on_program'] =
                    (float)$this->totalFunding1618Apps["$i"]['on_program']
                    + (float)$apps1618_funding->get($i, 'on_program');

                $this->totalFunding1618Apps["$i"]['balance'] =
                    (float)$this->totalFunding1618Apps["$i"]['balance']
                    + (float)$apps1618_funding->get($i, 'balance');

                $this->totalFunding1618Apps["$i"]['achievement'] =
                    (float)$this->totalFunding1618Apps["$i"]['achievement']
                    + (float)$apps1618_funding->get($i, 'achievement');

                $this->totalFunding1618Apps["$i"]['ach_profiled'] =
                    (float)$this->totalFunding1618Apps["$i"]['ach_profiled']
                    + (float)$apps1618_funding->get($i, 'achievement_predicted');

                $this->totalFunding1618Apps["$i"]['total'] =
                    (float)$this->totalFunding1618Apps["$i"]['total']
                    + (float)$apps1618_funding->get($i, 'on_program')
                    + (float)$apps1618_funding->get($i, 'balance')
                    + (float)$apps1618_funding->get($i, 'achievement');

                $this->totalFunding1618Apps["$i"]['at_risk'] =
                    (float)$this->totalFunding1618Apps["$i"]['at_risk']
                    + (float)$apps1618_funding->get($i, 'at_risk');

                if (empty($sqid)) {
                    $this->totalFunding1618Apps["$i"]['profile'] = $profileamount;
                    $this->totalFunding1618Apps["$i"]['PFR'] = $pframount;
                }

                // 19-23 Starts
                if (!isset($this->totalFunding1923Apps[$i])) {
                    $this->totalFunding1923Apps[$i] = [
                        'on_program'   => 0,
                        'balance'      => 0,
                        'achievement'  => 0,
                        'ach_profiled' => 0,
                        'total'        => 0,
                        'at_risk'      => 0,
                    ];
                }
                //				pre($learner->get($i, 'on_program'));
                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                //$this->totalFunding1923Apps["$i"]['adjusted'] += $apps1923_funding->get($i,'adjusted');
                $this->totalFunding1923Apps["$i"]['on_program'] =
                    (float)$this->totalFunding1923Apps["$i"]['on_program']
                    + (float)$apps1923_funding->get($i, 'on_program');

                $this->totalFunding1923Apps["$i"]['balance'] =
                    (float)$this->totalFunding1923Apps["$i"]['balance']
                    + (float)$apps1923_funding->get($i, 'balance');

                $this->totalFunding1923Apps["$i"]['achievement'] =
                    (float)$this->totalFunding1923Apps["$i"]['achievement']
                    + (float)$apps1923_funding->get($i, 'achievement');

                $this->totalFunding1923Apps["$i"]['ach_profiled'] =
                    (float)$this->totalFunding1923Apps["$i"]['ach_profiled']
                    + (float)$apps1923_funding->get($i, 'achievement_predicted');

                $this->totalFunding1923Apps["$i"]['total'] =
                    (float)$this->totalFunding1923Apps["$i"]['total']
                    + (float)$apps1923_funding->get($i, 'on_program')
                    + (float)$apps1923_funding->get($i, 'balance')
                    + (float)$apps1923_funding->get($i, 'achievement');

                $this->totalFunding1923Apps["$i"]['at_risk'] =
                    (float)$this->totalFunding1923Apps["$i"]['at_risk']
                    + (float)$apps1923_funding->get($i, 'at_risk');

                if (empty($sqid)) {
                    $this->totalFunding1923Apps["$i"]['profile'] = $profileamount;
                    $this->totalFunding1923Apps["$i"]['PFR'] = $pframount;
                }

                // 24+ Starts
                if (!isset($this->totalFunding24Apps[$i])) {
                    $this->totalFunding24Apps[$i] = [
                        'on_program'   => 0,
                        'balance'      => 0,
                        'achievement'  => 0,
                        'ach_profiled' => 0,
                        'total'        => 0,
                        'at_risk'      => 0,
                    ];
                }

                //				pre($learner->get($i, 'on_program'));
                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                //$this->totalFunding24Apps["$i"]['adjusted'] += $apps24_funding->get($i,'adjusted');
                $this->totalFunding24Apps["$i"]['on_program'] =
                    (float)$this->totalFunding24Apps["$i"]['on_program']
                    + (float)$apps24_funding->get($i, 'on_program');

                $this->totalFunding24Apps["$i"]['balance'] =
                    (float)$this->totalFunding24Apps["$i"]['balance']
                    + (float)$apps24_funding->get($i, 'balance');

                $this->totalFunding24Apps["$i"]['achievement'] =
                    (float)$this->totalFunding24Apps["$i"]['achievement']
                    + (float)$apps24_funding->get($i, 'achievement');

                $this->totalFunding24Apps["$i"]['ach_profiled'] =
                    (float)$this->totalFunding24Apps["$i"]['ach_profiled']
                    + (float)$apps24_funding->get($i, 'achievement_predicted');

                $this->totalFunding24Apps["$i"]['total'] =
                    (float)$this->totalFunding24Apps["$i"]['total']
                    + (float)$apps24_funding->get($i, 'on_program')
                    + (float)$apps24_funding->get($i, 'balance')
                    + (float)$apps24_funding->get($i, 'achievement');

                $this->totalFunding24Apps["$i"]['at_risk'] =
                    (float)$this->totalFunding24Apps["$i"]['at_risk']
                    + (float)$apps24_funding->get($i, 'at_risk');

                if (empty($sqid)) {
                    $this->totalFunding24Apps["$i"]['profile'] = $profileamount;
                    $this->totalFunding24Apps["$i"]['PFR'] = $pframount;
                }

                // Apps1618LevyMay17 Starts
                if (!isset($this->totalFundingApps1618LevyMay17[$i])) {
                    $this->totalFundingApps1618LevyMay17[$i] = [
                        'on_program'   => 0,
                        'balance'      => 0,
                        'achievement'  => 0,
                        'ach_profiled' => 0,
                        'total'        => 0,
                        'at_risk'      => 0,
                    ];
                }

                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                $this->totalFundingApps1618LevyMay17["$i"]['on_program'] =
                    (float)$this->totalFundingApps1618LevyMay17["$i"]['on_program']
                    + (float)$Apps1618LevyMay17_funding->get($i, 'on_program');

                $this->totalFundingApps1618LevyMay17["$i"]['balance'] =
                    (float)$this->totalFundingApps1618LevyMay17["$i"]['balance']
                    + (float)$Apps1618LevyMay17_funding->get($i, 'balance');

                $this->totalFundingApps1618LevyMay17["$i"]['achievement'] =
                    (float)$this->totalFundingApps1618LevyMay17["$i"]['achievement']
                    + (float)$Apps1618LevyMay17_funding->get($i, 'achievement');

                $this->totalFundingApps1618LevyMay17["$i"]['ach_profiled'] =
                    (float)$this->totalFundingApps1618LevyMay17["$i"]['ach_profiled']
                    + (float)$Apps1618LevyMay17_funding->get($i, 'achievement_predicted');

                $this->totalFundingApps1618LevyMay17["$i"]['total'] =
                    (float)$this->totalFundingApps1618LevyMay17["$i"]['total']
                    + (float)$Apps1618LevyMay17_funding->get($i, 'on_program')
                    + (float)$Apps1618LevyMay17_funding->get($i, 'balance')
                    + (float)$Apps1618LevyMay17_funding->get($i, 'achievement');

                $this->totalFundingApps1618LevyMay17["$i"]['at_risk'] =
                    (float)$this->totalFundingApps1618LevyMay17["$i"]['at_risk']
                    + (float)$Apps1618LevyMay17_funding->get($i, 'at_risk');


                if (empty($sqid)) {
                    $this->totalFundingApps1618LevyMay17["$i"]['profile'] = $profileamount;
                    $this->totalFundingApps1618LevyMay17["$i"]['PFR'] = $pframount;
                }

                // Apps1618NLNPMay17 Starts
                if (!isset($this->totalFundingApps1618NLNPMay17[$i])) {
                    $this->totalFundingApps1618NLNPMay17[$i] = [
                        'on_program'   => 0,
                        'balance'      => 0,
                        'achievement'  => 0,
                        'ach_profiled' => 0,
                        'total'        => 0,
                        'at_risk'      => 0,
                    ];
                }

                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                $this->totalFundingApps1618NLNPMay17["$i"]['on_program'] =
                    (float)$this->totalFundingApps1618NLNPMay17["$i"]['on_program']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'on_program');

                $this->totalFundingApps1618NLNPMay17["$i"]['balance'] =
                    (float)$this->totalFundingApps1618NLNPMay17["$i"]['balance']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'balance');

                $this->totalFundingApps1618NLNPMay17["$i"]['achievement'] =
                    (float)$this->totalFundingApps1618NLNPMay17["$i"]['achievement']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'achievement');

                $this->totalFundingApps1618NLNPMay17["$i"]['ach_profiled'] =
                    (float)$this->totalFundingApps1618NLNPMay17["$i"]['ach_profiled']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'achievement_predicted');

                $this->totalFundingApps1618NLNPMay17["$i"]['total'] =
                    (float)$this->totalFundingApps1618NLNPMay17["$i"]['total']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'on_program')
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'balance')
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'achievement');

                $this->totalFundingApps1618NLNPMay17["$i"]['at_risk'] =
                    (float)$this->totalFundingApps1618NLNPMay17["$i"]['at_risk']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'at_risk');


                if (empty($sqid)) {
                    $this->totalFundingApps1618NLNPMay17["$i"]['profile'] = $profileamount;
                    $this->totalFundingApps1618NLNPMay17["$i"]['PFR'] = $pframount;
                }

                // Apps1618NLPMay17 Starts
                if (!isset($this->totalFundingApps1618NLPMay17["$i"])) {
                    $this->totalFundingApps1618NLPMay17["$i"] = array('on_program' => '', 'balance' => '', 'achievement' => '', 'ach_profiled' => '', 'total' => '', 'at_risk' => '');
                }
                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                $this->totalFundingApps1618NLPMay17["$i"]['on_program'] =
                    (float)$this->totalFundingApps1618NLPMay17["$i"]['on_program']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'on_program');

                $this->totalFundingApps1618NLPMay17["$i"]['balance'] =
                    (float)$this->totalFundingApps1618NLPMay17["$i"]['balance']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'balance');

                $this->totalFundingApps1618NLPMay17["$i"]['achievement'] =
                    (float)$this->totalFundingApps1618NLPMay17["$i"]['achievement']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'achievement');

                $this->totalFundingApps1618NLPMay17["$i"]['ach_profiled'] =
                    (float)$this->totalFundingApps1618NLPMay17["$i"]['ach_profiled']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'achievement_predicted');

                $this->totalFundingApps1618NLPMay17["$i"]['total'] =
                    (float)$this->totalFundingApps1618NLPMay17["$i"]['total']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'on_program')
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'balance')
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'achievement');

                $this->totalFundingApps1618NLPMay17["$i"]['at_risk'] =
                    (float)$this->totalFundingApps1618NLPMay17["$i"]['at_risk']
                    + (float)$Apps1618NLNPMay17_funding->get($i, 'at_risk');


                if (empty($sqid)) {
                    $this->totalFundingApps1618NLPMay17["$i"]['profile'] = $profileamount;
                    $this->totalFundingApps1618NLPMay17["$i"]['PFR'] = $pframount;
                }

                // Apps19LevyMay17 Starts
                if (!isset($this->totalFundingApps19LevyMay17[$i])) {
                    $this->totalFundingApps19LevyMay17[$i] = [
                        'on_program'   => 0,
                        'balance'      => 0,
                        'achievement'  => 0,
                        'ach_profiled' => 0,
                        'total'        => 0,
                        'at_risk'      => 0,
                    ];
                }

                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                $this->totalFundingApps19LevyMay17["$i"]['on_program'] =
                    (float)$this->totalFundingApps19LevyMay17["$i"]['on_program']
                    + (float)$Apps19LevyMay17_funding->get($i, 'on_program');

                $this->totalFundingApps19LevyMay17["$i"]['balance'] =
                    (float)$this->totalFundingApps19LevyMay17["$i"]['balance']
                    + (float)$Apps19LevyMay17_funding->get($i, 'balance');

                $this->totalFundingApps19LevyMay17["$i"]['achievement'] =
                    (float)$this->totalFundingApps19LevyMay17["$i"]['achievement']
                    + (float)$Apps19LevyMay17_funding->get($i, 'achievement');

                $this->totalFundingApps19LevyMay17["$i"]['ach_profiled'] =
                    (float)$this->totalFundingApps19LevyMay17["$i"]['ach_profiled']
                    + (float)$Apps19LevyMay17_funding->get($i, 'achievement_predicted');

                $this->totalFundingApps19LevyMay17["$i"]['total'] =
                    (float)$this->totalFundingApps19LevyMay17["$i"]['total']
                    + (float)$Apps19LevyMay17_funding->get($i, 'on_program')
                    + (float)$Apps19LevyMay17_funding->get($i, 'balance')
                    + (float)$Apps19LevyMay17_funding->get($i, 'achievement');

                $this->totalFundingApps19LevyMay17["$i"]['at_risk'] =
                    (float)$this->totalFundingApps19LevyMay17["$i"]['at_risk']
                    + (float)$Apps19LevyMay17_funding->get($i, 'at_risk');

                if (empty($sqid)) {
                    $this->totalFundingApps19LevyMay17["$i"]['profile'] = $profileamount;
                    $this->totalFundingApps19LevyMay17["$i"]['PFR'] = $pframount;
                }

                // Apps19NLNPMay17 Starts
                if (!isset($this->totalFundingApps19NLNPMay17[$i])) {
                    $this->totalFundingApps19NLNPMay17[$i] = [
                        'on_program'   => 0,
                        'balance'      => 0,
                        'achievement'  => 0,
                        'ach_profiled' => 0,
                        'total'        => 0,
                        'at_risk'      => 0,
                    ];
                }

                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                $this->totalFundingApps19NLNPMay17["$i"]['on_program'] =
                    (float)$this->totalFundingApps19NLNPMay17["$i"]['on_program']
                    + (float)$Apps19NLNPMay17_funding->get($i, 'on_program');

                $this->totalFundingApps19NLNPMay17["$i"]['balance'] =
                    (float)$this->totalFundingApps19NLNPMay17["$i"]['balance']
                    + (float)$Apps19NLNPMay17_funding->get($i, 'balance');

                $this->totalFundingApps19NLNPMay17["$i"]['achievement'] =
                    (float)$this->totalFundingApps19NLNPMay17["$i"]['achievement']
                    + (float)$Apps19NLNPMay17_funding->get($i, 'achievement');

                $this->totalFundingApps19NLNPMay17["$i"]['ach_profiled'] =
                    (float)$this->totalFundingApps19NLNPMay17["$i"]['ach_profiled']
                    + (float)$Apps19NLNPMay17_funding->get($i, 'achievement_predicted');

                $this->totalFundingApps19NLNPMay17["$i"]['total'] =
                    (float)$this->totalFundingApps19NLNPMay17["$i"]['total']
                    + (float)$Apps19NLNPMay17_funding->get($i, 'on_program')
                    + (float)$Apps19NLNPMay17_funding->get($i, 'balance')
                    + (float)$Apps19NLNPMay17_funding->get($i, 'achievement');

                $this->totalFundingApps19NLNPMay17["$i"]['at_risk'] =
                    (float)$this->totalFundingApps19NLNPMay17["$i"]['at_risk']
                    + (float)$Apps19NLNPMay17_funding->get($i, 'at_risk');


                if (empty($sqid)) {
                    $this->totalFundingApps19NLNPMay17["$i"]['profile'] = $profileamount;
                    $this->totalFundingApps19NLNPMay17["$i"]['PFR'] = $pframount;
                }

                // Apps19NLPMay17 Starts
                if (!isset($this->totalFundingApps19NLPMay17[$i])) {
                    $this->totalFundingApps19NLPMay17[$i] = [
                        'on_program'   => 0,
                        'balance'      => 0,
                        'achievement'  => 0,
                        'ach_profiled' => 0,
                        'total'        => 0,
                        'at_risk'      => 0,
                    ];
                }
                $submission = "W" . str_pad($i, 2, "0", STR_PAD_LEFT);
                $profileamount = DAO::getSingleValue($link, "select profile from lookup_profile_values where contract_id in ('$contracts') and submission = '$submission'");
                $pframount = DAO::getSingleValue($link, "select profile from lookup_pfr_values where contract_id in ('$contracts') and submission = '$submission'");
                $this->totalFundingApps19NLPMay17["$i"]['on_program'] =
                    (float)$this->totalFundingApps19NLPMay17["$i"]['on_program']
                    + (float)$Apps19NLPMay17_funding->get($i, 'on_program');

                $this->totalFundingApps19NLPMay17["$i"]['balance'] =
                    (float)$this->totalFundingApps19NLPMay17["$i"]['balance']
                    + (float)$Apps19NLPMay17_funding->get($i, 'balance');

                $this->totalFundingApps19NLPMay17["$i"]['achievement'] =
                    (float)$this->totalFundingApps19NLPMay17["$i"]['achievement']
                    + (float)$Apps19NLPMay17_funding->get($i, 'achievement');

                $this->totalFundingApps19NLPMay17["$i"]['ach_profiled'] =
                    (float)$this->totalFundingApps19NLPMay17["$i"]['ach_profiled']
                    + (float)$Apps19NLPMay17_funding->get($i, 'achievement_predicted');

                $this->totalFundingApps19NLPMay17["$i"]['total'] =
                    (float)$this->totalFundingApps19NLPMay17["$i"]['total']
                    + (float)$Apps19NLPMay17_funding->get($i, 'on_program')
                    + (float)$Apps19NLPMay17_funding->get($i, 'balance')
                    + (float)$Apps19NLPMay17_funding->get($i, 'achievement');

                $this->totalFundingApps19NLPMay17["$i"]['at_risk'] =
                    (float)$this->totalFundingApps19NLPMay17["$i"]['at_risk']
                    + (float)$Apps19NLPMay17_funding->get($i, 'at_risk');


                if (empty($sqid)) {
                    $this->totalFundingApps19NLPMay17["$i"]['profile'] = $profileamount;
                    $this->totalFundingApps19NLPMay17["$i"]['PFR'] = $pframount;
                }
            }
        }
        // rejig dataset for datamatrix display
        foreach ($this->totalFunding as $period => $data) {
            if (empty($sqid))
                if (DB_NAME == 'am_ligauk')
                    $this->data[] = array('period' => '<a href="/do.php?_action=funding_prediction&amp;contract=' . $contracts . '&amp;period=' . $period . '&amp;employer=' . $employer . '&amp;course=' . $course . '&amp;assessor=' . $assessor . '&amp;tutor=' . $tutor . '&amp;submission=' . $submissionp .  '">W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], '1618_prov_inc' => $data['1618_prov_inc'], '1618_emp_inc' => $data['1618_emp_inc'], 'FM36_Disadv' => $data['FM36_Disadv'], 'framework_uplift_opp' => $data['framework_uplift_opp'], 'framework_uplift_bal' => $data['framework_uplift_bal'], 'framework_uplift_comp' => $data['framework_uplift_comp'], 'ALS' => sprintf("%.2f", $data['als']), 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']), 'at_risk' => sprintf("%.2f", $data['at_risk']));
                else
                    $this->data[] = array('period' => '<a href="/do.php?_action=funding_prediction&amp;contract=' . $contracts . '&amp;period=' . $period . '&amp;employer=' . $employer . '&amp;course=' . $course . '&amp;assessor=' . $assessor . '&amp;tutor=' . $tutor . '&amp;submission=' . $submissionp .  '">W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], '1618_prov_inc' => $data['1618_prov_inc'], '1618_emp_inc' => $data['1618_emp_inc'], 'FM36_Disadv' => $data['FM36_Disadv'], 'framework_uplift_opp' => $data['framework_uplift_opp'], 'framework_uplift_bal' => $data['framework_uplift_bal'], 'framework_uplift_comp' => $data['framework_uplift_comp'], 'ALS' => sprintf("%.2f", $data['als']), 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']));
            else
                $this->data[] = array('period' => '<a href="/do.php?_action=funding_prediction&amp;contract=' . $contracts . '&amp;period=' . $period . '&amp;employer=' . $employer . '&amp;course=' . $course . '&amp;assessor=' . $assessor . '&amp;tutor=' . $tutor . '&amp;submission=' . $submissionp .  '">W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'total' => sprintf("%.2f", $data['total']));
        }

        if (!empty($sqid)) {
            $this->rowData = array_merge(array('contractDuration' => $learner->contractDuration, 'actualDuration' => $learner->actualDuration), $funding[0]);
        }

        // Shadow Funding Start
        foreach ($this->totalFundingTraineeship1924NP as $period => $data) {
            $this->dataTraineeship1924NP[] = array('period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']), 'at_risk' => sprintf("%.2f", $data['at_risk']));
        }

        foreach ($this->totalFundingTraineeship1924PMay17 as $period => $data) {
            $this->dataTraineeship1924PMay17[] = [
                'period'        => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>',
                'on_program'    => $data['on_program'],
                'balance'       => $data['balance'],
                'achievement'   => $data['achievement'],
                'ach_profiled'  => $data['ach_profiled'],
                'total'         => sprintf("%.2f", $data['total']),
                'profile'       => sprintf("%.2f", $data['profile']),
                'PFR'           => sprintf("%.2f", $data['PFR']),
                'at_risk'       => sprintf("%.2f", $data['at_risk']),
            ];
        }

        foreach ($this->totalFundingAEBOtherLearningNP as $period => $data) {
            $this->dataAEBOtherLearningNP[] = [
                'period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>',
                'on_program' => $data['on_program'],
                'balance' => $data['balance'],
                'achievement' => $data['achievement'],
                'ach_profiled' => $data['ach_profiled'],
                'total' => sprintf("%.2f", $data['total']),
                'profile' => sprintf("%.2f", $data['profile']),
                'PFR' => sprintf("%.2f", $data['PFR']),
                'at_risk' => sprintf("%.2f", $data['at_risk'])
            ];
        }


        foreach ($this->totalFundingAEBOtherLearningPNov17 as $period => $data) {
            $this->dataAEBOtherLearningPNov17[] = array(
                'period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>',
                'on_program' => $data['on_program'],
                'balance' => $data['balance'],
                'achievement' => $data['achievement'],
                'ach_profiled' => $data['ach_profiled'],
                'total' => sprintf("%.2f", $data['total']),
                'profile' => sprintf("%.2f", $data['profile']),
                'PFR' => sprintf("%.2f", $data['PFR']),
                'at_risk' => sprintf("%.2f", $data['at_risk'])
            );
        }

        foreach ($this->totalFunding1618Apps as $period => $data) {
            $this->data1618Apps[] = array('period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']), 'at_risk' => sprintf("%.2f", $data['at_risk']));
        }

        foreach ($this->totalFunding1923Apps as $period => $data) {
            $this->data1923Apps[] = array('period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']), 'at_risk' => sprintf("%.2f", $data['at_risk']));
        }

        foreach ($this->totalFunding24Apps as $period => $data) {
            $this->data24Apps[] = array('period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']), 'at_risk' => sprintf("%.2f", $data['at_risk']));
        }

        foreach ($this->totalFundingApps1618LevyMay17 as $period => $data) {
            $this->dataApps1618LevyMay17[] = array('period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']), 'at_risk' => sprintf("%.2f", $data['at_risk']));
        }

        foreach ($this->totalFundingApps1618NLNPMay17 as $period => $data) {
            $this->dataApps1618NLNPMay17[] = array('period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']), 'at_risk' => sprintf("%.2f", $data['at_risk']));
        }

        foreach ($this->totalFundingApps1618NLPMay17 as $period => $data) {
            $this->dataApps1618NLPMay17[] = array('period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']), 'at_risk' => sprintf("%.2f", $data['at_risk']));
        }

        foreach ($this->totalFundingApps19LevyMay17 as $period => $data) {
            $this->dataApps19LevyMay17[] = array('period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']), 'at_risk' => sprintf("%.2f", $data['at_risk']));
        }

        foreach ($this->totalFundingApps19NLNPMay17 as $period => $data) {
            $this->dataApps19NLNPMay17[] = array('period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']), 'at_risk' => sprintf("%.2f", $data['at_risk']));
        }

        foreach ($this->totalFundingApps19NLPMay17 as $period => $data) {
            $this->dataApps19NLPMay17[] = array('period' => 'W' . str_pad($period, 2, '0', STR_PAD_LEFT) . '</a>', 'on_program' => $data['on_program'], 'balance' => $data['balance'], 'achievement' => $data['achievement'], 'ach_profiled' => $data['ach_profiled'], 'total' => sprintf("%.2f", $data['total']), 'profile' => sprintf("%.2f", $data['profile']), 'PFR' => sprintf("%.2f", $data['PFR']), 'at_risk' => sprintf("%.2f", $data['at_risk']));
        }

        $this->ttotal = format_money(0);
    }

    private function defaultFundingRow(): array
    {
        return [
            'on_program'   => '',
            'balance'      => '',
            'achievement'  => '',
            'ach_profiled' => '',
            'total'        => '',
            'at_risk'      => '',
        ];
    }

    public function getCoreValues()
    {
        if (!empty($this->rowData)) {
            $data = $columns = array();
            foreach ($this->rowData as $field => $v) {
                if (!is_integer($field)) {
                    $columns[] = $field;
                    $data[0]["$field"] = $v;
                }
            }

            return $data;
        }
        return null;
    }

    public function getFinalData()
    {
        return $this->data;
    }

    public function toBarChart($link, $profiled = false)
    {

        // fix for no date

        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->data as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;

        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        
        die;
        if ($course != 0)

            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }
    public function toBarChartShadow($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->dataShadow as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChart1924TraineeshipNP($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->dataTraineeship1924NP as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChart1924TraineeshipPNov17($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->dataTraineeship1924PMay17 as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChartAEBOtherNP($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->dataAEBOtherLearningNP as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChartAEBOtherPNov17($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->dataAEBOtherLearningPNov17 as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChart1618Apps($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->data1618Apps as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChart1923Apps($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->data1923Apps as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChart24Apps($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->data24Apps as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChart1618AppsLevyMay17($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->dataApps1618LevyMay17 as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChart1618AppsNLNPMay17($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->dataApps1618NLNPMay17 as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChart1618AppsNLPMay17($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->dataApps1618NLPMay17 as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChart19AppsLevyMay17($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->dataApps19LevyMay17 as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChart19AppsNLNPMay17($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->dataApps19NLNPMay17 as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }

    public function toBarChart19AppsNLPMay17($link, $profiled = false)
    {
        // fix for no date
        // Firstly, scale down the values to multiples of 1000
        $scaledFunding = array();
        $t = 0;
        foreach ($this->dataApps19NLPMay17 as $period => $data) {
            $t += $data['total'];
            $scaledFunding["$period"] = sprintf("%.2f", ($data['total'] / 1000));
        }

        if ($t <= 0) {
            return '';
        }

        $labels = array();
        if (DB_NAME == 'am_crackerjack')
            $predictor_duration = 12;
        else
            $predictor_duration = 24;
        for ($i = 1; $i <= $predictor_duration; $i++) {
            $labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $test_data = array_values($scaledFunding);

        // Here's where we call the chart, and return the encoded chart data
        $course = $_REQUEST['course'];
        if ($course != 0)
            $title = "course " . DAO::getSingleValue($link, "select title from courses where id = $course");
        else
            $title = "contract ";

        return '<img src="/img.php?url=' . rawurlencode('http://chart.apis.google.com/chart?chtt=' . urlencode('Funding predictions for ' . $title) . '&cht=bvs&amp;chs=480x300&amp;chbh=27&amp;chd=' . $this->chart_data($test_data, $labels, $link, $profiled)) . '" />';
    }
}


function format_money2($val)
{
    return '&pound;' . format_money($val);
}