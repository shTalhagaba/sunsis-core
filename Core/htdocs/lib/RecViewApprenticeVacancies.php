<?php
class RecViewApprenticeVacancies extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		if(!isset($_SESSION[$key]))
		{
			$where = ' WHERE employer_id = "' . $id . '" ';

			$sql = <<<HEREDOC
SELECT
	vacancies.*
FROM
	vacancies 

$where
HEREDOC;

			$view = $_SESSION[$key] = new RecViewApprenticeVacancies();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 100, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY vacancy_reference'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY vacancy_reference DESC'));
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
			//echo $this->getViewNavigator('left');
			echo '<table class="table table-bordered resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
			echo '<tr><th>&nbsp;</th><th>Reference</th><th>Title</th><th>No. of Positions</th><th>Location</th><th>Closing Date</th><th>Interview From Date</th><th>Possible Start Date</th></tr>';
			echo '<tbody>';
			while($loc = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=rec_read_vacancy&id=' . $loc['id']);
				echo '<td><img src="/images/vacancy.jpg" border="0" /></td>';
				echo '<td>' . HTML::cell($loc['vacancy_reference']) . '</td>';
				echo '<td>' . HTML::cell($loc['vacancy_title']) . '</td>';
				echo '<td align="center">' . HTML::cell($loc['no_of_positions']) . '</td>';
				echo '<td>' . HTML::cell(DAO::getSingleValue($link, "SELECT CONCAT(COALESCE(full_name), ' (',COALESCE(`address_line_1`,''),' ',COALESCE(`address_line_2`,''),', ', COALESCE(`postcode`,''), ')') AS location FROM locations WHERE id = '" . $loc['location_id'] . "'")) . '</td>';
				echo isset($loc['closing_date']) ? '<td>' . Date::toShort($loc['closing_date']) . '</td>' : '<td></td>';
				echo isset($loc['interview_from_date']) ? '<td>' . Date::toShort($loc['interview_from_date']) . '</td>' : '<td></td>';
				echo isset($loc['possible_start_date']) ? '<td>' . Date::toShort($loc['possible_start_date']) . '</td>' : '<td></td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}
}
?>