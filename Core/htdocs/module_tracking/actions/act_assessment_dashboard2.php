<?php
class assessment_dashboard2 implements IAction
{
    public function execute(PDO $link)
    {
        pre(1);
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
        if($subaction == 'showReworkData')
        {
            echo $this->showReworkData($link);
            exit;
        }
        if($subaction == 'showAdditionalSupport')
        {
            echo $this->showAdditionalSupport($link);
            exit;
        }
        if($subaction == 'showLearners')
        {
            echo $this->showLearners($link);
            exit;
        }

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=assessment_dashboard2", "Induction Dashboard");

        $first_date = date('Y-m-d',strtotime("first day of this month"));
        $last_date = date('Y-m-d',strtotime("last day of this month"));

        $assessor_sql = <<<HEREDOC
SELECT DISTINCT users.id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE (groups.assessor=',users.id,' and tr.assessor is null) OR tr.assessor=' , users.id)
FROM users
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
WHERE
users.id IN (SELECT assessor FROM tr WHERE status_code = 1 AND assessor IS NOT NULL)
OR
(users.id IN (SELECT assessor FROM groups WHERE assessor IN (SELECT assessor FROM groups WHERE id IN (SELECT groups_id FROM group_members WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))))
AND users.id NOT IN ((SELECT assessor FROM tr WHERE status_code = 1 AND assessor IS NOT NULL)))
AND users.id NOT IN (20761, 22951, 22121)
ORDER BY firstnames, surname;
HEREDOC;
        $assessors = DAO::getResultset($link,$assessor_sql);

