<?php
class UnauthenticatedException extends Exception
{
	public function _construct($message = "Your session has either expired or the credentials you have supplied are unknown to Sunesis", $code = 0)
	{
		parent::__construct($message, $code);
	}
}
?>