<?php
class PreIagForm
{
    public static function toPdf(PDO $link, TrainingRecord $tr, $assessment, $iag_file, $learner_signature_file, $provider_signature_file)
    {
        if ($tr->isNonApp($link)) {
            self::toPdfNonApp($link, $tr, $assessment, $iag_file, $learner_signature_file, $provider_signature_file);
            return;
        }

        $ob_learner = $tr->getObLearnerRecord($link);

        $funding_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.lookup_submission_dates WHERE '{$tr->practical_period_start_date}' BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1");
        if ($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && in_array($tr->id, OnboardingHelper::UlnsToSkip($link))) {
            $funding_year = '';
        }

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
        if ($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

        $mpdf = new \Mpdf\Mpdf(['format' => 'Legal', 'default_font_size' => 10]);
        $mpdf->SetMargins(15, 15, 36);
        $mpdf->setAutoBottomMargin = 'stretch';

        $header = <<<HEADER
    <div>
        <table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
            <tr>
                <td width = "50%" align="left"><img class="img-responsive" src="images/logos/apprenticeship.png" height="2.00cm" width="6.11cm" alt="Apprenticeship" /></td>
                <td width = "50%" align="right"><img class="img-responsive" src="$logo" height="1.50cm" width="5cm"  /></td>
            </tr>
        </table>
    </div>

HEADER;

        $mpdf->SetHTMLHeader($header);
        $sunesis_stamp = md5('ghost' . date('d/m/Y') . $tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);
        $date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
    <div>
        <table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
            <tr>
                <td width = "30%" align="left" style="font-size: 10px">{$date}</td>
                <td width = "35%" align="left" style="font-size: 10px">Learner Pre IAG Form</td>
                <td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
            </tr>
        </table>
    </div>
HEREDOC;
        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        $form_data = is_null($assessment->form_data) ? null : json_decode($assessment->form_data);
        $tutor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->trainers}'");
        if ($tutor_name == '') {
            $tutor_name = $assessment->provider_sign_name;
        }
        $question_1a = (!is_null($form_data) && isset($form_data->question_1a)) ? nl2br($form_data->question_1a) : '';
        $question_1b = (!is_null($form_data) && isset($form_data->question_1b)) ? nl2br($form_data->question_1b) : '';
        $question_1c = (!is_null($form_data) && isset($form_data->question_1c)) ? nl2br($form_data->question_1c) : '';
        $question_1d = (!is_null($form_data) && isset($form_data->question_1d)) ? nl2br($form_data->question_1d) : '';
        $question_1e = (!is_null($form_data) && isset($form_data->question_1e)) ? nl2br($form_data->question_1e) : '';
        $question_1f = (!is_null($form_data) && isset($form_data->question_1f)) ? nl2br($form_data->question_1f) : '';
        $question_1g = (!is_null($form_data) && isset($form_data->question_1g)) ? nl2br($form_data->question_1g) : '';

        $question_2a = (!is_null($form_data) && isset($form_data->question_2a)) ? nl2br($form_data->question_2a) : '';
        $question_2b = (!is_null($form_data) && isset($form_data->question_2b)) ? nl2br($form_data->question_2b) : '';
        $question_2c = (!is_null($form_data) && isset($form_data->question_2c)) ? nl2br($form_data->question_2c) : '';
        $question_2d = (!is_null($form_data) && isset($form_data->question_2d)) ? nl2br($form_data->question_2d) : '';
        $question_2e = (!is_null($form_data) && isset($form_data->question_2e)) ? nl2br($form_data->question_2e) : '';
        $question_2f = (!is_null($form_data) && isset($form_data->question_2f)) ? nl2br($form_data->question_2f) : '';
        $question_2g = (!is_null($form_data) && isset($form_data->question_2g)) ? nl2br($form_data->question_2g) : '';

        echo <<<HTML
<div style="text-align: center;">
<h2><strong>Learner Pre IAG Form</strong></h2>
</div>
<p><br></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr>
        <th style="color: #000; background-color: #d2d6de !important">Learner Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td>
        <th style="color: #000; background-color: #d2d6de !important">Tutor Name:</th><td>$tutor_name</td>
    </tr>
</table>
</div>

<br>
HTML;

        if ($funding_year >= 2023) {
            echo <<<HTML

<div style="text-align: left;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr>
        <td colspan="2">
            <p><h3>1. Application Information.</h3></p>
            <p><h4>APPRENTICESHIP INFORMATION: Please provide details of the apprenticeship position and/or course which has been applied for:</h4></p>
        </th>
    </tr>
    <tr>
        <td style="width: 50%;"><strong>1a. Current job role</strong></td>
        <td style="color: blue;">$question_1a</td>
    </tr>
    <tr>
        <td><strong>1b. How long have you been employed in this job role?</strong></td>
        <td style="color: blue;">$question_1b</td>
    </tr>
    <tr>
        <td><strong>1c. How long have you been employed in this sector?</strong></td>
        <td style="color: blue;">$question_1c</td>
    </tr>
    <tr>
        <td><strong>1d. Please state any previous job roles relating to this sector/apprenticeship.</strong></td>
        <td style="color: blue;">$question_1d</td>
    </tr>
    <tr>
        <td><strong>1e. Which apprenticeship and level are you interested in?</strong></td>
        <td style="color: blue;">$question_1e</td>
    </tr>
    <tr>
        <td><strong>1f. Please state any prior accredited learning that is relevant to your chosen apprenticeship?</strong></td>
        <td style="color: blue;">$question_1f</td>
    </tr>
    <tr>
        <td><strong>1g. Additional Notes if applicable (e.g. Employment History/academic history)</strong></td>
        <td style="color: blue;">$question_1g</td>
    </tr>
</table>
</div>

<div style="text-align: left;">
<table border="1" style="width: 100%;" cellpadding="6">
    
    <tr>
        <td colspan="2">
            <p><h3>2. Expectations and Previous Experience.</h3></p>
            <p><h4>Please discuss the requirements and expectations of the apprentice along with identifying previous learning and work experience recording the details below:</h4></p>
        </th>
    </tr>
    <tr>
        <td style="width: 50%;"><strong>2a. What information advice and guidance have you received to help inform you of your options so far, and do you need any more?</strong></td>
        <td style="color: blue;">$question_2a</td>
    </tr>
    <tr>
        <td><strong>2b. What appeals to you about undertaking this apprenticeship vs another form of structured education or training at this time?</strong></td>
        <td style="color: blue;">$question_2b</td>
    </tr>
    <tr>
        <td><strong>2c. How will completion of this apprenticeship enable you to achieve your future goals and develop your career?</strong></td>
        <td style="color: blue;">$question_2c</td>
    </tr>
    <tr>
        <td><strong>2d. What transferable skills (e.g. presentation, time management, communication) do you wish to develop?</strong></td>
        <td style="color: blue;">$question_2d</td>
    </tr>
    <tr>
        <td><strong>2e. What hobbies and/or interests do you have? Do any of them involve the use of transferable skills?</strong></td>
        <td style="color: blue;">$question_2e</td>
    </tr>
    <tr>
        <td><strong>2f. Do you have the support of your direct line manager? (Y/N)</strong></td>
        <td style="color: blue;">$question_2f</td>
    </tr>
    <tr>
        <td><strong>2g. Has your employer confirmed that you will receive protected off the job training time during your normal working hours?</strong></td>
        <td style="color: blue;">$question_2g</td>
    </tr>
</table>
</div>

HTML;
        } else {
            echo <<<HTML

<div style="text-align: left;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr>
        <td colspan="2">
            <p><h3>1. Application Information.</h3></p>
            <p><h4>APPRENTICESHIP INFORMATION: Please provide details of the apprenticeship position and/or course which has been applied for:</h4></p>
        </th>
    </tr>
    <tr>
        <td style="width: 50%;"><strong>1a. Current job role and how long employed in this job role to date?</strong></td>
        <td style="color: blue;">$question_1a</td>
    </tr>
    <tr>
        <td><strong>1b. How long employed in this sector and in which job roles?</strong></td>
        <td style="color: blue;">$question_1b</td>
    </tr>
    <tr>
        <td><strong>1c. Apprenticeship Programme of main interest and alternative options considered</strong></td>
        <td style="color: blue;">$question_1c</td>
    </tr>
    <tr>
        <td><strong>1d. Have you completed any prior accredited learning that is relevant in the subject field?</strong></td>
        <td style="color: blue;">$question_1d</td>
    </tr>
</table>
</div>

<div style="text-align: left;">
<table border="1" style="width: 100%;" cellpadding="6">
    
    <tr>
        <td colspan="2">
            <p><h3>2. Expectations and Previous Experience.</h3></p>
            <p><h4>Please discuss the requirements and expectations of the apprentice along with identifying previous learning and work experience recording the details below:</h4></p>
        </th>
    </tr>
    <tr>
        <td style="width: 50%;"><strong>2a. What information advice and guidance have you received to help inform you of your options so far, and do you need any more?</strong></td>
        <td style="color: blue;">$question_2a</td>
    </tr>
    <tr>
        <td><strong>2b. What appeals to you about undertaking this apprenticeship vs another form of structured education or training at this time?</strong></td>
        <td style="color: blue;">$question_2b</td>
    </tr>
    <tr>
        <td><strong>2c. How will completion of this apprenticeship enable you to achieve your future goals and develop your career? What transferable skills (e.g. presentation, time management, communication) do you wish to develop?</strong></td>
        <td style="color: blue;">$question_2c</td>
    </tr>
    <tr>
        <td><strong>2d. What hobbies and/or interests do you have? Do any of them involve the use of transferable skills?</strong></td>
        <td style="color: blue;">$question_2d</td>
    </tr>
    <tr>
        <td><strong>2e. Do you have the support of your direct line manager and has it been confirmed to you that your expectation of receiving protected off the job training time will be met? (Y/N)</strong></td>
        <td style="color: blue;">$question_2e</td>
    </tr>
</table>
</div>

HTML;
        }

        $learner_sign_date = isset($assessment->learner_sign_date) ? Date::toShort($assessment->learner_sign_date) : '';
        $provider_sign_date = isset($assessment->provider_sign_date) ? Date::toShort($assessment->provider_sign_date) : '';

        echo <<<HTML
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="4" class="bg-blue">Signatures</th></tr>
    <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
    <tr>
        <td>Apprentice</td>
        <td>{$ob_learner->firstnames} {$ob_learner->surname}</td>
        <td><img src="$learner_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
        <td>{$learner_sign_date}</td>
    </tr>
    <tr>
        <td>Provider/Assessor/Tutor</td>
        <td>{$assessment->provider_sign_name}</td>
        <td><img src="$provider_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
        <td>{$provider_sign_date}</td>
    </tr>
</table>
</div>
HTML;

        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();

        $mpdf->WriteHTML($html);

        // $mpdf->Output('IAG File', 'I');

        $mpdf->Output($iag_file, 'F');
    }

