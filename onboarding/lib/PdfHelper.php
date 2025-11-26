<?php
class PdfHelper
{
    public static function generateSignImages($sign_string, $directory, $sign_image_file)
    {
        if ($sign_string == '' || $directory == '' || $sign_image_file == '') {
            return;
        }

        $signature_parts = explode('&', $sign_string);
        if (isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2])) {
            if (substr($signature_parts[0], 0, 5) == 'title') {
                $title = explode('=', $signature_parts[0]);
                $font = explode('=', $signature_parts[1]);
                $size = explode('=', $signature_parts[2]);
            } elseif (substr($signature_parts[1], 0, 5) == 'title') {
                $title = explode('=', $signature_parts[1]);
                $font = explode('=', $signature_parts[2]);
                $size = explode('=', $signature_parts[3]);
            } else {
                return;
            }
            $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
            imagepng($signature, $sign_image_file, 0);
        }
    }

    public static function preIagFormPdf(PDO $link, TrainingRecord $tr)
    {
        $assessment = DAO::getObject($link, "SELECT * FROM ob_learner_pre_iag_form WHERE tr_id = '{$tr->id}'");
        if (!isset($assessment->tr_id)) {
            return;
        }

        if ($assessment->learner_sign == '' || $assessment->provider_sign == '') {
            return;
        }

        $iag_dir = $tr->getDirectoryPath() . 'iag/';
        if (!is_dir($iag_dir)) {
            mkdir("$iag_dir", 0777, true);
        }

        $learner_signature_file = $iag_dir . 'learner_sign_image.png';
        if (!is_file($learner_signature_file)) {
            self::generateSignImages($assessment->learner_sign, $iag_dir, $learner_signature_file);
        }

        $provider_signature_file = $iag_dir . 'provider_sign_image.png';
        if (!is_file($provider_signature_file)) {
            self::generateSignImages($assessment->provider_sign, $iag_dir, $provider_signature_file);
        }

        $iag_file = $iag_dir . OnboardingHelper::PRE_IAG_PDF_NAME;

        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if (!is_file($iag_file) || in_array("IAG", $trGeneratePdfs)) {
            include_once("OnboardingDocuments/PreIagForm.php");

            $iag_file = in_array("IAG", $trGeneratePdfs) ? $iag_dir . 'Pre IAG Form_' . uniqid() . '.pdf' : $iag_file;

            PreIagForm::toPdf($link, $tr, $assessment, $iag_file, $learner_signature_file, $provider_signature_file);
        }
    }

    public static function writingAssessmentPdf(PDO $link, TrainingRecord $tr)
    {
        $ob_learner = $tr->getObLearnerRecord($link);

        $assessment = DAO::getObject($link, "SELECT * FROM ob_learner_writing_assessment WHERE tr_id = '{$tr->id}'");
        if (!isset($assessment->tr_id)) {
            return;
        }

        if ($assessment->learner_sign == '' || $assessment->provider_sign == '') {
            return;
        }

        $writing_assessment_dir = $tr->getDirectoryPath() . 'writing_assessment/';
        if (!is_dir($writing_assessment_dir)) {
            mkdir("$writing_assessment_dir", 0777, true);
        }

        $learner_signature_file = $writing_assessment_dir . 'learner_sign_image.png';
        if (!is_file($learner_signature_file)) {
            self::generateSignImages($assessment->learner_sign, $writing_assessment_dir, $learner_signature_file);
        }

        $provider_signature_file = $writing_assessment_dir . 'provider_sign_image.png';
        if (!is_file($provider_signature_file)) {
            self::generateSignImages($assessment->provider_sign, $writing_assessment_dir, $provider_signature_file);
        }

        $writing_assessment_file = $writing_assessment_dir . OnboardingHelper::WRITING_ASSESSMENT_PDF_NAME;

        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if (!is_file($writing_assessment_file) || in_array("LWA", $trGeneratePdfs)) {
            $trGeneratePdfs = $tr->generate_pdfs != '' ? $tr->generate_pdfs : '';
            $writing_assessment_file = in_array("LWA", explode(",", $trGeneratePdfs)) ? $writing_assessment_dir . 'Learner Writing Assessment_' . uniqid() . '.pdf' : $writing_assessment_file;

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
					<td width = "35%" align="left" style="font-size: 10px">Learner Writing Assessment</td>
					<td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;
            //Beginning Buffer to save PHP variables and HTML tags
            ob_start();

            $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
            $framework = Framework::loadFromDatabase($link, $tr->framework_id);
            $learner_comments = nl2br($assessment->learner_comments);
            $marking = $assessment->marking != '' ? json_decode($assessment->marking) : new stdClass();
            $answers = [];
            $total_score = 0;
            for ($i = 1; $i <= 8; $i++) {
                $s = "s{$i}";
                $answers[$i] = isset($marking->$s) ? $marking->$s : '';
                $total_score += intval($answers[$i]);
            }

            $writing_assessment_text = str_replace(
                '500 words',
                $framework->writing_assessment_chars . ' words',
                (string) $framework->writing_assessment_text
            );

            echo <<<HTML
<div style="text-align: center;">
    <h2><strong>Learner Writing Assessment</strong></h2>
</div>
<p><br></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">Employer:</th><td>$employer->legal_name</td></tr>
        <tr><th style="color: #000; background-color: #d2d6de !important">Learner Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td></tr>
        <tr><th style="color: #000; background-color: #d2d6de !important">Programme:</th><td>$framework->title</td></tr>
    </table>
</div>
<p>
    <h3>Writing Assessment Task (Please type this to demonstrate word processing skills)</h3>
</p>
<div style="text-align: left;">
    $writing_assessment_text
</div>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><td style="color: #000; background-color: #d2d6de !important"><strong>Details</strong></td></tr>
    </table>
</div>
<div>
    <p>$learner_comments</p>
</div>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><td style="color: #000; background-color: #d2d6de !important"><strong>Marking</strong></td></tr>
    </table>
</div>
<table border="1" style="width: 100%;" cellpadding="6">
    <tr style="color: #000; background-color: #d2d6de !important">
        <th><p class="text-center">Content and Layout</p></th><th>Marks Awarded</th>
    </tr>
    <tr>
        <td>
            <table border="1" style="width: 100%;" cellpadding="6">
                <tr>
                    <td>
                        Has included all of the information requested (where they work, and their job role; what inspires them most about working with children in this sector; what activities they most enjoy doing with children; what inspires them to work in their current setting; how their apprenticeship will impact their career prospects; how their apprenticeship will impact their personal development; a typical working day.)
                    </td>
                    <td style="width: 15%;">4</td>
                </tr>
                <tr><td>Has included at least five of the points listed. </td><td>3</td></tr>
                <tr><td>Has included at least three of the points listed. </td><td>2</td></tr>
                <tr><td>Has included less than three of the points listed. </td><td>1</td></tr>
            </table>
        </td>
        <th style="width: 15%;">{$answers[1]}</th>
    </tr>
    <tr>
        <td>
            <table border="1" style="width: 100%;" cellpadding="6">
                <tr>
                    <td>
                    Text contains both simple and complex sentences, with a wide range of connectives used (and, but, so, however, therefore, although, whereas, because etc.).
                    </td>
                    <td style="width: 15%;">2</td>
                </tr>
                <tr><td>Text contains both simple and complex sentences, with a limited range of connectives used (and, but, so)</td><td>1</td></tr>
            </table>
        </td>
        <th style="width: 15%;">{$answers[2]}</th>
    </tr>
    <tr>
        <td>
            <table border="1" style="width: 100%;" cellpadding="6">
                <tr><td>Writing is accurately laid out into paragraphs. </td><td style="width: 15%;">2</td></tr>
                <tr><td>There has been an attempt at paragraphing, although not always successfully. </td><td>1</td></tr>
                <tr><td>No paragraphs. </td><td>0</td></tr>
            </table>
        </td>
        <th style="width: 15%;">{$answers[3]}</th>
    </tr>
    <tr>
        <td>
            <table border="1" style="width: 100%;" cellpadding="6">
                <tr><td>The text is logical and well-organised.  Information, ideas and opinions are consistently communicated clearly and effectively.</td><td style="width: 15%;">2</td></tr>
                <tr><td>There has been some attempt to organise the text.  Information, ideas and opinions are not always communicated clearly or effectively.</td><td>1</td></tr>
                <tr><td>The text is poorly structured.  Information and ideas are unclear.</td><td>0</td></tr>
            </table>
        </td>
        <th style="width: 15%;">{$answers[4]}</th>
    </tr>
    <tr>
        <td>
            <table border="1" style="width: 100%;" cellpadding="6">
HTML;

            $writing_assessment_chars = $framework->writing_assessment_chars;
            for ($i = 4; $i >= 1; $i--) {
                echo '<tr><td>The text is approximately ' . $writing_assessment_chars . ' words.</td><td style="width: 15%;">' . $i . '</td></tr>';
                $writing_assessment_chars -= 50;
            }
            echo <<<HTML
            </table>
        </td>
        <th style="width: 15%;">{$answers[5]}</th>
    </tr>
    <tr  style="color: #000; background-color: #d2d6de !important">
        <th><p class="text-center">Spelling</p></th><th>Marks Awarded</th>
    </tr>
    <tr>
        <td>
            <table border="1" style="width: 100%;" cellpadding="6">
                <tr><td>Spelling is consistently accurate, including ambitious and /or irregular words where used.</td><td style="width: 15%;">3</td></tr>
                <tr><td>Spelling is accurate most of the time, with some accurate spelling of more complex or irregular words. </td><td>2</td></tr>
                <tr><td>Limited accuracy: some accurate spelling of simple or regular words.</td><td>1</td></tr>
                <tr><td>Spelling errors significantly impair meaning, or insufficient evidence to judge ability.</td><td>0</td></tr>
            </table>
        </td>
        <th style="width: 15%;">{$answers[6]}</th>
    </tr>
    <tr style="color: #000; background-color: #d2d6de !important">
        <th><p class="text-center">Punctuation</p></th><th>Marks Awarded</th>
    </tr>
    <tr>
        <td>
            <table border="1" style="width: 100%;" cellpadding="6">
                <tr><td>A range of punctuation (e.g. colons, commas, inverted commas, apostrophes, quotation marks) is used consistently accurately to mark the structure of sentences and give clarity and emphasis.</td><td style="width: 15%;">3</td></tr>
                <tr><td>Some accuracy / range in punctuation: some sentences are correctly demarcated, with some use of other punctuation, e.g. commas to mark phrases or clauses or within lists.</td><td>2</td></tr>
                <tr><td>Limited accuracy / range in punctuation.</td><td>1</td></tr>
                <tr><td>Punctuation errors significantly impair meaning, or insufficient evidence to judge ability.</td><td>0</td></tr>
            </table>
        </td>
        <th style="width: 15%;">{$answers[7]}</th>
    </tr>
    <tr style="color: #000; background-color: #d2d6de !important">
        <th><p class="text-center">Grammar</p></th><th>Marks Awarded</th>
    </tr>
    <tr>
        <td>
            <table border="1" style="width: 100%;" cellpadding="6">
                <tr><td>Grammar is consistently accurate: e.g. tenses and verb forms such as modals (would have been) are controlled; definite and indefinite articles are used accurately when needed.</td><td style="width: 15%;">3</td></tr>
                <tr><td>Some accuracy in grammar: e.g. some sentences are grammatically sound; there is some variation in verb forms; tense choice is appropriate some of the time; definite and indefinite articles are often incorrectly used or omitted when needed.</td><td>2</td></tr>
                <tr><td>Limited accuracy in grammar: e.g. errors in verb forms and tenses are frequent and tense choice is often incorrect; definite and indefinite articles are frequently inaccurate or omitted when needed.</td><td>1</td></tr>
                <tr><td>Grammar errors significantly impair meaning, or insufficient evidence to judge ability.</td><td>0</td></tr>
            </table>
        </td>
        <th style="width: 15%;">{$answers[8]}</th>
    </tr>
    <tr style="color: #000; background-color: #d2d6de !important">
        <th>
            <p class="text-right">Total Marks</p>
        </th>
        <th class="lblTotal text-bold lead text-center">{$total_score}</th>
    </tr>
</table>
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
            <td>Provider/Assessor</td>
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

            // $mpdf->Output('Learner Writing Assessment File', 'I');

            $mpdf->Output($writing_assessment_file, 'F');
        }
    }

    public static function apprenticeshipAgreementPdf(PDO $link, TrainingRecord $tr)
    {
        if ($tr->learner_sign == '' || $tr->emp_sign == '') {
            return;
        }

        $onboarding_dir = $tr->getDirectoryPath() . 'onboarding/';
        if (!is_dir($onboarding_dir)) {
            mkdir("$onboarding_dir", 0777, true);
        }

        $learner_signature_file = $onboarding_dir . 'learner_sign_image.png';
        if (!is_file($learner_signature_file)) {
            self::generateSignImages($tr->learner_sign, $onboarding_dir, $learner_signature_file);
        }

        $employer_signature_file = $onboarding_dir . 'emp_sign_image.png';
        if (!is_file($employer_signature_file)) {
            self::generateSignImages($tr->emp_sign, $onboarding_dir, $employer_signature_file);
        }

        $app_agreement_file = $onboarding_dir . OnboardingHelper::APP_AGREEMENT_PDF_NAME;

        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if (!is_file($app_agreement_file) || in_array("AA", $trGeneratePdfs)) {
            include_once("OnboardingDocuments/ApprenticeshipAgreement.php");

            $app_agreement_file = in_array("AA", $trGeneratePdfs) ? $onboarding_dir . 'Apprenticeship Agreement_' . uniqid() . '.pdf' : $app_agreement_file;

            ApprenticeshipAgreement::toPdf($link, $tr, $app_agreement_file, $learner_signature_file, $employer_signature_file);
        }
    }

    public static function skillsScanAgreementPdf(PDO $link, TrainingRecord $tr)
    {
        $ob_learner = $tr->getObLearnerRecord($link);

        $skills_analysis = $tr->getSkillsAnalysis($link);

        if (!isset($skills_analysis->tr_id)) {
            return;
        }

        if ($skills_analysis->learner_sign == '' || $skills_analysis->provider_sign == '') {
            return;
        }

        $skills_analysis_directory = $tr->getDirectoryPath() . 'skills_analysis/';
        if (!is_dir($skills_analysis_directory)) {
            mkdir("$skills_analysis_directory", 0777, true);
        }

        $learner_signature_file = $skills_analysis_directory . 'learner_sign_image.png';
        if (!is_file($learner_signature_file)) {
            self::generateSignImages($skills_analysis->learner_sign, $skills_analysis_directory, $learner_signature_file);
        }

        $employer_signature_file = $skills_analysis_directory . 'employer_sign_image.png';
        if (!is_file($employer_signature_file) && $skills_analysis->employer_sign != '') {
            self::generateSignImages($skills_analysis->employer_sign, $skills_analysis_directory, $employer_signature_file);
        }

        $provider_signature_file = $skills_analysis_directory . 'provider_sign_image.png';
        if (!is_file($provider_signature_file)) {
            self::generateSignImages($skills_analysis->provider_sign, $skills_analysis_directory, $provider_signature_file);
        }

        $sa_file = $skills_analysis_directory . OnboardingHelper::SKILLS_ANALYSIS_PDF_NAME;

        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if (!is_file($sa_file) || in_array("SS", $trGeneratePdfs)) {
            $sa_file = in_array("SS", $trGeneratePdfs) ? $skills_analysis_directory . 'Skills Scan Result_' . uniqid() . '.pdf' : $sa_file;

            $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
            if ($logo == '')
                $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

            $employer = Employer::loadFromDatabase($link, $tr->employer_id);
            if (in_array(DB_NAME, ["am_superdrug", "am_sd_demo"])) {
                $logo = $employer->logoPath();
            }

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
					<td width = "35%" align="left" style="font-size: 10px">Skills Analysis</td>
					<td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;
            //Beginning Buffer to save PHP variables and HTML tags
            ob_start();

            $framework = Framework::loadFromDatabase($link, $tr->framework_id);
            $ob_learner = $tr->getObLearnerRecord($link);
            $skills_analysis = $tr->getSkillsAnalysis($link);
            //$employer = Employer::loadFromDatabase($link, $tr->employer_id);
            $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
            $epa_name = $tr->getEpaOrgName($link);

            $sub_legal = $tr->getSubcontractorLegalName($link);
            $subcontractor_name = $sub_legal != '' ? $sub_legal : 'NA';
            $standard_title = $framework->getStandardCodeDesc($link);
            $standard_level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");
            $practical_period_start_date = Date::toShort($tr->practical_period_start_date);
            $practical_period_end_date = Date::toShort($tr->practical_period_end_date);
            $apprenticeship_start_date = Date::toShort($tr->apprenticeship_start_date);
            $apprenticeship_end_date_inc_epa = Date::toShort($tr->apprenticeship_end_date_inc_epa);

            $prior_attainment = DAO::getSingleValue($link, "SELECT description FROM central.lookup_prior_attainment WHERE code IN (SELECT level FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = 'h');");

            $ia_literacy = DAO::getSingleValue($link, "SELECT description FROM lookup_pre_assessment WHERE id = '{$tr->literacy}'");
            $ia_numeracy = DAO::getSingleValue($link, "SELECT description FROM lookup_pre_assessment WHERE id = '{$tr->numeracy}'");
            if (DB_NAME == "am_ela") {
                $ia_literacy .= '<br>' . $tr->literacy_other;
                $ia_numeracy .= '<br>' . $tr->numeracy_other;
            }
            $ia_literacy_diagnostic = '';
            if ($tr->literacy_diagnostic != '') {
                $ia_literacy_diagnostic .= '<tr>';
                $ia_literacy_diagnostic .= '<th class="text-bold text-green">Dianostic Assessment - Literacy</th>';
                $ia_literacy_diagnostic .= '<td>';
                $ia_literacy_diagnostic .= DAO::getSingleValue($link, "SELECT description FROM lookup_pre_assessment WHERE id = '{$tr->literacy_diagnostic}'");
                $ia_literacy_diagnostic .= $tr->literacy_diagnostic_other != '' ? '<br>' . $tr->literacy_diagnostic_other : '';
                $ia_literacy_diagnostic .= '</td>';
                $ia_literacy_diagnostic .= '</tr>';
            }
            $ia_numeracy_diagnostic = '';
            if ($tr->numeracy_diagnostic != '') {
                $ia_numeracy_diagnostic .= '<tr>';
                $ia_numeracy_diagnostic .= '<th class="text-bold text-green">Dianostic Assessment - Numeracy</th>';
                $ia_numeracy_diagnostic .= '<td>';
                $ia_numeracy_diagnostic .= DAO::getSingleValue($link, "SELECT description FROM lookup_pre_assessment WHERE id = '{$tr->numeracy_diagnostic}'");
                $ia_numeracy_diagnostic .= $tr->numeracy_diagnostic_other != '' ? '<br>' . $tr->numeracy_diagnostic_other : '';
                $ia_numeracy_diagnostic .= '</td>';
                $ia_numeracy_diagnostic .= '</tr>';
            }

            $date_completed = Date::toShort($skills_analysis->provider_sign_date);

            $funding_band_maximum = $framework->getFundingBandMax($link);
            $recommended_duration = $framework->getRecommendedDuration($link);

            $employerName = in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]) ? $employer->brandDescription($link) : $employer->legal_name;
            $employerMainLocation = $employer->getMainLocation($link);
            $employerLocation = $employerMainLocation->address_line_1 != '' ? $employerMainLocation->address_line_1 . '<br>' : '';
            $employerLocation .= $employerMainLocation->address_line_2 != '' ? $employerMainLocation->address_line_2 . '<br>' : '';
            $employerLocation .= $employerMainLocation->address_line_3 != '' ? $employerMainLocation->address_line_3 . '<br>' : '';
            $employerLocation .= $employerMainLocation->address_line_4 != '' ? $employerMainLocation->address_line_4 . '<br>' : '';
            $employerLocation .= $employerMainLocation->postcode != '' ? $employerMainLocation->postcode : '';

            echo <<<HTML
