<?php
class baltic_view_html_preview_of_email implements IAction
{
	public function execute(PDO $link)
	{
		$employer = isset($_REQUEST['employer'])?$_REQUEST['employer']:'';
		$candidate = isset($_REQUEST['candidate'])?$_REQUEST['candidate']:'';
		$pool = isset($_REQUEST['pool'])?$_REQUEST['pool']:'';

		$html_content = "";

		if($candidate != 'false')
			$html_content = DAO::getSingleValue($link, "SELECT email_html_preview FROM candidate_email_notes WHERE id = " . $candidate);
		if($pool != 'false')
			$html_content = DAO::getSingleValue($link, "SELECT email_html_preview FROM employer_pool_contact_email_notes WHERE id = " . $pool);
		if($employer != 'false')
			$html_content = DAO::getSingleValue($link, "SELECT email_html_preview FROM employer_contact_email_notes WHERE id = " . $employer);

		include_once('tpl_baltic_view_html_preview_of_email.php');
	}
}