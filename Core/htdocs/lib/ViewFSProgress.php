<?php
class ViewFSProgress extends View
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
	if(tr.closure_date is not null, DATEDIFF(closure_date, induction_fields.induction_date), DATEDIFF(CURDATE(), induction_fields.induction_date)) as days_on_programme,
	DATE_FORMAT(DATE_ADD(induction_fields.induction_date, INTERVAL 6 MONTH), '%d/%m/%Y') AS target_completion_date,
	CASE required WHEN 1 THEN "Maths" WHEN 2 THEN "English" WHEN 3 THEN "Both" ELSE "" END AS required_to_complete,
    case maths_overall_status when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 5 then 'Completed' when 6 then 'Not Required' end as maths_overall_status,
    case maths_mock_status when 1 then 'Required' when 2 then 'Issued' when 3 then 'Completed' end as maths_mock_status,
    case maths_mock_result when 1 then 'Pass' when 2 then 'Fail' end as maths_mock_result,
    comments_maths_mock as maths_mock_comments,
    DATE_FORMAT(maths_course_date, '%d/%m/%Y') AS maths_course_date,
    DATE_FORMAT(maths_exam_date, '%d/%m/%Y') AS maths_exam_date,
    case maths_exam_result when 1 then 'Pass' when 2 then 'Fail' when 3 then 'Did not attend' end as maths_exam_result,
    maths_exam_score,
    case maths_rft when 1 then 'RFT' when 2 then 'Not RFT' end maths_rft,
    maths_achieved_date,
    date_exam_result_received_maths as maths_exam_result_received_date,
    case tutor_maths when 1 then "Jo Hill" when 2 then "Mehwish Parveen" when 3 then "Iain Nicol" end as maths_tutor,
    comments_maths as maths_comments,
    case english_course_overall_status when 1 then 'Required' when 2 then 'Booked' when 3 then 'Support Session Required' when 4 then 'Support Session Booked' when 5 then 'Completed' when 6 then 'Not Required' end as english_overall_status,
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
    course_date as slc_course_date,
    case slc_rft when 1 then 'RFT' when 2 then 'Not RFT' end slc_rft,
    comments_slc as slc_comments,
	induction_fields.math_cert AS maths_certificate,
    induction_fields.eng_cert AS english_certificate,
    fs_progress.created as created,
    fs_progress.modified as modified
