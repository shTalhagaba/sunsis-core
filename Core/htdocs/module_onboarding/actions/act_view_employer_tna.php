<?php
class view_employer_tna implements IAction
{
	public function execute(PDO $link)
	{
		$employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id'] : '';
		if($employer_id == '')
			throw new Exception("Missing querystring argument: employer_id");

		$employer = Employer::loadFromDatabase($link, $employer_id);
		if(is_null($employer))
			throw new Exception("Invalid employer_id");
		$location_id = DAO::getSingleValue($link, "SELECT id FROM locations WHERE locations.organisations_id = '{$employer->id}' AND is_legal_address = '1'");
		$location = Location::loadFromDatabase($link, $location_id);

		$_SESSION['bc']->add($link, "do.php?_action=view_employer_tna&employer_id={$employer->id}", "View Employer TNA");

		$tna = DAO::getObject($link, "SELECT * FROM employer_tna WHERE employer_id = '{$employer->id}'");
		if(!isset($tna->employer_id))
		{
			$tna = new stdClass();
			$records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM employer_tna");
			foreach($records AS $_key => $value)
				$tna->$value = null;
			$tna->employer_id = $employer->id;
		}

        $listApprenticeships = [
			[1, "Level 2 Lean Manufacturing Operative Standard"],
			[3, "Level 3 Improvement Technician Standard"],
			[4, "Level 4 Improvement Practitioner Standard"],
		];

		$listSkills = [
			[1, "Health and safety"],
			[2, "Computer/digital skills (office apps e.g. word, excel)"],
			[3, "Maths skills"],
			[4, "Communication skills"],
			[5, "English Skills"],
			[6, "Problem solving and analytical skills"],
			[7, "Presentation skills"],
			[8, "Change management skills"],
			[9, "Managing conflict skills (people management)"],
			[10, "Coaching and mentoring skills"],
			[11, "Business reporting skills"],
			[12, "Project management skills"],
			[13, "Strategic planning skills"],
			[14, "Data analysis and planning] skills"],
			[15, "Collaboration and team building skills"],
			[16, "Management/Leadership skills"],
			[17, "Time management skills"],
			[18, "quality and diversity awareness"],
			[19, "Process and procedure development"],
			[20, "Mental health awareness"],
			[21, "Other (please state)"],
		];

		$ddlExistingSkills = [
			[1, "New Skills"],
			[2, "Existing skills employees can enhance"],
			[3, "Mixture of both"]
		];

		$listReasonOfPrevention = [
			[1, "Time and business demands"],
			[2, "Work-based culture of learning"],
			[3, "Resistance to change"],
			[4, "Management Commitment"],
			[5, "Misconception of ability or willingness to learn"],
			[6, "Remote working and availability"],
			[7, "None"],
			[8, "Other (please state)"],
		];

		$useOfSkills = [
			[1, "Taking on additional responsibilities"],
			[2, "Communicating effectively to all departments"],
			[3, "Effective team-working"],
			[4, "Maintaining organised/efficient work areas"],
			[5, "Confidence in identifying problems and agreeing solutions"],
			[6, "Following structured problem solving methodology"],
			[7, "Collating and understanding data that feeds into improvements"],
			[8, "Helping others when asked"],
			[9, "Acting on feedback and reflecting appropriately on own performance"],
			[10, "Using maths skills to create data driven improvements"],
			[11, "Using English skills to generate effective reports and clear legible communications"],
			[12, "Coaching others in effective problem solving techniques"],
			[13, "Other (please state)"],
		];

		$benefitsOfImprovement = [
			[1, "Better communication processes"],
			[2, "Cross-functional effective team working"],
			[3, "Improved understanding of business and improvement areas"],
			[4, "Focused problem solving to drive results"],
			[5, "Change management/effective problem solving culture"],
			[6, "Other (please state)"],
		];

		$listHealthAgenda = [
			[1, "Mental health"],
			[2, "Healthy living"],
			[3, "Wellness"],
			[4, "All of the above"],
			[5, "None of the above"],
		];

		$listOtherAgenda = [
			[1, "Prevent"],
			[2, "Safeguarding"],
			[3, "British Values"],
			[4, "All of the above"],
			[5, "None of the above"],
		];

        if(isset($_REQUEST['subaction']) && $_REQUEST['subaction'] == 'export')
        {
            $details = [
                'employer' => $employer,
                'location' => $location,
                'tna' => $tna,
            ];
            $this->export_to_pdf($link, $details);
            exit;
        }

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");
        $client_name = SystemConfig::getEntityValue($link, "client_name");

		include_once('tpl_view_employer_tna.php');
	}

