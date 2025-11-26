<?php
class save_arf_introduction implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {

        DAO::transaction_start($link);
        try
        {
            $form_learner = new ARFIntroduction();
            $form_learner->populate($_POST);

            if(!isset($_POST['smart1_achieved']))
                $form_learner->smart1_achieved = "off";
            else
                $form_learner->smart1_achieved = "on";

            if(!isset($_POST['smart2_achieved']))
                $form_learner->smart2_achieved = "off";
            else
                $form_learner->smart2_achieved = "on";

            if(!isset($_POST['smart3_achieved']))
                $form_learner->smart3_achieved = "off";
            else
                $form_learner->smart3_achieved = "on";

            if(!isset($_POST['smart4_achieved']))
                $form_learner->smart4_achieved = "off";
            else
                $form_learner->smart4_achieved = "on";

            if(!isset($_POST['smart5_achieved']))
                $form_learner->smart5_achieved = "off";
            else
                $form_learner->smart5_achieved = "on";

            $review_id = $_POST['review_id'];
            if(isset($_POST['source']) and $_POST['source']==1)
            {
                if(isset($_POST['manager_attendance']) && $_POST['manager_attendance']==1)
                    DAO::execute($link, "update assessor_review set manager_attendance = 1 where id = '$review_id'");
                else if(isset($_POST['manager_attendance']) && $_POST['manager_attendance']==2)
                    DAO::execute($link, "update assessor_review set manager_attendance = 0 where id = '$review_id'");
            }

            $form_learner->save($link);
            DAO::transaction_commit($link);

            // Employer Reference
/*            if(DB_NAME=="am_baltic_demo" and $_POST['signature_assessor_font']!='' and $_POST['signature_learner_font']!='' and $_POST['signature_employer_font']!='')
            {
                if(ARFIntroduction::isIntroductionReview($link, $_POST['review_id']))
                {
                    DAO::execute($link, "insert into employer_reference values(NULL, {$_POST['tr_id']}, NULL, NULl, NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl,1,NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl,NULl);");
                    $event_id = DAO::execute($link, "select max(id) from employer_reference where tr_id = {$_POST['tr_id']}");
                    $emailSent = DAO::getSingleValue($link, "select count(*) from employer_reference_emails where description = 'Employer Reference - Intro Template' and form_id = '$event_id'");
                    if($emailSent==0)
                    {
                        $review_id = $_POST['review_id'];
                        $tr_id = DAO::getSingleValue($link, "select tr_id from assessor_review where id = '$review_id'");
                        $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                        $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");
                        $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM assessor_review_forms_assessor1 WHERE review_id = '$review_id'");

                        $manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                        $mailtoemployer = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");

                        $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                        <br>Dear {$manager_name}
                        <br><br>Please see attached the Employer Reference template for your apprentice {$training_record->firstnames} &nbsp; {$training_record->surname}.
                        <br>The employer reference is a document that is required as part of the apprenticeship for your apprentice. This will form part of the End Point Assessment (EPA) which is carried out at the end of the apprenticeship by the awarding body's independent assessor.
                        <br><br>This reference will document specific examples of how your apprentice is showing workplace competence in a range of competencies, skills, attitudes and behaviours.
                        <br><br>It is a requirement of the apprenticeship that you as the employer provide these examples from their day-to-day work and in relation to the evidence they submit for their portfolio, which is also assessed as part of the EPA.
                        <br><br>We will ask you to complete elements of the reference document at certain stages throughout the apprenticeship, in line with your apprentice’s progress. You will be required to complete relevant sections of the reference when your apprentice reaches particular milestones in their apprenticeship in relation to workplace competence. There are three competence milestones for level 3 programmes and four competence milestones for level 4 programmes.
                        <br><br>I look forward to seeing your apprentice’s progress documented within the attached and will be available to assist you during our progress reviews.
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

                        $subject = "Employer Reference - Intro Template";
                        $success1 = Emailer::notification_email_employer_reference_intro($mailtoemployer, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                        DAO::execute($link,"insert into employer_reference_emails values(NULL,$review_id,'Employer Reference - Intro Template','Review',now(),NULL)");

                    }
                }
            }*/
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        $id = $_POST['tr_id'];
        if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic')
        {
            // Add one extra
            $need_one = DAO::getSingleValue($link, "SELECT count(*) FROM assessor_review WHERE tr_id = '$id' AND id NOT IN (SELECT review_id FROM arf_introduction WHERE signature_assessor_font IS NOT NULL);");
            if($need_one==0)
            {
                $has_last = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessor_review WHERE tr_id = '$id' AND id IN (SELECT review_id FROM arf_introduction AS a1
                   WHERE a1.`review_date` =  next_contact);
                   ");
                if($has_last==0)
                {
                    $due_date = DAO::getSingleValue($link, "SELECT MAX(next_contact) FROM arf_introduction WHERE review_id IN (SELECT id FROM assessor_review WHERE tr_id='$id')");
                    if($due_date=='')
                        $due_date = DAO::getSingleValue($link, "SELECT MAX(next_contact) FROM assessor_review_forms_assessor4 WHERE review_id IN (SELECT id FROM assessor_review WHERE tr_id='$id')");
                    DAO::execute($link,"insert into assessor_review values(NULL,'$id','$due_date',NULL,'','',0,'','','','','',2,NULL,NULL,NULL,'','','','','','',0,0,0,0,0,0,0,0,0)");
                }
            }
        }



        if(DB_NAME=='am_baltic')
        {

/*
            if($_POST['source']==1)
            {

                // Welcome Review
                $sql="SELECT arf_introduction.* FROM
            arf_introduction
            WHERE arf_introduction.`review_id` IN (SELECT id FROM assessor_review WHERE template_review = 1 and tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND signature_assessor_font IS NOT NULL AND signature_learner_font IS NULL
            AND arf_introduction.review_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Welcome Review Form Emailed to Learner');";

                $st = $link->query($sql);
                if($st)
                {
                    while($row = $st->fetch())
                    {
                        $review_id = $row['review_id'];
                        $review_date = Date::toShort($row['review_date']);
                        $tr_id = DAO::getSingleValue($link, "select tr_id from assessor_review where id = '$review_id'");
                        $source=2;
                        $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
                        $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                        $assessor_id = $training_record->assessor;
                        if(in_array($tr_id, Array(29951,28959,29965,29029,29008,29297)))
                            $mailtolearner = $training_record->home_email; //Mailto here
                        else
                            $mailtolearner = $training_record->learner_work_email; //Mailto here
                        $client = DB_NAME;
                        $client = str_replace("am_","",$client);
                        $client = str_replace("_","-",$client);
                        $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");

                        $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");

                        $message = "<html><body>
                    <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
                    <br><br>Hi {$training_record->firstnames},
                    <br><br>Thank you for attending your review on " . $review_date . ". We hope you found it useful and are looking forward to your next session!
                    <br><br>You can
                    <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> access your apprenticeship review document here.</a>
                    If you agree with the content, could you please sign and save this within the next 24 hours?
                    <br><br>If you have any questions or need assistance, please let us know.
                    <br><br>Kind Regards,
                    <br><br>The Baltic Assessment Team
                    <br>
                    <img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                    </body></html>";

                        $subject = "Apprenticeship Review";
                        if($review_id==77294)
                        {
                            $success1 = Emailer::notification_email_review_auto_test($mailtolearner, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                            DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Welcome Review Form Emailed to Learner','Review',now(),NULL)");
                        }
                        else
                        {
                            $success1 = Emailer::notification_email_review_auto($mailtolearner, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                            DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Welcome Review Form Emailed to Learner','Review',now(),NULL)");
                        }
                    }
                }

                // On-Programme Review
                $sql="SELECT arf_introduction.* FROM
            arf_introduction
            WHERE arf_introduction.`review_id` IN (SELECT id FROM assessor_review WHERE template_review = 2 and tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND signature_assessor_font IS NOT NULL AND signature_learner_font IS NULL
            AND arf_introduction.review_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form Emailed to Learner');";

                $st = $link->query($sql);
                if($st)
                {
                    while($row = $st->fetch())
                    {
                        $review_id = $row['review_id'];
                        $review_date = Date::toShort($row['review_date']);
                        $tr_id = DAO::getSingleValue($link, "select tr_id from assessor_review where id = '$review_id'");
                        $source=2;
                        $key = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
                        $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                        $assessor_id = $training_record->assessor;
                        if(in_array($tr_id, Array(29951,28959,29965,29029,29008,29297)))
                            $mailtolearner = $training_record->home_email; //Mailto here
                        else
                            $mailtolearner = $training_record->learner_work_email; //Mailto here
                        $client = DB_NAME;
                        $client = str_replace("am_","",$client);
                        $client = str_replace("_","-",$client);
                        $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");

                        $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");

                        $message = "<html><body>
                    <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
                    <br><br>Hi {$training_record->firstnames},
                    <br><br>Thank you for attending your review on " . $review_date . ". We hope you found it useful and are looking forward to your next session!
                    <br><br>You can
                    <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=2&key=".$key."'> access your apprenticeship review document here.</a>
                    If you agree with the content, could you please sign, comment and save this within the next 24 hours?
                    <br><br>If you have any questions or need assistance, please let us know.
                    <br><br>Kind Regards,
                    <br><br>The Baltic Assessment Team
                    <br>
                    <img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                    </body></html>";

                        $subject = "Apprenticeship Review";
                        $success1 = Emailer::notification_email_review_auto($mailtolearner, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                        DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form Emailed to Learner','Review',now(),NULL)");
                    }
                }

            }
            if($_POST['source']==2)
            {
                // Welcome Review
                $sql="SELECT arf_introduction.* FROM
                arf_introduction
                WHERE arf_introduction.`review_id` IN (SELECT id FROM assessor_review WHERE template_review = 1 and tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND signature_learner_font IS NOT NULL
                AND signature_employer_font IS NULL
                AND arf_introduction.review_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Welcome Review Form Emailed to Employer');";

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
                        $assessor_id = $training_record->assessor;
                        $mailtolearner = $training_record->learner_work_email; //Mailto here
                        $client = DB_NAME;
                        $client = str_replace("am_","",$client);
                        $client = str_replace("_","-",$client);
                        $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");
                        $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");
                        $learner_programme = $row['learner_programme'];
                        $manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                        $mailtoemployer = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                        $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM arf_introduction WHERE review_id = '$review_id'"));

                        $ln = $training_record->firstnames . " " . $training_record->surname;
                        $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
                        <br>Hi {$manager_name},
                        <br><br>We recently completed an apprenticeship review with {$ln} &nbsp; on {$actual_date}.
                        <br><br>You can <a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."' > access the review document here. </a> Could you please read through the comments and if you're happy, sign and date this by {$actual_date}?
                        <br><br>If you have any questions or need any assistance, please get in touch with your apprentice's Learning Mentor or our assessment team – we’re always happy to help!
                        <br><br>Kind Regards,
                        <br><br>The Baltic Assessment Team
                    <br><br>
                    <img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                    </body></html>";

                        $subject = "Apprenticeship Review";
                        $success1 = Emailer::notification_email_review_auto($mailtoemployer, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                        DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Welcome Review Form Emailed to Employer','Review',now(),NULL)");
                    }
                }

                // On programme Review
                $sql="SELECT arf_introduction.* FROM
                arf_introduction
                WHERE arf_introduction.`review_id` IN (SELECT id FROM assessor_review WHERE template_review = 2 and tr_id IN (SELECT id FROM tr WHERE status_code = 1)) AND signature_learner_font IS NOT NULL
                AND signature_employer_font IS NULL
                AND arf_introduction.review_id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Form Emailed to Employer');";

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
                        $assessor_id = $training_record->assessor;
                        $mailtolearner = $training_record->learner_work_email; //Mailto here
                        $client = DB_NAME;
                        $client = str_replace("am_","",$client);
                        $client = str_replace("_","-",$client);
                        $assessor_name = DAO::getSingleValue($link, "SELECT concat(firstnames,' ', surname) FROM users WHERE id = '$assessor_id'");
                        $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");
                        $learner_programme = $row['learner_programme'];
                        $manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                        $mailtoemployer = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                        $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM arf_introduction WHERE review_id = '$review_id'"));


                        $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header2.png\"><br>
                        <br>Hi {$manager_name},
                        <br><br>We recently completed an apprenticeship review with {$training_record->firstnames} &nbsp; {$training_record->surname} &nbsp; on {$actual_date}.
                        <br><br>To sign off this review, we require some comments and feedback from you around {$training_record->firstnames}'s progress with their apprenticeship.
                        <br><br><a href ='https://{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."' > You can access the review document here. </a>
                        <br><br>Could you complete the comments section then sign and date this by {$actual_date}?
                        <br><br>If you're unable to access the link below, please copy this URL in your browser to open the form:
                        <br><br>{$client}.sunesis.uk.net/do.php?_action=arf_introduction&review_id=".$review_id."&tr_id=".$tr_id."&source=3&key=".$key."
                        <br><br>If you need any assistance with completing the document or have any questions, please contact your apprentice’s Learning Mentor.
                        <br><br>Kind Regards,
                        <br><br>The Baltic Assessment Team
                    <br><br>
                    <img src=\"https://baltic.sunesis.uk.net/images/email_footer2.png\"><br>
                    </body></html>";

                        $subject = "Apprenticeship Review";
                        $success1 = Emailer::notification_email_review_auto($mailtoemployer, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                        DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Form Emailed to Employer','Review',now(),NULL)");
                    }
                }

            }
*/
        }

/*
        if(DB_NAME=='am_baltic')
        {
            // Initial Invite
            $sql="SELECT * FROM
            assessor_review
            WHERE tr_id in (select id from tr where status_code = 1) and template_review = 2 and tr_id = '$id' AND meeting_date >= '2019-08-26' AND id IN (SELECT review_id FROM arf_introduction WHERE signature_assessor_font IS NOT NULL)
            AND id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Session Invite');";

            $st = $link->query($sql);
            if($st)
            {
                while($row = $st->fetch())
                {
                    $review_id = $row['id'];
                    $tr_id = DAO::getSingleValue($link, "select tr_id from assessor_review where id = '$review_id'");
                    $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                    $mailtolearner = $training_record->learner_work_email; //Mailto here
                    $client = DB_NAME;
                    $client = str_replace("am_","",$client);
                    $client = str_replace("_","-",$client);
                    $assessor_name = DAO::getSingleValue($link, "SELECT learner_assessor FROM arf_introduction WHERE review_id = '$review_id'");
                    $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");
                    $manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                    $mailtoemployer = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");

                    $due_date = DAO::getSingleValue($link,"SELECT DATE_FORMAT(next_contact,'%d/%m/%Y') FROM arf_introduction WHERE review_id = '$review_id';");
                    $hours = DAO::getSingleValue($link,"SELECT hours FROM arf_introduction WHERE review_id = '$review_id';");
                    $minutes = DAO::getSingleValue($link,"SELECT minutes FROM arf_introduction WHERE review_id = '$review_id';");
                    if(DAO::getSingleValue($link, "SELECT * FROM assessor_review LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id WHERE tr_id = '$tr_id' AND review_id < '$review_id' order by review_id desc limit 0,1"))
                        $form_arf = DAO::getObject($link, "SELECT * FROM assessor_review LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id WHERE tr_id = '$tr_id' AND review_id < '$review_id' order by review_id desc limit 0,1");
                    else
                        $form_arf = DAO::getObject($link, "SELECT * FROM assessor_review LEFT JOIN assessor_review_forms_assessor4 ON assessor_review_forms_assessor4.`review_id` = assessor_review.id WHERE tr_id = '$tr_id' AND review_id < '$review_id' order by review_id desc limit 0,1");

                    $form_current = ARFIntroduction::loadFromDatabase($link,$review_id);


                    if(date("H") < 12){
                        $greetings =  "Good Morning";
                    }elseif(date("H") > 11 && date("H") < 18){
                    $greetings = "Good Afternoon";
                    }elseif(date("H") > 17){
                        $greetings = "Good Evening";
                    }

                    $specific = isset($form_current->specific)?$form_current->specific:'';
                    $measurable = isset($form_current->measurable)?$form_current->measurable:'';
                    $achievable = isset($form_current->achievable)?$form_current->achievable:'';
                    $timebound = isset($form_current->timebound)?$form_current->timebound:'';
                    $smart_line5 = isset($form_current->smart_line5)?$form_current->smart_line5:'';
                    $smart_actions = "<table style='border-style: solid'>
                    <thead>
                    <th colspan=4 style='padding-top: 12px;  padding-bottom: 12px;  text-align: left;  background-color: #4CAF50;  color: white;'>&nbsp;&nbsp;&nbsp;Actions Required for next contact</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan=4 style='border: 1px solid #ddd;  padding: 8px;'>(SMART - Exactly what you will do, how you will know it is complete, how is it realistic for you to achieve, when will you achieve it by?)</td>
                    </tr>
                    <tr>
                        <td colspan=4 style='border: 1px solid #ddd;  padding: 8px;'>
                            <table>
                                <tr>
                                    <td style='border: 1px solid #ddd;  padding: 8px;'><i>
                                    {$specific}
                                    </i></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid #ddd;  padding: 8px;'><i>
                                    {$measurable}
                                    </i></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid #ddd;  padding: 8px;'><i>
                                    {$achievable}
                                    </i></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid #ddd;  padding: 8px;'><i>
                                    {$timebound}
                                    </i></td>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid #ddd;  padding: 8px;'><i>
                                    {$smart_line5}
                                    </i></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>";


                    $message = "<html><body>
                    <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                    <br><br>{$greetings}
                    <br><br>Please see your next scheduled session with me, which will take place on {$due_date} at {$hours}:{$minutes} hours.
                    <br><br>A copy of the review we completed during today’s session has already been sent to you. As discussed, please could
                    you follow the link in that email and complete the document with comments, signatures and dates. I would like this to be
                    completed within 24 hours. Once completed, I will then send it to {$manager_name} so that they can fill in the employer
                    section. It is important these are completed, to ensure that we are able to evidence that progress is being made, as
                    required by ESFA (Education and Skills Funding Agency).
                    <br><br>The emails will show in your inbox as: Baltic Apprenticeships <apprenticeships@perspective-uk.com>.
                    <br><br>Here are your actions required for next contact along with the link to your next session:
                    <br><br>{$smart_actions}
                    <br>{$form_current->adobe}
                    <br><br>If you have any questions please do not hesitate to contact me.
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

                    $mailto = $mailtolearner.";".$mailtoemployer.";".$from;
                    $subject = "Review Session Invite - Auto Email";

                    $success1 = Emailer::notification_email_review_auto($mailto, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Session Invite','Review',now(),NULL)");
                }
            }
        }

*/

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



