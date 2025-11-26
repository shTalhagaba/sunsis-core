<?php
class read_employer_v3 implements IAction
{
    public $id = NULL;
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

        if($id == '')
            throw new Exception('Missing querystring argument: employer id');

        $vo = Employer::loadFromDatabase($link, $id);
        if(is_null($vo))
            throw new Exception('Employer record not found.');

        $this->id = $id;

        $_SESSION['bc']->add($link, "do.php?_action=read_employer_v3&id={$vo->id}", "View Employer");

        $sector = DAO::getSingleValue($link, "SELECT description FROM lookup_sector_types WHERE id = '{$vo->sector}'");
        $group_employer = DAO::getSingleValue($link, "SELECT title FROM brands WHERE id = '{$vo->manufacturer}'");
        $size = DAO::getSingleValue($link, "SELECT description FROM lookup_employer_size WHERE code = '{$vo->code}'");

        $locations_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM locations WHERE locations.organisations_id = '{$vo->id}'");
        $learners_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.type = '" . User::TYPE_LEARNER . "' AND users.employer_id = '{$vo->id}'");
	if(DB_NAME == "am_duplex" && $_SESSION['user']->employer_id == 3278)
        {
            $learners_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.type = '" . User::TYPE_LEARNER . "' AND users.employer_id = '{$vo->id}' AND users.id IN (SELECT learner_id FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.venue = 'Peterborough Skills Academy')");
        }
        $users_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.type != '" . User::TYPE_LEARNER . "' AND users.employer_id = '{$vo->id}'");
        $crm_notes_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_notes_orgs WHERE crm_notes_orgs.organisation_id = '{$vo->id}'");
        $crm_contacts_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM organisation_contact WHERE organisation_contact.org_id = '{$vo->id}'");
        $agreements_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM employer_agreements WHERE employer_id = '{$vo->id}'");
        $hs_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM health_safety WHERE location_id IN (SELECT locations.id FROM locations WHERE locations.organisations_id = '{$vo->id}')");
        if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
        {
            $learner_complaints_count = DAO::getSingleValue($link, "SELECT DISTINCT COUNT(*) FROM complaints INNER JOIN tr ON complaints.record_id = tr.id WHERE tr.employer_id = '{$vo->id}'");
            $complaints_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM complaints WHERE record_id = '{$vo->id}' AND complaint_type = '" . Complaint::EMPLOYER_COMPLAINT . "'");
        }
        $salary_rate_options = [
            0 => [0, '', null, null],
            1 => [1, 'Grade 1'],
            2 => [2, 'Grade 2'],
            3 => [3, 'Grade 3']];

        $vacancies = RecViewApprenticeVacancies::getInstance($link, $vo->id);
        $vacancies->refresh($link, $_REQUEST);

        $repository = Repository::getRoot().'/employers/'.$vo->id;
        $repository = Repository::readDirectory($repository);
        $files_count = 0;
        foreach($repository AS $file)
        {
            if($file->isDir())
                continue;
            $files_count++;
        }

        $is_tna_completed = 0;
        if(SystemConfig::getEntityValue($link, 'module_onboarding') && in_array(DB_NAME, ["am_lead", "am_lead_demo"]))
        {
            $is_tna_completed = DAO::getSingleValue($link, "SELECT COUNT(*) FROM employer_tna WHERE employer_id = '{$vo->id}' AND is_completed = 'Y'");
        }

	if(SystemConfig::getEntityValue($link, "module_crm") && in_array(DB_NAME, ["am_demo", "am_ela", "am_presentation"]))
        {
            $enquiries_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_enquiries WHERE crm_enquiries.company_type = 'employer' AND crm_enquiries.company_id = '{$vo->id}'");
            $leads_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_leads WHERE crm_leads.company_type = 'employer' AND crm_leads.company_id = '{$vo->id}'");
            $opportunities_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_opportunities WHERE crm_opportunities.company_type = 'employer' AND crm_opportunities.company_id = '{$vo->id}'");
        }

