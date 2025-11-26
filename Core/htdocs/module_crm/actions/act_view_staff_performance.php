<?php
class view_staff_performance implements IAction
{
	public $ss = null;

	function convertToHoursMins($time, $format = '%02d:%02d')
	{
		if ($time < 1) {
			return;
		}
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		return sprintf($format, $hours, $minutes);
	}

	public function execute(PDO $link)
	{
		//pr($_REQUEST);

		$_m = (int)date('m');
		//$_m = (($_m >= 1) && ($_m <= 7)) ? $_m += 5 : $_m -= 7;

		$filterYear = isset($_REQUEST['filterYear']) ? $_REQUEST['filterYear'] : date('Y');
		$startMonth = isset($_REQUEST['startMonth']) ? $_REQUEST['startMonth'] : 1;
		$endMonth = isset($_REQUEST['endMonth']) ? $_REQUEST['endMonth'] : $_m;

		$dates = $this->getDates($link, $filterYear, $startMonth, $endMonth);
		//pr($dates);
		$sql= <<<SQL
SELECT DISTINCT created_by FROM
(
SELECT DISTINCT crm_enquiries.`created_by` FROM crm_enquiries
WHERE crm_enquiries.created BETWEEN '$dates->start_date' AND '$dates->end_date'
UNION ALL
SELECT DISTINCT crm_leads.`created_by` FROM crm_leads
WHERE crm_leads.created BETWEEN '$dates->start_date' AND '$dates->end_date'
UNION ALL
SELECT DISTINCT crm_opportunities.`created_by` FROM crm_opportunities
WHERE crm_opportunities.created BETWEEN '$dates->start_date' AND '$dates->end_date'
) a
;
SQL;
		$staff = DAO::getSingleColumn($link, $sql);

		include('tpl_view_staff_performance.php');
	}

	private function getDates(PDO $link, $contract_year, $start_month = '1', $end_month = '1')
	{
		$month = 1;
		$start_date = '';
		$end_date = '';
		for($i = 1; $i <= 12; $i++)
		{
			$start_date_of_month = new Date($contract_year . '-'.$month.'-01');
			$last_date_of_month = DAO::getSingleValue($link, "SELECT LAST_DAY('{$start_date_of_month->formatMySQL()}')");
			$last_date_of_month = new Date($last_date_of_month);
			if($month == 12)
			{
				$month = 0;
				$contract_year++;
			}
			$month++;
			$start_date = $i == $start_month ? $start_date_of_month->formatMySQL() : $start_date;
			$end_date = $i == $end_month ? $last_date_of_month->formatMySQL() : $end_date;
		}
		return (object)['start_date' => $start_date, 'end_date' => $end_date];
	}
}