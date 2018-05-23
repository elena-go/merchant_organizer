@extends('layout')
@section('content')
@if($is_login == true)
<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="{{ asset('js/filters.js') }}"></script>
<div id="user_overview_top_row" class="row top-row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h2>Wires</h2>
    </div>
</div>
<script>
$(function() {
$("#accordion").accordion({
collapsible: true,
        active: 0,
        animate: 200
        });
});</script>
<script>
    $(function () {
    function showWire(){
    setTimeout(function(){
    document.body.style.paddingRight = '0';
    }, 380);
    }
    var f = new Filters('wires_filters', {{ Session::get('user_id') }});
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
            "created_at" : '{{ date("d/m/Y", strtotime($wire->created_at)) }}',
            "updated_at" : '{{ date("d/m/Y",strtotime($wire->updated_at)) }}'

    };
    @endforeach
            $(".opener").click(function () {
    showWire();
    var dialog = $("#dialog"), item = wires[$(this).attr('data-id')];
    for (var field in item) {
    dialog.find('[data-field="' + field + '"]').text(item[field]);
    }
    });
    $('#dialog .close').click(showWire);
    });</script>
<div id="accordion" class="wire-filter-wrapper">
    <h3><span class="glyphicon glyphicon-cog icon-filter"></span> Wires Filter</h3>
    <div class="row accordion-form-wrapper">
        <form method="GET" action="" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="accordion-inner-wrapper">
                <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <p class="p-filter"><span class="glyphicon glyphicon-calendar"></span> by Date</p>
                    <input type="text" name="date">
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <p class="p-filter"><span class="glyphicon glyphicon-tag"></span> Wire Status</p>
                    <select name="status">
                        <option value="-">All</option>
                        <option value="1">Pending</option>
                        <option value="2">Returned</option>
                        <option value="3">Compliance</option>
                        <option value="4">Awaiting Funds</option>
                        <option value="5">Approved</option>
                        <option value="6">Paid</option>
                    </select>
                </div>
                    @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b'))
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <p class="p-filter"><span class="glyphicon glyphicon-briefcase"></span> Company</p>
                    <select name="merchant">
                        <option value="-">All</option>
                        @foreach($merchants as $merch)
                        <option value="{{ $merch->merch_id }}">{{ $merch->name }}</option>
                        @endforeach
                    </select>
                </div>
                    @elseif(Session::has('active_m'))
                    <input type="hidden" name="merchant" value="{{ Session::get('active_m') }}">
                    @elseif(Session::has('limited_m'))
                    <input type="hidden" name="merchant" value="{{ Session::get('limited_m') }}">
                    @endif
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <p class="p-filter"><span class="glyphicon glyphicon-credit-card"></span> Bank</p>
                    <select name="bank">
                        <option value="-">All</option>
                        @foreach($banks as $bank)
                        <option value="{{ $bank->bank_id }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="row">
                @if($received_w_count[0]->wire_count != 0)
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <p class="p-filter"><span class="glyphicon glyphicon-euro"></span> Currency (Received)</p>
                    <select name="currency_r">
                        <option value="-">All</option>
                        @foreach($currencys as $currency)
                        <option value="{{ $currency->name }}">{{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                    <p class="p-filter"><span class="glyphicon glyphicon-euro"></span> Currency (Sent)</p>
                    <select name="currency_s">
                        <option value="-">All</option>
                        @foreach($currencys as $currency)
                        <option value="{{ $currency->name }}">{{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 checkbox-div">
                    <p class="p-filter"><span class="glyphicon glyphicon-file"></span> KYC status</p>
                    <table>
                        <tr>
                            <td><input type="checkbox" name="kyc[]" value="none" checked="checked"> No KYC</td>
                            <td><input type="checkbox" name="kyc[]" value="uploaded" checked="checked">Uploaded<br/></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="kyc[]" value="in_process" checked="checked">In Process</td>
                            <td><input type="checkbox" name="kyc[]" value="missing_docs" checked="checked">Missing Docs<br/></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="kyc[]" value="approved" checked="checked">Approved<br/></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                    @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b'))
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 checkbox-div">
                        <p class="p-filter"><span class="glyphicon glyphicon-user"></span> WorldCheck status</p>
                        <input type="checkbox" name="wc[]" value="not_checked" checked="checked"> Not Checked
                        <input type="checkbox" name="wc[]" value="no_match" checked="checked"> No Match <br/>
                        <input type="checkbox" name="wc[]" value="existing" checked="checked"> Existing 
                    </div>
                    @endif
                <div class="row">
                    @if(Session::has('active_sa'))
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-6 checkbox-div">
                        <input type="checkbox" id="show_hide" class="show_archived_btn">Show archived wires
                    </div>
                    @endif
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6"><input type="submit" name="search" class="filter-search" value="Search"></div>
                </div>
                </div>
            </div>
        </form>
    </div>
</div>
@include('_errors.sm')
@if(Session::has('em'))
@include('_errors.em')
@endif
<script>
    $('div.sm-alert').delay(8000).slideUp(600);
    $('div.em-wrapper').delay(8000).slideUp(1400);
</script>
@if(!empty($wires))
@if(isset($balances))
<div class="row top-row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 balance_wrapper">
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
                        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                        <tr>
                            <td>Fee:</td>
                        </tr>
                        <tr>
                            <td>Total:</td>
                        </tr>
                        @endif
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
                            <td>{{ number_format(floatval($balance[0]->total_amount_received),2,".",",") }}</td>
                        </tr>
                        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                        <tr>
                            <td>{{ number_format(floatval($balance[0]->total_amount_of_percent),2,".",",") }}</td>
                        </tr>
                        <tr>
                            <td>{{ number_format(floatval($balance[0]->total_amountafterpercent),2,".",",") }}</td>
                        </tr>
                        @endif
                    </table>
                </td>
                @endif
                @endforeach
            </tr>
        </table>
    </div>
</div>
@endif
<br/>
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
                        <td>Sent on:</td><td><span data-field="money_sent"></span></td>
                    </tr>
                    <tr>
                        <td>Sent funds: </td><td><span data-field="sent_amount"></span><span data-field="sent_currency"></span></td>
                    </tr>
                    <tr>
                        <td>Received on:</td><td><span data-field="received_on"></span></td>
                    </tr>
                    <tr>
                        <td>Received funds:</td><td><span data-field="received_amount"></span><span data-field="received_currency"></span></td>
                    </tr>
                    @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                    <tr>
                        <td>Fee: </td><td><span data-field="fee"></span>% (<span data-field="fee_amount"></span><span data-field="received_currency"></span>)</td>
                    </tr>
                    <tr>
                        <td>Received funds<br/>after fee:</td><td><span data-field="amount_after_fee"></span><span data-field="received_currency"></span></td>
                    </tr>
                    <tr>
                        <td>World Check:</td><td><span data-field="wc"></span></td>
                    </tr>
                    @endif
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
<table id="wires" class="table-hover table-responsive" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th class="wire_status">Status</th>
            <th>ID</th>
            <th>Sent on</th>
            @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b'))
            <th>Company</th>
            @endif
            <th style="width: 250px;">Name</th>
            <th>Amount Sent</th>
            <th><img src="{{ asset('images/currencies.png') }}" style="width:15px;" alt="Currency" title="Currency"></th>
            <th>Receive Funds</th>
            <th><img src="{{ asset('images/currencies.png') }}" style="width:15px;" alt="Currency" title="Currency"></th>
            <th>Received on</th>
            @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
            <th>Fee</th>
            <th>Amount of fee</th>
            <th><img src="{{ asset('images/currencies.png') }}" style="width:15px;" alt="Currency" title="Currency"></th>
            <th>Funds after fee</th>
            <th><img src="{{ asset('images/currencies.png') }}" style="width:15px;" alt="Currency" title="Currency"></th>
            @endif
            <th>KYC</th>
            @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b'))
            <th>World Check</th>
            @endif
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($wires as $wire)
        @if($wire->active == 0)
        <tr class="deletedWires">
            @else
            <tr>
            @endif
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
    <td style="width: 100px;" class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        {{ date('d/m/Y', strtotime($wire->sent_on)) }}
    </td>
    @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b'))
    <td style="width: 280px; padding-left: 8px;" class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        {{ $wire->merch_name }}
    </td>
    @endif
    <td style="width: 250px; text-align: left; padding-left: 8px;" class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        {{ $wire->client_name }}
    </td>
    <td class="amount_display opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        {{ number_format(floatval($wire->amount_sent),2,".",",") }}
    </td>
    <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        {{ $wire->currency }}
    </td>
    <td class="amount_display opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        @if(!empty($wire->amount_received)) {{ number_format(floatval($wire->amount_received),2,".",",") }} @else <img src="{{ asset('images/minus.png') }}" style="width:16px;" alt="-" title="-"> @endif
    </td>
    <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        @if(!empty($wire->currency_received)){{ $wire->currency_received }} @else <img src="{{ asset('images/minus.png') }}" style="width:16px;" alt="-" title="-"> @endif
    </td>
    <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        @if(!empty($wire->received_on)) {{ date('d/m/Y', strtotime($wire->received_on)) }} @else <img src="{{ asset('images/minus.png') }}" style="width:16px;" alt="-" title="-"> @endif
    </td>
    @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
    <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        @if(!empty($wire->percent)) {{ $wire->percent }}% @else <img src="{{ asset('images/minus.png') }}" style="width:16px;" alt="-" title="-"> @endif
    </td>
    <td style="width:100px;" class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        @if(!empty($wire->amount_of_percent)) {{ number_format(floatval($wire->amount_of_percent),2,".",",") }} @else <img src="{{ asset('images/minus.png') }}" style="width:16px;" alt="-" title="-"> @endif
    </td>
    <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        @if(!empty($wire->currency_received)) {{ $wire->currency_received }} @else <img src="{{ asset('images/minus.png') }}" style="width:16px;" alt="-" title="-"> @endif
    </td>
    <td class="amount_display opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        @if(!empty($wire->amountafterpercent)) {{ number_format(floatval($wire->amountafterpercent),2,".",",") }} @else <img src="{{ asset('images/minus.png') }}" style="width:16px;" alt="-" title="-"> @endif
    </td>
    <td class="opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        @if(!empty($wire->currency_received)) {{ $wire->currency_received }} @else <img src="{{ asset('images/minus.png') }}" style="width:16px;" alt="-" title="-"> @endif
    </td>
    @endif
    <td class="text-center opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        @if($wire->kyc == "uploaded")<img src="{{ asset('images/folder (3).png') }}" style="width:16px;" alt="Uploaded KYC" title="KYC Uploaded"><span class="sr-only">Uploaded</span>
        @elseif($wire->kyc == "none")<img src="{{ asset('images/folder (4).png') }}" style="width:16px;" alt="No KYC" title="No KYC"><span class="sr-only">No</span>
        @elseif($wire->kyc == "in_process")<img src="{{ asset('images/folder.png') }}" style="width:16px;" alt="In checking Process" title="In Process"><span class="sr-only">In Process</span>
        @elseif($wire->kyc == "missing_docs")<img src="{{ asset('images/folder (1).png') }}" style="width:16px;" alt="Missing Documents" title="Missing Documents"><span class="sr-only">Missing Docs</span>
        @elseif($wire->kyc == "approved")<img src="{{ asset('images/folder (2).png') }}" style="width:16px;" alt="KYC Approved" title="KYC Approved"><span class="sr-only">Approved</span>
        @else <span class="glyphicon glyphicon-question-sign"></span><span class="sr-only">?</span>
        @endif
    </td>
    @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b'))
    <td class="icon_display text-center opener" data-toggle="modal" data-id="{{ $wire->id }}" data-target="#dialog">
        @if($wire->wc == "not_checked")<img src="{{ asset('images/user.png') }}" style="width:16px;" alt="Not Checked" title="Not Checked">
        @elseif($wire->wc == "no_match")<img src="{{ asset('images/user (2).png') }}" style="width:16px;" alt="No Matches" title="No Matches">
        @elseif($wire->wc == "existing")<img src="{{ asset('images/user (1).png') }}" style="width:16px;" alt="Existing Profile" title="Existing Profile">
        @else <span class="glyphicon glyphicon-question-sign"></span>
        @endif
    </td>
    @endif
    <td style="width: 60px;">
        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b'))
        <a href="{{ url('wires/'.$wire->id.'/edit') }}"><img src="{{ asset('images/edit.png') }}" style="width:15px;" alt="Edit" title="Edit"></a>
        @elseif(Session::has('active_m') && ($wire->kyc != "approved" || $wire->kyc == "in_process"))
        <a href="{{ url('wires/'.$wire->id.'/edit') }}"><img src="{{ asset('images/edit.png') }}" style="width:15px;" alt="Edit" title="Edit"></a>
        @endif
        @if(Session::has('active_sa') || Session::has('active_mw'))
        @if($wire->active != 0)
        <a href="{{ url('wires/'.$wire->id) }}"><img src="{{ asset('images/icon.png') }}" style="width:11px;" alt="Archive" title="Archive"></a>
        @endif
        @endif
    </td>
</tr>
@endforeach
</tbody>
</table>
<div class="foot"></div>
@else
<div class="col-md-12">
    <h1>No Wires</h1>
    @if(Session::has('active_sa') || Session::has('active_m'))
    <h3><a href="{{ url('wires/create') }}">Here</a> you can add first wire.</h3>
    @endif
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
    $('#wires').DataTable({
        "language": {
            "paginate": {
                "previous": "&lt;",
                "next": "&gt;"
            }
        },
        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
    dom: '<"row"<"col-md-3 search_wrapper"f><"col-md-2 add_btn_wrapper"><"col-md-2 col-md-offset-5 export_btn_wrapper"B>>t<"foot"<"col-md-2 show_entries"l><"col-md-5 pagination_wrapper"p><"col-md-2 col-md-offset-3 show_count_entries"i>>',
            "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ -1, -2, - 3 ] }
            ],
            buttons: [
            {
            extend: 'csvHtml5',
                    title: 'Wire Exp' + date,
                    className: 'csv_btn'
            },
            {
            extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: 'Wire Exp' + date,
                    className: 'pdf_btn'
            }
            ],
            order: [[1, "desc"]],
            "lengthMenu": [[25, 50, 100, - 1], [25, 50, 100, "All"]]
            
            @else
                dom: '<"row"<"col-md-3 search_wrapper"f><"col-md-2 add_btn_wrapper"><"col-md-2 col-md-offset-5 export_btn_wrapper"B>>t<"foot"<"col-md-2 show_entries"l><"col-md-5 pagination_wrapper"p><"col-md-2 col-md-offset-3 show_count_entries"i>>',
            "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ -1, - 2 ] }
            ],
            buttons: [
            {
            extend: 'csvHtml5',
                    title: 'Wire Exp' + date,
                    className: 'csv_btn',
                    exportOptions: {
                    columns: [ 0, 1, 2, 5, 6, 7,8, 9, 10 ]
                    }
            },
            {
            extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: 'Wire Exp' + date,
                    className: 'pdf_btn',
                    exportOptions: {
                    columns: [ 0, 1, 2, 5, 6, 7,8, 9, 10 ]
                    }
            }
            ],
            order: [[1, "desc"]],
            "lengthMenu": [[25, 50, 100, - 1], [25, 50, 100, "All"]]
                @endif
    });
    @if(Session::has('active_sa') || Session::has('active_m'))
        $("div.add_btn_wrapper").html('<a href="{{ url('wires/create') }}"><span class="glyphicon glyphicon-plus add-btn"></span> New Wire</a>');
    @if(Session::has('active_sa'))
        $('.show_archived_btn').click(function(){
                var x = document.getElementsByClassName("deletedWires");
                if(document.getElementById('show_hide').checked == true ){
                    for (var i = 0; i < x.length; i++) {
                        x[i].style.display = 'table-row';
                    }
                }else{
                    for (var i = 0; i < x.length; i++) {
                        x[i].style.display = 'none';
                    }
                }
    });
    @endif
    @endif
</script>
@else
<div class="col-md-12 col-lg-10 col-lg-offset-1 col-sm-12 col-xs-12">
    <h1>No Wires</h1>
    @if(Session::has('active_sa') || Session::has('active_m'))
    <h3><a href="{{ url('wires/create') }}">Here</a> you can add first company.</h3>
    @endif
</div>
@endif
@stop