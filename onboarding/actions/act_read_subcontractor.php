<?php
class read_subcontractor implements IAction
{
    public $id = NULL;
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

        if($id == '')
            throw new Exception('Missing querystring argument: subcontractor id');

        $vo = Subcontractor::loadFromDatabase($link, $id);
        if(is_null($vo))
            throw new Exception('Subcontractor record not found.');

        $this->id = $id;

        $_SESSION['bc']->add($link, "do.php?_action=read_subcontractor&id={$vo->id}", "View subcontractor");

        $locations_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM locations WHERE locations.organisations_id = '{$vo->id}'");
        $users_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.type != '" . User::TYPE_LEARNER . "' AND users.employer_id = '{$vo->id}'");
        $crm_notes_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_notes_orgs WHERE crm_notes_orgs.organisation_id = '{$vo->id}'");
        $crm_contacts_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM organisation_contacts WHERE organisation_contacts.org_id = '{$vo->id}'");
        $hs_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM health_safety WHERE location_id IN (SELECT locations.id FROM locations WHERE locations.organisations_id = '{$vo->id}')");

        $repository = Repository::getRoot().'/subcontractors/'.$vo->id;
        $repository = Repository::readDirectory($repository);
        $files_count = 0;
        foreach($repository AS $file)
        {
            if($file->isDir())
                continue;
            $files_count++;
        }

        include_once('tpl_read_subcontractor.php');
    }

    private function renderLocations(PDO $link, $back)
    {
        $sql = <<<HEREDOC
SELECT
	locations.*,
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
	END AS `compliant`,
	(SELECT COUNT(*) FROM ob_tr WHERE ob_tr.provider_location_id = locations.id) AS learners
FROM
	locations
WHERE
	organisations_id = '$this->id'
ORDER BY locations.is_legal_address DESC;
HEREDOC;
        $st = $link->query($sql);
        if($st)
        {
            echo '<div class="row">';
            while($loc = $st->fetch())
            {
                echo '<div class="col-sm-6">';
                echo $loc['is_legal_address'] == '1' ? '<div class="box box-success box-solid">':  '<div class="box box-success">';
                echo '<div class="box-header with-border">';
                echo '<span class="text-bold">'.$loc['full_name'] . ' / ' . $loc['short_name'] .'</span>';
                echo '</div>';
                echo '<div class="box-body">';
                echo '<div class="row">';
                echo '<div class="col-sm-6">';
                echo $loc['address_line_1'] != '' ? $loc['address_line_1'] . '<br>' : '';
                echo $loc['address_line_2'] != '' ? $loc['address_line_2'] . '<br>' : '';
                echo $loc['address_line_3'] != '' ? $loc['address_line_3'] . '<br>' : '';
                echo $loc['address_line_4'] != '' ? $loc['address_line_4'] . '<br>' : '';
                echo $loc['postcode'] != '' ? '<i class="fa fa-map-marker"></i> ' . $loc['postcode'] . '<br>' : '';
                echo $loc['telephone'] != '' ? '<i class="fa fa-phone"></i> ' . $loc['telephone'] . '<br>' : '';
                echo $loc['fax'] != '' ? '<i class="fa fa-fax"></i> ' . $loc['fax'] : '';
                echo '</div> ';
                echo '<div class="col-sm-6">';
                echo '<span class="text-bold">H & S: </span>' . $loc['health_and_safety'] . '</br>';
                echo '<span class="text-bold">Compliance: </span>' . $loc['compliant'] . '</br>';
                echo '<span class="text-bold">SFA area cost factor: </span>' . DAO::getSingleValue($link, "SELECT SFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = '" . $loc['postcode'] . "'") . '</br>';
                echo '<span class="text-bold">EFA area cost factor: </span>' . DAO::getSingleValue($link, "SELECT EFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = '" . $loc['postcode'] . "'") . '</br>';
                echo '<span class="text-bold">Number of learners: </span>' . $loc['learners'] . '</br>';
                echo '</div> ';
                echo '</div> ';
                echo '</div> '; // box-body
                echo '<div class="box-footer"> ';
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN]))
                    echo '<span class="btn btn-info btn-xs" onclick="window.location.replace(\'do.php?_action=read_location&id=' . $loc['id'] . '&back=' . $back . '&organisations_id=' . $loc['organisations_id'] . '\');"><i class="fa fa-folder-open"></i> View</span> ';
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN]))
                    echo '<span class="btn btn-primary btn-xs" onclick="window.location.replace(\'do.php?_action=edit_location&id=' . $loc['id'] . '&organisations_id=' . $loc['organisations_id'] . '\');"><i class="fa fa-edit"></i> Edit</span> ';
                if(($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN])))
                    echo '<span class="btn btn-danger btn-xs pull-right" onclick="deleteRecord(\'location\', \''.$loc['postcode'].'\', \''.$loc['id'].'\', \''.$loc['full_name'].'\');"><i class="fa fa-trash"></i> Delete</span>';
                echo '</div>';
                echo '<div class="box-footer">';
                $pc = $loc['postcode'];
                echo <<<HTML
