<?php
class save_ilr_internal_validation implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$xml = isset($_REQUEST['questions_xml'])?$_REQUEST['questions_xml']:'';
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
		$questions_xml = XML::loadSimpleXML($xml);

		$query = '';
		foreach($questions_xml->question as $question)
		{
			$query .= 'REPLACE INTO ilr_internal_validation (tr_id, submission, q_id, q_reply) VALUES (';
			$query .= $tr_id . ', "' . $submission . '", ' . $question->q_id . ', ' . $question->q_reply . ");";
		}

		try
		{
			DAO::execute($link, $query);
		}
		catch(Exception $e)
		{
			throw new Exception($e);
		}


	}
}
?>
