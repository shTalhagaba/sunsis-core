<?php

class FundingPeriod
{
	private $periods = array();
	private $link = null;

	function __construct($link, $year)
	{
		$this->link = $link;
		$sql = "
			SELECT 
				contract_year, submission, last_submission_date
			FROM
				central.lookup_submission_dates
			WHERE
				(contract_year = '" . intval($year) . "' 
					OR 
				(contract_year = '" . (intval($year) - 1) . "' AND submission = 'W12'))
					AND
				submission <> 'W13'
			ORDER BY
				contract_year, submission
		;";
		//echo $sql; die;

		$st = $this->link->query($sql);

		if ($st) {
			$periods = array();
			while ($row = $st->fetch()) {
				$period = intval(substr($row['submission'], 1));

				// calculate start timestamp
				//echo $period . ' (' . $row['contract_year'] . ') :: ' . $row['last_submission_date'] . ' :: Month = ' . intval(substr($row['last_submission_date'], 5, 7)) . ' Day = ' .  intval(substr($row['last_submission_date'], 9, 11)) . ' Year = ' . intval(substr($row['last_submission_date'], 0, 4)) . '<br />';
				$periods[$row['contract_year']]["$period"]['end_date'] = mktime(23, 59, 59, intval(substr($row['last_submission_date'], 5, 7)), intval(substr($row['last_submission_date'], 9, 11)), intval(substr($row['last_submission_date'], 0, 4)));
			}
		}

		if (!isset($periods["$year"])) {
			throw new Exception('Census start/end dates as well as submission start/end dates need to be added for ' . $year . ' to the lookup_submission_dates table');
		}
		foreach ($periods["$year"] as $submission => $info) {
			if ($submission == 1) {
				if (isset($periods[$year - 1]['12']['end_date'])) {
					$this->periods[$submission]['start_date'] = $periods[$year - 1]['12']['end_date'] + 1;
				} else {
					// fallback: maybe use the same year's start date or a default
					$this->periods[$submission]['start_date'] = $info['end_date'];
				}
			} else {
				if (isset($this->periods[$submission - 1]['end_date'])) {
					$this->periods[$submission]['start_date'] = $this->periods[$submission - 1]['end_date'] + 1;
				} else {
					// fallback if previous submission is missing
					$this->periods[$submission]['start_date'] = $info['end_date'];
				}
			}

			$this->periods[$submission]['end_date'] = $info['end_date'];
		}
	}

	function getStart($period)
	{
		return $this->periods["$period"]['start_date'];
	}

	function getEnd($period)
	{
		return $this->periods["$period"]['end_date'];
	}

	function getCensusStart($period)
	{
		if (!isset($this->periods[$period]['start_date'])) {
			error_log("Missing start_date for period $period");
			return null;
		}

		$start = $this->periods[$period]['start_date'];
		return mktime(0, 0, 0, date('n', $start), 1, date('Y', $start));
	}

	function getCensusEnd($period)
	{
		return mktime(0, 0, 0, date('n', $this->periods["$period"]['start_date']), date('t', $this->periods["$period"]['start_date']), date('Y', $this->periods["$period"]['start_date']));
	}
}