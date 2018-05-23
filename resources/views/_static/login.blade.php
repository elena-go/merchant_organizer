<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{ $title }}</title>
        <link href="{{ asset('lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Hind+Vadodara|Raleway' rel='stylesheet' type='text/css'>
        <link type="text/css" href="{{ asset('css/ipaytech/login.css') }}" rel="stylesheet">
    </head>

    <body>
        <div class="container-fluid text-center div-wrapper">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if(Session::has('sm'))
                @include('_errors.sm')
                @endif
                <div class="col-md-3  col-lg-3"></div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 login-form-wrapper">
                    <h1 class="form-signin-heading">Welcome to iPayTech</h1>
                    <h4>Please log in to your account</h4>
                    @if($errors->any())
                    @include('_errors.laravel_em')
                    @endif
                    @if(Session::has('em'))
                    @include('_errors.em')
                    @endif
                    <form action="{{ url('user/signin') }}" method="post" class="col-md-12">
                        {{ csrf_field() }}
                        <div class="form-group col-md-6">
                            <label for="email" class="sr-only">Email address</label>
                            <input type="text" class="form-control email" name="email" placeholder="Email" value="{{ Input::old('email') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" class="form-control password" name="password" placeholder="Password">
                        </div>
                        <button type="submit" class="btn submit" name="submit">Log in</button>
                    </form>
                    <div class="col-md-4 reset-pass"><a href="{{ url('reset-password') }}">Forgot Password</a></div>
                    <div class="col-md-8 contact-us">
                        <p>Have a question? Here's how you can contact us!</p> 
                        <p>Write to: <span class="mail">support@payobin.com</span></p>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('lib/bootstrap/js/bootstrap.js') }}"
    </body>
</html>
