@extends('layout')
@section('content')
<h2 class="edit_bank_h2">Edit Bank</h2>
<div class="row placeholders text-center">
    <div class="col-md-3 col-lg-3 col-sm-4 col-xs-12">
        @if($errors->any())
        @include('_errors.laravel_em')
        @endif
        @if(Session::has('em'))
        @include('_errors.em')
        @endif
    </div>
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-11 text-left form_wrapper">
        <form id="edit_bank_form_wrapper" enctype="multipart/form-data" method="post" action="{{ url('banks/'.$bank[0]->id) }}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id" value="BankId">
            <table class="table table-responsive form-tbl">
                <tr>
                    <td><span class="star">*</span> Bank name:</td>
                    <td><input type="text" name="name" value="{{ $bank[0]->name }}"></td>
                </tr>
                <tr>
                    <td>Previous file:</td>
                    <td>{{ link_to_asset('bank/'.$bank[0]->file, 'Open current file') }}</td>
                </tr>
                <tr>
                    <td>Upload new PDF file:</td>
                    <td><input type="file" name="file" accept="application/pdf"></td>
                </tr>
            </table>
            <input type="button" value="Cancel" onclick="window.location ='{{ url('banks') }}'" class="btn btn-back"> <input type="submit" class="submit" value="Update" onclick="this.disabled=true;this.value='Saving...'; this.form.submit();">
        </form>
    </div>
    <div class="3"></div>
</div>
@stop