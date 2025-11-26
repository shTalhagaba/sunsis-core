<?php
class attach_qualification implements IAction
{
	public function execute(PDO $link) {

		$fid = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?addslashes($_REQUEST['internaltitle']):'';
		$proportion = isset($_REQUEST['proportion'])?$_REQUEST['proportion']:'';
		$duration = isset($_REQUEST['duration'])?$_REQUEST['duration']:'';
		$main_aim = isset($_REQUEST['mainaim'])?$_REQUEST['mainaim']:0;

		$sql = <<<SQL
SQL;
		
		$qualification = DAO::getObject($link, $sql);


		// importing qualification from qualification database 		
		$query = <<<HEREDOC
insert into
	framework_qualifications
select id, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, regulation_start_date, operational_start_date, operational_end_date, certification_end_date, NULL, NULL, '$fid',evidences, units, internaltitle, '$proportion', '$duration', units_required, mandatory_units, $main_aim 
from qualifications
	where id = '$qualification_id' and internaltitle='$internaltitle' $where;
HEREDOC;
		DAO::execute($link, $query);
	}
}
?>