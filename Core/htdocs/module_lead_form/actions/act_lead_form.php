<?php
class lead_form implements IAction
{
    public function execute(PDO $link)
    {
        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        if($review_id == '' && $tr_id == '')
            throw new Exception("missing querystring arguments: review_id, tr_id");

        $review = LeapReviewForm::loadFromDatabase($link, $review_id);
        $tr = TrainingRecord::loadFromDatabase($link, $_REQUEST['tr_id']);

        if($tr->coach == '')
        {
            throw new Exception("Please edit the learner's training record and select the Coach.");
        }
        else
        {
            $coach_chk = User::loadFromDatabaseById($link, $tr->coach);
            if(is_null($coach_chk))
                throw new Exception("Please edit the learner's training record and select the Coach.");
        }

        $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
        $employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);

        $framework_title = DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr->id}'");

        $coach_id = $tr->coach == '' ? $_SESSION['user']->id : $tr->coach;
        $coach = User::loadFromDatabaseById($link, $coach_id);

        $fs_registrations = [
            'eng' => '',
            'math' => ''
        ];

        $fs_progress = [
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
     AND LOWER(internaltitle) LIKE '%english%'
ORDER BY
    auto_id DESC
;
SQL;
        $student_qualifications_result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach($student_qualifications_result AS $student_qualification_row)
        {
            $fs_registrations['eng'] = 'English Level ' . $student_qualification_row['level'];
            if($student_qualification_row['unitsUnderAssessment'] > 0)
                $fs_progress['eng'] = sprintf("%.0f", $student_qualification_row['unitsUnderAssessment']);
        }

        $sql = <<<SQL
SELECT
    * 
FROM
    student_qualifications  
WHERE
    tr_id = '{$tr->id}' AND qualification_type = 'FS' AND aptitude = '0'
     AND LOWER(internaltitle) LIKE '%math%'
ORDER BY
    auto_id DESC
;
SQL;
        $student_qualifications_result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach($student_qualifications_result AS $student_qualification_row)
        {
            $fs_registrations['math'] = 'Maths Level ' . $student_qualification_row['level'];
            if($student_qualification_row['unitsUnderAssessment'] > 0)
                $fs_progress['math'] = sprintf("%.0f", $student_qualification_row['unitsUnderAssessment']);
        }

        $_SESSION['bc']->add($link, "do.php?_action=lead_form&review_id={$review->id}&tr_id={$tr->id}", "View Learner's Contact Form");
        if($subaction == 'export_pdf')
        {
            $this->export_pdf($link, [
                'review' => $review,
                'tr' => $tr,
                'employer' => $employer,
                'employer_location' => $employer_location,
                'framework_title' => $framework_title,
                'coach' => $coach,
                'fs_registrations' => $fs_registrations,
            ]);
        }

        $source = 1; // assessor/coach

        $a_sign_img = $review->coach_sign != '' ?
            $review->coach_sign : $_SESSION['user']->signature;
            //DAO::getSingleValue($link, "SELECT users.signature FROM users WHERE users.id = '{$tr->coach}'");

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

        if(is_null($review->coach_sign) || $review->id > 3448)
            include_once('tpl_lead_form_v2.php');
        else    
            include_once('tpl_lead_form.php');
    }

    public function export_pdf(PDO $link, $info)
    {
        $review = $info['review'];
        $tr = $info['tr'];
        $employer = $info['employer'];
        $employer_location = $info['employer_location'];
        $framework_title = $info['framework_title'];
        $coach = $info['coach'];
        $fs_registrations = $info['fs_registrations'];

        include_once("./MPDF57/mpdf.php");

        $logo = SystemConfig::getEntityValue($link, 'ob_header_image1');

        $mpdf=new mPDF('','Legal','10');

        $mpdf->setAutoBottomMargin = 'stretch';

        $sunesis_stamp = md5('ghost'.date('d/m/Y').$tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);
        $date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "50%" align="left">{$date}</td>
					<td width = "50%" align="right">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        $employer_address = $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : '';
        $employer_address .= $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : '';
        $employer_address .= $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : '';
        $employer_address .= $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : '';
        $employer_address .= $employer_location->postcode != '' ? $employer_location->postcode . '<br>' : '';

        echo <<<HTML
<div style="text-align: left;">
    <h2><strong>Learner Engagement Action Plan</strong></h2>
    <img width="150px;" class="img-responsive" src="$logo" />
</div>
HTML;

        $sd = Date::toShort($tr->start_date);
        $ped = Date::toShort($tr->target_date);
        $da = Date::toShort($review->date_of_activity);
        $record_of_work = str_replace(",", "<br> ", $review->record_of_work_completed);

        $completed_aims = '';
        foreach(explode(",", $review->learning_aims_completed_in_this_session) AS $auto_id)
        {
            $completed_aims .= DAO::getSingleValue($link, "SELECT internaltitle FROM student_qualifications WHERE auto_id = '{$auto_id}'") . '<br>';
        }

        $end = $review->end == 1 ? 'Yes' : 'No';
        $safeguarding = $review->safeguarding == 1 ? 'Yes' : 'No';
        $prevent = $review->prevent == 1 ? 'Yes' : 'No';
        $british_values = $review->british_values == 1 ? 'Yes' : 'No';
        $has_the_learner_progressed_to_sf = $review->has_the_learner_progressed_to_sf == 1 ? 'Yes' : 'No';
        $risk_rating_list = ['R' => 'Red', 'A' => 'Amber', 'G' => 'Green'];
        $risk_rating = isset($risk_rating_list[$review->risk_rating]) ? $risk_rating_list[$review->risk_rating] : $review->risk_rating;

        $directory = Repository::getRoot() . "/{$tr->username}/signatures/";
        if(!is_dir($directory))
        {
            mkdir("$directory", 0777, true);
        }
        $learner_signature_file = '';
        if($review->learner_sign != '')
        {
            $learner_signature_file = $directory . 'learner_sign_image.png';
            if(!is_file($learner_signature_file))
            {
                $signature_parts = explode('&', $review->learner_sign);
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
        $coach_signature_file = '';
        if($review->coach_sign != '')
        {
            $coach_signature_file = $directory . 'coach_sign_image.png';
            if(!is_file($coach_signature_file))
            {
                $signature_parts = explode('&', $review->coach_sign);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                    imagepng($signature, $coach_signature_file, 0, NULL);
                }
            }
        }
        $coach_signature_file = $coach_signature_file != '' ?
            '<img src="' . $coach_signature_file . '" alt="Coach Sign" />' : '';
        $learner_signature_file = $learner_signature_file != '' ?
            '<img src="' . $learner_signature_file . '" alt="Learner Sign" />' : '';



        echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th>Learner</th>
            <td>{$tr->firstnames} {$tr->surname}</td>
        </tr>
        <tr>
            <th>Company</th>
            <td>
                {$employer->legal_name} <br>
                {$employer_address}<br>
            </td>
        </tr>
        <tr>
            <th>Qualification & Level</th>
            <td>
                {$framework_title}
            </td>
        </tr>
        <tr>
            <th>Coach</th>
            <td>
                {$coach->firstnames} {$coach->surname}<br>
                {$coach->work_email}
            </td>
        </tr>
    </table>
    <p></p>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Start Date</th><th>End Date</th><th>FS Registrations</th></tr>
        <tr>
            <td>{$sd}</td><td>{$ped}</td><td>{$fs_registrations['eng']}<br>{$fs_registrations['math']}</td>
        </tr>
    </table>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Date of activity</th><th>Total Learning Hours for session</th></tr>
        <tr>
            <td>{$da}</td><td>{$review->total_learning_hours_for_this_session}</td>
        </tr>
    </table>
    <p></p>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Record of Work Completed</th></tr>
        <tr><td>{$record_of_work}</td></tr>
        <tr><th>Exceptions to the above and additional information</th></tr>
        <tr><td>{$review->expectations}</td></tr>
    </table>
    <p></p>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Learning aims completed in this session</th></tr>
        <tr><td>{$completed_aims}</td></tr>
    </table>
    <p></p>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2">Cultural Development</th></tr>
        <tr><th>E & D</th><td>{$end}</td></tr>
        <tr><th>Safeguarding</th><td>{$safeguarding}</td></tr>
        <tr><th>Prevent</th><td>{$prevent}</td></tr>
        <tr><th>British Values</th><td>{$british_values}</td></tr>
        <tr><th>Hot Topic No.</th><td>{$review->hot_topic_no}</td></tr>
    </table>
    <p></p>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Has the learner progressed on Skills Forward since last session</th><td>{$has_the_learner_progressed_to_sf}</td></tr>
    </table>
    <p></p>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Learners reflection on learning to date</th></tr>
        <tr><td>{$review->learner_reflection_on_learning_to_date}</td></tr>
    </table>
    <p></p>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2">Overall Progress</th></tr>
        <tr><th>Knowledge</th><td>{$review->Knowledge}</td></tr>
        <tr><th>Skills</th><td>{$review->Skills}</td></tr>
        <tr><th>Behaviour</th><td>{$review->Behaviour}</td></tr>
        <tr><th>English Completion Percentage</th><td>{$review->eng_comp_percentage}</td></tr>
        <tr><th>Maths Completion Percentage</th><td>{$review->math_comp_percentage}</td></tr>
        <tr><th>OTJ Monthly Target</th><td>{$review->otj_monthly_target}</td></tr>
        <tr><th>OTJ to Date</th><td>{$review->otj_to_date}</td></tr>
        <tr><th>Total OTJ Req</th><td>{$review->total_otj_req}</td></tr>
        <tr><th>Risk Rating</th><td>{$risk_rating}</td></tr>
    </table>
    <p></p>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2">SMART Targets</th></tr>
        <tr><th>1.</th><td>Complete development of maths and english on the Skills Forward Platform (<a href="https://www.myskillsforward.co.uk/institution/lead/">https://www.myskillsforward.co.uk/institution/lead/<a>)</td></tr>
        <tr><th>2.</th><td>Complete OTJ Diary and submit to coach {$coach->work_email}</td></tr>
        <tr><th>3.</th><td>{$review->t3}</td></tr>
        <tr><th>4.</th><td>{$review->t4}</td></tr>
        <tr><th>5.</th><td>{$review->t5}</td></tr>
        <tr><th>6.</th><td>{$review->t6}</td></tr>
        <tr><th>7.</th><td>{$review->t7}</td></tr>
        <tr><th>8.</th><td>{$review->t8}</td></tr>
    </table>
    <p></p>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2">Targets/actions that support your individual learning goals (Career and ambition goals)</th></tr>
        <tr><th>1.</th><td>{$review->goal1}</td></tr>
        <tr><th>2.</th><td>{$review->goal2}</td></tr>
        <tr><th>3.</th><td>{$review->goal3}</td></tr>
    </table>
    <p></p>
    <table border="1" style="width: 100%;" cellpadding="6">
    <tr><th>Coach</th><td>{$coach_signature_file}</td></tr>
    <tr><th>Learner</th><td>{$learner_signature_file}</td></tr>
    </table>

</div>
HTML;


        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();

        $mpdf->WriteHTML($html);

//        $mpdf->Output('Review.pdf', 'I');

        $mpdf->Output('EngagementForm'.$review->date_of_activity.'.pdf', 'D');

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