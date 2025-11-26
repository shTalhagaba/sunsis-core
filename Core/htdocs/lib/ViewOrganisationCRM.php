<?php
class ViewOrganisationCRM extends View
{

    public static function getInstance()
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {

            $username = $_SESSION['user']->username;
            $where = " ";
            if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
                $where = '';
            elseif($_SESSION['user']->type==7)
                $where = " where organisations.organisation_type=2 and organisations.creator='$username'";
            elseif($_SESSION['user']->type==2 || $_SESSION['user']->type==4 || $_SESSION['user']->type==20)
                $where = " where organisations.organisation_type=2";
            elseif($_SESSION['user']->type==3)
            {
                $assessor = $_SESSION['user']->username;
                $where = " where organisations.id in (select employer_id from tr where assessor = '$assessor') ";
            }
            elseif($_SESSION['user']->type==20)
            {
                $assessor = $_SESSION['user']->username;
                $where = " where organisations.id in (select employer_id from tr where programme = '$assessor') ";
            }
            else
            {
                $emp = $_SESSION['user']->employer_id;
                $where = " where (organisations.id='$emp')" ;
            }

            if(DB_NAME == "am_baltic" || DB_NAME=="ams")
            {
                if($where == "")
                    $where .= " WHERE crm_notes.audit_info IS NOT NULL ";
                else
                    $where .= " AND crm_notes.audit_info IS NOT NULL ";
            }

            if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo")
            {
                $sql = <<<HEREDOC
SELECT
		crm_notes.*, organisations.legal_name AS organisation,
		organisations.organisation_type,
		DATE_FORMAT(crm_notes.date, '%d/%m/%Y') AS date,
		# re added to crm_date to be correctly ordered
		crm_notes.date as crm_date,
		lookup_crm_contact_type.description as type_of_contact,
		lookup_crm_subject_org.description as subject,
		crm_notes.`audit_info`
FROM
		crm_notes
		LEFT JOIN organisations on organisations.id = crm_notes.organisation_id
		LEFT JOIN lookup_crm_contact_type on lookup_crm_contact_type.id = crm_notes.type_of_contact
		LEFT JOIN lookup_crm_subject_org on lookup_crm_subject_org.id = crm_notes.subject
		$where
HEREDOC;

            }
            else
            {
                $sql = <<<HEREDOC
SELECT
		crm_notes.*, organisations.legal_name AS organisation,
		organisations.organisation_type,	 
		DATE_FORMAT(crm_notes.date, '%d/%m/%Y') AS date,
		# re added to crm_date to be correctly ordered
		crm_notes.date as crm_date,
		lookup_crm_contact_type.description as type_of_contact,
		lookup_crm_subject.description as subject,
		crm_notes.`audit_info`
FROM
		crm_notes
		LEFT JOIN organisations on organisations.id = crm_notes.organisation_id
		LEFT JOIN lookup_crm_contact_type on lookup_crm_contact_type.id = crm_notes.type_of_contact
		LEFT JOIN lookup_crm_subject on lookup_crm_subject.id = crm_notes.subject
		$where
HEREDOC;
            }
            $view = $_SESSION[$key] = new ViewOrganisationCRM();
            $view->setSQL($sql);

            // Add view filters
            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Organisation', null, 'ORDER BY legal_name'),
                1=>array(1, 'Name of Person Contacted', null, 'ORDER BY name_of_person'),
                2=>array(2, 'Name of Person Contacted (Position)', null, 'ORDER BY position'),
                3=>array(3, 'Type of Contact', null, 'ORDER BY type_of_contact'),
                4=>array(4, 'Subject', null, 'ORDER BY lookup_crm_subject.description'),
                // re changed to crm_date to be correctly ordered
                5=>array(5, 'Date', null, 'ORDER BY crm_date'),
                6=>array(6, 'By Whom', null, 'ORDER BY by_whom'),
                7=>array(7, 'Whom Position', null, 'ORDER BY whom_position'),
                8=>array(8, 'Agreed Action', null, 'ORDER BY agreed_action'));
            $f = new DropDownViewFilter('order_by', $options, 0, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            // Organisation Filter
            if($_SESSION['user']->type == User::TYPE_MANAGER)
                $options = 'SELECT id, legal_name, null, CONCAT("WHERE  crm_notes.organisation_id=",id) FROM organisations WHERE id IN (SELECT DISTINCT organisation_id FROM crm_notes) AND organisations.parent_org = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
            else
                $options = "SELECT id, legal_name, null, CONCAT('WHERE  crm_notes.organisation_id=',id) FROM organisations WHERE id in (select organisation_id from crm_notes) order by legal_name";
            $f = new DropDownViewFilter('filter_organisation', $options, null, true);
            $f->setDescriptionFormat("Organisation: %s");
            $view->addFilter($f);

            // Name of person contacted Filter
            if(DB_NAME=="am_baltic" || DB_NAME=="ams")
                $options = "SELECT distinct name_of_person, name_of_person, null, CONCAT('WHERE  crm_notes.name_of_person=',CHAR(39),name_of_person,CHAR(39)) FROM crm_notes WHERE crm_notes.audit_info IS NOT NULL order by name_of_person";
            else
                $options = "SELECT distinct name_of_person, name_of_person, null, CONCAT('WHERE  crm_notes.name_of_person=',CHAR(39),name_of_person,CHAR(39)) FROM crm_notes order by name_of_person";
            $f = new DropDownViewFilter('filter_name_of_person', $options, null, true);
            $f->setDescriptionFormat("Name of Person Contacted: %s");
            $view->addFilter($f);

            // Name of by whom Filter
            if(DB_NAME=="am_baltic" || DB_NAME=="ams")
                $options = "SELECT distinct by_whom, by_whom, null, CONCAT('WHERE  crm_notes.by_whom=',CHAR(39),by_whom,CHAR(39)) FROM crm_notes WHERE crm_notes.audit_info IS NOT NULL order by by_whom";
            else
                $options = "SELECT distinct by_whom, by_whom, null, CONCAT('WHERE  crm_notes.by_whom=',CHAR(39),by_whom,CHAR(39)) FROM crm_notes order by by_whom";
            $f = new DropDownViewFilter('filter_by_whom', $options, null, true);
            $f->setDescriptionFormat("By whom: %s");
            $view->addFilter($f);

            // Type of Contact Filter
            $options = "SELECT id, description, null, CONCAT('WHERE  crm_notes.type_of_contact=',id) FROM lookup_crm_contact_type order by description";
            $f = new DropDownViewFilter('filter_type_of_contact', $options, null, true);
            $f->setDescriptionFormat("Type of Contact: %s");
            $view->addFilter($f);

            // Subject Filter
            if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo")
                $options = "SELECT id, description, null, CONCAT('WHERE  crm_notes.subject=',id) FROM lookup_crm_subject_org order by description";
            elseif(DB_NAME=="am_baltic" || DB_NAME == "ams")
                $options = "SELECT id, description, null, CONCAT('WHERE  crm_notes.subject=',id) FROM lookup_crm_subject where employer = 1 order by description";
            else
                $options = "SELECT id, description, null, CONCAT('WHERE  crm_notes.subject=',id) FROM lookup_crm_subject order by description";
            $f = new DropDownViewFilter('filter_subject', $options, null, true);
            $f->setDescriptionFormat("Subject: %s");
            $view->addFilter($f);

            // Date filters
            $dateInfo = getdate();
            $weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
            $timestamp = time()  - ((60*60*24) * $weekday);
            // Rewind by a further 1 week
            $timestamp = $timestamp - ((60*60*24) * 7);
            $format = "WHERE crm_notes.date >= '%s'";
            $f = new DateViewFilter('start_date', $format, '');
            $f->setDescriptionFormat("From start date: %s");
            $view->addFilter($f);

            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));
            $format = "WHERE crm_notes.date <= '%s'";
            $f = new DateViewFilter('end_date', $format, '');
            $f->setDescriptionFormat("To start date: %s");
            $view->addFilter($f);

            if(DB_NAME == "am_baltic")
            {
                // next action
                $options = "SELECT id, description, null, CONCAT('WHERE crm_notes.next_action=',id) FROM lookup_crm_regarding  WHERE employer = 1 ORDER BY description";
                $f = new DropDownViewFilter('filter_next_action', $options, null, true);
                $f->setDescriptionFormat("Next Action: %s");
                $view->addFilter($f);

                $options = array(
                    0=>array(0, 'Show all', null, null),
                    1=>array(1, 'With Prevention Alert', null, 'WHERE crm_notes.prevention_alert = "Y"'));
                $f = new DropDownViewFilter('filter_p_alert', $options, 0, false);
                $f->setDescriptionFormat("Prevention Alert: %s");
                $view->addFilter($f);
            }

        }

        return $_SESSION[$key];
    }

    public function render(PDO $link)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());
        //$st=$link->query("call view_training_providers();");
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center"><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="5">';
            echo '<thead height="40px">';
            echo '<tr>';
            echo '<th class="topRow">&nbsp;</th><th class="topRow">Organisation</th><th class="topRow">Name of Person Contacted</th><th class="topRow">Position</th><th class="topRow">Type of Contact</th><th class="topRow">Subject</th><th>Date</th><th class="topRow">By Whom</th><th class="topRow">Position</th><th class="topRow">Agreed Action</th><th class="topRow">Audit Info</th>';
            if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo")
                echo '<th>Prevention Alert</th>';
            echo '</tr>';
            echo '</thead>';

            echo '<tbody>';
            while($row = $st->fetch())
            {
                if(DB_NAME=="am_baltic" || DB_NAME=="ams")
                    echo '<tr>';
                else
                    echo HTML::viewrow_opening_tag('/do.php?_action=edit_crm_note&organisations_id=' . $row['organisation_id'] . '&id=' . $row['id']);
                echo '<td><img src="/images/text-left.png" border="0" /></td>';
                echo '<td align="left">' . HTML::cell($row['organisation']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['name_of_person']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['position']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['type_of_contact']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['subject']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['date']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['by_whom']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['whom_position']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['agreed_action']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['audit_info']) . '</td>';
                if(DB_NAME == "am_baltic" || DB_NAME == "am_baltic_demo")
                    echo $row['prevention_alert'] == 'Y' ? '<td>Yes</td>' : '<td></td>';

                echo '</tr>';
            }

            echo '</tbody></table></div>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

    public static function getInstanceV2()
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            $sql = new SQLStatement("
SELECT DISTINCT 
  crm_notes_orgs.id,
  crm_notes_orgs.`organisation_id`,
  organisations.legal_name AS employer,
  (SELECT contact_name FROM organisation_contact WHERE contact_id = crm_notes_orgs.`org_contact_id`) AS organisation_contact,
  (SELECT lookup_crm_contact_type.`description` FROM lookup_crm_contact_type WHERE lookup_crm_contact_type.`id` = crm_notes_orgs.`type_of_contact`) AS type_of_contact,
  (SELECT lookup_crm_subject.`description` FROM lookup_crm_subject WHERE lookup_crm_subject.`id` = crm_notes_orgs.`subject`) AS `subject`,
  DATE_FORMAT(crm_notes_orgs.`contact_date`, '%d/%m/%Y') AS contact_date,
  crm_notes_orgs.`contact_time`,
  crm_notes_orgs.`contact_duration`,
  crm_notes_orgs.`by_whom`,
  crm_notes_orgs.`by_whom_position`,
  DATE_FORMAT(crm_notes_orgs.`next_action_date`, '%d/%m/%Y') AS next_action_date,
  crm_notes_orgs.`next_action_time`,
  (SELECT lookup_crm_regarding.`description` FROM lookup_crm_regarding WHERE lookup_crm_regarding.`id` = crm_notes_orgs.`next_action_id`) AS `next_action`,
  crm_notes_orgs.`agreed_action`,
  crm_notes_orgs.`actioned`,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = crm_notes_orgs.`created_by`) AS created_by,
  DATE_FORMAT(crm_notes_orgs.`created_at`, '%d/%m/%Y %H:%i:%s') AS created_at
  
