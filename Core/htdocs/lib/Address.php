<?php
/**
 * BS7666 address
 */
class Address
{
	/**
	 * @param mixed $values Keyed array or object to copy values from
	 * @param string $prefix Optional prefix used by keys/fields in the array/object e.g. 'home_' or 'work_'
	 */
	public function __construct($values = null, $prefix = '')
	{
		if (!is_null($values)) {
			$this->set($values, $prefix);
		} else {
			$this->_setDefaultAddressType();
		}
	}

	/**
	 * @param mixed $values Keyed array or object to copy values from
	 * @param string $prefix Optional prefix used by keys/fields in the array/object e.g. 'home_' or 'work_'
	 * @throws Exception
	 */
	public function set($values, $prefix = '')
	{
		if (!is_array($values) && !is_object($values)) {
			throw new Exception('Argument $values must be an object or associative array');
		}
		$values = (array) $values;

		// Flags for use during migration from BS7666 to 4-line address
		// BS7666 has precedence
		if (array_key_exists($prefix . 'saon_start_number', $values)) {
			$this->_is4LineAddress = false;
			$this->_isBs7666Address = true;
		} else if (array_key_exists($prefix.'address_line_1', $values) && array_key_exists($prefix.'address_line_2', $values)
			&& array_key_exists($prefix.'address_line_3', $values) && array_key_exists($prefix.'address_line_4', $values)
		) {
			$this->_is4LineAddress = true;
			$this->_isBs7666Address = false;
		} else {
			throw new Exception("No address fields detected with prefix '$prefix'");
		}

		// Populate this object
		$this->prefix = $prefix;
		foreach ($this as $key => $value) {
			$field = ($this->prefix) . $key;
			if (array_key_exists($field, $values)) {
				$this->$key = $values[$field];
			}
		}

		$this->_trimAddressFields();

		// Generate values for the 4-line address fields if they have not been populated
		/*		if (empty($address1) && empty($address2) && empty($address3) && empty($address4)) {
			$lines = $this->to4Lines();
			list($this->address1, $this->address2, $this->address3, $this->address4) = $lines;
		}*/
	}


	private function _setDefaultAddressType()
	{
		$this->_is4LineAddress = false;
		$this->_isBs7666Address = true;
	}

	/**
	 * @return bool
	 */
	public function isBs7666Address()
	{
		return $this->_isBs7666Address;
	}


	/**
	 * @return bool
	 */
	public function is4LineAddress()
	{
		return $this->_is4LineAddress;
	}


	/**
	 * Format the address for editing
	 * @param bool $compulsory
	 * @return string HTML
	 */
	public function formatEdit($compulsory = false, $borough = false)
	{
		if ($this->_isBs7666Address) {
			return $this->_formatEditBs7666($compulsory);
		} else if ($this->_is4LineAddress) {
			return $this->_formatEdit4Line($compulsory, $borough);
		} else {
			return '';
		}
	}

