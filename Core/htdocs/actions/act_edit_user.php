<?php
class edit_user extends ActionController
{
    /**
     * The default action
     * @override
     * @param PDO $link
     * @throws Exception|UnauthorizedException
     */
    public function indexAction(PDO $link)
    {
        $username = isset($_GET['username']) ? trim($_GET['username']) : '';
        $org_id = isset($_GET['organisations_id']) ? $_GET['organisations_id'] : '';
        $people = isset($_GET['people'])?$_GET['people']:'';
        $people_type = isset($_GET['people_type'])?$_GET['people_type']:'';
        $lrs = isset($_GET['lrs'])?$_GET['lrs']:'';

        // Assertion (suspect this has actually happened, but don't know how)
        if(!$_SESSION['user']->employer_id){
            throw new Exception("The user object stored in Session no longer has an employer_id");
        }

        // Authorisation
        if( (!$_SESSION['user']->isAdmin()) && (!$_SESSION['user']->isOrgAdmin()) && (!$_SESSION['user']->isPeopleCreator()) && (!(int)$_SESSION['user']->type==7))
        {
            throw new UnauthorizedException();
        }


        if($username && $lrs == '')
        {
            // EXISTING USER
            $vo = User::loadFromDatabase($link, $username);
            if(!$vo){
                throw new Exception("User '".$username."' could not be found");
            }
            /*		if(!$vo->employer_id){
                throw new Exception("Cannot edit user record #".$vo->id." (".$vo->username.") because it does not have an employer ID.");
            }*/
            if(DB_NAME!='am_edudo' and DB_NAME!='am_hybrid')
            {
                if(!$_SESSION['user']->isAdmin() && $vo->employer_id)
                {
                    if($_SESSION['user']->type == User::TYPE_MANAGER)
                    {
                        $user_org_parent_id = DAO::getSingleValue($link, "SELECT parent_org FROM organisations WHERE organisations.id='".addslashes((string)$vo->employer_id)."'");
                        //if($_SESSION['user']->employer_id != $vo->employer_id && $_SESSION['user']->employer_id != $user_org_parent_id){
                        //	throw new UnauthorizedException("Your user account is associated with employer #".$_SESSION['user']->employer_id.". You may not edit users from employer #".$vo->employer_id.'.');
                        //}
                    }
                    else
                    {
                        if($_SESSION['user']->employer_id != $vo->employer_id){
                            throw new UnauthorizedException("Your user account is associated with employer #".$_SESSION['user']->employer_id.". You may not edit users from employer #".$vo->employer_id.'.');
                        }
                    }
                }
            }

            $vo->password = null; // Don't redisplay the password
            $org_id = $vo->employer_id;
            $people = $vo->role;
            $people_type = $vo->type;

            if($vo->type == 1)
                $people = 'Admin';
            elseif($vo->type==8)
                $people = 'Manager';
            elseif($vo->type==2)
                $people = 'Tutor';
            elseif($vo->type==3)
                $people = 'Assessor';
            elseif($vo->type==4)
                $people = 'IQA';
            elseif($vo->type==5)
                $people = 'Learner';
            elseif($vo->type==9)
                $people = 'Supervisor';
            elseif($vo->type==10)
                $people = 'Contract';
            elseif($vo->type==11)
                $people = 'Consultant';
            elseif($vo->type==12)
                $people = 'Viewer';
            elseif($vo->type==18)
                $people = 'GlobalManager';
            elseif($vo->type==19)
                $people = 'BrandManager';
            elseif($vo->type==21)
                $people = 'CourseDirector';
            elseif($vo->type==21)
                $people = 'ApprenticeRecruitment';
            else
                $people = 'Salesman';
        }
        else
        {
            // NEW USER
            $vo = new User();
            $vo->type = $people_type;
            if($org_id != '')
                $vo->employer_id = $org_id;
            $vo->gender = null;

            $key = addslashes((string)$org_id);
//			$sql = "SELECT COUNT(*) FROM organisations WHERE id=" . $key;
//			$exists = DAO::getSingleValue($link, $sql);
//			if(!$exists){
//				throw new Exception("No organisation exists with id ".$org_id);
//			}

            if($org_id == '')
            {
                $vo->firstnames = isset($_REQUEST['firstnames'])?$_REQUEST['firstnames']:'';
                $vo->surname = isset($_REQUEST['surname'])?$_REQUEST['surname']:'';
                $vo->dob = isset($_REQUEST['dob'])?$_REQUEST['dob']:'';
                $g = isset($_REQUEST['gender'])?$_REQUEST['gender']:'';
                switch($g)
                {
                    case 1:
                        $vo->gender = 'M';
                        break;
                    case 2:
                        $vo->gender = 'F';
                        break;
                    case 9:
                        $vo->gender = 'W';
                        break;
                    default:
                        $vo->gender = 'U';
                        break;
                }
                $vo->home_postcode = isset($_REQUEST['home_postcode'])?$_REQUEST['home_postcode']:'';
                $vo->home_email = isset($_REQUEST['home_email'])?$_REQUEST['home_email']:'';
                $vo->l45 = isset($_REQUEST['uln'])?$_REQUEST['uln']:'';
                $vo->home_address_line_1 = isset($_REQUEST['lastknownaddressline1'])?$_REQUEST['lastknownaddressline1']:'';
                $vo->home_address_line_2 = isset($_REQUEST['lastknownaddressline2'])?$_REQUEST['lastknownaddressline2']:'';
                $vo->home_address_line_3 = isset($_REQUEST['lastknownaddresstown'])?$_REQUEST['lastknownaddresstown']:'';
                $vo->home_address_line_4 = isset($_REQUEST['lastknownaddresscity'])?$_REQUEST['lastknownaddresscity']:'';
                $vo->verification_type = isset($_REQUEST['verification_type'])?$_REQUEST['verification_type']:'';
                $vo->ability_to_share = isset($_REQUEST['ability_to_share'])?$_REQUEST['ability_to_share']:'';
                $vo->verification_type_other = isset($_REQUEST['verification_type_other'])?$_REQUEST['verification_type_other']:'';
            }
            else
            {
                // prefill the address fields for the organisation
                $sql = <<<HEREDOC
SELECT
	id AS employer_location_id,
	address_line_1 AS work_address_line_1,
	address_line_2 AS work_address_line_2,
	address_line_3 AS work_address_line_3,
	address_line_4 AS work_address_line_4,
	telephone as work_telephone,
	postcode AS work_postcode
FROM
	locations
WHERE
	organisations_id = '$key' AND is_legal_address=1;
HEREDOC;

                $rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                if(count($rows) > 0)
                {
                    $vo->populate($rows[0]);
                }
            }
        }//else

        if($org_id != '')
            $_SESSION['bc']->add($link, "do.php?_action=edit_user&username=" . $username . "&organisations_id=" . $org_id . "&people=" . $people . "&people_type=" . $people_type, "Add/ Edit User");



        // Dropdown boxes
//		if($vo->username!='')
//		{	
        //$sql = "SELECT organisations.id, legal_name FROM organisations where id=$vo->employer_id ORDER BY legal_name;";
        //$organisations = DAO::getResultset($link, $sql);
//		}
//		else
//		{
//			$sql = "SELECT organisations.id, legal_name FROM organisations ORDER BY legal_name;";
//			$organisations = DAO::getResultset($link, $sql);
//		}

        $parent_org = $_SESSION['user']->employer_id;

        if($_SESSION['user']->type==8)
            if($people=='Learner')
                $organisations = DAO::getResultset($link, "select organisations.id, legal_name FROM organisations WHERE (organisation_type like '%2%')  order by legal_name");
            else
                $organisations = DAO::getResultset($link, "select organisations.id, legal_name FROM organisations WHERE id = '$org_id'");
//		elseif(DB_NAME=='am_raytheon' || DB_NAME=='am_tmuk' || DB_NAME=='am_exg' || DB_NAME=='am_fareham' || DB_NAME=='am_jlr' || DB_NAME=='am_landrover' || DB_NAME=='ams' || DB_NAME=='am_demo' || DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo' || DB_NAME=='am_rttg' || DB_NAME=='am_superdrug' || DB_NAME=='am_lead' || SystemConfig::getEntityValue($link, "is_demo_system") )
        $organisations = DAO::getResultset($link, "select organisations.id, legal_name FROM organisations order by legal_name");
//		else
//			$organisations = DAO::getResultset($link, "select organisations.id, legal_name FROM organisations WHERE id = '$org_id'");

        $sql = "SELECT id, people_type FROM lookup_people_type;";
        $people_types = DAO::getResultset($link, $sql);

        if($vo->employer_id > 0)
        {
            $sql = "SELECT id, full_name FROM locations WHERE organisations_id = {$vo->employer_id} ORDER BY is_legal_address, full_name";
            $locations = DAO::getResultSet($link, $sql);
        }
        else
        {
            $locations = array();
        }

        $supervisor_dropdown = DAO::getResultSet($link, "SELECT username, concat(firstnames,' ',surname) FROM users WHERE (employer_id = '1' || employer_id = '$vo->employer_id') and type=9 ORDER BY firstnames");
        $manager_dropdown = DAO::getResultSet($link, "SELECT username, concat(firstnames,' ',surname) FROM users WHERE type!=5 and web_access = 1 ORDER BY firstnames");
        $iqa_dropdown = DAO::getResultSet($link, "SELECT id, concat(firstnames,' ',surname) FROM users where id IN (5371,23226,24165,20884,25771,27199,3324,23425,32885) ORDER BY firstnames");
        $reduced_sample_dropdown = array(array('1','Red'),array('2','Amber'),array('3','Green'));
        $logged_in = $_SESSION['user']->username;
        $emp_id = $_SESSION['user']->employer_id;
        if($_SESSION['user']->isAdmin())
            $registered_by = DAO::getResultSet($link, "SELECT username, CONCAT(firstnames,' ',surname) FROM users WHERE TYPE!=5 order by firstnames,surname");
        else
            $registered_by = DAO::getResultSet($link, "SELECT username, CONCAT(firstnames,' ',surname) FROM users WHERE TYPE!=5 and employer_id = '$emp_id' order by firstnames,surname");

        $gender = "SELECT id, description, null FROM lookup_gender;";
        $gender = DAO::getResultset($link, $gender, DAO::FETCH_NUM, "edit user lookup_gender");

        $sql = "SELECT description, description FROM lookup_job_roles where cat='$people' ORDER BY description";
        $job_role = DAO::getResultset($link, $sql);

        if(DB_NAME == "am_reed_demo" || DB_NAME == "am_reed")
        {
            $sql = "SELECT id, description FROM lookup_job_goals ORDER BY description";
            $job_goals = DAO::getResultset($link, $sql);

            $sql = "SELECT id, description FROM lookup_offices ORDER BY description";
            $offices = DAO::getResultset($link, $sql);
        }

        if(DB_NAME == "am_reed_demo" || DB_NAME == "am_reed")
        {
            if($_SESSION['user']->isAdmin())
                $sql = "SELECT lookup_referral_source.id, CONCAT(description, ' - ', legal_name) AS description FROM lookup_referral_source INNER JOIN organisations ON lookup_referral_source.provider_id = organisations.id WHERE lookup_referral_source.active = 1 ORDER BY description";
            else
                $sql = "SELECT id, description FROM lookup_referral_source WHERE provider_id = " . $_SESSION['user']->employer_id . " AND active = 1  ORDER BY description";
            if($vo->referral_source != '')
                $activeReferralSource = DAO::getSingleValue($link, "SELECT active FROM lookup_referral_source WHERE id = '{$vo->referral_source}'");
        }
        else
            $sql = "SELECT description, description FROM lookup_referral_source ORDER BY description";
        $referral_sources = DAO::getResultset($link, $sql);

        //$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis201112;port=".DB_PORT, DB_USER, DB_PASSWORD);

        $L12_dropdown = DAO::getResultset($link,"SELECT Ethnicity_Code, LEFT(CONCAT(Ethnicity_Code, ' ', Ethnicity_Desc), 50), null from lis201112.ilr_l12_ethnicity order by Ethnicity_Code;");

        // ILR Fields Dropdowns
        $L24_dropdown = "SELECT Domicile_Code, LEFT(CONCAT(Domicile_Code, ' ', Domicile_Desc),50), null from lis201112.ilr_l24_domiciles order by Domicile_Code;";
        $L24_dropdown = DAO::getResultset($link,$L24_dropdown, DAO::FETCH_NUM, "edit user L24");

        $LLDDHealthProb_dropdown = DAO::getResultset($link,"SELECT distinct LLDDInd, LEFT(CONCAT(LLDDInd, ' ', LLDDInd_Desc),50), null from lis201415.ilr_llddind order by LLDDInd;", DAO::FETCH_NUM, "ILR2014 LLDDInd dropdown");
        $LLDDDS_dropdown = DAO::getResultset($link,"SELECT distinct LLDDCode, LEFT(CONCAT(LLDDCode, ' ', LLDDCode_Desc),50), null from lis201415.ilr_llddcode where LLDDType='DS' order by LLDDCode;", DAO::FETCH_NUM, "ILR2014 LLDDDS dropdown");
        $LLDDLD_dropdown = DAO::getResultset($link,"SELECT distinct LLDDCode, LEFT(CONCAT(LLDDCode, ' ', LLDDCode_Desc),50), null from lis201415.ilr_llddcode where LLDDType='LD' order by LLDDCode;", DAO::FETCH_NUM, "ILR2014 LLDDLD dropdown");

        $PriorAttain_dropdown = DAO::getResultset($link,"SELECT distinct PriorAttain, LEFT(CONCAT(PriorAttain, ' ', PriorAttainDesc),50), null from lis201415.ilr_priorattain order by PriorAttain;", DAO::FETCH_NUM, "ILR2014 PriorAttain dropdown2");

        $L36_dropdown = "SELECT Learner_Status_Code, LEFT(CONCAT(Learner_Status_Code,' ', Learner_Status_Desc),50) ,null from lis201112.ilr_l36_learner_status order by Learner_Status_Code;";
        $L36_dropdown = DAO::getResultset($link,$L36_dropdown, DAO::FETCH_NUM, "edit user L36");

        $employment_status = array(
            array('10', '10 In paid employment', ''),
            array('11', '11 Not in paid employment and looking for work', ''),
            array('12', '12 Not in paid employment and not looking for work', ''),
            array('98', '98 Not known/ Not provided', ''));

        $L47_dropdown = "SELECT Current_Emp_Status_Code, LEFT(CONCAT(Current_Emp_Status_Code, ' ', Current_Emp_Status_Desc),50),null from lis201112.ilr_l47_current_emp_status order by Current_Emp_Status_Code;";
        $L47_dropdown = DAO::getResultset($link,$L47_dropdown, DAO::FETCH_NUM, "edit user L47");

        $L39_dropdown = "SELECT Destination_Code, LEFT(CONCAT(Destination_Code,' ', Destination_Desc),50) ,null from lis201112.ilr_l39_destinations order by Destination_Code;";
        $L39_dropdown = DAO::getResultset($link,$L39_dropdown, DAO::FETCH_NUM, "edit user L39");

        $L40_dropdown = array(array('17','17 Learner migrated as part of provider merger'),array('18','18 Learner moved as a result of Minimum Contract Level'));

        $supervisor_checkboxes = "SELECT username, CONCAT(firstnames, ' ', Surname), null FROM users where type = 9;";
        $supervisor_checkboxes = DAO::getResultset($link, $supervisor_checkboxes);


        // $region_dropdown = array(array('North West','North West',''), array('North East','North East',''), array('Midlands','Midlands',''), array('East Midlands','East Midlands',''), array('West Midlands','West Midlands',''), array('London North','London North',''), array('London South','London South',''), array('Peterborough','Peterborough',''), array('Yorkshire','Yorkshire',''));
        $region_dropdown = 'select description, description, null from lookup_vacancy_regions order by description;';
        $region_dropdown = DAO::getResultset($link, $region_dropdown);

        $country_list = 'select code, description, null from lookup_country_list order by description;';
        $country_list = DAO::getResultset($link, $country_list);

        $linklis = '';

        $pre_assessment_dropdown = DAO::getResultset($link,"SELECT id, description, null from lookup_pre_assessment;");


        $home_address = new Address($vo, 'home_');
        $work_address = new Address($vo, 'work_');

        $web_account = array(
            array('1', 'Enabled', ''),
            array('0', 'Disabled', ''));

	$active_status = array(
            array('1', 'Yes', ''),
            array('0', 'No', ''));

        $vo->active = $vo->id == '' ? 1 : $vo->active;

		$induction_access = array(
			array('R', 'Read', ''),
			array('W', 'Write', ''),
			array('D', 'Disable', ''));

		$induction_menus = array(
			array('Dashboard', 'Dashboard', ''),
			array('Home', 'Home', ''),
			array('Settings', 'Settings', ''),
			array('Salesforce', 'Salesforce', ''));

		$op_menus = array(
			array('Dashboard', 'Dashboard', ''),
			array('Programmes', 'Programmes', ''),
			array('Scheduling', 'Scheduling', ''),
			array('Registers', 'Registers', ''));

        $record_status = array(
            array('1', 'Active', ''),
            array('0', 'Archived', ''));

        $job_readiness = array(
            array('A', 'A', ''),
            array('B', 'B', ''),
            array('C', 'C', ''));


        // Page title

        switch($people_type)
        {
            case 1:
                $people = "Admininstrator";
                break;
            case 2:
                $people = "Tutor";
                break;
            case 3:
                $people = "Assessor";
                break;
            case 4:
                $people = "IQA";
                break;
            case 5:
                $people = "Learner";
                break;
            case 6:
                $people = "Work Based Coordinator";
                break;
            case 10:
                $people = "Contract Manager";
                break;
            case 12:
                $people = "System Viewer";
                break;

        }

        $org = Organisation::loadFromDatabase($link, $vo->employer_id);
        if (!$org) {
            $org = new Organisation;
        }
        $loc = Location::loadFromDatabase($link, $vo->employer_location_id);
        if (!$loc) {
            $loc = new Location;
        }

        if (!$vo->id) {
            $page_title = 'New ' . $people;
        } else {
            if($vo->employer_id) {
                $page_title = "{$vo->firstnames} {$vo->surname}/{$org->trading_name}";
                if(strlen($page_title) > 50) {
                    $page_title = substr($page_title, 0, 50).'...';
                }
            } else {
                $page_title = "{$vo->firstnames} {$vo->surname}";
            }
        }

        $acl = new ACL();

        $max_file_size = str_replace("&nbsp;", " ", Repository::formatFileSize(Repository::getMaxFileSize()))." max";

        if(isset($_REQUEST['uln']) && $vo->l45 == '')
        {
            if($_REQUEST['uln'] != '')
                $vo->l45 = $_REQUEST['uln'];
        }

        if((DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo") && $vo->type == User::TYPE_LEARNER)
        {
            if(isset($vo->employer_id))
                $employer_business_codes = DAO::getResultSet($link, "SELECT brands.id, brands.`title`, null FROM brands INNER JOIN employer_business_codes ON brands.id = employer_business_codes.`brands_id` WHERE employer_business_codes.`employer_id` = '{$vo->employer_id}' ORDER BY brands.title");
        }

        // Presentation
        include('tpl_edit_user.php');
    }


    /**
     * Returns a JSON encoded array of similar learners
     * @param PDO $link
     * @return mixed
     */
    public function findSimilarRecordsAction(PDO $link)
    {
        $id = $this->_getParam("id");
        $firstnames = $this->_getParam("firstnames");
        $surname = $this->_getParam("surname");
        $dob = $this->_getParam("dob");
        $employerId = $this->_getParam('employer_id');
        $gender = $this->_getParam("gender");
        $uln = $this->_getParam("uln");

        // filter date of birth
        if(Date::isDate($dob)) {
            $dob = Date::toMySQL($dob);
        } else {
            $dob = null;
        }

        $where = array();
        if ($firstnames) {
            $where[] = "SOUNDEX(SUBSTRING_INDEX(`users`.`firstnames`, ' ', 1)) = SOUNDEX(SUBSTRING_INDEX(" . $link->quote($firstnames) . ", ' ', 1))";
        }
        if ($surname) {
            $where[] = "SOUNDEX(SUBSTRING_INDEX(`users`.`surname`, ' ', -1)) = SOUNDEX(SUBSTRING_INDEX(" . $link->quote($surname) . ", ' ', 1))";
        }
        if ($dob) {
            $where[] = "(`users`.`dob` = " . $link->quote($dob) . " OR `users`.`dob` IS NULL)";
        }
        if ($employerId) {
            $where[] = "`users`.`employer_id` = " . $link->quote($employerId);
        }
        /*		if ($gender) {
            $where[] = "`users`.`gender` = " . $link->quote($gender);
        }*/
        /*		if ($uln) {
            $where[] = "`users`.`l45` = " . $link->quote($uln);
        }*/
        $were[] = "`users`.`type` = 5";

        // Build core WHERE clause
        $where = implode(' AND ', $where);

        // Prepend id WHERE subclause
        if ($id) {
            $where = "`users`.`id` != " . $link->quote($id) . " AND (" . $where . ")";
        }

        $sql = "SELECT id FROM users WHERE ".$where;
        $ids = DAO::getSingleColumn($link, $sql);
        if (!$ids) {
            header("Content-Type: application/json");
            echo "[]";
            return;
        }

        $ids = DAO::quote($ids);
        $sql = <<<SQL
SELECT
	users.id,
	username,
	firstnames,
	surname,
	dob,
	gender,
	l45,
	ni,
	organisations.legal_name AS `employer`,
	(SELECT COUNT(id) FROM tr WHERE tr.username = users.username) AS `tr_count`,
	(SELECT GROUP_CONCAT(l03) FROM tr WHERE tr.username = users.username GROUP BY tr.username) AS `l03`
FROM
	users LEFT OUTER JOIN organisations
		ON users.employer_id = organisations.id
WHERE
	users.id IN ($ids);
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

        header("Content-Type: application/json");
        echo Text::json_encode_latin1($records);
    }

    public function findExistingUlnAction(PDO $link)
    {
        $id = $this->_getParam("id");
        $employerId = $this->_getParam("employer_id");
        $uln = $this->_getParam("uln");

        if (!$uln) {
            throw new Exception("Missing argument 'uln'");
        }


        $where = array();
        if ($id) {
            $where[] = "`users`.`id` != " . $link->quote($id);
        }
        //if ($employerId) {
        //	$where[] = "`users`.`employer_id` != " . $link->quote($employerId);
        //}
        if ($uln) {
            $where[] = "`users`.`l45` = " . $link->quote($uln);
        }

        $where[] = "`users`.`type` = 5";

        if(DB_NAME=="am_lead")
        {
            $where[] = " 1 = 2 ";
        }

        $where = implode(' AND ', $where);

        $sql = <<<SQL
SELECT
	id,
	firstnames,
	surname,
	dob,
	gender,
	l45
FROM
	users
WHERE
	$where;
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

        header("Content-Type: application/json");
        echo Text::json_encode_latin1($records);
    }

    public function findExistingNIAction(PDO $link)
    {
        $id = $this->_getParam("id");
        $employerId = $this->_getParam("employer_id");
        $ni = $this->_getParam("ni");

        if (!$ni) {
            throw new Exception("Missing argument 'ni'");
        }


        $where = array();
        if ($id) {
            $where[] = "`users`.`id` != " . $link->quote($id);
        }
        //if ($employerId) {
        //	$where[] = "`users`.`employer_id` != " . $link->quote($employerId);
        //}
        if ($ni) {
            $where[] = "`users`.`ni` = " . $link->quote($ni);
        }

        $where[] = "`users`.`type` = 5";

        if(DB_NAME=="am_lead")
        {
            $where[] = " 1 = 2 ";
        }

        $where = implode(' AND ', $where);

        $sql = <<<SQL
SELECT
	id,
	firstnames,
	surname,
	dob,
	gender,
	ni
FROM
	users
WHERE
	$where;
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

        header("Content-Type: application/json");
        echo Text::json_encode_latin1($records);
    }

    private function present_checkbox_values( PDO $link, $userinfoid )
    {
        $sql_lookupdata = <<<HEREDOC
				SELECT 	userinfoname,
						lookupvalues
				FROM 	users_capture_info
				WHERE 	users_capture_info.userinfoid = $userinfoid ;
HEREDOC;

        $st = $link->query($sql_lookupdata);
        if($st) {

            $lookup_gridbox = array();
            $row = $st->fetch();
            $display_name = 'meta_'.$row['userinfoname'];
            $lookup_options = explode("|", $row['lookupvalues']);
            foreach ( $lookup_options as $value ) {
                $lookup_gridbox[] = array($value,$value,NULL);
            }
            // inserts a table - need to review this?
            return HTML::checkboxGrid($display_name, $lookup_gridbox, null, 3, true);
        }
        else {
            throw new Exception('ERR: The metadata is not correctly set up');
        }

        return;
    }

    private function present_radio_values( PDO $link, $userinfoid )
    {
        $sql_lookupdata = <<<HEREDOC
				SELECT 	userinfoname,
						lookupvalues
				FROM 	users_capture_info
				WHERE 	users_capture_info.userinfoid = $userinfoid ;
HEREDOC;

        $st = $link->query($sql_lookupdata);
        if($st) {

            $lookup_gridbox = array();
            $row = $st->fetch();
            $display_name = 'meta_'.$row['userinfoname'];
            $lookup_options = explode("|", $row['lookupvalues']);
            foreach ( $lookup_options as $value ) {
                $lookup_gridbox[] = array($value,$value,NULL);
            }
            // inserts a table - need to review this?
            return HTML::radioButtonGrid($display_name, $lookup_gridbox, '');
        }
        else {
            throw new Exception('ERR: The metadata is not correctly set up');
        }

        return;
    }

    private function present_select_values( PDO $link, $userinfoid )
    {

        $sql_lookupdata = <<<HEREDOC
				SELECT 	userinfoname,
						lookupvalues
				FROM 	users_capture_info
				WHERE 	users_capture_info.userinfoid = $userinfoid ;
HEREDOC;

        $st = $link->query($sql_lookupdata);
        if($st) {

            $lookup_gridbox = array();
            $row = $st->fetch();
            $display_name = 'meta_'.$row['userinfoname'];
            $lookup_options = explode("|", $row['lookupvalues']);
            foreach ( $lookup_options as $value ) {
                $lookup_gridbox[] = array($value,$value,NULL);
            }
            return HTML::select($display_name, $lookup_gridbox, null, false, true);
        }
        else {
            throw new Exception('ERR: The metadata is not correctly set up');
        }

        return;
    }

    private function renderUserMetaData(PDO $link, User $vo)
    {
        // #171 - relmes - display user metadata
        $all_metadata_user = new User();
        $all_metadata_user->getUserMetaData($link);

        $meta_data_count = 0;
        if ( $_SESSION['user']->isAdmin() && (DB_NAME=='am_lcpa' || DB_NAME=='am_lcpa_test') ) {
            foreach( $all_metadata_user->user_metadata as $meta_group => $meta_array ) {
                echo '<h3>'.$meta_group.'</h3>';
                echo '<table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">';
                echo '<col width="150" />';
                foreach ( $meta_array as $title => $type ) {
                    $format_titles = explode("_", $title);
                    $format_details = explode("_", $type);

                    echo '<tr><td width="140" class="fieldLabel_optional" >'.$format_titles[1].'</td>';

                    $type = '';

                    if ( $vo->user_metadata && isset($vo->user_metadata[$meta_group]) && isset($vo->user_metadata[$meta_group][$format_titles[1]]) ) {
                        $type = $vo->user_metadata[$meta_group][$format_titles[1]];
                    }
                    echo '<td>';

                    switch ($format_details[1]) {
                        case 'text':
                            // echo '<textarea class="'.$element_class.'" name="'.$system_title.'"></textarea>';
                            echo '<textarea class="optional" name="meta_'.$format_titles[1].'">'.$type.'</textarea>';
                            break;
                        case 'int':
                            // echo '<select class="'.$element_class.'" name="'.$system_title.'" >';
                            echo '<select class="optional" name="meta_'.$format_titles[1].'" >';
                            for( $i = 0; $i <= 20; $i++ ) {
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                            echo '</select>';
                            break;
                        case 'float':
                            // echo '<input class="'.$element_class.'" type="text" name="'.$system_title.'"  size="10" maxlength="20"/>';
                            echo '<input class="optional" type="text" name="'.$format_titles[1].'" value="'.$type.'" size="10" maxlength="20"/>';
                            break;
                        case 'checkbox':
                            echo $this->present_checkbox_values($link, $format_titles[0]);
                            break;
                        case 'radio':
                            echo $this->present_radio_values($link, $format_titles[0]);
                            break;
                        case 'select':
                            echo $this->present_select_values($link, $format_titles[0]);
                            break;
                        // strings
                        default:
                            // echo '<input class="'.$element_class.'" type="text" name="'.$system_title.'"  size="40" maxlength="100"/>';
                            echo '<input class="optional" type="text" name="meta_'.$format_titles[1].'" value="'.$type.'" size="40" maxlength="100"/>';
                            break;
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
        }
    }
}
?>