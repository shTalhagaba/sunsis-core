<?php
class DataTechnicianV215
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        $html = "<table class=\"table1\" style=\"width: 900px\">
<thead>
<tr><th colspan=6>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th></tr>
</thead>
    <tbody><tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Skills Scan Status</td><td>Assessment Plan Status</td></tr>
<tr>
    <td colspan=2>Accesses, formats, collates, blends and extracts data from multiple identified sources in line with current industry standards</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Accesses, formats, collates, blends and extracts data from multiple identified sources in line with current industry standards'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Written Communication'])?$Assessment_Plan['Written Communication']:"";
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
</tr>
<tr>
    <td colspan=2>Locates and migrates data from already identified sources</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Locates and migrates data from already identified sources'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        $it_security = isset($Assessment_Plan['Research'])?$Assessment_Plan['Research']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
</tr>
<tr>
    <td colspan=2>Manipulates and links different data sets using tools and techniques to identify trends and patterns</td>
    <td style='text-align:center; vertical-align:middle'>" . $ss_result['Manipulates and links different data sets using tools and techniques to identify trends and patterns'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        $remote_infrastructure = isset($Assessment_Plan['Technologies'])?$Assessment_Plan['Technologies']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
</tr>
<tr>
    <td colspan=2>Presents data in a format appropriate to the task</td>
    <td style='text-align:center; vertical-align:middle'>" . $ss_result['Presents data in a format appropriate to the task'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        $data = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
</tr>
<tr>
    <td colspan=2>Summarises and explains the results of the gathered data</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Summarises and explains the results of the gathered data'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        $problem_solving = isset($Assessment_Plan['Customer Service'])?$Assessment_Plan['Customer Service']:"";
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>
</tr>
<tr>
    <td colspan=2>Identifies trends and patterns in data</td>
    <td style='text-align:center; vertical-align:middle'>" . $ss_result['Identifies trends and patterns in data'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        $workflow_management = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>
</tr>
<tr>
    <td colspan=2>Explains the different types of data sets and their formats</td>
    <td style='text-align:center; vertical-align:middle'>" . $ss_result['Explains the different types of data sets and their formats'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        $health_safety = isset($Assessment_Plan['Analysis'])?$Assessment_Plan['Analysis']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
</tr>
<tr>
    <td colspan=2>Describes the value of the data to the organisation and the importance of analysis management</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Describes the value of the data to the organisation and the importance of analysis management'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Specialist Areas'])?$Assessment_Plan['Specialist Areas']:"&nbsp";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Describes the role of data in the digital domain (including the use of external trusted data sets) and how it underpins every digital interaction including applications, devises, IoT and customer centricity</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Describes the role of data in the digital domain (including the use of external trusted data sets) and how it underpins every digital interaction including applications, devises, IoT and customer centricity'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Digital Tools'])?$Assessment_Plan['Digital Tools']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Explains the different types of data formats and data architectures including premises and cloud</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Explains the different types of data formats and data architectures including premises and cloud'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Digital Analytics'])?$Assessment_Plan['Digital Analytics']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Describes the characteristics of presentation tools to visualise and reviews the characteristics of data and communication tools and technologies for collaborative working</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Describes the characteristics of presentation tools to visualise and reviews the characteristics of data and communication tools and technologies for collaborative working'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Implementation'])?$Assessment_Plan['Implementation']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Applies algorithms and basic statistical methods to identify trends in data to audit results</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Applies algorithms and basic statistical methods to identify trends in data to audit results'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Effective Business Operation'])?$Assessment_Plan['Effective Business Operation']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Cross checks and filters data to identify faults</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Cross checks and filters data to identify faults'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Cleans, tests and assesses the confidence and integrity of the data</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Cleans, tests and assesses the confidence and integrity of the data'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Identifies opportunities to use automation</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Identifies opportunities to use automation'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status14!="")?$sss[$previous_review->skills_scan_status14]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Describes the methods of validating data how to identify common data quality and the importance of corrective action</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Describes the methods of validating data how to identify common data quality and the importance of corrective action'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Describes communication methods, formats and techniques commonly used and how these have been applied in a range of roles including customer, manager, client, peer, technical and nontechnical</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Describes communication methods, formats and techniques commonly used and how these have been applied in a range of roles including customer, manager, client, peer, technical and nontechnical'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Explains the legal requirements of using data and the importance of using data ethically</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Explains the legal requirements of using data and the importance of using data ethically'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Describes how they have communicated the results of data analysis to different audiences that assists understanding</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Describes how they have communicated the results of data analysis to different audiences that assists understanding'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Explains the significance of customer/end user issues, problems, value to the organisation, brand awareness, cultural awareness/ diversity, accessibility to both an internal and external audience</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Explains the significance of customer/end user issues, problems, value to the organisation, brand awareness, cultural awareness/ diversity, accessibility to both an internal and external audience'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Explains how they have stored, managed and distributed data in line with data security standards and legislation</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Explains how they have stored, managed and distributed data in line with data security standards and legislation'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Explains how they have produced clear and consistent technical documentation</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Explains how they have produced clear and consistent technical documentation'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Describes how they have reviewed their own development and kept up to date with developments in technologies, trends and innovation in regards to Data, Data Analysis & Data Science</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Describes how they have reviewed their own development and kept up to date with developments in technologies, trends and innovation in regards to Data, Data Analysis & Data Science'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Explains how they have integrated into a multi-functional team both internally and externally to their organisation in a Data Technician role</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Explains how they have integrated into a multi-functional team both internally and externally to their organisation in a Data Technician role'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Describes how they have worked in an inclusive manner</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Describes how they have worked in an inclusive manner'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status24!="")?$sss[$previous_review->skills_scan_status24]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Works independently as a Data Technician to meet required deadlines, managing stakeholder expectations</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Works independently as a Data Technician to meet required deadlines, managing stakeholder expectations'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status25!="")?$sss[$previous_review->skills_scan_status25]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Prioritises multiple data sets within the given task using own initiative</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Prioritises multiple data sets within the given task using own initiative'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status26!="")?$sss[$previous_review->skills_scan_status26]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html."<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr>
