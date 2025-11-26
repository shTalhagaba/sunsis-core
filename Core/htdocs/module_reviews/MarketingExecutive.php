<?php
class MarketingExecutive
{

    public static function getSkillsScan($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
        $sss = Array("F"=>"Fully Competent", "S"=>"Some Knowledge","N"=>"No Knowledge");

        $html="<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th></tr>
    </thead>
    <tbody>
    <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Skills Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td colspan=2>Coordinate and maintain key marketing channels (both digital and offline)</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Coordinate and maintain key marketing channels (both digital and offline)'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$sss[$previous_review->skills_scan_status1]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Written Communication'])?$Assessment_Plan['Written Communication']:"";
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
    </tr>
    <tr>
        <td colspan=2>Plan and deliver tactical campaigns against SMART (Specific, Measurable, Achievable, Realistic, Time-bound) objectives</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Plan and deliver tactical campaigns against SMART (Specific, Measurable, Achievable, Realistic, Time-bound) objectives'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
        $it_security = isset($Assessment_Plan['Research'])?$Assessment_Plan['Research']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
    </tr>
    <tr>
        <td colspan=2>Manage the production and distribution of marketing materials, e.g. digital, print and video content as appropriate</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Manage the production and distribution of marketing materials, e.g. digital, print and video content as appropriate'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
        $remote_infrastructure = isset($Assessment_Plan['Technologies'])?$Assessment_Plan['Technologies']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
    </tr>
    <tr>
        <td colspan=2>Produce a wide range of creative and effective communications, including ability to write and proofread clear and innovative copy, project briefs, and give confident presentations</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Produce a wide range of creative and effective communications, including ability to write and proofread clear and innovative copy, project briefs, and give confident presentations'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
        $data = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
    </tr>
    <tr>
        <td colspan=2>Able to engage and collaborate with a wide range of clients/stakeholders, across departments internally and with clients/suppliers externally to support marketing outcomes as required</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Able to engage and collaborate with a wide range of clients/stakeholders, across departments internally and with clients/suppliers externally to support marketing outcomes as required'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
        $problem_solving = isset($Assessment_Plan['Customer Service'])?$Assessment_Plan['Customer Service']:"";
        $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>
    </tr>
    <tr>
        <td colspan=2>Use good project and time management to deliver projects/tasks/events as appropriate, effectively. Including the ability to divide time effectively between reporting, planning and delivery</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Use good project and time management to deliver projects/tasks/events as appropriate, effectively. Including the ability to divide time effectively between reporting, planning and delivery'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
        $workflow_management = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>
    </tr>
    <tr>
        <td colspan=2>Coordinate several marketing campaigns/projects/events to agreed deadlines</td>
        <td style='text-align:center; vertical-align:middle'>" . $ss_result['Coordinate several marketing campaigns/projects/events to agreed deadlines'] . "</td>
        <td style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
        <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
        $health_safety = isset($Assessment_Plan['Analysis'])?$Assessment_Plan['Analysis']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
    </tr>
    <tr>
        <td colspan=2>Effectively liaise with, and manage, internal and external stakeholders including suppliers to deliver required outcomes</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Effectively liaise with, and manage, internal and external stakeholders including suppliers to deliver required outcomes'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Specialist Areas'])?$Assessment_Plan['Specialist Areas']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td colspan=2>Monitor project budgets within their scope of work using appropriate systems and controls</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Monitor project budgets within their scope of work using appropriate systems and controls'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Digital Tools'])?$Assessment_Plan['Digital Tools']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td colspan=2>Evaluate the effectiveness of marketing campaigns by choosing the appropriate digital and offline data sources</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Evaluate the effectiveness of marketing campaigns by choosing the appropriate digital and offline data sources'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Digital Analytics'])?$Assessment_Plan['Digital Analytics']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td colspan=2>Evaluate data and research findings to derive insights to support improvements to future campaigns</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Evaluate data and research findings to derive insights to support improvements to future campaigns'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Implementation'])?$Assessment_Plan['Implementation']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td colspan=2>Effectively use appropriate business systems and software to deliver marketing outcomes efficiently, for example to analyse data, produce reports and deliver copy</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Effectively use appropriate business systems and software to deliver marketing outcomes efficiently, for example to analyse data, produce reports and deliver copy'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Effective Business Operation'])?$Assessment_Plan['Effective Business Operation']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr>
    <tr>
        <td colspan=2>Use appropriate technologies to deliver marketing outcomes e.g. digital/web analytics, social media, CRM</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Use appropriate technologies to deliver marketing outcomes e.g. digital/web analytics, social media, CRM'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>";
        $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
        $html.="<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
    </tr></tbody>
