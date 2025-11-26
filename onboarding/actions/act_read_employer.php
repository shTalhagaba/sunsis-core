<?php
class read_employer implements IAction
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

		$_SESSION['bc']->add($link, "do.php?_action=read_employer&id={$vo->id}", "View Employer");

		$sector = DAO::getSingleValue($link, "SELECT description FROM lookup_sector_types WHERE id = '{$vo->sector}'");
		$size = DAO::getSingleValue($link, "SELECT description FROM lookup_employer_size WHERE code = '{$vo->code}'");

		$locations_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM locations WHERE locations.organisations_id = '{$vo->id}'");
		$learners_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.type = '" . User::TYPE_LEARNER . "' AND users.employer_id = '{$vo->id}'");
		$users_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.type != '" . User::TYPE_LEARNER . "' AND users.employer_id = '{$vo->id}'");
		$crm_notes_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_notes_orgs WHERE crm_notes_orgs.organisation_id = '{$vo->id}'");
		$crm_contacts_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM organisation_contacts WHERE organisation_contacts.org_id = '{$vo->id}'");
		$hs_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM health_safety WHERE employer_id = '{$vo->id}'");
		$agreements_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM employer_agreements WHERE employer_id = '{$vo->id}'");
		$ob_learners_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learners WHERE employer_id = '{$vo->id}'");
		if(DB_NAME == "am_ela")
		{
			if($_SESSION['user']->learners_caseload == 0)
            {
                // do nothing
            }
            elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_FRONTLINE)
            {
				$ob_learners_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learners WHERE ob_learners.employer_id = '{$vo->id}' AND ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_FRONTLINE . "'");
            }
            elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_LINKS_TRAINING)
            {
				$ob_learners_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learners WHERE ob_learners.employer_id = '{$vo->id}' AND ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_LINKS_TRAINING . "'");
            }
		elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_NEW_ACCESS)
            {
				$ob_learners_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learners WHERE ob_learners.employer_id = '{$vo->id}' AND ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_NEW_ACCESS . "'");
            }
            elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_INTERNAL_ELA)
            {
				$ob_learners_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM ob_learners WHERE ob_learners.employer_id = '{$vo->id}' AND ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_INTERNAL_ELA . "'");
            }
		}
		$sent_emails_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM emails WHERE emails.entity_type = 'organisations' AND emails.entity_id = '{$vo->id}' ORDER BY created DESC");
		$salary_rate_options = [
			0 => [0, '', null, null],
			1 => [1, 'Grade 1'],
			2 => [2, 'Grade 2'],
			3 => [3, 'Grade 3']];


		$repository = Repository::getRoot().'/employers/'.$vo->id;
		$repository = Repository::readDirectory($repository);
		$files_count = 0;
		foreach($repository AS $file)
		{
			if($file->isDir())
				continue;
			$files_count++;
		}

		$employer_agreement = $vo->getLatestAgreement($link);

		if(DB_NAME == "am_ela")
		{
			$health_safety = DAO::getObject($link, "SELECT * FROM health_safety WHERE health_safety.`employer_id` = '{$vo->id}' ORDER BY id DESC LIMIT 1");
		}

        $mainLocation = $vo->getMainLocation($link);

        $primary_contact_email_sql = <<<SQL
SELECT
  organisation_contacts.`contact_email`
FROM
  organisation_contacts
WHERE organisation_contacts.`org_id` = '{$vo->id}'
  AND organisation_contacts.`job_role` = 99
  AND organisation_contacts.`contact_email` IS NOT NULL
