<?php
class copy_subtree implements IAction
{
	public function execute(PDO $link)
	{
		
		// Check arguments
		$clipboardType = isset($_REQUEST['clipboardType'])?$_REQUEST['clipboardType']:'';
		$clipboard = isset($_REQUEST['clipboard'])?$_REQUEST['clipboard']:'';
		$clipboardNode = isset($_REQUEST['clipboardNode'])?$_REQUEST['clipboardNode']:'';

		$_SESSION['user']->clipboardType 	= 	$clipboardType;
		$_SESSION['user']->clipboard 		=	$clipboard;
		$_SESSION['user']->clipboardNode	=	$clipboardNode;	
			
	}
}
?>