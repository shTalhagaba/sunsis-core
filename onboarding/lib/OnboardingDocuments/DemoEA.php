<?php


class DemoEA 
{

    public static function generatePdf(PDO $link, EmployerAgreement $agreement)
    {
        $employer = Employer::loadFromDatabase($link, $agreement->employer_id);
        $location = $employer->getMainLocation($link);

        $_f = Repository::getRoot() . '/employers/' . $employer->id . '/EmployerAgreement_ID_' . $agreement->id . '.pdf';
        // if(is_file($_f))
        // {
        //     header("Content-type: application/pdf");
        //     header('Content-Disposition: attachment; filename="' . 'EmployerAgreement_ID_' . $agreement->id . '.pdf"');
        //     if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
        //     {
        //         header('Pragma: public');
        //         header('Cache-Control: max-age=0');
        //     }
        //     readfile($_f);
        //     exit;
        // }

        $employer_rep = OrganisationContact::loadFromDatabase($link, $agreement->employer_rep);
        $tp_rep = User::loadFromDatabaseById($link, $agreement->tp_rep);

        $tp = TrainingProvider::loadFromDatabase($link, $tp_rep->employer_id);
        $tp_location = $tp->getMainLocation($link);

        $logo = DAO::getSingleValue($link, "SELECT provider_logo FROM organisations WHERE id = '{$tp->id}'");
        if($logo == '')
            $logo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');

        if(!is_null($agreement->provider_sign))
        {
            $emp_directory = Repository::getRoot() . '/employers/' . $agreement->employer_id . '/signatures/';
            if(!is_dir($emp_directory))
            {
                mkdir("$emp_directory", 0777, true);
            }
            $tp_signature_file = $emp_directory . '/tp_sign_image.png';
            if(!is_file($tp_signature_file))
            {
                $signature_parts = explode('&', $agreement->provider_sign);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $f_size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $f_size[1]);
                    imagepng($signature, $tp_signature_file, 0);
                }
            }
        }
        if(!is_null($agreement->employer_sign))
        {
            $emp_directory = Repository::getRoot() . '/employers/' . $agreement->employer_id . '/signatures/';
            if(!is_dir($emp_directory))
            {
                mkdir("$emp_directory", 0777, true);
            }
            $tp_signature_file = $emp_directory . '/emp_sign_image.png';
            if(!is_file($tp_signature_file))
            {
                $signature_parts = explode('&', $agreement->employer_sign);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $f_size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $f_size[1]);
                    imagepng($signature, $tp_signature_file, 0);
                }
            }
        }

        $mpdf = new \Mpdf\Mpdf(['format' => 'Legal', 'default_font_size' => 10]);
        $mpdf->setAutoBottomMargin = 'stretch';

        $sunesis_stamp = md5('ghost'.date('d/m/Y').$agreement->id);
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

        $header = <<<HEADER
<div style="text-align: right;">
    <img width="50px;" height="50px;" class="img-responsive" src="$logo" />
</div>

HEADER;

        $mpdf->SetHTMLHeader($header);

        $first_pages = <<<HTML
<div style="text-align: center;">
    <p><br><br><br><br><br><br><br><br><br></p>
    <h1>
        Apprenticeship Standard <br><br>
        Contract for Services Agreement <br><br>
        Relating <br><br>
        To Apprenticeship Training for <br><br>
        Non-Levy Funded Employer <br><br><br>
        Version 4 <br>
    </h1>
</div>
HTML;
                    $mpdf->WriteHTML($first_pages);
                    $mpdf->addPage();
            
                    $first_pages = <<<HTML
