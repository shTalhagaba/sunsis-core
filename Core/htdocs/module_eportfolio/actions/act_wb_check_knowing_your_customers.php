<?php
class wb_check_knowing_your_customers implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');

		$wb_knowing_your_customers = DAO::getObject($link, "SELECT * FROM wb_knowing_your_customers WHERE tr_id = '{$tr_id}'");
		if(is_null($wb_knowing_your_customers))
		{
			$fields = DAO::getSingleColumn($link, "SELECT column_name FROM information_schema.columns WHERE table_name='wb_knowing_your_customers';");
			$wb_knowing_your_customers = new stdClass();
			foreach($fields AS $f)
				$wb_knowing_your_customers->$f = null;

			$wb_knowing_your_customers->tr_id = $tr_id;

		}

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);

		$answer_status = array(
			array('NA', 'Not Accepted'),
			array('A', 'Accepted')
		);

		$wb_knowing_your_customers->rsrch = json_decode($wb_knowing_your_customers->rsrch);

		$learning_activity_list = array(
			array('Workbook', 'Workbook'),
			array('iPad', 'iPad'),
			array('InStore', 'In-store')
		);

		if(!is_null($wb_knowing_your_customers->learning_journey) && $wb_knowing_your_customers->learning_journey != '')
			$wb_knowing_your_customers->learning_journey = json_decode($wb_knowing_your_customers->learning_journey);
		else
		{
			$wb_knowing_your_customers->learning_journey = new stdClass();
			for($i = 1; $i <= 11; $i++)
			{
				$f1 = 'lj_q'.$i;
				$f2 = 'lj_q'.$i.'_dc';
				$wb_knowing_your_customers->learning_journey->$f1 = null;
				$wb_knowing_your_customers->learning_journey->$f2 = null;
			}
		}
		if(!is_null($wb_knowing_your_customers->sd_customers_type) && $wb_knowing_your_customers->sd_customers_type != '')
		{
			$wb_knowing_your_customers->sd_customers_type = json_decode($wb_knowing_your_customers->sd_customers_type);
		}
		else
		{
			$wb_knowing_your_customers->sd_customers_type = new stdClass();
			$wb_knowing_your_customers->sd_customers_type->SRGM = null;
			$wb_knowing_your_customers->sd_customers_type->SDD = null;
			$wb_knowing_your_customers->sd_customers_type->YOU = null;
			$wb_knowing_your_customers->sd_customers_type->FWWIG = null;
			$wb_knowing_your_customers->sd_customers_type->LP = null;
			$wb_knowing_your_customers->sd_customers_type->AA = null;
			$wb_knowing_your_customers->sd_customers_type->SP = null;
		}

		if(!is_null($wb_knowing_your_customers->customers_type) && $wb_knowing_your_customers->customers_type != '')
		{
			$wb_knowing_your_customers->customers_type = json_decode($wb_knowing_your_customers->customers_type);
		}
		else
		{
			$wb_knowing_your_customers->customers_type = new stdClass();
			$wb_knowing_your_customers->customers_type->TypeLoyal = null;
			$wb_knowing_your_customers->customers_type->TypeDiscount = null;
			$wb_knowing_your_customers->customers_type->TypeImpulse = null;
			$wb_knowing_your_customers->customers_type->TypeWandering = null;
			$wb_knowing_your_customers->customers_type->TypeNeedBased = null;
		}


		include_once('tpl_wb_check_knowing_your_customers.php');
	}
}