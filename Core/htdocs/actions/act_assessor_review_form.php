<?php
class assessor_review_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $meeting_date = isset($_REQUEST['meeting_date']) ? $_REQUEST['meeting_date'] : '';
        $source = isset($_REQUEST['source']) ? $_REQUEST['source'] : '';
        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
        $output = isset($_REQUEST['output']) ? $_REQUEST['output'] : '';

        //pre("Please contact support");
    /*    if($source==1)
            $_SESSION['bc']->add($link, "do.php?_action=read_training_record&id=$tr_id", "Training Record");*/

        $keytoverify = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
        if($source==2 or $source==3)
            if($key!=$keytoverify)
                pre("Invalid URL");

        $review_programme_title = DAO::getSingleValue($link, "select review_programme_title from courses inner join courses_tr on courses_tr.course_id = courses.id and courses_tr.tr_id = '$tr_id'");
        $training_record = TrainingRecord::loadFromDatabase($link,$tr_id);
        if(isset($training_record->assessor) && $training_record->assessor!=0)
            $assessor = User::loadFromDatabaseById($link,$training_record->assessor);
        else
        {
            $assessor_id = DAO::getSingleValue($link,"select assessor from groups inner join group_members on group_members.groups_id = groups.id where group_members.tr_id = '$tr_id'");
            if($assessor_id)
                $assessor = User::loadFromDatabaseById($link,$assessor_id);
            else
                $assessor = new User();
        }

        $employer = Organisation::loadFromDatabase($link,$training_record->employer_id);
        $form = AssessorReviewForm::loadFromDatabase($link,$review_id);

        // Auto populate date
        if($source==1)
        {
            if($form->signature_assessor_date=='')
                $form->signature_assessor_date = date('Y-m-d');
        }
        elseif($source==2)
        {
            if($form->signature_learner_date=='')
                $form->signature_learner_date = date('Y-m-d');
        }
        elseif($source==3)
        {
            if($form->signature_employer_date=='')
                $form->signature_employer_date = date('Y-m-d');
        }
        /*if($source==2 && $form->signature_learner_font!="" && $form->signature_learner_name!="" && $form->signature_learner_date!="")
            pre("This link has been expired");
        elseif($source==3 && $form->signature_employer_font!="" && $form->signature_employer_name!="" && $form->signature_employer_date!="")
            pre("This link has been expired");
*/
        $learner = User::loadFromDatabase($link,$training_record->username);
        if(isset($training_record->crm_contact_id))
            $crm_contact = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
        else
            $crm_contact = new EmployerContacts();
        if($form->learner_programme=='')
            $form->learner_programme=$review_programme_title;
        if($form->learner_qualification=='')
            $form->learner_qualification=$review_programme_title;
        if($form->learner_name=='')
            $form->learner_name=$training_record->firstnames . ' ' . $training_record->surname;
        if($form->learner_dob=='')
            $form->learner_dob=$training_record->dob;
        if($form->learner_assessor=='')
            $form->learner_assessor=$_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname ;
        if($form->learner_ni=='')
            $form->learner_ni=$training_record->ni;
        if($form->learner_employer=='')
            $form->learner_employer=$employer->legal_name;
        if($form->start_date=='')
            $form->start_date=$training_record->start_date;
        if($form->planned_end_date=='')
            $form->planned_end_date=$training_record->target_date;
        if($form->registration_number=='')
            $form->registration_number = $learner->enrollment_no;
        if(isset($crm_contact))
            if($form->learner_manager=='')
                $form->learner_manager = $crm_contact->contact_name;



        if($output=='PDF')
        {
        // Save signature files
        $this->save_signatures($link,$form,$tr_id,$review_id);
        $db = DB_NAME;
        $username=$training_record->username;
        $learner_signature_url = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/learner_signature.png";
        $assessor_signature_url = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/assessor_signature.png";
        $employer_signature_url = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/employer_signature.png";

$html = '
<table style="width: 900px">
    <tr>
        <td>
            <table class="table1">
                <thead>
                    <tr>
                        <th style="width: 800px">&nbsp;&nbsp;&nbsp;Learner Review</th>
                    </tr>
                </thead>
            </table>
        </td>
        <td>';

        if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
            $html .= '<img height = "100" width = "80" src="images/logos/baltic.png">';
        else
            $html .= '<img height = "100" width = "80" src="images/sunesislogo.gif">';

        $html .= '</td>
    </tr>
</table>
<br>
<table class="table1" style="width: 900px">
    <thead>
        <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Learner Details</th></tr>
    </thead>
    <tbody>
        <tr>
            <td>Learner Name:</td>
            <td>' . $form->learner_name. '</td>
            <td>Date of Birth:</td>
            <td> ' . Date::toShort($form->learner_dob) . '</td>
        </tr>
        <tr>
            <td>Reviewer/ Assessor:</td>
            <td>'.$form->learner_assessor.'</td>
            <td>NI Number:</td>
            <td>'.$form->learner_ni.'</td>
        </tr>
        <tr>
            <td>Employer Name:</td>
            <td>'.$form->learner_employer.'</td>
            <td>Line Manager:</td>
            <td>'.$form->learner_manager.'</td>
        </tr>
        <tr>
            <td>Programme:</td>
            <td>'.$form->learner_programme.'</td>
            <td>Qualification Title:</td>
            <td>'.$form->learner_qualification.'</td>
        </tr>
        <tr>
            <td>Start Date:</td>
            <td>'.Date::toShort($form->start_date).'</td>
            <td>Registration Number:</td>
            <td>'.$form->registration_number.'</td>
        </tr>
        <tr>
            <td>Expected Completion Date:</td>
            <td>'.Date::toShort($form->planned_end_date).'</td>
            <td>Actual Review Date:</td>
            <td>'.Date::toShort($form->review_date).'</td>
        </tr>
    </tbody>
</table>
<br>
';
$html .= '
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;IF REVIEW 1</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Confirm understanding of plagiarism</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="plagiarism" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->plagiarism.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
        <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Review Employer Comments</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Discuss employer comments from previous review and any challenges set at FAP</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="employer_previous_comments" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->employer_previous_comments.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Significant Achievement over past 4 weeks</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Learner to identify a personal achievement  for example a piece of work, team contribution, work place recognition, Apprentice of the Month or Learner of the Week nomination. </td>
    </tr>
    <tr>
        <td colspan=4><textarea name="significant_achievement" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->significant_achievement.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
';

$html .= '
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Equality and Diversity</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Review learner understanding of Equality and Diversity, QCF Appeals procedure and bullying and harassment.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="equality_diversity" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->equality_diversity.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Safeguarding including E Safety</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Discuss with learner whether they feel safe at work, are they aware of '; if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo') $html.= 'Baltic '; else $html.= 'Perspective '; $html.='Training &apos;s Safeguarding policy.  Explore their understanding of safeguarding.  Discuss with learners their understanding of e safety, privacy setting, the negative aspects of social media.  Educate and challenge as appropriate.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="safeguarding" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->safeguarding.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Prevent, Radicalisation and Extremism</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Explore with learners their understanding of Prevent, Radicalisation and Extremism.   Educate and challenge as appropriate.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="prevent" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->prevent.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Health & Wellbeing</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Discuss with learner topics such as five a day, diet and exercise, factors that affect their health, i.e. drugs, alcohol and smoking. Raise awareness of anxiety and depression.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="health_wellbeing" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->health_wellbeing.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Concerns</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Does the learner have any concerns or wish to raise any Safeguarding, Equality & Diversity, Health & Safety, Health  & wellbeing issues (ASK THIS QUESTION EVERY MONTH)</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="concerns" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->concerns.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
';

