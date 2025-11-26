<?php
class Screen1 extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}

		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	tabel1
WHERE
	cps='$key'
LIMIT 1;
HEREDOC;

		$st = $link->query($query);

		$org = null;
		if($st)
		{
			$org = null;
			$row = $st->fetch();
			if($row)
			{
				$org = new Screen1();
				$org->populate($row);
			}

		}
		else
		{
			throw new Exception("Could not execute database query to find entry. " . '----' . $query . '----' . $link->errorCode());
		}

		return $org;
	}

	public function save(PDO $link)
	{

		return DAO::saveObjectToTable($link, 'tabel1', $this);
	}


	public function delete(PDO $link)
	{

	}

	public $cps = null;
	public $date_received = null;
	public $location = null;
	public $nato = null;
	public $repair_list = null;
	public $multi_part = null;
	public $recevied_from = null;
	public $type = null;
	public $for_customer = null;
	public $transport = null;
	public $advice_note = null;
	public $DMC = null;
	public $pack_level = null;
	public $order_no = null;
	public $description = null;
	public $note = null;
	public $supp_640 = null;
	public $supp_cont_no = null;
	public $warrant_no = null;
	public $br_640_in  = null;
	public $no_and_type_of_containers = null;
	public $contract_type = null;
	public $br_640_out = null;







}
?>