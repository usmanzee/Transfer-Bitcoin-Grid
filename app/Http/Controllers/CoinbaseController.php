<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coinbase;

class CoinbaseController extends Controller
{
    public function test() {
    	(new Coinbase)->createClient();
    }
}
