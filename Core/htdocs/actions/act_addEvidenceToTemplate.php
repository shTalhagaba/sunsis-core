<?php
class addEvidenceToTemplate implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$qualification_id = isset($_REQUEST['qualification_id']) ? $_REQUEST['qualification_id']:'';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id']:'';
		$framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id']:'';
		$evidence_id = isset($_REQUEST['evidence_id']) ? $_REQUEST['evidence_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle']) ? $_REQUEST['internaltitle']:'';
		$target= isset($_REQUEST['target'])?$_REQUEST['target']:'';
		$achieved= isset($_REQUEST['achieved'])?$_REQUEST['achieved']:'';
		$group_id= isset($_REQUEST['group_id'])?$_REQUEST['group_id']:'';
		 
		if($evidence_id == '')
		{
			// New record
			$vo = new Evidence();
			$vo->tr_id = $tr_id;
			$vo->qualification_id = $qualification_id;
			$vo->framework_id = $framework_id;
		}
		else
		{
			$vo = Evidence::loadFromDatabase($link, $tr_id, $evidence_id);
			$vo->qualification_id = $qualification_id;
			$vo->framework_id = $framework_id;
			if($vo->date=='0000-00-00')
				$vo->date = NULL;
		}

		$dropdown_type = "SELECT id, CONCAT(id, ' - ', type), null FROM lookup_evidence_type ORDER BY id;";
		$dropdown_type = DAO::getResultset($link, $dropdown_type);
		
		$dropdown_content = "SELECT id, CONCAT(id, ' - ', content), null FROM lookup_evidence_content ORDER BY id;";
		$dropdown_content = DAO::getResultset($link, $dropdown_content);
		
		$dropdown_category = "SELECT id, CONCAT(id, ' - ', category), null FROM lookup_evidence_categories ORDER BY id;";
		$dropdown_category = DAO::getResultset($link, $dropdown_category);
		
		$dropdown_assessor = "SELECT users.id, CONCAT(users.firstnames, ' ' , users.surname, ' - ', organisations.legal_name), null FROM users INNER JOIN organisations on organisations.id = users.employer_id where users.type=3;";
		$dropdown_assessor = DAO::getResultset($link, $dropdown_assessor);

		
		// Presentation
		include('tpl_editEvidenceToTemplate.php');
	}
}
?>