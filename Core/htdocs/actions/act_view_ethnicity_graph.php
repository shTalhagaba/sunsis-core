<?php
class view_ethnicity_graph implements IAction
{
	public function execute(PDO $link)
	{
		
		$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
		
		$view = ViewEthnicityGraph::getInstance();
		$view->refresh($link, $_REQUEST);

		$view->save_graph_data($link);
		
		$sql = "select description, count(value) as total from graph_data group by description";
		$st = $link->query($sql);		
		if($st) 
		{
			// for pie
			$xml = "<graph>";
			// for single bar
			$data = Array();
			$labels = Array();
			while($row = $st->fetch())
			{
				// for pie
				$xml .= "<record>";
				$xml .= "<description>" . $row['description'] . "</description>";
				$xml .= "<value>" . $row['total'] . "</value>";
				$xml .= "</record>";
				// for single bar
				$data[] = $row['total'];
				$labels[] = $row['description']; 
			}
			$xml .= "</graph>";
		}
		
		
		require_once('tpl_view_ethnicity_graph.php');
	}

}
?>