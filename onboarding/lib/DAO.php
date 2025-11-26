<?php
/**
 * General Data Access methods
 */
class DAO
{
	/**
	 * Returns a connection using the specified connection details, or uses the defaults defined
	 * by the DB_* constants if no connection details are specified. Database connections are cached
	 * to avoid creating multiple connections for identical calls to this method.
	 * @static
	 * @param string $host
	 * @param string $port
	 * @param string $user
	 * @param string $password
	 * @param string $dbName
	 * @param bool $cache
	 * @throws Exception
	 * @return PDO
	 */
	public static function getConnection($host = '', $port = '', $user = '', $password = '', $dbName = '', $cache = true)
	{
		if (!$host) {
			if (defined("DB_HOST")) {
				$host = DB_HOST;
			} else {
				if (PHP_OS == "WINNT") {
					$host = '127.0.0.1';
				} else {
					$host = "localhost";
				}
			}
		}
		if (!$port) {
			if (defined("DB_PORT")) {
				$port = DB_PORT;
			} else {
				$port = 3306;
			}
		}
		if (!$user) {
			if (defined("DB_USER")) {
				$user = DB_USER;
			}
		}
		if (!$password) {
			if (defined("DB_PASSWORD")) {
				$password = DB_PASSWORD;
			}
		}
		if (!$dbName) {
			if (defined("DB_NAME")) {
				$dbName = DB_NAME;
			}
		}

        $options = [];
        // If RDS SSL CA is provided in env, add SSL options
        $sslCa = getenv('PERSPECTIVE_DB_SSL_CA');
        if ($sslCa && file_exists($sslCa)) {
            $options = [
                PDO::MYSQL_ATTR_SSL_CA => $sslCa,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            ];
        }

		$key = $host . $port . $user . $password . $dbName;
		if ($cache) {
			if (array_key_exists($key, static::$connections)) {
					$link = static::$connections[$key];
			} else {
				$link = new PDO("mysql:host=" . $host . ";dbname=" . $dbName . ";port=" . $port.';charset=utf8mb4', $user, $password, $options);
				static::$connections[$key] = $link;
			}
		} else {
			$link = new PDO("mysql:host=" . $host . ";dbname=" . $dbName . ";port=" . $port.';charset=utf8mb4', $user, $password, $options);
		}

		return $link;
	}

	/**
	 * @static
	 * @param string $key
	 */
	public static function removeCacheKey($key)
	{
		$key = $_SERVER['SERVER_NAME'] . ' ' . $key;
		Cache::remove($key);
	}

	/**
	 * @static
	 * @param string $prefix
	 */
	public static function removeCacheKeyByPrefix($prefix)
	{
		$prefix = $_SERVER['SERVER_NAME'] . ' ' . $prefix;
		Cache::removeByPrefix($prefix);
	}

