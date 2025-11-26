<?php
class edit_learner_fs_progress implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $fs_progress_id = isset($_REQUEST['fs_progress_id']) ? $_REQUEST['fs_progress_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
        {
            if(isset($_REQUEST['fs_progress_id']))
                echo $this->deleteLearnerFSProgressRecord($link, $_REQUEST['fs_progress_id']);
            else
                echo 'Missing query string argument.';
            exit;
        }

        if($tr_id == '')
            throw new Exception('Missing Training Record ID.');

        $_SESSION['bc']->add($link, "do.php?_action=edit_learner_fs_progress&tr_id=" . $tr_id, "Add/Edit Learner FS Progress");

        if($fs_progress_id == '')
        {
            // New record
            $last_fs_id = DAO::getSingleValue($link, "select id from fs_progress where tr_id = '$tr_id' order by id desc limit 1");
            if($last_fs_id!="")
            {
                $vo = FSProgress::loadFromDatabase($link, $last_fs_id);
                $vo->id = null;
                $page_title = "Edit FS Progress Details";
                $today_date = new Date(date('Y-m-d'));
            }
            else
            {
                $vo = new FSProgress();
                $vo->tr_id = $tr_id;
                $page_title = "Add FS Progress Details";
                $exam_status = "";
            }
        }
        else
        {
            $vo = FSProgress::loadFromDatabase($link, $fs_progress_id);
            $page_title = "Edit FS Progress Details";
            $today_date = new Date(date('Y-m-d'));
        }

        $exam_status_ddl = DAO::getResultset($link, "SELECT id, description FROM lookup_exam_status ORDER BY description");

        $overall_status1 = Array(Array(1, "Required"), Array(2, "Booked"), Array(3, "Support Session Required"), Array(4, "Support Session Booked"), Array(6, "Not Required"), Array(7, "Invited"), Array(8, "Completed"));
        $overall_status2 = Array(Array(1, "Required"), Array(2, "Booked"), Array(3, "Support Session Required"), Array(4, "Support Session Booked"), Array(6, "Not Required"), Array(7, "Invited"), Array(9, "Pass"), Array(10, "Fail"));
        $test_status = Array(Array(1, "Required"), Array(2, "Invited"), Array(3, "Booked"), Array(4, "Support Session Required"), Array(5, "Support Session Booked"), Array(6, "Pass"), Array(7, "Fail"), Array(8, "Not Required"));
        $mock_status = Array(Array(1, "Required"), Array(2, "Issued"), Array(3, "Completed"), Array(4, "Outstanding"));
        $mock_result = Array(Array(1, "Pass"), Array(2, "Fail"));
        $exam_result = Array(Array(1, "Pass"), Array(2, "Fail"), Array(3, "Did not attend"));
        $rft = Array(Array(1, "RFT"), Array(2, "2"), Array(3, "3"), Array(4, "4"));
        $scl_status = Array(Array(1, "Required"), Array(2, "Booked"), Array(3, "Invited"), Array(4, "Not Required"), Array(5, "Pass"), Array(6, "Fail"));
        $required = Array(Array(1, "Maths"), Array(2, "English"), Array(3, "Both"), Array(4, "None"));
        $fs_required = Array(Array(1, "In progress"), Array(2, "Not Required"), Array(3, "Required"));
        $tutor = Array(Array(2, "Mehwish Parveen"),Array(3, "Iain Nicol"));
        $fs_coach = Array(Array(1, "Mehwish Parveen"),Array(2, "Angela Grady"));
        $learner_risk = Array(Array(1, "High Risk"),Array(2, "Medium Risk"));

        $enable_save = true;
        if($_SESSION['user']->type == User::TYPE_LEARNER)
            $enable_save = false;

		if(in_array($_SESSION['user']->id, [23428, 22988]))
			$enable_save = true;

        // Cancel button URL
        $js_cancel = "window.location.replace('do.php?_action=read_training_record&webinars_tab=1&id=$tr_id');";

        $pot_vo = TrainingRecord::loadFromDatabase($link,$tr_id);
        $html2 = $this->getFileDownloads($pot_vo,$fs_progress_id);

        $learner_info = FSProgress::getLearnerInfo($link, $tr_id);
        $end_date = ($learner_info[0][1]!="")?$learner_info[0][1]:date("d/m/Y");
        $info = Date::dateDiffInfo($learner_info[0][0], $end_date);
        $completion_date = new Date($learner_info[0][0]);
        $completion_date->addMonths(6);

        include('tpl_edit_learner_fs_progress.php');
    }