        include_once('tpl_read_employer_v3.php');
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
	(SELECT COUNT(*) FROM tr WHERE tr.employer_location_id = locations.id) AS learners
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
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN, User::TYPE_MANAGER]))
                    echo '<span class="btn btn-info btn-xs" onclick="window.location.replace(\'do.php?_action=read_location&id=' . $loc['id'] . '&back=' . $back . '&organisations_id=' . $loc['organisations_id'] . '\');"><i class="fa fa-folder-open"></i> View</span> ';
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN, User::TYPE_MANAGER]))
                    echo '<span class="btn btn-primary btn-xs" onclick="window.location.replace(\'do.php?_action=edit_location&id=' . $loc['id'] . '&organisations_id=' . $loc['organisations_id'] . '\');"><i class="fa fa-edit"></i> Edit</span> ';
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN, User::TYPE_MANAGER]))
                    echo '<span class="btn btn-primary btn-xs" onclick="window.location.replace(\'do.php?_action=edit_health_and_safety&id=' . $loc['id'] . '&back=' . $back . '&organisation_id=' . $loc['organisations_id'] . '\');"><i class="fa fa-support"></i> Health & Safety</span> ';
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

    private function renderLearners(PDO $link)
    {
        $sql = <<<HEREDOC
SELECT
	users.id, users.username, (SELECT COUNT(*) FROM tr WHERE tr.username = users.username) AS trs
FROM
	users
WHERE users.type = '5' AND users.employer_id='$this->id'
ORDER BY users.firstnames;
HEREDOC;
        if(DB_NAME == "am_duplex" && $_SESSION['user']->employer_id == 3278)
        {
            $sql = new SQLStatement($sql);
            $sql->setClause("WHERE users.id IN (SELECT learner_id FROM training INNER JOIN crm_training_schedule ON training.schedule_id = crm_training_schedule.id WHERE crm_training_schedule.venue = 'Peterborough Skills Academy')");
        }
	$st = $link->query($sql);
        if($st)
        {
            echo '<div class="row is-flex">';
            while($row = $st->fetch())
            {
                $learner = User::loadFromDatabase($link, $row['username']);
                $photopath = $learner->getPhotoPath();
                if($photopath)
                    $photopath = "do.php?_action=display_image&username=".rawurlencode($learner->username);
                else
                    $photopath = "/images/no_photo.png";
                $address = $learner->home_address_line_1 != '' ? '<br>' . $learner->home_address_line_1 . '<br>' : '';
                $address .= $learner->home_address_line_2 != '' ? $learner->home_address_line_2 . '<br>' : '';
                $address .= $learner->home_address_line_3 != '' ? $learner->home_address_line_3 . '<br>' : '';
                $address .= $learner->home_address_line_4 != '' ? $learner->home_address_line_4 : '';
                $address .= $learner->home_postcode != '' ? '<br><i class="fa fa-map-marker text-green"></i> ' . $learner->home_postcode . '<br>' : '';
                $address .= $learner->home_telephone != '' ? '<i class="fa fa-phone text-green"></i> ' . $learner->home_telephone . '<br>' : '';
                $address .= $learner->home_mobile != '' ? '<i class="fa fa-mobile text-green"></i> ' . $learner->home_mobile . '<br>' : '';
                $address .= $learner->home_email != '' ? '<i class="fa fa-envelope text-green"></i> <a href="mailto:'.$learner->home_email.'">' . $learner->home_email . '</a><br>' : '';
                $trs = '<i class="fa fa-graduation-cap text-green" title="Number of training records"></i> ' . $row['trs'];

                $view = '';
                $edit = '';
                $delete = '';
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN]))
                    $view = '<span class="btn btn-info btn-xs" onclick="window.location.href=\'do.php?_action=read_learner&username='.$learner->username.'&id='.$learner->id.'\'"><i class="fa fa-folder-open"></i> View</span>';
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN]))
                    $edit = in_array(DB_NAME, ["am_duplex"]) ?
                        '<span class="btn btn-primary btn-xs" onclick="window.location.href=\'do.php?_action=edit_learner_duplex&id='.$learner->id.'&organisations_id='.$learner->employer_id.'\'"><i class="fa fa-edit"></i> Edit</span>' :
                        '<span class="btn btn-primary btn-xs" onclick="window.location.href=\'do.php?_action=edit_learner&username='.$learner->username.'&location_id='.$learner->employer_location_id.'&organisations_id='.$learner->employer_id.'\'"><i class="fa fa-edit"></i> Edit</span>';
                if($row['trs'] == 0 && ($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN])))
                    $delete = '<span class="btn btn-danger btn-xs pull-right" onclick="deleteRecord(\'learner\', \''.$learner->username.'\', \''.$learner->id.'\', \''.$learner->firstnames . ' ' . $learner->surname.'\');"><i class="fa fa-trash"></i> Delete</span>';

                echo <<<HTML