	/**
	 * Saves an object or an associative array to a database table.
	 * Supports table names qualified by a database name.
	 * To save a batch of arrays or objects, use DAO::multipleRowInsert().
	 * @static
	 * @param PDO $link Database connection
	 * @param string $table Table name in format "table" or "database.table"
	 * @param mixed $data Object or associative-array
	 * @param boolean $allow_UPDATE allow this method to update existing record(s)
	 * @throws DatabaseException
	 * @throws Exception
	 * @return boolean
	 */
	public static function saveObjectToTable(PDO $link, $table, &$data, $allow_UPDATE = true)
	{
		// Convert an object to a keyed array so that
		// we only have to write code for an array
		if(is_array($data))
		{
			$array =& $data;
		}
		else if(is_object($data))
		{
			$array = get_object_vars($data);
		}
		else
		{
			throw new Exception("Argument \$data must be an array or an object");
		}

		// Get Table metadata
		$table_metadata = DAO::getTableFields($link, $table);
		$pri_keys = DAO::getTablePrimaryKeys($link, $table);

		// Escape table name
		$table = '`'.str_replace('.', '`.`', $table).'`';

		// Check for correspondences between primary key fields in the table
		// and hashkeys in the array
		$num_corresponding_keys = 0;
		foreach($pri_keys as $key_name=>$extra) // check each primary key
		{
			if(array_key_exists($key_name, $array))
			{
				if($array[$key_name] != '')
				{
					$num_corresponding_keys++;
				}
				else
				{
					// Throw an error if the primary key is none auto-incrementing
					if( ($array[$key_name] == '') && (strpos($extra, 'auto_increment') === false) )
					{
						throw new Exception("Table `$table` has a non-nullable, non-auto-incrementing primary key field `$key_name` "
							. " and the array has no corresponding property or value.", DAO::MYSQLI_COLUMN_CANNOT_BE_NULL);
					}
				}
			}
		}


		// Check for a matching record if the object has data for all primary keys
		if( ($num_corresponding_keys == count($pri_keys)) && count($pri_keys) > 0)
		{
			$sql = "SELECT COUNT(*) FROM $table WHERE ";
			$key_count = 0;
			foreach($pri_keys as $key_name=>$extra)
			{
				if($key_count++ > 0)
				{
					$sql .= ' AND ';
				}

				$sql .= " `{$key_name}`='".addslashes($array[$key_name])."' ";
			}

			$recordExists = DAO::getSingleValue($link, $sql);
		}
		else
		{
			$recordExists = false;
		}


		if(!$recordExists)
		{
			// INSERT new record

			$fieldCount = 0;
			$sql = "INSERT INTO $table SET ";
			foreach($array as $field=>$value)
			{
				if(array_key_exists($field, $table_metadata))
				{
					// Do not write NULL values in the INSERT clause
					// (this allows MySQL default field values to work)
					if(is_null($value))
					{
						continue;
					}

					// NB: No need to code for the eventuality of writing null,
					// blank or zero entries to an auto-incrementing primary key.
					// MySQL will intercept these values automatically and substitute
					// the next number in the auto-increment series.

					if($fieldCount++ > 0)
					{
						$sql .= ', ';
					}

					$sql .= DAO::formatNameValuePair($link, $field, $value, $table_metadata[$field]['DATA_TYPE']);
				}
			}

			// Execute query if there are any fields to INSERT
			if($fieldCount > 0)
			{
				DAO::execute($link, $sql);

				// Update the PHP object with any new auto_increment value
				foreach($pri_keys as $key_name=>$extra)
				{
					if( (strpos($extra, 'auto_increment') !== false) && self::key_exists_safe($key_name, $data))
					{
						if(is_array($data))
						{
							$data[$key_name] = $link->lastInsertId();
						}
						else
						{
							$data->$key_name = $link->lastInsertId();
						}
						break;
					}
				}

				return true;
			}
		}
		elseif($allow_UPDATE)
		{
			// UPDATE current record
			$fieldCount = 0;
			$sql = "UPDATE $table SET ";
			foreach($array as $field=>$value)
			{
				if(array_key_exists($field, $table_metadata))
				{
					$column_key = $table_metadata[$field]['COLUMN_KEY'];

					// 1) Do not write NULL values in the UPDATE clause
					// (VoLT convention for leaving a field value unchanged)
					// 2) Do not try to update the primary index
					if(is_null($value) || ($column_key == 'PRI'))
					{
						continue;
					}

					if($fieldCount++ > 0)
					{
						$sql .= ', ';
					}

					$sql .= DAO::formatNameValuePair($link, $field, $value, $table_metadata[$field]['DATA_TYPE']);
				}
			}
			$sql .= " WHERE ";
			$key_count = 0;
			foreach($table_metadata as $fieldname=>$meta)
			{
				if( ($meta['COLUMN_KEY'] == 'PRI') && array_key_exists($fieldname, $array) )
				{
					if($key_count++ > 0)
					{
						$sql .= ' AND ';
					}

					$sql .= " `{$fieldname}`='".addslashes($array[$fieldname])."' ";
				}
			}

			// Execute query if there are any fields to INSERT
			if($fieldCount > 0)
			{
				DAO::execute($link, $sql);
				return true;
			}

		}

		return false;
	}

	/**
	 * Retained for compatibility with Sunesis
	 * @static
	 * @deprecated Please use DAO::saveObjectToTable() instead
	 * @param PDO $link
	 * @param string $table
	 * @param array $data
	 * @param boolean $allow_UPDATE
	 * @return boolean
	 */
	public static function saveArrayToTable(PDO $link, $table, array $data, $allow_UPDATE=true)
	{
		return DAO::saveObjectToTable($link, $table, $data, $allow_UPDATE);
	}