<div style="text-align: center;">
    <p><br><br><br><br><br><br><br><br><br></p>
    <h1>
        (1) [$tp->legal_name] <br>
        (the TRAINING PROVIDER) <br><br>
        and <br><br>
        (2) [$employer->legal_name] <br>
        (the EMPLOYER) <br><br>
        EDRS Number: $employer->edrs <br><br>
    </h1>

    <p>----------------------------------------------------------------------------------------------</p>
    <p><h2>FRAMEWORK SERVICES AGREEMENT</h2></p>
    <br>
    <p>relating to Apprenticeship Training for non-Levy Funded Employers</p>
    <p>----------------------------------------------------------------------------------------------</p>
    <br>
    Version 4 <br><br>

    <h3>
        LAST REVIEWED: SEPTEMBER 2021 <br><br>
        NEXT REVIEW: SEPTEMBER 2022
    </h3>

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
        <tr><th style="color: #000; background-color: #d2d6de !important">AGREEMENT NUMBER (EDRS):</th><td>{$employer->edrs}</td></tr>
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
        if( $agreement->funding_type == 'L' )
        {
            $funding_type = 'Levy (DAS Account)';
        }
        elseif( $agreement->funding_type == 'CO' )
        {
            $funding_type = 'Co-Investment';
        }
        elseif( $agreement->funding_type == 'LG' )
        {
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
        $admin_service = $agreement->admin_service == 1 ? '<img src="./images/check.jpg" style="width:15px; height:15px;" /> ' : '';


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

        $avg_employees_l49 = $agreement->avg_no_of_employees <= 49 ? '<img src="./images/check.jpg" style="width:15px; height:15px;" />' : '';
        $avg_employees_m49 = $agreement->avg_no_of_employees > 49 ? '<img src="./images/check.jpg" style="width:15px; height:15px;" />' : '';

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
        $emp_signature_file = $emp_directory . 'emp_sign_image.png';
        $tp_signature_file = $emp_directory . 'tp_sign_image.png';
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
            <td><img src="{$emp_signature_file}" style="border: 2px solid;border-radius: 15px;" /></td>
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

        $agreement_date = Date::toShort($agreement->provider_sign_date);
        $first_pages = <<<HTML
<div>
    <h4 style="text-align: center">
        PARTIES, COMMENCEMENT AND DURATION
    </h4><br><br><br><br><br><br>

    <table cellpadding="6" cellspacing="6" style="margin-top: 25px;">
        <tr>
            <th valign="top">1</th>
            <th valign="top" align="left" colspan="2">Date</th>
        </tr>
        <tr>
            <td valign="top"></td>    
            <td valign="top" colspan="2">The date of this Agreement is $agreement_date </td>
        </tr>
        <tr>
            <th valign="top">2</th>
            <th valign="top" align="left" colspan="2">Parties</th>
        </tr>
        <tr>
            <td valign="top"></td>    
            <td valign="top" colspan="2">
                <p>This Agreement is made as a deed between:</p><br>
                <p>
                    $tp->legal_name incorporated and registered in England and Wales with company number $tp->company_number whose registered office is at 
                    $tp_location->address_line_1 $tp_location->address_line_2 $tp_location->address_line_3 $tp_location->address_line_4 $tp_location->postcode (the Training Provider); and
                </p><br>
                <p>
                    $employer->legal_name incorporated and registered in England and Wales with company number $employer->company_number whose registered office is at 
                    $location->address_line_1 $location->address_line_2 $location->address_line_3 $location->address_line_4 $location->postcode (the Employer).
                </p><br>
                <p>each 'a Party' and together 'the Parties'.</p>
            </td>
        </tr>
        <tr>
            <th valign="top">3</th>
            <th valign="top" align="left" colspan="2">Commencement and Duration</th>
        </tr>
        <tr>
            <td valign="top">3.1</td>
            <td valign="top" align="left" colspan="2">This Agreement shall commence on the Commencement Date and shall continue, unless terminated either:</td>
        </tr>
        <tr>
            <td valign="top"></td>
            <td valign="top">3.1.1</td>
            <td valign="top" align="left">
                by one Party serving on the other not less than 30 days' notice to terminate this Agreement, such notice to expire no earlier than the first anniversary of the commencement of this Agreement or (as the case may be) any subsequent anniversary; or
            </td>
        </tr>
        <tr>
            <td valign="top"></td>
            <td valign="top">3.1.2</td>
            <td valign="top" align="left">pursuant to Schedule 2.</td>
        </tr>
    </table>
</div>
HTML;
        $mpdf->WriteHTML($first_pages);


        //$mpdf->SetImportUse();
        $pagecount = $mpdf->SetSourceFile(Repository::getRoot() . '/policies/Employer-Agreement-Terms-and-Conditions.pdf');

        for ($i = 1; $i <= ($pagecount); $i++)
        {
            $mpdf->AddPage();
            $import_page = $mpdf->ImportPage($i);
            $mpdf->UseTemplate($import_page);
        }

		$mpdf->Output('EmployerAgreement_ID_' . $agreement->id . '.pdf', 'D');

        //$mpdf->Output(Repository::getRoot() . '/employers/' . $employer->id . '/EmployerAgreement_ID_' . $agreement->id . '.pdf', 'F');
    }

}