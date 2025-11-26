<?php
class DateViewFilter implements IViewFilter
{
	public function __construct($name, $sql_format, $default_value)
	{
		$this->key = $name;
		$this->sql_format = $sql_format;
		$this->value = $this->default_value = $default_value;
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
			$html_id = get_class($this->getParentView()).'_'.$this->key;

			if(array_key_exists($html_id, $v))
			{
				$state_changed = ($v[$html_id] != $this->value);

				if($v[$html_id] == '')
				{
					$this->value = NULL;
				}
				else
				{
					$this->value = $v[$html_id];
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
		$filter_has_changed = $this->value != $this->default_value;
		$this->value = $this->default_value;
		return $filter_has_changed;
	}


	public function toHTML()
	{
		$html_id = get_class($this->getParentView()).'_'.$this->key;

		//$default = str_replace('"', '\"', $this->default_value);
		$default = htmlspecialchars(Date::toShort($this->default_value) ?? '', ENT_QUOTES, 'UTF-8');
		$value = htmlspecialchars(Date::toShort($this->value) ?? '', ENT_QUOTES, 'UTF-8');

		$nameHTML = (!empty($html_id) ? ' name="' . $html_id . '"' : '');
		$html = <<<HTML
<input type="text" class="DateBox" id="$html_id" $nameHTML value="$value" size="10"
maxlength="10" onchange="if(window.{$html_id}_onchange){window.{$html_id}_onchange(this)}"
onblur="if(!this.validate()){return false;};if(window.{$html_id}_onblur){window.{$html_id}_onblur(this)}"
onfocus="if(window.{$html_id}_onfocus){window.{$html_id}_onfocus(this)}" />
<a href="#" id="{$html_id}_anchor" onclick="window.calPop.select(this.previousSibling.previousSibling, this.id, 'dd/MM/yyyy'); return false;">
<img src="/images/calendar-icon.gif" border="0" style="vertical-align:text-bottom" width="20" height="15" alt="Show calendar" title="Show calendar" /></a>
<script type="text/javascript">
//<![CDATA[
	var ele = document.getElementById("$html_id");
	ele.resetToDefault = function(){this.value = "$default"};
	ele.validate = function(){
		if(this.value && window.stringToDate && !window.stringToDate(this.value)){
			alert("Invalid calendar-date or invalid date-format. Please use the format dd/mm/yyyy.");
			this.value = "$default";
			this.focus();
			return false;
		}
		return true;
	}
//]]>
</script>
HTML;

		return $html;
	}


	public function getSQLStatement()
	{
		if($this->value != '')
		{
			if(strpos($this->sql_format, 'STR_TO_DATE'))
				return new SQLStatement(str_replace('%s', Date::toMySQL($this->value), $this->sql_format));
			else
				return new SQLStatement(sprintf($this->sql_format, Date::toMySQL($this->value)));
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
				return date('D jS M Y', Date::parseDate($this->value));
			}
			else
			{
				return sprintf($this->description_format, date('D jS M Y', Date::parseDate($this->value)));
			}
		}
		else
		{
			return '';
		}
	}


	public function setParentView(View $v)
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
}
?>