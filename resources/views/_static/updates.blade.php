@extends('layout')
@section('content')
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<div id="user_overview_top_row" class="row top-row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h2>Updates</h2>
    </div>
</div>
@if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
<!--<script>
/*
    $(function () {
    function showWire(){
    setTimeout(function(){
    document.body.style.paddingRight = '0';
    }, 380);
    }
    var wires = {};
    @foreach($wire_updates as $wire)
            wires[{{$wire -> id}}] = {
    "id": '10{{$wire->wire_id}}',
            "money_sent": '{{$wire->sent_on}}',
            @if ($wire -> active == 0)
            "status": 'Archived',
            @elseif($wire -> status == '1' || $wire -> status == 'Pending')
            "status": 'Pending',
            @elseif($wire -> status == '2')
            "status": 'Returned',
            @elseif($wire -> status == '3' || $wire -> status == 'Comliance')
            "status": 'Compliance',
            @elseif($wire -> status == '4')
            "status": 'Awaiting Funds',
            @elseif($wire -> status == '5' || $wire -> status == 'Complete')
            "status": 'Approved',
            @elseif($wire -> status == '6' || $wire -> status == 'Paid')
            "status": 'Paid',
            @endif
            "merch_name": '{{$wire->merch_name}}',
            "bank" :'{{ $wire->bank_name }}',
            "country" :'{{ $wire->sending_country }}',
            "phone" :'{{ $wire->client_phone }}',
            "email" :'{{ $wire->client_email }}',
            "sent_currecy" :'{{ $wire->currency }}',
            "sent_amount" :'{{ $wire->amount_sent }}',
            "fee_amount" :'{{ $wire->amount_of_percent }}',
            "fee" :'{{ $wire->percent }}',
            "amount_after_fee" :'{{ $wire->amountafterpercent }}',
            "received_on" :'{{ $wire->received_on }}',
            "received_amount" :'{{ $wire->amount_received }}',
            "received_currency" :'{{ $wire->currency_received }}',
            "kyc" :'{{ $wire->kyc }}',
            "wc" :'{{ $wire->wc }}',
            "notes" : '{!! $wire->notes !!}',
            "created_at" : '{{ $wire->created_at }}',
            "updated_at" : '{{ $wire->updated_at }}',
            "action" : '{{ $wire->action }}',
            "changed_by" : '{{ $wire->user_name }} {{ $wire->user_lastname }}'

    };
    @endforeach
            $(".opener").click(function () {
    showWire();
    var dialog = $("#dialog"), item = wires[$(this).attr('data-id')];
    for (var field in item) {
    dialog.find('[data-field="' + field + '"]').text(item[field]);
    }
    });
    $('#dialog .close').click(showWire);*/
    });</script>
