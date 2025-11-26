<?php
class lead_learner_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';

        if($key == '')
            throw new Exception("missing querystring argument: key");

        $review = DAO::getObject($link, "SELECT * FROM review_forms WHERE MD5(CONCAT('sunesis_lead_learner_review_form', id, tr_id)) = '{$key}'");/* @var $review LeapReviewForm */
        if(!isset($review->id))
        {
            LeapReviewForm::generateErrorPage($link);
            session_destroy();
            exit;
        }

        if($review->learner_sign != '')
        {
            LeapReviewForm::generateAlreadyCompletedPage($link);
            session_destroy();
            exit;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $review->tr_id);

        $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
        $employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);

        $framework_title = DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr->id}'");

        $coach = User::loadFromDatabaseById($link, $review->coach_id);

        $fs_registrations = [
            'eng' => '',
            'math' => ''
        ];

        $sql = <<<SQL
SELECT
    * 
FROM
    student_qualifications  
WHERE
    tr_id = '{$tr->id}' AND qualification_type = 'FS' AND aptitude = '0'
ORDER BY
    auto_id DESC
;
SQL;
        $student_qualifications_result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach($student_qualifications_result AS $student_qualification_row)
        {
            $title = strtolower($student_qualification_row['title']);
            if(!strpos($title, 'english') && $fs_registrations['eng'] == '')
            {
                $fs_registrations['eng'] = 'English Level ' . $student_qualification_row['level'];
            }
            if(!strpos($title, 'math') && $fs_registrations['math'] == '')
            {
                $fs_registrations['math'] = 'Maths Level ' . $student_qualification_row['level'];
            }
        }

        $source = 1; // assessor/coach

        $a_sign_img = $review->coach_sign != '' ?
            $review->coach_sign :
            DAO::getSingleValue($link, "SELECT users.signature FROM users WHERE users.id = '{$tr->coach}'");

        $disable_save = false;
        if($review->learner_sign != '' && $review->coach_sign != '')
            $disable_save = true;

        $learner_sign_img = $review->learner_sign != '' ?
            $review->learner_sign :
            DAO::getSingleValue($link, "SELECT learner_sign FROM review_forms WHERE id = '{$review->id}' AND learner_sign IS NOT NULL LIMIT 1");

	$minutes_planned = $tr->otj_hours * 60;
        $minutes_attended = DAO::getSingleValue($link, "SELECT SUM(duration_hours)*60 + SUM(duration_minutes) FROM otj WHERE tr_id = '{$tr->id}'");
        $hours_attended = $this->convertToHoursMins($minutes_attended, '%02d hours %02d minutes');
        $minutes_remaining = $minutes_planned - $minutes_attended;
        $hours_remaining = $this->convertToHoursMins($minutes_remaining, '%02d hours %02d minutes');

        if($minutes_remaining < 0)
            $minutes_remaining = 0;

        if(is_null($review->learner_sign) || $review->id > 3448)
            include_once('tpl_lead_learner_form_v2.php');
        else 
            include_once('tpl_lead_learner_form.php');
    }

    private function convertToHoursMins($time, $format = '%02d:%02d')
    {
        if ($time < 1)
        {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }
}