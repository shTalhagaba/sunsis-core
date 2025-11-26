<?php
/**
 * This file uploads a file in the back end, without refreshing the page
 *  
 */
class ajax_image_upload implements IAction
{
	public function execute(PDO $link)
	{
		$paths = Repository::processFileUploads('myfile', null, array('jpg', 'jpeg', 'gif', 'png'));
		$result = count($paths) > 0 ? '1':'0';
		
		sleep(1); // why?
		
		header("Content-Type: text/html");
		echo <<<HEREDOC
		<script type="text/javascript">window.top.frames['right'].stopUpload($result);</script> 
HEREDOC;

	}
}
?>