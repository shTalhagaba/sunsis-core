<?php
class baltic_read_employers_pool_emp implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$auto_id = isset($_GET['auto_id']) ? $_GET['auto_id'] : '';
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		if(!$auto_id)
		{
			if(!$id)
				throw new Exception("Missing or empty querystring argument 'Employer Record ID'");
			else
				$auto_id = $id;
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

		$viewProspectCRMContacts = ViewProspectCRMContacts::getInstance($link, $auto_id);
		$viewProspectCRMContacts->refresh($link, $_REQUEST);

		$org_contacts_calender_events_notes = $this->renderCalenderEventNotes($link, $auto_id);

		$exists = DAO::getSingleValue($link, "select count(*) from organisations where zone = '".$vo->dpn."'");

		$contacts = $vo->getContacts($link, $auto_id);
		$contact_emails = "";
		foreach($contacts AS $contact)
		{
			$str = explode("*", $contact[0]);
			if(isset($str[0]) && $str[0] != '')
				$contact_emails .= $str[0] . ",";
		}

		$contact_emails = rtrim($contact_emails, ",");

		$urls2 = Array();
		$directories = Array();
		$path = DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/" . $vo->auto_id;
		if(file_exists(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/" . $vo->auto_id . "/"))
		{
			$TrackDir=opendir(DATA_ROOT . "/uploads/" . DB_NAME . "/recruitment/" . $vo->auto_id . "/");
			$n2 = 0;
			$directories = Array();
			$sections = "<select name='section' id='sections'><option value=''></option>";
			$html2 = '<table class="resultset" border="0" cellspacing="0" cellpadding="6"><thead><tr>';
			if($_SESSION['user']->isAdmin())
				$html2 .= "<th>Delete</th>";
			$html2 .= '<th>Filename</th><th>Size</th><th>Upload Date</th></tr></thead>';
			while ($file = readdir($TrackDir))
			{
				$full = $path."/" . $file;
				if(!is_dir($full))
				{
					if ($file != "." && $file != ".." && $file!='admin')
					{
						$n2++;
						if(isset($urls2[$n2]))
						{
							$urls2[$no] .=  '<a href="do.php?_action=downloader&path=recruitment/' . $vo->auto_id . '/' . "&f=" . rawurlencode($file) . '"><br>'  .$file . '</a>';
							$href2 = "do.php?_action=delete_file&path=recruitment/" . $vo->auto_id . '/' . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=baltic_read_employers_pool_emp&auto_id='.$vo->auto_id);
							$html2 .= "<tr>";
							$html2 .= "<td><a href=$href2><img src='/images/delete.gif'></img></a></td>";
							$html2 .= "<td>" . $urls2[$n2] . "</td></tr>";
							die($urls2[$n2]);
						}
						else
						{
							$urls2[$n2] = '<a href="do.php?_action=downloader&path=recruitment/' . $vo->auto_id . '/' . "&f=" . rawurlencode($file) . '">' .$file . '</a>';
							$href2 = "do.php?_action=delete_file&path=recruitment/" . $vo->auto_id . '/' . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=baltic_read_employers_pool_emp&auto_id='.$vo->auto_id);
							$href3 = 'if(confirm("Are you sure?"))window.location.href="' . $href2 . '"';
							$html2 .= "<tr>";
							if($_SESSION['user']->isAdmin())
								$html2 .= "<td align=center><img style='cursor: pointer' onclick='$href3' src='/images/delete.gif'></img></td>";
							$html2 .= "<td>" . $urls2[$n2] . "</td><td align=right>". $this->format_size(filesize($full)) ."</td><td>" . date("d F Y H:i:s", filemtime($full)) . "</td></tr>";
						}
					}
				}
				else
				{
					if($file!="." && $file!=".." && $file!=".svn" && (substr($file,0,8)=="section_"))
					{
						$directories[] = $file;
						$sections .= "<option value='" . substr($file,8) . "'>" . substr($file,8) . "</option>";
					}
				}
			}
			$sections .= "</select>";
			$html2 .= '</table>';

			closedir($TrackDir);
		}

		$contacts = ViewEmployerContacts::getInstance($link, $auto_id);

		$usersWithDeletePermissions = DAO::getSingleColumn($link, "SELECT username FROM lookup_users_with_candidate_delete_permissions");

		// Presentation
		include('tpl_baltic_read_employers_pool_emp.php');
	}

	private function format_size($size)
	{
		$sizes = array(" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		if($size == 0)
		{
			return('n/a');
		}
		else
		{
			$i = 0;
			$s = $size;
			while($size > 1024){
				$size = $size/1024;
				$i++;
			}
			return sprintf("%.1f" . $sizes[$i], $size);
			//return sprintf("%.1f",($size/pow(1024, ($i = floor(log($size, 1024))))) . $sizes[$i]);
		}
	}

	private function renderCalenderEventNotes(PDO $link, $organisation_id)
	{
		$htmlOutput = "";
		$result = $link->query("SELECT * FROM emp_pool_calendar_events_notes WHERE org_id = " . $organisation_id);
		if($result)
		{
			$htmlOutput = '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="5">';
			$htmlOutput .= '<thead height="40px"><tr><th>&nbsp;</th><th>Contact Name</th><th>Contact Email</th><th>Invitation Sent By</th><th>Start Date & Time</th><th>End Date & Time</th><th>Location</th><th>Subject</th><th>Description</th><th>Cancelled</th></tr></thead>';
			$htmlOutput .= '<tbody>';
			while($row = $result->fetch())
			{
				if($row['status'] != 'CANCELLED')
					$htmlOutput .= HTML::viewrow_opening_tag('/do.php?_action=baltic_send_emp_pool_cal_event&id=' . $row['id'] . '&auto_id=' . $row['org_id']);

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

}
?>