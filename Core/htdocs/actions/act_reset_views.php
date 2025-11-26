<?php
class reset_views implements IAction
{
	public function execute(PDO $link)
	{
		foreach($_SESSION as $key=>$value)
		{
			if(preg_match('/^view_/', $key))
			{
				echo "<p>Unloading <code>$key</code></p>";
				unset($_SESSION[$key]); 
			}
		}
		
		echo '<p><b>Done</b></p>';
	}
}
?>