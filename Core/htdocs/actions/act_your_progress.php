<?php
class your_progress implements IUnauthenticatedAction
{
	public function execute( PDO $link )
	{
		if(!SystemConfig::getEntityValue($link, 'external_learner_access'))
			pre('Access Denied');
		$javascript_enabled = isset($_REQUEST['javascript']) ? $_REQUEST['javascript'] : '0';
		$username = isset($_POST['username'])?trim($_POST['username']):'';
		$firstnames = isset($_POST['firstnames'])?trim($_POST['firstnames']):'';
		$surname = isset($_POST['surname'])?trim($_POST['surname']):'';
		$dob = isset($_POST['dob'])?$_POST['dob']:'';
		$learner_key = isset($_POST['learner_key'])?$_POST['learner_key']:'';

		$message = isset($_GET['message']) ? $_GET['message'] : '';

		// Clear any current user credentials from the session.
		// Logging a user off then becomes a simple matter of redirecting to this page
		if(isset($_SESSION["username"]))
		{
			$message = "You are logged in as a different user in another window."
				." Please close all browser windows before logging in as new user.";
			require('tpl_your_progress.php');
			return;
		}

		if(!empty($learner_key) && !empty($username)) // User has submitted login data
		{
			$username = $link->quote($username);
			$firstnames = $link->quote($firstnames);
			$surname = $link->quote($surname);
			$dob = $link->quote($dob);
			$learner_key = $link->quote($learner_key);


			$q = <<<QUERY
SELECT COUNT(*) FROM users INNER JOIN tr ON users.username = tr.username WHERE users.username = $username AND users.firstnames = $firstnames AND users.surname = $surname AND users.dob = STR_TO_DATE($dob, '%d/%m/%Y') AND tr.learner_access_key = $learner_key
	;
QUERY;

			$valid_learner = DAO::getSingleValue($link, $q);

			if($valid_learner != 0)
			{

				$tr_id = DAO::getSingleValue($link, "SELECT id FROM tr WHERE tr.firstnames = $firstnames AND tr.surname = $surname AND tr.dob = STR_TO_DATE($dob, '%d/%m/%Y') AND tr.learner_access_key = $learner_key");

				$que = "SELECT courses.framework_id FROM courses INNER JOIN courses_tr ON courses_tr.course_id = courses.id WHERE tr_id='$tr_id'";
				$framework_id = trim(DAO::getSingleValue($link, $que));

				$que = "SELECT SUM(IF(aptitude=1,100,IF(unitsUnderAssessment>100,100,unitsUnderAssessment))/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) FROM student_qualifications WHERE tr_id='$tr_id' AND aptitude != 1";
				$achieved = trim(DAO::getSingleValue($link, $que));

				$this->printLearnerProgressionPDF($link, $tr_id, $framework_id, $achieved);
				session_destroy();
				exit;
			}
			else
			{
				// User credentials unknown
				$message = "We don't have your record.";
				require_once('tpl_your_progress.php');
			} // End: check user's credentials
		}
		else
		{
			// No login details provided
			require_once('tpl_your_progress.php');
		}
	}

