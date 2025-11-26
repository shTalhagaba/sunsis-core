<?php
class VoltTextboxViewFilter implements VoltIViewFilter
{
	public function __construct($name, $sql_format, $default_value, $html_attributes = '')
	{
		$this->key = $name;
		$this->sql_format = $sql_format;
		$this->value = $this->default_value = $default_value;
		$this->html_attributes = $html_attributes;
	}
	
	
	/**
	 * Each filter for a view must have a unique name.  The name is used
	 * to request a particular filter's data from a view and forms part
	 * of the name of the HTML form element(s) used to represent the filter
	 * on a web page.
	 *
	 * @param string $key
	 */
	public function setName($name)
	{
		$this->key = $name;
	}
	
	/**
	 * @return string the name of the filter
	 */
	public function getName()
	{
		return $this->key;
	}

	/**
	 * Called by the View object's refresh() method.  Use this method to
	 * update any options displayed to the user (included mainly with
	 * dropdown boxes, checkboxes and radio buttons in mind).
	 * 
	 * @param mixed dataSource An array, a database link...
	 */ 
	public function refresh(PDO $dataSource = null)
	{}

	
	/**
	 * Equivalent of the old setState() method
	 *
	 * @param unknown_type $selected
	 */
	public function setValue($v)
	{
		$state_changed = false;
		
		if(is_array($v))
		{
			$id = $this->key;
			
			if(array_key_exists($id, $v))
			{
				$state_changed = ($v[$id] != $this->value);
				
				if($v[$id] == '')
				{
					$this->value = NULL;
				}
				else
				{
					$this->value = $v[$id];
				}
			}
		}
		else
		{
			$state_changed = ($v != $this->value);
			
			if($v == '')
			{
				$this->value = NULL;
			}
			else
			{
				$this->value = $v;
			}
		}
		
		return $state_changed;
	}
	
	
	public function getValue()
	{
		return $this->value;
	}

	
	/**
	 * Returns the filter to its initial state.
	 *
	 */
	public function reset()
	{
		$this->value = $this->default_value;
	}
	
	
	public function toHTML()
	{
		$id = $this->key;
		$value = htmlspecialchars((string)$this->value);
		$attr = $this->html_attributes;
		$def = $this->default_value;
		
		$html = <<<HEREDOC
<input type="text" id="$id" name="$id" value="$value" $attr />
<script>document.getElementById('$id').resetToDefault = function() { this.value='$def';}</script>
HEREDOC;
		
		return $html;
	}
	
	
	public function getSQLStatement()
	{
		if($this->value != '')
		{
			return new SQLStatement(sprintf($this->sql_format, addslashes((string)$this->value)));
		}
		else
		{
			return null;
		}
	}
	
	
	/**
	 * The format for a description
	 *
	 * @param string $strDescriptionFormat sprintf() compatibile string
	 */
	public function setDescriptionFormat($strFormat)
	{
		$this->description_format = $strFormat;
	}
	
	
	/**
	 * Used for creating filter breadcrumbs
	 * 
	 * @return a sort description of the filter's status
	 */
	public function getDescription()
	{
		if($this->value != '') // includes null
		{
			if(is_null($this->description_format))
			{
				return $this->value;
			}
			else
			{
				return sprintf($this->description_format, $this->value);
			}
		}
		else
		{
			return '';
		}
	}
	
	
	public function setParentView(VoltView $v)
	{
		$this->view = $v;
	}
	
	public function getParentView()
	{
		return $this->view;
	}
	
	
	private $key = null;
	private $value = null;
	private $default_value = null;
	private $sql_format = null;
	private $description_format = null;
	private $view = null;
	private $html_attributes = null;
}
?>