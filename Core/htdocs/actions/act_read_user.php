<?php
class read_user implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$username = isset($_GET['username']) ? $_GET['username'] : '';
		$people = isset($_GET['people']) ? $_GET['people'] : '';
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		if(!$username && !$id)
		{
			throw new Exception("Missing or empty querystring argument 'username'");
		}

		// Create Value Object
		if ($id) {
			$vo = User::loadFromDatabaseById($link, $id);
			if (is_null($vo)) {
				throw new Exception("No user with id '$id'");
			}
		} else {
			$vo = User::loadFromDatabase($link, $username);
			if (is_null($vo)) {
				throw new Exception("No user with username '$username'");
			}
		}

		if($vo->type == User::TYPE_LEARNER && SystemConfig::getEntityValue($link, "onboarding"))
        	{
            		http_redirect("do.php?_action=read_learner&username={$username}&id={$id}");
        	}
		if($vo->type != User::TYPE_LEARNER)
        	{
            		http_redirect("do.php?_action=read_system_user&username={$username}&id={$id}");
        	}

		$_SESSION['bc']->add($link, "do.php?_action=read_user&username=" . $username, "View User");

		$isSafeToDelete = $vo->isSafeToDelete($link);

		if (empty($vo->employer_id)) {
			$o_vo = new Organisation(); // Blank organisaton value object
		} else {
			$o_vo = Organisation::loadFromDatabase($link, $vo->employer_id);
			if (!$o_vo) {
				$o_vo = new Organisation();
			}
		}

		if (empty($vo->employer_location_id)) {
			$loc = new Location(); // Blank organisaton value object
		} else {
			$loc = Location::loadFromDatabase($link, $vo->employer_location_id);
			if (!$loc) {
				$loc = new Location();
			}
		}

		// All values of L03 used for this learner
		$tr_l03 = DAO::getSingleColumn($link, "SELECT DISTINCT l03 FROM tr WHERE tr.username=" . $link->quote($vo->username) . " ORDER BY l03;");
		$tr_l03 = implode(',', $tr_l03);

		$trs = DAO::getResultset($link, "SELECT tr.id, concat(DATE_FORMAT(tr.start_date, '%d/%m/%Y'), '::', IF(tr.firstnames IS NULL, '', tr.firstnames), ' ', IF(tr.surname IS NULL, '', tr.surname), '::', IF(courses.title IS NULL, '', courses.title)) FROM tr LEFT JOIN courses_tr on courses_tr.tr_id=tr.id LEFT JOIN courses on courses.id = courses_tr.course_id where tr.username='$username';");

		if($_SESSION['user']->type==8)
			$courses = DAO::getResultSet($link, "select id, title from courses where courses.active = 1 and courses.organisations_id={$_SESSION['user']->employer_id} order by title");
		else
			$courses = DAO::getResultSet($link, "SELECT courses.id, courses.title, (SELECT title FROM frameworks WHERE id = courses.`framework_id`) AS standard FROM courses WHERE courses.active = 1 ORDER BY standard, courses.title;");

        $locations = DAO::getResultSet($link, "select id, full_name from locations WHERE organisations_id IN (SELECT id FROM organisations WHERE organisation_type = 3)");
		if(DB_NAME=="am_demo")
		{

			$sql_loc = <<<SQL
SELECT
	locations.id, CONCAT('Organisation: ', organisations.`legal_name`, ' [Location: ', locations.full_name, ' (', locations.`postcode`, ')]') AS full_name
FROM
	locations
	INNER JOIN organisations ON locations.`organisations_id` = organisations.`id`
WHERE
	organisations.`organisation_type` = 3;
SQL;

			$locations = DAO::getResultSet($link, $sql_loc);
			$locations = array();
		}


		$legal_name = $_SESSION['user']->org->legal_name;
		if($_SESSION['user']->type==8 && DB_NAME != 'am_lead')
			$contracts= DAO::getResultset($link,"SELECT id, title from contracts where active = 1 and contract_year >= 2014 and title like '%$legal_name%' order by contract_year desc, title");
		else
			$contracts = DAO::getResultSet($link, "select id, title from contracts where active = 1 and contract_year >= 2014 order by contract_year desc, title ");

		$assessors_sql = <<<SQL
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
 	users.type = 3
ORDER BY CONCAT(firstnames, ' ', surname)
SQL;
		$assessors = DAO::getResultset($link, $assessors_sql);

		$tutor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), '')
	),
	NULL
FROM
	users
WHERE
	type=2
ORDER BY
	firstnames, surname;
HEREDOC;

		$tutors = DAO::getResultset($link, $tutor_sql);

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
WHERE
	users.type = 4
ORDER BY
	firstnames, surname;
