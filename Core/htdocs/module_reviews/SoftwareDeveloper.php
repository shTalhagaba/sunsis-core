<?php
class SoftwareDeveloper
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
        <td rowspan=1>Logic</td>
        <td>Write good quality code (logic) with sound syntax in at least one language</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Logic'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Logic'])?$Assessment_Plan['Logic']:"";
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>User Interface</td>
        <td>Develop effective user interfaces for at least one channel</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['User Interface'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        $it_security = isset($Assessment_Plan['User Interface'])?$Assessment_Plan['User Interface']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
    </tr>
    <tr>
        <td>Data</td>
        <td>Link code to the database/data sets</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Data'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        $remote_infrastructure = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
    </tr>
    <tr>
        <td>Testing</td>
        <td>Test code and analyse results to correct errors found using either V-model manual testing and/or using unit testing</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Testing'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        $data = isset($Assessment_Plan['Testing'])?$Assessment_Plan['Testing']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
    </tr>
    <tr>
        <td rowspan=1>Problem Solving</td>
        <td>Apply structured techniques to problem solving, can debug code and can understand the structure of programmes in order to identify and resolve issues</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Problem Solving'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        $problem_solving = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"";
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>
    </tr>
    <tr>
        <td>Design</td>
        <td>Create simple data models and software designs to effectively communicate understanding of the program, following best practices and standards</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Design'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        $workflow_management = isset($Assessment_Plan['Design'])?$Assessment_Plan['Design']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>
    </tr>
    <tr>
        <td>Analysis</td>
        <td>Create basic analysis artefacts, such as user cases and/or user stories</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Analysis'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        $health_safety = isset($Assessment_Plan['Analysis'])?$Assessment_Plan['Analysis']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
    </tr>
    <tr>
        <td rowspan=1>Deployment</td>
        <td>Utilise skills to build, manage and deploy code into enterprise environments</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Deployment'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Deployment'])?$Assessment_Plan['Deployment']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td rowspan=1>Development Lifecycle</td>
        <td>Operate at all stages of the software development lifecycle, with increasing breadth and depth over time with initial focus on build and test</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Development Lifecycle'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Development Lifecycle'])?$Assessment_Plan['Development Lifecycle']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td rowspan=1>Business Operation</td>
        <td>Respond to the business environment and business issues related to software development</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Business Environment'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Business Operation'])?$Assessment_Plan['Business Operation']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
    </thead><tbody>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
    <tr>
        <td>Operate at all stages of the software development lifecycle</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Operate at all stages of the software development lifecycle'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Similarities and differences (taking into account positives and negatives of both approaches) between agile and waterfall software development methodologies</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Similarities and differences (taking into account positives and negatives of both approaches) between agile and waterfall software development methodologies'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>How teams work effectively to produce software and contributes appropriately</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How teams work effectively to produce software and contributes appropriately'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Apply software design approaches and patterns and can interpret and implement a given design, compliant with security and maintainability requirements</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Apply software design approaches and patterns and can interpret and implement a given design, compliant with security and maintainability requirements'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Respond to the business environment and business issues related to software development</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Respond to the business environment and business issues related to software development'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Applies the maths required to be a software developer (e.g. algorithms, logic and data structures)</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Applies the maths required to be a software developer (e.g. algorithms, logic and data structures)'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
    </tr></tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
    </thead><tbody>
    <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Previous Review Skill Scan Status</td><td>Current Skills Scan Status</td></tr>
    <tr>
        <td>Logical and creative thinking skills - A</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills - A'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example1 . "
        </i></td>
    </tr>
    <tr>
        <td>Analytical and problem solving skills - B</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Analytical and problem solving skills - B'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example2 . "
        </i></td>
    </tr>
    <tr>
        <td>Ability to work independently and to take responsibility - C</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work independently and to take responsibility - C'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example3 . "
        </i></td>
    </tr>
    <tr>
        <td>Can use own initiative - D</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Can use own initiative - D'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status24!="")?$sss[$previous_review->skills_scan_status24]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Example</td>
        <td colspan=3><i>
            " . $form_arf->example4 . "
        </i></td>
    </tr>
    <tr>
        <td>A thorough and organised approach - E</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A thorough and organised approach - E'] . "</td>
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
        <td>Ability to work with a range of internal and external people - F</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work with a range of internal and external people - F'] . "</td>
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
        <td>Ability to communicate effectively in a variety of situations - G</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to communicate effectively in a variety of situations - G'] . "</td>
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
        <td>Maintain productive, professional and secure working environment - H</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Maintain productive, professional and secure working environment - H'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status28!="")?$sss[$previous_review->skills_scan_status28]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status28", $ss_statuses, $form_arf->skills_scan_status28, true, false, $assessor_signed) . "</td>
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
    </thead><tbody>
    <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Analysis</td>
        <td>"; if(isset($Assessment_Plan['Analysis'])) $h.= $Assessment_Plan['Analysis']; $h.= "</td>
        <td>Business Operation</td>
        <td>"; if(isset($Assessment_Plan['Business Operation'])) $h.= $Assessment_Plan['Business Operation']; $h.= "</td>
        <td>Data</td>
        <td>"; if(isset($Assessment_Plan['Data'])) $h.= $Assessment_Plan['Data']; $h.= "</td>
    </tr>
    <tr>
        <td>Problem Solving</td>
        <td>"; if(isset($Assessment_Plan['Problem Solving'])) $h.= $Assessment_Plan['Problem Solving']; $h.= "</td>
        <td>Development Lifecycle</td>
        <td>"; if(isset($Assessment_Plan['Development Lifecycle'])) $h.= $Assessment_Plan['Development Lifecycle']; $h.= "</td>
        <td>Logic</td>
        <td>"; if(isset($Assessment_Plan['Logic'])) $h.= $Assessment_Plan['Logic']; $h.= "</td>
    </tr>
    <tr>
        <td>User Interface</td>
        <td>"; if(isset($Assessment_Plan['User Interface'])) $h.= $Assessment_Plan['User Interface']; $h.= "</td>
        <td>Service Level Agreements</td>
        <td>"; if(isset($Assessment_Plan['Service Level Agreements'])) $h.= $Assessment_Plan['Service Level Agreements']; $h.= "</td>
        <td>Testing</td>
        <td>"; if(isset($Assessment_Plan['Testing'])) $h.= $Assessment_Plan['Testing']; $h.= "</td>
    </tr>
    <tr>
        <td>Operational Requirements</td>
        <td>"; if(isset($Assessment_Plan['Operational Requirements'])) $h.= $Assessment_Plan['Operational Requirements']; $h.= "</td>
        <td>Deployment</td>
        <td>"; if(isset($Assessment_Plan['Deployment'])) $h.= $Assessment_Plan['Deployment']; $h.= "</td>
        <td>Design</td>
        <td>"; if(isset($Assessment_Plan['Design'])) $h.= $Assessment_Plan['Design']; $h.= "</td>
    </tr>
    <tr>
        <td colspan=6>Record here the detail of the progress. What has the learner been doing towards completing this and discuss employer reference progress</td>
    </tr>
    <tr>
        <td colspan=6><i>
            " . $form_arf->workplace_competence . "
        </i></td>
    </tr></tbody>
