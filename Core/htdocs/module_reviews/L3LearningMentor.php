<?php
class L3LearningMentor
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        $html = "
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
            </thead>
            <tbody><tr><td>Knowledge</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
            <tr>
                <td>Understand effective practice in providing accurate and relevant vocational/ pastoral Careers, Education, Information Advice and Guidance</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Understand effective practice in providing accurate and relevant vocational/ pastoral Careers, Education, Information Advice and Guidance'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Identify a range of effective questioning, active-listening, and assertiveness tecniques that can be used to get the best out of learners</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Identify a range of effective questioning, active-listening, and assertiveness tecniques that can be used to get the best out of learners'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Describe learning programme requirements and the need to plan contextualised learning in authentic or realistic work settings involving relevant stakeholders</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Describe learning programme requirements and the need to plan contextualised learning in authentic or realistic work settings involving relevant stakeholders'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Understand the roles of different practitioners, including assessors, coaches or teachers in providing practical help with assessment processes and requirements</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Understand the roles of different practitioners, including assessors, coaches or teachers in providing practical help with assessment processes and requirements'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Explain who has a legitimate need to be kept informed of issues impacting on the learners well-being and progress during an apprenticeship programme</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Explain who has a legitimate need to be kept informed of issues impacting on the learners well-being and progress during an apprenticeship programme'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Describe the learning mentors role in supporting the learners development and how to provide valid evidence of progress and achievement, and overcome any barriers</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Describe the learning mentors role in supporting the learners development and how to provide valid evidence of progress and achievement, and overcome any barriers'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Understand the impact of organisational and legal requirements for recording, storing and sharing information on learners progress, needs and welfare</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Understand the impact of organisational and legal requirements for recording, storing and sharing information on learners progress, needs and welfare'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Explain the role of key stakeholders, such as workpplace and education provider colleagues, who contribute to learners fulfilling their agreed action plans</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Explain the role of key stakeholders, such as workpplace and education provider colleagues, who contribute to learners fulfilling their agreed action plans'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Describe how learners may become physically or pshychologically at risk, and channels for reporting concerns, including organisational procedures for reporting</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Describe how learners may become physically or pshychologically at risk, and channels for reporting concerns, including organisational procedures for reporting'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Understand the opportunities available for continuing professional development, and industry requirements for maintaining CPD</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Understand the opportunities available for continuing professional development, and industry requirements for maintaining CPD'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Evaluate the quality assurance requirements relating to the mentoring environment, including the internal procedures and external influences on quality</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Evaluate the quality assurance requirements relating to the mentoring environment, including the internal procedures and external influences on quality'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>
            </tr>
        </table>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th></tr>
            </thead>
            <tbody><tr><td>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Skills Scan Status</td><td>Assessment Plan Status</td></tr>
            <tr>
                <td>Provide competent Career Education, Information Advice and Guidance, and supervise learners to acquire the most benefit from their learning programme</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Provide competent Career Education, Information Advice and Guidance, and supervise learners to acquire the most benefit from their learning programme'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Communicate and collaborate effectively and using a range of effective questioning, listening and assertiveness skills </td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Communicate and collaborate effectively and using a range of effective questioning, listening and assertiveness skills '] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Work with education providers, stakeholders and workplace colleagues to plan and implement structured and meaningful learning and work experience</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Work with education providers, stakeholders and workplace colleagues to plan and implement structured and meaningful learning and work experience'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Liaise with different education practitioners, including assessors, coaches and/or teachers to facilitate formative and summative assessment of learners skills and knowledge</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Liaise with different education practitioners, including assessors, coaches and/or teachers to facilitate formative and summative assessment of learners skills and knowledge'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Identify and refer issues relevant to learners progress and wellbeing, to education-providers and/ or workplace colleagues, and support those with barriers to learning</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Identify and refer issues relevant to learners progress and wellbeing, to education-providers and/ or workplace colleagues, and support those with barriers to learning'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Collaborate with wider education support team to review learners progress and to provide evidence of progress and achievement</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Collaborate with wider education support team to review learners progress and to provide evidence of progress and achievement'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Maintain appropriate records for the learning programme, complying with quality, confidentiality, Data Protection, and other external requirements (OFSTED, ESFA)</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Maintain appropriate records for the learning programme, complying with quality, confidentiality, Data Protection, and other external requirements (OFSTED, ESFA)'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Liaise with relevant colleagues to support the implementation of learners action plans, develop rigid support plans based on learning needs and berriers to learning</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Liaise with relevant colleagues to support the implementation of learners action plans, develop rigid support plans based on learning needs and berriers to learning'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Be vigilant in safeguarding learners and others in contact with them, and follow internal and external procedures when reporting safeguarding issues</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Be vigilant in safeguarding learners and others in contact with them, and follow internal and external procedures when reporting safeguarding issues'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status24!="")?$sss[$previous_review->skills_scan_status24]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Maintain an up to date, comprehensive CPD log to ensure the currency of their vocational skills and knowledge</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Maintain an up to date, comprehensive CPD log to ensure the currency of their vocational skills and knowledge'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status25!="")?$sss[$previous_review->skills_scan_status25]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Fully comply with internal and external quality assurance requirements</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Fully comply with internal and external quality assurance requirements'] . "</td>
                <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status26!="")?$sss[$previous_review->skills_scan_status26]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false, $assessor_signed) . "</td>
            </tr>
        </table>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
            </thead>
            <tbody>
            <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Previous Review Skill Scan Status</td><td>Current Skills Scan Status</td></tr>
            <tr>
                <td>Promote an ethos of motivation aspiration and a passion for learning</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Promote an ethos of motivation aspiration and a passion for learning'] . "</td>
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
                <td>Operate at all times to ethical and legal standards and within professional boundaries</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Operate at all times to ethical and legal standards and within professional boundaries'] . "</td>
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
                <td>Value equality and diversity and work with others to improve equality of opportunity and inclusion, and promote this comfortably within their role</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Value equality and diversity and work with others to improve equality of opportunity and inclusion, and promote this comfortably within their role'] . "</td>
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
                <td>Be resilient and adaptable when dealing with challenge and change, maintaining focus and self-control</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Be resilient and adaptable when dealing with challenge and change, maintaining focus and self-control'] . "</td>
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
                <td>Demonstrate, encourage and expect mutual respect in all professional contexts</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Demonstrate, encourage and expect mutual respect in all professional contexts'] . "</td>
                <td style='text-align:center'>" . (($previous_review->skills_scan_status35!="")?$sss[$previous_review->skills_scan_status35]:"") . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status35", $ss_statuses, $form_arf->skills_scan_status35, true, false, $assessor_signed) . "</td>
            </tr>
            <tr>
                <td>Example</td>
                <td colspan=3><i>
                    " . $form_arf->example5 . "
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