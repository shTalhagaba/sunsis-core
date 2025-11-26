<?php

/**
 * Contains the methods for the management of the system.
 *
 *  - which functional areas are active
 *  - system specific functional areas
 */
class SystemConfig
{
	private static $lookup_table = array();

	/**
	 * @static
	 * @param PDO $link
	 * @param string $key
	 * @return string|null
	 */
	public static function getEntityValue(PDO $link, $key)
	{
		if (!self::$lookup_table) {
			self::$lookup_table = DAO::getLookupTable($link, "SELECT DISTINCT entity,value FROM configuration", "system config");
		}
		return array_key_exists($key, self::$lookup_table) ? self::$lookup_table[$key] : null;
	}

	/**
	 * @static
	 * @param PDO $link
	 * @param string $key
	 * @param mixed $value
	 * @throws Exception
	 */
	public static function setEntityValue(PDO $link, $key, $value)
	{
		if (!is_string($key)) {
			throw new Exception("SystemConfig keys must be strings");
		}
		if (is_null($value)) {
			$value = '';
		}
		if (!is_string($value) && !is_numeric($value)) {
			throw new Exception("SystemConfig values must be strings or numbers");
		}
		$key = $link->quote($key);
		$value = $link->quote($value);
		DAO::execute($link, "REPLACE INTO configuration (entity,value) VALUES ($key, $value)");

		DAO::removeCacheKey("system config");
		static::$lookup_table = null;
	}


	/**
	 * Alternative method to getEntityValue() that uses a global
	 * database reference rather than an explicit one.
	 * @static
	 * @param string $key
	 * @return string|null
	 * @throws Exception
	 * @uses getEntityValue()
	 */
	public static function get($key)
	{
		$link = DAO::getConnection();
		return static::getEntityValue($link, $key);
	}

	/**
	 * @static
	 * @param string $key
	 * @param mixed $value
	 */
	public static function set($key, $value)
	{
		$link = DAO::getConnection();
		static::setEntityValue($link, $key, $value);
	}

	/**
	 * @static
	 * @return bool
	 */
	public static function setIncludePath()
	{
		$cache_key = $_SERVER['SERVER_NAME'].' system config ini path';
		$ini_path = Cache::get($cache_key);

		if (!$ini_path){
			$ini_path = static::_buildIncludePath();
			Cache::set($cache_key, $ini_path);
		}
		set_include_path(get_include_path().PATH_SEPARATOR.$ini_path);

		return true;
	}

	/**
	 * TODO We should not require a database lookup to build the PHP include path. Can we hardcode this instead?
	 * @static
	 * @return string
	 */
	private static function _buildIncludePath()
	{
		$link = DAO::getConnection();
		$ini_path = '';
		$config = DAO::getLookupTable($link, "SELECT DISTINCT entity,value FROM configuration", "system configs");

		foreach ( $config as $configuration_setting => $configuration_value )
		{
			//if(strpos($configuration_setting, "module_") === 0 && $configuration_value)
			{
				/*if ( !is_dir(WEBROOT.$configuration_setting."/actions") || !is_dir(WEBROOT.$configuration_setting."/templates") )
				{
					//DAO::execute($link, "UPDATE configuration SET value='0' WHERE entity='".addslashes($configuration_setting)."';");
					Cache::remove("ini path");
					Cache::remove("system config");
				}
				else*/
				{
					$ini_path .= PATH_SEPARATOR.'./'.$configuration_setting.'/actions'.PATH_SEPARATOR.'./'.$configuration_setting.'/templates';
				}
			}
		}

		return trim($ini_path, PATH_SEPARATOR);
	}
}