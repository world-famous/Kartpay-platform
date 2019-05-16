@extends('merchant.backend.layouts.app')

@section('content')

<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3>User Administration</h3>
		</div>
	</div>	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			Show User
		</div>
	</div>
	<br><br>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<a href="{{ url('/user_administration') }}" class="btn btn-default"><span class="fa fa-chevron-left"></span> Back</a>
			<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
				<label for="first_name">First Name</label>
				<input type="text" name="first_name" value="{{ $user->first_name }}" class="form-control" id="first_name" disabled>
				@if ($errors->has('first_name'))
					<span class="help-block">
						<strong>{{ $errors->first('first_name') }}</strong>
					</span>
				@endif
			</div>
			<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
				<label for="last_name">Last Name</label>
				<input type="text" name="last_name" value="{{ $user->last_name }}" class="form-control" id="last_name" disabled>
				@if ($errors->has('last_name'))
					<span class="help-block">
						<strong>{{ $errors->first('last_name') }}</strong>
					</span>
				@endif
			</div>
			<div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
				<label for="country_code">Country Code</label>
				<input type="text" name="country_code" value="{{ $user->country_code }}" class="form-control" id="country_code" disabled>
				@if ($errors->has('country_code'))
					<span class="help-block">
						<strong>{{ $errors->first('country_code') }}</strong>
					</span>
				@endif
			</div>
			<div class="form-group{{ $errors->has('contact_no') ? ' has-error' : '' }}">
				<label for="contact_no">Contact No</label>
				<input type="text" name="contact_no" value="{{ $user->contact_no }}" class="form-control" id="contact_no" disabled>
				@if ($errors->has('contact_no'))
					<span class="help-block">
						<strong>{{ $errors->first('contact_no') }}</strong>
					</span>
				@endif
			</div>
			<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
				<label for="email">Email</label>
				<input type="text" name="email" value="{{ $user->email }}" class="form-control" id="email" disabled>
				@if ($errors->has('email'))
					<span class="help-block">
						<strong>{{ $errors->first('email') }}</strong>
					</span>
				@endif
			</div>
			<div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
				<label for="is_active">Status</label>
				<select class="form-control" name="is_active" id="is_active" disabled>
				@if($user->is_active == '1')
					<option value="1" selected>Active</option>
					<option value="0">Not Active</option>
				@else
					<option value="1">Active</option>
					<option value="0" selected>Not Active</option>
				@endif
				</select>
			</div>
		 </div>
	</div>				
</div>

@endsection

@push('scripts')
<script>

$(function(){
	
});
</script>
@endpush
