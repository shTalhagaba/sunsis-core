<?php
class read_learner implements IAction
{
    public function execute(PDO $link)
    {
        $username = isset($_GET['username']) ? $_GET['username'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        if(!$username && !$id)
        {
            throw new Exception("Missing or empty querystring argument 'username'");
        }

        if ($id)
        {
            $vo = User::loadFromDatabaseById($link, $id);
            if (is_null($vo))
            {
                throw new Exception("No user with id '$id'");
            }
        }
        else
        {
            $vo = User::loadFromDatabase($link, $username);
            if (is_null($vo))
            {
                throw new Exception("No user with username '$username'");
            }
        }

        $_SESSION['bc']->add($link, "do.php?_action=read_learner&username={$vo->username}&id={$vo->id}", "View Learner");

        $tr_l03 = DAO::getSingleColumn($link, "SELECT DISTINCT l03 FROM tr WHERE tr.username='{$vo->username}' ORDER BY l03;");
        $tr_l03 = implode(',', $tr_l03);

        if($_SESSION['user']->type == User::TYPE_MANAGER)
            $ddlCourses = DAO::getResultSet($link, "SELECT id, title FROM courses WHERE courses.active = 1 AND courses.organisations_id='{$_SESSION['user']->employer_id}' ORDER BY title");
        else
            $ddlCourses = DAO::getResultSet($link, "SELECT id, title FROM courses WHERE courses.active = 1 ORDER BY title");

        $ddlLocations = [];

        if($_SESSION['user']->type == User::TYPE_MANAGER && DB_NAME != 'am_lead')
            $ddlContracts = DAO::getResultset($link,"SELECT id, title FROM contracts WHERE active = 1 AND contract_year >= YEAR(NOW())-2 AND title LIKE '%{$_SESSION['user']->org->legal_name}%' ORDER BY contract_year DESC, title");
        else
            $ddlContracts = DAO::getResultSet($link, "SELECT id, title FROM contracts WHERE active = 1 AND contract_year >= YEAR(NOW())-2 ORDER BY contract_year DESC, title ");

        $assessor_type = User::TYPE_ASSESSOR;
        if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo", "am_demo"]))
        {
            $assessors_sql = <<<HEREDOC
SELECT
users.id,
CONCAT(
    IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
    IF(surname IS NULL,'',surname), ' - ',
    users.username
),
NULL
FROM
users
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
INNER JOIN organisations ON organisations.id = users.employer_id
WHERE users.active = 1 AND TYPE != 5
ORDER BY CONCAT(firstnames, ' ', surname);
HEREDOC;

        }
        elseif(in_array(DB_NAME, ["am_city_skills", "am_ela"]))
        {
            $assessors_sql = <<<HEREDOC
SELECT
users.id,
CONCAT(
    IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
    IF(surname IS NULL,'',surname),
    IF(department IS NOT NULL OR job_role IS NOT NULL,
        CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
    ' - ',
    users.username
),
NULL
FROM
users
INNER JOIN organisations on organisations.id = users.employer_id 
where type=3 and users.active = 1
ORDER BY CONCAT(firstnames, ' ', surname)
HEREDOC;

        }
        else
        {
            $assessors_sql = <<<HEREDOC
SELECT
users.id,
CONCAT(
    IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
    IF(surname IS NULL,'',surname),
    IF(department IS NOT NULL OR job_role IS NOT NULL,
        CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
    ' - ',
    users.username
),
NULL
FROM
users
INNER JOIN organisations on organisations.id = users.employer_id 
where type=3 and users.active = 1
ORDER BY CONCAT(firstnames, ' ', surname)
HEREDOC;

        }

        $ddlAssessors = DAO::getResultset($link, $assessors_sql);

        $tutor_type = User::TYPE_TUTOR;
        $tutor_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), '')
	),
	NULL
FROM
	users
WHERE
users.active = 1 and type = '$tutor_type'
ORDER BY
	firstnames, surname;
HEREDOC;
        $ddlTutors = DAO::getResultset($link, $tutor_sql);

        $verifier_type = User::TYPE_VERIFIER;
        $verifier_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
WHERE
users.active = 1 and 	users.type = '$verifier_type'
ORDER BY
	firstnames, surname;
HEREDOC;
        $ddlVerifiers = DAO::getResultset($link, $verifier_sql);

        $gender_description = "SELECT description FROM lookup_gender WHERE id='{$vo->gender}';";
        $gender_description = DAO::getSingleValue($link, $gender_description);

        $nationality_description = "SELECT description FROM lookup_country_list WHERE code='{$vo->nationality}';";
        $nationality_description = DAO::getSingleValue($link, $nationality_description);

	if(SystemConfig::getEntityValue($link, "onboarding"))
        {
            $nationality_description = DAO::getSingleValue($link, "SELECT description FROM lookup_nationalities WHERE id = '{$vo->nationality}'");
        }

        $pre_assessment = DAO::getLookupTable($link, "SELECT id, description FROM lookup_pre_assessment;");
        $numeracy = isset($pre_assessment[$vo->numeracy]) ? $pre_assessment[$vo->numeracy] : '';
        $literacy = isset($pre_assessment[$vo->literacy]) ? $pre_assessment[$vo->literacy] : '';
        $ict = isset($pre_assessment[$vo->ict]) ? $pre_assessment[$vo->ict] : '';

        $ethnicity_description = DAO::getSingleValue($link, "SELECT Ethnicity_Desc FROM lis201112.ilr_l12_ethnicity WHERE Ethnicity_Code = '{$vo->ethnicity}'");

        $home_address = new Address($vo, 'home_');
        $work_address = new Address($vo, 'work_');

        $page_title = "{$vo->firstnames} {$vo->surname}";

        $photopath = $vo->getPhotoPath();
        if($photopath)
        {
            $photopath = "do.php?_action=display_image&username=".rawurlencode($vo->username);
        }
        else
        {
            $photopath = "/images/no_photo.png";
        }

        $bil_learner = false;
        if($vo->type == User::TYPE_LEARNER)
        {
            $isThereAnyBreakInLearningTrainingRecord = DAO::getResultset($link, "SELECT * FROM tr WHERE status_code = 6 AND outcome = 3 AND username = '" . $vo->username . "' ORDER BY id DESC LIMIT 1", DAO::FETCH_ASSOC);
            $bil_learner = false;
            $previous_course_id = null;
            $previous_provider_name = null;
            $previous_assessor_id = null;
            $previous_tutor_id = null;
            $previous_verifier_id = null;
            $previous_contract_id = null;
            $previous_training_record_id = null;
            if(count($isThereAnyBreakInLearningTrainingRecord) > 0)
            {
                $bil_tr = $isThereAnyBreakInLearningTrainingRecord[0];
                $previous_course = Course::loadFromDatabase($link, DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = " . $bil_tr['id']));
                $isLearnerAlreadyReEnrolled = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.`status_code` != 6 AND tr.start_date > '" . $bil_tr['start_date'] . "' AND tr.l03 = '" . $bil_tr['l03'] . "'");
                if($isLearnerAlreadyReEnrolled == 0)
                {
                    $bil_learner = true;
                    $previous_course_id = $previous_course->id;
                    $previous_provider_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $previous_course->organisations_id);
                    $previous_assessor_id = $bil_tr['assessor'];
                    $previous_tutor_id = $bil_tr['tutor'];
                    $previous_verifier_id = isset($bil_tr['verifier']) ? $bil_tr['verifier'] : '';
                    $previous_contract_id = $bil_tr['contract_id'];
                    $previous_training_record_id = $bil_tr['id'];
                }
            }
            if(DB_NAME == "am_baltic")
            {
                $candidate_id = DAO::getSingleValue($link, "SELECT id FROM candidate WHERE candidate.username = '" . $vo->username . "'");
                if($candidate_id != '')
                {
                    $view_candidate_crm = ViewCandidateCRM::getInstance($link, $candidate_id);
                    $view_candidate_crm->refresh($link, $_REQUEST);
                }
            }
        }

        $LLDD = array('1' => 'Yes', '2' => 'No', '3' => 'Prefer not to say');
        $LLDDCat = array(
            '4' => 'Visual impairment',
            '5' => 'Hearing impairment',
            '6' => 'Disability affecting mobility',
            '7' => 'Profound complex disabilities',
            '8' => 'Social and emotional difficulties',
            '9' => 'Mental health difficulty',
            '10' => 'Moderate learning difficulty',
            '11' => 'Severe learning difficulty',
            '12' => 'Dyslexia',
            '13' => 'Dyscalculia',
            '14' => 'Autism spectrum disorder',
            '15' => 'Asperger\'s syndrome',
            '16' => 'Temporary disability after illness (for example post-viral) or accident',
            '17' => 'Speech, Language and Communication Needs',
            '93' => 'Other physical disability',
            '94' => 'Other specific learning difficulty (e.g. Dyspraxia)',
            '95' => 'Other medical condition (for example epilepsy, asthma, diabetes)',
            '96' => 'Other learning difficulty',
            '97' => 'Other disability',
            '98' => 'Prefer not to say'
        );

        $forskills_info = null;
        if(in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
            $forskills_info = $vo->getForskillsUser($link);

        /*$ob_learner = null;
        if(SystemConfig::getEntityValue($link, "module_onboarding"))
        {
            $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE user_id = '{$vo->id}'");
        }

        if(in_array(DB_NAME, ["am_lead_demo", "am_lead"]))
        {
            $coaches_sql = <<<HEREDOC
SELECT
	users.id,
	CONCAT(
		IF(firstnames IS NULL, '', IF(surname IS NULL,firstnames, CONCAT(firstnames,' '))),
		IF(surname IS NULL,'',surname),
		IF(department IS NOT NULL OR job_role IS NOT NULL,
			CONCAT(' (', IF(department IS NOT NULL, IF(job_role IS NOT NULL, CONCAT(department,', ', job_role),department), job_role), ')'), ''),
		' - ',
		users.username
	),
	NULL
FROM
	users
INNER JOIN organisations ON organisations.id = users.employer_id
WHERE users.web_access = 1 AND users.type NOT IN (5, 12)
AND users.`username` NOT IN (SELECT ident FROM acl WHERE resource_category = 'application' AND privilege = 'administrator')
ORDER BY CONCAT(firstnames, ' ', surname)
;
HEREDOC;
            $ddlCoaches = DAO::getResultset($link, $coaches_sql);
        }*/
	
	$organisation_contact = DAO::getObject($link, "SELECT * FROM organisation_contact WHERE org_id = '{$vo->employer_id}' LIMIT 1");

        if(in_array(DB_NAME, ["am_duplex"]))
        {
	    $entityId = $vo->id;
            $entityType = get_class($vo);
            $existsInRegistrations = DAO::getSingleValue($link, "SELECT COUNT(*) FROM registrations WHERE registrations.entity_id = '{$entityId}' AND registrations.entity_type = '{$entityType}'");
            if(! $existsInRegistrations)
            {
                $reg = new Registration();
                $reg->entity_id = $entityId;
                $reg->entity_type = $entityType;
                $reg->firstnames = $vo->firstnames;
                $reg->surname = $vo->surname;
                $reg->dob = $vo->dob;
                $reg->save($link);
            }

            include('tpl_read_learner_duplex.php');
        }
        else
        {
            include('tpl_read_learner.php');
        }
    }

    public function renderComposeNewMessageBox(PDO $link, User $vo)
    {
        $template_types_list = [
            'HS_REQUEST',
            'WOLVERHAMPTON_LEVEL_4_JOIN_INST_NO_IMI',
            'WOLVERHAMPTON_LEVEL_4_JOIN_INST_WITH_IMI',
            'WOLVERHAMPTON_LEVEL_4_REMINDER',
            'WOLVERHAMPTON_LEVEL_3_JOIN_INST_NO_IMI',
            'WOLVERHAMPTON_LEVEL_3_JOIN_INST_WITH_IMI',
            'WOLVERHAMPTON_LEVEL_3_REMINDER',
            'RUDDINGTON_LEVEL_4_JOIN_INST_NO_IMI',
            'RUDDINGTON_LEVEL_4_JOIN_INST_WITH_IMI',
            'RUDDINGTON_LEVEL_4_REMINDER',
            'RUDDINGTON_LEVEL_3_JOIN_INST_NO_IMI',
            'RUDDINGTON_LEVEL_3_JOIN_INST_WITH_IMI',
            'RUDDINGTON_LEVEL_3_REMINDER',
            'LINCOLN_LEVEL_4_JOIN_INST_NO_IMI',
            'LINCOLN_LEVEL_4_JOIN_INST_WITH_IMI',
            'LINCOLN_LEVEL_4_REMINDER',
            'LINCOLN_LEVEL_3_JOIN_INST_NO_IMI',
            'LINCOLN_LEVEL_3_JOIN_INST_WITH_IMI',
            'LINCOLN_LEVEL_3_REMINDER',
            'DUPLEX_REG_FORM'
        ];

        $template_types_list = [
            'HS_REQUEST',
            'Master_Level_4_WOLVES_JI',
            'Master_Level_3_WOLVES_JI',
            'Master_Level_4_Nottingham_JI',
            'Master_Level_4_Lincoln_JI',
            'Master_Level_3_Nottingham_JI',
            'Master_Level_3_Lincoln_JI',
            'BC_REGISTRATION_FORM_URL'
        ];
        
        $template_types_list = "'" . implode("','", $template_types_list) . "'";

        $email_templates = DAO::getResultset($link, "SELECT template_type, template_type, null FROM email_templates WHERE template_type IN ($template_types_list) ORDER BY sorting;");
        array_unshift($email_templates, array('','Email template:',''));
        $ddlTemplates =  HTML::selectChosen('frmEmailTemplate', $email_templates, '', false);
        $html = <<<HTML
<form name="frmEmail" id="frmEmail" action="do.php?_action=ajax_email_actions" method="post">
	<input type="hidden" name="subaction" value="sendEmail" />
	<input type="hidden" name="frmEmailEntityType" value="sunesis_learner" />
	<input type="hidden" name="frmEmailEntityId" value="$vo->id" />
	<div class="box box-primary">
		<div class="box-header with-border"><h2 class="box-title">Compose New Email</h2></div>
		<div class="box-body">
			<div class="form-group"><div class="row"> <div class="col-sm-8"> $ddlTemplates </div><div class="col-sm-4"> <span class="btn btn-sm btn-default" onclick="load_email_template_in_frmEmail();">Load template</span></div> </div></div>
			<div class="form-group">To: <input name="frmEmailTo" id="frmEmailTo" class="form-control compulsory" placeholder="To:" value="$vo->home_email"></div>
			<div class="form-group">Subject: <input name="frmEmailSubject" id="frmEmailSubject" class="form-control compulsory" placeholder="Subject:"></div>
			<div class="form-group"><textarea name="frmEmailBody" id="frmEmailBody" class="form-control compulsory" style="height: 300px"></textarea></div>
		</div>
		<div class="box-footer">
			<div class="pull-right"><span class="btn btn-primary" onclick="sendEmail();"><i class="fa fa-envelope-o"></i> Send</span></div>
			<span class="btn btn-default" onclick="$('#btnCompose').show(); $('#mailBox').show(); $('#composeNewMessageBox').hide();"><i class="fa fa-times"></i> Discard</span>
		</div>
	</div>
</form>
HTML;

        return $html;
    }

    public function showSentEmails(PDO $link, User $vo)
    {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered small">';
        $result = DAO::getResultset($link, "SELECT * FROM emails WHERE emails.entity_type = 'sunesis_learner' AND emails.entity_id = '{$vo->id}' ORDER BY created DESC", DAO::FETCH_ASSOC);
        echo '<caption class="lead text-bold text-center">Sent Emails (' . count($result) . ')</caption>';
        echo '<tr><th>DateTime</th><th>By</th><th>To Address</th><th>From Address</th><th>Subject</th><th>Email</th></tr>';
        foreach($result AS $row)
        {
            echo '<tr>';
            echo '<td>' . Date::to($row['created'], Date::DATETIME) . '</td>';
            echo (DB_NAME == "am_duplex" && $row['by_whom'] == '9999') ?
                '<td class="bg-info">AUTOMATED EMAIL</td>' :
                '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = '{$row['by_whom']}'") . '</td>';
            echo '<td>' . $row['email_to'] . '</td>';
            echo '<td>' . $row['email_from'] . '</td>';
            echo '<td>' . $row['email_subject'] . '</td>';
            echo '<td><span class="btn btn-xs btn-info" onclick="viewEmail(\''.$row['id'].'\');"><i class="fa fa-eye"></i> View Email</span> </td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }

    public function generateSignatureImageFromHsForm(PDO $link, $hs_form)
    {
        if(!isset($hs_form->learner_sign) || $hs_form->learner_sign == '')
        {
            return;
        }

        $learner = User::loadFromDatabaseById($link, $hs_form->learner_id);
        if(is_null($learner))
        {
            return;
        }

        $directory = Repository::getRoot() . "/{$learner->username}/crm/hs_form/signatures";
        if(!is_dir($directory))
        {
            mkdir("$directory", 0777, true);
        }
        $learner_signature_file = "{$directory}/learner_sign.png";
        if(!is_file($learner_signature_file))
        {
            $signature_parts = explode('&', $hs_form->learner_sign);
            if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
            {
                $title = explode('=', $signature_parts[0]);
                $font = explode('=', $signature_parts[1]);
                $size = explode('=', $signature_parts[2]);
                $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                imagepng($signature, $learner_signature_file, 0, NULL);
            }
        }
    }

}
?>