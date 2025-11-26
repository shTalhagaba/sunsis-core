<?php
class sa_crontab extends ActionController
{
	/**
	 * @param PDO $link
	 * @override
	 * @throws UnauthorizedException
	 * @throws Exception
	 */
	public function indexAction(PDO $link)
	{
		if(!$_SESSION['user']->isAdmin() || (!SOURCE_LOCAL && !SOURCE_HOME && !SOURCE_BLYTHE_VALLEY)) {
			throw new UnauthorizedException();
		}

		$view = $this->buildView($link);
		$view->refresh($link, $_REQUEST);

		if (!DAO::schemaEntityExists($link, null, 'crontab')) {
			throw new Exception("Missing 'crontab' table in database. Please update the schema.");
		}
		if (!DAO::schemaEntityExists($link, null, 'crontab_config')) {
			throw new Exception("Missing 'crontab_config' table in database. Please update the schema.");
		}

		// Provide a means for direct editing of the SystemConfig variable: crontab.enabled
		$schedulerStatus = SystemConfig::get('crontab.enabled');
		if (!$schedulerStatus) {
			$schedulerStatus = 0;
		}
		$schedulerStatusOptions = array(array('0', 'Disabled'), array('1', 'Enabled'));

		include('smartassessor/crontab/tpl_view.php');
	}


	/**
	 * @param PDO $link
	 * @throws UnauthorizedException
	 * @throws Exception
	 */
	public function readAction(PDO $link)
	{
		if(!$_SESSION['user']->isAdmin() || (!SOURCE_HOME && !SOURCE_BLYTHE_VALLEY)) {
			throw new UnauthorizedException();
		}

		$id = $this->_getParam('id');
		if (!$id || !is_numeric($id)) {
			throw new Exception("Missing or non-numeric id: $id");
		}

		$action = CrontabAction::loadFromDatabase($link, $id);

		include('smartassessor/crontab/tpl_read.php');
	}


	public function editAction(PDO $link)
	{
		if(!$_SESSION['user']->isAdmin() || (!SOURCE_HOME && !SOURCE_BLYTHE_VALLEY)) {
			throw new UnauthorizedException();
		}

		$id = $this->_getParam('id');
		if ($id && !is_numeric($id)) {
			throw new Exception("Non-numeric id: $id");
		}

		if ($id) {
			$action = CrontabAction::loadFromDatabase($link, $id);
			if(!$action) {
				throw new Exception("Could not find a record for action with id #".$id);
			}
		} else {
			$action = CrontabAction::getInstance('Noop');
			$action->order = 10;
			$action->minute = '0';
			$action->hour = '*';
			$action->day_of_month = '*';
			$action->month = '*';
			$action->day_of_week = '*';
		}

		$authorities = array('Sunesis', 'SmartAssessor');
		$tasks = array(
			array('SynchroniseLearners', 'Synchronise Learners'),
			array('SynchroniseEmployers', 'Synchronise Employers'),
			array('SynchroniseAssessors', 'Synchronise Assessors'),
			array('SynchroniseReviews', 'Synchronise Reviews'),
			array('SynchroniseProgressTrack', 'Synchronise Progresstrack'),
			array('SynchroniseLearnerQualification', 'Synchronise Learner Qualification'),
            array('SynchroniseLearnerAssessors', 'Synchronise Learner Assessors'),
            array('SynchroniseLearnerIV', 'Synchronise Learner IV'),
            array('SynchroniseLearnersSurveyLink', 'Synchronise Learner Survey Link'),
			array('Noop', 'No Operation (Noop)')
		);

		include('smartassessor/crontab/tpl_edit.php');
	}

	public function deleteAction(PDO $link)
	{
		$id = $this->_getParam('id');
		if (!$id || !is_numeric($id)) {
			throw new Exception("Missing or non-numeric querystring argument 'id'");
		}

		$action = CrontabAction::loadFromDatabase($link, $id);
		$action->delete($link);
	}

	public function editConfigurationAction(PDO $link)
	{
		if(!$_SESSION['user']->isAdmin() || (!SOURCE_HOME && !SOURCE_BLYTHE_VALLEY)) {
			throw new UnauthorizedException();
		}

		$id = $this->_getParam('id');
		if ($id && !is_numeric($id)) {
			$id = null;
		}

		$task = $this->_getParam('task');
                
		if (!$task) {
			throw new Exception("Missing parameter 'task'");
		}

		if ($id) {
			$action = CrontabAction::loadFromDatabase($link, $id);
			if (!$action) {
				throw new Exception("Could not find a record for action with id #".$id);
			}
			if ($action->task != $task) {
				$action = CrontabAction::getInstance($task);
			}
		} else {
			//$actionType = $this->_getParam('task');
			$action = CrontabAction::getInstance($task);
		}

                             
		$this->renderActionConfigurationEdit($action);
	}

