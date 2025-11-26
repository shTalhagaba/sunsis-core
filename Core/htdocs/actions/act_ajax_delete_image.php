<?php
class ajax_delete_image implements IAction
{
	public function execute(PDO $link)
	{
		$username = isset($_GET['username']) ? trim($_GET['username']) : '';
		$username = str_replace(array('/', '\\'), '', $username);
		if(!$username){
			throw new Exception("Missing or empty querystring parameter: username");
		}
		
		$user = User::loadFromDatabase($link, $username);
		if(!$user){
			throw new Exception("User not found");
		}
		
		$photo_path = $user->getPhotoPath();
		if($photo_path){
			unlink($photo_path);
		}
		
		// Remove all images in the user's photos directory
		/*$path = DATA_ROOT."/uploads/".DB_NAME."/".$username."/photos";
		if( file_exists($path) ) {			
			$this->remove_image($path);
		}
		
		// Return an empty image
		$path = "../htdocs/images/no_photo.png";
		$im = @imagecreatefrompng($path); // Attempt to open
		if (!$im) { 
		        $im = imagecreatetruecolor (150, 30); // Create a blank image
		        $bgc = imagecolorallocate ($im, 255, 255, 255);
		        $tc = imagecolorallocate ($im, 0, 0, 0);
		        imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);
		        // Output an errmsg
		        imagestring ($im, 1, 5, 5, "$err", $tc);
	    }
		header("Content-Type: image/png");
		imagepng($im);
		imagedestroy($im);
		*/
	}
	
	/*private function remove_image( $directory )
	{
		if(!is_dir($directory)){
			return;
		}
		
		$TrackDir=opendir($directory);
		while ( false !== ( $filename = readdir($TrackDir) ) ) { 
			if ( $filename != "." && $filename != ".." ) {
				// get file extension
				$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
				// throw new Exception($ext." >> ".$directory."/".$filename);
				if ( array_key_exists($ext, $this->image_paths) ) {
					// remove the file
					unlink($directory."/".$filename);
				}	
			}
		}
		closedir($TrackDir);
	}
	*/
	
	//private $image_paths = array('jpg' => 1,'jpeg' => 1,'gif' => 1,'png' => 1);
}
?>