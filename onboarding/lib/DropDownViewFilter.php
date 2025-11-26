<?php
class DropDownViewFilter implements IViewFilter
{
	/**
	 * Constructor
	 *
	 * @param string $key The name attribute of the filter's HTML <select> element.
	 * Used to identify the filter object.
	 * @param mixed $options An array of option data or a SQL query. Option data should
	 * be arranged in rows of four columns each (value, label, option group, where clause).
	 * @param string $pre_selected Optional - a pre-selected value
	 * @param boolean $allow_null Optional - whether the dropdown box should contain a blank value
	 */
	public function __construct($key, $options, $pre_selected = null, $allow_null = true)
	{
		if(strpos($key, ' ') !== false)
		{
			throw new Exception("Argument \$key cannot contain spaces as it will be used as an HTML field identifier.");
		}

		$this->key = $key;
		$this->allow_null = $allow_null;
		$this->selected = $this->default_selected = $pre_selected;

		if(is_array($options))
		{
			// Options will be loaded from an array
			if(count($options) == 0 || !is_array($options[0]) || count($options[0]) < 4)
			{
				throw new Exception("Array \$options must be in the format"
					." \$options[n] = array(optionValue, optionLabel, optionGroup, WHERE_clause)");
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


	public function setValue($data)
	{
		$state_changed = false;

		if(is_array($data))
		{
			$html_id = get_class($this->getParentView()).'_'.$this->key;

			// If the data array contains data for this filter...
			if(array_key_exists($html_id, $data))
			{
				$new_value = $data[$html_id];

				// If the data is NULL or an empty string...
				if( (is_null($new_value) || $new_value === '') )
				{
					// If the current filter value IS NOT null...
					if(!is_null($this->selected))
					{
						$this->selected = NULL;
						$state_changed = true;
					}
				}
				else
				{
					if($this->selected != $new_value)
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
				if(!is_null($this->selected))
				{
					$this->selected = NULL;
					$state_changed = true;
				}
			}
			else
			{
				if($this->selected != $data)
				{
					$this->selected = $data;
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
			// Skip this code if this drop down box is initialised
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
		// Turn on output buffering
		ob_start();

		$html_id = get_class($this->getParentView()).'_'.$this->key;

		// Open HTML element
		echo '<select id="', $html_id, '" name="', $html_id, '" onchange="if(window.', $html_id, '_onchange){window.', $html_id, '_onchange(this);}" >';

		// Write blank option if filter can be set to null
		if($this->allow_null)
		{
			echo '<option value=""></option>';
		}

		$current_option_group = null;
		for($i = 0; $i < count($this->options); $i++)
		{
			$value 			= $this->options[$i][0];
			$label 			= $this->options[$i][1];
			$option_group 	= $this->options[$i][2];

			// If the row contains OptionGroup information (not null, not blank)
			// and the option group has changed (or is being set for the first time)
			// then run the code below.
			if( !is_null($option_group)
				&& $option_group !== ''
				&& $option_group != $current_option_group )
			{
				// Close current option group if there is one
				if(!is_null($current_option_group))
				{
					echo '</optgroup>';
				}

				// Begin new option group
				echo '<optgroup label="', htmlspecialchars($option_group), '">';

				// Record current option group for next iteration
				$current_option_group = $option_group;
			}

			if((string)$this->selected === (string)$value)
			{
				echo '<option value="', htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'), '" selected="selected">', htmlspecialchars($label ?? '', ENT_QUOTES, 'UTF-8'), '</option>';
			}
			else
			{
				echo '<option value="', htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'), '">', htmlspecialchars($label ?? '', ENT_QUOTES, 'UTF-8'), '</option>';
			}
		}

		echo '</select>';


		// Auto-generated JavaScript
		$def = (string) $this->default_selected;
		echo <<<HEREDOC
<script type="text/javascript">
//<![CDATA[
document.getElementById('$html_id').resetToDefault = function(){
	for(var i = 0; i < this.options.length; i++)
	{
		if(this.options[i].value == '$def')
		{
			this.options[i].selected = true;
			break;
		}
	}
}
//]]>
</script>
HEREDOC;

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}


	public function getSQLStatement()
	{
		if(!is_null($this->selected) && $this->selected !== '') // If user has selected a filter value...
		{
			foreach($this->options as $opt) // ...search through option data...
			{
				// ...to find the row with the user's selected value
				if( ((string)$opt[0]) == ((string)$this->selected) )
				{
					if(!is_null($opt[3]))
					{
						// SQL exists for this option.
						// Check for {{filtername}} markup and replace with the
						// value of the respective filter
						$sql = $this->parseFilterMarkup($opt[3]);

						return new SQLStatement($sql); // ...and return the SQL clause(s) associated with the value
					}
					else
					{
						return null; // ...or null if there is no associated SQL
					}
				}
			}

			// The user or programmer select value does not exist in the options
			// array.  If this drop-down box is allowed to be null, this is not
			// a problem
			if($this->allow_null)
			{
				return null;
			}
			else
			{
				throw new Exception("The value selected ($this->selected) in dropdown box '$this->key' does not exist in the options array.");
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

		return 'n/a';
	}


	private function parseFilterMarkup($sql)
	{
		// Only perform the relatively costly regular expression match
		// if '{{' is present in the string
		if(strpos($sql, '{{') !== false)
		{
			if(preg_match_all('/(\{\{[a-zA-Z0-9_]+\}\})/', $sql, $matches))
			{
				// Replace markup with the filter value
				foreach($matches[1] as $m)
				{
					$filter_name = trim($m, '{}');
					$filter_value = $this->view->getFilterValue($filter_name);

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

	protected $default_selected = null;
	protected $selected = null;
	protected $allow_null;

	protected $options = null;

	protected $format = null;

	protected $view = null;
}
?>