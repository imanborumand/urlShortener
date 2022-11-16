<?php  namespace Src\Http\Middlewares;

abstract class BaseMiddleware
{
	
	/**
	 * this method check request
	 * @return bool
	 */
	public static abstract function check() :bool;
	
}