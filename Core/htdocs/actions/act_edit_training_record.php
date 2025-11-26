<?php
class edit_training_record implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        //if(DB_NAME == "am_demo")
        //    http_redirect('do.php?_action=edit_training_record_v2&id='.$id);
        $username = isset($_GET['username']) ? $_GET['username'] : '';
        $pot_vo = TrainingRecord::loadFromDatabase($link, $id); /* @var $pot_vo TrainingRecord */
        $provider_id = $pot_vo->provider_id;

        $acl = ACL::loadFromDatabase($link, 'trainingrecord', $id); /* @var $acl ACL */


        // The course he is on
        $course_id = DAO::getSingleValue($link, "select course_id from courses_tr where tr_id = $id");
        $assessor_of_the_provider = " AND organisations.id = '$provider_id' ";
        if(DB_NAME == "am_superdrug")
            $assessor_of_the_provider = " ";
        if($course_id!='')
        {			$c_vo = Course::loadFromDatabase($link,$course_id);

            $tutor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
WHERE
	type=2 AND users.active = 1
ORDER BY CONCAT(firstnames, ' ', surname)
HEREDOC;

            if(DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo' or DB_NAME == "am_demo")
            {
                $assessor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname), ' - ',
		users.username
	),
	NULL
FROM
	users
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
INNER JOIN organisations ON organisations.id = users.employer_id
WHERE users.active = 1 AND TYPE!=5
ORDER BY CONCAT(firstnames, ' ', surname);
HEREDOC;

            }
            elseif(DB_NAME=='am_city_skills' or DB_NAME == "am_ela")
            {
                $assessor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
INNER JOIN organisations on organisations.id = users.employer_id $assessor_of_the_provider
where type=3 and users.active = 1
ORDER BY CONCAT(firstnames, ' ', surname)
HEREDOC;

            }
            else
            {
                $assessor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
INNER JOIN organisations on organisations.id = users.employer_id $assessor_of_the_provider
where type=3 and users.active = 1
ORDER BY CONCAT(firstnames, ' ', surname)
HEREDOC;

            }


            //	$que = "select CONCAT(firstnames, ' ', surname) from users INNER JOIN organisations on organisations.id = users.employer_id where username='$g_vo->assessor'";

            $verifier_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users

INNER JOIN organisations on organisations.id = users.employer_id and organisations.id = '$provider_id'
where type=4 AND users.active = 1 ORDER BY firstnames, surname
HEREDOC;


            $acoordinator_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users

