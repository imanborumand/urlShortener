<?php namespace Src\Http\Controllers;

use Src\Modules\Cache\CacheFactory;
use Src\Modules\Cache\Contract\CacheInterface;
use Src\Modules\DB;

abstract class BaseController
{
	protected DB $db;
	
	protected CacheInterface $cache;
	
	public function __construct()
	{
		$this->db = DB::getInstance();
		
		
		$cache = new CacheFactory();
		$this->cache = $cache->build(CACHE_TYPE);
		
	}
	
}