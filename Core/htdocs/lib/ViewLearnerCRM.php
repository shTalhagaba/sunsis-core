<?php
class ViewLearnerCRM extends View
{

    public static function getInstance()
    {
        $key = 'view_'.__CLASS__;
        $where = "";
        if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
        {
            $where = '';
        }
        elseif($_SESSION['user']->type == 3)
        {
            $id = $_SESSION['user']->id;
            $where = ' where (groups.assessor = '. '"' . $id . '" or tr.assessor="' . $id . '")';
	    if( DB_NAME == "am_baltic" && in_array($_SESSION['user']->username, ["marbrown"]) )
            {
                $where = '';
            }	
        }
        elseif($_SESSION['user']->type == 2)
        {
            $id = $_SESSION['user']->id;
            $where = ' where (groups.tutor = '. '"' . $id . '" or tr.tutor="' . $id . '")';
        }
        elseif($_SESSION['user']->type == 4)
        {
            $id = $_SESSION['user']->id;
            $where = ' where (groups.verifier = '. '"' . $id . '" or tr.verifier="' . $id . '")';
        }
        elseif($_SESSION['user']->type == 20)
        {
            $username = $_SESSION['user']->username;
            $where = ' where (tr.programme="' . $username . '")';
        }
        elseif($_SESSION['user']->type == 7)
        {
            $id = $_SESSION['user']->id;
            $where = ' where (tr.assessor="' . $id . '")';
        }
        elseif($_SESSION['user']->type == 21)
        {
            $username = $_SESSION['user']->username;
            $where = ' where (courses.director="' . $username . '")';
        }
        elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
        {
            $emp = $_SESSION['user']->employer_id;
            $where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp')" ;
        }

	$client_custom_fields = "";
        if(in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]))
        {
            $client_custom_fields = ",
        CASE crm_notes_learner.rating
            WHEN 'S1' THEN 'Stage 1'
            WHEN 'S2' THEN 'Stage 2'
            WHEN 'S3' THEN 'Stage 3'
        END AS rating,
        CASE crm_notes_learner.concerns
            WHEN 'L' THEN 'Learner'
            WHEN 'E' THEN 'Employer'
            WHEN 'B' THEN 'Both'
	    WHEN 'T' THEN 'Training Provider - Baltic'
        END AS concerns,
        CASE crm_notes_learner.reason
            WHEN '28' THEN 'Business Performance'
            WHEN '29' THEN 'Business Environment'
            WHEN '2' THEN 'Incorrect job role'
            WHEN '9' THEN 'Apprentice Performance'
            WHEN '5' THEN 'Health & Wellbeing'
            WHEN '1' THEN 'New Job'
            WHEN '16' THEN 'Capability'
            WHEN '35' THEN 'Dissatisfied with Baltic'
            WHEN '36' THEN 'Job Role Change'
        END AS reason";
        }

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
SELECT  DISTINCT
	concat(tr.firstnames,' ',tr.surname) as learner,
	crm_notes_learner.name_of_person,
	crm_notes_learner.position,
	#crm_notes_learner.type_of_contact,
	#crm_notes_learner.subject,
	lookup_crm_contact_type.description AS type_of_contact,
	lookup_crm_subject.description as subject,
	crm_notes_learner.date,
	crm_notes_learner.by_whom,
	crm_notes_learner.whom_position,
	crm_notes_learner.agreed_action,
	crm_notes_learner.id,
	crm_notes_learner.tr_id,
	crm_notes_learner.next_action_date,
	tr.l03,
	contracts.title AS contract,
	(SELECT legal_name FROM organisations WHERE id = tr.employer_id) AS employer,
	IF(tr.`assessor` IS NULL OR tr.`assessor` = '',
	(SELECT CONCAT(users.`firstnames`,' ',users.`surname`) FROM users WHERE users.id = groups.`assessor`),
        (SELECT CONCAT(users.`firstnames`,' ', users.`surname` ) FROM users WHERE users.id = tr.`assessor`)) AS assessor,
        (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
        student_frameworks.title AS framework
        $client_custom_fields
FROM
		crm_notes_learner
		INNER JOIN tr on tr.id = crm_notes_learner.tr_id
		LEFT JOIN group_members ON group_members.tr_id = tr.id
		LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
		LEFT JOIN courses on courses.id = courses_tr.course_id
		LEFT JOIN groups on groups.courses_id = courses.id and group_members.groups_id = groups.id
		LEFT JOIN lookup_crm_contact_type on lookup_crm_contact_type.id = crm_notes_learner.type_of_contact
		LEFT JOIN lookup_crm_subject on lookup_crm_subject.id = crm_notes_learner.subject
		LEFT JOIN contracts ON contracts.id = tr.contract_id
		LEFT JOIN student_frameworks ON tr.id = student_frameworks.tr_id
$where
HEREDOC;

            $view = $_SESSION[$key] = new ViewLearnerCRM();
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
                0=>array(0, 'Organisation', null, 'ORDER BY learner'),
                1=>array(1, 'Name of Person Contacted', null, 'ORDER BY name_of_person'),
                2=>array(2, 'Name of Person Contacted (Position)', null, 'ORDER BY position'),
                3=>array(3, 'Type of Contact', null, 'ORDER BY type_of_contact'),
                4=>array(4, 'Subject', null, 'ORDER BY subject'),
                5=>array(5, 'Date', null, 'ORDER BY date'),
                6=>array(6, 'By Whom', null, 'ORDER BY by_whom'),
                7=>array(7, 'Whom Position', null, 'ORDER BY whom_position'),
                8=>array(8, 'Agreed Action', null, 'ORDER BY agreed_action'));
            $f = new DropDownViewFilter('order_by', $options, 0, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_firstname', "WHERE tr.firstnames LIKE '%s%%'", null);
            $f->setDescriptionFormat("First Name: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            // Name of person contacted Filter
            $options = "SELECT distinct name_of_person, name_of_person, null, CONCAT('WHERE  crm_notes_learner.name_of_person=',CHAR(39),name_of_person,CHAR(39)) FROM crm_notes_learner order by name_of_person";
            $f = new DropDownViewFilter('filter_name_of_person', $options, null, true);
            $f->setDescriptionFormat("Name of Person Contacted: %s");
            $view->addFilter($f);

	    $options = "SELECT DISTINCT id, title, NULL, CONCAT('WHERE student_frameworks.id=',CHAR(39), id, CHAR(39)) FROM student_frameworks ORDER BY title;";
            $f = new DropDownViewFilter('filter_programme', $options, null, true);
            $f->setDescriptionFormat('Programme: %s');
            $view->addFilter($f);

            $options = "SELECT id, title, null, CONCAT('WHERE  contracts.id=',id) FROM contracts ORDER BY contracts.contract_year DESC, contracts.title";
            $f = new DropDownViewFilter('filter_contract', $options, null, true);
            $f->setDescriptionFormat("Contract: %s");
            $view->addFilter($f);

            // Type of Contact Filter
            $options = "SELECT id, description, null, CONCAT('WHERE  crm_notes_learner.type_of_contact=',id) FROM lookup_crm_contact_type order by description";
            $f = new DropDownViewFilter('filter_type_of_contact', $options, null, true);
            $f->setDescriptionFormat("Type of Contact: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT id, description, NULL, CONCAT('WHERE crm_notes_learner.subject=',CHAR(39), id, CHAR(39)) FROM lookup_crm_subject WHERE id IN (SELECT SUBJECT FROM crm_notes_learner) ORDER BY description;";
            $f = new DropDownViewFilter('filter_subject', $options, null, true);
            $f->setDescriptionFormat("Subject: %s");
            $view->addFilter($f);

            // Name of by whom Filter
            $options = "SELECT distinct by_whom, by_whom, null, CONCAT('WHERE  crm_notes_learner.by_whom=',CHAR(39),by_whom,CHAR(39)) FROM crm_notes_learner order by by_whom";
            $f = new DropDownViewFilter('filter_by_whom', $options, null, true);
            $f->setDescriptionFormat("By whom: %s");
            $view->addFilter($f);

            $parent_org = $_SESSION['user']->employer_id;

            // Employer Filter
            if($_SESSION['user']->type==8)
                $options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE (organisation_type like '%2%' or organisation_type like '%6%') and organisations.parent_org=$parent_org order by legal_name";
            else
                $options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE organisation_type like '%2%' or organisation_type like '%6%' order by legal_name";
            $f = new DropDownViewFilter('filter_employer', $options, null, true);
            $f->setDescriptionFormat("Employer: %s");
            $view->addFilter($f);

            // Date filters
            $dateInfo = getdate();
            $weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
            $timestamp = time()  - ((60*60*24) * $weekday);
            // Rewind by a further 1 week
            $timestamp = $timestamp - ((60*60*24) * 7);
            $format = "WHERE crm_notes_learner.date >= '%s'";
            $f = new DateViewFilter('start_date', $format, '');
            $f->setDescriptionFormat("From start date: %s");
            $view->addFilter($f);
            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));
            $format = "WHERE crm_notes_learner.date <= '%s'";
            $f = new DateViewFilter('end_date', $format, '');
            $f->setDescriptionFormat("To start date: %s");
            $view->addFilter($f);

	    $options = [
                0 => [0, 'Show All', null, null],
                1 => [1, 'Continuing Leanrers', null, 'WHERE tr.status_code = "1"'],
                2 => [2, 'Completed Leanrers', null, 'WHERE tr.status_code = "2"'],
                3 => [3, 'Withdrawn Leanrers', null, 'WHERE tr.status_code = "3"'],
                4 => [6, 'Temporary Withdrawn Leanrers', null, 'WHERE tr.status_code = "6"'],
            ];
            $f = new DropDownViewFilter('filter_tr_status', $options, 1, false);
            $f->setDescriptionFormat("Training Status: %s");
            $view->addFilter($f);

            if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
            {
                $options = [
                    0 => [0, 'Show All', null, null],
                    1 => [1, 'Stage 1', null, 'WHERE crm_notes_learner.rating = "S1"'],
                    2 => [2, 'Stage 2', null, 'WHERE crm_notes_learner.rating = "S2"'],
                    3 => [3, 'Stage 3', null, 'WHERE crm_notes_learner.rating = "S3"'],
                ];
                $f = new DropDownViewFilter('filter_rating', $options, 0, false);
                $f->setDescriptionFormat("Rating: %s");
                $view->addFilter($f);

                $options = [
                    0 => [0, 'Show All', null, null],
                    1 => [1, 'Learner', null, 'WHERE crm_notes_learner.concerns = "L"'],
                    2 => [2, 'Employer', null, 'WHERE crm_notes_learner.concerns = "E"'],
                    3 => [3, 'Both', null, 'WHERE crm_notes_learner.concerns = "B"'],
                ];
                $f = new DropDownViewFilter('filter_concerns', $options, 0, false);
                $f->setDescriptionFormat("Concerns: %s");
                $view->addFilter($f);

                $reasons = InductionHelper::getListLARReason();
                $options = [];
                foreach($reasons AS $reason_key => $reason_value)
                {
		    if(!in_array($reason_key, [28, 29, 2, 9, 5, 1, 16, 35, 36]))
                        continue;
                    $options[] = [$reason_key, $reason_value, null, "WHERE crm_notes_learner.reason = '{$reason_key}'"];
                }
                $f = new DropDownViewFilter('filter_reason', $options, null, true);
                $f->setDescriptionFormat("Reason: %s");
                $view->addFilter($f);

		$options = "SELECT DISTINCT users.`id`, CONCAT(users.`firstnames`, ' ', users.`surname`), NULL, CONCAT('WHERE tr.coordinator=', users.id) FROM users WHERE users.`id` IN (SELECT tr.coordinator FROM tr) ORDER BY users.firstnames;";
                $f = new DropDownViewFilter('filter_coordinator', $options, null, true);
                $f->setDescriptionFormat("Coordinator: %s");
                $view->addFilter($f);		
            }	
	    $options = "SELECT DISTINCT users.`id`, CONCAT(users.`firstnames`, ' ', users.`surname`), NULL, CONCAT('WHERE tr.assessor=', users.id) FROM users WHERE users.`id` IN (SELECT tr.assessor FROM tr) ORDER BY users.firstnames;";
            $f = new DropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);
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
            echo '<thead height="40px"><tr><th>&nbsp;</th><th class="topRow">L03</th><th class="topRow">Learner</th>';
            echo '<th class="topRow">Assessor</th><th>Coordinator</th><th>Framework</th><th class="topRow">Contract</th><th class="topRow">Employer</th>';
            echo '<th class="topRow">Name of Person</th><th class="topRow">Position</th><th class="topRow">Type of Contact</th>';
            echo '<th class="topRow">Subject</th><th class="topRow">Date</th><th class="topRow">By Whom</th><th class="topRow">Position</th>';
            echo '<th class="topRow">Next Action Date</th><th class="topRow">Agreed Action</th>';
            if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
            {
                echo '<th class="topRow">Rating</th><th class="topRow">Concerns</th><th class="topRow">Reason</th>';
            }
	    echo '</tr></thead>';	
            echo '<tbody>';
            while($row = $st->fetch())
            {

                echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_crm_note&id=' . $row['id'] . '&tr_id=' . $row['tr_id']);
                echo '<td><img src="/images/text-left.png" border="0" /></td>';
                echo '<td align="left">' . HTML::cell($row["l03"]) . '</td>';
                echo '<td align="left">' . HTML::cell($row["learner"]) . '</td>';
                echo '<td align="left">' . HTML::cell($row["assessor"]) . '</td>';
                echo '<td align="left">' . HTML::cell($row["coordinator"]) . '</td>';
                echo '<td align="left">' . HTML::cell($row["framework"]) . '</td>';
                echo '<td align="left">' . HTML::cell($row["contract"]) . '</td>';
                echo '<td align="left">' . HTML::cell($row["employer"]) . '</td>';
                echo '<td align="left">' . HTML::cell($row['name_of_person']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['position']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['type_of_contact']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['subject']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['date']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['by_whom']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['whom_position']) . '</td>';
                if($row['next_action_date'] != '')
                {
                    $row['next_action_date'] = strtotime($row['next_action_date']);
                    $row['next_action_date'] = date('d/m/Y',$row['next_action_date']);
                }
                echo '<td align="left">' . HTML::cell($row['next_action_date']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['agreed_action']) . '</td>';
		if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
                {
                    echo '<td>' . $row['rating'] . '</td>';
                    echo '<td>' . $row['concerns'] . '</td>';
                    echo '<td>' . $row['reason'] . '</td>';
                }

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
}
?>