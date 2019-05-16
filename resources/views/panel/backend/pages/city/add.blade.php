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
			<a href="{{ route('admins.city') }}" class="btn btn-default"><span class="fa fa-chevron-left"></span> Back</a>
			
			<form role="form" action="{{ route('admins.store.city') }}" method="post">
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
				
				<div class="form-group{{ $errors->has('state_id') ? ' has-error' : '' }}" id="div_state" hidden>
					<label for="state_id">State</label>
					<select name="state_id" id="state_id" class="form-control" required>
						<option value=''>--Select State--</option>
					</select>
					@if ($errors->has('state_id'))
						<span class="help-block">
							<strong>{{ $errors->first('state_id') }}</strong>
						</span>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('city_name') ? ' has-error' : '' }}">
					<label for="city_name">City Name</label>
					<input type="required" name="city_name" value="" class="form-control" id="city_name" required>
					@if ($errors->has('city_name'))
						<span class="help-block">
							<strong>{{ $errors->first('city_name') }}</strong>
						</span>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('city_code') ? ' has-error' : '' }}" hidden>
					<label for="city_code">City Code</label>
					<input type="required" name="city_code" value="" class="form-control" id="city_code">
					@if ($errors->has('city_code'))
						<span class="help-block">
							<strong>{{ $errors->first('city_code') }}</strong>
						</span>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('city_status') ? ' has-error' : '' }}">
					<label for="city_status">Status</label>
					<select name="city_status" id="city_status" class="form-control" required>
						<option value=''>--Select Status--</option>
						<option value='Active'>Active</option>
						<option value='Disabled'>Disabled</option>
					</select>
					@if ($errors->has('city_status'))
						<span class="help-block">
							<strong>{{ $errors->first('city_status') }}</strong>
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

function getStateByCountry(country_id, div_el_state, el_state)
{
	$.ajax({
		url : '{{ route("admins.get_state_by_country") }}',
		type: 'GET',
		data: {'country_id':country_id},
		dataType: 'JSON',
		beforeSend: function(){
			div_el_state.hide();
			el_state.html('<option value="">--Select State--</option>');
		},
		complete: function(){
		},
		success: function(res){
		  if(res.response == 'Success'){
			var keys = Object.keys(res.states);	
			el_state.html('');
			el_state.append('<option value="">--Select State--</option>');
			for(var x = 0; x < keys.length; x++)
			{ 
				el_state.append('<option value="'+res.states[x].id+'">'+res.states[x].state_name+' - ' +res.states[x].state_code+'</option>');
			}
			div_el_state.show();
		  }
		},
		error: function(a, b, c){
		  showTemporaryMessage(c, 'error', 'Error');	
		  return false;
		}
	  });
}

$('#country_id').change(function(event) {
	getStateByCountry($('#country_id').val(), $('#div_state'), $('#state_id'));
});

$(function(){
	$('#ul_application_settings').toggle();
	$('#li_application_settings').addClass('active');	
});
</script>
@endpush
