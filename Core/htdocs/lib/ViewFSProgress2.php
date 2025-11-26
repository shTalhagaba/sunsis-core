<?php
class ViewFSProgress2 extends View
{

    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;
        if(!isset($_SESSION[$key]))
        {
            $where = '';
            if($_SESSION['user']->isAdmin())
            {
                $where = " ";
            }
            elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
            {
                $emp = $_SESSION['user']->employer_id;
                $emp_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $emp);
                $where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp')" ;
            }

            // Create new view object

            $sql = <<<HEREDOC
SELECT
	tr.id as training_record_id,
	courses.title as programme,
    courses.apprenticeship_title as apprenticeship_title,
	tr.firstnames,
	tr.surname,
	DATE_FORMAT(induction_fields.induction_date, '%d/%m/%Y') AS induction_date,
    case allocated_tutor when 2 then "Mehwish Parveen" when 3 then "Iain Nicol" end as allocated_tutor,
    (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
    (SELECT CONCAT(users.firstnames,  ' ' , users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,

    if(tr.closure_date is not null, DATEDIFF(closure_date, induction_fields.induction_date), DATEDIFF(CURDATE(), induction_fields.induction_date)) as days_on_programme,
	DATE_FORMAT(DATE_ADD(induction_fields.induction_date, INTERVAL 6 MONTH), '%d/%m/%Y') AS target_completion_date,
	CASE required WHEN 1 THEN "Maths" WHEN 2 THEN "English" WHEN 3 THEN "Both" WHEN 4 THEN "None" WHEN 5 THEN "Achieved" ELSE "" END AS required_to_complete,
    case maths_overall_status when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 6 then 'Not Required' when 7 then 'Invited' when 8 then 'Completed' when 9 then 'Pass' when 10 then 'Fail' end as maths_overall_status,
    case maths_mock_status when 1 then 'Required' when 2 then 'Issued' when 3 then 'Completed' when 4 then 'Outstanding' when 5 then 'Not Required' end as maths_mock_status,
    case maths_mock_result when 1 then 'Pass' when 2 then 'Fail' end as maths_mock_result,
    maths_mock_comments as maths_mock_comments,
    DATE_FORMAT(maths_course_date, '%d/%m/%Y') AS maths_course_date,
    DATE_FORMAT(maths_exam_date, '%d/%m/%Y') AS maths_exam_date,
    case maths_exam_result when 1 then 'Pass' when 2 then 'Fail' when 3 then 'Did not attend' end as maths_exam_result,
    maths_exam_score,
    case maths_rft when 1 then 'RFT' when 2 then 'Not RFT' end maths_rft,
    maths_achieved_date,
    date_exam_result_received_maths as maths_exam_result_received_date,
    case tutor_maths when 1 then "Jo Hill" when 2 then "Mehwish Parveen" when 3 then "Iain Nicol" end as maths_tutor,
    comments_maths as maths_comments,
    case english_course_overall_status when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 6 then 'Not Required' when 7 then 'Invited' when 8 then 'Completed' when 9 then 'Pass' when 10 then 'Fail' end as english_overall_status,
    DATE_FORMAT(english_course_date, '%d/%m/%Y') AS english_course_date,
    case english_course_status when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 5 then 'Completed' when 6 then 'Not Required' end as english_course_status,
    english_achieved_date2 as english_achieved_date,
    case english_overall_status_reading when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 5 then 'Completed' when 6 then 'Not Required' end as english_reading_status,
    DATE_FORMAT(reading_exam_date, '%d/%m/%Y') AS reading_exam_date,
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
    case english_overall_status_writing when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 5 then 'Completed' when 6 then 'Not Required' end as english_writing_status,
    DATE_FORMAT(writing_exam_date, '%d/%m/%Y') AS writing_exam_date,
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
    DATE_FORMAT(course_date,'%d/%m/%Y') as slc_course_date,
    DATE_FORMAT(maths_mock_nda_date,'%d/%m/%Y') as maths_mock_nda_date,
    DATE_FORMAT(english_mock_nda_date,'%d/%m/%Y') as english_mock_nda_date,
    case slc_rft when 1 then 'RFT' when 2 then 'Not RFT' end slc_rft,
    comments_slc as slc_comments,
	induction_fields.math_cert AS maths_certificate,
    induction_fields.eng_cert AS english_certificate,
    english_course_comments,
    english_mock_comments,
    fs_progress.created as created,
    fs_progress.modified as modified,
    case fs_required when 1 then "In progress" when 2 then "Not required" when 3 then "Required" end fs_required,
    case english_evidence when 1 then "Yes" when 2 then "No" else "" end as english_evidence,        
    case maths_evidence when 1 then "Yes" when 2 then "No" else "" end as maths_evidence,
    comments as general_comments,
    DATE_FORMAT(tr.target_date, '%d/%m/%Y') AS planned_end_date,
	CASE maths_test_status WHEN 1 THEN "Required" WHEN 2 THEN "Invited" WHEN 3 THEN "Booked" WHEN 4 THEN "Support Session Required" WHEN 5 THEN "Support Session Booked" WHEN 6 THEN "Pass" WHEN 7 THEN "Fail" WHEN 8 THEN "Not Required" END maths_test_status,
	maths_exam_date,
	CASE english_overall_status_reading WHEN 1 THEN "Required" WHEN 2 THEN "Booked" WHEN 3 THEN "Support Session Required" WHEN 4 THEN "Support Session Booked" WHEN 6 THEN "Not Required" WHEN 7 THEN "Invited" WHEN 8 THEN "Completed" WHEN 9 THEN "Pass" WHEN 10 THEN "Fail" END english_overall_status_reading,
	reading_exam_date,
	CASE english_overall_status_writing WHEN 1 THEN "Required" WHEN 2 THEN "Booked" WHEN 3 THEN "Support Session Required" WHEN 4 THEN "Support Session Booked" WHEN 6 THEN "Not Required" WHEN 7 THEN "Invited" WHEN 8 THEN "Completed" WHEN 9 THEN "Pass" WHEN 10 THEN "Fail" END english_overall_status_writing,
	writing_exam_date,
	CASE scl_status WHEN 1 THEN "Required" WHEN 2 THEN "Booked" WHEN 3 THEN "Invited" WHEN 4 THEN "Not Required" WHEN 5 THEN "Pass" WHEN 6 THEN "Fail" END slc_status,
	course_date AS slc_date,
    if(english_evidence = 1, "Yes", "No") as english_evidence_seen,
    if(maths_evidence = 1, "Yes", "No") as maths_evidence_seen,
    if(achieved = 1, "Yes", "No") as achieved,
    achieved_timestamp,
    case fs_coach when 1 then "Mehwish Parveen" when 2 then "Angela Grady" end as fs_coach,
    walled_garden_enrolment_number,
    maths_forecasted_end_date,
    english_forecasted_end_date,
    CASE learner_risk WHEN 1 THEN "High Risk" WHEN 2 THEN "Medium RIsk" END learner_risk,
    risk_comments
FROM
	tr
	LEFT  JOIN fs_progress ON fs_progress.tr_id = tr.id AND fs_progress.id = (SELECT id FROM fs_progress AS fs2 WHERE fs2.tr_id = tr.id ORDER BY id DESC LIMIT 1)
	LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
    left join courses on courses.id = courses_tr.course_id
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
$where
ORDER BY surname;
HEREDOC;


            $view = $_SESSION[$key] = new ViewFSProgress2();
            $view->setSQL($sql);

            $parent_org = $_SESSION['user']->employer_id;

            // Add view filters
            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(0,'No limit',null,null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            // Surname Sort
            $options = array(
                0=>array(1, 'Surname (asc)', null, 'ORDER BY surname'),
                1=>array(2, 'Surname (desc)', null, 'ORDER BY surname DESC'));
            $f = new DropDownViewFilter('order_by', $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            // Add view filters
            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
                2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
                3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
                4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
                5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
                6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7=>array(7, '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
            $f = new DropDownViewFilter('filter_record_status', $options, 1, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            // Add view filters
            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. FS Progress Report', null, null),
                2=>array(2, '2. FS Progress Mocks Report', null, null),
                3=>array(3, '3. FS Achievement Report', null, null),
                4=>array(4, '4. FS Progress Sweep Report', null, null),
                5=>array(5, '5. FS Progress Exemption Report', null, null),
                6=>array(6, '6. Test Booking Report', null, null));
            $f = new DropDownViewFilter('filter_report_type', $options, 1, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $format = "WHERE induction_fields.induction_date >= '%s'";
            $f = new DateViewFilter('from_induction_date', $format, '');
            $f->setDescriptionFormat("From induction date: %s");
            $view->addFilter($f);

            $format = "WHERE induction_fields.induction_date <= '%s'";
            $f = new DateViewFilter('to_induction_date', $format, '');
            $f->setDescriptionFormat("To induction date: %s");
            $view->addFilter($f);

        }

        return $_SESSION[$key];
    }


    public function render(PDO $link, $columns)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());

        if($st)
        {

            $report_type = $this->getFilterValue('filter_report_type');

            echo $this->getViewNavigator();
            echo '<div align="center"><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr>';

            if($report_type==1)
            {
                echo '<th class="topRow">Programme</th><th class="topRow">Firstnames</th><th class="topRow">Surname</th><th class="topRow">Induction Date</th><th class="topRow">Planned End Date</th><th class="topRow">Days on Programme</th><th class="topRow">FS Exemption Status</th><th class="topRow">English Evidence Seen</th><th class="topRow">Maths Evidence Seen</th><th class="topRow">Exemption Evidence</th><th class="topRow">Achieved</th><th class="topRow">FS Achieved Date</th><th class="topRow">Required to complete</th><th class="topRow">Maths Overall Status</th><th class="topRow">Maths Course Date</th><th class="topRow">Maths Comments</th><th class="topRow">Maths Exam Date</th><th class="topRow">English Overall Status</th><th class="topRow">English Course Date</th><th class="topRow">English Comments</th><th class="topRow">Reading Exam Date</th><th class="topRow">Writing Exam Date</th><th class="topRow">SLC Date</th><th class="topRow">FS Coach</th><th class="topRow">Walled Garden Enrolment Number</th><th class="topRow">Maths Forecasted End Date</th><th class="topRow">English Forecasted End Date</th><th class="topRow">Learner Risk</th><th class="topRow">Risk Comments</th>';
            }
            elseif($report_type==2)
            {
                echo '<th class="topRow">Programme</th><th class="topRow">Firstnames</th><th class="topRow">Surname</th><th class="topRow">Planned End Date</th><th class="topRow">Tutor</th><th class="topRow">Required to complete</th><th class="topRow">Maths Mock Status</th><th class="topRow">Maths Mock NDA Date</th><th class="topRow">Maths Mock Comments</th><th class="topRow">English Mock Status</th><th class="topRow">English Mock NDA Date</th><th class="topRow">English Mock Comments</th>';
            }    
            elseif($report_type==3)
            {
                echo '<th class="topRow">Programme</th><th class="topRow">Firstnames</th><th class="topRow">Surname</th><th class="topRow">Induction Date</th><th class="topRow">Planned End Date</th><th class="topRow">Tutor</th><th class="topRow">Maths Overall Status</th><th class="topRow">Maths Achieved Date</th><th class="topRow">English Overall Status</th><th class="topRow">English Achieved Date</th>';
            }    
            elseif($report_type==4)
            {
                echo '<th class="topRow">Programme</th><th class="topRow">Firstnames</th><th class="topRow">Surname</th><th class="topRow">Induction Date</th><th class="topRow">Planned End Date</th><th class="topRow">Tutor</th><th class="topRow">Required to complete</th><th class="topRow">Maths Overall Status</th><th class="topRow">Maths Course Date</th><th class="topRow">Maths Exam Date</th><th class="topRow">Maths Exam Result</th><th class="topRow">Maths Exam Score</th><th class="topRow">Maths RFT</th><th class="topRow">Maths Achieved Date</th><th class="topRow">Maths Comments</th><th class="topRow">English Overall Status</th><th class="topRow">English Course Date</th><th class="topRow">English Course Status</th><th class="topRow">English Achieved Date</th><th class="topRow">English Reading Status</th><th class="topRow">Reading Exam Date</th><th class="topRow">Reading Exam Result</th><th class="topRow">Reading Exam Score</th><th class="topRow">Reading RFT</th><th class="topRow">Reading Exam Result Recieved Date</th><th class="topRow">English Writing Status</th><th class="topRow">Writing Exam Date</th><th class="topRow">Writing Exam Result</th><th class="topRow">Writing Exam Score</th><th class="topRow">Writing RFT</th><th class="topRow">Writing Exam Result Recieved Date</th><th class="topRow">SLC Status</th><th class="topRow">SLC Date Exam Result Recieved</th><th class="topRow">SLC Course Date</th><th class="topRow">SLC RFT</th>';
            }    
            elseif($report_type==5)
            {
                echo '<th class="topRow">Programme</th><th class="topRow">Firstnames</th><th class="topRow">Surname</th><th class="topRow">Induction Date</th><th class="topRow">Planned End Date</th><th class="topRow">Allocated Tutor</th><th class="topRow">Coordinator</th><th class="topRow">Learning Mentor</th><th class="topRow">Days on Programme</th><th class="topRow">Target Completion Date</th><th class="topRow">Required to complete</th><th class="topRow">FS Exemption Status</th><th class="topRow">English Exemption Evidence Seen</th><th class="topRow">Maths Exemption Evidence Seen</th><th class="topRow">Comments</th>';
            }    
            elseif($report_type==6)
            {
                echo '<th class="topRow">Programme</th><th class="topRow">Firstnames</th><th class="topRow">Surname</th><th class="topRow">Induction Date</th><th class="topRow">Planned End Date</th><th class="topRow">Maths Test Status</th><th class="topRow">Maths Test Date</th><th class="topRow">English Reading Test Status</th><th class="topRow">English Reading Test Date</th><th class="topRow">English Writing Test Status</th><th class="topRow">English Writing Test Date</th><th class="topRow">SLC Status</th><th class="topRow">SLC Date</th>';
            }    

            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['training_record_id']);
                
                if($report_type==1)
                {
                    echo '<td align="center">' . $row['programme'] . '</td><td>' . $row['firstnames'] . '</td><td>' . $row['surname'] . '</td><td>' . $row['induction_date'] . '</td><td>' . $row['planned_end_date'] . '</td><td>' . $row['days_on_programme'] . '</td><td>' . $row['fs_required'] . '</td><td>' . $row['english_evidence_seen'] . '</td><td>' . $row['maths_evidence_seen'] . '</td><td>' . $row['general_comments'] . '</td><td>' . $row['achieved'] . '</td><td>' . $row['achieved_timestamp'] . '</td><td>' . $row['required_to_complete'] . '</td><td>' . $row['maths_overall_status'] . '</td><td>' . $row['maths_course_date'] . '</td><td>' . $row['maths_comments'] . '</td><td>' . $row['maths_exam_date'] . '</td><td>' . $row['english_overall_status'] . '</td><td>' . $row['english_course_date'] . '</td><td>' . $row['english_course_comments'] . '</td><td>' . $row['reading_exam_date'] . '</td><td>' . $row['writing_exam_date'] . '</td><td>' . $row['slc_course_date'] . '</td><td>' . $row['fs_coach'] . '</td><td>' . $row['walled_garden_enrolment_number'] . '</td><td>' . $row['maths_forecasted_end_date'] . '</td><td>' . $row['english_forecasted_end_date'] . '</td><td>' . $row['learner_risk'] . '</td><td>' . $row['risk_comments'] . '</td>';
                }
                elseif($report_type==2)
                {
                    echo '<td align="center">' . $row['programme'] . '</td><td>' . $row['firstnames'] . '</td><td>' . $row['surname'] . '</td><td>' . $row['planned_end_date'] . '</td><td>' . $row['allocated_tutor'] . '</td><td>' . $row['required_to_complete'] . '</td><td>' . $row['maths_mock_status'] . '</td><td>' . $row['maths_mock_nda_date'] . '</td><td>' . $row['maths_mock_comments'] . '</td><td>' . $row['english_mock_status'] . '</td><td>' . $row['english_mock_nda_date'] . '</td><td>' . $row['english_mock_comments'] . '</td>';
                }    
                elseif($report_type==3)
                {
                    echo '<td align="center">' . $row['programme'] . '</td><td>' . $row['firstnames'] . '</td><td>' . $row['surname'] . '</td><td>' . $row['induction_date'] . '</td><td>' . $row['planned_end_date'] . '</td><td>' . $row['allocated_tutor'] . '</td><td>' . $row['maths_overall_status'] . '</td><td>' . $row['maths_achieved_date'] . '</td><td>' . $row['english_overall_status'] . '</td><td>' . $row['english_achieved_date'] . '</td>';
                }    
                elseif($report_type==4)
                {
                    echo '<td align="center">' . $row['programme'] . '</td><td>' . $row['firstnames'] . '</td><td>' . $row['surname'] . '</td><td>' . $row['induction_date'] . '</td><td>' . $row['planned_end_date'] . '</td><td>' . $row['allocated_tutor'] . '</td><td>' . $row['required_to_complete'] . '</td><td>' . $row['maths_overall_status'] . '</td><td>' . $row['maths_course_date'] . '</td><td>' . $row['maths_exam_date'] . '</td><td>' . $row['maths_exam_result'] . '</td><td>' . $row['maths_exam_score'] . '</td><td>' . $row['maths_rft'] . '</td><td>' . $row['maths_achieved_date'] . '</td><td>' . $row['maths_comments'] . '</td><td>' . $row['english_overall_status'] . '</td><td>' . $row['english_course_date'] . '</td><td>' . $row['english_course_status'] . '</td><td>' . $row['english_achieved_date'] . '</td><td>' . $row['english_reading_status'] . '</td><td>' . $row['reading_exam_date'] . '</td><td>' . $row['reading_exam_result'] . '</td><td>' . $row['reading_exam_score'] . '</td><td>' . $row['reading_rft'] . '</td><td>' . $row['reading_exam_result_received_date'] . '</td><td>' . $row['english_writing_status'] . '</td><td>' . $row['writing_exam_date'] . '</td><td>' . $row['writing_exam_result'] . '</td><td>' . $row['writing_exam_score'] . '</td><td>' . $row['writing_rft'] . '</td><td>' . $row['writing_exam_result_received_date'] . '</td><td>' . $row['slc_status'] . '</td><td>' . $row['slc_date_exam_result_received'] . '</td><td>' . $row['slc_course_date'] . '</td><td>' . $row['slc_rft'] . '</td>';
                }    
                elseif($report_type==5)
                {
                    $learner_info = FSProgress::getLearnerInfo($link, $row['training_record_id']);
                    $end_date = ($learner_info[0][1]!="")?$learner_info[0][1]:date("d/m/Y");
                    $info = Date::dateDiffInfo($learner_info[0][0], $end_date);
                    $completion_date = new Date($learner_info[0][0]);
                    $completion_date->addMonths(6);
                    $days_on_programme = isset($info['days']) ? $info['days'] : '';                
                    echo '<td align="center">' . $row['programme'] . '</td><td>' . $row['firstnames'] . '</td><td>' . $row['surname'] . '</td><td>' . $row['induction_date'] . '</td><td>' . $row['planned_end_date'] . '</td><td>' . $row['allocated_tutor'] . '</td><td>' . $row['coordinator'] . '</td><td>' . $row['assessor'] . '</td><td>' . $days_on_programme . '</td><td>' . $completion_date . '</td><td>' . $row['required_to_complete'] . '</td><td>' . $row['fs_required'] . '</td><td>' . $row['english_evidence'] . '</td><td>' . $row['maths_evidence'] . '</td><td>' . $row['general_comments'] . '</td>';
                }    
                elseif($report_type==6)
                {
                    echo '<td align="center">' . $row['programme'] . '</td><td>' . $row['firstnames'] . '</td><td>' . $row['surname'] . '</td><td>' . $row['induction_date'] . '</td><td>' . $row['planned_end_date'] . '</td><td>' . $row['maths_test_status'] . '</td><td>' . $row['maths_exam_date'] . '</td><td>' . $row['english_overall_status_reading'] . '</td><td>' . $row['reading_exam_date'] . '</td><td>' . $row['english_overall_status_writing'] . '</td><td>' . $row['writing_exam_date'] . '</td><td>' . $row['slc_status'] . '</td><td>' . $row['slc_date'] . '</td>';
                }    

                echo '</tr>';
            }

            echo '</tbody></table></div align="center">';
            echo $this->getViewNavigator();


        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }
}
?>