<div style="text-align: center;">
    <h2><strong>Apprenticeship Skills Analysis</strong></h2>
</div>
<p><br></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Student Programme Details</strong></h4></th></tr>
        <tr><th>Date Completed:</th><td>{$date_completed}</td></tr>
        <tr><th class="text-bold">Apprentice Name</th><td>$ob_learner->firstnames $ob_learner->surname</td></tr>
        <tr><th class="text-bold">Employer Name</th><td>$employerName <br> $employerLocation</td></tr>
        <tr><th class="text-bold">Level</th><td>$standard_level</td></tr>
        <tr><th class="text-bold">Apprenticeship Title</th><td>$standard_title</td></tr>
        <tr><th class="text-bold">Funding Band Maximum</th><td>&pound;$funding_band_maximum</td></tr>
        <tr><th class="text-bold">Recommended Duration (practical period) - months</th><td>$recommended_duration</td></tr>
        <tr><th class="text-bold">Contracted Hours per week</th><td>$tr->contracted_hours_per_week</td></tr>
        <tr><th class="text-bold">Apprentice Job Role</th><td>$tr->job_title</td></tr>
        <tr><th class="text-bold">Main Training Provider</th><td>$provider->legal_name</td></tr>
        <tr><th class="text-bold">Subcontractor (if applicable)</th><td>$subcontractor_name</td></tr>
        <tr><th class="text-bold text-green">End Point Assessment Organisation</th><td>$epa_name</td></tr>
        <tr><th class="text-bold text-green">End Point Assessment Price</th><td>&pound;$tr->epa_price</td></tr>
    </table>
</div>
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Prior Attainment</strong></h4></th></tr>
        <tr>
            <th class="text-bold text-green">Prior Attainment Level</th>
            <td>$prior_attainment</td>
        </tr>
        <tr>
            <th class="text-bold text-green">Initial Assessment - Literacy</th>
            <td>$ia_literacy</td>
        </tr>
        <tr>
            <th class="text-bold text-green">Initial Assessment - Numeracy</th>
            <td>$ia_numeracy</td>
        </tr>
	$ia_literacy_diagnostic
        $ia_numeracy_diagnostic
    </table>
</div>
<p></p>

