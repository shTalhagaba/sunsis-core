<?php
class tolerance_report implements IAction
{
    public function execute(PDO $link)
    {
        $fromDate = date('d/m/Y');
        $toDate = date('d/m/Y');
        $factor_dropdown = array(
            //array('1', 'All'),
            //array('2', 'Reviews'),
            array('3', 'Assessment Plans')
        );

        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        if ($subaction == 'showInductionDashPanels') {
            echo $this->showInductionDashPanels($link);
            exit;
        }
        if ($subaction == 'showReviewProgress') {
            echo $this->showReviewProgress($link);
            exit;
        }
        if ($subaction == 'showReworkData') {
            echo $this->showReworkData($link);
            exit;
        }
        if ($subaction == 'showAdditionalSupport') {
            echo $this->showAdditionalSupport($link);
            exit;
        }
        if ($subaction == 'showLearners') {
            echo $this->showLearners($link);
            exit;
        }

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=assessment_dashboard2", "Induction Dashboard");

        $first_date = date('Y-m-d', strtotime("first day of this month"));
        $last_date = date('Y-m-d', strtotime("last day of this month"));

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
        $assessors = DAO::getResultset($link, $assessor_sql);

        $manager_sql = <<<HEREDOC
SELECT DISTINCT users.username, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1)
FROM users
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
WHERE
users.username IN (SELECT supervisor FROM users WHERE supervisor IS NOT NULL)
ORDER BY firstnames, surname;
HEREDOC;
        $managers = DAO::getResultset($link, $manager_sql);

