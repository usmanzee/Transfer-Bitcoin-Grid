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

Route::get('coinbase_test', 'CoinbaseController@test');

Auth::routes();

Route::middleware('auth')->get('transaction-requests', 'TransactionController@index');
//Route::get('transaction-requests', 'TransactionController@index')->name('home');

Route::get('test_blockchain', function() {
	$guid="22bbc39b-187d-46bd-b334-afa3d0cb5fb6";
	$firstpassword="usmanjamil0308";
	$secondpassword="PASSWORD_HERE";
	$amounta = "10000000";
	$amountb = "400000";
	$addressa = "1A8JiWcwvpY7tAopUkSnGuEYHmzGYfZPiq";
	$addressb = "1ExD2je6UNxL5oSu6iPUhn9Ta7UrN8bjBy";
	$recipients = urlencode('{
	              "'.$addressa.'": '.$amounta.',
	              "'.$addressb.'": '.$amountb.'
	           }');

	$json_url = "https://blockchain.info/merchant/22bbc39b-187d-46bd-b334-afa3d0cb5fb6/payment?password=usmanjamil0308&to=3DqZRoa2oujG5nTLsjuTjzuigVQufztNKe&amount=10000";

	$json_data = file_get_contents($json_url);

	dd($json_data);

	$json_feed = json_decode($json_data);

	$message = $json_feed->message;
	$txid = $json_feed->tx_hash;
});
