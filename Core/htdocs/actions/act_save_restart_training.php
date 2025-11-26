<?php
class save_restart_training implements IAction
{
    public function execute(PDO $link)
    {
        $username = isset($_REQUEST['username'])?$_REQUEST['username']:'';
        $framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
        $course_id = isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'';
        $group_id = isset($_REQUEST['group_id'])?$_REQUEST['group_id']:'';
        $provider_location_id = isset($_REQUEST['provider_location_id'])?$_REQUEST['provider_location_id']:'';
        $contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
        $start_date = isset($_REQUEST['start_date'])?$_REQUEST['start_date']:'';
        $end_date = isset($_REQUEST['end_date'])?$_REQUEST['end_date']:'';
        $planned_epa_date = isset($_REQUEST['planned_epa_date'])?$_REQUEST['planned_epa_date']:'';
        $assessor = isset($_REQUEST['assessor'])?$_REQUEST['assessor']:'0';
        $coordinator = isset($_REQUEST['coordinator'])?$_REQUEST['coordinator']:'';
        $tutor = isset($_REQUEST['tutor'])?$_REQUEST['tutor']:'0';
        $verifier = isset($_REQUEST['verifier'])?$_REQUEST['verifier']:'0';
        $bil_learner = isset($_REQUEST['bil_learner'])?$_REQUEST['bil_learner']:'false';
        $copy_compliance = isset($_REQUEST['copy_compliance'])?$_REQUEST['copy_compliance']:'false';
        $inductee_id = isset($_REQUEST['inductee_id'])?$_REQUEST['inductee_id']:'';
        $previous_training_record_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $fs_exempt = '';


        if(in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]))
        {
            $fs_exempt = DAO::getSingleValue($link, "SELECT induction.fs_exempt FROM induction WHERE inductee_id = '{$inductee_id}'");
        }

        if($framework_id == '')
            $framework_id = DAO::getSingleValue($link, "select framework_id from courses where id = '$course_id'");

        if($provider_location_id=='')
            $provider_location_id = DAO::getSingleValue($link, "SELECT locations.id FROM locations LEFT JOIN courses ON locations.organisations_id = courses.organisations_id WHERE courses.id = '$course_id' ");

        $sd = Date::toMySQL($start_date);
        $ed = Date::toMySQL($end_date);

        $user = User::loadFromDatabase($link, $username);
        $course = Course::loadFromDatabase($link, $course_id);

        $framework = Framework::loadFromDatabase($link, $course->framework_id);

        $que = "select id from locations where organisations_id='$course->organisations_id'";
        $location_id = trim(DAO::getSingleValue($link, $que));

        if($location_id!='')
            $provider = Location::loadFromDatabase($link, $location_id);
        else
            $provider = new Location();


        $link->beginTransaction();
        try
        {

            $l03 = DAO::getSingleValue($link, "select l03 from tr where username = '$username' limit 0,1");

            // Create training record
            $tr = new TrainingRecord();
            $tr->populate($user, true);
            $tr->contract_id = $contract_id;
            $tr->start_date = $start_date;
            $tr->target_date = $end_date;
            $tr->planned_epa_date = $planned_epa_date;
            $tr->status_code = 1;
            $tr->provider_id = $course->organisations_id;
            $tr->provider_location_id = $provider_location_id;
            $tr->provider_address_line_1 = $provider->address_line_1;
            $tr->provider_address_line_2 = $provider->address_line_2;
            $tr->provider_address_line_3 = $provider->address_line_3;
            $tr->provider_address_line_4 = $provider->address_line_4;
            $tr->provider_postcode = $provider->postcode;
            $tr->provider_telephone = $provider->telephone;
            $tr->ethnicity = $user->ethnicity;
            $tr->work_experience = 0;
            $tr->assessor = $assessor;
            $tr->coordinator = $coordinator;
            $tr->tutor = $tutor;
            $tr->verifier = $verifier;
            $tr->l36 = 0;
            // Make it null so it does not uses users id and creates its own id
            $tr->id = NULL;

            $tr->otj_hours = $framework->otj_hours;
            $tr->epa_organisation = $framework->epa_org_id;

            if($l03=='')
            {
                $l03 = (int)DAO::getSingleValue($link, "select max(l03) from tr where l03 + 0 <> 0 AND LENGTH(RTRIM(l03))=12");
                $l03 += 1;
                $tr->l03 = str_pad($l03,12,'0',STR_PAD_LEFT);
                $l03 = str_pad($l03,12,'0',STR_PAD_LEFT);
            }
            else
            {
                //$l03 = str_pad($l03,12,'0',STR_PAD_LEFT);
                $tr->l03 = $l03;
            }
            if(in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]))
            {
                $tr->actual_progression = 'N';
            }

	        //if( in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]) && $inductee_id != '' )
	        if( in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]))
            {
                $previous_tr = TrainingRecord::loadFromDatabase($link, $previous_training_record_id);
                $tr->crm_contact_id = $previous_tr->crm_contact_id;
                $tr->assessor = $previous_tr->assessor;
                //$line_manager_from_induction = DAO::getSingleValue($link, "SELECT contact_id FROM organisation_contact WHERE contact_id IN (SELECT inductees.emp_crm_contacts FROM inductees WHERE inductees.id = '{$inductee_id}') AND job_role = 1 LIMIT 1;");
                //$tr->crm_contact_id = $line_manager_from_induction;
            }

            $tr->parent_id = $previous_training_record_id;
            

	    if(DB_NAME == "am_ela")
            {
                $onefileFields = DAO::getObject($link, "SELECT onefile_id, onefile_username FROM tr WHERE tr.id = '{$previous_training_record_id}'");
                if(isset($onefileFields->onefile_id) && $onefileFields->onefile_id != '')
                {
                    $tr->onefile_id = $onefileFields->onefile_id;
                    $tr->onefile_username = $onefileFields->onefile_username;
                }
            }
	
            $tr->save($link);

            if(in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]))
            {
                $op_details = new stdClass();
                $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM tr_operations");
                foreach($records AS $key => $value)
                    $op_details->$value = null;
                $op_details->tr_id = $tr->id;
                // if the learner has come from Induction module and it is the first time the record is being opened, then copy the crm contacts from induction to operations
                $op_details->main_contact_id = DAO::getSingleValue($link, "SELECT inductees.emp_crm_contacts FROM inductees INNER JOIN tr ON inductees.sunesis_username = tr.username WHERE tr.id = '{$tr->id}'");
                $op_details->learner_id = DAO::getSingleValue($link, "SELECT inductees.learner_id FROM inductees INNER JOIN tr ON inductees.sunesis_username = tr.username WHERE tr.id = '{$tr->id}'");
                if($inductee_id != '')
                {
                    $inductee_fields = DAO::getObject($link, "SELECT ldd, ldd_comments, general_comments FROM inductees WHERE id = '{$inductee_id}'");
                    if(isset($inductee_fields->ldd) && $inductee_fields->ldd != '')
                        $op_details->ldd = $inductee_fields->ldd;
                    if(isset($inductee_fields->ldd_comments) && $inductee_fields->ldd_comments != '')
                        $op_details->additional_support = $inductee_fields->ldd_comments;
                    if(isset($inductee_fields->general_comments) && $inductee_fields->general_comments != '')
                        $op_details->general_comments = $inductee_fields->general_comments;

                    DAO::execute($link, "UPDATE inductees SET inductees.linked_tr_id = '{$tr->id}' WHERE inductees.id = '{$inductee_id}'");
                }
                $op_details->learner_status = 'OP';
                DAO::saveObjectToTable($link, 'tr_operations', $op_details);

                $this->createFsNotRequiredEntries($link, $tr, $inductee_id);
                $this->createFsProgressEntry($link, $tr, $inductee_id);
                $this->createCrmEntry($link, $tr, $inductee_id);
            }

            if(in_array(DB_NAME, ["am_lead_demo"]))
            {
                $this->pullPreviousEpisodeInfo($link, $tr, $course);
            }

            $tr_id = $tr->id;
            $identity = $user->getFullyQualifiedName();

            // if break in learning learner and user wants to copy the compliance records then do that
            if($bil_learner)
            {
                $previous_course_id = isset($_REQUEST['previous_course_id'])?$_REQUEST['previous_course_id']:'';

                if($copy_compliance == 'true' && $previous_course_id != '' && $previous_training_record_id != '' && $previous_course_id == $course_id)
                {
                    $sql_copy_compliance = <<<SQL
INSERT INTO student_events(
  tr_id,
  event_id,
  event_date,
  `owner`,
  comments
)
SELECT
  $tr_id,
  event_id,
  event_date,
  `owner`,
  comments
FROM
  student_events
WHERE tr_id = '$previous_training_record_id'
 ;
SQL;
                    DAO::execute($link, $sql_copy_compliance);
                }
            }

            // Enrol on a course and put in a  group


            $framework_id = '0';
            $qualification_id = '0';

            if($tr_id=='' || $course_id=='')
            {
                throw new Exception("Could not enrol on a course! insufficient information given");
            }

            $que = "select main_qualification_id from courses where id='$course_id'";
            $qualification_id = DAO::getSingleValue($link, $que);

            if($qualification_id=='')
            {
                $qualification_id  = '0';
                $que = "select framework_id from courses where id='$course_id'";
                $framework_id = DAO::getSingleValue($link, $que);
                $fid = $framework_id;
            }

