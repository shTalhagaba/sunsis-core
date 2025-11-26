<?php
class save_assessor_review_formv2 implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        DAO::transaction_start($link);
        try
        {
            if($_POST['source']==2)
            {
                $form_learner = new AssessorReviewFormLearner();
                $form_learner->populate($_POST);
                $form_learner->save($link);
            }
            elseif($_POST['source']==3)
            {
                $form_employer = new AssessorReviewFormEmployer();
                $form_employer->populate($_POST);
                $form_employer->save($link);
            }
            else
            {
                $form_assessor1 = new AssessorReviewFormAssessor1();
                $form_assessor2 = new AssessorReviewFormAssessor2();
                $form_assessor3 = new AssessorReviewFormAssessor3();
                $form_assessor4 = new AssessorReviewFormAssessor4();
                if(isset($_POST['progress']))
                    $_POST['progress'] = Text::utf8_to_latin1($_POST['progress']);
                $form_assessor1->populate($_POST);
                $form_assessor2->populate($_POST);
                $form_assessor3->populate($_POST);
                $form_assessor4->populate($_POST);
                if(isset($_POST['english_exempt']))
                    $form_assessor4->english_exempt=1;
                else
                    $form_assessor4->english_exempt=0;
                if(isset($_POST['math_exempt']))
                    $form_assessor4->math_exempt=1;
                else
                    $form_assessor4->math_exempt=0;
                if(isset($_POST['ict_exempt']))
                    $form_assessor4->ict_exempt=1;
                else
                    $form_assessor4->ict_exempt=0;
                if(isset($_POST['present']))
                    $form_assessor4->present=1;
                else
                    $form_assessor4->present=0;
                $form_assessor1->save($link);
                $form_assessor2->save($link);
                $form_assessor3->save($link);
                $form_assessor4->save($link);


            }

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        // Auto email
        if(DB_NAME=='am_baltic')
        {
            if($_POST['source']==1)
            {
                $sql="SELECT assessor_review_forms_assessor4.* FROM
            assessor_review_forms_assessor4
            LEFT JOIN assessor_review_forms_learner ON assessor_review_forms_learner.`review_id` = assessor_review_forms_assessor4.`review_id`
            WHERE assessor_review_forms_assessor4.review_id IN (SELECT id FROM assessor_review WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND signature_assessor_font IS NOT NULL AND signature_learner_font IS NULL
            AND assessor_review_forms_assessor4.review_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form Emailed to Learner');";

                $st = $link->query($sql);
                if($st)
                {
                    while($row = $st->fetch())
                    {
                        $review_id = $row['review_id'];
                        $tr_id = DAO::getSingleValue($link, "select tr_id from assessor_review where id = '$review_id'");
                        $source=2;
                        $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
                        $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                        $mailtolearner = $training_record->learner_work_email; //Mailto here
                        $client = DB_NAME;
                        $client = str_replace("am_","",$client);
                        $client = str_replace("_","-",$client);
                        $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");
                        $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");

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

                        $subject = "Baltic Review Form - Initial";
                        $success1 = Emailer::notification_email_review_auto($mailtolearner, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                        DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form Emailed to Learner','Review',now(),NULL)");
                    }
                }
            }
            if($_POST['source']==2)
            {
                    $sql="SELECT assessor_review_forms_learner.* FROM
                assessor_review_forms_learner
                LEFT JOIN assessor_review_forms_employer ON assessor_review_forms_learner.`review_id` = assessor_review_forms_employer.`review_id`
                WHERE assessor_review_forms_learner.review_id IN (SELECT id FROM assessor_review WHERE tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND signature_learner_font IS NOT NULL
                AND signature_employer_font IS NULL
                AND assessor_review_forms_learner.review_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form Emailed to Employer')";

                $st = $link->query($sql);
                if($st)
                {
                    while($row = $st->fetch())
                    {
                        $review_id = $row['review_id'];
                        $tr_id = DAO::getSingleValue($link, "select tr_id from assessor_review where id = '$review_id'");
                        $source=3;
                        $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
                        $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                        $mailtolearner = $training_record->learner_work_email; //Mailto here
                        $client = DB_NAME;
                        $client = str_replace("am_","",$client);
                        $client = str_replace("_","-",$client);
                        $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");
                        $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");

                        $manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                        $mailtoemployer = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                        $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'"));


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

                        $subject = "Baltic Review Form - Initial";
                        $success1 = Emailer::notification_email_review_auto($mailtoemployer, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                        DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form Emailed to Employer','Review',now(),NULL)");
                    }
                }
            }

        }


        if(isset($_POST['next_contact']) and $_POST['next_contact']!='')
        {
            $tr_id = $_POST['tr_id'];
            $rd = Date::toMySQL($_POST['review_date']);
            $rd = ($rd=='')?"NULL":"'".$rd."'";
            $md = Date::toMySQL($_POST['next_contact']);
            $review_id = $_POST['review_id'];
            $new_review_id = DAO::getSingleValue($link, "select id from assessor_review where tr_id = '$tr_id' and id>'$review_id' limit 0,1");
            DAO::execute($link,"update assessor_review set due_date = '$md' where id = '$new_review_id'");
            DAO::execute($link,"update assessor_review set meeting_date = $rd where id = '$review_id'");
        }

        if(isset($form_learner))
            $vo = $form_learner;
        elseif(isset($form_employer))
            $vo = $form_employer;
        else
            $vo = $form_assessor1;

        if(IS_AJAX)
        {
            header("Content-Type: text/plain");
            echo $vo->review_id;
        }
        elseif(isset($_SESSION['user']->type))
        {
            http_redirect('do.php?_action=read_training_record&id='.$vo->tr_id);
        }
        else
        {
            echo "Saved";
        }
    }

}
?>



