@extends('layout')
@section('content')
<link type="text/css" href="{{ asset('css/additional_info.css') }}" rel="stylesheet">
<div id="user_overview_top_row" class="row top-row">
    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
        <h2>Additional Information</h2>
    </div>
</div>
<div class="row info-wrapper">
    <div class="col-md-3 col-lg-3 col-sm-6 col-xs-12 definitions-wrapper">
        <h3>Definitions:</h3>
        <p><strong>IBAN Code</strong> - International Bank Account Number.</p>
        <p><strong>SWIFT/BIC Code</strong> - Contains bank code, country code, location code and branch code. Used for identification of the bank. Also used between banks in order to exchange messages.</p>
        <p><strong>KYC</strong> - Know Your Client</p>
    </div>
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 faq-wrapper">
        <h3>FAQ:</h3>
            <table class="table table-responsive">
                <th>Question:</th>
                <th>Answer:</th>
                <tr>
                    <td class="q-td"><span class="glyphicon glyphicon-question-sign"></span> How long does it take for a transfer to be reach my account?</td>
                    <td>Transfer time may vary between countries. Usually it takes up to 3 business days for a transfer to reach your account.</td>
                </tr>
                <td class="q-td"><span class="glyphicon glyphicon-question-sign"></span> It's been 3 business days since the client made the transfer but it is yet to reflect in my account. What could be the issue?</td>
                <td>Transfer time may vary, depending on different factors; remitter's country, foreign currencies and wrong beneficiary details are among them. We recommend instructing your client carefully in order to avoid delays.</td>
            </table>
    </div>
    <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12 useful-links">
        <h3 style="text-align: left;">Useful links:</h3>
        <a href="https://www.bov.com/exchangerates.aspx">Currency Converter</a>
    </div>
</div>
<div class="row">
    
</div>
@stop