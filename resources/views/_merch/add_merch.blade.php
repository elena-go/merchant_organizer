@extends('layout')
@section('content')
<br/>
<h2>Add New Company</h2>
<div class="row placeholders text-center">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 text-left">
        <form class="new-user form-inline" method="post" action="{{ url('companies') }}">
            {{ csrf_field() }}
            <div class="row">
                <!-- Error display -->
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                    @if($errors->any())
                    @include('_errors.laravel_em')
                    @endif
                    @if(Session::has('em'))
                    @include('_errors.em')
                    @endif
                </div>
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 form_wrapper form-tbl">
                    <table class="table table-responsive add_merch_table_wrapper">
                        <tr>
                            <td><span class="star">*</span> Company name:</td>
                            <td>
                                <input type="text" name="name" required="required" placeholder="Company Name" value="{{ Input::old('name') }}">
                            </td>
                        </tr>
                        <tr>
                            <td><span class="star">*</span> Email:</td>
                            <td>
                                <input type="text" name="email" required="required" placeholder="email@domain.com" value="{{ Input::old('email') }}">
                            </td>
                        </tr>
                        <tr>
                            <td><span class="star">*</span> Mobile Phone:</td>
                            <td>
                                <div class="form-group" style="width: 29%;">
                                    <select class="mobile_code" name="mobile_code" required="required" placeholder="+(123) Country" value="{{ Input::old('mobile_code') }}">
                                        @include('_includes/phone_codes_list')
                                    </select>
                                </div>
                                <div class="form-group" style="width: 70%;">
                                    <input type="text" class="mobile_phone" name="mobile_phone" required="required" placeholder="656-4789" value="{{ Input::old('mobile_phone') }}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Landline Phone:</td>
                            <td>
                                <div class="form-group" style="width: 29%;">
                                    <select class="mobile_code" name="landline_code" placeholder="(123)" value="{{ Input::old('landline_code') }}">
                                        @include('_includes/phone_codes_list')
                                    </select>
                                </div>
                                <div class="form-group" style="width: 70%;">
                                    <input type="text" class="mobile_phone" name="landline_phone" placeholder="4567-890" value="{{ Input::old('landline_phone') }}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="star">*</span> Skype:</td>
                            <td>
                                <input type="text" name="skype" required="required" placeholder="skype.123" value="{{ Input::old('skype') }}">
                            </td>
                        </tr>
                        <tr>
                            <td>Address:</td>
                            <td>
                                <input type="text" name="address" placeholder="Country, City, Street 100/100" value="{{ Input::old('address') }}">
                            </td>
                        </tr>
                        <tr>
                            <td><span class="star">*</span> Bank's Name:</td>
                            <td><input type="text" name="bank_name" required="required" placeholder="Bank Name" value="{{ Input::old('bank_name') }}"></td>
                        </tr>

                        <tr>
                            <td><span class="star">*</span> Bank's Address:</td>
                            <td><input type="text" name="bank_address" required="required" placeholder="Country, City" value="{{ Input::old('bank_address') }}"></td>
                        </tr>
                        <tr>
                            <td><span class="star">*</span> Name of Beneficiary:</td>
                            <td><input type="text" name="account_holder" required="required" placeholder="Name Lastname" value="{{ Input::old('account_holder') }}"></td>
                        </tr>
                        <tr>
                            <td><span class="star">*</span> Beneficiary<br/>Address:</td>
                            <td><input type="text" name="beneficiary_address"  required="required" placeholder="Country, City" value="{{ Input::old('beneficiary_address') }}"></td>
                        </tr>
                        <tr>
                            <td><span class="star">*</span> EUR IBAN:</td>
                            <td><input type="text" name="eur_iban" required="required" placeholder="ZZ11222233335555777788" value="{{ Input::old('eur_iban') }}"></td>
                        </tr>
                        <tr>
                            <td><span class="star">*</span> SWIFT/BIC Code:</td>
                            <td><input type="text" name="swift_bic" required="required" placeholder="AAAABBCCDDD" value="{{ Input::old('swift_bic') }}"></td>
                        </tr>
                        <tr>
                            <td>Reference</td>
                            <td><input type="text" name="reference" placeholder="Settlement" value="{{ Input::old('reference') }}"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table class="currency_and_bank_table">
                                    <th><span class="star">*</span> Check Currencies:</th>
                                    <th><span class="star">*</span> Choose Banks:</th>
                                    <tr>
                                        <td>
                                            <!-- Took from "Currency" Model -->
                                            <select id='select-currency' name="currency[]" required="required" multiple='multiple'>
                                                @foreach($currencys as $currency)
                                                <option value="{{ $currency->id }}" selected>{{ $currency->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <!-- Got from "Bank" Model -->
                                            <select id='select-bank' name="bank[]" multiple='multiple'>
                                                @foreach($banks as $bank)
                                                <option value="{{ $bank->bank_id }}" @if (Input::old('bank[]') == 'selected' ) echo ' selected '; @endif>{{ $bank->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="star">*</span> Fees:</td>
                            <td>
                                <table class="add_merch_fee_table">
                                    <!-- Took from "Fee" Model -->
                                    @foreach($regions as $region)
                                    <tr>
                                        <td>{{ $region->name }}:</td>
                                        <td><input type="number" name="{{ 'region_' . $region->id }}" min="0.00" max="10" value="{{ $region->fee }}"/></td>
                                    </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    </table>
                    <input type="button" value="Cancel" onclick="window.location ='{{ url('companies') }}'" class="btn btn-back">
                    <input type="submit" class="submit" value="Create" onclick="this.disabled=true;this.value='Saving...'; this.form.submit();">
                </div>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('js/jquery.multi-select.js') }}" type="text/javascript"></script>
<!-- Script for multiselectable column for currency's and bank's list -->
<script>
                        $('#select-currency').multiSelect({
                            selectableHeader: "<div class='custom-header'>Currencies NOT in Use</div>",
                            selectionHeader: "<div class='custom-header'>Available currencies</div>"
                        });
                        $('#select-bank').multiSelect({
                            selectableHeader: "<div class='custom-header'>Banks NOT in Use</div>",
                            selectionHeader: "<div class='custom-header'>Available banks</div>"
                        });
</script>
@stop