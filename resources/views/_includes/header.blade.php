<nav class="navbar navbar-inverse navbar-fixed-top top-header">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand header-logo" href="{{ url('wires') }}">iPayTech</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="header-date-display"><?= date('d, M', time()); ?></li>
                <li class="header-name-display"><i>Welcome,</i> <strong>{{ Session::get('user_name') }} {{ Session::get('user_lastname') }}</strong></li>
                @if(!Session::has('user_id'))
                <li><a href="{{ url('user/signin') }}">Sign in</a></li>
                @else
                <li>
                    <a class="header-logout" href="{{ url('user/logout') }}" style="color:#a04646;"><span class="glyphicon glyphicon-log-out"></span> Log out</a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>