        require_once('tpl_tolerance_report.php');
    }


    private function showInductionDashPanels(PDO $link)
    {
        DAO::execute($link, "SET SESSION group_concat_max_len = 1000000");
        $assessor = isset($_REQUEST['assessor']) ? $_REQUEST['assessor'] : '';
        $manager = isset($_REQUEST['manager']) ? $_REQUEST['manager'] : '';
        $area = isset($_REQUEST['area']) ? $_REQUEST['area'] : '1';
        $area = 3;
        $where_assessor = "";
        $where_assessor2 = "";
        $where_manager = "";
        if ($manager != '') {
            $where_manager = " and tr.id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
        }
        if (!empty($assessor)) {
            $assessor = addslashes($assessor); // basic escaping if not using PDO params

            $where_assessor = "
        AND tr.id IN (
            SELECT t.id
            FROM tr AS t
            LEFT JOIN group_members ON group_members.tr_id = t.id
            LEFT JOIN groups ON groups.id = group_members.groups_id
            WHERE t.assessor = '$assessor'
               OR (t.assessor IS NULL AND groups.assessor = '$assessor')
        )
    ";

            $where_assessor2 = " AND assessment_plan_log_submissions.assessor = '$assessor' ";
        }

        if ($area == 3) {
            // AP Progress On Track/ Behind
            $ontrack = array();
            $behind_0_7 = array();
            $behind_8_28 = array();
            $behind_29_59 = array();
            $behind_60 = array();
            $behind_iqa = array();

            $queryap = "
SELECT 
    tr.id AS tr_id, 
    tr.start_date, 
    courses.id AS course_id,
    (SELECT COUNT(*) FROM ap_percentage WHERE course_id = courses.id) AS max_month_row,
    (SELECT t.max_month 
     FROM ap_percentage t 
     WHERE t.course_id = courses.id 
     ORDER BY t.id DESC LIMIT 1) AS max_month,
    (SELECT t.aps 
     FROM ap_percentage t 
     WHERE t.course_id = courses.id 
     ORDER BY t.id DESC LIMIT 1) AS aps,
    (SELECT MAX(aps) FROM ap_percentage WHERE course_id = courses.id) AS total_units,
    (
        SELECT COUNT(*) 
        FROM assessment_plan_log 
        LEFT JOIN assessment_plan_log_submissions AS sub 
            ON sub.assessment_plan_id = assessment_plan_log.id 
            AND sub.id = (
                SELECT MAX(id) 
                FROM assessment_plan_log_submissions 
                WHERE assessment_plan_id = assessment_plan_log.id
            )
        WHERE sub.completion_date IS NOT NULL 
          AND assessment_plan_log.tr_id = tr.id
    ) AS passed_units,
    (
        SELECT COUNT(*) 
        FROM assessment_plan_log 
        WHERE tr_id = tr.id
    ) AS total_plans
FROM tr
LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
LEFT JOIN courses ON courses.id = courses_tr.course_id
WHERE status_code = 1 $where_assessor $where_manager;
";


            $stap = $link->query($queryap);
            //print_r($stap);
            if ($stap) {
                while ($rowap = $stap->fetch()) {
                    $course_id = $rowap['course_id'];
                    $rowap['current_training_month'] = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $rowap['tr_id']);

                    //echo "yes";
                    $sql = "SELECT id 
        FROM ap_percentage 
        WHERE course_id = '$course_id' AND " . (int)$rowap['current_training_month'] . " BETWEEN min_month AND max_month";

                    //echo "\nSQL: $sql\n";
                    $rowap['month_row_id'] = DAO::getSingleValue($link, $sql);

                    //var_dump($rowap['month_row_id']); // see if it’s null/false
                    $start_date = $rowap['start_date'];

                    if (!empty($rowap['month_row_id'])) {
                        $rowap['aps_to_check'] = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < {$rowap['month_row_id']} ORDER BY id desc LIMIT 1");
                        $rowap['days_behind'] = DAO::getSingleValue($link, "SELECT DATEDIFF(CURDATE(),DATE_ADD('$start_date',INTERVAL max_month WEEK)) FROM ap_percentage WHERE course_id = '{$course_id}' AND id < {$rowap['month_row_id']} ORDER BY id desc LIMIT 1");
                    } else {
                        $rowap['aps_to_check'] = "";

                        $rowap['days_behind'] = DAO::getSingleValue($link, "SELECT DATEDIFF(CURDATE(),DATE_ADD('$start_date',INTERVAL ap_percentage.max_month WEEK)) AS diff FROM ap_percentage WHERE ap_percentage.course_id = '{$course_id}' ORDER BY ap_percentage.id DESC LIMIT 1");
                    }

                    if ($rowap['total_plans'] == '0')
                        continue;
                    $status = "red";
                    if ($rowap['current_training_month'] == '0')
                        $status = "green";
                    elseif ($rowap['current_training_month'] > $rowap['max_month'] and $rowap['passed_units'] >= $rowap['aps'])
                        $status = "green";
                    elseif ($rowap['current_training_month'] > $rowap['max_month'] and $rowap['passed_units'] < $rowap['aps'])
                        $status = "red";
                    elseif ($rowap['aps_to_check'] == '' or $rowap['passed_units'] >= $rowap['aps_to_check'])
                        $status = "green";

                    if ($status == 'green')
                        $ontrack[] = $rowap['tr_id'];
                    else {
                        if ($rowap['days_behind'] >= 0 and $rowap['days_behind'] <= 7)
                            $behind_0_7[] = $rowap['tr_id'];
                        elseif ($rowap['days_behind'] >= 8 and $rowap['days_behind'] <= 28)
                            $behind_8_28[] = $rowap['tr_id'];
                        elseif ($rowap['days_behind'] >= 29 and $rowap['days_behind'] <= 59)
                            $behind_29_59[] = $rowap['tr_id'];
                        else
                            $behind_60[] = $rowap['tr_id'];
                    }
                }
            }
            $total_on_track = count($ontrack);
            $total_behind_0_7 = count($behind_0_7);
            $total_behind_8_28 = count($behind_8_28);
            $total_behind_29_59 = count($behind_29_59);
            $total_behind_60 = count($behind_60);
            $total_behind_iqa = count($behind_iqa);
            $ontracktrs = implode(",", $ontrack);
            $behindtrs_0_7 = implode(",", $behind_0_7);
            $behindtrs_8_28 = implode(",", $behind_8_28);
            $behindtrs_29_59 = implode(",", $behind_29_59);
            $behindtrs_60 = implode(",", $behind_60);
            $behindiqatrs = implode(",", $behind_iqa);
        }

        $total = $total_behind_0_7 + $total_behind_8_28 + $total_behind_29_59 + $total_behind_60;
        $total = ($total == 0) ? 1 : $total;
        $days_0_7_p = round(($total_behind_0_7 / $total * 100), 2);
        $days_8_28_p = round(($total_behind_8_28 / $total * 100), 2);
        $days_29_59_p = round(($total_behind_29_59 / $total * 100), 2);
        $days_60_p = round(($total_behind_60 / $total * 100), 2);
        // End
        $html = '';
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$total_behind_0_7 ($days_0_7_p%)</h1>
			<p>0 to 7 days behind</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_tolerance_report&_reset=1&ViewToleranceReport_filter_band=1&ViewToleranceReport_filter_tr_ids=$behindtrs_0_7&ViewToleranceReport_filter_band=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$total_behind_8_28 ($days_8_28_p%)</h1>
			<p>8 to 28 days behind</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_tolerance_report&_reset=1&ViewToleranceReport_filter_band=2&ViewToleranceReport_filter_tr_ids=$behindtrs_8_28&ViewToleranceReport_filter_band=2" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-orange">
		<div class="inner">
			<h1>$total_behind_29_59 ($days_29_59_p%)</h1>
			<p>29 to 59 days behind</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_tolerance_report&_reset=1&ViewToleranceReport_filter_band=3&ViewToleranceReport_filter_tr_ids=$behindtrs_29_59&ViewToleranceReport_filter_band=3" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-red">
		<div class="inner">
			<h1>$total_behind_60 ($days_60_p%)</h1>
			<p>60+ days behind</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_tolerance_report&_reset=1&ViewToleranceReport_filter_band=4&ViewToleranceReport_filter_tr_ids=$behindtrs_60&ViewToleranceReport_filter_band=4" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        //<a href="" onclick="navToViewAssessmentPlanLogs('$behindtrs');" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>-->

        return $html;
    }

    private function showReviewProgress(PDO $link)
    {

        $assessor = isset($_REQUEST['assessor']) ? $_REQUEST['assessor'] : '';
        $manager = isset($_REQUEST['manager']) ? $_REQUEST['manager'] : '';
        $area = isset($_REQUEST['area']) ? $_REQUEST['area'] : '1';
        $where_assessor = "";
        $where_manager = "";
        if ($manager != '') {
            $where_manager = " and tr.id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
        }
        if ($assessor != '') {
            $where_assessor = " and tr.id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR (tr.assessor is null and groups.assessor='$assessor'))";
        }


        $queryap = "SELECT tr.id AS tr_id, tr.start_date, courses.id as course_id, firstnames, surname
,(SELECT COUNT(*) FROM ap_percentage WHERE course_id = courses.id) AS max_month_row
,(SELECT max_month FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS max_month
,(SELECT aps FROM ap_percentage WHERE course_id = courses.id ORDER BY id DESC LIMIT 1) AS aps
,(SELECT MAX(aps) FROM ap_percentage WHERE course_id = courses.id) AS total_units
,(SELECT COUNT(*)
FROM assessment_plan_log
LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
	sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
WHERE  sub.`completion_date` IS NOT NULL
AND tr_id = tr.id) AS passed_units
,(select count(*) from assessment_plan_log where tr_id = tr.id) as total_plans
FROM tr
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
LEFT JOIN courses ON courses.id = courses_tr.`course_id`
WHERE
status_code = 1 $where_assessor $where_manager;
";

        $html = '
        <div class="col-sm-12">
            <div class="box box-success box-solid">
			    <div class="box-header with-border"><h1 class="box-title">Summary Information</h1> &nbsp;</div>
				    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead class="bg-gray"><tr><th>Firstnames</th><th>Surname</th><th>Days to next milestone</th></tr></thead>
                                <tbody>';
        $stap = $link->query($queryap);
        if ($stap) {
            while ($rowap = $stap->fetch()) {
                if ($rowap['total_units'] <= $rowap['passed_units'])
                    continue;

                $class = '';
                $total_units = $rowap['total_units'];
                $passed_units = $rowap['passed_units'];
                $max_month = $rowap['max_month'];
                $aps = $rowap['aps'];
                $course_id = $rowap['course_id'];
                $next_milestone_week = DAO::getSingleValue($link, "select min(max_month) from ap_percentage where course_id = '$course_id' and aps > $passed_units");

                $current_training_month = TrainingRecord::getCurrentDiscountedTrainingMonth($link, $rowap['tr_id']);

                if (isset($aps)) {
                    $class = 'bg-red';
                    if ($current_training_month == 0)
                        $class = 'bg-green';
                    elseif ($current_training_month > $max_month && $passed_units >= $aps)
                        $class = 'bg-green';
                    elseif ($current_training_month > $max_month && $passed_units < $aps)
                        $class = 'bg-red';
                    else {
                        $month_row_id = DAO::getSingleValue($link, "SELECT id FROM ap_percentage WHERE course_id = '{$course_id}' AND '{$current_training_month}' BETWEEN min_month AND max_month");
                        $aps_to_check = DAO::getSingleValue($link, "SELECT aps FROM ap_percentage WHERE course_id = '{$course_id}' AND id < '{$month_row_id}' ORDER BY id DESC LIMIT 1");
                        if ($aps_to_check == '' || $passed_units >= $aps_to_check)
                            $class = 'bg-green';
                    }
                }

                if ($class == 'bg-red')
                    continue;

                $rowap['current_training_month'] = $current_training_month;
                $start_date = TrainingRecord::getDiscountedStartDate($link, $rowap['tr_id']);
                $max_month = $next_milestone_week;
                if ($max_month != '') {
                    //$days = DAO::getSingleValue($link, "SELECT ABS(DATEDIFF(DATE_ADD('$start_date',INTERVAL $max_month WEEK),CURDATE())) FROM ap_percentage WHERE course_id = {$rowap['course_id']} ORDER BY id desc LIMIT 1");
                    $days = DAO::getSingleValue($link, "SELECT ABS(DATEDIFF(DATE_ADD('$start_date',INTERVAL $max_month WEEK),CURDATE()));");
                } else {
                    $days = 0;
                }
                $ht = "<tr><td class=\"text-bold\">" . $rowap['firstnames'] . "</td><td class=\"text-bold\">" . $rowap['surname'] . "</td><td class=\"text-bold\">" . $days . "</td></tr>";
                $html .= $ht;
            }
        }
        $html .= '</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
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
        $assessor = isset($_REQUEST['assessor']) ? $_REQUEST['assessor'] : '';
        $manager = isset($_REQUEST['manager']) ? $_REQUEST['manager'] : '';
        $where_manager = "";
        $where_manager2 = "";
        if ($manager != '') {
            $where_manager = " and additional_support.tr_id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
            $where_manager2 = " and tr.id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
        }
        $assessor_where = '';
        $assessor_where2 = '';
        if ($assessor != '') {
            $assessor_where = " and additional_support.tr_id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR groups.assessor='$assessor')";
            $assessor_where2 = " and tr.id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR groups.assessor='$assessor')";
        }

        $total_due = DAO::getSingleValue($link, "SELECT COUNT(*) FROM additional_support
        left join tr on tr.id = additional_support.tr_id
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        WHERE additional_support.subject_area = 0 and tr.status_code = 1 $due_date_where $assessor_where $where_manager;");
        $total_gone_ahead = DAO::getSingleValue($link, "SELECT COUNT(*) FROM additional_support
        left join tr on tr.id = additional_support.tr_id
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        WHERE additional_support.subject_area = 0 and tr.status_code = 1 $meeting_date_where $assessor_where $where_manager;");
        $total_no_contact = DAO::getSingleValue($link, "SELECT COUNT(*) FROM additional_support
        LEFT JOIN tr ON tr.id = additional_support.`tr_id`
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        WHERE DATE_ADD((DATE_ADD(`actual_date`, INTERVAL 12 WEEK)), INTERVAL (9-DAYOFWEEK((DATE_ADD(`actual_date`, INTERVAL 12 WEEK)))) DAY) <= NOW()
        AND tr.`status_code` = 1 AND COALESCE(last_contact,0)!=1 AND additional_support.id = (SELECT MAX(id) FROM additional_support adds WHERE adds.tr_id = additional_support.`tr_id`) $assessor_where $where_manager;");
        $no_support_booked = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr
        LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
	    INNER JOIN courses ON courses.id = courses_tr.`course_id` AND courses.`programme_type` = 2
        WHERE status_code = 1 AND COALESCE(last_contact,0)!=1 AND tr.id NOT IN (SELECT tr_id FROM additional_support WHERE IF(revised_date IS NOT NULL, revised_date,due_date) > NOW()) $assessor_where2 $where_manager2;");
        DAO::execute($link, "SET group_concat_max_len=15000;");
        $no_support_booked_ids = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr.id) FROM tr
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
		<a href="do.php?_action=view_additional_support&_reset=1&ViewAdditionalSupport_filter_assessor=$assessor&ViewAdditionalSupport_filter_manager=$manager&ViewAdditionalSupport_due_start_date=$start_date&ViewAdditionalSupport_due_end_date=$end_date&ViewAdditionalSupport_filter_no_contact=2" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $total_due = ($total_due == 0) ? 1 : $total_due;
        $percentage_gone_ahead = round(($total_gone_ahead / $total_due * 100), 2);
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-lime">
		<div class="inner">
			<h1>$total_gone_ahead ($percentage_gone_ahead%)</h1>
			<p>Total gone ahead</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_additional_support&_reset=1&ViewAdditionalSupport_filter_assessor=$assessor&ViewAdditionalSupport_filter_manager=$manager&ViewAdditionalSupport_actual_start_date=$start_date&ViewAdditionalSupport_actual_end_date=$end_date&ViewAdditionalSupport_filter_no_contact=2" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
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
		<a href="do.php?_action=view_additional_support&_reset=1&ViewAdditionalSupport_filter_assessor=$assessor&ViewAdditionalSupport_filter_manager=$manager&ViewAdditionalSupport_filter_no_contact=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
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
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_filter_tr_ids=$no_support_booked_ids" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
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


        $assessor = isset($_REQUEST['assessor']) ? $_REQUEST['assessor'] : '';
        $manager = isset($_REQUEST['manager']) ? $_REQUEST['manager'] : '';
        $where_assessor = "";
        $where_manager = "";
        if ($manager != '') {
            $where_manager = " and tr_id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
        }
        $where_assessor2 = "";
        if ($assessor != '') {
            $where_assessor = " and tr_id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR (tr.assessor is null and groups.assessor='$assessor')) ";
            $where_assessor2 = " and assessment_plan_log_submissions.assessor = '$assessor' ";
        }

        $total = DAO::getSingleValue($link, "SELECT distinct count(*)
FROM assessment_plan_log_submissions
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_manager)
$where_marked $where_assessor2 ;");

        if ($total == 0)
            $total = 1;

        $total_signed_off = DAO::getSingleValue($link, "SELECT distinct count(*)
FROM assessment_plan_log_submissions
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_manager)
and assessor_signed_off is not null $where_marked $where_assessor2 ;");

        $total_signed_off_percentage = round($total_signed_off / $total * 100);

        $no_total_signed_off = DAO::getSingleValue($link, "SELECT distinct count(*)
FROM assessment_plan_log_submissions
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1) $where_manager)
and assessor_signed_off is null $where_marked $where_assessor2 ;");

        $no_total_signed_off_percentage = round($no_total_signed_off / $total * 100);

        /*        $rft_total = DAO::getSingleValue($link,"SELECT distinct count(*)
        FROM assessment_plan_log_submissions
        LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
        WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
        and assessor_signed_off is not null
        and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id` AND  s2.id <= assessment_plan_log_submissions.id) < 3)
        $where_marked $where_assessor2 $where_manager;");*/

        $rft_total = DAO::getSingleValue($link, "SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is not null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id`) < 2)
$where_marked $where_assessor2 $where_manager;");


        $rft_total_percentage = round($rft_total / $total * 100);

        /*        $no_rft_total = DAO::getSingleValue($link,"SELECT distinct count(*)
        FROM assessment_plan_log_submissions
        LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
        WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
        and assessor_signed_off is not null
        and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id` AND  s2.id <= assessment_plan_log_submissions.id) > 2)
        $where_marked $where_assessor2 $where_manager;");*/

        $no_rft_total = DAO::getSingleValue($link, "SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is not null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id`) = 2)
$where_marked $where_assessor2 $where_manager;");

        $no_rft_total_percentage = round($no_rft_total / $total * 100);


        /*        $not_rework_total = DAO::getSingleValue($link,"SELECT distinct count(*)
        FROM assessment_plan_log_submissions
        LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
        WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
        and assessor_signed_off is null
        and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id` AND  s2.id <= assessment_plan_log_submissions.id) < 3)
        $where_marked $where_assessor2 $where_manager;");*/

        $not_rework_total = DAO::getSingleValue($link, "SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id`) < 2)
$where_marked $where_assessor2 $where_manager;");

        $not_rework_total_percentage = round($not_rework_total / $total * 100);

        /*        $genuine_rework_total = DAO::getSingleValue($link,"SELECT distinct count(*)
        FROM assessment_plan_log_submissions
        LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
        WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
        and assessor_signed_off is null
        and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id` AND  s2.id <= assessment_plan_log_submissions.id) > 2)
        $where_marked $where_assessor2 $where_manager;");*/

        $genuine_rework_total = DAO::getSingleValue($link, "SELECT distinct count(*)
FROM assessment_plan_log_submissions
LEFT JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.assessment_plan_id
WHERE assessment_plan_id IN (SELECT id FROM assessment_plan_log WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1))
and assessor_signed_off is null
and ((SELECT COUNT(*) FROM assessment_plan_log_submissions AS s2  WHERE s2.`assessment_plan_id` = assessment_plan_log.`id`) = 2)
$where_marked $where_assessor2 $where_manager;");

        $genuine_rework_total_percentage = round($genuine_rework_total / $total * 100);

        // End
        $html = '';
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-green">
		<div class="inner">
			<h1>$total_signed_off ($total_signed_off_percentage%)</h1>
			<p>Assessor Sign Off Total</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h1>$no_total_signed_off ($no_total_signed_off_percentage%)</h1>
			<p>No Assessor Sign Off Total</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=2" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-maroon">
		<div class="inner">
			<h1>$rft_total ($rft_total_percentage%)</h1>
			<p>RFT Total</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=1&ViewAPSubmission_filter_submission_count=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-olive">
		<div class="inner">
			<h1>$no_rft_total ($no_rft_total_percentage%)</h1>
			<p>Signed off rework</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=1&ViewAPSubmission_filter_submission_count=2" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-yellow">
		<div class="inner">
			<h1>$not_rework_total ($not_rework_total_percentage%)</h1>
			<p>Not Rework Total</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=2&ViewAPSubmission_filter_submission_count=1" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-blue">
		<div class="inner">
			<h1>$genuine_rework_total ($genuine_rework_total_percentage%)</h1>
			<p>Current rework</p>
		</div>
		<div class="icon"><i class="fa fa-users"></i> </div>
		<a href="do.php?_action=view_ap_submission&_reset=1&ViewAPSubmission_filter_from_marked_date=$start_date&ViewAPSubmission_filter_to_marked_date=$end_date&ViewAPSubmission_filter_person_reviewed=$assessor&ViewAPSubmission_filter_manager=$manager&ViewAPSubmission_filter_signed_off=2&ViewAPSubmission_filter_submission_count=2" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;
        $overall_rework = $genuine_rework_total + $no_rft_total;
        $overall_rework_percentage = $genuine_rework_total_percentage + $no_rft_total_percentage;
        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-blue">
		<div class="inner">
			<h1>$overall_rework ($overall_rework_percentage%)</h1>
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

        $assessor = isset($_REQUEST['assessor']) ? $_REQUEST['assessor'] : '';
        $manager = isset($_REQUEST['manager']) ? $_REQUEST['manager'] : '';
        $where_assessor = "";
        $where_manager = "";
        if ($manager != '') {
            $where_manager = " and tr.id in (select id from tr where assessor in (select id from users where supervisor = '$manager')) ";
        }
        $where_assessor2 = "";
        if ($assessor != '') {
            $where_assessor = " and tr.id in (SELECT tr.id FROM tr LEFT JOIN group_members ON group_members.tr_id = tr.id LEFT JOIN groups ON groups.id = group_members.groups_id WHERE tr.assessor = '$assessor' OR (tr.assessor is null and groups.assessor='$assessor')) ";
        }


        $live = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE status_code = 1 AND (SELECT
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
        $live_ids = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(tr.id) FROM tr WHERE status_code = 1 AND (SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = tr.id AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y'
ORDER BY
	id DESC
LIMIT 1) IS NULL $where_assessor $where_manager;
");

        $gateway = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE status_code = 1 AND (SELECT
	DATE_FORMAT(op_epa.task_actual_date, '%d/%m/%Y')
FROM
	op_epa
WHERE
	op_epa.`tr_id` = tr.id AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y'
ORDER BY
	id DESC
LIMIT 1) IS NOT NULL $where_assessor $where_manager;
");

        $gateway_ids = DAO::getSingleValue($link, "SELECT group_concat(tr.id) FROM tr WHERE status_code = 1 AND (SELECT
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
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_filter_tr_ids=$live_ids" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
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
		<a href="do.php?_action=view_training_records_v2&_reset=1&ViewTrainingRecordsV2_filter_tr_ids=$gateway_ids" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
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
		<a href="do.php?_action=view_operations_reports&subview=view_operations_bil_report&_reset=1&filter_from_tr_start=$first_date&filter_to_tr_start=$last_date&filter_assessor=$assessor&filter_manager=$manager" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        $html .= <<<HTML
<div class="col-lg-6 col-xs-6">
	<div class="small-box bg-aqua">
		<div class="inner">
			<h2>$interviews_count</h2>
			<p>Interviews</p>
		</div>
		<div class="icon"><i class="fa fa-briefcase"></i></div>
		<a href="do.php?_action=view_operations_reports&subview=view_interviews&_reset=1&filter_from_tr_start=$first_date&filter_to_tr_start=$last_date&filter_assessor=$assessor&filter_manager=$manager" class="small-box-footer">Click to see <i class="fa fa-arrow-circle-right"></i></a>
	</div>
</div>
HTML;

        return $html;
    }
}
