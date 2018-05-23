@extends('layout')
@section('content')
@if($is_login == true)
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="{{ asset('js/filters.js') }}"></script>
<div id="user_overview_top_row" class="row top-row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h2>Billing</h2>
    </div>
</div>
<script>
    $(function() {
    $("#accordion").accordion({
    collapsible: true,
            active: 0,
            animate: 200
    });
    
    function shiftThatShit(){
    setTimeout(function(){
    document.body.style.paddingRight = '0';
    }, 380);
    }
    var f = new Filters('billing_filters', {{ Session::get('user_id') }});
    //f.clear();
    var savedFilters = f.load();
    for (var i in savedFilters){
        if (savedFilters[i] instanceof Array){
            for (var j in savedFilters[i]){
                if (!savedFilters[i][j].checked){
                    $('[name="' + i + '"][value="' + savedFilters[i][j].name + '"]').removeAttr('checked');
                }
            }
        }
        else{
            $('[name="' + i + '"]').val(savedFilters[i]);
        }
    }
    $('input[name="search"]').click(function(){
        var obj = {};
        $('.accordion-form-wrapper input[type="text"], .accordion-form-wrapper select').each(function(){
            obj[$(this).attr('name')] = $(this).val();
        });
        $('.accordion-form-wrapper input[type="checkbox"]').each(function(){
            if (!obj[$(this).attr('name')]) obj[$(this).attr('name')] = [];
            obj[$(this).attr('name')].push({name: $(this).val(), checked: $(this).is(':checked')});
        });
        f.save(obj);
    });
    var wires = {};
    @foreach($wires as $wire)
            wires[{{$wire -> id}}] = {
            "id": '10{{$wire->wire_id}}',
            "money_sent": '{{ date("d/m/Y", strtotime($wire->sent_on)) }}',
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
            "client_name" :'{{ $wire->client_name }}',
            "merch_name": '{{$wire->merch_name}}',
            "bank" :'{{ $wire->bank_name }}',
            "country" :'{{ $wire->country_name }}',
            "phone" :'{{ $wire->m_code }}{{ $wire->client_phone }} ({{ $wire->m_country }})',
            "email" :'{{ $wire->client_email }}',
            "sent_currecy" :'{{ $wire->currency }}',
            "sent_amount" :'{{ $wire->amount_sent }}',
            @if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b'))
            "fee_amount" :'{{ $wire->amount_of_percent }}',
            "fee" :'{{ $wire->percent }}',
            "amount_after_fee" :'{{ $wire->amountafterpercent }}',
            @endif
            @if(!empty($wire->received_on))
            "received_on" :'{{ date("d/m/Y", strtotime($wire->received_on)) }}',
            @else
            "received_on" :'',
            @endif
            @if(!empty($wire->amount_received))
            "received_amount" :'{{ $wire->amount_received }}',
            "received_currency" :'{{ $wire->currency_received }}',
            @else
            "received_amount" :'',
            "received_currency" :'',   
            @endif
            @if ($wire -> kyc == "none")"kyc" :"No KYC",
            @elseif($wire -> kyc == "uploaded")"kyc" :"KYC Uploaded",
            @elseif($wire -> kyc == "in_process")"kyc" :"In Checking Process",
            @elseif($wire -> kyc == "missing_docs")"kyc" :"Missing Documents",
            @elseif($wire -> kyc == "approved")"kyc" :"KYC Approved",
            @else "kyc" :"UNKNOWN",
            @endif
            @if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b'))
            @if ($wire -> wc == "not_checked")"wc" :"Not Checked",
            @elseif($wire -> wc == "no_match")"wc" :"No Match",
            @elseif($wire -> wc == "existing")"wc" :"Existing Profile",
            @else "wc" :"UNKNOWN",
            @endif
            @endif
            "notes" : '{!! $wire->notes !!}',
            "created_at" : '{{ $wire->created_at }}',
            "updated_at" : '{{ $wire->updated_at }}'
    };
    @endforeach
            $(".opener").click(function () {
    var dialog = $("#dialog"), item = wires[$(this).attr('data-id')];
    for (var field in item) {
    dialog.find('[data-field="' + field + '"]').text(item[field]);
    }
    });
    });</script>
