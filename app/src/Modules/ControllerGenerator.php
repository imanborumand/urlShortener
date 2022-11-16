<?php

namespace Src\Modules;

use Src\Http\Controllers\BaseController;
use Src\Http\Middlewares\BaseMiddleware;
use Src\Http\Validators\RequestValidator;

class ControllerGenerator
{
	
	private ?array $params = null;
	
	/**
	 * this method return method of controllers
	 * @param BaseController $controller
	 * @param string         $methodName
	 * @return mixed
	 */
	public function runMethodOfControllers( BaseController $controller, string $methodName) : mixed
	{
		if($this->params) {
			return $controller->$methodName($this->params);
		}
		return $controller->$methodName();
	}
	
	
	/**
	 * @param BaseMiddleware $middleware
	 * @return $this
	 */
	public function middleware( BaseMiddleware $middleware) : self
	{
		$middleware::check();
		return $this;
	}
	
	
	/**
	 * @param RequestValidator $validator
	 * @return $this
	 */
	public function request( RequestValidator $validator) : static
	{
		$this->params = $validator->rules();
		return $this;
	}
}