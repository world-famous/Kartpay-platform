@extends('layouts.app_verification')

@section('content')
	<div class="container">
		<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">
			<div class="col-lg-10 col-lg-offset-1 col-md-12">
				<div class="site--logo">
					<img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive" alt="" style="margin-top: 45px;height: 45px;width: 292px;">
				</div>
				<p><h2 class="text-center"  style="color: black;">Mobile OTP (One Time Password)</h2></p>
                <?php
	                use App\Merchant;
	                use App\User;
									use Carbon\Carbon;

								if(!session('verification_code'))
								{ ?>
										<p style="text-align:center;">Session Invalid</p>
									<?php
								}
								else
								{
	                $server = explode('.', Request::server('HTTP_HOST'));
	                $subdomain = $server[0];
	                if($subdomain == 'merchant') $user = Merchant::where('verification_code', '=', session('verification_code'))->first();
	                if($subdomain == 'panel') $user = User::where('verification_code', '=', session('verification_code'))->first();

									if($user->allow_login == '1' && $user->request_otp_times <= 3)
									{
										//check allow resend otp
										$last_send_otp = Carbon::parse($user->last_send_otp);
										$remainingTime = $last_send_otp->diffInSeconds(Carbon::now());
										$allowLogin = $user->allow_login;
										$requestOtpTimes = $user->request_otp_times;
                ?>
				<p style="color: black; text-align: justify;">SMS sent to '<b>{{ $user->country_code }} {{ $user->contact_no }}</b>'. Please check your sms. The OTP code only valid for 5 minutes. If the sms is not receive, <span id="link_resend"></span></p>

				<form class="form-horizontal" id="form_otp" role="form" method="POST" action="{{ url('/verification/forgot_password/otp') }}">
					{{ csrf_field() }}
					<div class="form-group{{ $errors->has('otp') ? ' has-error' : '' }}">
						<input id="otp" type="text" class="form-control form-control-kartpay input-lg" name="otp" maxlength="6" placeholder="Enter otp" value="" required autofocus style="border-radius: 10px;color: black;box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19); font-size: 100%;height: 1%;">

						@if ($errors->has('otp'))
							<span class="help-block">
							<strong>{{ $errors->first('otp') }}</strong>
						</span>
						@endif
					</div>
					<input type="hidden" name="verification_code" id="verification_code" value="{{ $user->verification_code }}" />
					<button type="button" class="btn btn-block btn-default btn-lg btn-kartpay" id="btn_submit"  style="background: orangered;border-radius: 20px; width: 52%;margin-left: 21%;font-size: 100%;height: 40px;" onclick="submitForm();">Submit</button>
				</form>
				<?php
			  }
				else
				{ ?>
					<p>You have Reached OTP Verification Limit, Please Check your Email for Complete Registration</p>
				<?php
				}
			}
				?>
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

		<?php if(isset($user) && isset($remainingTime)) { ?>
			var remainingTime = '{{ $remainingTime }}';
			var time = setInterval(function(){
			var timeLeft = 60 - remainingTime;
				if(timeLeft <= 0)
				{
					var resend_link = "{{ url('/verification/forgot_password/resend_otp/resend') }}";
					$('#link_resend').html('<a href="' + resend_link + '?verification_code={{ $user->verification_code }}">click here</a> to send OTP sms again.');
					clearInterval(time);
				}
				else
				{
					$('#link_resend').html('You can re-send the OTP after <b>' + timeLeft + '</b> seconds.');
				}

				remainingTime++;
			}, 1000);
		<?php } ?>
</script>
@endpush
