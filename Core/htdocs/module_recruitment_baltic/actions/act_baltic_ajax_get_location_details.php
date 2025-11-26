<?php
class baltic_ajax_get_location_details implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$location_id = isset($_REQUEST['location_id'])?$_REQUEST['location_id']: '';
		if($location_id == '')
			throw new Exception("No location selected");

		echo DAO::getSingleValue($link, "SELECT CONCAT(IFNULL(contact_name,''), '*', IFNULL(contact_telephone,''), '*', IFNULL(contact_email,'')) FROM locations WHERE id = '" . $location_id . "'");
	}
}