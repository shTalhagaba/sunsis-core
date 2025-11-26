<?php
class ValueObject
{
	public function __construct()
	{
		// Placeholder - do not remove
	}
	
	/**
	 * Populates a value object's properties with the corresponding
	 * values from an associative array or object.  Value object property names
	 * that are not found in the associative array are silently ignored.
	 * 
	 * @param mixed $map An associative array or an object
	 * 
	 * @param boolean $empty_string_is_null Set this to true if selectively
	 * updating fields rather than updating the entire record. NULL values are
	 * not written to the database, but empty strings are (they are written as NULL
	 * so as to be compatible with all datatypes).
	 */
	public function populate($map, $empty_string_is_null = false)
	{
		$class = new ReflectionClass(get_class($this));
		$properties = $class->getProperties();

		if(is_array($map))
		{
			foreach($properties as $property)
			{
				$propName = $property->getName();
				if( array_key_exists($propName, $map) )
				{
					if( ($map[$propName] === '') && $empty_string_is_null )
					{
						$property->setValue($this, NULL);
					}
					else
					{
						$property->setValue($this, $map[$propName]);
					}
				}
			}
		}
		elseif(is_object($map))
		{
			foreach($properties as $property)
			{
				$propName = $property->getName();
				if( array_key_exists($propName, $map) )
				{
					if( ($map->$propName === '') && $empty_string_is_null )
					{
						$property->setValue($this, NULL);
					}
					else
					{
						$property->setValue($this, $map->$propName);
					}
				}
			}			
		}
		else
		{
			throw new Exception("Argument \$map must be an array or an object");
		}
	}

	/**
	 * Helper method.  Takes a value object and produces a SQL compatible
	 * list of name-value pairs e.g.
	 * firstname='Fred', surname='Bloggs', age=42
	 * 
	 * @param array $exclude An array of property names to exclude from this method's output
	 */
	public function toNameValuePairs(array $exclude = array())
	{
		$class = new ReflectionClass(get_class($this));
		$properties = $class->getProperties();

		$name = '';
		$value = '';
		$numProperties = count($properties);
		$sql = '';
		for($i = 0; $i < $numProperties; $i++)
		{
			$name = $properties[$i]->getName();
			
			// Skip any properties that are to be excluded
			// or are not public
			if(in_array($name, $exclude) || !$properties[$i]->isPublic())
			{
				continue;
			}
				
			$value = $properties[$i]->getValue($this);
			
			/* Ignore NULL values.  Value object properties
			 * set to NULL will *not* be written to the database.
			 * Only value object fields with an empty string ('') will result
			 * in a blank value (NULL) being returned from this routine.
			 */
			if(is_null($value))
			{
				continue;
			}
			
			// Ignore any fields prefixed with '_'
			if( ($value !== '') && (substr($value, 0, 1) == '_') )
			{
				continue;
			}
			

			// Convert arrays to SETs
			if(is_array($value))
			{
				$value = implode(',', $value);
			}
			
			// If this property is not the first to be appended to the output
			// string, prepend a comma to separate it from the previous properties.
			if(strlen($sql) > 0)
			{
				$sql .= ', ';
			}
			
			// Format according to datatype
			if(is_numeric($value))
			{
				// Numbers
				$sql .= '`' . $name. '`=' . $value;
			}
			elseif($value === '')
			{
				// Write an empty string (which denotes a deliberately empty value)
				// as NULL, since NULL is compatible with all datatypes
				$sql .= '`' . $name . '`=NULL';
			}
			elseif(is_string($value))
			{
				if(preg_match('#^\d{1,2}/\d{1,2}/\d{2,4}$#', $value))
				{
					// Dates (in UK format)
					$sql .= '`' . $name . "`='" . Date::toMySQL($value) . "'";
				}
				else
				{
					// Strings
					$sql .= '`' . $name . "`='" . addslashes((string)$value) . "'";
				}
			}
		}

		return $sql;
	}

