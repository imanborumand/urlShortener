<?php namespace Src\Http\Controllers;

use Src\Http\ApiResponse\ApiResponse;
use Src\Http\Validators\FormValidator;


class AuthController extends BaseController
{
	
	
	/**
	 * login user by email and pass
	 * @return string
	 * @throws \Exception
	 */
	public function login() : string
	{
		if (!$this->checkUserForLogin()) {
			return	ApiResponse::apiResponse([], 'not_valid_user_or_pass', 400);
		}
		
		$token = generateRandomStr(30);
		
		$updateToken = $this->db->table(USER_TABLE)
			->update(['token' => $token])->commit();
		if (!$updateToken) {
			return ApiResponse::apiResponse([], 'server_error', 500);
		}
		
		
		return ApiResponse::apiResponse(['token' => $token]);
	}
	
	
	/**
	 * register user
	 * @return string
	 */
	public function register() : string
	{
		$params = $this->validateData();
		
		if ($this->getUserByEmail($params['email'])) {
			return ApiResponse::apiResponse([], 'email_has_exists', 400);
		}
	
		$params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);
		
		
		if (
			!$this->db->table(USER_TABLE)
					  ->insert( $params)
					  ->commit()
		) {
			return ApiResponse::apiResponse([], 'server_error', 500);
		}
		
		return ApiResponse::apiResponse([], 'reg_ok');
	}
	
	
	/**
	 * @return string
	 */
	public function logout() : string
	{
		$user = (new LinkController())->getUserByToken();
		if (
			$this->db->table(USER_TABLE)
				->where('id', $user['id'])
			->update(['token' => null])
			->commit()
		) {
			return ApiResponse::apiResponse([], 'logout_ok');
		}
		return ApiResponse::apiResponse([], 'server_error', 500);
	}
	
	
	/**
	 * @return string
	 */
	public function profile() : string
	{
		$user = (new LinkController())->getUserByToken();
		if ($user) {
			unset($user['password'], $user['token'] );
			return ApiResponse::apiResponse($user, 'logout_ok');
		}
		return ApiResponse::apiResponse([], 'server_error', 500);
	}
	
	
	/**
	 * check user
	 * @return bool
	 */
	private function checkUserForLogin() : bool
	{
		$params = $this->validateData();
		$user = $this->getUserByEmail($params['email']);
		if (!$user) {
			return	false;
		}
		
		if (!password_verify($params['password'], $user['password'])) {
			return false;
		}
		return true;
	}
	
	
	/**
	 * get user by email form DB
	 * @param string $email
	 * @return array|null
	 */
	private function getUserByEmail( string $email) : ?array
	{
		return  $this->db->table(USER_TABLE)->select()
						 ->where('email', $email)
						 ->first();
	}
	
	/**
	 * validate request data in login and register step
	 * @return array
	 */
	protected function validateData() : array
	{
		$validation = new FormValidator();
		$data       = getJsonRequest();
		
		$validation->setName('email')
				   ->setValue($data['email'] ?? null)
				   ->isEmail()
				   ->required();
		
		$validation->setName('password')
				   ->setValue($data['password'] ?? null)
				   ->isString()
				   ->min(6)
				   ->max(30)
				   ->required();
		
		if (!$validation->isSuccess()) {
			ApiResponse::apiResponse($validation->getErrors(), 'error_validation', 400);
		}
		return $validation->validated;
	}
	
}