// enroling on a course
            $query = <<<HEREDOC
insert into
	courses_tr (course_id, tr_id, qualification_id, framework_id)
values($course_id, $tr_id, '$qualification_id', $framework_id);
HEREDOC;
            DAO::execute($link, $query);


// Create review

    if(DB_NAME=='am_ela')
        $tr->createReviews($link);

            if($group_id!='')
            {
// 	attaching to a group
                $query = <<<HEREDOC
insert into
	group_members (groups_id, tr_id, member)
values($group_id, $tr_id, 0);
HEREDOC;
                DAO::execute($link, $query);
            }


// Check if this course has a framework attached to it and get framework id
            $que = "select framework_id from courses where id='$course_id'";
            $fid = DAO::getSingleValue($link, $que);

            $que = "select id from student_frameworks where tr_id='$tr_id'";
            $tr_framework_id = DAO::getSingleValue($link, $que);

            if($fid!='')
            {
                if($tr_framework_id=='')
                {

// Importing framework
                    $query = <<<HEREDOC
insert into
	student_frameworks
select title, id, '$tr_id', framework_code, comments, duration_in_months
from frameworks
	where id = '$framework_id';
HEREDOC;
                    DAO::execute($link, $query);

// importing qualification from framework

if(DB_NAME=='am_ela')
{
$query = <<<HEREDOC

    insert into
        student_qualifications(id,
    framework_id,
    tr_id,
    internaltitle,
    lsc_learning_aim,
    awarding_body,
    title,
    description,
    assessment_method,
    structure,
    level,
    qualification_type,
    accreditation_start_date,
    operational_centre_start_date,
    accreditation_end_date,
    certification_end_date,
    dfes_approval_start_date,
    dfes_approval_end_date,
    evidences,
    units,
    unitsCompleted,
    unitsNotStarted,
    unitsBehind,
    unitsOnTrack,
    unitsUnderAssessment,
    unitsPercentage,
    proportion,
    aptitude,
    attitude,
    comments,
    modified,
    username,
    trading_name,
    auto_id,
    start_date,
    end_date,
    actual_end_date,
    achievement_date,
    units_required,
    awarding_body_reg,
    awarding_body_date,
    awarding_body_batch,
    a14,
    a18,
    a51a,
    a16,
    certificate_applied,
    certificate_received,
    smart_assessor_id
    )
    select 
    id, 
    '$framework_id',
    '$tr_id', 
    framework_qualifications.internaltitle, 
    lsc_learning_aim, 
    awarding_body, 
    title, 
    description, 
    assessment_method, 
    structure, 
    level, 
    qualification_type, 
    accreditation_start_date, 
    operational_centre_start_date, 
    accreditation_end_date, 
    certification_end_date, 
    dfes_approval_start_date, 
    dfes_approval_end_date, 
    evidences, 
    units,
    '0',
    '0',
    '0',
    '0',
    '0',
    units_required, 
    proportion, 
    0, 
    0, 
    0, 
    0, 
    0, 
    0, 
    0, 
    DATE_ADD('$sd',INTERVAL COALESCE(offset_months, 0) MONTH), 
    DATE_ADD('$sd',INTERVAL (COALESCE(offset_months, 0)+COALESCE(duration_in_months, 0)) MONTH), 
    NULL, 
    NULL, 
    units_required,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    '100',
    NULL,
    '',
    '',
    ''
    from framework_qualifications
    LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and 
    course_qualifications_dates.framework_id = framework_qualifications.framework_id and 
    course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
        where framework_qualifications.framework_id = '$framework_id' and course_qualifications_dates.course_id='$course_id'
        order by sequence;
HEREDOC;

}
else
{
$query = <<<HEREDOC
insert into
	student_qualifications(id,
framework_id,
tr_id,
internaltitle,
lsc_learning_aim,
awarding_body,
title,
description,
assessment_method,
structure,
level,
qualification_type,
accreditation_start_date,
operational_centre_start_date,
accreditation_end_date,
certification_end_date,
dfes_approval_start_date,
dfes_approval_end_date,
evidences,
units,
unitsCompleted,
unitsNotStarted,
unitsBehind,
unitsOnTrack,
unitsUnderAssessment,
unitsPercentage,
proportion,
aptitude,
attitude,
comments,
modified,
username,
trading_name,
auto_id,
start_date,
end_date,
actual_end_date,
achievement_date,
units_required,
awarding_body_reg,
awarding_body_date,
awarding_body_batch,
a14,
a18,
a51a,
a16,
certificate_applied,
certificate_received,
smart_assessor_id
)
select 
id, 
'$framework_id',
'$tr_id', 
framework_qualifications.internaltitle, 
lsc_learning_aim, 
awarding_body, 
title, 
description, 
assessment_method, 
structure, 
level, 
qualification_type, 
accreditation_start_date, 
operational_centre_start_date, 
accreditation_end_date, 
certification_end_date, 
dfes_approval_start_date, 
dfes_approval_end_date, 
evidences, 
units,
'0',
'0',
'0',
'0',
'0',
units_required, 
proportion, 
0, 
0, 
0, 
0, 
0, 
0, 
0, 
'$sd', 
'$ed', 
NULL, 
NULL, 
units_required,
NULL,
NULL,
NULL,
NULL,
NULL,
'100',
NULL,
'',
'',
''
from framework_qualifications
LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and 
course_qualifications_dates.framework_id = framework_qualifications.framework_id and 
course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
	where framework_qualifications.framework_id = '$framework_id' and course_qualifications_dates.course_id='$course_id';
HEREDOC;
}

DAO::execute($link, $query);


                    // Creating milestones
                    $sql = "SELECT *, timestampdiff(MONTH, start_date, end_date) as months FROM student_qualifications where tr_id = $tr_id";
                    $st = $link->query($sql);
                    $unit=0;
                    while($row = $st->fetch())
                    {
                        $xml = mb_convert_encoding($row['evidences'],'UTF-8');

                        //$pageDom = new DomDocument();
                        //$pageDom->loadXML(utf8_encode($xml));
                        $pageDom = XML::loadXmlDom(mb_convert_encoding($xml,'UTF-8'));


                        $evidences = $pageDom->getElementsByTagName('unit');
                        foreach($evidences as $evidence)
                        {
                            $unit_id = $evidence->getAttribute('owner_reference');
                            $tr_id = $row['tr_id'];
                            $framework_id = $row['framework_id'];
                            $qualification_id = $row['id'];
                            $internaltitle = $row['internaltitle'];

                            $m = Array();
                            for($a = 1; $a<=$row['months']; $a++)
                            {
                                if($a==$row['months'])
                                    $m[] = 100;
                                else
                                    $m[] = sprintf("%.1f", 100 / $row['months'] * $a);
                            }
                            for($a = $row['months']+1; $a<=36; $a++)
                            {
                                $m[] = 100;
                            }
                            $internaltitle = addslashes((string)$internaltitle);
                            DAO::execute($link, "insert into student_milestones values($framework_id, '$qualification_id', '$internaltitle', '$unit_id', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16], $m[17], $m[18], $m[19], $m[20], $m[21], $m[22], $m[23], $m[24], $m[25], $m[26], $m[27], $m[28], $m[29], $m[30], $m[31], $m[32], $m[33], $m[34], $m[35], 1, $tr_id, 1)");
                        }
                    }

                }
            }


            // Creating ILR

            // FW DESTINY INTEGRATION
            //$sql = "SELECT users.ni, users.l45, users.l24, users.l14, users.l15, users.l16, users.l35, users.l48, users.l42a, users.l42b, contract_holder.upin, users.uln, l03, tr.l28a, tr.l28b, tr.l34a, tr.l34b, tr.l34c, tr.l34d, tr.l36, tr.l37, tr.l39, tr.l40a, tr.l40b, tr.l41a, tr.l41b, tr.l47, tr.id, tr.surname, tr.firstnames, DATE_FORMAT(tr.dob,'%d/%m/%Y') AS date_of_birth, DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date, tr.ethnicity, tr.gender, learning_difficulties, disability,learning_difficulty, tr.home_postcode, TRIM(CONCAT(IF(tr.home_paon_start_number IS NOT NULL,TRIM(tr.home_paon_start_number),''),IF(tr.home_paon_start_suffix IS NOT NULL,TRIM(tr.home_paon_start_suffix),''),' ', IF(tr.home_paon_end_number IS NOT NULL,TRIM(tr.home_paon_end_number),''),IF(tr.home_paon_end_suffix IS NOT NULL,TRIM(tr.home_paon_end_suffix),''),' ' , IF(tr.home_paon_description IS NOT NULL,TRIM(tr.home_paon_description),''),' ',IF(tr.home_street_description IS NOT NULL,TRIM(tr.home_street_description),''))) AS L18, tr.home_locality, tr.home_town,tr.home_county, current_postcode, tr.home_telephone, country_of_domicile, tr.ni, prior_attainment_level,DATE_FORMAT(tr.start_date,'%d/%m/%Y') AS start_date, DATE_FORMAT(target_date,'%d/%m/%Y') AS target_date, status_code,provider_location_id, tr.employer_id, provider_location.postcode AS lpcode, organisations.edrs AS edrs, organisations.legal_name AS employer_name,employer_location.postcode AS epcode FROM tr LEFT JOIN locations AS provider_location ON provider_location.id = tr.provider_location_id LEFT JOIN locations AS employer_location ON employer_location.id = tr.employer_location_id LEFT JOIN organisations ON organisations.id = tr.employer_id 	LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN organisations AS contract_holder ON contract_holder.id = contract_holder LEFT JOIN users ON users.username = tr.username WHERE contract_id = '$contract_id' AND tr.id = '$tr_id';";
            //$sql = "SELECT users.ni, users.l45, users.l24, users.l14, users.l15, users.l16, users.l35, users.l48, users.l42a, users.l42b, contract_holder.upin, users.uln, users.home_email, l03, tr.l28a, tr.l28b, tr.l34a, tr.l34b, tr.l34c, tr.l34d, tr.l36, tr.l37, tr.l39, tr.l40a, tr.l40b, tr.l41a, tr.l41b, tr.l47, tr.id, tr.surname, tr.firstnames, DATE_FORMAT(users.dob,'%d/%m/%Y') AS date_of_birth, DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date, tr.ethnicity, tr.gender, learning_difficulties, disability,learning_difficulty, tr.home_postcode, TRIM(CONCAT(IF(tr.home_paon_start_number IS NOT NULL,TRIM(tr.home_paon_start_number),''),IF(tr.home_paon_start_suffix IS NOT NULL,TRIM(tr.home_paon_start_suffix),''),' ', IF(tr.home_paon_end_number IS NOT NULL,TRIM(tr.home_paon_end_number),''),IF(tr.home_paon_end_suffix IS NOT NULL,TRIM(tr.home_paon_end_suffix),''),' ' , IF(tr.home_paon_description IS NOT NULL,TRIM(tr.home_paon_description),''),' ',IF(tr.home_street_description IS NOT NULL,TRIM(tr.home_street_description),''))) AS L18, tr.home_locality, tr.home_town,tr.home_county, current_postcode, tr.home_telephone, country_of_domicile, tr.ni, prior_attainment_level,DATE_FORMAT(tr.start_date,'%d/%m/%Y') AS start_date, DATE_FORMAT(target_date,'%d/%m/%Y') AS target_date, status_code,provider_location_id, tr.employer_id, provider_location.postcode AS lpcode, organisations.edrs AS edrs, organisations.legal_name AS employer_name,employer_location.postcode AS epcode FROM tr LEFT JOIN locations AS provider_location ON provider_location.id = tr.provider_location_id LEFT JOIN locations AS employer_location ON employer_location.id = tr.employer_location_id LEFT JOIN organisations ON organisations.id = tr.employer_id 	LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN organisations AS contract_holder ON contract_holder.id = contract_holder LEFT JOIN users ON users.username = tr.username WHERE contract_id = '$contract_id' AND tr.id = '$tr_id';";
            $sql = <<<SQL
