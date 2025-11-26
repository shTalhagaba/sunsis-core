<!-- Combo-handled YUI CSS files: --> 
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/combo?2.6.0/build/menu/assets/skins/sam/menu.css"> 
<!-- Combo-handled YUI JS files: --> 
<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.6.0/build/yahoo-dom-event/yahoo-dom-event.js&2.6.0/build/container/container_core-min.js&2.6.0/build/menu/menu-min.js"></script> 

<?php
/**
 * Shared Action
 *
 */
class yui_test implements IAction
{
	public function execute(PDO $link)
	{

		echo "OK";
	}

}
?>