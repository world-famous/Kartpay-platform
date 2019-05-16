@extends('panel.backend.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
    	@if (count($errors) > 0)
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                @foreach($errors->all() as $error)
                    <span>{{$error}}</span><br/>
                @endforeach
            </div>
        @endif

        @if(session('success'))
            <div class="row">
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
                        {{session('success')}}
                </div>
            </div>
        @endif


    	<form class="form-horizontal form-label-left" method="post" action="{{ route('admins.update.staging') }}">
            <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                    Staging Settings
                </label>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="radio">
                    <label><input type="radio" name="app_env" value="production" <?php if($app_env == 'production' || $app_env != 'staging') echo 'checked'; ?>>Production</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="app_env" value="staging" <?php if($app_env == 'staging') echo 'checked'; ?>>Staging</label>
                  </div>
                </div>
            </div>

		      {!! csrf_field() !!}
		      <div class="ln_solid"></div>
		        <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success" style="border-radius: 15px;">Submit</button>
                    <a href="/" class="btn btn-primary" type="button" style="border-radius: 15px;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
