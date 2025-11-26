<?php
class SQLStatement
{
	public function __construct($statement)
	{
		if($statement instanceof SQLStatement)
		{
			foreach($statement->clauses as $clauseName=>$clauseValue)
			{
				$this->clauses[$clauseName] = $clauseValue;
			}
		}
		else
		{
			// Presume this is a SQL string
			$this->parseSQL($statement);
		}
	}
	
	

	
	public function getClause($clause)
	{
		$clause = strtolower($clause);
		
		if(array_key_exists($clause, $this->clauses))
		{
			return $this->clauses[strtolower($clause)];
		}
		else
		{
			return '';
		}
	}
	
	
	public function setClause($clause, $overwrite = false)
	{
		$clause = trim($clause);
		if(preg_match($this->reg_keywords, $clause, $matches))
		{
			$keyword = strtolower($matches[1]);
			$clause = trim(substr($clause, strlen($keyword))); // strip keyword
		}
		else
		{
			throw new Exception("Missing or unrecognised SQL keyword in argument \$clause");
		}
		
		// If after removing the SQL keyword we are left with an empty string...
		if(strlen($clause) == 0)
		{
			// Clause has no content.  Delete it from this statement.
			if(array_key_exists($keyword, $this->clauses))
			{
				unset($this->clauses[$keyword]);
			}
			return;
		}
		
		switch($keyword)
		{
			case 'select':
			case 'from':
			case 'group by':
			case 'order by':
			case 'limit':
				// Always overwrite existing clause
				$this->clauses[$keyword] = strtoupper($keyword) . ' ' . $clause;
				break;
			
			case 'where':
			case 'having':
				if($overwrite)
				{
					$this->clauses[$keyword] = strtoupper($keyword) . ' ' . $clause;
				}
				else
				{
					if($this->hasClause($keyword))
					{
						$this->clauses[$keyword] .= ' AND ';
					}
					else
					{
						$this->clauses[$keyword] = strtoupper($keyword) . ' ';
					}
					$this->clauses[$keyword] .= '(' . $clause . ')';					
				}
				break;
				
			default:
				throw new Exception("Unrecognised SQL keyword");
				break;
		}
	}
	
	
	/**
	 * Explicit function for removing a clause from a SQL statement.
	 *
	 * @param string $keyword SQL keyword
	 */ 
	public function removeClause($keyword)
	{
		// Remove the clause for this keyword (if present)
		$keyword = strtolower($keyword);
		if(array_key_exists($keyword, $this->clauses))
		{
			unset($this->clauses[$keyword]);
		}
	}
	
	
	public function hasClause($clause)
	{
		return array_key_exists(strtolower($clause), $this->clauses);
	}
	
	
	public function appendStatement(SQLStatement $s)
	{
		if(!is_null($s))
		{
			foreach($s->clauses as $clauseName=>$clauseValue)
			{
				$this->setClause($clauseValue);
			}
		}
	}
	
	
	/**
	 * This method replaces MySQL date-time constants with
	 * date-time literals. This allows a SQL statement to be
	 * cached in MySQL's query cache.
	 *
	 */
	public function replaceDateTimeConstants()
	{
		$patterns = array(
			"/CURRENT_DATE\(\)|CURRENT_DATE|CURDATE\(\)/",
			"/CURRENT_TIME\(\)|CURRENT_TIME|CURTIME\(\)/",
			"/TIMESTAMP\(\)|TIMESTAMP|NOW\(\)/");
		$replacements = array(
			date('\'Y-m-d\''),
			date('\'H:i:s\''),
			date('\'Y-m-d H:i:s\''));
		
		$this->clauses = preg_replace($patterns, $replacements, $this->clauses);
	}
	
	
	public function __toString()
	{
		$sql = $this->getClause('select') . "\r\n"
			. $this->getClause('from') . "\r\n"
			. $this->getClause('where') . "\r\n"
			. $this->getClause('group by') . "\r\n"
			. $this->getClause('having') . "\r\n"
			. $this->getClause('order by') . "\r\n"
			. $this->getClause('limit');
		
		return trim($sql);
	}
	
	
	private function parseSQL($sql)
	{
		// Trim and add a terminating ';' if necessary
		$sql = trim($sql);
		if($sql[strlen($sql) - 1] != ';'){
			$sql .= ';';
		}
		
		$max = strlen($sql);
		
		$current_keyword = null;
		
		$i = 0;
		$start = 0;
		for($i = 0; $i < $max; $i++)
		{
			switch($sql[$i])
			{
				case '(':
					// Zoom to closing bracket
					$level = 1;
					// #180 - {0000000190} - incrementation causing error (relmes)
					// replicated to match CLM.
					while($level > 0 && ++$i < $max)
					{
						switch($sql[$i])
						{
							case '(':
								$level++;
								break;
							case ')':
								$level--;
								break;
							case "'":
								// Zoom to closing quotation mark
								// #180 - {0000000190} - incrementation causing error (relmes)
								$i++;
								while($i < $max && $sql[$i] != "'")
								{
									if($sql[$i] == "\\")
									{
										$i += 2; // Skip two characters for escape sequences
									}
									else
									{
										$i += 1;
									}
								}
								if($i >= $max)
								{
									throw new Exception("SQL parsing error: unterminated string literal");
								}
								break;
							default:
								break;
						}
					}
					if($level > 0)
					{
						throw new Exception("SQL parsing error: Unterminated parenthesis. Character $i in SQL: " . $sql);
					}
					break;
				
				case "'":
					// Zoom to closing quotation mark
					$i++;
					while($i < $max && $sql[$i] != "'")
					{
						if($sql[$i] == "\\")
						{
							$i += 2; // Skip two characters for escape sequences
						}
						else
						{
							$i += 1;
						}
					}
					if($i >= $max)
					{
						throw new Exception("SQL parsing error: unterminated string literal");
					}
					break;
				
				case "`":
					// Zoom to closing back tick
					do
					{
						$i++;
					} while($i < $max && $sql[$i] != "`");
					if($i >= $max)
					{
						throw new Exception("SQL parsing error: unterminated backtick literal");
					}					
					break;
					
				case ';':
					// Statement end
					$this->clauses[$current_keyword] = substr($sql, $start, $i - $start);
					$start = $i;
					break 2; // Leave switch, leave loop
					
				default:
					// If the character is a letter, identify the word...
					if($this->is_char($sql[$i]))
					{
						// If the letter is the beginning of a recognised SQL keyword...
						if(preg_match($this->reg_keywords, substr($sql, $i), $matches) > 0)
						{
							// If this is not the first clause to be parsed in this iteration...
							if(!is_null($current_keyword))
							{
								// Save current clause
								$this->clauses[$current_keyword] = substr($sql, $start, $i - $start);								
							}
							
							// Record keyword name
							$current_keyword = strtolower($matches[1]);
								
							// Mark start of new clause
							$start = $i;
								
							// Fast forward to end of keyword
							$i += (strlen($current_keyword) - 1);
						}
						else
						{
							// Fast forward to the end of the word
							do
							{
								$i++;
							} while($this->is_char($sql[$i]));
							$i--; // reverse one to position on final letter of word
						}
					}

					break;
					
			} // end switch
		} // end character iteration
		
	}
	
	
	private function is_char($char)
	{
		$ascii = ord($char);
		return (($ascii >= 65) && ($ascii <= 90)) || (($ascii >= 97) && ($ascii <= 122));
	}
	
	
	private $reg_keywords = '/^(SELECT|FROM|WHERE|GROUP BY|HAVING|ORDER BY|LIMIT)[^A-Z]/i';
	private $clauses = array();
}
?>