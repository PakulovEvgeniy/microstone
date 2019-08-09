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

Route::get('/', 'HomeController@get');
Route::get('/category/{id?}', 'Category_prod@get');
Route::get('/category/{idF}/filters', 'Category_Filter@get');
Route::get('/about', 'AppController@get');
Route::get('/contact', 'AppController@get');
Route::get('/account', 'AppController@get');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
