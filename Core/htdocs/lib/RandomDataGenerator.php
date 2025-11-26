<?php
class RandomDataGenerator
{
	public function __construct(PDO $link)
	{
		$this->uk_fn_female_start = DAO::getSingleValue($link, "SELECT MIN(id) FROM testdata.uk_firstnames_female;");
		$this->uk_fn_female_range = DAO::getSingleValue($link, "SELECT SUM(frequency) FROM testdata.uk_firstnames_female;");
		$this->uk_fn_male_start = DAO::getSingleValue($link, "SELECT MIN(id) FROM testdata.uk_firstnames_male;");
		$this->uk_fn_male_range = DAO::getSingleValue($link, "SELECT SUM(frequency) FROM testdata.uk_firstnames_male;");
		$this->uk_sn_start = DAO::getSingleValue($link, "SELECT MIN(id) FROM testdata.uk_surnames;");
		$this->uk_sn_range = DAO::getSingleValue($link, "SELECT SUM(frequency) FROM testdata.uk_surnames;");
		$this->street_names_start = DAO::getSingleValue($link, "SELECT MIN(id) FROM testdata.streetnames;");
		$this->street_names_end = DAO::getSingleValue($link, "SELECT MAX(id) FROM testdata.streetnames;");
		$this->postcode_districts = DAO::getSingleColumn($link, "SELECT DISTINCT district FROM testdata.postcode_districts ORDER BY district");
	}

	public function getFirstname(PDO $link, $gender)
	{
		if($gender == 1 || $gender == 'M')
		{
			if(count($this->fn_male)){
				return array_shift($this->fn_male);
			}

			$delta = array();
			for($i = 0; $i < 20; $i++)
			{
				$delta[] = mt_rand($this->uk_fn_male_start, $this->uk_fn_male_range);
			}
			sort($delta);

			$sql = "SELECT `name` FROM testdata.uk_firstnames_male WHERE FALSE ";
			foreach($delta as $d)
			{
				$sql .= " OR (".$d." >= start_range AND ".$d." <= end_range) ";
			}
			$this->fn_male = DAO::getSingleColumn($link, $sql);

			return array_shift($this->fn_male);
		}
		else
		{
			if(count($this->fn_female)){
				return array_shift($this->fn_female);
			}

			$delta = array();
			for($i = 0; $i < 20; $i++)
			{
				$delta[] = mt_rand($this->uk_fn_female_start, $this->uk_fn_female_range);
			}
			sort($delta);

			$sql = "SELECT `name` FROM testdata.uk_firstnames_female WHERE FALSE ";
			foreach($delta as $d)
			{
				$sql .= " OR (".$d." >= start_range AND ".$d." <= end_range) ";
			}
			$this->fn_female = DAO::getSingleColumn($link, $sql);

			return array_shift($this->fn_female);
		}
	}

	public function getSurname(PDO $link)
	{
		if(count($this->sn)){
			return array_shift($this->sn);
		}

		$delta = array();
		for($i = 0; $i < 20; $i++)
		{
			$delta[] = mt_rand($this->uk_sn_start, $this->uk_sn_range);
		}
		sort($delta);

		$sql = "SELECT `name` FROM testdata.uk_surnames WHERE FALSE ";
		foreach($delta as $d)
		{
			$sql .= " OR (".$d." >= start_range AND ".$d." <= end_range) ";
		}
		$this->sn = DAO::getSingleColumn($link, $sql);

		return array_shift($this->sn);
	}

	public function getULN()
	{
		$uln = null;
		$remainder = null;

		do
		{
			$uln = mt_rand(1,9);
			for($i = 1; $i < 9; $i++)
			{
				$uln = $uln . mt_rand(0,9);
			}

			$remainder = ((10 * $uln[0])
				+ (9 * $uln[1])
				+ (8 * $uln[2])
				+ (7 * $uln[3])
				+ (6 * $uln[4])
				+ (5 * $uln[5])
				+ (4 * $uln[6])
				+ (3 * $uln[7])
				+ (2 * $uln[8])) % 11;

			$uln = $uln . (10 - $remainder);

		} while ( ($remainder == 0) || in_array((integer)$uln, $this->ulns));

		$this->ulns[] = (integer) $uln;

		return $uln;
	}