</table>";
        

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
        <td>Systems development</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Systems development") . "<br>" . ReviewSkillsScans::getEventDate($events,'Systems development') . "</td>
        <td>HTML5 MTA</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "HTML5 MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'HTML5 MTA') . "</td>
        <td>HTML5 MTA test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "HTML5 MTA test") . "<br>" . ReviewSkillsScans::getEventDate($events,'HTML5 MTA test') . "</td>
    </tr>
    <tr>
        <td>Project management and effective communication in a business</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Project management and effective communication in a business") . "<br>" . ReviewSkillsScans::getEventDate($events,'Project management and effective communication in a business') . "</td>
        <td>Object oriented programming</td>
        <td>". ReviewSkillsScans::getEventStatus($events, "Object oriented programming") . "<br>" . ReviewSkillsScans::getEventDate($events,'Object oriented programming') . "</td>
        <td>Database design</td>
        <td>". ReviewSkillsScans::getEventStatus($events, "Database design") . "<br>" . ReviewSkillsScans::getEventDate($events,'Database design') . "</td>
    </tr>
    <tr>
        <td>Human computer interaction</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Human computer interaction") . "<br>" . ReviewSkillsScans::getEventDate($events,'Human computer interaction') . "</td>
        <td>Design and create advanced websites</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Design and create advanced websites") . "<br>" . ReviewSkillsScans::getEventDate($events,'Design and create advanced websites') . "</td>
        <td>9628-01 software development methodologies test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "9628-01 software development methodologies test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-01 software development methodologies test') . "</td>
    </tr>
    <tr>
        <td>Functional Skills English</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills English") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills English') . "</td>
        <td>Functional Skills Mathematics</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills Mathematics") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills Mathematics') . "</td>
        <td>Functional Skills Mathematics Test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills Mathematics Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills Mathematics Test') . "</td>
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