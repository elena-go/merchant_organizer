@extends('layout')
@section('content')

<!-- Error display -->
@if($errors->any()) @include('_includes.laravel_em') @endif
@if(Session::has('em')) @include('_errors.em') @endif
@if(Session::has('sm')) @include('_errors.sm') @endif
<!-- Script for Merchant's pop-up data display, table display options  -->
<script>
    $('div.sm-alert').delay(8000).slideUp(600);
    $('div.em-wrapper').delay(8000).slideUp(1400);</script>
<!-- End error display -->
<!-- Merchant's pop-up  -->
<div id="dialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <span data-field="name"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="regions section">
                    Regions:
                    <div id="listRegions"></div>
                </div>
                <div class="countries section">
                    <div id="listCountries"></div>
                </div>
                <div class="main section">
                    <table class="table-responsive">
                        <tr>
                            <td>Company ID:</td><td><span data-field="merch_id"></span></td>
                        </tr>
                        <tr>
                            <td>Status:</td><td><span data-field="status"></span></td>
                        </tr>
                        <tr>
                            <td>Address:</td><td><span data-field="address"></span></td>
                        </tr>
                        <tr>
                            <td>Mobile Phone:</td><td><span data-field="mobile"></span></td>
                        </tr>
                        <tr>
                            <td>Office Phone:</td><td><span data-field="landline"></span></td>
                        </tr>
                        <tr>
                            <td>E-mail: </td><td><span data-field="email"></span></td>
                        </tr>
                        <tr>
                            <td>Skype: </td><td><span data-field="skype"></span></td>
                        </tr>
                        <tr>
                            <td>Name of Beneficiary: </td><td><span data-field="account_holder"></span></td>
                        </tr>
                        <tr>
                            <td>Beneficiary Address:</td><td><span data-field="beneficiary_address"></span></td>
                        </tr>
                        <tr>
                            <td>Bank: </td><td><span data-field="bank_name"></span></td>
                        </tr>
                        <tr>
                            <td>Bank Address:</td><td><span data-field="bank_address"></span></td>
                        </tr>
                        <tr>
                            <td>EUR/IBAN:</td><td><span data-field="eur_iban"></span></td>
                        </tr>
                        <tr>
                            <td>SWIFT/BIC:</td><td><span data-field="swift_bic"></span></td>
                        </tr>
                        <tr>
                            <td>Available banks:</td><td><span data-field="available_banks"></span></td>
                        </tr>
                        <tr>
                            <td>Available currencies:</td>
                            <td>
                                <button id="btnCur">Currencies List</button>
                                <div class="currencies menu">
                                    <span data-field="available_currencies"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Reference:</td><td><span data-field="reference"></span></td>
                        </tr>
                        <tr>
                            <td>Started to work:</td><td><span data-field="created_at"></span></td>
                        </tr>
                        <tr>
                            <td>Last updated:</td><td><span data-field="updated_at"></span></td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<br/>
@if(!empty($merchants))
@if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
<div class="row">
    <div class="col-md-12">
        <h2>Companies</h2>
    </div>
</div>
<!-- Display data for Tech Team and Wire Manager/Team -->
<table id="merchants" class="table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Company name</th>
            <th>Bank</th>
            <th>E-Mail</th>
            <th>Mobile</th>
            <th>Skype</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($merchants as $merchant)
        @if($merchant->status == 0)
        <tr style="color: #a04646;" class="deletedMerch">
            @else
        <tr>
            @endif
            <td style="width: 80px;"><button type="button" class="opener merch-id" data-toggle="modal" data-id="{{ $merchant->id }}" data-target="#dialog">{{ $merchant->merchant_id }}</button></td>
            <td class="opener merch-id" data-toggle="modal" data-id="{{ $merchant->id }}" data-target="#dialog">{{ $merchant->name }}</td>
            <td class="opener merch-id" data-toggle="modal" data-id="{{ $merchant->id }}" data-target="#dialog">{{ $merchant->bank_name }}</td>
            <td class="opener merch-id" data-toggle="modal" data-id="{{ $merchant->id }}" data-target="#dialog">{{ $merchant->email }}</td>
            <td class="opener merch-id" data-toggle="modal" data-id="{{ $merchant->id }}" data-target="#dialog">{{ $merchant->m_code }} {{ $merchant->mobile_phone }} ({{ $merchant->m_country }})</td>
            <td class="opener merch-id" data-toggle="modal" data-id="{{ $merchant->id }}" data-target="#dialog">{{ $merchant->skype }}</td>
            <td>
                <!-------------------------------------------- Edit | Delete -------------------------------->
                @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                <a href="{{ url('companies/'.$merchant->id.'/edit') }}" class="edit_icon"><img src="{{ asset('images/edit.png') }}" style="width:15px;" alt="Edit" title="Edit"></a>
                @endif
                @if(Session::has('active_sa') && $merchant->status != 0)
                <a href="{{ url('companies/'.$merchant->id) }}" class="archive_icon"><img src="{{ asset('images/icon.png') }}" style="width:11px;" alt="Archive" title="Archive"></a>
                @endif
            </td>
        </tr>
        @endforeach
