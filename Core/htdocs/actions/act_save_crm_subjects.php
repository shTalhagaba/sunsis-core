<?php
class save_crm_subjects implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$subject = isset($_REQUEST['subject'])?$_REQUEST['subject']:'';
		$subject = addslashes((string)$subject);

		if($subject=='')
		{
			throw new Exception("Missing or empty argument ");
		}

		if($id=='')
		{
		$query = <<<HEREDOC
insert into
	lookup_crm_subject (id, description)
VALUES(null,'$subject');
HEREDOC;
		}
		else
		{
			$query = <<<HEREDOC
update lookup_crm_subject SET description = '$subject' WHERE id = '$id';
HEREDOC;

		}
		DAO::execute($link, $query);

		$sql = "select id from lookup_referral_source where description='$subject' ";
		if($st = $link->query($sql))
		{
			$r = $st->fetch();
			$d = $r['id'];
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		echo $d;
	}
}
?>