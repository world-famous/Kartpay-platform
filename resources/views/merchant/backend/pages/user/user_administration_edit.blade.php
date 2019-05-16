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
			Edit User
		</div>
	</div>
	@if (session('message'))
	<div class="row">
		<div class="alert alert-success alert-dismissable">
			<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
				{!! session('message') !!}
		</div>
	</div>
	@endif
	<br><br>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<a href="{{ url('/user_administration') }}" class="btn btn-default"><span class="fa fa-chevron-left"></span> Back</a>

			<form role="form" action="{{ url('user_administration/' . $user->id . '/update') }}" method="post">
			{!! csrf_field() !!}
				<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
					<label for="first_name">First Name</label>
					<input type="text" name="first_name" value="{{ $user->first_name }}" class="form-control" id="first_name" onkeydown="return alphaOnly(event);" required>
					@if ($errors->has('first_name'))
						<span class="help-block">
							<strong>{{ $errors->first('first_name') }}</strong>
						</span>
					@endif
				</div>
				<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
					<label for="last_name">Last Name</label>
					<input type="text" name="last_name" value="{{ $user->last_name }}" class="form-control" id="last_name" onkeydown="return alphaOnly(event);" required>
					@if ($errors->has('last_name'))
						<span class="help-block">
							<strong>{{ $errors->first('last_name') }}</strong>
						</span>
					@endif
				</div>
				<div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
					<label for="country_code">Country Code</label>
					<input type="text" name="country_code" value="{{ $user->country_code }}" class="form-control" id="country_code" readonly>
					@if ($errors->has('country_code'))
						<span class="help-block">
							<strong>{{ $errors->first('country_code') }}</strong>
						</span>
					@endif
				</div>
				<div class="form-group{{ $errors->has('contact_no') ? ' has-error' : '' }}">
					<label for="contact_no">Contact No</label>
					<input type="text" name="contact_no" value="{{ $user->contact_no }}" class="form-control" id="contact_no" maxlength="10"
                                onkeypress="return event.charCode >= 48 &amp;&amp; event.charCode <= 57">
					@if ($errors->has('contact_no'))
						<span class="help-block">
							<strong>{{ $errors->first('contact_no') }}</strong>
						</span>
					@endif
				</div>
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					<label for="email">Email</label>
					<input type="required" name="email" value="{{ $user->email }}" class="form-control" id="email" required>
					@if ($errors->has('email'))
						<span class="help-block">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
					@endif
				</div>
				<div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
					<label for="is_active">Status</label>
					<select class="form-control" name="is_active" id="is_active">
					@if($user->is_active == '1')
						<option value="1" selected>Active</option>
						<option value="0">Not Active</option>
					@else
						<option value="1">Active</option>
						<option value="0" selected>Not Active</option>
					@endif
					</select>
				</div>
				 <div class="box-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Submit</button>
				</div>
			</form>
		 </div>
	</div>
</div>

@endsection

@push('scripts')
<script>

function alphaOnly(event)
{
  var key = event.keyCode;
  return ((key >= 65 && key <= 90) || key == 8);
}

function disabledFirstChar(e)
{
    if (e.keyCode == 8 && $('#country_code').is(":focus") && $('#country_code').val().length < 2) {
      e.preventDefault();
  }
}

$(function(){

});
</script>
@endpush
