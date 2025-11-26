<?php
class read_screen1 implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['cps']) ? $_GET['cps'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=read_screen1&cps=" . $id, "View Good Receipt Note");

		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$vo = Screen1::loadFromDatabase($link, $id);

		if($vo->multi_part == 1)
			$vo->multi_part = "Yes";
		else
			$vo->multi_part = "No";


		$locations = array();
		$locations['location1'] = 'Location 1';
		$locations['location2'] = 'Location 2';
		$locations['location3'] = 'Location 3';
		$locations['location4'] = 'Location 4';
		$locations['location5'] = 'Location 5';
		$locations['location6'] = 'Location 6';
		$locations['location7'] = 'Location 7';
		$locations['location8'] = 'Location 8';
		$locations['location9'] = 'Location 9';
		$locations['location10'] = 'Location 10';
		$locations['location11'] = 'Location 11';
		$locations['location12'] = 'Location 12';
		$locations['location13'] = 'Location 13';
		$locations['location14'] = 'Location 14';
		$locations['location15'] = 'Location 15';
		$locations['location16'] = 'Location 16';
		$locations['location17'] = 'Location 17';
		$locations['location18'] = 'Location 18';
		$locations['location19'] = 'Location 19';
		$vo->location = $locations[$vo->location];

		$contract_type = array();
		$contract_type[0] = '';
		$contract_type[1] = 'Contract';
		$contract_type[2] = 'Non-Contract';
		$contract_type[3] = 'Permanent';
		$vo->contract_type = $contract_type[$vo->contract_type];

		$a = array();
		$a[0] = 'No';
		$a[1] = 'Yes';

		$vo->br_640_in = $a[$vo->br_640_in];
		$vo->br_640_out = $a[$vo->br_640_out];
		// Presentation
		include('tpl_read_screen1.php');
	}




}
?>