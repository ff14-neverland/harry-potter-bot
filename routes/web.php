<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

//API Part
$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get('status', ['uses' => 'ApiController@showStatus']);
$router->get('battle', ['uses' => 'ApiController@startBattle']);

//UI Part
$router->get('/', ['uses' => 'UiController@showIndex']);
$router->post('/ui/battle', ['uses' => 'UiController@getBattleResult']);