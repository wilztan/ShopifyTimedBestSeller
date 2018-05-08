<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
//
// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->get('/','HomeController@get');

$router->get('/product','ProductController@index');

/**
* Best Seller per Tags
* Type = name {Never Change for further Use}
* Tags = 0 for general
* time xxx days
* ie /bestseller/BestSellingThisMonth/0/30
*/
$router->get('/bestseller/{type}/{tags}/{time}','CollectionController@getBestSeller');
