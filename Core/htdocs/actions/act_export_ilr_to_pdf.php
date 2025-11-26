<?php
class export_ilr_to_pdf implements IAction
{
    public function execute(PDO $link)
    {
        require 'vendor/autoload.php';

        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $xml = isset($_REQUEST['xml']) ? $_REQUEST['xml'] : '';
        $contract_id = isset($_REQUEST['contract_id']) ? $_REQUEST['contract_id'] : '';

        if($tr_id == '' || $xml == '' || $contract_id == '')
            throw new Exception("Missing querystring arguments");

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);

        $contract = Contract::loadFromDatabase($link, $contract_id);

        $contract_year = $contract->contract_year;

        $xml = Ilr2018::loadFromXML($xml);

        //include_once("./MPDF57/mpdf.php");

        //$mpdf = new \Mpdf\Mpdf();
        //$mpdf=new mPDF('','A4-L','9','',5,5,22,5,5,5);

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4-L',
            'default_font_size' => 9,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 22,
            'margin_bottom' => 5,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);

        $stylesheet = file_get_contents('common.css');
        $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

        $mpdf->SetHTMLHeader($this->getHeader($link, $contract_year));
        $mpdf->SetHTMLFooter($this->getFooter($link, $tr_id));
        $mpdf->AddPage('L');

        //first page
        $dob = Date::toShort($xml->DateOfBirth);

        $address = '';
        $current_postcode = '';
        $postcode_prior_enrolment = '';
        $email = '';
        $tel_number = '';
        foreach($xml->LearnerContact AS $LearnerContact)
        {
            if($LearnerContact->LocType->__toString() == '1' && $LearnerContact->ContType->__toString() == '2')
            {
                $address .= isset($LearnerContact->PostAdd->AddLine1) ? $LearnerContact->PostAdd->AddLine1->__toString() : '';
                $address .= isset($LearnerContact->PostAdd->AddLine2) ? '<br>' . $LearnerContact->PostAdd->AddLine2->__toString() : '';
                $address .= isset($LearnerContact->PostAdd->AddLine3) ? '<br>' . $LearnerContact->PostAdd->AddLine3->__toString() : '';
                $address .= isset($LearnerContact->PostAdd->AddLine4) ? '<br>' . $LearnerContact->PostAdd->AddLine4->__toString() : '';
            }
            if($LearnerContact->LocType->__toString() == '2' && $LearnerContact->ContType->__toString() == '2')
            {
                $current_postcode = isset($LearnerContact->PostCode) ? $LearnerContact->PostCode->__toString() : '';
            }
            if($LearnerContact->LocType->__toString() == '2' && $LearnerContact->ContType->__toString() == '1')
            {
                $postcode_prior_enrolment = isset($LearnerContact->PostCode) ? $LearnerContact->PostCode->__toString() : '';
            }
            if($LearnerContact->LocType->__toString() == '4' && $LearnerContact->ContType->__toString() == '2')
            {
                $email = isset($LearnerContact->Email) ? $LearnerContact->Email->__toString() : '';
            }
            if($LearnerContact->LocType->__toString() == '3' && $LearnerContact->ContType->__toString() == '2')
            {
                $tel_number = isset($LearnerContact->TelNumber) ? $LearnerContact->TelNumber->__toString() : '';
            }
        }

        $LLDDHealthProb = 'No information provided by the learner';
        if($xml->LLDDHealthProb == '1')
            $LLDDHealthProb = $xml->Sex == 'M' ? 'Learner considers himself to have a learning difficulty and/or disability and/or health problem' : 'Learner considers herself to have a learning difficulty and/or disability and/or health problem';
        if($xml->LLDDHealthProb == '2')
            $LLDDHealthProb = $xml->Sex == 'M' ? 'Learner does not consider himself to have a learning difficulty and/or disability and/or health problem' : 'Learner does not consider herself to have a learning difficulty and/or disability and/or health problem';

        $LLDDCats = [
            "1" => "1 Emotional/behavioural difficulties",
            "2" => "2 Multiple disabilities",
            "3" => "3 Multiple learning difficulties",
            "4" => "4 Visual impairment",
            "5" => "5 Hearing impairment",
            "6" => "6 Disability affecting mobility",
            "7" => "7 Profound complex disabilities",
            "8" => "8 Social and emotional difficulties",
            "9" => "9 Mental health difficulty",
            "10" => "10 Moderate learning difficulty",
            "11" => "11 Severe learning difficulty",
            "12" => "12 Dyslexia",
            "13" => "13 Dyscalculia",
            "14" => "14 Autism spectrum disorder",
            "15" => "15 Asperger's syndrome",
            "16" => "16 Temporary disability after illness (for example post-viral) or accident",
            "17" => "17 Speech, Language and Communication Needs",
            "93" => "93 Other physical disability",
            "94" => "94 Other specific learning difficulty (e.g. Dyspraxia)",
            "95" => "95 Other medical condition (for example epilepsy, asthma, diabetes)",
            "96" => "96 Other learning difficulty",
            "97" => "97 Other disability",
            "98" => "98 Prefer not to say",
            "99" => "99 Not provided"
        ];

        $lldd_cats = '';
        if($xml->LLDDHealthProb->__toString() == '1')
        {
            $lldd_cats = '<br><b>Categories:</b><br>';
            foreach($xml->LLDDandHealthProblem AS $LLDDandHealthProblem)
            {
                $lldd_cats .= isset($LLDDCats[$LLDDandHealthProblem->LLDDCat->__toString()]) ? $LLDDCats[$LLDDandHealthProblem->LLDDCat->__toString()] : $LLDDandHealthProblem->LLDDCat->__toString();
                $lldd_cats .= isset($LLDDandHealthProblem->PrimaryLLDD) ? ' - <b>Primary LLDD</b><br>' : '<br>';
            }
        }

        $learner_fams = '';
        foreach($xml->LearnerFAM AS $LearnerFAM)
        {
            if($LearnerFAM->LearnFAMType->__toString() == "EHC" && $LearnerFAM->LearnFAMCode->__toString() == "1")
                $learner_fams .= 'Education Health Care plan | ';
            if($LearnerFAM->LearnFAMType->__toString() == "DLA" && $LearnerFAM->LearnFAMCode->__toString() == "1")
                $learner_fams .= 'Disabled student allowance | ';
            if($LearnerFAM->LearnFAMType->__toString() == "SEN" && $LearnerFAM->LearnFAMCode->__toString() == "1")
                $learner_fams .= 'Special educational needs | ';
            if($LearnerFAM->LearnFAMType->__toString() == "HNS" && $LearnerFAM->LearnFAMCode->__toString() == "1")
                $learner_fams .= 'High needs students | ';
        }
        $learner_fams .= $learner_fams != '' ? '<br>' : '';
        if(isset($xml->ALSCost) && $xml->ALSCost->__toString() != '')
            $learner_fams .= '<b>Learner Support Cost (&#163;): </b>' . $xml->ALSCost->__toString() . '<br>';
        $MCF_dropdown = ['1' => '1 Learner is exempt from GCSE maths condition of funding due to a learning difficulty', '2' => '2 Learner is exempt from GCSE maths condition of funding as they hold an equivalent overseas qualification','3' => '3 Learner has met the GCSE maths condition of funding as they hold an approved equivalent UK qualification','4' => '4 Learner has met the GCSE maths condition of funding by undertaking or completing a valid maths GCSE or equivalent qualification at another institution','5' => '5 Learner holds a pass grade for functional skills level 2 in maths','6' => '6 Unassigned'];
        $ECF_dropdown = ['1' => '1 Learner is exempt from GCSE English condition of funding due to a learning difficulty', '2' => '2 Learner is exempt from GCSE English condition of funding as they hold an equivalent overseas qualification','3' => '3 Learner has met the GCSE English condition of funding as they hold an approved equivalent UK qualification','4' => '4 Learner has met the GCSE English condition of funding by undertaking or completing a valid English GCSE or equivalent qualification at another institution','5' => '5 Learner holds a pass grade for functional skills level 2 English','6' => '6 Unassigned'];
        foreach($xml->LearnerFAM AS $LearnerFAM)
        {
            if($LearnerFAM->LearnFAMType->__toString() == "EDF" && $LearnerFAM->LearnFAMCode->__toString() == "1")
                $learner_fams .= '1 Learner has not achieved a maths GCSE (at grade A*-C) by the end of year 11<br>';
            if($LearnerFAM->LearnFAMType->__toString() == "EDF" && $LearnerFAM->LearnFAMCode->__toString() == "2")
                $learner_fams .= '2 Learner has not achieved an English GCSE (at grade A*-C) by the end of year 11<br>';
            if($LearnerFAM->LearnFAMType->__toString() == "MCF" && isset($MCF_dropdown[$LearnerFAM->LearnFAMCode->__toString()]))
                $learner_fams .= $MCF_dropdown[$LearnerFAM->LearnFAMCode->__toString()] . '<br>';
            if($LearnerFAM->LearnFAMType->__toString() == "ECF" && isset($ECF_dropdown[$LearnerFAM->LearnFAMCode->__toString()]))
                $learner_fams .= $ECF_dropdown[$LearnerFAM->LearnFAMCode->__toString()] . '<br>';
        }
	if($contract_year < 2021)
        {
        	$PriorAttain_dropdown = DAO::getLookupTable($link,"SELECT distinct PriorAttain, CONCAT(PriorAttain, ' ', PriorAttainDesc) FROM lis201415.ilr_priorattain;");
        	if(isset($xml->PriorAttain) && isset($PriorAttain_dropdown[$xml->PriorAttain->__toString()]))
           		$learner_fams .= '<b>Prior Attainment: </b>' . $PriorAttain_dropdown[$xml->PriorAttain->__toString()] . '<br>';
	}
        if(isset($xml->PlanLearnHours) && $xml->PlanLearnHours->__toString() != '')
            $learner_fams .= '<b>Planned learning hours: </b>' . $xml->PlanLearnHours->__toString() . '<br>';
        if(isset($xml->PlanEEPHours) && $xml->PlanEEPHours->__toString() != '')
            $learner_fams .= '<b>Planned employability, enrichment and pastoral hours: </b>' . $xml->PlanEEPHours->__toString() . '<br>';
        if(isset($xml->Accom) && $xml->Accom->__toString() != '')
            $learner_fams .= 'Learner is living away from home in accommodation owned or managed by the provider<br>';
        $LSR_dropdown = ['36' => '36 Care to Learn', '55' => '55 16-19 Bursary Fund - learner is a member of a vulnerable group', '56' => '56 16-19 Bursary Fund - learner has been awarded a discretionary bursary', '57' => '57 Residential support', '58' => '58 19+ Hardship (SFA or Advanced Learner Loan funded learners only)', '59' => '59 20+ Childcare (SFA or Advanced Learner Loan funded learners only)', '60' => '60 19+ Residential Access Fund (SFA or Advanced Learner Loan funded learners only)', '61' => '61 ESF funded learner receiving childcare support', '62' => '62 Unassigned', '63' => '63 Unassigned', '64' => '64 Unassigned', '65' => '65 Unassigned'];
        $lsr = '';
        foreach($xml->LearnerFAM AS $LearnerFAM)
        {
            if($LearnerFAM->LearnFAMType->__toString() == "LSR" && isset($LSR_dropdown[$LearnerFAM->LearnFAMCode->__toString()]))
                $lsr .= $LSR_dropdown[$LearnerFAM->LearnFAMCode->__toString()] . ' | ';
        }
        if($lsr != '')
            $learner_fams .= '<b>Learner Support Reasons:</b><br>'.$lsr.'<br>';
        $nlm = '';
        $NLM_dropdown = [
            '17' => '17 Learner migrated as part of provider merger',
            '18' => '18 Learner moved as a result of Minimum Contract Level',
            '21' => '21 Unassigned',
            '22' => '22 Unassigned',
            '23' => '23 Unassigned',
            '24' => '24 Unassigned',
            '25' => '25 Unassigned'
        ];
        foreach($xml->LearnerFAM AS $LearnerFAM)
        {
            if($LearnerFAM->LearnFAMType->__toString() == "NLM" && isset($NLM_dropdown[$LearnerFAM->LearnFAMCode->__toString()]))
                $nlm .= $NLM_dropdown[$LearnerFAM->LearnFAMCode->__toString()] . ' | ';
        }
        if($nlm != '')
            $learner_fams .= '<b>National learner monitoring:</b><br>'.$nlm.'<br>';
        if(isset($xml->EngGrade) && $xml->EngGrade->__toString() != '')
            $learner_fams .= '<b>GCSE English Qualification Grade: </b>' . $xml->EngGrade->__toString() . '<br>';
        if(isset($xml->MathGrade) && $xml->MathGrade->__toString() != '')
            $learner_fams .= '<b>GCSE Maths Qualification Grade: </b>' . $xml->MathGrade->__toString() . '<br>';

        $FME_dropdown = ['1'=> '1: 14-15 year old learner is eligible for free meals', '2'=> '2: 16-19 year old learner is eligible for and in receipt of free meals'];
        foreach($xml->LearnerFAM AS $LearnerFAM)
        {
            if($LearnerFAM->LearnFAMType->__toString() == "FME" && isset($FME_dropdown[$LearnerFAM->LearnFAMCode->__toString()]))
                $learner_fams .= '<b>Free meals eligibility: </b>' . $FME_dropdown[$LearnerFAM->LearnFAMCode->__toString()];
        }
        $ppe = '';
        $PPE_dropdown = [
            '1'=> '1 Learner is eligible for Service Child premium',
            '2'=> '2 Learner is eligible for Adopted from Care premium',
            '3'=> '3 Unassigned',
            '4'=> '4 Unassigned',
            '5'=> '5 Unassigned'
        ];
        foreach($xml->LearnerFAM AS $LearnerFAM)
        {
            if($LearnerFAM->LearnFAMType->__toString() == "PPE" && isset($PPE_dropdown[$LearnerFAM->LearnFAMCode->__toString()]))
                $ppe .= $PPE_dropdown[$LearnerFAM->LearnFAMCode->__toString()] . ' | ';
        }
        if($ppe != '')
            $learner_fams .= '<br>'.$ppe.'<br>';

        if($learner_fams != '')
        {
            $learner_fams = <<<LEARNER_FAM
<table border="1" cellpadding="5"  style="width: 100%;">
	<tr style="background-color:#d3d3d3;"><th>Learner Funding and Monitoring</th></tr>
	<tr>
		<td>$learner_fams</td>
	</tr>
</table>
LEARNER_FAM;
        }

        $ProvSpecLearnMonOccurA = '';
        $ProvSpecLearnMonOccurB = '';
        foreach($xml->ProviderSpecLearnerMonitoring AS $ProviderSpecLearnerMonitoring)
        {
            if($ProviderSpecLearnerMonitoring->ProvSpecLearnMonOccur->__toString() == "A")
                $ProvSpecLearnMonOccurA = $ProviderSpecLearnerMonitoring->ProvSpecLearnMon->__toString();
            if($ProviderSpecLearnerMonitoring->ProvSpecLearnMonOccur->__toString() == "B")
                $ProvSpecLearnMonOccurB = $ProviderSpecLearnerMonitoring->ProvSpecLearnMon->__toString();

        }

        $ethnicity_desc = DAO::getSingleValue($link,"SELECT CONCAT(Ethnicity, ' ', Ethnicity_Desc) FROM lis201415.ilr_ethnicity WHERE Ethnicity = '{$xml->Ethnicity}';");

        $html = <<<FIRST_PAGE
