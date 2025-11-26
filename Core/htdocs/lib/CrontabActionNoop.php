<?php
class CrontabActionNoop extends CrontabAction
{
	public function __construct()
	{
		$this->task = 'Noop';
	}

	public function execute(PDO $link)
	{
		//
	}
}