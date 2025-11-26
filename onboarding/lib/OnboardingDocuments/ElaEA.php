<?php


class ElaEA
{
    public static function generateSign($dir_path, $sign_text)
    {
        if (!is_dir($dir_path)) {
            mkdir("$dir_path", 0777, true);
        }
        $file_name = md5(time() . rand(1, 100) . $sign_text) . '.png';
        $file_path = $dir_path . $file_name;
        $signature_parts = explode('&', $sign_text);
        if (isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2])) {
            $title = explode('=', $signature_parts[0]);
            $font = explode('=', $signature_parts[1]);
            $f_size = explode('=', $signature_parts[2]);
            $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $f_size[1]);
            imagepng($signature, $file_path, 0);
        }

        return [
            'file_name' => $file_name,
            'file_path' => $file_path,
        ];
    }
    public static function generatePdf(PDO $link, EmployerAgreement $agreement, $tr_id = '')
    {
        $employer = Employer::loadFromDatabase($link, $agreement->employer_id);
        $location_ids = explode(',', $agreement->locations);
        $location = isset($location_ids[0]) ? Location::loadFromDatabase($link, $location_ids[0]) : $employer->getMainLocation($link); // there must be at least one location

        $employer_rep = OrganisationContact::loadFromDatabase($link, $agreement->employer_rep);
        $tp_rep = User::loadFromDatabaseById($link, $agreement->tp_rep);

        $tp = TrainingProvider::loadFromDatabase($link, $tp_rep->employer_id);
        $tp_location = $tp->getMainLocation($link);

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tp->id}'");
        if ($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

        $emp_directory = Repository::getRoot() . '/employers/' . $agreement->employer_id . '/signatures/';
        $provider_signature_details = self::generateSign($emp_directory, $agreement->provider_sign);
        $employer_signature_details = self::generateSign($emp_directory, $agreement->employer_sign);

        $mpdf = new \Mpdf\Mpdf([
            'format'            => 'Legal',
            'default_font_size' => 10,
            'margin_left'       => 15,
            'margin_right'      => 15,
            'margin_top'        => 32,
        ]);
        $mpdf->setAutoBottomMargin = 'stretch';

        $sunesis_stamp = md5('ghost' . date('d/m/Y') . $agreement->id);
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
					<td width = "50%" align="left">{$date}</td>
					<td width = "50%" align="right">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

        $header = <<<HEADER
        <div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "50%" align="left"><img class="img-responsive" src="images/logos/apprenticeship.png" height="2.00cm" width="6.11cm" alt="Apprenticeship" /></td>
					<td width = "50%" align="right"><img class="img-responsive" src="images/logos/ela-training.png" height="1.50cm" width="5cm" alt="ELA Training" /></td>
				</tr>
			</table>
		</div>

HEADER;

        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);

        $agreement_date = Date::toShort($agreement->provider_sign_date);
        $agreement_date_month_year = Date::to($agreement->provider_sign_date, 'M Y');

        $first_pages = <<<HTML
<div style="text-align: center;">
    <p><br><br><br><br><br><br><br><br><br></p>
    <h3>Dated: $agreement_date_month_year</h3>
    <h1>APPRENTICESHIP TRAINING SERVICES AGREEMENT</h1>
    <h3>between</h3>
    <h1>The Equestrian Learning Academy Limited</h1>
    <h3>and</h3>
    <h1>$employer->legal_name</h1>
</div>
HTML;

        $mpdf->WriteHTML($first_pages);
        $mpdf->addPage();


        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();



        echo <<<HTML
<div style="text-align: center;">
    <p><br></p>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important">AGREEMENT NUMBER (EDRS):</th><td>{$agreement->agreement_number}</td></tr>
    </table>
