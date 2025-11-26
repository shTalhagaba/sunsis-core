<?php
#[\AllowDynamicProperties]
class TrainingRecord extends Entity
{
    public static function loadFromDatabase(PDO $link, $id)
    {
        if($id == '')
        {
            return null;
        }

        $key = addslashes($id);
        $query = <<<HEREDOC
SELECT
	*
FROM
	ob_tr
WHERE
	id='$key'
LIMIT 1;
HEREDOC;
        $st = $link->query($query);

        $tr = null;
        if($st)
        {
            $tr = null;
            $row = $st->fetch();
            if($row)
            {
                $tr = new TrainingRecord();
                $tr->populate($row);

                $prices = DAO::getObject($link, "SELECT * FROM tr_prices WHERE tr_id = '{$tr->id}'");
                if(isset($prices->tr_id))
                {
                    foreach($prices AS $key => $value)
                    {
                        $tr->$key = $value;
                    }
                }
            }
        }
        else
        {
            throw new Exception("Could not execute database query to find organisation. " . '----' . $query);
        }

        return $tr;
    }

    public function save(PDO $link)
    {
        $this->created = $this->id == '' ? date('Y-m-d H:i:s') : $this->created;
        $this->created_by = $this->id == '' ? $_SESSION['user']->id : $this->created_by;
        $this->modified = date('Y-m-d H:i:s');

        DAO::saveObjectToTable($link, 'ob_tr', $this);
        $this->saveTrainingPrices($link);
        return $this;
    }

    private function saveTrainingPrices(PDO $link)
    {
        $prices = new stdClass();
        $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM tr_prices");
        foreach($records AS $key => $value)
        {
            $prices->$value = isset($this->$value) ? $this->$value : 0;
        }
        $prices->tr_id = $this->id;

        DAO::saveObjectToTable($link, 'tr_prices', $prices);
    }

    public function getPlannedOtjHours(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT SUM(otj_hours) FROM ob_learner_delivery_plan WHERE tr_id = '{$this->id}' ");
    }

    public static function getStatusDesc($status)
    {
        if($status == self::STATUS_SS_SIGNED_BY_PROVIDER)
            return 'Skills Assessment SIGNED BY PROVIDER';
        elseif($status == self::STATUS_SS_EMAILS_ENT)
            return 'Skills Assessment EMAIL SENT';
        elseif($status == self::STATUS_SS_SIGNED_BY_LEARNER)
            return 'Skills Assessment SIGNED BY LEARNER';
        elseif($status == self::STATUS_SS_SIGNED_BY_EMPLOYER)
            return 'Skills Assessment SIGNED BY EMPLOYER';
        elseif($status == self::STATUS_ONBOARDING_FORM_PREPARED)
            return 'Onboarding form ready to be sent to LEARNER';
        elseif($status == self::STATUS_ONBOARDING_URL_SENT)
            return 'Onboarding URL email sent to LEARNER';
        else
            return 'CREATED';
    }

    public function getObLearnerRecord(PDO $link)
    {
        return OnboardingLearner::loadFromDatabase($link, $this->ob_learner_id);
    }

    public function getSkillsAnalysis(PDO $link)
    {
        return SkillsAnalysis::loadFromDatabaseByTrainingRecordId($link, $this->id);
    }

