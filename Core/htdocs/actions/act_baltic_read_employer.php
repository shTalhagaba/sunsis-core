<?php
class baltic_read_employer implements IAction
{
	public function execute(PDO $link)
	{
		if(SOURCE_LOCAL)
			http_redirect('do.php?_action=read_employer_v3&id='.$_REQUEST['id']);

		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$emp_group_id = isset($_GET['emp_group_id']) ? $_GET['emp_group_id'] : '';
		$subaction = isset($_GET['subaction']) ? $_GET['subaction'] : '';

		if($subaction == 'deleteOrganisationCRMContact')
		{
			echo $this->deleteOrganisationCRMContact($link);
			exit;
		}

		$_SESSION['bc']->add($link, "do.php?_action=read_employer&id=" . $id . "&emp_group_id=" . $emp_group_id, "View Employer");

		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$vo = Employer::loadFromDatabase($link, $id);
		$isSafeToDelete = $vo->isSafeToDelete($link);

		// Page title
		if($vo->id == 0)
		{
			$page_title = "New Employer";
		}
		elseif(strlen($vo->trading_name) > 50)
		{
			$page_title = substr($vo->trading_name, 0, 50).'...';
		}
		else
		{
			$page_title = $vo->trading_name;
		}

		$type_checkboxes = "SELECT id, CONCAT(id, ' - ', org_type), null FROM lookup_org_type ORDER BY id;";
		$type_checkboxes = DAO::getResultset($link, $type_checkboxes, DAO::FETCH_NUM, "read employer type checkboxes");

		$sector = DAO::getSingleValue($link, "select description from lookup_sector_types where id = '$vo->sector'");

		$group_employer = DAO::getSingleValue($link, "select title from brands where id = '$vo->manufacturer'");

		$view2 = ViewCrmNotes::getInstance($link, $id);
		$view2->refresh($link, $_REQUEST);

		$vo3 = ViewOrganisationLearners::getInstance($link, $id);
		$vo3->refresh($link, $_REQUEST);
				$learnerTab = 0;
		if($vo3->getFilterValue("filter_learner_surname") != '' || $vo3->getFilterValue("filter_learner_firstname") != '')
			$learnerTab = 1;
		if($learnerTab==1)
		{
			$employerInfoTab = '';
			$learnerTab =  ' class="selected" ';
		}
		else
		{
			$employerInfoTab = ' class="selected" ';
			$learnerTab = '';
		}

		$vo5 = ViewOrganisationOtherLearners::getInstance($link, $id);
		$vo5->refresh($link, $_REQUEST);

		$vo4 = ViewEmployerTrainingRecords::getInstance($link, $id);
		$vo4->refresh($link, $_REQUEST);

		$locations = ViewOrganisationLocations::getInstance($link, $id);
		$locations->refresh($link, $_REQUEST);

		$vacancies = ViewApprenticeVacancies::getInstance($link, $id);
		$vacancies->refresh($link, $_REQUEST);

		$view_employer_contact_emails = ViewEmployerContactEmails::getInstance($link, $id);
		$view_employer_contact_emails->refresh($link, $_REQUEST);

		$viewCRMContacts = ViewCRMContacts::getInstance($link, $id);
		$viewCRMContacts->refresh($link, $_REQUEST);

		$org_contacts_calender_events_notes = $this->renderCalenderEventNotes($link, $id);

		$data = $vo4->getStats($link);

		// re - 29/02/2012 - change this to be a configuration table element
		// ---
		$emp_id = $id;
		// Preparation for File Repository Listing
		$db = DB_NAME;
		//		$user = $_SESSION['user']->username;

		// Create if does not exists
		if(!file_exists(DATA_ROOT."/uploads/$db"))
		{
			mkdir(DATA_ROOT."/uploads/$db");
		}
		if(!file_exists(DATA_ROOT."/uploads/$db/employers"))
		{
			mkdir(DATA_ROOT."/uploads/".DB_NAME."/employers");
		}
		if(!file_exists(DATA_ROOT."/uploads/$db/employers/$emp_id"))
		{
			mkdir(DATA_ROOT."/uploads/".DB_NAME."/employers/".$emp_id);
		}

		$urls2 = Array();
		$directories = Array();
		if(file_exists(DATA_ROOT."/uploads/$db/employers/$emp_id"))
		{
			$TrackDir=opendir(DATA_ROOT."/uploads/$db/employers/$emp_id");
			$n2 = 0;
			$directories = Array();
			$sections = "<select name='section' id='sections'><option value=''></option>";
			$html2 = '<table class="resultset" id="tblFiles" border="0" cellspacing="0" cellpadding="6"><thead><tr>';
			if($_SESSION['user']->isAdmin())
				$html2 .= "<th>Delete</th>";
			$html2 .= '<th>Filename</th><th>Size</th><th>Upload Date</th></tr></thead>';
			while ($file = readdir($TrackDir))
			{
				$full = DATA_ROOT."/uploads/$db/employers/$emp_id/$file";
				if(!is_dir($full))
				{
					if ($file != "." && $file != ".." && $file!='admin')
					{
						$n2++;
						if(isset($urls2[$n2]))
						{
							$urls2[$no] .=  '<a href="do.php?_action=downloader&path=employers/' . $emp_id . '/' . "&f=" . rawurlencode($file) . '"><br>'  .$file . '</a>';
							$href2 = "do.php?_action=delete_file&path=employers/" . $emp_id . '/' . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=read_employer&id='.$emp_id);
							$html2 .= "<tr>";
							$html2 .= "<td><a href=$href2><img src='/images/delete.gif'></img></a></td>";
							$html2 .= "<td>" . $urls2[$n2] . "</td></tr>";
							die($urls2[$n2]);
						}
						else
						{
							$urls2[$n2] = '<a href="do.php?_action=downloader&path=employers/' . $emp_id . '/' . "&f=" . rawurlencode($file) . '">' .$file . '</a>';
							$href2 = "do.php?_action=delete_file&path=employers/" . $emp_id . '/' . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=read_employer&id='.$emp_id);;
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

		foreach($directories as $directory)
		{
			if(substr($directory,0,8)=="section_")
			{
				$TrackDir=opendir(DATA_ROOT."/uploads/$db/$directory");
				//$sections = "<select name='section' id='sections'><option value=''></option>";
				$html2 .= "<br><h3>Section:" . substr($directory,8) . "</h3>";
				$html2 .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6"><thead>';
				if($_SESSION['user']->isAdmin())
					$html2 .= "<th>Delete</th>";
				$html2 .= '<th>Filename</th><th>Size</th><th>Upload Date</th></thead>';
				while ($file = readdir($TrackDir))
				{
					$full = DATA_ROOT."/uploads/$db/$directory/$file";
					if(!is_dir($full))
					{
						if ($file != "." && $file != ".." && $file!='admin')
						{
							$n2++;
							if(isset($urls2[$n2]))
							{
								$urls2[$no] .=  '<a href="do.php?_action=downloader&path=' . $directory . "&f=" . rawurlencode($file) . '"><br>' . $n2 . ". " .$file . '</a>';
								$href2 = "do.php?_action=delete_file&path=" . $directory . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=read_employer&id='.$emp_id);;
								$html2 .= "<tr><td><a href=$href2><img src='/images/delete.gif'></img></a></td><td>" . $urls2[$n2] . "</td></tr>";
								die($urls2[$n2]);
							}
							else
							{
								//$path = DATA_ROOT.'/uploads/' . $db . '/' . $directory . '/';
								$urls2[$n2] = '<a href="do.php?_action=downloader&path=' . rawurlencode($directory) . "&f=" . rawurlencode($file) . '">'  . $file . '</a>';
								$href2 = "do.php?_action=delete_file&path=" . rawurlencode($directory) . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=read_employer&id='.$emp_id);
								$href3 = 'if(confirm("Are you sure?"))window.location.href="' . $href2 . '"';
								$html2 .= "<tr>";
								if($_SESSION['user']->isAdmin())
									$html2 .= "<td align=center><img style='cursor: pointer' onclick='$href3' src='/images/delete.gif'></img></td>";
								$html2 .= "<td>" . $urls2[$n2] . "</td><td align=right>". $this->format_size(filesize($full)) ."</td><td>" . date("d F Y H:i:s", filemtime($full)) . "</td></tr>";
							}
						}
					}
				}
				$html2 .= '</table>';
				closedir($TrackDir);
			}
		}

		//$sizeDescription = Employer::getEmployerSizeDescription($link);
		//$vo->code = isset($sizeDescription[$vo->code][1])?$sizeDescription[$vo->code][1]:'';
		//$vo->code = Employer::getEmployerSizeDescription($vo->code);

		$learners = SystemConfig::get("smartassessor.config.learners");

		if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic")
		{
			$pool_id = DAO::getSingleValue($link, "SELECT org_id FROM employer_pool_contact_email_notes WHERE sunesis_employer_id = " . $id);
			if($pool_id)
			{
				$view_employer_pool_emails = ViewEmployerPoolContactEmails::getInstance($link, $pool_id);
				$view_employer_pool_emails->refresh($link, $_REQUEST);
			}
		}

		$contacts = $vo->getContacts($link, $id);
		$contact_emails = "";
		foreach($contacts AS $contact)
		{
			$str = explode("*", $contact[0]);
			if(isset($str[0]) && $str[0] != '')
				$contact_emails .= $str[0] . ",";
		}

		$contact_emails = rtrim($contact_emails, ",");
		// Presentation
		include('tpl_baltic_read_employer.php');
	}

	private function deleteOrganisationCRMContact($link)
	{
		$contact_id = isset($_REQUEST['contact_id'])?$_REQUEST['contact_id']:'';
		if($contact_id == '')
			return;

		$linked_trs = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.crm_contact_id = '{$contact_id}'");
		if($linked_trs > 0)
			return 'This record is linked with training records and cannot be deleted';

		$changed = DAO::execute($link, "DELETE FROM organisation_contact WHERE contact_id = '{$contact_id}'");
		if($changed == 0)
			return 'Record not deleted, please try again or raise a support request';
		return 'Record deleted successfully';
	}

	private function renderLocations(PDO $link, Employer $vo)
	{
		$locations = $vo->getLocations($link);
		if(count($locations) > 0)
		{
			echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
			echo '<tr><th>&nbsp;</th><th>Name</th><th>Locality</th><th>Town</th><th>County</th><th>Postcode</th><th>Telephone</th></tr>';

			/* @var $loc Location */
			foreach($locations as $loc)
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_location&id=' . $loc->id);
				echo '<td><a href="do.php?_action=read_location&id=' . $loc->id . '"><img src="/images/building-icon.png" border="0" /></a></td>';
				echo '<td>' . HTML::cell($loc->full_name) . '</td>';
				echo '<td>' . HTML::cell($loc->address_line_2) . '</td>';
				echo '<td>' . HTML::cell($loc->address_line_3) . '</td>';
				echo '<td>' . HTML::cell($loc->address_line_4) . '</td>';
				echo '<td>' . HTML::cell($loc->postcode) . '</td>';
				echo '<td>' . HTML::cell($loc->telephone) . '</td>';
				echo '</tr>';
			}

			echo '</table>';
		}
		else
		{
			echo '<p class="sectionDescription">None entered.</p>';
		}
	}


	private function renderPersonnel(PDO $link, Employer $vo)
	{
		$personnel = $vo->getPersonnel($link);
		if(count($personnel) > 0)
		{
			echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
			echo '<tr><th>&nbsp;</th><th>Surname</th><th>Firstnames</th><th>Telephone</th><th>Role</th></tr>';

			foreach($personnel as $per)
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $per->username);
				echo '<td><a href="do.php?_action=read_personnel&id=' . $per->username . '"><img src="/images/blue-person.png" border="0" /></a></td>';
				echo '<td>' . HTML::cell($per->surname) . '</td>';
				echo '<td>' . HTML::cell($per->firstnames) . '</td>';
				echo '<td>' . HTML::cell($per->work_telephone) . '</td>';

				$que = "select people_type from lookup_people_type where id='$per->type'";
				$type = trim(DAO::getSingleValue($link, $que));

				echo '<td>' . HTML::cell($type) . '</td>';


				echo '</tr>';
			}

			echo '</table>';
		}
		else
		{
			echo '<p class="sectionDescription">None entered.</p>';
		}
	}

	private function renderLearners(PDO $link, Employer $vo)
	{
		$personnel = $vo->getLearners($link);
		if(count($personnel) > 0)
		{
			echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
			echo '<tr><th>&nbsp;</th><th>Surname</th><th>Firstnames</th><th>Telephone</th></tr>';

			foreach($personnel as $per)
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $per->username);
				echo '<td><a href="do.php?_action=read_personnel&id=' . $per->username . '"><img src="/images/blue-person.png" border="0" /></a></td>';
				echo '<td>' . HTML::cell($per->surname) . '</td>';
				echo '<td>' . HTML::cell($per->firstnames) . '</td>';
				echo '<td>' . HTML::cell($per->work_telephone) . '</td>';
				echo '</tr>';
			}

			echo '</table>';
		}
		else
		{
			echo '<p class="sectionDescription">None entered.</p>';
		}
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
		$result = $link->query("SELECT * FROM employer_calendar_events_notes WHERE org_id = " . $organisation_id);
		if($result)
		{
			$htmlOutput = '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="5">';
			$htmlOutput .= '<thead height="40px"><tr><th>&nbsp;</th><th>Contact Name</th><th>Contact Email</th><th>Invitation Sent By</th><th>Start Date & Time</th><th>End Date & Time</th><th>Location</th><th>Subject</th><th>Description</th><th>Cancelled</th></tr></thead>';
			$htmlOutput .= '<tbody>';
			while($row = $result->fetch())
			{
				if($row['status'] != 'CANCELLED')
					$htmlOutput .= HTML::viewrow_opening_tag('/do.php?_action=send_emp_contact_cal_event&id=' . $row['id'] . '&org_id=' . $row['org_id']);

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

	private function learnersWithAgeGrant(PDO $link, $employer_id)
	{
		$sql = <<<HEREDOC
SELECT
	users.surname, users.firstnames, users.username, organisations.legal_name,
	locations.full_name, locations.telephone, users.gender

FROM
	users LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
where type = '5' and users.age_grant = 1 and users.employer_id='$employer_id';

HEREDOC;

		$result = $link->query($sql);
		if($result)
		{
			$htmlOutput = '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="5">';
			$htmlOutput .= '<thead height="40px"><tr><th>&nbsp;</th><th>Surname</th><th>Firstname</th><th>Username</th><th>Location</th><th>Work Telephone</th></tr></thead>';
			$htmlOutput .= '<tbody>';
			while($row = $result->fetch())
			{
				$htmlOutput .= HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				if($row['gender']=='M')
					$htmlOutput .= '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
				else
					$htmlOutput .= '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';
				$htmlOutput .= "<td>" . HTML::cell($row['surname']) . "</td>";
				$htmlOutput .= "<td>" . HTML::cell($row['firstnames']) . "</td>";
				$htmlOutput .= "<td>" . HTML::cell($row['username']) . "</td>";
				if($row['full_name'] == NULL) // can include empty string
				{
					$htmlOutput .= "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					$htmlOutput .= '<td align="left">' . HTML::cell($row['full_name']) . '</td>';
				}
				$htmlOutput .= '<td align="left">' . HTML::cell($row['telephone']) . '</td>';
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

	public function renderLearnerComplaints(PDO $link, Employer $vo)
	{
		$sql = new SQLStatement("
SELECT DISTINCT
	complaints.*, tr.firstnames, tr.surname
FROM
	complaints INNER JOIN tr ON complaints.record_id = tr.id
	INNER JOIN organisations ON tr.employer_id = organisations.id
		");
		$sql->setClause("WHERE organisations.id = '{$vo->id}'");
		$records = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
		$htmlOutput = '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="5">';
		$htmlOutput .= '<thead height="40px"><tr><th>Detail</th><th style="width: 15%;">Dates</th><th style="width: 15%;">Related Person / Department</th><th style="width: 15%;">Investigation</th><th>Response</th></tr></thead>';
		$htmlOutput .= '<tbody>';
		if(count($records) == 0)
			$htmlOutput .= '<tr><td colspan="5"><i>No records found</i></td> </tr>';
		else
		{
			foreach($records AS $row)
			{
				$trs = '<tr>';
				$trs .= '<td valign="top">';
				$trs .= '<strong>Learner: </strong> &nbsp; '.$row['firstnames'] . ' ' . strtoupper($row['surname']) . '<br>';
				$trs .= '<strong>Reference: </strong> &nbsp; '.$row['reference'] . ' &nbsp; ';
				$trs .= $row['outcome'] == 'C' ? '<strong>Outcome: </strong> &nbsp; Closed &nbsp; </span> <br>' : '<strong>Outcome: </strong> &nbsp; <span class="label label-danger"> &nbsp; Open <br>';
				$trs .= '<strong>Summary: </strong>'.HTML::nl2p($row['complaint_summary']);
				$trs .= '</td>';
				$trs .= '<td valign="top">';
				$trs .= '<strong>Date of complaint: </strong> &nbsp; '.Date::toShort($row['date_of_complaint']) . '<br>';
				$trs .= '<strong>Date of event: </strong> &nbsp;'.Date::toShort($row['date_of_event']) . '<br>';
				$trs .= '<strong>Created: </strong> &nbsp; '.Date::to($row['created'], Date::DATETIME) . '<br>';
				$trs .= '<strong>Created By: </strong> &nbsp; '.DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'");
				$trs .= '</td>';
				$trs .= '<td valign="top">';
				$trs .= '<strong>Person: </strong> &nbsp; '.$row['related_person'] . '<br>';
				$trs .= '<strong>Department(s): </strong>';
				$trs .= '<ul>';
				$depts = InductionHelper::getListRelatedDepartments();
				foreach(explode(',', $row['related_department']) AS $d)
					$trs .= isset($depts[$d]) ? '<li> '.$depts[$d] . '</li>' : '<li>'.$d.'</li>';
				$trs .= '</ul>';
				$trs .= '</td>';
				$trs .= '<td valign="top">';
				$trs .= $row['investigation_needed'] == 'Y' ? '<strong>Investigation needed: </strong> &nbsp; Yes &nbsp; <br>' : '<strong>Investigation needed: </strong> &nbsp; No <br>';
				$trs .= $row['investigation_form_sent'] == 'Y' ? '<strong>Investigation form sent: </strong> &nbsp; Yes &nbsp; <br>' : '<strong>Investigation form sent: </strong> &nbsp; No <br>';
				$trs .= '<strong>Investigation form sent to: </strong>';
				$trs .= ' <ul> ';
				$sent_to = InductionHelper::getListOpInternalManagers($link);
				foreach(explode(',', $row['investigation_form_to']) AS $d)
					$trs .= isset($sent_to[$d]) ? '<li> '.$sent_to[$d] . '</li>' : '<li> '.$d.'</li>';
				$trs .= '</ul>';
				$trs .= '</td>';
				$trs .= '<td valign="top">';
				$trs .= '<strong>Date of response: </strong> &nbsp; '.Date::toShort($row['date_of_response']) . '<br>';
				$trs .= $row['corrective_action_taken'] == 'Y' ? '<strong>Corrective action taken: </strong> &nbsp; Yes &nbsp; <br>' : '<strong>Corrective action taken: </strong> &nbsp; No <br>';
				$trs .= '<strong>Baltic values: </strong>';
				$trs .= ' &nbsp; ';
				$b_vals = InductionHelper::getListBalticValues();
				foreach(explode(',', $row['baltic_values']) AS $d)
					$trs .= isset($b_vals[$d]) ? $b_vals[$d] . ', ' : $d.', ';
				$trs .= '<br>';
				$trs .= '<strong>Summary: </strong>'.HTML::nl2p($row['response_summary']);
				$trs .= '</td>';
				$htmlOutput .= $trs;
			}
		}

		$htmlOutput .= '</tbody></table></div>';


		return $htmlOutput;
	}

	public function renderEmployerComplaints(PDO $link, Employer $vo)
	{
		$sql = new SQLStatement("
SELECT DISTINCT
	complaints.*
FROM
	complaints
		");
		$sql->setClause("WHERE complaints.complaint_type = '" . Complaint::EMPLOYER_COMPLAINT . "'");
		$sql->setClause("WHERE complaints.record_id = '{$vo->id}'");
		$records = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
		$htmlOutput = '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="5">';
		$htmlOutput .= '<thead height="40px"><tr><th>Detail</th><th style="width: 15%;">Dates</th><th style="width: 15%;">Related Person / Department</th><th style="width: 15%;">Investigation</th><th>Response</th></tr></thead>';
		$htmlOutput .= '<tbody>';
		if(count($records) == 0)
			$htmlOutput .= '<tr><td colspan="5"><i>No records found</i></td> </tr>';
		else
		{
			foreach($records AS $row)
			{
				$trs = '<tr>';
				$trs .= '<td valign="top">';
				$trs .= '<strong>Reference: </strong> &nbsp; '.$row['reference'] . ' &nbsp; ';
				$trs .= $row['outcome'] == 'C' ? '<strong>Outcome: </strong> &nbsp; Closed &nbsp; </span> <br>' : '<strong>Outcome: </strong> &nbsp; <span class="label label-danger"> &nbsp; Open <br>';
				$trs .= '<strong>Summary: </strong>'.HTML::nl2p($row['complaint_summary']);
				$trs .= '</td>';
				$trs .= '<td valign="top">';
				$trs .= '<strong>Date of complaint: </strong> &nbsp; '.Date::toShort($row['date_of_complaint']) . '<br>';
				$trs .= '<strong>Date of event: </strong> &nbsp;'.Date::toShort($row['date_of_event']) . '<br>';
				$trs .= '<strong>Created: </strong> &nbsp; '.Date::to($row['created'], Date::DATETIME) . '<br>';
				$trs .= '<strong>Created By: </strong> &nbsp; '.DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'");
				$trs .= '</td>';
				$trs .= '<td valign="top">';
				$trs .= '<strong>Person: </strong> &nbsp; '.$row['related_person'] . '<br>';
				$trs .= '<strong>Department(s): </strong>';
				$trs .= '<ul>';
				$depts = InductionHelper::getListRelatedDepartments();
				foreach(explode(',', $row['related_department']) AS $d)
					$trs .= isset($depts[$d]) ? '<li> '.$depts[$d] . '</li>' : '<li>'.$d.'</li>';
				$trs .= '</ul>';
				$trs .= '</td>';
				$trs .= '<td valign="top">';
				$trs .= $row['investigation_needed'] == 'Y' ? '<strong>Investigation needed: </strong> &nbsp; Yes &nbsp; <br>' : '<strong>Investigation needed: </strong> &nbsp; No <br>';
				$trs .= $row['investigation_form_sent'] == 'Y' ? '<strong>Investigation form sent: </strong> &nbsp; Yes &nbsp; <br>' : '<strong>Investigation form sent: </strong> &nbsp; No <br>';
				$trs .= '<strong>Investigation form sent to: </strong>';
				$trs .= ' <ul> ';
				$sent_to = InductionHelper::getListOpInternalManagers($link);
				foreach(explode(',', $row['investigation_form_to']) AS $d)
					$trs .= isset($sent_to[$d]) ? '<li> '.$sent_to[$d] . '</li>' : '<li> '.$d.'</li>';
				$trs .= '</ul>';
				$trs .= '</td>';
				$trs .= '<td valign="top">';
				$trs .= '<strong>Date of response: </strong> &nbsp; '.Date::toShort($row['date_of_response']) . '<br>';
				$trs .= $row['corrective_action_taken'] == 'Y' ? '<strong>Corrective action taken: </strong> &nbsp; Yes &nbsp; <br>' : '<strong>Corrective action taken: </strong> &nbsp; No <br>';
				$trs .= '<strong>Baltic values: </strong>';
				$trs .= ' &nbsp; ';
				$b_vals = InductionHelper::getListBalticValues();
				foreach(explode(',', $row['baltic_values']) AS $d)
					$trs .= isset($b_vals[$d]) ? $b_vals[$d] . ', ' : $d.', ';
				$trs .= '<br>';
				$trs .= '<strong>Summary: </strong>'.HTML::nl2p($row['response_summary']);
				$trs .= '</td>';
				$htmlOutput .= $trs;
			}
		}

		$htmlOutput .= '</tbody></table></div>';


		return $htmlOutput;
	}

}
?>