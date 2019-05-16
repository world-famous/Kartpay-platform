@extends('layouts.app_verification')

@section('content')
<div class="container">
    <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">
        <div class="col-lg-10 col-lg-offset-1 col-md-12">
            <div class="site--logo">
                <img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive"  style="margin-top: 45px;height: 45px;width: 292px;" alt="">
            </div>
			<p><h2 class="text-center" style="color: black;">Resend Verification Link</h2></p>
			<form id="form_resend_link" role="form" method="POST" action="{{ route('merchants.resend_link') }}">
				{{ csrf_field() }}
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
						<input id="email" type="email" class="form-control form-control-kartpay input-lg" name="email" placeholder="Enter email" value="{{ old('email') }}" required style="border-radius: 10px;box-shadow: 0px 1px 10px 1px rgba(81, 64, 49, 0.19);font-size: 100%;height: 39px;">

						@if ($errors->has('email'))
							<span class="help-block">
								<strong>{{ $errors->first('email') }}</strong>
							</span>
						@endif
				</div>
                <br>
				<button type="button" class="btn btn-block btn-default btn-lg btn-kartpay" id="btn_submit"  style="border-radius: 20px;background: orangered;font-size: 100%;width: 45%;margin-left: 25%;" onclick="submitForm();">
					Submit
				</button>
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
	$('#form_resend_link').submit();
}
</script>
@endpush
