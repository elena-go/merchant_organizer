<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{ $title }}</title>
        <script src="{{ asset('js/jquery-1.12.0.min.js') }}" type="text/javascript"></script>
        <!-- Bootstrap src-->@include('_includes.bootstrap')
        <link type="text/css" href="{{ asset('css/reset_pass.css') }}" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Hind+Vadodara|Raleway' rel='stylesheet' type='text/css'>
    </head>

    <body>
        <div class="container-fluid text-center div-wrapper">
            <div class="col-lg-3 col-md-3"></div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 login-form-wrapper">
                <h2 class="form-signin-heading">Instructions on how to reset your password were sent to</h2>
                <h1>{{ $email_to }}</h1>
                <p class="text-center">If you don't receive an email within 3 minutes, please check that it was not sent to your "spam" folder.</p>
            </div>
        </div>
        <script src="{{ asset('lib/bootstrap/js/bootstrap.js') }}"
    </body>
</html>
