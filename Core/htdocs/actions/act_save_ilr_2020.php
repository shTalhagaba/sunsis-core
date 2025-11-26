<?php
class save_ilr_2020 implements IAction
{
    public function execute(PDO $link)
    {
        try {
            DAO::transaction_start($link);
            $this->process($link);
            DAO::transaction_commit($link);
        }
        catch(Exception $e) {
            DAO::transaction_rollback($link);
            throw $e;
        }
    }

    private function process(PDO $link)
    {
        // Check arguments
        $qan = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        $xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
        $L01 = isset($_REQUEST['L01'])?$_REQUEST['L01']:'';
        $l28a = isset($_REQUEST['L28a'])?$_REQUEST['L28a']:'';
        $l28b = isset($_REQUEST['L28b'])?$_REQUEST['L28b']:'';
        $A09 = isset($_REQUEST['A09'])?$_REQUEST['A09']:'';
        $approve = isset($_REQUEST['approve'])?$_REQUEST['approve']:'';
        $active = isset($_REQUEST['active'])?$_REQUEST['active']:'';
        $sub = isset($_REQUEST['sub'])?$_REQUEST['sub']:'';
        $contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $template = isset($_REQUEST['template'])?$_REQUEST['template']:'';


        if($approve == 'true')
            $approved=1;
        else
            $approved=0;

        if($active == 'true')
            $activated=1;
        else
            $activated=0;

        $xml = addslashes((string)$xml);
        $xml_escaped = $xml;
        $L03_escaped = $qan;
        $L01_escaped = $L01;
        $A09_escaped = $A09;
        $is_approved_escaped = $approved;
        $is_activated_escaped = $activated;
        $sub_escaped = $sub;
        $shoulddemigrate = true;

        if($template!=1)
        {
            // Update Exempt Flag in student qualifications table
            $achieved = true;
            $achievement_date = '';
            $exempt = XML::loadSimpleXML($xml_escaped);

            $aims = Array();
            $start_dates = Array();
            $end_dates = Array();
            $planned_dates = Array();
            $earliest_start_date = '';
            $latest_end_date = '';
            $xresult = $exempt->xpath('/Learner/LearningDelivery');
            if(!empty($xresult))
            {
                foreach($xresult AS $key => $node)
                {
                    $a09 = $node->LearnAimRef;
                    $compstatus = $node->CompStatus;
                    $outcome = $node->Outcome;


                    $a27 = Date::toMySQL($node->LearnStartDate);
                    $a28 = Date::toMySQL($node->LearnPlanEndDate);

                    $st = new Date($a27);
                    if($earliest_start_date=='')
                        $earliest_start_date = new Date($st->formatShort());
                    elseif($earliest_start_date->getDate()>$st->getDate())
                        $earliest_start_date = new Date($st->formatShort());

                    $ed = new Date($a28);
                    if($latest_end_date=='')
                        $latest_end_date = new Date($ed->formatShort());
                    elseif($latest_end_date->getDate()<$ed->getDate())
                        $latest_end_date = new Date($ed->formatShort());

                    $planned_dates[] = $node->A28;

                    if($node->LearnActEndDate)
                    {
                        $a31 = "'" . Date::toMySQL($node->LearnActEndDate) . "'";
                        $a34 = $node->CompStatus;
                        if($node->Outcome=="8" or $node->CompStatus=="6")
                        {
                            $shoulddemigrate = false;
                        }
                    }
                    else
                    {
                        $achieved = false;
                        $a31 = "NULL";
                        $shoulddemigrate = false;
                    }

                    if($node->AchDate)
                        $a40 = "'" . Date::toMySQL($node->AchDate) . "'";
                    else
                        $a40 = "NULL";

                    if($a40=='' || $a40=='undefined' || $a40=="''")
                        $a40 = "NULL";

                    if($node->LearnActEndDate)
                    {
                        $d1 = new Date($node->LearnActEndDate);
                        $d2 = new Date($node->LearnStartDate);

                        if($d1->getDate() == $d2->getDate() && $node->Outcome == 3 && $node->CompStatus == 3)
                        {
                            $aims[] = "'" . $node->LearnAimRef . "'";
                            $start_dates["'" . $node->LearnAimRef . "'"] = $node->LearnStartDate;
                            $end_dates["'" . $node->LearnAimRef . "'"] = $node->LearnPlanEndDate;
                        }
                        else
                        {
                            $aims[] = "'" . $node->LearnAimRef . "'";
                            $start_dates["'" . $node->LearnAimRef . "'"] = $node->LearnStartDate;
                            $end_dates["'" . $node->LearnAimRef . "'"] = $node->LearnPlanEndDate;
                        }
                    }
                    else
                    {
                        $aims[] = "'" . $node->LearnAimRef . "'";
                        $start_dates["'" . $node->LearnAimRef . "'"] = $node->LearnStartDate;
                        $end_dates["'" . $node->LearnAimRef . "'"] = $node->LearnPlanEndDate;
                    }

                    $s = "update student_qualifications set start_date = '$a27', end_date = '$a28', actual_end_date = $a31, achievement_date = $a40 where REPLACE(id,'/','') = '$a09' and tr_id = $tr_id;";
                    DAO::execute($link, $s);
                }
            }

            $aims2 = implode(",",$aims);

            if($shoulddemigrate)
            {
                DAO::execute($link, "delete from ilr where tr_id = '$tr_id' and contract_id in (select id from contracts where contract_year = 2021)");
                DAO::execute($link, "UPDATE tr SET contract_id = (SELECT contract_id FROM ilr WHERE ilr.tr_id = tr.id ORDER BY contract_id DESC LIMIT 0,1) where tr.id = '$tr_id';");
            }
            else
            {
                $c = DAO::getSingleValue($link, "select count(*) from ilr where tr_id='$tr_id' and contract_id in (select id from contracts where contract_year = 2021)");
                if($c==0)
                {
                    DAO::execute($link, "INSERT INTO ilr SELECT L01,L03,A09,ilr,'W01',contract_type,tr_id,is_complete,is_valid,is_approved,is_active,(SELECT id FROM contracts WHERE parent_id = contract_id) FROM ilr WHERE contract_id in (select id from contracts where contract_year = 2020) and submission = 'W13' AND tr_id = '$tr_id'");
                    DAO::execute($link, "UPDATE tr SET contract_id = (SELECT contract_id FROM ilr WHERE ilr.tr_id = tr.id ORDER BY contract_id DESC LIMIT 0,1) where tr.id = '$tr_id';");
                }
            }

            if(DB_NAME!='am_lead')
            {
                DAO::execute($link, "update student_qualifications set aptitude=0 where tr_id = $tr_id;");
                if($aims2!='')
                    DAO::execute($link, "update student_qualifications set aptitude=1 where REPLACE(id,'/','') NOT IN ($aims2) and tr_id = $tr_id;");
            }

            $c = DAO::getSingleValue($link, "select count(*) from ilr where submission= '$sub' and contract_id=$contract_id and tr_id='$tr_id'");

            $previous_ilr_xml = DAO::getSingleValue($link, "select ilr from ilr where submission= '$sub' and contract_id=$contract_id and tr_id = $tr_id");
            $previous_ilr = Ilr2020::loadFromXML($previous_ilr_xml);

            if($c==0)
            {
                $sql = "insert into ilr (L01, L03, A09, ilr, submission, contract_type, tr_id, is_complete, is_valid, is_approved, is_active, contract_id) values('$L01_escaped', '$L03_escaped', '$A09_escaped', '$xml_escaped', '$sub', '0', $tr_id, '0', '0', '$is_approved_escaped', '$is_activated_escaped', '$contract_id');";
            }
            else
            {
                $sql = "update ilr set L03 = '$L03_escaped', L01 = '$L01_escaped', A09 = '$A09_escaped', ilr = '$xml_escaped', is_approved = '$is_approved_escaped', is_active = '$is_activated_escaped' where submission= '$sub' and contract_id=$contract_id and tr_id = $tr_id";
            }

            DAO::execute($link, $sql);

            $user_agent = SUBSTR($_SERVER['HTTP_USER_AGENT'],0,200);
            $username = $_SESSION['user']->username;
            $link->query("insert into ilr_audit (id, username, `date`, A09, `changed`, `from`, `to`, user_agent, tr_id, submission, contrat_id) values(NULL,'$username',NULL,'ILR','ILR','','','$user_agent','$tr_id','$sub','$contract_id');");
            $ilrAuditId = $link->lastInsertId();

            // Update tr status
            $vo = Ilr2020::loadFromXML($xml);
            $funding_type = Ilr2020::FundingType($vo);

            //$this->saveAuditTrailEntryDetails($link, $ilrAuditId, $previous_ilr, $vo);
            $this->compareXML($link, $ilrAuditId, $previous_ilr_xml, $xml);

            $status_code = "";
            $outcome = "";
            $closure_date = "NULL";
            $ld_start_date = '';
            if(strpos($xml, "<AimType>1</AimType>")!==false)
            {
                foreach($vo->LearningDelivery as $ld)
                {
                    if($ld->AimType=='1')
                    {
                        if($ld_start_date=='')
                        {
                            $ld_start_date = new Date($ld->LearnStartDate);
                            $xpath = $ld->LearnActEndDate;
                            if(isset($xpath))
                                $closure_date = "'" . Date::toMySQL($xpath) . "'";
                            else
                                $closure_date = "NULL";

                            $xpath = $ld->CompStatus;
                            if(isset($xpath))
                                $status_code = $xpath;
                            else
                                $status_code = 1;

                            $xpath = $ld->Outcome;
                            if(isset($xpath))
                                $outcome = $xpath;
                            else
                                $outcome = '';

                            $WithdrawReason = $ld->WithdrawReason;
                            if(isset($WithdrawReason) && $WithdrawReason == "40" && $status_code==3 && $outcome==3)
                                $status_code = 4;

                        }
                        else
                        {
                            if($ld_start_date->before($ld->LearnStartDate))
                            {
                                $xpath = $ld->LearnActEndDate;
                                if(isset($xpath))
                                    $closure_date = "'" . Date::toMySQL($xpath) . "'";
                                else
                                    $closure_date = "NULL";

                                $xpath = $ld->CompStatus;
                                if(isset($xpath))
                                    $status_code = $xpath;
                                else
                                    $status_code = 1;

                                $xpath = $ld->Outcome;
                                if(isset($xpath))
                                    $outcome = $xpath;
                                else
                                    $outcome = '';

                                $WithdrawReason = $ld->WithdrawReason;
                                if(isset($WithdrawReason) && $WithdrawReason == "40" && $status_code==3 && $outcome==3)
                                    $status_code = 4;

                            }
                        }
                    }
                }
            }
            elseif(strpos($xml, "<AimType>5</AimType>")!==false)
            {
                $xpath = $vo->xpath("/Learner/LearningDelivery[AimType='5']/LearnActEndDate");
                if(isset($xpath[0]))
                    $closure_date = "'" . Date::toMySQL($xpath[0]) . "'";
                else
                    $closure_date = "NULL";

                $xpath = $vo->xpath("/Learner/LearningDelivery[AimType='5']/CompStatus");
                if(@in_array('1',$xpath))
                    $status_code = 1;
                else
                    $status_code = $xpath[sizeof($xpath)-1];

                $xpath = $vo->xpath("/Learner/LearningDelivery[AimType='5']/Outcome");
                if(isset($xpath[0]))
                    $outcome = $xpath[0];
                else
                    $outcome = '';

            }
            elseif(strpos($xml, "<AimType>4</AimType>")!==false)
            {

                $xpath = $vo->xpath("/Learner/LearningDelivery[AimType='4']/LearnActEndDate");
                if(isset($xpath[0]))
                    $closure_date = "'" . Date::toMySQL($xpath[0]) . "'";
                else
                    $closure_date = "NULL";

                $xpath = $vo->xpath("/Learner/LearningDelivery[AimType='4']/CompStatus");
                if(@in_array('1',$xpath))
                    $status_code = 1;
                else
                    $status_code = $xpath[sizeof($xpath)-1];

                $xpath = $vo->xpath("/Learner/LearningDelivery[AimType='4']/Outcome");
                if(isset($xpath[0]))
                    $outcome = $xpath[0];
                else
                    $outcome = '';

            }
            else
            {
                $status_code = 1;
                $outcome = "";
            }


            $ilr_status = DAO::getSingleValue($link,"SELECT is_valid FROM ilr WHERE tr_id = '$tr_id' ORDER BY contract_id DESC, submission DESC LIMIT 0,1;");
            $uln = "" . $vo->ULN;
            $xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon");
            $l42a = "" . (empty($xpath))?'':$xpath[0];
            $xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon");
            $l42b = "" . (empty($xpath))?'':$xpath[0];
            $xpath = $vo->xpath("/Learner/Ethnicity");
            $ethnicity = "" . (empty($xpath))?'':$xpath[0];

            $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email");
            $home_email = (empty($xpath))?'':$xpath[0];

            $xpath = $vo->xpath("/Learner/NINumber");
            $ni = (empty($xpath))?'':$xpath[0];

            $start_date = $earliest_start_date->formatMySQL();
            $target_date = $latest_end_date->formatMySQL();
            if($status_code == 1)
                $closure_date = "NULL";

            $rftitle = DAO::getSingleValue($link, "select title from contracts where id = '$contract_id' and (title LIKE '% RF %'or title LIKE '% Work Routes %' OR title LIKE '% Flex Fund %')");
            $wpltitle = DAO::getSingleValue($link, "select title from contracts where id = '$contract_id' and (title LIKE '% WPL %')");

            if(DB_NAME=="am_lead")
            {
                if($status_code == '')
                {
                    if(strpos($xml, "<AimType>3</AimType>")!==false)
                    {
                        $xpath = $vo->xpath("/Learner/LearningDelivery[AimType='3']/CompStatus");
                        if(@in_array('1',$xpath))
                            $status_code = 1;
                        else
                            $status_code = $xpath[sizeof($xpath)-1];
                    }
                }

                DAO::execute($link, "update tr set target_date = '$target_date', start_date='$start_date',home_email = '$home_email', l42a = '$l42a', l42b = '$l42b', uln = '$uln', ilr_status = '$ilr_status', status_code = '$status_code', outcome='$outcome', closure_date = $closure_date, ethnicity = '$ethnicity' where id = '$tr_id'");
            }
            else
            {
                $link->query("update tr set ni='$ni', target_date = '$target_date', start_date='$start_date',home_email = '$home_email', l42a = '$l42a', l42b = '$l42b', uln = '$uln', ilr_status = '$ilr_status', status_code = '$status_code', outcome='$outcome', closure_date = $closure_date, ethnicity = '$ethnicity' where id = '$tr_id'");
            }

            // hook for Superdrug Kickstart
            if(DB_NAME == 'am_superdrug')
            {
                $this->saveSuperdrugKickstart($link, $tr_id, $contract_id, $sub);
            }

            header("Content-Type: text/xml");
            echo '<?xml version="1.0"?><report><success/></report>';


            $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine1'); $l18 = (empty($xpath))?'':(string)$xpath[0];
            $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine2'); $l19 = (empty($xpath))?'':(string)$xpath[0];
            $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine3'); $l20 = (empty($xpath))?'':(string)$xpath[0];
            $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine4'); $l21 = (empty($xpath))?'':(string)$xpath[0];
            $xpath = $vo->xpath('/Learner/LearnerContact/TelNumber'); $l23 = (empty($xpath))?'':$xpath[0];
            $l24 = $vo->Domicile;
            $l14 = $vo->LLDDHealthProb;
            $l39 = $vo->Dest;
            if($l14=='')
                $l14=0;
            $xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode"); $l15 = (empty($xpath))?'':$xpath[0];
            $xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode"); $l16 = (empty($xpath))?'':$xpath[0];
            $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode"); $l17 = (empty($xpath))?'':$xpath[0];
            $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $l34a = (empty($xpath[0]))?'':(string)$xpath[0];
            $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $l34b = (empty($xpath[1]))?'':(string)$xpath[1];
            $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $l34c = (empty($xpath[2]))?'':(string)$xpath[2];
            $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $l34d = (empty($xpath[3]))?'':(string)$xpath[3];
            $l35 = $vo->PriorAttain;
            if($l35=='')
                $l35 = 0;
            $l39 = $vo->Dest;
            if($l39=='')
                $l39 = 0;
            $l45 = $vo->ULN;

            $username = DAO::getSingleValue($link, "select username from tr where id = '$tr_id'");
            $learner = User::loadFromDatabase($link, $username);
            if (!$learner) {
                throw new Exception("Cannot find the associated learner record for training record #".$tr_id);
            }
            $user_id = $learner->id;

            $obj = new stdClass();
            $obj->home_address_line_1 = $l18;
            $obj->home_address_line_2 = $l19;
            $obj->home_address_line_3 = $l20;
            $obj->home_address_line_4 = $l21;
            $obj->home_postcode = $l17;
            $obj->l24 = $l24;
            $obj->l14 = $l14;
            $obj->l15 = $l15;
            $obj->l16 = $l16;
            $obj->l34a = $l34a;
            $obj->l34b = $l34b;
            $obj->l34c = $l34c;
            $obj->l34d = $l34d;
            $obj->l35 = $l35;
            $obj->l28a = $l28a;
            $obj->l28b = $l28b;
            $obj->l39 = $l39;
            $obj->l42a = $l42a;
            $obj->l42b = $l42b;
            if ($l45 && User::isValidUln($l45) && $this->ulnIsUniqueToUser($link, $learner, $l45)) {
                $obj->l45 = $l45;
            }
            $obj->id = $user_id;
            DAO::saveObjectToTable($link, "users", $obj);

            $obj = new stdClass();
            $obj->home_telephone = $l23;
            $obj->home_address_line_1 = $l18;
            $obj->home_address_line_2 = $l19;
            $obj->home_address_line_3 = $l20;
            $obj->home_address_line_4 = $l21;
            $obj->disability = $l15;
            $obj->learning_difficulty = $l16;
            $obj->home_postcode = $l17;
            $obj->l39 = $l39;
            $obj->id = $tr_id;
            DAO::saveObjectToTable($link, "tr", $obj);
        }
        else
        {
            DAO::execute($link, "update contracts set template = '$xml' where id = '$contract_id'");
            header("Content-Type: text/xml");
            echo '<?xml version="1.0"?><report><success/></report>';
        }
    }


