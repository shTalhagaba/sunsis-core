<?php
class display_image
{
	public function execute(PDO $link)
	{
		try
		{
			$username = isset($_GET['username']) ? trim($_GET['username']) : '';
			$candidate_id = isset($_GET['candidate_id']) ? trim($_GET['candidate_id']) : '';
			if($candidate_id=='')
			{
				if(!$username){
					$this->streamImage("images/no_photo.png");
					return;
				}

				$username = str_replace('/', '', $username);
				$user = User::loadFromDatabase($link, $username);
				if(!$user){
					$this->streamImage("images/no_photo.png");
					return;
				}
				$photo_path = $user->getPhotoPath();
			}
			else
			{
				$candidate = Candidate::loadFromDatabase($link, $candidate_id);
				if(!$candidate){
					$this->streamImage("images/no_photo.png");
					return;
				}
				$photo_path = $candidate->getPhotoPath();
			}

			if(!$photo_path){
				$this->streamImage("images/no_photo.png");
				return;
			}

			$this->streamImage($photo_path);
		}
		catch(Exception $e)
		{
			$this->streamErrorImage($e->getMessage());
			email_support($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTrace());
		}
	}
	
	

	
	private function streamImage($file_path)
	{
		if(!file_exists($file_path)){
			throw new Exception("File $file_path does not exist");
		}

		// Get conditional request headers
		$if_match = $this->getIfMatch();
		$if_none_match = $this->getIfNoneMatch();
		$if_modified_since = array_key_exists("HTTP_IF_MODIFIED_SINCE", $_SERVER) ? $_SERVER['HTTP_IF_MODIFIED_SINCE']:'';
		if(($pos = strpos($if_modified_since, ';')) !== false){
			$if_modified_since = substr($if_modified_since, 0, $pos);
		}
		if($if_modified_since){
			$if_modified_since = date_parse_from_format('D, d M Y H:i:s', $if_modified_since); // Convert to UNIX timestamp
		}
		
		// Conditional request handling
		$file_etag = hash_file("sha256", $file_path);
		$file_modified = filemtime($file_path); // UNIX timestamp
		header('ETag: "'.$file_etag.'"');
		header("Last-Modified: ".gmdate('D, d M Y H:i:s', $file_modified).' GMT');
		header("Cache-Control: public, must-revalidate"); // public is required for IE to save it over HTTPS
		header("Pragma: public");
		if(count($if_match) > 0)
		{
			if(in_array('*', $if_match) || in_array($file_etag, $if_match))
			{
				if($file_modified > $if_modified_since)
				{
					header("Content-Type: ".$this->getMimeType($file_path));
					readfile($file_path);
				}
				else
				{
					header("HTTP/1.x 304 Not Modified");
					return;
				}
			}
			else
			{
				header("HTTP/1.x 412 Precondition failed");
				return;
			}
		}
		elseif(count($if_none_match) > 0)
		{
			if(in_array('*', $if_none_match) || in_array($file_etag, $if_none_match))
			{
				if($file_modified > $if_modified_since)
				{
					header("Content-Type: ".$this->getMimeType($file_path));
					readfile($file_path);
				}
				else
				{
					header("HTTP/1.x 304 Not Modified");
					return;
				}
			}
			else
			{
				header("Content-Type: ".$this->getMimeType($file_path));
				readfile($file_path);			
			}
		}
		else
		{
			if($file_modified > $if_modified_since)
			{
				header("Content-Type: ".$this->getMimeType($file_path));
				readfile($file_path);
			}
			else
			{
				header("HTTP/1.x 304 Not Modified");
				return;
			}			
		}
	}
	
	private function streamErrorImage($msg)
	{
	    $im = imagecreatetruecolor (150, 30); /* Create a blank image */
	    $bgc = imagecolorallocate ($im, 255, 255, 255);
	    $tc = imagecolorallocate ($im, 0, 0, 0);
	    imagefilledrectangle ($im, 0, 0, 150, 30, $bgc);
		imagestring ($im, 1, 5, 5, $msg, $tc);
		header("Content-Type: image/png");
		imagepng($im);
		imagedestroy($im);
	}
	
	
	private function getIfMatch()
	{
		$str = array_key_exists("HTTP_IF_MATCH", $_SERVER) ? trim($_SERVER['HTTP_IF_MATCH'],'"'):'';
		if(!$str){
			return array();
		}
		$str = str_replace('"', '', $str);
		$str = str_replace(' ', '', $str);
		return explode(',', $str);
	}
	
	
	private function getIfNoneMatch()
	{
		$str = array_key_exists("HTTP_IF_NONE_MATCH", $_SERVER) ? trim($_SERVER['HTTP_IF_NONE_MATCH'],'"'):'';
		if(!$str){
			return array();
		}
		$str = str_replace('"', '', $str);
		$str = str_replace(' ', '', $str);
		return explode(',', $str);	
	}
	
	
	private function getMimeType($file_path)
	{
		$extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
		$mime_types = array(
			"gif"=>"image/gif",
			"jpg"=>"image/jpeg",
			"jpeg"=>"image/jpeg",
			"png"=>"image/png"
		);
		
		return isset($mime_types[$extension]) ? $mime_types[$extension] : 'application/octet-stream';
	}
}
?>