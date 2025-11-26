<?php
class ViewAwardingBodies extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if($_SESSION['user']->isAdmin())
			$where = "";
		elseif($_SESSION['user']->type==17)
			$where = " and organisations.id = " . $_SESSION['user']->employer_id;
		else 
			$where = "";
			
		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
	SELECT
		organisations.*, locations.address_line_2, locations.address_line_3, locations.`postcode`
	FROM
		organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
	where organisations.organisation_type like '%8%' $where;
HEREDOC;
	
			$view = $_SESSION[$key] = new ViewAwardingBodies();
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
				0=>array(1, 'Abbreviation (asc)', null, 'ORDER BY short_name'),
				1=>array(2, 'Company name (asc)', null, 'ORDER BY legal_name'),
				2=>array(3, 'Company name (desc)', null, 'ORDER BY legal_name DESC'),
				3=>array(4, 'Location (asc), Provider name (asc)', null, 'ORDER BY address_line_3, address_line_2, legal_name'),
				4=>array(5, 'Location (desc), Provider name (desc)', null, 'ORDER BY address_line_3 DESC, address_line_2 DESC, legal_name DESC'));
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
			echo '<div class="table-responsive"><table id="tblABodies" class="table table-bordered">';
			echo '<thead><tr><th>&nbsp;</th><th>Abbreviation</th><th>Legal Name</th><th>Trading Name</th><th>Town</th><th>Locality</th><th>Postcode</th><th>URL</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('/do.php?_action=read_awarding_body&id=' . $row['id']);
				echo '<td><span class="fa fa-building-o"></span> </td>';
				echo '<td>' . HTML::cell(strtoupper($row['short_name'])) . '</td>';
				echo '<td>' . HTML::cell($row['legal_name']) . '</td>';
				echo '<td>' . HTML::cell($row['trading_name']) . '</td>';
				echo '<td>' . HTML::cell($row['address_line_3']) . '</td>';
				echo '<td>' . HTML::cell($row['address_line_2']) . '</td>';
				echo '<td>' . HTML::cell($row['postcode']) . '</td>';
				if(isset($row['company_number']) && $row['company_number'] != '')
				{
					$url = DAO::getSingleValue($link, "SELECT website FROM central.lookup_awarding_bodies WHERE registration_number = '" . $row['company_number'] . "'");
					echo '<td><a target="_blank" href="' . $url . '">' . $url . '</a></td>';
				}
				else
					echo '<td>&nbsp;</td>';
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