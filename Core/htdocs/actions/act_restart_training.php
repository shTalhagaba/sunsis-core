<?php
class restart_training implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		if($tr_id == '')
		{
			throw new Exception("missing querystring argument: tr_id");
		}
		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(is_null($tr))
		{
			throw new Exception("Invalid training id");
		}

        	$_SESSION['bc']->add($link, "do.php?_action=restart_training&tr_id=" . $tr->id, "Restart from BIL");

		$courseId = DAO::getSingleValue($link, "SELECT courses_tr.course_id FROM courses_tr WHERE courses_tr.tr_id = '{$tr->id}'");
		$course = Course::loadFromDatabase($link, $courseId);
		$frameworkId = DAO::getSingleValue($link, "SELECT student_frameworks.id FROM student_frameworks WHERE student_frameworks.tr_id = '{$tr->id}'");
		$framework = Framework::loadFromDatabase($link, $frameworkId);
		$contract = Contract::loadFromDatabase($link, $tr->contract_id);
		$reasonForLeaving = '';
		if($tr->reason_for_leaving != '')
		{
			$reasonForLeaving = DAO::getSingleValue($link, "SELECT description FROM lookup_reasons_for_leaving WHERE id = '{$tr->reasons_for_leaving}'") . '<br>';
		}
		$ilrWithdrawReason = DAO::getSingleValue($link, "SELECT extractvalue(ilr, 'Learner/LearningDelivery[LearnAimRef=\"ZPROG001\"]/WithdrawReason') FROM ilr WHERE ilr.contract_id = '{$tr->contract_id}' AND ilr.tr_id = '{$tr->id}' ORDER BY submission DESC LIMIT 1");
		if($ilrWithdrawReason != '')
		{
			$reasonForLeaving .= DAO::getSingleValue($link, "SELECT WithdrawReason_Desc FROM lis201415.ilr_withdrawreason WHERE WithdrawReason = '{$ilrWithdrawReason}'");
		}
		$contractsList = DAO::getResultset($link,"SELECT id, title, contract_year FROM contracts WHERE active = 1 AND contract_year >= 2014 ORDER BY contract_year DESC, title");

		require_once('tpl_restart_training.php');
	}
}
?>