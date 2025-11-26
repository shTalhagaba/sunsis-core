<?php 
class RepositoryFile
{
	/**
	 * A file
	 * @param string $path absolute path
	 */
	public function __construct($absolute_path)
	{
		if(!file_exists($absolute_path)){
			throw new Exception("File does not exist");
		}

		$this->absolute_path = $absolute_path;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return basename($this->absolute_path);
	}

	/**
	 * @return string
	 */
	public function getAbsolutePath()
	{
		return $this->absolute_path;
	}

	/**
	 * @return string
	 */
	public function getRelativePath()
	{
		return str_replace(Repository::getRoot(), '', $this->absolute_path);
	}

	/**
	 * @return string
	 */
	public function getDownloadURL()
	{
		return "do.php?_action=downloader&f=".rawurlencode($this->getRelativePath());
	}

	/**
	 * @return string
	 */
	public function getDeletionURL()
	{
		return "do.php?_action=delete_file&f=".rawurlencode($this->getRelativePath());
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		if(is_file($this->absolute_path))
		{
			return filesize($this->absolute_path);
		}
		elseif(is_dir($this->absolute_path))
		{
			if(PHP_OS == "Linux")
			{
				return `du --summarize --bytes {$this->absolute_path} | cut --fields=1`;
			}
			elseif(PHP_OS == "WINNT")
			{
				$total_size = 0;
				$files = $this->getContents();
				foreach($files as $f)
				{
					$total_size += $f->getSize();
				}
				return $total_size;
			}
			else
			{
				return 0;
			}
		}
		return 0;
	}

	/**
	 * @return int
	 */
	public function getModifiedTime()
	{
		return filemtime($this->absolute_path);
	}

	/**
	 * @return bool
	 */
	public function isDir()
	{
		return is_dir($this->absolute_path);
	}

	/**
	 * @return bool
	 */
	public function isFile()
	{
		return is_file($this->absolute_path);
	}
	
	/**
	 * If this file is a directory, returns the contents
	 * @return RepositoryFile[] array of RespositoryFile objects or an empty array if this file is not a directory
	 */
	public function getContents()
	{
		if(!is_dir($this->absolute_path)){
			return array();
		}
		return Repository::readDirectory($this->absolute_path);
	}

	public function getExtension()
	{
		if(!$this->isFile())
			return '';
		$ext = new SplFileInfo($this->getName());
		return $ext->getExtension();
	}

	private $absolute_path;
}


?>