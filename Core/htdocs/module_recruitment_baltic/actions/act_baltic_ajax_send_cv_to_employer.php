<?php
class baltic_ajax_send_cv_to_employer implements IAction
{
	public function execute(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';

		if( '' == $candidate_id)
		{

		}
		else
		{
			$sql= "UPDATE candidate SET candidate.status_code = 18 WHERE id = " . $candidate_id;
			DAO::execute($link, $sql);
		}
	}
}
?>
