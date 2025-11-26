<?php
class SoftwareDevelopmentTechnician
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
        <td>Write simple code for discrete software components following appropriate logical approach to agreed standards</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Logic'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Logic'])?$Assessment_Plan['Logic']:"";
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td>Security</td>
        <td>Apply secure development principles to specific software components</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Security'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        $it_security = isset($Assessment_Plan['Security'])?$Assessment_Plan['Security']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
    </tr>
    <tr>
        <td>Development Support</td>
        <td>Apply industry standard approaches for configuration management and version control to manage code during build and release</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Development Support'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        $remote_infrastructure = isset($Assessment_Plan['Development Support'])?$Assessment_Plan['Development Support']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
    </tr>
    <tr>
        <td>Data</td>
        <td>Make connections between code and defined data sources</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Data'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        $data = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
    </tr>
    <tr>
        <td rowspan=1>Test</td>
        <td>Conduct functionality tests on deliverables for that component</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Test'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        $problem_solving = isset($Assessment_Plan['Test'])?$Assessment_Plan['Test']:"";
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>
    </tr>
    <tr>
        <td>Analysis</td>
        <td>Basic analysis models such as use cases and process maps</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Analysis'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        $workflow_management = isset($Assessment_Plan['Analysis'])?$Assessment_Plan['Analysis']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>
    </tr>
    <tr>
        <td>Development Lifecycle</td>
        <td>Supports the Software Developers at the build and test stages of the software development lifecycle</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Development Lifecycle'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        $health_safety = isset($Assessment_Plan['Development Lifecycle'])?$Assessment_Plan['Development Lifecycle']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
    </tr>
    <tr>
        <td rowspan=1>Quality</td>
        <td>Follows organisational and industry good coding practices (including those for naming, commenting etc.)</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Quality'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Quality'])?$Assessment_Plan['Quality']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td rowspan=1>Problem Solving</td>
        <td>Solve logical problems including appropriate mathematical application</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Problem Solving'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td rowspan=1>Business Operation</td>
        <td>Respond to business issues related to software development</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Business Operation'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Business Operation'])?$Assessment_Plan['Business Operation']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td rowspan=1>Communication</td>
        <td>Articulate the role and function of software components to a variety of stakeholders</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Communication'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Communication'])?$Assessment_Plan['Communication']:"";
        $h.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td rowspan=1>User Interface</td>
        <td>Develop user interfaces appropriate to the organisation’s development standards and the type of component being developed.</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['User Interface'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['User Interface'])?$Assessment_Plan['User Interface']:"";
        $h.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr></tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
    </thead><tbody>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
    <tr>
        <td>Business context and market environment for software development</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Business context and market environment for software development'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Structure of software applications</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Structure of software applications'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Stages of the software development lifecycle</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Stages of the software development lifecycle'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Configuration management and version control systems and how to apply them</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Configuration management and version control systems and how to apply them'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>How to test code (e.g. unit testing)</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How to test code (e.g. unit testing)'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Different methodologies that can be used for software development</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Different methodologies that can be used for software development'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Context for the development platform (whether web, mobile, or desktop applications)</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Context for the development platform (whether web, mobile, or desktop applications)'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The technician role within their software development team</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The technician role within their software development team'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>How to implement code following a logical approach</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How to implement code following a logical approach'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>How code integrates into the wider project</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How code integrates into the wider project'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status24!="")?$sss[$previous_review->skills_scan_status24]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>How to follow a set of functional and non-functional requirements</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How to follow a set of functional and non-functional requirements'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status25!="")?$sss[$previous_review->skills_scan_status25]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>End user context for the software development activity</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['End user context for the software development activity'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status26!="")?$sss[$previous_review->skills_scan_status26]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>How to connect their code to specified data sources</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How to connect their code to specified data sources'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status27!="")?$sss[$previous_review->skills_scan_status27]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Database normalisation</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Database normalisation'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status28!="")?$sss[$previous_review->skills_scan_status28]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status28", $ss_statuses, $form_arf->skills_scan_status28, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Why there is a need to follow good coding practices</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Why there is a need to follow good coding practices'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status29!="")?$sss[$previous_review->skills_scan_status29]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status29", $ss_statuses, $form_arf->skills_scan_status29, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Principles of good interface design</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of good interface design'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status30!="")?$sss[$previous_review->skills_scan_status30]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status30", $ss_statuses, $form_arf->skills_scan_status30, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The importance of building in security to software at the development stage</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The importance of building in security to software at the development stage'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status31!="")?$sss[$previous_review->skills_scan_status31]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status31", $ss_statuses, $form_arf->skills_scan_status31, true, false, $assessor_signed) . "</td>
    </tr></tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
    </thead>
    <tbody><tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Previous Review Skill Scan Status</td><td>Current Skills Scan Status</td></tr>
    <tr>
        <td>Logical and creative thinking skills - A</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills - A'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status32!="")?$sss[$previous_review->skills_scan_status32]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status32", $ss_statuses, $form_arf->skills_scan_status32, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status33!="")?$sss[$previous_review->skills_scan_status33]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status33", $ss_statuses, $form_arf->skills_scan_status33, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status34!="")?$sss[$previous_review->skills_scan_status34]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status34", $ss_statuses, $form_arf->skills_scan_status34, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status35!="")?$sss[$previous_review->skills_scan_status35]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status35", $ss_statuses, $form_arf->skills_scan_status35, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status36!="")?$sss[$previous_review->skills_scan_status36]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status36", $ss_statuses, $form_arf->skills_scan_status36, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status37!="")?$sss[$previous_review->skills_scan_status37]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status37", $ss_statuses, $form_arf->skills_scan_status37, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status38!="")?$sss[$previous_review->skills_scan_status38]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status38", $ss_statuses, $form_arf->skills_scan_status38, true, false, $assessor_signed) . "</td>
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
        <td style='text-align:center'>" . (($previous_review->skills_scan_status39!="")?$sss[$previous_review->skills_scan_status39]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
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
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th></tr>
    </thead><tbody>
    <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Analysis</td>
        <td>"; if(isset($Assessment_Plan['Analysis'])) $h.=$Assessment_Plan['Analysis']; $h.= "</td>
        <td>Business Operation</td>
        <td>"; if(isset($Assessment_Plan['Business Operation'])) $h.=$Assessment_Plan['Business Operation']; $h.="</td>
        <td>Communication</td>
        <td>"; if(isset($Assessment_Plan['Communication'])) $h.=$Assessment_Plan['Communication']; $h.= "</td>
    </tr>
    <tr>
        <td>Data</td>
        <td>"; if(isset($Assessment_Plan['Data'])) $h.=$Assessment_Plan['Data']; $h.="</td>
        <td>Development Lifecycle</td>
        <td>"; if(isset($Assessment_Plan['Development Lifecycle'])) $h.= $Assessment_Plan['Development Lifecycle']; $h.= "</td>
        <td>Development Support</td>
        <td>"; if(isset($Assessment_Plan['Development Support'])) $h.= $Assessment_Plan['Development Support']; $h.="</td>
    </tr>
    <tr>
        <td>Logic</td>
        <td>"; if(isset($Assessment_Plan['Logic'])) $h.= $Assessment_Plan['Logic']; $h.= "</td>
        <td>Problem Solving</td>
        <td>"; if(isset($Assessment_Plan['Problem Solving'])) $h.= $Assessment_Plan['Problem Solving']; $h.= "</td>
        <td>Quality</td>
        <td>"; if(isset($Assessment_Plan['Quality'])) $h.= $Assessment_Plan['Quality']; $h.="</td>
    </tr>
    <tr>
        <td>Security</td>
        <td>"; if(isset($Assessment_Plan['Security'])) $h.= $Assessment_Plan['Security']; $h.= "</td>
        <td>Test</td>
        <td>"; if(isset($Assessment_Plan['Test'])) $h.= $Assessment_Plan['Test']; $h.= "</td>
        <td>User Interface</td>
        <td>"; if(isset($Assessment_Plan['User Interface'])) $h.= $Assessment_Plan['User Interface']; $h.= "</td>
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
        <td>Part 1 software development environment roles and structure</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Part 1 software development environment roles and structure") . "<br>" . ReviewSkillsScans::getEventDate($events,'Part 1 software development environment roles and structure') . "</td>
        <td>Part 2 software development processes</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Part 2 software development processes") . "<br>" . ReviewSkillsScans::getEventDate($events,'Part 2 software development processes') . "</td>
        <td>Software development context and methodologies test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Software development context and methodologies test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Software development context and methodologies test') . "</td>
    </tr>
    <tr>
        <td>Programming part 1: software development requirements</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Programming part 1: software development requirements") . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming part 1: software development requirements') . "</td>
        <td>Programming part 2: Software data interfaces and security</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Programming part 2: Software data interfaces and security") . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming part 2: Software data interfaces and security') . "</td>
        <td>Programming part 3: developing code using good practices and logic</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Programming part 3: developing code using good practices and logic") . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming part 3: developing code using good practices and logic') . "</td>
    </tr>
    <tr>
        <td>Programming test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Programming test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming test') . "</td>
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