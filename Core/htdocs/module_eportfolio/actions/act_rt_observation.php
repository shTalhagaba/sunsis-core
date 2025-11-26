<?php
class rt_observation implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$step = isset($_REQUEST['step'])?$_REQUEST['step']:0;

		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');
		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(is_null($tr))
			throw new Exception('Training record not found');
		if($id == '')
		{
			// extra check to prevent creating another review for the learner - might happen if you open the screen in new tab
			$exists = DAO::getSingleValue($link, "SELECT id FROM rt_observations WHERE tr_id = '{$tr_id}' ");
			if($exists != "")
				$rt_observation = RtObservation::loadFromDatabase($link, $exists);
			else
			{
				$rt_observation = new RtObservation($tr_id);
				$rt_observation->save($link);
			}
		}
		else
		{
			$rt_observation = RtObservation::loadFromDatabase($link, $id);
		}

		if(!is_object($rt_observation->evidences))
		{
			$rt_observation->evidences = XML::loadSimpleXML($rt_observation->evidences);
		}

		$units = $rt_observation->evidences;

		$assessor_signature = DAO::getSingleValue($link, "SELECT signature FROM users WHERE users.id = '{$_SESSION['user']->id}'");

		include_once('tpl_rt_observation.php');
	}
}