</table>
<script>
    $(function () {
    function showMerch(){
    setTimeout(function(){
    document.body.style.paddingRight = '0';
    }, 380);
    }
    function dtItemClick(){
    $(".opener").unbind('click').click(function () {
    showMerch();
    var dialog = $("#dialog"), item = merchs[$(this).attr('data-id')];
    for (var field in item) {
    var output = item[field];
    if (output instanceof Array){
    if (output[0] == '') output.shift();
    output = output.join('<br/>');
    }
    dialog.find('[data-field="' + field + '"]').html(output);
    }
    var regions_fee_list = $('#listRegions').empty();
    for (var i in item.fees){
    $('<div style="cursor: pointer;"></div>').text(item.fees[i].region_name + ': ' + item.fees[i].fee + '%').attr('data-region', item.fees[i].region_id).appendTo(regions_fee_list).click(function(){
    var region_id = $(this).attr('data-region');
    var region = item.fees.filter(function(x){ return x.region_id == region_id; })[0];
    var countries_fee_list = $('#listCountries').empty();
    for (var i in region.countries_fees){
    if (region.countries_fees[i] == '') continue;
    $('<div class="col-md-10"></div>').text(region.countries_fees[i].country_name).appendTo(countries_fee_list);
    $('<div class="col-md-2"></div>').text(region.countries_fees[i].fee + '%').appendTo(countries_fee_list);
    }
    });
    }
    });
    }
    var merchs = {};
    @foreach($merchants as $merch)
            merchs[{{$merch -> id }}] = {
    "id": '{{$merch->id }}',
            @if ($merch -> status == 0)
            "status": 'Archived',
            @else
            "status": 'Active',
            @endif
            "name" :'{{ $merch->name }}',
            "merch_id": '{{$merch->merchant_id }}',
            "address" :'{{ $merch->address }}',
            "mobile" :'{{ $merch->m_code }} {{ $merch->mobile_phone }} ({{ $merch->m_country }})',
            "landline" :'{{ $l_phone[$merch->merch_id][0]->l_code }} {{ $l_phone[$merch->merch_id][0]->landline_phone }} ({{ $l_phone[$merch->merch_id][0]->l_country }})',
            "skype" :'{{ $merch->skype }}',
            "email" :'{{ $merch->email }}',
            "account_holder" :'{{ $merch->account_holder }}',
            "beneficiary_address" :'{{ $merch->beneficiary_address }}',
            "bank_name" :'{{ $merch->bank_name }}',
            "bank_address" :'{{ $merch->bank_address }}',
            "eur_iban" :'{{ $merch->eur_iban }}',
            "reference" :'{{ $merch->reference }}',
            "swift_bic" :'{{ $merch->swift_bic }}',
            "available_banks" :[''
                    @foreach($merch_bank[$merch -> id] as $m_bank)
                    @if($m_bank->status == 0)
                        , '<span style="color: red;">{{ $m_bank->name }}</span>'
                        @else
                    , '{{ $m_bank->name }}'
                @endif
                    @endforeach],
            "available_currencies" : [''
                    @foreach($merch_currency[$merch -> id] as $m_curr)
                    , '{{ $m_curr->name }}'
                    @endforeach],
            "created_at" : '{{ $merch->created_at }}',
            "updated_at" : '{{ $merch->updated_at }}',
            @if (!empty($regions_fees[$merch -> id]))
            "fees":[''
                    @foreach($regions_fees[$merch -> id] as $m_reg)
                    , {
                    "region_id": '{{ $m_reg->region_id }}',
                            "region_name": '{{ $m_reg->name }}',
                            "fee": '{{ $m_reg->fee }}',
                            @if (!empty($country_fees[$merch -> id]))
                            "countries_fees":[''
                                    @foreach($country_fees[$merch -> id] as $m_countries)
                                    @foreach($m_countries as $m_country)
                                    @if ($m_country -> region == $m_reg -> region_id)
                                    , {
                                    "country_id": '{{ $m_country->country_id }}',
                                            "country_name": '{{ $m_country->country }}',
                                            "region_id": '{{ $m_country->region }}',
                                            "fee": '{{ $m_country->fee }}'}
                            @endif
                                    @endforeach
                                    @endforeach]
                            @endif
                    }
            @endforeach],
            @endif
    };
    @endforeach
    dtItemClick();
    $('#dialog .close').click(showMerch);
    $('#btnCur').click(function(){
    $('div.currencies.menu').css({height:100}).toggle('slow');
    });
    });
