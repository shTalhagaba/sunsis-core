<?php
class edit_evidence_matrix_submission implements IAction
{
    public function execute(PDO $link)
    {

        ini_set('memory_limit','2048M');
        ini_set('max_input_vars','2000');

        // Validate data entry
        $project_id = isset($_REQUEST['project_id']) ? $_REQUEST['project_id'] : '';
        $submission_id = isset($_REQUEST['submission_id']) ? $_REQUEST['submission_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $assessor_id = isset($_REQUEST['assessor_id']) ? $_REQUEST['assessor_id'] : '';


        if($tr_id == '')
            $tr_id = DAO::getSingleValue($link, "select tr_id from tr_projects where id = '$project_id' limit 0,1");

        $course_id = DAO::getSingleValue($link, "select course_id from courses_tr where tr_id = '$tr_id';");

        if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
        {
            if(isset($_REQUEST['submission_id']))
                echo $this->deleteAssessmentPlanSubmission($link, $_REQUEST['submission_id'], $project_id, $tr_id);
            else
                echo 'Missing query string argument.';
            exit;
        }

        if($project_id == '' and $tr_id == '')
            throw new Exception('Missing Training Record ID.');

        $_SESSION['bc']->add($link, "do.php?_action=edit_evidence_matrix_submission&submission_id=$submission_id&project_id=$project_id&tr_id=$tr_id", "Add/Edit Learner Additional Support Session");

        $last_submission_id = DAO::getSingleValue($link, "select id from project_submissions where project_id = '$project_id' order by id desc limit 1");
        if($submission_id == '')
        {
            // New record
            $vo = new EvidenceMatrixSubmission();
            $vo->id = $submission_id;
            $vo->project_id = $project_id;
            $vo->matrix = DAO::getSingleValue($link, "select matrix from project_submissions where project_id = '$project_id' order by id desc limit 1");
            $page_title = "Add Evidence Matrix Submission";
            $exam_status = "";
        }
        else
        {
            $vo = EvidenceMatrixSubmission::loadFromDatabase($link, $submission_id);
            //$vo->mode = DAO::getSingleValue($link, "select mode from assessment_plan_log where id = '$vo->assessment_plan_id'");
            $page_title = "Edit Evidence Matrix Submission";
            $today_date = new Date(date('Y-m-d'));
        }

        $enable_save = true;
        /*if(($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_SYSTEM_VIEWER) && !in_array($_SESSION['user']->username, ['creay123', 'bblackett1']))
            $enable_save = false;
        if($submission_id!="" and $submission_id!=$last_submission_id)
            $enable_save = false;*/


        $iqa_dropdown = DAO::getResultSet($link, "SELECT id, concat(firstnames,' ',surname) FROM users where id IN (5371,23226,24165,20884,25771,27199,3324,21193,23425,2270,28791,29444) ORDER BY firstnames");

            // Cancel button URL
        if(DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo')
        {
            $assessor_ddl = DAO::getResultset($link, "SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE web_access = 1 AND TYPE IN (2, 3,7, 25,1)
UNION
SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE id = '$vo->assessor'
ORDER BY n;");

        }
        else
        {
            $assessor_ddl = DAO::getResultset($link, "SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE id in (select distinct assessor from tr) ORDER BY n;");
        }


        $mode_ddl = DAO::getResultSet($link, "SELECT id, project, NULL FROM evidence_project WHERE course_id IN (SELECT course_id FROM courses_tr WHERE tr_id = '$tr_id') ORDER BY project");

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
            array('3', 'Reduced Sample'),
        );

        $summative_statuses = array(
            array('1', 'Not Raised'),
            array('2', 'Raised'),
            array('3', 'Summative Actions (Resubmission Required)'),
            array('4', 'Summative Actions (Resubmission Not Required)'),
            array('5', 'SPV Complete'),
        );

        $iqa_status_ddl2 = array(
            array('1', 'Accept'),
            array('2', 'Reject'),
        );

        $rags = array(
            array('1', 'Red'),
            array('2', 'Amber'),
            array('3', 'Green'),
            array('4', 'Blue'),
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

        $iqa_reasons2 = array(
            array('1', 'VARCS not satisfied'),
            array('2', 'Evidence mapping error'),
            array('3', 'Lack of knowledge'),
            array('4', 'Attention to detail (Functional Skills/ Structure/ GDPR)'),
            array('5', 'Standards update'),
        );

        $assessor_reasons = array(
            array('1', '1st rework'),
            array('2', 'Outcomes not met'),
            array('3', 'Push back for higher grade'),
            array('4', 'Lack of evidence'),
            array('5', 'Error with context/layout/Functional Skills'),
        );

        $fail_reasons = array(
            array('1', 'Valid'),
            array('2', 'Authentic'),
            array('3', 'Reliable'),
            array('4', 'Current'),
            array('5', 'Sufficient'),
        );

        $extension_reasons = array(
            array('1', 'Learner missed deadline'),
            array('2', 'Annual Leave'),
            array('3', 'Illness/ Health and Wellbeing'),
            array('4', 'Exposure'),
            array('5', 'Work commitments'),
            array('6', 'Learner Capability'),
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

        $recommendations_type = array(
            array('1', 'Higher Grades'),
            array('2', 'Strengthen evidence / knowledge'),
            array('3', 'Missed opportunity'),
            array('4', 'Deselect Evidence'),
        );
        
        $coach_actioned_status = array(
            array('1', 'Yes'),
            array('2', 'Set as interview prep & manager approval'),
            array('3', 'N/A will be picked up in next submission'),
        );

        include('tpl_edit_evidence_matrix_submission.php');
    }


    private function deleteAssessmentPlanSubmission(PDO $link, $submission_id, $assessment_plan_id, $tr_id)
    {
        $notes = new stdClass();
        $notes->id = null;
        $notes->parent_table = "project_submissions";
        $notes->subject = "Submission Deleted";
        $notes->username = $_SESSION['user']->username;
        $notes->firstnames = $_SESSION['user']->firstnames;
        $notes->surname = $_SESSION['user']->surname;
        //$notes->parent_id = DAO::getSingleValue($link, "select ")$submission_id;
        $notes->parent_id = $assessment_plan_id;
        DAO::saveObjectToTable($link, 'notes', $notes);

        $result = DAO::execute($link, "DELETE FROM project_submissions WHERE id = " . $submission_id);
        DAO::execute($link, "DELETE FROM tr_projects WHERE id NOT IN (SELECT project_id FROM project_submissions);");

        http_redirect("do.php?_action=read_training_record&id=$tr_id");
    }
}
?>
