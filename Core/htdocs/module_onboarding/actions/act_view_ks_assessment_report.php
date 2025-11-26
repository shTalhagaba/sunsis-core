<?php

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class view_ks_assessment_report implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

		$view = VoltView::getViewFromSession('ViewKsAssessmentReport', 'ViewKsAssessmentReport');
		/* @var $view VoltView */
		if (is_null($view)) {
			$view = $_SESSION['ViewKsAssessmentReport'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_ks_assessment_report", "View KS Assessment Report");

		if ($subaction == 'export_csv') {
			$this->export_csv($link, $view);
			exit;
		}

		include_once('tpl_view_ks_assessment_report.php');
	}

	private function buildView(PDO $link)
	{
		$sql = new SQLStatement("
SELECT
	ob_learners.id AS learner_id,
	ob_learners.employer_id,
	ob_learners.employer_location_id,
	ob_learners.ks_assessment,
	ob_learners.firstnames,
	ob_learners.surname,
	organisations.legal_name,
	ks_assessment.*
FROM
	ob_learners
	LEFT JOIN organisations ON ob_learners.employer_id = organisations.id
	LEFT JOIN ks_assessment ON (ob_learners.id = ks_assessment.ob_learner_id AND ob_learners.ks_assessment = ks_assessment.assessment_type)
ORDER BY
	ob_learners.firstnames
;
		");

		$view = new VoltView('ViewKsAssessmentReport', $sql->__toString());

		$f = new VoltTextboxViewFilter('filter_legal_name', "WHERE organisations.legal_name LIKE '%%%s%%'", null);
		$f->setDescriptionFormat("Employer: %s");
		$view->addFilter($f);

		$options = [
			0 => ['l2iop', 'Level 2 Improving Operational Performance', null, 'WHERE ob_learners.ks_assessment = "l2iop"'],
			1 => ['l3it', 'Level 3 Improvement Technician', null, 'WHERE ob_learners.ks_assessment = "l3it"'],
			2 => ['l4ip', 'Level 4 Improvement Practitioner', null, 'WHERE ob_learners.ks_assessment = "l4ip"'],
			3 => ['lmo', 'Lean Manufacturing Operative', null, 'WHERE ob_learners.ks_assessment = "lmo"'],
		];
		$f = new VoltDropDownViewFilter('filter_assessment', $options, 'l2iop', false);
		$f->setDescriptionFormat("Assessment: %s");
		$view->addFilter($f);

		$options = array(
			0 => array(20, 20, null, null),
			1 => array(50, 50, null, null),
			2 => array(100, 100, null, null),
			3 => array(200, 200, null, null),
			4 => array(300, 300, null, null),
			5 => array(400, 400, null, null),
			6 => array(500, 500, null, null),
			7 => array(0, 'No limit', null, null)
		);
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
		foreach ($lookup as $key => $value) {
			if (in_array($key, $keys))
				$output[] = $nbsp ? str_replace(" ", "&nbsp;", $value) : $value;
		}

		return $output;
	}

	private function replaceFewSpaces($string)
	{
		$output = '';
		$string = explode(' ', $string);
		$cnt = 0;
		foreach ($string as $word) {
			if (++$cnt < 6) {
				$output .= $word . '&nbsp;';
			} else {
				$output .= $word . ' ';
			}
		}
		return $output;
	}

	private function renderView(PDO $link, VoltView $view)
	{
		$total_score_data = [
			"0-50" => 0,
			"51-70" => 0,
			"71-90" => 0,
			"91-100" => 0,
			"100+" => 0,
		];

		$assessment_type = $view->getFilterValue('filter_assessment');
		$listAssessmentDescriptions = OnboardingHelper::getAssessmentTypesList();
		$assessment_desc = isset($listAssessmentDescriptions[$assessment_type]) ? $listAssessmentDescriptions[$assessment_type] : $assessment_type;

		$questions_k = OnboardingHelper::getQuestions($link, $assessment_type, 'k');
		$questions_s = OnboardingHelper::getQuestions($link, $assessment_type, 's');
		$questions_p = OnboardingHelper::getQuestions($link, $assessment_type, 'p');

		$knowledge_table_header = '<thead><tr>';
		$knowledge_table_header .= '<th>Learner Name</th><th>Employer</th><th>Score</th><th class="small">Answered 3 or 4</th><th class="small">% Answered 3 or 4</th>';
		foreach ($questions_k as $key => $value)
			$knowledge_table_header .= '<th class="small text-center">' . $this->replaceFewSpaces($value) . '</th>';
		$knowledge_table_header .= '</tr></thead>';

		$skills_table_header = '<thead><tr>';
		$skills_table_header .= '<th>Learner Name</th><th>Employer</th><th>Score</th><th class="small">Answered 2 or 3</th><th class="small">% Answered 2 or 3</th>';
		foreach ($questions_s as $key => $value)
			$skills_table_header .= '<th class="small text-center">' . $this->replaceFewSpaces($value) . '</th>';
		$skills_table_header .= '</tr></thead>';

		$production_table_header = '<thead><tr>';
		$production_table_header .= '<th>Learner Name</th><th>Employer</th><th>Job Role</th><th>Job Title</th><th>Score</th><th class="small">Answered 3 or 4</th><th class="small">% Answered 3 or 4</th>';
		foreach ($questions_p as $key => $value)
			$production_table_header .= '<th class="small text-center">' . $this->replaceFewSpaces($value) . '</th>';
		$production_table_header .= '</tr></thead>';

		$knowledge_table_body = '<tbody>';
		$skills_table_body = '<tbody>';
		$production_table_body = '<tbody>';

		$listKnowledgeOptions = OnboardingHelper::getKnowledgeAnswersList();
		$listSkillsOptions = OnboardingHelper::getSkillsAnswersList();

		$st = $link->query($view->getSQLStatement()->__toString());
		if ($st) {
			while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
				$k_assessment = (array)json_decode($row['k_qs']);
				$s_assessment = (array)json_decode($row['s_qs']);
				$p_assessment = (array)json_decode($row['p_qs']);

				$k_stats = OnboardingHelper::calculateKS('k', $k_assessment);
				$s_stats = OnboardingHelper::calculateKS('s', $s_assessment);
				$p_stats = OnboardingHelper::calculateKS('p', $p_assessment);

				$total_score = $k_stats->total_score + $s_stats->total_score + $p_stats->total_score;
				if ($total_score >= 0 && $total_score <= 50)
					$total_score_data["0-50"]++;
				elseif ($total_score >= 51 && $total_score <= 70)
					$total_score_data["51-70"]++;
				elseif ($total_score >= 71 && $total_score <= 90)
					$total_score_data["71-90"]++;
				elseif ($total_score >= 91 && $total_score <= 100)
					$total_score_data["91-100"]++;
				elseif ($total_score >= 100)
					$total_score_data["100+"]++;

				$knowledge_table_body .= '<tr>';
				$knowledge_table_body .= '<td>' . $row['firstnames'] . '&nbsp;' . $row['surname'] . '</td>';
				$knowledge_table_body .= '<td>' . str_replace(' ', '&nbsp;', $row['legal_name']) . '</td>';
				$knowledge_table_body .= '<td>' . $k_stats->score . '/' . $k_stats->total_score . '</td>';
				$knowledge_table_body .= '<td>' . $k_stats->t_3_or_4 . '</td>';
				$knowledge_table_body .= '<td>' . $k_stats->percentage_3_or_4 . '%</td>';

				foreach ($questions_k as $id => $desc) {
					$q_id = 'q' . $id;
					$knowledge_table_body .= '<td>';

					if (isset($k_assessment[$q_id])) {
						$knowledge_table_body .= isset($listKnowledgeOptions[$k_assessment[$q_id]]) ?
							$k_assessment[$q_id] . '.&nbsp;' . str_replace(' ', '&nbsp;', $listKnowledgeOptions[$k_assessment[$q_id]]) :
							$k_assessment[$q_id];
					}
					$knowledge_table_body .= '</td>';
				}
				$knowledge_table_body .= '</tr>';

				$skills_table_body .= '<tr>';
				$skills_table_body .= '<td>' . $row['firstnames'] . '&nbsp;' . $row['surname'] . '</td>';
				$skills_table_body .= '<td>' . str_replace(' ', '&nbsp;', $row['legal_name']) . '</td>';
				$skills_table_body .= '<td>' . $s_stats->score . '/' . $s_stats->total_score . '</td>';
				$skills_table_body .= '<td>' . $s_stats->t_2_or_3 . '</td>';
				$skills_table_body .= '<td>' . $s_stats->percentage_2_or_3 . '%</td>';

				foreach ($questions_s as $id => $desc) {
					$q_id = 'q' . $id;
					$skills_table_body .= '<td>';

					if (isset($s_assessment[$q_id])) {
						$skills_table_body .= isset($listSkillsOptions[$s_assessment[$q_id]]) ?
							$s_assessment[$q_id] . '.&nbsp;' . str_replace(' ', '&nbsp;', $listSkillsOptions[$s_assessment[$q_id]]) :
							$s_assessment[$q_id];
					}
					$skills_table_body .= '</td>';
				}
				$skills_table_body .= '</tr>';

				$production_table_body .= '<tr>';
				$production_table_body .= '<td>' . $row['firstnames'] . '&nbsp;' . $row['surname'] . '</td>';
				$production_table_body .= '<td>' . str_replace(' ', '&nbsp;', $row['legal_name']) . '</td>';
				$job_role = isset($this->job_roles[$row['your_role']]) ? $this->job_roles[$row['your_role']] : $row['your_role'];
				$production_table_body .= '<td>' . $job_role . '</td>';
				$production_table_body .= '<td>' . $row['job_title'] . '</td>';
				$production_table_body .= '<td>' . $p_stats->score . '/' . $p_stats->total_score . '</td>';
				$production_table_body .= '<td>' . $p_stats->t_3_or_4 . '</td>';
				$production_table_body .= '<td>' . $p_stats->percentage_3_or_4 . '%</td>';

				foreach ($questions_p as $id => $desc) {
					$q_id = 'q' . $id;
					$production_table_body .= '<td>';

					if (isset($p_assessment[$q_id])) {
						$production_table_body .= isset($listKnowledgeOptions[$p_assessment[$q_id]]) ?
							$p_assessment[$q_id] . '.&nbsp;' . str_replace(' ', '&nbsp;', $listKnowledgeOptions[$p_assessment[$q_id]]) :
							$p_assessment[$q_id];
					}
					$production_table_body .= '</td>';
				}
				$production_table_body .= '</tr>';
			}
		} else {
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}

		$knowledge_table_body .= '</tbody>';
		$skills_table_body .= '</tbody>';
		$production_table_body .= '</tbody>';

		$this->score_graph = $this->generate_graph($total_score_data);

		$tabs = <<<HTML
<h4 class="lead text-bold text-center">
	$assessment_desc
	<span class="btn btn-sm btn-info" onclick="showScoreGraph();"><i class="fa fa-bar-chart"></i></span>
</h4>
<div class="nav-tabs-custom bg-gray-light">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_k" data-toggle="tab">Knowledge</a></li>
		<li><a href="#tab_s" data-toggle="tab">Skills</a></li>
		<li><a href="#tab_p" data-toggle="tab">Production Processing</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_k">
		<span class="btn btn-sm btn-info fa fa-bar-chart" onclick="window.location.href='do.php?_action=view_ks_assessment_report_graph&type=$assessment_type&q=k'" title="Graphical View"></span>
			<div class="row">
				<div class="col-sm-4">
					<table class="small table table-bordered">
						<caption class="text-bold">Knowledge Key</caption>
						<tr><th>No Understanding</th><td>0</td></tr>
						<tr><th>Basic Understanding</th><td>1</td></tr>
						<tr><th>Good Understanding</th><td>2</td></tr>
						<tr><th>Proficient</th><td>3</td></tr>
						<tr><th>Expert</th><td>4</td></tr>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<table class="table table-bordered table-striped">
						$knowledge_table_header
						$knowledge_table_body
					</table>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="tab_s">
		<span class="btn btn-sm btn-info fa fa-bar-chart" onclick="window.location.href='do.php?_action=view_ks_assessment_report_graph&type=$assessment_type&q=s'" title="Graphical View"></span>
			<div class="row">
				<div class="col-sm-4">
					<table class="small table table-bordered">
						<caption class="text-bold">Skills Key</caption>
						<tr><th>No Experience</th><td>0</td></tr>
						<tr><th>Some Experience</th><td>1</td></tr>
						<tr><th>Extensive Experience</th><td>2</td></tr>
						<tr><th>Expert</th><td>3</td></tr>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<table class="table table-bordered table-striped">
						$skills_table_header
						$skills_table_body
					</table>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="tab_p">
		<span class="btn btn-sm btn-info fa fa-bar-chart" onclick="window.location.href='do.php?_action=view_ks_assessment_report_graph&type=$assessment_type&q=p'" title="Graphical View"></span>
			<div class="row">
				<div class="col-sm-4">
					<table class="small table table-bordered">
						<caption class="text-bold">Production Processing Key</caption>
						<tr><th>No Understanding</th><td>0</td></tr>
						<tr><th>Basic Understanding</th><td>1</td></tr>
						<tr><th>Good Understanding</th><td>2</td></tr>
						<tr><th>Proficient</th><td>3</td></tr>
						<tr><th>Expert</th><td>4</td></tr>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<table class="table table-bordered table-striped">
						$production_table_header
						$production_table_body
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
HTML;


		echo $tabs;
	}

	public function replaceStringWithNewLines($string)
	{
		$string = explode(' ', $string);
		$output = $string[0] . ' ';
		for ($i = 1; $i < count($string); $i++) {
			if ($i % 6 == 0) {
				$output .= $string[$i] . '\n';
			} else {
				$output .= $string[$i] . ' ';
			}
		}

		return $output;
	}

	private function export_csv(PDO $link, VoltView $view)
	{


		/** Error reporting */
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');

		$objSpreadsheet = new Spreadsheet();

		$objSpreadsheet->getProperties()->setCreator("Sunesis")
			->setLastModifiedBy($_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname)
			->setTitle("KSAssessment")
			->setSubject("KSAssessment")
			->setDescription("KSAssessment")
			->setKeywords("office 2007 openxml php")
			->setCategory("KSAssessment");

		$assessment_type = $view->getFilterValue('filter_assessment');
		$sheet_names = [
			'l2iop' => 'L2 IOP',
			'l3it' => 'L3 Technician',
			'l4ip' => 'L4 Practitioner',
			'lmo' => 'LMO',
		];
		$sheet_name = isset($sheet_names[$assessment_type]) ? $sheet_names[$assessment_type] : $assessment_type;

		$listAssessmentDescriptions = OnboardingHelper::getAssessmentTypesList();
		$sheet_heading = isset($listAssessmentDescriptions[$assessment_type]) ? $listAssessmentDescriptions[$assessment_type] : $assessment_type;

		$questions_k = OnboardingHelper::getQuestions($link, $assessment_type, 'k');
		$questions_s = OnboardingHelper::getQuestions($link, $assessment_type, 's');
		$questions_p = OnboardingHelper::getQuestions($link, $assessment_type, 'p');

		// Knowledge Element Sheet
		$objSpreadsheet->createSheet();
		$k_sheet = $objSpreadsheet->setActiveSheetIndex(0);
		$k_sheet->setTitle($sheet_name . ' Knowledge');
		$k_sheet->setCellValue('A1', $sheet_heading . ' - Knowledge');
		$k_sheet->mergeCells("A1:F1");
		$k_sheet->getStyle("A1:F1")
			->applyFromArray(
				array(
					'fill' => array(
						'type' => Fill::FILL_SOLID,
						'color' => array('rgb' => '000000'),
					),
					'font' => array(
						'size'  => 14,
						'bold'  => true,
						'color' => array('rgb' => 'FF4500'),
					)
				)
			);
		$k_sheet->setCellValue('A2', 'Knowledge Key:');
		$k_sheet->mergeCells("A2:B2");
		$k_sheet->getStyle('A2:B2')->applyFromArray(
			array(
				'fill' => array(
					'type' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'E8E8E8'),
				),
				'font' => array(
					'bold'  => true,
				)
			)
		);
		$k_sheet->setCellValue('C2', '0 = No Understanding');
		$k_sheet->setCellValue('D2', '1 = Basic Understanding');
		$k_sheet->setCellValue('E2', '2 = Good Understanding');
		$k_sheet->setCellValue('F2', '3 = Proficient');
		$k_sheet->setCellValue('G2', '4 = Expert');
		foreach (range('A', 'F') as $columnID) {
			$k_sheet->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		$k_sheet->setCellValue('A4', 'Learner Name');
		$k_sheet->setCellValue('B4', 'Employer');
		$k_sheet->setCellValue('C4', 'Score');
		$k_sheet->setCellValue('D4', 'Total Score');
		$k_sheet->setCellValue('E4', 'Answered 3 or 4');
		$k_sheet->setCellValue('F4', '% Answered 3 or 4');
		$row = 4;
		$col = 5;
		foreach ($questions_k as $key => $value) {
			$k_sheet->setCellValue(Coordinate::stringFromColumnIndex(++$col) . $row, $value);
			$k_sheet->getStyle(Coordinate::stringFromColumnIndex($col) . $row)->applyFromArray(
				array(
					'font' => array(
						'size'  => 8,
						'bold'  => true,
					)
				)
			)->getAlignment()->setWrapText(true);
		}
		$k_sheet->getRowDimension('4')->setRowHeight(80);

		// Skills Element Sheet
		$objSpreadsheet->createSheet();
		$s_sheet = $objSpreadsheet->setActiveSheetIndex(1);
		$s_sheet->setTitle($sheet_name . ' Skills');
		$s_sheet->setCellValue('A1', $sheet_heading . ' - Skills');
		$s_sheet->mergeCells("A1:F1");
		$s_sheet->getStyle("A1:F1")
			->applyFromArray(
				array(
					'fill' => array(
						'type' => Fill::FILL_SOLID,
						'color' => array('rgb' => '000000'),
					),
					'font' => array(
						'size'  => 14,
						'bold'  => true,
						'color' => array('rgb' => 'FF4500'),
					)
				)
			);
		$s_sheet->setCellValue('A2', 'Skills Key:');
		$s_sheet->mergeCells("A2:B2");
		$s_sheet->getStyle('A2:B2')->applyFromArray(
			array(
				'fill' => array(
					'type' => Fill::FILL_SOLID,
					'color' => array('rgb' => 'E8E8E8'),
				),
				'font' => array(
					'bold'  => true,
				)
			)
		);
		$s_sheet->setCellValue('C2', '0 = No Experience');
		$s_sheet->setCellValue('D2', '1 = Some Experience');
		$s_sheet->setCellValue('E2', '2 = Extensive Experience');
		$s_sheet->setCellValue('F2', '3 = Expert');
		foreach (range('A', 'E') as $columnID) {
			$s_sheet->getColumnDimension($columnID)
				->setAutoSize(true);
		}
		$s_sheet->setCellValue('A4', 'Learner Name');
		$s_sheet->setCellValue('B4', 'Employer');
		$s_sheet->setCellValue('C4', 'Score');
		$s_sheet->setCellValue('D4', 'Total Score');
		$s_sheet->setCellValue('E4', 'Answered 2 or 3');
		$s_sheet->setCellValue('F4', '% Answered 2 or 3');
		$row = 4;
		$col = 5;
		foreach ($questions_s as $key => $value) {
			$s_sheet->setCellValue(Coordinate::stringFromColumnIndex(++$col) . $row, $value);
			$s_sheet->getStyle(Coordinate::stringFromColumnIndex($col) . $row)->applyFromArray(
				array(
					'font' => array(
						'size'  => 8,
						'bold'  => true,
					)
				)
			)->getAlignment()->setWrapText(true);
		}
		$s_sheet->getRowDimension('4')->setRowHeight(80);

		if ($assessment_type == 'lmo') {
			// Production Element Sheet
			$objSpreadsheet->createSheet();
			$p_sheet = $objSpreadsheet->setActiveSheetIndex(2);
			$p_sheet->setTitle($sheet_name . ' Production');
			$p_sheet->setCellValue('A1', $sheet_heading . ' - Production');
			$p_sheet->mergeCells("A1:F1");
			$p_sheet->getStyle("A1:F1")
				->applyFromArray(
					array(
						'fill' => array(
							'type' => Fill::FILL_SOLID,
							'color' => array('rgb' => '000000'),
						),
						'font' => array(
							'size'  => 14,
							'bold'  => true,
							'color' => array('rgb' => 'FF4500'),
						)
					)
				);
			$p_sheet->setCellValue('A2', 'Production Key:');
			$p_sheet->mergeCells("A2:B2");
			$p_sheet->getStyle('A2:B2')->applyFromArray(
				array(
					'fill' => array(
						'type' => Fill::FILL_SOLID,
						'color' => array('rgb' => 'E8E8E8'),
					),
					'font' => array(
						'bold'  => true,
					)
				)
			);
			$p_sheet->setCellValue('C2', '0 = No Understanding');
			$p_sheet->setCellValue('D2', '1 = Basic Understanding');
			$p_sheet->setCellValue('E2', '2 = Good Understanding');
			$p_sheet->setCellValue('F2', '3 = Proficient');
			$p_sheet->setCellValue('G2', '4 = Expert');
			foreach (range('A', 'F') as $columnID) {
				$p_sheet->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			$p_sheet->setCellValue('A4', 'Learner Name');
			$p_sheet->setCellValue('B4', 'Employer');
			$p_sheet->setCellValue('C4', 'Job Role');
			$p_sheet->setCellValue('D4', 'Job Title');
			$p_sheet->setCellValue('E4', 'Score');
			$p_sheet->setCellValue('F4', 'Total Score');
			$p_sheet->setCellValue('G4', 'Answered 3 or 4');
			$p_sheet->setCellValue('H4', '% Answered 3 or 4');
			$row = 4;
			$col = 7;
			foreach ($questions_p as $key => $value) {
				$p_sheet->setCellValue(Coordinate::stringFromColumnIndex(++$col) . $row, $value);
				$p_sheet->getStyle(Coordinate::stringFromColumnIndex($col) . $row)->applyFromArray(
					array(
						'font' => array(
							'size'  => 8,
							'bold'  => true,
						)
					)
				)->getAlignment()->setWrapText(true);
			}
			$p_sheet->getRowDimension('4')->setRowHeight(80);
		}

		$st = $link->query($view->getSQLStatement()->__toString());
		if ($st) {
			$k_row = 5;
			$s_row = 5;
			$p_row = 5;
			while ($datarow = $st->fetch(DAO::FETCH_ASSOC)) {
				$k_col = 0;
				$s_col = 0;
				$p_col = 0;

				$k_assessment = (array)json_decode($datarow['k_qs']);
				$s_assessment = (array)json_decode($datarow['s_qs']);
				$p_assessment = (array)json_decode($datarow['p_qs']);

				$k_stats = OnboardingHelper::calculateKS('k', $k_assessment);
				$s_stats = OnboardingHelper::calculateKS('s', $s_assessment);
				$p_stats = OnboardingHelper::calculateKS('p', $p_assessment);

				$k_sheet->setCellValue(Coordinate::stringFromColumnIndex($k_col) . $k_row, $datarow['firstnames'] . ' ' . $datarow['surname']);
				$k_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($k_col))->setAutoSize(true);
				$k_col++;

				$k_sheet->setCellValue(Coordinate::stringFromColumnIndex($k_col) . $k_row, $datarow['legal_name']);
				$k_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($k_col))->setAutoSize(true);
				$k_col++;

				$k_sheet->setCellValue(Coordinate::stringFromColumnIndex($k_col) . $k_row, $k_stats->score);
				$k_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($k_col))->setAutoSize(true);
				$k_col++;

				$k_sheet->setCellValue(Coordinate::stringFromColumnIndex($k_col) . $k_row, $k_stats->total_score);
				$k_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($k_col))->setAutoSize(true);
				$k_col++;

				$k_sheet->setCellValue(Coordinate::stringFromColumnIndex($k_col) . $k_row, $k_stats->t_3_or_4);
				$k_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($k_col))->setAutoSize(true);
				$k_col++;

				$k_sheet->setCellValue(Coordinate::stringFromColumnIndex($k_col) . $k_row, $k_stats->percentage_3_or_4);
				$k_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($k_col))->setAutoSize(true);
				$k_col++;

				foreach ($questions_k as $id => $desc) {
					$q_id = 'q' . $id;
					if (isset($k_assessment[$q_id])) {
						if (isset($listKnowledgeOptions[$k_assessment[$q_id]]))
							$k_sheet->setCellValue(Coordinate::stringFromColumnIndex($k_col) . $k_row, $listKnowledgeOptions[$k_assessment[$q_id]]);
						else
							$k_sheet->setCellValue(Coordinate::stringFromColumnIndex($k_col) . $k_row, $k_assessment[$q_id]);
					}
					$k_col++;
				}

				$k_row++;

				// Skills
				$s_sheet->setCellValue(Coordinate::stringFromColumnIndex($s_col) . $s_row, $datarow['firstnames'] . ' ' . $datarow['surname']);
				$s_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($s_col))->setAutoSize(true);
				$s_col++;

				$s_sheet->setCellValue(Coordinate::stringFromColumnIndex($s_col) . $s_row, $datarow['legal_name']);
				$s_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($s_col))->setAutoSize(true);
				$s_col++;

				$s_sheet->setCellValue(Coordinate::stringFromColumnIndex($s_col) . $s_row, $s_stats->score);
				$s_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($s_col))->setAutoSize(true);
				$s_col++;

				$s_sheet->setCellValue(Coordinate::stringFromColumnIndex($s_col) . $s_row, $s_stats->total_score);
				$s_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($s_col))->setAutoSize(true);
				$s_col++;

				$s_sheet->setCellValue(Coordinate::stringFromColumnIndex($s_col) . $s_row, $s_stats->t_2_or_3);
				$s_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($s_col))->setAutoSize(true);
				$s_col++;

				$s_sheet->setCellValue(Coordinate::stringFromColumnIndex($s_col) . $s_row, $s_stats->percentage_2_or_3);
				$s_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($s_col))->setAutoSize(true);
				$s_col++;

				foreach ($questions_s as $id => $desc) {
					$q_id = 'q' . $id;
					if (isset($s_assessment[$q_id])) {
						if (isset($listSkillsOptions[$s_assessment[$q_id]]))
							$s_sheet->setCellValue(Coordinate::stringFromColumnIndex($s_col) . $s_row, $listSkillsOptions[$s_assessment[$q_id]]);
						else
							$s_sheet->setCellValue(Coordinate::stringFromColumnIndex($s_col) . $s_row, $s_assessment[$q_id]);
					}
					$s_col++;
				}

				$s_row++;

				if ($assessment_type == 'lmo') {
					// Production
					$p_sheet->setCellValue(Coordinate::stringFromColumnIndex($p_col) . $p_row, $datarow['firstnames'] . ' ' . $datarow['surname']);
					$p_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($p_col))->setAutoSize(true);
					$p_col++;

					$p_sheet->setCellValue(Coordinate::stringFromColumnIndex($p_col) . $p_row, $datarow['legal_name']);
					$p_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($p_col))->setAutoSize(true);
					$p_col++;

					$job_role = isset($this->job_roles[$datarow['your_role']]) ? $this->job_roles[$datarow['your_role']] : $datarow['your_role'];
					$p_sheet->setCellValue(Coordinate::stringFromColumnIndex($p_col) . $p_row, $job_role);
					$p_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($p_col))->setAutoSize(true);
					$p_col++;

					$p_sheet->setCellValue(Coordinate::stringFromColumnIndex($p_col) . $p_row, $datarow['job_title']);
					$p_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($p_col))->setAutoSize(true);
					$p_col++;

					$p_sheet->setCellValue(Coordinate::stringFromColumnIndex($p_col) . $p_row, $p_stats->score);
					$p_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($p_col))->setAutoSize(true);
					$p_col++;

					$p_sheet->setCellValue(Coordinate::stringFromColumnIndex($p_col) . $p_row, $p_stats->total_score);
					$p_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($p_col))->setAutoSize(true);
					$p_col++;

					$p_sheet->setCellValue(Coordinate::stringFromColumnIndex($p_col) . $p_row, $p_stats->t_3_or_4);
					$p_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($p_col))->setAutoSize(true);
					$p_col++;

					$p_sheet->setCellValue(Coordinate::stringFromColumnIndex($p_col) . $p_row, $p_stats->percentage_3_or_4);
					$p_sheet->getColumnDimension(Coordinate::stringFromColumnIndex($p_col))->setAutoSize(true);
					$p_col++;

					foreach ($questions_p as $id => $desc) {
						$q_id = 'q' . $id;
						if (isset($p_assessment[$q_id])) {
							if (isset($listKnowledgeOptions[$p_assessment[$q_id]]))
								$p_sheet->setCellValue(Coordinate::stringFromColumnIndex($p_col) . $p_row, $listKnowledgeOptions[$p_assessment[$q_id]]);
							else
								$p_sheet->setCellValue(Coordinate::stringFromColumnIndex($p_col) . $p_row, $p_assessment[$q_id]);
						}
						$p_col++;
					}

					$p_row++;
				}
			}
		} else {
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}



		$objSpreadsheet->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="CRMActivities.xlsx"');
		header('Cache-Control: max-age=0');
		header('Pragma: public');

		$objWriter = new Xlsx($objSpreadsheet);
		$objWriter->save('php://output');
	}

	public function generate_graph($data)
	{
		$options = new stdClass();
		$options->chart = (object)['type' => 'column'];
		$options->title = (object)['text' => ''];
		$options->subtitle = (object)['text' => 'Learners Scores'];
		$options->xAxis = (object)[
			'type' => 'category',
			'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '8px', 'fontFamily' => 'Verdana, sans-serif']]
		];
		$options->yAxis = (object)['min' => 0, 'title' => (object)['text' => 'Score']];
		$options->legend = (object)['enabled' => false];
		$options->tooltip = (object)['pointFormat' => 'Learners: <b>{point.y}</b>'];

		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->data = [];
		foreach ($data as $key => $value) {
			$series->data[] = [$key, $value];
		}
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

		$options->series[] = $series;


		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public $score_graph = '';

	public $job_roles = [
		1 => 'Production/Assembly',
		2 => 'Inspection/Quality Assurance',
		3 => 'Logistics/Material Handling',
		4 => 'Production processing/Finishing',
	];
}
