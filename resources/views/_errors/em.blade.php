@if($em = Session::get('em'))
<div class="row sm-alert">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
        <div class="em-txt" role='alert'>{{ $em }}</div>
    </div>
</div>
@endif
