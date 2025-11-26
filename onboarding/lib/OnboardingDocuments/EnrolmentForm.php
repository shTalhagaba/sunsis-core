<?php
class EnrolmentForm 
{
    public static function toPdf(PDO $link, TrainingRecord $tr, $c_file, $learner_signature_file, $provider_signature_file)
    {        
        $ob_learner = $tr->getObLearnerRecord($link);
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        if($framework->fund_model == Framework::FUNDING_STREAM_99 )
        {
            self::toPdfLearnerLoan($link, $tr, $c_file, $learner_signature_file, $provider_signature_file);
            return;
        }

        $logo = !is_null($provider->provider_logo) ? $provider->provider_logo : 'images/logos/' . SystemConfig::getEntityValue($link, 'logo');

        if($framework->fund_model == Framework::FUNDING_STREAM_BOOTCAMP)
        {
            $combined_logo = 'images/logos/combined-logo.png';
            $height = '150px';
            $width = '150px';
            if(DB_NAME == 'am_puzzled')
            {
                $combined_logo = 'images/logos/combined-logo-ymca.png';
                $height = '80px';
                $width = '80px';
            }
            $tds = '<td align="left">
                <img width="' . $width . '" height="' . $height . '" src="' . $logo . '" alt="Trainig Provider Logo" />
                <img src="' . $combined_logo . '" alt="Trainig Provider Logo" />
            </td>';
        }
        elseif($framework->fund_model == Framework::FUNDING_STREAM_ASF)
        {
            $tds = '<td width = "50%"><img src="' . $logo . '" alt="Trainig Provider Logo" /></td>';
            $tds .= '<td width = "50%"><img src="images/logos/Mayor_of_London_logo1.svg" alt="Mayor of London Logo"></td>';
        }
        elseif($framework->fund_model == Framework::FUNDING_STREAM_APP)
        {
            $tds = '<td width = "33%"><img src="' . $logo . '" alt="Trainig Provider Logo" /></td>';
            $tds .= '<td width = "33%"><img src="images/logos/apprenticeship.png" alt="Apprenticeship Logo"></td>';
            $tds .= '<td width = "33%"><img src="images/logos/dfe-logo.png"  height="100px" width="150px" alt="Department for Education Logo"></td>';
        }

        $mpdf = new \Mpdf\Mpdf(['format' => 'Legal', 'default_font_size' => 10]);
        $mpdf->SetMargins(15, 15, 50);
        $mpdf->setAutoBottomMargin = 'stretch';

        $header = <<<HEADER
    <div>
        <table width = "100%">
            <tr>{$tds}</tr>            
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
                <td width = "35%" align="left" style="font-size: 10px">Enrolment Form</td>
                <td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
            </tr>
        </table>
    </div>
HEREDOC;
        
        ob_start();

        $learner_address = $tr->home_address_line_1;
        $learner_address .= $tr->home_address_line_2 != '' ? '<br>' . $tr->home_address_line_2 : '';
        $learner_address .= $tr->home_address_line_3 != '' ? '<br>' . $tr->home_address_line_3 : '';
        $learner_address .= $tr->home_address_line_4 != '' ? '<br>' . $tr->home_address_line_4 : '';
        $gender = $ob_learner->gender == "F" ? "Female" : $ob_learner->gender;
        $gender = $ob_learner->gender == "M" ? "Male" : $gender;
        $ethnicity = DAO::getSingleValue($link,"SELECT Ethnicity_Desc FROM lis201213.ilr_ethnicity WHERE Ethnicity = '{$ob_learner->ethnicity}';");
        $dob = Date::toShort($ob_learner->dob);
        $age_at_start_sql = <<<SQL
SELECT 
((DATE_FORMAT('$tr->apprenticeship_start_date','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
        $age_at_start = DAO::getSingleValue($link, $age_at_start_sql);
        $nationality = DAO::getSingleValue($link, "SELECT description FROM lookup_nationalities WHERE id = '{$tr->nationality}'");
        $emergency_contacts_html = '';
        $emergency_contacts_result = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
        foreach($emergency_contacts_result AS $emergency_contacts_row)
        {
            if(trim($emergency_contacts_row['em_con_name']) == '')
            {
                continue;
            }
            $emergency_contacts_html .= '<i>' . $emergency_contacts_row['em_con_rel'] . '</i><br>';
            $emergency_contacts_html .= $emergency_contacts_row['em_con_title'] . ' ' . $emergency_contacts_row['em_con_name'] . '<br>';
            $emergency_contacts_html .= $emergency_contacts_row['em_con_tel'] . '<br>';
            $emergency_contacts_html .= $emergency_contacts_row['em_con_mob'] . '<hr>';
        }

        $LLDD = array('Y' => 'Yes', 'N' => 'No', 'P' => 'Prefer not to say');
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
        $lldd_desc = isset($LLDD[$tr->LLDD]) ? $LLDD[$tr->LLDD] : $tr->LLDD;
        $llddcats = '';
        $saved_llddcat = explode(",", $tr->llddcat);
        foreach($saved_llddcat AS $_llddcat)
            $llddcats .= isset($LLDDCat[$_llddcat]) ? $LLDDCat[$_llddcat] . '<br>' : $_llddcat . '<br>';

        $_primary_lldd = isset($LLDDCat[$tr->primary_lldd]) ? $LLDDCat[$tr->primary_lldd] : $tr->primary_lldd;
        $yesNoOptions = OnboardingHelper::getYesNoList();
        $ehcPlan = isset($yesNoOptions[$tr->ehc_plan]) ? $yesNoOptions[$tr->ehc_plan] : '';
        $ehcPlanEvidence = '';
        $ob_directory = Repository::getRoot() . DIRECTORY_SEPARATOR . 'OnboardingModule' . DIRECTORY_SEPARATOR . 'learners' . DIRECTORY_SEPARATOR . $tr->ob_learner_id . DIRECTORY_SEPARATOR . $tr->id . DIRECTORY_SEPARATOR .'onboarding';
        if($tr->ehc_evidence_file != '' && is_file($ob_directory . DIRECTORY_SEPARATOR . $tr->ehc_evidence_file) )
        {
            $ehcPlanEvidence = 'Yes (' . $tr->ehc_evidence_file . ')';
        }
        $countryOfBirth = DAO::getSingleValue($link, "SELECT country_name FROM lookup_countries WHERE id = '{$tr->country_of_birth}';");
        $countryOfPermanentResidence = DAO::getSingleValue($link, "SELECT country_name FROM lookup_countries WHERE id = '{$tr->country_of_perm_residence}';");

        $saved_eligibility_list = explode(',', $tr->EligibilityList);
        $livedFor3Years = in_array(1, $saved_eligibility_list) ? 'Yes' : '';
        $hasRightToLiveAfterCompletion = in_array(10, $saved_eligibility_list) ? 'Yes' : '';
        $currentlyNonUK = in_array(5, $saved_eligibility_list) ? 'Yes' : '';
        $needVisaToStudy = in_array(6, $saved_eligibility_list) ? 'Yes' : '';

        $evidenceType = DAO::getSingleValue($link, "SELECT description FROM lookup_id_evidence_types WHERE id = '{$tr->id_evidence_type}'");
        $evidenceExpiryDate = Date::toShort($tr->evidence_expiry_date);
        $evidenceEvidenceGiven = '';
        if($tr->evidence_pp_file != '' && is_file($ob_directory . DIRECTORY_SEPARATOR . $tr->evidence_pp_file) )
        {
            $evidenceEvidenceGiven = 'Yes (' . $tr->evidence_pp_file . ')';
        }
        $dateOfFirstUKEntry = Date::toShort($tr->date_of_first_uk_entry);
        $dateOfRecentUKEntry = Date::toShort($tr->date_of_most_recent_uk_entry);
        $evidenceILRGiven = '';
        if($tr->evidence_ilr_file != '' && is_file($ob_directory . DIRECTORY_SEPARATOR . $tr->evidence_ilr_file) )
        {
            $evidenceILRGiven = 'Yes (' . $tr->evidence_ilr_file . ')';
        }

        $ipe = ''; $nipn = ''; $nipl = ''; $nk = '';
        if($tr->EmploymentStatus == '10') $ipe = 'Yes';
        if($tr->EmploymentStatus == '11') $nipn = 'Yes';
        if($tr->EmploymentStatus == '12') $nipl = 'Yes';
        if($tr->EmploymentStatus == '98') $nk = 'Yes';

        $work_curr_emp_checked = '';
        if($tr->EmploymentStatus == '10' && $tr->work_curr_emp == '1') $work_curr_emp_checked = 'Yes';
        $SEI_checked = '';
        if($tr->EmploymentStatus == '10' && $tr->SEI == '1') $SEI_checked = 'Yes';
        $PEI_checked = '';
        if(($tr->EmploymentStatus == '11' || $tr->EmploymentStatus == '12') && $tr->PEI == '1') $PEI_checked = 'Yes';
        $SEM_checked = '';
        if($tr->EmploymentStatus == '10' && $tr->SEM == '1') $SEM_checked = 'Yes';
        $employerSector = DAO::getSingleValue($link, "SELECT description FROM lookup_sector_types WHERE id = '{$tr->curr_emp_sector}'");
        $LOE_dropdown = [1 => 'Up to 3 months', 2 => '4-6 months', 3 => '7-12 months', 4 => 'more than 12 months'];
        $LOEDesc = isset($LOE_dropdown[$tr->LOE]) ? $LOE_dropdown[$tr->LOE] : '';
        $EII_dropdown = [5 => '0-10 hours per week', 6 => '11-20 hours per week', 7 => '21-30 hours per week', 8 => '8 Learner is employed for 31+ hours per week'];
        $EIIDesc = isset($EII_dropdown[$tr->EII]) ? $EII_dropdown[$tr->EII] : '';
        $LOU_dropdown = [1 => 'unemployed for less than 6 months', 2 => 'unemployed for 6-11 months', 3 => 'unemployed for 12-23 months', 4 => 'unemployed for 24-35 months', 5 => 'unemployed for over 36 months'];
        $LOUDesc = isset($LOU_dropdown[$tr->LOU]) ? $LOU_dropdown[$tr->LOU] : '';
        $BSI_dropdown = [1 => 'JSA', 2 => 'ESA WRAG', 3 => 'Another state benefit', 4 => 'Universal Credit'];
        $BSIDesc = isset($BSI_dropdown[$tr->BSI]) ? $BSI_dropdown[$tr->BSI] : '';
        $practicalPeriodStartDate = Date::toLong($tr->practical_period_start_date);

        $hhs = '';
        $hhs_list = LookupHelper::getListHhs();
        $selected_hhs = explode(",", $tr->hhs);
        foreach($selected_hhs AS $_v)
            $hhs .= isset($hhs_list[$_v]) ? $hhs_list[$_v] . '<br>' : $_v . '<br>';

        $ob_eng = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '101'");
        $obEngDesc = '';
        if(isset($ob_eng->a_grade))
        {
            $obEngDesc = DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_eng->a_grade}'");
        }
        if($obEngDesc == '' && isset($ob_eng->p_grade))
        {
            $obEngDesc = 'Predicted Grade: ' . DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_eng->p_grade}'");
        }
        $ob_math = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '102'");
        $obMathDesc = '';
        if(isset($ob_math->a_grade))
        {
            $obMathDesc = DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_math->a_grade}'");
        }
        if($obMathDesc == '' && isset($ob_math->p_grade))
        {
            $obMathDesc = 'Predicted Grade: ' . DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_math->p_grade}'");
        }
        $ob_ict = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '103'");
        $obIctDesc = '';
        if(isset($ob_ict->a_grade))
        {
            $obIctDesc = DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_ict->a_grade}'");
        }
        if($obIctDesc == '' && isset($ob_ict->p_grade))
        {
            $obIctDesc = 'Predicted Grade: ' . DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_ict->p_grade}'");
        }

        $priorAttain = '';
        $ob_high = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = 'h'");
        if( isset($ob_high->level) )
        {
            $priorAttain = DAO::getSingleValue($link,"SELECT DISTINCT description FROM central.lookup_prior_attainment WHERE code = '{$ob_high->level}';"); 
        }

        echo <<<HTML