</script>
<script>
    $('#merchants').DataTable({
    "language": {
    "paginate": {
    "previous": "&lt;",
            "next": "&gt;"
    }
    },
            dom: '<"row"<"col-md-3 search_wrapper"f><"col-md-2 add_btn_wrapper"><"col-md-2 show_hide_merch">>t<"foot"<"col-md-2 show_entries"l><"col-md-5 pagination_wrapper"p><"col-md-2 col-md-offset-3 show_count_entries"i>>',
            order: [[1, "asc"]],
            "lengthMenu": [[25, 50, 100, - 1], [25, 50, 100, "All"]]
    });
            @if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
            $("div.add_btn_wrapper").html('<a href="{{ url('companies/create') }}"><span class="glyphicon glyphicon-plus add-btn"></span> New Company</a>');
            @endif
            @if(Session::has('active_sa'))
            $("div.show_hide_merch").html('<input type="checkbox" id="show_hide"> Show archived companies');
            $('.show_hide_merch').click(function(){
                var x = document.getElementsByClassName("deletedMerch");
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
</script>
<!-- Display data for Merchant's user -->
@elseif(Session::has('active_m') || Session::has('limited_m'))
<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
    <div class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1" style="background-color: #ffffff;">
        <h2>Company Info:</h2>
        <table class=" table table-responsive merch_prof">
            <tr>
                <td>Company Name:</td>
                <td>{{ $merchants[0]->name }}</td>
            </tr>
            <tr>
                <td>Address:</td>
                <td>{{ $merchants[0]->address }}</td>
            </tr>
            <tr>
                <td>Contact information:</td>
                <td>{{ $merchants[0]->m_code }}{{ $merchants[0]->mobile_phone  }}<br/>
                    @if(!empty($merchants[0]->l_code))
                    {{ $merchants[0]->l_code }}{{ $merchants[0]->landline_phone }}<br/>
                    @endif
                    {{ $merchants[0]->skype }}<br/>
                    {{ $merchants[0]->email }}
                </td>
            </tr>
            <tr>
                <td>Company ID:</td>
                <td>{{ $merchants[0]->merchant_id }}</td>
            </tr>
            <tr>
                <td>Name of Beneficiary:</td>
                <td>{{ $merchants[0]->account_holder }}<br/>
                    {{ $merchants[0]->account_holder }}
                </td>
            </tr>
            <tr>
                <td>Bank details:</td>
                <td>Name: {{ $merchants[0]->bank_name }}<br/>
                    Address: {{ $merchants[0]->bank_address }}<br/>
                    EUR/IBAN: {{ $merchants[0]->eur_iban }}<br/>
                    SWIFT/BIC: {{ $merchants[0]->swift_bic }}
                </td>
            </tr>
            <tr>
                <td>Available banks:</td>
                <td>
                    @foreach($merch_bank as $m_bank)
                    {{ link_to_asset('bank/'.$m_bank->file, $m_bank->name) }}<br/>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>Available currencies:</td>
                <td>
                    @foreach($merch_currency as $m_currency)
                    {{ $m_currency->name }},
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>Users:</td>
                <td>
                    @foreach($merchants_users as $m_user)
                    {{ $m_user->name }} {{ $m_user->lastname }}<br/>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>Web:</td>
                <td>
                    @foreach($merchants_users as $m_user)
                    {{ $m_user->url }}<br/>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>
</div>
@endif
@else
<h1>No Companies</h1>
@if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
<h3><a href="{{ url('companies/create') }}">Here</a> you can add first company.</h3>
@endif
@endif
@stop