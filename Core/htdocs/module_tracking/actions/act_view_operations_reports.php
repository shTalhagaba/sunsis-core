<?php
class view_operations_reports implements IAction
{
    public function execute(PDO $link)
    {
        $subview = isset($_REQUEST['subview'])?$_REQUEST['subview']:'';
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        if($subview == '')
            throw new Exception('Report type is missing');

        if($subview == 'view_op_mock_status_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_op_mock_status_report", "Mock Status Report");
            $view = VoltView::getViewFromSession('view_op_mock_status_report', 'view_op_mock_status_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_op_mock_status_report'] = $this->buildView($link, 'view_op_mock_status_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_additional_support_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_additional_support_report", "Additional Support Report");
            $view = VoltView::getViewFromSession('view_additional_support_report', 'view_additional_support_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_additional_support_report'] = $this->buildView($link, 'view_additional_support_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_achievements_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_achievements_report", "Achievements Report");
            $view = VoltView::getViewFromSession('view_achievements_report', 'view_achievements_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_achievements_report'] = $this->buildView($link, 'view_achievements_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_epa_status_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_epa_status_report", "EPA Status Report");
            $view = VoltView::getViewFromSession('view_epa_status_report', 'view_epa_status_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_epa_status_report'] = $this->buildView($link, 'view_epa_status_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_3weeks_calls_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_3weeks_calls_report", "3 week call & 48 hour calls");
            $view = VoltView::getViewFromSession('view_3weeks_calls_report', 'view_3weeks_calls_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_3weeks_calls_report'] = $this->buildView($link, 'view_3weeks_calls_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_course_status_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_course_status_report", "Course Status Report");
            $view = VoltView::getViewFromSession('view_course_status_report', 'view_course_status_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_course_status_report'] = $this->buildView($link, 'view_course_status_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_id_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_id_report", "Photographic ID Report");
            $view = VoltView::getViewFromSession('view_id_report', 'view_id_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_id_report'] = $this->buildView($link, 'view_id_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_reschedule_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_reschedule_report", "Sessions Cancellation Report");
            $view = VoltView::getViewFromSession('view_reschedule_report', 'view_reschedule_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_reschedule_report'] = $this->buildView($link, 'view_reschedule_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_operations_lar')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_operations_lar", "View Operations LAR Report");
            $view = VoltView::getViewFromSession('view_operations_lar', 'view_operations_lar'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_operations_lar'] = $this->buildView($link, 'view_operations_lar');
            }
            $view->refresh($_REQUEST, $link);
        }
        /*if($subview == 'view_sales_lar')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_sales_lar", "View Operations LAR Report");
            $view = VoltView::getViewFromSession('view_sales_lar', 'view_sales_lar');
            if(is_null($view))
            {
                $view = $_SESSION['view_sales_lar'] = $this->buildView($link, 'view_sales_lar');
            }
            $view->refresh($_REQUEST, $link);
        }*/
        if($subview == 'previous_on_lar')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=previous_on_lar", "View Previous on LAR Report");
            $view = VoltView::getViewFromSession('previous_on_lar', 'previous_on_lar'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['previous_on_lar'] = $this->buildView($link, 'previous_on_lar');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_operations_lar_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_operations_lar_report", "View Previous on LAR Report");
            $view = VoltView::getViewFromSession('view_operations_lar_report', 'view_operations_lar_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_operations_lar_report'] = $this->buildView($link, 'view_operations_lar_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_sales_lar_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_sales_lar_report", "View Previous on LAR Report");
            $view = VoltView::getViewFromSession('view_sales_lar_report', 'view_sales_lar_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_sales_lar_report'] = $this->buildView($link, 'view_sales_lar_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_operations_bil_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_operations_bil_report", "View Previous on LAR Report");
            $view = VoltView::getViewFromSession('view_operations_bil_report', 'view_operations_bil_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_operations_bil_report'] = $this->buildView($link, 'view_operations_bil_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_monthly_leavers_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_monthly_leavers_report", "View Previous on LAR Report");
            $view = VoltView::getViewFromSession('view_monthly_leavers_report', 'view_monthly_leavers_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_monthly_leavers_report'] = $this->buildView($link, 'view_monthly_leavers_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_interviews')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_interviews", "View Interviews Report");
            $view = VoltView::getViewFromSession('view_interviews', 'view_interviews'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_interviews'] = $this->buildView($link, 'view_interviews');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_prevention_alert_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_prevention_alert_report", "View Previous on LAR Report");
            $view = VoltView::getViewFromSession('view_prevention_alert_report', 'view_prevention_alert_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_prevention_alert_report'] = $this->buildView($link, 'view_prevention_alert_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_ach_forecast_in_prog')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_ach_forecast_in_prog", "View Ach. Forecast - In Progress");
            $view = VoltView::getViewFromSession('view_ach_forecast_in_prog', 'view_ach_forecast_in_prog'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_ach_forecast_in_prog'] = $this->buildView($link, 'view_ach_forecast_in_prog');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_ach_forecast_gateway_ready')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_ach_forecast_gateway_ready", "View Ach. Forecast - Gateway Ready");
            $view = VoltView::getViewFromSession('view_ach_forecast_gateway_ready', 'view_ach_forecast_gateway_ready'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_ach_forecast_gateway_ready'] = $this->buildView($link, 'view_ach_forecast_gateway_ready');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_ach_forecast_framework')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_ach_forecast_framework", "View Ach. Forecast - Framework");
            $view = VoltView::getViewFromSession('view_ach_forecast_framework', 'view_ach_forecast_framework'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_ach_forecast_framework'] = $this->buildView($link, 'view_ach_forecast_framework');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_learners_additional_info_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_learners_additional_info_report", "View Learners Additional Info. Report");
            $view = VoltView::getViewFromSession('view_learners_additional_info_report', 'view_learners_additional_info_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_learners_additional_info_report'] = $this->buildView($link, 'view_learners_additional_info_report');
            }
            $view->refresh($_REQUEST, $link);
        }
        if($subview == 'view_lras_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_lras_report", "View LRAS Report");
            $view = VoltView::getViewFromSession('view_lras_report', 'view_lras_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_lras_report'] = $this->buildView($link, 'view_lras_report');
            }
            $view->refresh($_REQUEST, $link);
        }
	if($subview == 'view_lar_potential_leaver_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_lar_potential_leaver_report", "View BIL Potential Leaver Report");
            $view = VoltView::getViewFromSession('view_lar_potential_leaver_report', 'view_lar_potential_leaver_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_lar_potential_leaver_report'] = $this->buildView($link, 'view_lar_potential_leaver_report');
            }
            $view->refresh($_REQUEST, $link);
        }
	if($subview == 'view_leaver_reinstatement')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_leaver_reinstatement", "View LAR Potential Leaver Report");
            $view = VoltView::getViewFromSession('view_leaver_reinstatement', 'view_leaver_reinstatement'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_leaver_reinstatement'] = $this->buildView($link, 'view_leaver_reinstatement');
            }
            $view->refresh($_REQUEST, $link);
        }
	    if($subview == 'view_interview_cancellation_report')
        {
            $_SESSION['bc']->add($link, "do.php?_action=view_operations_reports&subview=view_interview_cancellation_report", "View Interview Cancellation Report");
            $view = VoltView::getViewFromSession('view_interview_cancellation_report', 'view_interview_cancellation_report'); /* @var $view VoltView */
            if(is_null($view))
            {
                $view = $_SESSION['view_interview_cancellation_report'] = $this->buildView($link, 'view_interview_cancellation_report');
            }
            $view->refresh($_REQUEST, $link);
        }

        if($subaction == 'export_csv')
        {
            if($view->getViewName() == 'view_learners_additional_info_report')
                $this->exportAdditionInfoReportView($link, $view);
            elseif($view->getViewName() == 'view_ach_forecast_gateway_ready')
                $this->export_view_ach_forecast_gateway_ready_report($link, $view);
            elseif($view->getViewName() == 'previous_on_lar')
                $this->export_view_previous_on_lar_report($link, $view);
            else
                $this->exportToCSV($link, $view);
            exit;
        }

        include_once('tpl_view_operations_reports.php');
    }

    private function buildView(PDO $link, $view_name)
    {
        if($view_name == 'view_op_mock_status_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  op_trackers.`title` AS programme,
  (SELECT legal_name FROM organisations WHERE id = tr.employer_id) AS employer,
  tr.l03 AS learner_reference,
  tr.`firstnames`,
  tr.`surname`,
  mock_table.unit_ref AS unit_title,
  CASE
    mock_table.mock_code
    WHEN 'MO' THEN 'Mock Outstanding'
    WHEN 'MI' THEN 'Mock Issued'
    WHEN 'MP' THEN 'Mock Passed'
	WHEN 'SI' THEN 'SDS Issued'
    WHEN 'SO' THEN 'SDS Outstanding'
    WHEN 'SP' THEN 'SDS Passed'
  END AS mock_code,
  (SELECT CONCAT(users.`firstnames`, ' ' , users.`surname`) FROM users WHERE users.id = mock_table.created_by) AS created_by,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  DATE_FORMAT(mock_table.`created`,'%d/%m/%Y %H:%i:%s') AS created,
  mock_table.comments
FROM
  (SELECT m1.*
FROM op_tracker_unit_mock m1 LEFT JOIN op_tracker_unit_mock m2
 ON (m1.tr_id = m2.tr_id  AND m1.`unit_ref` = m2.`unit_ref` AND m1.id < m2.id)
WHERE m2.id IS NULL) AS mock_table
  LEFT JOIN tr ON mock_table.tr_id = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN tr_operations ON tr.id = tr_operations.tr_id
WHERE
  (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")

;
SQL;
        }
        elseif($view_name == 'view_id_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  op_trackers.`title` AS programme,
  tr.firstnames, tr.surname,
  CASE
    tr_operations.learner_id
    WHEN 'RPI' THEN 'Received prior induction'
    WHEN 'RAI' THEN 'Received after induction'
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
  END AS learner_id,
  organisations.legal_name AS employer,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  #(SELECT DISTINCT DATE_FORMAT(`induction_date`, '%d/%m/%Y') FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  #WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS induction_date
  induction_fields.induction_date AS induction_date, 
  tr_operations.learner_id_notes
FROM
  tr_operations
  LEFT JOIN tr ON tr_operations.tr_id = tr.id
  LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.employer_id = organisations.id
  LEFT JOIN (
  SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, 
  DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date,
  EXTRACTVALUE(inductees.`learner_id_notes`, '/Notes/Note[last()]/Note') AS learner_id_notes
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
WHERE tr_operations.`learner_id` IS NOT NULL AND op_trackers.`title` IS NOT NULL
AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
        }
        elseif($view_name == 'view_reschedule_report')
        {
            $sql = <<<SQL
SELECT 
  session_cancellations.session_id,
  session_cancellations.tr_id AS training_id,
  tr.firstnames,
  tr.surname,
  organisations.legal_name AS employer,
  op_trackers.title AS programme,
  sessions.unit_ref,
  DATE_FORMAT(sessions.start_date, '%d/%m/%Y') AS session_date,
  DATE_FORMAT(session_cancellations.cancellation_date, '%d/%m/%Y') AS cancellation_date,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = session_cancellations.cancelled_by) AS cancelled_by,
  CASE session_cancellations.category
      WHEN 'lar' THEN 'LAR'
      WHEN 'bil' THEN 'BIL'
      WHEN 'lvr' THEN 'Leaver'
      WHEN 'ecc' THEN 'Employer cannot commit'
      WHEN 'los' THEN 'Learner off sick'
      WHEN 'loh' THEN 'Learner on holiday'
      WHEN 'oth' THEN 'Other'
      WHEN 'brs' THEN 'Baltic re-schedule'
      WHEN 'err' THEN 'Co-ordinator error'
      WHEN 'lcc' THEN 'Learner cannot commit'
	WHEN 'exm' THEN 'Exempt'
      WHEN 'tec' THEN 'Technical'
      WHEN 'id' THEN 'ID'
      WHEN 'add' THEN 'Address'
      ELSE ''
  END AS category,
  CASE session_cancellations.cancellation_type
    WHEN '1' THEN 'Schedule cancellation'
    WHEN '2' THEN 'Prior to Reminders'
    WHEN '3' THEN 'Cancellation within 4 weeks notice'
    WHEN '4' THEN 'Cancellation with 14+ days notice'
    WHEN '5' THEN 'Cancellation with 7+ days notice'
    WHEN '6' THEN 'Cancelled within 7 days of course start date'
    WHEN '7' THEN 'Cancelled on the day of course'
    WHEN '8' THEN 'Other'
    ELSE ''
  END AS cancellation_type,
  session_cancellations.comments,
  induction_fields.induction_date,
  ROUND(DATEDIFF(session_cancellations.`cancellation_date`, induction_fields.ind_date)/7, 2) AS induction_date_to_cancellation_date,
  ROUND(DATEDIFF(sessions.`start_date`, session_cancellations.`cancellation_date`)/7, 2) AS session_date_to_cancellation_date,
  (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = sessions.personnel) AS trainer,
  CASE sessions.event_type
     WHEN 'CRS' THEN 'Course'
    WHEN 'DEV' THEN 'Development'
    WHEN 'EX' THEN 'Exam'
    WHEN 'MRK' THEN 'Marking'
    WHEN 'OBS' THEN 'Observations'
    WHEN 'PRP' THEN 'Preparations'
    WHEN 'ST' THEN 'Staff training'
    WHEN 'SUP' THEN 'Support'
    WHEN 'TM' THEN 'Trainer meeting'
    WHEN 'WRK' THEN 'Workshop'
    WHEN 'O' THEN 'Other'
    ELSE '' 
  END AS event_type
FROM
  session_cancellations INNER JOIN sessions ON session_cancellations.session_id = sessions.id
  LEFT JOIN tr ON session_cancellations.tr_id = tr.id
  LEFT JOIN courses_tr ON tr.`id` = courses_tr.`tr_id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.employer_id = organisations.id
  LEFT JOIN tr_operations ON tr.id = tr_operations.tr_id
  LEFT JOIN (
  SELECT DISTINCT sunesis_username, induction_programme.`programme_id`,
  DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date, induction.induction_date AS ind_date
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
WHERE
  #(tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
  true
;
SQL;
        }
        elseif($view_name == 'view_operations_lar')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr.firstnames,
  tr.surname,
  organisations.legal_name AS employer,
  op_trackers.title AS programme,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  #extractvalue(lar_details, '/Notes/Note[1]/Date') AS added_to_lar_date,
  '' AS added_to_lar_date,
  tr_operations.lar_details,
  extractvalue(lar_details, '/Notes/Note[last()]/LastActionDate') AS date_of_last_action,
  extractvalue(lar_details, '/Notes/Note[last()]/NextActionDate') AS date_of_next_action,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[Type="N"][last()]/Date') AS lar_closed_date,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor
FROM
  tr_operations INNER JOIN tr ON tr_operations.tr_id = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.employer_id = organisations.id
WHERE
  tr_operations.lar_details IS NOT NULL AND extractvalue(tr_operations.`lar_details`, 'count(/Notes/Note[Type="O"])') > 0
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
        }
        elseif($view_name == 'view_sales_lar')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr.firstnames,
  tr.surname,
  organisations.legal_name AS employer,
  op_trackers.title AS programme,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  #extractvalue(lar_details, '/Notes/Note[1]/Date') AS added_to_lar_date,
  '' AS added_to_lar_date,
  tr_operations.lar_details,
  extractvalue(lar_details, '/Notes/Note[last()]/LastActionDate') AS date_of_last_action,
  extractvalue(lar_details, '/Notes/Note[last()]/NextActionDate') AS date_of_next_action,
  extractvalue(lar_details, '/Notes/Note[last()]/SalesDeadlineDate') AS sales_deadline_date,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor
FROM
  tr_operations INNER JOIN tr ON tr_operations.tr_id = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.employer_id = organisations.id
WHERE
  tr_operations.lar_details IS NOT NULL AND extractvalue(tr_operations.`lar_details`, 'count(/Notes/Note[Type="S"])') > 0
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
        }
        elseif($view_name == 'previous_on_lar')
        {
            $sql = <<<SQL
SELECT DISTINCT
tr.firstnames,
  tr.surname,
  organisations.legal_name AS employer,
  op_trackers.title AS programme,
  induction_fields.induction_date,
  #(SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = induction_fields.assigned_coord) AS coordinator,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = induction_fields.assigned_assessor) AS learning_mentor,
  EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/Type') AS lar_type,
  EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/Date') AS lar_date,
  CASE EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[last()-1]/RAG')
  	WHEN '1' THEN 'LAR - Terminate'
  	WHEN '2' THEN 'LAR - Tolerate'
  	WHEN '3' THEN 'LAR - Treat'
  	WHEN '4' THEN 'BIL LAR - Terminate'
  	WHEN '5' THEN 'BIL LAR - Tolerate'
  	WHEN '6' THEN 'BIL LAR - Treat'
	WHEN '7' THEN 'High Risk LAR - Terminate'
	WHEN '8' THEN 'High Risk LAR - Tolerate'
	WHEN '9' THEN 'High Risk LAR - Treat'
	WHEN '10' THEN 'High Risk BIL LAR - Terminate'
	WHEN '11' THEN 'High Risk BIL LAR - Tolerate'
	WHEN '12' THEN 'High Risk BIL LAR - Treat'
	WHEN 'R' THEN 'Red'
	WHEN 'A' THEN 'Amber'
	WHEN 'G' THEN 'Green'
  END AS lar_rag,
  EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[last()]/ClosedDate') AS lar_closed_date,
  EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/Reason') AS lar_primary_reason,
  EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/SecondReason') AS lar_secondary_reason,
  EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/Retention') AS retention_category,
  EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[position()=last()-1]/ActivelyInvolved') AS actively_involved,
  CASE tr.status_code
	WHEN '1' THEN 'Continuing'
	WHEN '2' THEN 'Completed'
	WHEN '3' THEN 'Withdrawn'
	WHEN '4' THEN 'Transferred'
	WHEN '5' THEN 'Changes in Learning'
	WHEN '6' THEN 'Temp. Withdrawn'
	ELSE ''
  END AS training_record_status,
  tr_operations.lar_details
FROM
  tr_operations INNER JOIN tr ON tr_operations.tr_id = tr.id
  LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.employer_id = organisations.id
  LEFT JOIN (
  SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, induction.assigned_coord, induction.assigned_assessor,
  DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
WHERE
  tr_operations.lar_details IS NOT NULL
  AND extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') = "N"
#  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
        }
        elseif($view_name == 'view_additional_support_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr.l03,
  tr.`firstnames`,
  tr.`surname`,
  op_trackers.`title` AS programme,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(tr.closure_date, '%d/%m/%Y') AS actual_end_date,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  #tr.ad_lldd AS learning_difficulties_disabilities,
  #tr.ad_arrangement_req AS support_arrangements_requested,
  #tr.ad_arrangement_agr AS support_arrangements_agreed,
  CASE tr.ad_evidence
  WHEN 'R' THEN 'Requested'
  WHEN 'p' THEN 'Provided'
  WHEN 'D' THEN 'Declined'
  WHEN 'A' THEN 'Accepted'
  END AS evidence,
  tr_operations.additional_support,
  CASE tr_operations.ldd
    WHEN 'MLD' THEN 'Moderate Learning Difficulty' 
    WHEN 'SLD' THEN 'Severe Learning Difficulty' 
    WHEN 'DXA' THEN 'Dyslexia' 
    WHEN 'DLA' THEN 'Dyscalculia' 
    WHEN 'ASD' THEN 'Autism Spectrum Disorder' 
    WHEN 'OSLD' THEN 'Other Specific Learning Difficulty' 
    WHEN 'OTH' THEN 'Other (Additional Data Required)' 
    WHEN 'PNS' THEN 'Prefer Not To Say' 
    WHEN 'NP' THEN 'Not provided' 
    WHEN 'N' THEN 'None'
  END AS ldd,
  tr_operations.ldd_comments,
  tr.ad_arrangement_req AS support_arrangements_requested,           
  tr.ad_arrangement_agr AS support_arrangements_agreed,
  (SELECT DATE_FORMAT(inductees.ldd_set_date, '%d/%m/%Y') FROM inductees INNER JOIN induction_programme ON inductees.id = induction_programme.inductee_id
  WHERE tr.username = inductees.sunesis_username AND courses_tr.`course_id` = induction_programme.programme_id)   AS ad_supp_identified           
FROM
  tr_operations
  LEFT JOIN tr ON tr_operations.tr_id = tr.id
  LEFT JOIN courses_tr ON tr.id = courses_tr.`tr_id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
WHERE
  tr_operations.additional_support IS NOT NULL
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
        }
        elseif($view_name == 'view_achievements_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  op_trackers.`title` AS programme,
  tr.`firstnames`,
  tr.`surname`,
  DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS predicted_end_date,
  DATE_FORMAT(tr_operations.`completed_date`, '%d/%m/%Y') AS completion_date
FROM
  tr_operations
  LEFT JOIN tr ON tr_operations.tr_id = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
WHERE
  (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
        }
        elseif($view_name == 'view_epa_status_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  op_trackers.`title` AS programme,
  tr.`firstnames`,
  tr.`surname`,
  DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS planned_end_date,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  (SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '1' ORDER BY op_epa.id DESC LIMIT 1) AS 'EPA ready',
  (SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' 
	WHEN '28' THEN 'EPA Ready' WHEN '29' THEN 'Sent' WHEN '30' THEN 'Chased' WHEN '31' THEN 'Complete' WHEN '32' THEN 'Not Sent' WHEN '33' THEN 'Yes' WHEN '34' THEN 'No' WHEN '35' THEN 'Yes' WHEN '36' THEN 'No' END
  FROM op_epa WHERE tr_id = tr_operations.tr_id AND task = '1' ORDER BY op_epa.id DESC LIMIT 1) AS status_1,
  (SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '2' ORDER BY op_epa.id DESC LIMIT 1) AS 'Employer reference',
  (SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' 
	WHEN '28' THEN 'EPA Ready' WHEN '29' THEN 'Sent' WHEN '30' THEN 'Chased' WHEN '31' THEN 'Complete' WHEN '32' THEN 'Not Sent' WHEN '33' THEN 'Yes' WHEN '34' THEN 'No' WHEN '35' THEN 'Yes' WHEN '36' THEN 'No' END
  FROM op_epa WHERE tr_id = tr_operations.tr_id AND task = '2' ORDER BY op_epa.id DESC LIMIT 1) AS status_2,
  (SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '3' ORDER BY op_epa.id DESC LIMIT 1) AS 'Summative portfolio',
  (SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' 
	WHEN '28' THEN 'EPA Ready' WHEN '29' THEN 'Sent' WHEN '30' THEN 'Chased' WHEN '31' THEN 'Complete' WHEN '32' THEN 'Not Sent' WHEN '33' THEN 'Yes' WHEN '34' THEN 'No' WHEN '35' THEN 'Yes' WHEN '36' THEN 'No' END
  FROM op_epa WHERE tr_id = tr_operations.tr_id AND task = '3' ORDER BY op_epa.id DESC LIMIT 1) AS status_3,
  (SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '4' ORDER BY op_epa.id DESC LIMIT 1) AS 'IQA complete',
  (SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' 
	WHEN '28' THEN 'EPA Ready' WHEN '29' THEN 'Sent' WHEN '30' THEN 'Chased' WHEN '31' THEN 'Complete' WHEN '32' THEN 'Not Sent' WHEN '33' THEN 'Yes' WHEN '34' THEN 'No' WHEN '35' THEN 'Yes' WHEN '36' THEN 'No' END
  FROM op_epa WHERE tr_id = tr_operations.tr_id AND task = '4' ORDER BY op_epa.id DESC LIMIT 1) AS status_4,
  (SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '5' ORDER BY op_epa.id DESC LIMIT 1) AS 'Passed to awarding body',
  (SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' 
	WHEN '28' THEN 'EPA Ready' WHEN '29' THEN 'Sent' WHEN '30' THEN 'Chased' WHEN '31' THEN 'Complete' WHEN '32' THEN 'Not Sent' WHEN '33' THEN 'Yes' WHEN '34' THEN 'No' WHEN '35' THEN 'Yes' WHEN '36' THEN 'No' END
  FROM op_epa WHERE tr_id = tr_operations.tr_id AND task = '5' ORDER BY op_epa.id DESC LIMIT 1) AS status_5,
  (SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '6' ORDER BY op_epa.id DESC LIMIT 1) AS 'Synoptic project',
  (SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' 
	WHEN '28' THEN 'EPA Ready' WHEN '29' THEN 'Sent' WHEN '30' THEN 'Chased' WHEN '31' THEN 'Complete' WHEN '32' THEN 'Not Sent' WHEN '33' THEN 'Yes' WHEN '34' THEN 'No' WHEN '35' THEN 'Yes' WHEN '36' THEN 'No' END
  FROM op_epa WHERE tr_id = tr_operations.tr_id AND task = '6' ORDER BY op_epa.id DESC LIMIT 1) AS status_6,
  (SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '7' ORDER BY op_epa.id DESC LIMIT 1) AS 'Interview',
  (SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' 
	WHEN '28' THEN 'EPA Ready' WHEN '29' THEN 'Sent' WHEN '30' THEN 'Chased' WHEN '31' THEN 'Complete' WHEN '32' THEN 'Not Sent' WHEN '33' THEN 'Yes' WHEN '34' THEN 'No' WHEN '35' THEN 'Yes' WHEN '36' THEN 'No' END
  FROM op_epa WHERE tr_id = tr_operations.tr_id AND task = '7' ORDER BY op_epa.id DESC LIMIT 1) AS status_7,
  (SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '8' ORDER BY op_epa.id DESC LIMIT 1) AS 'EPA result',
  (SELECT IF(op_epa.`task_type` = '1', 'On Programme', (IF(op_epa.`task_type` = '2', 'Re-Sit', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '8' ORDER BY op_epa.id DESC LIMIT 1) AS 'EPA result type',
  (SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' 
	WHEN '28' THEN 'EPA Ready' WHEN '29' THEN 'Sent' WHEN '30' THEN 'Chased' WHEN '31' THEN 'Complete' WHEN '32' THEN 'Not Sent' WHEN '33' THEN 'Yes' WHEN '34' THEN 'No' WHEN '35' THEN 'Yes' WHEN '36' THEN 'No' WHEN '41' THEN 'Fail - Continue' WHEN '42' THEN 'Fail - completion non-achiever' END
  FROM op_epa WHERE tr_id = tr_operations.tr_id AND task = '8' ORDER BY op_epa.id DESC LIMIT 1) AS status_8,
  (SELECT DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y') AS task_actual_date FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '8' ORDER BY op_epa.id DESC LIMIT 1) AS 'EPA result Actual Date',
  (SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '9' ORDER BY op_epa.id DESC LIMIT 1) AS 'Project',
  (SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' 
	WHEN '28' THEN 'EPA Ready' WHEN '29' THEN 'Sent' WHEN '30' THEN 'Chased' WHEN '31' THEN 'Complete' WHEN '32' THEN 'Not Sent' WHEN '33' THEN 'Yes' WHEN '34' THEN 'No' WHEN '35' THEN 'Yes' WHEN '36' THEN 'No' END
  FROM op_epa WHERE tr_id = tr_operations.tr_id AND task = '9' ORDER BY op_epa.id DESC LIMIT 1) AS status_9,
  (SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = tr.id AND op_epa.`task` = '12'
ORDER BY
	id DESC
LIMIT 1
) AS gateway_forecast,
(SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = tr.id AND op_epa.`task` = '3'
ORDER BY
	id DESC
LIMIT 1
) AS summative_portfolio,
(SELECT
	IF(op_epa.task_applicable = 'Y', 'Yes', 'No')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = tr.id AND op_epa.`task` = '15'
ORDER BY
	id DESC
LIMIT 1
) AS eol,
(SELECT
    DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = tr.id AND op_epa.`task` = '15'
ORDER BY
	id DESC
LIMIT 1
) AS eol_actual_date
FROM
  tr 
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id
WHERE
  tr.id IN (SELECT tr_id FROM op_epa) AND 
  (tr_operations.`leaver_details` IS NULL OR EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
        }

        elseif($view_name == 'view_3weeks_calls_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  op_trackers.`title` AS programme,
  tr.`firstnames`,
  tr.`surname`,
  #DATE_FORMAT(induction.induction_date, '%d/%m/%Y') AS induction_date,
  (SELECT DISTINCT DATE_FORMAT(`induction_date`, '%d/%m/%Y') FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS induction_date,
  #DATE_FORMAT(DATE_ADD(tr.`created`, INTERVAL + 48 HOUR),'%d/%m/%Y') AS 48_hr_call_planned_date,
  #DATE_FORMAT(tr_operations.`hour_48_call`,'%d/%m/%Y') AS 48_hr_actual_date,
  DATE_FORMAT(DATE_ADD(tr.`created`, INTERVAL + 21 DAY),'%d/%m/%Y') AS 3_week_call_planned_date,
  DATE_FORMAT(tr_operations.`week_3_call`,'%d/%m/%Y') AS 3_week_call_actual_date,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator
FROM
  #tr_operations
  #INNER JOIN tr ON tr_operations.`tr_id` = tr.id
  tr LEFT JOIN tr_operations ON tr_operations.`tr_id` = tr.id
  LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
WHERE
  (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
HAVING programme != ''
ORDER BY tr.firstnames
;
SQL;
        }

        elseif($view_name == 'view_course_status_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr.id AS training_id,
  op_trackers.`title` AS programme,
  (SELECT legal_name FROM organisations WHERE id = tr.employer_id) AS employer,
  tr.l03,
  tr.`firstnames`,
  tr.`surname`,
  DATE_FORMAT(tr.`dob`, '%d/%m/%Y') AS learner_dob,
  sch_table.unit_ref AS course,
  '' AS course_date,
  '' AS event_type,
  '' AS trainer,
  '' AS exam_time,
  '' AS rft,
  tr_operations.`additional_support`,
  CASE
    sch_table.code
    WHEN 'I' THEN 'Invited'
    WHEN 'B' THEN 'Booked'
    WHEN 'R' THEN 'Required'
    WHEN 'U' THEN 'Uploaded'
    WHEN 'P' THEN 'Pass'
    WHEN 'MC' THEN 'Merit / Credit'
    WHEN 'D' THEN 'Distinction'
    WHEN 'NR' THEN 'Not Required'
  END AS `code`,
  (SELECT CONCAT(users.`firstnames`, ' ' , users.`surname`) FROM users WHERE users.id = sch_table.created_by) AS created_by,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  DATE_FORMAT(sch_table.`created`,'%d/%m/%Y %H:%i:%s') AS created,
  tr.home_email AS personal_email,
  tr.work_email AS work_email,
  sch_table.comments
FROM
  (SELECT m1.*
FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2
 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id)
WHERE m2.id IS NULL) AS sch_table
  LEFT JOIN tr ON sch_table.tr_id = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN tr_operations ON tr.id = tr_operations.tr_id
WHERE
  (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
  AND tr.id IS NOT NULL
;
SQL;
        }
        elseif($view_name == 'view_operations_lar_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr_operations.tr_id AS SystemID,
  CASE extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/RAG')
  	WHEN '1' THEN 'LAR - Terminate'
  	WHEN '2' THEN 'LAR - Tolerate'
  	WHEN '3' THEN 'LAR - Treat'
  	WHEN '4' THEN 'BIL LAR - Terminate'
  	WHEN '5' THEN 'BIL LAR - Tolerate'
  	WHEN '6' THEN 'BIL LAR - Treat'
	WHEN '7' THEN 'High Risk LAR - Terminate'
	WHEN '8' THEN 'High Risk LAR - Tolerate'
	WHEN '9' THEN 'High Risk LAR - Treat'
	WHEN '10' THEN 'High Risk BIL LAR - Terminate'
	WHEN '11' THEN 'High Risk BIL LAR - Tolerate'
	WHEN '12' THEN 'High Risk BIL LAR - Treat'
	WHEN 'R' THEN 'Red'
	WHEN 'A' THEN 'Amber'
	WHEN 'G' THEN 'Green'
  END AS lar_rag,
  tr.`firstnames`,
  tr.`surname`,
  organisations.legal_name AS employer,
  #op_trackers.title AS programme,
  student_frameworks.title AS programme,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS planned_end_date,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[1]/Date') AS added_to_lar_date,
  '' AS added_to_lar_date,
  tr_operations.lar_details,
  #extractvalue(lar_details, '/Notes/Note[last()]/LastActionDate') AS date_of_last_action,
  extractvalue(lar_details, '/Notes/Note[last()]/NextActionDate') AS revisit_date,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Reason') AS lar_reason,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Retention') AS retention_category,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Owner') AS lar_owner,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') AS lar_type,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  CASE TRUE
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) > 24 THEN '24+'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS age_band,
#  (SELECT DISTINCT induction.`brm` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
#  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS BDM,
#  (SELECT DISTINCT induction.`resourcer` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
#  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS recruiter,
#  (SELECT DISTINCT induction.`lead_gen` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
#  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS lead_generator,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
#  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/LeaverDecision') AS leaver_decision_made,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/SecondReason') AS secondary_lar_reason,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Summary') AS lar_summary,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Communication') AS lar_communication,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ContactHistory') AS lar_contact_history,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/NextActionHistory') AS lar_next_action_history,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ActivelyInvolved') AS actively_involved,
  #IF(tr_operations.arm_involved = 'Y', 'Yes', IF(tr_operations.arm_involved = 'N', 'No', '') ) AS arm_involved,
  #DATE_FORMAT(tr_operations.arm_revisit, '%d/%m/%Y') AS arm_revisit,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/OpenDate') AS lar_open_date,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ClosedDate') AS lar_closed_date,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Destination') AS lar_destination
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
  LEFT JOIN courses_tr ON tr.id = courses_tr.`tr_id`
WHERE
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') IN ("O", "D")
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
        }
	elseif($view_name == 'view_lar_potential_leaver_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr_operations.tr_id AS SystemID,
  tr.`firstnames`,
  tr.`surname`,
  organisations.legal_name AS employer,
  student_frameworks.title AS programme,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS planned_end_date,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Reason') AS lar_reason,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/RAG') AS lar_rag
  
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
  LEFT JOIN courses_tr ON tr.id = courses_tr.`tr_id`
WHERE
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/RAG') = "R" AND 
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') IN ("O", "D", "S") AND
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ClosedDate') = "" AND
  (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
        }
	elseif($view_name == 'view_leaver_reinstatement')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr_operations.tr_id AS SystemID,
  tr.`firstnames`,
  tr.`surname`,
  organisations.legal_name AS employer,
  student_frameworks.title AS programme,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS planned_end_date,
  IF(tr_operations.previous_leaver = 1, 'Yes', 'No') AS previous_leaver,
  DATE_FORMAT(tr_operations.reinstatement_date, '%d/%m/%Y') AS reinstatement_date,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Note') AS leaver_note,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Date') AS leaver_date,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Reason') AS leaver_reason,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Cause') AS leaver_motive,
  #EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Retention') AS retention_category,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Owner') AS owner,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/LeaverDecision') AS leaver_decision_made,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/PositiveOutcome') AS leaver_positive_outcome
  
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
  LEFT JOIN courses_tr ON tr.id = courses_tr.`tr_id`
WHERE
    tr_operations.previous_leaver = '1'
;
SQL;
        }
	elseif($view_name == 'view_interview_cancellation_report')
    {
            $sql = <<<SQL
SELECT
  tr.`firstnames`,
  tr.`surname`,
  (SELECT
    student_frameworks.`title`
  FROM
    student_frameworks
  WHERE student_frameworks.`tr_id` = tr.`id`) AS programme,
  (SELECT
    organisations.`legal_name`
  FROM
    organisations
  WHERE organisations.`id` = tr.`employer_id`) AS employer,
  CASE op_epa.task_status
    WHEN 60
    THEN 'Baltic Invoice'
    WHEN 61
    THEN 'Employer Invoice'
    WHEN 62
    THEN 'Baltic Reviewing'
    WHEN 63
    THEN 'Invoice waived by EPAO'
    ELSE ''
  END AS task_status,
  DATE_FORMAT(
    op_epa.`task_date`,
    '%d/%m/%Y'
  ) AS task_date,
  op_epa.`task_epao`,
  op_epa.`task_comments`
FROM
  tr
  INNER JOIN op_epa
    ON tr.`id` = op_epa.`tr_id`
WHERE op_epa.`task` = 25
;
SQL;
        }
        elseif($view_name == 'view_sales_lar_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr_operations.tr_id AS SystemID,
  CASE extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/RAG')
  	WHEN '1' THEN 'LAR - Terminate'
  	WHEN '2' THEN 'LAR - Tolerate'
  	WHEN '3' THEN 'LAR - Treat'
  	WHEN '4' THEN 'BIL LAR - Terminate'
  	WHEN '5' THEN 'BIL LAR - Tolerate'
  	WHEN '6' THEN 'BIL LAR - Treat'
	WHEN '7' THEN 'High Risk LAR - Terminate'
	WHEN '8' THEN 'High Risk LAR - Tolerate'
	WHEN '9' THEN 'High Risk LAR - Treat'
	WHEN '10' THEN 'High Risk BIL LAR - Terminate'
	WHEN '11' THEN 'High Risk BIL LAR - Tolerate'
	WHEN '12' THEN 'High Risk BIL LAR - Treat'
	WHEN 'R' THEN 'Red'
	WHEN 'A' THEN 'Amber'
	WHEN 'G' THEN 'Green'
  END AS lar_rag,
  tr.`firstnames`,
  tr.`surname`,
  organisations.legal_name AS employer,
  op_trackers.title AS programme,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[1]/Date') AS added_to_lar_date,
  '' AS added_to_lar_date,
  tr_operations.lar_details,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/LastActionDate') AS date_of_last_action,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/NextActionDate') AS revisit_date,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/SalesDeadlineDate') AS sales_deadline,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[Type="N"][last()]/Date') AS lar_closed_date,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Reason') AS lar_reason,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Retention') AS retention_category,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Owner') AS lar_owner,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  CASE TRUE
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) > 24 THEN '24+'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS age_band,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/SecondReason') AS secondary_lar_reason,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Summary') AS lar_summary,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Communication') AS lar_communication,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ContactHistory') AS lar_contact_history,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/NextActionHistory') AS lar_next_action_history,
  #IF(tr_operations.arm_involved = 'Y', 'Yes', IF(tr_operations.arm_involved = 'N', 'No', '') ) AS arm_involved,
  #DATE_FORMAT(tr_operations.arm_revisit, '%d/%m/%Y') AS arm_revisit,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/OpenDate') AS lar_open_date,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ClosedDate') AS lar_closed_date,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Destination') AS lar_destination
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
WHERE
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Type') = "S"
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
        }
        elseif($view_name == 'view_operations_bil_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr.id AS training_id,
  tr.`firstnames`,
  tr.`surname`,
  organisations.legal_name AS employer,
  op_trackers.title AS programme,
  CASE extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type')
  	WHEN "Y" THEN "Yes"
  	WHEN "O" THEN "Ops BIL"
  	WHEN "F" THEN "Formal BIL"
  END AS bil_type,
  #DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  #extractvalue(tr_operations.`lar_details`, '/Notes/Note[1]/Date') AS added_to_lar_date
  #'' AS added_to_lar_date,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Reason') AS lar_reason,
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Reason') AS bil_reason,
  #extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Retention') AS bil_retention,
  #extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Date') AS bil_date, 
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Owner') AS bil_owner,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  tr_operations.lar_details,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/PredictedReturn') AS predicted_return,
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/PredictedLeaver') AS predicted_leaver,
  #extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/NextAction') AS next_action,
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Note') AS bil_summary,
  #IF(tr_operations.arm_involved = 'Y', 'Yes', IF(tr_operations.arm_involved = 'N', 'No', '') ) AS arm_involved,
  #DATE_FORMAT(tr_operations.arm_revisit, '%d/%m/%Y') AS arm_revisit,
  DATE_FORMAT( extractvalue(tr_operations.`bil_details`, '/Notes/Note[1]/DateTime') , '%d/%m/%Y') AS first_entry_date_created,
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/LdolDate') AS bil_ldol_date, 
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/ClosedDate') AS bil_closed_date, 
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/FdolDate') AS bil_fdol_date, 
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/RevisitDate') AS bil_revisit_date, 
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/NextActionSummary') AS next_action_summary
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
WHERE
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type') IN ("Y", "O", "F")
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
;
SQL;
        }
        elseif($view_name == 'view_lras_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr.id AS training_id,
  tr.`firstnames`,
  tr.`surname`,
  DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS planned_end_date,
  organisations.legal_name AS employer,
  student_frameworks.title AS programme,
  CASE extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/Status')
  	WHEN "Y" THEN "Yes"
  	WHEN "N" THEN "No"
  	ELSE ""
  END AS status,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/Summary') AS summary,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/Reason') AS reason,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/Category') AS category,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/LrasDate') AS date,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/RecommendedEndDate') AS recommended_end_date,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/ProReact') AS proactive_reactive,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/SupportProvider') AS support_provider,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/ActionPlanAgreed') AS action_plan,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/ResourcesProvided') AS resources_aftercare,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/CreatedBy') AS created_by,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/DateTime') AS created_at,
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/Owner') AS owner,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.assessor) AS assessor
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
WHERE
  extractvalue(tr_operations.`lras_details`, '/Notes/Note[last()]/Status') = "Y"  
  #AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
  AND tr.status_code NOT IN (2, 3)
;
SQL;
        }
        elseif($view_name == 'view_monthly_leavers_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr.`l03`,
  tr.`firstnames`,
  tr.`surname`,
  DATE_FORMAT(tr.dob, '%d/%m/%Y') AS dob,
  CASE TRUE
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 16 AND 18 THEN '16-18'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) BETWEEN 19 AND 24 THEN '19-24'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) > 24 THEN '24+'
	WHEN ((DATE_FORMAT(tr.`start_date`,'%Y') - DATE_FORMAT(tr.`dob`,'%Y')) - (DATE_FORMAT(tr.`start_date`,'00-%m-%d') < DATE_FORMAT(tr.`dob`,'00-%m-%d'))) < 16 THEN 'Under 16'
  END AS age_band,
