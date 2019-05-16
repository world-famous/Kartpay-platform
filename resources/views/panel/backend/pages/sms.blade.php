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
    	<form class="form-horizontal form-label-left" method="post" action="sms-settings">
		    <div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	Sms Sender <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <input type="text" name="sender" class="form-control col-md-7 col-xs-12" value="{{ $sender }}" style="border: 1px solid #fd4907;border-radius: 10px;">
		        </div>
		    </div>
		    <div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	Username <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <input type="text" name="username" class="form-control col-md-7 col-xs-12" value="{{ $username }}" style="border: 1px solid #fd4907;border-radius: 10px;">
		        </div>
		    </div>
		    <div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	Password <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <input type="text" name="password" class="form-control col-md-7 col-xs-12" value="{{ $password }}" style="border: 1px solid #fd4907;border-radius: 10px;">
		        </div>
		    </div>
			<div class="form-group">
		        <label class="control-label col-md-3 col-sm-3 col-xs-12">
		        	OTP Settings <span class="required">*</span>
		        </label>
		        <div class="col-md-3 col-sm-3 col-xs-12">
		            <div class="radio">
      					  <label><input type="radio" name="otp_setting" value="1" <?php if($otp_setting == '1') echo 'checked'; ?>>Enable</label>
      					</div>
      					<div class="radio">
      					  <label><input type="radio" name="otp_setting" value="0" <?php if($otp_setting == '0') echo 'checked'; ?>>Disable</label>
      					</div>
		        </div>
		    </div>
		    {!! csrf_field() !!}
		    <div class="ln_solid"></div>
		    <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a href="" class="btn btn-primary" type="button" style="border-radius: 20px;">Cancel</a>
                    <button type="submit" class="btn btn-success" style="border-radius: 20px;">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