/*    private function getExamStatus(PDO $link, $qualification_id, $tr_id)
    {
        $return_status = "";
        $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
        $qualification_id = str_replace('/', '', $qualification_id);
        $rs = (int)DAO::getSingleValue($link, "SELECT COUNT(*) FROM ilr WHERE tr_id = '" . $training_record->id . "' AND contract_id = '" . $training_record->contract_id . "' AND LOCATE('" . $qualification_id . "', ilr) ORDER BY submission DESC LIMIT 0, 1");
        if($rs == 0)
            $return_status = ExamResult::EXEMPTED;
        elseif($rs > 0)
            $return_status = ExamResult::REQUIRED;
        return $return_status;
    }
*/

    private function getFileDownloads(TrainingRecord $pot_vo, $fs_progress_id, $section = null)
    {
        $learner_dir = Repository::getRoot().'/'.trim($pot_vo->username) . '/fs_progress/'.$fs_progress_id;
        $files = Repository::readDirectory($learner_dir);
        if(count($files) == 0){
            return "";
        }

        $html = "";

        $html .= <<<HEREDOC
<div class="Directory">
<table cellspacing="0" style="table-layout:fixed; width:570">
<col width="310"/><col width="70"/><col width="170"/>
<tr>
	<th>Filename</th><th>Size</th><th>Upload Date</th><th>&nbsp;</th>
</tr>
HEREDOC;

        /* @var $f RepositoryFile */
        foreach($files as $f)
        {
            if($f->isDir()){
                continue;
            }
            $html .= "<tr>\r\n";
            $html .= '<td align="left" style="cursor:pointer;word-wrap:break-word;" onclick="downloadFile(\''.$f->getRelativePath().'\');" title="Download file">'.htmlspecialchars((string)$f->getName()).'</td>';
            $html .= '<td align="right" style="font-family:monospace" width="70">'.Repository::formatFileSize($f->getSize()).'</td>';
            $html .= '<td align="right" style="font-family:monospace" width="170">'.date("d/m/Y H:i:s", $f->getModifiedTime()).'</td>';
            if($_SESSION['user']->isAdmin())
            {
                $html .= '<td align="right" width="20"><img src="/images/trash_can.png" title="Delete file" onclick="deleteFile(\''.$f->getRelativePath().'\');" style="cursor:pointer"/></td>';
            }
            else
            {
                $html .= '<td align="right" width="20">&nbsp;</td>';
            }
            $html .= "\r\n</tr>\r\n";
        }

        $html .= "</table>\r\n";
        $html .= "</div>\r\n";

        return $html;
    }

    private function deleteLearnerFSProgressRecord(PDO $link, $fs_progress_id)
    {
        $result = DAO::execute($link, "DELETE FROM fs_progress WHERE id = " . $fs_progress_id);
        if($result > 0)
            return 'The record has been successfully deleted.';
        else
            return 'Operation failed.';
    }
/*
    private function getUnits(PDO $link, $qualification_id, $tr_id)
    {
        $qualification_id = str_replace('/', '', $qualification_id);

        $sql = <<<HEREDOC
SELECT
	 student_qualifications.id,
	 student_qualifications.evidences
FROM
	 student_qualifications
WHERE
	 student_qualifications.tr_id = '$tr_id' AND REPLACE(student_qualifications.id, '/', '') = '$qualification_id' ;
HEREDOC;

        $student_qualifications = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

        $units_ddl = array();
        foreach ($student_qualifications AS $qualification)
        {
            $evidence = XML::loadSimpleXML($qualification['evidences']);

            $units = $evidence->xpath('//unit');
            $q_units = array();
            foreach ($units AS $unit)
            {
                $temp = array();
                $temp = (array)$unit->attributes();
                $temp = $temp['@attributes'];
                $temp['reference'] = str_replace('/','', $temp['reference']);
                if($temp['chosen'] == 'true')
//					$q_units[$temp['reference']] = $temp['reference'] . ' - ' . $temp['title'];
                    $q_units[] = $temp;
            }
            $units_ddl[] = $q_units;
        }
        $final_ddl = array();
        foreach($units_ddl AS $unit_entry)
        {
            for($i=0;$i<count($unit_entry);$i++)
                $final_ddl[] = array($unit_entry[$i]['reference'], $unit_entry[$i]['title']);
        }
        return $final_ddl;
    } */
}
?>