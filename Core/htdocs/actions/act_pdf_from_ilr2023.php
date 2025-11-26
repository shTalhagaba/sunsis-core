<?php

use setasign\Fpdi\Fpdi;

class pdf_from_ilr2023 implements IAction
{
    public function execute(PDO $link)
    {
        ini_set("ignore_user_abort", 1); // Required to allow the PHP script time to delete the temporary PDF file

        $xml = isset($_REQUEST['xml']) ? $_REQUEST['xml'] : '';
        $contract_id = isset($_REQUEST['contract_id']) ? $_REQUEST['contract_id'] : '';

        $vo = Ilr2023::loadFromXML($xml);

        //	$_SESSION['bc']->add($link, "do.php?_action=pdf_from_ilr2011&xml=" . $xml, "ILR PDF");
        // relmes - php 5.3 assigning the return value of new by reference change
        $pdf = new FPDI();

        $pagecount = $pdf->setSourceFile('ilr2023.pdf');


        $tpl = $pdf->ImportPage(1);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);

        $pdf->SetFont('Arial', '', 10);


        $con = Contract::loadFromDatabase($link, $contract_id);

        if (isset($con->ukprn))
            $pdf->Text(43, 25, strtoupper($this->spaceout($con->ukprn, 4)));

        $pdf->Text(133, 25, strtoupper($this->spaceout($vo->PrevUKPRN, 4)));

        if ($vo->ULN != '') {
            $pdf->Text(217, 25, $this->spaceout(substr($vo->ULN, 0, 5), 4));
            $pdf->Text(245, 25, $this->spaceout(substr($vo->ULN, 5, 5), 4));
        }

        if ($vo->LearnRefNumber != '') {
            $pcode = str_replace(" ", "", $vo->LearnRefNumber);
            preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

            $pdf->Text(77, 30, $this->spaceout($matches[1][0], 4));
            $pdf->Text(110, 30, $this->spaceout($matches[2][0], 4));
        }

        if ($vo->PrevLearnRefNumber != '') {
            $pcode = str_replace(" ", "", $vo->PrevLearnRefNumber);
            preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

            $pdf->Text(205, 30, $this->spaceout($matches[1][0], 4));
            $pdf->Text(239, 30, $this->spaceout($matches[2][0], 4));
        }

        $pdf->Text(33, 51, strtoupper($vo->FamilyName));
        $pdf->Text(120, 51, strtoupper($vo->GivenNames));

