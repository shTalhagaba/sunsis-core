<?php
class VoltDateRangeViewFilter implements VoltIViewFilter
{
	public function __construct($filter_name, $field_name, $default_start_date, $default_end_date, $timestamp = false)
	{
		$this->key = $filter_name;
		$this->field_name = $field_name;
		$this->start_date = $this->default_start_date = $default_start_date;
		$this->end_date = $this->default_end_date = $default_end_date;
		$this->timestamp = $timestamp;
	}


	/**
	 * Each filter for a view must have a unique name.  The name is used
	 * to request a particular filter's data from a view and forms part
	 * of the name of the HTML form element(s) used to represent the filter
	 * on a web page.
	 *
	 * @param string $name
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
	 * @param mixed $dataSource An array, a database link...
	 */
	public function refresh(PDO $dataSource = null)
	{
		//
	}


	/**
	 * Equivalent of the old setState() method
	 *
	 * @param mixed $v
	 * @return bool
	 */
	public function setValue($v)
	{
		$fieldName = $this->getHtmlFieldName();

		if(is_array($v))
		{
			if(array_key_exists($fieldName, $v))
			{
				$v = $v[$fieldName];
			}
			else
			{
				return false;
			}
		}

		// Parse the value string into two dates
		$v = trim($v);
		if($v == "" || $v == "|")
		{
			$start_date = $end_date = null;
		}
		else
		{
			$dates = explode('|', $v);
			if(count($dates) == 1)
			{
				$start_date = Date::toShort($dates[0]);
				$end_date = null;
			}
			else
			{
				$start_date = Date::toShort($dates[0]);
				$end_date = Date::toShort($dates[1]);
			}
		}

		// Set any blank dates to today's date
		if($start_date == "" && $this->default_start_date != ""){
			$start_date = Date::toShort("now");
		}
		if($end_date == "" && $this->default_end_date != ""){
			$end_date = Date::toShort("now");
		}
		/*if($start_date == "" || $end_date == "")
		{
			$d = new Date("now");
			$start_date = $start_date ? $start_date : $d->formatShort();
			$end_date = $end_date ? $end_date : $d->formatShort();
		}*/

		// Compare old and new values
		$existing_value = $this->start_date.'|'.$this->end_date;
		$new_value = $start_date.'|'.$end_date;
		$state_changed = ($existing_value != $new_value);

		// If the dates are identical, switch to single-date mode
		$this->view->setPreference($fieldName.'_range', $start_date == $end_date ? '0':'1');

		// Set values
		$this->start_date = $start_date;
		$this->end_date = $end_date;

		return $state_changed;
	}


	public function getValue()
	{
		if($this->start_date == "" && $this->end_date == "")
		{
			return $this->start_date . '|' . $this->end_date;
		}
		else
		{
			if($this->start_date)
			{
				return $this->start_date;
			}
			elseif($this->end_date)
			{
				return $this->end_date;
			}
			else
			{
				return null;
			}
		}
	}


	public function getStartDate()
	{
		return $this->start_date;
	}

	public function setStartDate($d)
	{
		$this->start_date = Date::toShort($d);
	}

	public function getEndDate()
	{
		return $this->end_date;
	}

	public function setEndDate($d)
	{
		$this->end_date = Date::toShort($d);
	}


	public function getDisplayValue()
	{
		return $this->getValue();
	}


	/**
	 * Returns the filter to its initial state.
	 *
	 */
	public function reset()
	{
		$changed = $this->start_date != $this->default_start_date || $this->end_date != $this->default_end_date;
		$this->start_date = $this->default_start_date;
		$this->end_date = $this->default_end_date;
		return $changed;
	}


