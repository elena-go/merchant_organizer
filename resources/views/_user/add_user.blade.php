@extends('layout')
@section('content')
<script>
    function hideCompanyField() {
        document.getElementById("merch_list").style.display = "none";
    }
    ;
    function showMerchField() {
        var val = document.getElementById('role').value;
        if (val == 3) {
            document.getElementById('merch_list').style.display = "inline";
        }
    }
    ;
</script>
<br/>
<h2>Add New User</h2>
<div class="row placeholders text-center">
    <div class='col-md-12 col-lg-12 col-sm-12 col-xs-12'>
        <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 col-sm-10 col-sm-offset-1 col-xs-12">
            @if($errors->any())
            @include('_errors.laravel_em')
            @endif
            @if(Session::has('em'))
            @include('_errors.em')
            @endif
        </div>
    </div>
    <div class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-10 col-sm-offset-1 col-xs-12 text-left form_wrapper">
        <form class="new-user form-inline" method="post" action="{{ url('users') }}">
            {{ csrf_field() }}
            <table class="table table-responsive">
                <tr>
                    <td><span class="star">*</span> First name:</td>
                    <td><input type="text" name="fname" required="required" placeholder="Jane" value="{{ Input::old('fname') }}"></td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Last name:</td>
                    <td><input type="text" name="lname" required="required" placeholder="Doe" value="{{ Input::old('lname') }}"></td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Role:</td>
                    <td>
                        <select id="role" name="role" required="required" onclick="showMerchField()">
                            <option value="0" @if(Input::old('role') == 0) echo  ' selected="selected"; ' @endif>Choose role:</option>
                            <option value="3" @if(Input::old('role') == 3) echo  ' selected="selected"; ' @endif>Company's User</option>
                            <option value="2" @if(Input::old('role') == 2) echo  ' selected="selected"; ' @endif>Wire Team</option>
                            @if(Session::has('active_sa'))
                            <option value="1" @if (Input::old('role') == 1) echo  selected="selected"; @endif>Tech Team</option>
                            @endif
                            @if(Session::has('active_sa') || Session::has('active_mw'))
                            <option value="5" @if(Input::old('role') == 5) echo  selected="selected" @endif>Wire Manager</option>
                            @endif
                            <option value="4" @if (Input::old('role') == 4) echo  selected="selected"; @endif>Bank's Account Manager</option>
                        </select>
                        <select name="merchant" id="merch_list">
                            <option value='-'>Choose company:</option>
                            @foreach($merchants as $merchant)
                            <option value="{{ $merchant->merch_id }}" @if(Input::old('merchant') == $merchant->merch_id) echo  selected="selected" @endif>{{ $merchant->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <!-- Unique email -->
                    <td><span class="star">*</span> Email:</td>
                    <td><input type="text" name="email" required="required" placeholder="j.doe@email.com" value="{{ Input::old('email') }}"></td>
                </tr>
                <tr>
                    <td><span class="star">*</span> Password:</td>
                    <td><input type="text" min="8" max="255" name="password" required="required"></td>
                </tr>
                <tr>
                <tr>
                    <td>Mobile Phone:</td>
                    <td>
                        <div class="form-group">
                            <select name="mobile_code" placeholder="+(XXX) XXXXXXX" value="{{ Input::old('mobile_code') }}">
                                <option value="">Choose code:</option>
                                @include('_includes/phone_codes_list')
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="mobile_phone" placeholder="XXX-XXXX" value="{{ Input::old('mobile_phone') }}">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="full_wire_tbl_td">Skype:</td>
                    <td>
                        <input type="text" name="skype" placeholder="skype.123" value="{{ Input::old('skype') }}">
                    </td>
                </tr>
                <tr>
                    <td class="full_wire_tbl_td">URL:</td>
                    <td>
                        <input type="text" name="url" placeholder="website.com" value="{{ Input::old('url') }}">
                    </td>
                </tr>
            </table>
            <input type="button" value="Cancel" onclick="window.location ='{{ url('users') }}'" class="btn btn-back">
            <input type="submit" class="submit" value="Create" onclick="this.disabled=true;this.value='Saving...'; this.form.submit();">
        </form>
    </div>
</div>

@stop