ORDER BY organisation_contacts.`contact_id` DESC
LIMIT 1;
SQL;
        $primary_contact_email = DAO::getSingleValue($link, $primary_contact_email_sql);
        if($primary_contact_email == '')
            $primary_contact_email = $mainLocation->contact_email;

        
        include_once('tpl_read_employer.php');
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
	(SELECT COUNT(*) FROM ob_tr WHERE ob_tr.employer_location_id = locations.id) AS learners
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
				echo '<span class="text-bold">Main Contact Person Details: </span></br>';
				echo $loc['contact_name'] . '</br>';
				if($loc['contact_email'] != '')
				    echo '<i class="fa fa-envelope"></i> ' . $loc['contact_email'] . '<br>';
				if($loc['contact_telephone'] != '')
				    echo '<i class="fa fa-phone"></i> ' . $loc['contact_telephone'] . '<br>';
				if($loc['contact_mobile'] != '')
				    echo '<i class="fa fa-mobile-phone"></i> ' . $loc['contact_mobile'] . '<br>';
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
				$address .= $learner->home_address_line_4 != '' ? $learner->home_address_line_4 . '<br>' : '';
				$address .= $learner->home_postcode != '' ? '<i class="fa fa-map-marker text-green"></i> ' . $learner->home_postcode . '<br>' : '';
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
					$edit = '<span class="btn btn-primary btn-xs" onclick="window.location.href=\'do.php?_action=edit_learner&username='.$learner->username.'&location_id='.$learner->employer_location_id.'&organisations_id='.$learner->employer_id.'\'"><i class="fa fa-edit"></i> Edit</span>';
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
		$where = "";
        if(DB_NAME == "am_ela")
        {
            if($_SESSION['user']->learners_caseload == 0)
            {
                $where = "";
            }
            elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_FRONTLINE)
            {
                $where = " AND ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_FRONTLINE . "'";
            }
            elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_LINKS_TRAINING)
            {
                $where = " AND ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_LINKS_TRAINING . "'";
            }
	    elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_NEW_ACCESS)
            {
                $where = " AND ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_NEW_ACCESS . "'";
            }
	    elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_INTERNAL_ELA)
            {
                $where = " AND ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_INTERNAL_ELA . "'";
            }
        }

		$sql = <<<HEREDOC
SELECT DISTINCT
    ob_learners.id,
	organisations.legal_name AS employer,
    ob_learners.firstnames,
    ob_learners.surname,
    DATE_FORMAT(ob_learners.dob, '%d/%m/%Y') AS date_of_birth,
    ob_learners.home_postcode AS postcode,
    ob_learners.home_email AS email,
    frameworks.title AS standard,
    ob_tr.status_code,
    ob_tr.id AS tr_id,
    ob_tr.provider_id,
	CASE ob_tr.status_code
		WHEN 1 THEN 'In Progress'
		WHEN 2 THEN 'Completed'
		WHEN 3 THEN 'Archived'
		WHEN 4 THEN 'Converted'
		WHEN 5 THEN 'Not Progressed'
		WHEN 6 THEN 'Change of Employer'
		ELSE ''
	END AS training_status
FROM
	ob_learners
	LEFT JOIN organisations ON ob_learners.`employer_id` = organisations.`id`
	LEFT JOIN ob_tr ON ob_learners.id = ob_tr.ob_learner_id 
	LEFT JOIN ob_learner_skills_analysis ON ob_tr.`id` = ob_learner_skills_analysis.`tr_id`
	LEFT JOIN frameworks ON ob_tr.`framework_id` = frameworks.`id`
