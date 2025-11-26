<?php
class funding_profiler implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=funding_profiler", "Funding Profiler");

		$A15_dropdown = "SELECT DISTINCT ProgType, LEFT(CONCAT(ProgType, ' ' , ProgType_Desc),40), NULL FROM lis201415.ilr_progtype ORDER BY ProgType;";
		$A15_dropdown = DAO::getResultset($link,$A15_dropdown);
		
		$frameworks = array();
		$component1 = array();
		
		$A26_dropdown = "SELECT DISTINCT Framework_Code, LEFT(CONCAT(Framework_Code, ' ', Framework_Desc),40) ,null from lad201213.frameworks order by Framework_Code;";
		$A26_dropdown = DAO::getResultset($link,$A26_dropdown);
		
		$starting_month = array("Sep 2011", "Oct 2011", "Nov 2011", "Dec 2011");
		
		// Presentation
		include('tpl_funding_profiler.php');
	}
}
?>