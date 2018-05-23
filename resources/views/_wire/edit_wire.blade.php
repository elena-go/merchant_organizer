@extends('layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
@if(!empty($wire))
<script>
    $(function () {
        $("#sent_on").datepicker({dateFormat: 'yy-mm-dd'});
        $("#received_on").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: '{{ $wire->sent_on }}'
        });
    });
</script>				
<br/>
<h2>Edit Wire: 10{{ $wire->wire_id }}</h2>
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
        <form class="form-inline" method="post" action="{{ url('wires/'.$wire->id) }}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT" />
            <table class="table table-responsive">
                @if(Session::has('active_sa'))
                <tr>
                    <td>Status:</td>
                    <td>
                        <input type="radio" name="active" value="1" @if($wire->active == 1) checked @endif> Active
                        <input type="radio" name="active" value="0" @if($wire->active == 0) checked @endif> Inactive
                    </td>
                </tr>
                @else
                <input type="hidden" name="active" value="1">
                @endif
                <tr>
                    <td><span class="star">*</span> Sent on Date:</td>
                    @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                    <td><input type="text" id="sent_on" required="required" name="created_on" value="{{ date('Y-m-d', strtotime($wire->sent_on)) }}"></td>
                    @else
                    <td><p>{{ date('Y-m-d', strtotime($wire->sent_on)) }}</p></td>
                    @endif
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>
                        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                        <select name="status">
                            <option value="2" @if($wire->status == '2') echo ' selected="selected"'; @endif >Returned</option>
                            @if($wire->status != '6')
                            @if($wire->status == '1') <option value="1" selected="selected">Pending</option> @endif
                            @if($wire->status == '3') <option value="3" selected="selected">Compliance</option> @endif 
                            @if($wire->status == '4')<option value="4"  selected="selected">Awaiting Funds</option>@endif 
                            @if($wire->status == '5')
                            <option value="5"  selected="selected">Approved</option>
                            <option value="6" @if($wire->status == '6') echo ' selected="selected"'; @endif >Paid</option>
                            @endif
                            @else
                            <option value="6" @if($wire->status == '6') echo ' selected="selected"'; @endif >Paid</option>
                            @endif
                        </select>
                        @else
                        @if($wire->status == '1')
                        <p>Pending</p>
                        @elseif($wire->status == '2')
                        <p>Returned</p>
                        @elseif($wire->status == '3')
                        <p>Compliance</p>
                        @elseif($wire->status == '4')
                        <p>Awaiting Funds</p>
                        @elseif($wire->status == '5')
                        <p>Approved</p>
                        @elseif($wire->status == '6')
                        <p>Paid</p>
                        @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Client's full name:</td>
                    <td>
                        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || (Session::has('active_m') && $wire->status == '1'))
                        <input type="text" name="client_name" required="required" placeholder="Jane Doe" value="{{ $wire->client_name }}">
                        @else
                        <input type="hidden" value="{{ $wire->client_name }}" name="client_name">
                        <p>{{ $wire->client_name }}</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Client's phone:</td>
                    <td>
                        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                        <div class="form-group">
                            <select name="mobile_code" placeholder="+(123) Country">
                                <option value="{{ $wire->m_id }}">{{ $wire->m_code }} {{ $wire->m_country }}</option>
                                @include('_includes/phone_codes_list')
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="mobile_phone" placeholder="XXX-XXXX" @if(!empty($wire->client_phone)) value="{{ $wire->client_phone }}" @else echo ' value="" ' @endif>
                        </div>
                        @else
                        <input type="hidden" name="mobile_code" value="{{ $wire->m_id }}">
                        <input type="hidden" name="mobile_phone" value="{{ $wire->client_phone }}">
                        <p>{{ $wire->m_code }}{{ $wire->client_phone }} ({{ $wire->m_country }})</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Client's email:</td>
                    @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                    <td><input type="text" name="client_email" required="required" placeholder="j.doe@gmail.com" value="{{ $wire->client_email }}"></td>
                    @else
                    <td><p>{{ $wire->client_email }}</p></td>
                    @endif
                </tr>
                <tr>
                    <td><span class="star">*</span> Company:</td>
                    <td>
                        @if(Session::has('active_sa'))
                        <select name="merchant" required="required">
                            @foreach($merchants as $merchant)
                            <option value="{{ $merchant->merch_id }}" @if($wire->merchant_id == $merchant->merch_id) echo ' selected="selected"'; @endif >{{ $merchant->name }}</option>
                            @endforeach
                        </select>
                        @else
                        <input type="hidden" name="merchant" value="{{ $wire->merchant_id }}">
                        <p>{{ $wire->merchant_name }}</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Send to:</td>
                    <td>
                        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                        <select name="bank" required="required">
                            @foreach($banks as $bank)
                            <option value="{{ $bank->id }}" @if($wire->sent_to_bank == $bank->id) echo ' selected="selected"'; @endif >{{ $bank->name }}</option>
                            @endforeach
                        </select>
                        @else
                        <p>{{ $wire->bank_name }}</p>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Sending Country:</td>
                    <td>
                        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                        @include('_includes.wires_country_list')
                        @else
                        <p>{{ $wire->country_name }}</p>
                        @endif
                    </td>
                </tr>
                @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                <tr>
                    <td>Fee:</td>
                    <td><input type="number"  step="0.1"  min="0" max="9.9"  placeholder="3" name="percent" class="percent" value="{{ $wire->percent }}"></td>
                </tr>
                @endif
                <tr>
                    <td><span class="star">*</span> Sent amount:</td>
                    <td>
                        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                        <input type="text" min="1" max="10" name="amount_sent" required="required" value="{{ $wire->amount_sent }}" class="amount-inp">
                        <select name="currency" required="required" class="currency-inp">
                            @foreach($currencys as $currency)
                            <option value="{{ $currency->name }}" @if($wire->currency == $currency->name) echo ' selected="selected"'; @endif >{{ $currency->name }}</option>
                            @endforeach
                        </select>
                        @else
                        <p>{{ $wire->amount_sent }} {{ $wire->currency }}</p>
                        @endif
                    </td>
                </tr>
                @if(Session::has('active_sa') || Session::has('active_b'))
                <tr>
                    <td>Amount Received:</td>
                    <td>
                        <input type="text" name="amount_received" class="amount-inp" @if($wire->amount_received != NULL || $wire->amount_received != 0) echo ' value="{{ $wire->amount_received }}" '; @else echo ' value="{{ Input::old('amount_received') }}" ' @endif>
                        <select name="currency_received" class="currency-inp">
                            <option value="">Choose Currency:</option>
                            <option value="EUR" @if($wire->currency_received == 'EUR') echo ' selected="selected"'; @endif>EUR</option>
                            <option value="USD" @if($wire->currency_received == 'USD') echo ' selected="selected"'; @endif>USD</option>
                            <option value="GBP" @if($wire->currency_received == 'GBP') echo ' selected="selected"'; @endif>GBP</option>
                            <option value="AED" @if($wire->currency_received == 'AED') echo ' selected="selected"'; @endif>AED</option>
                            <option value="AUD" @if($wire->currency_received == 'AUD') echo ' selected="selected"'; @endif>AUD</option>
                            <option value="CAD" @if($wire->currency_received == 'CAD') echo ' selected="selected"'; @endif>CAD</option>
                            <option value="SAR" @if($wire->currency_received == 'SAR') echo ' selected="selected"'; @endif>SAR</option>
                            <option value="CHF" @if($wire->currency_received == 'CHF') echo ' selected="selected"'; @endif>CHF</option>
                            <option value="JPY" @if($wire->currency_received == 'JPY') echo ' selected="selected"'; @endif>JPY</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Received on:</td>
                    <td><input type="text" id="received_on" name="received_on" value="{{ $wire->received_on }}"></td>
                </tr>
                @else
                <tr>
                    <td>Amount Received:</td>
                    <td>
                        @if(!empty($wire->amount_received))
                        <input type="hidden" name="amount_received" value="{{ $wire->amount_received }}">
                        <input type="hidden" name="currency_received" value="{{ $wire->currency_received }}">
                        @if(!empty($wire->received_on))<input type="hidden" name="received_on"  value="{{ $wire->received_on }}" > @endif
                        <p>{{ $wire->amount_received }} {{ $wire->currency_received }}</p>
                        <p>Received on: {{ date('d/m/Y',strtotime($wire->received_on)) }}</p>
                        @else
                        <p>No received funds</p>
                        @endif
                    </td>
                </tr>
                @endif
                <tr>
                    <td><span class="star">*</span> KYC uploaded to the cloud:</td>
                    <td>
                        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || (Session::has('active_m') && ($wire->kyc == 'none' || $wire->kyc == 'missing_docs')))
                        <select name="kyc" required="required">
                            @if(Session::has('active_m') && $wire->kyc == 'none')<option value="none" @if($wire->kyc == 'none') echo ' selected="selected"'; @endif >No KYC</option> @endif
                            
                            @if(Session::has('active_m') && $wire->kyc == 'missing_docs')<option value="missing_docs" @if($wire->kyc == 'missing_docs') echo ' selected="selected"'; @endif >Missing Documents</option> @endif
                            <option value="uploaded" @if($wire->kyc == 'uploaded') echo ' selected="selected"'; @endif >Uploaded</option>
                            
                            @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                            <option value="none" @if($wire->kyc == 'none') echo ' selected="selected"'; @endif >No KYC</option>
                            <option value="in_process" @if ($wire->kyc == 'in_process') echo ' selected="selected"'; @endif>In Process</option>
                            <option value="missing_docs" @if ($wire->kyc == 'missing_docs') echo ' selected="selected"'; @endif>Missing Documents</option>
                            <option value="approved" @if ($wire->kyc == 'approved') echo ' selected="selected"'; @endif>Approved</option>
                            @endif
                        </select>
                        @else
                        <input type="hidden" name="kyc" value="{{ $wire->kyc }}">
                        <p>{{ $wire->kyc }}</p>
                        @endif
                    </td>
                </tr>
                @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b'))
                <tr>
                    <td>World Check:</td>
                    <td>
                        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                        <input type="radio" name="wc" value="not_checked" @if($wire->wc == "not_checked") checked @endif > Not checked
                        <input type="radio" class="wc-radio-btn" name="wc" value="no_match" @if($wire->wc == "no_match") checked @endif > No match
                        <input type="radio" class="wc-radio-btn" name="wc" value="existing" @if($wire->wc == "existing") checked @endif > Existing
                        @else
                        <p>{{ $wire->wc }}</p>
                        <input type="hidden" name="wc" value="{{ $wire->wc }}">
                        @endif
                    </td>
                </tr>
                @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_b'))
                <tr>
                    <td>Notes:</td>
                    <td>
                        <textarea name="notes" rows="3" style="width: 100%;">@if(!empty($wire->notes)) {{ $wire->notes }} @else  {{ Input::old('notes') }}  @endif</textarea>
                    </td>
                </tr>
                @endif
                @endif
            </table>
            <input type="button" value="Cancel" onclick="window.location ='{{ url('wires') }}'" class="btn btn-back">
            <input type="submit" class="submit" value="Update" onclick="this.disabled=true;this.value='Saving...'; this.form.submit();">
        </form>
    </div>
</div>
@else
<div class="col-md-12">
    <div class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-12 col-xs-12"><img src="{{ asset('images/no_data.png') }}" alt="Nothing to display..." title="Nothing to display..."/></div>
</div>
@endif
@stop
