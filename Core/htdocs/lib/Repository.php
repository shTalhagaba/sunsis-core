<?php
class Repository
{
	/**
	 * Processes file uploads
	 * @static
	 * @param string $form_field_name
	 * @param string $target_directory
	 * @param array $valid_extensions
	 * @param integer $max_individual_file_size The maximum size of each file (bytes)
	 * @return array an array of the absolute paths of all uploaded files
	 * @throws Exception
	 */
	public static function processFileUploads($form_field_name, $target_directory, array $valid_extensions = array(), $max_individual_file_size = 0)
	{
		if(!isset($_FILES[$form_field_name])){
			return array();
		}
		if(!isset($_FILES[$form_field_name]['name'])){
			return array();
		}
		if(is_array($_FILES[$form_field_name]['name']) && count($_FILES[$form_field_name]['name']) == 0){
			return array();
		}
		if(!is_array($_FILES[$form_field_name]['name']) && $_FILES[$form_field_name]['name'] == ''){
			return array();
		}
		
		// Shorten the reference to the files array from hereon
		$field = $_FILES[$form_field_name];
		
		// Support both single and multiple upload modes
		// Convert single upload mode to multiple
		if(!is_array($field['error'])){
			$field['error'] = array($field['error']);
		}
		if(!is_array($field['tmp_name'])){
			$field['tmp_name'] = array($field['tmp_name']);
		}
		if(!is_array($field['name'])){
			$field['name'] = array($field['name']);
		}
		if(!is_array($field['size'])){
			$field['size'] = array($field['size']);
		}
		if(!is_array($field['type'])){
			$field['type'] = array($field['type']);
		}

		
		// Check for errors
		for($i = 0; $i < count($field['error']); $i++)
		{
			if($field['error'][$i] === UPLOAD_ERR_OK){
				continue; // No error
			}
			
			$filename = isset($field['name'][$i]) ? $field['name'][$i] : "The file ";
			switch($field['error'][$i])
			{	
				case UPLOAD_ERR_INI_SIZE:
					throw new Exception("$filename exceeded the global maximum upload size of ".ini_get("upload_max_filesize"));
					break;
					
				case UPLOAD_ERR_FORM_SIZE:
					throw new Exception("$filename exceeded the maximum upload size");
					break;
					
				case UPLOAD_ERR_PARTIAL:
					throw new Exception("$filename was only partially uploaded");
					break;
					
				case UPLOAD_ERR_NO_FILE:
					throw new Exception("No file was uploaded");
					break;
					
				case UPLOAD_ERR_NO_TMP_DIR:
					throw new Exception("Missing temporary folder");
					break;
				
				case UPLOAD_ERR_CANT_WRITE:
					throw new Exception("Failed to write file to disk");
					break;

				case UPLOAD_ERR_EXTENSION:
					throw new Exception("File upload stopped by extension");
					break;
					
				default:
					throw new Exception("Unknown file-upload error code: ".$field['error'][$i]);
					break;
			}
		}
		
		// Confirm maximum individual file size has not been exceeded
		if($max_individual_file_size > 0)
		{
			for($i = 0; $i < count($field['size']); $i++)
			{
				if($field['size'][$i] > $max_individual_file_size){
					throw new Exception("File ".$field['name'][$i]." exceeds the maximum file size of ".Repository::formatFileSize($max_individual_file_size));
				}
			}			
		}
		
		// Confirm the upload quota has not been exceeded
		$total_size = 0;
		foreach($field['size'] as $size){
			$total_size += $size;
		}
		$remaining_space = Repository::getRemainingSpace();
		if($total_size > $remaining_space)
		{
			throw new Exception("Uploaded files (" . Repository::formatFileSize($total_size) . ") exceed the remaining space available ("
				. Repository::formatFileSize($remaining_space) . ")");
		}
		
		// Confirm the file extension is authorised
		if(count($valid_extensions) > 0)
		{
			array_walk($valid_extensions, function(&$item, $key){$item = strtolower($item);}); // convert all valid extensions to lower-case
			foreach($field['name'] as $filename)
			{
				$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
				if(!in_array($ext, $valid_extensions)){
					throw new Exception("File $filename is of a type not authorised for storage in this system. Authorised types include: ".implode(', ', $valid_extensions));
				}
			}
		}
		// Confirm the target directory exists
		$target_directory = trim($target_directory, '/\\ ');
		$target_path = Repository::getRoot().($target_directory?('/'.$target_directory):'');
		if(is_file($target_path)){
			throw new Exception("Target path '$target_directory' is a regular file and so cannot accept uploads");
		}
		if(!is_dir($target_path)){
			if(!mkdir($target_path, 0770, true)){
				throw new Exception("The target directory '$target_directory' does not exist and all attempts to create it have failed. Check file permissions.");
			}
		}

		///////////////// if file with the same name already exists in the directory then change the file name////////////
		$absolute_paths = array();
		for($i = 0; $i < count($field['tmp_name']); $i++)
		{
			$file = pathinfo($target_path . "/" . $field['name'][$i]);
			$filename = $file['filename'];
			$j = 1;
			while(file_exists($target_path . "/" . $filename . "." . $file['extension'])){
				$filename = $file['filename']." ($j)";
				$j++;
			}
			$field['name'][$i] = $filename .".". $file['extension'];
			$cleanName = preg_replace('/[^A-Za-z0-9 .,()\\-_]/', '', $field['name'][$i]);
			$absolute_paths[] = $target_path.'/'.$cleanName;
			move_uploaded_file($field['tmp_name'][$i], $target_path.'/'.$cleanName);
		}


		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
/*		// Copy files to section
		$absolute_paths = array();
		for($i = 0; $i < count($field['tmp_name']); $i++)
		{
			$cleanName = preg_replace('/[^A-Za-z0-9 .,()\\-_]/', '', $field['name'][$i]);
			$absolute_paths[] = $target_path.'/'.$cleanName;
			move_uploaded_file($field['tmp_name'][$i], $target_path.'/'.$cleanName);
		}*/
		
		// Return an array of the absolute file paths of all uploaded files
		return $absolute_paths;
	}
	
