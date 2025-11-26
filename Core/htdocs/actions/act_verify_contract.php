<?php
class verify_contract implements IAction
{
	public function execute(PDO $link)
	{
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$startDate = isset($_REQUEST['startDate'])?$_REQUEST['startDate']:'';
		$targetDate = isset($_REQUEST['targetDate'])?$_REQUEST['targetDate']:'';

		$verified = "Unsuccessful";

		if($contract_id == '' || $startDate == '' || $targetDate == '')
		{
			throw new Exception('Mandatory information missing.');
		}

		$sql = "SELECT * FROM contracts WHERE id = {$contract_id} "; //Get the details of the selected contract

		$st = $link->query($sql);

		if($st)
		{
			while($row = $st->fetch()) // selected start date and projected end dates must be between the start and end date of the selected contract.
			{
				if($this->formatDate($startDate) >= $this->formatDate($row['start_date']) AND $this->formatDate($startDate) <= $this->formatDate($row['end_date']))
				  //AND $this->formatDate($targetDate) >= $this->formatDate($row['start_date']) AND $this->formatDate($targetDate) <= $this->formatDate($row['end_date']))
					$verified = "Successful";
			}
		}
		echo $verified;

	}

	function formatDate($_date) // converts 'DD/MM/YYYY to YYYY-MM-DD'
	{
		$_date = str_replace('/', '-', $_date);
		return date('Y-m-d', strtotime($_date));
	}
}