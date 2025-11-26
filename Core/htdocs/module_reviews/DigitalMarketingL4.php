<?php
class DigitalMarketingL4
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        $html="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Technical Knowledge and Competence</th></tr>
    </thead><tbody>
    <tr><td colspan=2>Technical Knowledge and Competence</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td rowspan=4>Marketing Plan</td>
        <td>1. Understanding digital marketing plans </td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $tk_result['Marketing Planning'] . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Marketing Planning & Ethics') . "<br>" . ReviewSkillsScans::getEventDate($events,'Legalities of Digital Marketing') . "</td>
    <tr><td>2. Understand how market segmentation contributes to marketing planning</td></tr>
    <tr><td>3. Understand how to develop a promotional mix for effective marketing</td></tr>
    <tr><td>4. Understand how branding is used across digital marketing channels</td></tr>
    </tr>
    <tr>
        <td rowspan=2>Ethics and Legalities of Digital Marketing</td>
        <td>1. Understand the implications of ethics to digital marketing</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . $tk_result['Ethics and Legalities of Digital Marketing'] . "</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>
        <td rowspan=2 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Marketing Planning & Ethics') . "<br>" . ReviewSkillsScans::getEventDate($events,'Legalities of Digital Marketing') . "</td>
    <tr><td>2. Understand the effect of legal and regulatory requirements on digital marketing</td></tr>
    </tr>
    <tr>
        <td rowspan=4>Business Concepts</td>
        <td>1. Understand the relationship between business objectives and structures</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $tk_result['Business Concepts'] . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Business Concepts') . "<br>" . ReviewSkillsScans::getEventDate($events,'Project Management') . "</td>
    <tr><td>2. Understand how the external environment affects business models</td></tr>
    <tr><td>3. Be able to lead a team</td></tr>
    <tr><td>4. Understand how finance affects a business operation</td></tr>
    </tr>
    <tr>
        <td rowspan=4>Project Management</td>
        <td>1. Understand why organisations use project management</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $tk_result['Project Management'] . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Business Concepts') . "<br>" . ReviewSkillsScans::getEventDate($events,'Project Management') . "</td>
    <tr><td>2. Understand how to set up projects</td></tr>
    <tr><td>3. Be able to use project management tools to maintain, control and monitor projects</td></tr>
    <tr><td>4. Be able to review projects at all stages</td></tr>
    </tr>
    <tr>
        <td rowspan=3>Digital Marketing Metrics & Analytics</td>
        <td>1. Understand the sales funnel in digital marketing</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $tk_result['Digital Marketing Metrics & Analytics'] . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Digital Marketing Metrics') . "<br>" . ReviewSkillsScans::getEventDate($events,'Analytics & Content Marketing') . "</td>
    <tr><td>2. Understand how metrics of digital marketing are generated</td></tr>
    <tr><td>3. Understand analytics of digital marketing</td></tr>
    </tr>
    <tr>
        <td rowspan=3>Content Marketing</td>
        <td>1. Understand the uses of content marketing</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $tk_result['Content Marketing'] . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Digital Marketing Metrics') . "<br>" . ReviewSkillsScans::getEventDate($events,'Analytics & Content Marketing') . "</td>
    <tr><td>2. Understand technology used in content marketing</td></tr>
    <tr><td>3. Be able to manage a content marketing campaign</td></tr>
    </tr>
    <tr>
        <td rowspan=3>Marketing on Mobile</td>
        <td>1. Understand how uniqueness of mobile technologies affects marketing</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $tk_result['Marketing on Mobile'] . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Marketing on Mobile') . "<br>" . ReviewSkillsScans::getEventDate($events,'Email Marketing') . "</td>
    <tr><td>2. Understand mobile marketing communications</td></tr>
    <tr><td>3. Understand the use of location aware applications for business</td></tr>
    </tr>
    <tr>
        <td rowspan=3>Marketing on Mobile</td>
        <td>1. Understand how uniqueness of mobile technologies affects marketing</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $tk_result['Marketing on Mobile'] . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Marketing on Mobile') . "<br>" . ReviewSkillsScans::getEventDate($events,'Email Marketing') . "</td>
    <tr><td>2. Understand mobile marketing communications</td></tr>
    <tr><td>3. Understand the use of location aware applications for business</td></tr>
    </tr>
    <tr>
        <td rowspan=3>Email Marketing</td>
        <td>1. Understand requirements for email marketing campaigns</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $tk_result['Email Marketing'] . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Marketing on Mobile') . "<br>" . ReviewSkillsScans::getEventDate($events,'Email Marketing') . "</td>
    <tr><td>2. Understand design criteria for email marketing campaigns</td></tr>
    <tr><td>3. Be able to run email marketing campaigns</td></tr>
    </tr>
    <tr>
        <td rowspan=3>Retention Marketing</td>
        <td>1. Understanding the value of customer data to retention marketing</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $tk_result['Retention Marketing'] . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Retention Marketing & Principles of Keywords') . "<br>" . ReviewSkillsScans::getEventDate($events,'Optimisation') . "</td>
    <tr><td>2. Understand how organisations achieve positive customer relations</td></tr>
    <tr><td>3. Understand strategies for retention marketing</td></tr>
    </tr>
    <tr>
        <td rowspan=3>Video Channel Marketing</td>
        <td>1. Understand video channel technologies</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $tk_result['Video Channel Marketing'] . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Video Channel Marketing') . "<br>" . ReviewSkillsScans::getEventDate($events,'Using Collaborative Technologies in a Business') . "</td>
    <tr><td>2. Understand requirements for video channel management</td></tr>
    <tr><td>3. Be able to manage a video channel</td></tr>
    </tr>
    <tr>
        <td rowspan=4>Personal & Professional Development</td>
        <td>1. Understand how to plan for personal and professional development</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $tk_result['Personal & Professional Development'] . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Personal & Professional Development') . "</td>
    <tr><td>2. Understand how people learn</td></tr>
    <tr><td>3. Be able to produce personal and professional development plans</td></tr>
    <tr><td>4. Be able to make recommendations for personal and professional development</td></tr>
    </tr>
    <tr>
        <td rowspan=3>Designing an Effective Web User Experience</td>
        <td>1. Understand the web-based user experience</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . $tk_result['Designing an Effective Web User Experience'] . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>
        <td rowspan=3 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Designing an Effective Web User Experience') . "<br>" . ReviewSkillsScans::getEventDate($events,'CMS Website Creation') . "</td>
    <tr><td>2. Be able to optimise website user experience</td></tr>
    <tr><td>3. Be able to test the website user experience</td></tr>
    </tr>
    <tr>
        <td rowspan=4>Principles of Keywords and Optimisation</td>
        <td>1.  Understand SEO</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of Keywords and Optimisation'] . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Retention Marketing & Principles of Keywords') . "<br>" . ReviewSkillsScans::getEventDate($events,'Optimisation') . "</td>
    <tr><td>2. Be able to plan implementation of SEO techniques</td></tr>
    <tr><td>3. Understand SMO</td></tr>
    <tr><td>4. Understand how to plan implementation of a website optimised for mobile devices</td></tr>
    </tr>
    <tr>
        <td rowspan=4>CMS Website Creation</td>
        <td>1. Be able to create a plan for the components of a CMS website</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $tk_result['CMS Website Creation'] . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Designing an Effective Web User Experience') . "<br>" . ReviewSkillsScans::getEventDate($events,'CMS Website Creation') . "</td>
    <tr><td>2. Be able to use CMS software to create a website</td></tr>
    <tr><td>3. Understand how to make a website accessible</td></tr>
    <tr><td>4. Be able to measure and improve the ROI of a website</td></tr>
    </tr>
   <tr>
        <td rowspan=4>Using Collaborative Technologies</td>
        <td>1. Stay safe and secure when working with collaborative technology</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $tk_result['CMS Website Creation'] . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Video Channel Marketing') . "<br>" . ReviewSkillsScans::getEventDate($events,'Using Collaborative Technologies in a Business') . "</td>
    <tr><td>2. Plan and set up IT tools and devices for collaborative working</td></tr>
    <tr><td>3. Prepare collaborative technology for use</td></tr>
    <tr><td>4. Manage tasks using collaborative technologies</td></tr>
    </tr></tbody>
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
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-406-PD","ITP-410-PD")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Analytical and problem solving skills</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Analytical and problem solving skills'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-406-PD","ITP-410-PD")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Ability to work independently and to take responsibility</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work independently and to take responsibility'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-406-PD","ITP-410-PD")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Can use own initiative</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Can use own initiative'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-406-PD","ITP-410-PD")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>A thorough and organised approach</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A thorough and organised approach'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-406-PD","ITP-410-PD")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Ability to work with a range of internal and external people</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work with a range of internal and external people'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-406-PD","ITP-410-PD")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Ability to communicate effectively in a variety of situations</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to communicate effectively in a variety of situations'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-406-PD","ITP-410-PD")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr>
    <tr>
        <td>Maintain productive, professional and secure working environment</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Maintain productive, professional and secure working environment'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("ITP-406-PD","ITP-410-PD")))
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $html.="</tr></tbody>
</table>
<br>";

        $html.="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th></tr>
    </thead><tbody>
    <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Activity 1 - Project 1 Personal and Professional Development</td>
        <td>";
        if(isset($Assessment_Plan['Activity 1 - Project 1 Personal and Professional Development'])) $html.= $Assessment_Plan['Activity 1 - Project 1 Personal and Professional Development']; $html.= "</td>
        <td>Activity 2 - Project 2 SWOT Analysis</td>
        <td>"; if(isset($Assessment_Plan['Activity 2 - Project 2 SWOT Analysis'])) $html.= $Assessment_Plan['Activity 2 - Project 2 SWOT Analysis']; $html.= "</td>
        <td>Activity 3 - Project 2 Dream job specification</td>
        <td>"; if(isset($Assessment_Plan['Activity 3 - Project 2 Dream job specification'])) $html.= $Assessment_Plan['Activity 3 - Project 2 Dream job specification']; $html.= "</td>
    </tr>
    <tr>
        <td>Activity 4 - Project 2 PDP</td>
        <td>"; if(isset($Assessment_Plan['Activity 4 - Project 2 PDP'])) $html.= $Assessment_Plan['Activity 4 - Project 2 PDP']; $html.= "</td>
        <td>Activity 5 - Project 2 Learning Styles</td>
        <td>"; if(isset($Assessment_Plan['Activity 5 - Project 2 Learning Styles'])) $html.= $Assessment_Plan['Activity 5 - Project 2 Learning Styles']; $html.= "</td>
        <td>Activity 6 - Project 2/3 diary of progress</td>
        <td>"; if(isset($Assessment_Plan['Activity 6 - Project 2/3 diary of progress'])) $html.= $Assessment_Plan['Activity 6 - Project 2/3 diary of progress']; $html.= "</td>
    </tr>
    <tr>
        <td>Activity 7 - Project 2 report</td>
        <td>"; if(isset($Assessment_Plan['Activity 7 - Project 2 report'])) $html.=$Assessment_Plan['Activity 7 - Project 2 report']; $html.= "</td>
        <td>Activity 8 - Project 3 presentation</td>
        <td>"; if(isset($Assessment_Plan['Activity 8 - Project 3 presentation'])) $html.= $Assessment_Plan['Activity 8 - Project 3 presentation']; $html.= "</td>
        <td>Activity 9 - ERR</td>
        <td>"; if(isset($Assessment_Plan['Activity 9 - ERR'])) $html.= $Assessment_Plan['Activity 9 - ERR']; $html.= "</td>
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
        <td>Business Concepts AND Project Management</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Business Concepts and Project Management") . "<br>" . ReviewSkillsScans::getEventDate($events,'Business Concepts and Project Management') . "</td>
        <td>Marketing Planning Ethics and Legalities</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Marketing Planning Ethics and Legalities") . "<br>" . ReviewSkillsScans::getEventDate($events,'Marketing Planning Ethics and Legalities') . "</td>
        <td>Mobile Marketing and Email Marketing</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Mobile Marketing and Email Marketing") . "<br>" . ReviewSkillsScans::getEventDate($events,'Mobile Marketing and Email Marketing') . "</td>
    </tr>
    <tr>
        <td>Video Channel Management and Using Collaborative Technologies</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Video Channel Management and Using Collaborative Technologies") . "<br>" . ReviewSkillsScans::getEventDate($events,'Video Channel Management and Using Collaborative Technologies') . "</td>
        <td>Designing an Effective Web User Experience and CMS Website Creation</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Designing an Effective Web User Experience and CMS Website Creation") . "<br>" . ReviewSkillsScans::getEventDate($events,'Designing an Effective Web User Experience and CMS Website Creation') . "</td>
        <td>Content Marketing and Metrics and Analytics</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Content Marketing and Metrics and Analytics") . "<br>" . ReviewSkillsScans::getEventDate($events,'Content Marketing and Metrics and Analytics') . "</td>
    </tr>
    <tr>
        <td>Retention Marketing and Principles of Keywords and Optimisation</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Retention Marketing and Principles of Keywords and Optimisation") . "<br>" . ReviewSkillsScans::getEventDate($events,'Retention Marketing and Principles of Keywords and Optimisation') . "</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr></tbody>
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
    return $html;

    }
}
?>