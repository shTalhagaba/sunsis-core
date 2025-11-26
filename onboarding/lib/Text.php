<?php
class Text
{
	public static function softBreak($text, $width = 20)
	{
		return preg_replace("/([^\s\r\n]{{$width}})/", '\1&#8203;', $text);
	}

	public static function utf8_to_latin1($str)
	{
		// Change smart quotes, em/en dashes and elipsis into latin 1 equivalents
		$str = str_replace(array(
			chr(0xe2) . chr(0x80) . chr(0x98),
			chr(0xe2) . chr(0x80) . chr(0x99),
			chr(0xe2) . chr(0x80) . chr(0x9c),
			chr(0xe2) . chr(0x80) . chr(0x9d),
			chr(0xe2) . chr(0x80) . chr(0x93),
			chr(0xe2) . chr(0x80) . chr(0x94),
			chr(0xe2) . chr(0x80) . chr(0xa6),
			chr(226) . chr(128) . chr(162),
			chr(226) . chr(128). chr(152),
			chr(0x91),
			chr(0x92),
			chr(0x93),
			chr(0x94),
			chr(0x95),
			chr(0xA3),
			chr(226) . chr(128). chr(153)), array(
			'\'',
			'\'',
			'"',
			'"',
			'-',
			'-',
			'...',
			'* ',
			'\'',
			'\'',
			'\'',
			'"',
			'"',
			'-',
			'&pound;',
			'\''), $str);

		// Convert all remaining UTF-8 characters into Latin-1
		$str = mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8');

		// Replace the latin1 bullet point with a wiki compatible asterisk and space
		$str = preg_replace('/\xB7/', '* ', $str);

		$str = preg_replace('/\x96/', '-', $str);

		$str = preg_replace('/\xE9/', 'e', $str);

		// Silently remove all control characters except for CR, LF and TAB
		$str = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $str);