	/**
	 * Inserts an array of associated-arrays/objects into a table. Supports table names qualified
	 * by a database name.
	 * Unlike other DAO functions, no distinction is drawn between '' and NULL values
	 * when updating existing rows; both write NULL to the database.
	 * @static
	 * @param PDO $link Database connection
	 * @param string $table Table name in format "table_name" or "database.table_name"
	 * @param array $rows Array of associated-arrays/objects
	 * @param boolean $allow_UPDATE allow this method to update existing record(s)
	 * @throws DatabaseException
	 * @throws Exception
	 * @return boolean
	 */
	public static function multipleRowInsert(PDO $link, $table, array &$rows, $allow_UPDATE = true)
	{
		if(count($rows) == 0){
			return false;
		}

		foreach($rows as $row)
		{
			if(!is_array($row) && !is_object($row))
			{
				throw new Exception("Argument \$rows must be an array of objects or arrays");
			}
		}

		// Each batch is restricted to 2MB or smaller
		$max_allowed_packet = self::getMaxAllowedPacket($link) < (2 * DAO::MEGABYTE) ? self::getMaxAllowedPacket($link) : (2 * DAO::MEGABYTE);

		// Get Table metadata
		$columns = DAO::getTableFields($link, $table);
		$pri_keys = DAO::getTablePrimaryKeys($link, $table);
		$auto_increment_field_name = null;
		foreach($pri_keys as $key_name=>$extra)
		{
			if(strpos($extra, 'auto_increment') !== false)
			{
				$auto_increment_field_name = $key_name;
			}
		}

		// Validate non auto-incrementing primary key fields
		foreach($pri_keys as $key_name=>$extra) // check each primary key
		{
			if(strpos($extra, 'auto_increment') === false)
			{
				foreach($rows as $row)
				{
					if(is_array($row))
					{
						$value = array_key_exists($key_name, $row) ? $row[$key_name] : '';
					}
					else
					{
						$value = array_key_exists($key_name, $row) ? $row->$key_name : '';
					}

					// Do not abbreviate this comparison to $value == '' (it must not match 0)
					if(is_null($value) || $value === '')
					{
						throw new Exception("Table `$table` has a non-nullable, non-auto-incrementing primary key field `$key_name` "
							. " and one row has no corresponding property or value.", DAO::MYSQLI_COLUMN_CANNOT_BE_NULL);
					}
				}
			}
		}

		// Determine columns we will be inserting data into
		// Obeys CLM custom: object fields set to NULL are excluded from writes to the database
		$fields = array();
		foreach($rows as $r)
		{
			// The foreach below won't work if the object implements Iterator
			if($r instanceof Iterator)
			{
				$r = get_object_vars($r);
			}

			foreach($r as $field=>$value)
			{
				if(!in_array($field, $fields) && array_key_exists($field, $columns) && !is_null($value))
				{
					$fields[] = $field;
				}
			}
		}

		$header = self::getMultipleInsertHeader($table, $columns, $pri_keys, $fields, $allow_UPDATE);
		$footer = self::getMultipleInsertFooter($table, $columns, $pri_keys, $fields, $allow_UPDATE);


		try
		{
			// Turn on output buffering
			ob_start();

			echo $header;

			$rows_to_update = array();
			$header_length = strlen($header);
			$footer_length = strlen($footer);
			$value_length = 0;
			$value_count = 0;
			foreach($rows as &$row)
			{
				$s = self::getMultipleInsertValues($link, $row, $columns, $pri_keys, $fields);
				$value_length += strlen($s) + 1; // we add 1 for a comma

				// Execute query if too long (include the next iteration's footer in the calculation)
				if(($header_length + $value_length + ($footer_length * 2) + 10) >= $max_allowed_packet)
				{
					// Query getting too long
					// Execute now and reset before continuing
					echo $footer;
					$sql = ob_get_contents();
					DAO::execute($link, $sql);
					$id = $link->lastInsertId();
					if($auto_increment_field_name)
					{
						foreach($rows_to_update as &$r)
						{
							if(is_array($r))
							{
								$r[$auto_increment_field_name] = $id++;
							}
							else
							{
								$r->$auto_increment_field_name = $id++;
							}
						}
					}

					$rows_to_update = array();
					$value_length = strlen($s);
					$value_count = 0;

					ob_clean();
					echo $header;
				}

				$value_count++;

				if($auto_increment_field_name)
				{
					if(is_array($row))
					{
						if(!isset($row[$auto_increment_field_name]) || $row[$auto_increment_field_name] == '')
						{
							$rows_to_update[] =& $row;
						}
					}
					else
					{
						if(!isset($row->$auto_increment_field_name) || $row->$auto_increment_field_name == '')
						{
							$rows_to_update[] =& $row;
						}
					}
				}

				if($value_count > 1)
				{
					echo ",";
				}
				echo $s;
			}

			// Perform (last) query
			echo $footer;
			$sql = ob_get_contents();
			DAO::execute($link, $sql);
			$id = $link->lastInsertId();
			if($auto_increment_field_name)
			{
				foreach($rows_to_update as &$r)
				{
					if(is_array($r))
					{
						$r[$auto_increment_field_name] = $id++;
					}
					else
					{
						$r->$auto_increment_field_name = $id++;
					}
				}
			}

			// Close output buffer
			ob_end_clean();
		}
		catch(Exception $e)
		{
			ob_end_clean();
			throw $e;
		}

		return true;
	}

	/**
	 * Creates the header for a multiple-row INSERT e.g. INSERT INTO table (`field`, ..) VALUES
	 * @static
	 * @param string $table Table name
	 * @param array $columns Array of column metadata
	 * @param array $pri_keys Array of primary-key field names
	 * @param array $fields Array of field names
	 * @param boolean $allow_update
	 * @return string SQL header
	 */
	private static function getMultipleInsertHeader($table, array $columns, array $pri_keys, array $fields, $allow_update)
	{
		// Escape table name
		$table = '`'.str_replace('.', '`.`', $table).'`';

		// Scan for unique secondary indexes
		$unique_secondary_indexes = false;
		foreach($columns as $name=>$meta)
		{
			if(strpos($meta['COLUMN_KEY'], 'UNI') !== FALSE)
			{
				$unique_secondary_indexes = true;
				break;
			}
		}

		if(!$unique_secondary_indexes && $allow_update)
		{
			// Allows ON DUPLICATE KEY UPDATE syntax -- see getMultipleInsertFooter()
			$sql = "INSERT INTO $table (";
		}
		else
		{
			// ON DUPLICATE KEY UPDATE syntax not advisable in the presence
			// of unique secondary indexes. Ignore any index clashes.
			$sql = "INSERT IGNORE INTO $table (";
		}

		foreach($fields as $f)
		{
			$sql .= '`'.$f.'`,';
		}
		$sql = trim($sql, ',');
		$sql .= ") VALUES ";

		return $sql;
	}


