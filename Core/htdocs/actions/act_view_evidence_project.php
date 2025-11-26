<?php
class view_evidence_project implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $apl_id = isset($_REQUEST['apl_id']) ? $_REQUEST['apl_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $super = isset($_REQUEST['super']) ? $_REQUEST['super'] : 0;

        $course_id = DAO::getSingleValue($link, "select course_id from courses_tr where tr_id = '$tr_id'");

        if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
        {
            if(isset($_REQUEST['apl_id']))
                echo $this->deleteEvidenceProject($link, $_REQUEST['apl_id']);
            else
                echo 'Missing query string argument.';
            exit;
        }

        if($tr_id == '')
            throw new Exception('Missing Training Record ID.');

        $_SESSION['bc']->add($link, "do.php?_action=view_evidence_project&tr_id=$tr_id&apl_id=$apl_id", "Add/Edit Evidence Project");

        if($apl_id == '')
        {
            // New record
            $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
            $vo = new EvidenceProject();
            $vo->tr_id = $tr_id;
            $vo->assessor = $tr->assessor;
            $page_title = "Add Evidence Project";
            $exam_status = "";
        }
        else
        {
            $vo = EvidenceProject::loadFromDatabase($link, $apl_id, $super);
            $page_title = "Edit Evidence Project Details";
            $today_date = new Date(date('Y-m-d'));
        }

        $assessor_ddl = DAO::getLookupTable($link, "SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE web_access = 1 AND TYPE IN (3,7, 25,1)
UNION
SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE id = '$vo->assessor'
ORDER BY n;");


        //pre($assessor_ddl);
        $mode_ddl = DAO::getResultSet($link, "SELECT id, project, NULL FROM evidence_project WHERE course_id IN (SELECT course_id FROM courses_tr WHERE tr_id = '$tr_id') ORDER BY project");


        $iqa_person_ddl = DAO::getResultSet($link, "SELECT id, concat(firstnames, ' ', surname), NULL FROM users WHERE id IN (8478,5371,23226,25165,25771)");

        $rags = array(
            array('1', 'Red'),
            array('2', 'Amber'),
            array('3', 'Green'),
            array('4', 'Blue'),
        );

        $sample_types = array(
            array('1', '1st time check'),
            array('2', 'previously checked by IQA'),
        );

        $actioned = array(
            array('1', 'Addressed in interview prep'),
            array('2', 'Programme manager authorised'),
        );

        //   usort($mode_ddl, 'sortByOption');

        $iqa_status_ddl = array(
            array('1', 'Accepted'),
            array('2', 'Rejected'),
        );

        $paperwork_ddl = array(
            array('1', 'In progress'),
            array('2', 'Awaiting marking'),
            array('3', 'Complete'),
            array('4', 'Rework required'),
            array('5', 'IQA'),
            array('6', 'Overdue'),
            array('7', 'IQA Rejected'),
            array('8', 'IQA Recheck')
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

        /*Database & Campaign Management	Legislation	Project Management
        Sales Process	Technical	Data Migration
        Data manipulating & Linking	Data Analysis Security & Policies	Collect & Compile Data
        Performance Queries 	Statistical Analysis	Analytical Techniques
        Data Quality	Applications	Reporting Data
        Presenting Data	Data Architecture 	Business Analysis
        Investigation Techniques	Business Process Modelling 	Requirements Engineering & Management
        Data Modelling 	Gap Analysis	Acceptance Testing
        Stakeholder Analysis & Management 	Business Impact Assessment 	Design Networks from a Specification
        Diagnostic Tools & Techniques 	Documenting 	Effective Business Operation
        Integrating Network Software	Interpret Written Requirements and Tech Specs 	Logging & Responding to Calls
        Monitor Test & Adjust Networks 	Network Installation	Network Performance
        Service Level Agreements 	Troubleshooting & Repair	Upgrading Network Systems
        Business Environment 	Deployment	Design
        Operational Requirements 	Testing 	User Interface
                Advise and Support Others	Conduct Software Testing	Design Test Strategies
        Developing & Collecting Data	Implementing Software Testing	Legislation & Standards
        Presenting Test Results 	Results vs Expectations 	Software Requirements
        Test Cases	Test Outcomes
        */


        $enable_save = true;
        if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_SYSTEM_VIEWER)
            $enable_save = false;

        // Cancel button URL
        $js_cancel = "window.location.replace('do.php?_action=read_training_record&webinars_tab=1&id=$tr_id');";

        include('tpl_view_evidence_project.php');
    }

    function sortByOption($a, $b) {
        return strcmp($a[1], $b[1]);
    }

    private function deleteAssessmentPlanLog(PDO $link, $apl_id)
    {
        $result = DAO::execute($link, "DELETE FROM assessment_plan_log WHERE id = " . $apl_id);
        if($result > 0)
            return 'The record has been successfully deleted.';
        else
            return 'Operation failed.';
    }
}
?>