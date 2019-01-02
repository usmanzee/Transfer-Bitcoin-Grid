<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UserTransactionRequest;
use App\AdminAccountDetail;
use App\Coinbase;

class TransactionController extends Controller
{

	public function _construct() {
		$this->middleware('auth', ['except' => ['saveUserTransactionRequest']]);
	}
    public function index() {
        $adminAccountExists = false;
    	$transactionRequests = UserTransactionRequest::all();
    	$adminAccountDetail = AdminAccountDetail::first();
        if(!is_null($adminAccountDetail)) {
            $adminAccountExists = true;
        }

    	foreach ($transactionRequests as $key => $transactionRequest) {
    		$transactionRequest->amountInSatoshi = $transactionRequest->amount;
            $transactionRequest->amountInBTC = $transactionRequest->amount/100000000;
    	}

    	return view('transaction-requests', compact('adminAccountExists', 'adminAccountDetail','transactionRequests'));
    }

    public function saveAdminAccountDetail(Request $request) {

        AdminAccountDetail::create([
            'blockchain_id' => $request->blockchainId,
            'password' => $request->password,
            'address_index' => $request->bitcoinAddressIndex
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Your account has been saved.'
        ]);
    }

    public function showAdminAccountDetail() {
        return view('add-admin-account-detail');
    }

    public function addAdminAccountDetail(Request $request) {
        $response = $this->saveAdminAccountDetail($request);
        return redirect('transaction-requests');
    }

    public function deleteAdminAccountDetail(Request $request) {
        AdminAccountDetail::truncate();
        return response()->json([
            'status' => true,
            'message' => 'Admin account deleted successfully.'
        ]);
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

        $baseUrl = "http://blockchain.logicsbay.com:3000";
        $adminAccountDetail = AdminAccountDetail::first();

        $addressIndex = ($adminAccountDetail->address_index) ? $adminAccountDetail->address_index : 0;

        $url = $baseUrl."/merchant/".$adminAccountDetail->blockchain_id."/payment?password=".$adminAccountDetail->password."&to=".$request->bitcoinAccountAddress."&amount=".$request->amountInSatoshi."&from=".$addressIndex;

        $response = $this->curlGetRequest($url);
        $response = json_decode($response);

        if($response) {

            if(isset($response->success)) {
                $output = [
                    'status' => true,
                    'message' => $response->message
                ];
            } else if($response->error) {
                $output = [
                    'status' => false,
                    'message' => $response->error
                ];
            }
        } else {
            $output = [
                'status' => false,
                'message' => 'Unable to make payment.'
            ];
        }

        return $output;
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
