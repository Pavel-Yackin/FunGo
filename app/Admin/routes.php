<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

    $router->resource('partners', PartnerController::class);
    $router->resource('partner-types', PartnerTypeController::class);
    $router->resource('partner-options', PartnerOptionController::class);

    $router->resource('checks', CheckController::class);

});
