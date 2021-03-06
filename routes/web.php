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

Route::get('/', function () {
    return redirect('transaction-requests');
});
Auth::routes();
Route::middleware('auth')->get('transaction-requests', 'TransactionController@index');
Route::middleware('auth')->get('add-admin-account-detail', 'TransactionController@showAdminAccountDetail');
Route::middleware('auth')->post('add-admin-account-detail', 'TransactionController@addAdminAccountDetail');
