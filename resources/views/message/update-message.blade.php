@if ($message = Session::get('successUpd'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    <strong>Sukses ! </strong>{{ $message }}
</div>
@endif


@if ($message = Session::get('errorUpd'))
<div class="alert alert-danger alert-block"> 
    <button type="button" class="close" data-dismiss="alert">×</button> 
    <strong>Maaf ! </strong>{{ $message }} 
</div>
@endif


@if ($message = Session::get('warningUpd'))
<div class="alert alert-warning alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button> 
	<strong>Warning ! </strong> {{ $message }}
</div>
@endif


@if ($message = Session::get('infoUpd'))
<div class="alert alert-info alert-block">	
    <button type="button" class="close" data-dismiss="alert">×</button> 
	<strong>Info ! </strong>{{ $message }}
</div>
@endif