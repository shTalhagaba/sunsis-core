<?php
class ImportContracts extends View
{

	public static function getInstance($link, $contract_id)
	{
		$key = 'view'.__CLASS__;
		
		if(true)
		{

		$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = '$contract_id'");	
		$contract_year -=1;
		// Create new view object
			$sql = <<<HEREDOC
SELECT
	contracts.*, organisations.legal_name,
	(select count(distinct L03) from ilr where ilr.contract_id = contracts.id) as ilrs 
FROM
	contracts
	LEFT JOIN organisations on organisations.id = contracts.contract_holder
WHERE
	contracts.contract_year = $contract_year
HEREDOC;

			
			$view = $_SESSION[$key] = new ImportContracts();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'Contract Year (desc), title (asc)', null, 'ORDER BY contract_year desc, title asc'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY qualification_type DESC, level DESC'));
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
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>&nbsp;</th><th>Title</th><th>Contract Holder</th><th>Year</th><th>ILRs</th></tr></thead>';
			$counter=1;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<tr><th>&nbsp;</td>';
				echo '<td><input id="button'.$counter++.'" type="checkbox" title="' . $row['title'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
				echo '<td>' . $row['title'] . '</td>';
				echo '<td>' . $row['legal_name'] . '</td>';
				echo '<td align=center>' . $row['contract_year'] . '</td>';
				echo '<td align=center>' . $row['ilrs'] . '</td>';
				echo '</tr>';
					
				$qid = $row['id'];
				
			}
			echo '</tbody></table></div align="left">';
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>