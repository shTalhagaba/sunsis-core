<?php
class fap_review_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $meeting_date = isset($_REQUEST['meeting_date']) ? $_REQUEST['meeting_date'] : '';
        $source = isset($_REQUEST['source']) ? $_REQUEST['source'] : '';
        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
        $output = isset($_REQUEST['output']) ? $_REQUEST['output'] : '';

/*        if($source==1)
            $_SESSION['bc']->add($link, "do.php?_action=read_training_record&id=$tr_id", "Training Record");*/

        $keytoverify = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
        if($source==2 or $source==3)
            if($key!=$keytoverify)
                pre("Invalid URL");


        $training_record = TrainingRecord::loadFromDatabase($link,$tr_id);
        $form = FAPReviewForm::loadFromDatabase($link,$review_id);

        if(Date::isDate($form->review_date))
            $review_date = new Date($form->review_date);
        else
            $review_date = new Date(date('d-m-Y'));


        $review_programme_title = DAO::getSingleValue($link, "select review_programme_title from courses inner join courses_tr on courses_tr.course_id = courses.id and courses_tr.tr_id = '$tr_id'");
        if($form->learner_programme=='')
            $form->learner_programme=$review_programme_title;


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
        $learner = User::loadFromDatabase($link,$training_record->username);
        $crm_contact = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
        if($form->learner_name=='')
            $form->learner_name=$training_record->firstnames . ' ' . $training_record->surname;
        if($form->learner_assessor=='')
            $form->learner_assessor=$_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname ;

        if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
            $client = "Baltic";
        else
            $client = "Perspective";

        if($training_record->getProgType($link)=='25' and $review_date->after('02/11/2017'))
        {
            if($output=='PDF')
            {

                $this->save_signatures($link,$form,$tr_id,$review_id);
                $db = DB_NAME;
                $username=$training_record->username;
                $assessor_signature_url = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/assessor_signature.png";

                $html = '
<table style="width: 900px">
    <tr>
        <td>
            <table class="table1">
                <thead>
                <tr><th style="width: 800px">&nbsp;&nbsp;&nbsp;Assessment Plan Support Session</th></tr>
                </thead>
            </table>
        </td>
        <td>';
                if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
                    $html .= '<img height = "100" width = "80" src="img/baltic_assessor_review.jpg">';
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
        <td>Learner:</td>
        <td>'.$form->learner_name.'</td>
        <td>Programme:</td>
        <td>'.$form->learner_programme.'</td>
    </tr>
    <tr>
        <td>Assessor:</td>
        <td>'.$form->learner_assessor.'</td>
        <td>Date:</td>
        <td>'.Date::toShort($form->review_date).'</td>
    </tr>
    </tbody>
</table>
<br>
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Feedback Summary Notes</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Review Employer comments from previous review, discuss and set improvement activities if applicable.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="feedback_summary_notes" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->feedback_summary_notes.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Learner comments regarding above employer comments.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="general_feedback" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->general_feedback,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Does the learner have any concerns or wish to raise any Safeguarding, Equality & Diversity, Health & wellbeing, Radicalisation or Health & Safety queries?.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="equality_diversity" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->equality_diversity,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
<br>
<br>
<br>
<br>
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Apprenticeship Commitment</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Are there any issues or anything you would like to discuss or disclose which could prevent you completing your apprenticeship?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="commitment" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->commitment,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
<br>
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Additional Support Requirements</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Is there any additional support you would like from Baltic Training or your Line Manager?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="main_feedback" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->main_feedback,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>';

$html .= '<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;COMPETENCE PROGRESS FOR APPRENTICES COMPLETING STANDARDS</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Progress Summary: Workplace Competence</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td width=200px>&nbsp;Competence&nbsp;</td>
                    <td width=200px>&nbsp;&nbsp;</td>
                    <td width=200px>&nbsp;Competence&nbsp;</td>
                    <td width=200px>&nbsp;&nbsp;</td>
                    <td width=200px>&nbsp;Competence&nbsp;</td>
                    <td width=200px>&nbsp;&nbsp;</td>
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
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
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
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
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
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;Competence&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
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

$html.='<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;COMPETENCE PROGRESS FOR APPRENTICES COMPLETING FRAMEWORK</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Progress Summary: Work Place Competence</td>
    </tr>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td>&nbsp;102&nbsp;</td>
                    <td>&nbsp;304/404&nbsp;</td>
                    <td>&nbsp;Professional Discussion&nbsp;</td>
                    <td>&nbsp;Projects/ Statements&nbsp;</td>
                </tr>
                <tr>
                    <td>'.$form->knowledge_module_1.'</td>
                    <td>'.$form->knowledge_status_1.'</td>
                    <td>'.$form->knowledge_module_2.'</td>
                    <td>'.$form->knowledge_status_2.'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4><textarea name="knowledge_module" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->knowledge_module.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Date of next contact: </th><th style="text-align: center">'.Date::toShort($form->next_contact).'</th></tr>
    </thead>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Objectives for next session</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><textarea name="next_objectives" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->next_objectives.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Assessor Overview</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Detail the call in terms of progress, work set and actions to be achieved</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="tech_feedback" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->tech_feedback,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Contact</th></tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <ul>
                <li>E-mail any completed work to assessing@'.$client.'training.com before your next session.</li>
                <li>Email Address : ';
                if(isset($_SESSION['user']))
                    $html .= $_SESSION['user']->work_email;

                $html .= '</li></ul>
        </td>
    </tr>
    </tbody>
</table>
<br>


<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=6>&nbsp;Assessor</th></tr>
    </thead>
    <tbody>
    <tr>
        <td>Name</td>
        <td style="text-align: center">'.$form->signature_assessor_name.'</td>
        <td>Signature</td>';
                if($form->signature_assessor_font!='')
                    $html .= '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = "' . $assessor_signature_url .  '" height="49" width="285"/></div></td>';
                else
                    $html .= '<td>&nbsp;</td>';
                $html.='<td>Date</td>
        <td style="text-align: center">'.Date::toShort($form->signature_assessor_date).'</td>
    </tr>
    </tbody>
</table>
<br>

<table style="width: 900px">
    <tr>
        <td style="text-align: center;">';
                if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
                    $html.= '<img height = "100" width = "80" src="img/baltic_assessor_review.jpg">';
                else
                    $html.='<img height = "100" width = "80" src="images/sunesislogo.gif">';
                $html.='</td>
    </tr>
</table>
<br>';

                if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
                    $html.='<table style="width: 900px">
    <tr>
        <td style="text-align: center; color: #00539F"><b>Baltic Training Services</b></td>
    </tr>
    <tr>
        <td style="text-align: center; color: #00539F">Baltic House, Hilton Road, Aycliffe Business Park, Newton Aycliffe, DL5 6EN</td>
    </tr>
    <tr>
        <td style="text-align: center; color: #00539F">T | 01325731050 W | www.baltictraining.com TW | @baltictraining F | facebook.com/baltictraining</td>
    </tr>
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

        }
        else
        {
            if($output=='PDF')
            {

                $this->save_signatures($link,$form,$tr_id,$review_id);
                $db = DB_NAME;
                $username=$training_record->username;
                $assessor_signature_url = DATA_ROOT."/uploads/$db/$username/signatures/$review_id/assessor_signature.png";

                $html = '
<table style="width: 900px">
    <tr>
        <td>
            <table class="table1">
                <thead>
                <tr><th style="width: 800px">&nbsp;&nbsp;&nbsp;Feedback Action Plan</th></tr>
                </thead>
            </table>
        </td>
        <td>';
                if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
                    $html .= '<img height = "100" width = "80" src="img/baltic_assessor_review.jpg">';
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
        <td>Learner:</td>
        <td>'.$form->learner_name.'</td>
        <td>Programme:</td>
        <td>'.$form->learner_programme.'</td>
    </tr>
    <tr>
        <td>Assessor:</td>
        <td>'.$form->learner_assessor.'</td>
        <td>Date:</td>
        <td>'.Date::toShort($form->review_date).'</td>
    </tr>
    </tbody>
</table>
<br>
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Feedback Summary Notes</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Review Employer comments from previous review, discuss and set improvement activities if applicable.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="feedback_summary_notes" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->feedback_summary_notes.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;General Feedback</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>This is to include information of what the learner has been doing in the workplace, referencing training received from ' . $client . '.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="general_feedback" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->general_feedback,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Significant Achievement over the past 4 weeks</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Learner to identify best achievement – for example a piece of work, team contribution, Apprentice of the month or learner of the week nomination.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="significant_achievement" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->significant_achievement,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Safeguarding, Equality & Diversity and Health & Safety</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Does the learner have any concerns or wish to raise any Safeguarding, Equality & Diversity or Health & Safety queries.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="equality_diversity" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->equality_diversity,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
<br>
<br>
<br>
<br>
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Apprenticeship Commitment</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Are there any issues or anything you would like to discuss or disclose which could prevent you completing your 12 month apprenticeship?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="commitment" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->commitment,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
<br>
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Functional Skills: English, Maths and ICT Level 2</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>
            <table>
                <tr>
                    <td style="text-align: center;">Target Completion Date</td>
                    <td style="text-align: center; width: 70px">English</td><td style="text-align: center; width: 100px">'.$form->english_exempt.'</td>
                    <td style="text-align: center; width: 70px">Maths</td><td style="text-align: center; width: 100px">'.$form->math_exempt.'</td>
                    <td style="text-align: center; width: 70px">ICT</td><td style="text-align: center; width: 100px">'.$form->ict_exempt.'</td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>
<br>
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Functional Skills Feedback</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Insert information from induction task, tasks  set by tutors, IA results and assessor feedback.</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="functional_feedback" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->functional_feedback,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
<br>
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
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->main_name_unit1.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->main_name_unit2.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->main_name_unit3.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->main_name_unit4.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->main_name_unit5.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->main_name_unit6.'</td>
                </tr>
                <tr>
                    <td style="text-align: center; width: 100px" colspan=2>'.$form->main_perc_unit1.'</td>
                    <td style="text-align: center; width: 100px" colspan=2>'.$form->main_perc_unit2.'</td>
                    <td style="text-align: center; width: 100px" colspan=2>'.$form->main_perc_unit3.'</td>
                    <td style="text-align: center; width: 100px" colspan=2>'.$form->main_perc_unit4.'</td>
                    <td style="text-align: center; width: 100px" colspan=2>'.$form->main_perc_unit5.'</td>
                    <td style="text-align: center; width: 100px" colspan=2>'.$form->main_perc_unit6.'</td>
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
                    <td style="text-align: center">Workshop 1</td>
                    <td style="text-align: center">Workshop 2</td>
                    <td style="text-align: center">Workshop 3</td>
                </tr>
                <tr>
                    <td style="text-align: center; width: 306px">'.$form->workshop1.'</td>
                    <td style="text-align: center; width: 306px">'.$form->workshop2.'</td>
                    <td style="text-align: center; width: 306px">'.$form->workshop3.'</td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>
<br>
<br>
<br>
<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;General Feedback</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Include information from assessment feedback e-mails, did they pass? Was there rework? Are they aware of how to do the rework? Do they need support? Is all evidence ready for workshops?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="main_feedback" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->main_feedback,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>
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
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->tech_name_unit1.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->tech_name_unit2.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->tech_name_unit3.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->tech_name_unit4.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->tech_name_unit5.'</td>
                    <td>&nbsp;Unit&nbsp;</td>
                    <td style="text-align: center; width: 100px">'.$form->tech_name_unit6.'</td>
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
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;General Feedback</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Include feedback from confirmation emails from tech cert grades following training. Discuss the training course, did they enjoy it? What did they learn?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="tech_feedback" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.mb_convert_encoding($form->tech_feedback,'UTF-8').'</textarea></td>
    </tr>
    </tbody>
</table>
<br>';

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

                $html.='<table class="table1" style="width: 900px">
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

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Learner Log</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>General Feedback - Check what is being taught in the workplace, has it been documented on the log?  How many hours have they documented?</td>
    </tr>
    <tr>
        <td colspan=4><textarea name="learner_log" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->learner_log.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Date of next contact: </th><th style="text-align: center">'.Date::toShort($form->next_contact).'</th></tr>
    </thead>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Objectives for next session</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><textarea name="next_objectives" style="font-family:sans-serif; font-size:10pt"  rows="10" cols="123">'.$form->next_objectives.'</textarea></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Contact</th></tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <ul>
                <li>E-mail any completed work to assessing@'.$client.'training.com before your next session.</li>
                <li>Email Address : ';
                if(isset($_SESSION['user']))
                    $html .= $_SESSION['user']->work_email;

                $html .= '</li></ul>
        </td>
    </tr>
    </tbody>
</table>
<br>


<table class="table1" style="width: 900px">
    <thead>
        <tr><th colspan=4>&nbsp;Learner</th></tr>
    </thead>
    <tbody>
    <tr>
        <td>Print Name</td>
        <td style="text-align: center">'.$training_record->firstnames . ' ' . $training_record->surname.'</td>
    </tr>
    </tbody>
</table>

<table class="table1" style="width: 900px">
    <thead>
    <tr><th colspan=6>&nbsp;Assessor</th></tr>
    </thead>
    <tbody>
    <tr>
        <td>Name</td>
        <td style="text-align: center">'.$form->signature_assessor_name.'</td>
        <td>Signature</td>';
                if($form->signature_assessor_font!='')
                    $html .= '<td><div onclick="getSignature(1)" class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = "' . $assessor_signature_url .  '" height="49" width="285"/></div></td>';
                else
                    $html .= '<td>&nbsp;</td>';
                $html.='<td>Date</td>
        <td style="text-align: center">'.Date::toShort($form->signature_assessor_date).'</td>
    </tr>
    </tbody>
</table>
<br>

<table style="width: 900px">
    <tr>
        <td style="text-align: center;">';
                if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
                    $html.= '<img height = "100" width = "80" src="img/baltic_assessor_review.jpg">';
                else
                    $html.='<img height = "100" width = "80" src="images/sunesislogo.gif">';
                $html.='</td>
    </tr>
</table>
<br>';

                if(DB_NAME=='am_baltic' || DB_NAME=='am_baltic_demo')
                    $html.='<table style="width: 900px">
    <tr>
        <td style="text-align: center; color: #00539F"><b>Baltic Training Services</b></td>
    </tr>
    <tr>
        <td style="text-align: center; color: #00539F">Baltic House, Hilton Road, Aycliffe Business Park, Newton Aycliffe, DL5 6EN</td>
    </tr>
    <tr>
        <td style="text-align: center; color: #00539F">T | 01325731050 W | www.baltictraining.com TW | @baltictraining F | facebook.com/baltictraining</td>
    </tr>
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

        }



        if($training_record->getProgType($link)=='25' and $review_date->after('02/11/2017'))
            include('tpl_edit_fap_review_form_standard.php');
        else
            include('tpl_edit_fap_review_form.php');

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

    }


}
?>