HTML;
            $english = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND subject = 'English' AND q_type = 'g'");
            $maths = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND subject = 'Maths' AND q_type = 'g'");
            $ict = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND subject = 'ICT' AND q_type = 'g'");
            $qual_records = DAO::getResultset($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type NOT IN ('g', 'h') ORDER BY date_completed", DAO::FETCH_ASSOC);

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th style="width: 25%;">GCSE/A/AS Level</th><th style="width: 25%;">Subject</th><th style="width: 15%;">Predicted Grade</th><th style="width: 15%;">Actual Grade</th><th style="width: 20%;">Date Completed</th></tr>';
            echo '<tr><td>GCSE</td><td>English Language</td>';
            echo isset($english->p_grade) ? '<td>' . $english->p_grade . '</td>' : '<td></td>';
            echo isset($english->a_grade) ? '<td>' . $english->a_grade . '</td>' : '<td></td>';
            echo isset($english->date_completed) ? '<td>' . Date::toShort($english->date_completed) . '</td>' : '<td></td>';
            echo '</tr>';
            echo '<tr><td>GCSE</td><td>Maths</td>';
            echo isset($maths->p_grade) ? '<td>' . $maths->p_grade . '</td>' : '<td></td>';
            echo isset($maths->a_grade) ? '<td>' . $maths->a_grade . '</td>' : '<td></td>';
            echo isset($maths->date_completed) ? '<td>' . Date::toShort($maths->date_completed) . '</td>' : '<td></td>';
            echo '</tr>';
            echo '<tr><td>GCSE</td><td>ICT</td>';
            echo isset($ict->p_grade) ? '<td>' . $ict->p_grade . '</td>' : '<td></td>';
            echo isset($ict->a_grade) ? '<td>' . $ict->a_grade . '</td>' : '<td></td>';
            echo isset($ict->date_completed) ? '<td>' . Date::toShort($ict->date_completed) . '</td>' : '<td></td>';
            echo '</tr>';
            $qualLevelsList = DAO::getLookupTable($link, "SELECT id, description FROM lookup_ob_qual_levels");
            foreach ($qual_records as $record) {
                $record = (object)$record;
                echo '<tr>';
                echo isset($qualLevelsList[$record->level]) ? '<td>' . $qualLevelsList[$record->level] . '</td>' : '<td>' . $record->level . '</td>';
                echo '<td>' . $record->subject . '</td>';
                echo '<td>' . $record->p_grade . '</td>';
                echo '<td>' . $record->a_grade . '</td>';
                echo '<td>' . Date::toShort($record->date_completed) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div><p></p>';

            $employment_records = DAO::getResultset($link, "SELECT * FROM ob_learners_ea WHERE tr_id = '{$tr->id}' ORDER BY ea_date_from DESC", DAO::FETCH_ASSOC);

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="5" style="color: #000; background-color: #d2d6de !important"><h4><strong>Employment & Work Experience</strong></h4></th></tr>';
            echo '<tr><th style="width: 15%;">Date From</th><th style="width: 15%;">Date To</th><th style="width: 20%;">Employer</th><th style="width: 20%;">Role</th><th style="width: 30%;">Responsibilities</th></tr>';
            foreach ($employment_records as $record) {
                $record = (object)$record;
                echo '<tr>';
                echo '<td>' . Date::toShort($record->ea_date_from) . '</td>';
                echo '<td>' . Date::toShort($record->ea_date_to) . '</td>';
                echo '<td>' . $record->ea_employer . '</td>';
                echo '<td>' . $record->ea_role . '</td>';
                echo '<td>' . $record->ea_resp . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div><p></p>';

            $als_records = DAO::getResultset($link, "SELECT * FROM ob_learner_als WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="5" style="color: #000; background-color: #d2d6de !important"><h4><strong>Additional Learning Support</strong></h4></th></tr>';
            echo '<tr><th style="width: 15%;">Date Discussed</th><th style="width: 15%;">Support Required</th><th style="width: 20%;">Details</th><th style="width: 20%;">Date Claimed From</th><th style="width: 30%;">Additional Info.</th></tr>';
            if (count($als_records) == 0)
                echo '<tr><td colspan="5"><i>No records to show.</i></td></tr>';
            foreach ($als_records as $als_row) {
                $als_row = (object)$als_row;
                echo '<tr>';
                echo '<td>' . Date::toShort($als_row->date_discussed) . '</td>';
                echo $als_row->support_required == 'Y' ? '<td>Yes</td>' : '<td>No</td>';
                echo '<td>' . HTML::cell($als_row->details) . '</td>';
                echo '<td>' . Date::toShort($als_row->date_claimed_from) . '</td>';
                echo '<td>' . HTML::cell($als_row->additional_info) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div><p></p>';

            if (DB_NAME == "am_ela") {
                echo '<div style="text-align: right;">';
                echo '<table border="1" style="width: 100%;" cellpadding="6">';
                echo '<tr><td>I have no experience and knowledge of this, need full training and possibly additional learning support</td><td>0</td></tr>';
                echo '<tr><td>I have a little experience and knowledge, but still need full training and possibly additional learning support</td><td>1</td></tr>';
                echo '<tr><td>I have a basic understanding /experience of this, but I would still like full training</td><td>2</td></tr>';
                echo '<tr><td>I have a reasonable understanding /experience of this and currently do this in my job, but I still need the majority of the training offered</td><td>3</td></tr>';
                echo '<tr><td>I have a more than reasonable understanding /experience of this, have a related qualification (not current) and I only need some training to be assessment ready</td><td>4</td></tr>';
                echo '<tr><td>I am fully knowledgeable and competent, have a related and current qualification and can provide evidence to support my competence. I do not require any training and am assessment ready.</td><td>5</td></tr>';
                echo '</table>';
                echo '</div>';
            }

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="6" style="color: #000; background-color: #d2d6de !important"><h4><strong>Knowledge, Skills & Behaviours</strong></h4></th></tr>';
            echo '<tr><th>Group</th><th>Evidence</th><th>Learner Comments</th><th>Learner Score</th><th>Before DL</th><th>After DL</th></tr>';

            $total_planned_hours = 20;
            $dh_total = 0;
            $dh_scored = 0;
            //$scores_list = SkillsAnalysis::getScoreAndPercentageList();
            $scores = LookupHelper::getDDLKsbScores();
            $score_percentages = $skills_analysis->getRplPercentages();
            foreach ($skills_analysis->ksb as $entry) {
                $row_score = 0;
                $del_hours = $entry['del_hours'] != '' ? floatval($entry['del_hours']) : 0;
                echo '<tr>';
                echo '<td>' . $entry['unit_group'] . '</td>';
                echo '<td>' . html_entity_decode($entry['evidence_title']) . '</td>';
                echo '<td class="small">' . $entry['comments'] . '</td>';
                //echo '<td>' . $entry['del_hours'] . '</td>';
                echo '<td>' . $entry['score'] . '</td>';
                echo '<td>' . $del_hours . '</td>';
                /*$p = round(100-($scores_list[$entry['score']] * 100), 3);
            
            echo $entry['score'] != 1 ? '<td>' . $p . '%</td>' : '<td>0%</td>';
            if (intval($entry['score']) > 0) {
                $row_score = round(floatval($entry['del_hours']) * $scores_list[$entry['score']], 2);
            } else {
                $row_score = 0;
            }
            echo '<td>' . $row_score . '</td>';
            */
                if ($entry['score'] == 5)
                    $delivery_plan_hours = round($del_hours * $score_percentages["score_5"], 2);
                elseif ($entry['score'] == 4)
                    $delivery_plan_hours = round($del_hours * $score_percentages["score_4"], 2);
                elseif ($entry['score'] == 3)
                    $delivery_plan_hours = round($del_hours * $score_percentages["score_3"], 2);
                elseif ($entry['score'] == 2)
                    $delivery_plan_hours = round($del_hours * $score_percentages["score_2"], 2);
                elseif ($entry['score'] == 1)
                    $delivery_plan_hours = $del_hours * $score_percentages["score_1"];

                echo '<td>' . $delivery_plan_hours . '</td>';
                echo '</tr>';
                $dh_total += floatval($entry['del_hours']);
                $dh_scored += $row_score;
            }
            echo '<tr><th colspan="6">Duration following asseessment: ' . $skills_analysis->duration_fa . ' months</th></tr>';
            echo '<tr><th colspan="6">Percentage following assessment: ' . $skills_analysis->percentage_fa . '%</th> </tr>';
            if ($tr->underSixHoursPerWeekRule())
                echo '<tr><th colspan="6">Price Reduction Percentage: ' . $skills_analysis->price_reduction_percentage . '%</th> </tr>';
            echo '</table>';
            echo '</div><p></p>';

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th style="color: #000; background-color: #d2d6de !important"><h4><strong>Rationale (Duration and Negotiated Price)</strong></h4></th></tr>';
            echo '<tr><td>' . $skills_analysis->rationale_by_provider . '</td></tr>';
            echo '</table>';
            echo '</div><p></p>';

            if ($skills_analysis->employer_comments != '') {
                echo '<div style="text-align: center;">';
                echo '<table border="1" style="width: 100%;" cellpadding="6">';
                echo '<tr><th style="color: #000; background-color: #d2d6de !important"><h4><strong>Employer Comments</strong></h4></th></tr>';
                echo '<tr><td>' . $skills_analysis->employer_comments . '</td></tr>';
                echo '</table>';
                echo '</div><p></p>';
            }

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Off-the-Job Hours</strong></h4></th></tr>';
            echo '<tr><th>Contracted hours per week</th><td>' . $tr->contracted_hours_per_week . '</td></tr>';
            echo '<tr><th>Weeks to be worked per year</th><td>' . $tr->weeks_to_be_worked_per_year . '</td></tr>';
            echo '<tr><th>Total contracted hours per year</th><td>' . $tr->total_contracted_hours_per_year . '</td></tr>';
            if ($tr->contracted_hours_per_week >= 30) {
                echo '<tr><td colspan="2"></td></tr><tr><th colspan="2" class="bg-green-gradient">Full Time Hours (30 or above)</th></tr>';
                echo '<tr><th>Length of Programme (Practical Period)</th><td>' . $tr->duration_practical_period . ' months</td></tr>';
                echo '<tr><th>Total Contracted Hours - Full Apprenticeship</th><td>' . $tr->total_contracted_hours_full_apprenticeship . ' hours</td></tr>';
                if (!$tr->underSixHoursPerWeekRule()) {
                    echo '<tr class="bg-light-blue-gradient"><th>Minimum 20% OTJ Training</th><td>' . $tr->minimum_percentage_otj_training . ' hours</td></tr>';
                } else {
                    //echo '<tr class="bg-light-blue-gradient"><th>OTJ Hours</th><td>' . $tr->off_the_job_hours_based_on_duration . ' hours</td></tr>';
                    if ($tr->otj_overwritten != '') {
                        echo '<tr class="bg-light-blue-gradient"><th>OTJ Hours</th><td>' . $tr->otj_overwritten . ' hours</td></tr>';
                    } else {
                        echo '<tr class="bg-light-blue-gradient"><th>OTJ Hours</th><td>' . $tr->off_the_job_hours_based_on_duration . ' hours</td></tr>';
                    }
                }
            } else {
                echo '<tr><td colspan="2"></td></tr>';
                echo '<tr><th colspan="2" class="bg-green-gradient">Part Time Hours (less than 30)</th></tr>';
                echo '<tr><th>Minimum Duration (part time)</th><td>' . $skills_analysis->minimum_duration_part_time . ' months</td></tr>';
                echo '<tr><th>Total Contracted Hours - Full Apprenticeship</th><td>' . $skills_analysis->part_time_total_contracted_hours_full_apprenticeship . ' hours</td></tr>';
                echo '<tr class="bg-light-blue-gradient"><th>Minimum 20% OTJ Training</th><td>' . $skills_analysis->part_time_otj_hours . ' hours</td></tr>';
            }
            echo '<tr><td colspan="2"></td></tr>';
            echo '<tr><td colspan="2"></td></tr>';
            //echo '<tr class="bg-green-gradient"><th>Planned Delivery Hours (OTJ) following Skills Analysis</th><td>' . $skills_analysis->delivery_plan_hours_fa . '</td></tr>';
            echo '</table>';
            echo '</div><p></p>';

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Learner\'s Eligibility</strong></h4></th></tr>';
            if ($skills_analysis->is_eligible_after_ss == 'Y')
                echo '<tr><td colspan="2">Learner is Eligible <br>' . $skills_analysis->rationale_by_provider . '</td></tr>';
            elseif ($skills_analysis->is_eligible_after_ss == 'N') {
                echo '<tr><th colspan="2">Learner is NOT Eligible <br>' . $skills_analysis->rationale_by_provider . '</th></tr>';
                echo '<tr><th>Reason: </th><td>' . $skills_analysis->ineligibility_reason . '</td></tr>';
            }
            echo '</table>';
            echo '</div><p></p>';

            $learner_sign_date = isset($skills_analysis->learner_sign_date) ? Date::toShort($skills_analysis->learner_sign_date) : '';
            $provider_sign_date = isset($skills_analysis->provider_sign_date) ? Date::toShort($skills_analysis->provider_sign_date) : '';
            // $provider_user_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$skills_analysis->provider_user_id}'");
            $provider_user_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.signature = '{$skills_analysis->provider_sign}'");

            $provider_td_title = in_array(DB_NAME, ["am_ela"]) ? 'Assessor/Tutor' : 'Training Provider';

            $employer_rows = '';
            if ($skills_analysis->employer_sign != '') {
                $employer_sign_date = isset($skills_analysis->employer_sign_date) ? Date::toShort($skills_analysis->employer_sign_date) : '';
                $employer_rows .= '<tr>';
                $employer_rows .= '<td>Employer</td>';
                $employer_rows .= '<td>' . $skills_analysis->employer_sign_name . '</td>';
                $employer_rows .= '<td><img src="' . $employer_signature_file . '" style="border: 2px solid;border-radius: 15px;" /></td>';
                $employer_rows .= '<td>' . $employer_sign_date . '</td>';
                $employer_rows .= '</tr>';
            }

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
	$employer_rows
        <tr>
            <td>{$provider_td_title}</td>
            <td>{$provider_user_name}</td>
            <td><img src="$provider_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$provider_sign_date}</td>
        </tr>
    </table>
</div>
HTML;

            $html = ob_get_contents();

            $mpdf->SetHTMLFooter($footer);
            ob_end_clean();
// pre($html);
            $mpdf->WriteHTML($html);

            // $mpdf->Output('IAG File', 'I');

            $mpdf->Output($sa_file, 'F');
        }
    }

    public static function initialContractPdf(PDO $link, TrainingRecord $tr, EmployerSchedule1 $schedule)
    {
        $ob_learner = $tr->getObLearnerRecord($link);

        if (!isset($schedule->tr_id)) {
            return;
        }

        if ($schedule->tp_sign == '' || $schedule->emp_sign == '') {
            return;
        }

        $document_term = in_array(DB_NAME, ["am_ela"]) ? "agreement" : "contract";

        $ic_dir = $tr->getDirectoryPath() . 'schedule1/';
        if (!is_dir($ic_dir)) {
            mkdir("$ic_dir", 0777, true);
        }

        $employer_signature_file = $ic_dir . 'emp_sign_image.png';
        if (!is_file($employer_signature_file)) {
            self::generateSignImages($schedule->emp_sign, $ic_dir, $employer_signature_file);
        }

        $provider_signature_file = $ic_dir . 'tp_sign_image.png';
        if (!is_file($provider_signature_file)) {
            self::generateSignImages($schedule->tp_sign, $ic_dir, $provider_signature_file);
        }

        $ic_file = $ic_dir . 'InitialContract_' . $schedule->id . '.pdf';

        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if (!is_file($ic_file) || in_array("S1", $trGeneratePdfs)) {
            $ic_file = in_array("S1", $trGeneratePdfs) ? $ic_dir . 'InitialContract_' . $schedule->id . '_' . uniqid() . '.pdf' : $ic_file;

            $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
            if ($logo == '')
                $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

            $employer = Employer::loadFromDatabase($link, $tr->employer_id);
            if (in_array(DB_NAME, ["am_superdrug", "am_sd_demo"])) {
                $logo = $employer->logoPath();
            }

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
					<td width = "35%" align="left" style="font-size: 10px">Initial {$document_term}</td>
					<td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;
            //Beginning Buffer to save PHP variables and HTML tags
            ob_start();

            //$employer = Employer::loadFromDatabase($link, $tr->employer_id);
            $mainLocation = $employer->getMainLocation($link);
            $employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);
            $detail = json_decode($schedule->detail);
            $dob = Date::toShort($ob_learner->dob);
            $age_at_start_of_app = Date::dateDiff(date("Y-m-d"), $ob_learner->dob);
            $framework = Framework::loadFromDatabase($link, $tr->framework_id);

            $mainContactName = isset($detail->contact_name) ? $detail->contact_name : $mainLocation->contact_name;
            $mainContactTel = isset($detail->contact_telephone) ? $detail->contact_telephone : $mainLocation->contact_telephone;
            $mainContactEmail = isset($detail->contact_email) ? $detail->contact_email : $mainLocation->contact_email;

            $employerName = in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]) ? $employer->brandDescription($link) : $employer->legal_name;

            echo <<<HTML
<div style="text-align: center;">
    <h2><strong>Employer Apprenticeship Agreement</strong></h2>
</div>
<p><br></p>
HTML;
            echo <<<HTML
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">Section 1 - Employer and Apprentice Details</th></tr>
        <tr>
            <th>1.1</th>
            <td>
                <strong>Name of Employer: </strong>$employerName <br>
                <strong>Contact Name: </strong>$mainContactName <br>
                <strong>Contact Tel No.: </strong>$mainContactTel <br>
                <strong>Contact Email: </strong>$mainContactEmail 
            </td>
        </tr>
        <tr>
            <th>1.2</th>
            <td>
                <strong>Name of Apprentice: </strong>$ob_learner->firstnames $ob_learner->surname <br>
                <strong>Date of Birth: </strong>$dob <br>
                <strong>Age at start of apprenticeship: </strong>$age_at_start_of_app <br>
                <strong>ULN: </strong>$ob_learner->uln <br>
                <strong>Cohort: </strong>$framework->title
            </td>
        </tr>
    </table>
</div>
HTML;

            $apprentice_job_title = (isset($detail->apprentice_job_title) && $detail->apprentice_job_title != '') ? $detail->apprentice_job_title : $tr->job_title;
            $level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");
            $title_of_app = $framework->getStandardCodeDesc($link);
            $proposed_sd = isset($detail->practical_period_start_date) ? $detail->practical_period_start_date : Date::toShort($tr->practical_period_start_date);
            $proposed_ed = isset($detail->practical_period_end_date) ? $detail->practical_period_end_date : Date::toShort($tr->practical_period_end_date);
            $planned_epa_date = isset($detail->planned_epa_date) ? $detail->planned_epa_date : Date::toShort($tr->planned_epa_date);
            $contracted_hours_per_week = isset($detail->contracted_hours_per_week) ? $detail->contracted_hours_per_week : $tr->contracted_hours_per_week;
            $weeks_to_be_worked_per_year = isset($detail->weeks_to_be_worked_per_year) ? $detail->weeks_to_be_worked_per_year : $tr->weeks_to_be_worked_per_year;

            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 2 - Apprenticeship Programme</th></tr>
        <tr>
            <th>2.1</th>
            <th>Apprentice Job Title</th>
            <td>{$apprentice_job_title}</td>
        </tr>
        <tr>
            <th>2.2</th>
            <th>Standard</th>
            <td>{$framework->title}</td>
        </tr>
        <tr>
            <th>2.3</th>
            <th>Level of Apprenticeship</th>
            <td>{$level}</td>
        </tr>
        <tr>
            <th>2.4</th>
            <th>Title of Apprenticeship</th>
            <td>{$title_of_app}</td>
        </tr>
        <tr>
            <th>2.5</th>
            <th>Location of Training</th>
            <td>
                $employer_location->address_line_1,
                $employer_location->address_line_2 
                $employer_location->address_line_3, 
                $employer_location->address_line_4,
                $employer_location->postcode
            </td>
        </tr>
        <tr>
            <th>2.6</th>
            <th>Proposed Start Date</th>
            <td>{$proposed_sd}</td>
        </tr>
        <tr>
            <th>2.7</th>
            <th>Proposed End Date<br><small>(for practical training)</th>
            <td>{$proposed_ed}</td>
        </tr>
        <tr>
            <th>2.8</th>
            <th>Planned EPA Date</th>
            <td>{$planned_epa_date}</td>
        </tr>
        <tr>
            <th>2.9</th>
            <th>Contracted Hours per week</th>
            <td>{$contracted_hours_per_week}</td>
        </tr>
        <tr>
            <th>2.10</th>
            <th>Weeks to be worked per year</th>
            <td>{$weeks_to_be_worked_per_year}</td>
        </tr>
    </table>
</div>
HTML;

            $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
            $provider_location = Location::loadFromDatabase($link, $tr->provider_location_id);
            $provider_details = '';
            $provider_details .= $provider->legal_name . '<br>';
            $provider_details .= $provider_location->address_line_1 != '' ? $provider_location->address_line_1 . '<br>' : '';
            $provider_details .= $provider_location->address_line_2 != '' ? $provider_location->address_line_2 . '<br>' : '';
            $provider_details .= $provider_location->address_line_3 != '' ? $provider_location->address_line_3 . '<br>' : '';
            $provider_details .= $provider_location->address_line_4 != '' ? $provider_location->address_line_4 . '<br>' : '';
            $provider_details .= $provider_location->postcode . '<br>';

            $tp_contract_manager = isset($detail->tp_contract_manager) ? DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$detail->tp_contract_manager}'") : '';
            $tp_op_manager = isset($detail->tp_op_manager) ? DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$detail->tp_op_manager}'") : '';

            $trainers_ids = $tr->trainers != '' ? explode(",", $tr->trainers) : [];
            $_trainers = '';
            foreach ($trainers_ids as $_t_id)
                $_trainers .= DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$_t_id}'") . '<br>';
            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 3 - Training Provider Actions</th></tr>
        <tr>
            <th>3.1</th>
            <th>Training Provider</th>
            <td>{$provider_details}</td>
        </tr>
        <tr>
            <th>3.2</th>
            <th>Contract Manager</th>
            <td>{$tp_contract_manager}</td>
        </tr>
        <tr>
            <th>3.3</th>
            <th>Operations Manager</th>
            <td>{$tp_op_manager}</td>
        </tr>
        <tr>
            <th>3.4</th>
            <th>Training to be delivered by the<br>Training Provider</th>
            <td>{$detail->training_by_provider}</td>
        </tr>
        <tr>
            <th>3.5</th>
            <th>Trainer</th>
            <td>{$_trainers}</td>
        </tr>
        <tr>
            <th>3.6</th>
            <th>Training Provider Equipment</th>
            <td>{$detail->provider_equipment}</td>
        </tr>
    </table>
