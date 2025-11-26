<?php
class DigitalMarketingV3
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        $h="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th></tr>
    </thead><tbody>
    <tr><td colspan=2>Competence</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td rowspan=1>Communication</td>
        <td>Applies a good level of written communication skills for a range of audiences and digital platforms and with regard to the sensitivity of communication</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Communication'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Written Communication'])?$Assessment_Plan['Written Communication']:"";
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Research</td>
        <td>Analyses and contributes information on the digital environment to inform short and long term digital communications strategies and campaigns</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Research'] . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        $it_security = isset($Assessment_Plan['Research'])?$Assessment_Plan['Research']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
    </tr>
    <tr>
        <td>Technologies</td>
        <td>Recommends and applies effective, secure and appropriate solutions using a wide variety of digital technologies and tools over a range of platforms and user interfaces to achieve marketing objectives</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Technologies'] . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        $remote_infrastructure = isset($Assessment_Plan['Technologies'])?$Assessment_Plan['Technologies']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
    </tr>
    <tr>
        <td>Data</td>
        <td>Reviews, monitors and analyses online activity and provides recommendations and insights to others</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Data'] . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        $data = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
    </tr>
    <tr>
        <td rowspan=1>Customer Service</td>
        <td>Responds efficiently to enquiries using online and social media platforms.</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Customer Service'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        $problem_solving = isset($Assessment_Plan['Customer Service'])?$Assessment_Plan['Customer Service']:"";
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>
    </tr>
    <tr>
        <td>Problem Solving</td>
        <td>Applies structured techniques to problem solving, and analyses problems and resolves issues across a variety of digital platforms</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Problem Solving'] . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        $workflow_management = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>
    </tr>
    <tr>
        <td>Analysis</td>
        <td>Creates basic analytical dashboards using appropriate digital tools</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Analysis'] . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        $health_safety = isset($Assessment_Plan['Analysis'])?$Assessment_Plan['Analysis']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
    </tr>
    <tr>
        <td rowspan=1>Specialist Areas</td>
        <td>Search marketing, search engine optimisation, e mail marketing, web analytics and metrics, mobile apps and Pay-Per-Click</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Specialist Areas'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Specialist Areas'])?$Assessment_Plan['Specialist Areas']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td rowspan=1>Digital Tools</td>
        <td>Effective use of digital tools</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Digital Tools'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Digital Tools'])?$Assessment_Plan['Digital Tools']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td rowspan=1>Digital Analytics</td>
        <td>Measures and evaluates the success of digital marketing activities</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Digital Analytics'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Digital Analytics'])?$Assessment_Plan['Digital Analytics']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td rowspan=4>Implementation</td>
        <td>Developments in digital media technologies and trends</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $ss_result['Implementation'] . "</td>
        <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Implementation'])?$Assessment_Plan['Implementation']:"";
        $h.="<td rowspan=4 style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr><td>Marketing briefs and plans</td></tr>
    <tr><td>Company defined customer standards or industry good practice</td></tr>
    <tr><td>Company, team or client approaches to continuous integration</td></tr>
    <tr>
        <td rowspan=1>Effective Business Operation</td>
        <td>Operate effectively in own business, customers and industry environments</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Environment'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Effective Business Operation'])?$Assessment_Plan['Effective Business Operation']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td rowspan=1>Industry Developments and Practices</td>
        <td>Developments in digital media technologies and trends<br>Marketing briefs and plans<br>Company defined customer standards or industry good practice<br>Company, team or client approaches to continuous integration</td>";
        if(isset($ss_result['Industry Developments and Practices']))
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Industry Developments and Practices'] . "</td>";
        elseif(isset($ss_result['Industry developments and practices']))
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Industry developments and practices'] . "</td>";
        else
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr><tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
    </thead><tbody>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td>The principles of coding</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The principles of coding'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'9628-11 Principles of Coding Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-11 Principles of Coding Test') . "</td>
    </tr>
    <tr>
        <td>Applying basic marketing principles</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Applying basic marketing principles'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'9628-12 Principles of Online and Offline Marketing Theory Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-12 Principles of Online and Offline Marketing Theory Test') . "</td>
    </tr>
   <tr>
        <td>Applying the customer lifecycle</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Applying the customer lifecycle'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'9628-12 Principles of Online and Offline Marketing Theory Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-12 Principles of Online and Offline Marketing Theory Test') . "</td>
    </tr>
    <tr>
        <td>The role of customer relationship marketing</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The role of customer relationship marketing'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'9628-12 Principles of Online and Offline Marketing Theory Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-12 Principles of Online and Offline Marketing Theory Test') . "</td>
    </tr>
   <tr>
        <td>How  teams work effectively to deliver digital marketing campaigns and can deliver accordingly</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How  teams work effectively to deliver digital marketing campaigns and can deliver accordingly'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'9628-12 Principles of Online and Offline Marketing Theory Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-12 Principles of Online and Offline Marketing Theory Test') . "</td>
    </tr>
    <tr>
        <td>The main components of Digital and Social Media Strategies</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The main components of Digital and Social Media Strategies'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Google Analytics IQ Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Google Analytics IQ Test') . "</td>
    </tr>
    <tr>
        <td>The principles of all of the following specialist areas: search marketing, search engine optimisation, e mail marketing, web analytics and metrics, mobile apps and Pay-Per-Click and understands how these can work together</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The principles of all of the following specialist areas: search marketing, search engine optimisation, e mail marketing, web analytics and metrics, mobile apps and Pay-Per-Click and understands how these can work together'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Google Analytics IQ Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Google Analytics IQ Test') . "</td>
    </tr>
    <tr>
        <td>The similarities and differences, including positives and negatives, of all the major digital and social media platforms</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The similarities and differences, including positives and negatives, of all the major digital and social media platforms'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Google Analytics IQ Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Google Analytics IQ Test') . "</td>
    </tr>
    <tr>
        <td>Responds to the business environment and business issues related to digital marketing and customer needs</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Responds to the business environment and business issues related to digital marketing and customer needs'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Google Analytics IQ Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Google Analytics IQ Test') . "</td>
    </tr>
    <tr>
        <td>Digital etiquette</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Digital etiquette'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Google Analytics IQ Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Google Analytics IQ Test') . "</td>
    </tr>
    <tr>
        <td>How digital platforms integrate in to the working environment</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How digital platforms integrate in to the working environment'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Google Analytics IQ Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Google Analytics IQ Test') . "</td>
    </tr>
    <tr>
        <td>Required security levels necessary to protect data across digital and social media platforms</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Required security levels necessary to protect data across digital and social media platforms'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Google Analytics IQ Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Google Analytics IQ Test') . "</td>
    </tr></tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
    </thead><tbody>
    <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td>Logical and creative thinking skills - A</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills - A'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Research","Technologies","Data","Customer Service","Problem Solving","Analysis","Specialist Areas","Digital Analytics")))
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr>
        <td>Analytical and problem solving skills - B</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Analytical and problem solving skills - B'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Research","Technologies","Data","Customer Service","Problem Solving","Digital Tools","Digital Analytics")))
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr>
        <td>Ability to work independently and to take responsibility - C</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work independently and to take responsibility - C'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Research","Technologies","Customer Service","Problem Solving","Specialist Areas","Implementation","Environment")))
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr>
        <td>Can use own initiative - D</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Can use own initiative - D'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status28", $ss_statuses, $form_arf->skills_scan_status28, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Customer Service","Problem Solving","Implementation","Environment")))
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr>
        <td>A thorough and organised approach - E</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A thorough and organised approach - E'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status29", $ss_statuses, $form_arf->skills_scan_status29, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Research","Customer Service","Problem Solving","Digital Analytics","Implementation","Environment")))
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr>
        <td>Ability to work with a range of internal and external people - F</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work with a range of internal and external people - F'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status30", $ss_statuses, $form_arf->skills_scan_status30, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Communication","Customer Service","Implementation","Environment")))
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr>
        <td>Ability to communicate effectively in a variety of situations - G</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to communicate effectively in a variety of situations - G'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status31", $ss_statuses, $form_arf->skills_scan_status31, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Communication","Data","Customer Service","Problem Solving","Digital Analytics","Environment")))
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
    <tr>
        <td>Maintain productive, professional and secure working environment - H</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Maintain productive, professional and secure working environment - H'] . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status32", $ss_statuses, $form_arf->skills_scan_status32, true, false, $assessor_signed) . "</td>";
        if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Customer Service","Digital Tools","Implementation","Environment")))
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
    else
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>&nbsp;</td>";
    $h.="</tr>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th></tr>
    </thead><tbody>
    <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Written Communication</td>
        <td>"; if(isset($Assessment_Plan['Written Communication'])) $h.= $Assessment_Plan['Written Communication']; $h.= "</td>
        <td>Research</td>
        <td>"; if(isset($Assessment_Plan['Research'])) $h.= $Assessment_Plan['Research']; $h.= "</td>
        <td>Technologies</td>
        <td>"; if(isset($Assessment_Plan['Technologies'])) $h.= $Assessment_Plan['Technologies']; $h.= "</td>
    </tr>
    <tr>
        <td>Data</td>
        <td>"; if(isset($Assessment_Plan['Data'])) $h.= $Assessment_Plan['Data']; $h.= "</td>
        <td>Customer Service</td>
        <td>"; if(isset($Assessment_Plan['Customer Service'])) $h.= $Assessment_Plan['Customer Service']; $h.= "</td>
        <td>Problem Solving</td>
        <td>"; if(isset($Assessment_Plan['Problem Solving'])) $h.= $Assessment_Plan['Problem Solving']; $h.= "</td>
    </tr>
    <tr>
        <td>Analysis</td>
        <td>"; if(isset($Assessment_Plan['Analysis'])) $h.= $Assessment_Plan['Analysis']; $h.= "</td>
        <td>Implementation</td>
        <td>"; if(isset($Assessment_Plan['Implementation'])) $h.= $Assessment_Plan['Implementation']; $h.= "</td>
        <td>Specialist Areas</td>
        <td>"; if(isset($Assessment_Plan['Specialist Areas'])) $h.= $Assessment_Plan['Specialist Areas']; $h.= "</td>
    </tr>
    <tr>
        <td>Digital Tools</td>
        <td>"; if(isset($Assessment_Plan['Digital Tools'])) $h.= $Assessment_Plan['Digital Tools']; $h.= "</td>
        <td>Digital Analytics</td>
        <td>"; if(isset($Assessment_Plan['Digital Analytics'])) $h.= $Assessment_Plan['Digital Analytics']; $h.= "</td>
        <td>Industry Developments & Practices</td>
        <td>"; if(isset($Assessment_Plan['Industry Developments and Practices'])) $h.= $Assessment_Plan['Industry Developments and Practices']; $h.= "</td>
    </tr>
    <tr>
        <td>Effective Business Operation</td>
        <td>"; if(isset($Assessment_Plan['Effective Business Operation'])) $h.= $Assessment_Plan['Effective Business Operation']; $h.= "</td>
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
        <td>Principles of coding</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Principles of coding") . "<br>" . ReviewSkillsScans::getEventDate($events,'Principles of coding') . "</td>
        <td>9828-11 principles of coding test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "9628-11 principles of coding test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-11 principles of coding test') . "</td>
        <td>Part 1: principles of online and offline marketing theory</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Part 1: principles of online and offline marketing theory") . "<br>" . ReviewSkillsScans::getEventDate($events,'Part 1: principles of online and offline marketing theory') . "</td>
    </tr>
    <tr>
        <td>Part 2: principles of online and offline marketing theory</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Part 2: principles of online and offline marketing theory") . "<br>" . ReviewSkillsScans::getEventDate($events,'Part 2: principles of online and offline marketing theory') . "</td>
        <td>9628-12 principles of online and offline marketing theory test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "9628-12 principles of online and offline marketing theory test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-12 principles of online and offline marketing theory test') . "</td>
        <td>Google analytics IQ</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Google analytics IQ") . "<br>" . ReviewSkillsScans::getEventDate($events,'Google analytics IQ') . "</td>
    </tr>
    <tr>
        <td>Google analytics IQ test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Google analytics IQ test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Google analytics IQ test') . "</td>
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