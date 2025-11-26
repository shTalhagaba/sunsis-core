<?php
class UnifiedCommsTroubleshooter
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        $h = "<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th></tr>
    </thead>
    <tbody>
    <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Skills Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td rowspan=1>Voice Solutions</td>
        <td>Configure and troubleshoot voice solutions including hardware and software failures</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Voice Solutions'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Voice Solutions'])?$Assessment_Plan['Voice Solutions']:"";
        $h .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Data Solutions</td>
        <td>Install, configure and troubleshoot Data solutions including switches and access points</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Data Solutions'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Data Solutions'])?$Assessment_Plan['Data Solutions']:"";
        $h .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Network Services Solutions</td>
        <td>Install, configure and troubleshoot Network Services solutions including line faults and internet speed problems</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Network Services Solutions'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Network Services Solutions'])?$Assessment_Plan['Network Services Solutions']:"";
        $h .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Domain Services</td>
        <td>Configure and maintain a domain service including assigning services, deploying software and applying updates</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Domain Services'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Domain Services'])?$Assessment_Plan['Domain Services']:"";
        $h .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Networks</td>
        <td>Configure and maintain a domain service including assigning services, deploying software and applying updates</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Networks'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Networks'])?$Assessment_Plan['Networks']:"";
        $h .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Security</td>
        <td>Configure and maintain security principles covering software, access, encryption and auditing</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Security'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Security'])?$Assessment_Plan['Security']:"";
        $h .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Servers</td>
        <td>Configure and maintain servers including storage, print services, group policy and updates</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Servers'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Servers'])?$Assessment_Plan['Servers']:"";
        $h .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Software</td>
        <td>Configure and maintain client software, including managing user profiles and troubleshooting user issues</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Software'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Software'])?$Assessment_Plan['Software']:"";
        $h .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    </tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
    </thead>
    <tbody>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
    <tr>
        <td>Server administration principles including storage, print services, group policy, availability, load balancing, failover clustering, back-up and disaster recovery</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Server administration principles including storage, print services, group policy, availability, load balancing, failover clustering, back-up and disaster recovery'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Server and client architecture, features, deployment process and troubleshooting tools for client software and applications</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Server and client architecture, features, deployment process and troubleshooting tools for client software and applications'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Security principles including software, access such as VPN, encryption and auditing</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Security principles including software, access such as VPN, encryption and auditing'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Network fundamentals including network components and internet protocols</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Network fundamentals including network components and internet protocols'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Network services solutions including cloud services, SIP (Session Initiation Protocol), internet connectivity, mobility, fixed lines and hosted solutions</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Network services solutions including cloud services, SIP (Session Initiation Protocol), internet connectivity, mobility, fixed lines and hosted solutions'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Domain services including administration, user and service accounts and group policy</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Domain services including administration, user and service accounts and group policy'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status14!="")?$sss[$previous_review->skills_scan_status14]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Voice solutions and can identify the components of such a solution, the features, the deployment process and troubleshooting tools and techniques</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Voice solutions and can identify the components of such a solution, the features, the deployment process and troubleshooting tools and techniques'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Data solutions (LAN/WAN/WLAN), the differences between the different technologies and how the components form part of a solution</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Data solutions (LAN/WAN/WLAN), the differences between the different technologies and how the components form part of a solution'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
    </tr>
    </tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
    </thead>
    <tbody>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status24!="")?$sss[$previous_review->skills_scan_status24]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example8 . "
        </i></td>
    </tr>
    </tbody>
</table>
<br>";

    $h.= "<table class=\"table1\" style=\"width: 900px\">
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
    <tbody>
    <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Data Solutions</td>
        <td>";
        if(isset($Assessment_Plan['Data Solutions']))
             $h .= $Assessment_Plan['Data Solutions'];
        else
            $h .= "&nbsp;";
        $h .= "</td>
        <td>Networks</td>
        <td>";
        if(isset($Assessment_Plan['Networks']))
            $h .= $Assessment_Plan['Networks'];
        else
            $h .= "&nbsp;";
        $h .= "</td>
        <td>Servers</td>
        <td>";
        if(isset($Assessment_Plan['Servers']))
            $h .= $Assessment_Plan['Servers'];
        else
            "&nbsp;";
        $h .= "</td>
    </tr>
    <tr>
        <td>Voice Solutions</td>
        <td>";
        if(isset($Assessment_Plan['Voice Solutions']))
            $h .= $Assessment_Plan['Voice Solutions'];
        else
            $h .= "&nbsp;";
        $h .= "</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=6>Record here the detail of the progress. What has the learner been doing towards completing this and discuss employer reference progress</td>
    </tr>
    <tr>
        <td colspan=6><i>
            " . $form_arf->workplace_competence . "
        </i></td>
    </tr>
    </tbody>
</table>
<br>";

        return $h;
    }

    public static function getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {

     $h = "<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th></tr>
    </thead>
    <tbody><tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
    <tr>
        <td>Network fundamentals MTA</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Network fundamentals MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Network fundamentals MTA') . "</td>
        <td>Network systems and architecture</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Network systems and architecture") . "<br>" . ReviewSkillsScans::getEventDate($events,'Network systems and architecture') . "</td>
        <td>Network security</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Network security") . "<br>" . ReviewSkillsScans::getEventDate($events,'Network security') . "</td>
    </tr>
    <tr>
        <td>BCS award in voice and data solutions</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "BCS award in voice and data solutions") . "<br>" . ReviewSkillsScans::getEventDate($events,'BCS award in voice and data solutions') . "</td>
        <td>Network fundamentals MTA test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Network fundamentals MTA test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Network fundamentals MTA test') . "</td>
        <td>BCS award in server test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "BCS award in server test") . "<br>" . ReviewSkillsScans::getEventDate($events,'BCS award in server test') . "</td>
    </tr>
    <tr>
        <td>BCS award in voice and data solutions</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "BCS award in voice and data solutions") . "<br>" . ReviewSkillsScans::getEventDate($events,'BCS award in voice and data solutions') . "</td>
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
    </tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Record here the detail of the progress. What has the learner been doing towards completing this?</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->knowledge_modules . "
        </i></td>
    </tr>
    </tbody>
</table>
<br>";
        return $h;

    }
}
?>