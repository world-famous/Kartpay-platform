@extends('layouts.app_verification')

@section('content')
<div class="container">
    <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3">
        <div class="col-lg-10 col-lg-offset-1 col-md-12">
            <div class="site--logo">
                <img src="{{ asset('images/logo2.png') }}" class="center-block img-responsive"   style="height: 45px;width: 292px;margin-top: 45px;" alt="">
            </div>
            <p><h2 class="text-center" style="color: black;">Registration Success</h2></p>
			<p style="color: black;">Registration Success, now you can
				@if(Auth::guest())
					<a href="http://merchant{{ getLiveEnv('SESSION_DOMAIN') }}/login">
				@else
					<a href="{{ url('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
				@endif

				login</a></p>
				<form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
					{{ csrf_field() }}
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
@endpush
