@extends('layout')
@section('content')
@if($errors->any()) @include('_errors.laravel_em') @endif
@if(Session::has('em')) @include('_errors.em') @endif
@if(Session::has('sm')) @include('_errors.sm') @endif
<br/>
<!-------------------------------------------- If is Super Admin ---------------------------------------->

@if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
@if(!empty($users))
<div id="user_overview_top_row" class="row top-row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h2>Users</h2>
    </div>
</div>
<table id="users" class="table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Role</th>
            <th>Status</th>
            <th>Full Name</th>
            <th>E-Mail</th>
            <th>Company</th>
            <th>Phone</th>
            <th>Skype</th>
            <th>URL</th>
            <th>Created</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        @if(($user->status == 0 && $user->login == 0 && ($user->role_id != 3 || $user->role_id == 3)) || ($user->status == 1 && $user->login == 0 && $user->role_id == 3 && !empty($user->merchant_id)) || ($user->status == 1 && $user->login == 0 && $user->role_id != 3))
        <tr style="color:#ce3c0c;" class="deletedUser">
            @elseif(($user->status == 1 || $user->status == 0) && $user->login == 0 && $user->role_id == 3)
        <tr style="color:#ce970c;">
            @else
        <tr>
            @endif
            @if($user->role_id == 1)<td>Bank's Account Manager</td>
            @elseif($user->role_id == 3)<td>Company's User</td>
            @elseif($user->role_id == 5)<td>Wire Team</td>
            @elseif($user->role_id == 6)<td>Wire Manager</td>
            @elseif($user->role_id == 8)<td>Tech Team</td>
            @else<td>UNKNOWN</td>
            @endif
            <td>
                @if($user->status == 1 && $user->login == 1 && $user->role_id != 3) Active
                @elseif($user->status == 0 && $user->login == 1 && $user->role_id == 3)Limited Permissions
                @elseif($user->status == 1 && $user->login == 1 && $user->role_id == 3 && !empty($user->merchant_id)) Active
                @elseif($user->status == 1 && $user->login == 0 && $user->role_id == 3 && empty($user->merchant_id)) Missing Company
                @elseif($user->status == 1 && $user->login == 0 && $user->role_id == 3 && !empty($user->merchant_id)) Archived
                @elseif($user->status == 0 && $user->login == 0 && ($user->role_id != 3 || $user->role_id == 3))Archived
                @elseif($user->status == 1 && $user->login == 0 && $user->role_id != 3)Archived
                @else UNKNOWN
                @endif
            </td>
            <td>{{ $user->name }} {{ $user->lastname }}</td>
            <td>{{ $user->email }}</td>
            <td>
                @if(!empty($user->merchant_id))
                @foreach($merchants as $row)
                @if ($user->merchant_id == $row->merch_id)
                @if($row->status == 0)
                <span style='color:red;'>{{ $row->name }}</span>
                @else
                {{ $row->name }}
                @endif
                @endif
                @endforeach
                @endif
            </td>
            <td>@if(!empty($user->m_code)) {{ $user->m_code }}{{ $user->phone }} ({{ $user->m_country }}) @endif</td>
            <td>{{ $user->skype }}</td>
            <td><a target="_blank" href="http://{{ $user->url }}/" >{{ $user->url }}</a></td>
            <td>{{ $user->created_at }}</td>
            <td>
                @if(Session::has('active_sa') || ($user->role_id != 8 && !Session::has('active_sa')))
                <a href="{{ url('users/'.$user->id.'/edit') }}">
                    <img src="{{ asset('images/edit.png') }}" style="width:15px;" alt="Edit" title="Edit">
                </a>
                @endif
                @if(Session::get('user_id') != $user->id && Session::has('active_sa') && $user->login != 0)
                <a href="{{ url('users/'.$user->id) }}">
                    <img src="{{ asset('images/icon.png') }}" style="width:11px;" alt="Archive" title="Archive">
                </a>
                @endif
            </td>
        </tr>
        @endforeach
</table>
@else
<h1>No Users</h1>
<h3><a href="{{ url('users/create') }}">Here</a> you can add first user.</h3>
<div class="foot"></div>
@endif
@else
<div class="row">
    <h2>User Profile</h2>
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <div class="col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 col-sm-10 col-sm-offset-1 col-xs-12" style="background-color: #ffffff;">
            <table class="table table-responsive">
                <tr>
                    <td>ID:</td>
                    <td>{{ $users[0]->user_id }}</td>
                </tr>
                <tr>
                    <td>Role:</td>
                    <td>
                        @if($users[0]->role_id == 3)
                        Company's User
                        @elseif($users[0]->role_id == 1)
                        Bank's User
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>
                        @if($users[0]->status == 1)
                        Active
                        @else
                        Limited Permission
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Full Name:</td>
                    <td>{{ $users[0]->name }} {{ $users[0]->lastname }}</td>
                </tr>
                <tr>
                    <td>Company:</td>
                    <td>{{ $users[0]->merchant }}</td>
                </tr>
                <tr>
                    <td>Contact Information:</td>
                    <td>Phone: {{ $users[0]->m_code }}{{ $users[0]->phone }}<br/>
                        Skype: {{ $users[0]->skype }}<br/>
                        Email: {{ $users[0]->email }}
                    </td>
                </tr>
                <tr>
                    <td>Account created on:</td>
                    <td>{{ $users[0]->created_at }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endif
<script>
    $('#users').DataTable({
    "language": {
    "paginate": {
    "previous": "&lt;",
            "next": "&gt;"
    }
    },
            dom: '<"row"<"col-md-3 search_wrapper"f><"col-md-2 add_btn_wrapper"><"col-md-2 show_hide_user">>t<"foot"<"col-md-2 show_entries"l><"col-md-5 pagination_wrapper"p><"col-md-2 col-md-offset-3 show_count_entries"i>>',
            order: [[1, "asc"]],
            "lengthMenu": [[25, 50, 100, - 1], [25, 50, 100, "All"]]
    });
    @if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
            $("div.add_btn_wrapper").html('<a href="{{ url('users / create') }}"><span class="glyphicon glyphicon-plus add-btn"></span> New User</a>');
    @endif
            @if (Session::has('active_sa'))
            $("div.show_hide_user").html('<input type="checkbox" id="show_hide"> Show archived users');
    $('.show_hide_user').click(function(){
    var x = document.getElementsByClassName("deletedUser");
    if (document.getElementById('show_hide').checked == true){
    for (var i = 0; i < x.length; i++) {
    x[i].style.display = 'table-row';
    }
    } else{
    for (var i = 0; i < x.length; i++) {
    x[i].style.display = 'none';
    }
    }
    });
    @endif
</script>

@stop