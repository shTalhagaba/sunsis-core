<?php
class baltic_read_candidate implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$candidate_id = isset($_GET['candidate_id']) ? $_GET['candidate_id'] : '';
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$export = isset($_GET['export']) ? $_GET['export'] : '';

		if(!$candidate_id)
		{
			if(!$id)
				throw new Exception("Missing or empty querystring argument 'candidate id'");
			else
				$candidate_id = $id;
		}

		$_SESSION['bc']->add($link, "do.php?_action=read_candidate&candidate_id=".$candidate_id, "View Candidate");



		// Create Value Object
		if ($candidate_id)
		{
			$vo = Candidate::loadFromDatabase($link, $candidate_id);
			if (is_null($vo))
			{
				throw new Exception("No user with id '$candidate_id'");
			}
			$latest_email_date_time = DAO::getSingleValue($link, "SELECT CONCAT(date_sent, '*', time_sent) AS dateTime FROM candidate_email_notes WHERE candidate_id = $candidate_id AND sent_from_sunesis = 0 ORDER BY date_sent DESC, time_sent DESC LIMIT 1");
			$latest_email_date = '';
			$latest_email_time = '';
			if(isset($latest_email_date_time) AND $latest_email_date_time != '')
			{
				$latest_email_date_time = explode('*', $latest_email_date_time);
				$latest_email_date = $latest_email_date_time[0];
				$latest_email_time = $latest_email_date_time[1];
			}
			$candidate_extra_info = CandidateExtraInfo::loadFromDatabase($link, $candidate_id);
		}

		if($export == 'pdf')
		{
			$this->exportToPDF($link, $vo);
			exit;
		}

		$view_candidate_crm = ViewCandidateCRM::getInstance($link, $candidate_id);
		$view_candidate_crm->refresh($link, $_REQUEST);

		$view_candidate_emails = ViewCandidateEmail::getInstance($link, $candidate_id);
		$view_candidate_emails->refresh($link, $_REQUEST);

		$candidate_calender_events_notes = $this->renderCalenderEventNotes($link, $candidate_id);

		// Learner photo
		$photopath = $vo->getPhotoPath();
		if($photopath)
		{
			$photopath = "do.php?_action=display_image&username=".rawurlencode($vo->username)."&candidate_id=".$candidate_id;
		}
		else
		{
			$photopath = "/images/no_photo.png";
		}

		$exists_as_sunesis_learner = DAO::getSingleValue($link, "SELECT IF(username IS NOT NULL AND STATUS != 0, 'Yes', 'No') AS student FROM candidate WHERE id = " . $candidate_id);
		$pre_assessment_dropdown = DAO::getResultset($link,"SELECT id, description, null from lookup_pre_assessment;");

		$usersWithDeletePermissions = DAO::getSingleColumn($link, "SELECT username FROM lookup_users_with_candidate_delete_permissions");

		// Presentation
		include('tpl_baltic_read_candidate.php');
	}

	private function renderCalenderEventNotes(PDO $link, $candidate_id)
	{
		$htmlOutput = "";
		$result = $link->query("SELECT * FROM candidate_calendar_events_notes WHERE candidate_id = " . $candidate_id);
		if($result)
		{
			$htmlOutput = '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="5">';
			$htmlOutput .= '<thead height="40px"><tr><th>&nbsp;</th><th>Candidate Name</th><th>Candidate Email</th><th>Invitation Sent By</th><th>Start Date & Time</th><th>End Date & Time</th><th>Location</th><th>Subject</th><th>Description</th><th>Cancelled</th></tr></thead>';
			$htmlOutput .= '<tbody>';
			while($row = $result->fetch())
			{
				if($row['status'] != 'CANCELLED')
					$htmlOutput .= HTML::viewrow_opening_tag('/do.php?_action=baltic_send_candidate_cal_event&id=' . $row['id'] . '&candidate_id=' . $row['candidate_id']);

				$htmlOutput .= "<td><img src='/images/bell.JPG' border='0' width='30' height='30' /></td>";
				$htmlOutput .= "<td>" . $row['candidate_name'] . "</td>";
				$htmlOutput .= "<td>" . $row['candidate_email'] . "</td>";
				$htmlOutput .= "<td>" . $row['sender_name'] . " (" . $row['sender_email'] . ")</td>";
				$htmlOutput .= "<td>" . Date::to($row['start_date'] . ' ' . $row['start_time'], Date::DATETIME) . "</td>";
				$htmlOutput .= "<td>" . Date::to($row['end_date'] . ' ' . $row['end_time'], Date::DATETIME) . "</td>";
				$htmlOutput .= "<td>" . $row['location'] . "</td>";
				$htmlOutput .= "<td>" . $row['subject'] . "</td>";
				$row['description'] = html_entity_decode($row['description']);
				$row['description'] = strip_tags($row['description']);
				$htmlOutput .= "<td>" . $row['description'] . "</td>";
				if($row['status'] == 'CANCELLED')
					$htmlOutput .= "<td>YES</td>";
				else
					$htmlOutput .= "<td>NO</td>";
				$htmlOutput .= "</tr>";
			}
			$htmlOutput .= '</tbody>';
			$htmlOutput .= '</table>';
			$htmlOutput .= '</div>';
		}
		else
		{
			$htmlOutput = '<div align="left">NO RECORD FOUND</div>';
		}
		return $htmlOutput;
	}

	private function exportToPDF(PDO $link, Candidate $vo)
	{

		include("./MPDF57/mpdf.php");

		$mpdf=new mPDF('','','','',15,15,47,16,9,9);
		// LOAD a stylesheet
		$stylesheet = file_get_contents('common.css');
		$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text


		$html = '';
		$dob = htmlspecialchars(Date::toMedium($vo->dob));
		if ($vo->dob)
			$age = '<span style="margin-left:30px;color:gray">(' . Date::dateDiff(date("Y-m-d"), $vo->dob) . ')</span>';
		$vo->gender = DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id='{$vo->gender}';");
		$vo->ethnicity = DAO::getSingleValue($link, "SELECT description FROM lookup_country_list WHERE code='{$vo->ethnicity}';");
		$candidate_address = $vo->displayCandidateAddresses($link);
		$candidate_qualifications = $vo->render_candidate_qualifications($link, false);
		$candidate_employment = $vo->render_candidate_employment($link, false);
		$candidate_applications = $vo->render_candidate_applications($link, false);

		$html = <<<HEREDOC
		<div>
			<h3>Personal Details</h3>
			<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px">
				<col width="190"/>
				<col width="380"/>
				<tr>
					<td class="fieldLabel">Firstnames</td>
					<td class="fieldValue">$vo->firstnames</td>
				</tr>
				<tr>
					<td class="fieldLabel">Surname</td>
					<td class="fieldValue">$vo->surname</td>
				</tr>

				<tr>
					<td class="fieldLabel">Date of birth</td>
					<td class="fieldValue">$dob $age</td>
				</tr>
				<tr>
					<td class="fieldLabel">Email</td>
					<td class="fieldValue">$vo->email</td>
				</tr>
				<tr>
					<td class="fieldLabel">National Insurance</td>
					<td class="fieldValue">$vo->national_insurance</td>
				</tr>
				<tr>
					<td class="fieldLabel">Gender</td>
					<td class="fieldValue">$vo->gender</td>
				</tr>
				<tr>
					<td class="fieldLabel">Ethnicity</td>
					<td class="fieldValue">$vo->ethnicity</td>
				</tr>
			</table>
		</div>
		$candidate_address
		$candidate_qualifications
		$candidate_employment
		$candidate_applications

HEREDOC;

		$mpdf->SetHTMLFooter('<div align="center"><span style="font-size: 10px;">Baltic Training Services Ltd<br>Tel: 01325 731 050<br>F: 01325 317 156<br>E: yourfuture@baltictraining.com</span></div>');
		$mpdf->SetHTMLHeader("<div align='center'><img src='./images/logos/baltic.png' alt='Baltic Training Services'></div>  ");
		$mpdf->WriteHTML($html);




		$mpdf->Output();

		exit;

	}

}
?>