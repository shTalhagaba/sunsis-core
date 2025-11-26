<?php
class view_employers_pool implements IAction
{
	public function execute(PDO $link)
	{
		$export = isset($_REQUEST['export'])?$_REQUEST['export']:'';
		
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_employers_pool", "View Prospects");
	
		$view = ViewEmployersPool::getInstance($link);
		$view->refresh($link, $_REQUEST);

		if(isset($_REQUEST['ViewEmployersPool_filter_postcodes']) && $_REQUEST['ViewEmployersPool_filter_postcodes']!='')
		{
			$postcode = $_REQUEST['ViewEmployersPool_filter_postcodes'];
			$distance = $_REQUEST['ViewEmployersPool_filter_distance'];
		}

		if($export=='export')
		{
			$this->exportRecordsToExcel($link, $view, $_REQUEST['postcode'], $_REQUEST['distance']);
		}

		$numberOfProspectRecords = $view->getRowCount();

		require_once('tpl_view_employers_pool.php');
	}

	private function exportRecordsToExcel(PDO $link, ViewEmployersPool $view, $postcode, $distance)
	{
		$view = View::getViewFromSession('view_ViewEmployersPool'.$postcode.'_'.$distance);
		$columnsToShow = $view->getSelectedColumns($link);

		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');

		$emp_sql = $statement->__toString();
		if ( preg_match("/easting is not null and \'(.*)\' is not null\) AND/", $emp_sql, $postcode) ) {
			$loc = new GeoLocation();
			$loc->setPostcode($postcode[1], $link);
			$longitude = $loc->getLongitude();
			$latitude = $loc->getLatitude();
			$easting = $loc->getEasting();
			$northing = $loc->getNorthing();
		}

		if ( preg_match("/northing is not null and \'(.*)\' is not null/", $emp_sql, $set_distance) ) {
			$search_distance = $set_distance[1];
			$emp_sql = preg_replace("/LIMIT (.*)$/ ","", $emp_sql);
		}

		if ( is_object($loc) && is_numeric($search_distance) ) {
			$distance_check = 'AND (SQRT(POWER(ABS('.$easting.' - emp_pool.easting), 2) + POWER(ABS('.$northing.' - emp_pool.northing), 2)))/1609.344 <= '.$search_distance.' GROUP BY';
			$emp_sql = preg_replace("/GROUP BY/ ",$distance_check, $emp_sql);
		}
		//$where_clause = " WHERE (easting IS NOT NULL AND '" . $postcode . "' IS NOT NULL) AND ( northing IS NOT NULL AND '" . $distance . "' IS NOT NULL) AND (SQRT(POWER(ABS(" . $easting . " - emp_pool.easting), 2) + POWER(ABS(" . $northing . " - emp_pool.northing), 2))) / 1609.344 <= " . $distance;
		//pre($emp_sql);

		$st = $link->query($emp_sql);
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=EmployersPoolCSV.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			$line = '';
			foreach($columnsToShow AS $column)
			{
				$line .= $column . ",";
			}

			$line = rtrim($line, ",");
			$line .= "\r\n";

			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				foreach($columnsToShow AS $column)
				{
					$line .= str_replace(',', ' ', $row[$column]) . ', ';
				}
				$line = rtrim($line, ",");
				$line .= "\r\n";
			}
			echo $line;
		}

		exit;
	}
}
?>