    public function export_to_pdf(PDO $link, $details = [])
    {
	$header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        include_once("./MPDF57/mpdf.php");

        $mpdf=new mPDF('','Legal','10');

        $mpdf->setAutoBottomMargin = 'stretch';

        $details = (object)$details;
        $employer = $details->employer;
        $location = $details->location;
        $tna = $details->tna;

        $sunesis_stamp = md5('ghost'.date('d/m/Y').$employer->id);
        $sunesis_stamp = substr($sunesis_stamp, 0, 10);
        $date = date('d/m/Y H:i:s');
        $footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "50%" align="left">{$date}</td>
					<td width = "50%" align="right">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

        //Beginning Buffer to save PHP variables and HTML tags
        ob_start();

        $location_address = $location->full_name != '' ? $location->full_name . '<br>' : '';
        $location_address .= $location->address_line_1 != '' ? $location->address_line_1 . '<br>' : '';
        $location_address .= $location->address_line_2 != '' ? $location->address_line_2 . '<br>' : '';
        $location_address .= $location->address_line_3 != '' ? $location->address_line_3 . '<br>' : '';
        $location_address .= $location->address_line_4 != '' ? $location->address_line_4 . '<br>' : '';
        $location_address .= $location->postcode != '' ? $location->postcode . '<br>' : '';
        $location_address .= $location->telephone != '' ? $location->telephone . '<br>' : '';


        echo <<<HTML

<table style="width: 100%;">
    <tr>
        <td style="width: 40%;">
            <img width="110px" height="90px" src="{$header_image1}" alt="LEAD">
        </td>
        <td style="width: 60%;" align="left">
            <h3>Employer Training Needs Analysis</h3>
        </td>
    </tr>
</table>

<table style="width: 100%;" border="1">
    <col width="30%">
    <tr><th>Employer Name:</th><td>{$employer->legal_name}</td></tr>
    <tr><th>Employer Address:</th><td>{$location_address}</td></tr>
    <tr><th>Contact Name:</th><td>{$tna->contact_name}</td></tr>
    <tr><th>Job Role:</th><td>{$tna->contact_job_role}</td></tr>
    <tr><th>Contact Telephone Number:</th><td>{$tna->contact_telephone}</td></tr>
</table>

HTML;

        echo '<table style="width: 100%;" border="1" cellpadding="6">';

        echo '<tr><td><strong>1. Which apprenticeship/s would you like your employees to undertake?</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo implode("; ", $this->getDescriptions($this->listApprenticeships, $tna->q1));
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>2. What are your strategic goals over the next 12 months and where do the individuals planned for enrolment onto the apprenticeship fit within future plans for personal development?</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo $tna->q2;
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>3. Please select up to 5 skills that you feel your employees need to develop, in order to succeed at work</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo implode("<br>", $this->getDescriptions($this->listSkills, $tna->q3));
        echo $tna->q3_other != '' ? '<br><p>' . $tna->q3_other . '</p>' : '';
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>4. Are there any other skills, relevant to your industry, that you feel employees may benefit from in order to personally develop and progress in the workplace?</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo $tna->q4;
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>5. Are the skills identified in question 3 and 4 entirely new skills or are they areas in which employees can enhance on existing skills?</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo isset($this->ddlExistingSkills[$tna->q5]) ? $this->ddlExistingSkills[$tna->q5] : '';
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>6. What might prevent employees from learning new skills? Please select all that apply</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo implode("<br>", $this->getDescriptions($this->listReasonOfPrevention, $tna->q6));
        echo $tna->q6_other != '' ? '<br><p>' . $tna->q6_other . '</p>' : '';
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>7. What internal and external obstacles, if any, may affect your apprenticeship training programme?</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo $tna->q7;
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>8. How will continuous improvement skills be used on a daily basis by employees? Please select all that apply</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo implode("<br>", $this->getDescriptions($this->useOfSkills, $tna->q8));
        echo $tna->q8_other != '' ? '<br><p>' . $tna->q8_other . '</p>' : '';
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>9. Why are these skills valuable to your organisation?</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo $tna->q9;
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>10. How do these skills align with your organisations mission and vision?</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo $tna->q10;
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>11. How will these skills improve functions across teams and departments? Please select all that apply</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo implode("<br>", $this->getDescriptions($this->benefitsOfImprovement, $tna->q11));
        echo $tna->q11_other != '' ? '<br><p>' . $tna->q11_other . '</p>' : '';
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>12. Why are you enrolling employees onto this apprenticeship programme?</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo $tna->q12;
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>13. Do you have a mental health/healthy living or wellness training agenda currently in place for employees at work?</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo implode("<br>", $this->getDescriptions($this->listHealthAgenda, $tna->q13));
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><strong>14. Do you have a Prevent, Safeguarding or British Values training agenda currently in place for employees at work?</strong></td></tr>';
        echo '<tr>';
        echo '<td>';
        echo implode("<br>", $this->getDescriptions($this->listOtherAgenda, $tna->q14));
        echo '</td>';
        echo '</tr>';

        echo '</table>';


        $html = ob_get_contents();

        $mpdf->SetHTMLFooter($footer);
        ob_end_clean();

        $mpdf->WriteHTML($html);

//		$mpdf->Output('asd', 'I');
        $mpdf->Output('Employer TNA Form.pdf', 'D');
    }

