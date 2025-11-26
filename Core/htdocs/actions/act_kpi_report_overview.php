<?php
class kpi_report_overview implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, 'do.php?_action=kpi_report_overview', 'KPI Reports');
	
	//	require_once('tpl__prototype_1.php');
	//	die;
		
		$types = array(
			'gender' => array(
				'title' => 'Number of learners by area of learning, gender and programme'
				,'output' => array('HTML', 'CSV', 'XLS', 'BarChart', 'PieChart')
			)
			,'skin' => array(
				'title' => 'Number of learners by area of learning, ethnicity and programme'
				,'output' => array('HTML', 'CSV', 'XLS', 'BarChart', 'PieChart')
			)
			,'race' => array(
				'title' => 'Number of learners by area of learning and ethnicity'
				,'output' => array('HTML', 'CSV', 'XLS', 'BarChart', 'PieChart')
			)
			,'disability' => array(
				'title' => 'Number of learners with a disability or learning difficulty by area of learning and programme'
				,'output' => array('HTML', 'CSV', 'XLS', 'BarChart', 'PieChart')
			)
			,'discrepency' => array(
				'title' => 'Discrepency Report'
				,'output' => array('HTML')
			)			
		);		
		
		
		require_once('tpl_view_kpi_report_overview.php');
	}
	
	public function randomName()
	{
		$forenames = array('John','Jane','Jack','Daniel','James','Michael','Dean','Scott','Andrew','Peter','Gary','Paul','Ian','Jenny', 'Emma', 'Charlotte','Lorraine','Bethany','Lynda','Louise','Paula','Izzy','Lauren','Jane');
		$surnames = array('Smith', 'Cox','Grieve','Taylor','Brown','Davis','Evans','Wilson','Thomas','Johnson','Roberts','Robinson','Thompson','Wright','Walker','White','Edwards','Hughes','Green','Hall','Lewis','Harris','Lucas','Price','Tomlinson','Wilson','Campbell','Stewart','Robertson','MacDonald');
		
		$frand = rand(0, sizeof($forenames)-1);
		$srand = rand(0, sizeof($surnames)-1);
		return $surnames["$srand"] . ', ' . $forenames["$frand"];
	}
}
?>