	/**
	 * Creates the footer for a multiple-row INSERT statement. A footer is only
	 * generated if it is possible to use a ON DUPLICATE KEY UPDATE clause and
	 * the parameter 'allow_update' is true.
	 * @static
	 * @param string $table Table name
	 * @param array $columns Array of column metadata
	 * @param array $pri_keys Array of primary-key field names
	 * @param array $fields Array of field names
	 * @param boolean $allow_update
	 * @return string
	 */
	private static function getMultipleInsertFooter($table, array $columns, array $pri_keys, array $fields, $allow_update)
	{
		$sql = "";

		// Scan for unique secondary indexes
		$unique_secondary_indexes = false;
		foreach($columns as $name=>$meta)
		{
			if(strpos($meta['COLUMN_KEY'], 'UNI') !== FALSE)
			{
				$unique_secondary_indexes = true;
				break;
			}
		}


		// We can use "ON DUPLICATE KEY UPDATE" if there
		// are no unique secondary indexes, there are fields in the table
		// other than the primary key fields and if allow_overwrite is true
		if(!$unique_secondary_indexes && count($pri_keys) < count($fields) && $allow_update)
		{
			$sql .= " ON DUPLICATE KEY UPDATE ";
			foreach($fields as $f)
			{
				// Don't update primary keys or timestamp fields
				// (timestamps require flexibility that is not available in multiple-row inserts)
				if(array_key_exists($f, $pri_keys) || $columns[$f]['DATA_TYPE'] == 'timestamp'){
					continue;
				}

				$sql .= " `".$f."` = VALUES(`".$f."`),";
			}
			$sql = trim($sql, ',');
		}

		return $sql;
	}

	/**
	 * Generates the main body of a multiple-row INSERT statement.
	 * Unlike DAO::saveObjectToTable(), no distinction is currently drawn between '' and NULL;
	 * both write NULL to the database. This might change in the future as it makes updating
	 * existing records in batch less flexible.
	 * @static
	 * @param PDO $link Database link
	 * @param mixed $row Associative array or object
	 * @param array $columns Array of column metadata
	 * @param array $pri_keys Array of primary-key field names
	 * @param array $fields Array of field names
	 * @throws Exception
	 * @return string
	 */
	private static function getMultipleInsertValues(PDO $link, $row, array $columns, array $pri_keys, array $fields)
	{
		// cast row to an array to simplify the code below
		$dataArray = (array)$row;

		ob_start();

		try
		{
			echo "(";
			$field_count = 0;
			foreach($fields as $f)
			{
				$field_count++;
				if($field_count > 1) {
					echo ','; // Write value separator for all fields except the first
				}

				if(array_key_exists($f, $dataArray)) {
					// Extract and filter value
					$value = $dataArray[$f];
					if(is_array($value)) {
						$value = implode(',', $value); // immediately convert array to CSV (for SET fields)
					}
					if($columns[$f]['DATA_TYPE'] == "date") {
						$value = Date::toMySQL($value);
					}
					$value = trim($value);

					// Render value
					if (is_null($value) || $value === '') {
						if($columns[$f]['DATA_TYPE'] == "timestamp") {
							echo $columns[$f]['IS_NULLABLE'] ? 'DEFAULT':'NULL'; // NULL generates timestamp (if field not nullable)
						} else {
							echo $columns[$f]['IS_NULLABLE'] ? 'NULL':'DEFAULT'; // Only write NULL if field nullable
						}
					} else {
						if ($columns[$f]['DATA_TYPE'] == 'binary') {
							echo '0x', $value;
						} else {
							echo $link->quote($value); // just quote everything, even numbers
						}
					}
				} else {
					if ($columns[$f]['DATA_TYPE'] == "timestamp") {
						echo $columns[$f]['IS_NULLABLE'] ? 'DEFAULT':'NULL'; // NULL generates timestamp (if field not nullable)
					} else {
						echo $columns[$f]['IS_NULLABLE'] ? 'NULL':'DEFAULT'; // Only write NULL if field nullable
					}
				}
			}
			echo ")";

			$sql = ob_get_clean();
		}
		catch(Exception $e)	{
			ob_end_clean();
			throw $e;
		}

		return $sql;
	}


	/**
	 * Queries MySQL for the maximum allowed size (bytes) of a SQL statement (max_allowed_packet)
	 * @static
	 * @param PDO $link Database link
	 * @return int maximum allowed packet-size in bytes
	 */
	private static function getMaxAllowedPacket(PDO $link)
	{
		if(!array_key_exists('max_allowed_packet', self::$metadataCache))
		{
			self::$metadataCache['max_allowed_packet'] = DAO::getSingleValue($link, "SELECT @@max_allowed_packet");
		}

		return self::$metadataCache['max_allowed_packet'];
	}


	/**
	 * Returns an associative array describing the columns in the table
	 * @static
	 * @param PDO $link Database link
	 * @param string $table Table name
	 * @return array Associative array of arrays describing each column, keyed by the column name
	 * @throws Exception
	 */
	public static function getTableFields(PDO $link, $table)
	{
		if (array_key_exists($table.'_fields', self::$metadataCache)) {
			return self::$metadataCache[$table.'_fields'];
		}

		// TEMPORARY tables only show up using DESCRIBE
		$sql = "DESCRIBE $table;";
		$cols = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if (count($cols) > 0) {
			$rows = array();
			foreach($cols as $col) {
				$row = array();
				$row['COLUMN_NAME'] = $col['Field'];
				$row['DATA_TYPE'] = preg_replace('/[^A-Za-z ]/', '', $col['Type']);
				$row['IS_NULLABLE'] = $col['Null'] == 'YES' ? true:false;
				$row['COLUMN_KEY'] = $col['Key'];
				$row['COLUMN_DEFAULT'] = $col['Default'];
				$row['EXTRA'] = $col['Extra'];
				$rows[$col['Field']] = $row;
			}
			self::$metadataCache[$table.'_fields'] = $rows;
			return $rows;
		} else {
			throw new Exception("Unknown table '$table'", DAO::MYSQLI_UNKNOWN_TABLE);
		}
	}