$html .='
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Apprenticeship Commitment</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Are there any issues or anything you would like to disclose which could prevent you completing your 12 month apprenticeship?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="commitment" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->commitment.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Additional Support Requirements</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Is there any additional support you would like from '; if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo') $html.= 'Baltic '; else $html.= 'Perspective '; $html.=' Training or your Line Manager?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="additional_support" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->additional_support.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Learner Progress at Placement / Employment</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Discuss both positive and development areas. Comment on training actually undertaken on the job, attendance, time keeping, attitude and ability including new skills developed. Identify new skills and experience that have been learnt and applied at work.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="progress" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->progress.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Learning Log Discussion (must link in with learner/tutor off the job learning logs)</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><textarea name="discussion" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->discussion.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;ERR</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><textarea name="err" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->err.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
';

$html .= '
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Main Aim  (Indicate units completed with a %)</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Main aim title (Units):</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->main_name_unit1.'</td>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->main_name_unit2.'</td>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->main_name_unit3.'</td>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->main_name_unit4.'</td>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->main_name_unit5.'</td>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->main_name_unit6.'</td>
                </tr>
                <tr>
                    <td colspan=2>'.$form->main_perc_unit1.'</td>
                    <td colspan=2>'.$form->main_perc_unit2.'</td>
                    <td colspan=2>'.$form->main_perc_unit3.'</td>
                    <td colspan=2>'.$form->main_perc_unit4.'</td>
                    <td colspan=2>'.$form->main_perc_unit5.'</td>
                    <td colspan=2>'.$form->main_perc_unit6.'</td>
                </tr>
                <tr>
                    <td>Unit</td>
                    <td>'.$form->main_name_unit7.'</td>
                    <td>Unit</td>
                    <td>'.$form->main_name_unit8.'</td>
                    <td>Unit</td>
                    <td>'.$form->main_name_unit9.'</td>
                    <td>Unit</td>
                    <td>'.$form->main_name_unit10.'</td>
                    <td>Unit</td>
                    <td>'.$form->main_name_unit11.'</td>
                    <td>Unit</td>
                    <td>'.$form->main_name_unit12.'</td>
                </tr>
                <tr>
                    <td colspan=2>'.$form->main_perc_unit7.'</td>
                    <td colspan=2>'.$form->main_perc_unit8.'</td>
                    <td colspan=2>'.$form->main_perc_unit9.'</td>
                    <td colspan=2>'.$form->main_perc_unit10.'</td>
                    <td colspan=2>'.$form->main_perc_unit11.'</td>
                    <td colspan=2>'.$form->main_perc_unit12.'</td>
                </tr>
            </table>
        </td>
    <tr>
        <td>
            <table>
                 <tr>
                    <td style="text-align: center; width: 300px">Workshop 1</td>
                    <td style="text-align: center; width: 300px">Workshop 2</td>
                    <td style="text-align: center; width: 300px">Workshop 3</td>
                </tr>
                <tr>
                    <td>'.$form->workshop1.'</td>
                    <td>'.$form->workshop2.'</td>
                    <td>'.$form->workshop3.'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Progress toward target date. What has the learner been doing towards completing this aim? Insert Progress made in FAP</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="main_progress" style="font-family:sans-serif; font-size:10pt"  rows="12" cols="123">'.$form->main_progress.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
