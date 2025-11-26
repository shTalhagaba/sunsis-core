<?php

use setasign\Fpdi\Fpdi;

class pdf_from_ilr2010 implements IAction
{
	public function execute(PDO $link)
	{
		ini_set("ignore_user_abort", 1); // Required to allow the PHP script time to delete the temporary PDF file

		$xml = isset($_REQUEST['xml']) ? $_REQUEST['xml'] : '';

		$vo = Ilr2010::loadFromXML($xml);

		$_SESSION['bc']->add($link, "do.php?_action=pdf_from_ilr&xml=" . $xml, "ILR PDF");
		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new FPDI();

		$pagecount = $pdf->setSourceFile('ilr2010.pdf');

		$tpl = $pdf->ImportPage(1);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->SetFont('Arial', '', 10);

		$pdf->Text(107, 16, strtoupper($this->spaceout($vo->learnerinformation->L01, 6)));

		if ($vo->learnerinformation->L03 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(157, 16, $this->spaceout($matches[1][0], 6));
			$pdf->Text(202, 16, $this->spaceout($matches[2][0], 6));
		}

		$l03 = $vo->learnerinformation->L09;


		if ($vo->learnerinformation->L46 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L46);
			preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(112, 26, $this->spaceout($matches[1][0], 6));
			$pdf->Text(142, 26, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->learnerinformation->L45 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L45);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,5})/", $pcode, $matches);

			$pdf->Text(217, 26, $this->spaceout($matches[1][0], 6));
			$pdf->Text(255, 26, $this->spaceout($matches[2][0], 6));
		}


		$pdf->Text(33, 49, strtoupper($vo->learnerinformation->L09));
		$pdf->Text(140, 49, strtoupper($vo->learnerinformation->L10));

		$pdf->Text(63, 77, strtoupper($vo->learnerinformation->L20));
		$pdf->Text(148, 77, strtoupper($vo->learnerinformation->L21));


		$matches = array();

		if ($vo->learnerinformation->L17 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L17);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			@$pdf->Text(100, 58, $this->spaceout($matches[1][0], 5));
			@$pdf->Text(138, 58, $this->spaceout($matches[2][0], 5));
		}

		if ($vo->learnerinformation->L26 != '') {

			preg_match_all("/(^[a-zA-Z]{2})([0-9]{2})([0-9]{2})([0-9]{2})([a-zA-Z]{1})/", $vo->learnerinformation->L26, $matches);

			@$pdf->Text(217, 48, $this->spaceout($matches[1][0], 6));
			@$pdf->Text(235, 48, $this->spaceout($matches[2][0], 6));
			@$pdf->Text(252, 48, $this->spaceout($matches[3][0], 6));
			@$pdf->Text(270, 48, $this->spaceout($matches[4][0], 6));
			@$pdf->Text(287, 48, $this->spaceout($matches[5][0], 6));
		}


		$pdf->Text(235, 72, $vo->learnerinformation->L13);

		$pdf->Text(273, 72, $this->spaceout($vo->learnerinformation->L12, 5));

		if ($vo->learnerinformation->L11 != '' && $vo->learnerinformation->L11 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->learnerinformation->L11, $matches);
			$pdf->Text(231, 58, $this->spaceout($matches[1][0], 5));
			$pdf->Text(248, 58, $this->spaceout($matches[2][0], 5));
			$pdf->Text(280, 58, $this->spaceout($matches[4][0], 5));
		}


		$pdf->Text(76, 68, $vo->learnerinformation->L18);
		$pdf->Text(153, 68, $vo->learnerinformation->L19);

		if ($vo->learnerinformation->L22 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L22);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			@$pdf->Text(43, 87, $this->spaceout($matches[1][0], 5));
			@$pdf->Text(81, 87, $this->spaceout($matches[2][0], 5));
		}

		$pdf->Text(160, 87, $vo->learnerinformation->L24);
		$pdf->Text(235, 87, $vo->learnerinformation->L23);

		$pdf->Text(53, 102, $vo->learnerinformation->L14);


		$pdf->Text(96, 102, $this->spaceout(str_pad($vo->learnerinformation->L15, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(150, 102, $this->spaceout(str_pad($vo->learnerinformation->L16, 2, '0', STR_PAD_LEFT), 5));

		$pdf->Text(225, 102, $this->spaceout($vo->learnerinformation->L34a, 5));
		$pdf->Text(242, 102, $this->spaceout($vo->learnerinformation->L34b, 5));
		$pdf->Text(260, 102, $this->spaceout($vo->learnerinformation->L34c, 5));
		$pdf->Text(277, 102, $this->spaceout($vo->learnerinformation->L34d, 5));


		$pdf->Text(42, 112, $this->spaceout(str_pad($vo->learnerinformation->L35, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(96, 112, $this->spaceout(str_pad($vo->learnerinformation->L36, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(150, 112, $this->spaceout(str_pad($vo->learnerinformation->L37, 2, '0', STR_PAD_LEFT), 5));

		$pdf->Text(209, 112, $this->spaceout(str_pad($vo->learnerinformation->L47, 2, '0', STR_PAD_LEFT), 5));


		if ($vo->learnerinformation->L48 != '' && $vo->learnerinformation->L48 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->learnerinformation->L48, $matches);
			$pdf->Text(262, 112, $this->spaceout($matches[1][0], 1));
			$pdf->Text(270, 112, $this->spaceout($matches[2][0], 1));
			$pdf->Text(281, 112, $this->spaceout($matches[4][0], 1));
		}

		$pdf->Text(258, 109, $this->spaceout($vo->learnerinformation->L28a));
		$pdf->Text(273, 109, $this->spaceout($vo->learnerinformation->L28b));


		switch ($vo->learnerinformation->L27) {
			case '1':
				$pdf->Image("./images/register/small-tick2.gif", 152, 147, 4, 4);
				$pdf->Image("./images/register/small-tick2.gif", 152, 154, 4, 4);
				break;
			case '3':
				$pdf->Image("./images/register/small-tick2.gif", 152, 154, 4, 4);
				break;
			case '4':
				$pdf->Image("./images/register/small-tick2.gif", 152, 147, 4, 4);
				break;
		}

		$pdf->Text(33, 199, $this->spaceout($vo->learnerinformation->L39, 5));

		if (($vo->aims[0]->A10 != '45' && $vo->aims[0]->A15 != '99' && $vo->aims[0]->A18 != '22' && $vo->aims[0]->A18 != '23' && $vo->programmeaim->A10 != '70')) {
			$tpl = $pdf->ImportPage(2);
			$s = $pdf->getTemplatesize($tpl);
			$pdf->AddPage('P', array($s['width'], $s['height']));
			$pdf->useTemplate($tpl);

			$pdf->Text(65, 16, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

			if ($vo->learnerinformation->L03 != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
				preg_match_all("/([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})/", $pcode, $matches);

				$pdf->Text(157, 16, $this->spaceout($matches[1][0], 6));
				$pdf->Text(179, 16, $this->spaceout($matches[2][0], 6));
				$pdf->Text(202, 16, $this->spaceout($matches[3][0], 6));
				$pdf->Text(224, 16, $this->spaceout($matches[4][0], 6));
			}

			$pdf->Text(145, 42, $this->spaceout($vo->programmeaim->A10, 5));

			$pdf->Text(182, 42, $this->spaceout(str_pad($vo->programmeaim->A11a, 2, '0', STR_PAD_LEFT), 6));
			$pdf->Text(207, 42, $this->spaceout(str_pad($vo->programmeaim->A11b, 2, '0', STR_PAD_LEFT), 6));

			$pdf->Text(257, 42, $this->spaceout(str_pad($vo->programmeaim->A70, 2, '0', STR_PAD_LEFT), 5));

			$pdf->Text(43, 52, $this->spaceout(str_pad($vo->programmeaim->A15, 2, '0', STR_PAD_LEFT), 6));
			$pdf->Text(97, 52, $this->spaceout(str_pad($vo->programmeaim->A16, 2, '0', STR_PAD_LEFT), 6));
			$pdf->Text(152, 52, $this->spaceout($vo->programmeaim->A26, 6));


			if ($vo->programmeaim->A27 != '' && $vo->programmeaim->A27 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A27, $matches);
				$pdf->Text(206, 52, $this->spaceout($matches[1][0], 1));
				$pdf->Text(214, 52, $this->spaceout($matches[2][0], 1));
				$pdf->Text(225, 52, $this->spaceout($matches[4][0], 1));
			}

			if ($vo->programmeaim->A28 != '' && $vo->programmeaim->A28 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A28, $matches);
				$pdf->Text(264, 52, $this->spaceout($matches[1][0], 1));
				$pdf->Text(272, 52, $this->spaceout($matches[2][0], 1));
				$pdf->Text(283, 52, $this->spaceout($matches[4][0], 1));
			}

			if ($vo->programmeaim->A23 != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A23);
				preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

				@$pdf->Text(39, 62, $this->spaceout($matches[1][0], 5));
				@$pdf->Text(78, 62, $this->spaceout($matches[2][0], 5));
			}


			$pdf->Text(139, 62, $this->spaceout($vo->programmeaim->A51a, 5));
			$pdf->Text(191, 62, $this->spaceout(str_pad($vo->programmeaim->A14, 2, '0', STR_PAD_LEFT)));

			$pdf->Text(249, 62, $this->spaceout($vo->programmeaim->A46a, 5));
			$pdf->Text(273, 62, $this->spaceout($vo->programmeaim->A46b, 5));

			$pdf->Text(41, 72, $this->spaceout($vo->programmeaim->A64, 5));
			$pdf->Text(123, 72, $this->spaceout($vo->programmeaim->A65, 6));

			if ($vo->programmeaim->A31 != '' && $vo->programmeaim->A31 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A31, $matches);
				$pdf->Text(53, 101, $this->spaceout($matches[1][0], 1));
				$pdf->Text(63, 101, $this->spaceout($matches[2][0], 1));
				$pdf->Text(75, 101, $this->spaceout($matches[4][0], 1));
			}

			if ($vo->programmeaim->A40 != '' && $vo->programmeaim->A40 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A40, $matches);
				$pdf->Text(125, 102, $this->spaceout($matches[1][0], 1));
				$pdf->Text(134, 102, $this->spaceout($matches[2][0], 1));
				$pdf->Text(146, 102, $this->spaceout($matches[4][0], 1));
			}

			$pdf->Text(197, 102, $vo->programmeaim->A34);
			$pdf->Text(242, 102, $vo->programmeaim->A35);
			$pdf->Text(280, 102, $this->spaceout(str_pad($vo->programmeaim->A50, 2, '0', STR_PAD_LEFT), 5));

			$pdf->Text(64, 112, $this->spaceout($vo->programmeaim->A48a, 6));
			$pdf->Text(165, 112, $this->spaceout($vo->programmeaim->A48b, 6));
		}

		$tpl = $pdf->ImportPage(3);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->Text(85, 15, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

		if ($vo->learnerinformation->L03 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(158, 15, $this->spaceout($matches[1][0], 6));
			$pdf->Text(203, 15, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->aims[0]->A09 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A09);
			preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(30, 42, $this->spaceout($matches[1][0], 6));
			$pdf->Text(60, 42, $this->spaceout($matches[2][0], 6));
		}

		$pdf->Text(108, 42, $this->spaceout($vo->aims[0]->A10, 5));

		$pdf->Text(143, 42, $this->spaceout($vo->aims[0]->A11a, 6));
		$pdf->Text(167, 42, $this->spaceout($vo->aims[0]->A11b, 6));

		$pdf->Text(220, 42, $this->spaceout($vo->aims[0]->A70, 6));
		$pdf->Text(280, 42, $this->spaceout(str_pad($vo->aims[0]->A15, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(30, 52, $this->spaceout(str_pad($vo->aims[0]->A16, 2, '0', STR_PAD_LEFT), 6));
		$pdf->Text(68, 52, $this->spaceout($vo->aims[0]->A26, 6));
		$pdf->Text(115, 52, $this->spaceout($vo->aims[0]->A53, 6));

		$pdf->Text(160, 52, $this->spaceout($vo->aims[0]->A51a, 6));


		if ($vo->aims[0]->A27 != '' && $vo->aims[0]->A27 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A27, $matches);
			$pdf->Text(208, 52, $this->spaceout($matches[1][0], 1));
			$pdf->Text(217, 52, $this->spaceout($matches[2][0], 1));
			$pdf->Text(228, 52, $this->spaceout($matches[4][0], 1));
		}

		if ($vo->aims[0]->A28 != '' && $vo->aims[0]->A28 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A28, $matches);
			$pdf->Text(263, 52, $this->spaceout($matches[1][0], 1));
			$pdf->Text(272, 52, $this->spaceout($matches[2][0], 1));
			$pdf->Text(284, 52, $this->spaceout($matches[4][0], 1));
		}

		if ($vo->aims[0]->A23 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A23);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			@$pdf->Text(38, 62, $this->spaceout($matches[1][0], 5));
			@$pdf->Text(75, 62, $this->spaceout($matches[2][0], 5));
		}

		$pdf->Text(130, 62, $this->spaceout(str_pad($vo->aims[0]->A14, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(175, 62, $this->spaceout(str_pad($vo->aims[0]->A69, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(228, 62, $this->spaceout($vo->aims[0]->A59, 5));
		$pdf->Text(281, 62, $this->spaceout($vo->aims[0]->A18, 5));

		$pdf->Text(38, 72, $this->spaceout(str_pad($vo->aims[0]->A46a, 3, '0', STR_PAD_LEFT), 5));
		$pdf->Text(63, 72, $this->spaceout(str_pad($vo->aims[0]->A46b, 3, '0', STR_PAD_LEFT), 5));
		$pdf->Text(108, 72, $this->spaceout($vo->aims[0]->A49, 5));
		$pdf->Text(176, 72, $this->spaceout(str_pad($vo->aims[0]->A63, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(228, 71, $this->spaceout($vo->aims[0]->A44, 6));

		if ($vo->aims[0]->A45 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A45);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			@$pdf->Text(38, 82, $this->spaceout($matches[1][0], 5));
			@$pdf->Text(76, 82, $this->spaceout($matches[2][0], 5));
		}

		if ($vo->aims[0]->A22 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A22);
			preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(144, 82, $this->spaceout($matches[1][0], 6));
			$pdf->Text(174, 82, $this->spaceout($matches[2][0], 6));
		}

		$pdf->Text(258, 82, $this->spaceout($vo->aims[0]->A64, 6));
		$pdf->Text(30, 92, $this->spaceout($vo->aims[0]->A65, 6));

		if ($vo->aims[0]->A61 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A61);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(87, 92, $this->spaceout($matches[1][0], 6));
			$pdf->Text(125, 92, $this->spaceout($matches[2][0], 6));
		}
		$pdf->Text(175, 92, $this->spaceout($vo->aims[0]->A62, 6));

		$pdf->Text(233, 92, $this->spaceout(str_pad($vo->aims[0]->A66, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(281, 92, $this->spaceout(str_pad($vo->aims[0]->A67, 2, '0', STR_PAD_LEFT), 5));

		if ($vo->aims[0]->A31 != '' && $vo->aims[0]->A31 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A31, $matches);
			$pdf->Text(54, 129, $this->spaceout($matches[1][0], 1));
			$pdf->Text(63, 129, $this->spaceout($matches[2][0], 1));
			$pdf->Text(75, 129, $this->spaceout($matches[4][0], 1));
		}

		if ($vo->aims[0]->A40 != '' && $vo->aims[0]->A40 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A40, $matches);
			$pdf->Text(143, 129, $this->spaceout($matches[1][0], 1));
			$pdf->Text(152, 129, $this->spaceout($matches[2][0], 1));
			$pdf->Text(165, 129, $this->spaceout($matches[4][0], 1));
		}

		$pdf->Text(225, 129, $this->spaceout(str_pad($vo->aims[0]->A68, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(288, 129, $this->spaceout(str_pad($vo->aims[0]->A34, 1, '0', STR_PAD_LEFT), 5));
		$pdf->Text(47, 139, $vo->aims[0]->A35);
		$pdf->Text(107, 139, $this->spaceout($vo->aims[0]->A36, 5));
		$pdf->Text(205, 139, $this->spaceout($vo->aims[0]->A50, 5));
		$pdf->Text(273, 139, $this->spaceout($vo->aims[0]->A60, 5));

		if ($vo->aims[0]->A47a != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A47a);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(61, 178, $this->spaceout($matches[1][0], 6));
			$pdf->Text(106, 178, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->aims[0]->A47b != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A47b);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(164, 178, $this->spaceout($matches[1][0], 6));
			$pdf->Text(208, 178, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->aims[0]->A48a != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A48a);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(61, 189, $this->spaceout($matches[1][0], 6));
			$pdf->Text(106, 189, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->aims[0]->A48b != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A48b);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(164, 189, $this->spaceout($matches[1][0], 6));
			$pdf->Text(208, 189, $this->spaceout($matches[2][0], 6));
		}

		// Create One subsidiary Aim for ESF
		if ($vo->programmeaim->A10 == '70') {
			$tpl = $pdf->ImportPage(4);
			$s = $pdf->getTemplatesize($tpl);
			$pdf->AddPage('P', array($s['width'], $s['height']));
			$pdf->useTemplate($tpl);

			$pdf->Text(85, 18, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

			if ($vo->learnerinformation->L03 != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(156, 18, $this->spaceout($matches[1][0], 6));
				$pdf->Text(202, 18, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->programmeaim->A09 != '') {
				$pcode = str_replace(" ", "", "ZESF0001");
				preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(31, 43, $this->spaceout($matches[1][0], 6));
				$pdf->Text(62, 43, $this->spaceout($matches[2][0], 6));
			}

			$pdf->Text(110, 43, $this->spaceout($vo->programmeaim->A10, 5));

			$pdf->Text(147, 43, $this->spaceout($vo->programmeaim->A11a, 6));
			$pdf->Text(171, 43, $this->spaceout($vo->programmeaim->A11b, 6));

			$pdf->Text(219, 43, $this->spaceout($vo->programmeaim->A70, 6));

			$pdf->Text(32, 54, $this->spaceout(str_pad($vo->programmeaim->A16, 2, '0', STR_PAD_LEFT), 5));
			$pdf->Text(118, 54, $this->spaceout($vo->programmeaim->A53, 5));
			$pdf->Text(158, 55, $this->spaceout($vo->programmeaim->A51a, 5));


			if ($vo->programmeaim->A27 != '' && $vo->programmeaim->A27 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A27, $matches);
				$pdf->Text(207, 54, $this->spaceout($matches[1][0], 1));
				$pdf->Text(214, 54, $this->spaceout($matches[2][0], 1));
				$pdf->Text(226, 54, $this->spaceout($matches[4][0], 1));
			}

			if ($vo->programmeaim->A28 != '' && $vo->programmeaim->A28 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A28, $matches);
				$pdf->Text(265, 54, $this->spaceout($matches[1][0], 1));
				$pdf->Text(272, 54, $this->spaceout($matches[2][0], 1));
				$pdf->Text(284, 54, $this->spaceout($matches[4][0], 1));
			}

			if ($vo->programmeaim->A23 != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A23);
				preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

				$pdf->Text(38, 66, $this->spaceout($matches[1][0], 6));
				$pdf->Text(77, 66, $this->spaceout($matches[2][0], 6));
			}

			$pdf->Text(126, 66, $this->spaceout($vo->programmeaim->A14, 5));
			$pdf->Text(173, 66, $this->spaceout(str_pad($vo->programmeaim->A69, 2, '0', STR_PAD_LEFT), 5));
			$pdf->Text(228, 66, $this->spaceout($vo->programmeaim->A59, 5));
			$pdf->Text(280, 66, $this->spaceout(str_pad($vo->programmeaim->A18, 2, '0', STR_PAD_LEFT), 5));

			$pdf->Text(39, 77, $this->spaceout(str_pad($vo->programmeaim->A46a, 3, '0', STR_PAD_LEFT), 6));
			$pdf->Text(62, 77, $this->spaceout(str_pad($vo->programmeaim->A46b, 3, '0', STR_PAD_LEFT), 6));
			$pdf->Text(110, 77, $this->spaceout($vo->programmeaim->A49, 5));
			$pdf->Text(173, 77, $this->spaceout(str_pad($vo->programmeaim->A63, 2, '0', STR_PAD_LEFT), 5));

			if ($vo->programmeaim->A22 != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A22);
				preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(234, 77, $this->spaceout($matches[1][0], 6));
				$pdf->Text(265, 77, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->programmeaim->A61 != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A61);
				preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(31, 88, $this->spaceout($matches[1][0], 6));
				$pdf->Text(69, 88, $this->spaceout($matches[2][0], 6));
			}

			$pdf->Text(125, 88, $this->spaceout($vo->programmeaim->A62, 6));

			$pdf->Text(202, 88, $this->spaceout(str_pad($vo->programmeaim->A66, 2, '0', STR_PAD_LEFT), 6));
			$pdf->Text(280, 88, $this->spaceout(str_pad($vo->programmeaim->A67, 2, '0', STR_PAD_LEFT), 6));

			if ($vo->programmeaim->A31 != '' && $vo->programmeaim->A31 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A31, $matches);
				$pdf->Text(60, 107, $this->spaceout($matches[1][0], 1));
				$pdf->Text(68, 107, $this->spaceout($matches[2][0], 1));
				$pdf->Text(82, 107, $this->spaceout($matches[4][0], 1));
			}

			if ($vo->programmeaim->A40 != '' && $vo->programmeaim->A40 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A40, $matches);
				$pdf->Text(131, 107, $this->spaceout($matches[1][0], 1));
				$pdf->Text(139, 107, $this->spaceout($matches[2][0], 1));
				$pdf->Text(151, 107, $this->spaceout($matches[4][0], 1));
			}

			$pdf->Text(208, 107, $vo->programmeaim->A34);
			$pdf->Text(260, 107, $vo->programmeaim->A35);

			$pdf->Text(60, 118, $this->spaceout($vo->programmeaim->A36, 5));
			$pdf->Text(163, 118, $this->spaceout($vo->programmeaim->A50, 5));
			$pdf->Text(230, 118, $this->spaceout($vo->programmeaim->A60, 5));


			if ($vo->programmeaim->A47a != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A47a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(59, 140, $this->spaceout($matches[1][0], 6));
				$pdf->Text(104, 140, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->programmeaim->A47b != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A47b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(162, 140, $this->spaceout($matches[1][0], 6));
				$pdf->Text(206, 140, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->programmeaim->A48a != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A48a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(59, 151, $this->spaceout($matches[1][0], 6));
				$pdf->Text(104, 151, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->programmeaim->A48b != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A48b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(162, 151, $this->spaceout($matches[1][0], 6));
				$pdf->Text(206, 151, $this->spaceout($matches[2][0], 6));
			}

			$pdf->Text(61, 172, $this->spaceout($vo->learnerinformation->L40a, 6));
			$pdf->Text(84, 172, $this->spaceout($vo->learnerinformation->L40b, 6));

			if ($vo->learnerinformation->L41a != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L41a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(61, 182, $this->spaceout($matches[1][0], 6));
				$pdf->Text(107, 182, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L41b != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L41b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(162, 182, $this->spaceout($matches[1][0], 6));
				$pdf->Text(208, 182, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L42a != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(61, 192, $this->spaceout($matches[1][0], 6));
				$pdf->Text(107, 192, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L42b != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(162, 192, $this->spaceout($matches[1][0], 6));
				$pdf->Text(208, 192, $this->spaceout($matches[2][0], 6));
			}
		}

		// End

		// Subsidiary Aims
		for ($a = 1; $a <= $vo->subaims; $a++) {

			$tpl = $pdf->ImportPage(4);
			$s = $pdf->getTemplatesize($tpl);
			$pdf->AddPage('P', array($s['width'], $s['height']));
			$pdf->useTemplate($tpl);

			$pdf->Text(85, 18, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

			if ($vo->learnerinformation->L03 != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(156, 18, $this->spaceout($matches[1][0], 6));
				$pdf->Text(202, 18, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->aims[$a]->A09 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A09);
				preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(31, 43, $this->spaceout($matches[1][0], 6));
				$pdf->Text(62, 43, $this->spaceout($matches[2][0], 6));
			}

			$pdf->Text(110, 43, $this->spaceout($vo->aims[$a]->A10, 5));

			$pdf->Text(147, 43, $this->spaceout($vo->aims[$a]->A11a, 6));
			$pdf->Text(171, 43, $this->spaceout($vo->aims[$a]->A11b, 6));

			$pdf->Text(219, 43, $this->spaceout($vo->aims[$a]->A70, 6));

			$pdf->Text(32, 54, $this->spaceout(str_pad($vo->aims[$a]->A16, 2, '0', STR_PAD_LEFT), 5));
			$pdf->Text(118, 54, $this->spaceout($vo->aims[$a]->A53, 5));
			$pdf->Text(158, 55, $this->spaceout($vo->aims[$a]->A51a, 5));


			if ($vo->aims[$a]->A27 != '' && $vo->aims[$a]->A27 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A27, $matches);
				$pdf->Text(207, 54, $this->spaceout($matches[1][0], 1));
				$pdf->Text(214, 54, $this->spaceout($matches[2][0], 1));
				$pdf->Text(226, 54, $this->spaceout($matches[4][0], 1));
			}

			if ($vo->aims[$a]->A28 != '' && $vo->aims[$a]->A28 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A28, $matches);
				$pdf->Text(265, 54, $this->spaceout($matches[1][0], 1));
				$pdf->Text(272, 54, $this->spaceout($matches[2][0], 1));
				$pdf->Text(284, 54, $this->spaceout($matches[4][0], 1));
			}

			if ($vo->aims[$a]->A23 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A23);
				preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

				@$pdf->Text(38, 66, $this->spaceout($matches[1][0], 6));
				@$pdf->Text(77, 66, $this->spaceout($matches[2][0], 6));
			}

			$pdf->Text(126, 66, $this->spaceout($vo->aims[$a]->A14, 5));
			$pdf->Text(173, 66, $this->spaceout(str_pad($vo->aims[$a]->A69, 2, '0', STR_PAD_LEFT), 5));
			$pdf->Text(228, 66, $this->spaceout($vo->aims[$a]->A59, 5));
			$pdf->Text(280, 66, $this->spaceout(str_pad($vo->aims[$a]->A18, 2, '0', STR_PAD_LEFT), 5));

			$pdf->Text(39, 77, $this->spaceout(str_pad($vo->aims[$a]->A46a, 3, '0', STR_PAD_LEFT), 6));
			$pdf->Text(62, 77, $this->spaceout(str_pad($vo->aims[$a]->A46b, 3, '0', STR_PAD_LEFT), 6));
			$pdf->Text(110, 77, $this->spaceout($vo->aims[$a]->A49, 5));
			$pdf->Text(173, 77, $this->spaceout(str_pad($vo->aims[$a]->A63, 2, '0', STR_PAD_LEFT), 5));

			if ($vo->aims[$a]->A22 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A22);
				preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(234, 77, $this->spaceout($matches[1][0], 6));
				$pdf->Text(265, 77, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->aims[$a]->A61 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A61);
				preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(31, 88, $this->spaceout($matches[1][0], 6));
				$pdf->Text(69, 88, $this->spaceout($matches[2][0], 6));
			}

			$pdf->Text(125, 88, $this->spaceout($vo->aims[$a]->A62, 6));

			$pdf->Text(202, 88, $this->spaceout(str_pad($vo->aims[$a]->A66, 2, '0', STR_PAD_LEFT), 6));
			$pdf->Text(280, 88, $this->spaceout(str_pad($vo->aims[$a]->A67, 2, '0', STR_PAD_LEFT), 6));

			if ($vo->aims[$a]->A31 != '' && $vo->aims[$a]->A31 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A31, $matches);
				$pdf->Text(60, 107, $this->spaceout($matches[1][0], 1));
				$pdf->Text(68, 107, $this->spaceout($matches[2][0], 1));
				$pdf->Text(82, 107, $this->spaceout($matches[4][0], 1));
			}

			if ($vo->aims[$a]->A40 != '' && $vo->aims[$a]->A40 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A40, $matches);
				$pdf->Text(131, 107, $this->spaceout($matches[1][0], 1));
				$pdf->Text(139, 107, $this->spaceout($matches[2][0], 1));
				$pdf->Text(151, 107, $this->spaceout($matches[4][0], 1));
			}

			$pdf->Text(208, 107, $vo->aims[$a]->A34);
			$pdf->Text(260, 107, $vo->aims[$a]->A35);

			$pdf->Text(60, 118, $this->spaceout($vo->aims[$a]->A36, 5));
			$pdf->Text(163, 118, $this->spaceout($vo->aims[$a]->A50, 5));
			$pdf->Text(230, 118, $this->spaceout($vo->aims[$a]->A60, 5));


			if ($vo->aims[$a]->A47a != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A47a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(59, 140, $this->spaceout($matches[1][0], 6));
				$pdf->Text(104, 140, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->aims[$a]->A47b != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A47b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(162, 140, $this->spaceout($matches[1][0], 6));
				$pdf->Text(206, 140, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->aims[$a]->A48a != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A48a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(59, 151, $this->spaceout($matches[1][0], 6));
				$pdf->Text(104, 151, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->aims[$a]->A48b != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A48b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(162, 151, $this->spaceout($matches[1][0], 6));
				$pdf->Text(206, 151, $this->spaceout($matches[2][0], 6));
			}

			$pdf->Text(61, 172, $this->spaceout($vo->learnerinformation->L40a, 6));
			$pdf->Text(84, 172, $this->spaceout($vo->learnerinformation->L40b, 6));

			if ($vo->learnerinformation->L41a != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L41a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(61, 182, $this->spaceout($matches[1][0], 6));
				$pdf->Text(107, 182, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L41b != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L41b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(162, 182, $this->spaceout($matches[1][0], 6));
				$pdf->Text(208, 182, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L42a != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(61, 192, $this->spaceout($matches[1][0], 6));
				$pdf->Text(107, 192, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L42b != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(162, 192, $this->spaceout($matches[1][0], 6));
				$pdf->Text(208, 192, $this->spaceout($matches[2][0], 6));
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

		/*
		if(!(file_exists(DATA_ROOT."/uploads/".DB_NAME."/admin_reports")))
		mkdir(DATA_ROOT."/uploads/".DB_NAME."/admin_reports");


		//Determine a temporary file name in the current directory
		$this->CleanFiles(DATA_ROOT.'/uploads/' . DB_NAME);
		$file = basename(tempnam(DATA_ROOT.'/uploads/' . DB_NAME . '/admin_reports/', 'tmp'));

		$file = DATA_ROOT.'/uploads/' . DB_NAME . '/admin_reports/' . $file;



		rename($file, $file.'.pdf');
		//Save PDF to file
		$pdf->Output($file.'.pdf', 'F');

		// Ian S-S
		// Amend file permissions so that the file is readable by the 'backup' user
		//chmod($file, 0664); // u=rw,g=rw,o=r

		//Redirect
		//header('Location: '. $file.'.pdf');

	//	$len=filesize($file.'.pdf');
	//	header("content-type: application/pdf");
	//	header("content-length: $len");
		//header("content-disposition: attachment; filename=$filename");
	//	$fp=fopen($file.'.pdf', "r");
	//	fpassthru($fp);

		$download = "ilr" . $l03 . ".pdf";
		header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename=' . $download);
		readfile($file.'.pdf');

		$this->CleanFiles(DATA_ROOT.'/uploads/' . DB_NAME);
		*/
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

	/**
	 * @param string $dir absolute path to the directory to clean
	 */
	private function CleanFiles($dir)
	{
		//Delete temporary files
		$temporary_files = glob($dir . '/tmp*');
		foreach ($temporary_files as $absolute_path) {
			unlink($absolute_path);
		}
	}
}