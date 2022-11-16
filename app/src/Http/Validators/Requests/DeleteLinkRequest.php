<?php namespace Src\Http\Validators\Requests;

use Src\Http\ApiResponse\ApiResponse;
use Src\Http\Middlewares\AuthMiddleware;
use Src\Http\Validators\RequestValidator;

class DeleteLinkRequest extends RequestValidator
{
	
	
	/**
	 * @return array
	 */
	public function rules() : array
	{
		$data       = $_GET;
		
		$this->formValidator->setName( 'id' )
							->setValue( $data['id'] ?? null )
							->isNumber()
							->required();
		
		if( !$this->formValidator->isSuccess() ) {
			ApiResponse::apiResponse( $this->formValidator->getErrors() , 'error_validation' , 400 );
		}
		
		$this->formValidator->validated['token'] = AuthMiddleware::getTokenFromHeader();
		
		return $this->formValidator->validated;
	}
}