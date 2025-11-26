<?php
class UnifiedCommsTechnicianV3
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
        <td rowspan=1>Analysis</td>
        <td>Analyse system problems by selecting the appropriate tools and techniques in line with organisation guidance</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Analysis'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Network Technical Support"));
        $html."<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Rectification</td>
        <td>Select the most appropriate solution to the fault, using the relevant logistical support where appropriate, or escalates to a higher-level where necessary</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Rectification'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Network Technical Support"));
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Installing & Configuring</td>
        <td>Installs and configure appropriate component and or systems appropriate to the organisation</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Installing & Configuring'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Cabling and Installation"));
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Diagnostic Tools</td>
        <td>Selects the appropriate diagnostic tools to monitor, test and reacts to network performance</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Diagnostic Tools'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Network Technical Support"));
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Hardware & Software</td>
        <td>Undertakes hardware or software upgrades appropriate to the organisation</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Hardware & Software'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Cabling and Installation"));
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td rowspan=1>Interpreting Specifications</td>
        <td>Interpret technical specifications for activities and maintains accurate records</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Interpreting Specifications'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Network Technical Support and Cabling"));
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Technical Support</td>
        <td>Respond effectively with customers and provides technical support to them in line with organisations process</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Technical Support'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Network Technical Support"));
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Documenting Tasks</td>
        <td>Documents completed tasks in accordance with agreed organisational procedures</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Documenting Tasks'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Network Technical Support"));
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Cabling</td>
        <td>Competently cable or connect equipment in line with technical requirements</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Cabling'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
        $communication = ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Cabling"));
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    </tbody>
</table>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
    </thead><tbody>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td>Networks: data, protocols and how they relate to each other; the main routing protocols; the main factors affecting network performance including typical faults, and approaches to error control</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Networks: data, protocols and how they relate to each other; the main routing protocols; the main factors affecting network performance including typical faults, and approaches to error control'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Cloud services / solutions, routers and switches</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Cloud services / solutions, routers and switches'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>IT test or diagnostic equipment</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['IT test or diagnostic equipment'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Cabling and connectivity</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Cabling and connectivity'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status14!="")?$sss[$previous_review->skills_scan_status14]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>
    </tr>
   <tr>
        <td>Security principles including software, access, encryption and regulation</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Security principles including software, access, encryption and regulation'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Firewalls</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Firewalls'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>VPN and Remote Access Security</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['VPN and Remote Access Security'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Data, including network architectures</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Data, including network architectures'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Back-up and storage solutions</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Back-up and storage solutions'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Service level agreements</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Service level agreements'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Digital communication technologies</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Digital communication technologies'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status24!="")?$sss[$previous_review->skills_scan_status24]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status25!="")?$sss[$previous_review->skills_scan_status25]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status25!="")?$sss[$previous_review->skills_scan_status25]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status26!="")?$sss[$previous_review->skills_scan_status26]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status27!="")?$sss[$previous_review->skills_scan_status27]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status27!="")?$sss[$previous_review->skills_scan_status27]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false, $assessor_signed) . "</td>
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
        <td>Installation & Cabling Activity 1 - example 1</td>
        <td>"; if(isset($Assessment_Plan['Installation & Cabling Activity 1 - example 1'])) $html .= $Assessment_Plan['Installation & Cabling Activity 1 - example 1']; $html .= "</td>
        <td>Installation & Cabling Activity 1 - example 2</td>
        <td>"; if(isset($Assessment_Plan['Installation & Cabling Activity 1 - example 2'])) $html .= $Assessment_Plan['Installation & Cabling Activity 1 - example 2']; $html .= "</td>
        <td>Installation & Cabling Activity 1 - example 3</td>
        <td>"; if(isset($Assessment_Plan['Installation & Cabling Activity 1 - example 3'])) $html .= $Assessment_Plan['Installation & Cabling Activity 1 - example 3']; $html .= "</td>
    </tr>
    <tr>
        <td>Installation & Cabling Activity 2 - Hardware</td>
        <td>"; if(isset($Assessment_Plan['Installation & Cabling Activity 2 - Hardware'])) $html .= $Assessment_Plan['Installation & Cabling Activity 2 - Hardware']; $html .= "</td>
        <td>Installation & Cabling Activity 2 - Software</td>
        <td>"; if(isset($Assessment_Plan['Installation & Cabling Activity 2 - Software'])) $html .= $Assessment_Plan['Installation & Cabling Activity 2 - Software']; $html .= "</td>
        <td>Installation & Cabling Activity 2 - Systems</td>
        <td>"; if(isset($Assessment_Plan['Installation & Cabling Activity 2 - Systems'])) $html .= $Assessment_Plan['Installation & Cabling Activity 2 - Systems']; $html .= "</td>
    </tr>
    <tr>
        <td>Installation & Cabling Activity 3</td>
        <td>"; if(isset($Assessment_Plan['Installation & Cabling Activity 3'])) $html .= $Assessment_Plan['Installation & Cabling Activity 3']; $html .= "</td>
        <td>Network Technical Support Activity 1</td>
        <td>"; if(isset($Assessment_Plan['Network Technical Support Activity 1'])) $html.= $Assessment_Plan['Network Technical Support Activity 1']; $html.="</td>
        <td>Network Technical Support Activity 2</td>
        <td>"; if(isset($Assessment_Plan['Network Technical Support Activity 2'])) $html.= $Assessment_Plan['Network Technical Support Activity 2']; $html.="</td>
    </tr>
    <tr>
        <td>Network Technical Support Activity 3</td>
        <td>"; if(isset($Assessment_Plan['Network Technical Support Activity 3'])) $html.= $Assessment_Plan['Network Technical Support Activity 3']; $html.="</td>
        <td>Network Technical Support Activity 4</td>
        <td>"; if(isset($Assessment_Plan['Network Technical Support Activity 4'])) $html.= $Assessment_Plan['Network Technical Support Activity 4']; $html.="</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan=6>Record here the detail of the progress. What has the learner been doing towards completing this and discuss employer reference progress</td>
    </tr>
    <tr>
        <td colspan=6><i>
            ". $form_arf->workplace_competence . "
        </i></td>
    </tr></tbody>
</table>
<br>";

        return $html;
    }

    public static function getKnowledgeModule($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {


$h = "<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th></tr>
    </thead><tbody>
    <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
    <tr>
        <td>Mobility and devices MTA</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Mobility and devices MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Mobility and devices MTA') . "</td>
        <td>Cloud fundamentals MTA</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Cloud fundamentals MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Cloud fundamentals MTA') . "</td>
        <td>Business Processes</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Business Processes") . "<br>" . ReviewSkillsScans::getEventDate($events,'Business Processes') . "</td>
    </tr>
    <tr>
        <td>Coding and logic</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Coding and logic") . "<br>" . ReviewSkillsScans::getEventDate($events,'Coding and logic') . "</td>
        <td>Networking and architecture</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Networking and architecture") . "<br>" . ReviewSkillsScans::getEventDate($events,'Networking and architecture') . "</td>
        <td>9628-06 Networking and architecture test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "9628-06 Networking and architecture test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-06 Networking and architecture test') . "</td>
    </tr>
    <tr>
        <td>Functional Skills English</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills English") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills English') . "</td>
        <td>Functional Skills Mathematics</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills Mathematics") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills Mathematics') . "</td>
        <td>Windows Server Fundamentals MTA</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Windows Server Fundamentals MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Windows Server Fundamentals MTA') . "</td>
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