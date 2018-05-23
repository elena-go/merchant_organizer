@extends('layout')
@section('content')
<link type="text/css" href="{{ asset('css/contact_us.css') }}" rel="stylesheet">
<div id="user_overview_top_row" class="row top-row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h2>Contact Us</h2>
    </div>
</div>
<h3>Have a question? Here's how you can contact us!</h3>
<div class="row contact-wrapper">
<div class="col-md-4 col-md-offset-1 col-lg-4 col-lg-offset-1 col-sm-6 col-xs-6 box-wrapper">
    <h4>Management Team</h4>
    <table class="contact-tbl">
        <tr>
            <td class="td-bold">Skype:</td>
            <td>wire</td>
        </tr>
        <tr>
            <td class="td-bold">Phone:</td>
            <td>-</td>
        </tr>
    </table>
</div>
<div class="col-md-4 col-md-offset-2 col-lg-4 col-lg-offset-2 col-sm-6 col-xs-6 box-wrapper">
    <h4>For Technical Issues</h4>
    <table class="contact-tbl">
        <tr>
            <td>Skype:</td>
            <td>payobin.support</td>
        </tr>
        <tr>
            <td>Phone:</td>
            <td>-</td>
        </tr>
    </table>
</div>
</div>
@stop