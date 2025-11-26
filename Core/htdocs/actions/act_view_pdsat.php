<?php
class view_pdsat implements IAction
{
    public function execute(PDO $link)
    {
        $reset = isset($_REQUEST['reset'])?$_REQUEST['reset']:'';

        if($reset==1)
        {
            $contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 1;");
            $submission = DAO::getSingleValue($link, "SELECT submission FROM ilr WHERE contract_id IN (SELECT id FROM contracts WHERE contract_year = '$contract_year') ORDER BY submission DESC LIMIT 1;");

            $sql = "SELECT * FROM ilr WHERE submission = '$submission' AND contract_id IN (SELECT id FROM contracts WHERE contract_year = '$contract_year');";
            $st = $link->query($sql);
            if(!$st)
            {
                throw new DatabaseException($link, $sql);
            }
            $rows = $st->fetchAll(PDO::FETCH_ASSOC);
            $ap_rows = Array();
            foreach($rows AS $row)
            {
                $csv_fields = Array();
                $ilr = Ilr2020::loadFromXML($row['ilr']);
                $learnrefnumber = $ilr->LearnRefNumber."";
                $tr_id = $row['tr_id'];
                $family_name = $ilr->FamilyName."";
                $given_names = $ilr->GivenNames."";
                $dob = $ilr->DateOfBirth."";
                $ni = $ilr->NINumber."";
                $sex = $ilr->Sex."";
                $uln = $ilr->ULN."";
                $xpath = $ilr->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode");
                $ppe = (empty($xpath)) ? '' : $xpath[0];

                foreach($ilr->LearningDelivery as $delivery)
                {
                    $csv_fields = Array();
                    $csv_fields['TRID'] = $tr_id;
                    $csv_fields['LearnRefNumber'] = $learnrefnumber;
                    $csv_fields['FamilyName'] = $family_name;
                    $csv_fields['GivenNames'] = $given_names;
                    $csv_fields['DOB'] = Date::toMySQL($dob);
                    $csv_fields['NINumber'] = $ni;
                    $csv_fields['Sex'] = $sex;
                    $csv_fields['PostcodePrior'] = $ppe;
                    $csv_fields['ULN'] = $uln;
                    $csv_fields['LearnAimRef'] = $delivery->LearnAimRef."";
                    $csv_fields['LearnStartDate'] = Date::toMySQL($delivery->LearnStartDate."");
                    $csv_fields['PlannedEndDate'] = Date::toMySQL($delivery->LearnPlanEndDate."");
                    $csv_fields['ActEndDate'] = Date::toMySQL($delivery->LearnActEndDate."");
                    $csv_fields['CompStatus'] = $delivery->CompStatus."";
                    $csv_fields['WithdrawReason'] = $delivery->WithdrawReason."";

                    $lsf = "";
                    $eef = "";
                    foreach($delivery->LearningDeliveryFAM as $lsf)
                    {
                        if($lsf->LearnDelFAMType=='LSF' && ("".$lsf->LearnDelFAMDateFrom)!='' && ("".$lsf->LearnDelFAMDateTo)!='')
                        {
                            $lsf = "1";
                        }
                        if(isset($lsf->LearnDelFAMType) and $lsf->LearnDelFAMType=="EEF" and isset($lsf->LearnDelFAMCode) and $lsf->LearnDelFAMCode!='undefined')
                        {
                            $eef = $lsf->LearnDelFAMCode."";
                        }
                    }
                    $csv_fields['LSFCode'] = $lsf;
                    $csv_fields['EEFCode'] = $eef;

                    $ap_rows[] = $csv_fields;
                }
            }

            DAO::execute($link, "truncate PDSAT");
            DAO::multipleRowInsert($link, "PDSAT", $ap_rows);
        }

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_pdsat", "View PDSAT Reports");

        $view = ViewPDSAT::getInstance($link);
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_pdsat.php');
    }
}
?>