@extends('layout')
@section('content')
<link type="text/css" rel="stylesheet" href="{{ asset('css/delete.css') }}">
<div class="row placeholders text-center">
<!-------------------------------------------- Error display -------------------------------------------->
<div class="co-md-12 col-lg-12 col-sm-12 col-xs-12">    
<div class="col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 text-left">
        @if(Session::has('em'))
        @include('_includes.em')
        @endif
    </div>
</div>
<!-------------------------------------------- Deleting Form ------------------------------------------->
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 full_wire_wrapper text-center">
        <h2>Do you want to archive the user? <br> <strong>{{ $user->name }} {{ $user->lastname }}</strong></h2>
        <form method="post" action="{{ url('users/'.$id) }}">
            <input type="hidden" name="_method" value="DELETE">
            {{ csrf_field() }}
            <input type="button" value="Cancel" onclick="window.location='{{ url('users') }}'" class="no-btn">
            <input type="submit" value="Archive" class="yes-btn">
        </form>
        
    </div>
</div>
@stop