</table>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
    </thead><tbody>
    <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
    <tr>
        <td>The fundamentals of marketing theory that support the marketing process e.g. the extended marketing mix (7P'S: Product, Price, Place, Promotion, Physical environment, Process, People), product development, and segmentation</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The fundamentals of marketing theory that support the marketing process e.g. the extended marketing mix (7Ps: Product, Price, Place, Promotion, Physical environment, Process, People), product development, and segmentation'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status14!="")?$sss[$previous_review->skills_scan_status14]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The concepts of brand positioning and management and implementing process to support corporate reputation</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The concepts of brand positioning and management and implementing process to support corporate reputation'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The principles of stakeholder management and customer relationship management (CRM), both internal and external, to facilitate effective cross-functional relationships internally, and channel and customer relationships externally</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The principles of stakeholder management and customer relationship management (CRM), both internal and external, to facilitate effective cross-functional relationships internally, and channel and customer relationships externally'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The characteristics and plans of the business and sector they work within, including their vision and values</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The characteristics and plans of the business and sector they work within, including their vision and values'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>How marketing contributes to achieving wider business objectives</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How marketing contributes to achieving wider business objectives'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The target audience's decision making process and how that can influence marketing activities</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The target audiences decision making process and how that can influence marketing activities'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The sector specific legal, regulatory and compliance frameworks within which they must work, including current Data Protection regulations</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The sector specific legal, regulatory and compliance frameworks within which they must work, including current Data Protection regulations'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The principles of effective market research and how this can influence marketing activity e.g. valid data collection sources and methodologies and usage, including digital sources, and when to use quantitative and qualitative methods.</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The principles of effective market research and how this can influence marketing activity e.g. valid data collection sources and methodologies and usage, including digital sources, and when to use quantitative and qualitative methods.'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>Basic principles of product development and product/service portfolios</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Basic principles of product development and product/service portfolios'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The marketing landscape and how routes to market interplay most efficiently, e.g. franchise model, distribution</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The marketing landscape and how routes to market interplay most efficiently, e.g. franchise model, distribution'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
    </tr>
    <tr>
        <td>The features and benefits of different marketing communications channels and media, both digital and offline, and when and how to apply these</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The features and benefits of different marketing communications channels and media, both digital and offline, and when and how to apply these'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status24!="")?$sss[$previous_review->skills_scan_status24]:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
    </tr></tbody>
