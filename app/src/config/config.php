<?php

const DB_HOST = 'url-shortener-mysql'; // set database host
const DB_USER = 'root'; // set database user
const DB_PASS = 'secret'; // set database password
const DB_NAME = 'shortener'; // set database name
const DISPLAY_DEBUG = true; //display db errors?


const CACHE_TYPE = 'file';


//tables
const USER_TABLE = 'users';
const LINK_TABLE = 'links';


$config = [
	'lang' => 'en',
	
	'url' => 'http://localhost:8082',
	
	'routes' => [
		'login' => [
			'path' => 'login',
			'method' => 'POST',
			'parameters' => [
				'email' => ['email', 'required', 'string'],
				'password' => ['string', 'min:6', 'required']
			]
		],
		'register' => [
			'path' => 'register',
			'method' => 'POST',
			'parameters' => [
				'email' => ['email', 'required', 'string'],
				'password' => ['string', 'min:6', 'required']
			]
		],
		'create-link' => [
			'path' => 'create-link',
			'method' => 'POST',
			'parameters' => [
				'url' => ['url', 'required'],
			]
		],
		'delete-link' => [
			'path' => 'delete-link',
			'method' => 'DELETE',
		],
		'update-link' => [
			'path' => 'update-link',
			'method' => 'PUT',
		]
	]
];
