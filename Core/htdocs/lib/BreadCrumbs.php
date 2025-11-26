<?php
class BreadCrumbs extends Entity
{
	public function render(PDO $link)
	{
		if(DB_NAME=='am_lsn')
		{
			echo '<div style = "background-image: url(../images/menu_item_active_lsn.png)" id="breadcrumbs">';
		}
		else
		{
			echo '<div id="breadcrumbs">';
		}
		
		echo "You are here:"; 
		echo "<ol>";
		
		$length = count($this->urls);
		for($i = 0; $i < $length; $i++)
		{
			if($i + 1 == $length)
			{
				// Final crumb
				echo '<li class="breadcrumb">' . htmlspecialchars((string)$this->text[$i]) . "</li>";
			}
			else
			{
				// Trail crumb
				echo '<li class="breadcrumb"><a href="' . $this->urls[$i] . '">' . htmlspecialchars((string)$this->text[$i]) . "</a></li>";
			}
		}
		
		echo '</ol>';
		echo '</div>';
	}
	
	/**
	 * @param PDO $link
	 * @param string $url
	 * @param string $tex
	 */
	public function add(PDO $link, $url, $tex)
	{
		// Clean trail
		$this->clean();

		// Remove XHTML encoding from the URL
		$url = str_replace("&apos;", "&", $url);
		
		$pos = array_search($url, $this->urls);
		if($pos !== FALSE)
		{
			// Replace
			$this->index = $pos;
			$this->urls[$this->index] = $url;
			$this->text[$this->index] = $tex;
			$this->clean();		
		}
		else
		{
			// Append
			$this->index++;
			$this->urls[$this->index] = $url;
			$this->text[$this->index] = $tex;			
		}
	}

	/**
	 * @param int $decrement The number of crumbs in the trail to retrace (default 1)
	 * @return string Previous URL in the bread crumb trail
	 */
	public function getPrevious($decrement = 1)
	{
		$real_index = count($this->urls) - 1;
		if(($real_index - $decrement) >= 0)
		{
			return $this->urls[$real_index - $decrement];
		}
		else
		{
			return "do.php?_action=home_page"; // default
		}
	}


	/**
	 * @return string Previous URL in the bread crumb trail
	 */
	public function getCurrent()
	{
		$real_index = count($this->urls) - 1;
		if($real_index >= 0)
		{
			return $this->urls[$real_index];
		}
		else
		{
			return "do.php?_action=home_page"; // default
		}
	}


	/**
	 * Remove the current location from the stack
	 */
	public function pop()
	{
		$real_index = count($this->urls) - 1;
		if ($real_index >= 0) {
			unset($this->urls[$real_index]);
		}
	}


	/**
	 * Sanity checks. The Breadcrumb index is often manipulated
	 * directly which can result in errors if the new index does
	 * not correspond to an actual bread-crumb. This commonly happens
	 * when the trail index is set to 0, but there is no initial crumb
	 * in the trail (resulting in an 'Undefined offset: 0' error)
	 */
	private function clean()
	{
		// Remove XHTML encoding from URLs
		// (this is only essential during first deployment, when existing
		// breadcrumb trails will still contain XHTML encoded URLs)
		foreach($this->urls as &$url){
			$url = str_replace("&amp;", "&", $url);
		}

		$real_index = count($this->urls) - 1;
		if($this->index > $real_index)
		{
			$this->index = $real_index;
		}
		elseif($this->index < 0)
		{
			$this->index = -1;
			$this->urls = array();
			$this->text = array();
		}
		elseif($this->index < $real_index)
		{
			do{
				array_pop($this->urls);
				array_pop($this->text);
				$real_index--;
			} while($this->index < $real_index);
		}
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		switch($name)
		{
			case "index":
				$this->index = is_numeric($value) ? $value : 0;
				$this->clean();
				break;

			default:
				throw new Exception("Invalid property: ".$name);
				break;
		}
	}

	/**
	 * @param string $name
	 * @return mixed
	 * @throws Exception
	 */
	public function __get($name)
	{
		switch($name)
		{
			case "index":
				return $this->index;
				break;

			default:
				throw new Exception("Invalid property: ".$name);
				break;
		}
	}

	/**
	 * @param string $name
	 * @return bool
	 * @throws Exception
	 */
	public function __isset($name)
	{
		switch($name)
		{
			case "index":
				return isset($this->index);
				break;

			default:
				throw new Exception("Invalid property: ".$name);
				break;
		}
	}

	/**
	 * @param string $name
	 * @throws Exception
	 */
	public function __unset($name)
	{
		switch($name)
		{
			case "index":
				$this->index = 0;
				break;

			default:
				throw new Exception("Invalid property: ".$name);
				break;
		}
	}


	private $index = -1;
	public $urls = array();
	public $text = array();
}
?>