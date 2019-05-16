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
			<a href="{{ route('admins.gateway_type', ['id' => $gateway->id]) }}" class="btn btn-default"><span class="fa fa-chevron-left"></span> Back</a>
			
			<form role="form" action="{{ route('admins.store.gateway_type', ['id' => $gateway->id]) }}" method="post">
			{!! csrf_field() !!}
				
				<div class="form-group{{ $errors->has('gateway_type_name') ? ' has-error' : '' }}">
					<label for="gateway_type_name">Name</label>
					<input type="required" name="gateway_type_name" value="" class="form-control" id="gateway_type_name" required>
					@if ($errors->has('gateway_type_name'))
						<span class="help-block">
							<strong>{{ $errors->first('gateway_type_name') }}</strong>
						</span>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('is_enable') ? ' has-error' : '' }}">
					<label for="is_enable">Enable</label>
					<div class="radio">
					  <label><input type="radio" name="is_enable" value="1" required>Enable</label>
					</div>
					<div class="radio">
					  <label><input type="radio" name="is_enable" value="0" required>Disable</label>
					</div>
					@if ($errors->has('is_enable'))
						<span class="help-block">
							<strong>{{ $errors->first('is_enable') }}</strong>
						</span>
					@endif
				</div>
				
				<div class="form-group{{ $errors->has('route') ? ' has-error' : '' }}">
					<label for="route">Route</label>
					<input type="required" name="route" value="" class="form-control" id="route" placeholder="Example: http://anything.com/something" required>
					@if ($errors->has('route'))
						<span class="help-block">
							<strong>{{ $errors->first('route') }}</strong>
						</span>
					@endif
				</div>
				<input type="hidden" name="gateway_id" value="{{ $gateway->id }}" />
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
	
});
</script>
@endpush
