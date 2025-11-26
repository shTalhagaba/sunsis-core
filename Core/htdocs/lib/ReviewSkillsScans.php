<?php
class ReviewSkillsScans
{
    public static function getSkillsScanITInfrastructureTechnicianPrior($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events)
    {
        $html ="<table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th>
        </tr>
        </thead>
        <tbody>
        <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
        <tr>
            <td>Communication</td>
            <td>";
        $communication = isset($Assessment_Plan['Communication'])? $Assessment_Plan['Communication']:"";
        $html .= $communication . "</td>
            <td>Health & Safety</td>
            <td>";
        $health = isset($Assessment_Plan['Health & Safety'])? $Assessment_Plan['Health & Safety']:"";
        $html .= $health . "</td>
            <td>Remote Infrastructure</td>
            <td>";
        $remote = isset($Assessment_Plan['Remote Infrastructure'])? $Assessment_Plan['Remote Infrastructure']:"";
        $html .= $remote . "</td>
        </tr>
        <tr>
            <td>Data</td>
            <td>";
        $data = isset($Assessment_Plan['Data'])? $Assessment_Plan['Data']:"";
        $html .= $data . "</td>
            <td>Workflow Management</td>
            <td>";
        $workflow = isset($Assessment_Plan['Workflow Management'])? $Assessment_Plan['Workflow Management']:"";
        $html .= $workflow . "</td>
            <td>IT Security</td>
            <td>";
        $it = isset($Assessment_Plan['IT Security'])?$Assessment_Plan['IT Security']:"";
        $html .= $it . "</td>
        </tr>
        <tr>
            <td>Problem Solving</td>
            <td>";
        $problem = isset($Assessment_Plan['Problem Solving'])? $Assessment_Plan['Problem Solving']:"";
        $html .= $problem . "</td>
            <td>Performance</td>
            <td>";
        $performance = isset($Assessment_Plan['Performance'])? $Assessment_Plan['Performance']:"";
        $html .= $performance . "</td>
            <td>WEEE</td>
            <td>";
        $weee = isset($Assessment_Plan['WEEE'])? $Assessment_Plan['WEEE']:"";
        $html .= $weee . "</td>
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

    <table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
        </tr>
        </thead>
        <tbody>
        <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
        <tr>
            <td>Mobility and devices MTA</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Mobility and Devices MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Mobility and Devices MTA') . "</td>
            <td>Mobility and devices MTA test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Mobility and Devices MTA Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Mobility and Devices MTA Test') . "</td>
            <td>Cloud fundamentals MTA</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Cloud Fundamentals MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Cloud Fundamentals MTA') . "</td>
        </tr>
        <tr>
            <td>Cloud fundamentals MTA test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Cloud Fundamentals MTA Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Cloud Fundamentals MTA Test') . "</td>
            <td>ITIL business processes</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "ITIL Business Processes") . "<br>" . ReviewSkillsScans::getEventDate($events,'ITIL Business Processes') . "</td>
            <td>9628-10 ITIL foundation test business processes test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "9628-10 ITIL Foundation Test Business Processes Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-10 ITIL Foundation Test Business Processes Test') . "</td>
        </tr>
        <tr>
            <td>Coding and logic</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Coding and Logic") . "<br>" . ReviewSkillsScans::getEventDate($events,'Coding and Logic') . "</td>
            <td>9628-09 coding and logic test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "9628-09 Coding and Logic Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-09 Coding and Logic Test') . "</td>
            <td>Network Fundamentals MTA Test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "Network Fundamentals MTA Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Network Fundamentals MTA Test') . "</td>
        </tr>
        <tr>
            <td>9628-06 networking and architecture test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "9628-06 networking and architecture test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-06 networking and architecture test') . "</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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

    public static function getEventStatus($statuses, $event)
    {
        $res = "";
        foreach($statuses as $status)
        {
            if(isset($status['unit_ref']) and strtoupper($status['unit_ref'])==strtoupper($event))
            {
                $res = $status['code'];
                break;
            }
        }
        return $res;
    }

    public static function getEventDate($statuses, $event)
    {
        $res = "";
        foreach($statuses as $status)
        {
            if(isset($status['unit_ref']) and strtoupper($status['unit_ref'])==strtoupper($event))
            {
                $res = Date::toShort($status['date']);
                break;
            }
        }
        return $res;
    }

    public static function isAssessmentComplete($Assessment_Plan, $plans_array)
    {
        $assessmentComplete = true;
        foreach($plans_array as $plan)
        {
            if(!isset($Assessment_Plan[$plan]))
            {
                $assessmentComplete = false;
                break;
            }
            elseif(isset($Assessment_Plan[$plan]) and $Assessment_Plan[$plan]!="Complete")
            {
                $assessmentComplete = false;
                break;
            }
        }
        return $assessmentComplete;
    }


