<?php

class FundingPredictionPeriod extends FundingCore
{
	private $learners = array();
	private $period = 0;

	function __construct($link, $contractInfo, $period, $course = 0, $assessor = '', $employer = 0, $submissionp = '', $tutor = '', $tr_id = '', $filter_emp_b_code = '', $apply_proportion = 1)
	{
		ini_set('memory_limit','1024M');
		$this->period = $period;

		// find out the submission periods for the year(s) we're looking at
		parent::__construct($link, $contractInfo);

		$contractIds = explode(",", $contractInfo);
		$contractYear = DAO::getSingleValue($link, "select contract_year from contracts where id = '$contractIds[0]'");
		$class = 'FundingCalculator_' . $contractYear;
		require_once('years/' . $class . '.php');
		$gfunding = new $class($link, $contractInfo);
		$addition = (!empty($course) ? " AND courses.id = '" . intval($course) . "'" : "");
		$addition .= (!empty($assessor) ? " AND assessors.id = '" . $assessor . "'" : "");
		$addition .= (!empty($tutor) ? " AND tutors.id= '" . $tutor . "'" : "");
		$addition .= (!empty($tr_id) ? " AND ilr.tr_id= '" . $tr_id . "'" : "");
		$addition .= (!empty($employer) ? " AND employers.id = '" . $employer . "'" : "");
		if($filter_emp_b_code != '' && (DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo"))
		{
			$addition .= (!empty($filter_emp_b_code) ? " AND learners.employer_business_code= '" . $filter_emp_b_code . "'" : "");
		}
		$addition .= " and submission = '" . $submissionp . "'";
		$funding = $gfunding->getData($link, ',sq.title as qualification_title', null, $addition);
		$otherData = $gfunding->getOtherData($link, $funding);

		$achievement_period="";
		$aim_achievement_period="";
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

		//pre($funding);
		foreach($funding AS $key => $data)
		{

			// calculate the amount of funding the student is entitled to
			$availableFunding = $this->calculate_funding($data);

			$learner = new LearnerFunding($link, $data['total_funding'], $data, $this->pl,"All");

			if(array_key_exists($data['home_postcode'],$home_postcodes))
				if($data['FundModel']==35)
					$disup = (double)$home_postcodes[$data['home_postcode']]['SFA'];
				else
					$disup = (double)$home_postcodes[$data['home_postcode']]['EFA'];
			else
				$disup = 1;

			if(array_key_exists($data['postcode'],$postcodes))
				if($data['FundModel']==35)
					$area = (double)$postcodes[$data['postcode']]['SFA'];
				else
					$area = (double)$postcodes[$data['postcode']]['EFA'];
			else
				$area = 1;

			if($data['age']>18)
				if(array_key_exists($data['edrs'],$large_employer))
					$discount = (double)$large_employer[$data['edrs']];
				else
					$discount = 1;
			else
				$discount = 1;

			if($data['fully_funded']=='2')// || $data['age']>18)
				if(array_key_exists($data['qualid'], $ksarray))
					if($ksarray[$data['qualid']]=="NVQ/GNVQ Key Skills Unit" || $ksarray[$data['qualid']]=="Functional Skills")
						$fee_proportion = 0.825;
					else
						$fee_proportion = 0.5;
				else
					$fee_proportion = 1;
			else
				$fee_proportion = 1;


			// get the Achivemenet payment back to 100%
			if($data['funding_remaining_weight']=='0' || $data['funding_remaining_weight']=='')
				$prior = 1;
			else
				$prior = $data['funding_remaining_weight'];

			//pre($data);
			$LearnStartDate = $data['learner_start_date'];
			$LearnPlanEndDate = $data['learner_target_end_date'];
			$LearnActEndDate = $data['learner_end_date'];
			$LearnAimRef = $data['qualid'];
			$no_of_planned_instalments = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$LearnPlanEndDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");
			$last_day = DAO::getSingleValue($link, "SELECT LAST_DAY('$LearnPlanEndDate')='$LearnPlanEndDate'");
			if($last_day)
				$no_of_planned_instalments++;
            if($data['FundModel']=='81' or $data['FundModel']=='36')
                $no_of_planned_instalments--;

            if($data['L03']=='000000002209' && $data['qualid']=='60342687')
            {
                //pre($data);
                //pre($remaining_amount);
                //pre($threshold_eligible);
                //pre("Rate " . $Rate201314 . "\n\rTotal Funding " . $Total_Funding . "\n\r Held Back " . $held_back . "\n\r Remaining Amount " . $remaining_amount . "\n\r Planned Instalments " . $no_of_planned_instalments . "\n\r Actual OPPs this year " . $no_of_actual_instalments_this_year . "\n\r Balance Amount " . $balance . "\n\r Balance Period " . $balance_period . "\n\rAchieved " . $achieved . "\n\rAchievement Perdio " . $achievement_period . "\n\rMonthly OPP " . $monthly_instalment . "\n\rAchievement amount " . $achievement_amount . "\n\rNo of instalments remaining " . $no_of_instalments_remaining . "\n\rOPP this year " . $opp_this_year . "\n\r Start Month " . $start_month . "\n\rArea Cost " . $area . "\n\rAmount remaining for OPPs " . $amount_remaining_for_opps .  "\n\rAmount for this year " . $amount_for_this_year);
            }

			// No of Pre-Transational instalments
			$first_day_of_current_funding_period = new Date($contractYear.'-08-01');
			$achieved = false;

			$PED = new Date(DATE::toMedium($LearnPlanEndDate));
			$LSD = new Date(DATE::toMedium($LearnStartDate));

			if(Date::isDate($LearnActEndDate))
				if($data['FundModel']==25)
					if($LSD->before($contractYear.'-08-01'))
						$the_learning_delivery_actual_number_of_days_in_learning = DAO::getSingleValue($link, "SELECT DATEDIFF('$LearnActEndDate','$contractYear-08-01')+1;");
					else
						$the_learning_delivery_actual_number_of_days_in_learning = DAO::getSingleValue($link, "SELECT DATEDIFF('$LearnActEndDate','$LearnStartDate')+1;");
				else
					$the_learning_delivery_actual_number_of_days_in_learning = DAO::getSingleValue($link, "SELECT DATEDIFF('$LearnActEndDate','$LearnStartDate')+1;");
			else
				$the_learning_delivery_actual_number_of_days_in_learning = 43;
			$the_learning_delivery_planned_number_of_days_in_learning = DAO::getSingleValue($link, "SELECT DATEDIFF('$LearnPlanEndDate','$LearnStartDate')+1;");
			if($the_learning_delivery_planned_number_of_days_in_learning>=1 && $the_learning_delivery_planned_number_of_days_in_learning<14)
				$threshold_days = 1;
			elseif($the_learning_delivery_planned_number_of_days_in_learning>=14 && $the_learning_delivery_planned_number_of_days_in_learning<168)
				$threshold_days = 14;
			elseif($the_learning_delivery_planned_number_of_days_in_learning>=168)
				$threshold_days = 42;
            else
                $threshold_days = 0;
			if($the_learning_delivery_actual_number_of_days_in_learning>=$threshold_days)
				$threshold_eligible = true;
			else
				$threshold_eligible = false;

			$the_learning_delivery_aim_type = $data['aim_type'];
			$PwayCode = $data['PwayCode'];
			$FworkCode = $data['FworkCode'];

			if(($the_learning_delivery_aim_type == '16-18 Apprenticeships' || $the_learning_delivery_aim_type == '19-23 Apprenticeships' || $the_learning_delivery_aim_type == '24+ Apprenticeships') && $LearnAimRef!='ZPROG001')
				$the_learning_delivery_is_an_apprenticeship_component_aim = true;
			else
				$the_learning_delivery_is_an_apprenticeship_component_aim = false;

            $the_learning_delivery_framework_component_type_code = DAO::getSingleValue($link, "SELECT FrameworkComponentType FROM lars201617.Core_LARS_FrameworkAims WHERE LearnAimRef = '$LearnAimRef' AND FworkCode = '$FworkCode' AND PwayCode = '$PwayCode'");
			if($the_learning_delivery_is_an_apprenticeship_component_aim && ($the_learning_delivery_framework_component_type_code=='001' || $the_learning_delivery_framework_component_type_code=='003'))
				$the_learning_delivery_is_an_apprenticeship_competency_aim = true;
			else
				$the_learning_delivery_is_an_apprenticeship_competency_aim = false;
			if($the_learning_delivery_is_an_apprenticeship_component_aim && $the_learning_delivery_framework_component_type_code=='002')
				$the_learning_delivery_is_an_apprenticeship_knowledge_aim = true;
			else
				$the_learning_delivery_is_an_apprenticeship_knowledge_aim = false;

			//$no_of_actual_instalments_this_year = 12;
			if($data['achieved']==1)
				$achieved = true;
			if($first_day_of_current_funding_period->after($LearnStartDate)) // Transitional
			{
                // Check if Actual end date is found
				if(Date::isDate($LearnActEndDate))
				{
					$aim_achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$LearnActEndDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contractYear-08-01','%Y%m'))");
					// check if actual end date is in previous year
					if($first_day_of_current_funding_period->after($LearnActEndDate) || $first_day_of_current_funding_period->after($LearnPlanEndDate))
					{
						$pre_transitional_instalments = $no_of_planned_instalments;
						$no_of_actual_instalments_this_year = 0;
					}
					else
					{
						$pre_transitional_instalments = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$contractYear-07-31'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");
						if($PED->before($LearnActEndDate))
						{
							$no_of_actual_instalments_this_year = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(LAST_DAY('$LearnPlanEndDate'),'%Y%m'),DATE_FORMAT('$contractYear-08-01','%Y%m'))");
							$last_day = DAO::getSingleValue($link, "SELECT '$LearnPlanEndDate' = LAST_DAY('$LearnPlanEndDate')");
							if($last_day)
								$no_of_actual_instalments_this_year++;
						}
						else
						{
							$no_of_actual_instalments_this_year = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(LAST_DAY('$LearnActEndDate'),'%Y%m'),DATE_FORMAT('$contractYear-08-01','%Y%m'))");
						}
					}
					$FrameworkAchievementDate = $data['framework_achivement_date'];
					if(Date::isDate($FrameworkAchievementDate) && ($the_learning_delivery_is_an_apprenticeship_competency_aim || $the_learning_delivery_is_an_apprenticeship_knowledge_aim))
					{
						$FrameworkAchievementDate = Date::toMySQL($FrameworkAchievementDate);
						$achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$FrameworkAchievementDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contractYear-08-01','%Y%m'))");
					}
					else
					{
						$achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$LearnActEndDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contractYear-08-01','%Y%m'))");
					}
				}
				else
				{
					$pre_transitional_instalments = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$contractYear-07-31'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");
					$no_of_actual_instalments_this_year = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(LAST_DAY('$LearnPlanEndDate'),'%Y%m'),DATE_FORMAT('$contractYear-08-01','%Y%m'))");
					$achievement_profiled_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY(IF('$LearnPlanEndDate'>CURDATE(),'$LearnPlanEndDate',CURDATE())), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contractYear-08-01','%Y%m'))");

					if($pre_transitional_instalments>$no_of_planned_instalments)
						$pre_transitional_instalments = $no_of_planned_instalments;

                }

				if($data['FundModel']!='36' && $pre_transitional_instalments>0 && $pre_transitional_instalments<$no_of_planned_instalments)
					$pre_transitional_instalments++;


			}
			else
			{
				$pre_transitional_instalments = 0;
				$no_of_actual_instalments_this_year = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(LAST_DAY('$LearnPlanEndDate'),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");
				if(Date::isDate($LearnActEndDate))
				{
					$aim_achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$LearnActEndDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");
					if($PED->after($LearnActEndDate))
						$no_of_actual_instalments_this_year = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(LAST_DAY('$LearnActEndDate'),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");

					$FrameworkAchievementDate = $data['framework_achivement_date'];
					if(Date::isDate($FrameworkAchievementDate) && ($the_learning_delivery_is_an_apprenticeship_competency_aim || $the_learning_delivery_is_an_apprenticeship_knowledge_aim))
					{
						$FrameworkAchievementDate = Date::toMySQL($FrameworkAchievementDate);
						//$achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$FrameworkAchievementDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$LearnStartDate','%Y%m'))");
						$achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$FrameworkAchievementDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contractYear-08-01','%Y%m'))");
					}
					else
					{
						$aim_achievement_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY('$LearnActEndDate'), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contractYear-08-01','%Y%m'))");
						$achievement_period = $aim_achievement_period;
					}
				}
				else
				{
					$achievement_profiled_period = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT(DATE_ADD(LAST_DAY(IF('$LearnPlanEndDate'>CURDATE(),'$LearnPlanEndDate',CURDATE())), INTERVAL 1 DAY),'%Y%m'),DATE_FORMAT('$contractYear-08-01','%Y%m'))");
				}
                if($data['FundModel']!='36')
				    $no_of_actual_instalments_this_year++;
			}

            if($data['L03']=='000000002050' && $data['qualid']=='ZPROG001')
            {
                //pre($data);
            }


			// Check if actual end date was the last day of month then increment actual by 1
			if(Date::isDate($LearnActEndDate))
			{
				if($PED->before($LearnActEndDate))
				{
					if($last_day)
					{
						$no_of_actual_instalments_this_year++;
					}
				}
				else
				{
					$last_day = DAO::getSingleValue($link, "SELECT LAST_DAY('$LearnActEndDate')='$LearnActEndDate'");
					if($last_day)
						$no_of_actual_instalments_this_year++;
				}

			}
			else
			{
				if($last_day)
					$no_of_actual_instalments_this_year++;
			}
			//
			// Actuals cannot be less than 2 for non-transitional learners
			if(!$first_day_of_current_funding_period->after($LearnStartDate)) // Transitional
				if($no_of_actual_instalments_this_year==1 && $data['FundModel']!='36')
					$no_of_actual_instalments_this_year = 2;

			if($data['aim_type']=='Classroom' || $data['aim_type']=='Workplace')
			{
				$Rate201314 = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201516.`Core_LARS_Funding` WHERE FundingCategory = 'Matrix' AND LearnAimRef = '$LearnAimRef' ORDER By EffectiveFrom Desc LIMIT 0,1;");
			}
			else
			{
				//if(SOURCE_BLYTHE_VALLEY)
				$Rate201314 = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201718.Core_LARS_Funding WHERE FundingCategory = 'APP_ACT_COST' AND LearnAimRef = '$LearnAimRef' AND '$LearnStartDate' >= EffectiveFrom AND ('$LearnStartDate' <= EffectiveTo OR EffectiveTo IS NULL) LIMIT 0,1;");
                if($Rate201314=='')
                    $Rate201314 = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201718.Core_LARS_Funding WHERE FundingCategory = 'APP_ACT_COST' AND LearnAimRef = '$LearnAimRef' ORDER By EffectiveFrom Desc  LIMIT 0,1;");
			}
			$UnCappedRate201314 = DAO::getSingleValue($link, "SELECT RateUnWeighted FROM lars201718.`Core_LARS_Funding` WHERE FundingCategory = 'Matrix' AND LearnAimRef = '$LearnAimRef' ORDER By EffectiveFrom Desc  LIMIT 0,1;");


            if($data['FundModel']=='36')
            {
                if($data['qualid']=='ZPROG001')
                    if($data['LevyCap']=="" or $data['TotalNegotiatedPrice']<$data['LevyCap'])
                        $Rate201314 = $data['TotalNegotiatedPrice'];
                    else
                        $Rate201314 = $data['LevyCap'];
                else
                    $Rate201314 = 0;
            }

            //if($data['L03']=='000000001418' and $data['qualid']='6013608X')
              //  pre($Rate201314);

            if($data['programme_type']==2)
				$the_learning_delivery_is_an_apprenticeship = true;
			else
				$the_learning_delivery_is_an_apprenticeship = false;

            $the_learning_delivery_framework_common_component_code = DAO::getSingleValue($link, "SELECT FrameworkCommonComponent FROM lars201718.Core_LARS_LearningDelivery WHERE LearnAimRef = '$LearnAimRef'");

			if(($the_learning_delivery_framework_common_component_code=='10' || $the_learning_delivery_framework_common_component_code=='11' || $the_learning_delivery_framework_common_component_code=='12') && $the_learning_delivery_is_an_apprenticeship)
				$the_learning_delivery_is_an_apprenticeship_functional_skills_aim = true;
			else
				$the_learning_delivery_is_an_apprenticeship_functional_skills_aim = false;

			if($data['aim_type']=='16-18 Apprenticeships')
				$App_age_factor = 1.0723;
			else
				$App_age_factor = 1;

            if($data['FundModel']=='36')
            {
                $Total_Funding = $Rate201314;
                $FWTotalFunding = isset($data['1618FrameworkUplift']) ? $data['1618FrameworkUplift'] : 0;
            }
            else
            {
                $Total_Funding = $Rate201314 * $App_age_factor * $disup * $discount * $area;
                $FWTotalFunding = 0;
            }

			if($the_learning_delivery_is_an_apprenticeship_functional_skills_aim && $the_learning_delivery_aim_type == '16-18 Apprenticeships')
				$Total_Funding = $Total_Funding * 0.606061;

			// Check if Achievement payment was held back
			if($data['fully_funded']==2)
				if($the_learning_delivery_aim_type == '19-23 Apprenticeships' || $the_learning_delivery_aim_type == '24+ Apprenticeships' || $the_learning_delivery_aim_type == '16-18 Apprenticeships')
					$Total_Funding = $Total_Funding / 2;
				else
					$Total_Funding = $Total_Funding - ($UnCappedRate201314/2);

            if(($data['FundModel']=='81' or $data['FundModel']=='36') && $the_learning_delivery_is_an_apprenticeship_functional_skills_aim && $data['total_funding']==471)
                $Total_Funding = 471;

            if($apply_proportion!=0)
			    $Total_Funding = $Total_Funding * $data['proportion'] / 100;

			if($the_learning_delivery_aim_type == '24+ Apprenticeships' and $data['FundModel']!='36')
				$Total_Funding = $Total_Funding * 0.80;


            if($data['L03']=='000000002058' && $data['qualid']=='5010987X')
            {
                //pre($data);
            }

			$balance = 0;
			$held_back = 0;
			if($LSD->before('01/08/2013'))
				$held_back_rate = "0.25";
			else
				$held_back_rate = "0.20";
			if($LSD->before('01/08/2013')) // If learner started in before 2013
			{
				if($the_learning_delivery_is_an_apprenticeship_competency_aim || $data['aim_type']=='Classroom' || $data['aim_type']=='Workplace')
				{
					if($achievement_period>0 || !$achieved)
						$held_back = $Total_Funding * $held_back_rate;
					else
						$held_back = 0;

					$remaining_amount = $Total_Funding * (1 - $held_back_rate);
				}
				else
				{
					$held_back = 0;
					$remaining_amount = $Total_Funding * 0.80;
				}
			}
			else
			{
				$remaining_amount = $Total_Funding * 0.80;
			}

            $fw_remaining_amount = $FWTotalFunding * 0.80;



			$prior = trim($data['prior_learning']);
			if((int)$prior>0)
				$remaining_amount = $remaining_amount /100 * (int)$prior;
	
			if($no_of_planned_instalments == 0)
				$no_of_planned_instalments = 1;

            if($data['FundModel']=='36' and $the_learning_delivery_is_an_apprenticeship_functional_skills_aim)
				if((int)$prior>0)
					$remaining_amount = 724 /100 * (int)$prior;
				else
					$remaining_amount = 724;

			$monthly_instalment = $remaining_amount/$no_of_planned_instalments;
            $fw_monthly_instalment = $fw_remaining_amount/$no_of_planned_instalments;

			// If transitional learner
			if($LSD->before('01/08/2013') && $contractYear>='2014')
			{
				$amount_paid_pre_transition = $monthly_instalment * ($pre_transitional_instalments-12);
				$amount_remained_post_2013 = $remaining_amount - $amount_paid_pre_transition + ($held_back * 0.20);
				$monthly_instalment_in_2013 = $amount_remained_post_2013 / ($no_of_planned_instalments - ($pre_transitional_instalments-12));
				$paid_in_2013 = $monthly_instalment_in_2013 * 12;
				$amount_for_this_year = $amount_remained_post_2013 - $paid_in_2013;
                $fw_amount_for_this_year = 0;
			}
			else
			{
				$amount_paid_pre_transition = $monthly_instalment * $pre_transitional_instalments;
				$amount_for_this_year = $remaining_amount - $amount_paid_pre_transition + $held_back;
                $fw_amount_paid_pre_transition = $fw_monthly_instalment * $pre_transitional_instalments;
                $fw_amount_for_this_year = $fw_remaining_amount - $fw_amount_paid_pre_transition;
			}

			if($data['FundModel']==25)
				if($threshold_eligible)
					$amount_for_this_year = $data['EFA_Amount'];
				else
					$amount_for_this_year = 0;

			$achievement_amount = $Total_Funding * 0.2;
            $fw_achievement_amount = $FWTotalFunding * 0.2;

			if($data['restart']!=1)
				if($prior!='')
					$achievement_amount = $achievement_amount /100 * (int)$prior;


			$main_aiim = DAO::getSingleValue($link, "SELECT FrameworkComponentType FROM lars201516.`Core_LARS_FrameworkAims` WHERE LearnAimRef = '$LearnAimRef' ORDER BY EffectiveFrom DESC LIMIT 0,1;");
			if(($data['aim_type']=='16-18 Apprenticeships' || $data['aim_type']=='19-23 Apprenticeships' || $data['aim_type']=='24+ Apprenticeships') && $LSD->before('01/08/2013'))
			{
				// If it is transitional then nullify achievement payment for non-main aims
				if($main_aiim!='1' && $main_aiim!='2')
					$achievement_amount = 0;
			}

            if(($data['aim_type']=='16-18 Apprenticeships' || $data['aim_type']=='19-23 Apprenticeships' || $data['aim_type']=='24+ Apprenticeships') && $LSD->after('30/04/2017') && $the_learning_delivery_is_an_apprenticeship_functional_skills_aim)
            {
                $achievement_amount = 0;
            }

            $amount_remaining_for_opps = $amount_for_this_year;
            $fw_amount_remaining_for_opps = $fw_amount_for_this_year;

			$no_of_instalments_remaining = $no_of_planned_instalments - $pre_transitional_instalments;

			if($first_day_of_current_funding_period->after($LearnStartDate))
			{
				if($no_of_instalments_remaining>0)
                {
                    $opp_this_year = $amount_remaining_for_opps / $no_of_instalments_remaining;
                    $fw_opp_this_year = $fw_amount_remaining_for_opps / $no_of_instalments_remaining;
                }
				else
                {
                    $opp_this_year = 0;
                    $fw_opp_this_year = 0;
                }
			}
			else
			{
				$opp_this_year = $monthly_instalment;
                $fw_opp_this_year = $fw_monthly_instalment;
			}

			$start_month = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT('$LearnStartDate','%Y%m'),DATE_FORMAT('$contractYear-07-31','%Y%m'));");

			$no_of_instalments_this_year = $no_of_planned_instalments - $pre_transitional_instalments;
			if($no_of_actual_instalments_this_year<$no_of_instalments_remaining)
				$no_of_instalments_this_year = $no_of_actual_instalments_this_year;

			// Balancing Payment
			if($no_of_actual_instalments_this_year==0 && $amount_remaining_for_opps>0)
            {
                $balance = $amount_remaining_for_opps;
                $fw_balance = $fw_amount_remaining_for_opps;
            }
			elseif($no_of_actual_instalments_this_year < $no_of_instalments_remaining)
            {
                $balance = $opp_this_year * ($no_of_instalments_remaining - $no_of_actual_instalments_this_year);
                $fw_balance = $fw_opp_this_year * ($no_of_instalments_remaining - $no_of_actual_instalments_this_year);
            }

			$index_paid = 1;



			$no_of_instalments_this_year = $no_of_planned_instalments - $pre_transitional_instalments;
			if($no_of_actual_instalments_this_year<$no_of_instalments_remaining)
				$no_of_instalments_this_year = $no_of_actual_instalments_this_year;




            // Balancing Payment
			if($aim_achievement_period>0)
				$balance_period = $aim_achievement_period;
			else
				$balance_period = 1;

			// Achivement Period Planned
			if($achievement_period<$submissionp)
				$achievement_period_planned = $submissionp;
			else
				$achievement_period_planned = $achievement_period;



			// EFA Installments
			if($data['FundModel']==25)
			{
				// Calculate EFA Start Date
				if($LSD->before($contractYear.'-08-01'))
					$EFA_Start_Date = $contractYear.'-08-01';
				else
					$EFA_Start_Date = $LSD->formatMySQL();

				if(Date::isDate($LearnActEndDate))
				{
					if($PED->after($LearnActEndDate))
						$EFA_End_Date = $LearnActEndDate;
					else
						$EFA_End_Date = $PED->formatMySQL();
				}
				else
				{
					$EFA_End_Date = $PED->formatMySQL();
				}

				$EFA_Instalments = DAO::getSingleValue($link, "SELECT PERIOD_DIFF(DATE_FORMAT('$EFA_End_Date','%Y%m'),DATE_FORMAT('$EFA_Start_Date','%Y%m'))");
				$EFA_Instalments++;
			}
			if($data['FundModel']=='25')
			{
				for($i = 1; $i <= 24; $i++)
				{
					$learner->set('on_program',$i,0);
					$learner->set('balance',$i,0);
					$learner->set('achievement',$i,0);
				}

				$EFA_Start_Date = new Date($EFA_Start_Date);
				$EFA_End_Date = new Date($EFA_End_Date);
				if($contractYear==2014)
				{
					if($EFA_Start_Date->before('2014-09-01') && $EFA_End_Date->after('2014-07-31'))
						$learner->set('on_program',1,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2014-10-01') && $EFA_End_Date->after('2014-08-31'))
						$learner->set('on_program',2,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2014-11-01') && $EFA_End_Date->after('2014-09-30'))
						$learner->set('on_program',3,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2014-12-01') && $EFA_End_Date->after('2014-10-31'))
						$learner->set('on_program',4,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2015-01-01') && $EFA_End_Date->after('2014-11-30'))
						$learner->set('on_program',5,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2015-02-01') && $EFA_End_Date->after('2014-12-31'))
						$learner->set('on_program',6,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2015-03-01') && $EFA_End_Date->after('2015-01-31'))
						$learner->set('on_program',7,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2015-04-01') && $EFA_End_Date->after('2015-02-28'))
						$learner->set('on_program',8,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2015-05-01') && $EFA_End_Date->after('2015-03-31'))
						$learner->set('on_program',9,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2015-06-01') && $EFA_End_Date->after('2015-04-30'))
						$learner->set('on_program',10,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2015-07-01') && $EFA_End_Date->after('2015-05-31'))
						$learner->set('on_program',11,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2015-08-01') && $EFA_End_Date->after('2015-06-30'))
						$learner->set('on_program',12,$amount_for_this_year/$EFA_Instalments);

				}
				elseif($contractYear==2015)
				{
					if($EFA_Start_Date->before('2015-09-01') && $EFA_End_Date->after('2015-07-31'))
						$learner->set('on_program',1,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2015-10-01') && $EFA_End_Date->after('2015-08-31'))
						$learner->set('on_program',2,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2015-11-01') && $EFA_End_Date->after('2015-09-30'))
						$learner->set('on_program',3,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2015-12-01') && $EFA_End_Date->after('2015-10-31'))
						$learner->set('on_program',4,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2016-01-01') && $EFA_End_Date->after('2015-11-30'))
						$learner->set('on_program',5,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2016-02-01') && $EFA_End_Date->after('2015-12-31'))
						$learner->set('on_program',6,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2016-03-01') && $EFA_End_Date->after('2016-01-31'))
						$learner->set('on_program',7,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2016-04-01') && $EFA_End_Date->after('2016-02-28'))
						$learner->set('on_program',8,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2016-05-01') && $EFA_End_Date->after('2016-03-31'))
						$learner->set('on_program',9,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2016-06-01') && $EFA_End_Date->after('2016-04-30'))
						$learner->set('on_program',10,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2016-07-01') && $EFA_End_Date->after('2016-05-31'))
						$learner->set('on_program',11,$amount_for_this_year/$EFA_Instalments);
					if($EFA_Start_Date->before('2016-08-01') && $EFA_End_Date->after('2016-06-30'))
						$learner->set('on_program',12,$amount_for_this_year/$EFA_Instalments);

				}
                elseif($contractYear==2016)
                {
                    if($EFA_Start_Date->before('2016-09-01') && $EFA_End_Date->after('2016-07-31'))
                        $learner->set('on_program',1,$amount_for_this_year/$EFA_Instalments);
                    if($EFA_Start_Date->before('2016-10-01') && $EFA_End_Date->after('2016-08-31'))
                        $learner->set('on_program',2,$amount_for_this_year/$EFA_Instalments);
                    if($EFA_Start_Date->before('2016-11-01') && $EFA_End_Date->after('2016-09-30'))
                        $learner->set('on_program',3,$amount_for_this_year/$EFA_Instalments);
                    if($EFA_Start_Date->before('2016-12-01') && $EFA_End_Date->after('2016-10-31'))
                        $learner->set('on_program',4,$amount_for_this_year/$EFA_Instalments);
                    if($EFA_Start_Date->before('2017-01-01') && $EFA_End_Date->after('2016-11-30'))
                        $learner->set('on_program',5,$amount_for_this_year/$EFA_Instalments);
                    if($EFA_Start_Date->before('2017-02-01') && $EFA_End_Date->after('2016-12-31'))
                        $learner->set('on_program',6,$amount_for_this_year/$EFA_Instalments);
                    if($EFA_Start_Date->before('2017-03-01') && $EFA_End_Date->after('2017-01-31'))
                        $learner->set('on_program',7,$amount_for_this_year/$EFA_Instalments);
                    if($EFA_Start_Date->before('2017-04-01') && $EFA_End_Date->after('2017-02-28'))
                        $learner->set('on_program',8,$amount_for_this_year/$EFA_Instalments);
                    if($EFA_Start_Date->before('2017-05-01') && $EFA_End_Date->after('2017-03-31'))
                        $learner->set('on_program',9,$amount_for_this_year/$EFA_Instalments);
                    if($EFA_Start_Date->before('2017-06-01') && $EFA_End_Date->after('2017-04-30'))
                        $learner->set('on_program',10,$amount_for_this_year/$EFA_Instalments);
                    if($EFA_Start_Date->before('2017-07-01') && $EFA_End_Date->after('2017-05-31'))
                        $learner->set('on_program',11,$amount_for_this_year/$EFA_Instalments);
                    if($EFA_Start_Date->before('2017-08-01') && $EFA_End_Date->after('2017-06-30'))
                        $learner->set('on_program',12,$amount_for_this_year/$EFA_Instalments);
                }
			}

            if($data['L03']=='000000001805' && $data['qualid']=='60342687')
            {
                //pre($no_of_instalments_this_year);
                //pre($remaining_amount);
                //pre($threshold_eligible);
                //pre("Rate " . $Rate201314 . "\n\rTotal Funding " . $Total_Funding . "\n\r Held Back " . $held_back . "\n\r Remaining Amount " . $remaining_amount . "\n\r Planned Instalments " . $no_of_planned_instalments . "\n\r Actual OPPs this year " . $no_of_actual_instalments_this_year . "\n\r Balance Amount " . $balance . "\n\r Balance Period " . $balance_period . "\n\rAchieved " . $achieved . "\n\rAchievement Perdio " . $achievement_period . "\n\rMonthly OPP " . $monthly_instalment . "\n\rAchievement amount " . $achievement_amount . "\n\rNo of instalments remaining " . $no_of_instalments_remaining . "\n\rOPP this year " . $opp_this_year . "\n\r Start Month " . $start_month . "\n\rArea Cost " . $area . "\n\rAmount remaining for OPPs " . $amount_remaining_for_opps .  "\n\rAmount for this year " . $amount_for_this_year);
                //pre($data);
            }



			if($data['FundModel']!='25')
				for($i = 1; $i <= 24; $i++)
				{
					$learner->set('achievement',$i,0);
					if($data['aim_type']=='16-18 Apprenticeships' || $data['aim_type']=='19-23 Apprenticeships' || $data['aim_type']=='24+ Apprenticeships' || $data['aim_type']=='Classroom' || $data['aim_type']=='Workplace')
					{
                        if($data['aim_type']=='16-18 Apprenticeships' and $data['FundModel']=='36')
                        {
                            if(isset($data['TrailblazerFunding']['1618ProvIncentive'][$i]) and $data['TrailblazerFunding']['1618ProvIncentive'][$i]==500)
                                $learner->set('1618_prov_inc',$i,500);
                            if(isset($data['TrailblazerFunding']['1618EmpIncentive'][$i]) and $data['TrailblazerFunding']['1618EmpIncentive'][$i]==500)
                                $learner->set('1618_emp_inc',$i,500);
                        }
                        // Disadvantage Payment FM36
                        if($data['FundModel']=='36')
                        {
                            if(isset($data['DisadvantagePayment']['DisadvantagePayment'][$i]) and $data['DisadvantagePayment']['DisadvantagePayment'][$i]>0)
                                $learner->set('FM36_Disadv',$i,$data['DisadvantagePayment']['DisadvantagePayment'][$i]);
                        }

                        if($threshold_eligible || $data['aim_achieved'])
						{
							// Check if it should pay this month
							if($i>=$start_month)
							{
	
								// Only when enough instalments have not been paid
								if($no_of_planned_instalments==1 && $no_of_instalments_this_year<=1 && $i==$start_month)
								{
									$learner->set('on_program',$i,$opp_this_year);
                                    $learner->set('framework_uplift_opp',$i,$fw_opp_this_year);
									$index_paid++;
								}
								elseif($index_paid<=$no_of_instalments_this_year)
								{
									if($i==$start_month  && $no_of_instalments_remaining>1)
									{
										if($data['FundModel']=='36')
                                        {
                                            $learner->set('on_program',$i,($opp_this_year));
                                            $learner->set('framework_uplift_opp',$i,($fw_opp_this_year));
                                            $index_paid++;
                                        }
                                        else
                                        {
                                            $learner->set('on_program',$i,($opp_this_year*2));
                                            $learner->set('framework_uplift_opp',$i,($fw_opp_this_year*2));
                                            $index_paid+=2;
                                        }
									}
									else
									{
										//if($data['L03']=='000000002209' && $data['qualid']=='60342687')
										//pre($opp_this_year);

										$learner->set('on_program',$i,$opp_this_year);
                                        $learner->set('framework_uplift_opp',$i,$fw_opp_this_year);
										$index_paid++;
									}									
								}	
								else
								{
									$learner->set('on_program',$i,0);
                                    $learner->set('framework_uplift_opp',$i,0);
								}
							}
							else
							{
								$learner->set('on_program',$i,0);
                                $learner->set('framework_uplift_opp',$i,0);
							}

                            if($data['L03']=='000000015947' && $data['qualid']=='ZPROG001')
                            {
                                //pre($data);
                            }

                        }
						else
						{
							$learner->set('on_program',$i,0);
                            $learner->set('framework_uplift_opp',$i,0);
						}

						if($i==$balance_period && $balance>0 && ($achieved || $data['aim_achieved']))
						{
							$learner->set('balance',$i,$balance);
                            $learner->set('framework_uplift_bal',$i,$fw_balance);
						}
						else
						{
							$learner->set('balance',$i,0);
                            $learner->set('framework_uplift_bal',$i,0);
						}


						if(
							(
								($main_aiim=='1' || $main_aiim=='2' || $main_aiim=='3')
								&& $data['framework_achieved'] && $LSD->after('2013/08/01')
							)
							||
							(
                                ($data['aim_type']!='16-18 Apprenticeships' && $data['aim_type']!='19-23 Apprenticeships' && $data['aim_type']!='24+ Apprenticeships')
                                && $data['aim_achieved']
							)
                            ||
                            ($data['aim_type']=='16-18 Apprenticeships' || $data['aim_type']=='19-23 Apprenticeships' || $data['aim_type']=='24+ Apprenticeships') && ($data['aim_achieved'] && $LSD->after('2015/08/01')) || ($data['framework_achieved'] && $LSD->after('2013/08/01'))
                            )
						{
							if($i==$achievement_period)
							{
								$learner->set('achievement',$i,$achievement_amount);
                                $learner->set('framework_uplift_comp',$i,$fw_achievement_amount);
								$learner->set('achievement_predicted',$i,$achievement_amount);
							}
						}
						//else
						{
							if($i==$achievement_profiled_period && $data['continuing'])
							{
								$learner->set('achievement_predicted',$i,$achievement_amount);
							}
						}
					}
				}

			$data['disadvantage_uplift'] = $disup;
			$data['area_cost'] = $area;


			$gt = 0;
			$ach_pre = 0;
			for( $i = 1; $i<=24; $i++ ) {
				$gt += ($learner->get($i, 'on_program') + $learner->get($i, 'balance') + $learner->get($i, 'achievement'));
				$ach_pre += $learner->get($i,'achievement_predicted');
			}

			if($data['L03']=='000000002377' && $data['qualid']=='ZPROG001')
				pre($opp_this_year);


            // Display the aims with funding only
			if( $gt>0 || $ach_pre > 0 ) {
				if( $this->period<25 ) {
					// rebuild this into a nicer data structure that we can work with
					$this->learners[] = array(
						//'name' => '<a href="/do.php?_action=funding_prediction&amp;contract=' . $data['contract_id'] . '&amp;sq=' . $data['auto_id'] . '">' . ucwords(strtolower($data['name'])) . '</a>'
						'name' =>  ucwords(strtolower($data['name']))
					,'L03' => $data['L03']
					,'uln' => $data['uln']
					,'at_risk' => isset($data['at_risk'])?$data['at_risk']:''
					,'provider_name' => $data['provider_name']
					,'course_name' => $data['course_name']
					,'employer_name' =>$data['employer_name']
					,'qualification_title' => $data['qualid'] . ' ' . $data['qualification_title']
					,'assessor' => $data['assessor']
					,'tutor' => $data['tutor']
					,'ProvSpecLearnMon A' => isset($data['l42a'])?$data['l42a']:''
					,'ProvSpecLearnMon B' => isset($data['l42b'])?$data['l42b']:''
					,'learner_start_date' => date('d/m/Y', strtotime($data['learner_start_date']))
					,'learner_target_end_date' => date('d/m/Y', strtotime($data['learner_target_end_date']))
					,'learner_end_date' => (date('d/m/Y', strtotime($data['learner_end_date']))=='01/01/1970'?'':date('d/m/Y', strtotime($data['learner_end_date'])))
					,'entry_end_date' => (date('d/m/Y', strtotime($data['entry_end_date']))=='01/01/1970'?'':date('d/m/Y', strtotime($data['entry_end_date'])))
					,'disadvantage_uplift' => $data['disadvantage_uplift']
					,'area_cost' => $data['area_cost']
                    ,'funding_line_type' => $data['new_aim_type']
						//			,'P1_adj' => sprintf("%.2f",($learner->get(1, 'adjusted')))
					,'OPP' => sprintf("%.2f",($learner->get($this->period, 'on_program')))
					,'bal' => sprintf("%.2f",($learner->get($this->period, 'balance')))
					,'ach' => sprintf("%.2f",($learner->get($this->period, 'achievement')))
					,'ach_profiled' => sprintf("%.2f",($learner->get($this->period,'achievement_predicted')))
                    ,'1618_prov_inc' => sprintf("%.2f",($learner->get($this->period,'1618_prov_inc')))
                    ,'1618_emp_inc' => sprintf("%.2f",($learner->get($this->period,'1618_emp_inc')))
                    ,'FM36_Disadv' => sprintf("%.2f",($learner->get($this->period,'FM36_Disadv')))
					,'ALS' => sprintf("%.2f",($learner->get($this->period,'als')))
					,'total' => sprintf("%.2f",$learner->get($this->period, 'on_program') + $learner->get($this->period, 'balance') + $learner->get($this->period, 'achievement') + $learner->get($this->period, 'als')+ $learner->get($this->period, '1618_prov_inc')+ $learner->get($this->period, '1618_emp_inc')+ $learner->get($this->period, 'FM36_Disadv'))
					);

				}
				else {
					// rebuild this into a nicer data structure that we can work with
                    $ts_opp = Array();
                    $ts_bal = Array();
                    $em_opp = Array();
                    $em_bal = Array();
                    if(($data['FundModel']=='81' or $data['FundModel']=='36') && $the_learning_delivery_is_an_apprenticeship_functional_skills_aim)
                    {
                        for($ind=1; $ind<=24; $ind++)
                        {
                            $ts_opp[$ind] = 0;
                            $ts_bal[$ind] = 0;
                            $em_opp[$ind] = sprintf("%.2f",($learner->get($ind, 'on_program')));
                            $em_bal[$ind] = sprintf("%.2f",($learner->get($ind, 'balance')));
                        }
                    }
                    else
                    {
                        for($ind=1; $ind<=24; $ind++)
                        {
                            $ts_opp[$ind] = sprintf("%.2f",($learner->get($ind, 'on_program')));
                            $ts_bal[$ind] = sprintf("%.2f",($learner->get($ind, 'balance')));
                            $em_opp[$ind] = 0;
                            $em_bal[$ind] = 0;
                        }
                    }

                    $this->learners[] = array(
						//'name' => '<a href="/do.php?_action=funding_prediction&amp;contract=' . $data['contract_id'] . '&amp;sq=' . $data['auto_id'] . '">' . ucwords(strtolower($data['name'])) . '</a>'
						'name' =>  ucwords(strtolower($data['name']))
					,'L03' => $data['L03']
					,'uln' => $data['uln']
					,'at_risk' => isset($data['at_risk'])?$data['at_risk']:''
					,'provider_name' => (isset($data['provider_name']) ? $data['provider_name'] : "")
					,'course_name' => (isset($data['course_name']) ? $data['course_name'] : "")
					,'employer_name' => (isset($data['employer_name']) ? $data['employer_name'] : "")
					,'qualification_title' => $data['qualid'] . ' ' . $data['qualification_title']
						// added in a check for the setting of the assessor value.
					,'assessor' => (isset($data['assessor']) ? $data['assessor'] : "")
					,'tutor' => (isset($data['tutor']) ? $data['tutor'] : "")
					,'ProvSpecLearnMon A' => isset($data['l42a'])?$data['l42a']:''
					,'ProvSpecLearnMon B' => isset($data['l42b'])?$data['l42b']:''
					,'learner_start_date' => date('d/m/Y', strtotime($data['learner_start_date']))
					,'learner_target_end_date' => date('d/m/Y', strtotime($data['learner_target_end_date']))
					,'learner_end_date' => (date('d/m/Y', strtotime($data['learner_end_date']))=='01/01/1970'?'':date('d/m/Y', strtotime($data['learner_end_date'])))
					,'entry_end_date' => (date('d/m/Y', strtotime($data['entry_end_date']))=='01/01/1970'?'':date('d/m/Y', strtotime($data['entry_end_date'])))
					,'disadvantage_uplift' => $data['disadvantage_uplift']
					,'area_cost' => $data['area_cost']
						//			,'P1_adj' => sprintf("%.2f",($learner->get(1, 'adjusted')))
					,'P1_OPP' => $ts_opp[1]
					,'P1_bal' => $ts_bal[1]
					,'P1_ach' => sprintf("%.2f",($learner->get(1, 'achievement')))
					,'P1_ach_p' => sprintf("%.2f",($learner->get(1,'achievement_predicted')))
                    ,'P1_EM_OPP' => $em_opp[1]
                    ,'P1_EM_Bal' => $em_bal[1]
                    ,'P1_1618_Pro_Inc' => sprintf("%.2f",($learner->get(1,'1618_prov_inc')))
                    ,'P1_1618_Emp_Inc' => sprintf("%.2f",($learner->get(1,'1618_emp_inc')))
                    ,'P1_FM36_Disadv' => sprintf("%.2f",($learner->get(1,'FM36_Disadv')))
                    ,'P1_ALS' => sprintf("%.2f",($learner->get(1,'als')))
                    ,'P1_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(1,'framework_uplift_opp')))
                    ,'P1_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(1,'framework_uplift_bal')))
                    ,'P1_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(1,'framework_uplift_comp')))
					,'P1_total' => sprintf("%.2f",$learner->get(1, 'on_program') + $learner->get(1, 'balance') + $learner->get(1, 'achievement')+ $learner->get(1, '1618_prov_inc')+ $learner->get(1, '1618_emp_inc')+ $learner->get(1, 'FM36_Disadv')+ $learner->get(1, 'als'))

                    ,'P2_OPP' => $ts_opp[2]
                    ,'P2_bal' => $ts_bal[2]
					,'P2_ach' => sprintf("%.2f",($learner->get(2, 'achievement')))
					,'P2_ach_p' => sprintf("%.2f",($learner->get(2,'achievement_predicted')))
                    ,'P2_EM_OPP' => $em_opp[2]
                    ,'P2_EM_Bal' => $em_bal[2]
                    ,'P2_1618_Pro_Inc' => sprintf("%.2f",($learner->get(2,'1618_prov_inc')))
                    ,'P2_1618_Emp_Inc' => sprintf("%.2f",($learner->get(2,'1618_emp_inc')))
                    ,'P2_FM36_Disadv' => sprintf("%.2f",($learner->get(2,'FM36_Disadv')))
                    ,'P2_ALS' => sprintf("%.2f",($learner->get(2,'als')))
                    ,'P2_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(2,'framework_uplift_opp')))
                    ,'P2_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(2,'framework_uplift_bal')))
                    ,'P2_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(2,'framework_uplift_comp')))
					,'P2_total' => sprintf("%.2f",$learner->get(2, 'on_program') + $learner->get(2, 'balance') + $learner->get(2, 'achievement')+ $learner->get(2, '1618_prov_inc')+ $learner->get(2, '1618_emp_inc')+ $learner->get(2, 'FM36_Disadv')+ $learner->get(2, 'als'))

                    ,'P3_OPP' => $ts_opp[3]
                    ,'P3_bal' => $ts_bal[3]
					,'P3_ach' => sprintf("%.2f",($learner->get(3, 'achievement')))
					,'P3_ach_p' => sprintf("%.2f",($learner->get(3,'achievement_predicted')))
                    ,'P3_EM_OPP' => $em_opp[3]
                    ,'P3_EM_Bal' => $em_bal[3]
                    ,'P3_1618_Pro_Inc' => sprintf("%.2f",($learner->get(3,'1618_prov_inc')))
                    ,'P3_1618_Emp_Inc' => sprintf("%.2f",($learner->get(3,'1618_emp_inc')))
                    ,'P3_FM36_Disadv' => sprintf("%.2f",($learner->get(3,'FM36_Disadv')))
                    ,'P3_ALS' => sprintf("%.2f",($learner->get(3,'als')))
                    ,'P3_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(3,'framework_uplift_opp')))
                    ,'P3_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(3,'framework_uplift_bal')))
                    ,'P3_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(3,'framework_uplift_comp')))
					,'P3_total' => sprintf("%.2f",$learner->get(3, 'on_program') + $learner->get(3, 'balance') + $learner->get(3, 'achievement')+ $learner->get(3, '1618_prov_inc')+ $learner->get(3, '1618_emp_inc')+ $learner->get(3, 'FM36_Disadv')+ $learner->get(3, 'als'))
						//			,'P4_adj' => sprintf("%.2f",($learner->get(4, 'adjusted')))

                    ,'P4_OPP' => $ts_opp[4]
                    ,'P4_bal' => $ts_bal[4]
					,'P4_ach' => sprintf("%.2f",($learner->get(4, 'achievement')))
					,'P4_ach_p' => sprintf("%.2f",($learner->get(4,'achievement_predicted')))
                    ,'P4_EM_OPP' => $em_opp[4]
                    ,'P4_EM_Bal' => $em_bal[4]
                    ,'P4_1618_Pro_Inc' => sprintf("%.2f",($learner->get(4,'1618_prov_inc')))
                    ,'P4_1618_Emp_Inc' => sprintf("%.2f",($learner->get(4,'1618_emp_inc')))
                    ,'P4_FM36_Disadv' => sprintf("%.2f",($learner->get(4,'FM36_Disadv')))
                    ,'P4_ALS' => sprintf("%.2f",($learner->get(4,'als')))
                    ,'P4_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(4,'framework_uplift_opp')))
                    ,'P4_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(4,'framework_uplift_bal')))
                    ,'P4_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(4,'framework_uplift_comp')))
					,'P4_total' => sprintf("%.2f",$learner->get(4, 'on_program') + $learner->get(4, 'balance') + $learner->get(4, 'achievement')+ $learner->get(4, '1618_prov_inc')+ $learner->get(4, '1618_emp_inc')+ $learner->get(4, 'FM36_Disadv')+ $learner->get(4, 'als'))
						//			,'P5_adj' => sprintf("%.2f",($learner->get(5, 'adjusted')))

                    ,'P5_OPP' => $ts_opp[5]
                    ,'P5_bal' => $ts_bal[5]
					,'P5_ach' => sprintf("%.2f",($learner->get(5, 'achievement')))
					,'P5_ach_p' => sprintf("%.2f",($learner->get(5,'achievement_predicted')))
                    ,'P5_EM_OPP' => $em_opp[5]
                    ,'P5_EM_Bal' => $em_bal[5]
                    ,'P5_1618_Pro_Inc' => sprintf("%.2f",($learner->get(5,'1618_prov_inc')))
                    ,'P5_1618_Emp_Inc' => sprintf("%.2f",($learner->get(5,'1618_emp_inc')))
                    ,'P5_FM36_Disadv' => sprintf("%.2f",($learner->get(5,'FM36_Disadv')))
                    ,'P5_ALS' => sprintf("%.2f",($learner->get(5,'als')))
                    ,'P5_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(5,'framework_uplift_opp')))
                    ,'P5_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(5,'framework_uplift_bal')))
                    ,'P5_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(5,'framework_uplift_comp')))
					,'P5_total' => sprintf("%.2f",$learner->get(5, 'on_program') + $learner->get(5, 'balance') + $learner->get(5, 'achievement')+ $learner->get(5, '1618_prov_inc')+ $learner->get(5, '1618_emp_inc')+ $learner->get(5, 'FM36_Disadv')+ $learner->get(5, 'als'))
						//			,'P6_adj' => sprintf("%.2f",($learner->get(6, 'adjusted')))

                    ,'P6_OPP' => $ts_opp[6]
                    ,'P6_bal' => $ts_bal[6]
					,'P6_ach' => sprintf("%.2f",($learner->get(6, 'achievement')))
					,'P6_ach_p' => sprintf("%.2f",($learner->get(6,'achievement_predicted')))
                    ,'P6_EM_OPP' => $em_opp[6]
                    ,'P6_EM_Bal' => $em_bal[6]
                    ,'P6_1618_Pro_Inc' => sprintf("%.2f",($learner->get(6,'1618_prov_inc')))
                    ,'P6_1618_Emp_Inc' => sprintf("%.2f",($learner->get(6,'1618_emp_inc')))
                    ,'P6_FM36_Disadv' => sprintf("%.2f",($learner->get(6,'FM36_Disadv')))
                    ,'P6_ALS' => sprintf("%.2f",($learner->get(6,'als')))
                    ,'P6_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(6,'framework_uplift_opp')))
                    ,'P6_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(6,'framework_uplift_bal')))
                    ,'P6_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(6,'framework_uplift_comp')))
					,'P6_total' => sprintf("%.2f",$learner->get(6, 'on_program') + $learner->get(6, 'balance') + $learner->get(6, 'achievement')+ $learner->get(6, '1618_prov_inc')+ $learner->get(6, '1618_emp_inc')+ $learner->get(6, 'FM36_Disadv')+ $learner->get(6, 'als'))
						//			,'P7_adj' => sprintf("%.2f",($learner->get(7, 'adjusted')))

                    ,'P7_OPP' => $ts_opp[7]
                    ,'P7_bal' => $ts_bal[7]
					,'P7_ach' => sprintf("%.2f",($learner->get(7, 'achievement')))
					,'P7_ach_p' => sprintf("%.2f",($learner->get(7,'achievement_predicted')))
                    ,'P7_EM_OPP' => $em_opp[7]
                    ,'P7_EM_Bal' => $em_bal[7]
                    ,'P7_1618_Pro_Inc' => sprintf("%.2f",($learner->get(7,'1618_prov_inc')))
                    ,'P7_1618_Emp_Inc' => sprintf("%.2f",($learner->get(7,'1618_emp_inc')))
                    ,'P7_FM36_Disadv' => sprintf("%.2f",($learner->get(7,'FM36_Disadv')))
                    ,'P7_ALS' => sprintf("%.2f",($learner->get(7,'als')))
                    ,'P7_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(7,'framework_uplift_opp')))
                    ,'P7_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(7,'framework_uplift_bal')))
                    ,'P7_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(7,'framework_uplift_comp')))
					,'P7_total' => sprintf("%.2f",$learner->get(7, 'on_program') + $learner->get(7, 'balance') + $learner->get(7, 'achievement')+ $learner->get(7, '1618_prov_inc')+ $learner->get(7, '1618_emp_inc')+ $learner->get(7, 'FM36_Disadv')+ $learner->get(7, 'als'))
						//			,'P8_adj' => sprintf("%.2f",($learner->get(8, 'adjusted')))

                    ,'P8_OPP' => $ts_opp[8]
                    ,'P8_bal' => $ts_bal[8]
					,'P8_ach' => sprintf("%.2f",($learner->get(8, 'achievement')))
					,'P8_ach_p' => sprintf("%.2f",($learner->get(8,'achievement_predicted')))
                    ,'P8_EM_OPP' => $em_opp[8]
                    ,'P8_EM_Bal' => $em_bal[8]
                    ,'P8_1618_Pro_Inc' => sprintf("%.2f",($learner->get(8,'1618_prov_inc')))
                    ,'P8_1618_Emp_Inc' => sprintf("%.2f",($learner->get(8,'1618_emp_inc')))
                    ,'P8_FM36_Disadv' => sprintf("%.2f",($learner->get(8,'FM36_Disadv')))
                    ,'P8_ALS' => sprintf("%.2f",($learner->get(8,'als')))
                    ,'P8_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(8,'framework_uplift_opp')))
                    ,'P8_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(8,'framework_uplift_bal')))
                    ,'P8_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(8,'framework_uplift_comp')))
					,'P8_total' => sprintf("%.2f",$learner->get(8, 'on_program') + $learner->get(8, 'balance') + $learner->get(8, 'achievement')+ $learner->get(8, '1618_prov_inc')+ $learner->get(8, '1618_emp_inc')+ $learner->get(8, 'FM36_Disadv')+ $learner->get(8, 'als'))
						///			,'P9_adj' => sprintf("%.2f",($learner->get(9, 'adjusted')))

                    ,'P9_OPP' => $ts_opp[9]
                    ,'P9_bal' => $ts_bal[9]
					,'P9_ach' => sprintf("%.2f",($learner->get(9, 'achievement')))
					,'P9_ach_p' => sprintf("%.2f",($learner->get(9,'achievement_predicted')))
                    ,'P9_EM_OPP' => $em_opp[9]
                    ,'P9_EM_Bal' => $em_bal[9]
                    ,'P9_1618_Pro_Inc' => sprintf("%.2f",($learner->get(9,'1618_prov_inc')))
                    ,'P9_1618_Emp_Inc' => sprintf("%.2f",($learner->get(9,'1618_emp_inc')))
                    ,'P9_FM36_Disadv' => sprintf("%.2f",($learner->get(9,'FM36_Disadv')))
                    ,'P9_ALS' => sprintf("%.2f",($learner->get(9,'als')))
                    ,'P9_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(9,'framework_uplift_opp')))
                    ,'P9_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(9,'framework_uplift_bal')))
                    ,'P9_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(9,'framework_uplift_comp')))
					,'P9_total' => sprintf("%.2f",$learner->get(9, 'on_program') + $learner->get(9, 'balance') + $learner->get(9, 'achievement')+ $learner->get(9, '1618_prov_inc')+ $learner->get(9, '1618_emp_inc')+ $learner->get(9, 'FM36_Disadv')+ $learner->get(9, 'als'))
						//			,'P10_adj' => sprintf("%.2f",($learner->get(10, 'adjusted')))

                    ,'P10_OPP' => $ts_opp[10]
                    ,'P10_bal' => $ts_bal[10]
					,'P10_ach' => sprintf("%.2f",($learner->get(10, 'achievement')))
					,'P10_ach_p' => sprintf("%.2f",($learner->get(10,'achievement_predicted')))
                    ,'P10_EM_OPP' => $em_opp[10]
                    ,'P10_EM_Bal' => $em_bal[10]
                    ,'P10_1618_Pro_Inc' => sprintf("%.2f",($learner->get(10,'1618_prov_inc')))
                    ,'P10_1618_Emp_Inc' => sprintf("%.2f",($learner->get(10,'1618_emp_inc')))
                    ,'P10_FM36_Disadv' => sprintf("%.2f",($learner->get(10,'FM36_Disadv')))
                    ,'P10_ALS' => sprintf("%.2f",($learner->get(10,'als')))
                    ,'P10_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(10,'framework_uplift_opp')))
                    ,'P10_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(10,'framework_uplift_bal')))
                    ,'P10_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(10,'framework_uplift_comp')))
					,'P10_total' => sprintf("%.2f",$learner->get(10, 'on_program') + $learner->get(10, 'balance') + $learner->get(10, 'achievement')+ $learner->get(10, '1618_prov_inc')+ $learner->get(10, '1618_emp_inc')+ $learner->get(10, 'FM36_Disadv')+ $learner->get(10, 'als'))
						//			,'P11_adj' => sprintf("%.2f",($learner->get(11, 'adjusted')))

                    ,'P11_OPP' => $ts_opp[11]
                    ,'P11_bal' => $ts_bal[11]
					,'P11_ach' => sprintf("%.2f",($learner->get(11, 'achievement')))
					,'P11_ach_p' => sprintf("%.2f",($learner->get(11,'achievement_predicted')))
                    ,'P11_EM_OPP' => $em_opp[11]
                    ,'P11_EM_Bal' => $em_bal[11]
                    ,'P11_1618_Pro_Inc' => sprintf("%.2f",($learner->get(11,'1618_prov_inc')))
                    ,'P11_1618_Emp_Inc' => sprintf("%.2f",($learner->get(11,'1618_emp_inc')))
                    ,'P11_FM36_Disadv' => sprintf("%.2f",($learner->get(11,'FM36_Disadv')))
                    ,'P11_ALS' => sprintf("%.2f",($learner->get(11,'als')))
                    ,'P11_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(11,'framework_uplift_opp')))
                    ,'P11_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(11,'framework_uplift_bal')))
                    ,'P11_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(11,'framework_uplift_comp')))
					,'P11_total' => sprintf("%.2f",$learner->get(11, 'on_program') + $learner->get(11, 'balance') + $learner->get(11, 'achievement')+ $learner->get(11, '1618_prov_inc')+ $learner->get(11, '1618_emp_inc')+ $learner->get(11, 'FM36_Disadv')+ $learner->get(11, 'als'))
						//			,'P12_adj' => sprintf("%.2f",($learner->get(12, 'adjusted')))

                    ,'P12_OPP' => $ts_opp[12]
                    ,'P12_bal' => $ts_bal[12]
					,'P12_ach' => sprintf("%.2f",($learner->get(12, 'achievement')))
					,'P12_ach_p' => sprintf("%.2f",($learner->get(12,'achievement_predicted')))
                    ,'P12_EM_OPP' => $em_opp[12]
                    ,'P12_EM_Bal' => $em_bal[12]
                    ,'P12_1618_Pro_Inc' => sprintf("%.2f",($learner->get(12,'1618_prov_inc')))
                    ,'P12_1618_Emp_Inc' => sprintf("%.2f",($learner->get(12,'1618_emp_inc')))
                    ,'P12_FM36_Disadv' => sprintf("%.2f",($learner->get(12,'FM36_Disadv')))
                    ,'P12_ALS' => sprintf("%.2f",($learner->get(12,'als')))
                    ,'P12_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(12,'framework_uplift_opp')))
                    ,'P12_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(12,'framework_uplift_bal')))
                    ,'P12_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(12,'framework_uplift_comp')))
					,'P12_total' => sprintf("%.2f",$learner->get(12, 'on_program') + $learner->get(12, 'balance') + $learner->get(12, 'achievement')+ $learner->get(12, '1618_prov_inc')+ $learner->get(12, '1618_emp_inc')+ $learner->get(12, 'FM36_Disadv')+ $learner->get(12, 'als'))

                    ,'P13_OPP' => $ts_opp[13]
                    ,'P13_bal' => $ts_bal[13]
					,'P13_ach' => sprintf("%.2f",($learner->get(13, 'achievement')))
					,'P13_ach_p' => sprintf("%.2f",($learner->get(13,'achievement_predicted')))
                    ,'P13_EM_OPP' => $em_opp[13]
                    ,'P13_EM_Bal' => $em_bal[13]
                    ,'P13_1618_Pro_Inc' => sprintf("%.2f",($learner->get(13,'1618_prov_inc')))
                    ,'P13_1618_Emp_Inc' => sprintf("%.2f",($learner->get(13,'1618_emp_inc')))
                    ,'P13_FM36_Disadv' => sprintf("%.2f",($learner->get(13,'FM36_Disadv')))
                    ,'P13_ALS' => sprintf("%.2f",($learner->get(13,'als')))
                    ,'P13_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(13,'framework_uplift_opp')))
                    ,'P13_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(13,'framework_uplift_bal')))
                    ,'P13_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(13,'framework_uplift_comp')))
					,'P13_total' => sprintf("%.2f",$learner->get(13, 'on_program') + $learner->get(13, 'balance') + $learner->get(13, 'achievement')+ $learner->get(13, '1618_prov_inc')+ $learner->get(13, '1618_emp_inc')+ $learner->get(13, 'FM36_Disadv')+ $learner->get(13, 'als'))

                    ,'P14_OPP' => $ts_opp[14]
                    ,'P14_bal' => $ts_bal[14]
					,'P14_ach' => sprintf("%.2f",($learner->get(14, 'achievement')))
					,'P14_ach_p' => sprintf("%.2f",($learner->get(14,'achievement_predicted')))
                    ,'P14_EM_OPP' => $em_opp[14]
                    ,'P14_EM_Bal' => $em_bal[14]
                    ,'P14_1618_Pro_Inc' => sprintf("%.2f",($learner->get(14,'1618_prov_inc')))
                    ,'P14_1618_Emp_Inc' => sprintf("%.2f",($learner->get(14,'1618_emp_inc')))
                    ,'P14_FM36_Disadv' => sprintf("%.2f",($learner->get(14,'FM36_Disadv')))
                    ,'P14_ALS' => sprintf("%.2f",($learner->get(14,'als')))
                    ,'P14_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(14,'framework_uplift_opp')))
                    ,'P14_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(14,'framework_uplift_bal')))
                    ,'P14_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(14,'framework_uplift_comp')))
					,'P14_total' => sprintf("%.2f",$learner->get(14, 'on_program') + $learner->get(14, 'balance') + $learner->get(14, 'achievement')+ $learner->get(14, '1618_prov_inc')+ $learner->get(14, '1618_emp_inc')+ $learner->get(14, 'FM36_Disadv')+ $learner->get(14, 'als'))

                    ,'P15_OPP' => $ts_opp[15]
                    ,'P15_bal' => $ts_bal[15]
					,'P15_ach' => sprintf("%.2f",($learner->get(15, 'achievement')))
					,'P15_ach_p' => sprintf("%.2f",($learner->get(15,'achievement_predicted')))
                    ,'P15_EM_OPP' => $em_opp[15]
                    ,'P15_EM_Bal' => $em_bal[15]
                    ,'P15_1618_Pro_Inc' => sprintf("%.2f",($learner->get(15,'1618_prov_inc')))
                    ,'P15_1618_Emp_Inc' => sprintf("%.2f",($learner->get(15,'1618_emp_inc')))
                    ,'P15_FM36_Disadv' => sprintf("%.2f",($learner->get(15,'FM36_Disadv')))
                    ,'P15_ALS' => sprintf("%.2f",($learner->get(15,'als')))
                    ,'P15_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(15,'framework_uplift_opp')))
                    ,'P15_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(15,'framework_uplift_bal')))
                    ,'P15_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(15,'framework_uplift_comp')))
					,'P15_total' => sprintf("%.2f",$learner->get(15, 'on_program') + $learner->get(15, 'balance') + $learner->get(15, 'achievement')+ $learner->get(15, '1618_prov_inc')+ $learner->get(15, '1618_emp_inc')+ $learner->get(15, 'FM36_Disadv')+ $learner->get(15, 'als'))

                    ,'P16_OPP' => $ts_opp[16]
                    ,'P16_bal' => $ts_bal[16]
					,'P16_ach' => sprintf("%.2f",($learner->get(16, 'achievement')))
					,'P16_ach_p' => sprintf("%.2f",($learner->get(16,'achievement_predicted')))
                    ,'P16_EM_OPP' => $em_opp[16]
                    ,'P16_EM_Bal' => $em_bal[16]
                    ,'P16_1618_Pro_Inc' => sprintf("%.2f",($learner->get(16,'1618_prov_inc')))
                    ,'P16_1618_Emp_Inc' => sprintf("%.2f",($learner->get(16,'1618_emp_inc')))
                    ,'P16_FM36_Disadv' => sprintf("%.2f",($learner->get(16,'FM36_Disadv')))
                    ,'P16_ALS' => sprintf("%.2f",($learner->get(16,'als')))
                    ,'P16_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(16,'framework_uplift_opp')))
                    ,'P16_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(16,'framework_uplift_bal')))
                    ,'P16_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(16,'framework_uplift_comp')))
					,'P16_total' => sprintf("%.2f",$learner->get(16, 'on_program') + $learner->get(16, 'balance') + $learner->get(16, 'achievement')+ $learner->get(16, '1618_prov_inc')+ $learner->get(16, '1618_emp_inc')+ $learner->get(16, 'FM36_Disadv')+ $learner->get(16, 'als'))

                    ,'P17_OPP' => $ts_opp[17]
                    ,'P17_bal' => $ts_bal[17]
					,'P17_ach' => sprintf("%.2f",($learner->get(17, 'achievement')))
					,'P17_ach_p' => sprintf("%.2f",($learner->get(17,'achievement_predicted')))
                    ,'P17_EM_OPP' => $em_opp[17]
                    ,'P17_EM_Bal' => $em_bal[17]
                    ,'P17_1618_Pro_Inc' => sprintf("%.2f",($learner->get(17,'1618_prov_inc')))
                    ,'P17_1618_Emp_Inc' => sprintf("%.2f",($learner->get(17,'1618_emp_inc')))
                    ,'P17_FM36_Disadv' => sprintf("%.2f",($learner->get(17,'FM36_Disadv')))
                    ,'P17_ALS' => sprintf("%.2f",($learner->get(17,'als')))
                    ,'P17_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(17,'framework_uplift_opp')))
                    ,'P17_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(17,'framework_uplift_bal')))
                    ,'P17_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(17,'framework_uplift_comp')))
					,'P17_total' => sprintf("%.2f",$learner->get(17, 'on_program') + $learner->get(17, 'balance') + $learner->get(17, 'achievement')+ $learner->get(17, '1618_prov_inc')+ $learner->get(17, '1618_emp_inc')+ $learner->get(17, 'FM36_Disadv')+ $learner->get(17, 'als'))

                    ,'P18_OPP' => $ts_opp[18]
                    ,'P18_bal' => $ts_bal[18]
					,'P18_ach' => sprintf("%.2f",($learner->get(18, 'achievement')))
					,'P18_ach_p' => sprintf("%.2f",($learner->get(18,'achievement_predicted')))
                    ,'P18_EM_OPP' => $em_opp[18]
                    ,'P18_EM_Bal' => $em_bal[18]
                    ,'P18_1618_Pro_Inc' => sprintf("%.2f",($learner->get(18,'1618_prov_inc')))
                    ,'P18_1618_Emp_Inc' => sprintf("%.2f",($learner->get(18,'1618_emp_inc')))
                    ,'P18_FM36_Disadv' => sprintf("%.2f",($learner->get(18,'FM36_Disadv')))
                    ,'P18_ALS' => sprintf("%.2f",($learner->get(18,'als')))
                    ,'P18_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(18,'framework_uplift_opp')))
                    ,'P18_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(18,'framework_uplift_bal')))
                    ,'P18_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(18,'framework_uplift_comp')))
					,'P18_total' => sprintf("%.2f",$learner->get(18, 'on_program') + $learner->get(18, 'balance') + $learner->get(18, 'achievement')+ $learner->get(18, '1618_prov_inc')+ $learner->get(18, '1618_emp_inc')+ $learner->get(18, 'FM36_Disadv')+ $learner->get(18, 'als'))

                    ,'P19_OPP' => $ts_opp[19]
                    ,'P19_bal' => $ts_bal[19]
					,'P19_ach' => sprintf("%.2f",($learner->get(19, 'achievement')))
					,'P19_ach_p' => sprintf("%.2f",($learner->get(19,'achievement_predicted')))
                    ,'P19_EM_OPP' => $em_opp[19]
                    ,'P19_EM_Bal' => $em_bal[19]
                    ,'P19_1618_Pro_Inc' => sprintf("%.2f",($learner->get(19,'1618_prov_inc')))
                    ,'P19_1618_Emp_Inc' => sprintf("%.2f",($learner->get(19,'1618_emp_inc')))
                    ,'P19_FM36_Disadv' => sprintf("%.2f",($learner->get(19,'FM36_Disadv')))
                    ,'P19_ALS' => sprintf("%.2f",($learner->get(19,'als')))
                    ,'P19_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(19,'framework_uplift_opp')))
                    ,'P19_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(19,'framework_uplift_bal')))
                    ,'P19_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(19,'framework_uplift_comp')))
					,'P19_total' => sprintf("%.2f",$learner->get(19, 'on_program') + $learner->get(19, 'balance') + $learner->get(19, 'achievement')+ $learner->get(19, '1618_prov_inc')+ $learner->get(19, '1618_emp_inc')+ $learner->get(19, 'FM36_Disadv')+ $learner->get(19, 'als'))

                    ,'P20_OPP' => $ts_opp[20]
                    ,'P20_bal' => $ts_bal[20]
					,'P20_ach' => sprintf("%.2f",($learner->get(20, 'achievement')))
					,'P20_ach_p' => sprintf("%.2f",($learner->get(20,'achievement_predicted')))
                    ,'P20_EM_OPP' => $em_opp[20]
                    ,'P20_EM_Bal' => $em_bal[20]
                    ,'P20_1618_Pro_Inc' => sprintf("%.2f",($learner->get(20,'1618_prov_inc')))
                    ,'P20_1618_Emp_Inc' => sprintf("%.2f",($learner->get(20,'1618_emp_inc')))
                    ,'P20_FM36_Disadv' => sprintf("%.2f",($learner->get(20,'FM36_Disadv')))
                    ,'P20_ALS' => sprintf("%.2f",($learner->get(20,'als')))
                    ,'P20_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(20,'framework_uplift_opp')))
                    ,'P20_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(20,'framework_uplift_bal')))
                    ,'P20_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(20,'framework_uplift_comp')))
					,'P20_total' => sprintf("%.2f",$learner->get(20, 'on_program') + $learner->get(20, 'balance') + $learner->get(20, 'achievement')+ $learner->get(20, '1618_prov_inc')+ $learner->get(20, '1618_emp_inc')+ $learner->get(20, 'FM36_Disadv')+ $learner->get(20, 'als'))

                    ,'P21_OPP' => $ts_opp[21]
                    ,'P21_bal' => $ts_bal[21]
					,'P21_ach' => sprintf("%.2f",($learner->get(21, 'achievement')))
					,'P21_ach_p' => sprintf("%.2f",($learner->get(21,'achievement_predicted')))
                    ,'P21_EM_OPP' => $em_opp[21]
                    ,'P21_EM_Bal' => $em_bal[21]
                    ,'P21_1618_Pro_Inc' => sprintf("%.2f",($learner->get(21,'1618_prov_inc')))
                    ,'P21_1618_Emp_Inc' => sprintf("%.2f",($learner->get(21,'1618_emp_inc')))
                    ,'P21_FM36_Disadv' => sprintf("%.2f",($learner->get(21,'FM36_Disadv')))
                    ,'P21_ALS' => sprintf("%.2f",($learner->get(21,'als')))
                    ,'P21_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(21,'framework_uplift_opp')))
                    ,'P21_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(21,'framework_uplift_bal')))
                    ,'P21_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(21,'framework_uplift_comp')))
					,'P21_total' => sprintf("%.2f",$learner->get(21, 'on_program') + $learner->get(21, 'balance') + $learner->get(21, 'achievement')+ $learner->get(21, '1618_prov_inc')+ $learner->get(21, '1618_emp_inc')+ $learner->get(21, 'FM36_Disadv')+ $learner->get(21, 'als'))

                    ,'P22_OPP' => $ts_opp[22]
                    ,'P22_bal' => $ts_bal[22]
					,'P22_ach' => sprintf("%.2f",($learner->get(22, 'achievement')))
					,'P22_ach_p' => sprintf("%.2f",($learner->get(22,'achievement_predicted')))
                    ,'P22_EM_OPP' => $em_opp[22]
                    ,'P22_EM_Bal' => $em_bal[22]
                    ,'P22_1618_Pro_Inc' => sprintf("%.2f",($learner->get(22,'1618_prov_inc')))
                    ,'P22_1618_Emp_Inc' => sprintf("%.2f",($learner->get(22,'1618_emp_inc')))
                    ,'P22_FM36_Disadv' => sprintf("%.2f",($learner->get(22,'FM36_Disadv')))
                    ,'P22_ALS' => sprintf("%.2f",($learner->get(22,'als')))
                    ,'P22_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(22,'framework_uplift_opp')))
                    ,'P22_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(22,'framework_uplift_bal')))
                    ,'P22_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(22,'framework_uplift_comp')))
					,'P22_total' => sprintf("%.2f",$learner->get(22, 'on_program') + $learner->get(22, 'balance') + $learner->get(22, 'achievement')+ $learner->get(22, '1618_prov_inc')+ $learner->get(22, '1618_emp_inc')+ $learner->get(22, 'FM36_Disadv')+ $learner->get(22, 'als'))

                    ,'P23_OPP' => $ts_opp[23]
                    ,'P23_bal' => $ts_bal[23]
					,'P23_ach' => sprintf("%.2f",($learner->get(23, 'achievement')))
					,'P23_ach_p' => sprintf("%.2f",($learner->get(23,'achievement_predicted')))
                    ,'P23_EM_OPP' => $em_opp[23]
                    ,'P23_EM_Bal' => $em_bal[23]
                    ,'P23_1618_Pro_Inc' => sprintf("%.2f",($learner->get(23,'1618_prov_inc')))
                    ,'P23_1618_Emp_Inc' => sprintf("%.2f",($learner->get(23,'1618_emp_inc')))
                    ,'P23_FM36_Disadv' => sprintf("%.2f",($learner->get(23,'FM36_Disadv')))
                    ,'P23_ALS' => sprintf("%.2f",($learner->get(23,'als')))
                    ,'P23_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(23,'framework_uplift_opp')))
                    ,'P23_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(23,'framework_uplift_bal')))
                    ,'P23_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(23,'framework_uplift_comp')))
					,'P23_total' => sprintf("%.2f",$learner->get(23, 'on_program') + $learner->get(23, 'balance') + $learner->get(23, 'achievement')+ $learner->get(23, '1618_prov_inc')+ $learner->get(23, '1618_emp_inc')+ $learner->get(23, 'FM36_Disadv')+ $learner->get(23, 'als'))

                    ,'P24_OPP' => $ts_opp[24]
                    ,'P24_bal' => $ts_bal[24]
					,'P24_ach' => sprintf("%.2f",($learner->get(24, 'achievement')))
					,'P24_ach_p' => sprintf("%.2f",($learner->get(24,'achievement_predicted')))
                    ,'P24_EM_OPP' => $em_opp[24]
                    ,'P24_EM_Bal' => $em_bal[24]
                    ,'P24_1618_Pro_Inc' => sprintf("%.2f",($learner->get(24,'1618_prov_inc')))
                    ,'P24_1618_Emp_Inc' => sprintf("%.2f",($learner->get(24,'1618_emp_inc')))
                    ,'P24_FM36_Disadv' => sprintf("%.2f",($learner->get(24,'FM36_Disadv')))
                    ,'P24_ALS' => sprintf("%.2f",($learner->get(24,'als')))
                    ,'P24_1618_FW_Uplift_OPP' => sprintf("%.2f",($learner->get(24,'framework_uplift_opp')))
                    ,'P24_1618_FW_Uplift_Bal' => sprintf("%.2f",($learner->get(24,'framework_uplift_bal')))
                    ,'P24_1618_FW_Uplift_Comp' => sprintf("%.2f",($learner->get(24,'framework_uplift_comp')))
					,'P24_total' => sprintf("%.2f",$learner->get(24, 'on_program') + $learner->get(24, 'balance') + $learner->get(24, 'achievement')+ $learner->get(24, '1618_prov_inc')+ $learner->get(24, '1618_emp_inc')+ $learner->get(24, 'FM36_Disadv')+ $learner->get(24, 'als'))

					,'grand_total' => sprintf("%.2f",$gt)
					);
				}
			}

//			if($learner->get($period) < 0)
//			{
//				//echo $availableFunding . '-' . $learner->get($period);
//				//pre($data);
//			}

//			pre($learner);

			// total funding
			//$this->ttotal += $learner->get($period);

			//echo $learner->get($period) . '<br />';
		}

		$this->data = $this->learners;
//		pre($this->learners);
		$this->ttotal = format_money(0);
	}


	// RE - added this in for use on the reconciler section of
	//    - the system.
	// ---
	public function get_learnerdata() {
		return $this->data;
	}

	public function toHTML()
	{
		$html = '
			<h3>Funding prediction for ' . $this->contractInfo->title . ' (<span style="color:orange;">' . $this->year . '</span>)' . '</h3>';

		$html .= '<p>Viewing funding for: <strong>' . ' W' . str_pad($this->period, 2, '0', STR_PAD_LEFT) . '</strong></p>';

		$html .= '
			<table class="resultset" cellpadding="6" id="dataMatrix">
			<thead><tr>
			<th class="topRow">Learner</th><th class="topRow">Learner Reference #</th><th class="topRow">Provider</th><th class="topRow">Course</th><th class="topRow">Qualification</th><th>Started On</th><th class="topRow">Funding</th>
			</tr></thead>		
		';

		// ' W' . str_pad($this->period, 2, '0', STR_PAD_LEFT)
		foreach($this->learners AS $key => $data)
		{
			if($data['funding'] > 0)
			{
				$html .= '<tr>';
				$html .= '<td><a href="/do.php?_action=funding_prediction&amp;contract=' . $this->contractInfo->id . '&amp;sq=' . $data['auto_id'] . '">' . ucwords(strtolower($data['name'])) . '</a></td>';
				$html .= '<td>' . $data['L03'] . '</td>';
				$html .= '<td>' . $data['provider_name'] . '</td>';
				$html .= '<td>' . $data['course_name'] . '</td>';
				$html .= '<td>' . $data['qualification_title'] . '</td>';
				$html .= '<td>' . $data['learner_start_date'] . '</td>';
				$html .= '<td>&pound; ' . $data['achievement'] . '</td>';
				$html .= '<td>&pound;s ' . $data['funding'] . '</td>';
				$html .= '</tr>';
			}
		}

		$html .= '
			<tr class="bottomRow"><td colspan="6"></td><td><strong>&pound; ' . $this->ttotal . '</strong></td></tr>
			</table>		
		';
		return $html;
	}

	public function toLineChart($link)
	{

		// Firstly, scale down the values to multiples of 1000
		$scaledFunding = array();
		foreach($this->totalFunding AS $period => $total)
		{
			$scaledFunding["$period"] = sprintf("%.2f",($total / 1000));
		}

		$labels = array();
		for($i = 1; $i <= 12; $i++)
		{
			$labels[] = 'W' . str_pad($i, 2, '0', STR_PAD_LEFT);
		}

		/*return '<img src="http://chart.apis.google.com/chart?
				chts=000000,12
				&amp;chs=600x250
				&amp;chf=bg,s,ffffff|c,s,ffffff
				&amp;chxt=x,y
				&amp;chxl=0:|' . implode('|', $labels) . '1:1|2' . '

				&amp;cht=lc
				&amp;chd=t:' . implode(',', $scaledFunding) . '
				&amp;chdl=Funding+in+%C2%A31000k
				&amp;chco=0000ff
				&amp;chls=1,1,0"
				alt="Google Chart"/>';
				*/


		$test_data = array_values($scaledFunding);
		// Here's where we call the chart, and return the encoded chart data
		return '<img src="http://chart.apis.google.com/chart?cht=bvs&amp;chs=600x300&amp;chd=' . $this->chart_data($test_data, $labels, '', false) . " />";

	}


	function chart_data($values, $labels, $link, $profiled = false)
	{
		// First, find the maximum value from the values given
		$maxValue = max($values);

		// A list of encoding characters to help later, as per Google's example
		$simpleEncoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		$chartData = 's:';
		for ($i = 0; $i < count($values); $i++)
		{
			$currentValue = $values[$i];

			if ($currentValue > -1) {
				$chartData.=substr($simpleEncoding,61*($currentValue/$maxValue),1);
			}
			else
			{
				$chartData.='_';
			}
		}

		// 2) Calculate the y axis labels (we want 3 intervals)
		$interval = $maxValue / 4;
		$ylabels = 0 . '|' . $interval . '|' . ($interval * 2) . '|' . ($interval * 3) . '|' . $maxValue;

		// Return the chart data - and let the Y axis to show the maximum value
		return $chartData . '&amp;chdl=%C2%A3+k&amp;chxt=y,x&amp;chxl=0:|' . $ylabels . '|1:|' . implode('|', $labels);
	}

}

?>