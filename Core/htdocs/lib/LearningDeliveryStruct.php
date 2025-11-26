<?php

class LearningDeliveryStruct extends Entity
{
	public function __construct($LearnAimRef)
	{
		if($LearnAimRef == '')
			throw new Exception('Learning Aim Reference required');

		$this->LearnAimRef = $LearnAimRef;
	}

	public static function loadFromXML($xml)
	{
		$vo = XML::loadSimpleXML($xml);
		return $vo;
	}

	public $LearnAimRef = null;
	public $AimType = null;
	public $AimSeqNumber = null;
	public $LearnStartDate = null;
	public $OrigLearnStartDate = null;
	public $LearnPlanEndDate = null;
	public $FundModel = null;
	public $ProgType = null;
	public $FworkCode = null;
	public $PwayCode = null;
	public $PartnerUKPRN = null;
	public $DelLocPostCode = null;
	public $AddHours = null;
	public $PriorLearnFundAdj = null;
	public $OtherFundAdj = null;

	public $ConRefNumber = null;
	public $EmpOutcome = null;
	public $CompStatus = null;
	public $LearnActEndDate = null;
	public $WithdrawReason = null;
	public $Outcome = null;
	public $AchDate = null;
	public $OutGrade = null;
	public $SWSupAimID = null;

	public $SOF = null;
	public $FFI = null;
	public $WPL = null;
	public $EEF = null;
	public $RES = null;
	public $LSF = null;
	public $ADL = null;
	public $ALB = null;
	public $ASL = null;
	public $FLN = null;
	public $LDM = null;
	public $SPP = null;
	public $NSA = null;
	public $WPP = null;
	public $POD = null;
	public $TBS = null;
	public $HEM = null;
	public $HHS = array();
	//public $DateFrom = null;
	//public $DateTo = null;


}
?>