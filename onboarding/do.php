<?php
define('WEBROOT', __DIR__.'/');
require(WEBROOT.'lib/config.php');


// Add modules to include path
// TODO Setting the include path should not require a database connection
// TODO Sunesis modules need to be namespaced
SystemConfig::setIncludePath();

// Get Action object
$action = getAction();
if (!$action) {
	exit(0); // Fail quietly
}

// Authenticate user (unless the user is logging on -- in which case let them proceed)
if( !isset($_SESSION['user']) && !($action instanceof IUnauthenticatedAction) ) {
	throw new UnauthenticatedException();
}

// Convert UTF-8 POST data
httpPostToLatin1();

// Connect to mysql database using PDO
$link = DAO::getConnection();

// Destroy the user's session if it is too old
sessionTimeout($link);

// Pass username to Apache (in note form) for reference in the Apache LogFormat directive
if(function_exists("apache_note") && isset($_SESSION['user'])){
	apache_note("phpuser", $_SESSION['user']->username);
}

// Run control code
$action->execute($link);

// Close database connection
$link = null;

################################################################################

/**
 * @return IAction|null
 */
function getAction()
{
	// Filter and validate
	$action = isset($_REQUEST['_action'])?$_REQUEST['_action']:'';
	$action = preg_replace('/[^A-Za-z0-9\\-_]/', '', $action);
	if (empty($action)) {
		return null;
	}

	if(DB_NAME != "am_ray_recruit" AND DB_NAME != "am_lcurve_demo" AND DB_NAME != "am_baltic" AND DB_NAME != "ams")
	{
		// Load the class file
		if ((@include 'act_'.$action.'.php') != true) {
			if (SOURCE_LOCAL || SOURCE_BLYTHE_VALLEY) {
				throw new Exception("Could not find source file for action: ".$action);
			} else {
				return null;
			}
		}
	}
	else
	{
		if(belongsToRecruitmentModule($action))
		{
			// Load the class file
			if ((@include 'act_baltic_'.$action.'.php') != true) {
				if (SOURCE_LOCAL || SOURCE_BLYTHE_VALLEY) {
					throw new Exception("Could not find source file for action: ".$action);
				} else {
					return null;
				}
			}
			$action = 'baltic_' . $action;
		}
		else
		{
			// Load the class file
			if ((@include 'act_'.$action.'.php') != true) {
				if (SOURCE_LOCAL || SOURCE_BLYTHE_VALLEY) {
					throw new Exception("Could not find source file for action: ".$action);
				} else {
					return null;
				}
			}
		}
	}

	$instance = null;
	if (class_exists($action)) {
		$instance = new $action();
	} else if (SOURCE_LOCAL || SOURCE_BLYTHE_VALLEY) {
		throw new Exception("Could not find class definition for action: ".$action);
	}

	return $instance;
}

/**
 * Convert UTF-8 post to Latin-1
 */
function httpPostToLatin1()
{
	if( ($_SERVER['REQUEST_METHOD'] == 'POST') && (stripos(@$_SERVER['CONTENT_TYPE'], 'charset=UTF-8') !== FALSE) )
	{
		foreach($_POST as $key=>$value)
		{
			if(is_array($_POST[$key]))
			{
				foreach($_POST[$key] as &$v)
				{
					$v = Text::utf8_to_latin1($v);
				}
				$_REQUEST[$key] = $_POST[$key];
			}
			else
			{
				$_POST[$key] = $_REQUEST[$key] = Text::utf8_to_latin1($value);
			}
		}
	}
}

function sessionTimeout(PDO $link)
{
	if ( !isset($_SESSION['session_time']) ) {
		$_SESSION['session_time'] = time();
	}

	$system_timeout = SystemConfig::getEntityValue($link, 'system_timeout');
	if (isset($system_timeout) && is_numeric($system_timeout)) {
		// check the session time has not been exceeded
		if ( isset($_SESSION['session_time']) && (time()-$_SESSION['session_time']) > $system_timeout ) {
			session_destroy();
			http_redirect('do.php?_action=login');
		}
		else {
			// update the session_time
			$_SESSION['session_time'] = time();
		}
	}
}

function belongsToRecruitmentModule($action)
{
	$array_of_pages = array(
		'ajax_display_candidate_details',
		'ajax_display_candidate_screening',
		'ajax_display_employer_screening',
		'ajax_get_county',
		'ajax_get_email_template',
		'ajax_save_candidate_comment',
		'ajax_save_status_comment',
		'ajax_sync_outlook',
		'ajax_sync_outlook_emp_pool',
		'ajax_vacancies_crm',
		'convert_candidates',
		'delete_vacancy',
		'edit_candidate_crm',
		'edit_emails_templates',
		'fill_vacancy',
		'module_recruitment_build',
		'new_candidate',
		'read_candidate',
		'read_employers_pool_emp',
		'registration_complete',
		'report_candidates',
		'save_candidate_crm',
		'save_candidate_employer',
		'save_emails_templates',
		'save_employerpool_note',
		'save_screening_temporary',
		'send_candidate_batch_email',
		'send_candidate_cal_event',
		'send_candidate_email',
		'send_emp_pool_cal_event',
		'send_emp_pool_contact_email',
		'update_candidate',
		'vacancies_home',
		'view_candidate_employer',
		'view_candidate_register',
		'view_candidate_vacancies',
		'view_candidates',
		'view_captureinfo',
		'view_html_preview_of_email',
		'view_registered_employers',
		'view_vacancies',
		'view_vacancy',
		'check_application_status',
		'read_employer'
	);
	if(in_array($action, $array_of_pages))
		return true;
	else
		return false;

}

?>