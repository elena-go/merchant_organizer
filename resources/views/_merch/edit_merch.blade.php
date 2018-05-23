@extends('layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<link type="text/css" rel="stylesheet" href="{{ asset('css/multi-select.css') }}">
<h2 class="edit_merch_h2">Edit Company : {{ $merchant->name }}</h2>
<div class="row placeholders text-center">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 text-left">
        <form class="new-user form-inline" method="post" action="{{ url('companies/'.$merchant->id) }}">
            <div class="row">
                <!-- Error message display -->
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                    @if($errors->any())
                    @include('_errors.laravel_em')
                    @endif
                    @if(Session::has('em'))
                    @include('_errors.em')
                    @endif
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 form_wrapper form-tbl">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="id" value="{{ $merchant->id }}">
                    <table class="table table-responsive edit_merch_table_wrapper">

                        <!-- Disable/Archive or Activate the merchant
                        While merchant is Inactive - Users that are connected to him are not able to do nothing but seeing the data (wires that was made, personal and merchants data)
                        -->
                        @if(Session::has('active_sa'))
                        <tr>
                            <td>Status:</td>
                            <td>
                                <input type="radio" name="status" value="1" @if($merchant->status == 1) checked @endif> Active
                                       <input type="radio" name="status" value="0" @if($merchant->status == 0) checked @endif> Inactive
                            </td>
                        </tr>
                        @else
                        <input type="hidden" name="status" value="1">
                        @endif
                        <tr>
                            <td><span class="star">*</span> Company name:</td>
                            <td>
                                <input type="text" name="name" required="required" placeholder="Merchant Name" value="{{ $merchant->name }}">
                            </td>
                        </tr>
                        <tr>
                            <td><span class="star">*</span> Email:</td>
                            <td>
                                <input type="email" name="email" required="required" placeholder="j.doe@email.com" value="{{ $merchant->email }}">
                            </td>
                        </tr>
                        <tr>
                            <td><span class="star">*</span> Mobile Phone:</td>
                            <td>
                                <div class="form-group" style="width: 29%;">
                                    <select class="mobile_code" name="mobile_code" required="required" placeholder="+(123) Country">
                                        <option value="{{ $merchant->m_id }}">{{ $merchant->m_code }} {{ $merchant->m_country }}</option>
                                        @include('_includes/phone_codes_list')
                                    </select>
                                </div>
                                <div class="form-group" style="width: 70%;">
                                    <input type="text" class="mobile_phone" name="mobile_phone" required="required" placeholder="656-4789" value="{{ $merchant->mobile_phone }}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="full_wire_tbl_td">Landline Phone:</td>
                            <td>
                                <div class="form-group" style="width: 29%;">
                                    <select class="mobile_code" name="landline_code" placeholder="+(123) Country" value="{{ Input::old('landline_code') }}">
                                        <option value="{{ $l_phone[0]->l_id }}">{{ $l_phone[0]->l_code }} {{ $l_phone[0]->l_country }}</option>
                                        @include('_includes/phone_codes_list')
                                    </select>
                                </div>
                                <div class="form-group" style="width: 70%;">
                                    <input type="text" class="mobile_phone" name="landline_phone" placeholder="656-4789" value="{{ $merchant->landline_phone}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="full_wire_tbl_td"><span class="star">*</span> Skype:</td>
                            <td>
                                <input type="text" name="skype"  required="required" placeholder="skype.123" value="{{ $merchant->skype }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="full_wire_tbl_td">Address:</td>
                            <td>
                                <input type="text" name="address" placeholder="Country, City, Street 100/100"  value="{{ $merchant->address }}">
                            </td>
                        </tr>
                        <tr>
                            <td class="full_wire_tbl_td"><span class="star">*</span> Bank Name:</td>
                            <td><input type="text" name="bank_name" required="required" placeholder="Bank Name" value="{{ $merchant->bank_name }}"></td>
                        </tr>

                        <tr>
                            <td class="full_wire_tbl_td"><span class="star">*</span> Bank Address:</td>
                            <td><input type="text" name="bank_address" required="required" placeholder="Country, City" value="{{ $merchant->bank_address }}"></td>
                        </tr>
                        <tr>
                            <td class="full_wire_tbl_td"><span class="star">*</span> Name of Beneficiary:</td>
                            <td><input type="text" name="account_holder" required="required" placeholder="Name Lastname" value="{{ $merchant->account_holder }}"></td>
                        </tr>
                        <tr>
                            <td class="full_wire_tbl_td"><span class="star">*</span> Beneficiary<br/>Address:</td>
                            <td><input type="text" name="beneficiary_address" required="required" placeholder="Country, City" value="{{ $merchant->beneficiary_address }}"></td>
                        </tr>
                        <tr>
                            <td class="full_wire_tbl_td"><span class="star">*</span> EUR IBAN:</td>
                            <td><input type="text" name="eur_iban" required="required" placeholder="ZZ11222233335555777788" value="{{ $merchant->eur_iban }}"></td>
                        </tr>
                        <tr>
                            <td class="full_wire_tbl_td"><span class="star">*</span> SWIFT/BIC Code:</td>
                            <td><input type="text" name="swift_bic" required="required" placeholder="AAAABBCCDDD" value="{{ $merchant->swift_bic }}"></td>
                        </tr>
                        <tr>
                            <td class="full_wire_tbl_td">Reference</td>
                            <td><input type="text" name="reference" placeholder="Settlement" value="{{ $merchant->reference }}"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table class="currency_and_bank_table">
                                    <th>Currencies:</th>
                                    <th>Banks:</th>
                                    <tr>
                                        <td>
                                            <!-- Got from "Currency" Model -->
                                            <select id='select-currency' name="currency[]" required="required" multiple='multiple'>
                                                @foreach($currencys as $curr)
                                                <option value="{{ $curr->id }}" @if (in_array($curr->id, $merch_currencies) ) echo ' selected="selected"'; @endif>
                                                        {{ $curr->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <!-- Got from "Bank" Model -->
                                        <select id='select-bank' name="bank[]" required="required" multiple='multiple'>
                                            @foreach($banks as $row)
                                            <option value="{{ $row->bank_id }}" @if (in_array($row->bank_id, $merch_banks) ) echo ' selected="selected"'; @endif>
                                                    {{ $row->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>Fees:</td>
                    <td>
                        <!-- Got from "Fee" Model -->
                        @if(count($region_fee) == 4 && !empty($country_fee))
                        <div id="fee_accordion">
                            @foreach($region_fee as $m_reg)
                            <h3><span class="reg_name">{{ $m_reg->r_name }}</span> - <input type="number" step="0.01" min="0.00" max="10" style="width:50px;" name="{{ 'region_' . $m_reg->id }}" value="{{ $m_reg->fee }}"></h3>
                            <div>
                                <table class="table-striped">
                                    @foreach($country_fee as $m_countries)
                                    @foreach($m_countries as $m_country)
                                    @if($m_country->region == $m_reg->region_id)
                                    @if($m_country->fee != $m_reg->fee)
                                    <tr class="different_fee">
                                        <td>{{ $m_country->country_name }}</td>
                                        <td><input type="number" step="0.01" min="0.00" max="10" style="width:50px;" name="{{ 'country_' . $m_country->country_id }}" value="{{ $m_country->fee }}"></td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>{{ $m_country->country_name }}</td>
                                        <td><input type="number" step="0.01" min="0.00" max="10" style="width:50px;" name="{{ 'country_' . $m_country->country_id }}" value="{{ $m_country->fee }}"></td>
                                    </tr>
                                    @endif
                                    @endif
                                    @endforeach
                                    @endforeach
                                </table>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <!-- Got from "Fee" Model -->
                        <ul>
                            @foreach($regions as $region)
                            <li>{{ $region->name }} <input type="number" name="{{ 'region_' . $region->id }}" value="{{ $region->fee }}"/></li>
                            @endforeach
                        </ul>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="button" value="Cancel" onclick="window.location ='{{ url('companies') }}'" class="btn btn-back"> <input type="submit" class="submit" value="Update" onclick="this.disabled=true;this.value='Saving...'; this.form.submit();">
                    </td>
                </tr>
            </table>
        </div>
</form>
</div>
</div>
</div>
<script src="{{ asset('js/jquery.multi-select.js') }}" type="text/javascript"></script>
<!-- Script for Multi-selectable column: currencies and banks -->
<script>
$('#select-currency').multiSelect({
    selectableHeader: "<div class='custom-header'>Not in use:</div>",
    selectionHeader: "<div class='custom-header'>Currencies in use:</div>"
});
$('#select-bank').multiSelect({
    selectableHeader: "<div class='custom-header'>Not in use:</div>",
    selectionHeader: "<div class='custom-header'>Banks in use:</div>"
});
$('#fee_accordion input').click(function (e) {
    e.stopPropagation();
});
$("#fee_accordion").accordion({
    collapsible: true,
    active: 5,
    heightStyle: "content"
});

</script>
@stop
