@extends('layouts.app_verification')

@section('content')
<div class="container">
	<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3">
		<div class="col-lg-10 col-lg-offset-1 col-md-12">
			<div class="site--logo">
				<img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive"  style="margin-top: 45px;height: 45px;width: 292px;" alt="">
			</div>
			<p><h2 class="text-center" style="color: black;">Mobile OTP (One Time Password)</h2></p>

			@if(isset($allowLogin))
				@if($allowLogin == '1' && $requestOtpTimes <= 3)
					<p style="color: black;text-align: justify;">SMS sent to '<b>{{ $user->country_code }} {{ $user->contact_no }}</b>'. Please check your sms. The OTP code only valid for 5 minutes. If the sms is not receive, <span id="link_resend"></span></p>
					<form class="" id="form_otp" role="form" method="POST" action="{{ url('verification/otp_login') }}">
					{{ csrf_field() }}

						<div class="form-group{{ $errors->has('otp') ? ' has-error' : '' }}">
								<input id="otp" type="text" class="form-control form-control-kartpay input-lg" name="otp" maxlength="6" placeholder="Enter otp" value="" style="border-radius: 10px;font-size: 100%;box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);height: 42px;" required autofocus>

								@if ($errors->has('otp'))
									<span class="help-block">
										<strong>{{ $errors->first('otp') }}</strong>
									</span>
								@endif
						</div>
						<input id="verification_code" type="hidden" class="form-control" name="verification_code" value="{{ $user->verification_code }}" />
						<br>
						<button type="button" class="btn btn-block btn-default btn-lg btn-kartpay" id="btn_submit" style="border-radius: 20px;background: orangered;width: 29%;font-size: 100%;margin-left: 35%;margin-top: -4%;" onclick="submitForm();">
							Submit
						</button>
					</form>
				@elseif(isset($expiredSecretLink))
						@if($expiredSecretLink == '1')
						<p>Your login blocked, please contact Kartpay administrator.</p>
						@else
						<p>You have Reached OTP Login Limit, Please Check your Email for Login</p>
						@endif
				@endif
			@endif
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
    },
	.{
		align: center;
	}
</style>
@endpush

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

<script>
function submitForm()
{
	$('#form_otp').submit();
}

<?php if (isset($user)) {?>
	var remainingTime = '{{ $remainingTime }}';
	var time = setInterval(function(){
	var timeLeft = 60 - remainingTime;
		if(timeLeft <= 0)
		{
			var resend_link = "{{ url('verification/resend_otp_login/resend') }}";
			$('#link_resend').html('<a href="' + resend_link + '?verification_code={{ $user->verification_code }}">click here</a> to send OTP sms again.');
			clearInterval(time);
		}
		else
		{
			$('#link_resend').html('You can re-send the OTP after <b>' + timeLeft + '</b> seconds.');
		}

		remainingTime++;
	}, 1000);
<?php }?>

$(function(){


});
</script>
@endpush
