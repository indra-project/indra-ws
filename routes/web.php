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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1', 'namespace' => 'Api\V1'], function() use ($router) {
    //Stations routes
    $router->get('stations', 'StationsController@index');
    $router->get('stations/{id}', 'StationsController@show');
    $router->post('stations', 'StationsController@store');
    $router->post('stations/{id}', 'StationsController@update');
    $router->delete('stations/{id}', 'StationsController@destroy');
    $router->get('stations/{mac}/sensor-data', 'StationsController@sensor_data');

    //Station config
    $router->get('stations/{mac}/config', 'StationsController@config');

    //Sensors routes
    $router->get('stations/{mac}/sensors', 'SensorController@index');
    $router->get('stations/{mac}/sensors/{type}', 'SensorController@show');
    $router->post('stations/{mac}/sensors', 'SensorController@store');
    $router->post('stations/{mac}/sensors/{type}', 'SensorController@update');
    $router->delete('stations/{mac}/sensors/{type}', 'SensorController@destroy');

    //Sensor data
    $router->post('stations/{mac}/sensors/{type}/data', 'SensorController@data');


});