	public function getUPN()
	{
		$checkletters = array('A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','T','U','V','W','X','Y','Z');

		do
		{
			$upn = '';
			$checksum = 0;
			for($i = 0; $i < 12; $i++)
			{
				$digit = mt_rand(0,9);
				$checksum += ($digit * ($i + 2)); // e.g. char 2 multiply by 2, char 3 multiply by 3...
				$upn .= $digit;
			}

			$upn = $checkletters[$checksum % 23] . $upn;
		} while (in_array($upn, $this->upns));

		$this->upns[] = $upn;

		return $upn;
	}

	/**
	 *
	 * @param integer $age age in years
	 * @param string $date date the age was recorded
	 */
	public function getDob($age, $date)
	{
		if($age == "")
		{
			// Assume learner in year 10
			$age = 14;
			$dinfo = getdate();
			$date = $dinfo['mon'] >= 9 ? ($dinfo['year'].'-09-01') : ( ($dinfo['year']-1).'-09-01');
		}

		if($date == ""){
			$date = date('Y-m-d');
		}

		$dt = new DateTime(Date::toMySQL($date));
		$dt->modify("-".mt_rand(1,360)." days");
		$dt->modify("-".$age." years");

		return $dt->format('Y-m-d');
	}

	public function getTelephoneNumber()
	{
		return "01".mt_rand(10,90)." ".mt_rand(100,900)." ".mt_rand(1000,9000);
	}


	public function getAddress(PDO $link, $postcode_district = "")
	{
		$addr = new Address();
		$addr->paon_start_number = mt_rand(1,100);
		$addr->street_description = $this->getStreetName($link);
		$addr->town = $postcode_district ? $postcode_district : $this->getDistrict();
		$addr->postcode = $this->getPostcode($link, $addr->town);

		return $addr;
	}

	public function getStreetName(PDO $link)
	{
		return DAO::getSingleValue($link, "SELECT name FROM testdata.streetnames WHERE id=".mt_rand($this->street_names_start, $this->street_names_end));
	}

	public function getDistrict()
	{
		return $this->postcode_districts[mt_rand(0, count($this->postcode_districts) - 1)];
	}

	public function getPostcode(PDO $link, $district = "")
	{
		$district = $district ? $district : $this->getDistrict($link);
		$a = DAO::getSingleValue($link, "SELECT postcode FROM testdata.postcode_districts WHERE district='"
			.addslashes((string)$district)."' ORDER BY RAND() LIMIT 1");
		return $a . " " . mt_rand(1,9) . chr(mt_rand(65,90)) . chr(mt_rand(65,90));
	}

	public function getLearner(PDO $link, $ks4 = "", $postcode_district = "")
	{
		$learner = new Student();
		$learner->gender = mt_rand(1,2);
		$learner->ethnicity = "WBRI";
		$learner->uln = $this->getULN();
		$learner->upn = $this->getUPN();
		$learner->firstnames = $this->getFirstname($link, $learner->gender);
		$learner->middle_names = $this->getFirstname($link, $learner->gender);
		$learner->surname = $this->getSurname($link);
		$learner->populate($this->getAddress($link, $postcode_district));

		if(!$ks4){
			$dinfo = getdate();
			$ks4 = $dinfo['mon'] >= 9 ? ($dinfo['year'].'-09-01') : ( ($dinfo['year']-1).'-09-01');
		}
		$learner->ks4 = $ks4;
		$d = new Date($ks4);
		$d->addYears(2);
		$learner->ks5 = $d->formatMySQL();
		$learner->dob = $this->getDob(14, $ks4);

		return $learner;
	}

	private $uk_fn_female_start = NULL;
	private $uk_fn_female_range = NULL;
	private $uk_fn_male_start = NULL;
	private $uk_fn_male_range = NULL;
	private $uk_sn_start = NULL;
	private $uk_sn_range = NULL;

	private $street_names_start = NULL;
	private $street_names_end = NULL;

	private $upns = array();
	private $ulns = array();

	private $fn_female = array();
	private $fn_male = array();
	private $sn = array();
	private $postcode_districts = NULL;


}
?>