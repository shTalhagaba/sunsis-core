<?php
class WrappedException extends Exception
{
	public function __construct(Exception $e)
	{
		$this->wrapped_exception = $e;
		parent::__construct(basename($e->getFile()).'('.$e->getLine().'): '.$e->getMessage(), $e->getCode());
	}
	
	public function getWrappedException()
	{
		return $this->wrapped_exception;
	}
	
	
	private $wrapped_exception;
}
?>