<?php
class ITInfrastructureTechnicianV4
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        $html="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th></tr>
    </thead><tbody>
    <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td rowspan=3>Communication</td>
        <td>Work both independently and as part of a team and follow your organisations standards</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $ss_result['Communication'] . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Communication'])?$Assessment_Plan['Communication']:"";
        $html.="<td rowspan=3 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr><td>Able to communicate both in writing and orally at all levels</td></tr>
    <tr><td>Use a range of tools and demonstrate strong interpersonal skills and cultural awareness when dealing with colleagues, customers and clients during all tasks.</td>
    </tr>
    <tr>
        <td>IT Security</td>
        <td>Securely operate across all platforms and areas of responsibilities in line with organisations guidance and legislation</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['IT Security'] . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        $it_security = isset($Assessment_Plan['IT Security'])?$Assessment_Plan['IT Security']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
    </tr>
    <tr>
        <td>Remote Infrastructure</td>
        <td>Operate a range of mobile devices and securely add them to a network in accordance with organisations policies and procedures</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Remote Infrastructure'] . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        $remote_infrastructure = isset($Assessment_Plan['Remote Infrastructure'])?$Assessment_Plan['Remote Infrastructure']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
    </tr>
    <tr>
        <td>Data</td>
        <td>Record, analyse and communicate data at the appropriate level using the organisation's standard tools and processes, to all stakeholders within the responsibility of the position</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Data'] . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        $data = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
    </tr>
    <tr>
        <td rowspan=2>Problem Solving</td>
        <td>Apply structured techniques to common and non-routine problems, testing methodologies</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $ss_result['Problem Solving'] . "</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        $problem_solving = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"";
        $html.="<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>
    </tr>
    <tr><td>Troubleshoot and analyse problems by selecting the digital appropriate tools and techniques in line with organisation guidance and to obtain the relevant logistical support as required</td></tr>
    </tr>
    <tr>
        <td>Workflow Management</td>
        <td>Work flexibly and have the ability to work under pressure to progress allocated tasks in accordance with the organisation’s reporting and quality systems</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Workflow Management'] . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        $workflow_management = isset($Assessment_Plan['Workflow management'])?$Assessment_Plan['Workflow management']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>
    </tr>
    <tr>
        <td>Health and Safety</td>
        <td>Interpret and follow IT legislation to securely and professional work productively</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Health and Safety'] . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        $health_safety = isset($Assessment_Plan['Health and Safety'])?$Assessment_Plan['Health and Safety']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
    </tr>
    <tr>
        <td rowspan=2>Performance</td>
        <td>Optimise the performance of hardware, software and Network Systems and services in line with business requirements</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $ss_result['Performance'] . "</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Performance'])?$Assessment_Plan['Performance']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr><td>Explain the correct processes associated with WEEE (the Waste Electrical and Electronic Equipment Directive)</td>";
        $weee = isset($Assessment_Plan['WEEE'])?$Assessment_Plan['WEEE']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $weee . "</td>
    </tr></tbody>
</table>";
$html.="<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
    </thead><tbody>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td>A range of cabling and connectivity, the various types of antennas and wireless systems and IT test equipment (Networking MTA)</td>";
        $tk = isset($tk_result['9628-06 Networking and Architecture Test'])?$tk_result['9628-06 Networking and Architecture Test']:"";
        $html.="<td rowspan=5 style='text-align:center; vertical-align:middle'>" . $tk . "</td>
        <td rowspan=5 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>
        <td rowspan=5 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'9628-06 Networking and Architecture Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-06 Networking and Architecture Test') . "</td>
    </tr>
    <tr><td>Maintenance  processes and applying them in working practices (Networking MTA)</td></tr>
    <tr><td>Applying the basic elements and architecture of computer systems (Networking MTA)</td>
    <tr><td>Where to apply the relevant numerical skills e.g. Binary (Networking MTA)</td>
    <tr><td>Networking skills necessary to maintain a secure network (Networking MTA)</td>
    </tr>
    <tr>
        <td>Similarities, differences and benefits of the current Operating Systems available (Mobility & Devices MTA)</td>";
        $tk = isset($tk_result['Mobility and Devices MTA Test'])?$tk_result['Mobility and Devices MTA Test']:"";
        $html.="<td rowspan=2 style='text-align:center; vertical-align:middle'>" . $tk . "</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Mobility and Devices MTA Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Mobility and Devices MTA Test') . "</td>
    </tr>
    <tr><td>How to operate remotely and how to deploy and securely integrate mobile devices (Mobility & Devices MTA)</td></tr>
    </tr>
    <tr>
        <td>Similarities and differences between a range of coding and logic (Coding & Logic)</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['9268-09 Coding and Logic Test'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'9628-09 Coding and Logic Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-09 Coding and Logic Test') . "</td>
    </tr>
    <tr>
        <td>Business processes (Business Processes)</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $tk_result['9628-10 ITIL Foundation Test'] . "</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'City and Guilds 9628-10 Level 3 Award in Business Processes Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'City and Guilds 9628-10 Level 3 Award in Business Processes Test') . "</td>
    </tr>
    <tr><td>Business IT skills relevant to the organization (Business Processes)</td></tr>
    </tr></tbody>
