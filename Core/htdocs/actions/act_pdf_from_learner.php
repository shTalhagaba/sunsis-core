<?php

use setasign\Fpdi\Fpdi;

class pdf_from_learner implements IAction
{

	/**
	 * (non-PHPdoc)
	 * @see IAction::execute()
	 */
	public function execute(PDO $link)
	{

		/**
		 * re 29/09/2011 for reference on use:
		 * $pdf->Text(page_column, page_row, input_text);
		 */

		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';

		$pdf = new FPDI();

		/**
		 * re 28/10/2011 if we have a soap / destiny enabled system
		 * check to see if we have the actual filled in destiny form
		 * naming convention:
		 *   /uploads/<system_name>/<username>/destiny-ILRV2.pdf
		 *   this is disabled as not required functionality
		 */

		$pagecount = $pdf->setSourceFile('ilr2015.pdf');

		$tpl = $pdf->ImportPage(1);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->SetFont('Arial', '', 10);

		$vo = User::loadFromDatabase($link, $username);


		// ULN 
		// - a user object has user->uln as well as user->l45
		// - this is duplication for no good reason
		if ($vo->l45 != '') {
			$pdf->Text(219, 7, $this->spaceout(substr($vo->l45, 0, 5), 5));
			$pdf->Text(255, 7, $this->spaceout(substr($vo->l45, 5, 5), 6));
		}

		// UK PRN
		if ($vo->org->ukprn != '') {
			$ukprn_code = str_replace(" ", "", $vo->org->ukprn);
			$pdf->Text(63, 7, $this->spaceout($ukprn_code, 6));
		}

		// L01 Provider no (UPIN)
		if ($vo->org->upin != '') {
			$upin_code = str_replace(" ", "", $vo->org->upin);
			$pdf->Text(81, 17, $this->spaceout($upin_code, 6));
		}

		$pdf->Text(33, 38, strtoupper($vo->surname));
		$pdf->Text(140, 38, strtoupper($vo->firstnames));

		// re - date of birth
		if ($vo->dob != '' && $vo->dob != '00000000') {
			$dob = Date::toShort($vo->dob);
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/", $dob, $matches);
			$pdf->Text(229, 38, $this->spaceout($matches[1][0], 5));
			$pdf->Text(246, 38, $this->spaceout($matches[2][0], 5));
			$pdf->Text(262, 38, $this->spaceout($matches[3][0], 5));
			$pdf->Text(278, 38, $this->spaceout($matches[4][0], 5));
		}

		$pdf->Text(33, 47, $vo->home_address_line_1);
		$pdf->Text(123, 47, $vo->home_address_line_2);
		$pdf->Text(33, 59, $vo->home_address_line_3);
		$pdf->Text(123, 59, $vo->home_address_line_4);

		// re - country of domicile
		// going to do a lookup here
		if ($vo->l24 != '') {
			$domicile_sql = "SELECT Domicile_Desc FROM lis201314.ilr_domicile WHERE Domicile = '" . $vo->l24 . "'";
			$domicile_desc = DAO::getSingleValue($link, $domicile_sql);
			if ('' != $domicile_desc) {
				//$pdf->Text(240,50,$domicile_desc);
			} else {
				//$pdf->Text(240,50, $vo->l24);
			}
		}

		// re - home telephone number
		$pdf->Text(235, 59, $vo->home_telephone);

		$matches = array();
		if ($vo->home_postcode != '') {
			$pcode = str_replace(" ", "", $vo->home_postcode);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);
			if (isset($matches[2][0])) {
				$pdf->Text(33, 70, $this->spaceout($matches[1][0], 5));
				$pdf->Text(71, 70, $this->spaceout($matches[2][0], 5));
			}
		}

		// re - national insurance number
		// ---
		if ($vo->ni != '') {
			preg_match_all("/(^[a-zA-Z]{2})([0-9]{2})([0-9]{2})([0-9]{2})([a-zA-Z]{1})/", $vo->ni, $matches);
			if (isset($matches[1][0])) {
				$pdf->Text(215, 70, $this->spaceout($matches[1][0], 6));
				$pdf->Text(233, 70, $this->spaceout($matches[2][0], 6));
				$pdf->Text(250, 70, $this->spaceout($matches[3][0], 6));
				$pdf->Text(268, 70, $this->spaceout($matches[4][0], 6));
				$pdf->Text(285, 70, $this->spaceout($matches[5][0], 6));
			}
		}
		// ---

		// re - insert email address
		$pdf->Text(33, 79, $vo->home_email);
		// re - gender
		$pdf->Text(230, 49, $vo->gender);
		// re - ethnicity
		$pdf->Text(259, 49, $this->spaceout($vo->ethnicity, 5));
		// re - prior attainment
		$pdf->Text(34, 140, $this->spaceout(str_pad($vo->l35, 2, '0', STR_PAD_LEFT), 5));

		// re - disability flag
		if ($vo->l14 == 2) {
			$pdf->Text(169, 105, 'N');
		} else if ($vo->l14 == 1) {
			$pdf->Text(169, 105, 'Y');
		} else if ($vo->l14 == 9) {
		} else {
			$pdf->Text(169, 105, $vo->l14);
		}

		// re - LLDD & health problem type & code
		// ---		
		//$pdf->Text(218,105,$this->spaceout(str_pad($vo->l15,2,'0',STR_PAD_LEFT),5));
		//$pdf->Text(270,105,$this->spaceout(str_pad($vo->l16,2,'0',STR_PAD_LEFT),5));
		// ---

		// re - learner FAM types and identifiers
		// ---
		$pdf->Text(34, 153, $this->spaceout($vo->l34a, 5));
		$pdf->Text(79, 153, $this->spaceout($vo->l34b, 5));
		$pdf->Text(124, 153, $this->spaceout($vo->l34c, 5));
		$pdf->Text(169, 153, $this->spaceout($vo->l34d, 5));
		// ---

		// re - learner NLM values
		// ---
		$pdf->Text(218, 153, $this->spaceout($vo->l40a, 6));
		$pdf->Text(271, 153, $this->spaceout($vo->l40b, 6));
		// ---

		// re - only output page one of the ilr
		// #TODO verify that this is ok
		// ---

		$tpl = $pdf->ImportPage(2);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->Text(65, 17, strtoupper($vo->firstnames) . ' ' . strtoupper($vo->surname));

		$row = 41;
		$row2 = 58;
		$pdf->Text(33, $row, $this->spaceout(str_pad($vo->l37, 2, '0', STR_PAD_LEFT), 5));

		$edrs = DAO::getSingleValue($link, "select edrs from organisations where id = {$vo->employer_id}");
		$pdf->Text(131, $row, $this->spaceout($edrs, 6));
		$postcode = DAO::getSingleValue($link, "select postcode from locations where organisations_id = {$vo->employer_id}");
		if ($postcode != '') {
			$pcode = str_replace(" ", "", $postcode);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/", $pcode, $matches);
			//@$pdf->Text(233,$row,$this->spaceout($matches[1][0],5));
			//@$pdf->Text(271,$row,$this->spaceout($matches[2][0],5));
		}
		$row2 += 30;
		$row += 30;



		echo $pdf->Output();
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
}