<div>
    <h2><strong>Enrolment Form</strong></h2>
</div>
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Personal Information</strong></h4></th></tr>
        <tr><th>Title:</th><td>$ob_learner->learner_title</td></tr>
        <tr><th>Full Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td></tr>
        <tr><th>Gender:</th><td>$gender</td></tr>
        <tr><th>Date of Birth:</th><td>$dob</td></tr>
        <tr><th>Ethnicity:</th><td>$ethnicity</td></tr>
        <tr><th>ULN:</th><td>$ob_learner->uln</td></tr>
        <tr><th>National Insurance:</th><td>$ob_learner->ni</td></tr>
        <tr><th>Personal Email:</th><td>$ob_learner->home_email</td></tr>
        <tr><th>Work Email:</th><td>$ob_learner->work_email</td></tr>
        <tr><th>Telephone/Mobile:</th><td>$tr->home_telephone / $tr->home_mobile</td></tr>
        <tr><th>Address:</th><td>$learner_address</td></tr>
        <tr><th>Borough:</th><td>$ob_learner->borough</td></tr>
        <tr><th>Postcode:</th><td>$tr->home_postcode</td></tr>
        <tr><th valign="top">Emergency Contact(s):</th><td>$emergency_contacts_html</td></tr>
    </table>
</div>

<div style="margin-top: 15px;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Learning Support</strong></h4></th></tr>
        <tr><th>Does learner have a learning difficulty, health problem or disability:</th><td>$lldd_desc</td></tr>
        <tr><th>LLDD Categories:</th><td>$llddcats</td></tr>
        <tr><th>Primary LLDD:</th><td>$_primary_lldd</td></tr>
        <tr><th>Does learner have an Education Health Care Plan?</th><td>$ehcPlan</td></tr>
        <tr><th>If learner has an Education Health Care Plan, then evidence is provided?</th><td>$ehcPlanEvidence</td></tr>
    </table>
</div>

<div style="margin-top: 15px;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Ethnicity</strong></h4></th></tr>
        <tr><th>Ethnicity:</th><td>$ethnicity</td></tr>
    </table>
</div>

