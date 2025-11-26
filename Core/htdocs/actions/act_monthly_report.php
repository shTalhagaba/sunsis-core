<?php
class monthly_report implements IAction
{
	public function execute(PDO $link)
	{

		$export = isset($_REQUEST['export'])?$_REQUEST['export']:'';
		$category = isset($_REQUEST['category'])?$_REQUEST['category']:'';

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=monthly_report", "Monthly Report");

		$view = MonthlyReport::getInstance();
		$view->refresh($link, $_REQUEST);

		if($export == 'csv')
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="' . $view->getViewName() . '.csv"');

			switch($category)
			{
				case 1:
					echo $view->firstSetData;
					break;
				case 2:
					echo $view->secondSetData;
					break;
				case 3:
					echo $view->thirdSetData;
					break;
				case 4:
					echo $view->fourthSetData;
					break;
				case 5:
					echo $view->fifthSetData;
					break;
				case 6:
					echo $view->sixthSetData;
					break;
                case 7:
                    echo $view->seventhSetData;
                    break;
                case 8:
                    echo $view->eighthSetData;
                    break;
				case 0:
					echo $view->firstSetData . "\n\n";
					echo $view->secondSetData . "\n\n";
					echo $view->thirdSetData . "\n\n";
					echo $view->fourthSetData . "\n\n";
					echo $view->fifthSetData . "\n\n";
					echo $view->sixthSetData . "\n\n";
                    echo $view->seventhSetData . "\n\n";
                    echo $view->eighthSetData . "\n\n";
					break;
			}
			exit;
		}
		require_once('tpl_monthly_report.php');
	}
}
?>