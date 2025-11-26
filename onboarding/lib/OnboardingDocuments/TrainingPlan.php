<?php
class TrainingPlan
{
    public static function toPdf(PDO $link, TrainingRecord $tr, $c_file, $learner_signature_file, $provider_signature_file, $employer_signature_file)
    {
        $ob_learner = $tr->getObLearnerRecord($link);
        //$c_file = in_array("CS", explode(",", $tr->generate_pdfs)) ? $onboarding_dir.'Training Plan_'.uniqid().'.pdf' : $c_file;

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tr->provider_id}'");
        if ($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

        $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
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
                <td width = "35%" align="left" style="font-size: 10px">Training Plan</td>
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
        $employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);
        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        $provider_location = Location::loadFromDatabase($link, $tr->provider_location_id);
        $epa_name = $tr->getEpaOrgName($link);
        if ($tr->trainers != '')
            $trainer = User::loadFromDatabaseById($link, $tr->trainers);
        else
            $trainer = new User();


        $sub_legal = $tr->getSubcontractorLegalName($link);
        $subcontractor_name = $sub_legal != '' ? $sub_legal : 'NA';
        $standard_title = $framework->getStandardCodeDesc($link);
        $standard_level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");
        $practical_period_start_date = Date::toShort($tr->practical_period_start_date);
        $practical_period_end_date = Date::toShort($tr->practical_period_end_date);
        $apprenticeship_start_date = Date::toShort($tr->apprenticeship_start_date);
        $apprenticeship_end_date_inc_epa = Date::toShort($tr->apprenticeship_end_date_inc_epa);
        $dob = Date::toShort($ob_learner->dob);
        $age_at_start_sql = <<<SQL
SELECT 
((DATE_FORMAT('$tr->apprenticeship_start_date','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
        $age_at_start = DAO::getSingleValue($link, $age_at_start_sql);
        $gender = $ob_learner->gender == "F" ? "Female" : $ob_learner->gender;
        $gender = $ob_learner->gender == "M" ? "Male" : $gender;
        $ethnicity = DAO::getSingleValue($link, "SELECT Ethnicity_Desc FROM lis201213.ilr_ethnicity WHERE Ethnicity = '{$ob_learner->ethnicity}';");
        $hhs = '';
        $hhs_list = LookupHelper::getListHhs();
        $selected_hhs = explode(",", $tr->hhs);
        foreach ($hhs_list as $hhs_key => $hhs_option) {
            $hhs .= $hhs_option;
            $hhs .= in_array($hhs_key, $selected_hhs) ? ' - <strong> YES </strong><hr>' : '<hr>';
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
        $saved_llddcat = $tr->llddcat != '' ? explode(",", $tr->llddcat) : [];
        foreach ($saved_llddcat as $_llddcat)
            $llddcats .= isset($LLDDCat[$_llddcat]) ? $LLDDCat[$_llddcat] . '<br>' : $_llddcat . '<br>';

        $_primary_lldd = isset($LLDDCat[$tr->primary_lldd]) ? $LLDDCat[$tr->primary_lldd] : $tr->primary_lldd;

        $learner_address = $ob_learner->home_address_line_1;
        $learner_address .= $ob_learner->home_address_line_2 != '' ? '<br>' . $ob_learner->home_address_line_2 : '';
        $learner_address .= $ob_learner->home_address_line_3 != '' ? '<br>' . $ob_learner->home_address_line_3 : '';
        $learner_address .= $ob_learner->home_address_line_4 != '' ? '<br>' . $ob_learner->home_address_line_4 : '';

        $funding_band_maximum = $framework->getFundingBandMax($link);
        $recommended_duration = $framework->getRecommendedDuration($link);

        $tnp1_prices = is_null($tr->tnp1) ? [] : json_decode($tr->tnp1);
        $tnp1_costs = array_map(function ($ar) {
            return $ar->cost;
        }, $tnp1_prices);
        $tnp1_total = array_sum(array_map('floatval', $tnp1_costs));
        $tnp_rows = '';
        foreach ($tnp1_prices as $price_item) {
            $tnp_rows .= '<tr>';
            $tnp_rows .= '<th>' . $price_item->description . ' (TNP 1)</th>';
            $tnp_rows .= '<td>&pound;' . $price_item->cost . '</td>';
            $tnp_rows .= '</tr>';
        }
        $tnp = 0;
        if(false && $tr->practical_period_start_date >= '2025-07-31' && isset($skills_analysis->epa_price_fa) && $skills_analysis->epa_price_fa != '')
        {
            $tnp1_total = ceil( floatval($tnp1_total) + floatval($skills_analysis->epa_price_fa) - floatval($skills_analysis->epa_price) );
            $tnp = $tnp1_total + $tr->epa_price_fa;
        }
        else
        {
            $tnp = ceil($tnp1_total + $tr->epa_price);
            $tnp1_total = ceil($tnp1_total);
        }

        $table_result_of_skills_scan = "";

        $prp = '';
        if ($tr->underSixHoursPerWeekRule())
            $prp = '<tr><th>Price Reduction Percentage:</th><td>' . $skills_analysis->price_reduction_percentage . ' %</td></tr>';

        $training_cost = floatval($tnp) - floatval($tr->epa_price);
        $learner_age = DAO::getSingleValue($link, "SELECT ((DATE_FORMAT('{$tr->practical_period_start_date}','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT('{$tr->practical_period_start_date}','00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age");
        $employer_contributions = '';
        if ($tr->practical_period_start_date < '2024-04-01') {
            if ($employer->levy_employer == 0 && $learner_age >= 19 && $tr->type_of_funding != 'Levy Gifted') {
                $_p1 = ceil($training_cost * 0.05);
                $_p2 = ceil($tr->epa_price * 0.05);
                $_emp_t = $_p1 + $_p2;
                $employer_contributions = '<p>';
                $employer_contributions .= '<div style="text-align: center;">';
                $employer_contributions .= '<table border="1" style="width: 100%;" cellpadding="6">';
                $employer_contributions .= '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Employer Contributions</strong></h4></th></tr>';
                $employer_contributions .= '<tr><th>Training Cost (Employer 5%):</th><td>&pound;' . $_p1 . '</td></tr>';
                $employer_contributions .= '<tr><th>Assessment Cost (Employer 5%):</th><td>&pound;' . $_p2 . '</td></tr>';
                $employer_contributions .= '<tr><th>Total (Employer 5%):</th><td>&pound;' . $_emp_t . '</td></tr>';
                $employer_contributions .= '</table>';
                $employer_contributions .= '</div>';
                $employer_contributions .= '</p>';
            }
        } else {
            if ($employer->levy_employer == 0 && $learner_age > 21 && $tr->type_of_funding != 'Levy Gifted') {
                $_p1 = ceil($training_cost * 0.05);
                $_p2 = ceil($tr->epa_price * 0.05);
                $_emp_t = $_p1 + $_p2;
                $employer_contributions = '<p>';
                $employer_contributions .= '<div style="text-align: center;">';
                $employer_contributions .= '<table border="1" style="width: 100%;" cellpadding="6">';
                $employer_contributions .= '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Employer Contributions</strong></h4></th></tr>';
                $employer_contributions .= '<tr><th>Training Cost (Employer 5%):</th><td>&pound;' . $_p1 . '</td></tr>';
                $employer_contributions .= '<tr><th>Assessment Cost (Employer 5%):</th><td>&pound;' . $_p2 . '</td></tr>';
                $employer_contributions .= '<tr><th>Total (Employer 5%):</th><td>&pound;' . $_emp_t . '</td></tr>';
                $employer_contributions .= '</table>';
                $employer_contributions .= '</div>';
                $employer_contributions .= '</p>';
            }
        }

        if (true) {
            $table_result_of_skills_scan = <<<HTML
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Results of the Skills Analysis (taking into account all prior learning)</strong></h4></th></tr>
    <tr><th>Duration:</th><td>$tr->duration_practical_period</td></tr>
    $tnp_rows
    <tr><th>Total (TNP 1):</th><td>&pound;$tnp1_total</td></tr>
    <tr><th>End Point Assessment (TNP 2):</th><td>&pound;$tr->epa_price</td></tr>
    <tr><th>Total Negotiated Price:</th><td>&pound;$tnp</td></tr>
    <tr><th>Percentage of Skills Analysis:</th><td>$skills_analysis->percentage_fa %</td></tr>
    $prp
    <tr><td colspan="2"><strong>Rationale / Justification:</strong><br> $skills_analysis->rationale_by_provider</td></tr>
</table>
</div>            
$employer_contributions             
HTML;
        }

        $shift_patterns = DAO::getObject($link, "SELECT * FROM ob_learner_shift_pattern WHERE tr_id = '{$tr->id}'");
        $shift_patterns_details = '';
        if (isset($shift_patterns->tr_id) && $shift_patterns->tr_id) { 
            $shift_patterns_details .= 'Monday: ' . $shift_patterns->Mon_start . ' - ' . $shift_patterns->Mon_end . '<br>';
            $shift_patterns_details .= 'Tuesday: ' . $shift_patterns->Tue_start . ' - ' . $shift_patterns->Tue_end . '<br>';
            $shift_patterns_details .= 'Wednesday: ' . $shift_patterns->Wed_start . ' - ' . $shift_patterns->Wed_end . '<br>';
            $shift_patterns_details .= 'Thursday: ' . $shift_patterns->Thu_start . ' - ' . $shift_patterns->Thu_end . '<br>';
            $shift_patterns_details .= 'Friday: ' . $shift_patterns->Fri_start . ' - ' . $shift_patterns->Fri_end . '<br>';
            $shift_patterns_details .= 'Saturday: ' . $shift_patterns->Sat_start . ' - ' . $shift_patterns->Sat_end . '<br>';
            $shift_patterns_details .= 'Sunday: ' . $shift_patterns->Sun_start . ' - ' . $shift_patterns->Sun_end . '<br>';
        }
        if ($shift_patterns_details != '') {
            $shift_patterns_details = '<tr><th>Shift Pattern:</th><td>' . $shift_patterns_details . '</td></tr>';
        }
        $wages_and_employment_info = '';
        $wages_and_employment = DAO::getObject($link, "SELECT * FROM ob_learner_wae WHERE tr_id = '{$tr->id}'");
        if (isset($wages_and_employment->tr_id)) {
            if ($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link))) {
                $wages_and_employment_info = <<<HTML
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Apprenticeship Wages and Employment</strong></h4></th></tr>
    <tr><td colspan="2">Please confirm that the following agreements have been made for the apprenticeship to be eligible for apprenticeship funding.</td></tr>
    <tr><td>The apprentice is receiving a wage in line with the national minimum wage requirements</td><td>$wages_and_employment->opt1</td></tr>
    <tr><td>The apprentice rate was not used prior to a valid apprenticeship agreement being in place</td><td>$wages_and_employment->opt2</td></tr>
    <tr><td>The apprentice is included in the PAYE Scheme declared in the Apprenticeship Service account (Y/N)</td><td>$wages_and_employment->opt3</td></tr>
    <tr><td>The apprentice will be provided with the time required to undertake all off the job training requirements (20% or 6 hrs/wk) 
        within their normal hours of work in addition to any English and Maths requirements that they might have (Y/N)</td><td>$wages_and_employment->opt4</td></tr>
</table>
</div>

HTML;
            } else {
                $wages_and_employment_info = <<<HTML
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Apprenticeship Wages and Employment</strong></h4></th></tr>
    <tr><td colspan="2">Please confirm that the following agreements have been made for the apprenticeship to be eligible for apprenticeship funding.</td></tr>
    <tr><td>The apprentice is receiving a wage in line with the national minimum wage requirements</td><td>$wages_and_employment->opt1</td></tr>
    <tr><td>The apprentice rate was not used prior to a valid apprenticeship agreement being in place</td><td>$wages_and_employment->opt2</td></tr>
    <tr><td>The apprentice rate will not used prior to a valid apprenticeship agreement being in place</td><td>$wages_and_employment->opt3</td></tr>
    <tr><td>The apprentice will be provided with the time required to undertake all off the job training requirements within their normal hours of work in addition to any English and Maths requirements that they might have</td><td>$wages_and_employment->opt4</td></tr>
</table>
</div>

HTML;
            }
        }

        $care_leaver_row = $tr->care_leaver == '1' ? 'Yes' : 'No';
        $care_leaver_row .= ($tr->care_leaver == '1' && $tr->care_leaver_evidence_file != '') ? ' (Evidence is provided)' : (($tr->care_leaver == '1' && $tr->care_leaver_evidence_file == '') ? ' (Evidence is not provided)' : '');

        $gender = DAO::getSingleValue($link, "SELECT description FROM lookup_gender WHERE id = '{$ob_learner->gender}'");

        $LOE_dropdown = array('1' => 'Up to 3 months', '2' => '4-6 months', '3' => '7-12 months', '4' => 'more than 12 months');
        $EII_dropdown = array('5' => '0-10 hours per week', '6' => '11-20 hours per week', '7' => '21-30 hours per week', '8' => '30 hours or more per week');
        $LOU_dropdown = array('1' => 'unemployed for less than 6 months', '2' => 'unemployed for 6-11 months', '3' => 'unemployed for 12-23 months', '4' => 'unemployed for 24-35 months', '5' => 'unemployed for over 36 months');
        $BSI_dropdown = array('1' => 'JSA', '2' => 'ESA WRAG', '3' => 'Another state benefit', '4' => 'Universal Credit');

        $employment_status_html = '<tr>';
        $employment_status_html .= '<th>What learner did prior to starting Apprenticeship Programme on the ' . Date::toLong($tr->apprenticeship_start_date) . '</th>';
        if ($tr->EmploymentStatus == '10')
            $employment_status_html .= '<td>In paid employment</td>';
        elseif ($tr->EmploymentStatus == '11')
            $employment_status_html .= '<td>Not in paid employment, looking for work and available to start work</td>';
        elseif ($tr->EmploymentStatus == '12')
            $employment_status_html .= '<td>Not in paid employment, not looking for work and/or not available to start work</td>';
        elseif ($tr->EmploymentStatus == '98')
            $employment_status_html .= '<td>Not known / don\'t want to provide</td>';
        else
            $employment_status_html .= '<td></td>';
        $employment_status_html .= '</tr>';
        if ($tr->EmploymentStatus == '10') {
            $employment_status_html .= '<tr>';
            $employment_status_html .= '<th>Was the learner employed with current employer prior to starting Apprenticeship Programme? </th>';
            $employment_status_html .= $tr->work_curr_emp == '1' ? '<td>Yes</td>' : '<td>No</td>';
            $employment_status_html .= '</tr>';
            $employment_status_html .= '<tr>';
            $employment_status_html .= '<th>If not, was the learner self-employed? </th>';
            $employment_status_html .= $tr->SEI == '1' ? '<td>Yes</td>' : '<td>No</td>';
            $employment_status_html .= '</tr>';
            $employment_status_html .= '<tr>';
            $employment_status_html .= '<th>Employer Name </th>';
            $employment_status_html .= '<td>' . $tr->empStatusEmployer . '</td>';
            $employment_status_html .= '</tr>';
            $employment_status_html .= '<tr>';
            $employment_status_html .= '<th>How long the learner was employed? </th>';
            $employment_status_html .= isset($LOE_dropdown[$tr->LOE])  ? '<td>' . $LOE_dropdown[$tr->LOE] . '</td>' : '<td></td>';
            $employment_status_html .= '</tr>';
            $employment_status_html .= '<tr>';
            $employment_status_html .= '<th>How many hours did learner work each week? </th>';
            $employment_status_html .= isset($EII_dropdown[$tr->EII])  ? '<td>' . $EII_dropdown[$tr->EII] . '</td>' : '<td></td>';
            $employment_status_html .= '</tr>';
        }
        if ($tr->EmploymentStatus == '11' || $tr->EmploymentStatus == '12') {
            $employment_status_html .= '<tr>';
            $employment_status_html .= '<th>How long the learner was un-employed before ' . Date::toLong($tr->apprenticeship_start_date) . '? </th>';
            $employment_status_html .= isset($LOU_dropdown[$tr->LOU])  ? '<td>' . $LOU_dropdown[$tr->LOU] . '</td>' : '<td></td>';
            $employment_status_html .= '</tr>';
            $employment_status_html .= '<tr>';
            $employment_status_html .= '<th>Did learner receive any of these benefits? </th>';
            $employment_status_html .= isset($BSI_dropdown[$tr->BSI])  ? '<td>' . $BSI_dropdown[$tr->BSI] . '</td>' : '<td></td>';
            $employment_status_html .= '</tr>';
            $employment_status_html .= '<tr>';
            $employment_status_html .= '<th>Was the learner in Full Time Education or Training prior to ' . Date::toLong($tr->apprenticeship_start_date) . '? </th> ';
            $employment_status_html .= $tr->PEI == '1' ? '<td>Yes</td>' : '<td>No</td>';
            $employment_status_html .= '</tr>';
        }

        $emergency_contacts_html = '';
        $emergency_contacts_result = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts WHERE tr_id = '{$tr->id}'", DAO::FETCH_ASSOC);
        foreach ($emergency_contacts_result as $emergency_contacts_row) {
            if (trim($emergency_contacts_row['em_con_name'] ?? '') == '') {
                continue;
            }

            $emergency_contacts_html .= '<i>' . $emergency_contacts_row['em_con_rel'] . '</i><br>';
            $emergency_contacts_html .= $emergency_contacts_row['em_con_title'] . ' ' . $emergency_contacts_row['em_con_name'] . '<br>';
            $emergency_contacts_html .= $emergency_contacts_row['em_con_tel'] . '<br>';
            $emergency_contacts_html .= $emergency_contacts_row['em_con_mob'] . '<hr>';
        }

        $contry_of_birth = DAO::getSingleValue($link, "SELECT country_name FROM lookup_countries WHERE id = '{$tr->country_of_birth}';");
        $country_of_perm_residence = DAO::getSingleValue($link, "SELECT country_name FROM lookup_countries WHERE id = '{$tr->country_of_perm_residence}';");
        $nationality = DAO::getSingleValue($link, "SELECT description FROM lookup_nationalities WHERE id = '{$tr->nationality}';");

        $total_weeks_on_programme = DAO::getSingleValue($link, "SELECT ROUND(DATEDIFF('{$tr->practical_period_end_date}', '{$tr->practical_period_start_date}')/7)");

        $line_manager_details = '';
        if ($tr->line_manager_id != '') {
            $tr_line_manager = DAO::getObject($link, "SELECT * FROM organisation_contacts WHERE contact_id = '{$tr->line_manager_id}'");
            if (isset($tr_line_manager->contact_id)) {
                $line_manager_details .= $tr_line_manager->contact_title . ' ' . $tr_line_manager->contact_name . '<br>';
                $line_manager_details .= $tr_line_manager->job_title != '' ? $tr_line_manager->job_title . '<br>' : '';
                $line_manager_details .= $tr_line_manager->contact_telephone != '' ? 'Tel: ' . $tr_line_manager->contact_telephone . '<br>' : '';
                $line_manager_details .= $tr_line_manager->contact_mobile != '' ? 'Mob: ' . $tr_line_manager->contact_mobile . '<br>' : '';
                $line_manager_details .= $tr_line_manager->contact_email != '' ? 'Email: ' . $tr_line_manager->contact_email . '<br>' : '';
            }
        }

        $eligibility_rows = '';
        $saved_eligibility_list = explode(',', $tr->EligibilityList ?? '');
        if (in_array(1, $saved_eligibility_list)) {
            $eligibility_rows .= '<tr><th>Has learner lived within UK/EU for the last 3 years?</th><td>Yes</td></tr>';
        } else {
            $eligibility_rows .= '<tr><th>Has learner lived within UK/EU for the last 3 years?</th><td>No</td></tr>';
        }
        if (in_array(2, $saved_eligibility_list)) {
            $eligibility_rows .= '<tr>';
            $eligibility_rows .= '<th>Has the learner currently enrolled at any other college or training provider?</th>';
            $eligibility_rows .= '<td>';
            $eligibility_rows .= 'Yes <br>' . $tr->currently_enrolled_in_other;
            $eligibility_rows .= '</td></tr>';
        } else {
            $eligibility_rows .= '<tr><th>Has the learner currently enrolled at any other college or training provider?</th><td>No</td></tr>';
        }
        $eligibility_rows .= '<tr><th>Has the learner previously had access to a student loan?</th><td>' . ($tr->had_student_loan == 1 ? 'Yes' : ($tr->had_student_loan == 2 ? 'No' : '')) . '</td></tr>';
        if ($tr->had_student_loan == 1)
            $eligibility_rows .= '<tr><th>If student loan, has this been terminated and learner no longer receiving funding from the Student Loans Company?</th><td>' . ($tr->student_loan_terminated == 1 ? 'Yes' : ($tr->student_loan_terminated == 2 ? 'No' : '')) . '</td></tr>';
        if (DB_NAME == "am_superdrug" && $tr->free_school_meals != "") {
            $eligibility_rows .= '<tr><th>When learner was in secondary school, was learner eligible for free school meals?</th><td>' . ($tr->free_school_meals == 1 ? 'Yes' : ($tr->free_school_meals == 2 ? 'No' : '')) . '</td></tr>';
        }

        $id_evidence_desc = DAO::getSingleValue($link, "SELECT description FROM lookup_id_evidence_types WHERE id = '{$tr->id_evidence_type}'");
        $evidence_expiry_date = Date::toShort($tr->evidence_expiry_date);

        $criminalConvictionQuestion = '';
        if (DB_NAME == "am_ela") {
            $criminalConvictionQuestion .= '<div style="text-align: center;">';
            $criminalConvictionQuestion .= '<table border="1" style="width: 100%;" cellpadding="6">';
            $criminalConvictionQuestion .= '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Rehabilitation of Offenders</strong></h4></th></tr>';
            $criminalConvictionQuestion .= '<tr>';
            $criminalConvictionQuestion .= '<th style="width: 70%">Do you have any criminal convictions except those for minor motoring offences or those spent in accordance with the Rehabilitation of Offenders Act 1974?</th>';
            $criminalConvictionQuestion .= '<td> ' . $tr->crime_conviction . '</td>';
            $criminalConvictionQuestion .= '</tr>';
            $criminalConvictionQuestion .= '</table>';
            $criminalConvictionQuestion .= '</div>';
            $criminalConvictionQuestion .= '<p></p>';
        }

        $employerName = in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]) ? $employer->brandDescription($link) : $employer->legal_name;
        $employerMainLocation = $employer->getMainLocation($link);
        $employerLocation = $employerMainLocation->address_line_1 != '' ? $employerMainLocation->address_line_1 . '<br>' : '';
        $employerLocation .= $employerMainLocation->address_line_2 != '' ? $employerMainLocation->address_line_2 . '<br>' : '';
        $employerLocation .= $employerMainLocation->address_line_3 != '' ? $employerMainLocation->address_line_3 . '<br>' : '';
        $employerLocation .= $employerMainLocation->address_line_4 != '' ? $employerMainLocation->address_line_4 . '<br>' : '';
        $employerLocation .= $employerMainLocation->postcode != '' ? $employerMainLocation->postcode : '';

