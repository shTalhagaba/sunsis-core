<?php
class ViewGroups extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{

            if($_SESSION['user']->isAdmin())
            {
                $where = '';
            }
            else
            {
                $provider_id = $_SESSION['user']->employer_id;
                $where = " and groups.courses_id = '$provider_id'" ;
            }


            // Create new view object
			$sql = <<<HEREDOC
SELECT id, title
,(SELECT CONCAT(firstnames,' ',surname) FROM users WHERE users.id = groups.`tutor`) AS tutor
,(SELECT CONCAT(firstnames,' ',surname) FROM users WHERE users.id = groups.`assessor`) AS assessor
,(SELECT CONCAT(firstnames,' ',surname) FROM users WHERE users.id = groups.`verifier`) AS verifier
,start_date,end_date,capacity, case status when 1 then 'Open' when 2 then 'Closed' when 3 then 'Cancelled' end as status FROM groups
where groups.provider_ref is null $where;
HEREDOC;

			$view = $_SESSION[$key] = new ViewGroups();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

            // Add view filters
            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'Open', null, 'WHERE groups.status=1'),
                2=>array(2, 'Close', null, 'WHERE groups.status=2'),
                3=>array(3, 'Cancelled', null, 'WHERE groups.status=3'));
            $f = new DropDownViewFilter('filter_record_status', $options, 1, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            // Date filters
            $dateInfo = getdate();
            $weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
            $timestamp = time()  - ((60*60*24) * $weekday);
            // Calculate the timestamp for the end of this week

            // Start Date Filter
            $format = "WHERE groups.start_date >= '%s'";
            $f = new DateViewFilter('start_date', $format, '');
            $f->setDescriptionFormat("From start date: %s");
            $view->addFilter($f);


            $format = "WHERE groups.start_date <= '%s'";
            $f = new DateViewFilter('end_date', $format, '');
            $f->setDescriptionFormat("To start date: %s");
            $view->addFilter($f);


            // Target date filter
            $format = "WHERE groups.end_date >= '%s'";
            $f = new DateViewFilter('target_start_date', $format, '');
            $f->setDescriptionFormat("From target date: %s");
            $view->addFilter($f);

            // Calculate the timestamp for the end of this week
            $timestamp = time() + ((60*60*24) * (7 - $weekday));

            $format = "WHERE groups.end_date <= '%s'";
            $f = new DateViewFilter('target_end_date', $format, '');
            $f->setDescriptionFormat("To target date: %s");
            $view->addFilter($f);

            $parent_org = $_SESSION['user']->employer_id;
            // Group Tutor
            if($_SESSION['user']->type==8)
                $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.tutor=',char(39),id,char(39)) FROM users where type=2 and employer_id = $parent_org";
            else
                $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.tutor=',char(39),id,char(39)) FROM users where type=2";
            $f = new DropDownViewFilter('filter_tutor', $options, null, true);
            $f->setDescriptionFormat("Group Tutor: %s");
            $view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Group Title ', null, 'ORDER BY title'),
				1=>array(2, 'Tutor', null, 'ORDER BY tutor'),
				2=>array(3, 'Start Date (asc)', null, 'ORDER BY start_date ASC'),
				3=>array(4, 'Start Date (desc)', null, 'ORDER BY start_date DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}
	

	public function render(PDO $link)
	{
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Group Title</th><th>Tutor</th><th>Assessor</th><th>Verifier</th><th>Start Date</th><th>End Date</th><th>Capacity</th><th>Status</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_course_group&id=' . $row['id']);
				echo '<td><img src="/images/group-icon-blue.png" border="0" /></td>';
                echo '<td align="left">' . HTML::cell($row['title']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['tutor']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['assessor']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['verifier']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['start_date']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['end_date']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['capacity']) . "</td>";
                echo '<td align="left">' . HTML::cell($row['status']) . "</td>";
				echo '</tr>';
			}
			echo '</tbody></table></div align="center">';
			echo $this->getViewNavigator();
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}

	public function exportToCSV(PDO $link)
	{
		$columns = "title,tutor,assessor,verifier,start_date,end_date,capacity,status";
		$statement = $this->getSQLStatement();
		$statement->removeClause('limit');//$statement->setClause()
		$st = $link->query($statement->__toString());
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="' . $this->getViewName() . '.csv"');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			$columns = explode(",", $columns);
			if($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				$line = '';
				foreach($row as $field=>$value)
				{
					if(in_array($field, $columns))
					{
						if(strlen($line) > 0)
						{
							$line .= ',';
						}
						$line .= '"' . str_replace('"', '""', $field) . '"';
					}
				}
				echo $line . "\r\n";
				$planned_reviews = array();
				do
				{
					$line = '';

					foreach($row as $field=>$value)	{
						if(in_array($field, $columns)) {
							if( strlen($line) > 0 )	{
								$line .= ',';
							}
							$value = trim($value);
							if( preg_match("/green-tick.gif/", $value) ) {
								$value = "Yes";
							}
							elseif( preg_match("/red-cross.gif/", $value) ) {
								$value = "No";
							}
							elseif( preg_match("/notstarted.gif/", $value) ) {
								$value = "Not Started";
							}
							elseif( preg_match("/exempt.gif/", $value) ) {
								$value = "Exempt";
							}
							elseif( preg_match("/warning-17.JPG/", $value) ) {
								$value = "Warning";
							}
							$value = str_replace(',', '', $value);
							$value = str_replace(array("\n", "\r"), '', $value);
							$value = str_replace("\t", '', $value);
							if(strlen($value)==10 AND $field != 'uln')
								$line .= str_replace('"', '""', $value);
							else
								$line .= '="' . str_replace('"', '""', $value) . '"';
						}
					}
					echo $line."\r\n";
				} while($row = $st->fetch(PDO::FETCH_ASSOC));
			}
		}
		else
		{
			throw new DatabaseException($link, $statement->__toString());
		}
	}

}
?>