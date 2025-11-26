<?php
class edit_learner extends ActionController
{
	public function indexAction(PDO $link)
	{
		$username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : '';
		$employer_id = isset($_REQUEST['organisations_id']) ? $_REQUEST['organisations_id'] : '';
		$employer_location_id = isset($_REQUEST['location_id']) ? $_REQUEST['location_id'] : '';

		if( (!$_SESSION['user']->isAdmin()) &&
			(!$_SESSION['user']->isOrgAdmin()) &&
			(!$_SESSION['user']->isPeopleCreator()) &&
			(!(int)$_SESSION['user']->type == User::TYPE_SALESPERSON) )
		{
			throw new UnauthorizedException();
		}

		$vo = User::loadFromDatabase($link, $username);
		if(!$vo)
		{
			throw new Exception("User '".$username."' could not be found");
		}

		$selected_tab = isset($_REQUEST['selected_tab'])?$_REQUEST['selected_tab']:'tabPersonalDetails';

		$tabPersonalDetails = "";
		$tabContactDetails = "";
		$tabLLDD = "";
		$tabDiagnostics = "";
		$tabEmployment = "";
		$tabAccess = "";
		$tabAdditionalDetails = "";

		if(isset($$selected_tab))
			$$selected_tab = "active";
		else
			$tabPersonalDetails = "active";

		if(!isset($_REQUEST['toastr_message']))
			$_SESSION['bc']->add($link, "do.php?_action=edit_learner&username={$username}&organisations_id={$employer_id}&location_id={$employer_location_id}&selected_tab={$selected_tab}", "Add/Edit Learner");

		$sql = <<<SQL
SELECT
  locations.id,
  CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
  null
FROM
  locations
WHERE
	locations.organisations_id = '$vo->employer_id'
ORDER BY full_name ;
SQL;
		$ddlEmployersLocations = DAO::getResultset($link, $sql);

		$vo->password = null; // Don't redisplay the password
		$employer_id = $vo->employer_id;
		$employer_location_id = $vo->employer_location_id;

		$ddlEmployers = DAO::getResultset($link, "SELECT id, legal_name, LEFT(legal_name, 1) FROM organisations WHERE (organisation_type = '" . Organisation::TYPE_EMPLOYER . "') ORDER BY legal_name");
		$ddlGenders = DAO::getResultset($link, "SELECT id, description, null FROM lookup_gender;");
		$ddlJobRoles = DAO::getResultset($link, "SELECT description, description FROM lookup_job_roles WHERE cat='Learner' ORDER BY description");
		$ddlReferralSources = DAO::getResultset($link, "SELECT description, description FROM lookup_referral_source ORDER BY description");
		$ddlEthnicities = DAO::getResultset($link,"SELECT Ethnicity_Code, CONCAT(Ethnicity_Code, ' ', Ethnicity_Desc), null FROM lis201112.ilr_l12_ethnicity ORDER BY Ethnicity_Code;");
		$ddlLlddHealthProb = DAO::getResultset($link,"SELECT DISTINCT LLDDInd, CONCAT(LLDDInd, ' ', LLDDInd_Desc), null FROM lis201415.ilr_llddind ORDER BY LLDDInd;");
		$ddlPriorAttain = DAO::getResultset($link,"SELECT DISTINCT code, CONCAT(description), NULL FROM central.lookup_prior_attainment WHERE code NOT IN ('101', '102') ORDER BY sorting;");
		$ddlNationalities = DAO::getResultset($link,"SELECT code, description, NULL FROM lookup_country_list ORDER BY description;");
		if(SystemConfig::getEntityValue($link, "onboarding"))
		{
			$ddlNationalities = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_nationalities ORDER BY description;");
		}
		$ddlEmploymentStatus = [
			['10', '10 In paid employment', ''],
			['11', '11 Not in paid employment and looking for work', ''],
			['12', '12 Not in paid employment and not looking for work', ''],
			['98', '98 Not known/ Not provided', '']
		];
		$ddlPreAssessment = DAO::getResultset($link,"SELECT id, description, null FROM lookup_pre_assessment;");
		$ddlWebAccount = [
			['1', 'Enabled', ''],
			['0', 'Disabled', '']
		];
		$ddlLldd = [
			['1', 'Yes'],
			['2', 'No'],
			['3', 'Prefer not to say']
		];
		$ddlLlddCat = array(
			'4' => 'Visual impairment',
			'5' => 'Hearing impairment',
			'6' => 'Disability affecting mobility',
			'7' => 'Profound complex disabilities',
			'8' => 'Social and emotional difficulties',
			'9' => 'Mental health difficulty',
			'10' => 'Moderate learning difficulty',
			'11' => 'Severe learning difficulty',
			'12' => 'Dyslexia',
			'13' => 'Dyscalculia',
			'14' => 'Autism spectrum disorder',
			'15' => 'Asperger\'s syndrome',
			'16' => 'Temporary disability after illness (for example post-viral) or accident',
			'17' => 'Speech, Language and Communication Needs',
			'93' => 'Other physical disability',
			'94' => 'Other specific learning difficulty (e.g. Dyspraxia)',
			'95' => 'Other medical condition (for example epilepsy, asthma, diabetes)',
			'96' => 'Other learning difficulty',
			'97' => 'Other disability',
			'98' => 'Prefer not to say'
		);
		$ddlQualGrades = DAO::getResultset($link,"SELECT id, description, NULL FROM lookup_gcse_grades ORDER BY id;");
		$ddlTitles = [
			['Mr', 'Mr'],
			['Mrs', 'Mrs'],
			['Miss', 'Miss'],
			['Ms', 'Ms']
		];
		$ddlQualLevels = DAO::getResultset($link,"SELECT DISTINCT id, description, NULL FROM lookup_ob_qual_levels ORDER BY id;");
		$ddlLoe = [ ['1', 'Up to 3 months'], ['2', '4-6 months'], ['3', '7-12 months'], ['4', 'more than 12 months'] ];
		array_unshift($ddlLoe, ['','Length of employment','']);
		$ddlEii = [ ['5', '0-10 hours per week'], ['6', '11-20 hours per week'], ['7', '21-30 hours per week'], ['8', '30 hours or more per week'] ];
		array_unshift($ddlEii, ['','Hours/week','']);
		$ddlLou = [ ['1', 'unemployed for less than 6 months'], ['2', 'unemployed for 6-11 months'], ['3', 'unemployed for 12-23 months'], ['4', 'unemployed for 24-35 months'], ['5', 'unemployed for over 36 months'] ];
		array_unshift($ddlLou, ['','Length of unemployment','']);
		$ddlBsi = [ ['1', 'JSA'], ['2', 'ESA WRAG'], ['3', 'Another state benefit'], ['4', 'Universal Credit'] ];
		array_unshift($ddlBsi, ['','Select benefit type if applicable','']);

		$max_file_size = str_replace("&nbsp;", " ", Repository::formatFileSize(Repository::getMaxFileSize()))." max";

		$photopath = $vo->getPhotoPath();
		if($photopath)
		{
			$photopath = "do.php?_action=display_image&username=".rawurlencode($vo->username);
		}
		else
		{
			$photopath = "/images/no_photo.png";
		}

		$toastr_message = '';
		if(isset($_REQUEST['toastr_message']) && $_REQUEST['toastr_message'] != '')
			$toastr_message = $_REQUEST['toastr_message'];

		// Presentation
		include('tpl_edit_learner.php');
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
					$where[] = "`users`.`uln` = " . $link->quote($uln);
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
	uln AS l45,
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
			$where[] = "`users`.`uln` = " . $link->quote($uln);
		}

		$where[] = "`users`.`type` = 5";

//		if(DB_NAME=="am_lead")
//		{
//			$where[] = " 1 = 2 ";
//		}
//
		$where = implode(' AND ', $where);

		$sql = <<<SQL
SELECT
	id,
	firstnames,
	surname,
	dob,
	gender,
	uln
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