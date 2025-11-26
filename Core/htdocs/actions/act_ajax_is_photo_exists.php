<?php
class ajax_is_photo_exists implements IAction
{
	public function execute(PDO $link)
	{
		$username = isset($_GET['username']) ? trim($_GET['username']) : '';
		if(!$username){
			header('Content-Type: text/plain; charset=iso-8859-1');
			echo "N";
		}
		
		$username = str_replace('/', '', $username);
		$photopath = $this->getPhotoPath($username);
		
		header('Content-Type: text/plain; charset=iso-8859-1');
		echo $photopath ? 'Y':'N';
	}
}
?>