        $trainerLabel = DB_NAME == "am_superdrug" ? "Tutor" : "Trainer/Assessor";

        $selected_rui = explode(',', $tr->RUI ?? '');
        $selected_pmc = explode(',', $tr->PMC ?? '');

        $contact_preferences = 'About courses or learning opportunities: ' . (in_array(1, $selected_rui) ? '<strong>YES</strong>' : '<strong>NO</strong>') . '<hr>';
        $contact_preferences .= 'For surveys and research: ' . (in_array(2, $selected_rui) ? '<strong>YES</strong>' : '<strong>NO</strong>') . '<hr>';
        $contact_preferences .= 'By post: ' . (in_array(1, $selected_pmc) ? '<strong>YES</strong>' : '<strong>NO</strong>') . '<hr>';
        $contact_preferences .= 'By phone: ' . (in_array(2, $selected_pmc) ? '<strong>YES</strong>' : '<strong>NO</strong>') . '<hr>';
        $contact_preferences .= 'By email: ' . (in_array(3, $selected_pmc) ? '<strong>YES</strong>' : '<strong>NO</strong>') . '<hr>';

        if (DB_NAME == "am_superdrug") {
            $shift_patterns_details = "";
        }

        echo <<<HTML
<div style="text-align: center;">
<h2><strong>Training Plan</strong></h2>
</div>
<p></p>
<p>The training plan is a document which will set out the plan for the apprenticeship programme and ensure that all parties (Employer, Apprentice, Training Provider) are aware of their own and other roles and responsibilities when undertaking the apprenticeship programme.</p>
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Apprentice Details</strong></h4></th></tr>
    <tr><th>Title:</th><td>$ob_learner->learner_title</td></tr>
    <tr><th>Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td></tr>
    <tr><th>Gender:</th><td>$gender</td></tr>
    <tr><th>Date of Birth:</th><td>$dob</td></tr>
    <tr><th>Ethnicity:</th><td>$ethnicity</td></tr>
    <tr><th>ULN:</th><td>$ob_learner->uln</td></tr>
    <tr><th>National Insurance:</th><td>$ob_learner->ni</td></tr>
    <tr><th>Personal Email:</th><td>$ob_learner->home_email</td></tr>
    <tr><th>Work Email:</th><td>$ob_learner->work_email</td></tr>
    <tr><th>Telephone/Mobile:</th><td>$tr->home_telephone / $tr->home_mobile</td></tr>
    <tr><th>Address:</th><td>$learner_address</td></tr>
    <tr><th>Postcode:</th><td>$ob_learner->home_postcode</td></tr>
    <tr><th>Job Title:</th><td>$tr->job_title</td></tr>
    <tr><th>Contracted Hours per week:</th><td>$tr->contracted_hours_per_week</td></tr>
    $shift_patterns_details
    <tr><th>Is apprentice a care leaver:</th><td>$care_leaver_row</td></tr>
    <tr><th>Does learner have a learning difficulty, health problem or disability:</th><td>$lldd_desc</td></tr>
    <tr><th>LLDD Categories:</th><td>$llddcats</td></tr>
    <tr><th>Primary LLDD:</th><td>$_primary_lldd</td></tr>
    <tr><th>Household Situation:</th><td>{$hhs}</td></tr>
    <tr><th>Contact Preferences:</th><td>$contact_preferences</td></tr>
    <tr><th>Country of birth:</th><td>$contry_of_birth</td></tr>
    <tr><th>Country of permanent residence:</th><td>$country_of_perm_residence</td></tr>
    <tr><th>Nationality:</th><td>$nationality</td></tr>
    <tr><th>ID Evidence Type:</th><td>$id_evidence_desc</td></tr>
    <tr><th>ID Evidence Reference:</th><td>$tr->evidence_reference</td></tr>
    <tr><th>Date of Expiry:</th><td>$evidence_expiry_date</td></tr>
    <tr><th valign="top">Emergency Contact(s):</th><td>$emergency_contacts_html</td></tr>
    $eligibility_rows
</table>
</div>
<p></p>
$criminalConvictionQuestion
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Employment Status</strong></h4></th></tr>
        $employment_status_html    
    </table>
</div>
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Employer</strong></h4></th></tr>
    <tr><th>Name:</th><td>$employerName <br> $employerLocation</td></tr>
    <tr><th>Employer Mentor:</th><td>$employer_location->contact_name</td></tr>
    <tr><th>Line Manager / Supervisor:</th><td>$line_manager_details</td></tr>
    <tr><th>Address:</th><td>$employer_location->address_line_1 $employer_location->address_line_2 $employer_location->address_line_3 $employer_location->address_line_4 </td></tr>
    <tr><th>Postcode:</th><td>$employer_location->postcode</td></tr>
    <tr><th>Email:</th><td>$employer_location->contact_email</td></tr>
    <tr><th>Telephone/Mobile:</th><td>$employer_location->contact_telephone / $employer_location->contact_mobile</td></tr>
</table>
</div>
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Main Training Provider</strong></h4></th></tr>
    <tr><th>Name:</th><td>$provider->legal_name</td></tr>
    <tr><th>UKPRN:</th><td>$provider->ukprn</td></tr>
    <tr><th>Delivery Location Address:</th><td>$provider_location->address_line_1 $provider_location->address_line_2 $provider_location->address_line_3 $provider_location->address_line_4 </td></tr>
    <tr><th>Postcode:</th><td>$provider_location->postcode</td></tr>
    <tr><th>$trainerLabel:</th><td>$trainer->firstnames $trainer->surname</td></tr>
    <tr><th>$trainerLabel Email:</th><td>$trainer->work_email</td></tr>
    <tr><th>Telephone / Mobile:</th><td>$trainer->work_telephone / $trainer->work_mobile</td></tr>
</table>
</div>
$wages_and_employment_info
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Standard</strong></h4></th></tr>
    <tr><th>Standard Title:</th><td>$standard_title</td></tr>
    <tr><th>Level:</th><td>$standard_level</td></tr>
    <tr><th>Price (top of funding band):</th><td>&pound;$funding_band_maximum</td></tr>
    <tr><th>Recommended Duration - months:</th><td>$recommended_duration</td></tr>
</table>
</div>
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Individualised Apprenitceship Details</strong></h4></th></tr>
    <tr><th>Start Date of Practical Period:</th><td>$practical_period_start_date</td></tr>
    <tr><th>Planned End Date of Practical Period:</th><td>$practical_period_end_date</td></tr>
    <tr><th>Duration of Practical Period - months:</th><td>$tr->duration_practical_period</td></tr>
    <tr><th>Start Date of Apprenticeship:</th><td>$apprenticeship_start_date</td></tr>
    <tr><th>Planned End date of Apprenticeship (incl EPA):</th><td>$apprenticeship_end_date_inc_epa</td></tr>
    <tr><th>Duration of Full Apprenticeship (incl EPA) - months:</th><td>$tr->apprenticeship_duration_inc_epa</td></tr>
<tr><th>Total weeks on programme - weeks:</th><td>$total_weeks_on_programme</td></tr>
</table>
</div>
<p></p>
$table_result_of_skills_scan        
<p></p>



HTML;
        $subcontractor = Organisation::loadFromDatabase($link, $tr->subcontractor_id);
        $subcontractor_location = Location::loadFromDatabase($link, $tr->subcontractor_location_id);
        $subcontractor_name = !is_null($subcontractor) ? $subcontractor->legal_name : 'NA';
        $subcontractor_ukprn = !is_null($subcontractor) ? $subcontractor->ukprn : '';
        $subcontractor_address = !is_null($subcontractor_location) ?
            $subcontractor_location->address_line_1 . ' ' .
            $subcontractor_location->address_line_2 . ' ' .
            $subcontractor_location->address_line_3 . ' ' .
            $subcontractor_location->address_line_4 : '';
        $subcontractor_postcode = !is_null($subcontractor_location) ? $subcontractor_location->postcode : '';

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

