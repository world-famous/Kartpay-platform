@extends('layouts.app_verification')

@section('content')
<div class="container">
    <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">
        <div class="col-lg-10 col-lg-offset-1 col-md-12">
            <div class="site--logo">
                <img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive" alt="" style="margin-top: 45px;height: 45px; width: 292px;">
            </div>
            <p><h2 class="text-center" style="color: black;">Change Password Success</h2></p>
			<p style="text-align: justify;color: black;">Change Password Success, now you can
			<?php
			 $server = explode('.', Request::server('HTTP_HOST'));
			 $subdomain = $server[0];
			?>
			<a href="http://{{$subdomain}}{{ getLiveEnv('SESSION_DOMAIN') }}/login">login</a></p>
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
@endpush
