<?php
class edit_assessment_plan_submission implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $assessment_plan_id = isset($_REQUEST['assessment_plan_id']) ? $_REQUEST['assessment_plan_id'] : '';
        $submission_id = isset($_REQUEST['submission_id']) ? $_REQUEST['submission_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if($tr_id == '')
            $tr_id = DAO::getSingleValue($link, "select tr_id from assessment_plan_log where id = '$assessment_plan_id' limit 0,1");

        if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
        {
            if(isset($_REQUEST['submission_id']))
                echo $this->deleteAssessmentPlanSubmission($link, $_REQUEST['submission_id'], $assessment_plan_id, $tr_id);
            else
                echo 'Missing query string argument.';
            exit;
        }

        if($assessment_plan_id == '' and $tr_id == '')
            throw new Exception('Missing Training Record ID.');

        $_SESSION['bc']->add($link, "do.php?_action=edit_assessment_plan_submission&submission_id=$submission_id&assessment_plan_id=$assessment_plan_id&tr_id=$tr_id", "Add/Edit Learner Additional Support Session");

        if($submission_id == '')
        {
            // New record
            $vo = new AssessmentPlanSubmission();
            $vo->id = $submission_id;
            $vo->assessment_plan_id = $assessment_plan_id;
            $vo->mode = DAO::getSingleValue($link, "select mode from assessment_plan_log where id = '$assessment_plan_id'");
            $page_title = "Add Assessment Plan Submission";
            $exam_status = "";
        }
        else
        {
            $vo = AssessmentPlanSubmission::loadFromDatabase($link, $submission_id);
            $vo->mode = DAO::getSingleValue($link, "select mode from assessment_plan_log where id = '$vo->assessment_plan_id'");
            $page_title = "Edit Assessment Plan Submission";
            $today_date = new Date(date('Y-m-d'));
        }

        $enable_save = true;
        if(($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_SYSTEM_VIEWER) && $_SESSION['user']->username!="creay123")
            $enable_save = false;

        // Cancel button URL
        if(DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo')
        {
            $assessor_ddl = DAO::getResultset($link, "SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE web_access = 1 AND TYPE IN (3,7, 25,1)
UNION
SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE id = '$vo->assessor'
ORDER BY n;");

        }
        else
        {
            $assessor_ddl = DAO::getResultset($link, "SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE id in (select distinct assessor from tr) ORDER BY n;");
        }


            $mode_ddl = DAO::getResultSet($link, "SELECT id, description, NULL FROM lookup_assessment_plan_log_mode WHERE framework_id IN (SELECT id FROM student_frameworks WHERE tr_id = '$tr_id') ORDER BY description");

        $iqa_person_ddl = DAO::getResultSet($link, "SELECT id, concat(firstnames, ' ', surname), NULL FROM users WHERE id IN (8478,5371)");

        //   usort($mode_ddl, 'sortByOption');

        if(DB_NAME=="am_city_skills")
        {
            $iqa_status_ddl = array(
                array('1', 'Accept'),
                array('2', 'Refer'),
                array('3', 'Development')
            );
        }
        else
        {
            $iqa_status_ddl = array(
                array('1', 'Accepted'),
                array('2', 'Rejected'),
            );
        }

        $iqa_status_ddl = array(
            array('1', 'Accepted'),
            array('2', 'Rejected'),
        );

        $rags = array(
            array('1', 'Reg'),
            array('2', 'Amber'),
            array('3', 'Green'),
        );

        $portfolio_enhancement = array(
            array('1', 'Above & beyond'),
            array('2', 'Role overview'),
        );

        $iqa_reasons = array(
            array('1', 'Lack of evidence'),
            array('2', 'Wrong dates'),
            array('3', 'Outcomes not met'),
            array('4', 'Error with context/layout/Functional Skills'),
        );

        $assessor_reasons = array(
            array('1', '1st rework'),
            array('2', 'Outcomes not met'),
            array('3', 'Push back for higher grade'),
            array('4', 'Lack of evidence'),
            array('5', 'Error with context/layout/Functional Skills'),
        );

        $system = array(
            array('1', 'Skilsure'),
            array('2', 'Smart Assessor'),
        );

        $attempts = array(
            array('1', 'Yes'),
            array('0', 'No')
        );

        $paperwork_ddl = array(
            array('1', 'In progress'),
            array('2', 'Awaiting marking'),
            array('3', 'Complete'),
            array('4', 'Rework required'),
            array('5', 'IQA'),
            array('6', 'Overdue')
        );

        $iqa_type_ddl = array(
            array('1', 'Observation of teaching'),
            array('2', 'Planning'),
            array('3', 'Review'),
            array('4', 'Assessment'),
        );

        include('tpl_edit_assessment_plan_submission.php');
    }


    private function deleteAssessmentPlanSubmission(PDO $link, $submission_id, $assessment_plan_id, $tr_id)
    {
        $result = DAO::execute($link, "DELETE FROM assessment_plan_log_submissions WHERE id = " . $submission_id);
        DAO::execute($link, "DELETE FROM assessment_plan_log WHERE id NOT IN (SELECT assessment_plan_id FROM assessment_plan_log_submissions);");
        http_redirect("do.php?_action=read_training_record&id=$tr_id");

        /*if($result > 0)
            return 'The record has been successfully deleted.';
        else
            return 'Operation failed.';*/
    }
}
?>
