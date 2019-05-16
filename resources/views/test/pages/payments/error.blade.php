@extends('test.layouts.app')
@section('content')
<div class="container">
  <br><br><br><br><br>
  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <div class="panel panel-default">
        <div class="panel-body">
          <h1>{{ $error }}</h1>
          <i class="text-center fa fa-circle-times text-danger fa-5x"></i>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
