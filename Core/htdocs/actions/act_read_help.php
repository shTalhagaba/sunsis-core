<?php
class read_help implements IAction
{
	public function execute(mysqli $link)
	{
		if($_SESSION['role'] != "admin"){
			throw new UnauthorizedException();
		}

		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

		if($id == '') {
			throw new Exception("Missing querystring argument id");
		}

		$vo = Help::loadFromDatabase($link, $id);
		if(is_null($vo)) {
			throw new Exception("No record with id ".$id);
		}

		$isSafeToDelete = $vo->isSafeToDelete($link, $id);

		switch($subaction)
		{
			case "zoneStatus":
				$this->renderZoneStatus($link, $vo);
				return;
				break;

			default:
				break;
		}

		// Presentation
		include('tpl_read_help.php');
	}

	private function renderContent(Help $help, $content)
	{
		if(!$content){
			return;
		}

		echo '<div class="Wiki" style="margin-left:10px;">';
		echo '<h1>';
		echo htmlspecialchars((string)$help->title);
		echo '</h1>';
		echo HTML::wikify($content);
		echo '</div>';
	}

}
?>