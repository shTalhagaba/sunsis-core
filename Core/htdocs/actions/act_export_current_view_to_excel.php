<?php
class export_current_view_to_excel implements IAction
{
	public function execute(PDO $link)
	{
		set_time_limit(0);
		ini_set('memory_limit','2048M');
		$key = array_key_exists('key', $_REQUEST) ? $_REQUEST['key'] : 'view';
		$columns = array_key_exists('columns', $_REQUEST) ? $_REQUEST['columns'] : 'view';
		$view = View::getViewFromSession($key); /* @var $view View */
		if(!is_null($view))
		{
			if($key=='view_ViewStudentQualifications')
				$view->exportToCSV($link, $columns, 'student_qualifications', $key);
			elseif($key=='view_ViewAssessmentReport')
				$view->exportToCSV($link, $columns, 'next_review_date');
			elseif($key=='view_ViewLearningAims')
				$view->exportToCSV($link, $columns, 'programme_type', $key);
			elseif($key=='view_ViewTrainingRecords')
				$view->exportToCSV($link, $columns, 'ViewTrainingRecords', $key);
			elseif($key=='view_ViewReviewsReport')
				$view->exportToCSV($link, $columns, 'ViewReviewsReport', $key);
			elseif($key=='view_ViewEVReport')
				$view->exportToCSV($link, $columns, 'ViewEVReport', $key);
			elseif($key=='view_ViewIVReport')
				$view->exportToCSV($link, $columns, 'ViewIVReport', $key);
			elseif($key=='view_ViewVacancies')
				$view->exportToCSV($link, $columns, 'ViewVacancies', $key);
			elseif($key=='view_ViewBirminghamLAReport')
				$view->exportToCSV($link, $columns, 'ViewBirminghamLAReport', $key);
			elseif($key=='view_ViewGroupEmployers')
				$view->exportToCSV($link, $columns, 'ViewGroupEmployers', $key);
			elseif($key=='view_ViewAttendanceV2AdHocRegistersReport')
				$view->exportToCSV($link, $columns, 'ViewAttendanceV2AdHocRegistersReport', $key);
			elseif($key=='view_ViewAttendanceV2Report')
				$view->exportToCSV($link, $columns, 'ViewAttendanceV2Report', $key);
			elseif($key=='view_ViewTrDestinations')
			{
				ViewTrDestinations::createAndPopulateLLDDs($link);
				$view->exportToCSV($link, $columns);
			}
            elseif($key=='view_ViewAssessmentPlanLogs')
                $view->exportToCSV($link, $columns, 'ViewAssessmentPlanLogs', $key);
            elseif($key=='view_ViewForms')
                $view->exportToCSV($link, $columns, 'ViewForms', $key);
			elseif($key=='view_ViewCandidates')
			{
				$query = $this->setDistanceSearchClause($link, $view);
				$view->setSQL($query);
				$view->exportToCSV($link, $columns, 'ViewCandidates', $key);
			}
			elseif($key=='view_ViewRegisters')
			{
				$viewColumns = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(`colum`) FROM view_columns WHERE `view` = 'ViewRegisters' AND visible = 1 AND user = 'master'");
				$view->exportToCSV($link, $viewColumns?:$columns);
			}
			elseif($key=='view_ViewDataReport')
			{
                $viewColumns = DAO::getSingleValue($link, "SELECT GROUP_CONCAT(`colum`) FROM view_columns WHERE `view` = 'ViewDataReport' AND visible = 1 AND user = 'master'");
				$view->exportToCSV($link, $viewColumns?:$columns);
			}
			else
				$view->exportToCSV($link, $columns);
		}
		else
		{
			header("Content-Type: text/html");
			echo '<html><body><script language="JavaScript">alert("Cannot find the view to export"); history.go(-1);</script></body></html>';
		}
	}

	private function setDistanceSearchClause(PDO $link, View $view)
	{
		$loc = NULL;
		$longitude = NULL;
		$latitude = NULL;
		$easting = NULL;
		$northing = NULL;

		$search_distance = NULL;

		$candidate_sql = $view->getSQL();

		if ( preg_match("/easting is not null and \'(.*)\' is not null\) AND/", $candidate_sql, $postcode) ) {
			$loc = new GeoLocation();
			$loc->setPostcode($postcode[1], $link);
			$longitude = $loc->getLongitude();
			$latitude = $loc->getLatitude();
			$easting = $loc->getEasting();
			$northing = $loc->getNorthing();
		}

		if ( preg_match("/northing is not null and \'(.*)\' is not null/", $candidate_sql, $set_distance) ) {
			$search_distance = $set_distance[1];
			$candidate_sql = preg_replace("/LIMIT (.*)$/ ","", $candidate_sql);
		}

		if ( is_object($loc) && is_numeric($search_distance) ) {
			$distance_check = 'AND (SQRT(POWER(ABS('.$easting.' - candidate.easting), 2) + POWER(ABS('.$northing.' - candidate.northing), 2)))/1609.344 <= '.$search_distance.' GROUP BY';
			$candidate_sql = preg_replace("/GROUP BY/ ",$distance_check, $candidate_sql);
		}

		return $candidate_sql;
	}
}
?>