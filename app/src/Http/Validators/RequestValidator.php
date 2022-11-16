<?php namespace Src\Http\Validators;




use Src\Http\ApiResponse\ApiResponse;

abstract class RequestValidator
{
	
	protected FormValidator $formValidator;
	
	public function __construct()
	{
		$this->formValidator = new FormValidator();
	}
	
	
	/**
	 * check validate method
	 * @param string $method
	 * @return void
	 */
	public static function validateRequestMethod( string $method) : void
	{
		if (strtoupper($method) != $_SERVER['REQUEST_METHOD']) {
			ApiResponse::apiResponse([],'method_not_allow', 405);
		}
	}
	
	
	public abstract function rules();
	
	
	
}