#  op_trackers.title AS programme,
  student_frameworks.title AS programme,
  (SELECT DISTINCT DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS induction_date,
  DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS planned_end_date,
  #(SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '1' ORDER BY op_epa.id DESC LIMIT 1) AS 'EPA ready',
  #(SELECT CASE op_epa.task_status  WHEN '1'  THEN 'Ready' WHEN '2' THEN 'Not ready' WHEN '3' THEN 'Requested' WHEN '4' THEN 'Await return from employer' WHEN '5' THEN 'Assessor accepted' WHEN '6' THEN 'Assessor declined'
  #	WHEN '7' THEN 'Assessor passed to IQA' WHEN '8' THEN 'IQA passed' WHEN '9' THEN 'IQA rejected' WHEN '10' THEN 'BCS' WHEN '11' THEN 'C&G' WHEN '12' THEN 'Invited' WHEN '13' THEN 'Booked' WHEN '14' THEN 'Completed'
#	WHEN '15' THEN 'Rejected' WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail' WHEN '20' THEN 'Selected' WHEN '21' THEN 'Not selected'
#	WHEN '22' THEN 'In-progress with assessor' WHEN '23' THEN 'In Progress' WHEN '24' THEN 'To be sent' WHEN '25' THEN 'Not applicable' WHEN '26' THEN 'To be sampled' WHEN '27' THEN 'Awaiting BCS confirmation' END
 # FROM op_epa WHERE tr_id = tr_operations.tr_id AND task = '1' ORDER BY op_epa.id DESC LIMIT 1) AS epa_ready_status,
  (SELECT DATE_FORMAT(op_epa.`task_date`, '%d/%m/%Y') FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '1' ORDER BY op_epa.id DESC LIMIT 1) AS epa_ready_date,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Note') AS leaver_summary,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  (SELECT DISTINCT induction.`brm` FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS BDM,
  organisations.legal_name AS employer,
#  (SELECT DISTINCT IF(induction.levy_payer = 'Y', 'Yes', 'No') FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
#  WHERE inductees.`sunesis_username` = tr.`username` AND induction_programme.`programme_id` = courses_tr.`course_id` ) AS levy_payer,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Date') AS leaver_date,
  EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[last()]/Date') AS lar_date,
  #EXTRACTVALUE(tr_operations.`last_learning_evidence`, '/Evidences/Evidence[last()]/Date') AS last_learning_evidence_date,
  CASE EXTRACTVALUE(tr_operations.`lar_details`, '/Notes/Note[last()-1]/Type')
    	WHEN 'N' THEN 'No'
  	WHEN 'Y' THEN 'LAR'
  	WHEN 'O' THEN 'Ops LAR'
  	WHEN 'S' THEN 'Sales LAR'
	WHEN 'D' THEN 'Direct Leaver'
  	WHEN '' THEN ''
  END AS previous_lar,
  CASE EXTRACTVALUE(tr_operations.`bil_details`, '/Notes/Note[last()]/Type')
    WHEN 'Y' THEN 'Yes'
	WHEN '' THEN 'No'
    WHEN 'N' THEN 'No'
	WHEN "O" THEN "Ops BIL"
  	WHEN "F" THEN "Formal BIL"
  END AS BIL,
 # (SELECT IF(COUNT(*) > 0, 'Yes', 'No') FROM crm_notes WHERE crm_notes.`organisation_id` = organisations.id AND crm_notes.`prevention_alert` = 'Y') AS prevention_alert,
 # CASE organisations.not_linked
 # 	WHEN '1' THEN 'Yes'
 # 	WHEN '0' THEN 'No'
 # 	WHEN '' THEN 'No'
 # END AS stopped_working_with_employer,
 # organisations.not_linked_comments AS reason_not_working,
  CASE induction_fields.inductee_type
	WHEN 'NA' THEN 'New Apprentice'
	WHEN 'WFD' THEN 'WFD'
	WHEN 'P' THEN 'Progression'
	WHEN 'SSU' THEN 'New Apprentice Client Sourced'
	WHEN '3AAA' THEN '3AAA Transfer'
	WHEN 'DXC' THEN 'DXC Transfer'
	WHEN 'ANEW' THEN 'ACCM - New'
	WHEN 'AWFD' THEN 'ACCM - WFD'
	WHEN 'KNEW' THEN 'KEY ACCT - New'
	WHEN 'KWFD' THEN 'KEY ACCT - WFD'
	WHEN 'NSSU' THEN 'NB - STRAIGHT SIGN UP'
	WHEN 'ASSU' THEN 'ACCM - STRAIGHT SIGN UP'
	WHEN 'KSSU' THEN 'KEY ACCT - STRAIGHT SIGN UP'
	WHEN 'LAN' THEN 'LEVY ACCM - New'
	WHEN 'LASP' THEN 'LEVY ACCM - Straight Sign Up'
	WHEN 'LAWS' THEN 'LEVY ACCM - WFD'
	WHEN 'LAPG' THEN 'LEVY ACCM - PROG'
	WHEN 'HOET' THEN 'HOET Transfer'
    	WHEN 'LT' THEN 'Learner Transfer'
  END AS learner_type,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Reason') AS leaver_reason,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Cause') AS leaver_motive,
  #EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Retention') AS retention_category,
  #'' AS on_lar_at_leaving,
  '' AS days_on_programme,
  DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS actual_end_date,
  #EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Owner') AS owner,
  tr.home_email AS learner_email,
  (SELECT contact_email FROM organisation_contact WHERE contact_id = tr.crm_contact_id) AS line_manager_email,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/LeaverDecision') AS leaver_decision_made,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/PositiveOutcome') AS leaver_positive_outcome,
  REPLACE(induction_fields.salary, '&pound;', '') AS salary,
  extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Summary') AS lar_summary,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/DirectLeaver') AS direct_leaver,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/LdolDate') AS ldol_date,
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/LdolEvidence') AS ldol_evidence,
  CASE EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Owner')
    WHEN '23461' THEN 'Hannah Gibson'
    WHEN '27362' THEN 'Matt Ward'
    WHEN '28934' THEN 'Bailey Myers'
    ELSE ''
  END AS owner,
  tr.id AS training_id
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
  LEFT JOIN (
  SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, inductee_type, inductees.`salary`
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
WHERE
  EXTRACTVALUE(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "Y"
;
SQL;
        }
        elseif($view_name == 'view_prevention_alert_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  #(SELECT GROUP) AS employer_main_contacts,
  tr.`firstnames`,
  tr.`surname`,
  organisations.legal_name AS employer,
  op_trackers.title AS programme,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[1]/Date') AS added_to_leaver_date,
  extractvalue(tr_operations.`leaver_details`, '/Notes/Note[1]/Note') AS leaver_note,
  CASE extractvalue(tr_operations.`lar_details`, '/Notes/Note[1]/Type')
    WHEN 'N' THEN 'No'
  	WHEN 'Y' THEN 'LAR'
  	WHEN 'O' THEN 'Ops LAR'
  	WHEN 'S' THEN 'Sales LAR'
	WHEN 'D' THEN 'Direct Leaver'
  	WHEN '' THEN ''
  END AS previous_lar,
  extractvalue(tr_operations.`last_learning_evidence`, '/Evidences/Evidence[last()]/Date') AS last_learning_evidence_date,
  '' AS prevention_alert,
  CASE organisations.not_linked
  	WHEN '1' THEN 'Yes'
  	WHEN '0' THEN 'No'
  	WHEN '' THEN 'No'
  END AS stopped_working_with_employer,
  organisations.not_linked_comments AS reason_not_working
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN organisations ON tr.`employer_id` = organisations.id
  LEFT JOIN crm_notes ON organisations.id = crm_notes.`organisation_id`
WHERE
  crm_notes.`prevention_alert` = 'Y'
;
SQL;
        }
        elseif($view_name == 'view_ach_forecast_in_prog')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr.l03 AS learner_reference,
  DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS end_date,
  op_trackers.`id` AS programme_id,
  tr.`id` AS tr_id,
  op_trackers.`title` AS programme,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  tr.`firstnames`,
  tr.`surname`,
  '' AS technical_course_progress,
  '' AS test_progress,
  #'' AS ap_progress,
  #'' AS iqa_to_sign_off,
  (SELECT IF(op_epa.`task_applicable` = 'Y', 'Yes', (IF(op_epa.`task_applicable` = 'N', 'No', ''))) FROM op_epa WHERE tr_id = tr_operations.`tr_id` AND task = '4' ORDER BY op_epa.id DESC LIMIT 1) AS iqa_to_sign_off,
  #op_epa_extra.`predicted_gateway_month` AS predicted_gateway_ready_month,
  IF(
    student_frameworks.`title` LIKE "%L3%" 
    OR student_frameworks.`title` LIKE "%Level 3%",
    DATE_FORMAT(DATE_ADD(tr.start_date, INTERVAL 10 MONTH), '%M %Y'),
    DATE_FORMAT(DATE_ADD(tr.start_date, INTERVAL 15 MONTH), '%M %Y')
  ) AS predicted_gateway_ready_month,
  op_epa_extra.`predicted_epa_month`,
  DATE_FORMAT(tr.`target_date`, '%M %Y') AS target_month,
  frameworks.short_name,
  (SELECT COUNT(*) FROM op_course_percentage WHERE programme = frameworks.short_name) AS percentage_set,
  (SELECT COUNT(*) FROM op_test_percentage WHERE programme = frameworks.short_name) AS test_percentage_set
FROM
  tr_operations
  LEFT JOIN tr ON tr_operations.tr_id = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN frameworks ON student_frameworks.id = frameworks.id
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN op_epa_extra ON tr_operations.`tr_id` = op_epa_extra.`tr_id`
WHERE
  tr.status_code = '1'
;
SQL;

        }
        elseif($view_name == 'view_interviews')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr.`firstnames`,
  tr.`surname`,
  DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y') AS interview_date,
  CASE
   WHEN '12' THEN 'Invited'
   WHEN '13' THEN 'Booked'
   WHEN '14' THEN 'Completed'
  END AS interview_status,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor
