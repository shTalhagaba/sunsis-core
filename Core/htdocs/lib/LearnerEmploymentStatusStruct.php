<?php

class LearnerEmploymentStatusStruct extends Entity
{
	public function __construct()
	{

	}

	public static function loadFromXML($xml)
	{
		$vo = XML::loadSimpleXML($xml);
		return $vo;
	}

	public $EmpStat = null;
	public $DateEmpStatApp = null;
	public $EmpId = null;
	public $SEI = null;
	public $EII = null;
	public $LOU = null;
	public $LOE = null;
	public $BSI = null;
	public $PEI = null;
	public $RON = null;
	public $SEM = null;

}
?>