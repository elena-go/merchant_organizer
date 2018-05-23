@extends('layout')
@section('content')
<h2>Edit User</h2>
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
        <form class="form-inline" method="post" action="{{ url('users/'.$user->id) }}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id" value="{{ $user->id }}">
            <table class="table table-responsive">
                <tr>
                    <td>Status:</td>
                    <td>
                        @if(Session::has('active_sa'))
                        <input type="radio" name="status" value="1" @if($user->login == 1) checked @endif> Active
                        <input type="radio" name="status" value="0" @if($user->login == 0) checked @endif> Inactive
                        @else
                        <input type="hidden" name="status" value="{{ $user->login }}">
                        @if($user->login == 1)
                        <p>Active</p>
                        @else
                        <p>Inactive</p>
                        @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><span class="star">*</span>First name:</td>
                    <td>
                        <input type="text" name="fname" required="required" placeholder="Jane" value="{{ $user->name }}">
                    </td>
                </tr>
                <tr>
                    <td><span class="star">*</span>Last name:</td>
                    <td>
                        <input type="text" name="lname" required="required" placeholder="Doe" value="{{ $user->lastname }}">
                    </td>
                </tr>

                <tr>
                    <td><span class="star">*</span>Role:</td>
                    <td>
                        @if(Session::get('user_id') != $user->id)
                        <select name="role" required="required">
                            @if(Session::has('active_sa'))
                            <option value="1" @if ($user->role == '8') echo ' selected="selected"'; @endif>Tech Team</option>
                            <option value="5" @if($user->role == '6') echo  selected="selected" @endif>Wire Manager</option>
                            <option value="2" @if ($user->role  == '5') echo ' selected="selected"'; @endif>Wire Team</option>
                            <option value="3" @if ($user->role == '3') echo ' selected="selected"'; @endif>Company's User</option>
                            <option value="4" @if ($user->role == '1') echo ' selected="selected"'; @endif>Bank's Account Manager</option>
                            @endif
                            @if(Session::has('active_mw'))
                            @if ($user->role == '1') <option value="4" selected="selected" >Bank's Account Manager</option> @endif
                            <option value="5" @if($user->role == '6') echo  selected="selected" @endif>Wire Manager</option>
                            <option value="2" @if ($user->role  == '5') echo ' selected="selected"'; @endif>Wire Team</option>
                            <option value="3" @if ($user->role == '3') echo ' selected="selected"'; @endif>Company's User</option>
                            @endif
                            @if(Session::has('active_w'))
                            @if ($user->role == '1') <option value="4" selected="selected" >Bank's Account Manager</option> @endif
                            <option value="3" @if ($user->role == '3') echo ' selected="selected"'; @endif>Company's User</option>
                            <option value="2" @if ($user->role  == '5') echo ' selected="selected"'; @endif>Wire Team</option>
                            @endif
                        </select>
                        @else
                        @if($user->role == '8')
                        <input name="role" type="hidden" value="1">
                        <p> Tech Team</p>
                        @elseif($user->role =='1')
                        <input name="role" type="hidden" value="4">
                        <p>Bank's Account Manager</p>
                        @elseif($user->role =='6')
                        <input name="role" type="hidden" value="5">
                        <p>Wire Manager</p>
                        @elseif($user->role =='5')
                        <input name="role" type="hidden" value="2">
                        <p>Wire Team</p>
                        @elseif($user->role =='3')
                        <input name="role" type="hidden" value="3">
                        <p>Merchant</p>
                        @else
                        ?
                        @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><span class="star">*</span>Email:</td>
                    <td>
                        <input type="text" name="email" required="required" placeholder="j.doe@email.com" value="{{ $user->email }}">
                    </td>
                </tr>
                @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                <tr>
                    <td>Company:</td>
                    <td>
                        <select name="merchants">
                            <option value="">Choose Company:</option>
                            @foreach($merchants as $row)
                            <option value="{{ $row->merch_id }}" @if ($user->merchant_id == $row->merch_id) echo ' selected="selected"'; @endif>{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @else
                <input name="merchants" type="hidden" value="{{ $user->merchant_id }}">
                @endif
                <tr>
                    <td>Phone:</td>
                    <td>
                        <div class="form-group">
                            <select name="mobile_code" placeholder="+(123) Country">
                                <option value="{{ $user->phone_code }}">{{ $user->m_code }} {{ $user->m_country }}</option>
                                @include('_includes/phone_codes_list')
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="mobile_phone" placeholder="XXX-XXXX" @if(!empty($user->phone)) value="{{ $user->phone }}" @else echo ' value="" ' @endif>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Skype:</td>
                    <td>
                        <input type="text" name="skype" placeholder="skype.123" value="{{ $user->skype }}">
                    </td>
                </tr>
                <tr>
                    <td>URL:</td>
                    <td>
                        <input type="text" name="url" placeholder="website.com" value="{{ $user->url }}">
                    </td>
                </tr>

            </table>
            <input type="button" value="Cancel" onclick="window.location ='{{ url('users') }}'" class="btn btn-back"> <input type="submit" class="submit" value="Update" onclick="this.disabled=true;this.value='Saving...'; this.form.submit();">
        </form>
    </div>
</div>
@stop