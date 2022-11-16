<?php namespace Src\Modules\Cache;


use Src\Modules\Cache\CacheModels\File;
use Src\Modules\Cache\Contract\CacheInterface;

class CacheFactory
{
	
	/**
	 * @param string $type
	 * @return CacheInterface
	 */
	public function build( string $type) : CacheInterface
	{
		return match ($type) {
			'file' => new File(),
//			'redis' => //todo add redis
		};
	}
	
}