<iframe style="background-color: #ffffff;"
    src="https://maps.google.co.uk/maps?q=$pc&amp;ie=UTF8&amp;hq=&amp;hnear=B1 2HF,+United+Kingdom
						&amp;gl=uk&amp;t=m&amp;vpsrc=0&amp;z=14&amp;iwloc=A&amp;output=embed"
    frameborder="0" marginwidth="0" marginheight="0" scrolling="no" align="left"
    width="100%" height="250"></iframe>
                </td>
HTML;
                echo '</div> ';
                echo '</div> '; // box
                echo '</div> '; // col-sm-6
            }
            echo '</div> ';
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
	users.id, users.username, users.job_role, lookup_user_types.description AS job,
	(SELECT COUNT(*) FROM logins WHERE logins.username = users.username) AS logins, (SELECT DATE_FORMAT(logins.date, '%d/%,/%Y %H:%i:%s') FROM logins WHERE logins.username = users.username ORDER BY logins.date DESC LIMIT 1) AS last_login
FROM
	users LEFT JOIN lookup_user_types on lookup_user_types.id = users.type
WHERE
	users.type <> '5' AND users.employer_id='$this->id'
ORDER BY users.firstnames	;
HEREDOC;
        /* @var $result pdo_result */
        $st = $link->query($sql);
        if($st)
        {
            echo '<div class="row is-flex">';
            while($row = $st->fetch())
            {
                $user = User::loadFromDatabase($link, $row['username']);
                $photopath = $user->getPhotoPath();
                if($photopath)
                    $photopath = "do.php?_action=display_image&username=".rawurlencode($user->username);
                else
                    $photopath = "/images/no_photo.png";
                $address = $user->work_address_line_1 != '' ? '<br>' . $user->work_address_line_1 . '<br>' : '';
                $address .= $user->work_address_line_2 != '' ? $user->work_address_line_2 . '<br>' : '';
                $address .= $user->work_address_line_3 != '' ? $user->work_address_line_3 . '<br>' : '';
                $address .= $user->work_address_line_4 != '' ? $user->work_address_line_4 . '<br>' : '';
                $address .= $user->work_postcode != '' ? '<i class="fa fa-map-marker text-green"></i> ' . $user->work_postcode . '<br>' : '';
                $address .= $user->work_telephone != '' ? '<i class="fa fa-phone text-green"></i> ' . $user->work_telephone . '<br>' : '';
                $address .= $user->work_mobile != '' ? '<i class="fa fa-mobile text-green"></i> ' . $user->work_mobile . '<br>' : '';
                $address .= $user->work_email != '' ? '<i class="fa fa-envelope text-green"></i> <a href="mailto:'.$user->work_email.'">' . $user->work_email . '</a><br>' : '';
                $_access = $user->web_access == '1' ? 'text-black' : 'text-red';
                $_box = $user->web_access == '1' ? 'box-success' : 'box-danger';

                $view = '';
                $edit = '';
                $delete = '';
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN]))
                    $view = '<span class="btn btn-info btn-xs" onclick="window.location.href=\'do.php?_action=read_user&username='.$user->username.'&id='.$user->id.'\'"><i class="fa fa-folder-open"></i> View</span>';
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN]))
                    $edit = '<span class="btn btn-primary btn-xs" onclick="window.location.href=\'do.php?_action=edit_user&username='.$user->username.'&id='.$user->id.'&organisations_id='.$user->employer_id.'\'"><i class="fa fa-edit"></i> Edit</span>';
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN]))
                    $delete = '<span class="btn btn-danger btn-xs pull-right" onclick="deleteRecord(\'system_user\', \''.$user->username.'\', \''.$user->id.'\', \''.$user->firstnames . ' ' . $user->surname.'\');"><i class="fa fa-trash"></i> Delete</span>';

                echo <<<HTML
