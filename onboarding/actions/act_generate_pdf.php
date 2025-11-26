<?php
class generate_pdf extends ActionController
{
    public $tr = null;
    public $ob_learner = null;
    public $skills_analysis = null;

    public function indexAction(PDO $link)
    {
        $skills_analysis_id = isset($_REQUEST['skills_analysis_id']) ? $_REQUEST['skills_analysis_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        if($tr_id == '')
        {
            throw new Exception("Missing querystring argument: tr_id");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            throw new Exception("Invalid tr_id");
        }

        $ob_learner = $tr->getObLearnerRecord($link);
        if(is_null($ob_learner))
        {
            throw new Exception("Invalid tr_id");
        }

        if($skills_analysis_id != '')
        {
            $skills_analysis = SkillsAnalysis::loadFromDatabaseById($link, $skills_analysis_id);
            if(is_null($skills_analysis))
            {
                throw new Exception("Invalid skills_analysis_id");
            }
        }
        else
        {
            $skills_analysis_id = DAO::getSingleValue($link, "SELECT id FROM ob_learner_skills_analysis WHERE tr_id = '{$tr->id}'");
            $skills_analysis = SkillsAnalysis::loadFromDatabaseById($link, $skills_analysis_id);
        }

        $this->tr = $tr;
        $this->ob_learner = $ob_learner;
        $this->skills_analysis = $skills_analysis;
    }

    public function employerScheduleAction(PDO $link)
    {
        $this->indexAction($link);
	$schedule_id = isset($_REQUEST['schedule_id']) ? $_REQUEST['schedule_id'] : '';

        $tr = $this->tr; /* @var $tr TrainingRecord */
        $ob_learner = $this->ob_learner; /* @var $ob_learner OnboardingLearner */

        $schedule = EmployerSchedule1::loadFromDatabase($link, $schedule_id);

        $schedule_directory = $tr->getDirectoryPath() . 'schedule1/';
        if(!is_dir($schedule_directory))
        {
            mkdir("$schedule_directory", 0777, true);
        }
        $schedule_file = $schedule_directory.EmployerSchedule1::SCH_PDF_NAME;
        if(is_file($schedule_file))
        {
            unlink($schedule_file);
            $schedule->generatePdf($link);
        }

        header("Content-type: application/pdf");
        header('Content-Disposition: attachment; filename="' . EmployerSchedule1::SCH_PDF_NAME . '"');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        readfile($schedule_file);
        exit;
    }

    public function employerApprenticeshipAgreementAction(PDO $link)
    {
        $this->indexAction($link);

        $tr = $this->tr; /* @var $tr TrainingRecord */

        $app_agreement_directory = $tr->getDirectoryPath() . 'onboarding/';
        if(!is_dir($app_agreement_directory))
        {
            mkdir("$app_agreement_directory", 0777, true);
        }
        $app_agreement_file = $app_agreement_directory.OnboardingHelper::APP_AGREEMENT_PDF_NAME;
        if(is_file($app_agreement_file))
        {
            unlink($app_agreement_file);
            $tr->generateEmployerAppAgreementPdf($link);
        }

        header("Content-type: application/pdf");
        header('Content-Disposition: attachment; filename="' . OnboardingHelper::APP_AGREEMENT_PDF_NAME . '"');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        readfile($app_agreement_file);
        exit;
    }

    public function employerAgreementAction(PDO $link)
    {

        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $agreement = EmployerAgreement::loadFromDatabase($link, $id);

        EmployerAgreement::generatePdf($link, $agreement);

    }

    public function skillsAssessmentAction(PDO $link)
    {

        $this->indexAction($link);

        $tr = $this->tr; /* @var $tr TrainingRecord */
        $sa = $this->skills_analysis; /* @var $sa SkillsAnalysis */

        $skills_analysis_directory = $tr->getDirectoryPath() . 'skills_analysis/';
        if(!is_dir($skills_analysis_directory))
        {
            mkdir("$skills_analysis_directory", 0777, true);
        }
        $sa_file = $skills_analysis_directory.OnboardingHelper::SKILLS_ANALYSIS_PDF_NAME;
        if(is_file($sa_file))
        {
            unlink($sa_file);
            //$sa->generatePdf($link);
	    $tr->generateSkillsAssessmentPdf($link);	
        }
        

        header("Content-type: application/pdf");
        header('Content-Disposition: attachment; filename="' . OnboardingHelper::SKILLS_ANALYSIS_PDF_NAME . '"');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        readfile($sa_file);
        exit;

    }

    public function commitmentStatementAction(PDO $link)
    {

        $this->indexAction($link);

        $tr = $this->tr; /* @var $tr TrainingRecord */

        $onboarding_directory = $tr->getDirectoryPath() . 'onboarding/';
        if(!is_dir($onboarding_directory))
        {
            mkdir("$onboarding_directory", 0777, true);
        }
        $tr->generate_pdfs = 'CS';
        $tr->save($link);
        PdfHelper::commitmentStatementPdf($link, $tr);
        http_redirect('do.php?_action=read_training&id='.$tr->id);
        // $c_file = $onboarding_directory.OnboardingHelper::COMMITMENT_PDF_NAME;
        // if(is_file($c_file))
        // {
        //     unlink($c_file);
        //     $tr->generateCommitmentStatementPdf($link);
        // }

        // header("Content-type: application/pdf");
        // header('Content-Disposition: attachment; filename="' . OnboardingHelper::COMMITMENT_PDF_NAME . '"');
        // if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        // {
        //     header('Pragma: public');
        //     header('Cache-Control: max-age=0');
        // }
        // readfile($c_file);
        exit;

    }

    public function firstLearningActivityAction(PDO $link)
    {

        $this->indexAction($link);

        $tr = $this->tr; /* @var $tr TrainingRecord */

        $onboarding_directory = $tr->getDirectoryPath() . 'onboarding/';
        if(!is_dir($onboarding_directory))
        {
            mkdir("$onboarding_directory", 0777, true);
        }
        $fla_file = $onboarding_directory.OnboardingHelper::FIRST_LEARNING_ACTIVITY;
        if(!is_file($fla_file))
        {
            $tr->generateCommitmentStatementPdf($link);
        }

        header("Content-type: application/pdf");
        header('Content-Disposition: attachment; filename="' . OnboardingHelper::FIRST_LEARNING_ACTIVITY . '"');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        readfile($fla_file);
        exit;

    }

    public function learningAgreementAction(PDO $link)
    {

        $this->indexAction($link);

        $tr = $this->tr; /* @var $tr TrainingRecord */

        $onboarding_directory = $tr->getDirectoryPath() . 'onboarding/';
        if(!is_dir($onboarding_directory))
        {
            mkdir("$onboarding_directory", 0777, true);
        }
        $a_file = $onboarding_directory.OnboardingHelper::LEARNING_AGREEMENT;
        if(!is_file($a_file))
        {
            $tr->generateLearningAgreementPdf($link);
        }

        header("Content-type: application/pdf");
        header('Content-Disposition: attachment; filename="' . OnboardingHelper::LEARNING_AGREEMENT . '"');
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        {
            header('Pragma: public');
            header('Cache-Control: max-age=0');
        }
        readfile($a_file);
        exit;

    }

    public function employerHsFormAction(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if($id == '')
        {
            return;
        }        

        $hs = EmployerHealthAndSafety::loadFromDatabaseById($link, $id);
        if(is_null($hs))
        {
            return;
        }

        $hs_form = $hs->getHsForm($link);
        $detail = json_decode($hs_form->detail);

        $employer = Employer::loadFromDatabase($link, $hs->employer_id);
        $employer_rep_contact = DAO::getObject($link, "SELECT * FROM organisation_contacts WHERE contact_id = '{$hs->employer_rep}'");
        $hs_contact = DAO::getObject($link, "SELECT * FROM organisation_contacts WHERE contact_id = '{$hs->hs_contact_person}'");
        $mainLocation = Location::loadFromDatabase($link, $hs->location_id);

        $target_directory = Repository::getRoot() . "/employers/{$employer->id}/hs_forms/{$hs->id}/";
        if(!is_dir($target_directory))
        {
            mkdir("$target_directory", 0777, true);
        }

        $employer_signature_file = $target_directory . 'employer_sign_image.png';
        if(!is_file($employer_signature_file))
        {
            PdfHelper::generateSignImages($hs->employer_sign, $target_directory, $employer_signature_file);
        }
        $provider_signature_file = $target_directory . 'provider_sign_image.png';
        if(!is_file($provider_signature_file))
        {
            PdfHelper::generateSignImages($hs->provider_sign, $target_directory, $provider_signature_file);
        }
        // $verifier_signature_file = $target_directory . 'verifier_sign_image.png';
        // if(!is_file($verifier_signature_file))
        // {
        //     PdfHelper::generateSignImages($hs->verifier_sign, $target_directory, $verifier_signature_file);
        // }        

        $employer_sign_date = isset($hs->employer_sign_date) ? Date::toShort($hs->employer_sign_date) : '';
        $provider_sign_date = isset($hs->provider_sign_date) ? Date::toShort($hs->provider_sign_date) : '';
        //$verifier_sign_date = isset($hs->verifier_sign_date) ? Date::toShort($hs->verifier_sign_date) : '';

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
        $sunesis_stamp = md5('ghost'.date('d/m/Y').$employer->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);
        $date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
<div>
    <table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
        <tr>
            <td width = "30%" align="left" style="font-size: 10px">{$date}</td>
            <td width = "35%" align="left" style="font-size: 10px">Work Placement Health and Safety Checklist</td>
            <td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
        </tr>
    </table>
</div>
HEREDOC;
        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        $employer_rep_contact_name = $employer_rep_contact->contact_name ?? '';
        $employer_rep_contact_telephone = $employer_rep_contact->contact_telephone ?? '';
        $hs_contact_name = $hs_contact->contact_name ?? '';
        $hs_contact_email = $hs_contact->contact_email ?? '';

        echo <<<HTML
<h2 style="text-align: center;">Work Placement Health and Safety Checklist</h2>
<table  border="1" style="width: 100%;" cellpadding="6">
    <tr>
        <th>Company Name</th>
        <td>{$employer->legal_name}</td>
        <td>
            <table border="1" style="width: 100%;" cellpadding="6">
                <tr><th>Contact Person</th></tr>
                <tr><td>{$employer_rep_contact_name}</td></tr>
                <tr><td>{$employer_rep_contact_telephone}</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <th>Total number of employees</th>
        <td>{$employer->site_employees}</td>
        <td>
            <table border="1" style="width: 100%;" cellpadding="6">
                <tr><th>Health & Safety Contact</th></tr>
                <tr><td>{$hs_contact_name}</td></tr>
                <tr><td>{$hs_contact_email}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th>Address & Postcode</th>
        <td>
            {$mainLocation->address_line_1}<br>
            {$mainLocation->address_line_2}<br>
            {$mainLocation->address_line_3}<br>
            {$mainLocation->address_line_4}<br>
            {$mainLocation->postcode}<br>
        </td>
        <td>
            <table border="1" style="width: 100%;" cellpadding="6">
                <tr>
                    <th>Telephone</th>
                    <td>{$mainLocation->telephone}</td>
                </tr>
                <tr>
                    <th>Fax</th>
                    <td>{$mainLocation->fax}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th>Email Address</th>
        <td colspan="2"></td>
    </tr>
    <tr>
        <th>Website Address</th>
        <td colspan="2">{$employer->url}</td>
    </tr>
    <tr>
        <th>ERN</th>
        <td colspan="2">{$employer->edrs}</td>
    </tr>
</table>
<br>
<table  border="1" style="width: 100%;" cellpadding="6">
    <tr style="background-color: lightgray"><th>Nature of Business</th></tr>
    <tr><td>{$detail->nature_of_business}</td></tr>
</table>
<br>
<h4>Health and Safety Standards</h4>

HTML;
    
    for ($i = 1; $i <= 11; $i++) 
    {
        echo '<table  border="1" style="width: 100%;" cellpadding="6">';
        echo '<thead><tr style="background-color: lightgray">';
        echo '<th>' . $i . '</th>';
        echo '<th style="width: 50%;">' . DAO::getSingleValue($link, "SELECT section_title FROM lookup_employer_health_safety_questions WHERE serial_n = '{$i}' LIMIT 1") . '</th>';
        echo '<th align="center">Yes</th>';
        echo '<th align="center">No</th>';
        echo '<th>Evidence and Comments</th>';
        echo '</tr></thead><tbody>';
        $records = DAO::getResultset($link, "SELECT * FROM lookup_employer_health_safety_questions WHERE serial_n = '{$i}'", DAO::FETCH_ASSOC);
        foreach ($records as $row) { 
            $q = "q{$row['id']}";
            $ec = "evidence_and_comments{$row['id']}";
            echo '<tr>';
            echo '<th>' . $row['serial_c'] . '</th>';
            echo '<td>' . $row['question'] . '</td>';
            echo (isset($detail->$q) && $detail->$q == 'Yes') ?
                '<td align="center"><img src="images/check.jpg" height="25px" /></td>' :
                '<td align="center"></td>';
            echo (isset($detail->$q) && $detail->$q == 'No') ?
                '<td align="center"><img src="images/check.jpg" height="25px" /></td>' :
                '<td align="center"></td>';
            if($row['serial_n'] == '10' && $row['serial_c'] == 'A')
            {
                echo '<td>';
                echo '<span>Insurer\'s name: </span> &nbsp; ' . $hs->el_insurer . '<hr>';
                echo '<span>Policy number: </span> &nbsp; ' . $hs->el_insurance . '<hr>';
                echo '<span>Expiry date: </span>  &nbsp; ' . Date::toShort($hs->el_date);
                echo '</td>';
            }
            else
            {
                echo '<td>' . nl2br($detail->$ec ?? '') . '</td>';
            }

            echo '</tr>';
        }
        echo '</tbody></table>';
    }

    echo '<h4>Health and Safety Action Plan</h4>';
    echo '<table  border="1" style="width: 100%;" cellpadding="6">';
    echo '<thead><tr style="background-color: lightgray">';
    echo '<th>Ref</th><th>Action Required</th><th>By Who</th><th>Review Date</th><th>Completed</th></tr>';
    for ($i = 1; $i <= 3; $i++) 
    {
        $ref = 'ap_ref' . $i;
        $action = 'ap_action' . $i;
        $by = 'ap_by' . $i;
        $rd = 'ap_rd' . $i;
        $comp = 'ap_c' . $i;
        echo '<tr>';
        echo '<td>';
        echo isset($detail->$ref) ? $detail->$ref : '';
        echo '</td>';
        echo '<td>';
        echo isset($detail->$action) ? nl2br($detail->$action) : '';
        echo '</td>';
        echo '<td>';
        echo isset($detail->$by) ? $detail->$by : '';
        echo '</td>';
        echo isset($detail->$rd) ? '<td>' . Date::toShort($detail->$rd) . '</td>' : '<td></td>';
        echo '<td align="center">'; 
        echo isset($detail->comp) ? '<img src="images/check.jpg" height="25px" />' : '';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';

    echo '<h4>Health and Safety Assessment Outcome</h4>';
    echo '<table  border="1" style="width: 100%;" cellpadding="6">';
    echo '<tr>';
    echo '<th>Recommendation</th>';
    echo '<td>';
    if(isset($detail->recommendation))
    {
        echo $detail->recommendation == '1' ? 'Suitable' : '';    
        echo $detail->recommendation == '2' ? 'Suitable with action plan' : '';    
        echo $detail->recommendation == '3' ? 'Unsuitable' : '';    
    }
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th>Risk Category</th>';
    echo '<td>';
    if(isset($detail->risk_category))
    {
        echo $detail->risk_category;    
    }
    echo '</td>';
    echo '</tr>';
    echo '</table>';
    
    echo '<h4>Health and Safety Assessment Type</h4>';
    echo '<table  border="1" style="width: 100%;" cellpadding="6">';
    echo '<tr><th>Initial Assessment</th><th>Re-Assessment</th><th>Other (please specify)</th></tr>';
    echo '<tr>';
    echo $hs->assessment_type == 1 ? '<td align="center"><img src="images/check.jpg" height="25px" /></td>' : '<td></td>';
    echo $hs->assessment_type == 2 ? '<td align="center"><img src="images/check.jpg" height="25px" /></td>' : '<td></td>';
    echo $hs->assessment_type == 3 ? '<td align="center"><img src="images/check.jpg" height="25px" /><br>' . nl2br($hs->assessment_type_other) . '</td>' : '<td></td>';
    echo '</tr>';
    echo '</table>';

    echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="4" class="bg-blue">Signatures</th></tr>
        <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
        <tr>
            <td>Employer</td>
            <td>{$hs->employer_sign_name}</td>
            <td><img src="$employer_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$employer_sign_date}</td>
        </tr>
        <tr>
            <td>Provider</td>
            <td>{$hs->provider_sign_name}</td>
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
        // $mpdf->Output('HS Form', 'I');

        $mpdf->Output('HealthandSafetyChecklist.pdf', 'D');
    }
}