		return $str;
	}

	public static function remove_html_markup($str)
	{
		$str = str_ireplace(array('<br>',
			'<br/>',
			'<br />',
			'</p>',
			'</P>',
			'</li>',
			'</ul>',
			'</ol>',
			'</address>',
			'</blockquote>',
			'</center>',
			'</div>',
			'</pre>',
			'</h1>',
			'</h2>',
			'</h3>',
			'</h4>',
			'</h5>',
			'</h6>',
			'</h7>',
			'<hr>',
			'<hr/>',
			'<hr />',
			'</tr>',
			'</table>'), "\n", $str); // Convert <br/> etc. into \n (x0A)
		//$str = str_ireplace('<li>', chr(183).' ', $str); // All list items become latin 1 bullet points
		$str = str_ireplace('<li>', '* ', $str); // All list elements become asterisks
		$str = preg_replace('/[\n\r]+/', "\n", $str); // Replace all newline sequences with a single \n
		$str = strip_tags($str); // Remove HTML tags

		// Decode common HTML named entities
		$str = html_entity_decode($str); // Decode HTML character encoding
		$str = str_replace(chr(160), ' ', $str); // Replace hard spaces with soft spaces (char(160) == &nbsp;)

		// Decode numeric HTML entities (hex and dec) in the ISO-8859-1 (Latin 1) range
		$callback_hex = create_function('$matches',
			'return ((hexdec($matches[1]) < 128) || ((hexdec($matches[1]) >= 160) && (hexdec($matches[1] <= 255)))) ? chr(hexdec($matches[1])) : "?";');
		$callback_dec = create_function('$matches',
			'return (($matches[1] < 128) || (($matches[1] >= 160) && ($matches[1] <= 255))) ? chr($matches[1]) : "?";');
		$str = preg_replace_callback('/&#x([0-9a-f]+);/i', $callback_hex, $str);
		$str = preg_replace_callback('/&#([0-9]+);/', $callback_dec, $str);

		// Silently remove all control characters except for CR (x0A) aka \n
		$str = preg_replace('/[\x00-\x09\x0B-\x1F]/', '', $str);

		return $str;
	}


	/**
	 * The challenge is *not* to convert windows 1252 characters that could
	 * be part of a UTF-8 encoding sequence.
	 *
	 * @param string $str
	 * @return string
	 */
	public static function windows_1252_to_ascii($str)
	{
		$str = preg_replace(array("/(?<![\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF][\x80-\xBF])\x85/",
			"/(?<![\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF][\x80-\xBF])\x8C/",
			"/(?<![\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF][\x80-\xBF])\x91/",
			"/(?<![\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF][\x80-\xBF])\x92/",
			"/(?<![\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF][\x80-\xBF])\x93/",
			"/(?<![\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF][\x80-\xBF])\x94/",
			"/(?<![\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF][\x80-\xBF])\x95/",
			"/(?<![\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF][\x80-\xBF])\x96/",
			"/(?<![\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF][\x80-\xBF])\x97/",
			"/(?<![\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF][\x80-\xBF])\x9C/"), array('...',
			'OE',
			'\'',
			'\'',
			'"',
			'"',
			'*',
			'-',
			'-',
			'oe'), $str);

		return $str;
	}

	/**
	 * Custom function to fill the gap in PHP's range
	 * of case manipulation functions strtolower(), strtoupper(), ...
	 * @param string $str
	 * @return string
	 */
	public static function strtoproper($str)
	{
		$str = trim($str);
		//$str = preg_replace('/\b([A-Za-z])/e', 'strtoupper("\\1")', $str);
		$str = preg_replace_callback(
			'/\b([A-Za-z])/',
			function($matches){
				foreach($matches as $match){
					return strtoupper($match);
				}
			},
			$str
		);
		//$str = preg_replace('/(?<!\b)([A-Za-z])/e', 'strtolower("\\1")', $str);
		$str = preg_replace_callback(
			'/(?<!\b)([A-Za-z])/',
			function($matches){
				foreach($matches as $match){
					return strtolower($match);
				}
			},
			$str
		);
		//$str = preg_replace('/\bMac([A-Za-z])/e', '"Mac".strtoupper("\\1")', $str);
		$str = preg_replace_callback(
			'/\bMac([A-Za-z])/',
			function($matches){
				foreach($matches as $match){
					return "Mac".strtoupper($match);
				}
			},
			$str
		);
		//$str = preg_replace('/\bMc([A-Za-z])/e', '"Mc".strtoupper("\\1")', $str);
		$str = preg_replace_callback(
			'/\bMc([A-Za-z])/',
			function($matches){
				foreach($matches as $match){
					return "Mc".strtoupper($match);
				}
			},
			$str
		);
		$str = preg_replace(array('/\bla\b/i', '/\ble\b/i', '/\bde\b/i'), array('la', 'le', 'de'), $str);
		return $str;
	}

	/**
	 * @static
	 * @param string $str
	 * @return string
	 */
	public static function initialise($str)
	{
		if(preg_match_all("/\b[A-Za-z]/", $str, $matches))
		{
			return implode(' ', $matches[0]);
		}
	}

	public static function abbreviate($str, $max_width, $elipsis = "")
	{
		if(strlen(trim($str)) <= $max_width){
			return trim($str);
		}

		if($max_width < 3){
			throw new Exception("Illegal value for maxWidth");
		}

		return substr(trim($str), 0, $max_width - strlen($elipsis)).$elipsis;
	}

	/**
	 * Not a perfect fit for this class, but better here than elsewhere
	 * @param unknown_type $str
	 */
	public static function formatUserAgent($str)
	{
		if(preg_match('#Firefox/([0-9]+(\.[0-9]+)?)#', $str, $matches))
		{
			return "Firefox " . $matches[1];
		}
		else if(preg_match('#chromeframe/([0-9]+)#', $str, $matches))
		{
			return "ChromeFrame ".$matches[1];
		}
		else if(preg_match('#MSIE ([0-9]+\.[0-9]+)#', $str, $matches))
		{
			return "IE ".$matches[1];
		}
		else if(preg_match('#Chrome/([0-9]+(\.[0-9]+)?)#', $str, $matches))
		{
			return "Chrome ".$matches[1];
		}
		else if(preg_match('#Opera/([0-9]+(\.[0-9]+)?)#', $str, $matches))
		{
			return "Opera ".$matches[1];
		}
		else if(strpos($str, "iPhone") == true
			&& preg_match('#(?:Version/|OS )([0-9]+([\._][0-9]+)+)#', $str, $matches))
		{
			return "iPhone Safari ".$matches[1];
		}
		else if(strpos($str, "iPad") == true
			&& preg_match('#(?:Version/|OS )([0-9]+([\._][0-9]+)+)#', $str, $matches))
		{
			return "iPad Safari ".$matches[1];
		}
		else if(strpos($str, "iPod") == true
			&& preg_match('#(?:Version/|OS )([0-9]+([\._][0-9]+)+)#', $str, $matches))
		{
			return "iPod Safari ".$matches[1];
		}
		else if(strpos($str, "Safari") == true && strpos($str, "Windows") == true
			&& preg_match('#(?:Version/|OS )([0-9]+([\._][0-9]+)+)#', $str, $matches))
		{
			return "Win Safari ".$matches[1];
		}
		else if(strpos($str, "Safari") == true && strpos($str, "Macintosh") == true
			&& preg_match('#(?:Version/|OS )([0-9]+([\._][0-9]+)+)#', $str, $matches))
		{
			return "Mac Safari ".$matches[1];
		}
		else
		{
			return $str;
		}
	}

	/**
	 * json_encode() expects strings to be in UTF-8.
	 * CLM uses Latin1
	 *
	 */
	public static function json_encode_latin1($var)
	{
		$clone = Text::clone_object($var);
		Text::utf8_encode($var);
		return json_encode($var);
	}

	/**
	 * json_decode() expects strings to be in UTF-8.
	 * CLM uses Latin1
	 *
	 */
	public static function json_decode_latin1($str)
	{
		$str = utf8_encode($str);
		$json = json_decode($str);
		Text::utf8_decode($json);
		return $json;
	}

	/**
	 * Helper function for json_encode_latin1()
	 * @param $var
	 * @return unknown_type
	 */
	private static function utf8_encode(&$var)
	{
		if(is_array($var) || is_object($var))
		{
			foreach($var as &$v)
			{
				if(is_string($v))
				{
					$v = utf8_encode($v);
				}

				if(is_array($v) || is_object($v))
				{
					Text::utf8_encode($v);
				}
			}
		}
		else if(is_string($var))
		{
			$var = utf8_encode($var);
		}
	}

	/**
	 * Helper function for json_decode_latin1()
	 * @param $var
	 * @return unknown_type
	 */
	private static function utf8_decode(&$var)
	{
		if(is_array($var) || is_object($var))
		{
			foreach($var as &$v)
			{
				if(is_string($v))
				{
					$v = utf8_decode($v);
				}

				if(is_array($v) || is_object($v))
				{
					Text::utf8_decode($v);
				}
			}
		}
		else if(is_string($var))
		{
			$var = utf8_decode($var);
		}
	}

	/**
	 * Helper function for json_encode_latin1()
	 * @param $var
	 * @return unknown_type
	 */
	private static function clone_object($var)
	{
		if(is_object($var))
		{
			$var = clone $var;
		}

		if(is_object($var) || is_array($var))
		{
			foreach($var as &$v)
			{
				$v = Text::clone_object($v);
			}
		}

		return $var;
	}
}
?>