FROM
  crm_notes_orgs LEFT JOIN organisations ON crm_notes_orgs.organisation_id = organisations.id
			");

            if($_SESSION['user']->isAdmin() || $_SESSION['user']->type == User::TYPE_SYSTEM_VIEWER)
            {

            }
            elseif($_SESSION['user']->type == User::TYPE_SALESPERSON)
            {
                $sql->setClause("WHERE organisations.organisation_type = 2 AND organisations.creator = '{$_SESSION['user']->username}' ");
            }
            elseif(in_array($_SESSION['user']->type, [User::TYPE_TUTOR, User::TYPE_VERIFIER, User::TYPE_APPRENTICE_COORDINATOR]))
            {
                $sql->setClause("WHERE organisations.organisation_type = 2");
            }
            elseif($_SESSION['user']->type == User::TYPE_ASSESSOR)
            {
                $sql->setClause("WHERE organisations.id IN (SELECT employer_id FROM tr WHERE tr.assessor = '{$_SESSION['user']->id}') ");
            }
            else
            {
                $sql->setClause("WHERE organisations.id = '{$_SESSION['user']->employer_id}' ");
            }

            $view = $_SESSION[$key] = new ViewOrganisationCRM();
            $view->setSQL($sql->__toString());

            // Add view filters
            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Organisation', null, 'ORDER BY legal_name'),
                1=>array(1, 'Name of Person Contacted', null, 'ORDER BY name_of_person'),
                2=>array(2, 'Name of Person Contacted (Position)', null, 'ORDER BY position'),
                3=>array(3, 'Type of Contact', null, 'ORDER BY type_of_contact'),
                4=>array(4, 'Subject', null, 'ORDER BY subject'),
                5=>array(5, 'Creation Date (ASC)', null, 'ORDER BY crm_notes_orgs.`created_at` ASC'),
                6=>array(6, 'Creation Date (DESC)', null, 'ORDER BY crm_notes_orgs.`created_at` DESC'),
                7=>array(7, 'Contact Date (ASC)', null, 'ORDER BY crm_notes_orgs.`contact_date` ASC'),
                8=>array(8, 'Contact Date (DESC)', null, 'ORDER BY crm_notes_orgs.`contact_date` DESC'),
                9=>array(9, 'By Whom', null, 'ORDER BY by_whom'),
                10=>array(10, 'Whom Position', null, 'ORDER BY whom_position'),
                11=>array(11, 'Agreed Action', null, 'ORDER BY agreed_action'),
                12=>array(12, 'Next Action Date (ASC)', null, 'ORDER BY crm_notes_orgs.`next_action_date` ASC'),
                13=>array(13, 'Next Action Date (DESC)', null, 'ORDER BY crm_notes_orgs.`next_action_date` DESC'));
            $f = new DropDownViewFilter('order_by', $options, 0, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

	     $options = array(
                0=>array(0, 'Yes', null, 'HAVING actioned = "Y"'),
                1=>array(1, 'No', null, 'HAVING actioned = "N"'),
                2=>array(2, 'Not Applicable', null, 'HAVING actioned = "NA"'));
            $f = new DropDownViewFilter('filter_actioned', $options, '', true);
            $f->setDescriptionFormat("Actioned: %s");
            $view->addFilter($f);

            // Organisation Filter
            if($_SESSION['user']->type == User::TYPE_MANAGER)
                $options = 'SELECT id, legal_name, null, CONCAT("WHERE  crm_notes_orgs.organisation_id=",id) FROM organisations WHERE id IN (SELECT DISTINCT organisation_id FROM crm_notes_orgs) AND organisations.parent_org = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
            else
                $options = "SELECT id, legal_name, null, CONCAT('WHERE  crm_notes_orgs.organisation_id=',id) FROM organisations WHERE id in (select organisation_id from crm_notes_orgs) order by legal_name";
            $f = new DropDownViewFilter('filter_organisation', $options, null, true);
            $f->setDescriptionFormat("Organisation: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT by_whom, by_whom, null, CONCAT('WHERE  crm_notes_orgs.by_whom=',CHAR(39),by_whom,CHAR(39)) FROM crm_notes_orgs ORDER BY by_whom";
            $f = new DropDownViewFilter('filter_by_whom', $options, null, true);
            $f->setDescriptionFormat("By whom: %s");
            $view->addFilter($f);

            $options = "SELECT id, description, null, CONCAT('WHERE  crm_notes_orgs.type_of_contact=',id) FROM lookup_crm_contact_type ORDER BY description";
            $f = new DropDownViewFilter('filter_type_of_contact', $options, null, true);
            $f->setDescriptionFormat("Type of Contact: %s");
            $view->addFilter($f);

            $options = "SELECT id, description, null, CONCAT('WHERE  crm_notes_orgs.subject=',id) FROM lookup_crm_subject ORDER BY description";
            $f = new DropDownViewFilter('filter_subject', $options, null, true);
            $f->setDescriptionFormat("Subject: %s");
            $view->addFilter($f);

            $format = "WHERE crm_notes_orgs.contact_date >= '%s'";
            $f = new DateViewFilter('from_contact_date', $format, '');
            $f->setDescriptionFormat("From contact date: %s");
            $view->addFilter($f);

            $format = "WHERE crm_notes_orgs.contact_date <= '%s'";
            $f = new DateViewFilter('to_contact_date', $format, '');
            $f->setDescriptionFormat("To contact date: %s");
            $view->addFilter($f);

	    $format = "WHERE crm_notes_orgs.next_action_date >= '%s'";
            $f = new DateViewFilter('from_next_action_date', $format, '');
            $f->setDescriptionFormat("From next action date: %s");
            $view->addFilter($f);

            $format = "WHERE crm_notes_orgs.next_action_date <= '%s'";
            $f = new DateViewFilter('to_next_action_date', $format, '');
            $f->setDescriptionFormat("To next action date: %s");
            $view->addFilter($f);

            $format = "WHERE crm_notes_orgs.created_at >= '%s'";
            $f = new DateViewFilter('from_created_at', $format, '');
            $f->setDescriptionFormat("From created date: %s");
            $view->addFilter($f);

            $format = "WHERE crm_notes_orgs.created_at <= '%s'";
            $f = new DateViewFilter('to_created_at', $format, '');
            $f->setDescriptionFormat("To created date: %s");
            $view->addFilter($f);

        }

        return $_SESSION[$key];

    }

    public function renderV2(PDO $link)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator() . '<br>';

            echo <<<HEREDOC
			<div class="">
				<table class="table table-bordered">
					<thead class="bg-gray">
					<tr>
						<th></th>
						<th>Employer</th>
						<th>Organisation Contact</th>
						<th>Type Of Contact</th>
						<th>Subject</th>
						<th>Contact Date</th>
						<th>Contact Time</th>
						<th>Contact Duration</th>
						<th>By Whom</th>
						<th>By Whom Position</th>
						<th>Next Action Date</th>
						<th>Next Action Time</th>
						<th>Next Action</th>
						<th>Agreed Action</th>
						<th>Actioned</th>
						<th>Created By</th>
						<th>Created At</th>
					</tr>
					</thead>