<div class="col-sm-3">
	<div class="box $_box $_access">
		<div class="box-body box-profile">
			<img class="profile-user-img img-responsive img-circle" src="{$photopath}" alt="User's profile picture">
			<span class="profile-username">{$user->firstnames} {$user->surname}</span>
			<br><span class="text-bold">{$row['job']}</span>
			$address
			<br>
			<span class="text-bold">Total Logins: </span>{$row['logins']}
			<br><span class="text-bold">Last Login: </span>{$row['last_login']}
		</div>
		<div class="box-footer">
			$view
			$edit
			$delete
		</div>
	</div>
</div>
HTML;
            }
            echo '</div> ';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    private function renderSystemUsers_(PDO $link)
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
                echo '<td align="left" style="font-family:monospace">' . htmlspecialchars($row['username']) . "</td>";
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
	crm_notes_orgs.`id` AS id,
	crm_notes_orgs.`organisation_id`,
	organisation_contacts.`contact_name` AS name_of_person,
	organisation_contacts.`job_role`,
	crm_notes_orgs.`agreed_action`,
	DATE_FORMAT(crm_notes_orgs.`contact_date`, '%d/%m/%Y') AS contact_date,
	lookup_crm_contact_type.`description` AS type_of_contact,
	lookup_crm_subject.`description` AS `subject`,
	crm_notes_orgs.`by_whom`,
	crm_notes_orgs.`by_whom_position`,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = crm_notes_orgs.`created_by`) AS created_by,
	crm_notes_orgs.created_at,
	crm_notes_orgs.actioned

FROM
	crm_notes_orgs
	LEFT JOIN lookup_crm_contact_type ON lookup_crm_contact_type.id = crm_notes_orgs.type_of_contact
	LEFT JOIN lookup_crm_subject ON lookup_crm_subject.id = crm_notes_orgs.subject
	LEFT JOIN organisation_contacts ON organisation_contacts.`contact_id` = crm_notes_orgs.`org_contact_id`
WHERE
	crm_notes_orgs.organisation_id = $this->id
