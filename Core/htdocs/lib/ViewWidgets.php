<?php
class ViewWidgets extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			if($_SESSION['user']->isAdmin())
			{
				$sql = "SELECT * FROM widgets";
			}
			else
			{
				$identities = DAO::pdo_implode($_SESSION['user']->getIdentities());
				
				
				$sql = <<<HEREDOC
SELECT DISTINCT
	id,
	title,
	modified,
	created
FROM
	widgets INNER JOIN acl
	ON acl.resource_category='widget'
	AND acl.resource_id=widgets.id
	AND (acl.privilege='read' OR acl.privilege='write')
WHERE
	acl.ident IN ($identities)
HEREDOC;

			}
	

	
			$view = $_SESSION[$key] = new ViewWidgets();
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
			echo '<thead><tr><th>&nbsp;</th><th>Title</th><th>Last Modified</th><th>Created</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('/do.php?_action=read_widget&id=' . $row['id']);
				echo '<td align="left">&nbsp;</td>';
				echo '<td align="left" style="font-family: monospace">' . HTML::cell($row['title']) . '</td>';
				echo '<td align="left" style="font-family: monospace">' . HTML::cell($row['modified']) . '</td>';
				echo '<td align="left" style="font-family: monospace">' . HTML::cell($row['created']) . '</td>';
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