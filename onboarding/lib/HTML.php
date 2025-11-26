<?php
class HTML
{
	/**
	 * Convenience function for creating buttons
	 *
	 * @param string $title
	 * @param string $javascript
	 */
	public static function button($title, $javascript, $hovertitle="")
	{
		$javascript = str_replace('"', '&quot;', $javascript);

		return <<<HEREDOC
<span class="button" onclick="$javascript;" title='$hovertitle'>$title</span>
HEREDOC;

	}

	/**
	 * Constructs an HTML &lt;select&gt; element.  Requires an array of arrays for the
	 * option values. Element 1 is the true value, element 2 is the label to
	 * display to the user and element 3 (optional) is the label of the group
	 * to which the option belongs.
	 * @static
	 * @param string $fieldName
	 * @param array $options Array of arrays e.g. array(array('value'), ..), array(array('value', 'label'), ..), or array(array('value', 'label', 'optgroup'), ..)
	 * @param string $pre_selected
	 * @param boolean $include_empty_option (optional)
	 * @param boolean $compulsory (optional)
	 * @param boolean $enabled (optional)
	 * @param int $size
	 * @return string
	 */
	public static function select($fieldName, array $options, $pre_selected = null, $include_empty_option = false, $compulsory = false, $enabled=true, $size = 1, $additionalHTML = '')
	{
		// Turn on output buffering
		ob_start();

		if($size > 1 && !is_array($pre_selected)){
			$pre_selected = explode(",", $pre_selected);
		}
		$class = $compulsory ? "compulsory validate[required]":"optional";
		$enabled = $enabled ? '':'disabled="disabled"';

		echo '<select  name="', htmlspecialchars($fieldName ?? '', ENT_QUOTES, 'UTF-8'), ($size > 1 ? '[]':''), '" ',
		' id="', $fieldName, '" ',
		" class=\"$class\" ",
		$additionalHTML ,
		$enabled,
		($size > 1 ? ' multiple="multiple" size="'.$size.'" ':''),
		' onchange="if(window.', $fieldName, '_onchange){window.', $fieldName, '_onchange(this, arguments.length > 0 ? arguments[0] : window.event);}" >', "\r\n";

		if(($size == 1) && $include_empty_option){
			echo '<option value=""></option>', "\r\n";
		}

		$current_option_group = null;
		for($i = 0; $i < count($options); $i++)
		{
			$column_count = count($options[$i]);

			if(is_array($options[$i]))
			{
				switch(count($options[$i]))
				{
					case 0: /* NULL */
						throw new Exception("Option rows must contain at least one column of data.");
						break;

					case 1: /* Single dimensional array */
						$value = $label = $options[$i][0];
						$option_group = null;
						break;

					case 2: /* Two dimensional array, with sub-array 2 elements long */
						$value = $options[$i][0];
						$label = $options[$i][1];
						$option_group = null;
						break;

					default: /* Two dimensional array, with sub-array >= 3 elements long */
						$value = $options[$i][0];
						$label = $options[$i][1];
						$option_group = $options[$i][2];
						break;
				}
			}
			else
			{
				$value = $label = $options[$i];
				$option_group = null;
			}

			// If the row contains OptionGroup information (not null, not blank)
			// and the option group has changed (or is being set for the first time)
			// then run the code below.
			if( !is_null($option_group)
				&& $option_group !== ''
				&& $option_group != $current_option_group )
			{
				// Close current option group if set
				if(!is_null($current_option_group))
				{
					echo "</optgroup>\r\n";
				}

				// Begin new option group
				echo '<optgroup label="', htmlspecialchars($option_group ?? '', ENT_QUOTES, 'UTF-8'), "\">\r\n";

				// Record current option group for next iteration
				$current_option_group = $option_group;
			}


			if(	(is_array($pre_selected) && in_array($value, $pre_selected))
				|| ((string)$pre_selected === (string)$value) )
			{
				echo '<option value="', htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'), '" selected="selected">', htmlspecialchars($label ?? '', ENT_QUOTES, 'UTF-8'), "</option>\r\n";
			}
			else
			{
				echo '<option value="', htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'), '">', htmlspecialchars($label ?? '', ENT_QUOTES, 'UTF-8'), "</option>\r\n";
			}
		}

		echo '</select>';

		$html = ob_get_contents();
		ob_end_clean();

		if(trim($fieldName, ' 0123456789') == "courseForNewRow")
		{
			$html = iconv('latin1','UTF-8',$html);
			return json_encode($html);
		}
		else
			return $html;
	}

