<?php
class save_cs_observation implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);

		$id = isset($_REQUEST['id'])?$_REQUEST['id']:''; // cs observation id
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$unit_no = isset($_REQUEST['unit_no'])?$_REQUEST['unit_no']:'';
		$step = isset($_REQUEST['step'])?$_REQUEST['step']:'';
		$full_save = isset($_REQUEST['full_save'])?$_REQUEST['full_save']:'';

		$cs_observation = CSObservation::loadFromDatabase($link, $id);
		$cs_observation->populate($_REQUEST);

		// Save only one section asked
		if($unit_no != '')
		{
			$saved_unit = $cs_observation->evidences->xpath('//Units/Unit[@id="'.$unit_no.'"]');

			if(count($saved_unit) > 0)
			{
				$dom = dom_import_simplexml($saved_unit[0]);
				$dom->parentNode->removeChild($dom);
			}

			$unit = $cs_observation->evidences->addChild('Unit');
			$unit->addAttribute('id', $unit_no);

			if(in_array($unit_no, array('2','5','6','7')))
			{
				$system_refs = DAO::getSingleColumn($link, "SELECT el_system_ref FROM lookup_cs_obs_questions WHERE unit_no = '{$unit_no}'");
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
			}
			else
			{
				for($i = 1; $i <= 6; $i++)
				{
					if(!isset($_REQUEST[$unit_no.'_e'.$i.'_pd1']))
						continue;

					$unit_element = $unit->addChild('Element');
					$unit_element->addAttribute('id', 'e'.$i);
					if(isset($_REQUEST[$unit_no.'_e'.$i.'_pd1']))
						$unit_element->addAttribute('pd1', $_REQUEST[$unit_no.'_e'.$i.'_pd1']);
					else
						$unit_element->addAttribute('pd1', '');
					if(isset($_REQUEST[$unit_no.'_e'.$i.'_pd2']))
						$unit_element->addAttribute('pd2', $_REQUEST[$unit_no.'_e'.$i.'_pd2']);
					else
						$unit_element->addAttribute('pd2', '');
					if(isset($_REQUEST[$unit_no.'_e'.$i.'_dd1']))
						$unit_element->addAttribute('dd1', $_REQUEST[$unit_no.'_e'.$i.'_dd1']);
					else
						$unit_element->addAttribute('dd1', '');
					if(isset($_REQUEST[$unit_no.'_e'.$i.'_dd2']))
						$unit_element->addAttribute('dd2', $_REQUEST[$unit_no.'_e'.$i.'_dd2']);
					else
						$unit_element->addAttribute('dpd2', '');
					if(isset($_REQUEST[$unit_no.'_e'.$i.'_comments']))
						$unit_element->addAttribute('comments', htmlspecialchars((string)$_REQUEST[$unit_no.'_e'.$i.'_comments']));
					else
						$unit_element->addAttribute('comments', '');
				}
			}

			$cs_observation->save($link);
		}


		if($full_save == 'Y')
		{
			$cs_observation->updateProgress($link);
			$cs_observation->assessor_sign_date = date('Y-m-d');
			$cs_observation->save($link);
		}

		http_redirect('do.php?_action=cs_observation&id='.$cs_observation->id.'&tr_id='.$cs_observation->tr_id.'&step='.$step);
	}
}