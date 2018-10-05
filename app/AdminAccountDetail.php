<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminAccountDetail extends Model
{
    protected $table = 'admin_account_detail';

    protected $fillable = ['id', 'blockchain_id', 'password', 'address_index'];
}
