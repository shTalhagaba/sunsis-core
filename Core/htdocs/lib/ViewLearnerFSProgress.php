<?php
class ViewLearnerFSProgress extends View
{

    public static function getInstance(PDO $link, $id, $last)
    {

        DAO::execute($link, "update fs_progress set maths_course_date = (SELECT sessions.start_date FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE FIND_IN_SET('Functional Skills Mathematics', sessions.unit_ref) and entry_tr_id = '$id' order by start_date desc limit 1) where tr_id = '$id' and maths_course_date is null");
        DAO::execute($link, "update fs_progress set maths_exam_date = (SELECT sessions.start_date FROM sessions INNER JOIN session_entries ON sessions.id = session_entries.entry_session_id WHERE FIND_IN_SET('Functional Skills Mathematics Test', sessions.unit_ref) and entry_tr_id = '$id' order by start_date desc limit 1) where tr_id = '$id' and maths_exam_date is null");

        $where = '';
        if($last)
            $limit = " order by id desc limit 1 ";
        else
            $limit = "";

        // Create new view object
        $sql = <<<HEREDOC
SELECT
    fs_progress.id as id,
	tr.id as training_record_id,
	tr.firstnames,
	tr.surname,
    case allocated_tutor when 2 then "Mehwish Parveen" when 3 then "Iain Nicol" end as allocated_tutor, 
	induction_fields.induction_date,
	if(tr.closure_date is not null, DATEDIFF(closure_date, induction_fields.induction_date), DATEDIFF(CURDATE(), induction_fields.induction_date)) as days_on_programme,
	DATE_ADD(induction_fields.induction_date, INTERVAL 6 MONTH) AS target_completion_date,
    case maths_overall_status when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 6 then 'Not Required' when 7 then 'Invited' when 8 then 'Completed' when 9 then 'Pass' when 10 then 'Fail' end as maths_overall_status,
    case maths_mock_status when 1 then 'Required' when 2 then 'Issued' when 3 then 'Completed' when 4 then 'Outstanding' when 5 then 'Not Required' end as maths_mock_status,
    case maths_mock_result when 1 then 'Pass' when 2 then 'Fail' end as maths_mock_result,
    maths_mock_comments,
    maths_exam_date,
    maths_course_date,
    maths_mock_nda_date,
    english_reading_mock_nda_date,
    english_writing_mock_nda_date,
    case maths_exam_result when 1 then 'Pass' when 2 then 'Fail' when 3 then 'Did not attend' end as maths_exam_result,
    maths_exam_score,
    case maths_rft when 1 then 'RFT' when 2 then 'Not RFT' end maths_rft,
    maths_achieved_date,
    date_exam_result_received_maths as maths_exam_result_received_date,
    case tutor_maths when 1 then "Jo Hill" when 2 then "Mehwish Parveen" when 3 then "Iain Nicol" end as maths_tutor,
    comments_maths as maths_comments,
    case english_course_overall_status when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 6 then 'Not Required' when 7 then 'Invited' when 8 then 'Completed' when 9 then 'Pass' when 10 then 'Fail' end as english_overall_status,
    english_course_date,
    case english_course_status when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 5 then 'Uploaded' when 6 then 'Not Required' when 7 then 'Invited' end as english_course_status,
    english_achieved_date2 as english_achieved_date,
    case english_overall_status_reading when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 5 then 'Uploaded' when 6 then 'Not Required' when 7 then 'Invited' end as english_reading_status,
    reading_exam_date,
    case reading_exam_result when 1 then 'Pass' when 2 then 'Fail' when 3 then 'Did not attend' end as reading_exam_result,
    reading_exam_score,
    case reading_rft when 1 then 'RFT' when 2 then 'Not RFT' end reading_rft,
    case english_mock_status_reading when 1 then 'Required' when 2 then 'Issued' when 3 then 'Completed' end as reading_mock_status,
    case english_mock_result_reading when 1 then 'Pass' when 2 then 'Fail' end as reading_mock_result,
    case english_mock_status when 1 then "Required" when 2 then "Issued" when 3 then "Completed" when 4 then "Outstanding" when 5 then "Not Required" end as english_mock_status,        
    comments_reading_mock as reading_mock_comments,
    date_exam_result_received_reading as reading_exam_result_received_date,
    case tutor_reading when 1 then "Jo Hill" when 2 then "Mehwish Parveen" when 3 then "Iain Nicol" end as reading_tutor,
    comments_reading as reading_comments,
    case english_overall_status_writing when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 5 then 'Uploaded' when 6 then 'Not Required' when 7 then 'Invited' end as english_writing_status,
    writing_exam_date,
    case writing_exam_result when 1 then 'Pass' when 2 then 'Fail' when 3 then 'Did not attend' end as writing_exam_result,
    writing_exam_score,
    case writing_rft when 1 then 'RFT' when 2 then 'Not RFT' end writing_rft,
    case english_mock_status_writing when 1 then 'Required' when 2 then 'Issued' when 3 then 'Completed' end as writing_mock_status,
    case english_mock_result_writing when 1 then 'Pass' when 2 then 'Fail' end as writing_mock_result,
    comments_writing_mock as writing_mock_comments,
    date_exam_result_received_writing as writing_exam_result_received_date,
    case tutor_writing when 1 then "Jo Hill" when 2 then "Mehwish Parveen" when 3 then "Iain Nicol" end as writing_tutor,
    case scl_status when 1 then 'Required' when 2 then 'Booked' when 3 then 'Completed' when 4 then 'Not Required' end as slc_status,
    date_exam_result_received_slc as slc_date_exam_result_received,
    case tutor_slc when 1 then "Jo Hill" when 2 then "Mehwish Parveen" when 3 then "Iain Nicol" end as slc_tutor,
    course_date as slc_course_date,
    case slc_rft when 1 then 'RFT' when 2 then 'Not RFT' end slc_rft,
    comments_slc as slc_comments,
	induction_fields.math_cert AS maths_certificate,
    induction_fields.eng_cert AS english_certificate,
    english_course_comments,
    DATE_FORMAT(english_mock_nda_date,'%d/%m/%Y') as english_mock_nda_date,
    english_mock_comments,
    case maths_test_status when 1 then "Required" when 2 then "Invited" when 3 then "Booked" when 4 then "Support Session Required" when 5 then "Support Session Booked" when 6 then "Pass" when 7 then "Fail" when 8 then "Not Required" end as maths_test_status
FROM
	tr
	inner JOIN fs_progress ON fs_progress.tr_id = tr.id
	LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
  LEFT JOIN (
  SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, inductee_type, induction.`resourcer`,
  DATE_FORMAT(induction.`induction_date`, '%Y-%m-%d') AS induction_date, induction.arm AS account_rel_manager,
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
  END AS levy_payer,
  CASE induction.math_cert
    WHEN 'R' THEN 'Received'
    WHEN 'NR' THEN 'Not Received'
  END AS math_cert,
  CASE induction.eng_cert
    WHEN 'R' THEN 'Received'
    WHEN 'NR' THEN 'Not Received'
  END AS eng_cert
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
WHERE tr.id='$id' $where $limit
;
HEREDOC;

        $view = new ViewLearnerFSProgress();
        $view->setSQL($sql);


        return $view;
    }


    public function render(PDO $link, $last_entry)
    {

        $st = $link->query($this->getSQL());
        if($st)
        {
            echo '<div align="left">';
            echo '<h3>Maths</h3>';
            echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th  class="topRow">Maths Overall Status</th><th  class="topRow">Maths Course Date</th><th  class="topRow">Maths Test Status</th><th  class="topRow">Maths Test Date</th><th  class="topRow">Maths Achieved Date</th><th  class="topRow">Maths Comments</th></tr></thead>';
            echo '<tbody>';
            $overall_status = Array("","Required","Booked","Support Session Required","Support Session Booked","Completed");
            while($row = $st->fetch())
            {
                if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_REVIEWER)
                    echo '<tr>';
                else
                    echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_fs_progress&fs_progress_id=' . $row['id'] . '&tr_id=' . $row['training_record_id']);
                echo '<td align="left">' . $row['maths_overall_status'] . '</td>';
                echo '<td align="left">' . Date::toMedium($row['maths_course_date']) . '</td>';
                echo '<td align="left">' . $row['maths_test_status'] . '</td>';
                echo '<td align="left">' . Date::toMedium($row['maths_exam_date']) . '</td>';
                echo '<td align="left">' . Date::toMedium($row['maths_achieved_date']) . '</td>';
                echo '<td align="left">' . $row['maths_comments'] . '</td>';
                //echo '<td align="left">' . $last_entry->comments_maths . '</td>';

                echo '</tr>';

            }

            echo '</tbody></table></div>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

        $st = $link->query($this->getSQL());
        if($st)
        {
            echo '<div align="left">';
            echo '<h3>English</h3>';
            echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th  class="topRow">English Overall Status</th><th  class="topRow">English Course Date</th><th  class="topRow">English Reading Test Date</th><th  class="topRow">English Writing Test Date</th><th  class="topRow">SLC Date</th><th  class="topRow">English Achieved Date</th><th  class="topRow">English Comments</th></tr></thead>';
            echo '<tbody>';
            $overall_status = Array("","Required","Booked","Support Session Required","Support Session Booked","Completed");
            while($row = $st->fetch())
            {
                if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_REVIEWER)
                    echo '<tr>';
                else
                    echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_fs_progress&fs_progress_id=' . $row['id'] . '&tr_id=' . $row['training_record_id']);
                echo '<td align="left">' . $row['english_overall_status'] . '</td>';
                echo '<td align="left">' . Date::toMedium($row['english_course_date']) . '</td>';
                echo '<td align="left">' . Date::toMedium($row['reading_exam_date']) . '</td>';
                echo '<td align="left">' . Date::toMedium($row['writing_exam_date']) . '</td>';
                echo '<td align="left">' . Date::toMedium($row['slc_course_date']) . '</td>';
                echo '<td align="left">' . Date::toMedium($row['english_achieved_date']) . '</td>';
                echo '<td align="left">' . $row['english_course_comments'] . '</td>';
                //echo '<td align="left">' . $last_entry->english_course_comments . '</td>';
                echo '</tr>';

            }

            echo '</tbody></table></div>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

        $st = $link->query($this->getSQL());
        if($st)
        {
            echo '<div align="left">';
            echo '<h3>Maths Mocks</h3>';
            echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th  class="topRow">Maths Mock Status</th><th  class="topRow">Maths NDA Date</th><th  class="topRow">Maths Mock Comments</th></tr></thead>';
            echo '<tbody>';
            $overall_status = Array("","Required","Booked","Support Session Required","Support Session Booked","Completed");
            while($row = $st->fetch())
            {
                if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_REVIEWER)
                    echo '<tr>';
                else
                    echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_fs_progress&fs_progress_id=' . $row['id'] . '&tr_id=' . $row['training_record_id']);
                echo '<td align="left">' . $row['maths_mock_status'] . '</td>';
                echo '<td align="left">' . Date::toMedium($row['maths_mock_nda_date']) . '</td>';
                echo '<td align="left">' . $row['maths_mock_comments'] . '</td>';
                //echo '<td align="left">' . $last_entry->maths_mock_comments . '</td>';
                echo '</tr>';

            }

            echo '</tbody></table></div>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

        $st = $link->query($this->getSQL());
        if($st)
        {
            echo '<div align="left">';
            echo '<h3>English Mocks</h3>';
            echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th  class="topRow">English Mock Status</th><th  class="topRow">English Mock NDA Date</th><th  class="topRow">English Mock Comments</th></tr></thead>';
            echo '<tbody>';
            $overall_status = Array("","Required","Booked","Support Session Required","Support Session Booked","Completed");
            while($row = $st->fetch())
            {
                if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_REVIEWER)
                    echo '<tr>';
                else
                    echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_fs_progress&fs_progress_id=' . $row['id'] . '&tr_id=' . $row['training_record_id']);
                echo '<td align="left">' . $row['english_mock_status'] . '</td>';
                echo '<td align="left">' . Date::toMedium($row['english_mock_nda_date']) . '</td>';
                echo '<td align="left">' . $row['english_mock_comments'] . '</td>';
                //echo '<td align="left">' . $last_entry->comments_writing_mock . '</td>';
                echo '</tr>';

            }

            echo '</tbody></table></div>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }

}
?>