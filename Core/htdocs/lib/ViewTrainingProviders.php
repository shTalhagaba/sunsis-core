<?php
class ViewTrainingProviders extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		
		if($_SESSION['user']->type==8 || $_SESSION['user']->type==13 || $_SESSION['user']->type==14)
			$where = ' and organisations.id=' . $_SESSION['user']->employer_id;
		elseif($_SESSION['user']->type==1 && !$_SESSION['user']->isAdmin())
			$where = ' and organisations.id=' . $_SESSION['user']->employer_id;
		else
			$where = '';
		
		if(!isset($_SESSION[$key]))
		{
			// re 22/08/2011 - splitting of training provider information by location
            $provider_query_join = 'LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1) LEFT JOIN tr on tr.provider_id = organisations.id ';
            $provider_group_by = 'organisations.id';
           	if ( DB_NAME == 'am_lewisham' || DB_NAME == 'am_demo2' ) {
                $provider_query_join = 'LEFT JOIN locations ON locations.organisations_id=organisations.id LEFT JOIN tr ON locations.id = tr.provider_location_id ';
            	$provider_group_by .= ', organisations.id, locations.id';
            }	
			$sql = <<<HEREDOC
	SELECT
		organisations.id, organisations.legal_name as name, locations.address_line_3, locations.full_name as department, locations.address_line_2,
		locations.address_line_4, locations.address_line_1, organisations.ukprn,
		COUNT(tr.id) AS training_records,
		(select count(*) from tr LEFT JOIN courses_tr on courses_tr.tr_id = tr.id LEFT JOIN courses on courses.id = courses_tr.course_id where courses.programme_type = 2 and tr.provider_id = organisations.id) as apps,
		(select count(*) from tr LEFT JOIN courses_tr on courses_tr.tr_id = tr.id LEFT JOIN courses on courses.id = courses_tr.course_id where courses.programme_type = 1 and tr.provider_id = organisations.id) as non_apps,
	CASE (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)
		WHEN 1 THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		WHEN 2 THEN "<img  src='/images/red-cross.gif' border='0'> </img>"
		WHEN 3 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `compliant`,	

	CASE 
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >30 THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <0 THEN "<img  src='/images/red-cross.gif' border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >=0 and (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <= 30 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `health_and_safety`

FROM
		organisations 
		$provider_query_join 
where 
		organisations.organisation_type = '3' $where group by $provider_group_by;
HEREDOC;
	
			$view = $_SESSION[$key] = new ViewTrainingProviders();
			$view->setSQL($sql);
	
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'Company name (asc)', null, 'ORDER BY legal_name'),
				1=>array(2, 'Company name (desc)', null, 'ORDER BY legal_name DESC'),
				2=>array(3, 'Location (asc), Provider name (asc)', null, 'ORDER BY address_line_3, address_line_2, legal_name'),
				3=>array(4, 'Location (desc), Provider name (desc)', null, 'ORDER BY address_line_3 DESC, address_line_2 DESC, legal_name DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'All', null, 'where organisations.id IS NOT NULL'),
				1=>array(2, 'Due more than 1 month', null, 'Where (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)>30'),
				2=>array(3, 'Due within 1 month', null, 'Where (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)<=30 and (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)>=0'),
				3=>array(4, 'Overdue', null, 'Where (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)<0'));
			$f = new DropDownViewFilter('by_health_safety_timeliness', $options, 1, false);
			$f->setDescriptionFormat("Health & Safety Timeliness: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, 'where organisations.id IS NOT NULL'),
				1=>array(2, 'Compliant', null, 'where (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)=1'),
				2=>array(3, 'Non-complient', null, 'where (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)=2'),
				3=>array(4, 'Outstaning action', null, 'where (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)=3'));
			$f = new DropDownViewFilter('by_health_safety_compliance', $options, 1, false);
			$f->setDescriptionFormat("Health & Safety compliance: %s");
			$view->addFilter($f);
			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link, $columns)
	{

		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator();
			echo '<div class="table-responsive"><table id="tblTrainingProviders" class="table table-bordered">';
			echo '<thead><tr><th>&nbsp;</th>';
			
			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column))) . '</th>';
			}

			echo '</tr></thead><tbody>';
			while($row = $st->fetch())
			{	
				echo HTML::viewrow_opening_tag('/do.php?_action=read_trainingprovider&amp;id=' . $row['id']);
				echo '<td><span class="fa fa-building"></span> </td>';

				foreach($columns as $column)
				{
					if($column=='name')
						echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
					else
						echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}

				echo '</tr>';
			}
		
			echo '</tbody></table></div>';
			echo $this->getViewNavigator();
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		}
}
?>