	/**
	 * Constructs an HTML &lt;select&gt; element.  Requires an array of arrays for the
	 * option values. Element 1 is the true value, element 2 is the label to
	 * display to the user and element 3 (optional) is the label of the group
	 * to which the option belongs.
	 * @static
	 * @param string $fieldName
	 * @param array $options Array of arrays e.g. array(array('value'), ..), array(array('value', 'label'), ..), or array(array('value', 'label', 'optgroup'), ..)
	 * @param string $pre_selected
	 * @param boolean $include_empty_option (optional)
	 * @param boolean $compulsory (optional)
	 * @param boolean $enabled (optional)
	 * @param int $size
	 * @return string
	 */
	public static function selectChosen($fieldName, array $options, $pre_selected = null, $include_empty_option = false, $compulsory = false, $enabled=true, $size = 1, $additionalHTML = '')
	{
		// Turn on output buffering
		ob_start();

		if($size > 1 && !is_array($pre_selected)){
			$pre_selected = explode(",", $pre_selected);
		}
		$class = $compulsory ? "chosen-select compulsory validate[required] form-control":"chosen-select optional form-control";
		$enabled = $enabled ? '':'disabled="disabled"';

		echo '<select  name="', htmlspecialchars($fieldName ?? '', ENT_QUOTES, 'UTF-8'), ($size > 1 ? '[]':''), '" ',
		' id="', $fieldName, '" ',
		" class=\"$class\" ",
		$additionalHTML ,
		$enabled,
		($size > 1 ? ' multiple="multiple" size="'.$size.'" ':''),
		' onchange="if(window.', $fieldName, '_onchange){window.', $fieldName, '_onchange(this, arguments.length > 0 ? arguments[0] : window.event);}" >', "\r\n";

		if(($size == 1) && $include_empty_option){
			echo '<option value=""></option>', "\r\n";
		}

		$current_option_group = null;
		for($i = 0; $i < count($options); $i++)
		{
			$column_count = count($options[$i]);

			if(is_array($options[$i]))
			{
				switch(count($options[$i]))
				{
					case 0: /* NULL */
						throw new Exception("Option rows must contain at least one column of data.");
						break;

					case 1: /* Single dimensional array */
						$value = $label = $options[$i][0];
						$option_group = null;
						break;

					case 2: /* Two dimensional array, with sub-array 2 elements long */
						$value = $options[$i][0];
						$label = $options[$i][1];
						$option_group = null;
						break;

					default: /* Two dimensional array, with sub-array >= 3 elements long */
						$value = $options[$i][0];
						$label = $options[$i][1];
						$option_group = $options[$i][2];
						break;
				}
			}
			else
			{
				$value = $label = $options[$i];
				$option_group = null;
			}

			// If the row contains OptionGroup information (not null, not blank)
			// and the option group has changed (or is being set for the first time)
			// then run the code below.
			if( !is_null($option_group)
				&& $option_group !== ''
				&& $option_group != $current_option_group )
			{
				// Close current option group if set
				if(!is_null($current_option_group))
				{
					echo "</optgroup>\r\n";
				}

				// Begin new option group
				echo '<optgroup label="', htmlspecialchars($option_group ?? '', ENT_QUOTES, 'UTF-8'), "\">\r\n";

				// Record current option group for next iteration
				$current_option_group = $option_group;
			}


			if(	(is_array($pre_selected) && in_array($value, $pre_selected))
				|| (!is_array($pre_selected) && (string)$pre_selected === (string)$value) )
			{
				echo '<option value="', htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'), '" selected="selected">', htmlspecialchars($label ?? '', ENT_QUOTES, 'UTF-8'), "</option>\r\n";
			}
			else
			{
				echo '<option value="', htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'), '">', htmlspecialchars($label ?? '', ENT_QUOTES, 'UTF-8'), "</option>\r\n";
			}
		}

		echo '</select>';

		$html = ob_get_contents();
		ob_end_clean();

		if(trim($fieldName, ' 0123456789') == "courseForNewRow")
		{
			$html = iconv('latin1','UTF-8',$html);
			return json_encode($html);
		}
		else
			return $html;
	}

	/**
	 * @static
	 * @param $fieldName
	 * @param string $value
	 * @param bool $checked
	 * @param bool $enabled
	 * @param bool $compulsory
	 * @return string
	 */
	public static function radio($fieldName, $value, $checked = false, $enabled = true, $compulsory = false)
	{
		$checked = $checked ? ' checked="checked" ' : '';
		$enabled = $enabled ? '' : 'disabled="disabled"';
		$compulsory = $compulsory ? 'class="compulsory"' : '';

		return '<input '.$compulsory.' type="radio" '
			.' onchange="if(window.'.$fieldName.'_onchange){window.'.$fieldName.'_onchange(this, arguments.length > 0 ? arguments[0] : window.event)}"'
			.' onclick="if(window.'.$fieldName.'_onclick){window.'.$fieldName.'_onclick(this, arguments.length > 0 ? arguments[0] : window.event)}"'
			.' name="' . $fieldName . '" value="' . $value . "\" $checked $enabled />";
	}

	/**
	 * @static
	 * @param $fieldName
	 * @param string $value
	 * @param bool $checked
	 * @param bool $enabled
	 * @param bool $compulsory
	 * @return string
	 */
	public static function checkbox($fieldName, $value, $checked = false, $enabled = true, $compulsory = false)
	{
		$checked = $checked ? ' checked="checked" ' : '';
		$enabled = $enabled ? '' : 'disabled="disabled"';
		$compulsory = $compulsory ? 'class="compulsory"' : '';

		return '<input '.$compulsory.' type="checkbox" '
			.' onchange="if(window.'.$fieldName.'_onchange){window.'.$fieldName.'_onchange(this, arguments.length > 0 ? arguments[0] : window.event)}"'
			.' onclick="if(window.'.$fieldName.'_onclick){window.'.$fieldName.'_onclick(this, arguments.length > 0 ? arguments[0] : window.event)}"'
			.'name="'.$fieldName.'[]" value="' . $value . "\" $checked $enabled />";
	}


