<?php
class ApprenticeshipAgreement 
{
    public static function toPdf(PDO $link, TrainingRecord $tr, $app_agreement_file, $learner_signature_file, $employer_signature_file)
    {
        if(DB_NAME == "am_crackerjack")
        {
            self::apprenticeshipAgreementPdfCj($link, $tr, $app_agreement_file, $learner_signature_file, $employer_signature_file);
            return;
        }
		else
        {
            self::apprenticeshipAgreementPdfDemo($link, $tr, $app_agreement_file, $learner_signature_file, $employer_signature_file);
            return;
        }

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

		$employer = Employer::loadFromDatabase($link, $tr->employer_id);
        if(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]))
        {
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
                <td width = "50%" align="right"><img class="img-responsive" src="$logo" height="1.50cm" /></td>
            </tr>
        </table>
    </div>

HEADER;

        $mpdf->SetHTMLHeader($header);
        $sunesis_stamp = md5('ghost'.date('d/m/Y').$tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);
        $date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
    <div>
        <table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
            <tr>
                <td width = "30%" align="left" style="font-size: 10px">{$date}</td>
                <td width = "35%" align="left" style="font-size: 10px">Apprenticeship Agreement</td>
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
        //$employer = Organisation::loadFromDatabase($link, $tr->employer_id);
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $sub_legal =$tr->getSubcontractorLegalName($link);
        $subcontractor_name = $sub_legal != '' ? $sub_legal : 'NA';
        $standard_title = $framework->getStandardCodeDesc($link);
        $standard_level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");
        $practical_period_start_date = Date::toShort($tr->practical_period_start_date);
        $practical_period_end_date = Date::toShort($tr->practical_period_end_date);
        $apprenticeship_start_date = Date::toShort($tr->apprenticeship_start_date);
        $apprenticeship_end_date_inc_epa = Date::toShort($tr->apprenticeship_end_date_inc_epa);

        $planned_otj_hours = 0;
        $tch = 0;
        if($tr->contracted_hours_per_week >= 30)
        {
            $planned_otj_hours = $tr->underSixHoursPerWeekRule() ? $tr->off_the_job_hours_based_on_duration : $tr->minimum_percentage_otj_training;
            $tch = $tr->total_contracted_hours_full_apprenticeship;
        }
        else
        {
            $planned_otj_hours = $tr->part_time_otj_hours;
            $tch = $tr->part_time_total_contracted_hours_full_apprenticeship;
        }
	if($tr->otj_overwritten != '')
        {
            $planned_otj_hours = $tr->otj_overwritten;
        }

        echo <<<HTML
<div style="text-align: center;">
<h2><strong>Apprenticeship Agreement</strong></h2>
</div>
<br>

HTML;
        if(DB_NAME == "am_ela")
        {
            echo <<<HTML
<div style="font-size: 11px;">
<ol>
    <li>
        <strong>The apprenticeship agreement:</strong><br>
        <p>The apprenticeship agreement is a statutory requirement for the employment of an apprentice in connection with an approved apprenticeship standard. It forms part of the individual employment arrangements between the apprentice and the employer; it is a contract of service (i.e. a contract of employment) and not a contract of apprenticeship. If all the requirements of section 1 of the Employment Rights Act 1996 are complied with, the apprenticeship agreement can also serve as the written statement of particulars of employment. You are not required to use this template, but the requirements of the legislation as described below must be met when you form your apprenticeship agreement.</p>
    </li>
    <li>
        <strong>Why an apprenticeship agreement is required</strong><br>
        <p>The Apprenticeships, Skills, Children and Learning Act 2009 (ASCLA) introduced the requirement for an apprenticeship agreement to be in place when engaging an apprentice under a statutory apprenticeship. The requirements for an apprenticeship agreement can be found in section A1 of ASCLA and the Apprenticeships (Miscellaneous Provisions) Regulations 2017.</p>
    </li>
    <li>
        <strong>When the apprenticeship agreement must be in place</strong><br>
        <p>An apprenticeship agreement must be in place when an individual starts a statutory apprenticeship programme and should remain in place throughout the apprenticeship. The end date is when the end-point assessment is due to be completed.</p>
    </li>
    <li>
        <strong>The 'practical period'</strong><br>
        <p>The practical period is the period for which an apprentice is expected to work and receive training under an approved English apprenticeship agreement. The practical period does not include the end-point assessment. For the purpose of meeting the Education and Skills Funding Agency funding requirements, the practical period start date set out in the apprenticeship agreement must match the practical period start date in the training plan and the start date in the Individual Learner Record.</p>
    </li>
    <li>
        <strong>In certain circumstances, an apprenticeship can be completed without an apprenticeship agreement being in place</strong><br>
        <p>To commence a statutory apprenticeship (when an individual starts their apprenticeship programme) it is a legal requirement that an apprenticeship agreement be in place. The two circumstances in which an apprentice can complete a statutory apprenticeship without an apprenticeship agreement are where (i) they are holding office as an apprentice police constable, or as an apprentice minister of a religious organisation; or (ii) where they have been made redundant with less than six months of their apprenticeship's practical period left to run (see regulation 6 of the Apprenticeships (Miscellaneous Provisions) Regulations 2017).</p>
    </li>
    <li>
        <strong>Who needs to sign the apprenticeship agreement?</strong><br>
        <p>The employer and the apprentice need to sign the agreement - it is an agreement between these two parties only. Training providers sign a separate training plan which outlines the planned content and schedule for training, what is expected of and offered by the employer, provider and the apprentice, and how to resolve queries or complaints.</p>
    </li>
    <li>
        <strong>What you need to do with the signed agreement</strong><br>
        <p>You (the employer) must keep the agreement for the duration of the apprenticeship and give a copy to the apprentice and the training provider.</p>
    </li>
    <li>
        <strong>Information needed in an apprenticeship agreement</strong><br>
        <p>The apprenticeship agreement must comply with the requirements as provided in ASCLA. It must:</p>
        <ul>
            <li>provide for the apprentice to work for the employer for reward in an occupation for which a standard has been published by the Institute for Apprenticeships and Technical Education;</li>
            <li>provide for the apprentice to receive training in order to assist the apprentice to achieve the standard in the work done under the agreement;</li>
            <li>specify the apprenticeship's practical period; and</li>
            <li>specify the amount of off-the-job training the apprentice is to receive.</li>
        </ul>
    </li>
    <li>
        <strong>Specifying the amount of off-the-job training</strong><br>
        <p>This is a requirement of the Apprenticeships (Miscellaneous Provisions) Regulations 2017. Off-the-job training is a critical requirement of apprenticeships and, in order to meet the Education and Skills Funding Agency's funding rules, this must be at least 20% of the apprentice's normal working hours over the total duration of the apprenticeship (until gateway). Off-the-job training can only be received by an apprentice during their normal working hours. Maths and English, up to and including level 2, does not count towards the minimum 20% off-the-job training requirement. The amount of off-the-job training should be agreed with the main provider. The provider must account for relevant prior learning the apprentice has received and reduce the content and duration of off-the-job training as necessary to achieve occupational competence. All apprenticeships must be of minimum duration of 12 months and include at least 20% off-the-job training.</p>
    </li>
    <li>
        <strong>Off-the-job training definition</strong><br>
        <p>Off-the-job training is defined as training which is received by the apprentice, during the apprentice's normal working hours, for the purpose of achieving the standard connected to the apprenticeship. It is not on the job training received by the apprentice for the sole purpose of enabling the apprentice to perform the work to which the apprenticeship agreement relates. More information, including examples of off-the-job training, can be found on gov.uk</p>
    </li>
    <li>
        <strong>The apprenticeship agreement does not mean a change to existing contracts or terms and conditions</strong><br>
        <p>Any apprenticeship entered into before 15 January 2018 (the date the Apprenticeships (Miscellaneous Provisions) Regulations 2017 came into force) will not be affected by the additional requirements that must be set out in an apprenticeship agreement. Any apprenticeship entered into after 15 January 2018 in connection with an apprenticeship standard must satisfy the requirements of the 2017 Regulations.</p>
    </li>
</ol>
</div>

HTML;
        }
        else
        {
            echo <<<HTML
<div class="well">
<p>An apprenticeship agreement must be in place at the start of the apprenticeship. The purpose of the apprenticeship agreement is to identify:</p>
<ul style="margin-left: 25px;">
    <li>the skill, trade or occupation for which the apprentice is being trained;</li>
    <li>the apprenticeship standard or framework connected to the apprenticeship;</li>
    <li>the dates during which the apprenticeship is expected to take place; and</li>
    <li>the amount of off the job training that the apprentice is to receive.</li>
</ul>
<p></p>
<p>The apprenticeship agreement is a statutory requirement for the employment of an apprentice in connection with an approved apprenticeship standard.
    It forms part of the individual employment arrangements between the apprentice and the employer;
    it is a contract of service (i.e. a contract of employment) and not a contract of apprenticeship.</p>
<p></p>
<p>For further information, please see the explanatory notes and references before completing.</p>
</div>

HTML;
        }

        $type_of_funding = '';
        if($employer->funding_type == 'L')
        {
            $type_of_funding = 'Levy (DAS) Account';
        }
        elseif($employer->funding_type == 'CO')
        {
            $type_of_funding = 'Co-Investment';
        }
        elseif($employer->funding_type == 'LG')
        {
            $type_of_funding = 'Levy Gifted';
        }
        $type_of_employer = $employer->levy_employer == '1' ? 'Yes' : 'No';


        echo <<<HTML
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Apprenticeship Details</strong></h4></th></tr>
    <tr><th>Apprentice Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td></tr>
    <tr><th>Apprentice Job Role:</th><td>$tr->job_title</td></tr>
    <tr><th>Employer Name:</th><td>$employer->legal_name</td></tr>
    <tr><th>Funding Type:</th><td>$type_of_funding</td></tr>
    <tr><th>Levy Employer:</th><td>$type_of_employer</td></tr>
    <tr><th>Training Provider Name:</th><td>$provider->legal_name</td></tr>
    <tr><th>Subcontractor Name:</th><td>$subcontractor_name</td></tr>
    <tr><td colspan="2"></td></tr>
    <tr><th>Standard Title:</th><td>$standard_title</td></tr>
    <tr><th>Level:</th><td>$standard_level</td></tr>
    <tr><th>Start Date of Practical Period:</th><td>$practical_period_start_date</td></tr>
    <tr><th>Planned End Date of Practical Period:</th><td>$practical_period_end_date</td></tr>
    <tr><th>Duration of Practical Period - months:</th><td>$tr->duration_practical_period</td></tr>
    <tr><td colspan="2"></td></tr>
    <tr><th>Start Date of Apprenticeship:</th><td>$apprenticeship_start_date</td></tr>
    <tr><th>Planned End date of Apprenticeship (incl EPA):</th><td>$apprenticeship_end_date_inc_epa</td></tr>
    <tr><th>Duration of Full Apprenticeship (incl EPA) - months:</th><td>$tr->apprenticeship_duration_inc_epa</td></tr>
    <tr><td colspan="2"></td></tr>
    <tr><th>Total Contracted Hours full Apprenticeship:</th><td>$tch</td></tr>
    <tr><th>Planned Off-the-Job Hours:</th><td>$planned_otj_hours</td></tr>
</table>
</div>

HTML;
        $learner_sign_date = isset($tr->learner_sign_date) ? Date::toShort($tr->learner_sign_date) : '';
        $emp_sign_date = isset($tr->emp_sign_date) ? Date::toShort($tr->emp_sign_date) : '';
        
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

        $mpdf->Output($app_agreement_file, 'F');

    }

    public static function apprenticeshipAgreementPdfCj(PDO $link, TrainingRecord $tr, $app_agreement_file, $learner_signature_file, $employer_signature_file)
    {
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $ob_learner = $tr->getObLearnerRecord($link);
        $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
        $location = Location::loadFromDatabase($link, $tr->employer_location_id);
        
        $place_of_work = $location->address_line_1 != '' ? ' ' . $location->address_line_1 : '';
        $place_of_work .= $location->address_line_2 != '' ? ' ' . $location->address_line_2 : '';
        $place_of_work .= $location->address_line_3 != '' ? ' ' . $location->address_line_3 : '';
        $place_of_work .= $location->address_line_4 != '' ? ' ' . $location->address_line_4 : '';
        $place_of_work .= $location->postcode != '' ? ' ' . $location->postcode : '';

        $standard_title = $framework->getStandardCodeDesc($link);
        $practical_period_start_date = Date::toShort($tr->practical_period_start_date);
        $practical_period_end_date = Date::toShort($tr->practical_period_end_date);
        $apprenticeship_start_date = Date::toShort($tr->apprenticeship_start_date);
        $apprenticeship_end_date_inc_epa = Date::toShort($tr->apprenticeship_end_date_inc_epa);

        $learner_sign_date = isset($tr->learner_sign_date) ? Date::toShort($tr->learner_sign_date) : '';
        $emp_sign_date = isset($tr->emp_sign_date) ? Date::toShort($tr->emp_sign_date) : '';

        $planned_otj_hours = 0;
        $tch = 0;
        if($tr->contracted_hours_per_week >= 30)
        {
            $planned_otj_hours = $tr->off_the_job_hours_based_on_duration;
            $tch = $tr->total_contracted_hours_full_apprenticeship;
        }
        else
        {
            $planned_otj_hours = $tr->part_time_otj_hours;
            $tch = $tr->part_time_total_contracted_hours_full_apprenticeship;
        }

        if($tr->otj_overwritten != '')
        {
            $planned_otj_hours = $tr->otj_overwritten;
        } 
        else
        {
            $planned_otj_hours = $tr->contracted_hours_per_week >= 30 ? $tr->off_the_job_hours_based_on_duration : $tr->part_time_otj_hours;
        }

        $mpdf = new \Mpdf\Mpdf(['format' => 'Legal', 'default_font_size' => 10]);
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'Legal',
            'default_font_size' => 10,
            'margin_left'   => 15,
            'margin_right'  => 15,
            'margin_top'    => 57,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            'orientation'   => 'P'
        ]);
        //$mpdf->SetImportUse();	
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetCompression(false);
        $pagecount = $mpdf->SetSourceFile('20220810_Apprenticeship_Agreement_Final_v1.0.pdf');
        $tplIdx = $mpdf->ImportPage(1);
        $mpdf->UseTemplate($tplIdx, 10, 10, 200);
        $mpdf->SetTextColor(0, 0, 255);
        $mpdf->SetFont('Arial', 'B', 11);
        $mpdf->Text(112, 135, $ob_learner->firstnames . ' ' . $ob_learner->surname);
        $mpdf->SetFont('Arial', '', 8);
        $mpdf->Text(111, 145, $standard_title);
        $mpdf->Text(112, 151, $employer->legal_name);
        $mpdf->SetFont('Arial', '', 8);
        $mpdf->Text(110, 156, $place_of_work);
        $mpdf->SetFont('Arial', '', 11);
        $mpdf->Text(80, 175, $apprenticeship_start_date);
        $mpdf->Text(170, 175, $apprenticeship_end_date_inc_epa);
        $mpdf->Text(80, 190, $practical_period_start_date);
        $mpdf->Text(170, 190, $practical_period_end_date);
        $mpdf->Text(80, 205, $tr->duration_practical_period);
        $mpdf->Text(170, 205, $planned_otj_hours);

        $mpdf->Image($learner_signature_file, 54, 230, 70);
        $mpdf->Text(150, 236, $learner_sign_date);

        $mpdf->Image($employer_signature_file, 56, 244, 70);
        $mpdf->Text(150, 248, $emp_sign_date);

        $mpdf->AddPage();
        $tplIdx = $mpdf->ImportPage(2);
        $mpdf->UseTemplate($tplIdx, 10, 10, 200);
        $mpdf->AddPage();
        $tplIdx = $mpdf->ImportPage(3);
        $mpdf->UseTemplate($tplIdx, 10, 10, 200);

        $mpdf->Output($app_agreement_file, 'F');

    }
	
	public static function apprenticeshipAgreementPdfDemo(PDO $link, TrainingRecord $tr, $app_agreement_file, $learner_signature_file, $employer_signature_file)
    {        
        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

	    $employer = Employer::loadFromDatabase($link, $tr->employer_id);
        if(in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]))
        {
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
                <td width = "50%" align="right"><img class="img-responsive" src="$logo" height="1.50cm" /></td>
            </tr>
        </table>
    </div>

HEADER;

        $mpdf->SetHTMLHeader($header);
        $sunesis_stamp = md5('ghost'.date('d/m/Y').$tr->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);
        $date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
    <div>
        <table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
            <tr>
                <td width = "30%" align="left"></td>
                <td width = "30%">
                    <img src="images/logos/DfE-logo.jpg" alt="Department for Education" />
                </td>
                <td width = "30%">
                    <img src="images/logos/skills_for_life.png" alt="Skills for Life" />
                </td>
            </tr>
            <tr>
                <td width = "30%" align="left" style="font-size: 10px">{$date}</td>
                <td width = "35%" align="left" style="font-size: 10px">Apprenticeship Agreement V1.0</td>
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
        //$employer = Organisation::loadFromDatabase($link, $tr->employer_id);
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $sub_legal =$tr->getSubcontractorLegalName($link);
        $subcontractor_name = $sub_legal != '' ? $sub_legal : 'NA';
        $standard_title = $framework->standard_ref_no . ' ' . $framework->getStandardCodeDesc($link);
        $standard_level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");
        $practical_period_start_date = Date::toShort($tr->practical_period_start_date);
        $practical_period_end_date = Date::toShort($tr->practical_period_end_date);
        $apprenticeship_start_date = Date::toShort($tr->apprenticeship_start_date);
        $apprenticeship_end_date_inc_epa = Date::toShort($tr->apprenticeship_end_date_inc_epa);

        $planned_otj_hours = 0;
        $tch = 0;
        if($tr->contracted_hours_per_week >= 30)
        {
            $planned_otj_hours = $tr->underSixHoursPerWeekRule() ? $tr->off_the_job_hours_based_on_duration : $tr->minimum_percentage_otj_training;
            $tch = $tr->total_contracted_hours_full_apprenticeship;
        }
        else
        {
            $planned_otj_hours = $tr->part_time_otj_hours;
            $tch = $tr->part_time_total_contracted_hours_full_apprenticeship;
        }
	    if($tr->otj_overwritten != '')
        {
            $planned_otj_hours = $tr->otj_overwritten;
        }

        echo <<<HTML
<div style="text-align: center;">
<h2><strong>Apprenticeship Agreement</strong></h2>
</div>
<br>

HTML;

        $type_of_funding = '';
        if($employer->funding_type == 'L')
        {
            $type_of_funding = 'Levy (DAS) Account';
        }
        elseif($employer->funding_type == 'CO')
        {
            $type_of_funding = 'Co-Investment';
        }
        elseif($employer->funding_type == 'LG')
        {
            $type_of_funding = 'Levy Gifted';
        }
        $type_of_employer = $employer->levy_employer == '1' ? 'Yes' : 'No';


        echo <<<HTML
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Apprenticeship Details</strong></h4></th></tr>
    <tr><th>Apprentice Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td></tr>
    <tr><th>Apprentice Job Role:</th><td>$tr->job_title</td></tr>
    <tr><th>Employer Name:</th><td>$employer->legal_name</td></tr>
    <tr><th>Funding Type:</th><td>$type_of_funding</td></tr>
    <tr><th>Levy Employer:</th><td>$type_of_employer</td></tr>
    <tr><th>Training Provider Name:</th><td>$provider->legal_name</td></tr>
    <tr><th>Subcontractor Name:</th><td>$subcontractor_name</td></tr>
    <tr><td colspan="2"></td></tr>
    <tr><th>Standard Title:</th><td>$standard_title</td></tr>
    <tr><th>Level:</th><td>$standard_level</td></tr>
    <tr><th>Start Date of Practical Period:</th><td>$practical_period_start_date</td></tr>
    <tr><th>Planned End Date of Practical Period:</th><td>$practical_period_end_date</td></tr>
    <tr><th>Duration of Practical Period - months:</th><td>$tr->duration_practical_period</td></tr>
    <tr><td colspan="2"></td></tr>
    <tr><th>Start Date of Apprenticeship:</th><td>$apprenticeship_start_date</td></tr>
    <tr><th>Planned End date of Apprenticeship (incl EPA):</th><td>$apprenticeship_end_date_inc_epa</td></tr>
    <tr><th>Duration of Full Apprenticeship (incl EPA) - months:</th><td>$tr->apprenticeship_duration_inc_epa</td></tr>
    <tr><td colspan="2"></td></tr>
    <tr><th>Planned Off-the-Job Hours:</th><td>$planned_otj_hours</td></tr>
</table>
</div>

HTML;

		            echo <<<HTML
<div style="font-size: 11px;">
<ol>
    <li>
        <strong>The apprenticeship agreement:</strong><br>
        <p>The apprenticeship agreement is a statutory requirement for the employment of an apprentice in connection with an approved apprenticeship standard. It forms part of the individual employment arrangements between the apprentice and the employer; it is a contract of service (i.e. a contract of employment) and not a contract of apprenticeship. If all the requirements of section 1 of the Employment Rights Act 1996 are complied with, the apprenticeship agreement can also serve as the written statement of particulars of employment. </p>
    </li>
    <li>
        <strong>Why an apprenticeship agreement is required</strong><br>
        <p>The Apprenticeships, Skills, Children and Learning Act (ASCLA) 2009 and the Apprenticeships (Miscellaneous Provisions) Regulations 2017 (SI No. 2017/1310)) require an apprenticeship agreement to be in place, for nearly all apprentices. This forms part of the employment arrangements between an apprentice and their employer; it is a contract of service (i.e. a contract of employment) and not a contract of apprenticeship. An apprenticeship agreement must be put in place when an individual starts an apprenticeship and should remain in place throughout (the agreement must be extended if the duration of the apprenticeship is extended).</p>
    </li>
    <li>
        <strong>When the apprenticeship agreement must be in place</strong><br>
        <p>An apprenticeship agreement must be in place when an individual starts a statutory apprenticeship programme and should remain in place throughout the apprenticeship. The end date is when the end-point assessment is due to be completed.</p>
    </li>
    <li>
        <strong>The 'practical period'</strong><br>
        <p>The practical period is the period for which an apprentice is expected to work and receive training under an approved English apprenticeship agreement. The practical period does not include the end-point assessment. For the purpose of meeting the Department for Education funding requirements, the practical period start date set out in the apprenticeship agreement must match the practical period start date in the commitment statement and the start date in the Individual Learner Record.</p>
    </li>
    <li>
        <strong>In certain circumstances, an apprenticeship can be completed without an apprenticeship agreement being in place</strong><br>
        <p>To commence a statutory apprenticeship (when an individual starts their apprenticeship programme) it is a legal requirement that an apprenticeship agreement be in place. The two circumstances in which an apprentice can complete a statutory apprenticeship without an apprenticeship agreement are where (i) they are holding office as an apprentice police constable, or as an apprentice minister of a religious organisation; or (ii) where they have been made redundant with less than six months of their apprenticeship’s practical period left to run (see regulation 6 of the Apprenticeships (Miscellaneous Provisions) Regulations 2017).</p>
    </li>
    <li>
        <strong>Who needs to sign the apprenticeship agreement?</strong><br>
        <p>The employer and the apprentice need to sign the agreement – it is an agreement between these two parties only. Training providers sign a separate commitment statement which outlines the planned content and schedule for training, what is expected of and offered by the employer, provider and the apprentice, and how to resolve queries or complaints.</p>
    </li>
    <li>
        <strong>Information needed in an apprenticeship agreement</strong><br>
        <p>The apprenticeship agreement must comply with the requirements as provided in ASCLA. It must:</p>
        <ul>
            <li>provide for the apprentice to work for the employer for reward in an occupation for which a standard has been published by the Institute for Apprenticeships and Technical Education;</li>
            <li>provide for the apprentice to receive training in order to assist the apprentice to achieve the standard in the work done under the agreement;</li>
            <li>specify the apprenticeship's practical period; and</li>
            <li>specify the amount of off-the-job training the apprentice is to receive.</li>
        </ul>
    </li>
    <li>
        <strong>Specifying the amount of off-the-job training</strong><br>
        <p>This is a requirement of the Apprenticeships (Miscellaneous Provisions) Regulations 2017. Off-the-job training is a critical requirement of apprenticeships and in order to meet the Department for Education’s funding rules, this must be at least the published volume of off-the-job training hours for the standard over the total duration of the apprenticeship (until gateway). Off-the-job training can only be received by an apprentice during their normal working hours. Maths and English, up to and including level 2, does not count towards the minimum off-the-job training requirement. The amount of off-the-job training should be agreed with the main provider. The provider must account for relevant prior learning the apprentice has received and reduce the content and duration of off-the-job training as necessary to achieve occupational competence.</p>
    </li>
    <li>
        <strong>Off-the-job training definition</strong><br>
        <p>Off-the-job training is defined as training which is received by the apprentice, during the apprentice’s normal working hours, for the purpose of achieving the standard connected to the apprenticeship. It is not on the job training received by the apprentice for the sole purpose of enabling the apprentice to perform the work to which the apprenticeship agreement relates. More information, including examples of off-the-job training, can be found on gov.uk[1].</p>
    </li>
    <li>
        <strong>Duration & Off-the-job Agreement</strong><br>
        <p>The employer confirms that an appropriate and sufficient timeframe has been agreed upon which is stated in this agreement to ensure the apprentice is able to complete the required off-the-job (OTJ) training hours in accordance with apprenticeship funding rules and programme requirements. This timeframe has been established to support the apprentice’s learning and development needs and will allow for the planned delivery of OTJ training throughout the duration of the apprenticeship.</p>
		<p>The employer is committed to providing the apprentice with protected time during normal working hours to complete these OTJ training activities, ensuring that they are meaningful, relevant, and appropriately scheduled to support the successful achievement of the apprenticeship.</p>
    </li>
</ol>
</div>

HTML;

        $learner_sign_date = isset($tr->learner_sign_date) ? Date::toShort($tr->learner_sign_date) : '';
        $emp_sign_date = isset($tr->emp_sign_date) ? Date::toShort($tr->emp_sign_date) : '';
        
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

        $mpdf->Output($app_agreement_file, 'F');
    }

}