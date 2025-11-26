<?php
class view_help implements IAction
{
/*

	public function execute(PDO $link)
	{
		if(!isset($_REQUEST['id']))
		{
			$_SESSION['bc']->index = 0;
			$_SESSION['bc']->add($link, "do.php?_action=view_help", 'Help Center');
			
			$helpItems = Help::getAllHelpItems($link);
			require_once('tpl_view_help.php');
		}
		else
		{
			$id = intval($_REQUEST['id']);
			
			$help = Help::getHelpItem($link, $id);
			
			$_SESSION['bc']->index = 0;
			$_SESSION['bc']->add($link, "do.php?_action=view_help", 'Help Center');
			$_SESSION['bc']->add($link, "do.php?_action=view_help&amp;cid=" . $help->help_category_id, $help->help_category_name);
			$_SESSION['bc']->add($link, "do.php?_action=view_help&amp;id=$id", $help->title);		
			
			require_once('tpl_view_help_item.php');
		}
	}
*/


	public function execute(PDO $link)
	{
		/*
		if($_SESSION['role'] != "admin"){
			throw new UnauthorizedException();
		}
		*/
		$view = View::getViewFromSession('primaryView', 'view_help'); /* @var $view View */
		if(is_null($view))
		{
			$view = $_SESSION['primaryView'] = $this->buildView($link);
		}
		
		$rs = $view->refresh($link, $_REQUEST);
		
		include("tpl_view_help.php");
	}
	
	private function buildView(PDO $link)
	{
		$sql = <<<HEREDOC
SELECT
	`id`,
	`key`,
	`title`,
	`key_redirect`
FROM
	central.help

HEREDOC;
		$view = new View('view_help', $sql);
		$view->setSQL($sql);
				
		return $view;
	}
}

?>