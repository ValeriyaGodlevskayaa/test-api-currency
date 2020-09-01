<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'Api\CurrencyController@index');

Auth::routes();

Route::get('/currency', 'Api\CurrencyController@index')->name('currency');
Route::post('/currency', 'Api\CurrencyController@convertCurrency')->name('convertCurrency');
Route::post('/currencyHistory', 'Api\CurrencyController@currencyHistory')->name('currencyHistory');


