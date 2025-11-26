<?php

class Ilr2016 extends Entity
{
    public function __construct()
    {

    }

    public static function FundingType($xml)
    {
        $xpath = $xml->xpath("/Learner/LearningDelivery/FundModel");
        $funding_model = (!isset($xpath[0]))?'':$xpath[0];

        $xpath = $xml->xpath("/Learner/LearningDelivery/ProgType");
        $prog_type = (!isset($xpath[0]))?'':$xpath[0];

        if($funding_model=='10')
            $funding_type = "Community";
        elseif($funding_model=='25')
            $funding_type = "1619EFA";
        elseif($funding_model=='35')
            $funding_type = "SFA";
        elseif($funding_model=='70')
            $funding_type = "ESF";
        elseif($funding_model=='81')
            $funding_type = "OTHERSFA";
        elseif($funding_model=='82')
            $funding_type = "OTHEREFA";
        elseif($funding_model=='99')
            $funding_type = "NOFUNDING";
        else
            $funding_type = $funding_model;

        return $funding_type;
    }


    public static function loadFromDatabase(PDO $link, $submission, $contract_id, $tr_id, $L03)
    {
        if(is_null($submission) || is_null($contract_id) || is_null($tr_id))
        {
            return null;
        }

        $vo = XML::loadSimpleXML(DAO::getSingleValue($link, "select ilr from ilr WHERE submission='$submission' and contract_id=$contract_id and tr_id = $tr_id and L03='$L03'"));
        return $vo;
    }


    public static function loadFromXML($xml)
    {
        $vo = XML::loadSimpleXML($xml);
        return $vo;
    }