';

$html .= '
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Tech Cert (Indicate units completed with a %)</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Tech Cert title (Units):</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->tech_name_unit1.'</td>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->tech_name_unit2.'</td>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->tech_name_unit3.'</td>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->tech_name_unit4.'</td>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->tech_name_unit5.'</td>
                    <td width=100px>&nbsp;Unit&nbsp;</td>
                    <td width=100px>'.$form->tech_name_unit6.'</td>
                </tr>
                <tr>
                    <td colspan=2>'.$form->tech_perc_unit1.'</td>
                    <td colspan=2>'.$form->tech_perc_unit2.'</td>
                    <td colspan=2>'.$form->tech_perc_unit3.'</td>
                    <td colspan=2>'.$form->tech_perc_unit4.'</td>
                    <td colspan=2>'.$form->tech_perc_unit5.'</td>
                    <td colspan=2>'.$form->tech_perc_unit6.'</td>
                </tr>
                <tr>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td>'.$form->tech_name_unit7.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td>'.$form->tech_name_unit8.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td>'.$form->tech_name_unit9.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td>'.$form->tech_name_unit10.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td>'.$form->tech_name_unit11.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td>'.$form->tech_name_unit12.'</td>
                </tr>
                <tr>
                    <td colspan=2>'.$form->tech_perc_unit7.'</td>
                    <td colspan=2>'.$form->tech_perc_unit8.'</td>
                    <td colspan=2>'.$form->tech_perc_unit9.'</td>
                    <td colspan=2>'.$form->tech_perc_unit10.'</td>
                    <td colspan=2>'.$form->tech_perc_unit11.'</td>
                    <td colspan=2>'.$form->tech_perc_unit12.'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Progress toward target date - What has the learner been doing towards completing this aim? Insert Progress made in FAP</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="tech_progress" style="font-family:sans-serif; font-size:10pt"  rows="15" cols="123">'.$form->tech_progress.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
