<?php
class baltic_edit_candidate_crm implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry

		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$candidate_id = isset($_REQUEST['candidate_id']) ? $_REQUEST['candidate_id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_candidate_crm&candidate_id=" . $candidate_id , "Candidate CRM Note");

		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$view2 = NULL;
		$vo = NULL;

		if ( '' != $candidate_id )
		{
			$view2 = ViewCandidateCRM::getInstance($link, $candidate_id);
			if( $id == '' )
			{
				// New record
				$vo = new CandidateCRM();
			}
			else
			{
				$vo = CandidateCRM::loadFromDatabase($link, $id);
			}
			$candidate_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM candidate WHERE id = $candidate_id");
		}


		// Dropdown arrays
		$sql = "SELECT id, description, null FROM lookup_crm_regarding where description != '' order by description asc;";
		$contact_status = DAO::getResultSet($link, $sql);

		$sql = "SELECT id, description, null FROM lookup_crm_contact_type where description != '' order by description asc;";
		$contact_type = DAO::getResultSet($link, $sql);

		//if(DB_NAME=="am_baltic" || DB_NAME=="ams")
		//	$sql = "SELECT id, description, null FROM lookup_crm_subject where description != '' and candidate = 1 order by description asc;";
		//else
			$sql = "SELECT id, description, null FROM lookup_crm_subject where description != '' and candidate = 1 order by description asc;";
		$subject = DAO::getResultSet($link, $sql);

		$sql = "SELECT description, description, null FROM lookup_crm_outcomes where description != '' order by description asc;";
		$outcomes = DAO::getResultSet($link, $sql);

		$sql = "SELECT description, description, null FROM lookup_crm_outcomes_plus where description != '' order by description asc;";
		$outcomes_plus = DAO::getResultSet($link, $sql);

		include('tpl_baltic_edit_candidate_crm.php');
	}
}
?>