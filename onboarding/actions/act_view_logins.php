<?php
class view_logins implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
		if($subaction == 'get_ip_geo_location')
		{
			if(!isset($_REQUEST['ip']))
				return;
			echo $this->get_ip_geo_location($_REQUEST['ip']);
			return;
		}

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_logins", "View Logins");

		$view = ViewLogins::getInstance();
		$view->refresh($link, $_REQUEST);
		
		require_once('tpl_view_logins.php');
	}

	public function get_ip_geo_location($ip)
	{
		$result = '';
		$ip_result = file_get_contents("http://api.ipstack.com/{$ip}?access_key=663252003ee7f530a19626f737f10f3d");
		$ip_result = json_decode($ip_result);
		if(isset($ip_result->error) && is_object($ip_result->error))
		{
			$result .= '<h5 class="text-bold">Error: </h4>';
			$result .= '<p><label class="label label-danger">' . $ip_result->error->code . ' - ' . $ip_result->error->type . '</label></p>';
			$result .= '<p class="text-red">' . $ip_result->error->info . '</p>';
		}
		else
		{
			$result = '<h5 class="text-bold">IP Geo Location: </h4>';
			$result .= '<div class="small">';
			$result .= '<p><span class="text-bold">IP:</span> &nbsp; ' . $ip_result->ip . ' (' . $ip_result->type . ')</p>';
			$result .= '<p><span class="text-bold">Region:</span> &nbsp; ' . $ip_result->region_code . ' - ' . $ip_result->region_name . '</p>';
			$result .= '<p><span class="text-bold">City & Zip:</span> &nbsp; ' . $ip_result->city . ' - ' . $ip_result->zip . '</p>';
			$result .= '<p><span class="text-bold">Country:</span> &nbsp; ' . $ip_result->country_code . ' - ' . $ip_result->country_name . '</p>';
			$result .= '<p><span class="text-bold">Continent:</span> &nbsp; ' . $ip_result->continent_code . ' - ' . $ip_result->continent_name . '</p>';
			$result .= '</div>';
		}
		return $result;
	}
}
?>