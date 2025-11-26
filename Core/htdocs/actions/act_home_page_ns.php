<?php
class home_page_ns implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=home_page_ns", "Home Page");

		$_SESSION['current_submission_year'] = (!isset($_SESSION['current_submission_year']) || $_SESSION['current_submission_year'] == '' ) ?
			DAO::getSingleValue($link, "SELECT contract_year FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date LIMIT 1;") :
			$_SESSION['current_submission_year'];

		$current_submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");

		$start_stats_previous_3_months = HomePageV2::getStartsGraphs($link);

		$on_programme_stats = HomePageV2::getOnProgrammeStats($link);

		$overstayers_by_expected_month = HomePageV2::getOverstayersByExpectedMonthGraph($link);

		$withdrawals_in_current_submission_year = HomePageV2::getWithdrawalsGraph($link);

		$completions_due_by_expected_month = HomePageV2::getUpcomingCompletionsGraph($link);

		include_once('tpl_home_page_ns.php');
	}
}