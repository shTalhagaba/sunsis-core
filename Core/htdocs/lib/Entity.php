<?php
class Entity
{
	/**
	 * Populates an Entity's public properties with corresponding property
	 * values from an associative array or object.  Non-corresponding properties
	 * are silently ignored.  NULL values are NOT copied.
	 *
	 * This method is used both for populating objects from a database row
	 * or from a submitted form. When populating from a form you *may* wish to
	 * treat empty text boxes as NULL rather than as an empty string. In this framework,
	 * DAO::saveObjectToDatabase() will *not* write NULL object properties to the
	 * database, which preserves the existing value in the database.
	 *
	 * @param mixed $map An associative array or an object
	 * @param bool $empty_string_is_null Set this to true if selectively
	 * updating fields rather than updating the entire record. NULL values are
	 * not written to the database, but empty strings are (they are written as NULL
	 * so as to be compatible with all datatypes).
	 * @param array $exclude_fields
	 */
	public function populate($map, $empty_string_is_null = false, array $exclude_fields = array())
	{
		$map = (array) $map;

		$class = new ReflectionClass(get_class($this));
		$properties = $class->getProperties();
		foreach ($properties as $property) {
			$prop_name = $property->getName();
			if ( array_key_exists($prop_name, $map) && $property->isPublic() ) {
				if(is_null($map[$prop_name]) || in_array($prop_name, $exclude_fields)) {
					continue;
				} else if( ($map[$prop_name] === '') && $empty_string_is_null ) {
					$property->setValue($this, NULL);
				} else {
					$property->setValue($this, $map[$prop_name]);
				}
			}
		}

/*		if(is_array($map))
		{
			foreach($properties as $property)
			{
				$prop_name = $property->getName();

				if( array_key_exists($prop_name, $map) && $property->isPublic() )
				{
					if(is_null($map[$prop_name]) || in_array($prop_name, $exclude_fields))
					{
						continue;
					}
					elseif( ($map[$prop_name] === '') && $empty_string_is_null )
					{
						$property->setValue($this, NULL);
					}
					else
					{
						$property->setValue($this, $map[$prop_name]);
					}
				}
			}
		}
		elseif(is_object($map))
		{
			foreach($properties as $property)
			{
				$prop_name = $property->getName();
				if( array_key_exists($prop_name, $map) && $property->isPublic() )
				{
					if(is_null($map->$prop_name) || in_array($prop_name, $exclude_fields))
					{
						continue;
					}
					elseif( ($map->$prop_name === '') && $empty_string_is_null )
					{
						$property->setValue($this, NULL);
					}
					else
					{
						$property->setValue($this, $map->$prop_name);
					}
				}
			}			
		}
		else
		{
			throw new Exception("Argument \$map must be an array or an object");
		}*/
	}

	
	/**
	 * When passed a ValueObject that contains edited data, this method returns
	 * a string describing the changes that have been made in comparison to
	 * the ValueObject on which the method is called.
	 *
	 * @param ValueObject $new_version
	 * @return string
	 */
	public function buildAuditLogString(PDO $link, Entity $new_object)
	{
		$class1 = new ReflectionClass(get_class($this));
		$class2 = new ReflectionClass(get_class($new_object));
		
		$differences = '';
		
		$properties1 = $class1->getProperties();
		foreach($properties1 as $prop1)
		{
			$name = $prop1->getName();
			
			
			if(!is_null($this->audit_fields) && !array_key_exists($name, $this->audit_fields))
			{
				continue; // Exclude this field
			}

			$old_value = $prop1->getValue($this);

			if($class2->hasProperty($name))
			{
				$new_value = $class2->getProperty($name)->getValue($new_object);
				
				// to warrant a log entry, value2 must have a value
				// because we don't write NULL values to the database
				if(!is_null($new_value))
				{
					// Ensure date comparisons use the same date format
					if(Date::isDate($new_value) && Date::isDate($old_value))
					{
						$old_value = Date::toShort($old_value);
						$new_value = Date::toShort($new_value);
					}

					// Branch on whether we are dealing with arrays or scalars
					if(is_array($old_value) || is_array($new_value))
					{
						if(!is_array($old_value))
						{
							$old_value = explode(',', $old_value); // works if 'old_value' is NULL too
						}
						
						if(!is_array($new_value))
						{
							$new_value = explode(',', $new_value);
						}
						
						if( (count($old_value) != count($new_value))
							|| (count(array_intersect($old_value, $new_value)) != count($old_value)) )
						{

							if(!is_null($this->audit_fields))
							{
								if(array_key_exists($name, $this->audit_fields))
								{
									$differences .= "[{$this->audit_fields[$name]}] changed from '".implode(',', $old_value)."' to '".implode(',', $new_value)."'\n";
								}
							}
							else
							{
								$differences .= "[$name] changed from '".implode(',', $old_value)."' to '".implode(',', $new_value)."'\n";
							}
						}
					}
					elseif($new_value != $old_value)
					{
						if(!is_null($this->audit_fields))
						{
							if(array_key_exists($name, $this->audit_fields))
							{
								$differences .= "[{$this->audit_fields[$name]}] changed from '$old_value' to '$new_value'\n";
							}
						}
						else
						{
							$differences .= "[$name] changed from '$old_value' to '$new_value'\n";
						}								
					}
					
				}
			}
		}
		
		return $differences;
	}	

	
	/**
	 * Override if more is required
	 */
	public function toXML()
	{
		$class = new ReflectionClass(get_class($this));
		$objectProperties = $class->getProperties();		
		
		$xml = '<object class="'.$class->getName().'">';
		foreach($objectProperties as $property)
		{
			if($property->isPublic())
			{
				$field = $property->getName();
				$value = $property->getValue($this);

				$xml .= '<property name="'.$field.'">';
				if(is_array($value))
				{
					foreach($value as $v)
					{
						$xml .= '<value>'.htmlspecialchars((string)$v).'</value>';
					}
				}
				elseif(is_object($value))
				{
					$xml .= '<value/>';
				}
				else
				{
					$xml .= '<value>'.htmlspecialchars((string)$value).'</value>';
				}
				$xml .= '</property>';
			}
		}
		$xml .= "</object>";
		
		return $xml;
	}
	
	
	public function toJSON()
	{
		$class = new ReflectionClass(get_class($this));
		$props = $class->getProperties();		
		
		$json = '{';
		for($p = 0; $p < count($props); $p++)
		{
			if($props[$p]->isPublic())
			{
				$field = $props[$p]->getName();
				$value = $props[$p]->getValue($this);

				if($p > 0)
				{
					$json .= ",\r\n\t";
				}
				
				$json .= $field.':';
				if(is_array($value))
				{
					if(count($value) > 0)
					{
						$json .= '[';
						for($v = 0; $v < count($value); $v++)
						{
							if($v > 0)
							{
								$json .= ',';
							}
							if(is_null($value[$v]))
							{
								$json .= 'null';
							}
							else
							{
								$json .= '"'.addslashes((string)$value[$v]).'"';
							}
						}
						$json .= ']';
					}
					else
					{
						$json .= 'null';
					}
				}
				elseif(is_null($value) || is_object($value))
				{
					$json .= 'null';
				}
				else
				{
					$json .= '"'.addslashes((string)$value).'"';
				}
			}
		}
		$json .= "}";
		
		return $json;		
	}
	