ORDER BY crm_notes_orgs.contact_date DESC
HEREDOC;
        /* @var $result pdo_result */
        $st = $link->query($sql);
        if($st)
        {
            echo '<table class="table table-bordered table-striped">';
            echo '<thead><tr><th>Date</th><th>Person contacted</th><th> Position </th><th>Type of Contact</th><th>Subject</th><th>By whom</th><th> Position </th><th style="width: 25%;">Agreed Action</th><th>Audit Info</th></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                $class = $row['actioned'] == 'Y' ? 'text-green' : '';
                if(DB_NAME == "am_baltic")
                    echo '<tr>';
                else
                    echo HTML::viewrow_opening_tag('do.php?_action=edit_org_crm_note&id=' . rawurlencode($row['id']) . '&organisations_id=' . rawurlencode($row['organisation_id']), $class);
                echo '<td align="left">' . HTML::cell($row['contact_date']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['name_of_person']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['job_role']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['type_of_contact']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['subject']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['by_whom']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['by_whom_position']) . "</td>";
                echo '<td align="left"><small>' . nl2br($row['agreed_action']) . "</small></td>";
                echo '<td align="left"><i>created by: ' . $row['created_by'] . ' on ' . Date::toShort($row['created_at']) . ' at ' . Date::to($row['created_at'], 'H:i:s') . "</td>";
                echo '</tr>';
            }
            echo '</tbody></table>';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    private function renderHS(PDO $link, Subcontractor $vo)
    {
        $sql = <<<HEREDOC
SELECT
  health_safety.*,
  locations.`full_name`,
  locations.`address_line_1`,
  locations.`address_line_2`,
  locations.`address_line_3`,
  locations.`address_line_4`,
  locations.`postcode`,
  locations.organisations_id
FROM
  health_safety INNER JOIN locations ON health_safety.`location_id` = locations.`id`
WHERE
  locations.organisations_id = '{$vo->id}'
ORDER BY
  health_safety.`last_assessment`
;
HEREDOC;
        /* @var $result pdo_result */
        $st = $link->query($sql);
        if($st)
        {
            echo '<table class="table table-bordered table-striped">';
            echo '<thead><tr><th>Location</th><th>Last Assessment</th><th>Next Assessment</th><th>Assessor</th><th>Compliant</th><th title="paperwork received">Paperwork Rec.</th><th>PL</th><th>EL</th><th>Comments</th></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag('do.php?_action=edit_health_and_safety&id=' . $row['location_id'] . '&back=read_subcontractor&organisation_id=' . $row['organisations_id']);
                echo '<td align="left">';
                echo $row['full_name'] != '' ? $row['full_name'] . '<br>' : '';
                echo $row['address_line_1'] != '' ? $row['address_line_1'] . '<br>' : '';
                echo $row['address_line_2'] != '' ? $row['address_line_2'] . '<br>' : '';
                echo $row['address_line_3'] != '' ? $row['address_line_3'] . '<br>' : '';
                echo $row['address_line_4'] != '' ? $row['address_line_4'] . '<br>' : '';
                echo $row['postcode'] != '' ? $row['postcode'] : '';
                echo '</td>';
                echo '<td align="left">' . Date::toShort($row['last_assessment']) . "</td>";
                echo '<td align="left">' . Date::toShort($row['next_assessment']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['assessor']) . "</td>";
                echo '<td>';
                if($row['complient'] == 1)
                    echo '<label class="label label-success">Compliant</label> ';
                elseif($row['complient'] == 2)
                    echo '<label class="label label-danger">Non-Compliant</label> ';
                elseif($row['complient'] == 3)
                    echo '<label class="label label-warning">Outstanding action</label> ';
                echo '</td>';
                echo $row['paperwork_received'] == '1' ? '<td><i class="fa fa-check fa-md"></i></td> ' : '<td></td>';
                echo '<td>';
                echo 'Date: ' . Date::toShort($row['pl_date']) . '<br>';
                echo $row['pl_insurance'] != '' ? $row['pl_insurance'] : '';
                echo '</td>';
                echo '<td>';
                echo 'Date: ' . Date::toShort($row['el_date']) . '<br>';
                echo $row['el_insurance'] != '' ? $row['el_insurance'] : '';
                echo '</td>';
                echo '<td>' . $row['comments'] . '</td>';
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
        $job_roles_ddl = DAO::getLookupTable($link, "SELECT id, description FROM lookup_job_roles WHERE cat = 'CRM Contact' ORDER BY description");
        $job_roles_ddl = $job_roles_ddl + ['' => ''];

        $sql = <<<HEREDOC
SELECT organisation_contacts.*, (SELECT COUNT(*) FROM crm_notes_orgs WHERE org_contact_id = organisation_contacts.contact_id) AS crm_notes_count FROM organisation_contacts WHERE org_id='$this->id' ORDER BY contact_name;
HEREDOC;
        /* @var $result pdo_result */
        $st = $link->query($sql);
        if($st)
        {
            echo '<div class="row is-flex">';
            while($row = $st->fetch())
            {
                $job_role = isset($job_roles_ddl[$row['job_role']]) ? $job_roles_ddl[$row['job_role']] : $row['job_role'];
                if($row['contact_department'] != '')
                    $job_role .= ' (' . $row['contact_department'] . ')';
                $address = $row['contact_telephone'] != '' ? '<br><i class="fa fa-phone text-green"></i> ' . $row['contact_telephone'] . '<br>' : '';
                $address .= $row['contact_mobile'] != '' ? '<i class="fa fa-mobile text-green"></i> ' . $row['contact_mobile'] . '<br>' : '';
                $address .= $row['contact_email'] != '' ? '<i class="fa fa-envelope text-green"></i> <a href="mailto:'.$row['contact_email'].'">' . $row['contact_email'] . '</a><br>' : '';
                $_access = $row['left_employer'] == '0' ? 'text-black' : 'text-red';

                $edit = '';
                $delete = '';
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN]))
                    $edit = '<span class="btn btn-primary btn-xs" onclick="window.location.href=\'do.php?_action=edit_crm_contact&org_type=employer&org_id='.$row['org_id'].'&contact_id='.$row['contact_id'].'\'"><i class="fa fa-edit"></i> Edit</span>';
                if($row['crm_notes_count'] == 0 && ($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN])))
                    $delete = '<span class="btn btn-danger btn-xs pull-right" onclick="deleteRecord(\'crm_contact\', \'\', \''.$row['contact_id'].'\', \''.$row['contact_name'].'\');"><i class="fa fa-trash"></i> Delete</span>';

                echo <<<HTML
