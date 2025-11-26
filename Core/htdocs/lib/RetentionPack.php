<?php
class RetentionPack extends Entity
{
    public static function getCohort(PDO $link, $start_date, $end_date, $level, $app_titles)
    {
        $app_titles = "'" . (implode("','",$app_titles)) . "'";
        $where = " and ApprenticeshipTitle in ($app_titles) ";
        if($level!="")
            $where .= " and ByStandard = '$level' ";
        DAO::execute($link, "SET SESSION group_concat_max_len = 10000;");
        $query = "SELECT COUNT(*),GROUP_CONCAT(id) FROM retention_data
                    WHERE start_date BETWEEN '$start_date' AND '$end_date'
                    AND restart = 0 $where;
                    ";
        return DAO::getResultset($link, $query);
    }

    public static function getEarlyLeaver(PDO $link, $start_date, $end_date, $level, $week = 0, $app_titles)
    {
        $app_titles = "'" . (implode("','",$app_titles)) . "'";
        $where = " and ApprenticeshipTitle in ($app_titles) ";
        if($level!="")
            $where .= " and ByStandard = '$level' ";
        DAO::execute($link, "SET SESSION group_concat_max_len = 10000;");
        $query = "SELECT COUNT(*),GROUP_CONCAT(id) FROM retention_data
                WHERE
                (start_date BETWEEN '$start_date' AND '$end_date' AND restart = 0 AND status_code = 3 $where)
                OR
                (start_date BETWEEN '$start_date' AND '$end_date' AND restart = 0 $where AND CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) IN (SELECT CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) FROM retention_data WHERE status_code =3 AND Restart=1));
                ";

        return DAO::getResultset($link, $query);
    }

    public static function getEarlyLeaverByWeek(PDO $link, $start_date, $end_date, $level, $week = 0, $app_titles)
    {
        $weeks = Array(Array(0,28),Array(29,56),Array(57,84),Array(85,112),Array(113,140),Array(141,168),Array(169,196),Array(197,224),Array(225,252),Array(253,280),Array(281,308),Array(309,336),Array(337,364),Array(365,999));

        $start_week = $weeks[$week][0];
        $end_week = $weeks[$week][1];

        $app_titles = "'" . (implode("','",$app_titles)) . "'";
        $where = " and ApprenticeshipTitle in ($app_titles) ";
        if($level!="")
            $where .= " and ByStandard = '$level' ";
        DAO::execute($link, "SET SESSION group_concat_max_len = 10000;");
        $query = "SELECT count(*), GROUP_CONCAT(id) FROM retention_data
                WHERE
                (start_date BETWEEN '$start_date' AND '$end_date' AND restart = 0 $where AND status_code = 3 AND closure_date BETWEEN ADDDATE('$start_date', INTERVAL $start_week DAY) AND ADDDATE('$start_date', INTERVAL $end_week DAY))
                OR
                (start_date BETWEEN '$start_date' AND '$end_date' AND restart = 0 $where AND CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) IN (SELECT CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) FROM retention_data WHERE status_code =3 AND Restart=1 AND closure_date BETWEEN ADDDATE('$start_date', INTERVAL $start_week DAY) AND ADDDATE('$start_date', INTERVAL $end_week DAY)));
                ";

        return DAO::getResultset($link, $query);
    }

    public static function getAchievers(PDO $link, $start_date, $end_date, $level, $app_titles)
    {
        $app_titles = "'" . (implode("','",$app_titles)) . "'";
        $where = " and ApprenticeshipTitle in ($app_titles) ";
        if($level!="")
            $where .= " and ByStandard = '$level' ";
        DAO::execute($link, "SET SESSION group_concat_max_len = 10000;");
        $query = "SELECT COUNT(*),GROUP_CONCAT(id) FROM retention_data
                WHERE
                (start_date BETWEEN '$start_date' AND '$end_date' AND restart = 0 AND status_code = 2 $where)
                OR
                (start_date BETWEEN '$start_date' AND '$end_date' AND restart = 0 $where AND CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) IN (SELECT CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) FROM retention_data WHERE status_code = 2 AND Restart=1));
                ";

        return DAO::getResultset($link, $query);
    }

    public static function getEPAFail(PDO $link, $start_date, $end_date, $level, $app_titles)
    {
        $app_titles = "'" . (implode("','",$app_titles)) . "'";
        $where = " and ApprenticeshipTitle in ($app_titles) ";
        if($level!="")
            $where .= " and ByStandard = '$level' ";
        DAO::execute($link, "SET SESSION group_concat_max_len = 10000;");
        $query = "SELECT COUNT(*),GROUP_CONCAT(id) FROM retention_data
                WHERE
                (start_date BETWEEN '$start_date' AND '$end_date' AND restart = 0 $where AND EPA_Result = \"Fail\")
                OR
                (start_date BETWEEN '$start_date' AND '$end_date' AND restart = 0 $where AND CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) IN (SELECT CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) FROM retention_data WHERE EPA_Result = \"Fail\" AND Restart=1));
                ";

        return DAO::getResultset($link, $query);
    }

    public static function getOnProgramme(PDO $link, $start_date, $end_date, $level, $app_titles)
    {
        $app_titles = "'" . (implode("','",$app_titles)) . "'";
        $where = " and ApprenticeshipTitle in ($app_titles) ";
        if($level!="")
            $where .= " and ByStandard = '$level' ";
        DAO::execute($link, "SET SESSION group_concat_max_len = 10000;");
        $query = "SELECT COUNT(*),GROUP_CONCAT(id) FROM retention_data
                WHERE (start_date BETWEEN '$start_date' AND '$end_date' $where AND status_code = 1 AND restart = 0)
                OR
                (start_date BETWEEN '$start_date' AND '$end_date' AND restart = 0 $where AND status_code = 6 AND CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) NOT IN (SELECT CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) FROM retention_data WHERE status_code != 6 AND Restart=1));
                ";

        return DAO::getResultset($link, $query);
    }

    public static function getBIL(PDO $link, $start_date, $end_date, $level, $app_titles)
    {
        $app_titles = "'" . (implode("','",$app_titles)) . "'";
        $where = " and ApprenticeshipTitle in ($app_titles) ";
        if($level!="")
            $where .= " and ByStandard = '$level' ";
        DAO::execute($link, "SET SESSION group_concat_max_len = 10000;");
        $query = "SELECT count(*),GROUP_CONCAT(id) FROM retention_data
                WHERE
                (start_date BETWEEN '$start_date' AND '$end_date' $where AND restart = 0 AND status_code = 6 AND CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) NOT IN (SELECT CONCAT(l03,COALESCE(framework_code,1),COALESCE(StandardCode,1)) FROM retention_data WHERE status_code != 6 AND Restart=1));
                ";

        return DAO::getResultset($link, $query);
    }

}
?>