    public static function toPdfNonApp(PDO $link, TrainingRecord $tr, $assessment, $iag_file, $learner_signature_file, $provider_signature_file)
    {
        $ob_learner = $tr->getObLearnerRecord($link);

        $funding_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.lookup_submission_dates WHERE '{$tr->practical_period_start_date}' BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1");
        if ($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && in_array($tr->id, OnboardingHelper::UlnsToSkip($link))) {
            $funding_year = '';
        }

        //include_once("./MPDF57/mpdf.php");
        require dirname(__DIR__, 2) . '/vendor/autoload.php';

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
        if ($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

        $mpdf = new \Mpdf\Mpdf(['format' => 'Legal', 'default_font_size' => 10]);
        $mpdf->SetMargins(15, 15, 36);
        $mpdf->setAutoBottomMargin = 'stretch';

        $header = <<<HEADER
    <div>
        <table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
            <tr>
                <td width = "50%" align="left"></td>
                <td width = "50%" align="right"><img class="img-responsive" src="$logo" height="1.50cm" width="5cm"  /></td>
            </tr>
        </table>
    </div>

HEADER;

        $mpdf->SetHTMLHeader($header);
        $sunesis_stamp = md5('ghost' . date('d/m/Y') . $tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);
        $date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
    <div>
        <table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
            <tr>
                <td width = "30%" align="left" style="font-size: 10px">{$date}</td>
                <td width = "35%" align="left" style="font-size: 10px">Learner Pre IAG Form</td>
                <td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
            </tr>
        </table>
    </div>
HEREDOC;
        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        $form_data = is_null($assessment->form_data) ? null : json_decode($assessment->form_data);
        $tutor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->trainers}'");
        if ($tutor_name == '') {
            $tutor_name = $assessment->provider_sign_name;
        }
        $qs = [];
        for ($i = 1; $i <= 9; $i++) {
            $k = "question_$i";
            if (!is_null($form_data) && isset($form_data->$k)) {
                $qs[$k] = nl2br($form_data->$k);
            } else {
                $qs[$k] = '';
            }
        }

        echo <<<HTML
<div style="text-align: center;">
<h2><strong>Learner Pre IAG Form</strong></h2>
</div>
<p><br></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr>
        <th style="color: #000; background-color: #d2d6de !important">Learner Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td>
        <th style="color: #000; background-color: #d2d6de !important">Tutor Name:</th><td>$tutor_name</td>
    </tr>
</table>
</div>

<br>

<div style="text-align: left;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr>
        <th style="width: 60%;">1. Are you employed?</th>
        <td style="color: blue;">{$qs['question_1']}</td>
    </tr>
    <tr>
        <th style="width: 50%;">2. Current job role and details.</th>
        <td style="color: blue;">{$qs['question_2']}</td>
    </tr>
    <tr>
        <th style="width: 50%;">3. Programme of main interests and alternative options considered?</th>
        <td style="color: blue;">{$qs['question_3']}</td>
    </tr>
    <tr>
        <th style="width: 50%;">4. Have you completed any prior accredited learning that is relevant in the subject field?</th>
        <td style="color: blue;">{$qs['question_4']}</td>
    </tr>
    <tr>
        <th style="width: 50%;">5. Have you received Careers, information, advice and guidance? Do you need any further support and advise to ensure you have chosen the best option in line with your desired aspirations?</th>
        <td style="color: blue;">{$qs['question_5']}</td>
    </tr>
    <tr>
        <th style="width: 50%;">6. Why have you chosen this particular course  vs other options such as Apprenticeships etc?</th>
        <td style="color: blue;">{$qs['question_6']}</td>
    </tr>
    <tr>
        <th style="width: 50%;">7. How will completing this course enable you to achieve your future goals and develop In your desired career?</th>
        <td style="color: blue;">{$qs['question_7']}</td>
    </tr>
    <tr>
        <th style="width: 50%;">8. What hobbies and/or interests do you have relevant to your studies?</th>
        <td style="color: blue;">{$qs['question_8']}</td>
    </tr>
    <tr>
        <th style="width: 50%;">9. Can you confirm that you have applied/or will apply for an adult learner loan facility to fund this programme and you understand how the repayments will work?</th>
        <td style="color: blue;">{$qs['question_9']}</td>
    </tr>
</table>
</div>

HTML;

        $learner_sign_date = isset($assessment->learner_sign_date) ? Date::toShort($assessment->learner_sign_date) : '';
        $provider_sign_date = isset($assessment->provider_sign_date) ? Date::toShort($assessment->provider_sign_date) : '';

        echo <<<HTML
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="4" class="bg-blue">Signatures</th></tr>
    <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
    <tr>
        <td>Apprentice</td>
        <td>{$ob_learner->firstnames} {$ob_learner->surname}</td>
        <td><img src="$learner_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
        <td>{$learner_sign_date}</td>
    </tr>
    <tr>
        <td>Provider/Assessor/Tutor</td>
        <td>{$assessment->provider_sign_name}</td>
        <td><img src="$provider_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
        <td>{$provider_sign_date}</td>
    </tr>
</table>
</div>
HTML;

        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();

        $mpdf->WriteHTML($html);

        // $mpdf->Output('IAG File', 'I');

        $mpdf->Output($iag_file, 'F');
    }
}