</div>
HTML;
        $location_address = $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : '';
        $location_address .= $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : '';
        $location_address .= $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : '';
        $location_address .= $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : '';
        $location_address .= $location->postcode != '' ? $location->postcode . '<br>' : '';

        $tp_location_address = $tp_location->address_line_1 != '' ? $tp_location->address_line_1 . '<br>' : '';
        $tp_location_address .= $tp_location->address_line_2 != '' ? $tp_location->address_line_2 . '<br>' : '';
        $tp_location_address .= $tp_location->address_line_3 != '' ? $tp_location->address_line_3 . '<br>' : '';
        $tp_location_address .= $tp_location->address_line_4 != '' ? $tp_location->address_line_4 . '<br>' : '';
        $tp_location_address .= $tp_location->postcode != '' ? $tp_location->postcode . '<br>' : '';

        $funding_type = '';
        if ($agreement->funding_type == 'L') {
            $funding_type = 'Levy (DAS Account)';
        } elseif ($agreement->funding_type == 'CO') {
            $funding_type = 'Co-Investment';
        } elseif ($agreement->funding_type == 'LG') {
            $funding_type = 'Levy Gifted';
        }

        $employer_type = $agreement->employer_type == 'NE' ? 'New Employer' : '';
        $employer_type = $agreement->employer_type == 'EE' ? 'Existing Employer' : $employer_type;

        $employer_rep = OrganisationContact::loadFromDatabase($link, $agreement->employer_rep);

        echo <<<HTML
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">EMPLOYER DETAILS</th></tr>
        <tr><th>Company Name:</th><td class="text-blue">$employer->legal_name</td></tr>
        <tr><th>Trading As:</th><td class="text-blue">$employer->trading_name</td></tr>
        <tr><th>Company Number:</th><td class="text-blue">$employer->company_number</td></tr>
        <tr>
            <th>Employer Registered Address:</th>
            <td class="text-blue">{$location_address}</td>
        </tr>
        <tr>
            <th>Type of Employer:</th>
            <td>{$funding_type}<br>{$employer_type}</td>
        </tr>
        <tr>
            <th>Employer Representative:</th>
            <td>
                <table border="1" style="width: 100%;" cellpadding="6">
                    <tr>
                        <td>Name: $employer_rep->contact_name </td>
                    </tr>
                    <tr>
                        <td>Position:  $employer_rep->job_title </td>
                    </tr>
                    <tr>
                        <td>Email:  $employer_rep->contact_email </td>
                    </tr>
                    <tr>
                        <td>Telephone:  $employer_rep->contact_telephone </td>
                    </tr>
                    <tr>
                        <td>
                            Postal Address:<br>
                            <span class="text-blue">{$location_address}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th>Finance Contact:</th>
            <td>
                <table border="1" style="width: 100%;" cellpadding="6">
                    <tr>
                        <td>Name:  $agreement->finance_contact_name</td>
                    </tr>
                    <tr>
                        <td>Telephone:   $agreement->finance_contact_telephone</td>
                    </tr>
                    <tr>
                        <td>Email:   $agreement->finance_contact_email</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th>Levy Contact:</th>
            <td>
                <table border="1" style="width: 100%;" cellpadding="6">
                    <tr>
                        <td>Name:   $agreement->levy_contact_name</td>
                    </tr>
                    <tr>
                        <td>Telephone:    $agreement->levy_contact_telephone</td>
                    </tr>
                    <tr>
                        <td>Email:    $agreement->levy_contact_email</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
HTML;

        echo <<<HTML
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">TRAINING PROVIDER DETAILS</th></tr>
        <tr><th>Name:</th><td class="text-blue">$tp->legal_name</td></tr>
        <tr><th>Registered Address:</th><td class="text-blue">$tp->trading_name</td></tr>
        <tr><th>Registered Company Number:</th><td class="text-blue">$tp->company_number</td></tr>
        <tr><th>UKPRN:</th><td class="text-blue">$tp->ukprn</td></tr>
        <tr><th>VAT Number:</th><td class="text-blue">$tp->vat_number</td></tr>
        <tr>
            <th>Training Provider Representative:</th>
            <td>
                <table border="1" style="width: 100%;" cellpadding="6">
                    <tr>
                        <td>Name: $tp_rep->firstnames $tp_rep->surname</td>
                    </tr>
                    <tr>
                        <td>Position:  $tp_rep->job_role</td>
                    </tr>
                    <tr>
                        <td>Email:  $tp_rep->work_email</td>
                    </tr>
                    <tr>
                        <td>Telephone:  $tp_rep->work_telephone</td>
                    </tr>
                    <tr>
                        <td>Mobile:  $tp_rep->work_mobile</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th>Postal Address:</th>
            <td>$tp_location_address</td>
        </tr>
    </table>
