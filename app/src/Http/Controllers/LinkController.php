<?php namespace Src\Http\Controllers;





use Src\Http\ApiResponse\ApiResponse;
use Src\Http\Middlewares\AuthMiddleware;
use Src\Http\Resources\Link\LinkCollection;
use Src\Http\Validators\FormValidator;
use Src\Modules\Shortener;

class LinkController extends BaseController
{
	
	private bool $codeAccepted = false;
	
	
	/**
	 * create link
	 *
	 * @param array $params
	 * @return string
	 */
	public function createLink(array $params) : string
	{
		$user = $this->getUserByToken();
		return ApiResponse::apiResponse([
			 'url' => 'http://localhost:8082/' . $this->createCode( $user['id'] , $params['url'] )
		]);
	}
	
	
	/**
	 * list links of user
	 *
	 * @return string
	 */
	public function listLinks() : string
	{
		$user = $this->getUserByToken();
		
		$list = $this->db->table( LINK_TABLE )
						 ->select()
						 ->where( 'user_id' , $user['id'] )
						 ->orderBy( 'id' , 'desc' )->paginate();
		
		return ApiResponse::apiResponse( LinkCollection::toArray($list));
	}
	
	
	/**
	 * update link
	 *
	 * @param array $params
	 * @return string
	 */
	public function updateLink(array $params) : string
	{
		$user   = $this->getUserByToken();
		
		$link = $this->checkLinkAndAccess($user['id'], $params['id']);
		$this->db->table( LINK_TABLE )
				 ->where('id' , $link['id'])
				 ->update(['full_url' => $params['url']])
				 ->commit();
		
		return ApiResponse::apiResponse( [] , 'link_update' );
	}
	
	
	/**
	 * @param array $params
	 * @return string
	 */
	public function deleteLink(array $params) : string
	{
		$user   = $this->getUserByToken();
		$link = $this->checkLinkAndAccess($user['id'], $params['id']);
		
		$this->db->table(LINK_TABLE)
			->where('id', $link['id'])
			->delete()->commit();
		return ApiResponse::apiResponse([]);
	}
	
	
	/**
	 * @param int    $userId
	 * @param string $url
	 * @return string
	 */
	public function createCode( int $userId , string $url ) : string
	{
		//start transaction db
		$this->db->beginTransaction();
		try {
			$code = $this->createShortLinks($url);
			if (!$code ) {
				throw new \Exception();
			}
			
			$this->db->table(LINK_TABLE)
					 ->insert([
						'user_id'  => $userId,
						'full_url' => $url,
						'code'     => $code
					])->commit();
			
			$this->db->commitTransaction();
			
		} catch( \Exception ) {
			$this->db->rollbackTransaction();
			
			return ApiResponse::apiResponse([], 'server_error', 500);
		}
		
		return $code;
	}
	
	
	/**
	 * Checking the existence of the code in 20 rounds
	 * If the code was there and could not create a new code, they will return  false!
	 *
	 * @param string $url
	 * @return string|null
	 */
	private function createShortLinks( string $url ) : ?string
	{
		$shortener = new Shortener();
		$code      = $shortener->createShortCode( $url );
		$counter   = 0;
		
		while( $this->codeAccepted === false ) {
			
			$check = $this->db
				->table( LINK_TABLE )
				->select( 'code' )
				->where( 'code' , $code )
				->first();
			
			if( !$check ) {
				$this->codeAccepted = true;
				
				return $code;
			}
			
			$counter++;
			if( $counter == 20 ) {
				return false;
			}
			
		}
		return false;
	}
	
	
	
	/**
	 * @return array
	 */
	public function getUserByToken() : array
	{
		return $this->db->table( USER_TABLE )
						->select()
						->where( 'token' , AuthMiddleware::getTokenFromHeader())
						->first();
	}
	
	
	/**
	 * @param int $userId
	 * @param int $id
	 * @return array|string
	 */
	private function checkLinkAndAccess( int $userId, int $id) : array|string
	{
		$link = $this->db->table(LINK_TABLE)
						 ->select()
						 ->where('id', $id)
						 ->first();
		
		if (!$link ) {
			return ApiResponse::apiResponse( [] , 'not_found' , 404 );
		}
		if ($userId != $link['user_id']) {
			return ApiResponse::apiResponse( [] , 'not_access' , 403 );
		}
		
		return $link;
	}
}