</table>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
    </thead><tbody>
    <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Previous Review Skill Scan Status</td><td>Current Skills Scan Status</td></tr>
    <tr>
        <td>A tenacious and driven approach to see projects through to completion</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A tenacious and driven approach to see projects through to completion'] . "</td>
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
        <td>Being a proven self-starter and have an adaptable approach to meet changing work priorities</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Being a proven self-starter and have an adaptable approach to meet changing work priorities'] . "</td>
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
        <td>A creative and analytical mind, with a willingness to think of new ways of doing things</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A creative and analytical mind, with a willingness to think of new ways of doing things'] . "</td>
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
        <td>They come up with ideas and solutions to support the delivery of their work</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['They come up with ideas and solutions to support the delivery of their work'] . "</td>
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
        <td>A willingness to learn from mistakes, as not all activities go to plan, and improve their own performance as a result</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A willingness to learn from mistakes, as not all activities go to plan, and improve their own performance as a result'] . "</td>
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
        <td>A high level of professionalism, reliability and dependability with a passion for the customer</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A high level of professionalism, reliability and dependability with a passion for the customer'] . "</td>
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
        <td>Ethical behaviour in the way they approach marketing activities and their work, valuing equality & diversity</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ethical behaviour in the way they approach marketing activities and their work, valuing equality & diversity'] . "</td>
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
    <tbody><tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
    <tr>
        <td>Target Audience Workplace Project</td>
        <td>"; if(isset($Assessment_Plan['Target Audience Workplace Project'])) $html .= $Assessment_Plan['Target Audience Workplace Project']; $html .="</td>
        <td>Social Media Content Workplace Project</td>
        <td>"; if(isset($Assessment_Plan['Social Media Content Workplace Project'])) $html .= $Assessment_Plan['Social Media Content Workplace Project']; $html .="</td>
        <td>Marketing Environment Project</td>
        <td>"; if(isset($Assessment_Plan['Marketing Environment Project'])) $html .= $Assessment_Plan['Marketing Environment Project']; $html .= "</td>
    </tr>
    <tr>
        <td>Marketing Campaign Workplace Project</td>
        <td>"; if(isset($Assessment_Plan['Marketing Campaign Workplace Project'])) $html .= $Assessment_Plan['Marketing Campaign Workplace Project']; $html .= "</td>
        <td>Creative Copy Workplace Project</td>
        <td>"; if(isset($Assessment_Plan['Creative Copy Workplace Project'])) $html .= $Assessment_Plan['Creative Copy Workplace Project']; $html .= "</td>
        <td>Campaign Choice Workplace Project</td>
        <td>"; if(isset($Assessment_Plan['Campaign Choice Workplace Project'])) $html .= $Assessment_Plan['Campaign Choice Workplace Project']; $html .= "</td>
    </tr>
    <tr>
        <td>Personal Development Evaluation Workplace Project</td>
        <td>"; if(isset($Assessment_Plan['Personal Development Evaluation Workplace Project'])) $html .= $Assessment_Plan['Personal Development Evaluation Workplace Project']; $html .= "</td>
        <td>Over & above</td>
        <td>"; if(isset($Assessment_Plan['Over & above'])) $html .= $Assessment_Plan['Over & above']; $html .= "</td>
        <td>Role overview</td>
        <td>"; if(isset($Assessment_Plan['Role overview'])) $html .= $Assessment_Plan['Role overview']; $html .= "</td>
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
        <td>Market Research</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Market Research") . "<br>" . ReviewSkillsScans::getEventDate($events,'Market Research') . "</td>
        <td>Products and Channels</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Products and Channels") . "<br>" . ReviewSkillsScans::getEventDate($events,'Products and Channels') . "</td>
        <td>EPA Prep</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "EPA Prep") . "<br>" . ReviewSkillsScans::getEventDate($events,'EPA Prep') . "</td>
    </tr>
    <tr>
        <td>Marketing Concepts and Theories</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Marketing Concepts and Theories") . "<br>" . ReviewSkillsScans::getEventDate($events,'Marketing Concepts and Theories') . "</td>
        <td>Business Understanding and Commercial Awareness</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Business Understanding and Commercial Awareness") . "<br>" . ReviewSkillsScans::getEventDate($events,'Business Understanding and Commercial Awareness') . "</td>
        <td>Functional Skills English</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills English") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills English') . "</td>
    </tr>
    <tr>
        <td>Functional Skills English Reading Test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills English Reading Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills English Reading Test') . "</td>
        <td>Functional Skills Writing Test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills Writing Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills Writing Test') . "</td>
        <td>Functional Skills Mathematics</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills Mathematics") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills Mathematics') . "</td>
    </tr>
    <tr>
        <td>Functional Skills Mathematics Test</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "Functional Skills Mathematics Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Functional Skills Mathematics Test') . "</td>
        <td>SLC</td>
        <td>" . ReviewSkillsScans::getEventStatus($events, "SLC") . "<br>" . ReviewSkillsScans::getEventDate($events,'SLC') . "</td>
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