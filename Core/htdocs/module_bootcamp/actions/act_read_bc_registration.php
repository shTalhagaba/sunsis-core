<?php
class read_bc_registration implements IAction
{
	public function execute(PDO $link)
	{
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
        if($id == '')
        {
            throw new Exception("Missing querystring argument: id");
        }

        $registration = Registration::loadFromDatabase($link, $id);
        if( is_null($registration) )
        {
            throw new Exception("Invalid id");
        }

        if($subaction == 'export_pdf')
        {
            $this->exportPdf($link, $registration);
            exit;
        }

        $_SESSION['bc']->add($link, "do.php?_action=read_bc_registration&id=" . $registration->id, "View Applicant");

        $selectedRuis = explode(',', $registration->RUI);
        $selectedPmcs = explode(',', $registration->PMC);
        $selectedHearUs = explode(',', $registration->hear_us);


        $learner = null;
        if($registration->entity_type == 'User')
        {
            $learner = User::loadFromDatabaseById($link, $registration->entity_id);
        }

        $compliance = DAO::getObject($link, "SELECT * FROM registration_compliance WHERE registration_compliance.registration_id = '{$registration->id}' ORDER BY id DESC LIMIT 1 ");

        include('tpl_read_bc_registration.php');
    }

    private function exportPdf(PDO $link, Registration $registration)
    {
        include_once("./MPDF57/mpdf.php");

        $mpdf=new mPDF('','A4','10');

        $mpdf->setAutoBottomMargin = 'stretch';

        $sunesis_stamp = md5('ghost'.date('d/m/Y').$registration->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);
        $date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
    <div>
        <table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
            <tr>
                <td width = "35%" align="left" style="font-size: 10px">Registration: {$registration->firstnames} {$registration->surname}</td>
                <td width = "35%" align="left" style="font-size: 10px">Printed on $date</td>
                <td width = "35%" align="right" style="font-size: 10px">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
            </tr>
        </table>
    </div>
HEREDOC;

        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        $gender = $registration->gender == "F" ? "Female" : $registration->gender;
        $gender = $registration->gender == "M" ? "Male" : $gender;
        $dob = Date::to($registration->dob, Date::MEDIUM);
        $ethnicity = DAO::getSingleValue($link,"SELECT Ethnicity_Desc FROM lis201213.ilr_ethnicity WHERE Ethnicity = '{$registration->ethnicity}';");
        $address = $registration->home_address_line_1;
        $address .= $registration->home_address_line_2 != '' ? '<br>' . $registration->home_address_line_2 : '';
        $address .= $registration->home_address_line_3 != '' ? '<br>' . $registration->home_address_line_3 : '';
        $address .= $registration->home_address_line_4 != '' ? '<br>' . $registration->home_address_line_4 : '';
        $currentlyCaring = $registration->currently_caring == '1' ? 'Yes' : 'No';
        $hhsList = LookupHelper::getListHhs();
        $hhs = isset($hhsList[$registration->hhs]) ? $hhsList[$registration->hhs] : '';

        echo <<<HTML
<div style="text-align: left;">
    <h2><strong>Applicant Record</strong></h2>
</div>
<br>
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Personal Details</strong></h4></th></tr>
        <tr><th>Title:</th><td>$registration->learner_title</td></tr>
        <tr><th>Name:</th><td>$registration->firstnames $registration->surname</td></tr>
        <tr><th>Gender:</th><td>$gender</td></tr>
        <tr><th>Date of Birth:</th><td>$dob</td></tr>
        <tr><th>Ethnicity:</th><td>$ethnicity</td></tr>
        <tr><th>National Insurance:</th><td>$registration->ni</td></tr>
        <tr><th>Personal Email:</th><td>$registration->home_email</td></tr>
        <tr><th>Telephone/Mobile:</th><td>$registration->home_telephone / $registration->home_mobile</td></tr>
        <tr><th>Address:</th><td>$address</td></tr>
        <tr><th>Postcode:</th><td>$registration->home_postcode</td></tr>
        <tr><th>Is learner currently caring for children or other adults?:</th><td>$currentlyCaring</td></tr>
        <tr><th>Household Situation:</th><td>$hhs</td></tr>
    </table>
</div>
<br>

HTML;

        echo <<<HTML
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th style="color: #000; background-color: #d2d6de !important"><h4><strong>Emergency Contacts</strong></h4></th></tr>
        <tr>
            <td>
                $registration->em_con_title1 $registration->em_con_name1 <br>
                $registration->em_con_rel1 <br>
                $registration->em_con_tel1 <br>
                $registration->em_con_mob1 <br>
                $registration->em_con_email1 <br>
            </td>
        </tr>
        <tr>
            <td>
                $registration->em_con_title2 $registration->em_con_name2 <br>
                $registration->em_con_rel2 <br>
                $registration->em_con_tel2 <br>
                $registration->em_con_mob2 <br>
                $registration->em_con_email2 <br>
            </td>
        </tr>
    </table>
</div>
<br>

HTML;

        $confidentialInterview = $registration->confidential_interview == '1' ? 'Yes' : 'No';
        echo <<<HTML
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>LLDD Information</strong></h4></th></tr>
        <tr><th>Does learner consider him/herself to have a learning difficulty, health problem or disability?</th><td>{$registration->getLlddDescription()}</td></tr>
        <tr><th>LLDD Categories</th><td>{$registration->getLlddCatDescription('<br>')}</td></tr>
        <tr><th>Primary LLDD Category</th><td>{$registration->getPrimaryLlddDescription()}</td></tr>
        <tr><th>Learner would like to benefit from a confidential interview?</th><td>{$confidentialInterview}</td></tr>
    </table>
</div>
<br>

HTML;

        echo <<<HTML
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Prior Attainment</strong></h4></th></tr>
        <tr><th>Learner considers his/her Prior Attainment Level to be</th><td>{$registration->getPriorAttainmentDescription()}</td></tr>
        <tr><th>Subject for level 6 qualification or higher if completed</th><td>{$registration->getLevel6SubjectDescription()}</td></tr>
    </table>
</div>
<br>
<br>

HTML;


        echo <<<HTML
<div>
    <table border="1" style="width: 100%;" cellpadding="6">
        <tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Employment Status</strong></h4></th></tr>
        <tr><td colspan="2">{$registration->getEmploymentStatusDescription()}</td></tr>

HTML;

    if($registration->employment_status == '10')
    {
        $SEI = $registration->SEI == '1' ? 'Yes' : 'No';
        echo <<<HTML
        <tr>
            <th>Learner is self employed</th>
            <td>{$SEI}</td>
        </tr>
        <tr>
            <th>Employer Name</th>
            <td>{$registration->emp_status_employer}</td>
        </tr>
        <tr>
            <th>Employer Phone Number</th>
            <td>{$registration->emp_status_employer_tel}</td>
        </tr>
        <tr>
            <th>Employer Contact Name</th>
            <td>{$registration->employer_contact_name}</td>
        </tr>
        <tr>
            <th>Employer Contact Email</th>
            <td>{$registration->employer_contact_email}</td>
        </tr>
        <tr>
            <th>Employer/Workplace Postcode</th>
            <td>{$registration->workplace_postcode}</td>
        </tr>
        <tr>
            <th>Current Job Title</th>
            <td>{$registration->current_job_title}</td>
        </tr>
        <tr>
            <th>Industry/Sector of your current occupation</th>
            <td>{$registration->current_occupation}</td>
        </tr>
        <tr>
            <th>How long learner was employed</th>
            <td>{$registration->getLoeDescription()}</td>
        </tr>
        <tr>
            <th>Hours per week</th>
            <td>{$registration->getEiiDescription()}</td>
        </tr>
        <tr>
            <th>Current salary</th>
            <td>{$registration->current_salary}</td>
        </tr>
        <tr>
            <th>Is learner attending this bootcamp via current employer</th>
            <td>{$registration->viaCurrentEmployerDescription()}</td>
        </tr>
        <tr>
            <th>Does learner plan to work alongside the bootcamp?</th>
            <td>{$registration->planToWorkAlongsideDescription()}</td>
        </tr>

HTML;
    
    }

    if($registration->employment_status == '11' || $registration->employment_status == '12')
    {
        $PEI = $registration->PEI == '1' ? 'Yes' : 'No';
        echo <<<HTML
        <tr>
            <th>How long learner was un-employed before start of this course:</th>
            <td>{$registration->getLouDescription()}</td>
        </tr>
        <tr>
            <th>Did learner receive any of the benefits</th>
            <td>{$registration->getBsiCatDescription('<br>')}</td>
        </tr>
        <tr>
            <th>Was learner in Full Time Education or Training prior to start of this course?</th>
            <td>{$PEI}</td>
        </tr>

HTML;
    
    }
        echo <<<HTML
    </table>
</div>
<br>

HTML;

        $selectedRuis = explode(',', $registration->RUI);
        $selectedPmcs = explode(',', $registration->PMC);
        $selectedHearUs = explode(',', $registration->hear_us);

        
        echo '<div class="panel-body fieldValue" style="margin-bottom: 5px;">';
        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Contact Prefernces</strong></h4></th></tr>';
        echo '<tr><td colspan="2">Learner agrees to be contacted for other purposes as follows:</td></tr>';
        echo '<tr><td>About courses or learning opportunities</td>';
        echo '<td>' . (in_array(1, $selectedRuis) ? 'Yes' : 'No') . '</td>';
        echo '</tr>';
        echo '<tr><td>For research and evaluation purposes</td>';
        echo '<td>' . (in_array(2, $selectedRuis) ? 'Yes' : 'No') . '</td>';
        echo '</tr>';
        echo '<tr><td>By post</td>';
        echo '<td>' . (in_array(1, $selectedPmcs) ? 'Yes' : 'No') . '</td>';
        echo '</tr>';
        echo '<tr><td>By phone</td>';
        echo '<td>' . (in_array(2, $selectedPmcs) ? 'Yes' : 'No') . '</td>';
        echo '</tr>';
        echo '<tr><td>By email</td>';
        echo '<td>' . (in_array(3, $selectedPmcs) ? 'Yes' : 'No') . '</td>';
        echo '</tr></table></div><br>';
        
        echo '<div class="panel-body fieldValue" style="margin-bottom: 5px;">';
        echo '<table border="1" style="width: 100%;" cellpadding="6">';
        echo '<tr><th colspan="2" style="color: #000; background-color: #d2d6de !important"><h4><strong>Contact and Marketing Information</strong></h4></th></tr>';
        echo '<tr><td>How did learner hear about us?</td>';
        echo '<td>' . $registration->getHearUsDescription('<br>') . '</td>';
        echo '</tr>';
        echo '</tr></table></div><br>';

        $signDate = Date::toShort($registration->learner_sign_date);

        $signature_parts = explode('&', $registration->learner_sign);
        if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
        {
            if(substr($signature_parts[0], 0, 5) == 'title')
            {
                $title = explode('=', $signature_parts[0]);
                $font = explode('=', $signature_parts[1]);
                $size = explode('=', $signature_parts[2]);
            }
            elseif(substr($signature_parts[1], 0, 5) == 'title')
            {
                $title = explode('=', $signature_parts[1]);
                $font = explode('=', $signature_parts[2]);
                $size = explode('=', $signature_parts[3]);
            }
            else
            {
                return;
            }
            $sign_image_file = sys_get_temp_dir() . '/' . md5(time().$registration->id).'.png';
            $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
            imagepng($signature, $sign_image_file, 0);
            echo '<img src="' . $sign_image_file . '" style="border: 2px solid;border-radius: 15px;" /><br>';
            echo 'Sign Date: ' . $signDate;
        }
        

        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();

        $mpdf->WriteHTML($html);

        $mpdf->Output('RegistrationExport.pdf', 'D');
    }
}