<div class="col-sm-4">
	<div class="box box-primary $_access">
		<div class="box-body box-profile">
			<span class="profile-username">{$row['contact_title']} {$row['contact_name']}</span>
			<br><span class="text-bold">$job_role</span>
			$address
		</div>
		<div class="box-footer">
			$edit
			$delete
		</div>
	</div>
</div>
HTML;
            }
            echo '</div> ';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

    }

    public function isAbleToDelete()
    {
        if($_SESSION['user']->isAdmin())
            return true;

        return false;
    }

    public function renderFileRepository(PDO $link, Subcontractor $vo)
    {
        $repository = Repository::getRoot().'/subcontractors/'.$vo->id;
        $files = Repository::readDirectory($repository);

        if(count($files) > 0)
        {
            echo '<div class="row is-flex">';
            foreach($files as $f)
            {
                if($f->isDir()){
                    continue;
                }
                $ext = new SplFileInfo($f->getName());
                $ext = $ext->getExtension();
                $image = 'fa-file';
                if($ext == 'doc' || $ext == 'docx')
                    $image = 'fa-file-word-o';
                elseif($ext == 'pdf')
                    $image = 'fa-file-pdf-o';
                elseif($ext == 'txt')
                    $image = 'fa-file-text-o';

                $html = '<li class="list-group-item">';
                $html .= '<i class="fa '.$image.'"></i> ' . htmlspecialchars($f->getName());
                $html .= '<br><span class="direct-chat-timestamp"><i class="fa fa-clock-o"></i> ' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</span>';
                $html .= '<br><span class="direct-chat-timestamp"><i class="fa fa-folder"></i> ' . Repository::formatFileSize($f->getSize()) .'</span>';

                $html .= '<br><p><span title="Download file" class="btn btn-xs btn-info" onclick="window.location.href=\''.$f->getDownloadURL().'\';"><i class="fa fa-download"></i></span>';
                if($this->isAbleToDelete())
                {
                    $html .= '<span title="Delete file" class="btn btn-xs btn-danger pull-right" onclick="deleteFile(\''.$f->getRelativePath().'\');"><i class="fa fa-trash"></i></span></p>';
                }
                echo '</li>';
                echo <<<HTML
<div class="col-sm-4">
	$html
</div>
HTML;
            }
            echo '</div> ';
        }
        else
        {
            echo '<p><br></p><div class="panel panel-info"><i class="fa fa-info-circle"></i> No files.</div> ';
        }
    }

}