	/**
	 * @static
	 * @param string $fieldName
	 * @param array $boxes
	 * @param array $values
	 * @param int $columns
	 * @param bool $enabled
	 * @return string
	 */
	public static function voltCheckboxGrid($fieldName, array $boxes, $values, $columns = 1, $enabled = true)
	{
		$enabled = $enabled?'':'disabled="disabled"';

		if(!is_array($values))
		{
			$values = explode(',', $values);
		}


		// Build reset() string
		$js_default_values_array = '['.DAO::pdo_implode($values).']';

		$html = '<div id="grid_'.$fieldName.'"><table cellspacing="1" cellpadding="0">';
		$html .= '<tr>';

		$col = 1;
		foreach($boxes as $box)
		{
			if($col > $columns)
			{
				$html .= '</tr><tr>';
				$col = 1;
			}

			$checked = in_array($box[0], $values) ? 'checked="checked"':'';

			$html .= '<td title="'.htmlspecialchars($box[2] ?? '', ENT_QUOTES, 'UTF-8').'" style="padding-right:20px">'
				.'<input type="checkbox" name="'.$fieldName.'[]" value="'.$box[0]."\" $checked $enabled ' . ' title=\"' . $fieldName . '\" '
				. 'onchange=\"if(window.{$fieldName}_onchange){window.{$fieldName}_onchange(this, arguments.length > 0 ? arguments[0] : window.event)}\" '
				. 'onclick=\"if(window.{$fieldName}_onclick){window.{$fieldName}_onclick(this, arguments.length > 0 ? arguments[0] : window.event)}\" />"
				.htmlspecialchars($box[1] ?? '', ENT_QUOTES, 'UTF-8').'</td>';

			$col++;
		}

		$html .= '</tr></table></div>';
		$html .= <<<HTML
<script type="text/javascript">
//<![CDATA[
var grid = document.getElementById('grid_$fieldName');
grid.clear = function() {
	var inputs = this.getElementsByTagName('INPUT');
	for(var i = 0; i < inputs.length; i++)
	{
		inputs[i].checked = false;
	}
}

grid.reset = function() {
	this.clear();
	this.setValues($js_default_values_array);
}

grid.setValues = function(values) {
	if(!(values instanceof Array))
	{
		values = values.split(',');
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

grid.enable = function() {
	var inputs = this.getElementsByTagName('INPUT');
	for(var i = 0; i < inputs.length; i++) 
	{
		inputs[i].disabled = false;
	}
}

grid.disable = function() {
	var inputs = this.getElementsByTagName('INPUT');
	for(var i = 0; i < inputs.length; i++) 
	{
		inputs[i].disabled = true;
	}
}
//]]>
</script>
HTML;

		return $html;
	}



	/**
	 * @static
	 * @param string $fieldName
	 * @param array $boxes
	 * @param array $values
	 * @param int $columns
	 * @param bool $enabled
	 * @return string
	 */
	public static function checkboxGrid($fieldName, array $boxes, $values, $columns = 1, $enabled = true)
	{
		if(is_null($values))
		{
			$values = array();
		}
		elseif(!is_array($values))
		{
			$values = explode(',', $values);
		}

		// Build reset() string
		$js_default_values_array = '['.DAO::pdo_implode($values).']';

		// Layout cells
		$enabled = $enabled?'':'disabled="disabled"';
		$rows_required = ceil(count($boxes) / $columns);

		$html = '<div id="grid_'.$fieldName.'"><table class="table" cellspacing="1" cellpadding="0">';
		for($row = 0; $row < $rows_required; $row++)
		{
			$html .= '<tr>';
			for($col = 0; $col < $columns; $col++)
			{
				$index = (($col * $rows_required) + $row);
				if( $index < count($boxes))
				{
					// Value exists
					switch (count($boxes[$index])) {
						case 3:
							list($value, $label, $tooltip) = $boxes[$index];
							break;
						case 2:
							$value = $boxes[$index][0];
							$label = $boxes[$index][1];
							$tooltip = '';
							break;
						case 1:
							$value = $label = $boxes[$index][0];
							$tooltip = '';
							break;
						default:
							throw new Exception("Zero element array");
					}
					$checked = in_array($value, $values) ? 'checked="checked"':'';
					$html .= '<td title="'.htmlspecialchars($tooltip ?? '', ENT_QUOTES, 'UTF-8').'" style="width: 5%;padding-left:15px; padding-right:5px" align="left">';
					$html .= '<input type="checkbox" name="'.$fieldName.'[]" value="'.htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8')."\" $checked $enabled "
						."onchange=\"if(window.{$fieldName}_onchange){window.{$fieldName}_onchange(this, arguments.length > 0 ? arguments[0] : window.event)}\" "
						."onmouseover=\"if(window.{$fieldName}_onmouseover){window.{$fieldName}_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)}\" "
						."onclick=\"if(window.{$fieldName}_onclick){window.{$fieldName}_onclick(this, arguments.length > 0 ? arguments[0] : window.event)}\"/>";
					$html .= '</td>';
					$html .= '<td>'.htmlspecialchars($label ?? '', ENT_QUOTES, 'UTF-8').'</td>';
				}
				else
				{
					$html .= '<td></td><td></td>';
				}
			}
			$html .= '</tr>';
		}
		$html .= '</table></div>';


		$html .= <<<HTML
<script type="text/javascript">
//<![CDATA[
var grid = document.getElementById('grid_$fieldName');

grid.clear = function() {
	var inputs = this.getElementsByTagName('INPUT');
	for(var i = 0; i < inputs.length; i++)
	{
		inputs[i].checked = false;
	}
}

grid.reset = function() {
	this.clear();
	this.setValues($js_default_values_array);
}

grid.setValues = function(values) {
	if(values != null && !(values instanceof Array))
	{
		values = values.split(',');
	}
	values.sort();

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

grid.disable = function(values, blnDisable) {
	if(values != null && !(values instanceof Array))
	{
		values = new String(values).split(',');
	}
	values.sort();
	
	var inputs = this.getElementsByTagName('INPUT');
	for(var i = 0; i < inputs.length; i++)
	{
		if(values == null || isInSortedArray(inputs[i].value, values))
		{
			inputs[i].disabled = blnDisable;
		}
	}
}
//]]>
</script>
HTML;

		return $html;
	}

	/**
	 * @static
	 * @param string $fieldName
	 * @param array $buttons
	 * @param $value
	 * @param int $columns
	 * @param bool $enabled
	 * @return string
	 */
	public static function radioButtonGrid($fieldName, array $buttons, $value, $columns = 1, $enabled = true)
	{
		$enabled = $enabled?'':'disabled="disabled"';

		$html = '<div id="grid_'.$fieldName.'"><table class="table" cellspacing="1" cellpadding="0" style="width: 100%;">';
		$html .= '<tr>';

		$col = 1;
		foreach($buttons as $button)
		{
			if($col > $columns)
			{
				$html .= '</tr><tr>';
				$col = 1;
			}

			$checked = $button[0] == $value ? 'checked="checked"':'';

			$html .= '<td title="'.htmlspecialchars($button[2] ?? '', ENT_QUOTES, 'UTF-8').'" style="padding-right:20px">'
				.'<input type="radio" name="'.$fieldName.'" value="'.$button[0]."\" $checked $enabled "
				." onchange=\"if(window.{$fieldName}_onchange){window.{$fieldName}_onchange(this, arguments.length > 0 ? arguments[0] : window.event)}\" "
				." onclick=\"if(window.{$fieldName}_onclick){window.{$fieldName}_onclick(this, arguments.length > 0 ? arguments[0] : window.event)}\" />"
				.htmlspecialchars($button[1] ?? '', ENT_QUOTES, 'UTF-8').'</td>';

			$col++;
		}

		$html .= '</tr></table></div>';
		$html .= <<<HTML
<script type="text/javascript">
//<![CDATA[
var grid = document.getElementById('grid_$fieldName');
grid.clear = function() {
	var inputs = this.getElementsByTagName('INPUT');
	for(var i = 0; i < inputs.length; i++)
	{
		inputs[i].checked = false;
	}
}

grid.setValue = function(value) {
	// Create associated array of boxes
	var inputs = this.getElementsByTagName('INPUT');
	for(var i = 0; i < inputs.length; i++) 
	{
		inputs[i].checked = (inputs[i].value == value);
	}
}

grid.getValue = function() {
	var inputs = this.getElementsByTagName('INPUT');
	var value = null;
	for(var i = 0; i < inputs.length; i++) 
	{
		if(inputs[i].checked)
		{
			value = inputs[i].value;
		}
	}
	return value;
}
//]]>
</script>
HTML;

		return $html;
	}


	/**
	 * @static
	 * @param string $fieldName
	 * @param string $value
	 * @param bool $compulsory
	 * @param bool $enabled
	 * @return string
	 */
	public static function datebox_old($fieldName, $value, $compulsory = false, $enabled = true)
	{
		$value = htmlspecialchars(Date::toShort($value) ?? '', ENT_QUOTES, 'UTF-8');
		$class = $compulsory ? 'compulsory':'optional';
		$enabled = $enabled ? '':'disabled="disabled"';

		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
		{
			if (!$value){
				$value = "dd/mm/yyyy";
			}

			// HTML 5 "placeholder" attribute unavailable in IE
			$html = <<<HTML
<span>
<input class="$class DateBox" type="text" title="dd/mm/yyyy" id="input_$fieldName" name="$fieldName" value="$value"
size="10" maxlength="10"
onfocus="if(this.value=='dd/mm/yyyy'){this.value=''; $(this).css('color','black')}; if(window.{$fieldName}_onfocus){window.{$fieldName}_onfocus(this, arguments.length > 0 ? arguments[0] : window.event);}"
onblur="if(this.value==''){this.value='dd/mm/yyyy'; $(this).css('color','gray')}; if(!this.validate()){return false;};if(window.{$fieldName}_onblur){window.{$fieldName}_onblur(this, arguments.length > 0 ? arguments[0] : window.event);}"
onchange="if(window.{$fieldName}_onchange){window.{$fieldName}_onchange(this, arguments.length > 0 ? arguments[0] : window.event);}" $enabled />
<a href="#" id="anchor_{$fieldName}" name="anchor_{$fieldName}" onclick="var textbox = this.parentNode.getElementsByTagName('INPUT')[0]; if(textbox.disabled==false){window.calPop.select(textbox, this.id, 'dd/MM/yyyy');} return false;">
<img src="/images/calendar-icon.gif" border="0" style="vertical-align:text-bottom" width="19" height="15" alt="Show calendar" title="Show calendar" /></a>
</span>
HTML;
		}
		else
		{
			$html = <<<HTML
<span>
<input class="$class DateBox" type="text" placeholder="dd/mm/yyyy"
title="dd/mm/yyyy" id="input_$fieldName" name="$fieldName" value="$value"
size="10" maxlength="10"
onfocus="if(window.{$fieldName}_onfocus){window.{$fieldName}_onfocus(this, arguments.length > 0 ? arguments[0] : window.event);}"
onblur="if(!this.validate()){return false;};if(window.{$fieldName}_onblur){window.{$fieldName}_onblur(this, arguments.length > 0 ? arguments[0] : window.event);}"
onchange="if(window.{$fieldName}_onchange){window.{$fieldName}_onchange(this, arguments.length > 0 ? arguments[0] : window.event);}" $enabled />
<a href="#" id="anchor_{$fieldName}" name="anchor_{$fieldName}" onclick="var textbox = this.parentNode.getElementsByTagName('INPUT')[0]; if(textbox.disabled==false){window.calPop.select(textbox, this.id, 'dd/MM/yyyy');} return false;">
<img src="/images/calendar-icon.gif" border="0" style="vertical-align:text-bottom" width="19" height="15" alt="Show calendar" title="Show calendar" /></a>
</span>
HTML;
		}

		$script = <<<HTML
<script type="text/javascript">
//<![CDATA[
	var ele = document.getElementById("input_$fieldName");
	ele.validate = function(){
		if(this.value && this.value != 'dd/mm/yyyy' && window.stringToDate && !window.stringToDate(this.value)){
			alert("Invalid calendar-date or invalid date-format. Please use the format dd/mm/yyyy.");
			this.value = '';
			this.focus();
			return false;
		}
		return true;
	}
//]]>
</script>
HTML;

		return $html . $script;
	}

	public static function datebox($fieldName, $value, $compulsory = false, $is_dob = false)
	{
		$value = htmlspecialchars(Date::toShort($value) ?? '', ENT_QUOTES, 'UTF-8');
		$class = $compulsory ? 'compulsory':'optional';

		$html = <<<HEREDOC
<input class="datepicker $class" type="text" id="input_$fieldName" name="$fieldName" value="$value" size="10" maxlength="10" placeholder="dd/mm/yyyy" />
<script language="JavaScript">
	var ele = document.getElementById("input_$fieldName");
	ele.validate = function(){
		if(this.value != "" && (window.stringToDate(this.value) == null) ){
			var incorrect = this.value;
			alert("Invalid date format or invalid calendar date '" + incorrect + "'.  Format: dd/mm/yyyy");
			this.focus();
			return false;
		}
		return true;
	};
</script>
HEREDOC;
		if($is_dob)
		{
			$html .= <<<HEREDOC
<script language="JavaScript">
	var ele = document.getElementById("input_$fieldName");
	ele.validate = function(){
		if(this.value != "" && (window.stringToDate(this.value) != null) ){
			var pieces = this.value.split('/');
			var dateString = pieces[2]+'-'+pieces[1]+'-'+pieces[0];
			var birthday = new Date(dateString);
			if(birthday >= Date.now())
			{
				alert("Date of birth is invalid");
				this.focus();
				return false;
			}
		}
		return true;
	};
</script>
HEREDOC;
		}
		return $html;
	}

	/**
	 * @static
	 * @param string $fieldname
	 * @param string $value
	 * @param bool $compulsory
	 * @return string
	 */
	public static function emailbox($fieldName, $value, $compulsory = false)
	{
		$class = $compulsory ? 'compulsory':'optional';

		$html = <<<HEREDOC
<input class="$class" type="text" id="input_$fieldName" name="$fieldName" value="$value" size="40" maxlength="250" />
<script language="JavaScript">
	function validateEmail(email) {
		//var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		var re = /^[a-zA-Z0-9!#$%'\*\+\-/=\?\^_`\{\|\}~]+(\.[a-zA-Z0-9!#$%'\*\+\-/=\?\^_`\{\|\}~]+)*@[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9](\.[a-zA-Z0-9][a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])*\.[a-zA-Z]{2,6}$/;
	return re.test(email);
	}

	var ele = document.getElementById("input_$fieldName");
	ele.validate = function(){
		if(this.value != "" && (!validateEmail(this.value)) ){
			var incorrect = this.value;
			alert("Invalid email address '" + incorrect + "'");
			this.focus();
			return false;
		}
		return true;
	}
</script>
HEREDOC;
		return $html;
	}
	/**
	 * @static
	 * @param string $fieldname
	 * @param string $value
	 * @param bool $compulsory
	 * @return string
	 */
	public static function timebox($fieldname, $value, $compulsory = false)
	{
		$class = $compulsory ? 'timebox compulsory':'timebox optional';

		$html = <<<HTML
<input class="$class" type="text" name="$fieldname" id="input_$fieldname" value="$value" size="5" maxlength="5" />
<script type="text/javascript">
//<![CDATA[
	var ele = document.getElementById("input_$fieldname");
	ele.validate = function(){
		if(this.value=='')
			return;
		var regTime = /^(\d\d):(\d\d)$/;
		var hours;
		var minutes;
		if(matches = this.value.match(regTime))
		{
			hours = parseInt(matches[1]);
			minutes = parseInt(matches[2]);
			if(hours < 0 || hours > 24 || minutes < 0 || minutes > 59)
			{
				alert("Please enter the start time in 24 hour format HH:MM");
				this.focus();
				return false;
			}
		}
		else
		{
			alert("Please enter the start time in 24 hour format HH:MM");
			this.focus();
			return false;	
		}
	}
//]]>
</script>
HTML;

		return $html;
	}

	/**
	 * @static
	 * @param string $fieldname
	 * @param string $value
	 * @param bool $compulsory
	 * @return string
	 */
	public static function timebox_with_seconds($fieldname, $value, $compulsory = false)
	{
		$class = $compulsory ? 'compulsory':'optional';

		$html = <<<HTML
<input class="$class" type="text" name="$fieldname" id="input_$fieldname" value="$value" size="5" maxlength="8" />
<script type="text/javascript">
//<![CDATA[
	var ele = document.getElementById("input_$fieldname");
	ele.validate = function(){
		var regTime = /^(\d\d):(\d\d):(\d\d)$/;
		var hours;
		var minutes;
		var seconds;
		if(matches = this.value.match(regTime))
		{
			hours = parseInt(matches[1]);
			minutes = parseInt(matches[2]);
			seconds = parseInt(matches[3]);
			if(hours < 0 || hours > 24 || minutes < 0 || minutes > 59 || seconds < 0 || seconds > 59)
			{
				alert("Please enter the start time in 24 hour format HH:MM");
				this.focus();
				return false;
			}
		}
		else
		{
			alert("Please enter the start time in 24 hour format HH:MM");
			this.focus();
			return false;
		}
	}
//]]>
</script>
HTML;

		return $html;
	}

	/**
	 * @static
	 * @param string $url
	 * @param string $class
	 * @return string
	 */
	public static function viewrow_opening_tag($url, $class = '')
	{
		return <<<HTML
<tr class="$class" onclick="window.location.href='$url';"
onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"
onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
HTML;
	}


	/**
	 * @static
	 * @param string $value
	 * @return string
	 */
	public static function cell($value)
	{
		if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') !== FALSE)
		{
			return ($value == '') ? '&nbsp;' : nl2br(preg_replace('%([:/\\\\()#])([^0-9])%', '\1&#8203;\2', htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8')));
		}
		else
		{
			return ($value == '') ? '&nbsp;' : nl2br(htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8'));
		}
	}

	/**
	 * @static
	 * @param string $name
	 * @param array $labelsAndValues
	 * @param string $actualValue
	 * @return string
	 */
	public static function radioChoice($name, $labelsAndValues, $actualValue)
	{
		$html = '';
		foreach($labelsAndValues AS $label => $value)
		{
			$html .= $label . ' <input type="radio" name="' . $name . '" value="' . $value . '"' . ($actualValue == $value ? ' checked="checked"' : '') . ' />';
		}
		return $html;
	}

	/**
	 * @static
	 * @param string $name
	 * @param string $value
	 * @param string $additionalHTML
	 * @return string
	 */
	public static function textbox($name, $value = '', $additionalHTML = '')
	{
		return '<input type="text" name="' . $name . '" value="' . ($value) . '"' . $additionalHTML . ' />';
	}
	/*
	 public static function textboxdiff($name, $value = '', $additionalHTML = '')
	 {
		 return '<input type="text" name="' . $name . '" id="' . $name . '" value="' . ($value) . '"' . $additionalHTML . ' />';
	 }
 */
	/**
	 * @static
	 * @param string $name
	 * @param string $value
	 * @param string $additionalHTML
	 * @param int $totalSize
	 * @param string $startDate
	 * @return string
	 */
	public static function dateboxdiff($name, $value = '', $additionalHTML = '', $totalSize, $startDate)
	{
		$value = htmlspecialchars(Date::toShort($value) ?? '', ENT_QUOTES, 'UTF-8');

		$html = <<<HTML
<input class="datepicker compulsory" type="text" id="input_$name" name="$name" value="$value" size="10" maxlength="10" placeholder="dd/mm/yyyy" />
<script language="JavaScript">
	var ele = document.getElementById("input_$name");
	ele.validate = function(){
		if(this.value != "" && (window.stringToDate(this.value) == null) ){
			var incorrect = this.value;
			alert("Invalid date format or invalid calendar date '" + incorrect + "'.  Format: dd/mm/yyyy");
			this.focus();
			return false;
		}
		return true;
	};

$('input[name=$name]').change(function() {
	var totalSize = $totalSize;
	dateChanged = document.getElementsByName("$name").item(0).value;
	nameOfTF = "$name"; // e.g. meeting_0, meeting_1 etc.
	nameOfTF = nameOfTF.match(/\d/g); // extract the digit
	nmbr = nameOfTF; // now it contains the number of the textfield which is changed

	diffTF = "difference_"+nmbr; // corresponding difference textfield e.g. difference_0, difference_1 etc.
	nameOfTF = nameOfTF - 1;
	nameOfTF = "meeting_" + nameOfTF; // to get the name of the previous textfield name e.g. meeting_1 etc.
	if(nmbr == 0)
		dateLastMeeting = "$startDate";
	else
		dateLastMeeting = document.getElementsByName(nameOfTF).item(0).value; // the value of the last meeting date

	var output = getDiff(dateChanged, dateLastMeeting); // output is the array containing the difference between two dates in weeks and days


	document.getElementsByName(diffTF).item(0).value = output['weeks'] + "w " + output['days'] + "d"; //set the new value of the corresponding difference textfield
	if(nmbr != totalSize) // if the date changed is not the last row then update the difference of next meeting as well
	{

		nmbr = parseInt(nmbr) + 1; // incrementing the nmbr to get the next index number of textfield
		diffTF = nmbr;
		diffTF = "difference_" + diffTF; // get the corresponding difference textfield
		nmbr = "meeting_" + nmbr; // get the corresponding meeting textfield

		dateNextMeeting = document.getElementsByName(nmbr).item(0).value; // get the value of next meeting date

		if(dateNextMeeting != "")
		{
			var output = getDiff(dateNextMeeting, dateChanged);
			document.getElementsByName(diffTF).item(0).value = output['weeks'] + "w " + output['days'] + "d";
		}
	}

});

function getDiff(date1, date2)
{
	var result = new Array();

	date1 = getDate(date1);
	date2 = getDate(date2);

	var difference = date1 - date2;
	var days = difference/(1000*60*60*24);

	result['weeks'] = 0;
	result['days'] = days;

	if(days > 7)
	{
		result['weeks'] = Math.floor(days / 7);
		result['days'] = Math.floor(days % 7);
	}

	return result;
}

function getDate(inputString)
{
	var dateParts = inputString.split("/");
	day = dateParts[0];
	month = dateParts[1];
	year = dateParts[2];

	return new Date(year + "/" + month + "/" + day);

}



</script>
HTML;

		return $html;
		//return '<input type="text" name="' . $name . '" value="' . ($value) . '"' . $additionalHTML . ' />';
	}


	/**
	 * @static
	 * @param $text
	 * @return string
	 */
	public static function nl2p($text)
	{
		return '<p>'.preg_replace('/[\n\r]+/', '</p><p>', $text).'</p>';
	}

	/**
	 * @static
	 * @param string $text
	 * @return mixed|null|string
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
		$text = htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');

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
		$text = preg_replace('/\[([A-Z][a-z]+(?:[A-Z][a-z]+)+) (.+?)\]/', '<a href="" onclick="displayHelp(\'$1\'); return false;">$2</a>', $text);
		$text = preg_replace('/(?<=\s)([A-Z][a-z]+([A-Z][a-z]+)+)/', '<a href="" onclick="displayHelp(\'$1\'); return false;">$1</a>', $text);
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
		if(preg_match('/<p>\s*#{1,}/', $text))
		{
			$text = preg_replace('@(<p>\s*#{1,}.*</p>[\r\n]*)+@', "<ol style=\"list-style-type:decimal\">\r\n$0</ol>\r\n", $text);
			$text = preg_replace('@(<p>\s*#{2,}.*</p>[\r\n]*)+@', "<ol style=\"list-style-type:lower-alpha\">\r\n$0</ol>\r\n", $text);
			$text = preg_replace('@(<p>\s*#{3,}.*</p>[\r\n]*)+@', "<ol style=\"list-style-type:lower-roman\">\r\n$0</ol>\r\n", $text);
			$text = preg_replace('@<p>\s*\#+\s*(.*)</p>@', "<li style=\"margin-top:4px\">$1", $text);
		}

		// Unordered lists
		if(preg_match('@<p>\s*[*-]{1,}@', $text))
		{
			$text = preg_replace('@(<p>\s*[*-]{1,}.*</p>[\r\n]*)+@', "<ul style=\"list-style-type:square;\">\r\n$0</ul>\r\n", $text);
			$text = preg_replace('@(<p>\s*[*-]{2,}.*</p>[\r\n]*)+@', "<ul style=\"list-style-type:disc;\">\r\n$0</ul>\r\n", $text);
			$text = preg_replace('@(<p>\s*[*-]{3,}.*</p>[\r\n]*)+@', "<ul style=\"list-style-type:circle;\">\r\n$0</ul>\r\n", $text);
			$text = preg_replace('@<p>\s*[*-]+\s*(.*)</p>@', "<li style=\"margin-top:4px\">$1", $text);
		}

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


	/**
	 * Render an array of arrays (resulset) to HTML
	 * @param array|PDO|mysqli $rs Array of arrays, mysqli_result or PDOStatement
	 */
	public static function renderResultset($rs)
	{
		if(!$rs){
			echo "<p>NULL</p>";
			return;
		}

		if(is_array($rs))
		{
			if(count($rs) == 0){
				echo "<p>Empty resultset</p>";
				return;
			}

			$first_row = $rs[0];

			echo '<table border="1">';

			// Write headers
			echo "<tr>";
			foreach($first_row as $key=>$value)
			{
				echo "<th style=\"background-color:silver\">", $key, "</th>";
			}
			echo "</tr>";

			foreach($rs as $row)
			{
				echo "<tr>";
				foreach($row as $key=>$value)
				{
					echo "<td align=\"left\">", $value, "</td>";
				}
				echo "</tr>";
			}

			echo "</table>";
		}
		elseif($rs instanceof mysqli_result)
		{
			/* @var $rs mysqli_result */
			$row = $rs->fetch_assoc();
			if(!$row){
				echo "<p>Empty resultset</p>";
				return;
			}

			echo '<table border="1">';

			// Write headers
			echo "<tr>";
			foreach($row as $key=>$value)
			{
				echo "<th style=\"background-color:silver\">", $key, "</th>";
			}
			echo "</tr>";

			do{
				echo "<tr>";
				foreach($row as $key=>$value)
				{
					echo "<td align=\"left\">", $value, "</td>";
				}
				echo "</tr>";
			} while($row = $rs->fetch_assoc());

			echo "</table>";
		}
		elseif($rs instanceof PDOStatement)
		{
			/* @var $rs PDOStatement */
			$row = $rs->fetch(PDO::FETCH_ASSOC);
			if(!$row){
				echo "<p>Empty resultset</p>";
				return;
			}

			echo '<table border="1">';

			// Write headers
			echo "<tr>";
			foreach($row as $key=>$value)
			{
				echo "<th style=\"background-color:silver\">", $key, "</th>";
			}
			echo "</tr>";

			do{
				echo "<tr>";
				foreach($row as $key=>$value)
				{
					echo "<td align=\"left\">", $value, "</td>";
				}
				echo "</tr>";
			} while($row = $rs->fetch(PDO::FETCH_ASSOC));

			echo "</table>";
		}
	}

	/**
	 * Convenience method that calls {@link HTML::renderResultset}
	 * @param PDO $link
	 * @param string $sql
	 */
	public static function renderQuery(PDO $link, $sql)
	{
		$resultset = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		self::renderResultset($resultset);
	}

	public static function yesNoUnknown($text, $yes="Yes", $no="No", $unknown="")
	{
		if (is_null($text) || $text === ""){
			return $unknown;
		}

		$text = strtolower($text);
		if ($text == "no" || $text == "false" || $text == "disabled" || $text == false) {
			return $no;
		} else if($text == "yes" || $text == "true" || $text == "enabled" || $text == true){
			return $yes;
		}

		return $no;
	}

	public static function renderWorkbookIcons($fieldName = '', $bookmark_only = false, $btn_assessor_comments_class = '')
	{
		if($_SESSION['user']->type == User::TYPE_LEARNER)
		{
			if($bookmark_only)
				return '<button title="add/remove bookmark for this page" class="btn btn-default dim" type="button" onclick="bookmarkPage();"><i class="fa fa-bookmark-o"></i></button>';
			else
				return <<<HTML
<button title="save information" class="btn btn-warning dim" type="button" onclick="partialSave();"><i class="fa fa-save"></i> </button>
<button title="view assessor feedback for this section " class="btn btn-success dim btnViewAssessorComments $btn_assessor_comments_class" type="button" onclick="showAssessorFeedback('$fieldName')"><i class="fa fa-comments"></i></button>
<button title="view change history for this section" class="btn btn-info dim btnViewSectionHistory" type="button" onclick="showSectionHistory('$fieldName')"><i class="fa fa-history"></i></button>
<button title="add/remove bookmark for this page" class="btn btn-default dim" type="button" onclick="bookmarkPage();"><i class="fa fa-bookmark-o"></i></button>
HTML;
		}
		else
		{
			if($bookmark_only)
				return '';
			else
				return <<<HTML
<button title="view assessor feedback for this section " class="btn btn-success dim btnViewAssessorComments $btn_assessor_comments_class" type="button" onclick="showAssessorFeedback('$fieldName')"><i class="fa fa-comments"></i></button>
<button title="view change history for this section" class="btn btn-info dim btnViewSectionHistory" type="button" onclick="showSectionHistory('$fieldName')"><i class="fa fa-history"></i></button>
HTML;
		}
	}

	/**
	 * @static
	 * @param string $fieldname
	 * @param string $value
	 * @param bool $compulsory
	 * @return string
	 */
	public static function ratingStars($fieldName, $value, $min_value, $max_value, $compulsory = false)
	{
		$fieldName = 'rating_'.$fieldName;

		$html = '<section class="container_'.$fieldName.'">';
		for($i = $min_value; $i <= $max_value; $i++)
		{
			$checked = $i == $value?'checked="checked"':'';
			//$onchange = 'onchange="if(window.'.$fieldName.'_onchange){window.'.$fieldName.'_onchange(this, arguments.length > 0 ? arguments[0] : window.event)}"';
			$html .= '<input type="radio" name="' . $fieldName . '" class="rating" value="' . $i .'" ' . $checked . ' />';
		}
		$html .= '</section>';
		$html .= <<<SCRIPT
<script language="JavaScript">
	$(".container_$fieldName").rating();
</script>
SCRIPT;

		//console.log(input[name=$fieldName]:checked.val());
		return $html;
	}

	public static function csvSafe($value)
	{
		$value = str_replace(',', '; ', $value);
		$value = str_replace(array("\n", "\r"), '', $value);
		$value = str_replace("\t", '', $value);
		$value = str_replace("&nbsp;", '', $value);
		$value = '"' . str_replace('"', '""', $value) . '"';
		return $value;
	}
}