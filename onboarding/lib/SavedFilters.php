<?php

class SavedFilters extends ValueObject
{
	// db fields for when it becomes a ValueObject
	public $filter_id;
	public $filter_name;
	public $username;
	public $URI;
	public $filters;	
	
	/**
	 * Gets an array of ValueObjects for each saved filter for a particular user at a particular URI
	 * @param PDO $link
	 * @param $URI
	 * @param $username
	 * @return unknown_type
	 */
	public static function getSavedFilters(PDO $link, $URI, $username)
	{
		$user_sql = "
			SELECT 
				*,'user' as type
			FROM 
				user_saved_filters
			WHERE
				URI LIKE '" . addslashes($URI) . "%'
				AND
					username = '" . addslashes($username) . "'
			ORDER BY
				filter_name ASC
		;";
		
		$global_sql = "
				SELECT 
					*,'global' as type 
				FROM 
					central.user_saved_filters
				WHERE
					URI LIKE '" . addslashes($URI) . "%'
				ORDER BY
					filter_name ASC					
		;";
		
		$collection = array();
		$collection['user'] = DAO::getResultset($link, $user_sql, DAO::FETCH_ASSOC);
		$collection['global'] = DAO::getResultset($link, $global_sql, DAO::FETCH_ASSOC);
		
		return $collection;
		
		/*$filter = new SavedFilters();
		
		$sql = "
			SELECT 
				*,'user' as type
			FROM 
				user_saved_filters
			WHERE
				URI LIKE '" . addslashes($URI) . "%'
				AND
					username = '" . addslashes($username) . "'
			ORDER BY
				filter_name ASC
		;";

		$st = $link->query($sql);

		if($st)
		{
			$collection = array();
			//var_dump($st->fetch());
			while($row = $st->fetch())
			{
				$collection['user'][] = $row;
			}
			
			// now get global filters
			//$linkcentral = new PDO("mysql:host=".DB_HOST.";dbname=central;port=".DB_PORT, DB_USER, DB_PASSWORD);

			$sql = "
				SELECT 
					*,'global' as type 
				FROM 
					central.user_saved_filters
				WHERE
					URI LIKE '" . addslashes($URI) . "%'
				ORDER BY
					filter_name ASC					
			;";
	
			$st = $link->query($sql);		
			if($st)
			{
				$rows2 = array();
				while($row = $st->fetch())
				{
					$collection['global'][] = $row;
				}		
			}
		}
		else
		{
			throw new Exception(implode($link->errorInfo()));
		}
		
		return $collection;*/
	}	
	
	/**
	 * Gets a specific saved filter based on its ID
	 * @param PDO $link
	 * @param $filterID
	 * @return unknown_type
	 */
	public static function getSavedFilter(PDO $link, $filterID)
	{
		if(!$filterID){
			return null;
		}
		
		// $filterID format: (u|g)\d+
		$flag = substr($filterID, 0, 1);
		$filterID = substr($filterID, 1);
		
		if($flag == 'u') 
		{
			// USER FILTER
			$sql = "SELECT * FROM user_saved_filters WHERE filter_id = '" . intval($filterID) . "';";
		}
		elseif($flag == 'g')
		{
			// GLOBAL FILTER
			$sql = "SELECT * FROM central.user_saved_filters WHERE filter_id = '" . intval($filterID) . "';";
		}
		else
		{
			throw new Exception("Unknown filter-scope flag: ".$flag);
		}

		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(count($rows) > 0)
		{
			$filter = new SavedFilters();
			$filter->populate($rows[0]);
			return $filter;
		}

		return null;
	}	

	/**
	 * Produces an XML string representation of a specific filter
	 * @param $prefix
	 * @param $namespace
	 * @return unknown_type
	 */
	public function toXML($prefix = null, $namespace = null)
	{
		if(!is_null($namespace))
		{
			if($prefix == '')
			{
				$xmlns = "xmlns=\"".htmlspecialchars($namespace).'"';
			}
			else
			{
				$xmlns = "xmlns:$prefix=\"".htmlspecialchars($namespace).'"';
			}
		}
		else
		{
			$xmlns = '';
		}
		
		if($prefix != '')
		{
			$p = $prefix.':';
		}
		else
		{
			$p = '';
		}

		$xml = "<elements>";
		$xml .= "<element>";
		$xml .= "<filter_id>". htmlspecialchars($this->filter_id) . "</filter_id>";
		$xml .= "<filter_name>". htmlspecialchars($this->filter_name) . "</filter_name>";
		$xml .= "<uri>". htmlspecialchars($this->URI) . "</uri>";
		$xml .= "<username>". htmlspecialchars($this->username) . "</username>";
		$xml .= "<filters>";
		
		$filters = unserialize($this->filters);
		foreach($filters as $key => $val)
		{
			if(!is_array($val))
				$xml .= "<filter name=\"" . htmlspecialchars($key) . '">' . htmlspecialchars($val) . '</filter>';
		}
		
		$xml .= "</filters>";
		$xml .= "<grids>";
		foreach($filters as $key => $val)
		{
			if(is_array($val))
			{
				$xml .= "<grid name=\"" . htmlspecialchars('grid_'.$key) . "\">";
				$xml .= "<values>";
				foreach($val as $k => $v)
					$xml .= "<value>" . htmlspecialchars($v) . '</value>';
					//$xml .= "<grid name=\"" . htmlspecialchars('grid_'.$key) . '">' . htmlspecialchars($v) . '</grid>';
				$xml .= "</values>";
				$xml .= "</grid>";
			}
		}
		$xml .= "</grids>";
		$xml .= "</element>";
		$xml .= "</elements>";

		return $xml;
	}	
	
	/**
	 * Saves a filter to the database
	 * @param $link
	 * @param $name
	 * @param $URI
	 * @param $username
	 * @param $data
	 * @return unknown_type
	 */
	public static function saveFilter(PDO $link, $id, $name, $URI, $username, $data)
	{
		if(preg_match('/^([ug])([0-9]+)/', $id, $matches))
		{
			$flag = $matches[1];
			$filter_id = $matches[2];
		}
		else
		{
			$flag = "u";
			$filter_id = "";
		}
				
		$filter = array();
		$filter['filter_id'] = $filter_id;
		$filter['filter_name'] = $name;
		$filter['URI'] = $URI;
		$filter['username'] = $username;
		$filter['filters'] = serialize($data);
		
		try
		{
			$filter_id = DAO::saveObjectToTable($link, "user_saved_filters", $filter);
		}
		catch(SQLException $e)
		{
			if($e->getCode() == DAO::MYSQLI_DUPLICATE_KEY)
			{
				// Trying to save a new filter that is identical in uri-name-username to an existing filter
				$sql = "SELECT filter_id FROM user_saved_filters WHERE filter_name='".addslashes($name)
					."' AND `URI`='".addslashes($URI)."' AND username='".addslashes($username)."'";
				$filter_id = DAO::getSingleValue($link, $sql);
				if($filter_id)
				{
					$filter['filter_id'] = $filter_id;
					DAO::saveObjectToTable($link, "user_saved_filters", $filter);
				}
			}
		}
		
		return $filter_id;
	}
	

}

?>