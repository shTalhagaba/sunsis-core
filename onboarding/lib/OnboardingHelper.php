<?php
class OnboardingHelper {

    public static function getCurrentScriptUrl(): string
    {
        $isHttps = (
            (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        );

        $protocol = $isHttps ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
        $script = $_SERVER['SCRIPT_NAME'] ?? $_SERVER['PHP_SELF'] ?? '';

        return $protocol  . $host . $script;
    }

    public static function getSkillsAnalysisIdFromKey(PDO $link, $key)
    {
        return DAO::getSingleValue($link, "SELECT id FROM ob_learner_skills_analysis WHERE MD5(CONCAT('sunesis_skills_analysis_', id, '_key')) = '{$key}'");
    }

    public static function generateSkillsScanUrl($sa_id)
    {
        $key = "sunesis_skills_analysis_{$sa_id}_key";
        return self::getCurrentScriptUrl() . "?_action=form_skills_scan_learner&key=" . md5($key);
    }

    public static function validateSkillsScanKey(PDO $link, $id, $key)
    {

    }

    public static function generateOnboardingUrl($ob_learner_id)
    {
        return self::getCurrentScriptUrl() . "?_action=form_onboarding&id={$ob_learner_id}&key=" . md5($ob_learner_id . '_sunesis');
    }

    public static function generateNonAppEnrolmentUrl($ob_learner_id)
    {
        return self::getCurrentScriptUrl() . "?_action=form_non_app_enrolment&id={$ob_learner_id}&key=" . md5($ob_learner_id . '_sunesis_non_app_enrolment');
    }

    public static function isValidOnboardingUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis');
    }

    public static function isValidNonAppEnrolmentUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_non_app_enrolment');
    }

    public static function generateEmployerAgreementUrl($id, $employer_id)
    {
        return self::getCurrentScriptUrl()."?_action=sign_employer_agreement&id={$id}&employer_id={$employer_id}&key=".md5($id.'_'.$employer_id.'_sunesis');
    }

    public static function generateEmployerScheduleUrl($id, $employer_id, $tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=sign_employer_schedule&id={$id}&employer_id={$employer_id}&tr_id={$tr_id}&key=".md5($id.'_'.$employer_id.'_'.$tr_id.'_sunesis');
    }

    public static function generateEmployerAppAgreementUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=employer_sign_onboarding&tr_id={$tr_id}&key=".md5($tr_id.'_sunesis_employer_app_agreement');
    }

    public static function generatePersonalityTestUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=sign_fdil_learner&id={$tr_id}&key=".md5($tr_id.'_sunesis_sign_fdil_learner');
    }

    public static function generateInitialAssessmentUrl($tr_id, $subject)
    {
        $subject = strtolower($subject);

        $key = $tr_id."_sunesis_initial_assessment_key";

        return self::getCurrentScriptUrl() . "?_action=form_learner_initial_assessment&subject={$subject}&key=" . md5($key);
    }

    public static function getAssessmentTrainingIdFromKey(PDO $link, $key)
    {
        return DAO::getSingleValue($link, "SELECT id FROM ob_tr WHERE MD5(CONCAT( id,'_sunesis_initial_assessment_key')) = '{$key}'");
    }

    public static function generateFreeWritingAssessmentUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=learner_writing_assessment&id={$tr_id}&key=".md5($tr_id.'_sunesis_sign_free_writing_assessment_learner');
    }

    public static function generatePreIagFormUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=pre_iag_form_learner&id={$tr_id}&key=".md5($tr_id.'_sunesis_sign_pre_iag_form_learner');
    }

    public static function generateWellbeingAssessmentFormUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=view_wellbeing_assessment_form_learner&id={$tr_id}&key=".md5($tr_id.'_sunesis_sign_wellbeing_assessment_learner');
    }

    public static function generateBespokeTrainingPlanFormUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=view_bespoke_training_plan_form&id={$tr_id}&key=".md5($tr_id.'_sunesis_sign_bespoke_training_plan_learner');
    }

    public static function generateLearnStyleAssessmentUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=learning_style_assessment&id={$tr_id}&key=".md5($tr_id.'_sunesis_sign_learning_style_assessment_learner');
    }

    public static function generateLearnerFdilUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=learner_fdil&id={$tr_id}&key=".md5($tr_id.'_sunesis_sign_learner_fdil');
    }

    public static function generateTutorFdilUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=tutor_fdil&id={$tr_id}&key=".md5($tr_id.'_sunesis_sign_tutor_fdil');
    }

    public static function isValidPersonalityTestUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_sign_fdil_learner');
    }

    public static function isValidFreeWritingAssessmentUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_sign_free_writing_assessment_learner');
    }

    public static function isValidLearnerFdilUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_sign_learner_fdil');
    }

    public static function isValidTutorFdilUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_sign_tutor_fdil');
    }

    public static function isValidPreIagFormUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_sign_pre_iag_form_learner');
    }

    public static function isValidBespokeTrainingPlanFormUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_sign_bespoke_training_plan_learner');
    }

    public static function isValidWellbeingAssessmentFormUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_sign_wellbeing_assessment_learner');
    }

    public static function isValidOtjPlannerLearnerViewUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_otj_planner_learner_view');
    }

    public static function isValidDpLearnerViewUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_dp_learner_view');
    }

    public static function generateOtjPlannerLearnerViewUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=otj_planner_learner_view&id={$tr_id}&key=".md5($tr_id.'_sunesis_otj_planner_learner_view');
    }

    public static function generateDpLearnerViewUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=dp_learner_view&id={$tr_id}&key=".md5($tr_id.'_sunesis_dp_learner_view');
    }

    public static function isValidOtjPlannerEmployerViewUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_otj_planner_employer_view');
    }

    public static function isValidDpEmployerViewUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_dp_employer_view');
    }

    public static function generateOtjPlannerEmployerViewUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=otj_planner_employer_view&id={$tr_id}&key=".md5($tr_id.'_sunesis_otj_planner_employer_view');
    }

    public static function generateDpEmployerViewUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=dp_employer_view&id={$tr_id}&key=".md5($tr_id.'_sunesis_dp_employer_view');
    }

    public static function isValidSkillsScanEmployerUrl(PDO $link, $skills_analysis_id, $key)
    {
        return $key == md5($skills_analysis_id.'_skills_scan_employer_view');
    }

    public static function generateSkillsScanEmployerUrl($skills_analysis_id)
    {
        return self::getCurrentScriptUrl()."?_action=skills_scan_employer_view&id={$skills_analysis_id}&key=".md5($skills_analysis_id.'_skills_scan_employer_view');
    }

    public static function generateEmployerSignCommUrl($tr_id)
    {
        return self::getCurrentScriptUrl()."?_action=employer_sign_commercial_ob&tr_id={$tr_id}&key=".md5($tr_id.'_sunesis_employer_sign_commercial_ob');
    }

    public static function isValidLearnStyleAssessmentUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_sign_learning_style_assessment_learner');
    }

    public static function isValidEmployerAppAgreementUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_employer_app_agreement');
    }

    public static function isValidEmployerSignCommUrl(PDO $link, $tr_id, $key)
    {
        return $key == md5($tr_id.'_sunesis_employer_sign_commercial_ob');
    }

    public static function isValidKey(PDO $link, $id, $key)
    {
        return $key == md5($id.'_sunesis');
    }

    public static function isValidKey2Fa(PDO $link, $forwarding, $id, $key)
    {
        if($forwarding == 'sa')

            return $key == md5($id.'_sunesis');
    }

    public static function isValidEmployerScheduleKey(PDO $link, $id, $employer_id, $tr_id, $key)
    {
        return $key == md5($id.'_'.$employer_id.'_'.$tr_id.'_sunesis');
    }

    public static function isFormCompletedKey(PDO $link, $id, $forwarding, $key)
    {
        return $key == md5($id.$forwarding.'_sunesis_completed');
    }

    public static function generateErrorPage(PDO $link)
    {
        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Homepage</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
<body>

<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom"
    style="background-color: #BD1730;background-image: linear-gradient(to left, #BD1730, #9D8D8F)">
	<div class="container">
		<div class="navbar-header page-scroll">
			<a class="navbar-brand" href="#">
				<img height="50px" class="headerlogo" src="$header_image1" />
			</a>
		</div>
	</div>
</nav>

	<div class="nts-secondary-teaser-gradient" style="max-width: 450px; padding: 25px; border-radius: 25px; margin-left: 30%">
		<h2>Invalid Access Credentials</h2><hr>
		<p>The credentials you have supplied are unknown to the system.</p>
		<p>If you are sure that you have used the correct URL as specified in the email you received then contact us at <a style="color: white;" href="mailto:support@perspective-uk.com">support@perspective-uk.com</a>
		and provide the details.</p>
	</div>

	<footer class="main-footer">
		<div class="pull-left">
			<table class="table" style="border-collapse: separate; border-spacing: 0.5em;">
				<tr>

					<td><img width="230px" src="$header_image1" /></td>
				</tr>
			</table>
		</div>
		<div class="pull-right">
			<img src="images/logos/SUNlogo.png" />
		</div>
	</footer>
</body>
</html>
HTML;

    }

    public static function generateAlreadyCompletedPage(PDO $link, $tr_id = '')
    {
        if($tr_id == '')
        {
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        }
        else
        {
            $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations INNER JOIN tr ON organisations.id = tr.provider_id WHERE tr.id = '{$tr_id}'");
            if($logo == '')
                $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        }

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
        $client_name = SystemConfig::getEntityValue($link, "client_name");

        if(in_array(DB_NAME, ["am_barnsley", "am_barnsley_demo"]))
        {
            echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>$client_name | Already Completed</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
<body>

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom"
        style="background-color: #BD1730;background-image: linear-gradient(to left, #BD1730, #9D8D8F)">
        <div class="container">
            <div class="navbar-header page-scroll">
                <a class="navbar-brand" href="#">
                    <img height="50px" class="headerlogo" src="$logo" />
                </a>
            </div>
        </div>
    </nav>
    
    <content id="completionPage">
        <div class="jumbotron" 
            style="background-position: center; 
                background-size: 75%;
                background-image: url('/images/Form_Already_Completed.png');
                background-repeat: no-repeat;
                background-attachment: fixed; height: 80%;">
        </div>
    </content>

	<footer class="">
        <div class="pull-left">
            <img width="230px" src="$logo" />
        </div>
        <div class="pull-right">
            <img src="images/logos/SUNlogo.png" />
        </div>
    </footer>
    
</body>
</html>
HTML;
        }
        else
        {
            echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>


<div class="jumbotron text-center">
  <h1 class="display-3">Alert!</h1>
  <p class="lead"><strong>Your form is already completed.</strong></p>
  <hr>
  <p class="lead">
    <img height="50px" class="headerlogo" src="$header_image1" />
  </p>
</div>

HTML;
        }
    }

    public static function generateCompletionPage(PDO $link, $tr_id = '')
    {
        if($tr_id == '')
        {
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        }
        else
        {
            $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations INNER JOIN ob_tr ON organisations.id = ob_tr.provider_id WHERE ob_tr.id = '{$tr_id}'");
            if($logo == '')
                $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        }

        $client_name = SystemConfig::getEntityValue($link, "client_name");

        if(in_array(DB_NAME, ["am_barnsley", "am_barnsley_demo"]))
        {
            echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>$client_name | Completion Page</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/module_onboarding/css/onboarding.css">
<body>

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom" style="background-color: #BD1730;background-image: linear-gradient(to left, #BD1730, #9D8D8F)">
        <div class="container">
            <div class="navbar-header page-scroll">
                <a class="navbar-brand" href="#">
                    <img height="50px" class="headerlogo" src="$logo" />
                </a>
            </div>
        </div>
    </nav>

    <content id="completionPage">
        <div class="jumbotron" 
            style="background-position: center; 
                background-size: 75%;
                background-image: url('/images/Completion_Details_Thanks.png');
                background-repeat: no-repeat;
                background-attachment: fixed; height: 80%;">
        </div>
    </content>
    
    <footer class="">
        <div class="pull-left">
            <img width="230px" src="$logo" />
        </div>
        <div class="pull-right">
            <img src="images/logos/SUNlogo.png" />
        </div>
    </footer>
    
</body>
</html>
HTML;
        }
        else
        {
            echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>


<div class="jumbotron text-center">
  <h1 class="display-3">Thank You!</h1>
  <p class="lead"><strong>Your information has been saved successfully.</strong></p>
  <hr>
  <p class="lead">
    <img height="50px" class="headerlogo" src="$logo" />
  </p>
</div>

HTML;
        }
    }

    public static function getCheckboxesNames()
    {
        return [
            "previous_training",
            "currently_undertaking_training",
            "same_or_lower",
            "genuine_job",
            "substantially_diff",
        ];
    }

    public static function getYesNoList()
    {
        $listYesNo = [
            1 => "Yes",
            2 => "No",
        ];

        return $listYesNo;
    }

    public static function getYesNoDDL()
    {
        $ddlYesNo = [
            [1, "Yes"],
            [2, "No"],
        ];

        return $ddlYesNo;
    }

    public static function calculateKS($type, $assessment)
    {
        $result = new stdClass();

        $assessment = !is_array($assessment) ? (array)$assessment : $assessment;

        // total score
        $result->total_score = $type != 's' ? count($assessment)*4 : count($assessment)*3;

        // score earned by learner
        $result->score = array_sum($assessment);

        $temp = array_count_values($assessment);

        // answered 3 or 4 by learner for knowledge elements
        $result->t_3_or_4 = 0;
        if(isset($temp[3]))
            $result->t_3_or_4 += $temp[3];
        if(isset($temp[4]))
            $result->t_3_or_4 += $temp[4];

        // answered 2 or 3 by learner for skills elements
        $result->t_2_or_3 = 0;
        if(isset($temp[2]))
            $result->t_2_or_3 += $temp[2];
        if(isset($temp[3]))
            $result->t_2_or_3 += $temp[3];

        $count_of_questions = count($assessment) == 0 ? 1 : count($assessment);
        // percentage of answered 3 or 4 for knowledge elements
        $result->percentage_3_or_4 =  round( ($result->t_3_or_4/$count_of_questions)*100, 2);

        // percentage of answered 2 or 3 for skills elements
        $result->percentage_2_or_3 =  round( ($result->t_2_or_3/$count_of_questions)*100, 2);

        return $result;
    }

    public static function NumberBreakdown($number, $returnUnsigned = false)
    {
        $negative = 1;
        if ($number < 0)
        {
            $negative = -1;
            $number *= -1;
        }

        if ($returnUnsigned){
            return array(
                floor($number),
                ($number - floor($number))
            );
        }

        return array(
            floor($number) * $negative,
            ($number - floor($number)) * $negative
        );
    }

    public static function getReviewsDates($reviews_table_start_date, $reviews_table_end_date, $first_review_days = 28, $subsequent_review_days = 56)
    {
        $reviews = [];
        if($reviews_table_start_date == '' || $reviews_table_end_date == '')
            return $reviews;

        $reviews_table_start_date = !is_a($reviews_table_start_date, 'Date') ? new Date($reviews_table_start_date) : $reviews_table_start_date;
        $reviews_table_end_date = !is_a($reviews_table_end_date, 'Date') ? new Date($reviews_table_end_date) : $reviews_table_end_date;

        $review_number = 1;
        while($reviews_table_start_date->before($reviews_table_end_date))
        {
            if($review_number == 1)
                $reviews_table_start_date->addDays($first_review_days);
            else
                $reviews_table_start_date->addDays($subsequent_review_days);

            if($reviews_table_start_date->after($reviews_table_end_date))
                break;

            $reviews[$review_number] = $reviews_table_start_date->formatShort();

            $review_number++;
        }

        return $reviews;
    }

    public static function generateMarksAwardedDdl($start = 0, $end = 4)
    {
        $ddl = [];
        for($i = $start; $i <= $end; $i++)
        {
            $ddl[] = [$i, $i];
        }

        return $ddl;
    }

    public static function getDasAdminDdl()
    {
        return [
            [1, 'Employer to set up reservation'],
            [2, 'Employer reservation created'],
            [3, 'Administration team to update reservation'],
            [4, 'Reservation back with employer for approval'],
            [5, 'Live'],
            [6, 'Conflict or DAS issue'],
        ];
    }

    public static function getDasAdminList()
    {
        return [
            1 => 'Employer to set up reservation',
            2 => 'Employer reservation created',
            3 => 'Administration team to update reservation',
            4 => 'Reservation back with employer for approval',
            5 => 'Live',
            6 => 'Conflict or DAS issue',
        ];
    }

    public static function generateOtjColumnsHeader(PDO $link, $framework_id)
    {
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        return array_slice($cols, 0, self::colsOfStandard($link, $framework_id)-1);
    }

    public static function colsOfStandard(PDO $link, $framework_id)
    {
        $not_null_columns = <<<SQL
SELECT DISTINCT 
       (col_2_otj IS NOT NULL) + 
       (col_3_otj IS NOT NULL) + 
       (col_4_otj IS NOT NULL) + 
       (col_5_otj IS NOT NULL) + 
       (col_6_otj IS NOT NULL) + 
       (col_7_otj IS NOT NULL) + 
       (col_8_otj IS NOT NULL) + 
       (col_9_otj IS NOT NULL) + 
       (col_10_otj IS NOT NULL) + 
       (col_11_otj IS NOT NULL) + 
       (col_12_otj IS NOT NULL) + 
       (col_13_otj IS NOT NULL) + 
       (col_14_otj IS NOT NULL) + 
       (col_15_otj IS NOT NULL) + 
       (col_16_otj IS NOT NULL) AS num_null_columns
FROM otj_prog_template_sections WHERE framework_id = '{$framework_id}';        
SQL;
        return DAO::getSingleValue($link, $not_null_columns);
    }

    public static function UlnsToSkip(PDO $link)
    {
        if(DB_NAME != "am_ela")
        {
            return [];
        }

        return DAO::getSingleColumn($link, "SELECT tr_id FROM ulns_to_skip");
    }

    public static function otjPerWeekDescription($key)
    {
        $list = [
            'hpw_6' => '6 Hours per week (20%)',
            'hpw_7p5' => '7.5 Hours per week (25%)',
            'hpw_9' => '9 Hours per week (30%)',
            'hpw_10p5' => '10.5 Hours per week (35%)',
            'hpw_12' => '12 Hours per week (40%)',
            'hpw_13p5' => '13.5 Hours per week (45%)',
            'hpw_15' => '15 Hours per week (50%)',
        ];

        return isset($list[$key]) ? $list[$key] : '';
    }

    public static function otjPerWeekDdl()
    {
        return [
            ['hpw_6', '6 Hours per week (20%)'],
            ['hpw_7p5', '7.5 Hours per week (25%)'],
            ['hpw_9', '9 Hours per week (30%)'],
            ['hpw_10p5', '10.5 Hours per week (35%)'],
            ['hpw_12', '12 Hours per week (40%)'],
            ['hpw_13p5', '13.5 Hours per week (45%)'],
            ['hpw_15', '15 Hours per week (50%)'],
        ];
    }

    public static function immigrationStatusDdl()
    {
        return [
            [1, 'Asylum Seeker'],
            [2, 'Humanitarian Protection/Discretionary Leaver'],
            [3, 'Refugee'],
            [4, 'Right of Abode/Indefinite Leave to Remain or Enter'],
            [5, 'Exceptional Leave to Remain/Enter'],
            [6, 'Not applicable'],
        ];
    }

    public static function immigrationStatusDesc($id)
    {
        $list = [
            1 => 'Asylum Seeker',
            2 => 'Humanitarian Protection/Discretionary Leaver',
            3 => 'Refugee',
            4 => 'Right of Abode/Indefinite Leave to Remain or Enter',
            5 => 'Exceptional Leave to Remain/Enter',
            6 => 'Not applicable',
        ];
        return isset($list[$id]) ? $list[$id] : '';
    }

    public static function ictSkillsDdl()
    {
        return [
            ['Rarely/Never', 'Rarely/Never'],
            ['Occasional Basic Use', 'Occasional Basic Use'],
            ['Regular Use - Proficient', 'Regular Use - Proficient'],
        ];
    }

    const TOTAL_PLANNED_HOURS = 20;
    const APP_AGREEMENT_PDF_NAME = 'Apprenticeship Agreement.pdf';
    const EVIDENCE_EMPLOYMENT_PDF_NAME = 'Evidence of Employment Statement.pdf';
    const SKILLS_ANALYSIS_PDF_NAME = 'Skills Scan Result.pdf';
    const PRE_IAG_PDF_NAME = 'Pre IAG Form.pdf';
    const LEARN_STYLE_ASSESSMENT = 'Learning Style Assessment.pdf';
    const WRITING_ASSESSMENT_PDF_NAME = 'Learner Writing Assessment.pdf';
    const COMMITMENT_PDF_NAME = 'Commitment Statement.pdf';
    const LEARNING_AGREEMENT = 'Learning Agreement.pdf';
    const FIRST_LEARNING_ACTIVITY = 'First Learning Activity.pdf';
    const SCH_PDF_NAME = 'InitialContract.pdf';
    const TRAINING_PLAN_PDF_NAME = 'Training Plan.pdf';
    const EROLMENT_FORM_PDF_NAME = 'Enrolment Form.pdf';
    const FDIL_PDF_NAME = 'FDIL.pdf';
    const ALS_PDF_NAME = 'AdditionalLearningSupport.pdf';
}