<?php
class RecViewQuestions extends View
{
	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$sql = new SQLStatement('
				SELECT
					rec_questions.id,
					rec_questions.description,
					rec_questions.type,
					lookup_sector_types.id AS sector_id,
					lookup_sector_types.description AS sector_description,
					rec_questions.type
				FROM
					rec_questions
					LEFT JOIN lookup_sector_types ON rec_questions.sector_id = lookup_sector_types.id
			');

			$view = $_SESSION[$key] = new RecViewQuestions();
			$view->setSQL($sql->__toString());

			$f = new TextboxViewFilter('filter_description', "WHERE questions.description LIKE '%s%%'", null);
			$f->setDescriptionFormat("Description: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show All', null, null),
				1=>array(1, 'General', null, 'WHERE rec_questions.type = 0 '),
				2=>array(2, 'Sector Specific', null, 'WHERE rec_questions.type = 1 ')
			);
			$f = new DropDownViewFilter('filter_type', $options, 0, false);
			$f->setDescriptionFormat("Type: %s");
			$view->addFilter($f);

			$options = "SELECT id, description, NULL, CONCAT('WHERE rec_questions.sector_id=',char(39),id,char(39)) FROM lookup_sector_types ORDER BY description ASC;";
			$f = new DropDownViewFilter('filter_sector', $options, null, true);
			$f->setDescriptionFormat("Sector: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}

	public function render(PDO $link, $columns)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead>';
			echo '<tr><th>&nbsp;</th><th class="topRow">ID</th><th class="topRow">Description</th><th class="topRow">Type</th><th class="topRow">Sector</th></tr>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('/do.php?_action=rec_edit_question&id=' . $row['id'] . '&sector_id=' . $row['sector_id']);
				echo '<td align="center"><img src="/images/question_icon.jpg" alt="" width="20" height="20"></td>';
				echo '<td align="center">' . htmlspecialchars((string)$row['id']) . '</td>';
				echo '<td align="center">' . htmlspecialchars((string)$row['description']) . '</td>';
				if($row['type'] == '1')
					echo '<td align="center">Sector Specific</td>';
				else
					echo '<td align="center">General</td>';
				echo '<td align="center">' . htmlspecialchars((string)$row['sector_description']) . '</td>';
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