	/**
	 * Format the address for editing
	 * @param boolean $compulsory
	 * @return string HTML
	 */
	private function _formatEditBs7666($compulsory = false)
	{
		$class = $compulsory ? 'compulsory':'optional';

		$paon_start_number = htmlspecialchars((string)$this->paon_start_number);
		$paon_start_suffix = htmlspecialchars((string)$this->paon_start_suffix);
		$paon_end_number = htmlspecialchars((string)$this->paon_end_number);
		$paon_end_suffix = htmlspecialchars((string)$this->paon_end_suffix);
		$paon_description = htmlspecialchars((string)$this->paon_description);

		$saon_start_number = htmlspecialchars((string)$this->saon_start_number);
		$saon_start_suffix = htmlspecialchars((string)$this->saon_start_suffix);
		$saon_end_number = htmlspecialchars((string)$this->saon_end_number);
		$saon_end_suffix = htmlspecialchars((string)$this->saon_end_suffix);
		$saon_description = htmlspecialchars((string)$this->saon_description);

		$street_description = htmlspecialchars((string)$this->street_description);
		$locality = htmlspecialchars((string)$this->locality);
		$town = htmlspecialchars((string)$this->town);
		$county = htmlspecialchars((string)$this->county);
		$postcode = htmlspecialchars((string)$this->postcode);

		$address_lines = implode('<br/>', $this->toLines());
		$address_lines .= $postcode?'<br/>'.$postcode:'';

		$html = <<<HEREDOC
<fieldset class="bs7666">
<legend>Sub-Dwelling (location within building/estate)</legend>
<table border="0" class="bs7666">
	<!--  SAON -->
	<tr>
		<td colspan="5" align="center"><span class="fieldLabel">Number(s)</span></td>
		<td><span class="fieldLabel">and&nbsp;/&nbsp;or</span></td>
		<td colspan="1" align="center"><span class="fieldLabel">Name</span></td>
	</tr>
	<tr>
		<td align="left"><input class="optional" type="text" name="{$this->prefix}saon_start_number" value="$saon_start_number" size="2" maxlength="4" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')" />
			<br/><span style="font-size:8pt;color:#555555;font-style:italic">start</span></td>
		<td align="left"><input class="optional" type="text" name="{$this->prefix}saon_start_suffix" value="$saon_start_suffix" size="1" maxlength="1" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')" />
			<br/><span style="font-size:8pt;color:#555555;font-style:italic">suffix</span></td>
		<td align="left" valign="middle">&nbsp;-&nbsp;</td>
		<td align="left"><input class="optional" type="text" name="{$this->prefix}saon_end_number" value="$saon_end_number" size="2" maxlength="4" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')" />
			<br/><span style="font-size:8pt;color:#555555;font-style:italic">end</span></td>
		<td align="left"><input class="optional" type="text" name="{$this->prefix}saon_end_suffix" value="$saon_end_suffix" size="1" maxlength="1" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')" />
			<br/><span style="font-size:8pt;color:#555555;font-style:italic">suffix</span></td>
		<td align="left" valign="middle" width="20"></td>
		<td align="left"><input class="optional" type="text" name="{$this->prefix}saon_description" value="$saon_description" size="30" maxlength="90" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')" placeholder="Building-name/floor/flat ..."/>
			<br/><span style="font-size:8pt;color:#555555;font-style:italic">e.g. "Engineering Block" or "Flat"</span></td>
	</tr>
</table>
</fieldset>

<fieldset class="bs7666" style="margin-top: 10px">
<legend>Dwelling (building/estate)</legend>
<table border="0" class="bs7666">

	<!--  PAON -->
	<tr>
		<td colspan="5" align="center"><span class="fieldLabel">Number(s)</span></td>
		<td><span class="fieldLabel">and&nbsp;/&nbsp;or</span></td>
		<td colspan="1" align="center"><span class="fieldLabel">Name</span></td>
	</tr>
		<tr>
		<td align="left"><input class="optional" type="text" name="{$this->prefix}paon_start_number" value="$paon_start_number" size="2" maxlength="4" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')" />
			<br/><span style="font-size:8pt;color:#555555;font-style:italic">start</span></td>
		<td align="left"><input class="optional" type="text" name="{$this->prefix}paon_start_suffix" value="$paon_start_suffix" size="1" maxlength="1" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')" />
			<br/><span style="font-size:8pt;color:#555555;font-style:italic">suffix</span></td>
		<td align="left" valign="middle">&nbsp;-&nbsp;</td>
		<td align="left"><input class="optional" type="text" name="{$this->prefix}paon_end_number" value="$paon_end_number" size="2" maxlength="4" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')" />
			<br/><span style="font-size:8pt;color:#555555;font-style:italic">end</span></td>
		<td align="left"><input class="optional" type="text" name="{$this->prefix}paon_end_suffix" value="$paon_end_suffix" size="1" maxlength="1" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')" />
			<br/><span style="font-size:8pt;color:#555555;font-style:italic">suffix</span></td>
		<td align="left" valign="middle" width="20"></td>
		<td align="left"><input class="optional" type="text" name="{$this->prefix}paon_description" value="$paon_description" size="30" maxlength="90" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')" placeholder="Building or estate name ..."/>
			<br/><span style="font-size:8pt;color:#555555;font-style:italic">e.g. "Ivy Cottage" or "Blythe Valley Park"</span></td>
	</tr>
</table>
</fieldset>

<table cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
<div class="bs7666">
	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="left"><span class="fieldLabel">Street name</span></td><td align="right"><span class="fieldLabel">e.g. High Street</span></td></tr></table>
	<input class="optional" type="text" name="{$this->prefix}street_description" value="$street_description" size="30" maxlength="35" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')">
</div>

<div class="bs7666">
	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="left"><span class="fieldLabel">Locality</span></td><td align="right"><span class="fieldLabel">e.g. Covent Garden</span></td></tr></table>
	<input class="optional" type="text" name="{$this->prefix}locality" value="$locality" size="30" maxlength="35" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')">
</div>

<div class="bs7666">
	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="left"><span class="fieldLabel">Town</span></td><td align="right"><span class="fieldLabel">e.g. London</span></td></tr></table>
	<input class="optional" type="text" name="{$this->prefix}town" value="$town" size="30" maxlength="30" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')">
</div>

<div class="bs7666">
	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td align="left"><span class="fieldLabel">County</span></td><td align="right"><span class="fieldLabel">e.g. Middlesex</span></td></tr></table>
	<input class="optional" type="text" name="{$this->prefix}county" value="$county" size="30" maxlength="30" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')">
</div>

<div class="bs7666">
	<span class="fieldLabel">Postcode</span><br/><input class="$class" type="text" name="{$this->prefix}postcode" id="postcode" value="$postcode" size="10" maxlength="10" onkeyup="document.getElementById('{$this->prefix}_envelope').update(this.form, '{$this->prefix}')">
</div>
	</td>
	<td valign="top">
		<div id="{$this->prefix}_envelope" class="envelope" style="margin-top:10px">
		$address_lines
		</div>
	</td>
</tr>
</table>
<script language="JavaScript">
var envelope = document.getElementById('{$this->prefix}_envelope');
envelope.update = function(form, prefix) {
	this.innerHTML = bs7666_to_lines(form, prefix).join('<br/>');
}
</script>
HEREDOC;

		return $html;
	}