INNER JOIN organisations on organisations.id = users.employer_id and organisations.id = '$provider_id'
where type=20 and users.active = 1
HEREDOC;

            $tutor_select = DAO::getResultset($link, $tutor_sql);
            $assessor_select = DAO::getResultset($link, $assessor_sql);
            $verifier_select = DAO::getResultset($link, $verifier_sql);
            $acoordinator_select = DAO::getResultset($link, $acoordinator_sql);
            $wbcoordinator_select = DAO::getResultset($link, "SELECT username, CONCAT(firstnames, ' ', surname),null from users INNER JOIN organisations on organisations.id = users.employer_id where type='6'");
        }
        else
        {
            $tutor_select = array();
            $assessor_select = array();
            $verifier_select = array();
            $acoordinator_select = array();
            $wbcoordinator_select = array();
        }

        if($id != '' && is_numeric($id))
        {
            // Edit existing period of training (need an ID)

            $pot_vo = TrainingRecord::loadFromDatabase($link, $id); /* @var $pot_vo TrainingRecord */




            $is_grouped = $pot_vo->isGrouped($link);

            if($is_grouped)
            {
                $assessor = DAO::getSingleValue($link, "select username from users inner join groups on groups.assessor = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$id;");
                $tutor = DAO::getSingleValue($link, "select username from users inner join groups on groups.tutor = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$id;");
                $verifier = DAO::getSingleValue($link, "select username from users inner join groups on groups.verifier = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$id;");
            }
            else
            {
                $assessor = '';
                $tutor = '';
                $verifier = '';
                $acoordinator = '';
            }


            // Check authorisation
            /*		if(!$acl->isAuthorised($_SESSION['user'], 'write') )
                    {
                        throw new UnauthorizedException();
                    }
        */
        }
        elseif($id == '' && $username != '')
        {
            // New period of training (need a username)

            $pot_vo = new TrainingRecord();

            // Add author's organisation as a default reader
            //		if(!$_SESSION['user']->isAdmin() && ($_SESSION['user']->employer_id != '') )
            //		{
            //			$acl->setIdentities('read', '*/'.$_SESSION['user']->org_short_name);
            //		}

            // Add author as default writer
            //		if(!$_SESSION['user']->isAdmin())
            //		{
            //			$acl->setIdentities('write', $_SESSION['user']->getFullyQualifiedName());
            //		}


            // Populate the new training record using a snapshot of data from the
            // user specified in the querystring (allows the "create training record" button
            // on a user record to work).
            $user = User::loadFromDatabase($link, $username); /* @var $user User */
            $pot_vo->populate($user, true);

            // Add owner of this training record as a default reader
            $acl->appendIdentities('read', $user->getFullyQualifiedName());


            // Add all the administrators of this learner's organisation as a default reader and writer
            $query = "SELECT * FROM users WHERE employer_id='$user->employer_id' and type='1'";

            $st = $link->query($query);

            while($row = $st->fetch())
            {
                $user2 = User::loadFromDatabase($link, $row['username']);
                $acl->appendIdentities('read',$user2->getFullyQualifiedName());
                $acl->appendIdentities('write',$user2->getFullyQualifiedName());
            }

        }
        else
        {
            throw new Exception("You must either specify a training record ID or a username for which a training record is to be created");
        }

        $sql = "";
        // Dropdown boxes
        if($_SESSION['user']->isAdmin() || ( in_array($_SESSION['user']->username, ['cturnbull1', 'phutchinson', 'hgibson1', 'dparks', 'leahmiller', 'atodd123', 'scooper9', 'bmilburn']) && DB_NAME == 'am_baltic'))
            $sql = "SELECT organisations.id, legal_name, lookup_org_type.org_type FROM organisations LEFT JOIN lookup_org_type  ON organisations.`organisation_type` = lookup_org_type.`id` WHERE organisation_type IN (2, 6) ORDER BY lookup_org_type.org_type, legal_name;";
        elseif($_SESSION['user']->type==8)
            $sql = "SELECT organisations.id, legal_name FROM organisations where organisations.parent_org= {$_SESSION['user']->employer_id} ORDER BY legal_name;";
        elseif($_SESSION['user']->type==20)
            $sql = "SELECT organisations.id, legal_name FROM organisations ORDER BY legal_name;";
        elseif($_SESSION['user']->isOrgAdmin())
            $sql = "SELECT organisations.id, legal_name FROM organisations where organisations.id= {$_SESSION['user']->employer_id} ORDER BY legal_name;";

        if($sql != "")
            $employers = DAO::getResultset($link, $sql);

        if($pot_vo->employer_id > 0)
        {
            $sql = "SELECT id, full_name FROM locations WHERE organisations_id = {$pot_vo->employer_id} ORDER BY is_legal_address, full_name";
            $employer_locations = DAO::getResultSet($link, $sql);
        }
        else
        {
            $employer_locations = array();
        }

        if($pot_vo->employer_id > 0)
        {
            $sql = "SELECT contact_id, contact_name FROM organisation_contact WHERE org_id = {$pot_vo->employer_id} ORDER BY contact_name";
            $crm_contacts_dropdown = DAO::getResultSet($link, $sql);
        }
        else
        {
            $crm_contacts_dropdown = array();
        }



        if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==20 || ( in_array($_SESSION['user']->username, ['phutchinson', 'hgibson1', 'dparks', 'leahmiller', 'atodd123', 'scooper9', 'bmilburn']) && DB_NAME == 'am_baltic') )
            $sql = "SELECT organisations.id, legal_name FROM organisations WHERE organisation_type like '%3%' ORDER BY legal_name;";
        elseif($_SESSION['user']->type==8)
            $sql = "SELECT organisations.id, legal_name FROM organisations WHERE organisation_type like '%3%' and organisations.id= {$_SESSION['user']->employer_id} ORDER BY legal_name;";

        $providers = DAO::getResultset($link, $sql);

        if($pot_vo->provider_id > 0)
        {
            $sql = "SELECT id, full_name FROM locations WHERE organisations_id = {$pot_vo->provider_id} ORDER BY is_legal_address, full_name";
            $provider_locations = DAO::getResultSet($link, $sql);
        }
        else
        {
            $provider_locations = array();
        }

        $status_select = DAO::getResultset($link, "SELECT code, description, null FROM lookup_pot_status ORDER BY code;");
        $gender_select = DAO::getResultset($link, "SELECT id, description FROM lookup_gender ORDER BY id;");

        //$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis201112;port=".DB_PORT, DB_USER, DB_PASSWORD);
        $L12_dropdown = DAO::getResultset($link,"SELECT distinct Ethnicity, LEFT(CONCAT(Ethnicity, ' ', Ethnicity_Desc),60), null from lis201314.ilr_ethnicity order by Ethnicity;", DAO::FETCH_NUM, "ILR2013 Ethnicity dropdown2");
        $L14_dropdown = DAO::getResultset($link,"SELECT Difficulty_Disability, CONCAT(Difficulty_Disability, ' ', Difficulty_Disability_Desc), null from lis201112.ilr_l14_difficulty_disability order by Difficulty_Disability;", DAO::FETCH_NUM, "TrainingRecord L14 dropdown");
        $L15_dropdown = DAO::getResultset($link,"SELECT Disability_Code, CONCAT(Disability_Code, ' ', Disability_Desc), null from lis201112.ilr_l15_disability order by Disability_Code;", DAO::FETCH_NUM, "TrainingRecord L15 dropdown");
        $L16_dropdown = DAO::getResultset($link,"SELECT Difficulty_Code, CONCAT(Difficulty_Code,' ',Difficulty_Desc), null from lis201112.ilr_l16_difficulty order by Difficulty_Code;", DAO::FETCH_NUM, "TrainingRecord L16 dropdown");
        $L24_dropdown = DAO::getResultset($link,"SELECT Domicile_Code, CONCAT(Domicile_Code, ' ', Domicile_Desc), null from lis201112.ilr_l24_domiciles order by Domicile_Code;", DAO::FETCH_NUM, "TrainingRecord L24 dropdown");
        $L34_dropdown = DAO::getResultset($link,"SELECT Learner_Support_Reason_Code, CONCAT(Learner_Support_Reason_Code, ' ', Learner_Support_Reason_Desc), null from lis201112.ilr_l34_learner_supp_reasons order by Learner_Support_Reason_Code;", DAO::FETCH_NUM, "TrainingRecord L34 dropdown");
        $L35_dropdown = DAO::getResultset($link,"SELECT Prior_Attainment_Level_Code, CONCAT(Prior_Attainment_Level_Code, ' ', Prior_Attainment_Level_Desc), null from lis201112.ilr_l35_prior_attainment_level order by Prior_Attainment_Level_Code;", DAO::FETCH_NUM, "TrainingRecord L35 dropdown");
        $L36_dropdown = DAO::getResultset($link,"SELECT Learner_Status_Code, CONCAT(Learner_Status_Code, ' ', Learner_Status_Desc), null from lis201112.ilr_l36_learner_status order by Learner_Status_Code;", DAO::FETCH_NUM, "TrainingRecord L36 dropdown");
        $L37_dropdown = DAO::getResultset($link,"SELECT Employment_Status_First_Code, CONCAT(Employment_Status_First_Code, ' ', Employment_Status_First_Desc), null from lis201112.ilr_l37_employ_status_firsts order by Employment_Status_First_Code;", DAO::FETCH_NUM, "TrainingRecord L37 dropdown");
        $L39_dropdown = DAO::getResultset($link,"SELECT Destination_Code, CONCAT(Destination_Code, ' ', Destination_Desc), null from lis201112.ilr_l39_destinations order by Destination_Code;", DAO::FETCH_NUM, "TrainingRecord L39 dropdown");
        $L40_dropdown = DAO::getResultset($link,"SELECT National_Learner_Event_Code, CONCAT(National_Learner_Event_Code, ' ', National_Learner_Event_Desc), null from lis201112.ilr_l40_nat_learner_events order by National_Learner_Event_Code;", DAO::FETCH_NUM, "TrainingRecord L40 dropdown");
        $L47_dropdown = DAO::getResultset($link,"SELECT Current_Emp_Status_Code, CONCAT(Current_Emp_Status_Code, ' ', Current_Emp_Status_Desc), null from lis201112.ilr_l47_current_emp_status order by Current_Emp_Status_Code;", DAO::FETCH_NUM, "TrainingRecord L47 dropdown");

        if(DB_NAME=="am_pathway" || DB_NAME=="ams")
        {
            $acm_list = DAO::getResultset($link, "SELECT id, description FROM lookup_acm ORDER BY description");
            $iv_line_manager_list = DAO::getResultset($link, "SELECT id, description FROM lookup_iv_line_manager ORDER BY description");
        }

        $ilr = DAO::getSingleValue($link, "SELECT ilr FROM ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = '$id' ORDER BY contract_year DESC LIMIT 1;");

        if($ilr != '')
        {
            $ilrobj = Ilr2011::loadFromXML($ilr);
            $l15 = $ilrobj->learnerinformation->L15;
            $l16 = $ilrobj->learnerinformation->L16;
        }
        else
        {
            $l15 = 0;
            $l16 = 0;
        }

        $contract_id = $pot_vo->contract_id;
        $contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = '$contract_id'");
        $contract= DAO::getResultset($link,"SELECT id, title from contracts where contract_year = '$contract_year' order by contract_year desc, title");

        $reasons_for_leaving_dropdown = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_reasons_for_leaving ORDER BY description;");
        if(DB_NAME=='am_pera')
            $reasons_unfunded_dropdown = DAO::getResultset($link,"SELECT id, description, null from lookup_reason_past_planned;");

        /*		// The ethnicity table has been structured to work with both the DfES standard
                // codes and the DfES extended codes. This flexibility comes at the price
                // of a slightly more complicated query to generate dropdown boxes.
                $ethnicity_select = <<<HEREDOC
        SELECT
            dfes_extended,
            IF(main_category = sub_category,
                IF(sub_category = description,
                    description,
                    CONCAT(main_category, '---', description)),
                IF(sub_category = description,
                    CONCAT(main_category, '---', description),
                    CONCAT(main_category, '---', sub_category, '::', description))) AS `desc`
        FROM
            lookup_ethnicity
        WHERE
            dfes_main = dfes_extended;
        HEREDOC;
                $ethnicity_select = DAO::getResultSet($link, $ethnicity_select);
        */

        // Create Address presentation helper
        $home_bs7666 = new Address();
        $home_bs7666->set($pot_vo, 'home_');

        $work_bs7666 = new Address();
        $work_bs7666->set($pot_vo, 'work_');

        $provider_bs7666 = new Address();
        $provider_bs7666->set($pot_vo, 'provider_');

        $enrolment_no = DAO::getSingleValue($link, "SELECT enrollment_no FROM users WHERE users.username = '{$pot_vo->username}'");

        if($pot_vo->id == '')
        {
            $js_cancel = "window.history.go(-1);";
        }
        else
        {
            $js_cancel = "window.location.replace('do.php?_action=read_training_record&id={$pot_vo->id}')";
        }

        $showWarningForZProgEmptyEndDate = $this->checkForZprogEndDate($link, $id);

        $_SESSION['bc']->add($link, "do.php?_action=edit_training_record&id=" . $id, "Edit Training Record");

        if(SOURCE_LOCAL || in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
        {
            $coaches_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
INNER JOIN organisations ON organisations.id = users.employer_id
WHERE (users.web_access = 1 AND users.type NOT IN (5, 12)
AND users.`username` NOT IN (SELECT ident FROM acl WHERE resource_category = 'application' AND privilege = 'administrator')) OR (users.id = '{$pot_vo->coach}')
ORDER BY CONCAT(firstnames, ' ', surname)
;
HEREDOC;
            $coaches_list = DAO::getResultset($link, $coaches_sql);
        }

	if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
        {
            $account_rel_manager = DAO::getSingleValue($link, "SELECT induction.arm FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.`id` WHERE inductees.`linked_tr_id` = '{$pot_vo->id}';");
            if($account_rel_manager == '')
            {
                $account_rel_manager = DAO::getSingleValue($link, "SELECT induction.arm FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.`id` 
                INNER JOIN induction_programme ON inductees.`id` = induction_programme.`inductee_id` 
                WHERE inductees.`sunesis_username` = '{$pot_vo->username}' AND induction_programme.`programme_id` = '{$course_id}';
                ");
            }
        }

        // Presentation
        if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]) && in_array($_SESSION['user']->type, [User::TYPE_ASSESSOR]))
        {
            include('tpl_edit_tr_custom_fields.php');
        }
        else
        {
            include('tpl_edit_training_record.php');
        }

    }

    public function checkForZprogEndDate(PDO $link, $id)
    {
        $showWarning = false;
        $showWarning = DAO::getSingleValue($link, "SELECT locate('1',extractvalue(ilr,'/Learner/LearningDelivery/CompStatus'))>0 FROM ilr INNER JOIN contracts ON ilr.`contract_id` = contracts.id WHERE ilr.tr_id = {$id} ORDER BY contracts.`contract_year` DESC, submission DESC LIMIT 0,1; ");
        return $showWarning;
    }
}
?>