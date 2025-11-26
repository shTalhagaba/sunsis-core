<?php

use setasign\Fpdi\Fpdi;

class pdf_from_ilr2011 implements IAction
{
	public function execute(PDO $link)
	{
		ini_set("ignore_user_abort", 1); // Required to allow the PHP script time to delete the temporary PDF file

		$xml = isset($_REQUEST['xml']) ? $_REQUEST['xml'] : '';

		$vo = Ilr2011::loadFromXML($xml);

		$_SESSION['bc']->add($link, "do.php?_action=pdf_from_ilr2011&xml=" . $xml, "ILR PDF");
		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new FPDI();

		$pagecount = $pdf->setSourceFile('ilr2011.pdf');

		$tpl = $pdf->ImportPage(1);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->SetFont('Arial', '', 10);

		$pdf->Text(82, 19, strtoupper($this->spaceout($vo->learnerinformation->L01, 6)));

		if ($vo->learnerinformation->L03 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(157, 19, $this->spaceout($matches[1][0], 6));
			$pdf->Text(202, 19, $this->spaceout($matches[2][0], 6));
		}

		$l03 = $vo->learnerinformation->L09;


		if ($vo->learnerinformation->L46 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L46);
			preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(82, 10, $this->spaceout($matches[1][0], 6));
			$pdf->Text(112, 10, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->learnerinformation->L45 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L45);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,5})/", $pcode, $matches);

			$pdf->Text(172, 10, $this->spaceout($matches[1][0], 6));
			$pdf->Text(210, 10, $this->spaceout($matches[2][0], 6));
		}


		$pdf->Text(33, 41, strtoupper($vo->learnerinformation->L09));
		$pdf->Text(140, 41, strtoupper($vo->learnerinformation->L10));

		if ($vo->learnerinformation->L11 != '' && $vo->learnerinformation->L11 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->learnerinformation->L11, $matches);
			$pdf->Text(229, 41, $this->spaceout($matches[1][0], 5));
			$pdf->Text(246, 41, $this->spaceout($matches[2][0], 5));
			$pdf->Text(280, 41, $this->spaceout($matches[4][0], 5));
		}


		$pdf->Text(33, 50, $vo->learnerinformation->L18);
		$pdf->Text(123, 50, $vo->learnerinformation->L19);
		$pdf->Text(240, 50, $vo->learnerinformation->L24);

		$pdf->Text(33, 62, strtoupper($vo->learnerinformation->L20));
		$pdf->Text(123, 62, strtoupper($vo->learnerinformation->L21));
		$pdf->Text(235, 62, $vo->learnerinformation->L23);


		$matches = array();
		if ($vo->learnerinformation->L22 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L22);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			@$pdf->Text(33, 73, $this->spaceout($matches[1][0], 5));
			@$pdf->Text(71, 73, $this->spaceout($matches[2][0], 5));
		}
		if ($vo->learnerinformation->L17 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L17);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			@$pdf->Text(123, 73, $this->spaceout($matches[1][0], 5));
			@$pdf->Text(161, 73, $this->spaceout($matches[2][0], 5));
		}
		if ($vo->learnerinformation->L26 != '') {

			preg_match_all("/(^[a-zA-Z]{2})([0-9]{2})([0-9]{2})([0-9]{2})([a-zA-Z]{1})/", $vo->learnerinformation->L26, $matches);

			@$pdf->Text(215, 73, $this->spaceout($matches[1][0], 6));
			@$pdf->Text(233, 73, $this->spaceout($matches[2][0], 6));
			@$pdf->Text(250, 73, $this->spaceout($matches[3][0], 6));
			@$pdf->Text(268, 73, $this->spaceout($matches[4][0], 6));
			@$pdf->Text(285, 73, $this->spaceout($matches[5][0], 6));
		}


		$pdf->Text(33, 82, $vo->learnerinformation->L51);
		$pdf->Text(170, 83, $vo->learnerinformation->L13);
		$pdf->Text(215, 83, $this->spaceout($vo->learnerinformation->L12, 5));
		$pdf->Text(278, 83, $this->spaceout(str_pad($vo->learnerinformation->L35, 2, '0', STR_PAD_LEFT), 5));


		// re
		if ($vo->learnerinformation->L14 == 2) {
			$pdf->Text(132, 101, 'N');
		} else if ($vo->learnerinformation->L14 == 1) {
			$pdf->Text(132, 101, 'Y');
		} else if ($vo->learnerinformation->L14 == 9) {
		} else {
			$pdf->Text(132, 101, $vo->learnerinformation->L14);
		}
		$pdf->Text(218, 101, $this->spaceout(str_pad($vo->learnerinformation->L15, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(270, 101, $this->spaceout(str_pad($vo->learnerinformation->L16, 2, '0', STR_PAD_LEFT), 5));

		$pdf->Text(32, 114, $this->spaceout($vo->learnerinformation->L34a, 5));
		$pdf->Text(77, 114, $this->spaceout($vo->learnerinformation->L34b, 5));
		$pdf->Text(122, 114, $this->spaceout($vo->learnerinformation->L34c, 5));
		$pdf->Text(168, 114, $this->spaceout($vo->learnerinformation->L34d, 5));

		$pdf->Text(217, 114, $this->spaceout($vo->learnerinformation->L40a, 6));
		$pdf->Text(270, 114, $this->spaceout($vo->learnerinformation->L40b, 6));


		//		$pdf->Text(42,122,$this->spaceout(str_pad($vo->learnerinformation->L35,2,'0',STR_PAD_LEFT),5));
		//		$pdf->Text(96,122,$this->spaceout(str_pad($vo->learnerinformation->L36,2,'0',STR_PAD_LEFT),5));

		//		$pdf->Text(209,112,$this->spaceout(str_pad($vo->learnerinformation->L47,2,'0',STR_PAD_LEFT),5));



		//		$pdf->Text(258,139,$this->spaceout($vo->learnerinformation->L28a));
		//		$pdf->Text(273,139,$this->spaceout($vo->learnerinformation->L28b));

		switch ($vo->learnerinformation->L27) {
			case '1':
				$pdf->Image("./images/register/small-tick2.gif", 97, 159, 4, 4);
				$pdf->Image("./images/register/small-tick2.gif", 132, 159, 4, 4);
				break;
			case '3':
				$pdf->Image("./images/register/small-tick2.gif", 97, 159, 4, 4);
				break;
			case '4':
				$pdf->Image("./images/register/small-tick2.gif", 132, 159, 4, 4);
				break;
		}


		switch ($vo->learnerinformation->L52) {
			case '1':
				$pdf->Image("./images/register/small-tick2.gif", 157, 159, 4, 4);
				break;
			case '2':
				$pdf->Image("./images/register/small-tick2.gif", 183, 159, 4, 4);
				break;
			case '3':
				$pdf->Image("./images/register/small-tick2.gif", 210, 159, 4, 4);
				break;
			case '4':
				$pdf->Image("./images/register/small-tick2.gif", 157, 159, 4, 4);
				$pdf->Image("./images/register/small-tick2.gif", 183, 159, 4, 4);
				break;
			case '5':
				$pdf->Image("./images/register/small-tick2.gif", 157, 159, 4, 4);
				$pdf->Image("./images/register/small-tick2.gif", 210, 159, 4, 4);
				break;
			case '6':
				$pdf->Image("./images/register/small-tick2.gif", 183, 159, 4, 4);
				$pdf->Image("./images/register/small-tick2.gif", 210, 159, 4, 4);
				break;
			case '7':
				$pdf->Image("./images/register/small-tick2.gif", 157, 159, 4, 4);
				$pdf->Image("./images/register/small-tick2.gif", 183, 159, 4, 4);
				$pdf->Image("./images/register/small-tick2.gif", 210, 159, 4, 4);
				break;
		}


		$tpl = $pdf->ImportPage(2);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->Text(65, 17, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

		if ($vo->learnerinformation->L03 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})/", $pcode, $matches);

			$pdf->Text(157, 17, $this->spaceout($matches[1][0], 6));
			$pdf->Text(179, 17, $this->spaceout($matches[2][0], 6));
			$pdf->Text(202, 17, $this->spaceout($matches[3][0], 6));
			$pdf->Text(224, 17, $this->spaceout($matches[4][0], 6));
		}

		$l37 = $vo->learnerinformation->L37;
		if ($l37 == '1' || $l37 == '6' || $l37 == '7')
			$fdl = '1';
		elseif ($l37 == '2' || $l37 == '3' || $l37 == '4' || $l37 == '5' || $l37 == '8' || $l37 == '9' || $l37 == '10' || $l37 == '11' || $l37 == '12' || $l37 == '13' || $l37 == '14' || $l37 == '15' || $l37 == '16')
			$fdl = '4';
		elseif ($l37 == '17')
			$fdl = '6';
		elseif ($l37 == '98')
			$fdl = '98';
		else
			$fdl = $l37;

		$pdf->Text(31, 44, $this->spaceout(str_pad($fdl, 2, '0', STR_PAD_LEFT), 5));

		if ($vo->aims[0]->A27 != '' && $vo->aims[0]->A27 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A27, $matches);
			$pdf->Text(73, 44, $this->spaceout($matches[1][0], 1));
			$pdf->Text(82, 44, $this->spaceout($matches[2][0], 1));
			$pdf->Text(93, 44, $this->spaceout($matches[4][0], 1));
		}
		$pdf->Text(126, 44, $this->spaceout($vo->aims[0]->A44, 6));
		if ($vo->aims[0]->A45 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A45);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			@$pdf->Text(233, 44, $this->spaceout($matches[1][0], 5));
			@$pdf->Text(271, 44, $this->spaceout($matches[2][0], 5));
		}

		// EII, RFU and BSI
		if ($l37 == '6') {
			@$pdf->Text(82, 55, $this->spaceout('1', 5));
		} elseif ($l37 == '7') {
			@$pdf->Text(82, 55, $this->spaceout('2', 5));
		}

		if ($l37 == '3' || $l37 == '8' || $l37 == '9' || $l37 == '10') {
			@$pdf->Text(127, 55, $this->spaceout('1', 5));
		} elseif ($l37 == '4' || $l37 == '11' || $l37 == '12' || $l37 == '13') {
			@$pdf->Text(127, 55, $this->spaceout('2', 5));
		}

		if ($l37 == '8' || $l37 == '11' || $l37 == '14') {
			@$pdf->Text(172, 55, $this->spaceout('1', 5));
		} elseif ($l37 == '9' || $l37 == '12' || $l37 == '15') {
			@$pdf->Text(172, 55, $this->spaceout('2', 5));
		}


		$l47 = $vo->learnerinformation->L47;
		if ($l47 == '1' || $l47 == '6' || $l47 == '7')
			$ces = '1';
		elseif ($l47 == '2' || $l47 == '3' || $l47 == '4' || $l47 == '5' || $l47 == '8' || $l47 == '9' || $l47 == '10' || $l47 == '11' || $l47 == '12' || $l47 == '13' || $l47 == '14' || $l47 == '15' || $l47 == '16')
			$ces = '4';
		elseif ($l47 == '17')
			$ces = '6';
		elseif ($l47 == '98')
			$ces = '98';
		else
			$ces = $l47;

		$pdf->Text(31, 80, $this->spaceout(str_pad($ces, 2, '0', STR_PAD_LEFT), 5));

		if ($vo->learnerinformation->L48 != '' && $vo->learnerinformation->L48 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->learnerinformation->L48, $matches);
			$pdf->Text(72, 80, $this->spaceout($matches[1][0], 1));
			$pdf->Text(80, 80, $this->spaceout($matches[2][0], 1));
			$pdf->Text(93, 80, $this->spaceout($matches[4][0], 1));
		}

		if (trim($vo->aims[0]->A44b) == '')
			$pdf->Text(124, 80, $this->spaceout($vo->aims[0]->A44, 6));
		else
			$pdf->Text(124, 80, $this->spaceout($vo->aims[0]->A44b, 6));


		if (trim($vo->aims[0]->A45b) == '') {
			if ($vo->aims[0]->A45 != '') {
				$pcode = str_replace(" ", "", $vo->aims[0]->A45);
				preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

				@$pdf->Text(233, 80, $this->spaceout($matches[1][0], 5));
				@$pdf->Text(271, 80, $this->spaceout($matches[2][0], 5));
			}
		} else {
			if ($vo->aims[0]->A45b != '') {
				$pcode = str_replace(" ", "", $vo->aims[0]->A45b);
				preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

				@$pdf->Text(233, 80, $this->spaceout($matches[1][0], 5));
				@$pdf->Text(271, 80, $this->spaceout($matches[2][0], 5));
			}
		}

		// EII, RFU and BSI
		if ($l47 == '6') {
			@$pdf->Text(80, 89, $this->spaceout('1', 5));
		} elseif ($l47 == '7') {
			@$pdf->Text(80, 89, $this->spaceout('2', 5));
		}

		if ($l47 == '3' || $l47 == '8' || $l47 == '9' || $l47 == '10') {
			@$pdf->Text(125, 89, $this->spaceout('1', 5));
		} elseif ($l47 == '4' || $l47 == '11' || $l47 == '12' || $l47 == '13') {
			@$pdf->Text(125, 89, $this->spaceout('2', 5));
		}

		if ($l47 == '8' || $l47 == '11' || $l47 == '14') {
			@$pdf->Text(170, 89, $this->spaceout('1', 5));
		} elseif ($l47 == '9' || $l47 == '12' || $l47 == '15') {
			@$pdf->Text(170, 89, $this->spaceout('2', 5));
		}


		if ($vo->learnerinformation->L42a != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L42a);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(43, 195, $this->spaceout($matches[1][0], 6));
			$pdf->Text(88, 195, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->learnerinformation->L42b != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L42b);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(135, 195, $this->spaceout($matches[1][0], 6));
			$pdf->Text(181, 195, $this->spaceout($matches[2][0], 6));
		}


		$pdf->Text(253, 195, $this->spaceout($vo->learnerinformation->L39, 5));


		if ($vo->aims[0]->A15 != '99') {
			$tpl = $pdf->ImportPage(3);
			$s = $pdf->getTemplatesize($tpl);
			$pdf->AddPage('P', array($s['width'], $s['height']));
			$pdf->useTemplate($tpl);

			$pdf->Text(65, 17, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

			if ($vo->learnerinformation->L03 != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
				preg_match_all("/([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})([a-zA-Z0-9]{0,3})/", $pcode, $matches);

				$pdf->Text(157, 17, $this->spaceout($matches[1][0], 6));
				$pdf->Text(179, 17, $this->spaceout($matches[2][0], 6));
				$pdf->Text(202, 17, $this->spaceout($matches[3][0], 6));
				$pdf->Text(224, 17, $this->spaceout($matches[4][0], 6));
			}

			if ($vo->programmeaim->A27 != '' && $vo->programmeaim->A27 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A27, $matches);
				$pdf->Text(185, 42, $this->spaceout($matches[1][0], 1));
				$pdf->Text(193, 42, $this->spaceout($matches[2][0], 1));
				$pdf->Text(204, 42, $this->spaceout($matches[4][0], 1));
			}

			if ($vo->programmeaim->A28 != '' && $vo->programmeaim->A28 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A28, $matches);
				$pdf->Text(262, 42, $this->spaceout($matches[1][0], 1));
				$pdf->Text(270, 42, $this->spaceout($matches[2][0], 1));
				$pdf->Text(281, 42, $this->spaceout($matches[4][0], 1));
			}

			$pdf->Text(100, 52, $this->spaceout(str_pad(substr($vo->programmeaim->A70, 2), 2, '0', STR_PAD_LEFT), 5));
			$pdf->Text(168, 52, $this->spaceout(str_pad($vo->programmeaim->A15, 2, '0', STR_PAD_LEFT), 6));
			$pdf->Text(216, 52, $this->spaceout($vo->programmeaim->A26, 6));
			$pdf->Text(277, 52, $this->spaceout(str_pad($vo->programmeaim->A16, 2, '0', STR_PAD_LEFT), 6));

			if ($vo->programmeaim->A23 != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A23);
				preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

				@$pdf->Text(40, 62, $this->spaceout($matches[1][0], 5));
				@$pdf->Text(78, 62, $this->spaceout($matches[2][0], 5));
			}

			$pdf->Text(83, 83, $this->spaceout(str_pad($vo->programmeaim->A11b, 2, '0', STR_PAD_LEFT), 6));
			$pdf->Text(136, 83, $this->spaceout(str_pad($vo->aims[0]->A63, 2, '0', STR_PAD_LEFT), 5));
			$pdf->Text(181, 83, $this->spaceout(str_pad($vo->aims[0]->A69, 1, '0', STR_PAD_LEFT), 1));
			$pdf->Text(220, 83, $this->spaceout($vo->programmeaim->A46a, 5));
			$pdf->Text(271, 83, $this->spaceout($vo->programmeaim->A46b, 5));

			if ($vo->learnerinformation->L42a != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(59, 114, $this->spaceout($matches[1][0], 6));
				$pdf->Text(104, 114, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L42b != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(161, 114, $this->spaceout($matches[1][0], 6));
				$pdf->Text(207, 114, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->programmeaim->A48a != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A48a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(59, 124, $this->spaceout($matches[1][0], 6));
				$pdf->Text(104, 124, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->programmeaim->A48b != '') {
				$pcode = str_replace(" ", "", $vo->programmeaim->A48b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(161, 124, $this->spaceout($matches[1][0], 6));
				$pdf->Text(207, 124, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->programmeaim->A31 != '' && $vo->programmeaim->A31 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A31, $matches);
				$pdf->Text(48, 143, $this->spaceout($matches[1][0], 1));
				$pdf->Text(57, 143, $this->spaceout($matches[2][0], 1));
				$pdf->Text(69, 143, $this->spaceout($matches[4][0], 1));
			}
			$pdf->Text(116, 143, $vo->programmeaim->A34);
			if ($vo->programmeaim->A34 == '3' || $vo->programmeaim->A34 == '03')
				$pdf->Text(176, 143, $this->spaceout(str_pad($vo->programmeaim->A50, 2, '0', STR_PAD_LEFT), 5));
			$pdf->Text(235, 143, $vo->programmeaim->A35);

			if ($vo->programmeaim->A40 != '' && $vo->programmeaim->A40 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->programmeaim->A40, $matches);
				$pdf->Text(48, 153, $this->spaceout($matches[1][0], 1));
				$pdf->Text(57, 153, $this->spaceout($matches[2][0], 1));
				$pdf->Text(69, 153, $this->spaceout($matches[4][0], 1));
			}
			$pdf->Text(176, 153, $this->spaceout(str_pad($vo->programmeaim->A50, 2, '0', STR_PAD_LEFT), 5));
		}

		$tpl = $pdf->ImportPage(4);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->Text(85, 17, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

		if ($vo->learnerinformation->L03 != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(158, 17, $this->spaceout($matches[1][0], 6));
			$pdf->Text(203, 17, $this->spaceout($matches[2][0], 6));
		}

		$pdf->Text(32, 41, $this->spaceout($vo->aims[0]->A04, 5));

		if ($vo->aims[0]->A09 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A09);
			preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(85, 41, $this->spaceout($matches[1][0], 6));
			$pdf->Text(115, 41, $this->spaceout($matches[2][0], 6));
		}
		if ($vo->aims[0]->A27 != '' && $vo->aims[0]->A27 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A27, $matches);
			$pdf->Text(184, 41, $this->spaceout($matches[1][0], 1));
			$pdf->Text(193, 41, $this->spaceout($matches[2][0], 1));
			$pdf->Text(205, 41, $this->spaceout($matches[4][0], 1));
		}

		if ($vo->aims[0]->A28 != '' && $vo->aims[0]->A28 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A28, $matches);
			$pdf->Text(262, 41, $this->spaceout($matches[1][0], 1));
			$pdf->Text(270, 41, $this->spaceout($matches[2][0], 1));
			$pdf->Text(282, 41, $this->spaceout($matches[4][0], 1));
		}

		$pdf->Text(32, 51, $this->spaceout($vo->aims[0]->A10, 5));
		$pdf->Text(85, 51, $this->spaceout(str_pad($vo->aims[0]->A15, 2, '0', STR_PAD_LEFT), 5));
		$pdf->Text(138, 51, $this->spaceout($vo->aims[0]->A26, 6));
		$pdf->Text(217, 51, $this->spaceout($vo->aims[0]->A51a, 6));
		$pdf->Text(278, 51, $this->spaceout(str_pad($vo->aims[0]->A18, 2, '0', STR_PAD_LEFT), 5));

		if ($vo->aims[0]->A23 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A23);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

			@$pdf->Text(40, 63, $this->spaceout($matches[1][0], 5));
			@$pdf->Text(78, 63, $this->spaceout($matches[2][0], 5));
		}
		if ($vo->aims[0]->A22 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A22);
			preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(153, 63, $this->spaceout($matches[1][0], 6));
			$pdf->Text(183, 63, $this->spaceout($matches[2][0], 6));
		}
		$pdf->Text(270, 63, $this->spaceout(str_pad($vo->aims[0]->A59, 3, '0', STR_PAD_LEFT), 5));

		if ($vo->aims[0]->A61 != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A61);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

			$pdf->Text(47, 81, $this->spaceout($matches[1][0], 6));
			$pdf->Text(85, 81, $this->spaceout($matches[2][0], 6));
		}
		$pdf->Text(175, 81, $this->spaceout($vo->aims[0]->A62, 6));

		$pdf->Text(32, 102, $this->spaceout(str_pad($vo->aims[0]->A71, 2, '0', STR_PAD_LEFT), 1));
		if ($vo->aims[0]->A53 == '11') {
			$pdf->Text(80, 102, $this->spaceout('1', 6));
		} elseif ($vo->aims[0]->A53 == '12') {
			$pdf->Text(133, 102, $this->spaceout('1', 6));
		} elseif ($vo->aims[0]->A53 == '13') {
			$pdf->Text(80, 102, $this->spaceout('1', 6));
			$pdf->Text(133, 102, $this->spaceout('1', 6));
		}

		$pdf->Text(30, 125, $this->spaceout(str_pad($vo->aims[0]->A66, 2, '0', STR_PAD_LEFT), 5));

		// DBS
		$dbs = new Date($vo->aims[0]->A27);
		$dbs->subtractDays(1);
		$dbs = Date::toShort($dbs);
		preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $dbs, $matches);
		@$pdf->Text(70, 125, $this->spaceout($matches[1][0], 1));
		@$pdf->Text(78, 125, $this->spaceout($matches[2][0], 1));
		@$pdf->Text(92, 125, $this->spaceout($matches[4][0], 1));

		@$pdf->Text(176, 125, $this->spaceout(str_pad($vo->aims[0]->A67, 2, '0', STR_PAD_LEFT), 0));


		if ($vo->learnerinformation->L42a != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L42a);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(59, 142, $this->spaceout($matches[1][0], 6));
			$pdf->Text(104, 142, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->learnerinformation->L42b != '') {
			$pcode = str_replace(" ", "", $vo->learnerinformation->L42b);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(159, 142, $this->spaceout($matches[1][0], 6));
			$pdf->Text(205, 142, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->aims[0]->A48a != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A48a);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(59, 153, $this->spaceout($matches[1][0], 6));
			$pdf->Text(104, 153, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->aims[0]->A48b != '') {
			$pcode = str_replace(" ", "", $vo->aims[0]->A48b);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

			$pdf->Text(159, 153, $this->spaceout($matches[1][0], 6));
			$pdf->Text(205, 153, $this->spaceout($matches[2][0], 6));
		}

		if ($vo->aims[0]->A31 != '' && $vo->aims[0]->A31 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A31, $matches);
			$pdf->Text(32, 172, $this->spaceout($matches[1][0], 1));
			$pdf->Text(40, 172, $this->spaceout($matches[2][0], 1));
			$pdf->Text(53, 172, $this->spaceout($matches[4][0], 1));
		}
		$pdf->Text(96, 172, $this->spaceout(str_pad($vo->aims[0]->A34, 1, '0', STR_PAD_LEFT), 5));
		if ($vo->aims[0]->A34 == '3' || $vo->aims[0]->A34 == '03')
			$pdf->Text(130, 172, $this->spaceout($vo->aims[0]->A50, 5));
		$pdf->Text(176, 172, $vo->aims[0]->A35);
		if ($vo->aims[0]->A40 != '' && $vo->aims[0]->A40 != '00000000') {
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[0]->A40, $matches);
			$pdf->Text(221, 172, $this->spaceout($matches[1][0], 1));
			$pdf->Text(229, 172, $this->spaceout($matches[2][0], 1));
			$pdf->Text(241, 172, $this->spaceout($matches[4][0], 1));
		}

		$pdf->Text(32, 183, $this->spaceout($vo->aims[0]->A36, 5));
		$pdf->Text(177, 183, $this->spaceout($vo->aims[0]->A60, 5));

		// Subsidiary Aims
		for ($a = 1; $a <= $vo->subaims; $a++) {

			$tpl = $pdf->ImportPage(4);
			$s = $pdf->getTemplatesize($tpl);
			$pdf->AddPage('P', array($s['width'], $s['height']));
			$pdf->useTemplate($tpl);

			$pdf->Text(85, 17, $vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);

			if ($vo->learnerinformation->L03 != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L03);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(158, 17, $this->spaceout($matches[1][0], 6));
				$pdf->Text(203, 17, $this->spaceout($matches[2][0], 6));
			}

			$pdf->Text(32, 41, $this->spaceout('3', 5));
			if ($vo->aims[$a]->A09 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A09);
				preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(85, 41, $this->spaceout($matches[1][0], 6));
				$pdf->Text(115, 41, $this->spaceout($matches[2][0], 6));
			}
			if ($vo->aims[$a]->A27 != '' && $vo->aims[$a]->A27 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A27, $matches);
				$pdf->Text(184, 41, $this->spaceout($matches[1][0], 1));
				$pdf->Text(193, 41, $this->spaceout($matches[2][0], 1));
				$pdf->Text(205, 41, $this->spaceout($matches[4][0], 1));
			}

			if ($vo->aims[$a]->A28 != '' && $vo->aims[$a]->A28 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A28, $matches);
				$pdf->Text(262, 41, $this->spaceout($matches[1][0], 1));
				$pdf->Text(270, 41, $this->spaceout($matches[2][0], 1));
				$pdf->Text(282, 41, $this->spaceout($matches[4][0], 1));
			}

			$pdf->Text(32, 51, $this->spaceout($vo->aims[$a]->A10, 5));
			$pdf->Text(85, 51, $this->spaceout(str_pad($vo->aims[$a]->A15, 2, '0', STR_PAD_LEFT), 5));
			$pdf->Text(138, 51, $this->spaceout($vo->aims[$a]->A26, 6));
			$pdf->Text(217, 51, $this->spaceout($vo->aims[$a]->A51a, 6));
			$pdf->Text(278, 51, $this->spaceout(str_pad($vo->aims[$a]->A18, 2, '0', STR_PAD_LEFT), 5));

			if ($vo->aims[$a]->A23 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A23);
				preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);

				@$pdf->Text(40, 63, $this->spaceout($matches[1][0], 5));
				@$pdf->Text(78, 63, $this->spaceout($matches[2][0], 5));
			}
			if ($vo->aims[$a]->A22 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A22);
				preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(153, 63, $this->spaceout($matches[1][0], 6));
				$pdf->Text(183, 63, $this->spaceout($matches[2][0], 6));
			}
			$pdf->Text(270, 63, $this->spaceout(str_pad($vo->aims[$a]->A59, 3, '0', STR_PAD_LEFT), 5));

			if ($vo->aims[$a]->A61 != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A61);
				preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,4})/", $pcode, $matches);

				$pdf->Text(47, 81, $this->spaceout($matches[1][0], 6));
				$pdf->Text(85, 81, $this->spaceout($matches[2][0], 6));
			}
			$pdf->Text(175, 81, $this->spaceout($vo->aims[$a]->A62, 6));

			$pdf->Text(32, 102, $this->spaceout(str_pad($vo->aims[$a]->A71, 2, '0', STR_PAD_LEFT), 1));
			if ($vo->aims[$a]->A53 == '11') {
				$pdf->Text(80, 102, $this->spaceout('1', 6));
			} elseif ($vo->aims[$a]->A53 == '12') {
				$pdf->Text(133, 102, $this->spaceout('1', 6));
			} elseif ($vo->aims[$a]->A53 == '13') {
				$pdf->Text(80, 102, $this->spaceout('1', 6));
				$pdf->Text(133, 102, $this->spaceout('1', 6));
			}


			// DBS
			$dbs = new Date($vo->aims[$a]->A27);
			$dbs->subtractDays(1);
			$dbs = Date::toShort($dbs);
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $dbs, $matches);
			$pdf->Text(70, 125, $this->spaceout($matches[1][0], 1));
			$pdf->Text(78, 125, $this->spaceout($matches[2][0], 1));
			$pdf->Text(92, 125, $this->spaceout($matches[4][0], 1));


			$pdf->Text(30, 125, $this->spaceout(str_pad($vo->aims[$a]->A66, 2, '0', STR_PAD_LEFT), 5));
			$pdf->Text(176, 125, $this->spaceout(str_pad($vo->aims[$a]->A67, 2, '0', STR_PAD_LEFT), 0));


			if ($vo->learnerinformation->L42a != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(59, 142, $this->spaceout($matches[1][0], 6));
				$pdf->Text(104, 142, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->learnerinformation->L42b != '') {
				$pcode = str_replace(" ", "", $vo->learnerinformation->L42b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(159, 142, $this->spaceout($matches[1][0], 6));
				$pdf->Text(205, 142, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->aims[$a]->A48a != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A48a);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(59, 153, $this->spaceout($matches[1][0], 6));
				$pdf->Text(104, 153, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->aims[$a]->A48b != '') {
				$pcode = str_replace(" ", "", $vo->aims[$a]->A48b);
				preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/", $pcode, $matches);

				$pdf->Text(159, 153, $this->spaceout($matches[1][0], 6));
				$pdf->Text(205, 153, $this->spaceout($matches[2][0], 6));
			}

			if ($vo->aims[$a]->A31 != '' && $vo->aims[$a]->A31 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A31, $matches);
				$pdf->Text(32, 172, $this->spaceout($matches[1][0], 1));
				$pdf->Text(40, 172, $this->spaceout($matches[2][0], 1));
				$pdf->Text(53, 172, $this->spaceout($matches[4][0], 1));
			}
			$pdf->Text(96, 172, $this->spaceout(str_pad($vo->aims[$a]->A34, 1, '0', STR_PAD_LEFT), 5));
			if ($vo->aims[$a]->A34 == '3' || $vo->aims[$a]->A34 == '03')
				$pdf->Text(130, 172, $this->spaceout($vo->aims[$a]->A50, 5));
			$pdf->Text(176, 172, $vo->aims[$a]->A35);
			if ($vo->aims[$a]->A40 != '' && $vo->aims[$a]->A40 != '00000000') {
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $vo->aims[$a]->A40, $matches);
				$pdf->Text(221, 172, $this->spaceout($matches[1][0], 1));
				$pdf->Text(229, 172, $this->spaceout($matches[2][0], 1));
				$pdf->Text(241, 172, $this->spaceout($matches[4][0], 1));
			}

			$pdf->Text(32, 183, $this->spaceout($vo->aims[$a]->A36, 5));
			$pdf->Text(177, 183, $this->spaceout($vo->aims[$a]->A60, 5));
		}


		//echo $pdf->Output();

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

	function CleanFiles($dir)
	{
		//Delete temporary files
		$temporary_files = glob($dir . '/tmp*');
		foreach ($temporary_files as $absolute_path) {
			unlink($absolute_path);
		}
	}
}
