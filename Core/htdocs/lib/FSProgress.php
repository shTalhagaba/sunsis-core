<?php
class FSProgress extends Entity
{
    public static function loadFromDatabase(PDO $link, $id)
    {
        if($id == '')
        {
            return null;
        }

        $key = addslashes((string)$id);
        $query = <<<HEREDOC
SELECT
	*
FROM
	fs_progress
WHERE
	id='$key';
HEREDOC;
        $st = $link->query($query);

        $fs_progress = null;
        if($st)
        {
            $fs_progress = null;
            $row = $st->fetch();
            if($row)
            {
                $fs_progress = new FSProgress();
                $fs_progress->populate($row);
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find exam result for the training record. " . '----' . $query . '----' . $link->errorCode());
        }

        return $fs_progress;
    }

    public function save(PDO $link)
    {

        return DAO::saveObjectToTable($link, 'fs_progress', $this);
    }

    public function delete(PDO $link)
    {

    }


    public function isSafeToDelete(PDO $link)
    {
        return false;
    }

    public static function getLearnerInfo(PDO $link, $tr_id)
    {
        return DAO::getResultSet($link, "SELECT induction_fields.induction_date,tr.closure_date,courses.title AS programme
        ,(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE tr.tutor = users.id) AS tutor
        ,(SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator
        ,(SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor
        FROM tr
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
LEFT JOIN courses ON courses.id = courses_tr.course_id
  LEFT JOIN (
  SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, inductee_type, induction.`resourcer`,
  DATE_FORMAT(induction.`induction_date`, '%d/%m/%Y') AS induction_date, induction.arm AS account_rel_manager,
  CASE induction.sla_received
	WHEN 'YN' THEN 'Yes New'
	WHEN 'YO' THEN 'Yes Old'
	WHEN 'N' THEN 'No'
	WHEN 'R' THEN 'Rejected'
	WHEN '' THEN ''
  END AS sla_received,
  CASE induction.levy_payer
	WHEN 'Y' THEN 'Yes'
	WHEN 'N' THEN 'No'
	WHEN '' THEN ''
  END AS levy_payer
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
  where tr.id = '$tr_id'");

    }

    public static function updateFromSessionRegister(PDO $link, $session_entry)
    {
	if(!isset($session_entry['entry_tr_id']))
	{
		return;
	}
        $tr_id = $session_entry['entry_tr_id'];
        $last_fs_id = DAO::getSingleValue($link, "select id from fs_progress where tr_id = '$tr_id' order by id desc limit 1");
        if($last_fs_id)
        {
            $fs_progress = FSProgress::loadFromDatabase($link, $last_fs_id);
            if($session_entry['entry_exam_name']=="Functional Skills English Reading Test")
            {
                if($session_entry['entry_op_tracker_status'] == 'F')
                {
                    $fs_progress->english_overall_status_reading = "10";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'P')
                {
                    $fs_progress->english_overall_status_reading = "9";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'D')
                {
                    $fs_progress->english_overall_status_reading = "1";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'RP')
                {
                    $fs_progress->english_overall_status_reading = "1";
                }
            }
            elseif($session_entry['entry_exam_name']=="Functional Skills Writing Test")
            {
                if($session_entry['entry_op_tracker_status'] == 'F')
                {
                    $fs_progress->english_overall_status_writing = "10";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'P')
                {
                    $fs_progress->english_overall_status_writing = "9";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'D')
                {
                    $fs_progress->english_overall_status_writing = "1";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'RP')
                {
                    $fs_progress->english_overall_status_writing = "1";
                }
            }
            elseif($session_entry['entry_exam_name']=="SLC")
            {
                if($session_entry['entry_op_tracker_status'] == 'F')
                {
                    $fs_progress->scl_status = "6";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'P')
                {
                    $fs_progress->scl_status = "5";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'D')
                {
                    $fs_progress->scl_status = "1";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'RP')
                {
                    $fs_progress->scl_status = "1";
                }
            }
            elseif($session_entry['entry_exam_name']=="NCFE Level 2 Functional Skills Qualification in Mathematics Test")
            {
                if($session_entry['entry_op_tracker_status'] == 'F')
                {
                    $fs_progress->maths_test_status = "7";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'P')
                {
                    $fs_progress->maths_test_status = "6";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'D')
                {
                    $fs_progress->maths_test_status = "1";
                }
                elseif($session_entry['entry_op_tracker_status'] == 'RP')
                {
                    $fs_progress->maths_test_status = "1";
                }
            }

            $fs_progress->save($link);

        }

                /*
        // Here you can add your code/function.
        $fs_progress = new FSProgress();
            $fs_progress->tr_id = $session_entry['entry_tr_id'];
        // you can get unit ref in $session_entry['entry_exam_name']						
        if($session_entry['entry_op_tracker_status'] == 'F')
        {
            // do something for Pass
        }
        elseif($session_entry['entry_op_tracker_status'] == 'P')
        {
            // do something for Fail
        }
        elseif($session_entry['entry_op_tracker_status'] == 'D')
        {
            // do something for Did not Attend
        }
        elseif($session_entry['entry_op_tracker_status'] == 'RP')
        {
            // do something for Result Pending
        }
        */

    }


    public $id = NULL;
    public $tr_id = NULL;
    public $webinar_booked_date = NULL;
    public $webinar_attended_date = NULL;
    public $exam_status = NULL;
    public $comments = NULL;
    public $maths_overall_status;
    public $maths_mock_status;
    public $maths_mock_result;
    public $maths_course_date;
    public $maths_exam_date;
    public $maths_exam_result;
    public $maths_exam_score;
    public $maths_rft;
    public $maths_support_session;
    public $maths_achieved_date;
    public $english_overall_status;
    public $english_mock_status;
    public $english_mock_result;
    public $english_course_date;
    public $reading_exam_date;
    public $reading_exam_result;
    public $reading_exam_score;
    public $reading_rft;
    public $writing_exam_date;
    public $writing_exam_result;
    public $writing_exam_score;
    public $writing_rft;
    public $scl_status;
    public $reading_support_session;
    public $writing_support_session;
    public $english_achieved_date;
    public $required;
    public $english_evidence;
    public $maths_evidence;
    public $maths_test_status;
    public $comments_maths_test;
    public $maths_mock_nda_date;
    public $maths_mock_comments;
    public $english_course_tutor;
    public $english_course_comments;
    public $english_reading_mock_nda_date;
    public $english_writing_mock_nda_date;
    public $slc_result;

    public $fs_required;
    public $english_overall_status_reading;
    public $english_overall_status_writing;
    public $english_mock_status_reading;
    public $english_mock_result_reading;
    public $english_mock_status_writing;
    public $english_mock_result_writing;
    public $english_mock_nda_date;
    public $english_mock_comments;

    public $date_exam_result_received_maths;
    public $date_exam_result_received_reading;
    public $date_exam_result_received_writing;
    public $date_exam_result_received_slc;
    public $tutor_maths;
    public $tutor_reading;
    public $tutor_writing;
    public $tutor_slc;
    public $comments_maths = NULL;
    public $comments_reading = NULL;
    public $comments_writing = NULL;
    public $comments_slc = NULL;
    public $comments_maths_mock = NULL;
    public $comments_reading_mock = NULL;
    public $comments_writing_mock = NULL;
    public $course_date = NULL;
    public $slc_rft = NULL;
    public $english_course_status = NULL;
    public $english_course_overall_status = NULL;
    public $english_achieved_date2 = NULL;
    public $history = NULL;
    public $nda = NULL;

    public $progress_plan_next_date_of_action_maths = NULL;
    public $progress_plan_next_date_of_action_reading = NULL;
    public $progress_plan_next_date_of_action_writing = NULL;
    public $progress_plan_maths = NULL;
    public $progress_plan_reading = NULL;
    public $progress_plan_writing = NULL;
    public $progress_plan_set_date_maths = NULL;
    public $progress_plan_set_date_reading = NULL;
    public $progress_plan_set_date_writing = NULL;
    public $mock_set_date_maths = NULL;
    public $mock_set_date_reading = NULL;
    public $mock_set_date_writing = NULL;
    public $allocated_tutor = NULL;
    public $achieved = NULL;
    public $achieved_timestamp = NULL;
    public $fs_coach = NULL;
    public $walled_garden_enrolment_number = NULL;
    public $maths_forecasted_end_date = NULL;
    public $english_forecasted_end_date = NULL;
    public $english_mock_status_changed = NULL;
    public $maths_mock_status_changed = NULL;
    public $learner_risk = null;
    public $risk_comments = null;

    const REQUIRED = 1;
    const EXEMPTED = 2;
    const BOOKED = 3;
    const NOT_ATTENDED = 4;
    const ATTENDED = 5;

}
?>