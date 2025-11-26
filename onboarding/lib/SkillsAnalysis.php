<?php

class SkillsAnalysis extends Entity
{
    public function __construct($tr_id)
    {
        $this->tr_id = $tr_id;
    }

    public static function loadFromDatabaseByTrainingRecordId(PDO $link, $tr_id)
    {
        if($tr_id == '')
        {
            return new SkillsAnalysis($tr_id);
        }

        $key = addslashes($tr_id);
        $query = <<<HEREDOC
SELECT
	*
FROM
	ob_learner_skills_analysis
WHERE
	tr_id='$key'
LIMIT 1;
HEREDOC;
        $st = $link->query($query);

        $skills_analysis = null;
        if($st)
        {
            $skills_analysis = null;
            $row = $st->fetch();
            if($row)
            {
                $skills_analysis = new SkillsAnalysis($tr_id);
                $skills_analysis->populate($row);

                $ksb_records = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb WHERE skills_analysis_id = '{$skills_analysis->id}' AND tr_id = '{$tr_id}' ORDER BY id", DAO::FETCH_ASSOC);
                foreach($ksb_records AS $ksb_row)
                {
                    $skills_analysis->ksb[] = [
                        'id' => $ksb_row['id'],
                        'tr_id' => $ksb_row['tr_id'],
                        'unit_group' => $ksb_row['unit_group'],
                        'unit_title' => $ksb_row['unit_title'],
                        'evidence_title' => $ksb_row['evidence_title'],
                        'score' => $ksb_row['score'],
                        'comments' => $ksb_row['comments'],
                        'del_hours' => $ksb_row['del_hours'],
                        'skills_analysis_id' => $ksb_row['skills_analysis_id'],
                    ];
                }
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find skills analysis. " . '----' . $query);
        }

        return $skills_analysis;
    }
    
    public static function loadFromDatabaseById(PDO $link, $id)
    {
        $key = addslashes($id);
        $query = <<<HEREDOC
SELECT
	*
FROM
	ob_learner_skills_analysis
WHERE
	id='$key'
LIMIT 1;
HEREDOC;
        $st = $link->query($query);

        $skills_analysis = null;
        if($st)
        {
            $skills_analysis = null;
            $row = $st->fetch();
            if($row)
            {
                $skills_analysis = new SkillsAnalysis($row['tr_id']);
                $skills_analysis->populate($row);

                $ksb_records = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb WHERE skills_analysis_id = '{$skills_analysis->id}' ORDER BY id", DAO::FETCH_ASSOC);
                foreach($ksb_records AS $ksb_row)
                {
                    $skills_analysis->ksb[] = [
                        'id' => $ksb_row['id'],
                        'tr_id' => $ksb_row['tr_id'],
                        'unit_group' => $ksb_row['unit_group'],
                        'unit_title' => $ksb_row['unit_title'],
                        'evidence_title' => $ksb_row['evidence_title'],
                        'score' => $ksb_row['score'],
                        'comments' => $ksb_row['comments'],
                        'del_hours' => $ksb_row['del_hours'],
                        'skills_analysis_id' => $ksb_row['skills_analysis_id'],
                    ];
                }
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find skills analysis. " . '----' . $query);
        }

        return $skills_analysis;
    }

    public function save(PDO $link)
    {
        $this->updated_at = date('Y-m-d H:i:s');
        
        return DAO::saveObjectToTable($link, 'ob_learner_skills_analysis', $this);
    }



    public function delete(PDO $link)
    {
    }

    public function isSafeToDelete(PDO $link)
    {
        return false;
    }

    public static function getScoreAndPercentageList()
    {
        // return [
        //     0 => 1,
        //     1 => 1,
        //     2 => 0.975,//0.99975,
        //     3 => 0.95,//0.9995,
        //     4 => 0.9,//0.9990,
        //     5 => 0.85,//0.9985,
        // ];
        return [
            0 => 0,
            1 => 0,
            2 => 0.025,
            3 => 0.05,
            4 => 0.1,
            5 => 0.15,
        ];
    }

    public static function getScoreAndPercentageNonDeliveryHoursList()
    {
        return [
            0 => 0,
            1 => 0,
            2 => 0.025,
            3 => 0.05,
            4 => 0.1,
            5 => 0.15,
        ];
    }

    public function getRplPercentages()
    {
        if($this->rpl_percentages != '')
        {
            return (array) json_decode($this->rpl_percentages);
        }

        $rpl_percentages = [];

        foreach(self::getScoreAndPercentageList() AS $key => $value)
        {
            $rpl_percentages["score_{$key}"] = $value;
        }

        return $rpl_percentages;
    }

    public static function getReducedValue($delivery_hours, $score)
    {
        if($delivery_hours == '' || $score == '')
            return;

        $scores = self::getScoreAndPercentageList();    

        $reduced_value = round(($delivery_hours*$scores[$score])/100, 2);

        return $delivery_hours - $reduced_value;
    }

    public function generatePdf(PDO $link)
    {
        if($this->learner_sign == '' || $this->provider_sign == '')
        {
            return;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $this->tr_id);

        $skills_analysis_directory = $tr->getDirectoryPath() . 'skills_analysis/';
        if(!is_dir($skills_analysis_directory))
        {
            mkdir("$skills_analysis_directory", 0777, true);
        }
        $sa_file = $skills_analysis_directory.OnboardingHelper::SKILLS_ANALYSIS_PDF_NAME;
        $trGeneratePdfs = $tr->generate_pdfs != '' ? explode(",", $tr->generate_pdfs) : [];
        if(!is_file($sa_file) || in_array("SS", $trGeneratePdfs) )
        {
            $sa_file = in_array("SS", $trGeneratePdfs) ? $skills_analysis_directory.'Skills Scan Result_'.uniqid().'.pdf' : $sa_file;

            $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$this->provider_id}'");
            if($logo == '')
                $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

            $mpdf = new \Mpdf\Mpdf(['format' => 'Legal', 'default_font_size' => 10]);
            $mpdf->setAutoBottomMargin = 'stretch';

            $sunesis_stamp = md5('ghost'.date('d/m/Y').$this->id);
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

            $framework = Framework::loadFromDatabase($link, $tr->framework_id);
            $ob_learner = $tr->getObLearnerRecord($link);
            $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
            $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
            $epa_name = $tr->getEpaOrgName($link);

            $sub_legal =$tr->getSubcontractorLegalName($link);
            $subcontractor_name = $sub_legal != '' ? $sub_legal : 'NA';
            $standard_title = $framework->getStandardCodeDesc($link);
            $standard_level = DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';");
            $practical_period_start_date = Date::toShort($tr->practical_period_start_date);
            $practical_period_end_date = Date::toShort($tr->practical_period_end_date);
            $apprenticeship_start_date = Date::toShort($tr->apprenticeship_start_date);
            $apprenticeship_end_date_inc_epa = Date::toShort($tr->apprenticeship_end_date_inc_epa);

            $prior_attainment = DAO::getSingleValue($link, "SELECT description FROM central.lookup_prior_attainment WHERE code IN (SELECT level FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = 'h');");
            $date_completed = Date::toShort($this->provider_sign_date);
            $funding_band_maximum = $framework->getFundingBandMax($link);
            $recommended_duration = $framework->getRecommendedDuration($link);


            echo <<<HTML
<div style="text-align: center;">
    <h2><strong>Apprenticeship Skills Analysis</strong></h2>
    <img width="200px;" class="img-responsive" src="$logo" />
</div>

<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Student Programme Details</strong></h4></th></tr>
        <tr><th>Date Completed:</th><td>{$date_completed}</td></tr>
        <tr>
            <th class="text-bold">Apprentice Name</th>
            <td>$ob_learner->firstnames $ob_learner->surname</td>
        </tr>
        <tr>
            <th class="text-bold">Employer Name</th>
            <td>$employer->legal_name</td>
        </tr>
        <tr>
            <th class="text-bold">Level</th>
            <td>$standard_level</td>
        </tr>
        <tr>
            <th class="text-bold">Apprenticeship Title</th>
            <td>$standard_title</td>
        </tr>
        <tr>
            <th class="text-bold">Funding Band Maximum</th>
            <td>&pound;$funding_band_maximum</td>
        </tr>
        <tr>
            <th class="text-bold">Recommended Duration (practical period) - months</th>
            <td>$recommended_duration</td>
        </tr>
        <tr>
            <th class="text-bold">Contracted Hours per week</th>
            <td>$tr->contracted_hours_per_week</td>
        </tr>
        <tr>
            <th class="text-bold">Apprentice Job Role</th>
            <td>$tr->job_title</td>
        </tr>
        <tr>
            <th class="text-bold">Main Training Provider</th>
            <td>$provider->legal_name</td>
        </tr>
        <tr>
            <th class="text-bold">Subcontractor (if applicable)</th>
            <td>$subcontractor_name</td>
        </tr>
        <tr>
            <th class="text-bold text-green">End Point Assessment Organisation</th>
            <td>$epa_name</td>
        </tr>
        <tr>
            <th class="text-bold text-green">End Point Assessment Price</th>
            <td>&pound;$tr->epa_price</td>
        </tr>
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
            foreach($qual_records AS $record)
            {
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
            foreach($employment_records AS $record)
            {
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
            echo '</div><p></p>';

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="7" style="color: #000; background-color: #d2d6de !important"><h4><strong>Knowledge, Skills & Behaviours</strong></h4></th></tr>';
            echo '<tr><th>KSB</th><th>Topic</th><th>Required</th><th>Score</th><th>Comments</th></tr>';
            
            
            //$score_percentages = self::getScoreAndPercentageList();

            foreach($this->ksb AS $row)
            {
                $delivery_plan_hours = 0;
                $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 20;
                echo '<tr>';
                echo '<td>' . $row['unit_group'] . '</td>';
                echo '<td>' . $row['unit_title'] . '</td>';
                echo '<td>' . str_replace("&amp;apos;", "'", $row['evidence_title']) . '</td>';
                echo '<td>' . $row['score'] . '</td>';
                echo '<td class="small">' . $row['comments'] . '</td>';
                echo '</tr>';
            }
            echo '<tr><th colspan="7" class="text-center bg-green-gradient">' . $this->percentage_fa . '%</th> </tr>';
            echo '</table>';
            echo '</div><p></p>';

            $learner_age_sql = <<<SQL
SELECT 
    ((DATE_FORMAT('{$tr->practical_period_start_date}','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT('{$tr->practical_period_start_date}','00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
            $learner_age = DAO::getSingleValue($link, $learner_age_sql);

            $tnp1 = json_decode($this->tnp1);
            $tnp1_fa = json_decode($this->tnp1_fa);

            $tnp1_total = array_sum(array_column($tnp1, 'cost'));
            $tnp_total = ceil($tnp1_total + $this->epa_price);

            $tnp1_total_fa = array_sum(array_column($tnp1_fa, 'cost'));
            $tnp_total_fa = ceil($tnp1_total_fa + $this->epa_price);

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Self Assessment Outcome</strong></h4></th></tr>';
            foreach($tnp1 AS $tnp1_ba)
            {
                echo '<tr><th>' . $tnp1_ba->description . '</th><td>&pound;' . $tnp1_ba->cost . '</td></tr>';
            }
            echo '<tr><th>End Point Assessment</th><td>&pound;' . $this->epa_price . '</td></tr>';
            echo '<tr>';
            echo '<th>Total Negotiated Price</th>';
            echo '<td>&pound;' . $tnp_total;
            echo '</td>';
            echo '</tr>';
            echo '<tr><th>Original Recommended Duration</th>';
            echo '<td>' . $recommended_duration . ' months</td>';
            echo '</tr>';
            echo '<tr class="bg-gray-light"><th colspan="2">Following Skills Analysis figures</th></tr>';
            echo '<tr><th>Percentage Reduction to be applied</th><td>' . $this->percentage_fa . '%</td></tr>';
            foreach($tnp1_fa AS $tnp1_fa)
            {
                echo '<tr><th>' . $tnp1_fa->description . '</th><td>&pound;' . $tnp1_fa->cost . '</td></tr>';
            }
            echo '<tr><th>End Point Assessment</th><td>&pound;' . $this->epa_price . '</td></tr>';
            echo '<tr>';
            echo '<th>Total Negotiated Price</th>';
            echo '<td>&pound;' . $tnp_total_fa;
            echo '</td>';
            echo '</tr>';

            echo $tr->contracted_hours_per_week >= 30 ? '<tr><th>Minimum Duration (months)</th>' : '<tr><th>Minimum Duration - Part Time (months)</th>';
            echo $tr->contracted_hours_per_week >= 30 ? '<td>' . $this->duration_fa . ' months</td>' : '<td>' . $tr->minimum_duration_part_time . ' months</td>';
            echo '</tr>';
            echo '</table>';
            echo '</div><p></p>';

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th style="color: #000; background-color: #d2d6de !important"><h4><strong>Rationale (Duration and Negotiated Price)</strong></h4></th></tr>';
            echo '<tr><td>' . $this->rationale_by_provider . '</td></tr>';
            echo '</table>';
            echo '</div><p></p>';

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Off-the-Job Hours</strong></h4></th></tr>';
            echo '<tr><th>Contracted hours per week</th><td>' . $tr->contracted_hours_per_week . '</td></tr>';
            echo '<tr><th>Weeks to be worked per year</th><td>' . $tr->weeks_to_be_worked_per_year . '</td></tr>';
            echo '<tr><th>Total contracted hours per year</th><td>' . $tr->total_contracted_hours_per_year . '</td></tr>';
            if($tr->contracted_hours_per_week >= 30)
            {
                echo '<tr><td colspan="2"></td></tr><tr><th colspan="2" class="bg-green-gradient">Full Time Hours (30 or above)</th></tr>';
                echo '<tr><th>Length of Programme (Practical Period)</th><td>' . $tr->duration_practical_period . ' months</td></tr>';
                echo '<tr><th>Total Contracted Hours - Full Apprenticeship</th><td>' . $tr->total_contracted_hours_full_apprenticeship . ' hours</td></tr>';
                echo '<tr class="bg-light-blue-gradient"><th>Minimum 20% OTJ Training</th><td>' . $tr->minimum_percentage_otj_training . ' hours</td></tr>';
            }
            else
            {
                echo '<tr><td colspan="2"></td></tr>';
                echo '<tr><th colspan="2" class="bg-green-gradient">Part Time Hours (less than 30)</th></tr>';
                echo '<tr><th>Minimum Duration (part time)</th><td>' . $tr->minimum_duration_part_time . ' months</td></tr>';
                echo '<tr><th>Total Contracted Hours - Full Apprenticeship</th><td>' . $tr->part_time_total_contracted_hours_full_apprenticeship . ' hours</td></tr>';
                echo '<tr class="bg-light-blue-gradient"><th>Minimum 20% OTJ Training</th><td>' . $tr->part_time_otj_hours . ' hours</td></tr>';
            }
            echo '<tr><td colspan="2"></td></tr>';
            echo '<tr><td colspan="2"></td></tr>';
            echo '<tr class="bg-green-gradient"><th>Planned Delivery Hours (OTJ) following Skills Analysis</th><td>' . $this->delivery_plan_hours_fa . '</td></tr>';
            echo '</table>';
            echo '</div><p></p>';

            echo '<div style="text-align: center;">';
            echo '<table border="1" style="width: 100%;" cellpadding="6">';
            echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Learner\'s Eligibility</strong></h4></th></tr>';
            if($this->is_eligible_after_ss == 'Y')
                echo '<tr><th colspan="2">Learner is Eligible</th></tr>';
            elseif($this->is_eligible_after_ss == 'N')
            {
                echo '<tr><th colspan="2">Learner is NOT Eligible</th></tr>';
                echo '<tr><th>Reason: </th><td>' . $this->ineligibility_reason . '</td></tr>';
            }
            echo '</table>';
            echo '</div><p></p>';

            $learner_signature_file = $skills_analysis_directory . 'learner_sign_image.png';
            $provider_signature_file = $skills_analysis_directory . 'provider_sign_image.png';
            $learner_sign_date = isset($this->learner_sign_date) ? Date::toShort($this->learner_sign_date) : '';
            $provider_sign_date = isset($this->provider_sign_date) ? Date::toShort($this->provider_sign_date) : '';
            $provider_user_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$this->provider_user_id}'");
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

            $mpdf->WriteHTML($html);

//            $mpdf->Output('Employer App Agreement.pdf', 'I');

            $mpdf->Output($sa_file, 'F');
        }
    }

    public static function calculateOtjForPartTimers(PDO $link, $tr_id, $duration_in_months, $percentage = '')
    {
        if($tr_id == '')
        {
            echo 0;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $sed = new Date($tr->practical_period_start_date);
        $ped = new Date($tr->practical_period_start_date);
        $ped->addMonths($duration_in_months);
        $ped->addDays(1);

        $total_weeks_on_programme = self::calculateTotalWeeksOnProgramme($link, $sed->formatMySQL(), $ped->formatMySQL());

        $annual_leave_for_total_weeks_on_programme = self::calculateAnnualLeaveForTotalWeeksOnProgramme($total_weeks_on_programme);

        $actual_weeks_on_programme = $total_weeks_on_programme-$annual_leave_for_total_weeks_on_programme;

        $total_contracted_hours = $tr->contracted_hours_per_week * $actual_weeks_on_programme;
        
        $off_the_job_hours_part_time = round($total_contracted_hours * 0.2);

        $off_the_job_hours_part_time = self::checkForMimimumOtjHours( intval($off_the_job_hours_part_time) );

        $result = (object)[
            'total_weeks_on_programme' => $total_weeks_on_programme,
            'statutory_leave_entitlement_in_weeks' => $annual_leave_for_total_weeks_on_programme,
            'actual_weeks_on_programme' => $actual_weeks_on_programme,
            'off_the_job_hours' => round($off_the_job_hours_part_time),
            'sed' => $sed->formatMySQL(),
            'ped' => $ped->formatMySQL(),
        ];

        if($percentage != '' && $tr->postJuly25Start())
        {
            $reduction = 100 - $percentage;
            $reducedOtjHours = round($tr->otj_hours * (1 - ($reduction / 100)), 2);
            $reducedDuration = round($tr->duration_practical_period * (1 - ($reduction / 100)), 2);
            $ped = new Date($sed->formatMySQL());
            $ped->addMonths($reducedDuration);  
            $ped->addDays(1);
            $total_weeks_on_programme = self::calculateTotalWeeksOnProgramme($link, $sed->formatMySQL(), $ped->formatMySQL());
            $annual_leave_for_total_weeks_on_programme = self::calculateAnnualLeaveForTotalWeeksOnProgramme($total_weeks_on_programme);
            $actual_weeks_on_programme = $total_weeks_on_programme-$annual_leave_for_total_weeks_on_programme;
            $result = (object)[
                'total_weeks_on_programme' => $total_weeks_on_programme,
                'statutory_leave_entitlement_in_weeks' => $annual_leave_for_total_weeks_on_programme,
                'actual_weeks_on_programme' => $actual_weeks_on_programme,
                'off_the_job_hours' => round($reducedOtjHours),
                'duration_practical_period' => round($reducedDuration),
                'sed' => $sed->formatMySQL(),
                'ped' => $ped->formatMySQL(),
            ];
        }

        return $result;
    }

    public static function calculateOtjForFullTimers(PDO $link, $tr_id, $duration_in_months, $percentage = '')
    {
        if($tr_id == '')
        {
            echo 0;
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $sed = new Date($tr->practical_period_start_date);
        $ped = new Date($tr->practical_period_start_date);
        $ped->addMonths($duration_in_months);
        $ped->addDays(1);

        $total_weeks_on_programme = self::calculateTotalWeeksOnProgramme($link, $sed->formatMySQL(), $ped->formatMySQL());

        $annual_leave_for_total_weeks_on_programme = self::calculateAnnualLeaveForTotalWeeksOnProgramme($total_weeks_on_programme);

        $actual_weeks_on_programme = $total_weeks_on_programme-$annual_leave_for_total_weeks_on_programme;

        $off_the_job_hours_based_on_duration = $tr->postJuly25Start() ? $tr->calculatedOtj($link) : self::calculateOtjFullTime($actual_weeks_on_programme);

        $off_the_job_hours_based_on_duration = self::checkForMimimumOtjHours($off_the_job_hours_based_on_duration);

        $result = (object)[
            'total_weeks_on_programme' => $total_weeks_on_programme,
            'statutory_leave_entitlement_in_weeks' => $annual_leave_for_total_weeks_on_programme,
            'actual_weeks_on_programme' => $actual_weeks_on_programme,
            'off_the_job_hours' => $off_the_job_hours_based_on_duration,
            'sed' => $sed->formatMySQL(),
            'ped' => $ped->formatMySQL(),
        ];

        if($percentage != '' && $tr->postJuly25Start())
        {
            $reduction = 100 - $percentage;
            $reducedOtjHours = round($tr->otj_hours * (1 - ($reduction / 100)), 2);
            $reducedDuration = round($tr->duration_practical_period * (1 - ($reduction / 100)), 2);
            $ped = new Date($sed->formatMySQL());
            $ped->addMonths($reducedDuration);  
            $ped->addDays(1);
            $total_weeks_on_programme = self::calculateTotalWeeksOnProgramme($link, $sed->formatMySQL(), $ped->formatMySQL());
            $annual_leave_for_total_weeks_on_programme = self::calculateAnnualLeaveForTotalWeeksOnProgramme($total_weeks_on_programme);
            $actual_weeks_on_programme = $total_weeks_on_programme-$annual_leave_for_total_weeks_on_programme;
            $result = (object)[
                'total_weeks_on_programme' => $total_weeks_on_programme,
                'statutory_leave_entitlement_in_weeks' => $annual_leave_for_total_weeks_on_programme,
                'actual_weeks_on_programme' => $actual_weeks_on_programme,
                'off_the_job_hours' => round($reducedOtjHours),
                'duration_practical_period' => round($reducedDuration),
                'sed' => $sed->formatMySQL(),
                'ped' => $ped->formatMySQL(),
            ];
        }

        return $result;
    }

    public static function calculateOtjPartTime($totalContractedHours)
    {
        return round($totalContractedHours*0.2);
    }

    public static function calculateOtjFullTime($actualWeeksOnProgramme)
    {
        return round($actualWeeksOnProgramme*6);
    }

    public static function calculateOtjForFullTimersFromDates(PDO $link, $tr)
    {
        $total_weeks_on_programme = self::calculateTotalWeeksOnProgramme($link, $tr->practical_period_start_date, $tr->practical_period_end_date);

        $annual_leave_for_total_weeks_on_programme = self::calculateAnnualLeaveForTotalWeeksOnProgramme($total_weeks_on_programme);

        $actual_weeks_on_programme = $total_weeks_on_programme-$annual_leave_for_total_weeks_on_programme;

        $off_the_job_hours_based_on_duration = $tr->postJuly25Start() ? $tr->calculatedOtj($link) : self::calculateOtjFullTime($actual_weeks_on_programme);
        
        $off_the_job_hours_based_on_duration = self::checkForMimimumOtjHours($off_the_job_hours_based_on_duration);

        $result = (object)[
            'total_weeks_on_programme' => $total_weeks_on_programme,
            'statutory_leave_entitlement_in_weeks' => $annual_leave_for_total_weeks_on_programme,
            'actual_weeks_on_programme' => $actual_weeks_on_programme,
            'off_the_job_hours' => $off_the_job_hours_based_on_duration,
        ];

        return $result;
    }

    public static function checkForMimimumOtjHours($otjHours)
    {
        return $otjHours < 279 ? 279 : $otjHours;
    }

    public static function calculateTotalWeeksOnProgramme(PDO $link, $start_date, $end_date)
    {
        $sed = new Date($start_date);
        $ped = new Date($end_date);

        return DAO::getSingleValue($link, "SELECT ( TIMESTAMPDIFF(DAY, '" . $sed->formatMySQL() . "', '" . $ped->formatMySQL() . "') / 7 );");
    }

    public static function calculateAnnualLeaveForTotalWeeksOnProgramme($totalWeeksOnProgramme)
    {
        return ( $totalWeeksOnProgramme/52.1429 ) * SkillsAnalysis::YEARLY_ANNUAL_LEAVE;
    }

    public $id = NULL;
    public $tr_id = NULL;
    public $signed_by_learner = NULL;
    public $learner_sign = NULL;
    public $learner_sign_date = NULL;
    public $signed_by_employer = NULL;
    public $employer_sign = NULL;
    public $employer_sign_date = NULL;
    public $employer_comments = NULL;
    public $signed_by_provider = NULL;
    public $provider_sign = NULL;
    public $provider_sign_date = NULL;
    public $percentage_fa = NULL;
    public $duration_ba = NULL;
    public $duration_fa = NULL;
    public $training_cost_ba = NULL;
    public $training_cost_fa = NULL;
    public $delivery_plan_hours_ba = NULL;
    public $delivery_plan_hours_fa = NULL;
    public $rationale_by_provider = NULL;
    public $provider_user_id = NULL;
    public $is_eligible_after_ss = NULL;
    public $ineligibility_reason = NULL;
    public $is_finished = NULL;
    public $is_completed_by_learner = NULL;
    public $updated_at = NULL;
    public $tnp1 = NULL;
    public $epa_price = NULL;
    public $epa_price_fa = NULL;
    public $additional_prices = NULL;
    public $tnp1_fa = NULL;
    public $rpl_percentages = NULL;
    public $off_the_job_hours_based_on_duration = NULL;
    public $price_reduction_percentage = NULL;
    public $minimum_duration_part_time = NULL;
    public $minimum_percentage_otj_training = NULL;
    public $part_time_otj_hours = NULL;
    public $part_time_total_contracted_hours_full_apprenticeship = NULL;
    public $lock_for_learner = NULL;
    public $employer_sign_name = NULL;	
    public $otj_pw_ba = NULL;	
    public $otj_pw_fa = NULL;	

    public $ksb = NULL;

    protected $audit_fields = [
        'learner_sign' => 'Learner sign',
        'learner_sign_date' => 'Learner sign date',
        'signed_by_provider' => 'Signed by provider',
        'provider_sign' => 'Provider sign',
        'provider_sign_date' => 'Provider sign date',
        'percentage_fa' => 'Following percentage',
        'duration_ba' => 'Before duration',
        'duration_fa' => 'Following duration',
        'training_cost_ba' => 'Before training cost',
        'training_cost_fa' => 'Following training_cost',
        'delivery_plan_hours_ba' => 'Before deliveryplanhours',
        'delivery_plan_hours_fa' => 'Following delivery plan hours',
        'rationale_by_provider' => 'Comments by provider',
        'provider_user_id' => 'Provider user id',
        'is_eligible_after_ss' => 'Is eligible',
        'ineligibility_reason' => 'Ineligibility reason',
        'is_finished' => 'Is finished',
        'is_completed_by_learner' => 'Is completed by learner',
    ];

	const YEARLY_ANNUAL_LEAVE = 5.6;

}