<table border="1" cellpadding="5"  style="width: 100%;">
	<tr style="background-color:#d3d3d3;"><th colspan="8">Identifiers</th></tr>
	<tr>
		<th align="left">Learner Reference:</th><td>{$xml->LearnRefNumber}</td>
		<th align="left">Previous Learner Reference:</th><td>000000021721{$xml->PrevLearnRefNumber}</td>
		<th align="left">ULN:</th><td>{$xml->ULN}</td>
		<th align="left">Campus Identifier:</th><td>{$xml->CampId}</td>
	</tr>
	<tr>
		<th align="left">UKPRN:</th><td>{$contract->ukprn}</td>
		<th align="left">UKPRN in previous year:</th><td>{$xml->PrevUKPRN}</td>
		<th align="left">Pre Merger UKPRN:</th><td colspan="3">{$xml->PMUKPRN}</td>
	</tr>
</table>

<table border="0" style="width: 100%;">
	<tr>
		<td valign="top">
			<table border="1" cellpadding="6" width="100%;">
				<tr style="background-color:#d3d3d3;"><th colspan="2">Learner Information</th></tr>
				<tr><th align="left">Family Name:</th><td>{$xml->FamilyName}</td></tr>
				<tr><th align="left">Given Names:</th><td>{$xml->GivenNames}</td></tr>
				<tr><th align="left">Date of Birth:</th><td>{$dob}</td></tr>
				<tr><th align="left">Gender:</th><td>{$xml->Sex}</td></tr>
				<tr><th align="left">Current Address:</th><td>{$address}</td></tr>
				<tr><th align="left">CurrentPostcode:</th><td>{$current_postcode}</td></tr>
				<tr><th align="left" style="font-size: 9px">Postcode prior enrolment:</th><td>{$postcode_prior_enrolment}</td></tr>
				<tr><th align="left">Email:</th><td>{$email}</td></tr>
				<tr><th align="left">Telephone:</th><td>{$tel_number}</td></tr>
				<tr><th align="left">National Insurance:</th><td>{$xml->NINumber}</td></tr>
				<tr><th align="left">Ethnicity:</th><td style="font-size: 9px">{$ethnicity_desc}</td></tr>
				<tr><th align="left" style="font-size: 9px">Prov. Specified Learner monitoring A:</th><td>{$ProvSpecLearnMonOccurA}</td></tr>
				<tr><th align="left" style="font-size: 9px">Prov. Specified Learner monitoring B:</th><td>{$ProvSpecLearnMonOccurB}</td></tr>
			</table>
		</td>
		<td valign="top">
			<table border="1" cellpadding="5"  style="width: 100%;">
				<tr style="background-color:#d3d3d3;"><th>LLDD & Health Problem</th></tr>
				<tr>
					<td>{$LLDDHealthProb}{$lldd_cats}</td>
				</tr>

			</table>
			$learner_fams
		</td>


	</tr>
