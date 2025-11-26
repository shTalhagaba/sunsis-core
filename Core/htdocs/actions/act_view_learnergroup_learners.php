<?php
class view_learnergroup_learners implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		
		if($id == '')
		{
			throw new Exception("Missing argument \$id");
		}
		
		$view = ViewLearnerGroupLearners::getInstance($link, $id);
		$view->refresh($link, $_REQUEST);

		$vo = LearnerGroup::loadFromDatabase($link, $id);
		
		if($vo==null)
		{
			throw new Exception("could not found");	
		}

//		$query = "select description from lookup_sector_types where id=$vo->sector";
//		$sector= trim(DAO::getSingleValue($link, $query));
/*		
		$query = "select start_date from frameworks where id=$id";
		$framework_start_date = trim(DAO::getSingleValue($link, $query));
		
		$query = "select end_date from frameworks where id=$id";
		$framework_end_date = trim(DAO::getSingleValue($link, $query));
		
		$query = "select end_date from frameworks where id=$id";
		$framework_end_date = trim(DAO::getSingleValue($link, $query));
		
		$query = "select end_date from frameworks where id=$id";
		$framework_end_date = trim(DAO::getSingleValue($link, $query));
*/		
		require_once('tpl_view_learnergroup_learners.php');
	}
}
?>