-->
<div id="dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <span data-field="id"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table id="wires-pop" class="table-hover table-responsive" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>ID</th>
                            <th>Sent on</th>
                            <th>Company</th>
                            <th>Amount Sent</th>
                            <th>Amount Received</th>
                            <th>Received on</th>
                            <th>Fee</th>
                            <th>Amount after fee</th>
                            <th>KYC</th>
                            <th>World Check</th>
                            <th>Action</th>
                            <th>Changed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span data-field="status"></span></td>
                            <td><span data-field="id"></span></td>
                            <td><span data-field="money_sent"></span></td>
                            <td><span data-field="merch_name"></span></td>
                            <td><span data-field="sent_amount"></span> <span data-field="sent_currency"></span></td>
                            <td><span data-field="received_amount"></span> <span data-field="received_currency"></span></td>
                            <td><span data-field="received_on"></span></td>
                            <td><span data-field="fee"></span> (<span data-field="fee_amount"></span>)</td>
                            <td><span data-field="amount_after_fee"></span></td>
                            <td><span data-field="kyc"></span></td>
                            <td><span data-field="wc"></span></td>
                            <td><span data-field="action"></span></td>
                            <td><span data-field="changed_by"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <h3>Wire Updates</h3>
    <table id="wires" class="table-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Updated at</th>
                <th>Action</th>
                <th>Changed By</th>
                <th>Status</th>
                <th>ID</th>
                <th>Sent on</th>
                <th>Company</th>
                <th>Bank</th>
                <th>Country</th>
                <th>Amount Sent</th>
                <th>Amount Received</th>
                <th>Received on</th>
                <th>Fee</th>
                <th>Amount after fee</th>
                <th>KYC</th>
                <th>World Check</th>
            </tr>
        </thead>
        <tbody>
            @foreach($wire_updates as $wupdate)
            <tr>
                <td>{{ $wupdate->updated_at }}</td>
                <td>
                    @if($wupdate->action == 1)
                    Created
                    @elseif($wupdate->action == 2)
                    Updated
                    @elseif($wupdate->action == 3)
                    Archived
                    @endif
                </td>
                <td>{{ $wupdate->user_name }} {{ $wupdate->user_lastname }}</td>
                <td>{{ $wupdate->status }}</td>
                <td>10{{ $wupdate->wire_id }}<!--<button type="button" class="opener" data-toggle="modal" data-id="{{ $wupdate->wire_id }}" data-target="#dialog">10{{ $wupdate->wire_id }}</button>--></td>
                <td>{{$wire->sent_on}}</td>
                <td>{{$wire->merch_name}}</td>
                <td>{{$wire->bank_name}}</td>
                <td>{{$wire->sending_country }}</td>
                <td>{{ $wire->amount_sent }} {{ $wire->currency }}</td>
                <td>{{ $wire->amount_received }}{{ $wire->currency_received }}</td>
                <td>{{ $wire->received_on }}</td>
                <td>{{ $wire->percent }}% ({{ $wire->amount_of_percent }} {{ $wire->currency_received }})</td>
                <td>{{ $wire->amountafterpercent }}</td>
                <td>{{ $wupdate->kyc }}</td>
                <td>{{ $wupdate->wc }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="row">
    <h3>Company Updates</h3>
    <table id="merchants" class="table-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Updated at</th>
                <th>Action</th>
                <th>Changed By</th>
                <th>Status</th>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Contact Information</th>
                <th>Bank's details</th>
                <th>Beneficiary Name</th>
                <th>Beneficiary Address</th>
                <th>Available Banks</th>
                <th>Available Currencies</th>
            </tr>
        </thead>
        <tbody>
            @foreach($merchant_updates as $mupdate)
            <tr>
                <td>{{ $mupdate->updated_at }}</td>
                <td>
                    @if($mupdate->action == 1)
                    Created
                    @elseif($mupdate->action == 2)
                    Updated
                    @elseif($mupdate->action == 3)
                    Archived
                    @endif
                </td>
                <td>{{ $mupdate->user_name }} {{ $mupdate->user_lastname }}</td>
                <td>@if($mupdate->status == 1)
                    Active
                    @else
                    <span style="color: #bf3f3f;">Archived</span>
                    @endif
                </td>
                <td>{{ $mupdate->merchant_id }}</td>
                <td>{{ $mupdate->name }}</td>
                <td>10{{ $mupdate->address }}</td>
                <td>Phone: {{ $mupdate->mobile_phone }}<br/>
                Skype: {{ $mupdate->skype }}<br/>
                Email: {{ $mupdate->email }}
                </td>
                <td>Name: {{ $mupdate->bank_name }}<br/>
                    Address: {{ $mupdate->bank_address }}<br/>
                    EUR/IBAN: {{ $mupdate->eur_iban }}<br/>
                    SWIFT/BIC: {{ $mupdate->swift_bic }}
                </td>
                <td>{{ $mupdate->account_holder }}</td>
                <td>{{ $mupdate->beneficiary_address }}</td>
                <td>
                    @foreach($merch_bank[$mupdate->id] as $banks)
                    {{ $banks->name }}
                    @endforeach
                </td>
                <td>
                    @foreach($merch_currency[$mupdate->id] as $currency)
                    {{ $currency->name }}
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="row">
        <h3>User Updates</h3>
        <table id="users" class="table-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Updated at</th>
                <th>Action</th>
                <th>Changed By</th>
                <th>Status</th>
                <th>ID</th>
                <th>Name</th>
                <th>Company</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Skype</th>
                <th>URL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user_updates as $uupdate)
            <tr>
                <td>{{ $uupdate->updated_at }}</td>
                <td>
                    @if($uupdate->action == 1)
                    Created
                    @elseif($uupdate->action == 2)
                    Updated
                    @elseif($uupdate->action == 3)
                    Archived
                    @endif
                </td>
                <td>{{ $uupdate->changed_by }}</td>
                <td>
                    @if($uupdate->status == 1)
                    Active
                    @else
                    <span style="color: #bf3f3f;">Archived</span>
                    @endif
                </td>
                <td>{{ $uupdate->id }}</td>
                <td>{{ $uupdate->name }} {{ $uupdate->lastname }}</td>
                <td>10{{ $uupdate->merchant_id }}</td>
                <td>{{ $uupdate->email }}</td>
                <td>{{ $uupdate->phone }}</td>
                <td>{{ $uupdate->skype }}</td>
                <td>{{ $uupdate->url }}</td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
