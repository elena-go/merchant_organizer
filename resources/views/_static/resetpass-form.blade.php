<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>iPayTech | Change Password</title>
        <script src="{{ asset('js/jquery-1.12.0.min.js') }}" type="text/javascript"></script>
        <!-- Bootstrap src-->@include('_includes.bootstrap')
        <link type="text/css" href="{{ asset('css/reset_pass.css') }}" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Hind+Vadodara|Raleway' rel='stylesheet' type='text/css'>
    </head>

    <body>
        <div class="container-fluid text-center div-wrapper">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1 class="form-signin-heading">Reset Password</h1>
                <div class="col-lg-3 col-md-3"></div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 login-form-wrapper">
                    @if($errors->any())
                    @include('_errors.laravel_em')
                    @endif
                    @if(Session::has('em'))
                    @include('_errors.em')
                    @endif
                    <form action="{{ url('update-password') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{ $user_id[0]->user_id }}">
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" required="required" class="form-control password" name="password" placeholder="New Password">
                        </div>
                        <div class="form-group">
                            <label for="conf-password" class="sr-only">Confirm Password</label>
                            <input type="password" required="required" class="form-control conf-password" name="conf-password" placeholder="Confirm New Password">
                        </div>
                        <button type="submit" class="btn submit" name="submit">Reset Pass</button>
                        <p>Upon submission, you will be able to log in using your email and new password!</p>
                    </form>
                </div>
            </div>
        </div>
        <script src="{{ asset('lib/bootstrap/js/bootstrap.js') }}"
    </body>
</html>
