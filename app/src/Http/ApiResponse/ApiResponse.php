<?php namespace Src\Http\ApiResponse;

class ApiResponse
{
	
	
	/**
	 * @param mixed  $data
	 * @param string $key
	 * @param int    $statusCode
	 * @return string
	 */
	public static function apiResponse( mixed $data, string $key = 'success', int $statusCode = 200) : string
	{
		http_response_code($statusCode);
		echo json_encode(['data' => $data, 'message' => DEFAULT_LANGUAGE[$key] ?? 'Success']);
		exit;
	}
	
}