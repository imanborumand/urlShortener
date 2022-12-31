<?php

use Src\Http\Controllers\AuthController;
use Src\Http\Controllers\HomeController;
use Src\Http\Controllers\LinkController;
use Src\Http\Middlewares\AuthMiddleware;
use Src\Http\Validators\Requests\CreateLinkRequest;
use Src\Http\Validators\Requests\DeleteLinkRequest;
use Src\Http\Validators\Requests\UpdateLinkRequest;
use Src\Http\Validators\RequestValidator;
use Src\Modules\ControllerGenerator;

//check method validator for all routes
$url = $_SERVER['REQUEST_URI'];

if(str_contains($url, '?')) {
	$url = explode('?', $url)[0];
}

if(isset($config['routes'][str_replace('/', '', $url)])) {
	RequestValidator::validateRequestMethod(
		$config['routes'][str_replace('/', '', $url)]['method']
	);
}

$url = explode('/', $url);
if(count($url) > 1) {
	$url = '/' . $url[1];
}




$controllerGenerator = new ControllerGenerator();
return  match($url) {
	'/create-link' => $controllerGenerator->middleware( new AuthMiddleware())
		->request(new CreateLinkRequest())
		->runMethodOfControllers(new LinkController(), 'createLink'),
	
	'/list-links' =>  $controllerGenerator->middleware( new AuthMiddleware())
										  ->runMethodOfControllers(new LinkController(), 'listLinks'),
	
	'/update-link' => $controllerGenerator->middleware( new AuthMiddleware())
		->request(new UpdateLinkRequest())
		->runMethodOfControllers(new LinkController(), 'updateLink'),
	
	'/delete-link' => $controllerGenerator->middleware( new AuthMiddleware())
		->request(new DeleteLinkRequest())
		->runMethodOfControllers(new LinkController(), 'deleteLink'),
	
	
	'/login' =>  $controllerGenerator->runMethodOfControllers(new AuthController(), 'login'),
	'/register' =>  $controllerGenerator->runMethodOfControllers(new AuthController(), 'register'),
	
	'/logout' =>  $controllerGenerator->middleware( new AuthMiddleware())
									  ->runMethodOfControllers(new AuthController(), 'logout'),

	
	default =>  $controllerGenerator->runMethodOfControllers(new HomeController(), 'home'),
};