</div>
HTML;

        $expiry_date = Date::toShort($agreement->expiry_date);
        $admin_service = $agreement->admin_service == 1 ? '<img style="width:15px; height:15px;" src="./images/check.jpg" />' : '';


        echo <<<HTML
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">SPECIFIC DETAILS</th></tr>
        <tr>
            <th>Agreement Expiry Date</th>
            <td class="text-blue">$expiry_date</td>
        </tr>
        <tr>
            <th>Schedule 1</th>
            <td>
                All standards will be detailed in the schedule 1
            </td>
        </tr>
    </table>
</div>
HTML;

        $avg_employees_l49 = $agreement->avg_no_of_employees <= 49 ? '<img style="width:15px; height:15px;" src="./images/check.jpg" />' : '';
        $avg_employees_m49 = $agreement->avg_no_of_employees > 49 ? '<img style="width:15px; height:15px;" src="./images/check.jpg" />' : '';

        echo <<<HTML
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">SMALL EMPLOYER WAIVER</th></tr>
        <tr>
            <th>
                In the 365 days before the apprentice was recruited, how many employees
                (on average) did you employ?
            </th>
            <td  class="text-blue">$agreement->avg_no_of_employees</td>
        </tr>
        <tr>
            <td colspan="2">
                <p>
                    $avg_employees_l49 &nbsp;
                    49 or fewer - may be eligible for small employer waiver (subject to availability
                    - see Small Employer Incentive section of Schedule 1)
                </p>
                <p>
                    $avg_employees_m49 &nbsp;
                    50 or more - ineligible for small employer waiver
                </p>
            </td>
        </tr>
    </table>
</div>
HTML;

        echo <<<HTML
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important">EMPLOYER BANK DETAILS</th></tr>
        <tr>
            <td colspan="2">
                <p>
                    Employers who are eligible for the &pound;1,000 16-18 Employer Incentive
                    or 19-24 or Care Leaver's Incentive as detailed on the Schedule 1 document
                    will need to provide their company bank details.
                </p>
                <p>
                    Employers who are not eligible for this incentive are not required to provide
                    their bank details.
                </p>
                <p>
                    By providing these details, I confirm that Training Provider are authorised
                    to pay the apprenticeship incentive payment, when due, into the account as
                    detialed below.
                </p>
            </td>
        </tr>
        <tr>
            <td>Name of Bank</td>
            <td class="text-blue">
                $agreement->bank_name
            </td>
        </tr>
        <tr>
            <td>Account Name</td>
            <td class="text-blue">
                $agreement->account_name
            </td>
        </tr>
        <tr>
            <td>Sort Code</td>
            <td class="text-blue">
                $agreement->sort_code
            </td>
        </tr>
        <tr>
            <td>Account Number</td>
            <td class="text-blue">
                $agreement->account_number
            </td>
        </tr>
    </table>
    <p style="margin-top: 5px;">
        This agreement is entered into on the date set out above and is made up of these
        Agreement Particulars, the Agreement Terms and the Schedules stated above.
    </p>
</div>
HTML;

        $emp_directory = Repository::getRoot() . '/employers/' . $employer->id . '/signatures/';
        $emp_signature_file = $employer_signature_details['file_path'];
        $tp_signature_file = $provider_signature_details['file_path'];
        $emp_sign_date = isset($agreement->employer_sign_date) ? Date::toShort($agreement->employer_sign_date) : '';
        $tp_sign_date = isset($agreement->provider_sign_date) ? Date::toShort($agreement->provider_sign_date) : '';

        echo <<<HTML
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="4" style="color: #000; background-color: #d2d6de !important">SIGNATURES</th></tr>
        <tr><th>&nbsp;</th><th>Name</th><th>Signature</th><th>Date</th></tr>
        <tr>
            <td>Employer</td>
            <td>{$agreement->employer_sign_name}</td>
            <td><img id="img_tp_sign" src="{$emp_signature_file}" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$emp_sign_date}</td>
        </tr>
        <tr>
            <td>Training Provider</td>
            <td>{$agreement->provider_sign_name}</td>
            <td><img id="img_tp_sign" src="{$tp_signature_file}" style="border: 2px solid;border-radius: 15px;" /></td>
            <td>{$tp_sign_date}</td>
        </tr>
    </table>
