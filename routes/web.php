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

    Route::get('/wallet/{wallet}/transactions', 'TransactionsController@index')->middleware('can:view,wallet');
    Route::get('/wallet/{wallet}/transactionsList', 'TransactionsController@list')->middleware('can:view,wallet');
    Route::post('/wallet/{wallet}/transaction/create', 'TransactionsController@create')->middleware('can:pay,wallet');
    Route::delete('/wallet/{wallet}/transaction/delete/{transaction}', 'TransactionsController@delete')->middleware(\App\Policies\CanDeleteTransactionMiddleware::class);
});
