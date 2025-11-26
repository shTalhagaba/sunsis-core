<?php

use setasign\Fpdi\Fpdi;

class pdf_success_rates implements IAction
{
	public function execute(PDO $link)
	{


		//$_SESSION['bc']->add($link, "do.php?_action=pdf_from_ilr&xml=" . $xml, "ILR PDF");
		$leavers = isset($_REQUEST['leavers']) ? $_REQUEST['leavers'] : '';
		$timely_leavers = isset($_REQUEST['timely_leavers']) ? $_REQUEST['timely_leavers'] : '';
		$achievers = isset($_REQUEST['achievers']) ? $_REQUEST['achievers'] : '';
		$timely_achievers = isset($_REQUEST['timely_achievers']) ? $_REQUEST['timely_achievers'] : '';
		$submission = isset($_REQUEST['submission']) ? $_REQUEST['submission'] : '';
		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new FPDI();

		$pagecount = $pdf->setSourceFile('success_rates1.pdf');

		$tpl = $pdf->ImportPage(1);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['width'], $s['height']));
		$pdf->useTemplate($tpl);

		$pdf->SetFont('Arial', '', 8);

		$organisation = SystemOwner::loadFromDatabase($link, 11);

		$pdf->Text(45, 29, strtoupper($organisation->upin . " " . $organisation->legal_name));
		$pdf->Text(175, 31, strtoupper($organisation->ukprn));

		//$submission = DAO::getSingleValue($link, "SELECT central.lookup_submission_dates.submission FROM central.lookup_submission_dates WHERE central.lookup_submission_dates.start_submission_date < CURDATE() AND central.lookup_submission_dates.last_submission_date > CURDATE();");
		$cyear = DAO::getSingleValue($link, "SELECT contract_year FROM contracts WHERE CURDATE()>= start_date AND CURDATE()<= end_date LIMIT 1,1;");

		$pdf->Text(45, 39, strtoupper($cyear . "/" . (substr($cyear, -2) + 1) . " P" . substr($submission, -2)));

		$pdf->Text(182, 74, ("Year P" . substr($submission, -2)));


		// Display years 
		$pdf->Text(184, 79, ($cyear . "/" . (substr($cyear, -2) + 1)));
		$pdf->Text(159, 79, ($cyear - 1 . "/" . str_pad((substr($cyear - 1, -2) + 1), 2, '0', STR_PAD_LEFT)));
		$pdf->Text(139, 79, ($cyear - 1 . "/" . str_pad((substr($cyear - 1, -2) + 1), 2, '0', STR_PAD_LEFT)));
		$pdf->Text(119, 79, ($cyear - 2 . "/" . str_pad((substr($cyear - 2, -2) + 1), 2, '0', STR_PAD_LEFT)));
		$pdf->Text(99, 79, ($cyear - 3 . "/" . str_pad((substr($cyear - 3, -2) + 1), 2, '0', STR_PAD_LEFT)));


		// Display Overall and Timely Achievers and Leavers for current year
		$pdf->setXY(177, 83);
		$pdf->Cell(23, 5, $achievers, 0, 0, 'R');

		$pdf->setXY(177, 88);
		$pdf->Cell(23, 5, $leavers, 0, 0, 'R');

		if ($leavers == 0)
			$overall = $achievers;
		else
			$overall = sprintf("%.1f", $achievers / $leavers * 100);

		$pdf->SetFont('Arial', 'B', 8);
		$pdf->setXY(177, 93);
		$pdf->Cell(23, 5, $overall . "%", 0, 0, 'R');

		$pdf->SetFont('Arial', '', 8);
		$pdf->setXY(177, 103);
		$pdf->Cell(23, 5, $timely_achievers, 0, 0, 'R');

		$pdf->setXY(177, 108);
		$pdf->Cell(23, 5, $timely_leavers, 0, 0, 'R');

		if ($timely_leavers == 0)
			$timely = $timely_achievers;
		else
			$timely = sprintf("%.1f", $timely_achievers / $timely_leavers * 100);

		$pdf->SetFont('Arial', 'B', 8);
		$pdf->setXY(177, 113);
		$pdf->Cell(23, 5, $timely . "%", 0, 0, 'R');

		// Calculate 3 previous years
		for ($a = 1; $a <= 3; $a++) {

			$cyear--;

			$sql = <<<HEREDOC
SELECT 
	* 
FROM 
	ilr 
	LEFT JOIN contracts on contracts.id = ilr.contract_id
	where contracts.contract_year = $cyear and submission = 'W13'
	order by L03
HEREDOC;

			$st = $link->query($sql);
			if ($st) {
				//echo $this->getViewNavigator();
				$data = '';

				$contract_year = $cyear;
				$contract_start_date = DAO::getSingleValue($link, "select start_date from contracts where contract_year = $contract_year limit 1,1");
				$contract_end_date = DAO::getSingleValue($link, "select end_date from contracts where contract_year = $contract_year limit 1,1");
				$contract_start_date = new Date($contract_start_date);
				$contract_end_date = new Date($contract_end_date);

				$n = 0;
				$serial = 0;
				$l03 = '';
				$leavers = 0;
				$timely_leavers = 0;
				$achievers = 0;
				$timely_achievers = 0;

				while ($row = $st->fetch()) {
					try {
						$ilr = Ilr2009::loadFromXML($row['ilr']);
					} catch (Exception $e) {
						throw new Exception($row['ilr']);
					}

					$contract_year = $row['contract_year'];
					$tr_id = $row['tr_id'];
					$submission = $row['submission'];
					$l03 = $row['L03'];
					$contract_id = $row['contract_id'];

					// Is it achieved?
					$a35 = 1;
					$app = false;
					if ($ilr->programmeaim->A10 == "70" || ($ilr->programmeaim->A15 != "99" && $ilr->programmeaim->A15 != "")) {
						$a35 = $ilr->programmeaim->A35;
						$a28 = $ilr->programmeaim->A28;
						$a31 = $ilr->programmeaim->A31;

						if ($ilr->programmeaim->A15 != "99" && $ilr->programmeaim->A15 != "")
							$app = true;
						else
							$app = false;
					} else
						for ($sa = 0; $sa <= (int)$ilr->learnerinformation->subaims; $sa++) {
							$a35 = ($ilr->aims[$sa]->A35 != '1') ? $ilr->aims[$sa]->A35 : $a35;
							$a28 = $ilr->aims[$sa]->A28;
							$a31 = $ilr->aims[$sa]->A31;
						}

					if ($a35 == '1') {
						$a31d = new Date($a31);
						if ($a31d->getDate() >= $contract_start_date->getDate() && $a31d->getDate() <= $contract_end_date->getDate())
							$achieved = true;
					} else
						$achieved = false;

					if ($a31 != '' && $a31 != '00000000' & $a31 != 'dd/mm/yyyy') {
						$a31d = new Date($a31);
						if ($a31d->getDate() >= $contract_start_date->getDate() && $a31d->getDate() <= $contract_end_date->getDate()) {
							$leaver = true;
						}
					} else {
						$leaver = false;
					}


					$a28d = new Date($a28);
					if ($a28d->getDate() >= $contract_start_date->getDate() && $a28d->getDate() <= $contract_end_date->getDate())
						$early_leaver = true;
					else
						$early_leaver = false;

					if ($achieved) {
						$a28 = Date::toMySQL($a28);
						$a31 = Date::toMySQL($a31);
						$days = DAO::getSingleValue($link, "SELECT '$a31' <= DATE_ADD('$a28', INTERVAL 90 DAY)");
						$leaver = true;
					} else {
						$days = 0;
					}

					//Variable setting 
					if ($achieved)
						$achievers++;
					if ($days == 1)
						$timely_achievers++;
					if ($leaver)
						$leavers++;
					if ($early_leaver)
						$timely_leavers++;
				}
			} else {
				throw new DatabaseException($link, $sql);
			}


			// Display previous years values
			$pdf->SetFont('Arial', '', 8);
			$pdf->setXY(150 - ($a * 20), 83);
			$pdf->Cell(23, 5, $achievers, 0, 0, 'R');

			$pdf->setXY(150 - ($a * 20), 88);
			$pdf->Cell(23, 5, $leavers, 0, 0, 'R');

			if ($leavers == 0)
				$overall = $achievers;
			else
				$overall = sprintf("%.1f", $achievers / $leavers * 100);

			$pdf->SetFont('Arial', 'B', 8);
			$pdf->setXY(150 - ($a * 20), 93);
			$pdf->Cell(23, 5, $overall . "%", 0, 0, 'R');

			$pdf->SetFont('Arial', '', 8);
			$pdf->setXY(150 - ($a * 20), 103);
			$pdf->Cell(23, 5, $timely_achievers, 0, 0, 'R');

			$pdf->setXY(150 - ($a * 20), 108);
			$pdf->Cell(23, 5, $timely_leavers, 0, 0, 'R');

			if ($timely_leavers == 0)
				$timely = $timely_achievers;
			else
				$timely = sprintf("%.1f", $timely_achievers / $timely_leavers * 100);

			$pdf->SetFont('Arial', 'B', 8);
			$pdf->setXY(150 - ($a * 20), 113);
			$pdf->Cell(23, 5, $timely . "%", 0, 0, 'R');
		}


		//$pdf->Image("khushnood.png",50,140,150,75);

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
