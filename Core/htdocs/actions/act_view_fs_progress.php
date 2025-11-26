<?php
class view_fs_progress implements IAction
{
    public function execute(PDO $link)
    {
        $export = isset($_REQUEST['export'])?$_REQUEST['export']:'';

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_fs_progress", "View FS Progress Report");

        $view = ViewFSProgress::getInstance($link);
        $view->refresh($link, $_REQUEST);

        if($export=='export')
        {
            $this->exportRecordsToExcel($link, $view);
        }

        require_once('tpl_view_fs_progress.php');
    }

    private function exportRecordsToExcel(PDO $link, ViewIAReport $view)
    {
        set_time_limit(0);
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');//$statement->setClause()
        $st = $link->query($statement->__toString());
        if($st)
        {
            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=ViewIAReport.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            $line = '';
            $line .= 'Tutor,Assessor,Course,L03,Surname,Forenames,Age At Start of Training,Employer Postcode,Location,Start Date,Planned End Date, Actual End Date, Nine Month End Date,Main Aim Level,Numeracy Level,Literacy Level,ICT Level,English FS (Reading),English FS (Reading) DateOfExam,English FS (Writing),English FS (Writing) DateOfExam,Maths FS,Maths FS DateOfExam,ICT FS,ICT FS DateOfExam';
            echo $line . "\r\n";
            while($row = $st->fetch(PDO::FETCH_ASSOC))
            {
                $line = str_replace(',', ' ', $row['tutor']) . ', ';
                $line .= str_replace(',', ' ', $row['assessor']) . ', ';
                $line .= str_replace(',', ' ', $row['course']) . ', ';
                $line .= $row['l03'] . ', ';
                $line .= str_replace(',','; ', $row['surname']) . ', ';
                $line .= str_replace(',','; ', $row['forenames']) . ', ';
                $line .= $row['age_at_start_of_training'] . ', ';
                $line .= $row['employer_postcode'] . ', ';
                $line .= $row['location'] . ', ';
                $line .= $row['start_date'] . ', ';
                $line .= $row['planned_end_date'] . ', ';
                $line .= $row['actual_end_date'] . ', ';
                $line .= $row['nine_month_end_date'] . ', ';
                $line .= $row['main_aim_level'] . ', ';
                $line .= $row['maths_level'] . ', ';
                $line .= $row['english_level'] . ', ';
                $line .= $row['ict_level'] . ', ';

                // get the ids of functional skills
                $english_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $row['training_record_id'] . " AND qualification_type = 'FS' AND LOCATE('English', internaltitle) > 0;");
                $maths_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $row['training_record_id'] . " AND qualification_type = 'FS' AND LOCATE('Mathematics', internaltitle) > 0;");
                $ict_fs_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') AS qid FROM student_qualifications WHERE tr_id = " . $row['training_record_id'] . " AND qualification_type = 'FS' AND LOCATE('ICT', internaltitle) > 0;");

                $echo_eng_fs = "EXEMPTED";
                $echo_eng_fs_read = "";
                $echo_eng_fs_write = "";
                $echo_maths_fs = "EXEMPTED";
                $echo_ict_fs = "EXEMPTED";

                if($english_fs_id == '')
                    $echo_eng_fs = "NA";
                if($maths_fs_id == '')
                    $echo_maths_fs = "NA";
                if($ict_fs_id == '')
                    $echo_ict_fs = "NA";

                $ilr = DAO::getSingleValue($link, "SELECT ilr FROM ilr WHERE tr_id = " . $row['training_record_id'] . " AND contract_id = " . $row['contract_id'] . " ORDER BY submission DESC LIMIT 0, 1");
                if(strpos($ilr,$english_fs_id) != false)
                    $echo_eng_fs = "REQUIRED";
                if(strpos($ilr,$maths_fs_id) != false)
                    $echo_maths_fs = "REQUIRED";
                if(strpos($ilr,$ict_fs_id) != false)
                    $echo_ict_fs = "REQUIRED";

                $res = DAO::getResultset($link, "SELECT CASE
	WHEN exam_results.`exam_result` IS NOT NULL THEN exam_results.`exam_result`
	WHEN exam_results.`exam_result` IS NULL AND exam_results.`exam_date` IS NOT NULL THEN 'BOOKED'
	END AS status, DATE_FORMAT(exam_results.exam_date, '%d/%m/%Y') AS exam_date FROM exam_results WHERE tr_id = '" . $row['training_record_id'] . "' AND qualification_id = '$english_fs_id' AND (unit_reference LIKE '%Reading%' OR unit_title LIKE '%Reading%') LIMIT 1;", DAO::FETCH_ASSOC);

                if(isset($res) && count($res) > 0)
                    $echo_eng_fs_read = $res[0]["status"];

                if($echo_eng_fs_read == '')
                    $line .= $echo_eng_fs. ', ';
                else
                    $line .= $echo_eng_fs_read. ', ';
                if($echo_eng_fs_read == "BOOKED")
                    $line .= $res[0]["exam_date"]. ', ';
                else
                    $line .= ', ';

                $res = DAO::getResultset($link, "SELECT CASE
	WHEN exam_results.`exam_result` IS NOT NULL THEN exam_results.`exam_result`
	WHEN exam_results.`exam_result` IS NULL AND exam_results.`exam_date` IS NOT NULL THEN 'BOOKED'
	END AS status, DATE_FORMAT(exam_results.exam_date, '%d/%m/%Y') AS exam_date FROM exam_results WHERE tr_id = '" . $row['training_record_id'] . "' AND qualification_id = '$english_fs_id' AND (unit_reference LIKE '%Writing%' OR unit_title LIKE '%Writing%') LIMIT 1;", DAO::FETCH_ASSOC);
                if(isset($res) && count($res) > 0)
                    $echo_eng_fs_write = $res[0]["status"];

                if($echo_eng_fs_write == '')
                    $line .= $echo_eng_fs. ', ';
                else
                    $line .= $echo_eng_fs_write. ', ';
                if($echo_eng_fs_write == "BOOKED")
                    $line .= $res[0]["exam_date"]. ', ';
                else
                    $line .= ', ';

                $res = DAO::getResultset($link, "SELECT CASE
	WHEN exam_results.`exam_result` IS NOT NULL THEN exam_results.`exam_result`
	WHEN exam_results.`exam_result` IS NULL AND exam_results.`exam_date` IS NOT NULL THEN 'BOOKED'
	END AS status, DATE_FORMAT(exam_results.exam_date, '%d/%m/%Y') AS exam_date FROM exam_results WHERE tr_id = '" . $row['training_record_id'] . "' AND qualification_id = '$maths_fs_id' LIMIT 1; ", DAO::FETCH_ASSOC);
                if(isset($res) && count($res) > 0)
                    $echo_maths_fs = $res[0]["status"];

                $line .= $echo_maths_fs. ', ';
                if($echo_maths_fs == "BOOKED")
                    $line .= $res[0]["exam_date"]. ', ';
                else
                    $line .= ', ';

                $res = DAO::getResultset($link, "SELECT CASE
	WHEN exam_results.`exam_result` IS NOT NULL THEN exam_results.`exam_result`
	WHEN exam_results.`exam_result` IS NULL AND exam_results.`exam_date` IS NOT NULL THEN 'BOOKED'
	END AS status, DATE_FORMAT(exam_results.exam_date, '%d/%m/%Y') AS exam_date FROM exam_results WHERE tr_id = '" . $row['training_record_id'] . "' AND qualification_id = '$ict_fs_id' LIMIT 1;", DAO::FETCH_ASSOC);
                if(isset($res) && count($res) > 0)
                    $echo_ict_fs = $res[0]["status"];

                $line .= $echo_ict_fs. ', ';
                if($echo_ict_fs == "BOOKED")
                    $line .= $res[0]["exam_date"]. ', ';
                else
                    $line .= ', ';

                echo $line . "\r\n";
            }
        }

        exit;
    }
}
?>