	/**
	 * Queries MySQL for the primary-key fields of a table
	 * @static
	 * @param PDO $link Database link
	 * @param string $table Table name
	 * @throws Exception
	 * @return array Associative array of "information_schema.columns.extra" values, keyed by primary-key column name
	 */
	public static function getTablePrimaryKeys(PDO $link, $table)
	{
		if(array_key_exists($table.'_pri', self::$metadataCache)){
			return self::$metadataCache[$table.'_pri'];
		}

		$sql = "DESCRIBE $table;";
		$cols = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if(count($cols) > 0)
		{
			$lookup_table = array();
			foreach($cols as $col)
			{
				if($col['Key'] != 'PRI'){
					continue;
				}
				$lookup_table[$col['Field']] = $col['Extra'];
			}
			self::$metadataCache[$table.'_pri'] = $lookup_table;
			return $lookup_table;
		}
		else
		{
			throw new Exception("Unknown table '$table'", DAO::MYSQLI_UNKNOWN_TABLE);
		}
	}


	/**
	 * Returns a field name and value formatted suitably for inclusion in a SET clause of an INSERT or UPDATE statement.
	 * @static
	 * @param PDO $link Database link
	 * @param string $name Field name
	 * @param string $value Field value
	 * @param string $datatype Field datatype (as reported in information_schema.columns.data_type)
	 * @return string SQL
	 */
	private static function formatNameValuePair(PDO $link, $name, $value, $datatype)
	{
		// Trim strings
		if (is_string($value)) {
			$value = trim($value);
			$value = Text::utf8_to_latin1($value);
		}

		// Convert arrays to SQL SETs
		if (is_array($value)) {
			$value = implode(',', $value);
		}

		// Convert dates to MySQL format
		if ($datatype == 'date') {
			$value = Date::toMySQL($value);
		}

		if (is_null($value) || ($value === '') ) // NULL or empty string
		{
			return " `$name`=NULL ";
		}
		elseif($datatype == 'binary')
		{
			return " `$name`=0x" . $value . " "; // hexadecimal literal
		}
		elseif($datatype == 'varbinary')
		{
			return " `$name`=" . enc($link->quote($value)) . " "; // hexadecimal literal
		}
		else
		{
			return " `$name`=" . $link->quote($value). " "; // string and numeric data (both can use quotes)
		}
	}

	/**
	 * Tests whether a database schema entity is available.
	 * @static
	 * @param PDO $link Database link
	 * @param string $db (optional) Database name
	 * @param string $table (optional) Table name
	 * @param string $column (optional) Column name
	 * @param string $cache_key (optional) Access and/or refresh the result in the cache using the specified key
	 * @param int $cache_key_ttl (optional) Seconds before cache entry should expire
	 * @return boolean
	 * @throws Exception
	 */
	public static function schemaEntityExists(PDO $link, $db="", $table="", $column="", $cache_key=null, $cache_key_ttl=3600)
	{
		if ((!$db && !$table && !$column) || ($column && !$table)) {
			throw new Exception("At least one of the following arguments must be specified: {db}, {table} or {table and column}");
		}
		if (!$db) {
			if (defined('DB_NAME')) {
				$db = DB_NAME;
			} else {
				throw new Exception("Missing argument: \$db");
			}
		}

		if($column)
		{
			$sql = "SELECT 1 FROM information_schema.columns WHERE table_schema='".addslashes($db)."' AND table_name='".addslashes($table)."' AND column_name='".addslashes($column)."'";
		}
		elseif($table)
		{
			$sql = "SELECT 1 FROM information_schema.tables WHERE table_schema='".addslashes($db)."' AND table_name='".addslashes($table)."'";
		}
		elseif($db)
		{
			$sql = "SELECT 1 FROM information_schema.schemata WHERE schema_name='".addslashes($db)."'";
		}
		else
		{
			throw new Exception("At least one of the following arguments must be specified: {db}, {table} or {table and column}");
		}

		$result = DAO::getSingleValue($link, $sql, $cache_key, $cache_key_ttl);
		return $result ? true : false;
	}


