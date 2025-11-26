<?php
class delete_repository_file implements IAction
{
	public function execute(PDO $link)
	{

		$path = DATA_ROOT."/uploads/".$_GET['path'];
		$f = $_GET['f'];
		
	
		
		if(file_exists($path))
		{		

			$TrackDir=opendir($path);
			
			while ($file = readdir($TrackDir)) 
			{ 
				
				
				if($file == $f)
				{
					$dir = $path . $file;
					if(is_dir($dir)) 
					{ 
     					$objects = scandir($dir); 
     					foreach ($objects as $object) 
     					{ 
       						if ($object != "." && $object != "..") 
       						{ 
         						if (filetype($dir."/".$object) == "dir") rmdir($dir."/".$object); else unlink($dir."/".$object);
       						} 
     					} 
     					reset($objects); 
     					rmdir($dir); 
   					} 
					else 
					{
						unlink($path . $file);
					}
				}
			} 
			closedir($TrackDir); 
		}	

		http_redirect('do.php?_action=file_repository');	

	}
}
?>