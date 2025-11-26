<?php
class view_induction_reports implements IAction
{
	public function execute(PDO $link)
	{
		$subview = isset($_REQUEST['subview'])?$_REQUEST['subview']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subview == '')
			throw new Exception('Report type is missing');

		if($subview == 'sales_induction_data')
		{
			$_SESSION['bc']->add($link, "do.php?_action=view_induction_reports&subview=sales_induction_data", "Sales Induction Data");
			$view = VoltView::getViewFromSession('sales_induction_data', 'sales_induction_data'); /* @var $view View */
			if(is_null($view))
			{
				$view = $_SESSION['sales_induction_data'] = $this->buildView($link, 'sales_induction_data');
			}
			$view->refresh($_REQUEST, $link);
		}

		if($subview == 'induction_assessor_prep')
		{
			$_SESSION['bc']->add($link, "do.php?_action=view_induction_reports&subview=induction_assessor_prep", "Induction Assessor Prep");
			$view = VoltView::getViewFromSession('induction_assessor_prep', 'induction_assessor_prep'); /* @var $view View */
			if(is_null($view))
			{
				$view = $_SESSION['induction_assessor_prep'] = $this->buildView($link, 'induction_assessor_prep');
			}
			$view->refresh($_REQUEST, $link);
		}

		if($subview == 'holding_inductions')
		{
			$_SESSION['bc']->add($link, "do.php?_action=view_induction_reports&subview=holding_inductions", "Holding Inductions");
			$view = VoltView::getViewFromSession('holding_inductions', 'holding_inductions'); /* @var $view View */
			if(is_null($view))
			{
				$view = $_SESSION['holding_inductions'] = $this->buildView($link, 'holding_inductions');
			}
			$view->refresh($_REQUEST, $link);
		}

		if($subview == 'to_be_arranged')
		{
			$_SESSION['bc']->add($link, "do.php?_action=view_induction_reports&subview=to_be_arranged", "To Be Arranged");
			$view = VoltView::getViewFromSession('to_be_arranged', 'to_be_arranged'); /* @var $view View */
			if(is_null($view))
			{
				$view = $_SESSION['to_be_arranged'] = $this->buildView($link, 'to_be_arranged');
			}
			$view->refresh($_REQUEST, $link);
		}

		if($subview == 'completed_vs_live')
		{
			$_SESSION['bc']->add($link, "do.php?_action=view_induction_reports&subview=completed_vs_live", "Completed VS Live Learners");
			$view = VoltView::getViewFromSession('completed_vs_live', 'completed_vs_live'); /* @var $view View */
			if(is_null($view))
			{
				$view = $_SESSION['completed_vs_live'] = $this->buildView($link, 'completed_vs_live');
			}
			$view->refresh($_REQUEST, $link);
		}

		if($subaction == 'export_csv')
		{
			$this->exportToCSV($link, $view);
		}

