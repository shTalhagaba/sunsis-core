<?php
class edit_learner_exam_result implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $exam_result_id = isset($_REQUEST['exam_result_id']) ? $_REQUEST['exam_result_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
        {
            if(isset($_REQUEST['exam_result_id']))
                echo $this->deleteLearnerExamResultRecord($link, $_REQUEST['exam_result_id']);
            elseif(isset($_REQUEST['qualification_id']))
                echo $this->getExamStatus($link, $_REQUEST['qualification_id'], $_REQUEST['tr_id']);
            else
                echo 'Missing query string argument.';
            exit;
        }

        if($tr_id == '')
            throw new Exception('Missing Training Record ID.');

        $_SESSION['bc']->add($link, "do.php?_action=edit_learner_exam_result&tr_id=" . $tr_id, "Add/Edit Learner Exam Result");

        if($exam_result_id == '')
        {
            // New record
            $vo = new ExamResult();
            $vo->tr_id = $tr_id;
            $page_title = "Add Exam Result Details";
            $qualifications_ddl = DAO::getResultset($link, "SELECT REPLACE(id, '/', '') AS id, CONCAT(REPLACE(id, '/', ''), ' - ', internaltitle) FROM student_qualifications WHERE tr_id = '$tr_id'");
            $units_ddl = array();
            $exam_status = "";
        }
        else
        {
            $vo = ExamResult::loadFromDatabase($link, $exam_result_id);
            $page_title = "Edit Exam Result Details";
            $today_date = new Date(date('Y-m-d'));
            $qualifications_ddl = DAO::getResultset($link, "SELECT REPLACE(id, '/', '') AS id, CONCAT(REPLACE(id, '/', ''), ' - ', internaltitle) FROM student_qualifications WHERE tr_id = '$tr_id' AND REPLACE(id, '/', '') = '{$vo->qualification_id}'");
            //pre($vo);
            $units_ddl = $this->getUnits($link, $vo->qualification_id, $tr_id);
        }

        $attempts_ddl = array();
        for($i = 1; $i <= 15; $i++)
        {
            $attempts_ddl[] = array($i, $i);
        }

        $exam_types_ddl = array(
            array('1', 'Actual Exam'),
            array('2', 'Mock Exam')
        );

        $exam_subtype_ddl = array(
            array('1', 'Paper Based', ''),
            array('2', 'Web Based', '')
        );

        $exam_status_ddl = DAO::getResultset($link, "SELECT id, description FROM lookup_exam_status ORDER BY description");
        $exam_location_ddl = DAO::getResultset($link, "SELECT id, description FROM lookup_exam_location ORDER BY description");

        $enable_save = true;
        if( in_array($_SESSION['user']->type, [User::TYPE_LEARNER, User::TYPE_ORGANISATION_VIEWER, User::TYPE_SYSTEM_VIEWER, User::TYPE_REVIEWER]) )
            $enable_save = false;
	if( DB_NAME == "am_baltic" && in_array($_SESSION['user']->username, ["bmilburn"]) )
            $enable_save = true;

        // Cancel button URL
        $js_cancel = "window.location.replace('do.php?_action=read_training_record&exams_tab=1&id=$tr_id');";

        include('tpl_edit_learner_exam_result.php');
    }

    private function getExamStatus(PDO $link, $qualification_id, $tr_id)
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

    private function deleteLearnerExamResultRecord(PDO $link, $exam_result_id)
    {
        $result = DAO::execute($link, "DELETE FROM exam_results WHERE exam_results.id = " . $exam_result_id);
        if($result > 0)
            return 'The record has been successfully deleted.';
        else
            return 'Operation failed.';
    }

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
                //$final_ddl[] = array($unit_entry[$i]['reference'], $unit_entry[$i]['title']);
				$final_ddl[] = array(json_encode(['id'=>$unit_entry[$i]['reference'],'title'=>$unit_entry[$i]['title']]), $unit_entry[$i]['title']);
        }
        return $final_ddl;
    }
}
?>