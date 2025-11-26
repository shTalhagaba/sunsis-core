<?php

require_once('FundingCalculator.php');
class FundingCalculator_2019 extends FundingCalculator
{

    const LAD_DB = 'lad201314';
    const T2GSLN = '2732';
    const T2G_UPLIFT = 1;
    const ASLN = '2920';

    function __construct($db, $contracts)
    {
        parent::__construct($db, $contracts);
    }

    public function getData($link, $hook_fields = '', $hook_joins = '', $hook_where = '')
    {
        $funding2 = array();
        $c = 0;
        $d = 0;
        $als = Array();
        $xmlarray = Array();
        $EFA_Array = Array();
        $aims = Array();
        // XML Start
        if(DB_NAME=='am_ligauk')
        {
            $sqlxml = "	select ilr.ilr, ilr.tr_id, ilr.contract_id, contracts.title, contracts.contract_year, contracts.proportion
					,contracts.start_date, contracts.end_date, tr.marked_date as entry_end_date, courses.title as course_name
					,contracts.ukprn
					,concat(tr.firstnames,' ' ,tr.surname) as name, courses.programme_type, providers.legal_name as provider_name
					,employers.legal_name as employer_name, tr.marked_date, concat(assessors.firstnames,' ',assessors.surname) as assessor,concat(tutors.firstnames,' ',tutors.surname) as tutor,
					at_risk as at_risk
					from ilr
					left join contracts on contracts.id = ilr.contract_id
					left join tr on tr.id = ilr.tr_id
					left join users as learners on tr.username = learners.username
					left join users as assessors on assessors.id = tr.assessor
					left join users as tutors on tutors.id = tr.tutor
					left join courses_tr on courses_tr.tr_id = tr.id
					left join courses on courses.id = courses_tr.course_id
					left join organisations as providers on providers.id = tr.provider_id
					left join organisations as employers on employers.id = tr.employer_id
					where is_active = 1 and ilr.contract_id in (" . $this->contracts . ") $hook_where order by ilr.l03";
        }
        else
        {
            $sqlxml = "	select ilr.ilr, ilr.tr_id, ilr.contract_id, contracts.title, contracts.contract_year, contracts.proportion
					,contracts.start_date, contracts.end_date, tr.marked_date as entry_end_date, courses.title as course_name
					,contracts.ukprn
					,concat(tr.firstnames,' ' ,tr.surname) as name, courses.programme_type, providers.legal_name as provider_name
					,employers.legal_name as employer_name, tr.marked_date, concat(assessors.firstnames,' ',assessors.surname) as assessor,concat(tutors.firstnames,' ',tutors.surname) as tutor,
					0 as at_risk
					from ilr
					left join contracts on contracts.id = ilr.contract_id
					left join tr on tr.id = ilr.tr_id
					left join users as learners on tr.username = learners.username
					left join users as assessors on assessors.id = tr.assessor
					left join users as tutors on tutors.id = tr.tutor
					left join courses_tr on courses_tr.tr_id = tr.id
					left join courses on courses.id = courses_tr.course_id
					left join organisations as providers on providers.id = tr.provider_id
					left join organisations as employers on employers.id = tr.employer_id
					where is_active = 1 and ilr.contract_id in (" . $this->contracts . ") $hook_where order by ilr.l03";
        }

        $stxml = $this->db->query($sqlxml);
        while($rowxml = $stxml->fetch())
        {
            $ilr = Ilr2019::loadFromXML($rowxml['ilr']);
            $at_risk = $rowxml['at_risk'];
            $ukprn = $rowxml['ukprn'];
            $edrs = '';
            $ac_postcode = '';
            $main_aim = 0;
            $fully_funded = '';
            $A46a = '';
            $framework_achivement_date = '';
            $ldm = '';
            $xpath = $ilr->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon"); $l42a = (empty($xpath[0]))?'':$xpath[0];
            $xpath = $ilr->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon"); $l42b = (empty($xpath[0]))?'':$xpath[0];

            foreach($ilr->LearnerEmploymentStatus as $les)
            {
                if($les->EmpStat=='10');
                $edrs = trim($les->EmpId);
            }
            $framework_achieved = 0;
            foreach($ilr->LearningDelivery as $aim)
            {
                if($aim->AimType=='1')
                {
                    if(("".$aim->Outcome)==1)
                        $framework_achieved = 1;

                    if($aim->CompStatus=='2')
                        $framework_achivement_date = $aim->LearnActEndDate;
                    foreach($aim->LearningDeliveryFAM as $ldf)
                        if($ldf->LearnDelFAMType=='LDM' && $ldf->LearnDelFAMCode!='')
                            $ldm = "" . $ldf->LearnDelFAMCode;
                }
            }

            foreach($ilr->LearningDelivery as $aim)
            {
                $xmlarray = Array();
                $main_aim++;
                $a10 = "" . $aim->FundModel;
                if($a10=="")
                    $a10 = "35";
                if($a10=='99' || ($a10=='25' && $aim->ProgType=='24' && $aim->AimType!=5))
                    continue;

                $xmlarray['FundModel'] = "" . $aim->FundModel;
                // Shove EFA Funding
                if(($aim->FundModel == '25' && $aim->AimType==5))
                {
                    $efa_funding = $this->calculateEFA($link, $ilr, $ukprn);
                    $xmlarray['EFA_Amount'] = $efa_funding;
                }
                else
                {
                    $xmlarray['EFA_Amount'] = 0;
                }

                if($a10!='99' && $a10!='')
                {
                    $a34 = "" . $aim->CompStatus;
                    $a35 = "" . $aim->Outcome;
                    $marked_date = $rowxml['marked_date'];
                    if(!in_array("'" . strtoupper($aim->LearnAimRef) . "'", $aims))
                        $aims[]="'" . strtoupper($aim->LearnAimRef) . "'";
                    $xmlarray['funding_type'] = "".$aim->FundModel;
                    $xmlarray['L03'] = "". $ilr->LearnRefNumber;
                    $xmlarray['uln'] = "". $ilr->ULN;
                    $xmlarray['l42a'] = $l42a;
                    $xmlarray['l42b'] = $l42b;
                    $xmlarray['contract_year'] = $rowxml['contract_year'];
                    $xmlarray['contract_id'] = $rowxml['contract_id'];
                    $xmlarray['proportion'] = $rowxml['proportion'];
                    $xmlarray['framework_achieved'] = $framework_achieved;
                    $a27 = Date::toMySQL("". $aim->LearnStartDate);
                    $LearnStartDate = new Date($a27);
                    $xmlarray['learner_start_date'] = $a27;
                    $a28 = Date::toMySQL("". $aim->LearnPlanEndDate);
                    $xmlarray['learner_target_end_date'] = $a28;
                    $a31 = Date::toMySQL("" . $aim->LearnActEndDate);
                    $xmlarray['learner_end_date'] = $a31;

                    // Calculate the new Funding Line Type
                    $dob = $ilr->DateOfBirth;
                    $d2 = new DateTime(Date::toMySQL($ilr->DateOfBirth));
                    $d1 = new DateTime(Date::toMySQL($aim->LearnStartDate));
                    $diff = $d2->diff($d1);
                    $the_learner_age_at_31_august_of_the_current_teaching_year = $diff->y;
                    if(("".$aim->ProgType)==24)
                        $the_learning_delivery_is_a_traineeship = true;
                    else
                        $the_learning_delivery_is_a_traineeship = false;
                    $the_learning_delivery_is_eef_2 = false;
                    $the_learning_delivery_is_eef_3 = false;
                    $ldm_array = Array();
                    if(isset($the_learning_delivery_contract_type))
                        unset($the_learning_delivery_contract_type);
                    if(isset($the_learning_delivery_earliest_act_date))
                        unset($the_learning_delivery_earliest_act_date);
                    foreach($aim->LearningDeliveryFAM as $ldf)
                    {
                        if($ldf->LearnDelFAMType=='LDM' && $ldf->LearnDelFAMCode!='')
                            $ldm_array[] = "" . $ldf->LearnDelFAMCode;
                        if($ldf->LearnDelFAMType=='EEF' && $ldf->LearnDelFAMCode!='2')
                            $the_learning_delivery_is_eef_2 = true;
                        if($ldf->LearnDelFAMType=='EEF' && $ldf->LearnDelFAMCode!='3')
                            $the_learning_delivery_is_eef_3 = true;
                        if($ldf->LearnDelFAMType=='ACT' && $ldf->LearnDelFAMCode!='')
                        {
                            if(!isset($the_learning_delivery_contract_type))
                                $the_learning_delivery_contract_type = $ldf->LearnDelFAMCode;
                            if(!isset($the_learning_delivery_earliest_act_date) and $ldf->LearnDelFAMDateFrom!='' and $ldf->LearnDelFAMDateFrom!='dd/mm/yyyy')
                                $the_learning_delivery_earliest_act_date = new Date($ldf->LearnDelFAMDateFrom);
                        }
                    }
                    if(!isset($the_learning_delivery_contract_type))
                        $the_learning_delivery_contract_type = 2;
                    if(in_array(357,$ldm_array))
                        $the_learning_delivery_is_funded_from_a_procured_budget = true;
                    else
                        $the_learning_delivery_is_funded_from_a_procured_budget = false;

                    if($aim->ProgType==2 or $aim->ProgType==3 or $aim->ProgType==10 or $aim->ProgType==20 or $aim->ProgType==21 or $aim->ProgType==22 or $aim->ProgType==23 or $aim->ProgType==25)
                        $the_learning_delivery_is_an_apprenticeship = true;
                    else
                        $the_learning_delivery_is_an_apprenticeship = false;

                    if($the_learning_delivery_is_an_apprenticeship and $aim->AimType==1)
                        $the_learning_delivery_is_an_apprenticeship_programme_aim = true;
                    else
                        $the_learning_delivery_is_an_apprenticeship_programme_aim = false;

                    if($the_learning_delivery_is_an_apprenticeship and $aim->AimType==3)
                        $the_learning_delivery_is_an_apprenticeship_component_aim = true;
                    else
                        $the_learning_delivery_is_an_apprenticeship_component_aim = false;

                    $start_date = new Date('01/08/2099');
                    foreach($ilr->LearningDelivery as $aim2)
                    {
                        if(("".$aim2->OrigLearnStartDate)!="" && ("".$aim2->OrigLearnStartDate)!="dd/mm/yyyy")
                            $sd = "".$aim2->OrigLearnStartDate;
                        else
                            $sd = "".$aim2->LearnStartDate;

                        if($start_date->after($sd))
                            $start_date = new Date($sd);
                    }
                    $age = substr(Date::dateDiff($start_date, $dob, 3),0,2);

                    $the_learning_delivery_age_at_start = $age;

                    if(isset($the_learning_delivery_earliest_act_date) and $the_learning_delivery_earliest_act_date->after("31/12/2017") and $the_learning_delivery_contract_type==2)
                        $the_learning_delivery_is_non_levy_procured = true;
                    else
                        $the_learning_delivery_is_non_levy_procured = false;

                    if($aim->FundModel!=36)
                    {
                        if($the_learner_age_at_31_august_of_the_current_teaching_year>=19 && $the_learning_delivery_is_a_traineeship && (!$the_learning_delivery_is_funded_from_a_procured_budget))
                            $xmlarray['new_aim_type'] = "19-24 Traineeship (non-procured)";
                        elseif($the_learner_age_at_31_august_of_the_current_teaching_year>=19 && $the_learning_delivery_is_a_traineeship && $the_learning_delivery_is_funded_from_a_procured_budget)
                            $xmlarray['new_aim_type'] = "19-24 Traineeship (procured from Nov 2017)";
                        elseif($the_learner_age_at_31_august_of_the_current_teaching_year>=19 && (!$the_learning_delivery_is_funded_from_a_procured_budget) && (!$the_learning_delivery_is_an_apprenticeship))
                            $xmlarray['new_aim_type'] = "AEB - Other Learning (non-procured)";
                        elseif($the_learner_age_at_31_august_of_the_current_teaching_year>=19 && $the_learning_delivery_is_funded_from_a_procured_budget && (!$the_learning_delivery_is_an_apprenticeship))
                            $xmlarray['new_aim_type'] = "AEB - Other Learning (procured from Nov 2017)";
                        elseif($the_learning_delivery_age_at_start<19 and $the_learning_delivery_is_an_apprenticeship)
                            $xmlarray['new_aim_type'] = "16-18 Apprenticeship";
                        elseif($the_learning_delivery_age_at_start>=19 and $the_learning_delivery_is_an_apprenticeship and $the_learning_delivery_is_eef_2)
                            $xmlarray['new_aim_type'] = "16-18 Apprenticeship";
                        elseif($the_learning_delivery_age_at_start>=19 and $the_learning_delivery_age_at_start<24 and $the_learning_delivery_is_an_apprenticeship and (!$the_learning_delivery_is_eef_2))
                            $xmlarray['new_aim_type'] = "19-23 Apprenticeship";
                        elseif($the_learning_delivery_age_at_start>=24 and $the_learning_delivery_is_an_apprenticeship and $the_learning_delivery_is_eef_3)
                            $xmlarray['new_aim_type'] = "19-23 Apprenticeship";
                        elseif($the_learning_delivery_age_at_start>=24 and $the_learning_delivery_is_an_apprenticeship and (!$the_learning_delivery_is_eef_3))
                            $xmlarray['new_aim_type'] = "24+ Apprenticeship";
                        else
                            $xmlarray['new_aim_type'] = "None";
                    }
                    else
                    {
                        if($the_learning_delivery_is_an_apprenticeship and $the_learning_delivery_contract_type==1 and $the_learning_delivery_age_at_start<19 or ($the_learning_delivery_age_at_start>=19 && $the_learning_delivery_is_eef_2))
                            $xmlarray['new_aim_type'] = "16-18 Apprenticeship (From May 2017) Levy Contract";
                        elseif((!$the_learning_delivery_is_non_levy_procured) and $the_learning_delivery_is_an_apprenticeship and $the_learning_delivery_contract_type==2 and ($the_learning_delivery_age_at_start<19 or ($the_learning_delivery_age_at_start>=19 and $the_learning_delivery_is_eef_2)))
                            $xmlarray['new_aim_type'] = "16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured)";
                        elseif($the_learning_delivery_is_non_levy_procured and $the_learning_delivery_is_an_apprenticeship and $the_learning_delivery_contract_type==2 and ($the_learning_delivery_age_at_start<19 or ($the_learning_delivery_age_at_start>=19 and $the_learning_delivery_is_eef_2)))
                            $xmlarray['new_aim_type'] = "16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured)";
                        elseif($the_learning_delivery_is_an_apprenticeship and $the_learning_delivery_contract_type==1)
                            $xmlarray['new_aim_type'] = "19+ Apprenticeship (From May 2017) Levy Contract";
                        elseif((!$the_learning_delivery_is_non_levy_procured) and $the_learning_delivery_is_an_apprenticeship and $the_learning_delivery_contract_type==2)
                            $xmlarray['new_aim_type'] = "19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured)";
                        elseif($the_learning_delivery_is_non_levy_procured and $the_learning_delivery_is_an_apprenticeship and $the_learning_delivery_contract_type==2)
                            $xmlarray['new_aim_type'] = "19+ Apprenticeship Non-Levy Contract (procured)";
                        else
                            $xmlarray['new_aim_type'] = "None";
                    }

                    // End

                    //19-24 Traineeship (non-procured)
                    //19-24 Traineeship (procured from Nov 2017)
                    //AEB - Other Learning (non-procured)
                    //AEB - Other Learning (procured from Nov 2017)
                    //16-18 Apprenticeship
                    //19-23 Apprenticeship
                    //24+ Apprenticeship
                    //16-18 Apprenticeship (From May 2017) Levy Contract
                    //16-18 Apprenticeship (From May 2017) Non-Levy Contract (non-procured)
                    //16-18 Apprenticeship (From May 2017) Non-Levy Contract (procured)
                    //19+ Apprenticeship (From May 2017) Levy Contract
                    //19+ Apprenticeship (From May 2017) Non-Levy Contract (non-procured)
                    //19+ Apprenticeship Non-Levy Contract (procured)



                    // Calculate the latest actual/ planned end date for EFA
                    if($aim->FundModel==25)
                    {
                        $EFA_End_Date = new Date('2000-01-01');
                        foreach($ilr->LearningDelivery as $aim2)
                        {
                            if(Date::isDate("".$aim2->LearnActEndDate))
                                if($EFA_End_Date->before("".$aim2->LearnActEndDate))
                                    $EFA_End_Date = new Date("".$aim2->LearnActEndDate);
                                else
                                {}
                            else
                                if($EFA_End_Date->before("".$aim2->LearnPlanEndDate))
                                    $EFA_End_Date = new Date("".$aim2->LearnPlanEndDate);
                        }
                        $xmlarray['learner_end_date'] = $EFA_End_Date->formatMySQL();
                    }
                    else
                    {
                        $xmlarray['learner_end_date'] = $a31;
                    }
                    $a40 = Date::toMySQL("" . $aim->AchDate);
                    $xmlarray['entry_end_date'] = Date::toMySQL($rowxml['entry_end_date']);
                    $xmlarray['continuing'] = ($a34=='1')?1:0;
                    if($framework_achivement_date!='')
                        $xmlarray['framework_achivement_date'] = "" . $framework_achivement_date;
                    else
                        $xmlarray['framework_achivement_date'] = "" . $aim->AchDate;
                    $xmlarray['LDM'] = $ldm;
                    if($ldm=='')
                        foreach($aim->LearningDeliveryFAM as $ldf)
                            if($ldf->LearnDelFAMType=='LDM' && $ldf->LearnDelFAMCode!='')
                                $xmlarray['LDM'] = "" . $ldf->LearnDelFAMCode;
                    $xmlarray['aim_achievement_date'] = "" . $aim->LearnActEndDate;
                    $xpath = $ilr->xpath("/Learner/LearningDelivery[AimType='1' or AimType='4']/Outcome"); $achieved = (empty($xpath[0]))?'':$xpath[0];
                    $achieved = "".$achieved;


                    if($aim->Outcome=='1')
                        $xmlarray['achieved'] = ($achieved=='1')?1:0;
                    else
                        $xmlarray['achieved'] = 0;
                    $xmlarray['aim_achieved'] = ("".$aim->Outcome=='1')?1:0;
                    $ad = "".$aim->AchDate;
                    $xmlarray['name'] = $rowxml['name'];
                    $xmlarray['course_name'] = $rowxml['course_name'];
                    if(("".$aim->ProgType)=="99" || ("".$aim->ProgType)=="")
                        $xmlarray['programme_type'] = 1;
                    else
                        $xmlarray['programme_type'] = 2;
                    $xmlarray['FworkCode'] = "".$aim->FworkCode;
                    $xmlarray['PwayCode'] = "".$aim->PwayCode;
                    $xmlarray['StdCode'] = "".$aim->StdCode;

                    // Aim Type
                    $ProgType = "".$aim->ProgType;
                    $xmlarray['aim_type'] = '';
                    $restart = 0;
                    if($ProgType=='2' || $ProgType=='3' || $ProgType=='10' || $ProgType=='20' || $ProgType=='21' || $ProgType=='22' || $ProgType=='23' || ($ProgType=='25' and $a10=='36'))
                    {
                        if($age<19)
                            $xmlarray['aim_type'] = "16-18 Apprenticeships";
                        elseif($age<24 || ($age<25 && $start_date->before('01-08-2013')))
                            $xmlarray['aim_type'] = "19-23 Apprenticeships";
                        else
                            $xmlarray['aim_type'] = "24+ Apprenticeships";

                        foreach($aim->LearningDeliveryFAM as $ldf)
                        {
                            if($ldf->LearnDelFAMType=='RES')
                                if($ldf->LearnDelFAMCode=='1')
                                    $restart = 1;
                        }
                    }
                    elseif($ProgType=='25' and $a10=='81')
                    {
                        if($age<19)
                            $xmlarray['aim_type'] = "16-18 Trailblazer";
                        elseif($age<24)
                            $xmlarray['aim_type'] = "19-23 Trailblazer";
                        else
                            $xmlarray['aim_type'] = "24+ Trailblazer";

                        foreach($aim->LearningDeliveryFAM as $ldf)
                        {
                            if($ldf->LearnDelFAMType=='RES')
                                if($ldf->LearnDelFAMCode=='1')
                                    $restart = 1;
                        }
                    }
                    else
                    {
                        $ldm = '';
                        foreach($aim->LearningDeliveryFAM as $ldf)
                        {
                            if($ldf->LearnDelFAMType=='WPL')
                                if($ldf->LearnDelFAMCode=='1')
                                    $ldm = 'Workplace';

                            if($ldf->LearnDelFAMType=='RES')
                                if($ldf->LearnDelFAMCode=='1')
                                    $restart = 1;
                        }

                        if($ldm=='Workplace')
                            $xmlarray['aim_type'] = "Workplace";
                        else
                            $xmlarray['aim_type'] = "Classroom";
                    }


                    $xmlarray['restart'] = $restart;
                    $a09 = "" . strtoupper($aim->LearnAimRef);
                    $xmlarray['qualid'] = $a09;
                    $xmlarray['provider_name'] = $rowxml['provider_name'];
                    if(SOURCE_LOCAL || DB_NAME == "am_ligauk")
                        $xmlarray['at_risk'] = $rowxml['at_risk'];
                    $xmlarray['provider_name'] = $rowxml['provider_name'];
                    $xmlarray['employer_name'] = $rowxml['employer_name'];
                    $xmlarray['assessor'] = $rowxml['assessor'];
                    $xmlarray['tutor'] = $rowxml['tutor'];

                    if($aim->AimType==2 || $aim->AimType==4)
                        $xmlarray['main_aim'] = 1;
                    else
                        $xmlarray['main_aim'] = 0;
                    $xmlarray['qualification_title'] = "";

                    if($A46a=='' || $A46a=='999')
                        $A46a = "" . $aim->A46a;

                    $xpath = $aim->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
                    $fully_funded = "".$ffi;
                    $xmlarray['fully_funded'] = $fully_funded;

                    $xpath = $aim->xpath("./LearningDeliveryFAM[LearnDelFAMType='LSF']/LearnDelFAMCode"); $lsf = (empty($xpath[0]))?'0':$xpath[0];
                    $lsf = "".$lsf;

                    $dlpc = $aim->DelLocPostCode;
                    $xpath = $ilr->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode"); $ppe = (empty($xpath))?'':$xpath[0];

                    $xmlarray['prior_learning'] = "".$aim->PriorLearnFundAdj;

                    $xmlarray['postcode'] = trim($dlpc);
                    $xmlarray['home_postcode'] = trim($ppe);
                    $xmlarray['A46a'] = $A46a;

                    foreach($ilr->LearningDelivery as $delivery)
                    {
                        if("". $delivery->LearnStartDate=='')
                            pre("Start Date is missing for learner ". $ilr->LearnRefNumber);
                        if($start_date->after(Date::toShort("". $delivery->LearnStartDate)))
                            $start_date = new Date(Date::toShort("" . $delivery->LearnStartDate));
                    }

                    $xmlarray['als'] = 0;

                    // SLN Calculation
                    if($aim->ProgType!='99' && $aim->ProgType!='')
                    {
                        $xpath = $aim->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
                        if($age<19)
                        {
                            $sln = 2804;
                            if(($lsf=='1') && (!in_array($xmlarray['L03'],$als)))
                            {
                                $als[] = $xmlarray['L03'];
                                if($als=='1')
                                    $xmlarray['als'] = 225 * $xmlarray['proportion'] / 100;
                                elseif($lsf=='1')
                                {
                                    $xmlarray['als'] = 150 * $xmlarray['proportion'] / 100;
                                }
                            }
                        }
                        elseif($age<25)
                        {
                            if($ffi=='1')
                                $sln = 2615;
                            else
                                $sln = 2615;

                            if(($lsf=='1') && (!in_array($xmlarray['L03'],$als)))
                            {
                                $als[] = $xmlarray['L03'];
                                if($lsf=='1')
                                    $xmlarray['als'] = 184 * $xmlarray['proportion'] / 100;
                                elseif($lsf=='1')
                                    $xmlarray['als'] = 122 * $xmlarray['proportion'] / 100;
                            }
                        }
                        else
                        {
                            if($ffi=='1')
                                $sln = 2092;
                            else
                                $sln = 2092;

                            if(($lsf=='1') && (!in_array($xmlarray['L03'],$als)))
                            {
                                $als[] = $xmlarray['L03'];
                                if($lsf=='1')
                                    $xmlarray['als'] = 184 * $xmlarray['proportion'] / 100;
                                elseif($lsf=='1')
                                    $xmlarray['als'] = 122 * $xmlarray['proportion'] / 100;
                            }
                        }
                    }
                    else
                    {
                        if($age<19)
                        {
                            $sln = 2615;
                        }
                        elseif($age<25)
                        {
                            $xpath = $aim->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
                            if($ffi=='1')
                                $sln = 2615;
                            else
                                $sln = 2615;
                        }
                        else
                        {
                            if($ffi)
                                $sln = 2615;
                            else
                                $sln = 2615;
                        }
                    }
                    // END SLN Calculation

                    // Shov Trailblazer Funding
                    $Trailblazer = Array();
                    $Disadvantage = Array();

                    $LearnStartDate->addDays(89);
                    $left_before_90_days = false;
                    if(Date::isDate("".$aim->LearnActEndDate))
                        if($LearnStartDate->after("".$aim->LearnActEndDate))
                            $left_before_90_days = true;

                    $LearnStartDate->addDays(275);
                    $left_before_365_days = false;
                    if(Date::isDate("".$aim->LearnActEndDate))
                        if($LearnStartDate->after("".$aim->LearnActEndDate))
                            $left_before_365_days = true;

                    if( ($aim->FundModel=='81' or $aim->FundModel=='36') && $aim->AimType=='1')
                    {
                        $StdCode=$aim->StdCode;
                        // Calculate 16-18 Incentive Payments
                        if($xmlarray['aim_type'] == "16-18 Trailblazer" or $xmlarray['aim_type'] == "16-18 Apprenticeships")
                        {
                            $days_on_programme = DAO::getSingleValue($link, "select DATEDIFF(NOW(),'$a27')+1");
                            //if($days_on_programme>=90)
                            {
                                $LearnStartDate->subtractDays(89+275);
                                $LearnStartDate->addDays(89);
                                $month = $LearnStartDate->getMonth();
                                $year = $LearnStartDate->getYear();
                                if($month>=8)
                                {
                                    $month-=7;
                                    $month += 12*($year-2019);
                                }
                                elseif($month<8)
                                {
                                    $month+=5;
                                    $month += 12*($year-2019-1);
                                }
                                if(!$left_before_90_days)
                                {
                                    $Incentive1618 = DAO::getSingleValue($link, "select 1618Incentive from lars201718.Core_LARS_StandardFunding Where StandardCode = '$StdCode' order by EffectiveFrom DESC LIMIT 0,1");
                                    $Trailblazer['1618Incentive'][$month] = $Incentive1618/2;
                                    $Trailblazer['1618ProvIncentive'][$month] = 500;
                                    $Trailblazer['1618EmpIncentive'][$month] = 500;
                                }
                                $LearnStartDate->addDays(275);
                                $month = $LearnStartDate->getMonth();
                                $year = $LearnStartDate->getYear();
                                if($month>=8)
                                {
                                    $month-=7;
                                    $month += 12*($year-2019);
                                }
                                elseif($month<8)
                                {
                                    $month+=5;
                                    $month += 12*($year-2019-1);
                                }
                                if(!$left_before_365_days)
                                {
                                    $Trailblazer['1618Incentive'][$month] = $Incentive1618/2;
                                    $Trailblazer['1618ProvIncentive'][$month] = 500;
                                    $Trailblazer['1618EmpIncentive'][$month] = 500;
                                }
                            }
                        }

                        // Postcode Disadvantage
                        $postcode = $xmlarray['home_postcode'];
                        $DisadvantageAmount = 0;
                        if($xmlarray['StdCode']=='')
                        {
                            $DisadvantageAmount=DAO::getSingleValue($link,"select app_cash from central.201718postcodedisadvantage where postcode='$postcode'");
                        }
                        if($DisadvantageAmount>0)
                        {
                            $LearnStartDate->subtractDays(89+275);
                            $LearnStartDate->addDays(89);
                            $month = $LearnStartDate->getMonth();
                            $year = $LearnStartDate->getYear();
                            if($month>=8)
                            {
                                $month-=7;
                                $month += 12*($year-2019);
                            }
                            elseif($month<8)
                            {
                                $month+=5;
                                $month += 12*($year-2019-1);
                            }
                            if(!$left_before_90_days)
                                $Disadvantage['DisadvantagePayment'][$month] = $DisadvantageAmount/2;
                            $LearnStartDate->addDays(275);
                            $month = $LearnStartDate->getMonth();
                            $year = $LearnStartDate->getYear();
                            if($month>=8)
                            {
                                $month-=7;
                                $month += 12*($year-2019);
                            }
                            elseif($month<8)
                            {
                                $month+=5;
                                $month += 12*($year-2019-1);
                            }
                            if(!$left_before_365_days)
                                $Disadvantage['DisadvantagePayment'][$month] = $DisadvantageAmount/2;
                        }

                        // Calculate SmallBusinessIncentive
                        $edrs=trim($edrs);
                        //$employer_is_small = DAO::getSingleValue($link, "select ERN from central.201415largeemployers where ERN = '$edrs'");
                    }
                    if($xmlarray['qualid']=='ZPROG001' and $xmlarray['FundModel']==36)
                    {
                        $xmlarray['TrailblazerFunding'] = $Trailblazer;
                        $xmlarray['DisadvantagePayment'] = $Disadvantage;
                    }

                    // Shov Trailblazer Funding
                    $Levy = Array();
                    $tnp1=0;
                    $tnp2=0;
                    $total = 0;
                    if($aim->FundModel=='36' && $aim->AimType=='1')
                    {
                        foreach($aim->TrailblazerApprenticeshipFinancialRecord as $tb)
                        {
                            if($tb->TBFinType=='TNP' and $tb->TBFinCode=='1')
                                $tnp1 = $tb->TBFinAmount;
                            if($tb->TBFinType=='TNP' and $tb->TBFinCode=='2')
                                $tnp2 = $tb->TBFinAmount;
                        }
                        $total = $tnp1+$tnp2;
                    }
                    $xmlarray['TotalNegotiatedPrice'] = $total;

                    $xmlarray['sln'] = $sln;
                    $xmlarray['age'] = $age;
                    $xmlarray['edrs'] = trim($edrs);
                    $xmlarray['tr_id'] = $rowxml['tr_id'];
                    $PriorLearnFundAdj = "". $aim->PriorLearnFundAdj;
                    if($PriorLearnFundAdj=='' || $PriorLearnFundAdj=='undefined')
                        $PriorLearnFundAdj = 100;
                    $xmlarray['funding_remaining_weight'] = ($PriorLearnFundAdj)/100;
                    $xmlarray['contract_name'] = $rowxml['title'];
                    $xmlarray['contract_start_date'] = Date::toMySQL($rowxml['start_date']);
                    $xmlarray['contract_end_date'] = Date::toMySQL($rowxml['end_date']);
                    $xmlarray['provider_factor'] = 1;
                    $qualify = DAO::getSingleValue($this->db, "
					SELECT 
					CASE
							WHEN DATEDIFF('$a28','$a27') < 14 THEN 1
							WHEN DATEDIFF('$a28','$a27')/7 <= 24 AND DATEDIFF('$a31','$a27')/7 < 2 THEN 0
							WHEN DATEDIFF('$a28','$a27')/7 <= 24 AND DATEDIFF('$a31','$a27')/7 >= 2 THEN 1
							WHEN DATEDIFF('$a28','$a27')/7 > 24 AND DATEDIFF('$a31','$a27')/7 < 6 THEN 0
							WHEN DATEDIFF('$a28','$a27')/7 > 24 AND DATEDIFF('$a31','$a27')/7 >= 6 THEN 1
					END AS qualify
					");
                    $xmlarray['qualify'] = $qualify;


                    $xmlarray['target_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE (l.census_end_date >= '$a27' AND l.census_start_date <= '$a28' AND '$a28' >= l.census_end_date AND l.submission <> 'W13') OR ('$a27' > l.census_start_date AND '$a28' < l.census_end_date and submission!='W13');");
                    $xmlarray['onprogram_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE IF('$a31'<>'', (l.census_end_date >= '$a27' AND l.census_start_date <= '$a28' AND '$a28' >= l.census_end_date AND l.census_end_date <= IF('$a31'<'$a28','$a31','$a28') AND l.submission!='W13' AND l.contract_type=2), (l.census_end_date >= '$a27' AND l.census_end_date <= '$a28'  AND l.submission!='W13' AND l.contract_type=2));");
                    // ASB tweak
                    if($xmlarray['onprogram_periods']=='')// && $xmlarray['LDM']=='125')
                    {
                        $xmlarray['onprogram_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE l.census_start_date <= '$a27' AND l.census_end_date >= '$a27'");
                    }


                    $a40aim = $a40;
                    if($framework_achivement_date!='' && $framework_achivement_date!='undefined' && $framework_achivement_date!='dd/mm/yyyy')
                        $a40 = Date::toMySQL("" . $framework_achivement_date);

                    // Profiled Achievement Period
                    $cd = new Date(date('Y-m-d'));
                    $a28d = new Date($a28);
                    if($a28d->getDate()<$cd->getDate())
                        $a28a = Date::toMySQL($cd);
                    else
                        $a28a = Date::toMySQL($a28d);


                    $xmlarray['contract_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE (l.census_end_date >= '$a27' AND l.census_start_date <= '$a28' AND '$a28' >= l.census_end_date AND l.submission <> 'W13' AND l.contract_year = {$rowxml['contract_year']}) OR  ('$a27' > l.census_start_date AND '$a28' < l.census_end_date and l.contract_year = {$rowxml['contract_year']});");
                    $xmlarray['achiever_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE ('$a27' <= l.census_end_date AND IF('$a40'<>'', '$a40','$a28a') >= l.census_start_date AND l.submission <> 'W13' and l.contract_type = 2)");
                    $xmlarray['aim_achievers'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE ('$a27' <= l.census_end_date AND IF('$a40aim'<>'', '$a40aim','$a28a') >= l.census_start_date AND l.submission <> 'W13' and l.contract_type = 2)");
                    $xmlarray['marked_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE (l.census_end_date >= '$a27' AND l.census_start_date <= '$marked_date' AND l.submission <> 'W13')");
                    $xmlarray['unfunded_periods'] = DAO::getSingleValue($this->db, "SELECT GROUP_CONCAT(l.contract_year,'-',l.submission ORDER BY l.contract_year,l.submission) AS learner_periods FROM central.lookup_submission_dates AS l WHERE (l.census_end_date >= '$a27' AND l.census_start_date <= (IF('$a27'>CURDATE(),'$a27',CURDATE())) AND l.submission <> 'W13') OR ('$a27' > l.census_start_date AND (IF('$a28'>CURDATE(),'$a28',CURDATE())) < l.census_end_date)");

                    if($xmlarray['programme_type'] == 1)
                        $xmlarray['total_funding'] = DAO::getSingleValue($this->db,"select rate from central.funding_rates201314 where learning_aim_ref = '$a09'");
                    elseif($xmlarray['funding_type']==36 && $xmlarray['qualid']!='ZPROG001')
                        $xmlarray['total_funding'] = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201718.Core_LARS_Funding WHERE LearnAimRef='$a09' AND FundingCategory = 'APP_MAY_2017_EM' and EffectiveFrom<='$a27' and (EffectiveTO is null or EffectiveTo>='$a27')");
                    else
                        $xmlarray['total_funding'] = DAO::getSingleValue($link, "SELECT RateWeighted FROM lars201718.Core_LARS_Funding WHERE LearnAimRef='$a09' AND FundingCategory = 'Matrix' and EffectiveFrom<='$a27' and (EffectiveTO is null or EffectiveTo>='$a27')");

                    if($xmlarray['FundModel']=='36')
                        if($xmlarray['StdCode']!='')
                            $xmlarray['LevyCap'] = DAO::getSingleValue($link, "SELECT MaxEmployerLevyCap FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipType = 'STD' AND ApprenticeshipCode='{$xmlarray['StdCode']}' AND EffectiveFrom<='$a27' and (EffectiveTO is null or EffectiveTo>='$a27') ");
                        else
                            $xmlarray['LevyCap'] = DAO::getSingleValue($link, "SELECT MaxEmployerLevyCap FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipType = 'FWK' AND ApprenticeshipCode='{$xmlarray['FworkCode']}' AND ProgType='$ProgType'  and PwayCode='{$xmlarray['PwayCode']}' AND EffectiveFrom<='$a27' and (EffectiveTO is null or EffectiveTo>='$a27')");

                    if($xmlarray['FundModel']=='36' && $xmlarray['qualid']=='ZPROG001' && $the_learning_delivery_age_at_start<19)
                        if($xmlarray['StdCode']!='')
                            $xmlarray['1618FrameworkUplift'] = DAO::getSingleValue($link, "SELECT 1618FrameworkUplift FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipType = 'STD' AND ApprenticeshipCode='{$xmlarray['StdCode']}' LIMIT 0,1");
                        else
                            $xmlarray['1618FrameworkUplift'] = DAO::getSingleValue($link, "SELECT 1618FrameworkUplift FROM lars201718.Core_LARS_ApprenticeshipFunding WHERE ApprenticeshipType = 'FWK' AND ApprenticeshipCode='{$xmlarray['FworkCode']}' and PwayCode='{$xmlarray['PwayCode']}' LIMIT 0,1");
                    else
                        $xmlarray['1618FrameworkUplift'] = 0;

                    if($xmlarray['achiever_periods']=='')// && $xmlarray['LDM']=='125')
                        $xmlarray['achiever_periods'] = $xmlarray['onprogram_periods'];
                    if($xmlarray['aim_achievers']=='')// && $xmlarray['LDM']=='125')
                        $xmlarray['aim_achievers'] = $xmlarray['onprogram_periods'];

                    //
                    $xmlarray['at_risk'] = "" . $at_risk;
                    //
                    $funding2["$d"] = $xmlarray;
                    $d++;
                }
            }
        }

        //if(SOURCE_BLYTHE_VALLEY)
        //pre($funding2);
        return $funding2;
    }

    private function calculate_funding($data)
    {
        return $data['sln'] * $data['ratepersln'] * FundingCore::getWeighting($data['programme_weighting']) * $data['provider_factor'] * $data['funding_remaining_weight'] * $data['proportion'] / 100;
    }
    private function calculate_funding2($data)
    {
        return $data['sln'] * $data['provider_factor'] * $data['funding_remaining_weight'] * $data['proportion'] / 100;
    }

    public function getOtherData($link, $funding)
    {
        $aims = Array();
        $postcodes = Array();
        $home_postcodes = Array();
        $edrsarray = Array();
        $return = Array();
        foreach($funding AS $key => $data)
        {
            if(!in_array("'" . $data['qualid'] . "'", $aims))
                $aims[]	= "'" . $data['qualid'] . "'";

            if(!in_array("'" . $data['postcode'] . "'", $postcodes))
                $postcodes[] = "'" . $data['postcode'] . "'";

            if(!in_array("'" . $data['home_postcode'] . "'", $home_postcodes))
                $home_postcodes[] = "'" . $data['home_postcode'] . "'";

            if(!in_array("'" . $data['edrs'] . "'", $edrsarray))
                $edrsarray[] = "'" . $data['edrs'] . "'";
        }
        $aimsstring = implode(",",$aims);
        $return['aims'] = $aimsstring;
        $poststring = implode(",",$postcodes);
        $return['postcodes'] = $poststring;
        $home_poststring = implode(",",$home_postcodes);
        $return['home_postcodes'] = implode(",",$home_postcodes);
        $edrsstring = implode(",",$edrsarray);
        $return['edrsarray'] = implode(",",$edrsarray);

        $wgts = Array();
        if($aimsstring!='')
        {
            $wgt = "SELECT
			concat(LEARNING_AIM_REF, FUND_MODEL_ILR_SUBSET_CODE) as lar,
			CONCAT(FUND_PWGT_PERIOD_01_VALUE,'-',FUND_PWGT_PERIOD_02_VALUE,'-',FUND_PWGT_PERIOD_03_VALUE,'-',FUND_PWGT_PERIOD_04_VALUE,'-',FUND_PWGT_PERIOD_05_VALUE,'-',FUND_PWGT_PERIOD_06_VALUE,'-',FUND_PWGT_PERIOD_07_VALUE,'-',FUND_PWGT_PERIOD_08_VALUE,'-',FUND_PWGT_PERIOD_09_VALUE,'-',FUND_PWGT_PERIOD_10_VALUE,'-',FUND_PWGT_PERIOD_11_VALUE,'-',FUND_PWGT_PERIOD_12_VALUE) as sln,
			CONCAT(FUND_PWGT_PERIOD_01_PWGT,'-',FUND_PWGT_PERIOD_02_PWGT,'-',FUND_PWGT_PERIOD_03_PWGT,'-',FUND_PWGT_PERIOD_04_PWGT,'-',FUND_PWGT_PERIOD_05_PWGT,'-',FUND_PWGT_PERIOD_06_PWGT,'-',FUND_PWGT_PERIOD_07_PWGT,'-',FUND_PWGT_PERIOD_08_PWGT,'-',FUND_PWGT_PERIOD_09_PWGT,'-',FUND_PWGT_PERIOD_10_PWGT,'-',FUND_PWGT_PERIOD_11_PWGT,'-',FUND_PWGT_PERIOD_12_PWGT) as pw
			FROM lad201314.fund_pwgt_periods
			WHERE LEARNING_AIM_REF IN ($aimsstring) AND FUND_MODEL_ILR_SUBSET_CODE IN ('ER_APP','ER_OTHER')";
            $aimsst = $link->query($wgt);
            while($aimsrow = $aimsst->fetch(PDO::FETCH_ASSOC))
            {
                $wgts[$aimsrow['lar']]['sln'] = explode("-",$aimsrow['sln']);
                $wgts[$aimsrow['lar']]['pw'] = explode("-",$aimsrow['pw']);
            }
            $return['wgts'] = $wgts;
        }

        // Simplified Funding Rates
        $rates = Array();
        $rate_query = "SELECT * FROM central.funding_rates201314 WHERE learning_aim_ref IN ($aimsstring)";
        $aimsst = $link->query($rate_query);
        while($aimsrow = $aimsst->fetch(PDO::FETCH_ASSOC))
        {
            $rates[$aimsrow['learning_aim_ref']] = $aimsrow['rate'];
        }
        $return['rates'] = $rates;
        // End
        $pws = Array();
        $pw = "select * from lad201314.funding_prog_wgts";
        $pwst = $link->query($pw);
        while($pwrow = $pwst->fetch(PDO::FETCH_ASSOC))
        {
            $pws[$pwrow['FUNDING_PROG_WGT_CODE']][$pwrow['FUND_MODEL_ILR_SUBSET_CODE']] = $pwrow['FUNDING_PROG_WGT_DESC'];
        }
        $return['pws'] = $pws;

        // Build Array for Component Type to calculate FEE Proportion (Employer Contribution)
        $ksarray = Array();
        $ksquery = "SELECT LEARNING_AIM_REF, LEARNING_AIM_TYPE_DESC FROM
						lad201314.learning_aim
						LEFT JOIN lad201314.learning_aim_types  ON learning_aim.`LEARNING_AIM_TYPE_CODE` = learning_aim_types.`LEARNING_AIM_TYPE_CODE`
						WHERE learning_aim.`LEARNING_AIM_REF` in ($aimsstring)";

        $ksst = $link->query($ksquery);
        while($ksrow = $ksst->fetch(PDO::FETCH_ASSOC))
        {
            $ksarray[$ksrow['LEARNING_AIM_REF']] = $ksrow['LEARNING_AIM_TYPE_DESC'];
        }
        $return['ksarray'] = $ksarray;

        // Build Array for Distinct Postcodes
        $postcodes = Array();
        $pc = "select * from central.201415postcodeareacost where PostCode in ($poststring);";
        $pcst = $link->query($pc);
        while($pcrow = $pcst->fetch(PDO::FETCH_ASSOC))
        {
            $postcodes[$pcrow['PostCode']]['SFA'] = $pcrow['SFA_AreaCostFactor'];
            $postcodes[$pcrow['PostCode']]['EFA'] = $pcrow['EFA_AreaCostFactor'];
        }
        $return['postcodes'] = $postcodes;

        // Build Array for Distinct Postcodes
        $home_postcodes = Array();
        $home_pc = "select * from central.201415postcodedisadvantage where Postcode in ($home_poststring);";
        $home_pcst = $link->query($home_pc);
        while($home_pcrow = $home_pcst->fetch(PDO::FETCH_ASSOC))
        {
            $home_postcodes[$home_pcrow['Postcode']]['SFA'] = $home_pcrow['SFA_Uplift'];
            $home_postcodes[$home_pcrow['Postcode']]['EFA'] = $home_pcrow['EFA_Uplift'];
        }
        $return['home_postcodes'] = $home_postcodes;


        // Build array for large employer factor
        $large_employer = Array();
        $large_query = "select ERN from central.201415largeemployers where ERN in ($edrsstring)";
        $largest = $link->query($large_query);
        while($largerow = $largest->fetch(PDO::FETCH_ASSOC))
        {
            $large_employer[$largerow['ERN']] = "0.75";
        }
        $return['large_employer'] = $large_employer;

        return $return;
    }

    public static function calculateEFA($link, $learner, $ukprn)
    {
        $total_funding = 0;
        /*
                 *  Historic Retention
                 *  This is a lookup value based on the retention factor used for the 2013-14 allocation passed into the
                 *  calculation and used for the on programme funding element.
                 */
        $the_providers_historic_retention_factor = DAO::getSingleValue($link, "select Org_FundingFactorvalue from lars201314.ORG_Funding where Org_UKPRN='$ukprn' and Org_FundingFactor = 'HISTORIC RETENTION FACTOR'");
        if($the_providers_historic_retention_factor=='')
            $the_providers_historic_retention_factor = 1;
        $the_learners_provider_has_specialist_resources = DAO::getSingleValue($link, "select Org_SpecialResources from lars201314.ORG_Details where Org_UKPRN='$ukprn'");
//        $learners = $ilrs->getElementsByTagName("Learner");
        /*
                 *  Historic Programme Cost Weighting
                 *  This is a lookup value based on the programme cost weighting factor used for the 2013-14 allocation passed
                 *  into the calculation and used for the on programme funding element.
                 */
        $the_providers_programme_weighting = DAO::getSingleValue($link, "select Org_FundingFactorvalue from lars201314.ORG_Funding where Org_UKPRN='$ukprn' and Org_FundingFactor = 'HISTORIC PROGRAMME COST WEIGHTING FACTOR'");
        if($the_providers_programme_weighting!='')
            $the_providers_historic_programme_weighting = $the_providers_programme_weighting;
        else
            $the_providers_historic_programme_weighting = 1;
        /*
                 *  Historic Disadvantage Proportion
                 *  This is a lookup value based on the disadvantage funding (blocik 1 and block 2) from the 2013-14 allocation
                 *  passed into the caluclation and used for the on-programme funding element. This value is calculated as the
                 *  total Block 1 and Block 2 elements of your 2013-14 allocation as a proportion of the total programme funding
                 *  (less disadvantage and before area cost).
                 */
        $the_providers_disadvantage_proportion = DAO::getSingleValue($link, "select Org_FundingFactorvalue from lars201314.ORG_Funding where Org_UKPRN='$ukprn' and Org_FundingFactor = 'HISTORIC DISADVANTAGE FUNDING PROPORTION'");
        if($the_providers_disadvantage_proportion!='')
            $the_providers_historic_disadvantage_proportion = $the_providers_disadvantage_proportion;
        else
            $the_providers_historic_disadvantage_proportion = 0;

        /*
                 * Historic Area Cost
                 * This is a lookup value based on the Area Cost factor used for the 2013-14 allocation passed into the calculation
                 * and used for the on programme funding element.
                 */
        $the_providers_16_18_area_cost_factor = DAO::getSingleValue($link, "select Org_FundingFactorvalue from lars201314.ORG_Funding where Org_UKPRN='$ukprn' and Org_FundingFactor = 'HISTORIC AREA COST FACTOR'");
        if($the_providers_16_18_area_cost_factor!='')
            $the_providers_historic_16_18_area_cost_factor = $the_providers_16_18_area_cost_factor;
        else
            $the_providers_historic_16_18_area_cost_factor = 1;

        if(DB_NAME=='am_crackerjack')
        {
            $the_providers_historic_retention_factor = 1;
            $the_providers_historic_programme_weighting = 1;
            $the_providers_historic_disadvantage_proportion = 0.3360;
            $the_providers_historic_16_18_area_cost_factor = 1;
        }
        elseif(DB_NAME=='am_baltic')
        {
            //$the_providers_historic_retention_factor = 0.86500;
            $the_providers_historic_retention_factor = 0.87900;
            //$the_providers_historic_programme_weighting = 1.05800;
            $the_providers_historic_programme_weighting = 1.06300;
            //$the_providers_historic_disadvantage_proportion = 0.30824;
            $the_providers_historic_disadvantage_proportion = 0.27100;
            $the_providers_historic_16_18_area_cost_factor = 1;
        }
        elseif(DB_NAME=='am_lcurve')
        {
            $the_providers_historic_retention_factor = 0.86500;
            $the_providers_historic_programme_weighting = 1.05800;
            $the_providers_historic_disadvantage_proportion = 0.28388;
            $the_providers_historic_16_18_area_cost_factor = 1;
        }


        $the_first_day_of_the_current_funding_year = "2019-08-01";
        $the_last_day_of_the_current_funding_year = "2020-07-31";
        $the_first_june_of_the_current_funding_year = "2014-06-01";
        $the_funded_hours_per_fte = 600;
        $the_national_rate_for_fll_time_learners = 4000;
        $the_learning_hours_threshold_for_full_time_learners = 540;
        $the_national_rate_for_part_time_band_4_learners = 3300;
        $the_learning_hours_threshold_for_part_time_band_4_learners = 450;
        $the_national_rate_for_part_time_band_3_learners = 2700;
        $the_learning_hours_threshold_for_part_time_band_3_learners = 360;
        $the_national_rate_for_part_time_band_2_learners = 2133;
        $the_learning_hours_threshold_for_part_time_band_2_learners = 280;
        $the_national_rate_per_fte_time_band_1_learners = 4000;
        $the_learning_hours_threshold_for_part_time_band_1_learners = 0;

        $the_learners_source_of_funding = "Other";
        $ldm = false;
        $traineeship_flag = false;
        $DateOfBirth = $learner->DateOfBirth;
        $LearnRefNumber = $learner->LearnRefNumber;

        /*
                 *      Learner Age
                 *      This element derives the learner's age as at 31st August of the academic year in question
                 */
        $age = DAO::getSingleValue($link, "select TIMESTAMPDIFF(YEAR,'$DateOfBirth','2013-08-31') as age");

        //$learningdeliveries = $learner->getElementsByTagName("LearningDelivery");
        $the_learners_start_date = time();
        $the_learners_actual_end_date = '1900-01-01';
        $the_learners_latest_core_aims_start_date = '1900-01-01';
        $the_learners_planned_end_date = '1900-01-01';
        $the_learner_has_a_learning_delivery_started_before_this_year = false;
        $the_learners_number_of_core_aim_records = 0;
        $the_learners_latest_core_aim_sequence_number = 0;
        $for_at_least_one_of_the_learning_deliverys_completion_status_is_1_2_or_6 = false;
        $the_learning_deliverys_programme_weighting = 1;
        foreach($learner->LearningDelivery as $learningdelivery)
        {
            if(strtotime($learningdelivery->LearnStartDate)<strtotime($the_first_day_of_the_current_funding_year))
                $the_learner_has_a_learning_delivery_started_before_this_year = true;

            /*
                         *      Start Date Calculations
                         *      The start date used in planned duration elements is calculated in 2 steps.
                         *      The first step is to pick the earliest of the learning delievereis start date
                         */

            if(strtotime($learningdelivery->LearnStartDate)<$the_learners_start_date)
                $the_learners_start_date = $learningdelivery->LearnStartDate;

            /*
                         *      Actual End Date Caluclations
                         *      The actual end date used in planned duration elements is calculated in a number of steps
                         *      The first step is to use the planned end date if there is no actual end date
                         */

            if($learningdelivery->LearnActEndDate!='')
                $the_learning_delivery_adjusted_actual_end_date = $learningdelivery->LearnActEndDate;
            else
                $the_learning_delivery_adjusted_actual_end_date = $learningdelivery->LearnPlanEndDate;
            /*
                         *      The second step is to pick the latest end date across all the learner's aims.
                         *      The learner's actual end date is the latest of the learning deliveries actual end date
                         *      (or planned end date if the actual end date is unknown)
                         */

            if(strtotime($the_learners_actual_end_date)<strtotime($the_learning_delivery_adjusted_actual_end_date))
                $the_learners_actual_end_date = $the_learning_delivery_adjusted_actual_end_date;

            /*
                         *      Planned End Date Caluclations
                         *      The planned end date used in planned duration elements is calculated in 2 steps
                         *      The first step picks the lates of the learning deliveries plannned end dates.
                         */
            if(strtotime($the_learners_planned_end_date)<strtotime($learningdelivery->LearnPlanEndDate))
                $the_learners_planned_end_date = $learningdelivery->LearnPlanEndDate;

            /*
                         *      Source of Funding
                         *      This element creates a learner level source of funding flag using the 'Learning Delivery Funding
                         *      and Monitoring' entity in the ILR to find the source of funding code. The learner is set to EFA
                         *      where at least one of the learner's aims is EFA funded, where no EFA funded aims are found if the
                         *      learner has at least one SFA funded aim the learner is set to SFA funded otherwise a value of Other
                         *      is returned.
                         */

            foreach($learningdelivery->LearningDeliveryFAM as $learningdeliveryfam)
            {
                if($learningdeliveryfam->LearnDelFAMType=='SOF' && $learningdeliveryfam->LearnDelFAMCode=='105')
                {
                    $the_learners_source_of_funding = "SFA";
                    break;
                }
            }
            foreach($learningdelivery->LearningDeliveryFAM as $learningdeliveryfam)
            {
                if($learningdeliveryfam->LearnDelFAMType=='SOF' && $learningdeliveryfam->LearnDelFAMCode=='107')
                {
                    $the_learners_source_of_funding = "EFA";
                    break;
                }
            }
            foreach($learningdelivery->LearningDeliveryFAM as $learningdeliveryfam)
            {
                if($learningdeliveryfam->LearnDelFAMType=='LDM' && ($learningdeliveryfam->LearnDelFAMCode=='320' || $learningdeliveryfam->LearnDelFAMCode=='321'))
                {
                    $ldm = true;
                }
                if($learningdeliveryfam->LearnDelFAMType=='LDM' && ($learningdeliveryfam->LearnDelFAMCode=='320' || $learningdeliveryfam->LearnDelFAMCode=='323'))
                {
                    $traineeship_flag = true;
                }
            }
            /*
                         *      Core Aim Selection
                         *      There can be more than one core aim in a learner's dataset in one academic year, therefore a set
                         *      of logic is applied to pick the latest core aim in the set. This is achieved in 4 steps.
                         *
                         *      Step 1 identifies the core aim(s) from the learner's aim
                         */

            $the_learning_delivery_is_a_core_aim = false;
            $LearnAimRef = $learningdelivery->LearnAimRef;
            if($learningdelivery->AimType=='5')
            {
                $the_learners_number_of_core_aim_records++;
                $the_learning_delivery_is_a_core_aim = true;
                /*
                                 *      The second step is to pick the latest end date across all the learner's aims.
                                 *      The learner's actual end date is the latest of the learning deliveries actual end date
                                 *      (or planned end date if the actual end date is unknown)
                                 */

                if(strtotime($the_learners_latest_core_aims_start_date)<strtotime($learningdelivery->LearnStartDate))
                {
                    $the_learners_latest_core_aims_start_date = $learningdelivery->LearnStartDate;
                    $the_learners_latest_core_aim_sequence_number = $learningdelivery->AimSeqNumber;
                }
                /*
                                 *  New programme weighting
                                 *  These two elements source the in-year programme cost weighting for each learner calculating an in year
                                 *  value from the core aim recorded in the 2013-14 data. The learning delivery's programme weighting uses
                                 *  the SSA tier 2 code of the core aim recorded in the 2013-14 data. If the learner is academic a default
                                 *  of 1 is set. This element is then used to calculate the learner's new programme weighting where a core
                                 *  aim is recorded. If there is no core aim recorded (as there may not be for academic learner's in the
                                 *  annual school census, the weighting is set to a default value of 1.
                                 *
                                 */
                $ssa_codes1 = Array('03','03.1','03.2','03.3','03.4');
                $ssa_codes2 = Array('03','03.1','03.2','03.3','03.4','04.1','04.2');
                $ssa_codes3 = Array('04','04.3','05','05.1','05.2','06.1','07','07.1','07.3','07.4','09.1','09.2','13','13.1','13.2');

                $the_learning_deliverys_ssa_tier2_code = DAO::getSingleValue($link, "select LARS_SecSubjAreaTier2 from lars201314.LARS_1314 where LARS_LearnAimRef = '$LearnAimRef'");
                if($the_learning_deliverys_ssa_tier2_code=='')
                    $the_learning_deliverys_programme_weighting = 1;
                elseif($the_learners_provider_has_specialist_resources && in_array($the_learning_deliverys_ssa_tier2_code,$ssa_codes1))
                    $the_learning_deliverys_programme_weighting = 1.6;
                elseif(in_array($the_learning_deliverys_ssa_tier2_code,$ssa_codes2))
                    $the_learning_deliverys_programme_weighting = 1.3;
                elseif(in_array($the_learning_deliverys_ssa_tier2_code,$ssa_codes3))
                    $the_learning_deliverys_programme_weighting = 1.2;
                else
                    $the_learning_deliverys_programme_weighting = 1;
            }
            $the_learning_deliverys_learning_aim_type_code = DAO::getSingleValue($link, "select LARS_LrnAimRefType from lars201314.LARS_1314 where LARS_LearnAimRef = '$LearnAimRef'");
            $the_learning_deliverys_learning_aim_title = DAO::getSingleValue($link, "select LARS_LearnAimRefTitle from lars201314.LARS_1314 where LARS_LearnAimRef = '$LearnAimRef'");
            $the_learning_deliverys_awarding_body_code = DAO::getSingleValue($link, "select LARS_AwardOrgResp from lars201314.LARS_1314 where LARS_LearnAimRef = '$LearnAimRef'");

            /*
                         *  General Studies
                         *  This element flags general studies aims for the learning delivery academic flag
                         */

            $the_learning_delivery_is_general_studies = false;
            $codes = Array('0002','1413','1430','1434','1453','0001','1432');
            if($the_learning_deliverys_learning_aim_title!='' && in_array($the_learning_deliverys_learning_aim_type_code,$codes))
                $the_learning_delivery_is_general_studies = true;

            /*
                         *  Uplifts and Factors
                         *  Learning Delivery Academic Flag
                         *
                         *  This element calculates a flag for each aim to determine whether or not it is deemed academic (based on
                         *  the aim type). This flag is used in later steps to determine what programme cost weighting the core aim
                         *  should carry.
                         */

            $codes = Array('0001','0002','1413','1430','1431','1432','1433','1434','1435','1453','0003','1081','1422','2999','1446','1447','1420','1440');
            if($the_learning_deliverys_learning_aim_type_code=='')
                $the_learning_delivery_is_an_academic_aim = false;
            elseif($the_learning_delivery_is_general_studies)
                $the_learning_delivery_is_an_academic_aim = false;
            elseif(in_array($the_learning_deliverys_learning_aim_type_code,$codes))
                $the_learning_delivery_is_an_academic_aim = true;
            elseif(($the_learning_deliverys_awarding_body_code=='IB' && $the_learning_deliverys_learning_aim_type_code=='0016') || $the_learning_deliverys_learning_aim_type_code=='1401')
                $the_learning_delivery_is_an_academic_aim = true;
            else
                $the_learning_delivery_is_an_academic_aim = false;

            /*
                         * Completion Status
                         */

            if($learningdelivery->CompStatus=='1' || $learningdelivery->CompStatus=='2' || $learningdelivery->CompStatus=='6')
                $for_at_least_one_of_the_learning_deliverys_completion_status_is_1_2_or_6 = true;

        }
        /*
                 *      Learner is Studying an Academic Programme
                 *      This element uses the learning delivery academic flag to determine whether or not the learner's core
                 *      aim represents an academic programme or a vocational programme
                 */

        if($the_learners_number_of_core_aim_records == 0)
            $the_learner_is_studying_an_academic_programme = true;
        if($the_learners_number_of_core_aim_records > 0 && $the_learning_delivery_is_an_academic_aim)
            $the_learner_is_studying_an_academic_programme = true;
        else
            $the_learner_is_studying_an_academic_programme = false;


        /*
                 * The second step adjusts the start date to the start of the academic year if it falls before the start of the academic year
                 */
        if($the_learners_actual_end_date!='1900-01-01' && strtotime($the_learners_actual_end_date) < strtotime($the_first_day_of_the_current_funding_year))
            $the_learners_start_date_this_year = "";
        elseif(strtotime($the_learners_start_date) < strtotime($the_first_day_of_the_current_funding_year))
            $the_learners_start_date_this_year = $the_first_day_of_the_current_funding_year;
        elseif(strtotime($the_learners_start_date)<=strtotime($the_last_day_of_the_current_funding_year))
            $the_learners_start_date_this_year = $the_learners_start_date;
        else
            $the_learners_start_date_this_year = "";
        /*
                 *  The third step is to adjust the end date to the end of the academic year if it goes beyond the end of the academic year
                 */

        if(strtotime($the_learners_start_date)>strtotime($the_last_day_of_the_current_funding_year))
            $the_learners_actual_end_date_this_year = '';
        elseif($the_learners_actual_end_date!='1900-01-01' && strtotime($the_learners_actual_end_date)>strtotime($the_last_day_of_the_current_funding_year))
            $the_learners_actual_end_date_this_year = $the_last_day_of_the_current_funding_year;
        elseif($the_learners_actual_end_date!='1900-01-01' && strtotime($the_learners_actual_end_date)>=strtotime($the_first_day_of_the_current_funding_year))
            $the_learners_actual_end_date_this_year = $the_learners_actual_end_date;
        else
            $the_learners_actual_end_date_this_year = '';


        /*
                 * The second step adjusts the planned end date to the end of the academic year if it falls after the end of the academic year
                 */

        if(strtotime($the_learners_start_date)>strtotime($the_last_day_of_the_current_funding_year))
            $the_learners_planned_end_date_this_year = '';
        elseif(strtotime($the_learners_planned_end_date)>strtotime($the_last_day_of_the_current_funding_year))
            $the_learners_planned_end_date_this_year = $the_last_day_of_the_current_funding_year;
        elseif(strtotime($the_learners_planned_end_date)>=  strtotime($the_first_day_of_the_current_funding_year))
            $the_learners_planned_end_date_this_year = $the_learners_planned_end_date;
        else
            $the_learners_planned_end_date_this_year = '';

        /*
                 *      Learner's Planned Days in Funding Year
                 *      This element calculates the learner's planned programme duration as the difference between the learner's
                 *      start date this year and their planned end date this year
                 */

        if($the_learners_start_date_this_year!='' && $the_learners_planned_end_date_this_year!='' && strtotime($the_learners_planned_end_date_this_year)>=strtotime($the_learners_start_date_this_year))
            $the_learners_planned_number_of_days_this_funding_year = DAO::getSingleValue($link, "select DATEDIFF('$the_learners_planned_end_date_this_year','$the_learners_start_date_this_year')+1");
        else
            $the_learners_planned_number_of_days_this_funding_year = 0;




        /*
                 *      Learner's Actual Days in Funding Year
                 *      This element calculates the learner's actual programme duration as the difference between the learner's
                 *      start date this year and their actual end date this year
                 */

        if($the_learners_start_date_this_year!='' && $the_learners_actual_end_date_this_year!='' && strtotime($the_learners_actual_end_date_this_year)>=strtotime($the_learners_start_date_this_year))
            $the_learners_actual_number_of_days_this_funding_year = DAO::getSingleValue($link, "select DATEDIFF('$the_learners_actual_end_date_this_year','$the_learners_start_date_this_year')+1");
        else
            $the_learners_actual_number_of_days_this_funding_year = 0;
        /*
                 *      Date Rules
                 *
                 *      Summer School Students
                 *      Summer school students are not funded by the EFA in 2013-14 and so need to be identified so they can
                 *      be excluded when valid starts are calculated. These are identified as those students who are <= 15 years
                 *      old whose earliest start date falls on or after 1st June of the relevant academic year
                 */

        if($age <= 15 && strtotime($the_learners_start_date) >= strtotime('2013-06-01'))
            $the_learner_is_a_summer_school_student = true;
        else
            $the_learner_is_a_summer_school_student = false;

        $the_learners_total_planned_hours = (int)$learner->PlanLearnHours + (int)$learner->PlanEEPHours;
        $the_learners_fte = $the_learners_total_planned_hours / $the_funded_hours_per_fte;

        /*
                 *      Learner Qualifying Period
                 *      This element calculates the qualifying period of the learner based on the planned duration of their
                 *      programme
                 */

        if($the_learners_total_planned_hours>=450)
            $the_learners_qualifying_period_in_days = 42;
        elseif($the_learners_planned_number_of_days_this_funding_year >= 168)
            $the_learners_qualifying_period_in_days = 42;
        elseif($the_learners_planned_number_of_days_this_funding_year >= 14)
            $the_learners_qualifying_period_in_days = 14;
        else
            $the_learners_qualifying_period_in_days = 0;


        /*
                 *     The Learner is a Valid Start
                 *     The learner is counted as a start this year if their actual learning this year meets the appropriate
                 *     number of threshold days - which is based on the planned learning this year
                 */


        if($the_learners_qualifying_period_in_days>0 && $the_learners_actual_number_of_days_this_funding_year >= $the_learners_qualifying_period_in_days && $the_learner_is_a_summer_school_student == false)
            $the_learner_is_a_start = true;
        else
            $the_learner_is_a_start = false;

        /*
                 * High Needs
                 *
                 */

        $the_learner_is_lda = false;
        $the_learner_is_als = false;
        foreach($learner->LearnerFAM as $learnerfam)
        {
            if($learnerfam->LearnFAMType=='LDA' && $learnerfam->LearnFAMCode=='1')
                $the_learner_is_lda = true;
            elseif($learnerfam->LearnFAMType=='ALS' && $learnerfam->LearnFAMCode=='1')
                $the_learner_is_als = true;
        }
        if($the_learner_is_a_start && (($age>=19 && $the_learner_is_lda) || ($age < 19 && $the_learner_is_als)))
            $the_learner_is_high_needs = true;
        else
            $the_learner_is_high_needs = false;

        /*
                *      Funding Line Type
                *      The section determines under what EFA funding category the learner falls based on the source of funding
                *      and age of the learner.
                */

        if($the_learners_source_of_funding=='EFA' && $ldm && $age>=14 && $age<=15)
            $the_learners_funding_line_type = "14-16 Direct Funded Students";
        elseif($the_learners_source_of_funding=='EFA' && $age<19 && $the_learner_is_high_needs)
            $the_learners_funding_line_type = "16-19 High Needs Students";
        elseif($the_learners_source_of_funding=="EFA" && $age<19 && $the_learner_is_high_needs == false)
            $the_learners_funding_line_type = "16-19 Students (excluding High Needs Students)";
        elseif($the_learners_source_of_funding=="EFA" && $age>=19 && $age <=24 && $the_learner_is_high_needs)
            $the_learners_funding_line_type = "19-24 High Needs Students";
        elseif($the_learners_source_of_funding=="EFA" && $age>=20 && $age <=24 && (!$the_learner_is_lda) && $the_learner_is_als && $the_learner_has_a_learning_delivery_started_before_this_year)
            $the_learners_funding_line_type = "19-24 High Needs Students";
        elseif($the_learners_source_of_funding=="SFA" && $age>=25 && $the_learner_is_high_needs)
            $the_learners_funding_line_type = "25+ High Needs Students";
        elseif($the_learners_source_of_funding=="EFA" && $age>=19 && !$the_learner_is_high_needs)
            $the_learners_funding_line_type = "19+ Continuing Students (excluding high needs students)";
        elseif($the_learners_source_of_funding=="SFA" && $age<19 && $traineeship_flag)
            $the_learners_funding_line_type = "16-18 Traineeships (Non-EFA)";
        elseif($the_learners_source_of_funding=="SFA" && $age<19 && $traineeship_flag)
            $the_learners_funding_line_type = "16-18 Traineeships (Non-EFA)";
        elseif($the_learners_source_of_funding=="SFA" && $age>=19 && $age<=24 && $traineeship_flag)
            $the_learners_funding_line_type = "19-24 Traineeships (Non-EFA)";
        elseif($the_learners_source_of_funding=="SFA")
            $the_learners_funding_line_type = "Adult Skills Funded EFA Model";
        else
            $the_learners_funding_line_type = "Unknown";
        /*
                 *      Learner's Total Planned Hours
                 *      This element returns the sum of the planned learning hours and planned employability, enrichment and
                 *      pastoral hours from the ILR
                 */



        if($the_learners_total_planned_hours>=$the_learning_hours_threshold_for_full_time_learners)
            $the_learners_rate_band = "Full Time (at least 540 hours)";
        elseif($the_learners_total_planned_hours>=$the_learning_hours_threshold_for_part_time_band_4_learners)
            $the_learners_rate_band = "Part Time (450-539 hours)";
        elseif($the_learners_total_planned_hours>=$the_learning_hours_threshold_for_part_time_band_3_learners)
            $the_learners_rate_band = "Part Time (360-449 hours)";
        elseif($the_learners_total_planned_hours>=$the_learning_hours_threshold_for_part_time_band_2_learners)
            $the_learners_rate_band = "Part Time (280-359 hours)";
        elseif($the_learners_total_planned_hours>=$the_learning_hours_threshold_for_part_time_band_1_learners)
            $the_learners_rate_band = "Part Time (up to 279 hours) FTE";
        else
            $the_learners_rate_band = "None";

        if($the_learners_rate_band=="Full Time (at least 540 hours)")
            $the_learners_national_rate = $the_national_rate_for_fll_time_learners;
        elseif($the_learners_rate_band=="Part Time (450-539 hours)")
            $the_learners_national_rate = $the_national_rate_for_part_time_band_4_learners;
        elseif($the_learners_rate_band=="Part Time (360-449 hours)")
            $the_learners_national_rate = $the_national_rate_for_part_time_band_3_learners;
        elseif($the_learners_rate_band=="Part Time (280-359 hours)")
            $the_learners_national_rate = $the_national_rate_for_part_time_band_2_learners;
        elseif($the_learners_rate_band=="Part Time (up to 279 hours) FTE")
            $the_learners_national_rate = $the_national_rate_per_fte_time_band_1_learners * $the_learners_fte;
        else
            $the_learners_national_rate = 0;
        /*
                 *      Learner's Payment Period
                 *      This is the period (1-12) which the payments are allocated to
                 */

        if($the_learner_is_a_start && date('m',strtotime($the_learners_start_date_this_year))<=7)
            $the_learners_payment_period = date('m',strtotime($the_learners_start_date_this_year)) + 5;
        elseif($the_learner_is_a_start && date('m',strtotime($the_learners_start_date_this_year))>=8)
            $the_learners_payment_period = date('m',strtotime($the_learners_start_date_this_year)) - 7;
        else
            $the_learners_payment_period = 0;

        /*
                 *  Learner's new retention status
                 *  This element sources the in-year retention status for each learner calculating an in year value from
                 *  2013-14 data. For academic learners the calculation sets the learner as retained if any of the aims in the
                 *  programme are continuing, completed or on a planned break otherwise the learner is not retained.
                 *  For vocational learners this logic runs only on the core aim
                 */

        if(!$the_learner_is_a_start)
            $the_learners_new_retention_factor = 0;
        elseif($the_learner_is_studying_an_academic_programme && $for_at_least_one_of_the_learning_deliverys_completion_status_is_1_2_or_6)
            $the_learners_new_retention_factor = 1;
        elseif($the_learner_is_studying_an_academic_programme && $for_at_least_one_of_the_learning_deliverys_completion_status_is_1_2_or_6 && $the_learners_number_of_core_aim_records>0)
            $the_learners_new_retention_factor = 1;
        else
            $the_learners_new_retention_factor = 0.5;
        if($the_learner_is_studying_an_academic_programme)
            $the_learning_deliverys_programme_weighting = 1;
        $the_learners_new_programme_weighting = $the_learning_deliverys_programme_weighting;
        /*
                 *  On-Programme Funding
                 *  This element calculates the total funding for the learner.
                 */

        $the_learners_on_programme_funding =
            $the_learners_national_rate
                * $the_providers_historic_retention_factor
                * $the_providers_historic_programme_weighting
                * (1 + $the_providers_historic_disadvantage_proportion)
                * $the_providers_historic_16_18_area_cost_factor;

        if($the_learners_qualifying_period_in_days>0 && $the_learners_actual_number_of_days_this_funding_year >= $the_learners_qualifying_period_in_days)
            $total_funding += $the_learners_on_programme_funding;
        else
            $total_funding = 0;

        return $total_funding;
    }
}
?>