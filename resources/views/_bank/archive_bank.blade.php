@extends('layout')
@section('content')
<link type="text/css" rel="stylesheet" href="{{ asset('css/delete.css') }}">
<div class="row placeholders text-center">
<!-------------------------------------------- Error display -------------------------------------------->
<div class="col-md-12">
    <div class="col-md-4 col-md-offset-4 text-left">
        @if(Session::has('em'))
        @include('_includes.em')
        @endif
    </div>
</div>
<!-------------------------------------------- Deleting Form ------------------------------------------->
    <div class="col-md-12 text-center">
        <h2>Do you want to archive the bank? <br> <strong>{{ $bank[0]->name }}</strong></h2>
        <form method="post" action="{{ url('banks/'.$id) }}">
            <input type="hidden" name="_method" value="DELETE">
            {{ csrf_field() }}
            <input type="button" value="Cancel" onclick="window.location='{{ url('banks') }}'" class="no-btn">
            <input type="submit" value="Archive" class="yes-btn">
        </form>
    </div>
</div>
@stop