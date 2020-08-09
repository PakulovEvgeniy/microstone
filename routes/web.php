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
//URL::forceScheme('https');


Route::get('/', 'HomeController@get');
Route::get('/category/{id?}', 'Category_prod@get');
Route::get('/category/{idF}/filters', 'Category_Filter@get');
Route::get('/product/{id}', 'Product_page@get');
Route::get('/manufacturer/{id?}', 'Manufacturer@get');
Route::get('/about', 'AppController@get');
Route::get('/contact', 'AppController@get');
Route::get('/compare', 'Compare@get');
Route::get('/account/wishlist', 'Account\Wishlist@wishlist');
Route::get('/account/cart', 'Account\Cart@get');
Route::post('/account/cart', 'Account\Cart@get');
Route::post('/account/wishlist/add', 'Account\Wishlist@addToWish');
Route::post('/account/cart/add', 'Account\Cart@addToCart');
Route::post('/account/cart/edit', 'Account\Cart@editToCart');
Route::post('/account/cart/delete', 'Account\Cart@deleteFromCart');
Route::post('/account/cart/clear', 'Account\Cart@clearCart');
Route::post('/account/wishlist/addingrouplist', 'Account\Wishlist@addToWishList');
Route::post('/account/wishlist/delete', 'Account\Wishlist@delFromWish');
Route::post('/account/wishlist/deletefromgroup', 'Account\Wishlist@delFromWishGroup');
Route::post('/account/wishlist/addgroup', 'Account\Wishlist@addWishGroup');
Route::post('/account/wishlist/editgroup', 'Account\Wishlist@editWishGroup');
Route::post('/account/wishlist/archivegroup', 'Account\Wishlist@archiveWishGroup');
Route::post('/account/wishlist/delgroup', 'Account\Wishlist@delWishGroup');
Route::get('/account/wishlist/{id?}', 'Account\Wishlist@wishlistGroup');
Route::get('/account/{id?}/{act?}', 'Account\Account@get');
Route::post('/account/personal', 'Account\Account@post');
Route::post('/account/changepsw', 'Account\Account@changePassword');
Route::post('/account/contragents/add', 'Account\Account@addContragent');
Route::post('/account/contragents/edit', 'Account\Account@editContragent');
Route::post('/account/contragents/delete', 'Account\Account@deleteContragent');
Route::post('/account/addresses/add', 'Account\Account@addAddress');
Route::post('/account/addresses/edit', 'Account\Account@editAddress');
Route::post('/account/addresses/delete', 'Account\Account@deleteAddress');

Auth::routes(['verify' => true]);

Route::any('/{any}', 'Not_found@get')->where('any', '.*');



//Route::get('/home', 'HomeController@index')->name('home');
