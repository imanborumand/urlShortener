<?php

namespace Src\Http\Controllers;

use Src\Http\ApiResponse\ApiResponse;

class HomeController extends BaseController
{
	
	/**
	 * redirect to full_url
	 * @return string|void
	 */
	public function home()
	{
		$explodeCode = explode('/',  $_SERVER['REQUEST_URI']);
		$code = trim($explodeCode[1]);
		
		if (!isset($code[1])) {
			return ApiResponse::apiResponse([], 'link_not_valid', 400);
		}

		//check from cache
		$checkCache = $this->cache->get($code);
		if($checkCache) {
			$this->redirect($checkCache);
		}
		
		$link = $this->checkCode($code);
		
		//add to Cache
		$this->cache->set($code, $link['full_url']);
		$this->redirect($link['full_url']);
	}
	
	
	/**
	 * @param string $url
	 * @return void
	 */
	private function redirect( string $url)
	{
		header('Location: '.$url);
		die();
	}
	
	
	/**
	 *check code Validate
	 * @param string $code
	 * @return array|string
	 */
	private function checkCode( string $code) : array|string
	{
		$link = $this->db->table(LINK_TABLE)
						 ->select(['full_url', 'code'])
						 ->where('code', $code)
						 ->first();
		if (!$link) {
			return ApiResponse::apiResponse([], 'link_not_valid', 400);
		}
		return $link;
	}
	
	
	
	
}