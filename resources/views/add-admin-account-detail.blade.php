@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{!! asset('css/dataTables.bootstrap.css') !!}" type="text/css"/>
    <link rel="stylesheet" href="{!! asset('css/jquery-confirm.css') !!}" type="text/css"/>
@endsection
@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
    	<form method="post" action="{{ url('add-admin-account-detail') }}" accept-charset="UTF-8">
    		<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
			<div class="form-group">
			    <label for="blockchain_id">Blockchain Login Id: </label>
			    <input type="text" id="blockchain_id" name="blockchainId" placeholder="Blockchain login id" class="form-control" required />
			</div>
			<div class="form-group">
			    <label for="password">Password</label>
			    <input type="text" id="password" name="password" placeholder="Enter blockchain password" class="form-control" required />
			</div>
			<div class="form-group">
			    <label for="bitcoin_address_index">Bitcoin Address Index:</label>
			    <input type="text" id="bitcoin_address_index" name="bitcoinAddressIndex" placeholder="Enter Bitcoin Address" value="0" class="form-control" required />
			</div>
			<small style="color:red">Bitcoin address index is the address number: after login go to <b>settings</b> then <b>wallets & addresses</b> where you will find the list of addresses starting from <b>0</b> </small>
			<br><br>
			<button type="submit" class="btn btn-primary btn-block">Submit</button>
		</form>
    </div>
</div>
@endsection