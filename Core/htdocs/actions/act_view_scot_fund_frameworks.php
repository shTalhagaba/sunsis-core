<?php
class view_scot_fund_frameworks implements IAction
{
	public function execute(PDO $link)
	{
		$export = isset($_REQUEST['export']) ? $_REQUEST['export'] : '';

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_scot_fund_frameworks", "View Scottish Funded Frameworks");

		$view = ViewScotFundFrameworks::getInstance($link);
		$view->refresh($link, $_REQUEST);

		if (isset($export) && $export == 'export') {
			header("Content-Type: text/csv; charset=UTF-8");
			header('Content-Disposition: attachment; filename="ScottishFundingFrameworksReport.csv"');
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			ob_start();
			$view->exportToCSV($link);
			$data = ob_get_clean();

			echo mb_convert_encoding($data, 'Windows-1252', 'UTF-8');
			exit;
		}

		require_once('tpl_view_scot_fund_frameworks.php');
	}
}
