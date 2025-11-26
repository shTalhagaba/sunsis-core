<?php
class compare_ilrs implements IAction
{
    public function execute(PDO $link)
    {
        $output = isset($_REQUEST['output'])?$_REQUEST['output']:'';
        $ex = isset($_REQUEST['exclusion'])?$_REQUEST['exclusion']:'';
        $status = isset($_REQUEST['status'])?$_REQUEST['status']:'';

        if($status==1)
            $checked=" checked ";
        else
            $checked = "";


        $current_year = DAO::getSingleValue($link, "select max(contract_year) from contracts");
        $previous_year = $current_year-1;
        DAO::execute($link, "DROP TABLE IF EXISTS ilr_compare;");
        DAO::execute($link,"CREATE TEMPORARY TABLE `ilr_compare` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
  `ilr_audit_id` int(11) DEFAULT NULL,
  `field_changed` varchar(50) DEFAULT NULL,
  `old_value` text,
  `new_value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");
        $st = $link->query("select * from ilr where submission = 'W13' and contract_id in (select id from contracts where contract_year = '$previous_year')");
        while($row = $st->fetch())
        {
            $tr_id = $row['tr_id'];
            $old_ilr = $row['ilr'];
            $current_submission = DAO::getSingleValue($link, "SELECT submission FROM central.`lookup_submission_dates` WHERE contract_year = '$current_year' AND CURDATE() BETWEEN start_submission_date AND last_submission_date;");
            $new_ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = '$tr_id' and submission = '$current_submission' and contract_id in (select id from contracts where contract_year = '$current_year')");
            if($new_ilr!='')
            {
                $this->compareXML($link,$tr_id,$old_ilr,$new_ilr);
            }    
        }

        //DAO::execute($link, "drop table ilr_compare2");
        //DAO::execute($link, "create table ilr_compare2 select * from ilr_compare");
        // Now display / Export
        if($output=='CSV')
        {
            header("Content-Type: text/csv; charset=utf-8");
            header("Content-Disposition: attachment; filename=reconcilation.csv");

            $header = array("Contract","Learner Reference Number","Entity","Field",($previous_year." ILR"),($current_year." ILR"),);
            $header = array_map("utf8_decode",$header);
            $out = fopen("php://output","w");
            fputcsv($out,$header,",");
            $st = $link->query("SELECT * from ilr_compare LEFT JOIN tr ON tr.id = ilr_compare.`ilr_audit_id` LEFT JOIN contracts ON contracts.id = tr.`contract_id`");
            if($st && $st->rowCount() > 0)
            {
                while($row = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $new_rows = explode("$",$row['new_value']);
                    rsort($new_rows);
                    foreach($new_rows as $new_row)
                    {
                        $new_values = explode("|",$new_row);
                        if($new_values[0]!='')
                        {
                            $v1 = isset($new_values[1])?$new_values[1]:'&nbsp;';
                            $v2 = isset($new_values[2])?$new_values[2]:'&nbsp;';
                            $v3 = isset($new_values[3])?$new_values[3]:'&nbsp;';
                            $temp = array(
                                $row['title'],
                                $row['l03'],
                                $new_values[0],
                                $v1,
                                $v2,
                                $v3
                            );
                            if($status==1 and ($v1=="CompStatus" and $v2==1 and ($v3==2 or $v3==3 or $v3==6)) or ($v1=="Outcome" and $v2=="" and ($v3==1 or $v3==2 or $v3==3 or $v3==8)) or ($v1=="LearnActEndDate" and $v2=="" and ($v3!="")))
                            {}    
                            else
                                fputcsv($out,$temp,",");
                        }
                    }
                }
            }
        }
        else
        {
            $resultText = '';
            $st = $link->query("SELECT * from ilr_compare LEFT JOIN tr ON tr.id = ilr_compare.`ilr_audit_id` LEFT JOIN contracts ON contracts.id = tr.`contract_id`");
            if($st && $st->rowCount() > 0)
            {
                $resultText .= '<div align="left">';
                $resultText .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
                $resultText .= '<thead><tr><th>Contract</th><th>Learner Reference Number</th><th class="topRow">Entity</th><th>Field</th><th>'.$previous_year.' ILR</th><th>'.$current_year.' ILR</th></thead></tr>';
                $resultText .= '<tbody>';

                while($row = $st->fetch(PDO::FETCH_ASSOC))
                {
                    $tr_id = $row['ilr_audit_id'];
                    $new_rows = explode("$",$row['new_value']);
                    rsort($new_rows);
                    foreach($new_rows as $new_row)
                    {
                        $new_values = explode("|",$new_row);
                        if($new_values[0]!='')
                        {
                            $v1 = isset($new_values[1])?$new_values[1]:'&nbsp;';
                            $v2 = isset($new_values[2])?$new_values[2]:'&nbsp;';
                            $v3 = isset($new_values[3])?$new_values[3]:'&nbsp;';
                            if($status==1 and ($v1=="CompStatus" and $v2==1 and ($v3==2 or $v3==3 or $v3==6)) or ($v1=="Outcome" and $v2=="" and ($v3==1 or $v3==2 or $v3==3 or $v3==8)) or ($v1=="LearnActEndDate" and $v2=="" and ($v3!="")))
                                continue;
                            $resultText .= "<tr>";
                            $resultText .= "<td>" . $row['title'] . "</td>";
                            $resultText .= "<td>" . $row['l03'] . "</td>";
                            $resultText .= "<td>" . $new_values[0] . "</td>";
                            $resultText .= "<td>" . $v1 . "</td>";
                            $resultText .= "<td>" . $v2 . "</td>";
                            $resultText .= "<td>" . $v3 . "</td>";
                            $resultText .= "</tr>";
                        }
                    }
                }

                $resultText .= '</tbody>';
                $resultText .= '</table></div>';
            }
            include('tpl_compare_ilrs.php');
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
            DAO::saveObjectToTable($link, 'ilr_compare', $_obj);
        }
    }
}
?>