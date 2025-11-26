<?php
class ViewAssessorDiary extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC

			
HEREDOC;

			$view = $_SESSION[$key] = new ViewAssessorDiary();
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
	
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link, $type)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Date</th><th>Contact Initiator</th><th> Name of Person </th><th>Position</th><th>Type of Contact</th><th>Subject</th><th>Agreed Action</th></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=edit_crm_note&id=' . rawurlencode($row['id']) . '&organisations_id=' . rawurlencode($row['organisation_id']) . '&organisation_type=' . $type);
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['date']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['contact_initiator']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['name_of_person']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['position']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['type_of_contact']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['subject']) . "</td>";
				echo '<td align="left">' . htmlspecialchars((string)$row['agreed_action']) . "</td>";
				echo '</tr>';
			}
			echo '</tbody></table></div align="left">';
			echo $this->getViewNavigator('left');
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>