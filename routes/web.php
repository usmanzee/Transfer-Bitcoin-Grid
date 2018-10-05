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

Route::get('test_blockchain', function() {
	$guid="22bbc39b-187d-46bd-b334-afa3d0cb5fb6";
	$firstpassword="usmanjamil0308";
	$secondpassword="PASSWORD_HERE";
	$amounta = "10000";
	$amountb = "400000";
	$addressa = "1A8JiWcwvpY7tAopUkSnGuEYHmzGYfZPiq";
	$addressb = "1ExD2je6UNxL5oSu6iPUhn9Ta7UrN8bjBy";
	$recipients = urlencode('{
	              "'.$addressa.'": '.$amounta.',
	              "'.$addressb.'": '.$amountb.'
	           }');

	$arrContextOptions=array(
	    "ssl"=>array(
	        "verify_peer"=>false,
	        "verify_peer_name"=>false,
	    ),
	);

	// echo 'https://blockchain.info/merchant/'.$guid.'/payment?password='.$firstpassword.'&to='.$addressa.'&amount='.$amounta;
	// die;

	$json_url = 'http://localhost:3000/merchant/22bbc39b-187d-46bd-b334-afa3d0cb5fb6/payment?password=usmanjamil2468&to=14rUYvvsNskXr1eDY5daZwzS9Dhvxthp8j&amount=55&from=0';
	//$json_url = "https://blockchain.info/merchant/22bbc39b-187d-46bd-b334-afa3d0cb5fb6/payment?password=usmanjamil0308&to=3DqZRoa2oujG5nTLsjuTjzuigVQufztNKe&amount=10000";

	$json_data = file_get_contents($json_url, false, stream_context_create($arrContextOptions));

	dd($json_data);

	$json_feed = json_decode($json_data);

	$message = $json_feed->message;
	$txid = $json_feed->tx_hash;
});
