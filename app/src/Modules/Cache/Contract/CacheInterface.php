<?php namespace Src\Modules\Cache\Contract;


interface CacheInterface
{
	
	/**
	 * Fetches an entry from the cache.
	 * @param string $name
	 * @return mixed
	 */
	public function get( string $name) : mixed;
	
	
	/**
	 * @param string $name
	 * @param mixed  $data
	 * @param int    $lifetime
	 * @return bool
	 */
	public function set( string $name, mixed $data, int $lifetime = 3600) : bool;
	
	
	/**
	 * remove a cache entry.
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function remove( string $name) : bool;
	
	
}