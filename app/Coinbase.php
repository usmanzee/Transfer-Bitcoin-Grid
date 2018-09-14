<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Coinbase\Wallet\Client;
use Coinbase\Wallet\Configuration;

use Coinbase\Wallet\Enum\CurrencyCode;
use Coinbase\Wallet\Resource\Transaction;
use Coinbase\Wallet\Value\Money;


class Coinbase extends Model
{
    public function createClientAndSendPayment($address= "", $amount= "") {

    	//dd($amount);

    	$configuration = Configuration::apiKey('A07yTBPeCpzdwKKi', '1ma5fkPE214KoECB5Wnui55qHWF9zaQS');
		$client = Client::create($configuration);

		$accounts = $client->getAccounts();
		$account = $client->getPrimaryAccount();

		$transaction = Transaction::send();
		$transaction->setToBitcoinAddress($address);
		$transaction->setAmount(new Money($amount, CurrencyCode::BTC));
		//$transaction->setFee('0.0001');
		//$transaction->setDescription('this is optional');

		try { 

			$client->createAccountTransaction($account, $transaction);
			return [
				'status' => true,
				'message' => 'Payment has been transferred'
			];
		}
		catch(Exception $e) {
			return [
				'status' => false,
				'message' => $e->getMessage()
			];
		}
    }
}