		include_once('tpl_view_induction_reports.php');
	}

	private function buildView(PDO $link, $view_name)
	{
		$induction_status = null;
		$induction_status_options = array(
			0 => array('TBA', 'To Be Arranged', null, 'WHERE induction_status = "TBA"')
		,1 => array('S', 'Scheduled', null, 'WHERE induction_status = "S"')
		,2 => array('C', 'Completed', null, 'WHERE induction_status = "C"')
		,3 => array('H', 'Holding Induction', null, 'WHERE induction_status = "H"')
		,4 => array('L', 'Leaver', null, 'WHERE induction_status = "L"')
		,5 => array('W', 'Withdrawn', null, 'WHERE induction_status = "W"')
		);
		$induction_status_allow_null = true;
		if($view_name == 'sales_induction_data')
		{
			$sql = <<<SQL
SELECT DISTINCT 
	DATE_FORMAT(induction.`induction_date`, '%M %Y') AS induction_month,
	employers.legal_name AS company,
	CASE induction.`induction_status`
	  WHEN 'TBA' THEN 'To Be Arranged'
	  WHEN 'S' THEN 'Scheduled'
	  WHEN 'C' THEN 'Completed'
	  WHEN 'H' THEN 'Holding Induction'
	  WHEN 'L' THEN 'Leaver'
	  WHEN 'W' THEN 'Withdrawn'
	  ELSE ''
  END AS induction_status,
  (SELECT courses.title FROM courses WHERE courses.id = induction_programme.programme_id) AS programme,
  inductees.surname,
  inductees.firstnames,
  DATE_FORMAT(inductees.employment_start_date, '%d/%m/%Y') AS employment_start_date,
  DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date,
  CASE inductee_type
  	  WHEN 'NA' THEN 'New Apprentice'
  	  WHEN 'WFD' THEN 'WFD'
  	  WHEN 'P' THEN 'Progression'
  	  WHEN 'SSU' THEN 'New Apprentice Client Sourced'
  	  WHEN '3AAA' THEN '3AAA Transfer'
  END AS learner_type,
  CASE inductees.`employer_type`
	  WHEN 'AM' THEN 'Account Management'
	  WHEN 'NB' THEN 'New Business'
	  WHEN 'SG' THEN 'EEM Self Generated'
	  WHEN 'L' THEN 'Levy'
	  WHEN 'SC' THEN 'Senior Consultant - Levy'
      WHEN 'EE' THEN 'EEM Self'
      WHEN 'LS' THEN 'Levy Team Self Gen'
      WHEN 'LT' THEN 'Levy Team'
      WHEN 'NT' THEN 'Non Levy Team'
      WHEN 'NG' THEN 'Non Levy Self Gen'
      WHEN 'NM' THEN 'Non Levy Account Management'
      WHEN 'LM' THEN 'Levy Account Management'
      WHEN 'PG' THEN 'Specialist Self Gen'
  END AS `employer_type`,
  induction.brm AS bdm,
  induction.resourcer AS recruiter,
  induction.lead_gen,
  CASE TRUE
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) > 24 THEN '24+'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS age_group,
  DATE_FORMAT(dob, '%d/%m/%Y') AS dob,
  ((DATE_FORMAT(induction_date,'%Y') - DATE_FORMAT(dob,'%Y')) - (DATE_FORMAT(induction_date,'00-%m-%d') < DATE_FORMAT(dob,'00-%m-%d'))) AS age_at_induction,
  CASE induction.`levy_payer`
	  WHEN 'Y' THEN 'Yes'
	  WHEN 'N' THEN 'No'
  END AS `levy_payer`,
  CASE induction.`levy_app_completed`
	  WHEN 'Y' THEN 'Yes'
	  WHEN 'N' THEN 'No'
	  WHEN 'NA' THEN 'N/A'
  END AS `levy_app_completed`,
  CASE induction.iag_numeracy
  	  WHEN 'E1' THEN 'Entry Level 1'
  	  WHEN 'E2' THEN 'Entry Level 2'
  	  WHEN 'E3' THEN 'Entry Level 3'
  	  WHEN 'L1' THEN 'Level 1'
  	  WHEN 'L2' THEN 'Level 2'
  	  WHEN 'L3' THEN 'Level 3'
  	  WHEN 'U1' THEN 'Unclassified'
	  WHEN 'NA' THEN 'N/A'
	ELSE iag_numeracy
  END AS maths,
  CASE induction.iag_literacy
  	  WHEN 'E1' THEN 'Entry Level 1'
  	  WHEN 'E2' THEN 'Entry Level 2'
  	  WHEN 'E3' THEN 'Entry Level 3'
  	  WHEN 'L1' THEN 'Level 1'
  	  WHEN 'L2' THEN 'Level 2'
  	  WHEN 'L3' THEN 'Level 3'
  	  WHEN 'U1' THEN 'Unclassified'
	  WHEN 'NA' THEN 'N/A'
	ELSE iag_literacy
  END AS english,
  CASE induction.iag_ict
  	  WHEN 'E1' THEN 'Entry Level 1'
  	  WHEN 'E2' THEN 'Entry Level 2'
  	  WHEN 'E3' THEN 'Entry Level 3'
  	  WHEN 'L1' THEN 'Level 1'
  	  WHEN 'L2' THEN 'Level 2'
  	  WHEN 'L3' THEN 'Level 3'
  	  WHEN 'U1' THEN 'Unclassified'
	  WHEN 'NA' THEN 'N/A'
  END AS ict,
   CASE induction.`sla_received`
	  WHEN 'YN' THEN 'Yes New'
	  WHEN 'YO' THEN 'Yes Old'
	  WHEN 'YO' THEN 'Yes Old'
	  WHEN 'N' THEN 'No'
	  WHEN 'R' THEN 'Rejected'
	  ELSE ''
  END AS sla_received,
  CASE induction.`commit_statement`
      WHEN 'NS' THEN 'Not Sent'
      WHEN 'S' THEN 'Sent'
      WHEN 'FC' THEN 'Fully Completed'
  END AS commitment_statement,
  extractvalue(induction_notes, '/Notes/Note[last()]/Note') AS last_induction_comment,
  extractvalue(grey_section_comments, '/Notes/Note[last()]/Note') AS last_holding_induction_comment,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = induction.induction_owner) AS induction_owner,
  CASE induction_programme.`eligibility_test_status`
	  WHEN 'S' THEN 'Started'
	  WHEN 'C' THEN 'Completed'
	  WHEN 'O' THEN 'Outstanding'
	  WHEN 'NA' THEN 'Not Applicable'
  END AS `eligibility_test_status`,
  CASE inductees.`gender`
	  WHEN 'F' THEN 'Female'
	  WHEN 'M' THEN 'Male'
	  WHEN 'U' THEN 'Unknown'
	  WHEN 'W' THEN 'Withheld'
  END AS `gender`,
  CASE induction.`webcam`
	  WHEN 'NR' THEN 'Not Required'
	  WHEN 'S' THEN 'Sent'
	  WHEN '' THEN ''
  END AS `webcam`,
  DATE_FORMAT(induction_arranged, '%d/%m/%Y') AS induction_arranged,
  induction.emp_recruiter AS employer_recruiter
