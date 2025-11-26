<?php
class Z1AdministrationException extends Exception
{
	public function __construct($message = null, $code = 0, array $daemonResponse = null)
	{
		$this->daemonResponse = $daemonResponse;
		
		parent::__construct($message, $code);
	}
	
	
	public function getDaemonResponse()
	{
		return $this->daemonResponse;
	}
	
	private $daemonResponse = null;
}
?>