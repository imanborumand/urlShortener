<?php namespace Src\Http\Middlewares;

use Src\Http\ApiResponse\ApiResponse;
use Src\Modules\DB;

class AuthMiddleware extends BaseMiddleware
{
	
	
	/**
	 * @return bool
	 */
	public static function check() : bool
	{
		$token = self::getTokenFromHeader();
		if (!$token) {
			ApiResponse::apiResponse([], 'add_token', 401);
		}
		
		if (!self::checkUserByToken($token)) {
			ApiResponse::apiResponse([], 'not_access', 401);
		}
		
		return true;
	}
	
	
	/**
	 * @param string $token
	 * @return array|null
	 */
	private static function checkUserByToken(string $token) : ?array
	{
		return  DB::getInstance()->table(USER_TABLE)->select()
				   ->where('token', $token)->first();
	}
	
	
	/**
	 * @return mixed
	 */
	public static function getTokenFromHeader() : mixed
	{
		return  getallheaders()['Token'] ?? null;
	}
}