    private function getDescriptions($lookup, $keys, $nbsp = false)
    {
        if (!is_array($keys))
            $keys = explode(",", $keys);

        $output = [];
        foreach ($lookup AS $key => $value) {
            if (in_array($key, $keys))
                $output[] = $nbsp ? str_replace(" ", "&nbsp;", $value) : $value;
        }

        return $output;
    }

    private $listApprenticeships = [
        1 => "Level 2 Lean Manufacturing Operative Standard",
        2 => "Level 2 Improving Operational Performance Framework",
        3 => "Level 3 Improvement Technician Standard",
        4 => "Level 4 Improvement Practitioner Standard",
    ];

    private $listSkills = [
        1 => "Health and safety",
        2 => "Computer/digital skills (office apps e.g. word, excel)",
        3 => "Maths skills",
        4 => "Communication skills",
        5 => "English Skills",
        6 => "Problem solving and analytical skills",
        7 => "Presentation skills",
        8 => "Change management skills",
        9 => "Managing conflict skills (people management)",
        10 => "Coaching and mentoring skills",
        11 => "Business reporting skills",
        12 => "Project management skills",
        13 => "Strategic planning skills",
        14 => "Data analysis and planning skills",
        15 => "Collaboration and team building skills",
        16 => "Management/Leadership skills",
        17 => "Time management skills",
        18 => "quality and diversity awareness",
        19 => "Process and procedure development",
        20 => "Mental health awareness",
        21 => "Other (please state)",
    ];

    private $ddlExistingSkills = [
        1 => "New Skills",
        2 => "Existing skills employees can enhance",
        3 => "Mixture of both"
    ];

    private $listReasonOfPrevention = [
        1 => "Time and business demands",
        2 => "Work-based culture of learning",
        3 => "Resistance to change",
        4 => "Management Commitment",
        5 => "Misconception of ability or willingness to learn",
        6 => "Remote working and availability",
        7 => "None",
        8 => "Other (please state)",
    ];

    private $useOfSkills = [
        1 => "Taking on additional responsibilities",
        2 => "Communicating effectively to all departments",
        3 => "Effective team-working",
        4 => "Maintaining organised/efficient work areas",
        5 => "Confidence in identifying problems and agreeing solutions",
        6 => "Following structured problem solving methodology",
        7 => "Collating and understanding data that feeds into improvements",
        8 => "Helping others when asked",
        9 => "Acting on feedback and reflecting appropriately on own performance",
        10 => "Using maths skills to create data driven improvements",
        11 => "Using English skills to generate effective reports and clear legible communications",
        12 => "Coaching others in effective problem solving techniques",
        13 => "Other (please state)",
    ];

    private $benefitsOfImprovement = [
        1 => "Better communication processes",
        2 => "Cross-functional effective team working",
        3 => "Improved understanding of business and improvement areas",
        4 => "Focused problem solving to drive results",
        5 => "Change management/effective problem solving culture",
        6 => "Other (please state)",
    ];

    private $listHealthAgenda = [
        1 => "Mental health",
        2 => "Healthy living",
        3 => "Wellness",
        4 => "All of the above",
        5 => "None of the above",
    ];

    private $listOtherAgenda = [
        1 => "Prevent",
        2 => "Safeguarding",
        3 => "British Values",
        4 => "All of the above",
        5 => "None of the above",
    ];

}