    public static function generateStream4(PDO $link, $submission, $contracts, $con1, $beta = 0)
    {
        set_time_limit(0);
        ini_set('memory_limit','2048M');
        if(is_null($contracts) || is_null($submission))
        {
            return null;
        }

        $l03 = '';

        $no_of_aims = 0;
        $funding_model = DAO::getSingleValue($link, "select funding_body from contracts where id in ($contracts) limit 0,1");

        $sqlouter = "SELECT distinct l03 FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and is_active=1 ORDER BY l03, tr_id";
        $stouter = $link->query($sqlouter);
        if($stouter)
        {
            $batch_file_xml = "";
            // writing header information in data stream file
            $batch_file_xml .= '<?xml version="1.0" encoding="utf-8"?>';
            $batch_file_xml .= '<Message xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns="SFA/ILR/2016-17" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="SFA/ILR/2016-17">';
            $batch_file_xml .= "<Header>";
            $batch_file_xml .= "<CollectionDetails>";
            $batch_file_xml .= "<Collection>ILR</Collection>";
            $batch_file_xml .= "<Year>1617</Year>";
            $batch_file_xml .= "<FilePreparationDate>" .	date("Y-m-d") . "</FilePreparationDate>";
            $batch_file_xml .= "</CollectionDetails>";
            $batch_file_xml .= "<Source>";
            $batch_file_xml .= "<ProtectiveMarking>OFFICIAL-SENSITIVE-Personal</ProtectiveMarking>";
            if($beta == 1)
                $batch_file_xml .= "<UKPRN>99999999</UKPRN>";
            else
                $batch_file_xml .= "<UKPRN>" . $con1->ukprn . "</UKPRN>";
            $batch_file_xml .= "<SoftwareSupplier>Perspective UK Limited</SoftwareSupplier>";
            $batch_file_xml .= "<SoftwarePackage>Sunesis</SoftwarePackage>";
            $batch_file_xml .= "<Release>V 6</Release>";
            $batch_file_xml .= "<SerialNo>1</SerialNo>";
            $batch_file_xml .= "<DateTime>" . date('Y-m-d') . "T" . date('H:i:s') . "</DateTime>";
            $batch_file_xml .= "</Source>";
            $batch_file_xml .= "</Header>";
            $batch_file_xml .= "<LearningProvider>";
            if($beta == 1)
                $batch_file_xml .= "<UKPRN>99999999</UKPRN>";
            else
                $batch_file_xml .= "<UKPRN>" . $con1->ukprn . "</UKPRN>";
            $batch_file_xml .= "</LearningProvider>";

            while($rowouter = $stouter->fetch())
            {
                $l03 = $rowouter['l03'];
                $record=0;
                $AimSeqNumber =0;
                $no_of_aims=0;
                $sql = "SELECT * FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 ORDER BY tr_id desc";
                $destinations = DAO::getSingleValue($link, "SELECT count(*) FROM ilr WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03'");
                $st = $link->query($sql);
                if($st)
                {
                    while($row = $st->fetch())
                    {
                        $ilr = $row['ilr'];
                        $ilr = str_replace("&", "a", $ilr);
                        $record++;
                        $ilr = Ilr2016::loadFromXML($ilr);
                        $funding_type = Ilr2016::FundingType($ilr);
                        if($record==1)
                        {
                            $batch_file_xml .= "<Learner>";
                            $batch_file_xml .= "<LearnRefNumber>" . $ilr->LearnRefNumber . "</LearnRefNumber>";
                            if($ilr->PrevLearnRefNumber!='' && $ilr->PrevLearnRefNumber!='undefined')
                                $batch_file_xml .= "<PrevLearnRefNumber>" . $ilr->PrevLearnRefNumber . "</PrevLearnRefNumber>";
                            if($ilr->PrevUKPRN!='' && $ilr->PrevUKPRN!='undefined')
                                $batch_file_xml .= "<PrevUKPRN>" . $ilr->PrevUKPRN . "</PrevUKPRN>";
                            $batch_file_xml .= "<ULN>" . str_pad($ilr->ULN,10,'9',STR_PAD_LEFT) . "</ULN>";
                            $batch_file_xml .= "<FamilyName>" . trim(str_replace("apos;","'",substr($ilr->FamilyName,0,20))) . "</FamilyName>";
                            $batch_file_xml .= "<GivenNames>" . trim($ilr->GivenNames) . "</GivenNames>";
                            if($ilr->DateOfBirth!='' && $ilr->DateOfBirth!='00000000' && $ilr->DateOfBirth!='dd/mm/yyyy')
                                $batch_file_xml .= "<DateOfBirth>" . Date::toMySQL($ilr->DateOfBirth) . "</DateOfBirth>";
                            $batch_file_xml .= "<Ethnicity>" . str_pad($ilr->Ethnicity,2,'9',STR_PAD_LEFT) . "</Ethnicity>";
                            $batch_file_xml .= "<Sex>" . $ilr->Sex . "</Sex>";
                            $batch_file_xml .= "<LLDDHealthProb>" . str_pad($ilr->LLDDHealthProb,1,'9',STR_PAD_LEFT) . "</LLDDHealthProb>";
                            if($ilr->NINumber!='')
                                $batch_file_xml .= "<NINumber>" . $ilr->NINumber . "</NINumber>";

                            if($ilr->PriorAttain!='')
                                $batch_file_xml .= "<PriorAttain>" . $ilr->PriorAttain . "</PriorAttain>";

                            if($funding_type=="1619EFA")
                            {
                                if($ilr->Accom!='')
                                    $batch_file_xml .= "<Accom>" . $ilr->Accom . "</Accom>";
                                if($ilr->ALSCost!='')
                                    $batch_file_xml .= "<ALSCost>" . $ilr->ALSCost . "</ALSCost>";
                            }


                            $plan_learn_hours = DAO::getSingleColumn($link,"SELECT extractvalue(ilr,'/Learner/PlanLearnHours') FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 ORDER BY tr_id desc");
                            $plan_learn_hours = array_sum($plan_learn_hours);

                            $plan_eep_hours = DAO::getSingleColumn($link,"SELECT extractvalue(ilr,'/Learner/PlanEEPHours') FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 ORDER BY tr_id desc");
                            $plan_eep_hours = array_sum($plan_eep_hours);

                            if(($plan_learn_hours!='' && $plan_learn_hours!='0') || ($plan_eep_hours!='' && $plan_eep_hours!='0'))
                            {
                                $batch_file_xml .= "<PlanLearnHours>" . $plan_learn_hours . "</PlanLearnHours>";
                                $batch_file_xml .= "<PlanEEPHours>" . $plan_eep_hours . "</PlanEEPHours>";
                            }

                            if($ilr->MathGrade!='')
                                $batch_file_xml .= "<MathGrade>" . $ilr->MathGrade . "</MathGrade>";
                            if($ilr->EngGrade!='')
                                $batch_file_xml .= "<EngGrade>" . $ilr->EngGrade . "</EngGrade>";

                            $xpath = $ilr->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode");
                            if(!empty($xpath))
                                $batch_file_xml .= "<LearnerContact><LocType>2</LocType><ContType>1</ContType><PostCode>" . strtoupper(trim($xpath[0])) . "</PostCode></LearnerContact>";

                            $add1 = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine1');
                            $add2 = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine2');
                            $add3 = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine3');
                            $add4 = $ilr->xpath('/Learner/LearnerContact/PostAdd/AddLine4');
                            if(!empty($add1) || !empty($add2) || !empty($add3) || !empty($add1))
                                if($add1[0]!='' || $add2[0]!='' || $add3[0]!='' || $add4[0]!='')
                                {
                                    $batch_file_xml .= "<LearnerContact><LocType>1</LocType><ContType>2</ContType><PostAdd>";
                                    if(!empty($add1) && $add1[0]!='')
                                        $batch_file_xml .= "<AddLine1>" . substr($add1[0],0,30) . "</AddLine1>";

                                    if(!empty($add2) && $add2[0]!='')
                                        $batch_file_xml .= "<AddLine2>" . $add2[0] . "</AddLine2>";

                                    if(!empty($add3) && $add3[0]!='')
                                        $batch_file_xml .= "<AddLine3>" . $add3[0] . "</AddLine3>";

                                    if(!empty($add4) && $add4[0]!='')
                                        $batch_file_xml .= "<AddLine4>" . $add4[0] . "</AddLine4>";
                                    $batch_file_xml .= "</PostAdd></LearnerContact>";
                                }

                            $xpath = $ilr->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode");
                            if(!empty($xpath))
                                $batch_file_xml .= "<LearnerContact><LocType>2</LocType><ContType>2</ContType><PostCode>" . strtoupper(trim($xpath[0])) . "</PostCode></LearnerContact>";

                            $xpath = $ilr->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email");
                            if(!empty($xpath[0]))
                                $batch_file_xml .= "<LearnerContact><LocType>4</LocType><ContType>2</ContType><Email>" . $xpath[0] . "</Email></LearnerContact>";

                            $xpath = $ilr->xpath('/Learner/LearnerContact/TelNumber');
                            if(!empty($xpath) && $xpath[0]!='')
                                $batch_file_xml .= "<LearnerContact><LocType>3</LocType><ContType>2</ContType><TelNumber>" . str_replace(" ","",$xpath[0]) . "</TelNumber></LearnerContact>";

                            $rui1 = $rui2 = $rui3 = '';
                            $xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='1']/ContPrefCode"));
                            if(isset($xpath[0]))
                                $rui1 = $xpath[0];
                            $xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='2']/ContPrefCode"));
                            if(isset($xpath[0]))
                                $rui2 = $xpath[0];
                            $xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='3']/ContPrefCode"));
                            if(isset($xpath[0]))
                                $rui3 = $xpath[0];
                            if($rui1=='1')
                                $batch_file_xml .= "<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>1</ContPrefCode></ContactPreference>";
                            elseif($rui2=='2')
                                $batch_file_xml .= "<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>2</ContPrefCode></ContactPreference>";


                            if($rui3!='3' && $rui1!='1' && $rui2!='2')
                            {
                                $xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='1']/ContPrefCode"));
                                $pmc1 = (!isset($xpath[0]))?'':$xpath[0];
                                $xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='2']/ContPrefCode"));
                                $pmc2 = (!isset($xpath[0]))?'':$xpath[0];
                                $xpath = ($ilr->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='3']/ContPrefCode"));
                                $pmc3 = (!isset($xpath[0]))?'':$xpath[0];

                                if($pmc1=='1')
                                    $batch_file_xml .= "<ContactPreference><ContPrefType>PMC</ContPrefType><ContPrefCode>1</ContPrefCode></ContactPreference>";

                                if($pmc2=='2')
                                    $batch_file_xml .= "<ContactPreference><ContPrefType>PMC</ContPrefType><ContPrefCode>2</ContPrefCode></ContactPreference>";

                                if($pmc3=='3')
                                    $batch_file_xml .= "<ContactPreference><ContPrefType>PMC</ContPrefType><ContPrefCode>3</ContPrefCode></ContactPreference>";
                            }

                            foreach($ilr->LLDDandHealthProblem as $LLDDandHealthProblem)
                            {
                                if($LLDDandHealthProblem->LLDDCat != '')
                                {
                                    $batch_file_xml .= "<LLDDandHealthProblem>";
                                    $batch_file_xml .= "<LLDDCat>" . $LLDDandHealthProblem->LLDDCat . "</LLDDCat>";
                                    if($LLDDandHealthProblem->PrimaryLLDD != '')
                                    {
                                        $batch_file_xml .= "<PrimaryLLDD>" . $LLDDandHealthProblem->PrimaryLLDD . "</PrimaryLLDD>";
                                    }
                                    $batch_file_xml .= "</LLDDandHealthProblem>";
                                }
                            }

                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
                            if(!empty($xpath[0]) && $xpath[0]!='99' && $xpath[0]!='41')
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";
                            if(!empty($xpath[1]) && $xpath[1]!='99' && $xpath[0]!='41')
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $xpath[1]  . "</LearnFAMCode></LearnerFAM>";
                            if(!empty($xpath[2])  && $xpath[2]!='99' && $xpath[0]!='41')
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $xpath[2]  . "</LearnFAMCode></LearnerFAM>";
                            if(!empty($xpath[3]) && $xpath[3]!='99' && $xpath[0]!='41')
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $xpath[3]  . "</LearnFAMCode></LearnerFAM>";

                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LDA']/LearnFAMCode");
                            if(!empty($xpath[0]) && $xpath[0]!='99')
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>LDA</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";
                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='HNS']/LearnFAMCode");
                            if(!empty($xpath[0]))
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>HNS</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";
                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='EHC']/LearnFAMCode");
                            if(!empty($xpath[0]) && $xpath[0]!='99')
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>EHC</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";
                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='DLA']/LearnFAMCode");
                            if(!empty($xpath[0]) && $xpath[0]!='99')
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>DLA</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";
                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='SEN']/LearnFAMCode");
                            if(!empty($xpath[0]) && $xpath[0]!='99')
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>SEN</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";
                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='HNS']/LearnFAMCode");
                            if(!empty($xpath[0]) && $xpath[0]!='99')
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>HNS</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";
                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='FME']/LearnFAMCode");
                            if(!empty($xpath[0]))
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>FME</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";
                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='MCF']/LearnFAMCode");
                            if(!empty($xpath[0]))
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>MCF</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";
                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='ECF']/LearnFAMCode");
                            if(!empty($xpath[0]))
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>ECF</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";

                            $EDF1 = DAO::getSingleColumn($link,"SELECT extractvalue(ilr,'/Learner/LearnerFAM[LearnFAMType=\"EDF\" and LearnFAMCode=\"1\"]/LearnFAMCode') FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 ORDER BY tr_id desc");
                            $EDF1 = array_sum($EDF1);
                            if($EDF1>=1)
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>EDF</LearnFAMType><LearnFAMCode>1</LearnFAMCode></LearnerFAM>";

                            $EDF2 = DAO::getSingleColumn($link,"SELECT extractvalue(ilr,'/Learner/LearnerFAM[LearnFAMType=\"EDF\" and LearnFAMCode=\"2\"]/LearnFAMCode') FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 ORDER BY tr_id desc");
                            $EDF2 = array_sum($EDF2);
                            if($EDF2>=2)
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>EDF</LearnFAMType><LearnFAMCode>2</LearnFAMCode></LearnerFAM>";

                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='PPE']/LearnFAMCode");
                            if(!empty($xpath[0]))
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>PPE</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";
                            if(!empty($xpath[1]))
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>PPE</LearnFAMType><LearnFAMCode>" . $xpath[1]  . "</LearnFAMCode></LearnerFAM>";
                            $xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
                            if(!empty($xpath[0]) && $xpath[0]!='99')
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $xpath[0]  . "</LearnFAMCode></LearnerFAM>";
                            if(!empty($xpath[1]) && $xpath[1]!='99')
                                $batch_file_xml .= "<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $xpath[1]  . "</LearnFAMCode></LearnerFAM>";

                            $xpath = $ilr->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon");
                            if(!empty($xpath) && trim($xpath[0])!='')
                                $batch_file_xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>A</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $xpath[0]  . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";
                            $xpath = $ilr->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon");
                            if(!empty($xpath) && trim($xpath[0])!='')
                                $batch_file_xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>B</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $xpath[0]  . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";

                            $sqlemp = "SELECT * FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 ORDER BY tr_id";
                            $stemp = $link->query($sqlemp);
                            if($stemp)
                            {
                                $Duplicate = Array();
                                $EmploymentRecord = Array();
                                while($rowemp = $stemp->fetch())
                                {
                                    $ilremp = $rowemp['ilr'];
                                    $ilremp = str_replace("&", "a", $ilremp);
                                    $ilremp = Ilr2016::loadFromXML($ilremp);
                                    foreach($ilremp->LearnerEmploymentStatus as $empstatusemp)
                                    {
                                        $DateEmpStatApp = Date::toMySQL("".$empstatusemp->DateEmpStatApp);
                                        $EmpId = "" . $empstatusemp->EmpId;
                                        $EmpId = trim(substr($EmpId,0,9));
                                        $EmpStat = "" . $empstatusemp->EmpStat;
                                        $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='SEI']/ESMCode");
                                        $SEI = (!empty($xpath[0]))?$xpath[0]:"NULL";
                                        $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='EII']/ESMCode");
                                        $EII = (!empty($xpath[0]))?$xpath[0]:"NULL";
                                        $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='LOU']/ESMCode");
                                        $LOU = (!empty($xpath[0]))?$xpath[0]:"NULL";
                                        $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='LOE']/ESMCode");
                                        $LOE = (!empty($xpath[0]))?$xpath[0]:"NULL";
                                        $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='BSI']/ESMCode");
                                        $BSI = (!empty($xpath[0]))?$xpath[0]:"NULL";
                                        $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='PEI']/ESMCode");
                                        $PEI = (!empty($xpath[0]))?$xpath[0]:"NULL";
                                        $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='RON']/ESMCode");
                                        $RON = (!empty($xpath[0]))?$xpath[0]:"NULL";
                                        $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='SEM']/ESMCode");
                                        $SEM = (!empty($xpath[0]))?$xpath[0]:"NULL";
                                        $EmpRec = "EmpId=" . $EmpId . "-EmpStat=" . $EmpStat . "-SEI=" . $SEI . "-EII=" . $EII . "-LOU=" . $LOU . "-LOE=" . $LOE . "-BSI=" . $BSI . "-PEI=" . $PEI . "-RON=" . $RON . "-SEM=" . $SEM;
                                        if(("".$empstatusemp->EmpStat)!='' && ("".$empstatusemp->DateEmpStatApp)!='' && ("".$empstatusemp->DateEmpStatApp)!='dd/mm/yyyy' && (!in_array(("".$empstatusemp->DateEmpStatApp),$Duplicate)) && (sizeof($EmploymentRecord)==0 || $EmpRec!=$EmploymentRecord[sizeof($EmploymentRecord)-1]))
                                        {
                                            $batch_file_xml .= "<LearnerEmploymentStatus>";
                                            $batch_file_xml .= "<EmpStat>" . $EmpStat . "</EmpStat>";
                                            if($empstatusemp->DateEmpStatApp!='' && $empstatusemp->DateEmpStatApp!='dd/mm/yyyy')
                                                $batch_file_xml .= "<DateEmpStatApp>" . $DateEmpStatApp . "</DateEmpStatApp>";
                                            if($EmpId!='')
                                                $batch_file_xml .= "<EmpId>" . $EmpId . "</EmpId>";
                                            $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='SEI']/ESMCode");
                                            if(!empty($xpath[0]))
                                                $batch_file_xml .= "<EmploymentStatusMonitoring><ESMType>SEI</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>";
                                            $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='EII']/ESMCode");
                                            if(!empty($xpath[0]))
                                                $batch_file_xml .= "<EmploymentStatusMonitoring><ESMType>EII</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>";
                                            $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='LOU']/ESMCode");
                                            if(!empty($xpath[0]))
                                                $batch_file_xml .= "<EmploymentStatusMonitoring><ESMType>LOU</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>";
                                            $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='LOE']/ESMCode");
                                            if(!empty($xpath[0]))
                                                $batch_file_xml .= "<EmploymentStatusMonitoring><ESMType>LOE</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>";
                                            $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='BSI']/ESMCode");
                                            if(!empty($xpath[0]))
                                                $batch_file_xml .= "<EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>";
                                            $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='PEI']/ESMCode");
                                            if(!empty($xpath[0]))
                                                $batch_file_xml .= "<EmploymentStatusMonitoring><ESMType>PEI</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>";
                                            $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='RON']/ESMCode");
                                            if(!empty($xpath[0]))
                                                $batch_file_xml .= "<EmploymentStatusMonitoring><ESMType>RON</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>";
                                            $xpath = $empstatusemp->xpath("./EmploymentStatusMonitoring[ESMType='SEM']/ESMCode");
                                            if(!empty($xpath[0]))
                                                $batch_file_xml .= "<EmploymentStatusMonitoring><ESMType>SEM</ESMType><ESMCode>" . $xpath[0] . "</ESMCode></EmploymentStatusMonitoring>";
                                            $batch_file_xml .= "</LearnerEmploymentStatus>";

                                            $Duplicate[] = ("".$empstatusemp->DateEmpStatApp);
                                            $EmploymentRecord[] = $EmpRec;
                                        }
                                    }
                                }
                            }
                            $LearnerHE = "";
                            if($ilr->LearnerHE->UCASPERID != "undefined" && $ilr->LearnerHE->UCASPERID != '')
                                $LearnerHE .= "<UCASPERID>" . $ilr->LearnerHE->UCASPERID . "</UCASPERID>";
                            if($ilr->LearnerHE->TTACCOM != "undefined" && $ilr->LearnerHE->TTACCOM != '')
                                $LearnerHE .= "<TTACCOM>" . $ilr->LearnerHE->TTACCOM . "</TTACCOM>";
                            if($ilr->LearnerHE->FINAMOUNT1 != "undefined" && $ilr->LearnerHE->FINAMOUNT1 != '')
                                $LearnerHE .= "<LearnerHEFinancialSupport><FINTYPE>1</FINTYPE><FINAMOUNT>" . $ilr->LearnerHE->FINAMOUNT1 . "</FINAMOUNT></LearnerHEFinancialSupport>";
                            if($ilr->LearnerHE->FINAMOUNT2 != "undefined" && $ilr->LearnerHE->FINAMOUNT2 != '')
                                $LearnerHE .= "<LearnerHEFinancialSupport><FINTYPE>2</FINTYPE><FINAMOUNT>" . $ilr->LearnerHE->FINAMOUNT2 . "</FINAMOUNT></LearnerHEFinancialSupport>";
                            if($ilr->LearnerHE->FINAMOUNT3 != "undefined" && $ilr->LearnerHE->FINAMOUNT3 != '')
                                $LearnerHE .= "<LearnerHEFinancialSupport><FINTYPE>3</FINTYPE><FINAMOUNT>" . $ilr->LearnerHE->FINAMOUNT3 . "</FINAMOUNT></LearnerHEFinancialSupport>";
                            if($ilr->LearnerHE->FINAMOUNT4 != "undefined" && $ilr->LearnerHE->FINAMOUNT4 != '')
                                $LearnerHE .= "<LearnerHEFinancialSupport><FINTYPE>4</FINTYPE><FINAMOUNT>" . $ilr->LearnerHE->FINAMOUNT1 . "</FINAMOUNT></LearnerHEFinancialSupport>";
                            if($LearnerHE != "")
                                $batch_file_xml .= '<LearnerHE>' . $LearnerHE . '</LearnerHE>';
                        }
                        foreach($ilr->LearningDelivery as $delivery)
                        {
                            $AimSeqNumber++;
                            $batch_file_xml .= "<LearningDelivery>";
                            $batch_file_xml .= "<LearnAimRef>" . strtoupper($delivery->LearnAimRef) . "</LearnAimRef>";
                            if($delivery->AimType!='')
                                $batch_file_xml .= "<AimType>" . $delivery->AimType . "</AimType>";
                            $batch_file_xml .= "<AimSeqNumber>" . $AimSeqNumber . "</AimSeqNumber>";
                            if($delivery->LearnStartDate!='' && $delivery->LearnStartDate!='dd/mm/yyyy')
                                $batch_file_xml .= "<LearnStartDate>" . Date::toMySQL($delivery->LearnStartDate) . "</LearnStartDate>";
                            if($delivery->OrigLearnStartDate!='' && $delivery->OrigLearnStartDate!='undefined' && $delivery->OrigLearnStartDate!='dd/mm/yyyy')
                                $batch_file_xml .= "<OrigLearnStartDate>" . Date::toMySQL($delivery->OrigLearnStartDate) . "</OrigLearnStartDate>";
                            if($delivery->LearnPlanEndDate!='' && $delivery->LearnPlanEndDate!='dd/mm/yyyy')
                                $batch_file_xml .= "<LearnPlanEndDate>" . Date::toMySQL($delivery->LearnPlanEndDate) . "</LearnPlanEndDate>";
                            if($delivery->FundModel!='')
                                $batch_file_xml .= "<FundModel>" . $delivery->FundModel . "</FundModel>";
                            if($delivery->FundModel!='10' && $delivery->ProgType!='' && $delivery->ProgType!='99')
                                $batch_file_xml .= "<ProgType>" . $delivery->ProgType . "</ProgType>";
                            if($delivery->FworkCode!='' && $delivery->FworkCode!='undefined' && $delivery->FundModel!='10' && $delivery->ProgType!='99' && $delivery->ProgType!='')
                                $batch_file_xml .= "<FworkCode>" . $delivery->FworkCode . "</FworkCode>";
                            if($delivery->PwayCode!='' && $delivery->PwayCode!='undefined' && $delivery->FundModel!='10' && $delivery->FundModel!='21' && $delivery->FundModel!='22' && $delivery->ProgType!='99' && $delivery->ProgType!='')
                                $batch_file_xml .= "<PwayCode>" . $delivery->PwayCode . "</PwayCode>";
                            elseif($delivery->FundModel=='35' && $delivery->ProgType!='24' && ($delivery->AimType=='1' || $delivery->AimType=='2' || $delivery->AimType=='3'))
                                $batch_file_xml .= "<PwayCode>0</PwayCode>";
                            if($delivery->StdCode!='' && $delivery->StdCode!='undefined')
                                $batch_file_xml .= "<StdCode>" . $delivery->StdCode . "</StdCode>";
                            if($delivery->PartnerUKPRN!='' && $delivery->PartnerUKPRN!='undefined' && ($delivery->AimType=='3' or $delivery->AimType=='4' or $delivery->AimType=='5'))
                                $batch_file_xml .= "<PartnerUKPRN>" . $delivery->PartnerUKPRN . "</PartnerUKPRN>";
                            $batch_file_xml .= "<DelLocPostCode>" . strtoupper(trim($delivery->DelLocPostCode)) . "</DelLocPostCode>";
                            if($delivery->AddHours !='' && $delivery->AddHours !='undefined')
                                $batch_file_xml .= "<AddHours>" . $delivery->AddHours . "</AddHours>";
                            if($delivery->PriorLearnFundAdj!='' && $delivery->PriorLearnFundAdj!='undefined')
                                $batch_file_xml .= "<PriorLearnFundAdj>" . $delivery->PriorLearnFundAdj . "</PriorLearnFundAdj>";
                            if($delivery->OtherFundAdj!='' && $delivery->OtherFundAdj!='undefined')
                                $batch_file_xml .= "<OtherFundAdj>" . $delivery->OtherFundAdj . "</OtherFundAdj>";
                            if($delivery->ConRefNumber !='' && $delivery->ConRefNumber !='undefined')
                                $batch_file_xml .= "<ConRefNumber>" . $delivery->ConRefNumber . "</ConRefNumber>";
                            if($delivery->EmpOutcome!='' && $delivery->EmpOutcome!='undefined' && $delivery->AimType!='1' && $delivery->FundModel!='21' && $delivery->FundModel!='10' && $delivery->FundModel!='99' ) {
                                $batch_file_xml .= "<EmpOutcome>" . $delivery->EmpOutcome . "</EmpOutcome>";
                            }
                            if( $delivery->CompStatus!='' ) {
                                $batch_file_xml .= "<CompStatus>" . $delivery->CompStatus . "</CompStatus>";
                            }
                            if( $delivery->LearnActEndDate!='' && $delivery->LearnActEndDate!='dd/mm/yyyy' ) {
                                $batch_file_xml .= "<LearnActEndDate>" . Date::toMySQL($delivery->LearnActEndDate) . "</LearnActEndDate>";
                            }
                            if( $delivery->WithdrawReason!='' && $delivery->CompStatus=='3') {
                                $batch_file_xml .= "<WithdrawReason>" . $delivery->WithdrawReason . "</WithdrawReason>";
                            }
                            if( $delivery->Outcome!='' && $delivery->Outcome!='undefined' ) {
                                $batch_file_xml .= "<Outcome>" . $delivery->Outcome . "</Outcome>";
                            }
                            if( $delivery->AchDate != '' && $delivery->AchDate != 'dd/mm/yyyy' && ($delivery->ProgType == '24' || $delivery->ProgType == '25') ) {
                                $batch_file_xml .= "<AchDate>" . Date::toMySQL($delivery->AchDate) . "</AchDate>";
                            }
                            if( $delivery->AimType!='1' && $delivery->OutGrade!='' && $delivery->OutGrade != 'undefined' )
                            {
                                $batch_file_xml .= "<OutGrade>" . $delivery->OutGrade . "</OutGrade>";
                            }

                            $sof = '';
                            $done = false;
                            if($delivery->FundModel!='99')
                                if((($delivery->FundModel=='36' || $delivery->FundModel=='35' || $delivery->FundModel=='25' || $delivery->FundModel=='70' || $delivery->FundModel=='81') && $delivery->ProgType!='99') || ($delivery->ProgType=='99')){
                                    $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode"); $sof = (empty($xpath[0]))?'':$xpath[0];}
                            if($sof!='' && $sof!='undefined')
                                $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" . $sof . "</LearnDelFAMCode></LearningDeliveryFAM>";

                            $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
                            if($ffi!='' && $ffi!='undefined' && $delivery->FundModel!='99')
                                $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode>" . $ffi . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FLN']/LearnDelFAMCode"); $fln = (empty($xpath[0]))?'':$xpath[0];
                            if($fln !='')
                                $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>FLN</LearnDelFAMType><LearnDelFAMCode>" . $fln . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='NSA']/LearnDelFAMCode"); $nsa = (empty($xpath[0]))?'':$xpath[0];
                            if($nsa!='' && $nsa!='undefined')
                                $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>NSA</LearnDelFAMType><LearnDelFAMCode>" . $nsa . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='EEF']/LearnDelFAMCode"); $eef = (empty($xpath[0]))?'':$xpath[0];
                            if($eef!='' && $eef!='undefined')
                                $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>EEF</LearnDelFAMType><LearnDelFAMCode>" . $eef . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            foreach($delivery->LearningDeliveryFAM as $ldm)
                                if($ldm->LearnDelFAMType=='LDM' && $ldm->LearnDelFAMCode!='' && $ldm->LearnDelFAMCode!='undefined' && $ldm->LearnDelFAMCode!='98')
                                    $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" . $ldm->LearnDelFAMCode . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SPP']/LearnDelFAMCode"); $spp = (empty($xpath[0]))?'':$xpath[0];
                            if($spp!='' && $spp!='undefined')
                                $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>SPP</LearnDelFAMType><LearnDelFAMCode>" . $spp . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode"); $res = (empty($xpath[0]))?'':$xpath[0];
                            if($res!='')
                                $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>RES</LearnDelFAMType><LearnDelFAMCode>" . $res . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ADL']/LearnDelFAMCode"); $adl = (empty($xpath[0]))?'':$xpath[0];
                            if($adl!='' && $adl!='undefined')
                                $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>ADL</LearnDelFAMType><LearnDelFAMCode>" . $adl . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ASL']/LearnDelFAMCode"); $asl = (empty($xpath[0]))?'':$xpath[0];
                            if($asl!='' && $asl!='undefined')
                                $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>ASL</LearnDelFAMType><LearnDelFAMCode>" . $asl . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='POD']/LearnDelFAMCode"); $pod = (empty($xpath[0]))?'':$xpath[0];
                            if($pod!='' && $pod!='undefined')
                                $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>POD</LearnDelFAMType><LearnDelFAMCode>" . $pod . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            foreach($delivery->LearningDeliveryFAM as $lsf)
                            {
                                if($lsf->LearnDelFAMType=='LSF' && ("".$lsf->LearnDelFAMDateFrom)!='' && ("".$lsf->LearnDelFAMDateTo)!='')
                                {
                                    $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>LSF</LearnDelFAMType><LearnDelFAMCode>" . $lsf->LearnDelFAMCode . "</LearnDelFAMCode>";
                                    $batch_file_xml .= "<LearnDelFAMDateFrom>" . Date::toMySQL($lsf->LearnDelFAMDateFrom) . "</LearnDelFAMDateFrom>";
                                    $batch_file_xml .= "<LearnDelFAMDateTo>" . Date::toMySQL($lsf->LearnDelFAMDateTo) . "</LearnDelFAMDateTo>";
                                    $batch_file_xml .= "</LearningDeliveryFAM>";
                                }
                            }
                            foreach($delivery->LearningDeliveryFAM as $alb)
                            {
                                if($alb->LearnDelFAMType == 'ALB' && ("" . $alb->LearnDelFAMDateFrom) !='' && ("" . $alb->LearnDelFAMDateTo) !='')
                                {
                                    $batch_file_xml .= "<LearningDeliveryFAM>";
                                    $batch_file_xml .= "<LearnDelFAMType>ALB</LearnDelFAMType><LearnDelFAMCode>" . $alb->LearnDelFAMCode . "</LearnDelFAMCode>";
                                    $batch_file_xml .= "<LearnDelFAMDateFrom>" . Date::toMySQL($alb->LearnDelFAMDateFrom) . "</LearnDelFAMDateFrom>";
                                    $batch_file_xml .= "<LearnDelFAMDateTo>" . Date::toMySQL($alb->LearnDelFAMDateTo) . "</LearnDelFAMDateTo>";
                                    $batch_file_xml .= "</LearningDeliveryFAM>";

                                }
                            }
                            foreach($delivery->LearningDeliveryFAM as $alb)
                            {
                                if($alb->LearnDelFAMType == 'ACT' && ("" . $alb->LearnDelFAMDateFrom) !='' && ("" . $alb->LearnDelFAMDateTo) !='')
                                {
                                    $batch_file_xml .= "<LearningDeliveryFAM>";
                                    $batch_file_xml .= "<LearnDelFAMType>ACT</LearnDelFAMType><LearnDelFAMCode>" . $alb->LearnDelFAMCode . "</LearnDelFAMCode>";
                                    $batch_file_xml .= "<LearnDelFAMDateFrom>" . Date::toMySQL($alb->LearnDelFAMDateFrom) . "</LearnDelFAMDateFrom>";
                                    $batch_file_xml .= "<LearnDelFAMDateTo>" . Date::toMySQL($alb->LearnDelFAMDateTo) . "</LearnDelFAMDateTo>";
                                    $batch_file_xml .= "</LearningDeliveryFAM>";

                                }
                            }

                            if($delivery->AimType=="1" || $delivery->AimType=="4")
                            {
                                foreach($delivery->LearningDeliveryFAM AS $hhs)
                                    if($hhs->LearnDelFAMType=='HHS' && $hhs->LearnDelFAMCode!='' && $hhs->LearnDelFAMCode!='undefined')
                                        $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>HHS</LearnDelFAMType><LearnDelFAMCode>" . $hhs->LearnDelFAMCode . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            }
                            foreach($delivery->LearningDeliveryFAM as $hem)
                            {
                                if($hem->LearnDelFAMType=='HEM')
                                    $batch_file_xml .= "<LearningDeliveryFAM><LearnDelFAMType>HEM</LearnDelFAMType><LearnDelFAMCode>" . $hem->LearnDelFAMCode . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            }
                            if($delivery->LearnAimRef=='Z0007834' || $delivery->LearnAimRef=='Z0007835' || $delivery->LearnAimRef=='Z0007836' || $delivery->LearnAimRef=='Z0007837' || $delivery->LearnAimRef=='Z0007838')
                            {
                                foreach( $delivery->LearningDeliveryWorkPlacement as $ldwp)
                                {
                                    if($ldwp->WorkPlaceStartDate!='')
                                    {
                                        $batch_file_xml .= "<LearningDeliveryWorkPlacement>";
                                        $batch_file_xml .= "<WorkPlaceStartDate>" . Date::toMySQL($ldwp->WorkPlaceStartDate) . "</WorkPlaceStartDate>";
                                        if($ldwp->WorkPlaceEndDate!='')
                                            $batch_file_xml .= "<WorkPlaceEndDate>" . Date::toMySQL($ldwp->WorkPlaceEndDate) . "</WorkPlaceEndDate>";
                                        if($ldwp->WorkPlaceMode!='')
                                            $batch_file_xml .= "<WorkPlaceMode>". $ldwp->WorkPlaceMode."</WorkPlaceMode>";
                                        if($ldwp->WorkPlaceEmpId!='')
                                            $batch_file_xml .= "<WorkPlaceEmpId>".$ldwp->WorkPlaceEmpId."</WorkPlaceEmpId>";
                                        $batch_file_xml .= "</LearningDeliveryWorkPlacement>";
                                    }
                                }
                            }
                            if($delivery->AimType=="1")
                            {
                                foreach($delivery->TrailblazerApprenticeshipFinancialRecord as $ldwp)
                                {
                                    if($ldwp->TBFinType!='')
                                    {
                                        $batch_file_xml .= "<TrailblazerApprenticeshipFinancialRecord>";
                                        $batch_file_xml .= "<TBFinType>".$ldwp->TBFinType."</TBFinType>";
                                        if($ldwp->TBFinCode !='')
                                            $batch_file_xml .= "<TBFinCode>".$ldwp->TBFinCode ."</TBFinCode>";
                                        if($ldwp->TBFinDate !='' && $ldwp->TBFinDate != 'dd/mm/yyyy')
                                            $batch_file_xml .= "<TBFinDate>". Date::toMySQL($ldwp->TBFinDate)."</TBFinDate>";
                                        if($ldwp->TBFinAmount !='')
                                            $batch_file_xml .= "<TBFinAmount>".$ldwp->TBFinAmount."</TBFinAmount>";
                                        $batch_file_xml .= "</TrailblazerApprenticeshipFinancialRecord>";
                                    }
                                }
                            }
                            $xpath = $delivery->xpath("/Learner/LearningDelivery/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon");
                            if(!empty($xpath) && trim($xpath[0])!='')
                                $batch_file_xml .= "<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>A</ProvSpecDelMonOccur><ProvSpecDelMon>" . $xpath[0]  . "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
                            $xpath = $delivery->xpath("/Learner/LearningDelivery/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon");
                            if(!empty($xpath) && trim($xpath[0])!='')
                                $batch_file_xml .= "<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>B</ProvSpecDelMonOccur><ProvSpecDelMon>" . $xpath[0]  . "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
                            $LearningDeliveryHE = "";
                            //Learning Delivery HE fields
                            if($delivery->LearningDeliveryHE->NUMHUS !='' && $delivery->LearningDeliveryHE->NUMHUS !='undefined')
                                $LearningDeliveryHE .= "<NUMHUS>" . $delivery->LearningDeliveryHE->NUMHUS . "</NUMHUS>";
                            if($delivery->LearningDeliveryHE->SSN !='' && $delivery->LearningDeliveryHE->SSN !='undefined')
                                $LearningDeliveryHE .= "<SSN>" . $delivery->LearningDeliveryHE->SSN . "</SSN>";
                            if($delivery->LearningDeliveryHE->QUALENT3 !='' && $delivery->LearningDeliveryHE->QUALENT3 !='undefined')
                                $LearningDeliveryHE .= "<QUALENT3>" . $delivery->LearningDeliveryHE->QUALENT3 . "</QUALENT3>";
                            if($delivery->LearningDeliveryHE->SOC2000 !='' && $delivery->LearningDeliveryHE->SOC2000 !='undefined')
                                $LearningDeliveryHE .= "<SOC2000>" . $delivery->LearningDeliveryHE->SOC2000 . "</SOC2000>";
                            if($delivery->LearningDeliveryHE->SEC !='' && $delivery->LearningDeliveryHE->SEC !='undefined')
                                $LearningDeliveryHE .= "<SEC>" . $delivery->LearningDeliveryHE->SEC . "</SEC>";
                            if($delivery->LearningDeliveryHE->UCASAPPID !='' && $delivery->LearningDeliveryHE->UCASAPPID !='undefined')
                                $LearningDeliveryHE .= "<UCASAPPID>" . $delivery->LearningDeliveryHE->UCASAPPID . "</UCASAPPID>";
                            if($delivery->LearningDeliveryHE->TYPEYR !='' && $delivery->LearningDeliveryHE->TYPEYR !='undefined')
                                $LearningDeliveryHE .= "<TYPEYR>" . $delivery->LearningDeliveryHE->TYPEYR . "</TYPEYR>";
                            if($delivery->LearningDeliveryHE->MODESTUD !='' && $delivery->LearningDeliveryHE->MODESTUD !='undefined')
                                $LearningDeliveryHE .= "<MODESTUD>" . $delivery->LearningDeliveryHE->MODESTUD . "</MODESTUD>";
                            if($delivery->LearningDeliveryHE->FUNDLEV !='' && $delivery->LearningDeliveryHE->FUNDLEV !='undefined')
                                $LearningDeliveryHE .= "<FUNDLEV>" . $delivery->LearningDeliveryHE->FUNDLEV . "</FUNDLEV>";
                            if($delivery->LearningDeliveryHE->FUNDCOMP !='' && $delivery->LearningDeliveryHE->FUNDCOMP !='undefined')
                                $LearningDeliveryHE .= "<FUNDCOMP>" . $delivery->LearningDeliveryHE->FUNDCOMP . "</FUNDCOMP>";
                            if($delivery->LearningDeliveryHE->STULOAD !='' && $delivery->LearningDeliveryHE->STULOAD !='undefined')
                                $LearningDeliveryHE .= "<STULOAD>" . $delivery->LearningDeliveryHE->STULOAD . "</STULOAD>";
                            if($delivery->LearningDeliveryHE->YEARSTU !='' && $delivery->LearningDeliveryHE->YEARSTU !='undefined')
                                $LearningDeliveryHE .= "<YEARSTU>" . $delivery->LearningDeliveryHE->YEARSTU . "</YEARSTU>";
                            if($delivery->LearningDeliveryHE->MSTUFEE !='' && $delivery->LearningDeliveryHE->MSTUFEE !='undefined')
                                $LearningDeliveryHE .= "<MSTUFEE>" . $delivery->LearningDeliveryHE->MSTUFEE . "</MSTUFEE>";
                            if($delivery->LearningDeliveryHE->PCOLAB !='' && $delivery->LearningDeliveryHE->PCOLAB !='undefined')
                                $LearningDeliveryHE .= "<PCOLAB>" . $delivery->LearningDeliveryHE->PCOLAB . "</PCOLAB>";
                            if($delivery->LearningDeliveryHE->PCFLDCS !='' && $delivery->LearningDeliveryHE->PCFLDCS !='undefined')
                                $LearningDeliveryHE .= "<PCFLDCS>" . $delivery->LearningDeliveryHE->PCFLDCS . "</PCFLDCS>";
                            if($delivery->LearningDeliveryHE->PCSLDCS !='' && $delivery->LearningDeliveryHE->PCSLDCS !='undefined')
                                $LearningDeliveryHE .= "<PCSLDCS>" . $delivery->LearningDeliveryHE->PCSLDCS . "</PCSLDCS>";
                            if($delivery->LearningDeliveryHE->PCTLDCS !='' && $delivery->LearningDeliveryHE->PCTLDCS !='undefined')
                                $LearningDeliveryHE .= "<PCTLDCS>" . $delivery->LearningDeliveryHE->PCTLDCS . "</PCTLDCS>";
                            if($delivery->LearningDeliveryHE->SPECFEE !='' && $delivery->LearningDeliveryHE->SPECFEE !='undefined')
                                $LearningDeliveryHE .= "<SPECFEE>" . $delivery->LearningDeliveryHE->SPECFEE . "</SPECFEE>";
                            if($delivery->LearningDeliveryHE->NETFEE !='' && $delivery->LearningDeliveryHE->NETFEE !='undefined')
                            {
                                $LearningDeliveryHE .= "<NETFEE>" . $delivery->LearningDeliveryHE->NETFEE . "</NETFEE>";
                                $LearningDeliveryHE .= "<GROSSFEE>" . $delivery->LearningDeliveryHE->NETFEE . "</GROSSFEE>";
                            }
                            if($delivery->LearningDeliveryHE->DOMICILE !='' && $delivery->LearningDeliveryHE->DOMICILE !='undefined')
                                $LearningDeliveryHE .= "<DOMICILE>" . $delivery->LearningDeliveryHE->DOMICILE . "</DOMICILE>";
                            if($delivery->LearningDeliveryHE->ELQ !='' && $delivery->LearningDeliveryHE->ELQ !='undefined')
                                $LearningDeliveryHE .= "<ELQ>" . $delivery->LearningDeliveryHE->ELQ . "</ELQ>";
                            if($LearningDeliveryHE != '')
                                $batch_file_xml .= "<LearningDeliveryHE>" . $LearningDeliveryHE . "</LearningDeliveryHE>";

                            $batch_file_xml .= "</LearningDelivery>";
                        }
                    }
                    $batch_file_xml .= "</Learner>";
                }
            }
            // Learner Destination and Progression
            $exists = DAO::getSingleValue($link, "SELECT COUNT(*) FROM destinations INNER JOIN tr ON destinations.`tr_id` = tr.`id` INNER JOIN contracts ON tr.`contract_id` = contracts.id WHERE contracts.id IN ($contracts)");
            if($exists)
            {
                $destinations = "SELECT destinations.tr_id, tr.l03 FROM destinations INNER JOIN tr ON destinations.`tr_id` = tr.`id` INNER JOIN contracts ON tr.`contract_id` = contracts.id WHERE contracts.id IN ($contracts) GROUP BY tr.l03 ORDER BY tr_id";
                $stdestinations =$link->query($destinations);
                if($stdestinations)
                {
                    while($rowdestinations = $stdestinations->fetch())
                    {
                        $tr_ids = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr.id) AS tr_ids FROM tr INNER JOIN contracts ON tr.contract_id = contracts.id WHERE tr.l03 = '" . $rowdestinations['l03'] . "' AND contracts.id IN ($contracts);");
                        $tr_destinations = $link->query("SELECT destinations.* FROM destinations WHERE destinations.tr_id IN ( " . $tr_ids . " )");
                        $tr_id = $rowdestinations['tr_id'];
                        $l03 = DAO::getSingleValue($link, "select l03 from tr where id = '$tr_id'");
                        $uln = DAO::getSingleValue($link, "SELECT extractvalue(ilr,'/Learner/ULN') FROM ilr WHERE tr_id = '$tr_id' ORDER BY contract_id DESC, submission DESC LIMIT 0,1");
                        $batch_file_xml .= "<LearnerDestinationandProgression>";
                        $batch_file_xml .= "<LearnRefNumber>";
                        $batch_file_xml .= $l03;
                        $batch_file_xml .= "</LearnRefNumber>";
                        $batch_file_xml .= "<ULN>" . str_pad($uln,10,'9',STR_PAD_LEFT) . "</ULN>";
                        while($tr_dest = $tr_destinations->fetch())
                        {
                            $batch_file_xml .= "<DPOutcome>";
                            $batch_file_xml .= "<OutType>" . $tr_dest['outcome_type'] . "</OutType>";
                            $batch_file_xml .= "<OutCode>" . substr($tr_dest['type_code'],3,1) . "</OutCode>";
                            $batch_file_xml .= "<OutStartDate>" . $tr_dest['outcome_start_date'] . "</OutStartDate>";
                            if($tr_dest['outcome_end_date']!='' && $tr_dest['outcome_end_date']!='0000-00-00')
                                $batch_file_xml .= "<OutEndDate>" . $tr_dest['outcome_end_date'] . "</OutEndDate>";
                            if($tr_dest['outcome_collection_date']!='' && $tr_dest['outcome_collection_date']!='0000-00-00')
                                $batch_file_xml .= "<OutCollDate>" . $tr_dest['outcome_collection_date'] . "</OutCollDate>";
                            $batch_file_xml .= "</DPOutcome>";
                        }
                        $batch_file_xml .= "</LearnerDestinationandProgression>";
                    }
                }
            }
            $batch_file_xml .= "</Message>";

            $dom = new DOMDocument;
            $dom->preserveWhiteSpace = FALSE;
            @$dom->loadXML($batch_file_xml);
            $dom->formatOutput = TRUE;
            echo $dom->saveXml();
        }
    }



    public static function getFilename(PDO $link, $contract_id, $submission, $L01)
    {
        if(is_null($contract_id))
        {
            return null;
        }

        $contract = Contract::loadFromDatabase($link, $contract_id);

        $vo = new Ilr2016();
        $vo->learnerinformation = new LearnerInformation();
        $vo->aims[0] = new Aim();

        $sql = "SELECT * FROM ilr WHERE submission = '$submission' and contract_id ='$contract_id' and is_active=1;";

        // R06 record level validation starts
        $que = "select count(DISTINCT concat(L01,L03)) from ilr where submission = '$submission' and contract_id='$contract_id' and is_active=1;";
        $no_of_distinct_ilrs = trim(DAO::getSingleValue($link, $que));
        $que = "select count(concat(L01,L03)) from ilr where submission = '$submission' and contract_id = '$contract_id' and is_active=1;";
        $no_of_total_ilrs = trim(DAO::getSingleValue($link, $que));
        if($no_of_distinct_ilrs<$no_of_total_ilrs)
            throw new Exception("R06: No two learners must have the same provider number and learner reference");
        // R06 record level validation ends
        $st = $link->query($sql);
        if($st)
        {
            $file='A';
            $file.= $contract->ukprn;
            //$file.= '00';
            $file.= '1314';
            $file.= $submission;
        }
        return $file;
    }

    private function cleanTextField($fieldValue)
    {
        $fieldValue = str_replace($this->HTML_NEW_LINES, "\n", $fieldValue); // Convert <br/> etc. into \n
        $fieldValue = str_replace("\r", '', $fieldValue); // Remove all carriage returns (we'll use the UNIX newline)
        $fieldValue = preg_replace('/\n{2,}/', "\n", $fieldValue); // Remove superfluous newlines
        $fieldValue = strip_tags($fieldValue); // Remove HTML tags

        return $fieldValue;
    }

    public static function copyILRFields($xml, $template)
    {
        //$pageDomTemplate = new DomDocument();
        //@$pageDomTemplate->loadXML($template);
        $pageDomTemplate = XML::loadXmlDom($template);
        //$pageDomXML = new DomDocument();
        //@$pageDomXML->loadXML($xml);
        $pageDomXML = XML::loadXmlDom($xml);

        $evidencesTemplate = $pageDomTemplate->getElementsByTagName('subaim');
        foreach($evidencesTemplate as $evidenceTemplate)
        {
            $a09t = "" . $evidenceTemplate->getElementsByTagName('A09')->item(0)->nodeValue;

            $evidencesXML = $pageDomXML->getElementsByTagName('subaim');
            foreach($evidencesXML as $evidenceXML)
            {
                $a09x = "" . $evidenceXML->getElementsByTagName('A09')->item(0)->nodeValue;

                if($a09x == $a09t)
                {
                    $evidenceXML->getElementsByTagName('A10')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A10')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A11a')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A11a')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A11b')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A11b')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A70')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A70')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A71')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A71')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A69')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A69')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A46a')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A46a')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A46b')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A46b')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A18')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A18')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A63')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A63')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A66')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A66')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A67')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A67')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A34')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A34')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A35')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A35')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A50')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A50')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A53')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A53')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A59')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A59')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A60')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A60')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A61')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A61')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A62')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A62')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A63')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A63')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A66')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A66')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A67')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A67')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A68')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A68')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A69')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A69')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A70')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A70')->item(0)->nodeValue;
                    $evidenceXML->getElementsByTagName('A71')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A71')->item(0)->nodeValue;
                }
            }
        }
        $ilr = $pageDomXML->saveXML();
        $ilr=substr($ilr,21);
        return $ilr;
    }

    public static function Migrate($link, $tr_id,$contract_id,$l03)
    {
        $shouldMigrate = false;
        $vo = Ilr2016::loadFromDatabase($link, "W13", $contract_id, $tr_id, $l03);
        foreach($vo->LearningDelivery as $LearningDelivery)
        {
            if(!Date::isDate($LearningDelivery->LearnActEndDate))
            {
                $shouldMigrate = true;
            }
            else
            {
                $LearnActDate = new Date($LearningDelivery->LearnActEndDate);
                if($LearnActDate->after("31/07/2017"))
                    $shouldMigrate = true;
            }

            if($LearningDelivery->CompStatus==6)
            {
                $LearnStartDate=Date::toMySQL("".$LearningDelivery->LearnStartDate);
                $restart = DAO::getSingleValue($link, "select count(*) from tr where l03 = '$l03' and start_date > '$LearnStartDate'");
                if($restart=="" or $restart==0)
                    $shouldMigrate = true;
            }

            if(($LearningDelivery->FundModel==81 and $LearningDelivery->ProgType==25) or $LearningDelivery->FundModel==36)
            {
                if($LearningDelivery->CompStatus==6)
                {
                    $LearnStartDate=Date::toMySQL("".$LearningDelivery->LearnStartDate);
                    $restart = DAO::getSingleValue($link, "select count(*) from tr where l03 = '$l03' and start_date > '$LearnStartDate' and status_code=2");
                    if($restart=="" or $restart==0)
                        $shouldMigrate = true;
                }
            }

            if($LearningDelivery->Outcome==8)
            {
                $shouldMigrate = true;
            }
        }
        //return $shouldMigrate;

        // Then do the migration
        if($shouldMigrate)
        {
            // Get the target contract number
            $new_contract_id = DAO::getSingleValue($link,"select id from contracts where parent_id = '$contract_id'");
            if($new_contract_id)
            {
                DAO::execute($link, "INSERT IGNORE INTO ilr SELECT L01,L03,A09,ilr,'W01',contract_type,tr_id,is_complete,is_valid,is_approved,is_active,'$new_contract_id' FROM ilr where submission = 'W13' and contract_id = '$contract_id' and tr_id = '$tr_id';");
                DAO::execute($link, "update tr set contract_id = '$new_contract_id' where contract_id = '$contract_id' and id = '$tr_id';");
            }
            else
            {
                throw new Exception("Contract not found ". $contract_id);
            }
        }
        else
        {
            DAO::execute($link, "delete from ilr where tr_id = '$tr_id' and contract_id in (select id from contracts where contract_year = 2017);");
            DAO::execute($link, "update tr set contract_id = '$contract_id' where id = '$tr_id';");
        }
    }


    public $id = NULL;
    public $gender = NULL;
    public $firstnames = NULL;
    public $surname = NULL;
    public $postcode = NULL;
    public $town = NULL;
    public $L26 = NULL;
    public $submission_date=NULL;
    public $subaims=0;
    public $learnerinformation = NULL;
    public $aims = array();
    public $active = NULL;
    public $approve = NULL;
    public $programmeaim = NULL;

    private $HTML_NEW_LINES = array('<br>', '<br/>', '<br />', '<BR>', '<BR/>', '<BR />', '</p>', '</P>');
}
?>