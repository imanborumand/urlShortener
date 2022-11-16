<?php



if(! function_exists('generateRandomStr')) {
	/**
	 * this function generate random string
	 * for use in login step or other locate
	 * @param        $length
	 * @param string $keySpace
	 * @return string
	 * @throws Exception
	 */
	function generateRandomStr( $length, $keySpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') : string
	{
		$pieces = [];
		$max = mb_strlen($keySpace, '8bit') - 1;
		for ($i = 0; $i < $length; ++$i) {
			$pieces []= $keySpace[random_int(0, $max)];
		}
		return implode('', $pieces);
	}
}

if (!function_exists('getJsonRequest')) {
	
	/**
	 * @return mixed
	 */
	function getJsonRequest() : mixed
	{
		return json_decode(file_get_contents('php://input'), true);
	}
}