</div>
HTML;


        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();

        $mpdf->WriteHTML($html);
        $mpdf->addPage();

        $first_pages = <<<HTML
<div>
    <h4 style="text-align: center">
        PARTIES, COMMENCEMENT AND DURATION
    </h4><br><br><br><br><br><br>

    <table cellpadding="6" cellspacing="6" style="margin-top: 25px;">
        <tr>
            <th valign="top" align="left">Date</th>
        </tr>
        <tr>
            <td valign="top">The date of this Agreement is $agreement_date </td>
        </tr>
        <tr>
            <th valign="top" align="left">Parties</th>
        </tr>
        <tr>
            <td valign="top">
                <p>This Agreement is made as a deed between:</p><br>
                <p>
                    The Equestrian Learning Academy Limited incorporated and registered in England and Wales with company number $tp->company_number whose registered office is at 
                    Floor 1 Boundary House Business Centre Boston Manor Road London Greater London W7 2QE (the Training Provider); and
                </p><br>
                <p>
                    $employer->legal_name incorporated and registered in England and Wales with company number $employer->company_number whose registered office is at 
                    $location->address_line_1 $location->address_line_2 $location->address_line_3 $location->address_line_4 $location->postcode (the Employer).
                </p><br>
                <p>each 'a Party' and together 'the Parties'.</p>
            </td>
        </tr>
    </table>
