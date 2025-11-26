<?php
class DataAnalystV2
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        $html="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th></tr>
    </thead><tbody>
    <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Skills Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td colspan=2>Identify, collect and migrate data to/from a range of internal and external systems</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Identify, collect and migrate data to/from a range of internal and external systems'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Written Communication'])?$Assessment_Plan['Written Communication']:"";
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td colspan=2>Manipulate and link different data sets as required</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Manipulate and link different data sets as required'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        $it_security = isset($Assessment_Plan['Research'])?$Assessment_Plan['Research']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
    </tr>
    <tr>
        <td colspan=2>Interpret and apply the organisation's data and information security Standards, policies and procedures to data management activities</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Interpret and apply the organisation data and information security Standards, policies and procedures to data management activities'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        $remote_infrastructure = isset($Assessment_Plan['Technologies'])?$Assessment_Plan['Technologies']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
    </tr>
    <tr>
        <td colspan=2>Collect and compile data from different sources</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Collect and compile data from different sources'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        $data = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
    </tr>
    <tr>
        <td colspan=2>Perform database queries across multiple tables to extract data for analysis</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Perform database queries across multiple tables to extract data for analysis'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        $problem_solving = isset($Assessment_Plan['Customer Service'])?$Assessment_Plan['Customer Service']:"";
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>
    </tr>
    <tr>
        <td colspan=2>Perform routine statistical analyses and ad-hoc queries</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Perform routine statistical analyses and ad-hoc queries'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        $workflow_management = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>
    </tr>
    <tr>
        <td colspan=2>Use a range of analytical techniques such as data mining, time series forecasting and modelling techniques to identify and predict trends and patterns in data</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Use a range of analytical techniques such as data mining, time series forecasting and modelling techniques to identify and predict trends and patterns in data'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        $health_safety = isset($Assessment_Plan['Analysis'])?$Assessment_Plan['Analysis']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
    </tr>
    <tr>
        <td colspan=2>Assist production of performance dashboards and reports</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Assist production of performance dashboards and reports'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Specialist Areas'])?$Assessment_Plan['Specialist Areas']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td colspan=2>Assist with data quality checking and cleansing</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Assist with data quality checking and cleansing'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Digital Tools'])?$Assessment_Plan['Digital Tools']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td colspan=2>Apply the tools and techniques for data analysis, data visualisation and presentation</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Apply the tools and techniques for data analysis, data visualisation and presentation'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Digital Analytics'])?$Assessment_Plan['Digital Analytics']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td colspan=2>Assist with the production of a range of ad-hoc and standard data analysis reports</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Assist with the production of a range of ad-hoc and standard data analysis reports'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Implementation'])?$Assessment_Plan['Implementation']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td colspan=2>Summarise and present the results of data analysis to a range of stakeholders making recommendations</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Summarise and present the results of data analysis to a range of stakeholders making recommendations'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Effective Business Operation'])?$Assessment_Plan['Effective Business Operation']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td colspan=2>Works with the organisations data architecture</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Works with the organisations data architecture'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr></tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
    </thead><tbody>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
    <tr>
        <td>The range of data protection and legal issues</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The range of data protection and legal issues'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status14!="")?$sss[$previous_review->skills_scan_status14]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The different types of data, including open and public data, administrative data, and research data</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The different types of data, including open and public data, administrative data, and research data'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The differences between structured and unstructured data</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The differences between structured and unstructured data'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The fundamentals of data structures, database system design, implementation and maintenance</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The fundamentals of data structures, database system design, implementation and maintenance'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The importance of the domain context for data analytics</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The importance of the domain context for data analytics'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The quality issues that can arise with data and how to avoid and/or resolve these</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The quality issues that can arise with data and how to avoid and/or resolve these'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The importance of clearly defining customer requirements for data analysis</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The importance of clearly defining customer requirements for data analysis'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The processes and tools used for data integration</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The processes and tools used for data integration'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The steps involved in carrying out routine data analysis tasks</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The steps involved in carrying out routine data analysis tasks'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>How to use and apply industry standard tools and methods for data analysis</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How to use and apply industry standard tools and methods for data analysis'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
    </tr>
    </tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
    </thead>
    <tbody><tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Previous Review Skill Scan Status</td><td>Current Skills Scan Status</td></tr>
    <tr>
        <td>Logical and creative thinking skills</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status25!="")?$sss[$previous_review->skills_scan_status25]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status26!="")?$sss[$previous_review->skills_scan_status26]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status27!="")?$sss[$previous_review->skills_scan_status27]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status28!="")?$sss[$previous_review->skills_scan_status28]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status28", $ss_statuses, $form_arf->skills_scan_status28, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status29!="")?$sss[$previous_review->skills_scan_status29]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status29", $ss_statuses, $form_arf->skills_scan_status29, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status30!="")?$sss[$previous_review->skills_scan_status30]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status30", $ss_statuses, $form_arf->skills_scan_status30, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status31!="")?$sss[$previous_review->skills_scan_status31]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status31", $ss_statuses, $form_arf->skills_scan_status31, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status32!="")?$sss[$previous_review->skills_scan_status32]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status32", $ss_statuses, $form_arf->skills_scan_status32, true, false, $assessor_signed) . "</td>
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
    <tbody>
    <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Data Architecture & Processes Workplace Project</td>
        <td>"; if(isset($Assessment_Plan['Data Architecture & Processes Workplace Project'])) $html.= $Assessment_Plan['Data Architecture & Processes Workplace Project']; else "&nbsp;"; $html.="</td>
        <td>Collecting & Cleansing Data Workplace Project 1</td>
        <td>"; if(isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 1'])) echo $Assessment_Plan['Collecting & Cleansing Data Workplace Project 1']; else "&nbsp;"; $html.="</td>
        <td>Analysing Data Workplace Project 1</td>
        <td>"; if(isset($Assessment_Plan['Analysing Data Workplace Project 1'])) echo $Assessment_Plan['Analysing Data Workplace Project 1']; else "&nbsp;"; $html .= "</td>
    </tr>
    <tr>
        <td>Reporting Data Workplace Project 1</td>
        <td>"; if(isset($Assessment_Plan['Reporting Data Workplace Project 1'])) echo $Assessment_Plan['Reporting Data Workplace Project 1']; else "&nbsp;"; $html.="</td>
        <td>Collecting & Cleansing Data Workplace Project 2</td>
        <td>"; if(isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 2'])) echo $Assessment_Plan['Collecting & Cleansing Data Workplace Project 2']; else "&nbsp;"; $html.="</td>
        <td>Analysing Data Workplace Project 2</td>
        <td>"; if(isset($Assessment_Plan['Analysing Data Workplace Project 2'])) echo $Assessment_Plan['Analysing Data Workplace Project 2']; else "&nbsp;"; $html.="</td>
    </tr>
    <tr>
        <td>Reporting Data Workplace Project 2</td>
        <td>"; if(isset($Assessment_Plan['Reporting Data Workplace Project 2'])) echo $Assessment_Plan['Reporting Data Workplace Project 2']; else "&nbsp;"; $html.="</td>
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

        return $html;
    }

    public static function getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {

$html="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th></tr>
    </thead>
    <tbody>
    <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
    <tr>
        <td>Data Analysis Concepts Part 1</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Data Analysis Concepts Part 1") . "<br>" . ReviewSkillsScans::getEventDate($events,'Data Analysis Concepts Part 1') . "</td>
        <td>Data Analysis Concepts Part 2</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Data Analysis Concepts Part 2") . "<br>" . ReviewSkillsScans::getEventDate($events,'Data Analysis Concepts Part 2') . "</td>
        <td>Data Analysis Tools Part 1</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Data Analysis Tools Part 1") . "<br>" . ReviewSkillsScans::getEventDate($events,'Data Analysis Tools Part 1') . "</td>
    </tr>
    <tr>
        <td>Data Analysis Tools Part 2</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Data Analysis Tools Part 2") . "<br>" . ReviewSkillsScans::getEventDate($events,'Data Analysis Tools Part 2') . "</td>
        <td>BCS Level 4 Dip in Data Analysis Concepts Test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "BCS Level 4 Dip in Data Analysis Concepts Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'BCS Level 4 Dip in Data Analysis Concepts Test') . "</td>
        <td>BCS Level 4 Cert in Data Analysis Tools Test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "BCS Level 4 Cert in Data Analysis Tools Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'BCS Level 4 Cert in Data Analysis Tools Test') . "</td>
    </tr>
    <tr>
        <td>Implementing Data Analysis Concepts and Tools  EPA Prep Course</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Implementing Data Analysis Concepts and Tools  EPA Prep Course") . "<br>" . ReviewSkillsScans::getEventDate($events,'Implementing Data Analysis Concepts and Tools  EPA Prep Course') . "</td>
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
    </tbody>
</table>
<br>";

    return $html;
    
    }
}
?>