<?php
class view_employers_tna_report implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

		$view = VoltView::getViewFromSession('ViewEmployersTNAReport', 'ViewEmployersTNAReport');
		/* @var $view VoltView */
		//if(is_null($view))
		{
			$view = $_SESSION['ViewEmployersTNAReport'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_employers_tna_report", "View Employers TNA Report");

		if ($subaction == 'export_csv') {
			$this->export_csv($link, $view);
			exit;
		}

		include_once('tpl_view_employers_tna_report.php');
	}

	private function buildView(PDO $link)
	{
		$sql = new SQLStatement("
SELECT
	employer_tna.*,
	organisations.legal_name,
	locations.address_line_1,
	locations.address_line_2,
	locations.address_line_3,
	locations.address_line_4,
	locations.postcode
FROM
	employer_tna
	LEFT JOIN organisations ON employer_tna.employer_id = organisations.id
	LEFT JOIN locations ON (organisations.id = locations.organisations_id AND locations.is_legal_address = '1')
ORDER BY
	employer_tna.completed_date DESC
;
		");

		$view = new VoltView('ViewEmployersTNAReport', $sql->__toString());

		$f = new VoltTextboxViewFilter('filter_legal_name', "WHERE organisations.legal_name LIKE '%%%s%%'", null);
		$f->setDescriptionFormat("Employer: %s");
		$view->addFilter($f);

		$format = "WHERE employer_tna.completed_date >= '%s'";
		$f = new VoltDateViewFilter('from_completed_date', $format, '');
		$f->setDescriptionFormat("From completed date: %s");
		$view->addFilter($f);
		$format = "WHERE employer_tna.completed_date <= '%s'";
		$f = new VoltDateViewFilter('to_completed_date', $format, '');
		$f->setDescriptionFormat("To completed date: %s");
		$view->addFilter($f);

		$options = array(
			0 => array(20, 20, null, null),
			1 => array(50, 50, null, null),
			2 => array(100, 100, null, null),
			3 => array(200, 200, null, null),
			4 => array(300, 300, null, null),
			5 => array(400, 400, null, null),
			6 => array(500, 500, null, null),
			7 => array(0, 'No limit', null, null));
		$f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		return $view;
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

	private function renderView(PDO $link, VoltView $view)
	{

//		pr($view->getSQLStatement()->__toString());
		$st = $link->query($view->getSQLStatement()->__toString());
		if ($st) {
			echo '<div class="box box-info collapsed-box">';
			echo '<div class="box-header with-border">
					<span class="box-title"> <label class="text-center">Questions Descriptions</label></span>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>';
			echo '<div class="box-body table-responsive">';
			echo '<table class="table table-bordered">';
			echo '<tr><td class="small">1. Which apprenticeship/s would you like your employees to undertake?</th></tr>';
			echo '<tr><td class="small">2. What are your strategic goals over the next 12 months and where do the individuals planned for enrolment onto the apprenticeship fit within future plans for personal development?</th></tr>';
			echo '<tr><td class="small">3. Please select up to 5 skills that you feel your employees need to develop, in order to succeed at work</th></tr>';
			echo '<tr><td class="small">4. Are there any other skills, relevant to your industry, that you feel employees may benefit from in order to personally develop and progress in the workplace?</th></tr>';
			echo '<tr><td class="small">5. Are the skills identified in question 3 and 4 entirely new skills or are they areas in which employees can enhance on existing skills?</th></tr>';
			echo '<tr><td class="small">6. What might prevent employees from learning new skills?</th></tr>';
			echo '<tr><td class="small">7. What internal and external obstacles, if any, may affect your apprenticeship training programme?</th></tr>';
			echo '<tr><td class="small">8. How will continuous improvement skills be used on a daily basis by employees?</th></tr>';
			echo '<tr><td class="small">9. Why are these skills valuable to your organisation?</th></tr>';
			echo '<tr><td class="small">10. How do these skills align with your organisations mission and vision?</th></tr>';
			echo '<tr><td class="small">11. How will these skills improve functions across teams and departments?</th></tr>';
			echo '<tr><td class="small">12. Why are you enrolling employees onto this apprenticeship programme?</th></tr>';
			echo '<tr><td class="small">13. Do you have a mental health/healthy living or wellness training agenda currently in place for employees at work?</th></tr>';
			echo '<tr><td class="small">14. Do you have a Prevent, Safeguarding or British Values training agenda currently in place for employees at work?</th></tr>';
			echo '</table> ';
			echo '</div>';
			echo '</div>';
			echo $view->getViewNavigatorExtra('', $view->getViewName());
			echo '<div align="center" ><table id="tblTna" class="table table-bordered">';
			echo '<thead class="bg-gray-light"><tr>';
			echo '<th>Completed Date</th><th>Employer</th><th>Address</th><th>Contact</th>';
			for($i = 1; $i <= 14; $i++)
				echo '<th>Q&nbsp;' . $i . '</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
				echo HTML::viewrow_opening_tag('do.php?_action=view_employer_tna&employer_id=' . $row['employer_id']);
				echo '<td>' . Date::to($row['completed_date'], Date::DATETIME) . '</td>';
				echo '<td>' . str_replace(' ', '&nbsp;', $row['legal_name']) . '</td>';
				echo '<td>';
				echo $row['address_line_1'] != '' ? str_replace(' ', '&nbsp;', $row['address_line_1']) . '<br>' : '';
				echo $row['address_line_2'] != '' ? $row['address_line_2'] . '<br>' : '';
				echo $row['address_line_3'] != '' ? $row['address_line_3'] . '<br>' : '';
				echo $row['address_line_4'] != '' ? $row['address_line_4'] . '<br>' : '';
				echo $row['postcode'] != '' ? $row['postcode'] : '';
				echo '</td>';
				echo '<td>';
				echo $row['contact_name'] != '' ? str_replace(' ', '&nbsp;', $row['contact_name']) . '<br>' : '';
				echo $row['contact_job_role'] != '' ? str_replace(' ', '&nbsp;', $row['contact_job_role']) . '<br>' : '';
				echo $row['contact_telephone'] != '' ? $row['contact_telephone'] : '';
				echo '</td>';
				echo $row['q1'] != '' ? '<td>' . '-&nbsp;' . implode("<br>- ", $this->getDescriptions($this->listApprenticeships, $row['q1'], true)) . '</td>' : '<td></td>';
				echo '<td>' . $this->replaceFewSpaces($row['q2']) . '</td>';
				echo '<td>';
				echo $row['q3'] != '' ? '-&nbsp;' . implode("<br>- ", $this->getDescriptions($this->listSkills, $row['q3'], true)) : '';
				echo $row['q3_other'] != '' ? '<br>' . $row['q3_other'] : '';
				echo '</td>';
				echo '<td>' . $this->replaceFewSpaces($row['q4']) . '</td>';
				echo isset($this->ddlExistingSkills[$row['q5']]) ? '<td>' . $this->ddlExistingSkills[$row['q5']] . '</td>' : '<td>' . $row['q5'] . '</td>';
				echo '<td>';
				echo $row['q6'] != '' ? '-&nbsp;' . implode("<br>- ", $this->getDescriptions($this->listReasonOfPrevention, $row['q6'], true)) : '';
				echo $row['q6_other'] != '' ? '<br>' . $row['q6_other'] : '';
				echo '</td>';
				echo '<td>' . $this->replaceFewSpaces($row['q7']) . '</td>';
				echo '<td>';
				echo $row['q8'] != '' ? '-&nbsp;' . implode("<br>- ", $this->getDescriptions($this->useOfSkills, $row['q8'], true)) : '';
				echo $row['q8_other'] != '' ? '<br>' . $row['q8_other'] : '';
				echo '</td>';
				echo '<td>' . $this->replaceFewSpaces($row['q9']) . '</td>';
				echo '<td>' . $this->replaceFewSpaces($row['q10']) . '</td>';
				echo '<td>';
				echo $row['q11'] != '' ? '-&nbsp;' . implode("<br>- ", $this->getDescriptions($this->benefitsOfImprovement, $row['q11'], true)) : '';
				echo $row['q11_other'] != '' ? '<br>' . $row['q11_other'] : '';
				echo '</td>';
				echo '<td>' . $this->replaceFewSpaces($row['q12']) . '</td>';
				echo '<td>';
				echo $row['q13'] != '' ? '-&nbsp;' . implode("<br>- ", $this->getDescriptions($this->listHealthAgenda, $row['q13'], true)) : '';
				echo '</td>';
				echo '<td>';
				echo $row['q14'] != '' ? '-&nbsp;' . implode("<br>- ", $this->getDescriptions($this->listOtherAgenda, $row['q14'], true)) : '';
				echo '</td>';


				echo '</tr>';
			}
			echo '</tbody></table></div><p><br></p>';
			echo $view->getViewNavigatorExtra('', $view->getViewName());
		} else {
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}

	private function export_csv(PDO $link, VoltView $view)
	{
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if ($st) {

			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=Employers TNA Report.csv');
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}

			echo "1. Which apprenticeship/s would you like your employees to undertake?\n";
			echo "2. What are your strategic goals over the next 12 months and where do the individuals planned for enrolment onto the apprenticeship fit within future plans for personal development?\n";
			echo "3. Please select up to 5 skills that you feel your employees need to develop in order to succeed at work\n";
			echo "4. Are there any other skills relevant to your industry that you feel employees may benefit from in order to personally develop and progress in the workplace?\n";
			echo "5. Are the skills identified in question 3 and 4 entirely new skills or are they areas in which employees can enhance on existing skills?\n";
			echo "6. What might prevent employees from learning new skills?\n";
			echo "7. What internal and external obstacles if any may affect your apprenticeship training programme?\n";
			echo "8. How will continuous improvement skills be used on a daily basis by employees?\n";
			echo "9. Why are these skills valuable to your organisation?\n";
			echo "10. How do these skills align with your organisations mission and vision?\n";
			echo "11. How will these skills improve functions across teams and departments?\n";
			echo "12. Why are you enrolling employees onto this apprenticeship programme?\n";
			echo "13. Do you have a mental health/healthy living or wellness training agenda currently currently in place for employees at work?\n";
			echo "14. Do you have a Prevent Safeguarding or British Values training agenda currently in place for employees at work?\n";
			echo "\n";
			echo 'Completed Date,Employer,Address,Contact Name,Contact Job Role,Contact Telephone,';
			for($i = 1; $i <= 14; $i++)
				echo in_array($i, [3, 6, 8, 11]) ? 'Q' . $i . ',Q' . $i . ' Other,' : 'Q' . $i . ',';
			echo "\n";
			while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
				echo Date::to($row['completed_date'], Date::DATETIME) . ',';
				echo HTML::csvSafe($row['legal_name']) . ',';
				echo $row['address_line_1'] != '' ? HTML::csvSafe($row['address_line_1']) . ' ' : '';
				echo $row['address_line_2'] != '' ? HTML::csvSafe($row['address_line_2']) . ' ' : '';
				echo $row['address_line_3'] != '' ? HTML::csvSafe($row['address_line_3']) . ' ' : '';
				echo $row['address_line_4'] != '' ? HTML::csvSafe($row['address_line_4']) . ' ' : '';
				echo $row['postcode'] != '' ? HTML::csvSafe($row['postcode']) : '';
				echo ',';
				echo HTML::csvSafe($row['contact_name']) . ',';
				echo HTML::csvSafe($row['contact_job_role']) . ',';
				echo HTML::csvSafe($row['contact_telephone']) . ',';
				echo $row['q1'] != '' ? HTML::csvSafe(implode("; ", $this->getDescriptions($this->listApprenticeships, $row['q1'], false))) . ',' : ',';
				echo HTML::csvSafe($row['q2']) . ',';
				echo $row['q3'] != '' ? HTML::csvSafe(implode("; ", $this->getDescriptions($this->listSkills, $row['q3'], false))) . ',' : ',';
				echo HTML::csvSafe($row['q3_other']) . ',';
				echo HTML::csvSafe($row['q4']) . ',';
				echo isset($this->ddlExistingSkills[$row['q5']]) ? $this->ddlExistingSkills[$row['q5']] . ',' : ',';
				echo $row['q6'] != '' ? HTML::csvSafe(implode("; ", $this->getDescriptions($this->listReasonOfPrevention, $row['q6'], false))) . ',' : ',';
				echo HTML::csvSafe($row['q6_other']) . ',';
				echo HTML::csvSafe($row['q7']) . ',';
				echo $row['q8'] != '' ? HTML::csvSafe(implode("; ", $this->getDescriptions($this->useOfSkills, $row['q8'], false))) . ',' : ',';
				echo HTML::csvSafe($row['q8_other']) . ',';
				echo HTML::csvSafe($row['q9']) . ',';
				echo HTML::csvSafe($row['q10']) . ',';
				echo $row['q11'] != '' ? HTML::csvSafe(implode("; ", $this->getDescriptions($this->benefitsOfImprovement, $row['q11'], false))) . ',' : ',';
				echo HTML::csvSafe($row['q11_other']) . ',';
				echo $row['q12'] . ',';
				echo $row['q13'] != '' ? HTML::csvSafe(implode("; ", $this->getDescriptions($this->listHealthAgenda, $row['q13'], false))) . ',' : ',';
				echo $row['q14'] != '' ? HTML::csvSafe(implode("; ", $this->getDescriptions($this->listOtherAgenda, $row['q14'], false))) : '';

				echo "\n";
			}
		} else {
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}

	private function replaceFewSpaces($string)
	{
		$output = '';
		$string = explode(' ', $string);
		$cnt = 0;
		foreach($string AS $word)
		{
			if(++$cnt < 6)
			{
				$output .= $word . '&nbsp;';
			}
			else
			{
				$output .= $word . ' ';
			}

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
		14 => "Data analysis and planning] skills",
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