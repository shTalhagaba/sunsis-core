<?php
class view_ks_assessment_report_graph implements IAction
{
	public $graphs = [];
	public $total_graph = [];
	public function execute(PDO $link)
	{
		$type = isset($_REQUEST['type']) ? strtolower($_REQUEST['type']) : 'l2iop';
		$_q = isset($_REQUEST['q']) ? strtolower($_REQUEST['q']) : 'k';

		$view = VoltView::getViewFromSession('ViewKsAssessmentReport', 'ViewKsAssessmentReport');
		if(is_null($view))
		{
			http_redirect('do.php?_action=view_ks_assessment_report');
		}

		$_SESSION['bc']->add($link, "do.php?_action=view_ks_assessment_report_graph", "View Assessment Report Graph");

		$ddlAssessmentTypes = OnboardingHelper::getAssessmentTypesList();

		$questions = DAO::getLookupTable($link, "SELECT CONCAT('q', LOWER(assessment_type), question_id) AS id, question_desc AS description FROM lookup_ks_questions WHERE assessment_type = '{$type}' AND question_type = '{$_q}'");

		$answers1 = [
			"No understanding" => 0,
			"Basic understanding" => 0,
			"Good understanding" => 0,
			"Proficient" => 0,
			"Expert and can train others" => 0,
		];

		$answers2 = [
			"No experience" => 0,
			"Some experience" => 0,
			"Extensive experience" => 0,
			"Expert and can train others" => 0,
		];

		$answers11 = OnboardingHelper::getKnowledgeAnswersList();
		$answers22 = OnboardingHelper::getSkillsAnswersList();

		$i = 0;
		$total_score = 0;
		foreach($questions AS $key => $value)
		{
			$temp = new stdClass();
			$temp->graph_id = 'graph'.++$i;
			$temp->question_desc = $value;
			if($_q == 's')
			{
				$temp->data = $answers2;
				$total_score += 4;
			}
			else
			{
				$temp->data = $answers1;
				$total_score += 3;
			}
			$temp->graph = '';

			$this->graphs[$key] = $temp;
		}

		$this->total_graph['total_score'] = $total_score;

		$st = $link->query($view->getSQLStatement()->__toString());
		if ($st)
		{
			while ($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				if($_q == 'k')
					$assessment = (array)json_decode($row['k_qs']);
				elseif($_q == 's')
					$assessment = (array)json_decode($row['s_qs']);
				elseif($_q == 'p')
					$assessment = (array)json_decode($row['p_qs']);
				else
					throw new Exception('Invalid elements type');
				foreach($assessment AS $key => $value)
				{
					if($_q == 's')
						$this->graphs[strtolower($key)]->data[$answers22[$value]]++;
					else
						$this->graphs[strtolower($key)]->data[$answers11[$value]]++;
				}
			}
		}
		else
		{
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}

		foreach($this->graphs AS $entry)
		{
			$entry->graph = $this->generate_graph($ddlAssessmentTypes[$type], $_q, $entry->data);
		}

		include_once('tpl_view_ks_assessment_report_graph.php');
	}

	public function generate_graph($title, $_q, $data)
	{
		$options = new stdClass();
		$options->chart = (object)['type' => 'column'];
		$options->title = (object)['text' => ''];
		$options->subtitle = (object)['text' => $title];
		$options->xAxis = (object)[
			'type' => 'category',
			'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '8px', 'fontFamily' => 'Verdana, sans-serif']]
		];
		$options->yAxis = (object)['min' => 0, 'title' => (object)['text' => 'Answers']];
		$options->legend = (object)['enabled' => false];
		$options->tooltip = (object)['pointFormat' => 'Learners: <b>{point.y}</b>'];

		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$series->data[] = [$key, $value];
		}
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

		$options->series[] = $series;


		return json_encode($options, JSON_NUMERIC_CHECK);
	}

}