<?php
class edit_forecast_learners implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->add($link, "do.php?_action=edit_forecast_learners", "Edit Forecast about Learners");


		include('tpl_edit_forecast_learners.php');
	}

	private function generateForecastLearnersTable(PDO $link)
	{
		$this->prepareMonthsArray();
		$html = "";
		$resultSet = DAO::getResultset($link, "SELECT * FROM forecast_learners WHERE username = '" . $_SESSION['user']->username . "'", DAO::FETCH_ASSOC);
		$learner_types = DAO::getResultset($link, "SELECT * FROM lookup_learner_types", DAO::FETCH_ASSOC);
		echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		$html .= '<thead><tr><th>Learner Type</th><th>Year</th>';
		foreach($this->monthsArray AS $month)
		{
			$html .= '<th>' . $month . '</th>';
		}
		$html .= '</tr></thead>';
		$html .= '<tbody>';

		if(count($resultSet) == 0)
		{
			foreach($learner_types AS $type)
			{
				$html .= '<tr>';
				$html .= '<td align="center">' . HTML::cell($type['description']) . '</td>';
				$html .= '<td align="center"><input type="hidden" name="year" value="2015" />2015</td>';
				foreach($this->monthsArray AS $month)
				{
					$month_short_name = strtolower($month);
					$month_short_name = substr($month_short_name, 0, 3);
					$html .= '<td align="left">' . HTML::textbox($type['id'] . '_' . $month_short_name, '', 'size = "5"') . '</td>';
				}
				$html .= '</tr>';
			}
		}
		else
		{
			foreach($resultSet AS $record)
			{
				$inner_html = "";
				$inner_html .= '<tr>';
				$inner_html .= '<td align="center">' . HTML::cell(DAO::getSingleValue($link, "SELECT description FROM lookup_learner_types WHERE id = " . $record['type'])) . '</td>';
				$inner_html .= '<td align="center"><input type="hidden" name="year" value="2015" />2015</td>';
				foreach($this->monthsArray AS $month)
				{
					$month_short_name = strtolower($month);
					$month_short_name = substr($month_short_name, 0, 3);
					$inner_html .= '<td align="left">' . HTML::textbox($record['type'] . '_' . $month_short_name, $record[$month_short_name], 'size = "5"') . '</td>';
				}
				$inner_html .= '</tr>';
				$html .= $inner_html;
			}
		}

		$html .= '</tbody></table></div>';

		return $html;
	}

	private function prepareMonthsArray()
	{
		$this->monthsArray = array();

		$this->monthsArray[] = "January";
		$this->monthsArray[] = "February";
		$this->monthsArray[] = "March";
		$this->monthsArray[] = "April";
		$this->monthsArray[] = "May";
		$this->monthsArray[] = "June";
		$this->monthsArray[] = "July";
		$this->monthsArray[] = "August";
		$this->monthsArray[] = "September";
		$this->monthsArray[] = "October";
		$this->monthsArray[] = "November";
		$this->monthsArray[] = "December";

	}// end function prepareMonthsArray()

	private $monthsArray = null;
}
?>