<?php
class LeapReviewForm extends Entity
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
	review_forms
WHERE
	id='$key'
LIMIT 1;
HEREDOC;
        $st = $link->query($query);

        $form = null;
        if($st)
        {
            $form = null;
            $row = $st->fetch();
            if($row)
            {
                $form = new LeapReviewForm();
                $form->populate($row);
            }
        }
        else
        {
            throw new Exception("Could not execute database query to find organisation. " . '----' . $query . '----' . $st->errorCode());
        }

        return $form;
    }


    public static function getDetail()
    {
        return (object)[
            'total_learning_hours_for_this_session' => null,
            'expectations' => null,
            'learning_aims_completed_in_this_session' => [],
            'cultural_development' => [
                'eNd' => 0,
                'safeguarding' => 0,
                'prevent' => 0,
                'british_values' => 0,
                'hot_topic_no' => null,
            ],
            'has_the_learner_progressed_to_sf' => null,
            'learner_reflection_on_learning_to_date' => null,
            'overall_progress' => [
                'knowledge' => null,
                'skills' => null,
                'behaviour' => null,
                'otj_monthly_target' => null,
                'otj_to_date' => null,
                'total_otj_req' => null,
                'risk_rating' => null,
            ],
            'targets' => [
                't3' => null,
                't4' => null,
                't5' => null,
            ],
            'employer_comments' => null,
        ];
    }

    public function save(PDO $link)
    {
        DAO::saveObjectToTable($link, 'review_forms', $this);
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
	<title>Sunesis | Onboarding</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>


<div class="jumbotron text-center">
  <h1 class="display-3">Error!</h1>
  <p class="lead"><strong>Invalid details</strong></p>
  <hr>
  <p class="lead">
    <img height="50px" class="headerlogo" src="$header_image1" />
  </p>
</div>

HTML;

    }

    public static function generateCompletionPage(PDO $link)
    {
        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

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
    <img height="50px" class="headerlogo" src="$header_image1" />
  </p>
</div>

HTML;

    }

    public static function generateAlreadyCompletedPage(PDO $link)
    {
        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

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
  <p class="lead"><strong>This form has already been completed.</strong></p>
  <hr>
  <p class="lead">
    <img height="50px" class="headerlogo" src="$header_image1" />
  </p>
</div>

HTML;

    }

    public $id = NULL;
    public $tr_id = NULL;
    public $date_of_activity = NULL;
    public $record_of_work_completed = NULL;
    public $learner_sign = NULL;
    public $learner_sign_date = NULL;
    public $coach_sign = NULL;
    public $coach_sign_date = NULL;
    public $coach_sign_name = NULL;
    public $emp_sign = NULL;
    public $emp_sign_date = NULL;
    public $emp_sign_name = NULL;
    public $total_learning_hours_for_this_session = NULL;
    public $expectations = NULL;
    public $end = 0;
    public $safeguarding = 0;
    public $prevent = 0;
    public $british_values = 0;
    public $hot_topic_no = NULL;
    public $has_the_learner_progressed_to_sf = 0;
    public $Knowledge = NULL;
    public $Skills = NULL;
    public $Behaviours = NULL;
    public $otj_monthly_target = NULL;
    public $otj_to_date = NULL;
    public $total_otj_req = NULL;
    public $risk_rating = NULL;
    public $t3 = NULL;
    public $t4 = NULL;
    public $t5 = NULL;
    public $t6 = NULL;
    public $t7 = NULL;
    public $t8 = NULL;
    public $employer_comments = NULL;
    public $learning_aims_completed_in_this_session = NULL;
    public $learner_reflection_on_learning_to_date = NULL;
    public $emails_sent_to_learner = NULL;
    public $emails_sent_to_employer = NULL;
    public $coach_id = NULL;
    public $smart1 = NULL;
    public $smart2 = NULL;
    public $smart3 = NULL;
    public $smart4 = NULL;
    public $smart5 = NULL;
    public $goal1 = NULL;
    public $goal2 = NULL;
    public $goal3 = NULL;
    public $eng_comp_percentage = NULL;
    public $math_comp_percentage = NULL;
    public $target_otj_this_month = NULL;
    public $actual_otj_this_month = NULL;
    public $otj_remaining_minutes = NULL;
    public $cultural_development = NULL;
    public $date_of_next_meeting = NULL;
    public $time_of_next_meeting = NULL;

}