<?php
class ViewProviderQualifications extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	provider_qualifications.*, qualifications.*
FROM
	provider_qualifications	
INNER JOIN qualifications on qualifications.id = provider_qualifications.qualification_id and qualifications.internaltitle = provider_qualifications.internaltitle 
where 
	org_id = $id
HEREDOC;

			$view = $_SESSION[$key] = new ViewProviderQualifications();
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
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator('left');
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Internal Title</th><th>Type</th><th>Level</th><th>QAN</th><th>LAD</th><th>Total Units</th></tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				//echo HTML::viewrow_opening_tag('do.php?_action=edit_crm_note&id=' . rawurlencode($row['id']) . '&organisations_id=' . rawurlencode($row['organisation_id']) . '&organisation_type=' . $type);
				echo '<td><img src="/images/rosette.gif" /></td>';
				echo '<td align="left">' . HTML::cell($row['internaltitle']) . "</td>";
				echo '<td align="left">' . HTML::cell($row['qualification_type']) . "</td>";
				echo '<td align="left">' . htmlspecialchars($row['level']) . "</td>";
				echo '<td align="left">' . htmlspecialchars($row['id']) . "</td>";
				echo '<td align="left">' . htmlspecialchars($row['lsc_learning_aim']) . "</td>";
				echo '</tr>';
			}
			echo '</tbody></table></div>';
			echo $this->getViewNavigator('left');
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>