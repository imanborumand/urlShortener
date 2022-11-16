<?php namespace Src\Modules\Cache\CacheModels;

use Src\Modules\Cache\Contract\CacheInterface;

class File implements CacheInterface
{
	
	private string $cacheDir = '/tmp/cache';
	
	
	public function __construct(array $options = [])
	{
		$available_options = array('cache_dir');
		foreach ($available_options as $name) {
			if (isset($options[$name])) {
				$this->$name = $options[$name];
			}
		}
	}
	
	
	/**
	 *
	 * @param string $name
	 * @return false|mixed
	 */
	public function get(string $name) : mixed
	{
		$file_name = $this->getFileName($name);
		
		if (!is_file($file_name) || !is_readable($file_name)) {
			return false;
		}
		
		$lines    = file($file_name);
		$lifetime = array_shift($lines);
		$lifetime = (int) trim($lifetime);
		
		if ($lifetime !== 0 && $lifetime < time()) {
			@unlink($file_name);
			return false;
		}
		$serialized = join('', $lines);
		return unserialize($serialized);
	}
	
	
	/**
	 * @param string $name
	 * @param mixed  $data
	 * @param int    $lifetime
	 * @return bool
	 */
	public function set(string $name, mixed $data, int $lifetime = 3600) : bool
	{
		$dir = $this->getDirectory($name);
		if (!is_dir($dir)) {
			if (!mkdir($dir, 0755, true)) {
				return false;
			}
		}
		$file_name  = $this->getFileName($name);
		$lifetime   = time() + $lifetime;
		$serialized = serialize($data);
		$result     = file_put_contents($file_name, $lifetime . PHP_EOL . $serialized);
		if ($result === false) {
			return false;
		}
		return true;
	}
	
	
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function remove(string $name) : bool
	{
		$file_name = $this->getFileName($name);
		return unlink($file_name);
	}
	
	
	/**
	 *
	 * @param string $name
	 * @return string
	 */
	protected function getDirectory(string $name) : string
	{
		$hash = sha1($name, false);
		$dirs = array(
			$this->getCacheDirectory(),
			substr($hash, 0, 2),
			substr($hash, 2, 2)
		);
		return join(DIRECTORY_SEPARATOR, $dirs);
	}
	
	/**
	 *
	 * @return string
	 */
	protected function getCacheDirectory() : string
	{
		return $this->cacheDir;
	}
	
	
	/**
	 *
	 * @param string $name
	 * @return string
	 */
	protected function getFileName(string $name) : string
	{
		$directory = $this->getDirectory($name);
		$hash      = sha1($name, false);
		return     $directory . DIRECTORY_SEPARATOR . $hash . '.cache';
	}
}