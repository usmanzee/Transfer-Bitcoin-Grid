<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UserTransactionRequest;
use App\Coinbase;

class TransactionController extends Controller
{

	public function _construct() {
		$this->middleware('auth', ['except' => ['saveUserTransactionRequest']]);
	}
    public function index() {
    	$transactionRequests = UserTransactionRequest::all();
    	$exchangeRates = $this->curlGetRequest('https://api.coinbase.com/v2/exchange-rates?currency=BTC');
    	$exchangeRates = json_decode($exchangeRates);
    	
    	foreach ($transactionRequests as $key => $transactionRequest) {
    		$transactionRequest->amountInSatoshi = $transactionRequest->amount;
    		$decimalPlaces = strlen($transactionRequest->amountInSatoshi)+8;
    		$transactionRequest->amountInBTC = number_format($transactionRequest->amount/100000000, $decimalPlaces, '.', '');
    		$transactionRequest->amountInUSD = ($transactionRequest->amount/100000000)*$exchangeRates->data->rates->USD;

    	}

    	return view('transaction-requests', compact('transactionRequests'));
    }

    public function saveUserTransactionRequest(Request $request ) {

    	UserTransactionRequest::create([
    		'name' => $request->get('name', null),
    		'email' => $request->get('email', null),
    		'bitcoin_account_address' => $request->get('bitcoinAccountAddress', null),
    		'amount' => $request->get('amount', null)
    	]);

    	return response()->json([
    		'status' => true,
    		'message' => 'Your request for transaction has been sent successfully.'
    	]);
    }

    public function transferPayment(Request $request) {
    	 $response = (new Coinbase)->createClientAndSendPayment($request->bitcoinAccountAddress, $request->amountInBTC);
         dd($response);
    	 if($response['status']) {
    	 	UserTransactionRequest::where('id', $request->requestId)->update([
    	 		'status' => 1
    	 	]);
    	 }
    	 return response()->json($response);
    }

    public function curlGetRequest($url) {
    	$curl = curl_init();
    	curl_setopt_array($curl, array(
    		CURLOPT_RETURNTRANSFER => 1,
    		CURLOPT_URL => $url,
    		));
    	$response = curl_exec($curl);
    	curl_close($curl);
    	return $response;
    }
}