<tr>
    <td colspan=2>Works independently as a Data Technician, following standard procedures to complete prioritised tasks on time</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Works independently as a Data Technician, following standard procedures to complete prioritised tasks on time'] . "</td>
    <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status27!="")?$sss[$previous_review->skills_scan_status27]:"") . "</td>
    <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
</tr></tbody>
</table>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th></tr>
    </thead>
    <tbody><tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Approach to Work Workplace Project</td>
        <td>";
        if(isset($Assessment_Plan['Approach to Work Workplace Project'])) $html .= $Assessment_Plan['Approach to Work Workplace Project']; else "&nbsp;";
        $html.="</td><td>Data Gathering Workplace Project 1</td>
        <td>"; if(isset($Assessment_Plan['Data Gathering Workplace Project 1'])) $html .= $Assessment_Plan['Data Gathering Workplace Project 1']; else "&nbsp;";
        $html.="</td><td>Data Analysis & Validation Project 1</td>
        <td>"; if(isset($Assessment_Plan['Data Analysis & Validation Project 1'])) $html .= $Assessment_Plan['Data Analysis & Validation Project 1']; else "&nbsp;";
        $html.="</td>
    </tr>
    <tr>
        <td>Data Distribution & Dissemination Workplace Project 1</td>
        <td>";  if(isset($Assessment_Plan['Data Distribution & Dissemination Workplace Project 1'])) $html .= $Assessment_Plan['Data Distribution & Dissemination Workplace Project 1']; else "&nbsp;";
        $html.="</td>
        <td>Data Gathering Workplace Project 2</td>
        <td>";  if(isset($Assessment_Plan['Data Gathering Workplace Project 2'])) $html .= $Assessment_Plan['Data Gathering Workplace Project 2']; else "&nbsp;";
        $html.="</td>
        <td>Data Analysis & Validation Project 2</td>
        <td>"; if(isset($Assessment_Plan['Data Analysis & Validation Project 2'])) $html .= $Assessment_Plan['Data Analysis & Validation Project 2']; else "&nbsp;";
        $html.="</td>
    </tr>
    <tr>
        <td>Data Distribution & Dissemination Workplace Project 2</td>
        <td>"; if(isset($Assessment_Plan['Data Distribution & Dissemination Workplace Project 2'])) $html .= $Assessment_Plan['Data Distribution & Dissemination Workplace Project 2']; else "&nbsp;";
        $html.="</td>
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
</table>";

        return $html;
    }

    public static function getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {

    }
}
?>