</div>
HTML;

            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 4 - Employer Actions</th></tr>
        <tr>
            <th>4.1</th>
            <th>Training to be delivered by the<br>Employer</th>
            <td>{$detail->training_by_employer}</td>
        </tr>
        <tr>
            <th>4.2</th>
            <th>Employer Equipment</th>
            <td>{$detail->employer_equipment}</td>
        </tr>
    </table>
</div>
HTML;

            $epa_org_name = $tr->getEpaOrgName($link);
            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 5 - End-Point Assessment (EPA) Organisation - Standards Only</th></tr>
        <tr>
            <th>5.1</th>
            <th>Name of EPA Organisation</th>
            <td>{$epa_org_name}</td>
        </tr>
    </table>
</div>
HTML;

            $subcontractor_name = $tr->getSubcontractorLegalName($link);
            $subcon_ukprn = DAO::getSingleValue($link, "SELECT ukprn FROM organisations WHERE id = '{$tr->subcontractor_id}'");
            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 6 - Subcontracting</th></tr>
        <tr>
            <th>6.1</th>
            <th>Name of Subcontractor</th>
            <td>{$subcontractor_name}</td>
        </tr>
        <tr>
            <th>6.2</th>
            <th>Training to be delivered by<br>Subcontractor</th>
            <td>{$detail->training_by_subcontractor}</td>
        </tr>
        <tr>
            <th>6.3</th>
            <th>UKPRN</th>
            <td>{$subcon_ukprn}</td>
        </tr>
    </table>
