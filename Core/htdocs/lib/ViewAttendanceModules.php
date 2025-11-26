<?php
class ViewAttendanceModules extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$where = "";
			if(!$_SESSION['user']->isAdmin())
				$where = " WHERE attendance_modules.provider_id = '{$_SESSION['user']->employer_id}' ";

			// Create new view object
			$sql = <<<HEREDOC
SELECT
	*
FROM
	attendance_modules
$where

HEREDOC;

			$view = $_SESSION[$key] = new ViewAttendanceModules();
			$view->setSQL($sql);

			$f = new TextboxViewFilter('filter_title', "WHERE attendance_modules.module_title LIKE '%s%%'", null);
			$f->setDescriptionFormat("Module Title: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_qualification_id', "WHERE replace(attendance_modules.qualification_id,'/','') LIKE replace('%%%s%%','/','') ", null);
			$f->setDescriptionFormat("QAN: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_qualification_title', "WHERE attendance_modules.qualification_title LIKE '%%%s%%' ", null);
			$f->setDescriptionFormat("Qualification Title: %s");
			$view->addFilter($f);

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
				0=>array(1, 'Module Title (asc)', null, 'ORDER BY module_title'),
				1=>array(2, 'Qualification Title (asc)', null, 'ORDER BY qualification_title'),
				2=>array(3, 'Learning Hours (asc)', null, 'ORDER BY hours ASC'),
				3=>array(4, 'Learning Hours (desc)', null, 'ORDER BY hours DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}


	public function render(PDO $link)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th>Module Title</th><th>Qualification Number</th><th>Qualification Title</th><th>Learning Hours</th><th>Training Provider</th></tr></thead>';

			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_attendance_module&id=' . $row['id']);
				echo '<td><img src="/images/group-icon-blue.png" border="0" /></td>';
				echo '<td>' . HTML::cell($row['module_title']) . "</td>";
				echo '<td>' . HTML::cell($row['qualification_id']) . "</td>";
				echo '<td>' . HTML::cell($row['qualification_title']) . "</td>";
				echo '<td>' . HTML::cell($row['hours']) . "</td>";
				echo '<td>' . HTML::cell(DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $row['provider_id'])) . "</td>";
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