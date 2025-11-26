<?php
/**
 * Reads delimited files. Iterate over the instantiated class using foreach
 * to retrieve the values.
 * @author iss
 */
class CsvFileReader implements Iterator
{
	/** @var string */
	private $_path;

	/** @var resource */
	private $_fh;

	/** @var string */
	private $_delimiter;

	/** @var string */
	private $_regex;

	/** @var string */
	private $_currentLine;

	/** @var int */
	private $_currentIndex = 0;

	/**
	 * @param string $path Path to file
	 * @param string $delimiter Defaults to comma
	 */
	public function __construct($path, $delimiter = ',')
	{
		$this->open($path, $delimiter);
	}

	/**
	 * Open a new delimited file. Automatically closes previously opened file.
	 * @param string $path Path to file
	 * @param string $delimiter Defaults to comma
	 * @throws InvalidArgumentException
	 */
	public function open($path, $delimiter = ',')
	{
		if(empty($path)){
			throw new InvalidArgumentException("Missing argument, \$path");
		}
		if(!file_exists($path)){
			throw new InvalidArgumentException("Argument \$path does not exist, $path");
		}
		if(!is_file($path)){
			throw new InvalidArgumentException("Argument \$path is not a file, $path");
		}

		$this->_path = $path;
		$this->_delimiter = $delimiter;
		$this->_regex = '/' . $this->_delimiter . '(?=([^"]*"[^"]*")*(?![^"]*"))/';

		$this->close();
		$this->_fh = fopen($path, 'r');
		$this->_readLine(); // Read the first line (without incrementing currentIndex)
	}

	/**
	 * Close file
	 */
	public function close()
	{
		if(!empty($this->_fh)){
			@fclose($this->_fh);
		}
		$this->_fh = null;
	}

	public function __destruct()
	{
		$this->close();
	}

	public function rewind()
	{
		rewind($this->_fh);
		$this->_currentLine = null;
		$this->_currentIndex = 0;
		$this->_readLine(); // Read the first line (without incrementing currentIndex)
	}

	/**
	 * Iterator interface
	 * @return array|null
	 */
	public function current()
	{
		return $this->_tokenize($this->_currentLine);
	}

	/**
	 * Iterator interface
	 * @return int
	 */
	public function key()
	{
		return $this->_currentIndex;
	}

	/**
	 * Iterator interface
	 */
	public function next()
	{
		if($this->_readLine()) {
			$this->_currentIndex++;
		}
	}

	/**
	 * Iterator interface
	 * @return bool
	 */
	public function valid()
	{
		return !is_null($this->_currentLine);
	}

	/**
	 * Supports CRLF in fields
	 * @return bool
	 */
	private function _readLine()
	{
		// Check for End Of File
		if(feof($this->_fh)) {
			$this->_currentLine = null;
			return FALSE;
		}

		// Read lines from file until an even number of quotes are counted
		$quoteCount = 0;
		$this->_currentLine = null;
		do {
			$line = fgets($this->_fh);
			if($line === FALSE) {
				break; // Don't return false (we could have been reading a multi-line row)
			}

			// Count quotes. The count must be even to leave the loop.
			// This works because quotes within values are escaped with
			// another quote, so quotes within values always come in an
			// even number and thus do not affect detection of unterminated
			// values by looking for an uneven number of quotes.
			$quoteCount += substr_count($line, '"');
			$oddNumberOfQuotes = ($quoteCount % 2) > 0;

			// Trim line
			if(!$oddNumberOfQuotes) {
				// EVEN: end of row detected
				$line = trim($line, " \r\n"); // Remove CRLF and trailing spaces
			} else {
				// ODD: unterminated value - there will be more lines to follow
				$line = str_replace(array("\r", "\n"), ' ', $line); // Replace CRLF with a space
			}

			// Append to current line
			$this->_currentLine .= $line;
		} while($oddNumberOfQuotes); // end on an even number of quotes

		return TRUE;
	}

	/**
	 * @param string $line
	 * @return array|null
	 */
	private function _tokenize($line)
	{
		if(is_null($line)){
			return null;
		}
		if(empty($line)){
			return array();
		}

		$values = preg_split($this->_regex, $line);
		foreach($values as &$v) {
			$v = trim($v, "\" \r\n");
			$v = str_replace('""', '"', $v);
		}

		return $values;
	}
}