FROM
  op_epa INNER JOIN tr ON op_epa.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
WHERE
  op_epa.`task` = 7 AND task_status IN (12, 13, 14)
;
SQL;
        }
        elseif($view_name == 'view_ach_forecast_gateway_ready')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr.`id` AS tr_id,
  (SELECT courses_tr.course_id FROM courses_tr WHERE courses_tr.tr_id = tr.id) AS course_id,
  op_trackers.`id` AS programme_id,
  DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS end_date,
  (SELECT DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y') FROM op_epa WHERE op_epa.`tr_id` = tr.`id` AND op_epa.`task` = '12' ORDER BY id DESC LIMIT 1) AS gateway_forecast_actual_date,
  op_trackers.`title` AS programme,
  (SELECT IF(op_epa.`task_type` = 1, 'On Programme', IF(op_epa.`task_type` = 2, 'Re-Sit', '')) AS task_type FROM op_epa WHERE tr_id = tr.id ORDER BY task_date DESC, id DESC LIMIT 1) AS task_type,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  (SELECT CONCAT(supervisors.firstnames, ' ', supervisors.surname) FROM users AS supervisors INNER JOIN users AS assessors ON supervisors.username = assessors.supervisor WHERE assessors.id = tr.assessor ) AS line_manager,
  tr.`firstnames`,
  tr.`surname`,
  '' AS evidence_progress,
  '' AS technical_course_progress,
  '' AS test_progress,
  '' AS iqa_progress,
  '' AS ap_progress,
  (SELECT COUNT(*) FROM lookup_assessment_plan_log_mode WHERE framework_id = frameworks.id) AS total_no_of_plans,

  (SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id) 
		WHERE sub.completion_date IS NOT NULL AND assessment_plan_log.tr_id = tr.id) AS total_complete,

  (SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id) 
		WHERE sub.completion_date IS NULL AND COALESCE(iqa_status,0)!=2 AND sent_iqa_date IS NOT NULL AND assessment_plan_log.tr_id = tr.id) AS awaiting_iqa,

  (SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id) 
		WHERE sub.completion_date IS NULL AND COALESCE(iqa_status,0)!=2 AND sent_iqa_date IS NULL AND submission_date IS NOT NULL AND assessment_plan_log.tr_id = tr.id) AS awaiting_marking,

  (SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
		WHERE sub.completion_date IS NULL AND COALESCE(iqa_status,0)!=2 AND sent_iqa_date IS NULL AND submission_date IS NULL AND due_date < CURDATE() AND assessment_plan_log.tr_id = tr.id) AS overdue,

  (SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id) 
		WHERE sub.completion_date IS NULL AND COALESCE(iqa_status,0)!=2 AND sent_iqa_date IS NULL AND submission_date IS NULL 
		AND due_date >= CURDATE() AND set_date IS NOT NULL 			
		AND assessment_plan_log.tr_id = tr.id) AS in_progress,
		
  ((SELECT COUNT(*) FROM lookup_assessment_plan_log_mode WHERE framework_id = frameworks.id)-(  (SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id) 
		WHERE sub.set_date IS NOT NULL AND assessment_plan_log.tr_id = tr.id))) AS to_be_set,
  frameworks.short_name,
  '' AS iqa_status,
  '' AS iqa_actual_date,
  '' AS epa_ready,
  '' AS epa_ready_actual_date,
  '' AS employer_reference,
  '' AS employer_reference_actual_date,
  '' AS summative_portfolio,
  '' AS summative_portfolio_actual_date,
  '' AS gateway_dec_complete,
  '' AS gateway_dec_actual_date,
  '' AS passed_to_ss,
  '' AS passed_to_ss_actual_date,
  '' AS passed_to_ss_status,
  '' AS synoptic_project,
  '' AS synoptic_project_actual_date,
  '' AS epa_result_status,
  '' AS epa_result_actual_date,
  '' AS interview,
  '' AS interview_actual_date,
  '' AS interview_status,
  '' AS deadline_date,
  '' AS eol_statement,
  '' AS eol_statement_actual_date,
  '' AS epa_forecast,
  '' AS potential_achievement_month,
  '' AS epa_ready_comments,
  '' AS epa_workshop_actual_date,
  '' AS epa_workshop_status,
  '' AS project_actual_date,
  '' AS project_comments,
  (SELECT COUNT(*) FROM op_course_percentage WHERE programme = frameworks.short_name) AS percentage_set,
  (SELECT COUNT(*) FROM op_test_percentage WHERE programme = frameworks.short_name) AS test_percentage_set,
  #(SELECT manager_comments.`comment` FROM manager_comments WHERE manager_comments.`tr_id` = tr.id AND manager_comments.`comment_type` = 'ER' ORDER BY manager_comments.`id` DESC LIMIT 1) AS employer_ref_comments,
  #(SELECT manager_comments.`comment` FROM manager_comments WHERE manager_comments.`tr_id` = tr.id AND manager_comments.`comment_type` = 'LP' ORDER BY manager_comments.`id` DESC LIMIT 1) AS learner_progress_comments,
  '' AS employer_ref_comments,
  '' AS learner_progress_comments,
  CASE tr_operations.learner_status
    WHEN 'A' THEN 'Achieved'
    WHEN 'BIL' THEN 'BIL'
    WHEN 'OBIL' THEN 'Ops BIL'
    WHEN 'FBIL' THEN 'Formal BIL'
    WHEN 'LAR' THEN 'LAR'
    WHEN 'OP' THEN 'On Programme'
    WHEN 'PA' THEN 'PEED - Assessment'
    WHEN 'PC' THEN 'PEED - Coordinator'
    WHEN 'PLM' THEN 'PEED - Learning Mentor'
    WHEN 'GR' THEN 'Gateway Ready'
    WHEN 'F' THEN 'Fail'
    WHEN 'LRA' THEN 'LRAS (Learners requiring additional support)'
    WHEN 'PL' THEN 'PEED/LAR'
    WHEN 'LB' THEN 'LAR & BIL'
    WHEN 'PNDL' THEN 'Pending Leaver'	
  END AS learner_status,
#  (SELECT DATE_FORMAT(op_epa.task_peed_forecast_date, '%d/%m/%Y') AS peed_forecast_date FROM op_epa WHERE op_epa.task_type = '3' AND op_epa.tr_id = tr.id) AS peed_forecast
  CASE EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/Status')
    WHEN 'Y' THEN 'PEED'
    WHEN 'PP' THEN 'Potential PEED'
    WHEN 'N' THEN 'No'
  END AS peed_forecast_status,
  EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/Date') AS lsl_date,
  EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/Comments') AS peed_comments,
  CASE EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/Reason')
    WHEN '28' THEN 'Business Performance'
    WHEN '29' THEN 'Business Environment'
    WHEN '2' THEN 'Incorrect job role'
    WHEN '9' THEN 'Apprentice Performance'
    WHEN '5' THEN 'Health & Wellbeing'
    WHEN '1' THEN 'New Job'
    WHEN '16' THEN 'Capability'
    WHEN '35' THEN 'Dissatisfied with Baltic'
    WHEN '36' THEN 'Job Role Change'
  END AS peed_reason,
  CASE EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/Cause')
    WHEN '1' THEN 'Covid - Lack of Evidence'
    WHEN '2' THEN 'Covid - Performance'
    WHEN '3' THEN 'DXC'
    WHEN '4' THEN 'Furlough'
    WHEN '5' THEN 'Health'
    WHEN '6' THEN 'High Rework - Missed Deadlines'
    WHEN '7' THEN 'Job Role Concerns'
    WHEN '8' THEN 'Lack of Commitment - Apprentice - Capability'
    WHEN '9' THEN 'Lack of Commitment - Apprentice - Missed Deadlines'
    WHEN '10' THEN 'Lack of Evidence'
    WHEN '11' THEN 'Lack of Time in Workplace'
    WHEN '12' THEN 'Learning Mentor Knowledge'
    WHEN '13' THEN 'Performance - Concerns with Apprentice'
    WHEN '14' THEN 'Redundancy'
  END AS peed_cause,
  EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/Revisit') AS revisit_date,
  EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/Owner') AS peed_owner,
  EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/ForecastDate') AS peed_forecast_date,
  CASE EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/Lsl')
    WHEN 'N' THEN 'No'
    WHEN 'Y' THEN 'Yes'
  END AS lsl_involvement,
  CASE EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/LslStatus')
    WHEN 'O' THEN 'Owning'
    WHEN 'I' THEN 'Involvement'
    WHEN 'IL' THEN 'Involvement - LAR'
    WHEN 'IB' THEN 'Involvement - BIL'
    WHEN 'AP' THEN 'Action Plan'
    WHEN 'NA' THEN 'No Action'
  END AS lsl_involvement_status,
  '' AS epao,
  (SELECT legal_name FROM organisations WHERE organisations.id = tr.employer_id) AS employer,
  DATE_FORMAT(tr_operations.pdp_month9_date, '%d/%m/%Y') AS pdp_month9_date,
  tr_operations.pdp_month9_completed,
  DATE_FORMAT(tr_operations.pdp_month12_date, '%d/%m/%Y') AS pdp_month12_date,
  tr_operations.pdp_month12_completed,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr_operations.pdp_coach_sign) AS pdp_coach_sign,
  #DATE_FORMAT(tr_operations.mock_interview_planned_date, '%d/%m/%Y') AS mock_interview_planned_date,
  #DATE_FORMAT(tr_operations.mock_interview_actual_date, '%d/%m/%Y') AS mock_interview_actual_date,
  #tr_operations.mock_interview_completed,
  EXTRACTVALUE(tr_operations.`epa_mock_interview`, '/Mock/Set[Iteration=1]/PlannedDate') AS mock_interview_planned_date,
  EXTRACTVALUE(tr_operations.`epa_mock_interview`, '/Mock/Set[Iteration=1]/ActualDate') AS mock_interview_actual_date,
  EXTRACTVALUE(tr_operations.`epa_mock_interview`, '/Mock/Set[Iteration=1]/Completed') AS mock_interview_completed,
  EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/DateWeek1') AS CheckInDateWeek1,
  EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/DateWeek2') AS CheckInDateWeek2,
  EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/DateWeek3') AS CheckInDateWeek3,
  EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/DateWeek4') AS CheckInDateWeek4,
  EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/CheckInDoneWeek1') AS CheckInDoneWeek1,
  EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/CheckInDoneWeek2') AS CheckInDoneWeek2,
  EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/CheckInDoneWeek3') AS CheckInDoneWeek3,
  EXTRACTVALUE(tr_operations.`project_checkin`, '/Notes/Note[last()]/CheckInDoneWeek4') AS CheckInDoneWeek4,
  tr_operations.project_plan,
  (SELECT apprenticeship_title FROM courses WHERE courses.framework_id = frameworks.id LIMIT 1) AS apprenticeship_title

FROM
  tr
  LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN frameworks ON student_frameworks.id = frameworks.id
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`

;
SQL;

        }
        elseif($view_name == 'view_ach_forecast_framework')
        {
            $sql = <<<SQL
SELECT DISTINCT
  DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS end_date,
  student_frameworks.`title` AS programme,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  tr.`firstnames`,
  tr.`surname`,
  IF(tr.`status_code` = '2', '<i class="fa fa-check"></i>', '<i class="fa fa-close"></i>') AS complete,
  DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS ach_date,
  DATE_FORMAT(tr.`target_date`, '%M %Y') AS target_month,
  IF(
    tr.`closure_date` IS NULL,
    'C',
    (
      IF(
        ((tr.`closure_date` <= tr.`target_date`) OR (tr.`closure_date` BETWEEN tr.`target_date` AND DATE_ADD(tr.`target_date`, INTERVAL 90 DAY))),
        'T',
        (
          IF(
            tr.`closure_date` > DATE_ADD(tr.`target_date`, INTERVAL 90 DAY),
            'A',
            0
          )
        )
      )
    )
  ) AS ach_type

FROM
  tr_operations
  LEFT JOIN tr ON tr_operations.tr_id = tr.id
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`
  LEFT JOIN op_epa_extra ON tr_operations.`tr_id` = op_epa_extra.`tr_id`
