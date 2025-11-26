<?php
class CyberSecurityRiskAnalyst
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        $html = "<table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=5>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th>
        </tr>
        </thead>
        <tbody>
        <tr><td colspan=2>Competence</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
        <tr>
            <td rowspan=1>Threats, hazards, risks and intelligence</td>
            <td>Discover vulnerabilities in a system</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Discover vulnerabilities in a system'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Core Activity 1'])?$Assessment_Plan['Core Activity 1']:"";
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
        </tr>
        <tr>
            <td rowspan=1>Threats, hazards, risks and intelligence</td>
            <td>Analyse and evaluate security threats and hazards to a system or service or processes</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Analyse and evaluate security threats and hazards to a system or service or processes'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Core Activity 1'])?$Assessment_Plan['Core Activity 1']:"";
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
        </tr>
        <tr>
            <td rowspan=1>Threats, hazards, risks and intelligence</td>
            <td>Common attack techniques and how to defend against them.  Awareness of use of  relevant external sources of vulnerabilities (e.g. OWASP)</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Common attack techniques and how to defend against them.  Awareness of use of  relevant external sources of vulnerabilities (e.g. OWASP)'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Core Activity 1'])?$Assessment_Plan['Core Activity 1']:"";
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
        </tr>
        <tr>
            <td rowspan=1>Threats, hazards, risks and intelligence</td>
            <td>Security risk assessment for a simple system and basic remediation advice in the context of the employer</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Security risk assessment for a simple system and basic remediation advice in the context of the employer'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Core Activity 1'])?$Assessment_Plan['Core Activity 1']:"";
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
        </tr>
        <tr>
            <td rowspan=1>Security</td>
            <td>Source and analyse a security case (e.g. a Common Criteria Protection Profile for a security component) and describe what threats, vulnerability or risks are mitigated and identify any residual areas of concern </td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Source and analyse a security case (e.g. a Common Criteria Protection Profile for a security component) and describe what threats, vulnerability or risks are mitigated and identify any residual areas of concern'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        $it_security = isset($Assessment_Plan['Core Activity 1'])?$Assessment_Plan['Core Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
        </tr>
        <tr>
            <td rowspan=1>Security</td>
            <td>Develop a simple security case to include the security objectives, threats, and for every identified attack technique identify mitigation or security controls that could include technical, implementation, policy or process </td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Develop a simple security case to include the security objectives, threats, and for every identified attack technique identify mitigation or security controls that could include technical, implementation, policy or process'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        $it_security = isset($Assessment_Plan['Core Activity 1'])?$Assessment_Plan['Core Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
        </tr>
        <tr>
            <td>Organisational context</td>
            <td>Follow organisational policies and standards for information and cyber security</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Follow organisational policies and standards for information and cyber security'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        $remote_infrastructure = isset($Assessment_Plan['Core Activity 1'])?$Assessment_Plan['Core Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
        </tr>
        <tr>
            <td>Organisational context</td>
            <td>Operate according to service level agreements or employer defined performance targets</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Operate according to service level agreements or employer defined performance targets'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        $remote_infrastructure = isset($Assessment_Plan['Core Activity 1'])?$Assessment_Plan['Core Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
        </tr>
        <tr>
            <td>Organisational context</td>
            <td>Investigate different views of the future (using more than one external source) and trends in a relevant technology area and describe what this might mean for your business, with supporting reasoning</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Investigate different views of the future (using more than one external source) and trends in a relevant technology area and describe what this might mean for your business, with supporting reasoning'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
        $remote_infrastructure = isset($Assessment_Plan['Core Activity 1'])?$Assessment_Plan['Core Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
        </tr>
        <tr>
            <td>Cyber security risk assessment</td>
            <td>Conduct a cyber-risk assessment against an externally (market) recognised cyber security standard using a recognised risk assessment methodology</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Conduct a cyber-risk assessment against an externally (market) recognised cyber security standard using a recognised risk assessment methodology'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";
        $data = isset($Assessment_Plan['Risk Analyst Activity 1'])?$Assessment_Plan['Risk Analyst Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
        </tr>
        <tr>
            <td>Cyber security risk assessment</td>
            <td>Identify threats relevant to a specific organisation and/or sector. Information security policy and process</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Identify threats relevant to a specific organisation and/or sector. Information security policy and process'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>";
        $data = isset($Assessment_Plan['Risk Analyst Activity 1'])?$Assessment_Plan['Risk Analyst Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
        </tr>
        <tr>
            <td>Cyber security risk assessment</td>
            <td>Develop an information security policy or process to address an identified risk</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Develop an information security policy or process to address an identified risk'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>";
        $data = isset($Assessment_Plan['Risk Analyst Activity 1'])?$Assessment_Plan['Risk Analyst Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
        </tr>
        <tr>
            <td>Cyber security risk assessment</td>
            <td>Develop an information security policy within a defined scope to take account of a minimum of 1 law or regulation relevant to cyber security</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Develop an information security policy within a defined scope to take account of a minimum of 1 law or regulation relevant to cyber security'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>";
        $data = isset($Assessment_Plan['Risk Analyst Activity 1'])?$Assessment_Plan['Risk Analyst Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
        </tr>
        <tr>
            <td>Audit & assurance</td>
            <td>Take an active part in a security audit against a recognised cyber security standard, undertake a gap analysis and make recommendations for remediation</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Take an active part in a security audit against a recognised cyber security standard, undertake a gap analysis and make recommendations for remediation'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>";
        $workflow_management = isset($Assessment_Plan['Risk Analyst Activity 1'])?$Assessment_Plan['Risk Analyst Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>
        </tr>
        <tr>
            <td>Incident response & business continuity</td>
            <td>Develop an incident response plan for approval (within an organisations governance arrangements for incident response)</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Develop an incident response plan for approval (within an organisations governance arrangements for incident response)'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>";
        $health_safety = isset($Assessment_Plan['Risk Analyst Activity 1'])?$Assessment_Plan['Risk Analyst Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
        </tr>
        <tr>
            <td>Incident response & business continuity</td>
            <td>Develop a business continuity plan for approval (within an organisations governance arrangements for business continuity)</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Develop a business continuity plan for approval (within an organisations governance arrangements for business continuity)'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>";
        $health_safety = isset($Assessment_Plan['Risk Analyst Activity 1'])?$Assessment_Plan['Risk Analyst Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
        </tr>
        <tr>
            <td>Cyber security culture</td>
            <td>Assess security culture using a recognised approach</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Assess security culture using a recognised approach'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>";
        $health_safety = isset($Assessment_Plan['Risk Analyst Activity 1'])?$Assessment_Plan['Risk Analyst Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
        </tr>
        <tr>
            <td>Cyber security culture</td>
            <td>Design and implement a simple (security awareness) campaign to address a specific aspect of a security culture</td>
            <td style='text-align:center; vertical-align:middle'>" . $ss_result['Design and implement a simple (security awareness) campaign to address a specific aspect of a security culture'] . "</td>
            <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>";
        $health_safety = isset($Assessment_Plan['Risk Analyst Activity 1'])?$Assessment_Plan['Risk Analyst Activity 1']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
        </tr>
        </tbody>
    </table>
    <table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=4>&nbsp;&nbsp;&nbsp;Technical Knowledge</th>
        </tr>
        </thead>
        <tbody>
        <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
        <tr>
            <td>Why cyber security matters - the importance to business and society</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Why cyber security matters - the importance to business and society'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Basic theory - concepts such as security, identity, confidentiality, integrity, availability, threat, vulnerability, risk and hazard. Also how these relate to each other and lead to risk and harm</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Basic theory - concepts such as security, identity, confidentiality, integrity, availability, threat, vulnerability, risk and hazard. Also how these relate to each other and lead to risk and harm'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Security assurance - concepts and how assurance may be achieved in practice (eg penetration testing is and how it contributes to assurance; and extrinsic assurance methods)</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Security assurance - concepts and how assurance may be achieved in practice (eg penetration testing is and how it contributes to assurance; and extrinsic assurance methods)'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Building a security case - deriving security objectives with reasoned justification in a representative business scenario</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Building a security case - deriving security objectives with reasoned justification in a representative business scenario'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Cyber security concepts applied to ICT infrastructure - the fundamental building blocks and typical architectures and identify some common vulnerabilities in networks and systems</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Cyber security concepts applied to ICT infrastructure - the fundamental building blocks and typical architectures and identify some common vulnerabilities in networks and systems'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Develop a simple security case to include the security objectives, threats, and for every identified attack technique identify mitigation or security controls that could include technical, implementation, policy or process</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Develop a simple security case to include the security objectives, threats, and for every identified attack technique identify mitigation or security controls that could include technical, implementation, policy or process'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Attack techniques and sources of threat, the main types of common attack techniques; also the role of human behaviour.  How attack techniques combine with motive and opportunity to become a threat</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Attack techniques and sources of threat, the main types of common attack techniques; also the role of human behaviour.  How attack techniques combine with motive and opportunity to become a threat'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Cyber defence - ways to defend against attack techniques</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Cyber defence - ways to defend against attack techniques'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Laws and ethics - security standards, regulations and their consequences.  The role of criminal and other law; key relevant features of UK and international law</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Laws and ethics - security standards, regulations and their consequences.  The role of criminal and other law; key relevant features of UK and international law'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Conduct a cyber - risk assessment against an externally (market) recognised cyber security standard using a recognised risk assessment methodology</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Conduct a cyber - risk assessment against an externally (market) recognised cyber security standard using a recognised risk assessment methodology'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status28", $ss_statuses, $form_arf->skills_scan_status28, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Threat landscape - how to apply relevant techniques for horizon scanning including use of recognised sources of threat intelligence</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Threat landscape - how to apply relevant techniques for horizon scanning including use of recognised sources of threat intelligence'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status29", $ss_statuses, $form_arf->skills_scan_status29, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Threat trends - the significance of identified trends  in cyber security and  the value and risk of this analysis</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Threat trends - the significance of identified trends  in cyber security and  the value and risk of this analysis'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status30", $ss_statuses, $form_arf->skills_scan_status30, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>Types of risk assessment methodologies and approaches to risk treatment; can identify the vulnerabilities in organisations and security management systems; understand the threat intelligence lifecycle; describe different approaches to risk treatment. Understand the role of the risk owner and contrast that role with other stakeholders</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Types of risk assessment methodologies and approaches to risk treatment; can identify the vulnerabilities in organisations and security management systems; understand the threat intelligence lifecycle; describe different approaches to risk treatment. Understand the role of the risk owner and contrast that role with other stakeholders'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status31", $ss_statuses, $form_arf->skills_scan_status31, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
        <tr>
            <td>The legal, standards, regulations and ethical standards relevant to cyber security: governance, organisational structure, roles, policies, standard, guidelines and how these all work together to deliver identified security outcomes. Awareness of the legal framework, key concepts applying to ISO27001 (a specification for information security management), and awareness of legal and regulatory obligations for breach notification</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The legal, standards, regulations and ethical standards relevant to cyber security: governance, organisational structure, roles, policies, standard, guidelines and how these all work together to deliver identified security outcomes. Awareness of the legal framework, key concepts applying to ISO27001 (a specification for information security management), and awareness of legal and regulatory obligations for breach notification'] . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status31", $ss_statuses, $form_arf->skills_scan_status31, true, false, $assessor_signed) . "</td>
            <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Cyber Security Introduction') . "<br>" . ReviewSkillsScans::getEventDate($events,'Cyber Security Introduction') . "</td>
        </tr>
    </table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
    </thead><tbody>
    <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Previous Review Skill Scan Status</td><td>Current Skills Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td>Logical and creative thinking skills</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills'] . "</td>
        <td width=50px style='text-align:center'>" . (($previous_review->skills_scan_status33!="")?$sss[$previous_review->skills_scan_status33]:"") . "</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status33", $ss_statuses, $form_arf->skills_scan_status33, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4","Risk Analyst Activity 1","Risk Analyst Activity 2","Risk Analyst Activity 3","Risk Analyst Activity 4")))
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Example</td>
        <td colspan=4><i>
            " . $form_arf->example1 . "
        </i></td>
    </tr>
    <tr>
        <td>Analytical and problem solving skills</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Analytical and problem solving skills'] . "</td>
        <td width=50px style='text-align:center'>" . (($previous_review->skills_scan_status34!="")?$sss[$previous_review->skills_scan_status34]:"") . "</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status34", $ss_statuses, $form_arf->skills_scan_status34, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4","Risk Analyst Activity 1","Risk Analyst Activity 2","Risk Analyst Activity 3","Risk Analyst Activity 4")))
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Example</td>
        <td colspan=4><i>
            " . $form_arf->example2 . "
        </i></td>
    </tr>
    <tr>
        <td>Ability to work independently and to take responsibility</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work independently and to take responsibility'] . "</td>
        <td width=50px style='text-align:center'>" . (($previous_review->skills_scan_status35!="")?$sss[$previous_review->skills_scan_status35]:"") . "</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status35", $ss_statuses, $form_arf->skills_scan_status35, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4","Risk Analyst Activity 1","Risk Analyst Activity 2","Risk Analyst Activity 3","Risk Analyst Activity 4")))
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Example</td>
        <td colspan=4><i>
            " . $form_arf->example3 . "
        </i></td>
    </tr>
    <tr>
        <td>Can use own initiative</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Can use own initiative'] . "</td>
        <td width=50px style='text-align:center'>" . (($previous_review->skills_scan_status36!="")?$sss[$previous_review->skills_scan_status36]:"") . "</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status36", $ss_statuses, $form_arf->skills_scan_status36, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4","Risk Analyst Activity 1","Risk Analyst Activity 2","Risk Analyst Activity 3","Risk Analyst Activity 4")))
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Example</td>
        <td colspan=4><i>
            " . $form_arf->example4 . "
        </i></td>
    </tr>
    <tr>
        <td>A thorough and organised approach</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A thorough and organised approach'] . "</td>
        <td width=50px style='text-align:center'>" . (($previous_review->skills_scan_status37!="")?$sss[$previous_review->skills_scan_status37]:"") . "</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status37", $ss_statuses, $form_arf->skills_scan_status37, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4","Risk Analyst Activity 1","Risk Analyst Activity 2","Risk Analyst Activity 3","Risk Analyst Activity 4")))
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Example</td>
        <td colspan=4><i>
            " . $form_arf->example5 . "
        </i></td>
    </tr>
    <tr>
        <td>Ability to work with a range of internal and external people</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work with a range of internal and external people'] . "</td>
        <td width=50px style='text-align:center'>" . (($previous_review->skills_scan_status38!="")?$sss[$previous_review->skills_scan_status38]:"") . "</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status38", $ss_statuses, $form_arf->skills_scan_status38, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4","Risk Analyst Activity 1","Risk Analyst Activity 2","Risk Analyst Activity 3","Risk Analyst Activity 4")))
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Example</td>
        <td colspan=4><i>
            " . $form_arf->example6 . "
        </i></td>
    </tr>
    <tr>
        <td>Ability to communicate effectively in a variety of situations</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to communicate effectively in a variety of situations'] . "</td>
        <td width=50px style='text-align:center'>" . (($previous_review->skills_scan_status39!="")?$sss[$previous_review->skills_scan_status39]:"") . "</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status39", $ss_statuses, $form_arf->skills_scan_status39, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4","Risk Analyst Activity 1","Risk Analyst Activity 2","Risk Analyst Activity 3","Risk Analyst Activity 4")))
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Example</td>
        <td colspan=4><i>
            " . $form_arf->example7 . "
        </i></td>
    </tr>
    <tr>
        <td>Maintain productive, professional and secure working environment</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Maintain productive, professional and secure working environment'] . "</td>
        <td width=50px style='text-align:center'>" . (($previous_review->skills_scan_status40!="")?$sss[$previous_review->skills_scan_status40]:"") . "</td>
        <td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status40", $ss_statuses, $form_arf->skills_scan_status40, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Core Activity 1","Core Activity 2","Core Activity 3","Core Activity 4","Risk Analyst Activity 1","Risk Analyst Activity 2","Risk Analyst Activity 3","Risk Analyst Activity 4")))
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td width=50px rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Example</td>
        <td colspan=4><i>
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
    </thead><tbody>
    <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Core Activity 1</td>
        <td>"; if(isset($Assessment_Plan['Core Activity 1'])) $html.=$Assessment_Plan['Core Activity 1']; $html.="</td>
        <td>Core Activity 2</td>
        <td>"; if(isset($Assessment_Plan['Core Activity 2'])) $html.=$Assessment_Plan['Core Activity 2']; $html.="</td>
        <td>Core Activity 3</td>
        <td>"; if(isset($Assessment_Plan['Core Activity 3'])) $html.=$Assessment_Plan['Core Activity 3']; $html.="</td>
    </tr>
    <tr>
        <td>Core Activity 4</td>
        <td>"; if(isset($Assessment_Plan['Core Activity 4'])) $html.=$Assessment_Plan['Core Activity 4']; $html.="</td>
        <td>Risk Analyst Activity 1</td>
        <td>"; if(isset($Assessment_Plan['Risk Analyst Activity 1'])) $html.=$Assessment_Plan['Risk Analyst Activity 1']; $html.="</td>
        <td>Risk Analyst Activity 2</td>
        <td>"; if(isset($Assessment_Plan['Risk Analyst Activity 2'])) $html.=$Assessment_Plan['Risk Analyst Activity 2']; $html.="</td>
    </tr>
    <tr>
        <td>Risk Analyst Activity 3</td>
        <td>"; if(isset($Assessment_Plan['Risk Analyst Activity 2'])) $html.=$Assessment_Plan['Risk Analyst Activity 2']; $html.="</td>
        <td>Risk Analyst Activity 4</td>
        <td>"; if(isset($Assessment_Plan['Risk Analyst Activity 4'])) $html.=$Assessment_Plan['Risk Analyst Activity 4']; $html.="</td>
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
            <td>Certificate in cyber security introduction</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in cyber security introduction") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in cyber security introduction') . "</td>
            <td>Certificate in cyber security introduction test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in cyber security introduction test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in cyber security introduction test') . "</td>
            <td>Certificate in network and digital communications</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in network and digital communications") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in network and digital communications') . "</td>
        </tr>
        <tr>
            <td>Certificate in security technology building blocks</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in security technology building blocks") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in security technology building blocks') . "</td>
            <td>Certificate in governance organisation law regulation and standards</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in governance organisation law regulation and standards") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in governance organisation law regulation and standards') . "</td>
            <td>Certificate in governance organisation law regulation and standards test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in governance organisation law regulation and standards test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in governance organisation law regulation and standards test') . "</td>
        </tr>
        <tr>
            <td>Award in risk assessment</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Award in risk assessment") . "<br>" . ReviewSkillsScans::getEventDate($events,'Award in risk assessment'). "</td>
            <td>Award in risk assessment test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Award in risk assessment test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Award in risk assessment test') . "</td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <br>
    <table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan=4>Record here the detail of the progress. What has the learner been doing towards completing this and discuss employer reference progress</td>
        </tr>
        <tr>
            <td colspan=4><i>
                " . $form_arf->workplace_competence . "
            </i></td>
        </tr>
        </tbody>
    </table>
    <br>

    <table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
        </tr>
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

        return $html;

    }
}
?>