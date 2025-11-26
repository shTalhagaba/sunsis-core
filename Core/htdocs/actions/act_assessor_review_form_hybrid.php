<?php
class assessor_review_form_hybrid implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        pre("Invalid link");
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $meeting_date = isset($_REQUEST['meeting_date']) ? $_REQUEST['meeting_date'] : '';
        $source = isset($_REQUEST['source']) ? $_REQUEST['source'] : '';
        $review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
        $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
        $output = isset($_REQUEST['output']) ? $_REQUEST['output'] : '';


        $keytoverify = md5("PerspectiveSunesistr_id=".$tr_id."source=".$source."review_id=".$review_id);
        if($source==2 or $source==3)
            if($key!=$keytoverify)
                pre("Invalid URL");

        $review_programme_title = DAO::getSingleValue($link, "select title from courses inner join courses_tr on courses_tr.course_id = courses.id and courses_tr.tr_id = '$tr_id'");
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
        $form_learner = AssessorReviewFormLearner::loadFromDatabase($link,$review_id);
        $form_assessor1 = AssessorReviewFormAssessor1::loadFromDatabase($link,$review_id);
        $form_assessor2 = AssessorReviewFormAssessor2::loadFromDatabase($link,$review_id);
        $form_assessor3 = AssessorReviewFormAssessor3::loadFromDatabase($link,$review_id);
        $form_assessor4 = AssessorReviewFormAssessor4::loadFromDatabase($link,$review_id);
        $form_employer = AssessorReviewFormEmployer::loadFromDatabase($link,$review_id);

        if($source==2 and $form_learner->signature_learner_font!='')
            pre("Review is complete");

        if($source==3 and $form_employer->signature_employer_font!='')
            pre("Review is complete");

        // Auto populate date
        if($source==1)
        {
            if($form_assessor4->signature_assessor_date=='')
                $form_assessor4->signature_assessor_date = date('Y-m-d');
        }
        elseif($source==2)
        {
            if($form_learner->signature_learner_date=='')
                $form_learner->signature_learner_date = date('Y-m-d');
        }
        elseif($source==3)
        {
            if($form_employer->signature_employer_date=='')
                $form_employer->signature_employer_date = date('Y-m-d');
        }

        $learner = User::loadFromDatabase($link,$training_record->username);
        if(isset($training_record->crm_contact_id))
            $crm_contact = EmployerContacts::loadFromDatabase($link,$training_record->crm_contact_id);
        else
            $crm_contact = new EmployerContacts();
        if($form_assessor1->learner_programme=='')
            $form_assessor1->learner_programme=$review_programme_title;
        if($form_assessor1->learner_qualification=='')
            $form_assessor1->learner_qualification=$review_programme_title;
        if($form_assessor1->learner_name=='')
            $form_assessor1->learner_name=$training_record->firstnames . ' ' . $training_record->surname;
        if($form_assessor1->learner_dob=='')
            $form_assessor1->learner_dob=$training_record->dob;
        if($form_assessor1->learner_assessor=='')
            $form_assessor1->learner_assessor=$_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname ;
        if($form_assessor1->learner_ni=='')
            $form_assessor1->learner_ni=$training_record->ni;
        if($form_assessor1->learner_employer=='')
            $form_assessor1->learner_employer=$employer->legal_name;
        if($form_assessor1->start_date=='')
            $form_assessor1->start_date=$training_record->start_date;
        if($form_assessor1->planned_end_date=='')
            $form_assessor1->planned_end_date=$training_record->target_date;
        if($form_assessor1->registration_number=='')
            $form_assessor1->registration_number = $learner->enrollment_no;
        if(isset($crm_contact))
            if($form_assessor1->learner_manager=='')
                $form_assessor1->learner_manager = $crm_contact->contact_name;

        if($output=='PDF')
        {
            // Save signature files
            $this->save_signatures($link,'',$tr_id,$review_id);
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
                <th style="width: 800px">&nbsp;&nbsp;&nbsp;Hybrid Training Learner Progress Review</th>
                </tr>
                </thead>
            </table>
        </td>
        <td>
            <img height = "80" width = "200" src="images/logos/hybrid.png">
        </td>
    </tr>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    </thead>
    <tbody>
    <tr>
        <td>Learner Name:</td>
        <td>'.$form_assessor1->learner_name.'</td>
        <td>Qualification:</td>
        <td>'. $form_assessor1->learner_qualification.'</td>
    </tr>
    <tr>
        <td>Assessor:</td>
        <td>'. $form_assessor1->learner_assessor.'</td>
        <td>Employer:</td>
        <td>'. $form_assessor1->learner_employer.'</td>
    </tr>
    <tr>
        <td>IQA:</td>
        <td>'.$form_assessor1->learner_iqa.'</td>
        <td>Funder:</td>
        <td>'. $form_assessor1->learner_funder.'</td>
    </tr>
    <tr>
        <td>Review Date:</td>
        <td>'. $form_assessor1->review_date.'</td>
        <td>Planned End Date:</td>
        <td>'.$form_assessor1->planned_date.'</td>
    </tr>

    <tr>
    <td>Framework:</td>
    <td>'.$form_assessor1->learner_framework.'</td>
    <td colspan=2>
        <table><tr><td>Time in:'.$form_assessor1->time_in.'</td>
        <td>Time out:'.$form_assessor1->time_out.'</td></tr></table>
    </td>
    </tr>
    <tr>
        <td>Type of contact</td>
        <td colspan=4>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Face to face' . HTML::radio("type_of_contact", 1, ($form_assessor1->type_of_contact==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">Remote' . HTML::radio("type_of_contact", 2, ($form_assessor1->type_of_contact==2)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">Missed visit' . HTML::radio("type_of_contact", 3, ($form_assessor1->type_of_contact==3)?true:false, true, false) . '</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>Risk band</td>
        <td colspan=4>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Red'. HTML::radio("rags", 1, ($form_assessor1->rags==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">Amber'. HTML::radio("rags", 2, ($form_assessor1->rags==2)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">Green'. HTML::radio("rags", 3, ($form_assessor1->rags==3)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
    </tr>

    </tbody>
</table>
<br>';
            $html .= '
<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Smart Targets</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=2>Have all objectives from last progression review been completed:</td>
        <td colspan=2>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes'. HTML::radio("objectives", 1, ($form_assessor1->objectives==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">No'. HTML::radio("objectives", 2, ($form_assessor1->objectives==2)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">Partial'. HTML::radio("objectives", 3, ($form_assessor1->objectives==3)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">First review'.HTML::radio("objectives", 4, ($form_assessor1->objectives==4)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4><i>
                <table><tr><td>Smart Targets to be achieved by next review:</td><td>Date to be achieved:</td></tr>
                <tr><td>'. $form_assessor2->smart_target_1 . '</td><td>'. $form_assessor2->smart_target_date_1.
                '</td></tr>
                <tr><td>'. $form_assessor2->smart_target_2 . '</td><td>'. $form_assessor2->smart_target_date_2.
                '</td></tr>
                <tr><td>'. $form_assessor2->smart_target_3 . '</td><td>'. $form_assessor2->smart_target_date_3.
                '</td></tr>
                <tr><td>'. $form_assessor2->smart_target_4 . '</td><td>'. $form_assessor2->smart_target_date_4.
                '</td></tr>
                <tr><td>'. $form_assessor2->smart_target_5 . '</td><td>'. $form_assessor2->smart_target_date_5.
                '</td></tr>
                <tr><td>'. $form_assessor2->smart_target_6 . '</td><td>'. $form_assessor2->smart_target_date_6.
                '</td></tr>
                <tr><td>'. $form_assessor2->smart_target_7 . '</td><td>'. $form_assessor2->smart_target_date_7.
                '</td></tr>
                </table>
        </i></td>
    </tr>
    </tbody>
</table>
<br>';

            $html .= '
<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Progression with qualification</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Main Aim & Sub Aims:</td>
    </tr>
    <tr>
        <td colspan=4><i>'.$form_assessor2->progression_with_qualification.'</i></td>
    </tr>
    <tr>
        <td colspan=4>
            <table><tr><td>% Main Aim:'.$form_assessor2->main_aim_percentage.'</td>
            <td>% Sub Aim:'.$form_assessor2->sub_aim_percentage.'</td>
            <td>% Combined Aim:'.$form_assessor2->combined_aim_percentage.'</td></tr></table>
        </td>
    </tr>
    <tr>
        <td colspan=4>Functional Skills:</td>
    </tr>
    <tr>
        <td colspan=4><i>'.$form_assessor3->functional_skills.'</i></td>
    </tr>
    <tr>
    <td colspan=2>
        <table>
            <tr>
                <td>Functional Skills to be completed:</td>
                <td style="text-align: center; width: 100px">English</td><td>'. HTML::checkbox("english_to_be", 1, ($form_assessor3->english_to_be==1)?true:false, true, false).'</td>
                <td style="text-align: center; width: 100px">Maths</td><td>'. HTML::checkbox("math_to_be", 1, ($form_assessor3->math_to_be==1)?true:false, true, false).'</td>
                <td style="text-align: center; width: 100px">ICT</td><td>'. HTML::checkbox("ict_to_be", 1, ($form_assessor3->ict_to_be==1)?true:false, true, false).'</td>
            </tr>
        </table>
    </td>
        <td colspan=2>
            <table>
                <tr>
                    <td>Functional Skills completed:</td>
                    <td style="text-align: center; width: 100px">English</td><td>'.HTML::checkbox("english_completed", 1, ($form_assessor3->english_completed==1)?true:false, true, false).'</td>
                    <td style="text-align: center; width: 100px">Maths</td><td>'.HTML::checkbox("math_completed", 1, ($form_assessor3->math_completed==1)?true:false, true, false).'</td>
                    <td style="text-align: center; width: 100px">ICT</td><td>'.HTML::checkbox("ict_completed", 1, ($form_assessor3->ict_completed==1)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>
';

            $html .='
<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Internal Training & Off The Job Training Undertaken:</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>'.$form_assessor3->internal_training.'</i></td>
    </tr>
    <tr>
        <td>Hours to be added:</td>
        <td>'.$form_assessor3->hours_to_be_added.'</td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;ERR & PLTS</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=2>
            <table>
                <tr>
                    <td>ERR Completed:</td>
                    <td style="width: 50px; text-align: center">Yes'.HTML::radio("err_completed", 1, ($form_assessor3->err_completed==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">No'.HTML::radio("err_completed", 2, ($form_assessor3->err_completed==2)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
        <td>PLTS Embedded/ Other:</td>'. $form_assessor3->plts_embedded.'</td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Plan for next assessment visit</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>'.$form_assessor3->plan_for_next_assessment.'</i></td>
    </tr>
    <tr>
        <td>Date and time of next visit:</td>
        <td>'.$form_assessor3->date_time_next_visit.'</td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Additional Learner Support</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>ALN:'.$form_assessor3->aln.'</td>
        <td>ASN:'.$form_assessor3->asn.'</td>
        <td>ALSN:'.$form_assessor3->alsn.'</td>
        <td>Other:'.$form_assessor3->other.'</td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=2>&nbsp;&nbsp;&nbsp;Support given since last review</th>
    <th colspan=2>&nbsp;&nbsp;&nbsp;Results of this support since last review</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=2><i>'.$form_assessor3->support_since_last_review.'</i></td>
        <td colspan=2><i>'.$form_assessor4->results_support.'</i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Welfare</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Barriers to learning and changes in personal circumstances that may an impact on learning</td>
    </tr>
    <tr>
        <td colspan=4><i>'.$form_assessor4->learner_welfare.'</i></td>
    </tr>
    <tr>
        <td>Welfare Risk Factor:</td>
        <td><table><tr>
            <td style="text-align: center; width: 100px">None</td><td>'. HTML::checkbox("welfare_none", 1, ($form_assessor4->welfare_none==1)?true:false, true, false).'</td>
            <td style="text-align: center; width: 100px">WF</td><td>'.HTML::checkbox("welfare_wf", 1, ($form_assessor4->welfare_wf==1)?true:false, true, false).'</td>
            <td style="text-align: center; width: 100px">SG</td><td>'.HTML::checkbox("welfare_sg", 1, ($form_assessor4->welfare_sg==1)?true:false, true, false).'</td>
        </tr></table>
        </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;IAG Discussed Today</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>'.$form_assessor4->iag.'</i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Health & Safety Discussed Today:</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>'.$form_assessor4->health_safety.'</i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Safeguarding & Prevent Discussed Today:</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>'.$form_assessor4->safeguarding.'</i></td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Equality & Diversity Discussed Today:</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>'.$form_assessor4->equality.'</i></td>
    </tr>
    </tbody>
</table>
<br>
';

            $html .= '
<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Assessor questions</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Is the learner on target</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes'.HTML::radio("on_track", 1, ($form_assessor4->on_track==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">No'.HTML::radio("on_track", 2, ($form_assessor4->on_track==2)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
        <td>Is the Learner E-Portfolio up to date?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes'.HTML::radio("portfolio", 1, ($form_assessor4->portfolio==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">No'.HTML::radio("portfolio", 2, ($form_assessor4->portfolio==2)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=4>If no please state how far behind or Why Portfolio is not up to date:</td>
    </tr>
    <tr>
        <td colspan=4><i>'.$form_assessor4->why_portfolio_behind.'</i></td>
    </tr>
    <tr>
        <td colspan=3>Has the Learner been involved in any Equality, Health & Safety, Safeguarding& Prevent issues since the last session?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes'.HTML::radio("issue", 1, ($form_assessor4->issue==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">No'.HTML::radio("issue", 2, ($form_assessor4->issue==2)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>Date reported:</td><td>'.$form_assessor4->date_reported.'</td><td>Case number:</td>
        <td>'.$form_assessor4->case_number.'</td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Assessor\'s Feedback</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>'.$form_assessor4->assessor_feedback.'</i></td>
    </tr>
    </tbody>
</table>
<br>

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
        <td>&nbsp;Assessor</td>';
        $html.='<td><div class = "sigbox" width="300px" id="AssessorSignatureDiv"><img id = "assessor_signature" src = "' . $assessor_signature_url .  '" height="49" width="285"/></div></td>';
        $html.='<td style="text-align: center">'.$form_assessor4->signature_assessor_name.'</td>
        <td style="text-align: center">'. $form_assessor4->signature_assessor_date .'</td>
    </tr>
    </tbody>
</table>
<br>



<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Questions</th>
    </thead>
    </tr>
    <tbody>
    <tr>
        <td colspan=3>Do you understand the appeals procedure?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes'.HTML::radio("appeals", 1, ($form_learner->appeals==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">No'.HTML::radio("appeals", 2, ($form_learner->appeals==2)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Learner Comments</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>'.$form_learner->learner_comments.'</i></td>
    </tr>
    </tbody>
</table>
<br>

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
        $html.= '<td><div onclick="getSignature(2)" class = "sigbox" width="300px" id="LearnerSignatureDiv"><img id = "learner_signature" src = "' . $learner_signature_url .  '" height="49" width="285"/></div></td>';
        $html .='<td style="text-align: center">'.$form_learner->signature_learner_name.'</td>
        <td style="text-align: center">'.$form_learner->signature_learner_date.'</td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Employer Questions</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=3>Has the learner attended work regularly since the last review?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes'.HTML::radio("attended_regularly", 1, ($form_employer->attended_regularly==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">No'.HTML::radio("attended_regularly", 2, ($form_employer->attended_regularly==2)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=3>Has there been any unauthorised absences?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes'. HTML::radio("unauthorised_absences", 1, ($form_employer->unauthorised_absences==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">No'. HTML::radio("unauthorised_absences", 2, ($form_employer->unauthorised_absences==2)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan=3>If yes please state the number of days::</td>
        <td>'.$form_employer->days_absence.'</td>
    </tr>
    <tr>
        <td colspan=3>Has there been any sick days?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Yes'.HTML::radio("sick", 1, ($form_employer->sick==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">No'. HTML::radio("sick", 2, ($form_employer->sick==2)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3">If yes please state the number of days::</td>
        <td>'.$form_employer->sick_days.'</td>
    </tr>
    <tr>
        <td colspan=3>How is the Learners Time keeping?</td>
        <td>
            <table>
                <tr>
                    <td style="width: 50px; text-align: center">Good'.HTML::radio("time_keeping", 1, ($form_employer->time_keeping==1)?true:false, true, false).'</td>
                    <td style="width: 50px; text-align: center">Requires Improvement'.HTML::radio("time_keeping", 2, ($form_employer->time_keeping==2)?true:false, true, false).'</td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Employer Comments</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>'.$form_employer->employer_comments.'</i></td>
    </tr>
    </tbody>
</table>
<br>';

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
        <td>Employer:</td>';
        $html.='<td><div onclick="getSignature(3)" class = "sigbox" width="300px" id="EmployerSignatureDiv"><img id = "employer_signature" src = "' . $employer_signature_url .  '" height="49" width="285"/></div></td>';
        $html.='<td style="text-align: center">'.$form_employer->signature_employer_name.'</td>
        <td style="text-align: center">'.$form_employer->signature_employer_date.'</td>
    </tr>
    </tbody>
</table>
<br>

<table class="table1" style="width: 900px">
    <thead>
    <tr>
    <th colspan=4>&nbsp;&nbsp;&nbsp;Other Comments/ Notes</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><i>'.$form_employer->other_comments.'</i></td>
    </tr>
    </tbody>
</table>
<br>';

//==============================================================
//==============================================================
//==============================================================

            include("./MPDF57/mpdf.php");

            $mpdf=new mPDF('D');

            $mpdf->SetDisplayMode('fullpage');

            $stylesheet = file_get_contents('./MPDF57/examples/baltic.css');
            $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

            $mpdf->WriteHTML(mb_convert_encoding($html,'UTF-8'),2);
            $filename = $training_record->firstnames . ' ' . $training_record->surname . '-' . $form_assessor1->review_date . ".pdf";
            $mpdf->Output($filename,'D');
            exit;
        }

        include('tpl_edit_assessor_review_form_hybrid.php');

    }

    // Have to sort this out
    private function save_signatures($link, $form,$tr_id,$review_id)
    {
        $form_learner = AssessorReviewFormLearner::loadFromDatabase($link,$review_id);
        $form_assessor1 = AssessorReviewFormAssessor1::loadFromDatabase($link,$review_id);
        $form_assessor2 = AssessorReviewFormAssessor2::loadFromDatabase($link,$review_id);
        $form_assessor3 = AssessorReviewFormAssessor3::loadFromDatabase($link,$review_id);
        $form_assessor4 = AssessorReviewFormAssessor4::loadFromDatabase($link,$review_id);
        $form_employer = AssessorReviewFormEmployer::loadFromDatabase($link,$review_id);
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

        $signature_learner_font = explode("&",$form_learner->signature_learner_font);
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

        $signature_assessor_font = explode("&",$form_assessor4->signature_assessor_font);
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

        $signature_employer_font = explode("&",$form_employer->signature_employer_font);
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