</div>
HTML;

            $_e = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%english%';");
            $_m = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%math%';");
            $_ict = DAO::getSingleValue($link, "SELECT COUNT(*) FROM framework_qualifications WHERE framework_id = '{$tr->framework_id}' AND qualification_type = 'FS' AND LOWER(title) LIKE '%ict%';");
            $_e = $_e > 0 ? 'Yes' : 'No';
            $_m = $_m > 0 ? 'Yes' : 'No';
            $_ict = $_ict > 0 ? 'Yes' : 'No';
            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important">Section 7 - Functional Skills required for this Apprenticeship (not the individual)</th></tr>
        <tr>
            <th>7.1</th>
            <th>Maths</th>
            <td>$_e</td>
        </tr>
        <tr>
            <th>7.2</th>
            <th>English</th>
            <td>$_m</td>
        </tr>
        <tr>
            <th>7.3</th>
            <th>ICT</th>
            <td>$_ict</td>
        </tr>
    </table>
</div>
HTML;

            $max_funding_band = $framework->getFundingBandMax($link);
            echo <<<HTML
<p></p>
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th colspan="2" style="color: #000; background-color: #d2d6de !important">
                Section 8 - Proposed Cost of Training Per Apprentice (subject to RPL)<br>
                <i>the maximum funding band for this standard is &pound; $max_funding_band</i>
            </th>
        </tr>
HTML;
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">TNP 1</th></tr>';

            //$tnp1_prices = is_null($tr->tnp1) ? [] : json_decode($tr->tnp1);
            $tnp1_prices = DAO::getSingleValue($link, "SELECT tnp1 FROM ob_learner_skills_analysis WHERE tr_id = '{$tr->id}'");
            $tnp1_prices = is_null($tnp1_prices) ? [] : json_decode($tnp1_prices);
            $tnp1_costs = array_map(function ($ar) {
                return $ar->cost;
            }, $tnp1_prices);
            $tnp1_total = array_sum(array_map('floatval', $tnp1_costs));
            $tnp_total = $tnp1_total + $tr->epa_price;
            foreach ($tnp1_prices as $tnp1) {
                echo '<tr>';
                echo '<th>' . $tnp1->description . '</th>';
                echo '<td>&pound;' . $tnp1->cost . '</td>';
                echo '<tr>';
            }
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">TNP 2</th></tr>';
            echo '<tr><th>EPA Cost</th><td>' . $tr->epa_price . '</td></tr>';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">TNP</th></tr>';
            echo '<tr><th>Total Proposed Cost (TNP 1 + TNP 2)</th><td>' . $tnp_total . '</td></tr>';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">Additional Prices</th></tr>';
            $additional_prices = (is_null($tr->additional_prices) || $tr->additional_prices == 0) ? [] : json_decode($tr->additional_prices);
            foreach ($additional_prices as $additional_price) {
                echo '<tr>';
                echo '<th>' . $additional_price->description . '</th>';
                echo '<td>&pound;' . $additional_price->cost . '</td>';
                echo '<tr>';
            }
            if (count($additional_prices) == 0) {
                echo '<tr><td colspan="2"><i>No additional price to show.</i></td></tr>';
            }


            echo <<<HTML
    </table>
</div>
HTML;

            $cost1 = '';
            $cost2 = '';
            $cost3 = '';
            $cost4 = '';
            $cost5 = '';

            $learner_age_sql = <<<SQL
