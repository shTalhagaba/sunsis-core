<?php
class XMLException extends Exception
{
	public function __construct($message, $code, $xml = "")
	{
		parent::__construct($message, $code);
		$this->xml = $xml;
	}
	
	public function getXml()
	{
		return $this->xml;
	}
	
	private $xml;
}
?>