<div class="row">
    <h3>Bank Updates</h3>
    <table id="bank" class="table-responsive" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Updated At</th>
                <th>Action</th>
                <th>Changed By</th>
                <th>Status</th>
                <th>ID</th>
                <th>Name</th>
                <th>File</th>
                <th>Created At:</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bank_updates as $bupdate)
            <tr>
            <td>{{ $bupdate->updated_at }}</td>
                <td>
                    @if($bupdate->action == 1)
                    Created
                    @elseif($bupdate->action == 2)
                    Updated
                    @elseif($bupdate->action == 3)
                    Archived
                    @endif
                </td>
                <td>{{ $bupdate->user_name }} {{ $bupdate->user_lastname }}</td>
                <td>
                    @if($bupdate->status == 1)
                    Active
                    @else
                    <span style="color: #bf3f3f;">Archived</span>
                    @endif
                </td>
                <td>{{ $bupdate->bank_id }}</td>
                <td>{{ $bupdate->name }}</td>
                <td>{{ link_to_asset('bank/'.$bupdate->file, $bupdate->name) }}</td>
                <td>{{ $bupdate->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
<script>
    $('#wires').DataTable({
        dom: '<"row"<"col-md-3 search_wrapper"f><"col-md-2 add_btn_wrapper">>t<"foot"<"col-md-2 show_entries"l><"col-md-5 pagination_wrapper"p><"col-md-2 col-md-offset-3 show_count_entries"i>>',
    order: [[0, "desc"]],
            "lengthMenu": [[5, 15, 25, 50, 100, - 1], [5, 15, 25, 50, 100, "All"]]
    });
    $('#wires-pop').DataTable();
    $('#merchants').DataTable({
        dom: '<"row"<"col-md-3 search_wrapper"f><"col-md-2 add_btn_wrapper">>t<"foot"<"col-md-2 show_entries"l><"col-md-5 pagination_wrapper"p><"col-md-2 col-md-offset-3 show_count_entries"i>>',
    order: [[0, "desc"]],
            "lengthMenu": [[5, 15, 25, 50, 100, - 1], [5, 15, 25, 50, 100, "All"]]
    });
    $('#users').DataTable({
        dom: '<"row"<"col-md-3 search_wrapper"f><"col-md-2 add_btn_wrapper">>t<"foot"<"col-md-2 show_entries"l><"col-md-5 pagination_wrapper"p><"col-md-2 col-md-offset-3 show_count_entries"i>>',
    order: [[0, "desc"]],
            "lengthMenu": [[15, 25, 50, 100, - 1], [15, 25, 50, 100, "All"]]
    });
    $('#bank').DataTable({
        dom: '<"row"<"col-md-3 search_wrapper"f><"col-md-2 add_btn_wrapper">>t<"foot"<"col-md-2 show_entries"l><"col-md-5 pagination_wrapper"p><"col-md-2 col-md-offset-3 show_count_entries"i>>',
    order: [[0, "desc"]],
            "lengthMenu": [[15, 25, 50, 100, - 1], [15, 25, 50, 100, "All"]]
    });
</script>

@stop