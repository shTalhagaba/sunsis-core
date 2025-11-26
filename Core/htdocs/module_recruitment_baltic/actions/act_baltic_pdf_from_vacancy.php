<?php
class baltic_pdf_from_vacancy implements IAction {

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

		$vacancy_id = isset($_REQUEST['vacancy_id']) ? $_REQUEST['vacancy_id'] : '';

		$pdf = new FPDI();

		/**
		 * re 28/10/2011 if we have a soap / destiny enabled system
		 * check to see if we have the actual filled in destiny form
		 * naming convention:
		 *   /uploads/<system_name>/<username>/destiny-ILRV2.pdf
		 *   this is disabled as not required functionality
		 */
		if(DB_NAME=="am_baltic")
			$pagecount = $pdf->setSourceFile('BalticVacancyForm.pdf');
		else
			$pagecount = $pdf->setSourceFile('DemoVacancyForm.pdf');

		$tpl=$pdf->ImportPage(1);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['w'], $s['h']));
		$pdf->useTemplate($tpl);

		$pdf->SetFont('Arial', '', 10);

		$vo = Vacancy::loadFromDatabase($link, $vacancy_id);


		$pdf->Text(100, 25, $vo->job_title);
		$pdf->SetFont('Arial', '', 8);
		if(isset($vo->employer_id) && $vo->employer_id != '')
			$pdf->Text(73, 34, DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $vo->employer_id));
		if(isset($vo->type) && $vo->type != '')
			$pdf->Text(73, 43, DAO::getSingleValue($link, "SELECT description FROM lookup_vacancy_type WHERE id = " . $vo->type));
		if(isset($vo->apprenticeship_type) && $vo->apprenticeship_type != '')
			$pdf->Text(73, 53, DAO::getSingleValue($link, "SELECT RIGHT(description, 7) FROM lookup_vacancy_app_type WHERE id = " . $vo->apprenticeship_type));
		$pdf->SetFont('Arial', '', 6);
		$pdf->Text(107, 53, $vo->code);
		$pdf->SetFont('Arial', '', 10);
		$delivery_location = DAO::getResultset($link, "SELECT full_name, address_line_1, address_line_2, address_line_3, address_line_4, postcode FROM locations WHERE id = " . $vo->location);
		$pdf->Text(145, 35, $delivery_location[0][0]);
		$pdf->Text(145, 40, $delivery_location[0][1]);
		$pdf->Text(145, 45, $delivery_location[0][2]);
		$pdf->Text(145, 50, $delivery_location[0][3]);
		$pdf->Text(145, 55, $delivery_location[0][4]);
		$pdf->Text(145, 60, $delivery_location[0][5]);

		$pdf->SetFont('Arial', '', 8);
		$pdf->SetXY(13, 150);
		$pdf->MultiCell(0,4,$vo->description,0,'L',false);

		$pdf->Text(39, 217, $vo->salary);
		$pdf->Text(85, 217, $vo->hrs_per_week);
		$pdf->Text(130, 217, $vo->shift_pattern);

		$pdf->SetXY(13, 230);
		$pdf->MultiCell(0,4,$vo->skills_req,0,'L',false);

		$tpl=$pdf->ImportPage(2);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['w'], $s['h']));
		$pdf->useTemplate($tpl);

		$pdf->SetXY(13, 23);
		$pdf->MultiCell(0,4,$vo->training_provided,0,'L',false);

		$pdf->SetXY(13, 82);
		$pdf->MultiCell(0,4,$vo->required_quals,0,'L',false);

		$pdf->SetXY(13, 129);
		$pdf->MultiCell(0,4,$vo->person_spec,0,'L',false);

		$pdf->SetXY(13, 172);
		$vo->future_prospects = preg_replace( "/\r|\n/", "", $vo->future_prospects);
		$pdf->MultiCell(0,4,$vo->future_prospects,0,'L',false);

		echo $pdf->Output();
	}

	function getSettings($string, $limit, $required)
	{
		$description_length = strlen ($string);
		$loop = $description_length/80;
		$loop = (string) $loop;
		$loop = explode('.',$loop);
		$loop_counter = $loop[0];
		$remaining = $description_length % $limit;

		if($required == 'quotient')
			return $loop_counter;
		elseif($required == 'remainder')
			return $remaining;

	}


}
?>