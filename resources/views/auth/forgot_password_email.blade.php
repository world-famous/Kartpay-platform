@extends('layouts.app_verification')

@section('content')
<div class="content">
  <div class="col-md-4 col-md-offset-4">
    <div class="col-md-10 col-md-offset-1">
      <div class="site--logo">
        <img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive"  style="margin-top: 45px;height: 45px; width: 292px;" alt="">
      </div>
      <div style="padding: 6%;box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);border-radius: 20px;background: white;">
          <h2 class="text-center" style="color: black;margin-top: 0px;margin-bottom: 7%;">Forgot Password?</h2>
          <form id="form_forgot_password_email" role="form" method="POST" action="{{ url('forgot_password') }}">
          {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <input id="email" type="text" class="form-control form-control-kartpay input-lg" name="email" placeholder="Enter email" style="border-radius: 10px;font-size: 100%;box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);height: 10%;" value="" required autofocus>

              @if ($errors->has('email'))
                  <span class="help-block">
                      <strong>{{ $errors->first('email') }}</strong>
                  </span>
              @endif
            </div>
            <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
                <img src="{{ captcha_src() }}" name="img_captcha" id="img_captcha" class="img-responsive center-block" alt="captcha" />
                <a href="#" onclick="refreshCaptcha();" style="position:absolute;margin-left:45%;white-space:nowrap;margin-top: 2%;"><i class="glyphicon glyphicon-refresh glyphicon-spin" onclick="refreshCaptcha();"></i> Refresh Captcha</a>
                <br>
                <input id="captcha" type="text" class="form-control form-control-kartpay input-lg" name="captcha" placeholder="Enter captcha" style="border-radius: 10px;font-size: 100%;box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);height: 10%;margin-top: 12px;" value="" required autofocus>

              @if ($errors->has('captcha'))
                  <span class="help-block">
                      <strong>{{ $errors->first('captcha') }}</strong>
                  </span>
              @endif
            </div>
              <button type="button" class="btn btn-block btn-default btn-kartpay btn-lg" id="btn_submit" style="border-radius: 20px;width: 40%;font-size: 100%;background: orangered;margin-left: 29%;" onclick="submitForm();">
                  Submit
              </button>
          </form>
      </div>
    </div>
  </div>
</div>
@endsection
@push('styles')
<style>
  .login-box
  {
    margin-top: 100px;
  }
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
  function submitForm()
  {
      $('#form_forgot_password_email').submit();
  }
	function refreshCaptcha()
	{
		$.ajax({
          		url: "/refresh_captcha",
          		type: 'post',
          		dataType: 'json',
          		data:{
          				'_token':'{{ csrf_token() }}'
          	       },
        		  success: function(res)
              {
        			     $('#img_captcha').attr('src', res.image_url);
        		  },
        		  error: function(data)
              {
        			     alert('Try Again.');
        		  }
          });
	}
</script>
@endpush
