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

Route::resource('catalogo','CatalogosController');
Route::resource('group','GroupsController');

Route::resource('user','UsersController');
Route::resource('evaluador','EvaluadorsController');

Route::get('/user/login/signin', 'UsersController@signin');
Route::get('/user/validate/unique/{attribute}', 'UsersController@ValidateUnique');
Route::post('/user/access/activation', 'UsersController@activatedUser');