<div class="col-sm-3">
	<div class="box box-primary bg-gray-light">
		<div class="box-body box-profile">
			<img class="profile-user-img img-responsive img-circle" src="{$photopath}" alt="Learner's profile picture">
			<span class="profile-username">{$learner->firstnames} {$learner->surname}</span>
			$address
			<br>
			$trs
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

    private function renderObLearners(PDO $link)
    {
        $sql = <<<HEREDOC
SELECT
	ob_learners.*,
	(SELECT subject FROM onboarding_log WHERE ob_learner_id = ob_learners.id ORDER BY created DESC LIMIT 1) AS ob_status
FROM
	ob_learners
WHERE ob_learners.employer_id='$this->id'
ORDER BY ob_learners.firstnames;
HEREDOC;
        $st = $link->query($sql);
        if($st)
        {
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered table-striped" id="tblObLearners"><caption class="text-bold text-center">'.$st->rowCount().' records</caption>';
            echo '<tr><th>Status</th><th>Program</th><th>Creation Date</th><th>Firstnames</th><th>Surname</th><th>Gender</th><th>DOB</th><th>Home Postcode</th><th>Email</th><th>Location</th></tr>';
            echo '<tbody>';
            $listAssessments = OnboardingHelper::getAssessmentTypesList();
            while($row = $st->fetch())
            {
                $location = Location::loadFromDatabase($link, $row['employer_location_id']);
                echo HTML::viewrow_opening_tag('do.php?_action=read_ob_learner&id=' . $row['id'], "small");
                echo '<td><label class="label label-info">' . $row['ob_status'] . '</label></td>';
                echo isset($listAssessments[$row['ks_assessment']]) ? '<td>' . $listAssessments[$row['ks_assessment']] . '</td>' : '<td>' . $row['ks_assessment'] . '</td>';
                echo '<td>' . Date::to($row['created'], DAte::DATETIME) . '</td>';
                echo '<td>' . $row['firstnames'] . '</td>';
                echo '<td>' . $row['surname'] . '</td>';
                echo '<td>' . $row['gender'] . '</td>';
                echo '<td>' . Date::toShort($row['dob']) . '</td>';
                echo '<td>' . $row['home_postcode'] . '</td>';
                echo '<td><a href="mailto:' . $row['home_email'] . '">' . $row['home_email'] . '</a></td>';
                echo '<td>' . $location->full_name . ', ' . $location->address_line_1 . ' ' . $location->postcode . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>  ';
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
	crm_notes_orgs.`id` AS id,
	crm_notes_orgs.`organisation_id`,
	organisation_contact.`contact_name` AS name_of_person,
	organisation_contact.`job_role`,
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
	LEFT JOIN organisation_contact ON organisation_contact.`contact_id` = crm_notes_orgs.`org_contact_id`
WHERE
	crm_notes_orgs.organisation_id = $this->id
ORDER BY crm_notes_orgs.contact_date DESC
HEREDOC;
        /* @var $result pdo_result */
        $st = $link->query($sql);
        if($st)
        {
            echo '<table class="table table-bordered table-striped">';
            echo '<thead><tr><th>Date</th><th>Person contacted</th><th>Type of Contact</th><th>Subject</th><th>By whom</th><th> Position </th><th style="width: 25%;">Agreed Action</th><th>Audit Info</th></thead>';
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
                //echo '<td align="left">' . HTML::cell($row['job_role']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['type_of_contact']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['subject']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['by_whom']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['by_whom_position']) . "</td>";
                echo '<td align="left"><small>' . nl2br((string) $row['agreed_action']) . "</small></td>";
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

    private function renderHS(PDO $link, Employer $vo)
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
                echo HTML::viewrow_opening_tag('do.php?_action=edit_health_and_safety&id=' . $row['location_id'] . '&back=read_employer_v3&organisation_id=' . $row['organisations_id']);
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
SELECT organisation_contact.*, (SELECT COUNT(*) FROM crm_notes_orgs WHERE org_contact_id = organisation_contact.contact_id) AS crm_notes_count FROM organisation_contact WHERE org_id='$this->id' ORDER BY contact_name;
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

    private function learnersWithAgeGrant(PDO $link, $employer_id)
    {
        $sql = <<<HEREDOC
SELECT
	users.surname, users.firstnames, users.username, organisations.legal_name,
	locations.full_name, locations.telephone, users.gender

FROM
	users LEFT JOIN organisations ON users.employer_id = organisations.id
	LEFT JOIN locations ON users.employer_location_id = locations.id
WHERE type = '5' AND users.age_grant = 1 AND users.employer_id='$employer_id';

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

    public function isAbleToDelete()
    {
        if($_SESSION['user']->isAdmin())
            return true;

        return false;
    }

    public function renderFileRepository(PDO $link, Employer $vo)
    {
        $repository = Repository::getRoot().'/employers/'.$vo->id;
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
                $html .= '<i class="fa '.$image.'"></i> ' . htmlspecialchars((string)$f->getName());
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

    public function renderComposeNewMessageBox(PDO $link, $vo)
    {
        $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type IN ('EMPLOYER_TNA', 'REMINDER_EMPLOYER_TNA', 'SUCCESSFUL_SCREENING', 'UNSUCCESSFUL_SCREENING');");
        if(SystemConfig::getEntityValue($link, 'module_crm'))
        {
            if(DB_NAME == "am_duplex")
                $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type IN ('INITIAL_MARKETING_EMAIL', 'REMINDER_INITIAL_CONTACT', 'LEVEL3_THANKS_BOOKING', 'LEVEL4_THANKS_BOOKING');");
            if(SOURCE_LOCAL)
                $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates;");
        }	
        array_unshift($email_templates, array('','Email template:',''));
        $ddlTemplates =  HTML::selectChosen('frmEmailTemplate', $email_templates, '', false);
        $from_email = $_SESSION['user']->work_email == '' ? SystemConfig::getEntityValue($link, 'onboarding_email') : $_SESSION['user']->work_email;

        $html = <<<HTML
<form name="frmEmail" id="frmEmail" action="do.php?_action=ajax_actions" method="post">
	<input type="hidden" name="subaction" value="sendEmail" />
	<input type="hidden" name="frmEmailEntityType" value="employers" />
	<input type="hidden" name="frmEmailEntityId" value="$vo->id" />
	<div class="box box-primary">
		<div class="box-header with-border"><h2 class="box-title">Compose New Email</h2></div>
		<div class="box-body">
			<div class="form-group"><div class="row"> <div class="col-sm-8"> $ddlTemplates </div><div class="col-sm-4"> <span class="btn btn-sm btn-default" onclick="load_email_template_in_frmEmail();">Load template</span></div> </div></div>
			<div class="form-group">To: <input name="frmEmailTo" id="frmEmailTo" class="form-control compulsory" placeholder="To:" value=""></div>
			<div class="form-group">From: <input name="frmEmailFrom" id="frmEmailFrom" class="form-control compulsory" placeholder="From:" value="{$from_email}"></div>
			<div class="form-group">Subject: <input name="frmEmailSubject" id="frmEmailSubject" class="form-control compulsory" placeholder="Subject:"></div>
			<div class="form-group"><textarea name="frmEmailBody" id="frmEmailBody" class="form-control compulsory" style="height: 300px"></textarea></div>
		</div>
		<div class="box-footer">
			<div class="pull-right"><span class="btn btn-primary" onclick="sendEmail();"><i class="fa fa-envelope-o"></i> Send</span></div>
			<span class="btn btn-default" onclick="$('#btnCompose').show(); $('#mailBox').show(); $('#composeNewMessageBox').hide();"><i class="fa fa-times"></i> Discard</span>
		</div>
	</div>
</form>
HTML;

        return $html;
    }

    public function showSentEmails(PDO $link, Employer $vo)
    {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered small">';
        $result = DAO::getResultset($link, "SELECT * FROM emails WHERE emails.entity_type = 'employer' AND emails.entity_id = '{$vo->id}' ORDER BY created DESC", DAO::FETCH_ASSOC);
        echo '<caption class="lead text-bold text-center">Sent Emails (' . count($result) . ')</caption>';
        echo '<tr><th>Email Type</th><th>DateTime</th><th>By</th><th>To Address</th><th>Subject</th><th>Email</th></tr>';
        foreach($result AS $row)
        {
            echo '<tr>';
            echo '<td>' . DAO::getSingleValue($link, "SELECT template_type FROM email_templates WHERE id = '{$row['email_type']}'") . '</td>';
            echo '<td>' . Date::to($row['created'], Date::DATETIME) . '</td>';
            echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = '{$row['by_whom']}'") . '</td>';
            echo '<td>' . $row['email_to'] . '</td>';
            echo '<td>' . $row['email_subject'] . '</td>';
            echo '<td><span class="btn btn-xs btn-info" onclick="viewEmail(\''.$row['id'].'\');"><i class="fa fa-eye"></i> View Email</span> </td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }

    private function renderAgreements(PDO $link, Employer $vo)
	{
		$sql = <<<HEREDOC
SELECT
  employer_agreements.*
FROM
  employer_agreements 
WHERE
  employer_agreements.employer_id = '{$vo->id}'
ORDER BY
  employer_agreements.`id`
;
HEREDOC;
		/* @var $result pdo_result */
		$st = $link->query($sql);
		if($st)
		{
			echo '<table class="table table-bordered ">';
			echo '<thead><tr><th>Status</th><th>Creation Date</th><th>Created By</th><th>Expiry Date</th><th>Actions</th></thead>';
			echo '<tbody>';
			if($st->rowCount() > 0)
            {
                while($row = $st->fetch())
                {
                    echo '<tr>';
                    echo '<td>';
                    if($row['status'] == EmployerAgreement::TYPE_CREATED)
                        echo '<label class="label label-info">CREATED</label>';
                    if($row['status'] == EmployerAgreement::TYPE_SENT)
                        echo '<label class="label label-warning">SENT TO EMPLOYER</label>';
                    if($row['status'] == EmployerAgreement::TYPE_SIGNED_BY_EMPLOYER)
                        echo '<label class="label label-success">SIGNED BY EMPLOYER</label>';
                    if($row['status'] == EmployerAgreement::TYPE_COMPLETED)
                        echo '<label class="label label-success">COMPLETED</label>';
                    echo '</td>';
                    echo '<td align="left">' . Date::to($row['created'], Date::DATETIME) . "</td>";
                    echo '<td align="left">' . HTML::cell(DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'")) . "</td>";
                    echo '<td align="left">' . Date::toShort($row['expiry_date']) . "</td>";
                    echo '<td>';
                    echo '<span class="btn btn-xs btn-primary" onclick="window.location.href=\'do.php?_action=view_employer_agreement&id='.$row['id'].'&employer_id='.$row['employer_id'].'\'"><i class="fa fa-folder-open"></i> View</span> &nbsp;';
                    if(in_array($row['status'], [EmployerAgreement::TYPE_CREATED]))
                    {
                        echo '<span class="btn btn-xs btn-primary" onclick="window.location.href=\'do.php?_action=edit_employer_agreement&id='.$row['id'].'&employer_id='.$row['employer_id'].'\'"><i class="fa fa-edit"></i> Edit</span> &nbsp;';
                        echo '<span class="btn btn-xs btn-primary" onclick="load_and_prepare_agreement_email(\''.$row['id'].'\');"><i class="fa fa-envelope"></i> Email</span> &nbsp;';
                        echo '<span class="btn btn-xs btn-danger" onclick="deleteRecord(\'agreement\', \'Creation Date '.Date::to($row['created'], Date::DATETIME).'\', \''.$row['id'].'\', \'\');"><i class="fa fa-trash"></i> Delete</span> &nbsp;';
                    }
                    elseif(in_array($row['status'], [EmployerAgreement::TYPE_SENT]))
                    {
                        echo '<span class="btn btn-xs btn-primary" disabled="disabled"><i class="fa fa-edit"></i> Edit</span> &nbsp;';
                        echo '<span class="btn btn-xs btn-primary" onclick="load_and_prepare_agreement_email(\''.$row['id'].'\');"><i class="fa fa-envelope"></i> Email</span> &nbsp;';
                        echo '<span class="btn btn-xs btn-danger" disabled="disabled"><i class="fa fa-trash"></i> Delete</span> &nbsp;';
                    }
                    elseif(in_array($row['status'], [EmployerAgreement::TYPE_SIGNED_BY_EMPLOYER]))
                    {
                        echo '<span class="btn btn-xs btn-primary" disabled="disabled"><i class="fa fa-edit"></i> Edit</span> &nbsp;';
                        echo '<span class="btn btn-xs btn-primary" disabled="disabled"><i class="fa fa-envelope"></i> Email</span> &nbsp;';
                        echo '<span class="btn btn-xs btn-danger" disabled="disabled"><i class="fa fa-trash"></i> Delete</span> &nbsp;';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
            }
			else
            {
                echo '<tr><td colspan="5"><i>No records found.</i></td></tr>';
            }
			echo '</tbody></table>';
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	public function renderEnquiries(PDO $link, $employer_id)
    {
        $sql = <<<HEREDOC
        SELECT DISTINCT
  crm_enquiries.*,
  CONCAT(firstnames, ' ', surname) AS created_by_name  
FROM
  crm_enquiries
  LEFT JOIN users ON crm_enquiries.created_by = users.`id`
WHERE company_id = '$employer_id'
  AND company_type = 'employer'
ORDER BY modified DESC,
  enquiry_title;
HEREDOC;
        /* @var $result pdo_result */
        $st = $link->query($sql);
        if($st)
        {
            echo '<table class="table table-responsive table-bordered">';
            echo '<thead><tr><th>ID</th><th>Last Modified</th><th>Created</th><th>Owner</th><th>Title</th><th>Status</th><th>Type</th><th>Source</th><th>Contact Person</th></tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag("do.php?_action=read_enquiry&id={$row['id']}");
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . Date::to($row['modified'], Date::DATETIME) . '</td>';
                echo '<td>' . Date::to($row['created'], Date::DATETIME) . '</td>';
                echo '<td>' . $row['created_by_name'] . '</td>';
                echo '<td>' . $row['enquiry_title'] . '</td>';
                echo '<td>' . Enquiry::getListEnquiryStatus($row['status']) . '</td>';
                echo '<td>' . Enquiry::getListEnquiryType($row['enquiry_type']) . '</td>';
                echo '<td>' . $row['source'] . '</td>';
                $contacts_table_name = $row['company_type'] == 'pool' ? 'pool_contact' : ($row['company_type'] == 'employer' ? 'organisation_contact' : '');
                $contact_person = DAO::getObject($link, "SELECT * FROM {$contacts_table_name} WHERE contact_id = '{$row['main_contact_id']}'");
                if(isset($contact_person->contact_id))
                {
                    echo '<td>';
                    echo $contact_person->contact_title . ' ' . $contact_person->contact_name . '<br>';
                    echo $contact_person->job_title != '' ?  $contact_person->job_title . '<br>' : '';
                    echo $contact_person->job_role != '' ?  $contact_person->job_role . '<br>' : '';
                    echo $contact_person->contact_telephone != '' ?  $contact_person->contact_telephone . '<br>' : '';
                    echo $contact_person->contact_mobile != '' ?  $contact_person->contact_mobile . '<br>' : '';
                    echo $contact_person->decision_maker == '1' ?  'Decision Maker: Yes' . $contact_person->decision_maker : '';
                    echo '</td>';
                }
                else
                {
                    echo '<td></td>';
                }
                echo '</tr>';
            }
            if($st->rowCount() == 0)
            {
                echo '<tr><td colspan="9"><i class="fa fa-info-circle"></i> No enquiries found.</td></tr>';
            }
            echo '</tbody></table> ';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public function renderLeads(PDO $link, $employer_id)
    {
        $sql = <<<HEREDOC
        SELECT DISTINCT
        crm_leads.*,
  CONCAT(firstnames, ' ', surname) AS created_by_name  
FROM
  crm_leads
  LEFT JOIN users ON crm_leads.created_by = users.`id`
WHERE company_id = '$employer_id'
  AND company_type = 'employer'
ORDER BY modified DESC,
  lead_title;
HEREDOC;
        /* @var $result pdo_result */
        $st = $link->query($sql);
        if($st)
        {
            echo '<table class="table table-responsive table-bordered">';
            echo '<thead><tr><th>ID</th><th>Last Modified</th><th>Created</th><th>Owner</th><th>Title</th><th>Status</th><th>Type</th><th>Source</th><th>Contact Person</th></tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag("do.php?_action=read_lead&id={$row['id']}");
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . Date::to($row['modified'], Date::DATETIME) . '</td>';
                echo '<td>' . Date::to($row['created'], Date::DATETIME) . '</td>';
                echo '<td>' . $row['created_by_name'] . '</td>';
                echo '<td>' . $row['lead_title'] . '</td>';
                echo '<td>' . Lead::getListLeadStatus($row['status']) . '</td>';
                echo '<td>' . Enquiry::getListEnquiryType($row['lead_type']) . '</td>';
                echo '<td>' . $row['source'] . '</td>';
                $contacts_table_name = $row['company_type'] == 'pool' ? 'pool_contact' : ($row['company_type'] == 'employer' ? 'organisation_contact' : '');
                $contact_person = DAO::getObject($link, "SELECT * FROM {$contacts_table_name} WHERE contact_id = '{$row['main_contact_id']}'");
                if(isset($contact_person->contact_id))
                {
                    echo '<td>';
                    echo $contact_person->contact_title . ' ' . $contact_person->contact_name . '<br>';
                    echo $contact_person->job_title != '' ?  $contact_person->job_title . '<br>' : '';
                    echo $contact_person->job_role != '' ?  $contact_person->job_role . '<br>' : '';
                    echo $contact_person->contact_telephone != '' ?  $contact_person->contact_telephone . '<br>' : '';
                    echo $contact_person->contact_mobile != '' ?  $contact_person->contact_mobile . '<br>' : '';
                    echo $contact_person->decision_maker == '1' ?  'Decision Maker: Yes' . $contact_person->decision_maker : '';
                    echo '</td>';
                }
                else
                {
                    echo '<td></td>';
                }
                echo '</tr>';
            }
            if($st->rowCount() == 0)
            {
                echo '<tr><td colspan="9"><i class="fa fa-info-circle"></i> No enquiries found.</td></tr>';
            }
            echo '</tbody></table> ';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }
    
    
    public function renderOpportunities(PDO $link, $employer_id)
    {
        $sql = <<<HEREDOC
        SELECT DISTINCT
        crm_opportunities.*,
  CONCAT(firstnames, ' ', surname) AS created_by_name  
FROM
crm_opportunities
  LEFT JOIN users ON crm_opportunities.created_by = users.`id`
WHERE company_id = '$employer_id'
  AND company_type = 'employer'
ORDER BY modified DESC,
opportunity_title;
HEREDOC;
        /* @var $result pdo_result */
        $st = $link->query($sql);
        if($st)
        {
            echo '<table class="table table-responsive table-bordered">';
            echo '<thead><tr><th>ID</th><th>Last Modified</th><th>Created</th><th>Owner</th><th>Title</th><th>Status</th><th>Estimated Closed Date</th><th>Estimated Revenue</th><th>Source</th><th>Contact Person</th></tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag("do.php?_action=read_opportunity&id={$row['id']}");
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . Date::to($row['modified'], Date::DATETIME) . '</td>';
                echo '<td>' . Date::to($row['created'], Date::DATETIME) . '</td>';
                echo '<td>' . $row['created_by_name'] . '</td>';
                echo '<td>' . $row['opportunity_title'] . '</td>';
                echo '<td>' . Lead::getListLeadStatus($row['status']) . '</td>';
                echo '<td>' . Date::toShort($row['est_closed_date']) . '</td>';
                echo '<td>' . $row['est_revenue'] . '</td>';
                echo '<td>' . $row['source'] . '</td>';
                $contacts_table_name = $row['company_type'] == 'pool' ? 'pool_contact' : ($row['company_type'] == 'employer' ? 'organisation_contact' : '');
                $contact_person = DAO::getObject($link, "SELECT * FROM {$contacts_table_name} WHERE contact_id = '{$row['main_contact_id']}'");
                if(isset($contact_person->contact_id))
                {
                    echo '<td>';
                    echo $contact_person->contact_title . ' ' . $contact_person->contact_name . '<br>';
                    echo $contact_person->job_title != '' ?  $contact_person->job_title . '<br>' : '';
                    echo $contact_person->job_role != '' ?  $contact_person->job_role . '<br>' : '';
                    echo $contact_person->contact_telephone != '' ?  $contact_person->contact_telephone . '<br>' : '';
                    echo $contact_person->contact_mobile != '' ?  $contact_person->contact_mobile . '<br>' : '';
                    echo $contact_person->decision_maker == '1' ?  'Decision Maker: Yes' . $contact_person->decision_maker : '';
                    echo '</td>';
                }
                else
                {
                    echo '<td></td>';
                }
                echo '</tr>';
            }
            if($st->rowCount() == 0)
            {
                echo '<tr><td colspan="9"><i class="fa fa-info-circle"></i> No enquiries found.</td></tr>';
            }
            echo '</tbody></table> ';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }	

}