';


$html .= '<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Complete Knowledge Module and Competence Progress for Apprentices Completing Standards</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Progress Summary: Knowledge Modules</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td width=200px>&nbsp;Knowledge Module&nbsp;</td>
                    <td width=200px>&nbsp;Status&nbsp;</td>
                    <td width=200px>&nbsp;Knowledge Module&nbsp;</td>
                    <td width=200px>&nbsp;Status&nbsp;</td>
                    <td width=200px>&nbsp;Knowledge Module&nbsp;</td>
                    <td width=200px>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td>'.$form->knowledge_module_1.'</td>
                    <td>'.$form->knowledge_status_1.'</td>
                    <td>'.$form->knowledge_module_2.'</td>
                    <td>'.$form->knowledge_status_2.'</td>
                    <td>'.$form->knowledge_module_3.'</td>
                    <td>'.$form->knowledge_status_3.'</td>
                </tr>
                <tr>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td>'.$form->knowledge_module_4.'</td>
                    <td>'.$form->knowledge_status_4.'</td>
                    <td>'.$form->knowledge_module_5.'</td>
                    <td>'.$form->knowledge_status_5.'</td>
                    <td>'.$form->knowledge_module_6.'</td>
                    <td>'.$form->knowledge_status_6.'</td>
                </tr>
                <tr>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td>'.$form->knowledge_module_7.'</td>
                    <td>'.$form->knowledge_status_7.'</td>
                    <td>'.$form->knowledge_module_8.'</td>
                    <td>'.$form->knowledge_status_8.'</td>
                    <td>'.$form->knowledge_module_9.'</td>
                    <td>'.$form->knowledge_status_9.'</td>
                </tr>
                <tr>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Knowledge Module&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td>'.$form->knowledge_module_10.'</td>
                    <td>'.$form->knowledge_status_10.'</td>
                    <td>'.$form->knowledge_module_11.'</td>
                    <td>'.$form->knowledge_status_11.'</td>
                    <td>'.$form->knowledge_module_12.'</td>
                    <td>'.$form->knowledge_status_12.'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Record here the detail of the progress.  What has the learner been doing towards completing this?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="knowledge_module" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->knowledge_module.'</textarea></td>
    </tr>
    <tr>
        <td colspan=4>Progress Summary: Workplace Competence</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td width=200px>&nbsp;Competence&nbsp;</td>
                    <td width=200px>&nbsp;Status&nbsp;</td>
                    <td width=200px>&nbsp;Competence&nbsp;</td>
                    <td width=200px>&nbsp;Status&nbsp;</td>
                    <td width=200px>&nbsp;Competence&nbsp;</td>
                    <td width=200px>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td>'.$form->workplace_competence_1.'</td>
                    <td>'.$form->workplace_status_1.'</td>
                    <td>'.$form->workplace_competence_2.'</td>
                    <td>'.$form->workplace_status_2.'</td>
                    <td>'.$form->workplace_competence_3.'</td>
                    <td>'.$form->workplace_status_3.'</td>
                </tr>
                <tr>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td>'.$form->workplace_competence_4.'</td>
                    <td>'.$form->workplace_status_4.'</td>
                    <td>'.$form->workplace_competence_5.'</td>
                    <td>'.$form->workplace_status_5.'</td>
                    <td>'.$form->workplace_competence_6.'</td>
                    <td>'.$form->workplace_status_6.'</td>
                </tr>
                <tr>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td>'.$form->workplace_competence_7.'</td>
                    <td>'.$form->workplace_status_7.'</td>
                    <td>'.$form->workplace_competence_8.'</td>
                    <td>'.$form->workplace_status_8.'</td>
                    <td>'.$form->workplace_competence_9.'</td>
                    <td>'.$form->workplace_status_9.'</td>
                </tr>
                <tr>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;Status&nbsp;</td>
                </tr>
                <tr>
                    <td>'.$form->workplace_competence_10.'</td>
                    <td>'.$form->workplace_status_10.'</td>
                    <td>'.$form->workplace_competence_11.'</td>
                    <td>'.$form->workplace_status_11.'</td>
                    <td>'.$form->workplace_competence_12.'</td>
                    <td>'.$form->workplace_status_12.'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Record here the detail of the progress.  What has the learner been doing towards completing this?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="workplace_competence" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->workplace_competence.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>';