</table>
FIRST_PAGE;

        $mpdf->WriteHTML($html);

        $mpdf->AddPage('L');
        $mpdf->SetHTMLFooter($this->getFooter($link, $tr_id, false));

        $rui_1 = 'No';
        $rui_2 = 'No';
        $pmc_1 = 'No';
        $pmc_2 = 'No';
        $pmc_3 = 'No';
        foreach($xml->ContactPreference AS $ContactPreference)
        {
            if(isset($ContactPreference->ContPrefType) && $ContactPreference->ContPrefType->__toString() == 'RUI')
                $rui_1 = (isset($ContactPreference->ContPrefCode) && $ContactPreference->ContPrefCode->__toString() == '1') ? 'Yes' : $rui_1;
            if(isset($ContactPreference->ContPrefType) && $ContactPreference->ContPrefType->__toString() == 'RUI')
                $rui_2 = (isset($ContactPreference->ContPrefCode) && $ContactPreference->ContPrefCode->__toString() == '2') ? 'Yes' : $rui_2;
            if(isset($ContactPreference->ContPrefType) && $ContactPreference->ContPrefType->__toString() == 'PMC')
                $pmc_1 = (isset($ContactPreference->ContPrefCode) && $ContactPreference->ContPrefCode->__toString() == '1') ? 'Yes' : $pmc_1;
            if(isset($ContactPreference->ContPrefType) && $ContactPreference->ContPrefType->__toString() == 'PMC')
                $pmc_2 = (isset($ContactPreference->ContPrefCode) && $ContactPreference->ContPrefCode->__toString() == '2') ? 'Yes' : $pmc_2;
            if(isset($ContactPreference->ContPrefType) && $ContactPreference->ContPrefType->__toString() == 'PMC')
                $pmc_3 = (isset($ContactPreference->ContPrefCode) && $ContactPreference->ContPrefCode->__toString() == '3') ? 'Yes' : $pmc_3;
        }
        $rui = <<<RUI
<table border="1" cellpadding="5"  style="width: 100%;">
	<tr style="background-color:#d3d3d3;"><th colspan="2" align="center">Contact Preferences</th></tr>
	<tr>
		<th valign="top" align="left" style="width: 60%;">Learner wishes to be contacted about courses or learning opportunities</th>
		<td style="width: 20%;">{$rui_1}</td>
	</tr>
	<tr>
		<th valign="top" align="left">Learner wishes to be contacted for surveys and research</th>
		<td>{$rui_2}</td>
	</tr>
	<tr>
		<th valign="top" align="left">Learner wishes to be contacted by post</th>
		<td>{$pmc_1}</td>
	</tr>
	<tr>
		<th valign="top" align="left">Learner wishes to be contacted by phone</th>
		<td>{$pmc_2}</td>
	</tr>
	<tr>
		<th valign="top" align="left">Learner wishes to be contacted by email</th>
		<td>{$pmc_3}</td>
	</tr>