SELECT 
    ((DATE_FORMAT(CURDATE(),'%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
            $learner_age = DAO::getSingleValue($link, $learner_age_sql);

            if ($employer->funding_type == 'LG' && $tr->type_of_funding == "Levy Gifted") {
                $cost5 = '&pound;' . $tnp_total;
            } elseif ($employer->funding_type == 'L') {
                $cost1 = '&pound;' . $tnp_total;
            } else {
                $ageToCheck = $tr->practical_period_start_date < '2024-04-01' ? 19 : 21;
                if (in_array($employer->code, [1, 2, 3, 6]) || $learner_age > $ageToCheck) // then show 2nd and 3rd box
                {
                    $cost2 = '&pound;' . ceil(($tnp_total * 5) / 100);
                    $cost3 = '&pound;' . ceil(($tnp_total * 95) / 100);
                } else {
                    $cost4 = '&pound;' . $tnp_total;
                }
            }
            $wm_auth = '';
            if (DB_NAME == "am_crackerjack") {
                $wm_auth = (isset($detail->wm_auth) && $detail->wm_auth == 1) ? '<tr><th>West Midlands Combined Authority</th><td colspan="3">Yes</td></tr>' : '';
                if (isset($detail->wm_auth) && $detail->wm_auth == 1) {
                    $cost1 = '&pound;' . $tnp_total;
                    $cost2 = '';
                    $cost3 = '';
                    $cost4 = '';
                }
            }
            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="5" style="color: #000; background-color: #d2d6de !important">Section 9 - Total Cost of Training Paid to the Training Provider (subject to RPL)</th></tr>
	{$wm_auth}
        <tr class="text-center">
            <th>Levy Paying Employers</th>
            <th>Co-Funded/Non Levy Employers</th>
            <th>Government Contribution</th>
            <th>Government Contribution - SME</th>
            <th class="text-center">Levy Gifted</th>
        </tr>
        <tr class="text-center">
            <td>Maximum Employer Contribution via Levy - 100%</td>
            <td>0% or 5% Employer Contribution</td>
            <td>95%</td>
            <td>100%</td>
            <td>100%</td>
        </tr>
        <tr class="text-center">
            <td>{$cost1}</td>
            <td>{$cost2}</td>
            <td>{$cost3}</td>
            <td>{$cost4}</td>
            <td>{$cost5}</td>
        </tr>
        <tr>
            <td colspan="5">
                The Department for Education (DfE) will fund 95% of the Apprenticeship programme, with the Employer contributing the other 5%. 
                This Co-Investment is not applicable for small employers with less than 50 employees if they take on a 16-18 year old or a 19-23 year old with an EHC plan. 
                Delivery of Maths and English will be paid directly to Training Provider via the Department for Education (DfE).
            </td>
        </tr>
    </table>
</div>
HTML;


            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">Section 10 - Additional Details (details supporting the negotiated costs / reduced rates)</th></tr>
        <tr>
            <td>The negotiated price will be confirmed with the Employer after the Skills Analysis has taken place, together with the first visit from the trainer.</td>
        </tr>
        <tr>
            <td>{$detail->section11_additional_details}</td>
        </tr>
    </table>
</div>
HTML;

            $section12Option1 = (isset($detail->section12) && is_array($detail->section12) && in_array(1, $detail->section12)) ? '<img style="width:15px; height:15px;" src="./images/check.jpg" /> ' : '';
            $section12Option2 = (isset($detail->section12) && is_array($detail->section12) && in_array(2, $detail->section12)) ? '<img style="width:15px; height:15px;" src="./images/check.jpg" /> ' : '';
            $section12Option3 = (isset($detail->section12) && is_array($detail->section12) && in_array(3, $detail->section12)) ? '<img style="width:15px; height:15px;" src="./images/check.jpg" /> ' : '';
            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">Section 11 - Additional Payments</th></tr>
        <tr>
            <td>
                <p style="font-weight: 700;">16-18 Employer Incentive / 19-24 Education Health Care Plan</p>
                <p>
                    The training provider and employer will receive a payment towards the additional cost associated with training
                    if, at the start of the apprenticeship, the apprentice is:
                </p>
                <ul style="margin-left: 5px;">
                    <li style="font-weight: 700;">
                        Aged between 16 and 18 years old (or 15 years of age if the apprentice's 16th birthday
                        is between the last Friday of June and 31 August).
                    </li>
                    <li style="font-weight: 700;">
                        Aged between 19 and 24 years old and has either an Education, Health and Care (EHC) plan
                        provided by their local authority or has been in the care of thier local authority.
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <label>
                        {$section12Option1}
                        I (the Employer) confirm I am eligible for the &pound;1,000 16-18 Employer Incentive
                        for the Apprentice detailed within this schedule.
                    </label>
                </p>
                <hr>
                <p>
                    <label>
                        {$section12Option2}
                        I (the Employer) confirm I am eligible for the &pound;1,000 19-24 Education Health Care plan or care leaver
                        employer incentive for the Apprentice detailed within this schedule.
                        (Relevant evidence will be required at the beginning of the apprenticeship)
                    </label>
                </p>
                <hr>
                <p>
                    <label>
                        {$section12Option3}
                        Not Applicable
                    </label>
                </p>
            </td>
        </tr>
    </table>
</div>
HTML;

            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">Section 12 - Payment Schedule</th></tr>
        <tr>
            <td>
                <p style="font-weight: 700;"><strong>Levy Paying Employers</strong></p>
                <ul style="margin-left: 5px;">
                    <li>
                        80% of the total price will be taken from your Apprenticeship Service
                        account on a monthly basis, over the duration of the apprentice's programme.
                    </li>
                    <li style="font-weight: 700;">
                        20% of the total cost will be retained for achievement and/or End Point
                        Assessment costs and will be taken from your Apprenticeship Service Account.
                    </li>
                </ul>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-weight: 700;"><strong>Co-Investor Employers</strong></p>
                <ul style="margin-left: 5px;">
                    <li>
                        Where your 5% Employer Contribution is &pound;250 or less, you will be
                        invoiced in full at the start of the apprenticeship programme.
                    </li>
                    <li style="font-weight: 700;">
                        Where your 5% Employer Contribution is over &pound;250, you will be invoiced in full,
                        and payments will be obtained on 4 equal instalments at months 1, 4, 7 and 9.
                    </li>
                    <li style="font-weight: 700;">
                        Invoices are to be paid within 30 days from the date of invoice.
                    </li>
                </ul>
            </td>
        </tr>
    </table>
</div>
HTML;
            if (DB_NAME == "am_crackerjack") {
                echo '<table class="table table-bordered">';
                echo '<tr>';
                echo '<th style="width: 50%;">Please select a payment option below:</th>';
                echo '<td>';
                echo (isset($detail->payment_structure) && $detail->payment_structure == 'upfront_payment') ? '<img style="width:15px; height:15px;" src="./images/check.jpg" />  Upfront Payment <br>' : '  Upfront Payment <br>';
                echo (isset($detail->payment_structure) && $detail->payment_structure == 'monthly_standing_order') ? '<img style="width:15px; height:15px;" src="./images/check.jpg" />  Monthly Standing Order <br>' : '  Monthly Standing Order <br>';
                echo '</td>';
                echo '</tr>';
                echo '</table>';
            }

            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">Section 13 - Mandatory Policies</th></tr>
        <tr>
            <td>
                <p>Training Provider policies available to learner:</p>
                <ul style="margin-left: 5px;">
                    <li>Safeguarding</li>
                    <li>Health & Safety</li>
                    <li>Equality & Diversity</li>
                    <li>GDPR</li>
                    <li>Complaints</li>
                </ul>
            </td>
        </tr>
    </table>
</div>
HTML;

            $section15radio_option1 = (isset($detail->section15radio) && $detail->section15radio == '1') ? '<img style="width:15px; height:15px;" src="./images/check.jpg" />' : '';
            $section15radio_option2 = (isset($detail->section15radio) && $detail->section15radio == '2') ? '<img style="width:15px; height:15px;" src="./images/check.jpg" />' : '';

            $_v1 = '';
            if (!$tr->postJuly25Start()) {
                $_v1 = "20% off-the-job training is the equivalent of 1 day per week based on a 5 day working week.";
            }

            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">Section 14 - Employer Declarations</th></tr>
        <tr>
            <td>
                <span style="font-weight: 700;" style="margin-left: 5px;"> {$section15radio_option1} Option 1 - </span>
                I confirm that apprentice(s) named in this {$document_term} has/have been issued with a contract of
                employment and is/will be employed for at least 30 hours per week. The minimum
                duration of each apprenticeship is based on the apprentice working at least 30 hours a week.
                <p style="font-weight: 700;">------------------------------OR------------------------------</p>
                <span style="font-weight: 700; margin-left: 5px;"> {$section15radio_option2} Option 2 - </span>
                I confirm that apprentice(s) named in this {$document_term} has/have been issued with a contract of
                employment and is/will be employed for at least 16 hours per week. I am aware that
                the duration of the apprenticeship will be extended accordingly to take account of this.
            </td>
        </tr>
        <tr>
            <td>
                <img style="width:15px; height:15px;" src="./images/check.jpg" /> Off-the-job training has been discussed and I am aware of the requirements for this.
                {$_v1}
            </td>
        </tr>
        <tr>
            <td>
                <img style="width:15px; height:15px;" src="./images/check.jpg" /> The cost of this Apprenticeship has been discussed with us in detail, 
                    we fully understand the negotiated price for training and associated costs (TNP1) and we have negotiated the EPA price (TNP2). 
                    I understand that this is an indicative price at this point and is subject to change after the Skills Analysis has taken place.
            </td>
        </tr>
        <tr>
            <td>
                <img style="width:15px; height:15px;" src="./images/check.jpg" /> I confirm that all apprentices listed in this schedule will spend at least
                50% of their working hours in England over the duration of the apprenticeship.
            </td>
        </tr>
        <tr>
            <td>
                <img style="width:15px; height:15px;" src="./images/check.jpg" /> I confirm as part of our recruitment process we have check the named apprentice(s) right
                     to work in the UK and have checked and hold copies of the relevant documentation which will be made
                      available to the main provider when requested.
            </td>
        </tr>
    </table>
</div>
HTML;

            $emp_sign_date = isset($schedule->emp_sign_date) ? Date::toShort($schedule->emp_sign_date) : '';
            $tp_sign_date = isset($schedule->tp_sign_date) ? Date::toShort($schedule->tp_sign_date) : '';

            echo <<<HTML
<p>All costs shown in the {$document_term} are current however, this is subject to change.</p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="4" class="bg-blue">Signatures</th></tr>
        <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
        <tr>
            <td>Employer</td>
            <td>{$schedule->emp_sign_name}</td>
            <td><img id="img_tp_sign" src="$employer_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$emp_sign_date}</td>
        </tr>
        <tr>
            <td>Training Provider</td>
            <td>{$schedule->tp_sign_name}</td>
            <td><img id="img_tp_sign" src="{$provider_signature_file}" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$tp_sign_date}</td>
        </tr>
    </table>
</div>
HTML;

            $html = ob_get_contents();

            $mpdf->SetHTMLFooter($footer);
            ob_end_clean();

            $mpdf->WriteHTML($html);

            // $mpdf->Output('IAG File', 'I');

            $mpdf->Output($ic_file, 'F');
        }
    }

    public static function learningStylesAssessmentPdf(PDO $link, TrainingRecord $tr)
    {
        $ob_learner = $tr->getObLearnerRecord($link);

        $assessment = DAO::getObject($link, "SELECT * FROM ob_learner_learning_style WHERE tr_id = '{$tr->id}'");
        if (!isset($assessment->tr_id)) {
            return;
        }

        if ($assessment->learner_sign == '') {
            return;
        }

        $learn_style_assessment_dir = $tr->getDirectoryPath() . 'learn_style_assessment/';
        if (!is_dir($learn_style_assessment_dir)) {
            mkdir("$learn_style_assessment_dir", 0777, true);
        }

        $learner_signature_file = $learn_style_assessment_dir . 'learner_sign_image.png';
        if (!is_file($learner_signature_file)) {
            self::generateSignImages($assessment->learner_sign, $learn_style_assessment_dir, $learner_signature_file);
        }

        $learn_style_assessment_file = $learn_style_assessment_dir . OnboardingHelper::LEARN_STYLE_ASSESSMENT;

        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if (!is_file($learn_style_assessment_file) || in_array("LSA", $trGeneratePdfs)) {
            $learn_style_assessment_file = in_array("LSA", $trGeneratePdfs) ? $learn_style_assessment_dir . 'Learning Style Assessment_' . uniqid() . '.pdf' : $learn_style_assessment_file;

            $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
            if ($logo == '')
                $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

            $mpdf = new \Mpdf\Mpdf(['format' => 'Legal', 'default_font_size' => 10]);
            $mpdf->SetTitle("Learning Style Self-Assessment");
            $mpdf->SetAuthor("Sunesis");
            $mpdf->SetCreator("Sunesis");
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
					<td width = "35%" align="left" style="font-size: 10px">Learning Style Self Assessment</td>
					<td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;
            //Beginning Buffer to save PHP variables and HTML tags
            ob_start();

            $learning_style_form_data = json_decode($assessment->form_data);
            $_answer_a = 0;
            $_answer_b = 0;
            $_answer_c = 0;

            foreach ($learning_style_form_data as $_key => $_value) {
                if (substr($_key, 0, 9) == 'question_') {
                    if ($_value == 'a')
                        $_answer_a++;
                    elseif ($_value == 'b')
                        $_answer_b++;
                    elseif ($_value == 'c')
                        $_answer_c++;
                }
            }

            echo <<<HTML
<div style="text-align: center;">
    <h2><strong>Learning Style Self-Assessment</strong></h2>
</div>
<p><br></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th style="color: #000; background-color: #d2d6de !important">Learner Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td>
        </tr>
    </table>
</div>
<p><br></p>
<div style="text-align: left;">
    <table border="1" style="width: 100%;" cellpadding="6">

HTML;

            $result = DAO::getResultset($link, "SELECT * FROM lookup_learning_style_assessment ORDER BY id", DAO::FETCH_ASSOC);
            foreach ($result as $row) {
                $question_id = 'question_' . $row['id'];
                $question_options = [
                    ['a', $row['opt_a'], ''],
                    ['b', $row['opt_b'], ''],
                    ['c', $row['opt_c'], ''],
                ];
                echo '<tr>';
                echo '<td>';
                echo '<p style="color: blue;"><strong>' . $row['question'] . '</strong></p><br>';
                echo '<table border="1" style="width: 100%; margin-left: 10px;" cellpadding="6">';
                foreach ($question_options as $opt) {
                    $checked = (isset($learning_style_form_data->$question_id) && $learning_style_form_data->$question_id == $opt[0]) ? '<img style="width:15px; height:15px;" src="./images/check.jpg" /> ' : '';
                    echo '<tr>';
                    echo '<td>' . strtoupper($opt[0]) . ')</td>';
                    echo '<td>';
                    echo (isset($learning_style_form_data->$question_id) && $learning_style_form_data->$question_id == $opt[0]) ? '<strong>' : '';
                    echo $checked . ' &nbsp; ' . $opt[1];
                    echo (isset($learning_style_form_data->$question_id) && $learning_style_form_data->$question_id == $opt[0]) ? '</strong>' : '';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</td>';
                echo '</tr>';
            }

            echo <<<HTML
    </table>
</div>

HTML;
            if ($_answer_a > $_answer_b && $_answer_a > $_answer_c) {
                echo '<p style="color: blue">Based on assessment the learning style of the learner is: <strong>VISUAL</strong></p>';
            } elseif ($_answer_b > $_answer_a && $_answer_b > $_answer_c) {
                echo '<p style="color: blue">Based on assessment the learning style of the learner is: <strong>AUDITORY</strong></p>';
            } elseif ($_answer_c > $_answer_a && $_answer_c > $_answer_b) {
                echo '<p style="color: blue">Based on assessment the learning style of the learner is: <strong>KINAESTHETIC </strong></p>';
            } elseif ($_answer_a > $_answer_b && $_answer_a == $_answer_c) {
                echo '<p class="text-info">Based on assessment the learning style of the learner is: <span class="text-bold lead">VISUAL & KINAESTHETIC </span></p>';
            } elseif ($_answer_a == $_answer_b && $_answer_a > $_answer_c) {
                echo '<p class="text-info">Based on assessment the learning style of the learner is: <span class="text-bold lead">VISUAL & AUDITORY </span></p>';
            } elseif ($_answer_b > $_answer_a && $_answer_b == $_answer_c) {
                echo '<p class="text-info">Based on assessment the learning style of the learner is: <span class="text-bold lead">AUDITORY & KINAESTHETIC </span></p>';
            }


            $learner_sign_date = isset($assessment->learner_sign_date) ? Date::toShort($assessment->learner_sign_date) : '';

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
    </table>
</div>
HTML;

            $html = ob_get_contents();

            $mpdf->SetHTMLFooter($footer);
            ob_end_clean();

            $mpdf->WriteHTML($html);

            // $mpdf->Output('IAG File', 'I');

            $mpdf->Output($learn_style_assessment_file, 'F');
        }
    }

    public static function commitmentStatementPdf(PDO $link, TrainingRecord $tr, $with_prices = '')
    {
        $ob_learner = $tr->getObLearnerRecord($link);

        if ($tr->learner_sign == '' || $tr->emp_sign == '' || $tr->tp_sign == '') {
            return;
        }

        $onboarding_dir = $tr->getDirectoryPath() . 'onboarding/';
        if (!is_dir($onboarding_dir)) {
            mkdir("$onboarding_dir", 0777, true);
        }

        $learner_signature_file = $onboarding_dir . 'learner_sign_image.png';
        if (!is_file($learner_signature_file)) {
            self::generateSignImages($tr->learner_sign, $onboarding_dir, $learner_signature_file);
        }

        $provider_signature_file = $onboarding_dir . 'provider_sign_image.png';
        if (!is_file($provider_signature_file)) {
            self::generateSignImages($tr->tp_sign, $onboarding_dir, $provider_signature_file);
        }

        $employer_signature_file = $onboarding_dir . 'employer_sign_image.png';
        if (!is_file($employer_signature_file)) {
            self::generateSignImages($tr->emp_sign, $onboarding_dir, $employer_signature_file);
        }

        $c_file = $onboarding_dir . OnboardingHelper::TRAINING_PLAN_PDF_NAME;

        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if (!is_file($c_file) || in_array("CS", $trGeneratePdfs)) {
            include_once("OnboardingDocuments/TrainingPlan.php");

            $c_file = in_array("CS", $trGeneratePdfs) ? $onboarding_dir . 'Training Plan_' . uniqid() . '.pdf' : $c_file;

            TrainingPlan::toPdf($link, $tr, $c_file, $learner_signature_file, $provider_signature_file, $employer_signature_file);
        }
    }

    public static function enrolmentFormPdf(PDO $link, TrainingRecord $tr)
    {
        $ob_learner = $tr->getObLearnerRecord($link);

        if ($tr->learner_sign == '' || $tr->tp_sign == '') {
            return;
        }

        $onboarding_dir = $tr->getDirectoryPath() . 'onboarding/';
        if (!is_dir($onboarding_dir)) {
            mkdir("$onboarding_dir", 0777, true);
        }

        $learner_signature_file = $onboarding_dir . 'learner_sign_image.png';
        if (!is_file($learner_signature_file)) {
            self::generateSignImages($tr->learner_sign, $onboarding_dir, $learner_signature_file);
        }

        $provider_signature_file = $onboarding_dir . 'provider_sign_image.png';
        if (!is_file($provider_signature_file)) {
            self::generateSignImages($tr->tp_sign, $onboarding_dir, $provider_signature_file);
        }

        $c_file = $onboarding_dir . OnboardingHelper::EROLMENT_FORM_PDF_NAME;

        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if (!is_file($c_file) || in_array("EF", $trGeneratePdfs)) {
            include_once("OnboardingDocuments/EnrolmentForm.php");

            $c_file = in_array("EF", $trGeneratePdfs) ? $onboarding_dir . 'Enrolment Form_' . uniqid() . '.pdf' : $c_file;

            EnrolmentForm::toPdf($link, $tr, $c_file, $learner_signature_file, $provider_signature_file);
        }
    }

    public static function evidenceOfEmploymentPdf(PDO $link, TrainingRecord $tr)
    {
        if ($tr->emp_sign == '') {
            return;
        }

        $onboarding_dir = $tr->getDirectoryPath() . 'onboarding/';
        if (!is_dir($onboarding_dir)) {
            mkdir("$onboarding_dir", 0777, true);
        }

        $learner_signature_file = $onboarding_dir . 'learner_sign_image.png';
        if (!is_file($learner_signature_file)) {
            self::generateSignImages($tr->learner_sign, $onboarding_dir, $learner_signature_file);
        }

        $employer_signature_file = $onboarding_dir . 'emp_sign_image.png';
        if (!is_file($employer_signature_file)) {
            self::generateSignImages($tr->emp_sign, $onboarding_dir, $employer_signature_file);
        }

        $app_agreement_file = $onboarding_dir . OnboardingHelper::EVIDENCE_EMPLOYMENT_PDF_NAME;

        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if (true || !is_file($app_agreement_file) || in_array("EE", $trGeneratePdfs)) {
            $app_agreement_file = in_array("EE", $trGeneratePdfs) ? $onboarding_dir . 'Evidence of Employment Statement_' . uniqid() . '.pdf' : $app_agreement_file;

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
					<td width = "50%" align="right"><img class="img-responsive" src="$logo" height="1.50cm" /></td>
				</tr>
			</table>
		</div>

HEADER;

            $version = '2022-2023';
            if ($tr->practical_period_start_date >= '2023-08-01' && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link))) {
                $version = '2023-2024';
            }
            $mpdf->SetHTMLHeader($header);
            $sunesis_stamp = md5('ghost' . date('d/m/Y') . $tr->id);
            $sunesis_stamp = substr($sunesis_stamp, 0, 10);
            $date = date('d/m/Y H:i:s');
            $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "35%" align="left" style="font-size: 10px">Employment Statement v1</td>
					<td width = "35%" align="left" style="font-size: 10px">{$version}</td>
					<td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;
            //Beginning Buffer to save PHP variables and HTML tags
            ob_start();

            $framework = Framework::loadFromDatabase($link, $tr->framework_id);
            $ob_learner = $tr->getObLearnerRecord($link);
            $employer = Organisation::loadFromDatabase($link, $tr->employer_id);

            $standard_title = $framework->getStandardCodeDesc($link);
            $standard_level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");
            $practical_period_start_date = Date::toShort($tr->practical_period_start_date);
            $practical_period_end_date = Date::toShort($tr->practical_period_end_date);
            $apprenticeship_start_date = Date::toShort($tr->apprenticeship_start_date);
            $apprenticeship_end_date_inc_epa = Date::toShort($tr->apprenticeship_end_date_inc_epa);

            $employer_location = '';
            $location = Location::loadFromDatabase($link, $tr->employer_location_id);
            $employer_location .= $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : '';
            $employer_location .= $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : '';
            $employer_location .= $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : '';
            $employer_location .= $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : '';
            $employer_location .= $location->postcode != '' ? $location->postcode . '<br>' : '';

            $planned_otj_hours = 0;
            $tch = 0;
            if ($tr->contracted_hours_per_week >= 30) {
                $planned_otj_hours = $tr->underSixHoursPerWeekRule() ? $tr->off_the_job_hours_based_on_duration : $tr->minimum_percentage_otj_training;
                $tch = $tr->total_contracted_hours_full_apprenticeship;
            } else {
                $planned_otj_hours = $tr->part_time_otj_hours;
                $tch = $tr->part_time_total_contracted_hours_full_apprenticeship;
            }

            if ($tr->otj_overwritten != '') {
                $planned_otj_hours = $tr->otj_overwritten;
            }

            echo <<<HTML
<div style="text-align: center;">
    <h2><strong>Evidence of Employment Statement</strong></h2>
    <br>
    <p>I, <strong>{$tr->emp_sign_name}</strong> can confirm that <strong>{$ob_learner->firstnames} {$ob_learner->surname}</strong> is an employee of 
    <strong>{$employer->legal_name}</strong> and the details provided below are accurate.</p>
</div>
<br>


HTML;

            $extra_info = DAO::getObject($link, "SELECT * FROM ob_learner_extra_details WHERE tr_id = '{$tr->id}'");
            $employment_contract = isset($extra_info->employment_contract) ? $extra_info->employment_contract : '';
            $employment_start_date = isset($extra_info->employment_start_date) ? Date::toShort($extra_info->employment_start_date) : '';

            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Name of Apprentice:</th><td>$ob_learner->firstnames $ob_learner->surname</td></tr>
        <tr><th>National Insurance Number:</th><td>$ob_learner->ni</td></tr>
        <tr><th>Employment Contract:</th><td>$employment_contract</td></tr>
        <tr><th>Employment Start Date:</th><td>$employment_start_date</td></tr>
        <tr><th>Apprenticeship Standard and Level:</th><td>$standard_title $standard_level</td></tr>
        <tr><th>Apprenticeship Start Date:</th><td>$apprenticeship_start_date</td></tr>
        <tr><th>Apprenticeship Practical Planned End Period:</th><td>$practical_period_end_date</td></tr>
        <tr><th>Apprenticeship EPA Planned End Date:</th><td>$apprenticeship_end_date_inc_epa</td></tr>
        <tr><th>Planned Off-the-Job Hours:</th><td>$planned_otj_hours</td></tr>
        <tr><td colspan="2"></td></tr>
        <tr><th>Employer Name:</th><td>$employer->legal_name</td></tr>
        <tr><th>Employer Address:</th><td>$employer_location</td></tr>
              
    </table>
</div>
<p>I can confirm that we expect <strong>{$ob_learner->firstnames} {$ob_learner->surname}</strong> to have continual employment throughout their Apprenticeship and for this to continue through to their 
End Point Assessment.</p>
<p>Apart from redundancy, dismissal or resignation I can see no reason why this Apprenticeship program cannot be supported through to achievement.
</p>

HTML;
            $emp_sign_date = isset($tr->emp_sign_date) ? Date::toShort($tr->emp_sign_date) : '';
            $learner_sign_date = isset($tr->learner_sign_date) ? Date::toShort($tr->learner_sign_date) : '';
            $learner_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM ob_learners WHERE id = '{$tr->ob_learner_id}'");
            $learner_rows = '';
            //if($tr->id == 1038)
            {
                $learner_rows = '<tr>';
                $learner_rows .= '<td>Learner</td>';
                $learner_rows .= '<td>' . $learner_name . '</td>';
                $learner_rows .= '<td><img src="' . $learner_signature_file . '" style="border: 2px solid;border-radius: 15px;" /></td>';
                $learner_rows .= '<td>' . $learner_sign_date . '</td>';
                $learner_rows .= '</tr>';
            }

            echo <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="4" class="bg-blue">Signatures</th></tr>
        <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
	$learner_rows
        <tr>
            <td>Employer</td>
            <td>{$tr->emp_sign_name}</td>
            <td><img src="$employer_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$emp_sign_date}</td>
        </tr>
    </table>
</div>
HTML;

            $html = ob_get_contents();

            $mpdf->SetHTMLFooter($footer);
            ob_end_clean();

            $mpdf->WriteHTML($html);

            // $mpdf->Output('AA', 'I');
            $app_agreement_file = $tr->getDirectoryPath() . '/Evidence of Employment.pdf';
            $mpdf->Output($app_agreement_file, 'F');
        }
    }

    public static function alsPdf(PDO $link, TrainingRecord $tr)
    {
        $als_entry = DAO::getObject($link, "SELECT * FROM ob_learner_additional_support WHERE tr_id = '{$tr->id}'");
        if (!isset($als_entry->tr_id)) {
            return;
        }
        if ($als_entry->learner_sign == '' || $als_entry->provider_sign == '') {
            return;
        }

        $tempDir = sys_get_temp_dir() . '/';

        $learner_signature_file = $tempDir . md5($als_entry->tr_id . '_als_entry_learner_sign_' . time()) . '.png';
        if (!is_file($learner_signature_file)) {
            self::generateSignImages($als_entry->learner_sign, $tempDir, $learner_signature_file);
        }
        $provider_signature_file = $tempDir . md5($als_entry->tr_id . '_als_entry_provider_sign_' . time()) . '.png';
        if (!is_file($provider_signature_file)) {
            self::generateSignImages($als_entry->provider_sign, $tempDir, $provider_signature_file);
        }

        $als_file = $tempDir . OnboardingHelper::ALS_PDF_NAME;

        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if (true || !is_file($als_file) || in_array("AL", $trGeneratePdfs)) {
            $als_file = in_array("AL", $trGeneratePdfs) ? $tempDir . 'ALS_' . uniqid() . '.pdf' : $als_file;

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
					<td width = "50%" align="right"><img class="img-responsive" src="$logo" height="1.50cm" /></td>
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
					<td width = "35%" align="left" style="font-size: 10px"></td>
					<td width = "35%" align="left" style="font-size: 10px"></td>
					<td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;
            //Beginning Buffer to save PHP variables and HTML tags
            ob_start();

            $framework = Framework::loadFromDatabase($link, $tr->framework_id);
            $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
            $ob_learner = $tr->getObLearnerRecord($link);

            $form_data = is_null($als_entry->form_data) ? null : json_decode($als_entry->form_data);

            $table1Rows = '';
            $als_total_yes = 0;
            $als_total_no = 0;
            $funding_year = 2023;
            if (
                $tr->practical_period_start_date > '2024-05-31' ||
                (isset($form_data->funding_year) && $form_data->funding_year == 2024) // this is if 2024 info is saved. 
            ) {
                if (!in_array($tr->id, [2149, 2159, 2160, 2180]))
                    $funding_year = 2024;
            }
            $questions = DAO::getResultset($link, "SELECT * FROM lookup_questions_als WHERE year = '{$funding_year}' AND version = 1 AND tbl_group = 1", DAO::FETCH_ASSOC);
            foreach ($questions as $question) {
                $answer_id = 'answer' . $question['id'];
                $comments_id = 'comments' . $question['id'];
                $table1Rows .= '<tr>';
                $table1Rows .= '<th>' . $question['question'] . '</th>';
                $table1Rows .= '<td>' . (isset($form_data->$answer_id) ? $form_data->$answer_id : '') . '</td>';
                $table1Rows .= '<td>' . (isset($form_data->$comments_id) ? $form_data->$comments_id : '') . '</td>';
                $table1Rows .= '</tr>';
                if (isset($form_data->$answer_id) && $form_data->$answer_id == 'Yes') {
                    $als_total_yes++;
                }
                if (isset($form_data->$answer_id) && $form_data->$answer_id == 'No') {
                    $als_total_no++;
                }
            }
            $agreeToReferral = isset($form_data->learnerAgreeT1) ? $form_data->learnerAgreeT1 : '';

            $table2Rows = '';
            $questions = DAO::getResultset($link, "SELECT * FROM lookup_questions_als WHERE year = '{$funding_year}' AND version = 1 AND tbl_group = 2", DAO::FETCH_ASSOC);
            foreach ($questions as $question) {
                $t2_answer_id = 't2_answer' . $question['id'];
                $t2_comments_id = 't2_comments' . $question['id'];
                $table2Rows .= '<tr>';
                $table2Rows .= '<th>' . $question['question'] . '</th>';
                $table2Rows .= '<td>' . (isset($form_data->$t2_answer_id) ? $form_data->$t2_answer_id : '') . '</td>';
                $table2Rows .= $funding_year == 2023 ? '<td>' . $question['action'] . '</td>' : '<td>' . (isset($form_data->$t2_comments_id) ? $form_data->$t2_comments_id : '') . '</td>';
                $table2Rows .= '</tr>';
            }

            echo <<<HTML
<div style="text-align: center;">
    <h2><strong>Learning Support / Additional Details</strong></h2>
</div>
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Learner Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td></tr>
        <tr><th>Employer Name:</th><td>$employer->legal_name</td></tr>
        <tr><th>Standard:</th><td>$framework->title</td></tr>
    </table>    
</div>
<br>
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Question</th><th>Yes/No</th><th>Comments</th></tr>
        $table1Rows
        <tr>
            <th colspan="3">
                <span class="text-bold">Total Score: </span><span class="text-lg text-info">{$als_total_yes}/{$als_total_no}</span><br>
                <span class="text-bold">Number of 'Yes': </span><span class="text-lg text-info">{$als_total_yes}</span><br>
                <span class="text-bold">Does the learner agree to a referral?: </span>
                <span class="text-lg text-info">{$agreeToReferral}</span><br>
            </th>
        </tr>
    </table>    
</div>
<br>
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Question</th><th>Yes/No</th><th>Action</th></tr>
        $table2Rows
    </table>    
</div>

HTML;

            $learner_sign_date = isset($als_entry->learner_sign_date) ? Date::toShort($als_entry->learner_sign_date) : '';
            $provider_sign_date = isset($als_entry->provider_sign_date) ? Date::toShort($als_entry->provider_sign_date) : '';

            echo <<<HTML
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
<tr><th colspan="4" class="bg-blue">Signatures</th></tr>
<tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
<tr>
<td>Learner</td>
<td>{$ob_learner->firstnames} {$ob_learner->surname}</td>
<td><img src="$learner_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
<td>{$learner_sign_date}</td>
</tr>
<tr>
<td>Provider</td>
<td>{$als_entry->provider_sign_name}</td>
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

            // $mpdf->Output('AA', 'I');
            $als_file = $tr->getDirectoryPath() . OnboardingHelper::ALS_PDF_NAME;
            $mpdf->Output($als_file, 'F');
        }
    }

    public static function fdilPdf(PDO $link, TrainingRecord $tr)
    {
        $fdil_entry = DAO::getObject($link, "SELECT * FROM ob_learner_fdil WHERE tr_id = '{$tr->id}'");
        if (!isset($fdil_entry->tr_id)) {
            return;
        }
        if ($fdil_entry->learner_sign == '' || $fdil_entry->tutor_sign == '') {
            return;
        }

        $onboarding_dir = $tr->getDirectoryPath() . 'onboarding/';
        if (!is_dir($onboarding_dir)) {
            mkdir("$onboarding_dir", 0777, true);
        }

        $learner_signature_file = $onboarding_dir . 'learner_sign_image.png';
        if (!is_file($learner_signature_file)) {
            self::generateSignImages($fdil_entry->learner_sign, $onboarding_dir, $learner_signature_file);
        }
        $tutor_signature_file = $onboarding_dir . 'tutor_sign_image.png';
        if (!is_file($tutor_signature_file)) {
            self::generateSignImages($fdil_entry->tutor_sign, $onboarding_dir, $tutor_signature_file);
        }

        $fdil_file = $onboarding_dir . OnboardingHelper::FDIL_PDF_NAME;

        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if (true || !is_file($fdil_file) || in_array("FD", $trGeneratePdfs)) {
            $fdil_file = in_array("FD", $trGeneratePdfs) ? $onboarding_dir . 'FDIL_' . uniqid() . '.pdf' : $fdil_file;

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
					<td width = "50%" align="right"><img class="img-responsive" src="$logo" height="1.50cm" /></td>
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
					<td width = "35%" align="left" style="font-size: 10px">FDIL v1</td>
					<td width = "35%" align="left" style="font-size: 10px">2022-2023</td>
					<td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;
            //Beginning Buffer to save PHP variables and HTML tags
            ob_start();

            $framework = Framework::loadFromDatabase($link, $tr->framework_id);
            $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
            $ob_learner = $tr->getObLearnerRecord($link);

            echo <<<HTML
<div style="text-align: center;">
    <h2><strong>Session Attendance, Review & Evaluation</strong></h2>
    <br>
</div>
<br>


HTML;

            $fdil_session_date = isset($fdil_entry->fdil_session_date) ? Date::toShort($fdil_entry->fdil_session_date) : '';
            $fdil_learner_comments = isset($fdil_entry->learner_comments) ? nl2br($fdil_entry->learner_comments) : '';
            $funding_year = DAO::getSingleValue($link, "SELECT contract_year FROM central.lookup_submission_dates WHERE '{$tr->practical_period_start_date}' BETWEEN start_submission_date AND last_submission_date ORDER BY contract_year DESC LIMIT 1");
            if (in_array($tr->id, OnboardingHelper::UlnsToSkip($link))) {
                $funding_year = '';
            }
            if ($tr->practical_period_start_date > '2024-03-22') {
                $funding_year = '2024';
            }
            $functional_skills_text = '';
            if ($funding_year == 2023) {
                $functional_skills_text = <<<TEXT
                <p><strong>Functional Skills</strong></p>
                <p>During this teaching session, you used speaking, listening and communication skills including outcomes 1, 2, 3, 4, 6 and 8. 
                    This was done by making requests to obtain information, responding effectively to questions, following, and understanding 
                    discussions, and respecting the rights of others. You have also followed main points and details (L2.2.11) and written 
                    notes with specialist words (L3.3.27)</p>
                <p>We have looked at how to calculate the percentage of an amount (L2.N5) where you have calculated 20% of your off the job 
                    hours and explained how to decrease it from the amount required. (L2.N6)</p>
TEXT;
            }

            if ($funding_year == 2023) {
                if (in_array($framework->id, [2, 8, 11, 18, 6, 9, 17])) {
                    $fdil_page_content = DAO::getSingleValue($link, "SELECT fdil_page_content FROM frameworks_fdil_templates WHERE framework_id = '{$framework->id}' AND year = '2023' AND version = '1'");
                } else {
                    $fdil_page_content = $framework->fdil_page_content;
                }
            } elseif ($funding_year == 2024) {
                $fdil_page_content = DAO::getSingleValue($link, "SELECT fdil_page_content FROM frameworks_fdil_templates WHERE framework_id = '{$framework->id}' AND year = '2024' AND version = '1'");
            } else {
                $fdil_page_content = $framework->fdil_page_content;
            }

            echo <<<HTML
<p></p>
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Employer Name:</th><td>$employer->legal_name</td></tr>
        <tr><th>Learner Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td></tr>
    </table>    
    <br>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th>Date:</th><td>$fdil_session_date</td>
            <th>Start Time:</th><td>$fdil_entry->fdil_session_start_time</td>
            <th>End Time:</th><td>$fdil_entry->fdil_session_end_time</td>
            <th>Number of Hours:</th><td>$fdil_entry->fdil_session_hours</td>
        </tr>
    </table>    
    <br>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr>
            <th>Framework:</th><td>$framework->title</td>
            <th>Session Location:</th><td>REMOTE</td>
            <th>Trainer Name:</th><td>$fdil_entry->fdil_trainer_name</td>
        </tr>
    </table>    
    <br>
    
    <h3>Session Objective:</h3>
    $fdil_page_content
    <br>
    $functional_skills_text
    <br>    
    <hr>
    <h3>How I found today's session:</h3>
    $fdil_learner_comments

</div>


HTML;

            $learner_sign_date = isset($fdil_entry->learner_sign_date) ? Date::toShort($fdil_entry->learner_sign_date) : '';
            $tutor_sign_date = isset($fdil_entry->tutor_sign_date) ? Date::toShort($fdil_entry->tutor_sign_date) : '';

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
<td>Tutor</td>
<td>{$fdil_entry->tutor_sign_name}</td>
<td><img src="$tutor_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
<td>{$tutor_sign_date}</td>
</tr>
</table>
</div>
HTML;

            $html = ob_get_contents();

            $mpdf->SetHTMLFooter($footer);
            ob_end_clean();

            $mpdf->WriteHTML($html);

            // $mpdf->Output('AA', 'I');
            $fdil_file = $tr->getDirectoryPath() . '/FDIL.pdf';
            $mpdf->Output($fdil_file, 'F');
        }
    }

    public static function generatePlrPdf(PDO $link, $tr_id)
    {
        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $ob_learner = $tr->getObLearnerRecord($link);

        $mpdf = new \Mpdf\Mpdf(['format' => 'A4-L', 'default_font_size' => 10]);
        $mpdf->setAutoBottomMargin = 'stretch';

        $sunesis_stamp = md5('ghost' . date('d/m/Y') . $tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);
        $date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
    <div>
        <table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
            <tr>
                <td width = "35%" align="left" style="font-size: 10px">PLR of {$ob_learner->firstnames} {$ob_learner->surname}</td>
                <td width = "35%" align="left" style="font-size: 10px">Printed on $date</td>
                <td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
            </tr>
        </table>
    </div>
HEREDOC;

        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        $dob = Date::to($ob_learner->dob, Date::MEDIUM);

        echo <<<HTML
<div style="text-align: left;">
<h2><strong>Personal Learning Record</strong></h2>
<h3>$ob_learner->firstnames $ob_learner->surname</h3>
<h3>$dob</h3>
<br>
</div>
<br>

HTML;

        $plr_records = DAO::getResultset($link, "SELECT * FROM lrs_learner_learning_events WHERE tr_id = '{$tr->id}' AND sunesis_core = 0", DAO::FETCH_ASSOC);
        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="9" style="color: #000; background-color: #d2d6de !important"><strong>Learning Events (' . count($plr_records) . ')</strong></th></tr>';
        echo '<tr>';
        echo '<th>Provider</th>';
        echo '<th>Awarding Body</th>';
        echo '<th>Type</th>';
        echo '<th>Reference Number</th>';
        echo '<th>Aim Description</th>';
        echo '<th>Start Date</th>';
        echo '<th>End Date</th>';
        echo '<th>Grade</th>';
        echo '<th>Award Date</th>';
        echo '</tr>';
        foreach ($plr_records as $plr_record) {
            echo '<tr>';
            echo '<td>' . $plr_record['AchievementProviderName'] . '</td>';
            echo '<td>' . $plr_record['AwardingOrganisationName'] . '</td>';
            echo '<td>' . $plr_record['QualificationType'] . '</td>';
            echo '<td>' . $plr_record['SubjectCode'] . '</td>';
            echo '<td>' . $plr_record['Subject'] . '</td>';
            echo '<td>' . Date::toShort($plr_record['ParticipationStartDate']) . '</td>';
            echo '<td>' . Date::toShort($plr_record['ParticipationEndDate']) . '</td>';
            echo '<td>' . $plr_record['Grade'] . '</td>';
            echo '<td>' . Date::toShort($plr_record['AchievementAwardDate']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';

        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();

        $mpdf->WriteHTML($html);

        $mpdf->Output('PLR File.pdf', 'D');
        // $fdil_file = $tr->getDirectoryPath() . '/FDIL.pdf';
        // $mpdf->Output($fdil_file, 'F');


    }
}
