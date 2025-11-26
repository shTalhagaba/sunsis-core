<?php
class ViewRegisterNotes extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT * 
FROM register_entry_notes 
LEFT JOIN register_entries ON register_entries.id = register_entry_notes.register_entries_id
where 
	register_entries.pot_id = $id
HEREDOC;

			$view = $_SESSION[$key] = new ViewRegisterNotes();
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
	
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			//echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Date</th><th>Description</th></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				//echo HTML::viewrow_opening_tag('do.php?_action=edit_learner_crm_note&id=' . rawurlencode($row['id']) . '&tr_id=' . rawurlencode($row['tr_id']));
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['modified']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['note']) . "</td>";
				echo '</tr>';
			}
			echo '</tbody></table></div align="left">';
			//echo $this->getViewNavigator('left');
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>