<?php
class ViewVacancySummary extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			//if($_SESSION['user']->isAdmin())
			//{

				$sql = <<<HEREDOC
SELECT
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Open'), 1, 0)) AS `Open`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Selection'), 1, 0)) AS `Selection`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Client Interview'), 1, 0)) AS `ClientInterview`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Offer Pending'), 1, 0)) AS `OfferPending`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Induction Pending'), 1, 0)) AS `InductionPending`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Completed'), 1, 0)) AS `Completed`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Close'), 1, 0)) AS `Close`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Filled'), 1, 0)) AS `Filled`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Hold'), 1, 0)) AS `Hold`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Live'), 1, 0)) AS `Live`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Lost'), 1, 0)) AS `Lost`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Stop'), 1, 0)) AS `Stop`,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Withdrawn'), 1, 0)) AS `Withdrawn`,
	vacancies.*
FROM
	vacancies;


HEREDOC;
			//}

			// Create new view object
			$view = $_SESSION[$key] = new ViewVacancySummary();
			$view->setSQL($sql);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(300,300,null,null),
				5=>array(400,400,null,null),
				6=>array(500,500,null,null),
				7=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'BRM', null, 'GROUP BY vacancies.brm'),
				1=>array(1, 'Region', null, 'GROUP BY vacancies.region'),
				2=>array(2, 'Employer', null, 'GROUP BY vacancies.employer_id'));
			$f = new DropDownViewFilter('filter_group_by', $options, 0, true);
			$f->setDescriptionFormat("Group By: %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}

	public function render(PDO $link)
	{
		$group_by = $this->getFilterValue('filter_group_by');

		if($group_by == 0)
			$group_by_field = "Business Resource Manager";
		elseif($group_by == 1)
			$group_by_field = "Region";
		elseif($group_by == 2)
			$group_by_field = "Manager";

		echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		echo '<thead><tr><th class="topRow">Group By</th><th class="topRow" colspan="13">Vacancies</th></tr>';
		echo '<tr><th class="bottomRow">' . $group_by_field . '</th><th class="bottomRow">Open</th><th class="bottomRow">Selection</th><th class="bottomRow">Client Interview</th><th class="bottomRow">Offer Pending</th><th class="bottomRow">Induction Pending</th><th class="bottomRow">Completed</th>
				<th>Close</th><th>Filled</th><th>Hold</th><th>Live</th><th>Lost</th><th>Stop</th><th>Withdrawn</th></tr></thead>';
		echo '<tbody>';

		$st = $link->query($this->getSQL());

		if($st)
		{
			while($row = $st->fetch())
			{
				if($group_by == 0)
					$firstColumnValue = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE username = '" . $row['brm'] . "'");
				elseif($group_by == 1)
					$firstColumnValue = DAO::getSingleValue($link, "SELECT description FROM  lookup_vacancy_regions WHERE id = '" . $row['region'] . "'");
				elseif($group_by == 2)
					$firstColumnValue = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $row['employer_id']);

				if($firstColumnValue == '' OR is_null($firstColumnValue))
					$firstColumnValue = 'NULL';
				echo '<tr>';
				echo '<td align="left">' . HTML::cell($firstColumnValue) . "</td>";
				echo '<td align="center">' . HTML::cell($row['Open']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['Selection']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['ClientInterview']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['OfferPending']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['InductionPending']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['Completed']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['Close']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['Filled']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['Hold']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['Live']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['Lost']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['Stop']) . "</td>";
				echo '<td align="center">' . HTML::cell($row['Withdrawn']) . "</td>";
				echo '</tr>';

			}
			echo '</tbody></table></div align="center">';
		}
	}

}
?>
