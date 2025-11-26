<?php
class ICTSupportTechnician
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

	    $_communication = isset($ss_result['Interpret and prioritise internal or external customers requirements in line with organisations policy']) ? $ss_result['Interpret and prioritise internal or external customers requirements in line with organisations policy'] : '';
        $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th></tr>
            </thead>
            <tbody><tr><td colspan=1>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Skills Scan Status</td></tr>
            <tr>
                <td>Interpret and prioritise internal or external customers requirements in line with organisations policy</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $_communication . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Apply the appropriate tools and techniques to undertake fault finding and rectification</td>
                <td style='text-align:center; vertical-align:middle'>" . $ss_result['Apply the appropriate tools and techniques to undertake fault finding and rectification'] . "</td>
                <td style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
                <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Apply Continuous Professional Development to support necessary business output and technical developments</td>
                <td style='text-align:center; vertical-align:middle'>" . $ss_result['Apply Continuous Professional Development to support necessary business output and technical developments'] . "</td>
                <td style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
                <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Operate safely and securely across platforms and responsibilities maintaining the security of personal data of internal and external stakeholders</td>
                <td style='text-align:center; vertical-align:middle'>" . $ss_result['Operate safely and securely across platforms and responsibilities maintaining the security of personal data of internal and external stakeholders'] . "</td>
                <td style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
                <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Communicate with all levels of stakeholders, keeping them informed of progress and managing escalation where appropriate</td>
                <td style='text-align:center; vertical-align:middle'>" . $ss_result['Communicate with all levels of stakeholders, keeping them informed of progress and managing escalation where appropriate'] . "</td>
                <td style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
                <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Develop and maintain effective working relationships with colleagues, customers, and other relevant stakeholders</td>
                <td style='text-align:center; vertical-align:middle'>" . $ss_result['Develop and maintain effective working relationships with colleagues, customers, and other relevant stakeholders'] . "</td>
                <td style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
                <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Manage and prioritise the allocated workload effectively making best use of time and resources</td>
                <td style='text-align:center; vertical-align:middle'>" . $ss_result['Manage and prioritise the allocated workload effectively making best use of time and resources'] . "</td>
                <td style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
                <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Complete documentation relevant to the task and escalate where appropriate</td>
                <td  style='text-align:center; vertical-align:middle'>" . $ss_result['Complete documentation relevant to the task and escalate where appropriate'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Install or undertake basic software upgrades, either physically or remotely</td>
                <td  style='text-align:center; vertical-align:middle'>" . $ss_result['Install or undertake basic software upgrades, either physically or remotely'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Establish and diagnose the extent of the IT support task, in line with the organisations policies and SLAs</td>
                <td  style='text-align:center; vertical-align:middle'>" . $ss_result['Establish and diagnose the extent of the IT support task, in line with the organisations policies and SLAs'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Provide remote/F2F support to resolve customer requirements</td>
                <td  style='text-align:center; vertical-align:middle'>" . $ss_result['Provide remote/F2F support to resolve customer requirements'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Maintain a safe working environment for own personal safety and others in line with Health & Safety appropriate to the task</td>
                <td  style='text-align:center; vertical-align:middle'>" . $ss_result['Maintain a safe working environment for own personal safety and others in line with Health & Safety appropriate to the task'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Identify and scope the best solution informed by the system data associated with the task</td>
                <td  style='text-align:center; vertical-align:middle'>" . $ss_result['Identify and scope the best solution informed by the system data associated with the task'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Test and evaluate the systems performance and compliance with customer requirement</td>
                <td  style='text-align:center; vertical-align:middle'>" . $ss_result['Test and evaluate the systems performance and compliance with customer requirement'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status14!="")?$sss[$previous_review->skills_scan_status14]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Escalate non routine problems in line with procedures</td>
                <td  style='text-align:center; vertical-align:middle'>" . $ss_result['Escalate non routine problems in line with procedures'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Use basic scripting to execute the relevant tasks for example PowerShell, Linux</td>
                <td  style='text-align:center; vertical-align:middle'>" . $ss_result['Use basic scripting to execute the relevant tasks for example PowerShell, Linux'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Carry out routine maintenance across systems, (such as IT, Communications), ensuring organisational compliance at all times</td>
                <td  style='text-align:center; vertical-align:middle'>" . $ss_result['Carry out routine maintenance across systems, (such as IT, Communications), ensuring organisational compliance at all times'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Apply the necessary security, in line with access and/or encryption requirements</td>
                <td  style='text-align:center; vertical-align:middle'>" . $ss_result['Apply the necessary security, in line with access and/or encryption requirements'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
            </tr>
        </table>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
            </thead>
            <tbody><tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
            <tr>
                <td>Approaches to back up and storage solutions</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Approaches to back up and storage solutions'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Basic elements of technical documentation and its interpretation</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Basic elements of technical documentation and its interpretation'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Principles of root cause problem solving using fault diagnostics for troubleshooting</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of root cause problem solving using fault diagnostics for troubleshooting'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Principles of basic network addressing for example binary</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of basic network addressing for example binary'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Basic awareness of the principles of cloud and cloud-based services</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Basic awareness of the principles of cloud and cloud-based services'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Fundamental principles of virtual networks and components</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Fundamental principles of virtual networks and components'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status24!="")?$sss[$previous_review->skills_scan_status24]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Principles of cultural awareness and how diversity impacts on delivery of support tasks</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of cultural awareness and how diversity impacts on delivery of support tasks'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status25!="")?$sss[$previous_review->skills_scan_status25]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Methods of communication including level of technical terminology to use to technical and non-technical stakeholders</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Methods of communication including level of technical terminology to use to technical and non-technical stakeholders'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status26!="")?$sss[$previous_review->skills_scan_status26]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Different types of maintenance and preventative measures to reduce the incidence of faults</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Different types of maintenance and preventative measures to reduce the incidence of faults'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status27!="")?$sss[$previous_review->skills_scan_status27]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Key principles of Security including the role of People, Product and Process in secure systems for example access and encryption requirements</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Key principles of Security including the role of People, Product and Process in secure systems for example access and encryption requirements'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status28!="")?$sss[$previous_review->skills_scan_status28]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status28", $ss_statuses, $form_arf->skills_scan_status28, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Fundamentals of physical networks and components</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Fundamentals of physical networks and components'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status29!="")?$sss[$previous_review->skills_scan_status29]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status29", $ss_statuses, $form_arf->skills_scan_status29, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Approaches to documenting tasks, findings, actions taken and outcome for example, use of task tracking and ticketing systems</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Approaches to documenting tasks, findings, actions taken and outcome for example, use of task tracking and ticketing systems'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status30!="")?$sss[$previous_review->skills_scan_status30]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status30", $ss_statuses, $form_arf->skills_scan_status30, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Basic awareness of legislation in relation to disposal of waste materials for example Waste Electronic and Electrical regulations (WEEE)</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Basic awareness of legislation in relation to disposal of waste materials for example Waste Electronic and Electrical regulations (WEEE)'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status31!="")?$sss[$previous_review->skills_scan_status31]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status31", $ss_statuses, $form_arf->skills_scan_status31, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Fundamental principles of operating systems, hardware system architectures and devices</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Fundamental principles of operating systems, hardware system architectures and devices'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status32!="")?$sss[$previous_review->skills_scan_status32]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status32", $ss_statuses, $form_arf->skills_scan_status32, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Principles of remote operation of devices including how to deploy and securely integrate mobile devices into a network</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of remote operation of devices including how to deploy and securely integrate mobile devices into a network'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status33!="")?$sss[$previous_review->skills_scan_status33]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status33", $ss_statuses, $form_arf->skills_scan_status33, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Fundamental principles of peripherals for example: printers and scanners</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Fundamental principles of peripherals for example: printers and scanners'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status34!="")?$sss[$previous_review->skills_scan_status34]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status34", $ss_statuses, $form_arf->skills_scan_status34, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Principles of virtualisation of servers, applications and networks</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of virtualisation of servers, applications and networks'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status35!="")?$sss[$previous_review->skills_scan_status35]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status35", $ss_statuses, $form_arf->skills_scan_status35, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Principles of disaster recovery, how a disaster recovery plan works and their role within it</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of disaster recovery, how a disaster recovery plan works and their role within it'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status36!="")?$sss[$previous_review->skills_scan_status36]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status36", $ss_statuses, $form_arf->skills_scan_status36, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Principles of Test Plans, their role and significance</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of Test Plans, their role and significance'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status37!="")?$sss[$previous_review->skills_scan_status37]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status37", $ss_statuses, $form_arf->skills_scan_status37, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Fundamentals of purpose, creation and maintenance of asset registers</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Fundamentals of purpose, creation and maintenance of asset registers'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status38!="")?$sss[$previous_review->skills_scan_status38]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status38", $ss_statuses, $form_arf->skills_scan_status38, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Approaches to system upgrades and updates and their significance</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Approaches to system upgrades and updates and their significance'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status39!="")?$sss[$previous_review->skills_scan_status39]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status39", $ss_statuses, $form_arf->skills_scan_status39, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Approaches to interpretation of log files, event viewer and system tools</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Approaches to interpretation of log files, event viewer and system tools'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status40!="")?$sss[$previous_review->skills_scan_status40]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status40", $ss_statuses, $form_arf->skills_scan_status40, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Basic elements of network infrastructure architectures including Wi-Fi and wired networks</td>
                <td style='text-align:center; vertical-align:middle'>" . $tk_result['Basic elements of network infrastructure architectures including Wi-Fi and wired networks'] . "</td>
                <td  style='text-align:center'>" . (($previous_review->skills_scan_status41!="")?$sss[$previous_review->skills_scan_status41]:"") . "</td>
                <td  style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status41", $ss_statuses, $form_arf->skills_scan_status41, true, false, $assessor_signed) . "</td>
            </tr>
        </table>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
            </thead>
            <tbody>
            <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Previous Review Skill Scan Status</td><td>Current Skills Scan Status</td></tr>
            <tr>
                <td>Works professionally, taking initiative as appropriate and acting with an ethical approach</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Works professionally, taking initiative as appropriate and acting with an ethical approach'] . "</td>
                <td style='text-align:center'>" . (($previous_review->skills_scan_status42!="")?$sss[$previous_review->skills_scan_status42]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status42", $ss_statuses, $form_arf->skills_scan_status42, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Example</td>
                <td colspan=3><i>
                    " . $form_arf->example1 . "
                </i></td>
            </tr>
            <tr>
                <td>Communicates technical and non-technical information in a variety of situations to support effective working with internal or external stakeholders</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Communicates technical and non-technical information in a variety of situations to support effective working with internal or external stakeholders'] . "</td>
                <td style='text-align:center'>" . (($previous_review->skills_scan_status43!="")?$sss[$previous_review->skills_scan_status43]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status43", $ss_statuses, $form_arf->skills_scan_status43, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Example</td>
                <td colspan=3><i>
                    " . $form_arf->example2 . "
                </i></td>
            </tr>
            <tr>
                <td>Demonstrates a productive and organised approach to their work</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Demonstrates a productive and organised approach to their work'] . "</td>
                <td style='text-align:center'>" . (($previous_review->skills_scan_status44!="")?$sss[$previous_review->skills_scan_status44]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status44", $ss_statuses, $form_arf->skills_scan_status44, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Example</td>
                <td colspan=3><i>
                    " . $form_arf->example3 . "
                </i></td>
            </tr>
            <tr>
                <td>Self-motivated, for example takes responsibility to complete the job</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Self-motivated, for example takes responsibility to complete the job'] . "</td>
                <td style='text-align:center'>" . (($previous_review->skills_scan_status45!="")?$sss[$previous_review->skills_scan_status45]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status45", $ss_statuses, $form_arf->skills_scan_status45, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Example</td>
                <td colspan=3><i>
                    " . $form_arf->example4 . "
                </i></td>
            </tr>
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
        </table>";

        return $html;
    }

    public static function getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {


        $html = "<table class=\"table1\" style=\"width: 900px\">
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
    </tr>
</table>
<br>";
        return $html;

    }
}
?>