FROM
	tr
	INNER JOIN fs_progress ON fs_progress.tr_id = tr.id AND fs_progress.id = (SELECT id FROM fs_progress AS fs2 WHERE fs2.tr_id = tr.id ORDER BY id DESC LIMIT 1)
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


            $view = $_SESSION[$key] = new ViewFSProgress();
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

            // Employer filter
            if($_SESSION['user']->type==8)
                $options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE (organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%1%") and organisations.parent_org= ' . $_SESSION['user']->employer_id . ' order by legal_name';
            else
                $options = 'SELECT id, legal_name, null, CONCAT("WHERE users.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" or organisation_type like "%6%" or organisation_type like "%1%" order by legal_name';
            $f = new DropDownViewFilter('organisation', $options, null, true);
            $f->setDescriptionFormat("Employer: %s");
            $view->addFilter($f);

            // Location Filter
            $options = 'SELECT id, full_name, null, CONCAT("WHERE locations.id=",id) FROM locations order by full_name';
            $f = new DropDownViewFilter('location', $options, null, true);
            $f->setDescriptionFormat("Location: %s");
            $view->addFilter($f);

            // Contract Filter
            $options = 'SELECT id, title, null, CONCAT("WHERE contracts.id=",id) FROM contracts where active = 1 order by contract_year desc, title';
            if(DB_NAME=="am_lead" && $_SESSION['user']->type == User::TYPE_MANAGER)
                $options = 'SELECT id, title, null, CONCAT("WHERE contracts.id=",id) FROM contracts WHERE contracts.title LIKE "%$emp_name%" AND active = 1 ORDER BY contract_year DESC, title';
            $f = new DropDownViewFilter('contract', $options, null, true);
            $f->setDescriptionFormat("Contract: %s");
            $view->addFilter($f);


            // Gender filter
            $options = "SELECT DISTINCT gender, gender, null, CONCAT('WHERE users.gender=',char(39),gender,char(39)) FROM users";
            $f = new DropDownViewFilter('filter_gender', $options, null, true);
            $f->setDescriptionFormat("Gender: %s");
            $view->addFilter($f);

            // ethnicity filter
            //$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis200809;port=".DB_PORT, DB_USER, DB_PASSWORD);
            $options = "SELECT DISTINCT Ethnicity_Code, Ethnicity_Desc, null, CONCAT('WHERE users.ethnicity=',char(39),ethnicity_code,char(39)) FROM lis200809.ILR_L12_Ethnicity";
            $f = new DropDownViewFilter('ethnicity', $options, null, true);
            $f->setDescriptionFormat("Ethnicity: %s");
            $view->addFilter($f);


            $options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3";
            $f = new DropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            // ULN Filter
            $f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
            $f->setDescriptionFormat("Learner ULN: %s");
            $view->addFilter($f);

            /*//Contract Year filter
            $options = "SELECT id, contract_year, NULL, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts WHERE active =  1 GROUP BY contract_year ORDER BY contract_year DESC";
            $f = new DropDownViewFilter('filter_contract', $options, null, true);
            $f->setDescriptionFormat("Contract Year: %s");
            $view->addFilter($f);*/

            $f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
            $f->setDescriptionFormat("L03: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), null, CONCAT('WHERE contracts.contract_year=',contract_year) FROM contracts where active = 1 order by contract_year desc";
            $f = new DropDownViewFilter('filter_contract_year', $options, null, true);
            $f->setDescriptionFormat("Contract Year: %s");
            $view->addFilter($f);

            // Add Assessment filter
            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'Completed Assessment', null, 'having numeracy_level is not null and literacy_level is not null'),
                2=>array(2, 'Missing Assessment', null, 'having numeracy_level is null or literacy_level is null or numeracy_level = "" or literacy_level = ""'));
            $f = new DropDownViewFilter('filter_assessment', $options, 0, false);
            $f->setDescriptionFormat("Progress: %s");
            $view->addFilter($f);

            // Disability
            //$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis200809;port=".DB_PORT, DB_USER, DB_PASSWORD);
            /*		$options = "SELECT DISTINCT Disability_Code, Disability_Desc, NULL, CONCAT('WHERE tr.disability=',CHAR(39),Disability_code,CHAR(39)) FROM lis200809.ILR_L15_Disability WHERE Disability_code <> 98 ORDER BY Disability_Desc";
              $f = new DropDownViewFilter('disability', $options, null, true);
              $f->setDescriptionFormat("Disability: %s");
              $view->addFilter($f);

              // Learning difficulty
              //$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis200809;port=".DB_PORT, DB_USER, DB_PASSWORD);
              $options = "SELECT DISTINCT Difficulty_Code, Difficulty_Desc, NULL, CONCAT('WHERE tr.learning_difficulties=',CHAR(39),Difficulty_code,CHAR(39)) FROM lis200809.ILR_L16_Difficulty WHERE Difficulty_code <> 98 ORDER BY Difficulty_Desc";
              $f = new DropDownViewFilter('learning_difficulty', $options, null, true);
              $f->setDescriptionFormat("Learning Difficulty: %s");
              $view->addFilter($f);
      */
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

            if($_SESSION['user']->type==8)
                $options = "SELECT DISTINCT frameworks.id, title, null, CONCAT('WHERE student_frameworks.id=',frameworks.id) FROM frameworks where frameworks.parent_org = $parent_org and frameworks.active = 1 order by frameworks.title";
            else
                $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE student_frameworks.id=',id) FROM frameworks order by frameworks.title";
            $f = new DropDownViewFilter('filter_framework', $options, null, true);
            $f->setDescriptionFormat("Framework: %s");
            $view->addFilter($f);

            // Date filters
            $dateInfo = getdate();
            $weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
            $timestamp = time()  - ((60*60*24) * $weekday);

            // Rewind by a further 1 week
            $timestamp = $timestamp - ((60*60*24) * 7);

            // Start Date Filter
            $format = "WHERE tr.start_date >= '%s'";
            $f = new DateViewFilter('start_date', $format, '');
            $f->setDescriptionFormat("From start date: %s");
            $view->addFilter($f);

            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));

            $format = "WHERE tr.start_date <= '%s'";
            $f = new DateViewFilter('end_date', $format, '');
            $f->setDescriptionFormat("To start date: %s");
            $view->addFilter($f);


            // Target date filter
            $format = "WHERE tr.target_date >= '%s'";
            $f = new DateViewFilter('target_start_date', $format, '');
            $f->setDescriptionFormat("From target date: %s");
            $view->addFilter($f);

            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));

            $format = "WHERE tr.target_date <= '%s'";
            $f = new DateViewFilter('target_end_date', $format, '');
            $f->setDescriptionFormat("To target date: %s");
            $view->addFilter($f);


            // Closure date filter
            $format = "WHERE tr.closure_date >= '%s'";
            $f = new DateViewFilter('closure_start_date', $format, '');
            $f->setDescriptionFormat("From closure date: %s");
            $view->addFilter($f);

            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));

            $format = "WHERE tr.closure_date <= '%s'";
            $f = new DateViewFilter('closure_end_date', $format, '');
            $f->setDescriptionFormat("To closure date: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'End Date Within 6 Weeks of Start Date', null, ' WHERE tr.closure_date <= DATE_ADD(tr.start_date,INTERVAL 6 WEEK) AND tr.closure_date IS NOT NULL '),
                2=>array(2, 'Exclude End Date Within 6 Weeks of Start Date', null, ' WHERE tr.closure_date > DATE_ADD(tr.start_date,INTERVAL 6 WEEK) OR tr.closure_date IS NULL '));
            $f = new DropDownViewFilter('filter_closure_within_6_wks', $options, 0, false);
            $f->setDescriptionFormat("Closure Within Six Weeks: %s");
            $view->addFilter($f);

            $options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type ORDER BY description ASC ";
            $f = new DropDownViewFilter('filter_programme_type', $options, NULL, true);
            $f->setDescriptionFormat("Programme Type: %s");
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
            /*		echo $this->getViewNavigator();
             echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
             echo '<thead><tr><th>&nbsp;</th><th>Surname</th><th>Firstname</th><th>Job Role</th><th>Username</th><th>Enrolment no</th><th>Organisation</th><th>Location</th><th>Work Telephone</th><th>Home Telephone</th><th>Status</th></tr></thead>';

             echo '<tbody>';
             while($row = $st->fetch(PDO::FETCH_ASSOC))
             {

                 echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $row['username']);

                 if($row['gender']=='M')
                     echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
                 else
                     echo '<td><a href="do.php?_action=read_user&username=' . $row['username'] . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';
                 echo '<td align="left">' . HTML::cell($row['surname']) . "</td>";
                 echo '<td align="left">' . HTML::cell($row['firstname']) . "</td>";
                 echo '<td align="left">' . HTML::cell($row['job_role']) . "</td>";
                 echo '<td align="left" style="font-family:monospace">' . htmlspecialchars((string)$row['username']) . "</td>";
                 echo '<td align="left">' . HTML::cell($row['enrolment_no']) . "</td>";

                 if($row['organisation'] == NULL) // can include empty string
                 {
                     echo "<td style='background-color:#EEEEEE;'>&nbsp;</td>";
                 }
                 else
                 {
                     echo '<td align="left">' . HTML::cell($row['organisation']) . '</td>';
                 }

                 echo '<td>' . HTML::cell($row['full_name'])	. '</td>';
                 echo '<td>' . HTML::cell($row['work_telephone']) . '</td>';


                 echo '<td align="left">' . HTML::cell($row['home_telephone']) . '</td>';

                 if($row['status_code']=='')
                     $status = "Training Not Started";
                 else
                 {
                     $code = $row['status_code'];
                     $status = DAO::getSingleValue($link, "select description from lookup_pot_status where code=$code");
                 }

                 echo '<td align="center">' . HTML::cell($status) . '</td>';

                 echo '</tr>';
             }
             echo '</tbody></table></div align="center">';
             echo $this->getViewNavigator();
         */

            echo $this->getViewNavigator();
            echo '<div align="center"><table class="resultset sortData" id="dataMatrix" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr>';

            foreach($columns as $column)
            {
                echo '<th class="topRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
            }

            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['training_record_id']);
                foreach($columns as $column)
                {
                    echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
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