<?php
class ITProV2
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        $h = "<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th></tr>
    </thead><tbody>
    <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Skills Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td rowspan=1>Health & Safety in IT</td>
        <td>1. Be able to comply with relevant Health & Safety procedures</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Health & Safety in IT'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Health & Safety Statement - Unit 102'])?$Assessment_Plan['Health & Safety Statement - Unit 102']:"";
        $h .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=5>Develop own effectiveness & professionalism</td>
        <td>1. Identify own development needs and the activities needed to meet them</td>
        <td rowspan=5 style='text-align:center; vertical-align:middle'>" . $ss_result['Develop own effectiveness & professionalism'] . "</td>
        <td rowspan=5 style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
        <td rowspan=5 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-404-WB1","ITP-404-WB2","ITP-404-WB3")))
        $h .= "<td rowspan=5 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h .= "<td rowspan=5 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h .= "</tr>
    <tr><td>2. Obtain and interpret feedback from others on performance</td></tr>
    <tr><td>3. Set and agree personal goals and participate in development activities to meet them</td></tr>
    <tr><td>4. Manage own personal/professional development in order to achieve career and personal goals</td></tr>
    <tr><td>5. reflect critically on own learning</td></tr>
    </tr>
    <tr>
        <td rowspan=2>Software Installation and Upgrade</td>
        <td>1. Understand the installation/upgrade process </td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $ss_result['Software Installation and Upgrade'] . "</td>
        <td rowspan=2 style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-308-PD","ITP-308-PRE")))
        $h.="<td rowspan=2 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=2 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr><td>2. Be able to carry out or control a wide range of installations or upgrades</td></tr>
    </tr>
    <tr>
        <td rowspan=5>IT Project Management</td>
        <td>1. Understand the principles, processes, tools and techniques of project management</td>
        <td rowspan=5 style='text-align:center; vertical-align:middle'>" . $ss_result['IT Project Management'] . "</td>
        <td rowspan=5 style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
        <td rowspan=5 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-441-PRE")))
        $h.="<td rowspan=5 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=5 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr><td>2. Be able to agree the scope and objectives of a project </td></tr>
    <tr><td>3. Be able to identify the budget in order to develop a project plan</td></tr>
    <tr><td>4. Be able to implement a project plan</td></tr>
    <tr><td>5. Be able to manage a project to its conclusion</td></tr>
    </tr>
    <tr>
        <td rowspan=2>Investigating & Defining Requirements</td>
        <td>1. Be able to control the investigation of existing and proposed systems and processes</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $ss_result['Investigating & Defining Requirements'] . "</td>
        <td rowspan=2 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-405-PD","ITP-405-PRE")))
        $h.="<td rowspan=2 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=2 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr><td>2. Be able to analyse information to identify needs and constraints</td></tr>
    </tr>
    <tr>
        <td rowspan=2>Remote Support</td>
        <td>1. Understand the role of remote support in the organisation</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $ss_result['Remote Support'] . "</td>
        <td rowspan=2 style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-406-PD","ITP-Task A","ITP- Task C")))
        $h.="<td rowspan=2 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=2 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr><td>2. Be able to maintain and implement customer support requirements</td></tr>
    </tr>
    <tr>
        <td rowspan=3>Technical advice & guidance</td>
        <td>1. Be able to control the provision of technical advice and guidance</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $ss_result['Technical advice & guidance'] . "</td>
        <td rowspan=3 style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-410-PD","ITP - Task A","ITP - Task B")))
        $h.="<td rowspan=3 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=3 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr><td>2. Be able to provide reactive technical advice and guidance to customers on a range of topics</td></tr>
    <tr><td>3. Be able to provide proactive technical advice and guidance to customers</td></tr>
    </tr></tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
    </thead><tbody>
    <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
    <tr>
        <td rowspan=3>Effective Communication in Business</td>
        <td>1. Understand the importance of effective communication in business</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $tk_result['Effective Communication in Business'] . "</td>
        <td rowspan=3 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>
    <tr><td>2. Understand the importance of effective written communication in business</td></tr>
    <tr><td>3. Understand the importance of effective verbal communication in business</td></tr>
    </tr>
    <tr>
        <td rowspan=7>Systems Development</td>
        <td>1. Understand the importance of effective communication in business</td>
        <td rowspan=7 style='text-align:center; vertical-align:middle'>" . $tk_result['Systems Development'] . "</td>
        <td rowspan=7 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
        <td rowspan=7 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>
    <tr><td>2. Know how to plan systems development activities against agreed quality standards</td></tr>
    <tr><td>3. Be able to establish customer requirements</td></tr>
    <tr><td>4. Be able to establish procedures for system maintenance</td></tr>
    <tr><td>5. Understand system implementation procedures</td></tr>
    <tr><td>6. Be able to produce requirements specifications</td></tr>
    <tr><td>7. Be able to prepare for system implementation</td></tr>
    </tr>
    <tr>
        <td rowspan=4>Personal & Professional Development</td>
        <td>1. Understand how to plan for personal and professional development</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $tk_result['Personal & Professional Development'] . "</td>
        <td rowspan=4 style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>
    <tr><td>2. Understand how people learn</td></tr>
    <tr><td>3. Be able to produce personal and professional development plans</td></tr>
    <tr><td>4. Be able to make recommendations for personal and professional development</td></tr>
    </tr>
    <tr>
        <td rowspan=4>IT Virtualisation</td>
        <td>1. Understand the commercial impact and potential of virtualisation</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $tk_result['IT Virtualisation'] . "</td>
        <td rowspan=4 style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>
    <tr><td>2. Be able to design virtualisation deployments</td></tr>
    <tr><td>3. Be able to implement virtualisation deployments</td></tr>
    <tr><td>4. Be able to manage virtualisation environments</td></tr>
    </tr>
    <tr>
        <td rowspan=4>Data Communications & Networks</td>
        <td>1. Understand data communication networks and the requirement for open systems</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $tk_result['Data Communications & Networks'] . "</td>
        <td rowspan=4 style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>
    <tr><td>2. Understand the methods used for data communication</td></tr>
    <tr><td>3. Know the function and methods of control used for local area networks</td></tr>
    <tr><td>4. Understand wide area networks and internet working</td></tr>
    </tr>
    <tr>
        <td rowspan=3>Emerging Technologies</td>
        <td>1. Understand emerging technologies</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $tk_result['Emerging Technologies'] . "</td>
        <td rowspan=3 style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>
    <tr><td>2. Understand the impact of emerging technologies on society</td></tr>
    <tr><td>3. Be able to conduct research into emerging technologies</td></tr>
    </tr>
    <tr>
        <td rowspan=4>Networking Technologies</td>
        <td>1. Understand networking principles</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $tk_result['Networking Technologies'] . "</td>
        <td rowspan=4 style='text-align:center'>" . (($previous_review->skills_scan_status14!="")?$sss[$previous_review->skills_scan_status14]:"") . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>
    <tr><td>2. Understand networking components</td></tr>
    <tr><td>3. Be able to design networked systems</td></tr>
    <tr><td>4. Be able to implement and support networked systems</td></tr>
    </tr>
    <tr>
        <td rowspan=9>Installing and Configuring Windows Based Server</td>
        <td>1. Be able to deploy Windows based servers</td>
        <td rowspan=9 style='text-align:center; vertical-align:middle'>" . $tk_result['Installing and Configuring Windows Based Server'] . "</td>
        <td rowspan=9 style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
        <td rowspan=9 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
    <tr><td>2. Understand domain controllers</td></tr>
    <tr><td>3. Be able to manage user accounts</td></tr>
    <tr><td>4. Be able to implement Internet Protocol (IP)</td></tr>
    <tr><td>5. Be able to implement Dynamic Host Configuration Protocols (DHCP)</td></tr>
    <tr><td>6. Be able to implement Domain Name Systems (DNS)</td></tr>
    <tr><td>7. Be able to configure server storage</td></tr>
    <tr><td>8. Be able to manage group policy</td></tr>
    <tr><td>9. Be able to implement server virtualization</td></tr>
    </tr>
   <tr>
        <td rowspan=5>IT Project Management</td>
        <td>1. Understand the principles, processes, tools and techniques of project management</td>
        <td rowspan=5 style='text-align:center; vertical-align:middle'>" . $tk_result['IT Project Management'] . "</td>
        <td rowspan=5 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
        <td rowspan=5 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
    <tr><td>2. Be able to agree the scope and objectives of a project</td></tr>
    <tr><td>3. Be able to identify the budget in order to develop a project plan</td></tr>
    <tr><td>4. Be able to implement a project plan</td></tr>
    <tr><td>5. Be able to manage a project to its conclusion</td></tr>
    </tr></tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
    </thead><tbody>
    <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Previous Review Skill Scan Status</td><td>Current Skills Scan Status</td></tr>
    <tr>
        <td>Logical and creative thinking skills</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example1 . "
        </i></td>
    </tr>
    <tr>
        <td>Analytical and problem solving skills</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Analytical and problem solving skills'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example2 . "
        </i></td>
    </tr>
    <tr>
        <td>Ability to work independently and to take responsibility</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work independently and to take responsibility'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example3 . "
        </i></td>
    </tr>
    <tr>
        <td>Can use own initiative</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Can use own initiative'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example4 . "
        </i></td>
    </tr>
    <tr>
        <td>A thorough and organised approach</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A thorough and organised approach'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example5 . "
        </i></td>
    </tr>
    <tr>
        <td>Ability to work with a range of internal and external people</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work with a range of internal and external people'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example6 . "
        </i></td>
    </tr>
    <tr>
        <td>Ability to communicate effectively in a variety of situations</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to communicate effectively in a variety of situations'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example7 . "
        </i></td>
    </tr>
    <tr>
        <td>Maintain productive, professional and secure working environment</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Maintain productive, professional and secure working environment'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example8 . "
        </i></td>
    </tr></tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Skills Scan</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><b>What new skills, knowledge and behaviors have been developed since last review & since starting  point</b></td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->setting_work . "
        </i></td>
    </tr>
    </tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th></tr>
    </thead>
    <tbody><tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Complaint Statement - Unit 406</td>
        <td>"; if(isset($Assessment_Plan['Complaint Statement - Unit 406'])) $h.=$Assessment_Plan['Complaint Statement - Unit 406']; $h.="</td>
        <td>Health & Safety Statement - Unit 102</td>
        <td>"; if(isset($Assessment_Plan['Health & Safety Statement - Unit 102'])) $h.=$Assessment_Plan['Health & Safety Statement - Unit 102']; $h.="</td>
        <td>Pre-defined Decision - Unit 209</td>
        <td>"; if(isset($Assessment_Plan['Pre-defined Decision - Unit 209 '])) $h.= $Assessment_Plan['Pre-defined Decision - Unit 209 ']; $h.="</td>
    </tr>
    <tr>
        <td>Pre-defined Decision - Unit 308</td>
        <td>"; if(isset($Assessment_Plan['Pre-defined Decision - Unit 308'])) $h.= $Assessment_Plan['Pre-defined Decision - Unit 308']; $h.= "</td>
        <td>Pre-defined Decision - Unit 405</td>
        <td>"; if(isset($Assessment_Plan['Pre-defined Decision - Unit 405'])) $h.= $Assessment_Plan['Pre-defined Decision - Unit 405']; $h.= "</td>
        <td>Pre-defined Decision - Unit 441</td>
        <td>"; if(isset($Assessment_Plan['Pre-defined Decision - Unit 441'])) $h.= $Assessment_Plan['Pre-defined Decision - Unit 441']; $h.= "</td>
    </tr>
    <tr>
        <td>Proactive Statement - Unit 410</td>
        <td>"; if(isset($Assessment_Plan['Proactive Statement - Unit 410'])) $h.= $Assessment_Plan['Proactive Statement - Unit 410']; $h.="</td>
        <td>Professional Discussion - Unit 308</td>
        <td>"; if(isset($Assessment_Plan['Professional Discussion - Unit 308'])) $h.= $Assessment_Plan['Professional Discussion - Unit 308']; $h.= "</td>
        <td>Professional Discussion - Unit 405</td>
        <td>"; if(isset($Assessment_Plan['Professional Discussion - Unit 405'])) $h.= $Assessment_Plan['Professional Discussion - Unit 405']; $h.= "</td>
    </tr>
    <tr>
        <td>Professional Discussion - Unit 406</td>
        <td>"; if(isset($Assessment_Plan['Professional Discussion - Unit 406'])) $h.= $Assessment_Plan['Professional Discussion - Unit 406']; $h.= "</td>
        <td>Professional Discussion - Unit 410</td>
        <td>"; if(isset($Assessment_Plan['Professional Discussion - Unit 410'])) $h.= $Assessment_Plan['Professional Discussion - Unit 410']; $h.= "</td>
        <td>Reactive Statement - Unit 410 & Unit 406</td>
        <td>"; if(isset($Assessment_Plan['Reactive Statement - Unit 410 & Unit 406'])) $h.= $Assessment_Plan['Reactive Statement - Unit 410 & Unit 406']; $h.= "</td>
    </tr>
    <tr>
        <td>Workbook 1 - Unit 404</td>
        <td>"; if(isset($Assessment_Plan['Workbook 1 - Unit 404'])) $h.= $Assessment_Plan['Workbook 1 - Unit 404']; $h.= "</td>
        <td>Workbook 2 - 403 tech cert & Unit 404</td>
        <td>"; if(isset($Assessment_Plan['Workbook 2 - 403 tech cert & Unit 404'])) $h.= $Assessment_Plan['Workbook 2 - 403 tech cert & Unit 404']; $h.= "</td>
        <td>Workbook 3 - Unit 404 & Unit 406</td>
        <td>"; if(isset($Assessment_Plan['Workbook 3 - Unit 404 & Unit 406'])) $h.= $Assessment_Plan['Workbook 3 - Unit 404 & Unit 406']; $h.= "</td>
    </tr>
    <tr>
        <td colspan=6>Record here the detail of the progress. What has the learner been doing towards completing this and discuss employer reference progress</td>
    </tr>
    <tr>
        <td colspan=6><i>
            " . $form_arf->workplace_competence . "
        </i></td>
    </tr>
