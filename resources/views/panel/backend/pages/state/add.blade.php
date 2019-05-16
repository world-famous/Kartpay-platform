@extends('panel.backend.layouts.app')

@section('content')

<div class="">
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
			<a href="{{ route('admins.state') }}" class="btn btn-default"><span class="fa fa-chevron-left"></span> Back</a>
			
			<form role="form" action="{{ route('admins.store.state') }}" method="post">
			{!! csrf_field() !!}
			
				<div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
					<label for="country_id">Country</label>
					<select name="country_id" id="country_id" class="form-control" required>
						<option value=''>--Select Country--</option>
						@foreach($countrys as $country)
							<option value='{{ $country->id }}'>{{ $country->country_name }} - {{ $country->country_code }}</option>
						@endforeach
					</select>
					@if ($errors->has('country_id'))
						<span class="help-block">
							<strong>{{ $errors->first('country_id') }}</strong>
						</span>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('state_name') ? ' has-error' : '' }}">
					<label for="state_name">State Name</label>
					<input type="required" name="state_name" value="" class="form-control" id="state_name" required>
					@if ($errors->has('state_name'))
						<span class="help-block">
							<strong>{{ $errors->first('state_name') }}</strong>
						</span>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('state_code') ? ' has-error' : '' }}">
					<label for="state_code">State Code</label>
					<input type="required" name="state_code" value="" class="form-control" id="state_code" required>
					@if ($errors->has('state_code'))
						<span class="help-block">
							<strong>{{ $errors->first('state_code') }}</strong>
						</span>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('state_status') ? ' has-error' : '' }}">
					<label for="state_status">Status</label>
					<select name="state_status" id="state_status" class="form-control" required>
						<option value=''>--Select Status--</option>
						<option value='Active'>Active</option>
						<option value='Disabled'>Disabled</option>
					</select>
					@if ($errors->has('state_status'))
						<span class="help-block">
							<strong>{{ $errors->first('state_status') }}</strong>
						</span>
					@endif
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

$(function(){
	$('#ul_application_settings').toggle();
	$('#li_application_settings').addClass('active');	
});
</script>
@endpush
