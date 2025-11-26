<?php
class ViewScreen1 extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		//if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
	SELECT * FROM tabel1;
HEREDOC;

			$view = $_SESSION[$key] = new ViewScreen1();
			$view->setSQL($sql);


			$f = new TextboxViewFilter('filter_cps', "WHERE cps LIKE '%s%%'", null);
			$f->setDescriptionFormat("CPS Starts with: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_nato', "WHERE nato LIKE '%s%%'", null);
			$f->setDescriptionFormat("NATO Starts with: %s");
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
				0=>array(1, 'Date Received (asc)', null, 'ORDER BY date_received'),
				1=>array(2, 'Date Received (desc)', null, 'ORDER BY date_received DESC'),
				2=>array(3, 'CPS (asc)', null, 'ORDER BY cps'),
				3=>array(4, 'CPS (desc)', null, 'ORDER BY cps desc'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
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
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="4">';
			echo '<thead><tr>';

			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '</tr></thead><tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_screen1&amp;cps=' . $row['cps']);
				//echo '<tr class="Data"><td>&nbsp;</td>';
				foreach($columns as $column)
				{
					if($column == 'multi_part')
					{
						if($row['multi_part'] == 1)
							echo '<td align="left">Yes</td>';
						else
							echo '<td align="left">No</td>';
					}
					elseif($column == 'br_640_in')
					{
						if($row['br_640_in'] == 1)
							echo '<td align="left">Yes</td>';
						else
							echo '<td align="left">No</td>';
					}
					elseif($column == 'br_640_out')
					{
						if($row['br_640_out'] == 1)
							echo '<td align="left">Yes</td>';
						else
							echo '<td align="left">No</td>';
					}
					elseif($column == 'contract_type')
					{
						if($row['contract_type'] == 1)
							echo '<td align="left">Contract</td>';
						elseif($row['contract_type'] == 2)
							echo '<td align="left">Non-contract</td>';
						elseif($row['contract_type'] == 3)
							echo '<td align="left">Permanent</td>';
					}
					else
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
			}
			echo $this->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
}
?>