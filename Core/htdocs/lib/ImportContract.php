<?php
class ImportContract extends View
{

	public static function getInstance($contract_id)
	{
		$key = 'view_'.__CLASS__;
		
//		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
SELECT
	contracts.*, organisations.legal_name
FROM
	contracts INNER JOIN organisations on contracts.training_provider = organisations.id
WHERE
	contracts.id!='$contract_id'
HEREDOC;
	
			$view = $_SESSION[$key] = new ImportContract();
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
				0=>array(1, 'Funding Body (asc), Contract Type (asc)', null, 'ORDER BY funding_body, contract_type'),
				1=>array(2, 'Funding Body (desc), Contract Type (desc)', null, 'ORDER BY funding_body DESC, contract_type DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
			
		}

		return $_SESSION[$key];
	}
	

	public function render(PDO $link, $target)
	{
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>Title</th><th>Training Provider</th><th>Funding Source</th><th>Contract Type</th><th>Start Date</th><th>End Date</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('/do.php?_action=copy_contract&source=' . $row['id'] . '&target=' . $target);
				echo '<td align="left">' . HTML::cell($row['title']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['legal_name']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['funding_body']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['contract_type']) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toShort($row['start_date'])) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toShort($row['end_date'])) . '</td>';
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