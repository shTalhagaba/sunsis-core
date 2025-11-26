<?php
class cs_observation implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:3816;
		$step = isset($_REQUEST['step'])?$_REQUEST['step']:0;

		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');
		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(is_null($tr))
			throw new Exception('Training record not found');
		if($id == '')
		{
			// extra check to prevent creating another review for the learner - might happen if you open the screen in new tab
			$exists = DAO::getSingleValue($link, "SELECT id FROM cs_observations WHERE tr_id = '{$tr_id}' ");
			if($exists != "")
				$cs_observation = CSObservation::loadFromDatabase($link, $exists);
			else
			{
				$cs_observation = new CSObservation($tr_id);
				$cs_observation->save($link);
			}
		}
		else
		{
			$cs_observation = CSObservation::loadFromDatabase($link, $id);
		}

		if(!is_object($cs_observation->evidences))
		{
			$cs_observation->evidences = XML::loadSimpleXML($cs_observation->evidences);
		}

		$units = $cs_observation->evidences;

		$assessor_signature = DAO::getSingleValue($link, "SELECT signature FROM users WHERE users.id = '{$_SESSION['user']->id}'");

		include_once('tpl_cs_observation.php');
	}
}