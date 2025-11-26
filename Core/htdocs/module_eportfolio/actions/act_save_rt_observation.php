<?php
class save_rt_observation implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);

		$criteria_units = array('Cus','Bus','Mkt','Pns','Tec','Tem','Lng','Div','Env');

		$id = isset($_REQUEST['id'])?$_REQUEST['id']:''; // rt observation id
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$unit_no = isset($_REQUEST['unit_no'])?$_REQUEST['unit_no']:'';
		$step = isset($_REQUEST['step'])?$_REQUEST['step']:'';
		$full_save = isset($_REQUEST['full_save'])?$_REQUEST['full_save']:'';

		$rt_observation = RtObservation::loadFromDatabase($link, $id);
		$rt_observation->populate($_REQUEST);

		// Save only one section asked
		if($unit_no != '')
		{
			$saved_unit = $rt_observation->evidences->xpath('//Units/Unit[@id="'.$unit_no.'"]');

			if(count($saved_unit) > 0)
			{
				$dom = dom_import_simplexml($saved_unit[0]);
				$dom->parentNode->removeChild($dom);
			}

			$unit = $rt_observation->evidences->addChild('Unit');
			$unit->addAttribute('id', $unit_no);

			if(!in_array($unit_no, $criteria_units))
			{
				$system_refs = DAO::getSingleColumn($link, "SELECT el_system_ref FROM lookup_rt_obs_questions WHERE unit_no = '{$unit_no}'");
				foreach($system_refs AS $ref)
				{
					$unit_element = $unit->addChild('Element');
					$unit_element->addAttribute('id', $ref);
					for($i = 1; $i <= 2; $i++)
					{
						$date_key = 'el_'.$ref.'_date'.$i;
						$checks_key = 'el_'.$ref.'_checks'.$i;
						if(isset($_REQUEST[$date_key]))
						{
							$unit_element->addAttribute('date'.$i, $_REQUEST[$date_key]);
							if(isset($_REQUEST[$checks_key]))
							{
								$unit_element->addAttribute('checks'.$i, implode(',', $_REQUEST[$checks_key]));
							}
						}
					}
				}
				if($unit_no == 4)
				{
					if(isset($_REQUEST['KnowYourProduct']))
						$unit->addChild('KnowYourProduct', $_REQUEST['KnowYourProduct']);
					if(isset($_REQUEST['KnowYourCustomer']))
						$unit->addChild('KnowYourCustomer', $_REQUEST['KnowYourCustomer']);
					if(isset($_REQUEST['OvercomeObjections']))
						$unit->addChild('OvercomeObjections', $_REQUEST['OvercomeObjections']);
					if(isset($_REQUEST['ListenToYourCustomer']))
						$unit->addChild('ListenToYourCustomer', $_REQUEST['ListenToYourCustomer']);
				}
			}

			if(in_array($unit_no, $criteria_units))
			{
				$criteria_met = array();
				foreach($_REQUEST AS $key => $value)
				{
					if(in_array(substr($key, 0, 3), $criteria_units))
					{
						$criteria_met[] = $value;
					}
				}
				$unit->addChild('CriteriaMet', implode(',', $criteria_met));
			}

			$rt_observation->save($link);
		}


		if($full_save == 'Y')
		{
			$rt_observation->assessor_sign_date = date('Y-m-d');
			$rt_observation->save($link);
		}

		http_redirect('do.php?_action=rt_observation&id='.$rt_observation->id.'&tr_id='.$rt_observation->tr_id.'&step='.$step);
	}
}