    public static function getSkillsScanRiskAnalystPrior($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events)
    {
            $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
            <tr>
            <td>Risk Analyst Activity 1</td>
            <td>";
        if(isset($Assessment_Plan['Risk Analyst Activity 1']))
            $html .= $Assessment_Plan['Risk Analyst Activity 1'];
        $html .= "</td>
                <td>Risk Analyst Activity 2</td>
                <td>";
        if(isset($Assessment_Plan['Risk Analyst Activity 2']))
            $html .= $Assessment_Plan['Risk Analyst Activity 2'];
        $html .= "</td>
                <td>Risk Analyst Activity 3</td>
                <td>";
        if(isset($Assessment_Plan['Risk Analyst Activity 3']))
            $html .= $Assessment_Plan['Risk Analyst Activity 3'];
        $html .= "</td>
            </tr>
            <tr>
                <td>Risk Analyst Activity 4</td>
                <td>";
        if(isset($Assessment_Plan['Risk Analyst Activity 4']))
            $html .= $Assessment_Plan['Risk Analyst Activity 4'];
        $html .= "</td>
                <td>Core Activity 1</td>
                <td>";
        if(isset($Assessment_Plan['Core Activity 1']))
            $html .= $Assessment_Plan['Core Activity 1'];
        $html .= "</td>
                <td>Core Activity 2</td>
                <td>";
        if(isset($Assessment_Plan['Core Activity 2']))
            $html .= $Assessment_Plan['Core Activity 2'];
        $html .= "</td>
            </tr>
            <tr>
                <td>Core Activity 3</td>
                <td>";
        if(isset($Assessment_Plan['Core Activity 3']))
            $html .= $Assessment_Plan['Core Activity 3'];
        $html .= "</td>
                <td>Core Activity 4</td>
                <td>";
        if(isset($Assessment_Plan['Core Activity 4']))
            $html .= $Assessment_Plan['Core Activity 4'];
        $html .= "</td>
                <td></td>
                <td></td>
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

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
            <tr>
                <td>Certificate in cyber security introduction</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in cyber security introduction") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in cyber security introduction') . "</td>
                <td>Certificate in cyber security introduction test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in cyber security introduction test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in cyber security introduction test') . "</td>
                <td>Certificate in network and digital communications</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in network and digital communications") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in network and digital communications') . "</td>
            </tr>
            <tr>
                <td>Certificate in security technology building blocks</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in security technology building blocks") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in security technology building blocks') . "</td>
                <td>Certificate in governance organisation law regulation and standards</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in governance organisation law regulation and standards") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in governance organisation law regulation and standards') . "</td>
                <td>Certificate in governance organisation law regulation and standards test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Certificate in governance organisation law regulation and standards test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Certificate in governance organisation law regulation and standards test') . "</td>
            </tr>
            <tr>
                <td>Award in risk assessment</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Award in risk assessment") . "<br>" . ReviewSkillsScans::getEventDate($events,'Award in risk assessment') . "</td>
                <td>Award in risk assessment test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Award in risk assessment test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Award in risk assessment test') . "</td>
                <td></td>
                <td></td>
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

    public static function getSkillsScanNetworkEngineerPrior($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events)
    {
        $html = "<table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th>
        </tr>
        </thead>
        <tbody>
        <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
        <tr>
            <td>Design Networks from a specification</td>
            <td>";
        if(isset($Assessment_Plan['Design Networks from a specification']))
            $html .= $Assessment_Plan['Design Networks from a specification'] . "</td>
            <td>Network Installation</td>
            <td>";
        if(isset($Assessment_Plan['Network Installation']))
            $html .= $Assessment_Plan['Network Installation']. "</td>
            <td>Network Performance</td>
            <td>";
        if(isset($Assessment_Plan['Network Performance']))
            $html .= $Assessment_Plan['Network Performance']. "</td>
        </tr>
        <tr>
            <td>Diagnostic Tools & Techniques</td>
            <td>";
        if(isset($Assessment_Plan['Diagnostic Tools & Techniques']))
            $html .= $Assessment_Plan['Diagnostic Tools & Techniques'] . "</td>
            <td>Troubleshooting & Repair</td>
            <td>";
        if(isset($Assessment_Plan['Troubleshooting & Repair']))
            $html .= $Assessment_Plan['Troubleshooting & Repair'] . "</td>
            <td>Integrating Network Software</td>
            <td>";
        if(isset($Assessment_Plan['Integrating Network Software']))
            $html .= $Assessment_Plan['Integrating Network Software'] . "</td>
        </tr>
        <tr>
            <td>Monitor Test & Adjust Networks</td>
            <td>";
        if(isset($Assessment_Plan['Monitor Test & Adjust Networks']))
            $html .= $Assessment_Plan['Monitor Test & Adjust Networks'] . "</td>
            <td>Logging & Responding to Calls</td>
            <td>";
        if(isset($Assessment_Plan['Logging & Responding to Calls']))
            $html .= $Assessment_Plan['Logging & Responding to Calls'] . "</td>
            <td>Service Level Agreements</td>
            <td>";
        if(isset($Assessment_Plan['Service Level Agreements']))
            $html .= $Assessment_Plan['Service Level Agreements'] . "</td>
        </tr>
        <tr>
            <td>Effective Business Operation</td>
            <td>";
        if(isset($Assessment_Plan['Effective Business Operation']))
            $html .= $Assessment_Plan['Effective Business Operation'] . "</td>
            <td>Interpret Written Requirements and Tech Specs</td>
            <td>";
        if(isset($Assessment_Plan['Interpret Written Requirements and Tech Specs']))
            $html .= $Assessment_Plan['Interpret Written Requirements and Tech Specs'] . "</td>
            <td>Documenting</td>
            <td>";
        if(isset($Assessment_Plan['Documenting']))
            $html .= $Assessment_Plan['Documenting'] . "</td>
        </tr>
        <tr>
            <td>Upgrading Network Systems</td>
            <td>";
        if(isset($Assessment_Plan['Upgrading Network Systems']))
            $html .= $Assessment_Plan['Upgrading Network Systems'] . "</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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
    <table class=\"table1\" style=\"width: 900px\">
        <thead>
        <tr>
        <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
        </tr>
        </thead>
        <tbody>
        <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
        <tr>
            <td>CompTIA server+</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "CompTIA server+") . "<br>" . ReviewSkillsScans::getEventDate($events,'CompTIA server+') . "</td>
            <td>9628-04 network systems and architecture test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "9628-04 network systems and architecture test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-04 network systems and architecture test') . "</td>
            <td>CompTIA security+</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "CompTIA security+") . "<br>" . ReviewSkillsScans::getEventDate($events,'CompTIA security+') . "</td>
        </tr>
        <tr>
            <td>9628-05 Network security test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "9628-05 Network security test") . "<br>" . ReviewSkillsScans::getEventDate($events,'CompTIA security') . "</td>
            <td>CompTIA Network+</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "CompTIA Network+") . "<br>" . ReviewSkillsScans::getEventDate($events,'CompTIA Network+') . "</td>
            <td>CompTIA Network+ Test</td>
            <td>" . ReviewSkillsScans::getEventStatus($events, "CompTIA Network+ Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'CompTIA Network+ Test') . "</td>
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

    public static function getSkillsScanNetworkEngineerV2Prior($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events)
    {
            $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
            <tr>
                <td>Design Networks from a specification</td>
                <td>";
        if(isset($Assessment_Plan['Design Networks from a specification']))
            $html .= $Assessment_Plan['Design Networks from a specification'];
        $html .= "</td>
                <td>Network Installation</td>
                <td>";
        if(isset($Assessment_Plan['Network Installation']))
            $html .= $Assessment_Plan['Network Installation'];
                $html .= "</td>
                <td>Network Performance</td>
                <td>";
        if(isset($Assessment_Plan['Network Performance']))
            $html .= $Assessment_Plan['Network Performance'];
                $html .= "</td>
            </tr>
            <tr>
                <td>Diagnostic Tools & Techniques</td>
                <td>";
        if(isset($Assessment_Plan['Diagnostic Tools & Techniques']))
            $html .= $Assessment_Plan['Diagnostic Tools & Techniques'];
        $html .= "</td>
                <td>Troubleshooting & Repair</td>
                <td>";
        if(isset($Assessment_Plan['Troubleshooting & Repair']))
            $html .= $Assessment_Plan['Troubleshooting & Repair'];
        $html .= "</td>
                <td>Integrating Network Software</td>
                <td>";
        if(isset($Assessment_Plan['Integrating Network Software']))
            $html .= $Assessment_Plan['Integrating Network Software'];
        $html .= "</td>
            </tr>
            <tr>
                <td>Monitor Test & Adjust Networks</td>
                <td>";
        if(isset($Assessment_Plan['Monitor Test & Adjust Networks']))
            $html .= $Assessment_Plan['Monitor Test & Adjust Networks'];
        $html .= "</td>
                <td>Logging & Responding to Calls</td>
                <td>";
        if(isset($Assessment_Plan['Logging & Responding to Calls']))
            $html .= $Assessment_Plan['Logging & Responding to Calls'];
        $html .= "</td>
                <td>Service Level Agreements</td>
                <td>";
        if(isset($Assessment_Plan['Service Level Agreements']))
            $html .= $Assessment_Plan['Service Level Agreements'];
        $html .= "</td>
            </tr>
            <tr>
                <td>Effective Business Operation</td>
                <td>";
        if(isset($Assessment_Plan['Effective Business Operation']))
            $html .= $Assessment_Plan['Effective Business Operation'];
        $html .= "</td>
                <td>Interpret Written Requirements and Tech Specs</td>
                <td>";
        if(isset($Assessment_Plan['Interpret Written Requirements and Tech Specs']))
            $html .= $Assessment_Plan['Interpret Written Requirements and Tech Specs'];
        $html .= "</td>
                <td>Documenting</td>
                <td>";
        if(isset($Assessment_Plan['Documenting']))
            $html .= $Assessment_Plan['Documenting'];
        $html .= "</td>
            </tr>
            <tr>
                <td>Upgrading Network Systems</td>
                <td>";
        if(isset($Assessment_Plan['Upgrading Network Systems']))
            $html .= $Assessment_Plan['Upgrading Network Systems'];
        $html .= "</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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
        </table>";
        $html .= "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
            <tr>
                <td>9628-04 network systems and architecture test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "9628-04 network systems and architecture test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-04 network systems and architecture test') . "</td>
                <td>9628-05 network security test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "9628-05 network security test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-05 network security test') . "</td>
                <td>Mobility and devices MTA</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Mobility and devices MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Mobility and devices MTA') . "</td>
            </tr>
            <tr>
                <td>Cloud fundamentals MTA</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Cloud fundamentals MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Cloud fundamentals MTA') . "</td>
                <td>Mobility and devices MTA test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Mobility and devices MTA test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Mobility and devices MTA test') . "</td>
                <td>Cloud fundamentals MTA TEST</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Cloud fundamentals MTA TEST") . "<br>" . ReviewSkillsScans::getEventDate($events,'Cloud fundamentals MTA TEST') . "</td>
            </tr>
            <tr>
                <td>9628-03 Networking principles test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "9628-03 Networking principles test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-03 Networking principles test') . "</td>
                <td>Networking principles</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Networking principles") . "<br>" . ReviewSkillsScans::getEventDate($events,'Networking principles') . "</td>
                <td>Network systems and architecture</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Network systems and architecture") . "<br>" . ReviewSkillsScans::getEventDate($events,'Network systems and architecture') . "</td>
            </tr>
            <tr>
                <td>Network security</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Network security") . "<br>" . ReviewSkillsScans::getEventDate($events,'Network security') . "</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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

    public static function getLearnerEmployerComments($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events)
    {
        $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Any other business</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            <td colspan=4>Any other business<br>Learner comments<br>Learning mentor comments<br>Documenting learner development</td>
            </tr>
            <tr><td>" . $form_arf->learner_comment . "</td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr><th>&nbsp;&nbsp;&nbsp;Completed SMART CLASSROOM Link:</th>
            <th style=\"text-align: center\">
                " . $form_arf->adobe . "
            </th></tr>
            </thead>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <tbody><tr>
                <td colspan=4>&nbsp;&nbsp;&nbsp;Date of Next Review: </td>
                <td style=\"text-align: center\">
                    " . Date::toMedium($form_arf->next_contact) . "
                </td>
                <td>&nbsp;&nbsp;&nbsp;Hours: </td>
                <td style=\"text-align: center\">
                    " . $form_arf->hours . "
                </td>
                <td>&nbsp;&nbsp;&nbsp;Minutes: </td>
                <td style=\"text-align: center\">
                    " . $form_arf->minutes . "
                </td>
            </tr></tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <tbody><tr>
                <td colspan=4>&nbsp;&nbsp;&nbsp;Date of Next Support: </td>
                <td style=\"text-align: center\">
                    " . Date::toMedium($form_arf->next_support) . "
                </td>
                <td>&nbsp;&nbsp;&nbsp;Hours: </td>
                <td style=\"text-align: center\">
                    " . $form_arf->support_hours . "
                </td>
                <td>&nbsp;&nbsp;&nbsp;Minutes: </td>
                <td style=\"text-align: center\">
                    " . $form_arf->support_minutes . "
                </td>
            </tr></tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Learner Comments</th></tr>
            </thead>
            <tbody>
            <tr>
                <td>" . $form_arf->learner_comment2 . "</td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Employer Progress Review</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Please complete the following section to review your apprentice's progress in their training programme and at work.<br>In your opinion how is your apprentice progressing within their apprenticeship?</td>
            </tr>
            <tr>
                <td colspan=4>" . $form_arf->employer_progress_review . "</td>
            </tr>
            <tr>
                <td colspan=2>Are there any performance issues?</td>";
                $issues = Array(Array('1','Yes'),Array('2','No'));
            $html .= "<td colspan=2 style='text-align:center; vertical-align:middle'>" . HTML::select("performance_issues", $issues, $form_arf->performance_issues, true, false) . "</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->emp_logical_creative . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>";
        return $html;
    }

    public static function getLearnerEmployerCommentsOld($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events)
    {
        $html="<table class=\"table1\" style=\"width: 900px\">
    <tr>
    <td>&nbsp;&nbsp;&nbsp;Manager Attendance:";
        $html.="<td style=\"text-align: center;\">"; $checked = ($form_arf->manager_attendance=="1")?" checked ":""; $html.= "<input disabled=\"disabled\" name = \"manager_attendance\" type = checkbox $checked>"; echo "<input type=\"hidden\" name = \"manager_attendance\" value=\"1\"></td>";
        $html.="</td>
    </tr>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <tr>
        <td colspan=4>&nbsp;&nbsp;&nbsp;Date of next contact: </td>
        <td style=\"text-align: center\">
            " . $form_arf->next_contact . "
        </td>
        <td>&nbsp;&nbsp;&nbsp;Hours: </td>
        <td style=\"text-align: center\">
            " . $form_arf->hours . "
        </td>
        <td>&nbsp;&nbsp;&nbsp;Minutes: </td>
        <td style=\"text-align: center\">
            " . $form_arf->minutes . "
        </td>
    </tr>
</table>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th>&nbsp;&nbsp;&nbsp;Adobe Link: </th>
    <th style=\"text-align: center\">
        " . $form_arf->adobe . "
    </th></tr>
    </thead>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Learner Comments</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>(to include general comments on course, feedback on how they feel it is going, new skills developed)</td>
    </tr>
    <tr>
        <td>" . $form_arf->learner_comment . "</td>
    </tr>
    </tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Employer Progress Review</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4>Please complete the following section to review your apprentice's progress in their apprenticeship.<br>How does your apprentice contribute to your team/business?</td>
    </tr>
    <tr>
        <td colspan=4><textarea tabindex=\"-1\" id=\"employer_progress_review\" name=\"employer_progress_review\" onblur=\"checkLength(event,this,10000)\" onkeypress=\"checkLength(event,this,5000)\" style=\"font-family:sans-serif; font-size:10pt\"  rows=\"10\" cols=\"123\">" . $form_arf->employer_progress_review . "</textarea></td>
    </tr>
    <tr>
        <td colspan=4>Please tick the following (this is to support your apprentice to maintain/improve behaviour).</td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td style=\"width: 200px; text-align: center\">&nbsp;</td>
                    <td style=\"width: 200px; text-align: center\">Poor</td>
                    <td style=\"width: 200px; text-align: center\">Satisfactory</td>
                    <td style=\"width: 200px; text-align: center\">Good</td>
                    <td style=\"width: 200px; text-align: center\">Excellent</td>
                </tr>
                <tr>
                    <td style=\"width: 200px; text-align: left\">Attendance</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("attendance", 1, ($form_arf->attendance==1)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("attendance", 2, ($form_arf->attendance==2)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("attendance", 3, ($form_arf->attendance==3)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("attendance", 4, ($form_arf->attendance==4)?true:false, true, false) . "</td>
                </tr>
                <tr>
                    <td style=\"width: 200px; text-align: left\">Punctuality/Timekeeping</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("punctuality", 1, ($form_arf->punctuality==1)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("punctuality", 2, ($form_arf->punctuality==2)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("punctuality", 3, ($form_arf->punctuality==3)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("punctuality", 4, ($form_arf->punctuality==4)?true:false, true, false) . "</td>
                </tr>
                <tr>
                    <td style=\"width: 200px; text-align: left\">Attitude</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("attitude", 1, ($form_arf->attitude==1)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("attitude", 2, ($form_arf->attitude==2)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("attitude", 3, ($form_arf->attitude==3)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("attitude", 4, ($form_arf->attitude==4)?true:false, true, false) . "</td>
                </tr>
                <tr>
                    <td style=\"width: 200px; text-align: left\">Communication</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("communication", 1, ($form_arf->communication==1)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("communication", 2, ($form_arf->communication==2)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("communication", 3, ($form_arf->communication==3)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("communication", 4, ($form_arf->communication==4)?true:false, true, false) . "</td>
                </tr>
                <tr>
                    <td style=\"width: 200px; text-align: left\">Enthusiasm</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("enthusiasm", 1, ($form_arf->enthusiasm==1)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("enthusiasm", 2, ($form_arf->enthusiasm==2)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("enthusiasm", 3, ($form_arf->enthusiasm==3)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("enthusiasm", 4, ($form_arf->enthusiasm==4)?true:false, true, false) . "</td>
                </tr>
                <tr>
                    <td style=\"width: 200px; text-align: left\">Commitment to the role</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("commitment", 1, ($form_arf->commitment==1)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("commitment", 2, ($form_arf->commitment==2)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("commitment", 3, ($form_arf->commitment==3)?true:false, true, false) . "</td>
                    <td style=\"width: 200px; text-align: center\">" . HTML::radio("commitment", 4, ($form_arf->commitment==4)?true:false, true, false) . "</td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<br>
<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Employer Comments (Please record here any comments you would like to add judging the attitude and behaviour of your apprentice in regards to the following areas:)</th></tr>
    </thead>
    <tbody>
    <tr>
        <td colspan=4><b>Logical and creative thinking skills:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->emp_logical_creative . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Analytical and problem solving skills:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->emp_problem_solving . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Ability to work independently and to take responsibility:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->emp_independently . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Can use own initiative:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->emp_initiative . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>A thorough and organised approach:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->emp_organised . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Ability to work with a range of internal and external people:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->emp_internal_external . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Ability to communicate effectively in a variety of situations:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->emp_communicate_effectively . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>Maintain productive, professional and secure working environment:</td>
    </tr>
    <tr>
        <td colspan=4><i>
            " . $form_arf->emp_maintain_productive . "
        </i></td>
    </tr>
    <tr>
        <td colspan=4><b>All comments are important and any development areas will be set as objectives for your apprentice</td>
    </tr>
    </tbody>
</table>
<br>";
    return $html;

    }

    public static function getSkillsScanSoftwareDevelopmentTechnician($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events)
    {
        $assessor_signed = true;
        $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=5>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th>
            </tr>
            </thead>
            <tbody>
            <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
            <tr>
                <td rowspan=1>Logic</td>
                <td>Write simple code for discrete software components following appropriate logical approach to agreed standards</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Logic'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
                $communication = isset($Assessment_Plan['Logic'])?$Assessment_Plan['Logic']:"";
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
            </tr>
            <tr>
                <td>Security</td>
                <td>Apply secure development principles to specific software components</td>
                <td style='text-align:center; vertical-align:middle'>" . $ss_result['Security'] . "</td>
                <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
                $it_security = isset($Assessment_Plan['Security'])?$Assessment_Plan['Security']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
            </tr>
            <tr>
                <td>Development Support</td>
                <td>Apply industry standard approaches for configuration management and version control to manage code during build and release</td>
                <td style='text-align:center; vertical-align:middle'>" . $ss_result['Development Support'] . "</td>
                <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
                $remote_infrastructure = isset($Assessment_Plan['Development Support'])?$Assessment_Plan['Development Support']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
            </tr>
            <tr>
                <td>Data</td>
                <td>Make connections between code and defined data sources</td>
                <td style='text-align:center; vertical-align:middle'>" . $ss_result['Data'] . "</td>
                <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
                $data = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
            </tr>
            <tr>
                <td rowspan=1>Test</td>
                <td>Conduct functionality tests on deliverables for that component</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Test'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
                $problem_solving = isset($Assessment_Plan['Test'])?$Assessment_Plan['Test']:"";
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>
            </tr>
            <tr>
                <td>Analysis</td>
                <td>Basic analysis models such as use cases and process maps</td>
                <td style='text-align:center; vertical-align:middle'>" . $ss_result['Analysis'] . "</td>
                <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
                $workflow_management = isset($Assessment_Plan['Analysis'])?$Assessment_Plan['Analysis']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>
            </tr>
            <tr>
                <td>Development Lifecycle</td>
                <td>Supports the Software Developers at the build and test stages of the software development lifecycle</td>
                <td style='text-align:center; vertical-align:middle'>" . $ss_result['Development Lifecycle'] . "</td>
                <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
                $health_safety = isset($Assessment_Plan['Development Lifecycle'])?$Assessment_Plan['Development Lifecycle']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
            </tr>
            <tr>
                <td rowspan=1>Quality</td>
                <td>Follows organisational and industry good coding practices (including those for naming, commenting etc.)</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Quality'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
                $performance = isset($Assessment_Plan['Quality'])?$Assessment_Plan['Quality']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
            </tr>
            <tr>
                <td rowspan=1>Problem Solving</td>
                <td>Solve logical problems including appropriate mathematical application</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Problem Solving'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
                $performance = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
            </tr>
            <tr>
                <td rowspan=1>Business Operation</td>
                <td>Respond to business issues related to software development</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Business Operation'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";
                $performance = isset($Assessment_Plan['Business Operation'])?$Assessment_Plan['Business Operation']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
            </tr>
            <tr>
                <td rowspan=1>Communication</td>
                <td>Articulate the role and function of software components to a variety of stakeholders</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Communication'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>";
                $performance = isset($Assessment_Plan['Communication'])?$Assessment_Plan['Communication']:"";
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $performance . "</td>
            </tr>
            <tr>
                <td rowspan=1>User Interface</td>
                <td>Develop user interfaces appropriate to the organisation’s development standards and the type of component being developed.</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['User Interface'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false) . "</td>";
                $performance = isset($Assessment_Plan['User Interface'])?$Assessment_Plan['User Interface']:"";
        $html .= "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
            </tr>
            </tbody>
        </table>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=5>&nbsp;&nbsp;&nbsp;Technical Knowledge</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
            <tr>
                <td>Business context and market environment for software development</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Business context and market environment for software development'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Software Development Context and Methodologies test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Software Development Context and Methodologies test') . "</td>
            </tr>
            <tr>
                <td>Structure of software applications</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Structure of software applications'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Software Development Context and Methodologies test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Software Development Context and Methodologies test') . "</td>
            </tr>
            <tr>
                <td>Stages of the software development lifecycle</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Stages of the software development lifecycle'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Software Development Context and Methodologies test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Software Development Context and Methodologies test') . "</td>
            </tr>
            <tr>
                <td>Configuration management and version control systems and how to apply them</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Configuration management and version control systems and how to apply them'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Software Development Context and Methodologies test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Software Development Context and Methodologies test') . "</td>
            </tr>
            <tr>
                <td>How to test code (e.g. unit testing)</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How to test code (e.g. unit testing)'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Software Development Context and Methodologies test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Software Development Context and Methodologies test') . "</td>
            </tr>
            <tr>
                <td>Different methodologies that can be used for software development</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Different methodologies that can be used for software development'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Software Development Context and Methodologies test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Software Development Context and Methodologies test') . "</td>
            </tr>
            <tr>
                <td>Context for the development platform (whether web, mobile, or desktop applications)</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Context for the development platform (whether web, mobile, or desktop applications)'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Software Development Context and Methodologies test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Software Development Context and Methodologies test') . "</td>
            </tr>
            <tr>
                <td>The technician role within their software development team</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The technician role within their software development team'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Software Development Context and Methodologies test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Software Development Context and Methodologies test') . "</td>
            </tr>
            <tr>
                <td>How to implement code following a logical approach</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How to implement code following a logical approach'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Programming Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming Test') . "</td>
            </tr>
            <tr>
                <td>How code integrates into the wider project</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How code integrates into the wider project'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Programming Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming Test') . "</td>
            </tr>
            <tr>
                <td>How to follow a set of functional and non-functional requirements</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How to follow a set of functional and non-functional requirements'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Programming Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming Test') . "</td>
            </tr>
            <tr>
                <td>End user context for the software development activity</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['End user context for the software development activity'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Programming Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming Test') . "</td>
            </tr>
            <tr>
                <td>How to connect their code to specified data sources</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How to connect their code to specified data sources'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Programming Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming Test') . "</td>
            </tr>
            <tr>
                <td>Database normalisation</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Database normalisation'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status28", $ss_statuses, $form_arf->skills_scan_status28, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Programming Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming Test') . "</td>
            </tr>
            <tr>
                <td>Why there is a need to follow good coding practices</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Why there is a need to follow good coding practices'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status29", $ss_statuses, $form_arf->skills_scan_status29, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Programming Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming Test') . "</td>
            </tr>
            <tr>
                <td>Principles of good interface design</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Principles of good interface design'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status30", $ss_statuses, $form_arf->skills_scan_status30, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Programming Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming Test') . "</td>
            </tr>
            <tr>
                <td>The importance of building in security to software at the development stage</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The importance of building in security to software at the development stage'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status31", $ss_statuses, $form_arf->skills_scan_status31, true, false) . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . ReviewSkillsScans::getEventStatus($events,'Programming Test') . "<br>" . ReviewSkillsScans::getEventDate($events,'Programming Test') . "</td>
            </tr>
            </tbody>
        </table>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Current Skill Scan Status</td><td>Assessment Plan Status</td></tr>
            <tr>
                <td>Logical and creative thinking skills - A</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills - A'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status32", $ss_statuses, $form_arf->skills_scan_status32, true, false) . "</td>";
                if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Logic", "Security", "Data", "Test", "Development Lifecycle", "Problem Solving", "User Interface")))
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
            $html.="</tr>
            <tr>
                <td>Analytical and problem solving skills - B</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Analytical and problem solving skills - B'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status33", $ss_statuses, $form_arf->skills_scan_status33, true, false) . "</td>";
                if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Logic", "Security", "Data", "Test", "Development Lifecycle", "Problem Solving", "User Interface")))
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
            $html.="</tr>
            <tr>
                <td>Ability to work independently and to take responsibility - C</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work independently and to take responsibility - C'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status34", $ss_statuses, $form_arf->skills_scan_status34, true, false) . "</td>";
                if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Logic", "Problem Solving", "Development Support", "Communication")))
                $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
            $html.="</tr>
            <tr>
                <td>Can use own initiative - D</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Can use own initiative - D'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status35", $ss_statuses, $form_arf->skills_scan_status35, true, false) . "</td>";
                if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Logic", "Problem Solving", "Communication")))
                $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
            $html .= "</tr>
            <tr>
                <td>A thorough and organised approach - E</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['A thorough and organised approach - E'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status36", $ss_statuses, $form_arf->skills_scan_status36, true, false) . "</td>";
                if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Logic", "Development Support", "Data", "Test", "Analysis", "Development Lifecycle", "Quality", "Problem Solving", "Communication", "Business Operation", "User Interface")))
                $html.= "<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
            $html.="</tr>
            <tr>
                <td>Ability to work with a range of internal and external people - F</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to work with a range of internal and external people - F'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status37", $ss_statuses, $form_arf->skills_scan_status37, true, false) . "</td>";
                if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Logic", "Development Support", "Data", "Test", "Analysis", "Development Lifecycle", "Quality", "Problem Solving", "Communication", "Business Operation", "User Interface")))
                $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
            $html.="</tr>
            <tr>
                <td>Ability to communicate effectively in a variety of situations - G</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Ability to communicate effectively in a variety of situations - G'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status38", $ss_statuses, $form_arf->skills_scan_status38, true, false) . "</td>";
                if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Commination", "Test", "Analysis", "Development Lifecycle", "Problem Solving")))
                $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
            $html.="</tr>
            <tr>
                <td>Maintain productive, professional and secure working environment - H</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Maintain productive, professional and secure working environment - H'] . "</td>
                <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status39", $ss_statuses, $form_arf->skills_scan_status39, true, false) . "</td>";
                if(ReviewSkillsScans::isAssessmentComplete($Assessment_Plan, Array("Logic", "Development Support", "Data", "Test", "Analysis", "Development Lifecycle", "Quality", "Problem Solving", "Communication", "Business Operation", "User Interface")))
                $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'>Complete</td>";
            else
                $html.="<td rowspan=1 style='text-align:center; vertical-align:middle'></td>";
            $html.="</tr>
            </tbody>
        </table>
        <br>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
            </tr>
            </thead>
            <tbody>
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
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <br>
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan=4>Record here the detail of the progress. What has the learner been doing towards completing this and discuss employer reference progress</td>
            </tr>
            <tr>
                <td colspan=4><i>
                    " . $form_arf->workplace_competence . "
                </i></td>
            </tr>
            </tbody>
        </table>
        <br>

        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=4>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
            </tr>
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

    public static function getSkillsScanItInfrastructureTechnicianV2($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events)
    {
        $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
            <tr>
                <td>Communication</td>
                <td>";
            if(isset($Assessment_Plan['Communication']))
                $html.= $Assessment_Plan['Communication'];
            $html.="</td>
                <td>Health & Safety</td>
                <td>";
            if(isset($Assessment_Plan['Health & Safety']))
                $html.= $Assessment_Plan['Health & Safety'];
            $html.="</td>
                <td>Remote Infrastructure</td>
                <td>";
            if(isset($Assessment_Plan['Remote Infrastructure']))
                $html.= $Assessment_Plan['Remote Infrastructure'];
            $html.="</td>
            </tr>
            <tr>
                <td>Data</td>
                <td>";
            if(isset($Assessment_Plan['Data']))
                $html.=$Assessment_Plan['Data'];
            $html.="</td>
                <td>Workflow Management</td>
                <td>";
            if(isset($Assessment_Plan['Workflow Management']))
                $html.=$Assessment_Plan['Workflow Management'];
            $html.="</td>
                <td>IT Security</td>
                <td>";
            if(isset($Assessment_Plan['IT Security']))
                $html.=$Assessment_Plan['IT Security'];
            $html.="</td>
            </tr>
            <tr>
                <td>Problem Solving</td>
                <td>";
            if(isset($Assessment_Plan['Problem Solving']))
                $html.=$Assessment_Plan['Problem Solving'];
            $html.="</td>
                <td>Performance</td>
                <td>";
            if(isset($Assessment_Plan['Performance']))
                $html.=$Assessment_Plan['Performance'];
            $html.="</td>
                <td>WEEE</td>
                <td>";
            if(isset($Assessment_Plan['WEEE']))
                $html.=$Assessment_Plan['WEEE'];
            $html.="</td>
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
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
            <tr>
                <td>Network Fundamentals MTA</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Network Fundamentals MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Network Fundamentals MTA') . "</td>
                <td>Network Fundamentals MTA Test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Network Fundamentals MTA Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Network Fundamentals MTA Test') . "</td>
                <td>Mobility and Devices MTA</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Mobility and Devices MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Mobility and Devices MTA') . "</td>
            </tr>
            <tr>
                <td>Mobility and Devices MTA Test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Mobility and Devices MTA Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Mobility and Devices MTA Test') . "</td>
                <td>Cloud Fundamentals MTA</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Cloud Fundamentals MTA") . "<br>" . ReviewSkillsScans::getEventDate($events,'Cloud Fundamentals MTA') . "</td>
                <td>Cloud Fundamentals MTA Test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Cloud Fundamentals MTA Test") . "<br>" . ReviewSkillsScans::getEventDate($events,'Cloud Fundamentals MTA Test') . "</td>
            </tr>
            <tr>
                <td>ITIL Business Processes</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "ITIL Business Processes") . "<br>" . ReviewSkillsScans::getEventDate($events,'ITIL Business Processes') . "</td>
                <td>9628-10 ITIL foundation test business processes test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "9628-10 ITIL foundation test business processes test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-10 ITIL foundation test business processes test') . "</td>
                <td>Coding and Logic</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Coding and Logic") . "<br>" . ReviewSkillsScans::getEventDate($events,'Coding and Logic') . "</td>
            </tr>
            <tr>
                <td>9628-09 Coding and logic test</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "9628-09 Coding and logic test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-09 Coding and logic test') . "</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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

    public static function getSkillsScanSoftwareDeveloperPrior($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events)
    {
         $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Work Place Competence</th>
            </tr>
            </thead>
            <tbody>
            <tr><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td><td>Competence</td><td>Status</td></tr>
            <tr>
                <td>Business Environment</td>
                <td>";
        if(isset($Assessment_Plan['Business Environment']))
            $html.=$Assessment_Plan['Business Environment'];
        $html.="</td>
                <td>Operational Requirements</td>
                <td>";
        if(isset($Assessment_Plan['Operational Requirements']))
            $html.=$Assessment_Plan['Operational Requirements'];
        $html.="</td>
                <td>Service Level Agreements</td>
                <td>";
        if(isset($Assessment_Plan['Service Level Agreements']))
            $html.=$Assessment_Plan['Service Level Agreements'];
        $html.="</td>
            </tr>
            <tr>
                <td>User Interface</td>
                <td>";
        if(isset($Assessment_Plan['User Interface']))
            $html.=$Assessment_Plan['User Interface'];
        $html.="</td>
                <td>Design</td>
                <td>";
        if(isset($Assessment_Plan['Design']))
            $html.=$Assessment_Plan['Design'];
        $html.="</td>
                <td>Testing</td>
                <td>";
        if(isset($Assessment_Plan['Testing']))
            $html.=$Assessment_Plan['Testing'];
        $html.="</td>
            </tr>
            <tr>
                <td>Problem Solving</td>
                <td>";
        if(isset($Assessment_Plan['Problem Solving']))
            $html.=$Assessment_Plan['Problem Solving'];
        $html.="</td>
                <td>Deployment</td>
                <td>";
        if(isset($Assessment_Plan['Deployment']))
            $html.=$Assessment_Plan['Deployment'];
        $html.="</td>
                <td>Analysis</td>
                <td>";
        if(isset($Assessment_Plan['Analysis']))
            $html.=$Assessment_Plan['Analysis'];
        $html.="</td>
            </tr>
            <tr>
                <td>Development Lifecycle</td>
                <td>";
        if(isset($Assessment_Plan['Development Lifecycle']))
            $html.=$Assessment_Plan['Development Lifecycle'];
        $html.="</td>
                <td>Data</td>
                <td>";
        if(isset($Assessment_Plan['Data']))
            $html.=$Assessment_Plan['Data'];
        $html.="</td>
                <td>Good Practice</td>
                <td>";
        if(isset($Assessment_Plan['Good Practice']))
            $html.=$Assessment_Plan['Good Practice'];
        $html.="</td>
            </tr>
            <tr>
                <td>Logic</td>
                <td>";
        if(isset($Assessment_Plan['Logic']))
            $html.=$Assessment_Plan['Logic'];
        $html.="</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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
        <table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr>
            <th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th>
            </tr>
            </thead>
            <tbody>
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
                <td>" . ReviewSkillsScans::getEventStatus($events, "Object oriented programming") . "<br>" . ReviewSkillsScans::getEventDate($events,'Object oriented programming') . "</td>
                <td>Database design</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Database design") . "<br>" . ReviewSkillsScans::getEventDate($events,'Database design') . "</td>
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



    public static function getSkillsScanDigitalMarketerV5($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {
        $assessor_signed = true;
	$_sss_value = '';
        if(isset($sss[$previous_review->skills_scan_status1]))
            $_sss_value = $sss[$previous_review->skills_scan_status1];
        $html =
            "<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Skills Scan and Progress Summary</th></tr>
    </thead>
    <tbody>
    <tr><td colspan=2>Competence - Based on Assessment Plans</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Skills Scan Status</td><td>Assessment Plan Status</td></tr>
    <tr>
        <td rowspan=1>Communication</td>
        <td>Applies a good level of written communication skills for a range of audiences and digital platforms and with regard to the sensitivity of communication</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Communication'] . "</td>
        <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status1!="")?$_sss_value:"") . "</td>
        <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status1", $ss_statuses, $form_arf->skills_scan_status1, true, false, $assessor_signed) . "</td>";
        $communication = isset($Assessment_Plan['Written Communication'])?$Assessment_Plan['Written Communication']:"";
        $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $communication . "</td>
       </tr>
       <tr>
           <td>Research</td>
           <td>Analyses and contributes information on the digital environment to inform short and long term digital communications strategies and campaigns</td>
           <td style='text-align:center; vertical-align:middle'>" . $ss_result['Research'] . "</td>
           <td style='text-align:center'>" . (($previous_review->skills_scan_status2!="")?$sss[$previous_review->skills_scan_status2]:"") . "</td>
           <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status2", $ss_statuses, $form_arf->skills_scan_status2, true, false, $assessor_signed) . "</td>";
           $it_security = isset($Assessment_Plan['Research'])?$Assessment_Plan['Research']:"";
           $html .= "<td style='text-align:center; vertical-align:middle'>" . $it_security . "</td>
       </tr>
       <tr>
           <td>Technologies</td>
           <td>Recommends and applies effective, secure and appropriate solutions using a wide variety of digital technologies and tools over a range of platforms and user interfaces to achieve marketing objectives</td>
           <td style='text-align:center; vertical-align:middle'>" . $ss_result['Technologies'] . "</td>
           <td style='text-align:center'>" . (($previous_review->skills_scan_status3!="")?$sss[$previous_review->skills_scan_status3]:"") . "</td>
           <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status3", $ss_statuses, $form_arf->skills_scan_status3, true, false, $assessor_signed) . "</td>";
           $remote_infrastructure = isset($Assessment_Plan['Technologies'])?$Assessment_Plan['Technologies']:"";
           $html .= "<td style='text-align:center; vertical-align:middle'>" . $remote_infrastructure . "</td>
       </tr>
       <tr>
           <td>Data</td>
           <td>Reviews, monitors and analyses online activity and provides recommendations and insights to others</td>
           <td style='text-align:center; vertical-align:middle'>" . $ss_result['Data'] . "</td>
           <td style='text-align:center'>" . (($previous_review->skills_scan_status4!="")?$sss[$previous_review->skills_scan_status4]:"") . "</td>
           <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status4", $ss_statuses, $form_arf->skills_scan_status4, true, false, $assessor_signed) . "</td>";
           $data = isset($Assessment_Plan['Data'])?$Assessment_Plan['Data']:"";
           $html .= "<td style='text-align:center; vertical-align:middle'>" . $data . "</td>
       </tr>
       <tr>
           <td rowspan=1>Customer Service</td>
           <td>Responds efficiently to enquiries using online and social media platforms.</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Customer Service'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status5!="")?$sss[$previous_review->skills_scan_status5]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status5", $ss_statuses, $form_arf->skills_scan_status5, true, false, $assessor_signed) . "</td>";
           $problem_solving = isset($Assessment_Plan['Customer Service'])?$Assessment_Plan['Customer Service']:"";
           $html .= "<td rowspan=1 style='text-align:center; vertical-align:middle'>" . $problem_solving . "</td>
       </tr>
       <tr>
           <td>Problem Solving</td>
           <td>Applies structured techniques to problem solving, and analyses problems and resolves issues across a variety of digital platforms</td>
           <td style='text-align:center; vertical-align:middle'>" . $ss_result['Problem Solving'] . "</td>
           <td style='text-align:center'>" . (($previous_review->skills_scan_status6!="")?$sss[$previous_review->skills_scan_status6]:"") . "</td>
           <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status6", $ss_statuses, $form_arf->skills_scan_status6, true, false, $assessor_signed) . "</td>";
           $workflow_management = isset($Assessment_Plan['Problem Solving'])?$Assessment_Plan['Problem Solving']:"";
           $html .= "<td style='text-align:center; vertical-align:middle'>" . $workflow_management . "</td>
       </tr>
       <tr>
           <td>Analysis</td>
           <td>Creates basic analytical dashboards using appropriate digital tools</td>
           <td style='text-align:center; vertical-align:middle'>" . $ss_result['Analysis'] . "</td>
           <td style='text-align:center'>" . (($previous_review->skills_scan_status7!="")?$sss[$previous_review->skills_scan_status7]:"") . "</td>
           <td style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status7", $ss_statuses, $form_arf->skills_scan_status7, true, false, $assessor_signed) . "</td>";
           $health_safety = isset($Assessment_Plan['Analysis'])?$Assessment_Plan['Analysis']:"";
           $html .= "<td style='text-align:center; vertical-align:middle'>" . $health_safety . "</td>
       </tr>
       <tr>
           <td rowspan=1>Specialist Areas</td>
           <td>Search marketing, search engine optimisation, e mail marketing, web analytics and metrics, mobile apps and Pay-Per-Click</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Specialist Areas'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status8!="")?$sss[$previous_review->skills_scan_status8]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status8", $ss_statuses, $form_arf->skills_scan_status8, true, false, $assessor_signed) . "</td>";
           $performance = isset($Assessment_Plan['Specialist Areas'])?$Assessment_Plan['Specialist Areas']:"";
           $html .= "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
       </tr>
       <tr>
           <td rowspan=1>Digital Tools</td>
           <td>Effective use of digital tools</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Digital Tools'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status9!="")?$sss[$previous_review->skills_scan_status9]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status9", $ss_statuses, $form_arf->skills_scan_status9, true, false, $assessor_signed) . "</td>";
           $performance = isset($Assessment_Plan['Digital Tools'])?$Assessment_Plan['Digital Tools']:"";
           $html .= "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
       </tr>
       <tr>
           <td rowspan=1>Digital Analytics</td>
           <td>Measures and evaluates the success of digital marketing activities</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Digital Analytics'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status10!="")?$sss[$previous_review->skills_scan_status10]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status10", $ss_statuses, $form_arf->skills_scan_status10, true, false, $assessor_signed) . "</td>";
           $performance = isset($Assessment_Plan['Digital Analytics'])?$Assessment_Plan['Digital Analytics']:"";
           $html .= "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
       </tr>
       <tr>
           <td rowspan=4>Implementation</td>
           <td>Developments in digital media technologies and trends</td>
           <td rowspan=4 style='text-align:center; vertical-align:middle'>" . $ss_result['Implementation'] . "</td>
           <td rowspan=4 style='text-align:center'>" . (($previous_review->skills_scan_status11!="")?$sss[$previous_review->skills_scan_status11]:"") . "</td>
           <td rowspan=4 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status11", $ss_statuses, $form_arf->skills_scan_status11, true, false, $assessor_signed) . "</td>";
           $performance = isset($Assessment_Plan['Implementation'])?$Assessment_Plan['Implementation']:"";
           $html .= "<td rowspan=4 style='text-align:center; vertical-align:middle'>" . $performance . "</td>
       </tr>
       <tr><td>Marketing briefs and plans</td></tr>
       <tr><td>Company defined customer standards or industry good practice</td></tr>
       <tr><td>Company, team or client approaches to continuous integration</td></tr>
       <tr>
           <td rowspan=1>Effective Business Operation</td>
           <td>Operate effectively in own business, customers and industry environments</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Effective business operation'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status12!="")?$sss[$previous_review->skills_scan_status12]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status12", $ss_statuses, $form_arf->skills_scan_status12, true, false, $assessor_signed) . "</td>";
           $performance = isset($Assessment_Plan['Effective Business Operation'])?$Assessment_Plan['Effective Business Operation']:"";
           $html .= "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
       </tr>
       <tr>
           <td rowspan=1>Industry Developments and Practices</td>
           <td>Developments in digital media technologies and trends<br>Marketing briefs and plans<br>Company defined customer standards or industry good practice<br>Company, team or client approaches to continuous integration</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ss_result['Industry developments and practices'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>";
           $performance = isset($Assessment_Plan['Industry Developments and Practices'])?$Assessment_Plan['Industry Developments and Practices']:"";
           $html .= "<td style='text-align:center; vertical-align:middle'>" . $performance . "</td>
       </tr>
       </tbody>
   </table>
   <table class=\"table1\" style=\"width: 900px\">
       <thead>
       <tr><th colspan=4>&nbsp;&nbsp;&nbsp;Technical Knowledge</th></tr>
       </thead>
       <tbody><tr><td>Technical Knowledge</td><td>Skills Scan Grade</td><td>Previous Skill Scan Status</td><td>Current Assessment Plan Status</td></tr>
       <tr>
           <td>The principles of coding</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The principles of coding'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status13!="")?$sss[$previous_review->skills_scan_status13]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status13", $ss_statuses, $form_arf->skills_scan_status13, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>Applying basic marketing principles</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Applying basic marketing principles'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status14!="")?$sss[$previous_review->skills_scan_status14]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status14", $ss_statuses, $form_arf->skills_scan_status14, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>Applying the customer lifecycle</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Applying the customer lifecycle'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status15!="")?$sss[$previous_review->skills_scan_status15]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status15", $ss_statuses, $form_arf->skills_scan_status15, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>The role of customer relationship marketing</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The role of customer relationship marketing'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status16!="")?$sss[$previous_review->skills_scan_status16]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status16", $ss_statuses, $form_arf->skills_scan_status16, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>How  teams work effectively to deliver digital marketing campaigns and can deliver accordingly</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How  teams work effectively to deliver digital marketing campaigns and can deliver accordingly'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status17!="")?$sss[$previous_review->skills_scan_status17]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status17", $ss_statuses, $form_arf->skills_scan_status17, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>The main components of Digital and Social Media Strategies</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The main components of Digital and Social Media Strategies'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status18!="")?$sss[$previous_review->skills_scan_status18]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status18", $ss_statuses, $form_arf->skills_scan_status18, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>The principles of all of the following specialist areas: search marketing, search engine optimisation, e mail marketing, web analytics and metrics, mobile apps and Pay-Per-Click and understands how these can work together</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The principles of all of the following specialist areas: search marketing, search engine optimisation, e mail marketing, web analytics and metrics, mobile apps and Pay-Per-Click and understands how these can work together'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status19!="")?$sss[$previous_review->skills_scan_status19]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status19", $ss_statuses, $form_arf->skills_scan_status19, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>The similarities and differences, including positives and negatives, of all the major digital and social media platforms</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['The similarities and differences, including positives and negatives, of all the major digital and social media platforms'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status20!="")?$sss[$previous_review->skills_scan_status20]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status20", $ss_statuses, $form_arf->skills_scan_status20, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>Responds to the business environment and business issues related to digital marketing and customer needs</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Responds to the business environment and business issues related to digital marketing and customer needs'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status21!="")?$sss[$previous_review->skills_scan_status21]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status21", $ss_statuses, $form_arf->skills_scan_status21, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>Digital etiquette</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Digital etiquette'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status22!="")?$sss[$previous_review->skills_scan_status22]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status22", $ss_statuses, $form_arf->skills_scan_status22, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>How digital platforms integrate in to the working environment</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['How digital platforms integrate in to the working environment'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status23!="")?$sss[$previous_review->skills_scan_status23]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status23", $ss_statuses, $form_arf->skills_scan_status23, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>Required security levels necessary to protect data across digital and social media platforms</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $tk_result['Required security levels necessary to protect data across digital and social media platforms'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status24!="")?$sss[$previous_review->skills_scan_status24]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status24", $ss_statuses, $form_arf->skills_scan_status24, true, false, $assessor_signed) . "</td>
       </tr></tbody>
   </table>
   <table class=\"table1\" style=\"width: 900px\">
       <thead>
       <tr><th colspan=5>&nbsp;&nbsp;&nbsp;Attitudes & Behaviours</th></tr>
       </thead>
       <tbody><tr><td>Attitudes & Behaviours</td><td>Skills Scan Grade</td><td>Previous Review Skill Scan Status</td><td>Current Skills Scan Status</td></tr>
       <tr>
           <td>Logical and creative thinking skills - A</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Logical and creative thinking skills - A'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status25!="")?$sss[$previous_review->skills_scan_status25]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status25", $ss_statuses, $form_arf->skills_scan_status25, true, false, $assessor_signed) . "</td>
       </tr>
       <tr>
           <td>Example</td>
           <td colspan=3><i>
               " .  $form_arf->example1 . "
           </i></td>
       </tr>
       <tr>
           <td>Analytical and problem solving skills - B</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . $ab_result['Analytical and problem solving skills - B'] . "</td>
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status26!="")?$sss[$previous_review->skills_scan_status26]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status26", $ss_statuses, $form_arf->skills_scan_status26, true, false, $assessor_signed) . "</td>
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
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status27!="")?$sss[$previous_review->skills_scan_status27]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status27", $ss_statuses, $form_arf->skills_scan_status27, true, false, $assessor_signed) . "</td>
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
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status28!="")?$sss[$previous_review->skills_scan_status28]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status28", $ss_statuses, $form_arf->skills_scan_status28, true, false, $assessor_signed) . "</td>
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
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status29!="")?$sss[$previous_review->skills_scan_status29]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status29", $ss_statuses, $form_arf->skills_scan_status29, true, false, $assessor_signed) . "</td>
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
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status30!="")?$sss[$previous_review->skills_scan_status30]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status30", $ss_statuses, $form_arf->skills_scan_status30, true, false, $assessor_signed) . "</td>
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
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status31!="")?$sss[$previous_review->skills_scan_status31]:"") . "</td>
           <td rowspan=1 style='text-align:center; vertical-align:middle'>" . HTML::select("skills_scan_status31", $ss_statuses, $form_arf->skills_scan_status31, true, false, $assessor_signed) . "</td>
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
           <td rowspan=1 style='text-align:center'>" . (($previous_review->skills_scan_status32!="")?$sss[$previous_review->skills_scan_status32]:"") . "</td>
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
   </table>";
        return $html;


    }


