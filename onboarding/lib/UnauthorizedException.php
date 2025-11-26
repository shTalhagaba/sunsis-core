<?php
class UnauthorizedException extends Exception
{
	public function __construct($message = "You are not authorised to perform this operation", $code = 0)
	{
		parent::__construct($message, $code);
	}
}
?>