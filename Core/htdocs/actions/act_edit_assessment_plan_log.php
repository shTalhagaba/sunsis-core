<?php
class edit_assessment_plan_log implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $apl_id = isset($_REQUEST['apl_id']) ? $_REQUEST['apl_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
        {
            if(isset($_REQUEST['apl_id']))
                echo $this->deleteAssessmentPlanLog($link, $_REQUEST['apl_id']);
            else
                echo 'Missing query string argument.';
            exit;
        }

        if($tr_id == '')
            throw new Exception('Missing Training Record ID.');

        $_SESSION['bc']->add($link, "do.php?_action=edit_assessment_plan_log&tr_id=" . $tr_id, "Add/Edit Assessment Plan Log");

        if($apl_id == '')
        {
            // New record
            $vo = new AssessmentPlanLog();
            $vo->tr_id = $tr_id;
            $page_title = "Add Assessment Plan Log";
            $exam_status = "";
        }
        else
        {
            $vo = AssessmentPlanLog::loadFromDatabase($link, $apl_id);
            $page_title = "Edit Assessment Plan Log Details";
            $today_date = new Date(date('Y-m-d'));
        }

        $assessor_ddl = DAO::getResultset($link, "SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE web_access = 1 AND TYPE IN (3,7, 25, 1)
UNION
SELECT id, CONCAT(firstnames,' ',surname) AS n FROM users WHERE id = '$vo->assessor'
ORDER BY n;");


        $mode_ddl = DAO::getResultSet($link, "SELECT id, description, null FROM lookup_assessment_plan_log_mode ORDER BY description");

        //   usort($mode_ddl, 'sortByOption');

        $status_ddl = array(
            array('1', 'Green'),
            array('2', 'Yellow'),
            array('3', 'Red')
        );

        $paperwork_ddl = array(
            array('1', 'In progress'),
            array('2', 'Awaiting marking'),
            array('3', 'Complete'),
            array('4', 'Rework required'),
            array('5', 'IQA'),
            array('6', 'Overdue')
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

        include('tpl_edit_assessment_plan_log.php');
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