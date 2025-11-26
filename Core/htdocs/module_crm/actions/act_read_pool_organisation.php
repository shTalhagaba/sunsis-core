<?php
class read_pool_organisation implements IAction
{
    public $id = NULL;
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
        if($subaction == 'delete_location')
        {
            DAO::execute($link, "DELETE FROM pool_locations WHERE id = '{$_REQUEST['record_id']}'");
            echo 'location deleted';
            exit;
        }

        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

        if($id == '')
            throw new Exception('Missing querystring argument: pool organisation id');

        $vo = EmployerPool::loadFromDatabase($link, $id);
        if(is_null($vo))
            throw new Exception('Pool organisation record not found.');

        $this->id = $id;

        $_SESSION['bc']->add($link, "do.php?_action=read_pool_organisation&id={$vo->id}", "View pool organisation");

        $enquiries_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_enquiries WHERE crm_enquiries.company_type = 'pool' AND crm_enquiries.company_id = '{$vo->id}'");
        $leads_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_leads WHERE crm_leads.company_type = 'pool' AND crm_leads.company_id = '{$vo->id}'");
        $opportunities_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_opportunities WHERE crm_opportunities.company_type = 'pool' AND crm_opportunities.company_id = '{$vo->id}'");
        $locations_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM pool_locations WHERE pool_locations.pool_id = '{$vo->id}'");
        $contacts_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM pool_contact WHERE pool_contact.pool_id = '{$vo->id}'");

        $repository = Repository::getRoot().'/pools/'.$vo->id;
        $repository = Repository::readDirectory($repository);
        $files_count = 0;
        foreach($repository AS $file)
        {
            if($file->isDir())
                continue;
            $files_count++;
        }

        include_once('tpl_read_pool_organisation.php');
    }

    private function renderLocations(PDO $link, $back)
    {
        $sql = <<<HEREDOC
SELECT
	pool_locations.*,
	CASE

		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = pool_locations.id ORDER BY next_assessment DESC LIMIT 1) >30 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = pool_locations.id ORDER BY next_assessment DESC LIMIT 1) <0 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = pool_locations.id ORDER BY next_assessment DESC LIMIT 1) >=0 and (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = pool_locations.id ORDER BY next_assessment DESC LIMIT 1) <= 30 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `health_and_safety`,
	CASE

		WHEN (SELECT complient FROM health_safety WHERE location_id = pool_locations.id ORDER BY next_assessment DESC LIMIT 1) = 1 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN (SELECT complient FROM health_safety WHERE location_id = pool_locations.id ORDER BY next_assessment DESC LIMIT 1) = 2 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		WHEN (SELECT complient FROM health_safety WHERE location_id = pool_locations.id ORDER BY next_assessment DESC LIMIT 1) = 3 THEN "<img  src='/images/warning-17.JPG'  border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `compliant`,
	(SELECT COUNT(*) FROM tr WHERE tr.provider_location_id = pool_locations.id) AS learners
FROM
	pool_locations
WHERE
	pool_id = '$this->id'
ORDER BY pool_locations.is_legal_address DESC;
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
                echo '</div> ';
                echo '</div> '; // box-body
                echo '<div class="box-footer"> ';
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN]))
                    echo '<span class="btn btn-primary btn-xs" onclick="window.location.replace(\'do.php?_action=edit_pool_location&id=' . $loc['id'] . '&pool_id=' . $loc['pool_id'] . '\');"><i class="fa fa-edit"></i> Edit</span> ';