	public function updateCrontabSettingsAction(PDO $link)
	{
		$enabled = $this->_getParam('enabled');
		if($enabled) {
			SystemConfig::set('crontab.enabled', 1);
		} else {
			SystemConfig::set('crontab.enabled', 0);
		}
	}

	/**
	 * @param PDO $link
	 * @throws UnauthorizedException
	 * @throws Exception
	 */
	public function saveAction(PDO $link)
	{
		if(!$_SESSION['user']->isAdmin() || (!SOURCE_HOME && !SOURCE_BLYTHE_VALLEY)) {
			throw new UnauthorizedException();
		}

		// We need the task type before we can instantiate the CrontabAction object
		$task = $this->_getParam('task');
		if (!$task) {
			throw new Exception("Missing parameter 'task'");
		}

		// Instantiate, populate and save the CrontabAction object
		$action = CrontabAction::getInstance($task);
		$action->set($_REQUEST);
		$action->save($link);

		// Return the ID of the CrontabAction object so
		// the browser can be navigated to the record in read mode
		header("Content-Type:text/plain");
		echo $action->id;
	}

	public function runAction(PDO $link)
	{
		if(!$_SESSION['user']->isAdmin() || (!SOURCE_HOME && !SOURCE_BLYTHE_VALLEY)) {
			throw new UnauthorizedException();
		}

		$id = $this->_getParam('id');
		if (!$id || !is_numeric($id)) {
			throw new Exception("Missing or non-numeric parameter 'id'");
		}

		$action = CrontabAction::loadFromDatabase($link, $id);
		if (!$action) {
			throw new Exception("No scheduled task could be found with id #" . $id);
		}

		// Logger
		$columnMap = array(
			'priority' => 'priority',
			'priority_name' => 'priorityName',
			'message' => 'message',
			'timestamp' => 'timestamp',
			'crontab_id' => 'crontab_id'
		);
		$linkLog = DAO::getConnection(null, null, null, null, null, false); // Use a separate connection for logging
		$writerDb = new LogWriterDb($linkLog, 'crontab_log', $columnMap);
		$logger = new Zend_Log($writerDb);
		$logger->addWriter(new Zend_Log_Writer_Stream('php://output'));
		$action->setLog($logger);

		header("Content-Type: text/html");

		// We need output buffering because we are also logging to STDOUT
		ob_start();
		try
		{
			DAO::transaction_start($link);
			$action->log('Task started', Zend_Log::INFO);
			$action->execute($link);
			$action->log('Task completed', Zend_Log::INFO);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			try {
				DAO::transaction_rollback($link);
			} catch (Exception $e) {
				// If we cannot rollback, keep going
			}
			$action->log($e, Zend_Log::ERR);
			$action->log('Task aborted', Zend_Log::INFO);
		}
		$str = ob_get_clean();

		echo nl2br((string) $str);
	}

	/**
	 * @param PDO $link
	 * @return View
	 */
	private function buildView(PDO $link)
	{
		$key = "view_sa_crontab";
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		}

		$sql = <<<SQL
SELECT
	crontab.*
FROM
	crontab
WHERE
	`task` IN ('SynchroniseLearners', 'SynchroniseEmployers', 'SynchroniseAssessors', 'SynchroniseReviews','SynchroniseProgresstrack','SynchroniseLearnerQualification','SynchroniseLearnerAssessors','SynchroniseLearnerIV','SynchroniseLearnersSurveyLink','Noop')
ORDER BY
	`order` ASC, `id` ASC
SQL;

		$view = new View();
		$view->setSQL($sql);