	/**
	 * Format the address for editing
	 * @param bool $compulsory
	 * @return string HTML
	 */
	private function _formatEdit4Line($compulsory = false, $borough = false)
	{
		$class = $compulsory ? 'compulsory':'optional';
		$address_line_1 = htmlspecialchars((string)$this->address_line_1);
		$address_line_2 = htmlspecialchars((string)$this->address_line_2);
		$address_line_3 = htmlspecialchars((string)$this->address_line_3);
		$address_line_4 = htmlspecialchars((string)$this->address_line_4);
		$postcode = htmlspecialchars((string)$this->postcode);

		if($borough)
		{
			$html = <<<HTML
<div class="Address4Line">
	<div><span class="fieldLabel">Building No./Name &amp; Street</span></div>
	<input class="optional" type="text" name="{$this->prefix}address_line_1" value="$address_line_1" size="40" maxlength="100">
</div>
<div class="Address4Line">
	<div><span class="fieldLabel">Suburb / Village</span></div>
	<input class="optional" type="text" name="{$this->prefix}address_line_2" value="$address_line_2" size="40" maxlength="100">
</div>
<div class="Address4Line">
	<div><span class="fieldLabel">Town / City</span></div>
	<input class="optional" type="text" name="{$this->prefix}address_line_3" value="$address_line_3" size="40" maxlength="100">
	<span class="button" onclick="fillBorough();">Fill</span>
</div>
<div class="Address4Line">
	<div><span class="fieldLabel">County</span></div>
	<input class="optional" type="text" name="{$this->prefix}address_line_4" value="$address_line_4" size="40" maxlength="100">
</div>
<div class="Address4Line">
	<span class="fieldLabel">Postcode</span><br/>
	<input class="$class" type="text" id="{$this->prefix}postcode" name="{$this->prefix}postcode" value="$postcode" size="10" maxlength="10">
</div>
HTML;
		}
		else
		{
			$html = <<<HTML
<div class="Address4Line">
	<div><span class="fieldLabel">Building No./Name &amp; Street</span></div>
	<input class="optional" type="text" name="{$this->prefix}address_line_1" value="$address_line_1" size="40" maxlength="100">
</div>
<div class="Address4Line">
	<div><span class="fieldLabel">Suburb / Village</span></div>
	<input class="optional" type="text" name="{$this->prefix}address_line_2" value="$address_line_2" size="40" maxlength="100">
</div>
<div class="Address4Line">
	<div><span class="fieldLabel">Town / City</span></div>
	<input class="optional" type="text" name="{$this->prefix}address_line_3" value="$address_line_3" size="40" maxlength="100">
</div>
<div class="Address4Line">
	<div><span class="fieldLabel">County</span></div>
	<input class="optional" type="text" name="{$this->prefix}address_line_4" value="$address_line_4" size="40" maxlength="100">
</div>
<div class="Address4Line">
	<span class="fieldLabel">Postcode</span><br/>
	<input class="$class" type="text" id="{$this->prefix}postcode" name="{$this->prefix}postcode" value="$postcode" size="10" maxlength="10">
</div>
HTML;

		}
		
		return $html;
	}


