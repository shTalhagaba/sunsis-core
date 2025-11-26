<?php
class kpi_report_list implements IAction
{
	private $db = null;
	
	public function execute(PDO $link)
	{
		// make db object visible to whole class
		$this->db = $link;
		
		// calculate current contract year
		$contract_year = date('Y');
		if(date('n') < 8)
		{
			$contract_year -= 1;
		}
		//$contract_year = 2008;
		
		// default output
		if(!isset($_REQUEST['output']))
		{
			$_REQUEST['output'] = 'HTML';
		}	

		$sessionKey = get_class($this) . 'contractyear';
		if(isset($_SESSION["$sessionKey"]))
		{
			$contract_year = intval($_SESSION["$sessionKey"]);
		}
		if(isset($_REQUEST['y']))
		{
			$contract_year = intval($_REQUEST['y']);
			$_SESSION["$sessionKey"] = $contract_year;
		}

		$programme_type = 2;
		$sessionKey = get_class($this) . 'programmetype';
		if(isset($_SESSION["$sessionKey"]))
		{
			$programmme_type = intval($_SESSION["$sessionKey"]);
		}
		if(isset($_REQUEST['p']))
		{
			$programme_type = intval($_REQUEST['p']);
			$_SESSION["$sessionKey"] = $programme_type;
		}
		
		// list of valid reports
		$reports = array(
			'continuing_learners' => array(
				'title' => 'In Learning List  report'
				,'description' => 'Shows all learners who are in learning up to the selected contract year '
				,'output' => array('HTML', 'CSV')
				,'cssClass' => 'el'
			)
			,'early_leavers' => array(
				'title' => 'Early leavers report'
				,'description' => 'Shows all learners who have left their qualification early as of ' . date('d/m/Y')
				,'output' => array('HTML', 'CSV')
				,'cssClass' => 'el'
			)
			,'all_leavers' => array(
				'title' => 'All leavers report'
				,'description' => 'Shows all learners who have left their qualification as of ' . date('d/m/Y')
				,'output' => array('HTML', 'CSV')
				,'cssClass' => 'el'
			)
			,'all_learners' => array(
				'title' => 'All learners report'
				,'description' => 'Shows all learners as of ' . date('d/m/Y')
				,'output' => array('HTML', 'CSV')
				,'cssClass' => 'el'
			)
//			,'starters_leavers_achievers' => array(
//				'title' => 'Starters / Leavers / Achievers / Continuing'
//				,'description' => 'Shows the total number of learners who have started/left/achieved and who are continuing for each submission period'
//				,'output' => array('HTML', 'CSV', 'BarChart', 'PieChart')
//				,'cssClass' => 'sla'
//			)
//			,'transferred_learners' => array(
//				'title' => 'Transferred learners'
//				,'description' => 'Shows all learners taking a qualification who have transferred from a different provider as of ' . date('d/m/Y')
//				,'output' => array('HTML', 'CSV', 'BarChart', 'PieChart')
//				,'cssClass' => 'tl'
//			)
			,'unfunded_learners' => array(
				'title' => 'Unfunded learners'
				,'description' => 'Shows all learners who are currently being unfunded as of ' . date('d/m/Y')
				,'output' => array('HTML')
				,'cssClass' => 'ufl'
			)
			,'timely_achievers' => array(
				'title' => 'Timely achievers report'
				,'description' => 'Shows all learners who have finished before their expected end date  as of ' . date('d/m/Y')
				,'output' => array('HTML', 'CSV')
				,'cssClass' => 'el'
			)
			,'framework_achievers' => array(
				'title' => 'Achievers report'
				,'description' => 'Shows all learners who have completed all the aims within the framework they are taking as of ' . date('d/m/Y')
				,'output' => array('HTML', 'CSV', 'BarChart', 'PieChart')
				,'cssClass' => 'fa'
			)
			,'learner_achievements' => array(
				'title' => 'Framework progress'
				,'description' => 'Shows all learners who have not yet achieved their aims as of ' . date('d/m/Y')
				,'output' => array('HTML', 'CSV', 'BarChart', 'PieChart')
				,'cssClass' => 'la'
			)
//			,'newstarts_period3' => array(
//				'title' => 'New starts in (08/09) report'
//				,'description' => 'Shows all learners who started in 08/09 Contract'
//				,'output' => array('HTML', 'CSV')
//				,'cssClass' => 'el'
//			)
//			,'newstarts_period4' => array(
//				'title' => 'New starts in (09/10) report'
//				,'description' => 'Shows all learners who started in 09/10 Contract'
//				,'output' => array('HTML', 'CSV')
//				,'cssClass' => 'el'
//			)
//			,'newstarts_period5' => array(
//				'title' => 'New starts in (10/11) report'
//				,'description' => 'Shows all learners who started in 10/11 Contract'
//				,'output' => array('HTML', 'CSV')
//				,'cssClass' => 'el'
//			)
//			,'newstarts_period6' => array(
//				'title' => 'New starts in (11/12) report'
//				,'description' => 'Shows all learners who started in 11/12 Contract'
//				,'output' => array('HTML', 'CSV')
//				,'cssClass' => 'el'
//			)
		);

		$programme_types = DAO::getResultSet($link, "SELECT code, description FROM lookup_programme_type order by description asc");


		//if(DB_NAME=='am_superdrug' || DB_NAME=='ams' || DB_NAME=='am_baltic')
		{
			$reports['temporarily_withdrawn'] =  array(
				'title' => 'Temporarily Withdrawn Learners'
						,'description' => 'Shows all learners temporarily withdrawn and have not come back to training'
						,'output' => array('HTML', 'CSV')
						,'cssClass' => 'el'
				);	
		}

		
		if(!isset($_REQUEST['r']))
		{
			// breadcrumbs
			$_SESSION['bc']->index=0;
			$_SESSION['bc']->add($link, 'do.php?_action=kpi_report_list', 'KPI Reports');			
			require_once('tpl_kpi_report_list.php');
		}
		else
		{
			$reportName = $_REQUEST['r'];
			if(in_array($reportName, array_keys($reports)))
			{
				
					$path = './lib/kpi_reports/' . $reportName . '.php';
					if(!file_exists($path))
					{
						throw new Exception('Report not found, report file has not been created');
					}
					else
					{
						//pr($path);
						require_once($path);
			
						$className = "KPI_Report_$reportName";
						
						$report = new $className($this->db, $contract_year, $programme_type);	
								
						$outputHTML = $report->render($_REQUEST['output']);
						$reportInfo = $reports["$reportName"];
						
						// breadcrumbs
						$_SESSION['bc']->index=1;
						$_SESSION['bc']->add($link, 'do.php?_action=kpi_report_list&amp;r=' . $reportName, $reportInfo['title']);
							
						$url = $this->get_url();
						
						require_once('tpl_kpi_report_view.php');
					}
			}
			else
			{
				throw new Exception('Invalid report');
			}
		}
	}
	
	private function get_url()
	{
		return str_replace(array('&output=HTML','&output=BarChart','&output=PieChart'), '', substr($_SERVER['REQUEST_URI'], 1));	
	}	
}
?>