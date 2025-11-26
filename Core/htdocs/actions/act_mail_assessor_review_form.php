<?php
class mail_assessor_review_form implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $source = isset($_REQUEST['source']) ? $_REQUEST['source'] : '';
        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

        $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);

        $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
        $from = $_SESSION['user']->work_email;

        if($source=='2')
        {
            $mailto = $training_record->home_email; //Mailto here
        }
        else
        {
            $mailto = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");
        }

        if($type=='feedback')
            $subject = 'Feedback Action Plan - ' . $training_record->firstnames . ' ' . $training_record->surname;
        else
            $subject = 'Assessment Review - ' . $training_record->firstnames . ' ' . $training_record->surname;


        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        //$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
        if(DB_NAME=='am_baltic' or DB_NAME=='am_baltic_demo')
            $headers .= 'From: Baltic Training Services <support@perspective-uk.com>' . "\r\n";
        elseif(DB_NAME=='am_hybrid')
            $headers .= 'From: Hybrid Group Apprenticeship Team <apprenticeships@hybrid-group.net>' . "\r\n";
        else
            $headers .= 'From: Perspective Limited <support@perspective-uk.com>' . "\r\n";



        //$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
        //$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
        $client = DB_NAME;
        $client = str_replace("am_","",$client);
        $client = str_replace("_","-",$client);
        $user = $_SESSION['user']->username;
        $line_manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where contact_id = '$training_record->crm_contact_id'");
        $review_date = DAO::getSingleValue($link,"select meeting_date from assessor_review where id = '$review_id'");

        if($source=='2')
        {
            $assessor_name = $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname;
            if($type=='feedback')
            {
                $message = "<html><body><br><br>Dear {$training_record->firstnames} &nbsp; {$training_record->surname}<br>Please click the link below to open completed feedback action plan. <br><br> Many Thanks <br> {$assessor_name} <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=fap_review_form&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> Please click here to open feedback action plan </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form <br><br> {$client}.sunesis.uk.net/do.php?_action=fap_review_form&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."</body></html>";
                Emailer::notification_email_review($mailto, 'apprenticeships@perspective-uk.com', $from, 'Review Form', '', $message, array());
                DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Feedback Action Plan Emailed to Learner','FAP',now(),'$user')");
                $message = "<html><body><br><br>Dear {$line_manager_name} <br>Please click the link below to open completed feedback action plan. <br><br> Many Thanks <br> {$assessor_name} <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=fap_review_form&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> Please click here to open feedback action plan </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form <br><br> {$client}.sunesis.uk.net/do.php?_action=fap_review_form&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."</body></html>";
                $success = $mailto = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");

                Emailer::notification_email_review($mailto, 'apprenticeships@perspective-uk.com', $from, 'Review Form', '', $message, array());
                DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Feedback Action Plan Emailed to Employer','FAP',now(),'$user')");
            }
            else
            {
                $success1 = false;
                $success2 = false;
                if(DB_NAME=='am_baltic')
                {
                    $old = DAO::getSingleValue($link, "select count(*) from assessor_review_forms_assessor4 where review_id = '$review_id'");
                    if($old>0)
                    {
                        $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                        <br><br>Dear {$training_record->firstnames} &nbsp; {$training_record->surname}
                        <br><br>Please click the link below to open completed review form. Please can you complete the comments section, sign, date and save.
                        <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=assessor_review_formv2&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> Please click here to open review form </a>
                        <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form
                        <br><br>{$client}.sunesis.uk.net/do.php?_action=assessor_review_formv2&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."
                        <br><br>We are required by the ESFA (Education and Skills Funding Agency) to provide evidence that progress is being made on each apprenticeship programme and the progress review is an important document that enable us to do this.
                        <br><br>Please complete and return the review within the next 24 hours. If you have any questions please contact me on 01325731056.
                        <br><br>Kind Regards,
                        <br><br>{$assessor_name}
                        <br><br><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; mso-fareast-language:EN-GB'>T<span style='color:#A6A6A6'>: </span></span></b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:gray; mso-fareast-language:EN-GB'>01325 731 056<o:p></o:p></span></p>
                        <p class=MsoNormal><a href=\"http://www.baltictraining.com/\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:EN-GB'>Website</span></a><b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:
                        EN-GB'> </span></b><b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; color:#000033;mso-fareast-language:EN-GB'>|</span></b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F; mso-fareast-language:EN-GB'> </span><a
                        href=\"https://twitter.com/baltictraining\"><span style='font-size:10.0pt; font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:EN-GB'>Twitter</span></a><b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; color:#7F7F7F;mso-fareast-language:EN-GB'> </span></b><b><span
                        style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#000033;mso-fareast-language:EN-GB'>|</span></b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:
                        EN-GB'> </span><a href=\"https://www.linkedin.com/company/baltic-training\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:EN-GB'>LinkedIn</span></a><span style='font-size:10.0pt;
                        font-family:\"Segoe UI\",\"sans-serif\";color:#000033;mso-fareast-language:EN-GB'> <b>|</b> </span><a href=\"https://www.facebook.com/BalticApprenticeships/\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;
                        mso-fareast-language:EN-GB'>Facebook</span></a><span style='font-size:10.0pt; font-family:\"Segoe UI\",\"sans-serif\";color:#000033;mso-fareast-language: EN-GB'> <b>|</b> </span><a href=\"https://www.youtube.com/user/baltictraining\"><span style='font-size: 10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:
                        EN-GB'>YouTube</span></a><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; mso-fareast-language:EN-GB'><o:p></o:p></span></p><p class=MsoNormal style='line-height:105%'><span style='font-size:10.0pt;
                        line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"'><v:shape id=\"_x0000_i1025\" type=\"#_x0000_t75\" style='width:47.25pt;height:69.75pt' o:ole=\"\"> <img src=\"https://baltic.sunesis.uk.net/images/image002.png\">
                        </v:shape><!--[if gte mso 9]><xml><o:OLEObject Type=\"Embed\" ProgID=\"PBrush\" ShapeID=\"_x0000_i1025\" DrawAspect=\"Content\" ObjectID=\"_1620489288\"></o:OLEObject></xml><![endif]--></span><span style='font-size:10.0pt;line-height:105%; font-family:\"Segoe UI\",\"sans-serif\";color:#1F497D;mso-fareast-language:
                        EN-GB'>&nbsp; <span style='mso-no-proof:yes'><v:shape id=\"Picture_x0020_6\" o:spid=\"_x0000_i1027\" type=\"#_x0000_t75\" alt=\"cid:image002.png@01D2D2E3.6A493F00\" style='width:65.25pt;height:65.25pt;visibility:visible;mso-wrap-style:square'><v:imagedata src=\"https://baltic.sunesis.uk.net/images/image003.png\"
                          o:title=\"image002.png@01D2D2E3\"/></v:shape></span>&nbsp;&nbsp;&nbsp;</span><b><span style='font-size:10.0pt; line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"'>&nbsp;&nbsp;</span></b><a href=\"https://baltictraining.com/wp-content/uploads/2017/05/ICS_SM_with_D_Feb17_cmyk.jpg\"><span
                        style='font-size:10.0pt;line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"; color:#1F497D;mso-fareast-language:EN-GB;mso-no-proof:yes;text-decoration: none;text-underline:none'><v:shape id=\"Picture_x0020_5\" o:spid=\"_x0000_i1026\"
                         type=\"#_x0000_t75\" alt=\"ICS_SM_with_D_Feb17_cmyk\" href=\"https://baltictraining.com/wp-content/uploads/2017/05/ICS_SM_with_D_Feb17_cmyk.jpg\" style='width:105pt;height:65.25pt;visibility:visible;mso-wrap-style:square'
                         o:button=\"t\"><v:fill o:detectmouseclick=\"t\"/><v:imagedata src=\"https://baltic.sunesis.uk.net/images/image004.jpg\" o:title=\"ICS_SM_with_D_Feb17_cmyk\"/></v:shape></span></a><b><span style='font-size:10.0pt;line-height:105%;
                        font-family:\"Segoe UI\",\"sans-serif\"'><o:p></o:p></span></b></p><p class=MsoNormal style='line-height:105%'><i><span style='font-size:10.0pt; line-height:105%;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;
                        mso-fareast-language:EN-GB'>This e-mail contains information which is confidential and may be privileged. Unless you are the intended addressee (or authorised to receive for the addressee) you may not use, forward, copy
                        or disclose to anyone this e-mail or any information contained in this e-mail. If you have received this e-mail in error, please advise the sender by replying to this email immediately and delete this e-mail. Any opinions
                        expressed are not necessarily those of the company. Baltic Training Services Ltd is registered in England and Wales with company number 5868493. &nbsp;As part of our quality monitoring processes we will be
                        recording telephone calls for training purposes only.</span></body></html>";
                    }
                    else
                    {
                            $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
                        <br><br>Hi {$training_record->firstnames}
                        <br><br>Thank you for attending your review on " . $review_date . ". We hope you found it useful and are looking forward to your next session!
                        <br><br>You can
                        <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> access your apprenticeship document here.</a>
                        If you agree with the content, could you please sign and save this within 24 hours?
                        <br><br>If you have any questions or need assistance, please let us know.
                        <br><br>Kind Regards,
                        <br><br>The Baltic Assessment Team
                        <br>
                        <img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                        </body></html>";
                    }

                }
                elseif(DB_NAME=='am_sd_demo')
                    $message = "<html><body><br><br>Dear {$training_record->firstnames} &nbsp; {$training_record->surname}<br>Please click the link below to open completed review form. Please can you complete the comments section, sign, date and save within 24 hours.<br><br> Many Thanks <br> {$assessor_name} <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=sd_form&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> Please click here to open review form </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form <br><br> {$client}.sunesis.uk.net/do.php?_action=sd_form&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."</body></html>";
                else
                    $message = "<html><body><br><br>Dear {$training_record->firstnames} &nbsp; {$training_record->surname}<br>Please click the link below to open completed review form. Please can you complete the comments section, sign, date and save within 48 hours.<br><br> Many Thanks <br> {$assessor_name} <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=assessor_review_formv2&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> Please click here to open review form </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form <br><br> {$client}.sunesis.uk.net/do.php?_action=assessor_review_formv2&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."</body></html>";

                if(DB_NAME=='am_sd_demo')
                {
                    $success1 = Emailer::notification_email_review($mailto, 'apprenticeships@perspective-uk.com', $from, 'Review Form', '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    if($training_record->learner_work_email!='')
                        $success2 = Emailer::notification_email_review($training_record->learner_work_email, 'apprenticeships@perspective-uk.com', $from, 'Review Form', '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form Emailed to Learner','Review',now(),'$user')");
                }
                else
                {
                    $success1 = Emailer::notification_email_review($mailto, 'apprenticeships@perspective-uk.com', $from, 'Review Form', '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    if($training_record->learner_work_email!='')
                        $success2 = Emailer::notification_email_review($training_record->learner_work_email, 'apprenticeships@perspective-uk.com', $from, 'Review Form', '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form Emailed to Learner','Review',now(),'$user')");
                }
            }
        }
        else
        {
            $assessor_name = $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname ;;
            $manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where contact_id = '$training_record->crm_contact_id'");
            $mailto = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));

            $assessor_id = $training_record->assessor;
            $mailtoassessor = DAO::getSingleValue($link, "SELECT work_email FROM users WHERE id = '$assessor_id'"); //Mailto here

            if(DB_NAME=='am_baltic')
            {
                $old = DAO::getSingleValue($link, "select count(*) from assessor_review_forms_assessor4 where review_id = '$review_id'");
                if($old>0)
                {
                    $message = "<html><body>
                    <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                    <br>Dear {$manager_name}
                    <br><br>We have recently completed our Baltic apprenticeship review with {$training_record->firstnames} &nbsp; {$training_record->surname} &nbsp; on {$actual_date}
                    <br><br>Please click the link below to open completed review form. Please can you complete the comments section, sign, date and save.
                    <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=assessor_review_formv2&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."' > Please click here to open review form </a>
                    <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form
                    <br><br>{$client}.sunesis.uk.net/do.php?_action=assessor_review_formv2&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."
                    <br><br>We are required by the ESFA (Education and Skills Funding Agency) to provide evidence that progress is being made on each apprenticeship programme and the progress review is an important document that enable us to do this.
                    <br><br>We ask that you complete this review at your earliest convenience and try to complete all future reviews within 5 working days.
                    <br><br>If you have any questions please contact me on 01325731056.
                    <br><br>Kind Regards,
                    <br><br>{$assessor_name}
                    <br><br><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; mso-fareast-language:EN-GB'>T<span style='color:#A6A6A6'>: </span></span></b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:gray; mso-fareast-language:EN-GB'>01325 731 056<o:p></o:p></span></p>
                    <p class=MsoNormal><a href=\"http://www.baltictraining.com/\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:EN-GB'>Website</span></a><b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:
                    EN-GB'> </span></b><b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; color:#000033;mso-fareast-language:EN-GB'>|</span></b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F; mso-fareast-language:EN-GB'> </span><a
                    href=\"https://twitter.com/baltictraining\"><span style='font-size:10.0pt; font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:EN-GB'>Twitter</span></a><b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; color:#7F7F7F;mso-fareast-language:EN-GB'> </span></b><b><span
                    style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#000033;mso-fareast-language:EN-GB'>|</span></b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:
                    EN-GB'> </span><a href=\"https://www.linkedin.com/company/baltic-training\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:EN-GB'>LinkedIn</span></a><span style='font-size:10.0pt;
                    font-family:\"Segoe UI\",\"sans-serif\";color:#000033;mso-fareast-language:EN-GB'> <b>|</b> </span><a href=\"https://www.facebook.com/BalticApprenticeships/\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;
                    mso-fareast-language:EN-GB'>Facebook</span></a><span style='font-size:10.0pt; font-family:\"Segoe UI\",\"sans-serif\";color:#000033;mso-fareast-language: EN-GB'> <b>|</b> </span><a href=\"https://www.youtube.com/user/baltictraining\"><span style='font-size: 10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:
                    EN-GB'>YouTube</span></a><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; mso-fareast-language:EN-GB'><o:p></o:p></span></p><p class=MsoNormal style='line-height:105%'><span style='font-size:10.0pt;
                    line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"'><v:shape id=\"_x0000_i1025\" type=\"#_x0000_t75\" style='width:47.25pt;height:69.75pt' o:ole=\"\"> <img src=\"https://baltic.sunesis.uk.net/images/image002.png\">
                    </v:shape><!--[if gte mso 9]><xml><o:OLEObject Type=\"Embed\" ProgID=\"PBrush\" ShapeID=\"_x0000_i1025\" DrawAspect=\"Content\" ObjectID=\"_1620489288\"></o:OLEObject></xml><![endif]--></span><span style='font-size:10.0pt;line-height:105%; font-family:\"Segoe UI\",\"sans-serif\";color:#1F497D;mso-fareast-language:
                    EN-GB'>&nbsp; <span style='mso-no-proof:yes'><v:shape id=\"Picture_x0020_6\" o:spid=\"_x0000_i1027\" type=\"#_x0000_t75\" alt=\"cid:image002.png@01D2D2E3.6A493F00\" style='width:65.25pt;height:65.25pt;visibility:visible;mso-wrap-style:square'><v:imagedata src=\"https://baltic.sunesis.uk.net/images/image003.png\"
                      o:title=\"image002.png@01D2D2E3\"/></v:shape></span>&nbsp;&nbsp;&nbsp;</span><b><span style='font-size:10.0pt; line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"'>&nbsp;&nbsp;</span></b><a href=\"https://baltictraining.com/wp-content/uploads/2017/05/ICS_SM_with_D_Feb17_cmyk.jpg\"><span
                    style='font-size:10.0pt;line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"; color:#1F497D;mso-fareast-language:EN-GB;mso-no-proof:yes;text-decoration: none;text-underline:none'><v:shape id=\"Picture_x0020_5\" o:spid=\"_x0000_i1026\"
                     type=\"#_x0000_t75\" alt=\"ICS_SM_with_D_Feb17_cmyk\" href=\"https://baltictraining.com/wp-content/uploads/2017/05/ICS_SM_with_D_Feb17_cmyk.jpg\" style='width:105pt;height:65.25pt;visibility:visible;mso-wrap-style:square'
                     o:button=\"t\"><v:fill o:detectmouseclick=\"t\"/><v:imagedata src=\"https://baltic.sunesis.uk.net/images/image004.jpg\" o:title=\"ICS_SM_with_D_Feb17_cmyk\"/></v:shape></span></a><b><span style='font-size:10.0pt;line-height:105%;
                    font-family:\"Segoe UI\",\"sans-serif\"'><o:p></o:p></span></b></p><p class=MsoNormal style='line-height:105%'><i><span style='font-size:10.0pt; line-height:105%;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;
                    mso-fareast-language:EN-GB'>This e-mail contains information which is confidential and may be privileged. Unless you are the intended addressee (or authorised to receive for the addressee) you may not use, forward, copy
                    or disclose to anyone this e-mail or any information contained in this e-mail. If you have received this e-mail in error, please advise the sender by replying to this email immediately and delete this e-mail. Any opinions
                    expressed are not necessarily those of the company. Baltic Training Services Ltd is registered in England and Wales with company number 5868493. &nbsp;As part of our quality monitoring processes we will be
                    recording telephone calls for training purposes only.</span></body></html>";
                }
                else
                {
                    $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM arf_introduction WHERE review_id = '$review_id'"));
                    $message = "<html><body>
                    <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                    <br>Dear {$manager_name}
                    <br><br>We have recently completed our Baltic apprenticeship review with {$training_record->firstnames} &nbsp; {$training_record->surname} &nbsp; on {$actual_date}
                    <br><br>Please click the link below to open completed review form. Please can you complete the comments section, sign, date and save.
                    <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."' > Please click here to open review form </a>
                    <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form
                    <br><br>{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."
                    <br><br>We are required by the ESFA (Education and Skills Funding Agency) to provide evidence that progress is being made on each apprenticeship programme and the progress review is an important document that enable us to do this.
                    <br><br>We ask that you complete this review at your earliest convenience and try to complete all future reviews within 5 working days.
                    <br><br>If you have any questions please contact me on 01325731056.
                    <br><br>Kind Regards,
                    <br><br>{$assessor_name}
                    <br><br><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; mso-fareast-language:EN-GB'>T<span style='color:#A6A6A6'>: </span></span></b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:gray; mso-fareast-language:EN-GB'>01325 731 056<o:p></o:p></span></p>
                    <p class=MsoNormal><a href=\"http://www.baltictraining.com/\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:EN-GB'>Website</span></a><b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:
                    EN-GB'> </span></b><b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; color:#000033;mso-fareast-language:EN-GB'>|</span></b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F; mso-fareast-language:EN-GB'> </span><a
                    href=\"https://twitter.com/baltictraining\"><span style='font-size:10.0pt; font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:EN-GB'>Twitter</span></a><b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; color:#7F7F7F;mso-fareast-language:EN-GB'> </span></b><b><span
                    style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#000033;mso-fareast-language:EN-GB'>|</span></b><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:
                    EN-GB'> </span><a href=\"https://www.linkedin.com/company/baltic-training\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:EN-GB'>LinkedIn</span></a><span style='font-size:10.0pt;
                    font-family:\"Segoe UI\",\"sans-serif\";color:#000033;mso-fareast-language:EN-GB'> <b>|</b> </span><a href=\"https://www.facebook.com/BalticApprenticeships/\"><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;
                    mso-fareast-language:EN-GB'>Facebook</span></a><span style='font-size:10.0pt; font-family:\"Segoe UI\",\"sans-serif\";color:#000033;mso-fareast-language: EN-GB'> <b>|</b> </span><a href=\"https://www.youtube.com/user/baltictraining\"><span style='font-size: 10.0pt;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;mso-fareast-language:
                    EN-GB'>YouTube</span></a><span style='font-size:10.0pt;font-family:\"Segoe UI\",\"sans-serif\"; mso-fareast-language:EN-GB'><o:p></o:p></span></p><p class=MsoNormal style='line-height:105%'><span style='font-size:10.0pt;
                    line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"'><v:shape id=\"_x0000_i1025\" type=\"#_x0000_t75\" style='width:47.25pt;height:69.75pt' o:ole=\"\"> <img src=\"https://baltic.sunesis.uk.net/images/image002.png\">
                    </v:shape><!--[if gte mso 9]><xml><o:OLEObject Type=\"Embed\" ProgID=\"PBrush\" ShapeID=\"_x0000_i1025\" DrawAspect=\"Content\" ObjectID=\"_1620489288\"></o:OLEObject></xml><![endif]--></span><span style='font-size:10.0pt;line-height:105%; font-family:\"Segoe UI\",\"sans-serif\";color:#1F497D;mso-fareast-language:
                    EN-GB'>&nbsp; <span style='mso-no-proof:yes'><v:shape id=\"Picture_x0020_6\" o:spid=\"_x0000_i1027\" type=\"#_x0000_t75\" alt=\"cid:image002.png@01D2D2E3.6A493F00\" style='width:65.25pt;height:65.25pt;visibility:visible;mso-wrap-style:square'><v:imagedata src=\"https://baltic.sunesis.uk.net/images/image003.png\"
                      o:title=\"image002.png@01D2D2E3\"/></v:shape></span>&nbsp;&nbsp;&nbsp;</span><b><span style='font-size:10.0pt; line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"'>&nbsp;&nbsp;</span></b><a href=\"https://baltictraining.com/wp-content/uploads/2017/05/ICS_SM_with_D_Feb17_cmyk.jpg\"><span
                    style='font-size:10.0pt;line-height:105%;font-family:\"Segoe UI\",\"sans-serif\"; color:#1F497D;mso-fareast-language:EN-GB;mso-no-proof:yes;text-decoration: none;text-underline:none'><v:shape id=\"Picture_x0020_5\" o:spid=\"_x0000_i1026\"
                     type=\"#_x0000_t75\" alt=\"ICS_SM_with_D_Feb17_cmyk\" href=\"https://baltictraining.com/wp-content/uploads/2017/05/ICS_SM_with_D_Feb17_cmyk.jpg\" style='width:105pt;height:65.25pt;visibility:visible;mso-wrap-style:square'
                     o:button=\"t\"><v:fill o:detectmouseclick=\"t\"/><v:imagedata src=\"https://baltic.sunesis.uk.net/images/image004.jpg\" o:title=\"ICS_SM_with_D_Feb17_cmyk\"/></v:shape></span></a><b><span style='font-size:10.0pt;line-height:105%;
                    font-family:\"Segoe UI\",\"sans-serif\"'><o:p></o:p></span></b></p><p class=MsoNormal style='line-height:105%'><i><span style='font-size:10.0pt; line-height:105%;font-family:\"Segoe UI\",\"sans-serif\";color:#7F7F7F;
                    mso-fareast-language:EN-GB'>This e-mail contains information which is confidential and may be privileged. Unless you are the intended addressee (or authorised to receive for the addressee) you may not use, forward, copy
                    or disclose to anyone this e-mail or any information contained in this e-mail. If you have received this e-mail in error, please advise the sender by replying to this email immediately and delete this e-mail. Any opinions
                    expressed are not necessarily those of the company. Baltic Training Services Ltd is registered in England and Wales with company number 5868493. &nbsp;As part of our quality monitoring processes we will be
                    recording telephone calls for training purposes only.</span></body></html>";
                }
            }
            elseif(DB_NAME=='am_sd_demo')
                $message = "<html><body><br>Dear {$manager_name}<br><br>We have recently completed our review contact with &nbsp; {$training_record->firstnames} &nbsp; {$training_record->surname}. <br><br> Please click the link below to open completed review form for Learner.  <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=sd_form&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."' > Please click here to open review form </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form. <br><br> {$client}.sunesis.uk.net/do.php?_action=sd_form&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key." <br><br> We are required by the ESFA (Education and Skills Funding Agency) to provide evidence that progress is being made on each apprenticeship programme and the progress review is an important document that enable us to do this. <br><br> We ask that you complete this review at your earliest convenience and try to complete all future reviews within 5 working days. <br><br> Many Thanks <br> {$assessor_name} </body></html>";
            else
                $message = "<html><body><br>Dear {$manager_name}<br><br>Please click the link below to open completed review form for {$training_record->firstnames} &nbsp; {$training_record->surname}. Please can you complete any parts of the review relevant to you, sign, date and save.<br><br> Many Thanks <br> {$assessor_name} <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=assessor_review_formv2&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."' > Please click here to open review form </a> <br><br> If you are unable to see the link above this line then copy the URL below in your browser to open the form <br><br> {$client}.sunesis.uk.net/do.php?_action=assessor_review_formv2&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key." </body></html>";


            Emailer::notification_email_review(($mailto.";".$mailtoassessor), 'apprenticeships@perspective-uk.com', $from, 'Review Form', '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
            //Emailer::notification_email_review(("khushnood.khan@perspective-uk.com"), 'apprenticeships@perspective-uk.com', $from, 'Review Form', '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
            DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form Emailed to Employer','Review',now(),'$user')");
            /*$success = mail($mailto,$subject,$message,$headers);
            if($success)
                DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form Emailed to Employer','Review')");
            */
        }


        echo "true";
        //http_redirect("do.php?_action=read_training_record&id=" . $tr_id);
    }
}
?>