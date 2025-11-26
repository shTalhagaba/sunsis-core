<?php
class CyberSecurityRiskTechnologist2021
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        $h="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th></tr>
    </thead><tbody>
    <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Skills Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td rowspan=1>Threats, hazards, risks and intelligence</td>
        <td>Discover vulnerabilities in a system</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Discover vulnerabilities in a system'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Threats, hazards, risks and intelligence</td>
        <td>Analyse and evaluate security threats and hazards to a system or service or processes</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Analyse and evaluate security threats and hazards to a system or service or processes'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Threats, hazards, risks and intelligence</td>
        <td>Common attack techniques and how to defend against them.  Awareness of use of  relevant external sources of vulnerabilities (e.g. OWASP)</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Common attack techniques and how to defend against them.  Awareness of use of  relevant external sources of vulnerabilities (e.g. OWASP)'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Threats, hazards, risks and intelligence</td>
        <td>Security risk assessment for a simple system and basic remediation advice in the context of the employer</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Security risk assessment for a simple system and basic remediation advice in the context of the employer'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Security</td>
        <td>Source and analyse a security case (e.g. a Common Criteria Protection Profile for a security component) and describe what threats, vulnerability or risks are mitigated and identify any residual areas of concern </td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Source and analyse a security case (e.g. a Common Criteria Protection Profile for a security component) and describe what threats, vulnerability or risks are mitigated and identify any residual areas of concern'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Security</td>
        <td>Develop a simple security case to include the security objectives, threats, and for every identified attack technique identify mitigation or security controls that could include technical, implementation, policy or process </td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Develop a simple security case to include the security objectives, threats, and for every identified attack technique identify mitigation or security controls that could include technical, implementation, policy or process'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Organisational context</td>
        <td>Follow organisational policies and standards for information and cyber security</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Follow organisational policies and standards for information and cyber security'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Organisational context</td>
        <td>Operate according to service level agreements or employer defined performance targets</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Operate according to service level agreements or employer defined performance targets'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Organisational context</td>
        <td>Investigate different views of the future (using more than one external source) and trends in a relevant technology area and describe what this might mean for your business, with supporting reasoning</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Investigate different views of the future (using more than one external source) and trends in a relevant technology area and describe what this might mean for your business, with supporting reasoning'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Design, build & test a network</td>
        <td>Design, build, test and troubleshoot a network incorporating more than one subnet with static and dynamic routes that includes servers, hubs, switches, routers and user devices</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Design, build, test and troubleshoot a network incorporating more than one subnet with static and dynamic routes that includes servers, hubs, switches, routers and user devices'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Technologist Activity 1 Project 1","Technologist Activity 1 Project 2","Technologist Activity 1 Project 3"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Security Analysis</td>
        <td>Analyse security requirements (functional and non-functional security requirements that may be presented in a security case) against other design requirements (e.g. usability, cost, size, weight, power, heat, supportability etc.), given for a given system or product. Identify conflicting requirements and propose, with reasoning, resolution through appropriate trade-offs</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Analyse security requirements (functional and non-functional security requirements that may be presented in a security case) against other design requirements (e.g. usability, cost, size, weight, power, heat, supportability etc.), given for a given system or product. Identify conflicting requirements and propose, with reasoning, resolution through appropriate trade-offs'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Technologist Activity 1 Project 1","Technologist Activity 1 Project 2","Technologist Activity 1 Project 3"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Security in a network</td>
        <td>Design and build a simple system in accordance with a simple security case to include properly implemented security controls required by the security case</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Design and build a simple system in accordance with a simple security case to include properly implemented security controls required by the security case'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Technologist Activity 1 Project 1","Technologist Activity 1 Project 2","Technologist Activity 1 Project 3"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Security in a network</td>
        <td>Configure relevant types of common security hardware and software components to implement a given security policy</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Configure relevant types of common security hardware and software components to implement a given security policy'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Technologist Activity 1 Project 1","Technologist Activity 1 Project 2","Technologist Activity 1 Project 3"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Security in a network</td>
        <td>Design a system employing a crypto to meet defined security objectives. Develop and implement a key management plan for the given scenario/system</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Design a system employing a crypto to meet defined security objectives. Develop and implement a key management plan for the given scenario/system'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status14!="")?$sss[$previous_review->skills_scan_status14]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Technologist Activity 1 Project 1","Technologist Activity 1 Project 2","Technologist Activity 1 Project 3"));
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    </tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
    </thead><tbody>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
    <tr>
        <td>Why cyber security matters - the importance to business and society</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Why cyber security matters - the importance to business and society'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Basic theory - concepts such as security, identity, confidentiality, integrity, availability, threat, vulnerability, risk and hazard. Also how these relate to each other and lead to risk and harm</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Basic theory - concepts such as security, identity, confidentiality, integrity, availability, threat, vulnerability, risk and hazard. Also how these relate to each other and lead to risk and harm'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Security assurance - concepts and how assurance may be achieved in practice (eg penetration testing is and how it contributes to assurance; and extrinsic assurance methods)</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Security assurance - concepts and how assurance may be achieved in practice (e.g. penetration testing is and how it contributes to assurance; and extrinsic assurance methods)'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Building a security case - deriving security objectives with reasoned justification in a representative business scenario</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Building a security case - deriving security objectives with reasoned justification in a representative business scenario'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Cyber security concepts applied to ICT infrastructure - the fundamental building blocks and typical architectures and identify some common vulnerabilities in networks and systems</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Cyber security concepts applied to ICT infrastructure - the fundamental building blocks and typical architectures and identify some common vulnerabilities in networks and systems'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Attack techniques and sources of threat, the main types of common attack techniques; also the role of human behaviour.  How attack techniques combine with motive and opportunity to become a threat</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Attack techniques and sources of threat, the main types of common attack techniques; also the role of human behaviour.  How attack techniques combine with motive and opportunity to become a threat'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Cyber defence - ways to defend against attack techniques</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Cyber defence - ways to defend against attack techniques'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Laws and ethics - security standards, regulations and their consequences.  The role of criminal and other law; key relevant features of UK and international law</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Laws and ethics - security standards, regulations and their consequences.  The role of criminal and other law; key relevant features of UK and international law'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Threat landscape - how to apply relevant techniques for horizon scanning including use of recognised sources of threat intelligence</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Threat landscape - how to apply relevant techniques for horizon scanning including use of recognised sources of threat intelligence'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Threat trends - the significance of identified trends  in cyber security and  the value and risk of this analysis</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Threat trends - the significance of identified trends  in cyber security and  the value and risk of this analysis'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status24!="")?$sss[$previous_review->skills_scan_status24]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Basics of networks: data, protocols and how they relate to each other; the main routing protocols; the main factors affecting network performance including typical failure modes in protocols and approaches to error control</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Basics of networks: data, protocols and how they relate to each other; the main routing protocols; the main factors affecting network performance including typical failure modes in protocols and approaches to error control'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status25!="")?$sss[$previous_review->skills_scan_status25]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>How to build a security case: what good practice in design is; common security architectures; awareness of reputable security architectures that incorporates hardware and software components, and sources of architecture patterns and guidance. How to build a security case including context, threats, justifying the selected mitigations and security controls with reasoning and recognising the dynamic and adaptable nature of threats</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How to build a security case: what good practice in design is; common security architectures; awareness of reputable security architectures that incorporates hardware and software components, and sources of architecture patterns and guidance. How to build a security case including context, threats, justifying the selected mitigations and security controls with reasoning and recognising the dynamic and adaptable nature of threats'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status26!="")?$sss[$previous_review->skills_scan_status26]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>How cyber security technology components are typically deployed in networks and systems to provide security functionality including: hardware and software</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How cyber security technology components are typically deployed in networks and systems to provide security functionality including: hardware and software'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status27!="")?$sss[$previous_review->skills_scan_status27]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Basics of cryptography - the main techniques, the significance of key management and the legal issues</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Basics of cryptography - the main techniques, the significance of key management and the legal issues'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status28!="")?$sss[$previous_review->skills_scan_status28]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status28", $ss_statuses, $form_arf->skills_scan_status29, true, false, $assessor_signed) . "</td>
    </tr>
</tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
    </thead><tbody>
    <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td>Logical and creative thinking skills</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status29!="")?$sss[$previous_review->skills_scan_status29]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status29", $ss_statuses, $form_arf->skills_scan_status29, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status30!="")?$sss[$previous_review->skills_scan_status30]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status30", $ss_statuses, $form_arf->skills_scan_status30, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status31!="")?$sss[$previous_review->skills_scan_status31]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status31", $ss_statuses, $form_arf->skills_scan_status31, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status32!="")?$sss[$previous_review->skills_scan_status32]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status32", $ss_statuses, $form_arf->skills_scan_status32, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status33!="")?$sss[$previous_review->skills_scan_status33]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status33", $ss_statuses, $form_arf->skills_scan_status33, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            ". $form_arf->example5 ."
        </i></td>
    </tr>
    <tr>
        <td>Ability to work with a range of internal and external people</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work with a range of internal and external people'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status34!="")?$sss[$previous_review->skills_scan_status34]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status34", $ss_statuses, $form_arf->skills_scan_status34, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status35!="")?$sss[$previous_review->skills_scan_status35]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status35", $ss_statuses, $form_arf->skills_scan_status35, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status36!="")?$sss[$previous_review->skills_scan_status36]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status36", $ss_statuses, $form_arf->skills_scan_status36, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            ". $form_arf->example8 ."
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
    </thead><tbody>
    <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Core Activity 1</td>
        <td>"; if(isset($Assessment_Plan['Core Activity 1'])) $h.= $Assessment_Plan['Core Activity 1']; $h.= "</td>
        <td>Core Activity 2</td>
        <td>"; if(isset($Assessment_Plan['Core Activity 2'])) $h.= $Assessment_Plan['Core Activity 2']; $h.= "</td>
        <td>Core Activity 3</td>
        <td>"; if(isset($Assessment_Plan['Core Activity 3'])) $h.= $Assessment_Plan['Core Activity 3']; $h.= "</td>
    </tr>
    <tr>
        <td>Core Activity 4</td>
        <td>"; if(isset($Assessment_Plan['Core Activity 4'])) $h.= $Assessment_Plan['Core Activity 4']; $h.= "</td>
        <td>Technologist Activity 1 Project 1</td>
        <td>"; if(isset($Assessment_Plan['Technologist Activity 1 Project 1'])) $h.= $Assessment_Plan['Technologist Activity 1 Project 1']; $h.= "</td>
        <td>Technologist Activity 1 Project 2</td>
        <td>"; if(isset($Assessment_Plan['Technologist Activity 1 Project 2'])) $h.= $Assessment_Plan['Technologist Activity 1 Project 2']; $h.= "</td>
    </tr>
    <tr>
        <td>Technologist Activity 1 Project 3</td>
        <td>"; if(isset($Assessment_Plan['Technologist Activity 1 Project 3'])) $h.= $Assessment_Plan['Technologist Activity 1 Project 3']; $h.= "</td>
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
</table>
<br>";


        return $h;
    }

    public static function getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {

        $h="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th></tr>
    </thead><tbody>
    <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
    <tr>
        <td>Certificate in cyber security introduction</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in cyber security introduction") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in cyber security introduction') . "</td>
        <td>Certificate in cyber security introduction test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in cyber security introduction test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in cyber security introduction test') . "</td>
        <td>Certificate in network and digital communications</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in network and digital communications") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in network and digital communications') . "</td>
    </tr>
    <tr>
        <td>Certificate in network and digital communications test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in network and digital communications test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in network and digital communications test') . "</td>
        <td>Certificate in security case development and design good practice</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in security case development and design good practice") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in security case development and design good practice') . "</td>
        <td>Certificate in security case development and design good practice test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in security case development and design good practice test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in security case development and design good practice test') . "</td>
    </tr>
    <tr>
        <td>Certificate in security technology building blocks</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in security technology building blocks") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in security technology building blocks') . "</td>
        <td>Certificate in security technology building blocks test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in security technology building blocks test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in security technology building blocks test') . "</td>
        <td>Certificate in employment of cryptography</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in employment of cryptography") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in employment of cryptography') . "</td>
    </tr>
    <tr>
        <td>Certificate in employment of cryptography test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in employment of cryptography test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in employment of cryptography test') . "</td>
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
    </tr>
</table>
<br>";

        return $h;

    }
}
?>