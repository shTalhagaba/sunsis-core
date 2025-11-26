<?php
class assessment_dashboard implements IAction
{
    public function execute(PDO $link)
    {
        $fromDate = date('d/m/Y');
        $toDate = date('d/m/Y');

        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        if($subaction == 'showInductionDashPanels')
        {
            echo $this->showInductionDashPanels($link);
            exit;
        }
        if($subaction == 'showReviewProgress')
        {
            echo $this->showReviewProgress($link);
            exit;
        }

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=assessment_dashboard", "Induction Dashboard");

        $first_date = date('Y-m-d',strtotime("first day of this month"));
        $last_date = date('Y-m-d',strtotime("last day of this month"));

        $assAssessors = Array();
        foreach($assAssessors AS $row)
        {
            $a_id = $row['assigned_assessor'];
            $sql = <<<SQL
SELECT
  COUNT(*) AS cnt
FROM
  induction
  INNER JOIN inductees ON induction.`inductee_id` = inductees.id
  LEFT JOIN tr ON inductees.`sunesis_username` = tr.`username`
WHERE tr.`username` IS NOT NULL AND induction.induction_date >= '$first_date' AND induction.induction_date <= '$last_date' AND induction.assigned_assessor = '$a_id'
;
SQL;
            $row['on_prog'] = (int)DAO::getSingleValue($link, $sql);
            $sql = <<<SQL
SELECT
  COUNT(*) AS cnt
FROM
  induction
  INNER JOIN inductees ON induction.`inductee_id` = inductees.id
  LEFT JOIN tr ON inductees.`sunesis_username` = tr.`username`
WHERE tr.`username` IS NOT NULL AND induction.induction_date >= '$first_date' AND induction.induction_date <= '$last_date' AND induction.assigned_assessor = '$a_id' AND induction.`planned_end_date` BETWEEN CURDATE() AND NOW() + INTERVAL 30 DAY
;
SQL;
            $row['comp_due'] = (int)DAO::getSingleValue($link, $sql);
        }

        foreach($assAssessors AS &$arr)
            foreach($arr AS $key => &$value)
                if($key == 'assigned_assessor')
                    $value = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$value}'");

        $aAssessorNames = array();
        $a_newly_signed = array();
        $a_on_prog = array();
        $a_comp_due = array();

        for($i = 0; $i < count($assAssessors); $i++)
        {
            $aAssessorNames[] = $assAssessors[$i]['assigned_assessor'];
            $a_newly_signed[] = $assAssessors[$i]['newly_signed'];
            $a_on_prog[] = $assAssessors[$i]['on_prog'];
            $a_comp_due[] = $assAssessors[$i]['comp_due'];
        }

