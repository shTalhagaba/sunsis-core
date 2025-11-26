<?php
class print_appointment implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$appointment_id = isset($_REQUEST['appointment_id']) ? $_REQUEST['appointment_id'] : '';

		$appointment = Appointment::loadFromDatabase($link, $appointment_id);
		$training_record = TrainingRecord::loadFromDatabase($link, $tr_id);

		include("./MPDF57/mpdf.php");

		$html = "<h3>Learner</h3>";
		$html .= "<table  border='0' style='margin-left:10px' cellspacing='4' cellpadding='4'>";
		$html .= "<tr> <td class='fieldLabel'>Name: </td> <td  class='fieldValue'>" . $training_record->firstnames .' ' . $training_record->surname . "</td></tr>";
		$html .= "</table>";
		$html .= "<h3>Appointment Details</h3>";
		$html .= "<table  border='0' style='margin-left:10px' cellspacing='4' cellpadding='4'>";
		$html .= "<tr> <td class='fieldLabel'>Creation Time</td> <td  class='fieldValue'>" . $appointment->created . "</td></tr>";
		$html .= "<tr> <td class='fieldLabel'>Date</td> <td  class='fieldValue'>" . Date::toMedium($appointment->appointment_date) . "</td></tr>";
		$html .= "<tr> <td class='fieldLabel'>Start Time</td> <td  class='fieldValue'>" . $appointment->appointment_start_time . "</td></tr>";
		$html .= "<tr> <td class='fieldLabel'>End Time</td> <td  class='fieldValue'>" . $appointment->appointment_end_time . "</td></tr>";
		if($appointment->appointment_type != '')
			$html .= "<tr> <td class='fieldLabel'>Type</td> <td  class='fieldValue'>" . DAO::getSingleValue($link, "SELECT description FROM lookup_appointment_types WHERE id = " . $appointment->appointment_type) . "</td></tr>";
		if($appointment->interviewer != '')
			$html .= "<tr> <td class='fieldLabel'>Assessor / Interviewer</td> <td  class='fieldValue'>" . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = " . $appointment->interviewer) . "</td></tr>";
		if($appointment->appointment_status != '')
			$html .= "<tr> <td class='fieldLabel'>Type</td> <td  class='fieldValue'>" . DAO::getSingleValue($link, "SELECT description FROM lookup_appointment_status WHERE id = " . $appointment->appointment_status) . "</td></tr>";
		switch($appointment->appointment_rgb_status)
		{
			case 'green':
				$html .= "<tr> <td class='fieldLabel'>GYR</td> <td  class='fieldValue'>Green <img src='/images/trafficlight-green.jpg' alt='Green'></td></tr>";
				break;
			case 'yellow':
				$html .= "<tr> <td class='fieldLabel'>GYR</td> <td  class='fieldValue'>Yellow <img src='/images/trafficlight-yellow.jpg' alt='Yellow'></td></tr>";
				break;
			case 'red':
				$html .= "<tr> <td class='fieldLabel'>GYR</td> <td  class='fieldValue'>Red <img src='trafficlight-red.jpg' alt='Red'></td></tr>";
				break;
			default:

				break;
		}
		if($appointment->appointment_paperwork != '')
			$html .= "<tr> <td class='fieldLabel'>Paperwork</td> <td  class='fieldValue'>" . DAO::getSingleValue($link, "SELECT description FROM lookup_appointment_paperwork WHERE id = " . $appointment->appointment_paperwork) . "</td></tr>";
		$html .= "<tr> <td class='fieldLabel'>Comments</td> <td  class='fieldValue'>" . $appointment->appointment_comments . "</td></tr>";
		$html .= "</table>";

		$mpdf=new mPDF('c');
		$mpdf->SetDisplayMode('fullpage');

		// LOAD a stylesheet
		$stylesheet = file_get_contents('./common.css');
		$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

//		$mpdf->SetHTMLHeader("<div align='right'><img src='./images/vesa.jpg' alt='./images/vesa.jpg'></div>  ");
		$mpdf->WriteHTML($html);
//		$mpdf->SetHTMLFooter('<div align="center"><span style="font-size: 10px;">Leicester VESA<br>Knighton Fields Centre, Herrick Road, Leicester, LE2 6DH<br>Tel: 0116-2707942</span></div>');
		$mpdf->Output();

	}

}