<div style="margin-top: 15px;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Residency Assessment</strong></h4></th></tr>
        <tr><th>Country of birth:</th><td>$countryOfBirth</td></tr>
        <tr><th>Country of permanent residence:</th><td>$countryOfPermanentResidence</td></tr>
        <tr><th>Has learner lived within the UK for the last 3 Years?</th><td>$livedFor3Years</td></tr>
        <tr><th>Dees learner has the right to live in the UK for at least 12 months after finishing the programme?</th><td>$hasRightToLiveAfterCompletion</td></tr>
        <tr><th>Nationality:</th><td>$nationality</td></tr>
        <tr><th>Evidence Type:</th><td>$evidenceType</td></tr>
        <tr><th>Evidence Given:</th><td>$evidenceEvidenceGiven</td></tr>
        <tr><th>Evidence Reference:</th><td>$tr->evidence_reference</td></tr>
        <tr><th>Expiration date of the evidence document:</th><td>$evidenceExpiryDate</td></tr>
        <tr><th></th><td></td></tr>
        <tr><th>Is learner a non-EU citizen currently resident in the UK?</th><td>$currentlyNonUK</td></tr>
        <tr><th>Date of first entry to the UK:</th><td>$dateOfFirstUKEntry</td></tr>
        <tr><th>Date of most recent entry to the UK (excluding holidays):</th><td>$dateOfRecentUKEntry</td></tr>
        <tr><th>Has learner been granted indefinite Leave to Enter/Remain in the UK? If yes, a copy of ILR status provided as evidence?</th><td>$evidenceILRGiven</td></tr>
        <tr><th>Does learner need a visa to study in the UK?</th><td>$needVisaToStudy</td></tr>
        <tr><th>Current immigration status</th><td>$tr->immigration_category</td></tr>
        <tr><th>Passport number</th><td>$tr->passport_number</td></tr>
        <tr><th>Other residency notes and details:</th><td>$tr->other_residency_details</td></tr>
    </table>
</div>

<div style="margin-top: 15px;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Employment Details</strong></h4></th></tr>
        <tr><th>In paid employment:</th><td>$ipe</td></tr>
        <tr><th>Not in paid employment, looking for work and available to start work:</th><td>$nipn</td></tr>
        <tr><th>Not in paid employment, not looking for work and/or not available to start work:</th><td>$nipl</td></tr>
        <tr><th>Not known / don't want to provide:</th><td>$nk</td></tr>
        <tr><th>Was learner employed with current employer prior to starting this Programme?</th><td>$work_curr_emp_checked</td></tr>
        <tr><th>Learner is self-employed?</th><td>$SEI_checked</td></tr>
        <tr><th>Employer Name:</th><td>$tr->empStatusEmployer</td></tr>
        <tr><th>Sector of Employer:</th><td>$employerSector</td></tr>
        <tr><th>How long learner is employed?</th><td>$LOEDesc</td></tr>
        <tr><th>Working hours each week:</th><td>$EIIDesc</td></tr>
        <tr><th>Are your earnings below the London Living Wage of &pound;13.85 per hour, or gross salary of less than &pound;27,007.50?</th><td>$tr->earnings_below_llw</td></tr>
        <tr><th>Learner attending bootcamp via current employer?</th><td>$tr->bootcamp_via_current_emp</td></tr>
        <tr><th>Learner plan to work alongside the bootcamp?</th><td>$tr->bootcamp_with_work</td></tr>
        <tr><th>Learner was un-employed before?</th><td>$LOUDesc</td></tr>
        <tr><th>Receive any benefits?</th><td>$BSIDesc</td></tr>
        <tr><th>Learner in Full Time Education or Training prior to $practicalPeriodStartDate</th><td>$PEI_checked</td></tr>
    </table>
</div>

<div style="margin-top: 15px;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Household Situation</strong></h4></th></tr>
        <tr>
            <th>
                No household member is in employment and the household includes one or more dependent children<br>
                No household member is in employment and the household does not include any dependent children<br>
                Learner lives in a single adult household with dependent children<br>
                None of these statements apply
            </th>
            <td>$hhs</td>
        </tr>
    </table>
</div>

<div style="margin-top: 15px;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Education and Qualifications</strong></h4></th></tr>
        <tr><th>GCSE - English Language:</th><td>$obEngDesc</td></tr>
        <tr><th>GCSE - Maths:</th><td>$obMathDesc</td></tr>
        <tr><th>GCSE - ICT:</th><td>$obIctDesc</td></tr>
        <tr><th>Prior Attainment / Highest Qualification Level:</th><td>$priorAttain</td></tr>
    </table>
</div>

