<?php
class ajax_get_learner_details implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']: '';
		if($id == '')
		{
			echo "No ID provided";
		}
		else
		{
			$learner_details = DAO::getResultset($link, "SELECT l45, firstnames, surname, home_postcode, DATE_FORMAT(dob, '%d/%m/%Y'), gender, verification_type, ability_to_share, verification_type_other, home_address_line_1, home_address_line_2, home_address_line_3, home_address_line_4 FROM users WHERE id = '" . $id . "'");
			echo json_encode($learner_details);
		}
	}
}