</table>
RUI;

        $LearnerHEHtml = '';
        foreach($xml->LearnerHE AS $LearnerHE)
        {
            $LearnerHEHtml = '<tr>';
            $LearnerHEHtml .= '<th align="left" style="width: 60%;">UCAS Personal Identifier</th>';
            $LearnerHEHtml .= isset($LearnerHE->UCASPERID) ? '<td>' . $LearnerHE->UCASPERID->__toString() . '</td>' : '<td></td>';
            $LearnerHEHtml .= '</tr>';
            $LearnerHEHtml .= '<tr>';
            $LearnerHEHtml .= '<th align="left" style="width: 60%;">Term Time Accommodation</th>';
            $LearnerHEHtml .= isset($LearnerHE->TTACCOM) ? '<td>' . $LearnerHE->TTACCOM->__toString() . '</td>' : '<td></td>';
            $LearnerHEHtml .= '</tr>';
            foreach($LearnerHE->LearnerHEFinancialSupport AS $LearnerHEFinancialSupport)
            {
                if(isset($LearnerHEFinancialSupport->FINTYPE) && $LearnerHEFinancialSupport->FINTYPE->__toString() == '1')
                {
                    $LearnerHEHtml .= '<tr>';
                    $LearnerHEHtml .= '<th align="left" style="width: 60%;">Financial Support Amount - Cash</th>';
                    $LearnerHEHtml .= isset($LearnerHEFinancialSupport->FINAMOUNT) ? '<td>' . $LearnerHEFinancialSupport->FINAMOUNT->__toString() . '</td>' : '<td></td>';
                    $LearnerHEHtml .= '</tr>';
                }
                if(isset($LearnerHEFinancialSupport->FINTYPE) && $LearnerHEFinancialSupport->FINTYPE->__toString() == '2')
                {
                    $LearnerHEHtml .= '<tr>';
                    $LearnerHEHtml .= '<th align="left" style="width: 60%;">Financial Support Amount - Near Cash</th>';
                    $LearnerHEHtml .= isset($LearnerHEFinancialSupport->FINAMOUNT) ? '<td>' . $LearnerHEFinancialSupport->FINAMOUNT->__toString() . '</td>' : '<td></td>';
                    $LearnerHEHtml .= '</tr>';
                }
                if(isset($LearnerHEFinancialSupport->FINTYPE) && $LearnerHEFinancialSupport->FINTYPE->__toString() == '3')
                {
                    $LearnerHEHtml .= '<tr>';
                    $LearnerHEHtml .= '<th align="left" style="width: 60%;">Financial Support Amount - Accommodation Discounts</th>';
                    $LearnerHEHtml .= isset($LearnerHEFinancialSupport->FINAMOUNT) ? '<td>' . $LearnerHEFinancialSupport->FINAMOUNT->__toString() . '</td>' : '<td></td>';
                    $LearnerHEHtml .= '</tr>';
                }
                if(isset($LearnerHEFinancialSupport->FINTYPE) && $LearnerHEFinancialSupport->FINTYPE->__toString() == '4')
                {
                    $LearnerHEHtml .= '<tr>';
                    $LearnerHEHtml .= '<th align="left" style="width: 60%;">Financial Support Amount - Other</th>';
                    $LearnerHEHtml .= isset($LearnerHEFinancialSupport->FINAMOUNT) ? '<td>' . $LearnerHEFinancialSupport->FINAMOUNT->__toString() . '</td>' : '<td></td>';
                    $LearnerHEHtml .= '</tr>';
                }
            }
        }

        if($LearnerHEHtml != '')
            $LearnerHEHtml = '<table border="1" cellpadding="5"  style="width: 100%;"><tr style="background-color:#d3d3d3;"><th colspan="2" align="center">Learner HE Information</th></tr>' . $LearnerHEHtml . '</table>';

	// 2021 change - addition of PriorAttain as an element with sub elements
        if($contract_year > 2020 && isset($xml->PriorAttain) && isset($xml->PriorAttain->PriorLevel))
        {
            $PriorAttain_dropdown = DAO::getLookupTable($link,"SELECT distinct PriorAttain, CONCAT(PriorAttain, ' ', PriorAttainDesc) FROM central.lookup_ilr_prior_attainment;");
            $LearnerHEHtml .= '<table border="1" cellpadding="5"  style="width: 100%;">';
            $LearnerHEHtml .= '<tr style="background-color:#d3d3d3;"><th colspan="2" align="center">Prior Attainment</th></tr>';
            $LearnerHEHtml .= '<tr><th valign="top" align="left" style="width: 50%;">Level</th><th valign="top" align="left" style="width: 50%;">Date Level applies</th></tr>';
            foreach($xml->PriorAttain AS $PriorAttainElement)
            {
                $LearnerHEHtml .= '<tr>';
                $LearnerHEHtml .= isset($PriorAttain_dropdown[$PriorAttainElement->PriorLevel->__toString()]) ?
                    '<th valign="top" align="left">' . $PriorAttain_dropdown[$PriorAttainElement->PriorLevel->__toString()] . '</th>' :
                    '<th valign="top" align="left">' . $PriorAttainElement->PriorLevel->__toString() . '</th>';
                $LearnerHEHtml .= '<th valign="top" align="left">' . Date::toShort($PriorAttainElement->DateLevelApp->__toString()) . '</th>';
                $LearnerHEHtml .= '</tr>';
            }
            $LearnerHEHtml .= '</table>';
        }

        $mpdf->WriteHTML($rui.$LearnerHEHtml);

        $mpdf->AddPage('L');
        $mpdf->SetHTMLFooter($this->getFooter($link, $tr_id, false));

        $LOE_dropdown = ['1' => '1 Learner has been employed for up to 3 months', '2' => '2 Learner has been employed for 4-6 months', '3' => '3 Learner has been employed for 7-12 months', '4' => '4 Learner has been employed for more than 12 months'];
        $EII_dropdown = [
            '2'=> '2 Learner is employed for less than 16 hours per week (Valid till 31/07/2018)',
            '3'=> '3 Learner is employed for 16-19 hours per week (Valid till 31/07/2018)',
            '4'=> '4 Learner is employed for 20 hours or more per week (Valid till 31/07/2018)',
            '5'=> '5 Learner is employed for 0 to 10 hours per week',
            '6'=> '6 Learner is employed for 11 to 20 hours per week',
            '7'=> '7 Learner is employed for 21 to 30 hours per week',
            '8'=> '8 Learner is employed for 31+ hours per week',
        ];
        $LOU_dropdown = ['1'=> '1 Learner has been unemployed for less than 6 months', '2'=> '2 Learner has been unemployed for 6-11 months', '3'=> '3 Learner has been unemployed for 12-23 months', '4'=> '4 Learner has been unemployed for 24-35 months', '5'=> '5 Learner has been unemployed for over 36 months'];
        $BSI_dropdown = ['1'=> '1 Learner is in receipt of JSA','2'=> '2 Learner is in receipt of ESA WRAG','3'=> '3 Learner is in receipt of another state benefit','4'=> '4 Learner is in receipt of Universal Credit','5'=> '5 Unassigned','6'=> '6 Unassigned','7'=> '7 Unassigned','8'=> '8 Unassigned','9'=> '9 Unassigned','10'=> '10 Unassigned'];

        $EmpStatsList = DAO::getLookupTable($link,"SELECT distinct EmpStatCode AS id, CONCAT(EmpStatCode, ' ', EmpStaCode_Desc) AS description FROM lis201415.ilr_empstatcode;");
        $employments = '';
        $first_employment = true;
        $second_employment = false;
        foreach($xml->LearnerEmploymentStatus AS $LearnerEmploymentStatus)
        {
            $emp_stat = isset($EmpStatsList[$LearnerEmploymentStatus->EmpStat->__toString()]) ? $EmpStatsList[$LearnerEmploymentStatus->EmpStat->__toString()] : $LearnerEmploymentStatus->EmpStat->__toString();
            $emp_stat_date = Date::toShort($LearnerEmploymentStatus->DateEmpStatApp->__toString());
            $emp_id = $LearnerEmploymentStatus->EmpId->__toString();
            $emp_id .= $emp_id != '' ?  '  ' . DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE edrs = '{$emp_id}'") : '';
            $agreement_id = $LearnerEmploymentStatus->AgreeId->__toString();
            $employments .= "<table border='1' cellpadding='5'  style='width: 100%;'>";
            if($first_employment)
            {
                $employments .= '<tr style="background-color:#d3d3d3;"><th colspan="4" align="center">Prior to enrolment</th></tr>';
                $first_employment = false;
            }
            if($second_employment)
            {
                $employments .= '<tr style="background-color:#d3d3d3;"><th colspan="4" align="center">Since enrolment</th></tr>';
                $second_employment = false;
            }
            $employments .= "<tr>";
            $employments .= "<th style='width: 20%;'>Employment Status:</th><td>{$emp_stat}</td>";
            $employments .= "<th style='width: 30%;'>Date Employment Status Applies:</th><td>{$emp_stat_date}</td>";
            $employments .= "</tr>";
            $employments .= "<tr>";
            $employments .= "<th style='width: 20%;'>Employer Identifier:</th><td>{$emp_id}</td>";
            if($contract_year < 2020)
                $employments .= "<th style='width: 30%;'>Agreement Identifier:</th><td>{$agreement_id}</td>";
            else
                $employments .= "<th style='width: 30%;'></th><td></td>";
            $employments .= "</tr>";

	    $oet_list = [
                '1' => 'Learner has been made redundant',
                '2' => 'Small or Medium Employer',
                '3' => 'Employer has changed',
                '4' => 'Unassigned',
            ];
            $employments .= "<tr><td colspan='4' align='left'>";
            foreach($LearnerEmploymentStatus->EmploymentStatusMonitoring AS $EmploymentStatusMonitoring)
            {
                if(isset($EmploymentStatusMonitoring->ESMType) && $EmploymentStatusMonitoring->ESMType->__toString() == "SEI")
                {
                    $employments .= "<br>Is the learner self employed?: ";
                    $employments .= (isset($EmploymentStatusMonitoring->ESMCode) && $EmploymentStatusMonitoring->ESMCode->__toString() == "1") ? "<b>Yes</b>" : "<b>No</b>";
                }
                if(isset($EmploymentStatusMonitoring->ESMType) && $EmploymentStatusMonitoring->ESMType->__toString() == "PEI")
                {
                    $employments .= "<br>Was the learner in full time education or training prior to enrolment?: ";
                    $employments .= (isset($EmploymentStatusMonitoring->ESMCode) && $EmploymentStatusMonitoring->ESMCode->__toString() == "1") ? "<b>Yes</b>" : "<b>No</b>";
                }
                if(isset($EmploymentStatusMonitoring->ESMType) && $EmploymentStatusMonitoring->ESMType->__toString() == "SEM")
                {
                    $employments .= "<br>Is this a small employer?: ";
                    $employments .= (isset($EmploymentStatusMonitoring->ESMCode) && $EmploymentStatusMonitoring->ESMCode->__toString() == "1") ? "<b>Yes</b>" : "<b>No</b>";
                }
		if(isset($EmploymentStatusMonitoring->ESMType) && $EmploymentStatusMonitoring->ESMType->__toString() == "OET")
                {
                    $employments .= "<br>Other Employment Type: ";
                    $employments .= (isset($EmploymentStatusMonitoring->ESMCode) && isset($oet_list[$EmploymentStatusMonitoring->ESMCode->__toString()]) ) ? "<b>" . $oet_list[$EmploymentStatusMonitoring->ESMCode->__toString()] . "</b>" : "";
                }
                if(isset($EmploymentStatusMonitoring->ESMType) && $EmploymentStatusMonitoring->ESMType->__toString() == "LOE")
                {
                    $employments .= "<br>Length of Employment: ";
                    $employments .= (isset($EmploymentStatusMonitoring->ESMCode) && $LOE_dropdown[$EmploymentStatusMonitoring->ESMCode->__toString()]) ? "<b>{$LOE_dropdown[$EmploymentStatusMonitoring->ESMCode->__toString()]}</b>" : "<b>N/A</b>";
                }
                if(isset($EmploymentStatusMonitoring->ESMType) && $EmploymentStatusMonitoring->ESMType->__toString() == "EII")
                {
                    $employments .= "<br>Employment Intensity Indicator: ";
                    $employments .= (isset($EmploymentStatusMonitoring->ESMCode) && $EII_dropdown[$EmploymentStatusMonitoring->ESMCode->__toString()]) ? "<b>{$EII_dropdown[$EmploymentStatusMonitoring->ESMCode->__toString()]}</b>" : "<b>N/A</b>";
                }
                if(isset($EmploymentStatusMonitoring->ESMType) && $EmploymentStatusMonitoring->ESMType->__toString() == "LOU")
                {
                    $employments .= "<br>Length Of Unemployment: ";
                    $employments .= (isset($EmploymentStatusMonitoring->ESMCode) && $LOU_dropdown[$EmploymentStatusMonitoring->ESMCode->__toString()]) ? "<b>{$LOU_dropdown[$EmploymentStatusMonitoring->ESMCode->__toString()]}</b>" : "<b>N/A</b>";
                }
                if(isset($EmploymentStatusMonitoring->ESMType) && $EmploymentStatusMonitoring->ESMType->__toString() == "BSI")
                {
                    $employments .= "<br>Benefit Status Indicator: ";
                    $employments .= (isset($EmploymentStatusMonitoring->ESMCode) && $BSI_dropdown[$EmploymentStatusMonitoring->ESMCode->__toString()]) ? "<b>{$BSI_dropdown[$EmploymentStatusMonitoring->ESMCode->__toString()]}</b>" : "<b>N/A</b>";
                }
            }
            $employments .= "</td></tr>";

            $employments .= "</table><br>";

            $second_employment = true;
        }

        $employment_page = <<<EMPLOYMENT