$html .= '
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Functional Skills Development: In the Workplace - In Everyday Use - In Training</th><tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Functional Skills exemptions (Tick if exempt):</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td style="text-align: center; width: 300px">English</td><td>'.$form->english_exempt.'</td>
                    <td style="text-align: center; width: 300px">Maths</td><td>'.$form->math_exempt.'</td>
                    <td style="text-align: center; width: 300px">ICT</td><td>'.$form->ict_exempt.'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Identify use of Maths, English and ICT in the work role, how are these skills being developed in the work role. Provide examples of work tasks that continue to develop functional skills knowledge.  Recognise their everyday use.  Learner to comment on FS workshop activity, moredle use or one to one specialist support received.  If First Review - review induction task  here.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="use_functional" style="font-family:sans-serif; font-size:10pt" rows="10" cols="123">'.$form->use_functional.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Functional Skills - (complete only if working towards qualification)</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Indicate units completed with a %</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td style="text-align: center; width: 150px">&nbsp;&nbsp;Qualification&nbsp;&nbsp;</td>
                    <td style="text-align: center; width: 150px">English L1</td>
                    <td style="text-align: center; width: 150px">English L2</td>
                    <td style="text-align: center; width: 150px">Math L1</td>
                    <td style="text-align: center; width: 150px">Math L2</td>
                    <td style="text-align: center; width: 150px">ICT L1</td>
                    <td style="text-align: center; width: 150px">ICT L2</td>
                    <td style="text-align: center; width: 150px">PLTS</td>
                </tr>
                <tr>
                    <td style="text-align: center">%</td>
                    <td>'.$form->english_l1.'</td>
                    <td>'.$form->english_l2.'</td>
                    <td>'.$form->math_l1.'</td>
                    <td>'.$form->math_l2.'</td>
                    <td>'.$form->ict_l1.'</td>
                    <td>'.$form->ict_l2.'</td>
                    <td>'.$form->plts.'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Progress toward target date - What has the learner been doing towards completing this aim? Insert Progress made in FAP</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="functional_progress" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->functional_progress.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Actions Required for next contact</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>(SMART - Exactly what you will do, how you will know it is complete, how is it realistic for you to achieve, when will you achieve it by?)</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td style="text-align: center">S - Specific</td>
                    <td style="text-align: center">M - Measurable</td>
                    <td style="text-align: center">AR - Achievable & Realistic</td>
                    <td style="text-align: center">T - Timebound</td>
                </tr>
                <tr>
                    <td><textarea name="specific" rows="10" cols="26">'.$form->specific.'</textarea></td>
                    <td><textarea name="measurable" rows="10" cols="26">'.$form->measurable.'</textarea></td>
                    <td><textarea name="achievable" rows="10" cols="26">'.$form->achievable.'</textarea></td>
                    <td><textarea name="timebound" rows="10" cols="26">'.$form->timebound.'</textarea></td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>
';