	public function toNameValuePairsSQL(array $exclude = array())
	{
		$class = new ReflectionClass(get_class($this));
		$properties = $class->getProperties();

		$name = '';
		$value = '';
		$numProperties = count($properties);
		$flag = 0;
		$sql = '(';
		for($i = 0; $i < $numProperties; $i++)
		{
			$name = $properties[$i]->getName();
			
			// Skip any properties that are to be excluded
			// or are not public
			if(in_array($name, $exclude) || !$properties[$i]->isPublic())
			{
				continue;
			}
				
			$value = $properties[$i]->getValue($this);
			
			/* Ignore NULL values.  Value object properties
			 * set to NULL will *not* be written to the database.
			 * Only value object fields with an empty string ('') will result
			 * in a blank value (NULL) being returned from this routine.
			 */
			if(is_null($value))
			{
				continue;
			}
			
			// Ignore any fields prefixed with '_'
			if( ($value !== '') && (substr($value, 0, 1) == '_') )
			{
				continue;
			}
			

			// Convert arrays to SETs
			if(is_array($value))
			{
				$value = implode(',', $value);
			}
			
			// If this property is not the first to be appended to the output
			// string, prepend a comma to separate it from the previous properties.
			if($flag == 0)
				$flag=1;
			else
				$sql .= ', ';
			
			
			// Format according to datatype
			if(is_numeric($value))
			{
				// Numbers
				$sql .= $name; //. '=' . $value;
			}
			elseif($value === '')
			{
				// Write an empty string (which denotes a deliberately empty value)
				// as NULL, since NULL is compatible with all datatypes
				$sql .= $name;// . '=NULL';
			}
			elseif(is_string($value))
			{
				if(preg_match('#^\d{1,2}/\d{1,2}/\d{2,4}$#', $value))
				{
					// Dates (in UK format)
					$sql .= $name;// . "='" . Date::toMySQL($value) . "'";
				}
				else
				{
					// Strings
					$sql .= $name;// . "='" . addslashes((string)$value) . "'";
				}
			}
		}

		$sql .= ') VALUES (';
		$flag=0;
		for($i = 0; $i < $numProperties; $i++)
		{
			$name = $properties[$i]->getName();
			
			// Skip any properties that are to be excluded
			// or are not public
			if(in_array($name, $exclude) || !$properties[$i]->isPublic())
			{
				continue;
			}
				
			$value = $properties[$i]->getValue($this);
			
			/* Ignore NULL values.  Value object properties
			 * set to NULL will *not* be written to the database.
			 * Only value object fields with an empty string ('') will result
			 * in a blank value (NULL) being returned from this routine.
			 */
			if(is_null($value))
			{
				continue;
			}
			
			// Ignore any fields prefixed with '_'
			if( ($value !== '') && (substr($value, 0, 1) == '_') )
			{
				continue;
			}
			

			// Convert arrays to SETs
			if(is_array($value))
			{
				$value = implode(',', $value);
			}
			
			// If this property is not the first to be appended to the output
			// string, prepend a comma to separate it from the previous properties.
			if($flag==  0)
				$flag=1;
			else
				$sql .= ', ';
			
			// Format according to datatype
			if(is_numeric($value))
			{
				// Numbers
				$sql .= $value;
			}
			elseif($value === '')
			{
				// Write an empty string (which denotes a deliberately empty value)
				// as NULL, since NULL is compatible with all datatypes
				$sql .= 'NULL';
			}
			elseif(is_string($value))
			{
				if(preg_match('#^\d{1,2}/\d{1,2}/\d{2,4}$#', $value))
				{
					// Dates (in UK format)
					$sql .= "'" . Date::toMySQL($value) . "'";
				}
				else
				{
					// Strings
					$sql .= "'". addslashes((string)$value) . "'";
				}
			}
		}
		
		$sql.=')';
		return $sql;
	}
	
	
	public function __toString()
	{
		$exclude = array();
		return $this->toNameValuePairs($exclude);
	}
	
	/**
	 * When passed a ValueObject that contains edited data, this method returns
	 * a string describing the changes that have been made in comparison to
	 * the ValueObject on which the method is called.
	 *
	 * @param ValueObject $new_version
	 * @return string
	 */
	public function buildAuditLogString(PDO $link, ValueObject $new_object, array $exclude_fields = array())
	{
		$class1 = new ReflectionClass(get_class($this));
		$properties1 = $class1->getProperties();
		
		$class2 = new ReflectionClass(get_class($new_object));
		
		$differences = '';
		
		foreach($properties1 as $prop1)
		{
			$name = $prop1->getName();
			
			if(in_array($name, $exclude_fields))
			{
				// Exclude this field
				continue;
			}
			
			$old_value = $prop1->getValue($this);
			
			if($class2->hasProperty($name))
			{
				$new_value = $class2->getProperty($name)->getValue($new_object);
				
				// to warrant a log entry, value2 must have a value,
				// and it must be substantially different to value1 
				// i.e. treat null as an empty string
				if(!is_null($new_value))
				{
					// The new value exists (it is not a null value)
					
					// Test for a change in value, taking into account various
					// combinations of null values and empty strings.
					$property_value_changed = false;
					if($new_value === '')
					{
						if(!is_null($old_value) && ($old_value !== '') )
						{
							// replacing a real value with an empty string
							$property_value_changed = true;
						}
						else
						{
							// Replacing a null value with an empty string
							// is not a significant change, at least not in this
							// program because empty strings are written to the
							// database as NULL.
						}
					}
					else
					{
						// Comparing two 'real' values
						if(Date::isDate($new_value) && Date::isDate($old_value))
						{
							// Specialised comparison routine for dates to avoid
							// dates with identical values but different formats
							// testing as unequal.
							$property_value_change = Date::parseDate($new_value) != Date::parseDate($old_value);
						}
						else
						{
							// Straightforward comparison
							$property_value_changed = ($new_value !== $old_value);
						}
					}
					
					if($property_value_changed)
					{
						$differences .= "[$name] changed from '$old_value' to '$new_value'\n";
					}
				}
			}
		}
		
		return $differences;
	}
}
?>