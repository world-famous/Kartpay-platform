@extends('live.layouts.app')
@section('content')
<div class="container">
  <br><br><br><br><br>
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
		<img src="{{ asset('images/logo2.png') }}" alt="" style="width: 292px;margin-top: 45px;"class="site_logo img-responsive center-block">
		<br><br>
      <div class="panel panel-default">
        <div class="panel-body" style="text-align:center;">
          <h1>{{ $title }}</h1>
          <h3>{{ $message }}</h3>
          <i class="text-center fa fa-circle-times text-danger fa-5x"></i>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@push('scripts')
<script>
$(function()
{
	window.location.href = "{!! $url !!}";
});
</script>
@endpush