HTML;

        $extraQuals = DAO::getResultset($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND subject != '' AND q_type NOT IN ('g', 'h')", DAO::FETCH_ASSOC);
        if(count($extraQuals) > 0)
        {
            echo '<div style="margin-top: 15px;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="5" style="color: #000; background-color: #d2d6de !important"><h4><strong>Additional Qualifications</strong></h4></th></tr>';
            echo '<tr><th>Level</th><th>Subject</th><th>Predicted Grade</th><th>Actual Grade</th><th>Date Completed</th></tr>';
        }
        foreach($extraQuals AS $extraQualification)
        {
            echo '<tr>';
            echo '<td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_ob_qual_levels WHERE id = '{$extraQualification['level']}'") . '</td>';
            echo '<td>' . $extraQualification['subject'] . '</td>';
            echo '<td>' . $extraQualification['p_grade'] . '</td>';
            echo '<td>' . $extraQualification['a_grade'] . '</td>';
            echo '<td>' . Date::toShort($extraQualification['date_completed']) . '</td>';
            echo '</tr>';
        }
        if(count($extraQuals) > 0)
        {
            echo '</table></div>';
        }

        $obLearnerQualSql = "SELECT 
 (SELECT description FROM lookup_qual_type WHERE id = qual_type) AS qual_type,
 qual_id, qual_title, qual_start_date, qual_end_date, qual_exempt, ob_learner_quals.id,
 ob_learner_quals.qual_level, ob_learner_quals.qual_dh, ob_learner_quals.qual_delivery_postcode, 
 (SELECT description FROM lookup_qual_level WHERE lookup_qual_level.id = ob_learner_quals.qual_level) AS level_desc
FROM
  ob_learner_quals WHERE tr_id = '{$tr->id}' 
ORDER BY ob_learner_quals.qual_start_date ;";
        $progQuals = DAO::getResultset($link, $obLearnerQualSql, DAO::FETCH_ASSOC);
        if(count($progQuals) > 0)
        {
            echo '<div style="margin-top: 15px;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr>';
            echo '<th colspan="7" style="color: #000; background-color: #d2d6de !important">';
            echo '<h4><strong>Course Information</strong></h4>';
            echo '<br><strong>Title:</strong> ' . $framework->title . '<br>';
            echo '<br><strong>Start Date:</strong> ' . Date::toShort($tr->practical_period_start_date) . '<br>';
            echo '<br><strong>Practical Period End Date:</strong> ' . Date::toShort($tr->practical_period_end_date) . '<br>';
            echo '</th></tr>';
            echo '<tr><th>Code</th><th>Title</th><th>Level</th><th>Start Date</th><th>Planned End Date</th><th>GLH</th><th>Delivery Postcode</th></tr>';
        }
        foreach($progQuals AS $progQualification)
        {
            echo '<tr>';
            echo '<td>' . $progQualification['qual_id'] . '</td>';
            echo '<td>' . $progQualification['qual_title'] . '</td>';
            echo '<td>' . $progQualification['qual_level'] . ' ' . $progQualification['level_desc'] . '</td>';
            echo '<td>' . Date::toShort($progQualification['qual_start_date']) . '</td>';
            echo '<td>' . Date::toShort($progQualification['qual_end_date']) . '</td>';
            echo '<td>' . $progQualification['qual_dh'] . '</td>';
            echo '<td>' . $progQualification['qual_delivery_postcode'] . '</td>';
            echo '</tr>';
        }
        if(count($progQuals) > 0)
        {
            echo '</table></div>';
        }

        $html = ob_get_contents();
        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();
        $mpdf->WriteHTML($html);

        $mpdf->addPage();
        ob_start();

        echo '<h3>Learning Agreement and Declaration</h3>';

        if($framework->fund_model == Framework::FUNDING_STREAM_ASF)
        {
            echo <<<HTML

        <div class="well">
            <p>
                I confirm that I have received sufficient guidance from {$provider->legal_name} about: the choice of courses available to me;
                course entry requirements; my suitability for the course; the financial and learning support available to me, as appropriate.
            </p>
            <p>
                I understand EETGroup reserves the right to amend course arrangements as published, and merge or close classes if learner
                numbers cease to be viable. I agree to abide by the EETGroup's policies and procedures and Learner Code of Conduct (available
                at induction). I understand any breaches of these may result in disciplinary action being taken against me and my learning
                agreement terminated. I agree to provide evidence of eligibility to study with us including evidence of 3 years residency. I
                formally accept the learning programme specified on this form and confirm that all the information supplied on this form is
                correct. I understand if I have declared false information EETGroup will take action against me to reclaim course fees and any
                associated costs. I give my consent to EETGroup to record and process the information contained in this form where EETGroup
                complies with its obligations under the GDPR guidelines. EETGroup's processing includes the use of CCTV to maintain the
                security of the premises, to prevent, detect and investigate crime. I understand EETGroup have a no abuse tolerance to their
                staff or other learners, and that any breach of this will result in my quick removal from the course and premises, without the
                chance of returning. I agree to abide by the expectations of both EETGroup as well as the course, and I am willing/able to
                attend all sessions of the course, as well as the set employment sessions. I understand that failure to attend the scheduled
                sessions may result in EETGroup reclaiming course fees.
            </p>
            <p>
                By signing this enrolment declaration, I am confirming that the information I have provided is correct.
            </p>
            <p>
                By signing this form, I am giving consent for EETGroup to process my enrolment in line with the guidance above. I have read
                and accept the terms of Employment Education Training Groups Privacy Policy.
            </p>
        </div>
HTML;

        }
        elseif($framework->fund_model == Framework::FUNDING_STREAM_BOOTCAMP)
        {
            echo <<<HTML

        <div class="well">
            <p>
                I agree that initial assessment and information advice and guidance concerning the course has been provided to me, this
                included information about the course, its entry requirements, the implications of the choice of course, its suitability and the
                support which is available to me. I agree that the information given on this agreement is true, correct and completed to the
                best of my knowledge and I understand that (name of the provider) has the right to cancel my enrolment if it is found that I
                have provided false or inaccurate information. I agree that this information can be used to process my data for any purposes
                connected with my studies or my health and safety whilst on the premises. This also includes any other contractual
                requirements and, in particular to the disclosure of all the data on this form or otherwise collected about me to the DfE for the
                purposes noted in the GLA Privacy Notice and DfE Privacy Q&A which can be found below at Annex four and five. I also agree
                with the below points relating to my chosen programme:
            </p>
            <ul>
                <li>Take appropriate responsibility for my own learning, development and progression</li>
                <li>Attend and undertake training required to achieve the Skills Bootcamp identified in Programme Details in the ILP</li>
                <li>Promptly inform the Employer and/or EEVTraining if any matters or issues arise, or might arise, that will, or may, affect my
                learning, development and progression</li>
                <li>All times behave in a safe and responsible manner and in accordance with the statutory requirements of health and safety
                law relating to my responsibilities from time to time</li>
                <li>comply with the policies, regulations and procedures of my Employer and/or (name of provider), notified to me from time to
                time;</li>
            </ul>
            <p>
                If you wish to raise a complaint about how we have handled your personal data email to (provider's email address) or any
                other issues, please email (provider's emails address) with full details of your issue.
            </p>
            <p>
                If you are not satisfied how your complaint has been dealt with, please be aware of the Department of Education (DfE)
                Whistleblowing and Complaints policies and processes. Whistleblowing involves entering a 'whistleblowing' webform on the
                'Contact the Department for Education' page, which can be found on Complaints procedure - Department for Education - 
                GOV.UK (www.gov.uk)
            </p>
            <p>
                Whistleblowing entries for Skills Bootcamps must be clearly marked as 'Skills Bootcamps' and will submitted via the DfE's
                whistleblowing submission process and will be escalated to the relevant policy team. Please also copy in
                skillsbootcamps@london.gov.uk.
            </p>
        </div>
HTML;

        }

        $selected_rui = explode(',', $tr->RUI);
        $selected_pmc = explode(',', $tr->PMC);

        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="2">Learner <u>agrees</u> to be contacted as follows:</th></tr>';
        echo '<tr><th>About courses or learning opportunities:</th><td>' . (in_array(1, $selected_rui) ? 'Yes' : '') . '</td></tr>';
        echo '<tr><th>For surveys and research:</th><td>' . (in_array(2, $selected_rui) ? 'Yes' : '') . '</td></tr>';
        echo '<tr><th>By post:</th><td>' . (in_array(1, $selected_pmc) ? 'Yes' : '') . '</td></tr>';
        echo '<tr><th>By phone:</th><td>' . (in_array(2, $selected_pmc) ? 'Yes' : '') . '</td></tr>';
        echo '<tr><th>By email:</th><td>' . (in_array(3, $selected_pmc) ? 'Yes' : '') . '</td></tr>';
        echo '</table>';

        $html = ob_get_contents();
        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();
        $mpdf->WriteHTML($html);

        $mpdf->addPage();
        ob_start();

        $training_plan = DAO::getObject($link, "SELECT * FROM ob_learner_bespoke_training_plan WHERE tr_id = '{$tr->id}'");
        $bespokeForm = is_null($training_plan->form_data) ? null : json_decode($training_plan->form_data);
        echo '<h3>Bespoke Training Plan</h3>';
        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr>';
        echo '<th>Justification of Functional Skills Support</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question1) ? $bespokeForm->question1 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Why do you want to do this course?</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question2) ? $bespokeForm->question2 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Short Term Goals</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question3) ? $bespokeForm->question3 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Long Term Goals</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question4) ? $bespokeForm->question4 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>What are your current/ongoing barriers to achieving your goals?</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question5) ? $bespokeForm->question5 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>How can we support?</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question6) ? $bespokeForm->question6 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>What skills are you hoping to achieve from this project?<br>Soft Skills (generally personality traits)</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question7) ? $bespokeForm->question7 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>What skills are you hoping to achieve from this project?<br>Hard Skills (job-specific abilities acquired through education and training)</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question8) ? $bespokeForm->question8 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>What prior work experience do you hold?</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question9) ? $bespokeForm->question9 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>What prior qualifications have you achieved / studied? How will these help you on the course?</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question10) ? $bespokeForm->question10 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>What difficulties you have when learning?</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question11) ? $bespokeForm->question11 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>What are your learning styles? (Visual, Auditory, Kinaesthetic)</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question12) ? $bespokeForm->question12 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>What employment support would you benefit from?</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question13) ? $bespokeForm->question13 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Do you have any additional support needs?<br>(e.g Learning support, careers advice, childcare, travel expenses, access support)</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question14) ? $bespokeForm->question14 : '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Do you have any internet connection that is reliable at your palce of residence?</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question15 == 1) ? 'Yes' : 'No') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Do you have a device sucha as laptop, tablet or PC that you can use for the classes?</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question16 == 1) ? 'Yes' : 'No') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Do you have Microsoft Word on your computer?</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question17 == 1) ? 'Yes' : 'No') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Have you used Microsoft Teams or any other similar platform before i.e. ZOOM?</th>';
        echo '<td>' . ((isset($bespokeForm) && $bespokeForm->question18 == 1) ? 'Yes' : 'No') . '</td>';
        echo '</tr>';
        echo '</table>';

        $html = ob_get_contents();
        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();
        $mpdf->WriteHTML($html);

        $mpdf->addPage();
        ob_start();

        $ilp_form_record = DAO::getObject($link, "SELECT * FROM ob_learner_ilp_form WHERE tr_id = '{$tr->id}'");
        $ilpForm = $ilp_form_record?->form_data
            ? json_decode($ilp_form_record->form_data)
            : null;

        echo '<h3>Individual Action Plan</h3>';
        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr>';
        echo '<th width="50%">Specific Goal<br>
                What are you going to achieve? What do you want to develop or improve whilst onthe course?</th>';
        echo '<td>' . ($ilpForm->specific_goal ?? '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th width="50%">Measurable<br>
                How will you know when you have achieved this goal? How will others know?</th>';
        echo '<td>' . ($ilpForm->measurable ?? '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th width="50%">Achievable<br>
                What is needed to achieve this goal? Skills, knowledge, resources, support, time, etc.</th>';
        echo '<td>' . ($ilpForm->achievable ?? '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th width="50%">Realistic<br>
                What steps can I put in place to achieve this goal?</th>';
        echo '<td>' . ($ilpForm->realistic ?? '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th width="50%">Timescale<br>
                When will I achieve the goal by? It is a short/medium or long term goal? Dates?</th>';
        echo '<td>' . ($ilpForm->timescale ?? '') . '</td>';
        echo '</tr>';
        echo '</table>';

        $html = ob_get_contents();
        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();
        $mpdf->WriteHTML($html);

        $mpdf->addPage();
        ob_start();

        $trInductionChecklistProvider = DAO::getSingleColumn($link, "SELECT checklist_item_id FROM ob_learner_induction_checklist WHERE tr_id = '{$tr->id}' AND provider_agree = 1");
        $inductionChecklist = DAO::getResultset($link, "SELECT * FROM lookup_induction_checklist", DAO::FETCH_ASSOC);
        echo '<h3>Induction Checklist</h3>';
        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        foreach($inductionChecklist AS $inductionChecklistRow)
        {
            echo '<tr>';
            echo '<th>' . $inductionChecklistRow['sequence'] . '</th>';
            echo '<td>' . $inductionChecklistRow['description'] . '</td>';
            echo '<td class="text-center">' . (in_array($inductionChecklistRow['id'], $trInductionChecklistProvider) ? ' Yes ' : '') . '</td>';
            echo '</tr>';
        } 
        echo '</table>';
        
        $learner_sign_date = Date::toShort($tr->learner_sign_date);
        $provider_sign_date = Date::toShort($tr->tp_sign_date);

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
        <td>Provider</td>
        <td>{$tr->tp_sign_name}</td>
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

        if(false && DB_NAME == "am_eet" && $_SESSION['user']->username == 'aperspective')
        {
            $mpdf->Output('Enrolment Form', 'F');
        }
        else
        {
            $mpdf->Output($c_file, 'F');
        }
    }

    public static function toPdfLearnerLoan(PDO $link, TrainingRecord $tr, $c_file, $learner_signature_file, $provider_signature_file)
    {
        $ob_learner = $tr->getObLearnerRecord($link);
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        $framework = Framework::loadFromDatabase($link, $tr->framework_id);

        $logo = !is_null($provider->provider_logo) ? $provider->provider_logo : 'images/logos/' . SystemConfig::getEntityValue($link, 'logo');

        $tds = '<td align="left">
            <img width="150px" src="' . $logo . '" alt="Trainig Provider Logo" />
        </td>';

        $mpdf = new \Mpdf\Mpdf(['format' => 'Legal', 'default_font_size' => 10]);
        $mpdf->SetMargins(15, 15, 50);
        $mpdf->setAutoBottomMargin = 'stretch';

        $header = <<<HEADER
    <div>
        <table width = "100%">
            <tr>{$tds}</tr>            
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
                <td width = "35%" align="left" style="font-size: 10px">Enrolment Form</td>
                <td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
            </tr>
        </table>
    </div>
HEREDOC;
        
        ob_start();

        $learner_address = $tr->home_address_line_1;
        $learner_address .= $tr->home_address_line_2 != '' ? '<br>' . $tr->home_address_line_2 : '';
        $learner_address .= $tr->home_address_line_3 != '' ? '<br>' . $tr->home_address_line_3 : '';
        $learner_address .= $tr->home_address_line_4 != '' ? '<br>' . $tr->home_address_line_4 : '';
        $learner_address .= $ob_learner->borough != '' ? '<br>' . $ob_learner->borough : '';
        $gender = $ob_learner->gender == "F" ? "Female" : $ob_learner->gender;
        $gender = $ob_learner->gender == "M" ? "Male" : $gender;
        $ethnicity = DAO::getSingleValue($link,"SELECT Ethnicity_Desc FROM lis201213.ilr_ethnicity WHERE Ethnicity = '{$ob_learner->ethnicity}';");
        $dob = Date::toShort($ob_learner->dob);
        $age_at_start_sql = <<<SQL
SELECT 
((DATE_FORMAT('$tr->apprenticeship_start_date','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
        $age_at_start = DAO::getSingleValue($link, $age_at_start_sql);
        $nationality = DAO::getSingleValue($link, "SELECT description FROM lookup_nationalities WHERE id = '{$tr->nationality}'");
        $emergency_contacts_html = '';
        $emergency_contacts_result = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
        foreach($emergency_contacts_result AS $emergency_contacts_row)
        {
            if(trim($emergency_contacts_row['em_con_name'] ?? '') == '')
            {
                continue;
            }
            $emergency_contacts_html .= '<i>' . $emergency_contacts_row['em_con_rel'] . '</i><br>';
            $emergency_contacts_html .= $emergency_contacts_row['em_con_title'] . ' ' . $emergency_contacts_row['em_con_name'] . '<br>';
            $emergency_contacts_html .= $emergency_contacts_row['em_con_tel'] . '<br>';
            $emergency_contacts_html .= $emergency_contacts_row['em_con_mob'] . '<hr>';
        }

        $LLDD = array('Y' => 'Yes', 'N' => 'No', 'P' => 'Prefer not to say');
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
        $lldd_desc = isset($LLDD[$tr->LLDD]) ? $LLDD[$tr->LLDD] : $tr->LLDD;
        $llddcats = '';
        $saved_llddcat = $tr->llddcat != '' ? explode(',', $tr->llddcat) : [];
        foreach($saved_llddcat AS $_llddcat)
            $llddcats .= isset($LLDDCat[$_llddcat]) ? $LLDDCat[$_llddcat] . '<br>' : $_llddcat . '<br>';

        $_primary_lldd = isset($LLDDCat[$tr->primary_lldd]) ? $LLDDCat[$tr->primary_lldd] : $tr->primary_lldd;
        $yesNoOptions = OnboardingHelper::getYesNoList();
        $ehcPlan = isset($yesNoOptions[$tr->ehc_plan]) ? $yesNoOptions[$tr->ehc_plan] : '';
        $ehcPlanEvidence = '';
        $ob_directory = Repository::getRoot() . DIRECTORY_SEPARATOR . 'OnboardingModule' . DIRECTORY_SEPARATOR . 'learners' . DIRECTORY_SEPARATOR . $tr->ob_learner_id . DIRECTORY_SEPARATOR . $tr->id . DIRECTORY_SEPARATOR .'onboarding';
        if($tr->ehc_evidence_file != '' && is_file($ob_directory . DIRECTORY_SEPARATOR . $tr->ehc_evidence_file) )
        {
            $ehcPlanEvidence = 'Yes (' . $tr->ehc_evidence_file . ')';
        }
        $countryOfBirth = DAO::getSingleValue($link, "SELECT country_name FROM lookup_countries WHERE id = '{$tr->country_of_birth}';");
        $countryOfPermanentResidence = DAO::getSingleValue($link, "SELECT country_name FROM lookup_countries WHERE id = '{$tr->country_of_perm_residence}';");

        $saved_eligibility_list = $tr->EligibilityList != '' ? explode(',', $tr->EligibilityList) : [];
        $livedFor3Years = in_array(1, $saved_eligibility_list) ? 'Yes' : '';
        $hasRightToLiveAfterCompletion = in_array(10, $saved_eligibility_list) ? 'Yes' : '';
        $currentlyNonUK = in_array(5, $saved_eligibility_list) ? 'Yes' : '';
        $needVisaToStudy = in_array(6, $saved_eligibility_list) ? 'Yes' : '';

        $evidenceType = DAO::getSingleValue($link, "SELECT description FROM lookup_id_evidence_types WHERE id = '{$tr->id_evidence_type}'");
        $evidenceExpiryDate = Date::toShort($tr->evidence_expiry_date);
        $evidenceEvidenceGiven = '';
        if($tr->evidence_pp_file != '' && is_file($ob_directory . DIRECTORY_SEPARATOR . $tr->evidence_pp_file) )
        {
            $evidenceEvidenceGiven = 'Yes (' . $tr->evidence_pp_file . ')';
        }
        $dateOfFirstUKEntry = Date::toShort($tr->date_of_first_uk_entry);
        $dateOfRecentUKEntry = Date::toShort($tr->date_of_most_recent_uk_entry);
        $evidenceILRGiven = '';
        if($tr->evidence_ilr_file != '' && is_file($ob_directory . DIRECTORY_SEPARATOR . $tr->evidence_ilr_file) )
        {
            $evidenceILRGiven = 'Yes (' . $tr->evidence_ilr_file . ')';
        }

        $ipe = ''; $nipn = ''; $nipl = ''; $nk = '';
        if($tr->EmploymentStatus == '10') $ipe = 'Yes';
        if($tr->EmploymentStatus == '11') $nipn = 'Yes';
        if($tr->EmploymentStatus == '12') $nipl = 'Yes';
        if($tr->EmploymentStatus == '98') $nk = 'Yes';

        $work_curr_emp_checked = '';
        if($tr->EmploymentStatus == '10' && $tr->work_curr_emp == '1') $work_curr_emp_checked = 'Yes';
        $SEI_checked = '';
        if($tr->EmploymentStatus == '10' && $tr->SEI == '1') $SEI_checked = 'Yes';
        $PEI_checked = '';
        if(($tr->EmploymentStatus == '11' || $tr->EmploymentStatus == '12') && $tr->PEI == '1') $PEI_checked = 'Yes';
        $SEM_checked = '';
        if($tr->EmploymentStatus == '10' && $tr->SEM == '1') $SEM_checked = 'Yes';
        $employerSector = DAO::getSingleValue($link, "SELECT description FROM lookup_sector_types WHERE id = '{$tr->curr_emp_sector}'");
        $LOE_dropdown = [1 => 'Up to 3 months', 2 => '4-6 months', 3 => '7-12 months', 4 => 'more than 12 months'];
        $LOEDesc = isset($LOE_dropdown[$tr->LOE]) ? $LOE_dropdown[$tr->LOE] : '';
        $EII_dropdown = [5 => '0-10 hours per week', 6 => '11-20 hours per week', 7 => '21-30 hours per week', 8 => '8 Learner is employed for 31+ hours per week'];
        $EIIDesc = isset($EII_dropdown[$tr->EII]) ? $EII_dropdown[$tr->EII] : '';
        $LOU_dropdown = [1 => 'unemployed for less than 6 months', 2 => 'unemployed for 6-11 months', 3 => 'unemployed for 12-23 months', 4 => 'unemployed for 24-35 months', 5 => 'unemployed for over 36 months'];
        $LOUDesc = isset($LOU_dropdown[$tr->LOU]) ? $LOU_dropdown[$tr->LOU] : '';
        $BSI_dropdown = [1 => 'JSA', 2 => 'ESA WRAG', 3 => 'Another state benefit', 4 => 'Universal Credit'];
        $BSIDesc = isset($BSI_dropdown[$tr->BSI]) ? $BSI_dropdown[$tr->BSI] : '';
        $practicalPeriodStartDate = Date::toLong($tr->practical_period_start_date);

        $hhs = '';
        $hhs_list = LookupHelper::getListHhs();
        $selected_hhs = $tr->hhs != '' ? explode(",", $tr->hhs) : [];
        foreach($selected_hhs AS $_v)
            $hhs .= isset($hhs_list[$_v]) ? $hhs_list[$_v] . '<br>' : $_v . '<br>';

        $ob_eng = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '101'");
        $obEngDesc = '';
        if(isset($ob_eng->a_grade))
        {
            $obEngDesc = DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_eng->a_grade}'");
        }
        if($obEngDesc == '' && isset($ob_eng->p_grade))
        {
            $obEngDesc = 'Predicted Grade: ' . DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_eng->p_grade}'");
        }
        $ob_math = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '102'");
        $obMathDesc = '';
        if(isset($ob_math->a_grade))
        {
            $obMathDesc = DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_math->a_grade}'");
        }
        if($obMathDesc == '' && isset($ob_math->p_grade))
        {
            $obMathDesc = 'Predicted Grade: ' . DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_math->p_grade}'");
        }
        $ob_ict = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND level = '103'");
        $obIctDesc = '';
        if(isset($ob_ict->a_grade))
        {
            $obIctDesc = DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_ict->a_grade}'");
        }
        if($obIctDesc == '' && isset($ob_ict->p_grade))
        {
            $obIctDesc = 'Predicted Grade: ' . DAO::getSingleValue($link,"SELECT description, NULL FROM lookup_gcse_grades WHERE id = '{$ob_ict->p_grade}'");
        }

        $priorAttain = '';
        $ob_high = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = 'h'");
        if( isset($ob_high->level) )
        {
            $priorAttain = DAO::getSingleValue($link,"SELECT DISTINCT description FROM central.lookup_prior_attainment WHERE code = '{$ob_high->level}';"); 
        }

        $ob_learner_extra_details = DAO::getObject($link, "SELECT * FROM ob_learner_extra_details WHERE tr_id = {$tr->id}");
        $havePassport = ( $tr->id_evidence_type == 1 || (isset($ob_learner_extra_details->have_uk_pp) && $ob_learner_extra_details->have_uk_pp != '') ) ? 'Yes' : 'No';
        $legalResident = $ob_learner_extra_details->legal_uk_resident == 1 ? 'Yes' : ($ob_learner_extra_details->legal_uk_resident == 2 ? 'No' : '');
        $livedInEU = $ob_learner_extra_details->lived_in_eu == 1 ? 'Yes' : ($ob_learner_extra_details->lived_in_eu == 2 ? 'No' : '');
        $immigrationStatus = isset($ob_learner_extra_details->immigration_status) ? OnboardingHelper::immigrationStatusDesc($ob_learner_extra_details->immigration_status) : '';
        $have_uk_bc = $ob_learner_extra_details->have_uk_bc == 1 ? 'Yes' : ($ob_learner_extra_details->have_uk_bc == 2 ? 'No' : '');
        $smartphone = $ob_learner_extra_details->smartphone == 1 ? 'Yes' : ($ob_learner_extra_details->smartphone == 2 ? 'No' : '');
        $tablet = $ob_learner_extra_details->tablet == 1 ? 'Yes' : ($ob_learner_extra_details->tablet == 2 ? 'No' : '');
        $laptop = $ob_learner_extra_details->laptop == 1 ? 'Yes' : ($ob_learner_extra_details->laptop == 2 ? 'No' : '');

        $formHeading = 'Enrolment Form';
        if($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN)
        {
            $formHeading = 'Adult Learner Loan - Enrolment Form';
        }
        if($framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL)
        {
            $formHeading = 'Commercial ';
        }

        $_t = "";
        $_t1 = "";
        $_t2 = "";
        if($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN)
        {
            $_t = "<tr><th>Advanced Learner Loan Amount:</th><td>{$tr->all_amount}</td></tr><tr><th>Have you had a advanced learner loan before:</th><td>{$tr->all_before}</td></tr>";
        }
        if($framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL)
        {
            $tutorName = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$tr->trainers}'");
            $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
            $employerLocation = Location::loadFromDatabase($link, $tr->employer_location_id);
            $_t = "<tr><th>Purchase Order No.</th><td>{$tr->purchase_order_no}</td></tr>";
            $_t1 = "<tr><th>Tutor/LSC:</th><td>{$tutorName}</td></tr>";
            $_t2 = "<tr><th>Employer ERN:</th><td>{$employer->code}</td></tr>";
        }
        
        echo <<<HTML
<div>
    <h2><strong>$formHeading - Enrolment Form</strong></h2>
</div>
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Provider:</th><td>$provider->legal_name</td></tr>
        <tr><th>ULN:</th><td>$ob_learner->uln</td></tr>
        $_t1
        $_t2
        <tr><th>Title:</th><td>$ob_learner->learner_title</td></tr>
        <tr><th>Firstname(s):</th><td>$ob_learner->firstnames</td></tr>
        <tr><th>Surname:</th><td>$ob_learner->surname</td></tr>
        <tr><th>Gender:</th><td>$gender</td></tr>
        <tr><th>Date of Birth:</th><td>$dob</td></tr>
        <tr><th>National Insurance:</th><td>$ob_learner->ni</td></tr>
        <tr><th>Do you have a UK passport. Please enter passport number:</th><td>$havePassport $tr->evidence_reference</td></tr>
        <tr><th>Address:</th><td>$learner_address</td></tr>
        <tr><th>Postcode:</th><td>$tr->home_postcode</td></tr>
        <tr><th>Personal Email:</th><td>$ob_learner->home_email</td></tr>
        <tr><th>Work Email:</th><td>$ob_learner->work_email</td></tr>
        <tr><th>Telephone/Mobile:</th><td>$tr->home_telephone / $tr->home_mobile</td></tr>
        <tr><th valign="top">Emergency Contact(s):</th><td>$emergency_contacts_html</td></tr>
        <tr><th>Course Title:</th><td>$framework->title</td></tr>
        <tr><th>Commercial Fee:</th><td>$tr->commercial_fee</td></tr>
        <tr><th>Employer Paying any part of the fee?:</th><td>$tr->commercial_fee_emp_cont</td></tr>
        $_t
        <tr><th>Ethnicity:</th><td>$ethnicity</td></tr>
        <tr><th>Nationality:</th><td>$nationality</td></tr>
        <tr><th>Are you a legal resident of the UK and able to take paid employment within the EU?:</th><td>$legalResident</td></tr>
        <tr><th>Have you lived within the UK/EU for the last 3 Years?:</th><td>$livedInEU</td></tr>
        <tr><th>Immigration status</th><td>$immigrationStatus</td></tr>        
        <tr><th>Do you have a valid UK Passport?</th><td>$havePassport $tr->evidence_reference</td></tr>
        <tr><th>Do you have a UK Birth Certificate?</th><td>$have_uk_bc</td></tr>
    </table>
    <br>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="4" style="color: #000; background-color: #d2d6de !important"><h4><strong>Proof of Identity</strong></h4></th></tr>
        <tr>
            <th colspan="4">
                Verification of Identity - This must be completed for all learners and must detail the type of evidence seen. 
                Evidence must be validated before the learner commences on programme. Please note, we do not require copies of identification evidence. 
                (If applicable) Please provide evidence of name change (e.g., Marriage certificate, deed poll or another legal document) 
            </th>
        </tr>
        <tr>
            <th>Evidence Type</th>
            <td>$evidenceType</td>
            <th>Evidence Reference</th>
            <td>$tr->evidence_reference</td>
        </tr>
    </table>
    <br>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important"><h4><strong>ICT Skills</strong></h4></th></tr>
        <tr><th>Internet / Email</th><th>MS Office (Word/Excel)</th><th>ePortfolio (or similar web-based platforms)</th></tr>
        <tr><td>$ob_learner_extra_details->internet_use</td><td>$ob_learner_extra_details->ms_office</td><td>$ob_learner_extra_details->eportfolio</td></tr>
    </table>

    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="3" style="color: #000; background-color: #d2d6de !important"><h4><strong>Access to Devices</strong></h4></th></tr>
        <tr><th>Smartphone</th><th>Tablet </th><th>Laptop/PC</th></tr>
        <tr><td>$smartphone</td><td>$tablet</td><td>$laptop</td></tr>
    </table>

    <div style="margin-top: 15px;">
        <table border="1" style="width: 100%;" cellpadding="6">
            <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Household Situation</strong></h4></th></tr>
            <tr>
                <th>
                    No household member is in employment and the household includes one or more dependent children<br>
                    No household member is in employment and the household does not include any dependent children<br>
                    Learner lives in a single adult household with dependent children<br>
                    None of these statements apply
                </th>
                <td>$hhs</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 15px;">
        <table border="1" style="width: 100%;" cellpadding="6">
            <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Education and Qualifications</strong></h4></th></tr>
            <tr><th>GCSE - English Language:</th><td>$obEngDesc</td></tr>
            <tr><th>GCSE - Maths:</th><td>$obMathDesc</td></tr>
            <tr><th>GCSE - ICT:</th><td>$obIctDesc</td></tr>
            <tr><th>Prior Attainment / Highest Qualification Level:</th><td>$priorAttain</td></tr>
        </table>
    </div>
HTML;

        $extraQuals = DAO::getResultset($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND subject != '' AND q_type NOT IN ('g', 'h')", DAO::FETCH_ASSOC);
        if(count($extraQuals) > 0)
        {
            echo '<div style="margin-top: 15px;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="5" style="color: #000; background-color: #d2d6de !important"><h4><strong>Additional Qualifications</strong></h4></th></tr>';
            echo '<tr><th>Level</th><th>Subject</th><th>Predicted Grade</th><th>Actual Grade</th><th>Date Completed</th></tr>';
        }
        foreach($extraQuals AS $extraQualification)
        {
            echo '<tr>';
            echo '<td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_ob_qual_levels WHERE id = '{$extraQualification['level']}'") . '</td>';
            echo '<td>' . $extraQualification['subject'] . '</td>';
            echo '<td>' . $extraQualification['p_grade'] . '</td>';
            echo '<td>' . $extraQualification['a_grade'] . '</td>';
            echo '<td>' . Date::toShort($extraQualification['date_completed']) . '</td>';
            echo '</tr>';
        }
        if(count($extraQuals) > 0)
        {
            echo '</table></div>';
        }

        $obLearnerQualSql = "SELECT 
 (SELECT description FROM lookup_qual_type WHERE id = qual_type) AS qual_type,
 qual_id, qual_title, qual_start_date, qual_end_date, qual_exempt, ob_learner_quals.id, ob_learner_quals.qual_dh, ob_learner_quals.qual_delivery_postcode, 
 (SELECT description FROM lookup_qual_level WHERE lookup_qual_level.id = ob_learner_quals.qual_level) AS level_desc, ob_learner_quals.qual_level
FROM
  ob_learner_quals WHERE tr_id = '{$tr->id}' 
ORDER BY ob_learner_quals.qual_start_date ;";
        $progQuals = DAO::getResultset($link, $obLearnerQualSql, DAO::FETCH_ASSOC);
        if(count($progQuals) > 0)
        {
            echo '<div style="margin-top: 15px;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="7" style="color: #000; background-color: #d2d6de !important">';
            echo '<h4><strong>Course Information</strong></h4>';
            echo '<br><strong>Title:</strong> ' . $framework->title . '<br>';
            echo '<br><strong>Start Date:</strong> ' . Date::toShort($tr->practical_period_start_date) . '<br>';
            echo '<br><strong>Practical Period End Date:</strong> ' . Date::toShort($tr->practical_period_end_date) . '<br>';
            echo '</th></tr>';
            echo '<tr><th>Aim Ref</th><th>Title</th><th>Level</th><th>Start Date</th><th>Planned End Date</th><th>GLH</th><th>Delivery Postcode</th></tr>';
        }
        foreach($progQuals AS $progQualification)
        {
            echo '<tr>';
            echo '<td>' . $progQualification['qual_id'] . '</td>';
            echo '<td>' . $progQualification['qual_title'] . '</td>';
            echo '<td>' . $progQualification['qual_level'] . ' ' . $progQualification['level_desc'] . '</td>';
            echo '<td>' . Date::toShort($progQualification['qual_start_date']) . '</td>';
            echo '<td>' . Date::toShort($progQualification['qual_end_date']) . '</td>';
            echo '<td>' . $progQualification['qual_dh'] . '</td>';
            echo '<td>' . $progQualification['qual_delivery_postcode'] . '</td>';
            echo '</tr>';
        }
        if(count($progQuals) > 0)
        {
            echo '</table></div>';
        }

        echo <<<HTML
    <div style="margin-top: 15px;">
        <table border="1" style="width: 100%;" cellpadding="6">
            <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Employment Details</strong></h4></th></tr>
            <tr><th>In paid employment:</th><td>$ipe</td></tr>
            <tr><th>Not in paid employment, looking for work and available to start work:</th><td>$nipn</td></tr>
            <tr><th>Not in paid employment, not looking for work and/or not available to start work:</th><td>$nipl</td></tr>
            <tr><th>Not known / don't want to provide:</th><td>$nk</td></tr>
            <tr><th>Was learner employed with current employer prior to starting this Programme?</th><td>$work_curr_emp_checked</td></tr>
            <tr><th>Learner is self-employed?</th><td>$SEI_checked</td></tr>
            <tr><th>Employer Name:</th><td>$tr->empStatusEmployer</td></tr>
            <tr><th>Sector of Employer:</th><td>$employerSector</td></tr>
            <tr><th>How long learner is employed?</th><td>$LOEDesc</td></tr>
            <tr><th>Working hours each week:</th><td>$EIIDesc</td></tr>
            <tr><th>Learner was un-employed before?</th><td>$LOUDesc</td></tr>
            <tr><th>Receive any benefits?</th><td>$BSIDesc</td></tr>
        </table>
    </div>
        <br>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important"><h4><strong>Declaration & Data Protection</strong></h4></th></tr>
        <tr>
            <td>
                <p>Under its agreement ELA Training Services will satisfy itself that the Learner is trained in a safe environment.</p>
                <p>The information you supply will be used by the Education & Skills Funding Agency, an Executive Agency of the Department for Business, Innovation and Skills, to issue you (if you do not already have one) with a Unique Learner Number (ULN), and to create your Personal Learning Record. For more information about how your information is processed and shared, please refer to the Department for Education (DfE) Privacy Notice. </p>
                <p>Other organisations with which we will share information include Department for Work & Pensions, Local and Combined Authorities in England, Greater London Authority, Higher Education Statistics Agency, Office for Standards in Education, and educational institutions and organisations performing research and statistical work on behalf of the Department for Education, or partners of those organisations.</p> 
            </td>
        </tr>
    </table>
HTML;

        $selected_rui = explode(',', $tr->RUI);
        $selected_pmc = explode(',', $tr->PMC);

        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="2">Learner <u>agrees</u> to be contacted as follows:</th></tr>';
        echo '<tr><th>About courses or learning opportunities:</th><td>' . (in_array(1, $selected_rui) ? 'Yes' : '') . '</td></tr>';
        echo '<tr><th>For surveys and research:</th><td>' . (in_array(2, $selected_rui) ? 'Yes' : '') . '</td></tr>';
        echo '<tr><th>By post:</th><td>' . (in_array(1, $selected_pmc) ? 'Yes' : '') . '</td></tr>';
        echo '<tr><th>By phone:</th><td>' . (in_array(2, $selected_pmc) ? 'Yes' : '') . '</td></tr>';
        echo '<tr><th>By email:</th><td>' . (in_array(3, $selected_pmc) ? 'Yes' : '') . '</td></tr>';
        echo '</table>';
        echo '<br>';
        echo <<<HTML
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important"><h4><strong>Learner Declaration</strong></h4></th></tr>
        <tr>
            <td>
                <p>
                    I sign to confirm that the information I have given is correct and that I have read and understood the contents of this agreement. 
                    I have received relevant information, advice, and guidance from the representative of {$provider->legal_name}. 
                    I have discussed the options available to me and have agreed this is the most appropriate training and funding based on my previous achievements and future aspirations and eligibility. 
                </p>
                <p>I agree to the payment structure and total negotiated programme price as detailed in the Agreement. </p>
                <p>The evidence I have provided confirms my eligibility to participate in this programme. </p>
                <p>Should I not have already provided my Unique Learner Number (ULN), I consent to {$provider->legal_name} accessing this on my behalf.</p> 
                <p>{$provider->legal_name} complaints procedure has been explained to me and should I wish to escalate any concerns.</p>
            </td>
        </tr>
    </table>

    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important"><h4><strong>Training Provider Declaration</strong></h4></th></tr>
        <tr>
            <td>
                <p>I sign to confirm that, to the best of my knowledge, the information on this Agreement is correct. </p>
                <p>I declare that I have supported the learner in the completion of this document where necessary, and that the above-named learner meets the eligibility conditions to commence on this programme. </p>
                <p>I can confirm that all parties will receive a copy of this learning agreement. </p>
                <p>The training programme has been explained to the Learner and Employer and will define the training and competence objectives to be achieved. </p>
                <p>{$provider->legal_name} representatives will undertake regular reviews of progress and provide the Learner with on-going information, advice, and guidance. </p>
                <p>{$provider->legal_name} representatives will support the Learner to achieve the learning aims and objectives outlined in this agreement. </p>
                <p>Alongside this, the Learner will be given access to their portfolio system which will show details of their training programme at the commencement of training and will be updated throughout the programme. </p>
            </td>
        </tr>
    </table>


               
</div>
HTML;

        $learner_sign_date = Date::toShort($tr->learner_sign_date);
        $provider_sign_date = Date::toShort($tr->tp_sign_date);
        $emp_sign_date = Date::toShort($tr->emp_sign_date);

        $employer_signature_file = 'do.php?_action=generate_image&' . ($tr->emp_sign != '' ? $tr->emp_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25');

        $empSigns = '';
        if($framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL)
        {
            $empSigns = <<<HTML
    <tr>
        <td>Employer</td>
        <td>{$tr->emp_sign_name}</td>
        <td><img src="$employer_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
        <td>{$emp_sign_date}</td>
    </tr>
HTML;
        }

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
    {$empSigns}
    <tr>
        <td>Provider</td>
        <td>{$tr->tp_sign_name}</td>
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

        $mpdf->Output($c_file, 'F');
    }
}