	/**
	 * The root upload directory for this website
	 * @static
	 * @return string DATA_ROOT.'/uploads/'.DBNAME
	 * @throws Exception
	 */
	public static function getRoot()
	{
		$root = DATA_ROOT.'/uploads/'.DB_NAME;
		if(!file_exists($root)){
			if(!mkdir($root)){
				throw new Exception("Upload directory does not exist and all attempts to create it have failed. Check file permissions.");
			}
		}
		return $root;
	}
	
	/**
	 * @static
	 * @param int $size
	 * @return string
	 */
	public static function formatFileSize($size) 
	{
		$sizes = array("&nbsp;B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");

		$i = 0;
		while($size > 1024){
			$size = $size/1024;
			$i++;
		}
		
		return sprintf("%.1f&nbsp;" . $sizes[$i], $size);
		//return sprintf("%.1f",($size/pow(1024, ($i = floor(log($size, 1024))))) . $sizes[$i]);
	}
	
	/**
	 * Convert any file size to bytes
	 * @static
	 * @param string $size
	 * @return integer file size in bytes
	 */
	public static function parseFileSize($size)
	{
		if(is_numeric($size)){
			return $size;
		}
		
		$size = strtoupper(trim($size));
		if(preg_match('/^([0-9\\.]+)\\s*(B|K|M|G|T)/', $size, $matches))
		{
			$num = $matches[1];
			$pow = $matches[2];
			switch($pow)
			{
				case 'B':
					return $num;
				case 'K':
					return $num * 1024;
				case 'M':
					return $num * 1024 * 1024;
				case 'G':
					return $num * 1024 * 1024 * 1024;
				case 'T':
					return $num * 1024 * 1024 * 1024 * 1024;
				default:
					return $size;
			}
		}
		else
		{
			return $size;
		}
	}
	