	public function toHTML()
	{
		$fieldName = $this->getHtmlFieldName();
		$value = $this->start_date.'|'.$this->end_date;
		$range = $this->view->getPreference($fieldName."_range");
		$checked = $range ? ' checked="checked" ':'';
		$end_date_style = $this->view->getPreference($fieldName."_range") ? 'display:inline':'display:none';

		$html = <<<HEREDOC
<input type="hidden" id="{$fieldName}" name="{$fieldName}" value="$value"/>
<input type="hidden" id="{$fieldName}_range" name="{$fieldName}_range" value="$range" />
<span id="{$fieldName}_start_date_span" style="margin-right:20px"><input class="datepicker" type="text" id="{$fieldName}_start_date"
name="{$fieldName}_start_date" value="{$this->start_date}" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></span>
<span id="{$fieldName}_end_date_span" style="$end_date_style;margin-right:20px">to <input class="datepicker" type="text" id="{$fieldName}_end_date" name="{$fieldName}_end_date" value="{$this->end_date}" size="10" maxlength="10" placeholder="dd/mm/yyyy"/></span>
<span title="Tick to specify a range of dates" >
<input type="checkbox" name="{$fieldName}_link_ui" id="{$fieldName}_range_ui" value="1" $checked /> specify a range of dates
</span>

<script type="text/javascript">
var obj = $("#{$fieldName}_start_date");
obj[0].resetToDefault = function(){
	this.value = "{$this->default_start_date}";
	$('#{$fieldName}').val($('#{$fieldName}_start_date').val() + '|' + $('#{$fieldName}_end_date').val());
};
obj[0].validate = function(){
	if(this.value != "" && (stringToDate(this.value) == null) ){
		var incorrect = this.value;
		alert("Invalid date format or invalid calendar date '" + incorrect + "'.  Format: dd/mm/yyyy");
		this.value = "{$this->default_start_date}";
		this.focus();
		return false;
	}
	return true;
}
obj.change(function(e){
	if($('#{$fieldName}_range').val() == 0){
		$('#{$fieldName}_end_date').val($(this).val());
	}
	if(dateCmp($(this).val(), $('#{$fieldName}_end_date').val()) > 0){
		$('#{$fieldName}_end_date').val($(this).val());
	}
	$('#{$fieldName}').val($('#{$fieldName}_start_date').val() + '|' + $('#{$fieldName}_end_date').val());
});

obj = $("#{$fieldName}_end_date");
obj[0].resetToDefault = function(){
	this.value = "{$this->default_end_date}";
	$('#{$fieldName}').val($('#{$fieldName}_start_date').val() + '|' + $('#{$fieldName}_end_date').val());
};
obj[0].validate = function(){
	if(this.value != "" && (stringToDate(this.value) == null) ){
		var incorrect = this.value;
		alert("Invalid date format or invalid calendar date '" + incorrect + "'.  Format: dd/mm/yyyy");
		this.value = "{$this->default_end_date}";
		this.focus();
		return false;
	}
	return true;
}
obj.change(function(e){
	if($('#{$fieldName}_range').val() == 0){
		$('#{$fieldName}_start_date').val($(this).val());
	}
	if(dateCmp($(this).val(), $('#{$fieldName}_start_date').val()) < 0){
		$('#{$fieldName}_start_date').val($(this).val());
	}
	$('#{$fieldName}').val($('#{$fieldName}_start_date').val() + '|' + $('#{$fieldName}_end_date').val());
});

$("#{$fieldName}_range_ui").click(function(e){
	$('#{$fieldName}_range').val(this.checked?'1':'0');
	if(this.checked){
		$('#{$fieldName}_end_date_span').fadeIn();
	} else {
		$('#{$fieldName}_end_date_span').fadeOut();
		$('#{$fieldName}_end_date').val($("#{$fieldName}_start_date").val());
		$('#{$fieldName}').val($('#{$fieldName}_start_date').val() + '|' + $('#{$fieldName}_end_date').val());
	}
});
</script>

HEREDOC;

		return $html;
	}


	public function getSQLStatement()
	{
		if($this->start_date && $this->end_date)
		{
			if($this->start_date == $this->end_date && $this->timestamp == false)
			{
				$sql = "WHERE ".$this->field_name." = '".Date::toMySQL($this->start_date)."'";
			}
			else
			{
				$sql = "WHERE ".$this->field_name." BETWEEN '".Date::toMySQL($this->start_date)
					."' AND '".Date::toMySQL($this->end_date)." 23:23:59'";
			}
		}
		else if($this->start_date)
		{
			$sql = "WHERE ".$this->field_name." >= '".Date::toMySQL($this->start_date)."' ";
		}
		else if($this->end_date)
		{
			$sql = "WHERE ".$this->field_name." <= '".Date::toMySQL($this->end_date)." 23:23:59' ";
		}
		else
		{
			$sql = null;
		}

		if(!is_null($sql))
		{
			return new SQLStatement($sql);
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
	 * @return string a sort description of the filter's status
	 */
	public function getDescription()
	{
		if($this->start_date && $this->end_date)
		{
			if($this->start_date == $this->end_date)
			{
				$txt = $this->start_date;
			}
			else
			{
				$txt = "from ".$this->start_date." to ".$this->end_date;
			}
		}
		else if($this->start_date)
		{
			$txt = ">= ".$this->start_date;
		}
		else if($this->end_date)
		{
			$txt = "<= ".$this->end_date;
		}
		else
		{
			return null;
		}


		return sprintf($this->description_format, $txt);
	}


	public function setParentView(VoltView $v)
	{
		$this->view = $v;
	}

	public function getParentView()
	{
		return $this->view;
	}

	private function getHtmlFieldName()
	{
		$parentView = $this->getParentView();
		$fieldName = $parentView->getViewName() . '_' . $this->key;
		return $fieldName;
	}


	private $key = null;
	private $field_name = null;
	private $timestamp = false;

	private $start_date = null;
	private $end_date = null;
	private $default_start_date = null;
	private $default_end_date = null;
	private $description_format = null;

	/** @var View */
	private $view = null;
}
?>