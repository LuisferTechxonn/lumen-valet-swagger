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



$router->get('/', function () use ($router) {
    return $router->app->version().' Swagger Edition';
});

/**
 * Rutas de Cars
 */
$router->get('/api/cars','CarsController@getAll');
$router->get('/api/cars/{car_id}','CarsController@getById');
$router->put('/api/cars/{car_id}','CarsController@putCar');
$router->patch('/api/cars/{car_id}','CarsController@patchCar');
$router->delete('/api/cars/{car_id}','CarsController@deleteCar');
$router->post('/api/cars/{car_id}','CarsController@postCar');