	/**
	 * Format the address for reading
	 * @return string HTML
	 */
	public function formatRead()
	{
		if ($this->_isBs7666Address) {
			return $this->_formatReadBs7666();
		} else if ($this->_is4LineAddress) {
			return $this->_formatRead4Line();
		} else {
			return '';
		}
	}


	/**
	 * Format the address for reading
	 * @return string HTML
	 */
	private function _formatReadBs7666()
	{
		$addr = htmlspecialchars(implode("\n", $this->toLines()));

		// Append postcode as a link to Google Maps
		if ($this->postcode != '') {
			if (strlen($addr) > 0) {
				$addr .= '<br/>';
			}
			$addr .= '<a href="http://maps.google.co.uk/maps?f=q&hl=en&q=' . urlencode($this->postcode) . '" target="_blank">'
				. htmlspecialchars((string)$this->postcode) . '</a>';
		}

		return nl2br($addr);
	}


	/**
	 * Format the address for reading
	 * @return string HTML
	 */
	private function _formatRead4Line()
	{
		$array = $this->toLines();
		$lines = array();
		foreach ($array as $value) {
			if ($value) {
				$lines[] = $value;
			}
		}
		$addr = htmlspecialchars(implode("\n", $lines));

		// Append postcode as a link to Google Maps
		if ($this->postcode != '') {
			if (strlen($addr) > 0) {
				$addr .= '<br/>';
			}
			$addr .= '<a href="http://maps.google.co.uk/maps?f=q&hl=en&q=' . urlencode($this->postcode) . '" target="_blank">'
				. htmlspecialchars((string)$this->postcode) . '</a>';
		}

		return nl2br($addr);
	}

	/**
	 * Converts Address object into an array of lines (does not include postcode).
	 * No restriction is placed on the length of the returned array,
	 * which may contain up to 6 elements.
	 * @return array
	 */
	public function toLines()
	{
		if ($this->_isBs7666Address) {
			return $this->_toLinesBs7666();
		} else if ($this->_is4LineAddress) {
			return $this->_toLines4Line();
		} else {
			return array();
		}
	}

	/**
	 * Converts a 4-line Address object into an array of address lines (does not include postcode)
	 * @return array
	 */
	private function _toLines4Line()
	{
		return array($this->address_line_1, $this->address_line_2, $this->address_line_3, $this->address_line_4);
	}


	/**
	 * Converts a BS7666 Address object into an array of address lines (does not include postcode)
	 * @return array
	 */
	private function _toLinesBs7666()
	{
		$lines = array();

		if($this->saon_start_number != ''
			|| $this->saon_start_suffix != ''
			|| $this->saon_end_number != ''
			|| $this->saon_end_suffix != ''
			|| $this->saon_description != ''
		) {
			$line = '';
			if ($this->saon_start_number != '' || $this->saon_start_suffix != '') {
				$line .= $this->saon_start_number . $this->saon_start_suffix;
			}
			if ($this->saon_end_number != '' || $this->saon_end_suffix != '') {
				$line .= ' - ' . $this->saon_end_number . $this->saon_end_suffix;
			}
			if ($this->saon_description != '') {
				$line .= ' ' . $this->saon_description;
			}

			$lines[] = trim($line);
		}


		if ($this->paon_description != '') {
			$lines[] = $this->paon_description;
		}

		if($this->paon_start_number != ''
			|| $this->paon_start_suffix != ''
			|| $this->paon_end_number != ''
			|| $this->paon_end_suffix != ''
		) {
			$line = '';
			if ($this->paon_start_number != '' || $this->paon_start_suffix != '') {
				$line .= $this->paon_start_number . $this->paon_start_suffix;
			}
			if ($this->paon_end_number != '' || $this->paon_end_suffix != '') {
				$line .= ' - ' . $this->paon_end_number . $this->paon_end_suffix;
			}

			$line .= ' '.$this->street_description;

			$lines[] = trim($line);
		} else if($this->street_description != '') {
			$lines[] = $this->street_description;
		}

		if ($this->locality) {
			$lines[] = $this->locality;
		}
		if ($this->town) {
			$lines[] = $this->town;
		}
		if ($this->county) {
			$lines[] = $this->county;
		}

		$lines = array_unique($lines);
		return $lines;
	}


