<?php
class edit_als implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $als_id = isset($_REQUEST['als_id']) ? $_REQUEST['als_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        /*if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
        {
            if(isset($_REQUEST['exam_result_id']))
                echo $this->deleteLearnerExamResultRecord($link, $_REQUEST['exam_result_id']);
            elseif(isset($_REQUEST['qualification_id']))
                echo $this->getExamStatus($link, $_REQUEST['qualification_id'], $_REQUEST['tr_id']);
            else
                echo 'Missing query string argument.';
            exit;
        }*/

        if($tr_id == '')
            throw new Exception('Missing Training Record ID.');

        $_SESSION['bc']->add($link, "do.php?_action=edit_als&tr_id=" . $tr_id, "Add/Edit ALS");

        if($als_id == '')
        {
            // New record
            $vo = new ALS();
            $vo->tr_id = $tr_id;
            $page_title = "Add ALS Details";
        }
        else
        {
            $vo = ALS::loadFromDatabase($link, $als_id);
            $page_title = "Edit ALS Details";
            $today_date = new Date(date('Y-m-d'));
        }

        $outcomes = array(
            array('1', 'Completed ALS Support Plan'),
            array('2', 'ALS Plan Completed - NO funding required'),
            array('3', 'No ALS support required')
        );

        $referred_by = array(
            array('1', 'Learner'),
            array('2', 'Assessor'),
            array('3', 'Employer')
        );

        $enable_save = true;
        if( in_array($_SESSION['user']->type, [User::TYPE_LEARNER, User::TYPE_ORGANISATION_VIEWER, User::TYPE_SYSTEM_VIEWER, User::TYPE_REVIEWER]) )
            $enable_save = false;

        // Cancel button URL
        $js_cancel = "window.location.replace('do.php?_action=read_training_record&als_tab=1&id=$tr_id');";

        include('tpl_edit_als.php');
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
    }*/
}
?>