	/**
	 * Returns a result set for a SELECT query, in the form of an array of arrays with each row keyed either
	 * by column number, column name or both.
	 * @static
	 * @param PDO $link Database connection
	 * @param string $sql SQL query
	 * @param integer $options (optional) How each row's array should be keyed. One of three constants: DAO::FETCH_NUM(default), DAO::FETCH_ASSOC or DAO::FETCH_BOTH
	 * @param string $cache_key (optional) Access and/or refresh the result in the cache using the specified key
	 * @param int $cache_key_ttl (optional) Seconds before cache entry should expire
	 * @return array an array of arrays
	 * @throws SQLException
	 */
	public static function getResultset(PDO $link, $sql, $options=DAO::FETCH_NUM, $cache_key=null, $cache_key_ttl=3600)
	{
		$sql = DAO::quickCheckForEncDec($sql);
		if($options != DAO::FETCH_ASSOC && $options != DAO::FETCH_NUM){
			$options = DAO::FETCH_BOTH; // Be cautious
		}
		if(!is_null($cache_key) && Cache::isAvailable())
		{
			$cache_key = $_SERVER['SERVER_NAME'].' '.$cache_key;
			$a = Cache::get($cache_key);
			if(is_null($a))
			{
				$a = DAO::getResultsetImplementation($link, $sql, $options);
				Cache::set($cache_key, $a, $cache_key_ttl);
			}
			return $a;
		}
		else
		{
			return DAO::getResultsetImplementation($link, $sql, $options);
		}
	}

	/**
	 * Internal implementation
	 * @static
	 * @param PDO $link Database connection
	 * @param string $query SQL query
	 * @param integer $options (optional) How each row's array should be keyed. One of three constants: DAO::FETCH_NUM(default), DAO::FETCH_ASSOC or DAO::FETCH_BOTH
	 * @return array
	 * @throws DatabaseException
	 */
	private static function getResultsetImplementation(PDO $link, $query, $options = DAO::FETCH_NUM)
	{
		$resultset = null;

		$st = $link->query($query);
		if($st)
		{
			if($st->errorCode() > 0){
				throw new DatabaseException($st, $query);
			}
			$resultset = $st->fetchAll($options);
		}
		else
		{
			throw new DatabaseException($link, $query);
		}

		return $resultset ? $resultset : array();
	}


	/**
	 * Retrieve a row from the database as an object.
	 * @static
	 * @param PDO $link Database connection
	 * @param string $sql SQL query
	 * @param string $cache_key (optional) Access and/or refresh the result in the cache using the specified key
	 * @param int $cache_key_ttl (optional) Seconds before cache entry should expire
	 * @return stdClass|null the first row of the resultset as an object (stdClass)
	 * @throws Exception if the SQL query returns more than one row (use LIMIT if this is likely to happen)
	 */
	public static function getObject(PDO $link, $sql, $cache_key=null, $cache_key_ttl=3600)
	{
		if(!is_null($cache_key) && Cache::isAvailable())
		{
			$cache_key = $_SERVER['SERVER_NAME'].' '.$cache_key;
			$a = Cache::get($cache_key);
			if(is_null($a))
			{
				$a = DAO::getObjectImplementation($link, $sql);
				Cache::set($cache_key, serialize($a), $cache_key_ttl);
			}
			else
			{
				$a = unserialize($a);
			}
			return $a;
		}
		else
		{
			return DAO::getObjectImplementation($link, $sql);
		}
	}