        if ($vo->DateOfBirth != '' && $vo->DateOfBirth != '00000000') {
            $dob = Date::toShort($vo->DateOfBirth);
            preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $dob, $matches);
            $pdf->Text(211, 51, $this->spaceout($matches[1][0], 4));
            $pdf->Text(228, 51, $this->spaceout($matches[2][0], 4));
            $pdf->Text(245, 51, $this->spaceout($matches[3][0], 4));
            $pdf->Text(256, 51, $this->spaceout($matches[4][0], 4));
        }

        $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine1');
        $add1 = (empty($xpath)) ? '' : (string)$xpath[0];
        $pdf->Text(33, 57, $add1);
        $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine2');
        $add2 = (empty($xpath)) ? '' : (string)$xpath[0];
        $pdf->Text(120, 57, $add2);
        //$pdf->Text(240,47,$vo->Domicile);

        $pdf->Text(212, 57, $vo->Sex);

        $pdf->Text(239, 57, $this->spaceout($vo->Ethnicity, 4));

        $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine3');
        $add3 = (empty($xpath)) ? '' : (string)$xpath[0];
        $pdf->Text(33, 63, strtoupper($add3));

        $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine4');
        $add4 = (empty($xpath)) ? '' : (string)$xpath[0];
        $pdf->Text(120, 63, strtoupper($add4));

        $xpath = $vo->xpath('/Learner/LearnerContact/TelNumber');
        $tel = (empty($xpath)) ? '' : $xpath[0];
        $pdf->Text(230, 63, $tel);

        $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode");
        $cp = (empty($xpath)) ? '' : $xpath[0];
        $matches = array();
        if ($cp != '') {
            $pcode = str_replace(" ", "", $cp);
            preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

            @$pdf->Text(38, 69, $this->spaceout($matches[1][0], 4));
            @$pdf->Text(60, 69, $this->spaceout($matches[2][0], 4));
        }

        $xpath = $vo->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode");
        $ppe = (empty($xpath)) ? '' : $xpath[0];
        if ($ppe != '') {
            $pcode = str_replace(" ", "", $ppe);
            preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

            @$pdf->Text(127, 69, $this->spaceout($matches[1][0], 4));
            @$pdf->Text(149, 69, $this->spaceout($matches[2][0], 4));
        }


        if ($vo->NINumber != '') {
            preg_match_all("/(^[a-zA-Z]{2})([0-9]{2})([0-9]{2})([0-9]{2})([a-zA-Z]{1})/", $vo->NINumber, $matches);

            @$pdf->Text(216, 69, $this->spaceout($matches[1][0], 4));
            @$pdf->Text(228, 69, $this->spaceout($matches[2][0], 4));
            @$pdf->Text(240, 69, $this->spaceout($matches[3][0], 4));
            @$pdf->Text(251, 69, $this->spaceout($matches[4][0], 4));
            @$pdf->Text(262, 69, $this->spaceout($matches[5][0], 4));
        }

        $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email");
        $email = (empty($xpath)) ? '' : $xpath[0];
        $pdf->Text(33, 75, $email);

        $pdf->Text(180, 75, $vo->CampId);

        $xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='4']/ContPrefCode"));
        $rui4 = (empty($xpath)) ? '' : $xpath[0];
        $xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='5']/ContPrefCode"));
        $rui5 = (empty($xpath)) ? '' : $xpath[0];

        if ($rui4 == '4') {
            $pdf->Image("./images/register/small-tick2.gif", 160, 78, 4, 4);
        }
        if ($rui5 == '5') {
            $pdf->Image("./images/register/small-tick2.gif", 238, 78, 4, 4);
        }

        if ($vo->LLDDHealthProb == 1) {
            $pdf->Image("./images/register/small-tick2.gif", 160, 91, 4, 4);
        }

        $y = 100;
        $t = 1;
        foreach ($vo->LLDDandHealthProblem as $lldd) {
            if ($t > 4)
                break;
            $lldd_code = str_split($lldd->LLDDCat);
            if (count($lldd_code) > 1) {
                $pdf->Text(217, $y, $lldd_code[0]);
                $pdf->Text(223, $y, $lldd_code[1]);
            } else {
                $pdf->Text(217, $y, '0');
                $pdf->Text(223, $y, $lldd_code[0]);
            }
            if (isset($lldd->PrimaryLLDD))
                $pdf->Text(234, $y, 'Y');
            $y += 6;
            $t++;
        }

        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='HNS']/LearnFAMCode");
        $hns = (empty($xpath[0])) ? '' : (string)$xpath[0];
        if ($hns == "1") {
            $pdf->Image("./images/register/small-tick2.gif", 70, 102, 4, 4);
        }

        $pdf->Text(70, 102, $hns);

        $pdf->Text(34, 140, $this->spaceout(str_pad($vo->PriorAttain, 2, '0', STR_PAD_LEFT), 5));


        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LDA']/LearnFAMCode");
        $lda = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $pdf->Text(63, 111, $this->spaceout(str_pad($lda, 1, ' ', STR_PAD_LEFT), 5));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='EHC']/LearnFAMCode");
        $ehc = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $pdf->Text(63, 116, $this->spaceout(str_pad($ehc, 1, ' ', STR_PAD_LEFT), 5));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='DLA']/LearnFAMCode");
        $dla = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $pdf->Text(63, 123, $this->spaceout(str_pad($dla, 1, ' ', STR_PAD_LEFT), 5));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='SEN']/LearnFAMCode");
        $sen = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $pdf->Text(63, 130, $this->spaceout(str_pad($sen, 1, ' ', STR_PAD_LEFT), 5));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='EDF']/LearnFAMCode");
        $edf1 = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='EDF']/LearnFAMCode");
        $edf2 = (empty($xpath[1])) ? '' : (string)$xpath[1];
        $pdf->Text(140, 111, $this->spaceout(str_pad($edf1, 1, ' ', STR_PAD_LEFT), 5));
        $pdf->Text(140, 116, $this->spaceout(str_pad($edf2, 1, ' ', STR_PAD_LEFT), 5));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='MCF']/LearnFAMCode");
        $mcf = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $pdf->Text(140, 123, $this->spaceout(str_pad($mcf, 1, ' ', STR_PAD_LEFT), 5));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='ECF']/LearnFAMCode");
        $ecf = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $pdf->Text(140, 129, $this->spaceout(str_pad($ecf, 1, ' ', STR_PAD_LEFT), 5));


        $pdf->Text(193, 126, $this->spaceout(str_pad($vo->ALSCost, 1, ' ', STR_PAD_LEFT), 6));

        $pdf->Text(92, 139, $this->spaceout(str_pad($vo->PlanLearnHours, 1, ' ', STR_PAD_LEFT), 6));
        $pdf->Text(185, 139, $this->spaceout(str_pad($vo->PlanEEPHours, 1, ' ', STR_PAD_LEFT), 6));

        if ($vo->Accom == '5')
            $pdf->Text(282, 139, $this->spaceout(str_pad("Y", 1, ' ', STR_PAD_LEFT), 6));
        else
            $pdf->Text(240, 139, $this->spaceout(str_pad("N", 1, ' ', STR_PAD_LEFT), 6));

        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
        $lsr1 = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
        $lsr2 = (empty($xpath[1])) ? '' : (string)$xpath[1];
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
        $lsr3 = (empty($xpath[2])) ? '' : (string)$xpath[2];
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
        $lsr4 = (empty($xpath[3])) ? '' : (string)$xpath[3];

        $pdf->Text(34, 152, $this->spaceout($lsr1, 5));
        $pdf->Text(79, 152, $this->spaceout($lsr2, 5));
        $pdf->Text(124, 152, $this->spaceout($lsr3, 5));
        $pdf->Text(169, 152, $this->spaceout($lsr4, 5));

        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
        $nlm1 = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
        $nlm2 = (empty($xpath[1])) ? '' : (string)$xpath[1];
        $pdf->Text(218, 152, $this->spaceout($nlm1, 6));
        $pdf->Text(271, 152, $this->spaceout($nlm2, 6));

        $pdf->Text(55, 159, $this->spaceout($vo->MathGrade, 5));
        $pdf->Text(136, 159, $this->spaceout($vo->EngGrade, 5));

        $FME = '';
        $LearnerFAMFME = '';
        foreach ($vo->LearnerFAM as $_lFAM) {
            if ($_lFAM->LearnFAMType->__toString() == 'FME')
                $FME = $_lFAM->LearnFAMCode->__toString();
        }
        //$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='FME']/LearnFAMCode"); $fme = (empty($xpath[0]))?'':(string)$xpath[0];
        $pdf->Text(207, 159, $this->spaceout(str_pad($FME, 1, ' ', STR_PAD_LEFT), 5));
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='PPE']/LearnFAMCode");
        $ppe1 = (empty($xpath[0])) ? '' : (string)$xpath[0];
        $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='PPE']/LearnFAMCode");
        $ppe2 = (empty($xpath[1])) ? '' : (string)$xpath[1];
        $pdf->Text(278, 159, $this->spaceout($ppe1, 6));
        $pdf->Text(285, 159, $this->spaceout($ppe2, 6));

        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if ($tr_id != '') {
            $username = DAO::getSingleValue($link, "SELECT username FROM tr WHERE tr.id = '{$tr_id}'");
            if (file_exists(Repository::getRoot() . '/' . $username . '/learner_signature.png')) {
                $learner_signature = "<img src='" . Repository::getRoot() . "/" . $username . "/learner_signature.png" . "' />";
                $pdf->Image(Repository::getRoot() . "/" . $username . "/learner_signature.png", 196, 165, 0, 10);
            }
            if (SystemConfig::getEntityValue($link, 'module_onboarding')) {
                $sql = <<<SQL
SELECT
  DATE_FORMAT(onboarding_log.created, '%d/%m/%Y')
FROM
  onboarding_log INNER JOIN ob_learners ON onboarding_log.`ob_learner_id` = ob_learners.`id`
  INNER JOIN users ON ob_learners.`user_id` = users.`id`
  INNER JOIN tr ON users.`username` = tr.`username`
WHERE tr.id = '$tr_id'
  AND SUBJECT = 'FORM COMPLETED BY LEARNER' ;
SQL;
                $date_signed = DAO::getSingleValue($link, $sql);
                if ($date_signed != '') {
                    $date_signed = Date::to($date_signed, 'y-m-d');
                    $date_signed = explode('-', $date_signed);
                    if (count($date_signed) == 3) {
                        $pdf->Text(225, 190, $date_signed[2]);
                        $pdf->Text(232, 190, $date_signed[1]);
                        $pdf->Text(245, 190, $date_signed[0]);
                    }
                }
            }
        }


        /*$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='1']/ContPrefCode"));
        $pmc1 = (empty($xpath))?'':$xpath[0];
        $xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='2']/ContPrefCode"));
        $pmc2 = (empty($xpath))?'':$xpath[0];
        $xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='3']/ContPrefCode"));
        $pmc3 = (empty($xpath))?'':$xpath[0];

        if($pmc1=='1')
            $pdf->Image("./images/register/small-tick2.gif",192,86,4,4);
        if($pmc2=='2')
            $pdf->Image("./images/register/small-tick2.gif",233,86,4,4);
        if($pmc3=='3')
            $pdf->Image("./images/register/small-tick2.gif",274,86,4,4);*/


        $tpl = $pdf->ImportPage(2);
        $s = $pdf->getTemplatesize($tpl);
        $pdf->AddPage('P', array($s['width'], $s['height']));
        $pdf->useTemplate($tpl);

        $pdf->Text(65, 17, $vo->GivenNames . ' ' . $vo->FamilyName);

        if ($vo->LearnRefNumber != '') {
            $pcode = str_replace(" ", "", $vo->LearnRefNumber);
            preg_match_all("/([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})/", $pcode, $matches);

            $pdf->Text(157, 17, $this->spaceout($matches[1][0], 6));
            $pdf->Text(179, 17, $this->spaceout($matches[2][0], 6));
            $pdf->Text(202, 17, $this->spaceout($matches[3][0], 6));
            $pdf->Text(224, 17, $this->spaceout($matches[4][0], 6));
        }

        $row = 40;
        $row2 = 58;
        $emp_count = 0;
        $sem_counter = 1;
        foreach ($vo->LearnerEmploymentStatus as $empstatus) {
            $pdf->Text(33, $row, $this->spaceout(str_pad($empstatus->EmpStat, 2, '0', STR_PAD_LEFT), 5));
            if ($empstatus->DateEmpStatApp != '' && $empstatus->DateEmpStatApp != '00000000') {
                $DateEmpStatApp = new Date("" . $empstatus->DateEmpStatApp);
                $pdf->Text(80, $row, $this->spaceout(str_pad($DateEmpStatApp->getDays(), 2, '0', STR_PAD_LEFT), 1));
                $pdf->Text(89, $row, $this->spaceout(str_pad($DateEmpStatApp->getMonth(), 2, '0', STR_PAD_LEFT), 1));
                $pdf->Text(100, $row, $this->spaceout(substr($DateEmpStatApp->getYear(), 2, 2), 1));
            }
            $pdf->Text(131, $row, $this->spaceout($empstatus->EmpId, 6));
            $pdf->Text(262, $row, $this->spaceout($empstatus->AgreeId, 6));

            if ($sem_counter == 1) {
                $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='SEM']/ESMCode");
                $sem = (empty($xpath[0])) ? '' : $xpath[0];
                @$pdf->Text(262, $row, $this->spaceout($sem));
                @$pdf->Text(300, $row, $empstatus->AgreeId);
            }
            $sem_counter++;

            $SEI = '';
            $EII = '';
            $LOU = '';
            $LOE = '';
            $BSI = '';
            $PEI = '';
            $RON = '';
            foreach ($empstatus as $EmploymentStatusMonitoring) {
                if ($EmploymentStatusMonitoring->ESMType->__toString() == 'SEI' && $EmploymentStatusMonitoring->ESMCode->__toString() != '')
                    $SEI = $EmploymentStatusMonitoring->ESMCode->__toString();
                if ($EmploymentStatusMonitoring->ESMType->__toString() == 'EII' && $EmploymentStatusMonitoring->ESMCode->__toString() != '')
                    $EII = $EmploymentStatusMonitoring->ESMCode->__toString();
                if ($EmploymentStatusMonitoring->ESMType->__toString() == 'LOU' && $EmploymentStatusMonitoring->ESMCode->__toString() != '')
                    $LOU = $EmploymentStatusMonitoring->ESMCode->__toString();
                if ($EmploymentStatusMonitoring->ESMType->__toString() == 'LOE' && $EmploymentStatusMonitoring->ESMCode->__toString() != '')
                    $LOE = $EmploymentStatusMonitoring->ESMCode->__toString();
                if ($EmploymentStatusMonitoring->ESMType->__toString() == 'BSI' && $EmploymentStatusMonitoring->ESMCode->__toString() != '')
                    $BSI = $EmploymentStatusMonitoring->ESMCode->__toString();
                if ($EmploymentStatusMonitoring->ESMType->__toString() == 'PEI' && $EmploymentStatusMonitoring->ESMCode->__toString() != '')
                    $PEI = $EmploymentStatusMonitoring->ESMCode->__toString();
                if ($EmploymentStatusMonitoring->ESMType->__toString() == 'RON' && $EmploymentStatusMonitoring->ESMCode->__toString() != '')
                    $RON = $EmploymentStatusMonitoring->ESMCode->__toString();
            }
            $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='SEI']/ESMCode");
            $sei = (empty($xpath[0])) ? '' : $xpath[0];
            @$pdf->Text(32, $row2, $this->spaceout(str_pad($SEI, 2, '0', STR_PAD_LEFT), 5));

            $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='EII']/ESMCode");
            $eii = (empty($xpath[0])) ? '' : $xpath[0];
            @$pdf->Text(73, $row2, $this->spaceout(str_pad($EII, 2, '0', STR_PAD_LEFT), 5));

            $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='LOU']/ESMCode");
            $lou = (empty($xpath[0])) ? '' : $xpath[0];
            @$pdf->Text(115, $row2, $this->spaceout(str_pad($LOU, 2, '0', STR_PAD_LEFT), 5));

            $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='LOE']/ESMCode");
            $lou = (empty($xpath[0])) ? '' : $xpath[0];
            @$pdf->Text(156, $row2, $this->spaceout(str_pad($LOE, 2, '0', STR_PAD_LEFT), 5));

            $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='BSI']/ESMCode");
            $bsi = (empty($xpath[0])) ? '' : $xpath[0];
            @$pdf->Text(196, $row2, $this->spaceout(str_pad($BSI, 2, '0', STR_PAD_LEFT), 5));

            $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='PEI']/ESMCode");
            $pei = (empty($xpath[0])) ? '' : $xpath[0];
            @$pdf->Text(238, $row2, $this->spaceout(str_pad($PEI, 2, '0', STR_PAD_LEFT), 5));

            $xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='RON']/ESMCode");
            $ron = (empty($xpath[0])) ? '' : $xpath[0];
            @$pdf->Text(278, $row2, $this->spaceout(str_pad($RON, 2, '0', STR_PAD_LEFT), 5));

            if ($emp_count >= 1) {
                $row2 += 27;
                $row += 29;
            } else {
                $row2 += 33;
                $row += 33;
            }
            $emp_count++;
        }
        $xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon");
        $ProvSpecLearnMon1 = (empty($xpath[0])) ? '' : $xpath[0];
        if ($ProvSpecLearnMon1 != '') {
            $pcode = str_replace(" ", "", $ProvSpecLearnMon1);
            preg_match_all("/([a-zA-Z0-9]{0,18})([a-zA-Z0-9]{0,18})/", $pcode, $matches);

            $pdf->Text(10, 195, $this->spaceout($matches[1][0], 6));
        }
        $xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon");
        $ProvSpecLearnMon2 = (empty($xpath[0])) ? '' : $xpath[0];
        if ($ProvSpecLearnMon2 != '') {
            $pcode = str_replace(" ", "", $ProvSpecLearnMon2);
            preg_match_all("/([a-zA-Z0-9]{0,18})([a-zA-Z0-9]{0,18})/", $pcode, $matches);

            $pdf->Text(154, 195, $this->spaceout($matches[1][0], 6));
        }


        $aim_seq = 0;
        foreach ($vo->LearningDelivery as $delivery) {
            $aim_seq++;
            if (true) {
                $tpl = $pdf->ImportPage(3);
                $s = $pdf->getTemplatesize($tpl);
                $pdf->AddPage('P', array($s['width'], $s['height']));
                $pdf->useTemplate($tpl);

                $pdf->Text(65, 17, $vo->GivenNames . ' ' . $vo->FamilyName);
                if ($vo->LearnRefNumber != '') {
                    $pcode = str_replace(" ", "", $vo->LearnRefNumber);
                    preg_match_all("/([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})/", $pcode, $matches);
                    $pdf->Text(157, 17, $this->spaceout($matches[1][0], 6));
                    $pdf->Text(179, 17, $this->spaceout($matches[2][0], 6));
                    $pdf->Text(202, 17, $this->spaceout($matches[3][0], 6));
                    $pdf->Text(224, 17, $this->spaceout($matches[4][0], 6));
                }

                $pdf->Text(55, 43, $this->spaceout(str_pad($aim_seq, 2, '0', STR_PAD_LEFT), 6));
                $pdf->Text(183, 43, $this->spaceout(str_pad($delivery->FundModel, 2, '0', STR_PAD_LEFT), 6));

                if ($delivery->DelLocPostCode != '') {
                    $pcode = str_replace(" ", "", $delivery->DelLocPostCode);
                    preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

                    @$pdf->Text(232, 43, $this->spaceout($matches[1][0], 5));
                    @$pdf->Text(270, 43, $this->spaceout($matches[2][0], 5));
                }

                if ($delivery->LearnStartDate != '' && $delivery->LearnStartDate != '00000000') {
                    $sd = Date::toShort($delivery->LearnStartDate);
                    preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $sd, $matches);
                    $pdf->Text(36, 53, $this->spaceout($matches[1][0], 1));
                    $pdf->Text(44, 53, $this->spaceout($matches[2][0], 1));
                    $pdf->Text(55, 53, $this->spaceout($matches[4][0], 1));
                }

                if ($delivery->LearnPlanEndDate != '' && $delivery->LearnPlanEndDate != '00000000') {
                    $ed = Date::toShort($delivery->LearnPlanEndDate);
                    preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $ed, $matches);
                    $pdf->Text(99, 53, $this->spaceout($matches[1][0], 1));
                    $pdf->Text(107, 53, $this->spaceout($matches[2][0], 1));
                    $pdf->Text(118, 53, $this->spaceout($matches[4][0], 1));
                }

                $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode");
                $res = (empty($xpath[0])) ? '' : $xpath[0];
                if ($res == '1')
                    $pdf->Text(182, 53, $this->spaceout(str_pad("Y", 1, '0', STR_PAD_LEFT), 6));
                else
                    $pdf->Text(182, 53, $this->spaceout(str_pad("N", 1, '0', STR_PAD_LEFT), 6));


                if ($delivery->OrigLearnStartDate != '' && $delivery->OrigLearnStartDate != '00000000' && $delivery->OrigLearnStartDate != 'undefined' &&  $delivery->OrigLearnStartDate != 'dd/mm/yyyy') {
                    $sd = Date::toShort($delivery->OrigLearnStartDate);
                    preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $sd, $matches);
                    $pdf->Text(234, 53, $this->spaceout($matches[1][0], 1));
                    $pdf->Text(242, 53, $this->spaceout($matches[2][0], 1));
                    $pdf->Text(253, 53, $this->spaceout($matches[4][0], 1));
                }

                $pdf->Text(32, 63, $this->spaceout(str_pad($delivery->ProgType, 2, '0', STR_PAD_LEFT), 6));
                $pdf->Text(79, 63, $this->spaceout($delivery->FworkCode, 6));
                $pdf->Text(130, 63, $this->spaceout(str_pad($delivery->PwayCode, 3, '0', STR_PAD_LEFT), 6));
                $pdf->Text(180, 63, $this->spaceout(str_pad($delivery->StdCode, 3, '0', STR_PAD_LEFT), 6));
                $pdf->Text(233, 63, $this->spaceout(str_pad($delivery->PHours, 2, '0', STR_PAD_LEFT), 6));
                //				$pdf->Text(261,63,$this->spaceout(str_pad($delivery->OtherFundAdj,4,'0',STR_PAD_LEFT),6));

                $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode");
                $sof = (empty($xpath[0])) ? '' : $xpath[0];
                $pdf->Text(32, 83, $this->spaceout(str_pad($sof, 1, '0', STR_PAD_LEFT), 6));

                $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode");
                $ffi = (empty($xpath[0])) ? '' : $xpath[0];
                if ($ffi != 'undefined')
                    $pdf->Text(85, 83, $this->spaceout(str_pad($ffi, 1, '0', STR_PAD_LEFT), 6));

                $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='WPL']/LearnDelFAMCode");
                $wpl = (empty($xpath[0])) ? '' : $xpath[0];
                if ($wpl != 'undefined')
                    $pdf->Text(125, 83, $this->spaceout(str_pad($wpl, 1, '0', STR_PAD_LEFT), 6));

                $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='EEF']/LearnDelFAMCode");
                $eef = (empty($xpath[0])) ? '' : $xpath[0];
                if ($eef != 'undefined')
                    $pdf->Text(164, 83, $this->spaceout(str_pad($eef, 1, '0', STR_PAD_LEFT), 6));

                foreach ($delivery->LearningDeliveryFAM as $ldf) {
                    if ($ldf->LearnDelFAMType == 'DAM' && $ldf->LearnDelFAMCode != '' && $ldf->LearnDelFAMCode != 'undefined') {
                        $pdf->Text(200, 83, $this->spaceout(str_pad($ldf->LearnDelFAMCode, 3, '0', STR_PAD_LEFT), 6));
                        break;
                    }
                }

                foreach ($delivery->LearningDeliveryFAM as $ldf) {
                    if ($ldf->LearnDelFAMType == 'LSF' && $ldf->LearnDelFAMCode != '' && $ldf->LearnDelFAMCode != 'undefined') {
                        if ($ldf->LearnDelFAMCode == '1')
                            $pdf->Text(64, 93, $this->spaceout(str_pad("Y", 1, '0', STR_PAD_LEFT), 6));
                        else
                            $pdf->Text(64, 93, $this->spaceout(str_pad("N", 1, '0', STR_PAD_LEFT), 6));
                        if ($ldf->LearnDelFAMDateFrom != '' && $ldf->LearnDelFAMDateFrom != '00000000') {
                            $sd = Date::toShort($ldf->LearnDelFAMDateFrom);
                            preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $sd, $matches);
                            $pdf->Text(96, 93, $this->spaceout($matches[1][0], 1));
                            $pdf->Text(104, 93, $this->spaceout($matches[2][0], 1));
                            $pdf->Text(115, 93, $this->spaceout($matches[4][0], 1));
                        }
                        if ($ldf->LearnDelFAMDateTo != '' && $ldf->LearnDelFAMDateTo != '00000000') {
                            $sd = Date::toShort($ldf->LearnDelFAMDateTo);
                            preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $sd, $matches);
                            if (isset($matches[1][0]))
                                $pdf->Text(146, 93, $this->spaceout($matches[1][0], 1));
                            if (isset($matches[2][0]))
                                $pdf->Text(154, 93, $this->spaceout($matches[2][0], 1));
                            if (isset($matches[4][0]))
                                $pdf->Text(165, 93, $this->spaceout($matches[4][0], 1));
                        }
                        break;
                    }
                }
                foreach ($delivery->LearningDeliveryFAM as $ldf) {
                    if ($ldf->LearnDelFAMType == 'ALB' && $ldf->LearnDelFAMCode != '' && $ldf->LearnDelFAMCode != 'undefined') {
                        $pdf->Text(64, 103, $this->spaceout(str_pad($ldf->LearnDelFAMCode, 1, '0', STR_PAD_LEFT), 6));
                        if ($ldf->LearnDelFAMDateFrom != '' && $ldf->LearnDelFAMDateFrom != '00000000') {
                            $sd = Date::toShort($ldf->LearnDelFAMDateFrom);
                            preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $sd, $matches);
                            $pdf->Text(96, 103, $this->spaceout($matches[1][0], 1));
                            $pdf->Text(104, 103, $this->spaceout($matches[2][0], 1));
                            $pdf->Text(115, 103, $this->spaceout($matches[4][0], 1));
                        }
                        if ($ldf->LearnDelFAMDateTo != '' && $ldf->LearnDelFAMDateTo != '00000000') {
                            $sd = Date::toShort($ldf->LearnDelFAMDateTo);
                            preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $sd, $matches);
                            $pdf->Text(146, 103, $this->spaceout($matches[1][0], 1));
                            $pdf->Text(154, 103, $this->spaceout($matches[2][0], 1));
                            $pdf->Text(165, 103, $this->spaceout($matches[4][0], 1));
                        }
                        break;
                    }
                }

                $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ASL']/LearnDelFAMCode");
                $asl = (empty($xpath[0])) ? '' : $xpath[0];
                $pdf->Text(30, 113, $this->spaceout(str_pad($asl, 1, '0', STR_PAD_LEFT), 6));

                $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SPP']/LearnDelFAMCode");
                $spp = (empty($xpath[0])) ? '' : $xpath[0];
                $pdf->Text(81, 113, $this->spaceout(str_pad(substr($spp, 2, 3), 1, ' ', STR_PAD_LEFT), 6));

                $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='NSA']/LearnDelFAMCode");
                $nsa = (empty($xpath[0])) ? '' : $xpath[0];
                $pdf->Text(131, 113, $this->spaceout(str_pad($nsa, 2, '0', STR_PAD_LEFT), 6));

                $xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='LDM']/LearnDelFAMCode");
                $ldm1 = (empty($xpath[0])) ? '' : $xpath[0];
                if ($ldm1 != 'undefined')
                    $pdf->Text(30, 124, $this->spaceout(str_pad($ldm1, 1, '0', STR_PAD_LEFT), 6));
                $ldm2 = (empty($xpath[1])) ? '' : $xpath[1];
                if ($ldm2 != 'undefined')
                    $pdf->Text(82, 124, $this->spaceout(str_pad($ldm2, 1, '0', STR_PAD_LEFT), 6));
                $ldm3 = (empty($xpath[2])) ? '' : $xpath[2];
                if ($ldm3 != 'undefined')
                    $pdf->Text(136, 124, $this->spaceout(str_pad($ldm3, 1, '0', STR_PAD_LEFT), 6));
                $ldm4 = (empty($xpath[3])) ? '' : $xpath[3];
                if ($ldm4 != 'undefined')
                    $pdf->Text(189, 124, $this->spaceout(str_pad($ldm4, 1, '0', STR_PAD_LEFT), 6));

                $xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon");
                $ProvSpecDelMonA = (empty($xpath[0])) ? '' : $xpath[0];
                if ($ProvSpecDelMonA != '') {
                    $pcode = str_replace(" ", "", $ProvSpecDelMonA);
                    preg_match_all("/([a-zA-Z0-9]{0,11})([a-zA-Z0-9]{0,11})/", $pcode, $matches);

                    $pdf->Text(59, 160, $this->spaceout($matches[1][0], 6));
                }
                $xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon");
                $ProvSpecDelMonB = (empty($xpath[0])) ? '' : $xpath[0];
                if ($ProvSpecDelMonB != '') {
                    $pcode = str_replace(" ", "", $ProvSpecDelMonB);
                    preg_match_all("/([a-zA-Z0-9]{0,11})([a-zA-Z0-9]{0,11})/", $pcode, $matches);

                    $pdf->Text(161, 160, $this->spaceout($matches[1][0], 6));
                }
                $xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='C']/ProvSpecDelMon");
                $ProvSpecDelMonC = (empty($xpath[0])) ? '' : $xpath[0];
                if ($ProvSpecDelMonC != '') {
                    $pcode = str_replace(" ", "", $ProvSpecDelMonC);
                    preg_match_all("/([a-zA-Z0-9]{0,11})([a-zA-Z0-9]{0,11})/", $pcode, $matches);

                    $pdf->Text(59, 173, $this->spaceout($matches[1][0], 6));
                }
                $xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='D']/ProvSpecDelMon");
                $ProvSpecDelMonD = (empty($xpath[0])) ? '' : $xpath[0];
                if ($ProvSpecDelMonD != '') {
                    $pcode = str_replace(" ", "", $ProvSpecDelMonD);
                    preg_match_all("/([a-zA-Z0-9]{0,11})([a-zA-Z0-9]{0,11})/", $pcode, $matches);

                    $pdf->Text(161, 173, $this->spaceout($matches[1][0], 6));
                }

                /*foreach( $delivery->LearningDeliveryFAM AS $hhs )
                {
                    if($hhs->LearnDelFAMType=='HHS' && $hhs->LearnDelFAMCode=='1')
                    {
                        $pdf->Image("./images/register/small-tick2.gif",38,139,4,4);
                    }
                    if($hhs->LearnDelFAMType=='HHS' && $hhs->LearnDelFAMCode=='2')
                    {
                        $pdf->Image("./images/register/small-tick2.gif",80,139,4,4);
                    }
                    if($hhs->LearnDelFAMType=='HHS' && $hhs->LearnDelFAMCode=='3')
                    {
                        $pdf->Image("./images/register/small-tick2.gif",120,139,4,4);
                    }
                    if($hhs->LearnDelFAMType=='HHS' && $hhs->LearnDelFAMCode=='99')
                    {
                        $pdf->Image("./images/register/small-tick2.gif",170,139,4,4);
                    }
                    if($hhs->LearnDelFAMType=='HHS' && $hhs->LearnDelFAMCode=='98')
                    {
                        $pdf->Image("./images/register/small-tick2.gif",218,139,4,4);
                    }
                }*/

                if (isset($delivery->LearnActEndDate) && ("" . $delivery->LearnActEndDate) != '' && ("" . $delivery->LearnActEndDate) != '00000000' && ("" . $delivery->LearnActEndDate) != 'dd/mm/yyyy') {
                    $LearnActEndDate = new Date("" . $delivery->LearnActEndDate);
                    $pdf->Text(48, 190, $this->spaceout(str_pad($LearnActEndDate->getDays(), 2, '0', STR_PAD_LEFT), 1));
                    $pdf->Text(57, 190, $this->spaceout(str_pad($LearnActEndDate->getMonth(), 2, '0', STR_PAD_LEFT), 1));
                    $pdf->Text(69, 190, $this->spaceout(substr($LearnActEndDate->getYear(), 2, 2), 1));
                }
                $pdf->Text(115, 190, $delivery->CompStatus);
                $pdf->Text(175, 190, $this->spaceout(str_pad($delivery->WithdrawReason, 2, '0', STR_PAD_LEFT), 5));
                $pdf->Text(230, 190, $delivery->Outcome);
            }
        }


        // Prepare directory
        $admin_reports = Repository::getRoot() . '/admin_reports';
        if (is_file($admin_reports)) {
            throw new Exception("admin_reports exists but it is a file and not a directory");
        }
        if (!is_dir($admin_reports)) {
            mkdir($admin_reports);
        }
        //$this->cleanFiles($admin_reports);

        // Generate temporary filename
        $file_path = tempnam($admin_reports, 'tmp');
        rename($file_path, $file_path . '.pdf');
        $file_path .= '.pdf';

        // Write to file
        $pdf->Output($file_path, 'F');

        // Stream file to browser
        $size = filesize($file_path);
        $download = "ilr" . $vo->learnerinformation->L09 . ".pdf";
        header('Content-Type: application/pdf');
        header('Content-Length: ' . $size);
        header('Content-Disposition: attachment; filename=' . $download);
        readfile($file_path);

        // Delete temporary file
        unlink($file_path); // Requires ini_set('ignore_user_abort', 1) -- set at the top of this file

    }

    public function spaceout($strvalue, $n = 4)
    {

        $buffer = "";


        if ($strvalue == '')
            return $buffer;

        $j = mb_strlen($strvalue);
        for ($k = 0; $k < $j; $k++) {
            $char = mb_substr($strvalue, $k, 1);
            // do stuff with $char
            $buffer = $buffer . $char . str_repeat(' ', $n);
        }

        return $buffer;
    }

    function CleanFiles($dir)
    {
        //Delete temporary files
        $temporary_files = glob($dir . '/tmp*');
        foreach ($temporary_files as $absolute_path) {
            unlink($absolute_path);
        }
    }
}