FROM
	inductees
	  LEFT JOIN induction
	    ON inductees.id = induction.inductee_id
	  LEFT JOIN induction_programme
	    ON induction_programme.inductee_id = inductees.id
	  LEFT JOIN organisations AS employers
	    ON employers.id = inductees.employer_id
;

SQL;
		}
		if($view_name == 'induction_assessor_prep')
		{
			$sql = <<<SQL
SELECT DISTINCT 
	employers.legal_name AS company,
	(SELECT courses.title FROM courses WHERE courses.id = induction_programme.programme_id) AS programme,
	inductees.surname,
    inductees.firstnames,
    inductees.`work_email`,
    inductees.`home_email` AS `personal_email`,
    inductees.`ni` AS national_insurance,
    #(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = induction.assigned_assessor) AS assigned_assessor,
	#(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = induction.assigned_coord) AS assigned_coordinator,
     CASE induction.iag_numeracy
  	  WHEN 'E1' THEN 'Entry Level 1'
  	  WHEN 'E2' THEN 'Entry Level 2'
  	  WHEN 'E3' THEN 'Entry Level 3'
  	  WHEN 'L1' THEN 'Level 1'
  	  WHEN 'L2' THEN 'Level 2'
  	  WHEN 'L3' THEN 'Level 3'
  	  WHEN 'U1' THEN 'Unclassified'
	ELSE iag_numeracy
  END AS maths,
  CASE induction.iag_literacy
  	  WHEN 'E1' THEN 'Entry Level 1'
  	  WHEN 'E2' THEN 'Entry Level 2'
  	  WHEN 'E3' THEN 'Entry Level 3'
  	  WHEN 'L1' THEN 'Level 1'
  	  WHEN 'L2' THEN 'Level 2'
  	  WHEN 'L3' THEN 'Level 3'
  	  WHEN 'U1' THEN 'Unclassified'
	ELSE iag_literacy
  END AS english,
  CASE induction.iag_ict
  	  WHEN 'E1' THEN 'Entry Level 1'
  	  WHEN 'E2' THEN 'Entry Level 2'
  	  WHEN 'E3' THEN 'Entry Level 3'
  	  WHEN 'L1' THEN 'Level 1'
  	  WHEN 'L2' THEN 'Level 2'
  	  WHEN 'L3' THEN 'Level 3'
  	  WHEN 'U1' THEN 'Unclassified'
	  WHEN 'NA' THEN 'N/A'
  END AS ict,
  DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date,
  DATE_FORMAT(induction.`induction_date`, '%M %Y') AS induction_month,
  CASE induction.`induction_status`
	  WHEN 'TBA' THEN 'To Be Arranged'
	  WHEN 'S' THEN 'Scheduled'
	  WHEN 'C' THEN 'Completed'
	  WHEN 'H' THEN 'Holding Induction'
	  WHEN 'L' THEN 'Leaver'
	  WHEN 'W' THEN 'Withdrawn'
	  ELSE ''
  END AS induction_status,
  DATE_FORMAT(induction.`planned_end_date`, '%d/%m/%Y') AS planned_end_date
FROM
	inductees
	  LEFT JOIN induction
	    ON inductees.id = induction.inductee_id
	  LEFT JOIN induction_programme
	    ON induction_programme.inductee_id = inductees.id
	  LEFT JOIN organisations AS employers
	    ON employers.id = inductees.employer_id
;

SQL;
		}
		if($view_name == 'holding_inductions')
		{
			$induction_status = 'H';
			$induction_status_options = array(
				0 => array('H', 'Holding Induction', null, 'WHERE induction_status = "H"')
			);
			$induction_status_allow_null = false;
			$sql = <<<SQL
SELECT DISTINCT
 	'Holding Induction' AS induction_status_desc,
	DATE_FORMAT(induction.`induction_date`, '%M %Y') AS induction_month,
	employers.legal_name AS company,
	(SELECT description FROM lookup_delivery_locations WHERE id = inductees.location_area) AS delivery_location,
	(SELECT courses.title FROM courses WHERE courses.id = induction_programme.programme_id) AS programme,
	inductees.surname,
    inductees.firstnames,
	induction.lead_gen,
    (DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d')) AS age_at_induction,
    CASE TRUE
    	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
    	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
    	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) > 24 THEN '24+'
    	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) < 16 THEN 'Under 16'
    END AS age_group,
	DATE_FORMAT(inductees.employment_start_date, '%d/%m/%Y') AS employment_start_date,
	extractvalue(grey_section_comments, '/Notes/Note[last()]/Note') AS last_holding_induction_comment,
	induction.resourcer AS recruiter,
	induction.brm AS bdm,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = induction.induction_owner) AS induction_owner,
  CASE induction.`webcam`
	  WHEN 'NR' THEN 'Not Required'
	  WHEN 'S' THEN 'Sent'
	  WHEN '' THEN ''
  END AS `webcam`,
  DATE_FORMAT(induction_arranged, '%d/%m/%Y') AS induction_arranged
