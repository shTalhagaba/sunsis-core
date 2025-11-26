<?php

class FundingGenerator
{
	private $contractInfo;
	
	const LAD_DB = 'lad200809';
	const T2GSLN = '2775';
	const ASLN = '2860';	
	
	function __construct($db, $contractInfo)
	{
		// make the contract info available to all of the class
		$this->contractInfo = $contractInfo;
		
		// contract year
		$contractYear = $this->contractInfo->year;
		
		// try and find the relevent years calculator
		//$totalFunding = 
	}
}

?>