HEREDOC;
		$verifiers = DAO::getResultset($link, $verifier_sql);

		$showPanel = array_key_exists('_showPanel', $_REQUEST) ? $_REQUEST['_showPanel'] : '0'; // Default to 1 lesson


		$gender_description = "SELECT description FROM lookup_gender WHERE id='{$vo->gender}';";
		$gender_description = DAO::getSingleValue($link, $gender_description);

		$nationality_description = "SELECT description FROM lookup_country_list WHERE code='{$vo->nationality}';";
		$nationality_description = DAO::getSingleValue($link, $nationality_description);

		if($vo->numeracy>0)
			$numeracy = DAO::getSingleValue($link, 'select description from lookup_pre_assessment where id = ' . $vo->numeracy);
		else
			$numeracy = '';

		if($vo->literacy>0)
			$literacy = DAO::getSingleValue($link, 'select description from lookup_pre_assessment where id = ' . $vo->literacy);
		else
			$literacy = '';

		if($vo->ict>0)
			$ict = DAO::getSingleValue($link, 'select description from lookup_pre_assessment where id = ' . $vo->ict);
		else
			$ict = '';

		$L12_dropdown = DAO::getResultset($link,"SELECT Ethnicity_Code, LEFT(CONCAT(Ethnicity_Code, ' ', Ethnicity_Desc), 50), null from lis201112.ilr_l12_ethnicity order by Ethnicity_Code;");
		$ethnicity_description = DAO::getSingleValue($link, "select Ethnicity_Desc from lis201112.ilr_l12_ethnicity where Ethnicity_Code='$vo->ethnicity'");

		$home_address = new Address($vo, 'home_');
		$work_address = new Address($vo, 'work_');

		// Page title
		if($vo->employer_id)
		{
			if(isset($loc->full_name))
				$page_title = "{$vo->firstnames} {$vo->surname}/{$loc->full_name}/{$o_vo->trading_name}";
			else
				$page_title = "{$vo->firstnames} {$vo->surname}/{$o_vo->trading_name}";
		}
		else
		{
			$page_title = "{$vo->firstnames} {$vo->surname}";
		}
		if(strlen($page_title) > 50)
		{
			$page_title = substr($page_title, 0, 50).'...';
		}


		// get the xml for ILR
		if(DB_NAME=='am_lewisham' || DB_NAME == "am_fareham")
		{
			$tr_id = DAO::getSingleValue($link, "select id from tr where username = '$username' order by id desc limit 0,1");
			if($tr_id!='')
				$xml = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id order by contract_id desc, submission desc limit 0,1");
			else
				$xml = '';
		}

		// re 14/09/2011
		// get the learner document template
		$document_outputs = array();
		if( DB_NAME=='am_superdrug' || SystemConfig::getEntityValue($link, "learner_letters") ) {
			$document_outputs = Docx::load_learner_templates();
		}

		// Learner photo
		$photopath = $vo->getPhotoPath();
		if($photopath){
			$photopath = "do.php?_action=display_image&username=".rawurlencode($vo->username);
		} else {
			$photopath = "/images/no_photo.png";
		}

		$bil_learner = false;
		if($vo->type == User::TYPE_LEARNER)
		{
			$isThereAnyBreakInLearningTrainingRecord = DAO::getResultset($link, "SELECT * FROM tr WHERE status_code = 6 AND outcome = 3 AND username = '" . $vo->username . "' ORDER BY id DESC LIMIT 1", DAO::FETCH_ASSOC);
			$bil_learner = false;
			$previous_course_id = null;
			$previous_provider_name = null;
			$previous_assessor_id = null;
			$previous_tutor_id = null;
			$previous_verifier_id = null;
			$previous_contract_id = null;
			$previous_training_record_id = null;
			if(count($isThereAnyBreakInLearningTrainingRecord) > 0)
			{
				$bil_tr = $isThereAnyBreakInLearningTrainingRecord[0];
				$previous_course = Course::loadFromDatabase($link, DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = " . $bil_tr['id']));
				$isLearnerAlreadyReEnrolled = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.`status_code` != 6 AND tr.start_date > '" . $bil_tr['start_date'] . "' AND tr.l03 = '" . $bil_tr['l03'] . "'");
				if($isLearnerAlreadyReEnrolled == 0)
				{
					$bil_learner = true;
					$previous_course_id = $previous_course->id;
					$previous_provider_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $previous_course->organisations_id);
					$previous_assessor_id = $bil_tr['assessor'];
					$previous_tutor_id = $bil_tr['tutor'];
					$previous_verifier_id = isset($bil_tr['verifier']) ? $bil_tr['verifier'] : '';
					$previous_contract_id = $bil_tr['contract_id'];
					$previous_training_record_id = $bil_tr['id'];
				}
			}
			if(DB_NAME == "am_baltic")
			{
				$candidate_id = DAO::getSingleValue($link, "SELECT id FROM candidate WHERE candidate.username = '" . $vo->username . "'");
				if($candidate_id != '')
				{
					$view_candidate_crm = ViewCandidateCRM::getInstance($link, $candidate_id);
					$view_candidate_crm->refresh($link, $_REQUEST);
				}
			}
		}

		$LLDD = array('1' => 'Yes', '2' => 'No', '3' => 'Prefer not to say');
		$LLDDCat = array(
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

		// Presentation
		if((DB_NAME == 'am_demo' || DB_NAME == 'am_lead_demo') && $vo->type == User::TYPE_LEARNER )
			include('tpl_read_user1.php');
		else
			include('tpl_read_user.php');
	}

}
?>