FROM
	inductees
	  LEFT JOIN induction
	    ON inductees.id = induction.inductee_id
	  LEFT JOIN induction_programme
	    ON induction_programme.inductee_id = inductees.id
	  LEFT JOIN organisations AS employers
	    ON employers.id = inductees.employer_id

;

SQL;
		}
		if($view_name == 'to_be_arranged')
		{
			$induction_status = 'TBA';
			$induction_status_options = array(
				0 => array('TBA', 'To Be Arranged', null, 'WHERE induction_status = "TBA"')
			);
			$induction_status_allow_null = false;
			$sql = <<<SQL
SELECT DISTINCT 
	DATE_FORMAT(induction.`induction_date`, '%M %Y') AS induction_month,
	(SELECT description FROM lookup_delivery_locations WHERE id = inductees.location_area) AS delivery_location,
	induction.brm AS bdm,
	induction.resourcer AS recruiter,
	induction.lead_gen,
	employers.legal_name AS company,
	(SELECT courses.title FROM courses WHERE courses.id = induction_programme.programme_id) AS programme,
	inductees.surname,
    inductees.firstnames,
    CASE TRUE
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) > 24 THEN '24+'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS age_group,
  DATE_FORMAT(dob, '%d/%m/%Y') AS dob,
    DATE_FORMAT(inductees.employment_start_date, '%d/%m/%Y') AS employment_start_date,
	extractvalue(induction_notes, '/Notes/Note[last()]/Note') AS last_induction_comment,
     CASE induction.`sla_received`
	  WHEN 'YN' THEN 'Yes New'
	  WHEN 'YO' THEN 'Yes Old'
	  WHEN 'YO' THEN 'Yes Old'
	  WHEN 'N' THEN 'No'
	  WHEN 'R' THEN 'Rejected'
	  ELSE ''
  END AS sla_received