        $assessor_sql = <<<HEREDOC
SELECT DISTINCT users.id, CONCAT(firstnames,' ',surname, ' - ' , lookup_user_types.`description`), LEFT(firstnames, 1), CONCAT('WHERE (groups.assessor=',users.id,' and tr.assessor is null) OR tr.assessor=' , users.id)
FROM users
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
WHERE
users.id IN (SELECT assessor FROM tr WHERE status_code = 1 AND assessor IS NOT NULL)
OR
(users.id IN (SELECT assessor FROM groups WHERE assessor IN (SELECT assessor FROM groups WHERE id IN (SELECT groups_id FROM group_members WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))))
AND users.id NOT IN ((SELECT assessor FROM tr WHERE status_code = 1 AND assessor IS NOT NULL)))
ORDER BY firstnames, surname;
HEREDOC;
        $assessors = DAO::getResultset($link,$assessor_sql);

        require_once('tpl_assessment_dashboard.php');
    }


    private function showInductionDashPanels(PDO $link)
    {
        $fromDate = $_REQUEST['fromDate'];
        $start_date = Date::toMySQL($fromDate);

        $toDate = $_REQUEST['toDate'];
        $end_date = Date::toMySQL($toDate);

        $where_actual = " and actual_date between '$start_date' and '$end_date'";
        $where_signed = " and signed_off_date between '$start_date' and '$end_date'";
        $where_due = " and due_date between '$start_date' and '$end_date'";

        $assessor = isset($_REQUEST['assessor'])?$_REQUEST['assessor']:'';
        $where_assessor = "";
        if($assessor!='')
            $where_assessor = " and tr_id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR (tr.assessor is null and groups.assessor='$assessor')) ";

        //$total_plans = DAO::getSingleValue($link,"SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_actual $where_assessor;");
        $total_plans_completed = DAO::getSingleValue($link,"SELECT COUNT(*) FROM assessment_plan_log WHERE paperwork=3 and tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_signed $where_assessor;");
        $total_plans_completed_timely = DAO::getSingleValue($link,"SELECT COUNT(*) FROM assessment_plan_log WHERE signed_off_date <= due_date and paperwork=3 and tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_signed $where_assessor;");
        $total_in_progress = DAO::getSingleValue($link,"SELECT COUNT(*) FROM assessment_plan_log WHERE paperwork=1 and tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_due $where_assessor;");
        $total_awaiting_marking = DAO::getSingleValue($link,"SELECT COUNT(*) FROM assessment_plan_log WHERE paperwork=2 and tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_actual $where_assessor;");
        $total_rework_required = DAO::getSingleValue($link,"SELECT COUNT(*) FROM assessment_plan_log WHERE paperwork=4 and tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_due $where_assessor;");
        $total_iqa_rejected = DAO::getSingleValue($link,"SELECT COUNT(*) FROM assessment_plan_log WHERE paperwork=5 and tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_signed $where_assessor;");
        $total_overdue = DAO::getSingleValue($link,"SELECT COUNT(*) FROM assessment_plan_log WHERE paperwork=6 and tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_due $where_assessor;");
        $total_plans = $total_plans_completed + $total_in_progress + $total_awaiting_marking + $total_rework_required + $total_iqa_rejected + $total_overdue;

        // AP Progress On Track/ Behind
        $ontrack = Array();
        $behind = Array();

    $queryap = "SELECT tr.id AS tr_id
,(SELECT COUNT(*) FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS max_month_row
,(SELECT max_month FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS max_month
,(SELECT aps FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS aps
,IF((DAY(start_date)<=13), ((PERIOD_DIFF(DATE_FORMAT(CURDATE(),\"%Y%m\"),DATE_FORMAT(start_date,\"%Y%m\")))+1), ((PERIOD_DIFF(DATE_FORMAT(CURDATE(),\"%Y%m\"),DATE_FORMAT(start_date,\"%Y%m\"))))) AS current_training_month
,(SELECT MAX(aps) FROM ap_percentage WHERE course_id = courses.id) AS total_units
,(SELECT COUNT(*) FROM assessment_plan_log WHERE tr_id = tr.id AND paperwork = '3') AS passed_units
,(select count(*) from assessment_plan_log where tr_id = tr.id) as total_plans
,(SELECT id FROM ap_percentage WHERE course_id = courses.id AND current_training_month BETWEEN min_month AND max_month) AS month_row_id
,(SELECT aps FROM ap_percentage WHERE course_id = courses.id AND id < month_row_id ORDER BY id desc LIMIT 1) AS aps_to_check
FROM tr
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
LEFT JOIN courses ON courses.id = courses_tr.`course_id`
WHERE courses.id not in (349) and status_code = 1 $where_assessor;
";

        $stap = $link->query($queryap);
        if($stap)
        {
            while($rowap = $stap->fetch())
            {
                if($rowap['total_plans']=='0')
                    continue;
                $status = "red";
                if($rowap['current_training_month']=='0')
                    $status="green";
                elseif($rowap['current_training_month'] > $rowap['max_month'] and $rowap['passed_units'] >= $rowap['aps'])
                    $status = "green";
                elseif($rowap['current_training_month'] > $rowap['max_month'] and $rowap['passed_units'] < $rowap['aps'])
                    $status = "red";
                elseif($rowap['aps_to_check']=='' or $rowap['passed_units'] >= $rowap['aps_to_check'])
                    $status = "green";
                if($status=='green')
                    $ontrack[]=$rowap['tr_id'];
                else
                    $behind[]=$rowap['tr_id'];
            }
        }
        $total_on_track = count($ontrack);
        $total_behind = count($behind);
        $ontracktrs = implode(",",$ontrack);
        $behindtrs = implode(",",$behind);


            // End
        $html = '';
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$total_plans</h1>
			<p>Total Plans</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$total_plans_completed ($total_plans_completed_timely timely)</h1>
			<p>Total number completed</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_assessment_plan_logs&_reset=1&ViewAssessmentPlanLogs_filter_from_signed_off_date=$start_date&ViewAssessmentPlanLogs_filter_to_signed_off_date=$end_date&ViewAssessmentPlanLogs_filter_assessor=$assessor&ViewAssessmentPlanLogs_filter_paperwork=3" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$total_in_progress</h1>
			<p>Total in progress</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_assessment_plan_logs&_reset=1&ViewAssessmentPlanLogs_due_start_date=$start_date&ViewAssessmentPlanLogs_due_end_date=$end_date&ViewAssessmentPlanLogs_filter_assessor=$assessor&ViewAssessmentPlanLogs_filter_paperwork=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-blue">
		<div class="inner">
			<h1>$total_awaiting_marking</h1>
			<p>Total awaiting marking</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_assessment_plan_logs&_reset=1&ViewAssessmentPlanLogs_last_start_date=$start_date&ViewAssessmentPlanLogs_last_end_date=$end_date&ViewAssessmentPlanLogs_filter_assessor=$assessor&ViewAssessmentPlanLogs_filter_paperwork=2" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$total_rework_required</h1>
			<p>Total rework required</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_assessment_plan_logs&_reset=1&ViewAssessmentPlanLogs_due_start_date=$start_date&ViewAssessmentPlanLogs_due_end_date=$end_date&ViewAssessmentPlanLogs_filter_assessor=$assessor&ViewAssessmentPlanLogs_filter_paperwork=4" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$total_iqa_rejected</h1>
			<p>Total ready for IQA</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_assessment_plan_logs&_reset=1&ViewAssessmentPlanLogs_filter_from_signed_off_date=$start_date&ViewAssessmentPlanLogs_filter_to_signed_off_date=$end_date&ViewAssessmentPlanLogs_filter_assessor=$assessor&ViewAssessmentPlanLogs_filter_paperwork=5" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h1>$total_overdue</h1>
			<p>Total overdue</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_assessment_plan_logs&_reset=1&ViewAssessmentPlanLogs_due_start_date=$start_date&ViewAssessmentPlanLogs_due_end_date=$end_date&ViewAssessmentPlanLogs_filter_assessor=$assessor&ViewAssessmentPlanLogs_filter_paperwork=6" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-lime">
		<div class="inner">
			<h1>$total_on_track</h1>
			<p>Learners On-track</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_assessment_plan_logs&_reset=1&ViewAssessmentPlanLogs_filter_trs=$ontracktrs" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$total_behind</h1>
			<p>Learners Behind</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_assessment_plan_logs&_reset=1&ViewAssessmentPlanLogs_filter_trs=$behindtrs" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        //<a href="" onclick="navToViewAssessmentPlanLogs('$behindtrs');" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>-->

        return $html;
    }

    private function showReviewProgress(PDO $link)
    {
        $fromDate = $_REQUEST['fromDate'];
        $start_date = Date::toMySQL($fromDate);

        $toDate = $_REQUEST['toDate'];
        $end_date = Date::toMySQL($toDate);

        $due_date_where = " and due_date between '$start_date' and '$end_date'";
        $meeting_date_where = " and meeting_date between '$start_date' and '$end_date'";
        $assessor = isset($_REQUEST['assessor'])?$_REQUEST['assessor']:'';
        $assessor_where = '';
        if($assessor!='')
            $assessor_where = " and tr_id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR groups.assessor='$assessor')";


        $total_learner_incomplete = DAO::getSingleValue($link,  "SELECT COUNT(distinct assessor_review.id) FROM assessor_review LEFT JOIN tr on tr.id = assessor_review.tr_id LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id LEFT JOIN assessor_review_forms_assessor4 ON assessor_review_forms_assessor4.`review_id` = assessor_review.id WHERE signature_learner_font IS NULL and signature_assessor_font is not null and tr.status_code = 1 $assessor_where;");
        $total_employer_incompleted = DAO::getSingleValue($link,"SELECT COUNT(DISTINCT assessor_review.id) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id LEFT JOIN assessor_review_forms_employer ON assessor_review_forms_employer.`review_id` = assessor_review.id LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id WHERE signature_learner_font IS NOT NULL AND signature_employer_font IS NULL AND tr.status_code = 1 $assessor_where;");
        $total_incomplete = DAO::getSingleValue($link,          "SELECT (SELECT COUNT(distinct assessor_review.id) FROM assessor_review LEFT JOIN tr on tr.id = assessor_review.tr_id LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id LEFT JOIN assessor_review_forms_assessor4 ON assessor_review_forms_assessor4.`review_id` = assessor_review.id WHERE signature_learner_font IS NULL and signature_assessor_font is not null and tr.status_code = 1 $assessor_where)+(SELECT COUNT(DISTINCT assessor_review.id) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id LEFT JOIN assessor_review_forms_employer ON assessor_review_forms_employer.`review_id` = assessor_review.id LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id WHERE signature_learner_font IS NOT NULL AND signature_employer_font IS NULL AND tr.status_code = 1 $assessor_where)");
        $total_awaiting_learner_signature_24 = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM forms_audit LEFT JOIN assessor_review ON assessor_review.id = forms_audit.`form_id` LEFT JOIN tr on tr.id = assessor_review.tr_id WHERE description = \"Review Form Emailed to Learner\" AND DATE_ADD(`date`, INTERVAL 1 DAY) < NOW() AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_learner WHERE signature_learner_font IS NOT NULL) and status_code = 1 $assessor_where;");
        $total_awaiting_employer_signature_7 = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM forms_audit LEFT JOIN assessor_review ON assessor_review.id = forms_audit.`form_id` LEFT JOIN tr on tr.id = assessor_review.tr_id WHERE description = \"Review Form Emailed to Employer\" AND DATE_ADD(`date`, INTERVAL 7 DAY) < NOW() AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_employer WHERE signature_employer_font IS NOT NULL) and status_code = 1 $assessor_where;");
        $total_aged = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM forms_audit LEFT JOIN assessor_review ON assessor_review.id = forms_audit.`form_id` left join tr on tr.id = assessor_review.tr_id WHERE tr.status_code = 1 and description = \"Review Form Emailed to Employer\" AND DATE_ADD(`date`, INTERVAL 5 WEEK) < NOW() AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_employer WHERE signature_employer_font IS NOT NULL) and status_code = 1 $assessor_where;");
        $total_due = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM assessor_review left join forms_audit on assessor_review.tr_id = forms_audit.form_id left join tr on tr.id = assessor_review.tr_id WHERE tr.status_code = 1 $due_date_where $assessor_where;");
        $total_gone_ahead = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM assessor_review left join tr on tr.id = assessor_review.tr_id LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id LEFT JOIN assessor_review_forms_assessor4 ON assessor_review_forms_assessor4.review_id = assessor_review.id WHERE tr.status_code = 1 and signature_assessor_font is not null $meeting_date_where $assessor_where;");
        $total_completed = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM assessor_review left join tr on tr.id = assessor_review.tr_id LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id LEFT JOIN assessor_review_forms_employer ON assessor_review_forms_employer.review_id = assessor_review.id WHERE tr.status_code = 1 and signature_employer_font is not null $meeting_date_where $assessor_where;");
        $total_no_contact = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM assessor_review WHERE tr_id IN (SELECT tr.id FROM tr LEFT JOIN student_frameworks ON student_frameworks.`tr_id` = tr.id LEFT JOIN frameworks ON frameworks.id = student_frameworks.`id` WHERE target_date >= now() and status_code = 1 AND ((DATE_ADD(start_date, INTERVAL 12 MONTH) > NOW() AND frameworks.framework_type IN (2,3,25)) OR (DATE_ADD(start_date, INTERVAL 18 MONTH) > NOW() AND frameworks.framework_type IN (20)))) AND DATE_ADD((DATE_ADD(`meeting_date`, INTERVAL 6 WEEK)), INTERVAL (9-DAYOFWEEK((DATE_ADD(`meeting_date`, INTERVAL 6 WEEK)))) DAY) <= NOW() AND meeting_date=(SELECT MAX(meeting_date) FROM assessor_review AS ar2 WHERE ar2.tr_id = assessor_review.tr_id) $assessor_where;");

        $html = '';
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$total_learner_incomplete</h1>
			<p>Total learners incomplete</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_audit=4&ViewReviewProgress_filter_signature=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-purple">
		<div class="inner">
			<h1>$total_employer_incompleted</h1>
			<p>Total employers incomplete</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_signature=2" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-blue">
		<div class="inner">
			<h1>$total_incomplete</h1>
			<p>Total incomplete</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_audit=3" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$total_awaiting_learner_signature_24</h1>
			<p>Awaiting learner signature (24 hours)</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_signature=4" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$total_awaiting_employer_signature_7</h1>
			<p>Awaiting employer signature (7 days)</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_signature=5" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h1>$total_aged</h1>
			<p>Awaiting signature (5 weeks)</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_signature=6" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-maroon">
		<div class="inner">
			<h1>$total_due</h1>
			<p>Total due this month</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_from_marked_date=$start_date&ViewReviewProgress_filter_to_marked_date=$end_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-lime">
		<div class="inner">
			<h1>$total_gone_ahead</h1>
			<p>Total gone ahead</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_signature=10&ViewReviewProgress_last_start_date=$start_date&ViewReviewProgress_last_end_date=$end_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-olive">
		<div class="inner">
			<h1>$total_no_contact</h1>
			<p>No contact 6 weeks</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_signature=9" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-olive">
		<div class="inner">
			<h1>$total_completed</h1>
			<p>Total completed</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_signature=8&ViewReviewProgress_last_start_date=$start_date&ViewReviewProgress_last_end_date=$end_date" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        return $html;
    }
}