<?php
class ACL
{
	/**
	 * Loads an ACL from the database. If the ACL does not exist, a blank
	 * ACL object is returned initialised with the specified resource category
	 * and resource ID.
	 *
	 * @param pdo $link
	 * @param string $resource_category The type of resource e.g. Application, Learner, Support Record
	 * @param string $resource_id The ID of the resource, usually numeric
	 * @return ACL
	 */
	public static function loadFromDatabase(PDO $link, $resource_category, $resource_id)
	{
		$acl = new ACL();
		$acl->resource_category = $resource_category;
		$acl->resource_id = $resource_id;

		$rcat = addslashes((string)$resource_category);
		$rid = addslashes((string)$resource_id);
		$sql = <<<HEREDOC
SELECT
	privilege, ident
FROM
	acl
WHERE
	resource_category = '$rcat' AND resource_id='$rid'
ORDER BY
	privilege, ident;		
HEREDOC;

		$st = $link->query($sql);		
		if($st)
		{
			while($row = $st->fetch())
			{
				if(array_key_exists($row[0], $acl->identities))
				{
					$acl->identities[$row[0]][] = $row[1];
				}
				else
				{
					$acl->identities[$row[0]] = array($row[1]);
				}
			}
			
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		return $acl;
	}

	/**
	 * Saves the ACL to the database. Note that any existing database entries
	 * for this ACL are deleted prior to saving. If an ACL object contains
	 * no data at all, then the effect of saving will be similar to deleting the ACL.
	 *
	 * @param PDO $link
	 */
	public function save(PDO $link)
	{
		// Validate the ACL entries

		$invalid_identities = $this->validate($link);
		if(count($invalid_identities) > 0)
		{
			//throw new Exception('The following identities are invalid: '.implode(',', $invalid_identities));
		}


		// Clear all existing records for this ACL before proceeding
		$rcat = addslashes((string)$this->resource_category);
		$rid = addslashes((string)$this->resource_id);
		$sql = "DELETE FROM acl WHERE resource_category='$rcat' AND resource_id='$rid';";
		DAO::execute($link, $sql);

		if(count($this->identities) > 0)
		{
			$sql = '';
			foreach($this->identities as $privilege=>$entries)
			{
				foreach($entries as $entry)
				{
					if(strlen($sql) > 0)
					{
						$sql .= ',';
					}
	
					$sql .= " ('$rcat', '$rid', '".addslashes((string)$privilege)."', '".addslashes((string)$entry)."') ";
				}
			}
			if($sql != '')
			{
				$sql = "INSERT INTO acl (resource_category, resource_id, privilege, ident) VALUES " . $sql;
				DAO::execute($link, $sql);
			}
		}
	}


	/**
	 * Returns an array of identities for the specified privilege
	 *
	 * @param string $privilege
	 * @return array
	 */
	public function getIdentities($privilege)
	{
		$privilege = strtolower($privilege);
		if(array_key_exists($privilege, $this->identities))
		{
			return $this->identities[$privilege];
		}
		else
		{
			return array();
		}
	}


	/**
	 * Replaces the identities for a named privilege with a new set of identities
	 *
	 * @param string $privilege
	 * @param mixed $identities Array or comma-separated string of identities
	 */
	public function setIdentities($privilege, $identities)
	{
		// If there is no data to add, go no further
		if(is_null($identities))
		{
			return;
		}
		
		if(!is_array($identities))
		{
			if(strlen($identities) > 0)
			{
				$identities = explode(',', $identities);
			}
			else
			{
				$identities = array();
			}
		}
		
		$this->identities[strtolower($privilege)] = $identities;
	}


	/**
	 * Appends identities to a named privilege.
	 *
	 * @param string $privilege
	 * @param mixed $identities Array or comma-separated string of identities
	 */
	public function appendIdentities($privilege, $identities)
	{
		// If there is no data to add, go no further
		if(is_null($identities))
		{
			return;
		}
		
		$privilege = strtolower($privilege);
		
		if(!is_array($identities))
		{
			$identities = explode(',', $identities);
		}

		$entry = null;
		if(array_key_exists($privilege, $this->identities))
		{
			$entries = $this->identities[$privilege];
			$entries = array_merge($entries, $identities);
			$entries = $this->trim_array_elements($entries);
			$entries = array_unique($entries);
			$this->identities[$privilege] = $entries;
		}
		else
		{
			$this->identities[$privilege] = $identities;
		}
	}


	/**
	 * Removes identities from a named privilege
	 *
	 * @param string $privilege
	 * @param mixed $identities Array or comma-separated string of identities
	 */
	public function removeIdentities($privilege, $identities_to_remove)
	{
		// If there is no data to remove, go no further
		if(is_null($identities_to_remove))
		{
			return;
		}

		if(!is_array($identities_to_remove))
		{
			$identities_to_remove = explode(',', $identities_to_remove);
		}
		
		$privilege = strtolower($privilege);
		
		if($privilege == '')
		{
			// Remove the identities from all privileges
			foreach($this->identities as $privilege=>&$entries)
			{
				$entries = array_diff($entries, $identities);
			}
		}
		else
		{
			// Remove identities from only the named privilege
			if(array_key_exists($privilege, $this->identities))
			{
				$this->identities[$privilege] = array_diff($this->identities[$privilege], $identities_to_remove);
			}
		}
	}

	
	/**
	 * Determines if a user has a named privilege. If the user is an administrator
	 * (User->isAdmin() == true) then they are automatically authorised. If this
	 * ACL has not yet been assigned to a resource (ACL->resource_id == '') then
	 * the user is authorised as the ACL is not active.
	 *
	 * @param User $user
	 * @param string $privilege
	 * @return boolean
	 */
	public function isAuthorised(User $user, $privilege)
	{
		// If this ACL is not guarding anything or the user is an administrator
		if( ($this->resource_id == '') || ($this->resource_category == '') || $user->isAdmin())
		{
			return true;
		}

		if($privilege == '')
		{
			throw new Exception("Argument 'privilege' cannot be null");
		}
		
		if(array_key_exists($privilege, $this->identities))
		{
			$ids = $user->getIdentities();
			$ids_minus_identities = array_diff($ids, $this->identities[$privilege]);
			return count($ids_minus_identities) < count($ids);
		}

		// Default deny
		return false;
	}


	/**
	 * Convenience function for reading form fields containing ACL data. This
	 * is the counterpart of ACL->renderList() and makes use of temporary
	 * $_SESSION variables created by that method.
	 *
	 * @param array $HTMLForm
	 * @param unknown_type $fieldname
	 * @return mixed An array of values if the field exists, null if the field was not found
	 */
	public function readACLFormField(array $HTMLForm, $fieldname)
	{
		// Fetch raw value
		if(array_key_exists($fieldname, $HTMLForm) && $HTMLForm[$fieldname] != '')
		{
			$value = $HTMLForm[$fieldname];
		}
		else
		{
			$value = null;
		}
		
		// If there is a value...
		if(!is_null($value))
		{
			// Fetch list of values the user is allowed to use in this field
			if(!array_key_exists($fieldname, $this->allowed_values) && array_key_exists('acltemp_'.$fieldname, $_SESSION))
			{
				// Fetch from session, cache value and then unset session variable (so it cannot be used again)
				$allowed_values = $this->allowed_values[$fieldname] = $_SESSION['acltemp_'.$fieldname];
				unset($_SESSION['acltemp_'.$fieldname]);
			}
			elseif(array_key_exists($fieldname, $this->allowed_values))
			{
				// Fetch from local cache
				$allowed_values = $this->allowed_values[$fieldname];
			}
			else
			{
				// User is not allowed to enter any values in this field
				$allowed_values = array();
			}

			if(!is_array($value))
			{
				$value = explode(',', $value);
			}

			if(count($allowed_values) > 0)
			{
				// Discard any field values that are not in the allowed_values array
				$value = array_intersect($value, $allowed_values);
			}
		}

		return $value;
	}



	/**
	 * Renders the HTML UI for an ACL direct to STDOUT.
	 *
	 * @param pdo $link
	 * @param string $fieldname
	 * @param mixed $values Identities to show pre-selected (the value of the field)
	 * @param integer $show Bitwise OR ACL::EVERYONE, ACL::GROUPS, ACL::EMPLOYEES and ACL::USERS
	 * @param mixed $filter_include [Optional] array or comma-separated list of identities (no groups!)
	 * @param boolean $multi_select [optional] default true
	 */
	public function renderList(PDO $link, $fieldname, $values, $show = 23, $filter_include = null, $max_selection = 0)
	{
		// make values array (the current field value)
		if(is_null($values))
		{
			$values = array();
		}
		elseif(!is_array($values))
		{
			$values = explode(',', $values);
		}
		
		// Cache identity lists
		if(is_null($this->everyone) || is_null($this->groups) || is_null($this->employees) || is_null($this->users))
		{
			$this->all_identities = array();
			$this->all_options = array();

			$sql = "SELECT `group_name` AS `value`, `group_name` AS `label`, 'Groups' AS `grouping` FROM groups ORDER BY `group_name`";
			$this->groups = DAO::getResultset($link, $sql);
			$this->all_identities = array_merge($this->all_identities, DAO::getSingleColumn($link, $sql));
			$this->all_options = array_merge($this->all_options, DAO::getLookupTable($link, $sql));
			
			
			$sql = <<<HEREDOC
# Since this is listing employees only, we do NOT need LEFT OUTER JOINs
# to handle users without an employer. Also all organisations will have a location.
SELECT
	CONCAT(users.username, '/', locations.short_name, '/', organisations.short_name) AS `value`,
	CONCAT(users.surname, ', ', users.firstnames, ' (<code>', users.username, '</code>)', ' @ ', locations.full_name) AS `label`,
	CONCAT(organisations.trading_name, ' employees') AS `grouping`
FROM
	organisations INNER JOIN users INNER JOIN locations
	ON (users.employer_id = organisations.id
	AND locations.id = users.employer_location_id)

UNION

SELECT
	CONCAT('*/', short_name) AS `value`,
	'*' AS `label`,
	CONCAT(organisations.trading_name, ' employees') AS `grouping`
FROM
	organisations

UNION

SELECT
	CONCAT('*/', locations.short_name, '/', organisations.short_name) AS `value`,
	CONCAT('* @ ', locations.full_name) AS `label`,
	CONCAT(organisations.trading_name, ' employees') AS `grouping`
FROM
	organisations INNER JOIN locations
	ON locations.organisations_id = organisations.id

ORDER BY
	grouping, label	
HEREDOC;
			$this->employees_and_wildcards = DAO::getResultset($link, $sql);
			$this->all_identities = array_merge($this->all_identities, DAO::getSingleColumn($link, $sql));
			$this->all_options = array_merge($this->all_options, DAO::getLookupTable($link, $sql));

            $this->employees = array_filter($this->employees_and_wildcards, function($var) {
                return strpos($var[0]?:'','*')===false;
            });
			$this->employee_wildcards = array_filter($this->employees_and_wildcards, function($var){
                return strpos($var[0]?:'','*')!==false;
            });
	
			
			$sql = <<<HEREDOC
SELECT
	users.username,
	CONCAT(users.surname, ', ', users.firstnames, ' (<code>', users.username, '</code>)'),
	'Users'
FROM
	users
where type!=5 and type!=26
ORDER BY
	surname, firstnames
HEREDOC;
			$this->users = DAO::getResultset($link, $sql);
			$this->all_identities = array_merge($this->all_identities, DAO::getSingleColumn($link, $sql));
			$this->all_options = array_merge($this->all_options, DAO::getLookupTable($link, $sql));

			$this->everyone = array(array('*', 'Everyone', 'Wildcard'));
			$this->all_identities[] = '*';
			$this->all_options['*'] = array('*', 'Everyone', 'Wildcard');
		}


		// Build options array
		$options = array();
		if(($show & ACL::EVERYONE) == ACL::EVERYONE)
		{
			$options = array_merge($options, $this->everyone);
		}
		if(($show & ACL::GROUPS) == ACL::GROUPS)
		{
			$options = array_merge($options, $this->groups);
		}
		
		if( (($show & ACL::EMPLOYEE_WILDCARDS) == ACL::EMPLOYEE_WILDCARDS)
			&& (($show & ACL::EMPLOYEES) == ACL::EMPLOYEES))
		{
			$options = array_merge($options, $this->employees_and_wildcards);
		}
		elseif(($show & ACL::EMPLOYEES) == ACL::EMPLOYEES)
		{
			$options = array_merge($options, $this->employees);
		}
		elseif(($show & ACL::EMPLOYEE_WILDCARDS) == ACL::EMPLOYEE_WILDCARDS)
		{
			$options = array_merge($options, $this->employee_wildcards);
		}
			
		if(($show & ACL::USERS) == ACL::USERS)
		{
			$options = array_merge($options, $this->users);
		}
		
	
		// Apply inclusion filters
		if($filter_include != '')
		{
			if(!is_array($filter_include))
			{
				$filter_include = explode(',', $filter_include);
			}
			
			// Trim array
			$filter_include = array_filter($filter_include, function(&$var) {
                $var = trim($var ?: '');
                return strlen($var) > 0;
            });
			
			// Turn the filters into a regular expression
			if(count($filter_include) > 0)
			{
				if(count($filter_include) == 1)
				{
					$pattern = '#'.$filter_include[0].'$#';
				}
				else
				{
					$pattern = '#(?:'.implode(')|(?:', $filter_include).')$#';
				}

				// Remove wildcards
				$pattern = str_replace('*','', $pattern);

				// Construct new options array
				$filtered_options = array();
				foreach($options as $row)
				{
					if(preg_match($pattern, $row[0]))
					{
						$filtered_options[] = $row;
					}
				}
				
				$options = $filtered_options;
			}
		}
	

		// Prepend any anomalous entries that fall outside of the options array
		$anomalies = array();
		$anomalous_values = array_diff($values, $this->all_identities);
		foreach($anomalous_values as $v)
		{
			$anomalies[] = array($v, $v, ACL::ANOMALIES_GROUP_LABEL);
		}
		$options = array_merge($anomalies, $options);


		// The options array is an array of arrays, like a 'recordset', useful for rendering the ACL in HTML.
		// This structure is not useful for comparing with the 'values' function argument.
		// Extract the core values into a single-dimensional array for easier comparisons.
		$option_values = array();
		foreach($options as $o)
		{
			$option_values[] = $o[0];
		}

		// Temporarily store the list of options available to the user.
		// These arrays will be used again when the browser submits the form fields
		// this function is creating. This process stops a fraudulent user from slipping in
		// a few extra field values that he shouldn't have administrative control over.
		$_SESSION['acltemp_'.$fieldname] = $_SESSION['acltemp_'.$fieldname.'_not'] = $option_values;

		



		// Distinguish between editable and non-editable values.
		// Cache editable values in the Session object for later validation routines
		$non_editable_values = array_diff($values, $option_values);
		$editable_values = array_intersect($values, $option_values);


		echo "<table>";
		echo "<tr><th>Options</th><th>Current field value</th></tr>";
		echo "<tr><td style=\"border:1px solid gray;\">";
		echo "<div style=\"height:250px;overflow:scroll;\">\r\n";
		echo '<table cellspacing="0" cellpadding="2">'."\r\n";

		$grouping = '';
		$content_pane_id = md5(uniqid(rand(), true));
		$fieldname = str_replace(' ', '_', $fieldname);
		foreach($options as $row)
		{
			if($grouping != $row[2])
			{
				// New group
				$grouping = $row[2];
				if($grouping == ACL::ANOMALIES_GROUP_LABEL)
				{
					echo '<tr class="aclSubheading" style="background-color:red;color:white"><td colspan="2">'.$grouping.'</td></tr>'."\r\n";
				}
				else
				{
					echo '<tr class="aclSubheading"><td colspan="2">'.$grouping.'</td></tr>'."\r\n";
				}
			}

			$checked = in_array($row[0], $editable_values) ? 'checked="checked"':'';
			$checked_opposite = in_array($row[0], $editable_values) ? '':'checked="checked"';
			$class = in_array($row[0], $editable_values) ? 'aclRowSelected':'aclRowUnselected';
			$cell_style = $grouping == ACL::ANOMALIES_GROUP_LABEL ? 'color:red;font-family:monospace':'';
			if($grouping != ACL::ANOMALIES_GROUP_LABEL)
			{
				$label = str_replace('*', 'All', $row[1]);
			}
			else
			{
				$label = $row[1];
			}

			echo <<<HEREDOC
<tr class="$class">
<td><input type="checkbox" name="{$fieldname}[]" value="{$row[0]}" $checked
onclick="window.acl_identity_onclick(this, document.getElementById('$content_pane_id'), $max_selection);" /><input type="checkbox" style="display:none" name="{$fieldname}_not[]" value="{$row[0]}" $checked_opposite /></td>
<td style="$cell_style">$label</td></tr>
HEREDOC;
			
			
			echo "\r\n";
		}
		echo '</table></div>';
		echo "</td><td width=\"200\" valign=\"top\" style=\"border:1px solid gray;\">";
		echo '<textarea style="height:250px; border-style:none" cols="25" onfocus="blur()" id="'.$content_pane_id.'">';

		$display_array = array_merge($non_editable_values, $editable_values);
		echo implode("\r\n", $display_array);

		echo '</textarea>';
		echo "</table>";

		echo '<script language="JavaScript">';
		echo 'var cp = document.getElementById("'.$content_pane_id.'");';
		echo 'cp.update = function(checkbox){';
		echo 'var checkboxes = checkbox.form.elements[checkbox.name];';
		if(count($non_editable_values) > 0)
		{
			echo 'var html = "'.implode('\r\n', $non_editable_values).'\r\n";';
		}
		else
		{
			echo 'var html ="";';
		}
		echo 'for(var i = 0; i < checkboxes.length; i++){';
		echo 'if(checkboxes[i].checked){ html += (checkboxes[i].value + "\r\n");}}';
		echo 'this.value = html;  }';
		echo '</script>';
	}


	/**
	 * Validates the identities in an ACL
	 * 
	 * @param pdo $link
	 * @return boolean
	 */
	public function validate(PDO $link)
	{
		$invalid_entries = array();

		foreach($this->identities as $privilege=>$entries)
		{
			foreach($entries as $id)
			{
	
				if(strpos($id, '*') !== false)
				{
					// Validate wildcard entries
					if(preg_match('#^\*/([^/]+)/([^/]+)$#', $id, $matches))
					{
						$loc = addslashes((string)$matches[1]);
						$org = addslashes((string)$matches[2]);
	
						$sql = <<<HEREDOC
SELECT
	COUNT(*)
FROM
	locations
WHERE
	locations.short_name='$loc'
	AND organisations_id IN (SELECT id FROM organisations WHERE short_name = '$org')
HEREDOC;
	
						if(DAO::getSingleValue($link, $sql) == 0)
						{
							$invalid_entries[] = $id;
						}
					}
					elseif(preg_match('#^\*/([^/]+)$#', $id, $matches))
					{
						$org = addslashes((string)$matches[1]);
						$sql = "SELECT COUNT(*) FROM organisations WHERE short_name='$org'";
	
						if(DAO::getSingleValue($link, $sql) == 0)
						{
							$invalid_entries[] = $id;
						}
					}
					elseif($id != '*')
					{
						$invalid_entries[] = $id;
					}
				}
				else
				{
					if(preg_match('#^([^/]+)/([^/]+)/([^/]+)$#', $id, $matches))
					{
						// Fully qualified username
						$user = addslashes((string)$matches[1]);
						$loc = addslashes((string)$matches[2]);
						$org = addslashes((string)$matches[3]);
	
						$sql = <<<HEREDOC
SELECT
	COUNT(*)
FROM
	users INNER JOIN organisations INNER JOIN locations
	ON(users.employer_id = organisations.id AND users.employer_location_id = locations.id)
WHERE
	users.username = '$user' AND organisations.short_name = '$org' AND locations.short_name = '$loc';
HEREDOC;
						if(DAO::getSingleValue($link, $sql) == 0)
						{
							$invalid_entries[] = $id;
						}
					}
					else
					{
						// User or group name
						$key = addslashes((string)$id);
						$sql = <<<HEREDOC
SELECT
	COUNT(*)
FROM
(SELECT
	username
FROM
	users
WHERE
	username = '$key'
UNION

SELECT
	group_name
FROM
	groups
WHERE
	group_name='$key') AS tb1;
HEREDOC;
	
						if(DAO::getSingleValue($link, $sql) == 0)
						{
							$invalid_entries[] = $id;
						}
					}
				}
			}
		}

		return $invalid_entries;
	}

	
	public static function expandWildcards(PDO $link, $identities)
	{
		if(!is_array($identities))
		{
			$identities = explode(',', $identities);
		}
		
		// Strip empty strings
		$identities = array_filter($identities, function(&$var){
            $var = trim($var?:'');
            return strlen($var) > 0;
        });
		
		if(count($identities) == 0)
		{
			return array();
		}		
		
		// Find and expand wildcards
		$expanded_users = array();
		foreach($identities as $identity)
		{
			if($identity[0] == '*')
			{
				if(preg_match('#^\*/([^/]+)$#', $identity, $matches))
				{
					// Organisation wildcard (e.g. */hoover)
					$key = addslashes((string)$matches[1]);
					$sql = <<<HEREDOC
SELECT
	CONCAT(username, '/', short_name)
FROM
	users INNER JOIN organisations
	ON users.employer_id = organisations.id
WHERE
	short_name='$key'
HEREDOC;
					$expanded_users = array_merge($expanded_users, DAO::getSingleColumn($link, $sql));
				}
				elseif(preg_match('#^\*/([^/]+)/([^/]+)$', $identity, $matches))
				{
					// Location/Organisation wildcard (e.g. */Rotherham/Hoover)
					$loc = addslashes((string)$matches[1]);
					$org = addslashes((string)$matches[2]);
					$sql = <<<HEREDOC
SELECT
	CONCAT(username, '/', loc.short_name, '/', org.short_name)
FROM
	users INNER JOIN organisations AS org INNER JOIN locations AS loc
	ON (users.employer_id = organisations.id
	AND users.employer_location_id = locations.id
	AND locations.organisations_id = organisations.id)
WHERE
	org.short_name='$org' AND loc.short_name='$loc'
HEREDOC;
					$expanded_users = array_merge($expanded_users, DAO::getSingleColumn($link, $sql));
				}
				elseif($identity == '*')
				{
					$sql = "SELECT CONCAT(username, '/', loc.short_name, '/', org.short_name) FROM users";
					$expanded_users = array_merge($expanded_users, DAO::getSingleColumn($link, $sql));
				}						
			}
		}

		// Add expanded users
		$identities = array_merge($identities, $expanded_users);
		
		return array_unique($identities);
	}
	
	/**
	 * Filters a list of identities using as second list of identities as filters.
	 * The filter argument accepts wildcards, the filter "* /hoover" will match "bob/hoover",
	 * "bob/sales/hoover" and "* /sales/hoover".
	 *
	 * @param mixed $identities An array or comma-separated list of identities
	 * @param mixed $filters An array or comma-separated list of identities that
	 * act as inclusion filters
	 * @return array An array of identities that meet the criteria of the filters
	 */
	public static function filterIdentities($identities, $filters)
	{
		if(!is_array($identities))
		{
			$identities = explode(',', $identities);
		}
		
		if(!is_array($filters))
		{
			$filters = explode(',', $filters);
		}
		
		// Strip empty strings
		$callback = function(&$var) {
            $var= trim($var?:'');
            return strlen($var) > 0;
        };
		$identities = array_filter($identities, $callback);
		$filters = array_filter($filters, $callback);
		
		// Turn the filters into a regular expression
		if(count($filters) > 0)
		{
			if(count($filters) == 1)
			{
				$pattern = '#'.$filters[0].'$#';
			}
			else
			{
				$pattern = '#(?:'.implode(')|(?:', $filters).')$#';
			}

			// Remove wildcards
			$pattern = str_replace('*','', $pattern);

			// Construct new options array
			$filtered_identities = array();
			foreach($identities as $id)
			{
				if(preg_match($pattern, $id))
				{
					$filtered_identities[] = $id;
				}
			}
			
			$identities = $filtered_identities;
		}
		
		return $identities;
	}

	
	
	
	
	private function trim_array_elements(array $a)
	{
		$b = array();
		foreach($a as $element)
		{
			$trimmed = trim($element);
			if(strlen($trimmed) > 0)
			{
				$b[] = strtolower($trimmed);
			}
		}
		
		return $b;
	}

	
	public $resource_category = NULL;
	public $resource_id = NULL;

	private $entries = array();
	private $identities = array();

	// Identity cache
	private $everyone = null;
	private $groups = null;
	private $employees_and_wildcards = null;
	private $employees = null;
	private $employee_wildcards = null;
	private $users = null;
	private $all_identities = null;
	private $all_options = null;

	// Cached form fields
	private $allowed_values = array();

	const EVERYONE = 1;
	const GROUPS = 2;
	const EMPLOYEES = 4;
	const USERS = 8;
	const EMPLOYEE_WILDCARDS = 16;

	const ANOMALIES_GROUP_LABEL = 'Defunct identities (consider removing)';
}
?>