</table>
<br>";

        return $h;
    }

    public static function getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {

        $html="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th></tr>
    </thead><tbody>
    <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
    <tr>
        <td>Systems development</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Systems development") . "<br>" . ReviewSkillsScans::getEventDate($events,'Systems development') . "</td>
        <td>Project management and effective communication in a business</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Project management and effective communication in a business") . "<br>" . ReviewSkillsScans::getEventDate($events,'Project management and effective communication in a business') . "</td>
        <td>Data communication and networks and networking technologies</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Data communication and networks and networking technologies") . "<br>" . ReviewSkillsScans::getEventDate($events,'Data communication and networks and networking technologies') . "</td>
    </tr>
    <tr>
        <td>404 diploma workshop 1</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "404 diploma workshop 1") . "<br>" . ReviewSkillsScans::getEventDate($events,'404 diploma workshop 1') . "</td>
        <td>404 diploma workshop 2</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "404 diploma workshop 2") . "<br>" . ReviewSkillsScans::getEventDate($events,'404 diploma workshop 2') . "</td>
        <td>404 diploma workshop 3</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "404 diploma workshop 3") . "<br>" . ReviewSkillsScans::getEventDate($events,'404 diploma workshop 3') . "</td>
    </tr>
    <tr>
        <td>Emerging technologies</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Emerging technologies") . "<br>" . ReviewSkillsScans::getEventDate($events,'Emerging technologies') . "</td>
        <td>IT virtualisation</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "IT virtualisation") . "<br>" . ReviewSkillsScans::getEventDate($events,'IT virtualisation') . "</td>
        <td>Installing and Configuring Windows Based Server 2016</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Installing and Configuring Windows Based Server 2016") . "<br>" . ReviewSkillsScans::getEventDate($events,'Installing and Configuring Windows Based Server 2016') . "</td>
    </tr>
    <tr>
        <td colspan=6>Record here the detail of the progress. What has the learner been doing towards completing this?</td>
    </tr>
    <tr>
        <td colspan=6><i>
            " . $form_arf->knowledge_modules . "
        </i></td>
    </tr>
</table>
<br>";
        return $html;

    }
}
?>