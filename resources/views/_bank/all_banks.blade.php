@extends('layout')
<!------------------------------------------------------------------------------- Content for displaying banks -->
@section('content')
<!-------------------------------------------------------------------------------Error display -->
@if($errors->any()) @include('_errors.laravel_em') @endif
@if(Session::has('em')) @include('_errors.em') @endif
@if(Session::has('sm')) @include('_errors.sm') @endif
<br/>
<!-- End error display -->
@if(!empty($banks))
<div id="user_overview_top_row" class="row top-row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h2>Banks</h2>
    </div>
</div>
@if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
<table id="bank" class="table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Bank (click on name of the bank to open a file)</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($banks as $bank)
        <!-- If bank has been archived (status = 0) - it gets different style -->
        @if($bank->status == 0)
        <tr style="background-color: #a04646;" class="deletedBank">
            @else
        <tr>
            @endif
            <td style="text-align: center;"><a href="{{ asset('bank/'.$bank->file) }}" target="_blank"><div class='row'>{{ $bank->name }}</div></a></td>
            <td style="text-align: center;">
                <!-------------------------------------------------------------- Edit | Delete-->
                @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
                <a href="{{ url('banks/'. $bank->id.'/edit') }}" class="edit_icon"><img src="{{ asset('images/edit.png') }}" style="width:15px;" alt="Edit" title="Edit"></a>
                @endif
                @if(Session::has('active_sa') && $bank->status != 0)
                <a href="{{ url('banks/'. $bank->id) }}" class="archive_icon"><img src="{{ asset('images/icon.png') }}" style="width:11px;" alt="Archive" title="Archive"></a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@elseif(Session::has('active_m'))
<table id="bank" class="table-hover" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Bank (click on name of the bank to open a file)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($banks as $bank)
        <tr>
            <td style="text-align: center;"><a href="{{ asset('bank/'.$bank[0]->file) }}" target="_blank"><div class='row'>{{ $bank[0]->name }}</div></a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@else
    <h1>No Banks</h1>
    @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
    <h3><a href="{{ url('banks/create') }}">Here</a> you can add first bank.</h3>
    @endif
@endif
<script>
    $('#bank').DataTable({
        dom: '<"row"<"col-md-3 search_wrapper"f><"col-md-2 add_btn_wrapper"><"col-md-2 show_hide_bank">>t<"foot"<"col-md-2 show_entries"l><"col-md-5 pagination_wrapper"p><"col-md-2 col-md-offset-3 show_count_entries"i>>',
            order: [[1, "asc"]],
    });
@if (Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
            $("div.add_btn_wrapper").html('<a href="{{ url('banks/create') }}"><span class="glyphicon glyphicon-plus add-btn"></span> New Bank</a>');
            @endif
            @if(Session::has('active_sa'))
                $("div.show_hide_bank").html('<input type="checkbox" id="show_hide"> Show archived banks');
            $('.show_hide_bank').click(function(){
                var x = document.getElementsByClassName("deletedBank");
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
@stop