	/**
	 * Internal implementation method
	 * @static
	 * @param PDO $link Database link
	 * @param string $sql SQL query
	 * @return stdClass|null
	 * @throws DatabaseException
	 * @throws Exception
	 */
	private static function getObjectImplementation(PDO $link, $sql)
	{
		$obj = null;
		if (!$sql) {
			throw new Exception("Argument $sql cannot be null");
		}

		$st = $link->query($sql);
		if ($st)
		{
			if ($st->errorCode() > 0) {
				throw new DatabaseException($st, $sql);
			}
			$obj = $st->fetchObject();
			$st->closeCursor(); // Explicitly close the cursor in case the query returns more than one row
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		return $obj ? $obj : null;
	}


	/**
	 * Executes a SELECT query and returns the values from the first column as an array
	 * @static
	 * @param PDO $link Database connection
	 * @param string $sql SQL query
	 * @param string $cache_key (optional) Access and/or refresh the result in the cache using the specified key
	 * @param int $cache_key_ttl (optional) Seconds before cache entry should expire
	 * @return array the first column as an array of scalar values e.g. array(row1_val, row2_val, ..)
	 */
	public static function getSingleColumn(PDO $link, $sql, $cache_key=null, $cache_key_ttl=3600)
	{
		if(!is_null($cache_key) && Cache::isAvailable())
		{
			$cache_key = $_SERVER['SERVER_NAME'].' '.$cache_key;
			$a = Cache::get($cache_key);
			if(is_null($a))
			{
				$a = DAO::getSingleColumnImplementation($link, $sql);
				Cache::set($cache_key, $a, $cache_key_ttl);
			}
			return $a;
		}
		else
		{
			return DAO::getSingleColumnImplementation($link, $sql);
		}
	}

	/**
	 * Internal implementation method
	 * @static
	 * @param PDO $link Database link
	 * @param string $query
	 * @return array
	 * @throws Exception
	 * @throws DatabaseException
	 */
	private static function getSingleColumnImplementation(PDO $link, $query)
	{
		if($query == ''){
			throw new Exception('Argument $query cannot be null');
		}

		$value = null;

		$st = $link->query($query);
		if($st)
		{
			if($st->errorCode() > 0){
				throw new DatabaseException($st, $query);
			}
			$value = $st->fetchAll(PDO::FETCH_COLUMN, 0);
		}
		else
		{
			throw new DatabaseException($link, $query);
		}

		return $value ? $value : array();
	}

	public static function quickCheckForEncDec($sql)
	{
		return $sql;
		$search = array(
			"concat(firstnames, ' ', surname)",
			"concat(firstnames, \" \", surname)",
			"tr.`firstnames`",
			"tr.`surname`"
		);
		$replace = array(
			'CONCAT(' . dec('firstnames') . ', " ", ' . dec('surname') . ')',
			'CONCAT(' . dec('firstnames') . ', " ", ' . dec('surname') . ')',
			dec('firstnames', 'tr', true),
			dec('surname', 'tr', true)
		);
		return str_replace($search, $replace, $sql);
	}

	/**
	 * Executes a SELECT query and returns the result from the first column in the first row
	 * @static
	 * @param PDO $link Database connection
	 * @param string $sql SQL query
	 * @param string $cache_key (optional) Access and/or refresh the result in the cache using the specified key
	 * @param int $cache_key_ttl (optional) Seconds before cache entry should expire
	 * @return string the first value from a SQL query (first row, first column)
	 */
	public static function getSingleValue(PDO $link, $sql, $cache_key=null, $cache_key_ttl=3600)
	{
		$sql = DAO::quickCheckForEncDec($sql);
		if(!is_null($cache_key) && Cache::isAvailable())
		{
			$cache_key = $_SERVER['SERVER_NAME'].' '.$cache_key;
			$a = Cache::get($cache_key);
			if(is_null($a))
			{
				$a = DAO::getSingleValueImplementation($link, $sql);
				Cache::set($cache_key, $a, $cache_key_ttl);
			}
			return $a;
		}
		else
		{
			return DAO::getSingleValueImplementation($link, $sql);
		}
	}

	/**
	 * Internal implementation method
	 * @static
	 * @param PDO $link Database link
	 * @param string $query
	 * @return null|string
	 * @throws Exception
	 * @throws DatabaseException
	 */
	private static function getSingleValueImplementation(PDO $link, $query)
	{
		if($query == ''){
			throw new Exception('Argument $query cannot be null');
		}

		$value = null;

		$st = $link->query($query);
		if($st)
		{
			if($st->errorCode() > 0){
				throw new DatabaseException($st, $query);
			}
			$row = $st->fetch(PDO::FETCH_NUM);
			if ($row) {
				$value = $row[0];
			}
		}
		else
		{
			throw new DatabaseException($link, $query);
		}

		return $value;
	}

	/**
	 * Execute a single SQL statement that doesn't return a resultset e.g. UPDATE, INSERT, REPLACE, DELETE ...
	 * @static
	 * @param PDO $link Database link
	 * @param string $query
	 * @return integer number of rows affected by the query (rows updated or inserted)
	 * @throws Exception
	 * @throws DatabaseException
	 */
	public static function execute(PDO $link, $query)
	{
		if ($query == '') {
			throw new Exception('Argument $query cannot be null');
		}

		$affected_rows = $link->exec($query);
		if ($affected_rows === FALSE) {
			throw new DatabaseException($link, $query);
		}

		// Reset internal caches if this is a DDL query
		if (stripos($query, 'ALTER') === 0) {
			self::$metadataCache = array();
		}

		return $affected_rows;
	}


	/**
	 * Executes a query and returns a resultset. Checks for errors
	 * and throws a DatabaseException if the query is unsuccessful.
	 * Do not use this method
	 * for SQL statements that do not return a resultset.
	 * @static
	 * @param PDO $link
	 * @param string $query
	 * @return PDOStatement
	 * @throws DatabaseException
	 * @throws Exception
	 */
	public static function query(PDO $link, $query)
	{
		if (!preg_match('/^select\b/i', $query)) {
			throw new Exception("DAO::query() may only be used with SELECT queries.");
		}
		$st = $link->query($query);
		if (!$st) {
			throw new DatabaseException($link, $query);
		}
		return $st;
	}


	/**
	 * Returns an associative array, with values from the first column of a resultset mapped to values from the second column.
	 *
	 * Note on auto-casting of keys when creating associative arrays. PHP will cast
	 * the key to an integer where it can, but this makes no difference to
	 * accessing the value, as the key used to access the value will also be
	 * cast to an integer when possible.
	 * @static
	 * @param PDO $link Database connection
	 * @param string $sql SQL query
	 * @param string $cache_key (optional) Access and/or refresh the result in the cache using the specified key
	 * @param int $cache_key_ttl (optional) Seconds before cache entry should expire
	 * @return array associative array e.g. array('row1_col1_val'=>row1_col2_val, 'row2_col1_val'=>row2_col2_val, ..)
	 */
	public static function getLookupTable(PDO $link, $sql, $cache_key=null, $cache_key_ttl=3600)
	{
		if(!is_null($cache_key) && Cache::isAvailable())
		{
			$cache_key = $_SERVER['SERVER_NAME'].' '.$cache_key;
			$a = Cache::get($cache_key);
			if(is_null($a))
			{
				$a = DAO::getLookupTableImplementation($link, $sql);
				Cache::set($cache_key, $a, $cache_key_ttl);
			}
			return $a;
		}
		else
		{
			return DAO::getLookupTableImplementation($link, $sql);
		}
	}

	/**
	 * Implementation method
	 * @static
	 * @param PDO $link
	 * @param $query
	 * @return array
	 * @throws Exception
	 * @throws DatabaseException
	 */
	private static function getLookupTableImplementation(PDO $link, $query)
	{
		$table = array();
		$st = $link->query($query);
		if($st)
		{
			if($st->errorCode() > 0){
				throw new DatabaseException($st, $query);
			}

			if($st->columnCount() > 2)
			{
				while($row = $st->fetch(PDO::FETCH_BOTH)){
					$table[$row[0]] = $row;
				}
			}
			elseif($st->columnCount() == 2)
			{
				while($row = $st->fetch(PDO::FETCH_NUM)){
					$table[$row[0]] = DAO::cast($row[1]);
				}
			}
			else
			{
				throw new Exception("At least two columns are required for getLookupTable().");
			}
		}
		else
		{
			throw new DatabaseException($link, $query);
		}

		return $table;
	}


	/**
	 * Casts a string to either a float, integer or string
	 * @static
	 * @param string $v
	 * @return float|int|string
	 */
	private static function cast($v)
	{
		if(is_numeric($v))
		{
			if(strlen($v>8))
			{
				$v = (string) $v; // Cast telephone numbers to strings?
			}
			elseif(ctype_digit($v)) // all digits, no punctuation
			{
				$v = (int) $v;
			}
			else
			{
				$v = (float) $v;
			}
		}

		return $v;
	}


	/**
	 * Formats a value or an array of values so that it is suitable for inclusion in
	 * SQL queries. Strings are quoted and escaped, numbers are left unquoted and
	 * NULL values are rendered as an unquoted NULL.
	 * Individual array values are comma separated, producing a string suitable for
	 * inclusion in SQL's IN (...) operator.
	 * Nested arrays are merged with the parent array. Objects are converted to strings
	 * using the object's __toString() method.
	 * @static
	 * @param mixed $value Scalar value or an array of values (nested arrays supported)
	 * @throws Exception If the value's datatype is unsupported
	 * @return string
	 */
	public static function quote($value)
	{
		// Cast $value as an array
		if(is_null($value)) {
			$value = array(null);
		} else {
			$value = (array) $value;
		}

		$str = '';
		foreach ($value as $element) {
			if (is_object($element)) {
				$element = $element->__toString(); // convert object to string
			}
			if (is_numeric($element)) {
				$str .= $element;
			} else if (is_string($element)) {
				$str .= "'" . addslashes($element) . "'";
			} else if (is_null($element)) {
				$str .= 'NULL';
			} else if (is_array($element)) {
				$str .= DAO::quote($element);
			} else {
				throw new Exception("Unsupported datatype");
			}
			$str .= ',';
		}
		return trim($str, ',');
	}

	/**
	 * Please use DAO::implode() in future.
	 * @static
	 * @deprecated
	 * @uses DAO::implode
	 * @param array $array
	 * @return string
	 */
	public static function pdo_implode(array $array)
	{
		return DAO::quote($array);
	}

	/**
	 * Start a transaction
	 * @static
	 * @param PDO $link Database link
	 * @throws DatabaseException
	 */
	public static function transaction_start(PDO $link)
	{
		if($link->beginTransaction() == false)
		{
			throw new DatabaseException($link);
		}
	}

	/**
	 * Rollback a transaction
	 * @static
	 * @param PDO $link Database link
	 * @param Exception $e No longer used
	 * @throws DatabaseException
	 * @throws PDOException if there is no active transaction
	 */
	public static function transaction_rollback(PDO $link, Exception $e = null)
	{
		if ($link->rollBack() == false) {
			throw new DatabaseException($link);
		}
	}

	/**
	 * Commit a transaction
	 * @static
	 * @param PDO $link Database link
	 * @throws DatabaseException
	 * @throws PDOException if there is no active transaction
	 */
	public static function transaction_commit(PDO $link)
	{
		if ($link->commit() == false) {
			throw new DatabaseException($link);
		}
	}

	/**
	 * Safe key/property exists checker
	 *
	 * @param string|int $key   The key/property name
	 * @param array|object|null $data   The array or object to check
	 * @return bool
	 */
	private static function key_exists_safe($key, $data): bool
	{
		if (is_array($data)) {
			return array_key_exists($key, $data);
		}
		if (is_object($data)) {
			return property_exists($data, $key);
		}
		return false; // if null or not array/object
	}

	const PDO_DUPLICATE_KEY = 1062;
	const PDO_UNKNOWN_TABLE = 1051;
	const PDO_COLUMN_CANNOT_BE_NULL = 1048;

	private static $metadataCache = array();
	private static $connections = array();

	const MYSQLI_DUPLICATE_KEY = 1062;
	const MYSQLI_UNKNOWN_TABLE = 1051;
	const MYSQLI_COLUMN_CANNOT_BE_NULL = 1048;
	const MYSQLI_LOCK_DEADLOCK = 1213;
	const MYSQLI_LOCK_WAIT_TIMEOUT = 1205;

	const MEGABYTE = 1048576;

	const FETCH_ASSOC = PDO::FETCH_ASSOC;
	const FETCH_NUM = PDO::FETCH_NUM;
	const FETCH_BOTH = PDO::FETCH_BOTH;
}