    public static function compareXML($link, $audit_id, $old, $new)
    {
        $old = XML::loadSimpleXML($old);
        $new = XML::loadSimpleXML($new);
        $old_learner_fields = Array();
        $new_learner_fields = Array();
        // Learner Level Fields
        foreach($old as $field => $value)
        {
            if($field!="LearnerEmploymentStatus" and $field!="LearningDelivery" and $field!="ContactPreference" and $field!="LLDDandHealthProblem" and $field!="LearnerFAM" and $field!="ProviderSpecLearnerMonitoring" and $field!="LearnerContact")
            {
                $old_learner_fields[] = "Learner|".$field."=".$old->$field;
            }
        }
        foreach($new as $field => $value)
        {
            if($field!="LearnerEmploymentStatus" and $field!="LearningDelivery" and $field!="ContactPreference" and $field!="LLDDandHealthProblem" and $field!="LearnerFAM" and $field!="ProviderSpecLearnerMonitoring"  and $field!="LearnerContact")
            {
                $new_learner_fields[] = "Learner|".$field."=".$new->$field;
            }
        }
        // Learner Contact
        foreach($old->LearnerContact as $oldcp)
        {
            if($oldcp->LocType=='3' and $oldcp->ContType=='2')
                $old_learner_fields[] = "Learner|TelNo=".$oldcp->TelNumber;
            if($oldcp->LocType=='4' and $oldcp->ContType=='2')
                $old_learner_fields[] = "Learner|Email=".$oldcp->Email;
            if($oldcp->LocType=='2' and $oldcp->ContType=='2')
                $old_learner_fields[] = "Learner|Postcode=".$oldcp->Postcode;
            if($oldcp->LocType=='2' and $oldcp->ContType=='1')
                $old_learner_fields[] = "Learner|PostcodePrior=".$oldcp->PostcodePrior;
            if($oldcp->LocType=='1' and $oldcp->ContType=='2')
                foreach($oldcp->PostAdd as $field)
                {
                    foreach($field as $field2 => $value)
                    {
                        $old_learner_fields[] = "Learner|".$field2."=".$value;
                    }
                }
        }
        foreach($new->LearnerContact as $newcp)
        {
            if($newcp->LocType=='3' and $newcp->ContType=='2')
                $new_learner_fields[] = "Learner|TelNo=".$newcp->TelNumber;
            if($newcp->LocType=='4' and $newcp->ContType=='2')
                $new_learner_fields[] = "Learner|Email=".$newcp->Email;
            if($newcp->LocType=='2' and $newcp->ContType=='2')
                $new_learner_fields[] = "Learner|Postcode=".$newcp->Postcode;
            if($newcp->LocType=='2' and $newcp->ContType=='1')
                $new_learner_fields[] = "Learner|PostcodePrior=".$newcp->PostcodePrior;
            if($newcp->LocType=='1' and $newcp->ContType=='2')
                foreach($newcp->PostAdd as $field)
                {
                    foreach($field as $field2 => $value)
                    {
                        $new_learner_fields[] = "Learner|".$field2."=".$value;
                    }
                }
        }
        // Learner Contact Preference
        foreach($old->ContactPreference as $oldcp)
        {
            $old_learner_fields[] = "Learner|ContPrefType-ContPrefCode=".$oldcp->ContPrefType."-".$oldcp->ContPrefCode;
        }
        foreach($new->ContactPreference as $newcp)
        {
            $new_learner_fields[] = "Learner|ContPrefType-ContPrefCode=".$newcp->ContPrefType."-".$newcp->ContPrefCode;
        }
        // Learner LLDDandHealthProblem
        foreach($old->LLDDandHealthProblem as $oldcp)
        {
            $old_learner_fields[] = "Learner|LLDDCat-PrimaryLLDD=".$oldcp->LLDDCat."-".$oldcp->PrimaryLLDD;
        }
        foreach($new->LLDDandHealthProblem as $newcp)
        {
            $new_learner_fields[] = "Learner|LLDDCat-PrimaryLLDD=".$newcp->LLDDCat."-".$newcp->PrimaryLLDD;
        }
        // Learner FAM
        foreach($old->LearnerFAM as $oldcp)
        {
            $old_learner_fields[] = "Learner|LearnFAMType-LearnFAMCode=".$oldcp->LearnFAMType."-".$oldcp->LearnFAMCode;
        }
        foreach($new->LearnerFAM as $newcp)
        {
            $new_learner_fields[] = "Learner|LearnFAMType-LearnFAMCode=".$newcp->LearnFAMType."-".$newcp->LearnFAMCode;
        }
        // Learner ProviderSpecLearnerMonitoring
        foreach($old->ProviderSpecLearnerMonitoring as $oldcp)
        {
            $old_learner_fields[] = "Learner|ProvSpecLearnMonOccur-ProvSpecLearnMon=".$oldcp->ProvSpecLearnMonOccur."-".$oldcp->ProvSpecLearnMon;
        }
        foreach($new->ProviderSpecLearnerMonitoring as $newcp)
        {
            $new_learner_fields[] = "Learner|ProvSpecLearnMonOccur-ProvSpecLearnMon=".$newcp->ProvSpecLearnMonOccur."-".$newcp->ProvSpecLearnMon;
        }

        // Learner Employment Status
        foreach($old->LearnerEmploymentStatus as $oldcp)
        {
            $oldfields = "Learner|EmpStat-DateEmpStatApp-EmpId-AgreeId=".$oldcp->EmpStat."-".$oldcp->DateEmpStatApp."-".$oldcp->EmpId."-".$oldcp->AgreeId;
            $old_learner_fields[] = $oldfields;
            foreach($oldcp->EmploymentStatusMonitoring as $oldmon)
            {
                $oldfields="Learner|DateEmpStatApp-ESMType-ESMCode=" . $oldcp->DateEmpStatApp . '-' . $oldmon->ESMType . "-".$oldmon->ESMCode;
                $old_learner_fields[] = $oldfields;
            }
        }
        foreach($new->LearnerEmploymentStatus as $newcp)
        {
            $newfields = "Learner|EmpStat-DateEmpStatApp-EmpId-AgreeId=".$newcp->EmpStat."-".$newcp->DateEmpStatApp."-".$newcp->EmpId."-".$newcp->AgreeId;
            $new_learner_fields[] = $newfields;
            foreach($newcp->EmploymentStatusMonitoring as $newmon)
            {
                $newfields="Learner|DateEmpStatApp-ESMType-ESMCode=" . $newcp->DateEmpStatApp . "-" .$newmon->ESMType . "-".$newmon->ESMCode;
                $new_learner_fields[] = $newfields;
            }
        }

        // Learner Learning Delivery
        foreach($old->LearningDelivery as $oldcp)
        {
            foreach($oldcp as $field => $value)
            {
                if($field!="LearningDeliveryFAM" and $field!="LearningDeliveryWorkPlacement" and $field!="TrailblazerApprenticeshipFinancialRecord")
                {
                    $old_learner_fields[] = "LearnAimRef[".$oldcp->LearnAimRef."]|".$field."=".$value;
                }
            }

            foreach($oldcp->LearningDeliveryFAM as $oldfam)
            {
                $old_learner_fields[] = "LearnAimRef[".$oldcp->LearnAimRef."]|LearnDelFAMType-LearnDelFAMCode-LearnDelFAMDateFrom-LearnDelFAMDateTo=" . $oldfam->LearnDelFAMType."-" . $oldfam->LearnDelFAMCode . "-" . $oldfam->LearnDelFAMDateFrom . "-" . $oldfam->LearnDelFAMDateTo;
            }
            foreach($oldcp->LearningDeliveryWorkPlacement as $oldfam)
            {
                $old_learner_fields[] = "LearnAimRef[".$oldcp->LearnAimRef. "]|WorkPlaceStartDate-WorkPlaceEndtDate-WorkPlaceHours-WorkPlaceMode-WorkPlaceEmpId=". $oldfam->WorkPlaceStartDate ."-" . $oldfam->WorkPlaceEndDate . "-" . $oldfam->WorkPlaceHours . "-" . $oldfam->WorkPlaceMode . "-" . $oldfam->WorkPlaceEmpId;
            }
            foreach($oldcp->TrailblazerApprenticeshipFinancialRecord as $oldfam)
            {
                $old_learner_fields[] = "LearnAimRef[".$oldcp->LearnAimRef. "]|AFinDate-AFinCode-AFinType-AFinAmount=" . $oldfam->TBFinDate . "-" . $oldfam->TBFinCode . "-" . $oldfam->TBFinType . "-" . $oldfam->TBFinAmount;
            }
        }
        foreach($new->LearningDelivery as $newcp)
        {
            foreach($newcp as $field => $value)
            {
                if($field!="LearningDeliveryFAM" and $field!="LearningDeliveryWorkPlacement" and $field!="TrailblazerApprenticeshipFinancialRecord")
                {
                    $new_learner_fields[] = "LearnAimRef[".$newcp->LearnAimRef."]|".$field."=".$value;
                }
            }
            foreach($newcp->LearningDeliveryFAM as $newfam)
            {
                $new_learner_fields[] = "LearnAimRef[".$newcp->LearnAimRef."]|LearnDelFAMType-LearnDelFAMCode-LearnDelFAMDateFrom-LearnDelFAMDateTo=" . $newfam->LearnDelFAMType."-" . $newfam->LearnDelFAMCode . "-" . $newfam->LearnDelFAMDateFrom . "-" . $newfam->LearnDelFAMDateTo;
            }
            foreach($newcp->LearningDeliveryWorkPlacement as $newfam)
            {
                $new_learner_fields[] = "LearnAimRef[".$newcp->LearnAimRef. "]|WorkPlaceStartDate-WorkPlaceEndtDate-WorkPlaceHours-WorkPlaceMode-WorkPlaceEmpId=". $newfam->WorkPlaceStartDate ."-" . $newfam->WorkPlaceEndDate . "-" . $newfam->WorkPlaceHours . "-" . $newfam->WorkPlaceMode . "-" . $newfam->WorkPlaceEmpId;
            }
            foreach($newcp->TrailblazerApprenticeshipFinancialRecord as $newfam)
            {
                $new_learner_fields[] = "LearnAimRef[".$newcp->LearnAimRef. "]|AFinDate-AFinCode-AFinType-AFinAmount=" . $newfam->TBFinDate . "-" . $newfam->TBFinCode . "-" . $newfam->TBFinType . "-" . $newfam->TBFinAmount;
            }
        }

        $output = "";
        $common_values = array_intersect($old_learner_fields,$new_learner_fields);
        foreach($old_learner_fields as $old_learner_field)
        {
            if(!in_array($old_learner_field,$common_values)) // Values either changed or removed
            {
                // Check if the value has changed
                $old_values = explode("|",$old_learner_field);
                $old_field_value = explode("=",$old_values[1]);
                $value_removed = 1;
                foreach($new_learner_fields as $new_learner_field)
                {
                    if(!in_array($new_learner_field,$common_values))
                    {
                        $new_values = explode("|",$new_learner_field);
                        $new_field_value = explode("=",$new_values[1]);
                        if($old_values[0]==$new_values[0] and $old_field_value[0]==$new_field_value[0] and ($old_field_value[1]!='' or $new_field_value[1]!=''))
                        {
                            $value_removed = 0;
                            $output .= "$".$old_values[0]."|".$old_field_value[0]."|".$old_field_value[1]."|".$new_field_value[1];
                        }
                    }
                }
                if($value_removed==1 and $old_field_value[1]!='')
                {
                    $output .= "$".$old_values[0]."|".$old_field_value[0]."|".$old_field_value[1]."|";
                }
            }
        }
        foreach($new_learner_fields as $new_learner_field)
        {
            if(!in_array($new_learner_field,$common_values)) // Values either changed or removed
            {
                // Check if the value has changed
                $new_values = explode("|",$new_learner_field);
                $new_field_value = explode("=",$new_values[1]);
                $value_removed = 1;
                foreach($old_learner_fields as $old_learner_field)
                {
                    if(!in_array($old_learner_field,$common_values))
                    {
                        $old_values = explode("|",$old_learner_field);
                        $old_field_value = explode("=",$old_values[1]);
                        if($new_values[0]==$old_values[0] and $new_field_value[0]==$old_field_value[0])
                        {
                            $value_removed = 0;
                        }
                    }
                }
                if($value_removed==1 and $new_field_value[1]!='')
                {
                    $output .= "$".$new_values[0]."|".$new_field_value[0]."||".$new_field_value[1];
                }
            }
        }

        //$old_values = implode("$",array_diff($old_learner_fields,$new_learner_fields));
        //$new_values = implode("$",array_diff($new_learner_fields,$old_learner_fields));
        $old_values = "";
        $new_values = $output;
        if($old_values!='' or $new_values!='')
        {
            $_obj = new stdClass();
            $_obj->ilr_audit_id = $audit_id;
            $_obj->field_changed = '';
            $_obj->old_value = $old_values;
            $_obj->new_value = $new_values;
            DAO::saveObjectToTable($link, 'ilr_audit_trail_entry', $_obj);
        }
    }