@include('_errors.sm')
@if($em = Session::get('em'))
<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 em-wrapper">
    <div class="col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
        @include('_errors.em')
    </div>
</div>
@endif
<br/>
@endif
@if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
<div class="row top-row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 billing-accordion-wrapper">
        <div id="accordion">
            <h3><span class="glyphicon glyphicon-cog  icon-filter"></span> Billing Filter</h3>
            <div class="row accordion-form-wrapper">
                <form method="GET" action="">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 accordion-inner-wrapper">
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6 top-filter-row">
                            <p class="p-filter"><span class="glyphicon glyphicon-calendar"></span> by Date</p>
                            <input type="text" name="date">
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6 top-filter-row">
                            <p class="p-filter"><span class="glyphicon glyphicon-briefcase"></span> by Company</p>
                            <select name="merchant">
                                <option value="-">All Companies</option>
                                @foreach($merchants as $merch)
                                <option value="{{ $merch->merch_id }}">{{ $merch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6 top-filter-row">
                            <p class="p-filter"><span class="glyphicon glyphicon-credit-card"></span> by Bank</p>
                            <select name="bank">
                                <option value="-">All Banks</option>
                                @foreach($banks as $bank)
                                <option value="{{ $bank->bank_id }}">{{ $bank->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                            <p class="p-filter"><span class="glyphicon glyphicon-euro"></span>  Currency (received)</p>
                            <select name="currency_r">
                                <option value="-">All Currencies</option>
                                @foreach($currencys as $currency)
                                <option value="{{ $currency->name }}">{{ $currency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                            <p class="p-filter"><span class="glyphicon glyphicon-euro"></span> Currency (sent)</p>
                            <select name="currency_s">
                                <option value="-">All Currencies</option>
                                @foreach($currencys as $currency)
                                <option value="{{ $currency->name }}">{{ $currency->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                            <p class="p-filter"><span class="glyphicon glyphicon-user"></span> by WorldCheck status</p>
                            <input type="checkbox" name="wc[]" value="not_checked" checked="checked"> Not Checked<br>
                            <input type="checkbox" name="wc[]" value="no_match" checked="checked"> No Match <br>
                            <input type="checkbox" name="wc[]" value="existing" checked="checked"> Existing 
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-lg-8 col-sm-6 col-xs-6"></div>
                            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6"><input type="submit" name="search" class="filter-search" value="Search"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(isset($balances) && !empty($balances[0][0]->total_amount_received))
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 balance_wrapper" style="margin-top: 8px;">
        <table class="table-responsive">
            <tr>
                <td>
                    <table class="balance_title_tbl">
                        <tr>
                            <td>Currency:</td>
                        </tr>
                        <tr>
                            <td>Amount:</td>
                        </tr>
                        <tr>
                            <td>Fee:</td>
                        </tr>
                        <tr>
                            <td>Total:</td>
                        </tr>
                    </table>
                </td>
                @foreach($balances as $balance)
                @if(!empty($balance[0]->currency_received))
                <td>
                    <table class="balance_values_tbl">
                        <tr>
                            <td class="curr_title">{{ $balance[0]->currency_received }}</td>
                        </tr>
                        <tr>
                            <td>@if(!empty($balance[0]->total_amount_received)){{ number_format(floatval($balance[0]->total_amount_received),2,".",",") }} @else x @endif</td>
                        </tr>
                        <tr>
                            <td>@if(!empty($balance[0]->total_amount_of_percent)){{ number_format(floatval($balance[0]->total_amount_of_percent),2,".",",") }} @else x @endif</td>
                        </tr>
                        <tr>
                            <td>@if(!empty($balance[0]->total_amountafterpercent)){{ number_format(floatval($balance[0]->total_amountafterpercent),2,".",",") }} @else x @endif</td>
                        </tr>
                    </table>
                </td>
                @endif
                @endforeach
            </tr>
        </table>
    </div>
    @endif
</div>
@if(isset($wires) && !empty($wires))
<table id="billing" class="table-hover table-responsive" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Status</th>
            <th>ID</th>
            <th>Company</th>
            <th>Bank</th>
            <th>Name</th>
            <th>Amount Sent</th>
            <th>Amount Received</th>
            <th>Received on</th>
            <th>Fee</th>
            <th>Amount of fee</th>
            <th>Amount after fee</th>
            <th>KYC</th>
            <th>World Check</th>
        </tr>
    </thead>
    <tbody>
        @foreach($wires as $wire)
        <tr>
            <td class="wire_status">
            @if(!empty($wire->notes))
            <span class="glyphicon glyphicon-envelope note-sign" title="Wire contains additional notes!"></span>
            @else
            <span class="glyphicon glyphicon-envelope note-sign" style="color: transparent;"></span>
            @endif
            @if($wire->status == '1') Pending
            @elseif($wire->status == '2') Returned
            @elseif($wire->status == '3') Compliance
            @elseif($wire->status == '4') Awaiting Funds
            @elseif($wire->status == '5') Approved
            @elseif($wire->status == '6') Paid
            @endif
        </td>
        <td>
            @if($wire->active == 0)
            <button type="button" class="opener wire-archived" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">10{{ $wire->wire_id }}</button>
            @elseif($wire->status == '1' || $wire->status == 'Pending')
            <button type="button" class="opener wire-pending" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">10{{ $wire->wire_id }}</button>
            @elseif($wire->status == '2')
            <button type="button" class="opener wire-returned" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">10{{ $wire->wire_id }}</button>
            @elseif($wire->status == '3')
            <button type="button" class="opener wire-awaiting" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">10{{ $wire->wire_id }}</button>
            @elseif($wire->status == '4')
            <button type="button" class="opener wire-awaiting" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">10{{ $wire->wire_id }}</button>
            @elseif($wire->status == '5' || $wire->status == 'Complete')
            <button type="button" class="opener wire-complete" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">10{{ $wire->wire_id }}</button>
            @elseif($wire->status == '6' || $wire->status == 'Paid')
            <button type="button" class="opener wire-paid" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">10{{ $wire->wire_id }}</button>
         @endif
            </td>
            <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">{{ $wire->merch_name }}</td>
            <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">{{ $wire->bank_name }}</td>
            <td style="width: 200px;" class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">{{ $wire->client_name }}</td>
            <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">{{ $wire->amount_sent }} {{ $wire->currency }}</td>
            <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">{{ $wire->amount_received }} {{ $wire->currency_received }}</td>
            <td style="width: 180px;" class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">@if(!empty($wire->received_on)) {{ date('d/m/Y', strtotime($wire->received_on)) }} @else  @endif</td>
            <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">{{ $wire->percent }}%</td>
            <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">{{ $wire->amount_of_percent }} {{ $wire->currency_received }}</td>
            <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">{{ $wire->amountafterpercent }} {{ $wire->currency_received }}</td>
            <td class="text-center opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
                @if($wire->kyc == "uploaded")<img src="{{ asset('images/folder (3).png') }}" style="width:16px;" alt="Uploaded KYC" title="KYC Uploaded"><span class="sr-only">Uploaded</span>
                @elseif($wire->kyc == "none")<img src="{{ asset('images/folder (4).png') }}" style="width:16px;" alt="No KYC" title="No KYC"><span class="sr-only">No</span>
                @elseif($wire->kyc == "in_process")<img src="{{ asset('images/folder.png') }}" style="width:16px;" alt="In checking Process" title="In Process"><span class="sr-only">In Process</span>
                @elseif($wire->kyc == "missing_docs")<img src="{{ asset('images/folder (1).png') }}" style="width:16px;" alt="Missing Documents" title="Missing Documents"><span class="sr-only">Missing Docs</span>
                @elseif($wire->kyc == "approved")<img src="{{ asset('images/folder (2).png') }}" style="width:16px;" alt="KYC Approved" title="KYC Approved"><span class="sr-only">Approved</span>
                @else <span class="glyphicon glyphicon-question-sign"></span><span class="sr-only">?</span>
                @endif
            </td>
            <td class="icon_display text-center opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
                @if($wire->wc == "not_checked")<img src="{{ asset('images/user.png') }}" style="width:16px;" alt="Not Checked" title="Not Checked">
                @elseif($wire->wc == "no_match")<img src="{{ asset('images/user (2).png') }}" style="width:16px;" alt="No Matches" title="No Matches">
                @elseif($wire->wc == "existing")<img src="{{ asset('images/user (1).png') }}" style="width:16px;" alt="Existing Profile" title="Existing Profile">
                @else <span class="glyphicon glyphicon-question-sign"></span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div id="dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content info_wire_popup">
            <div class="modal-header text-center">
                <span data-field="id"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table-responsive popup_tbl">
                    <tr>
                        <td>Wire Status:</td><td><span data-field="status"></span></td>
                    </tr>
                    <tr>
                        <td>Company:</td><td><span data-field="merch_name"></span></td>
                    </tr>
                    <tr>
                        <td>Client's name:</td><td><span data-field="client_name"></span></td>
                    </tr>
                    <tr>
                        <td>Client's phone:</td><td><span data-field="phone"></span></td>
                    </tr>
                    <tr>
                        <td>Client's email:</td><td><span data-field="email"></span></td>
                    </tr>
                    <tr>
                        <td>Sending country: </td><td><span data-field="country"></span></td>
                    </tr>
                    <tr>
                        <td>Bank's name: </td><td><span data-field="bank"></span></td>
                    </tr>
                    <tr>
                        <td>Sent on:</td><td><span data-field="sent_on"></span></td>
                    </tr>
                    <tr>
                        <td>Sent funds: </td><td><span data-field="sent_amount"></span><span data-field="sent_currecy"></span></td>
                    </tr>
                    <tr>
                        <td>Received on:</td><td><span data-field="received_on"></span></td>
                    </tr>
                    <tr>
                        <td>Received funds:</td><td><span data-field="received_amount"></span><span data-field="received_currency"></span></td>
                    </tr>
                    <tr>
                        <td>Fee: </td><td><span data-field="fee"></span>% (<span data-field="fee_amount"></span><span data-field="received_currency"></span>)</td>
                    </tr>
                    <tr>
                        <td>Received funds<br/>after fee:</td><td><span data-field="amount_after_fee"></span><span data-field="received_currency"></span></td>
                    </tr>
                    <tr>
                        <td>World Check:</td><td><span data-field="wc"></span></td>
                    </tr>
                    <tr>
                        <td>KYC status:</td><td><span data-field="kyc"></span></td>
                    </tr>
                    <tr>
                        <td>Notes:</td><td><span data-field="notes"></span></td>
                    </tr>
                    <tr>
                        <td>Created at:</td><td><span data-field="created_at"></span></td>
                    </tr>
                    <tr>
                        <td>Last updated:</td><td><span data-field="updated_at"></span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@else
<div class="col-md-12">
    <div class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1"><img src="{{ asset('images/no_data.png') }}" alt="Nothing to display..." title="Nothing to display..."/></div>
</div>
@endif
<script>
    var date = new Date();
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    $('input[name="date"]').daterangepicker({
    locale: {
    format: 'YYYY-MM-DD'
    },
            startDate: firstDay,
            endDate: lastDay
    });
    $('#billing').DataTable({
    dom: '<"row"<"col-md-3 search_wrapper"f><"col-md-2 col-md-offset-5 export_btn_wrapper"B>>t<"foot"<"col-md-2 show_entries"l><"col-md-5 pagination_wrapper"p><"col-md-2 col-md-offset-3 show_count_entries"i>>',
            buttons: [
                {
                    extend: 'csvHtml5',
                    title: 'Billing Exp' + date,
                    className: 'csv_btn'
                },
                
            {
            extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: 'Billing Exp' + date,
                    className: 'pdf_btn'
            }
            ],
            order: [[1, "desc"]],
            "lengthMenu": [[25, 50, 100, - 1], [25, 50, 100, "All"]]
    });</script>
@endif
@stop