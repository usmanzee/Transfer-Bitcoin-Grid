@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" type="text/css"/>
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
                    <th>Amount(USD-$)</th>
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
                    <td>{{ $transactionRequest->amountInUSD }}</td>
                    <td id="action_link_{{ $transactionRequest->id }}">@if($transactionRequest->status) Paid @else <a href="" class="payment_button" requestId="{{ $transactionRequest->id }}" amountInBTC="{{ $transactionRequest->amountInBTC }}" bitcoinAccountAddress="{{ $transactionRequest->bitcoin_account_address }}">Send payment</a> @endif</td>
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
                    <th>Amount(USD-$)</th>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    });

    $(".payment_button").click(function(e) {
        e.preventDefault();
        $("#loading_div").show();
        var requestId = $(this).attr('requestId');
        var amountInBTC = $(this).attr('amountInBTC');
        var bitcoinAccountAddress = $(this).attr('bitcoinAccountAddress');

        if(amountInBTC && requestId) {
            $.ajax({
                url: "{{ url('api/transfer-payment') }}",
                method: "POST",
                tryCount : 0,
                retryLimit : 3,
                data: {
                    requestId: requestId,
                    amountInBTC: amountInBTC,
                    bitcoinAccountAddress: bitcoinAccountAddress,
                },
                success: function(response) {
                    $("#loading_div").hide();
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
    });
</script>
@endsection