<?php
class downloader implements IAction
{
	public function execute(PDO $link)
	{
		set_time_limit(0); // Don't time out during download

		$root = $this->getRoot();
		$absolute_path = $this->getAbsolutePath(); // Ends in '/'
		$file_name = $this->getFile(); // Can include path info
		$file_path = $absolute_path.$file_name;
		
		// Insert the database name if absent
		if(!preg_match("#^$root/(am_demo|".DB_NAME.")#", $file_path)){
			$file_path = preg_replace("#^$root#", $root.'/'.DB_NAME, $file_path);
		}
		
		// Check file exists and is a regular file
		if(!is_file($file_path)) {
			throw new Exception("File " . basename($file_path) . " does not exist."); 
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
					$this->streamFile($file_path);
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
					$this->streamFile($file_path);
				}
				else
				{
					header("HTTP/1.x 304 Not Modified");
					return;
				}
			}
			else
			{
				$this->streamFile($file_path);				
			}
		}
		else
		{
			if($file_modified > $if_modified_since)
			{
				$this->streamFile($file_path);
			}
			else
			{
				header("HTTP/1.x 304 Not Modified");
				return;
			}			
		}
		
		// log downloads
		define('LOG_DOWNLOADS', true);
		if(LOG_DOWNLOADS)
		{
			$log_file = $this->getLogFile();
			$f = @fopen($log_file, 'a+');
			if($f){
				@fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ". $_SESSION['user']->username . " " . $file_path . "\n");
				@fclose($f);
			}			
		}
	}
	
	/**
	* @return string path ending in a path separator '/'
	* @throws Exception
	*/
	private function getRoot()
	{
		$root = str_replace('\\', '/', DATA_ROOT); // Convert windows path separtors to Linux
		$root = rtrim($root,' /').'/uploads';
		if(!is_dir($root)){
			throw new Exception("Missing uploads directory");
		}
		return $root;
	}

	/**
	 * @return string absolute path ending in a path separator '/'
	 */
	private function getAbsolutePath()
	{
		$path = isset($_GET['path'])?$_GET['path']:'/';
		if(!$path){
			$path = '/';
		}

		// Convert windows path separators to Linux
		$path = str_replace('\\', '/', $path);
		
		// Remove parent directory notation
		$path = str_replace('../', '', $path);
		
		// Remove special characters
		$path = str_replace(array(':', ';'), '', $path);
		
		// Remove duplicate path separators
		$path = preg_replace('#/{2,}#', '/', $path);
		
		// Pre-pend and append path separators
		if($path[0] != '/'){
			$path = '/'.$path;
		}
		if($path[strlen($path) - 1] != '/'){
			$path = $path.'/';
		}
		
		// Prepend root if required
		$root = $this->getRoot();
		if(!preg_match("#^$root#", $path)){
			$path  = $root.$path;
		}
		
		return $path;
	}
	
	/**
	 * 
	 * @return string relative path (does not start with '/')
	 */
	private function getFile()
	{
		$f = isset($_GET['f'])?trim($_GET['f']):'';
		if(!$f){
			throw new Exception("No file specified");
		}
		
		// Convert windows path separators to Linux
		$f = str_replace('\\', '/', $f);
		
		// Remove parent directory notation
		$f = str_replace('../', '', $f);
		
		// Remove special characters
		$f = str_replace(array(':', ';'), '', $f);
		
		// Remove duplicate path separators
		$f = preg_replace('#/{2,}#', '/', $f);

		// Strip path separators (left and right)
		$f = trim($f, '/ ');
		
		return $f;
	}
	
	/**
	 * @return string
	 */
	private function getLogFile()
	{
		return $this->getRoot().'/downloads.log';
	}

	/**
	 * @return array
	 */
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

	/**
	 * @return array
	 */
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

	/**
	 * @param string $file_path
	 */
	private function streamFile($file_path)
	{
		$file_name = basename($file_path);
		$file_size = filesize($file_path); 
		$mime_type = $this->getMimeType($file_path);
		
		header("Content-Type: $mime_type");
		header("Content-Disposition: attachment; filename=\"$file_name\"");
		header("Content-Length: " . $file_size);
		
		readfile($file_path);
	}

	/**
	 * @param string $path
	 * @return string
	 */
	private function getMimeType($path)
	{
		$path = basename($path);
		$mime_map = array(
			"xls"=>"application/vnd.ms-excel",
			"xlsx"=>"application/vnd.ms-excel",
			"chm"=>"application/vnd.ms-htmlhelp",
			"ppt"=>"application/vnd.ms-powerpoint",
			"pps"=>"application/vnd.ms-powerpoint",
			"pot"=>"application/vnd.ms-powerpoint",
			"doc"=>"application/msword",
			"dot"=>"application/msword",
			"docx"=>"application/msword",
			"pdf"=>"application/pdf",
			"txt"=>"text/plain",
			"rtf"=>"application/rtf",
			"odt"=>"application/vnd.oasis.opendocument.text",
			"rar"=>"application/x-rar-compressed",
			"swf"=>"application/x-shockwave-flash",
			"zip"=>"application/zip",
			
			"gif"=>"image/gif",
			"jpeg"=>"image/jpeg",
			"jpg"=>"image/jpeg",
			"jpe"=>"image/jpeg",
			"jp2"=>"image/jp2",
			"j2c"=>"image/jp2",
			"jpc"=>"image/jp2",
			"j2k"=>"image/jp2",
			"jpx"=>"image/jp2",
			"png"=>"image/png",
			"bmp"=>"image/x-bmp",
			"tiff"=>"images/tiff",
			"ico"=>"image/x-icon",
			"wmf"=>"application/x-msmetafile",
			"emf"=>"application/x-msmetafile",
			
			"css"=>"text/css",
			"js"=>"application/javascript",
			"html"=>"text/html",
			"xml"=>"text/xml"
		);
		
		// Set mime type
		$mime_type = "application/octet-stream";
		$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
		if($ext)
		{
			if(array_key_exists($ext, $mime_map))
			{
				$mime_type = $mime_map[$ext];
			}
		}
		
		return $mime_type;
	}
}
?>