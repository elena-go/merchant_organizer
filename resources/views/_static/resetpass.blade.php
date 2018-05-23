<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Softtransfer | Reset</title>
        <script src="{{ asset('js/jquery-1.12.0.min.js') }}" type="text/javascript"></script>
        <!-- Bootstrap src-->@include('_includes.bootstrap')
        <link type="text/css" href="{{ asset('css/reset_pass.css') }}" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Hind+Vadodara|Raleway' rel='stylesheet' type='text/css'>
    </head>

    <body>
        <div class="container-fluid text-center div-wrapper">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-3 col-md-3"></div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 login-form-wrapper">
                <h1 class="form-signin-heading">Reset Password</h1>
                    @if($errors->any())
                    @include('_errors.laravel_em')
                    @endif
                    @if(Session::has('em'))
                    @include('_errors.em')
                    @endif
                    <form action="{{ url('reset-password') }}" method="post" class="col-md-12">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="email" class="sr-only">Email address</label>
                            <input type="email" class="form-control email" name="email" placeholder="Email">
                        </div>
                        <button type="submit" class="btn submit" name="submit">Reset Pass</button>
                        <p><em>After submitting your email address, you will receive instructions explaining how to reset your password!</em></p>
                    </form>
                </div>
            </div>
        </div>
        <script src="{{ asset('lib/bootstrap/js/bootstrap.js') }}"
    </body>
</html>
