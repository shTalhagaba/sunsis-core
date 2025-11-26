<?php

use setasign\Fpdi\Fpdi;

class pdf_from_ilr2009 implements IAction
{
	public function execute(PDO $link)
	{

		$xml = isset($_REQUEST['xml']) ? $_REQUEST['xml'] : '';

		$vo = Ilr2009::loadFromXML($xml);

		$_SESSION['bc']->add($link, "do.php?_action=pdf_from_ilr&xml=" . $xml, "ILR PDF");
		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new FPDI();

		$pagecount = $pdf->setSourceFile('ilr2009.pdf');

		$tpl = $pdf->ImportPage(1);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->SetFont('Arial', '', 10);

		$pdf->Text(65, 21, strtoupper($this->spaceout($vo->learnerinformation->L25)));
		$pdf->Text(101, 21, strtoupper($this->spaceout($vo->learnerinformation->L44)));
		$pdf->Text(128, 21, strtoupper($this->spaceout($vo->learnerinformation->L01)));

		if ($vo->learnerinformation->L03 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(169, 21, $this->spaceout($matches[1][0]));
			$pdf->Text(207, 21, $this->spaceout($matches[2][0]));
		}

		$l03 = $vo->learnerinformation->L09;


		if ($vo->learnerinformation->L46 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L46);
			preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(127, 30, $this->spaceout($matches[1][0]));
			$pdf->Text(151.5, 30, $this->spaceout($matches[2][0]));
		}