public static function EvidenceMatrix($link, $tr_id, $course_id, $form_arf)
{

    $html = "<table class=\"table1\" style=\"width: 900px\">
    <thead>
    <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary - Projects</th></tr>
    </thead>
    <tbody>
    <tr><td colspan=2>Project</td><td>Status</td></tr>";

    $projects = DAO::getResultset($link, "SELECT
                            tr_projects.id
                            ,evidence_project.project
                            ,(SELECT (LENGTH(matrix)-LENGTH(REPLACE(matrix,\",\",\"\"))+1) FROM project_submissions WHERE project_submissions.completion_date IS NOT NULL AND project_submissions.project_id = tr_projects.id ORDER BY project_submissions.id DESC LIMIT 1) AS matrix
                            ,(SELECT COUNT(*) FROM evidence_criteria WHERE evidence_criteria.course_id = evidence_project.course_id) AS total
                            FROM
                            tr_projects
                            INNER JOIN evidence_project ON tr_projects.project = evidence_project.id
                            WHERE tr_id = '$tr_id';
                            ", DAO::FETCH_ASSOC);


    $total = 0;
    foreach($projects AS $project)
    {
    $matrix = ($project['matrix']=='')?'0':$project['matrix'];
    $total+=(int)$matrix;
    $html .= '<tr><td colspan=2>' . $project['project'] . '</td><td align=center>' . $matrix . ' / ' . $project['total'] . '</td></tr>';
    }

    if(isset($project['total']))
    {
        $html .= '<tr><td colspan=2 style="background-color: lightgreen">Total</td><td align=center style="background-color: lightgreen">' . $total . ' / ' . $project['total'] . '</td></tr>';
        $per = round($total/$project['total']*100);
    }
    else
    {
        $html .= '<tr><td colspan=2 style="background-color: lightgreen">Total</td><td align=center style="background-color: lightgreen">' . $total . ' / 0' . '</td></tr>';
        $per = round($total/1*100);
    }

    $html .= '<tr><td colspan=2 style="background-color: lightblue">Evidence % </td><td align=center style="background-color: lightblue">' . $per . '%</td></tr>
    </table>
    <br>

    <table class="table1" style="width: 900px">
        <thead>
        <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary - Competence</th></tr>
        </thead><tbody>';

        $projects = DAO::getResultset($link, "SELECT lookup_assessment_plan_log_mode.id
    ,description
    ,(SELECT COUNT(*) FROM evidence_criteria WHERE course_id = '$course_id' AND competency = lookup_assessment_plan_log_mode.id) AS total_criteria
    ,(SELECT COUNT(*) FROM evidence_criteria WHERE competency = lookup_assessment_plan_log_mode.id AND FIND_IN_SET(evidence_criteria.id,(SELECT GROUP_CONCAT(REPLACE(matrix,\" \",\"\")) FROM project_submissions WHERE project_submissions.completion_date IS NOT NULL AND project_id IN (SELECT id FROM tr_projects WHERE tr_id = '$tr_id')))<>0) AS matrix
    ,(SELECT GROUP_CONCAT(criteria) FROM evidence_criteria WHERE competency = lookup_assessment_plan_log_mode.id AND FIND_IN_SET(evidence_criteria.id,(SELECT GROUP_CONCAT(REPLACE(matrix,\" \",\"\")) FROM project_submissions WHERE project_submissions.completion_date IS NOT NULL AND project_id IN (SELECT id FROM tr_projects WHERE tr_id = '$tr_id')))<>0) AS completed
    FROM
    lookup_assessment_plan_log_mode
    INNER JOIN student_frameworks ON student_frameworks.id = lookup_assessment_plan_log_mode.framework_id AND student_frameworks.tr_id = '$tr_id';
    ;
                            ", DAO::FETCH_ASSOC);


        $html .= '<tr><td>Competency</td><td>Completed Criteria</td><td>Status</td></tr>';
        $total = 0;
        $gt=0;
        foreach($projects AS $project)
        {
            $matrix = ($project['matrix']=='')?'0':$project['matrix'];
            $total+=(int)$matrix;
            $gt+=$project['total_criteria'];
            $html .= '<tr><td>' . $project['description'] . '</td>
            <td>'. str_replace(",","<br>",$project['completed']) .'</td>
            <td align=center>' . $matrix . ' / ' . $project['total_criteria'] . '</td></tr>';
        }

        if(isset($project['total_criteria']))
        {
            $html .= '<tr><td style="background-color: lightgreen">Total</td><td style="background-color: lightgreen">&nbsp</td><td align=center style="background-color: lightgreen">' . $total . ' / ' . $gt . '</td></tr>';
            $per = round($total/$gt*100);
        }
        else
        {
            $html .= '<tr><td style="background-color: lightgreen">Total</td><td style="background-color: lightgreen">&nbsp</td><td align=center style="background-color: lightgreen">' . $total . ' / 0' . '</td></tr>';
            $per = round($total/1*100);
        }

        $html .="<tr>
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

    public static function getKnowledgeModuleDigitalMarketerV5($link, $form_arf, $review_id, $tr_id, $source, $ss_result, $tk_result, $ab_result, $Assessment_Plan, $ss_statuses, $events, $previous_review)
    {

            $html = "<table class=\"table1\" style=\"width: 900px\">
            <thead>
            <tr><th colspan=6>&nbsp;&nbsp;&nbsp;Progress Summary: Knowledge Modules</th></tr>
            </thead>
            <tbody><tr><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td><td>Knowledge Module</td><td>Status</td></tr>
            <tr>
                <td>Principles of coding</td>
                <td>" . ReviewSkillsScans::getEventStatus($events, "Principles of coding") . "<br>" . ReviewSkillsScans::getEventDate($events,'Principles of coding') . "</td>
                <td>9828-11 principles of coding test</td>
                <td>" .  ReviewSkillsScans::getEventStatus($events, "9628-11 principles of coding test") . "<br>" . ReviewSkillsScans::getEventDate($events,'9628-11 principles of coding test') . "</td>
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

        return $html;

    }

}
?>