;
SQL;

        }
        elseif($view_name == 'view_learners_additional_info_report')
        {
            $sql = <<<SQL
SELECT DISTINCT
  tr.`l03`,
  tr.`firstnames`,
  tr.`surname`,
  DATE_FORMAT(tr.dob, '%d/%m/%Y') AS dob,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS planned_end_date,
  student_frameworks.`title` AS programme,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  tr_operations.`additional_info`

FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
  LEFT JOIN student_frameworks ON tr.id = student_frameworks.`tr_id`
  LEFT JOIN op_tracker_frameworks ON student_frameworks.`id` = op_tracker_frameworks.`framework_id`
  LEFT JOIN op_trackers ON op_tracker_frameworks.`tracker_id` = op_trackers.`id`

WHERE
  tr_operations.`additional_info` IS NOT NULL
;
SQL;

        }
        $view = new VoltView($view_name, $sql);

        $f = new VoltTextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
        $f->setDescriptionFormat("First Name: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
        $view->addFilter($f);

        if($view_name == 'view_op_mock_status_report')
        {
            $options = array(
                0=>array(0, 'Show All', null, null),
                1=>array(1, 'Mock Outstanding', null, 'WHERE mock_table.mock_code="MO"'),
                2=>array(2, 'Mock Issued', null, 'WHERE mock_table.mock_code="MI"'),
                3=>array(3, 'Mock Passed', null, 'WHERE mock_table.mock_code="MP"')
            );
            $f = new VoltDropDownViewFilter('filter_mock_code', $options, 0, false);
            $f->setDescriptionFormat("Mock Code: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE op_trackers.id=',op_trackers.id) FROM op_trackers ORDER BY title";
            $f = new VoltDropDownViewFilter('filter_tracker', $options, null, true);
            $f->setDescriptionFormat("Tracker: %s");
            $view->addFilter($f);

        }
        if($view_name == 'view_id_report')
        {
            $options = array(
                0=>array(0, 'Show All', null, null),
                1=>array(1, 'Received prior induction', null, 'WHERE tr_operations.learner_id="RPI"'),
                2=>array(2, 'Received after induction', null, 'WHERE tr_operations.learner_id="RAI"'),
                3=>array(3, 'Received following induction', null, 'WHERE tr_operations.learner_id="RFI"'),
                4=>array(4, 'Outstanding', null, 'WHERE tr_operations.learner_id="O"'),
                5=>array(5, 'Sign posted', null, 'WHERE tr_operations.learner_id="SP"'),
                6=>array(6, 'Not Required', null, 'WHERE tr_operations.learner_id="NR"'),
                7=>array(7, 'Passport', null, 'WHERE tr_operations.learner_id="P"'),
                8=>array(8, 'Driving License', null, 'WHERE tr_operations.learner_id="DL"'),
                9=>array(9, 'Provisional Driving License', null, 'WHERE tr_operations.learner_id="PDL"'),
                10=>array(10, 'Proof of Age Card', null, 'WHERE tr_operations.learner_id="PAC"'),
                11=>array(11, 'Birth Certificate', null, 'WHERE tr_operations.learner_id="BC"'),
                12=>array(12, 'Residency', null, 'WHERE tr_operations.learner_id="R"'),
                13=>array(13, 'Passed to Coach', null, 'WHERE tr_operations.learner_id="PTC"'),
            );
            $f = new VoltDropDownViewFilter('filter_learner_id', $options, 0, false);
            $f->setDescriptionFormat("Learner ID: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE op_trackers.id=',op_trackers.id) FROM op_trackers ORDER BY title";
            $f = new VoltDropDownViewFilter('filter_tracker', $options, null, true);
            $f->setDescriptionFormat("Tracker: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ' , users.surname, ' - ', users.username), LEFT(users.firstnames, 1), CONCAT('WHERE tr.assessor=',char(39),users.id,char(39)) FROM users INNER JOIN tr ON users.id = tr.assessor ORDER BY users.firstnames";
            $f = new VoltDropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

	    $options = array(
                0=>array('SHOW_ALL', 'Show all', null, 'WHERE tr.start_date > DATE_ADD(CURDATE(), INTERVAL - 8 YEAR)'),
                1=>array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
                2=>array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
                3=>array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
                4=>array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
                5=>array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
                6=>array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7=>array('7', '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
            $f = new VoltCheckboxViewFilter('filter_tr_status_multi', $options, array('1'));
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

        }
	if(in_array($view_name, ["view_reschedule_report", "previous_on_lar"]))
        {
            $options = array(
                0=>array('SHOW_ALL', 'Show all', null, 'WHERE tr.start_date > DATE_ADD(CURDATE(), INTERVAL - 8 YEAR)'),
                1=>array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
                2=>array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
                3=>array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
                4=>array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
                5=>array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
                6=>array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7=>array('7', '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
            $f = new VoltCheckboxViewFilter('filter_tr_status_multi', $options, array('1'));
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);
        }
        if($view_name == 'view_prevention_alert_report')
        {
            $options = "SELECT DISTINCT organisations.id, organisations.legal_name, LEFT(organisations.legal_name, 1), CONCAT('WHERE tr.employer_id=',char(39),organisations.id,char(39)) FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id ORDER BY legal_name";
            $f = new VoltDropDownViewFilter('filter_employer', $options, null, true);
            $f->setDescriptionFormat("Employer: %s");
            $view->addFilter($f);
        }
        if($view_name == 'view_reschedule_report')
        {
            $options = "SELECT DISTINCT organisations.id, organisations.legal_name, LEFT(organisations.legal_name, 1), CONCAT('WHERE tr.employer_id=',char(39),organisations.id,char(39)) FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id INNER JOIN session_cancellations ON tr.id = session_cancellations.tr_id ORDER BY legal_name";
            $f = new VoltDropDownViewFilter('filter_employer', $options, null, true);
            $f->setDescriptionFormat("Employer: %s");
            $view->addFilter($f);

            //$options = "SELECT DISTINCT unit_ref, unit_ref, NULL, CONCAT('WHERE sessions.unit_ref=',CHAR(39),unit_ref,CHAR(39)) FROM sessions INNER JOIN session_cancellations ON session_id = sessions.id ORDER BY unit_ref";
            $options = "SELECT DISTINCT unit_ref, unit_ref, NULL, CONCAT('WHERE FIND_IN_SET(\'', unit_ref, '\', sessions.unit_ref)') FROM op_tracker_units ORDER BY unit_ref";
            $f = new VoltDropDownViewFilter('filter_unit_ref', $options, null, true);
            $f->setDescriptionFormat("Unit Ref: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ', users.surname), NULL, CONCAT('WHERE session_cancellations.cancelled_by=',CHAR(39),users.id,CHAR(39)) FROM users INNER JOIN session_cancellations ON users.id = cancelled_by ORDER BY firstnames";
            $f = new VoltDropDownViewFilter('filter_cancelled_by', $options, null, true);
            $f->setDescriptionFormat("Cancelled By: %s");
            $view->addFilter($f);

            $format = "WHERE cancellation_date >= '%s'";
            $f = new VoltDateViewFilter('filter_from_cancellation_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE cancellation_date <= '%s'";
            $f = new VoltDateViewFilter('filter_to_cancellation_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

	    $options = array(
                0=>array('1', 'LAR', null, 'WHERE session_cancellations.category = "lar"'),
                1=>array('2', 'BIL', null, 'WHERE session_cancellations.category = "bil"'),
                2=>array('3', 'Leaver', null, 'WHERE session_cancellations.category = "lvr"'),
                3=>array('4', 'Employer cannot commit', null, 'WHERE session_cancellations.category = "ecc"'),
                4=>array('5', 'Learner off sick', null, 'WHERE session_cancellations.category = "los"'),
                5=>array('6', 'Learner on holiday', null, 'WHERE session_cancellations.category = "loh"'),
                6=>array('7', 'Other', null, 'WHERE session_cancellations.category = "oth"'),
                7=>array('8', 'Baltic re-schedule', null, 'WHERE session_cancellations.category = "brs"'),
                8=>array('9', 'Co-ordinator error', null, 'WHERE session_cancellations.category = "err"'),
                9=>array('10', 'Learner cannot commit', null, 'WHERE session_cancellations.category = "lcc"'),
                10=>array('11', 'Exempt', null, 'WHERE session_cancellations.category = "exm"'),
                11=>array('12', 'Technical', null, 'WHERE session_cancellations.category = "tec"'),
                12=>array('13', 'ID', null, 'WHERE session_cancellations.category = "id"'),
                13=>array('14', 'Address', null, 'WHERE session_cancellations.category = "add"'),
            );
            $f = new VoltDropDownViewFilter('filter_cancellation_category', $options);
            $f->setDescriptionFormat("Cancellation Category: %s");
            $view->addFilter($f);

            $options = array(
                0=>array('1', 'Schedule cancellation', null, 'WHERE session_cancellations.cancellation_type = "1"'),
                1=>array('2', 'Prior to Reminders', null, 'WHERE session_cancellations.cancellation_type = "2"'),
                2=>array('3', 'Cancellation within 4 weeks notice', null, 'WHERE session_cancellations.cancellation_type = "3"'),
                3=>array('4', 'Cancellation with 14+ days notice', null, 'WHERE session_cancellations.cancellation_type = "4"'),
                4=>array('5', 'Cancellation with 7+ days notice', null, 'WHERE session_cancellations.cancellation_type = "5"'),
                5=>array('6', 'Cancelled within 7 days of course start date', null, 'WHERE session_cancellations.cancellation_type = "6"'),
                6=>array('7', 'Cancelled on the day of course', null, 'WHERE session_cancellations.cancellation_type = "7"'),
                7=>array('8', 'Other', null, 'WHERE session_cancellations.cancellation_type = "8"'),
            );
            $f = new VoltDropDownViewFilter('filter_cancellation_type', $options);
            $f->setDescriptionFormat("Cancellation Type: %s");
            $view->addFilter($f);

            $f = new VoltTextboxViewFilter('filter_training_id', "WHERE tr.id LIKE '%s%%'", null);
            $f->setDescriptionFormat("Training ID: %s");
            $view->addFilter($f);

	    $f = new VoltTextboxViewFilter('filter_session_id', "WHERE sessions.id LIKE '%s%%'", null);
            $f->setDescriptionFormat("Session ID: %s");
            $view->addFilter($f);

        }
        elseif($view_name == 'view_achievements_report')
        {
            $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE op_trackers.id=',op_trackers.id) FROM op_trackers ORDER BY title";
            $f = new VoltDropDownViewFilter('filter_tracker', $options, null, true);
            $f->setDescriptionFormat("Tracker: %s");
            $view->addFilter($f);

            $format = "WHERE tr.target_date >= '%s'";
            $f = new VoltDateViewFilter('filter_from_planned_end_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE tr.target_date <= '%s'";
            $f = new VoltDateViewFilter('filter_to_planned_end_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`completed_date` >= '%s'";
            $f = new VoltDateViewFilter('filter_from_completed_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`completed_date` <= '%s'";
            $f = new VoltDateViewFilter('filter_to_completed_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);
        }
        elseif($view_name == 'view_operations_lar_report' || $view_name == 'view_sales_lar_report')
        {
            //$format = "WHERE STR_TO_DATE(extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') >= '%s'";
            $format = "WHERE tr.start_date >= '%s'";
            $f = new VoltDateViewFilter('filter_from_tr_start', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            //$format = "WHERE STR_TO_DATE(extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') <= '%s'";
            $format = "WHERE tr.start_date <= '%s'";
            $f = new VoltDateViewFilter('filter_to_tr_start', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);
	    if($view_name == 'view_operations_lar_report')
            {
                #lar_type
                $options = array(
                    0=>array('0', 'Show Both (Operations + Direct Leaver', null, null),
                    1=>array('1', 'Operations', null, 'HAVING lar_type = "O"'),
                    2=>array('2', 'Direct Leaver ', null, 'HAVING lar_type = "D"'));
                $f = new VoltDropDownViewFilter('filter_op_direct_lar', $options, 0, false);
                $f->setDescriptionFormat("LAR Type: %s");
                $view->addFilter($f);
            }
//             $options = <<<SQL
// SELECT DISTINCT
//   users.id,
//   CONCAT(users.firstnames, ' ', users.surname, ' - ', users.username ), LEFT(users.firstnames, 1),
//   CONCAT(
//     "WHERE extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ActivelyInvolved') LIKE ",
//     CHAR(39),
//     '%',
//     users.id,
//     '%',
//     CHAR(39)
//   )
// FROM
//   users INNER JOIN lookup_actively_involved_users ON users.id = lookup_actively_involved_users.user_id ORDER BY users.firstnames;
// SQL;
            $options = [
                0 => ['0', 'ARM', null, "WHERE extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ActivelyInvolved') LIKE '%ARM%' "],
                1 => ['1', 'Programme Coach', null, "WHERE extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ActivelyInvolved') LIKE '%Programme Coach%' "],
                2 => ['2', 'Programme Coord', null, "WHERE extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ActivelyInvolved') LIKE '%Programme Coord%' "],
                3 => ['3', 'Safeguarding', null, "WHERE extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ActivelyInvolved') LIKE '%Safeguarding%' "],
                4 => ['4', 'Apprentice Success', null, "WHERE extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/ActivelyInvolved') LIKE '%Apprentice Success%' "],
            ];
            $f = new VoltDropDownViewFilter('filter_actively_involved_users', $options, null, true);
            $f->setDescriptionFormat("Actively Involved Users: %s");
            $view->addFilter($f);		
        }
        elseif($view_name == 'view_operations_bil_report')
        {
            $options = "SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ' , users.surname, ' - ', users.username), LEFT(users.firstnames, 1), CONCAT('WHERE tr.assessor=',char(39),users.id,char(39)) FROM users INNER JOIN tr ON users.id = tr.assessor ORDER BY users.firstnames";
            $f = new VoltDropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            $options = <<<SQL
SELECT DISTINCT users.username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE tr.assessor IN (SELECT users.id FROM users WHERE users.supervisor=',CHAR(39), users.`username`, CHAR(39), ')')
FROM users
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
WHERE
users.username IN (SELECT supervisor FROM users WHERE supervisor IS NOT NULL)
ORDER BY firstnames, surname;
SQL;
            $f = new VoltDropDownViewFilter('filter_manager', $options, null, true);
            $f->setDescriptionFormat("Manager: %s");
            $view->addFilter($f);
            /*
               $format = "WHERE STR_TO_DATE(extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') >= '%s'";
               $f = new VoltDateViewFilter('filter_from_tr_start', $format, '');
               $f->setDescriptionFormat("From: %s");
               $view->addFilter($f);

               $format = "WHERE STR_TO_DATE(extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') <= '%s'";
               $f = new VoltDateViewFilter('filter_to_tr_start', $format, '');
               $f->setDescriptionFormat("To: %s");
               $view->addFilter($f);
   */
        }
        elseif($view_name == 'view_monthly_leavers_report')
        {
            $format = "WHERE STR_TO_DATE(extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') >= '%s'";
            $f = new VoltDateViewFilter('filter_from_leaver_start', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE STR_TO_DATE(extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Date'), '%d/%m/%Y') <= '%s'";
            $f = new VoltDateViewFilter('filter_to_leaver_start', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);
        }
        elseif($view_name == 'view_interviews')
        {
            $options = "SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ' , users.surname, ' - ', users.username), LEFT(users.firstnames, 1), CONCAT('WHERE tr.assessor=',char(39),users.id,char(39)) FROM users INNER JOIN tr ON users.id = tr.assessor ORDER BY users.firstnames";
            $f = new VoltDropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            $options = <<<SQL
SELECT DISTINCT users.username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE tr.assessor IN (SELECT users.id FROM users WHERE users.supervisor=',CHAR(39), users.`username`, CHAR(39), ')')
FROM users
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
WHERE
users.username IN (SELECT supervisor FROM users WHERE supervisor IS NOT NULL)
ORDER BY firstnames, surname;
SQL;
            $f = new VoltDropDownViewFilter('filter_manager', $options, null, true);
            $f->setDescriptionFormat("Manager: %s");
            $view->addFilter($f);

            $format = "WHERE op_epa.task_actual_date >= '%s'";
            $f = new VoltDateViewFilter('filter_from_tr_start', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE op_epa.task_actual_date <= '%s'";
            $f = new VoltDateViewFilter('filter_to_tr_start', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);
        }
        elseif($view_name == 'view_additional_support_report')
        {
	    $format = "WHERE tr.start_date >= '%s'";
            $f = new VoltDateViewFilter('filter_from_tr_start', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE tr.start_date <= '%s'";
            $f = new VoltDateViewFilter('filter_to_tr_start', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE op_trackers.id=',op_trackers.id) FROM op_trackers ORDER BY title";
            $f = new VoltDropDownViewFilter('filter_tracker', $options, null, true);
            $f->setDescriptionFormat("Tracker: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ' , users.surname, ' - ', users.username), LEFT(users.firstnames, 1), CONCAT('WHERE tr.assessor=',char(39),users.id,char(39)) FROM users INNER JOIN tr ON users.id = tr.assessor ORDER BY users.firstnames";
            $f = new VoltDropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            $options = array(
                0=>array('SHOW_ALL', 'Show all', null, 'WHERE tr.start_date > DATE_ADD(CURDATE(), INTERVAL - 8 YEAR)'),
                1=>array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
                2=>array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
                3=>array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
                4=>array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
                5=>array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
                6=>array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7=>array('7', '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
            $f = new VoltCheckboxViewFilter('filter_tr_status_multi', $options, array('1'));
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

	    $options = array(
                0=>array('A', 'Achieved ', null, 'WHERE tr_operations.learner_status="A"'),
                1=>array('OBIL', 'Ops BIL ', null, 'WHERE tr_operations.learner_status="OBIL"'),
                2=>array('FBIL', 'Formal BIL ', null, 'WHERE tr_operations.learner_status="FBIL"'),
                3=>array('LAR', 'LAR ', null, 'WHERE tr_operations.learner_status="LAR"'),
                4=>array('OP', 'On Programme ', null, 'WHERE tr_operations.learner_status="OP"'),
                5=>array('PA', 'PEED - Assessment ', null, 'WHERE tr_operations.learner_status="PA"'),
                6=>array('PC', 'PEED - Coordinator ', null, 'WHERE tr_operations.learner_status="PC"'),
                7=>array('PLM', 'PEED - Learning Mentor ', null, 'WHERE tr_operations.learner_status="PLM"'),
                8=>array('GR', 'Gateway Ready ', null, 'WHERE tr_operations.learner_status="GR"'),
                9=>array('F', 'Fail ', null, 'WHERE tr_operations.learner_status="F"'),
                10=>array('LRA', 'LRAS (Learners requirding additional support ', null, 'WHERE tr_operations.learner_status="LRA"'),
            );
            $f = new VoltDropDownViewFilter('filter_tr_operations_learner_status', $options, '', true);
            $f->setDescriptionFormat("Learner Status: %s");
            $view->addFilter($f);
        }
        elseif($view_name == 'view_ach_forecast_in_prog' || $view_name == 'view_ach_forecast_gateway_ready' || $view_name == 'view_ach_forecast_framework')
        {
            if($view_name == 'view_ach_forecast_gateway_ready')
            {
                $options = array(
                    0=>array('SHOW_ALL', 'Show all', null, 'WHERE tr.start_date > DATE_ADD(CURDATE(), INTERVAL - 8 YEAR)'),
                    1=>array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
                    2=>array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
                    3=>array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
                    4=>array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
                    5=>array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
                    6=>array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                    7=>array('7', '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
                $f = new VoltCheckboxViewFilter('filter_tr_status_multi', $options, array('1'));
                $f->setDescriptionFormat("Show: %s");
                $view->addFilter($f);

		$options = array(
                     0=>array('0', 'Show all', null, null),
                    1=>array('1', 'PEED', null, 'HAVING peed_forecast_status = "PEED"'),
                    2=>array('2', 'Potential PEED ', null, 'HAVING peed_forecast_status = "Potential PEED"'),
                    3=>array('3', 'No', null, 'HAVING peed_forecast_status = "No"'));
                $f = new VoltDropDownViewFilter('filter_peed_status', $options, 0, false);
                $f->setDescriptionFormat("Show: %s");
                $view->addFilter($f);

                $f = new VoltTextboxViewFilter('filter_tr_ids', "WHERE tr.id in (%s)", null);
                $f->setDescriptionFormat("TR IDs: %s");
                $view->addFilter($f);

                $format = "WHERE tr.`id` IN ( SELECT DISTINCT tr_id FROM op_epa WHERE op_epa.`task` = 1 AND op_epa.`task_actual_date` >= '%s')";
                $f = new VoltDateViewFilter('filter_from_epa_actual_date', $format, '');
                $f->setDescriptionFormat("From: %s");
                $view->addFilter($f);

                $format = "WHERE tr.`id` IN ( SELECT DISTINCT tr_id FROM op_epa WHERE op_epa.`task` = 1 AND op_epa.`task_actual_date` <= '%s')";
                $f = new VoltDateViewFilter('filter_to_epa_actual_date', $format, '');
                $f->setDescriptionFormat("To: %s");
                $view->addFilter($f);

		$format = "WHERE tr.`id` IN ( SELECT DISTINCT tr_id FROM op_epa WHERE op_epa.`task` = 5 AND op_epa.`task_actual_date` >= '%s')";
                $f = new VoltDateViewFilter('filter_from_pss_actual_date', $format, '');
                $f->setDescriptionFormat("From: %s");
                $view->addFilter($f);

                $format = "WHERE tr.`id` IN ( SELECT DISTINCT tr_id FROM op_epa WHERE op_epa.`task` = 5 AND op_epa.`task_actual_date` <= '%s')";
                $f = new VoltDateViewFilter('filter_to_pss_actual_date', $format, '');
                $f->setDescriptionFormat("To: %s");
                $view->addFilter($f);

            }
            $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE op_trackers.id=',op_trackers.id) FROM op_trackers ORDER BY title";
            $f = new VoltDropDownViewFilter('filter_tracker', $options, null, true);
            $f->setDescriptionFormat("Tracker: %s");
            $view->addFilter($f);

            $format = "WHERE tr.`target_date` >= '%s'";
            $f = new VoltDateViewFilter('filter_from_tr_target_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE tr.`target_date` <= '%s'";
            $f = new VoltDateViewFilter('filter_to_tr_target_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

            $current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date");
            $month = 8;
            $optionsMonths = [];
            for($i = 1; $i <= 12; $i++)
            {
                $start_date_of_month = new Date($current_contract_year . '-'.$month.'-01');
                $last_date_of_month = DAO::getSingleValue($link, "SELECT LAST_DAY('{$start_date_of_month->formatMySQL()}')");
                $last_date_of_month = new Date($last_date_of_month);
                if($month == 12)
                {
                    $month = 0;
                    $current_contract_year++;
                }
                $month++;
                $optionsMonths[] = [$start_date_of_month->format('F') . ' ' . $start_date_of_month->format('Y'), $start_date_of_month->format('F') . ' ' . $start_date_of_month->format('Y'), null, "HAVING target_month = '" . $start_date_of_month->format('F') . " " . $start_date_of_month->format('Y') . "'"];
            }
            $f = new VoltDropDownViewFilter('filter_target_month', $optionsMonths, null, true);
            $f->setDescriptionFormat("Target Month: %s");
            if($view_name != 'view_ach_forecast_gateway_ready')
                $view->addFilter($f);

            $current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date");
            $optionsQuarters = [];
            $month = 8;
            for($i = 1; $i <= 12; $i = $i+3)
            {
                $start_date_of_quarter = new Date($current_contract_year . '-'.$month.'-01');
                $last_date_of_quarter = new Date($current_contract_year . '-'.$month.'-01');
                $last_date_of_quarter->addMonths(3);
                $last_date_of_quarter->subtractDays(1);
                if($month >= 11)
                {
                    $month = -1;
                    $current_contract_year++;
                }
                $month += 3;
                $option_name = $start_date_of_quarter->format('F') . ' ' . $start_date_of_quarter->format('Y') . ' - ' . $last_date_of_quarter->format('F') . ' ' . $last_date_of_quarter->format('Y');
                $optionsQuarters[] = [$option_name, $option_name, null, "WHERE tr.target_date BETWEEN '{$start_date_of_quarter->formatMySQL()}' AND '{$last_date_of_quarter->formatMySQL()}'"];
            }
            $f = new VoltDropDownViewFilter('filter_quarter', $optionsQuarters, null, true);
            $f->setDescriptionFormat("Quarter: %s");
            if($view_name != 'view_ach_forecast_gateway_ready')
                $view->addFilter($f);
        }
        elseif($view_name == 'view_3weeks_calls_report')
        {
            $options = "SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ' , users.surname, ' - ', users.username), LEFT(users.firstnames, 1), CONCAT('WHERE tr.assessor=',char(39),users.id,char(39)) FROM users INNER JOIN tr ON users.id = tr.assessor ORDER BY users.firstnames";
            $f = new VoltDropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`week_3_call` >= '%s'";
            $f = new VoltDateViewFilter('filter_from_3_wk_end_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`week_3_call` <= '%s'";
            $f = new VoltDateViewFilter('filter_to_3_wk_end_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`hour_48_call` >= '%s'";
            $f = new VoltDateViewFilter('filter_from_48_hr_end_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE tr_operations.`hour_48_call` <= '%s'";
            $f = new VoltDateViewFilter('filter_to_48_hr_end_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

        }
        elseif($view_name == 'view_epa_status_report')
        {
            $options = array(
                0=>array(0, 'Show All', null, null),
                1=>array(1, 'Ready', null, 'WHERE op_epa.task_status="1"'),
                2=>array(2, 'Not ready', null, 'WHERE op_epa.task_status="2"'),
                3=>array(3, 'Requested', null, 'WHERE op_epa.task_status="3"'),
                4=>array(4, 'Await return from employer', null, 'WHERE op_epa.task_status="4"'),
                5=>array(5, 'Assessor accepted', null, 'WHERE op_epa.task_status="5"'),
                6=>array(6, 'Assessor declined', null, 'WHERE op_epa.task_status="6"'),
                7=>array(7, 'Assessor passed to IQA', null, 'WHERE op_epa.task_status="7"'),
                8=>array(8, 'IQA passed', null, 'WHERE op_epa.task_status="8"'),
                9=>array(9, 'IQA rejected', null, 'WHERE op_epa.task_status="9"'),
                10=>array(10, 'BCS', null, 'WHERE op_epa.task_status="10"'),
                11=>array(11, 'C&G', null, 'WHERE op_epa.task_status="11"'),
                12=>array(12, 'Invited', null, 'WHERE op_epa.task_status="12"'),
                13=>array(13, 'Booked', null, 'WHERE op_epa.task_status="13"'),
                14=>array(14, 'Completed', null, 'WHERE op_epa.task_status="14"'),
                15=>array(15, 'Rejected', null, 'WHERE op_epa.task_status="15"'),
                16=>array(16, 'Pass', null, 'WHERE op_epa.task_status="16"'),
                17=>array(17, 'Merit', null, 'WHERE op_epa.task_status="17"'),
                18=>array(18, 'Distinction', null, 'WHERE op_epa.task_status="18"'),
                19=>array(19, 'Fail', null, 'WHERE op_epa.task_status="19"'),
                20=>array(20, 'Selected', null, 'WHERE op_epa.task_status="20"'),
                21=>array(21, 'Not Selected', null, 'WHERE op_epa.task_status="21"'),
                22=>array(22, 'In-progress with assessor', null, 'WHERE op_epa.task_status="22"'),
                23=>array(23, 'In Progress', null, 'WHERE op_epa.task_status="23"'),
                24=>array(24, 'To be sent', null, 'WHERE op_epa.task_status="24"'),
                25=>array(25, 'Not applicable', null, 'WHERE op_epa.task_status="25"'),
                26=>array(26, 'To be sampled', null, 'WHERE op_epa.task_status="26"'),
                27=>array(27, 'Awaiting BCS confirmation', null, 'WHERE op_epa.task_status="27"'),
                28=>array(28, 'EPA Ready', null, 'WHERE op_epa.task_status="28"'),
                29 =>array('Sent', null, 'WHERE op_epa.task_status="29"'), 
                30 =>array('Chased', null, 'WHERE op_epa.task_status="30"'),
                31 =>array('Complete', null, 'WHERE op_epa.task_status="31"'),
                32 =>array('Not Sent', null, 'WHERE op_epa.task_status="32"'),
                33 =>array('Yes', null, 'WHERE op_epa.task_status="33"'),
                34 =>array('No', null, 'WHERE op_epa.task_status="34"'),
                35 =>array('Yes', null, 'WHERE op_epa.task_status="35"'),
                36 =>array('No', null, 'WHERE op_epa.task_status="36"'),
                37 =>array('Not Set', null, 'WHERE op_epa.task_status="37"'),
                38 =>array('Not Completed', null, 'WHERE op_epa.task_status="38"'),
                39 =>array('Required', null, 'WHERE op_epa.task_status="39"'),
                40 =>array('Not Required', null, 'WHERE op_epa.task_status="40"'),
                41 =>array('Fail- continue', null, 'WHERE op_epa.task_status="41"'),
                42 =>array('Fail- completion non-achiever', null, 'WHERE op_epa.task_status="42"'),
                43 =>array('Awaiting EPA confirmation', null, 'WHERE op_epa.task_status="43"'),
                44 =>array('Involvement - PEED Concern', null, 'WHERE op_epa.task_status="44"'),
                45 =>array('Owning', null, 'WHERE op_epa.task_status="45"'),
                46 =>array('No action', null, 'WHERE op_epa.task_status="46"'),
                47 =>array('Action plan in place', null, 'WHERE op_epa.task_status="47"'),
                48 =>array('Involvement - LAR', null, 'WHERE op_epa.task_status="48"'),
                49 =>array('PEED Cause', null, 'WHERE op_epa.task_status="49"'),
                50 =>array('AP', null, 'WHERE op_epa.task_status="50"'),
                51 =>array('1st for EPA', null, 'WHERE op_epa.task_status="51"'),
                52 =>array('Sent to EPAO', null, 'WHERE op_epa.task_status="52"'),
                53 =>array('Project Submission', null, 'WHERE op_epa.task_status="53"'),
            );
            $f = new VoltDropDownViewFilter('filter_task_status', $options, 0, false);
            $f->setDescriptionFormat("Task Status: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT users.id, CONCAT(users.firstnames, ' ' , users.surname, ' - ', users.username), LEFT(users.firstnames, 1), CONCAT('WHERE tr.assessor=',char(39),users.id,char(39)) FROM users INNER JOIN tr ON users.id = tr.assessor ORDER BY users.firstnames";
            $f = new VoltDropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            $format = "WHERE tr.target_date >= '%s'";
            $f = new VoltDateViewFilter('filter_from_completed_date', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE tr.target_date <= '%s'";
            $f = new VoltDateViewFilter('filter_to_completed_date', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);
        }
        elseif($view_name == 'view_course_status_report')
        {
            $options = array(
                0=>array(0, 'Show All', null, null),
                1=>array(1, 'Invited', null, 'WHERE sch_table.code="I"'),
                2=>array(2, 'Booked', null, 'WHERE sch_table.code="B"'),
                3=>array(3, 'Required', null, 'WHERE sch_table.code="R"'),
                4=>array(4, 'Uploaded', null, 'WHERE sch_table.code="U"'),
                5=>array(5, 'Pass', null, 'WHERE sch_table.code="P"'),
                6=>array(6, 'Merit / Credit', null, 'WHERE sch_table.code="MC"'),
                7=>array(7, 'Distinction', null, 'WHERE sch_table.code="D"'),
                8=>array(8, 'Not Required', null, 'WHERE sch_table.code="NR"')
            );
            $f = new VoltDropDownViewFilter('filter_sch_code', $options, 0, false);
            $f->setDescriptionFormat("Sch Code: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT unit_ref, unit_ref, null, CONCAT('WHERE sch_table.unit_ref=',char(39),unit_ref,char(39)) FROM op_tracker_unit_sch ORDER BY unit_ref";
            $f = new VoltDropDownViewFilter('filter_unit_ref', $options, null, true);
            $f->setDescriptionFormat("Unit Ref: %s");
            $view->addFilter($f);

            $format = "WHERE sch_table.created >= '%s'";
            $f = new VoltDateViewFilter('filter_from_sch_date_created', $format, '');
            $f->setDescriptionFormat("From: %s");
            $view->addFilter($f);

            $format = "WHERE sch_table.created <= '%s'";
            $f = new VoltDateViewFilter('filter_to_sch_date_created', $format, '');
            $f->setDescriptionFormat("To: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show All', null, null),
                1=>array(1, 'Only Test', null, 'WHERE sch_table.unit_ref LIKE "% Test"'),
                2=>array(2, 'Without Test', null, 'WHERE sch_table.unit_ref NOT LIKE "% Test"')
            );
            $f = new VoltDropDownViewFilter('filter_test_units', $options, 0, false);
            $f->setDescriptionFormat("Test Unit: %s");
            $view->addFilter($f);

        }
        elseif($view_name == 'view_learners_additional_info_report')
        {
            $options = "SELECT DISTINCT id, description, NULL, CONCAT('WHERE LOCATE(\'<','Type','>', id, '<','/','Type','>\', additional_info) > 0') FROM lookup_op_add_details_types ORDER BY description";
            $f = new VoltDropDownViewFilter('filter_additional_info_type', $options, null, true);
            $f->setDescriptionFormat("Type: %s");
            $view->addFilter($f);
        }

        $options = array(
            0=>array(20,20,null,null),
            1=>array(50,50,null,null),
            2=>array(100,100,null,null),
            3=>array(200,200,null,null),
            4=>array(300,300,null,null),
            5=>array(400,400,null,null),
            6=>array(500,500,null,null),
            7=>array(0, 'No limit', null, null));
        $f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, $view_name == 'view_learners_additional_info_report' ? 0 : 20, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        return $view;
    }

    private function removeNotRequiredColumns($viewName, array $columns)
    {
        $final_array = $columns;
        switch($viewName)
        {
            case 'view_ach_forecast_in_prog':
            case 'view_ach_forecast_gateway_ready':
                $final_array = array_diff($columns, array('programme_id', 'tr_id', 'short_name', 'percentage_set', 'test_percentage_set', 'course_id'));
                break;
            case 'view_operations_lar_report':
            case 'view_operations_lar':
            case 'view_sales_lar':
            case 'view_sales_lar_report':
		$final_array = array_diff($columns, array('lar_details', 'lar_type'));
                break;
            case 'view_operations_bil_report':
            case 'previous_on_lar':
                $final_array = array_diff($columns, array('lar_details'));
                break;
            case 'view_monthly_leavers_report':
                $final_array = array_diff($columns, array('lar_date', 'actual_end_date', 'training_id'));
                break;
            default:
                break;
        }
        return $final_array;
    }

    private function render_view_previous_on_lar_report(PDO $link, $view)
    {	
        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<div align="center" ><table id="tblLearners" class="table table-striped table-bordered text-center" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr>';
            echo '<th>Firstnames</th><th>Surname</th><th>Employer</th><th>Programme</th><th>Induction Date</th><th>Coordinator</th><th>Learning Mentor</th>';
            echo '<th>Type</th><th>Date</th><th>RAG</th><th>LAR Closed Date</th><th>Primary Reason</th><th>Secondary Reason</th><th>Retention Category</th>';
            echo '<th>Actively Involved</th><th>Training Status</th>';
            echo '</tr></thead>';
            echo '<tbody>';

            $types = InductionHelper::getListLAR();
            $ragDDL = InductionHelper::getListLARRAGRating();
            $reasonDDL = InductionHelper::getListLARReason();
            $retnetions = InductionHelper::getListRetentionCategories();
            $active_inv_users = InductionHelper::getListActivelyInvolvedUsersList($link);

            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo '<tr>';
                echo '<td>' . $row['firstnames'] .'</td>';
                echo '<td>' . $row['surname'] .'</td>';
                echo '<td>' . $row['employer'] .'</td>';
                echo '<td>' . $row['programme'] .'</td>';
                echo '<td>' . $row['induction_date'] .'</td>';
                echo '<td>' . $row['coordinator'] .'</td>';
                echo '<td>' . $row['learning_mentor'] .'</td>';
                echo isset($types[$row['lar_type']])?'<td>' . $types[$row['lar_type']] . '</td>':'<td></td>';
                echo '<td>' . $row['lar_date'] . '</td>';
                echo '<td>' . $row['lar_rag'] . '</td>';
                echo '<td>' . $row['lar_closed_date'] . '</td>';


                $primary_reasons_description = [];
                if($row['lar_primary_reason'] != '')
                {
                    $primary_reasons = explode(",", $row['lar_primary_reason']);
                    foreach($primary_reasons AS $p_r)
                    {
                        if(isset($reasonDDL[$p_r]))
                        {
                            $primary_reasons_description[] = $reasonDDL[$p_r];
                        }
                    }
                }
                echo count($primary_reasons_description) > 0 ? '<td>' . implode("; ", $primary_reasons_description) . '</td>' : '<td></td>';

                $secondary_reasons_description = [];
                if($row['lar_secondary_reason'] != '')
                {
                    $secondary_reasons = explode(",", $row['lar_secondary_reason']);
                    foreach($secondary_reasons AS $s_r)
                    {
                        if(isset($reasonDDL[$s_r]))
                        {
                            $secondary_reasons_description[] = $reasonDDL[$s_r];
                        }
                    }
                }
                echo count($secondary_reasons_description) > 0 ? '<td>' . implode("; ", $secondary_reasons_description) . '</td>' : '<td></td>';

                echo isset($retnetions[$row['retention_category']]) ? '<td>' . $retnetions[$row['retention_category']] . '</td>' : '<td></td>';

                $users_names = [];
                if($row['actively_involved'] != '')
                {
                    $user_ids = explode(",", $row['actively_involved']);
                    foreach($user_ids AS $user_id)
                    {
                        if(isset($active_inv_users[$user_id]))
                        {
                            $users_names[] = $active_inv_users[$user_id];
                        }
			else
                        {
                            $users_names[] = $user_id;
                        }
                    }
                }
                echo count($users_names) > 0 ? '<td>' . implode("; ", $users_names) . '</td>' : '<td></td>';

                echo '<td>' . $row['training_record_status'] . '</td>';

                echo '</tr>';
            }

            echo '</tbody></table></div>';
            echo $view->getViewNavigatorExtra('', $view->getViewName());
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function export_view_previous_on_lar_report(PDO $link, $view)
    {
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if($st)
        {
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$view->getViewName().'.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            
            $types = InductionHelper::getListLAR();
            $ragDDL = InductionHelper::getListLARRAGRating();
            $reasonDDL = InductionHelper::getListLARReason();
            $retnetions = InductionHelper::getListRetentionCategories();
            $active_inv_users = InductionHelper::getListActivelyInvolvedUsersList($link);

            echo "Firstnames,Surname,Employer,Programme,Induction Date,Coordinator,Learning Mentor,Type,Date,RAG,LAR Closed Date,";
            echo "Primary Reason,Secondary Reason,Retention Category,Actively Involved,Training Status";
            echo "\r\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo $this->csvSafe($row['firstnames']) .',';
                echo $this->csvSafe($row['surname']) .',';
                echo $this->csvSafe($row['employer']) .',';
                echo $this->csvSafe($row['programme']) .',';
                echo $row['induction_date'] .',';
                echo $this->csvSafe($row['coordinator']) .',';
                echo $this->csvSafe($row['learning_mentor']) .',';
                echo isset($types[$row['lar_type']])?HTML::csvSafe($types[$row['lar_type']]) . ',':',';
                echo $row['lar_date'] .',';
                echo $row['lar_rag'] .',';
                echo $row['lar_closed_date'] .',';
                $primary_reasons_description = [];
                if($row['lar_primary_reason'] != '')
                {
                    $primary_reasons = explode(",", $row['lar_primary_reason']);
                    foreach($primary_reasons AS $p_r)
                    {
                        if(isset($reasonDDL[$p_r]))
                        {
                            $primary_reasons_description[] = HTML::csvSafe($reasonDDL[$p_r]);
                        }
                    }
                }
                echo count($primary_reasons_description) > 0 ? implode("; ", $primary_reasons_description) . ',' : ',';
                $secondary_reasons_description = [];
                if($row['lar_secondary_reason'] != '')
                {
                    $secondary_reasons = explode(",", $row['lar_secondary_reason']);
                    foreach($secondary_reasons AS $s_r)
                    {
                        if(isset($reasonDDL[$s_r]))
                        {
                            $secondary_reasons_description[] = HTML::csvSafe($reasonDDL[$s_r]);
                        }
                    }
                }
                echo count($secondary_reasons_description) > 0 ? implode("; ", $secondary_reasons_description) . ',' : ',';
                echo isset($retnetions[$row['retention_category']]) ? HTML::csvSafe($retnetions[$row['retention_category']]) . ',' : ',';
                $users_names = [];
                if($row['actively_involved'] != '')
                {
                    $user_ids = explode(",", $row['actively_involved']);
                    foreach($user_ids AS $user_id)
                    {
                        if(isset($active_inv_users[$user_id]))
                        {
                            $users_names[] = $active_inv_users[$user_id];
                        }
			else
                        {
                            $users_names[] = $user_id;
                        }
                    }
                }
                echo count($users_names) > 0 ? implode("; ", $users_names) . ',' : ',';
                echo $row['training_record_status'] . ',';
                echo "\r\n";

            }
        }
        else
        {
            throw new DatabaseException($link, $statement()->__toString());
        }
    }

    private function render_view_ach_forecast_gateway_ready_report(PDO $link, VoltView $view)
    {
        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            $columns = array();
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }

            $columns = $this->removeNotRequiredColumns($view->getViewName(), $columns);
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<div align="center" ><table id="tblLearners" class="table table-striped table-bordered text-center" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr>';
            foreach($columns AS $column)
            {
                echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . '</th>';
            }
            echo '</tr></thead>';
            echo '<tbody>';
            $interview_task_statuses = InductionHelper::getListOpTaskStatus(7);
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $tr_id = $row['tr_id'];

                if(isset($row['employer_ref_comments']))
                {
                    $row['employer_ref_comments'] = DAO::getSingleValue($link, "SELECT manager_comments.`comment` FROM manager_comments WHERE manager_comments.`tr_id` = '{$tr_id}' AND manager_comments.`comment_type` = 'ER' ORDER BY manager_comments.`id` DESC LIMIT 1");
                }
                if(isset($row['learner_progress_comments']))
                {
                    $row['learner_progress_comments'] = DAO::getSingleValue($link, "SELECT manager_comments.`comment` FROM manager_comments WHERE manager_comments.`tr_id` = '{$tr_id}' AND manager_comments.`comment_type` = 'LP' ORDER BY manager_comments.`id` DESC LIMIT 1");
                }
                //echo '<tr>';
		        echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['tr_id'], "small");
                echo '<td>' . $row['start_date'] . '</td>';
                echo '<td>' . $row['end_date'] . '</td>';
                echo '<td>' . $row['gateway_forecast_actual_date'] . '</td>';
                echo '<td>' . $row['programme'] . '</td>';
                echo '<td>' . $row['task_type'] . '</td>';
                echo '<td>' . $row['assessor'] . '</td>';
                echo '<td>' . $row['coordinator'] . '</td>';
                echo '<td>' . $row['line_manager'] . '</td>';
                echo '<td>' . $row['firstnames'] . '</td>';
                echo '<td>' . $row['surname'] . '</td>';
                $obj = TrainingRecord::getEvidenceProgress($link, $row['tr_id'], $row['course_id']);
                echo $obj->total > 0 ? '<td class="text-center bg-green">' . $obj->matrix . '/' . $obj->total . ' = ' . round(($obj->matrix/$obj->total) * 100)  . '%</td>' : '<td class="text-center bg-green">0%</td>';
                $class = '';
                $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                if(in_array($row['programme_id'], ['9', '18', '29']))
                    $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                else
                    $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                $course_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                if($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0)
                {
                    $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
                    $class = "bg-green";
                    if($current_training_month > $max_month_value && $course_percentage < 100)
                    {
                        $class = "bg-red";
                    }
                    else
                    {
                        $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                        $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                        if($course_percentage >= $aps_to_check)
                            $class = "bg-green";
                        else
                            $class = "bg-red";
                    }
                }
                if($course_percentage >= 100 || $current_training_month == 0)
                    $class = "bg-green";
                echo $total_units != 0 ? '<td class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . $course_percentage  . '%</td>': '<td class="text-center bg-green">N/A</td>';

                $class = '';
                $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC")');
                $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC"))');
                $test_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                if($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0)
                {
                    $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
                    $class = "bg-green";
                    if($current_training_month > $max_month_value && $test_percentage < 100)
                    {
                        $class = "bg-red";
                    }
                    else
                    {
                        $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                        $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                        if($test_percentage >= $aps_to_check)
                            $class = "bg-green";
                        else
                            $class = "bg-red";
                    }
                }
                if($test_percentage >= 100 || $current_training_month == 0)
                    $class = "bg-green";
                echo $total_units != 0 ? '<td class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . $test_percentage  . '%</td>': '<td class="text-center bg-green">N/A</td>';

		// IQA Progress
		$course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");

		$obj = TrainingRecord::getEvidenceProgress($link, $row['tr_id'], $course_id, 2);
                echo $obj->total > 0 ? 
                    '<td class="text-center bg-green">' . $obj->matrix . '/' . $obj->total . ' = ' . round(($obj->matrix/$obj->total) * 100)  . '%</td>' : 
                    '<td class="text-center bg-green">0%</td>';

                $class = '';
                
                $assessment_evidence = DAO::getSingleValue($link, "SELECT assessment_evidence FROM courses WHERE id = '$course_id'");
                $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                if($assessment_evidence==2)
                {
                    $class = 'bg-green';
                    $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");
                    $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
                    $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                    if(isset($max_month_row->id))
                    {
                        $class = 'bg-red';
                        if($current_training_month == 0)
                            $class = 'bg-green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                            $class = 'bg-green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                            $class = 'bg-red';
                        else
                        {
                            $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                            $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                            if($aps_to_check == '' || $passed_units >= $aps_to_check)
                                $class = 'bg-green';
                        }
                    }
                    echo $total_units != 0 ? '<td style="cursor:pointer;" class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . round(($passed_units/$total_units) * 100)  . '%</td>': '<td class="text-center '.$class.'">0%</td>';
                }
                else
                {
                    $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
                                    sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
                    $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                    $sd = Date::toMySQL($row['start_date']);
                    if(isset($max_month_row->id))
                    {
                        $class = 'bg-red';
                        if($current_training_month == 0)
                            $class = 'bg-green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                            $class = 'bg-green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                            $class = 'bg-red';
                        else
                        {
                            $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                            $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                            if($aps_to_check == '' || $passed_units >= $aps_to_check)
                                $class = 'bg-green';
                        }
                    }
                    echo $total_units != 0 ? '<td class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . round(($passed_units/$total_units) * 100)  . '%</td>': '<td class="text-center '.$class.'">0%</td>';
                }
                echo '<td>' . $row['total_no_of_plans'] . '</td>';
                echo '<td>' . $row['total_complete'] . '</td>';
                echo '<td>' . $row['awaiting_iqa'] . '</td>';
                echo '<td>' . $row['awaiting_marking'] . '</td>';
                echo '<td>' . $row['overdue'] . '</td>';
                echo '<td>' . $row['in_progress'] . '</td>';
                echo '<td>' . $row['to_be_set'] . '</td>';
                $yes_no = array('N' => 'No', 'Y' => 'Yes', '' => '');
                $epa_details = DAO::getResultset($link, "SELECT id, op_epa.task_applicable, op_epa.`task`, DATE_FORMAT(op_epa.`task_actual_date`, '%d/%m/%Y') AS task_actual_date, task_status, task_epao, task_comments FROM op_epa WHERE id IN (SELECT MAX(id) FROM op_epa WHERE tr_id = '{$tr_id}' GROUP BY task)", DAO::FETCH_ASSOC);
                $row_data = [];
                foreach($epa_details AS $epa_detail)
                {
                    $obj = new stdClass();
                    foreach($epa_detail AS $key => $value)
                    {
                        $obj->$key = $key == 'task_applicable' ? $yes_no[$value] : $value;
                    }
                    $row_data[$obj->task] = $obj;
                }
                if(isset($row_data[4])) //IQA complete and IQA actual date
                    echo '<td align="center">' . $row_data[4]->task_applicable . '</td><td>' . $row_data[4]->task_actual_date . '</td>';
                else
                    echo '<td></td><td></td>';
                if(isset($row_data[1])) // EPA ready and EPA ready actual date
                    echo '<td align="center">' . $row_data[1]->task_applicable . '</td><td>' . $row_data[1]->task_actual_date . '</td>';
                else
                    echo '<td></td><td></td>';
                if(isset($row_data[2])) // employer reference and actual date
                    echo '<td align="center">' . $row_data[2]->task_applicable . '</td><td>' . $row_data[2]->task_actual_date . '</td>';
                else
                    echo '<td></td><td></td>';
                if(isset($row_data[3])) // summative portfolio and actual date
                    echo '<td align="center">' . $row_data[3]->task_applicable . '</td><td>' . $row_data[3]->task_actual_date . '</td>';
                else
                    echo '<td></td><td></td>';
                if(isset($row_data[10])) // Gateway dec and Gateway dec actual date
                    echo '<td align="center">' . $row_data[10]->task_applicable . '</td><td>' . $row_data[10]->task_actual_date . '</td>';
                else
                    echo '<td></td><td></td>';
                if(isset($row_data[5])) // passed to ss project and actual date
                {
                    echo '<td align="center">' . $row_data[5]->task_applicable . '</td><td>' . $row_data[5]->task_actual_date . '</td>';
                    if($row_data[5]->task_status == '10')
                        echo '<td>BCS</td>';
                    elseif($row_data[5]->task_status == '11')
                        echo '<td>C&G</td>';
                    elseif($row_data[5]->task_status == '50')
                        echo '<td>AP</td>';
                    elseif($row_data[5]->task_status == '51')
                        echo '<td>1st for EPA</td>';
                    else
                        echo '<td></td>';
                }
                else
                    echo '<td></td><td></td><td></td>';
                if(isset($row_data[6])) // synoptic project and actual date
                    echo '<td align="center">' . $row_data[6]->task_applicable . '</td><td>' . $row_data[6]->task_actual_date . '</td>';
                else
                    echo '<td></td><td></td>';
                $epa_result_status = array('16' => 'Passed', '17' => 'Merit', '18' => 'Distinction', '19' => 'Fail', '41' => 'Fail- continue', '42' => 'Fail- completion non-achiever');
                if(isset($row_data[8])) // EPA result
                    echo isset($epa_result_status[$row_data[8]->task_status]) ? '<td>' . $epa_result_status[$row_data[8]->task_status] . '</td>' : '<td>' . $row_data[8]->task_status . '</td>';
                else
                    echo '<td></td>';
                if(isset($row_data[8])) // EPA result actual date
                    echo '<td align="center">' . $row_data[8]->task_actual_date . '</td>';
                else
                    echo '<td></td>';
                if(isset($row_data[7])) // interview and actual date and status
                {
                    echo '<td align="center">' . $row_data[7]->task_applicable . '</td><td>' . $row_data[7]->task_actual_date . '</td>';
                    echo isset($interview_task_statuses[$row_data[7]->task_status]) ? '<td>' . $interview_task_statuses[$row_data[7]->task_status] . '</td>' : '<td>' . $row_data[7]->task_status . '</td>';
                }
                else
                    echo '<td></td><td></td><td></td>';
                if(isset($row_data[19])) // deadline date actual date
                    echo '<td align="center">' . $row_data[19]->task_actual_date . '</td>';
                else
                    echo '<td></td>';
                if(isset($row_data[15])) // end of learning statement and actual date
                    echo '<td align="center">' . $row_data[15]->task_applicable . '</td><td>' . $row_data[15]->task_actual_date . '</td>';
                else
                    echo '<td></td><td></td>';
                if(isset($row_data[11])) // EPA forecast
                    echo '<td align="center">' . $row_data[11]->task_actual_date . '</td>';
                else
                    echo '<td></td>';
                $epa_potential_achievement_month = DAO::getSingleValue($link, "SELECT potential_achievement_month FROM op_epa WHERE op_epa.tr_id = '{$tr_id}' AND op_epa.`potential_achievement_month` IS NOT NULL ORDER BY id DESC LIMIT 1;");
                echo '<td>' . $epa_potential_achievement_month . '</td>';
                $epa_ready_comments = DAO::getSingleValue($link, "SELECT task_comments FROM op_epa WHERE op_epa.tr_id = '{$tr_id}' AND op_epa.`task` = '1' ORDER BY id DESC LIMIT 1;");
                echo '<td class="small">' . nl2br($epa_ready_comments) . '</td>';
                if(isset($row_data[16]))
                {
                    echo '<td align="center">' . $row_data[16]->task_actual_date . '</td>';
                    $epa_task_status = InductionHelper::getListOpTaskStatus(16);
                    echo isset($epa_task_status[$row_data[16]->task_status]) ? '<td>' . $epa_task_status[$row_data[16]->task_status] . '</td>' : '<td>' . $row_data[16]->task_status . '</td>';
                }
                else
                    echo '<td></td><td></td>';
		// HERE add for Project Actual Date and comments
                if(isset($row_data[9])) // end of learning statement and actual date
                    echo '<td align="center">' . $row_data[9]->task_actual_date . '</td><td>' . $row_data[9]->task_comments . '</td>';
                else
                    echo '<td></td><td></td>';
                echo '<td class="small">' . nl2br($row['employer_ref_comments']) . '</td>';
                echo '<td class="small">' . nl2br($row['learner_progress_comments']) . '</td>';
                echo '<td>' . $row['learner_status'] . '</td>';
                echo '<td>' . $row['peed_forecast_status'] . '</td>';
                echo '<td>' . Date::toShort($row['lsl_date']) . '</td>';
                echo '<td class="small">' . $row['peed_comments'] . '</td>';
                echo '<td>' . $row['peed_reason'] . '</td>';
                echo '<td>' . $row['peed_cause'] . '</td>';
                echo '<td>' . Date::toShort($row['revisit_date']) . '</td>';
                echo $row['peed_owner'] == '23461' ? '<td>Hannah Gibson</td>' : '<td>' . $row['peed_owner'] . '</td>';
                echo '<td>' . Date::toShort($row['peed_forecast_date']) . '</td>';
                echo '<td>' . $row['lsl_involvement'] . '</td>';
                echo '<td>' . $row['lsl_involvement_status'] . '</td>';
                //PEED task
		/*
                $peed_forecast = DAO::getObject($link, "SELECT task_status, op_epa.`task_lsl`, op_epa.`task_peed_cause`, op_epa.`task_peed_forecast_date`, op_epa.`task_comments` FROM op_epa WHERE op_epa.tr_id = '{$tr_id}' AND op_epa.`task` = '17' ORDER BY id DESC LIMIT 1;");
                if(isset($peed_forecast->task_status))
                {
                    $epa_task_status = InductionHelper::getListOpTaskStatus();
                    echo isset($epa_task_status[$peed_forecast->task_status]) ? '<td>' . $epa_task_status[$peed_forecast->task_status] . '</td>' : '<td>' . $peed_forecast->task_status . '</td>';
                    echo isset($yes_no[$peed_forecast->task_lsl]) ? '<td>' . $yes_no[$peed_forecast->task_lsl] . '</td>' : '<td>' . $peed_forecast->task_lsl . '</td>';
                    echo '<td>' . $peed_forecast->task_peed_cause . '</td>';
                    echo '<td>' . Date::toShort($peed_forecast->task_peed_forecast_date) . '</td>';
                    echo '<td>' . $peed_forecast->task_comments . '</td>';
                }
                else
                {
                    echo '<td></td><td></td><td></td><td></td><td></td>';
                }
		*/

                if(isset($row_data[10])) // Gateway Declarations
                {
                    $list_epao = InductionHelper::getListOpEpao();
                    echo isset($list_epao[$row_data[10]->task_epao]) ? '<td>' . $list_epao[$row_data[10]->task_epao] . '</td>' : '<td>' . $row_data[10]->task_epao . '</td>';
                }
                else
                {
                    echo '<td></td>';
                }
		echo '<td>' . $row['employer'] . '</td>';
		        echo '<td>' . $row['pdp_month9_date'] . '</td>';
		        echo '<td>' . $row['pdp_month9_completed'] . '</td>';
		        echo '<td>' . $row['pdp_month12_date'] . '</td>';
		        echo '<td>' . $row['pdp_month12_completed'] . '</td>';
		        echo '<td>' . $row['pdp_coach_sign'] . '</td>';
		        echo '<td>' . $row['mock_interview_planned_date'] . '</td>';
		        echo '<td>' . $row['mock_interview_actual_date'] . '</td>';
		        echo '<td>' . $row['mock_interview_completed'] . '</td>';
		        echo '<td>' . $row['CheckInDateWeek1'] . '</td>';
		        echo '<td>' . $row['CheckInDateWeek2'] . '</td>';
		        echo '<td>' . $row['CheckInDateWeek3'] . '</td>';
		        echo '<td>' . $row['CheckInDateWeek4'] . '</td>';
		        echo '<td>' . $row['CheckInDoneWeek1'] . '</td>';
		        echo '<td>' . $row['CheckInDoneWeek2'] . '</td>';
		        echo '<td>' . $row['CheckInDoneWeek3'] . '</td>';
		        echo '<td>' . $row['CheckInDoneWeek4'] . '</td>';
		        echo '<td>' . $row['project_plan'] . '</td>';
		        echo '<td>' . $row['apprenticeship_title'] . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $view->getViewNavigatorExtra('', $view->getViewName());
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function renderView(PDO $link, VoltView $view)
    {
        if($view->getViewName() == 'view_ach_forecast_gateway_ready')
        {
            $this->render_view_ach_forecast_gateway_ready_report($link, $view);
            return;
        }
        if($view->getViewName() == 'previous_on_lar')
        {
            $this->render_view_previous_on_lar_report($link, $view);
            return;
        }
        //if(SOURCE_HOME) pre($view->getSQLStatement()->__toString());
        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            $columns = array();
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }

            $columns = $this->removeNotRequiredColumns($view->getViewName(), $columns);
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            $small = in_array($view->getViewName(), array('view_epa_status_report')) ? 'small' : '';
            echo '<div align="center" ><table id="tblLearners" class="table table-striped table-bordered text-center'.$small.'" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr>';
            foreach($columns AS $column)
            {
                if($view->getViewName() == 'view_operations_lar_report' && in_array($column, ["lar_summary", "lar_communication", "lar_contact_history", "lar_next_action_history"]))
                {
                    echo '<th class="bottomRow">';
                    echo ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column)));
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    echo '</th>'; 
                }
                else
                {
                    echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . '</th>';
                }
            }
            echo '</tr></thead>';
            echo '<tbody>';
	        $bil_owners_list = InductionHelper::getListOpOwners('Y');	
	        $lras_reasons = Safeguarding::getListTriggers($link);	
            $lras_categories = Safeguarding::getListCategories($link);
            $lras_sps = Safeguarding::getListSupportProvider();
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo '<tr>';
                foreach($columns AS $column)
                {
                    if($column == 'comments' || $column == 'additional_support')
                        echo '<td class="small">' . HTML::nl2p($row[$column]) . '</td>';
                    elseif(in_array($view->getViewName(), ['view_operations_lar_report', 'view_sales_lar_report'])  && $column == 'lar_owner')
                    {
                        echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['lar_owner']}'") . '</td>';
                    }
		    elseif($column == 'owner' && $view->getViewName() == 'view_leaver_reinstatement')
                    {
                        echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['owner']}'") . '</td>';
                    }	
                    elseif( $view->getViewName() == 'view_lras_report' && $column == 'created_by' )
                    {
                        echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'") . '</td>';
                    }
                    elseif( $view->getViewName() == 'view_lras_report' && $column == 'reason' )
                    {
                        echo '<td>';
                        foreach( explode(',', $row['reason']) AS $lras_reason )
                            echo isset($lras_reasons[$lras_reason]) ? $lras_reasons[$lras_reason] . ' | ' : $lras_reason . ' | ';
                        echo '</td>';
                    }
                    elseif( $view->getViewName() == 'view_lras_report' && $column == 'category' )
                    {
                        echo '<td>';
                        echo isset($lras_categories[$row['category']]) ? $lras_categories[$row['category']] : $row['category'];
                        echo '</td>';
                    }
                    elseif( $view->getViewName() == 'view_lras_report' && $column == 'support_provider' )
                    {
                        echo '<td>';
                        foreach( explode(',', $row['support_provider']) AS $lras_sp )
                            echo isset($lras_sps[$lras_sp]) ? $lras_sps[$lras_sp] . ' | ' : $lras_sp . ' | ';
                        echo '</td>';
                    }
		            elseif(in_array($view->getViewName(), ['view_operations_lar_report', 'view_sales_lar_report'])  && $column == 'actively_involved' && trim($row['actively_involved'] != ''))
                    {
                        $_ai_users = explode(',', $row['actively_involved']);
                        echo '<td>';
                        foreach($_ai_users AS $_ai_user)
                        {
                            $_ai_user_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$_ai_user}'");
                            echo $_ai_user_name != '' ? $_ai_user_name : $_ai_user;
                            echo count($_ai_users) > 1 ? ' | ' : '';
                        }
                        echo '</td>';
			            //echo '<td>' . DAO::getSingleValue($link, "SELECT GROUP_CONCAT(CONCAT(firstnames, ' ', surname) SEPARATOR '; ') FROM users WHERE users.id IN ({$row['actively_involved']})") . '</td>';
                    }	
                    elseif(in_array($view->getViewName(), ['view_operations_bil_report'])  && $column == 'bil_owner')
                    {
                        echo isset($bil_owners_list[$row['bil_owner']]) ? '<td>' . $bil_owners_list[$row['bil_owner']] . '</td>' : '<td></td>';
                    }
                    elseif(in_array($view->getViewName(), ['view_operations_bil_report'])  && $column == 'bil_reason')
                    {
                        $bil_reasons = InductionHelper::getListLARReason();
                        echo isset($bil_reasons[$row['bil_reason']]) ? '<td>' . $bil_reasons[$row['bil_reason']] . '</td>' : '<td></td>';
                    }
                    elseif(in_array($view->getViewName(), ['view_operations_bil_report'])  && $column == 'bil_retention')
                    {
                        $bil_retentions = InductionHelper::getListBilRetentions();
                        echo isset($bil_retentions[$row['bil_retention']]) ? '<td>' . $bil_retentions[$row['bil_retention']] . '</td>' : '<td></td>';
                    }
                    elseif($view->getViewName() == 'view_course_status_report' && $column == 'course_date')
                    {
                        if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
                        {
                            $sql = <<<SQL
SELECT sessions.start_date FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND session_entries.`entry_exam_name` = '{$row['course']}'
ORDER BY
	#sessions.`created` DESC
	sessions.`start_date` DESC
LIMIT 1
;
SQL;
                        }
                        else
                        {
                            //SELECT sessions.start_date FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND sessions.unit_ref = '{$row['course']}';
                            $sql = <<<SQL
SELECT sessions.start_date FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND FIND_IN_SET('{$row['course']}', unit_ref) ORDER BY sessions.`start_date` DESC;
SQL;
                        }
                        $course_date = DAO::getSingleValue($link, $sql);
                        echo '<td>' . Date::toShort($course_date) . '</td>';
                    }
                    elseif($view->getViewName() == 'view_course_status_report' && $column == 'rft')
                    {
                        $pft = false;
			$pnft = '';
                        $u_ref = $row['course'];
                        // 1. for tests only
                        if(substr($u_ref, -5) === ' Test' || strtolower(substr($u_ref, -5)) === ' test')
                        {
                            // 2. if current status is pass
                            $current_status_sql = <<<SQL
SELECT
  entry_op_tracker_status
FROM
  session_entries 
WHERE entry_tr_id = '{$row['training_id']}'
  AND entry_exam_name = '$u_ref'
  AND session_entries.`entry_session_id` IN (SELECT id FROM sessions WHERE sessions.`status` = 'S')
ORDER BY entry_id DESC
LIMIT 1;
SQL;
                            $current_status = DAO::getSingleValue($link, $current_status_sql);
                            if($current_status ==  "P")
                            {
                                // 3. any failed row
                                $entry_rows = DAO::getSingleValue($link, "SELECT COUNT(*) FROM session_entries WHERE entry_tr_id = '{$row['training_id']}' AND entry_exam_name = '{$u_ref}' AND entry_op_tracker_status = 'F'");
                                if($entry_rows == 0)
                                {
                                    $pft = true;
                                }
				else
                                {
                                    $pnft = intval($entry_rows)+1;
                                }
                            }
                        }
                        echo '<td>';
                        echo $pft ? 'RFT' : '';
                        echo $pnft;
                        echo '</td>';
                    }
                    elseif($view->getViewName() == 'view_course_status_report' && $column == 'event_type')
                    {
                        if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
                        {
                            $sql = <<<SQL
SELECT sessions.event_type FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND session_entries.`entry_exam_name` = '{$row['course']}'
ORDER BY
	sessions.`start_date` DESC
LIMIT 1
;
SQL;
                        }
                        else
                        {
                            $sql = <<<SQL
SELECT sessions.event_type FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND FIND_IN_SET('{$row['course']}', unit_ref) ORDER BY sessions.`start_date` DESC;
SQL;
                        }
                        $event_types = InductionHelper::getListEventTypes();
                        $event_type = DAO::getSingleValue($link, $sql);
                        echo isset($event_types[$event_type]) ? '<td>' . $event_types[$event_type] . '</td>' : '<td></td>';
                    }
                    elseif($view->getViewName() == 'view_course_status_report' && $column == 'trainer')
                    {
                        if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
                        {
                            $sql = <<<SQL
SELECT sessions.personnel FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND session_entries.`entry_exam_name` = '{$row['course']}'
ORDER BY
	sessions.`start_date` DESC
LIMIT 1
;
SQL;
                        }
                        else
                        {
                            $sql = <<<SQL
SELECT sessions.personnel FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND FIND_IN_SET('{$row['course']}', unit_ref) ORDER BY sessions.`start_date` DESC;
SQL;
                        }
                        $personnel = DAO::getSingleValue($link, $sql);
                        echo $personnel != '' ? '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$personnel}'") . '</td>' : '<td></td>';
                    }
                    elseif($view->getViewName() == 'view_course_status_report' && $column == 'exam_time')
                    {
                        if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
                        {
                            $sql = <<<SQL
SELECT CONCAT(sessions.start_time, ' - ', sessions.end_time) FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND session_entries.`entry_exam_name` = '{$row['course']}'
ORDER BY
	sessions.`start_date` DESC
LIMIT 1
;
SQL;
                        }
                        else
                        {
                            $sql = <<<SQL
SELECT NULL;
SQL;
                        }
                        $exam_time = DAO::getSingleValue($link, $sql);
                        echo $exam_time != '' ? '<td>' . $exam_time . '</td>' : '<td></td>';
                    }
                    elseif(in_array($view->getViewName(), array('view_ach_forecast_in_prog', 'view_ach_forecast_gateway_ready')) && ($column == 'technical_course_progress' || $column == 'test_progress' || $column == 'ap_progress'))
                    {
                        $class = '';
                        if($column == 'technical_course_progress')
                        {
                            $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                            if(in_array($row['programme_id'], ['9', '18', '29']))
                                $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                            else
                                $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                            $course_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
                            $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                            if($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0)
                            {
                                $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
                                $class = "bg-green";
                                if($current_training_month > $max_month_value && $course_percentage < 100)
                                {
                                    $class = "bg-red";
                                }
                                else
                                {
                                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                                    $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                                    if($course_percentage >= $aps_to_check)
                                        $class = "bg-green";
                                    else
                                        $class = "bg-red";
                                }
                            }
                            if($course_percentage >= 100 || $current_training_month == 0)
                                $class = "bg-green";

                            echo $total_units != 0 ? '<td class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . $course_percentage  . '%</td>': '<td class="text-center '.$class.'">N/A</td>';
                        }
                        elseif($column == 'test_progress')
                        {
                            //$total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref LIKE "% Test"');
                            //$passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test"');
                            //echo $total_units != 0 ? '<td class="text-center '.$class.'">' . round(($passed_units/$total_units) * 100)  . '%</td>': '<td class="text-center '.$class.'">N/A</td>';

                            ///////////////////////////////////////
                            $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC")');
                            $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC"))');
                            $test_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
                            $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                            if($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0)
                            {
                                $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
                                $class = "bg-green";
                                if($current_training_month > $max_month_value && $test_percentage < 100)
                                {
                                    $class = "bg-red";
                                }
                                else
                                {
                                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                                    $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND id<$month_row_id ORDER BY id DESC LIMIT 1");

                                    if($test_percentage >= $aps_to_check)
                                        $class = "bg-green";
                                    else
                                        $class = "bg-red";
                                }
                            }
                            if($test_percentage >= 100 || $current_training_month == 0)
                                $class = "bg-green";

                            echo $total_units != 0 ? '<td class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . $test_percentage  . '%</td>': '<td class="text-center '.$class.'">N/A</td>';
                            ///////////////////////////////////////
                        }
                        else
                        {
                            $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                            $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                            //$passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$row['tr_id']}' AND paperwork = '3';");
                            $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		        				sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                				WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
                            $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                            $sd = Date::toMySQL($row['start_date']);
                            $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                            if(isset($max_month_row->id))
                            {
                                $class = 'bg-red';
                                if($current_training_month == 0)
                                    $class = 'bg-green';
                                elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                                    $class = 'bg-green';
                                elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                                    $class = 'bg-red';
                                else
                                {
                                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                                    $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                                    if($aps_to_check == '' || $passed_units >= $aps_to_check)
                                        $class = 'bg-green';
                                }
                            }
                            echo $total_units != 0 ? '<td class="text-center '.$class.'">' . $passed_units . '/' . $total_units . ' = ' . round(($passed_units/$total_units) * 100)  . '%</td>': '<td class="text-center '.$class.'">0%</td>';
                        }
                    }
                    elseif($column == 'added_to_lar_date')
                    {
                        $the_date = '';
                        if($view->getViewName() == 'previous_on_lar')
                        {
                            if($row['lar_details'] != '' && !is_null($row['lar_details']))
                            {
                                $lar_details = XML::loadSimpleXML($row['lar_details']);
                                $the_date = $lar_details->Note[0]->Date->__toString();
                                if(count($lar_details->Note) > 0 && $lar_details->Note[count($lar_details->Note)-1]->Type->__toString() == 'N')
                                {
                                    $is_Note_n_present = false;
                                    foreach($lar_details->Note AS $note)
                                    {
                                        if($note->Type->__toString() == 'N')
                                        {
                                            $is_Note_n_present = true;
                                            break;
                                        }
                                    }
                                    if(!$is_Note_n_present)
                                        $the_date = $lar_details->Note[0]->Date->__toString();
                                    else
                                    {
                                        for($i = count($lar_details->Note) - 2; $i >= 0; $i--)
                                        {
                                            if($lar_details->Note[$i]->Type->__toString() == 'N')
                                            {
                                                $the_date = $lar_details->Note[$i+1]->Date->__toString();
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            if($row['lar_details'] != '' && !is_null($row['lar_details']))
                            {
                                $lar_details = XML::loadSimpleXML($row['lar_details']);
                                if(count($lar_details->Note) > 0 && $lar_details->Note[count($lar_details->Note)-1]->Type->__toString() != 'N')
                                {
                                    $is_Note_n_present = false;
                                    foreach($lar_details->Note AS $note)
                                    {
                                        if($note->Type->__toString() == 'N')
                                        {
                                            $is_Note_n_present = true;
                                            break;
                                        }
                                    }
                                    if(!$is_Note_n_present)
                                        $the_date = $lar_details->Note[0]->Date->__toString();
                                    else
                                    {
                                        for($i = count($lar_details->Note) - 1; $i >= 0; $i--)
                                        {
                                            if($lar_details->Note[$i]->Type->__toString() == 'N')
                                            {
                                                $the_date = $lar_details->Note[$i+1]->Date->__toString();
                                                break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        echo '<td class="text-center">' . $the_date . '</td>';
                    }
                    elseif(in_array($view->getViewName(), ['view_monthly_leavers_report', 'view_leaver_reinstatement']) && $column == 'leaver_reason')
                    {
                        $_list_leaver_reasons = InductionHelper::getListOpLeaverReasons();
                        echo isset($_list_leaver_reasons[$row[$column]]) ? '<td>' . $_list_leaver_reasons[$row[$column]] . '</td>' : '<td></td>';
                    }
                    elseif(in_array($view->getViewName(), ['view_monthly_leavers_report', 'view_leaver_reinstatement']) && $column == 'leaver_motive')
                    {
                        $_list_leaver_motives = InductionHelper::getListLARCause();
                        echo isset($_list_leaver_motives[$row[$column]]) ? '<td>' . $_list_leaver_motives[$row[$column]] . '</td>' : '<td></td>';
                    }
                    elseif($view->getViewName() == 'view_monthly_leavers_report' && $column == 'on_lar_at_leaving')
                    {
                        echo $row['leaver_date'] == $row['lar_date'] ? '<td>Yes</td>' : '<td>No</td>';
                    }
                    elseif($view->getViewName() == 'view_monthly_leavers_report' && $column == 'days_on_programme')
                    {
                        if($row['actual_end_date']!='')
                            $_end_date = Date::toMySQL($row['actual_end_date']);
                        else
                            $_end_date = date('Y-m-d');
                        echo '<td>' . TrainingRecord::getDiscountedDaysOnProgramme($link, $row['training_id'], $_end_date) . '</td>';
                    }
		    elseif($view->getViewName() == 'view_monthly_leavers_report' && $column == 'leaver_positive_outcome')
                    {
                        $p_outcomes = InductionHelper::getListLeaverPositiveOutcome();
                        echo isset($p_outcomes[$row['leaver_positive_outcome']]) ? '<td>' . $p_outcomes[$row['leaver_positive_outcome']] . '</td>' : '<td></td>';
                    }
                    elseif($column == 'lar_reason')
                    {
                        $lar_reason_list = InductionHelper::getListLARReason();
                        if(isset($row['lar_reason']) && $row['lar_reason'] != '')
                        {
                            echo '<td>';
                            foreach(explode(",", $row['lar_reason']) AS $_r)
                            {
                                echo isset($lar_reason_list[$_r]) ? $lar_reason_list[$_r] . '; ' : '';
                            }
                            echo '</td>';
                        }
                        else
                        {
                            echo '<td></td>';
                        }
                    }
                    elseif($column == 'secondary_lar_reason')
                    {
                        $lar_reason_list = InductionHelper::getListLARReason();
                        if(isset($row['secondary_lar_reason']) && $row['secondary_lar_reason'] != '')
                        {
                            echo '<td>';
                            foreach(explode(",", $row['secondary_lar_reason']) AS $_r)
                            {
                                echo isset($lar_reason_list[$_r]) ? $lar_reason_list[$_r] . '; ' : '';
                            }
                            echo '</td>';
                        }
                        else
                        {
                            echo '<td></td>';
                        }
                    }
                    elseif($column == 'retention_category')
                    {
                        $retention_category_list = InductionHelper::getListRetentionCategories();
                        echo isset($retention_category_list[$row['retention_category']]) ? '<td>' . $retention_category_list[$row['retention_category']] . '</td>' : '<td></td>';
                    }
                    else
                        echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $view->getViewNavigatorExtra('', $view->getViewName());
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function renderAdditionInfoReportView(PDO $link, VoltView $view)
    {
        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            //echo $view->getViewNavigatorExtra('', $view->getViewName());
            $filter_type = $view->getFilterValue('filter_additional_info_type');
            echo '<div align="" ><table id="tblLearners" class="table table-bordered">';
            echo '<thead>';
            echo '<tr><th colspan="9" class="bg-info">Learner</th><th colspan="5" class="bg-success">Additional Information</th></tr>';
            echo '<tr><th class="bg-info">LO3</th><th class="bg-info">Firstnames</th><th class="bg-info">Surname</th><th class="bg-info">DOB</th><th class="bg-info">Start Date</th><th class="bg-info">Planned End Date</th>';
            echo '<th class="bg-info">Assessor</th><th class="bg-info">Programme</th><th class="bg-info">Coordinator</th>';
            echo '<th class="bg-success">Date</th><th class="bg-success">Type</th><th class="bg-success">Detail</th><th class="bg-success">Created</th><th class="bg-success">Created By</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $notes = XML::loadSimpleXML($row['additional_info']);
                foreach($notes->Note AS $note)
                {
                    if($filter_type != '' && $note->Type->__toString() != $filter_type)
                        continue;
                    echo '<tr>';
                    echo '<td>' . $row['l03'] . '</td>';
                    echo '<td>' . $row['firstnames'] . '</td>';
                    echo '<td>' . $row['surname'] . '</td>';
                    echo '<td>' . $row['dob'] . '</td>';
                    echo '<td>' . $row['start_date'] . '</td>';
                    echo '<td>' . $row['planned_end_date'] . '</td>';
                    echo '<td>' . $row['assessor'] . '</td>';
                    echo '<td class="small">' . $row['programme'] . '</td>';
                    echo '<td>' . $row['coordinator'] . '</td>';
                    echo '<td>' . html_entity_decode($note->Date) . '</td>';
                    echo '<td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_op_add_details_types WHERE id = '" . html_entity_decode($note->Type) . "'") . '</td>';
                    echo '<td class="small">' . html_entity_decode($note->Detail) . '</td>';
                    echo '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                    echo '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                    echo '</tr>';
                }
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            //echo $view->getViewNavigatorExtra('', $view->getViewName());
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function exportAdditionInfoReportView(PDO $link, VoltView $view)
    {
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if($st)
        {
            $filter_type = $view->getFilterValue('filter_additional_info_type');
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$view->getViewName().'.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            echo 'LO3,Firstnames,Surname,DOB,Start Date,Planned End Date,Assessor,Programme,Coordinator,Date,Type,Detail,Created,Created By';
            echo "\r\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $notes = XML::loadSimpleXML($row['additional_info']);
                foreach($notes->Note AS $note)
                {
                    if($filter_type != '' && $note->Type->__toString() != $filter_type)
                        continue;
                    echo $this->csvSafe($row['l03']) . ',';
                    echo $this->csvSafe($row['firstnames']) . ',';
                    echo $this->csvSafe($row['surname']) . ',';
                    echo $row['dob'] . ',';
                    echo $row['start_date'] . ',';
                    echo $row['planned_end_date'] . ',';
                    echo $this->csvSafe($row['assessor']) . ',';
                    echo $this->csvSafe($row['programme']) . ',';
                    echo $this->csvSafe($row['coordinator']) . ',';
                    echo html_entity_decode($note->Date) . ',';
                    echo $this->csvSafe(DAO::getSingleValue($link, "SELECT description FROM lookup_op_add_details_types WHERE id = '" . html_entity_decode($note->Type) . "'")) . ',';
                    echo $this->csvSafe(html_entity_decode($note->Detail)) . ',';
                    echo Date::to($note->DateTime, Date::DATETIME) . ',';
                    echo $this->csvSafe(DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'")) . ',';
                    echo "\r\n";
                }
            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function export_view_ach_forecast_gateway_ready_report(PDO $link, VoltView $view)
    {
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if($st)
        {
            $filter_type = $view->getFilterValue('filter_additional_info_type');
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$view->getViewName().'.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            $columns = array();
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }

            $columns = $this->removeNotRequiredColumns($view->getViewName(), $columns);
            foreach($columns AS $column)
            {
                if($column == 'technical_course_progress')
                {
                    echo 'Technical Completed Units,';
                    echo 'Technical Total Units,';
                    echo 'Technical Progress,';
                    echo 'Tech Progress Status,';
                }
                elseif($column == 'test_progress')
                {
                    echo 'Test Completed Units,';
                    echo 'Test Total Units,';
                    echo 'Test Progress,';
                    echo 'Test Progress Status,';
                }
                elseif($column == 'ap_progress')
                {
                    echo 'Assessment Progress,';
                    echo 'Assessment Progress Status,';
                }
                else
                {
                    echo ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . ',';
                }
            }
            echo "\r\n";

	    $interview_task_statuses = InductionHelper::getListOpTaskStatus(7);

            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                $tr_id = $row['tr_id'];
                if(isset($row['employer_ref_comments']))
                {
                    $row['employer_ref_comments'] = DAO::getSingleValue($link, "SELECT manager_comments.`comment` FROM manager_comments WHERE manager_comments.`tr_id` = '{$tr_id}' AND manager_comments.`comment_type` = 'ER' ORDER BY manager_comments.`id` DESC LIMIT 1");
                }
                if(isset($row['learner_progress_comments']))
                {
                    $row['learner_progress_comments'] = DAO::getSingleValue($link, "SELECT manager_comments.`comment` FROM manager_comments WHERE manager_comments.`tr_id` = '{$tr_id}' AND manager_comments.`comment_type` = 'LP' ORDER BY manager_comments.`id` DESC LIMIT 1");
                }
                echo $row['start_date'] . ',';
                echo $row['end_date'] . ',';
                echo $row['gateway_forecast_actual_date'] . ',';
                echo $this->csvSafe($row['programme']) . ',';
                echo $this->csvSafe($row['task_type']) . ',';
                echo $this->csvSafe($row['assessor']) . ',';
                echo $this->csvSafe($row['coordinator']) . ',';
                echo $this->csvSafe($row['line_manager']) . ',';
                echo $this->csvSafe($row['firstnames']) . ',';
                echo $this->csvSafe($row['surname']) . ',';
                $obj = TrainingRecord::getEvidenceProgress($link, $row['tr_id'], $row['course_id']);
                echo $obj->total > 0 ? $obj->matrix . '/' . $obj->total . ' = ' . round(($obj->matrix/$obj->total) * 100)  . '%,' : '0%,';
                $class = '';
                $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                if(in_array($row['programme_id'], ['9', '18', '29']))
                    $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                else
                    $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                $course_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                if($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0)
                {
                    $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
                    $class = "green";
                    if($current_training_month > $max_month_value && $course_percentage < 100)
                    {
                        $class = "red";
                    }
                    else
                    {
                        $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                        $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                        if($course_percentage >= $aps_to_check)
                            $class = "green";
                        else
                            $class = "red";
                    }
                }
                if($course_percentage >= 100 || $current_training_month == 0)
                    $class = "green";

                echo $passed_units . ',';
                echo $total_units . ',';
                echo $total_units != 0 ? $course_percentage  . '%,': 'N/A,';
                echo $total_units != 0 ? $class . ',' : 'green,';

                $class = '';
                $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC")');
                $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC"))');
                $test_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                if($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0)
                {
                    $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
                    $class = "green";
                    if($current_training_month > $max_month_value && $test_percentage < 100)
                    {
                        $class = "red";
                    }
                    else
                    {
                        $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                        $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                        if($test_percentage >= $aps_to_check)
                            $class = "green";
                        else
                            $class = "red";
                    }
                }
                if($test_percentage >= 100 || $current_training_month == 0)
                    $class = "green";

                echo $passed_units . ',';
                echo $total_units . ',';
                echo $total_units != 0 ? $test_percentage  . '%,': 'N/A,';
                echo $total_units != 0 ? $class . ',' : 'green,';

		// IQA Progress
                $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                $obj = TrainingRecord::getEvidenceProgress($link, $row['tr_id'], $course_id, 2);
                echo $obj->total > 0 ? 
                    $obj->matrix . '/' . $obj->total . ' = ' . round(($obj->matrix/$obj->total) * 100)  . '%,' : 
                    '0%,';

                $class = '';

                $assessment_evidence = DAO::getSingleValue($link, "SELECT assessment_evidence FROM courses WHERE id = '$course_id'");
                $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                if($assessment_evidence==2)
                {
                    $class = 'bg';
                    $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND completion_date is not null");
                    $passed_units2 = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_projects LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
                                    sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND (completion_date is not null or (sent_iqa_date is not null and iqa_status!=2) or submission_date is not null)");
                    $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                    if(isset($max_month_row->id))
                    {
                        $class = 'red';
                        if($current_training_month == 0)
                            $class = 'green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                            $class = 'green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                            $class = 'red';
                        else
                        {
                            $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                            $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                            if($aps_to_check == '' || $passed_units >= $aps_to_check)
                                $class = 'green';
                        }
                    }
                    echo $total_units != 0 ? $passed_units . '/' . $total_units . ' = ' . round(($passed_units/$total_units) * 100)  . '%,': '0%,';
                }
                else
                {
                    $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
                                    sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                                    WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
                    $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                    $sd = Date::toMySQL($row['start_date']);
                    if(isset($max_month_row->id))
                    {
                        $class = 'red';
                        if($current_training_month == 0)
                            $class = 'green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                            $class = 'green';
                        elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                            $class = 'red';
                        else
                        {
                            $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                            $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                            if($aps_to_check == '' || $passed_units >= $aps_to_check)
                                $class = 'green';
                        }
                    }
                    echo $total_units != 0 ? $passed_units . '/' . $total_units . ' = ' . round(($passed_units/$total_units) * 100)  . '%,': '0%,';
                }
                //echo $total_units != 0 ? round(($passed_units/$total_units) * 100)  . '%,': 'N/A,';
                echo $class . ',';
                echo $row['total_no_of_plans'] . ',';
                echo $row['total_complete'] . ',';
                echo $row['awaiting_iqa'] . ',';
                echo $row['awaiting_marking'] . ',';
                echo $row['overdue'] . ',';
                echo $row['in_progress'] . ',';
                echo $row['to_be_set'] . ',';
                $yes_no = array('N' => 'No', 'Y' => 'Yes', '' => '');
                $epa_details = DAO::getResultset($link, "SELECT id, op_epa.task_applicable, op_epa.`task`, DATE_FORMAT(op_epa.`task_actual_date`, '%d/%m/%Y') AS task_actual_date, task_status, task_epao, task_comments FROM op_epa WHERE id IN (SELECT MAX(id) FROM op_epa WHERE tr_id = '{$tr_id}' GROUP BY task)", DAO::FETCH_ASSOC);
                $row_data = [];
                foreach($epa_details AS $epa_detail)
                {
                    $obj = new stdClass();
                    foreach($epa_detail AS $key => $value)
                    {
                        $obj->$key = $key == 'task_applicable' ? $yes_no[$value] : $value;
                    }
                    $row_data[$obj->task] = $obj;
                }
                if(isset($row_data[4])) //IQA complete and IQA actual date
                    echo $row_data[4]->task_applicable . ',' . $row_data[4]->task_actual_date . ',';
                else
                    echo ',,';
                if(isset($row_data[1])) // EPA ready and EPA ready actual date
                    echo $row_data[1]->task_applicable . ',' . $row_data[1]->task_actual_date . ',';
                else
                    echo ',,';
                if(isset($row_data[2])) // employer reference and actual date
                    echo $row_data[2]->task_applicable. ',' . $row_data[2]->task_actual_date. ',';
                else
                    echo ',,';
                if(isset($row_data[3])) // summative portfolio and actual date
                    echo $row_data[3]->task_applicable. ',' . $row_data[3]->task_actual_date. ',';
                else
                    echo ',,';
                if(isset($row_data[10])) // Gateway dec and Gateway dec actual date
                    echo $row_data[10]->task_applicable . ',' . $row_data[10]->task_actual_date . ',';
                else
                    echo ',,';
                if(isset($row_data[5])) // passed to ss project and actual date
                {
                    echo $row_data[5]->task_applicable. ',' . $row_data[5]->task_actual_date. ',';
                    if($row_data[5]->task_status == '10')
                        echo 'BCS,>';
                    elseif($row_data[5]->task_status == '11')
                        echo 'C&G,';
                    elseif($row_data[5]->task_status == '50')
                        echo 'AP,';
                    elseif($row_data[5]->task_status == '51')
                        echo '1st for EPA,';
                    else
                        echo ',';
                }
                else
                    echo ',,,';
                if(isset($row_data[6])) // synoptic project and actual date
                    echo $row_data[6]->task_applicable. ',' . $row_data[6]->task_actual_date. ',';
                else
                    echo ',,';
                $epa_result_status = array('16' => 'Passed', '17' => 'Merit', '18' => 'Distinction', '19' => 'Fail', '41' => 'Fail- continue', '42' => 'Fail- completion non-achiever');
                if(isset($row_data[8])) // EPA result
                    echo isset($epa_result_status[$row_data[8]->task_status]) ? $epa_result_status[$row_data[8]->task_status] . ',' : $row_data[8]->task_status . ',';
                else
                    echo ',';
                if(isset($row_data[8])) // EPA result actual date
                    echo $row_data[8]->task_actual_date . ',';
                else
                    echo ',';
                if(isset($row_data[7])) // interview and actual date
                {
                    echo $row_data[7]->task_applicable. ',' . $row_data[7]->task_actual_date. ',';
                    echo isset($interview_task_statuses[$row_data[7]->task_status]) ? $interview_task_statuses[$row_data[7]->task_status] . ',' : $row_data[7]->task_status . ',';
                }
                else
                    echo ',,,';
                if(isset($row_data[19])) // deadline date actual date
                    echo $row_data[19]->task_actual_date . ',';
                else
                    echo ',';
                if(isset($row_data[15])) // end of learning statement and actual date
                    echo $row_data[15]->task_applicable . ',' . $row_data[15]->task_actual_date . ',';
                else
                    echo ',,';
                if(isset($row_data[11])) // EPA forecast
                    echo $row_data[11]->task_actual_date . ',';
                else
                    echo ',';
                $epa_potential_achievement_month = DAO::getSingleValue($link, "SELECT potential_achievement_month FROM op_epa WHERE op_epa.tr_id = '{$tr_id}' AND op_epa.`potential_achievement_month` IS NOT NULL ORDER BY id DESC LIMIT 1;");
                echo $epa_potential_achievement_month . ',';
                $epa_ready_comments = DAO::getSingleValue($link, "SELECT task_comments FROM op_epa WHERE op_epa.tr_id = '{$tr_id}' AND op_epa.`task` = '1' ORDER BY id DESC LIMIT 1;");
                echo $this->csvSafe($epa_ready_comments) . ',';
                if(isset($row_data[16]))
                {
                    echo $row_data[16]->task_actual_date . ',';
                    $epa_task_status = InductionHelper::getListOpTaskStatus(16);
                    echo isset($epa_task_status[$row_data[16]->task_status]) ? $epa_task_status[$row_data[16]->task_status] . ',' : $row_data[16]->task_status . ',';
                }
                else
                    echo ',,';
		if(isset($row_data[9])) // Project and comments
                    echo $row_data[9]->task_actual_date . ',' . $this->csvSafe($row_data[9]->task_comments) . ',';
                else
                    echo ',,';
                echo $this->csvSafe($row['employer_ref_comments']) . ',';
                echo $this->csvSafe($row['learner_progress_comments']) . ',';
                echo $this->csvSafe($row['learner_status']) . ',';
                echo $this->csvSafe($row['peed_forecast_status']) . ',';
                echo $this->csvSafe($row['lsl_date']) . ',';
                echo $this->csvSafe($row['peed_comments']) . ',';
                echo $this->csvSafe($row['peed_reason']) . ',';
                echo $this->csvSafe($row['peed_cause']) . ',';
                echo Date::toShort($row['revisit_date']) . ',';
                echo $row['peed_owner'] == '23461' ? 'Hannah Gibson,' : $this->csvSafe($row['peed_owner']) . ',';
                echo Date::toShort($row['peed_forecast_date']) . ',';
                echo $this->csvSafe($row['lsl_involvement']) . ',';
                echo $this->csvSafe($row['lsl_involvement_status']) . ',';

                //PEED task
		/*
                $peed_forecast = DAO::getObject($link, "SELECT task_status, op_epa.`task_lsl`, op_epa.`task_peed_cause`, op_epa.`task_peed_forecast_date`, op_epa.`task_comments` FROM op_epa WHERE op_epa.tr_id = '{$tr_id}' AND op_epa.`task` = '17' ORDER BY id DESC LIMIT 1;");
                if(isset($peed_forecast->task_status))
                {
                    $epa_task_status = InductionHelper::getListOpTaskStatus();
                    echo isset($epa_task_status[$peed_forecast->task_status]) ? $this->csvSafe($epa_task_status[$peed_forecast->task_status]) . ',' : $this->csvSafe($peed_forecast->task_status) . ',';
                    echo isset($yes_no[$peed_forecast->task_lsl]) ? $this->csvSafe($yes_no[$peed_forecast->task_lsl]) . ',' : $this->csvSafe($peed_forecast->task_lsl) . ',';
                    echo $this->csvSafe($peed_forecast->task_peed_cause) . ',';
                    echo Date::toShort($peed_forecast->task_peed_forecast_date) . ',';
                    echo $this->csvSafe($peed_forecast->task_comments) . ',';
                }
                else
                {
                    echo ',,,,,';
                }
		*/

                if(isset($row_data[10])) // Gateway Declarations
                {
                    $list_epao = InductionHelper::getListOpEpao();
                    echo isset($list_epao[$row_data[10]->task_epao]) ? $this->csvSafe($list_epao[$row_data[10]->task_epao]) . ',' : $row_data[10]->task_epao . ',';
                }
                else
                {
                    echo ',';
                }
		echo $this->csvSafe($row['employer']) . ',';
                echo $row['pdp_month9_date'] . ',';
		        echo $row['pdp_month9_completed'] . ',';
		        echo $row['pdp_month12_date'] . ',';
		        echo $row['pdp_month12_completed'] . ',';
		        echo $row['pdp_coach_sign'] . ',';
		        echo $row['mock_interview_planned_date'] . ',';
		        echo $row['mock_interview_actual_date'] . ',';
		        echo $row['mock_interview_completed'] . ',';
		        echo $row['CheckInDateWeek1'] . ',';
		        echo $row['CheckInDateWeek2'] . ',';
		        echo $row['CheckInDateWeek3'] . ',';
		        echo $row['CheckInDateWeek4'] . ',';
		        echo $row['CheckInDoneWeek1'] . ',';
		        echo $row['CheckInDoneWeek2'] . ',';
		        echo $row['CheckInDoneWeek3'] . ',';
		        echo $row['CheckInDoneWeek4'] . ',';
		        echo $this->csvSafe($row['project_plan']) . ',';
		        echo $this->csvSafe($row['apprenticeship_title']) . ',';
                echo "\r\n";

            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function exportToCSV(PDO $link, VoltView $view)
    {
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if($st)
        {
            $columns = array();
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }
            $columns = $this->removeNotRequiredColumns($view->getViewName(), $columns);
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename='.$view->getViewName().'.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            foreach($columns AS $column)
            {
                if(in_array($view->getViewName(), array('view_ach_forecast_in_prog', 'view_ach_forecast_gateway_ready')) && $column == 'technical_course_progress')
                {
                    echo 'Technical Completed Units,';
                    echo 'Technical Total Units,';
                    echo 'Technical Progress,';
                    echo 'Tech Progress Status,';
                }
                elseif(in_array($view->getViewName(), array('view_ach_forecast_in_prog', 'view_ach_forecast_gateway_ready')) && $column == 'test_progress')
                {
                    echo 'Test Completed Units,';
                    echo 'Test Total Units,';
                    echo 'Test Progress,';
                    echo 'Test Progress Status,';
                }
                elseif(in_array($view->getViewName(), array('view_ach_forecast_gateway_ready')) && $column == 'ap_progress')
                {
                    echo 'Assessment Progress,';
                    echo 'Assessment Progress Status,';
                }
                else
                {
                    echo ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . ',';
                }
            }
            echo "\r\n";
	    $bil_owners_list = InductionHelper::getListOpOwners('Y');
        $lras_reasons = Safeguarding::getListTriggers($link);	
        $lras_categories = Safeguarding::getListCategories($link);
        $lras_sps = Safeguarding::getListSupportProvider();
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                foreach($columns AS $column)
                {
                    if($view->getViewName() == 'view_course_status_report' && $column == 'course_date')
                    {
                        if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
                        {
                            $sql = <<<SQL
SELECT sessions.start_date FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND session_entries.`entry_exam_name` = '{$row['course']}'
ORDER BY
	#sessions.`created` DESC
	sessions.`start_date` DESC
LIMIT 1
;
SQL;
                        }
                        else
                        {
                            $sql = <<<SQL
SELECT sessions.start_date FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND FIND_IN_SET('{$row['course']}', unit_ref) ORDER BY sessions.`start_date` DESC;
SQL;
                        }
                        $course_date = DAO::getSingleValue($link, $sql);
                        echo Date::toShort($course_date) . ',';
                    }
		    elseif($column == 'owner' && $view->getViewName() == 'view_leaver_reinstatement')
                    {
                        echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['owner']}'") . ',';
                    }
                    elseif(in_array($view->getViewName(), ['view_operations_lar_report', 'view_sales_lar_report'])  && $column == 'lar_owner')
                    {
                        echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['lar_owner']}'") . ',';
                    }
		    elseif(in_array($view->getViewName(), ['view_operations_lar_report', 'view_sales_lar_report'])  && $column == 'actively_involved' && trim($row['actively_involved'] != ''))
                    {
			$_ai_users = explode(',', $row['actively_involved']);
                        foreach($_ai_users AS $_ai_user)
                        {
                            $_ai_user_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$_ai_user}'");
                            echo $_ai_user_name != '' ? $_ai_user_name : $_ai_user;
                            echo count($_ai_users) > 1 ? '; ' : '';
                        }
                        echo ',';
                        //echo DAO::getSingleValue($link, "SELECT GROUP_CONCAT(CONCAT(firstnames, ' ', surname) SEPARATOR '; ') FROM users WHERE users.id IN ({$row['actively_involved']})") . ',';
                    }
                    elseif( $view->getViewName() == 'view_lras_report' && $column == 'created_by' )
                    {
                        echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'") . ',';
                    }
                    elseif( $view->getViewName() == 'view_lras_report' && $column == 'reason' )
                    {
                        foreach( explode(',', $row['reason']) AS $lras_reason )
                            echo isset($lras_reasons[$lras_reason]) ? $lras_reasons[$lras_reason] . ' | ' : $lras_reason . ' | ';
                        echo ',';
                    }
                    elseif( $view->getViewName() == 'view_lras_report' && $column == 'category' )
                    {
                        echo isset($lras_categories[$row['category']]) ? $lras_categories[$row['category']] : $row['category'];
                        echo ',';
                    }
                    elseif( $view->getViewName() == 'view_lras_report' && $column == 'support_provider' )
                    {
                        foreach( explode(',', $row['support_provider']) AS $lras_sp )
                            echo isset($lras_sps[$lras_sp]) ? $lras_sps[$lras_sp] . ' | ' : $lras_sp . ' | ';
                        echo ',';
                    }	
                    elseif($view->getViewName() == 'view_course_status_report' && $column == 'rft')
                    {
                        $pft = false;
			$pnft = '';
                        $u_ref = $row['course'];
                        // 1. for tests only
                        if(substr($u_ref, -5) === ' Test' || strtolower(substr($u_ref, -5)) === ' test')
                        {
                            // 2. if current status is pass
                            $current_status_sql = <<<SQL
SELECT
  entry_op_tracker_status
FROM
  session_entries 
WHERE entry_tr_id = '{$row['training_id']}'
  AND entry_exam_name = '$u_ref'
  AND session_entries.`entry_session_id` IN (SELECT id FROM sessions WHERE sessions.`status` = 'S')
ORDER BY entry_id DESC
LIMIT 1;
SQL;
                            $current_status = DAO::getSingleValue($link, $current_status_sql);
                            if($current_status ==  "P")
                            {
                                // 3. any failed row
                                $entry_rows = DAO::getSingleValue($link, "SELECT COUNT(*) FROM session_entries WHERE entry_tr_id = '{$row['training_id']}' AND entry_exam_name = '{$u_ref}' AND entry_op_tracker_status = 'F'");
                                if($entry_rows == 0)
                                {
                                    $pft = true;
                                }
				else
                                {
                                    $pnft = intval($entry_rows)+1;
                                }
                            }
                        }
                        echo $pft ? 'RFT' : '';
                        echo $pnft;
                        echo ',';
                    }
                    elseif($view->getViewName() == 'view_course_status_report' && $column == 'event_type')
                    {
                        if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
                        {
                            $sql = <<<SQL
SELECT sessions.event_type FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND session_entries.`entry_exam_name` = '{$row['course']}'
ORDER BY
	sessions.`start_date` DESC
LIMIT 1
;
SQL;
                        }
                        else
                        {
                            $sql = <<<SQL
SELECT sessions.event_type FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND FIND_IN_SET('{$row['course']}', unit_ref) ORDER BY sessions.`start_date` DESC;
SQL;
                        }
                        $event_types = InductionHelper::getListEventTypes();
                        $event_type = DAO::getSingleValue($link, $sql);
                        echo isset($event_types[$event_type]) ? $this->csvSafe($event_types[$event_type]) . ',' : ',';
                    }
                    elseif($view->getViewName() == 'view_course_status_report' && $column == 'trainer')
                    {
                        if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
                        {
                            $sql = <<<SQL
SELECT sessions.personnel FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND session_entries.`entry_exam_name` = '{$row['course']}'
ORDER BY
	sessions.`start_date` DESC
LIMIT 1
;
SQL;
                        }
                        else
                        {
                            $sql = <<<SQL
SELECT sessions.personnel FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND FIND_IN_SET('{$row['course']}', unit_ref) ORDER BY sessions.`start_date` DESC;
SQL;
                        }
                        $personnel = DAO::getSingleValue($link, $sql);
                        echo $personnel != '' ? DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$personnel}'") . ',' : ',';
                    }
                    elseif($view->getViewName() == 'view_course_status_report' && $column == 'exam_time')
                    {
                        if(substr($row['course'], -5) === ' Test' || substr($row['course'], -5) === ' test')
                        {
                            $sql = <<<SQL
SELECT CONCAT(sessions.start_time, ' - ', sessions.end_time) FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE session_entries.entry_tr_id = '{$row['training_id']}' AND session_entries.`entry_exam_name` = '{$row['course']}'
ORDER BY
	sessions.`start_date` DESC
LIMIT 1
;
SQL;
                        }
                        else
                        {
                            $sql = <<<SQL
SELECT NULL;
SQL;
                        }
                        $exam_time = DAO::getSingleValue($link, $sql);
                        echo $exam_time != '' ? $this->csvSafe($exam_time) . ',' : ',';
                    }
                    elseif($view->getViewName() == 'view_ach_forecast_gateway_ready' && ($row[$column] == '<i class="fa fa-check"></i>' || $row[$column] == '<i class="fa fa-close"></i>'))
                    {
                        echo $row[$column] == '<i class="fa fa-check"></i>' ? 'Yes,' : 'No,';
                    }
                    elseif(in_array($view->getViewName(), array('view_ach_forecast_in_prog', 'view_ach_forecast_gateway_ready')) && ($column == 'technical_course_progress' || $column == 'test_progress' || $column == 'ap_progress'))
                    {
                        $class = '';
                        if($column == 'technical_course_progress')
                        {
                            $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                            if(in_array($row['programme_id'], ['9', '18', '29']))
                                $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                            else
                                $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("U") AND m1.unit_ref NOT LIKE "% Test" AND m1.`unit_ref` != "SLC" AND m1.`unit_ref` NOT LIKE "%Workshop%" AND m1.`unit_ref` NOT LIKE "%Functional Skills%" AND m1.`unit_ref` NOT LIKE "%EPA Prep%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Digital Marketing Campaign%" AND m1.`unit_ref` NOT LIKE "%Plan Create and Implement a Multi Channel Digital Marketing Campaign%"');
                            $course_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
                            $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                            if($row['short_name'] != '' && $row['percentage_set'] > 0 && $course_percentage < 100 && $current_training_month > 0)
                            {
                                $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_course_percentage WHERE programme = '{$row['short_name']}';");
                                $class = "green";
                                if($current_training_month > $max_month_value && $course_percentage < 100)
                                {
                                    $class = "red";
                                }
                                else
                                {
                                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                                    $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_course_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                                    if($course_percentage >= $aps_to_check)
                                        $class = "green";
                                    else
                                        $class = "red";
                                }
                            }
                            if($course_percentage >= 100 || $current_training_month == 0)
                                $class = "green";

                            echo $passed_units . ',';
                            echo $total_units . ',';
                            echo $total_units != 0 ? $course_percentage  . '%,': 'N/A,';
                            echo $class . ',';
                        }
                        elseif($column == 'test_progress')
                        {
                            //$total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND m1.unit_ref LIKE "% Test"');
                            //$passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test"');
                            //echo $total_units != 0 ? round(($passed_units/$total_units) * 100)  . '%,': 'N/A,';
                            ///////////////////////////////////////
                            $total_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND m1.`code` != "NR" AND (m1.unit_ref LIKE "% Test" OR m1.`unit_ref` = "SLC")');
                            $passed_units = DAO::getSingleValue($link, 'SELECT COUNT(*) FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.tr_id = "' . $row['tr_id'] .'" AND ( (m1.`code` IN ("P", "MC", "D") AND m1.unit_ref LIKE "% Test") OR (m1.`code` IN ("U", "P") AND m1.`unit_ref` = "SLC"))');
                            $test_percentage = $total_units != 0 ? round(($passed_units/$total_units) * 100) : 'N/A';
                            $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $row['tr_id']);
                            if($row['short_name'] != '' && $row['test_percentage_set'] > 0 && $test_percentage < 100 && $current_training_month > 0)
                            {
                                $max_month_value = DAO::getSingleValue($link, "SELECT MAX(max_month) FROM op_test_percentage WHERE programme = '{$row['short_name']}';");
                                $class = "green";
                                if($current_training_month > $max_month_value && $test_percentage < 100)
                                {
                                    $class = "red";
                                }
                                else
                                {
                                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND {$current_training_month} BETWEEN min_month AND max_month");
                                    $aps_to_check = DAO::getSingleValue($link, "SELECT max_percentage FROM op_test_percentage WHERE programme = '{$row['short_name']}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");

                                    if($test_percentage >= $aps_to_check)
                                        $class = "green";
                                    else
                                        $class = "red";
                                }
                            }
                            if($test_percentage >= 100 || $current_training_month == 0)
                                $class = "green";

                            echo $passed_units . ',';
                            echo $total_units . ',';
                            echo $total_units != 0 ? $test_percentage  . '%,': 'N/A,';
                            echo $class . ',';
                            ///////////////////////////////////////
                        }
                        else
                        {
                            $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$row['tr_id']}'");
                            $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
                            //$passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = '{$row['tr_id']}' AND paperwork = '3';");
                            $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		        				sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                				WHERE tr_id = '{$row['tr_id']}' AND completion_date IS NOT NULL");
                            $max_month_row = DAO::getObject($link, "SELECT * FROM ap_percentage WHERE course_id = '{$course_id}' ORDER BY id DESC LIMIT 1");
                            $sd = Date::toMySQL($row['start_date']);
                            $current_training_month = DAO::getSingleValue($link, "SELECT IF((DAY('$sd')<=13), ((PERIOD_DIFF(DATE_FORMAT(CURDATE(),\"%Y%m\"),DATE_FORMAT('$sd',\"%Y%m\")))+1), ((PERIOD_DIFF(DATE_FORMAT(CURDATE(),\"%Y%m\"),DATE_FORMAT('$sd',\"%Y%m\")))))");
                            if(isset($max_month_row->id))
                            {
                                $class = 'red';
                                if($current_training_month == 0)
                                    $class = 'green';
                                elseif($current_training_month > $max_month_row->max_month && $passed_units >= $max_month_row->aps)
                                    $class = 'green';
                                elseif($current_training_month > $max_month_row->max_month && $passed_units < $max_month_row->aps)
                                    $class = 'red';
                                else
                                {
                                    $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                                    $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                                    if($aps_to_check == '' || $passed_units >= $aps_to_check)
                                        $class = 'green';
                                }
                            }
                            echo $total_units != 0 ? round(($passed_units/$total_units) * 100)  . '%,': 'N/A,';
                            echo $class . ',';
                        }
                    }
                    elseif($column == 'added_to_lar_date')
                    {
                        $the_date = '';
                        if($row['lar_details'] != '' && !is_null($row['lar_details']))
                        {
                            $lar_details = XML::loadSimpleXML($row['lar_details']);
                            if(count($lar_details->Note) > 0 && $lar_details->Note[count($lar_details->Note)-1]->Type->__toString() != 'N')
                            {
                                $is_Note_n_present = false;
                                foreach($lar_details->Note AS $note)
                                {
                                    if($note->Type->__toString() == 'N')
                                    {
                                        $is_Note_n_present = true;
                                        break;
                                    }
                                }
                                if(!$is_Note_n_present)
                                    $the_date = $lar_details->Note[0]->Date->__toString();
                                else
                                {
                                    for($i = count($lar_details->Note) - 1; $i >= 0; $i--)
                                    {
                                        if($lar_details->Note[$i]->Type->__toString() == 'N')
                                        {
                                            $the_date = $lar_details->Note[$i+1]->Date->__toString();
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        echo $the_date . ',';
                    }
                    elseif($view->getViewName() == 'view_operations_bil_report' && $column == 'bil_owner')
                    {
                        echo isset($bil_owners_list[$row['bil_owner']]) ? HTML::csvSafe($bil_owners_list[$row['bil_owner']]) . ',' : ',';
                    }
                    elseif(in_array($view->getViewName(), ['view_operations_bil_report'])  && $column == 'bil_reason')
                    {
                        $bil_reasons = InductionHelper::getListLARReason();
                        echo isset($bil_reasons[$row['bil_reason']]) ? $this->csvSafe($bil_reasons[$row['bil_reason']]) . ',' : ',';
                    }
                    elseif(in_array($view->getViewName(), ['view_operations_bil_report'])  && $column == 'bil_retention')
                    {
                        $bil_retentions = InductionHelper::getListBilRetentions();
                        echo isset($bil_retentions[$row['bil_retention']]) ? $this->csvSafe($bil_retentions[$row['bil_retention']]) . ',' : ',';
                    }
                    elseif(in_array($view->getViewName(), ['view_monthly_leavers_report', 'view_leaver_reinstatement']) && $column == 'leaver_reason')
                    {
                        $_list_leaver_reasons = InductionHelper::getListOpLeaverReasons();
                        echo isset($_list_leaver_reasons[$row[$column]]) ? $this->csvSafe($_list_leaver_reasons[$row[$column]]) . ',' : ',';
                    }
                    elseif(in_array($view->getViewName(), ['view_monthly_leavers_report', 'view_leaver_reinstatement']) && $column == 'leaver_motive')
                    {
                        $_list_leaver_motives = InductionHelper::getListLARCause();
                        echo isset($_list_leaver_motives[$row[$column]]) ? $this->csvSafe($_list_leaver_motives[$row[$column]]) . ',' : ',';
                    }
                    elseif($view->getViewName() == 'view_monthly_leavers_report' && $column == 'on_lar_at_leaving')
                    {
                        echo $row['leaver_date'] == $row['lar_date'] ? 'Yes,' : 'No,';
                    }
                    elseif($view->getViewName() == 'view_monthly_leavers_report' && $column == 'days_on_programme')
                    {
                        if($row['actual_end_date']!='')
                            $_end_date = Date::toMySQL($row['actual_end_date']);
                        else
                            $_end_date = date('Y-m-d');
                        echo TrainingRecord::getDiscountedDaysOnProgramme($link, $row['training_id'], $_end_date) . ',';
                    }
		            elseif($view->getViewName() == 'view_monthly_leavers_report' && $column == 'leaver_positive_outcome')
                    {
                        $p_outcomes = InductionHelper::getListLeaverPositiveOutcome();
                        echo isset($p_outcomes[$row['leaver_positive_outcome']]) ? $p_outcomes[$row['leaver_positive_outcome']] . ',' : ',';
                    }
                    elseif($column == 'lar_reason')
                    {
                        $lar_reason_list = InductionHelper::getListLARReason();
                        if(isset($row['lar_reason']) && $row['lar_reason'] != '')
                        {
                            foreach(explode(",", $row['lar_reason']) AS $_r)
                            {
                                echo isset($lar_reason_list[$_r]) ? $this->csvSafe($lar_reason_list[$_r]) . '; ' : '';
                            }
                            echo ',';
                        }
                        else
                        {
                            echo ',';
                        }
                    }
                    elseif($column == 'secondary_lar_reason')
                    {
                        $lar_reason_list = InductionHelper::getListLARReason();
                        if(isset($row['secondary_lar_reason']) && $row['secondary_lar_reason'] != '')
                        {
                            foreach(explode(",", $row['secondary_lar_reason']) AS $_r)
                            {
                                echo isset($lar_reason_list[$_r]) ? $this->csvSafe($lar_reason_list[$_r]) . '; ' : '';
                            }
                            echo ',';
                        }
                        else
                        {
                            echo ',';
                        }
                    }
                    elseif($column == 'retention_category')
                    {
                        $retention_category_list = InductionHelper::getListRetentionCategories();
                        echo isset($retention_category_list[$row['retention_category']]) ? $this->csvSafe($retention_category_list[$row['retention_category']]) . ',' : ',';
                    }
                    else
                        echo ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
                }
                echo "\r\n";
            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
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