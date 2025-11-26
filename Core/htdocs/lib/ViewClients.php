<?php
class ViewClients extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
	SELECT
		organisations.*, locations.address_line_2, locations.address_line_3
	FROM
		organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
	where organisations.organisation_type like '%1%';
HEREDOC;
	
			$view = $_SESSION[$key] = new ViewClients();
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
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="2">';
			echo '<thead><tr><th>&nbsp;</th><th>Legal Name</th><th>Town</th><th>Locality</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('/do.php?_action=read_client&id=' . $row['id']);
				echo '<td><img src="/images/blue-building.png" border="0" /></td>';
				echo '<td align="left">' . HTML::cell($row['legal_name']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['address_line_3']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['address_line_2']) . '</td>';
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
}
?>