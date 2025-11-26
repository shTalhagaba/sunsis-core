<?php
class read_hotel implements IAction
{
	public $id = NULL;
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
			throw new Exception('Missing querystring argument: employer id');

		$vo = Employer::loadFromDatabase($link, $id);
		if(is_null($vo))
			throw new Exception('Hotel record not found.');

		$this->id = $id;

		$_SESSION['bc']->add($link, "do.php?_action=read_hotel&id=" . $vo->id, "View Hotel");

		$sector = DAO::getSingleValue($link, "SELECT description FROM lookup_sector_types WHERE id = '{$vo->sector}'");
		$group_employer = DAO::getSingleValue($link, "SELECT title FROM brands WHERE id = '{$vo->manufacturer}'");
		$size = DAO::getSingleValue($link, "SELECT description FROM lookup_employer_size WHERE code = '{$vo->code}'");

		$locations_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM locations WHERE locations.organisations_id = '{$vo->id}'");
		$learners_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.type = '" . User::TYPE_LEARNER . "' AND users.employer_id = '{$vo->id}'");
		$users_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.type != '" . User::TYPE_LEARNER . "' AND users.employer_id = '{$vo->id}'");
		$crm_notes_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_notes WHERE crm_notes.organisation_id = '{$vo->id}'");
		$org_crm_notes_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_notes_orgs WHERE crm_notes_orgs.organisation_id = '{$vo->id}'");
		$crm_contacts_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM organisation_contact WHERE organisation_contact.org_id = '{$vo->id}'");
		if(SOURCE_LOCAL || in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
		{
			$learner_complaints_count = DAO::getSingleValue($link, "SELECT DISTINCT COUNT(*) FROM complaints INNER JOIN tr ON complaints.record_id = tr.id WHERE tr.employer_id = '{$vo->id}'");
			$complaints_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM complaints WHERE record_id = '{$vo->id}' AND complaint_type = '" . Complaint::EMPLOYER_COMPLAINT . "'");
		}
		$salary_rate_options = [
			0 => [0, '', null, null],
			1 => [1, 'Grade 1'],
			2 => [2, 'Grade 2'],
			3 => [3, 'Grade 3']];
		include_once('tpl_read_hotel.php');
	}

