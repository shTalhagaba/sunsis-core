<?php
class assessor_capacity implements IAction
{
    public function execute(PDO $link)
    {
        $fromDate = isset($_REQUEST['fromDate']) ? $_REQUEST['fromDate'] : '01/08/2020';
        $toDate = isset($_REQUEST['toDate']) ? $_REQUEST['toDate'] : '31/07/2021';
        $recalculate = isset($_REQUEST['recalculate']) ? $_REQUEST['recalculate'] : 0;
        $apprenticeship_title = isset($_REQUEST['apprenticeship_title']) ? $_REQUEST['apprenticeship_title'] : DAO::getSingleColumn($link, "SELECT DISTINCT apprenticeship_title FROM courses WHERE apprenticeship_title IS NOT NULL AND apprenticeship_title!='';");
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        $start_date = Date::toMySQL($fromDate);
        $end_date = Date::toMySQL($toDate);


        if($subaction == 'export_csv')
        {
            $this->exportToCSV($link, $start_date, $end_date);
            exit;
        }

        if($recalculate==1)
        {
            DAO::execute($link, "DROP TABLE retention_data");
            DAO::execute($link, "CREATE TABLE retention_data
            SELECT tr.id
            ,tr.l03
            ,tr.`start_date`
            ,tr.`target_date`
            ,tr.`closure_date`
            ,tr.`status_code`
            ,frameworks.`framework_code`
            ,frameworks.`framework_type`
            ,frameworks.`StandardCode`
            ,(SELECT LEFT(extractvalue(ilr, \"/Learner/LearningDelivery[LearnAimRef='ZPROG001']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode\"),1) FROM ilr WHERE ilr.`tr_id` = tr.id ORDER BY contract_id DESC, submission DESC LIMIT 0,1) AS Restart
            ,(SELECT CASE op_epa.task_status WHEN '16' THEN 'Pass' WHEN '17' THEN 'Merit' WHEN '18' THEN 'Distinction' WHEN '19' THEN 'Fail'  END FROM op_epa WHERE op_epa.tr_id = tr.id AND task = '8' ORDER BY op_epa.id DESC LIMIT 1) AS EPA_Result
            ,0 AS `NLevel`
            ,'                 ' AS ByStandard
            ,apprenticeship_title AS ApprenticeshipTitle
            ,(SELECT DATE_FORMAT(op_epa.task_actual_date, '%Y-%m-%d') FROM op_epa WHERE op_epa.`tr_id` = tr.`id` AND op_epa.`task` = '12' ORDER BY id DESC LIMIT 1) AS GatewayDate
            ,(SELECT DATE_FORMAT(op_epa.task_actual_date, '%Y-%m-%d') FROM op_epa WHERE op_epa.`tr_id` = tr.`id` AND op_epa.`task` = '8' AND op_epa.`task_status` = '19' ORDER BY id DESC LIMIT 1) AS Fail
	        ,(SELECT DATE_FORMAT(op_epa.task_peed_forecast_date, '%Y/%m/%d') AS peed_forecast_date FROM op_epa WHERE op_epa.task_type = '3' AND op_epa.tr_id = tr.id) AS PEEDDate
            FROM tr
            INNER JOIN student_frameworks ON student_frameworks.`tr_id` = tr.id
            INNER JOIN frameworks ON frameworks.`id` = student_frameworks.`id` AND framework_type IN (2,3,25)
            INNER JOIN courses ON courses.`framework_id` = frameworks.`id`
            WHERE start_date >= '2017-08-01';");

            DAO::execute($link, "UPDATE retention_data
            INNER JOIN lars201718.`Core_LARS_Standard` AS ls ON ls.`StandardCode` = retention_data.`StandardCode`
            SET NLevel = ls.`NotionalEndLevel`
            WHERE retention_data.StandardCode IS NOT NULL;
            ");

            DAO::execute($link, "UPDATE retention_data SET NLevel = 2 WHERE framework_type = 3 AND framework_code IS NOT NULL;");
            DAO::execute($link, "UPDATE retention_data SET NLevel = 3 WHERE framework_type = 2 AND framework_code IS NOT NULL;");
            DAO::execute($link, "UPDATE retention_data SET ByStandard = IF(StandardCode IS NOT NULL, CONCAT(\"Level \",NLevel, \" Standard\"), CONCAT(\"Level \", NLevel, \" Framework\"));");
            $d = date('d/m/Y h:i:s a', time());
            DAO::execute($link, "update configuration set value = '$d' where entity = 'Retention'");

        }

        $programmes_ddl = DAO::getResultset($link, "SELECT DISTINCT apprenticeship_title, apprenticeship_title, NULL FROM courses WHERE apprenticeship_title IS NOT NULL AND apprenticeship_title!='';");


        //$_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=assessor_capacity", "Assessor Capacity");

        if(DB_NAME=='am_baltic')
            include_once('tpl_assessor_capacity.php');
        else
            include_once('tpl_assessor_capacity2.php');
    }

    private function exportToCSV(PDO $link, $start_date, $end_date)
    {
            //$columns = $this->removeNotRequiredColumns($view->getViewName(), $columns);
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=LearningMentorCapacity.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }

        echo "Assessor,Max Capacity,Actual Caseload,Capacity,On Programme,Fails,Planned Achievers,Gateway Ready,Starts,PEED,BIL";
        echo "\r\n";

        $st = $link->query("SELECT
                        CONCAT(firstnames, ' ', surname) AS AssessorName
                        ,capacity AS MaxCapacity
                        ,(SELECT COUNT(*) FROM tr LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id WHERE status_code = 1 and learner_status != 'PC' and learner_status != 'GR' AND assessor = users.id and start_date <= '$end_date') AS ActualCaseload
                        ,(SELECT GROUP_CONCAT(tr.id) FROM tr LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id WHERE status_code = 1 and learner_status != 'PC' and learner_status != 'GR' AND assessor = users.id and start_date <= '$end_date') AS ActualCaseloadN
                        ,(SELECT COUNT(*) FROM tr WHERE status_code = 1 AND assessor = users.id AND start_date <= '$end_date') AS OnProgramme
                        ,(SELECT GROUP_CONCAT(tr.id) FROM tr WHERE status_code = 1 AND assessor = users.id AND start_date <= '$end_date') AS OnProgrammeN
                        ,(SELECT count(*) FROM tr LEFT JOIN op_epa ON tr.id = op_epa.tr_id WHERE assessor = users.id and task = 8 AND task_status = 19) as Fails
                        ,(SELECT GROUP_CONCAT(tr.id) FROM tr LEFT JOIN op_epa ON tr.id = op_epa.tr_id WHERE assessor = users.id and task = 8 AND task_status = 19) as FailsN
                        ,(SELECT COUNT(*) FROM tr WHERE status_code = 1 AND assessor = users.id AND (SELECT task_actual_date FROM op_epa WHERE op_epa.`tr_id` = tr.id AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y' ORDER BY id DESC LIMIT 1) = NULL AND (SELECT op_epa.task_actual_date FROM op_epa WHERE op_epa.`tr_id` = tr.id AND op_epa.task = '12' ORDER BY id DESC LIMIT 1) BETWEEN '$start_date' AND '$end_date') AS PlannedAchievers
                        ,(SELECT GROUP_CONCAT(tr.id) FROM tr WHERE status_code = 1 AND assessor = users.id AND (SELECT task_actual_date FROM op_epa WHERE op_epa.`tr_id` = tr.id AND op_epa.`task` = '5' AND op_epa.`task_applicable` = 'Y' ORDER BY id DESC LIMIT 1) = NULL AND (SELECT op_epa.task_actual_date FROM op_epa WHERE op_epa.`tr_id` = tr.id AND op_epa.task = '12' ORDER BY id DESC LIMIT 1) BETWEEN '$start_date' AND '$end_date') AS PlannedAchieversN
                        ,(SELECT COUNT(*) FROM tr LEFT JOIN op_epa ON tr.id = op_epa.tr_id WHERE assessor = users.id and status_code = 1 AND task = 1 AND task_applicable = 'Y' and start_date <= '$end_date') AS GatewayReady
                        ,(SELECT GROUP_CONCAT(tr.id) FROM tr LEFT JOIN op_epa ON tr.id = op_epa.tr_id WHERE assessor = users.id and status_code = 1 AND task = 1 AND task_applicable = 'Y' and start_date <= '$end_date') AS GatewayReadyN
                        ,(SELECT count(*) FROM inductees INNER JOIN induction ON inductees.id = induction.inductee_id WHERE induction_assessor = users.id and induction.induction_status IN ('TBA', 'S', 'H') AND induction_date BETWEEN '$start_date' AND '$end_date') AS `Starts`
                        ,(SELECT COUNT(*) FROM tr INNER JOIN tr_operations ON tr.id = tr_operations.tr_id WHERE assessor = users.id and tr.status_code = '1' AND EXTRACTVALUE(tr_operations.`peed_details`, '/Notes/Note[last()]/Status') IN ('Y')) AS PEED
                        ,(SELECT GROUP_CONCAT(tr.id) FROM tr INNER JOIN tr_operations ON tr.id = tr_operations.tr_id WHERE assessor = users.id and tr.status_code = '1' AND EXTRACTVALUE(tr_operations.peed_details, '/Notes/Note[last()]/Status') IN ('Y')) AS PEEDN
                        ,(SELECT COUNT(*) FROM tr LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id WHERE status_code = 6 AND assessor = users.id AND extractvalue(tr_operations.bil_details, '/Notes/Note[last()]/Type') IN ('F','O','Y') ) AS BIL
                        ,(SELECT GROUP_CONCAT(tr.id) FROM tr LEFT JOIN tr_operations ON tr_operations.tr_id = tr.id WHERE status_code = 6 AND assessor = users.id AND extractvalue(tr_operations.bil_details, '/Notes/Note[last()]/Type') IN ('F','O','Y') ) AS BILN
                        FROM users WHERE TYPE = 3 AND web_access = 1 ORDER BY firstnames;");
        while($row = $st->fetch())
        {
            echo $row['AssessorName'] . ",";
            echo $row['MaxCapacity'] . ',';
            echo $row['ActualCaseload'] . ",";
            $capacity = $row['MaxCapacity'] - $row['ActualCaseload'];
            echo $capacity . ',';
            echo $row['OnProgramme'] . ",";
            echo $row['Fails'] . ",";
            echo $row['PlannedAchievers'] . ",";
            echo $row['GatewayReady'] . ",";
            echo $row['Starts'] . ",";
            echo $row['PEED'] . ",";
            echo $row['BIL'] . ",";
            echo "\r\n";
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
