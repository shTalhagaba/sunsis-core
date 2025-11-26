<?php
class read_employers_pool_emp implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$auto_id = isset($_GET['auto_id']) ? $_GET['auto_id'] : '';

		if(!$auto_id)
		{
			throw new Exception("Missing or empty querystring argument 'Employer Record ID'");
		}

		$_SESSION['bc']->add($link, "do.php?_action=read_employers_pool_emp&auto_id=".$auto_id, "View Employer Pool Record");


		// Create Value Object
		if ($auto_id)
		{
			$vo = EmployerPool::loadFromDatabase($link, $auto_id);
			if (is_null($vo))
			{
				throw new Exception("No Employer with id '$auto_id'");
			}
		}

		$view_employer_pool_crm = ViewEmployerPoolNotes::getInstance($link, $auto_id);
		$view_employer_pool_crm->refresh($link, $_REQUEST);

		$view_employer_pool_emails = ViewEmployerPoolContactEmails::getInstance($link, $auto_id);
		$view_employer_pool_emails->refresh($link, $_REQUEST);

		$org_contacts_calender_events_notes = $this->renderCalenderEventNotes($link, $auto_id);

		$exists = DAO::getSingleValue($link, "select count(*) from organisations where zone = '".$vo->dpn."'");

		// Presentation
		include('tpl_read_employers_pool_emp.php');
	}

	private function renderCalenderEventNotes(PDO $link, $organisation_id)
	{
		$htmlOutput = "";
		$result = $link->query("SELECT * FROM emp_pool_calendar_events_notes WHERE org_id = " . $organisation_id);
		if($result)
		{
			$htmlOutput = '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="5">';
			$htmlOutput .= '<thead height="40px"><tr><th>&nbsp;</th><th>Contact Name</th><th>Contact Email</th><th>Invitation Sent By</th><th>Start Date & Time</th><th>End Date & Time</th><th>Location</th><th>Subject</th><th>Description</th></tr></thead>';
			$htmlOutput .= '<tbody>';
			while($row = $result->fetch())
			{
				$htmlOutput .= "<tr>";
				$htmlOutput .= "<td><img src='/images/bell.JPG' border='0' width='30' height='30' /></td>";
				$htmlOutput .= "<td>" . $row['contact_name'] . "</td>";
				$htmlOutput .= "<td>" . $row['contact_email'] . "</td>";
				$htmlOutput .= "<td>" . $row['sender_name'] . " (" . $row['sender_email'] . ")</td>";
				$htmlOutput .= "<td>" . Date::to($row['start_date'] . ' ' . $row['start_time'], Date::DATETIME) . "</td>";
				$htmlOutput .= "<td>" . Date::to($row['end_date'] . ' ' . $row['end_time'], Date::DATETIME) . "</td>";
				$htmlOutput .= "<td>" . $row['location'] . "</td>";
				$htmlOutput .= "<td>" . $row['subject'] . "</td>";
				$row['description'] = html_entity_decode($row['description']);
				$row['description'] = strip_tags($row['description']);
				$htmlOutput .= "<td>" . $row['description'] . "</td>";
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

}
?>