<?php
class save_awarding_body implements IAction
{
	public function execute(PDO $link)
	{
		$org = new AwardingBody();
		$org->populate($_REQUEST);

		$org->save($link);

		// only if new record then auto create the location
		if(isset($_REQUEST['id']) && $_REQUEST['id'] == '')
		{
			if(isset($_REQUEST['lookup_a_body']) && $_REQUEST['lookup_a_body'] != '')
			{
				$awarding_body = DAO::getResultset($link, "SELECT * FROM central.lookup_awarding_bodies WHERE registration_number = '" . $_REQUEST['lookup_a_body'] . "'", DAO::FETCH_ASSOC);
				$awarding_body = $awarding_body[0];

				$location = new Location();
				$location->organisations_id = $org->id;
				$location->full_name = "Head Quarters";
				$location->short_name = "HQ";
				$location->address_line_1 = isset($awarding_body["ho_address_line_1"])?$awarding_body["ho_address_line_1"]:'';
				$location->address_line_2 = isset($awarding_body["ho_address_line_2"])?$awarding_body["ho_address_line_2"]:'';
				$location->address_line_3 = isset($awarding_body["ho_town"])?$awarding_body["ho_town"]:'';
				$location->address_line_4 = isset($awarding_body["ho_county"])?$awarding_body["ho_county"]:'';
				$location->line1 = isset($awarding_body["ho_address_line_1"])?$awarding_body["ho_address_line_1"]:'';
				$location->line2 = isset($awarding_body["ho_address_line_2"])?$awarding_body["ho_address_line_2"]:'';
				$location->line3 = isset($awarding_body["ho_town"])?$awarding_body["ho_town"]:'';
				$location->line4 = isset($awarding_body["ho_county"])?$awarding_body["ho_county"]:'';
				$location->postcode = isset($awarding_body["ho_postcode"])?$awarding_body["ho_postcode"]:'';
				$location->telephone = isset($awarding_body["ho_telephone"])?$awarding_body["ho_telephone"]:'';
				$location->contact_name = 'HQ Contact';
				$location->contact_email = isset($awarding_body["email"])?$awarding_body["email"]:'';
				$location->is_legal_address = 1;
				$location->save($link);
			}
		}
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>