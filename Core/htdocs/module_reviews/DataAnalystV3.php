<?php
class DataAnalystV3
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
        <td colspan=2>Use data systems securely to meet requirements and in line with organisational procedures and legislation, including principles of Privacy by Design</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Use data systems securely to meet requirements and in line with organisational procedures and legislation, including principles of Privacy by Design'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 1']) and isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 2']) and $Assessment_Plan['Collecting & Cleansing Data Workplace Project 1']=="Complete" and $Assessment_Plan['Collecting & Cleansing Data Workplace Project 2']=="Complete")
            $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            echo "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Implement the stages of the data analysis lifecycle</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Implement the stages of the data analysis lifecycle'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 1']) and isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 2']) and $Assessment_Plan['Collecting & Cleansing Data Workplace Project 1']=="Complete" and $Assessment_Plan['Collecting & Cleansing Data Workplace Project 2']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Apply principles of data classification within data analysis activity</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Apply principles of data classification within data analysis activity'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Data Architecture & Processes Workplace Project']) and $Assessment_Plan['Data Architecture & Processes Workplace Project']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Analyse data sets taking account of different data structures and database designs</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Analyse data sets taking account of different data structures and database designs'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 1']) and isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 2']) and $Assessment_Plan['Collecting & Cleansing Data Workplace Project 1']=="Complete" and $Assessment_Plan['Collecting & Cleansing Data Workplace Project 2']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Assess the impact on user experience and domain context on the data analysis activity</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Assess the impact on user experience and domain context on the data analysis activity'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 1']) and isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 2']) and $Assessment_Plan['Collecting & Cleansing Data Workplace Project 1']=="Complete" and $Assessment_Plan['Collecting & Cleansing Data Workplace Project 2']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Identify and escalate quality risks in data analysis with suggested mitigation/resolutions as appropriate</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Identify and escalate quality risks in data analysis with suggested mitigation/resolutions as appropriate'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Analysing Data Workplace Project 1']) and isset($Assessment_Plan['Analysing Data Workplace Project 2']) and $Assessment_Plan['Analysing Data Workplace Project 1']=="Complete" and $Assessment_Plan['Analysing Data Workplace Project 2']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Undertake customer requirements analysis and implement findings in data analytics planning and outputs</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Undertake customer requirements analysis and implement findings in data analytics planning and outputs'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Analysing Data Workplace Project 1']) and isset($Assessment_Plan['Analysing Data Workplace Project 2']) and $Assessment_Plan['Analysing Data Workplace Project 1']=="Complete" and $Assessment_Plan['Analysing Data Workplace Project 2']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Identify data sources and the risks, challenges to combination within data analysis activity</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Identify data sources and the risks, challenges to combination within data analysis activity'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Analysing Data Workplace Project 1']) and isset($Assessment_Plan['Analysing Data Workplace Project 2']) and $Assessment_Plan['Analysing Data Workplace Project 1']=="Complete" and $Assessment_Plan['Analysing Data Workplace Project 2']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Apply organizational architecture requirements to data analysis activities</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Apply organizational architecture requirements to data analysis activities'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 1']) and isset($Assessment_Plan['Collecting & Cleansing Data Workplace Project 2']) and $Assessment_Plan['Collecting & Cleansing Data Workplace Project 1']=="Complete" and $Assessment_Plan['Collecting & Cleansing Data Workplace Project 2']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Apply statistical methodologies to data analysis tasks</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Apply statistical methodologies to data analysis tasks'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Analysing Data Workplace Project 1']) and isset($Assessment_Plan['Analysing Data Workplace Project 2']) and $Assessment_Plan['Analysing Data Workplace Project 1']=="Complete" and $Assessment_Plan['Analysing Data Workplace Project 2']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Apply predictive analytics in the collation and use of data</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Apply predictive analytics in the collation and use of data'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Reporting Data Workplace Project 1']) and isset($Assessment_Plan['Reporting Data Workplace Project 2']) and $Assessment_Plan['Reporting Data Workplace Project 1']=="Complete" and $Assessment_Plan['Reporting Data Workplace Project 2']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Collaborate and communicate with a range of internal and external stakeholders using appropriate styles and behaviours to suit the audience</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Collaborate and communicate with a range of internal and external stakeholders using appropriate styles and behaviours to suit the audience'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Reporting Data Workplace Project 1']) and isset($Assessment_Plan['Reporting Data Workplace Project 2']) and $Assessment_Plan['Reporting Data Workplace Project 1']=="Complete" and $Assessment_Plan['Reporting Data Workplace Project 2']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Use a range of analytical techniques such as data mining, time series forecasting and modelling techniques to identify and predict trends and patterns in data</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Use a range of analytical techniques such as data mining, time series forecasting and modelling techniques to identify and predict trends and patterns in data'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Data Architecture & Processes Workplace Project']) and $Assessment_Plan['Data Architecture & Processes Workplace Project']=="Complete")
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
    $html.="</tr>
    <tr>
        <td colspan=2>Collate and interpret qualitative and quantitative data and convert into infographics, reports, tables, dashboards and graphs</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Collate and interpret qualitative and quantitative data and convert into infographics, reports, tables, dashboards and graphs'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status14!="")?$sss[$previous_review->skills_scan_status14]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Data Architecture & Processes Workplace Project']) and $Assessment_Plan['Data Architecture & Processes Workplace Project']=="Complete")
            $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
        $html.="</tr>
    <tr>
        <td colspan=2>Select and apply the most appropriate data tools to achieve the best outcome</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Select and apply the most appropriate data tools to achieve the best outcome'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>";
        if(isset($Assessment_Plan['Data Architecture & Processes Workplace Project']) and $Assessment_Plan['Data Architecture & Processes Workplace Project']=="Complete")
            $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
        else
            $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
        $html.="</tr>
    </tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
    </thead><tbody>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
    <tr>
        <td>Current relevant legislation and its application to the safe use of data</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Current relevant legislation and its application to the safe use of data'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Organisational data and information security standards, policies and procedures relevant to data management activities</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Organisational data and information security standards, policies and procedures relevant to data management activities'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Principles of the data life cycle and the steps involved in carrying out routine data analysis tasks</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of the data life cycle and the steps involved in carrying out routine data analysis tasks'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Principles of data, including open and public data, administrative data, and research data</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of data, including open and public data, administrative data, and research data'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The differences between structured and unstructured data</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The differences between structured and unstructured data'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The fundamentals of data structures, database system design, implementation and maintenance</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The fundamentals of data structures, database system design, implementation and maintenance'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Principles of user experience and domain context for data analytics</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of user experience and domain context for data analytics'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Quality risks inherent in data and how to mitigate/resolve these</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Quality risks inherent in data and how to mitigate/resolve these'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Principal approaches to defining customer requirements for data analysis</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Principal approaches to defining customer requirements for data analysis'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status24!="")?$sss[$previous_review->skills_scan_status24]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Approaches to combining data from different sources</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Approaches to combining data from different sources'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status25!="")?$sss[$previous_review->skills_scan_status25]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Approaches to organisational tools and methods for data analysis</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Approaches to organisational tools and methods for data analysis'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status26!="")?$sss[$previous_review->skills_scan_status26]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Organisational data architecture</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Organisational data architecture'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status27!="")?$sss[$previous_review->skills_scan_status27]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Principles of statistics for analysing datasets</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of statistics for analysing datasets'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status28!="")?$sss[$previous_review->skills_scan_status28]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status28", $ss_statuses, $form_arf->skills_scan_status28, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The principles of descriptive, predictive and prescriptive analytics</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The principles of descriptive, predictive and prescriptive analytics'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status29!="")?$sss[$previous_review->skills_scan_status29]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status29", $ss_statuses, $form_arf->skills_scan_status29, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The ethical aspects associated with the use of and collation of data</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The ethical aspects associated with the use of and collation of data'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status30!="")?$sss[$previous_review->skills_scan_status30]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status30", $ss_statuses, $form_arf->skills_scan_status30, true, false, $assessor_signed) . "</td>
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
        <td>Maintain productive, professional and secure working environment</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Maintain productive, professional and secure working environment'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status31!="")?$sss[$previous_review->skills_scan_status31]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status31", $ss_statuses, $form_arf->skills_scan_status31, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example1 . "
        </i></td>
    </tr>
    <tr>
        <td>Shows initiative, being resourceful when faced with a problem and taking responsibility for solving problems within their own remit</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Shows initiative, being resourceful when faced with a problem and taking responsibility for solving problems within their own remit'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status32!="")?$sss[$previous_review->skills_scan_status32]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status32", $ss_statuses, $form_arf->skills_scan_status32, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example2 . "
        </i></td>
    </tr>
    <tr>
        <td>Works independently and collaboratively</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Works independently and collaboratively'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status33!="")?$sss[$previous_review->skills_scan_status33]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status33", $ss_statuses, $form_arf->skills_scan_status33, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example3 . "
        </i></td>
    </tr>
    <tr>
        <td>Works logically and analytically</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Works logically and analytically'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status34!="")?$sss[$previous_review->skills_scan_status34]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status34", $ss_statuses, $form_arf->skills_scan_status34, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example4 . "
        </i></td>
    </tr>
    <tr>
        <td>Identifies issues quickly, enjoys investigating and solving complex problems and applies appropriate solutions. Has a strong desire to push to ensure the true root cause of any problem is found and a solution is identified which prevents recurrence</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Identifies issues quickly, enjoys investigating and solving complex problems and applies appropriate solutions. Has a strong desire to push to ensure the true root cause of any problem is found and a solution is identified which prevents recurrence'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status35!="")?$sss[$previous_review->skills_scan_status35]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status35", $ss_statuses, $form_arf->skills_scan_status35, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example5 . "
        </i></td>
    </tr>
    <tr>
        <td>Demonstrates resilience by viewing obstacles as challenges and learning from failure</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Demonstrates resilience by viewing obstacles as challenges and learning from failure'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status36!="")?$sss[$previous_review->skills_scan_status36]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status36", $ss_statuses, $form_arf->skills_scan_status36, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example6 . "
        </i></td>
    </tr>
    <tr>
        <td>Demonstrates an ability to adapt to changing contexts within the scope of a project, direction of the organisation or Data Analyst role</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Demonstrates an ability to adapt to changing contexts within the scope of a project, direction of the organisation or Data Analyst role'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status37!="")?$sss[$previous_review->skills_scan_status37]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status37", $ss_statuses, $form_arf->skills_scan_status37, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example7 . "
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
        <td>"; if(isset($Assessment_Plan['Analysing Data Workplace Project 1'])) echo $Assessment_Plan['Analysing Data Workplace Project 1']; else "&nbsp;"; $html.="</td>
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
        <td>Data Analysis Concepts</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Data Analysis Concepts") . "<br>" . ReviewSkillsScans::getEventDate($events,'Data Analysis Concepts') . "</td>
        <td>SQL for Data Analysis</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "SQL for Data Analysis") . "<br>" . ReviewSkillsScans::getEventDate($events,'SQL for Data Analysis') . "</td>
        <td>Data Analysis Using Python and R</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Data Analysis Using Python and R") . "<br>" . ReviewSkillsScans::getEventDate($events,'Data Analysis Using Python and R') . "</td>
    </tr>
    <tr>
        <td>Data Visualisation</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Data Visualisation") . "<br>" . ReviewSkillsScans::getEventDate($events,'Data Visualisation') . "</td>
        <td>Implementing Data Analysis Tools and Techniques</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Implementing Data Analysis Tools and Techniques") . "<br>" . ReviewSkillsScans::getEventDate($events,'Implementing Data Analysis Tools and Techniques') . "</td>
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