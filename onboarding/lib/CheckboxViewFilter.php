<?php
class CheckboxViewFilter implements IViewFilter
{
	/**
	 * Constructor
	 *
	 * @param string $key The name attribute of the filter's HTML <select> element.
	 * Used to identify the filter object.
	 * @param mixed $options An array of option data or a SQL query. Option data should
	 * be arranged in rows of four columns each (value, label, option group, where clause).
	 * @param string $pre_selected Optional - a pre-selected value
	 */
	public function __construct($key, $options, $pre_selected = array())
	{
		if (strpos($key, ' ') !== false) {
			throw new Exception("Argument \$key cannot contain spaces as it will be used as an HTML field identifier.");
		}

		$this->key = $key;
		sort($pre_selected);
		$this->selected = $this->default_selected = $pre_selected;

		if(is_array($options))
		{
			// Options will be loaded from an array
			if(count($options) == 0 || !is_array($options[0]) || count($options[0]) < 4)
			{
				throw new Exception("Array \$options must be in the format"
					." \$options[n] = array(optionValue, optionLabel, WHERE_clause)");
			}
			$this->options = $options;

			$this->sql_options_query = NULL; // Ensure this is null (later behaviour depends on it)
		}
		else
		{
			// Options will be loaded from a database
			$this->options = NULL; // Will be updated everytime refresh() is called
			$this->sql_options_query = (string) $options;
		}
	}


	/**
	 * @return mixed the selected value or NULL if no option is selected
	 */
	public function getValue()
	{
		return $this->selected;
	}

	public function getDisplayValue()
	{
		return $this->getOptionLabel($this->selected);
	}

	public function setValue($data)
	{
		$state_changed = false;


		if(is_array($data))
		{
			// If the data array contains data for this filter...
		//	if(array_key_exists($this->key, $data))
			{
				$new_value = $data;

				// If the data is NULL or an empty string...
				if( $new_value == '' )
				{
					// If the current filter value IS NOT null...
					if(count($this->selected) > 0)
					{
						$this->selected = array();
						$state_changed = true;
					}
				}
				else
				{
					if(!is_array($new_value))
					{
						$new_value = array($new_value);
					}

					// If the new data is different to the current value,
					// change the value of the filter
					$diff1 = array_diff($this->selected, $new_value);
					$diff2 = array_diff($new_value, $this->selected);
					if(count($diff1) > 0 || count($diff2) > 0)
					{
						$this->selected = $new_value;
						$state_changed = true;
					}
				}
			}
		}
		else
		{
			if(is_null($data) || $data === '')
			{
				// If the current filter value IS NOT null...
				if(count($this->selected) > 0)
				{
					$this->selected = array();
					$state_changed = true;
				}
			}
			else
			{
				$new_value = array($data);

				// If the new data is different to the current value,
				// change the value of the filter
				$diff1 = array_diff($this->selected, $new_value);
				$diff2 = array_diff($new_value, $this->selected);
				if(count($diff1) > 0 || count($diff2) > 0)
				{
					$this->selected = $new_value;
					$state_changed = true;
				}
			}
		}

		// return true if state has changed, false if not
		return $state_changed;
	}

	public function getOptions()
	{
		return $this->options;
	}

	public function reset()
	{
		$filter_has_changed = $this->selected != $this->default_selected;
		$this->selected = $this->default_selected;
		return $filter_has_changed;
	}


	public function setName($name)
	{
		$this->key = $name;
	}

	public function getName()
	{
		return $this->key;
	}


	public function refresh(PDO $link = null)
	{
		if(!is_null($link))
		{
			// Skip this code if this checkbox filter is initialised
			// from a hard-coded array
			if(is_null($this->sql_options_query)) return;

			// Check for references to the values of other filters
			// (marked-up as {{filtername}})
			$sql = $this->parseFilterMarkup($this->sql_options_query);

			$this->options = array();
			$st = $link->query($sql);
			if($st)
			{
				while($row = $st->fetch())
				{
					$this->options[] = $row;
				}
			}
			else
			{
				throw new DatabaseException($link, $sql);
			}
		}
	}


