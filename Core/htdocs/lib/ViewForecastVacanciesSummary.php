<?php
class ViewForecastVacanciesSummary extends View
{

	public static function getInstance(PDO $link, $region, $location, $brm, $employer, $sector, $forecast_start_date)
	{
		$key = 'view_'.__CLASS__;

		//if(!isset($_SESSION[$key]))
		{
			$select = '';
			$group_by = '';

			if($region)
			{
				$select = ' (SELECT description FROM lookup_vacancy_regions WHERE id = vacancies.region) AS region, ';
				$group_by = ' GROUP BY region ';
			}

			$sql = <<<SQL
SELECT
	$select
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Open'), 1, 0)) AS open_vacancies,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Selection'), 1, 0)) AS selection_vacancies,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Client Interview'), 1, 0)) AS client_interview_vacancies,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Offer Pending'), 1, 0)) AS offer_pending_vacancies,
	SUM(IF(vacancies.`status` = (SELECT id FROM lookup_vacancy_status WHERE description = 'Induction Pending'), 1, 0)) AS induction_pending_vacancies

FROM
	vacancies

$group_by

SQL;

// Create new view object

			$view = $_SESSION[$key] = new ViewForecastVacanciesSummary();
			$view->setSQL($sql);


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

			foreach($columns as $column)
			{
				echo '<th class="bottomRow" style="font-size:80%; color:#555555">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '</tr></thead>';

			echo '<tbody>';


			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				echo '<tr>';
				foreach($columns as $column)
				{
					if($column == 'date_submitted')
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':Date::toShort($row[$column])):'&nbsp') . '</td>';
					else
						echo '<td align="left">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
				echo '</tr>';
			}//end while
			echo '</tbody></table>';
			echo $this->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}



}
?>