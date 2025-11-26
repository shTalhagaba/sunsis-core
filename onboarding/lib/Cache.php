<?php
/**
 * Wrapper for cache functions (tested with XCache only)
 * @author ianss
 */
class Cache
{
	public static function isAvailable()
	{
		if (PHP_SAPI == 'cli') {
			return false;
		}

		return ( (extension_loaded("XCache") && ini_get("xcache.var_size") > 0)) || extension_loaded("apc");
	}
	
	public static function get($key)
	{
		if(extension_loaded("XCache"))
		{
			return xcache_get($key);
		}
		elseif(extension_loaded("apc"))
		{
			return apc_fetch($key);
		}
		else
		{
			return null;
		}
	}
	
	public static function set($key, $value, $ttl = 3600)
	{
		if(!is_string($key)){
			throw new Exception("Key must be a string");
		}
		if(is_object($value) || is_resource($value)){
			throw new Exception("Cache supports values that are numbers or strings, or arrays thereof");
		}
		
		if(extension_loaded("XCache"))
		{
			xcache_set($key, $value, $ttl);
		}
		elseif(extension_loaded("apc"))
		{
			apc_store($key, $value, $ttl);
		}
	}
	
	public static function keyExists($key)
	{
		if(!$key){
			return false;
		}
		if(extension_loaded("XCache"))
		{
			return xcache_isset($key);
		}
		elseif(extension_loaded("apc"))
		{
			return apc_exists($key);
		}
		else
		{
			return false;
		}
	}
	
	public static function remove($key)
	{
		if(!$key){
			return;
		}
		if(extension_loaded("XCache"))
		{
			xcache_unset($key);
		}
		elseif(extension_loaded("apc"))
		{
			apc_delete($key);
		}
	}
	
	/**
	 * XCache specific method
	 * @param string $prefix
	 */
	public static function removeByPrefix($prefix)
	{
		if(!$prefix){
			return;
		}
		if(extension_loaded("XCache"))
		{
			xcache_unset_by_prefix($prefix);
		}
		elseif(extension_loaded("apc"))
		{
			$keys = new APCIterator('user', '/^'.$prefix.'/', APC_ITER_KEY);
			foreach($keys as $key)
			{
				apc_delete($key);
			}
		}			
	}
	
	public static function clear()
	{
		if(extension_loaded("XCache"))
		{
			xcache_clear_cache(XC_TYPE_VAR, 0);
		}
		elseif(extension_loaded("apc"))
		{
			apc_clear_cache('user');
		}
	}
}