	public function toHTML($allow_null = false)
	{
		$id = $this->key;
				$jsDefaultValue = '{';
				if(is_array($this->default_selected))
				{
					foreach($this->default_selected as $value)
					{
						 $jsDefaultValue .= ("'".addslashes($value)."',");
					}
				}
				else
				{
					$jsDefaultValue .= "'".addslashes($this->default_selected)."'";
				}
				$jsDefaultValue = '}';
		$jsDefaultValue = Text::json_encode_latin1($this->default_selected);

		$html = '<div id="grid_'.$id.'" style="height: 250px;width: 300px;overflow-y: scroll; overflow-x: scroll;" ><table cellspacing="1" cellpadding="0">';
		$html .= '<tr>';
		$html_id = get_class($this->getParentView()).'_'.$this->key;
		$columns = 1;
		$col = 1;
		$index = 0;
		foreach($this->options as $option)
		{
			if($col > $columns)
			{
				$html .= '</tr><tr>';
				$col = 1;
			}

//			$checked = in_array($option[0], $this->selected) ? 'checked="checked"':'';
			$checked = in_array($option[0], $this->selected) ? 'checked=true':'';

			$html .= '<td title="'.htmlspecialchars($option[2]).'" style="padding-right:20px">'
				.'<input id="checkboxIndex_'.$index.'" type="checkbox" name="'.$html_id.'[]" value="'.$option[0]."\" $checked onchange=\"if(window.{$id}_onchange){window.{$id}_onchange(this, arguments.length > 0 ? arguments[0] : window.event)}\"/>"
				.htmlspecialchars($option[1]).'</td>';

			$col++;
			$index++;
		}

		$fn = $id.'_onchange';
		$html .= '</tr></table></div>';
		$html .= <<<HEREDOC
<script language="JavaScript">
//<![CDATA[
var grid = document.getElementById('grid_$id');


grid.setValues = function(values) {
	if(!(values instanceof Array))
	{
		values = new Array(values);
	}

	values = values.sort();

	// Create associated array of boxes
	var inputs = this.getElementsByTagName('INPUT');
	for(var i = 0; i < inputs.length; i++)
	{
		inputs[i].checked = inputs[i].checked || isInSortedArray(inputs[i].value, values);
	}
}

grid.getValues = function() {
	var inputs = this.getElementsByTagName('INPUT');
	var values = new Array();
	for(var i = 0; i < inputs.length; i++)
	{
		if(inputs[i].checked)
		{
			values[values.length] = inputs[i].value;
		}
	}
	return values;
}

grid.resetGridToDefault = function()
{
//	var grid = document.getElementById('grid_$id');

	var boxes = this.getElementsByTagName('INPUT');
	boxes[0].checked = true;
	for(var i = 1; i < boxes.length; i++)
	{
		boxes[i].checked = false;
	}
}

function $fn(ele)
		{
			var n = ele.name;

			if(ele.value == "SHOW_ALL")
			{
				$("input[name='"+n+"']").each( function () {
					if(this.value != "SHOW_ALL")
						this.checked = false;
				});
			}
			else
			{
				$("input[name='"+n+"']").each( function () {
					if(this.value == "SHOW_ALL")
						this.checked = false;
				});
			}

			var any_selected = false;
			$("input[name='"+n+"']").each( function () {
				if(this.checked)
					any_selected = true;
			});

			if(!any_selected)
			{
				$("input[name='"+n+"']").each( function () {
					if(this.value == "SHOW_ALL")
						this.checked = true;
				});
			}
		}
//]]>
</script>
HEREDOC;

		return $html;
	}


