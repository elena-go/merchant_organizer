@extends('layout')
@section('content')
<br/>
<div class="row placeholders text-center">
    <h2>Add new Bank</h2>
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
        @if($errors->any())
        @include('_errors.laravel_em')
        @endif
        @if(Session::has('em'))
        @include('_errors.em')
        @endif
    </div>
    <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12 text-left form_wrapper">
        <form method="post" action="{{ url('banks') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <table class="table table-responsive form-tbl">
                <tr>
                    <td><span class="star">*</span> Bank name:</td>
                    <td><input type="text" name="name" required="required" placeholder="Bank Name" value="{{ Input::old('name') }}"></td>
                </tr>
                <tr>
                    <td><span class="star">*</span> <strong>PDF</strong> file:</td>
                    <td><input type="file" name="bank_file" accept="application/pdf"></td>
                </tr>
            </table>
            <input type="button" value="Cancel" onclick="window.location ='{{ url('banks') }}'" class="btn btn-back">
            <input type="submit" class="submit" value="Create" onclick="this.disabled=true;this.value='Saving...'; this.form.submit();">
        </form>
    </div>
    <div class="3"></div>
</div>
@stop