	private function renderLocations(PDO $link, $back)
	{
		$sql = <<<HEREDOC
SELECT
	GROUP_CONCAT(DISTINCT users.firstnames, " ", users.surname SEPARATOR ', ') AS assessor,
	locations.*,
    CONCAT(COALESCE(locations.`address_line_1`), ' ',COALESCE(locations.`address_line_2`,''), ' ',COALESCE(locations.`address_line_3`,''),',',COALESCE(locations.`address_line_4`,'')) AS location_address,

	CASE

		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >30 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <0 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >=0 and (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <= 30 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `health_and_safety`,
	CASE

		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 1 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 2 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 3 THEN "<img  src='/images/warning-17.JPG'  border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `compliant`
FROM
	locations
LEFT JOIN
	tr ON tr.employer_location_id = locations.id
LEFT JOIN
	users ON users.id = tr.assessor
WHERE
	organisations_id = '$this->id'
GROUP BY locations.id;
HEREDOC;
		/* @var $result pdo_result */
		$st = $link->query($sql);
		if($st)
		{
			echo '<table class="table table-bordered">';
			echo '<thead><tr><th>&nbsp;</th><th>Name</th><th>Address</th><th>Telephone</th><th>Assessors</th><th>Health & Safety</th><th>Compliance</th><th class="small">SFA Area Cost Factor</th><th class="small">EFA Area Cost Factor</th></tr></thead>';
			echo '<tbody>';
			while($loc = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_location&id=' . $loc['id'] . '&back=' . $back . '&organisation_id=' . $loc['organisations_id']);
				echo '<td><span class="fa fa-building"></span> </td>';
				echo '<td>' . HTML::cell($loc['full_name']) . '</td>';
				echo '<td>' . $loc['location_address'] . '<br>' . $loc['postcode'] . '</td>';
				echo '<td>' . HTML::cell($loc['telephone']) . '</td>';
				echo '<td>' . HTML::cell($loc['assessor']) . '</td>';
				echo '<td align=center>' . $loc['health_and_safety'] . '</td>';
				echo '<td align=center>' . $loc['compliant'] . '</td>';
				echo '<td align=center>' . DAO::getSingleValue($link, "SELECT SFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = '" . $loc['postcode'] . "'") . '</td>';
				echo '<td align=center>' . DAO::getSingleValue($link, "SELECT EFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = '" . $loc['postcode'] . "'") . '</td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

	}

	private function renderLearners(PDO $link)
	{
		$sql = <<<HEREDOC
SELECT
	users.surname, users.job_role, users.firstnames, users.username, organisations.legal_name,
	locations.full_name, locations.telephone, users.gender, users.home_email, users.work_email,
	(select count(*) from tr where tr.username = users.username) as trs
FROM
	users LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
where type = '5' and users.employer_id='$this->id';
HEREDOC;
		$st = $link->query($sql);
		if($st)
		{
			echo '<table id="tblLearners" class="table table-bordered">';
			echo '<thead><tr><th>&nbsp;</th><th>Surname</th><th>Firstname</th><th>Location</th><th>Work Email</th><th>Home Email</th><th>Training Records</th></tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				if($_SESSION['user']->type!=9 && $_SESSION['user']->type!=2 && $_SESSION['user']->type!=3 && $_SESSION['user']->type!=4)
				{
					echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				}
				if($row['gender']=='M')
					echo '<td><span class="fa fa-male"></span> </td>';
				elseif($row['gender']=='F')
					echo '<td><span class="fa fa-female"></span> </td>';
				else
					echo '<td><span class="fa fa-user"></span> </td>';
				echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['firstnames']) . "</td>";
				if($row['full_name'] == NULL)
				{
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				}
				else
				{
					echo '<td align="left">' . HTML::cell($row['full_name']) . '</td>';
				}
				echo '<td align="left">' . HTML::cell($row['work_email']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['home_email']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['trs']) . '</td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function renderSystemUsers(PDO $link)
	{
		$sql = <<<HEREDOC
SELECT
	type,
	surname,
	firstnames,
	username,
	organisations.legal_name,
	locations.full_name,
	work_telephone,
	work_email,
	job_role,
	lookup_user_types.description as utype,
	web_access,
	users.gender
FROM
	users
	LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
	LEFT JOIN lookup_user_types on lookup_user_types.id = users.type
where type <> '5' and employer_id='$this->id';
HEREDOC;
		/* @var $result pdo_result */
		$st = $link->query($sql);
		if($st)
		{
			echo '<table class="table table-bordered table-striped">';
			echo '<thead><tr><th>&nbsp;</th><th>Web Access</th><th>Surname</th><th>Firstname</th><th>Username</th><th>User Type</th><th>Job Role</th><th>Location</th><th>Work Telephone</th><th>Work Email</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				if($_SESSION['user']->type!=9 && $_SESSION['user']->type!=2 && $_SESSION['user']->type!=3 && $_SESSION['user']->type!=4)
				{
					echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);
				}
				if($row['gender']=='M')
					echo '<td><span class="fa fa-male"></span> </td>';
				elseif($row['gender']=='F')
					echo '<td><span class="fa fa-female"></span> </td>';
				else
					echo '<td><span class="fa fa-user"></span> </td>';
				if($row['web_access'] == '1')
					echo "<td><span class='label label-success'><span class='fa fa-check'></span> Enabled</span></td>";
				else
					echo "<td><span class='label label-danger'><span class='fa fa-close'></span> Disabled</span></td>";
				echo '<td>' . HTML::cell($row['surname']) . "</td>";
				echo '<td>' . HTML::cell($row['firstnames']) . "</td>";
				echo '<td align="left" style="font-family:monospace">' . htmlspecialchars((string)$row['username']) . "</td>";
				echo '<td>' . HTML::cell($row['utype']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['job_role']) . '</td>';
				if($row['full_name'] == NULL)
					echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
				else
					echo '<td align="left">' . HTML::cell($row['full_name']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['work_telephone']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['work_email']) . '</td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function renderCRMNotes(PDO $link, $type)
	{
		$sql = <<<HEREDOC
SELECT
	crm_notes.id,
	organisation_id,
	name_of_person,
	position,
	agreed_action,
	DATE_FORMAT(date, '%d/%m/%Y') AS date,
	lookup_crm_contact_type.description as type_of_contact,
	lookup_crm_subject.description as subject,
	by_whom,
	whom_position,
	organisations.legal_name,
	#organisations_status.org_status_id,
	#organisations_status.org_status_comment,
	crm_notes.`audit_info`
FROM
	crm_notes
	left join lookup_crm_contact_type on lookup_crm_contact_type.id = crm_notes.type_of_contact
	left join lookup_crm_subject on lookup_crm_subject.id = crm_notes.subject
	LEFT JOIN organisations ON organisations.id = crm_notes.organisation_id


where
	organisation_id = $this->id
	AND organisations.id = $this->id
	AND  organisations.id = organisation_id
ORDER BY crm_notes.date desc
HEREDOC;
		/* @var $result pdo_result */
		$st = $link->query($sql);
		if($st)
		{
			echo '<table class="table table-bordered table-striped">';
			echo '<thead><tr><th>&nbsp;</th><th>Date</th><th>Person contacted</th><th> Position </th><th>Type of Contact</th><th>Subject</th><th>By whom</th><th> Position </th><th>Agreed Action</th><th>Audit Info</th></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				if(DB_NAME=="am_baltic")
					echo '<tr>';
				else
					echo HTML::viewrow_opening_tag('do.php?_action=edit_crm_note&mode=edit&id=' . rawurlencode($row['id']) . '&organisations_id=' . rawurlencode($row['organisation_id']) . '&organisation_type=' . $type . '&person_contacted=' . rawurlencode($row['name_of_person']));
				echo '<td><span class="fa fa-sticky-note"></span> </td>';
				echo '<td align="left">' . HTML::cell($row['date']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['name_of_person']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['position']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['type_of_contact']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['subject']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['by_whom']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['whom_position']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['agreed_action']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['audit_info']) . "</td>";
				echo '</tr>';
			}
			echo '</tbody></table>';
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function renderOrganisationCRMNotes(PDO $link, $type)
	{
        $view = ViewOrganisationCrmNotes::getInstance($link, $this->id);
        $view->refresh($link, $_REQUEST);

		$sql = <<<HEREDOC
SELECT
	crm_notes_orgs.id,	
	crm_notes_orgs.organisation_id,
	organisation_contact.contact_name as name_of_person,
	organisation_contact.job_title as position,
	crm_notes_orgs.agreed_action,
	DATE_FORMAT(crm_notes_orgs.`contact_date`, '%d/%m/%Y') AS date,
	lookup_crm_contact_type.description as type_of_contact,
	lookup_crm_subject.description as subject,
	crm_notes_orgs.by_whom,
	crm_notes_orgs.by_whom_position as whom_position,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = crm_notes_orgs.`created_by`) AS created_by,
	crm_notes_orgs.created_at,
	crm_notes_orgs.actioned
FROM
	crm_notes_orgs
	left join lookup_crm_contact_type on lookup_crm_contact_type.id = crm_notes_orgs.type_of_contact
	left join lookup_crm_subject on lookup_crm_subject.id = crm_notes_orgs.subject
	left join organisation_contact on organisation_contact.contact_id = crm_notes_orgs.org_contact_id
	LEFT JOIN organisations ON organisations.id = crm_notes_orgs.organisation_id
	
where 
	organisation_id = $this->id
	AND organisations.id = $this->id
	AND  organisations.id = organisation_id
ORDER BY crm_notes_orgs.contact_date desc
HEREDOC;
		/* @var $result pdo_result */
		$st = $link->query($sql);
		if($st)
		{
			echo '<table class="table table-bordered table-striped">';
			echo '<thead><tr><th>&nbsp;</th><th>Date</th><th>Person contacted</th><th>Type of Contact</th><th>Subject</th><th>By whom</th><th> Position </th><th>Agreed Action</th><th>Audit Info</th></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				if(DB_NAME=="am_baltic")
					echo '<tr>';
				else
					echo HTML::viewrow_opening_tag('do.php?_action=edit_crm_note&mode=edit&id=' . rawurlencode($row['id']) . '&organisations_id=' . rawurlencode($row['organisation_id']) . '&organisation_type=' . $type . '&person_contacted=' . rawurlencode($row['name_of_person']));
				echo '<td><span class="fa fa-sticky-note"></span> </td>';
				echo '<td align="left">' . HTML::cell($row['date']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['name_of_person']) . "</td>";
				//echo '<td align="left">' . HTML::cell($row['position']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['type_of_contact']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['subject']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['by_whom']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['whom_position']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['agreed_action']) . "</td>";
                if (isset($row['audit_info'])) {
                    echo '<td align="left">' . htmlspecialchars((string)$row['audit_info']) . "</td>";
                }else{
                    echo '<td align="left"><i>created by: ' . $row['created_by'] . ' on ' . Date::toShort($row['created_at']) . ' at ' . Date::to($row['created_at'], 'H:i:s') . "</td>";
                }
				echo '</tr>';
			}
			echo '</tbody></table>';
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function renderCRMContacts(PDO $link)
	{
		$ddlJobRoles = array(
			'' => '',
			0 => 'Admin',
			1 => 'Line Manager/ Supervisor',
			2 => 'HE',
			3 => 'Finance',
			4 => 'Levy Contact',
			5 => 'Apprentice Coordinator',
			6 => 'HR Manager',
			7 => 'HR Adviser',
			8 => 'L & D Manager',
			9 => 'Training Manager'
		);
		$sql = <<<HEREDOC
	SELECT
		*
	FROM
		organisation_contact
	WHERE org_id = '$this->id';
HEREDOC;
		/* @var $result pdo_result */
		$st = $link->query($sql);
		if($st)
		{
			echo '<table class="table table-bordered table-striped">';
			echo '<thead><tr><th>&nbsp;</th><th>Title</th><th>Name</th><th>Job Role</th><th>Department</th><th>Telephone</th><th>Mobile</th><th>Email</th></tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo")
				{
					$linked_trs = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.crm_contact_id = '" . $row['contact_id'] . "'");
					if($linked_trs > 0)
					{
						echo '<tr><td rowspan = "2"><span class="label label-info">Review forms attached</span></td></tr>';
					}
					else
					{
						echo '<tr><td rowspan = "2"><span class="btn btn-sm btn-danger" onclick="deleteOrganisationCRMContact(\'' . $row['contact_id'] . '\');"><i class="fa fa-trash"></i> </span></td></tr>';
					}
				}
				echo HTML::viewrow_opening_tag('/do.php?_action=edit_crm_contact&org_type=employer&contact_id=' . $row['contact_id']);
				if(DB_NAME != "am_baltic" || DB_NAME != "am_baltic_demo")
					echo '<td><i class="fa fa-user"></i></td>';
				echo '<td align="left">' . HTML::cell($row['contact_title']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_name']) . '</td>';
				echo '<td align="left">' . HTML::cell($ddlJobRoles[$row['job_role']]) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_department']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_telephone']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_mobile']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contact_email']) . '</td>';

				echo '</tr>';
			}

			echo '</tbody></table>';
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
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
		$htmlOutput = '<div class="table-responsive"><table class="table table-bordered">';
		$htmlOutput .= '<thead><tr><th>Detail</th><th style="width: 15%;">Dates</th><th style="width: 15%;">Related Person / Department</th><th style="width: 15%;">Investigation</th><th>Response</th></tr></thead>';
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
		$htmlOutput = '<div class="table-responsive"><table class="table table-bordered">';
		$htmlOutput .= '<thead ><tr><th>Detail</th><th style="width: 15%;">Dates</th><th style="width: 15%;">Related Person / Department</th><th style="width: 15%;">Investigation</th><th>Response</th></tr></thead>';
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

		$htmlOutput .= '</tbody></table></div> ';


		return $htmlOutput;
	}

	private function learnersWithAgeGrant(PDO $link, $hotel_id)
	{
		$sql = <<<HEREDOC
SELECT
	users.surname, users.firstnames, users.username, organisations.legal_name,
	locations.full_name, locations.telephone, users.gender

FROM
	users LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
WHERE type = '5' AND users.age_grant = 1 AND users.employer_id='$hotel_id';

HEREDOC;

		$result = $link->query($sql);
		if($result)
		{
			$htmlOutput = '<div class="table-responsive"><table class="table table-bordered">';
			$htmlOutput .= '<thead><tr><th>&nbsp;</th><th>Surname</th><th>Firstname</th><th>Username</th><th>Location</th><th>Work Telephone</th></tr></thead>';
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
				if($row['full_name'] == NULL)
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
			$htmlOutput = '<div>NO RECORD FOUND</div>';
		}
		return $htmlOutput;

	}


}