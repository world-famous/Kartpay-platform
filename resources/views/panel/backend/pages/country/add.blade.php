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
			<a href="{{ route('admins.country') }}" class="btn btn-default"><span class="fa fa-chevron-left"></span> Back</a>

			<form role="form" action="{{ route('admins.store.country') }}" method="post">
			{!! csrf_field() !!}

				<div class="form-group{{ $errors->has('country_name') ? ' has-error' : '' }}">
					<label for="country_name">Country Name</label>
					<input type="required" name="country_name" value="" class="form-control" id="country_name" size="20" required>
					@if ($errors->has('country_name'))
						<span class="help-block">
							<strong>{{ $errors->first('country_name') }}</strong>
						</span>
					@endif
				</div>

				<div class="form-group{{ $errors->has('country_code') ? ' has-error' : '' }}">
					<label for="country_code">Code</label>
					<input type="required" name="country_code" value="" class="form-control" id="country_code" size="20" required>
					@if ($errors->has('country_code'))
						<span class="help-block">
							<strong>{{ $errors->first('country_code') }}</strong>
						</span>
					@endif
				</div>

				<div class="form-group{{ $errors->has('country_status') ? ' has-error' : '' }}">
					<label for="country_status">Status</label>
					<select name="country_status" id="country_status" class="form-control" required>
						<option value=''>--Select Status--</option>
						<option value='Active'>Active</option>
						<option value='Disabled'>Disabled</option>
					</select>
					@if ($errors->has('country_status'))
						<span class="help-block">
							<strong>{{ $errors->first('country_status') }}</strong>
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

$(function()
{  
	$('#ul_application_settings').toggle();
	$('#li_application_settings').addClass('active');
});
</script>
@endpush
