<?php
class display_help implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$key = isset($_REQUEST['key']) ? $_REQUEST['key'] : '';
		$content = isset($_REQUEST['content']) ? $_REQUEST['content'] : '';
		$title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
		
		if($key == '' && $content == '') {
			throw new Exception("Value required for 'key' or 'content'");
		}
	
		$key = Help::cleanLookupKey($key);
		
		if($key)
		{
			$vo = $this->getHelp($link, $key);
		}
		else
		{
			// When no key is specified, read the content from
			// the 'content' querystring argument
			$vo = new Help();
			$vo->key = "";
			$vo->title = $title;
			$vo->default_content = HTML::wikify($content);			
		}
		
		// Presentation
		include('tpl_display_help.php');
	}

	/**
	 * Tries to load Help object from cache first, then the database
	 * 
	 * @param mysqli $link
	 * @param string $key
	 * @throws Exception
	 */
	private function getHelp(PDO $link, $key)
	{
		$key = Help::cleanLookupKey($key);
		$xcache_key = $_SERVER['SERVER_NAME'].' help '.$key;
		
	
		
		$ttl = 600;
			
		
		
		//if(defined("XC_TYPE_VAR") && xcache_isset($xcache_key))
	//	{
	//		$values = xcache_get($xcache_key);
	//	}
	//	else
	//	{
			// No cached content. Load from database
			$values = array("key"=>$key, "title"=>"", "default_content"=>"", "admin_content"=>"", "partnership_content"=>"", "provider_content"=>"", "school_content"=>"");
			$help = Help::loadFromDatabase($link, $key);
			if(is_null($help)){
				$help = new Help();
				$help->key = $key;
				$help->title = $key;
				return $help;
			}
			
			// Follow redirections to other Help pages
			$count = 0;
			$max_redirects = 5;
			$key_redirect = $help->key_redirect;
			while(!is_null($help) && $key_redirect && $count < $max_redirects){
				$help = Help::loadFromDatabase($link, $key_redirect);
				$count++;
			}
			if(is_null($help)){
				throw new Exception("Cannot follow redirect to Help page '".$key_redirect."'. Page does not exist.");
			}
			
			$values['id'] = $help->id;
			$values['key'] = $key;
			$values['title'] = $help->title;
			$values['default_content'] = Help::wikify($help->default_content);
			//$values['admin_content'] = Help::wikify($help->admin_content);
			//$values['partnership_content'] = Help::wikify($help->partnership_content);
			//$values['provider_content'] = Help::wikify($help->provider_content);
			//$values['school_content'] = Help::wikify($help->school_content);
		//	if(defined("XC_TYPE_VAR")){
		//		xcache_set($xcache_key, $values, $ttl);
		//	}
	//	}
		
		$help = new Help();
		$help->populate($values);
		
		return $help;
	}
	
/*	
	private function getUserRole()
	{
		switch($_SESSION['role'])
		{
			case "admin":
			case "partnership":
				return $_SESSION['role'];
			
			case "user":
				if($_SESSION['org']->org_type_id == ORG_SCHOOL)
				{
					return "school";
				}
				else
				{
					return "provider";
				}
				
			default:
				throw new Exception("Unknown user type");
		}
	}
*/	
	
	private function renderHelp(Help $help)
	{
		if(!$help->default_content)
		{
			if($_SESSION['role'] == 'admin')
			{
				echo <<<HEREDOC
<p>No help page exists for key <b>{$help->key}</b>. Do you want to create one?</p>
<p><button onclick="window.opener.location.href='do.php?_action=edit_help&key={$help->key}';window.close();">Create help page</button></p>
HEREDOC;
			}
		}
		else
		{
			/*
			if($_SESSION['role'] == 'admin' && $help->admin_content)
			{
				echo $help->admin_content;
			}
			elseif($_SESSION['role'] == 'partnership' && $help->partnership_content)
			{
				echo $help->partnership_content;
			}
			elseif($_SESSION['org']->org_type_id == ORG_PROVIDER && $help->provider_content)
			{
				echo $help->provider_content;
			}
			elseif($_SESSION['org']->org_type_id == ORG_SCHOOL && $help->school_content)
			{
				echo $help->school_content;
			}
			
			else
			*/
			{
				echo $help->default_content;
			}
		}
	}
}
?>
