<?php
class save_add_learner implements IAction
{
	public function execute(PDO $link)
	{
		$vo = new User();
		$vo->populate($_POST);

		do
		{
			$pwd = PasswordUtilities::generateDatePassword();
			$pwd = PasswordUtilities::randomCapitalisation($pwd, 1);
			$pwd = PasswordUtilities::replaceSpacesWithNumbers($pwd);
			$validationResults = PasswordUtilities::checkPasswordStrength($pwd, PasswordUtilities::getIllegalWords());
		} while($validationResults['code'] == 0);
		$vo->password = $pwd;
		$vo->pwd_sha1 = sha1($pwd);
		$vo->web_access = 0;

		$vo->type = User::TYPE_LEARNER;
		$vo->created = date('Y-m-d H:i:s');

		$location = Location::loadFromDatabase($link, $vo->employer_location_id);
		$vo->work_address_line_1 = $location->address_line_1;
		$vo->work_address_line_2 = $location->address_line_2;
		$vo->work_address_line_3 = $location->address_line_3;
		$vo->work_address_line_4 = $location->address_line_4;
		$vo->work_postcode = $location->postcode;
		$vo->work_telephone = $location->telephone;
		$vo->work_fax = $location->fax;

		DAO::transaction_start($link);
		try
		{

			$vo->save($link, true);

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		http_redirect("do.php?_action=edit_learner&username={$vo->username}&organisations_id={$vo->employer_id}&location_id={$vo->employer_location_id}");
	}


}
?>
