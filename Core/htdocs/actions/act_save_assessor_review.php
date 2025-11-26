<?php
class save_assessor_review implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$frequency = isset($_REQUEST['frequency'])?$_REQUEST['frequency']:'';
		$weeks= isset($_REQUEST['weeks'])?$_REQUEST['weeks']:'';
        $type= isset($_REQUEST['type'])?$_REQUEST['type']:'';

		$xmlreviews = XML::loadSimpleXML($xml);
		$values = '';

        foreach($xmlreviews->review as $review)
		{
			$ac = str_replace("'","\'",$review->assessorcomments);
			$paperwork = $review->paperwork;
			if($paperwork=='')
				$paperwork=10;//this is for the blank value of the dropdown if it is left blank, datatype of field is integer so have to provide something

			$attendance = ($review->attendance=='true')?1:0;

			if($review->duedate!='dd/mm/yyyy' && $review->duedate!='' && $review->duedate!='0000-00-00')
			{
				$d = new Date($review->date);
				$meeting_date = "'" . $d->getYear() . '-' . $d->getMonth() . '-' . $d->getDays() . "'";

				$d = new Date($review->duedate);
				$due_date = "'" . $d->getYear() . '-' . $d->getMonth() . '-' . $d->getDays() . "'";

                $d1 = new Date($review->duedate1);
                $due_date1 = "'" . $d1->getYear() . '-' . $d1->getMonth() . '-' . $d1->getDays() . "'";

                $d2 = new Date($review->duedate2);
                $due_date2 = "'" . $d2->getYear() . '-' . $d2->getMonth() . '-' . $d2->getDays() . "'";

                $d3 = new Date($review->duedate3);
                $due_date3 = "'" . $d3->getYear() . '-' . $d3->getMonth() . '-' . $d3->getDays() . "'";

                $manager_attendance=($review->manager_attendance=='true')?1:0;
                $manager_auth1=($review->manager_auth1=='true')?1:0;
                $manager_auth2=($review->manager_auth2=='true')?1:0;
                $manager_auth3=($review->manager_auth3=='true')?1:0;
                $reason1=($review->reason1=='')?0:$review->reason1;
                $reason2=($review->reason2=='')?0:$review->reason2;
                $reason3=($review->reason3=='')?0:$review->reason3;
                $hours=($review->hours=='')?0:$review->hours;

                if($type=="Feedback")
                {
                    $values .= "({$review->id}, {$tr_id}, {$due_date}, {$meeting_date}, '{$review->assessor}', '{$review->traffic}', '{$paperwork}', '{$ac}', '{$review->qualification}', '','','{$review->place}'),";
                }
                else
                {
                    if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic')
                        $values .= "({$review->id}, {$tr_id}, {$due_date}, {$meeting_date}, '{$review->assessor}', '{$review->traffic}', '{$paperwork}', '{$ac}', '{$review->qualification}', '','','{$review->place}','{$review->template}',{$due_date1},{$due_date2},{$due_date3},'{$review->from1}','{$review->from2}','{$review->from3}','{$review->to1}','{$review->to2}','{$review->to3}','{$reason1}','{$reason2}','{$reason3}','{$review->contracttype}','{$manager_attendance}','{$hours}','{$manager_auth1}','{$manager_auth2}','{$manager_auth3}'),";
                    else
                        $values .= "({$review->id}, {$tr_id}, {$due_date}, {$meeting_date}, '{$review->assessor}', '{$review->traffic}', '{$paperwork}', '{$ac}', '{$review->qualification}', '','','{$review->place}'),";
                }
			}
		}

		$values = substr($values, 0, -1);

		DAO::transaction_start($link);
		try
		{
			if(!empty($values))
			{
                if($type=="Review")
                {
                    if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic')
                    {
                        $sql2 = <<<HEREDOC
insert into
	assessor_review (id, tr_id, due_date, meeting_date, assessor, comments, paperwork_received, assessor_comments, qualification, smart_assessor_id,typeofreview,place,template_review,due_date1,due_date2,due_date3,from1,from2,from3,to1,to2,to3,reason1,reason2,reason3,contract_type,manager_attendance,hours,manager_auth1,manager_auth2,manager_auth3)
values
	$values
ON DUPLICATE KEY UPDATE id=values(id),tr_id=values(tr_id),due_date=values(due_date),meeting_date=values(meeting_date),assessor=values(assessor),comments=values(comments),paperwork_received=values(paperwork_received),
assessor_comments=values(assessor_comments),qualification=values(qualification),smart_assessor_id=values(smart_assessor_id),typeofreview=values(typeofreview),place=values(place),template_review=values(template_review),due_date1=values(due_date1)
,due_date2=values(due_date2),due_date3=values(due_date3),from1=values(from1),from2=values(from2),from3=values(from3),to1=values(to1),to2=values(to2),to3=values(to3),reason1=values(reason1),reason2=values(reason2),reason3=values(reason3)
,contract_type=values(contract_type),manager_attendance=values(manager_attendance),hours=values(hours),manager_auth1=values(manager_auth1),manager_auth2=values(manager_auth2),manager_auth3=values(manager_auth3);
HEREDOC;
                    }
                    else
                    {
                        $sql2 = <<<HEREDOC
insert into
	assessor_review (id, tr_id, due_date, meeting_date, assessor, comments, paperwork_received, assessor_comments, qualification, smart_assessor_id,typeofreview,place)
values
	$values
ON DUPLICATE KEY UPDATE id=values(id),tr_id=values(tr_id),due_date=values(due_date),meeting_date=values(meeting_date),assessor=values(assessor),comments=values(comments),paperwork_received=values(paperwork_received),
assessor_comments=values(assessor_comments),qualification=values(qualification),smart_assessor_id=values(smart_assessor_id),typeofreview=values(typeofreview),place=values(place);
HEREDOC;
                    }
                }
                elseif($type=="Feedback")
                {
                    $sql2 = <<<HEREDOC
insert into
	fap_review (id, tr_id, due_date, meeting_date, assessor, comments, paperwork_received, assessor_comments, qualification, typeofreview, smart_assessor_id, place)
values
	$values
ON DUPLICATE KEY UPDATE id=values(id),tr_id=values(tr_id),due_date=values(due_date),meeting_date=values(meeting_date),assessor=values(assessor),comments=values(comments),paperwork_received=values(paperwork_received),
assessor_comments=values(assessor_comments),qualification=values(qualification),typeofreview=values(typeofreview),smart_assessor_id=values(smart_assessor_id),place=values(place);
HEREDOC;
				// Delete existing reviews
                }



			    DAO::execute($link, $sql2);
			    }
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link);
			throw new WrappedException($e);

		}

