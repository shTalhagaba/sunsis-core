<?php
class ViewUnsuccessfulLogins extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
            // Create new view object
		    $sql = <<<SQL
SELECT
    DATE_FORMAT(logins_unsuccessful.date, '%d/%m/%Y %H:%i:%s') AS date,
    username AS offered_username,
    remote_address,
    user_agent
FROM
    logins_unsuccessful
SQL;
			$view = $_SESSION[$key] = new ViewUnsuccessfulLogins();
			$view->setSQL($sql);

			// Add view filters
			$format = "WHERE logins_unsuccessful.`date` > SUBDATE('%s', 1)";
			$f = new DateViewFilter('start_date', $format, null);
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			$format = "WHERE logins_unsuccessful.`date` < ADDDATE('%s',1)";
			$f = new DateViewFilter('end_date', $format, date('d/m/Y'));
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);

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
				0=>array(1, 'Date (asc)', null, 'ORDER BY logins_unsuccessful.`date` ASC'),
				1=>array(2, 'Date (desc)', null, 'ORDER BY logins_unsuccessful.`date` DESC'),
				2=>array(3, 'Username (asc), Date (asc)', null, 'ORDER BY username ASC, logins_unsuccessful.`date` ASC'),
				3=>array(4, 'Username (asc), Date (desc)', null, 'ORDER BY username ASC, logins_unsuccessful.`date` DESC'),
				4=>array(5, 'Username (desc), Date (asc)', null, 'ORDER BY username DESC, logins_unsuccessful.`date` ASC'),
				5=>array(6, 'Username (desc), Date (desc)', null, 'ORDER BY username DESC, logins_unsuccessful.`date` DESC')
			);
			$f = new DropDownViewFilter('order_by', $options, 2, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}


	public function render(PDO $link)
	{
//if(SOURCE_HOME || SOURCE_BLYTHE_VALLEY) pre($this->getSQL());
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div class="table-responsive"><table id="tblFailedLogins" class="table table-bordered">';
			echo <<<HEREDOC
	<thead>
	<tr>
		<th>Date</th>
		<th>Offered Username</th>
		<th>Remote Address</th>
		<th>User Agent</th>
	</tr>
	</thead>
HEREDOC;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<tr>';
				echo '<td>' . $row['date'] . '</td>';
				echo '<td><code>' . HTML::cell($row['offered_username']) . '</code></td>';
				echo '<td><code>' . HTML::cell($row['remote_address']) . '</code></td>';
				echo '<td><code>' . HTML::cell($row['user_agent']) . '</code></td>';
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