WHERE organisations.id='$this->id'
$where
ORDER BY ob_learners.firstnames;
HEREDOC;
		$st = $link->query($sql);
		if($st)
		{
			echo '<div class="table-responsive">';
			echo '<table class="table table-bordered table-striped" id="tblObLearners"><caption class="text-bold text-center">'.$st->rowCount().' records</caption>';
			echo <<<HTML
	<tr>
		<th class="bottomRow">Firstnames</th>
		<th class="bottomRow">Surname</th>
		<!-- <th class="bottomRow">Date of Birth</th> -->
		<th class="bottomRow">Postcode</th>
		<th class="bottomRow">Email</th>
		<th class="bottomRow">Standard</th>
		<th class="bottomRow">Employer</th>
		<th class="bottomRow">Training Status</th>
	</tr>
HTML;
        	echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=view_ob_learner&id=' . $row['id'], "small");
                echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
                echo '<td>' . HTML::cell($row['surname']) . '</td>';
                // echo '<td>' . Date::toShort($row['date_of_birth']) . '</td>';
                echo '<td>' . HTML::cell($row['postcode']) . '</td>';
                echo '<td>' . HTML::cell($row['email']) . '</td>';
                echo '<td>' . HTML::cell($row['standard']) . '</td>';
                echo '<td>' . HTML::cell($row['employer']) . '</td>';
                echo '<td>' . HTML::cell($row['training_status']) . '</td>';
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

	private function renderHS(PDO $link, Employer $vo)
	{
		$sql = <<<HEREDOC
SELECT
health_safety.`id`,
`employer_id`,
`location_id`,
DATE_FORMAT(`last_assessment`, '%d/%m/%Y') AS `last_assessment`,
DATE_FORMAT(`next_assessment`, '%d/%m/%Y') AS `next_assessment`,
`assessor`,
`comments`,
CASE `complient`
	WHEN '1' THEN 'Compliant'
	WHEN '2' THEN 'Non-Compliant'
	WHEN '3' THEN 'Outstanding Action'
	ELSE ''
END AS `complient`,
IF(`paperwork_received` = 1, 'Yes', 'No') AS `paperwork_received`,
CASE `age_range`
	WHEN '1' THEN 'Red'
	WHEN '2' THEN 'Amber'
	WHEN '3' THEN 'Green'
	ELSE ''
END AS `rag_status`,
DATE_FORMAT(`pl_date`, '%d/%m/%Y') AS `pl_date`,
`pl_insurance`,
DATE_FORMAT(`el_date`, '%d/%m/%Y') AS `el_date`,
`el_insurance`,
`employer_rep`,
`assessment_type`,
`assessment_type_other`,
CASE `recommendation`
	WHEN '1' THEN 'Suitable'
	WHEN '2' THEN 'Suitable with Action Plan'
	WHEN '3' THEN 'Unsuitable'
	ELSE ''
END AS `recommendation`,
`risk_category`,
`hs_contact_person`,
  locations.`full_name`,
  locations.`address_line_1`,
  locations.`address_line_2`,
  locations.`address_line_3`,
  locations.`address_line_4`,
  locations.`postcode`,
  locations.organisations_id,
  health_safety.employer_sign,
  health_safety.employer_sign_name,
  health_safety.employer_sign_date,
  health_safety.provider_sign,
  health_safety.provider_sign_name,
  health_safety.provider_sign_date,
  health_safety.verifier_sign
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
			while($row = $st->fetch())
			{
				$assessment_type = '';
				if($row['assessment_type'] == 1)
				{
					$assessment_type = 'Inital Assessment';
				}
				elseif($row['assessment_type'] == 2)
				{
					$assessment_type = 'Re-Assessment';
				}
				elseif($row['assessment_type'] == 3)
				{
					$assessment_type = $row['assessment_type_other'];
				}

				$employer_signed = $row['employer_sign'] != '' ? '<span class="label label-success">Employer has signed</span>' : '';
				$provider_signed = $row['provider_sign'] != '' ? '<span class="label label-success">Provider has signed</span>' : '';
				$verifier_signed = $row['verifier_sign'] != '' ? '<span class="label label-success">Verifier has signed</span>' : '';

				$download_pdf = '';
				if($employer_signed != '' && $provider_signed != '')
				{
					$download_pdf = '<span class="btn btn-xs btn-info" onclick="download_hs_form_pdf(\'' . $row['id'] . '\');"><i class="fa fa-file-pdf-o"></i> Download</span>';
				}

				$_emp_sign_details = '';
				$_emp_sign_details .= $row['employer_sign'] != '' ?
					'<img src="do.php?_action=generate_image&'.$row['employer_sign'].'" style="border: 2px solid;border-radius: 15px;" /><br>' :
					'<img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" /><br>';
				$_emp_sign_details .= $row['employer_sign_name'] . '<br>';
				$_emp_sign_details .= Date::toShort($row['employer_sign_date']);
				$_prov_sign_details = '';
				$_prov_sign_details .= $row['provider_sign'] != '' ?
					'<img src="do.php?_action=generate_image&'.$row['provider_sign'].'" style="border: 2px solid;border-radius: 15px;" /><br>' :
					'<img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" /><br>';
				$_prov_sign_details .= $row['provider_sign_name'] . '<br>';
				$_prov_sign_details .= Date::toShort($row['provider_sign_date']);	

				echo <<<HTML
<div class="box box-primary">
	<div class="box-header with-border">
		<span class="box-title">
			{$assessment_type}
		</span>		
		<div class="box-tools">
			<span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=edit_employer_hs&id={$row['id']}&employer_id={$row['employer_id']}'"><i class="fa fa-edit"></i> Edit</span> &nbsp; 
			<span class="btn btn-primary btn-xs" onclick="window.location.href='do.php?_action=employer_health_safety_form&hs_id={$row['id']}'"><i class="fa fa-tasks"></i> H&S Form</span> &nbsp; 
			<span class="btn btn-xs btn-primary" onclick="load_and_prepare_hs_email('{$row['id']}');"><i class="fa fa-envelope"></i> Email</span>
			$download_pdf
		</div>
	</div>
	<div class="box-body">
		<p class="text-info">{$employer_signed} {$provider_signed} </p>
		<span class="text-bold">Location: </span> {$row['full_name']} {$row['address_line_1']} {$row['address_line_2']} {$row['address_line_3']} {$row['address_line_4']} {$row['postcode']}<br>
		<span class="text-bold">Last Assessment: </span>{$row['last_assessment']} | <span class="text-bold">Next Assessment: </span>{$row['next_assessment']} | <span class="text-bold">Assessor: </span>{$row['assessor']} | 
		<span class="text-bold">EL Date: </span>{$row['el_date']} | <span class="text-bold">EL Policy Number: </span>{$row['el_insurance']} <br>
		<span class="text-bold">PL Date: </span>{$row['pl_date']} | <span class="text-bold">PL Policy Number: </span>{$row['pl_insurance']} | <span class="text-bold">Comments: </span>{$row['comments']} <br>
		<span class="text-bold">Outcome Status: </span>{$row['complient']} | <span class="text-bold">Paperwork Received: </span>{$row['paperwork_received']} | <span class="text-bold">RAG Status: </span>{$row['rag_status']}
	</div>
	<div class="box-footer">
		<table class="table table-bordered">
			<tr><th>Employer</th><th>Provider</th></tr>
			<tr><td>{$_emp_sign_details}</td><td>{$_prov_sign_details}</td></tr>
		</table>
	</div>
</div>				
HTML;

			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
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
			echo '<thead><tr><th>Status</th><th>Creation Timestamp</th><th>Created By</th><th>Expiry Date</th><th>Funding Type</th><th>File Upload</th><th>Employer Sign, Name & Date</th><th>Provider Sign, Name & Date</th><th>Actions</th></thead>';
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
                        echo '<label class="label bg-green-gradient">COMPLETED</label>';
                    echo '</td>';
                    echo '<td align="left">' . Date::to($row['created'], Date::DATETIME) . "</td>";
                    echo '<td align="left">' . HTML::cell(DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'")) . "</td>";
                    echo '<td align="left">' . Date::toShort($row['expiry_date']) . "</td>";
					echo '<td align="left">';
					echo $row['funding_type'] != '' ? LookupHelper::getListFundingType($row['funding_type']) : '';
					echo '</td>';
                    $dir_name = Repository::getRoot() . "/employers/{$row['employer_id']}/agreements/{$row['id']}";
                    $disable_view = false;
                    if(is_dir($dir_name))
                    {
                        $files = Repository::readDirectory($dir_name);
                        if(isset($files[0]))
                        {
                            $disable_view = true;
                            echo '<td>';
                            echo '<p><a class="text-green" href="'.$files[0]->getDownloadURL().'">' . $files[0]->getName() . '</a></p>';
                            echo '</td>';
                        }
                        else
                        {
                            echo '<td>No</td>';
                        }
                    }
                    else
                    {
                        echo '<td>No</td>';
                    }
		    echo '<td>';
					echo $row['employer_sign'] != '' ?
						'<img src="do.php?_action=generate_image&'.$row['employer_sign'].'" style="border: 2px solid;border-radius: 15px;" /><br>' :
						'<img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" /><br>';
						echo $row['employer_sign_name'] . '<br>';
						echo Date::toShort($row['employer_sign_date']);
					echo '</td>';
					echo '<td>';
					echo $row['provider_sign'] != '' ?
						'<img src="do.php?_action=generate_image&'.$row['provider_sign'].'" style="border: 2px solid;border-radius: 15px;" /><br>' :
						'<img src="do.php?_action=generate_image&title=Not yet signed&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" /><br>';
						echo $row['provider_sign_name'] . '<br>';
						echo Date::toShort($row['provider_sign_date']);
					echo '</td>';	
                    echo '<td>';
                    $view_and_sign = $row['status'] == EmployerAgreement::TYPE_SIGNED_BY_EMPLOYER ? 'View & Sign' : 'View';
                    echo !$disable_view ?
                        '<span class="btn btn-xs btn-primary" onclick="window.location.href=\'do.php?_action=view_employer_agreement&id='.$row['id'].'&employer_id='.$row['employer_id'].'\'"><i class="fa fa-folder-open"></i> ' . $view_and_sign . '</span> &nbsp;':
                        '<span class="btn btn-xs btn-primary" disabled="disabled"><i class="fa fa-folder-open"></i> ' . $view_and_sign . '</span> &nbsp;';
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
			if( DB_NAME == "am_ela" && ( $_SESSION['user']->isTypeAdmin() || in_array($_SESSION['user']->username, ["iakmal01"]) )  )
			{
				$disable_view = true;
			}
                        echo $disable_view ?
                            '<span class="btn btn-xs btn-primary" onclick="window.location.href=\'do.php?_action=edit_employer_agreement&id='.$row['id'].'&employer_id='.$row['employer_id'].'\'"><i class="fa fa-edit"></i> Edit</span> &nbsp;':
                            '<span class="btn btn-xs btn-primary" disabled="disabled"><i class="fa fa-edit"></i> Edit</span> &nbsp;';
                        echo '<span class="btn btn-xs btn-primary" disabled="disabled"><i class="fa fa-envelope"></i> Email</span> &nbsp;';
                        echo '<span class="btn btn-xs btn-danger" disabled="disabled"><i class="fa fa-trash"></i> Delete</span> &nbsp;';
                    }
                    elseif(in_array($row['status'], [EmployerAgreement::TYPE_COMPLETED]) && $row['file_upload'] == "N")
                    {
			if( DB_NAME == "am_ela" && ( $_SESSION['user']->isTypeAdmin() || in_array($_SESSION['user']->username, ["iakmal01"]) )  )
			{
				echo '<span class="btn btn-xs btn-primary" onclick="window.location.href=\'do.php?_action=edit_employer_agreement&id='.$row['id'].'&employer_id='.$row['employer_id'].'\'"><i class="fa fa-edit"></i> Edit</span> &nbsp;';
			}
                        echo '<span class="btn btn-xs btn-info" onclick="downloadAgreement(' . $row['id'] . ');"><i class="fa fa-file-pdf-o"></i> Download</span> &nbsp;';
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

	private function renderCRMContacts(PDO $link)
	{
		$job_roles_ddl = DAO::getLookupTable($link, "SELECT id, description FROM lookup_job_roles WHERE cat = 'CRM Contact' ORDER BY description");
		$job_roles_ddl = DAO::getLookupTable($link, "SELECT id, description FROM lookup_crm_contact_job_roles ORDER BY description");
		$job_roles_ddl = $job_roles_ddl + ['' => ''];

		$sql = <<<HEREDOC
SELECT 
    organisation_contacts.*
    , (SELECT COUNT(*) FROM crm_notes_orgs WHERE org_contact_id = organisation_contacts.contact_id) AS crm_notes_count
FROM 
    organisation_contacts 
WHERE 
    org_id='$this->id' 
ORDER BY 
    contact_name;
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
				if(true || $_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN]))
					$edit = '<span class="btn btn-primary btn-xs" onclick="window.location.href=\'do.php?_action=edit_crm_contact&org_type=employer&org_id='.$row['org_id'].'&contact_id='.$row['contact_id'].'\'"><i class="fa fa-edit"></i> Edit</span>';
				if($row['crm_notes_count'] == 0 && ($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN])))
					$delete = '<span class="btn btn-danger btn-xs pull-right" onclick="deleteRecord(\'crm_contact\', \'\', \''.$row['contact_id'].'\', \''.$row['contact_name'].'\');"><i class="fa fa-trash"></i> Delete</span>';

				$job_title = $row['job_title'] != '' ? '<br><span class="text-info">' . $row['job_title'] . '</span>' : '';
				echo <<<HTML
<div class="col-sm-4">
	<div class="box box-primary $_access">
		<div class="box-body box-profile">
			<span class="profile-username">{$row['contact_title']} {$row['contact_name']}</span>
			<br><span class="text-bold">$job_role</span>
			$job_title
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

	public function renderComposeNewMessageBox(PDO $link, $vo)
	{
		$email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type IN ('EMPLOYER_AGREEMENT', 'REMINDER_EMPLOYER_AGREEMENT');");
		array_unshift($email_templates, array('','Email template:',''));
		$ddlTemplates =  HTML::selectChosen('frmEmailTemplate', $email_templates, '', false);
		$html = <<<HTML
<form name="frmEmail" id="frmEmail" action="do.php?_action=ajax_email_actions" method="post">
	<input type="hidden" name="subaction" value="sendEmail" />
	<input type="hidden" name="frmEmailEntityType" value="employers" />
	<input type="hidden" name="frmEmailEntityId" value="$vo->id" />
	<div class="box box-primary">
		<div class="box-header with-border"><h2 class="box-title">Compose New Email</h2></div>
		<div class="box-body">
			<div class="form-group"><div class="row"> <div class="col-sm-8"> $ddlTemplates </div><div class="col-sm-4"> <span class="btn btn-sm btn-default" onclick="load_email_template_in_frmEmail();">Load template</span></div> </div></div>
			<div class="form-group">To: <input name="frmEmailTo" id="frmEmailTo" class="form-control compulsory" placeholder="To:" value=""></div>
			<div class="form-group">From: <input name="frmEmailFrom" id="frmEmailFrom" class="form-control compulsory" placeholder="From:" value="{$_SESSION['user']->work_email}"></div>
			<div class="form-group">Subject: <input name="frmEmailSubject" id="frmEmailSubject" class="form-control compulsory" placeholder="Subject:" autocomplete="0"></div>
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

	public function showSentEmails(PDO $link, Employer $organisation)
    {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered small">';
        $result = DAO::getResultset($link, "SELECT * FROM emails WHERE emails.entity_type = 'organisations' AND emails.entity_id = '{$organisation->id}' ORDER BY created DESC", DAO::FETCH_ASSOC);
        echo '<caption class="lead text-bold text-center">Sent Emails (' . count($result) . ')</caption>';
        echo '<tr><th>DateTime</th><th>By</th><th>To Address</th><th>Subject</th><th>Email</th></tr>';
        foreach($result AS $row)
        {
            echo '<tr>';
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

}