SELECT
  users.ni,
  users.l45,
  users.l24,
  users.l14,
  users.l15,
  users.l16,
  users.l35,
  users.l48,
  users.l42a,
  users.l42b,
  contract_holder.upin,
  users.uln,
  users.home_email,
  l03,
  tr.l28a,
  tr.l28b,
  tr.l34a,
  tr.l34b,
  tr.l34c,
  tr.l34d,
  tr.l36,
  tr.l37,
  tr.l39,
  tr.l40a,
  tr.l40b,
  tr.l41a,
  tr.l41b,
  tr.l47,
  tr.id,
  tr.surname,
  tr.firstnames,
  DATE_FORMAT(users.dob, '%d/%m/%Y') AS date_of_birth,
  DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date,
  tr.ethnicity,
  tr.gender,
  learning_difficulties,
  disability,
  learning_difficulty,
  tr.home_address_line_1,
  tr.home_address_line_2,
  tr.home_address_line_3,
  tr.home_address_line_4,
  tr.home_postcode,
  current_postcode,
  IF(tr.`home_telephone` IS NOT NULL,tr.home_telephone,tr.home_mobile) AS home_telephone,
  country_of_domicile,
  tr.ni,
  prior_attainment_level,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(target_date, '%d/%m/%Y') AS target_date,
  status_code,
  provider_location_id,
  tr.employer_id,
  provider_location.postcode AS lpcode,
  organisations.edrs AS edrs,
  organisations.legal_name AS employer_name,
  employer_location.postcode AS epcode