//                if(($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN])))
//                    echo '<span class="btn btn-danger btn-xs pull-right" onclick="deleteLocation(\'location\', \''.$loc['postcode'].'\', \''.$loc['id'].'\', \''.$loc['full_name'].'\');"><i class="fa fa-trash"></i> Delete</span>';
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

    public function isAbleToDelete()
    {
        if($_SESSION['user']->isAdmin())
            return true;

        return false;
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
	<input type="hidden" name="frmEmailEntityType" value="pool" />
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

    public function showSentEmails(PDO $link, $vo)
    {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered small">';
        $result = DAO::getResultset($link, "SELECT * FROM emails WHERE emails.entity_type = 'pool' AND emails.entity_id = '{$vo->id}' ORDER BY created DESC", DAO::FETCH_ASSOC);
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

    private function renderCrmContacts(PDO $link)
    {
        $job_roles_ddl = DAO::getLookupTable($link, "SELECT id, description FROM lookup_job_roles WHERE cat = 'CRM Contact' ORDER BY description");
        $job_roles_ddl = $job_roles_ddl + ['' => ''];

        $sql = <<<HEREDOC
SELECT DISTINCT pool_contact.* FROM pool_contact WHERE pool_id='$this->id' ORDER BY decision_maker DESC, contact_name;
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
                if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [User::TYPE_ADMIN, User::TYPE_CRM_FRON_DESK_USER]))
                    $edit = '<span class="btn btn-primary btn-xs" onclick="window.location.href=\'do.php?_action=edit_pool_contact&org_type=pool&pool_id='.$row['pool_id'].'&contact_id='.$row['contact_id'].'\'"><i class="fa fa-edit"></i> Edit</span>';

                $decision_maker = $row['decision_maker'] == 1 ? '<i class="fa fa-info-circle"></i> This person has decision making authority' : '';

                echo <<<HTML
<div class="col-sm-4">
	<div class="box box-primary $_access">
		<div class="box-body box-profile">
			<span class="profile-username">{$row['contact_title']} {$row['contact_name']}</span>
			<br><span class="text-bold">$job_role</span>
			$address
            <br>$decision_maker
		</div>
		<div class="box-footer">
			$edit
			
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

    public function renderEnquiries(PDO $link, $pool_id)
    {
        $sql = <<<HEREDOC
        SELECT DISTINCT
  crm_enquiries.*,
  CONCAT(firstnames, ' ', surname) AS created_by_name  
FROM
  crm_enquiries
  LEFT JOIN users ON crm_enquiries.created_by = users.`id`
WHERE company_id = '$pool_id'
  AND company_type = 'pool'
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
                echo $row['status'] != '' ? '<td>' . Enquiry::getListEnquiryStatus($row['status']) . '</td>' : '<td></td>';
                echo $row['enquiry_type'] != '' ? '<td>' . Enquiry::getListEnquiryType($row['enquiry_type']) . '</td>' : '<td></td>';
                echo '<td>' . $row['source'] . '</td>';
                $contacts_table_name = $row['company_type'] == 'pool' ? 'pool_contact' : ($row['company_type'] == 'E' ? 'organisation_contact' : '');
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

    public function renderLeads(PDO $link, $pool_id)
    {
        $sql = <<<HEREDOC
        SELECT DISTINCT
        crm_leads.*,
  CONCAT(firstnames, ' ', surname) AS created_by_name  
FROM
  crm_leads
  LEFT JOIN users ON crm_leads.created_by = users.`id`
WHERE company_id = '$pool_id'
  AND company_type = 'pool'
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
                echo $row['status'] != '' ? '<td>' . Lead::getListLeadStatus($row['status']) . '</td>' : '<td></td>';
                echo $row['lead_type'] != '' ? '<td>' . Enquiry::getListEnquiryType($row['lead_type']) . '</td>' : '<td></td>';
                echo '<td>' . $row['source'] . '</td>';
                $contacts_table_name = $row['company_type'] == 'pool' ? 'pool_contact' : ($row['company_type'] == 'E' ? 'organisation_contact' : '');
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
    
    
    public function renderOpportunities(PDO $link, $pool_id)
    {
        $sql = <<<HEREDOC
        SELECT DISTINCT
        crm_opportunities.*,
  CONCAT(firstnames, ' ', surname) AS created_by_name  
FROM
crm_opportunities
  LEFT JOIN users ON crm_opportunities.created_by = users.`id`
WHERE company_id = '$pool_id'
  AND company_type = 'pool'
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
                echo '<td>' . $row['status'] != '' ? Lead::getListLeadStatus($row['status']) . '</td>' : '<td></td>';
                echo '<td>' . Date::toShort($row['est_closed_date']) . '</td>';
                echo '<td>' . $row['est_revenue'] . '</td>';
                echo '<td>' . $row['source'] . '</td>';
                $contacts_table_name = $row['company_type'] == 'pool' ? 'pool_contact' : ($row['company_type'] == 'E' ? 'organisation_contact' : '');
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

    public function renderFileRepository(PDO $link, EmployerPool $vo)
    {
        $repository = Repository::getRoot().'/pools/'.$vo->id;
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

}