	/**
	 * Returns a four-line address (does not include postcode).
	 * @return array Fixed-size array (4 elements)
	 */
	public function to4Lines()
	{
		if ($this->_isBs7666Address) {
			return $this->_to4LinesBs7666();
		} else if ($this->_is4LineAddress) {
			return $this->_to4Lines4Line();
		} else {
			return array('', '', '', '');
		}
	}


	/**
	 * @return array
	 */
	private function _to4Lines4Line()
	{
		return array($this->address_line_1, $this->address_line_2, $this->address_line_3, $this->address_line_4);
	}


	/**
	 * Compresses SAON and PAON onto one line and omits the county if necessary.
	 * In a sane world we would probably omit the locality to reduce the number of lines
	 * instead of the county, but the quality of data entry in Sunesis is so poor that
	 * locality often contains the PAON or the street description. County is the safest
	 * field to omit.
	 * @return array
	 */
	private function _to4LinesBs7666()
	{
		$this->_trimAddressFields();

		// SAON (combine number and description)
		$saon = '';
		if ($this->saon_start_number || $this->saon_start_suffix) {
			$saon .= $this->saon_start_number . $this->saon_start_suffix;
		}
		if ($this->saon_end_number || $this->saon_end_suffix) {
			if (strlen($saon) > 0) {
				$saon .= '-';
			}
			$saon .= $this->saon_end_number . $this->saon_end_suffix;
		}
		if($this->saon_description) {
			if (strlen($saon) > 0) {
				$saon .= ' ';
			}
			$saon .= $this->saon_description;
		}

		// PAON (split into number and description)
		$paon_number = '';
		if ($this->paon_start_number || $this->paon_start_suffix) {
			$paon_number .= $this->paon_start_number . $this->paon_start_suffix;
		}
		if ($this->paon_end_number || $this->paon_end_suffix) {
			if (strlen($paon_number) > 0) {
				$paon_number .= '-';
			}
			$paon_number .= $this->paon_end_number . $this->paon_end_suffix;
		}
		$paon_desc = $this->paon_description;

		// Clear $paon_number if the street description also has the street number (very common in Sunesis)
		if ($paon_number) {
			$regex = '/^' . preg_quote($this->paon_start_number, '/') . '\s*' . preg_quote($this->paon_start_suffix, '/') . '\s*[\-\/]?\s*'
				. preg_quote($this->paon_end_number, '/') . '\s*' . preg_quote($this->paon_end_suffix, '/') . '/i';
			//echo $regex . "\r\n";
			if (preg_match($regex, $this->street_description)) {
				$paon_number = '';
			}
		}


		$street = $paon_number;
		if($this->street_description) {
			if ($paon_desc && strpos($this->street_description, $paon_desc) === 0) {
				$street .= ' ' . trim(str_replace($paon_desc, '', $this->street_description), ', '); // Remove paon_desc from street_description
			} else {
				$street .= ' ' . $this->street_description;
			}
		}

		$lines = array();
		if ($saon || $paon_desc) {
			$line = $saon . ', ' . $paon_desc;
			$lines[] = Text::strtoproper(trim($line, ', '));
		}
		if ($street) {
			$lines[] = Text::strtoproper(trim($street, ', '));
		}
		if ($this->locality) {
			$lines[] = Text::strtoproper($this->locality);
		}
		if ($this->town) {
			$lines[] = Text::strtoproper($this->town);
		}
		if ($this->county) {
			$lines[] = Text::strtoproper($this->county);
		}

		// Remove duplicated rows (cannot use array_unique() - it preserves indexes)
		$temp = array();
		foreach ($lines as $line) {
			if (!in_array($line, $temp)) {
				$temp[] = $line;
			}
		}
		$lines = $temp;

		// Trim large array (starting with the last line, usually the county)
		while (count($lines) > 4) {
			array_pop($lines);
		}

		// Pad small array with empty strings
		while (count($lines) < 4) {
			array_push($lines, '');
		}

		// Remove duplicate spaces
		foreach ($lines as &$line) {
			$line = preg_replace('/[ ]{2,}/', ' ', $line);
		}

		return $lines;
	}


