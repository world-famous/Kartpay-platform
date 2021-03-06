@extends('layouts.app_verification')

@section('content')
<div class="container">
	<div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">
		<div class="col-md-10 col-md-offset-1">
	        <div class="site--logo">
		        <img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive" alt="" style="height: 45px;width: 292px;margin-top: 45px;">
            </div>
            <p><h2 class="text-center" style="color: black;">Verification Email</h2></p>
            <p style="color: black;text-align: justify;margin-left: 4%;">We sent a verification email to the address you provided. Please check your email. The verification link will only be valid for 1 hour.</p>
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
@endpush
