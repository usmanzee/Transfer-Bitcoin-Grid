<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('save-admin-account-detail', 'TransactionController@saveAdminAccountDetail');

Route::post('save-user-transaction-request', 'TransactionController@saveUserTransactionRequest');

Route::post('transfer-payment', 'TransactionController@transferPayment');

Route::post('delete-admin-account-detail', 'TransactionController@deleteAdminAccountDetail');
