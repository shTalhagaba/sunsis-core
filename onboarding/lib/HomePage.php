<?php

class HomePage
{
	public static function getStartsGraphs(PDO $link)
	{
		$frameworks_titles = DAO::getSingleColumn($link, "SELECT frameworks.title FROM frameworks WHERE frameworks.id IN (SELECT DISTINCT ob_tr.framework_id FROM ob_tr) ORDER BY frameworks.title");
		$previous_6_months = [];
		for($i = 5; $i >= -1; $i--)
		{
			$month_name = date("F Y",strtotime("-{$i} Months"));

			foreach($frameworks_titles AS $frameworks_title)
			{
				$previous_6_months[$month_name][$frameworks_title] = [];
			}
		}

		$start_date = date("Y",strtotime("-2 Months")) . "-" . date("m",strtotime("-5 Months")) . "-01";

		$view = ViewTrainingRecords::getInstance($link);

		$filters = [
			'_reset' => 1,
			'ViewTrainingRecords_' . View::KEY_PAGE_SIZE => 0,
			'ViewTrainingRecords_from_practical_period_start_date' => $start_date,
			'ViewTrainingRecords_filter_status' => null,
		];
		$view->refresh($link, $filters);

		$sql = $view->getSQLStatement()->__toString();
		$st = DAO::query($link, $sql);
		if($st)
		{
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				$month_name = $row['_start_month_year'];
				$standard_name = $row['standard'];
				if( isset($previous_6_months[$month_name][$standard_name]) )
				{
					$previous_6_months[$month_name][$standard_name][] = $row['system_id'];
				}
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		return $previous_6_months;
	}

}