	/**
	 * Trim all address fields. With regard to BS7666 fields, we are very
	 * limited in what we can trim. We cannot remove non-numeric data from
	 * numeric fields, because users have often used these fields for essential
	 * non-numeric data (e.g. 'Flat', 'Appt' ..)
	 */
	private function _trimAddressFields()
	{
		// Trim every field and remove zero values
		foreach($this as &$value) {
			$value = trim($value ?: '', ' ".,&-;/');
			if ($value === '0') {
				$value = '';
			}
		}
	}

	/**
	 * @return string
	 */
	public function toXML()
	{
		if ($this->_isBs7666Address) {
			return $this->_toXmlBs7666();
		} else if ($this->_is4LineAddress) {
			return $this->_toXml4Line();
		} else {
			return '';
		}
	}


	/**
	 * @return string
	 */
	private function _toXmlBs7666()
	{
		return '<address>'
			. '<saon_start_number>'.htmlspecialchars((string)$this->saon_start_number).'</saon_start_number>'
			. '<saon_start_suffix>'.htmlspecialchars((string)$this->saon_start_suffix).'</saon_start_suffix>'
			. '<saon_end_number>'.htmlspecialchars((string)$this->saon_end_number).'</saon_end_number>'
			. '<saon_end_suffix>'.htmlspecialchars((string)$this->saon_end_suffix).'</saon_end_suffix>'
			. '<saon_description>'.htmlspecialchars((string)$this->saon_description).'</saon_description>'
			. '<paon_start_number>'.htmlspecialchars((string)$this->paon_start_number).'</paon_start_number>'
			. '<paon_start_suffix>'.htmlspecialchars((string)$this->paon_start_suffix).'</paon_start_suffix>'
			. '<paon_end_number>'.htmlspecialchars((string)$this->paon_end_number).'</paon_end_number>'
			. '<paon_end_suffix>'.htmlspecialchars((string)$this->paon_end_suffix).'</paon_end_suffix>'
			. '<paon_description>'.htmlspecialchars((string)$this->paon_description).'</paon_description>'
			. '<street_description>'.htmlspecialchars((string)$this->street_description).'</street_description>'
			. '<locality>'.htmlspecialchars((string)$this->locality).'</locality>'
			. '<town>'.htmlspecialchars((string)$this->town).'</town>'
			. '<county>'.htmlspecialchars((string)$this->county).'</county>'
			. '<postcode>'.htmlspecialchars((string)$this->postcode).'</postcode>'
			. '</address>';
	}

	/**
	 * @return string
	 */
	private function _toXml4Line()
	{
		return '<address>'
			. '<address_line_1>' . htmlspecialchars((string)$this->address_line_1) . '</address_line_1>'
			. '<address_line_2>' . htmlspecialchars((string)$this->address_line_2) . '</address_line_2>'
			. '<address_line_3>' . htmlspecialchars((string)$this->address_line_3) . '</address_line_3>'
			. '<address_line_4>' . htmlspecialchars((string)$this->address_line_4) . '</address_line_4>'
			. '<postcode>' . htmlspecialchars((string)$this->postcode) . '</postcode>'
			. '</address>';
	}


	public function __toString()
	{
		return $this->toXML();
	}


	public $paon_start_number = '';
	public $paon_start_suffix = '';
	public $paon_end_number = '';
	public $paon_end_suffix = '';
	public $paon_description = '';

	public $saon_start_number = '';
	public $saon_start_suffix = '';
	public $saon_end_number = '';
	public $saon_end_suffix = '';
	public $saon_description = '';

	public $street_description = '';
	public $locality = '';
	public $town = '';
	public $county = '';
	public $postcode = '';

	public $address_line_1 = '';
	public $address_line_2 = '';
	public $address_line_3 = '';
	public $address_line_4 = '';

	private $prefix = '';
	private $_is4LineAddress = false;
	private $_isBs7666Address = false;
}
?>
