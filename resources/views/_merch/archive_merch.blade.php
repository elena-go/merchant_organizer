@extends('layout')
@section('content')
<link type="text/css" rel="stylesheet" href="{{ asset('css/delete.css') }}">
<div class="row placeholders text-center">
<!-------------------------------------------- Error display -------------------------------------------->
<div class="col-md12 col-lg-12 col-sm-12 col-xs-12">
    <div class="col-md-4 col-md-offset-4 text-left">
        @if(Session::has('em'))
        @include('_errors.em')
        @endif
    </div>
</div>
<!-------------------------------------------- Deleting Form ------------------------------------------->
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 full_wire_wrapper text-center">
        <h2>Are you sure you want to archive the Company? <br> <strong>{{ $merchant->name }}</h2>
        <form method="post" action="{{ url('companies/'.$id) }}">
            <input type="hidden" name="_method" value="DELETE">
            {{ csrf_field() }}
            <input type="button" value="Cancel" onclick="window.location='{{ url('companies') }}'" class="no-btn">
            <input type="submit" value="Archive" class="yes-btn">
        </form>
        
    </div>
</div>
@stop