FROM
  tr
  LEFT JOIN locations AS provider_location
    ON provider_location.id = tr.provider_location_id
  LEFT JOIN locations AS employer_location
    ON employer_location.id = tr.employer_location_id
  LEFT JOIN organisations
    ON organisations.id = tr.employer_id
  LEFT JOIN contracts
    ON contracts.id = tr.contract_id
  LEFT JOIN organisations AS contract_holder
    ON contract_holder.id = contract_holder
  LEFT JOIN users
    ON users.username = tr.username
WHERE contract_id = '$contract_id'
  AND tr.id = '$tr_id'
SQL;



            // e-portfolio
            if(DB_NAME=='ams' || DB_NAME=='am_demos' || DB_NAME=='am_ray_recruit')
            {
                $st = $link->query("select * from student_qualifications where tr_id = '$tr_id'");
                if($st)
                {
                    while($row = $st->fetch())
                    {
                        $qualification_id = $row['auto_id'];
                        $pageDom = new DomDocument();
                        @$pageDom->loadXML($row['evidences']);
                        $units = $pageDom->getElementsByTagName('unit');
                        foreach($units as $unit)
                        {
                            $title = addslashes(htmlspecialchars(@$unit->getAttribute('title')));
                            $proportion = htmlspecialchars(@$unit->getAttribute('proportion'));
                            $mandatory = htmlspecialchars(@$unit->getAttribute('mandatory'));
                            if($mandatory=="true")
                                $mandatory = 1;
                            else
                                $mandatory = 0;
                            $reference = htmlspecialchars(@$unit->getAttribute('reference'));
                            $owner_reference = htmlspecialchars(@$unit->getAttribute('owner_reference'));
                            $glh = htmlspecialchars(@$unit->getAttribute('glh'));
                            $credits = htmlspecialchars(@$unit->getAttribute('credits'));
                            $comments = htmlspecialchars(@$unit->getAttribute('comments'));
                            DAO::execute($link, "insert into qualification_units values(NULL, '$qualification_id','$proportion','0','$mandatory','$reference','$owner_reference','1','$title','$glh','$credits','$comments')");
                            $unit_id = $link->lastInsertId();
                            $elements = $unit->getElementsByTagName('element');
                            foreach($elements as $element)
                            {
                                $title = addslashes(htmlspecialchars(@$element->getAttribute('title')));
                                DAO::execute($link, "insert into qualification_elements values(NULL, '$unit_id','$title')");
                                $element_id = $link->lastInsertId();
                                $evidences = $element->getElementsByTagName('evidence');
                                foreach($evidences as $evidence)
                                {
                                    $title = addslashes(htmlspecialchars(@$evidence->getAttribute('title')));
                                    DAO::execute($link, "insert into qualification_pcs values(NULL, '$element_id','$title',NULL,NULL)");
                                }
                            }
                        }
                    }
                }
            }
            //

            $contract_year = DAO::getSingleValue($link,"select contract_year from contracts where id='$contract_id'");

            $co = Contract::loadFromDatabase($link, $contract_id);
            $submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where last_submission_date>=CURDATE() and contract_year = '$contract_year' and contract_type = '$co->funding_body' order by last_submission_date LIMIT 1;");
            if($submission=="")
                $submission = "W13";
            $ilrtemplatetext = DAO::getSingleValue($link, "select template from contracts where id = '$contract_id'");
            $framework_code = DAO::getSingleValue($link, "SELECT framework_code FROM student_frameworks LEFT JOIN frameworks ON frameworks.id = student_frameworks.id WHERE tr_id = '$tr_id';");

            $previous_ilr_text = DAO::getSingleValue($link, "select ilr from ilr where tr_id = '$previous_training_record_id' order by contract_id desc, submission desc limit 1");

            if($ilrtemplatetext!='')
            {
                $ilrtemplate = Ilr2019::loadFromXML($ilrtemplatetext);
            }

            $previous_ilr = @simplexml_load_string($previous_ilr_text);

            $st = $link->query($sql);
            if($st)
            {
                while($row = $st->fetch())
                {
                    // here to create ilrs for the first time from training records.
                    $xml = '<Learner>';
                    $xml .= "<LearnRefNumber>" . $tr->l03 . "</LearnRefNumber>";
                    if($row['l45']!='')
                        $xml .= "<ULN>" . $row['l45'] . "</ULN>";
                    else
                        $xml .= "<ULN>9999999999</ULN>";
                    $xml .= "<FamilyName>" . $row['surname'] .	"</FamilyName>";
                    $xml .= "<GivenNames>" . $row['firstnames'] . "</GivenNames>";
                    $xml .= "<DateOfBirth>" . $row['date_of_birth'] . "</DateOfBirth>";
                    $xml .= "<Ethnicity>" . $row['ethnicity'] .	"</Ethnicity>";
                    $xml .= "<Sex>" . $row['gender'] . "</Sex>";

                    $LLDDHealthProb = @((string)$previous_ilr->LLDDHealthProb);
                    if($LLDDHealthProb)
                        $xml .= "<LLDDHealthProb>" . $LLDDHealthProb . "</LLDDHealthProb>";
                    else
                        $xml .= "<LLDDHealthProb>" . $row['l14'] .	"</LLDDHealthProb>";

                    if($course->programme_type!='6')
                        $xml .= "<NINumber>" . $row['ni'] . "</NINumber>";


                    $prior = "";
                    if(@$previous_ilr->PriorAttain)
                    {
                        foreach (@$previous_ilr->PriorAttain as $PriorAttain)
                        {
                            $prior .= "<PriorAttain>";
                            $prior .= "<PriorLevel>" . @(string)$PriorAttain->PriorLevel . "</PriorLevel>";
                            $prior .= "<DateLevelApp>" . @(string)$PriorAttain->DateLevelApp . "</DateLevelApp>";    
                            $prior .= "</PriorAttain>";
                        }
                    }
                    if($prior!="")
                    {
                        $xml .= $prior;
                    }

                    $xml.= "<PlanLearnHours>" . @$previous_ilr->PlanLearnHours . "</PlanLearnHours>";

                    $xml .= "<LearnerContact><LocType>2</LocType><ContType>1</ContType><PostCode>" . $row['home_postcode'] . "</PostCode></LearnerContact>";
                    $xml .= "<LearnerContact><LocType>1</LocType><ContType>2</ContType><PostAdd>";
                    $xml .= "<AddLine1>" . $row['home_address_line_1'] . "</AddLine1>";
                    $xml .= "<AddLine2>" . $row['home_address_line_2'] . "</AddLine2>";
                    $xml .= "<AddLine3>" . $row['home_address_line_3'] . "</AddLine3>";
                    $xml .= "<AddLine4>" . $row['home_address_line_4'] . "</AddLine4>";
                    $xml .= "</PostAdd></LearnerContact>";
                    $xml .= "<LearnerContact><LocType>2</LocType><ContType>2</ContType><PostCode>" . $row['home_postcode'] . "</PostCode></LearnerContact>";
                    $xml .= "<LearnerContact><LocType>3</LocType><ContType>2</ContType><TelNumber>" . $row['home_telephone'] . "</TelNumber></LearnerContact>";
                    $xml .= "<LearnerContact><LocType>4</LocType><ContType>2</ContType><Email>" . $row['home_email'] . "</Email></LearnerContact>";

                    $lldd = "";
                    if(@$previous_ilr->LLDDandHealthProblem)
                    {
                        foreach (@$previous_ilr->LLDDandHealthProblem as $LLDDandHealthProblem)
                        {
                            $lldd .= "<LLDDandHealthProblem>";
                            $lldd .= "<LLDDCat>" . @(string)$LLDDandHealthProblem->LLDDCat . "</LLDDCat>";
                            if(isset($LLDDandHealthProblem->PrimaryLLDD))
                                $lldd .= "<PrimaryLLDD>" . @(string)$LLDDandHealthProblem->PrimaryLLDD . "</PrimaryLLDD>";    
                            $lldd .= "</LLDDandHealthProblem>";
                        }
                    }
                    if($lldd!="")
                    {
                        $xml .= $lldd;
                    }

                    $xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34a'] . "</LearnFAMCode></LearnerFAM>";
                    $xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34b'] . "</LearnFAMCode></LearnerFAM>";
                    $xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34c'] . "</LearnFAMCode></LearnerFAM>";
                    $xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34d'] . "</LearnFAMCode></LearnerFAM>";
                    $xml .= "<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $row['l40a'] . "</LearnFAMCode></LearnerFAM>";
                    $xml .= "<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $row['l40b'] . "</LearnFAMCode></LearnerFAM>";
                    $xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>A</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $row['l42a'] . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";
                    $xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>B</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $row['l42b'] . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";

                    $l47=$row['l47'];
                    if($l47=='')
                        $l47='98';
                    $l37=$row['l37'];
                    if($l37=='')
                        $l37='98';
                    $sta = new Date($sd);
                    $sta->subtractDays(1);
                    $xml .= "<LearnerEmploymentStatus><EmpStat>10</EmpStat><DateEmpStatApp>" . $sta->formatMySQL() . "</DateEmpStatApp><EmpId>" . $row['edrs'] . "</EmpId></LearnerEmploymentStatus>";
                    if(DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo')
                    {
                        $sta->subtractDays(-1);
                        $xml .= "<LearnerEmploymentStatus><EmpStat>10</EmpStat><DateEmpStatApp>" . $sta->formatMySQL() . "</DateEmpStatApp><EmpId>" . $row['edrs'] . "</EmpId></LearnerEmploymentStatus>";
                    }
                    //$xml .= "<LearnerEmploymentStatus><EmpStat>" . $l47 . "</EmpStat><DateEmpStatApp>" . $sd . "</DateEmpStatApp><EmpId>" . $row['edrs'] . "</EmpId></LearnerEmploymentStatus>";


                    $AimSeqNumber = 0;
                    if($course->programme_type=='2' || $course->programme_type=='7')
                    {
                        $AimSeqNumber++;
                        $sql_main = "select framework_qualifications.main_aim, frameworks.framework_type, frameworks.framework_code, student_qualifications.*, tr.work_postcode, tr.start_date as lsd, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle left join frameworks on frameworks.id = framework_qualifications.framework_id where tr_id = {$row['id']} order by main_aim desc limit 0,1";
                        $st3 = $link->query($sql_main);
                        if($st3)
                        {
                            while($row_sub = $st3->fetch())
                            {
                                $xml .= "<LearningDelivery>";
                                $xml .= "<LearnAimRef>ZPROG001</LearnAimRef>";
                                $xml .= "<AimType>1</AimType>";
                                $xml .= "<AimSeqNumber>" . $AimSeqNumber . "</AimSeqNumber>";
                                $xml .= "<LearnStartDate>" . $row_sub['lsd'] . "</LearnStartDate>";
                                $xml .= "<LearnPlanEndDate>" . $row_sub['led'] . "</LearnPlanEndDate>";
                                $FundModel = $this->getValueFromTemplate($ilrtemplatetext,"ZPROG001","FundModel");
                                /*if($FundModel!='')
                                    $xml .= "<FundModel>" . $FundModel . "</FundModel>";
                                else*/
                                if($course->programme_type=='1' || $course->programme_type=='2')
                                    $xml .= "<FundModel>36</FundModel>";
                                elseif($course->programme_type=='3')
                                    $xml .= "<FundModel>21</FundModel>";
                                elseif($course->programme_type=='4')
                                    $xml .= "<FundModel>22</FundModel>";
                                elseif($course->programme_type=='5')
                                    $xml .= "<FundModel>70</FundModel>";
                                elseif($course->programme_type=='6')
                                    $xml .= "<FundModel>10</FundModel>";
                                if($course->programme_type!='6')
                                    $xml .= "<ProgType>" . $row_sub['framework_type'] . "</ProgType>";
                                if($course->programme_type!='1' && $course->programme_type!='6')
                                    $xml .= "<FworkCode>" . $row_sub['framework_code'] . "</FworkCode>";
                                $xml .= "<PwayCode>" . $framework->PwayCode . "</PwayCode>";
                                $xml .= "<StdCode>" . $framework->StandardCode . "</StdCode>";
                                $provider_id = $course->organisations_id;
                                $ukprn = DAO::getSingleValue($link, "select ukprn from organisations where id = '$provider_id' and ukprn not in (select ukprn from organisations where organisation_type = 1)");
                                $xml .= "<PartnerUKPRN>" . $ukprn . "</PartnerUKPRN>";
                                $xml .= "<DelLocPostCode>" . $tr->work_postcode . "</DelLocPostCode>";
                                $xml .= "<PropFundRemain>100</PropFundRemain>";
                                $xml .= "<CompStatus>1</CompStatus>";

                                $sof="";
                                $otj = "";
                                foreach(@$previous_ilr->LearningDelivery as $learningdelivery)
                                {
                                    if($learningdelivery->AimType==1)
                                    {
                                        $otj = @$learningdelivery->OTJActHours;
                                        foreach (@$learningdelivery->LearningDeliveryFAM as $ldfam)
                                        {
                                            if($ldfam->LearnDelFAMType=="SOF")
                                            {
                                                $sof .= "<LearningDeliveryFAM>";
                                                $sof .= "<LearnDelFAMType>SOF</LearnDelFAMType>";
                                                $sof .= "<LearnDelFAMCode>" . @(string)$ldfam->LearnDelFAMCode . "</LearnDelFAMCode>";    
                                                $sof .= "</LearningDeliveryFAM>";
                                            }
                                        }
                                        break;
                                    }
                                }
                                if($sof!="")
                                {
                                    $xml .= $sof;
                                }
                                if($otj!="")
                                {
                                    $xml .= "<OTJActHours>" . $otj . "</OTJActHours>";
                                }

                                $act="";
                                foreach(@$previous_ilr->LearningDelivery as $learningdelivery)
                                {
                                    if($learningdelivery->AimType==1)
                                    {
                                        foreach (@$learningdelivery->LearningDeliveryFAM as $ldfam)
                                        {
                                            if($ldfam->LearnDelFAMType=="ACT")
                                            {
                                                $act .= "<LearningDeliveryFAM>";
                                                $act .= "<LearnDelFAMType>ACT</LearnDelFAMType>";
                                                $act .= "<LearnDelFAMCode>" . @(string)$ldfam->LearnDelFAMCode . "</LearnDelFAMCode>";    
                                                $act .= "<LearnDelFAMDateFrom>" . $sd . "</LearnDelFAMDateFrom>";    
                                                $act .= "<LearnDelFAMDateTo></LearnDelFAMDateTo>";    
                                                $act .= "</LearningDeliveryFAM>";
                                            }
                                        }
                                        break;
                                    }
                                }
                                if($act!="")
                                {
                                    $xml .= $act;
                                }

                                $FFI = $this->getValueFromTemplate2($ilrtemplatetext,str_replace("/" , "", "ZPROG001"),"FFI");
                                $xml .= "<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode>" . $FFI . "</LearnDelFAMCode></LearningDeliveryFAM>";
                                if(DB_NAME=='am_superdrug')
                                    $xml .= "<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>356</LearnDelFAMCode></LearningDeliveryFAM>";
				                $xml .= $tr->epa_organisation != '' ? "<EPAOrgID>" . $tr->epa_organisation . "</EPAOrgID>" : '';
                                if(DB_NAME=='am_ela')
                                {
                                    //$xml.="<LearningDeliveryFAM><LearnDelFAMType>ACT</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode><LearnDelFAMDateFrom>" . $sd . "</LearnDelFAMDateFrom><LearnDelFAMDateTo></LearnDelFAMDateTo></LearningDeliveryFAM>";        
                                }
                                $xml .= "</LearningDelivery>";
                            }
                        }
                    }

                    $provider_id = $course->organisations_id;
                    $PartnerUKPRN = DAO::getSingleValue($link, "select ukprn from organisations where id = '$provider_id' and ukprn not in (select ukprn from organisations where organisation_type = 1)");

                    if(DB_NAME=='am_ela')
                        $sql_main = "select framework_qualifications.main_aim, frameworks.framework_type, frameworks.framework_code, student_qualifications.*, tr.work_postcode, student_qualifications.start_date AS lsd, student_qualifications.end_date AS led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle left join frameworks on frameworks.id = framework_qualifications.framework_id where tr_id = {$row['id']} order by sequence";
                    else
                        $sql_main = "select framework_qualifications.main_aim, frameworks.framework_type, frameworks.framework_code, student_qualifications.*, tr.work_postcode, tr.start_date as lsd, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle left join frameworks on frameworks.id = framework_qualifications.framework_id where tr_id = {$row['id']} order by main_aim desc";

                    if($fs_exempt == 'Y' && in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]))
                    {
                        $_training_id = $row['id'];
                        $sql_main = <<<HEREDOC
SELECT
  framework_qualifications.main_aim,
  frameworks.framework_type,
  frameworks.framework_code,
  student_qualifications.*,
  tr.work_postcode,
  tr.start_date AS lsd,
  tr.target_date AS led
FROM
  student_qualifications
  INNER JOIN tr ON tr.id = student_qualifications.tr_id
  INNER JOIN framework_qualifications
    ON framework_qualifications.framework_id = student_qualifications.framework_id
    AND framework_qualifications.id = student_qualifications.id
    AND framework_qualifications.internaltitle = student_qualifications.internaltitle
  LEFT JOIN frameworks ON frameworks.id = framework_qualifications.framework_id
WHERE tr_id = '$_training_id' AND framework_qualifications.`qualification_type` != 'FS'
ORDER BY main_aim DESC;
HEREDOC;
                    }

                    $st3 = $link->query($sql_main);
                    if($st3)
                    {
                        while($row_sub = $st3->fetch())
                        {
                            $AimSeqNumber++;
                            $xml .= "<LearningDelivery>";
                            $xml .= "<LearnAimRef>" . str_replace("/" , "", $row_sub['id']) . "</LearnAimRef>";

                            if($course->programme_type=='2')
                                $xml .= "<AimType>3</AimType>";
                            elseif($course->programme_type=='7')
                                $xml .= "<AimType>5</AimType>";
                            else
                                $xml .= "<AimType>4</AimType>";

                            $xml .= "<AimSeqNumber>" . $AimSeqNumber . "</AimSeqNumber>";
                            $row_sub['lsd'] = Date::to(new Date($row_sub['lsd']), Date::SHORT);
                            $xml .= "<LearnStartDate>" . $row_sub['lsd'] . "</LearnStartDate>";
                            $row_sub['led'] = Date::to(new Date($row_sub['led']), Date::SHORT);
                            $xml .= "<LearnPlanEndDate>" . $row_sub['led'] . "</LearnPlanEndDate>";
                            $FundModel = $this->getValueFromTemplate($ilrtemplatetext,str_replace("/" , "", $row_sub['id']),"FundModel");
                            /*if($FundModel!='')
                                $xml .= "<FundModel>" . $FundModel . "</FundModel>";
                            else*/
                            if($course->programme_type=='1' || $course->programme_type=='2')
                                $xml .= "<FundModel>36</FundModel>";
                            elseif($course->programme_type=='3')
                                $xml .= "<FundModel>21</FundModel>";
                            elseif($course->programme_type=='4')
                                $xml .= "<FundModel>22</FundModel>";
                            elseif($course->programme_type=='5')
                                $xml .= "<FundModel>70</FundModel>";
                            elseif($course->programme_type=='6')
                                $xml .= "<FundModel>10</FundModel>";
                            elseif($course->programme_type=='7')
                                $xml .= "<FundModel>25</FundModel>";

                            if($course->programme_type!='6')
                                $xml .= "<ProgType>" . $row_sub['framework_type'] . "</ProgType>";

                            if($course->programme_type!='1' && $course->programme_type!='6')
                                $xml .= "<FworkCode>" . $row_sub['framework_code'] . "</FworkCode>";
                            $xml .= "<PwayCode>" . $framework->PwayCode . "</PwayCode>";
                            $xml .= "<StdCode>" . $framework->StandardCode . "</StdCode>";
                            $xml .= "<PartnerUKPRN>" . $PartnerUKPRN . "</PartnerUKPRN>";
                            $xml .= "<DelLocPostCode>" . $tr->work_postcode . "</DelLocPostCode>";
                            $xml .= "<CompStatus>1</CompStatus>";
                            $SOF = $this->getValueFromTemplate2($ilrtemplatetext,str_replace("/" , "", $row_sub['id']),"SOF");
                            $FFI = $this->getValueFromTemplate2($ilrtemplatetext,str_replace("/" , "", $row_sub['id']),"FFI");
                            $xml .= "<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode>" . $FFI . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            if(DB_NAME=='am_ela')
                            {
                                if($row_sub['qualification_type']=="FS")
                                    $xml.="<LearningDeliveryFAM><LearnDelFAMType>ACT</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode><LearnDelFAMDateFrom>" . $sd . "</LearnDelFAMDateFrom><LearnDelFAMDateTo></LearnDelFAMDateTo></LearningDeliveryFAM>";        
                                $xml.="<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>105</LearnDelFAMCode></LearningDeliveryFAM>";
                            }
                            else
                            {
                                $xml .= "<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" . $SOF . "</LearnDelFAMCode></LearningDeliveryFAM>";
                            }    

                            $xml .= "</LearningDelivery>";
                        }
                    }

                    $xml .= "</Learner>";

                    $xml = str_replace("&", "&amp;", $xml);
                    $xml = str_replace("'", "&apos;", $xml);

                    $sql = "Select contract_type from contracts where id ='$contract_id'";
                    $contract_type = DAO::getResultset($link, $sql);
                    $contract_type = $contract_type[0][0];
                    $contract = addslashes((string)$contract_id);
                    $contract_type=addslashes((string)$contract_type);

                    $sql = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('','$l03','0','$xml','$submission','$contract_type','$tr_id','0','0','1','1','$contract');";
                    DAO::execute($link, $sql);

                }
            }


            // create folders on enrolment
            if(SystemConfig::getEntityValue($link, "module_eportfolio"))
            {
                if(!file_exists(Repository::getRoot() . '/' . $tr->username))
                    mkdir(Repository::getRoot() . '/' . $tr->username, 0777, true);
                $folders = DAO::getSingleColumn($link, "SELECT folder FROM lookup_tr_folders WHERE enable = 'Y'");
                foreach($folders AS $f)
                {
                    if(!file_exists(Repository::getRoot() . '/' . $tr->username . '/' . $f))
                        mkdir(Repository::getRoot() . '/' . $tr->username . '/' . $f, 0777, true);
                }
            }

            if(in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]))
            {
                $this->createEpaEntries($link, $tr, $framework);
            }

	    if(in_array(DB_NAME, ["am_ela"]))
            {
                $sql = "SELECT
                        id,
                        internaltitle,
                        student_qualifications.`onefile_learning_aim_id`
                        FROM
                        student_qualifications
                        WHERE tr_id = '{$previous_training_record_id}'
                        AND onefile_learning_aim_id IS NOT NULL";

                $parentQuals = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                foreach($parentQuals AS $parentQual)
                {
                    $internalTitle = addslashes((string)$parentQual['internaltitle']);
                    $updateSql = "UPDATE student_qualifications 
                                    SET onefile_learning_aim_id = '{$parentQual['onefile_learning_aim_id']}'
                                    WHERE tr_id = '{$tr->id}' AND id = '{$parentQual['id']}' AND internaltitle = '{$internalTitle}'";
                    
                    DAO::execute($link, $updateSql);
                }
            }

            $link->commit();
        }

        catch(Exception $e)
        {
            $link->rollback();
            throw new WrappedException($e);
        }


        if($assessor!='')
            http_redirect('do.php?_action=read_user&username=' . $username);

    }
    public static function xmlspecialchars($text)
    {
        return str_replace('&#039;', '&apos;', htmlspecialchars((string)$text, ENT_QUOTES));
    }

    public static function getValueFromTemplate($ilr,$LearningAimRef,$Field)
    {
        if($ilr!='')
        {
            $ilr = Ilr2013::loadFromXML($ilr);
            foreach($ilr->LearningDelivery as $delivery)
            {
                if(("".$delivery->LearnAimRef) == $LearningAimRef || ("".$delivery->LearnAimRef)=='')
                    return $delivery->$Field;
            }
        }
    }

    public static function getValueFromTemplate2($ilr,$LearningAimRef,$Field)
    {
        if($ilr!='')
        {
            $ilr = Ilr2013::loadFromXML($ilr);
            foreach($ilr->LearningDelivery as $delivery)
            {
                if(("".$delivery->LearnAimRef) == $LearningAimRef || ("".$delivery->LearnAimRef)=='')
                    foreach($delivery->LearningDeliveryFAM as $ldf)
                        if($ldf->LearnDelFAMType==$Field)
                            return $ldf->LearnDelFAMCode;

            }
        }
    }

    public function pullPreviousEpisodeInfo(PDO $link, TrainingRecord $tr, Course $course)
    {
        $start_date = Date::toMySQL($tr->start_date);
        $sql = <<<SQL
SELECT
  tr.id
FROM
  tr
  INNER JOIN courses_tr
    ON tr.`id` = courses_tr.`tr_id`
WHERE tr.`username` = '{$tr->username}'
  AND courses_tr.`course_id` = '{$course->id}'
  AND tr.`status_code` = 6
  AND tr.`closure_date` <  '{$start_date}'
;
SQL;
        $bil_tr_id = DAO::getSingleValue($link, $sql);
        if($bil_tr_id != '')
        {
            DAO::execute($link, "UPDATE tr_tracking SET tr_tracking.tr_id = '{$tr->id}' WHERE tr_tracking.tr_id = '{$bil_tr_id}'");
        }
    }

    private function createEpaEntries(PDO $link, TrainingRecord  $tr, Framework $framework)
    {
	$start_date = Date::toMySQL($tr->start_date);
        $sql = <<<SQL
SELECT
  tr.id
FROM
  tr
  INNER JOIN courses_tr
    ON tr.`id` = courses_tr.`tr_id`
  INNER JOIN frameworks ON courses_tr.`framework_id` = frameworks.`id`
WHERE tr.`username` = '{$tr->username}'
  AND frameworks.`StandardCode` = '{$framework->StandardCode}'
  AND tr.`status_code` = 6
  AND tr.`closure_date` <  '{$start_date}'
;

SQL;
        $bil_tr_id = DAO::getSingleValue($link, $sql);
        if($bil_tr_id != '')
        {
            return;
        }

        $rows = [];
        $tasks = [
            '1' => '2', //'EPA ready',
            '10' => '32', //'Gateway Declarations',
            '11' => '34', //'EPA Forecast',
            '12' => '36', //'Gateway Forecast',
            //'15' => '38', //'End of Learning Statement',
            //'2' => '24', //'Employer reference',
            '3' => '22', //'Summative portfolio',
            //'4' => '26', //'IQA complete',
            '5' => '10', //'Passed to SS',
            '6' => '27', //'Synoptic project',
            '7' => '27', //'Interview',
            '9' => '21', //'Project',
        ];
        foreach($tasks AS $task_id => $status_id)
        {
            $forecast_months = null;
            $last_date_of_month = null;
	    $first_date_of_month = null;
            if(in_array($task_id, ['11', '12']))
            {
                if($task_id == 11)
                    $forecast_months = DAO::getSingleValue($link, "SELECT frameworks.epa_forecast FROM frameworks INNER JOIN student_frameworks ON frameworks.id = student_frameworks.`id` WHERE student_frameworks.`tr_id` = '{$tr->id}'");
                if($task_id == 12)
                    $forecast_months = DAO::getSingleValue($link, "SELECT frameworks.gateway_forecast FROM frameworks INNER JOIN student_frameworks ON frameworks.id = student_frameworks.`id` WHERE student_frameworks.`tr_id` = '{$tr->id}'");
                $temp_date = new Date($tr->start_date);
                $temp_date->addMonths($forecast_months);
                $last_date_of_month = date("Y-m-t", strtotime($temp_date->formatMySQL()));
		$first_date_of_month = $temp_date->format('Y-m') . "-01";
            }

	    if($task_id == "5")
            {
                if($framework->epa_org_id == "EPA0475")
                {
                    $status_id = 50; //AP
                }
                elseif($framework->epa_org_id == "EPA0001")
                {
                    $status_id = 10; //BCS
                }
		elseif($framework->epa_org_id == "EPA0440")
                {
                    $status_id = 51; //1st for EPA
                }
            } 
            $rows[] = [
                'id' => null,
                'tr_id' => $tr->id,
                'task_type' => 1, // On Programme
                'task' => $task_id,
                'potential_achievement_month' => $task_id == 1 ? DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, tr.`start_date`, tr.`target_date`) FROM tr WHERE tr.id = '{$tr->id}'") : null,
                'task_applicable' => 'N',
                'task_status' => $status_id,
                'task_date' => $tr->start_date,
                'task_actual_date' => $task_id == '11' ? $first_date_of_month : $last_date_of_month,
            ];
        }

        DAO::multipleRowInsert($link, 'op_epa', $rows);
    }

    private function createFsNotRequiredEntries(PDO $link, TrainingRecord  $tr, $inductee_id)
    {
        if($inductee_id == '')
            return;

        $fs_units = [
            "Functional Skills English",
            "Functional Skills English Reading Test",
            "Functional Skills Writing Test",
            "Functional Skills Mathematics",
            "Functional Skills Mathematics Test",
            "SLC",
        ];

        $fs_exempt = DAO::getSingleValue($link, "SELECT induction.fs_exempt FROM induction WHERE inductee_id = '{$inductee_id}'");
        if($fs_exempt == "Y")
        {
            DAO::execute($link, "UPDATE student_qualifications SET student_qualifications.`aptitude` = '1' WHERE student_qualifications.`tr_id` = '{$tr->id}'");

            foreach($fs_units AS $fs_unit)
            {
                $obj = new stdClass();
                $obj->tr_id = $tr->id;
                $obj->unit_ref = $fs_unit;
                $obj->code = 'NR';
                $obj->comments = null;
                $obj->created_by = $_SESSION['user']->id;
                DAO::saveObjectToTable($link, "op_tracker_unit_sch", $obj);
            }
        }
    }

    private function createCrmEntry(PDO $link, TrainingRecord $tr, $inductee_id)
    {
        if($inductee_id == '')
            return;

        $coach_comments = DAO::getSingleValue($link, "SELECT coordinator_notes FROM induction WHERE inductee_id = '{$inductee_id}'");
        if(trim($coach_comments) == '')
        {
            return;
        }    

        $crm = new LearnerCrmNote();
        $crm->tr_id = $tr->id;
        $crm->name_of_person = $tr->firstnames . ' ' . $tr->surname;
        $crm->position = 'Learner';
        $crm->type_of_contact = '5';
        $crm->subject = '95';
        $crm->date = date('Y-m-d');
        $crm->by_whom = 'Induction Owner';
        $crm->agreed_action = $coach_comments;

        $crm->save($link);
    }

    private function createFsProgressEntry(PDO $link, TrainingRecord  $tr, $inductee_id)
    {
        if($inductee_id == '')
        {
            return;
        }

        $induction_id = DAO::getSingleValue($link, "SELECT id FROM induction WHERE induction.inductee_id = '{$inductee_id}'");
        $induction = Induction::loadFromDatabase($link, $induction_id);
        if(is_null($induction))
        {
            return;
        }

        $fs_progress = new FSProgress();
        $fs_progress->tr_id = $tr->id;
        
        if($induction->wfd_assessment == "Y" && $induction->maths_gcse_elig_met == "Y")
        {
            $fs_progress->required = "4"; // None
        }
        elseif($induction->wfd_assessment == "N" && $induction->maths_gcse_elig_met == "N")
        {
            $fs_progress->required = "3"; // Both
        }
        elseif($induction->wfd_assessment == "N")
        {
            $fs_progress->required = "2"; // English
        }
        elseif($induction->maths_gcse_elig_met == "N")
        {
            $fs_progress->required = "1"; // Maths
        }

        if( in_array($induction->math_cert, ["5", "6"]) && in_array($induction->eng_cert, ["5", "6"]) )
        {
            $fs_progress->fs_required = "4";
        }
        elseif($induction->math_cert == "7" && $induction->eng_cert == "7")
        {
            $fs_progress->fs_required = "2";
        }
        elseif( in_array($induction->math_cert, ["1", "4"]) && in_array($induction->eng_cert, ["1", "4"]) )
        {
            $fs_progress->fs_required = "3";
        }

        if($induction->wfd_assessment == "N")
        {
            $fs_progress->english_evidence = 0;
        }
        elseif($induction->wfd_assessment == "Y")
        {
            $fs_progress->english_evidence = 1;
        }

        if($induction->maths_gcse_elig_met == "N")
        {
            $fs_progress->maths_evidence = 0;
            //$fs_progress->maths_overall_status = "1";
            $fs_progress->maths_test_status = "1";
            $fs_progress->maths_mock_status = "1";
        }
        elseif($induction->maths_gcse_elig_met == "Y")
        {
            $fs_progress->maths_evidence = 1;
            $fs_progress->maths_overall_status = "6";
            $fs_progress->maths_test_status = "8";
            $fs_progress->maths_mock_status = "5";
        }

        if($induction->wfd_assessment == "N")
        {
            //$fs_progress->english_course_overall_status = "1";
            $fs_progress->english_mock_status = "1";
            $fs_progress->english_overall_status_reading = "1";
            $fs_progress->english_overall_status_writing = "1";
            $fs_progress->scl_status = "1";
        }
        elseif($induction->wfd_assessment == "Y")
        {
            $fs_progress->english_course_overall_status = "6";
            $fs_progress->english_mock_status = "5";
            $fs_progress->english_overall_status_reading = "6";
            $fs_progress->english_overall_status_writing = "6";
            $fs_progress->scl_status = "4";
        }

        $fs_progress->save($link);
    }
}
?>