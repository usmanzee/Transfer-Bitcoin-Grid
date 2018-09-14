<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTransactionRequest extends Model
{
	use SoftDeletes;

    protected $table = 'user_transaction_requests';

    protected $fillable = ['id', 'user_id', 'user_id', 'user_game_id', 'name', 'email', 'bitcoin_account_address', 'amount'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at']; 
}
