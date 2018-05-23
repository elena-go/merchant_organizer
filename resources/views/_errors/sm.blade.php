@if($sm = Session::get('sm'))
<div class="row sm-alert">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="sm" role='alert'>{{ $sm }}</div>
    </div>
</div>
@endif