	/**
	 * MIME type for XFDF is 'application/vnd.adobe.xfdf'
	 * Character set MUST be UTF-8
	 */
	public function toXFDF($pdfURL,$extra=null)
	{
		$class = new ReflectionClass(get_class($this));
		$objectProperties = $class->getProperties();		
		
		$xml = '<xfdf xmlns="http://ns.adobe.com/xfdf/" xml:space="preserve">';
		$xml .= '<fields>';
		
		if ( $extra )
		{
			
			foreach( $extra as $key => $value)
			{

				$xml .= '<field name="'.$key.'">';
				$xml .= '<value>'.mb_convert_encoding(htmlspecialchars((string)$value),'UTF-8').'</value>';
				$xml .= '</field>';
			}
			
		}
		
		foreach($objectProperties as $property)
		{
			if($property->isPublic())
			{
				$field = $property->getName();
				$value = $property->getValue($this);

				$xml .= '<field name="'.$field.'">';
				if(is_array($value))
				{
					foreach($value as $v)
					{
						$xml .= '<value>'.mb_convert_encoding(htmlspecialchars((string)$v),'UTF-8').'</value>';
					}
				}
				elseif(is_object($value))
				{
					continue; // ignore objects (for now)
				}
				else
				{
					$xml .= '<value>'.mb_convert_encoding(htmlspecialchars((string)$value),'UTF-8').'</value>';
				}
				$xml .= '</field>';
			}
		}
		
		$xml .= '</fields>'
			. '<ids original="'.md5($pdfURL).'" modified="'.time().'" />'
			. '<f href="'.$pdfURL.'" />'
			. '</xfdf>';
		
		return $xml;		
	}
	
	
	public function getAuditFields()
	{
		return $this->audit_fields;
	}

	
	protected $audit_fields = NULL;
}
?>