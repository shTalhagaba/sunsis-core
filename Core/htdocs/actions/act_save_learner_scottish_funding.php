<?php
class save_learner_scottish_funding implements IAction
{
	public function execute(PDO $link)
	{
		$framework_id = isset($_REQUEST['fwrk_id'])?$_REQUEST['fwrk_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$number_of_milestones = DAO::getSingleValue($link, "SELECT milestones FROM frameworks WHERE id = " . $framework_id);
		$sql = "";

		if(isset($_REQUEST['SP_due_date']))
		{
			$SP_due_date = $_REQUEST['SP_due_date'] != ''? "'".Date::toMySQL($_REQUEST['SP_due_date'])."'" : 'NULL';
			$SP_doc_sent_date = $_REQUEST['SP_doc_sent_date'] != ''? "'".Date::toMySQL($_REQUEST['SP_doc_sent_date'])."'" : 'NULL';
			$SP_date_paid = $_REQUEST['SP_date_paid'] != ''? "'".Date::toMySQL($_REQUEST['SP_date_paid'])."'" : 'NULL';
			$SP_amount_received = $_REQUEST['SP_amount_received'] != ''? "'".$_REQUEST['SP_amount_received']."'" : 'NULL';
		}

		$SP_query = " ";
		if(isset($_REQUEST['SP_due_date']))
		{
			$SP_query = <<<SP_query
INSERT INTO scottish_payments
	(tr_id, payment_type, due_date, doc_sent_date, date_paid, amount_received, fwrk_id)
VALUES
	($tr_id, 'SP', $SP_due_date, $SP_doc_sent_date, $SP_date_paid, $SP_amount_received, $framework_id);
SP_query;

		}

		$sql = $SP_query . PHP_EOL;
		for($i = 1; $i <= $number_of_milestones; $i++)
		{
			$MP_due_date = $_REQUEST['MP_due_date_'.$i] != ''? "'".Date::toMySQL($_REQUEST['MP_due_date_'.$i])."'" : 'NULL';
			$MP_milestones_completion_date = $_REQUEST['MP_milestones_completion_date_'.$i] != ''? "'".Date::toMySQL($_REQUEST['MP_milestones_completion_date_'.$i])."'" : 'NULL';
			$MP_doc_sent_date = $_REQUEST['MP_doc_sent_date_'.$i] != ''? "'".Date::toMySQL($_REQUEST['MP_doc_sent_date_'.$i])."'" : 'NULL';
			$MP_date_paid = $_REQUEST['MP_date_paid_'.$i] != ''? "'".Date::toMySQL($_REQUEST['MP_date_paid_'.$i])."'" : 'NULL';
			$MP_amount_received = $_REQUEST['MP_amount_received_'.$i] != ''? "'".$_REQUEST['MP_amount_received_'.$i]."'" : 'NULL';

			$MP_query = <<<MP_query
INSERT INTO scottish_payments
	(tr_id, payment_type, due_date, milestones_completion_date, doc_sent_date, date_paid, amount_received, fwrk_id)
VALUES
	($tr_id, 'MP$i', $MP_due_date, $MP_milestones_completion_date, $MP_doc_sent_date, $MP_date_paid, $MP_amount_received, $framework_id);
MP_query;
			$sql .= $MP_query . PHP_EOL;
		}

		$OP_due_date = $_REQUEST['OP_due_date'] != ''? "'".Date::toMySQL($_REQUEST['OP_due_date'])."'" : 'NULL';
		$OP_doc_sent_date = $_REQUEST['OP_doc_sent_date'] != ''? "'".Date::toMySQL($_REQUEST['OP_doc_sent_date'])."'" : 'NULL';
		$OP_date_paid = $_REQUEST['OP_date_paid'] != ''? "'".Date::toMySQL($_REQUEST['OP_date_paid'])."'" : 'NULL';
		$OP_amount_received = $_REQUEST['OP_amount_received'] != ''? "'".$_REQUEST['OP_amount_received']."'" : 'NULL';

		$OP_query = <<<SP_query
INSERT INTO scottish_payments
	(tr_id, payment_type, due_date, doc_sent_date, date_paid, amount_received, fwrk_id)
VALUES
	($tr_id, 'OP', $OP_due_date, $OP_doc_sent_date, $OP_date_paid, $OP_amount_received, $framework_id);
SP_query;

		DAO::execute($link, "DELETE FROM scottish_payments WHERE tr_id = " . $tr_id . " AND fwrk_id = " . $framework_id);
		$sql .= $OP_query . PHP_EOL;

		DAO::execute($link, $sql);


		$_SESSION['bc']->index = $_SESSION['bc']->index-1;
		http_redirect('do.php?_action=read_training_record&id='.$tr_id);
	}
}
?>