    public function getProviderLegalName(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$this->provider_id}'");
    }

    public function getSubcontractorLegalName(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$this->subcontractor_id}'");
    }

    public function getEmployerAgreementSchedule1(PDO $link)
    {
        $id = DAO::getSingleValue($link, "SELECT id FROM employer_agreement_schedules WHERE tr_id = '{$this->id}' ORDER BY id DESC LIMIT 1");
        if($id != '')
            return EmployerSchedule1::loadFromDatabase($link, $id);
        else
            return new EmployerSchedule1();
    }

    public function getCommitmentStatement(PDO $link)
    {
        return DAO::getObject($link, "SELECT * FROM commitment_statements WHERE tr_id = '{$this->id}'");
    }

    public function getEpaOrgName(PDO $link)
    {
        $sql = <<<SQL
SELECT EP_Assessment_Organisations
FROM central.`epa_organisations`
WHERE EPA_ORG_ID = '{$this->epa_organisation}'
SQL;
        return DAO::getSingleValue($link, $sql);
    }

    public function getKsbStats(PDO $link)
    {
        $result = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb WHERE tr_id = '{$this->id}' ORDER BY id", DAO::FETCH_ASSOC);
        $total_planned_hours = OnboardingHelper::TOTAL_PLANNED_HOURS;
        $delivery_plan_total_fa = 0;
        $delivery_plan_total_ba = 0;
        foreach($result AS $row)
        {
            $delivery_plan_hours = 0;
            $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 20;
            if($row['score'] == 5)
                $delivery_plan_hours = ceil($del_hours * 0.25);
            elseif($row['score'] == 4)
                $delivery_plan_hours = ceil($del_hours * 0.5);
            elseif($row['score'] == 3)
                $delivery_plan_hours = ceil($del_hours * 0.75);
            elseif($row['score'] == 2)
                $delivery_plan_hours = ceil($del_hours * 0.9);
            elseif($row['score'] == 1)
                $delivery_plan_hours = $del_hours;
            $delivery_plan_total_fa += $delivery_plan_hours;
            $delivery_plan_total_ba += $del_hours;
        }
        $percentage_following_assessment = round(($delivery_plan_total_fa/$delivery_plan_total_ba) * 100, 0);
        $stats = [
            'total_planned_hours' => $total_planned_hours,
            'delivery_plan_total_ba' => $delivery_plan_total_ba,
            'delivery_plan_total_fa' => ceil($delivery_plan_total_fa),
            'percentage_following_assessment' => $percentage_following_assessment,
        ];
        return (object)$stats;
    }

    public function getCareLeaverDetails(PDO $link)
    {
        $care_leaver_details = DAO::getObject($link, "SELECT * FROM ob_learner_care_leaver_details WHERE tr_id = '{$this->id}'");
        if(!isset($care_leaver_details))
        {
            $care_leaver_details = new stdClass();
            $care_leaver_details->tr_id = $this->id;
            $care_leaver_details->in_care_of_local_authority = null;
            $care_leaver_details->eligible_for_bursary_payment = null;
            $care_leaver_details->give_consent_to_inform_employer = null;
            $care_leaver_details->in_care_evidence = null;
            $care_leaver_details->in_care_evidence_file = null;
            $care_leaver_details->care_leaver_bank_name = null;
            $care_leaver_details->care_leaver_account_name = null;
            $care_leaver_details->care_leaver_sort_code = null;
            $care_leaver_details->care_leaver_account_number = null;
            $care_leaver_details->child_type = null;
        }
        return $care_leaver_details;
    }

    public function getCriminalConvictionDetails(PDO $link)
    {
        $criminal_conviction_details = DAO::getObject($link, "SELECT * FROM ob_learner_criminal_convictions WHERE tr_id = '{$this->id}'");
        if(!isset($criminal_conviction_details))
        {
            $criminal_conviction_details = new stdClass();
            $criminal_conviction_details->tr_id = $this->id;
            $criminal_conviction_details->have_criminal_conviction = null;
            $criminal_conviction_details->is_it_motoring_conviction = null;
            $criminal_conviction_details->details = null;
            $criminal_conviction_details->working_with_agencies = null;
            $criminal_conviction_details->details_of_agencies = null;
        }
        return $criminal_conviction_details;
    }

    public function delete(PDO $link)
    {

    }

    public function underSixHoursPerWeekRule()
    {
        return $this->practical_period_start_date >= '2022-08-01' ? true : false;
    }

    public function getDirectoryPath()
    {
        return Repository::getRoot() . "/OnboardingModule/learners/{$this->ob_learner_id}/{$this->id}/";
    }

    public function generateSignatureImages(PDO $link)
    {
        // save employer schedule 1 signatures for provider and employer
        $schedule = $this->getEmployerAgreementSchedule1($link);
        if(!is_null($schedule->tp_sign))
        {
            $directory = $this->getDirectoryPath();
            $directory .= 'schedule1/';
            if(!is_dir($directory))
            {
                mkdir("$directory", 0777, true);
            }
            $tp_signature_file = $directory . 'tp_sign_image.png';
            if(!is_file($tp_signature_file))
            {
                $signature_parts = explode('&', $schedule->tp_sign);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                    imagepng($signature, $tp_signature_file, 0);
                }
            }
        }
        if(!is_null($schedule->emp_sign))
        {
            $directory = $this->getDirectoryPath();
            $directory .= 'schedule1/';
            if(!is_dir($directory))
            {
                mkdir("$directory", 0777, true);
            }
            $emp_signature_file = $directory . 'emp_sign_image.png';
            if(!is_file($emp_signature_file))
            {
                $signature_parts = explode('&', $schedule->emp_sign);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                    imagepng($signature, $emp_signature_file, 0);
                }
            }
        }

        // skills analysis
        $skills_analysis = $this->getSkillsAnalysis($link);
        if($skills_analysis)
        {
        if(!is_null($skills_analysis->learner_sign))
        {
            $directory = $this->getDirectoryPath();
            $directory .= 'skills_analysis/';
            if(!is_dir($directory))
            {
                mkdir("$directory", 0777, true);
            }
            $learner_signature_file = $directory . 'learner_sign_image.png';
            if(!is_file($learner_signature_file))
            {
                $signature_parts = explode('&', $skills_analysis->learner_sign);
                if(isset($signature_parts[1]) && isset($signature_parts[2]) && isset($signature_parts[3]))
                {
                    $title = explode('=', $signature_parts[1]);
                    $font = explode('=', $signature_parts[2]);
                    $size = explode('=', $signature_parts[3]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                    imagepng($signature, $learner_signature_file, 0);
                }
            }
        }
        if(!is_null($skills_analysis->provider_sign))
        {
            $directory = $this->getDirectoryPath();
            $directory .= 'skills_analysis/';
            if(!is_dir($directory))
            {
                mkdir("$directory", 0777, true);
            }
            $provider_signature_file = $directory . 'provider_sign_image.png';
            if(!is_file($provider_signature_file))
            {
                $signature_parts = explode('&', $skills_analysis->provider_sign);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                    imagepng($signature, $provider_signature_file, 0);
                }
            }
        }
}

        // onboarding
        if(!is_null($this->learner_sign))
        {
            $directory = $this->getDirectoryPath();
            $directory .= 'onboarding/';
            if(!is_dir($directory))
            {
                mkdir("$directory", 0777, true);
            }
            $learner_signature_file = $directory . 'learner_sign_image.png';
            if(!is_file($learner_signature_file))
            {
                $signature_parts = explode('&', $this->learner_sign);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                    imagepng($signature, $learner_signature_file, 0);
                }
            }
        }
        if(!is_null($this->emp_sign))
        {
            $directory = $this->getDirectoryPath();
            $directory .= 'onboarding/';
            if(!is_dir($directory))
            {
                mkdir("$directory", 0777, true);
            }
            $emp_signature_file = $directory . 'emp_sign_image.png';
            if(!is_file($emp_signature_file))
            {
                $signature_parts = explode('&', $this->emp_sign);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                    imagepng($signature, $emp_signature_file, 0);
                }
            }
        }
        if(!is_null($this->tp_sign))
        {
            $directory = $this->getDirectoryPath();
            $directory .= 'onboarding/';
            if(!is_dir($directory))
            {
                mkdir("$directory", 0777, true);
            }
            $tp_signature_file = $directory . 'tp_sign_image.png';
            if(!is_file($tp_signature_file))
            {
                $signature_parts = explode('&', $this->tp_sign);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                    imagepng($signature, $tp_signature_file, 0);
                }
            }
        }

    }

    public function generateCommitmentStatementPdf(PDO $link)
    {
        if($this->learner_sign == '' || $this->emp_sign == '' || $this->tp_sign == '')
        {
            return;
        }

        $skills_analysis = $this->getSkillsAnalysis($link);

        $onboarding_directory = $this->getDirectoryPath() . 'onboarding/';
        if(!is_dir($onboarding_directory))
        {
            mkdir("$onboarding_directory", 0777, true);
        }
        $c_file = $onboarding_directory.OnboardingHelper::TRAINING_PLAN_PDF_NAME;
        if(!is_file($c_file) || in_array("CS", explode(",", $this->generate_pdfs)) )
        {
            include_once("./MPDF57/mpdf.php");

            $c_file = in_array("CS", explode(",", $this->generate_pdfs)) ? $onboarding_directory.'Training Plan_'.uniqid().'.pdf' : $c_file;

            $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$this->provider_id}'");
            if ($logo == '')
                $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

            $mpdf = new \Mpdf\Mpdf(['format' => 'Legal', 'default_font_size' => 10]);
            $mpdf->setAutoBottomMargin = 'stretch';

            $sunesis_stamp = md5('ghost' . date('d/m/Y') . $this->id);
            $sunesis_stamp = substr($sunesis_stamp, 0, 10);
            $date = date('d/m/Y H:i:s');
            $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "30%" align="left" style="font-size: 10px">{$date}</td>
					<td width = "35%" align="left" style="font-size: 10px">App5-Training Plan-v2 2122 Sep 08 2021</td>
					<td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;
            //Beginning Buffer to save PHP variables and HTML tags
            ob_start();

            $framework = Framework::loadFromDatabase($link, $this->framework_id);
            $ob_learner = $this->getObLearnerRecord($link);
            $skills_analysis = $this->getSkillsAnalysis($link);
            $employer = Organisation::loadFromDatabase($link, $this->employer_id);
            $employer_location = Location::loadFromDatabase($link, $this->employer_location_id);
            $provider = Organisation::loadFromDatabase($link, $this->provider_id);
            $provider_location = Location::loadFromDatabase($link, $this->provider_location_id);
            $epa_name = $this->getEpaOrgName($link);
            if($this->trainers != '')
                $trainer = User::loadFromDatabaseById($link, $this->trainers);
            else
                $trainer = new User();


            $sub_legal = $this->getSubcontractorLegalName($link);
            $subcontractor_name = $sub_legal != '' ? $sub_legal : 'NA';
            $standard_title = $framework->getStandardCodeDesc($link);
            $standard_level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");
            $practical_period_start_date = Date::toShort($this->practical_period_start_date);
            $practical_period_end_date = Date::toShort($this->practical_period_end_date);
            $apprenticeship_start_date = Date::toShort($this->apprenticeship_start_date);
            $apprenticeship_end_date_inc_epa = Date::toShort($this->apprenticeship_end_date_inc_epa);
            $dob = Date::toShort($ob_learner->dob);
            $age_at_start_sql = <<<SQL
SELECT 
    ((DATE_FORMAT('$this->apprenticeship_start_date','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT(CURDATE(),'00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
            $age_at_start = DAO::getSingleValue($link, $age_at_start_sql);
            $gender = $ob_learner->gender == "F" ? "Female" : $ob_learner->gender;
            $gender = $ob_learner->gender == "M" ? "Male" : $gender;
            $ethnicity = DAO::getSingleValue($link,"SELECT Ethnicity_Desc FROM lis201213.ilr_ethnicity WHERE Ethnicity = '{$ob_learner->ethnicity}';");
            $hhs = '';
            $hhs_list = LookupHelper::getListHhs();
            $selected_hhs = explode(",", $this->hhs);
            foreach($selected_hhs AS $_v)
                $hhs .= isset($hhs_list[$_v]) ? $hhs_list[$_v] . '<br>' : $_v . '<br>';
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
            $lldd_desc = isset($LLDD[$this->LLDD]) ? $LLDD[$this->LLDD] : $this->LLDD;
            $llddcats = '';
            $saved_llddcat = explode(",", $this->llddcat);
            foreach($saved_llddcat AS $_llddcat)
                $llddcats .= isset($LLDDCat[$_llddcat]) ? $LLDDCat[$_llddcat] . '<br>' : $_llddcat . '<br>';

            $_primary_lldd = isset($LLDDCat[$this->primary_lldd]) ? $LLDDCat[$this->primary_lldd] : $this->primary_lldd;

            $learner_signature_file = $onboarding_directory . 'learner_sign_image.png';
            $emp_signature_file = $onboarding_directory . 'emp_sign_image.png';
            $provider_signature_file = $onboarding_directory . 'tp_sign_image.png';
            $learner_sign_date = isset($this->learner_sign_date) ? Date::toShort($this->learner_sign_date) : '';
            $emp_sign_date = isset($this->emp_sign_date) ? Date::toShort($this->emp_sign_date) : '';
            $provider_sign_date = isset($this->tp_sign_date) ? Date::toShort($this->tp_sign_date) : '';

            $learner_address = $this->home_address_line_1;
            $learner_address .= $this->home_address_line_2 != '' ? '<br>' . $this->home_address_line_2 : '';
            $learner_address .= $this->home_address_line_3 != '' ? '<br>' . $this->home_address_line_3 : '';
            $learner_address .= $this->home_address_line_4 != '' ? '<br>' . $this->home_address_line_4 : '';

            $funding_band_maximum = $framework->getFundingBandMax($link);
            $recommended_duration = $framework->getRecommendedDuration($link);

            $tnp1_prices = is_null($this->tnp1) ? [] : json_decode($this->tnp1);
            $tnp1_costs = array_map(function ($ar) {return $ar->cost;}, $tnp1_prices);
            $tnp1_total = array_sum(array_map('floatval', $tnp1_costs));
            $tnp_rows = '';
            foreach($tnp1_prices AS $price_item)
            {
                $tnp_rows .= '<tr>';
                $tnp_rows .= '<th>' . $price_item->description . ' (TNP 1)</th>';
                $tnp_rows .= '<td>&pound;' . $price_item->cost . '</td>';
                $tnp_rows .= '</tr>';
            }
            $tnp = ceil($tnp1_total+$this->epa_price);

	    $table_result_of_skills_scan = "";
            if(DB_NAME != "am_crackerjack")
            {
                $table_result_of_skills_scan = <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Results of the Skills Analysis (taking into account all prior learning)</strong></h4></th></tr>
        <tr><th>Duration:</th><td>$this->duration_practical_period</td></tr>
        $tnp_rows
        <tr><th>End Point Assessment (TNP 2):</th><td>&pound;$this->epa_price</td></tr>
        <tr><th>Total Negotiated Price:</th><td>&pound;$tnp</td></tr>
    </table>
</div>            
HTML;
            }
	
            echo <<<HTML
<div style="text-align: center;">
    <h2><strong>Training Plan</strong></h2>
    <img width="200px;" class="img-responsive" src="$logo" />
</div>
<p></p>
<h2><strong>Section 1 - Details</strong></h2>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th>Standard Title:</th><td>$standard_title</td></tr>
        <tr><th>Level:</th><td>$standard_level</td></tr>
        <tr><th>Price (top of funding band):</th><td>&pound;$funding_band_maximum</td></tr>
        <tr><th>Recommended Duration - months:</th><td>$recommended_duration</td></tr>
    </table>
</div>        
$table_result_of_skills_scan                
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Individualised Apprenitceship Details</strong></h4></th></tr>
        <tr><th>Start Date of Practical Period:</th><td>$practical_period_start_date</td></tr>
        <tr><th>Planned End Date of Practical Period:</th><td>$practical_period_end_date</td></tr>
        <tr><th>Duration of Practical Period - months:</th><td>$this->duration_practical_period</td></tr>
        <tr><th>Start Date of Apprenticeship:</th><td>$apprenticeship_start_date</td></tr>
        <tr><th>Planned End date of Apprenticeship (incl EPA):</th><td>$apprenticeship_end_date_inc_epa</td></tr>
        <tr><th>Duration of Full Apprenticeship (incl EPA) - months:</th><td>$this->apprenticeship_duration_inc_epa</td></tr>
    </table>
</div>
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Contact Details - Apprentice</strong></h4></th></tr>
        <tr><th>Name:</th><td>$ob_learner->firstnames $ob_learner->surname</td></tr>
        <tr><th>Job Title:</th><td>$this->job_title</td></tr>
        <tr><th>Contracted Hours per week:</th><td>$this->contracted_hours_per_week</td></tr>
        <tr><th>Personal Email:</th><td>$ob_learner->home_email</td></tr>
        <tr><th>Work Email:</th><td>$ob_learner->work_email</td></tr>
        <tr><th>Telephone/Mobile:</th><td>$this->home_telephone / $this->home_mobile</td></tr>
        <tr><th>Date of Birth:</th><td>$dob</td></tr>
        <tr><th>Address:</th><td>$learner_address</td></tr>
        <tr><th>Postcode:</th><td>$this->home_postcode</td></tr>
    </table>
</div>
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Employer</strong></h4></th></tr>
        <tr><th>Name:</th><td>$employer->legal_name</td></tr>
        <tr><th>Employer Mentor:</th><td>$employer_location->contact_name</td></tr>
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
        <tr><th>Trainer:</th><td>$trainer->firstnames $trainer->surname</td></tr>
        <tr><th>Trainer Email:</th><td>$trainer->work_email</td></tr>
        <tr><th>Telephone / Mobile:</th><td>$trainer->work_telephone / $trainer->work_mobile</td></tr>
    </table>
</div>
<p></p>
HTML;
            $subcontractor = Organisation::loadFromDatabase($link, $this->subcontractor_id);
            $subcontractor_location = Location::loadFromDatabase($link, $this->subcontractor_location_id);
            $subcontractor_name = !is_null($subcontractor) ? $subcontractor->legal_name : 'NA';
            $subcontractor_ukprn = !is_null($subcontractor) ? $subcontractor->ukprn : '';
            $subcontractor_address = !is_null($subcontractor_location) ?
                $subcontractor_location->address_line_1 . ' ' .
                $subcontractor_location->address_line_2 . ' ' .
                $subcontractor_location->address_line_3 . ' ' .
                $subcontractor_location->address_line_4 : '';
            $subcontractor_postcode = !is_null($subcontractor_location) ? $subcontractor_location->postcode : '';

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
HTML;

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Off-the-Job Training - 20% minimum</strong></h4></th></tr>';
            echo '<tr><th>Contracted hours per week</th><td>' . $this->contracted_hours_per_week . '</td></tr>';
            echo '<tr><th>Weeks to be worked per year</th><td>' . $this->weeks_to_be_worked_per_year . '</td></tr>';
            echo '<tr><th>Total contracted hours per year</th><td>' . $this->total_contracted_hours_per_year . '</td></tr>';
            if($this->contracted_hours_per_week >= 30)
            {
                echo '<tr><td colspan="2"></td></tr><tr><th colspan="2" class="bg-green-gradient">Full Time Hours (30 or above)</th></tr>';
                echo '<tr><th>Length of Programme (Practical Period)</th><td>' . $this->duration_practical_period . ' months</td></tr>';
                echo '<tr><th>Total Contracted Hours - Full Apprenticeship</th><td>' . $this->total_contracted_hours_full_apprenticeship . ' hours</td></tr>';
                echo '<tr class="bg-light-blue-gradient"><th>Minimum 20% OTJ Training</th><td>' . $this->minimum_percentage_otj_training . ' hours</td></tr>';
            }
            else
            {
                echo '<tr><td colspan="2"></td></tr>';
                echo '<tr><th colspan="2" class="bg-green-gradient">Part Time Hours (less than 30)</th></tr>';
                echo '<tr><th>Minimum Duration (part time)</th><td>' . $this->minimum_duration_part_time . ' months</td></tr>';
                echo '<tr><th>Total Contracted Hours - Full Apprenticeship</th><td>' . $this->part_time_total_contracted_hours_full_apprenticeship . ' hours</td></tr>';
                echo '<tr class="bg-light-blue-gradient"><th>Minimum 20% OTJ Training</th><td>' . $this->part_time_otj_hours . ' hours</td></tr>';
            }
            echo '<tr><td colspan="2"></td></tr>';
            //echo '<tr class="bg-green-gradient"><th>Planned Delivery Hours (OTJ) following Skills Analysis</th><td>' . $skills_analysis->delivery_plan_hours_fa . '</td></tr>';
            echo '</table>';
            echo '</div><p></p><hr>';

            echo '<h2><strong>Section 2 - ALS</strong></h2>';

            $als_records = DAO::getResultset($link, "SELECT * FROM ob_learner_als WHERE tr_id = '{$this->id}' ORDER BY id", DAO::FETCH_ASSOC);
            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="5" style="color: #000; background-color: #d2d6de !important"><h4><strong>Additional Learning Support</strong></h4></th></tr>';
            echo '<tr><th style="width: 15%;">Date Discussed</th><th style="width: 15%;">Support Required</th><th style="width: 20%;">Details</th><th style="width: 20%;">Date Claimed From</th><th style="width: 30%;">Additional Info.</th></tr>';
            if(count($als_records) == 0)
                echo '<tr><td colspan="5"><i>No records.</i></td></tr>';
            foreach($als_records AS $als_row)
            {
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
            echo '</div><p></p><hr>';

            $planned_reviews_start_date = $this->practical_period_start_date;
            $planned_reviews_end_date = $this->practical_period_end_date;

            echo '<h2><strong>Section 3 - Delivery Plan</strong></h2>';

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="11" style="color: #000; background-color: #d2d6de !important"><h4><strong>Training to be delivered</strong></h4></th></tr>';
            echo '<tr class="bg-light-blue">
                                    <th>Training to be delivered</th>
                                    <th>Exempt</th>
                                    <th>Level</th>
                                    <th>Details</th>
                                    <th>Start Date</th>
                                    <th>Planned End Date</th>
                                    <th>Number of months</th>
                                </tr>';
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
    ob_learner_quals.tr_id = '{$this->id}' AND 
    framework_qualifications.framework_id = '{$this->framework_id}'   
SQL;
            $ob_quals = DAO::getResultset($link, $ob_quals_sql, DAO::FETCH_ASSOC);
            foreach($ob_quals AS $qual)
            {
                echo '<tr>';
                echo '<td>' . $qual['qual_id'] .  ' ' . $qual['qual_title'] . '</td>';
                if($qual['qual_exempt'] == 0)
                {
                    echo '<td>No</td>';    
                }
                elseif($qual['qual_exempt'] == 1)
                {
                    echo '<td>Yes</td>';    
                }
                elseif($qual['qual_exempt'] == 2)
                {
                    echo '<td>Pending</td>';    
                }
                else
                {
                    echo '<td></td>';    
                }
                echo '<td>' . $qual['level'] . '</td>';
                echo '<td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_qual_type WHERE id = '{$qual['qualification_type']}'") . '</td>';
                echo '<td>' . Date::toShort($qual['qual_start_date']) . '</td>';
                echo '<td>' . Date::toShort($qual['qual_end_date']) . '</td>';
                echo '<td>' . $qual['no_of_months'] . ' months</td>';
                if($qual['main_aim'] == 1)
                {
                    $planned_reviews_start_date = $qual['qual_start_date'];
                    $planned_reviews_end_date = $qual['qual_end_date'];
                }
                echo '</tr>';
            }

            echo '</table>';
            echo '</div><p></p>';

            echo '<div style="text-align: center;">';
            echo '<h4 class="text-bold">Planned Reviews - (main provider, employer, apprentice must be present)</h4>';
            echo '<p>The first review should take place at week 4, and all other reviews every 8 weeks and should be signed off by all parties on OneFile.</p>';
            echo '<p>Reviews should discuss progress to date against the trianing plan and the immediate next steps required.</p>';

            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="3" style="color: #000; background-color: #d2d6de !important"><h4><strong>Planned Reviews</strong></h4></th></tr>';
            echo '<tr><th>Review Number</th><th>Planned Date</th><th>Actual Date</th>';

            $_review_dates = OnboardingHelper::getReviewsDates($planned_reviews_start_date, $planned_reviews_end_date);
            foreach ($_review_dates as $_review_number => $_review_date) {
                echo "<tr><td>{$_review_number}</td><td>{$_review_date}</td><td></td></tr>";
            }

            echo '</table>';
            echo '</div><p></p><hr>';

            $sql = <<<SQL
SELECT 
    framework_qualifications.evidences, framework_qualifications.title
FROM
    ob_learner_quals
    LEFT JOIN framework_qualifications ON REPLACE(ob_learner_quals.qual_id, '/', '') = REPLACE(framework_qualifications.id, '/', '') 
WHERE
    ob_learner_quals.tr_id = '{$this->id}' AND 
    framework_qualifications.framework_id = '{$this->framework_id}'
    AND framework_qualifications.main_aim = '1'
SQL;
            $main_aim_detail = DAO::getObject($link, $sql);
            $main_aim_xml = XML::loadSimpleXML($main_aim_detail->evidences);
            $units = $main_aim_xml->xpath('//unit');
            $q_units = array();
            foreach ($units AS $unit)
            {
                $temp = array();
                $temp = (array)$unit->attributes();
                $temp = $temp['@attributes'];
                $q_units[] = $temp;
            }
            $units_ddl[] = $q_units;
            echo '<h4>Main Aim Components</h4>';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<caption style="color: #000; background-color: #d2d6de !important"><h4><strong>' . $main_aim_detail->title . '</h4></caption>';
            
            foreach($units_ddl[0] AS $row)
            {
                echo '<tr>';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td>' . $row['glh'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div><p></p>';


            echo '<h2><strong>Section 4 - Training Plan Roles and Responsibilities</strong></h2>';
            echo '<div>';
            echo '<h4>Roles, Responsibilities & Declarations</h4>';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<caption style="color: #000; background-color: #d2d6de !important"><h4><strong>Learner Roles & Responsibilities</strong></h4></caption>';
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'LEARNER' ORDER BY id", DAO::FETCH_ASSOC);
            foreach($result AS $row)
            {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div><p></p>';

            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<caption style="color: #000; background-color: #d2d6de !important"><h4><strong>The Employer (Manager of Apprentice) agrees to:</strong></h4></caption>';
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'EMPLOYER' AND sub_id = 0 ORDER BY id", DAO::FETCH_ASSOC);
            $first_loop = true;
            $previous_id = '';
            foreach($result AS $row)
            {
                echo '<tr>';
                echo $previous_id != $row['id'] ? '<td>' . $row['id'] . '</td>' : '<td></td>';
                echo '<td>';
                echo $row['description'];
                $subs = DAO::getSingleColumn($link, "SELECT description FROM lookup_cs_roles_responsibilities WHERE sub_id = '{$row['id']}' AND user_type = 'EMPLOYER'");
                if(count($subs) > 0)
                    echo '<ul>';
                foreach($subs AS $sub)
                {
                    echo '<li style="margin-left: 20px;">' . $sub . '</li>';
                }
                if(count($subs) > 0)
                    echo '</ul>';
                echo '</td>';
                echo '</tr>';
                $first_loop = false;
                $previous_id = $row['id'];
            }
            echo '</table>';
            echo '</div><p></p>';

            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<caption style="color: #000; background-color: #d2d6de !important"><h4><strong>The Main Provider agrees to:</strong></h4></caption>';
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_roles_responsibilities WHERE user_type = 'PROVIDER' AND sub_id = 0 ORDER BY id", DAO::FETCH_ASSOC);
            $first_loop = true;
            $previous_id = '';
            foreach($result AS $row)
            {
                echo '<tr>';
                echo $previous_id != $row['id'] ? '<td>' . $row['id'] . '</td>' : '<td></td>';
                echo '<td>';
                echo $row['description'];
                $subs = DAO::getSingleColumn($link, "SELECT description FROM lookup_cs_roles_responsibilities WHERE sub_id = '{$row['id']}' AND user_type = 'PROVIDER'");
                if(count($subs) > 0)
                    echo '<ul>';
                foreach($subs AS $sub)
                {
                    echo '<li style="margin-left: 20px;">' . $sub . '</li>';
                }
                if(count($subs) > 0)
                    echo '</ul>';
                echo '</td>';
                echo '</tr>';
                $first_loop = false;
                $previous_id = $row['id'];
            }
            echo '</table>';
            echo '</div><p></p>';

            $provider_legal_name = $provider->legal_name;
            echo <<<HTML
<table border="1" style="width: 100%;" cellpadding="6">
    <caption style="color: #000; background-color: #d2d6de !important"><h4><strong>Working Together</strong></h4></caption>
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
    <caption style="color: #000; background-color: #d2d6de !important"><h4><strong>Queries and Complaints Process</strong></h4></caption>
    <tr>
        <td>
            <p>A formal complaint should be put in writing to the Operations Manager; <a class="text-blue" href="mailto:donna.johal@crackerjacktraining.com">donna.johal@crackerjacktraining.com</a> you will receive a response to your complaint within a further 10 working days. 
                If you are not satisfied with the outcome of the stage one consideration of your complaint you may request a review of the decision within 10 working days of receiving the outcome. 
                You must submit a written explanation to the Managing Director; <a class="text-blue" href="fiona.baker@crackerjacktraining.com">fiona.baker@crackerjacktraining.com</a>, of why you are dissatisfied with the outcome of stage one. 
                If following this process the complaint has not been addressed, you can raise this issue directly with the Department for Education, (the DfE) through; DfE at <a class="text-blue" href="complaints.esfa@education.gov.uk">complaints.esfa@education.gov.uk</a>.</p>
        </td>
    </tr>
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
            echo '<caption style="color: #000; background-color: #d2d6de !important"><h4><strong>Learner Declarations</strong></h4></caption>';
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'LEARNER' ORDER BY id", DAO::FETCH_ASSOC);
            $saved_learner_dec = explode(",", $this->learner_dec);
            foreach($result AS $row)
            {
                echo '<tr>';
                if(in_array($row['id'], $saved_learner_dec))
                    echo '<td align="right">Yes </td>';
                else
                    echo '<td align="right"></td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div><p></p>';

            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<caption style="color: #000; background-color: #d2d6de !important"><h4><strong>Employer Declarations</strong></h4></caption>';
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'EMPLOYER' ORDER BY id", DAO::FETCH_ASSOC);
            $saved_employer_dec = explode(",", $this->emp_dec);
            foreach($result AS $row)
            {
                echo '<tr>';
                if(in_array($row['id'], $saved_employer_dec))
                    echo '<td align="right">Yes </td>';
                else
                    echo '<td align="right"></td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div><p></p>';

            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<caption style="color: #000; background-color: #d2d6de !important"><h4><strong>Provider Declarations</strong></h4></caption>';
            $result = DAO::getResultset($link, "SELECT * FROM lookup_cs_declarations WHERE user_type = 'PROVIDER' ORDER BY id", DAO::FETCH_ASSOC);
            $saved_tp_dec = explode(",", $this->tp_dec);
            foreach($result AS $row)
            {
                echo '<tr>';
                if(in_array($row['id'], $saved_tp_dec))
                    echo '<td align="right">Yes </td>';
                else
                    echo '<td align="right"></td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '</div><p></p>';


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
            <td>{$this->emp_sign_name}</td>
            <td><img src="$emp_signature_file" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$emp_sign_date}</td>
        </tr>
        <tr>
            <td>Provider</td>
            <td>{$this->tp_sign_name}</td>
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

            // $mpdf->Output('Employer App Agreement.pdf', 'I');

            $mpdf->Output($c_file, 'F');

        }
    }

    public function getSign(PDO $link)
    {
        $pre_iag_sign = DAO::getSingleValue($link, "SELECT learner_sign FROM ob_learner_pre_iag_form WHERE tr_id = '{$this->id}'");
        $learn_style_sign = DAO::getSingleValue($link, "SELECT learner_sign FROM ob_learner_learning_style WHERE tr_id = '{$this->id}'");
        $writing_assessment_sign = DAO::getSingleValue($link, "SELECT learner_sign FROM ob_learner_writing_assessment WHERE tr_id = '{$this->id}'");
        $skills_scan_sign = DAO::getSingleValue($link, "SELECT learner_sign FROM ob_learner_skills_analysis WHERE tr_id = '{$this->id}'");

        if($this->learner_sign != '')
            return $this->learner_sign;
        elseif($skills_scan_sign != '')
            return $skills_scan_sign;
        elseif($writing_assessment_sign != '')
            return $writing_assessment_sign;
        elseif($learn_style_sign != '')
            return $learn_style_sign;
        elseif($pre_iag_sign != '')
            return $pre_iag_sign;
        else
            return '';
    }

    public function isArchived()
    {
        return $this->status_code == self::STATUS_ARCHIVED;
    }

    public function isNonApp(PDO $link)
    {
        $framework = Framework::loadFromDatabase($link, $this->framework_id);
        return in_array($framework->fund_model, [Framework::FUNDING_STREAM_BOOTCAMP, Framework::FUNDING_STREAM_ASF, Framework::FUNDING_STREAM_99]);
    }

    public function postJuly25Start()
    {
        $d = new Date($this->practical_period_start_date);
        $cd = new Date('2025-07-31');
        
        return $d->after($cd);
    }

    public function calculatedOtj(PDO $link)
    {
        $framework = Framework::loadFromDatabase($link, $this->framework_id);
        return $framework->calculatedOtj($link);
    }

    public function otjPW()
    {
        $v = str_replace('hpw_', '', $this->otj_duration_pw);
        return str_replace('p', '.', $v);
    }

    public $id = NULL;
    public $ob_learner_id = NULL;
    public $framework_id = NULL;
    public $employer_id = NULL;
    public $employer_location_id = NULL;
    public $provider_id = NULL;
    public $provider_location_id = NULL;
    public $subcontractor_id = NULL;
    public $subcontractor_location_id = NULL;
    public $epa_organisation = NULL;
    public $epa_price = NULL;
    public $trainers = NULL;
    public $practical_period_start_date = NULL;
    public $practical_period_end_date = NULL;
    public $duration_practical_period = NULL;
    public $apprenticeship_start_date = NULL;
    public $apprenticeship_end_date_inc_epa = NULL;
    public $apprenticeship_duration_inc_epa = NULL;
    public $planned_epa_date = NULL;
    public $status_code = NULL;
    public $contracted_hours_per_week = NULL;
    public $weeks_to_be_worked_per_year = NULL;
    public $total_contracted_hours_per_year = NULL;
    public $total_contracted_hours_full_apprenticeship = NULL;
    public $minimum_percentage_otj_training = NULL;
    public $created = NULL;
    public $modified = NULL;
    public $job_title = NULL;
    public $is_finished = NULL;
    public $llddcat = NULL;
    public $dp_set = NULL;
    public $RUI = NULL;
    public $PMC = NULL;
    public $disclaimer = NULL;
    public $hhs = NULL;
    public $LLDD = NULL;
    public $home_address_line_1 = NULL;
    public $home_address_line_2 = NULL;
    public $home_address_line_3 = NULL;
    public $home_address_line_4 = NULL;
    public $home_email = NULL;
    public $home_telephone = NULL;
    public $home_mobile = NULL;
    public $home_postcode = NULL;
    public $EmploymentStatus = NULL;
    public $work_curr_emp = NULL;
    public $SEI = NULL;
    public $PEI = NULL;
    public $SEM = NULL;
    public $empStatusEmployer = NULL;
    public $LOE = NULL;
    public $EII = NULL;
    public $LOU = NULL;
    public $BSI = NULL;
    public $ehc_plan = NULL;
    public $ehc_evidence_file = NULL;
    public $care_leaver = NULL;
    public $learner_dec = NULL;
    public $in_care_evidence_file = NULL;
    public $evidence_pp_file = NULL;
    public $evidence_ilr_file = NULL;
    public $evidence_previous_uk_study_visa_file = NULL;
    public $care_leaver_evidence_file = NULL;
    public $country_of_birth = NULL;
    public $country_of_perm_residence = NULL;
    public $nationality = NULL;
    public $primary_lldd = NULL;
    public $learner_sign = NULL;
    public $learner_sign_date = NULL;
    public $emp_sign_name = NULL;
    public $emp_sign = NULL;
    public $emp_sign_date = NULL;
    public $tp_sign_name = NULL;
    public $tp_sign = NULL;
    public $tp_sign_date = NULL;
    public $emp_dec = NULL;
    public $tp_dec = NULL;
    public $personality_test = NULL;
    public $personality_test_saved_at = NULL;
    public $EligibilityList = NULL;
    public $currently_enrolled_in_other = NULL;
    public $date_of_first_uk_entry = NULL;
    public $date_of_most_recent_uk_entry = NULL;
    public $passport_number = NULL;
    public $immigration_category = NULL;
    public $in_care_of_local_authority = NULL;
    public $eligible_for_bursary_payment = NULL;
    public $generate_pdfs = NULL;
    public $had_student_loan = NULL;
    public $student_loan_terminated = NULL;
    public $asked_to_contribute = NULL;
    public $funding_band_maximum = NULL;
    public $recommended_duration = NULL;
    public $minimum_duration_part_time = NULL;
    public $part_time_total_contracted_hours_full_apprenticeship = NULL;
    public $part_time_otj_hours = NULL;
    public $created_by = NULL;
    public $archive = NULL;

    public $training_cost = 0;
    public $training_material_cost = 0;
    public $reg_exam_certification_cost = 0;
    public $total_training_cost = 0;
    public $epa_cost = 0;
    public $total_negotiated_price = 0;
    public $subcontractor_training_cost = 0;
    public $subcontractor_management_cost = 0;
    public $additional_cost_funded_by_employer = 0;
    public $additional_cost_funded_by_provider = 0;
    public $additional_cost_resit1 = 0;
    public $additional_cost_resit2 = 0;
    public $sunesis_tr_id = 0;

    public $tnp1 = 0;
    public $additional_prices = 0;

    public $cohort_number = NULL;	
    public $off_the_job_hours_based_on_duration = NULL;
    public $price_reduction_percentage = NULL;	
    
    public $numeracy = NULL;	
    public $numeracy_diagnostic = NULL;	
    public $literacy = NULL;	
    public $literacy_diagnostic = NULL;	
    public $line_manager_id = NULL;	
    public $literacy_other = NULL;	
    public $literacy_diagnostic_other = NULL;	
    public $numeracy_other = NULL;	
    public $numeracy_diagnostic_other = NULL;	
    public $work_email = NULL;	
    public $term_time = NULL;	
    public $levy_gifted = NULL;	
    public $type_of_funding = NULL;	
    public $id_evidence_type = NULL;	
    public $evidence_reference = NULL;	
    public $evidence_expiry_date = NULL;	
    public $crime_conviction = NULL;	
    public $otj_overwritten = NULL;	
    public $coe = NULL;	
    public $coe_from_tr_id = NULL;	
    public $coe_to_tr_id = NULL;	
    public $glh = NULL;	
    public $borough = NULL;	
    public $ict = NULL;	
    public $ict_other = NULL;	
    public $esol = NULL;	
    public $esol_other = NULL;	
    public $prior_edu_checked = NULL;	
    public $other_residency_details = NULL;	
    public $earnings_below_llw = NULL;	
    public $bootcamp_via_current_emp = NULL;	
    public $bootcamp_with_work = NULL;	
    public $curr_emp_sector = NULL;	
    public $BSI_other_details = NULL;	
    public $free_school_meals = NULL;	
    public $fs_eng_opt_in = NULL;	
    public $fs_eng_opt_out_reason = NULL;	
    public $fs_maths_opt_in = NULL;	
    public $fs_maths_opt_out_reason = NULL;	
    public $otj_duration_pw = NULL;	
    public $otj_duration_pw_hours = NULL;	
    public $otj_hours = NULL;	
    public $commercial_fee = NULL;	
    public $commercial_fee_emp_cont = NULL;	
    public $all_amount = NULL;	
    public $all_before = NULL;	
    public $immigration_status = NULL;	
    public $have_uk_pp = NULL;	
    public $have_uk_bc = NULL;	
    public $purchase_order_no = NULL;	

    protected $audit_fields = [
        'epa_price' => 'EPA Price',
        'status_code' => 'Status Code',
        'practical_period_start_date' => 'Practical period start date',
        'practical_period_end_date' => 'Practical period end date',
        'duration_practical_period' => 'Duration practical period',
        'apprenticeship_start_date' => 'Apprenticeship start date',
        'apprenticeship_end_date_inc_epa' => 'Apprenticeship end date including EPA',
        'apprenticeship_duration_inc_epa' => 'Apprenticeship duration including EPA',
        'planned_epa_date' => 'Planned EPA Date',
        'contracted_hours_per_week' => 'Contracted hours per week',
        'weeks_to_be_worked_per_year' => 'Weeks to be worked per year',
        'tnp1' => 'TNP 1',
    ];

    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_ARCHIVED = 3;
    const STATUS_CONVERTED = 4;
    const STATUS_NOT_PROGRESSED = 5;
    const STATUS_CHANGE_OF_EMPLOYER = 6;
}
?>
