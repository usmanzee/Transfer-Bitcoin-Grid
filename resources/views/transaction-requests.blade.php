@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{!! asset('css/dataTables.bootstrap.css') !!}" type="text/css"/>
    <link rel="stylesheet" href="{!! asset('css/jquery-confirm.css') !!}" type="text/css"/>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Bitcoin Address</th>
                    <th>Amount(Satoshi)</th>
                    <th>Amount(BTC-฿)</th>
                    <!-- <th>Amount(USD-$)</th> -->
                    <th>Action</th>
                    <th>Request Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactionRequests as $key => $transactionRequest)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $transactionRequest->name }}</td>
                    <td>{{ $transactionRequest->email }}</td>
                    <td>{{ $transactionRequest->bitcoin_account_address }}</td>
                    <td>{{ $transactionRequest->amountInSatoshi }}</td>
                    <td>{{ $transactionRequest->amountInBTC }}</td>
                    <!-- <td>{{ $transactionRequest->amountInUSD }}</td> -->
                    <td id="action_link_{{ $transactionRequest->id }}">@if($transactionRequest->status) Paid @else <a href="" class="payment_button" requestId="{{ $transactionRequest->id }}" amountInSatoshi="{{ $transactionRequest->amountInSatoshi }}" bitcoinAccountAddress="{{ $transactionRequest->bitcoin_account_address }}">Send payment</a> @endif</td>
                    <td>{{ date('d M, Y - h:i A', strtotime($transactionRequest->created_at)) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Bitcoin Address</th>
                    <th>Amount(Satoshi)</th>
                    <th>Amount(BTC-฿)</th>
                    <!-- <th>Amount(USD-$)</th> -->
                    <th>Action</th>
                    <th>Request Date</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div id="loading_div" hidden style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.8;">
    <p style="position: absolute; color: White; top: 50%; left: 45%;">
        Transferring funds, please wait...
    </p>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="{{ asset('js/jquery.toaster.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.toaster.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-confirm.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
        var adminAccountDetail = "<?php echo $adminAccountDetail ?>";

        $(".payment_button").click(function(e) {
            e.preventDefault();
            //$("#loading_div").show();
            var requestId = $(this).attr('requestId');
            var amountInSatoshi = $(this).attr('amountInSatoshi');
            var bitcoinAccountAddress = $(this).attr('bitcoinAccountAddress');

            if(amountInSatoshi && requestId) {
                if(adminAccountDetail != " " && adminAccountDetail != null) {
                    $.confirm({
                        title: 'Transfer BTC',
                        content: ' <p><b>Note: </b>Please enter the required information to transfer amount.</p> ' +
                        '<form action="" class="formName">' +
                                '<label for="blockchain_id">Blockchain Login Id: </label>' +
                                '<input type="text" id="blockchain_id" placeholder="Blockchain login id" class="form-control" required />' +
                                '<br>'+
                                '<label for="password">Password</label>' +
                                '<input type="text" id="password" placeholder="Enter blockchain password" class="form-control" required />' +
                                '<br>'+
                                '<label for="bitcoin_address_index">Bitcoin Address Index:</label>' +
                                '<input type="text" id="bitcoin_address_index" placeholder="Enter Bitcoin Address" value="0" class="form-control" required />' +
                                '<small style="color:red">Bitcoin address index is the address number: after login go to <b>settings</b> then <b>wallets & addresses</b> where you will find the addresses starts from <b>0</b> </small>'+
                        '</form>',
                        buttons: {
                            formSubmit: {
                                text: 'Send',
                                btnClass: 'btn btn-success',
                                action: function () {
                                    var blockchainId = this.$content.find('#blockchain_id').val();
                                    var password = this.$content.find('#password').val();
                                    var bitcoinAddressIndex = this.$content.find('#bitcoin_address_index').val();

                                    // if(!dueDate){
                                    //     $.alert('Please Provide A Valid Date.');
                                    //     return false;
                                    // }

                                    //$("#loading").show();
                                    $.ajax({
                                        url: "{{ url('api/save-admin-account-detail') }}",
                                        type: 'POST',
                                        data: {
                                            blockchainId: blockchainId,
                                            password: password,
                                            bitcoinAddressIndex: bitcoinAddressIndex
                                        },
                                        success: function(response){
                                            //$("#loading").hide();
                                            if(response.status){
                                                $.toaster({ priority : 'success', title : 'Success!', message : response.message });
                                                transferAmount(requestId, amountInSatoshi, bitcoinAccountAddress);
                                            }else{
                                                $.toaster({ priority : 'danger', title : 'Failed!', message : response.message });
                                            }
                                        },
                                        fail: function(){
                                            //$("#loading").hide();
                                            $.toaster({ priority : 'danger', title : 'Failed!', message : 'Request Failed!' });
                                        }
                                    });
                                }
                            },
                            cancel: function () {
                            },
                        },
                        onContentReady: function () {
                            // bind to events
                            var jc = this;
                            this.$content.find('form').on('submit', function (e) {
                                // if the user submits the form by pressing enter in the field.
                                e.preventDefault();
                                jc.$$formSubmit.trigger('click'); // reference the button and click it
                            });
                        }
                    });
                } else {
                    transferAmount(requestId, amountInSatoshi, bitcoinAccountAddress);
                }
            }

        });
    });

    function transferAmount(requestId, amountInSatoshi, bitcoinAccountAddress) {
        $.ajax({
            url: "{{ url('api/transfer-payment') }}",
            method: "POST",
            tryCount : 0,
            retryLimit : 3,
            data: {
                requestId: requestId,
                amountInSatoshi: amountInSatoshi,
                bitcoinAccountAddress: bitcoinAccountAddress,
            },
            success: function(response) {
                //$("#loading_div").hide();
                if(response.status) {
                    $.toaster({ priority : 'success', title : 'Success', message : response.message });
                    $("#action_link_"+requestId).html('Paid');
                } else {
                    $.toaster({ priority : 'danger', title : 'Failed', message : response.message });
                }
            },
            error: function() {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.ajax(this);
                    return;
                }
            }
        });
    }

</script>
@endsection