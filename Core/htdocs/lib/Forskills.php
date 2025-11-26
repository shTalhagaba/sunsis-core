<?php

class Forskills
{
	public static function getUserDetails($url, $filters)
	{
		$_filters = "";
		if(is_array($filters) && count($filters) > 0)
		{
			$filters = json_encode($filters);
			$_filters = "&filters=".$filters;
		}

		$json = @file_get_contents($url.'&action=getUserDetails'.$_filters, true);
		if($json === false)
			return 'No Result';

		return $json;
	}

	public static function registerUser($url, $data)
	{
		$_data = '';
		if(is_array($data) && count($data) > 0)
		{
			$data = json_encode($data);
			$_data = "&data=".$data;
		}

		//throw new Exception($url.'&action=registerUser&fatalError=1'.$_data);

		$json = @file_get_contents($url.'&action=registerUser&fatalError=1'.$_data, true);
		if($json === false)
			return 'No Result';

		return $json;
	}

	public static function getUserAssessments($url, $filters)
	{
		$_filters = "";
		if(is_array($filters) && count($filters) > 0)
		{
			$filters = json_encode($filters);
			$_filters = "&filters=".$filters;
		}


		$json = @file_get_contents($url.'&action=getUserAssessments'.$_filters, true);
		if($json === false)
			return 'No Result';

		return $json;
	}

	public static function getAllResultsForUser($url, $filters)
	{
		$_filters = "";
		if(is_array($filters) && count($filters) > 0)
		{
			$filters = json_encode($filters);
			$_filters = "&filters=".$filters;
		}

		$json = @file_get_contents($url.'&action=getAllResultsForUser'.$_filters, true);
		if($json === false)
			return 'No Result';

		return $json;
	}
}