</div>
HTML;
        $mpdf->WriteHTML($first_pages);

        // $mpdf->SetImportUse();
        if ($agreement->id > 494 && DB_NAME == "am_ela")
            $pagecount = $mpdf->SetSourceFile(Repository::getRoot() . '/policies/Employer-Agreement-Terms-and-Conditions 2324.pdf');
        else
            $pagecount = $mpdf->SetSourceFile(Repository::getRoot() . '/policies/Employer-Agreement-Terms-and-Conditions.pdf');

        $tplId = $mpdf->ImportPage(1);
        $mpdf->UseTemplate($tplId);

        for ($i = 1; $i <= ($pagecount); $i++) {
            $mpdf->AddPage(
                '', //$orientation=''
                '', //$condition=''
                '', //$resetpagenum=''
                '', //$pagenumstyle=''
                '', //$suppress=''
                '', //$mgl=''
                '', //$mgr=''
                '', //$mgt=''
                '', //$mgb=''
                '0', //$mgh=''
                '0' //$mgf=''
            );
            $import_page = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($import_page);
        }

        if (count($location_ids) > 1) {
            $location_details = '';
            foreach ($location_ids as $_loc_id) {
                if ($_loc_id == $location->id)
                    continue;

                $_loc = Location::loadFromDatabase($link, $_loc_id);

                $location_details .= $_loc->address_line_1 != '' ? $_loc->address_line_1 . '<br>' : '';
                $location_details .= $_loc->address_line_2 != '' ? $_loc->address_line_2 . '<br>' : '';
                $location_details .= $_loc->address_line_3 != '' ? $_loc->address_line_3 . '<br>' : '';
                $location_details .= $_loc->address_line_4 != '' ? $_loc->address_line_4 . '<br>' : '';
                $location_details .= $_loc->postcode != '' ? $_loc->postcode . '<br><hr></br>' : '';
            }

            $appendix_a = <<<HTML
<div>
    <h4 style="text-align: center">
        Appendix A
    </h4><br><br>
    $location_details        
</div>                
HTML;

            $mpdf->addPage();
            $mpdf->WriteHTML($appendix_a);
        }

        if ($tr_id != '') {
            $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
            $ob_learner = $tr->getObLearnerRecord($link);
            $framework = Framework::loadFromDatabase($link, $tr->framework_id);
            $skills_analysis = $tr->getSkillsAnalysis($link);

            $learner_info = '<p><strong>Total Negotiated Price</strong></p>';
            $learner_info .= '<table border="1" style="width: 100%;" cellpadding="6">';
            $learner_info .= '<tr><th>Employer: </th><td>' . $employer->legal_name . '</td></tr>';
            $learner_info .= '<tr><th>Type of Employer: </th><td>' . $funding_type . '</td></tr>';
            $learner_info .= '<tr><th>Apprentice Level & Standard: </th><td>' . $framework->getStandardCodeDesc($link) . '</td></tr>';
            $learner_info .= '<tr><th>Maximum funding level for the above standard: </th><td>' . $framework->getFundingBandMax($link) . '</td></tr>';
            $learner_info .= '<tr><th>Name of Apprentice: </th><td>' . $ob_learner->firstnames . ' ' . $ob_learner->surname . '</td></tr>';
            $learner_info .= '<tr><th>Anticipated Start Date: </th><td>' . Date::toShort($tr->practical_period_start_date) . '</td></tr>';
            $learner_info .= '<tr><th>Anticipated End Date for End Point Assessment: </th><td>' . Date::toShort($tr->practical_period_end_date) . '</td></tr>';
            $learner_info .= '<tr><th>Location of Training: </th><td>' . $location_address . '</td></tr>';
            $learner_info .= '<tr><th>Agreed End Point Assessment Organisation: </th><td>' . $tr->getEpaOrgName($link) . '</td></tr>';

            $learner_info .= '<tr><td colspan="2"></td></tr>';

            $learner_info .= '<tr><th align="center">Description</th><th align="center">Associated Price Per Apprentice</th></tr>';

            $tnp1_prices = is_null($skills_analysis->tnp1_fa) ? [] : json_decode($skills_analysis->tnp1_fa);
            $tnp1_costs = array_map(function ($ar) {
                return $ar->cost;
            }, $tnp1_prices);
            $tnp1_total = array_sum(array_map('floatval', $tnp1_costs));
            $tnp_rows = '';
            foreach ($tnp1_prices as $price_item) {
                $learner_info .= '<tr>';
                $learner_info .= '<th>' . $price_item->description . ' (TNP 1)</th>';
                $learner_info .= '<td>&pound;' . $price_item->cost . '</td>';
                $learner_info .= '</tr>';
            }
            $learner_info .= '<tr><th>End point Assessment</th><td>' . $tr->epa_price . '</td></tr>';
            $learner_info .= '<tr><th>Total price of Apprenticeship</th><td>' . ceil($tnp1_total + $tr->epa_price) . '</td></tr>';

            $learner_info .= '</table>';

            $tnp_total = ceil($tnp1_total + $tr->epa_price);

            $cost1 = '';
            $cost2 = '';
            $cost3 = '';
            $cost4 = '';
            $cost5 = '';

            $learner_age_sql = <<<SQL
SELECT 
    ((DATE_FORMAT('{$tr->practical_period_start_date}','%Y') - DATE_FORMAT('{$ob_learner->dob}','%Y')) - (DATE_FORMAT('{$tr->practical_period_start_date}','00-%m-%d') < DATE_FORMAT('{$ob_learner->dob}','00-%m-%d'))) AS age        
SQL;
            $learner_age = DAO::getSingleValue($link, $learner_age_sql);

            if ($employer->funding_type == 'LG' && $tr->type_of_funding == "Levy Gifted") {
                $cost5 = '&pound;' . $tnp_total;
            } elseif ($employer->funding_type == 'L') {
                $cost1 = '&pound;' . $tnp_total;
            } else {
                if (in_array($employer->code, [1, 2, 3, 6]) || $learner_age >= 19) // then show 2nd and 3rd box
                {
                    $cost2 = '&pound;' . ceil(($tnp_total * 5) / 100);
                    $cost3 = '&pound;' . ceil(($tnp_total * 95) / 100);
                } else {
                    $cost4 = '&pound;' . $tnp_total;
                }
            }

            $learner_info .= <<<HTML
<p></p>
<div style="text-align: center;">
    <table border="1" style="width: 100%;" cellpadding="6">       
	
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


            $mpdf->addPage();
            $mpdf->WriteHTML($learner_info);
        }

        $mpdf->Output('EmployerAgreement_ID_' . $agreement->id . '.pdf', 'D');

        unlink($employer_signature_details['file_path']);
        unlink($provider_signature_details['file_path']);


        //$mpdf->Output(Repository::getRoot() . '/employers/' . $employer->id . '/EmployerAgreement_ID_' . $agreement->id . '.pdf', 'F');
    }
}
