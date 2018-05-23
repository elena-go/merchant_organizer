<div class="col-sm-3 col-md-2 navbar-collapse collapse sidebar left-nav-wrapper"  id="navigation">
    <ul class="nav nav-sidebar" >
        <li><a href="{{ url('wires') }}"><span class="glyphicon glyphicon-list"></span><span style="font-weight: 700;" onclick="new Filters('wires_filters', {{ Session::get('user_id') }}).clear()"> Wires</span></a></li>
        @if(Session::has('active_m'))
        <li><a href="{{ url('users') }}"><span class="glyphicon glyphicon-user"></span> User Profile</a></li>
        <li><a href="{{ url('companies') }}"><span class="glyphicon glyphicon-briefcase"></span> Company Info</a></li>
        <li><a href="{{ url('banks') }}"><span class="glyphicon glyphicon-credit-card"></span> Banks</a></li>
        @endif
        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
        <li><a href="{{ url('banks') }}"><span class="glyphicon glyphicon-credit-card"></span> Banks</a></li>
        <li><a href="{{ url('users') }}"><span class="glyphicon glyphicon-user"></span> Users</a></li>
        <li><a href="{{ url('companies') }}"><span class="glyphicon glyphicon-briefcase"></span> Companies</a></li>
        @endif
    </ul>
    @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w') || Session::has('active_m'))
    <ul class="nav nav-sidebar">
        @if(Session::has('active_sa') || Session::has('active_m'))
        <li><a href="{{ url('wires/create') }}"><span class="glyphicon glyphicon-plus"></span> New Wire</a></li>
        @endif
        @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
        <li><a href="{{ url('users/create') }}"><span class="glyphicon glyphicon-plus"></span> New User</a></li>
        <li><a href="{{ url('companies/create') }}"><span class="glyphicon glyphicon-plus"></span> New Company</a></li>
        <li><a href="{{ url('banks/create') }}"><span class="glyphicon glyphicon-plus"></span> New Bank</a></li>
        @endif
    </ul>
    @endif
    @if(Session::has('active_sa') || Session::has('active_mw') || Session::has('active_w'))
    <ul class="nav nav-sidebar">
        <li><a href="{{ url('billing') }}"><span class="glyphicon glyphicon-piggy-bank"></span> <span onclick="new Filters('billing_filters', {{ Session::get('user_id') }}).clear()"> Billing</span></a></li>
        <li><a href="{{ url('last-updates') }}"><span class="glyphicon glyphicon-refresh"></span> Updates</a></li>
        <li><a href="{{ url('statistics') }}"><span class="glyphicon glyphicon-stats"></span> <span onclick="new Filters('statistics_filters', {{ Session::get('user_id') }}).clear()"> Statistics</span></a></li>
    </ul>
    @endif
    <ul class="nav nav-sidebar">
        <li><a href="{{ url('additional-info') }}"><span class="glyphicon glyphicon-info-sign"></span> Additional Info</a></li>
        <li><a href="{{ url('contact-us') }}"><span class="glyphicon glyphicon-envelope"></span> Contact Us</a></li>
    </ul>
</div>