        $manager_sql = <<<HEREDOC
SELECT DISTINCT users.username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1)
FROM users
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
WHERE
users.username IN (SELECT supervisor FROM users WHERE supervisor IS NOT NULL)
ORDER BY firstnames, surname;
HEREDOC;
        $managers = DAO::getResultset($link,$manager_sql);

        require_once('tpl_assessment_dashboard.php');
    }


    private function showInductionDashPanels(PDO $link)
    {
        $fromDate = $_REQUEST['fromDate'];
        $start_date = Date::toMySQL($fromDate);

        $toDate = $_REQUEST['toDate'];
        $end_date = Date::toMySQL($toDate);

        $where_actual = " and submission_date between '$start_date' and '$end_date'";
        $where_signed = " and completion_date between '$start_date' and '$end_date'";
        $where_due = " and sub.due_date between '$start_date' and '$end_date'";
        $where_signed = " and assessor_signed_off between '$start_date' and '$end_date'";
        $where_marked = " and assessment_plan_log_submissions.marked_date between '$start_date' and '$end_date'";
        $where_markedev = " and project_submissions.marked_date between '$start_date' and '$end_date'";


        $assessor = isset($_REQUEST['assessor'])?$_REQUEST['assessor']:'';
        $manager = isset($_REQUEST['manager'])?$_REQUEST['manager']:'';
        $where_assessor = "";
        $where_assessorev = "";
        $where_assessor2 = "";
        $where_assessor2ev = "";
        $where_manager = "";
        if($manager!='')
        {
            $where_manager = " and tr_id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
        }
        if($assessor!='')
        {
            $where_assessor = " and tr_id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR (tr.assessor is null and groups.assessor='$assessor'))";
            $where_assessor2 = " and assessment_plan_log_submissions.assessor = '$assessor' ";
            $where_assessorev = " and tr_id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR (tr.assessor is null and groups.assessor='$assessor'))";
            $where_assessor2ev = " and project_submissions.assessor = '$assessor' ";
        }

        $total_signed_off = DAO::getSingleValue($link,"SELECT COUNT(*)
FROM assessment_plan_log_submissions
INNER JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_id
INNER JOIN tr ON tr.id = assessment_plan_log.`tr_id`
WHERE status_code = 1  $where_assessor2 $where_signed $where_manager;");

        $total_signed_off_ev = DAO::getSingleValue($link,"SELECT COUNT(*)
FROM project_submissions
INNER JOIN tr_projects ON tr_projects.id = project_id
INNER JOIN tr ON tr.id = tr_projects.`tr_id`
WHERE status_code = 1 $where_assessor2ev $where_signed $where_manager;");

        $gt_signed_off = $total_signed_off + $total_signed_off_ev;

        $total_marked = DAO::getSingleValue($link,"SELECT COUNT(*)
FROM assessment_plan_log_submissions
INNER JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_id
INNER JOIN tr ON tr.id = assessment_plan_log.`tr_id`
WHERE status_code = 1 $where_assessor2  $where_marked $where_manager;");

        $total_markedev = DAO::getSingleValue($link,"SELECT COUNT(*)
FROM project_submissions
INNER JOIN tr_projects ON tr_projects.id = project_id
INNER JOIN tr ON tr.id = tr_projects.`tr_id`
WHERE status_code = 1 $where_assessor2ev  $where_markedev $where_manager;");

        $gt_marked = $total_marked + $total_markedev;

        $total_plans_completed = DAO::getSingleValue($link,"SELECT count(*)
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
WHERE  sub.`completion_date` IS NOT NULL
AND tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_assessor $where_manager; ");

        $total_plans_completedev = DAO::getSingleValue($link,"SELECT count(*)
FROM tr_projects
LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
	sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
WHERE  sub.`completion_date` IS NOT NULL
AND tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_assessorev $where_manager; ");


/*
        $total_plans_completed_timely = DAO::getSingleValue($link,"SELECT count(*)
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
WHERE  sub.`completion_date` IS NOT NULL
AND tr_id IN (SELECT id FROM tr WHERE status_code = 1) and completion_date <= sub.due_date and tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_assessor $where_manager;");
*/
        $total_in_progress = DAO::getSingleValue($link,"SELECT count(*)
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
WHERE  (SELECT COUNT(*) FROM assessment_plan_log_submissions WHERE assessment_plan_id = assessment_plan_log.id)=1 and sub.`completion_date` IS NULL AND iqa_status is null and sent_iqa_date IS NULL AND submission_date IS NULL AND set_date IS NOT NULL and COALESCE(iqa_status, 0)!=2
AND sub.due_date >= CURDATE() AND tr_id IN (SELECT id FROM tr WHERE status_code = 1)
 $where_assessor $where_manager;");

        $total_in_progressev = DAO::getSingleValue($link,"SELECT count(*)
FROM tr_projects
LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
	sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
WHERE  (SELECT COUNT(*) FROM project_submissions WHERE project_id = tr_projects.id)=1 and sub.`completion_date` IS NULL AND iqa_status is null and sent_iqa_date IS NULL AND submission_date IS NULL AND set_date IS NOT NULL and COALESCE(iqa_status, 0)!=2
AND sub.due_date >= CURDATE() AND tr_id IN (SELECT id FROM tr WHERE status_code = 1)
 $where_assessorev $where_manager;");

        $gt_total_in_progress = $total_in_progress + $total_in_progressev;

        $total_awaiting_marking = DAO::getSingleValue($link,"SELECT count(*)
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
WHERE  sub.`completion_date` IS NULL AND COALESCE(iqa_status, 0)!=2 AND sent_iqa_date IS NULL AND submission_date IS NOT NULL
AND tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_assessor $where_manager;");

        $total_awaiting_markingev = DAO::getSingleValue($link,"SELECT count(*)
FROM tr_projects
LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
	sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
WHERE  sub.`completion_date` IS NULL AND COALESCE(iqa_status, 0)!=2 AND sent_iqa_date IS NULL AND submission_date IS NOT NULL
AND tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_assessorev $where_manager;");

        $gt_awaiting_marking = $total_awaiting_marking + $total_awaiting_markingev;

        $total_rework_required = DAO::getSingleValue($link,"SELECT COUNT(*) FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
WHERE  (COALESCE(iqa_status, 0)=2 OR
(
	(SELECT COUNT(*) FROM assessment_plan_log_submissions WHERE assessment_plan_id = assessment_plan_log.id)>1
	AND sub.`completion_date` IS NULL AND iqa_status IS NULL AND sent_iqa_date IS NULL AND submission_date IS NULL AND set_date IS NOT NULL and sub.due_date >= CURDATE()
))
AND tr_id IN (SELECT id FROM tr WHERE status_code = 1)
 $where_assessor $where_manager;");

        $total_rework_requiredev = DAO::getSingleValue($link,"SELECT COUNT(*) FROM tr_projects
LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
	sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
WHERE  (COALESCE(iqa_status, 0)=2 OR
(
	(SELECT COUNT(*) FROM project_submissions WHERE project_id = tr_projects.id)>1
	AND sub.`completion_date` IS NULL AND iqa_status IS NULL AND sent_iqa_date IS NULL AND submission_date IS NULL AND set_date IS NOT NULL and sub.due_date >= CURDATE()
))
AND tr_id IN (SELECT id FROM tr WHERE status_code = 1)
 $where_assessorev $where_manager;");

        $gt_rework_required = $total_rework_required + $total_rework_requiredev;

        $total_iqa_rejected = DAO::getSingleValue($link,"SELECT count(*)
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
WHERE  sub.`sent_iqa_date` IS not NULL AND COALESCE(iqa_status, 0)!=2 and completion_date is null
AND tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_assessor $where_manager;");

        $total_iqa_rejectedev = DAO::getSingleValue($link,"SELECT count(*)
FROM tr_projects
LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
	sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
WHERE  sub.`sent_iqa_date` IS not NULL AND COALESCE(iqa_status, 0)!=2 and completion_date is null
AND tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_assessorev $where_manager;");

        $gt_iqa_rejected = $total_iqa_rejected + $total_iqa_rejectedev;

        $total_iqa_rejected_trs = DAO::getSingleValue($link,"SELECT group_concat(tr_id)
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
WHERE  sub.`sent_iqa_date` IS not NULL AND COALESCE(iqa_status, 0)!=2 and completion_date is null
AND tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_assessor $where_manager;");

        $total_iqa_rejected_trsev = DAO::getSingleValue($link,"SELECT group_concat(tr_id)
FROM tr_projects
LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
	sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
WHERE  sub.`sent_iqa_date` IS not NULL AND COALESCE(iqa_status, 0)!=2 and completion_date is null
AND tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_assessorev $where_manager;");

        $total_iqa_rejected_trs_array = explode(",",$total_iqa_rejected_trs);
        $total_iqa_rejected_trs_arrayev = explode(",",$total_iqa_rejected_trsev);

        $total_overdue = DAO::getSingleValue($link,"SELECT count(*)
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
WHERE  sub.`completion_date` IS NULL AND sent_iqa_date IS NULL AND submission_date IS NULL AND set_date IS NOT NULL and COALESCE(iqa_status, 0)!=2
AND sub.due_date < CURDATE() AND tr_id IN (SELECT id FROM tr WHERE status_code = 1)
AND tr_id NOT IN (SELECT tr_id FROM tr_operations WHERE tr_operations.tr_id = assessment_plan_log.tr_id AND extractvalue(tr_operations.bil_details, '/Notes/Note[last()]/Type')='F')
  $where_assessor $where_manager;");

        $total_overdueev = DAO::getSingleValue($link,"SELECT count(*)
FROM tr_projects
LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
	sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
WHERE  sub.`completion_date` IS NULL AND sent_iqa_date IS NULL AND submission_date IS NULL AND set_date IS NOT NULL and COALESCE(iqa_status, 0)!=2
AND sub.due_date < CURDATE() AND tr_id IN (SELECT id FROM tr WHERE status_code = 1)
AND tr_id NOT IN (SELECT tr_id FROM tr_operations WHERE tr_operations.tr_id = tr_projects.tr_id AND extractvalue(tr_operations.bil_details, '/Notes/Note[last()]/Type')='F')
  $where_assessorev $where_manager;");

        $gt_total_overdue = $total_overdue + $total_overdueev;

        $due_today = DAO::getSingleValue($link,"SELECT count(*)
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id AND assessment_plan_log_submissions.submission_date IS NULL)
        WHERE due_date between '$start_date' and '$end_date' $where_assessor $where_manager;");

        $due_todayev = DAO::getSingleValue($link,"SELECT count(*)
FROM tr_projects
LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
	sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id AND project_submissions.submission_date IS NULL)
        WHERE due_date between '$start_date' and '$end_date' $where_assessorev $where_manager;");

        $gt_due_today = $due_today + $due_todayev;

        $total_plans = $total_plans_completed + $total_in_progress + $total_awaiting_marking + $total_rework_required + $total_iqa_rejected + $total_overdue;
        $total_plansev = $total_plans_completedev + $total_in_progressev + $total_awaiting_markingev + $total_rework_requiredev + $total_iqa_rejectedev + $total_overdueev;

        // AP Progress On Track/ Behind
        $ontrack = Array();
        $behind = Array();
        $behind_iqa = Array();

        $queryap = "SELECT tr.id AS tr_id, courses.id as course_id
,(SELECT COUNT(*) FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS max_month_row
,(SELECT max_month FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS max_month
,(SELECT aps FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS aps
#,IF((DAY(start_date)<=13), ((PERIOD_DIFF(DATE_FORMAT(CURDATE(),\"%Y%m\"),DATE_FORMAT(start_date,\"%Y%m\")))+1), ((PERIOD_DIFF(DATE_FORMAT(CURDATE(),\"%Y%m\"),DATE_FORMAT(start_date,\"%Y%m\"))))) AS current_training_month
,(SELECT MAX(aps) FROM ap_percentage WHERE course_id = courses.id) AS total_units
,(SELECT COUNT(*)
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
WHERE  sub.`completion_date` IS NOT NULL
AND tr_id = tr.id) AS passed_units
,(select count(*) from assessment_plan_log where tr_id = tr.id) as total_plans
#,(SELECT id FROM ap_percentage WHERE course_id = courses.id AND current_training_month BETWEEN min_month AND max_month) AS month_row_id
#,(SELECT aps FROM ap_percentage WHERE course_id = courses.id AND id < month_row_id ORDER BY id desc LIMIT 1) AS aps_to_check
FROM tr
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
LEFT JOIN courses ON courses.id = courses_tr.`course_id`
WHERE
status_code = 1 and courses.programme_type=2 and assessment_evidence = 1
AND tr.id NOT IN (SELECT tr_id FROM tr_operations WHERE tr_operations.tr_id = tr.id AND extractvalue(tr_operations.bil_details, '/Notes/Note[last()]/Type')='F')
$where_assessor $where_manager;
";

        $stap = $link->query($queryap);
        if($stap)
        {
            while($rowap = $stap->fetch())
            {
                $rowap['current_training_month'] = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $rowap['tr_id']);
                $rowap['month_row_id'] = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = {$rowap['course_id']} AND {$rowap['current_training_month']} BETWEEN min_month AND max_month");
                if($rowap['month_row_id']!='')
                    $rowap['aps_to_check'] = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = {$rowap['course_id']} AND id < {$rowap['month_row_id']} ORDER BY id desc LIMIT 1");
                else
                    $rowap['aps_to_check'] = "";

                //if($rowap['total_plans']=='0')
                //    continue;
                $status = "red";
                if($rowap['current_training_month']=='0')
                    $status="green";
                elseif($rowap['current_training_month'] > $rowap['max_month'] and $rowap['passed_units'] >= $rowap['aps'])
                    $status = "green";
                elseif($rowap['current_training_month'] > $rowap['max_month'] and $rowap['passed_units'] < $rowap['aps'])
                    $status = "red";
                elseif($rowap['aps_to_check']=='' or $rowap['passed_units'] >= $rowap['aps_to_check'])
                    $status = "green";

                $tr_id = $rowap['tr_id'];
                $exempt = DAO::getSingleValue($link, "SELECT DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y') FROM op_epa WHERE op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y' ORDER BY id DESC LIMIT 1;");

                if($exempt=="")
                {
                    if($status=='green')
                        $ontrack[]=$rowap['tr_id'];
                    else
                    {
                        if(in_array($rowap['tr_id'],$total_iqa_rejected_trs_array))
                            $behind_iqa[]=$rowap['tr_id'];
                    }
                    if($status=="red")
                        $behind[]=$rowap['tr_id'];
                }
            }
        }
        $total_on_track = count($ontrack);
        $total_behind = count($behind);
        $total_behind_iqa = count($behind_iqa);
        $ontracktrs = implode(",",$ontrack);
        $behindtrs = implode(",",$behind);
        $behindiqatrs = implode(",",$behind_iqa);


        $queryapev = "SELECT tr.id AS tr_id, courses.id as course_id
,(SELECT COUNT(*) FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS max_month_row
,(SELECT max_month FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS max_month
,(SELECT aps FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS aps
#,IF((DAY(start_date)<=13), ((PERIOD_DIFF(DATE_FORMAT(CURDATE(),\"%Y%m\"),DATE_FORMAT(start_date,\"%Y%m\")))+1), ((PERIOD_DIFF(DATE_FORMAT(CURDATE(),\"%Y%m\"),DATE_FORMAT(start_date,\"%Y%m\"))))) AS current_training_month
,(SELECT MAX(aps) FROM ap_percentage WHERE course_id = courses.id) AS total_units
,(SELECT COUNT(*)
FROM tr_projects
LEFT JOIN project_submissions AS sub ON sub.project_id = tr_projects.id AND
	sub.id = (SELECT MAX(id) FROM project_submissions WHERE project_submissions.project_id = tr_projects.id)
WHERE  sub.`completion_date` IS NOT NULL
AND tr_id = tr.id) AS passed_units
,(select count(*) from tr_projects where tr_id = tr.id) as total_plans
#,(SELECT id FROM ap_percentage WHERE course_id = courses.id AND current_training_month BETWEEN min_month AND max_month) AS month_row_id
#,(SELECT aps FROM ap_percentage WHERE course_id = courses.id AND id < month_row_id ORDER BY id desc LIMIT 1) AS aps_to_check
FROM tr
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
LEFT JOIN courses ON courses.id = courses_tr.`course_id`
WHERE
status_code = 1 and courses.programme_type=2 and courses.assessment_evidence = 2
AND tr.id NOT IN (SELECT tr_id FROM tr_operations WHERE tr_operations.tr_id = tr.id AND extractvalue(tr_operations.bil_details, '/Notes/Note[last()]/Type')='F')
$where_assessorev $where_manager;
";
	    $ontrackev = [];
        $behindev = [];
        $behind_iqaev = [];
        $stapev = $link->query($queryapev);
        if($stapev)
        {
            while($rowapev = $stapev->fetch())
            {
                $rowapev['current_training_month'] = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $rowapev['tr_id']);
                $rowapev['month_row_id'] = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = {$rowapev['course_id']} AND {$rowapev['current_training_month']} BETWEEN min_month AND max_month");
                if($rowapev['month_row_id']!='')
                    $rowapev['aps_to_check'] = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = {$rowapev['course_id']} AND id < {$rowapev['month_row_id']} ORDER BY id desc LIMIT 1");
                else
                    $rowapev['aps_to_check'] = "";

                //if($rowap['total_plans']=='0')
                //    continue;
                $statusev = "red";
                if($rowapev['current_training_month']=='0')
                    $statusev="green";
                elseif($rowapev['current_training_month'] > $rowapev['max_month'] and $rowapev['passed_units'] >= $rowapev['aps'])
                    $statusev = "green";
                elseif($rowapev['current_training_month'] > $rowapev['max_month'] and $rowapev['passed_units'] < $rowapev['aps'])
                    $statusev = "red";
                elseif($rowapev['aps_to_check']=='' or $rowapev['passed_units'] >= $rowapev['aps_to_check'])
                    $statusev = "green";

                $tr_id = $rowapev['tr_id'];
                $exemptev = DAO::getSingleValue($link, "SELECT DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y') FROM op_epa WHERE op_epa.`tr_id` = '$tr_id' AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y' ORDER BY id DESC LIMIT 1;");

                if($exemptev=="")
                {
                    if($statusev=='green')
                        $ontrackev[]=$rowapev['tr_id'];
                    else
                    {
                        if(in_array($rowapev['tr_id'],$total_iqa_rejected_trs_arrayev))
                            $behind_iqaev[]=$rowapev['tr_id'];
                    }
                    if($statusev=="red")
                        $behindev[]=$rowapev['tr_id'];
                }
            }
        }
        $total_on_trackev = count($ontrackev);
        $total_behindev = count($behindev);
        $total_behind_iqaev = count($behind_iqaev);
        $ontracktrsev = implode(",",$ontrackev);
        $behindtrsev = implode(",",$behindev);
        $behindiqatrsev = implode(",",$behind_iqaev);


$mq = "SELECT COUNT(*)
FROM assessment_plan_log_submissions
INNER JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_id
INNER JOIN tr ON tr.id = assessment_plan_log.`tr_id`
WHERE status_code = 1 $where_assessor2  $where_marked $where_manager;";

        // End
        $html = '';
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$total_signed_off + $total_signed_off_ev = $gt_signed_off</h1>
			<p>Total LM signed-off</p>
		</div>
    	<div>
    	    <b>
                <div class="col-xs-6 bg-success"><a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_assessor_signed_off=$start_date&ViewAPSubmission_filter_to_assessor_signed_off=$end_date&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_person_reviewed=$assessor" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
                <div class="col-xs-6 bg-success"><a href="do.php?_action=view_evidence_matrix_submissions&_reset=1&ViewEvidenceMatrixSubmissions_filter_from_assessor_signed_off=$start_date&ViewEvidenceMatrixSubmissions_filter_to_assessor_signed_off=$end_date&ViewEvidenceMatrixSubmissions_filter_manager=$manager&ViewEvidenceMatrixSubmissions_filter_person_reviewed=$assessor" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
            </b>
		</div>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$total_marked + $total_markedev = $gt_marked</h1>
			<p>Total marked</p>
		</div>
    	<div>
    	    <b>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_evidence_matrix_submissions&_reset=1&ViewEvidenceMatrixSubmissions_filter_from_marked_date=$start_date&ViewEvidenceMatrixSubmissions_filter_to_marked_date=$end_date&ViewEvidenceMatrixSubmissions_filter_person_reviewed=$assessor&ViewEvidenceMatrixSubmissions_filter_manager=$manager" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
            </b>
		</div>
	</div>
</div>

HTML;

/*        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$total_plans_completed ($total_plans_completed_timely)</h1>
			<p>Total number completed</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_assessment_plan_logs2&_reset=1&ViewAssessmentPlanLogs2_filter_paperwork=3&ViewAssessmentPlanLogs2_filter_assessor=$assessor&ViewAssessmentPlanLogs2_filter_manager=$manager" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML; */
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$total_in_progress + $total_in_progressev = $gt_total_in_progress</h1>
			<p>Total in progress</p>
		</div>
    	<div>
    	    <b>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_assessment_plan_logs2&_reset=1&ViewAssessmentPlanLogs2_filter_assessor=$assessor&ViewAssessmentPlanLogs2_filter_manager=$manager&ViewAssessmentPlanLogs2_filter_paperwork=1" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_evidence_matrix_projects&_reset=1&ViewEvidenceMatrixProjects_filter_assessor=$assessor&ViewEvidenceMatrixProjects_filter_manager=$manager&ViewEvidenceMatrixProjects_filter_paperwork=1" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
            </b>
		</div>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-blue">
		<div class="inner">
			<h1>$total_awaiting_marking + $total_awaiting_markingev = $gt_awaiting_marking</h1>
			<p>Total awaiting marking</p>
		</div>
    	<div>
    	    <b>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_assessment_plan_logs2&_reset=1&ViewAssessmentPlanLogs2_filter_assessor=$assessor&ViewAssessmentPlanLogs2_filter_manager=$manager&ViewAssessmentPlanLogs2_filter_paperwork=2" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_evidence_matrix_projects&_reset=1&ViewEvidenceMatrixProjects_filter_assessor=$assessor&ViewEvidenceMatrixProjects_filter_manager=$manager&ViewEvidenceMatrixProjects_filter_paperwork=2" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
            </b>
		</div>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$total_rework_required + $total_rework_requiredev = $gt_rework_required</h1>
			<p>Total rework required</p>
		</div>
    	<div>
    	    <b>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_assessment_plan_logs2&_reset=1&ViewAssessmentPlanLogs2_filter_assessor=$assessor&ViewAssessmentPlanLogs2_filter_manager=$manager&ViewAssessmentPlanLogs2_filter_paperwork=4" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_evidence_matrix_projects&_reset=1&ViewEvidenceMatrixProjects_filter_assessor=$assessor&ViewEvidenceMatrixProjects_filter_manager=$manager&ViewEvidenceMatrixProjects_filter_paperwork=4" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
            </b>
		</div>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$total_iqa_rejected + $total_iqa_rejectedev = $gt_iqa_rejected </h1>
			<p>Total ready for IQA</p>
		</div>
    	<div>
    	    <b>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_assessment_plan_logs2&_reset=1&ViewAssessmentPlanLogs2_filter_assessor=$assessor&ViewAssessmentPlanLogs2_filter_manager=$manager&ViewAssessmentPlanLogs2_filter_paperwork=5" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_evidence_matrix_projects&_reset=1&ViewEvidenceMatrixProjects_filter_assessor=$assessor&ViewEvidenceMatrixProjects_filter_manager=$manager&ViewEvidenceMatrixProjects_filter_paperwork=5" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
            </b>
		</div>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h1>$total_overdue + $total_overdueev = $gt_total_overdue</h1>
			<p>Total overdue</p>
		</div>
    	<div>
    	    <b>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_assessment_plan_logs2&_reset=1&ViewAssessmentPlanLogs2_filter_assessor=$assessor&ViewAssessmentPlanLogs2_filter_manager=$manager&ViewAssessmentPlanLogs2_filter_paperwork=6" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_evidence_matrix_projects&_reset=1&ViewEvidenceMatrixProjects_filter_assessor=$assessor&ViewEvidenceMatrixProjects_filter_manager=$manager&ViewEvidenceMatrixProjects_filter_paperwork=6" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
            </b>
		</div>
	</div>
</div>
HTML;
        $behind_ontrack = $total_on_track + $total_behind;
        $behind_ontrack = ($behind_ontrack==0)?1:$behind_ontrack;
        $on_track_percentage = round(($total_on_track / ($behind_ontrack) * 100),2);

        $behind_ontrackev = $total_on_trackev + $total_behindev;
        $behind_ontrackev = ($behind_ontrackev==0)?1:$behind_ontrackev;
        $on_track_percentageev = round(($total_on_trackev / ($behind_ontrackev) * 100),2);

        $gt_on_track = $total_on_track + $total_on_trackev;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-lime">
		<div class="inner">
			<h1>$total_on_track + $total_on_trackev = $gt_on_track<h1>
			<p>Learners On-track</p>
		</div>
    	<div>
    	    <b>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_assessment_plan_logs2&_reset=1&ViewAssessmentPlanLogs2_filter_trs=$ontracktrs" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_evidence_matrix_projects&_reset=1&ViewEvidenceMatrixProjects_filter_trs=$ontracktrsev" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
            </b>
		</div>
	</div>
</div>
HTML;

        $behind_percentage = 100 - $on_track_percentage;
        $behind_percentageev = 100 - $on_track_percentageev;
        $gt_behindev = $total_behind + $total_behindev;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$total_behind + $total_behindev = $gt_behindev</h1>
			<p>Learners Behind</p>
		</div>
    	<div>
    	    <b>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_assessment_plan_logs2&_reset=1&ViewAssessmentPlanLogs2_filter_trs=$behindtrs" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_evidence_matrix_projects&_reset=1&ViewEvidenceMatrixProjects_filter_trs=$behindtrsev" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
            </b>
		</div>
	</div>
</div>
HTML;

        $current_date = date('Y-m-d');

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$due_today + $due_todayev = $gt_due_today</h1>
			<p>Plans due today</p>
		</div>
    	<div>
    	    <b>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_assessment_plan_logs2&_reset=1&ViewAssessmentPlanLogs2_filter_paperwork=7&ViewAssessmentPlanLogs2_due_start_date=$start_date&ViewAssessmentPlanLogs2_due_end_date=$end_date&ViewAssessmentPlanLogs2_filter_assessor=$assessor&ViewAssessmentPlanLogs2_filter_manager=$manager" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_evidence_matrix_projects&_reset=1&ViewEvidenceMatrixProjects_filter_paperwork=7&ViewEvidenceMatrixProjects_due_start_date=$start_date&ViewEvidenceMatrixProjects_due_end_date=$end_date&ViewEvidenceMatrixProjects_filter_assessor=$assessor&ViewEvidenceMatrixProjects_filter_manager=$manager" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
            </b>
		</div>
	</div>
</div>
HTML;

        $gt_behind_iqa = $total_behind_iqa + $total_behind_iqaev;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$total_behind_iqa + $total_behind_iqaev = $gt_behind_iqa</h1>
			<p>IQA On Track</p>
		</div>
    	<div>
    	    <b>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_assessment_plan_logs2&_reset=1&ViewAssessmentPlanLogs2_filter_trs=$behindiqatrs" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
		        <div class="col-xs-6 bg-success"><a href="do.php?_action=view_evidence_matrix_projects&_reset=1&ViewEvidenceMatrixProjects_filter_trs=$behindiqatrsev" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a></div>
            </b>
		</div>
	</div>
</div>
HTML;

        //<a href="" onclick="navToViewAssessmentPlanLogs('$behindtrs');" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>-->

        return $html;
    }

    private function showReviewProgress(PDO $link)
    {
        $fromDate = $_REQUEST['fromDate'];
        $start_date = Date::toMySQL($fromDate);

        $toDate = $_REQUEST['toDate'];
        $end_date = Date::toMySQL($toDate);

        $due_date_where = " and IF(due_date2 IS NOT NULL AND due_date2!='0000-00-00', due_date2, IF(due_date1 IS NOT NULL AND due_date1!='0000-00-00', due_date1, due_date)) BETWEEN '$start_date' AND '$end_date'";

        $meeting_date_where = " and meeting_date between '$start_date' and '$end_date'";
        $assessor = isset($_REQUEST['assessor'])?$_REQUEST['assessor']:'';
        $manager = isset($_REQUEST['manager'])?$_REQUEST['manager']:'';
        $where_manager = "";
        if($manager!='')
        {
            $where_manager = " and assessor_review.tr_id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
        }

        $assessor_where = '';
        if($assessor!='')
            $assessor_where = " and assessor_review.tr_id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR groups.assessor='$assessor')";


        $total_learner_incomplete = DAO::getSingleValue($link,  "SELECT (SELECT COUNT(DISTINCT assessor_review.id) FROM assessor_review
LEFT JOIN tr ON tr.id = assessor_review.tr_id
LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id
LEFT JOIN assessor_review_forms_assessor4 ON assessor_review_forms_assessor4.`review_id` = assessor_review.id
WHERE signature_learner_font IS NULL AND signature_assessor_font IS NOT NULL AND tr.status_code = 1 $assessor_where $where_manager)+
(SELECT COUNT(DISTINCT assessor_review.id) FROM assessor_review
LEFT JOIN tr ON tr.id = assessor_review.tr_id
LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id
WHERE signature_learner_font IS NULL AND signature_assessor_font IS NOT NULL AND tr.status_code = 1 $assessor_where $where_manager);");
        $total_employer_incompleted = DAO::getSingleValue($link,"SELECT (SELECT COUNT(DISTINCT assessor_review.id) FROM assessor_review
LEFT JOIN tr ON tr.id = assessor_review.tr_id
LEFT JOIN assessor_review_forms_employer ON assessor_review_forms_employer.`review_id` = assessor_review.id
LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id
WHERE signature_learner_font IS NOT NULL AND signature_employer_font IS NULL AND tr.status_code = 1 $assessor_where $where_manager)+
(SELECT COUNT(DISTINCT assessor_review.id) FROM assessor_review
LEFT JOIN tr ON tr.id = assessor_review.tr_id
LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id
WHERE signature_learner_font IS NOT NULL AND signature_employer_font IS NULL AND tr.status_code = 1 $assessor_where $where_manager);");
        $total_incomplete = DAO::getSingleValue($link,          "SELECT (SELECT COUNT(distinct assessor_review.id) FROM assessor_review LEFT JOIN tr on tr.id = assessor_review.tr_id LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id LEFT JOIN assessor_review_forms_assessor4 ON assessor_review_forms_assessor4.`review_id` = assessor_review.id WHERE signature_learner_font IS NULL and signature_assessor_font is not null and tr.status_code = 1 $assessor_where $where_manager)+(SELECT COUNT(DISTINCT assessor_review.id) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id LEFT JOIN assessor_review_forms_employer ON assessor_review_forms_employer.`review_id` = assessor_review.id LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id WHERE signature_learner_font IS NOT NULL AND signature_employer_font IS NULL AND tr.status_code = 1 $assessor_where $where_manager)+
(SELECT COUNT(DISTINCT assessor_review.id) FROM assessor_review
		LEFT JOIN tr ON tr.id = assessor_review.tr_id
		LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id
		WHERE signature_learner_font IS NULL AND signature_assessor_font IS NOT NULL AND tr.status_code = 1 $assessor_where $where_manager)+
(SELECT COUNT(DISTINCT assessor_review.id) FROM assessor_review LEFT JOIN tr ON tr.id = assessor_review.tr_id
		LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id
		WHERE signature_learner_font IS NOT NULL AND signature_employer_font IS NULL AND tr.status_code = 1 $assessor_where $where_manager)");
        $total_awaiting_learner_signature_24 = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM forms_audit LEFT JOIN assessor_review ON assessor_review.id = forms_audit.`form_id` LEFT JOIN tr on tr.id = assessor_review.tr_id WHERE description = \"Review Form Emailed to Learner\" AND DATE_ADD(`date`, INTERVAL 1 DAY) < NOW() AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_learner WHERE signature_learner_font IS NOT NULL) AND form_id NOT IN (SELECT review_id FROM arf_introduction WHERE signature_learner_font IS NOT NULL) and status_code = 1 $assessor_where $where_manager;");
        $total_awaiting_employer_signature_7 = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM forms_audit LEFT JOIN assessor_review ON assessor_review.id = forms_audit.`form_id` LEFT JOIN tr on tr.id = assessor_review.tr_id WHERE description = \"Review Form Emailed to Employer\" AND DATE_ADD(`date`, INTERVAL 7 DAY) < NOW() AND DATE_ADD(`date`, INTERVAL 5 WEEK) >= NOW() AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_employer WHERE signature_employer_font IS NOT NULL) AND form_id NOT IN (SELECT review_id FROM arf_introduction WHERE signature_employer_font IS NOT NULL) and status_code = 1 $assessor_where $where_manager;");
        $total_aged = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM forms_audit LEFT JOIN assessor_review ON assessor_review.id = forms_audit.`form_id` left join tr on tr.id = assessor_review.tr_id WHERE tr.status_code = 1 and description = \"Review Form Emailed to Employer\" AND DATE_ADD(`date`, INTERVAL 5 WEEK) < NOW() AND form_id NOT IN (SELECT review_id FROM assessor_review_forms_employer WHERE signature_employer_font IS NOT NULL) AND form_id NOT IN (SELECT review_id FROM arf_introduction WHERE signature_employer_font IS NOT NULL) and status_code = 1 $assessor_where $where_manager;");
        $total_due = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM assessor_review
        left join forms_audit on assessor_review.tr_id = forms_audit.form_id
        left join tr on tr.id = assessor_review.tr_id
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        WHERE tr.status_code = 1 $due_date_where $assessor_where $where_manager;");
        $total_gone_ahead = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM assessor_review
        left join tr on tr.id = assessor_review.tr_id
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id
        LEFT JOIN assessor_review_forms_assessor4 ON assessor_review_forms_assessor4.review_id = assessor_review.id
        LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id
        WHERE tr.status_code = 1 and (assessor_review_forms_assessor4.signature_assessor_font IS NOT NULL OR arf_introduction.`signature_assessor_font` IS NOT NULL) $meeting_date_where $assessor_where $where_manager;");
        $total_completed = DAO::getSingleValue($link,"SELECT (SELECT COUNT(DISTINCT assessor_review.id) FROM assessor_review
LEFT JOIN tr ON tr.id = assessor_review.tr_id
LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review.id
LEFT JOIN assessor_review_forms_employer ON assessor_review_forms_employer.review_id = assessor_review.id
WHERE tr.status_code = 1 AND signature_employer_font IS NOT NULL $meeting_date_where $assessor_where $where_manager)+
(SELECT COUNT(DISTINCT assessor_review.id) FROM assessor_review
LEFT JOIN tr ON tr.id = assessor_review.tr_id
LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id
WHERE tr.status_code = 1 AND signature_employer_font IS NOT NULL $meeting_date_where $assessor_where $where_manager);");
        $total_no_contact = DAO::getSingleValue($link,"SELECT COUNT(distinct assessor_review.id) FROM assessor_review
        WHERE tr_id IN (SELECT tr.id FROM tr
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        LEFT JOIN student_frameworks ON student_frameworks.`tr_id` = tr.id
        LEFT JOIN frameworks ON frameworks.id = student_frameworks.`id`
        WHERE target_date >= now() and status_code = 1 AND COALESCE(last_contact,0)!=1 AND ((DATE_ADD(start_date, INTERVAL 12 MONTH) > NOW() AND frameworks.framework_type IN (2,3,25)) OR (DATE_ADD(start_date, INTERVAL 18 MONTH) > NOW() AND frameworks.framework_type IN (20)))) AND DATE_ADD((DATE_ADD(`meeting_date`, INTERVAL 12 WEEK)), INTERVAL (9-DAYOFWEEK((DATE_ADD(`meeting_date`, INTERVAL 12 WEEK)))) DAY) <= NOW() AND meeting_date=(SELECT MAX(meeting_date) FROM assessor_review AS ar2 WHERE ar2.tr_id = assessor_review.tr_id) $assessor_where $where_manager;");

        $html = '';
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$total_learner_incomplete</h1>
			<p>Total learners incomplete</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_manager=$manager&ViewReviewProgress_filter_audit=4&ViewReviewProgress_filter_signature=1" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
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
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_manager=$manager&ViewReviewProgress_filter_signature=2" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
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
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_manager=$manager&ViewReviewProgress_filter_audit=3" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
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
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_manager=$manager&ViewReviewProgress_filter_signature=4" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
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
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_manager=$manager&ViewReviewProgress_filter_signature=5" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
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
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_manager=$manager&ViewReviewProgress_filter_signature=6" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
if(DB_NAME=='am_demo')
    $title = "Total due within filter dates";
else
    $title = "Total due this month";

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-maroon">
		<div class="inner">
			<h1>$total_due</h1>
			<p>$title</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_manager=$manager&ViewReviewProgress_filter_from_marked_date=$start_date&ViewReviewProgress_filter_to_marked_date=$end_date" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $total_due = ($total_due==0)?1:$total_due;
        $gone_ahead_percentage = round($total_gone_ahead / $total_due * 100,2);
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-lime">
		<div class="inner">
			<h1>$total_gone_ahead ($gone_ahead_percentage%)</h1>
			<p>Total gone ahead</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_manager=$manager&ViewReviewProgress_filter_signature=10&ViewReviewProgress_last_start_date=$start_date&ViewReviewProgress_last_end_date=$end_date" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-olive">
		<div class="inner">
			<h1>$total_no_contact</h1>
			<p>No contact 12 weeks</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_manager=$manager&ViewReviewProgress_filter_signature=9" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
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
		<a href="do.php?_action=view_review_progress&_reset=1&ViewReviewProgress_filter_assessor=$assessor&ViewReviewProgress_filter_manager=$manager&ViewReviewProgress_filter_signature=8&ViewReviewProgress_last_start_date=$start_date&ViewReviewProgress_last_end_date=$end_date" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        return $html;
    }

    private function showAdditionalSupport(PDO $link)
    {
        $fromDate = $_REQUEST['fromDate'];
        $start_date = Date::toMySQL($fromDate);

        $toDate = $_REQUEST['toDate'];
        $end_date = Date::toMySQL($toDate);

        $due_date_where = " and IF(revised_date IS NOT NULL, revised_date,due_date) BETWEEN '$start_date' AND '$end_date'";

        $meeting_date_where = " and actual_date between '$start_date' and '$end_date'";
        $assessor = isset($_REQUEST['assessor'])?$_REQUEST['assessor']:'';
        $manager = isset($_REQUEST['manager'])?$_REQUEST['manager']:'';
        $where_manager = "";
        $where_manager2 = "";
        if($manager!='')
        {
            $where_manager = " and additional_support.tr_id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
            $where_manager2 = " and tr.id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
        }
        $assessor_where = '';
        $assessor_where2 = '';
        if($assessor!='')
        {
            $assessor_where = " and additional_support.tr_id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR groups.assessor='$assessor')";
            $assessor_where2 = " and tr.id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR groups.assessor='$assessor')";
        }

        $total_due = DAO::getSingleValue($link,"SELECT COUNT(*) FROM additional_support
        left join tr on tr.id = additional_support.tr_id
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        WHERE additional_support.subject_area = 0 and tr.status_code = 1 $due_date_where $assessor_where $where_manager;");
        $total_gone_ahead = DAO::getSingleValue($link,"SELECT COUNT(*) FROM additional_support
        left join tr on tr.id = additional_support.tr_id
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        WHERE additional_support.subject_area = 0 and tr.status_code = 1 $meeting_date_where $assessor_where $where_manager;");
        $total_no_contact = DAO::getSingleValue($link,"SELECT COUNT(*) FROM additional_support
        LEFT JOIN tr ON tr.id = additional_support.`tr_id`
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        WHERE DATE_ADD((DATE_ADD(`actual_date`, INTERVAL 12 WEEK)), INTERVAL (9-DAYOFWEEK((DATE_ADD(`actual_date`, INTERVAL 12 WEEK)))) DAY) <= NOW()
        AND tr.`status_code` = 1 AND COALESCE(last_contact,0)!=1 AND additional_support.id = (SELECT MAX(id) FROM additional_support adds WHERE adds.tr_id = additional_support.`tr_id`) $assessor_where $where_manager;");
        $no_support_booked = DAO::getSingleValue($link,"SELECT COUNT(*) FROM tr
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        WHERE status_code = 1 AND COALESCE(last_contact,0)!=1 AND tr.id NOT IN (SELECT tr_id FROM additional_support WHERE IF(revised_date IS NOT NULL, revised_date,due_date) > NOW()) $assessor_where2 $where_manager2;");
        DAO::execute($link, "SET group_concat_max_len=15000;");
        $no_support_booked_ids = DAO::getSingleValue($link,"SELECT GROUP_CONCAT(tr.id) FROM tr
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        WHERE COALESCE(last_contact,0)!=1 AND status_code = 1 AND tr.id NOT IN (SELECT tr_id FROM additional_support WHERE IF(revised_date IS NOT NULL, revised_date,due_date) > NOW()) $assessor_where2 $where_manager2;");

        $html = '';
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-maroon">
		<div class="inner">
			<h1>$total_due</h1>
			<p>Total due this month</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_additional_support&_reset=1&ViewAdditionalSupport_filter_assessor=$assessor&ViewAdditionalSupport_filter_manager=$manager&ViewAdditionalSupport_due_start_date=$start_date&ViewAdditionalSupport_due_end_date=$end_date&ViewAdditionalSupport_filter_no_contact=2" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $total_due = ($total_due==0)?1:$total_due;
        $percentage_gone_ahead = round(($total_gone_ahead / $total_due * 100),2);
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-lime">
		<div class="inner">
			<h1>$total_gone_ahead ($percentage_gone_ahead%)</h1>
			<p>Total gone ahead</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_additional_support&_reset=1&ViewAdditionalSupport_filter_assessor=$assessor&ViewAdditionalSupport_filter_manager=$manager&ViewAdditionalSupport_actual_start_date=$start_date&ViewAdditionalSupport_actual_end_date=$end_date&ViewAdditionalSupport_filter_no_contact=2" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$total_no_contact</h1>
			<p>No contact 12 weeks</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_additional_support&_reset=1&ViewAdditionalSupport_filter_assessor=$assessor&ViewAdditionalSupport_filter_manager=$manager&ViewAdditionalSupport_filter_no_contact=1" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$no_support_booked</h1>
			<p>No Support Booked</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_filter_tr_ids=$no_support_booked_ids" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        return $html;
    }

    private function showReworkData(PDO $link)
    {
        $fromDate = $_REQUEST['fromDate'];
        $start_date = Date::toMySQL($fromDate);

        $toDate = $_REQUEST['toDate'];
        $end_date = Date::toMySQL($toDate);

        $where_actual = " and submission_date between '$start_date' and '$end_date'";
        $where_signed = " and completion_date between '$start_date' and '$end_date'";
        $where_due = " and sub.due_date between '$start_date' and '$end_date'";
        $where_signed = " and assessor_signed_off between '$start_date' and '$end_date'";
        $where_marked = " and assessment_plan_log_submissions.marked_date between '$start_date' and '$end_date'";
        $where_marked2 = " and project_submissions.marked_date between '$start_date' and '$end_date'";


        $assessor = isset($_REQUEST['assessor'])?$_REQUEST['assessor']:'';
        $manager = isset($_REQUEST['manager'])?$_REQUEST['manager']:'';
        $where_assessor = "";
        $where_manager = "";
        if($manager!='')
        {
            $where_manager = " and tr_id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
        }
        $where_assessor2 = "";
        $where_assessor22 = "";
        if($assessor!='')
        {
            $where_assessor = " and tr_id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR (tr.assessor is null and groups.assessor='$assessor')) ";
            $where_assessor2 = " and assessment_plan_log_submissions.assessor = '$assessor' ";
            $where_assessor22 = " and project_submissions.assessor = '$assessor' ";
        }

        $total = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM assessment_plan_log_submissions
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_manager)
$where_marked $where_assessor2 ;");

        if($total==0)
            $total = 1;

        $total2 = DAO::getSingleValue($link,"SELECT DISTINCT COUNT(*)
FROM project_submissions
WHERE project_submissions.project_id IN (SELECT id FROM tr_projects WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_manager)
$where_marked2 $where_assessor22 ;");

        if($total2==0)
            $total2 = 1;

        $total_signed_off = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM assessment_plan_log_submissions
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_manager)
and assessor_signed_off is not null $where_marked $where_assessor2 ;");

        $total_signed_off_percentage = round($total_signed_off/$total*100);

        $total_signed_off2 = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM project_submissions
WHERE project_submissions.project_id IN (SELECT id FROM tr_projects WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_manager)
and assessor_signed_off is not null $where_marked2 $where_assessor22 ;");

        $total_signed_off_percentage2 = round($total_signed_off2/$total2*100);

        $no_total_signed_off = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM assessment_plan_log_submissions
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_manager)
and assessor_signed_off is null $where_marked $where_assessor2 ;");

        $no_total_signed_off_percentage = round($no_total_signed_off/$total*100);

        $no_total_signed_off2 = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM project_submissions
WHERE project_submissions.project_id IN (SELECT id FROM tr_projects WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_manager)
and assessor_signed_off is null $where_marked2 $where_assessor22 ;");

        $no_total_signed_off_percentage2 = round($no_total_signed_off2/$total2*100);

/*        $rft_total = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is not null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id` AND  s2.id <= assessment_plan_log_submissions.id) < 3)
$where_marked $where_assessor2 $where_manager;");*/

        $rft_total = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is not null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id`) < 2)
$where_marked $where_assessor2 $where_manager;");


        $rft_total_percentage = round($rft_total/$total*100);

        $rft_total2 = DAO::getSingleValue($link,"SELECT DISTINCT COUNT(*)
FROM project_submissions
LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
WHERE tr_projects.project IN (SELECT project FROM tr_projects WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
AND assessor_signed_off IS NOT NULL
AND ((SELECT COUNT(*) FROM project_submissions AS s2  WHERE s2.project_id = tr_projects.id) < 2)
$where_marked2 $where_assessor22 $where_manager;");


        $rft_total_percentage2 = round($rft_total2/$total2*100);

/*        $no_rft_total = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is not null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id` AND  s2.id <= assessment_plan_log_submissions.id) > 2)
$where_marked $where_assessor2 $where_manager;");*/

        $no_rft_total = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is not null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id`) = 2)
$where_marked $where_assessor2 $where_manager;");

        $no_rft_total_percentage = round($no_rft_total/$total*100);


        $no_rft_total2 = DAO::getSingleValue($link,"SELECT DISTINCT COUNT(*)
FROM project_submissions
LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
WHERE tr_projects.project IN (SELECT project FROM tr_projects WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
AND assessor_signed_off IS NOT NULL
AND ((SELECT COUNT(*) FROM project_submissions AS s2  WHERE s2.project_id = tr_projects.id) = 2)
$where_marked2 $where_assessor22 $where_manager;");

        $no_rft_total_percentage2 = round($no_rft_total2/$total2*100);

/*        $not_rework_total = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id` AND  s2.id <= assessment_plan_log_submissions.id) < 3)
$where_marked $where_assessor2 $where_manager;");*/

        $not_rework_total = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id`) < 2)
$where_marked $where_assessor2 $where_manager;");

        $not_rework_total_percentage = round($not_rework_total/$total*100);

        $not_rework_total2 = DAO::getSingleValue($link,"SELECT DISTINCT COUNT(*)
FROM project_submissions
LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
WHERE tr_projects.project IN (SELECT project FROM tr_projects WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
AND assessor_signed_off IS NULL
AND ((SELECT COUNT(*) FROM project_submissions AS s2  WHERE s2.project_id = tr_projects.id) < 2)
$where_marked2 $where_assessor22 $where_manager;");

        $not_rework_total_percentage2 = round($not_rework_total2/$total2*100);

/*        $genuine_rework_total = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id` AND  s2.id <= assessment_plan_log_submissions.id) > 2)
$where_marked $where_assessor2 $where_manager;");*/

        $genuine_rework_total = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id`) = 2)
$where_marked $where_assessor2 $where_manager;");

        $genuine_rework_total_percentage = round($genuine_rework_total/$total*100);

        $genuine_rework_total2 = DAO::getSingleValue($link,"SELECT distinct count(*)
FROM project_submissions
LEFT JOIN tr_projects ON tr_projects.id = project_submissions.project_id
WHERE tr_projects.project IN (SELECT project FROM tr_projects WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
AND assessor_signed_off IS NULL
AND ((SELECT COUNT(*) FROM project_submissions AS s2  WHERE s2.project_id = tr_projects.id) = 2)
$where_marked2 $where_assessor22 $where_manager;");

        $genuine_rework_total_percentage2 = round($genuine_rework_total2/$total2*100);

        // End
        $html = '';
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$total_signed_off ($total_signed_off_percentage%) + $total_signed_off2 ($total_signed_off_percentage2%)</h1>
			<p>LM Sign Off Total</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=1" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h1>$no_total_signed_off ($no_total_signed_off_percentage%) + $no_total_signed_off2 ($no_total_signed_off_percentage2%)</h1>
			<p>No LM Sign Off Total</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=2" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-maroon">
		<div class="inner">
			<h1>$rft_total ($rft_total_percentage%) + $rft_total2 ($rft_total_percentage2%)</h1>
			<p>RFT Total</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=1&ViewAPSubmission_filter_submission_count=1" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-olive">
		<div class="inner">
			<h1>$no_rft_total ($no_rft_total_percentage%) + $no_rft_total2 ($no_rft_total_percentage2%)</h1>
			<p>Signed off rework</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=1&ViewAPSubmission_filter_submission_count=2" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$not_rework_total ($not_rework_total_percentage%) + $not_rework_total2 ($not_rework_total_percentage2%)</h1>
			<p>Not Rework Total</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=2&ViewAPSubmission_filter_submission_count=1" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-blue">
		<div class="inner">
			<h1>$genuine_rework_total ($genuine_rework_total_percentage%) + $genuine_rework_total2 ($genuine_rework_total_percentage2%)</h1>
			<p>Current rework</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=2&ViewAPSubmission_filter_submission_count=2" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $overall_rework = $genuine_rework_total + $no_rft_total;
        $overall_rework_percentage = $genuine_rework_total_percentage + $no_rft_total_percentage;

        $overall_rework2 = $genuine_rework_total2 + $no_rft_total2;
        $overall_rework_percentage2 = $genuine_rework_total_percentage2 + $no_rft_total_percentage2;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-blue">
		<div class="inner">
			<h1>$overall_rework ($overall_rework_percentage%) + $overall_rework2 ($overall_rework_percentage2%)</h1>
			<p>Overall rework</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
	</div>
</div>
HTML;

        return $html;
    }

    private function showLearners(PDO $link)
    {
        $fromDate = $_REQUEST['fromDate'];
        $start_date = Date::toMySQL($fromDate);

        $toDate = $_REQUEST['toDate'];
        $end_date = Date::toMySQL($toDate);
/*
        $where_actual = " and submission_date between '$start_date' and '$end_date'";
        $where_signed = " and completion_date between '$start_date' and '$end_date'";
        $where_due = " and sub.due_date between '$start_date' and '$end_date'";
        $where_signed = " and assessor_signed_off between '$start_date' and '$end_date'";
        $where_marked = " and assessment_plan_log_submissions.marked_date between '$start_date' and '$end_date'";


        $assessor = isset($_REQUEST['assessor'])?$_REQUEST['assessor']:'';
        $manager = isset($_REQUEST['manager'])?$_REQUEST['manager']:'';
        $where_assessor = "";
        $where_manager = "";
        if($manager!='')
        {
            $where_manager = " and tr_id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
        }
        $where_assessor2 = "";
        if($assessor!='')
        {
            $where_assessor = " and tr_id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR (tr.assessor is null and groups.assessor='$assessor')) ";
            $where_assessor2 = " and assessment_plan_log_submissions.assessor = '$assessor' ";
        }
*/

        $assessor = isset($_REQUEST['assessor'])?$_REQUEST['assessor']:'';
        $manager = isset($_REQUEST['manager'])?$_REQUEST['manager']:'';
        $where_assessor = "";
        $where_manager = "";
        if($manager!='')
        {
            $where_manager = " and tr.id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
        }
        $where_assessor2 = "";
        if($assessor!='')
        {
            $where_assessor = " and tr.id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR (tr.assessor is null and groups.assessor='$assessor')) ";
        }


        $live = DAO::getSingleValue($link,"SELECT COUNT(*) FROM tr
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
WHERE status_code = 1 AND (SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = tr.id AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y'
ORDER BY
	id DESC
LIMIT 1) IS NULL $where_assessor $where_manager;
");

        DAO::execute($link, "SET group_concat_max_len=15000;");
        $live_ids = DAO::getSingleValue($link,"SELECT GROUP_CONCAT(tr.id) FROM tr
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        WHERE status_code = 1 AND (SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = tr.id AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y'
ORDER BY
	id DESC
LIMIT 1) IS NULL $where_assessor $where_manager;
");

        $gateway = DAO::getSingleValue($link,"SELECT COUNT(*) FROM tr WHERE status_code = 1 AND (SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = tr.id AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y'
ORDER BY
	id DESC
LIMIT 1) IS NOT NULL $where_assessor $where_manager;
");

        $gateway_ids = DAO::getSingleValue($link,"SELECT group_concat(tr.id) FROM tr WHERE status_code = 1 AND (SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = tr.id AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y'
ORDER BY
	id DESC
LIMIT 1) IS NOT NULL $where_assessor $where_manager;
");

        $first_date = Date::toMySQL($start_date);
        $last_date = Date::toMySQL($end_date);
        $sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  tr_operations INNER JOIN tr ON tr_operations.`tr_id` = tr.`id`
WHERE
  extractvalue(tr_operations.`bil_details`, '/Notes/Note[last()]/Type') IN ("Y", "O", "F")
  AND (tr_operations.`leaver_details` IS NULL OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "N" OR extractvalue(tr_operations.`leaver_details`, '/Notes/Note[last()]/Type') = "")
$where_assessor $where_manager;
SQL;
        $bil_count = DAO::getSingleValue($link, $sql);

		$sql = <<<SQL
SELECT DISTINCT
  COUNT(*)
FROM
  op_epa INNER JOIN tr ON op_epa.`tr_id` = tr.`id`
WHERE
  op_epa.`task` = 7 AND task_status IN (12, 13, 14) AND task_actual_date BETWEEN  '{$first_date}' AND '{$last_date}'
  $where_assessor $where_manager;
;
SQL;
	    $interviews_count = DAO::getSingleValue($link, $sql);

        // End
        $html = '';
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$live</h1>
			<p>Live Learners</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_filter_tr_ids=$live_ids" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h1>$gateway</h1>
			<p>Gateway Learners</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_filter_tr_ids=$gateway_ids" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-maroon">
		<div class="inner">
			<h1>$bil_count</h1>
			<p>BIL</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_operations_reports&subview=view_operations_bil_report&_reset=1&filter_from_tr_start=$first_date&filter_to_tr_start=$last_date&filter_assessor=$assessor&filter_manager=$manager" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

		$html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<p>Interviews</p>
			<h2>$interviews_count</h2>
		</div>
		<div class="icon"><i class="fa fa-briefcase"></i></div>
		<a href="do.php?_action=view_operations_reports&subview=view_interviews&_reset=1&filter_from_tr_start=$first_date&filter_to_tr_start=$last_date&filter_assessor=$assessor&filter_manager=$manager" class="small-box-footer">View Report <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        return $html;
    }


}