FROM
	inductees
	  LEFT JOIN induction
	    ON inductees.id = induction.inductee_id
	  LEFT JOIN induction_programme
	    ON induction_programme.inductee_id = inductees.id
	  LEFT JOIN organisations AS employers
	    ON employers.id = inductees.employer_id

;

SQL;
		}
		if($view_name == 'completed_vs_live')
		{
			$induction_status = 'C';
			$induction_status_options = array(
				0 => array('C', 'Completed', null, 'WHERE induction_status = "C"')
			);
			$induction_status_allow_null = false;
			$sql = <<<SQL
SELECT DISTINCT 
	DATE_FORMAT(induction.`induction_date`, '%M %Y') AS induction_month,
	inductees.surname,
    inductees.firstnames,
    employers.legal_name AS company,
    IF(inductees.sunesis_username IS NULL, 'No', 'Yes') AS sunesis,
    DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date
FROM
inductees
  LEFT JOIN induction
    ON inductees.id = induction.inductee_id
  LEFT JOIN organisations AS employers
    ON employers.id = inductees.employer_id
;

SQL;
		}
		$view = new VoltView($view_name, $sql);

		$f = new VoltTextboxViewFilter('filter_firstnames', "WHERE inductees.firstnames LIKE '%s%%'", null);
		$f->setDescriptionFormat("First Name: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_surname', "WHERE inductees.surname LIKE '%s%%'", null);
		$f->setDescriptionFormat("Surname: %s");
		$view->addFilter($f);

		$format = "WHERE induction.induction_date >= '%s'";
		$f = new VoltDateViewFilter('filter_from_induction_date', $format, '');
		$f->setDescriptionFormat("From induction date: %s");
		$view->addFilter($f);

		$format = "WHERE induction.induction_date <= '%s'";
		$f = new VoltDateViewFilter('filter_to_induction_date', $format, '');
		$f->setDescriptionFormat("To induction date: %s");
		$view->addFilter($f);

		$f = new VoltDropDownViewFilter('filter_induction_status', $induction_status_options, $induction_status, $induction_status_allow_null);
		$f->setDescriptionFormat("Induction Status: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_brm', "WHERE induction.brm LIKE '%s%%'", null);
		$f->setDescriptionFormat("BDM: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_resourcer', "WHERE induction.resourcer LIKE '%s%%'", null);
		$f->setDescriptionFormat("Recruiter: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_lead_gen', "WHERE induction.lead_gen LIKE '%s%%'", null);
		$f->setDescriptionFormat("Lead Generator: %s");
		$view->addFilter($f);

		$options = array(
			0 => array('0', 'New Apprentice', null, 'WHERE inductee_type = "NA"')
			,1 => array('1', 'WFD', null, 'WHERE inductee_type = "WFD"')
			,2 => array('2', 'Progression', null, 'WHERE inductee_type = "P"')
			,3 => array('3', 'New Apprentice Client Sourced', null, 'WHERE inductee_type = "SSU"')
			,4 => array('4', '3AAA Transfer', null, 'WHERE inductee_type = "3AAA"')
            		,5 => array('5', 'Learner Transfer', null, 'WHERE inductee_type = "LT"')
		);
		$f = new VoltDropDownViewFilter('filter_learner_type', $options, null, true);
		$f->setDescriptionFormat("Learner Type: %s");
		$view->addFilter($f);

		$options = array(
			0 => array('0', '16-18', null, 'HAVING age_group = "16-18"')
		,1 => array('1', '19-24', null, 'HAVING age_group = "19-24"')
		,2 => array('2', '24+', null, 'HAVING age_group = "24+"')
		,3 => array('3', 'Under 16', null, 'HAVING age_group = "Under 16"')
		);
		$f = new VoltDropDownViewFilter('filter_age_group', $options, null, true);
		$f->setDescriptionFormat("Age Group: %s");
		$view->addFilter($f);

		if($view_name == 'sales_induction_data')
		{
			$options = array(
				0 => array('0', 'Started', null, 'WHERE induction_programme.`eligibility_test_status` = "S"')
			,1 => array('1', 'Completed', null, 'WHERE induction_programme.`eligibility_test_status` = "C"')
			,2 => array('2', 'Outstanding', null, 'WHERE induction_programme.`eligibility_test_status` = "O"')
			,3 => array('3', 'Not Applicable', null, 'WHERE induction_programme.`eligibility_test_status` = "NA"')
			);
			$f = new VoltDropDownViewFilter('filter_eligibility_test_status', $options, null, true);
			$f->setDescriptionFormat("Eligibility Test Status: %s");
			$view->addFilter($f);
		}

		$options = "SELECT DISTINCT organisations.id, legal_name, null, CONCAT('WHERE inductees.employer_id=',organisations.id) FROM organisations INNER JOIN inductees ON organisations.id = inductees.employer_id WHERE organisation_type LIKE '%2%' ORDER BY legal_name";
		$f = new VoltDropDownViewFilter('filter_employer', $options, null, true);
		$f->setDescriptionFormat("Employer: %s");
		$view->addFilter($f);

		/*$options = "SELECT DISTINCT courses.id, CONCAT(courses.title, ' (',frameworks.`title`, ')'), null, CONCAT('WHERE induction_programme.programme_id=',courses.id) FROM courses INNER JOIN frameworks ON courses.framework_id = frameworks.id INNER JOIN induction_programme ON courses.id = induction_programme.programme_id ORDER BY courses.title";
		$f = new VoltDropDownViewFilter('filter_programme', $options, null, true);
		$f->setDescriptionFormat("Programme: %s");*/

		$options = array(
			0=>array(20,20,null,null),
			1=>array(50,50,null,null),
			2=>array(100,100,null,null),
			3=>array(200,200,null,null),
			4=>array(300,300,null,null),
			5=>array(400,400,null,null),
			6=>array(500,500,null,null),
			7=>array(0, 'No limit', null, null));
		$f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		return $view;
	}

	private function renderView(PDO $link, VoltView $view)
	{
		$columns = '';
		$rows = array();
		$result = DAO::getResultset($link, $view->getSQLStatement()->__toString(), DAO::FETCH_ASSOC);

		foreach($result AS $rs)
			$rows[] = $rs;
		unset($result);

		echo $view->getViewNavigatorExtra('', $view->getViewName());
		echo '<div align="center" ><table id="tblLearners" class="table row-border" border="0" cellspacing="0" cellpadding="6">';
		echo '<thead><tr>';
		foreach($rows AS $row)
		{
			$columns = array_keys($row);
			foreach($columns AS $column)
			{
				if($column == 'last_induction_comment' || $column == 'last_holding_induction_comment')
					echo '<th><u>' . ucwords(str_replace("_","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",str_replace("_and_"," & ",$column))) . '</u></th>';
				else
					echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . '</th>';
			}
			break;
		}
		echo '</tr></thead>';
		echo '<tbody>';
		foreach($rows AS $row)
		{
			echo '<tr>';
			foreach($columns AS $column)
			{
				if($column == 'last_induction_comment' || $column == 'last_holding_induction_comment')
				{
					echo '<td><small><small>' . $row[$column] . '</small></small></td>';
				}
				else
					echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
			}
			echo '</tr>';
		}
		echo '</table></div>';
		echo $view->getViewNavigatorExtra('', $view->getViewName());
	}

	private function exportToCSV(PDO $link, VoltView $view)
	{
		$rows = array();
		$columns = '';

		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$result = DAO::getResultset($link, $statement, DAO::FETCH_ASSOC);

		foreach($result AS $rs)
			$rows[] = $rs;
		unset($result);

		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename='.$view->getViewName().'.csv');
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
		{
			header('Pragma: public');
			header('Cache-Control: max-age=0');
		}
		$line = '';
		foreach($rows AS $row)
		{
			$columns = array_keys($row);
			foreach($columns AS $column)
				$line .= ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . ',';
			break;
		}
		echo $line . "\r\n";
		foreach($rows AS $row)
		{
			$line = '';
			foreach($columns AS $column)
			{
				$line .= ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
			}
			echo $line . "\r\n";
		}
		exit;
	}

	private function csvSafe($value)
	{
		$value = str_replace(',', '; ', $value);
		$value = str_replace(array("\n", "\r"), '', $value);
		$value = str_replace("\t", '', $value);
		$value = '"' . str_replace('"', '""', $value) . '"';
		return $value;
	}
}