/*        if(DB_NAME=='am_baltic_demo' or DB_NAME=='am_baltic')
        {
            // Cancellation Invite
            $id = $tr_id;
            $sql="SELECT * FROM
                assessor_review
                WHERE template_review = 2 AND due_date1 IS NOT NULL and manager_auth1 = 1 and tr_id = '$id' and reason1!=3
                AND id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Session Invite - Revised 1');";

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

                    $due_date = Date::toShort($row['due_date1']);
                    $hours = $row['from1'];
                    $minutes = $row['to1'];

                    $form_arf = ARFIntroduction::loadFromDatabase($link,$review_id);

                    if(date("H") < 12){
                        $greetings =  "Good Morning";
                    }elseif(date("H") > 11 && date("H") < 18){
                        $greetings = "Good Afternoon";
                    }elseif(date("H") > 17){
                        $greetings = "Good Evening";
                    }


                    $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                        <br><br>{$greetings}
                        <br><br>Unfortunately due to you not being able to attend your original review date, we have had to rearrange this for
                         {$due_date} at {$hours}:{$minutes} hours.
                        <br><br>Here is your link to your next session:
                        <br><br>{$form_arf->adobe}
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
                    $subject = "Cancellation Invite - Auto Email";
                    $success1 = Emailer::notification_email_review_auto($mailto, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Session Invite - Revised 1','Review',now(),NULL)");
                }
            }


            // Cancellation Invite
            $id = $tr_id;
            $sql="SELECT * FROM
                assessor_review
                WHERE template_review = 2 AND due_date2 IS NOT NULL and manager_auth2 = 1 and tr_id = '$id' and reason2!=3
                AND id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Session Invite - Revised 2');";

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

                    $due_date = Date::toShort($row['due_date2']);
                    $hours = $row['from2'];
                    $minutes = $row['to2'];
                    $form_arf = ARFIntroduction::loadFromDatabase($link,$review_id);

                    if(date("H") < 12){
                        $greetings =  "Good Morning";
                    }elseif(date("H") > 11 && date("H") < 18){
                        $greetings = "Good Afternoon";
                    }elseif(date("H") > 17){
                        $greetings = "Good Evening";
                    }


                    $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                        <br><br>{$greetings}
                        <br><br>Unfortunately due to you not being able to attend your original review date, we have had to rearrange this for
                         {$due_date} at {$hours}:{$minutes} hours.
                        <br><br>Here is your link to your next session:
                        <br><br>{$form_arf->adobe}
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
                    $subject = "Cancellation Invite - Auto Email";
                    $success1 = Emailer::notification_email_review_auto($mailto, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Session Invite - Revised 2','Review',now(),NULL)");
                }
            }


            // Cancellation Invite
            $id = $tr_id;
            $sql="SELECT * FROM
                assessor_review
                WHERE template_review = 2 AND due_date3 IS NOT NULL and manager_auth3 = 1 and tr_id = '$id' and reason3!=3
                AND id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Session Invite - Revised 3');";

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

                    $due_date = Date::toShort($row['due_date3']);
                    $hours = $row['from3'];
                    $minutes = $row['to3'];
                    $form_arf = ARFIntroduction::loadFromDatabase($link,$review_id);

                    if(date("H") < 12){
                        $greetings =  "Good Morning";
                    }elseif(date("H") > 11 && date("H") < 18){
                        $greetings = "Good Afternoon";
                    }elseif(date("H") > 17){
                        $greetings = "Good Evening";
                    }


                    $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                        <br><br>{$greetings}
                        <br><br>Unfortunately due to you not being able to attend your original review date, we have had to rearrange this for
                         {$due_date} at {$hours}:{$minutes} hours.
                        <br><br>Here is your link to your next session:
                        <br><br>{$form_arf->adobe}
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
                    $subject = "Cancellation Invite - Auto Email";
                    $success1 = Emailer::notification_email_review_auto($mailto, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Session Invite - Revised 3','Review',now(),NULL)");
                }
            }


            // Cancellation Invite assessor
            $id = $tr_id;
            $sql="SELECT * FROM
                assessor_review
                WHERE template_review = 2 AND due_date1 IS NOT NULL and manager_auth1 = 1 and tr_id = '$id' and reason1=3
                AND id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Session Invite - Revised 4');";

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
                    $assessor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id IN (SELECT assessor FROM tr WHERE id = '$tr_id');");
                    $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");
                    $manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                    $mailtoemployer = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");

                    $due_date = Date::toShort($row['due_date1']);
                    $hours = $row['from1'];
                    $minutes = $row['to1'];
                    if(DAO::getSingleValue($link, "SELECT * FROM assessor_review LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id WHERE tr_id = '$tr_id' AND review_id < '$review_id' order by review_id desc limit 0,1"))
                        $form_arf = DAO::getObject($link, "SELECT * FROM assessor_review LEFT JOIN arf_introduction ON arf_introduction.`review_id` = assessor_review.id WHERE tr_id = '$tr_id' AND review_id < '$review_id' order by review_id desc limit 0,1");
                    else
                        $form_arf = DAO::getObject($link, "SELECT * FROM assessor_review LEFT JOIN assessor_review_forms_assessor4 ON assessor_review_forms_assessor4.`review_id` = assessor_review.id WHERE tr_id = '$tr_id' AND review_id < '$review_id' order by review_id desc limit 0,1");
                    if($row['assessor']!='')
                        $assessor_name2 = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users where id = {$row['assessor']}");
                    else
                        $assessor_name2 = '';

                    if(date("H") < 12){
                        $greetings =  "Good Morning";
                    }elseif(date("H") > 11 && date("H") < 18){
                        $greetings = "Good Afternoon";
                    }elseif(date("H") > 17){
                        $greetings = "Good Evening";
                    }

                    $adobe = (isset($form_arf->adobe))?$form_arf->adobe:"";

                    $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                        <br><br>{$greetings}
                        <br><br>Unfortunately due to unforeseen circumstances your assessor, {$assessor_name}, is currently out of the business.
                        <br><br>As a result of this we would like to arrange your review for, {$due_date} at {$hours}:{$minutes} hours.  {$assessor_name2} will be hosting this review session and will be able to support you in your assessor's absence.
                        <br><br>Here is your link to your next session:
                        <br><br>{$adobe}
                        <br><br>Apologies for any inconvenience caused and if you have any queries or would like to discuss this change please do not hesitate to contact via phone 01325 731 056.
                        <br><br>Kind Regards,
                        <br><br>{$assessor_name2}
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
                    $subject = "Cancellation Invite - Auto Email";
                    $success1 = Emailer::notification_email_review_auto($mailto, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Session Invite - Revised 4','Review',now(),NULL)");
                }
            }



            // Cancellation Invite assessor
            $id = $tr_id;
            $sql="SELECT * FROM
                assessor_review
                WHERE template_review = 2 AND due_date2 IS NOT NULL and manager_auth2 = 1 and tr_id = '$id' and reason2=3
                AND id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Session Invite - Revised 5');";

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
                    $assessor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id IN (SELECT assessor FROM tr WHERE id = '$tr_id');");
                    $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");
                    $manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                    $mailtoemployer = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");

                    $due_date = Date::toShort($row['due_date2']);
                    $hours = $row['from2'];
                    $minutes = $row['to2'];
                    $form_arf = ARFIntroduction::loadFromDatabase($link,$review_id);
                    $assessor_name2 = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users where id = {$row['assessor']}");

                    if(date("H") < 12){
                        $greetings =  "Good Morning";
                    }elseif(date("H") > 11 && date("H") < 18){
                        $greetings = "Good Afternoon";
                    }elseif(date("H") > 17){
                        $greetings = "Good Evening";
                    }


                    $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                        <br><br>{$greetings}
                        <br><br>Unfortunately due to unforeseen circumstances your assessor, {$assessor_name}, is currently out of the business.
                        <br><br>As a result of this we would like to arrange your review for, {$due_date} at {$hours}:{$minutes} hours.  {$assessor_name2} will be hosting this review session and will be able to support you in your assessor's absence.
                        <br><br>Here is your link to your next session:
                        <br><br>{$form_arf->adobe}
                        <br><br>Apologies for any inconvenience caused and if you have any queries or would like to discuss this change please do not hesitate to contact via phone 01325 731 056.
                        <br><br>Kind Regards,
                        <br><br>{$assessor_name2}
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
                    $subject = "Cancellation Invite - Auto Email";
                    $success1 = Emailer::notification_email_review_auto($mailto, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Session Invite - Revised 5','Review',now(),NULL)");
                }
            }



            // Cancellation Invite assessor
            $id = $tr_id;
            $sql="SELECT * FROM
                assessor_review
                WHERE template_review = 2 AND due_date3 IS NOT NULL and manager_auth3 = 1 and tr_id = '$id' and reason1=3
                AND id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Session Invite - Revised 6');";

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
                    $assessor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id IN (SELECT assessor FROM tr WHERE id = '$tr_id');");
                    $from = DAO::getSingleValue($link, "select work_email from users where id = '$training_record->assessor'");
                    $manager_name = DAO::getSingleValue($link,"select contact_name from organisation_contact where contact_id = '$training_record->crm_contact_id'");
                    $mailtoemployer = DAO::getSingleValue($link,"select contact_email from organisation_contact where contact_id = '$training_record->crm_contact_id'");

                    $due_date = Date::toShort($row['due_date3']);
                    $hours = $row['from3'];
                    $minutes = $row['to3'];
                    $form_arf = ARFIntroduction::loadFromDatabase($link,$review_id);
                    $assessor_name2 = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users where id = {$row['assessor']}");

                    if(date("H") < 12){
                        $greetings =  "Good Morning";
                    }elseif(date("H") > 11 && date("H") < 18){
                        $greetings = "Good Afternoon";
                    }elseif(date("H") > 17){
                        $greetings = "Good Evening";
                    }


                    $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                        <br><br>{$greetings}
                        <br><br>Unfortunately due to unforeseen circumstances your assessor, {$assessor_name}, is currently out of the business.
                        <br><br>As a result of this we would like to arrange your review for, {$due_date} at {$hours}:{$minutes} hours.  {$assessor_name2} will be hosting this review session and will be able to support you in your assessor's absence.
                        <br><br>Here is your link to your next session:
                        <br><br>{$form_arf->adobe}
                        <br><br>Apologies for any inconvenience caused and if you have any queries or would like to discuss this change please do not hesitate to contact via phone 01325 731 056.
                        <br><br>Kind Regards,
                        <br><br>{$assessor_name2}
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
                    $subject = "Cancellation Invite - Auto Email";
                    $success1 = Emailer::notification_email_review_auto($mailto, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                    DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Session Invite - Revised 6','Review',now(),NULL)");
                }
            }

            // Revised Notification 1
            $sql="SELECT * FROM
                assessor_review
                WHERE due_date1 IS NOT NULL AND due_date1 != '0000-00-00' AND manager_auth1 != 1
                AND id NOT IN (SELECT form_id FROM forms_audit WHERE description='Review Cancellation Notification 1');";

            $st = $link->query($sql);
            if($st)
            {
                while($row = $st->fetch())
                {
                    $review_id = $row['id'];
                    $tr_id = $row['tr_id'];
                    $manager_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE username IN (SELECT supervisor FROM users WHERE id IN (SELECT assessor FROM tr WHERE id = '$tr_id'));");
                    $training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
                    $review_forecast_date = $row['due_date'];
                    $revised_review_date = $row['due_date1'];
                    $revised_review_time = $row['from1'] . '-' . $row['to1'];
                    if($row['reason1']==1)
                        $reason = "Learner";
                    elseif($row['reason1']==2)
                        $reason = "Employer";
                    else
                        $reason = "Assessor";
                    $assessor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id IN (SELECT assessor FROM tr WHERE id = '$tr_id');");
                    $assessor_comments = $row['assessor_comments'];
                    $mailtomanager = DAO::getSingleValue($link, "SELECT work_email FROM users WHERE username IN (SELECT supervisor FROM users WHERE id IN (SELECT assessor FROM tr WHERE id = '$tr_id'));");

                    $message = "<html><body>
                        <img src=\"https://baltic.sunesis.uk.net/images/baltic_email_header.png\"><br>
                        <br><br>Dear {$manager_name}
                        <br><br>Please review the below review cancellation and authorise using the manager authorisation fields in Sunesis:
                        <br><br>Learner Name: {$training_record->firstnames}&nbsp;{$training_record->surname}
                        <br>Review Forecast Date: {$review_forecast_date}
                        <br>Revised Review Date: {$revised_review_date}
                        <br>Revised Review Time: {$revised_review_time}
                        <br>Reason for cancellation: {$reason}
                        <br>Assessor: {$assessor_name}
                        <br>Comments: {$assessor_comments}
                        <br><br>Kind Regards,
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

                    $mailto = $mailtomanager;
                    //DAO::execute($link,"insert into diceware values(NULL,\"SELECT work_email FROM users WHERE id IN (SELECT supervisor FROM users WHERE id IN (SELECT assessor FROM tr WHERE id = '$tr_id'));\");");
                    if($mailto!="")
                    {
                        $from = "sunesis@perspective-uk.com";
                        $subject = "Cancellation Notification for Manager";
                        $success1 = Emailer::notification_email_review_auto($mailto, 'apprenticeships@perspective-uk.com', $from, $subject, '', $message, array(), array('X-Mailer: PHP/' . phpversion()));
                        DAO::execute($link,"insert into forms_audit values(NULL,$review_id,'Review Cancellation Notification 1','Review',now(),NULL)");
                    }
                }
            }


        }
            */

	}
}
?>
