<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function() {
    Route::get('/home', 'HomeController@index')->name('home');


    Route::post('/wallet/create', 'WalletController@create');
    Route::delete('/wallet/delete/{wallet}', 'WalletController@delete')->middleware('can:delete,wallet');
    Route::post('/wallet/update/{wallet}', 'WalletController@update')->middleware('can:update,wallet');

});
