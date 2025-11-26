<?php
class attach_additional_qualification implements IAction
{
	public function execute(PDO $link)
	{
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';

		$xmlquals = XML::loadSimpleXML($xml);
		foreach($xmlquals->Qual as $qual)
		{
			$tr_id = $qual->tr_id;
			$internaltitle = $qual->internaltitle;
			$t = TrainingRecord::loadFromDatabase($link, $tr_id);
			$username = $t->username;
			$sd = Date::toMySQL($qual->start_date);
			$ed = Date::toMySQL($qual->end_date);
			$qualification_id = $qual->id;

			// importing qualification from qualification database
			$query = <<<SQL
insert into
	student_qualifications
Select id, '0', '$tr_id', '$internaltitle', lsc_learning_aim, awarding_body, title, description, assessment_method, structure, level, qualification_type, regulation_start_date, operational_start_date, operational_end_date, certification_end_date, NULL, NULL, evidences, units, '0', '0','0','0','0','0','0','0' ,'0' ,''  , NOW(), '$username', '0', NULL, '$sd', '$ed', NULL, NULL, units_required,'',NULL,'',0,0,0,0,'','',''
from qualifications
	where id = '$qualification_id' and internaltitle='$internaltitle';
SQL;
			DAO::execute($link, $query);

		}
	}
}
?>