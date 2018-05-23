<div class="col-md-12 lem-wrapper">
    @foreach($errors->all() as $error)
    <div class="row em-txt">
        <span class="glyphicon glyphicon-alert"></span> {{ $error }}
    </div>
    @endforeach
</div>