	public function getSQLStatement()
	{


		if(count($this->selected) > 0) // If user has selected a filter value...
		{
			$statement = "";

			foreach($this->selected as $choice) // For each of the user's choices
			{
				foreach($this->options as $opt) // ...search through the option array...
				{
					// ...for the row corresponding to the user's selected value
					if( ((string)$opt[0]) == ((string)$choice) )
					{
						if(!is_null($opt[3]))
						{
							// SQL exists for this option.
							// Check for {{filtername}} markup and replace with the
							// value of the respective filter
							$sql = $this->parseFilterMarkup($opt[3]);

//							$statement->set_clause($sql); // append sql to growing statement
							$statement .= $sql; // append sql to growing statement
						}
					}
				}
			}
			$statement = preg_replace("/WHERE/", "_FIRST_", $statement, 1);
			$statement = str_replace("WHERE", " OR ", $statement);
			$statement = str_replace("_FIRST_", " WHERE ", $statement);

			$statement = new SQLStatement($statement);

			if(strlen($statement->__toString()) > 0)
				{
					return $statement;
				}
				else
				{
					return null;
				}
			}
			else
			{
				// If the user has not selected a filter value
				return null;
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


	public function setDescriptionFormat($strFormat)
	{
		$this->format = $strFormat;
	}


	public function getDescription()
	{
		if(!is_null($this->selected) && ($this->selected !== '') ) // includes null
		{
			if(is_null($this->format))
			{
				return $this->getOptionLabel($this->selected);
			}
			else
			{
				return sprintf($this->format, $this->getOptionLabel($this->selected));
			}
		}
		else
		{
			return '';
		}
	}


	/**
	 * Helper method for getDescription(). Returns the label for a given
	 * filter value
	 *
	 * @param string $optionValue
	 * @return string
	 */
	private function getOptionLabel($optionValue)
	{
		foreach($this->options as $opt)
		{
			if($opt[0] == $optionValue)
			{
				return $opt[1];
			}
		}

		return "";
	}


	private function parseFilterMarkup($sql)
	{
		// Only perform the relatively costly regular expression match
		// if '{{' is present in the string
		if(strpos($sql, '{{') !== false)
		{
			if(preg_match_all('/(\{\{[a-zA-Z0-9_ ]+\}\})/', $sql, $matches))
			{
				// Replace markup with the filter value
				foreach($matches[1] as $m)
				{
					$filter_name = trim($m, '{}');

					if($this->view->hasFilter($filter_name))
					{
						$filter_value = $this->view->getFilterValue($filter_name);
					}
					else if(preg_match('/^(.+)[_ ](start|end)[_ ]date$/', $filter_name, $matches2))
					{
						// Possibly a DateRangeViewFilter
						if($this->view->hasFilter($matches2[1]))
						{
							$f = $this->view->getFilter($matches2[1]);
							$filter_value = ($matches2[2] == "start") ? $f->getStartDate() : $f->getEndDate();
						}
						else
						{
							$filter_value = NULL;
						}
					}
					else
					{
						$filter_value = NULL;
					}

					if($filter_value == '') // covers null too
					{
						// NULL
						$sql = str_replace($m, 'NULL', $sql);
					}
					elseif(is_numeric($filter_value))
					{
						// Numeric
						$sql = str_replace($m, $filter_value, $sql);
					}
					elseif(preg_match('#^\d{1,2}/\d{1,2}/\d{1,4}$#', $filter_value))
					{
						// Date
						$sql = str_replace($m, "'" . Date::toMySQL($filter_value) . "'", $sql);
					}
					else
					{
						// String
						$sql = str_replace($m, "'" . addslashes($filter_value) . "'", $sql);
					}
				}
			}
		}

		return $sql;
	}


	protected $sql_options_query = null;
	protected $sql_where = '';
	protected $key = '';

	protected $default_selected = array();
	protected $selected = array();

	protected $allow_null;

	protected $options = null;

	protected $format = null;

	protected $view = null;
}
?>