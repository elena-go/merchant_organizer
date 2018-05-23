@extends('layout')
@section('content')
<br/>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
    $(function () {
        $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd' })
    });
</script>
<h2>Add New Wire</h2>
<div class="row placeholders text-center">
    <div class="col-md-3 col-lg-3 col-sm-4 col-xs-12">
        @if($errors->any())
        @include('_errors.laravel_em')
        @endif
        @if(Session::has('em'))
        @include('_errors.em')
        @endif
    </div>
    <div class="col-md-6 col-lg-6 col-sm-8 col-xs-12 text-left form_wrapper">
        <form class="new-wire form-inline" method="post" action="{{ url('wires') }}">
            {{ csrf_field() }}
            <table class="table table-responsive">
                <tr>
                    <td><span class="star">*</span> Sent on Date:</td>
                    <td><input type="text" id="datepicker" required="required" name="created_at" value="{{ Input::old('datepicker') }}" placeholder="Click here to choose a date"></td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Client's full name:</td>
                    <td><input type="text" name="client_name" required="required" placeholder="Jane Doe" value="{{ Input::old('client_name') }}"></td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Client's phone:</td>
                    <td>
                        <div class="form-group">
                            <select name="mobile_code" placeholder="+(123) Country">
                                @include('_includes/phone_codes_list')
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="mobile_phone" name="mobile_phone" required="required" placeholder="656-4789" value="{{ Input::old('mobile_phone') }}">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Client's email:</td>
                    <td><input type="email" name="client_email" required="required" placeholder="j.doe@gmail.com" value="{{ Input::old('client_email') }}"></td>
                </tr>
                @if(Session::has('active_sa'))
                <tr>
                    <td><span class="star">*</span> Company:</td>
                    <td>
                        <select name="merchant" required="required">
                            <option>Select company:</option>
                            @foreach($merchants as $merchant)
                            <option value="{{ $merchant->merch_id }}" @if (Input::old('merchant') == $merchant->merch_id) echo ' selected="selected"'; @endif>{{ $merchant->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @endif
                <tr>
                    <td><span class="star">*</span> Send to:</td>
                    <td>
                        <select name="bank" required="required">
                            <option>Choose Bank:</option>
                            @foreach($banks as $bank)
                            @if(Session::has('active_sa'))
                            <option value="{{ $bank->bank_id }}" @if (Input::old('bank') == $bank->bank_id) echo ' selected="selected"'; @endif>{{ $bank->name }}</option>
                            @else
                            <option value="{{ $bank[0]->bank_id }}" @if (Input::old('bank') == $bank[0]->bank_id) echo ' selected="selected"'; @endif>{{ $bank[0]->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Sending Country:</td>
                    <td>
                        @include('_includes.country_list')
                    </td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Amount:</td>
                    <td>
                        <input type="text" min="1" name="amount_sent" required="required" placeholder="500.00" value="{{ Input::old('amount_sent') }}" class="amount-inp">
                        <select name="currency" required="required" class="currency-inp">
                            <option>Choose Currency:</option>
                            @foreach($currencys as $currency)
                            <option value="{{ $currency->name }}" @if (Input::old('currency') == $currency->name) echo ' selected="selected"'; @endif>{{ $currency->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><span class="star">*</span> KYC:</td>
                    <td>
                        <select name="kyc" required="required">
                            <option value="none" @if (Input::old('kyc') == "none" ) echo ' selected="selected"'; @endif>No KYC</option>
                            <option value="uploaded" @if (Input::old('kyc') == "uploaded" ) echo ' selected="selected"'; @endif>Uploaded</option>
                            @if($is_sadmin || $is_main_admin || $is_admin)
                            <option value="in_process" @if (Input::old('kyc') == "in_process" ) echo ' selected="selected"'; @endif>In Process</option>
                            <option value="missing_docs" @if (Input::old('kyc') == "missing_docs" ) echo ' selected="selected"'; @endif>Missing Docs</option>
                            <option value="approved" @if (Input::old('kyc') == "approved" ) echo ' selected="selected"'; @endif>Approved</option>
                            @endif
                        </select>
                    </td>
                </tr>
                @if(Session::has('active_sa'))
                <tr>
                    <td>World Check:</td>
                    <td>
                        <select name="wc">
                            <option value="not_checked" @if (Input::old('wc') == "not_checked" ) echo ' selected="selected"'; @endif>Not checked</option>
                            <option value="no_match" @if (Input::old('wc') == "no_match" ) echo ' selected="selected"'; @endif>No match</option>
                            <option value="existing" @if (Input::old('wc') == "existing" ) echo ' selected="selected"'; @endif>Existing profile</option>
                        </select>
                    </td>
                </tr>
                @endif
                @if(Session::has('active_sa') || Session::has('active_b'))
                <tr>
                    <td>Notes:</td>
                    <td>
                        <textarea name="notes" rows="4" style="width: 100%;">{{ Input::old('notes') }}</textarea>
                    </td>
                </tr>
                @endif
                <tr>
                    <td><input type="hidden" value="not_checked" name="wc"></td>
                </tr>
            </table>
            <input type="button" value="Cancel" onclick="window.location ='{{ url('wires') }}'" class="btn btn-back">
            <input type="submit" class="submit" value="Create" onclick="this.disabled=true;this.value='Saving...'; this.form.submit();">
        </form>
    </div>
</div>
@stop