$html .= '
<table class="table1" style="width: 900px">
    <thead>
        <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Date of next contact: </th><th style="text-align: center">'.Date::toShort($form->next_contact).'</th></tr>
    </thead>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Learner Comments</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>(to include general comments on course, feedback on how they feel it is going, new skills developed)</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="learner_comment" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->learner_comment.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Employer Progress Review</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Please complete the following section to review your apprentice\'s progress in their apprenticeship.<br>How does your apprentice contribute to your team/business?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="employer_progress_review" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->employer_progress_review.'</textarea></td>
    </tr>
    <tr>
        <td colspan=4>Please tick the following (this is to support your apprentice to maintain/improve behaviour).</td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td style="width: 200px; text-align: center">&nbsp;</td>
                    <td style="width: 200px; text-align: center">Poor</td>
                    <td style="width: 200px; text-align: center">Satisfactory</td>
                    <td style="width: 200px; text-align: center">Good</td>
                    <td style="width: 200px; text-align: center">Excellent</td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Attendance</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("attendance_poor", 1, $form->attendance_poor, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("attendance_satisfactory", 1, $form->attendance_satisfactory, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("attendance_good", 1, $form->attendance_good, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("attendance_excellent", 1, $form->attendance_excellent, true, false).'</td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Punctuality/Timekeeping</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("punctuality_poor", 1, $form->punctuality_poor, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("punctuality_satisfactory", 1, $form->punctuality_satisfactory, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("punctuality_good", 1, $form->punctuality_good, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("punctuality_excellent", 1, $form->punctuality_excellent, true, false).'</td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Attitude</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("attitude_poor", 1, $form->attitude_poor, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("attitude_satisfactory", 1, $form->attitude_satisfactory, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("attitude_good", 1, $form->attitude_good, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("attitude_excellent", 1, $form->attitude_excellent, true, false).'</td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Communication</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("communication_poor", 1, $form->communication_poor, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("communication_satisfactory", 1, $form->communication_satisfactory, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("communication_good", 1, $form->communication_good, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("communication_excellent", 1, $form->communication_excellent, true, false).'</td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Enthusiasm</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("enthusiasm_poor", 1, $form->enthusiasm_poor, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("enthusiasm_satisfactory", 1, $form->enthusiasm_satisfactory, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("enthusiasm_good", 1, $form->enthusiasm_good, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("enthusiasm_excellent", 1, $form->enthusiasm_excellent, true, false).'</td>
                </tr>
                <tr>
                    <td style="width: 200px; text-align: left">Commitment to the role</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("commitment_poor", 1, $form->commitment_poor, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("commitment_satisfactory", 1, $form->commitment_satisfactory, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("commitment_good", 1, $form->commitment_good, true, false).'</td>
                    <td style="width: 200px; text-align: center">'.HTML::checkbox("commitment_excellent", 1, $form->commitment_excellent, true, false).'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Please record here any comments you would like to add regarding:</td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td>Behaviours:</td>
                    <td colspan=3><textarea name="behaviours" rows="4" cols="100">'.$form->behaviours.'</textarea></td>
                </tr>
                <tr>
                    <td>Ability:</td>
                    <td colspan=3><textarea name="ability" rows="4" cols="100">'.$form->ability.'</textarea></td>
                </tr>
                <tr>
                    <td>Skills and Knowledge:</td>
                    <td colspan=3><textarea name="skills_knowledge" rows="4" cols="100">'.$form->skills_knowledge.'</textarea></td>
                </tr>
                <tr>
                    <td>Achievements/ progress at work:</td>
                    <td colspan=3><textarea name="achievements" rows="4" cols="100">'.$form->achievements.'</textarea></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Your comments are important and any development areas will be set as objectives for your apprentice</td>
    </tr>
    </tbody>
</table>
<br>
';

$html .= '
<table class="table1" style="width: 900px">
    <thead>
    <tr>
        <th>&nbsp;</th>
        <th>Signature</th>
        <th>Name</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Learner</td>';
                if($form->signature_learner_font!='')
                    $html.= '<td><div onclick="getSignature(2)" class = "sigbox" width="300px" id="LearnerSignatureDiv"><img id = "learner_signature" src = "' . $learner_signature_url .  '" height="49" width="285"/></div></td>';
                else
                    $html.='<td>&nbsp;</td>';
        $html.='<td style="text-align: center">'.$form->signature_learner_name.'</td>
        <td style="text-align: center">'.Date::toShort($form->signature_learner_date).'</td>
    </tr>
    <tr>
        <td>Reviewer</td>';
                if($form->signature_assessor_font!='')
                    $html.='<td><div class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = "' . $assessor_signature_url .  '" height="49" width="285"/></div></td>';
                else
                    $html.='<td>&nbsp;</td>';

        $html.='<td style="text-align: center">'.$form->signature_assessor_name.'</td>
        <td style="text-align: center">'.Date::toShort($form->signature_assessor_date).'</td>
    </tr>
    <tr>
        <td>Supervisor/ Company Contact:</td>';
                if($form->signature_employer_font!='')
                    $html.='<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = "' . $employer_signature_url .  '" height="49" width="285"/></div></td>';
                else
                    $html.='<td>&nbsp;</td>';
        $html.='<td style="text-align: center">'.$form->signature_employer_name.'</td>
        <td style="text-align: center">'.Date::toShort($form->signature_employer_date).'</td>
    </tr>
    </tbody>
</table>
<br>
';
//==============================================================
//==============================================================
//==============================================================

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $stylesheet = file_get_contents('./MPDF57/examples/baltic.css');
            $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $form->review_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }

        include('tpl_edit_assessor_review_form.php');
    }

    private function save_signatures($link, $form,$tr_id,$review_id)
    {
        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $username = $tr->username;
        $db=DB_NAME;
        if(!file_exists(DATA_ROOT."/uploads/$db"))
        {
            mkdir(DATA_ROOT."/uploads/$db");
        }
        if(!file_exists(DATA_ROOT."/uploads/$db/$username"))
        {
            mkdir(DATA_ROOT."/uploads/$db/$username");
        }
        if(!file_exists(DATA_ROOT."/uploads/$db/$username/signatures"))
        {
            mkdir(DATA_ROOT."/uploads/$db/$username/signatures");
        }
        if(!file_exists(DATA_ROOT."/uploads/$db/$username/signatures/$review_id"))
        {
            mkdir(DATA_ROOT."/uploads/$db/$username/signatures/$review_id");
        }

        $signature_learner_font = explode("&",$form->signature_learner_font);
        $size = substr($signature_learner_font[3],strpos($signature_learner_font[3],"=")+1);
        $font = substr($signature_learner_font[2],strpos($signature_learner_font[2],"=")+1);
        $text = str_replace("%20"," ",substr($signature_learner_font[1],strpos($signature_learner_font[1],"=")+1));
        $im = imagecreatetruecolor(285,49);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 285, 49, $white);
        Imagettftext($im, $size, 0, 25, 35, $black, ("./fonts/".$font), $text);
        $target_directory = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/learner_signature.png";
        imagepng($im,$target_directory,0,NULL);

        $signature_assessor_font = explode("&",$form->signature_assessor_font);
        $size = substr($signature_assessor_font[3],strpos($signature_assessor_font[3],"=")+1);
        $font = substr($signature_assessor_font[2],strpos($signature_assessor_font[2],"=")+1);
        $text = str_replace("%20"," ",substr($signature_assessor_font[1],strpos($signature_assessor_font[1],"=")+1));
        $im = imagecreatetruecolor(285,49);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 285, 49, $white);
        Imagettftext($im, $size, 0, 25, 35, $black, ("./fonts/".$font), $text);
        $target_directory = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/assessor_signature.png";
        imagepng($im,$target_directory,0,NULL);

        $signature_employer_font = explode("&",$form->signature_employer_font);
        $size = substr($signature_employer_font[3],strpos($signature_employer_font[3],"=")+1);
        $font = substr($signature_employer_font[2],strpos($signature_employer_font[2],"=")+1);
        $text = str_replace("%20"," ",substr($signature_employer_font[1],strpos($signature_employer_font[1],"=")+1));
        $im = imagecreatetruecolor(285,49);
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 285, 49, $white);
        Imagettftext($im, $size, 0, 25, 35, $black, ("./fonts/".$font), $text);
        $target_directory = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/employer_signature.png";
        imagepng($im,$target_directory,0,NULL);

    }

}
?>