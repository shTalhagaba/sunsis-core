<?php

class Help extends ValueObject
{
	// db fields for when it becomes a ValueObject
	public $help_id;
	public $help_category_id;
	public $title;
	public $content;
	public $display_order;
	public $help_category_name;
	public $id;
	public $key;
	public $key_redirect;

	public $default_content;
	public $admin_content;
	public $partnership_content;
	public $provider_content;
	public $school_content;

	public $created;
	public $modified;




	/**
	 * Gets an array of ValueObjects for each help item
	 * @param PDO $link
	 * @param $URI
	 * @param $username
	 * @return unknown_type
	 */
/*
	public static function getAllHelpItems(PDO $link)
	{
		$rows = array();
		$filter = new SavedFilters();

		$sql = "
			SELECT
				h.*,hc.title as help_category_name
			FROM
				central.help as h
			LEFT JOIN
				central.help_category AS hc ON (h.help_category_id = hc.help_category_id)
			ORDER BY
				hc.display_order, h.display_order
		;";

		$st = $link->query($sql);

		if($st)
		{
			//var_dump($st->fetch());
			while($row = $st->fetch())
			{
				$rows[$row['help_category_name']][] = $row;
			}

		}
		else
		{
			throw new Exception(implode($link->errorInfo()));
		}


		return $rows;
	}
*/
	/**
	 * Gets a specific saved filter based on its ID
	 * @param PDO $link
	 * @param $filterID
	 * @return unknown_type
	 */
/*
	public static function getHelpItem(PDO $link, $id)
	{

		$help = new Help();

		$sql = "
			SELECT
				h.*,hc.title as help_category_name
			FROM
				central.help as h
			LEFT JOIN
				central.help_category AS hc ON (h.help_category_id = hc.help_category_id)
			WHERE
				h.help_id = '" . intval($id) . "'
		;";

		$st = $link->query($sql);

		if($st)
		{
			$row = $st->fetch();
			if(empty($row))
			{
				throw new Exception('Help item not found');
			}
			else
			{
				$help->populate($row);
			}
		}
		else
		{
			throw new Exception(implode($link->errorInfo()));
		}

		return $help;
	}
*/
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == ''){
			return null;
		}

		$object = null;

		if(is_numeric($id))
		{
			$sql = "SELECT * FROM central.help WHERE id='".addslashes((string)$id)."';";
		}
		else
		{
			$id = Help::cleanLookupKey($id);
			$sql = "SELECT * FROM central.help  WHERE `key`='".addslashes((string)$id)."';";
		}

		if($st = $link->query($sql))
		{
			if( $row = $st->fetch() )
			{
				$object = new Help();
				$object->populate($row);
			}
			else
			{
				return null;
			}

			//mysqli_free_result($result);
		}
		else
		{
			throw new SQLException(mysqli_error($link), mysqli_errno($link), $sql);
		}

		return $object;
	}


	public function save(PDO $link)
	{
		$this->modified = "";
		$this->created = ($this->id == "") ? "" : $this->created;

		if($this->key == ""){
			throw new Exception("You must enter a valid lookup key");
		}

		if($this->title == ""){
			throw new Exception("You must enter a valid title");
		}

		$this->default_content = trim($this->default_content);
		$this->admin_content = trim($this->admin_content);
		$this->partnership_content = trim($this->partnership_content);
		$this->provider_content = trim($this->provider_content);
		$this->school_content = trim($this->school_content);

		// Clear cached key list
		if(defined("XC_TYPE_VAR"))
		{
			$key = $_SERVER['SERVER_NAME'].' help keys';
			xcache_unset($key);

			// Clear cached content
			$key = $_SERVER['SERVER_NAME'].' help '.$this->key;
			xcache_unset_by_prefix($key);
		}

		return DAO::saveObjectToTable($link, 'help', $this);
	}


	public function delete(PDO $link, $id = null)
	{
		if(is_null($id))
		{
			$id = $this->id;
		}

		if(is_array($id))
		{
			$id = DAO::mysqli_implode($id);
		}

		DAO::execute($link, "DELETE FROM central.help WHERE id IN(".$id.")");

		// Clear cached key list
		if(defined("XC_TYPE_VAR"))
		{
			// Clear cached key list
			$key = $_SERVER['SERVER_NAME'].' help keys';
			xcache_unset($key);

			// Clear cached content
			$key = $_SERVER['SERVER_NAME'].' help '.$this->key;
			xcache_unset_by_prefix($key);
		}

		return true;
	}


	public function isSafeToDelete(PDO $link, $record_id = null)
	{
		return true;
	}


	public static function renderIcon(PDO $link, $help_key)
	{
		// Clean key
		$help_key = Help::cleanLookupKey($help_key);

		// Get list of known keys
		$keys = array();
		if(Cache::isAvailable())
		{
			$key = $_SERVER['SERVER_NAME'].' help keys';
			$keys = Cache::get($key);
			if(is_null($keys))
			{
				$keys = DAO::getSingleColumn($link, "SELECT `key` FROM central.help");
				Cache::set($key, $keys, 600);
			}
		}
		else
		{
			//$keys = DAO::getSingleColumn("SELECT `key` FROM central.help");
		}

		// Render no icon if the requested key does not exist
		if(!in_array($help_key, $keys))
		{
			// No help content for this CLM page
			// If the user has admin privileges, render a transparent icon
			if($_SESSION['role'] == 'admin')
			{
				echo <<<HEREDOC
<img class="ActionIcon" style="opacity:0.5" src="/images/btn-help.gif"
title="Create help for this page" width="25" height="25" onclick="displayHelp('$help_key');"/>
HEREDOC;
			}
		}
		else
		{
			// Render full icon
			echo <<<HEREDOC
<img class="ActionIcon" src="/images/btn-help.gif"
title="View help" width="25" height="25" onclick="displayHelp('$help_key');"/>
HEREDOC;
		}
	}

	public static function renderLink(PDO $link, $help_key, $link_text)
	{
		// Clean key
		$help_key = Help::cleanLookupKey($help_key);

		// Get list of known keys
		$keys = array();
		if(Cache::isAvailable())
		{
			$key = $_SERVER['SERVER_NAME'].' help keys';
			$keys = Cache::get($key);
			if(is_null($keys))
			{
				$keys = DAO::getSingleColumn($link, "SELECT `key` FROM central.help");
				Cache::set($key, $keys, 600);
			}
		}
		else
		{
				$keys = DAO::getSingleColumn($link, "SELECT `key` FROM central.help");
		}

		if(!in_array($help_key, $keys))
		{


			// No help content for this CLM page
			echo <<<HEREDOC


<span style="color:red" title="Missing help page">$link_text'</span>
HEREDOC;
		}
		else
		{
			echo <<<HEREDOC
<a href="" onclick="displayHelp('$help_key'); return false;">$link_text</a>
HEREDOC;
		}
	}


	public static function cleanLookupKey($help_key)
	{
		if($help_key == ""){
			return $help_key;
		}

		$help_key = basename($help_key);
		$help_key = str_replace(array("act_", "tpl_", ".php"), "", $help_key); // Remove PHP file prefixes and suffixes
		$help_key = preg_replace('/[^A-Za-z]/', " ", $help_key); // Replace all punctuation with a space
		$help_key = preg_replace('/[A-Z]/', ' $0', $help_key); // Insert a space before all capitals (required to maintain existing case in next step)
		$help_key = Text::strtoproper($help_key); // Enforce proper case
		$help_key = str_replace(' ', '', $help_key); // Remove all spaces

		return $help_key;
	}

	/**
	 * For rendering content inside the Help window. Identical to HTML::wikify() except for
	 * the way Help links are rendered.
	 */
	public static function wikify($text)
	{
		if(is_null($text)){
			return null;
		}

		// Remember unformatted sections
		if(PHP_VERSION >= '5.3')
		{
			preg_match_all('#\{\{\{.*?\}\}\}#ms', $text, $matches);
			$unformattedSections = count($matches) ? $matches[0] : array();
			$text = preg_replace('#\{\{\{.*?\}\}\}#s', '{{{x}}}', $text);
		}

		// Remove HTML tags
		$text = htmlspecialchars((string)$text);

		// Remove extraneous blank lines
		$text = preg_replace('/^\s*\r?\n/', "", $text);

		// Wiki character formatting
		$text = preg_replace("/__(.*?)__/", '<u>$1</u>', $text); // underline
		$text = preg_replace("/~~(.*?)~~/", '<span style="text-decoration:line-through">$1</span>', $text); // underline
		$text = preg_replace("/'''''(.*?)'''''/", '<em><b>$1</b></em>', $text); // italic
		$text = preg_replace("/'''(.*?)'''/", '<b>$1</b>', $text); // bold
		$text = preg_replace("/''(.*?)''/", '<em>$1</em>', $text); // bold + italic

		// Hyperlinks
		$text = preg_replace('#\[([a-zA-Z0-9\.\-_]+@[a-zA-Z0-9\.\-_]+)\s+(.+?)\]#', '<a href="mailto:$1" target="_blank">$2</a>', $text);
		$text = preg_replace('#\[([a-zA-Z0-9\.\-_]+@[a-zA-Z0-9\.\-_]+)\]#', '<a href="mailto:$1" target="_blank">$1</a>', $text);
		$text = preg_replace('#\[((http|/)[^ \]]+?)\s+(.+?)\]#', '<a href="$1" target="_blank">$3</a>', $text);
		$text = preg_replace('#\[((http|/)[^ \]]+?)\]#', '<a href="$1" target="_blank">$1</a>', $text);
		$text = preg_replace('/\[([A-Z][a-z]+(?:[A-Z][a-z]+)+) (.+?)\]/', '<a href="do.php?_action=display_help&key=$1">$2</a>', $text);
		$text = preg_replace('/(?<=\s)([A-Z][a-z]+([A-Z][a-z]+)+)/', '<a href="do.php?_action=display_help&key=$1">$1</a>', $text);
		$text = preg_replace('/!([A-Z][a-z]+([A-Z][a-z]+)+)/', '$1', $text); // Remove exclamation mark

		// Images
		$text = preg_replace('/\[\[Image[:(]([^)\]]+)\)?\]\]/', '<img src="$1" border="0" />', $text);

		// Paragraph formatting
		$text = '<p>'.preg_replace('/[\n\r]+/', "</p>\r\n<p>", $text)."</p>\r\n";

		// Headings (we start at H2 because the title is H1)
		$text = preg_replace('#<p>= (.*) =</p>#', '<h2>$1</h2>', $text);
		$text = preg_replace('#<p>== (.*) ==</p>#', '<h3>$1</h3>', $text);
		$text = preg_replace('#<p>=== (.*) ===</p>#', '<h4>$1</h4>', $text);

		// Quotes
		$text = preg_replace('#(<p>\s*&gt;.*</p>[\r\n]*)+#', "<div class=\"Quote\">$0</div>\r\n", $text);
		$text = preg_replace("#<p>\s*&gt;+\s*#", '<p>', $text);

		// Tables
		if(strpos($text, '<p>||') !== FALSE)
		{
			$text = preg_replace('#(<p>\|\|.*\|\|</p>[\r\n]*)+#', "<table>\r\n$0</table>\r\n", $text);
			$text = preg_replace('#<p>\|\|#', '<tr><td valign="top">', $text);
			$text = preg_replace('#\|\|</p>#', '</td></tr>', $text);
			$text = preg_replace('#\|\|#', '</td><td valign="top">', $text);
			if(PHP_VERSION > '5.3')
			{
				// Convert first table row into header cells
				$text = preg_replace_callback('#<table>\r\n<tr>(.*?)</tr>#', function($matches){return str_replace('td valign="top">', 'th>', "<table>\r\n<tr>".$matches[1]).'</tr>';}, $text);
			}
		}

		// Ordered lists
		$text = preg_replace('/<p>\s*(\(?\d+[.)]|\(?[a-z][.)])\s*/', '<p>#', $text); // Convert alternative syntaxes to '#'
/*		if(preg_match('�<p>\s*#{1,}�', $text))
		{
			$text = preg_replace('�(<p>\s*#{1,}.*</p>[\r\n]*)+�', "<ol style=\"list-style-type:decimal\">\r\n$0</ol>\r\n", $text);
			$text = preg_replace('�(<p>\s*#{2,}.*</p>[\r\n]*)+�', "<ol style=\"list-style-type:lower-alpha\">\r\n$0</ol>\r\n", $text);
			$text = preg_replace('�(<p>\s*#{3,}.*</p>[\r\n]*)+�', "<ol style=\"list-style-type:lower-roman\">\r\n$0</ol>\r\n", $text);
			$text = preg_replace('�<p>\s*\#+\s*(.*)</p>�', "<li style=\"margin-top:4px\">$1", $text);
		}*/

		// Unordered lists
/*		if(preg_match('�<p>\s*[*-]{1,}�', $text))
		{
			$text = preg_replace('�(<p>\s*[*-]{1,}.*</p>[\r\n]*)+�', "<ul style=\"list-style-type:square;\">\r\n$0</ul>\r\n", $text);
			$text = preg_replace('�(<p>\s*[*-]{2,}.*</p>[\r\n]*)+�', "<ul style=\"list-style-type:disc;\">\r\n$0</ul>\r\n", $text);
			$text = preg_replace('�(<p>\s*[*-]{3,}.*</p>[\r\n]*)+�', "<ul style=\"list-style-type:circle;\">\r\n$0</ul>\r\n", $text);
			$text = preg_replace('�<p>\s*[*-]+\s*(.*)</p>�', "<li style=\"margin-top:4px\">$1", $text);
		}*/

		// Indented text
		$text = preg_replace('#(<p>\s+.*</p>[\r\n]*)+#', "<div class=\"Indent\">$0</div>\r\n", $text);
		$text = preg_replace("#<p>\s+#", '<p>', $text);

		// Symbols
		$text = preg_replace('/(?<!-)-&gt;/', '<img src="/images/menu-arrow.gif" width="11" height="11" />', $text);

		// Restore unformatted sections (WARNING: uses PHP 5.3 closures!)
		if(PHP_VERSION > '5.3')
		{
			$count = 0;
			$text = preg_replace_callback('#\{\{\{x\}\}\}#s', function($matches) use (&$count, $unformattedSections) {return $unformattedSections[$count++];}, $text);
			$text = str_replace(array("{{{", "}}}"), array("", ""), $text);
		}

		return $text;
	}


}

?>