        echo <<<HTML
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Delivery Subcontractor (if applicable)</strong></h4></th></tr>
    <tr><th>Name:</th><td>$subcontractor_name</td></tr>
    <tr><th>UKPRN:</th><td>$subcontractor_ukprn</td></tr>
    <tr><th>Delivery Location Address:</th><td>$subcontractor_address</td></tr>
    <tr><th>Postcode:</th><td>$subcontractor_postcode</td></tr>
</table>
</div>
<p></p>
<div style="text-align: center;">
<table border="1" style="width: 100%;" cellpadding="6">
    <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>End Point Assessment Organisation (EPAO)</strong></h4></th></tr>
    <tr><th>EPAO Name:</th><td>$epa_name</td></tr>
    <tr><th>Planned EPA Date:</th><td>$apprenticeship_end_date_inc_epa</td></tr>
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

        $ageAtStart = 0;
        if (!empty($tr->practical_period_start_date) && !empty($ob_learner->dob)) {
            $ageAtStart = Date::dateDiffInfo($tr->practical_period_start_date, $ob_learner->dob);
            $ageAtStart = isset($ageAtStart["year"]) ? $ageAtStart["year"] : 0;
        }
        if ($tr->practical_period_start_date >= '2025-04-01' && !$tr->isNonApp($link)) {
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Functional skills declaration and waiver</strong></h4></th></tr>';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><strong>Please indicate the agreed decision regarding the functional skills - ENGLISH</strong></th></tr>';
            echo '<tr>';
            echo '<td style="width: 10%;" align="center"><img src="' . ($ageAtStart < 19 ? 'images/checked_checked.png' : 'images/checked_blank.png') . '" height="15px" width="15px" /></td>';
            echo '<td style="width: 90%;">The apprentice is 16-18, does not hold acceptable equivalents and must undertake functional skills training and all parties agree to the programme as described above.</td>';
            echo '</tr>';
            if ($age_at_start < 19) {
                echo '<tr>';
                echo '<td style="width: 10%;" align="center"><img src="images/checked_blank.png" height="15px" width="15px" /></td>';
                echo '<td style="width: 90%;">The apprentice is aged 19 or over and it is agreed that a functional skills programme will take place and that the apprentice commits to participate fully and sit all necessary assessments.</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td style="width: 10%;" align="center"><img src="images/checked_blank.png" height="15px" width="15px" /></td>';
                echo '<td style="width: 90%;">The apprentice is aged 19 or over and it is agreed that a functional skills programme will not take place. ';
                echo 'Parties choose to decline functional skills training and understand the implications of this decision by waving their right to have a functional skills programme.</td>';
                echo '</tr>';
            } else {
                echo '<tr>';
                echo '<td style="width: 10%;" align="center"><img src="' . ($tr->fs_eng_opt_in == 'Yes' ? 'images/checked_checked.png' : 'images/checked_blank.png') . '" height="15px" width="15px" /></td>';
                echo '<td style="width: 90%;">The apprentice is aged 19 or over and it is agreed that a functional skills programme will take place and that the apprentice commits to participate fully and sit all necessary assessments.</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td style="width: 10%;" align="center"><img src="' . ($tr->fs_eng_opt_in == 'No' ? 'images/checked_checked.png' : 'images/checked_blank.png') . '" height="15px" width="15px" /></td>';
                echo '<td style="width: 90%;">The apprentice is aged 19 or over and it is agreed that a functional skills programme will not take place. ';
                echo 'Parties choose to decline functional skills training and understand the implications of this decision by waving their right to have a functional skills programme.</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Functional skills declaration and waiver</strong></h4></th></tr>';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><strong>Please indicate the agreed decision regarding the functional skills - MATHS</strong></th></tr>';
            echo '<tr>';
            echo '<td style="width: 10%;" align="center"><img src="' . ($ageAtStart < 19 ? 'images/checked_checked.png' : 'images/checked_blank.png') . '" height="15px" width="15px" /></td>';
            echo '<td style="width: 90%;">The apprentice is 16-18, does not hold acceptable equivalents and must undertake functional skills training and all parties agree to the programme as described above.</td>';
            echo '</tr>';
            if ($age_at_start < 19) {
                echo '<tr>';
                echo '<td style="width: 10%;" align="center"><img src="images/checked_blank.png" height="15px" width="15px" /></td>';
                echo '<td style="width: 90%;">The apprentice is aged 19 or over and it is agreed that a functional skills programme will take place and that the apprentice commits to participate fully and sit all necessary assessments.</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td style="width: 10%;" align="center"><img src="images/checked_blank.png" height="15px" width="15px" /></td>';
                echo '<td style="width: 90%;">The apprentice is aged 19 or over and it is agreed that a functional skills programme will not take place. ';
                echo 'Parties choose to decline functional skills training and understand the implications of this decision by waving their right to have a functional skills programme.</td>';
                echo '</tr>';
            } else {
                echo '<tr>';
                echo '<td style="width: 10%;" align="center"><img src="' . ($tr->fs_maths_opt_in == 'Yes' ? 'images/checked_checked.png' : 'images/checked_blank.png') . '" height="15px" width="15px" /></td>';
                echo '<td style="width: 90%;">The apprentice is aged 19 or over and it is agreed that a functional skills programme will take place and that the apprentice commits to participate fully and sit all necessary assessments.</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td style="width: 10%;" align="center"><img src="' . ($tr->fs_maths_opt_in == 'No' ? 'images/checked_checked.png' : 'images/checked_blank.png') . '" height="15px" width="15px" /></td>';
                echo '<td style="width: 90%;">The apprentice is aged 19 or over and it is agreed that a functional skills programme will not take place. ';
                echo 'Parties choose to decline functional skills training and understand the implications of this decision by waving their right to have a functional skills programme.</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

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
        if (count($employment_records) > 0) {
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
        }

        if (in_array(DB_NAME, ["am_ela"])) {
            $extra_info = DAO::getObject($link, "SELECT * FROM ob_learner_extra_details WHERE tr_id = '{$tr->id}'");
            if (!isset($extra_info->tr_id)) {
                $extra_info = new stdClass();
                $ob_learner_extra_details_fields = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_extra_details");
                foreach ($ob_learner_extra_details_fields as $extra_info_key => $extra_info_value)
                    $extra_info->$extra_info_value = null;
            }
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr style="color: #000; background-color: #d2d6de !important"><th>Company induction - a full company induction has been provided or is planned to cover </th><th>Date Completed / Planned</th></tr>';
            echo '<tr>';
            echo '<td>A full workplace induction with the company that you will be completing your apprenticeship with</td>';
            echo '<td>' . Date::toShort($extra_info->induction_f1) . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Information about legislations and regulations which affect your job role including:
                                <ul style="margin-left: 10px;">
                                    <li>Health and Safety</li>
                                    <li>Data Protection</li>
                                    <li>Prohibitions and restrictions as applicable</li>
                                </ul>
                            </td>';
            echo '<td>' . Date::toShort($extra_info->induction_f2) . '</td>';
            echo '<tr>';
            echo '<td>Company Disciplinary and Grievance procedures including who you should talk to if you have a problem at work</td>';
            echo '<td>' . Date::toShort($extra_info->induction_f3) . '</td>';
            echo '<tr>';
            echo '<td>Information about your rights and responsibilities when you are working including:
                                <ul style="margin-left: 10px;">
                                    <li>Holiday entitlement</li>
                                    <li>Salary Information</li>
                                    <li>Absence reporting</li>
                                    <li>Attendance and Professional Codes of Conduct which are applicable</li>
                                </ul>
                            </td>';
            echo '<td>' . Date::toShort($extra_info->induction_f4) . '</td>';
            echo '</tr>';
            echo '<tr><td>Employment Contract</td><td>' . $extra_info->employment_contract . '</td></tr>';
            echo '</table><p></p>';
        }
        echo '<div style="text-align: center;">';
        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th>Contracted hours per week</th><td>' . $tr->contracted_hours_per_week . '</td></tr>';
        echo '<tr><th>Weeks to be worked per year</th><td>' . $tr->weeks_to_be_worked_per_year . '</td></tr>';
        echo '<tr><th>Total contracted hours per year</th><td>' . $tr->total_contracted_hours_per_year . '</td></tr>';
        echo '<tr><th>Total weeks on programme - weeks:</th><td>' . $total_weeks_on_programme . '</td></tr>';
        if ($tr->contracted_hours_per_week >= 30) {
            echo '<tr><td colspan="2"></td></tr><tr><th colspan="2" class="bg-green-gradient">Full Time Hours (30 or above)</th></tr>';
            echo '<tr><th>Length of Programme (Practical Period)</th><td>' . $tr->duration_practical_period . ' months</td></tr>';
            echo '<tr><th>Total Contracted Hours - Full Apprenticeship</th><td>' . $tr->total_contracted_hours_full_apprenticeship . ' hours</td></tr>';
            if (!$tr->underSixHoursPerWeekRule()) {
                echo '<tr class="bg-light-blue-gradient"><th>Minimum 20% OTJ Training</th><td>' . $tr->minimum_percentage_otj_training . ' hours</td></tr>';
            } else {
                if ($tr->otj_overwritten != '') {
                    echo '<tr class="bg-light-blue-gradient"><th>OTJ Hours</th><td>' . $tr->otj_overwritten . ' hours</td></tr>';
                } else {
                    echo '<tr class="bg-light-blue-gradient"><th>OTJ Hours</th><td>' . $tr->off_the_job_hours_based_on_duration . ' hours</td></tr>';
                }
            }

            if (DB_NAME == "am_crackerjack" && $tr->term_time == 1) {
                echo '<tr><td colspan="2">Term-time learner, Weekly OTJ hours are increased in order to meet the minimum 20% OJT funding requirement as per the funding rules.</td></tr>';
            }
        } else {
            echo '<tr><td colspan="2"></td></tr>';
            echo '<tr><th colspan="2" class="bg-green-gradient">Part Time Hours (less than 30)</th></tr>';
            echo '<tr><th>Minimum Duration (part time)</th><td>' . $tr->minimum_duration_part_time . ' months</td></tr>';
            echo '<tr><th>Total Contracted Hours - Full Apprenticeship</th><td>' . $tr->part_time_total_contracted_hours_full_apprenticeship . ' hours</td></tr>';
            echo '<tr class="bg-light-blue-gradient"><th>Minimum 20% OTJ Training</th><td>' . ($tr->otj_overwritten != '' ? $tr->otj_overwritten : $tr->part_time_otj_hours) . ' hours</td></tr>';
        }
        //echo '<tr class="bg-green-gradient"><th>Planned Delivery Hours (OTJ) following Skills Analysis</th><td>' . $skills_analysis->delivery_plan_hours_fa . '</td></tr>';
        echo '</table>';
        echo '</div><p></p><hr>';

        if (DB_NAME == "am_ela") {
            $additional_support = DAO::getObject($link, "SELECT * FROM ob_learner_additional_support WHERE tr_id = '{$tr->id}' AND learner_sign IS NOT NULL AND provider_sign IS NOT NULL");
            if (isset($additional_support->tr_id)) {
                $form_data = is_null($additional_support->form_data) ? null : json_decode($additional_support->form_data);
                echo '<h2><strong>Additional Learning Support</strong></h2>';
                echo '<table border="1" style="width: 100%;" cellpadding="6">';
                echo '<tr><th style="width: 40%">Question</th><th style="width: 20%">Yes/No</th><th style="width: 40%">Comments</th></tr>';
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
                    echo '<tr>';
                    echo '<th>' . $question['question'] . '</th>';
                    echo '<td>' . (isset($form_data->$answer_id) ? $form_data->$answer_id : '') . '</td>';
                    echo '<td>' . (isset($form_data->$comments_id) ? $form_data->$comments_id : '') . '</td>';
                    echo '</tr>';
                    if (isset($form_data->$answer_id) && $form_data->$answer_id == 'Yes') {
                        $als_total_yes++;
                    }
                    if (isset($form_data->$answer_id) && $form_data->$answer_id == 'No') {
                        $als_total_no++;
                    }
                }

                echo '<tr>';
                echo '<th colspan="3">';
                echo '<strong>Total Score: </strong>' . $als_total_yes . '/' . $als_total_no . '<br>';
                echo '<strong>Number of "Yes": </strong>' . $als_total_yes . '<br>';
                echo '<strong>Does the learner agree to a referral?: </strong>';
                echo isset($form_data->learnerAgreeT1) ? $form_data->learnerAgreeT1 : '';
                echo '</th></tr></table>';
            }
        } else {
            // echo '<h2><strong>Additional Learning Support</strong></h2>';
            // $als_records = DAO::getResultset($link, "SELECT * FROM ob_learner_als WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
            // echo '<div style="text-align: center;">';
            // echo '<table border="1" style="width: 100%;" cellpadding="6">';
            // echo '<tr><th colspan="5" style="color: #000; background-color: #d2d6de !important"><h4><strong>Additional Learning Support</strong></h4></th></tr>';
            // echo '<tr><th style="width: 15%;">Date Discussed</th><th style="width: 15%;">Support Required</th><th style="width: 20%;">Details</th><th style="width: 20%;">Date Claimed From</th><th style="width: 30%;">Additional Info.</th></tr>';
            // if(count($als_records) == 0)
            //     echo '<tr><td colspan="5"><i>No records.</i></td></tr>';
            // foreach($als_records AS $als_row)
            // {
            //     $als_row = (object)$als_row;
            //     echo '<tr>';
            //     echo '<td>' . Date::toShort($als_row->date_discussed) . '</td>';
            //     echo $als_row->support_required == 'Y' ? '<td>Yes</td>' : '<td>No</td>';
            //     echo '<td>' . HTML::cell($als_row->details) . '</td>';
            //     echo '<td>' . Date::toShort($als_row->date_claimed_from) . '</td>';
            //     echo '<td>' . HTML::cell($als_row->additional_info) . '</td>';
            //     echo '</tr>';
            // }
            // echo '</table>';
            // echo '</div><p></p><hr>';
        }

        $planned_reviews_start_date = $tr->practical_period_start_date;
        $planned_reviews_end_date = $tr->practical_period_end_date;

        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();

        $mpdf->WriteHTML($html);
        $mpdf->addPage('L');

        ob_start();

        echo '<h2><strong>Delivery Plan</strong></h2>';

        echo '<div style="text-align: center;">';
        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr>
            <th>Training to be delivered</th>
            <th>Exempt</th>
            <th>Level</th>
            <th>Details</th>
            <th>Dates</th>
            <th>Number of months</th>';
        if (DB_NAME == "am_ela") {
            echo '<th>Offset Months</th>';
            echo '<th>ACT</th>';
            echo '<th>Weighting (%)</th>';
            echo '<th>Aim already on OneFile</th>';
            echo '<th>Link</th>';
        }
        echo '</tr>';
        $ob_learner_quals_order_by = DB_NAME == "am_ela" ? " ORDER BY ob_learner_quals.qual_start_date, ob_learner_quals.qual_sequence " : " ORDER BY ob_learner_quals.qual_start_date ";
        $ob_quals_sql = <<<SQL
SELECT 
ob_learner_quals.*,
framework_qualifications.level,
framework_qualifications.qualification_type,
TIMESTAMPDIFF(MONTH, qual_start_date, qual_end_date) AS no_of_months,
framework_qualifications.`main_aim`
FROM
ob_learner_quals
LEFT JOIN framework_qualifications ON REPLACE(ob_learner_quals.qual_id, '/', '') = REPLACE(framework_qualifications.id, '/', '') 
WHERE
ob_learner_quals.tr_id = '{$tr->id}' AND 
framework_qualifications.framework_id = '{$tr->framework_id}' $ob_learner_quals_order_by  
SQL;

        $additional = "";
        if (DB_NAME == "am_ela") {
            $additional = "ob_learner_quals.`qual_offset_months`, ob_learner_quals.`qual_weighting`, ob_learner_quals.`qual_on_of`, ob_learner_quals.`qual_standard_link`, ";
        }

        $ob_quals_sql = <<<SQL
SELECT
ob_learner_quals.`qual_id`,
ob_learner_quals.`qual_title`,
ob_learner_quals.`qual_exempt`,
ob_learner_quals.`qual_type`,
(SELECT description FROM lookup_qual_type WHERE id = ob_learner_quals.`qual_type`) AS qual_type_description,
ob_learner_quals.`qual_start_date`,
ob_learner_quals.`qual_end_date`,
$additional
TIMESTAMPDIFF(MONTH, qual_start_date, DATE_ADD(qual_end_date, INTERVAL 1 DAY) ) AS no_of_months,
(SELECT main_aim FROM framework_qualifications WHERE framework_id = ob_tr.`framework_id` AND REPLACE(id, '/', '') = qual_id) AS main_aim,
(SELECT `level` FROM framework_qualifications WHERE framework_id = ob_tr.`framework_id` AND REPLACE(id, '/', '') = qual_id) AS `level`

FROM
ob_learner_quals INNER JOIN ob_tr ON ob_learner_quals.`tr_id` = ob_tr.`id`
WHERE tr_id = '{$tr->id}' $ob_learner_quals_order_by

SQL;

        $ob_quals = DAO::getResultset($link, $ob_quals_sql, DAO::FETCH_ASSOC);
        foreach ($ob_quals as $qual) {
            echo '<tr>';
            echo '<td>' . $qual['qual_id'] .  ' ' . $qual['qual_title'] . '</td>';
            if ($qual['qual_exempt'] == 0) {
                echo '<td>No</td>';
            } elseif ($qual['qual_exempt'] == 1) {
                echo '<td>Yes</td>';
            } elseif ($qual['qual_exempt'] == 2) {
                echo '<td>Pending</td>';
            } else {
                echo '<td></td>';
            }
            echo '<td>' . $qual['level'] . '</td>';
            echo '<td>' . $qual['qual_type_description'] . '</td>';
            echo '<td>' . Date::toShort($qual['qual_start_date']) . '<br>' . Date::toShort($qual['qual_end_date']) . '</td>';
            echo '<td>' . $qual['no_of_months'] . ' months</td>';
            if ($qual['main_aim'] == 1) {
                $planned_reviews_start_date = $qual['qual_start_date'];
                $planned_reviews_end_date = $qual['qual_end_date'];
            }
            if (DB_NAME == "am_ela") {
                echo '<td>' . $qual['qual_offset_months'] . ' months</td>';
                if ($employer->funding_type == 'L' || $employer->levy_employer == 1) {
                    echo '<td>1. Levy</td>';
                } elseif ($employer->funding_type == 'CO' || $employer->levy_employer == 0) {
                    echo '<td>2. Non Levy</td>';
                } else {
                    echo '<td></td>';
                }
                echo '<td>' . $qual['qual_weighting'] . '</td>';
                echo $qual['qual_on_of'] == 1 ? '<td>Yes</td>' : ($qual['qual_on_of'] == 0 ? '<td>No</td>' : '<td></td>');
                echo '<td>' . $qual['qual_standard_link'] . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '</div><p></p>';

        //$learner_age = DAO::getSingleValue($link, "SELECT ((DATE_FORMAT(CURDATE(),'%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age");	

        if (DB_NAME == "am_ela") {
            //$training_cost = intval($tnp) - intval($tr->epa_price);

            echo '<p>';
            echo '<strong>Total TNP: </strong>&pound;' . $tnp . ' | ';
            echo '<strong>Training Cost: </strong>&pound;' . $training_cost . ' | ';
            if ($tr->practical_period_start_date < '2024-04-01') {
                if ($employer->levy_employer == 0 && $tr->type_of_funding != 'Levy Gifted') {
                    echo $learner_age > 19 ? '<strong>Training Cost (Employer 5%): </strong>&pound;' . ceil($training_cost * 0.05) . ' | ' : '<strong>Government Contribution: </strong>&pound;' . $training_cost . ' | ';
                }
                if ($employer->levy_employer == 0 && $tr->type_of_funding != 'Levy Gifted') {
                    echo $learner_age > 19 ? '<strong>Assessment Cost (Employer 5%): </strong>&pound;' . ceil($tr->epa_price * 0.05) : '<strong>Assessment Cost: </strong>&pound;' . $tr->epa_price;
                }
            } else {
                if ($employer->levy_employer == 0 && $tr->type_of_funding != 'Levy Gifted') {
                    echo $learner_age > 21 ? '<strong>Training Cost (Employer 5%): </strong>&pound;' . ceil($training_cost * 0.05) . ' | ' : '<strong>Government Contribution: </strong>&pound;' . $training_cost . ' | ';
                }
                if ($employer->levy_employer == 0 && $tr->type_of_funding != 'Levy Gifted') {
                    echo $learner_age > 21 ? '<strong>Assessment Cost (Employer 5%): </strong>&pound;' . ceil($tr->epa_price * 0.05) : '<strong>Assessment Cost: </strong>&pound;' . $tr->epa_price;
                }
            }
            if ($tr->type_of_funding == 'Levy Gifted') {
                echo '<strong>Assessment Cost: </strong>&pound;' . $tr->epa_price . ' | <strong>Levy Gifted</strong>: &pound;' . $tnp;
            }
            echo '</p>';
        }

        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();

        if (in_array(DB_NAME, ["am_superdrug", "am_sd_demo"])) {
            $_sql = new SQLStatement("SELECT detail FROM framework_sd_training");
            $_sql->setClause("WHERE framework_id = '{$framework->id}'");
            if ($employer->isSavers()) {
                $_sql->setClause("WHERE org = 'savers'");
            } else {
                $_sql->setClause("WHERE org = 'superdrug'");
            }
            $trainingCustomInfo =  DAO::getSingleValue($link, $_sql->__toString());
            if ($trainingCustomInfo != '') {
                $trainingCustomInfo = str_replace('$$APP_START_DATE$$', Date::toShort($tr->practical_period_start_date), $trainingCustomInfo);
                $trainingCustomInfo = str_replace('$$APP_PLANNED_END_DATE$$', Date::toShort($tr->practical_period_end_date), $trainingCustomInfo);

                $mpdf->WriteHTML($html);
                $mpdf->addPage('L');

                ob_start();

                echo $trainingCustomInfo;

                $html = ob_get_contents();

                $mpdf->SetHTMLFooter($footer);
                ob_end_clean();
            }
        }

        //if( $tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link)) )
        if (
            (
                (DB_NAME == "am_ela") &&
                ($tr->practical_period_start_date >= '2023-08-01') &&
                (!in_array($tr->id, OnboardingHelper::UlnsToSkip($link)))
            ) ||
            (
                (DB_NAME == "am_ela") &&
                (in_array($tr->id, Helpers::trIdsForOtjPlanner()))
            )
        ) {
            $otj_tr_template_sections = DAO::getResultset($link, "SELECT * FROM otj_tr_template_sections WHERE tr_id = '{$tr->id}' ORDER BY section_id", DAO::FETCH_ASSOC);
            if (count($otj_tr_template_sections) > 0) {
                $mpdf->WriteHTML($html);
                $mpdf->addPage('L');

                ob_start();

                echo '<h2><strong>OTJ Planner</strong></h2>';
                echo '<div style="text-align: center;">';
                echo '<table border="1" style="width: 100%;" cellpadding="6">';
                echo '<thead><tr class="bg-info"><th></th><th>KSB</th>';
                foreach (OnboardingHelper::generateOtjColumnsHeader($link, $tr->framework_id) as $_c) {
                    echo '<th>' . $_c . '</th>';
                }
                echo '<th>Behaviours</th></tr></thead><tbody>';

                foreach ($otj_tr_template_sections as $otj_tr_template_section) {
                    $subsections_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM otj_tr_template_subsections WHERE section_id = '{$otj_tr_template_section['section_id']}'");
                    if ($subsections_count > 1) {
                        $row_span = (int)$subsections_count + 1;
                        echo '<tr>';
                        echo '<td class="bg-info" rowspan="' . $row_span . '">' . $otj_tr_template_section['section_desc'] . '</td>';
                        echo '</tr>';
                    }
                    $otj_tr_template_subsections = DAO::getResultset($link, "SELECT * FROM otj_tr_template_subsections WHERE section_id = '{$otj_tr_template_section['section_id']}' ORDER BY subsection_id", DAO::FETCH_ASSOC);
                    foreach ($otj_tr_template_subsections as $otj_tr_template_subsection) {
                        echo '<tr>';

                        echo $subsections_count > 1 ? '' : '<td class="bg-info">' . $otj_tr_template_section['section_desc'] . '</td>';

                        echo '<td>' . $otj_tr_template_subsection['subsection_desc'] . '</td>';

                        $otj_tr_template_activities = DAO::getResultset($link, "SELECT * FROM otj_tr_template_activities WHERE subsection_id = '{$otj_tr_template_subsection['subsection_id']}' ORDER BY activity_id", DAO::FETCH_ASSOC);
                        foreach ($otj_tr_template_activities as $otj_prog_template_activity) {
                            echo '<td>' . $otj_prog_template_activity['activity_desc'] . '</td>';
                        }
                        echo '</tr>';
                    }

                    if (isset($otj_tr_template_section['section_id'])) {
                        echo '<tr class="bg-warning">';
                        echo '<td></td>';
                        echo '<td></td>';
                        for ($col = 2; $col <= OnboardingHelper::colsOfStandard($link, $tr->framework_id) + 1; $col++) {
                            echo '<td align="center"><strong>' . $otj_tr_template_section['col_' . $col . '_otj'] . '</strong></td>';
                        }
                        echo '</tr>';
                    }
                }
                echo '<tr>';
                echo '<td colspan="2"><strong>Comulative OTJ</strong></td>';
                $_total = 0;
                for ($col = 2; $col <= OnboardingHelper::colsOfStandard($link, $tr->framework_id) + 1; $col++) {
                    $_col = "col_{$col}_otj";
                    $single_total = DAO::getSingleValue($link, "SELECT SUM({$_col}) FROM otj_tr_template_sections WHERE tr_id = '{$tr->id}'");
                    echo '<td align="center"><strong>' . $single_total . '</strong></td>';
                    $_total += $single_total;
                }
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';

                echo '<strong>OTJ Planner - Total: ' . $_total . '</strong>';

                $html = ob_get_contents();

                $mpdf->SetHTMLFooter($footer);
                ob_end_clean();
            }
        }

        if ($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link))) {
            $fwk_delivery_plan = DAO::getSingleValue($link, "SELECT content FROM frameworks_delivery_plans WHERE framework_id = 1 AND (CURDATE() BETWEEN effective_to AND effective_from OR effective_to IS NULL);");
            if ($fwk_delivery_plan != '') {
                $mpdf->WriteHTML($html);
                $mpdf->addPage('L');

                ob_start();

                echo $fwk_delivery_plan;

                $html = ob_get_contents();

                $mpdf->SetHTMLFooter($footer);
                ob_end_clean();
            }
        }

        $mpdf->WriteHTML($html);
        $mpdf->addPage();

        ob_start();

        echo '<div style="text-align: center;">';
        echo '<h4 class="text-bold">Planned Reviews - (main provider, employer, apprentice must be present)</h4>';

        if(DB_NAME  == "am_ela")
        {
            echo '<p>The first review should take place at week 4, and all other reviews every 8 weeks and should be signed off by all parties on OneFile.</p>';
        }
        elseif(DB_NAME == "am_superdrug")
        {
            echo '<p>The first review should take place at week 4, and all other reviews every 8 weeks and should be signed off by all parties on Vault.</p>';
        }
        else
        {
            echo '<p>The first review should take place at week 4, and all other reviews every 8 weeks and should be signed off by all parties.</p>';
        }
        echo '<p>Reviews should discuss progress to date against the training plan and the immediate next steps required.</p>';

        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Planned Reviews</strong></h4></th></tr>';
        echo '<tr><th>Review Number</th><th>Planned Date</th>';

        $first_review_days = $framework->first_review != '' ? intval($framework->first_review) * 7 : 28;
        $subsequent_review_days = $framework->review_frequency != '' ? intval($framework->review_frequency) * 7 : 96;
        $_review_dates = OnboardingHelper::getReviewsDates($planned_reviews_start_date, $planned_reviews_end_date, $first_review_days, $subsequent_review_days);
        foreach ($_review_dates as $_review_number => $_review_date) {
            echo "<tr><td>{$_review_number}</td><td>{$_review_date}</td></tr>";
        }

        echo '</table>';
        echo '</div><p></p><hr>';
        $ob_learner_quals_order_by = DB_NAME == "am_ela" ? " ORDER BY ob_learner_quals.qual_start_date, ob_learner_quals.qual_sequence " : " ORDER BY ob_learner_quals.qual_start_date ";

        $sql = <<<SQL
SELECT 
framework_qualifications.evidences, framework_qualifications.title
FROM
ob_learner_quals
LEFT JOIN framework_qualifications ON REPLACE(ob_learner_quals.qual_id, '/', '') = REPLACE(framework_qualifications.id, '/', '') 
WHERE
ob_learner_quals.tr_id = '{$tr->id}' AND 
framework_qualifications.framework_id = '{$tr->framework_id}'
AND framework_qualifications.main_aim = '1' $ob_learner_quals_order_by 
SQL;

        $main_aim_detail = DAO::getObject($link, $sql);
        if ($main_aim_detail->evidences == '') {
            $main_aim_xml = XML::loadSimpleXML('<root></root>');
        } else {
            $main_aim_xml = XML::loadSimpleXML($main_aim_detail->evidences);
        }

        $units = $main_aim_xml->xpath('//unit');
        $q_units = array();
        foreach ($units as $unit) {
            $temp = array();
            $temp = (array)$unit->attributes();
            $temp = $temp['@attributes'];
            $q_units[] = $temp;
        }
        $units_ddl[] = $q_units;
        echo '<h4>Main Aim Components</h4>';
        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th style="color: #000; background-color: #d2d6de !important" colspan="2">' . $main_aim_detail->title . '</th></tr>';

        foreach ($units_ddl[0] as $row) {
            echo '<tr>';
            echo '<td>' . $row['title'] . '</td>';
            echo '<td>' . $row['glh'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div><p></p>';


        echo '<h2><strong>Training Plan Roles and Responsibilities</strong></h2>';
        echo '<div>';
        echo '<h4>Roles, Responsibilities & Declarations</h4>';
        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Learner Roles & Responsibilities:</strong></h4></th></tr>';
        $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'LEARNER' ORDER BY id", DAO::FETCH_ASSOC);
        foreach ($result as $row) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo DB_NAME == "am_eet" ? '<td>' . str_replace('ELA Training', $provider->legal_name, $row['description']) . '</td>' : '<td>' . $row['description'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div><p></p>';

        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>The Employer (Manager of Apprentice) agrees to:</strong></h4></th></tr>';
        $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'EMPLOYER' AND sub_id = 0 ORDER BY id", DAO::FETCH_ASSOC);
        $first_loop = true;
        $previous_id = '';
        foreach ($result as $row) {
            echo '<tr>';
            echo $previous_id != $row['id'] ? '<td>' . $row['id'] . '</td>' : '<td></td>';
            echo '<td>';
            echo DB_NAME == "am_eet" ? str_replace('ELA Training', $provider->legal_name, $row['description']) : $row['description'];
            $subs = DAO::getSingleColumn($link, "SELECT description FROM lookup_cs_roles_responsibilities WHERE sub_id = '{$row['id']}' AND user_type = 'EMPLOYER'");
            if (count($subs) > 0)
                echo '<ul>';
            foreach ($subs as $sub) {
                $sub = DB_NAME == "am_eet" ? str_replace('ELA Training', $provider->legal_name, $sub) : $sub;
                echo '<li style="margin-left: 20px;">' . $sub . '</li>';
            }
            if (count($subs) > 0)
                echo '</ul>';
            echo '</td>';
            echo '</tr>';
            $first_loop = false;
            $previous_id = $row['id'];
        }
        echo '</table>';
        echo '</div><p></p>';

        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>The Main Provider agrees to:</strong></h4></th></tr>';
        $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'PROVIDER' AND sub_id = 0 ORDER BY id", DAO::FETCH_ASSOC);
        $first_loop = true;
        $previous_id = '';
        foreach ($result as $row) {
            echo '<tr>';
            echo $previous_id != $row['id'] ? '<td>' . $row['id'] . '</td>' : '<td></td>';
            echo '<td>';
            echo DB_NAME == "am_eet" ? str_replace('ELA Training', $provider->legal_name, $row['description']) : $row['description'];
            $subs = DAO::getSingleColumn($link, "SELECT description FROM lookup_cs_roles_responsibilities WHERE sub_id = '{$row['id']}' AND user_type = 'PROVIDER'");
            if (count($subs) > 0)
                echo '<ul>';
            foreach ($subs as $sub) {
                $sub = DB_NAME == "am_eet" ? str_replace('ELA Training', $provider->legal_name, $sub) : $sub;
                echo '<li style="margin-left: 20px;">' . $sub . '</li>';
            }
            if (count($subs) > 0)
                echo '</ul>';
            echo '</td>';
            echo '</tr>';
            $first_loop = false;
            $previous_id = $row['id'];
        }
        echo '</table>';
        echo '</div><p></p>';

        $provider_legal_name = $provider->legal_name;
        $comp = '';
        if (DB_NAME == "am_crackerjack") {
            $comp = '<tr>
                        <td>
                            <p>
                                A formal complaint should be put in writing to the Operations Manager; <a class="text-blue" href="mailto:donna.johal@crackerjacktraining.com">donna.johal@crackerjacktraining.com</a> 
                                you will receive a response to your complaint within a further 10 working days. 
                                If you are not satisfied with the outcome of the stage one consideration of your complaint you may request a review of the decision within 10 working days of receiving the outcome. 
                                You must submit a written explanation to the Managing Director; <a class="text-blue" href="fiona.baker@crackerjacktraining.com">fiona.baker@crackerjacktraining.com</a>, 
                                of why you are dissatisfied with the outcome of stage one. 
                                If following this process the complaint has not been addressed, you can raise this issue directly with the Department for Education, (the DfE) through; DfE at <a class="text-blue" href="complaints.esfa@education.gov.uk">complaints.esfa@education.gov.uk</a>.
                            </p>
                        </td>
                    </tr>';
        }
        if (DB_NAME == "am_superdrug") {
            $comp = '<tr>
                        <td>
                            <p>
                                If at any time you are not happy with your Apprenticeship programme and wish to make a complaint, in the 1st instance
                                speak with your Manager and/or your Assessor. If you need to escalate this further you can make a formal complaint in
                                writing to the Internal Verifier; their contact details are in your learning plan or alternatively contact the Superdrug
                                Apprenticeship helpline on 01977 809564.<br>
                                Or on our email address: <a href="mailto:apprenticeships@uk.aswatson.com">apprenticeships@uk.aswatson.com</a>
                            </p>
                        </td>
                    </tr>';
        }
        echo <<<HTML
<table border="1" style="width: 100%;" cellpadding="6">
<tr><th style="color: #000; background-color: #d2d6de !important"><h4><strong>Working Together</strong></h4></th></tr>
$comp
<tr>
    <td>
        <i>The Employer and the Apprentice will work together with the Training Provider's representatives to ensure 
        that the Apprentice has the best chance to achieve. In so doing, each parties' roles and responsibilities should
        be read carefully in this Training Plan with further recourse to the appropriate, Funding Rules in force at the time.</i>
    </td>
</tr>
</table>
<p></p>
<table border="1" style="width: 100%;" cellpadding="6">
<tr><th style="color: #000; background-color: #d2d6de !important"><h4><strong>Queries and Complaints Process</strong></h4></th></tr>

<tr>
    <td>
        <p class="text-bold">Apprenticeship Helpline</p>
        <p>All parties can make use of the Apprenticeship Helpline if they have any queries, concerns or complaints:</p>
        <p>Email: <a class="text-green" href="mailto:helpdesk@manage-apprenticeships.service.gov.uk">helpdesk@manage-apprenticeships.service.gov.uk</a></p>
        <p>Telephone: 08000 150 600</p>
    </td>
</tr>
</table>
<p></p>
<hr>
HTML;

        echo '<h2><strong>Section 5 - Training Plan Declarations</strong></h2>';
        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Learner Declarations</strong></h4></th></tr>';

        if ($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link)))
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'LEARNER' AND year = '2023' AND version = 1 ORDER BY id", DAO::FETCH_ASSOC);
        else
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'LEARNER' AND year = '2022' AND version = 1 ORDER BY id", DAO::FETCH_ASSOC);

        $saved_learner_dec = explode(",", $tr->learner_dec);
        foreach ($result as $row) {
            echo '<tr>';
            if (in_array($row['id'], $saved_learner_dec))
                echo '<td align="right">Yes </td>';
            else
                echo '<td align="right"></td>';
            echo DB_NAME == "am_eet" ? '<td>' . str_replace('ELA Training', $provider->legal_name, $row['description']) . '</td>' : '<td>' . $row['description'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div><p></p>';

        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Employer Declarations</strong></h4></th></tr>';
        if ($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link)))
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'EMPLOYER' AND year = '2023' AND version = 1 ORDER BY id", DAO::FETCH_ASSOC);
        else
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'EMPLOYER' AND year = '2022' AND version = 1 ORDER BY id", DAO::FETCH_ASSOC);
        $saved_employer_dec = explode(",", $tr->emp_dec);
        foreach ($result as $row) {
            echo '<tr>';
            if (in_array($row['id'], $saved_employer_dec))
                echo '<td align="right">Yes </td>';
            else
                echo '<td align="right"></td>';
            $declrationDescription = $row['description'];
            if (in_array(DB_NAME, ["am_superdrug", "am_sd_demo"])) {
                $declrationDescription = str_replace('SD_HOURS_PER_WEEK', $tr->contracted_hours_per_week, $declrationDescription);
                $declrationDescription = str_replace('SD_OTJ_HOURS', $tr->off_the_job_hours_based_on_duration, $declrationDescription);
            }
            if (in_array(DB_NAME, ["am_eet"])) {
                $declrationDescription = str_replace('ELA Training', $provider->legal_name, $declrationDescription);
            }
            echo '<td>' . $declrationDescription . '</td>';
            echo '</tr>';
        }
        // if(DB_NAME == "am_ela")
        // {
        //     $wages_and_employment = DAO::getObject($link, "SELECT * FROM ob_learner_wae WHERE tr_id = '{$tr->id}'");
        //     if(isset($wages_and_employment->tr_id))
        //     {
        //         echo '<tr>';
        //         echo '<td align="right">' . ( isset($wages_and_employment->opt1) ? $wages_and_employment->opt1 : '') . '</td>';
        //         echo '<td>The apprentice is receiving a wage in line with the national minimum wage requirements</td>';
        //         echo '</tr>';
        //         echo '<tr>';
        //         echo '<td align="right">' . ( isset($wages_and_employment->opt2) ? $wages_and_employment->opt2 : '') . '</td>';
        //         echo '<td>The apprentice rate was not used prior to a valid apprenticeship agreement being in place</td>';
        //         echo '</tr>';
        //         if($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && (in_array($tr->id, [1038]) || SOURCE_LOCAL ) )
        //         {
        //             echo '<tr>';
        //             echo '<td align="right">' . ( isset($wages_and_employment->opt3) ? $wages_and_employment->opt3 : '') . '</td>';
        //             echo '<td>The apprentice is included in the PAYE Scheme declared in the Apprenticeship Service account.</td>';
        //             echo '</tr>';
        //             echo '<tr>';
        //             echo '<td align="right">' . ( isset($wages_and_employment->opt4) ? $wages_and_employment->opt4 : '') . '</td>';
        //             echo '<td>The apprentice will be provided with the time required to undertake all off the job training requirements (20% or 6 hrs/wk) 
        //                     within their normal hours of work in addition to any English and Maths requirements that they might have</td>';
        //             echo '</tr>';
        //         } 
        //         else 
        //         {
        //             echo '<tr>';
        //             echo '<td align="right">' . ( isset($wages_and_employment->opt3) ? $wages_and_employment->opt3 : '') . '</td>';
        //             echo '<td>The apprentice is receiving a wage in line with the national minimum wage requirements</td>';
        //             echo '</tr>';
        //             echo '<tr>';
        //             echo '<td align="right">' . ( isset($wages_and_employment->opt4) ? $wages_and_employment->opt4 : '') . '</td>';
        //             echo '<td>The apprentice is receiving a wage in line with the national minimum wage requirements</td>';
        //             echo '</tr>';
        //         }
        //     }
        // }

        echo '</table>';
        echo '</div><p></p>';

        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Training Provider Declarations</strong></h4></th></tr>';
        if ($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link)))
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'PROVIDER' AND year = '2023' AND version = 1 ORDER BY id", DAO::FETCH_ASSOC);
        else
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'PROVIDER' AND year = '2022' AND version = 1 ORDER BY id", DAO::FETCH_ASSOC);
        $saved_tp_dec = explode(",", $tr->tp_dec);
        foreach ($result as $row) {
            echo '<tr>';
            if (in_array($row['id'], $saved_tp_dec))
                echo '<td align="right">Yes </td>';
            else
                echo '<td align="right"></td>';
            echo '<td>' . $row['description'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</div><p></p>';


        $learner_sign_date = isset($tr->learner_sign_date) ? Date::toShort($tr->learner_sign_date) : '';
        $provider_sign_date = isset($tr->tp_sign_date) ? Date::toShort($tr->tp_sign_date) : '';
        $employer_sign_date = isset($tr->emp_sign_date) ? Date::toShort($tr->emp_sign_date) : '';

        if ($tr->practical_period_start_date >= '2023-08-01' && (DB_NAME == "am_ela") && !in_array($tr->id, OnboardingHelper::UlnsToSkip($link))) {
            $otj_signatures = DAO::getObject($link, "SELECT * FROM otj_planner_signatures WHERE tr_id = '{$tr->id}'");
            if (isset($otj_signatures->tr_id)) {
                $learner_sign_date = $otj_signatures->learner_sign_date == '' ? $learner_sign_date : Date::toShort($otj_signatures->learner_sign_date);
                $provider_sign_date = $otj_signatures->provider_sign_date == '' ? $provider_sign_date : Date::toShort($otj_signatures->provider_sign_date);
                $employer_sign_date = $otj_signatures->employer_sign_date == '' ? $employer_sign_date : Date::toShort($otj_signatures->employer_sign_date);
            }
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
    <tr>
        <td>Employer</td>
        <td>{$tr->emp_sign_name}</td>
        <td><img src="$employer_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
        <td>{$employer_sign_date}</td>
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

        // $mpdf->Output('Training Plan', 'I');

        $mpdf->Output($c_file, 'F');
    }
}
