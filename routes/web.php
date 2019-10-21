<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/ajax/partner-offers/{partnerId}', 'AjaxController@partnerOffers');

Route::get('/api/partners', 'PartnerController@index');
Route::get('/api/partners/{partnerId}', 'PartnerController@one');

Route::get('/api/checks', 'CheckController@index');
Route::get('/api/checks/{id}', 'CheckController@one');
Route::post('/api/checks/create', 'CheckController@create');
