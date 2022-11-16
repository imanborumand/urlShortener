<?php namespace Src\Modules;

/*
 * create short code
 */
class Shortener
{
	
	private int $length = 5;
	
	
	protected string $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
	
	/**
	 * @param $url
	 * @return string
	 */
	public function createShortCode( $url) : string
	{
		return $this->generateRandomString($this->length);
	}
	
	
	/**
	 * @param int $length
	 * @return string
	 */
	protected function generateRandomString( int $length = 6) : string
	{
		$sets = explode('|', $this->chars);
		$all = '';
		$randString = '';
		foreach ($sets as $set) {
			$randString .= $set[array_rand(str_split($set))];
			$all .= $set;
		}
		$all = str_split($all);
		for ($i = 0; $i < $length - count($sets); $i++) {
			$randString .= $all[array_rand($all)];
		}
		
		return str_shuffle($randString);
	}
	
	
}