		if ($vo->learnerinformation->L45 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L45);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,5})/", $pcode, $matches);

			$pdf->Text(227, 30, $this->spaceout($matches[1][0]));
			$pdf->Text(259, 30, $this->spaceout($matches[2][0]));
		}


		$pdf->Text(36, 51, strtoupper($vo->learnerinformation->L09));
		$pdf->Text(145, 51, strtoupper($vo->learnerinformation->L10));

		$pdf->Text(46, 79, strtoupper($vo->learnerinformation->L20));
		$pdf->Text(134, 79, strtoupper($vo->learnerinformation->L21));


		$matches = array();

		if ($vo->learnerinformation->L17 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L17);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			$pdf->Text(107, 60, $this->spaceout($matches[1][0]));
			$pdf->Text(135, 60, $this->spaceout($matches[2][0]));
		}

		if ($vo->learnerinformation->L26 != '') {

			preg_match_all("/(^[a-zA-Z]{2})([0-9]{2})([0-9]{2})([0-9]{2})([a-zA-Z]{1})/", $vo->learnerinformation->L26, $matches);

			$pdf->Text(223, 51, $this->spaceout($matches[1][0]));
			$pdf->Text(239, 51, $this->spaceout($matches[2][0]));
			$pdf->Text(254, 51, $this->spaceout($matches[3][0]));
			$pdf->Text(269, 51, $this->spaceout($matches[4][0]));
			$pdf->Text(284, 51, $this->spaceout($matches[5][0]));
		}


		$pdf->Text(226, 75, $vo->learnerinformation->L13);

		$pdf->Text(261, 75, $this->spaceout($vo->learnerinformation->L12));

		if ($vo->learnerinformation->L11 != '' && $vo->learnerinformation->L11 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->learnerinformation->L11, $matches);
			$pdf->Text(234, 60, $this->spaceout($matches[1][0]));
			$pdf->Text(249, 60, $this->spaceout($matches[2][0]));
			$pdf->Text(278, 60, $this->spaceout($matches[4][0]));
		}


		$pdf->Text(62, 71, $vo->learnerinformation->L18);
		$pdf->Text(134, 71, $vo->learnerinformation->L19);

		if ($vo->learnerinformation->L22 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L22);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			$pdf->Text(50, 88, $this->spaceout($matches[1][0]));
			$pdf->Text(79, 88, $this->spaceout($matches[2][0]));
		}

		$pdf->Text(125, 88, $vo->learnerinformation->L24);
		$pdf->Text(217, 88, $vo->learnerinformation->L23);

		$pdf->Text(62, 100, $vo->learnerinformation->L14);


		$pdf->Text(112, 100, $this->spaceout(str_pad($vo->learnerinformation->L15, 2, '0', STR_PAD_LEFT)));
		$pdf->Text(160, 100, $this->spaceout(str_pad($vo->learnerinformation->L16, 2, '0', STR_PAD_LEFT)));
		$pdf->Text(227, 100, $this->spaceout(str_pad($vo->learnerinformation->L35, 2, '0', STR_PAD_LEFT)));

		$pdf->Text(44, 109, $this->spaceout($vo->learnerinformation->L34a));
		$pdf->Text(61, 109, $this->spaceout($vo->learnerinformation->L34b));
		$pdf->Text(79, 109, $this->spaceout($vo->learnerinformation->L34c));

		//$pdf->Text(160,109,$this->spaceout(str_pad($vo->learnerinformation->L36,2,'0',STR_PAD_LEFT)));

		$pdf->Text(227, 109, $this->spaceout(str_pad($vo->learnerinformation->L37, 2, '0', STR_PAD_LEFT)));

		$pdf->Text(258, 109, $this->spaceout($vo->learnerinformation->L28a));
		$pdf->Text(273, 109, $this->spaceout($vo->learnerinformation->L28b));

		$pdf->Text(63, 122, $this->spaceout(str_pad($vo->learnerinformation->L47, 2, '0', STR_PAD_LEFT)));

		if ($vo->learnerinformation->L48 != '' && $vo->learnerinformation->L48 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->learnerinformation->L48, $matches);
			$pdf->Text(112, 122, $this->spaceout($matches[1][0]));
			$pdf->Text(127, 122, $this->spaceout($matches[2][0]));
			$pdf->Text(155, 122, $this->spaceout($matches[4][0]));
		}



		switch ($vo->learnerinformation->L27) {
			case '1':
				$pdf->Image("./images/register/small-tick2.gif", 243, 151, 5, 5);
				$pdf->Image("./images/register/small-tick2.gif", 243, 163, 5, 5);
				break;
			case '3':
				$pdf->Image("./images/register/small-tick2.gif", 243, 163, 5, 5);
				break;
			case '4':
				$pdf->Image("./images/register/small-tick2.gif", 243, 151, 5, 5);
				break;
		}

		$pdf->Text(38, 198, $this->spaceout($vo->learnerinformation->L39));

		if ($vo->aims[0]->A10 != '45' && $vo->aims[0]->A15 != '99' && $vo->aims[0]->A18 != '22' && $vo->aims[0]->A18 != '23' && $vo->programmeaim->A10 != '70') {
			$tpl = $pdf->ImportPage(2);
			$s = $pdf->getTemplatesize($tpl);
			$pdf->AddPage('P', array($s['width'], $s['height']));
			$pdf->useTemplate($tpl);

			$pdf->Text(85, 21, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

			if ($vo->learnerinformation->L03 != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(169, 21, $this->spaceout($matches[1][0]));
				$pdf->Text(207, 21, $this->spaceout($matches[2][0]));
			}


			$pdf->Text(191, 45, $this->spaceout(str_pad($vo->programmeaim->A15, 2, '0', STR_PAD_LEFT)));
			$pdf->Text(230, 45, $this->spaceout(str_pad($vo->programmeaim->A16, 2, '0', STR_PAD_LEFT)));
			$pdf->Text(273, 45, $this->spaceout($vo->programmeaim->A26));


			if ($vo->programmeaim->A27 != '' && $vo->programmeaim->A27 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A27, $matches);
				$pdf->Text(49, 56, $this->spaceout($matches[1][0]));
				$pdf->Text(64, 56, $this->spaceout($matches[2][0]));
				$pdf->Text(93, 56, $this->spaceout($matches[4][0]));
			}

			if ($vo->programmeaim->A28 != '' && $vo->programmeaim->A28 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A28, $matches);
				$pdf->Text(142, 56, $this->spaceout($matches[1][0]));
				$pdf->Text(157, 56, $this->spaceout($matches[2][0]));
				$pdf->Text(185, 56, $this->spaceout($matches[4][0]));
			}

			if ($vo->programmeaim->A23 != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A23);
				preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

				$pdf->Text(245, 56, $this->spaceout($matches[1][0]));
				$pdf->Text(273, 56, $this->spaceout($matches[2][0]));
			}


			$pdf->Text(57, 65, $this->spaceout($vo->programmeaim->A51a));
			$pdf->Text(129, 65, $this->spaceout(str_pad($vo->programmeaim->A14, 2, '0', STR_PAD_LEFT)));

			$pdf->Text(204, 65, $this->spaceout($vo->programmeaim->A46a));
			$pdf->Text(226, 65, $this->spaceout($vo->programmeaim->A46b));

			$pdf->Text(63, 75, $this->spaceout($vo->programmeaim->A64, 5));
			$pdf->Text(167, 75, $this->spaceout($vo->programmeaim->A65, 5));


			$pdf->Text(279, 65, $this->spaceout($vo->programmeaim->A02));


			if ($vo->programmeaim->A31 != '' && $vo->programmeaim->A31 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A31, $matches);
				$pdf->Text(48, 104, $this->spaceout($matches[1][0]));
				$pdf->Text(63, 104, $this->spaceout($matches[2][0]));
				$pdf->Text(92, 104, $this->spaceout($matches[4][0]));
			}

			if ($vo->programmeaim->A40 != '' && $vo->programmeaim->A40 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A40, $matches);
				$pdf->Text(152, 104, $this->spaceout($matches[1][0]));
				$pdf->Text(167, 104, $this->spaceout($matches[2][0]));
				$pdf->Text(195, 104, $this->spaceout($matches[4][0]));
			}

			$pdf->Text(48, 114, $vo->programmeaim->A34);
			$pdf->Text(97, 114, $vo->programmeaim->A35);
			$pdf->Text(152, 114, $this->spaceout(str_pad($vo->programmeaim->A50, 2, '0', STR_PAD_LEFT)));
		}

		$tpl = $pdf->ImportPage(3);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->Text(85, 21, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

		if ($vo->learnerinformation->L03 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(169, 21, $this->spaceout($matches[1][0]));
			$pdf->Text(207, 21, $this->spaceout($matches[2][0]));
		}


		$pdf->Text(231, 37, $this->spaceout($vo->aims[0]->A02));


		if ($vo->aims[0]->A09 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A09);
			preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(39, 46, $this->spaceout($matches[1][0]));
			$pdf->Text(64, 46, $this->spaceout($matches[2][0]));
		}

		$pdf->Text(111, 46, $this->spaceout($vo->aims[0]->A10));
		$pdf->Text(148, 46, $this->spaceout(str_pad($vo->aims[0]->A15, 2, '0', STR_PAD_LEFT)));
		$pdf->Text(186, 46, $this->spaceout(str_pad($vo->aims[0]->A16, 2, '0', STR_PAD_LEFT)));
		$pdf->Text(225, 46, $this->spaceout($vo->aims[0]->A26));
		$pdf->Text(281, 46, $this->spaceout($vo->aims[0]->A53));


		if ($vo->aims[0]->A27 != '' && $vo->aims[0]->A27 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A27, $matches);
			$pdf->Text(47, 58, $this->spaceout($matches[1][0]));
			$pdf->Text(62, 58, $this->spaceout($matches[2][0]));
			$pdf->Text(90, 58, $this->spaceout($matches[4][0]));
		}

		if ($vo->aims[0]->A28 != '' && $vo->aims[0]->A28 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A28, $matches);
			$pdf->Text(138, 58, $this->spaceout($matches[1][0]));
			$pdf->Text(153, 58, $this->spaceout($matches[2][0]));
			$pdf->Text(181, 58, $this->spaceout($matches[4][0]));
		}

		if ($vo->aims[0]->A23 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A23);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			$pdf->Text(245, 58, $this->spaceout($matches[1][0]));
			$pdf->Text(273, 58, $this->spaceout($matches[2][0], 5));
		}

		$pdf->Text(36, 68, $this->spaceout($vo->aims[0]->A51a));
		$pdf->Text(90, 68, $this->spaceout(str_pad($vo->aims[0]->A14, 2, '0', STR_PAD_LEFT)));
		$pdf->Text(141, 68, $this->spaceout($vo->aims[0]->A59, 5));
		$pdf->Text(195, 68, $this->spaceout(str_pad($vo->aims[0]->A46a, 3, '0', STR_PAD_LEFT), 5));
		$pdf->Text(217, 68, $this->spaceout(str_pad($vo->aims[0]->A46b, 3, '0', STR_PAD_LEFT), 5));
		$pdf->Text(258, 68, $this->spaceout($vo->aims[0]->A49, 5));

		$pdf->Text(38, 80, $this->spaceout($vo->aims[0]->A44, 4));

		if ($vo->aims[0]->A45 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A45);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			$pdf->Text(188, 80, $this->spaceout($matches[1][0]));
			$pdf->Text(217, 80, $this->spaceout($matches[2][0]));
		}

		$pdf->Text(280, 80, $this->spaceout($vo->aims[0]->A63, 4));

		$pdf->Text(55, 92, $this->spaceout($vo->aims[0]->A21, 4));

		if ($vo->aims[0]->A22 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A22);
			preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(115, 92, $this->spaceout($matches[1][0]));
			$pdf->Text(140, 92, $this->spaceout($matches[2][0]));
		}

		$pdf->Text(196, 92, $this->spaceout($vo->aims[0]->A64, 5));
		$pdf->Text(259, 92, $this->spaceout($vo->aims[0]->A65, 5));




		if ($vo->aims[0]->A54 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A54);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,5})/", $pcode, $matches);

			$pdf->Text(50, 104, $this->spaceout($matches[1][0]));
			$pdf->Text(82, 104, $this->spaceout($matches[2][0]));
		}

		if ($vo->aims[0]->A61 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A61);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(57, 116, $this->spaceout($matches[1][0]));
			$pdf->Text(88, 116, $this->spaceout($matches[2][0]));
		}


		$pdf->Text(152, 116, $this->spaceout($vo->aims[0]->A62));
		$pdf->Text(219, 116, $this->spaceout($vo->aims[0]->A66));
		$pdf->Text(280, 116, $this->spaceout($vo->aims[0]->A67));


		//	$pdf->Text(208,102,$this->spaceout($vo->aims[0]->A32,5));
		//	$pdf->Text(281,106,$this->spaceout($vo->aims[0]->A06));


		if ($vo->aims[0]->A31 != '' && $vo->aims[0]->A31 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A31, $matches);
			$pdf->Text(46, 144, $this->spaceout($matches[1][0]));
			$pdf->Text(61, 144, $this->spaceout($matches[2][0]));
			$pdf->Text(89, 144, $this->spaceout($matches[4][0]));
		}

		if ($vo->aims[0]->A40 != '' && $vo->aims[0]->A40 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A40, $matches);
			$pdf->Text(155, 144, $this->spaceout($matches[1][0]));
			$pdf->Text(170, 144, $this->spaceout($matches[2][0]));
			$pdf->Text(198, 144, $this->spaceout($matches[4][0]));
		}

		$pdf->Text(273, 144, $this->spaceout($vo->aims[0]->A68));

		$pdf->Text(45, 155, $vo->aims[0]->A34);
		$pdf->Text(86, 155, $vo->aims[0]->A35);
		$pdf->Text(140, 155, $this->spaceout($vo->aims[0]->A36));
		$pdf->Text(221, 155, $this->spaceout($vo->aims[0]->A50));
		$pdf->Text(274, 155, $this->spaceout($vo->aims[0]->A60));

		if ($vo->aims[0]->A47a != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A47a);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(76, 187, $this->spaceout($matches[1][0], 6));
			$pdf->Text(121, 187, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->aims[0]->A47b != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A47b);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(169, 187, $this->spaceout($matches[1][0], 6));
			$pdf->Text(213, 187, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->aims[0]->A48a != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A48a);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(76, 198, $this->spaceout($matches[1][0], 6));
			$pdf->Text(121, 198, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->aims[0]->A48b != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A48b);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(169, 198, $this->spaceout($matches[1][0], 6));
			$pdf->Text(213, 198, $this->spaceout($matches[2][0], 6));
		}

		// Create One subsidiary Aim for ESF
		if ($vo->programmeaim->A10 == '70') {
			$tpl = $pdf->ImportPage(4);
			$s = $pdf->getTemplatesize($tpl);
			$pdf->AddPage('P', array($s['width'], $s['height']));
			$pdf->useTemplate($tpl);

			$pdf->Text(85, 21, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

			if ($vo->learnerinformation->L03 != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(169, 21, $this->spaceout($matches[1][0]));
				$pdf->Text(207, 21, $this->spaceout($matches[2][0]));
			}

			$pdf->Text(119, 48, $this->spaceout($vo->programmeaim->A02));


			if ($vo->programmeaim->A09 != '') {
				$pcode = str_replace(" ", "", "ZESF0001");
				preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(41, 58, $this->spaceout($matches[1][0]));
				$pdf->Text(66, 58, $this->spaceout($matches[2][0]));
			}

			$pdf->Text(111, 58, $this->spaceout($vo->programmeaim->A10));
			//$pdf->Text(148,46,$this->spaceout($vo->programmeaim->A15));
			$pdf->Text(186, 58, $this->spaceout(str_pad($vo->programmeaim->A16, 2, '0', STR_PAD_LEFT)));
			//$pdf->Text(225,46,$this->spaceout($vo->programmeaim->A26));
			$pdf->Text(279, 58, $this->spaceout($vo->programmeaim->A53));


			if ($vo->programmeaim->A27 != '' && $vo->programmeaim->A27 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A27, $matches);
				$pdf->Text(47, 69, $this->spaceout($matches[1][0]));
				$pdf->Text(62, 69, $this->spaceout($matches[2][0]));
				$pdf->Text(90, 69, $this->spaceout($matches[4][0]));
			}

			if ($vo->programmeaim->A28 != '' && $vo->programmeaim->A28 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A28, $matches);
				$pdf->Text(138, 69, $this->spaceout($matches[1][0]));
				$pdf->Text(153, 69, $this->spaceout($matches[2][0]));
				$pdf->Text(181, 69, $this->spaceout($matches[4][0]));
			}

			if ($vo->programmeaim->A23 != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A23);
				preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

				$pdf->Text(245, 69, $this->spaceout($matches[1][0]));
				$pdf->Text(272, 69, $this->spaceout($matches[2][0], 5));
			}

			$pdf->Text(38, 79, $this->spaceout(str_pad($vo->programmeaim->A51a, 2, '0', STR_PAD_LEFT)), 8);
			$pdf->Text(96, 78, $this->spaceout($vo->programmeaim->A14));
			$pdf->Text(154, 78, $this->spaceout($vo->programmeaim->A59, 5));
			$pdf->Text(250, 78, $this->spaceout(str_pad($vo->programmeaim->A46a, 3, '0', STR_PAD_LEFT), 5));
			$pdf->Text(272, 78, $this->spaceout(str_pad($vo->programmeaim->A46b, 3, '0', STR_PAD_LEFT), 5));

			$pdf->Text(54, 89, $this->spaceout($vo->programmeaim->A21, 4));

			if ($vo->programmeaim->A22 != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A22);
				preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(114, 89, $this->spaceout($matches[1][0]));
				$pdf->Text(139, 89, $this->spaceout($matches[2][0]));
			}

			$pdf->Text(211, 89, $this->spaceout($vo->programmeaim->A63, 4));

			$pdf->Text(256, 89, $this->spaceout($vo->programmeaim->A49, 5));

			if ($vo->programmeaim->A61 != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A61);
				preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(57, 100, $this->spaceout($matches[1][0]));
				$pdf->Text(88, 100, $this->spaceout($matches[2][0]));
			}


			$pdf->Text(152, 100, $this->spaceout($vo->programmeaim->A62));
			$pdf->Text(217, 100, $this->spaceout($vo->programmeaim->A66));
			$pdf->Text(280, 100, $this->spaceout($vo->programmeaim->A67));

			if ($vo->programmeaim->A31 != '' && $vo->programmeaim->A31 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A31, $matches);
				$pdf->Text(44, 115, $this->spaceout($matches[1][0]));
				$pdf->Text(59, 115, $this->spaceout($matches[2][0]));
				$pdf->Text(87, 115, $this->spaceout($matches[4][0]));
			}

			$pdf->Text(137, 115, $this->spaceout($vo->programmeaim->A50));


			if ($vo->programmeaim->A40 != '' && $vo->programmeaim->A40 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A40, $matches);
				$pdf->Text(44, 126, $this->spaceout($matches[1][0]));
				$pdf->Text(59, 126, $this->spaceout($matches[2][0]));
				$pdf->Text(87, 126, $this->spaceout($matches[4][0]));
			}

			$pdf->Text(126, 126, $vo->programmeaim->A34);
			$pdf->Text(156, 126, $vo->programmeaim->A35);
			$pdf->Text(195, 126, $this->spaceout($vo->programmeaim->A36));
			$pdf->Text(272, 126, $this->spaceout($vo->programmeaim->A60));




			if ($vo->programmeaim->A47a != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A47a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(79, 144, $this->spaceout($matches[1][0], 6));
				$pdf->Text(124, 144, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->programmeaim->A47b != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A47b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(172, 144, $this->spaceout($matches[1][0], 6));
				$pdf->Text(216, 144, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->programmeaim->A48a != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A48a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(79, 154, $this->spaceout($matches[1][0], 6));
				$pdf->Text(124, 154, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->programmeaim->A48b != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A48b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(172, 154, $this->spaceout($matches[1][0], 6));
				$pdf->Text(216, 154, $this->spaceout($matches[2][0], 6));
			}

			$pdf->Text(74, 177, $this->spaceout($vo->learnerinformation->L40a));
			$pdf->Text(89, 177, $this->spaceout($vo->learnerinformation->L40b));

			if ($vo->learnerinformation->L41a != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L41a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(74, 187, $this->spaceout($matches[1][0], 6));
				$pdf->Text(119, 187, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L41b != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L41b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(165, 187, $this->spaceout($matches[1][0], 6));
				$pdf->Text(209, 187, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L42a != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(74, 197, $this->spaceout($matches[1][0], 6));
				$pdf->Text(119, 197, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L42b != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(165, 197, $this->spaceout($matches[1][0], 6));
				$pdf->Text(209, 197, $this->spaceout($matches[2][0], 6));
			}
		}
		// End

		// Subsidiary Aims
		for ($a = 1; $a <= $vo->subaims; $a++) {

			$tpl = $pdf->ImportPage(4);
			$s = $pdf->getTemplatesize($tpl);
			$pdf->AddPage('P', array($s['width'], $s['height']));
			$pdf->useTemplate($tpl);

			$pdf->Text(85, 21, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

			if ($vo->learnerinformation->L03 != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(169, 21, $this->spaceout($matches[1][0]));
				$pdf->Text(207, 21, $this->spaceout($matches[2][0]));
			}

			$pdf->Text(119, 48, $this->spaceout($vo->aims[$a]->A02));


			if ($vo->aims[$a]->A09 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A09);
				preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(41, 58, $this->spaceout($matches[1][0]));
				$pdf->Text(66, 58, $this->spaceout($matches[2][0]));
			}

			$pdf->Text(111, 58, $this->spaceout($vo->aims[$a]->A10));
			//$pdf->Text(148,46,$this->spaceout($vo->aims[$a]->A15));
			$pdf->Text(186, 58, $this->spaceout(str_pad($vo->aims[$a]->A16, 2, '0', STR_PAD_LEFT)));
			//$pdf->Text(225,46,$this->spaceout($vo->aims[$a]->A26));
			$pdf->Text(279, 58, $this->spaceout($vo->aims[$a]->A53));


			if ($vo->aims[$a]->A27 != '' && $vo->aims[$a]->A27 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A27, $matches);
				$pdf->Text(47, 69, $this->spaceout($matches[1][0]));
				$pdf->Text(62, 69, $this->spaceout($matches[2][0]));
				$pdf->Text(90, 69, $this->spaceout($matches[4][0]));
			}

			if ($vo->aims[$a]->A28 != '' && $vo->aims[$a]->A28 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A28, $matches);
				$pdf->Text(138, 69, $this->spaceout($matches[1][0]));
				$pdf->Text(153, 69, $this->spaceout($matches[2][0]));
				$pdf->Text(181, 69, $this->spaceout($matches[4][0]));
			}

			if ($vo->aims[$a]->A23 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A23);
				preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

				$pdf->Text(245, 69, $this->spaceout($matches[1][0]));
				$pdf->Text(272, 69, $this->spaceout($matches[2][0], 5));
			}

			$pdf->Text(38, 79, $this->spaceout(str_pad($vo->aims[$a]->A51a, 2, '0', STR_PAD_LEFT)), 8);
			$pdf->Text(96, 78, $this->spaceout($vo->aims[$a]->A14));
			$pdf->Text(154, 78, $this->spaceout($vo->aims[$a]->A59, 5));
			$pdf->Text(250, 78, $this->spaceout(str_pad($vo->aims[$a]->A46a, 3, '0', STR_PAD_LEFT), 5));
			$pdf->Text(272, 78, $this->spaceout(str_pad($vo->aims[$a]->A46b, 3, '0', STR_PAD_LEFT), 5));

			$pdf->Text(54, 89, $this->spaceout($vo->aims[$a]->A21, 4));

			if ($vo->aims[$a]->A22 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A22);
				preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(114, 89, $this->spaceout($matches[1][0]));
				$pdf->Text(139, 89, $this->spaceout($matches[2][0]));
			}

			$pdf->Text(211, 89, $this->spaceout($vo->aims[$a]->A63, 4));

			$pdf->Text(256, 89, $this->spaceout($vo->aims[$a]->A49, 5));

			if ($vo->aims[$a]->A61 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A61);
				preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(57, 100, $this->spaceout($matches[1][0]));
				$pdf->Text(88, 100, $this->spaceout($matches[2][0]));
			}


			$pdf->Text(152, 100, $this->spaceout($vo->aims[$a]->A62));
			$pdf->Text(217, 100, $this->spaceout($vo->aims[$a]->A66));
			$pdf->Text(280, 100, $this->spaceout($vo->aims[$a]->A67));

			if ($vo->aims[$a]->A31 != '' && $vo->aims[$a]->A31 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A31, $matches);
				$pdf->Text(44, 115, $this->spaceout($matches[1][0]));
				$pdf->Text(59, 115, $this->spaceout($matches[2][0]));
				$pdf->Text(87, 115, $this->spaceout($matches[4][0]));
			}

			$pdf->Text(137, 115, $this->spaceout($vo->aims[$a]->A50));


			if ($vo->aims[$a]->A40 != '' && $vo->aims[$a]->A40 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A40, $matches);
				$pdf->Text(44, 126, $this->spaceout($matches[1][0]));
				$pdf->Text(59, 126, $this->spaceout($matches[2][0]));
				$pdf->Text(87, 126, $this->spaceout($matches[4][0]));
			}

			$pdf->Text(126, 126, $vo->aims[$a]->A34);
			$pdf->Text(156, 126, $vo->aims[$a]->A35);
			$pdf->Text(195, 126, $this->spaceout($vo->aims[$a]->A36));
			$pdf->Text(272, 126, $this->spaceout($vo->aims[$a]->A60));




			if ($vo->aims[$a]->A47a != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A47a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(79, 144, $this->spaceout($matches[1][0], 6));
				$pdf->Text(124, 144, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->aims[$a]->A47b != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A47b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(172, 144, $this->spaceout($matches[1][0], 6));
				$pdf->Text(216, 144, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->aims[$a]->A48a != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A48a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(79, 154, $this->spaceout($matches[1][0], 6));
				$pdf->Text(124, 154, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->aims[$a]->A48b != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A48b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(172, 154, $this->spaceout($matches[1][0], 6));
				$pdf->Text(216, 154, $this->spaceout($matches[2][0], 6));
			}

			$pdf->Text(74, 177, $this->spaceout($vo->learnerinformation->L40a));
			$pdf->Text(89, 177, $this->spaceout($vo->learnerinformation->L40b));

			if ($vo->learnerinformation->L41a != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L41a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(74, 187, $this->spaceout($matches[1][0], 6));
				$pdf->Text(119, 187, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L41b != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L41b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(165, 187, $this->spaceout($matches[1][0], 6));
				$pdf->Text(209, 187, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L42a != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(74, 197, $this->spaceout($matches[1][0], 6));
				$pdf->Text(119, 197, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L42b != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(165, 197, $this->spaceout($matches[1][0], 6));
				$pdf->Text(209, 197, $this->spaceout($matches[2][0], 6));
			}
		}


		//echo $pdf->Output();




		//Determine a temporary file name in the current directory
		$this->CleanFiles(DATA_ROOT . '/uploads/' . DB_NAME);
		$file = basename(tempnam(DATA_ROOT . '/uploads/' . DB_NAME . '/', 'tmp'));

		$file = DATA_ROOT . '/uploads/' . DB_NAME . '/' . $file;

		rename($file, $file . '.pdf');
		//Save PDF to file
		$pdf->Output($file . '.pdf', 'F');

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
		readfile($file . '.pdf');
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

		$t = time();
		$h = opendir($dir);
		while ($file = readdir($h)) {
			if (substr($file, 0, 3) == 'tmp') {
				$path = $dir . '/' . $file;
				if ($t - filemtime($path) > 3600)
					@unlink($path);
			}
		}
		closedir($h);
	}
}