		// Add view filters
		$options = array(
			array(50,50,null,null),
			array(100,100,null,null),
			array(0,'No limit',null,null));
		$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 50, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		$_SESSION[$key] = $view;
		return $view;
	}


	private function renderView(PDO $link, View $view)
	{
		echo '<table class="resultset" cellspacing="0" cellpadding="4" style="width:800px">';
		echo '<tr class="topRow"><th colspan="3" style="border-right-width:2px; border-right-style:solid">Task</th><th colspan="6">Schedule</th></tr>';
		echo '<tr><th>ID</th><th>Order</th><th style="border-right-width:2px; border-right-style:solid">Task Name</th><th>Enabled</th><th>Minute</th><th>Hour</th><th>Day of Month</th><th>Month</th><th>Weekday</th></tr>';
		$sql = $view->getSQLStatement()->__toString();
		$st = $link->query($sql);
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			echo HTML::viewrow_opening_tag('do.php?_action=sa_crontab&subaction=read&id='.$row['id']);
			echo '<td align="right">', $row['id'], '</td>';
			echo '<td align="right">', $row['order'], '</td>';
			echo '<td align="left" style="border-right-width:2px; border-right-style:solid">', $row['task'], '</td>';
			echo '<td align="center">', HTML::yesNoUnknown($row['enabled']), '</td>';
			echo '<td align="center">', $row['minute'], '</td>';
			echo '<td align="center">', $row['hour'], '</td>';
			echo '<td align="center">', $row['day_of_month'], '</td>';
			echo '<td align="center">', $row['month'], '</td>';
			echo '<td align="center">', $row['day_of_week'], '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}


/*	private function renderActionConfigurationRead($action)
	{
		$subClass = new ReflectionClass($action);
		$parentClass = new ReflectionClass('CrontabAction');

		// Subclass properties
		$props = array();
		foreach($subClass->getProperties() as $prop) {
			if ($prop->isPublic()) {
				$props[] = $prop->getName();
			}
		}
		$subclassProperties = $props;

		// Parent class properties
		$props = array();
		foreach($parentClass->getProperties() as $prop) {
			if ($prop->isPublic()) {
				$props[] = $prop->getName();
			}
		}
		$parentClassProperties = $props;

		// Subclass only properties
		$properties = array_diff($subclassProperties, $parentClassProperties);

		echo <<<HTML
<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">
	<col width="150"/>
HTML;
		foreach ($properties as $prop) {
			echo '<tr>';
			echo '<td class="fieldLabel">', htmlspecialchars((string)$prop), '</td>';
			echo '<td class="fieldValue">', htmlspecialchars((string)$action->$prop), '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}*/

	private function renderActionConfigurationRead(CrontabAction $action)
	{
		switch ($action->task)
		{
			case "Noop":
            case "SynchroniseReviews":
            case "SynchroniseProgressTrack":
            case "SynchroniseLearnerQualification":
            case "SynchroniseLearnerAssessors":
            case "SynchroniseLearnerIV":
            case "SynchroniseLearnersSurveyLink":
				echo '<p class="sectionDescription">This task has no configuration options</p>';
				break;

			case 'SynchroniseEmployers':
				echo '<p class="sectionDescription">The authoritative system for each field in the employer record. If no system is specified, the field will not be synchronised.</p>';
				echo '<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">';
				echo '<col width="150"/>';
				echo '<tr><td class="fieldLabel">Name</td><td class="fieldValue">', htmlspecialchars((string)$action->Name), '</td></tr>';
				echo '<tr><td class="fieldLabel">EDS ID</td><td class="fieldValue">', htmlspecialchars((string)$action->EdsId), '</td></tr>';
				echo '<tr><td class="fieldLabel">Address Line 1</td><td class="fieldValue">', htmlspecialchars((string)$action->AddressLine1), '</td></tr>';
				echo '<tr><td class="fieldLabel">Address Line 2</td><td class="fieldValue">', htmlspecialchars((string)$action->AddressLine2), '</td></tr>';
				echo '<tr><td class="fieldLabel">Address Town</td><td class="fieldValue">', htmlspecialchars((string)$action->AddressTown), '</td></tr>';
				echo '<tr><td class="fieldLabel">Address County</td><td class="fieldValue">', htmlspecialchars((string)$action->AddressCounty), '</td></tr>';
				echo '<tr><td class="fieldLabel">Address Postcode</td><td class="fieldValue">', htmlspecialchars((string)$action->AddressPostCode), '</td></tr>';
				echo '<tr><td class="fieldLabel">Telephone</td><td class="fieldValue">', htmlspecialchars((string)$action->Telephone), '</td></tr>';
				echo '<tr><td class="fieldLabel">Key Contact Name</td><td class="fieldValue">', htmlspecialchars((string)$action->KeyContactName), '</td></tr>';
				echo '<tr><td class="fieldLabel">Key Contact Email</td><td class="fieldValue">', htmlspecialchars((string)$action->KeyContactEmail), '</td></tr>';
				echo '</table>';
				break;

			case 'SynchroniseLearners':
				echo '<p class="sectionDescription">The authoritative system for each field in the learner record. If no system is specified, the field will not be synchronised.</p>';
				echo '<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">';
				echo '<col width="150"/>';
				echo '<tr><td class="fieldLabel">Family Name</td><td class="fieldValue">', htmlspecialchars((string)$action->FamilyName), '</td></tr>';
				echo '<tr><td class="fieldLabel">Given Names</td><td class="fieldValue">', htmlspecialchars((string)$action->GivenNames), '</td></tr>';
				echo '<tr><td class="fieldLabel">ULN</td><td class="fieldValue">', htmlspecialchars((string)$action->ULN), '</td></tr>';
				echo '<tr><td class="fieldLabel">Date of Birth</td><td class="fieldValue">', htmlspecialchars((string)$action->DateOfBirth), '</td></tr>';
				echo '<tr><td class="fieldLabel">Sex</td><td class="fieldValue">', htmlspecialchars((string)$action->Sex), '</td></tr>';
				echo '<tr><td class="fieldLabel">NI Number</td><td class="fieldValue">', htmlspecialchars((string)$action->NINumber), '</td></tr>';
				echo '<tr><td class="fieldLabel">Domicile</td><td class="fieldValue">', htmlspecialchars((string)$action->Domicile), '</td></tr>';
				echo '<tr><td class="fieldLabel">Email</td><td class="fieldValue">', htmlspecialchars((string)$action->Email), '</td></tr>';
				echo '<tr><td class="fieldLabel">TelNumber</td><td class="fieldValue">', htmlspecialchars((string)$action->TelNumber), '</td></tr>';
				echo '<tr><td class="fieldLabel">Mobile</td><td class="fieldValue">', htmlspecialchars((string)$action->Mobile), '</td></tr>';
				echo '<tr><td class="fieldLabel">Disability</td><td class="fieldValue">', htmlspecialchars((string)$action->LlddDisability), '</td></tr>';
				echo '<tr><td class="fieldLabel">Learning Difficulty</td><td class="fieldValue">', htmlspecialchars((string)$action->LlddLearningDifficulty), '</td></tr>';
				echo '<tr><td class="fieldLabel">Home Address Line 1</td><td class="fieldValue">', htmlspecialchars((string)$action->HomeAddressLine1), '</td></tr>';
				echo '<tr><td class="fieldLabel">Home Address Locality</td><td class="fieldValue">', htmlspecialchars((string)$action->HomeAddressLocality), '</td></tr>';
				echo '<tr><td class="fieldLabel">Home Address Town</td><td class="fieldValue">', htmlspecialchars((string)$action->HomeAddressTown), '</td></tr>';
				echo '<tr><td class="fieldLabel">Home Address County</td><td class="fieldValue">', htmlspecialchars((string)$action->HomeAddressCounty), '</td></tr>';
				echo '<tr><td class="fieldLabel">Home Address Postcode</td><td class="fieldValue">', htmlspecialchars((string)$action->HomeAddressPostCode), '</td></tr>';
				echo '</table>';
				break;
            case 'SynchroniseAssessors':
				echo '<p class="sectionDescription">The authoritative system for each field in the assessors record. If no system is specified, the field will not be synchronised.</p>';
				echo '<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">';
				echo '<col width="150"/>';
				echo '<tr><td class="fieldLabel">FirstName</td><td class="fieldValue">', htmlspecialchars((string)$action->FirstName), '</td></tr>';
				echo '<tr><td class="fieldLabel">LastName</td><td class="fieldValue">', htmlspecialchars((string)$action->LastName), '</td></tr>';
				echo '<tr><td class="fieldLabel">UserName</td><td class="fieldValue">', htmlspecialchars((string)$action->UserName), '</td></tr>';
				echo '<tr><td class="fieldLabel">Region</td><td class="fieldValue">', htmlspecialchars((string)$action->Region), '</td></tr>';
				echo '<tr><td class="fieldLabel">Email </td><td class="fieldValue">', htmlspecialchars((string)$action->Email), '</td></tr>';
				echo '<tr><td class="fieldLabel">Telephone</td><td class="fieldValue">', htmlspecialchars((string)$action->Telephone), '</td></tr>';
				echo '<tr><td class="fieldLabel">Mobile</td><td class="fieldValue">', htmlspecialchars((string)$action->Mobile), '</td></tr>';
				echo '</table>';
				break;
			default:
				echo '<p>Unknown task: ' . $action->task . '</p>';
		}
	}

	private function renderActionConfigurationEdit(CrontabAction $action)
	{
		$authorities = array('Sunesis', 'SmartAssessor');
                          
		switch ($action->task)
		{
			case "Noop":
            case "SynchroniseReviews":
            case "SynchroniseProgressTrack":
            case "SynchroniseLearnerQualification":
            case "SynchroniseLearnerAssessors":
            case "SynchroniseLearnerIV":
            case "SynchroniseLearnersSurveyLink":
				echo '<p class="sectionDescription">This task has no configuration options</p>';
				break;

			case 'SynchroniseEmployers':
				echo '<p class="sectionDescription">Please indicate the authoritative system for each field in the employer record. If no system is specified, the field will not be synchronised.</p>';
				echo '<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">';
				echo '<col width="150"/>';
				echo '<tr><td class="fieldLabel">Name</td><td>', HTML::select('Name', $authorities, $action->Name, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">EDS ID</td><td>', HTML::select('EdsId', $authorities, $action->EdsId, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Address Line 1</td><td>', HTML::select('AddressLine1', $authorities, $action->AddressLine1, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Address Line 2</td><td>', HTML::select('AddressLine2', $authorities, $action->AddressLine2, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Address Town</td><td>', HTML::select('AddressTown', $authorities, $action->AddressTown, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Address County</td><td>', HTML::select('AddressCounty', $authorities, $action->AddressCounty, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Address Postcode</td><td>', HTML::select('AddressPostCode', $authorities, $action->AddressPostCode, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Telephone</td><td>', HTML::select('Telephone', $authorities, $action->Telephone, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Key Contact Name</td><td>', HTML::select('KeyContactName', $authorities, $action->KeyContactName, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Key Contact Email</td><td>', HTML::select('KeyContactEmail', $authorities, $action->KeyContactEmail, true, false) ,'</td></tr>';
				echo '</table>';
				break;

			case 'SynchroniseLearners':
				echo '<p class="sectionDescription">Please indicate the authoritative system for each field in the learner record. If no system is specified, the field will not be synchronised.</p>';
				echo '<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">';
				echo '<col width="150"/>';
				echo '<tr><td class="fieldLabel">Family Name</td><td>', HTML::select('FamilyName', $authorities, $action->FamilyName, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Given Names</td><td>', HTML::select('GivenNames', $authorities, $action->GivenNames, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">ULN</td><td>', HTML::select('ULN', $authorities, $action->ULN, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Date of Birth</td><td>', HTML::select('DateOfBirth', $authorities, $action->DateOfBirth, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Sex</td><td>', HTML::select('Sex', $authorities, $action->Sex, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">NI Number</td><td>', HTML::select('NINumber', $authorities, $action->NINumber, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Domicile</td><td>', HTML::select('Domicile', $authorities, $action->Domicile, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Email</td><td>', HTML::select('Email', $authorities, $action->Email, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">TelNumber</td><td>', HTML::select('TelNumber', $authorities, $action->TelNumber, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Mobile</td><td>', HTML::select('Mobile', $authorities, $action->Mobile, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Disability</td><td>', HTML::select('LlddDisability', $authorities, $action->LlddDisability, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Learning Difficulty</td><td>', HTML::select('LlddLearningDifficulty', $authorities, $action->LlddLearningDifficulty, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Home Address Line 1</td><td>', HTML::select('HomeAddressLine1', $authorities, $action->HomeAddressLine1, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Home Address Locality</td><td>', HTML::select('HomeAddressLocality', $authorities, $action->HomeAddressLocality, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Home Address Town</td><td>', HTML::select('HomeAddressTown', $authorities, $action->HomeAddressTown, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Home Address County</td><td>', HTML::select('HomeAddressCounty', $authorities, $action->HomeAddressCounty, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Home Address Postcode</td><td>', HTML::select('HomeAddressPostCode', $authorities, $action->HomeAddressPostCode, true, false) ,'</td></tr>';
				echo '</table>';
				break;
             case  'SynchroniseAssessors':
                echo '<p class="sectionDescription">Please indicate the authoritative system for each field in the assessors record. If no system is specified, the field will not be synchronised.</p>';
				echo '<table cellspacing="4" cellpadding="4" style="margin-left:10px; width:590px">';
				echo '<col width="150"/>';
				echo '<tr><td class="fieldLabel">FirstName</td><td>', HTML::select('FirstName', $authorities, $action->FirstName, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">LastName</td><td>', HTML::select('LastName', $authorities, $action->LastName, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">UserName</td><td>', HTML::select('UserName', $authorities, $action->UserName, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Region</td><td>', HTML::select('Region', $authorities, $action->Region, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Email</td><td>', HTML::select('Email', $authorities, $action->Email, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Telephone</td><td>', HTML::select('Telephone', $authorities, $action->Telephone, true, false) ,'</td></tr>';
				echo '<tr><td class="fieldLabel">Mobile</td><td>', HTML::select('Mobile', $authorities, $action->Mobile, true, false) ,'</td></tr>';
				echo '</table>';
				break;
			default:
				echo '<p>Unknown task: ' . $action->task . '</p>';
		}

	}
}