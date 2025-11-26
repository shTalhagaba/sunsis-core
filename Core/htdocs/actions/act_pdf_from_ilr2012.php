<?php

use setasign\Fpdi\Fpdi;

class pdf_from_ilr2012 implements IAction
{
	public function execute(PDO $link)
	{
		ini_set("ignore_user_abort", 1); // Required to allow the PHP script time to delete the temporary PDF file

		$xml = isset($_REQUEST['xml']) ? $_REQUEST['xml'] : '';
		$contract_id = isset($_REQUEST['contract_id']) ? $_REQUEST['contract_id'] : '';

		$vo = Ilr2012::loadFromXML($xml);

		//	$_SESSION['bc']->add($link, "do.php?_action=pdf_from_ilr2011&xml=" . $xml, "ILR PDF");
		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new FPDI();


		$pagecount = $pdf->setSourceFile('ilr2012.pdf');

		$tpl = $pdf->ImportPage(1);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->SetFont('Arial', '', 10);


		$con = Contract::loadFromDatabase($link, $contract_id);

		if (isset($con->ukprn))
			$pdf->Text(82, 10, strtoupper($this->spaceout($con->ukprn, 6)));
		$pdf->Text(172, 10, strtoupper($this->spaceout($vo->ULN, 6)));

		if ($vo->LearnRefNumber != '') {
			$pcode = str_replace(" ", "", $vo->LearnRefNumber);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(82, 20, $this->spaceout($matches[1][0], 6));
			$pdf->Text(127, 20, $this->spaceout($matches[2][0], 6));
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
		$pdf->Text(170, 83, $vo->Sex);
		$pdf->Text(215, 83, $this->spaceout($vo->Ethnicity, 5));
		$pdf->Text(278, 83, $this->spaceout(str_pad($vo->PriorAttain, 2, '0', STR_PAD_LEFT), 5));
		if ($vo->LLDDHealthProb == 2)
			$pdf->Text(132, 101, 'N');
		elseif ($vo->LLDDHealthProb == 1)
			$pdf->Text(132, 101, 'Y');
		else
			$pdf->Text(132, 101, $vo->LLDDHealthProb);

		$xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode");
		$ds = (empty($xpath)) ? '' : $xpath[0];
		$xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode");
		$ld = (empty($xpath)) ? '' : $xpath[0];
		$pdf->Text(218, 101, $this->spaceout(str_pad($ds, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(270, 101, $this->spaceout(str_pad($ld, 2, '0', STR_PAD_LEFT), 5));

		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr1 = (empty($xpath[0])) ? '' : (string)$xpath[0];
		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr2 = (empty($xpath[1])) ? '' : (string)$xpath[1];
		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr3 = (empty($xpath[2])) ? '' : (string)$xpath[2];
		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode");
		$lsr4 = (empty($xpath[3])) ? '' : (string)$xpath[3];

		$pdf->Text(32, 113, $this->spaceout($lsr1, 5));
		$pdf->Text(77, 113, $this->spaceout($lsr2, 5));
		$pdf->Text(122, 113, $this->spaceout($lsr3, 5));
		$pdf->Text(168, 113, $this->spaceout($lsr4, 5));

		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
		$nlm1 = (empty($xpath[0])) ? '' : (string)$xpath[0];
		$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode");
		$nlm2 = (empty($xpath[1])) ? '' : (string)$xpath[1];
		$pdf->Text(217, 113, $this->spaceout($nlm1, 6));
		$pdf->Text(270, 113, $this->spaceout($nlm2, 6));

		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='1']/ContPrefCode"));
		$rui1 = (empty($xpath)) ? '' : $xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='2']/ContPrefCode"));
		$rui2 = (empty($xpath)) ? '' : $xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='3']/ContPrefCode"));
		$rui3 = (empty($xpath)) ? '' : $xpath[0];

		if (($rui3 == '3') || ($rui1 == '1' && $rui2 == '2')) {
			$pdf->Image("./images/register/small-tick2.gif", 97, 159, 4, 4);
			$pdf->Image("./images/register/small-tick2.gif", 132, 159, 4, 4);
		} else {
			if ($rui1 == '1') {
				$pdf->Image("./images/register/small-tick2.gif", 97, 159, 4, 4);
			} elseif ($rui2 == '2') {
				$pdf->Image("./images/register/small-tick2.gif", 132, 159, 4, 4);
			}
		}

		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='1']/ContPrefCode"));
		$pmc1 = (empty($xpath)) ? '' : $xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='2']/ContPrefCode"));
		$pmc2 = (empty($xpath)) ? '' : $xpath[0];
		$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='3']/ContPrefCode"));
		$pmc3 = (empty($xpath)) ? '' : $xpath[0];

		if ($pmc1 == '1')
			$pdf->Image("./images/register/small-tick2.gif", 157, 159, 4, 4);
		if ($pmc2 == '2')
			$pdf->Image("./images/register/small-tick2.gif", 183, 159, 4, 4);
		if ($pmc3 == '3')
			$pdf->Image("./images/register/small-tick2.gif", 210, 159, 4, 4);


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
			@$pdf->Text(34, $row2, $this->spaceout(str_pad($sei, 2, '0', STR_PAD_LEFT), 5));

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='EII']/ESMCode");
			$eii = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(81, $row2, $this->spaceout(str_pad($eii, 2, '0', STR_PAD_LEFT), 5));

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='LOU']/ESMCode");
			$lou = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(127, $row2, $this->spaceout(str_pad($lou, 2, '0', STR_PAD_LEFT), 5));

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='BSI']/ESMCode");
			$bsi = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(174, $row2, $this->spaceout(str_pad($bsi, 2, '0', STR_PAD_LEFT), 5));

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='PEI']/ESMCode");
			$pei = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(220, $row2, $this->spaceout(str_pad($pei, 2, '0', STR_PAD_LEFT), 5));

			$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='RON']/ESMCode");
			$ron = (empty($xpath[0])) ? '' : $xpath[0];
			@$pdf->Text(266, $row2, $this->spaceout(str_pad($ron, 2, '0', STR_PAD_LEFT), 5));

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


		foreach ($vo->LearningDelivery as $delivery) {
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

				if ($delivery->LearnStartDate != '' && $delivery->LearnStartDate != '00000000') {
					$sd = Date::toShort($delivery->LearnStartDate);
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $sd, $matches);
					$pdf->Text(185, 42, $this->spaceout($matches[1][0], 1));
					$pdf->Text(193, 42, $this->spaceout($matches[2][0], 1));
					$pdf->Text(204, 42, $this->spaceout($matches[4][0], 1));
				}

				if ($delivery->LearnPlanEndDate != '' && $delivery->LearnPlanEndDate != '00000000') {
					$ed = Date::toShort($delivery->LearnPlanEndDate);
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $ed, $matches);
					$pdf->Text(262, 42, $this->spaceout($matches[1][0], 1));
					$pdf->Text(270, 42, $this->spaceout($matches[2][0], 1));
					$pdf->Text(281, 42, $this->spaceout($matches[4][0], 1));
				}

				$pdf->Text(32, 53, $this->spaceout(str_pad($delivery->FundModel, 2, '0', STR_PAD_LEFT), 6));
				$pdf->Text(107, 53, $this->spaceout(str_pad($delivery->ContOrg, 2, '0', STR_PAD_LEFT), 6));
				$pdf->Text(184, 53, $this->spaceout(str_pad($delivery->ProgType, 2, '0', STR_PAD_LEFT), 6));
				$pdf->Text(270, 54, $this->spaceout($delivery->FworkCode, 6));

				$pdf->Text(32, 63, $this->spaceout(str_pad($delivery->PwayCode, 3, '0', STR_PAD_LEFT), 6));
				$pdf->Text(100, 63, $this->spaceout(str_pad($delivery->ProgEntRoute, 2, '0', STR_PAD_LEFT), 6));

				if ($delivery->DelLocPostCode != '') {
					$pcode = str_replace(" ", "", $delivery->DelLocPostCode);
					preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

					@$pdf->Text(184, 63, $this->spaceout($matches[1][0], 5));
					@$pdf->Text(222, 63, $this->spaceout($matches[2][0], 5));
				}

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='NSA']/LearnDelFAMCode");
				$nsa = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(143, 84, $this->spaceout(str_pad($nsa, 2, '0', STR_PAD_LEFT), 6));

				foreach ($delivery->LearningDeliveryFAM as $ldf) {
					if ($ldf->LearnDelFAMType == 'LDM') {
						$pdf->Text(190, 84, $this->spaceout(str_pad($ldf->LearnDelFAMCode, 3, '0', STR_PAD_LEFT), 6));
						break;
					}
				}

				$ldm = 0;
				foreach ($delivery->LearningDeliveryFAM as $ldf) {
					if ($ldf->LearnDelFAMType == 'LDM') {
						$ldm++;
						if ($ldm == 2) {
							$pdf->Text(32, 95, $this->spaceout(str_pad($ldf->LearnDelFAMCode, 3, '0', STR_PAD_LEFT), 6));
						}
						if ($ldm == 3) {
							$pdf->Text(88, 95, $this->spaceout(str_pad($ldf->LearnDelFAMCode, 3, '0', STR_PAD_LEFT), 6));
						}
					}
				}

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='EEF']/LearnDelFAMCode");
				$eef = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(150, 95, $this->spaceout(str_pad($eef, 1, '0', STR_PAD_LEFT), 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode");
				$res = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(190, 95, $this->spaceout(str_pad($res, 1, '0', STR_PAD_LEFT), 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SSP']/LearnDelFAMCode");
				$ssp = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(32, 107, $this->spaceout(str_pad($ssp, 1, ' ', STR_PAD_LEFT), 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SPP']/LearnDelFAMCode");
				$spp = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(102, 107, $this->spaceout(str_pad($spp, 1, ' ', STR_PAD_LEFT), 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='CVE']/LearnDelFAMCode");
				$cve = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(199, 107, $this->spaceout(str_pad(substr($cve, 2, 3), 1, ' ', STR_PAD_LEFT), 6));

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon");
				$ProvSpecDelMonA = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonA != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonA);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(59, 129, $this->spaceout($matches[1][0], 6));
					$pdf->Text(104, 129, $this->spaceout($matches[2][0], 6));
				}

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon");
				$ProvSpecDelMonB = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonB != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonB);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(161, 129, $this->spaceout($matches[1][0], 6));
					$pdf->Text(207, 129, $this->spaceout($matches[2][0], 6));
				}

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='C']/ProvSpecDelMon");
				$ProvSpecDelMonC = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonC != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonC);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(59, 140, $this->spaceout($matches[1][0], 6));
					$pdf->Text(104, 140, $this->spaceout($matches[2][0], 6));
				}

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='D']/ProvSpecDelMon");
				$ProvSpecDelMonD = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonD != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonD);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(161, 140, $this->spaceout($matches[1][0], 6));
					$pdf->Text(207, 140, $this->spaceout($matches[2][0], 6));
				}

				if ($delivery->LearnActEndDate != '' && $delivery->LearnActEndDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnActEndDate, $matches);
					$pdf->Text(48, 159, $this->spaceout($matches[1][0], 1));
					$pdf->Text(57, 159, $this->spaceout($matches[2][0], 1));
					$pdf->Text(69, 159, $this->spaceout($matches[4][0], 1));
				}

				$pdf->Text(116, 159, $delivery->CompStatus);
				$pdf->Text(176, 159, $this->spaceout(str_pad($delivery->WithdrawReason, 2, '0', STR_PAD_LEFT), 5));
				$pdf->Text(235, 159, $delivery->Outcome);

				if ($delivery->AchDate != '' && $delivery->AchDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->AchDate, $matches);
					$pdf->Text(48, 169, $this->spaceout($matches[1][0], 1));
					$pdf->Text(57, 169, $this->spaceout($matches[2][0], 1));
					$pdf->Text(69, 169, $this->spaceout($matches[4][0], 1));
				}

				$pdf->Text(176, 169, $this->spaceout(str_pad($delivery->ActProgRoute, 2, '0', STR_PAD_LEFT), 5));
			} elseif ($delivery->AimType == '2' || $delivery->AimType == '3') {
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

				$pdf->Text(32, 41, $this->spaceout($delivery->AimType, 5));

				if ($delivery->LearnAimRef != '') {
					$pcode = str_replace(" ", "", $delivery->LearnAimRef);
					preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

					$pdf->Text(85, 41, $this->spaceout($matches[1][0], 6));
					$pdf->Text(115, 41, $this->spaceout($matches[2][0], 6));
				}
				if ($delivery->LearnStartDate != '' && $delivery->LearnStartDate != '00000000') {
					$delivery->LearnStartDate = Date::toShort($delivery->LearnStartDate);
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnStartDate, $matches);
					$pdf->Text(184, 41, $this->spaceout($matches[1][0], 1));
					$pdf->Text(193, 41, $this->spaceout($matches[2][0], 1));
					$pdf->Text(205, 41, $this->spaceout($matches[4][0], 1));
				}

				if ($delivery->LearnPlanEndDate != '' && $delivery->LearnPlanEndDate != '00000000') {
					$delivery->LearnPlanEndDate = Date::toShort($delivery->LearnPlanEndDate);
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnPlanEndDate, $matches);
					$pdf->Text(262, 41, $this->spaceout($matches[1][0], 1));
					$pdf->Text(270, 41, $this->spaceout($matches[2][0], 1));
					$pdf->Text(282, 41, $this->spaceout($matches[4][0], 1));
				}

				$pdf->Text(32, 51, $this->spaceout($delivery->FundModel, 5));
				$pdf->Text(85, 51, $this->spaceout(str_pad($delivery->ProgType, 2, '0', STR_PAD_LEFT), 5));
				$pdf->Text(123, 51, $this->spaceout($delivery->FworkCode, 6));
				$pdf->Text(176, 51, $this->spaceout(str_pad($delivery->PwayCode, 3, '0', STR_PAD_LEFT), 6));
				$pdf->Text(230, 51, $this->spaceout($delivery->PropFundRemain, 6));
				$pdf->Text(278, 51, $this->spaceout(str_pad($delivery->MainDelMeth, 2, '0', STR_PAD_LEFT), 5));

				if ($delivery->DelLocPostCode != '') {
					$pcode = str_replace(" ", "", $delivery->DelLocPostCode);
					preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

					@$pdf->Text(40, 63, $this->spaceout($matches[1][0], 5));
					@$pdf->Text(78, 63, $this->spaceout($matches[2][0], 5));
				}
				if ($delivery->PartnerUKPRN != '') {
					$pcode = str_replace(" ", "", $delivery->PartnerUKPRN);
					preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

					$pdf->Text(153, 63, $this->spaceout($matches[1][0], 6));
					$pdf->Text(183, 63, $this->spaceout($matches[2][0], 6));
				}
				$pdf->Text(270, 63, $this->spaceout(str_pad($delivery->PlanCredVal, 3, '0', STR_PAD_LEFT), 5));

				if ($delivery->ESFProjDosNumber != '' && $delivery->ESFProjDosNumber != 'undefined') {
					$pcode = str_replace(" ", "", $delivery->ESFProjDosNumber);
					preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

					$pdf->Text(47, 81, $this->spaceout($matches[1][0], 6));
					$pdf->Text(85, 81, $this->spaceout($matches[2][0], 6));
				}
				if ($delivery->ESFLocProjNumber != 'undefined')
					$pdf->Text(175, 81, $this->spaceout($delivery->ESFLocProjNumber, 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode");
				$ffi = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(35, 102, $this->spaceout(str_pad($ffi, 1, ' ', STR_PAD_LEFT), 1));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ALN']/LearnDelFAMCode");
				$aln = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(82, 102, $this->spaceout($aln, 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ASN']/LearnDelFAMCode");
				$asn = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(135, 102, $this->spaceout($asn, 6));

				$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode");
				$res = (empty($xpath[0])) ? '' : $xpath[0];
				$pdf->Text(177, 102, $this->spaceout($res, 6));

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon");
				$ProvSpecDelMonA = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonA != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonA);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(59, 121, $this->spaceout($matches[1][0], 6));
					$pdf->Text(104, 121, $this->spaceout($matches[2][0], 6));
				}

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon");
				$ProvSpecDelMonB = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonB != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonB);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(159, 121, $this->spaceout($matches[1][0], 6));
					$pdf->Text(205, 121, $this->spaceout($matches[2][0], 6));
				}

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='C']/ProvSpecDelMon");
				$ProvSpecDelMonC = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonC != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonC);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(59, 132, $this->spaceout($matches[1][0], 6));
					$pdf->Text(104, 132, $this->spaceout($matches[2][0], 6));
				}

				$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='D']/ProvSpecDelMon");
				$ProvSpecDelMonD = (empty($xpath[0])) ? '' : $xpath[0];
				if ($ProvSpecDelMonD != '') {
					$pcode = str_replace(" ", "", $ProvSpecDelMonD);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

					$pdf->Text(159, 132, $this->spaceout($matches[1][0], 6));
					$pdf->Text(205, 132, $this->spaceout($matches[2][0], 6));
				}

				if ($delivery->LearnActEndDate != '' && $delivery->LearnActEndDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->LearnActEndDate, $matches);
					@$pdf->Text(32, 152, $this->spaceout($matches[1][0], 1));
					@$pdf->Text(42, 152, $this->spaceout($matches[2][0], 1));
					@$pdf->Text(53, 152, $this->spaceout($matches[4][0], 1));
				}
				$pdf->Text(96, 152, $this->spaceout(str_pad($delivery->CompStatus, 1, '0', STR_PAD_LEFT), 5));
				$pdf->Text(130, 152, $this->spaceout(str_pad($delivery->WithdrawReason, 2, '0', STR_PAD_LEFT), 5));
				$pdf->Text(178, 152, $delivery->Outcome);
				if ($delivery->AchDate != '' && $delivery->AchDate != '00000000') {
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $delivery->AchDate, $matches);
					$pdf->Text(221, 152, $this->spaceout($matches[1][0], 1));
					$pdf->Text(229, 152, $this->spaceout($matches[2][0], 1));
					$pdf->Text(241, 152, $this->spaceout($matches[4][0], 1));
				}

				$pdf->Text(33, 163, $this->spaceout(str_pad($delivery->OutGrade, 6, ' ', STR_PAD_LEFT), 5));
				$pdf->Text(178, 163, $this->spaceout(str_pad($delivery->CredAch, 3, '0', STR_PAD_LEFT), 5));
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
