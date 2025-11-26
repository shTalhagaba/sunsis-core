<?php
class rec_view_analytics implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->add($link, "do.php?_action=view_searches_analytics", "Searches Analytics");

		$view = RecViewCandidates::getInstance($link);
		$view->refresh($link, $_REQUEST);

		$panelSearchesKeywords = $this->searchesKeywords($link);
		$panelSearchesLocations = $this->searchesLocations($link);
		$panelLearnersByEthnicity = $this->learners_by_ethnicity($link, $view);
		$panelLearnersByAgeBand = $this->learners_by_age_band($link, $view);
		$panelLearnersByGender = $this->learners_by_gender($link, $view);
		$panelLearnersByPostcode = $this->learners_by_postcode($link, $view);

		require_once('tpl_rec_view_analytics.php');
	}

	public function searchesKeywords(PDO $link)
	{
		$keywords = [];
		$records = DAO::getSingleColumn($link, "SELECT full_search FROM candidate_searches");
		foreach($records AS $row)
		{
			$row = json_decode($row);
			$row->keywords = trim(strtolower($row->keywords));
			if($row->keywords != '')
			{
				if(!isset($keywords[$row->keywords]))
					$keywords[$row->keywords] = 0;

				$keywords[$row->keywords] += 1;
			}
		}
		$data = [];
		foreach($keywords AS $key => $value)
		{
			$obj = new stdClass();
			$obj->name = $key;
			$obj->weight = $value;
			$data[] = $obj;
		}

		$options = new stdClass();
		$options->title = (object)['text' => 'Wordcloud of keywords searches'];
		$series = [];
		$series[] = (object)['type' => 'wordcloud', 'data' => $data, 'name' => 'Searched'];
		$options->series = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function searchesLocations(PDO $link)
	{
		$locations = [];
		$records = DAO::getSingleColumn($link, "SELECT full_search FROM candidate_searches");
		foreach($records AS $row)
		{
			$row = json_decode($row);
			$row->location = trim(strtolower($row->location));
			if($row->location != '')
			{
				if(!isset($locations[$row->location]))
					$locations[$row->location] = 0;

				$locations[$row->location] += 1;
			}
		}
		$data = [];
		foreach($locations AS $key => $value)
		{
			$obj = new stdClass();
			$obj->name = $key;
			$obj->weight = $value;
			$data[] = $obj;
		}

		$options = new stdClass();
		$options->title = (object)['text' => 'Wordcloud of location searches'];
		$series = [];
		$series[] = (object)['type' => 'wordcloud', 'data' => $data, 'name' => 'Searched'];
		$options->series = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_ethnicity(PDO $link, View $view)
	{
		$ethnicities = [
			'31' => 'British',
			'32' => 'Irish',
			'33' => 'Gypsy or Irish Traveller',
			'34' => 'Any other White background',
			'35' => 'White and Black Caribbean',
			'36' => 'White and Black African',
			'37' => 'White and Asian',
			'38' => 'Any other Mixed',
			'39' => 'Indian',
			'40' => 'Pakistani',
			'41' => 'Bangladeshi',
			'42' => 'Chinese',
			'43' => 'Any other Asian',
			'44' => 'African',
			'45' => 'Caribbean',
			'46' => 'Any other Black',
			'47' => 'Arab',
			'98' => 'Any other ethnic group',
			'99' => 'Not known/not provided'
		];

		$sql = $view->getSQLStatement();

		$data = [];
		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			if(!isset($data[$row['ethnicity']]))
			{
				$data[$row['ethnicity']] = 0;
			}
			$data[$row['ethnicity']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie', 'options3d' => (object)['enabled' => true, 'alpha' => 45, 'beta' => 0]];
		$options->title = (object)['text' => 'Applications by Ethnicity'];
		$options->plotOptions = (object)['pie' => (object )['innerSize' => 100, 'depth' => 45, 'allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true]];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Applications';
		$series->colorByPoint = true;
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = isset($ethnicities[$key])?$ethnicities[$key]:$key;
			$d->y = $value;
			$d->key = $key;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_age_band(PDO $link, View $view)
	{
		$sql = $view->getSQLStatement();
		$data['16-18'] = 0;
		$data['19-23'] = 0;
		$data['24+'] = 0;
		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			if((int)$row['age_in_years'] >= 16 && (int)$row['age_in_years'] <= 18)
				$data['16-18'] += 1;
			elseif((int)$row['age_in_years'] >= 19 && (int)$row['age_in_years'] <= 23)
				$data['19-23'] += 1;
			else
				$data['24+'] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie'];
		$options->title = (object)['text' => 'Applications by Age Band'];
		$options->plotOptions = (object)['pie' => (object )['allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true], ];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Applications';
		$series->colorByPoint = true;
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = $key;
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_gender(PDO $link, View $view)
	{
		$genders = ['M' => 'Male', 'F' => 'Female'];
		$sql = $view->getSQLStatement();
		$data = [];
		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			if(!isset($data[$row['gender']]))
			{
				$data[$row['gender']] = 0;
			}
			$data[$row['gender']] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'pie'];
		$options->title = (object)['text' => 'Applications by Gender'];
		$options->plotOptions = (object)['pie' => (object )['allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true], ];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Applications';
		$series->colorByPoint = true;
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = $genders[$key];
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}

	public function learners_by_postcode(PDO $link, View $view)
	{
		$sql = $view->getSQLStatement();
		$data = [];
		$result = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
		foreach($result AS $row)
		{
			$postcode_part = explode(" ", $row['postcode']);
			$postcode_part = $postcode_part[0];
			if(!isset($data[$postcode_part]))
			{
				$data[$postcode_part] = 0;
			}
			$data[$postcode_part] += 1;
		}

		$options = new stdClass();
		$options->chart = (object)['type' => 'column'];
		$options->title = (object)['text' => 'Applications by postcode'];
		$options->plotOptions = (object)['pie' => (object )['allowPointSelect' => true, 'cursor' => 'pointer', 'dataLabels' => (object)['enabled' => true, 'format' => '<b>{point.name}</b>: {point.y} ({point.percentage:.1f} %)'], 'showInLegend' => true], ];
		$options->xAxis = (object)[
			'type' => 'category',
			'labels' => (object)['rotation' => -45, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']]
		];
		$options->series = [];
		$series = new stdClass();
		$series->name = 'Applications';
		$series->colorByPoint = true;
		$series->dataLabels = (object)['enabled' => true, 'rotation' => -90, 'color' => '#FFFFFF', 'align' => 'right', 'format' => '{point.y}', 'y' => 2, 'style' => (object)['fontSize' => '10px', 'fontFamily' => 'Verdana, sans-serif']];
		$series->data = [];
		foreach($data AS $key => $value)
		{
			$d = new stdClass();
			$d->name = $key;
			$d->y = $value;
			$series->data[] = $d;
		}
		$options->series[] = $series;

		return json_encode($options, JSON_NUMERIC_CHECK);
	}
}
?>