HEREDOC;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo '<tr>';
		echo '<td>';
                if($row['actioned'] != 'Y')
                    echo '<span class="btn btn-xs btn-primary" onclick="set_note_as_actioned(\''.$row['id'].'\');">Set as Actioned</span>';
                echo '<a class="btn btn-xs btn-info" href="do.php?_action=edit_org_crm_note&id='.$row['id'].'&organisations_id='.$row['organisation_id'].'"> Open </a>';
                echo '</td>';
                echo '<td>' . HTML::cell($row['employer']) . '</td>';
                echo '<td>' . HTML::cell($row['organisation_contact']) . '</td>';
                echo '<td>' . HTML::cell($row['type_of_contact']) . '</td>';
                echo '<td>' . HTML::cell($row['subject']) . '</td>';
                echo '<td>' . HTML::cell($row['contact_date']) . '</td>';
                echo '<td>' . HTML::cell($row['contact_time']) . '</td>';
                echo '<td>' . HTML::cell($row['contact_duration']) . '</td>';
                echo '<td>' . HTML::cell($row['by_whom']) . '</td>';
                echo '<td>' . HTML::cell($row['by_whom_position']) . '</td>';
                echo '<td>' . HTML::cell($row['next_action_date']) . '</td>';
                echo '<td>' . HTML::cell($row['next_action_time']) . '</td>';
                echo '<td>' . HTML::cell($row['next_action']) . '</td>';
                echo '<td>' . HTML::cell($row['agreed_action']) . '</td>';
                echo '<td>' . HTML::cell($row['actioned']) . '</td>';
                echo '<td>' . HTML::cell($row['created_by']) . '</td>';
                echo '<td>' . HTML::cell($row['created_at']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }

}
?>