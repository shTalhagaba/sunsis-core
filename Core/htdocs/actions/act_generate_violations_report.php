<?php
class generate_violations_report implements IAction
{
	public function execute(PDO $link)
	{


		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';

		$contract = Contract::loadFromDatabase($link, $contract_id);
		$contract_holder = ContractHolder::loadFromDatabase($link, $contract->contract_holder);
		$contract_location = DAO::getSingleValue($link, "select description from lookup_contract_locations where id=" . $contract->contract_location);
		$submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");
		$resultSet = DAO::getResultset($link, "SELECT l03, ilr FROM ilr WHERE contract_id = " . $contract_id . " AND submission = 'W" . $submission . "'", DAO::FETCH_ASSOC);

		$ilrs = array();

		foreach($resultSet AS $record)
			$ilrs[] = $record['ilr'];

		$size = count($ilrs) - 1;

		if($size > 5)
		{
			include('./lib/ProgressBar.php');
			$p1 = new ProgressBar();
			$p1->render("Please wait.......");
			$i = 1;
		}

		$validator = new ValidateILR2015();

		$report = "";

		$no_errors = true;
		$report1 = "<table border='1'><thead><tr><th>Learner Reference Number</th><th>Error</th></tr></thead>";
		$report1 .= "<tbody>";

		$errors = array();

		foreach($ilrs AS $ilr)
		{
			if($size > 5)
				$p1->setProgressBarProgress($i * 100 / $size, 'Checking ILR - ' . $i . '/' . $size);

			$ilr_xml = Ilr2015::loadFromXML($ilr);

			$reportFromValidator = $validator->validate($link, $ilr_xml);

			if($reportFromValidator != 'No Error')
			{
				$no_errors = false;
				$reportFromValidator_xml = Ilr2015::loadFromXML($reportFromValidator);



				foreach($reportFromValidator_xml->error AS $error)
				{
					$report1 .= "<tr><td>" . $ilr_xml->LearnRefNumber . "</td><td>" . $error . "</td></tr>";
					//$errors[] = array('l03' => $ilr_xml->LearnRefNumber, 'error' => $error);
				}
			}
			if($size > 5)
				$i++;
		}
		$report1 .= "</tbody>";
		$report1 .= '</table>';

		if($no_errors)
			$report1 = "No Invalid ILR";

		$report = $report . $report1;

		//echo $report;
		//$this->generatePDFReport($report);

		//DAO::multipleRowInsert($link, "tbl_rules_violation", $errors);

		include_once('tpl_generate_violations_report.php');
	}

	private function generatePDFReport($data)
	{
		include("./MPDF57/mpdf.php");

		$mpdf=new mPDF('c');

		$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

		$mpdf->defaultheaderfontsize = 10;	/* in pts */
		$mpdf->defaultheaderfontstyle = B;	/* blank, B, I, or BI */
		$mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

		$mpdf->defaultfooterfontsize = 12;	/* in pts */
		$mpdf->defaultfooterfontstyle = B;	/* blank, B, I, or BI */
		$mpdf->defaultfooterline = 1; 	/* 1 to include line below header/above footer */


		$mpdf->SetHeader('Sunesis|Perspective (UK)');
		//$mpdf->SetFooter('{PAGENO}');	/* defines footer for Odd and Even Pages - placed at Outer margin */
		$f = array (
			'odd' =>
			array (
				'L' =>
				array (
					'content' => 'Prepared at: {DATE H:i:s} on {DATE d/m/Y}',
					'font-size' => 10,
					'font-style' => 'B',
				),
				'C' =>
				array (
					'content' => '{PAGENO} of {nbpg} pages',
					'font-size' => 8,
				),
				'R' =>
				array (
					'content' => "Rules Violation Report",
					'font-size' => 8,
				),
				'line' => 1,
			),
			'even' =>
			array (
				'L' =>
				array (
					'content' => 'Prepared at: {DATE H:i:s} on {DATE d/m/Y}',
					'font-size' => 10,
					'font-style' => 'B',
				),
				'C' =>
				array (
					'content' => '{PAGENO} of {nbpg} pages',
					'font-size' => 8,
				),
				'R' =>
				array (
					'content' => 'Rules Violation Report',
					'font-size' => 8,

				),
				'line' => 1,
			),
		);
		$mpdf->SetFooter($f);

		$mpdf->WriteHTML($data, 2);

		$mpdf->Output('file.pdf','D');
	}
}
?>