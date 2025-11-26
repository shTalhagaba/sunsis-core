<?php
class induction_home implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
        $selected_tab = isset($_REQUEST['selected_tab'])?$_REQUEST['selected_tab']:'';
        if($selected_tab == '')
            $selected_tab = isset($_SESSION['ViewInductionSelectedTab'])?$_SESSION['ViewInductionSelectedTab']:'tab1';

        $selected_tab = isset($_REQUEST['selected_tab'])?$_REQUEST['selected_tab']:(isset($_SESSION['ViewInductionSelectedTab'])?$_SESSION['ViewInductionSelectedTab']:'tab1');

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=induction_home" . "&selected_tab=" . $selected_tab, "Induction Home");



        if($subaction == 'loadQuickForm')
        {
            $this->loadQuickForm($link);
            exit;
        }

        $tab1 = "";
        $tab2 = "";
        $tab3 = "";
        $tab4 = "";
        $tab5 = "";
        $tab6 = "";

        if(isset($$selected_tab))
            $$selected_tab = " active ";
        else
            $tab1 = " active ";



        include_once('tpl_induction_home.php');
    }

    private function buildView($viewNameSuffix, $start_date = '', $end_date = '', $projected_start = '')
    {
        $sql = new SQLStatement("SELECT DISTINCT
  CASE induction.`induction_status`
	  WHEN 'TBA' THEN 'To Be Arranged'
	  WHEN 'S' THEN 'Scheduled'
	  WHEN 'C' THEN 'Completed'
	  WHEN 'H' THEN 'Holding Induction'
	  WHEN 'L' THEN 'Leaver'
	  WHEN 'W' THEN 'Withdrawn'
	  WHEN 'LT' THEN 'Learner Transfer'
	  ELSE ''
  END AS induction_status_desc,
  DATE_FORMAT(induction.`induction_date`, '%M %Y') AS induction_month,
  DATE_FORMAT(induction.`created`, '%d/%m/%Y') AS induction_creation,
  DATE_FORMAT(induction_arranged, '%d/%m/%Y') AS induction_arranged,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = induction.induction_owner) AS induction_owner,
  #employers.legal_name AS company,
  (SELECT legal_name FROM organisations WHERE organisations.id = inductees.employer_id) AS company,
  (SELECT courses.title FROM courses WHERE courses.id = induction_programme.programme_id) AS programme,
  inductees.surname,
  inductees.firstnames,
  ((DATE_FORMAT(CURDATE(),'%Y') - DATE_FORMAT(dob,'%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT(dob,'00-%m-%d'))) AS age,
  ((DATE_FORMAT(induction_date,'%Y') - DATE_FORMAT(dob,'%Y')) - (DATE_FORMAT(induction_date,'00-%m-%d') < DATE_FORMAT(dob,'00-%m-%d'))) AS age_at_induction,
  inductees.`work_email`,
  inductees.`home_email`,
  inductees.`home_telephone`,
  inductees.`ni` AS NINO,
  inductees.paid_hours,
  inductees.salary,
  (SELECT contact_name FROM organisation_contact WHERE contact_id IN (inductees.emp_crm_contacts)) AS employer_contacts,
  DATE_FORMAT(inductees.employment_start_date, '%d/%m/%Y') AS employment_start_date,
  DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date,
  DATE_FORMAT(induction.`planned_end_date`, '%d/%m/%Y') AS planned_end_date,
  
  #(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = induction.induction_assessor) AS induction_assessor,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = induction.assigned_assessor) AS coach,
  #(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = induction.assigned_coord) AS coordinator,
  #extractvalue(induction_notes, '/Notes/Note[last()]/Note') AS comments_on_induction,
  extractvalue(grey_section_comments, '/Notes/Note[last()]/Note') AS comments_on_holding_induction,
  induction.withdrawn_reason,
  CASE induction.`commit_statement`
      WHEN 'NS' THEN 'Not Sent'
      WHEN 'S' THEN 'Sent'
      WHEN 'FC' THEN 'Fully Completed'
      WHEN 'RP' THEN 'RPL Commitment Statement'
      WHEN 'ST' THEN 'Standard Commitment Statement'
  END AS training_plan,
  CASE induction.enrolment_form
  	WHEN 'NS' THEN 'Not Sent'
  	WHEN 'S' THEN 'Sent'
  	WHEN 'FC' THEN 'Fully Completed'
  END AS eligibility_form,
  CASE inductees.`learner_id`
	  WHEN 'RPI' THEN 'Received prior induction'
	  WHEN 'RAI' THEN 'Received at induction'
	  WHEN 'RFI' THEN 'Received following induction'
	  WHEN 'O' THEN 'Outstanding'
	  WHEN 'SP' THEN 'Sign posted'
	  WHEN 'NR' THEN 'Not Required'
	  WHEN 'P' THEN 'Passport'
	  WHEN 'DL' THEN 'Driving License'
	  WHEN 'PDL' THEN 'Provisional Driving License'
	  WHEN 'PAC' THEN 'Proof of Age Card'
	  WHEN 'BC' THEN 'Birth Certificate'
	  WHEN 'R' THEN 'Residency'
	  WHEN 'PTC' THEN 'Passed to Coach'
  END AS `id_checked`,
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
  /*CASE induction.math_cert
    WHEN '1' THEN 'Received'
    WHEN '2' THEN 'Not Received'
    WHEN '3' THEN 'Before FS Process'
    WHEN '4' THEN 'Quals confirmed on PLR'
    WHEN '5' THEN 'Certificates Requested'
    WHEN '6' THEN 'Certificates Re-print requested'
    WHEN '7' THEN 'No Qual - SMT Approved'
    ELSE ''
  END AS math_cert,
  CASE induction.eng_cert
    WHEN '1' THEN 'Received'
    WHEN '2' THEN 'Not Received'
    WHEN '3' THEN 'Before FS Process'
    WHEN '4' THEN 'Quals confirmed on PLR'
    WHEN '5' THEN 'Certificates Requested'
    WHEN '6' THEN 'Certificates Re-print requested'
    WHEN '7' THEN 'No Qual - SMT Approved'
    ELSE ''
  END AS eng_cert,*/
  CASE induction.wfd_assessment
  	  WHEN 'N' THEN 'No - SMT Approved'
  	  WHEN 'Y' THEN 'Yes'
  	  WHEN 'YP' THEN 'Yes - Pending Evidence'
#  	  WHEN 'E' THEN 'Exempt'
  END AS eng_fs_exemption_status,
  CASE induction.maths_gcse_elig_met
  	  WHEN 'N' THEN 'No - SMT Approved'
  	  WHEN 'Y' THEN 'Yes'
	  WHEN 'YP' THEN 'Yes - Pending Evidence'
#  	  WHEN 'E' THEN 'Exempt'
  END AS math_fs_exemption_status,
  
  CASE induction.`levy_payer`
	  WHEN 'Y' THEN 'Yes'
	  WHEN 'N' THEN 'No'
  END AS `levy_payer`,
  CASE induction.`levy_app_completed`
	  WHEN 'Y' THEN 'Yes'
	  WHEN 'N' THEN 'No'
	  WHEN 'NA' THEN 'N/A'
	  WHEN 'AN' THEN 'Application not made'
	  WHEN 'AM' THEN 'Application made'
	  WHEN 'AP' THEN 'Admin Processed'
	  WHEN 'LT' THEN 'Levy Transfer - application made'
	  WHEN 'LTN' THEN 'Levy Transfer - application not made'
	  WHEN 'RM' THEN 'Raised to ARM'
  END AS `levy_app_completed`,
  CASE induction.das_account
  	WHEN '' THEN ''
  	WHEN 'NR' THEN 'Not Required'
  	WHEN 'NC' THEN 'Not Created'
  	WHEN 'C' THEN 'Created'
  	WHEN 'PR' THEN 'Permissions Required'
  	WHEN 'RM' THEN 'Reservations Made'
	WHEN 'AM' THEN 'Application Made'
  	WHEN 'AP' THEN 'Admin Processed'
	WHEN 'RA' THEN 'Raised to ARM'
	WHEN 'LT' THEN 'Levy Transfer - application made'
	WHEN 'LTN' THEN 'Levy Transfer - application not made'
  END AS das_account,
  induction.das_account_contact AS digital_account_contact,
  induction.das_account_telephone AS digital_account_telephone,
  induction.das_account_email AS digital_account_email,
  extractvalue(induction.das_comments, '/Notes/Note[last()]/Note') AS das_comments,
  CASE inductees.`employer_type`
    WHEN 'AM' THEN 'Account Management'
    WHEN 'SG' THEN 'EEM Self Generated'
    WHEN 'EE' THEN 'EEM Self'
    WHEN 'L' THEN 'Levy'
    WHEN 'LS' THEN 'Levy Team Self Gen'
    WHEN 'LT' THEN 'Enterprise Acquisition'
    WHEN 'LM' THEN 'Enterprise Accounts'
    WHEN 'NB' THEN 'New Business'
    WHEN 'NT' THEN 'Customer Acquisition'
    WHEN 'NG' THEN 'Non Levy Self Gen'
    WHEN 'NM' THEN 'Account Management'
    WHEN 'SC' THEN 'Senior Consultant - Levy'
    WHEN 'PG' THEN 'Specialist Self Gen'
    WHEN 'CG' THEN 'CEM Self Gen'
    WHEN 'IN' THEN 'Internal'
  END AS `employer_type`,
  CASE inductee_type
  	  WHEN 'NA' THEN 'New Apprentice'
  	  WHEN 'WFD' THEN 'WFD'
  	  WHEN 'P' THEN 'Progression'
  	  WHEN 'SSU' THEN 'New Apprentice Client Sourced'
  	  WHEN '3AAA' THEN '3AAA Transfer'
	  WHEN 'DXC' THEN 'DXC Transfer'
	  WHEN 'HOET' THEN 'HOET Transfer'
	  WHEN 'LT' THEN 'Learner Transfer'
  END AS learner_type,
  induction_programme.skilsure_username AS smart_assessor_username,
  induction_programme.skilsure_password AS smart_assessor_password,
  induction_programme.mentor_username,
  induction_programme.mentor_password,
  induction.brm AS cem,
  induction.lead_gen AS business_consultant,
  induction.resourcer AS recruiter,
  induction.emp_recruiter AS employer_recruiter,
  extractvalue(induction.levy_comments, '/Notes/Note[last()]/Note') AS levy_comments,
  DATE_FORMAT(dob, '%d/%m/%Y') AS dob,
  induction.arm AS a_r_m,
  /*CASE TRUE
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) > 24 THEN '24+'
  	WHEN ((DATE_FORMAT(induction.`induction_date`,'%Y') - DATE_FORMAT(inductees.dob,'%Y')) - (DATE_FORMAT(induction.`induction_date`,'00-%m-%d') < DATE_FORMAT(inductees.dob,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS age_group,*/
  induction.`induction_status`,
  inductees.id AS inductee_learner_id,
  induction.comp_issue AS red_flag_learner,
  induction.comp_issue_notes,
  CASE induction.`holding_reason`
	WHEN '1' THEN 'Awaiting confirmed start date'
	WHEN '2' THEN 'DBS checks'
	WHEN '3' THEN 'DAS'
	WHEN '4' THEN 'College/Uni unenrolment'
	WHEN '5' THEN 'Employer commitment (On-Boarding struggle to contact)'
	WHEN '6' THEN 'Apprentice performance'
	WHEN '7' THEN 'Business environment'
	WHEN '8' THEN 'Apprentice commitment'
	WHEN '9' THEN 'Other'
    WHEN '10' THEN 'Employer Paperwork'
    WHEN '11' THEN 'Learner Paperwork'
    WHEN '12' THEN 'DBS / References'
    WHEN '13' THEN 'DAS Account'
    WHEN '14' THEN 'Sickness'
    WHEN '15' THEN 'Start Date Unconfirmed'
    WHEN '16' THEN 'Awaiting Unenrolment'
    WHEN '17' THEN 'Employer OOO'
    WHEN '18' THEN 'Paperwork - Both'
	ELSE ''
  END AS holding_induction_reason,
  extractvalue(induction.contact_comments, '/Notes/Note[last()]/Note') AS contact_comments,
  DATE_FORMAT(induction.date_added_to_hi, '%d/%m/%Y') AS added_to_holding,DATE_FORMAT(induction.date_removed_from_hi, '%d/%m/%Y') as holding_induction_date_remove,
  CASE induction.induction_moved
    WHEN '1' THEN 'Moved within month'
    WHEN '2' THEN 'Moved to another month'
    ELSE ''
  END AS induction_moved,
  DATE_FORMAT(induction.induction_moved_date, '%d/%m/%Y') AS induction_moved_date,
  CASE induction.induction_moved_reason
    WHEN '1' THEN 'Compliance Paperwork - Employer'
    WHEN '2' THEN 'Compliance Paperwork - Learner'
    WHEN '3' THEN 'Digital Account Not Created'
    WHEN '4' THEN 'Employer Concerns'
    WHEN '5' THEN 'Sickness'
    WHEN '6' THEN 'Workload'
    WHEN '7' THEN 'Holding Inductions'
    WHEN '8' THEN 'Other'
    WHEN '9' THEN 'Employer Paperwork'
    WHEN '10' THEN 'Learner Paperwork'
    WHEN '11' THEN 'Assessments Incomplete'
    WHEN '12' THEN 'DAS Account Not Created'
    WHEN '13' THEN 'Start Date Changed'
    ELSE ''	
  END AS induction_moved_reason,
  DATE_FORMAT(induction.projected_induction_date, '%d/%m/%Y') AS projected_induction_date,
  /*CASE induction_programme.skills_scan
    WHEN 'NC' THEN 'Not Completed'
    WHEN 'RS' THEN 'Requires Signature'
    WHEN 'U' THEN 'Uploaded'
    ELSE ''
  END AS skills_scan,*/
  /*CASE induction_programme.ip_status
    WHEN 'FC' THEN 'Fully Completed'
    WHEN 'E' THEN 'Escalated'
    WHEN 'AFR' THEN 'Awaiting Funding Reduction'
    WHEN 'AS' THEN 'Awaiting Signature'
    ELSE ''
  END AS ip_status,*/
  #DATE_FORMAT(induction_programme.call_arranged_for, '%d/%m/%Y') AS call_arranged_for,
  #extractvalue(induction_programme.coordinator_notes_program, '/Notes/Note[last()]/Comment') AS coordinator_notes_program,
  CASE induction_programme.funding_reduction
    WHEN '1' THEN 'Prior Quals'
    WHEN '2' THEN 'Prior Experience'
    WHEN '3' THEN 'Prior Quals & Experience'
    WHEN '4' THEN 'Employer agreed reduction'
    WHEN '5' THEN 'Staff reduction'
    WHEN '6' THEN 'Previous apprenticeship completed'
    WHEN '7' THEN 'Other'
    WHEN '8' THEN 'Admin/Processing Error'
    ELSE ''
  END AS funding_reduction,
  induction_programme.reduction_price,
  induction_programme.skills_scan_grade,
  #induction.eng_gcse_grade,
  #induction.maths_gcse_grade,
  induction.id AS induction_id,
  CASE inductees.ldd
    WHEN '1' THEN 'Emotional/Behaviour difficulties'
    WHEN '2' THEN 'Multiple difficulties'
    WHEN '4' THEN 'Vision impairment'
    WHEN '5' THEN 'Hearing impairment'
    WHEN '6' THEN 'Disability affecting mobility'
    WHEN '9' THEN 'Mental health difficulty'
    WHEN 'MLD' THEN 'Moderate Learning Difficulty'
    WHEN 'SLD' THEN 'Severe Learning Difficulty'
    WHEN 'DXA' THEN 'Dyslexia'
    WHEN 'DLA' THEN 'Dyscalculia'
    WHEN 'ASD' THEN 'Autism Spectrum Disorder'
    WHEN '15' THEN 'Asperger\'s syndrome'
    WHEN '16' THEN 'Temporary disability after illness'
    WHEN '17' THEN 'Speech, language, and communication needs'
    WHEN '93' THEN 'Other physical disability'
    WHEN '95' THEN 'Other medical condition'
    WHEN '97' THEN 'Other disability'
    WHEN '98' THEN 'Prefer not to say'
    WHEN 'OSLD' THEN 'Other Specific Learning Difficulty'
    WHEN 'OTH' THEN 'Other (Additional Data Required)'
    WHEN 'PNS' THEN 'Prefer Not To Say'
    WHEN 'NP' THEN 'Not provided'
    WHEN 'N' THEN 'None'
    ELSE ''
  END AS ldd,
  DATE_FORMAT(induction.cohort_date, '%d/%m/%Y') AS cohort_date,
  (SELECT IF(organisations.epp = 1, 'Yes', '') FROM organisations WHERE organisations.id = inductees.employer_id) AS expert_provider_pilot,
  DATE_FORMAT(induction.das_account_created, '%d/%m/%Y') AS das_account_created,
  CASE induction_programme.data_pathway
    WHEN 1 THEN 'Data Essentials L3 - Marketing'
    WHEN 2 THEN 'Data Essentials L3 - Leadership'
    WHEN 3 THEN 'Data Essentials L3 - Operations'
    WHEN 4 THEN 'Other'
    ELSE ''
  END AS data_pathway,
  CASE induction_programme.it_pathway
    WHEN 'AZ900' THEN 'AZ900 - Azure Fundamentals'
    WHEN 'MS900' THEN 'MS900 - 365 Fundamentals'
    WHEN 'SC900' THEN 'SC900 - Security & Compliance Fundamentals'
    ELSE ''
  END AS it_pathway

FROM
  inductees
  LEFT JOIN induction
    ON inductees.id = induction.inductee_id
  LEFT JOIN induction_programme
    ON induction_programme.inductee_id = inductees.id
#  LEFT JOIN organisations AS employers
#    ON employers.id = inductees.employer_id
#  LEFT JOIN locations AS emp_locations
#    ON emp_locations.organisations_id = employers.id
;
	");

        //$sql->setClause('WHERE (inductees.sunesis_username IS NULL OR inductees.sunesis_username = "N")');
        if($viewNameSuffix == 'ProjectStarts')
            $sql->setClause('WHERE induction.induction_date > "' . date('Y-m-t') . '"');
        if($viewNameSuffix == 'ProjectStarts')
            $sql->setClause('WHERE induction.induction_status !=  "C"');
        if($viewNameSuffix != 'All')
            $sql->setClause('WHERE induction.induction_status !=  "W"');

        $viewFullName = 'view_ViewInduction_'.$viewNameSuffix;
        $view = new VoltView($viewFullName, $sql->__toString());

        $f = new VoltTextboxViewFilter($viewFullName.'filter_firstnames', "WHERE inductees.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("First Name: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter($viewFullName.'filter_surname', "WHERE inductees.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
        $view->addFilter($f);

        $format = "WHERE induction.induction_date >= '%s'";
        $f = new VoltDateViewFilter($viewFullName.'filter_from_induction_date', $format, $start_date);
        $f->setDescriptionFormat("From induction date: %s");
        $view->addFilter($f);

        $format = "WHERE induction.induction_date <= '%s'";
        $f = new VoltDateViewFilter($viewFullName.'filter_to_induction_date', $format, $end_date);
        $f->setDescriptionFormat("To induction date: %s");
        $view->addFilter($f);

	$format = "WHERE induction.projected_induction_date >= '%s'";
        $f = new VoltDateViewFilter($viewFullName.'filter_from_projected_induction_date', $format, '');
        $f->setDescriptionFormat("From induction planned date: %s");
        $view->addFilter($f);

        $format = "WHERE induction.projected_induction_date <= '%s'";
        $f = new VoltDateViewFilter($viewFullName.'filter_to_projected_induction_date', $format, '');
        $f->setDescriptionFormat("To induction planned date: %s");
        $view->addFilter($f);

        $options = array(
            0 => array('0', 'To Be Arranged', null, 'WHERE induction_status = "TBA"')
        ,1 => array('1', 'Scheduled', null, 'WHERE induction_status = "S"')
        ,2 => array('2', 'Completed', null, 'WHERE induction_status = "C"')
        ,3 => array('3', 'Holding Induction', null, 'WHERE induction_status = "H"')
        ,4 => array('4', 'Leaver', null, 'WHERE induction_status = "L"')
        ,5 => array('5', 'Withdrawn', null, 'WHERE induction_status = "W"')
	,6 => array('6', 'Learner Transfer', null, 'WHERE induction_status = "LT"')
        ,7 => array('SHOW_ALL', 'Show all', null, 'WHERE induction_status IN ("TBA", "S", "C", "H", "L", "W")')
        );
        $f = new VoltCheckboxViewFilter($viewFullName.'filter_induction_status', $options, array());
        $f->setDescriptionFormat("Induction Status: %s");
        $view->addFilter($f);

        // $options = array(
        //     0 => array('0', 'Show All', null, null)
        // ,1 => array('1', 'No', null, 'WHERE (induction.reinstated IS NULL OR induction.reinstated = "N" )')
        // ,2 => array('2', 'Yes', null, 'WHERE induction.reinstated = "Y"')
        // );
        // $f = new VoltDropDownViewFilter($viewFullName.'filter_reinstated', $options, 0, false);
        // $f->setDescriptionFormat("Re-instated: %s");
        // $view->addFilter($f);

        $options = array(
            0 => array('0', 'No', null, 'WHERE (induction.levy_payer = "N" )')
        ,1 => array('1', 'Yes', null, 'WHERE induction.levy_payer = "Y"')
        );
        $f = new VoltDropDownViewFilter($viewFullName.'filter_levy_payer', $options, null, true);
        $f->setDescriptionFormat("Levy Payer: %s");
        $view->addFilter($f);

        $options = array(
            0 => array('0', 'No', null, 'WHERE (inductees.sunesis_username IS NULL )')
        ,1 => array('1', 'Yes', null, 'WHERE inductees.sunesis_username IS NOT NULL')
        );
        $f = new VoltDropDownViewFilter($viewFullName.'filter_sunesis_account', $options, null, true);
        $f->setDescriptionFormat("Sunesis Account: %s");
        $view->addFilter($f);

        // $options = array(
        //     0 => array('0', 'N/A', null, 'WHERE induction.`moredle_account` = "NA"')
        // ,1 => array('1', 'Yes', null, 'WHERE induction.`moredle_account` = "Y"')
        // ,2 => array('2', 'No', null, 'WHERE induction.`moredle_account` = "No"')
        // );
        // $f = new VoltDropDownViewFilter($viewFullName.'filter_moredle', $options, null, true);
        // $f->setDescriptionFormat("Moredle: %s");
        // $view->addFilter($f);

        $f = new VoltTextboxViewFilter($viewFullName.'filter_brm', "WHERE induction.brm LIKE '%s%%'", null);
        $f->setDescriptionFormat("CEM: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter($viewFullName.'filter_resourcer', "WHERE induction.resourcer LIKE '%s%%'", null);
        $f->setDescriptionFormat("Recruiter: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter($viewFullName.'filter_lead_gen', "WHERE induction.lead_gen LIKE '%s%%'", null);
        $f->setDescriptionFormat("Lead Generator: %s");
        $view->addFilter($f);

        $options = array(
            0 => array('0', 'New Apprentice', null, 'WHERE inductee_type = "NA"')
        ,1 => array('1', 'WFD', null, 'WHERE inductee_type = "WFD"')
        ,2 => array('2', 'Progression', null, 'WHERE inductee_type = "P"')
        ,3 => array('3', 'New Apprentice Client Sourced', null, 'WHERE inductee_type = "SSU"')
        ,4 => array('4', 'Learner Transfer', null, 'WHERE inductee_type = "LT"')
        ,14 => array('SHOW_ALL', 'Show all', null, 'WHERE inductee_type IS NOT NULL')
        );
        $f = new VoltCheckboxViewFilter($viewFullName.'filter_learner_type', $options, array());
        $f->setDescriptionFormat("Learner Type: %s");
        $view->addFilter($f);

        // $options = array(
        //     0 => array('0', 'Checking', null, 'WHERE induction.`miap` = "C"')
        // ,1 => array('1', 'Ineligible', null, 'WHERE induction.`miap` = "I"')
        // ,2 => array('2', 'No record', null, 'WHERE induction.`miap` = "N"')
        // ,3 => array('3', 'Yes', null, 'WHERE induction.`miap` = "Y"')
        // );
        // $f = new VoltDropDownViewFilter($viewFullName.'filter_miap', $options, null, true);
        // $f->setDescriptionFormat("MIAP: %s");
        // $view->addFilter($f);

        // $options = array(
        //     0 => array('0', 'No', null, 'WHERE induction.`headset_issued` = "N"')
        // ,1 => array('1', 'Sent', null, 'WHERE induction.`headset_issued` = "S"')
        // ,2 => array('2', 'Not Required', null, 'WHERE induction.`headset_issued` = "NR"')
        // ,3 => array('3', 'Signed For', null, 'WHERE induction.`headset_issued` = "SF"')
        // );
        // $f = new VoltDropDownViewFilter($viewFullName.'filter_headset', $options, null, true);
        // $f->setDescriptionFormat("Headset: %s");
        // $view->addFilter($f);

        $options = array(
            0 => array('0', 'Not Sent', null, 'WHERE induction.`commit_statement` = "NS"')
        ,1 => array('1', 'Sent', null, 'WHERE induction.`commit_statement` = "S"')
        ,2 => array('2', 'Fully Completed', null, 'WHERE induction.`commit_statement` = "FC"')
        );
        $f = new VoltDropDownViewFilter($viewFullName.'filter_comt_stmt', $options, null, true);
        $f->setDescriptionFormat("Commitment Statement: %s");
        $view->addFilter($f);
        /*
                $options = array(
                    0 => array('0', 'No', null, 'WHERE induction.`wfd_assessment` = "N"')
                    ,1 => array('1', 'Yes', null, 'WHERE induction.`wfd_assessment` = "Y"')
                    ,2 => array('2', 'Exempt', null, 'WHERE induction.`wfd_assessment` = "E"')
                );
                $f = new VoltDropDownViewFilter($viewFullName.'filter_wfd', $options, null, true);
                $f->setDescriptionFormat("WFD Assessment: %s");
                $view->addFilter($f);
        */
        // $options = array(
        //     0 => array('0', 'Started', null, 'WHERE induction_programme.`eligibility_test_status` = "S"')
        // ,1 => array('1', 'Completed', null, 'WHERE induction_programme.`eligibility_test_status` = "C"')
        // ,2 => array('2', 'Outstanding', null, 'WHERE induction_programme.`eligibility_test_status` = "O"')
        // ,3 => array('3', 'Not Applicable', null, 'WHERE induction_programme.`eligibility_test_status` = "NA"')
        // );
        // $f = new VoltDropDownViewFilter($viewFullName.'filter_eligibility_test_status', $options, null, true);
        // $f->setDescriptionFormat("Eligibility Test Status: %s");
        // $view->addFilter($f);

        // $options = array(
        //     0 => array('0', 'Entry Level 1', null, 'WHERE induction.`iag_numeracy` = "E1"')
        // ,1 => array('1', 'Entry Level 2', null, 'WHERE induction.`iag_numeracy` = "E2"')
        // ,2 => array('2', 'Entry Level 3', null, 'WHERE induction.`iag_numeracy` = "E3"')
        // ,3 => array('3', 'Level 1', null, 'WHERE induction.`iag_numeracy` = "L1"')
        // ,4 => array('4', 'Level 2', null, 'WHERE induction.`iag_numeracy` = "L2"')
        // ,5 => array('5', 'Level 3', null, 'WHERE induction.`iag_numeracy` = "L3"')
        // ,6 => array('6', 'Unclassified', null, 'WHERE induction.`iag_numeracy` = "U1"')
        // );
        // $f = new VoltDropDownViewFilter($viewFullName.'filter_iag_numeracy', $options, null, true);
        // $f->setDescriptionFormat("Maths: %s");
        // $view->addFilter($f);

        // $options = array(
        //     0 => array('0', 'Entry Level 1', null, 'WHERE induction.`iag_literacy` = "E1"')
        // ,1 => array('1', 'Entry Level 2', null, 'WHERE induction.`iag_literacy` = "E2"')
        // ,2 => array('2', 'Entry Level 3', null, 'WHERE induction.`iag_literacy` = "E3"')
        // ,3 => array('3', 'Level 1', null, 'WHERE induction.`iag_literacy` = "L1"')
        // ,4 => array('4', 'Level 2', null, 'WHERE induction.`iag_literacy` = "L2"')
        // ,5 => array('5', 'Level 3', null, 'WHERE induction.`iag_literacy` = "L3"')
        // ,6 => array('6', 'Unclassified', null, 'WHERE induction.`iag_literacy` = "U1"')
        // );
        // $f = new VoltDropDownViewFilter($viewFullName.'filter_iag_literacy', $options, null, true);
        // $f->setDescriptionFormat("English: %s");
        // $view->addFilter($f);

        // $options = array(
        //     0 => array('0', 'Entry Level 1', null, 'WHERE induction.`iag_ict` = "E1"')
        // ,1 => array('1', 'Entry Level 2', null, 'WHERE induction.`iag_ict` = "E2"')
        // ,2 => array('2', 'Entry Level 3', null, 'WHERE induction.`iag_ict` = "E3"')
        // ,3 => array('3', 'Level 1', null, 'WHERE induction.`iag_ict` = "L1"')
        // ,4 => array('4', 'Level 2', null, 'WHERE induction.`iag_ict` = "L2"')
        // ,5 => array('5', 'Level 3', null, 'WHERE induction.`iag_ict` = "L3"')
        // ,6 => array('6', 'Unclassified', null, 'WHERE induction.`iag_ict` = "U1"')
        // ,7 => array('7', 'N/A', null, 'WHERE induction.`iag_ict` = "NA"')
        // );
        // $f = new VoltDropDownViewFilter($viewFullName.'filter_iag_ict', $options, null, true);
        // $f->setDescriptionFormat("ICT: %s");
        // $view->addFilter($f);

        $options = array(
            0 => array('16-18', '16-18', null, 'HAVING age_group = "16-18"')
        ,1 => array('19-24', '19-24', null, 'HAVING age_group = "19-24"')
        ,2 => array('24+', '24+', null, 'HAVING age_group = "24+"')
        ,3 => array('Under 16', 'Under 16', null, 'HAVING age_group = "Under 16"')
        );
        $f = new VoltDropDownViewFilter($viewFullName.'filter_age_group', $options, null, true);
        $f->setDescriptionFormat("Age Group: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT organisations.id, legal_name, null, CONCAT('WHERE inductees.employer_id=',organisations.id) FROM organisations WHERE organisation_type LIKE '%2%' ORDER BY legal_name";
        $f = new VoltDropDownViewFilter($viewFullName.'filter_employer', $options, null, true);
        $f->setDescriptionFormat("Employer: %s");
        $view->addFilter($f);

        //$options = "SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ', users.surname), null, CONCAT('WHERE induction.induction_assessor=',users.id) FROM users INNER JOIN induction ON users.id = induction.induction_assessor ORDER BY users.firstnames";

        //$options = "SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ', users.surname), null, CONCAT('WHERE induction.assigned_assessor=',users.id) FROM users INNER JOIN induction ON users.id = induction.assigned_assessor ORDER BY users.firstnames";
        $options = <<<SQL
SELECT *
FROM (
    SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ', users.surname ) AS assessor, NULL, CONCAT('WHERE induction.assigned_assessor=', users.id) FROM users INNER JOIN induction ON users.id = induction.assigned_assessor
    UNION ALL
    SELECT 'XXXX' AS id, '--- Blank ---' AS assessor, NULL, CONCAT('WHERE induction.assigned_assessor IS NULL')
) a
ORDER BY assessor
;
SQL;

        $f = new VoltDropDownViewFilter($viewFullName.'filter_a_assessor', $options, null, true);
        $f->setDescriptionFormat("Assigned Learning Mentor: %s");
        $view->addFilter($f);

        $options = " SELECT * FROM (SELECT 'SHOW_ALL', 'Show All', NULL, CONCAT('WHERE induction_programme.programme_id IN (', GROUP_CONCAT(DISTINCT courses.id), ')') FROM courses INNER JOIN frameworks ON courses.framework_id = frameworks.id INNER JOIN induction_programme ON courses.id = induction_programme.programme_id WHERE courses.`active` = 1 AND frameworks.`active` = 1 AND courses.induction = 'Y') AS a ";
        $options .= " UNION ALL ";
        $options .= " SELECT * FROM (SELECT DISTINCT courses.id, CONCAT(courses.title, ' (',frameworks.`title`, ')'), NULL, CONCAT('WHERE induction_programme.programme_id=',courses.id) FROM courses INNER JOIN frameworks ON courses.framework_id = frameworks.id INNER JOIN induction_programme ON courses.id = induction_programme.programme_id WHERE courses.`active` = 1 AND frameworks.`active` = 1 AND courses.induction = 'Y' ORDER BY courses.title) AS b ";
        $f = new VoltCheckboxViewFilter($viewFullName.'filter_programme', $options, array());
        $f->setDescriptionFormat("Programme: %s");
        $view->addFilter($f);

        // $options = " SELECT * FROM (SELECT 'SHOW_ALL', 'Show All', NULL, CONCAT('WHERE inductees.location_area IN (', GROUP_CONCAT(lookup_delivery_locations.id), ')') FROM lookup_delivery_locations  ) AS a ";
        // $options .= " UNION ALL ";
        // $options .= " SELECT * FROM (SELECT id, description, NULL,CONCAT('WHERE inductees.location_area=',id) FROM lookup_delivery_locations ORDER BY description) AS b ";
        // $f = new VoltCheckboxViewFilter($viewFullName.'filter_dl', $options, array());
        // $f->setDescriptionFormat("Delivery Location: %s");
        // $view->addFilter($f);


        $options = array(
            0=>array(20,20,null,null),
            1=>array(0, 'No limit', null, null));
        $f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        return $view;
    }

    public function renderView(PDO $link, VoltView $view, $tab = '')
    {
        $table_id = $view->getViewName();
        $table_id = str_replace('view_', 'tbl_', $table_id);

        //if(SOURCE_HOME || SOURCE_BLYTHE_VALLEY) pr($view->getSQLStatement()->__toString());

        $rows = array();
        $columns = DAO::getSingleColumn($link, "SELECT colum FROM view_columns WHERE view = 'ViewInduction' AND visible = '1' ORDER BY sequence");
        $result = DAO::getResultset($link, $view->getSQLStatement()->__toString(), DAO::FETCH_ASSOC);
        foreach($result AS $rs)
            $rows[] = $rs;
        unset($result);

        echo $view->getViewNavigatorExtra($tab);

        echo '<div class="table-responsive"><table id="'.$table_id.'" class="table row-border table-striped " cellspacing="0" width="100%">';
        echo '<thead>';
        echo '<tr><th>&nbsp;</th><th>&nbsp;</th>';
        foreach($columns as $column)
        {
            if($column == 'comments_on_induction' || $column == 'comments_on_holding_induction')
                echo '<th><u>' . ucwords(str_replace("_","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",str_replace("_and_"," & ",$column))) . '</u></th>';
            else
                echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
        }
        echo '</tr></thead>';
        echo '<tbody>';
        foreach($rows AS $row)
        {
            $record_id = $row['inductee_learner_id'];
            $open_url = 'do.php?_action=view_inductee&id='.$record_id;
            $edit_url = 'do.php?_action=edit_inductee&id='.$record_id;
            if($_SESSION['user']->induction_access == 'R')
                $edit_url = '#';
            $td = <<<HTML

<div class="btn-group">
<button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
	<span class="caret"></span>
	<span class="sr-only">Toggle Dropdown</span>
</button>
<ul class="dropdown-menu" role="menu">
	<li><a href="#" onclick="window.location.href='$open_url';"><span class="fa fa-folder-open"></span>Open</a></li>
	<li><a href="#" onclick="window.location.href='$edit_url';"><span class="fa fa-edit"></span>Edit</a></li>
</ul>
</div>

HTML;
            echo '<tr style="cursor: pointer;" onclick="loadQuickForm(\''.$record_id.'\');"><td>' . $td . '</td>';
            echo '<td>';
            if($row['induction_status'] == 'C')
                echo InductionHelper::getFlag('green');
            elseif($row['induction_status'] == 'TBA' || $row['induction_status'] == 'L')
                echo InductionHelper::getFlag('red');
            elseif($row['induction_status'] == 'S')
                echo InductionHelper::getFlag('yellow');
            elseif($row['induction_status'] == 'H')
                echo InductionHelper::getFlag('blue');
            if($row['red_flag_learner'] == 'Y')
                echo '<span class="btn btn-xs btn-danger"><i class="fa fa-warning" title="Red Flag Reason: ' . $row['comp_issue_notes'] . '"></i></span> ';
            echo '</td>';
            foreach($columns as $column)
            {
                if($column == 'induction_status')
                {
                    echo '<td>' . $row['induction_status_desc'] . '</td>';
                }
                elseif($column == 'comments_on_holding_induction')
                {
                    echo '<td><small><small>' . $row[$column] . '</small></small></td>';
                }
                elseif($column == 'comments_on_induction')
                {
                    echo '<td><small><small>' . $row[$column] . '</small></small></td>';
                }
                elseif($column == 'employer_type' && isset($row['employer_type']))
                {
                    echo '<td>' . $row['employer_type'] . '</td>';
                }
                elseif($column == 'dob')
                {
                    $age_today = Date::dateDiff(date("Y-m-d"), Date::toMySQL($row['dob']), 1);
                    $next_month_date = date("Y-m-d", strtotime("+6 weeks", strtotime(date('Y-m-d'))));
                    $year_diff = Date::dateDiffInfo($next_month_date, Date::toMySQL($row['dob']));
                    $year_diff = isset($year_diff['year'])?$year_diff['year']:'0';
                    if($year_diff == 19)
                        echo '<td>' . InductionHelper::getFlag('blue', 'Turning 19 in 6 weeks') . '&nbsp;' . $row['dob'] . '</td>';
                    else
                        echo '<td>' . $row['dob'] . '</td>';
                }
                else
                {
                    echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                }
            }
            echo '</tr>';
        }

        echo '</tbody></table></div>';
    }

    private function loadQuickForm(PDO $link)
    {
        $inductee_id = isset($_REQUEST['inductee_learner_id'])?$_REQUEST['inductee_learner_id']:'';
        $_inductee = Inductee::loadFromDatabase($link, $inductee_id);
        if(count($_inductee->inductions) == 0)
        {
            echo '<div class="callout callout-danger"><i class="fa fa-info-circle"></i> No induction information saved against this learner, please click on edit and enter induction information.</div> ';
            return;
        }
        else
        {
            $_induction = $_inductee->inductions[0]; /* @var $_induction Induction */
        }
        $programme = DAO::getSingleValue($link, "SELECT title FROM courses WHERE id IN (SELECT programme_id FROM induction_programme WHERE inductee_id = '{$inductee_id}')");
        if($programme == '')
            $programme = 'Not Enrolled';
        $skilsure_details = DAO::getObject($link, "SELECT skilsure_username, skilsure_password, mentor_username, mentor_password FROM induction_programme WHERE inductee_id = '{$inductee_id}' ");
        $sk_user = isset($skilsure_details->skilsure_username)?$skilsure_details->skilsure_username:'';
        $sk_pass = isset($skilsure_details->skilsure_password)?$skilsure_details->skilsure_password:'';
        $mn_user = isset($skilsure_details->mentor_username)?$skilsure_details->mentor_username:'';
        $mn_pass = isset($skilsure_details->mentor_password)?$skilsure_details->mentor_password:'';
        $listLearnerID = InductionHelper::getListLearnerID();
        $learner_id = isset($listLearnerID[$_inductee->learner_id])?$listLearnerID[$_inductee->learner_id]:'';

        $selectInductionStatus = HTML::selectChosen('induction_status', InductionHelper::getDDLInductionStatus(), $_induction->induction_status, true, true);
        $selectInductionStatus = str_replace('<option value="C"', '<option value="C" disabled', $selectInductionStatus);
        //$selectSLA = HTML::selectChosen('sla_received', InductionHelper::getDDLSLAReceived(), $_induction->sla_received, true);
        $selectLevyPayer = HTML::selectChosen('levy_payer', InductionHelper::getDDLYesNo(), $_induction->levy_payer, true);
        $dateMFGS = Date::toShort($_induction->date_moved_from_grey_section);

        $dob = Date::toShort($_inductee->dob);
        $menu_buttons = '<div class="pull-right">';
        if(SOURCE_BLYTHE_VALLEY)
        {
            if(is_null($_inductee->sunesis_username))
            {
                $disable_save = '';
                $menu_buttons .= '<span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=view_inductee&id='.$_inductee->id.'\';"><i class="fa fa-folder-open"></i> Open</span>';
                $menu_buttons .= ' <span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=edit_inductee&id='.$_inductee->id.'\';"><i class="fa fa-edit"></i> Edit</span>';
            }
            else
            {
                $menu_buttons .= '<span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=view_inductee&id='.$_inductee->id.'\';"><i class="fa fa-folder-open"></i> Open Induction Record</span>';
                $menu_buttons .= ' <span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=read_user&username='.$_inductee->sunesis_username.'\';"><i class="fa fa-folder-open"></i> Open Sunesis Record</span>';
                $disable_save = 'disabled title="Save disabled: Sunesis account is created for this learner"';
            }
        }
        else
        {
            if($_SESSION['user']->induction_access == 'R')
            {
                $disable_save = 'disabled title="Save disabled: You do not have access to save the record."';
                $menu_buttons .= '<span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=view_inductee&id='.$_inductee->id.'\';"><i class="fa fa-folder-open"></i> Open</span>';
            }
            else
            {
                if(is_null($_inductee->sunesis_username))
                {
                    $disable_save = '';
                    $menu_buttons .= '<span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=view_inductee&id='.$_inductee->id.'\';"><i class="fa fa-folder-open"></i> Open</span>';
                    $menu_buttons .= ' <span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=edit_inductee&id='.$_inductee->id.'\';"><i class="fa fa-edit"></i> Edit</span>';
                }
                else
                {
                    $menu_buttons .= '<span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=view_inductee&id='.$_inductee->id.'\';"><i class="fa fa-folder-open"></i> Open Induction Record</span>';
                    $menu_buttons .= ' <span class="btn btn-xs btn-default" onclick="window.location.href=\'do.php?_action=read_user&username='.$_inductee->sunesis_username.'\';"><i class="fa fa-folder-open"></i> Open Sunesis Record</span>';
                    $disable_save = 'disabled title="Save disabled: Sunesis account is created for this learner"';
                }
            }
        }

        $menu_buttons .= '</div>';
        $employer = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$_inductee->employer_id}'");
        $contact_no = $_inductee->home_mobile != ''? $_inductee->home_mobile:$_inductee->home_telephone;
        $age_today = Date::dateDiff(date("Y-m-d"), $_inductee->dob, 1);
        $next_month_date = date("Y-m-d", strtotime("+6 weeks", strtotime(date('Y-m-d'))));
        $year_diff = Date::dateDiffInfo($next_month_date, $_inductee->dob);
        $year_diff = isset($year_diff['year'])?$year_diff['year']:'0';
        //$year_diff = '';
        if($year_diff == 19)
            $year_diff = InductionHelper::getFlag('blue', 'Turing 19 in 6 weeks');
        else
            $year_diff = '';

        echo <<<HTML
<div class="box box-widget widget-user">
	<div class="widget-user-header bg-aqua-active">
		<h2 class="widget-user-username">$_inductee->firstnames $_inductee->surname</h2>
		<h5 class="widget-user-desc">$employer</h5>
		$menu_buttons
	</div>
	<div class="box-body">
		<ul class="nav nav-stacked">
			<li>$year_diff</li>
			<li><label>DOB:</label><span class="pull-right">$dob (Age today: $age_today)</span></li>
			<li><label>Contact Tel:</label><span class="pull-right">$contact_no</span></li>
			<li><label>Programme:</label><span class="pull-right">$programme</span></li>
			<li><label>Smart Assessor username:</label><span class="pull-right">$sk_user</span></li>
			<li><label>Smart Assessor password:</label><span class="pull-right">$sk_pass</span></li>
			<li><label>Mentor username:</label><span class="pull-right">$mn_user</span></li>
			<li><label>Mentor password:</label><span class="pull-right">$mn_pass</span></li>
			<li><label>Learner ID:</label><span class="pull-right">$learner_id</span></li>
		</ul>
	</div>
	<div class="box-footer">
		<form role="form" name="frmQuickSaveInduction" id="frmQuickSaveInduction" action="/do.php" method="post">
			<input type="hidden" name="_action" value="save_induction" />
			<input type="hidden" name="formName" value="frmQuickSaveInduction" />
			<input type="hidden" name="id" value="$_induction->id" />
			<div class="form-group">
				<label for="induction_status">Induction Status</label>
				$selectInductionStatus
			</div>
			<div class="form-group">
				<label for="levy_payer">Levy Payer</label>
				$selectLevyPayer
			</div>
			<div class="form-group">
				<label for="work_email">Work Email:</label>
				<input type="text" class="form-control optional" name="work_email" id="work_email" value="$_inductee->work_email" maxlength="100" />
			</div>
			<div class="form-group">
				<label for="date_moved_from_grey_section">Date moved from grey section</label>
				<input type="text" class="form-control datepicker optional" name="date_moved_from_grey_section" id="input_date_moved_from_grey_section" value="$dateMFGS" maxlength="10" placeholder="dd/mm/yyyy" />
			</div>
			<div class="form-group">
				<label for="grey_section_comments">Grey Section Comments: <i class="fa fa-comments" title="show saved grey section comments" style="cursor: pointer" onclick="showNotes('$_induction->id', 'grey_section_comments');"></i></label>
				<textarea class="form-control optional" name="grey_section_comments" id="grey_section_comments" rows="5"></textarea>
			</div>
			<div class="form-group">
				<label for="contact_comments">Contact Comments: <i class="fa fa-comments" title="show saved contact comments" style="cursor: pointer" onclick="showNotes('$_induction->id', 'contact_comments');"></i></label>
				<textarea class="form-control optional" name="contact_comments" id="contact_comments" rows="5"></textarea>
			</div>
			<div class="form-group">
				<label for="induction_notes">Induction Comments: <i class="fa fa-comments" title="show saved induction comments" style="cursor: pointer" onclick="showNotes('$_induction->id', 'induction_notes');"></i></label>
				<textarea class="form-control optional" name="induction_notes" id="induction_notes" rows="5"></textarea>
			</div>

			<div>
				<button type="button" $disable_save class="btn btn-xs btn-primary pull-right" onclick="saveQuickSaveInduction(); "><i class="fa fa-save"></i> Save </button>
			</div>
		</form>
	</div>
</div>
HTML;
        unset($_inductee);
        unset($_induction);

    }

}