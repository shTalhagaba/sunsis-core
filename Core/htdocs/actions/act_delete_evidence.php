<?php
class delete_evidence implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$evidence_id = isset($_REQUEST['evidence_id'])?$_REQUEST['evidence_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$target= isset($_REQUEST['target'])?$_REQUEST['target']:'';
		$achieved= isset($_REQUEST['achieved'])?$_REQUEST['achieved']:'';
		$group_id= isset($_REQUEST['group_id'])?$_REQUEST['group_id']:'';
		$evidence_title= isset($_REQUEST['evidence_title'])?$_REQUEST['evidence_title']:'';
		
		
		if($evidence_id == '')
		{
			throw new Exception("Missing argument \$evidence_id");
		}
		
		$vo = Evidence::loadFromDatabase($link, $tr_id, $evidence_id);

		if(is_null($vo))
		{
			
			throw new Exception("Could not find Evidence '$evidence_id'");
			
		}
		
		$vo->delete($link);

		http_redirect('do.php?_action=view_evidence&qualification_id='. $qualification_id . '&framework_id=' . $framework_id . '&tr_id=' . $tr_id . '&internaltitle=' . $internaltitle . '&achieved=' . $achieved . '&target=' . $target . '&group_id=' . $group_id);
	}
}
?>