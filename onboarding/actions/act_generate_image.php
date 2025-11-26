<?php
class generate_image implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		/*
		$number = isset($_GET['title']) ? $_GET['title'] : '';
		$img_number = imagecreate(150,14);
		$white = imagecolorallocate($img_number,255,255,255);
		$black = imagecolorallocate($img_number,0,0,0);
		$grey_shade = imagecolorallocate($img_number,204,204,204);
		//Imagettftext($img_number,10,0,0,12,$black,'..\images\FreeMono.ttf',$number);
		Imagestring($img_number,3,0,0,$number,$black); // to generate an image without using fonts
		$image = imagerotate($img_number,90,0);
		header("Content-type: image/png");
		imagepng($image);
		imagedestroy($image);
		*/

		$text = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
		$font = isset($_REQUEST['font']) ? $_REQUEST['font'] : '';
		$size = isset($_REQUEST['size']) ? $_REQUEST['size'] : '';
		$n =    isset($_REQUEST['n']) ? $_REQUEST['n'] : '';

		if($text=='')
		{
			$text = "Signature";
			$font = "DirtyDarren.ttf";
			$size = "25";
		}

		// Get conditional request headers
		$if_match = $this->getIfMatch();
		$if_none_match = $this->getIfNoneMatch();
		/*$if_modified_since = array_key_exists("HTTP_IF_MODIFIED_SINCE", $_SERVER) ? $_SERVER['HTTP_IF_MODIFIED_SINCE']:'';
		if(($pos = strpos($if_modified_since, ';')) !== false){
			$if_modified_since = substr($if_modified_since, 0, $pos);
		}
		if($if_modified_since){
			$if_modified_since = date_parse_from_format('D, d M Y H:i:s', $if_modified_since); // Convert to UNIX timestamp
		}*/


		// Conditional request handling
		$etag = md5($text);
		if(count($if_match) > 0)
		{
			if(in_array('*', $if_match) || in_array($etag, $if_match))
			{
				header("HTTP/1.x 304 Not Modified");
				return;
			}
			else
			{
				header("HTTP/1.x 412 Precondition failed");
				return;
			}
		}
		elseif(count($if_none_match) > 0)
		{
			if(in_array('*', $if_none_match) || in_array($etag, $if_none_match))
			{
				header("HTTP/1.x 304 Not Modified");
				return;
			}
			else
			{
				$this->streamImage($text,$font,$size);
			}
		}
		else
		{
			$this->streamImage($text,$font,$size);
		}

	}


	private function streamImage($text,$font,$size)
	{
		$im = $this->getTextImage($text,$font,$size);

		header("Content-type: image/png");
		header("ETag: ".md5($text));
		header("Cache-Control: public, must-revalidate, max-age=0"); // public is required for IE to save it over HTTPS
		header("Expires: ");
		header("Pragma: public");

		imagepng($im);
		imagedestroy($im);
	}

	private function getTextImage($text,$font,$size)
	{
		$im = imagecreate(285,49);

		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);
		$grey_shade = imagecolorallocate($im, 204, 204, 204);

		if($size=='')
		{
			Imagestring($im, 3, 0, 0, $text, $black); // to generate an image without using fonts
			$rotated_image = imagerotate($im,90,0);
		}
		else
		{
			$font = $font == '' ? 'DirtyDarren.ttf' : $font;
            $font = "fonts/{$font}";
		    Imagettftext($im, $size, 0, 25, 35, $black, ($font), $text);
		}

		//imagedestroy($im);

		return $im;
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
}