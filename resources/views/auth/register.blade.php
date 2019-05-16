@extends('layouts.app_verification')

@section('content')
<div class="register-box">
    <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">
        <div class="col-lg-10 col-lg-offset-1 col-md-12">
            <div class="site--logo">
                <img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive" style="margin-top: 45px;height: 45px;width: 294px;" alt="">
            </div>
				@if(isset($user_type))
					@if($user_type == 'admin')
						<form id="form_register" role="form" method="POST" action="{{ route('admins.register') }}">
					@else
						<form id="form_register" role="form" method="POST" action="{{ route('merchants.register') }}">
					@endif
						<input type="hidden" name="verification_code" value="{{ $verification_code }}" />
						<input type="hidden" name="type" value="{{ $type }}" />
				@endif
              {{ csrf_field() }}
               <div style="box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);background: white;padding: 4%;border-radius: 24px;">
                <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
                    <input type="text" required class="form-control form-control-kartpay input-lg"
                        placeholder="First Name" value="{{ old('first_name') }}" onkeydown="return alphaOnly(event);"
                        name="first_name" style="box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);margin-top: 10%;height: 35px;font-size: 100%; border-radius: 10px;">
                    @if($errors->has('first_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('first_name') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
                    <input type="text" class="form-control form-control-kartpay input-lg"
                        placeholder="Last Name" onkeydown="return alphaOnly(event);"
                        name="last_name" value="{{ old('last_name') }}" required style="box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);height: 35px;font-size: 100%;border-radius: 10px;">
                    @if($errors->has('last_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('last_name') }}</strong>
                        </span>
                    @endif
                </div>
				@if(!isset($type) || (isset($type) && $type != 'staff') )
                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input type="email" class="form-control form-control-kartpay input-lg"
                        placeholder="Email" name="email" value="{{ old('email') }}" required style="box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);height: 35px;font-size: 100%;border-radius: 10px;">
                    @if($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
				@endif
                <div class="form-group {{ $errors->has('contact_no') || $errors->has('country_code') ? 'has-error' : '' }}">
                    <div class="row">
                        <div class="col-xs-3">
                            <input type="text" class="form-control form-control-kartpay input-lg"
                                maxlength="3" value="+91" disabled name="country_code" style="font-size: 100%;padding: 16%;box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);height: 100%;">
                            <input type="hidden" name="country_code" value="+91">
                        </div>
                        <div class="col-xs-9">
                            <input id="contact_no" type="text" name="contact_no" pattern=".{10,10}" title="must be 10 digits" maxlength="10"
                                onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57"
                                placeholder="Enter contact no."  value="{{ old('contact_no') }}"
                                required="required" class="form-control form-control-kartpay input-lg" style="height: 35px;font-size: 100%;width: 103%;margin-left: -3%;box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);border-radius: 10px;">
                        </div>
                    </div>
                    @if($errors->has('contact_no'))
                        <span class="help-block">
                            <strong>{{ $errors->first('contact_no') }}</strong>
                        </span>
                    @endif
                    @if($errors->has('country_code'))
                        <span class="help-block">
                            <strong>{{ $errors->first('country_code') }}</strong>
                        </span>
                    @endif
                </div>
                <br><br>
                <button type="submit" class="btn btn-block btn-default btn-lg btn-kartpay" style="background: rgb(255, 72, 33);width: 40%;border-radius: 20px;margin-left: 27%;height: 35px;font-size: 100%;margin-top: -13%;">Sign Up</button>
               </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<style>
    .site--logo img
    {
        width: 250px;
        margin-bottom: 50px;
    }
</style>
@endpush
@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script>
function alphaOnly(event)
{
  var key = event.keyCode;
  return ((key >= 65 && key <= 90) || key == 8 || key == 9 || key == 116);
}

function disabledFirstChar(e)
{
    if (e.keyCode == 8 && $('#country_code').is(":focus") && $('#country_code').val().length < 2)
    {
      e.preventDefault();
    }
}

function submitForm()
{
    $('#form_register').submit();
}
</script>
@endpush
