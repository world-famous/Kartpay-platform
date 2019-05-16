@extends('layouts.app_verification')

@section('content')
<div class="container">
    <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">
        <div class="col-lg-10 col-lg-offset-1 col-md-12">
            <div class="site--logo">
                <img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive" alt="" style="margin-top: 45px;height: 45px;width: 292px;">
            </div>
			@if(isset($user))
			<p><h2 class="text-center" style="color: black;">Verify Your Contact Number</h2></p>
			<p  style="color: black;text-align: justify;">If you'are forgot your contact number, please contact Kartpay Customer Service (+91 123456789)</p>
			@else
			<p><h2 class="text-center">Error</h2></p>
			@endif

			@if(isset($user))
			<form class="form-horizontal" id="form_contact_no" role="form" method="POST" action="{{ url('/verification/forgot_password/verification_contact_no') }}">
				{{ csrf_field() }}

				<div class="form-group{{ $errors->has('contact_no') ? ' has-error' : '' }}">
						<input id="contact_no" type="text" class="form-control form-control-kartpay input-lg" name="contact_no" maxlength="4" placeholder="Enter last 4 digits of your contact number" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="" required autofocus style="box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);font-size: 100%;border-radius: 10px;color: black;">

						@if ($errors->has('contact_no'))
							<span class="help-block">
								<strong>{{ $errors->first('contact_no') }}</strong>
							</span>
						@endif
				</div>

				<input id="verification_code" type="hidden" class="form-control" name="verification_code" value="{{ $user->verification_code }}" />

				<br><br>
				<button type="button" class="btn btn-block btn-default btn-lg btn-kartpay" id="btn_submit" style="border-radius: 20px;background: orangered;width: 65%; margin-left: 16%;margin-top: -14%;font-size: 100%;" onclick="submitForm();">Submit</button>
			</form>
			@else
			<p style="text-align:center">Verification link is invalid</p>
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
	$('#form_contact_no').submit();
}
</script>
@endpush
