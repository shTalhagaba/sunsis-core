<?php
class view_edim_reports_duplex extends ActionController
{
    public function indexAction(PDO $link)
    {
        $_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_edim_reports_duplex", "EDIM Dashboard");

        $females = DAO::getSingleColumn($link, "SELECT id FROM users WHERE type = 5 AND gender = 'F'");
        $males = DAO::getSingleColumn($link, "SELECT id FROM users WHERE type = 5 AND gender = 'M'");
        $others = DAO::getSingleColumn($link, "SELECT id FROM users WHERE type = 5 AND gender NOT IN ('M', 'F') ");

        $gendersPieChart = $this->generateGendersPie(count($males), count($females), count($others));


        $l1comp =  DAO::getSingleColumn(
            $link,
            "SELECT DISTINCT training.`learner_id` 
        FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` 
        WHERE crm_training_schedule.`level` = 'L1' AND training.`status` = 2;"
        );

        $l2comp =  DAO::getSingleColumn(
            $link,
            "SELECT DISTINCT training.`learner_id` 
        FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` 
        WHERE crm_training_schedule.`level` = 'L2' AND training.`status` = 2;"
        );

        $l3comp =  DAO::getSingleColumn(
            $link,
            "SELECT DISTINCT training.`learner_id` 
        FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` 
        WHERE crm_training_schedule.`level` = 'L3' AND training.`status` = 2;"
        );

        $l4comp =  DAO::getSingleColumn(
            $link,
            "SELECT DISTINCT training.`learner_id` 
        FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` 
        WHERE crm_training_schedule.`level` = 'L4' AND training.`status` = 2;"
        );

        $completionsBarChart = $this->generateCompletionsBar(count($l1comp), count($l2comp), count($l3comp), count($l4comp));

        require('tpl_view_edim_reports_duplex.php'); 
    }

    private function generateCompletionsBar($l1comp, $l2comp, $l3comp, $l4comp)
    {
        $options = new stdClass();
		$options->chart = (object)['type' => 'column', 'options3d' => (object)['enabled' => true, 'alpha' => 15, 'beta' => 15, 'depth' => 50, 'viewDistance' => 25]];
		$options->title = (object)['text' => 'Completions by Level'];
		$options->xAxis = (object)[
			'type' => 'category',
			'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']]
		];
		$options->yAxis = (object)['min' => 0, 'title' => (object)['text' => 'Learners']];
		$options->legend = (object)['enabled' => false];
		$options->tooltip = (object)['pointFormat' => 'Learners: <b>{point.y}</b>'];

		$options->series = [];
		$series = new stdClass();
		$series->name = 'Learners';
		$series->data = [];
        $series->data[] = ['Level 1', $l1comp];
        $series->data[] = ['Level 2', $l2comp];
        $series->data[] = ['Level 3', $l3comp];
        $series->data[] = ['Level 4', $l4comp];

		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '13px', 'fontFamily' => 'Verdana, sans-serif']];

		$options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    private function generateGendersPie($male, $female, $others)
    {
        $options = new stdClass();
        $options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
        $options->title = (object)['text' => 'Learners by Gender'];
        $options->plotOptions = (object)['pie' => (object )['innerSize' => 100, 'depth' => 45, 'allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true]];
        $options->series = [];
        $series = new stdClass();
        $series->name = 'Learners';
        $series->colorByPoint = true;
        $series->data = [
            (object)[
                'name' => 'Male',
                'y' => $male,
                'key' => 'Male Learners',
            ],
            (object)[
                'name' => 'Female',
                'y' => $female,
                'key' => 'Female Learners',
            ],
            (object)[
                'name' => 'Other',
                'y' => $others,
                'key' => 'Other (Not specified)',
            ],
        ];

        $options->series[] = $series;

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

}