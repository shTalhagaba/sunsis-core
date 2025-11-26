<?php
class Contract extends Entity
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
	contracts
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$org = null;
		if($st)
		{
			$org = null;
			$row = $st->fetch();
			if($row)
			{
				$org = new Contract();
				$org->populate($row);
			}
			
		}
		else
		{
			throw new Exception("Could not execute database query to find contract. " . '----' . $query . '----' . $link->errorCode());
		}

		return $org;	
	}
	
	public function save(PDO $link)
	{
		if(!isset($this->active))
			$this->active=0;		
		
		if(!isset($this->funding_type))
			$this->funding_type=0;

        if(!isset($this->funded))
            $this->funded=0;


        return DAO::saveObjectToTable($link, 'contracts', $this);
	}
	
	public function delete(PDO $link)
	{
		// Placeholder
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		return false;
	}
	
	public static function getListFundingProvisions($funded)
	{
		return
			array(
				'1' => '16-18 Apprenticeship'
				,'2' => '19+ Apprenticeship'
				,'3' => '16-18 Levy Apprenticeship'
				,'4' => '19+ Levy Apprenticeship'
				,'5' => 'All Ages - Levy Apprenticeship'
                		,'14' => 'All Ages - Non-levy Apprenticesahip'
				,'6' => 'Study Programme'
				,'7' => 'Traineeship'
				,'8' => 'Learner Loans'
				,'9' => 'Other'
				,'10' => 'Scottish'
				,'11' => 'Welsh'
				,'12' => 'Irish'
				,'13' => 'Commercial'
				,'15' => 'Apprenticeships - All Ages'
			);
	}

	public static function getDDLFundingProvisions($funded)
	{
		return  array(
			array('1', '16-18 Apprenticeship')
			,array('2', '19+ Apprenticeship')
			,array('3', '16-18 Levy Apprenticeship')
			,array('4', '19+ Levy Apprenticeship')
			,array('5', 'All Ages - Levy Apprenticeship')
            		,array('14', 'All Ages - Non-levy Apprenticeship')
			,array('6', 'Study Programme')
			,array('7', 'Traineeship')
			,array('8', 'Learner Loans')
			,array('9', 'Other')
			,array('10', 'Scottish')
			,array('11', 'Welsh')
			,array('12', 'Irish')
			,array('13', 'Commercial')
			,array('15', 'Apprenticeships - All Ages')
			,array('16', 'AEB')
			,array('17', 'Skills Bootcamp')
			,array('18', 'Free Courses for Jobs')
		);
	}

	public $id = null;
	public $title = NULL;
	public $description = NULL;
	public $contract_holder = NULL;
	public $funding_body = 2;
	public $contract_type = NULL;
	public $start_date = NULL;
	public $end_date = NULL;
	
	public $ukprn = NULL;
	public $upin = NULL;
	public $lsc_no = NULL;
	public $nes_delivery_lsc = NULL;
    public $parent_id = NULL;

	// Assessor reviews
	public $first_review_date = NULL;
	public $frequency = NULL;
	public $subsequent = NULL;
	public $contract_location = NULL;
	public $contract_year = NULL;
	public $L25 = NULL;
	
	public $funding_type = NULL;
	public $proportion = NULL;
	public $active = 1;
    public $funded = NULL;
	public $esf_contract_type = NULL;
	public $template = NULL;
	public $funding_provision = NULL;
    public $allocation_id = NULL;

	public $sync_learners_smart_assessor = NULL;
}
?>