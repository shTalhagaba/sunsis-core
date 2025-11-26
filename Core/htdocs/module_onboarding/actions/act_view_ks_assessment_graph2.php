<?php
class view_ks_assessment_graph2 implements IAction
{
	public function execute(PDO $link)
	{
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'l2iop';
		$_q = isset($_REQUEST['q']) ? $_REQUEST['q'] : 'k';

		$_SESSION['bc']->add($link, "do.php?_action=view_ks_assessment_graph2", "View Assessment Report Graph");

		$ddlAssessmentTypes = OnboardingHelper::getAssessmentTypesList();

		$graphs = [];


		$questions = DAO::getSingleColumn($link, "SELECT question_desc FROM lookup_ks_questions WHERE assessment_type = '{$type}' AND question_type = '{$_q}'");



		$graph = $this->generate_graph();

		include_once('tpl_view_ks_assessment_graph2.php');
	}

	public function generate_graph()
	{
		$data = [
			"0-50" => rand(1,10),
			"51-70" => rand(1,10),
			"71-90" => rand(1,10),
			"91-100" => rand(1,10),
			"100+" => rand(1,10),
		];

		$options = new stdClass();
		$options->chart = (object)['type' => 'column'];
		$options->title = (object)['text' => ''];
		$options->subtitle = (object)['text' => 'Learners Scores'];
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