<?php
class ViewProviders extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	organisations.*, locations.address_line_2, locations.address_line_3
FROM
	organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
WHERE
	organisation_type like "%3%";
HEREDOC;
			
			$view = $_SESSION[$key] = new ViewProviders();
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
	
			$options = array(
			//	0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY title, level'),
			//	1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY title DESC, level DESC'));
				0=>array(1, '', null, 'ORDER BY trading_name'),
				1=>array(2, '', null, 'ORDER BY trading_name DESC'));
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
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Town</th><th> Locality </th><th> Provider </th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				// $sector = $row['sector'];
				//$que = "select description from lookup_sector_types where id='$sector'";
				//$sector_title = trim(DAO::getSingleValue($link, $que));

				echo HTML::viewrow_opening_tag('do.php?_action=read_provider&id=' . rawurlencode($row['id']));
				echo '<td><img src="/images/blue-building.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['address_line_3']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['address_line_2']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['legal_name']) . "</td>";
				//echo '<td align="left">' . htmlspecialchars((string)$sector_title) . "</td>";
				//echo '<td align="left">' . htmlspecialchars((string)$row['comments']) . "</td>";
				//echo '<td align="left">' . htmlspecialchars((string)$row['lsc_learning_aim']) . "</td>";
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