	private function printLearnerProgressionPDF(PDO $link, $tr_id, $framework_id, $achieved)
	{
		$system_owner_details = DAO::getResultset($link, "SELECT organisations.legal_name, locations.contact_email FROM organisations INNER JOIN locations ON organisations.id = locations.organisations_id WHERE organisations.organisation_type = 1", DAO::FETCH_ASSOC);
		$system_owner_name = $system_owner_details[0]['legal_name'];
		$system_owner_email = $system_owner_details[0]['contact_email'];

		$training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
		/*if(is_null($training_record->home_email) || $training_record->home_email == '')
		{
			throw new Exception('We don\'t have your email address, please contact ' . $system_owner_name);
		}*/

		$framework_title = DAO::getSingleValue($link, "SELECT title FROM frameworks WHERE id = " . $framework_id);
		$training_start_date = Date::to($training_record->start_date, Date::SHORT);
		$training_planned_end_date = Date::to($training_record->target_date, Date::SHORT);

		$induction_booklet = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_events WHERE tr_id = " . $training_record->id . " AND event_id = (SELECT id FROM events_template WHERE TRIM(title) = 'Induction Booklet' AND provider_id = " . $training_record->provider_id . " ); ");
		$edims_booklet = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_events WHERE tr_id = " . $training_record->id . " AND event_id = (SELECT id FROM events_template WHERE TRIM(title) = 'EDIMs Booklet' AND provider_id = " . $training_record->provider_id . " ); ");
		$h_and_s_booklet = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_events WHERE tr_id = " . $training_record->id . " AND event_id = (SELECT id FROM events_template WHERE TRIM(title) = 'H&S Booklet' AND provider_id = " . $training_record->provider_id . " ); ");

		$induction_booklet = $induction_booklet == 1?'Yes':'No';
		$edims_booklet = $edims_booklet == 1?'Yes':'No';
		$h_and_s_booklet = $h_and_s_booklet == 1?'Yes':'No';

		$achieved = ($achieved=='')?0:sprintf("%.1f",$achieved);

		$progress_bar_color = "green";
		if($achieved == '0.0' || $achieved == '0')
			$progress_bar_color = "white";

		$achieved_progress_bar = <<<HEREDOC
			<table><tr><td bgcolor="#f5f9ee" class="fieldLabel">% Achieved: </td><td class="fieldValue">$achieved %</td></tr></table>
			<div style="width: 50%; border: 1px solid black; border-radius: 5px; position: relative; padding: 3px;">
				<div style="height: 20px; border-radius: 15px; width: $achieved%; background-color: $progress_bar_color;"></div>
			</div>
HEREDOC;

		include_once("./MPDF57/mpdf.php");

		$mpdf=new mPDF('','Legal-L','10');

		//Beginning Buffer to save PHP variables and HTML tags
		ob_start();

		// LOAD a stylesheet
		//$stylesheet = file_get_contents('common.css');
		//$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

		$client_logo = SystemConfig::getEntityValue($link, "logo");
		$client_logo = $client_logo ? $client_logo : 'perspective.png';
		$client_logo_path = "./images/logos/" . $client_logo;

		$html = <<<HTML
<div style="float: right; width: 30%;"><img src="$client_logo_path" alt=""></div>
<div>
	<table border="1" style="width: 100%;" cellspacing="0" cellpadding="5">
		<col width="10%"/>
		<tr><td bgcolor="#f5f9ee" class="fieldLabel">Learner Name:</td><td class="fieldValue" colspan="3">$training_record->firstnames $training_record->surname</td></tr>
		<tr><td bgcolor="#f5f9ee" class="fieldLabel">Framework Name:</td><td class="fieldValue" colspan="3" style="font-size: 10px;">$framework_title</td></tr>
		<tr><td bgcolor="#f5f9ee" class="fieldLabel">Start Date:</td><td class="fieldValue">$training_start_date</td><td bgcolor="#f5f9ee" class="fieldLabel" width="20%">Planned End Date:</td><td class="fieldValue">$training_planned_end_date</td></tr>
	</table>

	<table border="1" width="100%" cellspacing="0" cellpadding="6">
		<tr><th bgcolor="#f5f9ee">Induction Booklet</th><th bgcolor="#f5f9ee">EDIMs Booklet</th><th bgcolor="#f5f9ee">H&S Booklet</th></tr>
		<tbody><tr><td class="fieldValue" align="center">$induction_booklet</td><td class="fieldValue" align="center">$edims_booklet</td><td class="fieldValue" align="center">$h_and_s_booklet</td></tr></tbody>
	</table>
</div>

<div>
	<h4>Framework / Qualification Progress</h4>
	$achieved_progress_bar
</div>
<br>
<div>
	<table border="1" width="100%" cellspacing="0" cellpadding="5">

HTML;

		$s_quals = array();

		$qualifications = DAO::getResultset($link, "SELECT * FROM student_qualifications WHERE tr_id = " . $tr_id, DAO::FETCH_ASSOC);
		foreach ($qualifications AS $qualification)
		{
			$stdClass = new stdClass();

			$stdClass->qualification_id = $qualification['id'];
			$stdClass->qualification_title = $qualification['internaltitle'];

			$i = 1;
			$evidence = XML::loadSimpleXML($qualification['evidences']);
			$units = $evidence->xpath('//unit[@chosen=\'true\']');

			$s_qual_units = array();

			foreach ($units AS $unit)
			{
				//if($i > 25)
				//	break;
				$temp = (array)$unit->attributes();
				$temp = $temp['@attributes'];
				$temp['reference'] = str_replace('/','', $temp['reference']);
				if($temp['chosen'] == 'true')
				{
					$s_qual_units[$temp['owner_reference']] = round($temp['percentage']);
				}
				$i++;
			}

			$stdClass->progress = $s_qual_units;
			$s_quals[] = $stdClass;
		}

		foreach($s_quals AS $s_qual)
		{
			$total_number_of_chose_units_in_this_qual = count($s_qual->progress);
			$quotient = floor($total_number_of_chose_units_in_this_qual / 25);
			$excess = $total_number_of_chose_units_in_this_qual % 25;

			$offset = 0;
			$offsett = 0;

			$i = 1;
			$ii = 1;

			while($quotient > 0)
			{
				//echo $quotient . '= quotient<br>';
				$html .= '<tr><th style="width: 5%; " bgcolor="#f5f9ee">Qual Ref.</th><th style="width: 25%; " bgcolor="#f5f9ee">Qualification Title</th>';
				foreach($s_qual->progress AS $key => $value)
				{
					//echo $i . '=i<br>';
					//echo $offset . '=offset<br>';
					if($i > $offset + 25)
					{
						$offset = $i;
						break;
					}

					if($i <= $offset)
					{
						$i++;
						continue;
					}

					$html .= '<th bgcolor="#f5f9ee" style="width: 4%; ">' . $key . '</th>';
					$i++;
				}
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td class="fieldValue">' . $s_qual->qualification_id . '</td>';
				$html .= '<td class="fieldValue">' . $s_qual->qualification_title . '</td>';
				foreach($s_qual->progress AS $key => $value)
				{
					if($ii > $offsett + 25)
					{
						$offsett = $ii;
						break;
					}

					if($ii <= $offsett)
					{
						$ii++;
						continue;
					}
					if($value == '100.00')
						$html .= '<td class="fieldValue" bgcolor="lightgreen">' . $value . '%</td>';
					else
						$html .= '<td class="fieldValue" >' . $value . '%</td>';
					$ii++;
				}
				$html .= '</tr>';
				$quotient--;
			}

			if($excess > 0)
			{
				$html .= '<tr><th style="width: 5%; " bgcolor="#f5f9ee">Qual Ref.</th><th style="width: 25%; " bgcolor="#f5f9ee">Qualification Title</th>';

				$again_i = 0;
				$again_ii = 0;
				foreach($s_qual->progress AS $key => $value)
				{
					$again_i++;
					if($again_i < $offset)
						continue;
					$html .= '<th bgcolor="#f5f9ee" style="width: 5%; ">' . $key . '</th>';
				}

				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td class="fieldValue">' . $s_qual->qualification_id . '</td>';
				$html .= '<td class="fieldValue">' . $s_qual->qualification_title . '</td>';

				foreach($s_qual->progress AS $key => $value)
				{
					$again_ii++;
					if($again_ii < $offset)
						continue;
					if($value == '100.00')
						$html .= '<td class="fieldValue" bgcolor="lightgreen">' . $value . '%</td>';
					else
						$html .= '<td class="fieldValue" >' . $value . '%</td>';
				}
				$html .= '</tr>';
			}


		}




		$html .= "</table>";
		$html .= "<div align='center' style='font-size:70%;'>* to be updated on the 15th of each month.</div>";
		$html .= "</div>";

		//echo $html;
		//pre('here');
		$print_off_date = '{DATE j/m/Y H:i}';
		$print_off_date = date('d/m/Y H:i');
		$sunesis_stamp = md5('ghost'.date('d/m/Y H:i'));
		$footer = <<<FOOTER
			<table><tr><td style="font-size:70%;">$print_off_date</td></tr><tr><td style="font-size:70%;">$sunesis_stamp</td></tr></table>
FOOTER;

		$html .= $footer;
		echo $html;
		//exit;
		$file_name = 'Progress Report - ' . $training_record->firstnames . ' ' . $training_record->surname . '.pdf';

		$html = ob_get_contents();
		ob_end_clean();

		$mpdf->WriteHTML($html);

		//$mpdf->SetHTMLFooter($footer);

//		$mpdf->Output($file_name, 'I');
		$content = $mpdf->Output('', 'S');

		$content = chunk_split(base64_encode($content));
		$mailto = $training_record->home_email; //Mailto here
		$from_name = $system_owner_name; //Name of sender mail
		$from_mail = $system_owner_email; //Mailfrom here
		if(DB_NAME=="am_crackerjack")
			$from_mail = 'Laura.Prosser@crackerjacktraining.com'; //Mailfrom here
		$subject = 'Progress Report - ' . $training_record->firstnames . ' ' . $training_record->surname;
		$message = 'Dear ' . $training_record->firstnames . ' ' . $training_record->surname . ',<br><br>';
		$message .= 'Please find an attached progress report.<br><br>';
		if(DB_NAME=="am_crackerjack")
		{
			$message .= 'Kind Regards<br>';
			$message .= 'Laura Prosser<br>';
			$message .= 'Office Manager<br>';
			$message .= 'Crackerjack Training<br>';
			$message .= '0121 454 2043<br>';
			$message .= 'www.crackerjacktraining.co.uk<br>';
		}
		$filename = $file_name; //Your Filename with local date and time

		//Headers of PDF and e-mail
		$boundary = "XYZ-" . date("dmYis") . "-ZYX";

		$header = "--$boundary\r\n";
		$header .= "Content-Transfer-Encoding: 8bits\r\n";
		$header .= "Content-Type: text/html; charset=ISO-8859-1\r\n\r\n"; // or utf-8
		$header .= "$message\r\n";
		$header .= "--$boundary\r\n";
		$header .= "Content-Type: application/pdf; name=\"".$filename."\"\r\n";
		$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n";
		$header .= "Content-Transfer-Encoding: base64\r\n\r\n";
		$header .= "$content\r\n";
		$header .= "--$boundary--\r\n";

		$header2 = "MIME-Version: 1.0\r\n";
		$header2 .= "From: $from_name <$from_mail>\r\n";
		//$header2 .= "Return-Path: $from_mail\r\n";
		$header2 .= "Reply-To: $from_mail\r\n";
		$header2 .= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";
		$header2 .= "$boundary\r\n";

		if(!is_null($training_record->home_email) && $training_record->home_email != '')
			mail($mailto,$subject,$header,$header2, "-r".$from_mail);

		$mpdf->Output($filename ,'I');

		exit;
	}
}
	