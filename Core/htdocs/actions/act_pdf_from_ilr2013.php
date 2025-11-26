<?php

use setasign\Fpdi\Fpdi;

class pdf_from_ilr2013 implements IAction
{
	public function execute(PDO $link)
	{
		ini_set("ignore_user_abort", 1); // Required to allow the PHP script time to delete the temporary PDF file

		$xml = isset($_REQUEST['xml']) ? $_REQUEST['xml'] : '';
		$contract_id = isset($_REQUEST['contract_id']) ? $_REQUEST['contract_id'] : '';

		$vo = Ilr2013::loadFromXML($xml);

		//	$_SESSION['bc']->add($link, "do.php?_action=pdf_from_ilr2011&xml=" . $xml, "ILR PDF");
		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new FPDI();

		if (DB_NAME == 'am_lead')
			$pagecount = $pdf->setSourceFile('ilr2013v2.pdf');
		else
			$pagecount = $pdf->setSourceFile('ilr2013.pdf');


		$tpl = $pdf->ImportPage(1);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->SetFont('Arial', '', 10);


		$con = Contract::loadFromDatabase($link, $contract_id);

		if (isset($con->ukprn))
			$pdf->Text(63, 10, strtoupper($this->spaceout($con->ukprn, 6)));

		$pdf->Text(145, 10, strtoupper($this->spaceout($vo->PrevUKPRN, 6)));

		if ($vo->ULN != '') {
			$pdf->Text(219, 10, $this->spaceout(substr($vo->ULN, 0, 5), 5));
			$pdf->Text(255, 10, $this->spaceout(substr($vo->ULN, 5, 5), 6));
		}

		if ($vo->LearnRefNumber != '') {
			$pcode = str_replace(" ", "", $vo->LearnRefNumber);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(74, 20, $this->spaceout($matches[1][0], 6));
			$pdf->Text(120, 20, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->PrevLearnRefNumber != '') {
			$pcode = str_replace(" ", "", $vo->PrevLearnRefNumber);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(201, 20, $this->spaceout($matches[1][0], 6));
			$pdf->Text(247, 20, $this->spaceout($matches[2][0], 6));
		}

		$pdf->Text(33, 41, strtoupper($vo->FamilyName));
		$pdf->Text(140, 41, strtoupper($vo->GivenNames));

		if ($vo->DateOfBirth != '' && $vo->DateOfBirth != '00000000') {
			$dob = Date::toShort($vo->DateOfBirth);
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $dob, $matches);
			$pdf->Text(229, 41, $this->spaceout($matches[1][0], 5));
			$pdf->Text(246, 41, $this->spaceout($matches[2][0], 5));
			$pdf->Text(278, 41, $this->spaceout($matches[4][0], 5));
		}

		$xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine1');
		$add1 = (empty($xpath)) ? '' : (string)$xpath[0];
		$pdf->Text(33, 50, $add1);
		$xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine2');
		$add2 = (empty($xpath)) ? '' : (string)$xpath[0];
		$pdf->Text(123, 50, $add2);
		$pdf->Text(240, 50, $vo->Domicile);

		$xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine3');
		$add3 = (empty($xpath)) ? '' : (string)$xpath[0];
		$pdf->Text(33, 62, strtoupper($add3));

		$xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine4');
		$add4 = (empty($xpath)) ? '' : (string)$xpath[0];
		$pdf->Text(123, 62, strtoupper($add4));

		$xpath = $vo->xpath('/Learner/LearnerContact/TelNumber');
		$tel = (empty($xpath)) ? '' : $xpath[0];
		$pdf->Text(235, 62, $tel);

		$xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode");
		$cp = (empty($xpath)) ? '' : $xpath[0];
		$matches = array();
		if ($cp != '') {
			$pcode = str_replace(" ", "", $cp);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			@$pdf->Text(33, 73, $this->spaceout($matches[1][0], 5));
			@$pdf->Text(71, 73, $this->spaceout($matches[2][0], 5));
		}

		$xpath = $vo->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode");
		$ppe = (empty($xpath)) ? '' : $xpath[0];
		if ($ppe != '') {
			$pcode = str_replace(" ", "", $ppe);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			@$pdf->Text(123, 73, $this->spaceout($matches[1][0], 5));
			@$pdf->Text(161, 73, $this->spaceout($matches[2][0], 5));
		}


		if ($vo->NINumber != '') {
			preg_match_all("/(^[a-zA-Z]{2})([0-9]{2})([0-9]{2})([0-9]{2})([a-zA-Z]{1})/", $vo->NINumber, $matches);

			@$pdf->Text(215, 73, $this->spaceout($matches[1][0], 6));
			@$pdf->Text(233, 73, $this->spaceout($matches[2][0], 6));
			@$pdf->Text(250, 73, $this->spaceout($matches[3][0], 6));
			@$pdf->Text(268, 73, $this->spaceout($matches[4][0], 6));
			@$pdf->Text(285, 73, $this->spaceout($matches[5][0], 6));
		}

		$xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email");
		$email = (empty($xpath)) ? '' : $xpath[0];
		$pdf->Text(33, 82, $email);
		$pdf->Text(232, 52, $vo->Sex);
		$pdf->Text(259, 52, $this->spaceout($vo->Ethnicity, 5));
		$pdf->Text(34, 143, $this->spaceout(str_pad($vo->PriorAttain, 2, '0', STR_PAD_LEFT), 5));
		if ($vo->LLDDHealthProb == 2)
			$pdf->Text(132, 108, 'N');
		elseif ($vo->LLDDHealthProb == 1)
			$pdf->Text(132, 108, 'Y');
		else
			$pdf->Text(132, 108, $vo->LLDDHealthProb);

		$xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode");
		$ds = (empty($xpath)) ? '' : $xpath[0];
		$xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode");
		$ld = (empty($xpath)) ? '' : $xpath[0];
		$pdf->Text(218, 108, $this->spaceout(str_pad($ds, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(270, 108, $this->spaceout(str_pad($ld, 2, '0', STR_PAD_LEFT), 5));

		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LDA']/LearnFAMCode");
		$lda = (empty($xpath[0])) ? '' : (string)$xpath[0];
		$pdf->Text(122, 119, $this->spaceout(str_pad($lda, 1, ' ', STR_PAD_LEFT), 5));
		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='EHC']/LearnFAMCode");
		$ehc = (empty($xpath[0])) ? '' : (string)$xpath[0];
		$pdf->Text(181, 119, $this->spaceout(str_pad($ehc, 1, ' ', STR_PAD_LEFT), 5));
		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='DLA']/LearnFAMCode");
		$dla = (empty($xpath[0])) ? '' : (string)$xpath[0];
		$pdf->Text(243, 119, $this->spaceout(str_pad($dla, 1, ' ', STR_PAD_LEFT), 5));

		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='ALS']/LearnFAMCode");
		$als = (empty($xpath[0])) ? '' : (string)$xpath[0];
		$pdf->Text(87, 129, $this->spaceout(str_pad($als, 1, ' ', STR_PAD_LEFT), 5));

		$pdf->Text(166, 131, $this->spaceout(str_pad($vo->ALSCost, 1, ' ', STR_PAD_LEFT), 6));

		$pdf->Text(92, 143, $this->spaceout(str_pad($vo->PlanLearnHours, 1, ' ', STR_PAD_LEFT), 6));
		$pdf->Text(190, 143, $this->spaceout(str_pad($vo->PlanEEPHours, 1, ' ', STR_PAD_LEFT), 6));

		if ($vo->Accom == '5')
			$pdf->Text(282, 143, $this->spaceout(str_pad("Y", 1, ' ', STR_PAD_LEFT), 6));
		else
			$pdf->Text(240, 143, $this->spaceout(str_pad("N", 1, ' ', STR_PAD_LEFT), 6));



		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr1 = (empty($xpath[0])) ? '' : (string)$xpath[0];
		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr2 = (empty($xpath[1])) ? '' : (string)$xpath[1];
		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr3 = (empty($xpath[2])) ? '' : (string)$xpath[2];
		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr4 = (empty($xpath[3])) ? '' : (string)$xpath[3];

		$pdf->Text(34, 156, $this->spaceout($lsr1, 5));
		$pdf->Text(79, 156, $this->spaceout($lsr2, 5));
		$pdf->Text(124, 156, $this->spaceout($lsr3, 5));
		$pdf->Text(169, 156, $this->spaceout($lsr4, 5));

		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
		$nlm1 = (empty($xpath[0])) ? '' : (string)$xpath[0];
		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
		$nlm2 = (empty($xpath[1])) ? '' : (string)$xpath[1];
		$pdf->Text(218, 156, $this->spaceout($nlm1, 6));
		$pdf->Text(271, 156, $this->spaceout($nlm2, 6));

		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='1']/ContPrefCode"));
		$rui1 = (empty($xpath)) ? '' : $xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='2']/ContPrefCode"));
		$rui2 = (empty($xpath)) ? '' : $xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='3']/ContPrefCode"));
		$rui3 = (empty($xpath)) ? '' : $xpath[0];

		if (($rui3 == '3') || ($rui1 == '1' && $rui2 == '2')) {
			$pdf->Image("./images/register/small-tick2.gif", 99, 90.5, 4, 4);
			$pdf->Image("./images/register/small-tick2.gif", 157, 90.5, 4, 4);
		} else {
			if ($rui1 == '1') {
				$pdf->Image("./images/register/small-tick2.gif", 99, 90.5, 4, 4);
			} elseif ($rui2 == '2') {
				$pdf->Image("./images/register/small-tick2.gif", 157, 90.5, 4, 4);
			}
		}

		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='1']/ContPrefCode"));
		$pmc1 = (empty($xpath)) ? '' : $xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='2']/ContPrefCode"));
		$pmc2 = (empty($xpath)) ? '' : $xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='3']/ContPrefCode"));
		$pmc3 = (empty($xpath)) ? '' : $xpath[0];

		if ($pmc1 == '1')
			$pdf->Image("./images/register/small-tick2.gif", 192, 90.5, 4, 4);
		if ($pmc2 == '2')
			$pdf->Image("./images/register/small-tick2.gif", 233, 90.5, 4, 4);
		if ($pmc3 == '3')
			$pdf->Image("./images/register/small-tick2.gif", 274, 90.5, 4, 4);


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

		$row = 44;
		$row2 = 58;
		$emp_count = 0;
		foreach ($vo->LearnerEmploymentStatus as $empstatus) {
			$pdf->Text(33, $row, $this->spaceout(str_pad($empstatus->EmpStat, 2, '0', STR_PAD_LEFT), 5));
			if ($empstatus->DateEmpStatApp != '' && $empstatus->DateEmpStatApp != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $empstatus->DateEmpStatApp, $matches);
				@$pdf->Text(80, $row, $this->spaceout($matches[1][0], 1));
				@$pdf->Text(89, $row, $this->spaceout($matches[2][0], 1));
				@$pdf->Text(100, $row, $this->spaceout($matches[4][0], 1));
			}
			$pdf->Text(131, $row, $this->spaceout($empstatus->EmpId, 6));
			if ($empstatus->WorkLocPostCode != '') {
				$pcode = str_replace(" ", "", $empstatus->WorkLocPostCode);
				preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);
				@$pdf->Text(233, $row, $this->spaceout($matches[1][0], 5));
				@$pdf->Text(271, $row, $this->spaceout($matches[2][0], 5));
			}

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='SEI']/ESMCode");
			$sei = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(32, $row2, $this->spaceout(str_pad($sei, 2, '0', STR_PAD_LEFT), 5));

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='EII']/ESMCode");
			$eii = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(73, $row2, $this->spaceout(str_pad($eii, 2, '0', STR_PAD_LEFT), 5));

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='LOU']/ESMCode");
			$lou = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(115, $row2, $this->spaceout(str_pad($lou, 2, '0', STR_PAD_LEFT), 5));

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='LOE']/ESMCode");
			$lou = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(156, $row2, $this->spaceout(str_pad($lou, 2, '0', STR_PAD_LEFT), 5));

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='BSI']/ESMCode");
			$bsi = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(196, $row2, $this->spaceout(str_pad($bsi, 2, '0', STR_PAD_LEFT), 5));

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='PEI']/ESMCode");
			$pei = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(238, $row2, $this->spaceout(str_pad($pei, 2, '0', STR_PAD_LEFT), 5));

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='RON']/ESMCode");
			$ron = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(278, $row2, $this->spaceout(str_pad($ron, 2, '0', STR_PAD_LEFT), 5));

			if ($emp_count >= 1) {
				$row2 += 23;
				$row += 23;
			} else {
				$row2 += 30;
				$row += 30;
			}
			$emp_count++;
		}
		$xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon");
		$ProvSpecLearnMon1 = (empty($xpath[0])) ? '' : $xpath[0];
		if ($ProvSpecLearnMon1 != '') {
			$pcode = str_replace(" ", "", $ProvSpecLearnMon1);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(43, 173, $this->spaceout($matches[1][0], 6));
			$pdf->Text(88, 173, $this->spaceout($matches[2][0], 6));
		}
		$xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon");
		$ProvSpecLearnMon2 = (empty($xpath[0])) ? '' : $xpath[0];
		if ($ProvSpecLearnMon2 != '') {
			$pcode = str_replace(" ", "", $ProvSpecLearnMon2);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(135, 173, $this->spaceout($matches[1][0], 6));
			$pdf->Text(181, 173, $this->spaceout($matches[2][0], 6));
		}

		$pdf->Text(253, 173, $this->spaceout($vo->Dest, 5));

		$aim_seq = 0;
		foreach ($vo->LearningDelivery as $delivery) {
			$aim_seq++;
			if ($delivery->AimType == '1') {
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
					$pdf->Text(166, 53, $this->spaceout(str_pad("Y", 1, '0', STR_PAD_LEFT), 6));
				else
					$pdf->Text(166, 53, $this->spaceout(str_pad("N", 1, '0', STR_PAD_LEFT), 6));


				if ($delivery->OrigLearnStartDate != '' && $delivery->OrigLearnStartDate != '00000000' && $delivery->OrigLearnStartDate != 'undefined' &&  $delivery->OrigLearnStartDate != 'dd/mm/yyyy') {
					$sd = Date::toShort($delivery->OrigLearnStartDate);
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $sd, $matches);
					$pdf->Text(237, 53, $this->spaceout($matches[1][0], 1));
					$pdf->Text(245, 53, $this->spaceout($matches[2][0], 1));
					$pdf->Text(256, 53, $this->spaceout($matches[4][0], 1));
				}

				$pdf->Text(32, 63, $this->spaceout(str_pad($delivery->ProgType, 2, '0', STR_PAD_LEFT), 6));
				$pdf->Text(79, 63, $this->spaceout($delivery->FworkCode, 6));
				$pdf->Text(130, 63, $this->spaceout(str_pad($delivery->PwayCode, 3, '0', STR_PAD_LEFT), 6));
				$pdf->Text(194, 63, $this->spaceout(str_pad($delivery->PriorLearnFundAdj, 2, '0', STR_PAD_LEFT), 6));
				$pdf->Text(261, 63, $this->spaceout(str_pad($delivery->OtherFundAdj, 4, '0', STR_PAD_LEFT), 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode");
				$sof = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(32, 83, $this->spaceout(str_pad($sof, 1, '0', STR_PAD_LEFT), 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode");
				$ffi = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(85, 83, $this->spaceout(str_pad($ffi, 1, '0', STR_PAD_LEFT), 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='WPL']/LearnDelFAMCode");
				$wpl = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(125, 83, $this->spaceout(str_pad($wpl, 1, '0', STR_PAD_LEFT), 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='EEF']/LearnDelFAMCode");
				$eef = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(164, 83, $this->spaceout(str_pad($eef, 1, '0', STR_PAD_LEFT), 6));

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
				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ADL']/LearnDelFAMCode");
				$adl = (empty($xpath[0])) ? '' : $xpath[0];
				if ($adl == '1')
					$pdf->Text(249, 93, $this->spaceout(str_pad("Y", 1, '0', STR_PAD_LEFT), 6));
				else
					$pdf->Text(249, 93, $this->spaceout(str_pad("N", 1, '0', STR_PAD_LEFT), 6));

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
				$pdf->Text(30, 124, $this->spaceout(str_pad($ldm1, 1, '0', STR_PAD_LEFT), 6));
				$ldm2 = (empty($xpath[1])) ? '' : $xpath[1];
				$pdf->Text(82, 124, $this->spaceout(str_pad($ldm2, 1, '0', STR_PAD_LEFT), 6));
				$ldm3 = (empty($xpath[2])) ? '' : $xpath[2];
				$pdf->Text(136, 124, $this->spaceout(str_pad($ldm3, 1, '0', STR_PAD_LEFT), 6));
				$ldm4 = (empty($xpath[3])) ? '' : $xpath[3];
				$pdf->Text(189, 124, $this->spaceout(str_pad($ldm4, 1, '0', STR_PAD_LEFT), 6));

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon");
				$ProvSpecDelMonA = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonA != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonA);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(59, 143, $this->spaceout($matches[1][0], 6));
					$pdf->Text(104, 143, $this->spaceout($matches[2][0], 6));
				}
				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon");
				$ProvSpecDelMonB = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonB != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonB);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(161, 143, $this->spaceout($matches[1][0], 6));
					$pdf->Text(207, 143, $this->spaceout($matches[2][0], 6));
				}
				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='C']/ProvSpecDelMon");
				$ProvSpecDelMonC = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonC != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonC);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(59, 154, $this->spaceout($matches[1][0], 6));
					$pdf->Text(104, 154, $this->spaceout($matches[2][0], 6));
				}
				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='D']/ProvSpecDelMon");
				$ProvSpecDelMonD = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonD != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonD);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(161, 154, $this->spaceout($matches[1][0], 6));
					$pdf->Text(207, 154, $this->spaceout($matches[2][0], 6));
				}


				//if ($delivery->LearnActEndDate != '' && $delivery->LearnActEndDate != '00000000')
				if (isset($delivery->LearnActEndDate) && ("" . $delivery->LearnActEndDate) != '' && ("" . $delivery->LearnActEndDate) != '00000000' && ("" . $delivery->LearnActEndDate) != 'dd/mm/yyyy') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnActEndDate, $matches);
					$pdf->Text(48, 172, $this->spaceout($matches[1][0], 1));
					$pdf->Text(57, 172, $this->spaceout($matches[2][0], 1));
					$pdf->Text(69, 172, $this->spaceout($matches[4][0], 1));
				}
				$pdf->Text(115, 172, $delivery->CompStatus);
				$pdf->Text(175, 172, $this->spaceout(str_pad($delivery->WithdrawReason, 2, '0', STR_PAD_LEFT), 5));
				$pdf->Text(235, 172, $delivery->Outcome);

				if (isset($delivery->AchDate) && ("" . $delivery->AchDate) != '' && ("" . $delivery->AchDate) != '00000000' && ("" . $delivery->AchDate) != 'dd/mm/yyyy') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->AchDate, $matches);
					$pdf->Text(48, 182, $this->spaceout($matches[1][0], 1));
					$pdf->Text(57, 182, $this->spaceout($matches[2][0], 1));
					$pdf->Text(69, 182, $this->spaceout($matches[4][0], 1));
				}
			} elseif ($delivery->AimType != '1') {
				$tpl = $pdf->ImportPage(4);
				$s = $pdf->getTemplatesize($tpl);
				$pdf->AddPage('P', array($s['width'], $s['height']));
				$pdf->useTemplate($tpl);

				$pdf->Text(65, 17, $vo->GivenNames . ' ' . $vo->FamilyName);
				if ($vo->LearnRefNumber != '') {
					$pcode = str_replace(" ", "", $vo->LearnRefNumber);
					preg_match_all("/([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})/", $pcode, $matches);
					$pdf->Text(159, 17, $this->spaceout($matches[1][0], 6));
					$pdf->Text(181, 17, $this->spaceout($matches[2][0], 6));
					$pdf->Text(204, 17, $this->spaceout($matches[3][0], 6));
					$pdf->Text(226, 17, $this->spaceout($matches[4][0], 6));
				}

				$pdf->Text(23, 42, $this->spaceout($delivery->AimType, 5));
				$pdf->Text(59, 42, $this->spaceout(str_pad($aim_seq, 2, '0', STR_PAD_LEFT), 5));
				if ($delivery->LearnAimRef != '') {
					$pcode = str_replace(" ", "", $delivery->LearnAimRef);
					preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

					$pdf->Text(98, 42, $this->spaceout($matches[1][0], 6));
					$pdf->Text(128, 42, $this->spaceout($matches[2][0], 6));
				}
				$pdf->Text(179, 42, $this->spaceout($delivery->FundModel, 5));
				if ($delivery->DelLocPostCode != '') {
					$pcode = str_replace(" ", "", $delivery->DelLocPostCode);
					preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

					@$pdf->Text(233, 42, $this->spaceout($matches[1][0], 5));
					@$pdf->Text(271, 42, $this->spaceout($matches[2][0], 5));
				}

				if ($delivery->LearnStartDate != '' && $delivery->LearnStartDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnStartDate, $matches);
					$pdf->Text(25, 52, $this->spaceout($matches[1][0], 1));
					$pdf->Text(34, 52, $this->spaceout($matches[2][0], 1));
					$pdf->Text(46, 52, $this->spaceout($matches[4][0], 1));
				}
				if ($delivery->LearnPlanEndDate != '' && $delivery->LearnPlanEndDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnPlanEndDate, $matches);
					$pdf->Text(89, 52, $this->spaceout($matches[1][0], 1));
					$pdf->Text(97, 52, $this->spaceout($matches[2][0], 1));
					$pdf->Text(109, 52, $this->spaceout($matches[4][0], 1));
				}
				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode");
				$res = (empty($xpath[0])) ? '' : $xpath[0];
				if ($res == '1')
					$pdf->Text(152, 52, $this->spaceout("Y", 6));
				else
					$pdf->Text(152, 52, $this->spaceout("N", 6));
				if ($delivery->OrigLearnStartDate != '' && $delivery->OrigLearnStartDate != '00000000' && $delivery->OrigLearnStartDate != 'undefined' && $delivery->OrigLearnStartDate != 'dd/mm/yyyy') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->OrigLearnStartDate, $matches);
					$pdf->Text(175, 52, $this->spaceout($matches[1][0], 1));
					$pdf->Text(184, 52, $this->spaceout($matches[2][0], 1));
					$pdf->Text(196, 52, $this->spaceout($matches[4][0], 1));
				}
				if ($delivery->PartnerUKPRN != '') {
					$pcode = str_replace(" ", "", $delivery->PartnerUKPRN);
					preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

					$pdf->Text(233, 52, $this->spaceout($matches[1][0], 6));
					$pdf->Text(263, 52, $this->spaceout($matches[2][0], 6));
				}

				$pdf->Text(35, 65, $this->spaceout(str_pad($delivery->ProgType, 2, '0', STR_PAD_LEFT), 5));
				$pdf->Text(84, 65, $this->spaceout($delivery->FworkCode, 6));
				$pdf->Text(137, 65, $this->spaceout(str_pad($delivery->PwayCode, 3, '0', STR_PAD_LEFT), 6));
				$pdf->Text(203, 65, $this->spaceout(str_pad($delivery->PriorLearnFundAdj, 2, '0', STR_PAD_LEFT), 6));
				$pdf->Text(263, 65, $this->spaceout(str_pad($delivery->OtherFundAdj, 4, '0', STR_PAD_LEFT), 6));

				if ($delivery->ESFProjDosNumber != '' && $delivery->ESFProjDosNumber != 'undefined') {
					$pcode = str_replace(" ", "", $delivery->ESFProjDosNumber);
					preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,4})/", $pcode, $matches);
					$pdf->Text(37, 85, $this->spaceout($matches[1][0], 6));
					$pdf->Text(75, 85, $this->spaceout($matches[2][0], 6));
				}
				if ($delivery->ESFLocProjNumber != 'undefined')
					$pdf->Text(167, 84, $this->spaceout($delivery->ESFLocProjNumber, 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SOF']/LearnDelFAMCode");
				$sof = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(35, 106, $this->spaceout(str_pad($sof, 1, ' ', STR_PAD_LEFT), 5));
				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode");
				$ffi = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(87, 106, $this->spaceout(str_pad($ffi, 1, ' ', STR_PAD_LEFT), 1));
				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='WPL']/LearnDelFAMCode");
				$wpl = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(125, 106, $this->spaceout(str_pad($wpl, 1, ' ', STR_PAD_LEFT), 1));
				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='EEF']/LearnDelFAMCode");
				$eef = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(165, 106, $this->spaceout(str_pad($eef, 1, ' ', STR_PAD_LEFT), 1));
				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='NSA']/LearnDelFAMCode");
				$nsa = (empty($xpath[0])) ? '' : $xpath[0];
				if ($nsa != 'undefined')
					$pdf->Text(185, 106, $this->spaceout(str_pad($nsa, 2, ' ', STR_PAD_LEFT), 1));

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon");
				$ProvSpecDelMonA = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonA != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonA);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(59, 139, $this->spaceout($matches[1][0], 6));
					$pdf->Text(104, 139, $this->spaceout($matches[2][0], 6));
				}

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon");
				$ProvSpecDelMonB = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonB != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonB);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(159, 139, $this->spaceout($matches[1][0], 6));
					$pdf->Text(205, 139, $this->spaceout($matches[2][0], 6));
				}

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='C']/ProvSpecDelMon");
				$ProvSpecDelMonC = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonC != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonC);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(59, 151, $this->spaceout($matches[1][0], 6));
					$pdf->Text(104, 151, $this->spaceout($matches[2][0], 6));
				}

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='D']/ProvSpecDelMon");
				$ProvSpecDelMonD = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonD != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonD);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(159, 151, $this->spaceout($matches[1][0], 6));
					$pdf->Text(205, 151, $this->spaceout($matches[2][0], 6));
				}

				if ($delivery->LearnActEndDate != '' && $delivery->LearnActEndDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnActEndDate, $matches);
					@$pdf->Text(32, 169, $this->spaceout($matches[1][0], 1));
					@$pdf->Text(42, 169, $this->spaceout($matches[2][0], 1));
					@$pdf->Text(53, 169, $this->spaceout($matches[4][0], 1));
				}
				$pdf->Text(96, 169, $this->spaceout(str_pad($delivery->CompStatus, 1, '0', STR_PAD_LEFT), 5));
				$pdf->Text(130, 169, $this->spaceout(str_pad($delivery->WithdrawReason, 2, '0', STR_PAD_LEFT), 5));
				$pdf->Text(178, 169, $delivery->Outcome);
				if ($delivery->AchDate != '' && $delivery->AchDate != '00000000' && $delivery->AchDate != 'dd/mm/yyyy' && $delivery->AchDate != 'undefined') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->AchDate, $matches);
					$pdf->Text(38, 178, $this->spaceout($matches[1][0], 1));
					$pdf->Text(46, 178, $this->spaceout($matches[2][0], 1));
					$pdf->Text(58, 178, $this->spaceout($matches[4][0], 1));
				}

				$pdf->Text(155, 180, $this->spaceout(str_pad($delivery->OutGrade, 6, ' ', STR_PAD_LEFT), 5));
			} elseif ($delivery->AimType == '4' && $delivery->FundModel != '70') {
				$tpl = $pdf->ImportPage(5);
				$s = $pdf->getTemplatesize($tpl);
				$pdf->AddPage('P', array($s['width'], $s['height']));
				$pdf->useTemplate($tpl);

				$pdf->Text(85, 17, $vo->GivenNames . ' ' . $vo->FamilyName);

				if ($vo->LearnRefNumber != '') {
					$pcode = str_replace(" ", "", $vo->LearnRefNumber);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(156, 17, $this->spaceout($matches[1][0], 6));
					$pdf->Text(201, 17, $this->spaceout($matches[2][0], 6));
				}

				if ($delivery->LearnAimRef != '') {
					$pcode = str_replace(" ", "", $delivery->LearnAimRef);
					preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

					$pdf->Text(80, 40, $this->spaceout($matches[1][0], 6));
					$pdf->Text(110, 40, $this->spaceout($matches[2][0], 6));
				}

				if ($delivery->LearnStartDate != '' && $delivery->LearnStartDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnStartDate, $matches);
					$pdf->Text(184, 40, $this->spaceout($matches[1][0], 1));
					$pdf->Text(193, 40, $this->spaceout($matches[2][0], 1));
					$pdf->Text(205, 40, $this->spaceout($matches[4][0], 1));
				}

				if ($delivery->LearnPlanEndDate != '' && $delivery->LearnPlanEndDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnPlanEndDate, $matches);
					$pdf->Text(264, 40, $this->spaceout($matches[1][0], 1));
					$pdf->Text(272, 40, $this->spaceout($matches[2][0], 1));
					$pdf->Text(284, 40, $this->spaceout($matches[4][0], 1));
				}

				$pdf->Text(30, 50, $this->spaceout($delivery->FundModel, 5));
				$pdf->Text(80, 50, $this->spaceout($delivery->ContOrg, 5));
				$pdf->Text(272, 50, $this->spaceout($delivery->PropFundRemain, 6));

				$pdf->Text(30, 60, $this->spaceout(str_pad($delivery->MainDelMeth, 2, '0', STR_PAD_LEFT), 5));

				if ($delivery->DelLocPostCode != '') {
					$pcode = str_replace(" ", "", $delivery->DelLocPostCode);
					preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

					@$pdf->Text(80, 60, $this->spaceout($matches[1][0], 5));
					@$pdf->Text(118, 60, $this->spaceout($matches[2][0], 5));
				}
				if ($delivery->PartnerUKPRN != '') {
					$pcode = str_replace(" ", "", $delivery->PartnerUKPRN);
					preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

					$pdf->Text(180, 60, $this->spaceout($matches[1][0], 6));
					$pdf->Text(210, 60, $this->spaceout($matches[2][0], 6));
				}

				$pdf->Text(272, 60, $this->spaceout(str_pad($delivery->PlanCredVal, 3, '0', STR_PAD_LEFT), 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode");
				$ffi = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(143, 83, $this->spaceout(str_pad($ffi, 1, ' ', STR_PAD_LEFT), 1));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ALN']/LearnDelFAMCode");
				$aln = (empty($xpath[0])) ? '' : $xpath[0];
				if ($aln != 'undefined')
					$pdf->Text(173, 83, $this->spaceout($aln, 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ASN']/LearnDelFAMCode");
				$asn = (empty($xpath[0])) ? '' : $xpath[0];
				if ($asn != 'undefined')
					$pdf->Text(135, 102, $this->spaceout($asn, 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode");
				$res = (empty($xpath[0])) ? '' : $xpath[0];
				if ($res != 'undefined')
					$pdf->Text(177, 102, $this->spaceout($res, 6));

				if ($delivery->LearnActEndDate != '' && $delivery->LearnActEndDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnActEndDate, $matches);
					$pdf->Text(34, 146, $this->spaceout($matches[1][0], 1));
					$pdf->Text(43, 146, $this->spaceout($matches[2][0], 1));
					$pdf->Text(55, 146, $this->spaceout($matches[4][0], 1));
				}

				$pdf->Text(97, 146, $delivery->CompStatus);
				$pdf->Text(130, 146, $this->spaceout(str_pad($delivery->WithdrawReason, 2, '0', STR_PAD_LEFT), 5));
				$pdf->Text(170, 146, $delivery->Outcome);

				if ($delivery->AchDate != '' && $delivery->AchDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->AchDate, $matches);
					$pdf->Text(208, 146, $this->spaceout($matches[1][0], 1));
					$pdf->Text(217, 146, $this->spaceout($matches[2][0], 1));
					$pdf->Text(229, 146, $this->spaceout($matches[4][0], 1));
				}
				$pdf->Text(277, 146, $this->spaceout(str_pad($delivery->ActProgRoute, 2, '0', STR_PAD_LEFT), 6));
			} elseif ($delivery->AimType == '4' && $delivery->FundModel == '70') {
				$tpl = $pdf->ImportPage(6);
				$s = $pdf->getTemplatesize($tpl);
				$pdf->AddPage('P', array($s['width'], $s['height']));
				$pdf->useTemplate($tpl);

				$pdf->Text(85, 17, $vo->GivenNames . ' ' . $vo->FamilyName);

				if ($vo->LearnRefNumber != '') {
					$pcode = str_replace(" ", "", $vo->LearnRefNumber);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(163, 17, $this->spaceout($matches[1][0], 6));
					$pdf->Text(208, 17, $this->spaceout($matches[2][0], 6));
				}

				if ($delivery->LearnAimRef != '') {
					$pcode = str_replace(" ", "", $delivery->LearnAimRef);
					preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

					$pdf->Text(76, 41, $this->spaceout($matches[1][0], 6));
					$pdf->Text(106, 41, $this->spaceout($matches[2][0], 6));
				}

				if ($delivery->LearnStartDate != '' && $delivery->LearnStartDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnStartDate, $matches);
					$pdf->Text(192, 41, $this->spaceout($matches[1][0], 1));
					$pdf->Text(201, 41, $this->spaceout($matches[2][0], 1));
					$pdf->Text(213, 41, $this->spaceout($matches[4][0], 1));
				}

				if ($delivery->LearnPlanEndDate != '' && $delivery->LearnPlanEndDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnPlanEndDate, $matches);
					$pdf->Text(262, 41, $this->spaceout($matches[1][0], 1));
					$pdf->Text(270, 41, $this->spaceout($matches[2][0], 1));
					$pdf->Text(282, 41, $this->spaceout($matches[4][0], 1));
				}
				if ($delivery->DelLocPostCode != '') {
					$pcode = str_replace(" ", "", $delivery->DelLocPostCode);
					preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

					@$pdf->Text(233, 53, $this->spaceout($matches[1][0], 5));
					@$pdf->Text(271, 53, $this->spaceout($matches[2][0], 5));
				}
				if ($delivery->ESFProjDosNumber != '' && $delivery->ESFProjDosNumber != 'undefined') {
					$pcode = str_replace(" ", "", $delivery->ESFProjDosNumber);
					preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

					$pdf->Text(123, 62, $this->spaceout($matches[1][0], 6));
					$pdf->Text(161, 62, $this->spaceout($matches[2][0], 6));
				}
				if ($delivery->ESFLocProjNumber != 'undefined')
					$pdf->Text(270, 62, $this->spaceout($delivery->ESFLocProjNumber, 6));

				if ($delivery->LearnActEndDate != '' && $delivery->LearnActEndDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnActEndDate, $matches);
					$pdf->Text(50, 155, $this->spaceout($matches[1][0], 1));
					$pdf->Text(59, 155, $this->spaceout($matches[2][0], 1));
					$pdf->Text(71, 155, $this->spaceout($matches[4][0], 1));
				}

				$pdf->Text(120, 155, $delivery->CompStatus);
				$pdf->Text(170, 155, $this->spaceout(str_pad($delivery->WithdrawReason, 2, '0', STR_PAD_LEFT), 5));
				$pdf->Text(225, 155, $delivery->Outcome);

				if ($delivery->AchDate != '' && $delivery->AchDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->AchDate, $matches);
					$pdf->Text(208, 146, $this->spaceout($matches[1][0], 1));
					$pdf->Text(217, 146, $this->spaceout($matches[2][0], 1));
					$pdf->Text(229, 146, $this->spaceout($matches[4][0], 1));
				}
				$pdf->Text(277, 155, $this->spaceout(str_pad($delivery->ActProgRoute, 2, '0', STR_PAD_LEFT), 6));
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