    private function checkPermissions(PDO $link, Course $c_vo)
    {
        if($_SESSION['role'] == 'admin')
        {
            return true;
        }
        elseif($_SESSION['org']->org_type_id == ORG_PROVIDER)
        {
            $acl = CourseACL::loadFromDatabase($link, $c_vo->id);
            $is_employee = $_SESSION['org']->id == $c_vo->organisations_id;
            $is_local_admin = in_array('ladmin', $_SESSION['privileges']);
            $listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);

            return $is_employee && $is_local_admin;
        }
        elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
        {
            return false;
        }
        else
        {
            return false;
        }
    }

    /**
     * @param PDO $link
     * @param int $user_id
     * @param string $uln
     * @return bool True if the uln is unique to the specified learner
     */
    private function ulnIsUniqueToUser(PDO $link, User $learner, $uln)
    {
        $uln = trim($uln);
        $empid = $learner->employer_id;
        $count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE uln=".$link->quote($uln)
            . " AND users.id != ".$link->quote($learner->id)
            . " AND users.employer_id =  '$empid'");
        return $count ? false : true; // 'count' is the number of users with the same ULN
    }

    private function saveAuditTrailEntryDetails(PDO $link, $ilrAuditId, $previous_ilr, $new_ilr)
    {
        $ilrElementsForXPathCheck = array('SOF', 'FFI', 'EEF', 'RES', 'WPL');
        try
        {
            $audit_fields = array();
            $st = $link->query("SELECT field_title, mysql_syntax FROM lookup_ilr_audit_fields WHERE active = 1 AND aim_specific = 0 ");
            if($st)
            {
                while($row = $st->fetch())
                {
                    $audit_fields[$row['field_title']] = $row['mysql_syntax'];
                }
            }

            $audit_fields_aim_specific = array();
            $st = $link->query("SELECT field_title, mysql_syntax FROM lookup_ilr_audit_fields WHERE active = 1 AND aim_specific = 1 ");
            if($st)
            {
                while($row = $st->fetch())
                {
                    $audit_fields_aim_specific[$row['field_title']] = $row['mysql_syntax'];
                }
            }

            foreach($audit_fields AS $field=>$value)
            {
                $old_value = $previous_ilr->xpath($value);
                if(isset($old_value[0]))
                    $old_value = $old_value[0];
                $new_value = $new_ilr->xpath($value);
                if(isset($new_value[0]))
                    $new_value = $new_value[0];
                if(!is_array($new_value) AND !is_array($old_value))
                {
                    if ($old_value != 'undefined' && strcmp($old_value, $new_value) != 0)
                    {
                        $link->query("INSERT INTO ilr_audit_trail_entry (ilr_audit_id, field_changed, old_value, new_value) VALUES ($ilrAuditId, '$field', '$old_value', '$new_value')");
                    }
                }
            }

            foreach($audit_fields_aim_specific AS $field=>$value)
            {
                foreach($previous_ilr->LearningDelivery as $delivery)
                {
                    $learning_aim = $delivery->LearnAimRef;
                    if(in_array($field, $ilrElementsForXPathCheck))
                    {
                        $old_value = $delivery->xpath("./" . $value);
                        $old_value = $old_value[0];
                    }
                    else
                        $old_value = $delivery->$value;
                    $new_value = $new_ilr->xpath("/Learner/LearningDelivery[LearnAimRef='$learning_aim']/".$value);
                    if(isset($new_value[0]))
                        $new_value = $new_value[0];
                    if(!is_array($new_value))
                    {
                        if ($old_value != 'undefined' && strcmp($old_value, $new_value) != 0)
                        {
                            $link->query("INSERT INTO ilr_audit_trail_entry (ilr_audit_id, field_changed, old_value, new_value) VALUES ($ilrAuditId, '$learning_aim :: $field', '$old_value', '$new_value')");
                        }
                    }
                }
            }
        }
        catch(Exception $e)
        {
            throw new Exception($e->getLine() . ' :: ' . $e->getMessage());
        }
    }

    private function saveSuperdrugKickstart(PDO $link, $tr_id, $contract_id, $submission)
    {
        if(!in_array($contract_id, ["110"]))
            return;

        $sql = <<<SQL
SELECT 
	EXTRACTVALUE(ilr, '/Learner/LearningDelivery[LearnAimRef="ZWRKX001"]/LearnActEndDate') AS LearnActEndDate, 
	EXTRACTVALUE(ilr, '/Learner/LearningDelivery[LearnAimRef="ZWRKX001"]/CompStatus') AS CompStatus, 
	EXTRACTVALUE(ilr, '/Learner/LearningDelivery[LearnAimRef="ZWRKX001"]/Outcome')AS Outcome, 
	EXTRACTVALUE(ilr, '/Learner/LearningDelivery[LearnAimRef="ZWRKX001"]/AchDate') AS AchDate
FROM ilr WHERE ilr.tr_id = '{$tr_id}' AND ilr.contract_id = '{$contract_id}' AND ilr.submission = '{$submission}'
;
SQL;
        $ilr = DAO::getObject($link, $sql);
        if(!isset($ilr->LearnActEndDate))
            return;

        $LearnActEndDate = $ilr->LearnActEndDate;
        $CompStatus = $ilr->CompStatus;
        $Outcome = $ilr->Outcome;
        $AchDate = $ilr->AchDate;

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $tr->closure_date = $LearnActEndDate;
        $tr->status_code = $CompStatus;
        $tr->outcome = $Outcome;
        $tr->achievement_date = $AchDate;
        $tr->save($link);
    }
}
?>