<table border="1" cellpadding="5"  style="width: 100%;">
	<tr style="background-color:#d3d3d3;"><th>Employment Information</th></tr>
</table><br>
$employments
EMPLOYMENT;

        $mpdf->WriteHTML($employment_page);

        $aimtype_dropdown = DAO::getLookupTable($link, "SELECT DISTINCT AimType AS id, CONCAT(AimType, ' ',AimType_Desc) AS description FROM lis201415.ilr_aimtype;");
        $FundModel_dropdown = [
            '10' => '10 Community Learning',
            '25' => '25 16-19 EFA',
            '35' => '35 Adult Skills',
            '36' => '36 Apprenticeships (from 1 May 2017)',
            '70' => '70 ESF',
            '81' => '81 Other Adult',
            '82' => '82 Other 16-19',
            '99' => '99 Non-funded',
            '37' => 'Skills Bootcamps',
        ];
        $ProgType_dropdown = [
            '2' => '2 Advanced Level Apprenticeship',
            '3' => '3 Intermediate Level Apprenticeship',
            '20' => '20 Higher Level Apprenticeship (Level 4)',
            '21' => '21 Higher Level Apprenticeship (Level 5)',
            '22' => '22 Higher Level Apprenticeship (Level 6)',
            '23' => '23 Higher Level Apprenticeship (Level 7+)',
            '24' => '24 Traineeship',
            '25' => '25 Apprenticeship standard',
            '30' => 'T Level transition programme',
            '31' => 'T Level programme',
            '32' => 'Skills Bootcamps',
            '33' => 'Combined Authorities',
        ];
        $Outcome_dropdown = [
            '1' => '1 Achieved',
            '2' => '2 Partial achievement',
            '3' => '3 No achievement',
            '6' => '6 Achieved but uncashed (AS-levels only)',
            '7' => '7 Achieved and cashed (AS-levels only))',
            '8' => '8 Learning activities are complete but the outcome is not yet known))'
        ];

        $ALB_dropdown = ['1'=> '1 Advanced Learner Loan Bursary funding - rate 1','2'=> '2 Advanced Learner Loan Bursary funding - rate 2','3'=> '3 Advanced Learner Loan Bursary funding - rate 3'];
        $ACT_dropdown = ['1'=> '1 Apprenticeship funded through a contract for services with the employer','2'=> '2 Apprenticeship funded through a contract for services with the Skills Funding Agency'];

        foreach($xml->LearningDelivery AS $LearningDelivery)
        {
            $mpdf->AddPage('L');
            $mpdf->SetHTMLFooter($this->getFooter($link, $tr_id, false));

            $restart = "No";
            $hhs_present = false;
            $lsf_records = [];
            $alb_records = [];
            $act_records = [];
            foreach($LearningDelivery->LearningDeliveryFAM AS $LearningDeliveryFAM)
            {
                if(isset($LearningDeliveryFAM->LearnDelFAMType) && $LearningDeliveryFAM->LearnDelFAMType->__toString() == "HHS")
                    $hhs_present = true;
                if(isset($LearningDeliveryFAM->LearnDelFAMType) && $LearningDeliveryFAM->LearnDelFAMType->__toString() == "RES")
                    $restart = $LearningDeliveryFAM->LearnDelFAMCode->__toString() == "1" ? "Yes" : "No";
                if(isset($LearningDeliveryFAM->LearnDelFAMType) && $LearningDeliveryFAM->LearnDelFAMType->__toString() == "LSF")
                {
                    $lsf = new stdClass();
                    $lsf->detail = (isset($LearningDeliveryFAM->LearnDelFAMCode) && $LearningDeliveryFAM->LearnDelFAMCode->__toString() == '1') ?
                        "1 Learner is in receipt of learning support funding for this learning aim | " : "";
                    $lsf->date_from = isset($LearningDeliveryFAM->LearnDelFAMDateFrom) ?
                        "From: " . Date::toShort($LearningDeliveryFAM->LearnDelFAMDateFrom->__toString()) . " | " : "";
                    $lsf->date_to = isset($LearningDeliveryFAM->LearnDelFAMDateTo) ?
                        "To: " . Date::toShort($LearningDeliveryFAM->LearnDelFAMDateTo->__toString()) : "";
                    $lsf_records[] = $lsf;
                }
                if(isset($LearningDeliveryFAM->LearnDelFAMType) && $LearningDeliveryFAM->LearnDelFAMType->__toString() == "ALB")
                {
                    $alb = new stdClass();
                    $alb->detail = (isset($LearningDeliveryFAM->LearnDelFAMCode) && isset($ALB_dropdown[$LearningDeliveryFAM->LearnDelFAMCode->__toString()]) ) ?
                        "{$ALB_dropdown[$LearningDeliveryFAM->LearnDelFAMCode->__toString()]} | " : "";
                    $alb->date_from = isset($LearningDeliveryFAM->LearnDelFAMDateFrom) ?
                        "From: " . Date::toShort($LearningDeliveryFAM->LearnDelFAMDateFrom->__toString()) . " | " : "";
                    $alb->date_to = isset($LearningDeliveryFAM->LearnDelFAMDateTo) ?
                        "To: " . Date::toShort($LearningDeliveryFAM->LearnDelFAMDateTo->__toString()) : "";
                    $alb_records[] = $alb;
                }
                if(isset($LearningDeliveryFAM->LearnDelFAMType) && $LearningDeliveryFAM->LearnDelFAMType->__toString() == "ACT")
                {
                    $act = new stdClass();
                    $act->detail = (isset($LearningDeliveryFAM->LearnDelFAMCode) && isset($ACT_dropdown[$LearningDeliveryFAM->LearnDelFAMCode->__toString()]) ) ?
                        "{$ACT_dropdown[$LearningDeliveryFAM->LearnDelFAMCode->__toString()]} | " : "";
                    $act->date_from = isset($LearningDeliveryFAM->LearnDelFAMDateFrom) ?
                        "From: " . Date::toShort($LearningDeliveryFAM->LearnDelFAMDateFrom->__toString()) . " | " : "";
                    $act->date_to = isset($LearningDeliveryFAM->LearnDelFAMDateTo) ?
                        "To: " . Date::toShort($LearningDeliveryFAM->LearnDelFAMDateTo->__toString()) : "";
                    $act_records[] = $act;
                }
            }

            $delivery_pages = '';
            $aim_type = (isset($LearningDelivery->AimType) && isset($aimtype_dropdown[$LearningDelivery->AimType->__toString()])) ? $aimtype_dropdown[$LearningDelivery->AimType->__toString()] : $LearningDelivery->AimType->__toString();
            $aim_reference = $LearningDelivery->LearnAimRef->__toString();
            $delivery = '<table class="resultset" border="1" cellpadding="5" width="100%">';
            $delivery .= '<tr>';
            $delivery .= '<td valign="top"><b>Start Date:</b><br>' . Date::toShort($LearningDelivery->LearnStartDate->__toString()) . '</td>';
            $delivery .= '<td valign="top"><b>Original Learning Start Date:</b><br>' .  Date::toShort($LearningDelivery->OrigLearnStartDate->__toString()) . '</td>';
            $delivery .= '<td valign="top"><b>Planned End Date:</b><br>' .  Date::toShort($LearningDelivery->LearnPlanEndDate->__toString()) . '</td>';
            $delivery .= isset($FundModel_dropdown[$LearningDelivery->FundModel->__toString()]) ? '<td valign="top"><b>Fund Model:</b><br>' .  $FundModel_dropdown[$LearningDelivery->FundModel->__toString()] . '</td>' : '<td valign="top"><b>Fund Model:</b><br>' .  $LearningDelivery->FundModel->__toString() . '</td>';
            $delivery .= isset($ProgType_dropdown[$LearningDelivery->ProgType->__toString()]) ? '<td valign="top"><b>Programme Type:</b><br>' .  $ProgType_dropdown[$LearningDelivery->ProgType->__toString()] . '</td>' : '<td valign="top"><b>Programme Type:</b><br>' .  $LearningDelivery->FundModel->__toString() . '</td>';
            $delivery .= '</tr>';
            $delivery .= '<tr>';
            $fwrk_code = DAO::getSingleValue($link, "SELECT CONCAT(FworkCode, ' ', COALESCE(IssuingAuthorityTitle, '')) FROM lars201718.Core_LARS_Framework WHERE FworkCode = '{$LearningDelivery->FworkCode->__toString()}'");
            $pway_code = DAO::getSingleValue($link, "SELECT CONCAT(PwayCode, ' ', COALESCE(PathwayName,'')) FROM lars201718.Core_LARS_Framework WHERE PwayCode = '{$LearningDelivery->PwayCode->__toString()}' AND FworkCode = '{$LearningDelivery->FworkCode->__toString()}'");
            $delivery .= '<td valign="top"><b>Framework Code:</b><br>' . $fwrk_code . '</td>';
            $delivery .= '<td valign="top"><b>Pathway Code:</b><br>' . $pway_code . '</td>';
            $std_code = DAO::getSingleValue($link, "SELECT CONCAT(StandardCode, ' ', COALESCE(StandardName, '')) FROM lars201718.Core_LARS_Standard WHERE StandardCode = '{$LearningDelivery->StdCode->__toString()}'");
            $delivery .= '<td valign="top"><b>Standard Code:</b><br>' . $std_code . '</td>';
            $delivery .= '<td valign="top"><b>Is the aim a re-start:</b><br>' . $restart . '</td>';
            $delivery .= isset($LearningDelivery->PartnerUKPRN) ? '<td valign="top"><b>Sub-contractor or Partnership UKPRN:</b><br>' . $LearningDelivery->PartnerUKPRN->__toString() . '</td>' : '<td valign="top"><b>Sub-contractor or Partnership UKPRN:</b><br></td>';
            $delivery .= '</tr>';
            $delivery .= '<tr>';
            $delivery .= isset($LearningDelivery->PartnerUKPRN) ? '<td valign="top"><b>Delivery Location Postcode:</b><br>' . $LearningDelivery->DelLocPostCode->__toString() . '</td>' : '<td valign="top"><b>Delivery Location Postcode:</b><br></td>';
            $delivery .= isset($LearningDelivery->PriorLearnFundAdj) ? '<td valign="top"><b>Funding Adjustment For Prior Learning:</b><br>' . $LearningDelivery->PriorLearnFundAdj->__toString() . '</td>' : '<td valign="top"><b>Funding Adjustment For Prior Learning:</b><br></td>';
            $delivery .= isset($LearningDelivery->OtherFundAdj) ? '<td valign="top"><b>Other Funding Adjustment:</b><br>' . $LearningDelivery->OtherFundAdj->__toString() . '</td>' : '<td valign="top"><b>Other Funding Adjustment:</b><br></td>';
            $delivery .= isset($LearningDelivery->AddHours) ? '<td valign="top"><b>Additional Delivery Hours:</b><br>' . $LearningDelivery->AddHours->__toString() . '</td>' : '<td valign="top"><b>Additional Delivery Hours:</b><br></td>';
            $delivery .= isset($LearningDelivery->ConRefNumber) ? '<td valign="top"><b>Contract Reference Number:</b><br>' . $LearningDelivery->ConRefNumber->__toString() . '</td>' : '<td valign="top"><b>Contract Reference Number:</b><br></td>';
            $delivery .= '</tr>';
            $delivery .= '<tr>';
            $delivery .= isset($LearningDelivery->LSDPostcode) ? '<td valign="top"><b>Learning Start Date Postcode:</b><br>' . $LearningDelivery->LSDPostcode->__toString() . '</td>' : '<td valign="top"><b>Learning Start Date Postcode:</b><br></td>';
            $delivery .= isset($LearningDelivery->PHours) ? '<td valign="top"><b>Planned Hours:</b><br>' . $LearningDelivery->PHours->__toString() . '</td>' : '<td valign="top"><b>Planned Hours:</b><br></td>';
            $delivery .= isset($LearningDelivery->EPAOrgID) ? '<td colspan="2" valign="top"><b>End Point Assessment Organisation ID:</b><br>' . $LearningDelivery->EPAOrgID->__toString() . '</td>' : '<td colspan="2" valign="top"><b>End Point Assessment Organisation ID:</b><br></td>';
            $delivery .= isset($LearningDelivery->OTJActHours) ? '<td valign="top"><b>OTJ Actual Hours:</b><br>' . $LearningDelivery->OTJActHours->__toString() . '</td>' : '<td valign="top"><b>OTJ Actual Hours:</b><br></td>';
            $delivery .= '</tr>';
            $delivery .= '</table>';

            $delivery .= '<table class="resultset" border="1" cellpadding="5" width="100%">';
            $delivery .= '<tr style="background-color:#d3d3d3;"><th>Funding and Monitoring Information</th></tr>';
            foreach($lsf_records AS $_lsf_entry)
            {
                $delivery .= "<tr><td>{$_lsf_entry->detail}{$_lsf_entry->date_from}{$_lsf_entry->date_to}</td></tr>";
            }
            foreach($alb_records AS $_alb_entry)
            {
                $delivery .= "<tr><td>{$_alb_entry->detail}{$_alb_entry->date_from}{$_alb_entry->date_to}</td></tr>";
            }
            foreach($act_records AS $_act_entry)
            {
                $delivery .= "<tr><td>{$_act_entry->detail}{$_act_entry->date_from}{$_act_entry->date_to}</td></tr>";
            }

            $_d_fams_yes_no = [
                "WPL" => "Is the aim workplace learning:",
                "FLN" => "Family English, Maths or Language learning aim delivered through the Adult Skills Budget:",
                "ADL" => "Is the learner aim financed by 24+ Advanced learning loan:",
            ];
            $_d_fams = [
                "SOF" => "Source of Funding",
                "FFI" => "Full or Co-Funding Indicator",
                "EEF" => "Eligibility for Enhanced Apprenticeships Funding",
                "ASL" => "Community Learning Provision",
                "NSA" => "National Skills Academy Indicator",
            ];
            $ldm = '';
            $dam = '';
            foreach($LearningDelivery->LearningDeliveryFAM AS $LearningDeliveryFAM)
            {
                foreach($_d_fams_yes_no AS $key => $value)
                {
                    if(isset($LearningDeliveryFAM->LearnDelFAMType) && $LearningDeliveryFAM->LearnDelFAMType->__toString() == "{$key}")
                    {
                        $delivery .= (isset($LearningDeliveryFAM->LearnDelFAMCode) && $LearningDeliveryFAM->LearnDelFAMCode->__toString() == 1 ) ?
                            "<tr><td><b>{$value} </b>Yes</td></tr>" : "<tr><td><b>{$value} </b>No</td></tr>";
                    }
                }
                foreach($_d_fams AS $key => $value)
                {
                    if(isset($LearningDeliveryFAM->LearnDelFAMType) && $LearningDeliveryFAM->LearnDelFAMType->__toString() == "{$key}")
                    {
                        $delivery .= (isset($LearningDeliveryFAM->LearnDelFAMCode) && $LearningDeliveryFAM->LearnDelFAMCode->__toString() != "" ) ?
                            "<tr><td><b>{$value}: </b>" . DAO::getSingleValue($link, "SELECT CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc) FROM lis201415.ilr_learndelfamtypefamcode WHERE LearnDelFAMType = '{$key}' AND LearnDelFAMCode = '{$LearningDeliveryFAM->LearnDelFAMCode->__toString()}';") . "</td></tr>" : "";
                    }
                }
                if(isset($LearningDeliveryFAM->LearnDelFAMType) && $LearningDeliveryFAM->LearnDelFAMType->__toString() == "LDM")
                {
                    $ldm .= (isset($LearningDeliveryFAM->LearnDelFAMCode) && $LearningDeliveryFAM->LearnDelFAMCode->__toString() != "" ) ?
                        DAO::getSingleValue($link, "SELECT CONCAT(LearnDelFAMCode, ' ', LearnDelFAMCode_Desc) FROM lis201415.ilr_learndelfamtypefamcode WHERE LearnDelFAMType = 'LDM' AND LearnDelFAMCode = '{$LearningDeliveryFAM->LearnDelFAMCode->__toString()}';") . " | " : "";
                }
                if(isset($LearningDeliveryFAM->LearnDelFAMType) && $LearningDeliveryFAM->LearnDelFAMType->__toString() == "DAM")
                {
                    $dam .= (isset($LearningDeliveryFAM->LearnDelFAMCode) && $LearningDeliveryFAM->LearnDelFAMCode->__toString() != "" ) ?
                        $LearningDeliveryFAM->LearnDelFAMCode->__toString() . " | " : "";
                }

            }
            $delivery .= $ldm != '' ? "<tr><td><b>LDM: </b>{$ldm}</td></tr>" : "";
            $delivery .= $dam != '' ? "<tr><td><b>DAM: </b>{$dam}</td></tr>" : "";
            $delivery .= '</table>';

            if($hhs_present)
            {
                $delivery .= '<table class="resultset" border="1" cellpadding="5" width="100%">';
                $delivery .= '<tr style="background-color:#d3d3d3;"><th>Household Situation</th></tr>';
                $hhs = '';
                foreach($LearningDelivery->LearningDeliveryFAM AS $LearningDeliveryFAM)
                {
                    if($LearningDeliveryFAM->LearnDelFAMType->__toString() == "HHS" && $LearningDeliveryFAM->LearnDelFAMCode->__toString() == "1")
                    {
                        $hhs .= "HHS1 - No member of the household in which learner lives (including learner) is employed<br>";
                    }
                    if($LearningDeliveryFAM->LearnDelFAMType->__toString() == "HHS" && $LearningDeliveryFAM->LearnDelFAMCode->__toString() == "2")
                    {
                        $hhs .= "HHS2 - The household that learner lives in includes only one adult (aged 18 or over)<br>";
                    }
                    if($LearningDeliveryFAM->LearnDelFAMType->__toString() == "HHS" && $LearningDeliveryFAM->LearnDelFAMCode->__toString() == "3")
                    {
                        $hhs .= "HHS3 - There are one or more dependent children (aged 0-17 years or 18-24 years if full-time student or inactive) in the household<br>";
                    }
                    if($LearningDeliveryFAM->LearnDelFAMType->__toString() == "HHS" && $LearningDeliveryFAM->LearnDelFAMCode->__toString() == "99")
                    {
                        $hhs .= "HHS99 - None of these statements apply<br>";
                    }
                    if($LearningDeliveryFAM->LearnDelFAMType->__toString() == "HHS" && $LearningDeliveryFAM->LearnDelFAMCode->__toString() == "98")
                    {
                        $hhs .= "HHS98 - Learner wants to withhold this information";
                    }
                }
                $delivery .= $hhs == '' ? "<tr><td>No Household information has been entered.</td></tr>" : "<tr><td>$hhs</td></tr>";
                $delivery .= '</table>';
            }

            if(isset($LearningDelivery->ProviderSpecDeliveryMonitoring))
            {
                $delivery .= '<table class="resultset" border="1" cellpadding="5" width="100%">';
                $delivery .= '<tr style="background-color:#d3d3d3;"><th colspan="4">Provider Specified Delivery Monitoring Information</th></tr>';
                $prov_del_a = '';
                $prov_del_b = '';
                $prov_del_c = '';
                $prov_del_d = '';
                foreach($LearningDelivery->ProviderSpecDeliveryMonitoring AS $ProviderSpecDeliveryMonitoring)
                {
                    if($ProviderSpecDeliveryMonitoring->ProvSpecDelMonOccur->__toString() == "A")
                    {
                        $prov_del_a = $ProviderSpecDeliveryMonitoring->ProvSpecDelMon->__toString();
                    }
                    if($ProviderSpecDeliveryMonitoring->ProvSpecDelMonOccur->__toString() == "B")
                    {
                        $prov_del_b = $ProviderSpecDeliveryMonitoring->ProvSpecDelMon->__toString();
                    }
                    if($ProviderSpecDeliveryMonitoring->ProvSpecDelMonOccur->__toString() == "C")
                    {
                        $prov_del_c = $ProviderSpecDeliveryMonitoring->ProvSpecDelMon->__toString();
                    }
                    if($ProviderSpecDeliveryMonitoring->ProvSpecDelMonOccur->__toString() == "D")
                    {
                        $prov_del_d = $ProviderSpecDeliveryMonitoring->ProvSpecDelMon->__toString();
                    }
                }
                $delivery .= "<tr><td>A</td><td>{$prov_del_a}</td><td>B</td><td>{$prov_del_b}</td></tr>";
                $delivery .= "<tr><td>C</td><td>{$prov_del_c}</td><td>D</td><td>{$prov_del_d}</td></tr>";
                $delivery .= '</table>';
            }

            if(isset($LearningDelivery->TrailblazerApprenticeshipFinancialRecord))
            {
                $delivery .= '<table class="resultset" border="1" cellpadding="5" width="100%">';
                $delivery .= '<tr style="background-color:#d3d3d3;"><th colspan="4">Apprenticeship Financial Details</th></tr>';
                foreach($LearningDelivery->TrailblazerApprenticeshipFinancialRecord AS $TrailblazerApprenticeshipFinancialRecord)
                {
                    $record = '';
                    if(isset($TrailblazerApprenticeshipFinancialRecord->TBFinType) && $TrailblazerApprenticeshipFinancialRecord->TBFinType->__toString() != "")
                    {
                        $record .= $TrailblazerApprenticeshipFinancialRecord->TBFinType->__toString() == "TNP" ? "<b>Apprenticeship Financial Type: </b>TNP Total Negotiated Price | " : "<b>Apprenticeship Financial Type: </b>PMR Payment Record | ";
                    }
                    if(isset($TrailblazerApprenticeshipFinancialRecord->TBFinCode) && $TrailblazerApprenticeshipFinancialRecord->TBFinCode->__toString() != "")
                    {
                        $record .= "<b>Apprenticeship Financial Code: </b>{$TrailblazerApprenticeshipFinancialRecord->TBFinCode->__toString()} | ";
                    }
                    if(isset($TrailblazerApprenticeshipFinancialRecord->TBFinDate) && $TrailblazerApprenticeshipFinancialRecord->TBFinDate->__toString() != "")
                    {
                        $record .= "<b>Apprenticeship Financial Date: </b>" . Date::toShort($TrailblazerApprenticeshipFinancialRecord->TBFinDate->__toString()) . " | ";
                    }
                    if(isset($TrailblazerApprenticeshipFinancialRecord->TBFinAmount) && $TrailblazerApprenticeshipFinancialRecord->TBFinAmount->__toString() != "")
                    {
                        $record .= "<b>Apprenticeship Financial Amount: </b>{$TrailblazerApprenticeshipFinancialRecord->TBFinAmount->__toString()}";
                    }
                    $delivery .= $record != "" ? "<tr><td>{$record}</td></tr>" : "";
                }
                $delivery .= '</table>';
            }

            $delivery .= '<table class="resultset" border="1" cellpadding="5" width="100%">';
            $delivery .= '<tr style="background-color:#d3d3d3;"><th colspan="5">Learning End Information</th></tr>';
            $delivery .= '<tr>';
            $delivery .= isset($LearningDelivery->LearnActEndDate) ? '<td valign="top"><b>Learning Actual End Date:</b><br>' . Date::toShort($LearningDelivery->LearnActEndDate->__toString()) . '</td>' : '<td valign="top"><b>Learning Actual End Date:</b><br></td>';
            $delivery .= isset($LearningDelivery->AchDate) ? '<td valign="top"><b>Achievement Date:</b><br>' . Date::toShort($LearningDelivery->AchDate->__toString()) . '</td>' : '<td valign="top"><b>Achievement Date:</b><br></td>';
            $delivery .= isset($LearningDelivery->EmpOutcome) ? '<td valign="top"><b>Employment Outcome:</b><br>' . DAO::getSingleValue($link,"SELECT CONCAT(EmpOutcome, ' ', EmpOutcome_Desc) FROM lis201415.ilr_empoutcome WHERE EmpOutcome = '{$LearningDelivery->EmpOutcome->__toString()}';") . '</td>' : '<td valign="top"><b>Employment Outcome:</b><br></td>';
            $delivery .= isset($LearningDelivery->WithdrawReason) ? '<td valign="top"><b>Withdrawal Reason:</b><br>' . DAO::getSingleValue($link,"SELECT CONCAT(WithdrawReason, ' ', WithdrawReason_Desc) FROM lis201415.ilr_withdrawreason WHERE WithdrawReason = '{$LearningDelivery->WithdrawReason->__toString()}';") . '</td>' : '<td valign="top"><b>Withdrawal Reason:</b><br></td>';
            $delivery .= isset($LearningDelivery->OutGrade) ? '<td valign="top"><b>Outcome Grade:</b><br>' . DAO::getSingleValue($link,"SELECT CONCAT(OutGrade, ' ', OutGrade_Desc) FROM lis201415.ilr_outgrade WHERE OutGrade = '{$LearningDelivery->OutGrade->__toString()}';") . '</td>' : '<td valign="top"><b>Outcome Grade:</b><br></td>';
            $delivery .= '</tr>';
            $delivery .= '<tr>';
            $delivery .= isset($LearningDelivery->CompStatus) ? '<td valign="top" colspan="3"><b>Completion Status:</b><br>' . DAO::getSingleValue($link,"SELECT CONCAT(CompStatus, ' ', CompStatus_Desc) FROM lis201415.ilr_compstatus WHERE CompStatus = '{$LearningDelivery->CompStatus->__toString()}';") . '</td>' : '<td valign="top" colspan="3"><b>Completion Status:</b><br></td>';
            $delivery .= isset($Outcome_dropdown[$LearningDelivery->Outcome->__toString()]) ? '<td valign="top" colspan="2"><b>Outcome:</b><br>' . $Outcome_dropdown[$LearningDelivery->Outcome->__toString()] . '</td>' : '<td valign="top" colspan="2"><b>Outcome:</b><br></td>';
            $delivery .= '</tr>';
            $delivery .= '</table>';

            $delivery_pages .= <<<DELIVERIES
<table border="1" cellpadding="5" width="100%">
	<tr style="background-color:#d3d3d3;"><th>Learning Information: [Aim Type: {$aim_type}, Aim Reference: {$aim_reference}]</th></tr>

</table>
$delivery
DELIVERIES;

            $mpdf->WriteHTML($delivery_pages);
            //$mpdf->AddPage('L');
            //$mpdf->SetHTMLFooter($this->getFooter($link, $tr_id, false));
        }

        /*
                $deliveries_pages = <<<DELIVERIES
        $delivery_pages
        DELIVERIES;
                $mpdf->WriteHTML($deliveries_pages);
        */
        $filename = date('d-m-Y').'_ILR_export.pdf';

        $mpdf->Output($filename, 'D');



    }

    public function getHeader(PDO $link, $contract_year)
    {
        $contract_year = (int) $contract_year;
        $contract_year = $contract_year . '/' . ++$contract_year;

        $filename = SystemConfig::getEntityValue($link, "logo");
        $filename = $filename ? $filename : 'perspective.png';

        $header = <<<HTML
<table border="0" style="width: 100%;">
	<tr>
		<td style="width: 30%;"><img src="images/logos/$filename" style="max-width:150px;height:60px" alt="failed to load" /></td>
		<td style="width: 50%;"><h2>Individual Learner Record $contract_year</h2></td>
		<td style="width: 20%;"><img src="images/logos/SUNlogo.png" style="width:150px;height:50px" alt="failed to load" /></td>
	</tr>
</table>
HTML;
        return $header;
    }

    public function getFooter(PDO $link, $id, $first_page = true)
    {
        $username = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM tr WHERE tr.id = '{$id}'");

	$username_ = DAO::getSingleValue($link, "SELECT username FROM tr WHERE tr.id = '{$id}'");
        $learner_signature = '__________________________________';
        if(file_exists(Repository::getRoot().'/'.$username_.'/learner_signature.png'))
        {
            $learner_signature = "<img src='" . Repository::getRoot()."/".$username_."/learner_signature.png" . "' />";
        }
	// check if this learner came from onboarding
        if(in_array(DB_NAME, ["am_crackerjack", "am_demo", "am_ela", "am_sd_demo"]))
        {
            $ob_ids = DAO::getObject($link, "SELECT ob_tr.`id`, ob_tr.`ob_learner_id` FROM ob_tr WHERE ob_tr.`sunesis_tr_id` = '{$id}';");
            if(isset($ob_ids->id))
            {
                if(file_exists(Repository::getRoot() . "/OnboardingModule/learners/{$ob_ids->ob_learner_id}/{$ob_ids->id}/onboarding/learner_sign_image.png" ))
                {
                    $learner_signature = "<img src='" . Repository::getRoot() . "/OnboardingModule/learners/{$ob_ids->ob_learner_id}/{$ob_ids->id}/onboarding/learner_sign_image.png" . "' />";
                }
            }
        }

        $sunesis_stamp = md5('ghost'.date('d/m/Y').$id);

        $date = date("d/m/Y H:i");
        $footer = <<<HTML
<div style="float: left;">
	<table width="100%" style="font-size: 8px;">
	   <tr>
		    <td align="left">
		        <b>How we use your data: &nbsp; </b>This privacy notice is issued by the Education and Skills Funding Agency (ESFA), on behalf of the Secretary of State for the Depart-ment of Education (DfE).
		        It is to inform learners how their personal information will be used by the DfE, the ESFA (an executive agency of the DfE) and any successor bodies to these organisations.
		        For the purposes of relevant data protection legislation, the DfE is the data controller for personal data processed by the ESFA. Your personal information is used by the DfE to exercise its functions and to meet its statutory responsibilities, including under the Apprenticeships, Skills, Children and Learning Act 2009 and to create and maintain a unique learner number (ULN) and a personal learning record (PLR). Your information will be securely destroyed after it is no longer required for these purposes.
		        Your information may be used for education, training, employment and well-being related purposes, including for research. The DfE and the English European Social Fund (ESF) Managing Authority (or agents acting on their behalf) may contact you in order for them to carry out research and evaluation to inform the effective-ness of training.
		        Your information may also be shared with other third parties for the above purposes, but only where the law allows it and the sharing is in compliance with data protection legislation.
		        <br>Further information about use of and access to your personal data, details of organisation with whom we regularly share data, information about how long we retain your data, and how to change your consent to being contacted, please visit: https://www.gov.uk/government/publications/efsa-privacy-notice

	        </td>
	        <td>
	            <img src="images/EFSA.png" style="width:80px;height:80px;"/>
	        </td>
	   </tr>
	</table>
	<table width = "100%" style="font-size: 8px;">
		<tr>
			<td width = "15%" align="left">$date</td>
			<td width = "15%" align="left">$username</td>
			<td width = "20%" align="left">Print ID: $sunesis_stamp</td>
			<td width = "35%" align="right" valign="top">Learner Sign: {$learner_signature}</td>
			<td width = "15%" align="right">Page {PAGENO} of {nb}</td>
		</tr>
	</table>
</div>
HTML;
        if(!$first_page)
            $footer = <<<HEREDOC
<div>
	<table width = "100%" style="font-size: 8px;">
		<tr>
			<td width = "25%" align="left">$date</td>
			<td width = "25%" align="left">$username</td>
			<td width = "25%" align="left">Print ID: $sunesis_stamp</td>
			<td width = "25%" align="right">Page {PAGENO} of {nb}</td>
		</tr>
	</table>
</div>
HEREDOC;

        return $footer;
    }
}