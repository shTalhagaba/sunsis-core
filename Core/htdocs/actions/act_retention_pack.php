<?php
class retention_pack implements IAction
{
    public function execute(PDO $link)
    {
        $fromDate = isset($_REQUEST['fromDate']) ? $_REQUEST['fromDate'] : '01/08/2020';
        $toDate = isset($_REQUEST['toDate']) ? $_REQUEST['toDate'] : '31/07/2021';
        $recalculate = isset($_REQUEST['recalculate']) ? $_REQUEST['recalculate'] : 0;
        $apprenticeship_title = isset($_REQUEST['apprenticeship_title']) ? $_REQUEST['apprenticeship_title'] : DAO::getSingleColumn($link, "SELECT DISTINCT apprenticeship_title FROM courses WHERE apprenticeship_title IS NOT NULL AND apprenticeship_title!='';");

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

        $start_date = Date::toMySQL($fromDate);
        $end_date = Date::toMySQL($toDate);


        //$_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=retention_pack", "Retention Dashboard");

        include_once('tpl_retention_pack.php');
    }
}