</table>
<br>";
$html.="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
    </thead><tbody>
    <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td>Logical and creative thinking skills</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Data","Problem Solving","Workflow Management")))
        $html .="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Analytical and problem solving skills</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Analytical and problem solving skills'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Data","Problem Solving")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Ability to work independently and to take responsibility</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work independently and to take responsibility'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Communication","IT Security","Remote Infrastructure","Data","Problem Solving","Workflow Management","Health and Safety","Performance","WEEE")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html."<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Can use own initiative</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Can use own initiative'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Communication","IT Security","Remote Infrastructure","Data","Problem Solving","Workflow Management","Health and Safety","Performance","WEEE")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>A thorough and organised approach</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A thorough and organised approach'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("IT Security","Problem Solving","Workflow Management","Performance","WEEE")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Ability to work with a range of internal and external people</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work with a range of internal and external people'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Problem Solving","Health and Safety","Performance","WEEE","Communication")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Ability to communicate effectively in a variety of situations</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to communicate effectively in a variety of situations'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Communication")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Maintain productive, professional and secure working environment</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Maintain productive, professional and secure working environment'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Communication","IT Security","Remote Infrastructure","Data","Problem Solving","Workflow Management","Health and Safety","Performance","WEEE")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th></tr>
    </thead><tbody>
    <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Communication</td>
        <td>"; if(isset($Assessment_Plan['Communication'])) $html.= $Assessment_Plan['Communication']; $html.="</td>
        <td>Health & Safety</td>
        <td>"; if(isset($Assessment_Plan['Health and Safety'])) $html.= $Assessment_Plan['Health and Safety']; $html.="</td>
        <td>Remote Infrastructure</td>
        <td>"; if(isset($Assessment_Plan['Remote Infrastructure'])) $html.= $Assessment_Plan['Remote Infrastructure']; $html.="</td>
    </tr>
    <tr>
        <td>Data</td>
        <td>"; if(isset($Assessment_Plan['Data'])) $html.= $Assessment_Plan['Data']; $html.="</td>
        <td>Workflow Management</td>
        <td>"; if(isset($Assessment_Plan['Workflow management'])) $html.= $Assessment_Plan['Workflow management']; $html.="</td>
        <td>IT Security</td>
        <td>"; if(isset($Assessment_Plan['IT Security'])) $html.=$Assessment_Plan['IT Security']; $html.="</td>
    </tr>
    <tr>
        <td>Problem Solving</td>
        <td>"; if(isset($Assessment_Plan['Problem solving'])) $html.=$Assessment_Plan['Problem solving']; $html.="</td>
        <td>Performance</td>
        <td>"; if(isset($Assessment_Plan['Performance'])) $html.=$Assessment_Plan['Performance']; $html.="</td>
        <td>WEEE</td>
        <td>"; if(isset($Assessment_Plan['WEEE'])) $html.=$Assessment_Plan['WEEE']; $html.="</td>
    </tr>
    <tr>
        <td colspan=6>Record here the detail of the progress. What has the learner been doing towards completing this and discuss employer reference progress</td>
    </tr>
    <tr>
        <td colspan=6><i>
            " . $form_arf->workplace_competence . "
        </i></td>
    </tr></tbody>
</table>";

        return $html;
    }

    public static function getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $html="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th></tr>
    </thead><tbody>
    <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
    <tr>
        <td>Mobility and devices MTA</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Mobility and Devices MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Mobility and Devices MTA') . "</td>
        <td>Mobility and devices MTA test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Mobility and Devices MTA Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Mobility and Devices MTA Test') . "</td>
        <td>Networking and Architecture</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Networking and Architecture") . "<br>" . ReviewSkillsScans::getEventDate($events,'Networking and Architecture') . "</td>
    </tr>
    <tr>
        <td>9628-06 Networking and Architecture Test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "9628-06 Networking and Architecture Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-06 Networking and Architecture Test') . "</td>
        <td>Business Processes</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Business Processes") . "<br>" . ReviewSkillsScans::getEventDate($events,'Business Processes') . "</td>
        <td>City & Guilds 9628-10 Level 3 Award in Business Processes Test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "City and Guilds 9628-10 Level 3 Award in Business Processes Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'City and Guilds 9628-10 Level 3 Award in Business Processes Test') . "</td>
    </tr>
    <tr>
        <td>Coding and logic</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Coding and Logic") . "<br>" . ReviewSkillsScans::getEventDate($events,'Coding and Logic') . "</td>
        <td>9628-09 coding and logic test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "9628-09 Coding and Logic Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-09 Coding and Logic Test') . "</td>
        <td>Windows Server Fundamentals MTA</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Windows Server Fundamentals MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Windows Server Fundamentals MTA') . "</td>
    </tr>
    <tr>
        <td>Windows Server Fundamentals MTA Test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Windows Server Fundamentals MTA Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Windows Server Fundamentals MTA Test') . "</td>
        <td>Functional Skills English</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills English") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills English') . "</td>
        <td>Functional Skills Mathematics</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills Mathematics") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills Mathematics') . "</td>
    </tr>
    <tr>
        <td>Functional Skills Mathematics Test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills Mathematics Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills Mathematics Test') . "</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=6>Record here the detail of the progress. What has the learner been doing towards completing this?</td>
    </tr>
    <tr>
        <td colspan=6><i>
            " . $form_arf->knowledge_modules . "
        </i></td>
    </tr></tbody>
</table>
<br>";
        return $html;
    }
}
?>