	/**
	 * Maximum file size for uploads
	 * @static
	 * @return integer maximum size (bytes)
	 */
	public static function getMaxFileSize()
	{
		return Repository::parseFileSize(ini_get("upload_max_filesize"));
	}
	
	/**
	 * Defaults to 500MB, but can be changed by entering a value for
	 * 'repository.key' in the 'configuration' database table. The value in the configuration table
	 * should be suffixed with a unit: K, M or G (Kilobytes, Megabytes or Gigabytes).
	 *
	 * @static
	 * @return integer total storage space allocated to the repository (bytes)
	 */
	public static function getTotalSpace()
	{
		$default = 500 * (1024 * 1024); // 500M
		$space = SystemConfig::get('repository.space');
		if (!$space) {
			$space = $default;
		} else {
			$space = strtoupper($space);
			if (preg_match('/([0-9]+)([KMG])/', $space, $matches)) {
				switch ($matches[2]) {
					case 'K':
						$space = $matches[1] * (1024);
						break;
					case 'M':
						$space = $matches[1] * (1024 * 1024);
						break;
					case 'G':
						$space = $matches[1] * (1024 * 1024 * 1024);
						break;
					default:
						$space = $default;
						break;
				}
			} else {
				$space = $default;
			}
		}

		return $space;
	}
	
	
	/**
	 * Convenience method for the calculation Repository::getTotalSpace() - Repository::getUsedSpace()
	 * @static
	 * @return integer remaining space in bytes
	 */
	public static function getRemainingSpace()
	{
		return Repository::getTotalSpace() - Repository::getUsedSpace();
	}
	
	
	/**
	 * The amount of space already consumed by this site
	 * @static
	 * @return integer used space in bytes
	 */
	public static function getUsedSpace()
	{
		$upload_dir = new RepositoryFile(Repository::getRoot());
		return $upload_dir->getSize();
	}
	

	/**
	 * Read the contents of a directory into an array
	 * @static
	 * @param string $absolute_path
	 * @return RepositoryFile[] an array of RepositoryFile objects
	 */
	public static function readDirectory($absolute_path = "")
	{
		if(!$absolute_path){
			$absolute_path = Repository::getRoot();
		}
		$absolute_path = rtrim($absolute_path, '/\\ ');
		if(!is_dir($absolute_path)){
			return array();
		}
		
		$files = array();
		$dir = opendir($absolute_path);
		while ($file = readdir($dir))
		{
			if($file != '.' && $file != '..')
			{
				$files[] = new RepositoryFile($absolute_path.'/'.$file);
			}
		}
		closedir($dir);
		return $files;
	}

    public static function delTree($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? delTree($file) : unlink($file);
        }
        rmdir($path);
        return;
    }

	public static function getNumberOfFilesInDirectory($absolute_path = '')
	{
		$files = self::readDirectory($absolute_path);
		foreach($files AS $index => $value)
		{
			if($value->isDir())
			{
				unset($files[$index]);
			}
		}
		$files = array_values($files);
		return count($files);
	}

    public static function getRepositorySize($username)
    {
        $path = Repository::getRoot() . "/" . $username;
        $line = exec('du -sh ' . $path);
        $line = trim(str_replace($path, '', $line));
        return $line;
    }

    public static function downloadRepository($username,$filename)
    {
        $path = Repository::getRoot() . "/" . $username;
        $filename = Repository::getRoot() . "/test.zip";
        ExtendedZip::zipTree($path, $filename, ZipArchive::CREATE);
        //http_redirect("do.php?_action=downloader&path=/am_raytheon/data_dump/&f=data.zip");
    }

/**
	 * @return array
	 */
	public static function getAllowedMimeTypes()
	{
		return [
			'application/msword', 
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
			'application/vnd.ms-excel',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'text/csv',
			'text/plain',
			'application/xml',
			'application/zip',
			'application/x-rar-compressed',
			'application/x-rar-compressed',
			'application/x-7z-compressed',
			'application/pdf'
		];
	}

}