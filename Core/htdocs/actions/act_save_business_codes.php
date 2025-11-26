<?php
class save_business_codes implements IAction
{
	public function execute(PDO $link)
	{

		$screen_tab = isset($_POST['screen_tab'])?$_POST['screen_tab']:'';
		$id = isset($_POST['id'])?$_POST['id']:'';
        $report = isset($_POST['report'])?$_POST['report']:'';
        $new = isset($_POST['new'])?$_POST['new']:'';

		DAO::transaction_start($link);
		try
		{
			if($screen_tab=='1')
				$this->saveBusinessCodes($link, $id);
			elseif($screen_tab=='2')
				$this->saveBusinessCodes2($link, $id);

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

        if($new==1)
            http_redirect("do.php?_action=funding_reports");

        if($report!='')
		    http_redirect("do.php?_action=".$report);
        else
            http_redirect("do.php?_action=show_funding_comparison");
	}

	private function saveBusinessCodes(PDO $link, $id)
	{
		$xml = isset($_REQUEST['questions_xml'])?$_REQUEST['questions_xml']:'';
		$pageDom = XML::loadXmlDom($xml);
		$e = $pageDom->getElementsByTagName('Business');

		$selected_questions = array();

		foreach($e as $node)
		{
			$selected_questions[$node->getElementsByTagName('Code')->item(0)->nodeValue]['id'] = NULL;
			$selected_questions[$node->getElementsByTagName('Code')->item(0)->nodeValue]['description'] = $node->getElementsByTagName('Code')->item(0)->nodeValue;
			$selected_questions[$node->getElementsByTagName('Code')->item(0)->nodeValue]['year'] = 2015;
			$selected_questions[$node->getElementsByTagName('Code')->item(0)->nodeValue]['type'] = "Y";
			$selected_questions[$node->getElementsByTagName('Code')->item(0)->nodeValue][$node->getElementsByTagName('Month')->item(0)->nodeValue] = $node->getElementsByTagName('Value')->item(0)->nodeValue;
		}



		// Delete all existing selected questions for the learner
		$sql = "DELETE FROM profile_values";
		DAO::execute($link, $sql);

		// Add submitted contacts
		DAO::multipleRowInsert($link, "profile_values", $selected_questions);
	}


	private function saveBusinessCodes2(PDO $link, $id)
	{
		$xml = isset($_REQUEST['questions_xml'])?$_REQUEST['questions_xml']:'';
		$single_multi = isset($_REQUEST['single_multi'])?$_REQUEST['single_multi']:'';
		$chart_type = isset($_REQUEST['chart_type'])?$_REQUEST['chart_type']:'';
		$values_type = isset($_REQUEST['value_type'])?$_REQUEST['value_type']:'';
		$report = isset($_POST['report'])?$_POST['report']:'';
		$pageDom = XML::loadXmlDom($xml);
		$e = $pageDom->getElementsByTagName('BusinessCode');
		$selected_codes = array();
		foreach($e as $node)
		{
			$selected_codes[] = $node->nodeValue;
		}

		DAO::execute($link,"update profile_values set type = 'N'");
		$codes = implode(",",$selected_codes);
		if($codes!='')
			DAO::execute($link,"update profile_values set type = 'Y' where id in ($codes)");
        if($report=='')
        {
            DAO::execute($link,"delete from funding_configuration where ConType in ('SingleMulti','ChartType','ValuesType')");
            DAO::execute($link,"REPLACE INTO funding_configuration VALUES('SingleMulti','$single_multi')");
            DAO::execute($link,"REPLACE INTO funding_configuration VALUES('ChartType','$chart_type')");
            DAO::execute($link,"REPLACE INTO funding_configuration VALUES('ValuesType','$values_type')");
        }
	}

}
?>
